<?php
// Main and General
$version           = "1.0.0";
$underconstruction = true;
$draftsite         = "https://";
$publicsite        = "https://";
$maintitle         = "";
$nizuapikey        = "";
$nizusenderid      = 0;
// MariaDB Config
$dbhost			   = "";
$dbuser			   = "";
$dbpswd            = "";
$dbname            = "";
// Communication tools
$mailChimp_ApiKey  = "";	// http://kb.mailchimp.com/article/where-can-i-find-my-api-key
$mailChimp_ListId  = "";	// http://kb.mailchimp.com/article/how-can-i-find-my-list-id/
// Website
$logo              = ""; 
$owner             = "";
$street            = "";
$regionisocode     = "";
$locality          = "";
$countryname       = "";
$countrycode       = "";
$maintelephone     = "";
$mainfax           = "";
$contactmail       = "";
$latitude          = "";
$longitude         = "";
$postalcode        = "";
$classification    = "";
$ecommerce         = false;
$agency            = false;
$blog              = false;
$corporate         = true;
$realstate         = false;
$education         = false;
$wedding           = false;
// RGW Project
$rgw_customer_id   = 0;
$rgw_project_id    = 0;
$rgw_leads_source  = 13;
$rgw_erp           = "";
$rgw_api_token     = "";
// SEO
$xmlsitemap        = "sitemap.xml";
$rorsitemap        = "sitemap.ror";
$htmlsitemap       = "/sitemap"."/";
// Social Media
$og_cards          = true;
$og_cards_path     = "/images/og/";
$facebook_appid    = "";
$facebook_pageid   = "";
$facebook_url      = "https://www.facebook.com/";
$instagram_url     = "https://instagram.com/";
$dribbble_url      = "https://dribbble.com/";
$linkedin_url      = "https://www.linkedin.com/";
$twitter_url       = "";
$foursquare_url    = "";
$yelp_url          = "";
$youtube_url       = "";
$github_url        = "https://github.com/";
$pinterest_url     = "";
$tumblr_url        = "";
$behance_url       = "";
$slideshare_url    = "";
// Google
$g_captcha		     = "";
$g_analitycs_id	   = "";
$g_maps_key		     = "";
$g_calendar_key	   = "";
$g_validationid    = "";
// Microsoft
$msvalidate        = "";
// Internalisation i18
$isocountries      = ["be","be"];
$isolanguages      = ["eng","fra"];
$languages		     = ["en","fr"];
// Advanced Web
$pwa               = true;
$pwa_name          = "";
$pwa_short_name    = "";
$pwa_theme_color   = "#15257b";
$pwa_background    = "#15257b";
$pwa_orientation   = "portrait";
$pwa_display       = "minimal-ui";
$pwa_scope         = "/";
$pwa_start_url     = "/";
$pwa_screenshots   = "[]";
$pwa_generated     = "";
$pwa_icons_path    = "/images/icon-";
// Rocket Chat
$rc_host           = "";
$rc_whatsapp       = "";
// EU On premises analitycs
$matomo            = "matomo.nizu.io";
$matomo_site_id    = "";
// Local Business
// Opening Hours
$weekdays_open     = array(0,1,1,1,1,1,0);
$weekdays_open_hrs = array(
	"sun"=>array("start"=>"00:00","end"=>"00:00"),
	"mon"=>array("start"=>"10:30","end"=>"17:00"),
	"tue"=>array("start"=>"10:00","end"=>"23:00"),
	"wed"=>array("start"=>"08:00","end"=>"18:00"),
	"thu"=>array("start"=>"08:00","end"=>"18:00"),
	"fri"=>array("start"=>"08:00","end"=>"18:00"),
	"sat"=>array("start"=>"08:00","end"=>"18:00")
);
$google_maps_url   = "";
$openstreet_maps   = "";
