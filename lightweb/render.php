<?php
function render_page($page = "home")
{
    if ($page == "") {
        $page = "home";
    }
    if (isset(LIGHTWEB_TREE[$page])) {
        $publish_from = LIGHTWEB_TREE[$page]['publish_from'] ?? null;
        $publish_until = LIGHTWEB_TREE[$page]['publish_until'] ?? null;
        $publish_ok = true;
        $TodayDate = new DateTime(); // Today
        if ($publish_from !== null) {
            $publish_from_date = new DateTime($publish_from);
            if ($TodayDate > $publish_from_date) {
                $publish_ok = true;
            }
        }
        if ($publish_until !== null) {
            $publish_until_date = new DateTime($publish_until);
            if ($TodayDate < $publish_until_date) {
                $publish_ok = true;
            }
        }
        if ($publish_ok) {
            $headerhtml = file_get_contents(LIGHTWEB_PAGES_HEADERS_PATH . LIGHTWEB_TREE[$page]['header']);
            $headerhtml = str_replace("{{title}}", i18nString(LIGHTWEB_TREE[$page]['titlei18n']), $headerhtml);
            $headerhtml = str_replace("{{description}}", i18nString(LIGHTWEB_TREE[$page]['descriptioni18n']), $headerhtml);
            if (LIGHTWEB_MINIFY) {
                $headerhtml = minify($headerhtml);
            }
            $bodyhtml = file_get_contents(LIGHTWEB_PAGES_PATH . LIGHTWEB_TREE[$page]['path']);
            if (LIGHTWEB_MINIFY) {
                $bodyhtml = minify($bodyhtml);
            }
            $footerhtml = file_get_contents(LIGHTWEB_PAGES_FOOTERS_PATH . LIGHTWEB_TREE[$page]['footer']);
            if (LIGHTWEB_MINIFY) {
                $footerhtml = minify($footerhtml);
            }
            $fullpage = $headerhtml . "\n" . $bodyhtml . "\n" . $footerhtml;
            $fullpage = LoadPlugins($page, $fullpage);
            return($fullpage);
        } else {
            if (isset(LIGHTWEB_TREE['404'])) {
                return render_404();
            } else {
                return "404";
            }
        }
    } else {
        if (isset(LIGHTWEB_TREE['404'])) {
            return render_404();
        } else {
            return "404";
        }
    }
}
function render_404()
{
    $page = "404";
    $headerhtml = file_get_contents(LIGHTWEB_PAGES_HEADERS_PATH . LIGHTWEB_TREE[$page]['header']);
    $headerhtml = str_replace("{{title}}", i18nString(LIGHTWEB_TREE[$page]['titlei18n']), $headerhtml);
    $headerhtml = str_replace("{{description}}", i18nString(LIGHTWEB_TREE[$page]['descriptioni18n']), $headerhtml);
    $bodyhtml = file_get_contents(LIGHTWEB_PAGES_PATH . LIGHTWEB_TREE[$page]['path']);
    $footerhtml = file_get_contents(LIGHTWEB_PAGES_FOOTERS_PATH . LIGHTWEB_TREE[$page]['footer']);
    return($headerhtml . "\n" . $bodyhtml . "\n" . $footerhtml);
}
function minify($buffer)
{
    $search = array(
        '/\>[^\S ]+/s',     // strip whitespaces after tags, except space
        '/[^\S ]+\</s',     // strip whitespaces before tags, except space
        '/(\s)+/s',         // shorten multiple whitespace sequences
        '/<!--(.|\s)*?-->/' // Remove HTML comments
    );
    $replace = array(
        '>',
        '<',
        '\\1',
        ''
    );
    $buffer = preg_replace($search, $replace, $buffer);
    $buffer = str_replace("> <", "><", $buffer);
    $buffer = str_replace(">  <", "><", $buffer);
    return $buffer;
}