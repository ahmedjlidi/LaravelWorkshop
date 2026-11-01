# Instructor Guide — Laravel Capstone Workshop

Use this document with the `solutions` branch. The student starter is `main`. Allow 5–8 minutes after setup for each team's chosen exercise; each team edits only its marked TODO. All database and UI infrastructure is provided.

## Before the room arrives

1. Install dependencies and run the README setup on each machine. Internet is needed only for initial dependency downloads.
2. Start `main`, run `php artisan migrate:fresh --seed`, and confirm the landing page and five cards load. Seed on the workshop day: dates are UTC.
3. Run `php artisan test --filter=WorkshopPagesTest`: 7 pass. The untouched starter full suite has 29 pass / 14 fail; these are intentional exercise failures.
4. Keep a separate instructor checkout on `solutions`, or save student work with Git before switching. On solutions, all 43 cases / 213 assertions pass.
5. Point students at the exact file/method shown on their page. Give the scenario, let them make the small edit, then have them test both success and failure.

The interface never awards completion for clicking. A team's challenge is complete when its backend test class passes and they can explain the visible browser outcomes. This avoids a saved-but-invalid location or fallback recommendation looking like success.

## 1. Banking — server authorization

**Edit:** `app/Providers/AppServiceProvider.php`, `boot()`, the `view-investigation` Gate. Replace the starter's `return false;` with one line:

```php
return $user->role === 'compliance_officer';
```

**Teach:** Authentication identifies a user; authorization decides whether that user may perform an action. Laravel passes the authenticated User into the Gate. A guest is denied automatically. `BankingController::investigation()` calls `Gate::authorize()` before loading confidential data. Leave that infrastructure intact.

**Demonstrate:** On `/challenges/banking`, select Sara and apply the identity. Open the investigation: HTTP 200 and confidential notes. Return, select Omar and apply: the same link and direct URL return HTTP 403, with no notes. Guests also get 403. The button stays visible so students see that hiding buttons does not enforce access.

**Check:** `php artisan test --filter=BankingTest` — 6 pass. Starter: only `test_compliance_officer_can_read_confidential_investigation` fails. Denying everyone is a safe runnable starting condition.

**Common mistakes:** `=` assigns instead of comparing; misspelling `compliance_officer`; checking the name rather than role; changing only a Blade button; forgetting to apply the chosen identity before opening the case. Do not replace the Gate with a client-side check.

## 2. Bus — validate coordinates before saving

**Edit:** `app/Http/Controllers/BusController.php`, `update()`. Replace the two starter validation rules:

```php
'latitude' => ['required', 'numeric', 'between:-90,90'],
'longitude' => ['required', 'numeric', 'between:-180,180'],
```

**Teach:** `$request->validate()` redirects browser requests with errors and old input. On success it returns validated data; Eloquent saves only that data. `numeric` ensures numeric bounds rather than character-count validation. This is the input-processing portion of a larger live-tracking capstone, without GPS hardware, maps, or broadcasting.

**Demonstrate:** Submit 29.3117 / 47.4818, then 29.32 / 47.49: the latest saved location changes. Submit latitude 91, -91, longitude 181, or -181: errors appear and the saved panel stays unchanged. Try text and an empty value too. Both -90/90 and -180/180 are inclusive. HTML validation is disabled intentionally so Laravel's validation remains observable.

**Check:** `php artisan test --filter=BusTest` — 13 pass. Starter fails the four out-of-range data cases; the other 9 pass.

**Common mistakes:** Swapping latitude and longitude bounds; omitting `numeric`; using `max:90` without the negative lower bound; adding only an HTML input limit; saving raw input before validating.

## 3. Newsroom — date filtering with Eloquent

**Edit:** `app/Http/Controllers/NewsroomController.php`, `index()`. Replace the starter query line:

```php
$articles = Article::whereDate('deadline', today()->addDay())->orderBy('deadline')->get();
```

**Teach:** The query builder composes a database filter; `get()` executes it and returns models. `whereDate` compares the calendar date rather than requiring an exact timestamp. `today()->addDay()` handles month/year boundaries using Carbon. The application uses UTC consistently. No scheduler or email configuration is involved.

**Demonstrate:** Start with five assignments. After editing, refresh: only “Student robotics team reaches the finals” (Yousef Hassan, 09:00) and “A greener campus: the next chapter” (Dana Khaled, 17:00) remain, both on tomorrow's date. Today, yesterday, and the day after tomorrow are absent. Re-seed with `php artisan db:seed` if a new day has begun.

**Check:** `php artisan test --filter=NewsroomTest` — 2 pass; both fail in starter. Tests freeze time to 2030-01-15 UTC, check both tomorrow times, and check an empty result after the two tomorrow records are removed.

**Common mistakes:** Exact timestamp equality; adding one to a day number; forgetting `get()`; filtering in Blade instead of in Eloquent; querying today's date; calling a nonexistent global `tomorrow()` helper. Carbon's `today()->addDay()` is prepared and concise.

## 4. City — meaningful data and server-owned status

**Edit:** `app/Http/Controllers/CityController.php`, `store()`. Replace two rules:

```php
'description' => ['required', 'string', 'min:10'],
'location' => ['required', 'string', 'max:255'],
```

**Teach:** Laravel validation prevents incomplete reports. Eloquent creates the record after validation. The controller always assigns `Pending`; a database default matches it. Status never comes from resident-supplied input. The length limit on location matches its database string column.

**Demonstrate:** Empty form: required errors. “Broken” at “North gate”: description length error. Valid description with missing or whitespace-only location: location error. “The streetlight outside the library is broken.” at “North gate”: a new Pending report appears, and remains after refreshing. Laravel trims whitespace and turns empty strings into null before validation.

**Check:** `php artisan test --filter=CityTest` — 10 pass. Starter fails short description, nine characters, missing location, and blank location (4). Tests also verify no records on rejection, exactly ten characters accepted, string-only input, and ignoring a forged `status=Resolved`.

**Common mistakes:** Putting `min:10` on location; keeping `nullable` for a required field; accepting a request-supplied status; validating after `create()`; treating a redirect alone as proof of rejection. The starter's `?? ''` fallback simply keeps missing-location submissions runnable and may stay unchanged after the fix.

## 5. Advisor — controller logic and persistence

**Edit:** `app/Http/Controllers/AdvisorController.php`, `store()`. Replace the three TODO comments inside `match` with:

```php
'programming' => 'Computer Science',
'biology' => 'Medicine',
'design' => 'Architecture',
```

Leave the default arm, input validation, Eloquent creation, and redirect unchanged.

**Teach:** PHP `match` performs a strict value comparison and returns a value. The controller passes that value to Eloquent, and Blade shows the saved record. This is explicitly a rule-based educational placeholder, not AI or individualized career advice. A future capstone could replace only the recommendation component with an AI service and retain Laravel's application management.

**Demonstrate:** Submit Programming, Biology, then Design. The newest report should show Computer Science, Medicine, then Architecture. Refresh to demonstrate persistence. The existing seeded Architecture report is only sample data; examine the newest report, not an old matching row.

**Check:** `php artisan test --filter=AdvisorTest` — 5 pass. All 3 mapping cases fail in starter, while missing/unsupported-interest validation passes. Tests verify the latest row, stored interest/major, row count, and rendered output.

**Common mistakes:** Using capitalized inputs instead of the select's lowercase values; `=` instead of `=>`; missing commas; returning a value without saving it; changing only the Blade view; mistaking the fallback report for a correct recommendation.

## Branches, reset, and reuse

The same migrations, seeders, tests, dependencies, routes, and Blade pages are used on both branches. Only the five exercise code files and this guide differ. No runtime solution route, hidden answer controller, or executable answer file exists on `main`. The guide on `main` is a pointer to this branch and contains no answer snippets.

Save changes before `git switch solutions`. Run `php artisan optimize:clear` after switching, then `php artisan test`. SQLite is ignored and is shared by the two branch states in one checkout. A branch switch alone does not reset records or dates. Run `php artisan migrate:fresh --seed` to erase workshop data and restore samples; run `php artisan db:seed` to refresh sample dates and bus coordinates without deleting student reports. Use a new clone for a fresh starter without discarding existing student work.

To distribute without solution history, use `git archive --format=zip --output=../student-only.zip main`. Such an archive cannot switch branches; keep the full Git bundle for instructors. Always serve the `public/` directory; never expose the repository root as a web directory.

## Verification record

- Laravel 13.39.0, PHP 8.4.1, SQLite, Composer 2.
- Completed solution: **43 tests pass, 213 assertions**; Blade view compilation passes.
- Untouched starter: **29 pass, 14 expected failures**; no unrelated test failures. All six public pages load.
- Each exercise can be independently completed by restoring only its documented 1–3 lines; its full test class then passes.
- Browser checks verified both banking identities, rejected/accepted bus updates, tomorrow's two stories, empty/valid city reports, and a saved Biology → Medicine report.
- The supplied brief names capstone scenarios but does not provide real university repositories, datasets, or institutional names. No such provenance is invented; all demo records are fictional.
