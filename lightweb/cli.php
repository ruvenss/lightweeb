<?php
$cli = true;
include_once ('config.php');
if (LIGHTWEB_ENVIRONMENT == 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
}
include_once ('functions.php');
include_once ('render.php');
include_once ('db.php');
GetLanguages();
include_once ('publish.php');
if ($argc > 1) {
    $param = $argv[1];
    echo "
    *************************
    *     LightWeb CLI      *
    *        v3.0.31         *
    *************************
";
    switch ($param) {
        case 'publish':
            publish();
            break;

        default:
            # code...
            break;
    }
    die("\n");
} else {
    echo "
    *************************
    *     LightWeb CLI      *
    *        v3.0.31         *
    *************************
LightWeb CLI 3.0.31 is the new method to publish and generate your websites or web app.
Instructions:
php cli.php publish  
";
}