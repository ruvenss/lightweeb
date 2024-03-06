<?php
if (LIGHTWEB_DB) {
    $ldb = new mysqli(LIGHTWEB_DB_HOST, LIGHTWEB_DB_USER, LIGHTWEB_DB_PASS, LIGHTWEB_DB_NAME);
    define("ldb", $ldb);
    if (ldb->connect_errno) {
        die("LightWeb Failed to connect to MySQL: " . ldb->connect_error);
    }
}
define("LIGHTWEB_URI", $_REQUEST);
if (!isset(LIGHTWEB_URI['lang'])) {
    header("Location: /" . LIGHTWEB_LANG);
}
$uri = uripage();
$fullpage = render_page($uri);
echo i18n($fullpage);
