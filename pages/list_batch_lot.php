<style>
    .dataTables_filter { display : none; }
</style>
<?
if($_SESSION['type']!=='admin') exit();
if(empty($_GET['sort_by'])) $_GET['sort_by']='name';
?>
<div class="floatl">
	<h2 class="form-signin-heading">Settings</h2>
</div>

<div class="floatr">
	<button class="btn btn-primary" onclick="document.location='<?=root()?>?page=input_batch_lot';">Add</button>
</div>
<div class="col-md-8">&nbsp;</div>
<div class="col-md-4" style="float:right;margin:0px">
	<div class="col-md-6" style="width:auto">
        <br />
       <input type="text" placeholder="search" id="livesearch" class="livesearch" <?=($_GET['search']!='' ? 'value="'.$_GET['search'].'"' : '')?>>
    </div>
</div>
<div class="clear"></div>
<br/>
<div id="data_table">
    <table class="table table-striped table-bordered table-hover" id="datatable" width="100%" style="font-size: 12px">
    <thead>
        <tr>
        <th>QB Part</th>
        <th>Name</th>
        <th>Type</th>
        <th>Material</th>
        <th>Color</th>
        <th>Lot Number</th>
    </tr>
    </thead>
    <tbody></tbody>
    </table>
</div>
<script>
    $(document).ready(function(){
        <?php
            if($_SESSION['message_content'] != '')
            {
                echo '$.notify("'.$_SESSION['message_content'] .'", "'.$_SESSION['message_type'] .'")';
                
                unset($_SESSION['message_content']);
	            unset($_SESSION['message_type']);
            }
        ?>
            table = $('#datatable').DataTable({
                                       /* "dom": 'Blfrtip',
                                                "buttons": [
                                                    {
                                                        "extend": 'excel',
                                                        "text": 'Export Excel',
                                                        "titleAttr": 'Excel',
                                                        "action": newexportaction
                                                    },
                                                ],*/
                                        "processing": true,
                                        "serverSide": true,
                                        "pageLength": 25,
                                        "ordering": true,
                                        "order": [[ 2, 'desc' ]],
                                        "ajax":
                                        {
                                            "url": "<?php echo root(); ?>do/batch_lot_list/",
                                            "data": function(data){
                                                        // Read values
                                                        var livesearch = $('#livesearch').val();
		                                                //var cat = $('#cat').val();
                                
                                                        // Append to data
                                                        data.livesearch = livesearch;
                                                        //data.cat = cat;
                                                    },
                                            "type": "POST"
                                        },
                                        "deferRender": true,
                                         "fnCreatedRow": function( nRow, row, iDataIndex ) {
                                                            $(nRow).attr('id', row.id);
                                                        },
                                        "columns": [
                                            { "data": "inventory_id" },
                                            { "data": "name" },
                                            { "data": "type" },
                                            { "data": "material" },
                                            { "data": "color" },
                                            { "data": "lot_number" },
                                        ],
                                        /*"columnDefs": [ {
                                            "targets": [3,4,5,6,7], 
                                            "orderable": false, 
                                         }],*/
                                    });
        
            /*$('#cat').change(function(){
                table.draw();
    		});*/
			
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
            document.location.href = "<?php echo root();?>?page=edit_batch_lot&id=" + redirection;
        });
        
        /*function newexportaction(e, dt, button, config) {
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
     }*/
     
        
        
    })
</script>
