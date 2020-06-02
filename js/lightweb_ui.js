var lw_language = "";
var lw_userlang = "en";
var lw_userurl = "";
var lw_arruserurl = "";
var lw_menu_arr=[];
var lw_currentpage = "";
var lw_parentpage="";
var lw_userid = 0;
var lw_ismobile=false;
function lw_getOS() {
  var userAgent = window.navigator.userAgent,
      platform = window.navigator.platform,
      macosPlatforms = ['Macintosh', 'MacIntel', 'MacPPC', 'Mac68K'],
      windowsPlatforms = ['Win32', 'Win64', 'Windows', 'WinCE'],
      iosPlatforms = ['iPhone', 'iPad', 'iPod'],
      os = null;
  if (macosPlatforms.indexOf(platform) !== -1) {
    os = 'Mac OS';
  } else if (iosPlatforms.indexOf(platform) !== -1) {
    os = 'iOS';
  } else if (windowsPlatforms.indexOf(platform) !== -1) {
    os = 'Windows';
  } else if (/Android/.test(userAgent)) {
    os = 'Android';
  } else if (!os && /Linux/.test(platform)) {
    os = 'Linux';
  }
  return os;
}
function lw_openInNewTab(url) {
  var win = window.open(url, '_blank');
  win.focus();
}
