<?php

require_once 'inc/functions.php';

$query = "SELECT    
                projects.id, 
                projects.peregrine_id,
                designers_pgdesign.user_id,
                customers.name,
                customers.address,
                customers.address_2,
                customers.city,
                customers.state,
                customers.zip,
                customers.country,
                customers.email,
                customers.phone
            FROM projects 
            LEFT JOIN designers_pgdesign ON designers_pgdesign.id = projects.peregrine_id
            LEFT JOIN customers ON customers.id = projects.customer
            WHERE peregrine_id != ''";
$result = mysqli_query($link, $query);

while($output = mysqli_fetch_assoc($result))
{
    //echo ($output['id'].' - peregrine_id: '.$output['peregrine_id']. ' - pgdesign_user_id: '.$output['user_id'])."<br>";
    //echo "name: ".$output['name']."<br>";
    //echo "address: ".$output['address']."<br>";
    //echo "address_2: ".$output['address_2']."<br>";
    //echo "city: ".$output['city']."<br>";
    //echo "state: ".$output['state']."<br>";
    //echo "zip: ".$output['zip']."<br>";
    //echo "country: ".$output['country']."<br>";
    //echo "email: ".$output['email']."<br>";
    //echo "phone: ".$output['phone']."<br>";
    
    if($output['email'] == '' || $output['user_id'] == '')
        continue;
    
    $update = "UPDATE `users` SET ";
    
    if($output['name'] != '')
    {
        $update .= " `name` = '".$output['name']."', ";
    }
    
    if($output['address'] != '')
    {
        $update .= " `address` = '".$output['address']."', ";
        $update .= " `shipping_address` = '".$output['address']."', ";
    }
    
    if($output['city'] != '')
    {
        $update .= " `city` = '".$output['city']."', ";
        $update .= " `shipping_city` = '".$output['city']."', ";
    }
    
    if($output['state'] != '')
    {
        $update .= " `state` = '".$output['state']."', ";
        $update .= " `shipping_state` = '".$output['state']."', ";
    }
    
    if($output['zipcode'] != '')
    {
        $update .= " `zipcode` = '".$output['zipcode']."', ";
        $update .= " `shipping_zipcode` = '".$output['zipcode']."', ";
    }
    
    if($output['country'] != '')
    {
        $update .= " `country` = '".$output['country']."', ";
        $update .= " `shipping_country` = '".$output['country']."', ";
    }
    
    if($output['phone'] != '')
    {
        $update .= " `phone` = '".$output['phone']."', ";
    }
    
    if($output['email'] != '')
    {
        $update .= " `email` = '".$output['email']."'";
    }
    
    $update .= " WHERE `users`.`id` = ".$output['user_id']." AND `users`.`email` = 'sample@email.tst';";
            
    echo $update."<br><br>";
    
    //mysqli_query($link, $update);
}

?>