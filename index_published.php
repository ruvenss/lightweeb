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
function slack($slacktext,$slack_channel,$slack_webhook){
    if(isset($slacktext) && isset($slack_channel)) {
        if (strlen($slack_webhook)>0) {
            include("config.php");
            $slacktext="$slacktext";
            if (strlen($slack_webhook)>0){
                $ch = curl_init( $slack_webhook );
                $payload = json_encode( array( "channel"=> "#".$slack_channel,"username"=>"Marvin","text"=>$slacktext,"mrkdwn"=>true) );
                curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
                curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
                # Return response instead of printing.
                curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
                # Send request.
                $result = curl_exec($ch);
                curl_close($ch);
                # Print response.
                //echo $result;
            } else {
                die(__LINE__.__FUNCTION__."($slacktext,$slack_channel)");
            }
        } else {
            die(__LINE__.__FUNCTION__."($slacktext,$slack_channel)");
        }
    } else {
        die(__LINE__.__FUNCTION__."(missing slacktext,missing slack_channel)");
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