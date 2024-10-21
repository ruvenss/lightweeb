<?php
/**
 * DO NOT INSERT YOUR CODE HERE! THIS FILE WILL BE REWRITE IN THE NEXT UPDATE
 * USE ONLY FILES THAT BEGIN BY my_
 * 
 * @author Ruvenss G. Wilches <ruvenss@gmail.com>
 */

function render_page($page = "home", $lang = "")
{
    if (!defined("LW_CODEVER")) {
        define("LW_CODEVER", json_decode(file_get_contents(LIGHTWEB_PATH . "lightweb/lightweb.json"), true)['version']);
    }
    if ($page == "") {
        $page = "home";
    }
    if ($lang == "") {
        $lang = LIGHTWEB_URI['lang'];
    }
    if (!file_exists(LIGHTWEB_PATH . "lightweb/publish")) {
        mkdir(LIGHTWEB_PATH . "lightweb/publish");
    }
    if (!file_exists(LIGHTWEB_PUBLISH_PATH . "versions.json")) {
        file_put_contents(LIGHTWEB_PUBLISH_PATH . "versions.json", '{"v":1}');
    }
    $version_data = json_decode(file_get_contents(LIGHTWEB_PUBLISH_PATH . "versions.json"), true);
    $jsvendors = '<script type="text/javascript" id="lightweb-vendors-js" src="/vendors.js?v=3.0.37"></script>' . "\n</body>";
    $jsvendors_files = scandir(LIGHTWEB_PATH . 'lightweb/jscode', SCANDIR_SORT_ASCENDING);
    $jsvendors_path = dirname(dirname(__FILE__)) . "/vendors.js";
    if (count($jsvendors_files)) {
        $avoid_files = [".", "..", "facebook_pixel.js", "google_ua.js", "service-worker.js"];
        $jsvendors_code = '/* LightWeb 3.0.37 Standard JS Vendors   */' . "\n";
        foreach ($jsvendors_files as $jsvendors_file) {
            if (!in_array($jsvendors_file, $avoid_files)) {
                $jsvendors_code .= '/* ' . $jsvendors_file . ' version [ ' . filectime(LIGHTWEB_PATH . 'lightweb/jscode/' . $jsvendors_file) . ' ] */' . "\n";
                $jsvendors_code .= file_get_contents(LIGHTWEB_PATH . 'lightweb/jscode/' . $jsvendors_file);
            }
        }
        if (strlen($lang) == 2) {
            $jsvendors_code = str_replace("{{lang_lc}}", $lang, $jsvendors_code);
        }
        $jsvendors_code = str_replace("{{version}}", $version_data['v'], $jsvendors_code);
        if (publishing) {
            file_put_contents(LIGHTWEB_PUBLISH_PATH . "uncompress/vendors.js", $jsvendors_code);
        } else {
            file_put_contents($jsvendors_path, $jsvendors_code);
        }
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
            if (!file_exists(LIGHTWEB_PAGES_HEADERS_PATH)) {
                mkdir(LIGHTWEB_PAGES_HEADERS_PATH);
            }
            if (!file_exists(LIGHTWEB_PAGES_HEADERS_PATH . LIGHTWEB_TREE[$page]['header'])) {
                touch(LIGHTWEB_PAGES_HEADERS_PATH . LIGHTWEB_TREE[$page]['header']);
            }
            if (!file_exists(LIGHTWEB_PAGES_FOOTERS_PATH)) {
                mkdir(LIGHTWEB_PAGES_FOOTERS_PATH);
            }
            if (!file_exists(LIGHTWEB_PAGES_FOOTERS_PATH . LIGHTWEB_TREE[$page]['footer'])) {
                touch(LIGHTWEB_PAGES_FOOTERS_PATH . LIGHTWEB_TREE[$page]['footer']);
            }
            $headerhtml = file_get_contents(LIGHTWEB_PAGES_HEADERS_PATH . LIGHTWEB_TREE[$page]['header']);
            // Insert BreadCrumbs Snippet
            $headerhtml = str_replace("{{title}}", i18nString(LIGHTWEB_TREE[$page]['titlei18n'], $lang), $headerhtml);
            $headerhtml = str_replace("{{lang_lc}}", i18nString("lang_lc", $lang), $headerhtml);
            $headerhtml = str_replace("{{author}}", "LightWeb v" . LW_CODEVER, $headerhtml);
            $headerhtml = str_replace("{{description}}", i18nString(LIGHTWEB_TREE[$page]['descriptioni18n'], $lang), $headerhtml);
            // Insert Other languages references
            $tagheader = "";
            if (!defined("locales")) {
                GetLanguages();
            }
            foreach (locales as $langheader) {
                if ($langheader === $lang) {

                } else {
                    $tagheader .= '<link rel="alternate" hreflang="' . $langheader . '" href="https://' . LIGHTWEB_PRODUCTION . '/' . $langheader . LIGHTWEB_TREE[$page]['url'] . '">' . "\n";
                }
            }
            $headerhtml = str_replace("<head>", "<head>\n\t$tagheader", $headerhtml);
            // Insert Manifest for offline
            if (defined("LIGHTWEB_CACHE")) {
                if (LIGHTWEB_CACHE) {
                    $manifesttag = '<link rel="manifest" href="/manifest_' . $lang . '.json" />';
                    buildManifest($lang);
                    $headerhtml = str_replace("<head>", "<head>\n\t$manifesttag", $headerhtml);
                }
            } else {
                $manifesttag = '<link rel="manifest" href="/manifest_' . $lang . '.json" />';
                buildManifest($lang);
                $headerhtml = str_replace("<head>", "<head>\n\t$manifesttag", $headerhtml);
            }

            if (isset(LIGHTWEB_TREE[$page]['featured_image'])) {
                $headerhtml = str_replace("</head>", ogcard(i18nString(LIGHTWEB_TREE[$page]['titlei18n'], $lang), i18nString(LIGHTWEB_TREE[$page]['descriptioni18n'], $lang), "https://" . LIGHTWEB_PRODUCTION . LIGHTWEB_TREE[$page]['url'], LIGHTWEB_TREE[$page]['featured_image']) . "\n</head>", $headerhtml);
            }
            if (LIGHTWEB_MINIFY) {
                $headerhtml = minify($headerhtml);
            }
            $headerhtml = str_replace("</title>", "</title>\n" . snippet_breadcrumb($page, $lang), $headerhtml);
            $bodyhtml = file_get_contents(LIGHTWEB_PAGES_PATH . LIGHTWEB_TREE[$page]['path']);
            if (LIGHTWEB_MINIFY) {
                $bodyhtml = minify($bodyhtml);
            }
            $footerhtml = file_get_contents(LIGHTWEB_PAGES_FOOTERS_PATH . LIGHTWEB_TREE[$page]['footer']);
            if (strlen(GOOGLE_UA) > 0) {
                $google_script = '<!-- Google tag (gtag.js) -->' . "\n" . '<script async src="https://www.googletagmanager.com/gtag/js?id=UA-' . GOOGLE_UA . '"></script>' . "\n</body>";
                $footerhtml = str_replace("</body>", $google_script, $footerhtml);
            }
            $footerhtml = str_replace("</body>", $jsvendors, $footerhtml);
            if (LIGHTWEB_MINIFY) {
                $footerhtml = minify($footerhtml);
            }
            $fullpage = $headerhtml . "\n" . $bodyhtml . "\n" . $footerhtml;
            $fullpage = LoadPlugins($page, $fullpage, $lang);
            return ($fullpage);
        } else {
            if (isset(LIGHTWEB_TREE['404'])) {
                return render_404($lang);
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
function ogcard($title, $descripion, $url, $ogimage)
{
    /* Standard OG Card */
    $ogcard = '<meta property="og:url" content="' . $url . '">
<meta property="og:type" content="article:publisher">
<meta property="og:title" content="' . $title . '">
<meta property="og:description" content="' . $descripion . '">
<meta property="og:image" content="' . $ogimage . '">
<meta property="og:site_name" content="' . LIGHTWEB_PRODUCTION . '">';
    return $ogcard;
}
function metatags()
{
    $m = '<meta name="googlebot" content="notranslate">';
}
function snippet_breadcrumb($page, $lang = "")
{
    $Breadcrumbs = explode("/", $page);
    if (publishing) {
        $uri = "https://" . LIGHTWEB_PRODUCTION . "/" . $lang;
    } else {
        $uri = "https://" . LIGHTWEB_PRODUCTION . "/" . LIGHTWEB_URI['lang'];
    }
    $i = 0;
    foreach ($Breadcrumbs as $Breadcrumb) {
        $i++;
        if ($i == 1) {
            //die($Breadcrumb);
            if (isset(LIGHTWEB_TREE[$Breadcrumb])) {
                $BreadCrumbOBJ = LIGHTWEB_TREE[$Breadcrumb];
            } else {
                $BreadCrumbOBJ = ["url" => ""];
            }
        } else {
            $thispage = implode('/', array_slice($Breadcrumbs, 0, $i));
            if (isset(LIGHTWEB_TREE[$thispage])) {
                $BreadCrumbOBJ = LIGHTWEB_TREE[$thispage];
            } else {
                $BreadCrumbOBJ = ["url" => ""];
            }
        }
        if (isset($BreadCrumbOBJ['titlei18n'])) {
            $BreadCrumbName = i18nString($BreadCrumbOBJ['titlei18n'], $lang);
        } else {
            $BreadCrumbName = "title";
        }
        $snipped_child[] = '{
        "@type": "ListItem",
        "position": ' . $i . ',
        "name": "' . $BreadCrumbName . '",
        "item": "' . $uri . $BreadCrumbOBJ['url'] . '"
      }';
    }
    $s = '<script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "BreadcrumbList",
      "itemListElement": [' . implode(",", $snipped_child) . ']
    }
</script>
    ';
    return $s;
}
function snippet($title, $descripion, $type, $author = "", $parameter1 = "", $parameter2 = "")
{
    $s = '<script type="application/ld+json">
    {
        "@context": "https://schema.org/",
        ';
    switch ($type) {
        case 'recipe':
            $s .= '"@type": "Recipe",
        "name": "' . $title . '",
        "author": {
            "@type": "Person",
            "name": "' . $author . '"
        },
        "datePublished": "' . date("Y-m-d") . '",
        "description": "' . $descripion . '",
        "prepTime": "PT' . $parameter1 . 'M"';
            break;
        case 'organization':
            $s .= '"@type": "Organization",
        "image": "' . LIGHTWEB_SITE_CONFIG['image'] . '",
        "url": "https://' . LIGHTWEB_PRODUCTION . '",
        "sameAs": ["https://example.net/profile/example1234", "https://example.org/example1234"],
        "logo": "' . LIGHTWEB_SITE_CONFIG['logo'] . '",
        "name": "' . LIGHTWEB_SITE_CONFIG['name'] . '",
        "description": "' . $descripion . '",
        "email": "' . LIGHTWEB_SITE_CONFIG['email'] . '",
        "telephone": "' . LIGHTWEB_SITE_CONFIG['phone'] . '",
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "' . LIGHTWEB_SITE_CONFIG['locations'][0]['address'] . '",
            "addressLocality": "' . LIGHTWEB_SITE_CONFIG['locations'][0]['city'] . '",
            "addressCountry": "' . LIGHTWEB_SITE_CONFIG['locations'][0]['country'] . '",
            "addressRegion": "' . LIGHTWEB_SITE_CONFIG['locations'][0]['region'] . '",
            "postalCode": "' . LIGHTWEB_SITE_CONFIG['locations'][0]['cp'] . '"
        },
        "vatID": "' . LIGHTWEB_SITE_CONFIG['vat'] . '",
        "iso6523Code": "' . LIGHTWEB_SITE_CONFIG['iso'] . '"';
            break;
        default:
            # code...
            break;
    }
    $s .= '
    }
    </script>';
}
function render_404($lang = "")
{
    $page = "404";
    $headerhtml = file_get_contents(LIGHTWEB_PAGES_HEADERS_PATH . LIGHTWEB_TREE[$page]['header']);
    $headerhtml = str_replace("{{title}}", i18nString(LIGHTWEB_TREE[$page]['titlei18n'], $lang), $headerhtml);
    $headerhtml = str_replace("{{description}}", i18nString(LIGHTWEB_TREE[$page]['descriptioni18n'], $lang), $headerhtml);
    $headerhtml = str_replace("{{author}}", "LightWeb v" . LW_CODEVER, $headerhtml);
    if (isset(LIGHTWEB_TREE[$page]['featured_image'])) {
        $headerhtml = str_replace("</head>", ogcard(i18nString(LIGHTWEB_TREE[$page]['titlei18n'], $lang), i18nString(LIGHTWEB_TREE[$page]['descriptioni18n'], $lang), "https://" . LIGHTWEB_PRODUCTION . LIGHTWEB_TREE[$page]['url'], LIGHTWEB_TREE[$page]['featured_image']) . "\n</head>", $headerhtml);
    }
    $bodyhtml = file_get_contents(LIGHTWEB_PAGES_PATH . LIGHTWEB_TREE[$page]['path']);
    $footerhtml = file_get_contents(LIGHTWEB_PAGES_FOOTERS_PATH . LIGHTWEB_TREE[$page]['footer']);
    $fullpage = $headerhtml . "\n" . $bodyhtml . "\n" . $footerhtml;
    $fullpage = LoadPlugins($page, $fullpage, $lang);
    return ($fullpage);
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