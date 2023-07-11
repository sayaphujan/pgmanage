<?php
$query = "SELECT id, name FROM products WHERE name LIKE 'IA-AC%'";
$result = mysqli_query($link, $query);
$products = array();

while($output = mysqli_fetch_assoc($result))
{
    $products[] = $output;    
}

echo json_encode($products);
?>