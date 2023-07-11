<?php
//echo "save_final_design";
$_SESSION['generate'] = (isset($_GET["generate"])) ? $_GET["generate"] : '';

if(isset($_POST['data_image']))
{
    //echo "data_image";
    if($_POST['product_line'] == 'Glide')
    {
        $output_folder = $_SERVER['DOCUMENT_ROOT'].'/peregrinemanage/zip/Glide_order_';
    }
    elseif($_POST['product_line'] == 'Falkyn')
    {
        $output_folder = $_SERVER['DOCUMENT_ROOT'].'/peregrinemanage/zip/Falkyn_order_';
    }
    else
    {
        die('product not defined');
    }
    //echo "folder";
    
    // ################################ PDF ###########################################
    $output_file = $output_folder.$_POST['id'].'/Final_design_'.$_POST['id'].'.pdf';
    $dir = dirname($output_file);
    
    if(!file_exists($dir))
    {
        mkdir($dir, 0770);    
    }
    
    $ifp = fopen( $output_file, 'wb' ); 
    $data = explode( ',', $_POST['data_image']);
    $image=base64_decode($data[ 1 ]);
    $im = new Imagick();
    $im->readimageblob($image);
    $im->setImageFormat("pdf");
    $im->writeImages('Final_design_'.$_POST['id'].'.pdf', true);
    $output = $im->getimageblob();
    $outputtype = $im->getFormat();
    //header("Content-type: $outputtype");
    fwrite( $ifp, $output);
    fclose( $ifp ); 
    
    //return $output; 
    
    $output_file = 'Final_design_'.$_POST['id'].'.pdf';
    $folder_location = $output_folder.$_POST['id'].'/'.$output_file;
    
    rename('Final_design_'.$_POST['id'].'.pdf', $output_file);
    rename($output_file, $folder_location);
    
    // ################################ PNG ###########################################
    $output_png = $output_folder.$_POST['id'].'/Final_design_'.$_POST['id'].'.png';
    $dir = dirname($output_png);
    
    if(!file_exists($dir))
    {
        mkdir($dir, 0770);    
    }
    
    $ifp = fopen( $output_png, 'wb' ); 
    $data = explode( ',', $_POST['data_image']);
    $image=base64_decode($data[ 1 ]);
    $im = new Imagick();
    $im->readimageblob($image);
    $im->setImageFormat("png");
    $im->writeImages('Final_design_'.$_POST['id'].'.png', true);
    $output = $im->getimageblob();
    $outputtype = $im->getFormat();
    //header("Content-type: $outputtype");
    fwrite( $ifp, $output);
    fclose( $ifp ); 
    
    //return $output; 
    
    $output_png = 'Final_design_'.$_POST['id'].'.png';
    $folder_location = $output_folder.$_POST['id'].'/'.$output_png;
    
    rename('Final_design_'.$_POST['id'].'.png', $output_png);
    rename($output_png, $folder_location);
    
    //include '/pages/render_product_traveler.php';
}
?>