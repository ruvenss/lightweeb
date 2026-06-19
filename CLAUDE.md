# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What this is

LightWeeb 3.0 is a PHP framework that renders HTML websites dynamically in development mode and exports them as static HTML in production. It handles routing, multi-language i18n, SEO (OG cards, structured data, sitemaps), service workers, and PWA manifests with no database required.

## Commands

**Development server** (PHP built-in):
```bash
php -S localhost:8000
```
Pages are served via `index.php?lang=en&page1=about` etc. The `.htaccess` rewrites clean URLs to this format automatically under Apache.

**Publish to static HTML:**
```bash
php lightweb/cli.php publish
```
Output lands in `lightweb/publish/uncompress/` and is zipped to `lightweb/publish/compress/download.zip`.

**Update framework to latest release:**
```bash
cd lightweb && php autoupdate.php
```

## Architecture

### Routing
`.htaccess` rewrites all URLs to `index.php` with segments mapped to `?lang=%1&page1=%2&page2=%3…`. `init.php` calls `uripage()` to reassemble the slug (e.g. `about/team`) and passes it to `render_page()`.

### Page tree (`lightweb/pages/tree.json`)
Defines every page. Each key is the page slug used in routing. Example entry:
```json
"about": {
  "url": "/about",
  "path": "about.html",
  "header": "main.html",
  "footer": "main.html",
  "titlei18n": "about_title",
  "descriptioni18n": "about_desc",
  "featured_image": "https://...",
  "publish_from": "2024-01-01",
  "publish_until": "2025-12-31"
}
```
The `404` key is reserved for the not-found page.

### Content directories
- `lightweb/pages/` — page body HTML files (referenced by `tree.json` `path`)
- `lightweb/headers/` — `<html>…<body>` header partials
- `lightweb/footers/` — closing `</body></html>` footer partials
- `lightweb/locales/` — i18n JSON files named by ISO code (`en.json`, `fr.json`, etc.)
- `lightweb/plugins/` — PHP files auto-loaded per request; filename = function name called with `($fullpage, $lang, $uri)`
- `lightweb/jscode/` — JS files concatenated in filename order into `vendors.js` (excluded: `facebook_pixel.js`, `google_ua.js`, `service-worker.js`)

### Template variables
In header/footer/page HTML files and JS files:
- `{{title}}` `{{description}}` `{{author}}` `{{lang_lc}}` — filled by `render_page()`
- `{{version}}` — filled from `lightweb/publish/versions.json` (auto-incremented on each publish)
- `{{{i18n_key}}}` — replaced from the active locale JSON

### Configuration (`lightweb/config.php`)
Copy `lightweb/config_sample.php` to `lightweb/config.php`. Key constants:
- `LIGHTWEB_ENVIRONMENT` — `'development'` or `'production'`
- `LIGHTWEB_PRODUCTION` — production domain (no protocol)
- `LIGHTWEB_LANG` — default language code
- `LIGHTWEB_MINIFY` — HTML minification on/off
- `LIGHTWEB_CACHE` — controls PWA manifest generation
- `LIGHTWEB_DB` — set to `true` to enable MySQL via `ldb` global (mysqli instance)
- `GOOGLE_UA`, `FACEBOOK_PIXEL_ID`, `HUBSPOT_ID` — analytics integrations
- `LIGHTWEB_NIZU_TOKEN`, `LIGHTWEB_NIZU_CMS` — Nizu CMS integration

Site metadata (name, logo, colors, social media, location) lives in `lightweb/pages/siteconfig.json` (auto-created on first run if absent).

### API layer (`api/`)
XHR/API requests go to `api/index.php`, which loads: `xhr_handler.php` → `lightweb.php` → `my_config.php` → `my_functions.php` → `my_init.php`. The `response()` helper in `xhr_handler.php` outputs JSON with `{"success": bool, "data": [...]}`.

### CORS
Update `YOURDOMAIN.COM` in `.htaccess` to match the production domain.

## The `my_` file convention

**Never modify framework files directly.** Files without the `my_` prefix will be overwritten on the next `autoupdate`. All custom code belongs in:
- `lightweb/config.php` (your copy of `config_sample.php`)
- `api/my_config.php`, `api/my_functions.php`, `api/my_init.php`
- `lightweb/pages/`, `lightweb/headers/`, `lightweb/footers/`, `lightweb/locales/`
- `lightweb/plugins/*.php` (new plugin files)
- `lightweb/jscode/*.js` (new JS files)

## Rules

See @.claude/RULES.md for the full list of project dos and don'ts.

## Version tracking

Framework version is in `lightweb/lightweb.json`. `autoupdate.php` compares this to the GitHub master release and downloads changed files listed in `updatable_files`. The publish version counter (for cache-busting) is in `lightweb/publish/versions.json`.
