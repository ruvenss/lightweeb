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

                    $branch = null;
                    // Get Stage Tree
                    $tree = wp_get_stage_tree();
                    if ($tree == null) {
                        response(false, ["message" => "You can only edit stage enviroment"], 10, "You can only edit stage enviroment, tree.json not defined");
                    }
                    switch (DataInput['post_type']) {
                        case 'post':
                        case 'page':
                            $post_id = DataInput['post_id'];
                            $post_permalink = base64_decode(DataInput['post_permalink']);
                            $branch = wp_get_branch_id($post_id);
                            $content = base64_decode(DataInput['post_content']);
                            $title = base64_decode(DataInput['post_title']);
                            include_once (getcwd() . "/../../lightweb/config.php");
                            $content = wp_clean_content($content);
                            $site_url = DataInput['site_url'];
                            $post_permalink = str_replace($site_url, "", $post_permalink);
                            $new_tree = wp_create_branch($post_permalink, $post_id, $title, DataInput['post_description'], DataInput['featured_image'], DataInput['header'], DataInput['footer']);
                            $page_file = getcwd() . "/../../lightweb/pages" . $post_permalink . "/index.html";
                            file_put_contents($page_file, $content);
                            if (isset(DataInput['post_parent']) && DataInput['post_parent'] > 0) {
                                // Get Post Parent Data
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

                    response(true, ["post_id" => $post_id, "branch" => $branch, "Authorization" => LIGHTWEB_APIKEY]);
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
function wp_create_branch($post_permalink, $post_id, $title, $description, $featured_image, $header, $footer)
{
    $tree = wp_get_stage_tree();
    $branch_pieces = explode("/", $post_permalink);
    $titlei18n = wp_titlei18n($post_permalink);
    wp_update_locales_key($titlei18n, $title);
    wp_update_locales_key($titlei18n . "desc", $description);
    $branch_id = ltrim($post_permalink, "/");
    $branch_id = rtrim($branch_id, "/");
    if (isset($tree[$branch_id])) {
        $version = $tree[$branch_id]['version'] + 1;
    } else {
        $version = 1;

    }
    $tree[$branch_id]['version'] = $version;
    $branch = [
        "titlei18n" => $titlei18n,
        "descriptioni18n" => $titlei18n . "desc",
        "robots" => "Follow",
        "path" => ltrim($post_permalink, "/") . "index.html",
        "author" => "Light Web",
        "url" => $post_permalink,
        "header" => $header,
        "footer" => $footer,
        "featured_image" => $featured_image,
        "version" => $version,
        "type" => "page",
        "post_id" => $post_id,
        "static" => true
    ];
    $tree_branch = ltrim($post_permalink, "/");
    $tree_branch = rtrim($tree_branch, "/");
    $tree[$tree_branch] = $branch;
    wp_write_tree($tree);
    // rewrite tree
    $pages_path = getcwd() . "/../../lightweb/pages";
    wp_build_branch($pages_path, $branch_pieces, $tree);
    wp_add_branch($tree_branch, $branch);
    return $tree;
}
function wp_update_locales_key($key, $value)
{
    $locales_path = getcwd() . "/../../lightweb/locales";
    $key_updated = false;
    $languages_files = scandir($locales_path);
    $languages = [];
    foreach ($languages_files as $language_file) {
        $language = str_replace(".json", "", $language_file);
        if ($language == ".." || $language == ".") {

        } else {
            $languages[] = $language;
        }
    }
    if (count($languages) > 0) {
        for ($i = 0; $i < sizeof($languages); $i++) {
            $language_file = $locales_path . "/" . $languages[$i] . ".json";
            $locales_data = json_decode(file_get_contents($language_file), true);
            $locales_data[$key] = $value;
            file_put_contents($language_file, json_encode($locales_data));
        }
    }
}
function wp_add_branch($tree_branch, $branch_data = null)
{
    $tree = wp_get_stage_tree();
    $tree_branch = html_entity_decode($tree_branch);
    if ($branch_data == null) {
        $post_path = rtrim(ltrim($tree_branch, "/"), "/") . "/index.html";
        $post_path = str_replace("//", "/", $post_path);
        $branch_data = [
            "titlei18n" => "title",
            "descriptioni18n" => "description",
            "robots" => "Follow",
            "path" => $post_path,
            "author" => "Light Web",
            "url" => $tree_branch,
            "header" => "header.html",
            "footer" => "footer.html",
            "featured_image" => "",
            "version" => 1,
            "type" => "page",
            "static" => true
        ];
        $tree[$tree_branch] = $branch_data;
        wp_write_tree($tree);
        return;
    } else {
        $version = $branch_data['version'];
        $version = $version + 1;
        $branch_data['version'] = $version;
    }
    $tree[$tree_branch] = $branch_data;
    // rewrite tree
    wp_write_tree($tree);
    return;
}
function wp_build_branch($pages_path, $branch_pieces, $tree)
{
    $branch_log = "";
    if (count($branch_pieces) > 0) {
        for ($i = 0; $i < sizeof($branch_pieces); $i++) {
            $branch_log .= "/" . $branch_pieces[$i];
            if (!file_exists($pages_path . $branch_log)) {
                mkdir($pages_path . $branch_log);
                touch($pages_path . $branch_log . "/index.html");
            }
            $branch_id = ltrim($branch_log, "/");
            $branch_id = rtrim($branch_id, "/");
            $branch_id = ltrim($branch_id, "/");
            $branch_id = rtrim($branch_id, "/");
            if (!isset($tree[$branch_id]) && strlen($branch_id) > 0) {
                $tree[$branch_id] = [
                    "titlei18n" => "title",
                    "descriptioni18n" => "description",
                    "robots" => "Follow",
                    "path" => $branch_id . "/index.html",
                    "author" => "Light Web",
                    "url" => "/" . $branch_id . "/",
                    "header" => "",
                    "footer" => "",
                    "featured_image" => "",
                    "version" => "1",
                    "type" => "page",
                    "static" => true
                ];
                wp_write_tree($tree);
                wp_add_branch($branch_id);
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
function wp_write_tree($tree)
{
    $tree_path = getcwd() . "/../../lightweb/pages/tree.json";
    file_put_contents($tree_path, json_encode($tree, JSON_UNESCAPED_SLASHES));

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
    $content = str_replace('href="https://' . LIGHTWEB_PRODUCTION . '/', 'href="https://' . LIGHTWEB_PRODUCTION . '/{{lang_lc}}/', $content);
    return $content;
}
function wp_get_branch_id($post_id)
{
    $branch_id = null;
    $tree = wp_get_stage_tree();
    if ($post_id > 0 && sizeof($tree)) {
        foreach ($tree as $branch => $values) {
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