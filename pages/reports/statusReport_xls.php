<?php
session_start();
include("../../../connection.php");

/****************************************************************************************************/
$filename = "Monthly Status Report";
header("Content-Type: application/vnd.ms-excel");
header("Content-disposition: attachment; filename=" . $filename . ".xls");

/********************************** values being passed from report_details **************************/

$company = mysql_real_escape_string($_GET['company']);
$store = $_GET['store'];
$department = $_GET['department'];
$status = mysql_real_escape_string($_GET['status']);
$dateAsOf = date("Y-m-d", strtotime($_GET['dateAsOf']));

$where = "";

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

if (!empty($status)) {

	$where .= " AND current_status = '$status'";
}

if (!empty($dateAsOf)) {

	$where .= " AND startdate <= '$dateAsOf'";
}

?>

<!DOCTYPE html>
<html>

<head>
	<title>Status Report</title>
</head>

<body>

	<div style="width:90%;margin-left:auto;margin-right:auto">
		<center>
			<h4>Monthly Status Report as of <?php echo date("F d, Y", strtotime($dateAsOf)); ?></h4>
		</center>

		<table class='table table-bordered' border='1'>
			<tr>
				<th>NO</th>
				<th>EMP.ID</th>
				<th>NAME</th>
				<th>COMPANY</th>
				<th>BUSINESS UNIT</th>
				<th>DEPARTMENT</th>
				<th>POSITION</th>
				<th>PROMO TYPE</th>
				<th>STARTDATE</th>
				<th>EOCDATE</th>
				<th>CURRENT STATUS</th>
			</tr>
			<?php

			$counter = 0;

			$query = mysql_query("SELECT employee3.emp_id, name, current_status, startdate, eocdate, position, promo_company, promo_department, promo_type, company_duration
											FROM employee3, promo_record 
												WHERE employee3.emp_id = promo_record.emp_id AND (emp_type = 'Promo' OR emp_type = 'Promo-NESCO' OR emp_type = 'Promo-EasyL') $where ORDER BY name ASC") or die(mysql_error());
			while ($row = mysql_fetch_array($query)) {

				$storeName = "";
				$ctr = 0;
				$bunit = mysql_query("SELECT bunit_field, bunit_name FROM `locate_promo_business_unit`") or die(mysql_error());
				while ($str = mysql_fetch_array($bunit)) {

					$promo = mysql_query("SELECT promo_id FROM `promo_record` WHERE `" . $str['bunit_field'] . "` = 'T' AND emp_id = '" . $row['emp_id'] . "'") or die(mysql_error());
					if (mysql_num_rows($promo) > 0) {
						$ctr++;

						if ($ctr == 1) {

							$storeName = $str['bunit_name'];
						} else {

							$storeName .= ", " . $str['bunit_name'];
						}
					}
				}

				$counter++;
				echo "<tr>
							<td>$counter</td>
							<td>" . $row['emp_id'] . "</td>
							<td>" . ucwords(strtolower($row['name'])) . "</td>
							<td>" . $row['promo_company'] . "</td>
							<td>" . ucwords(strtolower($storeName)) . "</td>
							<td>" . $row['promo_department'] . "</td>
							<td>" . ucwords(strtolower($row['position'])) . "</td>
							<td>" . $row['promo_type'] . "</td>
							<td>" . date('m/d/Y', strtotime($row['startdate'])) . "</td>
							<td>" . date('m/d/Y', strtotime($row['eocdate'])) . "</td>
							<td>" . $row['current_status'] . "</td>
						</tr>";
			}
			?>
		</table>
</body>

</html>

<?php
//********************************* for report logs	*********************************************//

$activity 		= "Generate Monthly Status Report as of " . date("F d, Y", strtotime($dateAsOf));
$date 			= date("Y-m-d");
$time 			= date("H:i:s");
$nq->savelogs($activity, $date, $time, $_SESSION['emp_id'], $_SESSION['username']);

/************************************************************************************************/
?>