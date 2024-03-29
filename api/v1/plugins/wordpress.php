<?php
function wp_search()
{
    if (LIGHTWEB_DB) {
        $results = sqlSelectRows(WP_IDX . "wp_posts", "ID,post_title,post_author,post_date,post_title,post_parent,guid,post_type", "`post_status`='publish' AND (`post_type`='post' OR `post_type`='page')");
        if (sizeof($results)) {
            $authors = sqlSelectRows(WP_IDX . "users", "ID,display_name");
            for ($i = 0; $i < sizeof($results); $i++) {
                $post_author = $results[$i]['post_author'];
                $display_name = wp_get_author_display_name($post_author, $authors);
                $results[$i]['display_name'] = $display_name;
            }
            response(true, ["results" => $results]);
        } else {
            response(false, ["results" => null]);
        }
    } else {
        response(false, ["results" => null]);
    }
}
function wp_get_author_display_name($post_author, $authors)
{
    foreach ($authors as $key => $value) {
        if ($post_author == $key) {
            return ($value);
        }
    }
    return (null);
}