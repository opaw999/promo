<?php
session_start();
include("../../../connection.php");

/****************************************************************************************************/
$filename = "Duty Schedule Report";
header("Content-Type: application/vnd.ms-excel");
header("Content-disposition: attachment; filename=" . $filename . ".xls");

/********************************** values being passed from report_details **************************/

$company = mysql_real_escape_string($_GET['company']);
$store = $_GET['store'];
$department = $_GET['department'];
$status = mysql_real_escape_string($_GET['status']);

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

$schedule = mysql_query("SELECT bunit_name, bunit_dutySched, bunit_dutyDays, bunit_specialSched, bunit_specialDays FROM locate_promo_business_unit WHERE bunit_id = '" . $bunit[0] . "'") or die(mysql_error());
$sched = mysql_fetch_array($schedule);

$fields = $sched['bunit_dutySched'] . ", " . $sched['bunit_dutyDays'] . ", " . $sched['bunit_specialSched'] . ", " . $sched['bunit_specialDays'];
?>

<!DOCTYPE html>
<html>

<body>
	<div style="width:90%;margin-left;auto;margin-right:auto">
		<center>
			<h3>Updated Masterlist of Promo/Merchandiser as of <?php echo date('F d,Y'); ?></h3>
		</center>

		<?php if ($department == "") {

			$department = mysql_query("SELECT dept_name FROM locate_promo_department WHERE bunit_id = '" . $bunit[0] . "'") or die(mysql_error());
			while ($dept = mysql_fetch_array($department)) {

		?>

				<table class='table table-bordered' border='1'>
					<tr>
						<td colspan="12" style="color: #F11F1F;"><b><i><?php echo $dept['dept_name']; ?></i></b></td>
					</tr>
					<tr>
						<th>NO</th>
						<th>LASTNAME</th>
						<th>FIRSTNAME</th>
						<th>MIDDLENAME</th>
						<th>SUFFIX</th>
						<th>COMPANY</th>
						<th>POSITION</th>
						<th>DEPLOYMENT</th>
						<th>SPECIFIC DAYS</th>
						<th>TIME SCHEDULE</th>
						<th>DAYOFF</th>
						<th>CUTOFF</th>
						<th>INCLUSIVE DATES</th>
						<th>EOCDATE</th>
					</tr>
					<?php

					$counter = 1;

					$query = mysql_query("SELECT employee3.emp_id, current_status, startdate, eocdate, position, promo_company, promo_department, promo_type, dayoff, cutoff, $fields
														FROM employee3, promo_record 
															WHERE employee3.emp_id = promo_record.emp_id AND (emp_type = 'Promo' OR emp_type = 'Promo-NESCO' OR emp_type = 'Promo-EasyL') $where AND promo_department = '" . $dept['dept_name'] . "' ORDER BY name ASC") or die(mysql_error());
					while ($row = mysql_fetch_array($query)) {

						$fullname = mysql_query("SELECT firstname, middlename, lastname, suffix FROM applicant WHERE app_id = '" . $row['emp_id'] . "'") or die(mysql_error());
						$info = mysql_fetch_array($fullname);

						$lastname = $info['lastname'];
						$firstname = $info['firstname'];
						$middlename = $info['middlename'];

						if ($row[$sched['bunit_specialDays']] != "") {

							$dutySched = $row[$sched['bunit_dutySched']] . " & " . $row[$sched['bunit_specialSched']];
							$dutyDays = $row[$sched['bunit_dutyDays']] . " & " . $row[$sched['bunit_specialDays']];
						} else {

							$dutySched = $row[$sched['bunit_dutySched']];
							$dutyDays = $row[$sched['bunit_dutyDays']];
						}

						echo "
									<tr>
										<td>" . $counter . "</td>
										<td>" . utf8_decode(ucwords(strtolower($lastname))) . "</td>
										<td>" . utf8_decode(ucwords(strtolower($firstname))) . "</td>
										<td>" . utf8_decode(ucwords(strtolower($middlename))) . "</td>
										<td>" . ucwords(strtolower($info['suffix'])) . "</td>
										<td>" . $row['promo_company'] . "</td>
										<td>" . $row['position'] . "</td>
										<td>" . strtolower($row['promo_type']) . "</td>
										<td>" . strtoupper($dutyDays) . "</td>
										<td>" . $dutySched . "</td>
										<td>" . $row['dayoff'] . "</td>
										<td>" . $row['cutoff'] . "</td>
										<td>" . date('m/d/Y', strtotime($row['startdate'])) . " - " . date('m/d/Y', strtotime($row['eocdate'])) . "</td>
										<td>" . date('m/d/Y', strtotime($row['eocdate'])) . "</td>
									</tr>
								";

						$counter++;
					}
					?>
				</table><br><br>
			<?php
			}
		} else { ?>

			<table class='table table-bordered' border='1'>
				<tr>
					<td colspan="14" style="color: #F11F1F;"><b><i><?php echo $department; ?></i></b></td>
				</tr>
				<tr>
					<th>NO</th>
					<th>LASTNAME</th>
					<th>FIRSTNAME</th>
					<th>MIDDLENAME</th>
					<th>SUFFIX</th>
					<th>COMPANY</th>
					<th>POSITION</th>
					<th>DEPLOYMENT</th>
					<th>SPECIFIC DAYS</th>
					<th>TIME SCHEDULE</th>
					<th>DAYOFF</th>
					<th>CUTOFF</th>
					<th>INCLUSIVE DATES</th>
					<th>EOCDATE</th>
				</tr>
				<?php

				$counter = 1;

				$query = mysql_query("SELECT employee3.emp_id, current_status, startdate, eocdate, position, promo_company, promo_department, promo_type, dayoff, cutoff, $fields
													FROM employee3, promo_record 
														WHERE employee3.emp_id = promo_record.emp_id AND (emp_type = 'Promo' OR emp_type = 'Promo-NESCO' OR emp_type = 'Promo-EasyL') $where ORDER BY name ASC") or die(mysql_error());
				while ($row = mysql_fetch_array($query)) {

					$fullname = mysql_query("SELECT firstname, middlename, lastname, suffix FROM applicant WHERE app_id = '" . $row['emp_id'] . "'") or die(mysql_error());
					$info = mysql_fetch_array($fullname);

					$lastname = $info['lastname'];
					$firstname = $info['firstname'];
					$middlename = $info['middlename'];

					if ($row[$sched['bunit_specialSched']] != "") {

						$dutySched = $row[$sched['bunit_dutySched']] . " & " . $row[$sched['bunit_specialSched']];
						$dutyDays = $row[$sched['bunit_dutyDays']] . " & " . $row[$sched['bunit_specialDays']];
					} else {

						$dutySched = $row[$sched['bunit_dutySched']];
						$dutyDays = $row[$sched['bunit_dutyDays']];
					}

					echo "
								<tr>
									<td>" . $counter . "</td>
									<td>" . utf8_decode(ucwords(strtolower($lastname))) . "</td>
									<td>" . utf8_decode(ucwords(strtolower($firstname))) . "</td>
									<td>" . utf8_decode(ucwords(strtolower($middlename))) . "</td>
									<td>" . ucwords(strtolower($info['suffix'])) . "</td>
									<td>" . $row['promo_company'] . "</td>
									<td>" . $row['position'] . "</td>
									<td>" . strtolower($row['promo_type']) . "</td>
									<td>" . $dutySched . "</td>
									<td>" . strtoupper($dutyDays) . "</td>
									<td>" . $row['dayoff'] . "</td>
									<td>" . $row['cutoff'] . "</td>
									<td>" . date('m/d/Y', strtotime($row['startdate'])) . " - " . date('m/d/Y', strtotime($row['eocdate'])) . "</td>
									<td>" . date('m/d/Y', strtotime($row['eocdate'])) . "</td>
								</tr>
							";

					$counter++;
				}
				?>
			</table><?php
				}
					?>
</body>

</html>

<?php
//********************************* for report logs	*********************************************//

$activity 		= "Generate Duty Schedule Report as of " . date("F d, Y");
$date 			= date("Y-m-d");
$time 			= date("H:i:s");
$nq->savelogs($activity, $date, $time, $_SESSION['emp_id'], $_SESSION['username']);

/************************************************************************************************/
?>