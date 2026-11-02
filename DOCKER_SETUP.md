# One-command Docker setup

Use this route for a workshop with mixed Windows, macOS, and Linux machines. Only a working **Docker installation with Linux containers and the Compose plugin** is required on the host. PHP 8.4, its extensions, Composer, locked application dependencies, SQLite, migrations, and seed data are handled inside the container. Existing or partially installed host PHP/Composer libraries are ignored.

This is an optional way to run the same application. The original PHP/Composer setup remains supported. Use one route at a time; their databases are separate.

## Cloning from WSL or macOS

See the [GitHub clone walkthrough in README](README.md#clone-and-run-from-github) for exact commands for both platforms. The repository URL is `https://github.com/ahmedjlidi/LaravelWorkshop.git`; application files live at its root.

Inside WSL, use the Ubuntu terminal, enable Docker Desktop's **Resources → WSL Integration** for that distribution, clone under `~/projects`, and run `sh workshop.sh`. On macOS, use Terminal and the same shell command. Keep Docker Desktop running on the host. A normal clone uses the student `main` branch; `git switch solutions` selects completed behavior.

## Quick start after Docker is installed

Unzip the starter source (or clone the Git bundle) and open a terminal **inside `laravel-capstone-workshop/`**, where `compose.yaml` lives. An editor and a browser are enough; Git is optional for a student-only ZIP.

**macOS / Linux:**

```sh
sh workshop.sh
```

**Windows PowerShell:**

```powershell
powershell -NoProfile -ExecutionPolicy Bypass -File .\workshop.ps1
```

The Windows command permits this reviewed local script for that invocation; it does not change the machine's saved execution policy. On institution-managed machines where policy blocks scripts, ask the lab administrator, or use the direct Compose commands below.

The launcher checks Docker, Compose, engine availability, and Linux-container mode. On macOS and Windows it tries to start an already installed Docker Desktop. First-run installer prompts must be completed by the user. It then builds the image, starts in the background, and waits for the HTTP health check before printing:

**http://127.0.0.1:8080/**

The first run downloads container images, system packages, and Composer dependencies and may take several minutes. Start once before the live workshop. After the initial build, Docker reuses the image and dependencies. Do not close Docker Desktop while using the workshop. The container stays running when the terminal closes; no terminal server process needs to remain open.

No host `.env`, host `vendor/`, API keys, npm install, or manually created SQLite file is needed. Source files are mounted from your editor: save a PHP/Blade change and refresh the browser. No image rebuild is needed for a TODO edit.

## Install Docker once

A launcher cannot guarantee operation on every machine: Docker may need virtualization support, administrator assistance, an OS update, free disk/RAM, a restart, or acceptance of its terms. The scripts report the missing prerequisite and link here; they do not replace package managers, uninstall existing software, change BIOS settings, or silently install system services.

Use a currently supported OS and Docker release. The image uses native Linux Intel/AMD (`amd64`) or ARM (`arm64`) variants automatically. Windows ARM support also depends on Docker Desktop's current supported configuration; check the vendor requirements. The project does not force Intel emulation on Apple silicon.

### macOS — Apple silicon or Intel

1. Download the matching **Apple silicon** or **Intel** Docker Desktop installer from [Docker's macOS installation page](https://docs.docker.com/desktop/setup/install/mac-install/).
2. Open the downloaded installer, move Docker to Applications, then open **Docker.app**.
3. Complete Docker's first-run prompts and wait until its engine is running. If macOS is outside Docker's supported versions or hardware requirements, upgrade the machine or use a prepared lab machine.
4. Open a new terminal if `docker` is not found, then check:

```sh
docker version
docker compose version
```

`docker version` should show **Client and Server**, not only Client. Run `sh workshop.sh` in the project folder.

### Windows — Docker Desktop with WSL 2 / Linux containers

1. Review [Docker's Windows requirements and installer](https://docs.docker.com/desktop/setup/install/windows-install/), including supported Windows versions and virtualization requirements.
2. If WSL is missing or the installer requests it, open **PowerShell as Administrator**, run the following, and restart when requested:

```powershell
wsl --install
```

3. If WSL is already installed but needs updating, run `wsl --update`. Follow [Microsoft's WSL installation instructions](https://learn.microsoft.com/en-us/windows/wsl/install) if it reports a missing virtualization component. Complete any required restart before continuing.
4. Install Docker Desktop, open it, and use its **WSL 2 backend / Linux containers**. Complete its first-run prompts. Do not use Windows-container mode for this PHP image.
5. Open a new PowerShell window and verify:

```powershell
docker version
docker compose version
docker info --format '{{.OSType}}'
```

The last command must print `linux`. Extract the project to a normal local folder, open PowerShell there, and run the Windows quick-start command. You can edit files with a normal Windows editor; you do not need host PHP or a WSL shell for this launcher. If keeping the checkout inside a WSL Linux filesystem, use that distribution's terminal and `sh workshop.sh` with Docker Desktop WSL integration enabled instead.

### Linux — Docker Engine + Compose plugin

Use your distribution's official Docker installation instructions. They cover conflicting/partial package installations, the package repository, and the right architecture:

- [Ubuntu](https://docs.docker.com/engine/install/ubuntu/)
- [Debian](https://docs.docker.com/engine/install/debian/)
- [Fedora](https://docs.docker.com/engine/install/fedora/)
- [Other distributions / installation methods](https://docs.docker.com/engine/install/)

Install the Engine, CLI, Buildx, and **Compose plugin** packages described there. If Engine already works but `docker compose` is missing, follow the [Compose plugin installation guide](https://docs.docker.com/compose/install/linux/). The legacy `docker-compose` v1 command is insufficient; use a current Compose plugin supporting `up --wait` (v2.20+ or a newer major release).

On distributions using systemd, start the installed engine if necessary:

```sh
sudo systemctl start docker
```

Check `docker version` and `docker compose version`. If `docker info` reports socket permission denied, follow [Docker's Linux post-installation instructions](https://docs.docker.com/engine/install/linux-postinstall/) or your institution's approved rootless setup. Adding a user to the Docker group grants powerful host access and requires a new login; the launcher does not make that change automatically. For a lab where administrators require sudo, run `sudo sh workshop.sh` and use sudo consistently for that installation.

If using Docker Desktop for Linux instead, complete [its supported installation](https://docs.docker.com/desktop/setup/install/linux/) and keep Desktop running. Use one Docker context consistently; Engine and Desktop have separate data stores.

## Daily workshop commands

Use these from the project folder after startup. They operate only on this workshop's Compose project.

| Action | macOS / Linux | Windows PowerShell (after scripts are allowed) |
| --- | --- | --- |
| Start / rebuild if needed | `sh workshop.sh` | `.\workshop.ps1` |
| Check service health | `sh workshop.sh status` | `.\workshop.ps1 status` |
| View recent logs | `sh workshop.sh logs` | `.\workshop.ps1 logs` |
| Test shared pages | `sh workshop.sh test WorkshopPagesTest` | `.\workshop.ps1 test WorkshopPagesTest` |
| Test your exercise | `sh workshop.sh test BusTest` | `.\workshop.ps1 test BusTest` |
| Refresh sample dates | `sh workshop.sh seed` | `.\workshop.ps1 seed` |
| Reset workshop data | `sh workshop.sh reset` | `.\workshop.ps1 reset` |
| Stop, preserving data | `sh workshop.sh stop` | `.\workshop.ps1 stop` |

With a restrictive default Windows policy, use the invocation form for any action, for example:

```powershell
powershell -NoProfile -ExecutionPolicy Bypass -File .\workshop.ps1 test BusTest
```

Replace `BusTest` with `BankingTest`, `NewsroomTest`, `CityTest`, or `AdvisorTest`. Omit the filter to run all tests. On `main`, 14 exercise cases intentionally fail; on `solutions`, all 43 pass. A nonzero test exit code is preserved by both scripts.

`reset` asks you to type `RESET`, then erases only the Docker workshop database records and restores samples. It does not reset code. `seed` refreshes sample dates and the bus location; it preserves student-created reports. Normal restart/start does not reseed or reset the database. Run `seed` at the start of a new workshop day because newsroom dates use UTC.

The app key, SQLite file, dependencies, sessions, and runtime caches live in this project's named Docker volumes. Stop/start, rebuilds, and branch switches preserve those volumes. Do **not** add `--volumes` to `docker compose down` unless you deliberately want to remove all Docker workshop data. Host `.env`, host dependencies, and the native `database/database.sqlite` are separate and left alone.

Tests force a separate `:memory:` SQLite database even though the container has a persistent `DB_DATABASE` environment variable. Use the provided wrapper so Artisan receives the generated Docker app key:

```sh
docker compose exec app workshop-artisan route:list
docker compose exec app workshop-artisan test --filter=BankingTest
```

When switching branches, save your work as described in README, switch, then rerun the launcher. All Docker files are identical on `main` and `solutions`.

## If port 8080 is occupied

macOS / Linux:

```sh
WORKSHOP_PORT=8081 sh workshop.sh
```

Windows PowerShell:

```powershell
$env:WORKSHOP_PORT = '8081'
powershell -NoProfile -ExecutionPolicy Bypass -File .\workshop.ps1
```

Open http://127.0.0.1:8081/. Repeat the same port setting when restarting. This Docker URL is separate from the optional native PHP server at port 8000.

## Direct Docker commands

If you prefer no platform launcher, a working Docker + Compose installation can run the same setup:

```sh
docker compose up --build --detach --wait --wait-timeout 180
docker compose exec app workshop-artisan test --filter=WorkshopPagesTest
docker compose down
```

## Troubleshooting partial setups

| Message or symptom | Action |
| --- | --- |
| `docker` missing | Install the matching Docker package above; reopen the terminal. Host PHP will not fix this. |
| Only Docker Client appears / connection to daemon fails | Open Docker Desktop, or start Engine on Linux; complete first-run prompts and required restarts. |
| `docker compose` unavailable or `--wait` unknown | Update Docker Desktop or the Compose plugin. Installing the old standalone v1 command does not fix it. |
| Windows-container error | Switch Desktop to Linux containers. |
| WSL / virtualization error | Follow Docker/Microsoft's requirements; enable virtualization through the machine administrator if necessary. |
| Port allocated / address in use | Use the alternate-port example. |
| Permission denied on Linux Docker socket | Follow the post-install steps or approved sudo/rootless approach above. |
| Bind-mount denied / source unreadable | Allow the project folder in Docker Desktop file sharing. On SELinux systems, use an appropriate shared `:z` label on the `.:/app` source mount per your administrator's policy. |
| Build/download error | Verify internet/proxy access to Docker Hub, Debian repositories, and Packagist/GitHub. Retry the launcher after resolving the reported problem. No service API key is needed. |
| Startup health check fails | Run the `logs` command, address its first error, and rerun start. The script prints the ready URL only after health checks pass. |
| Windows shell script line-ending error | Use `workshop.ps1` on Windows. Git enforces LF for the Linux container scripts; re-extract source if an editor changed them to CRLF. |
| Host dependencies differ / wrong native PHP version | Use Docker; its dependency/runtime volumes override the host's installed libraries. |
| Browser refuses connection after restart | Start the launcher again and verify health. Keep Docker Desktop running and use the port printed by the script. |

A remote Docker daemon is not a supported launcher target because the code bind mount must refer to this local project folder. Use a local Docker context. For parallel independent checkouts, give each its own `COMPOSE_PROJECT_NAME` and `WORKSHOP_PORT` so their containers, ports, and named volumes do not overlap.

## Verification boundary

The container runs a Linux PHP environment on each supported host. Actual builds, startup, SQLite persistence, host-library isolation, and the application test suite are checked on this Apple-silicon Mac with Docker Desktop. The Windows PowerShell launcher is supplied for Windows and checked separately; this is not a claim that a physical Windows or Linux desktop was tested. Unsupported operating systems, disabled virtualization, managed-machine restrictions, or offline first-time builds still require preparation before the live session.
