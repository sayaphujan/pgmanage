<?php
ini_set('max_execution_time', '300'); //300 seconds = 5 minutes
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);


$id = sf($_GET['id']);
$s  = sf(md5($id.'peregrin3!'));
//$request = "https://projects.ndevix.com/pgdesign_dev/do/api_pull_order/?id=$id&s=$s";    
$request = "https://design.peregrinemfginc.com/do/api_pull_order/?id=$id&s=$s";    


// Generate curl request
$session = curl_init($request);
curl_setopt ($session, CURLOPT_POST, true);
curl_setopt ($session, CURLOPT_POSTFIELDS, $request);
curl_setopt($session, CURLOPT_HEADER, 0);
curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

// obtain response
$response = curl_exec($session);
curl_close($session);

//echo "<pre>";
//print_r($response);
//echo "</pre>";
//Create CSV file
$decoded_file = json_decode($response, true);

$results = array();
$results['options']     = $decoded_file['options'];
$results['colors']      = $decoded_file['colors'];
$results['measurement'] = $decoded_file['measurement'];

//debugging
//debugging
//debugging
$decoded_file['data']['Final_Design'] = '';
echo "<pre>";
print_r($decoded_file['data']);
echo "</pre>";
die();
?>