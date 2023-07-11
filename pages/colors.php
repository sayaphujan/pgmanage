
<div class="col-md-4">
	<h2 class="form-signin-heading">Colors</h2>
</div>

<div class="col-md-6">
  <br />
  <!--<input type="text" placeholder="search" id="livesearch" class="livesearch">-->
</div>
<script>
	$(function() {
			$('#livesearch').keypress(function() {	
          $('#data_table').load('<?=root()?>exec/colors/?search='+$('#livesearch').val()+' #data_table');
			});
	});
</script>

<div class="col-md-2 text-right"><br />

	<button class="btn btn-success" onclick="document.location='<?=root()?>?page=add_color';">Add Color</button>
</div>
<div class="clear"></div>

<div id="data_table">
    <table class="table table-striped table-bordered table-hover" id="datatable" width="100%" style="font-size: 12px">
        <thead>
            <tr>
                <th width="30%">Name</th>
                <th width="20%">Hexa</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
        
    
        <?
        
        //if($_GET['search']) {
        //	$search_sql = " WHERE customers.name LIKE '%".sf($_GET['search'])."%' OR customers.phone LIKE '%".sf($_GET['search'])."%' OR customers.email LIKE '%".sf($_GET['search'])."%' OR customers.city LIKE '%".sf($_GET['search'])."%' OR customers.state LIKE '%".sf($_GET['search'])."%' OR customers.zip LIKE '%".sf($_GET['search'])."%' OR customers.sponsor LIKE '%".sf($_GET['search'])."%' OR customers.address LIKE '%".sf($_GET['search'])."%'";
        //}
        //
        //
        //$q = mysqli_query($link, 'SELECT customers.*
        //                            , GROUP_CONCAT(DISTINCT(serial) SEPARATOR "<br>") as serial
        //                                FROM customers '.$search_sql.' 
        //                                LEFT JOIN projects ON projects.customer=customers.id
        //                                GROUP BY customers.id
        //                                ORDER BY customers.name ASC');
        //
        //while($row = mysqli_fetch_assoc($q)) {
        //    $check_sn = mysqli_query($link, 'SELECT serial FROM projects WHERE customer='.$row['id'].' ORDER BY serial ASC');
        //    echo '<tr onclick="document.location=\''.root().'?page=edit_customer&id='.$row['id'].'\'">';
        //    echo   '<td>'.$row['serial'].'</td>
        //            <td>'.$row['name'].'</td>
        //		    <td>'.$row['phone'].'</td>
        //		    <td>'.$row['email'].'</td>
        //		    <td>'.$row['city'].'</td>
        //		    <td>'.$row['state'].'</td>
        //		    <td>'.$row['country'].'</td>
        //		  </tr>';
        //}
        //
        //?>      
</div>
<script>
    $(document).ready(function(){
        tabel = $('#datatable').DataTable({
            "processing": true,
            "serverSide": true,
            "pageLength": 25,
            "ordering": true,
            "order": [[ 1, 'asc' ]],
            "ajax":
            {
                "url": "<?php echo root(); ?>do/color_list/",
                "type": "POST"
            },
            "deferRender": true,
            "fnCreatedRow": function( nRow, row, iDataIndex ) {
                                $(nRow).attr('id', row.colors_id);
                            },
            "columns": [
                { "data": "colors_name" },
                { "data": "colors_hex" },
            ],
        });
        
        $('#datatable tbody').on( 'click', 'tr', function () {
            var redirection = $(this).attr('id');
            document.location.href = "<?php echo root();?>?page=edit_color&id=" + redirection;
        });
    })
</script>