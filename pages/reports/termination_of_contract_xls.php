<?php
session_start();
include("../../../connection.php");

/****************************************************************************************************/		
	$filename = "report";	
	header( "Content-Type: application/vnd.ms-excel" );
	header( "Content-disposition: attachment; filename=".$filename.".xls");

/********************************** values being passed from report_details **************************/
	
	$company = mysql_real_escape_string($_GET['company']);
	$store = $_GET['store'];
	$department = $_GET['department'];
	$month = explode('|',$_GET['month']);

	$where = "";
	if(!empty($month[1])){
		$year	  = $month[1];
		$date = $year."-".$month[0];
	}
	else{
		$year   = date('Y');
		$date = $year."-".$month[0];
	}

	$where = "AND eocdate LIKE '%$date%'";

	if (!empty($store)) {
		
		$bunit = explode("/", $store);
		$where .= " AND $bunit[1]= 'T'";
	} 

	if (!empty($department)) {
		
		$where .= " AND promo_department = '$department'";
	}

	if (!empty($company)) {
		
		$pcName = $nq->getPromoCompanyName($company);
		$where .= " AND promo_company = '$pcName'";
	}

	switch ($month[0]) {
		case '1':
			$monthName = "January";
			break;
		case '2':
			$monthName = "February";
			break;
		case '3':
			$monthName = "March";
			break;
		case '4':
			$monthName = "April";
			break;
		case '5':
			$monthName = "May";
			break;
		case '6':
			$monthName = "June";
			break;
		case '7':
			$monthName = "July";
			break;
		case '8':
			$monthName = "August";
			break;
		case '9':
			$monthName = "September";
			break;
		case '10':
			$monthName = "October";
			break;
		case '11':
			$monthName = "November";
			break;
		case '12':
			$monthName = "December";
			break;
		default:
			$monthName = "";
			break;
	}

	// echo $where;
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Termination of Contract</title>
	</head>
	<body>

		<div style="width:90%;margin-left;auto;margin-right:auto">	
		<center><h4>END OF CONTRACT LIST for <?php echo $monthName." ".$year;?></h4></center>

		<table class='table table-bordered' border='1'>
			<tr>
				<th>NO</th>
				<th>EMP.ID</th>
				<th>NAME</th>
				<th>COMPANY</th>
				<th>BUSINESS UNIT</th>				      
				<th>DEPARTMENT</th>
				<th>POSITION</th> 
				<th>STARTDATE</th>
				<th>EOCDATE</th>
				<th>COMPANY DURATION</th>
				<th>PROMO TYPE</th>
			</tr>
			<?php 
				
				$counter = 0;
				$query = mysql_query("SELECT employee3.emp_id, name, startdate, eocdate, position, promo_company, promo_department, promo_type, company_duration
											FROM employee3, promo_record 
												WHERE employee3.emp_id = promo_record.emp_id AND (emp_type = 'Promo' OR emp_type = 'Promo-NESCO' OR emp_type = 'Promo-EasyL') AND current_status = 'Active' $where ORDER BY name ASC")or die(mysql_error());
				while ($row = mysql_fetch_array($query)) {
					
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

					if ($row['company_duration'] == "0000-00-00") {
						
						$companyDuration = "";
					} else {

						$companyDuration = date("m/d/Y", strtotime($row['company_duration']));
					}

					$counter++;
					echo "<tr>
							<td>$counter</td>
							<td>".$row['emp_id']."</td>
							<td>".ucwords(strtolower($row['name']))."</td>
							<td>".$row['promo_company']."</td>
							<td>".ucwords(strtolower($storeName))."</td>
							<td>".$row['promo_department']."</td>
							<td>".ucwords(strtolower($row['position']))."</td>
							<td>".date('m/d/Y', strtotime($row['startdate']))."</td>
							<td>".date('m/d/Y', strtotime($row['eocdate']))."</td>
							<td>".$companyDuration."</td>
							<td>".$row['promo_type']."</td>
						</tr>";
				}
			?>
		</table> 
	</body>
</html>