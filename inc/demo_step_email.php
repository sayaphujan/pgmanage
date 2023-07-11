<?

function demo_step_email($message) {
	$email = '<html><head><title>Peregrine Manufacturing</title></head><body style="background-color: #222; color: #fff; font-family: sans-serif;"><table style="max-width: 600px; margin: auto; width: 100%"><tr><td style="text-align: center"><img src="'.root().'images/logo.png"><br /><br /><div style="padding:10px; border: 1px solid #ddd; width: 100%;"><h1>Demo Container Update</h1>';
							
	$email .= nl2br($message);

	
	$email .= '</tr></table>';
	
	$email .= '</div><br />
	<br />
	info@peregrinemfginc.com - http://www.peregrinemfginc.com/</td></tr></table></body></html>';
	
	return $email;

}

?>