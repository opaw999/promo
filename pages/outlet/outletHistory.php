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

<?php 

    $query = mysql_query("SELECT change_no, change_outlet_record.emp_id, changefrom, changeto, effectiveon FROM `change_outlet_record`, `promo_record` WHERE change_outlet_record.emp_id = promo_record.emp_id AND hr_location = '$hrCode' ORDER BY change_no DESC")or die(mysql_error());
?>

<section class="content">

    <!-- Default box -->
    <div class="box box-primary">
        <div class="box-header with-border">
          	<h3 class="box-title">Change Outlet History</h3>
        </div>

        <div class="box-body">
          	<table id="" class="table table-striped table-hover table3">
                <thead>
                	<tr>
                		<th>Emp.ID</th>
                		<th>Name</th>
                		<th>Effective</th>
                		<th>PreviousOutlet</th>
                		<th>PresentOutlet</th>
                	</tr>
                </thead>
                <tbody>
                    <?php 

                        while ($row = mysql_fetch_array($query)) {
                            
                            echo "<tr>
                                    <td><a href='?p=profile&&module=Promo&&com=".$row['emp_id']."'>".$row['emp_id']."</td>
                                    <td>".ucwords(strtolower($nq->getPromoName($row['emp_id'])))."</td>
                                    <td>".date('m/d/Y', strtotime($row['effectiveon']))."</td>
                                    <td>".ucwords(strtolower($row['changefrom']))."</td>
                                    <td>".ucwords(strtolower($row['changeto']))."</td>
                                </tr>";
                        }
                    ?>
                </tbody>
                <tfoot>
                	<tr>
                		<th>Emp.ID</th>
                		<th>Name</th>
                		<th>Effective</th>
                		<th>PreviousOutlet</th>
                		<th>PresentOutlet</th>
                	</tr>
                </tfoot>
            </table>
        </div>

        <!-- /.box-body -->
    </div>
   	<!-- /.box -->

</section>