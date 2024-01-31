<?php
session_start();
include("../../../connection.php");

/****************************************************************************************************/		
	$filename = "report";	
	header( "Content-Type: application/vnd.ms-excel" );
	header( "Content-disposition: attachment; filename=".$filename.".xls");

/********************************** values being passed from report_details **************************/
	
	$monthplus 	= date('Y-m-d', strtotime('+1 month')); //date one month from the current date
	$monthminus = date('Y-m-d', strtotime('-1 month')); //date one month from the current date
	$plus3 		= date('Y-m-d', strtotime('+3 month')); //date 3 month from the current date
	$minus3 	= date('Y-m-d', strtotime('-3 month')); //date 3 month from the current date
	$yesterday 	= date("Y-m-d", strtotime( '-1 days' ) );
	$last7days  = date('Y-m-d', strtotime('-7 days'));

	$filterBU = $_GET['filterBU'];
	$filter = "";
	if($_GET['filterDate'] != "") {

		$filter = $_GET['filterDate'];
	}

	if ($_GET['filterMonth'] != "") {

		$filter = $_GET['filterMonth'];
	}

	if($filter != "") {

		$year = date('Y');
		$filterEOC = $year."-".$filter."%";
		switch($filter){
			case "01": $whereFilterEOC = "AND eocdate LIKE '$filterEOC' ";break;
			case "02": $whereFilterEOC = "AND eocdate LIKE '$filterEOC' ";break;
			case "03": $whereFilterEOC = "AND eocdate LIKE '$filterEOC' ";break;
			case "04": $whereFilterEOC = "AND eocdate LIKE '$filterEOC' ";break;
			case "05": $whereFilterEOC = "AND eocdate LIKE '$filterEOC' ";break;
			case "06": $whereFilterEOC = "AND eocdate LIKE '$filterEOC' ";break;
			case "07": $whereFilterEOC = "AND eocdate LIKE '$filterEOC' ";break;
			case "08": $whereFilterEOC = "AND eocdate LIKE '$filterEOC' ";break;
			case "09": $whereFilterEOC = "AND eocdate LIKE '$filterEOC' ";break;
			case "10": $whereFilterEOC = "AND eocdate LIKE '$filterEOC' ";break;
			case "11": $whereFilterEOC = "AND eocdate LIKE '$filterEOC' ";break;
			case "12": $whereFilterEOC = "AND eocdate LIKE '$filterEOC' ";break;
			case "today": $whereFilterEOC = "AND eocdate = '$date' "; break;
			case "yesterday": $whereFilterEOC = "AND eocdate = '$yesterday' "; break;
			case "last7days": $whereFilterEOC = "AND eocdate BETWEEN '$last7days' AND '$date' "; break;
			case "last1month": $whereFilterEOC = "AND eocdate BETWEEN '$monthminus' AND '$date' "; break;
			default: $whereFilterEOC = "AND eocdate BETWEEN '$minus3' AND '$plus3'"; break;
		}
	} else {

		$whereFilterEOC = "AND eocdate BETWEEN '$monthminus' AND '$monthplus'";
	}

	if ($filterBU != "") {
		
		$whereFilterEOC .= "AND $filterBU = 'T'";
	}

	$sql = mysql_query("SELECT employee3.emp_id, name, startdate, eocdate, position, promo_company, promo_department, promo_type FROM `employee3` INNER JOIN `promo_record` ON employee3.emp_id = promo_record.emp_id 
							WHERE 
								(employee3.emp_type='Promo' OR employee3.emp_type = 'Promo-NESCO') 
								AND (employee3.current_status = 'Active' OR employee3.current_status = 'End of Contract') $whereFilterEOC")or die(mysql_error());

?>

<!DOCTYPE html>
<html>
	<head>
		<title>End of Contract List</title>
	</head>
	<body>

		<div style="width:90%;margin-left;auto;margin-right:auto">	
		<center><h4>END OF CONTRACT LIST</h4></center>

		<table class='table table-bordered' border='1'>
			<tr>
				<th>No</th>
				<th>Emp. ID</th>
				<th>Name</th>
				<th>Company</th>
				<th>Business Unit</th>				      
				<th>Department</th>
				<th>Position</th> 
				<th>Startdate</th>
				<th>EOCdate</th>
				<th>Promo Type</th>
			</tr>
			<?php 

				$no = 0;
				while ($row = mysql_fetch_array($sql)) {
					
					$no++;

					$storeName = "";
					$ctr = 0;
					$bunit = mysql_query("SELECT bunit_field, bunit_name FROM `locate_promo_business_unit`")or die(mysql_error());
					while ($str = mysql_fetch_array($bunit)) {

						$promo = mysql_query("SELECT promo_id FROM `promo_record` WHERE `".$str['bunit_field']."` = 'T' AND emp_id = '".$row['emp_id']."'")or die(mysql_error());
						if(mysql_num_rows($promo) > 0) {
							$ctr++;

							if($ctr == 1){

								$storeName = $str['bunit_name'];
							} else {

								$storeName .= ", ".$str['bunit_name'];
							}
						}
					}

					echo "
						<tr>
							<td>".$no."</td>
							<td>".$row['emp_id']."</td>
							<td>".ucwords(strtolower($row['name']))."</td>
							<td>".$row['promo_company']."</td>
							<td>".$storeName."</td>
							<td>".$row['promo_department']."</td>
							<td>".ucwords(strtolower($row['position']))."</td>
							<td>".date("m/d/Y", strtotime($row['startdate']))."</td>
							<td>".date("m/d/Y", strtotime($row['eocdate']))."</td>
							<td>".$row['promo_type']."</td>
						</tr>
					";
				}
			?>
		</table> 
	</body>
</html>