<?
require_once '../inc/functions.php';

if ( $_SERVER[ 'SERVER_PORT' ] !== '443' ) {
    header( 'location: ' . root() );
    exit();
}

if ( $_SESSION[ 'uid' ] ) {
    switch ( $_GET[ 'page' ] ) {

        case 'account':
        $page = 'pages/account.php';
        $title .= '';
        break;
            
        default:
        $page = 'pages/new.php';
        $title .= '';
        break;
    }
} else {
    switch ( $_GET[ 'page' ] ) {

        default:
        $page = 'pages/login.php';
        $title .= '';
        break; 

    }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Peregrine Manufacturing Management Portal</title>
    <link href="<?=root()?>css/bootstrap.min.css" rel="stylesheet">
    <link href="<?=root()?>css/custom.css?v=<?=time()?>" rel="stylesheet">
    <link rel="stylesheet" href="https://www.ndevix.com/script/jquery/ui-1.12.1/jquery-ui.css" type="text/css" media="all"/>
    <link rel="stylesheet" href="https://www.ndevix.com/script/jquery/chosen-1.8.2/chosen.min.css" type="text/css" media="all"/>
    <script src="https://www.ndevix.com/script/jquery/jquery-3.2.1.min.js"></script>
    <script src="https://www.ndevix.com/script/jquery/ui-1.12.1/jquery-ui.js"></script>
    <script src="<?=root()?>js/bootstrap.min.js"></script>
    <script src="<?=root()?>js/functions.js?v=<?=time()?>"></script>
    <script src="https://www.ndevix.com/script/jquery/chosen-1.8.2/chosen.jquery.min.js"></script>
</head>

<body>
    <nav class="navbar navbar-expand-lg static-top navbar-inverse">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="<?=root('images/logo.png'); ?>" height="30" alt="">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fas fa-bars"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="<?=root(); ?>">Home
                            <span class="sr-only">(current)</span>
                        </a>
                    </li>
                    <? if($_SESSION['rid']) { ?>
                    <li>
                        <a href="<?=root()?>do/logout/">Logout</a>
                    </li>
                    <? } ?>
                </ul>
            </div>
        </div>
    </nav>

    <? include $page; ?>  
</body>
</html>