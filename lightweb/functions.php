<?php
function defi18n()
{
    $locales_file = LIGHTWEB_LOCALES_PATH . LIGHTWEB_URI['lang'] . ".json";
    if (!file_exists($locales_file)) {
        $lang = "en";
        $locales_file = LIGHTWEB_LOCALES_PATH . $lang . ".json";
    }
    define("i18Translations", json_decode(file_get_contents($locales_file), true));
}
function i18nString($i18key)
{
    if (!defined("i18Translations")) {
        defi18n();
    }
    foreach (i18Translations as $key => $value) {
        if ($key == $i18key) {
            return $value;
        }
    }
}
function i18n($fullpage)
{
    if (!defined("i18Translations")) {
        defi18n();
    }
    foreach (i18Translations as $key => $value) {
        $fullpage = str_replace("{{{$key}}}", $value, $fullpage);
    }
    return $fullpage;
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