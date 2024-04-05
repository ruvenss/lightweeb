<?php
function copyright($fullpage, $lang, $uri)
{
    $fullpage = str_replace("{{this_year}}", date("Y"), $fullpage);
    return ($fullpage);
}