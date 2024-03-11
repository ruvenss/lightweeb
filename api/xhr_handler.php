<?php
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