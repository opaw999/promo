<section class="content-header">
  	<h1>
    	<?php echo $module ?>
  	</h1>
  	<ol class="breadcrumb">
    	<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    	<li><a href="#"><?php echo $module; ?></a></li>
    	<li class="active"><?php echo $subMenu; ?></li>
  	</ol>
</section>

<section class="content">

    <!-- Default box -->
    <div class="box box-primary">
        <div class="box-header with-border">
          	<h3 class="box-title">Manage Promo Incharge Account</h3>
        </div>

        <div class="box-body">
          	<table id="managePromoIncharge" class="table table-striped table-hover table1">
                <thead>
                	<tr>
                		<th>Emp.ID</th>
                		<th>Name</th>
                		<th>UserType</th>
                		<th>Status</th>
                        <th>DateCreated</th>
                		<th>DateUpdated</th>
                	</tr>
                </thead>
                <tfoot>
                	<tr>
                		<th>Emp.ID</th>
                		<th>Name</th>
                		<th>UserType</th>
                		<th>Status</th>
                        <th>DateCreated</th>
                		<th>DateUpdated</th>
                	</tr>
                </tfoot>
            </table>
        </div>

        <!-- /.box-body -->
    </div>
   	<!-- /.box -->

</section>
<script type="text/javascript">
    
    function usertype(empId,type){

        $.ajax({
            type : "POST",
            url  : "functionquery.php?request=updatePIUsertype",
            data : { empId:empId, type:type },
            success : function(data){

                data = data.trim();
                if (data == "Ok") {

                    succSave("User Type Successfully Updated");
                } else {

                    alert(data);
                }
            }
        });
    }

    function userStatus(empId,status){

        $.ajax({
            type : "POST",
            url  : "functionquery.php?request=updatePIStatus",
            data : { empId:empId, status:status },
            success : function(data){
                
                data = data.trim();
                if (data == "Ok") {

                    succSave("User Status Successfully Updated");
                } else {

                    alert(data);
                }
            }
        });
    }
</script>