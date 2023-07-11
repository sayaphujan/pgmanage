<?

function get_step_email($message, $images = array(), $pdfs = array()) 
{
    if(!is_array($images))
    {
        $images = array();    
    }
    
    if(!is_array($pdfs))
    {
        $pdfs = array();    
    }
    
	$email = '<html>
	            <head>
	                <title>Peregrine Manufacturing</title>
	            </head>
	            <body style="background-color: #222; color: #fff; font-family: sans-serif;">
	                <table style="max-width: 600px; margin: auto; width: 100%">
	                    <tr>
	                        <td style="text-align: center"><img src="'.root().'images/logo.png"><br /><br />
	                            <div style="padding:10px; border: 1px solid #ddd; width: 100%;">
	                            <h1>Container Update</h1>';
	$email .= nl2br($message);

	if(count($pdfs)>0) 
	{
	    $email .=   '<br/><br/>
	                <strong>Check out these pdf of your new container!</strong><hr></hr><br />
	                <table width="100%">
	                    <tr>
	                        <td><a href="'.$pdfs[0].'">FINAL DESIGN</a></td>
	                        <td><a href="'.$pdfs[1].'">ORDER DATA</a></td>
	                    </tr1
	               </table>';
	}
	
	if(count($images)>0) 
	    $email .=   '<br /><br />
	                <strong>Check out these pictures of your new container!</strong><hr></hr><br />
	                <table width="100%">
	                    <tr>';
		
	$i = 0;
	foreach($images as $key=>$img_id) 
	{
		$email .=   '<td style="text-align: center">
		                <a href="'.root().'media/images/'.$img_id.'.png">
		                    <img src="'.root().'media/images/'.$img_id.'.png" style="margin: auto; width: 250px; border:1px solid #ddd; max-height: 300px; ">
		                </a>
		            </td>';
		
		$i++;
		
		if($i == 2) {
			$i = 0;
			$email .= '</tr><tr>';
		}

	}
	
	if(count($images)>0) $email .= '</tr></table>';
	
	$email .= '</div><br />
	<br />
	info@peregrinemfginc.com - http://www.peregrinemfginc.com/</td></tr></table></body></html>';
	
	return $email;

}

?>