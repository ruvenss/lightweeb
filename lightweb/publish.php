<?php
function publish()
{
    $version = prepare_render();
    echo "\nPublishing v$version...\n";
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
    $robots = ' User-agent: *
Allow: /
Sitemap: https://' . LIGHTWEB_PRODUCTION . '/sitemap.xml';
    $sitemap_header = '<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd http://www.google.com/schemas/sitemap-image/1.1 http://www.google.com/schemas/sitemap-image/1.1/sitemap-image.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
    $sitemap_footer = '
</urlset>
    <!-- XML Sitemap generated by LightWeb SEO -->';
    if (!file_exists(LIGHTWEB_PUBLISH_PATH)) {
        mkdir(LIGHTWEB_PUBLISH_PATH);
        mkdir(LIGHTWEB_PUBLISH_PATH . "compress");
        mkdir(LIGHTWEB_PUBLISH_PATH . "uncompress");

    }
    if (!file_exists(LIGHTWEB_PUBLISH_PATH . "uncompress/api")) {
        mkdir(LIGHTWEB_PUBLISH_PATH . "uncompress/api");
    }
    if (!file_exists(LIGHTWEB_PUBLISH_PATH . "versions.json")) {
        file_put_contents(LIGHTWEB_PUBLISH_PATH . "versions.json", json_encode(["v" => 1]));
        $version = 1;
    } else {
        $version_data = json_decode(file_get_contents(LIGHTWEB_PUBLISH_PATH . "versions.json"), true);
        $version = $version_data['v'] + 1;
        file_put_contents(LIGHTWEB_PUBLISH_PATH . "versions.json", json_encode(["v" => $version]));
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
    $dbphp = file_get_contents(LIGHTWEB_PATH . "lightweb/db.php");
    file_put_contents(LIGHTWEB_PUBLISH_PATH . "uncompress/api/db.php", $dbphp);
    if (!file_exists(LIGHTWEB_PUBLISH_PATH . "uncompress/offline")) {
        mkdir(LIGHTWEB_PUBLISH_PATH . "uncompress/offline");
    }
    if (!file_exists(LIGHTWEB_PUBLISH_PATH . "uncompress/offline/index.html")) {
        file_put_contents(LIGHTWEB_PUBLISH_PATH . "uncompress/offline/index.html", $offlinehtml);
    }
    /*
        SEO Files, SITEMAP etc..
    */
    file_put_contents(LIGHTWEB_PUBLISH_PATH . "uncompress/robots.txt", $robots);
    $sitemap_body = "";
    foreach (locales as $isolang) {
        $sitemapob = '
<url>
    <loc>https://' . LIGHTWEB_PRODUCTION . "/" . $isolang . '{{url}}</loc>
    <lastmod>{{fileupdate}}+00:00</lastmod>
</url>';
        foreach (LIGHTWEB_TREE as $page) {
            if (isset($page['url'])) {
                $sitemapitem = str_replace("{{url}}", $page['url'], $sitemapob);
                $filepage = LIGHTWEB_PAGES_PATH . $page['path'];
                if (file_exists($filepage)) {
                    $itemdate = date("Y-m-d", filemtime($filepage)) . "T" . date("H:i:s", filemtime($filepage));
                    $sitemapitem = str_replace("{{fileupdate}}", $itemdate, $sitemapitem);
                    $sitemap_body .= $sitemapitem;
                }
            }
        }
    }
    file_put_contents(LIGHTWEB_PUBLISH_PATH . "uncompress/sitemap.xml", $sitemap_header . $sitemap_body . $sitemap_footer);
    /*
        Service Workers
    */
    return $version;
}