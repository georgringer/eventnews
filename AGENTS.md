# Repository Guidelines

Guidance for coding agents working on EXT:eventnews. Personal or
machine-specific preferences do not belong here — put those in an untracked
`CLAUDE.local.md` (already gitignored).

## Context

- One Composer package, `georgringer/eventnews`, PSR-4
  `GeorgRinger\Eventnews\` → `Classes/` and `GeorgRinger\Eventnews\Tests\` →
  `Tests/`.
- The extension adds event and calendar functionality **on top of EXT:news**.
  `georgringer/news` is a hard dependency, not an optional integration.
- **One branch serves TYPO3 13.4 LTS and 14**, on PHP 8.2–8.5. Every change has
  to work on both cores; `.github/workflows/core13.yml` and `core14.yml` define
  the matrix that decides. Where the cores differ, switch on
  `(new Typo3Version())->getMajorVersion()` — see
  `Classes/EventListener/Administration/IndexActionEventListener.php`.
- Issues and code review happen on GitHub,
  <https://github.com/georgringer/eventnews>.
- Documentation sources live in `Documentation/`, rendered at
  <https://docs.typo3.org/p/georgringer/eventnews/main/en-us/>.

## Working mode

- When a bug is reported or reproduced, **do not start by fixing it**. First
  write a test that reproduces it, then fix and prove it with that test
  passing. The only exception is a purely mechanical fix with no testable
  behaviour, such as a typo in a label, comment or documentation.
- Only add code comments when they add meaning; otherwise leave them out.
- Do not break public API and do not drop TYPO3 13 support in passing.

## Looking things up

Use the **PhpStorm MCP** for TYPO3 core and EXT:news lookups — where an error
message originates, whether a method or API exists in a given version, who
calls what. It resolves symbols through the IDE index, which is both faster and
more reliable than grepping `.Build/vendor/`.

- `search_symbol` / `get_symbol_info` to find a class or method
- `analyze_calls` for call-graph questions, after locating the symbol
- `lint_files` / `get_file_problems` to check files after editing
- `search_text` / `search_regex` when you genuinely need text search

Do not grep `.Build/vendor/` for core or framework source.

## Project structure

- `Classes/` — `Controller/NewsController.php` (adds the `month` action),
  `Domain/{Model,Repository}` with the demand objects in `Domain/Model/Dto/`,
  `ViewHelpers/`, `Backend/FormDataProvider/`, `Event/`, `EventListener/`, and
  the legacy `Hooks/`.
- `Configuration/` — `TCA/` and `TCA/Overrides/`, `TypoScript/`, `TSconfig/`,
  `Flexforms/`, `Extbase/Persistence/`, `Icons.php`, `Services.yaml`.
- `Resources/Private/{Templates,Partials,Layouts,Language}`,
  `Resources/Public/`.
- `Tests/{Unit,Functional}` mirroring the `Classes/` namespace.
- `Build/` — `Scripts/runTests.sh` (the single test entry point), `phpunit/`,
  `php-cs-fixer/`, `rector/`, `fractor/`.
- Root: `ext_emconf.php`, `ext_localconf.php`, `ext_tables.sql`,
  `ext_conf_template.txt`. There is deliberately no `ext_tables.php`.
- `.Build/` holds Composer's vendor and web dir (`vendor-dir: .Build/vendor`),
  generated and gitignored — never edit.

## Commands

Everything runs through `Build/Scripts/runTests.sh`, which starts a container
with the requested PHP version and database. Do not invoke `phpunit`,
`php-cs-fixer`, `rector` or `fractor` directly — the wrapper supplies the
environment. `-h` is the authoritative, always-current list of suites.

Three things that are easy to get wrong:

- **Prefix local runs with `CI=true`.** Otherwise the script requests a TTY and
  aborts with `cannot attach stdin to a TTY-enabled container`.
- **`-s composerInstall` ignores `-t` and installs TYPO3 13.** Use
  `composerInstallHighest` or `composerInstallLowest`, which is what CI does —
  otherwise you silently test the wrong core and, for example, never see a v14
  deprecation.
- **A test system must be installed before any suite runs**, and it pins one
  core version. Switching between `-t 13` and `-t 14` means re-running
  `composerInstall*` first; the suite flags alone do not re-resolve
  dependencies.

```bash
# install a test system: pick core version and dependency resolution
CI=true Build/Scripts/runTests.sh -t 14 -p 8.3 -s composerInstallHighest
CI=true Build/Scripts/runTests.sh -t 13 -p 8.2 -s composerInstallLowest

# tests
CI=true Build/Scripts/runTests.sh -p 8.3 -s lint
CI=true Build/Scripts/runTests.sh -p 8.3 -s unit
CI=true Build/Scripts/runTests.sh -p 8.3 -s unit -- --filter setEventEnd
CI=true Build/Scripts/runTests.sh -p 8.3 -s functional              # sqlite
CI=true Build/Scripts/runTests.sh -p 8.3 -d mariadb -a mysqli -s functional
CI=true Build/Scripts/runTests.sh -p 8.3 -s functional -- \
    Tests/Functional/Tca/TcaTest.php

# style and automated migrations (-n is the dry-run CI checks)
PHP_CS_FIXER_IGNORE_ENV=1 CI=true Build/Scripts/runTests.sh -p 8.3 -s cgl -n
PHP_CS_FIXER_IGNORE_ENV=1 CI=true Build/Scripts/runTests.sh -p 8.3 -s cgl
CI=true Build/Scripts/runTests.sh -p 8.3 -s rector -n
CI=true Build/Scripts/runTests.sh -p 8.3 -s fractor -n

# documentation, and cleaning up
CI=true Build/Scripts/runTests.sh -s docsGenerate
CI=true Build/Scripts/runTests.sh -s clean
```

Functional tests default to SQLite, by far the fastest feedback loop; only
switch DBMS when the change touches SQL specifics. `-x` forwards xdebug to a
listening IDE (port 9003, `-y` for another port), and `-u` updates the
`typo3/core-testing-*` images — the first thing to try when a suite fails in
ways the code cannot explain.

`composer cs` and `composer csfix` are shortcuts for the two CGL runs above —
they call `runTests.sh` themselves, so they need Docker but no host PHP:

```bash
composer cs      # check only, changes nothing (runTests.sh -s cgl -n)
composer csfix   # apply the fixes       (runTests.sh -s cgl)
```

## The EXT:news class merge

`ext_localconf.php` registers this extension with news' class extension
mechanism:

```php
$GLOBALS['TYPO3_CONF_VARS']['EXT']['news']['classes']['Domain/Model/News'][] = 'eventnews';
```

news' `ClassCacheManager` then **copies the body of
`Classes/Domain/Model/News.php` into a generated class in the
`GeorgRinger\News\Domain\Model` namespace.** In that generated file every
unqualified class name resolves against the *news* namespace, not ours.

Consequences for that one file:

- Every class reference must stay **fully qualified**. Shortening
  `\GeorgRinger\Eventnews\Domain\Model\Organizer` to `Organizer` produces a
  fatal error at class load time (`Could not check compatibility … because
  class GeorgRinger\News\Domain\Model\Organizer is not available`).
- `Build/rector/rector.php` skips the file for this reason, because Rector's
  `withImportNames` would otherwise shorten those names on every run.
- Unit tests do not catch this — only the functional suite boots the class
  cache. Run `-s functional` after touching the model.

## Coding style

- PSR-12 via `typo3/coding-standards`, configured in
  `Build/php-cs-fixer/php-cs-fixer.php`. `-s cgl` is the authority; CI runs it
  as a dry-run on PHP 8.3.
- `declare(strict_types=1);` is **not** applied consistently across `Classes/`.
  Add it to new files, but do not retrofit it into existing files as a
  drive-by — it changes runtime behaviour.
- Labels live in `Resources/Private/Language/`. The English source files
  (`locallang.xlf`, `locallang_be.xlf`, `locallang_db.xlf`,
  `Overrides/locallang_modadministration.xlf`) **are** edited here — Crowdin
  uploads them as its source. The translated files (`<lang>.locallang*.xlf`)
  come back down from Crowdin and must not be hand-edited. The one exception is
  `Overrides/de.locallang_modadministration.xlf`, which `.crowdin.yaml`
  explicitly ignores, so it is maintained here.
- Heads up: `.crowdin.yml` and `.crowdin.yaml` both exist and describe
  different file sets. Which one the Crowdin CLI picks depends on its lookup
  order — worth consolidating into one file.
- `Build/fractor/fractor.php` skips `Resources/Private/Language/` and
  `Configuration/Flexforms/`. Fractor's XML processor rewrites indentation,
  blank lines and the XML declaration of every file it touches without
  performing any migration, so on those directories it is pure churn.

## Testing

- Unit tests extend `TYPO3\TestingFramework\Core\Unit\UnitTestCase`, functional
  tests extend `FunctionalTestCase` and resolve services with
  `$this->get(SomeClass::class)`. File names end in `Test.php`.
- Use PHPUnit attributes (`#[Test]`, `#[DataProvider]`), not docblock
  annotations. Data providers must be `public static`.
- Functional tests declare both packages:
  `protected array $testExtensionsToLoad = ['georgringer/news', 'georgringer/eventnews'];`
- `Build/phpunit/UnitTests.xml` sets `failOnDeprecation`, `failOnNotice`,
  `failOnRisky` and `failOnWarning`. **A triggered notice fails the build even
  when every assertion passes** — the run prints `OK, but there were issues!`
  and still exits non-zero.
- `Build/phpunit/FunctionalTests.xml` has `failOnDeprecation="false"`, see the
  comment in that file and the backlog below. The other three stay enabled.
- DB fixtures are CSV files loaded with `importCSVDataSet()`. These paths do
  not resolve `EXT:` prefixes — use `__DIR__`-relative paths.

### Open v14 deprecations

The functional suite reports six deprecations on TYPO3 14.3. Three belong to
EXT:news and cannot be fixed here (its `ext_emconf.php`, and `ext_tables.php`
being loaded). The three that are ours:

1. `ext_emconf.php` is deprecated — resolve by declaring `version` and
   `providesPackages` in `composer.json` and dropping the file.
2. `ExtensionManagementUtility::addPiFlexFormValue()` in
   `Configuration/TCA/Overrides/tt_content.php` — the data structure should be
   registered via `addPlugin()` or `columnsOverrides`. Needs a v13/v14 switch.
3. The `searchFields` TCA `ctrl` option in
   `Configuration/TCA/tx_eventnews_domain_model_{location,organizer}.php` is no
   longer evaluated in v14. Removing it outright changes v13 behaviour, so this
   needs a version-aware migration.

`Configuration/TSconfig/ContentElementWizard.tsconfig` is orphaned — its only
consumer was a `getMajorVersion() < 13` branch that has been removed. The
wizard entry now comes from `registerPlugin()`, so the file can go.

Once those and the EXT:news ones are gone, flip `failOnDeprecation` back to
`true` in `Build/phpunit/FunctionalTests.xml`.

## Commits and pull requests

**Only create or modify commits when explicitly asked.**

- Subject tags: `[BUGFIX]`, `[FEATURE]`, `[TASK]`, `[DOC]`. Imperative, concise
  subject; the body describes the behaviour without the patch, why that is a
  problem, and how the patch fixes it.
- Reference the GitHub issue in the footer as `Resolves: #123`.
- Work on a topic branch off `main` and open a pull request; do not commit to
  `main` directly.
- Keep commits as logical units — an unrelated cleanup belongs in its own
  commit, even when it touches the same file.
- Do not credit tooling or assistants in commit messages.
- Before pushing, `lint`, `unit`, `functional` and `cgl -n` should be green,
  and on both TYPO3 13 and 14 whenever the change touches version-sensitive
  code.

## Security

- Never commit secrets or credentials.
- Report potential security issues privately to the TYPO3 Security Team
  (<security@typo3.org>) instead of opening a public GitHub issue.
