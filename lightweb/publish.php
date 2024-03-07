<?php
function publish()
{
    echo "\nPublishing...\n";
    prepare_render();
    echo ".------------------------------------------.\n";
    echo "|                Pages                |    |\n";
    echo "|-------------------------------------|----|\n";
    foreach (LIGHTWEB_TREE as $key => $value) {
        echo "| " . str_pad($key, 35, " ", STR_PAD_RIGHT) . " | ";
        echo "✅ |\n";
        echo "|-------------------------------------|----|";
        echo "\n";
    }
    echo "|------------------------------------------|\n";
    echo "|Render complete                           |\n";
    echo "--------------------------------------------\n";
}
function prepare_render()
{
    $offlinehtml = '<html><head><title>LightWeb Offline</title><style>body{background:black;color:lime;font-family:mono;}</style></head><body>LightWeb 3.0 OffLine Data</body></html>';
    if (!file_exists(LIGHTWEB_PUBLISH_PATH)) {
        mkdir(LIGHTWEB_PUBLISH_PATH);
        mkdir(LIGHTWEB_PUBLISH_PATH . "compress");
        mkdir(LIGHTWEB_PUBLISH_PATH . "uncompress");
    }
    if (!file_exists(LIGHTWEB_PUBLISH_PATH . "uncompress/api")) {
        mkdir(LIGHTWEB_PUBLISH_PATH . "uncompress/api");
    }
    $htaccess = "RewriteEngine Off
Options -Indexes
ErrorDocument 404 https://" . LIGHTWEB_PRODUCTION . "/" . LIGHTWEB_LANG . "/404";
    file_put_contents(LIGHTWEB_PUBLISH_PATH . "uncompress/.htaccess", $htaccess);
    $configphp = '<?php' . "\n";
    if (LIGHTWEB_DB) {
        $configphp .= "define('LIGHTWEB_DB_HOST', '" . LIGHTWEB_DB_HOST . "');
define('LIGHTWEB_DB_USER', '" . LIGHTWEB_DB_USER . "');
define('LIGHTWEB_DB_PASS', '" . LIGHTWEB_DB_PASS . "');
define('LIGHTWEB_DB_NAME', '" . LIGHTWEB_DB_NAME . "');
define('LIGHTWEB_DB_PREFIX', '" . LIGHTWEB_DB_PREFIX . "');
define('LIGHTWEB_DB_PORT', '" . LIGHTWEB_DB_PORT . "');
define('LIGHTWEB_DB_CHARSET', '" . LIGHTWEB_DB_CHARSET . "');
define('LIGHTWEB_DB_COLLATE', '" . LIGHTWEB_DB_COLLATE . "');";
    }
    file_put_contents(LIGHTWEB_PUBLISH_PATH . "uncompress/api/config.php", $configphp);
    if (!file_exists(LIGHTWEB_PUBLISH_PATH . "uncompress/api/locales")) {
        mkdir(LIGHTWEB_PUBLISH_PATH . "uncompress/api/locales");
    }
    $locales_files = scandir(LIGHTWEB_LOCALES_PATH);
    foreach ($locales_files as $lcfile) {
        if (!$lcfile == "..") {
            $lcdata = file_get_contents(LIGHTWEB_LOCALES_PATH . $lcfile);
            file_put_contents(LIGHTWEB_PUBLISH_PATH . "uncompress/api/locales/" . $lcfile, $lcdata);
        }
    }
    if (!file_exists(LIGHTWEB_PUBLISH_PATH . "uncompress/offline")) {
        mkdir(LIGHTWEB_PUBLISH_PATH . "uncompress/offline");
    }
    if (!file_exists(LIGHTWEB_PUBLISH_PATH . "uncompress/offline/index.html")) {
        file_put_contents(LIGHTWEB_PUBLISH_PATH . "uncompress/offline/index.html", $offlinehtml);
    }
}