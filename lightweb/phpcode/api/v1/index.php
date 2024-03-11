<?php
header("PoweredBy: LightWeb 3.0;");
header("Content-Type: application/json; charset=UTF-8");
/* This will only act as server to server bridge between the UI and the API Server to avoid CORS problems */
$currentpath = getcwd();
$currentpath = str_replace("/api/v1", "", $currentpath);
if (file_exists($currentpath . "/../config.php")) {
    include_once($currentpath . "/../config.php");
} else {
    die("config missing " . $currentpath);
}
/* The objective of this unique piece is to transfer any call AS IS to api.nizapp.com/v1 */
$headers = getallheaders();
$Token = $headers['Authorization'];
$entityBody = file_get_contents('php://input');
$post_data = json_decode($entityBody, true);
$curl = curl_init();
curl_setopt_array(
    $curl,
    array(
        CURLOPT_URL => APISERVER,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => $_SERVER['REQUEST_METHOD'],
        CURLOPT_POSTFIELDS => $entityBody,
        CURLOPT_HTTPHEADER => ["Content-Type: application/json", "Authorization: Bearer $Token"],
    )
);
$response = curl_exec($curl);
curl_close($curl);
if (curl_errno($curl)) {
    $error_msg = curl_error($curl);
    die('{"Error": "' . $error_msg . '"}"');
} else {
    echo $response;
}
die();
