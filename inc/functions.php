<?
date_default_timezone_set('America/New_York');

error_reporting(E_ALL ^ E_NOTICE);
ini_set('display_errors', 1);

session_start();

$link=mysqli_connect('localhost','root','','projects_pgmanage4');


$mng = 'peregrinemanage';
$dev = 'pmimanage_dev';
$des = 'design.peregrinemfginc.com/';

function root($var='', $m='') {
	$dom = '127.0.0.1';
	$fol = 'pgmanage/';
	$m = 's';
	if ($var=='dom') {
		return $dom;
	} else {
		return 'https://'.$dom.'/'.$fol.$var;
	}
}


function num_only($var, $ex=',.') {
	return preg_replace('/[^0-9'.$ex.']/', '', $var);
}

function word_cleanup($var){
    $pat = "/<(\w+)>(\s|&nbsp;)*<\/\1>/";
    $var = preg_replace($pat, '', $var);
    return mb_convert_encoding($var, 'HTML-ENTITIES', 'UTF-8');
}

function sf($var) {
	return make_safe($var);
}

function make_safe($var, $safe='') {
	$var = word_cleanup($var);
	if (isset($safe) && !empty($safe)) {
		$var = strip_tags($var, $safe);
	}
	$var = addslashes(trim($var));
	return $var;
}

function save_posts($posts) {
	foreach ($posts as $post) {
		if (isset($_POST[$post])) {
			$_SESSION[$post] = $_POST[$post];
		}
	}
}

function unset_sess($sessions) {
	foreach ($sessions as $session) {
		if (isset($_SESSION[$session])) {
			unset($_SESSION[$session]);
		}
	}
}

function make_hash($var,$salt='l3sl1e') {
	$hash = crypt($var, $salt.time());
	$hash = str_replace('.', '-', $hash);
	$hash = str_replace('/', '_', $hash);
	return strrev($hash);
}

function blankCheck($vars) {
	$x = 0;
	$c = count($vars);

	for ($i = 0; $i < $c; ++$i) {
		if (!empty($_POST[$vars[$i]])) {
			++$x;
		}
	}
	
	if ($x==$c) {
		return true;
	} else {
		return false;
	}
}

function blankResp($var, $blank='') {
	$var = trim($var);
	if (!empty($var)) {
		return $var;
	} else {
		return $blank;
	}
}

function parseYT ($link) {
	parse_str(parse_url($link, PHP_URL_QUERY));
	return $v;
}

function formatURL($string){  
    return trim(preg_replace('/[-]{2,}/', '-', preg_replace('/[^a-z0-9]+/', '-', strtolower($string))), '-');  
}

function formatTel($phone) {
	$numbers_only = preg_replace('/[^\d]/', '', $phone);
	return preg_replace('/^1?(\d{3})(\d{3})(\d{4})$/', '$1-$2-$3', $numbers_only);
}

function chunkArray($list,$p) {
    $listlen = count($list);
    $partlen = floor($listlen / $p);
    $partrem = $listlen % $p;
    $partition = array();
    $mark = 0;
    for ($px = 0; $px < $p; $px++) {
        $incr = ($px < $partrem) ? $partlen + 1 : $partlen;
        $partition[$px] = array_slice( $list, $mark, $incr );
        $mark += $incr;
    }
    return $partition;
}

function genSKU($string,$id=NULL,$l=2){
    $results = '';
    $vowels = array('a', 'e', 'i', 'o', 'u', 'y');
    preg_match_all('/[A-Z][a-z]*/', ucfirst($string), $m);
    foreach($m[0] as $substring){
        $substring = str_replace($vowels, '', strtolower($substring));
        $results .= preg_replace('/([a-z]{'.$l.'})(.*)/', '$1', $substring);
    }
    $results .= '-'. str_pad($id, 4, 0, STR_PAD_LEFT);
    return $results;
}

function nameCheck($var) {
	$bad = array('admin', 'administrator', 'dev', 'developer');
	$chk  = '/^[a-zA-Z0-9_.-]{1,20}$/';
	if (preg_match($chk, $var)) {
		if (in_array(strtolower($var), $bad)) {
			return false;
		} else {
			return true;
		}
	} else {
		return false;
	}
}

function fixLink($str) {   
	preg_match_all('/<(a)\s([^>]+)>/is', $str, $tags);
	$count = count($tags[2]);
	for ($i = 0; $i < $count; ++$i){
		$strlen = strlen($tags[2][$i]); //length
		$hrefst = stripos($tags[2][$i], 'href="'); //Start
		$hrefsr = substr($tags[2][$i], strlen('href="') + $hrefst, $strlen);
		$hrefed = stripos($hrefsr, '"'); // End
		$str = str_replace(' '.$tags[2][$i], ' href="'.substr($hrefsr, 0, $hrefed).'" target="_blank" rel="nofollow"', $str);
	}
	return $str;
	
}

function fixAtts($str){
	preg_match_all('/<([^\/]\w*)\s([^\/>]+)>/is', $str, $tags);
	$count = count($tags[2]);
	for ($i = 0; $i < $count; ++$i){
		if ($tags[1][$i]=='a') {
			$strlen = strlen($tags[2][$i]); //length
			$hrefst = stripos($tags[2][$i], 'href="'); //Start
			$hrefsr = substr($tags[2][$i], strlen('href="') + $hrefst, $strlen);
			$hrefed = stripos($hrefsr, '"'); // End
			if (strpos(substr($hrefsr, 0, $hrefed),'javascript:') !== false) {
				$str = str_replace(' '.$tags[2][$i], ' href="#" rel="nofollow"', $str);
			} elseif (validURL(substr($hrefsr, 0, $hrefed))===false) {
				$str = str_replace(' '.$tags[2][$i], ' href="#" rel="nofollow"', $str);
			} else {
				$str = str_replace(' '.$tags[2][$i], ' href="'.substr($hrefsr, 0, $hrefed).'" target="_blank" rel="nofollow"', $str);	
			}
		} elseif ($tags[1][$i]=='font') {
			$strlen = strlen($tags[2][$i]); //length
			$hrefst = stripos($tags[2][$i], 'color="'); //Start
			$hrefsr = substr($tags[2][$i], strlen('color="') + $hrefst, $strlen);
			$hrefed = stripos($hrefsr, '"'); // End
			$str = str_replace(' '.$tags[2][$i], ' color="'.substr($hrefsr, 0, $hrefed).'"', $str);	
		} else {
			$str = str_replace(' '.$tags[2][$i], '', $str);
		}

	}
	return $str;
}

function fixTags($unclosedString){
	preg_match_all('/<([^\/^img^br]\w*)([^>]+)?>/is', $closedString = $unclosedString, $tags);
	$count = count($tags[0])-1;
	for ($i = $count; $i >= 0; --$i){ 
		if (substr_count($closedString, '</'.$tags[1][$i].'>') < substr_count($closedString, '<'.$tags[1][$i].''.$tags[2][$i].'>')) {
			$closedString .= '</'.$tags[1][$i].'>';
		}
	}

	return $closedString;
}

function fixHTML($str) {
	return fixTags(fixAtts(fixLink($str)));
}

function followURL($url) {
	$curl = curl_init($url);
	curl_setopt_array($curl, array(
			CURLOPT_RETURNTRANSFER  => true,
			CURLOPT_FOLLOWLOCATION  => true,
		));
	 
	$result = curl_exec($curl);
	 
	if ($result === false) {
		curl_close($curl);
		return null;
	}
	
	$trueurl = curl_getinfo($curl, CURLINFO_EFFECTIVE_URL);
	curl_close($curl);
	return $trueurl;
}

function validURL($url) {
	if (filter_var($url, FILTER_VALIDATE_URL)) {
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_NOBODY, true);
		$result = curl_exec($curl);
		if ($result !== false) {
			$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
			if ($statusCode == 404) {
				return false;
			} else {
				return true;
			}
		} else {
			return false;
		}
	} else {
		return false;
	}
}

function any_uploads($name) {
	foreach ($_FILES[$name]['error'] as $ferror) {
		if ($ferror != UPLOAD_ERR_NO_FILE) {
			return true;
		}
	}
	return false;
}

function is_dir_empty($dir) {
	if (!is_readable($dir)) return null; 
	$handle = opendir($dir);
	while (false !== ($entry = readdir($handle))) {
		if ($entry !== '.' && $entry !== '..') {
			return false;
		}
	}
	closedir($handle);
	return true;
}

function readChunked($filename,$retbytes=true) {
	$chunksize = 1*(1024*1024);
	$chunk = NULL;
	$cnt = 0;
	$handle = fopen($filename, 'rb');
	if ($handle === false) {
		return false;
	}
	while (!feof($handle)) {
		$chunk = fread($handle, $chunksize);
		echo $chunk;
		ob_flush();
		flush();
		if ($retbytes) {
			$cnt += strlen($chunk);
		}
	}
	$status = fclose($handle);
	if ($retbytes && $status) {
		return $cnt;
	}
	return $status;
}

function getAge($birthdate){
	list ($year, $month, $day) = explode('-', $birthdate);
	$diff     = date('Y') - $year;
	$birthday = date('Y').$month.$day;
	$date     = date('Ymd');
	$leap     = (date('d') + date('L'));
	if (($birthday > $date) || (($birthday > $date) && ($leap < $day))){
		--$diff;
	}
	return $diff;
}

function state($var='') {
	$state = array('Alabama', 'Alaska', 'Arizona', 'Arkansas', 'California', 'Colorado', 'Connecticut', 'Delaware', 'District of Columbia', 'Florida', 'Georgia', 'Hawaii', 'Idaho', 'Illinois', 'Indiana', 'Iowa', 'Kansas', 'Kentucky', 'Louisiana', 'Maine', 'Maryland', 'Massachusetts', 'Michigan', 'Minnesota', 'Mississippi', 'Missouri', 'Montana', 'Nebraska', 'Nevada', 'New Hampshire', 'New Jersey', 'New Mexico', 'New York', 'North Carolina', 'North Dakota', 'Ohio', 'Oklahoma', 'Oregon', 'Pennsylvania', 'Rhode Island', 'South Carolina', 'South Dakota', 'Tennessee', 'Texas', 'Utah', 'Vermont', 'Virginia', 'Washington', 'West Virginia', 'Wisconsin', 'Wyoming');
	if ($var=='') {
		return count($state);
	} else {
		return $state[$var];
	}
}

function month($num) {
	$month = array('','January','February','March','April','May','June','July','August','September','October','November','December');
	return $month[$num];
}

function scaleimage($input, $height, $width, $output, $unlink, $outputtype = 'jpeg') {
		if($input['tmp_name']) {
			$extention = explode('.',$input['name']);
			$ext = strtolower($extention[count($extention)-1]);
			//$original = '../images/gallery_thumbs/O_'.$id.'.jpg';
			//$output = '../images/gallery_thumbs/'.$id.'.jpg';
			$maxh = $height;
			$maxw = $width;
			$mode = '0666';
			$original = $input['tmp_name'];
			//move_uploaded_file($input['tmp_name'], $original);
			//chmod ($original, octdec($mode));
			$sizes = getimagesize($original);
			$aspect_ratio = $sizes[0]/$sizes[1];
	
				if ($sizes[0] <= $maxw) { 
					$new_width = $sizes[0]; 
					$new_height = $sizes[1]; 
				} else { 
					$new_width = $maxw;
					$new_height = abs($new_width/$aspect_ratio); 
				}  
				
				if($new_height >= $maxh) { 
					$new_width = abs($maxh*($new_width/$new_height));
					$new_height = $maxh;
				}
			
				
			$destimg = imagecreatetruecolor($new_width,$new_height) or die('Problem In Creating image'); 
		
			if ($ext=='png') {
				$image=true;
				$srcimg = imagecreatefrompng($original) or die('Problem In opening Source Image'); 
				
				imagealphablending($destimg, false);
  				imagesavealpha($destimg,true);

				imagecopyresampled($destimg,$srcimg,0,0,0,0,$new_width,$new_height,imagesx($srcimg),imagesy($srcimg)) or die('Problem In resizing');
				if($outputtype=='jpeg') {
				imagejpeg($destimg,$output,100) or die('Problem In saving'); 
				} else {
				imagepng($destimg,$output) or die('Problem In saving'); 
				}
			} elseif($ext=='gif') {
				$image=true;
				$destimg = imagecreate($new_width,$new_height) or die('Problem In Creating image'); 
				$srcimg = imagecreatefromgif($original) or die('Problem In opening Source Image'); 
				
				imagealphablending($destimg, false);
  				imagesavealpha($destimg,true);
				
				imagecopyresampled($destimg,$srcimg,0,0,0,0,$new_width,$new_height,imagesx($srcimg),imagesy($srcimg)) or die('Problem In resizing');
				if($outputtype=='jpeg') {
				imagejpeg($destimg,$output,100) or die('Problem In saving'); 
				} else {
				imagepng($destimg,$output) or die('Problem In saving'); 
				} 
			} elseif($ext=='jpg' || $ext == 'jpeg') {
				$image=true;
				$srcimg = imagecreatefromjpeg($original) or die('Problem In opening Source Image'); 
				imagecopyresampled($destimg,$srcimg,0,0,0,0,$new_width,$new_height,imagesx($srcimg),imagesy($srcimg)) or die('Problem In resizing');
				if($outputtype=='jpeg') {
				imagejpeg($destimg,$output,100) or die('Problem In saving'); 
				} else {
				imagepng($destimg,$output) or die('Problem In saving'); 
				}
			}
			
			imagedestroy($destimg);
			imagedestroy($srcimg);
			if($unlink==1) {
			unlink($input['tmp_name']);
			}
		}
}


function show_step_images($project, $step) {
	global $link;
	$images = mysqli_query($link, 'SELECT * FROM images WHERE project=\''.sf($project).'\' AND step=\''.sf($step).'\'');
	$html = '';
	
	$i = 0;
	while($img = mysqli_fetch_assoc($images)) {

		$html .= '<div class="col-sm-2" id="image_'.$img['id'].'"><a href="'.root().'media/images/'.$img['id'].'.png" data-toggle="lightbox" data-gallery="multiimages" data-title="'.$img['name'].'"><img src="'.root().'media/images/'.$img['id'].'.png" class="img-responsive"></a><p class="text-center">[<a href="javascript:;" onclick="remove_image('.$img['id'].');">remove</a>]</p></div>';
		
		$i++;
		
		if($i == 6) {
			$i = 0;
			$html .=  '<div class="clear"></div>';
		}

	}
	
	return $html;
}

function check_access($loc=null) {
	if($loc=='production') {
		if($_SESSION['type']=='admin' || $_SESSION['type']=='inspector' || $_SESSION['access_production']==1) {
			return true;
		}
	}
	
	if($loc=='demos') {
		if($_SESSION['type']=='admin' || $_SESSION['type']=='inspector' || $_SESSION['access_demos']==1) {
			return true;
		}
	}
	
	//default admin only
	if($_SESSION['type']=='admin') return true;
	if($_SESSION['type']=='inspector') return true;
	
	return false;
}


function uni_id() {
	if(!is_integer($_SESSION['u_var'])) {
		$_SESSION['u_var'] = time();
	} else {
		$_SESSION['u_var']++;
	}
	
	return $_SESSION['u_var'];
	
}

function get_next_batch_lot($vars) 
{	
	global $link;
	$batch_lot = '';
	
	foreach($vars as $key=>$val) 
   {
      //if($val['name']=='Material' && !empty($val['value'])) {
        //$material = $val['value'];
      //}
      
      //if($val['name']=='Color' && !empty($val['value'])) {
      //	$color = $val['value'];
      //}
      //if(preg_match('/color/i', $val['name']))
      //{
          //$color = $val['value'];
      //}
      
      if($val['name'] == 'Material'){ $material = $val['value']; }
      if (strpos($val['name'], 'Color') !== false) { $color = strtoupper($val['value']); }
      
        if(!empty($color) && !empty($material)) 
        {
            $sql = 'SELECT lot_number FROM batch_lots WHERE archived=\'0\' AND material=\''.sf($material).'\' AND color=UPPER(\''.sf($color).'\') AND lot_number != \'\'';
    		$q = mysqli_query($link, $sql);
    		
    		if(mysqli_num_rows($q)>0) 
    		{	
    			$result = mysqli_fetch_assoc($q);	
    			$batch_lot = $result['lot_number'];
    		}
    	}
    	
	}
	
	return $batch_lot;
}


function time_event($step, $event='stop', $substep='', $type='') {
	global $link;
	
	$_SESSION['timer_running']='';
	$_SESSION['timer_type'] = sf($type);
	// close any other open timers
	mysqli_query($link, 'UPDATE timer_events SET `stop`=\''.time().'\', `total_time`=('.time().' - start) WHERE `user`=\''.sf($_SESSION['uid']).'\' AND `stop`=\'\'');
		
	
	if($event=='start') {
	
		$_SESSION['timer_running']=sf($step);
		
		mysqli_query($link, 'INSERT INTO timer_events (step, substep, user, type, start) VALUES (\''.sf($step).'\', \''.sf($substep).'\', \''.sf($_SESSION['uid']).'\', \''.sf($type).'\', \''.time().'\')');
		
		$_SESSION['timer_epoch']=time();
		
	} else {
		$_SESSION['timer_epoch']='';
	}
	
}

function get_step_time($step) {

	global $link;
	
	$q = mysqli_query($link, 'SELECT (SELECT SUM(total_time) as total FROM timer_events WHERE `user`=\''.sf($_SESSION['uid']).'\' AND step=\''.sf($step).'\') as my_time, (SELECT SUM(total_time) as total FROM timer_events WHERE step=\''.sf($step).'\') as total_time, (SELECT SUM(total_time) as total FROM timer_events WHERE step=\''.sf($step).'\' AND type=\'rework\') as rework_time');
	
	$t = mysqli_fetch_assoc($q);
	
	if($t['my_time']=='') $t['my_time'] = 0;
	
	if($t['total_time']=='') $t['total_time'] = 0;
	
	if(!empty($_SESSION['timer_running']) && $_SESSION['timer_running']==$step) $t['my_time'] += time() - $_SESSION['timer_epoch'];
	
	return array('my_time'=>$t['my_time'], 'total_time'=>$t['total_time'], 'rework_time'=>$t['rework_time']);
}

function convert_final_design($final_design, $order_id)
{
    $output_file = 'final_design_'.$order_id.".svg";
    $output_folder = $_SERVER['DOCUMENT_ROOT'].'/peregrinemanage/zip/Glide_order_'.$order_id.'/'.$output_file;

    base64_to_jpeg($final_design, $output_folder);
}

function base64_to_jpeg($base64_string, $output_file) 
{
    $dir = dirname($output_file);
    
    if(!file_exists($dir))
    {
        mkdir($dir, 0770);    
    }
    
    // open the output file for writing
    $ifp = fopen( $output_file, 'wb' ); 

    // split the string on commas
    // $data[ 0 ] == "data:image/png;base64"
    // $data[ 1 ] == <actual base64 string>
    $data = explode( ',', $base64_string );

    // we could add validation here with ensuring count( $data ) > 1
    fwrite( $ifp, base64_decode( $data[ 1 ] ) );

    // clean up the file resource
    fclose( $ifp ); 

    return $output_file; 
}

function svg($raw, $svg, $dir, $name)
{
    $svg = file_get_contents($svg);
    
    $output_file = $dir.'/'.$name;
    $dir = dirname($output_file);
    
    if(!file_exists($dir))
    {
        mkdir($dir, 0770);    
    }
    
  $ifp = fopen( $output_file, 'wb' ); 
  $data = explode( ',', $raw);
  $image=base64_decode($data[ 1 ]);
  $im = new Imagick();
  $im->readimageblob($image);
  $im->setImageFormat("pdf");
  $im->writeImages($name, true);
  $output = $im->getimageblob();
  $outputtype = $im->getFormat();
  //header("Content-type: $outputtype");
    fwrite( $ifp, $output);
    fclose( $ifp ); 

    return $output_file; 
    //echo $svg;
}

function api_serial_active($id){
    global $link;
    
    $q = 'SELECT * FROM projects WHERE peregrine_id=\''.sf($id).'\' AND status!=\'deleted\'';
    $projects = mysqli_query($link, $q);
    
    return $project['serial'];
}

function api_serial(){
    global $link;
    
    /*$check_sn = mysqli_query($link, 'SELECT * FROM projects WHERE serial=2794');
    if(mysqli_num_rows($check_sn) < 1)
    {
        $sn = '2794';
    }
    else
    {
        $data = mysqli_fetch_assoc(mysqli_query($link, 'SELECT * FROM projects 
                                                                    WHERE product=89 AND serial >= 2794 
                                                                    ORDER BY serial DESC LIMIT 1'));
                $sn = $data['serial']+1;
    }*/
     $get_sn=array();
                $check_sn = mysqli_query($link, 'SELECT * FROM projects WHERE serial=\''.sf(2799).'\'');
                        if(mysqli_num_rows($check_sn) < 1)
                        {
                                $sn = '2799';
                        }
                        else
                        {
                            //$data = mysqli_fetch_assoc(mysqli_query($link,"SELECT projects.*, (SELECT product_steps.name FROM project_steps, product_steps WHERE project_steps.project=projects.id AND project_steps.status='Completed' AND project_steps.step = product_steps.id ORDER BY product_steps.order DESC LIMIT 1) as last_status, customers.name as customer_name, products.name as product_name FROM projects LEFT JOIN customers ON projects.customer = customers.id, products WHERE projects.product = products.id AND projects.status='started' ORDER BY serial DESC LIMIT 1"));
                            //$last = $data['serial'];
                            //for($i=2799;$i<=$last;$i++){
                            //    $query = 'SELECT * FROM projects WHERE serial='.$i.' AND status ="deleted"';
                            //    $new_sn = mysqli_query($link,$query);
                            //    if(mysqli_num_rows($new_sn) >0) //if exist
                            //    {
                            //        $sn = $i;
                            //    }else{
                            //        $sn = $last+1;        
                            //    }
                            //    $get_sn[]=$sn;
                            //}
                            
                            /*$query = "SELECT projects.*
                                        	, (SELECT product_steps.name
                                             	FROM project_steps, product_steps 
                                             	WHERE 
                                             		project_steps.project=projects.id AND 
                                             		project_steps.status='Completed' AND 
                                             		project_steps.step = product_steps.id 
                                             		ORDER BY product_steps.order DESC LIMIT 1) as last_status
                                            , customers.name as customer_name
                                            , products.name as product_name 
                                            FROM projects 
                                            LEFT JOIN customers ON projects.customer = customers.id, products 
                                            WHERE 
                                            	projects.product = products.id AND 
                                                products.name LIKE 'IA-AC100%' AND 
                                                char_length(serial) = 4 AND 
                                                serial >= 2794 AND 
                                                status != 'deleted'
                                            ORDER BY serial DESC LIMIT 10";*/
                            $query = "SELECT projects.*
                                        	, (SELECT product_steps.name
                                             	FROM project_steps, product_steps 
                                             	WHERE 
                                             		project_steps.project=projects.id AND 
                                             		project_steps.status='Completed' AND 
                                             		project_steps.step = product_steps.id 
                                             		ORDER BY product_steps.order DESC LIMIT 1) as last_status
                                            , customers.name as customer_name
                                            , products.name as product_name 
                                            FROM projects 
                                            LEFT JOIN customers ON projects.customer = customers.id, products 
                                            WHERE 
                                            	projects.product = products.id AND 
                                                char_length(serial) = 4 AND 
                                                serial >= 2794 AND 
                                                status != 'deleted' AND
                                                not_used_sn != '1'
                                            ORDER BY serial DESC LIMIT 10";
                            $result = mysqli_query($link, $query);
                            
                            $get_sn = array();
                            
                            while($output = mysqli_fetch_assoc($result))
                            {
                                $get_sn[] = $output['serial'];
                            }
                        }
                        
                        if(!empty($get_sn))
                        {
                            //$order_data['Serial_Number'] = min($get_sn);
                            return chrono_trigger($get_sn);
                        } 
                        else 
                        {
                            return $sn;
                        }
}

function api_product(){
    global $link;
    
    $sn = mysqli_query($link, 'SELECT id,name FROM products');
    while($row = mysqli_fetch_assoc($sn)) {
	    $product[] = array('id'=>$row['id'], 'name'=>$row['name']);
    }
    return $product;
}

function api_check_project($id){
    global $link;
    
    $sn = mysqli_query($link, 'SELECT * FROM projects 
                                          WHERE peregrine_id=\''.sf($id).'\' AND status!=\'deleted\'');
    if(mysqli_num_rows($sn) > 0){
        while($row = mysqli_fetch_assoc($sn)) {
    	    $project[] = array('project_id'=>$row['id'], 'product_id' => $row['product']);
        }
    }else{
        $project[] = array('project_id'=>0,'product_id'=>0);
    }
    return $project;
}


function chrono_trigger($array) {
    sort($array, SORT_NUMERIC);
    $prev = False;
    foreach($array as $num) {
        if($prev === False) {
            $prev = $num;
            continue;
        }
        if($prev != ($num-1)) {
            return $prev+1;
        }
        $prev = $num;
    }
    return False;
}


function edit_project($pere_id){
    global $link;
    
    $sn = mysqli_query($link, 'SELECT `customer`, `product`, `name`, `location`, `serial`, `payment`, `colors`, `notes`, `estimated_completion`, `priority`, `pod`, `metadata`, `global_vars`, `peregrine_date_modified`,`status` FROM projects WHERE peregrine_id=\''.sf($pere_id).'\' ORDER BY id DESC LIMIT 1');

    while($row = mysqli_fetch_assoc($sn)) {
        $row['global_vars'] = json_decode($row['global_vars']);
	    foreach($row['global_vars'] as $vars)
        {
            //echo "<pre>";
            //print_r($vars);
            //echo "</pre>";

            if($vars->name == "Customer"){
                $cust_name      = $vars->vars->{'Name'};
                $cust_height    = $vars->vars->{'Height'};
                $cust_weight    = $vars->vars->{'Weight'};
                $cust_chest     = $vars->vars->{'Chest'};
                $cust_torso     = $vars->vars->{'Torso'};
                $cust_waist     = $vars->vars->{'Waist'};
                $cust_thigh     = $vars->vars->{'Thigh'};
                $cust_inseam    = $vars->vars->{'Inseam'};
                $cust_cup       = $vars->vars->{'Cup'};
                $cust_gender    = $vars->vars->{'Gender'};
                
            }else{
                if(isset($vars->vars->{'Name'}))
                        $cust_name = $vars->vars->{'Name'};
                if(isset($vars->vars->{'Height'}))
                        $cust_height = $vars->vars->{'Height'};
                if(isset($vars->vars->{'Weight'}))
                        $cust_weight = $vars->vars->{'Weight'};
                if(isset($vars->vars->{'Chest'}))
                        $cust_chest = $vars->vars->{'Chest'};
                if(isset($vars->vars->{'Torso'}))
                        $cust_torso = $vars->vars->{'Torso'};
                if(isset($vars->vars->{'Waist'}))
                        $cust_waist = $vars->vars->{'Waist'};
                if(isset($vars->vars->{'Thigh'}))
                        $cust_thigh = $vars->vars->{'Thigh'};
                if(isset($vars->vars->{'Inseam'}))
                        $cust_inseam = $vars->vars->{'Inseam'};
                if(isset($vars->vars->{'Cup'}))
                        $cust_cup = $vars->vars->{'Cup'};
                if(isset($vars->vars->{'Gender'}))
                        $cust_gender = $vars->vars->{'Gender'};
            }
            
             if($vars->name == "Harness/Container"){
                $container_size       = $vars->vars->{'Container Size'};
                $yoke_size            = $vars->vars->{'Yoke Size'};
                $mlw_configuration    = $vars->vars->{'MLW Configuration'};
                $mlw_sze              = $vars->vars->{'MLW Size'};
                $offset               = $vars->vars->{'Offset'};
                $leg_pad_size         = $vars->vars->{'Leg Pad Size'};
                $lateral_size         = $vars->vars->{'Lateral Size'};
                $hardware_type        = $vars->vars->{'Hardware Type'};
                $webbing_color        = $vars->vars->{'Webbing Color'};
                $chest_strap_type     = $vars->vars->{'Chest Strap Type'};
                $base_ring_size       = $vars->vars->{'Base Ring Size'};
                $thread_color         = $vars->vars->{'Thread Color'};
                $pinstripe_color      = $vars->vars->{'Pinstripe Color'};
                $main_canopy_size     = $vars->vars->{'Main Canopy Size'};
                $reserve_canopy_size  = $vars->vars->{'Reserve Canopy Size'};
                $rsl                  = $vars->vars->{'RSL'};
                $chest_strap_size     = $vars->vars->{'Chest Strap Size'};
                $fabric_type_cb       = $vars->vars->{'Fabric Type CB'};
                $uv_treatment         = $vars->vars->{'UV Treatment'};
                
            }else{
                if(isset($vars->vars->{'Container Size'}))
                        $container_size = $vars->vars->{'Container Size'};
                if(isset($vars->vars->{'Yoke Size'}))
                        $yoke_size = $vars->vars->{'Yoke Size'};
                if(isset($vars->vars->{'MLW Configuration'}))
                        $mlw_configuration = $vars->vars->{'MLW Configuration'};
                if(isset($vars->vars->{'MLW Size'}))
                        $mlw_size = $vars->vars->{'MLW Size'};
                if(isset($vars->vars->{'Offset'}))
                        $offset = $vars->vars->{'Offset'};
                if(isset($vars->vars->{'Leg Pad Size'}))
                        $leg_pad_size = $vars->vars->{'Leg Pad Size'};
                if(isset($vars->vars->{'Lateral Size'}))
                        $lateral_size = $vars->vars->{'Lateral Size'};
                if(isset($vars->vars->{'Hardware Type'}))
                        $hardware_type = $vars->vars->{'Hardware Type'};
                if(isset($vars->vars->{'Webbing Color'}))
                        $webbing_color = $vars->vars->{'Webbing Color'};
                if(isset($vars->vars->{'Chest Strap Type'}))
                        $chest_strap_type = $vars->vars->{'Chest Strap Type'};
                if(isset($vars->vars->{'Base Ring Size'}))
                        $base_ring_size = $vars->vars->{'Base Ring Size'};
                if(isset($vars->vars->{'Thread Color'}))
                        $thread_color = $vars->vars->{'Thread Color'};
                if(isset($vars->vars->{'Pinstripe Color'}))
                        $pinstripe_color = $vars->vars->{'Pinstripe Color'};
                if(isset($vars->vars->{'Main Canopy Size'}))
                        $main_canopy_size = $vars->vars->{'Main Canopy Size'};
                if(isset($vars->vars->{'Reserve Canopy Size'}))
                        $reserve_canopy_size = $vars->vars->{'Reserve Canopy Size'};
                if(isset($vars->vars->{'RSL'}))
                        $rsl = $vars->vars->{'RSL'};
                if(isset($vars->vars->{'Chest Strap Size'}))
                        $chest_strap_size = $vars->vars->{'Chest Strap Size'};
                if(isset($vars->vars->{'Fabric Type CB'}))
                        $fabric_type_cb = $vars->vars->{'Fabric Type CB'};
                if(isset($vars->vars->{'UV Treatment'}))
                        $uv_treatment = $vars->vars->{'UV Treatment'};
            }
            
            if($vars->name == "Accessory Parts"){
                $main_riser_type                = $vars->vars->{'Main Riser Type'};
                $main_riser_color               = $vars->vars->{'Main Riser Color'};
                $main_riser_length              = $vars->vars->{'Main Riser Length'};
                $main_pilot_chute_type          = $vars->vars->{'Main Pilot Chute Type'};
                $main_pc_handle_type            = $vars->vars->{'Main PC Handle Type'};
                $main_pc_handle_color           = $vars->vars->{'Main PC Handle Color'};
                $bridle_length                  = $vars->vars->{'Bridle Length'};
                $reserve_static_line            = $vars->vars->{'Reserve Static Line'};
                $release_handle_color           = $vars->vars->{'Release Handle Color'};
                $reserve_deployment_handle_type = $vars->vars->{'Reserve Deployment handle type'};
                $fit_right_reserve_handle_color = $vars->vars->{'Fit Right Reserve Handle Color'};
                $main_deployment_bag_type       = $vars->vars->{'Main Deployment Bag Type'};
                $reserve_cap_color              = $vars->vars->{'Reserve Cap Color'};
                $main_pc_handle_color_1         = $vars->vars->{'Main PC Handle Color 1'};
                $main_pc_handle_color_2         = $vars->vars->{'Main PC Handle Color 2'};
                $main_pc_handle_color_3         = $vars->vars->{'Main PC Handle Color 3'};
                $main_riser_ring_size           = $vars->vars->{'Main Riser Ring Size'};
                $main_riser_hw_type             = $vars->vars->{'Main Riser HW Type'};
                $reserve_cap_binding_tape       = $vars->vars->{'Reserve Cap Binding Tape'};
                $reserve_cap_thread_color       = $vars->vars->{'Reserve Cap Thread Color'};
                $reserve_cap_pinstripe          = $vars->vars->{'Reserve Cap Pinstripe'};
            }else{
                if(isset($vars->vars->{'Main Riser Type'}))
                        $main_riser_type = $vars->vars->{'Main Riser Type'};
                if(isset($vars->vars->{'Main Riser Color'}))
                        $main_riser_color = $vars->vars->{'Main Riser Color'};
                if(isset($vars->vars->{'Main Riser Length'}))
                        $main_riser_length = $vars->vars->{'Main Riser Length'};
                if(isset($vars->vars->{'Main Pilot Chute Type'}))
                        $main_pilot_chute_type = $vars->vars->{'Main Pilot Chute Type'};
                if(isset($vars->vars->{'Main PC Handle Type'}))
                        $main_pc_handle_type = $vars->vars->{'Main PC Handle Type'};
                if(isset($vars->vars->{'Main PC Handle Color'}))
                        $main_pc_handle_color = $vars->vars->{'Main PC Handle Color'};
                if(isset($vars->vars->{'Bridle Length'}))
                        $bridle_length = $vars->vars->{'Bridle Length'};
                if(isset($vars->vars->{'Reserve Static Line Type'}))
                        $reserve_static_line_typpe = $vars->vars->{'Reserve Static Line Type'};
                if(isset($vars->vars->{'Release Handle Color'}))
                        $release_handle_color = $vars->vars->{'Release Handle Color'};
                if(isset($vars->vars->{'Reserve Deployment handle type'}))
                        $reserve_deployment_handle_type = $vars->vars->{'Reserve Deployment handle type'};
                if(isset($vars->vars->{'Fit Right Reserve Handle Color'}))
                        $fit_right_reserve_handle_color = $vars->vars->{'Fit Right Reserve Handle Color'};
                if(isset($vars->vars->{'Main Deployment Bag Type'}))
                        $main_deployment_bag_type = $vars->vars->{'Main Deployment Bag Type'};
                if(isset($vars->vars->{'Reserve Cap Color'}))
                        $reserve_cap_color = $vars->vars->{'Reserve Cap Color'};
                if(isset($vars->vars->{'Main PC Handle Color 1'}))
                        $main_pc_handle_color_1 = $vars->vars->{'Main PC Handle Color 1'};
                if(isset($vars->vars->{'Main PC Handle Color 2'}))
                        $main_pc_handle_color_2 = $vars->vars->{'Main PC Handle Color 2'};
                if(isset($vars->vars->{'Main PC Handle Color 3'}))
                        $main_pc_handle_color_3 = $vars->vars->{'Main PC Handle Color 3'};
                if(isset($vars->vars->{'Main Riser Ring Size'}))
                        $main_riser_ring_size = $vars->vars->{'Main Riser Ring Size'};
                if(isset($vars->vars->{'Main Riser HW Type'}))
                        $main_riser_hw_type = $vars->vars->{'Main Riser HW Type'};
                if(isset($vars->vars->{'Reserve Cap Binding Tape'}))
                        $reserve_cap_binding_tape = $vars->vars->{'Reserve Cap Binding Tape'};
                if(isset($vars->vars->{'Reserve Cap Thread Color'}))
                        $reserve_cap_thread_color = $vars->vars->{'Reserve Cap Thread Color'};
                if(isset($vars->vars->{'Reserve Cap Pinstripe'}))
                        $reserve_cap_pinstripe = $vars->vars->{'Reserve Cap Pinstripe'};
            }
            
        }
        
        $split = explode("-",$container_size);
        $reserve_container_size = $split[0];
        $main_container_size = $split[1];
        
        $project[] = array(
                        'cust_id'=>$row['customer'], 
                        'prod_id'=>$row['product'], 
                        'prod_name'=>$row['name'], 
                        'sn'=>$row['serial'], 
                        'status'=>$row['status'], 
                        'payment'=>$row['payment'], 
                        'colors'=>$row['colors'], 
                        'notes'=>$row['notes'], 
                        'estimated_ship_date'=>$row['estimated_completion'], 
                        'po'=>$row['pod'],
                        'date_modified'=>$row['peregrine_date_modified'],
                        'cust_name'=>$cust_name, 
                        'cust_height'=>$cust_height,
                        'cust_weight'=>$cust_weight,
                        'cust_chest'=>$cust_chest,
                        'cust_torso'=>$cust_torso,
                        'cust_waist'=>$cust_waist,
                        'cust_thigh'=>$cust_thigh,
                        'cust_inseam'=>$cust_inseam,
                        'cust_cup'=>$cust_cup,
                        'cust_gender'=>$cust_gender,
                        'container_size'=>$container_size,
                        'reserve_container_size'=>$reserve_container_size,
                        'main_container_size'=>$main_container_size,
                        'yoke_size'=>$yoke_size,
                        'mlw_configuration'=>$mlw_configuration,
                        'mlw_size'=>$mlw_sze,
                        'offset'=>$offset,
                        'leg_pad_size'=>$leg_pad_size,
                        'lateral_size'=>$lateral_size,
                        'hardware_type'=>$hardware_type,
                        'webbing_color'=>$webbing_color,
                        'chest_strap_type'=>$chest_strap_type,
                        'base_ring_size'=>$base_ring_size,
                        'thread_color'=>$thread_color,
                        'pinstripe_color'=>$pinstripe_color,
                        'main_canopy_size'=>$main_canopy_size,
                        'reserve_canopy_size'=>$reserve_canopy_size,
                        'rsl'=>$rsl,
                        'chest_strap_size'=>$chest_strap_size,
                        'fabric_type_cb'=>$fabric_type_cb,
                        'uv_treatment'=>$uv_treatment,
                        'main_riser_type'=>$main_riser_type,
                        'main_riser_color'=>$main_riser_color,
                        'main_riser_length'=>$main_riser_length,
                        'main_pilot_chute_type'=>$main_pilot_chute_type,
                        'main_pc_handle_type'=>$main_pc_handle_type,
                        'main_pc_handle_color'=>$main_pc_handle_color,
                        'bridle_length'=>$bridle_length,
                        'reserve_static_line'=>$reserve_static_line,
                        'release_handle_color'=>$release_handle_color,
                        'reserve_deployment_handle_type'=>$reserve_deployment_handle_type,
                        'fit_right_reserve_handle_color'=>$fit_right_reserve_handle_color,
                        'main_deployment_bag_type'=>$main_deployment_bag_type,
                        'reserve_cap_color'=>$reserve_cap_color,
                        'main_pc_handle_color_1'=>$main_pc_handle_color_1,
                        'main_pc_handle_color_2'=>$main_pc_handle_color_2,
                        'main_pc_handle_color_3'=>$main_pc_handle_color_3,
                        'main_riser_ring_size'=>$main_riser_ring_size,
                        'main_riser_hw_type'=>$main_riser_hw_type,
                        'reserve_cap_binding_tape'=>$reserve_cap_binding_tape,
                        'reserve_cap_thread_color'=>$reserve_cap_thread_color,
                        'reserve_cap_pinstripe'=>$reserve_cap_pinstripe,
                        );
        
    }   
    //return $project;
    //echo "<pre>";
    //print_r($project[0]);
    //echo "</pre>";
    echo json_encode($project);
    
}

function call_designer_to_edit_project($id){
    global $link;
    
    //echo 'SELECT * FROM projects WHERE id=\''.sf($id).'\'';
    
    $sn = mysqli_query($link, 'SELECT * FROM projects WHERE id=\''.sf($id).'\'');

    
    while($row = mysqli_fetch_assoc($sn)) {
        $pere_id = $row['peregrine_id'];
    }

    $request = "https://design.peregrinemfginc.com/do/api_project/?id=$pere_id";    
    //echo $request;
    
    // Generate curl request
    $session = curl_init($request);
    curl_setopt ($session, CURLOPT_POST, true);
    curl_setopt ($session, CURLOPT_POSTFIELDS, $request);
    curl_setopt($session, CURLOPT_HEADER, 0);
    curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
    
    // obtain response
    $response = curl_exec($session);
    curl_close($session);

}


function api_referral($id, $ref_id){
    global $link;
    
    $query = 'SELECT 
                                      referral_users.email as referrer_email
                                    , referral_users.name as referrer_name
                                    , referral_users.address as referrer_address
                                    , referral_users.phone as referrer_phone
                                    , referral_users.uspa as referrer_uspa
                                    , referral_users.dz as referrer_dz
                                    , referral_users.referal_code as referrer_referal_code
                                    , referral_users.payment as referrer_payment
                                    , referrals.*
                                    FROM referrals 
                                    LEFT JOIN referral_users ON referrals.referrer_id = referral_users.id
                                    WHERE referrals.id=\''.sf($id).'\' AND referrer_id=\''.sf($ref_id).'\' LIMIT 1';
                                    echo $query;
    $sn = mysqli_query($link, $query);
    while($row = mysqli_fetch_assoc($sn)) {
	    $referrals[] = $row;
    }
    //return $referrals;
    //return $id.'<br>'.$ref_id.'<br>'.$query;
}

function api_referral_parent($id){
    global $link;
    
    $query = 'SELECT * FROM referral_users WHERE referral_users.id=\''.sf($id).'\' LIMIT 1';
    $sn = mysqli_query($link, $query);
    while($row = mysqli_fetch_assoc($sn)) {
	    $referrals[] = $row;
    }
    return $referrals;
    //return $id.'<br>'.$ref_id.'<br>'.$query;
}

function api_referral_member($id){
    global $link;
    
    $query = 'SELECT * FROM referrals WHERE referrals.id=\''.sf($id).'\' LIMIT 1';
    $sn = mysqli_query($link, $query);
    while($row = mysqli_fetch_assoc($sn)) {
	    $referrals[] = $row;
    }
    return $referrals;
    //return $id.'<br>'.$ref_id.'<br>'.$query;
}

function render_product_traveler($product_id, $project_id, $order_id){
        
    $request = "https://design.peregrinemfginc.com/do/api_product_traveler/?id=".sf($order_id).'&product='.sf($product_id);    

    $session = curl_init($request);
    curl_setopt ($session, CURLOPT_POST, true);
    curl_setopt ($session, CURLOPT_POSTFIELDS, $request);
    curl_setopt($session, CURLOPT_HEADER, 0);
    curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($session);
    curl_close($session);
    
    $data = json_decode($response, true);
    
    //echo "<pre>";
    //print_r($data);
    //echo "</pre>";
    
    if($product_id == '96')
    {
        $dir = $_SERVER['DOCUMENT_ROOT']."/peregrinemanage/zip/Falkyn_order_".$project_id;
        $svg = root()."/zip/Falkyn_order_".$project_id.'/Final_design_'.$project_id.'.png';
    }
    else
    {
        $dir = $_SERVER['DOCUMENT_ROOT']."/peregrinemanage/zip/Glide_order_".$project_id;
        $svg = root()."/zip/Glide_order_".$project_id.'/Final_design_'.$project_id.'.png';
    }
        
    $header = '';
    $footer = '';
    
    $content = '
        <style>
            body {
                background:#FFF;    
                color: #000;
            }
            
            tr, td {
                border-bottom: 1px solid #ccc!important;
            }
            
            .right{
                border-right: 1px solid #ccc!important;
            }
            
            .left{
                border-left: 1px solid #ccc!important;
            }
            
            .box {
                width: 60%;
            }

            .row {
                width: 100%;
            }

            .bg-yellow {
                background-color: yellow;
                color:#000;
            }

            .bg-grey {
                background-color: grey;
                color:#FFF;
            }

            .first-col {
                width: 200px;
                padding: 5px;
            }

            .second-col {
                padding: 5px;
            }

            span {
                font-size: 12px;
                color: grey;
                opacity: 0,5;
            }
            
            /*
            #final_design{
                width: 1900px !important;
            }
            */
              
            .image {
              opacity: 1;
              display: block;
              transition: .5s ease;
              backface-visibility: hidden;
            }
            
            .cropped1 {
                width: 700px; /* width of container */
                height: 500px; /* height of container */
                object-fit: cover;
                object-position: 200px -40px;
            }
            
             .cropped2 {
                height: 400px;
                object-fit: cover;
            }

            .crop {
                width: 900px;
            }
            h2{
                color: black;
            }
            
            @media print
            {
                nav,
                .no-print
                {
                    display: none;
                }
            }
        </style>
        <page>
        <table frame="box" style="margin: auto; margin-top: 35px;">
            <tr >
                <td colspan="2">
                    <img src="'.root().'images/pmi.png" style="width:50px;padding-right: 10px;">
                    <img src="'.root().'images/peregrine.png" style="width:200px;margin-top: 18px;">
                </td>
                <td colspan="7"><h4 style="color: red;">HARNESS/CONTAINER TRAVELER CARD</h4></td>
            </tr>
            <tr  align="center" class="bg-yellow ">
                    <td class="right" rowspan="4"><h2>'.$data['serial_number'].'</h2></td>
                    <td class="right" rowspan="4">'.$data['production_cycle'].'</td>
                    <td class="right" rowspan="4">'.$data['yoke_size'].'-'.$data['main_lift_web_size'].'</td>
                    <td class="right bg-grey">LATERAL</td>
                    <td class="right left" rowspan="4">'.$data['reserve_container_size'].$data['main_container_size'].'</td>
                    <td rowspan="4" colspan="4"><h2>'.$data['name'].'</h2></td>
            </tr>
            <tr  align="center" class="bg-yellow">
                <td>'.$data['lateral_size'].'</td>
            </tr>
            <tr  align="center" class="bg-yellow ">
                <td class="right bg-grey">LEGPAD</td>
            </tr>
            <tr  align="center" class="bg-yellow">
                <td>'.$data['leg_pad_size'].'</td>
            </tr>
            <tr  align="center" class="bg-grey">
                <td class="right">PROCESS</td>
                <td class="right" colspan="4">OPERATOR</td>
                <td colspan="4">DATE COMPLETE</td>
            </tr>
            <tr  align="center">
                <td class="right">CUTTING COMPLETE</td>
                <td class="right" colspan="4">&nbsp;</td>
                <td colspan="4">&nbsp;</td>
            </tr>
            <tr  align="center">
                <td class="right">PROCESSING COMPLETE</td>
                <td class="right" colspan="4">&nbsp;</td>
                <td colspan="4">&nbsp;</td>
            </tr>
            <tr  align="center">
                <td class="right">HARNESS CUTTING COMPLETE</td>
                <td class="right" colspan="4">&nbsp;</td>
                <td colspan="4">&nbsp;</td>
            </tr>
            <tr  align="center">
                <td class="right">HARNESS PREP COMPLETE</td>
                <td class="right" colspan="4">&nbsp;</td>
                <td colspan="4">&nbsp;</td>
            </tr>
            <tr  align="center">
                <td class="right">MLA COMPLETE</td>
                <td class="right" colspan="4">&nbsp;</td>
                <td colspan="4">&nbsp;</td>
            </tr>
            <tr  align="center">
                <td class="right">MLB COMPLETE</td>
                <td class="right" colspan="4">&nbsp;</td>
                <td colspan="4">&nbsp;</td>
            </tr>
            <tr  align="center">
                <td class="right">HARNESSING COMPLETE</td>
                <td class="right" colspan="4">&nbsp;</td>
                <td colspan="4">&nbsp;</td>
            </tr>
            <tr  align="center">
                <td colspan="9">INSPECTED IN ACCORDANCE WITH THE FOLLOWING INSPECTION CHECK LISTS</td>
            </tr>
            <tr  align="center">
                <td class="right">MLW INSPECTION CHECKLIST <br/>(PM-CL003 REV 2)</td>
                <td class="right" colspan="3"><span>DATE</span></td>
                <td class="right"><span>STAMP</span></td>
                <td colspan="4"><span>INSPECTOR SIGNATURE</span></td>
            </tr>
            <tr  align="center">
                <td class="right">1ST IN PROCESS INSPECTION CHECKLIST <br/> (PM-CL001 REV 2)</td>
                <td class="right" colspan="3"><span>DATE</span></td>
                <td class="right"><span>STAMP</span></td>
                <td colspan="4"><span>INSPECTOR SIGNATURE</span></td>
            </tr>
            <tr  align="center">
                <td class="right">2ND IN PROCESS INSPECTION CHECKLIST <br/> (PM-CL001 REV 2)</td>
                <td class="right" colspan="3"><span>DATE</span></td>
                <td class="right"><span>STAMP</span></td>
                <td colspan="4"><span>INSPECTOR SIGNATURE</span></td>
            </tr>
            <tr  align="center">
                <td class="right">FINAL INSPECTION CHECKLIST <br/> (PM-CL002 REV 2)</td>
                <td class="right" colspan="3"><span>DATE</span></td>
                <td class="right"><span>STAMP</span></td>
                <td colspan="4"><span>INSPECTOR SIGNATURE</span></td>
            </tr>
            <tr  align="center">
                <td colspan="9">
                  <div class="crop">
                    <img id="final_design" class="cropped2" src="'.$svg.'">
                  </div>
                </td>
            </tr>
  
            <tr>
                <td colspan="2">
                    <img src="'.root().'images/pmi.png" style="width:50px;padding-right: 10px;">
                    <img src="'.root().'images/peregrine.png" style="width:200px;margin-top: 18px;">
                </td>
                <td colspan="7"><h4 style="color: red;">ACCESSORIES/TEAM C TRAVELER CARD</h4></td>
            </tr>
            <tr  align="center" class="bg-yellow">
                    <td class="right"><h2>'.$data['serial_number'].'</h2></td>
                    <td class="right" colspan="4">'.$data['production_cycle'].'</td>
                    <td colspan="4"><h2>'.$data['name'].'</h2></td>
            </tr>
            <tr>
                <td colspan="2">
                    <img src="'.root().'images/pmi.png" style="width:50px;padding-right: 10px;">
                    <img src="'.root().'images/peregrine.png" style="width:200px;margin-top: 18px;">
                </td>
                <td colspan="7"><h4 style="color: red;">HARNESS TRAVELER CARD</h4></td>
            </tr>
            <tr  align="center" class="bg-yellow">
                    <td class="right" rowspan="2"><h2>'.$data['serial_number'].'</h2></td>
                    <td class="right" colspan="4">'.$data['production_cycle'].'</td>
                    <td rowspan="2" colspan="4"><h2>'.$data['name'].'</h2></td>
            </tr>
            <tr  align="center" class="bg-yellow">
                <td class="right" colspan="2">CUT MLW</td>
                <td class="right" colspan="2">'.$data['main_lift_web_size'].'</td>
            </tr>
            <tr>
                <td class="right">HARNESS SIZE</td>
                <td class="right bg-yellow" colspan="4">'.$data['yoke_size'].'-'.$data['main_lift_web_size'].'</td>
                <td class="right bg-grey">LATERAL</td>
                <td class="right bg-yellow">'.$data['lateral_size'].'</td>
                <td class="right bg-grey">LEGPAD</td>
                <td class="right bg-yellow">'.$data['leg_pad_size'].'</td>
            </tr>
            <tr>
                <td class="right">HARNESS TYPE</td>
                <td class="right bg-yellow" colspan="4"></td>
                <td class="right" rowspan="4"><span>'.$data['batchlot_webbing-007'].'</span></td>
                <td class="right" rowspan="4" colspan="3"><span>DATE COMPLETE</span></td>
            </tr>
            <tr>
                <td class="right">TYPE 7 WEBBING COLOR</td>
                <td class="right bg-yellow" colspan="4">'.$data['webbing-007'].'</td>
            </tr>
            <tr>
                <td class="right">TYPE 8 WEBBING COLOR</td>
                <td class="right bg-yellow" colspan="4"></td>
            </tr>
            <tr>
                <td class="right">HARDWARE TYPE</td>
                <td class="right bg-yellow" colspan="4">'.$data['hardware'].'</td>
            </tr>
        </table>
        </page>
        <htmlpagefooter name="myFooter1">
          <div class="container">
            <div class="row">
             <div class="col-md-12">   
              <center><img src="'.root().'/images/hr.JPG"></center>
             </div>
            </div>
            <div class="row">
             <div class="col-md-4">   
              <b>{PAGENO} of {nbpg}</b>
             </div>
             <di1v class="col-md-8">   
              <img src="'.root().'/images/footer_peregrine.jpg" width="60%" style="float:right;">
             </div>
            </div>
          </div>
        </htmlpagefooter>
        <sethtmlpagefooter name="myFooter1" value="1"></sethtmlpagefooter>';
            
            require $_SERVER['DOCUMENT_ROOT']."/peregrinemanage/vendor/autoload.php";
            $mpdf = new \Mpdf\Mpdf([]);
            $mpdf->WriteHTML($content);
            $mpdf->Output($dir.'/Product_Traveler_'.$project_id.'.pdf', 'F');
}

function render_em_breakout($product_id, $project_id, $order_id){
    ini_set('max_execution_time', '300'); //300 seconds = 5 minutes
    
    $request = "https://".$des."/do/api_em_breakout/?id=".sf($order_id).'&product='.sf($product_id);    

    $session = curl_init($request);
    curl_setopt ($session, CURLOPT_POST, true);
    curl_setopt ($session, CURLOPT_POSTFIELDS, $request);
    curl_setopt($session, CURLOPT_HEADER, 0);
    curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($session);
    curl_close($session);
    
    $order_options = json_decode($response, true);

    //echo "<pre>";
    //print_r($order_options);
    //echo "</pre>";
    
    
    $color_hex =  [
                    'transparent' => 'None',
                    '#000000' => 'Black',
                    '#F7F7F7' => 'White',
                    '#6A6A6A' => 'Charcoal',
                    '#898E8C' => 'Silver',
                    '#355749' => 'Forest Green',
                    '#2d8447' => 'Kelly Green',
                    '#28FD46' => 'Neon Green',
                    '#D3BF80' => 'Tan',
                    '#937F4F' => 'Khaki',
                    '#5E473D' => 'Brown',
                    '#314AA4' => 'Royal Blue',
                    '#4E5675' => 'Navy Blue',
                    '#4494f0' => 'Electric Blue',
                    '#009e9a' => 'Teal',
                    '#493A5C' => 'Purple',
                    '#E3CADD' => 'Pink',
                    '#FB0069' => 'Neon Pink',
                    '#D5362D' => 'Orange',
                    '#F44E2F' => 'Neon Orange',
                    '#C2103B' => 'Red',
                    '#501922' => 'Burgandy',
                    '#D1D800' => 'Yellow',
                    '#E3FF00' => 'Neon Yellow',
                    '#FEC12B' => 'Gold',
                    '#CC0000' => 'Red',
                  ];
    foreach($order_options as $key => $val){
        if(isset($color_hex[$val]))
            $order_options[$key] = $color_hex[$val];
    }
    
    
    if($product_id == '96'){$p = 'Falkyn'; $c = '20'; }else{$p='Glide'; $c = '16'; }
    
    if($product_id == '96')
    {
        $dir = $_SERVER['DOCUMENT_ROOT']."/peregrinemanage/zip/Falkyn_order_".$project_id;
    }
    else
    {
        $dir = $_SERVER['DOCUMENT_ROOT']."/peregrinemanage/zip/Glide_order_".$project_id;
    }
        
    for($i=1;$i<=$c;$i++)
    {  
        $order_options['img-em'.$i.'_1'] = ($order_options['img-em'.$i.'_1'] == '') ? 'None' : $order_options['img-em'.$i.'_1'];
        $order_options['text_logo_field-em'.$i.'_1'] = ($order_options['text_logo_field-em'.$i.'_1'] == '') ? 'None' : $order_options['text_logo_field-em'.$i.'_1'];
        $order_options['em'.$i.'_2'] = ($order_options['em'.$i.'_1'] == 'None' && $order_options['img-em'.$i.'_1'] == 'None' && $order_options['text_logo_field-em'.$i.'_1'] == 'None') ? 'Transparent' : $order_options['em'.$i.'_2'];
        $order_options['em'.$i.'_3'] = ($order_options['em'.$i.'_1'] == 'None' && $order_options['img-em'.$i.'_1'] == 'None' && $order_options['text_logo_field-em'.$i.'_1'] == 'None') ? 'Transparent' : $order_options['em'.$i.'_3'];
    }

$header = '';
$footer = '';
$content .='
<style>
    .box {
        border: 1px solid #ccc!important;
    }
    .svgtext{
        paint-order: stroke;
        font-style: normal;
        font-weight: normal;
        fill-opacity: 1;
        stroke-width: 1px;
        stroke-linecap: butt;
        stroke-linejoin: miter;
        stroke-opacity: 1;
        font-size: 15px;
    }
svg {
    width: 300px;
    height: 300px;
}
p {
    color: #000000;
}
body {
    background: #FFFFFF;
}
.lead.measure{
    border-bottom: 1px solid black;
    width: 300px;
}
h2{
    color: black;
}
svg
{
    width: 300px;
    height: 410px;
}
    
@media print {
    .pagebreak { 
        page-break-before: always; 
    }
    .introduction{
        padding-top: 0px;
    }
    .no-print{
        display:none;
    }
    #final_design{
        width: 1900px !important;
    }
    .no-padding{
        padding-top: 0px !important;
    }
    .total-estimate{
        width: 100% !important;
        max-width: 100% !important;
        flex: none;
    }
    nav{
        display: none;
    }
}
</style>
<page>
<div class="container">
    <h3 class="pt-5">Embroidery:</h3>';
                            $z = 0;
                            
                            for($i=1;$i<=$c;$i++)
                            {
                                if($order_options['em'.$i.'_1'] != 'None')
                                {
                                    $z++;
                                    if($z == 3)
                                    { 
                                        $break = 'pagebreak'; 
                                        $z = 0;
                                    }
                                    else
                                    {
                                        $break = ''; 
                                    }
                                    
                                    $content .='<div class="row print no-padding '.$break.'">
                                                    <div class="col-sm-12"><h2>EM'.$i.' - '.$order_options['em'.$i.'_1'].'</h2></div>
                                                        <div class="col-sm-12 mb-5">
                                                            <div class="row">
                                                                <div class="col-sm-5 box">';
                                                    if(preg_match('/Custom Logo/', $order_options['em'.$i.'_1']))
                                                    {
                                                        //if client use emb format
                                                        //for now lets replace using
                                                        //other dummy text
                                                        if(preg_match('/\.emb/', $order_options['img-em'.$i.'_1']))
                                                        {
                                                            $logo = 'https://'.$des.'/images/upload/emb-file.png';
                                                        }
                                                        else if(preg_match('/\.dst/', $order_options['img-em'.$i.'_1']))
                                                        {
                                                            $logo = 'https://'.$des.'/images/upload/dst-file.png';
                                                        }
                                                        else if(preg_match('/\.EMB/', $order_options['img-em'.$i.'_1']))
                                                        {
                                                            $logo = 'https://'.$des.'/images/upload/emb-file.png';
                                                        }
                                                        else if(preg_match('/\.DST/', $order_options['img-em'.$i.'_1']))
                                                        {
                                                            $logo = 'https://'.$des.'/images/upload/dst-file.png';
                                                        }
                                                        else
                                                        {
                                                            $logo = 'https://'.$des.'/images/upload/'.$order_options['img-em'.$i.'_1'];
                                                        }
                                                    }
                                                    //else if(preg_match('/Custom Text/', $order_options['em'.$i.'_1']))
                                                    else
                                                    {
                                                        $logo = 'https://'.$des.'/zip/'.$p.'_order_'.$order_id.'/em'.$i.'_'.$order_id.'.svg';
                                                    }
                                                    
                                                    $handler = curl_init($logo);
                                                    curl_setopt($handler,  CURLOPT_RETURNTRANSFER, TRUE);
                                                    $re = curl_exec($handler);
                                                    $httpcdd = curl_getinfo($handler, CURLINFO_HTTP_CODE);
                                                    
                                                    if ($httpcdd == '404')
                                                    { 
                                                        //$logo = '';
                                                        echo "<script>window.location.href = 'https://'.$des.'/em-breakout/?design_id=".$order_id."&product=".$product_id."&status=render';</script>";
                                                    } 
                                                    else 
                                                    { 
                                                        $logo = $logo; 
                                                    }
                                                    
                                                    $order_options['em1_2_pgt2-gt']     = ($order_options['em1_2_pgt2-gt'] == 'None' ) ? 'Transparent' : $order_options['em1_2_pgt2-gt'];
                                                    $order_options['em1_2_pgt2-pmi']    = ($order_options['em1_2_pgt2-pmi'] == 'None' ) ? 'Transparent' : $order_options['em1_2_pgt2-pmi'];
                                                    $order_options['em1_3_pgt2']        = ($order_options['em1_3_pgt2'] == 'None' ) ? 'Transparent' : $order_options['em1_3_pgt2'];
                                                    
                                                    $order_options['em2_2_pgt3-glide']  = ($order_options['em2_2_pgt3-glide'] == 'None' ) ? 'Transparent' : $order_options['em2_2_pgt3-glide'];
                                                    $order_options['em2_2_pgt3-dash']   = ($order_options['em2_2_pgt3-dash'] == 'None' ) ? 'Transparent' : $order_options['em2_2_pgt3-dash'];
                                                    $order_options['em2_3_pgt3-inner']  = ($order_options['em2_3_pgt3-inner'] == 'None' ) ? 'Transparent' : $order_options['em2_3_pgt3-inner'];
                                                    $order_options['em2_3_pgt3-outer']  = ($order_options['em2_3_pgt3-outer'] == 'None' ) ? 'Transparent' : $order_options['em2_3_pgt3-outer'];
                                                    
                                                    $order_options['em3_2_pgt1-gt']     = ($order_options['em3_2_pgt1-gt'] == 'None' ) ? 'Transparent' : $order_options['em3_2_pgt1-gt'];
                                                    $order_options['em3_2_pgt1-pmi']    = ($order_options['em3_2_pgt1-pmi'] == 'None' ) ? 'Transparent' : $order_options['em3_2_pgt1-pmi'];
                                                    $order_options['em3_2_pgt1-peregrine']    = ($order_options['em3_2_pgt1-peregrine'] == 'None' ) ? 'Transparent' : $order_options['em3_2_pgt1-peregrine'];
                                                    $order_options['em3_3_pgt1']        = ($order_options['em3_3_pgt1'] == 'None' ) ? 'Transparent' : $order_options['em3_3_pgt1'];
                                                    
                                                    
                                                    
                                    $content .='    <img src="'.$logo.'"  width="300px">
                                                                </div>
                                                                <div class="col-sm-1">
                                                                    &nbsp;
                                                                </div>
                                                                <div class="col-sm-6">';
                                                    if(preg_match('/Custom/', $order_options['em'.$i.'_1']) || preg_match('/PMI Logo/', $order_options['em'.$i.'_1']) || preg_match('/Glide Logo/', $order_options['em'.$i.'_1']))
                                                    {
                                                                                 $content .= '
                                                                                       <p class="lead measure">Fill
                                                                                        &nbsp;&nbsp;:&nbsp; '.$order_options['em'.$i.'_2'].'</p>
                                                                                        <br/>
                                                                                        <p class="lead measure">Outline
                                                                                        &nbsp;&nbsp;:&nbsp; '.$order_options['em'.$i.'_3'].'</p>
                                                                                        ';
                                                                                //echo $needle;
                                                    }
                                                    else if(preg_match('/PGT/', $order_options['em'.$i.'_1']))
                                                    {
                                                                                 $content .= '
                                                                                       <p class="lead measure">GT Fill
                                                                                        &nbsp;&nbsp;:&nbsp; '.$order_options['em1_2_pgt2-gt'].'</p>
                                                                                        <br/>
                                                                                        <p class="lead measure">PMI Fill
                                                                                        &nbsp;&nbsp;:&nbsp; '.$order_options['em1_2_pgt2-pmi'].'</p>
                                                                                        <br/>
                                                                                        <p class="lead measure">Outline
                                                                                        &nbsp;&nbsp;:&nbsp; '.$order_options['em1_3_pgt2'].'</p>
                                                                                        ';
                                                                                //echo $needle;
                                                    }
                                                    else if(preg_match('/GlideGT/', $order_options['em'.$i.'_1']))
                                                    {
                                                                                 $content .= '
                                                                                       <p class="lead measure">Glide Fill
                                                                                        &nbsp;&nbsp;:&nbsp; '.$order_options['em2_2_pgt3-glide'].'</p>
                                                                                        <br/>
                                                                                        <p class="lead measure">Dash Fill
                                                                                        &nbsp;&nbsp;:&nbsp; '.$order_options['em2_2_pgt3-dash'].'</p>
                                                                                        <br/>
                                                                                        <p class="lead measure">Inner Outline
                                                                                        &nbsp;&nbsp;:&nbsp; '.$order_options['em2_3_pgt3-inner'].'</p>
                                                                                        <p class="lead measure">Outline
                                                                                        &nbsp;&nbsp;:&nbsp; '.$order_options['em2_3_pgt3-outer'].'</p>
                                                                                        ';
                                                                                //echo $needle;
                                                    }
                                                    else if(preg_match('/GTSW/', $order_options['em'.$i.'_1']))
                                                    {
                                                                                 $content .= '
                                                                                       <p class="lead measure">GT Fill
                                                                                        &nbsp;&nbsp;:&nbsp; '.$order_options['em3_2_pgt1-gt'].'</p>
                                                                                        <br/>
                                                                                        <p class="lead measure">PMI Fill
                                                                                        &nbsp;&nbsp;:&nbsp; '.$order_options['em3_2_pgt1-pmi'].'</p>
                                                                                        <br/>
                                                                                        <p class="lead measure">Peregrine Fill
                                                                                        &nbsp;&nbsp;:&nbsp; '.$order_options['em3_2_pgt1-peregrine'].'</p>
                                                                                        <p class="lead measure">Outline
                                                                                        &nbsp;&nbsp;:&nbsp; '.$order_options['em3_3_pgt1'].'</p>
                                                                                        ';
                                                                                //echo $needle;
                                                    }
                                                    else
                                                    {
                                                        
                                                                                                
                                                                                    if($order_options['new_falkyn'] == 'true')
                                                                                    {
                                                                                            if(preg_match('/F TATTOO/', $order_options['em'.$i.'_1']))
                                                                                            {
                                                                                                 echo '
                                                                                                            <p class="lead measure">Color 1
                                                                                                            &nbsp;&nbsp;:&nbsp; '.$order_options['em'.$i.'_needle7-1'].'</p>
                                                                                                            <br/>
                                                                                                            <p class="lead measure">Color 2
                                                                                                            &nbsp;&nbsp;:&nbsp; '.$order_options['em'.$i.'_needle6-1'].'</p>
                                                                                                            <br/>
                                                                                                            <p class="lead measure">Color 3
                                                                                                            &nbsp;&nbsp;:&nbsp; '.$order_options['em'.$i.'_needle5-1'].'</p>
                                                                                                            <br/>
                                                                                                            <p class="lead measure">Color 4
                                                                                                            &nbsp;&nbsp;:&nbsp; '.$order_options['em'.$i.'_needle4-1'].'</p>
                                                                                                            <br/>
                                                                                                            <p class="lead measure">Color 5
                                                                                                            &nbsp;&nbsp;:&nbsp; '.$order_options['em'.$i.'_needle8-1'].'</p>
                                                                                                            <br/>
                                                                                                            <p class="lead measure">Color 6
                                                                                                            &nbsp;&nbsp;:&nbsp; '.$order_options['em'.$i.'_needle2-1'].'</p>
                                                                                                            <br/>
                                                                                                        ';
                                                                                            }
                                                                                            else if(preg_match('/PMI TATTOO/', $order_options['em'.$i.'_1']))
                                                                                            {
                                                                                                 echo '
                                                                                                            <p class="lead measure">Color 1
                                                                                                            &nbsp;&nbsp;:&nbsp; '.$order_options['em'.$i.'_needle3-1'].'</p>
                                                                                                            <br/>
                                                                                                            <p class="lead measure">Color 2
                                                                                                            &nbsp;&nbsp;:&nbsp; '.$order_options['em'.$i.'_needle5-1'].'</p>
                                                                                                            <br/>
                                                                                                            <p class="lead measure">Color 3
                                                                                                            &nbsp;&nbsp;:&nbsp; '.$order_options['em'.$i.'_needle4-1'].'</p>
                                                                                                            <br/>
                                                                                                            <p class="lead measure">Color 4
                                                                                                            &nbsp;&nbsp;:&nbsp; '.$order_options['em'.$i.'_needle2-1'].'</p>
                                                                                                            <br/>
                                                                                                        ';
                                                                                            }
                                                                                            else if(preg_match('/FALKYN/', $order_options['em'.$i.'_1']))
                                                                                            {
                                                                                                  echo '
                                                                                                            <p class="lead measure">Color 1
                                                                                                            &nbsp;&nbsp;:&nbsp; '.$order_options['em'.$i.'_needle3-1'].'</p>
                                                                                                            <br/>
                                                                                                            <p class="lead measure">Color 2
                                                                                                            &nbsp;&nbsp;:&nbsp; '.$order_options['em'.$i.'_needle2-1'].'</p>
                                                                                                            <br/>
                                                                                                            <p class="lead measure">Color 3
                                                                                                            &nbsp;&nbsp;:&nbsp; '.$order_options['em'.$i.'_needle7-1'].'</p>
                                                                                                            <br/>
                                                                                                            <p class="lead measure">Color 4
                                                                                                            &nbsp;&nbsp;:&nbsp; '.$order_options['em'.$i.'_needle4-1'].'</p>
                                                                                                            <br/>
                                                                                                            <p class="lead measure">Color 5
                                                                                                            &nbsp;&nbsp;:&nbsp; '.$order_options['em'.$i.'_needle13-1'].'</p>
                                                                                                            <br/>
                                                                                                        
                                                                                                        ';
                                                                                            }
                                                                                            else if(preg_match('/F/', $order_options['em'.$i.'_1']))
                                                                                            {
                                                                                                echo '
                                                                                                            <p class="lead measure">Color 1
                                                                                                            &nbsp;&nbsp;:&nbsp; '.$order_options['em'.$i.'_needle2-1'].'</p>
                                                                                                            <br/>
                                                                                                            <p class="lead measure">Color 2
                                                                                                            &nbsp;&nbsp;:&nbsp; '.$order_options['em'.$i.'_needle6-1'].'</p>
                                                                                                            <br/>
                                                                                                            <p class="lead measure">Color 3
                                                                                                            &nbsp;&nbsp;:&nbsp; '.$order_options['em'.$i.'_needle7-1'].'</p>
                                                                                                            <br/>
                                                                                                            <p class="lead measure">Color 4
                                                                                                            &nbsp;&nbsp;:&nbsp; '.$order_options['em'.$i.'_needle8-1'].'</p>
                                                                                                            <br/>
                                                                                                            <p class="lead measure">Color 5
                                                                                                            &nbsp;&nbsp;:&nbsp; '.$order_options['em'.$i.'_needle3-1'].'</p>
                                                                                                            <br/>
                                                                                                            <p class="lead measure">Color 6
                                                                                                            &nbsp;&nbsp;:&nbsp; '.$order_options['em'.$i.'_needle4-1'].'</p>
                                                                                                            <br/>
                                                                                                            <p class="lead measure">Color 7
                                                                                                            &nbsp;&nbsp;:&nbsp; '.$order_options['em'.$i.'_needle5-1'].'</p>
                                                                                                            <br/>
                                                                                                        ';
                                                                                            }
                                                                                    }
                                                                                    else
                                                                                    {
                                                                                        for($j=1;$j<=16;$j++)
                                                                                        {
                                                                                            $order_options['em'.$i.'_needle'.$j] = ($order_options['em'.$i.'_needle'.$j] == '' || $order_options['em'.$i.'_needle'.$j] == 'none' ) ? 'None' : $order_options['em'.$i.'_needle'.$j];
                                                                                            $needle = '
                                                                                                       <p class="lead measure">Needle '.$j.'
                                                                                                        &nbsp;&nbsp;:&nbsp; '.$order_options['em'.$i.'_needle'.$j].'</p>
                                                                                                ';
                                                                                                
                                                                                            if(preg_match('/F TATTOO/', $order_options['em'.$i.'_1']))
                                                                                            {
                                                                                                if($j=='2' || $j=='4' || $j=='5' || $j=='6' || $j=='8' || $j=='16')
                                                                                                {
                                                                                                    $content .= $needle.'<br/>';
                                                                                                }
                                                                                            }
                                                                                            else if(preg_match('/PMI TATTOO/', $order_options['em'.$i.'_1']))
                                                                                            {
                                                                                                if($j=='1' || $j=='2' || $j=='8' || $j=='12' || $j=='16' )
                                                                                                {
                                                                                                    $content .= $needle.'<br/>';
                                                                                                }
                                                                                            }
                                                                                            else if(preg_match('/FALKYN/', $order_options['em'.$i.'_1']))
                                                                                            {
                                                                                                if($j=='1' || $j=='2' || $j=='3' || $j=='7' || $j=='8' || $j=='9' || $j=='12' || $j=='16' )
                                                                                                {
                                                                                                    $content .= $needle.'<br/>';
                                                                                                }
                                                                                            }
                                                                                            else if(preg_match('/F/', $order_options['em'.$i.'_1']))
                                                                                            {
                                                                                                if($j=='1' || $j=='2' || $j=='8' || $j=='16' )
                                                                                                {
                                                                                                    $content .= $needle.'<br/>';
                                                                                                }
                                                                                            } 
                                                                                        }
                                                                                    }
                                                                                
                                                    }
                                    $content .='                </div>
                                                            </div>
                                                        </div>
                                                    </div>';

                                }
                            }
        $content .='</div>
</div>
</page>
        <htmlpagefooter name="myFooter1">
          <div class="container">
            <div class="row">
             <div class="col-md-12">   
              <center><img src="'.$_SERVER['DOCUMENT_ROOT'].'/peregrinemanage/images/hr.JPG"></center>
             </div>
            </div>
            <div class="row">
             <div class="col-md-4">   
              <b>{PAGENO} of {nbpg}</b>
             </div>
             <di1v class="col-md-8">   
              <img src="'.$_SERVER['DOCUMENT_ROOT'].'/peregrinemanage/images/footer_peregrine.jpg" width="60%" style="float:right;">
             </div>
            </div>
          </div>
        </htmlpagefooter>
        <sethtmlpagefooter name="myFooter1" value="1"></sethtmlpagefooter>';
                
                require $_SERVER['DOCUMENT_ROOT']."/peregrinemanage/vendor/autoload.php";
                $mpdf = new \Mpdf\Mpdf([]);
                $mpdf->WriteHTML($content);
                $mpdf->Output($dir.'/EM_Breakout_'.$project_id.'.pdf', 'F');
                unset($mpdf);
}

?>
