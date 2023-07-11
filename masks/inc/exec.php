<?
require_once( 'functions.php' );


    switch ( $_GET[ 'act' ] ) {
        case 'add-to-cart':
			if($_POST['selected_mask_type']=='with_embroidery') {
			
				$_SESSION['cart_id']++;
				
				$cart_item = array();
				$cart_item['selected_mask_type']=sf($_POST['selected_mask_type']);
				$cart_item['embroidery_color']=sf($_POST['mask_embroidery_color']);
				$cart_item['size']=sf($_POST['mask_size']);
				$cart_item['qty']=sf($_POST['mask_qty']);
				$cart_item['color']='black';
				$cart_item['price']='27.00';
				$cart_item['total']=($_POST['mask_qty']*27.00); 
				$cart_item['desc']='Shredder mask (Black) with '.sf($_POST['mask_embroidery_color']).' embroidery - Size: '.sf($_POST['mask_size']).''; 
				
				$_SESSION['cart'][$_SESSION['cart_id']] = $cart_item;
				
			}
			
			if($_POST['selected_mask_type']=='without_embroidery') {
				$_SESSION['cart_id']++;
				
				$cart_item = array();
				$cart_item['selected_mask_type']=sf($_POST['selected_mask_type']);
				$cart_item['embroidery_color']='';
				$cart_item['size']=sf($_POST['mask_size']);
				$cart_item['qty']=sf($_POST['mask_qty']);
				$cart_item['color']='black';
				$cart_item['price']='22.00';
				$cart_item['total']=($_POST['mask_qty']*22.00); 
				$cart_item['desc']='Shredder mask (Black) without embroidery - Size: '.sf($_POST['mask_size']).''; 
				
				$_SESSION['cart'][$_SESSION['cart_id']] = $cart_item;
			}
			
			if($_POST['selected_mask_type']=='cb_with_embroidery') {
			
				$_SESSION['cart_id']++;
				
				$cart_item = array();
				$cart_item['selected_mask_type']=sf($_POST['selected_mask_type']);
				$cart_item['embroidery_color']=sf($_POST['mask_embroidery_color']);
				$cart_item['size']=sf($_POST['mask_size']);
				$cart_item['qty']=sf($_POST['mask_qty']);
				$cart_item['color']='Coyote Brown';
				$cart_item['price']='27.00';
				$cart_item['total']=($_POST['mask_qty']*27.00); 
				$cart_item['desc']='Shredder mask (Coyote Brown) with '.sf($_POST['mask_embroidery_color']).' embroidery - Size: '.sf($_POST['mask_size']).''; 
				
				$_SESSION['cart'][$_SESSION['cart_id']] = $cart_item;
				
			}
			
			if($_POST['selected_mask_type']=='cb_without_embroidery') {
				$_SESSION['cart_id']++;
				
				$cart_item = array();
				$cart_item['selected_mask_type']=sf($_POST['selected_mask_type']);
				$cart_item['embroidery_color']='';
				$cart_item['size']=sf($_POST['mask_size']);
				$cart_item['qty']=sf($_POST['mask_qty']);
				$cart_item['color']='Coyote Brown';
				$cart_item['price']='22.00';
				$cart_item['total']=($_POST['mask_qty']*22.00); 
				$cart_item['desc']='Shredder mask (Coyote Brown) without embroidery - Size: '.sf($_POST['mask_size']).''; 
				
				$_SESSION['cart'][$_SESSION['cart_id']] = $cart_item;
			}
			
			if($_POST['selected_mask_type']=='ulw_without_embroidery') {
				$_SESSION['cart_id']++;
				
				$cart_item = array();
				$cart_item['selected_mask_type']=sf($_POST['selected_mask_type']);
				$cart_item['embroidery_color']='';
				$cart_item['size']=sf($_POST['mask_size']);
				$cart_item['qty']=sf($_POST['mask_qty']);
				$cart_item['color']='black';
				$cart_item['price']='22.00';
				$cart_item['total']=($_POST['mask_qty']*22.00); 
				$cart_item['desc']='Shredder ULW mask without embroidery - Size: '.sf($_POST['mask_size']).''; 
				
				$_SESSION['cart'][$_SESSION['cart_id']] = $cart_item;
			}
			
			if($_POST['selected_mask_type']=='ulw_with_embroidery') {
			
				$_SESSION['cart_id']++;
				
				$cart_item = array();
				$cart_item['selected_mask_type']=sf($_POST['selected_mask_type']);
				$cart_item['embroidery_color']=sf($_POST['mask_embroidery_color']);
				$cart_item['size']=sf($_POST['mask_size']);
				$cart_item['qty']=sf($_POST['mask_qty']);
				$cart_item['color']='black';
				$cart_item['price']='27.00';
				$cart_item['total']=($_POST['mask_qty']*27.00); 
				$cart_item['desc']='Shredder ULW mask with '.sf($_POST['mask_embroidery_color']).' embroidery - Size: '.sf($_POST['mask_size']).''; 
				
				$_SESSION['cart'][$_SESSION['cart_id']] = $cart_item;
				
			}
			
			if($_POST['selected_mask_type']=='fr') {
			
				$_SESSION['cart_id']++;
				
				$cart_item = array();
				$cart_item['selected_mask_type']=sf($_POST['selected_mask_type']);
				$cart_item['embroidery_color']='';
				$cart_item['size']=sf($_POST['mask_size']);
				$cart_item['qty']=sf($_POST['mask_qty']);
				$cart_item['color']='black';
				$cart_item['price']='44.00';
				$cart_item['total']=($_POST['mask_qty']*44.00); 
				$cart_item['desc']='Shredder FR (Fire Retardant) - Size: '.sf($_POST['mask_size']).''; 
				
				$_SESSION['cart'][$_SESSION['cart_id']] = $cart_item;
				
			}
			
			
			echo 'document.location="'.root().'";';
            //print_r($_POST);
        exit();
        break;
		
		
		case 'add-to-cart-2':
		
			if($_POST['selected_mask_type']=='with_embroidery') {
			
				$_SESSION['cart_id']++;
				
				$cart_item = array();
				$cart_item['selected_mask_type']=sf($_POST['selected_mask_type']);
				$cart_item['size']=sf($_POST['mask_size']);
				$cart_item['qty']=sf($_POST['mask_qty']);
				$cart_item['color']='black';
				
				if($_POST['embroidery_type']=='pmi_logo') {
					$cart_item['embroidery_type']='pmi_logo';
					$price='27.00';
					$cart_item['embroidery_color']=sf($_POST['mask_embroidery_color']);
					$embroidery_text = $_POST['mask_embroidery_color'].' embroidery';
				} else {
					$cart_item['embroidery_type']='custom';
					$price='32.00';
					$cart_item['embroidery_color_1'] = sf($_POST['mask_custom_embroidery_color_1']);
					$cart_item['embroidery_color_2'] = sf($_POST['mask_custom_embroidery_color_2']);
					$cart_item['embroidery_color_3'] = sf($_POST['mask_custom_embroidery_color_3']);
					
					$colors = ' (Colors: '.sf($_POST['mask_custom_embroidery_color_1']).(!empty($_POST['mask_custom_embroidery_color_2']) ? ', '.sf($_POST['mask_custom_embroidery_color_2']) : '').(!empty($_POST['mask_custom_embroidery_color_3']) ? ', '.sf($_POST['mask_custom_embroidery_color_3']) : '').')';
					
					$cart_item['embroidery_file_name'] = sf($_FILES['embroidery_art']['name']);
					
					if(empty($_FILES['embroidery_art']['tmp_name'])) {
						echo 'alert(\'Please select a digitized embroidery file\');';
						exit();
					}
					
					mysqli_query($link, 'INSERT INTO mask_embroidery (file_name, color_1, color_2, color_3) VALUES (\''.sf($_FILES['embroidery_art']['name']).'\', \''.sf($_POST['mask_custom_embroidery_color_1']).'\', \''.sf($_POST['mask_custom_embroidery_color_2']).'\', \''.sf($_POST['mask_custom_embroidery_color_3']).'\')');
					
					$cart_item['embroidery_file_id'] = mysqli_insert_id($link);
					
					move_uploaded_file($_FILES['embroidery_art']['tmp_name'], '../uploads/embroidery/'.$cart_item['embroidery_file_id']);
					
					$embroidery_text = 'custom embroidery'.$colors;
				}
				
				$cart_item['price'] = $price;
				
				$cart_item['total']=($_POST['mask_qty'] * $price); 
				
				$cart_item['desc']='Shredder mask (Black) with '.sf($embroidery_text).' - Size: '.sf($_POST['mask_size']).''; 
				
				$_SESSION['cart'][$_SESSION['cart_id']] = $cart_item;
				
				
				
			}
			
			if($_POST['selected_mask_type']=='without_embroidery') {
				$_SESSION['cart_id']++;
				
				$cart_item = array();
				$cart_item['selected_mask_type']=sf($_POST['selected_mask_type']);
				$cart_item['embroidery_color']='';
				$cart_item['size']=sf($_POST['mask_size']);
				$cart_item['qty']=sf($_POST['mask_qty']);
				$cart_item['color']='black';
				$cart_item['price']='22.00';
				$cart_item['total']=($_POST['mask_qty']*22.00); 
				$cart_item['desc']='Shredder mask (Black) without embroidery - Size: '.sf($_POST['mask_size']).''; 
				
				$_SESSION['cart'][$_SESSION['cart_id']] = $cart_item;
			}
			
			if($_POST['selected_mask_type']=='cb_with_embroidery') {
			
				$_SESSION['cart_id']++;
				
				$cart_item = array();
				$cart_item['selected_mask_type']=sf($_POST['selected_mask_type']);
				//$cart_item['embroidery_color']=sf($_POST['mask_embroidery_color']);
				$cart_item['size']=sf($_POST['mask_size']);
				$cart_item['qty']=sf($_POST['mask_qty']);
				$cart_item['color']='Coyote Brown';
				
				if($_POST['embroidery_type']=='pmi_logo') {
					$cart_item['embroidery_type']='pmi_logo';
					$price='27.00';
					$cart_item['embroidery_color']=sf($_POST['mask_embroidery_color']);
					$embroidery_text = $_POST['mask_embroidery_color'].' embroidery';
				} else {
					$cart_item['embroidery_type']='custom';
					$price='32.00';
					$cart_item['embroidery_color_1'] = sf($_POST['mask_custom_embroidery_color_1']);
					$cart_item['embroidery_color_2'] = sf($_POST['mask_custom_embroidery_color_2']);
					$cart_item['embroidery_color_3'] = sf($_POST['mask_custom_embroidery_color_3']);
					
					$colors = ' (Colors: '.sf($_POST['mask_custom_embroidery_color_1']).(!empty($_POST['mask_custom_embroidery_color_2']) ? ', '.sf($_POST['mask_custom_embroidery_color_2']) : '').(!empty($_POST['mask_custom_embroidery_color_3']) ? ', '.sf($_POST['mask_custom_embroidery_color_3']) : '').')';
					
					$cart_item['embroidery_file_name'] = sf($_FILES['embroidery_art']['name']);
					
					if(empty($_FILES['embroidery_art']['tmp_name'])) {
						echo 'alert(\'Please select a digitized embroidery file\');';
						exit();
					}
					
					mysqli_query($link, 'INSERT INTO mask_embroidery (file_name, color_1, color_2, color_3) VALUES (\''.sf($_FILES['embroidery_art']['name']).'\', \''.sf($_POST['mask_custom_embroidery_color_1']).'\', \''.sf($_POST['mask_custom_embroidery_color_2']).'\', \''.sf($_POST['mask_custom_embroidery_color_3']).'\')');
					
					$cart_item['embroidery_file_id'] = mysqli_insert_id($link);
					
					move_uploaded_file($_FILES['embroidery_art']['tmp_name'], '../uploads/embroidery/'.$cart_item['embroidery_file_id']);
					
					$embroidery_text = 'custom embroidery'.$colors;
				}
				
				$cart_item['price'] = $price;
				
				$cart_item['total']=($_POST['mask_qty'] * $price); 
				
				$cart_item['desc']='Shredder mask (Coyote Brown) with '.sf($embroidery_text).' - Size: '.sf($_POST['mask_size']).''; 
				
				$_SESSION['cart'][$_SESSION['cart_id']] = $cart_item;
				
			}
			
			if($_POST['selected_mask_type']=='cb_without_embroidery') {
				$_SESSION['cart_id']++;
				
				$cart_item = array();
				$cart_item['selected_mask_type']=sf($_POST['selected_mask_type']);
				$cart_item['embroidery_color']='';
				$cart_item['size']=sf($_POST['mask_size']);
				$cart_item['qty']=sf($_POST['mask_qty']);
				$cart_item['color']='Coyote Brown';
				$cart_item['price']='22.00';
				$cart_item['total']=($_POST['mask_qty']*22.00); 
				$cart_item['desc']='Shredder mask (Coyote Brown) without embroidery - Size: '.sf($_POST['mask_size']).''; 
				
				$_SESSION['cart'][$_SESSION['cart_id']] = $cart_item;
			}
			
			if($_POST['selected_mask_type']=='ulw_without_embroidery') {
				$_SESSION['cart_id']++;
				
				$cart_item = array();
				$cart_item['selected_mask_type']=sf($_POST['selected_mask_type']);
				$cart_item['embroidery_color']='';
				$cart_item['size']=sf($_POST['mask_size']);
				$cart_item['qty']=sf($_POST['mask_qty']);
				$cart_item['color']='black';
				$cart_item['price']='22.00';
				$cart_item['total']=($_POST['mask_qty']*22.00); 
				$cart_item['desc']='Shredder ULW mask without embroidery - Size: '.sf($_POST['mask_size']).''; 
				
				$_SESSION['cart'][$_SESSION['cart_id']] = $cart_item;
			}
			
			if($_POST['selected_mask_type']=='ulw_with_embroidery') {
			
				$_SESSION['cart_id']++;
				
				$cart_item = array();
				$cart_item['selected_mask_type']=sf($_POST['selected_mask_type']);
				//$cart_item['embroidery_color']=sf($_POST['mask_embroidery_color']);
				$cart_item['size']=sf($_POST['mask_size']);
				$cart_item['qty']=sf($_POST['mask_qty']);
				$cart_item['color']='black';
				//$cart_item['price']='27.00';
				
				if($_POST['embroidery_type']=='pmi_logo') {
					$cart_item['embroidery_type']='pmi_logo';
					$price='27.00';
					$cart_item['embroidery_color']=sf($_POST['mask_embroidery_color']);
					$embroidery_text = $_POST['mask_embroidery_color'].' embroidery';
				} else {
					$cart_item['embroidery_type']='custom';
					$price='32.00';
					$cart_item['embroidery_color_1'] = sf($_POST['mask_custom_embroidery_color_1']);
					$cart_item['embroidery_color_2'] = sf($_POST['mask_custom_embroidery_color_2']);
					$cart_item['embroidery_color_3'] = sf($_POST['mask_custom_embroidery_color_3']);
					
					$colors = ' (Colors: '.sf($_POST['mask_custom_embroidery_color_1']).(!empty($_POST['mask_custom_embroidery_color_2']) ? ', '.sf($_POST['mask_custom_embroidery_color_2']) : '').(!empty($_POST['mask_custom_embroidery_color_3']) ? ', '.sf($_POST['mask_custom_embroidery_color_3']) : '').')';
					
					$cart_item['embroidery_file_name'] = sf($_FILES['embroidery_art']['name']);
					
					if(empty($_FILES['embroidery_art']['tmp_name'])) {
						echo 'alert(\'Please select a digitized embroidery file\');';
						exit();
					}
					
					mysqli_query($link, 'INSERT INTO mask_embroidery (file_name, color_1, color_2, color_3) VALUES (\''.sf($_FILES['embroidery_art']['name']).'\', \''.sf($_POST['mask_custom_embroidery_color_1']).'\', \''.sf($_POST['mask_custom_embroidery_color_2']).'\', \''.sf($_POST['mask_custom_embroidery_color_3']).'\')');
					
					$cart_item['embroidery_file_id'] = mysqli_insert_id($link);
					
					move_uploaded_file($_FILES['embroidery_art']['tmp_name'], '../uploads/embroidery/'.$cart_item['embroidery_file_id']);
					
					$embroidery_text = 'custom embroidery'.$colors;
				}
				
				$cart_item['price'] = $price;
				
				$cart_item['total']=($_POST['mask_qty'] * $price); 
				
				$cart_item['desc']='Shredder ULW mask with '.sf($embroidery_text).' - Size: '.sf($_POST['mask_size']).''; 
				
				$_SESSION['cart'][$_SESSION['cart_id']] = $cart_item;
				
			}
			
			if($_POST['selected_mask_type']=='fr') {
			
				$_SESSION['cart_id']++;
				
				$cart_item = array();
				$cart_item['selected_mask_type']=sf($_POST['selected_mask_type']);
				$cart_item['embroidery_color']='';
				$cart_item['size']=sf($_POST['mask_size']);
				$cart_item['qty']=sf($_POST['mask_qty']);
				$cart_item['color']='black';
				$cart_item['price']='44.00';
				$cart_item['total']=($_POST['mask_qty']*44.00); 
				$cart_item['desc']='Shredder FR (Fire Retardant) - Size: '.sf($_POST['mask_size']).''; 
				
				$_SESSION['cart'][$_SESSION['cart_id']] = $cart_item;
				
			}
			
			if($_POST['selected_mask_type']=='fl_black') {
			
				$_SESSION['cart_id']++;
				
				$cart_item = array();
				$cart_item['selected_mask_type']=sf($_POST['selected_mask_type']);
				$cart_item['embroidery_color']='';
				$cart_item['size']=sf($_POST['mask_size']);
				$cart_item['qty']=sf($_POST['mask_qty']);
				$cart_item['color']='black';
				$cart_item['price']='14.00';
				$cart_item['total']=($_POST['mask_qty']*14.00); 
				$cart_item['desc']='Black Shredder FL (Featherlite) - Size: '.sf($_POST['mask_size']).''; 
				
				$_SESSION['cart'][$_SESSION['cart_id']] = $cart_item;
				
			}
			
			if($_POST['selected_mask_type']=='fl_cb') {
			
				$_SESSION['cart_id']++;
				
				$cart_item = array();
				$cart_item['selected_mask_type']=sf($_POST['selected_mask_type']);
				$cart_item['embroidery_color']='';
				$cart_item['size']=sf($_POST['mask_size']);
				$cart_item['qty']=sf($_POST['mask_qty']);
				$cart_item['color']='Coyote Brown';
				$cart_item['price']='14.00';
				$cart_item['total']=($_POST['mask_qty']*14.00); 
				$cart_item['desc']='Coyote Brown Shredder FL (Featherlite) - Size: '.sf($_POST['mask_size']).''; 
				
				$_SESSION['cart'][$_SESSION['cart_id']] = $cart_item;
				
			}
			
			
			echo 'document.location="'.root().'";';
            //print_r($_POST);
        exit();
        break;
		

		case 'remove_from_cart':
			unset($_SESSION['cart'][sf($_GET['id'])]);
			header( 'Location: ' . root() );
	    exit();
		break;
		
		case 'checkout':
		
			$cart = $_SESSION['cart'];
			
			$order_confirmation_items = '';
			
			foreach ($cart as $item_id=>$item) {
				$total += $item['price']*$item['qty'];
			}
			
			
			if($_POST['payment_type']=='paypal') {
			
				mysqli_query($link, 'INSERT INTO mask_orders (`date`,`email`,`name`,`address_1`,`address_2`,`city`,`state`,`zip`,`phone`,`payment_method`,`transaction_id`,`order_total`, `order_data`, `payment_status`) VALUES (NOW(),\''.sf($_POST['email']).'\',\''.sf($_POST['name']).'\',\''.sf($_POST['address']).'\',\''.sf($_POST['address_2']).'\',\''.sf($_POST['city']).'\',\''.sf($_POST['state']).'\',\''.sf($_POST['zip']).'\',\''.sf($_POST['phone']).'\',\'PayPal\',\''.sf($return['id']).'\',\''.$total.'\', \''.sf(json_encode($_SESSION['cart'])).'\', \'UNPAID\')');
			
				$order_id = mysqli_insert_id($link);
				
				foreach ($cart as $item_id=>$item) {
					$order_confirmation_items .= $item['qty'].' x '.$item['desc'].' - $'.number_format(($item['price']*$item['qty']), 2, '.', '')."\n";
					mysqli_query($link, 'INSERT INTO mask_order_items (`order_id`, `description`, `type`, `color`, `embroidery_color`, `size`, `qty`, `embroidery_type`, `embroidery_file`) VALUES (\''.$order_id.'\', \''.sf($item['desc']).'\', \''.sf($item['selected_mask_type']).'\', \''.sf($item['color']).'\', \''.sf($item['embroidery_color']).'\', \''.sf($item['size']).'\', \''.sf($item['qty']).'\', \''.$item['embroidery_type'].'\', \''.$item['embroidery_file_id'].'\')');
					
				}
				
				$_SESSION['order_id'] = $order_id;
				
				
				
				echo 'document.location="https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&business=admin@peregrinemfginc.com&item_name='.urlencode('Mask Order #'.$order_id).'&item_number=1&amount='.$total.'&no_shipping=1&return='.urlencode(root().'confirmation/?id='.$order_id.'&paypal=1').'&currency_code=USD&notify_url='.urlencode(root().'/paypal/?order='.$order_id).'"';
				
				exit();
			
			}
			
			
			if($_POST['payment_type']=='cc') {
				
				$post = array('amount'=>($total*100), 'currency'=>'usd', 'description'=>$description, 'card[number]'=>$_POST['cc_number'], 'card[exp_month]'=>$_POST['cc_exp_month'], 'card[exp_year]'=>$_POST['cc_exp_year'],'card[cvc]'=>$_POST['cc_cvv']);
	
					$ch = curl_init();
					
					curl_setopt($ch, CURLOPT_URL, 'https://api.stripe.com/v1/charges');
					curl_setopt($ch, CURLOPT_POST, 1);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
					curl_setopt($ch, CURLOPT_USERPWD, 'sk_live_y8al1OxEfzACqZgWbHYKarWL00WxZRNC0j:');
					//curl_setopt($ch, CURLOPT_USERPWD, 'sk_test_XwwkXsnL5IrBLBkIPcQKbycX00teGXfPQ4:');
					curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
					$output = curl_exec($ch);

					curl_close($ch);
					
					$return = json_decode($output,true);
					
					
										
					if(is_array($return['error'])) {
						echo '$("#card_error").show(); $("#card_error").html("Your card was declined. Please check the information and try again."); $("#complete_order_button").text("Complete Order"); $("#complete_order_button").prop("disabled", true);';
						exit();
					} else {
						//create order
						
						mysqli_query($link, 'INSERT INTO mask_orders (`date`,`email`,`name`,`address_1`,`address_2`,`city`,`state`,`zip`,`phone`,`payment_method`,`transaction_id`,`order_total`, `order_data`, `payment_status`, `order_status`) VALUES (NOW(),\''.sf($_POST['email']).'\',\''.sf($_POST['name']).'\',\''.sf($_POST['address']).'\',\''.sf($_POST['address_2']).'\',\''.sf($_POST['city']).'\',\''.sf($_POST['state']).'\',\''.sf($_POST['zip']).'\',\''.sf($_POST['phone']).'\',\'Stripe\',\''.sf($return['id']).'\',\''.$total.'\', \''.sf(json_encode($_SESSION['cart'])).'\', \'PAID\', \'PAID\')');
			
						$order_id = mysqli_insert_id($link);
						
						foreach ($cart as $item_id=>$item) {
							$order_confirmation_items .= $item['qty'].' x '.$item['desc'].' - $'.number_format(($item['price']*$item['qty']), 2, '.', '')."\n";
							mysqli_query($link, 'INSERT INTO mask_order_items (`order_id`, `description`, `type`, `color`, `embroidery_color`, `size`, `qty`, `embroidery_type`, `embroidery_file`) VALUES (\''.$order_id.'\', \''.sf($item['desc']).'\', \''.sf($item['selected_mask_type']).'\', \''.sf($item['color']).'\', \''.sf($item['embroidery_color']).'\', \''.sf($item['size']).'\', \''.sf($item['qty']).'\', \''.$item['embroidery_type'].'\', \''.$item['embroidery_file_id'].'\')');
							
							
						}
						
						$_SESSION['order_id'] = $order_id;
									
						// email order info
						
						$message = sf($_POST['name'])."\n\n";
						$message .= 'Thank for for placing an order for a Peregrine Manufacturing masks. Below is a confirmation of your order.'."\n\n";
						$message .= $order_confirmation_items;
						$message .= "\nTotal: $".number_format($total,2,'.','')."\n\n";
						$message .= "Shipping Address:\n";
						$message .= sf($_POST['name'])."\n";
						$message .= sf($_POST['address'])."\n";
						if($_POST['address_2']) $message .= sf($_POST['address_2'])."\n";
						$message .= sf($_POST['city']).", ".sf($_POST['state'])." ".sf($_POST['zip'])."\n\n";
						$message .= "If you have any questions, please email us at pmisales@peregrinemfginc.com.\n\n";
						$message .= "Thank You\n\n";
						$message .= "Peregrine Manufacturing, Inc.";
						
						smtpemail(sf($_POST['email']),'Your Peregrine Manufacturing, Inc. Mask Order',$message);
						smtpemail('admin@peregrinemfginc.com','Your Peregrine Manufacturing, Inc. Mask Order',$message);
						
						echo 'document.location="'.root().'confirmation/?id='.$order_id.'";';
					}
					
					
			}
			//header( 'Location: ' . root() );
	    exit();
		break;

        default:
			echo 'error';
			//header( 'Location: ' . root() );
            exit();
            break;
    }

?>