# Laravel Capstone Workshop

Five independent capstone exercises. Each takes approximately 5–8 minutes and changes 1–3 lines of PHP. The application uses Laravel 13, Blade, and SQLite with no frontend build or external services.

## Recommended for a mixed-OS workshop: Docker

No host PHP, Composer, or PHP extensions are needed for this route. Install Docker once using [DOCKER_SETUP.md](DOCKER_SETUP.md), then run inside the project folder:

macOS / Linux:

```sh
sh workshop.sh
```

Windows PowerShell:

```powershell
powershell -NoProfile -ExecutionPolicy Bypass -File .\workshop.ps1
```

Open **http://127.0.0.1:8080/** after the launcher reports ready. The scripts build the PHP environment, install dependencies, generate an app key, migrate, seed on first use, and wait for HTTP readiness. Source edits appear on the next browser request. The server runs in the background and normal restarts preserve data.

For a challenge test use `sh workshop.sh test BusTest` or the Windows launcher with `test BusTest`. See [DOCKER_SETUP.md](DOCKER_SETUP.md) for all five test classes, installation instructions, stop/reset commands, ports, and partial-install troubleshooting. Supported Docker/virtualization must be installed first; a script cannot make every unsupported machine compatible.

The native PHP/Composer route below remains available and uses port 8000 with a separate database.

## Requirements for the native PHP/Composer route


- PHP **8.4 or 8.5** and Composer 2. The committed lock file was installed and tested on PHP 8.4.1; Laravel framework version is 13.39.0.
- PHP extensions: Ctype, cURL, DOM, Fileinfo, Filter, Hash, Mbstring, OpenSSL, PCRE, PDO, PDO_SQLite, Session, Tokenizer, XML, XMLWriter, and SQLite3. Composer reports missing extensions. ZIP/unzip is recommended for dependency extraction.
- Git, if switching between the supplied `main` and `solutions` branches.
- Internet access for the first Composer install; none needed while running the workshop.

No Node, npm, database server, Redis, API keys, GPS, or email service is needed. Docker is optional for the native route.

Install PHP and Composer before the workshop. On macOS, Laravel Herd or Homebrew (`brew install php@8.4 composer`) are options. On Windows, Laravel Herd includes PHP; ensure the SQLite extensions are enabled in the CLI's php.ini. On Ubuntu/Debian, install an available supported PHP version and its CLI, SQLite3, Mbstring, XML, and cURL packages, plus Composer. Distribution package names vary. Verify with `php -v`, `composer --version`, and `php -m`. See the [Laravel installation documentation](https://laravel.com/docs/13.x/installation) for platform installation options.

## Install and run without Docker

From a fresh checkout, run these commands in `laravel-capstone-workshop/`:

```sh
composer install
php -r "file_exists('.env') || copy('.env.example', '.env');"
php artisan key:generate
php -r "file_exists('database/database.sqlite') || touch('database/database.sqlite');"
php artisan migrate --seed
php artisan serve
```

Open **http://127.0.0.1:8000/**. Keep the terminal running; Ctrl+C stops the server. To use another port, run `php artisan serve --port=8001` and open that port.

Alternatively, after `composer install`, `composer setup` performs the environment, key, SQLite-file, migration, and seeding steps; then run `composer dev`.

If you received the Git bundle, create the checkout first:

```sh
git clone --branch main /path/to/laravel-capstone-workshop.bundle laravel-capstone-workshop
cd laravel-capstone-workshop
git branch solutions origin/solutions
```

The bundle is a complete local Git repository transfer; it requires no GitHub account. Skip cloning if you are already in the delivered repository.

## Native environment

The supplied `.env.example` sets SQLite, file sessions/cache, synchronous queues, and log mail. Laravel defaults to `database/database.sqlite`; leave `DB_DATABASE` unset. If overriding it, supply an absolute path to an existing file. Do not set a MySQL connection or an external service.

Keep `.env` local. `APP_KEY` is generated separately on each installation. The database and runtime files are ignored by Git. On Linux/macOS, `storage/` and `bootstrap/cache/` must be writable by the user running PHP. Do not use world-writable permissions. After changing environment settings, run `php artisan config:clear`.

All workshop dates use **UTC**, shown in the interface. Seed on the workshop day: five assignments span yesterday, today, tomorrow (two), and the day after tomorrow. Tests freeze time and remain reproducible on every date.

## Choose one challenge

Open the page, read the scenario, and find its clearly marked **TODO**. Edit only your assigned block, save, then refresh or submit the form. No model, migration, controller, or login infrastructure needs to be written.

| Exercise / URL | File and method | Expected edit | Test command |
| --- | --- | --- | --- |
| Banking — `/challenges/banking` | `app/Providers/AppServiceProvider.php`, `boot()` Gate | 1 role condition | `php artisan test --filter=BankingTest` |
| Bus — `/challenges/bus` | `app/Http/Controllers/BusController.php`, `update()` | 2 validation rules | `php artisan test --filter=BusTest` |
| Newsroom — `/challenges/newsroom` | `app/Http/Controllers/NewsroomController.php`, `index()` | 1 query line | `php artisan test --filter=NewsroomTest` |
| City — `/challenges/city` | `app/Http/Controllers/CityController.php`, `store()` | 2 validation rules | `php artisan test --filter=CityTest` |
| AI Advisor — `/challenges/ai-advisor` | `app/Http/Controllers/AdvisorController.php`, `store()` | 3 match arms | `php artisan test --filter=AdvisorTest` |

Each page includes its scenario, task, exact project-relative path and method, browser tests, a collapsible hint, and the Laravel concept it teaches.

Banking uses the labeled Omar (employee) and Sara (compliance officer) identities. Select one, click **Use selected identity**, then open the investigation. This uses Laravel session authentication. The actual Gate check runs before confidential data is queried, including when the investigation URL is entered directly. Guests are denied too. The identity switch is deliberately open for this local demonstration and must be replaced by real authentication before production use.

The bus exercise covers the input-processing part of tracking, with manual coordinates. The AI Advisor is explicitly a **rule-based educational placeholder**, not an AI model or personal career guidance.

## Expected starter behavior and tests

`main` is intentionally incomplete but runnable. All six public workshop pages load. Existing working pieces such as CSRF form tokens, session authentication, Eloquent saves, and Blade rendering are prepared for you.

```sh
php artisan test --filter=WorkshopPagesTest
php artisan test
```

The first command passes all **7 infrastructure checks** on both branches. On untouched `main`, the full suite has **29 passing and 14 intentionally failing cases**:

| Test class | Expected starter failures | Why |
| --- | --- | --- |
| `BankingTest` | 1 | The Gate denies everyone, including the officer. |
| `BusTest` | 4 | Out-of-range coordinates are accepted; required/numeric checks already work. |
| `NewsroomTest` | 2 | All assignments appear; the empty-tomorrow case is not filtered either. |
| `CityTest` | 4 | Short descriptions and missing/blank locations are accepted. |
| `AdvisorTest` | 3 | Every supported interest receives the fallback major. |

These failures identify the intended challenges; do not skip or weaken the tests. Completing your own exercise should make its test class pass without editing any other challenge. All **43 tests / 213 assertions pass** on `solutions`.

Tests use a separate, in-memory SQLite database and reseed for each case. They never erase your browser's workshop database. The included `.env.testing` needs no edits or external service. Run tests from a local environment with no cached production configuration; `php artisan config:clear` clears any stale config.

## Refresh and reset without Docker

Refresh sample dates and the seeded bus location without deleting student-created reports:

```sh
php artisan db:seed
```

Re-seeding uses update/first-or-create operations and does not duplicate samples. To start a new session with **all local workshop data erased**, run:

```sh
php artisan migrate:fresh --seed
php artisan optimize:clear
```

Reload the browser. If you were signed in during a reset, select a demo identity again. This command resets data only; it does not reset student code.

## Starter and reference branches

- `main`: student starter. Five TODO blocks; no executable reference answers or solution routes.
- `solutions`: completed implementations plus the full `INSTRUCTOR_GUIDE.md`.
- The schema, seeders, views, routes, dependencies, Docker setup, and tests are the same on both branches. Switching branches does not switch the ignored SQLite database.

Save your work before switching:

```sh
git add app
git commit -m "Complete my workshop challenge"
git switch solutions
php artisan optimize:clear
php artisan test
# Return to your saved student work:
git switch main
php artisan optimize:clear
```

Commit only the changes you intend to keep. If a bundle clone only has the remote reference, first run `git branch solutions origin/solutions`. Avoid force-switching or resetting your branch, which can discard your work. For a completely fresh starter, clone the bundle into a new directory and repeat setup.

Instructors: switch to `solutions` and open `INSTRUCTOR_GUIDE.md` for exact answers, demonstration scripts, and common mistakes. The application never reads or serves that guide. Its web root must remain `public/` as provided by `php artisan serve`.

## Native setup troubleshooting

- **Missing PHP/Composer:** install them first, open a new terminal, and confirm the commands resolve to the intended PHP version.
- **Composer platform errors:** use PHP 8.4+ and enable the named CLI extensions. Do not bypass platform requirements.
- **SQLite file missing:** create it with the PHP command in setup, then migrate and seed.
- **No key / 419 after stale forms:** generate the key once, reload the page, and submit again. Don't regenerate the key during a live session.
- **Newsroom dates look old:** run `php artisan db:seed` on that workshop day.
- **Your edit is invisible:** save the file in the active checkout and run `php artisan optimize:clear`, then reload.
- **Port in use:** choose `--port=8001`.

## Scope

The scenarios are based on the five university capstone descriptions supplied in the brief; all people and records are fictional sample data. This is a local teaching application, with deliberately small tasks. It contains no real AML detection, GPS tracking, email notifications, image uploads, AI integration, deployment infrastructure, or production login flow.
