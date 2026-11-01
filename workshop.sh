#!/bin/sh
# macOS / Linux launcher. No host PHP, Composer or Python is used.
set -eu
workshop_directory=$(CDPATH= cd -- "$(dirname -- "$0")" && pwd)
cd "$workshop_directory"
workshop_action=${1:-start}
case "$workshop_action" in
    start|stop|status|logs|test|seed|reset) ;;
    *) echo 'Usage: sh workshop.sh [start|stop|status|logs|test [TestClass]|seed|reset]' >&2; exit 2 ;;
esac
if [ "$#" -gt 2 ] || { [ "$#" -eq 2 ] && [ "$workshop_action" != test ]; }; then
    echo 'Only test accepts a second argument (the test class).' >&2
    exit 2
fi

if ! command -v docker >/dev/null 2>&1; then
    echo 'Docker is missing. Install it using DOCKER_SETUP.md, then rerun this command.' >&2
    case "$(uname -s)" in
        Darwin) echo 'macOS: https://docs.docker.com/desktop/setup/install/mac-install/' >&2 ;;
        *) echo 'Linux: https://docs.docker.com/engine/install/' >&2 ;;
    esac
    exit 1
fi
if ! docker compose version >/dev/null 2>&1; then
    echo 'Docker Compose v2 is missing. Install/update Docker Desktop or the Compose plugin; see DOCKER_SETUP.md.' >&2
    exit 1
fi
if ! docker info >/dev/null 2>&1; then
    if [ "$workshop_action" = start ] && [ "$(uname -s)" = Darwin ] && [ -d /Applications/Docker.app ]; then
        echo 'Starting Docker Desktop. Complete any first-run prompts in its window.'
        open -a Docker
        workshop_attempt=0
        until docker info >/dev/null 2>&1; do
            workshop_attempt=$((workshop_attempt + 1))
            if [ "$workshop_attempt" -ge 45 ]; then break; fi
            sleep 2
        done
    fi
    if ! docker info >/dev/null 2>&1; then
        echo 'Docker is installed but its engine is unavailable.' >&2
        echo 'Start Docker Desktop, or on Linux start Docker Engine and check your socket permissions. See DOCKER_SETUP.md.' >&2
        exit 1
    fi
fi
if [ "$(docker info --format '{{.OSType}}')" != linux ]; then
    echo 'This workshop needs Linux containers. Switch Docker Desktop to Linux containers and retry.' >&2
    exit 1
fi

case "$workshop_action" in
    start)
        workshop_port=${WORKSHOP_PORT:-8080}
        case "$workshop_port" in ''|*[!0-9]*) echo 'WORKSHOP_PORT must be an integer from 1 to 65535.' >&2; exit 2 ;; esac
        if [ "$workshop_port" -lt 1 ] || [ "$workshop_port" -gt 65535 ]; then
            echo 'WORKSHOP_PORT must be from 1 to 65535.' >&2; exit 2
        fi
        if ! docker compose up --build --detach --wait --wait-timeout 180; then
            docker compose logs --tail 80 app || true
            echo 'Startup failed. See DOCKER_SETUP.md; resolve the reported error and rerun this command.' >&2
            exit 1
        fi
        echo "Workshop ready: http://127.0.0.1:$workshop_port/"
        echo 'Edit your TODO files normally; PHP changes are visible on the next request.'
        echo 'The server keeps running after this terminal closes. Stop it with: sh workshop.sh stop'
        ;;
    stop) docker compose down ;;
    status) docker compose ps ;;
    logs) docker compose logs --tail 100 app ;;
    test)
        if [ -n "${2:-}" ]; then
            docker compose exec -T app workshop-artisan test "--filter=$2"
        else
            docker compose exec -T app workshop-artisan test
        fi
        ;;
    seed) docker compose exec -T app workshop-artisan db:seed --force --no-interaction ;;
    reset)
        printf 'This erases Docker workshop reports and recommendations and restores sample data. Type RESET: '
        IFS= read -r workshop_confirmation
        if [ "$workshop_confirmation" != RESET ]; then echo 'Reset cancelled.'; exit 0; fi
        docker compose exec -T app workshop-artisan migrate:fresh --seed --force --no-interaction
        ;;
esac
