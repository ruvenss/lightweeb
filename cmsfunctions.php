<?php
function create_lw_cms_subpage($page_path,$page,$languages) {
  	// Check if its not file call
  	if (strpos($page, '.') !== false) {
		// Do not create file only if exemptions 
		// TODO Check exceptions file
  	} else {
		mkdir($page_path); 
		touch($page_path."/index.html"); 
		$basicconfig='{"description":"","title":"","subtitle":"","subject":"","summary":"","category":"","topic":"","keywords":"","type":"","published":"true","publish_from":"","publish_until":"","header":"header.html","footer":"footer.html","minify":"true","sitemap":"true","ogimage":"/images/og/sub_'.$page.'.jpg"}';
		for ($i = 0; $i < sizeof($languages); $i++) {
			touch($page_path."/".$languages[$i]."_config.json");
			file_put_contents($page_path."/".$languages[$i]."_config.json", $basicconfig);
		}
  	}
}
function create_lw_cms_page($page,$pagefilename,$languages) {
    if (!file_exists($page)) {
        // If file has extension
        $ext = pathinfo($page, PATHINFO_EXTENSION);
        if (strlen($ext)>0) {
			// Do not create file only if exemptions 
			// TODO Check exceptions file
        } else {
        	// Check if this root directory doesn't exist (ex: images, js, etc..)
        	if(!file_exists(getcwd()."/".$pagefilename."/")){
	            touch($page.".html"); 
	            $basicconfig='{"description":"","title":"","subtitle":"","subject":"","summary":"","category":"","topic":"","keywords":"","type":"","published":"true","publish_from":"","publish_until":"","header":"header.html","footer":"footer.html","minify":"true","sitemap":"true","ogimage":"/images/og/'.$pagefilename.'.jpg"}';
	            for ($i = 0; $i < sizeof($languages); $i++) {
	                if (!file_exists($page."_".$languages[$i]."_config.json")) {
	                    touch($page."_".$languages[$i]."_config.json");
	                    file_put_contents($page."_".$languages[$i]."_config.json", $basicconfig);
	                }
	            }
        	}
        }
    }
}
function create_lw_cms_404($lw_pages,$languages){
    touch($lw_pages."404.html");
    $basicconfig='{"description":"Missing Page","title":"404","subtitle":"Page not found","subject":"URL Error","summary":"This URL does not exist any more","category":"","topic":"","keywords":"404,page not found,page missing,not found,error url,wrong url","type":"","published":"true","publish_from":"","publish_until":"","header":"header.html","footer":"footer.html","minify":"true","sitemap":"false","ogimage":"/images/og/404.jpg"}';
    for ($i = 0; $i < sizeof($languages); $i++) {
		touch("404_".$languages[$i]."_config.json");
		file_put_contents($lw_pages."404_".$languages[$i]."_config.json", $basicconfig);
	}
}
function create_lw_cms_locales($lw_locales,$language) {
    if (!file_exists($lw_locales.$language.".json")) {
	touch($lw_locales.$language.".json");
	$basiclocales='{
	"lang_curr":"'.strtoupper($language).'",
	"lang_lc":"'.strtolower($language).'",
	"lang_link":"'.strtolower($language).'/", 
	"lang_ab":"аҧсуа",
	"lang_aa":"Afaraf",
	"lang_af":"Afrikaans",
	"lang_ak":"Akan",
	"lang_sq":"Shqip",
	"lang_am":"አማርኛ",
	"lang_ar":"العربية",
	"lang_an":"Aragonés",
	"lang_hy":"Հայերեն",
	"lang_as":"অসমীয়া",
	"lang_av":"авар мацӀ, магӀарул мацӀ",
	"lang_ae":"avesta",
	"lang_ay":"aymar aru",
	"lang_az":"azərbaycan dili",
	"lang_bm":"bamanankan",
	"lang_ba":"башҡорт теле",
	"lang_eu":"euskara, euskera",
	"lang_be":"Беларуская",
	"lang_bn":"বাংলা",
	"lang_bh":"भोजपुरी",
	"lang_bi":"Bislama",
	"lang_bs":"bosanski jezik",
	"lang_br":"brezhoneg",
	"lang_bg":"български език",
	"lang_my":"ဗမာစာ",
	"lang_ca":"Català",
	"lang_ch":"Chamoru",
	"lang_ce":"нохчийн мотт",
	"lang_ny":"chiCheŵa, chinyanja",
	"lang_zh":"中文 (Zhōngwén), 汉语, 漢語",
	"lang_cv":"чӑваш чӗлхи",
	"lang_kw":"Kernewek",
	"lang_co":"corsu, lingua corsa",
	"lang_cr":"ᓀᐦᐃᔭᐍᐏᐣ",
	"lang_hr":"hrvatski",
	"lang_cs":"česky, čeština",
	"lang_da":"dansk",
	"lang_dv":"ދިވެހި",
	"lang_nl":"Nederlands, Vlaams",
	"lang_en":"English",
	"lang_eo":"Esperanto",
	"lang_et":"eesti, eesti keel",
	"lang_ee":"Eʋegbe",
	"lang_fo":"føroyskt",
	"lang_fj":"vosa Vakaviti",
	"lang_fi":"suomi, suomen kieli",
	"lang_fr":"français",
	"lang_ff":"Fulfulde, Pulaar, Pular",
	"lang_gl":"Galego",
	"lang_ka":"ქართული",
	"lang_de":"Deutsch",
	"lang_el":"Ελληνικά",
	"lang_gn":"Avañeẽ",
	"lang_gu":"ગુજરાતી",
	"lang_ht":"Kreyòl ayisyen",
	"lang_ha":"Hausa, هَوُسَ",
	"lang_he":"עברית",
	"lang_iw":"עברית",
	"lang_hz":"Otjiherero",
	"lang_hi":"हिन्दी",
	"lang_ho":"Hiri Motu",
	"lang_hu":"Magyar",
	"lang_ia":"Interlingua",
	"lang_id":"Bahasa Indonesia",
	"lang_ie":"Originally called Occidental; then Interlingue after WWII",
	"lang_ga":"Gaeilge",
	"lang_ig":"Asụsụ Igbo",
	"lang_ik":"Iñupiaq",
	"lang_io":"Ido",
	"lang_is":"Íslenska",
	"lang_it":"Italiano",
	"lang_iu":"ᐃᓄᒃᑎᑐᑦ",
	"lang_ja":"日本語",
	"lang_jv":"basa Jawa",
	"lang_kl":"kalaallisut",
	"lang_kn":"ಕನ್ನಡ",
	"lang_kr":"Kanuri",
	"lang_ks":"कश्मीरी",
	"lang_kk":"Қазақ тілі",
	"lang_km":"ភាសាខ្មែរ",
	"lang_ki":"Gĩkũyũ",
	"lang_rw":"Ikinyarwanda",
	"lang_ky":"кыргыз тили",
	"lang_kv":"коми кыв",
	"lang_kg":"KiKongo",
	"lang_ko":"한국어",
	"lang_ku":"Kurdî, كوردی‎",
	"lang_kj":"Kuanyama",
	"lang_la":"latine",
	"lang_lb":"Lëtzebuergesch",
	"lang_lg":"Luganda",
	"lang_li":"Limburgs",
	"lang_ln":"Lingála",
	"lang_lo":"ພາສາລາວ",
	"lang_lt":"lietuvių kalba",
	"lang_lv":"latviešu valoda",
	"lang_gv":"Gaelg, Gailck",
	"lang_mk":"македонски јазик",
	"lang_mg":"Malagasy fiteny",
	"lang_ms":"بهاس ملايو‎",
	"lang_ml":"മലയാളം",
	"lang_mt":"Malti",
	"lang_mi":"te reo Māori",
	"lang_mr":"मराठी",
	"lang_mh":"Kajin M̧ajeļ",
	"lang_mn":"монгол",
	"lang_na":"Ekakairũ Naoero",
	"lang_nv":"Dinékʼehǰí",
	"lang_nb":"Norsk bokmål",
	"lang_nd":"isiNdebele",
	"lang_ne":"नेपाली",
	"lang_ng":"Owambo",
	"lang_nn":"Norsk nynorsk",
	"lang_no":"Norsk",
	"lang_ii":"Nuosuhxop",
	"lang_nr":"isiNdebele",
	"lang_oc":"Occitan",
	"lang_oj":"ᐊᓂᔑᓈᐯᒧᐎᓐ",
	"lang_cu":"ѩзыкъ словѣньскъ",
	"lang_om":"Afaan Oromoo",
	"lang_or":"ଓଡ଼ିଆ",
	"lang_os":"ирон æвзаг",
	"lang_pa":"ਪੰਜਾਬੀ, پنجابی‎",
	"lang_pi":"पाऴि",
	"lang_fa":"فارسی",
	"lang_pl":"polski",
	"lang_ps":"پښتو",
	"lang_pt":"Português",
	"lang_qu":"Runa Simi, Kichwa",
	"lang_rm":"rumantsch grischun",
	"lang_rn":"kiRundi",
	"lang_ro":"română",
	"lang_ru":"русский язык",
	"lang_sa":"संस्कृतम्",
	"lang_sc":"sardu",
	"lang_sd":"सिन्धी",
	"lang_se":"Davvisámegiella",
	"lang_sm":"gagana faa Samoa",
	"lang_sg":"yângâ tî sängö",
	"lang_sr":"српски језик",
	"lang_gd":"Gàidhlig",
	"lang_sn":"chiShona",
	"lang_si":"සිංහල",
	"lang_sk":"slovenčina",
	"lang_sl":"slovenščina",
	"lang_so":"Soomaaliga, af Soomaali",
	"lang_st":"Sesotho",
	"lang_es":"Español",
	"lang_su":"Basa Sunda",
	"lang_sw":"Kiswahili",
	"lang_ss":"SiSwati",
	"lang_sv":"svenska",
	"lang_ta":"தமிழ்",
	"lang_te":"తెలుగు",
	"lang_tg":"тоҷикӣ, toğikī, تاجیکی‎",
	"lang_th":"ไทย",
	"lang_ti":"ትግርኛ",
	"lang_bo":"བོད་ཡིག",
	"lang_tk":"Türkmen",
	"lang_tl":"Wikang Tagalog",
	"lang_tn":"Setswana",
	"lang_to":"faka Tonga",
	"lang_tr":"Türkçe",
	"lang_ts":"Xitsonga",
	"lang_tt":"татарча, tatarça, تاتارچا‎",
	"lang_tw":"Twi",
	"lang_ty":"Reo Tahiti",
	"lang_ug":"Uyƣurqə, ئۇيغۇرچە‎",
	"lang_uk":"українська",
	"lang_ur":"اردو",
	"lang_uz":"zbek, Ўзбек, أۇزبېك‎",
	"lang_ve":"Tshivenḓa",
	"lang_vi":"Tiếng Việt",
	"lang_vo":"Volapük",
	"lang_wa":"Walon",
	"lang_cy":"Cymraeg",
	"lang_wo":"Wollof",
	"lang_fy":"Frysk",
	"lang_xh":"isiXhosa",
	"lang_yi":"ייִדיש",
	"lang_yo":"Yorùbá",
  "lang_za":"Saɯ cueŋƅ, Saw cuengh",
  "monday":"Monday", 
	"tuesday":"Tuesday",
	"wednesday":"Wednesday",
	"thursday":"Thursday",
	"friday":"Friday",
	"saturday":"Saturday",
	"sunday":"Sunday",
	"mon":"Mon", 
	"tue":"Tue",
	"wed":"Wed",
	"thu":"Thu",
	"fri":"Fri",
	"sat":"Sat",
	"sun":"Sun",
	"January":"January",
	"February":"February",
	"March":"March",
	"April":"April",
	"May":"May",
	"June":"June",
	"July":"July",
	"August":"August",
	"September":"September",
	"October":"October",
	"November":"November",
	"December":"December",
	"month1":"January",
	"month2":"February",
	"month3":"March",
	"month4":"April",
	"month5":"May",
	"month6":"June",
	"month7":"July",
	"month8":"August",
	"month9":"September",
	"month10":"October",
	"month11":"November",
	"month12":"December",
  "home":"Home",
	"jobs":"Jobs",
	"contact":"Contact",
  "login":"Login",
  "services":"Services",
	"products":"Products",
  "support":"Support",
  "blog":"Blog",
  "vlog":"Vlog",
	"contact_details":"Contact Details",
	"social_media":"Social Media",
	"newsletters":"Newsletters",
	"subscribe":"Subscribe",
  "partners":"Partners",
  "team":"Team"
}';
    file_put_contents($lw_locales.$language.".json", $basiclocales);
}
}

function publish($configfile){
	// Send output to nizu
	// compress data
	include $configfile;
	echo '
<!DOCTYPE html>
<html lang="eng" class="js">
<head>
<link rel="stylesheet" href="">
<title>LightWeb Publish mode</title>
<style>
@import url("https://fonts.googleapis.com/css2?family=Oxanium:wght@200;300;400;500;600;700;800&display=swap");
html, body {
	font-family: "Oxanium", cursive;
	font-size:medium;
    margin: 0;
    height: 100%;
    background-color:#01203c;
    color: #1ac9dc;
    font-weight: 200;
}
h1{
	font-weight: 200;
}
header{
	text-align: center;
    position: absolute;
    display: block;
    width: 100%;
    height: 70px;
    top: 0px;
    left: 0px;
    border-bottom-color: aliceblue;
    border-style: none;
    border-bottom-style: solid;
    border-bottom-width: 1px;
}
content {
    display: block;
    position: absolute;
    top: 50px;
    padding: 10px;
    width: 100%;
}
.card {
    display: block;
    border-radius: 20px;
    background-color: #004c90;
    max-width: 200px;
    padding: 10px;
    margin-top: 20px;
    text-align:center;
}
td{
	min-width: 200px;
}
</style>
</head>
<body>
<header>
<h1>Publishing '.$publicsite .'...</h1>
</header>
<content>
	<table>
		<tr>
		<td>
			<div class="card">
				<h2>Pages</h2>
				<p>0</p>
			</div>
		</td>
		<td>
			<div class="card">
				<h2>Approved pages</h2>
				<p>0</p>
			</div>
		</td>
	</tr>
	</table>
</content>
<footer>
</footer>
</body>
</html>';
}
function validatephone($configfile){
	if (isset($_REQUEST['phone'])) {
		$phone=trim($_REQUEST['phone']);
		$phone=preg_replace("/[^0-9]/", "",$phone);
		include $configfile;
		if (strlen($nizuapikey)>0 && $nizusenderid>0) {
			$execvalidation='curl -d "a=phone" -d "key='.$nizuapikey.'" -d "sender_id='.$nizusenderid.'" -d "phone='.$phone.'" https://api.nizu.be/validator/';
        	$e=exec($execvalidation);
        	$ans=json_decode($e,true);
        	if ($ans['data']['valid']) {
        		if ($ans['data']['number_type']=="MOBILE") {
        			die(json_encode(array("valid"=>true)));
        		} else {
        			die(json_encode(array("valid"=>"false")));
        		}
        	} else {
        		die(json_encode(array("valid"=>"false")));
        	}
    	} else {
    		die(json_encode(array("valid"=>"false")));
    	}
	}
}
function replace_between($str, $needle_start, $needle_end, $replacement) {
    $pos = strpos($str, $needle_start);
    $start = $pos === false ? 0 : $pos + strlen($needle_start);

    $pos = strpos($str, $needle_end, $start);
    $end = $start === false ? strlen($str) : $pos;
 
    return substr_replace($str,$replacement,  $start, $end - $start);
}
function localize_phrase($phrase) {
    return $translations[$phrase];
}
function left($str, $length) {
    return substr($str, 0, $length);
}
function right($str, $length) {
    return substr($str, -$length);
}
function getpageconfig($lw_pages,$browser_lang,$page){
    $configfile=$lw_pages.$page."_".$browser_lang."_config.json";
    //die($configfile);
    if (file_exists($configfile)){
        $rawfile=file_get_contents($configfile);
        return(json_decode($rawfile,true));
    } else {
        return(array());
    }
}
function generatemetas($title,$subtitle,$topic,$summary,$category,$keywords,$subject,$description,$browser_lang,$pageurl,$sqldate,$utcdate,$ogimage){
    include(getcwd()."/lightweb"."/config.php");
    if ($pageurl=="home") {
      $pageurl="";
    }
    if (strlen($title)==0) {
        $title=$maintitle;
    }
    //die($sqldate);
    $humandate=date("F j, Y, g:i a",strtotime($sqldate." 00:00:00"));
//Basic HTML Meta Tags
    if ($pwa) {
      $pwatags='<link rel="manifest" href="/manifest.json">
';
    } else {
      $pwatags='';
    }
    $basictags='<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="'.$keywords.'">
<meta name="description" content="'.$description.'">
<meta name="subject" content="'.$subject.'">
<meta name="copyright" content="'.$owner.'">
<meta name="language" content="'.strtoupper($browser_lang).'">
<meta name="robots" content="index,follow">
<meta name="revised" content="'.$humandate.'">
<meta name="abstract" content="">
<meta name="topic" content="'.$topic.'">
<meta name="summary" content="'.$summary.'">
<meta name="author" content="'.$owner.', '.$contactmail.'">
<meta name="designer" content="RGW IT SERVICES SPRL">
<meta name="reply-to" content="'.$contactmail.'">
<meta name="owner" content="'.$owner.'">
<meta name="url" content="'.$publicsite.'/'.$browser_lang.'/'.$pageurl.'">
<meta name="identifier-URL" content="'.$publicsite.'">
<meta name="directory" content="submission">
<meta name="pagename" content="'.$title.'">
<meta name="category" content="'.$category.'">
<meta name="coverage" content="Worldwide">
<meta name="distribution" content="Global">
<meta name="rating" content="General">
<meta name="revisit-after" content="7 days">
<meta name="subtitle" content="'.$subtitle.'">
<meta name="target" content="all">
<meta name="date" content="'.$humandate.'">
<meta name="search_date" content="'.$sqldate.'">
<meta name="DC.title" content="'.$title.'">
<meta name="ResourceLoaderDynamicStyles" content="">
<meta name="medium" content="blog">
<meta itemprop="name" content="'.$title.'">
<meta http-equiv="Expires" content="0">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="imagetoolbar" content="no">
<meta http-equiv="x-dns-prefetch-control" content="off">
<meta name="theme-color" content="'.$pwa_background .'">
<meta name="generator" content="LightWeb 2.0">';
// OpenGraph Meta Tags
    $opengraphtags='
<meta name="og:title" content="'.$title.'">
<meta name="og:type" content="website">
<meta name="og:url" content="'.$publicsite.$pageurl.'">
<meta name="og:image" content="'.$publicsite.$ogimage.'">
<meta name="og:site_name" content="'.$title.'">
<meta name="og:description" content="'.$description.'">
<meta name="fb:page_id" content="'.$facebook_pageid.'">
<meta name="application-name" content="'.$pwa_name.'">
<meta name="og:email" content="'.$contactmail.'">
<meta name="og:phone_number" content="'.$maintelephone.'">
<meta name="og:fax_number" content="'.$mainfax.'">
<meta name="og:latitude" content="'.$latitude.'">
<meta name="og:longitude" content="'.$longitude.'">
<meta name="og:street-address" content="'.$street.'">
<meta name="og:locality" content="'.$locality.'">
<meta name="og:region" content="'.$regionisocode.'">
<meta name="og:postal-code" content="'.$postalcode.'">
<meta name="og:country-name" content="'.$countryname.'">
<meta property="og:locale" content="'.strtolower($browser_lang).'_'.strtoupper($countrycode).'" />
<meta property="article:published_time" content="'.$utcdate.'" />
<meta property="article:modified_time" content="'.$utcdate.'" />
<meta name="dcterms.title" content="'.$title.'" />
<meta name="dcterms.creator" content="'.$owner.'" />
<meta name="dcterms.description" content="'.$description.'" />
<meta name="dcterms.date" content="'.$utcdate.'" />
<meta name="dcterms.type" content="Text" />
<meta name="dcterms.format" content="text/html" />
<meta name="dcterms.identifier" content="'.$publicsite.'/'.$browser_lang.'/'.$pageurl.'" />
<meta name="dcterms.language" content="'.strtoupper($browser_lang).'" />';
// Apple Meta Tags
    $appletags='
<meta name="apple-mobile-web-app-title" content="'.$pwa_name.'"> <!-- New in iOS6 -->
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-touch-fullscreen" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="format-detection" content="telephone=no">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta name="HandheldFriendly" content="true" />
<link href="/apple-touch-icon.png" rel="apple-touch-icon" type="image/png">
<link href="/touch-icon-ipad.png" rel="apple-touch-icon" sizes="72x72">
<link href="/touch-icon-iphone4.png" rel="apple-touch-icon" sizes="114x114">
<link href="/startup.png" rel="apple-touch-startup-image">
<link href="/images/icons/apple-touch-icon-iphone4.png" sizes="114x114" rel="apple-touch-icon-precomposed">
<link href="/images/icons/apple-touch-icon-ipad.png" sizes="72x72" rel="apple-touch-icon-precomposed">
<link href="/images/icons/apple-touch-icon-57x57.png" sizes="57x57" rel="apple-touch-icon-precomposed">
<link href="/images/icons/apple-touch-icon-152x152.png" sizes="152x152" rel="apple-touch-icon-precomposed">
<link href="/images/icons/apple-touch-icon-167x167.png" sizes="167x167" rel="apple-touch-icon-precomposed">
<link href="/images/icons/apple-touch-icon-180x180.png" sizes="180x180" rel="apple-touch-icon-precomposed">
<link href="/images/icons/apple-touch-icon-192x192.png" sizes="192x192" rel="apple-touch-icon-precomposed">';
// Microsoft Meta Tags
    $microsofttags='
<meta http-equiv="Page-Enter" content="RevealTrans(Duration=2.0,Transition=2)">
<meta http-equiv="Page-Exit" content="RevealTrans(Duration=3.0,Transition=12)">
<meta name="mssmarttagspreventparsing" content="true">
<meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible"/>
<meta name="msapplication-starturl" content="'.$pwa_start_url.'">
<meta name="msapplication-window" content="width=800;height=600">
<meta name="msapplication-navbutton-color" content="red">
<meta name="msapplication-TileColor" content="'.$pwa_background.'">
<meta name="application-name" content="'.$pwa_name.'">
<meta name="msapplication-tooltip" content="'.$description.'">
<meta name="msvalidate.01" content="'.$msvalidate.'">
<meta http-equiv="cleartype" content="on">';
// HTML Link Tags
    $htmllinktags='
<link rel="alternate" type="application/rss+xml" title="RSS" href="'.$publicsite.'/rss/">
<link rel="shortcut icon" type="image/ico" href="/favicon.ico">
<link rel="fluid-icon" type="image/png" href="/favicon.png">
<link rel="shortlink" href="'.$publicsite.'/'.$browser_lang.'/'.$pageurl.'">
<link rel="bookmark" title="'.$title.'" href="'.$publicsite.'/'.$browser_lang.'/'.$pageurl.'">
<link rel="canonical" href="'.$publicsite.'/'.$browser_lang.'/'.$pageurl.'">
<title>'.$title.'</title>';
if (sizeof($languages)) {
    for ($i=0; $i < sizeof($languages); $i++) { 
      $htmllinktags.='
<link rel="alternate" href="'.$publicsite.'/'.$languages[$i].'/'.$pageurl.'" hreflang="'.$languages[$i].'-'.$isocountries[$i].'" />';
    }
}
// Structured Data
// Captcha
$headerscripts="";
if (strlen($g_captcha)) {
	$headerscripts='
<script src="https://www.google.com/recaptcha/api.js" async defer></script>';
}
$structured_data='
<script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Organization",
        "url": "'.$publicsite.'",
        "logo": "'.$publicsite.$logo.'"
    }
</script>
';
switch ($topic) {
    case 'article':
        $structured_data.='
<script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "NewsArticle",
        "mainEntityOfPage": {
        "@type": "WebPage",
        "@id": "'.$publicsite.$pageurl.'"
        },
        "headline": "'.$title.'",
        "image": [
        "'.$publicsite.$ogimage.'"
        ],
        "datePublished": "'.$utcdate.'",
        "dateModified": "'.$utcdate.'",
        "author": {
        "@type": "Person",
        "name": "'.$owner.'"
        },
        "publisher": {
        "@type": "Organization",
        "name": "'.$owner.'",
        "logo": {
            "@type": "ImageObject",
            "url": "'.$publicsite.$logo.'"
        }
        }
    }
</script>';
        break;
        case 'course':
            $structured_data.='
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Course",
    "name": "'.$title.'",
    "description": "'.$description.'",
    "provider": {
    "@type": "Organization",
    "name": "'.$owner.'",
    "sameAs": "'.$publicsite.$pageurl.'"
    }
}
</script>';
            break;
         
    default:
        # code...
        break;
}
    return($pwatags.$basictags.$opengraphtags.$appletags.$microsofttags.$htmllinktags.$headerscripts.$structured_data);
}
function translate($rawlocales,$data){
    $locales=json_decode($rawlocales,true);
    if (sizeof($locales)){
        foreach ($locales as $key => $value) {
            $data=str_replace("{{".$key."}}", $value, $data);
        }
    }
    return($data);
}
function generatePWA(){
    include(getcwd()."/lightweb"."/config.php");
    $manifest='{
    "name": "'.$pwa_name.'",
    "short_name": "'.$pwa_short_name.'",
    "theme_color": "'.$pwa_theme_color.'",
    "background_color": "'.$pwa_background.'",
    "display": "'.$pwa_display.'",
    "orientation": "'.$pwa_orientation.'",
    "Scope": "'.$pwa_scope.'",
    "start_url": "'.$pwa_start_url.'",
    "dir": "ltr",
    "lang": "en",
    "description": "'.$owner.'",
    "related_applications": [],
    "prefer_related_applications": false,
    "icons": [
      {
        "src": "'.$pwa_icons_path.'72x72.png",
        "sizes": "72x72",
        "type": "image/png"
      },
      {
        "src": "'.$pwa_icons_path.'96x96.png",
        "sizes": "96x96",
        "type": "image/png"
      },
      {
        "src": "'.$pwa_icons_path.'128x128.png",
        "sizes": "128x128",
        "type": "image/png"
      },
      {
        "src": "'.$pwa_icons_path.'144x144.png",
        "sizes": "144x144",
        "type": "image/png"
      },
      {
        "src": "'.$pwa_icons_path.'152x152.png",
        "sizes": "152x152",
        "type": "image/png"
      },
      {
        "src": "'.$pwa_icons_path.'192x192.png",
        "sizes": "192x192",
        "type": "image/png"
      },
      {
        "src": "'.$pwa_icons_path.'384x384.png",
        "sizes": "384x384",
        "type": "image/png"
      },
      {
        "src": "'.$pwa_icons_path.'512x512.png",
        "sizes": "512x512",
        "type": "image/png"
      },
      {
        "src": "'.$pwa_icons_path.'1024x1024.png",
        "sizes": "1024x1024",
        "type": "image/png"
      }
    ],
    "splash_pages": null
}';
file_put_contents(getcwd()."/manifest.json", $manifest);
// Generate the Service Workers
$offlinesw='const CACHE = "pwabuilder-page";
const offlineFallbackPage = "";
self.addEventListener("install", function (event) {
  console.log("[PWA] Install Event processing");
  event.waitUntil(
    caches.open(CACHE).then(function (cache) {
      console.log("[PWA] Cached offline page during install");
      if (offlineFallbackPage === "ToDo-replace-this-name.html") {
        return cache.add(new Response("TODO: Update the value of the offlineFallbackPage constant in the serviceworker."));
      }
      return cache.add(offlineFallbackPage);
    })
  );
});
self.addEventListener("fetch", function (event) {
  if (event.request.method !== "GET") return;
  event.respondWith(
    fetch(event.request).catch(function (error) {
      // The following validates that the request was for a navigation to a new document
      if (
        event.request.destination !== "document" ||
        event.request.mode !== "navigate"
      ) {
        return;
      }
      console.error("[PWA] Network request Failed. Serving offline page " + error);
      return caches.open(CACHE).then(function (cache) {
        return cache.match(offlineFallbackPage);
      });
    })
  );
});
self.addEventListener("refreshOffline", function () {
  const offlinePageRequest = new Request(offlineFallbackPage);
  return fetch(offlineFallbackPage).then(function (response) {
    return caches.open(CACHE).then(function (cache) {
      console.log("[PWA] Offline page updated from refreshOffline event: " + response.url);
      return cache.put(offlinePageRequest, response);
    });
  });
});';
$registersw='
if ("serviceWorker" in navigator) {
  if (navigator.serviceWorker.controller) {
    console.log("[PWA] active service worker found, no need to register");
  } else {
    // Register the service worker
    navigator.serviceWorker
      .register("pwabuilder-sw.js", {
        scope: "./"
      })
      .then(function (reg) {
        console.log("[PWA] Service worker has been registered for scope: " + reg.scope);
      });
  }
}';
file_put_contents(getcwd()."/pwabuilder-sw.js", $offlinesw);
file_put_contents(getcwd()."/pwabuilder-sw-register.js", $registersw);
}
function footerscripts(){
	$footerscripts="";
    include(getcwd()."/lightweb"."/config.php");
    // LightWeb basic functions JS
    $footerscripts='
<script async src="https://rgwit.ams3.digitaloceanspaces.com/lightweb/nizu.min.js"></script>

';
    if (strlen($pwa_name)>0) {
        $footerscripts.='<script type="text/javascript">';
        $footerscripts.='
if ("serviceWorker" in navigator) {
if (navigator.serviceWorker.controller) {
    console.log("[PWA] active service worker found, no need to register");
} else {
    // Register the service worker
    navigator.serviceWorker
    .register("/pwabuilder-sw.js", {
        scope: "./"
    })
    .then(function (reg) {
        console.log("[PWA Builder] Service worker has been registered for scope: " + reg.scope);
    });
}
}';     
        $footerscripts.='</script>';
    }
    if (strlen($g_analitycs_id)>0) {
        $footerscripts.='
<script async src="https://www.googletagmanager.com/gtag/js?id='.$g_analitycs_id.'"></script>
<script type="text/javascript">
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
gtag("js", new Date());
gtag("config", "'.$g_analitycs_id.'");
</script>
';
    }
    if (strlen($rc_host)>0) {
        $footerscripts.='
<!-- Start of Rocket.Chat Livechat Script -->
<script type="text/javascript">
(function(w, d, s, u) {
    w.RocketChat = function(c) { w.RocketChat._.push(c) }; w.RocketChat._ = []; w.RocketChat.url = u;
    var h = d.getElementsByTagName(s)[0], j = d.createElement(s);
    j.async = true; j.src = "https://'.$rc_host.'/livechat/rocketchat-livechat.min.js?_=201903270000";
    h.parentNode.insertBefore(j, h);
    RocketChat(function() {
        this.minimizeWidget();
    });
})(window, document, "script", "https://'.$rc_host.'/livechat");
</script>
<!-- End of Rocket.Chat Livechat Script -->';
    }
    if (strlen($matomo)>0) {
    	$footerscripts.='
<!-- Matomo -->
<script type="text/javascript">
  var _paq = window._paq || [];
  _paq.push(["trackPageView"]);
  _paq.push(["enableLinkTracking"]);
  (function() {
    var u="https://matomo.nizu.io/";
    _paq.push(["setTrackerUrl", u+"matomo.php"]);
    _paq.push(["setSiteId", "1"]);
    var d=document, g=d.createElement("script"), s=d.getElementsByTagName("script")[0];
    g.type="text/javascript"; g.async=true; g.defer=true; g.src=u+"matomo.js"; s.parentNode.insertBefore(g,s);
  })();
</script>
<!-- End Matomo Code -->';
    }
    return($footerscripts);
}

function displayPage($lw_path,$lw_locales,$lw_pages,$lw_pages_headers,$lw_pages_footers,$page,$browser_lang,$header,$footer,$description,$title,$subtitle,$keywords,$summary,$category,$subject,$topic,$ogimage){
    $pagefile=$lw_pages.$page.".html";
    $nowversion=date("Ymdhsi");
    $sqldate=date("Y-m-d", filemtime($pagefile));
    //die ($sqldate);
	$utcdate=$sqldate."T".date("H:i:s", filemtime($pagefile))."+02:00";
    $rawheader=file_get_contents($lw_pages_headers.$header);
    $rawpage=file_get_contents($pagefile);
    $rawfooter=file_get_contents($lw_pages_footers.$footer);
    //Metas
    $pageurl="/".$browser_lang."/".$page."/";
    $metas = generatemetas($title,$subtitle,$topic,$summary,$category,$keywords,$subject,$description,$browser_lang,$page,$sqldate,$utcdate,$ogimage);
    $header=str_replace("{{metas}}",$metas,$rawheader);
    $header=str_replace("{{page_language}}",$browser_lang,$header);
    $header=str_replace("{{title}}",$title,$header);
    $header=str_replace("{{subtitle}}",$subtitle,$header);
    // Adding current Version
    $header=str_replace('.css"','.css?v='.$nowversion.'"',$header);
    $header=str_replace('.js"','.js?v='.$nowversion.'"',$header);
    $header=str_replace(".css'",".css?v=".$nowversion."'",$header);
    $header=str_replace(".js'",".js?v=".$nowversion."'",$header);
    $footer=str_replace('.css"','.css?v='.$nowversion.'"',$rawfooter);
    $footer=str_replace('.js"','.js?v='.$nowversion.'"',$footer);
    $footer=str_replace(".css'",".css?v=".$nowversion."'",$footer);
    $footer=str_replace(".js'",".js?v=".$nowversion."'",$footer);
    $footer=str_replace("{{footer_scripts}}",footerscripts(),$footer);
    // Translations via LOCALES
    $rawlocales=file_get_contents($lw_locales.$browser_lang.".json");
    $newheader=translate($rawlocales,$header);
    $newpage=translate($rawlocales,$rawpage);
    $newfooter=translate($rawlocales,$footer);
    //die($newheader);
    echo $newheader;
    echo $newpage;
    echo $newfooter;
}