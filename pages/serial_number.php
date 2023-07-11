<style>
    .dataTables_filter { display : none; }
</style>
<?
if(!check_access('production')) exit();

if(empty($_GET['sort_by'])) $_GET['sort_by']='name';
if(!isset($_GET['cat'])) $_GET['cat']='serial';
?>
<div class="floatr">
	<button class="btn btn-primary" onclick="document.location='<?=root()?>?page=add_lock_sn';">Add Serial Number</button>
</div>

<div class="col-md-12">
  <h1 style="text-align: center">PM-QC-004 REV2</h1>
	<!--<h2 class="form-signin-heading">Serial Number</h2>-->
</div>
<div class="col-md-8">&nbsp;</div>
<div class="col-md-4" style="float:right;margin:0px">
    <div class="col-md-6" style="width:auto;padding:3px">
		<!--<strong>Search by</strong>-->
		<br />
		<select id="cat">
			<option value="">- Select -</option>
			<option value="id" <?=($_GET['cat']=='id' ? 'selected="selected"' : '')?>>#ID</option>
			<option value="customer_name" <?=($_GET['cat']=='customer_name' ? 'selected="selected"' : '')?>>Client</option>
			<option value="serial" <?=($_GET['cat']=='serial' ? 'selected="selected"' : '')?>>Serial</option>
		</select>
	</div>
	<div class="col-md-6" style="width:auto">
        <br />
       <input type="text" placeholder="search" id="livesearch" class="livesearch" <?=($_GET['search']!='' ? 'value="'.$_GET['search'].'"' : '')?>>
    </div>
</div>
<div class="clear"></div>
<br/>
<?php /*

<div id="data_table">
<table class="table table-striped table-bordered table-hover">
<tr>
	<th><a href="/?page=project&sort_by=serial">Serial</a> <?=($_GET['sort_by']=='serial' ? '&#x25BC;' : '')?></th>
	<th width="15%"><a href="/?page=project&sort_by=customer_name">Client</a> <?=($_GET['sort_by']=='customer_name' ? '&#x25BC;' : '')?></th>
	<th><a href="/?page=project&sort_by=estimated_completion">Requested Completion</a> <?=($_GET['sort_by']=='estimated_completion' ? '&#x25BC;' : '')?></th>
	<th><a href="/?page=project&sort_by=pod">Production Cycle</a> <?=($_GET['sort_by']=='pod' ? '&#x25BC;' : '')?></th>
</tr>

<?


//$q = mysqli_query($link, 'SELECT projects.*, customers.name as customer_name FROM projects LEFT JOIN customers ON projects.customer = customers.id '.(!empty($_GET['status']) ? 'WHERE status=\''.sf($_GET['status']).'\'' : '').' ORDER BY '.sf($_GET['sort_by']).' ASC');

if($_GET['search']) {
	$search_sql = " AND (projects.name LIKE '%".sf($_GET['search'])."%' OR customers.name LIKE '%".sf($_GET['search'])."%' OR projects.serial LIKE '%".sf($_GET['search'])."%' OR projects.payment LIKE '%".sf($_GET['search'])."%' OR projects.notes LIKE '%".sf($_GET['search'])."%' OR projects.pod LIKE '%".sf($_GET['search'])."%' OR projects.colors LIKE '%".sf($_GET['search'])."%' OR projects.metadata LIKE '%".sf($_GET['search'])."%')";
}

$query = 'SELECT projects.*, (SELECT product_steps.name FROM project_steps, product_steps WHERE project_steps.project=projects.id AND project_steps.status=\'Completed\' AND project_steps.step = product_steps.id ORDER BY product_steps.order DESC LIMIT 1) as last_status, customers.name as customer_name, products.name as product_name FROM projects LEFT JOIN customers ON projects.customer = customers.id, products WHERE projects.product = products.id AND '.(!empty($_GET['status']) ? 'projects.status=\''.sf($_GET['status']).'\'' : 'projects.status!=\'deleted\'').(!empty($_GET['location']) ? ' AND projects.location=\''.sf($_GET['location']).'\'' : '').$search_sql.' ORDER BY '.sf($_GET['sort_by']).' ASC';
//echo $query;
$q = mysqli_query($link, $query);


if(!empty($_SESSION['new_projects'])) {
	$highlight_new = true;
}

while($row = mysqli_fetch_assoc($q)) {
	echo '<tr>
			<td onclick="document.location=\''.root().'?page=project&id='.$row['id'].'\'">'.$row['serial'].'</td>
			<td onclick="document.location=\''.root().'?page=project&id='.$row['id'].'\'">'.$row['customer_name'].'</td>
			<td onclick="document.location=\''.root().'?page=project&id='.$row['id'].'\'">'.date('Y-m-d', strtotime($row['estimated_completion'])).'</td>
			<td onclick="document.location=\''.root().'?page=project&id='.$row['id'].'\'">'.$row['pod'].'</td>
		</tr>';
}

?>

</table>      
</div>
*/ ?>
<div id="data_table">
    <table class="table table-striped table-bordered table-hover" id="datatable" width="100%" style="font-size: 12px">
        <thead>
            <tr>
                <th width="20%">#ID</th>
                <th width="20%">Project Status</th>
                <th width="20%">Serial Number</th>
                <th width="30%">Name</th>
                <th width="10%">Production Line</th>
                <th width="10%">Production Cycle</th>
                <th width="10%">Yoke Size</th>
                <th width="10%">MLW Size</th>
                <th width="10%">Hardware Type</th>
                <th width="10%">RCS</th>
                <th width="10%">MCS</th>
                <th width="20%">Date Completed</th>
                <th width="10%">SN Status</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>
<script>
    $(document).ready(function(){
            table = $('#datatable').DataTable({
                                        "dom": 'Blfrtip',
                                                "buttons": [
                                                    {
                                                        "extend": 'excel',
                                                        "text": 'Export Excel',
                                                        "titleAttr": 'Excel',
                                                        "action": newexportaction
                                                    },
                                                ],
                                        "processing": true,
                                        "serverSide": true,
                                        "pageLength": 25,
                                        "ordering": true,
                                        "order": [[ 2, 'desc' ]],
                                        "ajax":
                                        {
                                            "url": "<?php echo root(); ?>do/serial_number_list/",
                                            "data": function(data){
                                                        // Read values
                                                        var livesearch = $('#livesearch').val();
		                                                var cat = $('#cat').val();
                                
                                                        // Append to data
                                                        data.livesearch = livesearch;
                                                        data.cat = cat;
                                                    },
                                            "type": "POST"
                                        },
                                        "deferRender": true,
                                         "fnCreatedRow": function( nRow, row, iDataIndex ) {
                                                            $(nRow).attr('id', row.id);
                                                        },
                                        "columns": [
                                            { "data": "id", "render": function ( data, type, row, meta ) {
                                                if(row.not_used_sn == 'NOT USED' && row.line == ''){
                                                    return '';
                                                }else{
                                                    return data;
                                                }
                                            }},
                                            { "data": "status", "render": function ( data, type, row, meta ) {
                                                if(row.not_used_sn == 'NOT USED' && row.line == ''){
                                                    return '';
                                                }else{
                                                    return data;
                                                }
                                            }},
                                            { "data": "serial" },
                                            { "data": "customer_name" },
                                            { "data": "line" },
                                            { "data": "pod" },
                                            { "data": "yoke_size" },
                                            { "data": "mlw_size" },
                                            { "data": "hardware_type" },
                                            { "data": "rcs" },
                                            { "data": "mcs" },
                                            
                                            { "data": "completed" },
                                            { "data": "not_used_sn", "render": function ( data, type, row, meta ) {
                                                return  '<div style="text-align: center"><b>'+data+'</b><br><a href="<?=root();?>?page=lock_sn&id='+row.id+'"><input type="button" class="btn btn-info" value="Edit" style="margin-top:5px"></a></div>';
                                            }},
                                        ],
                                        "columnDefs": [ {
                                            "targets": [3,4,5,6,7], 
                                            "orderable": false, 
                                         }],
                                    });
        
            $('#cat').change(function(){
                table.draw();
    		});
			
            var timer = null;
			$('#livesearch').keypress(function() {
			    clearTimeout(timer); 
                timer = setTimeout(doStuff, 200)
			});
			
			function doStuff()
			{
			     table.draw();
			}
			
        
        $('#datatable tbody').on( 'click', 'tr', function () {
            var redirection = $(this).attr('id');
            document.location.href = "<?php echo root();?>?page=project&id=" + redirection;
        });
        
        function newexportaction(e, dt, button, config) {
         var self = this;
         var oldStart = dt.settings()[0]._iDisplayStart;
         dt.one('preXhr', function (e, s, data) {
             // Just this once, load all data from the server...
             data.start = 0;
             data.length = 2147483647;
             dt.one('preDraw', function (e, settings) {
                 // Call the original action function
                 if (button[0].className.indexOf('buttons-copy') >= 0) {
                     $.fn.dataTable.ext.buttons.copyHtml5.action.call(self, e, dt, button, config);
                 } else if (button[0].className.indexOf('buttons-excel') >= 0) {
                     $.fn.dataTable.ext.buttons.excelHtml5.available(dt, config) ?
                         $.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, button, config) :
                         $.fn.dataTable.ext.buttons.excelFlash.action.call(self, e, dt, button, config);
                 } else if (button[0].className.indexOf('buttons-csv') >= 0) {
                     $.fn.dataTable.ext.buttons.csvHtml5.available(dt, config) ?
                         $.fn.dataTable.ext.buttons.csvHtml5.action.call(self, e, dt, button, config) :
                         $.fn.dataTable.ext.buttons.csvFlash.action.call(self, e, dt, button, config);
                 } else if (button[0].className.indexOf('buttons-pdf') >= 0) {
                     $.fn.dataTable.ext.buttons.pdfHtml5.available(dt, config) ?
                         $.fn.dataTable.ext.buttons.pdfHtml5.action.call(self, e, dt, button, config) :
                         $.fn.dataTable.ext.buttons.pdfFlash.action.call(self, e, dt, button, config);
                 } else if (button[0].className.indexOf('buttons-print') >= 0) {
                     $.fn.dataTable.ext.buttons.print.action(e, dt, button, config);
                 }
                 dt.one('preXhr', function (e, s, data) {
                     // DataTables thinks the first item displayed is index 0, but we're not drawing that.
                     // Set the property to what it was before exporting.
                     settings._iDisplayStart = oldStart;
                     data.start = oldStart;
                 });
                 // Reload the grid with the original page. Otherwise, API functions like table.cell(this) don't work properly.
                 setTimeout(dt.ajax.reload, 0);
                 // Prevent rendering of the full data to the DOM
                 return false;
             });
         });
         // Requery the server with the new one-time export settings
         dt.ajax.reload();
     }
     
        
        
    })
</script>