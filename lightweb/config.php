<?php
/**
 * Environment config options:
 * - development
 * - production
 */
define('LIGHTWEB_ENVIRONMENT', 'development');
define('LIGHTWEB_PRODUCTION', 'nizu.io');
define('LIGHTWEB_STAGE', 'draft.nizu.io');
define('LIGHTWEB_LANG', 'en');
define('LIGHTWEB_VERSION', '3.0');
define('LIGHTWEB_PATH', getcwd() . "/");
define('LIGHTWEB_PAGES_PATH', LIGHTWEB_PATH . 'lightweb/pages/');
define('LIGHTWEB_LOCALES_PATH', LIGHTWEB_PATH . 'lightweb/locales/');
define('LIGHTWEB_PAGES_HEADERS_PATH', LIGHTWEB_PATH . 'lightweb/headers/');
define('LIGHTWEB_PAGES_FOOTERS_PATH', LIGHTWEB_PATH . 'lightweb/footers/');
define('LIGHTWEB_PAGES_TEMPLATE_PATH', LIGHTWEB_PATH . 'lightweb/template/');
define('LIGHTWEB_PUBLISH_PATH', LIGHTWEB_PATH . 'lightweb/publish/');
define('LIGHTWEB_TREE', json_decode(file_get_contents(LIGHTWEB_PAGES_PATH . 'tree.json'), true));
define('LIGHTWEB_DEBUG', false);
define('LIGHTWEB_MINIFY', true);
define('LIGHTWEB_DB', false);
define('LIGHTWEB_DB_HOST', 'localhost');
define('LIGHTWEB_DB_USER', 'root');
define('LIGHTWEB_DB_PASS', '');
define('LIGHTWEB_DB_NAME', 'lightweb');
define('LIGHTWEB_DB_PREFIX', 'tbl_');
define('LIGHTWEB_DB_PORT', '3306');
define('LIGHTWEB_DB_CHARSET', 'utf8mb4');
define('LIGHTWEB_DB_COLLATE', 'utf8mb4_unicode_ci');