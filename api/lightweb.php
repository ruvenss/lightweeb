<?php
/**
 * LightWeb API Handler 
 * This file won't be copied to production
 * @author Ruvenss G. Wilches <ruvenss@gmail.com>
 */
if (file_exists("../lightweb/config.php")) {
    include_once("../lightweb/config.php");
    GetLanguages();
    if (file_exists("../lightweb/pages/tree.json")) {
        define("tree", json_decode(file_get_contents("../lightweb/pages/tree.json"), true));
    } else {
        define("tree", []);
    }
    $DataInputRaw = file_get_contents("php://input");
    if (json_validator($DataInputRaw)) {
        $Bearer = getAuthorizationHeader();
        define("DataInput", json_decode($DataInputRaw, TRUE));
        define("request_type", $_SERVER['REQUEST_METHOD']);
        if (isset(DataInput['a'])) {
            $ThisFunction = DataInput['a'];
            if (function_exists($ThisFunction)) {
                $ThisFunction();
            }
        }
    }
} else {
    die('{"answer":false,"error":"LightWeb API Key missing in the server"}');
}
function create_page()
{
    if (verified_payload()) {
        foreach (tree as $page) {
            if ($page == DataInput['id']) {
                response(false, [], 0, "Page already exist.");
                break;
            }
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
        /**
         * create the i18n files
         */
        response(true, $newtree);
    }
}
function verified_payload()
{
    $i18OK = true;
    if (isset(DataInput['tree']) && isset(DataInput['id'])) {

        if (isset(DataInput['i18n'])) {
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
    if (isset($_SERVER['Authorization'])) {
        $headers = trim($_SERVER["Authorization"]);
    } elseif (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
        $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
    } elseif (function_exists('apache_request_headers')) {
        $requestHeaders = apache_request_headers();
        $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
        if (isset($requestHeaders['Authorization'])) {
            $headers = trim($requestHeaders['Authorization']);
        }
    }
    if (!empty($headers)) {
        $Bearer = trim(str_replace("Bearer", "", $headers));
        return($Bearer);
    } else {
        return null;
    }
}
function json_validator($data)
{
    if (!empty($data)) {
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