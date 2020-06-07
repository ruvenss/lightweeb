<?php
// RGW IT Open Source Hardcode Framework for web developers
// Data Base is SQLite
// Use the Config file to setup the website
ini_set("error_reporting", E_ALL);
include_once("cmsfunctions.php");
$lw_path=getcwd()."/lightweb"."/";
$lw_locales=$lw_path."locales/";
$lw_pages=$lw_path."webpages/";
$lw_pages_headers=$lw_path."headers/";
$lw_pages_footers=$lw_path	."footers/";
$phpexecutor_path=getcwd()."/cgi"."/";
$githubrepo="https://raw.githubusercontent.com/ruvenss/lightweb/master/";
if (!file_exists($lw_path)) {
	mkdir($lw_path);
}
if (!file_exists($phpexecutor_path)) {
	mkdir($phpexecutor_path);
	touch($phpexecutor_path."index.php");
	touch($phpexecutor_path."nizu.php");
	touch($phpexecutor_path."actions_app.php");
	touch($phpexecutor_path."db.php");
	copy($lw_path."config.php",$phpexecutor_path."config.php");
	mkdir($phpexecutor_path."jsondata");
	touch($phpexecutor_path."jsondata/index.html");
}
// Check File Size of executors
clearstatcache();
if (!filesize($phpexecutor_path."index.php")) {
	// Download and write from GitHub
	$cexec="wget -O $phpexecutor_path"."index.php ".$githubrepo."/cgi/index.php";
	exec($cexec);
}
if (!file_exists($lw_locales)) {
	mkdir($lw_locales);
}
if (!file_exists($lw_pages)) {
	mkdir($lw_pages);
}
if (!file_exists($lw_pages_headers)) {
	mkdir($lw_pages_headers);
	touch($lw_pages_headers."header.html");
}
if (!file_exists($lw_pages_footers)) {
	mkdir($lw_pages_footers);
	touch($lw_pages_footers."footer.html");
}
// check if config.php else go to setup
if (file_exists($lw_path.'config.php')) {
	include_once($lw_path.'config.php');
} else {
	header("Location: /lightweb/install/"); /* Redirect browser */
	exit();
}
if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])){
	$browser_lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
} else {
	$browser_lang = $languages[0];
}
if (isset($_REQUEST['lang'])) {
	if (strlen($_REQUEST['lang'])==2) {
		$browser_lang=$_REQUEST['lang'];
	} else {
		header("Location: /".$browser_lang.$_REQUEST['page']);
	}
} else {
	header("Location: /".$browser_lang."/");
}
// Check for webpages
if (isset($_REQUEST['p'])) {
	$page=$_REQUEST['p'];
} else {
	$page="home";
	$_REQUEST['p']="home";
}
if ($page=="") {
	$page="home";
	$_REQUEST['p']="home";
}
// building up root pages
if (!file_exists($lw_pages.$page)) {
	switch ($page) {
		case 'favicon.ico':
		case '505':
			break;
		case 'publish':
			publish($lw_path."config.php");
			die();
			break;
		default:
			create_lw_cms_page($lw_pages.$page,$page,$languages);
			break;
	}
}
// Building up sub pages
if (strlen($_REQUEST['key3'])){$page.="/".$_REQUEST['key3']; if (!file_exists($lw_pages.$_REQUEST['key3']."/")){create_lw_cms_subpage($lw_pages.$_REQUEST['key3'],$_REQUEST['key3'],$languages);}}
if (strlen($_REQUEST['key4'])){$page.="/".$_REQUEST['key4']; if (!file_exists($lw_pages.$_REQUEST['key3']."/".$_REQUEST['key4']."/")){create_lw_cms_subpage($lw_pages.$_REQUEST['key3']."/".$_REQUEST['key4'],$_REQUEST['key4'],$languages);}}
if (strlen($_REQUEST['key5'])){$page.="/".$_REQUEST['key5']; if (!file_exists($lw_pages.$_REQUEST['key3']."/".$_REQUEST['key4']."/".$_REQUEST['key5']."/")){create_lw_cms_subpage($lw_pages.$_REQUEST['key3']."/".$_REQUEST['key4']."/".$_REQUEST['key5'],$_REQUEST['key5'],$languages);}}
if (strlen($_REQUEST['key6'])){$page.="/".$_REQUEST['key6']; if (!file_exists($lw_pages.$_REQUEST['key3']."/".$_REQUEST['key4']."/".$_REQUEST['key5']."/".$_REQUEST['key6']."/")){create_lw_cms_subpage($lw_pages.$_REQUEST['key3']."/".$_REQUEST['key4']."/".$_REQUEST['key5']."/".$_REQUEST['key6'],$_REQUEST['key6'],$languages);}}
// Building up translations
for ($i=0; $i < sizeof($languages) ; $i++) {
	if (!file_exists($lw_locales.$languages[$i].".json")) {
		create_lw_cms_locales($lw_locales,$languages[$i]);
	}
}
// Display Page
$pageconfig=getpageconfig($lw_pages,$browser_lang,$page);
// Check if this is a Progressive Web App
if ($pwa) {
	if (!file_exists($lw_path."manifest.json") || !file_exists($lw_path."pwabuilder-sw.js")) {
		generatePWA();	
	}
}
if ($pageconfig['published']=="true") {
	// Display Page
	displayPage($lw_path,$lw_locales,$lw_pages,$lw_pages_headers,$lw_pages_footers,$page,$browser_lang,$pageconfig['header'],$pageconfig['footer'],$pageconfig['description'],$pageconfig['title'],$pageconfig['subtitle'],$pageconfig['keywords'],$pageconfig['summary'],$pageconfig['category'],$pageconfig['subject'],$pageconfig['topic'],$pageconfig['ogimage']);
} else {
	// Do 404
	$page="404";
	if (!file_exists($lw_pages."404.html")) {
		create_lw_cms_404($lw_pages,$languages);
	}
	// Display 404
	$pageconfig=getpageconfig($lw_pages,$browser_lang,$page);
	displayPage($lw_path,$lw_locales,$lw_pages,$lw_pages_headers,$lw_pages_footers,"404",$browser_lang,$pageconfig['header'],$pageconfig['footer'],$pageconfig['description'],$pageconfig['title'],$pageconfig['subtitle'],$pageconfig['keywords'],$pageconfig['summary'],$pageconfig['category'],$pageconfig['subject'],$pageconfig['topic'],$pageconfig['ogimage']);
}