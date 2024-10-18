<?php
/**
 * Environment config options:
 * - development
 * - production
 */
define('LIGHTWEB_ENVIRONMENT', 'production');
define('LIGHTWEB_PRODUCTION', '');
define('LIGHTWEB_STAGE', '');
define('LIGHTWEB_LANG', 'en');
define('LIGHTWEB_VERSION', '3.0');
define('LIGHTWEB_APIKEY', "My_secret_key");
if (isset($cli)) {
    $path = str_replace("/lightweb/lightweb", "/lightweb", getcwd());
    define('LIGHTWEB_PATH', $path . "/");
} else {
    $path = getcwd() . "/";
    $path = str_replace("/api/", "/", $path);
    define('LIGHTWEB_PATH', $path);
}
define('LIGHTWEB_PAGES_PATH', LIGHTWEB_PATH . 'lightweb/pages/');
define('LIGHTWEB_LOCALES_PATH', LIGHTWEB_PATH . 'lightweb/locales/');
define('LIGHTWEB_PAGES_HEADERS_PATH', LIGHTWEB_PATH . 'lightweb/headers/');
define('LIGHTWEB_PAGES_FOOTERS_PATH', LIGHTWEB_PATH . 'lightweb/footers/');
define('LIGHTWEB_PAGES_TEMPLATE_PATH', LIGHTWEB_PATH . 'lightweb/template/');
define('LIGHTWEB_PUBLISH_PATH', LIGHTWEB_PATH . 'lightweb/publish/');
if (!file_exists(LIGHTWEB_PAGES_PATH . 'tree.json')) {
    copy(LIGHTWEB_PATH . "templates/tree.json", LIGHTWEB_PAGES_PATH . 'tree.json');
}
if (!file_exists(LIGHTWEB_PAGES_PATH . 'siteconfig.json')) {
    copy(LIGHTWEB_PATH . "templates/siteconfig.json", LIGHTWEB_PAGES_PATH . 'siteconfig.json');
}
define('LIGHTWEB_TREE', json_decode(file_get_contents(LIGHTWEB_PAGES_PATH . 'tree.json'), true));
define('LIGHTWEB_SITE_CONFIG', json_decode(file_get_contents(LIGHTWEB_PAGES_PATH . 'siteconfig.json'), true));
define('LIGHTWEB_DEBUG', false);
define('LIGHTWEB_MINIFY', true);
define('LIGHTWEB_DB', false);
define('LIGHTWEB_DB_HOST', '');
define('LIGHTWEB_DB_USER', '');
define('LIGHTWEB_DB_PASS', '');
define('LIGHTWEB_DB_NAME', '');
define('LIGHTWEB_DB_PREFIX', '');
define('LIGHTWEB_DB_PORT', '3306');
define('LIGHTWEB_DB_CHARSET', 'utf8mb4');
define('LIGHTWEB_DB_COLLATE', 'utf8mb4_unicode_ci');
define('LIGHTWEB_NIZU_TOKEN', "");
define('LIGHTWEB_NIZU_CMS', "");
define('GOOGLE_UA', "");
define('FACEBOOK_PIXEL_ID', "");
define('HUBSPOT_ID', "");