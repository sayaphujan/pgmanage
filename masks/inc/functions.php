<?
date_default_timezone_set( 'America/Chicago' );

error_reporting( E_ALL ^ E_NOTICE );

ini_set( 'display_errors', 1 );

session_start();

//error_reporting(0);

$link = mysqli_connect('localhost','pgmanage_db','485elzpzkm','pgmanage_db');
if ( mysqli_connect_error() ) {
    $emsg = 'MySQL Error: ' . mysqli_connect_error();
    die( $emsg );
}

function mysqli_result( $res, $row, $field = 0 ) {
    $res->data_seek( $row );
    $datarow = $res->fetch_array();
    return $datarow[ $field ];
}

function root( $var = '' ) {
    $pro = 'https';
    $dom = 'masks.peregrinemfginc.com';
    $fol = '';

    if ( !empty( $_SERVER[ 'HTTPS' ] ) && $_SERVER[ 'HTTPS' ] != 'off' ) {
        $pro = 'https';
    }
    return $pro . '://' . $dom . '/' . $fol . $var;

}

function num_only( $var, $ex = ',.' ) {
    return preg_replace( '/[^0-9' . $ex . ']/', '', $var );
}

function word_cleanup( $var ) {
    $pat = "/<(\w+)>(\s|&nbsp;)*<\/\1>/";
    $var = preg_replace( $pat, '', $var );
    return mb_convert_encoding( $var, 'HTML-ENTITIES', 'UTF-8' );
}

function make_safe( $var, $safe = '' ) {
    $var = word_cleanup( $var );
    if ( isset( $safe ) && !empty( $safe ) ) {
        $var = strip_tags( $var, $safe );
    }
    $var = addslashes( trim( $var ) );
    return $var;
}

function sf($var) {
	return make_safe($var);
}

function save_posts( $posts ) {
    foreach ( $posts as $post ) {
        if ( isset( $_POST[ $post ] ) ) {
            $_SESSION[ $post ] = $_POST[ $post ];
        }
    }
}

function unset_sess( $sessions ) {
    foreach ( $sessions as $session ) {
        if ( isset( $_SESSION[ $session ] ) ) {
            unset( $_SESSION[ $session ] );
        }
    }
}

function blankCheck( $vars ) {
    $x = 0;
    $c = count( $vars );

    for ( $i = 0; $i < $c; ++$i ) {
        if ( !empty( $_POST[ $vars[ $i ] ] ) ) {
            ++$x;
        }
    }

    if ( $x == $c ) {
        return true;
    } else {
        return false;
    }
}

function blankResp( $var, $blank = '' ) {
    $var = trim( $var );
    if ( !empty( $var ) ) {
        return $var;
    } else {
        return $blank;
    }
}

function month( $num ) {
    $month = array( '', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December' );
    return $month[ $num ];
}

function sendHTML($email,$subject,$message) {
	$dom = root('dom');
	$headers  = "From: <info@peregrine.ndevix.com>\r\n"; 
	$headers .= "To: <{$email}>\r\n";
	$headers .= "Subject: {$subject}\r\n";
	$headers .= "Date: ".date("r")."\r\n";
	$headers .= "Organization: Organization\r\n";
	$headers .= "User-Agent: NDX Mail/1.0\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Reply-To: info@peregrinemfginc.com\r\n";
	$headers .= "Content-Type: text/html; charset=iso-8859-1\r\n";
	$headers .= "Content-Transfer-Encoding: 7bit\r\n";
	$headers .= "Content-Disposition: inline\r\n";
	$headers .= "Message-Id: <".date("mdY").time().".email@peregrine.ndevix.com>";
	
	$socket = pfsockopen('mail.ndevix.com', 25, $errno, $errstr);
	if ($socket){
		fputs($socket, "HELO s106.ndevix.com\r\n");
		fgets($socket, 256);
		fputs($socket, "AUTH LOGIN\r\n");
		fgets($socket, 256);
		fputs($socket, base64_encode("peregrinecontainers@ndevix.com")."\r\n");
		fgets($socket, 256);
		fputs($socket, base64_encode("i2pptpxzzi")."\r\n");
		fgets($socket, 256);
		fputs($socket, "MAIL FROM:<info@peregrine.ndevix.com>\r\n");
		fgets($socket, 256);
		fputs($socket, "RCPT TO:<{$email}>\r\n");
		fgets($socket, 256);
		fputs($socket, "DATA\r\n");
		fgets($socket, 256);
		fputs($socket, "{$headers}\n\n{$message}\r\n");
		fputs($socket, ".\r\n");
		fgets($socket, 256);
		fputs($socket, "QUIT\r\n");
		fgets($socket, 256);
		fclose($socket);
	} else {
		echo 'Our servers are temporarily offline. Please try back in a few minutes';
	}
}

function smtpemail($email,$subject,$message) {

$headers = 'From: Peregrine Manufacturing Inc <info@peregrine.ndevix.com>
To: <'.$email.'>
Subject: '.$subject.'
Date: '.date('r').'
User-Agent: nDX Mail/1.0
MIME-Version: 1.0
Content-Type: text/plain;
 charset="us-ascii"
Content-Transfer-Encoding: 7bit
Content-Disposition: inline
Message-Id: <'.date('mdY').time().'.email@s106.ndevix.com>';

$socket = pfsockopen('s105.ndevix.com', 25, $timeout);
	if(!$socket){
	echo("Our email services are temporarily offline. Please try back in a few minutes.");
	} else {
	fputs($socket, "HELO s106.ndevix.com\r\n");
	fgets($socket, 256);
	fputs($socket, "MAIL FROM:<".$from.">\r\n");
	fgets($socket, 256);
	fputs($socket, "RCPT TO:<".$email.">\r\n");
	fgets($socket, 256);
	fputs($socket, "DATA\r\n");
	fgets($socket, 256);
	fputs($socket, "".$headers."\n\n".stripslashes($message)."\r\n");
	fputs($socket, ".\r\n");
	fgets($socket, 256);
	fputs($socket, "QUIT\r\n");
	fgets($socket, 256);
	fclose($socket);
	}
}


function random_alphanum_string($length) {
    $key = '';
    $keys = array_merge(range(0, 9), range('a', 'z'));

    for ($i = 0; $i < $length; $i++) {
        $key .= $keys[array_rand($keys)];
    }

    return $key;
}
?>
