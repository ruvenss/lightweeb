<?php
function render_page($page = "home")
{
    if ($page == "") {
        $page = "home";
    }
    if (isset(LIGHTWEB_TREE[$page])) {
        $headerhtml = file_get_contents(LIGHTWEB_PAGES_HEADERS_PATH . LIGHTWEB_TREE[$page]['header']);
        $headerhtml = str_replace("{{title}}", i18nString(LIGHTWEB_TREE[$page]['titlei18n']), $headerhtml);
        $bodyhtml = file_get_contents(LIGHTWEB_PAGES_PATH . LIGHTWEB_TREE[$page]['path']);
        $footerhtml = file_get_contents(LIGHTWEB_PAGES_FOOTERS_PATH . LIGHTWEB_TREE[$page]['footer']);
        return($headerhtml . "\n" . $bodyhtml . "\n" . $footerhtml);
    } else {
        render_404();
    }
}
function render_404()
{
    return "404";
}