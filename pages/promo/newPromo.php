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

    <?php 

        $lastmonth = date("Y-m-d",strtotime("-1 month"));
        $date = date('Y-m-d');
    ?>

    <!-- Default box -->
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">New Employees tagged for the last 1 month from <?php echo "<b>".date("F d, Y", strtotime($lastmonth))."</b> to <b>".date("F d, Y", strtotime($date))."</b>"; ?></h3>
        </div>

        <div class="box-body">
          	<table id="newPromo" class="table table-striped table-hover table1">
                <thead>
                	<tr>
                		<th>Name</th>
                		<th>Position</th>
                        <th>EmpType</th>
                        <th>BusinessUnit</th>   
                        <th>Department</th>
                        <th>Startdate</th>
                        <th>EOCdate</th>
                	</tr>
                </thead>
            </table>
        </div>
        <!-- /.box-body -->
    </div>
   	<!-- /.box -->

</section>