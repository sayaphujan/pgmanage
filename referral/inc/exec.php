<?
require_once( 'functions.php' );

if ( !empty( $_SESSION[ 'rid' ] ) ) {
    switch ( $_GET[ 'act' ] ) {
        case 'logout':
            session_destroy();
            header( 'Location: ' . root() );
            exit();
            break;
        case 'new':
            $vars = array( 'pname', 'paddress', 'puspa', 'pemail', 'pphone', 'pcontact', 'ppdate' );
            save_posts( $vars );
            if ( blankCheck( $vars ) ) {
                $chk = mysqli_query( $link, 'SELECT email FROM referrals WHERE email=\'' . make_safe( $_POST[ 'pemail' ] ) . '\' AND deleted=\'\' LIMIT 1' );
                if ( mysqli_num_rows( $chk ) == 0 ) {
                    if ( mysqli_query( $link, 'INSERT INTO referrals (created, referrer_id, name, address, uspa, email, phone, contact_method, contact_window, picture, approved) VALUES(NOW(),\'' . make_safe( $_POST[ 'rid' ] ) . '\',\'' . make_safe( $_POST[ 'pname' ] ) . '\',\'' . make_safe( $_POST[ 'paddress' ] ) . '\',\'' . make_safe( $_POST[ 'puspa' ] ) . '\',\'' . make_safe( $_POST[ 'pemail' ] ) . '\',\'' . make_safe( $_POST[ 'pphone' ] ) . '\',\'' . make_safe( $_POST[ 'pcontact' ] ) . '\',\'' . make_safe( $_POST[ 'ppdate' ] ) . '\',\'\',\'Pending\')' ) ) {
                        $crid = mysqli_insert_id( $link );
                        if (file_exists($_FILES['ppicture']['tmp_name']) || is_uploaded_file($_FILES['ppicture']['tmp_name'])) {
                            set_time_limit( 0 );
                            ini_set( 'max_execution_time', 7200 );
                            ini_set( 'max_input_time', 7200 );
                            ini_set( 'memory_limit', '1024M' );
                            $ext = explode('/', $_FILES['ppicture']['type']);
                            if ( $ext[ 0 ] == 'image' ) {
                                if ( $ext[ 1 ] == 'jpeg' || $ext[ 1 ] == 'pjpeg' ) {
                                    $exts = 'jpg';
                                } else {
                                    $exts = $ext[ 1 ];
                                }
                                $original = '../photos/' . $crid . '.' . $exts;
                                $mode = '0666';
                                move_uploaded_file( $_FILES[ 'ppicture' ][ 'tmp_name' ], $original );
                                chmod( $original, octdec( $mode ) );
                                mysqli_query($link, 'UPDATE referrals SET picture=\'' . make_safe( $exts ) . '\' WHERE id=\'' . make_safe( $crid ) . '\'' );
                            }

                        }
                        //sendHTML('danc@peregrinemfginc.com', make_safe( $_POST[ 'cname' ] ).' created a refer', make_safe( $_POST[ 'cname' ] ).' has created a referral: <br><br>'.make_safe( $_POST[ 'pname' ] ).'<br><br>'.make_safe( $_POST[ 'pemail' ] ));
                        $_SESSION[ 'error' ] = '<div class="alert alert-success mt-5"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Referral Account Created!</div>';
                        unset_sess( $vars );
                    }
                } else {
                    $_SESSION[ 'error' ] = '<div class="alert alert-danger mt-5"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>That customer email has already been used, please choose a different one.</div>';
                }
            } else {
                $_SESSION[ 'error' ] = '<div class="alert alert-danger mt-5"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>You cannot leave fields blank!</div>';
            }
            header( 'Location: ' . $_SERVER[ 'HTTP_REFERER' ] );
            exit();
            break;

        default:
            header( 'Location: ' . root() );
            exit();
            break;
    }
} else {
    switch ( $_GET[ 'act' ] ) {
        case 'login':
            if (isset($_POST[ 'submit' ])) {
                if ( !empty( $_POST[ 'cemail' ] ) && !empty( $_POST[ 'cpassword' ] ) ) {
                    $chk = mysqli_query( $link, 'SELECT * FROM referral_users WHERE email=\'' . make_safe( $_POST[ 'cemail' ] ) . '\' AND deleted=\'\' LIMIT 1' );
                    if ( mysqli_num_rows( $chk ) == 1 ) {
                        $hash = mysqli_result( $chk, 0, 'salt' ) . $_POST[ 'cpassword' ];
                        for ( $i = 0; $i < 100000; ++$i ) {
                            $hash = hash( 'sha256', $hash );
                        }
                        $hash = mysqli_result( $chk, 0, 'salt' ) . $hash;
                        if ( $hash == mysqli_result( $chk, 0, 'password' ) ) {
                            $_SESSION[ 'rid' ] = mysqli_result( $chk, 0, 'id' );
                            $_SESSION[ 'cname' ] = mysqli_result( $chk, 0, 'name' );
                            $_SESSION[ 'error' ] = '<div class="alert alert-success mt-5"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Welcome, ' . $_SESSION[ 'cname' ] . '</div>';
                            header( 'Location: ' . root( 'dashboard/' ) );
                            exit();
                        } else {
                            $_SESSION[ 'error' ] = '<div class="alert alert-danger mt-5"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Could not be logged in! Please check your password.</div>';
                        }
                    } else {
                        $_SESSION[ 'error' ] = '<div class="alert alert-danger mt-5"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Could not be logged in! User does not exist!</div>';
                    }
                } else {
                    $_SESSION[ 'error' ] = '<div class="alert alert-danger mt-5"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Could not be logged in! Please check your login details.</div>';
                }
            } else {
                if (!empty( $_POST[ 'cemail' ] )) {
                    $chk = mysqli_query( $link, 'SELECT * FROM referral_users WHERE email=\'' . make_safe( $_POST[ 'cemail' ] ) . '\' AND deleted=\'\' LIMIT 1' );
                    if ( mysqli_num_rows( $chk ) == 1 ) {
                        $hash = random_alphanum_string(12);
                        mysqli_query( $link, 'INSERT INTO referral_forgot (created, hash, email) VALUES(NOW(),\'' . $hash . '\',\'' . make_safe( $_POST[ 'cemail' ] ) . '\')' );
                        sendHTML(mysqli_result($chk,0,'email'), 'Password Reset for referrals.peregrinemfginc.com', 'Hello '.mysqli_result($chk,0,'name').',<br /><br />Please use the link below to log into your account:<br /><a href="'.root('recover/?hash='.$hash).'">'.root('recover/?hash='.$hash).'</a>');
                        $_SESSION[ 'error' ] = '<div class="alert alert-success mt-5"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Recovery email sent to: '.mysqli_result($chk,0,'email').'</div>';
                    } else {
                        $_SESSION[ 'error' ] = '<div class="alert alert-danger mt-5"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Sorry there does not seem to be an account associated with that email!</div>';
                        $_SESSION['forgot'] = TRUE;
                    }
                } else {
                    $_SESSION[ 'error' ] = '<div class="alert alert-danger mt-5"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Enter the email you\'re trying to log into!</div>';
                    $_SESSION['forgot'] = TRUE;
                }
            }
            header( 'Location: ' . root( 'login/' ) );
            exit();
            break;

        case 'create':
            $vars = array( 'cname', 'caddress', 'cuspa', 'cdz', 'cemail', 'cppay' );
            $bars = array( 'cname', 'caddress', 'cdz', 'cemail', 'cpassword', 'cppay' );
            save_posts( $vars );
            if ( blankCheck( $bars ) ) {
                $eChk = mysqli_query( $link, 'SELECT email FROM referral_users WHERE email=\'' . make_safe( $_POST[ 'cemail' ] ) . '\' AND deleted=\'\' LIMIT 1' );
                if ( mysqli_num_rows( $eChk ) == 0 ) {
                    $salt = hash( 'sha256', uniqid( mt_rand(), true ) . time() . strtolower( $_POST[ 'cemail' ] ) );
                    $hash = $salt . $_POST[ 'cpassword' ];
                    for ( $i = 0; $i < 100000; ++$i ) {
                        $hash = hash( 'sha256', $hash );
                    }
                    $hash = $salt . $hash;
                    if ( mysqli_query( $link, 'INSERT INTO referral_users (created, email, salt, password, name, address, uspa, dz, payment) VALUES(NOW(),\'' . make_safe( $_POST[ 'cemail' ] ) . '\',\'' . make_safe( $salt ) . '\',\'' . make_safe( $hash ) . '\',\'' . make_safe( $_POST[ 'cname' ] ) . '\',\'' . make_safe( $_POST[ 'caddress' ] ) . '\',\'' . make_safe( $_POST[ 'cuspa' ] ) . '\',\'' . make_safe( $_POST[ 'cdz' ] ) . '\',\'' . make_safe( $_POST[ 'cppay' ] ) . '\')' ) ) {
                        $_SESSION[ 'error' ] = '<div class="alert alert-success mt-5"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Referral Account Registered! Welcome '.$_POST[ 'cname' ].'</div>';
                        $to = $_POST[ 'cemail' ];
                        $subject = "Peregrine Manufacturing, Inc.";
                        $message = "
                        <html>
                        <head>
                        <title>Peregrine Manufacturing, Inc.</title>
                        </head>
                        <body>
                        <p>Hello " . make_safe( $_POST[ 'cname' ] ) . ",</p>
                        <p>This email is to confirm that you have succesfully signed up for Peregrine Manufacturing, Inc. referral program!</p>
                        <p>Registration Reminders:<p>
                        <p>Login Email: " . make_safe( $_POST[ 'cemail' ] ) . "</p>
                        <p>Login Password: " . make_safe( $_POST[ 'cpassword' ] ) . "</p>
                        <p>Thank You!</p>
                        <p>--</p>
                        <p>Peregrine Manufacturing, Inc.</p>
                        </body>
                        </html>
                        ";
                        $headers = "MIME-Version: 1.0" . "\r\n";
                        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                        $headers .= 'From: <admin@peregrinemfginc.com>' . "\r\n";
                        mail( $to, $subject, $message, $headers );
						
						//sendHTML('danc@peregrinemfginc.com','New Referral User','A new user has been created in the referral system: <br><br>'.make_safe( $_POST[ 'cname' ] ).'<br><br>'.make_safe( $_POST[ 'cemail' ] ));
						
                        unset_sess( $vars );
                        $_SESSION['rid'] = mysqli_insert_id( $link );
                        $_SESSION['cname'] = $_POST[ 'cname' ];
                        header( 'Location: ' . root( 'new/' ) );
                        exit();
                    } else {
                        $_SESSION[ 'error' ] = '<div class="alert alert-danger mt-5"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>There was an error creating your account!</div>';
                    }
                } else {
                    $_SESSION[ 'error' ] = '<div class="alert alert-danger mt-5"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>That email is already in use, please choose a different login email.</div>';
                }
            } else {
                $_SESSION[ 'error' ] = '<div class="alert alert-danger mt-5"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>You cannot leave fields blank!</div>';
            }
            header( 'Location: ' . $_SERVER[ 'HTTP_REFERER' ] );
            exit();
            break;
            
        case 'recover':
            if (!empty($_POST[ 'npassword' ]) && !empty($_POST[ 'cnpassword' ]) && $_POST[ 'npassword' ]==$_POST[ 'cnpassword' ]) {
                $chk = mysqli_query( $link, 'SELECT * FROM referral_forgot WHERE hash=\'' . make_safe( $_POST[ 'rhash' ] ) . '\' AND deleted=\'\' LIMIT 1' );
                if ( mysqli_num_rows( $chk ) == 1 ) {
                    mysqli_query($link,'UPDATE referral_forgot SET deleted=\'y\' WHERE id=\''.make_safe(mysqli_result($chk,0,'id')).'\'');
                    $salt = hash( 'sha256', uniqid( mt_rand(), true ) . time() . strtolower( mysqli_result($chk,0,'email') ) );
                    $hash = $salt . $_POST[ 'npassword' ];
                    for ( $i = 0; $i < 100000; ++$i ) {
                        $hash = hash( 'sha256', $hash );
                    }
                    $hash = $salt . $hash;
                    mysqli_query($link,'UPDATE referral_users SET salt=\''.$salt.'\',password=\''.$hash.'\' WHERE email=\''.make_safe(mysqli_result($chk,0,'email')).'\'');
                    $uchk = mysqli_query( $link, 'SELECT * FROM referral_users WHERE email=\'' . make_safe( mysqli_result($chk,0,'email') ) . '\' AND deleted=\'\' LIMIT 1' );
                    $_SESSION[ 'rid' ] = mysqli_result( $uchk, 0, 'id' );
                    $_SESSION[ 'cname' ] = mysqli_result( $uchk, 0, 'name' );
                    $_SESSION[ 'error' ] = '<div class="alert alert-success mt-5"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Welcome, ' . $_SESSION[ 'cname' ] . '! Your password was succesfully updated!</div>';
                    header( 'Location: ' . root( 'dashboard/' ) );
                    exit();
                } else {
                    $_SESSION[ 'error' ] = '<div class="alert alert-danger mt-5"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Recovery link has expired. Please try again.</div>';
                    $_SESSION['forgot'] = TRUE;
                    header( 'Location: ' . root('login/') );
                    exit();
                }
            } else {
                $_SESSION[ 'error' ] = '<div class="alert alert-danger mt-5"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Passwords do not match!</div>';
            }
            header( 'Location: ' . $_SERVER[ 'HTTP_REFERER' ] );
            exit();
            break;

        default:
            header( 'Location: ' . root() );
            exit();
            break;
    }
}
?>