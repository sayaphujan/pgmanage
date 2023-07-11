<?
$content = trim(file_get_contents("php://input"));

$data = json_decode($content, true);
 
//If json_decode failed, the JSON is invalid.
if(is_array($data)){
    print_r($data);
	exit();
}

print_r($_REQUEST);


?>