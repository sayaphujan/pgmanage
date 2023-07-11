<?
//if($_SERVER['REMOTE_ADDR']!=='173.9.220.189') {echo 'updates in progress'; exit();}

require_once 'inc/functions.php';

if($_SERVER['SERVER_PORT']!=='443') {
	header('location: '.root());
	exit();
}

if($_SESSION['uid']) {

	switch ($_GET['page']) {
		
		default:
		$page = 'pages/projects.php';
		$title .= 'Projects';
		break;
		
		case 'copy_project':
		$page = 'pages/copy_project.php';
		$title .= 'Duplicate Project';
		break;
		
		case 'add_project':
		$page = 'pages/add_project.php';
		$title .= 'Add Project';
		break;
		
		case 'edit_project':
		$page = 'pages/edit_project.php';
		$title .= 'Edit Project';
		break;
		
		case 'project':
		$page = 'pages/project.php';
		$title .= 'Project';
		break;
		
		case 'project_step':
		$page = 'pages/project_step.php';
		$title .= 'Project';
		break;
		
		case 'final_design':
		$page = 'pages/final_design.php';
		$title .= 'Final Design';
		break;
		
		case 'em_breakout':
		$page = 'pages/em_breakout.php';
		$title .= 'Embroidery Breakout';
		break;
		
		case 'em_breakout_pdf':
		$page = 'pages/em_breakout_pdf.php';
		$title .= 'Embroidery Breakout';
		break;
		
		case 'products':
		$page = 'pages/products.php';
		$title .= 'Products';
		break;
		
		case 'product':
		$page = 'pages/product.php';
		$title .= 'Product';
		break;
		
		case 'customers':
		$page = 'pages/customers.php';
		$title .= 'Customers';
		break;
		
		case 'add_customer':
		$page = 'pages/add_customer.php';
		$title .= 'Add Customer';
		break;
		
		case 'edit_customer':
		$page = 'pages/edit_customer.php';
		$title .= 'Edit Customer';
		break;
		
		case 'colors':
		$page = 'pages/colors.php';
		$title .= 'Colors';
		break;
		
		case 'add_color':
		$page = 'pages/add_color.php';
		$title .= 'Add Color';
		break;
		
		case 'edit_color':
		$page = 'pages/edit_color.php';
		$title .= 'Edit Customer';
		break;
		
		case 'users':
		$page = 'pages/users.php';
		$title .= 'Customers';
		break;
		
		case 'inspectors':
		$page = 'pages/inspectors.php';
		$title .= 'Inspectors';
		break;
		
		case 'add_inspector':
		$page = 'pages/add_inspector.php';
		$title .= 'Add Inspector';
		break;
		
		case 'add_user':
		$page = 'pages/add_user.php';
		$title .= 'Add User';
		break;
		
		case 'edit_user':
		$page = 'pages/edit_user.php';
		$title .= 'Edit User';
		break;
		
		case 'edit_inspector':
		$page = 'pages/edit_inspector.php';
		$title .= 'Edit Inspector';
		break;
		
		case 'copy_product':
		$page = 'pages/copy_product.php';
		$title .= 'Copy Product';
		break;
		
		case 'demos':
		$page = 'pages/demos.php';
		$title .= 'Demos';
		break;
		
		case 'add_demo':
		$page = 'pages/add_demo.php';
		$title .= 'Create Demo Request';
		break;
		
		case 'demo':
		$page = 'pages/demo.php';
		$title .= 'Demo Request';
		break;
		
		case 'generate':
		$page = 'pages/generate.php';
		$title .= 'Generate Documents';
		break;
		
		case 'upload_project_data':
		$page = 'pages/upload_project_data.php';
		$title .= 'Data Upload';
		break;
		
		case 'reporting':
		$page = 'pages/reporting.php';
		$title .= 'Reporting';
		break;
		
		case 'production_report':
		$page = 'pages/production_report.php';
		$title .= 'Production Report';
		break;
		
		case 'print_report':
		$page = 'pages/print_report.php';
		$title .= 'Print Production Report';
		break;
		
		case 'settings':
		//$page = 'pages/settings.php';
		$page = 'pages/list_batch_lot.php';
		$title .= 'Settings';
		break;
		
		case 'add_batch_lot':
		$page = 'pages/add_settings.php';
		$title .= 'Settings';
		break;
		
		case 'input_batch_lot':
		$page = 'pages/add_batch_lot.php';
		$title .= 'Batch Lot';
		break;
		
		case 'edit_batch_lot':
		$page = 'pages/edit_batch_lot.php';
		$title .= 'Batch Lot';
		break;
		
		case 'list_batch_lot':
		$page = 'pages/list_batch_lot.php';
		$title .= 'Batch Lot';
		break;
		
        case 'check_batch_lot_all':
		$page = 'pages/check_batch_lot_all.php';
		$title .= 'Check Batch Lot';
		break;
    
		case 'check_batch_lot':
		$page = 'pages/check_batch_lot.php';
		$title .= 'Check Batch Lot';
		break;
		
		case 'check_batch_lot_new':
		$page = 'pages/check_batch_lot_noredundant.php';
		$title .= 'Check Batch Lot';
		break;
		
		case 'engineering_messages':
		$page = 'pages/engineering_messages.php';
		$title .= 'Engineering Messages';
		break;
		
		case 'referrals':
		$page = 'pages/referrals.php';
		$title .= 'Referrals';
		break;
		
		case 'referral_users':
		$page = 'pages/referral_users.php';
		$title .= 'Referrals';
		break;
		
		case 'referral':
		$page = 'pages/referral.php';
		$title .= 'Referrals';
		break;
		
		case 'edit_referral':
		$page = 'pages/edit_referral.php';
		$title .= 'Edit Referrals';
		break;
		
		case 'edit_referral_user':
		$page = 'pages/edit_referral_user.php';
		$title .= 'Edit Referrals User';
		break;
		
		case 'masks':
		$page = 'pages/masks.php';
		$title .= 'Masks';
		break;
		
		case 'mask_order':
		$page = 'pages/mask_order.php';
		$title .= 'Mask Order';
		break;
		
		case 'mask_production':
		$page = 'pages/mask_production.php';
		$title .= 'Masks';
		break;
		
		case 'mask_production_2':
		$page = 'pages/mask_production_2.php';
		$title .= 'Masks';
		break;
		
		case 'orders':
		$page = 'pages/orders.php';
		$title .= 'Orders';
		break;
		
		case 'serial_number':
		$page = 'pages/serial_number.php';
		$title .= 'Serial Number';
		break;
		
		case 'lock_sn':
		$page = 'pages/lock_sn.php';
		$title .= 'Lock Serial Number';
		break;
		
		case 'add_lock_sn':
		$page = 'pages/add_lock_sn.php';
		$title .= 'Lock Serial Number';
		break;
		
		case 'test_serial':
		$page = 'pages/test_serial.php';
		$title .= 'Pull Order';
		break;
		
		case 'pull_order':
		$page = 'pages/pull_order.php';
		$title .= 'Pull Order';
		break;
		
		case 'pull_order_falkyn':
		$page = 'pages/pull_order_falkyn.php';
		$title .= 'Pull Order';
		break;
		
		case 'test_pull_order':
		$page = 'pages/test_pull_order.php';
		$title .= 'Pull Order';
		break;
		
		case 'pull_products':
		$page = 'pages/pull_products.php';
		$title .= 'Pull Products';
		break;
		
		case 'convert':
		$page = 'pages/convert.php';
		$title .= 'Convert';
		break;
		
		case 'render-product-traveler':
		$page = 'pages/render_product_traveler.php';
		$title .= 'Convert';
		break;
    
    case 'convert_emb':
		$page = 'pages/convert_emb.php';
		$title .= 'Convert';
		break;
		
		case 'render':
		$page = 'pages/render.php';
		$title .= 'Render';
		break;
		
		case 'render_final_design':
		$page = 'pages/render_final_design.php';
		$title .= 'Render Final Design';
		break;
		
		case 'csv_reader':
		$page = 'pages/csv_reader.php';
		$title .= 'CSV Reader';
		break;
		
	}
	
} else {

	switch ($_GET['page']) {
		
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
	<link rel="stylesheet" href="<?=root()?>script/jquery/ui-1.12.1/jquery-ui.css" type="text/css" media="all" />
	<link rel="stylesheet" href="<?=root()?>script/jquery/chosen-1.8.2/chosen.min.css" type="text/css" media="all" />
	<script src="<?=root()?>script/jquery/jquery-3.2.1.min.js"></script>
    <script src="<?=root()?>script/jquery/ui-1.12.1/jquery-ui.js"></script>
	<script src="<?=root()?>js/bootstrap.min.js"></script>
	<script src="<?=root()?>js/functions.js?v=<?=time()?>"></script>
	<script src="https://www.ndevix.com/script/jquery/chosen-1.8.2/chosen.jquery.min.js"></script>
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.24/datatables.min.css"/>
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.24/datatables.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.min.css"/>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/notify/0.4.2/notify.js"></script>
    <script type="text/javascript" src="<?php echo root();?>savesvgaspng.js"></script>
    <script src="/peregrinemanage/js/svg-pan-zoom.js"></script>
  </head>
  <body>
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container" style="width: 100%">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="<?=root()?>"><img src="images/logo.png" id="logo"></a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
			<? 
            if ($_SESSION['uid']) { 
				
                if (check_access('production')) {
                    echo '<li '.($_GET['page']=='projects' ? ' class="active"' : '').'><a href="'.root().'?page=projects">Production'.($_SESSION['new_projects']>0 ? ' - <span class="text-info">'.$_SESSION['new_projects'].' New</span>':'').'</a></li>
                    <li '.($_GET['page']=='production_report' ? ' class="active"' : '' ).'><a href="'.root().'?page=production_report">Production Report</a></li>
					<li '.($_GET['page']=='orders' ? ' class="active"' : '' ).'><a href="'.root().'?page=orders">Orders</a></li>
                    <li '.($_GET['page']=='reporting' ? ' class="active"' : '' ).'><a href="'.root().'?page=settings">Materials</a></li>
					<!--<li '.($_GET['page']=='masks' ? ' class="active"' : '' ).'><a href="'.root().'?page=masks">Masks</a></li>-->';

                }

                echo '<li '.($_GET['page']=='customers' ? ' class="active"' : '').'><a href="'.root().'?page=customers">Customers</a></li>';

                if (check_access('demos')) {
                //	echo '<li '.($_GET['page']=='demos' ? ' class="active"' : '').'><a href="'.root().'?page=demos">Demo Requests</a></li>';
                }

                if (check_access('admin')) {
                    echo'
                    <li '.($_GET['page']=='products' ? ' class="active"' : '' ).'><a href="'.root().'?page=products">Products</a></li>
                    <li '.($_GET['page']=='users' ? ' class="active"' : '' ).'><a href="'.root().'?page=users">Staffs</a></li>
                    <li '.($_GET['page']=='inspectors' ? ' class="active"' : '' ).'><a href="'.root().'?page=inspectors">Inspectors</a></li>

                    <li '.($_GET['page']=='reporting' ? ' class="active"' : '' ).'><a href="'.root().'?page=reporting">Reporting</a></li>
                    <li '.($_GET['page']=='reporting' ? ' class="active"' : '' ).'><a href="'.root().'?page=engineering_messages">Logs</a></li>
                    <li '.($_GET['page']=='referrals' ? ' class="active"' : '' ).'><a href="'.root().'?page=referrals">Referrals</a></li>
                    <li '.($_GET['page']=='serial_number' ? ' class="active"' : '' ).'><a href="'.root().'?page=serial_number">Master SN Logs</a></li>
					
                    ';
                }

            } 
			?>
          </ul>
		  <ul class="nav navbar-nav navbar-right">
		  <? if($_SESSION['uid']) { ?>
			<li><a href="<?=root()?>do/logout/">Exit</a></li>
		  <? } ?>
		  </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>
	
    <div class="container main-page">
      
		<? include $page; ?>
     
    </div>
  </body>
</html>
