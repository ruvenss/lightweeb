<?php
function GetLanguages()
{
    if (!defined("locales")) {
        $languages_files = glob(LIGHTWEB_LOCALES_PATH . '*.{json}', GLOB_BRACE);
        $languages = [];
        foreach ($languages_files as $language_file) {
            $language = right(str_replace(".json", "", $language_file), 2);
            $languages[] = $language;
        }
        define("locales", $languages);
    }
}
function LoadPlugins($uri, $fullpage)
{
    $plugins = glob(LIGHTWEB_PATH . 'lightweb/plugins/*.{php}', GLOB_BRACE);
    if (count($plugins) > 0) {
        foreach ($plugins as $plugin) {
            $plug_arr = explode("/", $plugin);
            $plugfunction = end($plug_arr);
            $plugfunction = str_replace(".php", "", $plugfunction);
            include_once ($plugin);
            if (function_exists($plugfunction)) {
                $fullpage = $plugfunction($fullpage);
            }
        }
    }
    return $fullpage;
}
function defi18n()
{
    $locales_file = LIGHTWEB_LOCALES_PATH . LIGHTWEB_URI['lang'] . ".json";
    if (!file_exists($locales_file)) {
        $lang = "en";
        $locales_file = LIGHTWEB_LOCALES_PATH . $lang . ".json";
    }
    define("i18Translations", json_decode(file_get_contents($locales_file), true));
}
function defi18nPublishing($lang)
{
    $locales_file = LIGHTWEB_LOCALES_PATH . $lang . ".json";
    if (!file_exists($locales_file)) {
        $lang = "en";
        $locales_file = LIGHTWEB_LOCALES_PATH . $lang . ".json";
    }
    return (json_decode(file_get_contents($locales_file), true));
}
function i18nString($i18key, $lang = "")
{
    if (publishing) {
        $translations = defi18nPublishing($lang);
        foreach ($translations as $key => $value) {
            if ($key == $i18key) {
                return $value;
            }
        }
    } else {
        if (!defined("i18Translations")) {
            defi18n();
        }
        foreach (i18Translations as $key => $value) {
            if ($key == $i18key) {
                return $value;
            }
        }
    }
    return "";
}
function i18n($fullpage = "", $lang = "")
{
    if (publishing) {
        $translations = defi18nPublishing($lang);
        if (!$fullpage == null && strlen($fullpage) > 0) {
            foreach ($translations as $key => $value) {
                $fullpage = str_replace("{{{$key}}}", $value, $fullpage);
            }
            return $fullpage;
        } else {
            return null;
        }
    } else {
        if (!defined("i18Translations")) {
            defi18n();
        }
        if (!$fullpage == null && strlen($fullpage) > 0) {
            foreach (i18Translations as $key => $value) {
                $fullpage = str_replace("{{{$key}}}", $value, $fullpage);
            }
            return $fullpage;
        } else {
            return null;
        }
    }
}
function left($str, $length)
{
    return substr($str, 0, $length);
}

function right($str, $length)
{
    return substr($str, -$length);
}
function uripage()
{
    $uriarr = [];
    foreach ($_REQUEST as $key => $value) {
        if (left($key, 4) == "page") {
            if (!$value == "" || !$value == null) {
                $uriarr[] = $value;
            }
        }
    }
    $page_id = implode("/", $uriarr);
    return $page_id;
}
function buildManifest()
{

}