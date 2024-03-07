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
            // Insert BreadCrumbs Snippet

            $headerhtml = str_replace("{{title}}", i18nString(LIGHTWEB_TREE[$page]['titlei18n']), $headerhtml);
            $headerhtml = str_replace("{{description}}", i18nString(LIGHTWEB_TREE[$page]['descriptioni18n']), $headerhtml);
            if (LIGHTWEB_MINIFY) {
                $headerhtml = minify($headerhtml);
            }
            $headerhtml = str_replace("</title>", "</title>\n" . snippet_breadcrumb($page), $headerhtml);
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
function ogcard($title, $descripion, $url, $ogimage)
{
    /* Standard OG Card */
    $ogcard = '<meta property="og:url" content="' . $url . '">
<meta property="og:type" content="article:publisher">
<meta property="og:title" content=' . $title . '">
<meta property="og:description" content="' . $descripion . '">
<meta property="og:image" content="' . $ogimage . '">
<meta property="og:site_name" content="' . LIGHTWEB_PRODUCTION . '">';
    return $ogcard;
}
function metatags()
{
    $m = '<meta name="googlebot" content="notranslate">';
}
function snippet_breadcrumb($page)
{
    $Breadcrumbs = explode("/", $page);
    $uri = "https://" . LIGHTWEB_PRODUCTION . "/" . LIGHTWEB_URI['lang'];
    $i = 0;
    foreach ($Breadcrumbs as $Breadcrumb) {
        $i++;
        if ($i == 1) {
            $BreadCrumbOBJ = LIGHTWEB_TREE[$Breadcrumb];
        } else {
            $thispage = implode('/', array_slice($Breadcrumbs, 0, $i));
            $BreadCrumbOBJ = LIGHTWEB_TREE[$thispage];
        }
        $BreadCrumbName = i18nString($BreadCrumbOBJ['titlei18n']);
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