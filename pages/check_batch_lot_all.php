<?
if(!check_access('production')) exit();

//product that need to be checked
$product_ids = [70, 82, 89, 94, 96];
$product_ids_raw = implode(',', $product_ids);

//get batch lot data put them into array
//get batch lot data put them into array
//get batch lot data put them into array
$batch_lot = array();
$query = mysqli_query($link, 'SELECT * FROM batch_lots ORDER BY lot_number ASC');

while($row = mysqli_fetch_assoc($query))
{
    $material = trim(strtolower($row['material']));
    $color = trim(strtolower($row['color']));
    
    if($row['lot_number'] != 1 && $material != '' && $color != '' && $row['lot_number'] != '')
        $batch_lot[$material.'_'.$color][] = $row['lot_number'];
}
//echo "<pre>";
//print_r($batch_lot);
//echo "</pre>";

//get product part data put them into array
//get product part data put them into array
//get product part data put them into array
$product_part = array();
$query = mysqli_query($link, 'SELECT * FROM product_parts WHERE batch_lot = 1 AND product IN ('.$product_ids_raw.')');

while($row = mysqli_fetch_assoc($query))
{
    $product_part[$row['product']][] = $row['name'];
}

//echo "<pre>";
//print_r($product_part);
//echo "</pre>";
  
$parts = mysqli_query($link, 'SELECT  
                                      projects.id as project_id,
                                      projects.serial,
                                      projects.product,
                                      projects.name as product_name,
                                      project_parts.name, 
                                      project_parts.variables, 
                                      project_parts.batch_lot
                                  FROM project_parts
                                  LEFT JOIN projects ON projects.id = project_parts.project
                                  WHERE 
                                      projects.product IN ('.$product_ids_raw.') 
                                      #AND (batch_lot IS NULL OR batch_lot = 1) 
                                      AND projects.status = "completed"
                                 ');
?>
<br>
<br>
<style>
table td{
  padding: 2px !important;
  font-size: 12px;
  border-top: 1px dotted #ddd !important;
}
table tr{
  cursor: pointer;
}
table tr:hover
{
  background-color: yellow;
}
</style>
<table class="table">
    <tr>
        <th>SERIAL</th>
        <th>PRODUCT</th>
        <th>PART NAME</th>
        <th>MATERIAL</th>
        <th>COLOR</th>
        <th>BATCH LOT</th>
        <th>CHECKING RESULT</th>
    <tr>
<?
while($part = mysqli_fetch_assoc($parts))
{
    //echo "<pre>";
    //print_r($part);
    //echo "</pre>";
    
    //echo $part['product'];
    
    $part_variables = json_decode($part['variables'], true);
    
    $material = '';
    $color = '';
    
    if(is_array($part_variables))
    {
        foreach($part_variables as $key => $var)
        {
            if(!empty($var['value'])) 
            {                        
                if(preg_match('/Material/', $var['name']))
                {
                    $material = strtolower($var['value']);
                }
                
                if(preg_match('/Color/', $var['name']))
                {
                    $color = strtolower($var['value']);
                }
            }
        }
    }
    
    if($material != '' && $color != '')
    {
        if(in_array($part['name'], $product_part[$part['product']]))
        {
            $key = trim($material.'_'.$color);
            
            //echo $key."<br>";
            //echo $part['batch_lot'];
            //print_r($batch_lot[$key]);
            
            if($part['batch_lot'] == '')
            {
                $message = "EMPTY";
                continue;
            }
            else
            {
                if(is_array($batch_lot[$key]))
                {
                    if(in_array($part['batch_lot'], $batch_lot[$key]))
                    {
                        $message = "PASS";
                        continue;
                    }
                    else
                    {
                        $message = "<div title='Need to reassign batch lot number from step page.\nClick here to fix'>MISMATCH</div>";
                    }
                }
                else
                {
                    $message = "<div title='Batch lot setting is not found for this material + color or it is found but the value is 1.\nNeed to set the lot number in SETTINGS page, then click here to reassign batch lot number from step page'>NOT FOUND<div>";
                }
            }
            
            $link = "window.open('".root('?page=check_batch_lot&sn='.$part['serial'])."', '_blank')";
            
            echo "<tr onclick=\"$link\">";
            echo "<td>".$part['serial']."</td>";
            echo "<td>".$part['product_name']."</td>";
            echo "<td>".$part['name']."</td>";
            echo "<td>".$material."</td>";
            echo "<td>".$color."</td>";
            echo "<td>".$part['batch_lot']."</td>";
            echo "<td>".$message."</td>";
            echo "</tr>";
        }
    }
}  
?>
</table>