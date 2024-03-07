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
function snippet($title, $descripion, $type, $image = "", $author = "", $parameter1 = "", $parameter2 = "")
{
    switch ($type) {
        case 'recipe':
            $s = '
    <script type="application/ld+json">
    {
      "@context": "https://schema.org/",
      "@type": "Recipe",
      "name": "' . $title . '",
      "author": {
        "@type": "Person",
        "name": "' . $author . '"
      },
      "datePublished": "' . date("Y-m-d") . '",
      "description": "' . $descripion . '",
      "prepTime": "PT' . $parameter1 . 'M"
    }
    </script>';
            break;
        case 'organization':
            $s = '
     <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Organization",
      "image": "' . $image . '",
      "url": "https://' . LIGHTWEB_PRODUCTION . '",
      "sameAs": ["https://example.net/profile/example1234", "https://example.org/example1234"],
      "logo": "https://www.example.com/images/logo.png",
      "name": "' . $title . '",
      "description": "' . $descripion . '",
      "email": "contact@example.com",
      "telephone": "+47-99-999-9999",
      "address": {
        "@type": "PostalAddress",
        "streetAddress": "Rue Improbable 99",
        "addressLocality": "Paris",
        "addressCountry": "FR",
        "addressRegion": "Ile-de-France",
        "postalCode": "75001"
      },
      "vatID": "FR12345678901",
      "iso6523Code": "0199:724500PMK2A2M1SQQ228"
    }';
            break;
        default:
            # code...
            break;
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