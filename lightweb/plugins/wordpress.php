<?php
/**
 * @name WordPress Plugin Connectivity
 * @author Ruvenss G. Wilches <ruvenss@gmail.com>
 * @depends LIGHTWEB_DB=true
 */
// $fullpage = str_replace("{{this_year}}", date("Y"), $fullpage);
if (LIGHTWEB_DB) {
    if (str_contains($fullpage, '{{wp_categories}}')) {
        $fullpage = str_replace("{{wp_categories}}", wp_categories(), $fullpage);
    }
    if (str_contains($fullpage, '{{wp_nav}}')) {
        $fullpage = str_replace("{{wp_nav}}", wp_nav(), $fullpage);
    }
}
function wp_search()
{
    return null;
}
function wp_nav()
{
    return null;
}
function wp_categories()
{
    $categories = sqlSelectRows("view_wp_categories", "term_taxonomy_id,term_id,description,term_title", "");
    $categories_template = '
    <div class="lw_categories" id="lw_categories" data-id="lw_categories">
        <ul class="lw_categories_content">
            ';
    foreach ($categories as $category) {
        $categories_template .= '<li class="lw_category_item" id="lw_cat_' . $category['term_id'] . '" data-id="' . $category['term_id'] . '"><a href="#" target="_self">' . $category['term_title'] . '</a></li>' . "\n";
    }
    $categories_template .= '
        </ul>
    </div>';
    return $categories_template;
}