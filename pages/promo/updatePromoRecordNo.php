<section class="content-header">
    <h1>
        Update Promo Record No.
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">HRMS Concerns</a></li>
        <li class="active">Update Promo Record No.</li>
    </ol>
</section>

<section class="content ">
	<div class="row">
		<div class="col-md-12">
			<div id="progress" style="width:100%;border:1px solid #ccc;">&nbsp;</div>
			<div id="information" style="width"></div>
		</div>
	</div>
</section>

<?php
// Total processes
ob_end_flush();

	$query = mysql_query("SELECT promo_id, emp_id FROM `promo_record` WHERE record_no = ''")or die(mysql_error());

	$total = mysql_num_rows($query);

	$i = 1;
	while($r = mysql_fetch_array($query)) {

		$empId = $r['emp_id'];

		$percent = intval($i/$total * 100)."%";
		$i++;


		echo '<script language="javascript">
				    document.getElementById("progress").innerHTML="<div style=\"width:'.$percent.';background-color:skyblue;\">&nbsp;</div>";
					document.getElementById("information").innerHTML=" Updating HRMS ID: '.$empId.'";	 
			</script>';
						    
		$sql = mysql_query("SELECT record_no FROM employee3 WHERE emp_id = '$empId' AND (emp_type = 'Promo' or emp_type = 'Promo-NESCO') ORDER BY record_no DESC LIMIT 1")or die(mysql_error());
		$row = mysql_fetch_array($sql);
		
		mysql_query("UPDATE promo_record SET record_no='".$row['record_no']."' WHERE emp_id = '$empId'")or die(mysql_error());

		flush();
		// sleep(1);


	}
	
	echo '<script language="javascript">document.getElementById("information").innerHTML="Process completed"</script>';

?>