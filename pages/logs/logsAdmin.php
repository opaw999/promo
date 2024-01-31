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

    $today      = date("Y-m-d");
    $yesterday  = date("Y-m-d", strtotime( '-1 days' ));
    $last7days  = date('Y-m-d', strtotime('-7 days'));
    $last15days = date('Y-m-d', strtotime('-15 days'));

    if (!empty($_GET['filterDate'])) {
        
        $filter = $_GET['filterDate'];

        if ($filter == "today") {
            
            $message = "Filter LOGS Today, ".date('M d, Y',strtotime($today));
        }

        if ($filter == "yesterday") {
            
            $message = "Filter LOGS Yesterday, ".date('M d, Y',strtotime($yesterday));
        }

        if ($filter == "last7Days") {
            
            $message = "Filter LOGS Last 7 days, ".date('M d, Y',strtotime($last7days))." to ".date('M d, Y',strtotime($today));
        }

        if ($filter == "last15Days") {
            
            $message = "Filter LOGS Last 15 days, ".date('M d, Y',strtotime($last15days))." to ".date('M d, Y',strtotime($today));
        }
    } else {

        $filter = "today";
        $message = "Filter LOGS Today, ".date('M d, Y',strtotime($today));
    }
?>
<section class="content">

    <!-- Default box -->
    <div class="box box-primary">
        <div class="box-header with-border">
            <div class="form-group">
                <label class="col-md-1">Filter: </label>
                <div class="col-md-2">
                    <select class="form-control" id="" onchange="dateFilter(this.value)">
                        <option value="today" <?php if($filter == "today"): echo "selected=''"; endif; ?>>Today</option>
                        <option value="yesterday" <?php if($filter == "yesterday"): echo "selected=''"; endif; ?>>Yesterday</option>
                        <option value="last7Days" <?php if($filter == "last7Days"): echo "selected=''"; endif; ?>>Last 7 Days</option>
                        <option value="last15Days" <?php if($filter == "last15Days"): echo "selected=''"; endif; ?>>Last 15 Days</option>
                    </select>
                </div>
            </div>
            <div>
                <h3 class="box-title pull-right"><?php echo $message; ?></h3>
            </div>
        </div>

        <div class="box-body">
            <input type="hidden" name="dateFilter" value="<?php echo $filter; ?>">
          	<table id="logsAdmin" class="table table-striped table-hover table1">
                <thead>
                	<tr>
                		<th>LogNo</th>
                		<th>Activity</th>
                		<th>Date</th>
                		<th>Time</th>
                        <th>User</th>
                        <th>Username</th>
                	</tr>
                </thead>
                <tfoot>
                	<tr>
                		<th>LogNo</th>
                		<th>Activity</th>
                		<th>Date</th>
                		<th>Time</th>
                        <th>User</th>
                        <th>Username</th>
                	</tr>
                </tfoot>
            </table>
        </div>

        <!-- /.box-body -->
    </div>
   	<!-- /.box -->

</section>
<script type="text/javascript">
    
    function dateFilter(value){
        
        var loc = document.location;
        window.location = loc+"&&filterDate="+value;

    }
</script>