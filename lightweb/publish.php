<?php
function publish()
{
    echo "\nPublishing...\n";
    prepare_render();
    echo ".------------------------------------------.\n";
    echo "|                Pages                |    |\n";
    echo "|-------------------------------------|----|\n";
    foreach (LIGHTWEB_TREE as $key => $value) {
        echo "| " . str_pad($key, 35, " ", STR_PAD_RIGHT) . " | ";

        echo "✅ |\n";
        echo "--------------------------------------|----|";
        echo "\n";
    }
    echo "--------------------------------------------\n";
    echo "|Render complete                           |\n";
    echo "--------------------------------------------\n";
}
function prepare_render()
{
    if (!file_exists(LIGHTWEB_PUBLISH_PATH)) {
        mkdir(LIGHTWEB_PUBLISH_PATH);
        mkdir(LIGHTWEB_PUBLISH_PATH . "compress");
        mkdir(LIGHTWEB_PUBLISH_PATH . "uncompress");
    }
}