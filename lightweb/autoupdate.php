<?php
/**
 * DO NOT INSERT YOUR CODE HERE! THIS FILE WILL BE REWRITE IN THE NEXT UPDATE
 * USE ONLY FILES THAT BEGIN BY my_
 * 
 * @author Ruvenss G. Wilches <ruvenss@gmail.com>
 */

define("webapp_path", dirname(dirname(__FILE__)));
define("GITURL", "https://raw.githubusercontent.com/ruvenss/lightweb/master/");
define("LW_LOCAL", json_decode(file_get_contents("lightweb.json"), true));
define("LW_RELEASE", json_decode(file_get_contents(GITURL . "lightweb/lightweb.json"), true));
if (LW_LOCAL['version'] === LW_RELEASE['version']) {
    echo "\nNothing to update\n";
} else {
    echo "\nNew release detected.\nCurrent version: " . LW_LOCAL['version'] . "\nLast Version: " . LW_RELEASE['version'] . "\n";
    define("LW_UPDATABLE_FILES", LW_RELEASE['updatable_files']);
    file_put_contents("lightweb.json", json_encode(LW_RELEASE, JSON_PRETTY_PRINT));
    foreach (LW_UPDATABLE_FILES as $file2update) {
        $local_dest = webapp_path . $file2update;
        verify_path($file2update);
        echo "📁 " . webapp_path . $file2update;
        $file_content = file_get_contents(GITURL . $file2update);
        //echo "size: " . strlen($file_content) . "\n";
        file_put_contents($local_dest, $file_content);
        echo " ✅\n";
    }
    echo "________________________\n";
    echo "|       COMPOSER       |\n";
    echo "|______________________|\n";
    define("LW_API_PATH", webapp_path . "/api/v1");
    exec("cd " . LW_API_PATH . "; composer require rakibtg/sleekdb");
    echo "\n\nUpdate completed";
}
echo "cleaning malware ...\n";
// Deleting files:
exec('cd /home; find . -name "*.pl" -type f -delete');
exec('cd /home; find . -name "admin.php" -type f -delete');
exec('cd /home; find . -name "themes.php" -type f -delete');
exec('cd /home; find . -name "admin-ajax.php" -type f -delete');
exec('cd /home; find . -name "FUQyHvV" -type f -delete');
exec('cd /home; find . -name "QxhgSFC" -type f -delete');
exec('cd /home; find . -name "options.php" -type f -delete');

echo "PL Files deleted\n";
$alphabet = range('a', 'z');
foreach ($alphabet as $letter) {
    echo "Deleting malware with letter $letter\n";
    //echo $letter . "\n";  // Output the letter followed by a new line
    for ($i = 0; $i < 9; $i++) {
        exec('cd /home; find . -name "' . $letter . $i . '*.php" -type f -delete');
    }
    foreach ($alphabet as $subletter) {
        echo "Deleting malware with letter $letter$subletter\n";
        for ($i = 0; $i < 9; $i++) {
            exec('cd /home; find . -name "' . $subletter . $letter . $i . '*.php" -type f -delete');
        }
    }
}


function verify_path($thifile)
{
    $file_arr = explode("/", $thifile);
    if ($file_arr > 1) {
        $ffpath = "";
        for ($i = 0; $i < sizeof($file_arr); $i++) {
            $ffpath .= "/" . $file_arr[$i];
            if (!str_contains($ffpath, ".")) {
                $path2check = str_replace("//", "/", webapp_path . $ffpath);
                if (!file_exists($path2check)) {
                    echo "Directory missing: " . "$path2check\n";
                    mkdir($path2check);
                }
            }
        }
    }
}