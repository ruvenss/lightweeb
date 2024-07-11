<?php
function wp_article_update()
{
    if (!DataInput == null) {
        if (!defined("wp_secret")) {
            response(false, ["message" => "wp_secret is not defined in my_config."], 10, "wp_secret is not defined in my_config.");
        }
        if (!defined("wp_token")) {
            response(false, ["message" => "wp_token is not defined in my_config."], 10, "wp_token is not defined in my_config.");
        }
        if (isset(DataInput['post_id']) && DataInput['post_id'] > 0 && isset(DataInput['update'])) {
            if (isset(DataInput['secret'])) {
                $secret = trim(DataInput['secret']);
                if ($secret == wp_secret) {
                    switch (DataInput['post_type']) {
                        case 'post':
                        case 'page':
                            break;
                        case 'nav_menu_item':
                            # soon to be done
                            break;
                        case 'popup':
                        case 'popup_theme':
                            # soon to be done
                            break;
                        case 'slide':
                            # soon to be done
                            break;
                        default:
                            # code...
                            break;
                    }
                    $post_id = DataInput['post_id'];
                    response(true, ["post_id" => $post_id]);
                } else {
                    response(false, ["message" => "WordPress Secret incorrect"], 10, "The WordPress Secret does not match with the local secret");
                }
            } else {
                response(false, ["message" => "WordPress Secret Missing"], 10, "WP secret is missing ");
            }
        } else {
            response(false, ["message" => "No Post ID or Key."], 10, "wp_article_update update also missing");
        }
    } else {
        response(false, ["DataInput" => null]);
    }
}
function wp_get_branch_id($post_id)
{

}
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