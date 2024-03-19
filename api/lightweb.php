<?php
/**
 * LightWeb API Handler 
 * This file won't be copied to production
 * @author Ruvenss G. Wilches <ruvenss@gmail.com>
 */
if (file_exists("../lightweb/config.php")) {
    include_once ("../lightweb/config.php");
    GetLanguages();
    if (file_exists("../lightweb/pages/tree.json")) {
        define("tree", json_decode(file_get_contents("../lightweb/pages/tree.json"), true));
    } else {
        define("tree", []);
    }
    $DataInputRaw = file_get_contents("php://input");
    if (json_validator($DataInputRaw)) {
        $Bearer = getAuthorizationHeader();
        if ($Bearer == LIGHTWEB_APIKEY) {
            define("DataInput", json_decode($DataInputRaw, TRUE));
            define("request_type", $_SERVER['REQUEST_METHOD']);
            if (isset (DataInput['a'])) {
                $ThisFunction = DataInput['a'];
                if (function_exists($ThisFunction)) {
                    $ThisFunction();
                }
            }
        } else {
            response(false, [], 0, "Incorrect Key");
        }

    } else {
        if (isset ($_REQUEST['a'])) {
            $ThisFunction = trim(str_replace(" ", "", $_REQUEST['a']));
            switch ($ThisFunction) {
                case 'version':
                case 'onlyhumans':
                    $ThisFunction();
                    break;
                default:
                    include_once ("my_config.php");
                    include_once ("my_functions.php");
                    include_once ("my_init.php");
                    if (function_exists($ThisFunction)) {
                        $ThisFunction();
                    }
                    break;
            }
        }
        response(false);
    }
} else {
    die ('{"answer":false,"error":"LightWeb API Key missing in the server"}');
}
function onlyhumans()
{

    if (isset ($_REQUEST['LW_uuid']) && strlen($_REQUEST['LW_uuid']) > 5) {
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
function get_page()
{
    if (page_exist(DataInput['id'])) {
        $headerhtml = file_get_contents(LIGHTWEB_PAGES_HEADERS_PATH . tree[DataInput['id']]['header']);
        $bodyhtml = file_get_contents(LIGHTWEB_PAGES_PATH . DataInput['id'] . "/index.html");
        $footerhtml = file_get_contents(LIGHTWEB_PAGES_FOOTERS_PATH . tree[DataInput['id']]['footer']);
        response(true, ["config" => tree[DataInput['id']], "header" => $headerhtml, "body" => $bodyhtml, "footer" => $footerhtml]);
    } else {
        response(false, [], 0, "Page ID does not exist.");
    }
}
function GetTree()
{
    response(true, tree);
}
function GetLocales()
{
    $translations = [];
    for ($i = 0; $i < sizeof(locales); $i++) {
        $locales_path = dirname(dirname(__FILE__)) . "/lightweb/locales/" . locales[$i] . ".json";
        if (file_exists($locales_path)) {
            $currentLocales = json_decode(file_get_contents($locales_path), true);
            $translations[locales[$i]] = $currentLocales;
        }
    }
    response(true, ["locales" => locales, "translations" => $translations]);
}
function delete_page()
{
    if (page_exist(DataInput['id'])) {
        $copytree = tree;
        $treepath = dirname(dirname(__FILE__)) . "/lightweb/pages/tree.json";
        foreach ($copytree as $key => $value) {
            if (DataInput['id'] == $key) {
                unset($copytree[$key]);
            }
        }
        file_put_contents($treepath, json_encode($copytree));
        response(true, $copytree);
    } else {
        response(false, [], 0, "Page does not exist, you can't delete something that does not exist.");
    }
}
function update_page()
{
    if (verified_payload()) {
        if (page_exist(DataInput['id'])) {
            $copytree = tree;
            $newtree = [];
            foreach ($copytree as $key => $value) {
                $newtree[$key] = $value;
            }
            $newtree[DataInput['id']] = DataInput['tree'];
            $treepath = dirname(dirname(__FILE__)) . "/lightweb/pages/tree.json";
            file_put_contents($treepath, json_encode($newtree));
            $brach_parts = explode("/", DataInput['id']);
            $fullpath = "";
            for ($i = 0; $i < sizeof($brach_parts); $i++) {
                $fullpath .= "/" . $brach_parts[$i];
                if (!file_exists(dirname(dirname(__FILE__)) . "/lightweb/pages" . $fullpath)) {
                    mkdir(dirname(dirname(__FILE__)) . "/lightweb/pages" . $fullpath);
                    file_put_contents(dirname(dirname(__FILE__)) . "/lightweb/pages" . $fullpath . "/index.html", "<p></p>");
                }
            }
            if (isset (DataInput['htmlcontent']) && strlen(DataInput['htmlcontent']) > 0) {
                file_put_contents(dirname(dirname(__FILE__)) . "/lightweb/pages" . $fullpath . "/index.html", DataInput['htmlcontent']);
            }
            /**
             * Update the i18n Files
             */
            for ($i = 0; $i < sizeof(locales); $i++) {
                $locales_path = dirname(dirname(__FILE__)) . "/lightweb/locales/" . locales[$i] . ".json";
                if (file_exists($locales_path)) {
                    $currentLocales = json_decode(file_get_contents($locales_path), true);
                    $payloadi18nkeys = DataInput['i18n'][locales[$i]];
                    foreach ($payloadi18nkeys as $key => $value) {
                        $currentLocales[$key] = $value;
                    }
                    file_put_contents($locales_path, json_encode($currentLocales));
                }
            }
            response(true, []);
        } else {
            response(false, [], 0, "Page does not exist, you can't update something that does not exist.");
        }
    }
}
function create_page()
{
    if (verified_payload()) {
        if (page_exist(DataInput['id'])) {
            response(false, [], 0, "Page already exist.");
        }
        $copytree = tree;
        $newtree = [];
        foreach ($copytree as $key => $value) {
            $newtree[$key] = $value;
        }
        $newtree[DataInput['id']] = DataInput['tree'];
        $treepath = dirname(dirname(__FILE__)) . "/lightweb/pages/tree.json";
        file_put_contents($treepath, json_encode($newtree));
        $brach_parts = explode("/", DataInput['id']);
        $fullpath = "";
        for ($i = 0; $i < sizeof($brach_parts); $i++) {
            $fullpath .= "/" . $brach_parts[$i];
            if (!file_exists(dirname(dirname(__FILE__)) . "/lightweb/pages" . $fullpath)) {
                mkdir(dirname(dirname(__FILE__)) . "/lightweb/pages" . $fullpath);
                file_put_contents(dirname(dirname(__FILE__)) . "/lightweb/pages" . $fullpath . "/index.html", "<p></p>");
            }

        }
        if (isset (DataInput['htmlcontent']) && strlen(DataInput['htmlcontent']) > 0) {
            file_put_contents(dirname(dirname(__FILE__)) . "/lightweb/pages" . $fullpath . "/index.html", DataInput['htmlcontent']);
        }
        /**
         * Update the i18n Files
         */
        for ($i = 0; $i < sizeof(locales); $i++) {
            $locales_path = dirname(dirname(__FILE__)) . "/lightweb/locales/" . locales[$i] . ".json";
            if (file_exists($locales_path)) {
                $currentLocales = json_decode(file_get_contents($locales_path), true);
                $payloadi18nkeys = DataInput['i18n'][locales[$i]];
                foreach ($payloadi18nkeys as $key => $value) {
                    $currentLocales[$key] = $value;
                }
                file_put_contents($locales_path, json_encode($currentLocales));
            }
        }
        response(true, $newtree);
    }
}
function page_exist($page_id)
{
    foreach (tree as $key => $value) {
        if ($key == $page_id) {
            return true;
        }
    }
    return false;
}
function verified_payload()
{
    $i18OK = true;
    if (isset (DataInput['tree']) && isset (DataInput['id'])) {

        if (isset (DataInput['i18n'])) {
            foreach (DataInput['i18n'] as $i18lang) {
                if (in_array($i18lang, locales)) {
                    // all good
                } else {
                    $i18OK = false;
                }
            }
        } else {
            response(false, [], 0, "i18 translations key missing.");
        }
        if (!$i18lang) {
            response(false, [], 0, "i18 translations missing.");
        }
        return true;
    } else {
        response(false, [], 0, "Tree is not defined");
    }
}
function getAuthorizationHeader()
{
    $headers = null;
    if (isset ($_SERVER['Authorization'])) {
        $headers = trim($_SERVER["Authorization"]);
    } elseif (isset ($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
        $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
    } elseif (function_exists('apache_request_headers')) {
        $requestHeaders = apache_request_headers();
        $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
        if (isset ($requestHeaders['Authorization'])) {
            $headers = trim($requestHeaders['Authorization']);
        }
    }
    if (!empty ($headers)) {
        $Bearer = trim(str_replace("Bearer", "", $headers));
        return ($Bearer);
    } else {
        return null;
    }
}
function json_validator($data)
{
    if (!empty ($data)) {
        return is_string($data) &&
            is_array(json_decode($data, true)) ? true : false;
    }
    return false;
}
function GetLanguages()
{
    if (!defined("locales")) {
        $languages_files = scandir(dirname(dirname(__FILE__)) . "/lightweb/locales");
        $languages = [];
        foreach ($languages_files as $language_file) {
            $language = str_replace(".json", "", $language_file);
            if ($language == ".." || $language == ".") {

            } else {
                $languages[] = $language;
            }
        }
        define("locales", $languages);
    }
}