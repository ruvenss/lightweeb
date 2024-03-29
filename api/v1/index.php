<?php
header("PoweredBy: LightWeb 3.0.0;");
header("Content-Type: application/json; charset=UTF-8");
define("errors", json_decode(file_get_contents("../errors.json"), true));
include_once ("../xhr_handler.php");
include_once ("../my_config.php");
include_once ("../my_functions.php");
include_once ("../my_init.php");
loadPlugins();
if (isset($_REQUEST['a'])) {
    $ThisFunction = trim(str_replace(" ", "", $_REQUEST['a']));
    if (isset($_POST['formData']) && json_decode($_POST['formData'])) {
        define("formData", json_decode($_POST['formData'], true));
    } else {
        define("formData", null);
    }
    switch ($ThisFunction) {
        case 'version':
        case 'onlyhumans':
            $ThisFunction();
            break;
        default:
            include_once ("../my_config.php");
            include_once ("../my_functions.php");
            include_once ("../my_init.php");
            if (function_exists($ThisFunction)) {
                $ThisFunction();
            }
            break;
    }
}
response(false);
function onlyhumans()
{
    if (isset($_REQUEST['LW_uuid']) && strlen($_REQUEST['LW_uuid']) > 5) {
        $LW_uuid = trim($_REQUEST['LW_uuid']);
        if (!file_exists("onlyhumans")) {
            mkdir("onlyhumans");
            touch("onlyhumans/index.html");
        }
        if (!file_exists("onlyhumans/$LW_uuid.json")) {
            $key = sha1($LW_uuid);
            file_put_contents("onlyhumans/$LW_uuid.json", sha1($LW_uuid));
        } else {
            $key = file_get_contents("onlyhumans/$LW_uuid.json");
        }
        response(true, ["onlyhumans" => $key]);
    } else {
        response(false);
    }
}
function version()
{
    response(true, ["version" => LIGHTWEB_VERSION]);
}
function loadPlugins()
{
    $pluginsfiles = scandir("plugins", SCANDIR_SORT_ASCENDING);
    foreach ($pluginsfiles as $pluginfile) {
        if ($pluginfile == "." || $pluginfile == "..") {
            include_once ("plugins/$pluginfile");
        }
    }
}