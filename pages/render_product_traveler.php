<?php    
$_SESSION['generate'] = (isset($_GET["generate"])) ? $_GET["generate"] : '';

    $product_id = intval($_GET['product']);
    $project_id  = intval($_GET['id']);
    $order_id  = intval($_GET['design_id']);

render_product_traveler($product_id, $project_id, $order_id);

?>
<script>
var id = "<?php echo $project_id; ?>";
var generate =  "<?php echo $_SESSION['generate']; ?>";
    if(generate == 'true'){
        window.location.href = '?page=edit_project&id='+id;
    }else{
        window.location.href = '?page=project&id='+id;        
    }

</script>