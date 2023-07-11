<?
require 'functions.php';

if(!$_SESSION['uid']) exit();


$q = mysqli_query($link, 'SELECT * FROM mask_embroidery WHERE id=\''.sf($_GET['id']).'\'');

$file = mysqli_fetch_assoc($q);

$file_location = '../masks/uploads/embroidery/'.$file['id'];

if (file_exists($file)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="'.basename($file['file_name']).'"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file_location));
    readfile($file_location);
    exit;
}

?>