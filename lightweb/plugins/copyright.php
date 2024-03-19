<?php
function copyright($fullpage)
{
    $fullpage = str_replace("{{this_year}}", date("Y"), $fullpage);
    return ($fullpage);
}