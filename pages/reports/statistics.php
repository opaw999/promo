<section class="content-header">
  	<h1>
    	<?php echo $module ?> <small>Active status only.</small>
  	</h1>
  	<ol class="breadcrumb">
    	<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    	<li><a href="#"><?php echo $subMenu; ?></a></li>
    	<li class="active">Summary</li>
  	</ol>
</section>

<section class="content">
	
	<div class="row">
		<div class="col-md-12">
			
			<div class="box box-primary">
				<div class="box-header with-border">
                    <h3 class="box-title"><?php echo $subMenu; ?></h3>
                </div>
                <div class="box-body">
                	<table class="table table-bordered table-stripped table-hover" style="width:100%">
                		<thead>
                			<tr>
                				<th rowspan="2">Business Unit</th>
                				<th rowspan="2">Department</th>
                				<th colspan="2">Employee Type</th>
                				<th rowspan="2">Total</th>
                			</tr>
                			<tr>
                				<th>Promo</th>
                				<th>Promo-NESCO</th>
                			</tr>
                		</thead>
                		<tbody>
                			<?php 

                                $totalEmp = 0;
                                $totalPromo = 0;
                                $totalPromoNesco = 0;
                				$getBu = mysql_query("SELECT bunit_id, bunit_name, bunit_field FROM `locate_promo_business_unit` WHERE status = 'active' AND hrd_location = 'asc'")or die(mysql_error());
                				while ($bu = mysql_fetch_array($getBu)) { 

                					
                                    $totalEmp = countTotalPromo($bu['bunit_field'],'allPromo');
                                    $totalPromo = countTotalPromo($bu['bunit_field'],'Promo');
                					$totalPromoNesco = countTotalPromo($bu['bunit_field'],'Promo-NESCO');
                                    ?>

	        						<tr>
	            						<td><?php echo $bu['bunit_name']; ?></td>
	            						<td></td>
	            						<td><a href="#" onclick="viewStatDept('<?php echo $bu['bunit_field']; ?>','','Promo')"><small class="badge bg-green"><?php echo $totalPromo ?></small></a></td>
	            						<td><a href="#" onclick="viewStatDept('<?php echo $bu['bunit_field']; ?>','','Promo-NESCO')"><small class="badge bg-green"><?php echo $totalPromoNesco ?></small></a></td>
	            						<th><a href="#" onclick="viewStatAll('<?php echo $bu['bunit_field']; ?>','')"><small class="badge bg-green"><?php echo $totalEmp; ?></small></a></th>
	            					</tr><?php
                					
                					$promo = 0;
                					$promoNESCO = 0;
                					$total = 0;
                					$getDept = mysql_query("SELECT dept_name FROM `locate_promo_department` WHERE bunit_id = '".$bu['bunit_id']."' AND status = 'active';")or die(mysql_error());
                					while ($dept = mysql_fetch_array($getDept)) { 

                						$promo = countPromo($bu['bunit_field'],$dept['dept_name'],'Promo');
                						$promoNESCO = countPromo($bu['bunit_field'],$dept['dept_name'],'Promo-NESCO');
                						$total = $promo + $promoNESCO;
                						?>
                						
                						<tr>
	                						<td></td>
	                						<td><?php echo $dept['dept_name']; ?></td>
	                						<td><a href="#" onclick="viewStatDept('<?php echo $bu['bunit_field']; ?>','<?php echo $dept['dept_name']; ?>','Promo')"><?php echo $promo; ?></a></td>
	                						<td><a href="#" onclick="viewStatDept('<?php echo $bu['bunit_field']; ?>','<?php echo $dept['dept_name']; ?>','Promo-NESCO')"><?php echo $promoNESCO; ?></a></td>
	                						<td><a href="#" onclick="viewStatAll('<?php echo $bu['bunit_field']; ?>','<?php echo $dept['dept_name']; ?>')"><?php echo $total; ?></a></td>
	                					</tr><?php
                					}
                				}
                			?>
                		</tbody>
                	</table>
                </div>
			</div>
		</div>
	</div>
</section>

<!-- ./Modal -->
<div id="viewStatDept" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light-blue color-palette">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Statistics Summary</h4>
            </div>
	        <div class="modal-body">
	            <div class="viewStatDept"></div>
	        </div>
    	</div>
    	<!-- /.modal-content -->
  	</div>
  	<!-- /.modal-dialog -->
</div>

<?php 

	function countPromo($bunit,$dept,$emp_type){

		$query = mysql_query("SELECT DISTINCT(employee3.emp_id) AS num FROM employee3, promo_record WHERE employee3.emp_id = promo_record.emp_id AND emp_type = '$emp_type' AND $bunit = 'T' AND promo_department = '$dept' AND current_status = 'Active'")or die(mysql_error());
		return mysql_num_rows($query);
	}

    function countTotalPromo($bunit,$emp_type){

        if ($emp_type == 'allPromo') {
            $emp_type = "(emp_type = 'Promo' OR emp_type = 'Promo-NESCO')";
        } else {

            $emp_type = "emp_type = '$emp_type'";
        }

        $getCountBU = mysql_query("SELECT DISTINCT(employee3.emp_id) AS num FROM employee3, promo_record WHERE employee3.emp_id = promo_record.emp_id AND $emp_type AND current_status = 'Active' AND ".$bunit." = 'T'")or die(mysql_error());
        return mysql_num_rows($getCountBU);
    }
?>

<script type="text/javascript">
	
	function viewStatDept(bunit,dept,emp_type){

	    $("#viewStatDept").modal({
	        backdrop: 'static',
	        keyboard: false
	    });

	    $("#viewStatDept").modal("show");
        $(".viewStatDept").html("");
    	$.ajax({
        	type : "POST",
        	url  : "functionquery.php?request=viewStatDept",
        	data : { bunit:bunit, dept:dept, emp_type:emp_type },
        	success : function(data){
        	
        	    $(".viewStatDept").html(data);  
        	}
    	});
  	}

    function viewStatAll(bunit,dept){

        $("#viewStatDept").modal({
            backdrop: 'static',
            keyboard: false
        });

        $("#viewStatDept").modal("show");
        $(".viewStatDept").html("");
        $.ajax({
            type : "POST",
            url  : "functionquery.php?request=viewStatAll",
            data : { bunit:bunit, dept:dept },
            success : function(data){
            
                $(".viewStatDept").html(data);  
            }
        });
    }

    function generate(bunit,dept,emp_type){

        window.location = "pages/reports/statistics_summary_xls.php?bunit="+bunit+"&&dept="+dept+"&&emp_type="+emp_type;
    }
</script>