<?php
/**
 * DO NOT INSERT YOUR CODE HERE! THIS FILE WILL BE REWRITE IN THE NEXT UPDATE
 * USE ONLY FILES THAT BEGIN BY my_
 * 
 * @author Ruvenss G. Wilches <ruvenss@gmail.com>
 */
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
function LoadPlugins($uri, $fullpage, $lang)
{
    $plugins = glob(LIGHTWEB_PATH . 'lightweb/plugins/*.{php}', GLOB_BRACE);
    if (count($plugins) > 0) {
        foreach ($plugins as $plugin) {
            $plug_arr = explode("/", $plugin);
            $plugfunction = end($plug_arr);
            $plugfunction = str_replace(".php", "", $plugfunction);
            include_once($plugin);
            if (function_exists($plugfunction)) {
                $fullpage = $plugfunction($fullpage, $lang, $uri);
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
function buildManifest($lang)
{
    if (strlen($lang) == 2) {
        $manifest_path = dirname(dirname(__FILE__)) . "/manifest_$lang.json";
        $icon_sizes = ["36x36", "48x48", "72x72", "96x96", "144x144", "192x192", "256x256", "384x384", "512x512"];
        $icons = [];
        for ($i = 0; $i < sizeof($icon_sizes); $i++) {
            $iconsize = $icon_sizes[$i];
            $icons[] = ["src" => "/android-chrome-" . $iconsize . ".png", "sizes" => $iconsize, "type" => "image/png"];
        }
        $manifest = [
            "short_name" => LIGHTWEB_SITE_CONFIG['name'],
            "name" => LIGHTWEB_SITE_CONFIG['name'],
            "description" => i18nString("homedesc", $lang),
            "start_url" => "/$lang/",
            "background_color" => LIGHTWEB_SITE_CONFIG['background_color'],
            "display" => "standalone",
            "orientation" => "portrait-primary",
            "theme_color" => LIGHTWEB_SITE_CONFIG['background_color'],
            "icons" => $icons
        ];
        $manifest_json = json_encode($manifest, JSON_PRETTY_PRINT);
        file_put_contents($manifest_path, $manifest_json);
    }
    /* Microsoft App */
    $manifest_xml = dirname(dirname(__FILE__)) . "/browserconfig.xml";
    $manifest_xmlcontent = '<?xml version="1.0" encoding="utf-8"?>
<browserconfig>
    <msapplication>
        <tile>
            <square70x70logo src="/mstile-70x70.png"/>
            <square150x150logo src="/mstile-150x150.png"/>
            <square310x310logo src="/mstile-310x310.png"/>
            <wide310x150logo src="/mstile-310x150.png"/>
            <TileColor>#ffffff</TileColor>
        </tile>
    </msapplication>
</browserconfig>';
    file_put_contents($manifest_xml, $manifest_xmlcontent);
}
function html_elegant_encode($content)
{
    $content = str_replace(["´", "'", "’"], "&apos;", $content);
    $content = str_replace("À", "&Agrave;", $content);
    $content = str_replace("à", "&agrave;", $content);
    $content = str_replace("Â", "&Acirc;", $content);
    $content = str_replace("â", "&acirc;", $content);
    $content = str_replace("Æ", "&AElig;", $content);
    $content = str_replace("æ", "&aelig;", $content);
    $content = str_replace("Ç", "&Ccedil;", $content);
    $content = str_replace("ç", "&ccedil;", $content);
    $content = str_replace("È", "&Egrave;", $content);
    $content = str_replace("è", "&egrave;", $content);
    $content = str_replace("É", "&Eacute;", $content);
    $content = str_replace("é", "&eacute;", $content);
    $content = str_replace("Ê", "&Ecirc;", $content);
    $content = str_replace("ê", "&ecirc;", $content);
    $content = str_replace("Ë", "&Euml;", $content);
    $content = str_replace("ë", "&euml;", $content);
    $content = str_replace("Î", "&Icirc;", $content);
    $content = str_replace("î", "&icirc;", $content);
    $content = str_replace("Ï", "&Iuml;", $content);
    $content = str_replace("ï", "&iuml;", $content);
    $content = str_replace("Ô", "&Ocirc;", $content);
    $content = str_replace("ô", "&ocirc;", $content);
    $content = str_replace("Œ", "&OElig;", $content);
    $content = str_replace("œ", "&oelig;", $content);
    $content = str_replace("Ù", "&Ugrave;", $content);
    $content = str_replace("ù", "&ugrave;", $content);
    $content = str_replace("Û", "&Ucirc;", $content);
    $content = str_replace("û", "&ucirc;", $content);
    $content = str_replace("Ü", "&Uuml;", $content);
    $content = str_replace("ü", "&uuml;", $content);
    $content = str_replace("Ý", "&Yacute;", $content);
    $content = str_replace("ý", "&yacute;", $content);
    $content = str_replace("Þ", "&THORN;", $content);
    $content = str_replace("þ", "&thorn;", $content);
    $content = str_replace("ß", "&szlig;", $content);
    $content = str_replace("ÿ", "&yuml;", $content);
    $content = str_replace("ñ", "&ndash;", $content);
    $content = str_replace("Ñ", "&Ntilde;", $content);
    $content = str_replace("«", "&laquo;", $content);
    $content = str_replace("»", "&raquo;", $content);
    return $content;
}