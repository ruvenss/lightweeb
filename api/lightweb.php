<?php
/**
 * DO NOT INSERT YOUR CODE HERE! THIS FILE WILL BE REWRITE IN THE NEXT UPDATE
 * USE ONLY FILES THAT BEGIN BY my_
 * LightWeb API Handler 
 * This file won't be copied to production
 * @author Ruvenss G. Wilches <ruvenss@gmail.com>
 */
define("API_LW_PATH", "../lightweb/");
if (file_exists(API_LW_PATH . "config.php")) {
    include_once(API_LW_PATH . "config.php");
    GetLanguages();
    if (file_exists(API_LW_PATH . "pages/tree.json")) {
        define("tree", json_decode(file_get_contents(API_LW_PATH . "pages/tree.json"), true));
    } else {
        define("tree", []);
    }
    $DataInputRaw = file_get_contents("php://input");
    if (json_validator($DataInputRaw)) {
        $Bearer = getAuthorizationHeader();
        if ($Bearer == LIGHTWEB_APIKEY) {
            define("DataInput", json_decode($DataInputRaw, true));
            define("request_type", $_SERVER['REQUEST_METHOD']);
            if (isset(DataInput['a']) && function_exists(DataInput['a'])) {
                DataInput['a']();
            }
        } else {
            response(false, [], 0, "Incorrect Key");
        }
    } else {
        response(false, ["error" => "invalid JSON Payload"], 0, "Invalid JSON Payload");
    }
} else {
    die('{"answer":false,"error":"LightWeb API Key missing in the server"}');
}
function SaveBody()
{
    if (isset(DataInput['content_url']) && isset(DataInput['page'])) {
        $page_file = dirname(dirname(__FILE__)) . "/lightweb/pages/" . DataInput['page'] . "/index.html";
        $page_data = file_get_contents(DataInput['content_url']);
        if (strlen($page_data)) {
            file_put_contents($page_file, file_get_contents(DataInput['content_url']));
            response(true, ["page" => DataInput['page'], "downloaded_from" => DataInput['content_url']]);
        } else {
            response(false, ["error" => "Page content can not be empty"], 2, "Missing page content");
        }
    } elseif (!isset(DataInput['content_url'])) {
        response(false, ["error" => "Page content can not be empty"], 2, "Missing page content");
    } else {
        response(false, ["error" => "Page name can not be empty"], 2, "Missing page name");
    }
}
function SaveHeader()
{
    if (isset(DataInput['content_url'])) {
        if (isset(DataInput['header'])) {
            $header_file = dirname(dirname(__FILE__)) . "/lightweb/headers/" . DataInput['header'] . ".html";
            file_put_contents($header_file, file_get_contents(DataInput['content_url']));
            response(true, []);
        } else {
            response(false, ["error" => "Header name can not be empty"], 2, "Missing header name");
        }
    } else {
        response(false, ["error" => "Header content can not be empty"], 2, "Missing header content");
    }
}
function SaveFooter()
{
    if (isset(DataInput['content_url'])) {
        if (isset(DataInput['footer'])) {
            $footer_file = dirname(dirname(__FILE__)) . "/lightweb/footers/" . DataInput['footer'] . ".html";
            file_put_contents($footer_file, file_get_contents(DataInput['content_url']));
            response(true, []);
        } else {
            response(false, ["error" => "Footer name can not be empty"], 2, "Missing Footer name");
        }
    } else {
        response(false, ["error" => "Footer content can not be empty"], 2, "Missing Footer content");
    }
}
function getAssets()
{
    $assets = scandir(dirname(dirname(__FILE__)));
    $assets_data = scanDirectory(dirname(dirname(__FILE__)));
    response(true, ["assets" => $assets_data]);
}
function scanDirectory($dir)
{
    $data = [];
    $files = scandir($dir);
    foreach ($files as $file) {
        if ($file != '.' && $file != '..' && $file != '.htaccess' && $file != '.vscode' && $file != '.git') {
            $path = $dir . '/' . $file;
            if (is_dir($path)) {
                if ($file != 'api' && $file != 'lightweb')
                    $data[] = ["asset_type" => "folder", "asset_name" => $file, "asset_local" => $path, "assets" => scanDirectory($path)];
            } else {
                $data[] = ["asset_type" => "file", "asset_name" => $file, "asset_local" => $path];
            }
        }
    }
    return $data;
}
function assets_scan($assets, $assets_data)
{
    for ($i = 0; $i < sizeof($assets); $i++) {
        $asset_file = $assets[$i];
        if ($asset_file == ".." || $asset_file == "." || $asset_file == ".htaccess" || $asset_file == ".vscode" || $asset_file == "lightweb" || $asset_file == "api" || $asset_file == "index.php" || $asset_file == "README.md" || $asset_file == "vendor.js") {

        } else {
            if (is_dir(dirname(dirname(__FILE__)) . "/" . $asset_file)) {
                $assets_data[] = ["asset_type" => "folder", "asset_name" => $asset_file, "asset_local" => dirname(dirname(__FILE__)) . "/" . $asset_file];
                //assets_scan($assets, $assets_data);
            } else {
                $assets_data[] = ["asset_type" => "file", "asset_name" => $asset_file, "asset_local" => dirname(dirname(__FILE__)) . "/" . $asset_file];
            }
        }
    }
    return $assets_data;
}
function getHeader()
{
    $header_file = dirname(dirname(__FILE__)) . "/lightweb/headers/" . DataInput['header'] . ".html";
    if (file_exists($header_file)) {
        $headerhtml = file_get_contents($header_file);
        response(true, ["header" => $headerhtml]);
    } else {
        response(false, ["header" => $header_file], 2, "Missing header file");
    }
}
function getFooter()
{
    $footer_file = dirname(dirname(__FILE__)) . "/lightweb/footers/" . DataInput['footer'] . ".html";
    if (file_exists($footer_file)) {
        $footerhtml = file_get_contents($footer_file);
        response(true, ["footer" => $footerhtml]);
    } else {
        response(false, ["footer" => $footer_file], 2, "Missing footer file");
    }
}
function getPage()
{
    response(true, ["header" => file_get_contents(dirname(dirname(__FILE__)) . "/lightweb/footers/" . DataInput['footer'] . ".html")]);
}
function getHeaders()
{
    $headers = scandir(dirname(dirname(__FILE__)) . "/lightweb/headers");
    $headers_data = [];
    for ($i = 0; $i < sizeof($headers); $i++) {
        $header_file = str_replace(".html", "", $headers[$i]);
        if ($header_file == ".." || $header_file == ".") {

        } else {
            $headers_data[] = $header_file;
        }
    }
    response(true, ["headers" => $headers_data]);
}
function getFooters()
{
    $footers = scandir(dirname(dirname(__FILE__)) . "/lightweb/footers");
    $footers_data = [];
    for ($i = 0; $i < sizeof($footers); $i++) {
        $footer_file = str_replace(".html", "", $footers[$i]);
        if ($footer_file == ".." || $footer_file == ".") {

        } else {
            $footers_data[] = $footer_file;
        }
    }
    response(true, ["footers" => $footers_data]);
}
function publish()
{
    $e = "cd " . LIGHTWEB_PATH . "lightweb; ./ToProduction.sh > /dev/null 2>&1 &";
    error_log("publishing via API: $e", 0);
    $result = exec($e);
    error_log($result, 0);
    if (file_exists("../lightweb/publish/versions.json")) {
        define("versions", json_decode(file_get_contents("../lightweb/publish/versions.json"), true));
    } else {
        define("versions", ["v" => 0]);
        response(false, ["version" => 0, "zip" => null]);
    }
    response(true, ["zip" => "/lightweb/publish/compress/download.zip", "version" => versions['v']]);
}
function update_locales()
{
    if (isset(DataInput['i18nfiles'])) {
        $i18nfiles = DataInput['i18nfiles'];
        if (sizeof($i18nfiles)) {
            foreach ($i18nfiles as $i18nfile => $i18ncontent) {
                $i18npath = LIGHTWEB_LOCALES_PATH . $i18nfile . ".json";
                $i18nfile_content = [];
                $i = 0;
                for ($i = 0; $i < sizeof($i18ncontent); $i++) {
                    $i18ncontentObj = $i18ncontent[$i];
                    $n = 0;
                    foreach ($i18ncontentObj as $key => $value) {
                        $i18nfile_content[$n][$key] = $value;
                        $n++;
                    }
                }
                copy($i18npath, $i18npath . "." . date("Ymdhis") . ".json");
                file_put_contents($i18npath, json_encode($i18nfile_content[0]));
            }
        }
        response(true);
    } else {
        response(false, [], 0, "i18nfiles not defined");
    }
}
function add_key_locales()
{
    if (isset(DataInput['key'])) {
        $key = DataInput['key'];
        if (isset(DataInput['value'])) {
            $value = DataInput['value'];
        } else {
            response(false, [], 0, "value missing");
        }
    } else {
        response(false, [], 0, "Key missing");
    }
    if (locales && sizeof(locales)) {
        $locales_updated = 0;
        foreach (locales as $locale) {
            $i18npath = LIGHTWEB_LOCALES_PATH . $locale . ".json";
            if ($i18npath && file_exists($i18npath)) {
                $json_data = json_decode(file_get_contents($i18npath), true);
                if (locales_key_exist($key, $json_data)) {
                    response(false, [], 0, "Key already exist");
                } else {
                    $json_data[$key] = $value;
                    file_put_contents($i18npath, json_encode($json_data));
                    $locales_updated++;
                }
            }
        }
        response(true, ["locales_updated" => $locales_updated]);
    } else {
        response(false, [], 0, "i18nfiles " . (empty(locales) ? "not defined" : "missing"));
    }
}
function locales_key_exist($key, $locales_data)
{
    foreach ($locales_data as $locales_key => $value) {
        if ($locales_key == $key) {
            return true;
        }
    }
    return false;
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
            if (isset(DataInput['htmlcontent']) && strlen(DataInput['htmlcontent']) > 0) {
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
                    copy($locales_path, $locales_path . "." . date("Ymdhis") . ".json");
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
        if (isset(DataInput['htmlcontent']) && strlen(DataInput['htmlcontent']) > 0) {
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
        return ($Bearer);
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
function GetConfig()
{
    $config = [];
    $config['LIGHTWEB_ENVIRONMENT'] = LIGHTWEB_ENVIRONMENT;
    $config['LIGHTWEB_PRODUCTION'] = LIGHTWEB_PRODUCTION;
    $config['LIGHTWEB_STAGE'] = LIGHTWEB_STAGE;
    $config['LIGHTWEB_LANG'] = LIGHTWEB_LANG;
    $config['LIGHTWEB_VERSION'] = LIGHTWEB_VERSION;
    $config['LIGHTWEB_DEBUG'] = LIGHTWEB_DEBUG;
    $config['LIGHTWEB_MINIFY'] = LIGHTWEB_MINIFY;
    $config['LIGHTWEB_DB'] = LIGHTWEB_DB;
    $config['LIGHTWEB_DB_HOST'] = LIGHTWEB_DB_HOST;
    $config['LIGHTWEB_DB_USER'] = LIGHTWEB_DB_USER;
    $config['LIGHTWEB_DB_PASS'] = LIGHTWEB_DB_PASS;
    $config['LIGHTWEB_DB_NAME'] = LIGHTWEB_DB_NAME;
    $config['LIGHTWEB_DB_PREFIX'] = LIGHTWEB_DB_PREFIX;
    $config['LIGHTWEB_DB_PORT'] = LIGHTWEB_DB_PORT;
    $config['LIGHTWEB_DB_CHARSET'] = LIGHTWEB_DB_CHARSET;
    $config['LIGHTWEB_DB_COLLATE'] = LIGHTWEB_DB_COLLATE;
    $config['LIGHTWEB_NIZU_TOKEN'] = LIGHTWEB_NIZU_TOKEN;
    $config['LIGHTWEB_NIZU_CMS'] = LIGHTWEB_NIZU_CMS;
    $config['GOOGLE_UA'] = GOOGLE_UA;
    $config['FACEBOOK_PIXEL_ID'] = FACEBOOK_PIXEL_ID;
    if (defined("HUBSPOT_ID")) {
        $config['HUBSPOT_ID'] = HUBSPOT_ID;
    }
    $siteconfig = json_decode(file_get_contents(LIGHTWEB_PATH . "lightweb/pages/siteconfig.json"));
    response(true, ["config" => $config, "siteconfig" => $siteconfig]);
}