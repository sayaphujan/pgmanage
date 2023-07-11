<?php

$id = sf($_GET['id']);
$product = sf($_GET['product']);
$s  = sf(md5($id.'peregrin3!'));

if($product == 96)
{
    echo '<META HTTP-EQUIV="Refresh" Content="0; URL=https://design.peregrinemfginc.com/do/api_render_falkyn/?id='.$id.'&s='.$s.'&product='.$product.'">';  
}
else
{
    echo '<META HTTP-EQUIV="Refresh" Content="0; URL=https://design.peregrinemfginc.com/do/api_render/?id='.$id.'&s='.$s.'&product='.$product.'">';  
}
?>