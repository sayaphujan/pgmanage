<?
require_once('../inc/functions.php');


if($_POST['txn_type']) {

	// CONFIG: Enable debug mode. This means we'll log requests into 'ipn.log' in the same directory.
	// Especially useful if you encounter network errors or other intermittent problems with IPN (validation).
	// Set this to 0 once you go live or don't require logging.
	
	define("DEBUG", 1);

	// Set to 0 once you're ready to go live

	define("LOG_FILE", "ndx-ipn-debug.log");


	$raw_post_data = file_get_contents('php://input');
	$raw_post_array = explode('&', $raw_post_data);
	$myPost = array();
	foreach ($raw_post_array as $keyval) {
			$keyval = explode ('=', $keyval);
			if (count($keyval) == 2)
					$myPost[$keyval[0]] = urldecode($keyval[1]);
	}

	// read the post from PayPal system and add 'cmd'
	$req = 'cmd=_notify-validate';
	if(function_exists('get_magic_quotes_gpc')) {
			$get_magic_quotes_exists = true;
	}
	foreach ($myPost as $key => $value) {
			if($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) {
					$value = urlencode(stripslashes($value));
			} else {
					$value = urlencode($value);
			}
			$req .= "&$key=$value";
	}

	// Post IPN data back to PayPal to validate the IPN data is genuine
	// Without this step anyone can fake IPN data
	
	$item_name = $_POST['item_name'];
	$item_number = $_POST['item_number'];
	$payment_status = $_POST['payment_status'];
	$payment_amount = $_POST['mc_gross'];
	$payment_currency = $_POST['mc_currency'];
	$txn_id = $_POST['txn_id'];
	$receiver_email = $_POST['receiver_email'];
	$payer_email = $_POST['payer_email'];
	
	
	//start ipn process
	$paypal_url = "https://www.paypal.com/cgi-bin/webscr";


	$ch = curl_init($paypal_url);
	if ($ch == FALSE) {
			return FALSE;
	}

	curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
	curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);

	if(DEBUG == true) {
			curl_setopt($ch, CURLOPT_HEADER, 1);
			curl_setopt($ch, CURLINFO_HEADER_OUT, 1);
	}

	// Set TCP timeout to 30 seconds
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));

	$res = curl_exec($ch);
	if (curl_errno($ch) != 0) // cURL error
			{
			if(DEBUG == true) {        
					error_log(date('[Y-m-d H:i e] '). "Can't connect to PayPal to validate IPN message: " . curl_error($ch) . PHP_EOL, 3, LOG_FILE);
			}
			curl_close($ch);
			exit;

	} else {
					// Log the entire HTTP response if debug is switched on.
					if(DEBUG == true) {
							error_log(date('[Y-m-d H:i e] '). "HTTP request of validation request:". curl_getinfo($ch, CURLINFO_HEADER_OUT) ." for IPN payload: $req" . PHP_EOL, 3, LOG_FILE);
							error_log(date('[Y-m-d H:i e] '). "HTTP response of validation request: $res" . PHP_EOL, 3, LOG_FILE);

							// Split response headers and payload
							list($headers, $res) = explode("\r\n\r\n", $res, 2);
					}
					curl_close($ch);
	}

	// Inspect IPN validation result and act accordingly

	if (strcmp ($res, "VERIFIED") == 0) {
			
			
			$q = mysqli_query($link, 'SELECT * FROM mask_orders WHERE `payment_status`=\'UNPAID\' AND `id`=\''.sf($_GET['order']).'\'');
			
			if(mysqli_num_rows($q)==1) {
				mysqli_query($link, 'UPDATE mask_orders SET `payment_status`=\'PAID\', `transaction_id`=\''.sf($txn_id).'\', `order_status`=\'PAID\' WHERE `id`=\''.sf($_GET['order']).'\'');
			} 
			
			$order = mysqli_fetch_assoc($q);
			
			$cart = json_decode($order['order_data'],true);
			
			foreach ($cart as $item_id=>$item) {
				$order_confirmation_items .= $item['qty'].' x '.$item['desc'].' - $'.number_format(($item['price']*$item['qty']), 2, '.', '')."\n";
			}
			
			
			$message = sf($order['name'])."\n\n";
			$message .= 'Thank for for placing an order for a Peregrine Manufacturing masks. Below is a confirmation of your order.'."\n\n";
			$message .= $order_confirmation_items;
			$message .= "\nTotal: $".number_format($order['order_total'],2,'.','')."\n\n";
			$message .= "Shipping Address:\n";
			$message .= sf($order['name'])."\n";
			$message .= sf($order['address_1'])."\n";
			if($order['address_2']) $message .= sf($order['address_2'])."\n";
			$message .= sf($order['city']).", ".sf($order['state'])." ".sf($order['zip'])."\n\n";
			$message .= "If you have any questions, please email us at pmisales@peregrinemfginc.com.\n\n";
			$message .= "Thank You\n\n";
			$message .= "Peregrine Manufacturing, Inc.";
			
			smtpemail(sf($order['email']),'Your Peregrine Manufacturing, Inc. Mask Order',$message);
			smtpemail('admin@peregrinemfginc.com','Your Peregrine Manufacturing, Inc. Mask Order',$message);


			if(DEBUG == true) {
					error_log(date('[Y-m-d H:i e] '). "Verified IPN: $req ". PHP_EOL, 3, LOG_FILE);
			}
	} else if (strcmp ($res, "INVALID") == 0) {
			// log for manual investigation
			// Add business logic here which deals with invalid IPN messages
			mysqli_query($link, 'UPDATE mask_orders SET payment_status=\'DECLINED\', `transaction_id`=\''.sf($txn_id).'\' WHERE `id`=\''.sf($_GET['order']).'\'');
			
			if(DEBUG == true) {
					error_log(date('[Y-m-d H:i e] '). "Invalid IPN: $req" . PHP_EOL, 3, LOG_FILE);
			}
	}

}

?>