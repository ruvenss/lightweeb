<?php
function wp_article_update()
{
    if (!DataInput == null) {
        //error_log(json_encode(DataInput, JSON_PRETTY_PRINT), 0);
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
                    $branch = null;
                    // Get Stage Tree
                    if (!defined("LIGHTWEB_TREE")) {
                        $tree = wp_get_stage_tree();
                        if (count($tree) > 0) {
                            define("LIGHTWEB_TREE", $tree);
                        } else {
                            response(false, ["message" => "You can only edit stage enviroment"], 10, "You can only edit stage enviroment, tree.json not defined");
                        }
                    }
                    switch (DataInput['post_type']) {
                        case 'post':
                        case 'page':
                            $post_id = DataInput['post_id'];
                            $branch = wp_get_branch_id($post_id);
                            $content = base64_decode(DataInput['post_content']);
                            if ($branch == null) {
                                $post_permalink = wp_permalink(DataInput['post_permalink']);
                                $site_url = DataInput['site_url'];
                                $post_permalink = str_replace($site_url, "", $post_permalink);
                                $new_tree = wp_create_branch($post_permalink, DataInput['post_title'], DataInput['post_description'], DataInput['featured_image'], DataInput['header'], DataInput['footer']);
                                $page_file = getcwd() . "/../../lightweb/pages" . $post_permalink . "/index.html";
                                file_put_contents($page_file, $content);
                            } else {
                                #update branch
                                $page_file = getcwd() . "/../../lightweb/pages" . $branch . "/index.html";
                                file_put_contents($page_file, $content);
                            }
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
                    response(true, ["post_id" => $post_id, "branch" => $branch]);
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
function wp_create_branch($post_permalink, $title, $description, $featured_image, $header, $footer)
{
    $tree = LIGHTWEB_TREE;
    $branch_pieces = explode("/", $post_permalink);
    $titlei18n = wp_titlei18n($post_permalink);
    $branch = [
        "titlei18n" => $titlei18n,
        "descriptioni18n" => $titlei18n . "desc",
        "robots" => "Follow",
        "path" => $post_permalink . "index.html",
        "author" => "Light Web",
        "url" => $post_permalink,
        "header" => $header,
        "footer" => $footer,
        "featured_image" => $featured_image,
        "version" => "1",
        "type" => "page",
        "branches" => count($branch_pieces),
        "static" => true
    ];
    $tree[$post_permalink] = $branch;
    // rewrite tree
    // error_log(json_encode($tree, JSON_PRETTY_PRINT), 0);
    $tree_path = getcwd() . "/../../lightweb/pages/tree.json";
    $pages_path = getcwd() . "/../../lightweb/pages";
    $index_path = wp_build_branch($pages_path, $branch_pieces);
    file_put_contents($tree_path, json_encode($tree));
    return $tree;
}
function wp_build_branch($pages_path, $branch_pieces)
{
    $branch_log = "";
    if (count($branch_pieces) > 0) {
        for ($i = 0; $i < sizeof($branch_pieces); $i++) {
            $branch_log .= "/" . $branch_pieces[$i];
            if (!file_exists($pages_path . $branch_log)) {
                mkdir($pages_path . $branch_log);
                touch($pages_path . $branch_log . "/index.html");
            }
        }
    } else {
        if (!file_exists($pages_path . $branch_pieces[0])) {
            $branch_log .= "/" . $branch_pieces[0];
            mkdir($pages_path . $branch_log);
            touch($pages_path . $branch_log . "/index.html");
        }
    }
    return $pages_path . $branch_log;
}
function wp_descriptioni18n($description)
{

}
function wp_titlei18n($title)
{
    $new_title = str_replace([" ", "/", "%", "*", "'", '"', "\n", "\r", "\t", "{", "}", "[", "]", "<", ">", "?", "&", ":", "`"], "-", $title);
    $new_title = str_replace([" ", "/", "%", "*", "'", '"', "\n", "\r", "\t", "{", "}", "[", "]", "<", ">", "?", "&", ":", "`"], "-", $title);
    $new_title = str_replace(["-B\\", "\\"], "", $title);
    $new_title = str_replace(["---", "--"], "-", $title);
    $new_title = str_replace([" ", "/", "%", "*", "'", '"', "\n", "\r", "\t", "{", "}", "[", "]", "<", ">", "?", "&", ":", "`"], "-", $title);
    $new_title = rtrim($new_title, '-');
    $new_title = ltrim($new_title, '-');
    $new_title = rtrim($new_title, '/');
    $new_title = ltrim($new_title, '/');
    $new_title = rtrim($new_title, '\\');
    $new_title = rtrim($new_title, '//');
    return trim($new_title);
}
function wp_permalink($post_permalink)
{
    $post_permalink = rtrim($post_permalink, '-');
    $post_permalink = ltrim($post_permalink, '-');
    $post_permalink = rtrim($post_permalink, '/');
    $post_permalink = ltrim($post_permalink, '/');
    $post_permalink = rtrim($post_permalink, '\\');
    $post_permalink = rtrim($post_permalink, '//');
    return trim($post_permalink);
}
function wp_clean_content($content)
{
    $content = str_replace("€", "&euro;", $content);
    $content = str_replace("œ", "&oelig;", $content);
    $content = str_replace("è", "&egrave;", $content);
    $content = str_replace("È", "&Egrave;", $content);
    $content = str_replace("Ç", "&Ccedil;", $content);
    $content = str_replace("ç", "&ccedil;", $content);
    $content = str_replace("É", "&Eacute;", $content);
    $content = str_replace("é", "&eacute;", $content);
    $content = str_replace("Ê", "&Ecirc;", $content);
    $content = str_replace("À", "&Agrave;", $content);
    $content = str_replace("à", "&agrave;", $content);
    $content = str_replace("Â", "&Acirc;", $content);
    $content = str_replace("â", "&acirc;", $content);
    return $content;
}
function wp_get_branch_id($post_id)
{
    $branch_id = null;
    if ($post_id > 0 && defined("LIGHTWEB_TREE")) {
        foreach (LIGHTWEB_TREE as $branch => $values) {
            if (isset($values['post_id']) && $values['post_id'] == $post_id) {
                $branch_id = $branch;
            }
        }
    }
    return $branch_id;
}
function wp_get_stage_tree()
{
    $tree = [];
    $tree_path = getcwd() . "/../../lightweb/pages/tree.json";
    if (file_exists($tree_path)) {
        $tree = json_decode(file_get_contents($tree_path), true);
    }
    return $tree;
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