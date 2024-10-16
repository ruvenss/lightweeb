<?php
/**
 * DO NOT INSERT YOUR CODE HERE! THIS FILE WILL BE REWRITE IN THE NEXT UPDATE
 * USE ONLY FILES THAT BEGIN BY my_
 * 
 * @author Ruvenss G. Wilches <ruvenss@gmail.com>
 */

header("PoweredBy: LightWeeb 3.0.34;");
if (LIGHTWEB_DB && !defined("ldb")) {
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
if (!file_exists(LIGHTWEB_PAGES_PATH . "tree.json"))
    GetLanguages();
$uri = uripage();
$fullpage = render_page($uri);
echo i18n($fullpage);

