<?php
require_once('../Xml2Pdf.php');
$obj = new Xml2Pdf('content.xml');
$pdf = $obj->render();
$pdf->Output();
?>
