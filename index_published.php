<?php
ini_set("error_reporting", E_ALL);
include_once('config.php');
class MyDB extends SQLite3 {
	function __construct() {
		include("config.php");
		$this->open($dbkey);
	}
}
function replace_between($str, $needle_start, $needle_end, $replacement) {
    $pos = strpos($str, $needle_start);
    $start = $pos === false ? 0 : $pos + strlen($needle_start);
    $pos = strpos($str, $needle_end, $start);
    $end = $start === false ? strlen($str) : $pos;
    return substr_replace($str,$replacement,  $start, $end - $start);
}
function contactForm(){
	if (isset($_REQUEST['name']) && isset($_REQUEST['email']) && isset($_REQUEST['countryCode']) && isset($_REQUEST['mobile']) && isset($_REQUEST['subject']) && isset($_REQUEST['message'])) {
		$contact_name=trim($_REQUEST['name']);
		$contact_email=trim($_REQUEST['email']);
		$contact_countryCode=trim($_REQUEST['countryCode']);
		$contact_mobile=trim($_REQUEST['mobile']);
		$contact_subject=trim($_REQUEST['subject']);
		$contact_message=trim($_REQUEST['message']);
		$contact_mobile=str_replace(" ", "", $contact_mobile);
		$contact_mobile=str_replace("/", "", $contact_mobile);
		$contact_mobile=str_replace("-", "", $contact_mobile);
		$contact_mobile=str_replace(".", "", $contact_mobile);
		$contact_mobile=str_replace(",", "", $contact_mobile);
		$contact_mobile=str_replace("=", "", $contact_mobile);
		$contact_mobile=str_replace("(", "", $contact_mobile);
		$contact_mobile=str_replace(")", "", $contact_mobile);
		$contact_countryCode=str_replace("+", "", $contact_countryCode);
		$contact_names=explode(" ", $contact_name);
		$contact_firstname=$contact_names[0];
		$contact_language=substr(trim($_REQUEST['Language']), 0,2);
		include 'config.php';
		if (filter_var($contact_email, FILTER_VALIDATE_EMAIL) && strlen($nizuapikey)>0 && $nizusenderid>0) {
			$content="New message from $contact_name,<br><br><h4>Form:</h4><br><p><table><tr><td>Name</td><td>$contact_name</td></tr><tr><td>e-mail</td><td>$contact_email</td></tr><tr><td>Mobile</td><td>+$contact_countryCode$contact_mobile</td></tr><tr><td>Language</td><td>$contact_language</td></tr><tr><td>Subject</td><td>$contact_subject</td></tr><tr><td>Message</td><td>$contact_message</td></tr></table></p>";
			$contentto="This is a copy of your data sent to $publicsite staff,<br><br><h4>Form:</h4><br><p><table><tr><td>Name</td><td>$contact_name</td></tr><tr><td>e-mail</td><td>$contact_email</td></tr><tr><td>Mobile</td><td>$contact_mobile</td></tr><tr><td>Language</td><td>$contact_language</td></tr><tr><td>Subject</td><td>$contact_subject</td></tr><tr><td>Message</td><td>$contact_message</td></tr></table></p>";
			$to=$nizusendermail;
			$subject='Message from '.$contact_name.' via your website';
			exec('curl -d "a=sendMail" -d "key=zFaZdHVYiseUsRgmqnm9MerIPBC4T1Re" -d "sender_id=1" -d "html='.$content.'" -d "toReceivers[]='.$to.'" -d "subject='.$subject.'"  https://api.nizu.be/mail/');
			$execemail='curl -d "a=sendMail" -d "key='.$nizuapikey.'" -d "sender_id='.$nizusenderid.'" -d "html='.$contentto.'" -d "toReceivers[]='.$contact_email.'" -d "subject='.$publicsite .' Message"  https://api.nizu.be/mail/';
			exec($execemail);
			// WhatsApp Notification
			switch ($contact_language) {
				case 'en':
					$messagewhatsapp="Dear $contact_firstname, thank you for contacting $publicsite . We have received your message and our staff will get back to you ASAP.";
					break;
				case 'fr':
					$messagewhatsapp="Cher $contact_firstname, merci d'avoir contacté $publicsite. Nous avons reçu votre message et notre personnel vous répondra dans les meilleurs délais.";
					break;
				case 'es':
					$messagewhatsapp="Estimado(a) $contact_firstname, gracias por contactar $publicsite. Hemos recibido su mensaje y nuestro personal se pondrá en contacto con usted lo antes posible.";
					break;
				case 'nl':
					$messagewhatsapp="Beste $contact_firstname, bedankt voor het contacteren van $publicsite. We hebben uw bericht ontvangen en onze medewerkers zullen zo snel mogelijk contact met u opnemen";
					break;
				default:
					$messagewhatsapp="Dear $contact_firstname, thank you for contacting $publicsite . We have received your message and our staff will get back to you ASAP.";
					break;
			}
			$execwhatsapp='curl -d "a=sendWhatsapp" -d "key='.$nizuapikey.'" -d "sender_id='.$nizusenderid.'" -d "message='.$messagewhatsapp.'" -d "receiver='.$contact_countryCode.$contact_mobile.'" https://api.nizu.be/whatsapp/';
        	$e=exec($execwhatsapp);
			header("Location: ".$contact_language."/message_sent/");
		} else {
			header("Location: ".$contact_language."/message_error/");
		}
	} else {
		header("Location: ".$contact_language."/message_error/");
	}
}
function validatephone(){
	if (isset($_REQUEST['phone'])) {
		$phone=trim($_REQUEST['phone']);
		$phone=preg_replace("/[^0-9]/", "",$phone);
		include 'config.php';
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
function cleanget($page) {
	$page=str_replace(".", "", $page);
	$page=str_replace("html", "", $page);
	$page=str_replace("&", "", $page);
	$page=str_replace("'", "", $page);
	$page=str_replace("=", "", $page);
	$page=str_replace('"', "", $page);
	return($page);
}
function search($query,$dbkey) {
	$query=cleanget($query);
	$searchrawdata="";
	if (strlen($query)>0) {
		$db = new MyDB();
		if(!$db) {
		  echo $db->lastErrorMsg();
		} else {
			$configfile = fopen("search.html", "r") or die("Unable to open file!");
			while(!feof($configfile)) {
				$searchrawdata .= fgets($configfile) . "";
			}

			$searchrawdata = str_replace("{{search_query}}",$query,$searchrawdata);
			if (isset($_GET['lang'])) {
				$lang=$_GET['lang'];
				$results = $db->query('SELECT htmlfile,title,descfile,lang FROM pages WHERE keywords LIKE "%'.$query.'%" AND lang="'.$lang.'"');
			} else {
				$results = $db->query('SELECT htmlfile,title,descfile,lang FROM pages WHERE keywords LIKE "%'.$query.'%"');
			}
			$fileid=0;
			$i=0;
			$listresults="<ol>";
			while ($row = $results->fetchArray()) {
			    $filehtml=str_replace(".html", "",$row['htmlfile']);
			    $filehtml=str_replace("webpages", "",$filehtml);
			    $title=$row['title'];
			    $title=str_replace("_", " ",$title);
			    $title=str_replace("/", " ",$title);
			    $lang=$row['lang'];
			    $descfile=$row['descfile'];
			    $listresults .= '<li><h2><strong><a href="/'.$lang.$filehtml.'/">'.ucwords($title).' [ '.$lang.' ]</a></strong></h2>
							<div class="post-meta"><h4>'.$descfile.'</h4></div>
					</li>';
			    $i=$i+1;
			}
			$searchrawdata = str_replace("{{results_qty}}",$i,$searchrawdata);
			$search_page=file_get_contents($lang."/search/index.html");
			$search_page_arr=explode("\n", $search_page);
			echo $search_page_arr[0]; //header
			echo $searchrawdata;
			echo $listresults;
			echo $searchfooter;
        	echo $search_page_arr[2]; //footer
		}
	}
}
if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])){
	$browser_lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
} else {
	$browser_lang = "en";
}
if (isset($_REQUEST['a'])) {
	$action=trim($_REQUEST['a']);
	if (strlen($action)>0) {
		switch($action) {
			case "validatephone":
				validatephone();
			case "contactform":
				contactForm();
				break;
			case "whoiam":
				break;
			default:
				include("actions_app.php");
		}
	}
} else {
	if (isset($_GET['s'])) {
		search($_GET['s'],$dbkey);
	} else {
		echo file_get_contents("home/index.html");
	}
}