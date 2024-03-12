<?php
header("PoweredBy: LightWeb 3.0;");
if (LIGHTWEB_DB) {
    $ldb = new mysqli(LIGHTWEB_DB_HOST, LIGHTWEB_DB_USER, LIGHTWEB_DB_PASS, LIGHTWEB_DB_NAME);
    define("ldb", $ldb);
    if (ldb->connect_errno) {
        die("LightWeb Failed to connect to DB Server: " . ldb->connect_error);
    }
}
if (!file_exists(dirname(dirname(__FILE__)) . "/api")) {
    die(dirname(dirname(__FILE__)) . "/api is missing.");
}
define("LIGHTWEB_URI", $_REQUEST);
if (!isset(LIGHTWEB_URI['lang'])) {
    header("Location: /" . LIGHTWEB_LANG);
}
GetLanguages();
$uri = uripage();
$fullpage = render_page($uri);
echo i18n($fullpage);

