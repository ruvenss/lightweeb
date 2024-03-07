<?php
function render_page($page = "home")
{
    if ($page == "") {
        $page = "home";
    }
    if (isset(LIGHTWEB_TREE[$page])) {
        $headerhtml = file_get_contents(LIGHTWEB_PAGES_HEADERS_PATH . LIGHTWEB_TREE[$page]['header']);
        $headerhtml = str_replace("{{title}}", i18nString(LIGHTWEB_TREE[$page]['titlei18n']), $headerhtml);
        $headerhtml = str_replace("{{description}}", i18nString(LIGHTWEB_TREE[$page]['descriptioni18n']), $headerhtml);
        $bodyhtml = file_get_contents(LIGHTWEB_PAGES_PATH . LIGHTWEB_TREE[$page]['path']);
        $footerhtml = file_get_contents(LIGHTWEB_PAGES_FOOTERS_PATH . LIGHTWEB_TREE[$page]['footer']);
        return($headerhtml . "\n" . $bodyhtml . "\n" . $footerhtml);
    } else {
        if (isset(LIGHTWEB_TREE['404'])) {
            $page = "404";
            $headerhtml = file_get_contents(LIGHTWEB_PAGES_HEADERS_PATH . LIGHTWEB_TREE[$page]['header']);
            $headerhtml = str_replace("{{title}}", i18nString(LIGHTWEB_TREE[$page]['titlei18n']), $headerhtml);
            $headerhtml = str_replace("{{description}}", i18nString(LIGHTWEB_TREE[$page]['descriptioni18n']), $headerhtml);
            $bodyhtml = file_get_contents(LIGHTWEB_PAGES_PATH . LIGHTWEB_TREE[$page]['path']);
            $footerhtml = file_get_contents(LIGHTWEB_PAGES_FOOTERS_PATH . LIGHTWEB_TREE[$page]['footer']);
            return($headerhtml . "\n" . $bodyhtml . "\n" . $footerhtml);
        } else {
            return "404";
        }
    }
}
function render_404()
{
    if (isset(LIGHTWEB_TREE['404'])) {
        return render_page("404");
    } else {
        return "404";
    }
}