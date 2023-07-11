<?
//ini_set('display_errors', 1); 
//ini_set('display_startup_errors', 1); 
//error_reporting(E_ALL);

if(!check_access('production')) exit();
?>
<style>
.red
{
  color: red;
  font-weight: bolder;
}
</style>
<div class="col-md-8">&nbsp;</div>
<div class="col-md-4" style="float:right;margin:0px">
    <div class="col-md-6" style="width:auto;padding:3px">
		<br />
		<strong>Serial</strong>
	</div>
	<div class="col-md-6" style="width:auto">
        <br />
       <input type="text" placeholder="search" id="livesearch" class="livesearch" <?=($_GET['sn']!='' ? 'value="'.$_GET['sn'].'"' : '')?>>
    </div>
</div>
<div class="clear"></div>
<br/>
<?
        $pq = mysqli_query($link, 'SELECT * FROM projects WHERE serial=\''.sf($_GET['sn']).'\' AND status != "deleted"');

        $project = mysqli_fetch_assoc($pq);
        
        
        
        $project_meta = json_decode($project['metadata'], true);
        
        $pq = mysqli_query($link, 'SELECT * FROM products WHERE id=\''.sf($project['product']).'\'');
        $product = mysqli_fetch_assoc($pq);
        
        $cq = mysqli_query($link, 'SELECT * FROM customers WHERE id=\''.sf($project['customer']).'\'');
        $customer = mysqli_fetch_assoc($cq);
        
        //this part is kindof a hack, but it works
        $step_q = mysqli_query($link, 'SELECT 
                                          project_steps.id as step_id
                                          , project_steps.name as step_name
                                          , project_steps.parts as step_parts
                                          , project_steps.metadata as step_meta
                                          FROM project_steps 
                                          LEFT JOIN product_steps ON project_steps.step = product_steps.id 
                                          LEFT JOIN projects ON projects.id = project_steps.project 
                                          WHERE projects.serial=\''.sf($_GET['sn']).'\' AND projects.status != "deleted"
                                          ');
                                          

    echo '
    <strong>'.$project['name'].'</strong>
    <div class="project_step_input">
          <div class="row parts-table parts-table-heading row-eq-height">
          <div class="col-md-3 part-heading">Step</div>
					<div class="col-md-2 part-heading">Part Name</div>
					<div class="col-md-2 part-heading">Batch Lot</div>
					<div class="col-md-5 part-heading">Part Info</div>
				</div>';        
        while($step = mysqli_fetch_assoc($step_q))
        {
            $query = 'SELECT project_sub_steps.*
                                , project_parts.name as part_name
                                , project_parts.batch_lot
                                , project_parts.variables as part_variables
                                , product_parts.batch_lot as capture_batch_lot 
                                FROM project_sub_steps 
                                LEFT JOIN project_parts ON project_sub_steps.part = project_parts.part AND project_parts.project = \''.sf($project['id']).'\' 
                                LEFT JOIN product_parts ON project_sub_steps.part = product_parts.id 
                                WHERE project_sub_steps.step=\''.sf($step['step_id']).'\' 
                                ORDER BY s_order ASC';
                 
            $ssq = mysqli_query($link, $query);

            while($sub_step = mysqli_fetch_assoc($ssq))
            {
                
              $pv = array();
              
              if($sub_step['part'] == '' || empty($sub_step['part'] == '' ) || $sub_step['part'] == null || $sub_step['part'] == '0'  )
              {
                  if($project['product'] == '70')
                  {
                      switch ($sub_step['name'])
                      {
                        case 'Cut 15a- Back Pad Outer Layer' :
                            $sub_step['part'] = '636';
                            $sub_step['part_project'] = '403';
                            $sub_step['part_name'] = '15A BACKPAD OUTSIDE LAYER (EM5)';
                            break;
                            
                        case 'Cut 15, Back Pad  4FL':
                            $sub_step['part'] = '637';
                            $sub_step['part_project'] = '404';
                            $sub_step['part_name'] = '15 BACKPAD 1/4';
                            break;
                            
                        case 'Cut OTS Binding Peice':
                            $sub_step['part'] = '638';
                            $sub_step['part_project'] = '405';
                            $sub_step['part_name'] = 'OTS BINDING PIECE';
                            break;
                            
                        case 'Cut 14a/14b, Lower Backpad Binding Piece':
                            $sub_step['part'] = '639';
                            $sub_step['part_project'] = '406';
                            $sub_step['part_name'] = '14A AND 14B LOWER BACKPAD BINDING PIECE';
                            break;
                            
                        case 'Cut 15c, Lateral Pad':
                            $sub_step['part'] = '679';
                            $sub_step['part_project'] = '440';
                            $sub_step['part_name'] = '15C LATERAL PAD LEFT COLOR';
                            break;
                            
                        case 'Cut 15b, Lateral Pad Inside Layer':
                            $sub_step['part'] = '640';
                            $sub_step['part_project'] = '407';
                            $sub_step['part_name'] = '15B LATERAL PAD INSIDE LAYER';
                            break;
                            
                        case 'Cut 14c/14b, Lateral Stabilizers':
                            $sub_step['part'] = '652';
                            $sub_step['part_project'] = '408';
                            $sub_step['part_name'] = '14C LATERAL STABILIZER OUT SIDE COLOR LEFT (EM15)';
                            break;
                            
                        case 'Cut 16 Left,  Leg Pad, Left':
                            $sub_step['part'] = '683';
                            $sub_step['part_project'] = '0';
                            $sub_step['part_name'] = '16a LEGPADS LEFT (EM9)';
                            break;
                            
                        case 'Cut 16 Right, Leg Pad, Right':
                            $sub_step['part'] = '684';
                            $sub_step['part_project'] = '0';
                            $sub_step['part_name'] = '16b LEGPADS RIGHT (EM10)';
                            break;
                            
                        case 'Cut 16 Left, LP Sleeve':
                            $sub_step['part'] = '645';
                            $sub_step['part_project'] = '407';
                            $sub_step['part_name'] = '15B LATERAL PAD INSIDE LAYER';
                            break;
                            
                        case 'Cut 1 Left,  Ring Cover Left':
                            $sub_step['part'] = '680';
                            $sub_step['part_project'] = '441';
                            $sub_step['part_name'] = '1L RING COVER LEFT (EM1)';
                            break;
                            
                        case 'Cut 1 Right, Ring Cover Right':
                            $sub_step['part'] = '641';
                            $sub_step['part_project'] = '409';
                            $sub_step['part_name'] = '1R RING COVER RIGHT (EM6)';
                            break;
                            
                        case 'Cut 5, Reserve Pin Cover Flap':
                            $sub_step['part'] = '642';
                            $sub_step['part_project'] = '410';
                            $sub_step['part_name'] = '5 RES PIN COVER FLAP (EM4)';
                            break;
                            
                        case 'Cut 2, Inside Mag Yoke':
                            $sub_step['part'] = '643';
                            $sub_step['part_project'] = '411';
                            $sub_step['part_name'] = '2 INSIDE YOKE';
                            break;
                            
                        case 'Cut 3, Yoke':
                            $sub_step['part'] = '647';
                            $sub_step['part_project'] = '414';
                            $sub_step['part_name'] = '3 YOKE UNDERLAY';
                            break;
                            
                        case 'Cut RSL Channel':
                            $sub_step['part'] = '648';
                            $sub_step['part_project'] = '415';
                            $sub_step['part_name'] = 'RSL CHANNEL';
                            break;
                            
                        case 'Cut Reserve Pin Cover Flap Nylotron':
                            $sub_step['part'] = '642';
                            $sub_step['part_project'] = '410';
                            $sub_step['part_name'] = '5 RES PIN COVER FLAP (EM4)';
                            break;
                            
                        case 'GATHER BOC POCKET FROM STOCK':
                            $sub_step['part'] = '659';
                            $sub_step['part_project'] = '423';
                            $sub_step['part_name'] = '13 BOC POCKET';
                            break;
                            
                        case 'Cut 11, Main Container':
                            $sub_step['part'] = '651';
                            $sub_step['part_project'] = '416';
                            $sub_step['part_name'] = '11 MAIN CONTAINER';
                            break;
                            
                        case 'Cut 4 Left, Riser Cover L1':
                            $sub_step['part'] = '653';
                            $sub_step['part_project'] = '417';
                            $sub_step['part_name'] = '4L RISER COVER L (EM7)';
                            break;
                            
                        case 'Cut 4 Right, Riser Cover R1':
                            $sub_step['part'] = '654';
                            $sub_step['part_project'] = '418';
                            $sub_step['part_name'] = '4R RISER COVER R (EM8)';
                            break;
                            
                        case 'Cut 4 Left, Riser Cover L2':
                            $sub_step['part'] = '653';
                            $sub_step['part_project'] = '417';
                            $sub_step['part_name'] = '4L RISER COVER L (EM7)';
                            break;
                            
                        case 'Cut 4 Right, Riser Cover R2':
                            $sub_step['part'] = '654';
                            $sub_step['part_project'] = '418';
                            $sub_step['part_name'] = '4R RISER COVER R (EM8)';
                            break;
                            
                        case 'Cut Nub Pocket':
                            $sub_step['part'] = '655';
                            $sub_step['part_project'] = '419';
                            $sub_step['part_name'] = 'NUB POCKET';
                            break;
                            
                        case 'Cut 17, Cut Release Handle Pocket':
                            $sub_step['part'] = '656';
                            $sub_step['part_project'] = '420';
                            $sub_step['part_name'] = '17 RELEASE POCKET (EM13)';
                            break;
                            
                        case 'Cut 18, Cut Reserve Handle Pocket':
                            $sub_step['part'] = '657';
                            $sub_step['part_project'] = '421';
                            $sub_step['part_name'] = '18 RESERVE POCKET (EM14)';
                            break;
                        
                        case 'Cut 10, Reserve Container':
                            $sub_step['part'] = '660';
                            $sub_step['part_project'] = '424';
                            $sub_step['part_name'] = '10 RESERVE CONTAINER LEFT';
                            break;
                            
                        case 'Cut, Reserve Linestow Cover':
                            $sub_step['part'] = '661';
                            $sub_step['part_project'] = '425';
                            $sub_step['part_name'] = 'LINESTOW COVER';
                            break;
                            
                        case 'Cut 11a, Main #2 Flap':
                            $sub_step['part'] = '651';
                            $sub_step['part_project'] = '416';
                            $sub_step['part_name'] = '11 MAIN CONTAINER';
                            break;
                            
                        case 'Cut 7, Mid Stripe Point':
                            $sub_step['part'] = '665';
                            $sub_step['part_project'] = '427';
                            $sub_step['part_name'] = '7 MID STRIPE POINT';
                            break;
                            
                        case 'Cut 8, Left Side Stripe':
                            $sub_step['part'] = '666';
                            $sub_step['part_project'] = '428';
                            $sub_step['part_name'] = '8 LEFT SIDE MID FLAP';
                            break;
                            
                        case 'Cut 9, Right Side Stripe':
                            $sub_step['part'] = '667';
                            $sub_step['part_project'] = '429';
                            $sub_step['part_name'] = '9 RIGHT SIDE MID FLAP';
                            break;
                            
                        case 'Cut, 8a, Lower Left Side Stripe':
                            $sub_step['part'] = '668';
                            $sub_step['part_project'] = '430';
                            $sub_step['part_name'] = '8A OUTER L SIDE STRIPE';
                            break;
                            
                        case 'Cut 9a, Lower Right Side Stripe':
                            $sub_step['part'] = '669';
                            $sub_step['part_project'] = '431';
                            $sub_step['part_name'] = '9A OUTER R SIDE STRIPE';
                            break;
                            
                        case 'Cut Snake Guard Stiffener, Left/Right':
                            $sub_step['part'] = '671';
                            $sub_step['part_project'] = '432';
                            $sub_step['part_name'] = 'SNAKEGUARD 8-9 STIFFENER';
                            break;
                            
                        case 'Cut Mid Flap Foam insert 8FL':
                            $sub_step['part'] = '672';
                            $sub_step['part_project'] = '433';
                            $sub_step['part_name'] = 'MIDFLAP FOAM INSERT';
                            break;
                            
                        case 'Cut or gather from stock, Reserve Pin Flap':
                            $sub_step['part'] = '673';
                            $sub_step['part_project'] = '434';
                            $sub_step['part_name'] = 'RESERVE PIN FLAP';
                            break;
                            
                        case 'Cut 12 or gather from stock, Main Pin Cover':
                            $sub_step['part'] = '674';
                            $sub_step['part_project'] = '435';
                            $sub_step['part_name'] = '12 MAIN PIN COVER';
                            break;
                            
                        case 'Cut 6 or gather from stock, Reserve PC Top':
                            $sub_step['part'] = '675';
                            $sub_step['part_project'] = '436';
                            $sub_step['part_name'] = '6 RES PC TOP CAP';
                            break;
                            
                        case 'Left Ring Cover':
                            $sub_step['part'] = '680';
                            $sub_step['part_project'] = '441';
                            $sub_step['part_name'] = '1L RING COVER LEFT (EM1)';
                            break;
                            
                        case 'Right Ring Cover':
                            $sub_step['part'] = '641';
                            $sub_step['part_project'] = '409';
                            $sub_step['part_name'] = '1R RING COVER RIGHT (EM6)';
                            break;
                            
                        case 'Back Pad':
                            $sub_step['part'] = '636';
                            $sub_step['part_project'] = '403';
                            $sub_step['part_name'] = '15A BACKPAD OUTSIDE LAYER (EM5)';
                            break;
                            
                        case 'Main Container LEFT':
                            $sub_step['part'] = '664';
                            $sub_step['part_project'] = '426';
                            $sub_step['part_name'] = '11A MAIN #2 (EM2)';
                            break;
                            
                        case 'Main Container RIGHT':
                            $sub_step['part'] = '663';
                            $sub_step['part_project'] = '0';
                            $sub_step['part_name'] = '11B MAIN #3 (EM3)';
                            break;
                            
                         case 'Reserve Pin Cover Flap':
                            $sub_step['part'] = '642';
                            $sub_step['part_project'] = '410';
                            $sub_step['part_name'] = '5 RES PIN COVER FLAP (EM4)';
                            break;
                            
                        case 'Riser Cover, Left':
                            $sub_step['part'] = '653';
                            $sub_step['part_project'] = '417';
                            $sub_step['part_name'] = '4L RISER COVER L (EM7)';
                            break;
                            
                         case 'Riser Cover, Right':
                            $sub_step['part'] = '654';
                            $sub_step['part_project'] = '418';
                            $sub_step['part_name'] = '4R RISER COVER R (EM8)';
                            break;
                            
                        case 'Release Handle Pocket':
                            $sub_step['part'] = '656';
                            $sub_step['part_project'] = '420';
                            $sub_step['part_name'] = '17 RELEASE POCKET (EM13)';
                            break;
                            
                         case 'Reserve Handle Pocket':
                            $sub_step['part'] = '657';
                            $sub_step['part_project'] = '421';
                            $sub_step['part_name'] = '18 RESERVE POCKET (EM14)';
                            break;
                            
                         case 'Release Handle':
                            $sub_step['part'] = '656';
                            $sub_step['part_project'] = '420';
                            $sub_step['part_name'] = '17 RELEASE POCKET (EM13)';
                            break;
                            
                         case 'Reserve Fit Right Handle':
                            $sub_step['part'] = '657';
                            $sub_step['part_project'] = '421';
                            $sub_step['part_name'] = '18 RESERVE POCKET (EM14)';
                            break;
                            
                        case 'PROCESS BIN 1, BACK PAD SUB ASSEMBLY':
                            $sub_step['part'] = '636';
                            $sub_step['part_project'] = '403';
                            $sub_step['part_name'] = '15A BACKPAD OUTSIDE LAYER (EM5)';
                            break;
                            
                         case 'PROCESS BIN 2, MAIN CONTAINER SUB ASSEMBLY':
                            $sub_step['part'] = '648';
                            $sub_step['part_project'] = '415';
                            $sub_step['part_name'] = 'RSL CHANNEL';
                            break;
                            
                         case 'PROCESS BIN 3, RESERVE CONTAINER SUB ASSEMBLY':
                            $sub_step['part'] = '660';
                            $sub_step['part_project'] = '424';
                            $sub_step['part_name'] = '10 RESERVE CONTAINER LEFT';
                            break;
                            
                         case 'Prep 1, Reserve Container':
                            $sub_step['part'] = '660';
                            $sub_step['part_project'] = '424';
                            $sub_step['part_name'] = '10 RESERVE CONTAINER LEFT';
                            break;
                            
                         case 'Prep 1, Reserve Container':
                            $sub_step['part'] = '660';
                            $sub_step['part_project'] = '424';
                            $sub_step['part_name'] = '10 RESERVE CONTAINER LEFT';
                            break;

                         case 'Prep 1, Leg Pads':
                            $sub_step['part'] = '682';
                            $sub_step['part_project'] = '0';
                            $sub_step['part_name'] = '16 LEGPADS';
                            break;

                         case 'Prep 1, Main Container':
                            $sub_step['part'] = '651';
                            $sub_step['part_project'] = '416';
                            $sub_step['part_name'] = '11 MAIN CONTAINER';
                            break;

                         case 'Prep 1, Back Pad':
                            $sub_step['part'] = '636';
                            $sub_step['part_project'] = '403';
                            $sub_step['part_name'] = '15A BACKPAD OUTSIDE LAYER (EM5)';
                            break;

                        case 'Prep 1, Reserve Pin Cover Flap #5':
                            $sub_step['part'] = '642';
                            $sub_step['part_project'] = '410';
                            $sub_step['part_name'] = '5 RES PIN COVER FLAP (EM4)';
                            break;

                         case 'CPrep 1, Reserve Pin Flap':
                            $sub_step['part'] = '673';
                            $sub_step['part_project'] = '434';
                            $sub_step['part_name'] = 'RESERVE PIN FLAP';
                            break;

                        case 'Prep 1, Lateral Pad':
                            $sub_step['part'] = '679';
                            $sub_step['part_project'] = '440';
                            $sub_step['part_name'] = '15C LATERAL PAD LEFT COLOR';
                            break;

                        case 'Prep 1, Lateral Stabilizers':
                            $sub_step['part'] = '652';
                            $sub_step['part_project'] = '408';
                            $sub_step['part_name'] = '14C LATERAL STABILIZER OUT SIDE COLOR LEFT (EM15)';
                            break;

                        case 'Prep 1, Riser Covers Left/Right':
                            $sub_step['part'] = '653';
                            $sub_step['part_project'] = '417';
                            $sub_step['part_name'] = '4L RISER COVER L (EM7)';
                            break;

                        case 'Prep 1, Main #2':
                            $sub_step['part'] = '664';
                            $sub_step['part_project'] = '426';
                            $sub_step['part_name'] = '11A MAIN #2 (EM2)';
                            break;

                        case 'Prep 1, Main Pin Cover #12':
                            $sub_step['part'] = '674';
                            $sub_step['part_project'] = '435';
                            $sub_step['part_name'] = '12 MAIN PIN COVER';
                            break;

                        case 'Prep 1, Release Handle Pocket':
                            $sub_step['part'] = '656';
                            $sub_step['part_project'] = '420';
                            $sub_step['part_name'] = '17 RELEASE POCKET (EM13)';
                            break;

                        case 'Prep 1, Reserve Handle Pocket':
                            $sub_step['part'] = '657';
                            $sub_step['part_project'] = '421';
                            $sub_step['part_name'] = '18 RESERVE POCKET (EM14)';
                            break;

                        case 'Bind Main Container':
                            $sub_step['part'] = '651';
                            $sub_step['part_project'] = '416';
                            $sub_step['part_name'] = '11 MAIN CONTAINER';
                            break;

                         case 'Bind Main Pin Cover Flap':
                            $sub_step['part'] = '674';
                            $sub_step['part_project'] = '435';
                            $sub_step['part_name'] = '12 MAIN PIN COVER';
                            break;

                        case 'Bind Ring Covers':
                            $sub_step['part'] = '680';
                            $sub_step['part_project'] = '441';
                            $sub_step['part_name'] = '1L RING COVER LEFT (EM1)';
                            break;

                        case 'Bind Lateral Stabilizers':
                            $sub_step['part'] = '652';
                            $sub_step['part_project'] = '408';
                            $sub_step['part_name'] = '14C LATERAL STABILIZER OUT SIDE COLOR LEFT (EM15)';
                            break;

                        case 'Bind Reserve Pin Cover Flap':
                            $sub_step['part'] = '642';
                            $sub_step['part_project'] = '410';
                            $sub_step['part_name'] = '5 RES PIN COVER FLAP (EM4)';
                            break;

                        case 'Bind Lower Back Pad':
                            $sub_step['part'] = '639';
                            $sub_step['part_project'] = '406';
                            $sub_step['part_name'] = '14A AND 14B LOWER BACKPAD BINDING PIECE';
                            break;

                        case 'Bind RSL Sleeve':
                            $sub_step['part'] = '648';
                            $sub_step['part_project'] = '415';
                            $sub_step['part_name'] = 'RSL CHANNEL';
                            break;

                        case 'Bind Fit Right Pockets':
                            $sub_step['part'] = '656';
                            $sub_step['part_project'] = '420';
                            $sub_step['part_name'] = '17 RELEASE POCKET (EM13)';
                            break;

                        case 'Bind Reserve Line Stow Cover':
                            $sub_step['part'] = '657';
                            $sub_step['part_project'] = '421';
                            $sub_step['part_name'] = '18 RESERVE POCKET (EM14)';
                            break;

                        case 'BIND LATERAL STABILIZERS':
                            $sub_step['part'] = '652';
                            $sub_step['part_project'] = '408';
                            $sub_step['part_name'] = '14C LATERAL STABILIZER OUT SIDE COLOR LEFT (EM15)';
                            break;

                        case 'BIND LEGPADS':
                            $sub_step['part'] = '682';
                            $sub_step['part_project'] = '0';
                            $sub_step['part_name'] = '16 LEGPADS';
                            break;

                        case 'BIND YOKE':
                            $sub_step['part'] = '647';
                            $sub_step['part_project'] = '414';
                            $sub_step['part_name'] = '3 YOKE UNDERLAY';
                            break;

                        case 'BIND MID FLAP, MAIN #2':
                            $sub_step['part'] = '672';
                            $sub_step['part_project'] = '433';
                            $sub_step['part_name'] = 'MIDFLAP FOAM INSERT';
                            break;

                        case 'BIND HANDLE POCKETS (if applicable)':
                            $sub_step['part'] = '656';
                            $sub_step['part_project'] = '420';
                            $sub_step['part_name'] = '17 RELEASE POCKET (EM13)';
                            break;

                        case 'BIND INNER YOKE':
                            $sub_step['part'] = '643';
                            $sub_step['part_project'] = '411';
                            $sub_step['part_name'] = '2 INSIDE YOKE';
                            break;

                       case 'BIND RESERVE CONTAINER SUB ASSEMBLY':
                            $sub_step['part'] = '660';
                            $sub_step['part_project'] = '424';
                            $sub_step['part_name'] = '10 RESERVE CONTAINER LEFT';
                            break;     

                        case 'BIND BACK PAD SUB ASSEMBLY' :
                            $sub_step['part'] = '636';
                            $sub_step['part_project'] = '403';
                            $sub_step['part_name'] = '15A BACKPAD OUTSIDE LAYER (EM5)';
                            break;

                        case 'BAR TACK,  MAIN CONTAINER  X 5':
                            $sub_step['part'] = '651';
                            $sub_step['part_project'] = '416';
                            $sub_step['part_name'] = '11 MAIN CONTAINER';
                            break;

                        case 'BAR TACK, RESERVE CONTAINER X 4':
                            $sub_step['part'] = '660';
                            $sub_step['part_project'] = '424';
                            $sub_step['part_name'] = '10 RESERVE CONTAINER LEFT';
                            break; 

                        case 'BAR TACK, BACK PAD  X 12' :
                            $sub_step['part'] = '636';
                            $sub_step['part_project'] = '403';
                            $sub_step['part_name'] = '15A BACKPAD OUTSIDE LAYER (EM5)';
                            break;

                        case 'BAR TACK, LEG PADS X 8':
                            $sub_step['part'] = '682';
                            $sub_step['part_project'] = '0';
                            $sub_step['part_name'] = '16 LEGPADS';
                            break;

                        case 'BAR TACK, FIT RIGHT POCKETS X 6 and INSTALL OETIKER CLAMPS':
                            $sub_step['part'] = '657';
                            $sub_step['part_project'] = '421';
                            $sub_step['part_name'] = '18 RESERVE POCKET (EM14)';
                            break;

                        case 'BAR TACK INNER YOKE SUB ASSEMBLY':
                            $sub_step['part'] = '643';
                            $sub_step['part_project'] = '411';
                            $sub_step['part_name'] = '2 INSIDE YOKE';
                            break;

                        case 'Grommet Main Container':
                            $sub_step['part'] = '651';
                            $sub_step['part_project'] = '416';
                            $sub_step['part_name'] = '11 MAIN CONTAINER';
                            break;

                        case 'Grommet Reserve Container':
                            $sub_step['part'] = '660';
                            $sub_step['part_project'] = '424';
                            $sub_step['part_name'] = '10 RESERVE CONTAINER LEFT';
                            break;

                        case 'INSPECT MAIN CONTAINER SUB ASSEMBLY':
                            $sub_step['part'] = '651';
                            $sub_step['part_project'] = '416';
                            $sub_step['part_name'] = '11 MAIN CONTAINER';
                            break;

                         case 'INSPECT RESERVE CONTAINER SUB ASSEMBLY':
                            $sub_step['part'] = '660';
                            $sub_step['part_project'] = '424';
                            $sub_step['part_name'] = '10 RESERVE CONTAINER LEFT';
                            break;

                        case 'INSPECT BACK PAD SUB ASSEMBLY' :
                            $sub_step['part'] = '636';
                            $sub_step['part_project'] = '403';
                            $sub_step['part_name'] = '15A BACKPAD OUTSIDE LAYER (EM5)';
                            break;

                         case 'INSPECT LEG PADS,  CORRECT COLOR AND SIZE':
                            $sub_step['part'] = '682';
                            $sub_step['part_project'] = '0';
                            $sub_step['part_name'] = '16 LEGPADS';
                            break;

                        case 'INSPECT HANDLE POCKETS':
                            $sub_step['part'] = '658';
                            $sub_step['part_project'] = '422';
                            $sub_step['part_name'] = 'HANDLE POCKET FOAM INSERT';
                            break;

                        case 'CHECK BACKPAD/RES-MAIN SUB ASSEMBLY FIT' :
                            $sub_step['part'] = '636';
                            $sub_step['part_project'] = '403';
                            $sub_step['part_name'] = '15A BACKPAD OUTSIDE LAYER (EM5)';
                            break;

                        case 'INSTALL LINESTOW COVER TO RESERVE CONTAINER':
                            $sub_step['part'] = '661';
                            $sub_step['part_project'] = '425';
                            $sub_step['part_name'] = 'LINESTOW COVER';
                            break;

                        case 'INSTALL MAIN CONTAINER SUB ASSEMBLY ONTO RESERVE CONTAINER SUB ASSEMBLY':
                            $sub_step['part'] = '651';
                            $sub_step['part_project'] = '416';
                            $sub_step['part_name'] = '11 MAIN CONTAINER';
                            break;

                        case 'VERTICAL BOX RESERVE CONTAINER':
                            $sub_step['part'] = '660';
                            $sub_step['part_project'] = '424';
                            $sub_step['part_name'] = '10 RESERVE CONTAINER LEFT';
                            break;

                        case 'TOP BOX THE RESERVE CONTAINER':
                            $sub_step['part'] = '660';
                            $sub_step['part_project'] = '424';
                            $sub_step['part_name'] = '10 RESERVE CONTAINER LEFT';
                            break;

                        case 'LATERAL PADDING ONTO BACK PAD SUB ASSEMBLY':
                            $sub_step['part'] = '679';
                            $sub_step['part_project'] = '440';
                            $sub_step['part_name'] = '15C LATERAL PAD LEFT COLOR';
                            break;

                        case 'LATERAL STABIZIERS ONTO BACK PAD SUB ASSEMBLY':
                            $sub_step['part'] = '652';
                            $sub_step['part_project'] = '408';
                            $sub_step['part_name'] = '14C LATERAL STABILIZER OUT SIDE COLOR LEFT (EM15)';
                            break;

                        case 'ZZ MAIN CONTAINER VERTICAL BOXING':
                            $sub_step['part'] = '651';
                            $sub_step['part_project'] = '416';
                            $sub_step['part_name'] = '11 MAIN CONTAINER';
                            break;

                        case 'BAR TACK TOP OF RESERVE CONTAINER X 5':
                            $sub_step['part'] = '660';
                            $sub_step['part_project'] = '424';
                            $sub_step['part_name'] = '10 RESERVE CONTAINER LEFT';
                            break;

                        case 'BAR TACK RESERVE  PIN COVER FLAP X 3':
                            $sub_step['part'] = '642';
                            $sub_step['part_project'] = '410';
                            $sub_step['part_name'] = '5 RES PIN COVER FLAP (EM4)';
                            break;

                        case 'ZZ LATERAL STABIZER TO LAT PAD OVER BASTING STITCH':
                            $sub_step['part'] = '652';
                            $sub_step['part_project'] = '408';
                            $sub_step['part_name'] = '14C LATERAL STABILIZER OUT SIDE COLOR LEFT (EM15)';
                            break;

                        case 'INSTALL LEFT LEG PAD, ZZ X 2':
                            $sub_step['part'] = '683';
                            $sub_step['part_project'] = '0';
                            $sub_step['part_name'] = '16a LEGPADS LEFT (EM9)';
                            break;

                        case 'INSTALL RIGHT LEG PAD, ZZ X 2':
                            $sub_step['part'] = '684';
                            $sub_step['part_project'] = '0';
                            $sub_step['part_name'] = '16b LEGPADS RIGHT (EM10)';
                            break;

                        case 'INSTALL RELEASE HANDLE POCKET':
                            $sub_step['part'] = '670';
                            $sub_step['part_project'] = '420';
                            $sub_step['part_name'] = 'RELEASE HANDLE(EM11)';
                            break;

                        case 'INSTALL RESERVE HANDLE POCKET':
                            $sub_step['part'] = '645';
                            $sub_step['part_project'] = '421';
                            $sub_step['part_name'] = 'RESERVE HANDLE (EM12)';
                            break;

                        case 'Check Fabric Type and Color':
                            $sub_step['part'] = '651';
                            $sub_step['part_project'] = '416';
                            $sub_step['part_name'] = '11 MAIN CONTAINER';
                            break;

                        case 'Check Binding Tape Color, Stitching and overall Quality':
                            $sub_step['part'] = '676';
                            $sub_step['part_project'] = '437';
                            $sub_step['part_name'] = 'BINDING TAPE COLOR 1';
                            break;

                        case 'Check Thread Color, (SPI) stitches per inch = 7-11 for Tex 60/70 and 135 ,  5-7 SPI for Tex 350':
                            $sub_step['part'] = '681';
                            $sub_step['part_project'] = '0';
                            $sub_step['part_name'] = 'MAIN THREAD COLOR';
                            break;

                       case 'Re- Check binding tape color, defects and overall workmanship':
                            $sub_step['part'] = '676';
                            $sub_step['part_project'] = '437';
                            $sub_step['part_name'] = 'BINDING TAPE COLOR 1';
                            break; 
                      }
                      
                      $query = mysqli_query($link,'SELECT variables FROM project_parts WHERE project=\''.sf($project['id']).'\' AND part=\''.sf($sub_step['part_project']).'\'');
                      $res = mysqli_fetch_assoc($query);
                      $sub_step['part_variables'] = $res['variables'];
                  }
              }

              	if($sub_step['type']=='text') {
            		$input_checked = '';
            		$input_value = $sub_step['value'];
            	} else {
            		$input_checked = ($sub_step['value']==1 ? 'checked="checked"' : '');
            		$input_value = '1';
            	}
            
                //echo "<pre>";
                //print_r($sub_step);
                //echo "</pre>";
                
              $notes_value = $sub_step['notes'];
              $part_vars = json_decode($sub_step['part_variables'], true);
              
              if(is_array($part_vars))
              {
                  $material = '';
                  $color = '';
                  
                  foreach($part_vars as $key=>$var)
                  {
                      if(!empty($var['value'])) 
                      {                        
                          if(preg_match('/Material/', $var['name']))
                          {
                              $material = $var['value'];
                              //echo $material.' = ';
                          }
                          
                          if(preg_match('/Color/', $var['name']))
                          {
                              $color = $var['value'];
                              //echo $color;
                          }
                      }
                  }
              }
              
              $lot_number = '';
              
              if($material != '' && $color != '')
              {
                  $query = mysqli_query($link, 'SELECT * FROM batch_lots WHERE material="'.sf($material).'" AND color = "'.sf($color).'"');
                  $settings = mysqli_fetch_assoc($query);
                  $lot_number = $settings['lot_number'];
                  $batch_lot = $sub_step['batch_lot'];
                  
                  if($lot_number != $batch_lot)
                      $red_style = ' red ';
                  else
                      $red_style = '';
                    
              }
              //echo"<pre>";
              //print_r($sub_step);
              //echo"</pre>";
              //if($sub_step['capture_batch_lot'] == 0 || $sub_step['capture_batch_lot'] == "" || $sub_step['batch_lot'] == "" || $sub_step['batch_lot'] == 0 )
                  //continue;
        
                //echo $sub_step['part_name'];
				if(!empty($sub_step['part_name'])){
				?>
				<div class="row parts-table parts-table-data <?php echo $red_style; ?>" 
             style="cursor: pointer"
             onclick="window.open('<?php echo root('?page=project_step&id='.$project['id'].'&get_step='.$step['step_id']); ?>', '_blank').focus();">
          <div class="col-md-3">
              <b><?php echo $step['step_name']; ?></b><br>
              <?php echo $sub_step['name']; ?>
          </div>
					<div class="col-md-2"><?=$sub_step['part_name']?></div>
					<div class="col-md-2 text-center">
						<?php 

                            if(is_array($part_vars))
                            {
                                $batch_lot = $sub_step['batch_lot'];
                                
                                if(empty($batch_lot)) 
                                {
                                    //attempt to look up a valid batch lot #
                                    $batch_lot = get_next_batch_lot($part_vars);
                                    if(!empty($batch_lot)) {
                                        $batch_lot_subtxt = '<div class="text-danger bold">Auto-populated</div>';
                                    } else {
                                        $batch_lot_subtxt = '';
                                    }
                                }
                            
                                if(!empty($batch_lot) && $sub_step['completed'] == 1)
                                {
                                    echo $batch_lot.'<br/>'.$batch_lot_subtxt.'<br/>'; //.date("Y-m-d", strtotime($sub_step['completed_time']));
                                } 
                                elseif(!empty($batch_lot) && $sub_step['completed'] == 0)
                                {
                                    $batch_lot = get_next_batch_lot($part_vars);
                                    echo '<input type=\'hidden\' name=\'batch_lot\' value=\''.$batch_lot.'\'><br/>';
                                    echo 'Waiting to be completed';
                                }
                                else 
                                {
                                    echo 'NONE';
                                }
                            }
                        
                        ?>
					</div>
					<div class="col-md-5 col-xs-12 project_substep_parts">
    					<?
        					$i = 1;
        					
        					if(is_array($part_vars))
                  {
        						foreach($part_vars as $key=>$var) {
        							if(!empty($var['value'])) {
        								echo '';
        								echo '<div class="col-md-6 col-sm-6 part-vars  part-var-int">'.$var['name'].'&nbsp;</div>';
        								echo '<div class="col-md-6 col-sm-6 part-vars">'.$var['value'].'&nbsp;</div>';
        								$i++;
        							}
        						}
                  }
    					?>
					
					</div>
				</div>
				<?      }
                else
                {
                    echo "Empty part data. Please resend order data from designer<br>";
                }
            }
        }
        echo '</div>';
?>
<script>
    var timer = null;
			$('#livesearch').keypress(function() {
			    clearTimeout(timer); 
                timer = setTimeout(doStuff, 200)
			});
			
			function doStuff()
			{
			    document.location='<?=root()?>?page=check_batch_lot&sn='+$('#livesearch').val();
			}
</script>