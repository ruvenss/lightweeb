# Claude Rules for LightWeeb

Project-specific dos and don'ts for Claude Code when working in this repository.

---

## DO

### Files & structure
- **Put all custom code in `my_` files** — `api/my_config.php`, `api/my_functions.php`, `api/my_init.php`, and `lightweb/config.php`
- **Add pages to `lightweb/pages/tree.json` first**, then create the HTML file referenced by `path`
- **Use `lightweb/plugins/*.php` for HTML post-processing** — the filename is the function name, signature must be `function name(string $fullpage, string $lang, string $uri): string`
- **Add new JS to a file in `lightweb/jscode/`** — files are auto-bundled alphabetically into `vendors.js`
- **Edit `lightweb/pages/siteconfig.json`** for any site-wide change (name, logo, colors, social links, location, analytics IDs)

### HTML content
- **Use `{{{i18n_key}}}` for every user-visible string** — never hard-code text in page, header, or footer files
- **Add new i18n keys to all locale files** in `lightweb/locales/` simultaneously — missing a language causes silent empty output
- **Use `{{title}}`, `{{description}}`, `{{lang_lc}}`, `{{author}}`** in header templates; use `{{version}}` in JS files for cache-busting
- **Keep page body files (`lightweb/pages/*.html`) tag-free at the root level** — no `<html>`, `<head>`, or `<body>` tags; they are injected by the header/footer system

### PHP
- **Use `declare(strict_types=1)` in all new PHP files**
- **Sanitize at the boundary** — `htmlspecialchars()` before echoing any user input into HTML, prepared statements for all DB queries
- **Return all API responses through `response()`** (`xhr_handler.php`) — never `echo` raw JSON from API files
- **Check `publishing` constant** (boolean) when writing plugin or rendering code that behaves differently between dev and publish mode
- **Use `ldb` (the global mysqli instance)** for DB queries — never open a second connection

### Git & deployment
- **Verify `.gitignore` before staging** — `lightweb/config.php`, all content dirs (`pages/*`, `headers/*`, `footers/*`), generated files (`vendors.js`, manifests, `publish/`), and static asset dirs (`assets`, `js`, `css`, `images`) are intentionally ignored
- **Run `php lightweb/cli.php publish`** to validate the full build before reporting a feature complete
- **Update `lightweb/pages/siteconfig.json`** when adding new social media handles or changing brand colors — it feeds OG cards, manifests, and structured data automatically

---

## DON'T

### Never touch framework files
- **Do not modify** `render.php`, `functions.php`, `init.php`, `db.php`, `publish.php`, `lightweb.php`, `xhr_handler.php`, `autoupdate.php`, or `cli.php` — they are overwritten by `autoupdate`
- **Do not edit `lightweb/lightweb.json`** manually — it is the version manifest managed by `autoupdate`
- **Do not change the `updatable_files` list** in `lightweb/lightweb.json`

### HTML & CSS
- **Do not use inline `style` attributes** — use CSS classes
- **Do not hard-code text** in any template file — use `{{{i18n_key}}}`
- **Do not add `<html>`, `<head>`, or `<body>` tags** inside `lightweb/pages/*.html` content files
- **Do not use `<table>` for layout**
- **Do not skip heading levels** — hierarchy must be `h1 → h2 → h3`

### JavaScript
- **Do not use `eval()`** under any circumstances
- **Do not add inline `<script>` blocks** in page/header/footer HTML — all JS belongs in `lightweb/jscode/`
- **Do not import npm packages** — this project has no build toolchain; use vanilla ES6+
- **Do not use `innerHTML` with user-controlled data** — use `textContent` or sanitize first

### PHP & security
- **Do not interpolate `$_REQUEST` / `$_GET` / `$_POST` values directly into SQL** — always use prepared statements
- **Do not call `exec()` or `shell_exec()` with any user-supplied input**
- **Do not output anything before `response()`** in API files — headers must be sent cleanly
- **Do not set `LIGHTWEB_ENVIRONMENT = 'production'`** in dev — it suppresses PHP errors needed for debugging

### Git & secrets
- **Do not commit `lightweb/config.php`** — it contains credentials and is in `.gitignore`
- **Do not commit `lightweb/publish/`** — generated output, in `.gitignore`
- **Do not commit `vendors.js`** or any `manifest_*.json` — generated on each request/publish
- **Do not add API keys, tokens, or passwords anywhere except `lightweb/config.php` or `api/my_config.php`**
- **Do not force-push to `master`** without explicit confirmation
