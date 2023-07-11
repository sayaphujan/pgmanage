<?
$title = 'Peregrine Manufacturing, Inc. - Masks';
require_once 'inc/functions.php';

if ( $_SERVER[ 'SERVER_PORT' ] !== '443' ) {
    header( 'location: ' . root() );
    exit();
}

switch ( $_GET[ 'page' ] ) {

	case 'checkout':
		$page = 'pages/checkout.php';
		$title .= '';
		break;
		
	case 'confirmation':
		$page = 'pages/confirmation.php';
		$title .= '';
		break;
		
	case 'order-beta':
		$page = 'pages/order-beta.php';
		$title .= '';
		break;

	default:
		$page = 'pages/order.php';
		$title .= '';
		break;
		
}

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.structure.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.theme.css" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css"/>
    <link rel="stylesheet" href="<?=root('master.css'); ?>"/>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.0/moment-with-locales.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <title>
        <?=$title; ?>
    </title>
</head>

<body>
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="<?=root(); ?>">
                <img src="<?=root('images/logo.png'); ?>" height="30" alt=""> Peregrine Manufacturing, Inc.
            </a>
            <? if (isset($_SESSION['rid'])) { ?>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="<?=root()?>">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?=root('do/logout/')?>">Logout</a>
                        </li>
                    </ul>
                </div>
            <? } else { ?>
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        
                    </li>
                </ul>
            <? } ?>
        </div>
    </nav>
    <header class="py-5 bg-image-full">
        <div style="height: 100px;"></div>
    </header>
	

    <? 
    if ($_SESSION['error']) {
        echo '<div class="container">'.$_SESSION['error'].'</div>';
        unset($_SESSION['error']);
    }
    include $page;
    ?>

    <br/>
</body>
</html>