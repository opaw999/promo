<?php 

	require_once('connection.php');
	
	$query = mysql_query("SELECT employee3.record_no, employee3.emp_id, name, emp_type, current_status FROM employee3 INNER JOIN promo_record ON employee3.record_no = promo_record.record_no AND employee3.emp_id = promo_record.emp_id
		WHERE emp_type LIKE 'Promo%'")or die(mysql_error());
	while ($row = mysql_fetch_array($query)) {

		$empId = $row['emp_id'];
		// echo $empId .' =>'.$row['name'].'=>'.$row['emp_type'].'<br>';

		$corporate = 'false';
		$talibon = 'false';
		$tubigon = 'false';
		$colon = 'false';
		$query2 = mysql_query("SELECT bunit_field FROM pis.locate_promo_business_unit WHERE status = 'active'")or die(mysql_error());
		while ($bf = mysql_fetch_array($query2)) {
			
			$chk_store = mysql_query("SELECT COUNT(promo_id) AS exist FROM promo_record WHERE record_no = '".$row['record_no']."' AND emp_id = '".$empId."' AND ".$bf['bunit_field']." = 'T'")or die(mysql_error());
			$store = mysql_fetch_array($chk_store);
			if ($store['exist'] > 0 && $bf['bunit_field'] == 'al_tal') {
				
				$talibon = 'true';
			}
			else if ($store['exist'] > 0 && $bf['bunit_field'] == 'al_tub') {

				$tubigon = 'true';
			}
			else if ($store['exist'] > 0 && ($bf['bunit_field'] == 'colm' || $bf['bunit_field'] == 'colc')) {
				
				$colon = 'true';
			} 
			else {

				$corporate = 'true';
			}
		}

		echo $empId .' =>'.$row['record_no'].' =>'.$row['name'].'=>'.$row['current_status'].'<br>';
		if ($corporate == 'true') {

			include("connection.php");
			
			$sql = mysql_query("SELECT peId, statCut, recordNo, empId FROM timekeeping.promo_sched_emp WHERE recordNo = '".$row['record_no']."' AND empId = '".$empId."'")or die(mysql_error());
			$exist = mysql_num_rows($sql);
			if ($exist == 0) {

				$query3 = mysql_query("SELECT peId, recordNo FROM timekeeping.promo_sched_emp WHERE recordNo IN ('', '0') AND empId = '".$empId."'")or die(mysql_error());
				if (mysql_num_rows($query3) > 0) {
					
					$e = mysql_fetch_array($query3);
					mysql_query("UPDATE timekeeping.promo_sched_emp SET recordNo = '".$row['record_no']."' WHERE recordNo = '".$e['recordNo']."' AND empId='".$empId."'")or die(mysql_error());
				}

			}
		}

		if ($talibon == 'true') {

			include("config_talibon.php");
			
			$sql = mysql_query("SELECT peId, statCut, recordNo, empId FROM timekeeping.promo_sched_emp WHERE recordNo = '".$row['record_no']."' AND empId = '".$empId."'")or die(mysql_error());
			$exist = mysql_num_rows($sql);
			if ($exist == 0) {

				$query3 = mysql_query("SELECT peId, recordNo FROM timekeeping.promo_sched_emp WHERE recordNo IN ('', '0') AND empId = '".$empId."'")or die(mysql_error());
				if (mysql_num_rows($query3) > 0) {
					
					$e = mysql_fetch_array($query3);
					mysql_query("UPDATE timekeeping.promo_sched_emp SET recordNo = '".$row['record_no']."' WHERE recordNo = '".$e['recordNo']."' AND empId='".$empId."'")or die(mysql_error());
				}

			}

			// mysql_close($con);
		}

		if ($tubigon == 'true') {
			
			include("config_tubigon.php");

			$sql = mysql_query("SELECT peId, statCut, recordNo, empId FROM timekeeping.promo_sched_emp WHERE recordNo = '".$row['record_no']."' AND empId = '".$empId."'")or die(mysql_error());
			$exist = mysql_num_rows($sql);
			if ($exist == 0) {

				$query3 = mysql_query("SELECT peId, recordNo FROM timekeeping.promo_sched_emp WHERE recordNo IN ('', '0') AND empId = '".$empId."'")or die(mysql_error());
				if (mysql_num_rows($query3) > 0) {
					
					$e = mysql_fetch_array($query3);
					mysql_query("UPDATE timekeeping.promo_sched_emp SET recordNo = '".$row['record_no']."' WHERE recordNo = '".$e['recordNo']."' AND empId='".$empId."'")or die(mysql_error());
				}

			}

			// mysql_close($con);
		}
		echo 'Processing Cut-off Done!<br><br>';
	}
?>