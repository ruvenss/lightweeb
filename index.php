<?php
// RGW IT Open Source Hardcode Framework for web developers
// Data Base is SQLite
// Use the Config file to setup the website
ini_set("error_reporting", E_ALL);
include_once('config.php');
$qtyslash   = substr_count($_SERVER['REQUEST_URI'], '/');
/* if ($_SERVER["SERVER_PORT"] != 443) {
	if ($_SERVER['HTTP_HOST']!=$draftsite){
		$redir = "Location: http://" . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
		header($redir);
		exit();
	}
}*/
class MyDB extends SQLite3 {
	function __construct() {
		include("config.php");
		$this->open($dbkey);
	}
}
class Template
{
    protected $_file;
    protected $_data = array();

    public function __construct($file = null)
    {
        $this->_file = $file;
    }

    public function set($key, $value)
    {
        $this->_data[$key] = $value;
        return $this;
    }

    public function render()
    {
        extract($this->_data);
        ob_start();
        include($this->_file);
        return ob_get_clean();
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
function slackhook($slacktext,$slack_webhook){
    if(isset($slacktext)) {
        
        if (strlen($slack_webhook)>0) {
            include("config.php");
            $slacktext="$slacktext";
            if (strlen($slack_webhook)>0){
                $ch = curl_init( $slack_webhook );
                $payload = json_encode( array("username"=>"Marvin","text"=>$slacktext,"mrkdwn"=>true) );
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
function slackMessage($slacktext,$channel){
	//curl -X POST -H 'Authorization: Bearer xoxp-71322161601-71322161633-223579264343-2d36d2f920d772fad42bb2dd8993dbea' -H 'Content-type: application/json' --data '{"channel":"C2394TTPS","text":"Test"}' https://slack.com/api/chat.postMessage
	//curl -X POST -H 'Authorization: Bearer xoxp-71322161601-71322161633-223579264343-2d36d2f920d772fad42bb2dd8993dbea' -H 'Content-type: application/json' --data '{"channel":"D239E5RAT","text": "Would you like to play a game?"}' https://slack.com/api/chat.postMessage
    if(isset($slacktext)) {
        $slack_webhook="https://slack.com/api/chat.postMessage";
        if (strlen($slack_webhook)>0) {
            include("config.php");
            $slacktext="$slacktext";
            if (strlen($slack_webhook)>0){
                $ch = curl_init( $slack_webhook );
                $payload = json_encode( array("channel"=>$channel,"text"=>$slacktext,"mrkdwn"=>true) );
                curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
                curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer xoxp-71322161601-71322161633-223579264343-2d36d2f920d772fad42bb2dd8993dbea','Content-Type:application/json'));
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
function search($query,$dbkey) {
	$query=cleanget($query);
	$searchrawdata="";
	if (strlen($query)>0) {
		$db = new MyDB();
		if(!$db) {
		  echo $db->lastErrorMsg();
		} else {
			$configfile = fopen("webpages/search.html", "r") or die("Unable to open file!");
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
			echo $searchrawdata;
			echo $listresults;
			echo '</ol>
			</div>
		    </div>
		</section>
        <section class="call_to_action dark_section">
               <div class="container triangles-of-section">
                    <div class="triangle-up-left"></div>
                    <div class="square-left"></div>
                    <div class="triangle-up-right"></div>
                    <div class="square-right"></div>
               </div>
               <div class="container">
                    <a class="btn btn-primary" href="https://www.rgwit.be/erp2/clients/open_ticket">e-tickets  &nbsp;<i class="fa fa-rocket"></i></a> </div>
        </section>';
		}
	}
}
function newsletterdb($dbkey,$clientemail,$firstname,$lastname) {
	$clientemail = filter_var(trim($clientemail), FILTER_SANITIZE_EMAIL);
	$newsletterlist= 'newslettersdb_'.$dbkey.'.txt';
	if (filter_var($clientemail, FILTER_VALIDATE_EMAIL)) {
		if (file_exists($newsletterlist)) {
			if( strpos(file_get_contents($newsletterlist),$clientemail) === false) {
				$newrecord=$clientemail."\t".$firstname."\t".$lastname;
				$myfile = file_put_contents($newsletterlist, $newrecord.PHP_EOL , FILE_APPEND | LOCK_EX);
				die(json_encode(array("success"=>"true")));
			} else { 
				die(json_encode(array("success"=>"false","issue"=>"email already subscribed")));
			}
		} else {
			$newrecord=$clientemail."\t".$firstname."\t".$lastname;
				$myfile = file_put_contents($newsletterlist, $newrecord.PHP_EOL , FILE_APPEND | LOCK_EX);
				die(json_encode(array("success"=>"true")));
		}
	} else {
		die(json_encode(array("success"=>"false","issue"=>"Invalid email format")));
	}
}
function rebuildsearch($myDBKey,$dbkey,$publicsite,$maintitle){
	include("config.php");
	if ($myDBKey==$dbkey) {
		//echo '<p style="font-family:mono,courier;">';
		//echo "\r\n<br>Rebuilding search keys...<br>";
		$db = new MyDB();
		if(!$db) {
		  echo $db->lastErrorMsg();
		} else {
			$sd="sitedump.txt";
			//echo "Opened database successfully\n<br>";
			//echo "Cleaning $sd file\n<br>";
			if (file_exists($sd)) {
				unlink($sd);
			}
			$siterorheader='<?xml version="1.0" encoding="UTF-8"?><rss version="2.0" xmlns:ror="http://rorweb.com/0.1/" ><channel>';
			$siterorfooter='</channel></rss>';
			$sitemapheader='<?xml version="1.0" encoding="UTF-8"?><urlset xmlns:xhtml="http://www.w3.org/1999/xhtml" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
			$sitemapfooter='</urlset>';
			$sitemapbody="";
			$i=0;
			$siterorbody="<title>ROR Sitemap for $publicsite</title>\n<link>$publicsite</link>";
			foreach (glob("webpages/*.html") as $filename) {
				switch ($filename) {
					case stristr($filename,'google'):
					case stristr($filename,'mywot'):
					case stristr($filename,'bing'):
					case stristr($filename,'yandex'):
					case stristr($filename,'profile_'):
					case 'webpages/404.html':
					case 'webpages/header.html':
					case 'webpages/footer.html':
					case 'webpages/search.html':
						break;
					default:
						if (filesize($filename)>0) {
							$myDate=date("Y-m-d H:i:s", filemtime($filename));
							$myDate.="+00:00";
							$myDate=str_replace(" ","T",$myDate);
							//echo "<b>$filename</b> size " . filesize($filename) . ", Date=$myDate<br>\n";
							$x = 'lynx --dump ./'.$filename.' > '.$sd;
							exec($x);
							$pagefile=str_replace(".html", "", $filename);
							$configdata = getFileConfig(str_replace("webpages/", "", $pagefile),$languages[0]);
							//echo "Config data file <b>$pagefile .config</b> title: ".$configdata[0]." <br>\n";
							$pagecontent = getSiteDump($sd,"en");
							$db->query('CREATE TABLE IF NOT EXISTS "pages" ( `id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE, `htmlfile` TEXT NOT NULL, `descfile` TEXT NOT NULL, `lang` TEXT NOT NULL DEFAULT "en", `keywords` TEXT, `title` TEXT )');
							$results = $db->query('SELECT id FROM pages WHERE htmlfile="'.$filename.'"');
							$fileid=0;
							while ($row = $results->fetchArray()) {
							    $fileid=$row['id'];
							}
							if ($fileid>0) {
							    $query='UPDATE pages SET title="'.str_replace("webpages/", "", $pagefile).'",descfile="'.$configdata[0].'",keywords="'.$pagecontent.'" WHERE id='.$fileid;
									
							} else {
								$query='INSERT INTO pages(htmlfile,title,descfile,keywords) VALUES("'.$filename.'","'.str_replace("webpages/", "", $pagefile).'","'.$configdata[0].'","'.$pagecontent.'")';
							}
							$sitemapbody.='<url>';
							$siterorbody.='<item>';
								$siterorbody.="\n".'<link>'."\n\t".'https://'.$publicsite.'/'.str_replace("webpages/", "", $pagefile)."</link>\n";
								$siterorbody.='<title>'.$configdata[0]."</title>\n";
								$siterorbody.='<description>'.$configdata[0]."</description>\n";
								$siterorbody.='<ror:updatePeriod>monthly</ror:updatePeriod>';
								$siterorbody.='<ror:sortOrder>'.$i.'</ror:sortOrder>';
								$siterorbody.='<ror:resourceOf>sitemap</ror:resourceOf>';
								$sitemapbody.="\n".'<loc>'."\n\t".'https://'.$publicsite.'/'.str_replace("webpages/", "", $pagefile);
								$sitemapbody.="\n".'</loc>'."\n".'<changefreq>'."\n\t".'monthly'."\n".'</changefreq>'."\n".'<priority>'."\n\t".'1.0'."\n".'</priority>'."\n".'<lastmod>'.$myDate.'</lastmod>'."\n";
							$sitemapbody.="\n".'</url>'."\n";
							$siterorbody.="\n".'</item>'."\n";

							$db->query($query);
							//echo "<b>$query</b><hr>";
							$i=$i+1;
						}
						// Check for other languages
						if (sizeof($languages)>0) {
							for ($i=1; $i < sizeof($languages); $i++) {
								//echo "\n<br> Translation SEO for <strong>".$languages[$i]."</strong><br><hr>";
								$configdata = getFileConfig(str_replace("webpages/", "", $pagefile),$languages[$i]);
								$pagecontent = "";
								$pagecontent = getSiteDump($sd,$languages[$i]);
								//echo "<br>$sd ".$languages[$i]." = <br>[ $pagecontent ]<br><br>";
								$results = $db->query('SELECT id FROM pages WHERE htmlfile="'.$filename.'" AND lang="'.$languages[$i].'"');
								$fileid=0;
								while ($row = $results->fetchArray()) {
								    $fileid=$row['id'];
								}
								//echo "<br>\n Translation sqlite fileid=<strong>$fileid</strong><br>\n";
								if ($fileid>0) {
								    $query='UPDATE pages SET title="'.str_replace("webpages/", "", $pagefile).'",descfile="'.$configdata[0].'",keywords="'.$pagecontent.'" WHERE id='.$fileid;
										
								} else {
									$query='INSERT INTO pages(htmlfile,title,descfile,keywords,lang) VALUES("'.$filename.'","'.str_replace("webpages/", "", $pagefile).'","'.$configdata[0].'","'.$pagecontent.'","'.$languages[$i].'")';
								}
								//echo "// SQLite3 Translation:\n<br>$query<br>//<br>";
								$db->query($query);
								$sitemapbody.='<url>';
								$siterorbody.='<item>';
									$siterorbody.="\n".'<link>'."\n\t".'https://'.$publicsite.'/'.$languages[$i].'/'.str_replace("webpages/", "", $pagefile)."</link>\n";
									$siterorbody.='<title>'.$configdata[0]."</title>\n";
									$siterorbody.='<description>'.$configdata[0]."</description>\n";
									$siterorbody.='<ror:updatePeriod>monthly</ror:updatePeriod>';
									$siterorbody.='<ror:sortOrder>'.$i.'</ror:sortOrder>';
									$siterorbody.='<ror:resourceOf>sitemap</ror:resourceOf>';
									$sitemapbody.="\n".'<loc>'."\n\t".'https://'.$publicsite.'/'.$languages[$i].'/'.str_replace("webpages/", "", $pagefile);
									$sitemapbody.="\n".'</loc>'."\n".'<changefreq>'."\n\t".'monthly'."\n".'</changefreq>'."\n".'<priority>'."\n\t".'1.0'."\n".'</priority>'."\n".'<lastmod>'.$myDate.'</lastmod>'."\n";
								$sitemapbody.="\n".'</url>'."\n";
								$siterorbody.="\n".'</item>'."\n";
							}
						}
						break;
				}
			}
			$sitemap=$sitemapheader."\n".$sitemapbody."\n".$sitemapfooter;
			$sitemapror=$siterorheader."\n".$siterorbody."\n".$siterorfooter;
			$manifest='{
				"short_name": "'.$maintitle.'",
				"name": "'.$maintitle.'",
				"icons": [{
					"src": "/img/icon-96.png",
					"sizes": "96x96",
					"type": "image/png"
				},{
					"src": "/img/icon-144.png",
					"sizes": "144x144",
					"type": "image/png"
				},{
					"src": "/img/icon-192.png",
					"sizes": "192x192",
					"type": "image/png"
				}],
				"start_url": "/",
				"display": "fullscreen",
				"orientation": "portrait"
			}';
			//echo "\r\n<br>Creating Sitemaps...<br>";
			//echo "<hr>".$sitemap;
			file_put_contents("sitemap.xml", $sitemap);
			file_put_contents("sitemap.ror", $sitemapror);
			//echo "\r\n<br>Creating Manifest for modern sites...<br></p>";
			file_put_contents("manifest.json", $manifest);
		}
	} else {
		die("err 049");
	}
}
function getSiteDump($siteDumpFile,$page_language){
	$configrawdata="";
	
        $lang_file =  'locales/' . $page_language . '.json';
        if (!file_exists($lang_file)) {
            $lang_file = 'locales/' . 'en.json';
        }
        $lang_file_content = file_get_contents($lang_file);
        $translations = json_decode($lang_file_content, true);
    
	$configfile = fopen($siteDumpFile, "r") or die("Unable to open file!");
	while(!feof($configfile)) {
		foreach ($translations as $key => $value) {
			$configrawdata=str_replace("{{".$key."}}", $value, $configrawdata);
		}
		$configrawdata .= trim(fgets($configfile)) . " ";
		$configrawdata = str_replace('"', " ", $configrawdata);
		$configrawdata = str_replace("//", " ", $configrawdata);
		$configrawdata = str_replace(".", " ", $configrawdata);
		$configrawdata = str_replace(",", " ", $configrawdata);
		$configrawdata = str_replace("'", " ", $configrawdata);
		$configrawdata = str_replace("-", " ", $configrawdata);
		$configrawdata = str_replace("\\", "", $configrawdata);
		$configrawdata = str_replace("?", " ", $configrawdata);
		$configrawdata = str_replace("*", " ", $configrawdata);
		$configrawdata = str_replace("+", " ", $configrawdata);
		$configrawdata = str_replace("|", " ", $configrawdata);
		$configrawdata = str_replace("&", " ", $configrawdata);
		$configrawdata = str_replace("$", " ", $configrawdata);
		$configrawdata = str_replace("__", " ", $configrawdata);
		$configrawdata = str_replace("[", " ", $configrawdata);
		$configrawdata = str_replace("]", " ", $configrawdata);
		$configrawdata = str_replace("(", " ", $configrawdata);
		$configrawdata = str_replace(")", " ", $configrawdata);
		$configrawdata = str_replace(" 0 ", " ", $configrawdata);
		$configrawdata = str_replace(" 1 ", " ", $configrawdata);
		$configrawdata = str_replace(" 2 ", " ", $configrawdata);
		$configrawdata = str_replace(" 3 ", " ", $configrawdata);
		$configrawdata = str_replace(" 4 ", " ", $configrawdata);
		$configrawdata = str_replace(" 5 ", " ", $configrawdata);
		$configrawdata = str_replace(" 6 ", " ", $configrawdata);
		$configrawdata = str_replace(" 7 ", " ", $configrawdata);
		$configrawdata = str_replace(" 8 ", " ", $configrawdata);
		$configrawdata = str_replace(" 9 ", " ", $configrawdata);
		$configrawdata = str_replace("//", " ", $configrawdata);
		$configrawdata = str_replace("/", " ", $configrawdata);
		$configrawdata = str_replace("png", " ", $configrawdata);
		$configrawdata = str_replace("jpg", " ", $configrawdata);
		$configrawdata = str_replace("html", " ", $configrawdata);
		$configrawdata = str_replace("xml", " ", $configrawdata);
		$configrawdata = str_replace("json", " ", $configrawdata);
		$configrawdata = str_replace("xhtml", " ", $configrawdata);
		$configrawdata = str_replace("#", " ", $configrawdata);
		$configrawdata = str_replace(":", " ", $configrawdata);
		$configrawdata = str_replace("!", " ", $configrawdata);
		$configrawdata = str_replace("    ", " ", $configrawdata);
		$configrawdata = str_replace("   ", " ", $configrawdata);
		$configrawdata = str_replace("  ", " ", $configrawdata);
		$configrawdata = str_replace("\t", " ", $configrawdata);
	}
	fclose($configfile);
	return(strtolower($configrawdata));
}
function localize_phrase($phrase) {
    
    return $translations[$phrase];
}
function getFileConfig($pagefile,$page_language) {
	if ($page_language=="en"){
		$page_language="";
	} else {
		$page_language=$page_language."_";
	}
	switch ($pagefile) {
		case 'header':
		case 'footer':
		case 'index':
		case 'search':
			$configdata=array("","","");
			break;
		
		default:
			$configfile="webpages/".$page_language.$pagefile.".config";
			//echo "<br>\n getFileConfig[$configfile] Page Language: <strong>".$page_language."</strong><br>";
			//$configrawdata="";
			if (!$configrawdata=file_get_contents($configfile)){
				file_put_contents($configfile, "||");
				$configdata=array("","","");
			} else{
				$configdata=explode("|", $configrawdata);
			}
			break;
	}
	
	return($configdata);
}
function setheader($docheader,$pagefile,$page_language,$translations){
	$myDate=date("Y-m-d", filemtime("webpages/".$pagefile.".html"));
	$myDate.="T".date("H:i:s", filemtime("webpages/".$pagefile.".html"))."+02:00";
	$configdata = getFileConfig($pagefile,$page_language);
	$description=ucfirst($configdata[0]);
	$keywords=$configdata[1];
	$shortlink=$configdata[2];
	$title=ucfirst(str_replace("_", " ", $pagefile));
	$description=str_replace('"', "", $description);
	if (strlen($page_language) && $page_language!="en") {
		$pagefile=$page_language."/".$pagefile;
	}
	$docheader = str_replace("{{page_language}}",$page_language,$docheader);
	$docheader = str_replace("{{title}}",$title." | ".$description,$docheader);
	$docheader = str_replace("{{description}}",$description,$docheader);
	$docheader = str_replace("{{keywords}}",$keywords,$docheader);
	$docheader = str_replace("{{shortlink}}",$pagefile,$docheader);
	$docheader = str_replace("{{timestamp}}",$myDate,$docheader);
	$docheader = str_replace("{{mnu_$pagefile}}","dropdown active highlight",$docheader);
	foreach ($translations as $key => $value) {
		$docheader=str_replace("{{".$key."}}", $value, $docheader);
	}
	return($docheader);
}
function publish($myDBKey,$dbkey,$publicsite){
	$publicsite_path=str_replace("https://", "", $publicsite);
	$publicsite_path=str_replace("http://", "", $publicsite_path);
	$publicsite_path=str_replace("www.", "", $publicsite_path);
	// Writing Keys:
	include("config.php");
	echo '<html class="no-js" prefix="og: http://ogp.me/ns#" lang="en">
<head>
<link rel="manifest" href="/manifest.json">
<meta charset="utf-8">
<title>Publish '.$publicsite.'</title>

<style type="text/css">
body{
	font-family:courier,sans,verdana;
	font-size:12px;
	background-color:#000;
	color:#fff;
	width:100%;
}
table{
	width:100%;
	border-width:1px;
	border-style:solid;
	border-color:#07ff00;
}
b{
	color:#07ff00;
}
strong{
	color:#07ff00;
}
</style>
</head>
<body>';
	echo '<center>Publishing '.$publicsite.'</center><br>';
	if ($myDBKey==$dbkey AND strlen($publicsite)>0) {
		$published_path="published";
		if (!file_exists($published_path)) {
		    mkdir($published_path, 0777, true);
		} else {
			exec("rm -r published");
		}
		exec("cp -r ".getcwd()."/*.* ".getcwd()."/$published_path/");
		exec("cp -r ".getcwd()."/.htaccess ".getcwd()."/$published_path/.htaccess");
		$dirs = array_filter(glob('*'), 'is_dir');
		foreach ($dirs as $subfolder) {
			exec("cp -r ".getcwd()."/$subfolder ".getcwd()."/$published_path/");
		}
		exec ("rm -rf ".getcwd()."/$published_path/published");
		if (!file_exists($published_path."/js")) {
		    mkdir($published_path."/js", 0777, true);
		}
		if (!file_exists($published_path."/images")) {
		    mkdir($published_path."/images", 0777, true);
		}
		if (!file_exists($published_path."/css")) {
		    mkdir($published_path."/css", 0777, true);
		}
		if (!file_exists($published_path."/webpages")) {
		    mkdir($published_path."/webpages", 0777, true);
		}
		// Compressing all html files
		$contentheader = file_get_contents($published_path."/webpages/header.html");
		$contentheader = str_replace("\n", " ", $contentheader);
		$contentheader = str_replace("\r", " ", $contentheader);
		$contentheader = str_replace("\t", " ", $contentheader);
		$contentheader = str_replace("  ", " ", $contentheader);
		$contentheader = str_replace("> <", "><", $contentheader);
		$contentheader = str_replace(">  <", "><", $contentheader);
		$contentheader = str_replace(">   <", "><", $contentheader);
		$contentfooter = file_get_contents($published_path."/webpages/footer.html");
		$contentfooter = str_replace("\n", " ", $contentfooter);
		$contentfooter = str_replace("\r", " ", $contentfooter);
		$contentfooter = str_replace("\t", " ", $contentfooter);
		$contentfooter = str_replace("> <", "><", $contentfooter);
		$contentfooter = str_replace(">  <", "><", $contentfooter);
		$contentfooter = str_replace(">   <", "><", $contentfooter);
		$contentfooter = str_replace(">    <", "><", $contentfooter);
		$contentfooter = str_replace("  ", " ", $contentfooter);
		$contentfooter = str_replace("   ", " ", $contentfooter);
		$contentfooter = str_replace("    ", " ", $contentfooter);
		$contentfooter = str_replace("   ", " ", $contentfooter);
		$contentfooter = str_replace("  ", " ", $contentfooter);
		$contentfooter = str_replace(">   <", "><", $contentfooter);
		$contentfooter = str_replace(">  <", "><", $contentfooter);
		$contentfooter = str_replace("> <", "><", $contentfooter);
		if (sizeof($languages)>0) {

			$header_languages=array();

			for ($i=0; $i < sizeof($languages); $i++) {
				$header_languages[$i]="";
				$translations = NULL;
			    if (is_null($translations)) {
			        $lang_file =  'locales/' . $languages[$i] . '.json';
			        if (!file_exists($lang_file)) {
			            $lang_file = 'locales/' . 'en.json';
			        }
			        $lang_file_content = file_get_contents($lang_file);
			        $translations = json_decode($lang_file_content, true);
			        $header_languages[$i]=$contentheader;
			        foreach ($translations as $key => $value) {
						$header_languages[$i]=str_replace("{{".$key."}}", $value, $header_languages[$i]);
					}
					//echo "$i -- $lang_file: ".$header_languages[$i];
			    }
			}
		} else {
			die("No languages");
		}
		foreach (glob($published_path."/webpages/*.html") as $filenamehtml) {
		    if (strlen($filenamehtml)) {
		    	switch ($filenamehtml) {
		    		case 'header.html':
		    		case 'footer.html':
					case 'search.html':
					case stristr($filenamehtml,'google'):
					case stristr($filenamehtml,'mywot'):
					case stristr($filenamehtml,'bing'):
					case stristr($filenamehtml,'yandex'):
					case stristr($filenamehtml,'profile_'):
						$content = file_get_contents($filenamehtml);
						$new_content=str_replace("\n", " ", $content);
						$new_content=str_replace("\r", " ", $new_content);
						$new_content=str_replace("\t", " ", $new_content);
						$myfile = fopen($filenamehtml, "w") or die("Unable to open file $filenamehtml!");
						fwrite($myfile, $new_content);
						fclose($myfile);
		    			break;
		    		default:
		    			echo '<table><tr>';
		    			echo "<td>Compressing <strong>$filenamehtml</strong></td><td>Replacing keys from config file..., this file is in (".sizeof($languages).")Language(s)</td>";
		    			echo '</tr></table>';
				    	$basefilename=basename($filenamehtml, ".html");
				    	if (sizeof($languages)>0) {
				    		for ($i=0; $i < sizeof($languages); $i++) {
				    			$lang_dir="published/".$languages[$i]."/".$basefilename;
				    			
				    			if (!file_exists("published/images/index.html")){
				    				touch("published/images/index.html");
				    			}
				    			if (!file_exists("published/images/og/index.html")){
				    				touch("published/images/og/index.html");
				    			}
				    			if (!file_exists("published/images/partners/index.html")){
				    				touch("published/images/partners/index.html");
				    			}
				    			if (!file_exists("published/images/slides/index.html")){
				    				touch("published/images/slides/index.html");
				    			}

				    			if (!file_exists("published/css/index.html")){
				    				touch("published/css/index.html");
				    			}
				    			if (!file_exists("published/js/index.html")){
				    				touch("published/js/index.html");
				    			}
				    			if (!file_exists("published/fonts/index.html")){
				    				touch("published/fonts/index.html");
				    			}
				    			echo "<p>Translation directory $lang_dir</p>\r\n";
				    			if (!file_exists("published/".$basefilename)) {
						    		mkdir("published/".$basefilename, 0777, true);
						    	}
				    			if (!file_exists($lang_dir)) {
							    	mkdir($lang_dir, 0777, true);
									$translations = NULL;
								    if (is_null($translations)) {
								        $lang_file =  'locales/' . $languages[$i] . '.json';
								        if (!file_exists($lang_file)) {
								            $lang_file = 'locales/' . 'en.json';
								        }
								        $lang_file_content = file_get_contents($lang_file);
								        $translations = json_decode($lang_file_content, true);
								    }
								    echo "<p><b> Setting Header for :</b> $basefilename in $languages[$i] file:$lang_file</p>\n";
								    $contentheader=setheader($header_languages[$i],$basefilename,$languages[$i],$translations);
								    //echo "<p style='font-family:courier'>".$contentheader."</p>";
								    $webpage_content=file_get_contents($filenamehtml);
								    //$webpage_content_arr=explode("\n", $webpage_content);
								    $compression=true;
								    $new_webpage_content="";
								    foreach(preg_split("/((\r?\n)|(\r\n?))/", $webpage_content) as $line){
									    // do stuff with $line
									    if ($compression===true) {
									    	$templine=trim($line);
										} else {
											$templine=$line;
										}
							    		if (strlen($templine)>0) {
							    			if ($compression===true) {
												if ($templine=='<script type="text/javascript">') {
													// stop compression
													$compression=false;
													$new_webpage_content.="\r\n".$templine;
												} else {
													$templine=str_replace("\t", " ", $templine);
													$templine=str_replace("               ", " ", $templine);
													$templine=str_replace("              ", " ", $templine);
													$templine=str_replace("             ", " ", $templine);
													$templine=str_replace("            ", " ", $templine);
													$templine=str_replace("           ", " ", $templine);
													$templine=str_replace("          ", " ", $templine);
													$templine=str_replace("         ", " ", $templine);
													$templine=str_replace("        ", " ", $templine);
													$templine=str_replace("       ", " ", $templine);
													$templine=str_replace("     ", " ", $templine);
													$templine=str_replace("    ", " ", $templine);
													$templine=str_replace("   ", " ", $templine);
													$templine=str_replace("  ", " ", $templine);
													$templine=str_replace("> <", "><", $templine);
													$new_webpage_content.=" ".$templine;
												}
											} else {
												$new_webpage_content.="\r\n".$templine;
											}
							    		}
									}
									$content = $contentheader."\n".$new_webpage_content."\n".$contentfooter;
									$content = content_vars($content);
									foreach ($translations as $key => $value) {
										$content=str_replace("{{".$key."}}", $value, $content);
									}
									file_put_contents($lang_dir."/index.html", $content);
									if ($i == 0) {
										file_put_contents("published/".$basefilename."/index.html", $content);
									}
								}
				    		}
				    	}
						//die();
				    	$e="minify -o ".getcwd()."/published/".$filenamehtml." ".$filenamehtml;
				    	//exec ($e);
		    			break;
		    	}
		    }
		}
		if (isset($_REQUEST['compression'])) {
			foreach (glob("js/*.js") as $filename) {
			    if (strlen($filename)) {
			    	$e="minify -o ".getcwd()."/published/".$filename." ".getcwd()."/".$filename;
			    	//echo "<br>\n $e";
			    	exec ($e);
			    }
			}
			foreach (glob("images/*.png") as $filename) {
			    if (strlen($filename)) {
			    	$e="optipng -o7 -quiet ".getcwd()."/".$filename." ".getcwd()."/published/".$filename;
			    	//echo "<br>\n $e";
			    	exec ($e);
			    }
			}
			if (file_exists("images/slides")) {
				foreach (glob("images/slides/*.png") as $filename) {
				    if (strlen($filename)) {
				    	$e="optipng -o7 -quiet ".getcwd()."/".$filename." ".getcwd()."/published/".$filename;
				    	//echo "<br>\n $e";
				    	exec ($e);
				    }
				}
			}
			if (file_exists("images/portfolio")) {
				foreach (glob("images/portfolio/*.png") as $filename) {
				    if (strlen($filename)) {
				    	$e="optipng -o7 -quiet ".getcwd()."/".$filename." ".getcwd()."/published/".$filename;
				    	//echo "<br>\n $e";
				    	exec ($e);
				    }
				}
			}
			if (file_exists("images/partners")) {
				foreach (glob("images/partners/*.png") as $filename) {
				    if (strlen($filename)) {
				    	$e="optipng -o7 -quiet ".getcwd()."/".$filename." ".getcwd()."/published/".$filename;
				    	//echo "<br>\n $e";
				    	exec ($e);
				    }
				}
			}
			if (file_exists("images/clients")) {
				foreach (glob("images/clients/*.png") as $filename) {
				    if (strlen($filename)) {
				    	$e="optipng -o7 -quiet ".getcwd()."/".$filename." ".getcwd()."/published/".$filename;
				    	//echo "<br>\n $e";
				    	exec ($e);
				    }
				}
			}
			if (file_exists("images/projects")) {
				foreach (glob("images/projects/*.png") as $filename) {
				    if (strlen($filename)) {
				    	$e="optipng -o7 -quiet ".getcwd()."/".$filename." ".getcwd()."/published/".$filename;
				    	//echo "<br>\n $e";
				    	exec ($e);
				    }
				}
			}
			foreach (glob("images/og/*.png") as $filename) {
			    if (strlen($filename)) {
			    	$e="optipng -o7 -quiet ".getcwd()."/".$filename." ".getcwd()."/published".$filename;
			    	//echo "<br>\n $e";
			    	exec ($e);
			    }
			}
			foreach (glob("css/*.css") as $filename) {
			    if (strlen($filename)) {
			    	$e="minify -o ".getcwd()."/published/".$filename." ".getcwd()."/".$filename;
			    	//echo "<br>\n $e";
			    	exec ($e);
			    }
			}
		}
		exec("cd ".getcwd().";cp index_published.php $published_path/index.php");
		exec("cd ".getcwd().";cp webpages/search.html $published_path/search.html");
		exec("cd ".getcwd().";cp config.php $published_path/config.php");
		exec("cd ".getcwd().";cp actions_app.php $published_path/actions_app.php");
		exec("cd ".getcwd().";cp google* $published_path");
		exec("cd ".getcwd().";cp mywot* $published_path");
		exec("cd ".getcwd().";cp profile* $published_path");
		exec("cd ".getcwd().";cp keybase* $published_path");
		exec("cd ".getcwd().";cp $dbkey $published_path");
		exec("cd ".getcwd().";cp sitemap* $published_path");
		exec("cd ".getcwd().";cp manifest* $published_path");
		if (sizeof($languages)>0) {
			for ($i=0; $i < sizeof($languages); $i++) {
				exec("cd ".getcwd()."/published;cp ".$languages[$i]."/home/index.html ".$languages[$i]."/index.html");
			}
		}
		exec("cd ".getcwd()."/$published_path;rm -R header;rm -R footer;rm -R webpages;");
		$r="rsync -av /home/$draftsite/published/ /home/$publicsite/";
		$e="cd ".getcwd()."/$published_path;tar -zcvf /home/".$publicsite_path.".tar.gz *";
		echo "<br>\n $e \n<br>";
		exec($e);
		exec($r);
	} else {
		die("You can't publish.");
	}
}
function content_vars($content){
	include("config.php");
	$cline = $content;
	$cline = str_replace("{{g_captcha}}",$g_captcha,$cline);
	return($cline);
}
if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])){
	$browser_lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
} else {
	$browser_lang = "en";
}

//die($browser_lang);
if (isset($_REQUEST['a'])) {
	$action=trim($_REQUEST['a']);
	if (strlen($action)>0) {
		switch($action) {
			case "newsletter":
				if (isset($_REQUEST['e']) && isset($_REQUEST['fn']) && isset($_REQUEST['ln'])) {
					newsletterdb($dbkey,$_REQUEST['e'],$_REQUEST['fn'],$_REQUEST['ln']);
				} else {
					die("Missing vars");
				}
				break;
			case "rebuildsearch":
				if (isset($_REQUEST['dbkey'])){
					rebuildsearch($_REQUEST['dbkey'],$dbkey,$publicsite,$maintitle);
				}
				break;
			case "publish":
				if (isset($_REQUEST['dbkey'])){
					rebuildsearch($_REQUEST['dbkey'],$dbkey,$publicsite,$maintitle);
					publish($_REQUEST['dbkey'],$dbkey,$publicsite);

				}
				break;
			default:
				include("actions_app.php");
		}
	}
} else {
	if (isset($_GET['page'])) {
		$rootpage=$_GET['page'];
	} else {
		$rootpage="";
	}
	if (isset($_GET['p'])) {
		$pagefile=cleanget($_GET['p']);
	}
	if (isset($_GET['lang'])) {
		$page_language=$_GET['lang'];
	} else {
		$page_language="en";
	}
	switch ($page_language) {
		case 'fr':
			//die("Language loaded fr");
			break;
		case 'en':
			//die("Language loaded en");
			break;
		default:
			$page_language=$browser_lang;
			$pagefile=$page_language;
			break;
	}
	$pagefile = "Home | $maintitle";
	$docheader = "";
	$description = "";
	$keywords = "";
	$shortlink = "";
	static $translations = NULL;
    if (is_null($translations)) {
        $lang_file =  'locales/' . $page_language . '.json';
        if (!file_exists($lang_file)) {
            $lang_file = 'locales/' . 'en.json';
        }
        $lang_file_content = file_get_contents($lang_file);
        $translations = json_decode($lang_file_content, true);
    }
	$header = fopen("webpages/header.html", "r") or die("Unable to open file!");
	$footer = fopen("webpages/footer.html", "r") or die("Unable to open file!");
	while(!feof($header)) {
		$docheader .= fgets($header) . "";
	}
	fclose($header);
	//die("Language loaded $lang_file page:".$_GET['p']);
	if (isset($_GET['p'])) {
		
		$pagefile=cleanget($_GET['p']);
		
		if ($pagefile=="" && strlen($rootpage)>0) {
			$pagefile=str_replace("/", "", $rootpage);
		}
		if ($pagefile=="fr"||$pagefile=="de"||$pagefile=="en"||$pagefile=="es"||$pagefile=="nl"||$pagefile=="it"||$pagefile=="tr"||$pagefile=="cn"){
			$pagefile="";
		}

		if ($pagefile!="") {

			if (file_exists("webpages/".$pagefile.".html")) {
				$docheader=setheader($docheader,$pagefile,$page_language,$translations);
				echo $docheader;
				if (file_exists("webpages/".$page_language."_".$pagefile.".html")) {
					
					echo file_get_contents("webpages/".$pagefile.".html");
				} else {

					// Draft Status, just translate in real time.
					$content=file_get_contents("webpages/".$pagefile.".html");
					//print_r($translations);
					
					foreach ($translations as $key => $value) {
						$content=str_replace("{{".$key."}}", $value, $content);
						//print_r("{{".$key."}}".$value);
					}
					//die();
					echo $content;
				}
			} else {
				//die($pagefile);
				$pagefile=str_replace("/", "", $rootpage);
				$docheader=setheader($docheader,"404",$page_language,$translations);
				echo $docheader;
				$content=file_get_contents("webpages/404.html");
				echo $content;
			}
		} else {

			$docheader=setheader($docheader,"home",$page_language,$translations,$description,$keywords);
			echo $docheader;
			$content=file_get_contents("webpages/home.html");
			foreach ($translations as $key => $value) {
				$content=str_replace("{{".$key."}}", $value, $content);
			}
			echo $content;
		}
		/*
		print_r("pagefile=".$pagefile);
		print_r("\n <br>");
		print_r($rootpage);
		print_r("\n <br>");
		print_r("browser_lang=".$browser_lang);
		print_r("\n <br>");
		print_r($_GET);
		die ();
		*/
		
	} else {
		if (isset($_GET['s'])) {
			$docheader = setheader($docheader,"search",$page_language,$translations);
			echo $docheader;
			search($_GET['s'],$dbkey);
		} else {
			$docheader=setheader($docheader,"home",$page_language,$translations,$description,$keywords);
			echo $docheader;
			$content=file_get_contents("webpages/home.html");
			foreach ($translations as $key => $value) {
				$content=str_replace("{{".$key."}}", $value, $content);
			}
			echo $content;
		}
	}

	$docfooter="";
	while(!feof($footer)) {
		$docfooter.=fgets($footer) . "";
	}
	fclose($footer);
	$docfooter = str_replace("{{year}}",date("Y"),$docfooter);
	foreach ($translations as $key => $value) {
		$docfooter=str_replace("{{".$key."}}", $value, $docfooter);
	}
	echo $docfooter;
	//die("Language loaded $lang_file [$page_language] page:".$_GET['p']." pagefile=".$pagefile." s=".$_GET['s']);
}
