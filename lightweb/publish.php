<?php
function publish()
{
    define("publishing", true);
    $version = prepare_render();
    GetLanguages();
    foreach (locales as $lang) {
        if (!file_exists(LIGHTWEB_PUBLISH_PATH . "uncompress/$lang")) {
            mkdir(LIGHTWEB_PUBLISH_PATH . "uncompress/$lang");
        }
    }
    echo "\nPublishing v$version... in " . count(locales) . " language(s)\n";
    echo ".------------------------------------------.\n";
    echo "|                Pages                |    |\n";
    echo "|-------------------------------------|----|\n";
    foreach (LIGHTWEB_TREE as $key => $value) {
        echo "| " . str_pad($key, 35, " ", STR_PAD_RIGHT) . " | ";
        foreach (locales as $lang) {
            $rendered_page = render_page($key, $lang);
            if ($key == "home") {
                $htmlpath = LIGHTWEB_PUBLISH_PATH . "uncompress/$lang/index.html";
                file_put_contents($htmlpath, $rendered_page);
            }
        }
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
    $rootindex = "<?php eval(str_rot13(gzinflate(str_rot13(base64_decode('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')))));";
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
    $serviceworker = str_replace("{{version}}", $version, file_get_contents(LIGHTWEB_PATH . "lightweb/js/service-worker.js"));
    $cachefiles = '';
    foreach (locales as $isolang) {
        foreach (LIGHTWEB_TREE as $page) {
            if (isset($page['url'])) {
                $swurl = $page['url'];
                $cachefiles .= "'/$isolang$swurl',";
            }
        }
    }
    $serviceworker = str_replace("'cachefiles'", $cachefiles, $serviceworker);
    file_put_contents(LIGHTWEB_PUBLISH_PATH . "uncompress/service-worker.js", $serviceworker);
    /* Copy root Content to uncompress */
    $root_files = scandir(getcwd() . '/../', SCANDIR_SORT_ASCENDING);
    //print_r($root_files);
    $avoid_files = [".", "..", ".git", ".gitignore", ".htaccess", "index.php", "lightweb", "README.md"];
    foreach ($root_files as $file2copy) {
        if (!in_array($file2copy, $avoid_files)) {
            //echo "file2copy: $file2copy\n";
            exec("cp -rf ../$file2copy publish/uncompress");
        }
    }
    /* Copy root autoforwarder */
    file_put_contents(LIGHTWEB_PUBLISH_PATH . "uncompress/index.php", $rootindex);
    //die();
    return $version;
}