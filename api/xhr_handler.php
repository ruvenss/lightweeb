<?php
/**
 * DO NOT INSERT YOUR CODE HERE! THIS FILE WILL BE REWRITE IN THE NEXT UPDATE
 * USE ONLY FILES THAT BEGIN BY my_
 * @param mixed $success
 * @param mixed $arrdata
 * @param mixed $error
 * @param mixed $error_msg
 * @param mixed $module
 * @return never
 * @author Ruvenss G. Wilches <ruvenss@gmail.com>
 */
function response($success = false, $arrdata = [], $error = 0, $error_msg = null, $module = null)
{
    header('X-Powered-By: NIZU API nizu.io');
    $response = [
        "success" => $success,
        "data" => $arrdata
    ];
    if ($error_msg) {
        header('Error: ' . $error_msg);
        header('Error-Code: ' . $error);
        $response["success"] = false;
    } else {
        header('Module: ' . $module);
    }
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    flush();
    ob_flush();
    exit();
}