<?php
/* LightWeb init root file */
include ('cgi/config.php');
if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])){
	$browser_lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
} else {
	$browser_lang = $languages[0];

}
if (isset($_REQUEST['lang'])) {
	if (strlen($_REQUEST['lang'])==2) {
		$browser_lang=$_REQUEST['lang'];
		// check if the language exist in the translation, if not take language[0]
		if (!in_array($browser_lang, $languages)) {
			$browser_lang=$languages[0];
		} 
	} else {
		header("Location: /".$browser_lang.$_REQUEST['page']);
	}
} else {
	header("Location: /".$browser_lang."/");
}
