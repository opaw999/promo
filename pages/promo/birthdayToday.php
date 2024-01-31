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
            <h3 class="box-title"><?php echo $subMenu; ?></h3>
        </div>

        <div class="box-body">
          	<table id="birthdayToday" class="table table-striped table-hover table1">
                <thead>
                	<tr>
                		<th>Name</th>
                		<th>Gender</th>
                        <th>Birthdate</th>
                        <th>BusinessUnit</th>   
                        <th>Department</th>
                        <th>PromoType</th>
                        <th>Position</th>
                	</tr>
                </thead>
            </table>
        </div>
        <!-- /.box-body -->
    </div>
   	<!-- /.box -->

</section>