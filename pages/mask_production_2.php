<?
$order_status = 'PAID';


$types = array();
		$types['with_embroidery']='Black with Embroidery';
		$types['without_embroidery']='Black without Embroidery';
		$types['cb_with_embroidery']='Coyote Brown with Embroidery';
		$types['cb_without_embroidery']='Coyote Brown without Embroidery';
		$types['ulw_with_embroidery']='ULW with Embroidery';
		$types['ulw_without_embroidery']='ULW without Embroidery';
		$types['fr']='FR';
		$types['fl_black']='Featherlite Black';
		$types['fl_cb']='Featherlite Coyote Brown';
		
?>

<div class="col-md-6">
	<h1>Mask Production</h1>
  
</div>

<div class="row">
<div class="col-md-12">
<h3>Cut QTYs</h3>
<table class="table table-striped  table-bordered">
<tr>
	<th></th>
	<th>XS</th>
	<th>S</th>
	<th>M</th>
	<th>L</th>
	<th>XL</th>
</tr>
<?

$masks_q = mysqli_query($link, 'SELECT 
		
		(SELECT SUM(qty) as qty FROM `mask_order_items`,mask_orders WHERE mask_order_items.order_id = mask_orders.id AND mask_orders.order_status=\''.sf($order_status).'\' AND (mask_order_items.type=\'with_embroidery\' OR mask_order_items.type=\'without_embroidery\') AND mask_order_items.size=\'XS\') as XS,
		
		(SELECT SUM(qty) as qty FROM `mask_order_items`,mask_orders WHERE mask_order_items.order_id = mask_orders.id AND mask_orders.order_status=\''.sf($order_status).'\' AND (mask_order_items.type=\'with_embroidery\' OR mask_order_items.type=\'without_embroidery\') AND mask_order_items.size=\'S\') as S,
		
		(SELECT SUM(qty) as qty FROM `mask_order_items`,mask_orders WHERE mask_order_items.order_id = mask_orders.id AND mask_orders.order_status=\''.sf($order_status).'\' AND (mask_order_items.type=\'with_embroidery\' OR mask_order_items.type=\'without_embroidery\') AND mask_order_items.size=\'M\') as M,
		
		(SELECT SUM(qty) as qty FROM `mask_order_items`,mask_orders WHERE mask_order_items.order_id = mask_orders.id AND mask_orders.order_status=\''.sf($order_status).'\' AND (mask_order_items.type=\'with_embroidery\' OR mask_order_items.type=\'without_embroidery\') AND mask_order_items.size=\'L\') as L,
		
		(SELECT SUM(qty) as qty FROM `mask_order_items`,mask_orders WHERE mask_order_items.order_id = mask_orders.id AND mask_orders.order_status=\''.sf($order_status).'\' AND (mask_order_items.type=\'with_embroidery\' OR mask_order_items.type=\'without_embroidery\') AND mask_order_items.size=\'XL\') as XL
		
		');
		
		$mask = mysqli_fetch_assoc($masks_q);
			

?>
<tr>
	<td width="25%"><strong>Shredder CB Cut QTY</strong></td>
	<td width="15%"><?=$mask['XS']?></td>
	<td width="15%"><?=$mask['S']?></td>
	<td width="15%"><?=$mask['M']?></td>
	<td width="15%"><?=$mask['L']?></td>
	<td width="15%"><?=$mask['XL']?></td>
</tr>

<?

$masks_q = mysqli_query($link, 'SELECT 
		
		(SELECT SUM(qty) as qty FROM `mask_order_items`,mask_orders WHERE mask_order_items.order_id = mask_orders.id AND mask_orders.order_status=\''.sf($order_status).'\' AND (mask_order_items.type=\'cb_with_embroidery\' OR mask_order_items.type=\'cb_without_embroidery\') AND mask_order_items.size=\'XS\') as XS,
		
		(SELECT SUM(qty) as qty FROM `mask_order_items`,mask_orders WHERE mask_order_items.order_id = mask_orders.id AND mask_orders.order_status=\''.sf($order_status).'\' AND (mask_order_items.type=\'cb_with_embroidery\' OR mask_order_items.type=\'cb_without_embroidery\') AND mask_order_items.size=\'S\') as S,
		
		(SELECT SUM(qty) as qty FROM `mask_order_items`,mask_orders WHERE mask_order_items.order_id = mask_orders.id AND mask_orders.order_status=\''.sf($order_status).'\' AND (mask_order_items.type=\'cb_with_embroidery\' OR mask_order_items.type=\'cb_without_embroidery\') AND mask_order_items.size=\'M\') as M,
		
		(SELECT SUM(qty) as qty FROM `mask_order_items`,mask_orders WHERE mask_order_items.order_id = mask_orders.id AND mask_orders.order_status=\''.sf($order_status).'\' AND (mask_order_items.type=\'cb_with_embroidery\' OR mask_order_items.type=\'cb_without_embroidery\') AND mask_order_items.size=\'L\') as L,
		
		(SELECT SUM(qty) as qty FROM `mask_order_items`,mask_orders WHERE mask_order_items.order_id = mask_orders.id AND mask_orders.order_status=\''.sf($order_status).'\' AND (mask_order_items.type=\'cb_with_embroidery\' OR mask_order_items.type=\'cb_without_embroidery\') AND mask_order_items.size=\'XL\') as XL
		
		');
		
		$mask = mysqli_fetch_assoc($masks_q);
			
?>
<tr>
	<td width="25%"><strong>Shredder ULW Cut QTY</strong></td>
	<td width="15%"><?=$mask['XS']?></td>
	<td width="15%"><?=$mask['S']?></td>
	<td width="15%"><?=$mask['M']?></td>
	<td width="15%"><?=$mask['L']?></td>
	<td width="15%"><?=$mask['XL']?></td>
</tr>


<?

$masks_q = mysqli_query($link, 'SELECT 
		
		(SELECT SUM(qty) as qty FROM `mask_order_items`,mask_orders WHERE mask_order_items.order_id = mask_orders.id AND mask_orders.order_status=\''.sf($order_status).'\' AND (mask_order_items.type=\'ulw_with_embroidery\' OR mask_order_items.type=\'ulw_without_embroidery\') AND mask_order_items.size=\'XS\') as XS,
		
		(SELECT SUM(qty) as qty FROM `mask_order_items`,mask_orders WHERE mask_order_items.order_id = mask_orders.id AND mask_orders.order_status=\''.sf($order_status).'\' AND (mask_order_items.type=\'ulw_with_embroidery\' OR mask_order_items.type=\'ulw_without_embroidery\') AND mask_order_items.size=\'S\') as S,
		
		(SELECT SUM(qty) as qty FROM `mask_order_items`,mask_orders WHERE mask_order_items.order_id = mask_orders.id AND mask_orders.order_status=\''.sf($order_status).'\' AND (mask_order_items.type=\'ulw_with_embroidery\' OR mask_order_items.type=\'ulw_without_embroidery\') AND mask_order_items.size=\'M\') as M,
		
		(SELECT SUM(qty) as qty FROM `mask_order_items`,mask_orders WHERE mask_order_items.order_id = mask_orders.id AND mask_orders.order_status=\''.sf($order_status).'\' AND (mask_order_items.type=\'ulw_with_embroidery\' OR mask_order_items.type=\'ulw_without_embroidery\') AND mask_order_items.size=\'L\') as L,
		
		(SELECT SUM(qty) as qty FROM `mask_order_items`,mask_orders WHERE mask_order_items.order_id = mask_orders.id AND mask_orders.order_status=\''.sf($order_status).'\' AND (mask_order_items.type=\'ulw_with_embroidery\' OR mask_order_items.type=\'ulw_without_embroidery\') AND mask_order_items.size=\'XL\') as XL
		
		');
		
		$mask = mysqli_fetch_assoc($masks_q);
			

?>
<tr>
	<td width="25%"><strong>Shredder Black ULW Cut QTY</strong></td>
	<td width="15%"><?=$mask['XS']?></td>
	<td width="15%"><?=$mask['S']?></td>
	<td width="15%"><?=$mask['M']?></td>
	<td width="15%"><?=$mask['L']?></td>
	<td width="15%"><?=$mask['XL']?></td>
</tr>

<?

$masks_q = mysqli_query($link, 'SELECT 
		
		(SELECT SUM(qty) as qty FROM `mask_order_items`,mask_orders WHERE mask_order_items.order_id = mask_orders.id AND mask_orders.order_status=\''.sf($order_status).'\' AND mask_order_items.type=\'fr\' AND mask_order_items.size=\'XS\') as XS,
		
		(SELECT SUM(qty) as qty FROM `mask_order_items`,mask_orders WHERE mask_order_items.order_id = mask_orders.id AND mask_orders.order_status=\''.sf($order_status).'\' AND mask_order_items.type=\'fr\' AND mask_order_items.size=\'S\') as S,
		
		(SELECT SUM(qty) as qty FROM `mask_order_items`,mask_orders WHERE mask_order_items.order_id = mask_orders.id AND mask_orders.order_status=\''.sf($order_status).'\' AND mask_order_items.type=\'fr\' AND mask_order_items.size=\'M\') as M,
		
		(SELECT SUM(qty) as qty FROM `mask_order_items`,mask_orders WHERE mask_order_items.order_id = mask_orders.id AND mask_orders.order_status=\''.sf($order_status).'\' AND mask_order_items.type=\'fr\' AND mask_order_items.size=\'L\') as L,
		
		(SELECT SUM(qty) as qty FROM `mask_order_items`,mask_orders WHERE mask_order_items.order_id = mask_orders.id AND mask_orders.order_status=\''.sf($order_status).'\' AND mask_order_items.type=\'fr\' AND mask_order_items.size=\'XL\') as XL
		
		');
		
		$mask = mysqli_fetch_assoc($masks_q);
			

?>
<tr>
	<td width="25%"><strong>Shredder FR Cut QTY</strong></td>
	<td width="15%"><?=$mask['XS']?></td>
	<td width="15%"><?=$mask['S']?></td>
	<td width="15%"><?=$mask['M']?></td>
	<td width="15%"><?=$mask['L']?></td>
	<td width="15%"><?=$mask['XL']?></td>
</tr>

<?

$masks_q = mysqli_query($link, 'SELECT 
		
		(SELECT SUM(qty) as qty FROM `mask_order_items`,mask_orders WHERE mask_order_items.order_id = mask_orders.id AND mask_orders.order_status=\''.sf($order_status).'\' AND mask_order_items.type=\'fl_black\' AND mask_order_items.size=\'XS\') as XS,
		
		(SELECT SUM(qty) as qty FROM `mask_order_items`,mask_orders WHERE mask_order_items.order_id = mask_orders.id AND mask_orders.order_status=\''.sf($order_status).'\' AND mask_order_items.type=\'fl_black\' AND mask_order_items.size=\'S\') as S,
		
		(SELECT SUM(qty) as qty FROM `mask_order_items`,mask_orders WHERE mask_order_items.order_id = mask_orders.id AND mask_orders.order_status=\''.sf($order_status).'\' AND mask_order_items.type=\'fl_black\' AND mask_order_items.size=\'M\') as M,
		
		(SELECT SUM(qty) as qty FROM `mask_order_items`,mask_orders WHERE mask_order_items.order_id = mask_orders.id AND mask_orders.order_status=\''.sf($order_status).'\' AND mask_order_items.type=\'fl_black\' AND mask_order_items.size=\'L\') as L,
		
		(SELECT SUM(qty) as qty FROM `mask_order_items`,mask_orders WHERE mask_order_items.order_id = mask_orders.id AND mask_orders.order_status=\''.sf($order_status).'\' AND mask_order_items.type=\'fl_black\' AND mask_order_items.size=\'XL\') as XL
		
		');
		
		$mask = mysqli_fetch_assoc($masks_q);
			

?>
<tr>
	<td width="25%"><strong>Shredder FL Black Cut QTY</strong></td>
	<td width="15%"><?=$mask['XS']?></td>
	<td width="15%"><?=$mask['S']?></td>
	<td width="15%"><?=$mask['M']?></td>
	<td width="15%"><?=$mask['L']?></td>
	<td width="15%"><?=$mask['XL']?></td>
</tr>

<?

$masks_q = mysqli_query($link, 'SELECT 
		
		(SELECT SUM(qty) as qty FROM `mask_order_items`,mask_orders WHERE mask_order_items.order_id = mask_orders.id AND mask_orders.order_status=\''.sf($order_status).'\' AND mask_order_items.type=\'fl_cb\' AND mask_order_items.size=\'XS\') as XS,
		
		(SELECT SUM(qty) as qty FROM `mask_order_items`,mask_orders WHERE mask_order_items.order_id = mask_orders.id AND mask_orders.order_status=\''.sf($order_status).'\' AND mask_order_items.type=\'fl_cb\' AND mask_order_items.size=\'S\') as S,
		
		(SELECT SUM(qty) as qty FROM `mask_order_items`,mask_orders WHERE mask_order_items.order_id = mask_orders.id AND mask_orders.order_status=\''.sf($order_status).'\' AND mask_order_items.type=\'fl_cb\' AND mask_order_items.size=\'M\') as M,
		
		(SELECT SUM(qty) as qty FROM `mask_order_items`,mask_orders WHERE mask_order_items.order_id = mask_orders.id AND mask_orders.order_status=\''.sf($order_status).'\' AND mask_order_items.type=\'fl_cb\' AND mask_order_items.size=\'L\') as L,
		
		(SELECT SUM(qty) as qty FROM `mask_order_items`,mask_orders WHERE mask_order_items.order_id = mask_orders.id AND mask_orders.order_status=\''.sf($order_status).'\' AND mask_order_items.type=\'fl_cb\' AND mask_order_items.size=\'XL\') as XL
		
		');
		
		$mask = mysqli_fetch_assoc($masks_q);
			

?>
<tr>
	<td width="25%"><strong>Shredder FL Coyote Brown Cut QTY</strong></td>
	<td width="15%"><?=$mask['XS']?></td>
	<td width="15%"><?=$mask['S']?></td>
	<td width="15%"><?=$mask['M']?></td>
	<td width="15%"><?=$mask['L']?></td>
	<td width="15%"><?=$mask['XL']?></td>
</tr>

</table>


<br />
<br />
<h3>Embroidery</h3>

<table class="table table-striped  table-bordered">
<tr>
	<th width="5%">Order</th>
	<th width="40%">Description</th>
	<th>Mask Type</th>
	<th>Size</th>
	<th>QTY</th>
	<th>Embroidery</th>
</tr>

<?
$q = mysqli_query($link, 'SELECT mask_order_items.* FROM `mask_order_items`,mask_orders WHERE mask_order_items.order_id = mask_orders.id AND mask_orders.order_status=\''.sf($order_status).'\' AND (mask_order_items.type=\'with_embroidery\' OR mask_order_items.type=\'cb_with_embroidery\' OR mask_order_items.type=\'ulw_with_embroidery\') ORDER BY `mask_order_items`.id ASC');

while($mask = mysqli_fetch_assoc($q)) {
	
	echo '<tr>
		<td>'.$mask['order_id'].'</td>
		<td>'.$mask['description'].'</td>
		<td>'.$types[$mask['type']].'</td>
		<td>'.$mask['size'].'</td>
		<td>'.$mask['qty'].'</td>
		<td>';
		
		
		
		if($mask['embroidery_type']=='' || $mask['embroidery_type']=='pmi_logo') {
			echo 'PMI Logo - '.$mask['embroidery_color'];
		} else {
			$emb_q = mysqli_query($link, 'SELECT * FROM mask_embroidery WHERE `id`=\''.sf($mask['embroidery_file']).'\'');
			$emb = mysqli_fetch_assoc($emb_q);
			
			echo 'Custom<br />
			<a href="/inc/download_mask_embroidery.php?id='.$mask['embroidery_file'].'" target="_blank">Download</a><br />
			Color 1: '.$emb['color_1'].'<br />
			Color 2: '.$emb['color_2'].'<br />
			Color 3: '.$emb['color_3'].'
			
			';
		}
		echo '</td>
	</tr>
	';
	
}
?>
</table>

</div>
</div>
<br />
<br />

