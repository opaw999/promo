<?php

session_start();
include("connection.php");
mysql_query("set character_set_results='utf8'");
date_default_timezone_set("Asia/Manila");

$date2d = date("Y-m-d"); //date today
$date 	= date('Y-m-d');
$time 	= date("H:i:s");

// location
$loginId = $_SESSION['emp_id'];
$hrLocation = mysql_query("SELECT company_code, bunit_code, dept_code FROM `employee3` WHERE emp_id = '$loginId' AND current_status = 'Active'") or die(mysql_error());
$hr = mysql_fetch_array($hrLocation);

$ccHr = $hr['company_code'];
$bcHr = $hr['bunit_code'];
$dcHr = $hr['dept_code'];

$locateHR = "asc";
if ($ccHr != "07") {
	$hrCode = 'asc';
} else {

	$hrCode = 'cebo';
	if ($ccHr == "07" && $bcHr == "01") {

		$locateHR = "colc";
	} else {
		$locateHR = "colm";
	}
}

$promoEmpType = "(emp_type = 'Promo' OR emp_type = 'Promo-NESCO' OR emp_type = 'Promo-EasyL')";
$datab2 = "timekeeping";

if ($_GET['request'] == "loadBlacklists") {

	// storing  request (ie, get/post) global array to a variable  
	$requestData = $_REQUEST;
	$columns = array(
		// datatable column index  => database column lastname
		0 => 'app_id',
		1 => 'name',
		2 => 'reportedby',
		3 => 'date_blacklisted',
		4 => 'reason',
		5 => 'action'
	);

	// getting total number records without any search
	$sql = " SELECT app_id,blacklist_no, name, status, reason, reportedby, date_blacklisted FROM `blacklist` ";
	$query = mysql_query($sql) or die(mysql_error());
	$totalData = mysql_num_rows($query);
	$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

	$sql = "SELECT app_id, blacklist_no, name, status, reason, reportedby, date_blacklisted FROM `blacklist` WHERE 1=1";
	if (!empty($requestData['search']['value'])) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
		$sql .= " AND ( app_id LIKE '%" . $requestData['search']['value'] . "%' ";
		$sql .= " OR name LIKE '%" . $requestData['search']['value'] . "%' ";
		$sql .= " OR reportedby LIKE '%" . $requestData['search']['value'] . "%' ";
		$sql .= " OR DATE_FORMAT(date_blacklisted, '%m/%d/%Y') LIKE '%" . $requestData['search']['value'] . "%' ";
		$sql .= " OR reason LIKE '%" . $requestData['search']['value'] . "%' )";
	}

	$query = mysql_query($sql) or die(mysql_error());
	$totalFiltered = mysql_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
	$sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "  LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
	/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */
	$query = mysql_query($sql) or die(mysql_error());

	$data = array();
	$dateB = "";
	while ($row = mysql_fetch_array($query)) {  // preparing an array

		if ($row['date_blacklisted'] != NULL or $row['date_blacklisted'] != '') {
			$dateB = date('m/d/Y', strtotime($row['date_blacklisted']));
		}

		$blacklistNo = $row['blacklist_no'];

		$link = "<button type='submit' class='btn btn-default' onclick='editBlacklist(\"$blacklistNo\")'><i class='fa fa-pencil'></i> Edit</button>";
		$link_emp = "<a href=?p=profile&&module=Promo&&com=" . $row['app_id'] . ">" . $row['app_id'] . "</a>";

		$nestedData = array();
		$nestedData[] = $link_emp;
		$nestedData[] = $row["name"];
		$nestedData[] = $row["reportedby"];
		$nestedData[] = $dateB;
		$nestedData[] = $row["reason"];
		$nestedData[] = $link;
		$data[] = $nestedData;
	}

	$json_data = array(
		"draw"            => intval($requestData['draw']),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
		"recordsTotal"    => intval($totalData),  // total number of records
		"recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
		"data"            => $data   // total data array
	);
	echo json_encode($json_data);  // send data as json format
} else if ($_GET['request'] == "loadEOCList") {

	$monthplus 	= date('Y-m-d', strtotime('+1 month')); //date one month from the current date
	$monthminus = date('Y-m-d', strtotime('-1 month')); //date one month from the current date
	$plus3 		= date('Y-m-d', strtotime('+3 month')); //date 3 month from the current date
	$minus3 	= date('Y-m-d', strtotime('-3 month')); //date 3 month from the current date
	$yesterday 	= date("Y-m-d", strtotime('-1 days'));
	$last7days  = date('Y-m-d', strtotime('-7 days'));

	$filterBU = $_POST['filterBU'];
	$filter = "";
	if ($_POST['filterDate'] != "") {

		$filter = $_POST['filterDate'];
	}

	if ($_POST['filterMonth'] != "") {

		$filter = $_POST['filterMonth'];
	}

	if ($filter != "") {

		$year = $_POST['filterYear'];
		$filterEOC = $year . "-" . $filter . "%";
		switch ($filter) {
			case "01":
				$whereFilterEOC = "AND eocdate LIKE '$filterEOC' ";
				break;
			case "02":
				$whereFilterEOC = "AND eocdate LIKE '$filterEOC' ";
				break;
			case "03":
				$whereFilterEOC = "AND eocdate LIKE '$filterEOC' ";
				break;
			case "04":
				$whereFilterEOC = "AND eocdate LIKE '$filterEOC' ";
				break;
			case "05":
				$whereFilterEOC = "AND eocdate LIKE '$filterEOC' ";
				break;
			case "06":
				$whereFilterEOC = "AND eocdate LIKE '$filterEOC' ";
				break;
			case "07":
				$whereFilterEOC = "AND eocdate LIKE '$filterEOC' ";
				break;
			case "08":
				$whereFilterEOC = "AND eocdate LIKE '$filterEOC' ";
				break;
			case "09":
				$whereFilterEOC = "AND eocdate LIKE '$filterEOC' ";
				break;
			case "10":
				$whereFilterEOC = "AND eocdate LIKE '$filterEOC' ";
				break;
			case "11":
				$whereFilterEOC = "AND eocdate LIKE '$filterEOC' ";
				break;
			case "12":
				$whereFilterEOC = "AND eocdate LIKE '$filterEOC' ";
				break;
			case "today":
				$whereFilterEOC = "AND eocdate = '$date' ";
				break;
			case "yesterday":
				$whereFilterEOC = "AND eocdate = '$yesterday' ";
				break;
			case "last7days":
				$whereFilterEOC = "AND eocdate BETWEEN '$last7days' AND '$date' ";
				break;
			case "last1month":
				$whereFilterEOC = "AND eocdate BETWEEN '$monthminus' AND '$date' ";
				break;
			default:
				$whereFilterEOC = "AND eocdate BETWEEN '$minus3' AND '$monthplus'";
				break;
		}
	} else {

		$whereFilterEOC = "AND eocdate BETWEEN '$minus3' AND '$monthplus'";
	}

	if ($filterBU != "") {

		$whereFilterEOC .= "AND $filterBU = 'T'";
	}

	// storing  request (ie, get/post) global array to a variable  
	$requestData = $_REQUEST;
	$columns = array(
		// datatable column index  => database column lastname
		0 => 'employee3.name',
		1 => 'employee3.startdate',
		2 => 'employee3.eocdate'
	);

	// getting total number records without any search

	$sql = " SELECT employee3.record_no, employee3.emp_id, employee3.name, employee3.startdate, employee3.eocdate FROM `employee3` INNER JOIN `promo_record` ON employee3.emp_id = promo_record.emp_id WHERE $promoEmpType AND hr_location = '$hrCode' AND (employee3.current_status = 'Active' OR employee3.current_status = 'End of Contract' OR employee3.current_status = 'V-Resigned' OR employee3.current_status = 'Ad-Resigned') $whereFilterEOC";
	$query = mysql_query($sql) or die(mysql_error());
	$totalData = mysql_num_rows($query);
	$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

	$sql = "SELECT employee3.record_no, employee3.emp_id, employee3.name, employee3.startdate, employee3.eocdate FROM `employee3` INNER JOIN `promo_record` ON employee3.emp_id = promo_record.emp_id WHERE 1=1 AND $promoEmpType AND hr_location = '$hrCode' AND (employee3.current_status = 'Active' OR employee3.current_status = 'End of Contract' OR employee3.current_status = 'V-Resigned' OR employee3.current_status = 'Ad-Resigned') $whereFilterEOC";
	if (!empty($requestData['search']['value'])) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
		$sql .= " AND ( employee3.emp_id LIKE '%" . $requestData['search']['value'] . "%' ";
		$sql .= " OR employee3.name LIKE '%" . $requestData['search']['value'] . "%' ";
		$sql .= " OR DATE_FORMAT(employee3.startdate, '%m/%d/%Y') LIKE '%" . $requestData['search']['value'] . "%' ";
		$sql .= " OR DATE_FORMAT(employee3.eocdate, '%m/%d/%Y') LIKE '%" . $requestData['search']['value'] . "%' )";
	}
	$query = mysql_query($sql) or die(mysql_error());
	$totalFiltered = mysql_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
	$sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "  LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
	/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */
	$query = mysql_query($sql) or die(mysql_error());

	$data = array();
	while ($row = mysql_fetch_array($query)) {  // preparing an array

		$name = ucwords(strtolower($row['name']));
		$empId = $row['emp_id'];
		$recordNo = $row['record_no'];

		$nestedData = array();
		$nestedData[] = "<a href='?p=profile&&module=Promo&&com=$empId' target='_blank'>$name</a>";
		$nestedData[] = date("m/d/Y", strtotime($row['startdate']));
		$nestedData[] = date("m/d/Y", strtotime($row['eocdate']));

		$rate = $label = $label2 = "";
		$al_tagEpas = $al_talEpas = $icmEpas = $pmEpas = $al_tubEpas = $colcEpas = $colmEpas = $altaEpas = 0;
		$al_tagComment = $al_talComment = $icmComment = $pmComment = $al_tubComment = $colcComment = $colmComment =  $altaComment = "yes";
		$al_tagStore = $al_talStore = $icmStore = $pmStore = $al_tubStore = $colcStore = $colmStore = $altaStore = "";

		if ($hrCode == "asc") {

			$stores = mysql_query("SELECT bunit_name, bunit_field, bunit_epascode FROM `locate_promo_business_unit` WHERE appraisal_status = 'active' AND hrd_location = '$hrCode'") or die(mysql_error());
			while ($bU = mysql_fetch_array($stores)) {

				$promo = mysql_query("SELECT promo_id, " . $bU['bunit_epascode'] . " FROM promo_record WHERE " . $bU['bunit_field'] . " = 'T' AND emp_id = '$empId' AND record_no = '$recordNo'") or die(mysql_error());
				if (mysql_num_rows($promo) > 0) {

					$storeApp = mysql_query("SELECT details_id, numrate, descrate, raterSO, rateeSO FROM `appraisal_details` WHERE emp_id = '$empId' AND record_no = '$recordNo' AND store = '" . $bU['bunit_name'] . "'") or die(mysql_error());
					$epas = mysql_fetch_array($storeApp);

					$raterSO = $epas['raterSO'];
					$rateeSO = $epas['rateeSO'];
					$numrate = $epas['numrate'];

					if ($numrate != "") {

						if ($raterSO == 1 && $rateeSO == 1) {

							$rate = "yes";
							$label = "label label-success";
						} else {

							$rate = "no";
							$label = "label label-warning";
						}

						if ($numrate == 100) {
							$label2 = "label label-success";
						} else if ($numrate >= 90 && $numrate <= 99.99) {
							$label2 = "label label-primary";
						} else if ($numrate >= 85 && $numrate <= 89.99) {
							$label2 = "label label-info";
						} else if ($numrate >= 70 && $numrate <= 84.99) {
							$label2 = "label label-danger";
						} else if ($numrate >= 0 && $numrate <= 69.99) {
							$label2 = "label label-danger";
						} else {
							$label2 = "label label-danger";
						}

						if ($bU['bunit_name'] == "ASC: MAIN") {
							$al_tagEpas = $numrate;
							$al_tagComment = $rate;
						} else if ($bU['bunit_name'] == "ALTURAS TALIBON") {
							$al_talEpas = $numrate;
							$al_talComment = $rate;
						} else if ($bU['bunit_name'] == "ISLAND CITY MALL") {
							$icmEpas = $numrate;
							$icmComment = $rate;
						} else if ($bU['bunit_name'] == "PLAZA MARCELA") {
							$pmEpas = $numrate;
							$pmComment = $rate;
						} else if ($bU['bunit_name'] == "ALTURAS TUBIGON") {
							$al_tubEpas = $numrate;
							$al_tubComment = $rate;
						} else if ($bU['bunit_name'] == "ALTA CITTA") {
							$altaEpas = $numrate;
							$altaComment = $rate;
						}

						$nestedData[] = "<a href='javascript:void(0)' onclick=viewdetails('$epas[details_id]') title='Click to View Appraisal Details'> <span class='$label2'>$numrate</span></a> <span class='$label'>$rate</span>";
					} else {

						if ($bU['bunit_name'] == "ASC: MAIN") {
							$al_tagEpas = 0;
							$al_tagComment = "no";
						} else if ($bU['bunit_name'] == "ALTURAS TALIBON") {
							$al_talEpas = 0;
							$al_talComment = "no";
						} else if ($bU['bunit_name'] == "ISLAND CITY MALL") {
							$icmEpas = 0;
							$icmComment = "no";
						} else if ($bU['bunit_name'] == "PLAZA MARCELA") {
							$pmEpas = 0;
							$pmComment = "no";
						} else if ($bU['bunit_name'] == "ALTURAS TUBIGON") {
							$al_tubEpas = 0;
							$al_tubComment = "no";
						} else if ($bU['bunit_name'] == "ALTA CITTA") {
							$altaEpas = 0;
							$altaComment = "no";
						}

						$nestedData[] = "<span class='label label-default'>none</span>";
					}
				} else {

					if ($bU['bunit_name'] == "ASC: MAIN") {
						$al_tagStore = "none";
					} else if ($bU['bunit_name'] == "ALTURAS TALIBON") {
						$al_talStore = "none";
					} else if ($bU['bunit_name'] == "ISLAND CITY MALL") {
						$icmStore = "none";
					} else if ($bU['bunit_name'] == "PLAZA MARCELA") {
						$pmStore = "none";
					} else if ($bU['bunit_name'] == "ALTURAS TUBIGON") {
						$al_tubStore = "none";
					} else if ($bU['bunit_name'] == "ALTA CITTA") {
						$altaStore = "none";
					}

					$nestedData[] = "";
				}
			}

			$option = "";

			if (($al_tagEpas >= 85 || $al_tagStore == "none") && ($al_talEpas >= 85 || $al_talStore == "none") && ($icmEpas >= 85 || $icmStore == "none") && ($pmEpas >= 85 || $pmStore == "none") && ($al_tubEpas >= 85 || $al_tubStore == "none") && ($altaEpas >= 85 || $altaStore == "none")) {

				if ($al_tagComment == "yes" && $al_talComment == "yes" && $icmComment == "yes" && $pmComment == "yes" && $al_tubComment == "yes" && $altaComment == "yes") {

					$option = "
								<select onchange=proceedTo(this.value,'$empId','$recordNo')>
									<option value=''>Proceed To</option>
									<option value='Renewal'>Renewal</option>
									<option value='Resigned'>Resigned</option>
								</select>
							";
				} else if (($al_tagEpas >= 85 && $al_tagComment == "no") || ($al_talEpas >= 85 && $al_talComment == "no") || ($icmEpas >= 85 && $icmComment == "no") || ($pmEpas >= 85 && $pmComment == "no") || ($al_tubEpas >= 85 && $al_tubComment == "no") || ($altaEpas >= 85 && $altaComment == "no")) {

					$option = "
								<select onchange=proceedTo(this.value,'$empId','$recordNo')>
									<option value=''>Proceed To</option>
									<option value='Resigned'>Resigned</option>
									<option value='Blacklist'>Blacklist</option>
								</select>
							";
				} else {

					$option = "
								<select onchange=proceedTo(this.value,'$empId','$recordNo')>
									<option value=''>Proceed To</option>
									<option value='Resigned'>Resigned</option>
									<option value='Blacklist'>Blacklist</option>
								</select>
						";
				}
			} else {

				if ($al_tagEpas == 0 && $al_talEpas == 0 && $icmEpas == 0 && $pmEpas == 0 && $al_tubEpas == 0 && $altaEpas == 0) {

					$option = "";
				} else {

					$option = "
									<select onchange=proceedTo(this.value,'$empId','$recordNo')>
										<option value=''>Proceed To</option>
										<option value='Blacklist'>Blacklist</option>
									</select>
								";
				}
			}
		} else {

			$stores = mysql_query("SELECT bunit_name, bunit_field, bunit_epascode FROM `locate_promo_business_unit` WHERE appraisal_status = 'active' AND hrd_location = '$hrCode'") or die(mysql_error());
			while ($bU = mysql_fetch_array($stores)) {

				$promo = mysql_query("SELECT promo_id, " . $bU['bunit_epascode'] . " FROM promo_record WHERE " . $bU['bunit_field'] . " = 'T' AND emp_id = '$empId' AND record_no = '$recordNo'") or die(mysql_error());
				if (mysql_num_rows($promo) > 0) {

					$storeApp = mysql_query("SELECT details_id, numrate, descrate, raterSO, rateeSO FROM `appraisal_details` WHERE emp_id = '$empId' AND record_no = '$recordNo' AND store = '" . $bU['bunit_name'] . "'") or die(mysql_error());
					$epas = mysql_fetch_array($storeApp);

					$raterSO = $epas['raterSO'];
					$rateeSO = $epas['rateeSO'];
					$numrate = $epas['numrate'];

					if ($numrate != "") {

						if ($raterSO == 1 && $rateeSO == 1) {

							$rate = "yes";
							$label = "label label-success";
						} else {

							$rate = "no";
							$label = "label label-warning";
						}

						if ($numrate == 100) {
							$label2 = "label label-success";
						} else if ($numrate >= 90 && $numrate <= 99.99) {
							$label2 = "label label-primary";
						} else if ($numrate >= 85 && $numrate <= 89.99) {
							$label2 = "label label-info";
						} else if ($numrate >= 70 && $numrate <= 84.99) {
							$label2 = "label label-danger";
						} else if ($numrate >= 0 && $numrate <= 69.99) {
							$label2 = "label label-danger";
						} else {
							$label2 = "label label-danger";
						}

						if ($bU['bunit_name'] == "COLONNADE- COLON") {
							$colcEpas = $numrate;
							$colcComment = $rate;
						} else if ($bU['bunit_name'] == "COLONNADE- MANDAUE") {
							$colmEpas = $numrate;
							$colmComment = $rate;
						}

						$nestedData[] = "<a href='javascript:void(0)' onclick=viewdetails('$epas[details_id]') title='Click to View Appraisal Details'> <span class='$label2'>$numrate</span></a> <span class='$label'>$rate</span>";
					} else {

						if ($bU['bunit_name'] == "COLONNADE- COLON") {
							$colcEpas = 0;
							$colcComment = "no";
						} else if ($bU['bunit_name'] == "COLONNADE- MANDAUE") {
							$colmEpas = 0;
							$colmComment = "no";
						}

						$nestedData[] = "<span class='label label-default'>none</span>";
					}
				} else {

					if ($bU['bunit_name'] == "COLONNADE- COLON") {
						$colcStore = "none";
					} else if ($bU['bunit_name'] == "COLONNADE- MANDAUE") {
						$colmStore = "none";
					}

					$nestedData[] = "";
				}
			}

			$option = "";

			if (($colcEpas >= 85 || $colcStore == "none") && ($colmEpas >= 85 || $colmStore == "none")) {

				if ($colcComment == "yes" && $colmComment == "yes") {

					$option = "
								<select onchange=proceedTo(this.value,'$empId','$recordNo')>
									<option value=''>Proceed To</option>
									<option value='Renewal'>Renewal</option>
									<option value='Resigned'>Resigned</option>
								</select>
							";
				} else if (($colcEpas >= 85 && $colcComment == "no") || ($colmEpas >= 85 && $colmComment == "no")) {

					$option = "
								<select onchange=proceedTo(this.value,'$empId','$recordNo')>
									<option value=''>Proceed To</option>
									<option value='Resigned'>Resigned</option>
									<option value='Blacklist'>Blacklist</option>
								</select>
							";
				} else {

					$option = "
								<select onchange=proceedTo(this.value,'$empId','$recordNo')>
									<option value=''>Proceed To</option>
									<option value='Resigned'>Resigned</option>
									<option value='Blacklist'>Blacklist</option>
								</select>
						";
				}
			} else {

				if ($colcEpas == 0 && $colmEpas == 0) {

					$option = "";
				} else {

					$option = "
									<select onchange=proceedTo(this.value,'$empId','$recordNo')>
										<option value=''>Proceed To</option>
										<option value='Blacklist'>Blacklist</option>
									</select>
								";
				}
			}
		}

		$nestedData[] = $option;
		$data[] = $nestedData;
	}

	$json_data = array(
		"draw"            => intval($requestData['draw']),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
		"recordsTotal"    => intval($totalData),  // total number of records
		"recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
		"data"            => $data   // total data array
	);
	echo json_encode($json_data);  // send data as json format
} else if ($_GET['request'] == "loadManagePromoIncharge") {

	// storing  request (ie, get/post) global array to a variable  
	$requestData = $_REQUEST;
	$columns = array(
		// datatable column index  => database column lastname
		0 => 'promo_user.emp_id',
		1 => 'employee3.name',
		2 => 'promo_user.usertype',
		3 => 'promo_user.user_status',
		4 => 'promo_user.date_created',
		5 => 'promo_user.date_updated'
	);

	// getting total number records without any search
	$sql = " SELECT promo_user.user_no, promo_user.emp_id, promo_user.usertype, promo_user.user_status, promo_user.date_created, promo_user.date_updated, employee3.name FROM `promo_user` INNER JOIN `employee3` ON promo_user.emp_id = employee3.emp_id WHERE `usertype`!='administrator'";
	$query = mysql_query($sql) or die(mysql_error());
	$totalData = mysql_num_rows($query);
	$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

	$sql = "SELECT promo_user.user_no, promo_user.emp_id, promo_user.usertype, promo_user.user_status, promo_user.date_created, promo_user.date_updated, employee3.name FROM `promo_user` INNER JOIN `employee3` ON promo_user.emp_id = employee3.emp_id WHERE 1=1 AND `usertype`!='administrator'";
	if (!empty($requestData['search']['value'])) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
		$sql .= " AND ( promo_user.emp_id LIKE '%" . $requestData['search']['value'] . "%' ";
		$sql .= " OR employee3.name LIKE '%" . $requestData['search']['value'] . "%' ";
		$sql .= " OR DATE_FORMAT(promo_user.date_created, '%m/%d/%Y') LIKE '%" . $requestData['search']['value'] . "%' )";
	}

	$query = mysql_query($sql) or die(mysql_error());
	$totalFiltered = mysql_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
	$sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "  LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
	/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */
	$query = mysql_query($sql) or die(mysql_error());

	$data = array();
	while ($row = mysql_fetch_array($query)) {  // preparing an array

		$name = ucwords(strtolower($row['name']));
		$empId = $row['emp_id'];
		// usertype
		if ($row['usertype'] == "promo1") {
			$usertype = "<select name='usertype' onchange='usertype(\"$empId\",this.value)'>
								<option value='promo1' selected=''>Promo Incharge</option>
								<option value='promo2'>Encoder</option>
							</selet>";
		} else {

			$usertype = "<select name='usertype' onchange='usertype(\"$empId\",this.value)'>
								<option value='promo1'>Promo Incharge</option>
								<option value='promo2' selected=''>Encoder</option>
							</selet>";
		}

		// user status
		if ($row['user_status'] == "active") {
			$status = "<select name='userStatus' onchange='userStatus(\"$empId\",this.value)'>
								<option value='active' selected=''>active</option>
								<option value='inactive'>inactive</option>
							</selet>";
		} else {

			$status = "<select name='userStatus' onchange='userStatus(\"$empId\",this.value)'>
								<option value='active'>active</option>
								<option value='inactive' selected=''>inactive</option>
							</selet>";
		}

		if (!empty($row['date_updated'])) {

			$dateUpdated = date("m/d/Y h:i:s A", strtotime($row['date_updated']));
		} else {

			$dateUpdated = "";
		}

		$nestedData = array();
		$nestedData[] = "<a href='?p=profile&&module=Promo&&com=" . $row['emp_id'] . "'>" . $row['emp_id'] . "</a>";
		$nestedData[] = $name;
		$nestedData[] = $usertype;
		$nestedData[] = $status;
		$nestedData[] = date("m/d/Y h:i:s A", strtotime($row['date_created']));
		$nestedData[] = $dateUpdated;
		$data[] = $nestedData;
	}

	$json_data = array(
		"draw"            => intval($requestData['draw']),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
		"recordsTotal"    => intval($totalData),  // total number of records
		"recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
		"data"            => $data   // total data array
	);
	echo json_encode($json_data);  // send data as json format
} else if ($_GET['request'] == "loadManagePromo") {

	// storing  request (ie, get/post) global array to a variable  
	$requestData = $_REQUEST;
	$columns = array(
		// datatable column index  => database column lastname
		0 => 'employee3.name',
		1 => 'users.username',
		2 => 'users.usertype',
		3 => 'users.user_status',
		4 => 'users.login'
	);

	// getting total number records without any search
	$sql = " SELECT employee3.name, employee3.emp_id, users.user_no, users.username, users.usertype, users.user_status, users.login  FROM `employee3` INNER JOIN `users` ON employee3.emp_id = users.emp_id WHERE $promoEmpType AND users.usertype = 'employee'";
	$query = mysql_query($sql) or die(mysql_error());
	$totalData = mysql_num_rows($query);
	$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

	$sql = "SELECT employee3.name, employee3.emp_id, users.user_no, users.username, users.usertype, users.user_status, users.login  FROM `employee3` INNER JOIN `users` ON employee3.emp_id = users.emp_id WHERE 1=1 AND $promoEmpType AND users.usertype = 'employee'";
	if (!empty($requestData['search']['value'])) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
		$sql .= " AND ( employee3.name LIKE '%" . $requestData['search']['value'] . "%' ";
		$sql .= " OR users.username LIKE '%" . $requestData['search']['value'] . "%' ";
		$sql .= " OR users.user_status LIKE '%" . $requestData['search']['value'] . "%' ";
		$sql .= " OR users.login LIKE '%" . $requestData['search']['value'] . "%' )";
	}

	$query = mysql_query($sql) or die(mysql_error());
	$totalFiltered = mysql_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
	$sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "  LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
	/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */
	$query = mysql_query($sql) or die(mysql_error());

	$data = array();
	while ($row = mysql_fetch_array($query)) {  // preparing an array

		$name = ucwords(strtolower(utf8_decode($row['name'])));
		$empId = $row['emp_id'];
		$user_no = $row['user_no'];

		if ($row['user_status'] == "active") {

			$userClass = "btn btn-success btn-xs btn-block btn-flat";
		} else {

			$userClass = "btn btn-danger btn-xs btn-block btn-flat";
		}

		$store = "";
		$ctr = 0;
		$bunit = mysql_query("SELECT bunit_field, bunit_acronym FROM `locate_promo_business_unit`") or die(mysql_error());
		while ($str = mysql_fetch_array($bunit)) {

			$promo = mysql_query("SELECT promo_id FROM `promo_record` WHERE `" . $str['bunit_field'] . "` = 'T' AND emp_id = '$empId'") or die(mysql_error());
			if (mysql_num_rows($promo) > 0) {
				$ctr++;

				if ($ctr == 1) {

					$store = $str['bunit_acronym'];
				} else {

					$store .= ", " . $str['bunit_acronym'];
				}
			}
		}

		$depart = mysql_query("SELECT promo_department FROM promo_record WHERE emp_id = '$empId'") or die(mysql_error());
		$dc = mysql_fetch_array($depart);

		$department = $dc['promo_department'];

		if ($row['user_status'] == "active") {

			$iconImage = "<a href='javascript:void(0)' title='click to deactivate account' onclick=userAction(\"$user_no\",'deactivateAccount')><img src='../images/icons/icon-close-circled-20.png' height='17' width='17'></a>";
		} else {

			$iconImage = "<a href='javascript:void(0)' title='click to activate account' onclick=userAction(\"$user_no\",'activateAccount')><img src='../images/icons/icn_active.gif' height='17' width='17'></a>";
		}

		if ($loginId == "06359-2013") {
			$trashImage = "<a href='javascript:void(0)' title='click to delete account' onclick=userAction(\"$user_no\",'deleteAccount')><img src='../images/icons/delete-icon.png' height='17' width='17'></a>";
		}

		$nestedData = array();
		$nestedData[] = "<a href='?p=profile&&module=Promo&&com=$empId'>$name</a>";
		$nestedData[] = $row['username'];
		$nestedData[] = $row['usertype'];
		$nestedData[] = "<label class='$userClass'>" . $row['user_status'] . "</label>";
		$nestedData[] = $row['login'];
		$nestedData[] = $store;
		$nestedData[] = $department;
		$nestedData[] = "<a href='javascript:void(0)' title='click to reset password' onclick=userAction(\"$user_no\",'resetPass')><img src='../images/icons/refresh.png' height='17' width='17'></a>&nbsp;$iconImage&nbsp;$trashImage";
		$data[] = $nestedData;
	}

	$json_data = array(
		"draw"            => intval($requestData['draw']),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
		"recordsTotal"    => intval($totalData),  // total number of records
		"recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
		"data"            => $data   // total data array
	);
	echo json_encode($json_data);  // send data as json format
} else if ($_GET['request'] == "loadLogs") {

	// storing  request (ie, get/post) global array to a variable  
	$requestData = $_REQUEST;
	$columns = array(
		// datatable column index  => database column lastname
		0 => 'log_no',
		1 => 'activity',
		2 => 'date',
		3 => 'username',
		4 => 'user'
	);

	// getting total number records without any search
	$sql = "SELECT * FROM `logs` WHERE user = '$loginId'";
	$query = mysql_query($sql) or die(mysql_error());
	$totalData = mysql_num_rows($query);
	$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

	$sql = "SELECT * FROM `logs` WHERE 1=1 AND user = '$loginId'";
	if (!empty($requestData['search']['value'])) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
		$sql .= " AND ( log_no LIKE '%" . $requestData['search']['value'] . "%' ";
		$sql .= " OR activity LIKE '%" . $requestData['search']['value'] . "%' ";
		$sql .= " OR user LIKE '%" . $requestData['search']['value'] . "%' ";
		$sql .= " OR username LIKE '%" . $requestData['search']['value'] . "%' ";
		$sql .= " OR DATE_FORMAT(date, '%m/%d/%Y') LIKE '%" . $requestData['search']['value'] . "%' )";
	}

	$query = mysql_query($sql) or die(mysql_error());
	$totalFiltered = mysql_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
	$sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "  LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
	/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */
	$query = mysql_query($sql) or die(mysql_error());

	$data = array();
	while ($row = mysql_fetch_array($query)) {  // preparing an array


		$nestedData = array();
		$nestedData[] = $row['log_no'];
		$nestedData[] = $row['activity'];
		$nestedData[] = date("m/d/Y", strtotime($row['date']));
		$nestedData[] = date("h:i:s A", strtotime($row['time']));
		$nestedData[] = "<a href='../placement/?p=profile&&module=Promo&&com=" . $row['user'] . "' target='_blank'>" . $row['user'] . "</a>";
		$nestedData[] = $row['username'];
		$data[] = $nestedData;
	}

	$json_data = array(
		"draw"            => intval($requestData['draw']),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
		"recordsTotal"    => intval($totalData),  // total number of records
		"recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
		"data"            => $data   // total data array
	);
	echo json_encode($json_data);  // send data as json format
} else if ($_GET['request'] == "loadlogsAdmin") {

	$today      = date("Y-m-d");
	$yesterday  = date("Y-m-d", strtotime('-1 days'));
	$last7days  = date('Y-m-d', strtotime('-7 days'));
	$last15days = date('Y-m-d', strtotime('-15 days'));

	$filterDate = $_POST['filter'];
	$condition = "";

	if (!empty($filterDate)) {

		if ($filterDate == "yesterday") {

			$condition = "AND logs.date = '$yesterday'";
		} else if ($filterDate == "last7Days") {

			$condition = "AND logs.date BETWEEN '$last7days' AND '$today'";
		} else if ($filterDate == "last15Days") {

			$condition = "AND logs.date BETWEEN '$last15days' AND '$today'";
		} else {

			$condition = "AND logs.date = '$today'";
		}
	} else {

		$condition = "";
	}
	// storing  request (ie, get/post) global array to a variable  
	$requestData = $_REQUEST;
	$columns = array(
		// datatable column index  => database column lastname
		0 => 'logs.log_no',
		1 => 'logs.activity',
		2 => 'logs.date',
		3 => 'logs.username',
		4 => 'logs.user'
	);

	// getting total number records without any search
	$sql = "SELECT logs.log_no, logs.activity, logs.date, logs.time, logs.user, logs.username FROM `logs` INNER JOIN `promo_user` ON logs.user = promo_user.emp_id WHERE promo_user.usertype != 'administrator' $condition";
	$query = mysql_query($sql) or die(mysql_error());
	$totalData = mysql_num_rows($query);
	$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

	$sql = "SELECT logs.log_no, logs.activity, logs.date, logs.time, logs.user, logs.username FROM `logs` INNER JOIN `promo_user` ON logs.user = promo_user.emp_id WHERE 1=1 AND promo_user.usertype != 'administrator' $condition";
	if (!empty($requestData['search']['value'])) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
		$sql .= " AND ( logs.log_no LIKE '%" . $requestData['search']['value'] . "%' ";
		$sql .= " OR logs.activity LIKE '%" . $requestData['search']['value'] . "%' ";
		$sql .= " OR logs.user LIKE '%" . $requestData['search']['value'] . "%' ";
		$sql .= " OR logs.username LIKE '%" . $requestData['search']['value'] . "%' ";
		$sql .= " OR DATE_FORMAT(logs.date, '%m/%d/%Y') LIKE '%" . $requestData['search']['value'] . "%' )";
	}

	$query = mysql_query($sql) or die(mysql_error());
	$totalFiltered = mysql_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
	$sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "  LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
	/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */
	$query = mysql_query($sql) or die(mysql_error());

	$data = array();
	while ($row = mysql_fetch_array($query)) {  // preparing an array


		$nestedData = array();
		$nestedData[] = $row['log_no'];
		$nestedData[] = $row['activity'];
		$nestedData[] = date("m/d/Y", strtotime($row['date']));
		$nestedData[] = date("h:i:s A", strtotime($row['time']));
		$nestedData[] = "<a href='../placement/?p=profile&&module=Promo&&com=" . $row['user'] . "' target='_blank'>" . $row['user'] . "</a>";
		$nestedData[] = $row['username'];
		$data[] = $nestedData;
	}

	$json_data = array(
		"draw"            => intval($requestData['draw']),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
		"recordsTotal"    => intval($totalData),  // total number of records
		"recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
		"data"            => $data   // total data array
	);
	echo json_encode($json_data);  // send data as json format
} else if ($_GET['request'] == "loadMasterfile") {

	$condition = "";

	if (!empty($_POST['company'])) {

		$filterQ = mysql_query("SELECT pc_name FROM `locate_promo_company` WHERE pc_code = '" . $_POST['company'] . "'") or die(mysql_error());
		$pcName = mysql_fetch_array($filterQ);

		$condition .= "AND promo_record.promo_company = '" . mysql_real_escape_string($pcName['pc_name']) . "'";
	}

	if (!empty($_POST['promoType'])) {

		$condition .= "AND promo_record.promo_type = '" . $_POST['promoType'] . "'";
	}

	if (!empty($_POST['department'])) {

		$condition .= "AND promo_record.promo_department = '" . $_POST['department'] . "'";
	}

	if (!empty($_POST['store'])) {

		$condition .= "AND promo_record." . $_POST['store'] . " = 'T'";
	}

	if (!empty($_POST['status'])) {

		$condition .= "AND employee3.current_status = '" . $_POST['status'] . "'";
	}

	// storing  request (ie, get/post) global array to a variable  
	$requestData = $_REQUEST;
	$columns = array(
		// datatable column index  => database column lastname
		0 => 'employee3.emp_id',
		1 => 'employee3.name',
		2 => 'employee3.position',
		3 => 'employee3.current_status',
		4 => 'promo_record.promo_company',
		5 => 'promo_record.promo_department',
		6 => 'promo_record.promo_type'
	);

	// getting total number records without any search
	$sql = "SELECT employee3.emp_id, employee3.name, employee3.position, employee3.current_status, promo_record.promo_company, promo_record.promo_department, promo_record.promo_type  FROM `employee3`, `promo_record` WHERE employee3.emp_id = promo_record.emp_id AND employee3.record_no = promo_record.record_no AND employee3.current_status = 'Active'  AND $promoEmpType AND promo_record.hr_location = '$hrCode' $condition";
	$query = mysql_query($sql) or die(mysql_error());
	$totalData = mysql_num_rows($query);
	$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

	$sql = "SELECT employee3.emp_id, employee3.name, employee3.position, employee3.current_status, promo_record.promo_company, promo_record.promo_department, promo_record.promo_type  FROM `employee3`, `promo_record` WHERE 1=1 AND employee3.emp_id = promo_record.emp_id AND employee3.record_no = promo_record.record_no AND employee3.current_status = 'Active'  AND $promoEmpType AND promo_record.hr_location = '$hrCode' $condition";
	if (!empty($requestData['search']['value'])) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
		$sql .= " AND ( employee3.name LIKE '%" . $requestData['search']['value'] . "%' ";
		$sql .= " OR employee3.current_status LIKE '%" . $requestData['search']['value'] . "%' ";
		$sql .= " OR employee3.position LIKE '%" . $requestData['search']['value'] . "%' ";
		$sql .= " OR promo_record.promo_company LIKE '%" . $requestData['search']['value'] . "%' ";
		$sql .= " OR promo_record.promo_department LIKE '%" . $requestData['search']['value'] . "%' ";
		$sql .= " OR promo_record.promo_type LIKE '%" . $requestData['search']['value'] . "%' )";
	}

	$query = mysql_query($sql) or die(mysql_error());
	$totalFiltered = mysql_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
	$sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "  LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
	/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */
	$query = mysql_query($sql) or die(mysql_error());

	$data = array();
	while ($row = mysql_fetch_array($query)) {  // preparing an array

		$empId = $row['emp_id'];
		// $name = utf8_decode(ucwords(strtolower($row['name'])));
		$name = ucwords(strtolower($row['name']));
		$store = "";
		$ctr = 0;
		$bunit = mysql_query("SELECT bunit_field, bunit_acronym FROM `locate_promo_business_unit`") or die(mysql_error());
		while ($str = mysql_fetch_array($bunit)) {

			$promo = mysql_query("SELECT promo_id FROM `promo_record` WHERE `" . $str['bunit_field'] . "` = 'T' AND emp_id = '$empId'") or die(mysql_error());
			if (mysql_num_rows($promo) > 0) {
				$ctr++;

				if ($ctr == 1) {

					$store = $str['bunit_acronym'];
				} else {

					$store .= ", " . $str['bunit_acronym'];
				}
			}
		}

		if (strtolower($row['current_status']) == "active") {

			$labelC = "btn btn-success btn-xs btn-flat btn-block";
		} else if (strtolower($row['current_status']) == "end of contract" || strtolower($row['current_status']) == "resigned") {

			$labelC = "btn btn-warning btn-xs btn-flat btn-block";
		} else {

			$labelC = "btn btn-danger btn-xs btn-flat btn-block";
		}

		$nestedData = array();
		$nestedData[] = "<a href='?p=profile&&module=Promo&&com=$empId' target='_blank'>$name</a>";
		$nestedData[] = $row['promo_company'];
		$nestedData[] = $store;
		$nestedData[] = $row['promo_department'];
		$nestedData[] = ucwords(strtolower($row['position']));
		$nestedData[] = $row['promo_type'];
		$nestedData[] = "<label class='$labelC'>" . $row['current_status'] . "</label>";
		$data[] = $nestedData;
	}

	$json_data = array(
		"draw"            => intval($requestData['draw']),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
		"recordsTotal"    => intval($totalData),  // total number of records
		"recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
		"data"            => $data   // total data array
	);
	echo json_encode($json_data);  // send data as json format
} else if ($_GET['request'] == "loadResignationList") {

	// storing  request (ie, get/post) global array to a variable  
	$requestData = $_REQUEST;
	$columns = array(
		// datatable column index  => database column lastname
		0 => 'employee3.name',
		1 => 'employee3.emp_type',
		2 => 'termination.date',
		3 => 'termination.remarks',
		4 => 'employee3.added_by',
		5 => 'employee3.date_updated',
	);

	// getting total number records without any search
	$sql = "SELECT employee3.emp_id, employee3.name, employee3.emp_type, termination.termination_no, termination.date, termination.remarks, termination.resignation_letter, termination.added_by, termination.date_updated, termination.resignation_letter FROM `termination` INNER JOIN `employee3` ON termination.emp_id = employee3.emp_id WHERE $promoEmpType";
	$query = mysql_query($sql) or die(mysql_error());
	$totalData = mysql_num_rows($query);
	$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

	$sql = "SELECT employee3.emp_id, employee3.name, employee3.emp_type, termination.termination_no, termination.date, termination.remarks, termination.resignation_letter, termination.added_by, termination.date_updated, termination.resignation_letter FROM `termination` INNER JOIN `employee3` ON termination.emp_id = employee3.emp_id WHERE 1=1 AND $promoEmpType";
	if (!empty($requestData['search']['value'])) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
		$sql .= " AND (employee3.emp_id LIKE '%" . $requestData['search']['value'] . "%' ";
		$sql .= " OR DATE_FORMAT(termination.date, '%m/%d/%Y') LIKE '%" . $requestData['search']['value'] . "%' ";
		$sql .= " OR employee3.name LIKE '%" . $requestData['search']['value'] . "%' ";
		$sql .= " OR employee3.emp_type LIKE '%" . $requestData['search']['value'] . "%' ";
		$sql .= " OR employee3.added_by LIKE '%" . $requestData['search']['value'] . "%' ";
		$sql .= " OR employee3.date_updated LIKE '%" . $requestData['search']['value'] . "%' )";
	}

	$query = mysql_query($sql) or die(mysql_error());
	$totalFiltered = mysql_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
	$sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "  LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
	/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */
	$query = mysql_query($sql) or die(mysql_error());

	$data = array();
	while ($row = mysql_fetch_array($query)) {  // preparing an array

		$terminationNo = $row['termination_no'];
		$empId = $row['emp_id'];

		$letter = "";
		if (!empty($row['resignation_letter'])) {

			$letter = "<button class='btn btn-primary btn-sm btn-block' onclick='resignationLetter(\"$empId\",\"$terminationNo\")'><i class='fa fa-file-image-o'></i> &nbsp;View</button>";
		} else {

			$letter = "<button class='btn btn-warning btn-sm btn-block' onclick='uploadResignationLetter(\"$empId\",\"$terminationNo\")'><i class='fa fa-upload'></i> &nbsp;Upload</button>";
		}

		$nestedData = array();
		$nestedData[] = "<a href='?p=profile&&module=Promo&&com=$empId'>" . utf8_decode(ucwords(strtolower($row['name']))) . "</a>";
		$nestedData[] = date("m/d/Y", strtotime($row['date']));
		$nestedData[] = ucwords(strtolower($nq->getPromoName($row['added_by'])));
		$nestedData[] = date("m/d/Y", strtotime($row['date_updated']));
		$nestedData[] = $row['remarks'];
		$nestedData[] = $letter;
		$data[] = $nestedData;
	}

	$json_data = array(
		"draw"            => intval($requestData['draw']),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
		"recordsTotal"    => intval($totalData),  // total number of records
		"recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
		"data"            => $data   // total data array
	);
	echo json_encode($json_data);  // send data as json format
} else if ($_GET['request'] == "editBlacklist") {

	$blacklistNo = $_POST['blacklistNo'];

	$query = mysql_query("SELECT * FROM `blacklist` WHERE blacklist_no = '$blacklistNo'") or die(mysql_error());
	$fetch = mysql_fetch_array($query);

	if (!empty($fetch['app_id'])) {

		$name = $fetch['app_id'] . " * " . $fetch['name'];
	} else {

		$name = $fetch['name'];
	}

	$dateBlacklisted = "";
	$birthday = "";
	$address = "";
	$reportedBy = "";
	if (!empty($fetch['date_blacklisted'])) {

		$dateBlacklisted = date("m/d/Y", strtotime($fetch['date_blacklisted']));
	}

	if (!empty($fetch['bday'])) {

		$birthday = date("m/d/Y", strtotime($fetch['bday']));
	}

?>
	<style type="text/css">
		.fieldReq {
			color: #f56954;
		}

		.datepicker {
			z-index: 9999 !important
		}
	</style>
	<input type="hidden" name="blacklistNo" value="<?php echo $blacklistNo; ?>">
	<div class="row">
		<div class="col-md-12">
			<div class="form-group">
				<label>Employee</label>
				<input type="text" name="" class="form-control" readonly="" value="<?php echo $name ?>">
			</div>
			<div class="form-group">
				<label>Reason</label> <i class="text-red">*</i>
				<textarea name="reason" class="form-control" rows="4" onkeyup="inputField(this.name)"><?php echo $fetch['reason']; ?></textarea>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6">
			<div class="form-group">
				<label>Date Blacklisted</label> <i class="text-red">*</i>
				<div class="input-group date">
					<div class="input-group-addon">
						<i class="fa fa-calendar"></i>
					</div>
					<input type="text" name="dateBlacklisted" class="form-control pull-right datepicker" value="<?php echo $dateBlacklisted; ?>" onchange="inputField(this.name)">
				</div>
			</div>
			<div class="form-group">
				<label>Birthday</label>
				<div class="input-group date">
					<div class="input-group-addon">
						<i class="fa fa-calendar"></i>
					</div>
					<input type="text" name="birthday" class="form-control pull-right datepicker" value="<?php echo $birthday; ?>">
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
				<label>Reported By</label> <i class="text-red">*</i>
				<div class="input-group">
					<input class="form-control" type="text" name="reportedBy" onkeyup="nameSearch(this.value)" value="<?php echo utf8_encode($fetch['reportedby']); ?>" autocomplete="off">
					<span class="input-group-addon"><i class="fa fa-child"></i></span>
				</div>
				<div class="search-results" style="display: none;"></div>
			</div>
			<div class="form-group">
				<label>Address</label>
				<input type="text" class="form-control" name="address" value="<?php echo $fetch['address']; ?>">
			</div>
		</div>
	</div>
	<script type="text/javascript">
		//Date picker
		$('.datepicker').datepicker({

			inline: true,
			changeYear: true,
			changeMonth: true
		});
	</script>
<?php
} else if ($_GET['request'] == "findSup") {

	$key = mysql_real_escape_string($_POST['str']);
	$val = "";
	$empname = mysql_query("SELECT `users`.`emp_id`,`employee3`.`name` FROM `users` INNER JOIN `employee3` ON `users`.`emp_id` = `employee3`.`emp_id`
								   	WHERE `users`.`usertype` = 'supervisor' 
								   	AND (name like '%$key%' or employee3.emp_id = '$key') order by name limit 10") or die(mysql_error());
	if (mysql_num_rows($empname) > 0) {

		while ($n = mysql_fetch_array($empname)) {
			$empId = $n['emp_id'];
			$name  = $n['name'];

			if ($val != $empId) {
				echo "<a class = \"nameFind\" href = \"javascript:void(0)\" onclick='getEmpId(\"$empId * $name\")'>" . $empId . " * " . $name . "</a></br>";
			} else {
				echo "<a class = \"afont\" href = \"javascript:void(0)\">No Result Found</a></br>";
			}
		}
	} else {

		echo "<a class = \"afont\" href = \"javascript:void(0)\">No Result Found</a></br>";
	}
} else if ($_GET['request'] == "findHrStaff") {

	$key = mysql_real_escape_string($_POST['str']);
	$val = "";
	$empname = mysql_query("SELECT `users`.`emp_id`,`employee3`.`name` FROM `users` INNER JOIN `employee3` ON `users`.`emp_id` = `employee3`.`emp_id`
								   	WHERE (usertype = 'administrator' OR usertype = 'placement1' OR usertype = 'placement2' OR usertype = 'placement3' OR usertype = 'placement4' OR usertype = 'nesco') 
								   	AND (name like '%$key%' or employee3.emp_id = '$key') GROUP BY users.emp_id order by name limit 10") or die(mysql_error());
	if (mysql_num_rows($empname) > 0) {

		while ($n = mysql_fetch_array($empname)) {
			$empId = $n['emp_id'];
			$name  = $n['name'];

			if ($val != $empId) {
				echo "<a class = \"nameFind\" href = \"javascript:void(0)\" onclick='getEmpId(\"$empId * $name\")'>" . $empId . " * " . $name . "</a></br>";
			} else {
				echo "<a class = \"afont\" href = \"javascript:void(0)\">No Result Found</a></br>";
			}
		}
	} else {

		echo "<a class = \"afont\" href = \"javascript:void(0)\">No Result Found</a></br>";
	}
} else if ($_GET['request'] == "findActivePromo") {

	$key = mysql_real_escape_string($_POST['str']);
	$val = "";
	$empname = mysql_query("SELECT employee3.emp_id, employee3.name FROM `promo_record`, `employee3` 
									WHERE promo_record.emp_id = employee3.emp_id AND employee3.current_status = 'Active' 
									AND $promoEmpType
									AND (employee3.name like '%$key%' or employee3.emp_id = '$key') AND promo_record.hr_location = '$hrCode' GROUP BY employee3.emp_id order by name limit 10") or die(mysql_error());
	if (mysql_num_rows($empname) > 0) {

		while ($n = mysql_fetch_array($empname)) {
			$empId = $n['emp_id'];
			$name  = utf8_decode($n['name']);

			$temp_name = str_replace("'", "", $name);

			if ($val != $empId) {
				echo "<a class = \"nameFind\" href = \"javascript:void(0)\" onclick='getEmpId(\"$empId * $temp_name\")'>" . $empId . " * " . $name . "</a></br>";
			} else {
				echo "<a class = \"afont\" href = \"javascript:void(0)\">No Result Found</a></br>";
			}
		}
	} else {

		echo "<a class = \"afont\" href = \"javascript:void(0)\">No Result Found</a></br>";
	}
} else if ($_GET['request'] == "findActivePromoStation") {

	$key = mysql_real_escape_string($_POST['str']);
	$val = "";
	$empname = mysql_query("SELECT employee3.emp_id, employee3.name FROM `promo_record`, `employee3` 
									WHERE promo_record.emp_id = employee3.emp_id AND promo_record.record_no = employee3.record_no AND employee3.current_status IN ('Active', 'Ad-Resigned') 
									AND $promoEmpType AND promo_type = 'ROVING'
									AND (employee3.name like '%$key%' or employee3.emp_id = '$key') AND promo_record.hr_location = '$hrCode' GROUP BY employee3.emp_id order by name limit 10") or die(mysql_error());
	if (mysql_num_rows($empname) > 0) {

		while ($n = mysql_fetch_array($empname)) {
			$empId = $n['emp_id'];
			$name  = $n['name'];

			if ($val != $empId) {
				echo "<a class = \"nameFind\" href = \"javascript:void(0)\" onclick='getEmpId(\"$empId * $name\")'>" . $empId . " * " . $name . "</a></br>";
			} else {
				echo "<a class = \"afont\" href = \"javascript:void(0)\">No Result Found</a></br>";
			}
		}
	} else {

		echo "<a class = \"afont\" href = \"javascript:void(0)\">No Result Found</a></br>";
	}
} else if ($_GET['request'] == "findAllForExtendPromo") {

	$key = mysql_real_escape_string($_POST['str']);
	$val = "";
	$empname = mysql_query("SELECT employee3.emp_id, employee3.name FROM `promo_record`, `employee3` 
									WHERE promo_record.emp_id = employee3.emp_id
									AND $promoEmpType
									AND (current_status = 'Active' OR current_status = 'End of Contract' OR current_status = 'Resigned' OR current_status = 'V-Resigned' OR current_status = 'Ad-Resigned')
									AND (employee3.name like '%$key%' or employee3.emp_id = '$key') AND promo_record.hr_location = '$hrCode' GROUP BY employee3.emp_id order by name limit 10") or die(mysql_error());
	if (mysql_num_rows($empname) > 0) {

		while ($n = mysql_fetch_array($empname)) {
			$empId = $n['emp_id'];
			$name  = $n['name'];

			if ($val != $empId) {
				echo "<a class = \"nameFind\" href = \"javascript:void(0)\" onclick='getEmpId(\"$empId * $name\")'>" . $empId . " * " . $name . "</a></br>";
			} else {
				echo "<a class = \"afont\" href = \"javascript:void(0)\">No Result Found</a></br>";
			}
		}
	} else {

		echo "<a class = \"afont\" href = \"javascript:void(0)\">No Result Found</a></br>";
	}
} else if ($_GET['request'] == "findAllPromo") {

	$key = mysql_real_escape_string($_POST['str']);
	$val = "";
	$empname = mysql_query("SELECT employee3.emp_id, employee3.name FROM `promo_record`, `employee3` 
									WHERE promo_record.emp_id = employee3.emp_id AND promo_record.record_no = employee3.record_no
									AND $promoEmpType
									AND (employee3.name like '%$key%' or employee3.emp_id = '$key') AND promo_record.hr_location = '$hrCode' GROUP BY employee3.emp_id order by name limit 10") or die(mysql_error());
	if (mysql_num_rows($empname) > 0) {

		while ($n = mysql_fetch_array($empname)) {
			$empId = $n['emp_id'];
			$name  = $n['name'];

			if ($val != $empId) {
				echo "<a class = \"nameFind\" href = \"javascript:void(0)\" onclick='getEmpId(\"$empId * $name\")'>" . $empId . " * " . $name . "</a></br>";
			} else {
				echo "<a class = \"afont\" href = \"javascript:void(0)\">No Result Found</a></br>";
			}
		}
	} else {

		echo "<a class = \"afont\" href = \"javascript:void(0)\">No Result Found</a></br>";
	}
} else if ($_GET['request'] == "findApplicant") {

	$key = mysql_real_escape_string($_POST['str']);
	$val = "";

	if (strpos($key, ",") !== false) {

		$split = explode(",", $key);
		$empname = mysql_query("SELECT `app_id`, `applicant`.firstname, `applicant`.lastname, `applicant`.middlename, `applicant`.suffix, `applicants`.`status`, `applicants`.`position`, `applicants`.app_code FROM `applicant` LEFT JOIN `applicants` ON `applicant`.appcode = `applicants`.app_code 
										WHERE (`applicant`.lastname like '%" . trim($split[0]) . "%' AND `applicant`.firstname like '" . trim($split[1]) . "%' or app_id = '$key')  ORDER BY lastname, firstname ASC limit 10") or die(mysql_error());
	} else {

		$empname = mysql_query("SELECT `app_id`, `applicant`.firstname, `applicant`.lastname, `applicant`.middlename, `applicant`.suffix, `applicants`.`status`, `applicants`.`position`, `applicants`.app_code FROM `applicant` LEFT JOIN `applicants` ON `applicant`.appcode = `applicants`.app_code 
										WHERE (`applicant`.firstname like '%$key%' or `applicant`.lastname like '%$key%' or app_id = '$key')  ORDER BY lastname, firstname ASC limit 100") or die(mysql_error());
	}

	if (mysql_num_rows($empname) > 0) {

		while ($n = mysql_fetch_array($empname)) {

			$empId = $n['app_id'];
			$name = "";

			if (!empty($n['suffix'])) {

				$name = $n['lastname'] . ", " . $n['firstname'] . " " . $n['suffix'] . ", " . $n['middlename'];
			} else {

				$name = $n['lastname'] . ", " . $n['firstname'] . " " . $n['middlename'];
			}

			if ($val != $empId) {
				echo "<a class = \"nameFind\" href = \"javascript:void(0)\" onclick='getEmpId(\"$empId * $name\")'>" . $empId . " * " . $name . "</a></br>";
			} else {
				echo "<a class = \"afont\" href = \"javascript:void(0)\">No Result Found</a></br>";
			}
		}
	} else {

		echo "<a class = \"afont\" href = \"javascript:void(0)\">No Result Found</a></br>";
	}
} else if ($_GET['request'] == "updateBlacklist") {

	$blacklistNo = $_POST['blacklistNo'];
	$reason 	= mysql_real_escape_string($_POST['reason']);
	$dateBlacklisted = date("Y-m-d", strtotime($_POST['dateBlacklisted']));

	if ($_POST['birthday'] == "") {

		$birthday = "";
	} else {

		$birthday 	= date("Y-m-d", strtotime($_POST['birthday']));
	}

	$reportedBy = $_POST['reportedBy'];
	$address 	= mysql_real_escape_string($_POST['address']);

	$query = mysql_query("UPDATE `blacklist` 
									SET 
										`date_blacklisted`='$dateBlacklisted',`reportedby`='$reportedBy',`reason`='$reason',`bday`='$birthday',`address`='$address' 
									WHERE `blacklist_no` = '$blacklistNo'") or die(mysql_error());

	die("Ok");
} else if ($_GET['request'] == "addBlacklistForm") {
?>
	<i class="text-red">Note :</i>
	<ol>
		<li>You are advised to search the lastname first to find out if the one being searched is blacklisted. </li>
		<li>if no results found, that indicates that the one being search is not an applicant nor an employee. </li>
	</ol>
	<input type="hidden" name="blackSign" value="0">
	<div class="row">
		<div class="col-md-9">
			<div class="form-group">
				<label class="col-md-1">Search</label>
				<div class="col-md-5">
					<input type="text" name="lastname" class="form-control" placeholder="Lastname">
				</div>
				<div class="col-md-5">
					<input type="text" name="firstname" class="form-control" placeholder="Firstname">
				</div>
				<div class="col-md-1">
					<button class="btn btn-primary" onclick="browseNames()"><i class="fa fa-search"></i> Search</button>
				</div>
			</div>
		</div>
	</div>
	<div id="nonApp" style="display: none;">
		<hr>
		<i class="text-red">
			No Results Found! Kindly fill up the textbox below to blacklist non-applicant or non-employee.
		</i>
		<div class="row">
			<div class="col-md-3">
				<div class="form-group">
					<label>Lastname <i>*</i></label>
					<input type="text" name="lname" class="form-control" placeholder="Lastname">
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label>Firstname <i>*</i></label>
					<input type="text" name="fname" class="form-control" placeholder="Firstname">
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label>Middlename <i>(if there is any)</i></label>
					<input type="text" name="mname" class="form-control" placeholder="Middlename">
				</div>
			</div>
			<div class="col-md-3">
				<label>&nbsp;</label><br>
				<button class="btn btn-primary" onclick="chooseToBlacklist()"><i class="fa fa-hand-pointer-o"></i> Choose to Blacklist</button>
			</div>
		</div>
	</div>
	<div id="resultBrowse"></div>
	<div style="width: 5%; margin:0 auto;">
		<br>
		<img src="../images/system/10.gif" id='loading-gif' style="display: none;">
	</div>
	<?php
} else if ($_GET['request'] == "browseNames") {

	$lastname = $_POST['lastname'];
	$firstname = $_POST['firstname'];

	$fullname = "$lastname, $firstname";
	$fullname2 = "$lastname,$firstname";

	$condition = "";
	if (!empty($lastname) && !empty($firstname)) {
		$condition = "lastname = '$lastname' AND firstname LIKE '%$firstname%'";
	} else {

		if (!empty($lastname)) {
			$condition = "lastname = '$lastname'";
		} else {
			$condition = "firstname LIKE '%$firstname%'";
		}
	}

	$query = mysql_query("SELECT app_id, lastname, firstname, middlename, suffix, appCode FROM `applicant` WHERE $condition") or die(mysql_error());
	$query2 = mysql_query("SELECT name FROM `blacklist` WHERE name LIKE '%$fullname%' OR name LIKE '%$fullname2%'") or die(mysql_error());

	$queryNum = mysql_num_rows($query);
	$queryNum2 = mysql_num_rows($query2);

	if ($queryNum == 0 && $queryNum2 == 0) {

		die("No Result Found");
	} else { ?>
		<br>
		<div class="row">
			<div class="col-md-7">
				<label>Applicant/Employee</label>
				<table class="table table-hover table-stripped">
					<?php

					while ($fetch = mysql_fetch_array($query)) {

						$fullname = "";
						if (!empty($fetch['suffix'])) {

							$fullname = $fetch['lastname'] . ", " . $fetch['firstname'] . " " . $fetch['suffix'] . ", " . $fetch['middlename'];
						} else {

							$fullname = $fetch['lastname'] . ", " . $fetch['firstname'] . " " . $fetch['middlename'];
						}

						$query3 = mysql_query("SELECT current_status FROM `employee3` WHERE emp_id = '" . $fetch['app_id'] . "'") or die(mysql_error());
						$fetchNum3 = mysql_num_rows($query3);
						$option = "";

						if ($fetchNum3 > 0) {

							$fetch3 = mysql_fetch_array($query3);
							switch ($fetch3['current_status']) {
								case 'Active':
									$label = "success";
									break;
								case 'Resigned':
									$label = "warning";
									break;
								case 'V-Resigned':
									$label = "warning";
									break;
								case 'End of Contract':
									$label = "warning";
									break;
								default:
									$label = "danger";
									$option = "disabled=''";
									break;
							}

							$currentStat = $fetch3['current_status'];
						} else {

							$query4 = mysql_query("SELECT status FROM `applicants` WHERE app_code = '" . $fetch['appCode'] . "'") or die(mysql_error());
							$fetch4 = mysql_fetch_array($query4);

							$label = "primary";
							$currentStat = $fetch4['status'];
							if (empty($currentStat)) {
								$currentStat = "N/A";
							}
						}

						echo "
								<input type='hidden' id='appName_" . $fetch['app_id'] . "' value='" . utf8_encode(ucwords(strtolower($fullname))) . "'>
								<tr>
									<td>" . utf8_encode(ucwords(strtolower($fullname))) . "</td>
									<td><span class='btn btn-flat btn-block btn-xs btn-$label'>$currentStat</span></td>
									<td><button class='btn btn-default' $option onclick=choose(\"$fetch[app_id]\")><i class='fa fa-hand-pointer-o'></i> Choose</button></td>
								</tr>";
					}
					?>
				</table>
			</div>
			<div class="col-md-5">
				<label>Blacklisted</label>
				<table class="table table-hover table-stripped">
					<?php

					while ($fetch2 = mysql_fetch_array($query2)) {

						echo "<tr>
									<td>" . utf8_encode($fetch2['name']) . "</td>
									<td><span class='btn btn-xs btn-block btn-danger btn-flat'>blacklisted</span></td>
								</tr>";
					}
					?>
				</table>
			</div>
		</div>
	<?php
	}
} else if ($_GET['request'] == "submitBlacklist") {

	$appId = $_POST['appId'];
	$appName = ucwords(strtolower($_POST['appName']));
	$reason = mysql_real_escape_string($_POST['reason']);
	$dateBlacklisted = date("Y-m-d", strtotime($_POST['dateBlacklisted']));

	if ($_POST['birthday'] == "") {

		$birthday = "";
	} else {

		$birthday 	= date("Y-m-d", strtotime($_POST['birthday']));
	}

	$reportedBy = $_POST['reportedBy'];
	$address = mysql_real_escape_string($_POST['address']);

	$query = mysql_query("INSERT INTO `blacklist`
										(`app_id`, `name`, `date_blacklisted`, `date_added`, `reportedby`, `reason`, `status`, `staff`, `bday`, `address`) 
									VALUES 
										('$appId','$appName','$dateBlacklisted','$date','$reportedBy','$reason','blacklisted','" . $_SESSION['emp_id'] . "','$birthday','$address')") or die(mysql_error());

	if (strtotime($dateBlacklisted) > strtotime(date('Y-m-d'))) {

		$current_status = 'Active';
		$user_status = 'active';
	} else {

		$current_status = 'blacklisted';
		$user_status = 'inactive';
	}

	$sql = mysql_query("UPDATE employee3 SET current_status = '" . $current_status . "' WHERE emp_id = '$appId'") or die();
	$user = mysql_query("UPDATE users SET user_status = '" . $user_status . "' WHERE emp_id = '$appId'") or die(mysql_error());

	die("Ok");
} else if ($_GET['request'] == "submitAddOutlet") {

	$empId = $_POST['empId'];
	$bunit = explode("||", $_POST['store']);
	$previousStore = $_POST['prevStore'];
	$effectiveOn = date("Y-m-d", strtotime($_POST['effectiveOn']));
	$remarks = mysql_real_escape_string($_POST['remarks']);

	$currentStore = "";
	$updateField = "";
	$updateEpas = "";
	$updateContract = "";
	$updatePermit = "";
	$updateClearance = "";
	$updateIntro = "";
	$counter = 0;

	// get current store
	for ($i = 0; $i < sizeof($bunit) -  1; $i++) {

		$counter++;
		$store = explode("/", $bunit[$i]);
		$query = mysql_query("SELECT bunit_name, bunit_epascode, bunit_contract, bunit_permit, bunit_clearance, bunit_intro FROM `locate_promo_business_unit` WHERE bunit_id = '" . $store[0] . "'") or die(mysql_error());
		$fetch = mysql_fetch_array($query);

		if ($counter == 1) {

			$currentStore = $fetch['bunit_name'];
			$updateField = "`$store[1]`='T'";
			$updateEpas = $fetch['bunit_epascode'] . "=''";
			$updateContract = $fetch['bunit_contract'] . "=''";
			$updatePermit = $fetch['bunit_permit'] . "=''";
			$updateClearance = $fetch['bunit_clearance'] . "=''";
			$updateIntro = $fetch['bunit_intro'] . "=''";
		} else {

			$currentStore .= ", " . $fetch['bunit_name'];
			$updateField .= ", `$store[1]`='T'";
			$updateEpas .= ", " . $fetch['bunit_epascode'] . "=''";
			$updateContract .= ", " . $fetch['bunit_contract'] . "=''";
			$updatePermit .= ", " . $fetch['bunit_permit'] . "=''";
			$updateClearance .= ", " . $fetch['bunit_clearance'] . "=''";
			$updateIntro .= ", " . $fetch['bunit_intro'] . "=''";
		}
	}

	$sql = mysql_query(
		"SELECT
					*
				 FROM
					employee3 
				 WHERE
					emp_id = '" . $empId . "'"
	) or die(mysql_error());
	$old_data = mysql_fetch_array($sql);
	// insert the old contrct to the employment record table
	mysql_query(
		"INSERT
				INTO
			 employmentrecord_
				(
					emp_id,
					names,
					company_code,
					bunit_code,
					dept_code,
					section_code,
					sub_section_code,
					unit_code,
					barcodeId,
					bioMetricId,
					payroll_no,
					startdate,
					eocdate,
					emp_type,
					position,
					positionlevel,
					current_status,
					lodging,
					pos_desc,
					remarks,
					epas_code,
					contract,
					permit,
					clearance,
					comments,
					date_updated,
					updatedby,
					duration,
					emp_no,
					emp_pins
				) VALUES (
					'" . $empId . "',
					'" . $old_data['name'] . "',
					'" . $old_data['company_code'] . "',
					'" . $old_data['bunit_code'] . "',
					'" . $old_data['dept_code'] . "',
					'" . $old_data['section_code'] . "',
					'" . $old_data['sub_section_code'] . "',
					'" . $old_data['unit_code'] . "',
					'" . $old_data['barcodeId'] . "',
					'" . $old_data['bioMetricId'] . "',
					'" . $old_data['payroll_no'] . "',
					'" . $old_data['startdate'] . "',
					'" . $old_data['eocdate'] . "',
					'" . $old_data['emp_type'] . "',
					'" . $old_data['position'] . "',
					'" . $old_data['positionlevel'] . "',
					'End of Contract',
					'" . $old_data['lodging'] . "',
					'" . $old_data['position_desc'] . "',
					'" . mysql_real_escape_string($old_data['remarks']) . "',
					'" . $old_data['epas_code'] . "',
					'" . $old_data['contract'] . "',
					'" . $old_data['permit'] . "',
					'" . $old_data['clearance'] . "',
					'" . $old_data['comments'] . "',
					'" . $old_data['date_updated'] . "',
					'" . $old_data['updated_by'] . "',
					'" . $old_data['duration'] . "',
					'" . $old_data['emp_no'] . "',
					'" . $old_data['emp_pins'] . "'
				)"
	) or die(mysql_error());
	$sql = mysql_query(
		"SELECT
					record_no
				  FROM
					employmentrecord_
				  WHERE
					emp_id = '" . $empId . "'
				  ORDER BY 
					record_no DESC"
	) or die(mysql_error());
	$new_rno = mysql_fetch_array($sql);
	// appraisal details
	$sql = mysql_query(
		"SELECT 
					record_no
				 FROM
					appraisal_details
				 WHERE
					record_no = '" . $old_data['record_no'] . "'
					and emp_id = '" . $empId . "'"
	) or die(mysql_error());
	$c_appdetails = mysql_num_rows($sql);
	if ($c_appdetails > 0) {
		mysql_query(
			"UPDATE
					appraisal_details
				 SET
					record_no = '" . $new_rno['record_no'] . "'
				 WHERE
					record_no = '" . $old_data['record_no'] . "'
					and emp_id = '" . $empId . "'"
		) or die(mysql_error());
	}
	// witness
	$sql = mysql_query(
		"SELECT
					rec_no
				 FROM
					employment_witness
				 WHERE
					rec_no = '" . $old_data['record_no'] . "'"
	) or die(mysql_error());
	$c_empwitness = mysql_num_rows($sql);
	if ($c_empwitness > 0) {
		mysql_query(
			"UPDATE
					employment_witness
				 SET
					rec_no = '" . $new_rno['record_no'] . "'
				 WHERE
					rec_no = '" . $old_data['record_no'] . "'"
		) or die(mysql_error());
	}
	$sql2 = mysql_query(
		"SELECT
					*
				 FROM
					promo_record 
				 WHERE
					emp_id = '" . $empId . "'"
	) or die(mysql_error());
	$old_promo_data = mysql_fetch_array($sql2);

	// insert the old contrct to the promo_history_record table
	mysql_query(
		"INSERT
				INTO
			 promo_history_record
				(
					emp_id,
					promo_company,
					promo_department,
					company_duration,
					al_tag,
					al_tal,
					icm,
					pm,
					abenson_tag,
					abenson_icm,
					al_tub,
					alta_citta,
					fr_panglao,
					fr_panglao_epascode,
					fr_panglao_contract,
					fr_panglao_permit,
					fr_panglao_clearance,
					fr_panglao_intro,
					fr_tubigon,
					fr_tubigon_epascode,
					fr_tubigon_contract,
					fr_tubigon_permit,
					fr_tubigon_clearance,
					fr_tubigon_intro,
					al_panglao,
					al_panglao_epascode,
					al_panglao_contract,
					al_panglao_permit,
					al_panglao_clearance,
					al_panglao_intro,
					alta_epascode,
					alta_contract,
					alta_permit,
					alta_clearance,
					alta_intro,
					promo_type,
					record_no,
					asc_epascode,
					tal_epascode,
					icm_epascode,
					pm_epascode,
					absna_epascode,
					absni_epascode,
					cdc_epascode,
					berama_epascode,
					tub_epascode,
					asc_contract,
					tal_contract,
					icm_contract,
					pm_contract,
					absna_contract,
					absni_contract,
					cdc_contract,
					berama_contract,
					tub_contract,
					asc_permit,
					tal_permit,
					icm_permit,
					pm_permit,
					absna_permit,
					absni_permit,
					cdc_permit,
					berama_permit,
					tub_permit,
					asc_clearance,
					tal_clearance,
					icm_clearance,
					pm_clearance,
					absna_clearance,
					absni_clearance,
					cdc_clearance,
					berama_clearance,
					tub_clearance,
					asc_intro,
					tal_intro,
					icm_intro,
					pm_intro,
					absna_intro,
					absni_intro,
					cdc_intro,
					berama_intro,
					tub_intro,
					type,
					epas,
					transferOn,
					addedoutlet,
					hr_location
				) VALUES (
					'" . $empId . "',
					'" . mysql_real_escape_string($old_promo_data['promo_company']) . "',
					'" . $old_promo_data['promo_department'] . "',
					'" . $old_promo_data['company_duration'] . "',
					'" . $old_promo_data['al_tag'] . "',
					'" . $old_promo_data['al_tal'] . "',
					'" . $old_promo_data['icm'] . "',
					'" . $old_promo_data['pm'] . "',
					'" . $old_promo_data['abenson_tag'] . "',
					'" . $old_promo_data['abenson_icm'] . "',
					'" . $old_promo_data['al_tub'] . "',
					'" . $old_promo_data['alta_citta'] . "',
					'" . $old_promo_data['fr_panglao'] . "',
					'" . $old_promo_data['fr_panglao_epascode'] . "',
					'" . $old_promo_data['fr_panglao_contract'] . "',
					'" . $old_promo_data['fr_panglao_permit'] . "',
					'" . $old_promo_data['fr_panglao_clearance'] . "',
					'" . $old_promo_data['fr_panglao_intro'] . "',
					'" . $old_promo_data['fr_tubigon'] . "',
					'" . $old_promo_data['fr_tubigon_epascode'] . "',
					'" . $old_promo_data['fr_tubigon_contract'] . "',
					'" . $old_promo_data['fr_tubigon_permit'] . "',
					'" . $old_promo_data['fr_tubigon_clearance'] . "',
					'" . $old_promo_data['fr_tubigon_intro'] . "',
					'" . $old_promo_data['al_panglao'] . "',
					'" . $old_promo_data['al_panglao_epascode'] . "',
					'" . $old_promo_data['al_panglao_contract'] . "',
					'" . $old_promo_data['al_panglao_permit'] . "',
					'" . $old_promo_data['al_panglao_clearance'] . "',
					'" . $old_promo_data['al_panglao_intro'] . "',
					'" . $old_promo_data['alta_epascode'] . "',
					'" . $old_promo_data['alta_contract'] . "',
					'" . $old_promo_data['alta_permit'] . "',
					'" . $old_promo_data['alta_clearance'] . "',
					'" . $old_promo_data['alta_intro'] . "',
					'" . $old_promo_data['promo_type'] . "',
					'" . $new_rno['record_no'] . "',
					'" . $old_promo_data['asc_epascode'] . "',
					'" . $old_promo_data['tal_epascode'] . "',
					'" . $old_promo_data['icm_epascode'] . "',
					'" . $old_promo_data['pm_epascode'] . "',
					'" . $old_promo_data['absna_epascode'] . "',
					'" . $old_promo_data['absni_epascode'] . "',
					'" . $old_promo_data['cdc_epascode'] . "',
					'" . $old_promo_data['berama_epascode'] . "',
					'" . $old_promo_data['tub_epascode'] . "',
					'" . $old_promo_data['asc_contract'] . "',
					'" . $old_promo_data['tal_contract'] . "',
					'" . $old_promo_data['icm_contract'] . "',
					'" . $old_promo_data['pm_contract'] . "',
					'" . $old_promo_data['absna_contract'] . "',
					'" . $old_promo_data['absni_contract'] . "',
					'" . $old_promo_data['cdc_contract'] . "',
					'" . $old_promo_data['berama_contract'] . "',
					'" . $old_promo_data['tub_contract'] . "',
					'" . $old_promo_data['asc_permit'] . "',
					'" . $old_promo_data['tal_permit'] . "',
					'" . $old_promo_data['icm_permit'] . "',
					'" . $old_promo_data['pm_permit'] . "',
					'" . $old_promo_data['absna_permit'] . "',
					'" . $old_promo_data['absni_permit'] . "',
					'" . $old_promo_data['cdc_permit'] . "',
					'" . $old_promo_data['berama_permit'] . "',
					'" . $old_promo_data['tub_permit'] . "',
					'" . $old_promo_data['asc_clearance'] . "',
					'" . $old_promo_data['tal_clearance'] . "',
					'" . $old_promo_data['icm_clearance'] . "',
					'" . $old_promo_data['pm_clearance'] . "',
					'" . $old_promo_data['absna_clearance'] . "',
					'" . $old_promo_data['absni_clearance'] . "',
					'" . $old_promo_data['cdc_clearance'] . "',
					'" . $old_promo_data['berama_clearance'] . "',
					'" . $old_promo_data['tub_clearance'] . "',
					'" . $old_promo_data['asc_intro'] . "',
					'" . $old_promo_data['tal_intro'] . "',
					'" . $old_promo_data['icm_intro'] . "',
					'" . $old_promo_data['pm_intro'] . "',
					'" . $old_promo_data['absna_intro'] . "',
					'" . $old_promo_data['absni_intro'] . "',
					'" . $old_promo_data['cdc_intro'] . "',
					'" . $old_promo_data['berama_intro'] . "',
					'" . $old_promo_data['tub_intro'] . "',
					'" . $old_promo_data['type'] . "',
					'" . $old_promo_data['epas'] . "',
					'" . $old_promo_data['transferOn'] . "',
					'" . $old_promo_data['addedoutlet'] . "',
					'" . $old_promo_data['hr_location'] . "'
				)"
	) or die(mysql_error());

	// insert employee3
	mysql_query(
		"INSERT INTO 
			`employee3`
				(
					`emp_id`, 
					`emp_no`, 
					`emp_pins`, 
					`barcodeId`, 
					`bioMetricId`, 
					`payroll_no`, 
					`name`, 
					`startdate`, 
					`eocdate`, 
					`emp_type`, 
					`current_status`, 
					`duration`, 
					`positionlevel`, 
					`position`,  
					`lodging`, 
					`remarks`, 
					`date_added`, 
					`added_by`
				) VALUES (
					'" . $empId . "',
					'" . $old_data['emp_no'] . "',
					'" . $old_data['emp_pins'] . "',
					'" . $old_data['barcodeId'] . "',
					'" . $old_data['bioMetricId'] . "',
					'" . $old_data['payroll_no'] . "',
					'" . $old_data['name'] . "',
					'" . $effectiveOn . "',
					'" . $old_data['eocdate'] . "',
					'" . $old_data['emp_type'] . "',
					'" . $old_data['current_status'] . "',
					'" . $old_data['duration'] . "',
					'" . $old_data['positionlevel'] . "',
					'" . $old_data['position'] . "',
					'" . $old_data['lodging'] . "',
					'',
					'" . $date . "',
					'" . $loginId . "'
				)
			"
	) or die(mysql_error());

	$recordNo = mysql_insert_id();

	// delete the old record in employee3
	mysql_query("DELETE FROM employee3 WHERE emp_id = '" . $empId . "' 
	AND record_no = '" . $old_data['record_no'] . "'") or die(mysql_error());

	//update for timekeeping table
	$store_tk = explode("||", $_POST['store']);
	$chk_field = array();
	for ($i = 0; $i < sizeof($store_tk) - 1; $i++) {
		$get_tkStore = explode("/", $store_tk[$i]);
		$chk_field[] = end($get_tkStore);
	}
	foreach ($chk_field as $field) {

		if ($field == 'al_tal') {
			$talibon = 'true';
		} else if ($field == 'al_tub') {
			$tubigon = 'true';
		} else if ($field == 'colm' || $field == 'colc') {
			$colon = 'true';
		} else {
			$corporate = 'true';
		}
	}

	$get_oldCutoff = mysql_query("SELECT * FROM timekeeping.promo_sched_emp WHERE recordNo = '" . $old_data['record_no'] . "' AND empId = '" . $empId . "'");
	$cutoffNum =  mysql_num_rows($get_oldCutoff);

	if ($cutoffNum > 0) {

		mysql_query("UPDATE timekeeping.promo_sched_emp SET `recordNo`='" . $new_rno['record_no'] . "'
		   WHERE `recordNo`='" . $old_data['record_no'] . "'");
	} else {

		mysql_query("INSERT INTO timekeeping.promo_sched_emp (statCut, recordNo, empId, date_setup)
		VALUES ('10', '" . $new_rno['record_no'] . "', '" . $empId . "', '" . date('Y-m-d') . "')");
	}

	$get_statCut = mysql_query("SELECT * FROM timekeeping.promo_sched_emp WHERE `recordNo`='" . $new_rno['record_no'] . "'");
	$statCut = mysql_fetch_array($get_statCut);
	$insert_newSC = mysql_query("INSERT INTO timekeeping.promo_sched_emp (statCut, recordNo, empId, date_setup)
	VALUES ('" . $statCut['statCut'] . "', '" . $recordNo . "', '" . $empId . "', '" . $date . "')");

	if ($talibon == 'true') {

		include("config_talibon_timekeeping.php");
		$get_oldCutoff = mysql_query("SELECT * FROM promo_sched_emp WHERE recordNo = '" . $old_data['record_no'] . "' AND empId = '" . $empId . "'");
		$cutoffNum =  mysql_num_rows($get_oldCutoff);

		if ($cutoffNum > 0) {

			mysql_query("UPDATE promo_sched_emp SET `recordNo`='" . $new_rno['record_no'] . "'
				   WHERE `recordNo`='" . $old_data['record_no'] . "'");
		} else {

			mysql_query("INSERT INTO promo_sched_emp (statCut, recordNo, empId, date_setup)
				VALUES ('10', '" . $new_rno['record_no'] . "', '" . $empId . "', '" . date('Y-m-d') . "')");
		}

		$get_statCut = mysql_query("SELECT * FROM promo_sched_emp WHERE `recordNo`='" . $new_rno['record_no'] . "'");
		$statCut = mysql_fetch_array($get_statCut);
		$insert_newSC = mysql_query("INSERT INTO promo_sched_emp (statCut, recordNo, empId, date_setup)
		VALUES ('" . $statCut['statCut'] . "', '" . $recordNo . "', '" . $empId . "', '" . $date . "')");

		mysql_close($con);
	}

	if ($tubigon == 'true') {

		include("config_tubigon_timekeeping.php");
		$get_oldCutoff = mysql_query("SELECT * FROM promo_sched_emp WHERE recordNo = '" . $old_data['record_no'] . "' AND empId = '" . $empId . "'");
		$cutoffNum =  mysql_num_rows($get_oldCutoff);

		if ($cutoffNum > 0) {

			mysql_query("UPDATE promo_sched_emp SET `recordNo`='" . $new_rno['record_no'] . "'
			WHERE `recordNo`='" . $old_data['record_no'] . "'");
		} else {

			mysql_query("INSERT INTO promo_sched_emp (statCut, recordNo, empId, date_setup)
			VALUES ('10', '" . $new_rno['record_no'] . "', '" . $empId . "', '" . date('Y-m-d') . "')");
		}

		$get_statCut = mysql_query("SELECT * FROM promo_sched_emp WHERE `recordNo`='" . $new_rno['record_no'] . "'");
		$statCut = mysql_fetch_array($get_statCut);
		$insert_newSC = mysql_query("INSERT INTO promo_sched_emp (statCut, recordNo, empId, date_setup)
		VALUES ('" . $statCut['statCut'] . "', '" . $recordNo . "', '" . $empId . "', '" . $date . "')");
		mysql_close($con);
	}

	$update = mysql_query("UPDATE `promo_record` SET `record_no`='$recordNo', `promo_type`='ROVING', $updateField, $updateEpas, $updateContract, $updatePermit, $updateClearance, $updateIntro, `transferOn`='$effectiveOn' WHERE `emp_id` = '$empId'") or die(mysql_error());
	$insert = mysql_query("INSERT INTO `change_outlet_record` (`emp_id`, `changefrom`, `changeto`, `effectiveon`) 
	VALUES ('$empId','$previousStore','$currentStore','$effectiveOn')") or die(mysql_error());

	// get added store
	$ctr = 0;
	$addedOutlet = "";
	for ($i = 0; $i < sizeof($bunit) - 1; $i++) {

		$counter++;
		$store = explode("/", $bunit[$i]);

		$storeName = mysql_query("SELECT bunit_name FROM `locate_promo_business_unit` WHERE bunit_id = '" . $store[0] . "'") or die(mysql_error());
		$strN = mysql_fetch_array($storeName);

		$chkStore = mysql_query("SELECT promo_id FROM `promo_record` WHERE emp_id = '$empId' AND $store[1] = 'T'") or die(mysql_error());
		$chkNum = mysql_num_rows($chkStore);

		$chkStore2 = mysql_query("SELECT promo_id FROM `promo_history_record` WHERE emp_id = '$empId' AND $store[1] = 'T'") or die(mysql_error());
		$chkNum2 = mysql_num_rows($chkStore2);

		if ($chkNum > 0 && $chkNum2 == 0) {

			$ctr++;
			if ($ctr == 1) {

				$addedOutlet .= $strN['bunit_name'];
			} else {

				if ($i == sizeof($bunit) - 2) {

					$addedOutlet .= " AND " . $strN['bunit_name'];
				} else {

					$addedOutlet .= ", " . $strN['bunit_name'];
				}
			}
		}
	}

	if (empty($remarks)) {

		$remarks = "Added Outlet - $addedOutlet";
	}
	mysql_query("UPDATE `employee3` SET `remarks`='$remarks' WHERE emp_id ='$empId'") or die(mysql_error());
	die("Ok");
} else if ($_GET['request'] == "supervisorDetails") {

	$supId = $_POST['empId'];

	$query = mysql_query("SELECT company_code, bunit_code, dept_code, section_code, position, positionlevel, emp_type FROM `employee3` WHERE emp_id = '$supId'") or die(mysql_error());
	$fetch = mysql_fetch_array($query); ?>

	<table class="table">
		<tr>
			<td>Company</td>
			<td>:</td>
			<td><?php echo ucwords(strtolower($nq->getCompanyName($fetch['company_code']))); ?></td>
		</tr>
		<tr>
			<td>Business Unit</td>
			<td>:</td>
			<td><?php echo ucwords(strtolower($nq->getBusinessUnitName($fetch['bunit_code'], $fetch['company_code']))); ?></td>
		</tr>
		<tr>
			<td>Department</td>
			<td>:</td>
			<td><?php echo ucwords(strtolower($nq->getDepartmentName($fetch['dept_code'], $fetch['bunit_code'], $fetch['company_code']))); ?></td>
		</tr>
		<tr>
			<td>Section</td>
			<td>:</td>
			<td><?php echo ucwords(strtolower($nq->getSectionName($fetch['section_code'], $fetch['dept_code'], $fetch['bunit_code'], $fetch['company_code']))); ?></td>
		</tr>
		<tr>
			<td>Position</td>
			<td>:</td>
			<td><?php echo ucwords(strtolower($fetch['position'])); ?></td>
		</tr>
		<tr>
			<td>Position Level</td>
			<td>:</td>
			<td><?php echo ucwords(strtolower($fetch['positionlevel'])); ?></td>
		</tr>
		<tr>
			<td>Employee Type</td>
			<td>:</td>
			<td><?php echo ucwords(strtolower($fetch['emp_type'])); ?></td>
		</tr>
	</table>

<?php
} else if ($_GET['request'] == "subordinates") {

	$supId = $_POST['empId'];
	$query = mysql_query("SELECT leveling_subordinates.record_no, leveling_subordinates.subordinates_rater, name, current_status, position FROM `employee3`, `leveling_subordinates` WHERE employee3.emp_id = leveling_subordinates.subordinates_rater 
									AND ratee = '$supId' AND $promoEmpType") or die(mysql_error());

?>

	<div class="panel panel-default">
		<div class="panel-heading">S U B O R D I N A T E S
			<span style="float:right"><a href="javascript:void(0)" class="btn btn-primary btn-sm" onclick="addSubordinates()"><span class="fa fa-street-view"></span> Add Subordinates </a> | <a href="javascript:void(0)" class="btn btn-warning btn-sm" onclick="remove_sub()"><span class="fa fa-remove"></span> Remove</a></span>
		</div>
		<div class="panel-body">
			<table class="table table-striped table-hover table1">
				<thead>
					<tr>
						<th></th>
						<th>Emp.ID</th>
						<th>Name</th>
						<th>Position</th>
						<th>Status</th>
					</tr>
				</thead>
				<tbody>
					<?php

					while ($row = mysql_fetch_array($query)) {
						$empId = $row['subordinates_rater'];
						$recordNo = $row['record_no'];

						if ($row['current_status'] == "Active") {

							$class = "btn btn-success btn-xs btn-flat";
						} else {

							$class = "btn btn-warning btn-xs btn-flat";
						}

						echo "
                    					<tr>
                    						<span style='display:none;'><input type='checkbox' name='empId[]' class='chk_" . $recordNo . "' value='" . $recordNo . "'></span>
                    						<td><input type='checkbox' class='chkC_" . $recordNo . "' onclick=chkSub(\"$recordNo\")></td>
                    						<td><a href='?p=profile&&module=Promo&&com=" . $empId . "'>" . $empId . "</a></td>
                    						<td>" . ucwords(strtolower($row['name'])) . "</td>
                    						<td>" . ucfirst(strtolower($row['position'])) . "</td>
                    						<td><span class='$class btn-block'>" . ucfirst(strtolower($row['current_status'])) . "</span></td>
                    					</tr>
                    					";
					}
					?>
				</tbody>
			</table>
		</div>
	</div>
	<script type="text/javascript">
		$(function() {
			$(".table1").DataTable({

				"order": [
					[2, 'asc']
				]
			});
		});
	</script>
<?php
} else if ($_GET['request'] == "addSubordinates") {
?>
	<div class="row">
		<div class="col-md-4">
			<div class="form-group">
				<label>Employee Type</label>
				<select name="contract_type" class="form-control" onchange="contract_type()">
					<option value="">All</option>
					<option value="Promo">Promo</option>
					<option value="Promo-NESCO">Promo-NESCO</option>
				</select>
			</div>
			<div class="form-group">
				<label>Business Unit</label>
				<select name="store" class="form-control" onchange="bunit(this.value)">
					<option value=""> --Select-- </option>
					<?php

					$locateStore = mysql_query("SELECT bunit_id, bunit_name, bunit_field FROM `locate_promo_business_unit`") or die(mysql_error());
					while ($row = mysql_fetch_array($locateStore)) {

						echo "<option value='" . $row['bunit_id'] . "/" . $row['bunit_field'] . "'>" . $row['bunit_name'] . "</option>";
					}
					?>
				</select>
			</div>
			<div class="form-group">
				<label>Department</label>
				<select name="department" class="form-control" onchange="dept()">
					<option value=""> --Select-- </option>
				</select>
			</div>
		</div>
		<div class="col-md-8">

			<div class="subordinates-list size-emp">

				<div style="width: 5%; margin:0 auto;">
					<br><br><br>
					<img src="../images/system/10.gif" id='loading-gif' style="display: none;">
				</div>
			</div>
		</div>
	</div>
<?php
} else if ($_GET['request'] == "locateDepartment") {

	$id = explode("/", $_POST['id']);

	echo "<option value=''> --Select-- </option>";
	$query = mysql_query("SELECT dept_name FROM `locate_promo_department` WHERE bunit_id = '$id[0]'") or die(mysql_error());
	while ($row = mysql_fetch_array($query)) {

		echo "<option value='" . $row['dept_name'] . "'>" . $row['dept_name'] . "</option>";
	}
} else if ($_GET['request'] == "subordinatesList") {

	$store = $_POST['store'];
	$department = $_POST['department'];
	$emp_type = $_POST['emp_type'];
	$supId = $_POST['supId'];

	if ($emp_type == 'Promo') {

		$where = "emp_type = 'Promo'";
	} else if ($emp_type == 'Promo-NESCO') {

		$where = "emp_type = 'Promo-NESCO'";
	} else {

		$where = "emp_type LIKE 'Promo%'";
	}

	$query = mysql_query("SELECT employee3.emp_id, name, position, current_status FROM `employee3`, `promo_record` WHERE employee3.emp_id = promo_record.emp_id 
									AND $where AND $store = 'T' AND promo_department = '$department' AND current_status != 'blacklisted' AND employee3.emp_id != '$supId' AND promo_record.hr_location = '$hrCode'") or die(mysql_error());
?>
	<table class="table table-striped table-hover table3">
		<thead>
			<tr>
				<th></th>
				<th>Emp.ID</th>
				<th>Name</th>
				<th>Position</th>
				<th>Status</th>
			</tr>
		</thead>
		<tbody class="size-emp">
			<?php

			while ($row = mysql_fetch_array($query)) {

				$empId = $row['emp_id'];
				if ($row['current_status'] == "Active") {

					$class = "btn btn-success btn-xs";
				} else {

					$class = "btn btn-warning btn-xs";
				}

				$valEmpId = mysql_query("SELECT record_no FROM `leveling_subordinates` WHERE ratee = '$supId' AND subordinates_rater = '$empId'") or die(mysql_error());
				$valNum = mysql_num_rows($valEmpId);

				if ($valNum == 0) {
					echo "<tr>
									<span style='display:none;'><input type='checkbox' name='chkEmpId[]' class='chkId_$empId' value='$empId'></span>
									<td><input type='checkbox' class='chkIdC_$empId' onclick='chkIdC(\"$empId\")'></td>
									<td><a href='?p=profile&&module=Promo&&com='$empId'>$empId</td>
									<td>" . ucwords(strtolower($row['name'])) . "</td>
									<td>" . ucfirst(strtolower($row['position'])) . "</td>
									<td><span class='$class btn-block'>" . $row['current_status'] . "</span></td>
								</tr>";
				}
			}
			?>
		</tbody>
		<tfoot>
			<tr>
				<th></th>
				<th>Emp.ID</th>
				<th>Name</th>
				<th>Position</th>
				<th>Status</th>
			</tr>
		</tfoot>
	</table>
	<script type="text/javascript">
		$(".table3").DataTable({

			"order": [
				[2, 'asc']
			],
			"paging": false
		});
	</script>
<?php
} else if ($_GET['request'] == "saveSubordinates") {

	$supId = $_POST['supId'];
	$chk = explode("*", $_POST['newCHK']);

	for ($i = 0; $i < sizeof($chk) - 1; $i++) {

		$query = mysql_query("INSERT INTO `leveling_subordinates`(`ratee`, `subordinates_rater`) VALUES ('$supId','$chk[$i]')") or die(mysql_error());
	}

	die("Ok");
} else if ($_GET['request'] == "remove_sub") {

	$chk = explode("*", $_POST['newCHK']);

	for ($i = 0; $i < sizeof($chk) - 1; $i++) {

		$query = mysql_query("DELETE FROM `leveling_subordinates` WHERE `record_no` = '$chk[$i]'") or die(mysql_error());
	}

	die("Ok");
} else if ($_GET['request'] == "setupCompany") {

	$company = mysql_real_escape_string(strtoupper($_POST['company']));

	$check = mysql_query("SELECT pc_code FROM `locate_promo_company` WHERE pc_name = '$company'") or die(mysql_error());
	$chkNum = mysql_num_rows($check);

	if ($chkNum > 0) {
		die("Exist");
	}

	$query = mysql_query("INSERT INTO `locate_promo_company`(`pc_name`) VALUES ('$company')") or die(mysql_error());

	die("Ok");
} else if ($_GET['request'] == "submitPromoAccount") {

	$empId 	  = $_POST['empId'];
	$usertype = $_POST['usertype'];
	$username = $_POST['username'];
	$password = md5($_POST['password']);
	$dateAdded = date("Y-m-d H:i:s");

	$check = mysql_query("SELECT user_no FROM `users` WHERE username = '$username'") or die(mysql_error());
	$chkNum = mysql_num_rows($check);

	if ($chkNum > 0) {
		die("Exist");
	}

	$query = mysql_query("INSERT INTO `users`
										(`emp_id`, `username`, `password`, `usertype`, `user_status`, `login`, `date_created`, `user_id`) 
									VALUES 
										('$empId','$username','$password','employee','inactive','no','$dateAdded','4')") or die(mysql_error());

	die("Ok");
} else if ($_GET['request'] == "submitPromoIncharge") {

	$empId 	  = $_POST['empId'];
	$usertype = $_POST['usertype'];
	$dateAdded = date("Y-m-d H:i:s");

	$check = mysql_query("SELECT `user_no` FROM `promo_user` WHERE `emp_id` = '$empId'") or die(mysql_error());
	$chkNum = mysql_num_rows($check);

	if ($chkNum > 0) {
		die("Exist");
	}

	$query = mysql_query("INSERT INTO `promo_user`
										(`emp_id`, `usertype`, `user_status`, `date_created`) 
									VALUES 
										('$empId','$usertype','inactive','$dateAdded')") or die(mysql_error());

	die("Ok");
} else if ($_GET['request'] == "updatePIUsertype") {

	$empId = $_POST['empId'];
	$type = $_POST['type'];
	$dateUpdated = date("Y-m-d H:i:s");

	$query = mysql_query("UPDATE `promo_user` SET `usertype`='$type', `date_updated`='$dateUpdated' WHERE `emp_id` = '$empId'") or die(mysql_error());

	die("Ok");
} else if ($_GET['request'] == "updatePIStatus") {

	$empId = $_POST['empId'];
	$status = $_POST['status'];
	$dateUpdated = date("Y-m-d H:i:s");

	$query = mysql_query("UPDATE `promo_user` SET `user_status`='$status' WHERE `emp_id` = '$empId'") or die(mysql_error());

	die("Ok");
} else if ($_GET['request'] == "promoAccess") {

	$name = $_POST['name'];
	$access = $_POST['access'];
	$menuId = $_POST['menuId'];

	$query = mysql_query("UPDATE `promo_menu` SET `$name`='$access' WHERE `menuId` = '$menuId'") or die(mysql_error());

	die("Ok");
} else if ($_GET['request'] == "resetPass") {

	$userNo = $_POST['userNo'];
	$password = md5("Hrms2014");
	$query = mysql_query("UPDATE `users` SET `password`='$password' WHERE `user_no` = '$userNo'") or die(mysql_error());

	die("Password Successfully Resetted");
} else if ($_GET['request'] == "activateAccount") {

	$userNo = $_POST['userNo'];
	$query = mysql_query("UPDATE `users` SET `user_status`='active' WHERE `user_no` = '$userNo'") or die(mysql_error());

	die("Successfully Activated the User Account");
} else if ($_GET['request'] == "deactivateAccount") {

	$userNo = $_POST['userNo'];
	$query = mysql_query("UPDATE `users` SET `user_status`='inactive' WHERE `user_no` = '$userNo'") or die(mysql_error());

	die("Successfully Deactivated the User Account");
} else if ($_GET['request'] == "deleteAccount") {

	$userNo = $_POST['userNo'];
	$query = mysql_query("DELETE FROM `users` WHERE `user_no` = '$userNo'") or die(mysql_error());

	die("User Account Successfully Deleted");
} else if ($_GET['request'] == "filterPromo") {

?>

	<div class="form-group">
		<label>Store</label>
		<select name="store" class="form-control" onchange="locateDept(this.value)">
			<option value=""> --Select-- </option>
			<?php

			$query = mysql_query("SELECT bunit_id, bunit_name, bunit_field FROM `locate_promo_business_unit`") or die(mysql_error());
			while ($row = mysql_fetch_array($query)) {

				echo "<option value='" . $row['bunit_id'] . "/" . $row['bunit_field'] . "'>" . $row['bunit_name'] . "</option>";
			}
			?>
		</select>
	</div>
	<div class="form-group">
		<label>Department</label>
		<select name="department" class="form-control">
			<option value=""> --Select-- </option>
		</select>
	</div>
	<div class="form-group">
		<label>Promo Type</label>
		<select name="promoType" class="form-control">
			<option value=""> --Select-- </option>
			<option value="STATION">STATION</option>
			<option value="ROVING">ROVING</option>
		</select>
	</div>
	<div class="form-group">
		<label>Company</label>
		<select class="form-control select2" name="company" style="width: 100%;">
			<option value=""> --Select-- </option>
			<?php

			$query = mysql_query("SELECT pc_code, pc_name FROM `locate_promo_company`") or die(mysql_error());
			while ($row = mysql_fetch_array($query)) {

				echo "<option value='" . $row['pc_code'] . "'>" . $row['pc_name'] . "</option>";
			}
			?>
		</select>
	</div>

	<script type="text/javascript">
		$(function() {
			//Initialize Select2 Elements
			$(".select2").select2();
		});
	</script>
	<?php
} else if ($_GET['request'] == "checkPromoStatus") {

	$empId = $_POST['empId'];

	$query = mysql_query("SELECT current_status FROM `employee3` WHERE emp_id = '$empId'") or die(mysql_error());
	$fetch = mysql_fetch_array($query);

	die($fetch['current_status']);
} else if ($_GET['request'] == "chkReqsRT") {

	$empId = $_POST['empId'];
	$status = $_POST['status'];

	$loop = 0;
	$store = mysql_query("SELECT * FROM `locate_promo_business_unit` WHERE status = 'active'") or die(mysql_error());
	while ($r = mysql_fetch_array($store)) {

		$bunit = mysql_query("SELECT promo_id FROM `promo_record` WHERE $r[bunit_field] = 'T' AND emp_id = '$empId'") or die(mysql_error());
		$bunitNum = mysql_num_rows($bunit);

		if ($bunitNum > 0) {

			$loop++; ?>

			<div class="form-group">
				<label>Clearance (<?php echo ucwords(strtolower($r['bunit_name'])); ?>)</label> <i class="text-red">*</i>
				<input type="file" name="<?php echo $r['bunit_clearance']; ?>" id="clearance_<?php echo $loop; ?>" class="btn btn-default btn-flat" onchange="inputClearance(this.name)">
			</div><?php
				}
			}

			echo "<input type='hidden' name='loop' value='$loop'>";

			if ($status == "Resigned") { ?>

		<div class="form-group">
			<label>Resignation Letter</label> <i class="text-red">*</i>
			<input type="file" name="resignation" class="btn btn-default btn-flat" onchange="inputField(this.name)">
		</div>
	<?php
			} ?>
<?php
} else if ($_GET['request'] == "submitPromoRT") {

	$employee = explode("*", $_POST['employee']);
	$empId = trim($employee[0]);

	$dateEffective = date("Y-m-d", strtotime($_POST['dateEffective']));
	$remarks = $_POST['remarks'];
	$status = $_POST['status'];

	$clearanceNames = explode("||", $_POST['clearanceName']);

	// upload intro(s)
	$clearanceFieldValue = "";
	$loop = 0;
	for ($i = 0; $i < sizeof($clearanceNames) - 1; $i++) {

		if (!empty($_FILES[$clearanceNames[$i]]['name'])) {
			$image		= addslashes(file_get_contents($_FILES[$clearanceNames[$i]]['tmp_name']));
			$image_name	= addslashes($_FILES[$clearanceNames[$i]]['name']);
			$array 	= explode(".", $image_name);

			$filename 	= $empId . "=" . date('Y-m-d') . "=" . $clearanceNames[$i] . "=" . date('H-i-s-A') . "." . end($array);
			$destination_path	= "../document/clearance/" . $filename;

			if (@move_uploaded_file($_FILES[$clearanceNames[$i]]['tmp_name'], $destination_path)) {

				$loop++;

				if ($loop == 1) {

					$clearanceFieldValue .= "`$clearanceNames[$i]` = '$destination_path'";
				} else {

					$clearanceFieldValue .= " ,`$clearanceNames[$i]` = '$destination_path'";
				}
			}
		}
	}

	$uploadClearance = mysql_query("UPDATE `promo_record` SET $clearanceFieldValue WHERE `emp_id` = '$empId'") or die(mysql_error());

	$destination_pathRT = "";

	if ($status == "Resigned") {

		if (!empty($_FILES['resignation']['name'])) {
			$image		= addslashes(file_get_contents($_FILES['resignation']['tmp_name']));
			$image_name	= addslashes($_FILES['resignation']['name']);
			$array 	= explode(".", $image_name);

			$filename 	= $empId . "=" . date('Y-m-d') . "=" . 'Resignation-Letter' . "=" . date('H-i-s-A') . "." . end($array);
			$destination_pathRT	= "../document/resignation/" . $filename;

			move_uploaded_file($_FILES['resignation']['tmp_name'], $destination_pathRT);
		}
	}

	mysql_query("INSERT INTO `termination`
										(`emp_id`, `date`, `remarks`, `resignation_letter`, `added_by`, `date_updated`) 
								VALUES 
										('$empId','$dateEffective','$remarks','$destination_pathRT','$loginId','$date')") or die(mysql_error());

	mysql_query("UPDATE employee3 SET current_status = '$status', remarks = '$remarks' WHERE emp_id = '$empId'") or die(mysql_error());
	$user = mysql_query("UPDATE users SET user_status = 'inactive' WHERE emp_id = '$empId'") or die(mysql_error());

	die("Ok");
} else if ($_GET['request'] == "resignationLetter") {

	$terminationNo = $_POST['terminationNo'];
	$empId = $_POST['empId'];

	$query = mysql_query("SELECT resignation_letter FROM `termination` WHERE emp_id = '$empId' AND termination_no = '$terminationNo'") or die(mysql_error());
	$fetch = mysql_fetch_array($query);

	die($fetch['resignation_letter']);
} else if ($_GET['request'] == "uploadResignationLetter") {

	$empId = $_POST['empId'];
	$terminationNo = $_POST['terminationNo'];

?>
	<input type="hidden" name="empId" value="<?php echo $empId; ?>">
	<input type="hidden" name="terminationNo" value="<?php echo $terminationNo; ?>">
	<div class="form-group">
		<label>Choose Image</label>
		<input type="file" name="resignationLetter" class="form-control">
	</div>
<?php
} else if ($_GET['request'] == "submitResignationLetter") {

	$empId = $_POST['empId'];
	$terminationNo = $_POST['terminationNo'];

	$destination_pathRT = "";

	if (!empty($_FILES['resignationLetter']['name'])) {
		$image		= addslashes(file_get_contents($_FILES['resignationLetter']['tmp_name']));
		$image_name	= addslashes($_FILES['resignationLetter']['name']);
		$array 	= explode(".", $image_name);

		$filename 	= $empId . "=" . date('Y-m-d') . "=" . 'Resignation-Letter' . "=" . date('H-i-s-A') . "." . end($array);
		$destination_pathRT	= "../document/resignation/" . $filename;

		if (move_uploaded_file($_FILES['resignationLetter']['tmp_name'], $destination_pathRT)) {

			mysql_query("UPDATE termination SET resignation_letter = '" . $destination_pathRT . "' WHERE termination_no = '" . $terminationNo . "' AND emp_id = '" . $empId . "'") or die(mysql_error());
			die("success");
		}
	}
} else if ($_GET['request'] == "moresignedList") {

	$supId = $_POST['empId'];
	$query = mysql_query("SELECT leveling_subordinates.record_no, leveling_subordinates.subordinates_rater, name, current_status, emp_type, position FROM `employee3`, `leveling_subordinates` WHERE employee3.emp_id = leveling_subordinates.subordinates_rater 
									AND ratee = '$supId' AND current_status in ('Active','End of Contract','V-Resigned') AND $promoEmpType ") or die(mysql_error());

	function checkStat($empId, $stat, $supId)
	{

		$query = mysql_query("SELECT `tag_stat` FROM `tag_for_resignation` WHERE `ratee_id` = '" . $empId . "' AND `tag_stat` = '" . $stat . "' AND `rater_id` = '" . $supId . "'") or die(mysql_error());
		$row = mysql_fetch_array($query);

		return $row['tag_stat'];
	}
?>

	<div class="panel panel-default">
		<div class="panel-heading">S U B O R D I N A T E S</div>
		<div class="panel-body">
			<table class="table table-striped table-hover table1">
				<thead>
					<tr>
						<th>Emp.ID</th>
						<th>Name</th>
						<th>EmpType</th>
						<th>Position</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?php

					while ($row = mysql_fetch_array($query)) {
						$empId = $row['subordinates_rater'];

						$condition = "";
						$ctr = 0;
						$locateEpas = mysql_query("SELECT bunit_field, bunit_epascode FROM `locate_promo_business_unit`") or die(mysql_error());
						while ($fetch = mysql_fetch_array($locateEpas)) {

							$epasField = $fetch['bunit_field'];
							$chk = mysql_query("SELECT promo_id FROM `promo_record` WHERE emp_id = '$empId' AND $epasField = 'T'") or die(mysql_error());
							$chkNum = mysql_num_rows($chk);

							if ($chkNum > 0) {

								$ctr++;
								if ($ctr == 1) {
									$condition = "AND (" . $fetch['bunit_epascode'] . " = ''";
								} else {

									$condition .= " OR " . $fetch['bunit_epascode'] . " = ''";
								}
							}
						}

						if ($ctr > 0) {

							$condition .= ")";
						}

						$promoR = mysql_query("SELECT promo_id FROM `promo_record` WHERE emp_id = '$empId' $condition") or die(mysql_error());
						$promoNum = mysql_fetch_array($promoR);

						if ($promoNum > 0) { ?>

							<tr class="<?php if (checkStat($empId, 'Pending', $supId)) : echo "info";
										elseif (checkStat($empId, 'Done', $supId)) : echo "success";
										endif; ?>"><?php

													echo "
                    						<td><a href='?p=profile&&module=Promo&&com=" . $empId . "'>" . $empId . "</a></td>
                    						<td>" . ucwords(strtolower($row['name'])) . "</td>
                    						<td>" . $row['emp_type'] . "</td>
                    						<td>" . ucfirst(strtolower($row['position'])) . "</td>"; ?>
								<td>
									<?php if (checkStat($empId, 'Pending', $supId)) : ?>
										<a class="text-danger" data-toggle="tooltip" data-placement="top" title="Untag for Resignation" href="javascript:void(0)" onclick="statForResign('<?php echo $empId; ?>','untag','<?php echo $supId; ?>')">
											<i class="fa fa-remove"></i>
										</a>
									<?php elseif (checkStat($empId, 'Done', $supId)) : ?>
										<i data-toggle="tooltip" data-placement="top" title="EPAS done" class="fa  fa-thumbs-o-up"></i>
									<?php else : ?>
										<a data-toggle="tooltip" data-placement="top" title="Click to Tag for Resignation" href="javascript:void(0)" onclick="statForResign('<?php echo $empId; ?>','tag','<?php echo $supId; ?>')">
											<i class="fa fa-tag"></i>
										</a>
									<?php endif; ?>
								</td>
							</tr>
					<?php

						}
					}
					?>
				</tbody>
			</table>
		</div>
	</div>
	<script type="text/javascript">
		$(function() {
			$(".table1").DataTable({

				"order": [
					[1, 'asc']
				]
			});
		});
	</script>
<?php
} else if ($_GET['request'] == "statForResign") {

	$empId = $_POST['empId'];
	$stat = $_POST['stat'];
	$supId = $_POST['supId'];

	if ($stat == "tag") {

		mysql_query("INSERT INTO `tag_for_resignation`
								(`ratee_id`, `rater_id`, `added_by`, `date_added`, `tag_stat`) 
							VALUES 
								('$empId','$supId','$loginId','$date','Pending')") or die(mysql_error());
	} else {

		mysql_query("DELETE FROM `tag_for_resignation` WHERE `ratee_id` = '$empId' AND `rater_id` = '$supId'") or die(mysql_error());
	}

	die("Ok");
} else if ($_GET['request'] == "promoDetails") {

	$empId = $_POST['empId'];

	$query = mysql_query("SELECT startdate, eocdate, position, promo_company, promo_department, promo_type, type FROM `promo_record`, `employee3` WHERE promo_record.emp_id = employee3.emp_id AND employee3.emp_id = '$empId' AND promo_record.hr_location = '$hrCode'") or die(mysql_error());
	$fetch = mysql_fetch_array($query);

	$store = "";
	$prevStore = "";
	$ctr = 0;
	$bunit = mysql_query("SELECT bunit_field, bunit_acronym, bunit_name FROM `locate_promo_business_unit`") or die(mysql_error());
	while ($str = mysql_fetch_array($bunit)) {

		$promo = mysql_query("SELECT promo_id FROM `promo_record` WHERE `" . $str['bunit_field'] . "` = 'T' AND emp_id = '$empId'") or die(mysql_error());
		if (mysql_num_rows($promo) > 0) {
			$ctr++;

			if ($ctr == 1) {

				$store = $str['bunit_acronym'];
				$prevStore = $str['bunit_name'];
			} else {

				$store .= ", " . $str['bunit_acronym'];
				$prevStore .= ", " . $str['bunit_name'];
			}
		}
	}


?>
	<input type="hidden" name="prevStore" value="<?php echo $prevStore; ?>">
	<input type="hidden" name="storeNum" value="<?php echo $ctr; ?>">
	<table class="table">
		<tr>
			<td width="25%">Company</td>
			<td>:</td>
			<td><?php echo $fetch['promo_company']; ?></td>
		</tr>
		<tr>
			<td>Business Unit</td>
			<td>:</td>
			<td><?php echo $store; ?></td>
		</tr>
		<tr>
			<td>Department</td>
			<td>:</td>
			<td><?php echo $fetch['promo_department']; ?></td>
		</tr>
		<tr>
			<td>Promo Type</td>
			<td>:</td>
			<td><?php echo $fetch['promo_type']; ?></td>
		</tr>
		<tr>
			<td>Contract Type</td>
			<td>:</td>
			<td><?php echo $fetch['type']; ?></td>
		</tr>
		<tr>
			<td>Position</td>
			<td>:</td>
			<td><?php echo $fetch['position']; ?></td>
		</tr>
		<tr>
			<td>Startdate</td>
			<td>:</td>
			<td><?php echo date("m/d/Y", strtotime($fetch['startdate'])); ?></td>
		</tr>
		<tr>
			<td>EOCdate</td>
			<td>:</td>
			<td><?php echo date("m/d/Y", strtotime($fetch['eocdate'])); ?></td>
		</tr>
	</table>

<?php
} else if ($_GET['request'] == "addOutletForm") {

	$empId = $_POST['empId']; ?>

	<div class="panel panel-default">
		<div class="panel-heading">
			<strong>CURRENT OUTLET</strong>
		</div>
		<div class="panel-body">
			<div class="row">
				<div class="col-md-12">
					<table class="table table-bordered">
						<tr>
							<td colspan="2"><b>SELECT STORE :</b></td>
						</tr>
						<?php

						$counter = 0;
						$store = mysql_query("SELECT * FROM `locate_promo_business_unit` WHERE status = 'active' AND hrd_location = '$hrCode'") or die(mysql_error());
						while ($r = mysql_fetch_array($store)) {

							$counter++;

							$bunit = mysql_query("SELECT promo_id FROM `promo_record` WHERE $r[bunit_field] = 'T' AND emp_id = '$empId'") or die(mysql_error());
							$bunitNum = mysql_num_rows($bunit);
						?>
							<tr>
								<td><input type="checkbox" id="check2_<?php echo $counter; ?>" name="<?php echo $r['bunit_field']; ?>" value="<?php echo $r['bunit_id'] . '/' . $r['bunit_field']; ?>" <?php if ($bunitNum > 0) {
																																																			echo "checked='' disabled=''";
																																																		} ?> /></td>
								<td><?php echo $r['bunit_name']; ?></td>
							</tr><?php
								}
									?>
						<input type="hidden" name="counter" value="<?php echo $counter; ?>">
					</table>
				</div>
			</div>
			<div class="form-group"> <span class="text-red">*</span>
				<label>Effective On</label>
				<div class="input-group date">
					<div class="input-group-addon">
						<i class="fa fa-calendar"></i>
					</div>
					<input type="text" name="effectiveOn" class="form-control pull-right datepicker" value="<?php echo date('m/d/Y'); ?>" onchange="inputField(this.name)">
				</div>
			</div>
			<div class="form-group">
				<label>Remarks</label> <i class="">(optional)</i>
				<textarea name="remarks" class="form-control" rows="4"></textarea>
			</div>

		</div>
		<div class="panel-footer">
			<button class="btn btn-primary" onclick="submitFields()"><i class="fa fa-bank"></i>&nbsp; Add Outlet</button>
		</div>
	</div>

	<script type="text/javascript">
		//Date picker
		$('.datepicker').datepicker({
			inline: true,
			changeYear: true,
			changeMonth: true
		});
	</script>
<?php
} else if ($_GET['request'] == "getApplicantStat") {

	$empId = $_POST['empId'];

	$query = mysql_query("SELECT applicants.status FROM `applicant`, `applicants` WHERE applicant.appcode = applicants.app_code AND applicant.app_id = '$empId'") or die(mysql_error());
	$row = mysql_fetch_array($query);

	$status = $row['status'];

	if (empty($status)) {

		$query = mysql_query("SELECT current_status FROM `employee3` WHERE emp_id = '$empId'") or die(mysql_error());
		$row = mysql_fetch_array($query);

		$status = $row['current_status'];
	}

	die("Ok||$status");
} else if ($_GET['request'] == "getCurrentStat") {

	$empId = $_POST['empId'];

	$query = mysql_query("SELECT current_status FROM `employee3` WHERE emp_id = '$empId'") or die(mysql_error());
	$row = mysql_fetch_array($query);

	die("Ok||" . $row['current_status']);
} else if ($_GET['request'] == "tagToRecruitment") {

	$empId = $_POST['empId'];
	$recProcess = $_POST['recProcess'];
	$position 	= mysql_real_escape_string($_POST['position']);

	$chk = mysql_query("SELECT applicants.app_code FROM `applicant`, `applicants` WHERE applicant.appCode = applicants.app_code AND applicant.app_id = '$empId'") or die(mysql_error());
	$chkNum = mysql_num_rows($chk);
	$row = mysql_fetch_array($chk);

	$app_details = "";
	$chk_appDetails = mysql_query("SELECT no FROM application_details WHERE app_id = '$empId'") or die(mysql_error());

	if ($recProcess == "initial_completion") {

		$delAppRec = mysql_query("DELETE FROM `applicants` WHERE `app_code` = '" . $row['app_code'] . "'") or die(mysql_error());
		$updAppRec = mysql_query("UPDATE `applicant` SET `appcode`= 0 WHERE `app_id` = '$empId'") or die(mysql_error());
		$app_details = "Initial Completion";
	}

	if ($chkNum > 0) {

		if ($recProcess == "exam") {

			$set = "`status`='initialreq completed', `position` = '$position'";
			$app_details = "Examination";
		} else if ($recProcess == "interview") {

			$set = "`status`='for interview', `position` = '$position'";
			$app_details = "Interview";
		} else if ($recProcess == "training") {

			$set = "`status`='for training', `position` = '$position'";
			$app_details = "Training";
		} else if ($recProcess == "final_completion") {

			$set = "`status`='for final completion', `position` = '$position'";
			$app_details = "Final Completion";
		} else if ($recProcess == "orientation") {

			$set = "`status`='for orientation', `position` = '$position'";
			$app_details = "Orientation";
		} else {

			$set = "`status`='for hiring', `position` = '$position'";
			$app_details = "Hiring";
		}

		$updApplicants = mysql_query("UPDATE `applicants` SET $set WHERE `app_code` = '" . $row['app_code'] . "'") or die(mysql_error());
	} else {

		if ($recProcess == "exam") {

			$value = "initialreq completed";
			$app_details = "Examination";
		} else if ($recProcess == "interview") {

			$value = "for interview";
			$app_details = "Interview";
		} else if ($recProcess == "training") {

			$value = "for training";
			$app_details = "Training";
		} else if ($recProcess == "final_completion") {

			$value = "for final completion";
			$app_details = "Final Completion";
		} else if ($recProcess == "orientation") {

			$value = "for orientation";
			$app_details = "Orientation";
		} else {

			$value = "for hiring";
			$app_details = "Hiring";
		}

		$appInfo = mysql_query("SELECT lastname, firstname, middlename, suffix FROM `applicant` WHERE app_id = '$empId'") or die(mysql_error());
		$info = mysql_fetch_array($appInfo);

		$insert = mysql_query("INSERT INTO `applicants`
											(`lastname`, `firstname`, `middlename`, `position`, `status`, `date_time`, `suffix`, `entry_by`,`hr_location`) 
										VALUES 
											('" . $info['lastname'] . "','" . $info['firstname'] . "','" . $info['middlename'] . "','$position','$value','$date','" . $info['suffix'] . "','$loginId','$locateHR')") or die(mysql_error());

		$id = mysql_insert_id();
		mysql_query("UPDATE `applicant` SET `appcode`='$id' WHERE `app_id` = '$empId'") or die(mysql_error());
	}

	// check application_details table
	if (mysql_num_rows($chk_appDetails) > 0) {

		$upd_appDetails = mysql_query("UPDATE application_details SET application_status = '$app_details', position_applied = '$position', updatedby = '" . $_SESSION['emp_id'] . "', date_updated = '$date' WHERE app_id = '$empId'") or die(mysql_error());
	} else {

		$ins_appDetails = mysql_query("INSERT INTO `application_details`
															(`app_id`, `position_applied`, `date_applied`, `application_status`, `updatedby`, `date_updated`) 
													VALUES 
															('$empId','$position','$date','$app_details','" . $_SESSION['emp_id'] . "','$date')") or die(mysql_error());
	}

	die("Ok");
} else if ($_GET['request'] == "getPromoType") {

	$empId = $_POST['empId'];

	$query = mysql_query("SELECT promo_type FROM promo_record WHERE emp_id = '$empId'") or die(mysql_error());
	$row = mysql_fetch_array($query);

	die("Ok||" . $row['promo_type']);
} else if ($_GET['request'] == "transferForm") {

	$empId = $_POST['empId'];
	$recordNo = $nq->getRec($empId);

	function getAcronym($storeName)
	{

		$query = mysql_query("SELECT bunit_acronym, bunit_epascode FROM `locate_promo_business_unit` WHERE bunit_name ='$storeName'") or die(mysql_error());
		return $query;
	}

	$promoType = $nq->getPromoType($empId);
	$query = mysql_query("SELECT record_no, numrate, rater, store FROM appraisal_details WHERE emp_id = '$empId' and record_no != '$recordNo' ORDER BY details_id DESC") or die(mysql_error());
?>

	<input type="hidden" name="empId" value="<?php echo $empId; ?>">
	<input type="hidden" name="recordNo" value="<?php echo $recordNo; ?>">
	<input type="hidden" name="promoType" value="<?php echo $promoType; ?>">
	<div class="panel panel-default">
		<div class="panel-heading">
			<strong>Previous Rate(s)</strong>
		</div>
		<div class="panel-body">
			<div class="size-emp">
				<table class="table table-hover">
					<thead>
						<tr>
							<th></th>
							<th>Store</th>
							<th>Rate</th>
							<th>Rater</th>
							<th>Startdate</th>
							<th>Eocdate</th>
						</tr>
					</thead>
					<tbody>
						<?php

						$loop = 0;
						while ($row = mysql_fetch_array($query)) {

							$loop++;
							$prev_recordNo = $row['record_no'];
							$bunit = $row['store'];

							$prevInfo = mysql_query("SELECT startdate, eocdate FROM employmentrecord_ WHERE record_no = '" . $prev_recordNo . "' and emp_id = '" . $empId . "'") or die(mysql_error());
							$info = mysql_fetch_array($prevInfo);

							$str = mysql_fetch_array(getAcronym($bunit));

							echo "<tr>";

							if ($promoType == "STATION") {

								echo "<td><input type='radio' name='transferStation' id='chkS_$loop' value='$bunit*$prev_recordNo'></td>";
							} else {
								echo "<td><input type='checkbox' name='transferRoving[]' class='chk_$prev_recordNo' value='$bunit*$prev_recordNo' id='chk_$loop' onclick='chk(\"$loop\",\"$prev_recordNo\")'></td>";
							}

							echo "	<td>" . $str['bunit_acronym'] . "</td>
                								<td>" . $row['numrate'] . "</td>
                								<td>" . ucwords(strtolower($nq->getPromoName($row['rater']))) . "</td>
                								<td>" . date('m/d/Y', strtotime($info['startdate'])) . "</td>
                								<td>" . date('m/d/Y', strtotime($info['eocdate'])) . "</td>
                							</tr>";
						}
						?>
						<input type="hidden" name="loop" value="<?php echo $loop; ?>">
					</tbody>
				</table>
			</div>
		</div>
		<div class="panel-footer">
			<?php

			$store = "";
			$button = 0;
			$bunit = mysql_query("SELECT bunit_field, bunit_epascode FROM `locate_promo_business_unit`") or die(mysql_error());
			while ($str = mysql_fetch_array($bunit)) {

				$epascode = $str['bunit_epascode'];
				$promo = mysql_query("SELECT $epascode FROM `promo_record` WHERE `" . $str['bunit_field'] . "` = 'T' AND emp_id = '$empId'") or die(mysql_error());
				if (mysql_num_rows($promo) > 0) {

					$p = mysql_fetch_array($promo);

					if ($p[$epascode] == "") {
						$button++;
					}
				}
			}

			?>
			<button class="btn btn-primary btn-sm" onclick="transfer()" <?php if ($button == 0) : echo "disabled=''";
																		endif; ?>><i class="fa fa-reply-all"></i> Transfer Rate</button>
			<span class="loadingSave"></span>
		</div>
	</div>
<?php
} else if ($_GET['request'] == "transferRate") {

	$empId = $_POST['empId'];
	$recordNo = $_POST['recordNo'];
	$promoType = $_POST['promoType'];
	$store = explode("||", $_POST['store']);
	$noStore = 0;
	$mgaStore = "";

	if ($promoType == "STATION") : $loop = 0;
	else : $loop = 1;
	endif;

	for ($i = 0; $i < sizeof($store) - $loop; $i++) {

		$bu = explode("*", $store[$i]);

		$query = mysql_query("SELECT bunit_epascode FROM `locate_promo_business_unit` WHERE bunit_name = '" . trim($bu[0]) . "'") or die();
		$row = mysql_fetch_array($query);

		$epascode = $row['bunit_epascode'];

		$chkThis = mysql_query("SELECT $epascode FROM `promo_record` WHERE emp_id = '$empId'") or die(mysql_error());
		$chk = mysql_fetch_array($chkThis);

		if ($chk[$epascode] == "1") {

			$noStore++;
			$mgaStore .= trim($bu[0]) . "||";
		} else {

			mysql_query("UPDATE promo_record SET record_no = '$recordNo', $epascode = '1' WHERE emp_id = '$empId'") or die(mysql_error());
			mysql_query("UPDATE promo_history_record SET $epascode = '' WHERE emp_id = '$empId' and record_no = '" . trim($bu[1]) . "'") or die(mysql_error());
			mysql_query("UPDATE `appraisal_details` SET `record_no`= '$recordNo' WHERE `record_no` = '" . trim($bu[1]) . "' AND `emp_id` = '$empId' AND `store` = '" . trim($bu[0]) . "'") or die(mysql_error());

			$msg = "Appraisal Grade was Successfully Transfered!";
		}
	}

	$outlet = "";
	if ($noStore > 0) {

		$mgaBU = explode("||", $mgaStore);
		$counter = 0;

		for ($x = 0; $x < sizeof($mgaBU) - 1; $x++) {

			$counter++;
			if ($counter == 1) {

				$outlet .= $mgaBU[$x];
			} else {

				if ($x == sizeof($mgaBU) - 2) {

					$outlet .= " AND " . $mgaBU[$x];
				} else {

					$outlet .= ", " . $mgaBU[$x];
				}
			}
		}

		if ($noStore == 1) {

			$msg = "Cannot process end of contract appraisal of " . ucwords(strtolower($outlet)) . " has already done.";
		} else {

			$msg = "Cannot process end of contract appraisal of " . ucwords(strtolower($outlet)) . " has already done.";
		}
	}

	die("Ok||$msg");
} else if ($_GET['request'] == "printPermit") {

?>

	<link href="plugins/autoSuggest/css/jquery-ui.css" rel="stylesheet">
	<style type="text/css">
		.ui-autocomplete {
			padding: 0;
			list-style: none;
			background-color: #fff;
			width: 218px;
			border: 1px solid #B0BECA;
			max-height: 350px;
			overflow-x: hidden;
		}

		.ui-autocomplete .ui-menu-item {
			border-top: 1px solid #B0BECA;
			display: block;
			padding: 4px 6px;
			color: #353D44;
			cursor: pointer;
		}

		.ui-autocomplete .ui-menu-item:first-child {
			border-top: none;
		}

		.ui-autocomplete .ui-menu-item.ui-state-focus {
			background-color: #D5E5F4;
			color: #161A1C;
		}

		.ui-autocomplete {
			z-index: 9999;
		}
	</style>
	<input type="hidden" name="recordNo">
	<div class="form-group"> <i class="text-red">*</i>
		<label>Search Promo</label>
		<div class="input-group">
			<input class="form-control" name="employee" onkeyup="nameSearch(this.value)" autocomplete="off" type="text">
			<span class="input-group-addon"><i class="fa fa-user"></i></span>
		</div>
		<div class="search-results" style="display: none;"></div>
	</div>
	<div class="row">
		<div class="col-md-6">
			<div class="form-group"> <i class="text-red">*</i>
				<label>Business Unit</label>
				<select name="store" class="form-control" disabled="" onchange="inputField(this.name)">
					<option value=""> --Select-- </option>
				</select>
			</div>
			<div class="form-group"> <i class="text-red">*</i>
				<label>Duty Schedule</label>
				<select name="dutySched" class="form-control selects2 dutySched" disabled="" onchange="inputDutySched()">
					<option value=""> --Select-- </option>
					<?php

					$query = mysql_query("SELECT * FROM timekeeping.shiftcodes ORDER BY 1stIn, 1stOut, 2ndIn, 2ndOut ASC") or die(mysql_error());
					while ($row = mysql_fetch_array($query)) {
						$shiftCode 	= $row['shiftCode'];
						$In1 	   	= $row['1stIn'];
						$Out1 		= $row['1stOut'];
						$In2 		= $row['2ndIn'];
						$Out2 		= $row['2ndOut'];

						if ($In2 == "") {

							echo "<option value = '$shiftCode'>$shiftCode = $In1-$Out1 </option>";
						} else {

							echo "<option value = '$shiftCode'>$shiftCode = $In1-$Out1, $In2-$Out2</option>";
						}
					}
					?>
				</select>
			</div>
			<div class="form-group">
				<label>Special Schedule</label>
				<select name="specialSched" class="form-control selects2" disabled="" onchange="inputSpecialDays(this.value)">
					<option value=""> --Select-- </option>
					<?php

					$query = mysql_query("SELECT * FROM timekeeping.shiftcodes ORDER BY 1stIn, 1stOut, 2ndIn, 2ndOut ASC") or die(mysql_error());
					while ($row = mysql_fetch_array($query)) {
						$shiftCode 	= $row['shiftCode'];
						$In1 	   	= $row['1stIn'];
						$Out1 		= $row['1stOut'];
						$In2 		= $row['2ndIn'];
						$Out2 		= $row['2ndOut'];

						if ($In2 == "") {

							echo "<option value = '$shiftCode'>$shiftCode = $In1-$Out1 </option>";
						} else {

							echo "<option value = '$shiftCode'>$shiftCode = $In1-$Out1, $In2-$Out2</option>";
						}
					}
					?>
				</select>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group"> <i class="text-red">*</i>
				<label>Day Off</label>
				<select name="dayOff" class="form-control" disabled="" onchange="inputField(this.name)">
					<option value=""> --Select-- </option>
					<?php

					$days = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday', 'No Day Off');

					for ($x = 0; $x < sizeof($days); $x++) { ?>

						<option value="<?php echo $days[$x]; ?>"><?php echo $days[$x]; ?></option>
					<?php
					} ?>
				</select>
			</div>
			<div class="form-group"> <i class="text-red">*</i>
				<label>Duty Days</label>
				<input type="text" name="dutyDays" class="form-control" disabled="" onkeyup="inputField(this.name)" style="text-transform: uppercase;">
			</div>
			<div class="form-group">
				<label>Special Days</label>
				<input type="text" name="specialDays" class="form-control" disabled="" onkeyup="inputField(this.name)" style="text-transform: uppercase;">
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6">
			<label>Cut-off</label>
			<select name="cutOff" class="form-control" disabled="">
				<option value=""> --Select-- </option>
				<?php

				$cutOff = mysql_query("SELECT startFC, endFC, startSC, endSC, statCut FROM timekeeping.promo_schedule") or die(mysql_error());
				while ($c = mysql_fetch_array($cutOff)) {

					$statCut = $c['statCut'];

					if ($c['endFC'] == "") {

						$endFC = "last";
					} else {
						$endFC = $c['endFC'];
					}

					$cut_off = "$c[startFC]-$endFC / $c[startSC]-$c[endSC]";

					echo '<option value="' . $statCut . '|' . $cut_off . '">' . $cut_off . '</option>';
				}
				?>
			</select>
		</div>
	</div>
	<script src="plugins/autoSuggest/js/jquery-ui.min.js" type="text/javascript"></script>
	<script src="plugins/autoSuggest/js/jquery.select-to-autocomplete.js"></script>
	<script type="text/javascript">
		$(function() {

			$('.selects2').selectToAutocomplete();
		});
	</script>
<?php
} else if ($_GET['request'] == "printPreviousPermit") {

	$empId = $_POST['empId'];
	$recordNo = $_POST['recordNo'];

	$name = $empId . " * " . $nq->getEmpName($empId);
?>
	<style type="text/css">
		.ui-autocomplete {
			padding: 0;
			list-style: none;
			background-color: #fff;
			width: 218px;
			border: 1px solid #B0BECA;
			max-height: 350px;
			overflow-x: hidden;
		}

		.ui-autocomplete .ui-menu-item {
			border-top: 1px solid #B0BECA;
			display: block;
			padding: 4px 6px;
			color: #353D44;
			cursor: pointer;
		}

		.ui-autocomplete .ui-menu-item:first-child {
			border-top: none;
		}

		.ui-autocomplete .ui-menu-item.ui-state-focus {
			background-color: #D5E5F4;
			color: #161A1C;
		}

		.ui-autocomplete {
			z-index: 9999;
		}
	</style>
	<input type="hidden" name="recordNo" value="<?php echo $recordNo; ?>">
	<input type="hidden" name="empId" value="<?php echo $empId; ?>">
	<div class="form-group"> <i class="text-red">*</i>
		<label>Search Promo</label>
		<div class="input-group">
			<input class="form-control" name="employee" value="<?php echo $name; ?>" onkeyup="nameSearch(this.value)" autocomplete="off" type="text" disabled="">
			<span class="input-group-addon"><i class="fa fa-user"></i></span>
		</div>
		<div class="search-results" style="display: none;"></div>
	</div>
	<div class="row">
		<div class="col-md-6">
			<div class="form-group"> <i class="text-red">*</i>
				<label>Business Unit</label>
				<select name="store" class="form-control" onchange="inputField(this.name)">
					<option value=""> --Select-- </option>
				</select>
			</div>
			<div class="form-group"> <i class="text-red">*</i>
				<label>Duty Schedule</label>
				<select name="dutySched" class="form-control selects2 dutySched" onchange="inputDutySched()">
					<option value=""> --Select-- </option>

					<?php

					$query = mysql_query("SELECT * FROM timekeeping.shiftcodes ORDER BY 1stIn, 1stOut, 2ndIn, 2ndOut ASC") or die(mysql_error());
					while ($row = mysql_fetch_array($query)) {
						$shiftCode 	= $row['shiftCode'];
						$In1 	   	= $row['1stIn'];
						$Out1 		= $row['1stOut'];
						$In2 		= $row['2ndIn'];
						$Out2 		= $row['2ndOut'];

						if ($In2 == "") {

							echo "<option value = '$shiftCode'>$shiftCode = $In1-$Out1 </option>";
						} else {

							echo "<option value = '$shiftCode'>$shiftCode = $In1-$Out1, $In2-$Out2</option>";
						}
					}
					?>
				</select>
			</div>
			<div class="form-group">
				<label>Special Schedule</label>
				<select name="specialSched" class="form-control selects2 specialSched" onchange="inputSpecialDays(this.value)">
					<option value=""> --Select-- </option>
					<?php

					$query = mysql_query("SELECT * FROM timekeeping.shiftcodes ORDER BY 1stIn, 1stOut, 2ndIn, 2ndOut ASC") or die(mysql_error());
					while ($row = mysql_fetch_array($query)) {
						$shiftCode 	= $row['shiftCode'];
						$In1 	   	= $row['1stIn'];
						$Out1 		= $row['1stOut'];
						$In2 		= $row['2ndIn'];
						$Out2 		= $row['2ndOut'];

						if ($In2 == "") {

							echo "<option value = '$shiftCode'>$shiftCode = $In1-$Out1 </option>";
						} else {

							echo "<option value = '$shiftCode'>$shiftCode = $In1-$Out1, $In2-$Out2</option>";
						}
					}
					?>
				</select>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group"> <i class="text-red">*</i>
				<label>Day Off</label>
				<select name="dayOff" class="form-control" onchange="inputField(this.name)">
					<option value=""> --Select-- </option>
					<?php

					$days = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday', 'No Day Off');

					for ($x = 0; $x < sizeof($days); $x++) { ?>

						<option value="<?php echo $days[$x]; ?>"><?php echo $days[$x]; ?></option>
					<?php
					} ?>
				</select>
			</div>
			<div class="form-group"> <i class="text-red">*</i>
				<label>Duty Days</label>
				<input type="text" name="dutyDays" class="form-control" onkeyup="inputField(this.name)" style="text-transform: uppercase;">
			</div>
			<div class="form-group">
				<label>Special Days</label>
				<input type="text" name="specialDays" class="form-control" onkeyup="inputField(this.name)" style="text-transform: uppercase;">
			</div>
		</div>
	</div>

	<script src="plugins/autoSuggest/js/jquery.select-to-autocomplete.js"></script>
	<script type="text/javascript">
		$(function() {

			$('.selects2').selectToAutocomplete();
		});
	</script>
<?php
} else if ($_GET['request'] == "previousPermit") {

?>
	<div class="form-group">
		<label>Search Promo</label>
		<div class="input-group">
			<input class="form-control" name="employee" onkeyup="nameSearch(this.value)" autocomplete="off" type="text">
			<span class="input-group-addon"><i class="fa fa-user"></i></span>
		</div>
		<div class="search-results" style="display: none;"></div>
	</div>
	<div class="previousContract"></div>
<?php
} else if ($_GET['request'] == "viewPreviousContract") {

	$empId = $_POST['empId'];

	$query = mysql_query("SELECT record_no, startdate, eocdate FROM `employmentrecord_` WHERE emp_id = '$empId' ORDER BY startdate DESC") or die(mysql_error());
?>
	<div class="panel panel-default">
		<div class="panel-heading">
			<strong>Previous Permit</strong>
		</div>
		<div class="panel-body size-emp">
			<table class="table table-hover">
				<thead>
					<tr>
						<th>Startdate</th>
						<th>EOCdate</th>
						<th>
							<center>Action</center>
						</th>
					</tr>
				</thead>
				<tbody>
					<?php

					$no = 0;
					while ($row = mysql_fetch_array($query)) {

						$recordNo = $row['record_no'];
						echo "<tr>
										<td>" . date('M. d, Y', strtotime($row['startdate'])) . "</td>
										<td>" . date('M. d, Y', strtotime($row['eocdate'])) . "</td>
										<td align='center'><button class='btn btn-primary btn-block btn-sm' onclick='printPreviousPermit(\"$empId\",\"$recordNo\")'>Print Permit</button></td>
									</tr>";
					}
					?>
				</tbody>
			</table>
		</div>
	<?php
} else if ($_GET['request'] == "locateStore") {

	$empId = $_POST['empId'];
	$store = "";

	echo "<option value=''> --Select-- </option>";

	$bunit = mysql_query("SELECT bunit_field, bunit_name, bunit_permit, bunit_dutySched, bunit_dutyDays, bunit_specialSched, bunit_specialDays FROM `locate_promo_business_unit`") or die(mysql_error());
	while ($str = mysql_fetch_array($bunit)) {

		$promo = mysql_query("SELECT promo_id FROM `promo_record` WHERE `" . $str['bunit_field'] . "` = 'T' AND emp_id = '$empId'") or die(mysql_error());
		if (mysql_num_rows($promo) > 0) {

			echo "<option value='" . $str['bunit_name'] . "|" . $str['bunit_permit'] . "|" . $str['bunit_dutySched'] . "|" . $str['bunit_dutyDays'] . "|" . $str['bunit_specialSched'] . "|" . $str['bunit_specialDays'] . "'>" . $str['bunit_name'] . "</option>";
		}
	}
} else if ($_GET['request'] == "getEmpRecordNo") {

	$empId = $_POST['empId'];

	$bunit = mysql_query("SELECT record_no FROM `employee3` WHERE emp_id = '$empId'") or die(mysql_error());
	$row = mysql_fetch_array($bunit);

	die($row['record_no']);
} else if ($_GET['request'] == "editContractForm") {

	$empId = $_POST['empId'];
	$recordNo = $nq->getRec($empId);

	$query = mysql_query(" SELECT * from employment_witness where rec_no = '$recordNo' AND emp_id ='$empId'") or die(mysql_error());
	$row = mysql_fetch_array($query);

	$chNo 	= $row['contract_header_no'];
	$contractDate = $row['date_generated'];
	$w1 	= $row['witness1'];
	$w2 	= $row['witness2'];
	$issuedon = date("m/d/Y", strtotime($row['issuedon']));
	$issuedat = $row['issuedat'];

	$otherDetails = mysql_query("SELECT * FROM applicant_otherdetails WHERE app_id='$empId'") or die(mysql_error());
	$cs = mysql_fetch_array($otherDetails);

	$cedulaNo = $cs['cedula_no'];
	$sssNum = $cs['sss_no'];
	$cedula_date = date("m/d/Y", strtotime($cs['cedula_date']));
	$cedula_place = $cs['cedula_place'];

	if (@$contractDate == "" || @$contractDate == "0000-00-00") {
		$contractDate = '';
	} else {
		$contractDate = $nq->changeDateFormat('m/d/Y', $contractDate);
	}

	?>
		<style type="text/css">
			.issued {
				display: none;
			}
		</style>

		<input type="hidden" name="empId" value="<?php echo $empId; ?>">
		<input type="hidden" name="contract" value="current">
		<input type="hidden" name="recordNo" value="<?php echo $recordNo; ?>">
		<div class="panel panel-default">
			<div class="panel-heading">
				<strong>Edit & Generate Contract</strong>
			</div>
			<div class="panel-body">

				<div class="row">
					<div class="col-md-6">
						<div class="form-group"> <i class="text-red">*</i>
							<label>Witness(1)</label>
							<input type="text" name="witness1" class="form-control" placeholder="Firstname Lastname" autocomplete="off" style="text-transform: uppercase;" value="<?php if (!empty($w1)) : echo $w1;
																																													endif; ?>" onkeyup="searchWitness1(this.value)">
							<div class="witness1" style="display: none;"></div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group"> <i class="text-red">*</i>
							<label>Witness(2)</label>
							<input type="text" name="witness2" class="form-control" placeholder="Firstname Lastname" autocomplete="off" style="text-transform: uppercase;" value="<?php if (!empty($w2)) : echo $w2;
																																													endif; ?>" onkeyup="searchWitness2(this.value)">
							<div class="witness2" style="display: none;"></div>
						</div>
					</div>
				</div>
				<div class="form-group"> <i class="text-red">*</i>
					<label>Contract Header</label>
					<select name="contractHeader" class="form-control" onchange="inputField(this.name)">
						<option value=""> --Select-- </option>
						<?php

						$header = mysql_query("SELECT * FROM contract_header WHERE hr_location = '$hrCode' order by company") or die(mysql_error());
						while ($r = mysql_fetch_array($header)) { ?>

							<option value="<?php echo $r['ccode_no'] ?>" <?php if ($r['ccode_no'] == $chNo) : echo "selected=''";
																			endif; ?>><?php echo $r['company'] . " ----- " . $r['address']; ?></option>
						<?php
						}
						?>
					</select>
				</div>
				<div class="form-group"> <i class="text-red">*</i>
					<label>Please choose either to use Cedula (CTC No.) or SSS No.</label>
					<div style="margin-left:40px;">
						<p>
						<div class="row">
							<div class="col-md-4">
								<input type='radio' name='clear' style="border-color: red;" id="clear1" value='Cedula' onclick="sssctc('ctc')"> Cedula (CTC No.)
							</div>
							<div class="col-md-8">
								<input type='text' name='cedula' value="<?php if (!empty($cedulaNo)) : echo $cedulaNo;
																		endif; ?>" class="form-control issued" onkeyup="inputField(this.name)" data-inputmask='"mask": "CCI9999 99999999"' data-mask>
							</div>
						</div>
						</p>
						<p>
						<div class="row">
							<div class="col-md-4">
								<input type='radio' name='clear' id="clear2" value='SSS' onclick="sssctc('sss')"> SSS No.
							</div>
							<div class="col-md-8">
								<input type='text' name='sss' value="<?php if (!empty($sssNum)) : echo $sssNum;
																		endif; ?>" class="form-control issued" onkeyup="inputField(this.name)" data-inputmask='"mask": "99-9999999-9"' data-mask>
							</div>
						</div>
						</p>
						<p>
						<div class="row">
							<div class="col-md-6 issuedOn issued">
								<div class="form-group"> <i class="text-red">*</i>
									<label>Issued On</label>
									<div class="input-group date">
										<div class="input-group-addon">
											<i class="fa fa-calendar"></i>
										</div>
										<input type="text" name="issuedOn" class="form-control pull-right datepicker" value="<?php if (!empty($issuedon)) {
																																	echo $issuedon;
																																} else if (!empty($cedula_date)) {
																																	echo $cedula_date;
																																} else {
																																	echo date('m/d/Y');
																																} ?>" onchange="inputField(this.name)">
									</div>
								</div>
							</div>
							<div class="col-md-6 issuedAt issued">
								<div class="form-group"> <i class="text-red">*</i>
									<label>Issued At</label>
									<input type="text" name="issuedAt" class="form-control" value="<?php if (!empty($issuedat)) {
																										echo $issuedat;
																									} else if (!empty($cedula_place)) {
																										echo $cedula_place;
																									} else {
																										echo date('m/d/Y');
																									} ?>" onkeyup="inputField(this.name)">
								</div>
							</div>
						</div>
						</p>
					</div>
				</div>
				<div class="form-group"> <i class="text-red">*</i>
					<label>Date of Signing the Contract</label>
					<div class="input-group date">
						<div class="input-group-addon">
							<i class="fa fa-calendar"></i>
						</div>
						<input type="text" name="contractDate" class="form-control pull-right datepicker" value="<?php if (!empty($contractDate)) : echo $contractDate;
																													else : echo date('m/d/Y');
																													endif; ?>" onchange="inputField(this.name)">
					</div>
				</div>
			</div>
			<div class="panel-footer">
				<button class="btn btn-primary" onclick="genContract()"><i class="fa fa-file-pdf-o"></i> &nbsp;Generate Contract</button>
			</div>
		</div>
		<script type="text/javascript">
			//Date picker
			$('.datepicker').datepicker({
				inline: true,
				changeYear: true,
				changeMonth: true
			});

			// Input mask
			$("[data-mask]").inputmask();
		</script>
	<?php
} else if ($_GET['request']  == "findWitness1") {

	$key = mysql_real_escape_string($_POST['str']);
	$val = "";
	$empname = mysql_query("SELECT distinct(witness1) FROM `employment_witness` WHERE 
									(witness1 like '%$key%' or emp_id = '$key') ORDER BY witness1 ASC LIMIT 10") or die(mysql_error());
	if (mysql_num_rows($empname) > 0) {

		while ($n = mysql_fetch_array($empname)) {
			$name  = $n['witness1'];

			if ($val != $name) {
				echo "<a class = \"nameFind\" href = \"javascript:void(0)\" onclick='getWitness1(\"$name\")'>" . $name . "</a></br>";
			} else {
				echo "";
			}
		}
	} else {

		echo "";
	}
} else if ($_GET['request']  == "findWitness2") {

	$key = mysql_real_escape_string($_POST['str']);
	$val = "";
	$empname = mysql_query("SELECT distinct(witness2) FROM `employment_witness` WHERE 
									(witness2 like '%$key%' or emp_id = '$key') ORDER BY witness2 ASC LIMIT 10") or die(mysql_error());
	if (mysql_num_rows($empname) > 0) {

		while ($n = mysql_fetch_array($empname)) {
			$name  = $n['witness2'];

			if ($val != $name) {
				echo "<a class = \"nameFind\" href = \"javascript:void(0)\" onclick='getWitness2(\"$name\")'>" . $name . "</a></br>";
			} else {
				echo "";
			}
		}
	} else {

		echo "";
	}
} else if ($_GET['request'] == "submitContract") {

	$empId 		= $_POST['empId'];
	$recordNo 	= $_POST['recordNo'];
	$witness1 	= strtoupper(mysql_real_escape_string($_POST['witness1']));
	$witness2 	= strtoupper(mysql_real_escape_string($_POST['witness2']));
	$contractHeader = $_POST['contractHeader'];
	$contractDate = date("Y-m-d", strtotime($_POST['contractDate']));
	$cedula 	= $_POST['cedula'];
	$sss 		= $_POST['sss'];
	$issuedOn 	= date("Y-m-d", strtotime($_POST['issuedOn']));
	$issuedAt 	= mysql_real_escape_string($_POST['issuedAt']);
	$clear1 	= $_POST['clear1'];
	$clear2 	= $_POST['clear2'];

	$ctc_or_sss = "";
	$setField = "";
	$setWitnessField = "";
	if ($clear1 == "true") {

		$ctc_or_sss = "Cedula";
		$ctc_sssno = $_POST['cedula'];
		$setField = "`cedula_no`='$cedula',";
		$setWitnessField = "issuedon = '$issuedOn', issuedat = '$issuedAt',";
	}

	if ($clear2 == "true") {

		$ctc_or_sss = "SSS";
		$ctc_sssno = $_POST['sss'];
		$setField = "`sss_no`='$sss',";
		$setWitnessField = "issuedat = '$issuedAt',";
	}

	$witness_rec = mysql_query("SELECT * FROM employment_witness WHERE rec_no='$recordNo' AND emp_id = '$empId'") or die(mysql_error());
	$witNum = mysql_num_rows($witness_rec);

	if ($witNum > 0) {

		mysql_query("UPDATE employment_witness 
								SET 
									witness1='$witness1',
									witness2='$witness2',
									contract_header_no = '$contractHeader',
									sss_ctc = '$ctc_or_sss',
									sssno_ctcno = '$ctc_sssno',
									$setWitnessField
									date_generated = '$contractDate',
									generated_by = '$loginId'
								WHERE rec_no='$recordNo' AND emp_id = '$empId'
						") or die(mysql_error());
	} else {

		mysql_query("INSERT INTO employment_witness
								(ew_no, emp_id, rec_no, witness1, witness2, contract_header_no, sss_ctc, sssno_ctcno, issuedat, issuedon, date_generated, generated_by) 
							VALUES 
								('','$empId', '$recordNo','$witness1','$witness2','$contractHeader','$ctc_or_sss','$ctc_sssno','$issuedAt','$issuedOn','$contractDate','$loginId')") or die(mysql_error());
	}

	$otherDetails = mysql_query("SELECT `no` FROM `applicant_otherdetails` WHERE `app_id` = '$empId'") or die(mysql_error());
	$otherNum = mysql_num_rows($otherDetails);

	if ($otherNum > 0) {

		mysql_query("UPDATE `applicant_otherdetails` 
								SET 
									$setField
									`cedula_date`='$issuedOn',
									`cedula_place`='$issuedAt',
									`recordedby`='$loginId'
								WHERE `app_id`='$empId'
						") or die(mysql_error());
	} else {

		mysql_query("INSERT INTO `applicant_otherdetails`
										(`no`, `app_id`, `sss_no`, `cedula_no`, `cedula_date`, `cedula_place`, `recordedby`) 
									  VALUES ('','$empId','$sss','$cedula','$issuedOn','$issuedAt','$loginId')") or die(mysql_error());
	}

	die("Ok");
} else if ($_GET['request'] == "extend") {
	?>
		<div class="form-group"> <i class="text-red">*</i>
			<label>Search Promo</label>
			<div class="input-group">
				<input class="form-control" name="employee" onkeyup="nameSearch(this.value)" autocomplete="off" type="text">
				<span class="input-group-addon"><i class="fa fa-user"></i></span>
			</div>
			<div class="search-results" style="display: none;"></div>
		</div>
	<?php
} else if ($_GET['request'] == "manualRenewal") {
	?>
		<input type="hidden" name="filename">
		<div class="form-group"> <i class="text-red">*</i>
			<label>Search Promo</label>
			<div class="input-group">
				<input class="form-control" name="employee" onkeyup="abensonNamesearch(this.value)" autocomplete="off" type="text">
				<span class="input-group-addon"><i class="fa fa-user"></i></span>
			</div>
			<div class="search-results" style="display: none;"></div>
		</div>
		<br>
		<div class="form-group"> <i class="text-red">*</i>
			<label> Clearance </label>
			<div class="row">
				<div class="col-md-6"><input type="radio" name="clearance" required="" class="clearance" value="clearance" onclick="withClearance()"> With Clearance </div>
				<div class="col-md-6"><input type="radio" name="clearance" required="" class="clearance" value="noclearance" onclick="noClearance()"> Without Clearance </div>
			</div>
		</div>
		<hr>
		<div class="form-group"> <i class="text-red">*</i>
			<label> EOC Appraisal </label>
			<div class="row">
				<div class="col-md-6"><input type="radio" class="epas" value="epas" required="" onclick="withEpas()"> With EPAS </div>
				<div class="col-md-6"><input type="radio" class="epas" value="noepas" required="" onclick="noEpas()"> No EPAS </div>
			</div>
		</div>

		<script type="text/javascript">
			$(".clearance").prop("disabled", true);
			$(".epas").prop("disabled", true);
		</script>
		<?php
	} else if ($_GET['request'] == "findAbensonEmp") {

		$key = mysql_real_escape_string($_POST['str']);
		$val = "";
		$empname = mysql_query("SELECT employee3.emp_id, employee3.name FROM `promo_record`, `employee3` 
									WHERE promo_record.emp_id = employee3.emp_id AND (employee3.current_status = 'Active' or employee3.current_status = 'End of Contract' or employee3.current_status = 'Resign') 
									AND $promoEmpType AND (abenson_tag = 'T' || abenson_icm = 'T')
									AND (employee3.name like '%$key%' or employee3.emp_id = '$key') AND promo_record.hr_location = '$hrCode' GROUP BY employee3.emp_id order by name limit 10") or die(mysql_error());
		if (mysql_num_rows($empname) > 0) {

			while ($n = mysql_fetch_array($empname)) {
				$empId = $n['emp_id'];
				$name  = $n['name'];

				if ($val != $empId) {
					echo "<a class = \"nameFind\" href = \"javascript:void(0)\" onclick='getEmpId(\"$empId * $name\")'>" . $empId . " * " . $name . "</a></br>";
				} else {
					echo "<a class = \"afont\" href = \"javascript:void(0)\">No Result Found</a></br>";
				}
			}
		} else {

			echo "<a class = \"afont\" href = \"javascript:void(0)\">No Result Found</a></br>";
		}
	} else if ($_GET['request'] == "loadAgency") {

		$agency_code = $_POST['agency'];

		echo "<option value=''> --Select-- </option>";
		$comp = mysql_query("SELECT * FROM $datab2.`promo_locate_agency` WHERE status = '1' ORDER BY agency_name ASC") or die(mysql_error());
		while ($com = mysql_fetch_array($comp)) { ?>

			<option value="<?php echo $com['agency_code']; ?>" <?php if ($agency_code == $com['agency_code']) : echo "selected=''";
																endif; ?>><?php echo $com['agency_name']; ?></option>
		<?php
		}
	} else if ($_GET['request'] == "loadCompany") {

		$company = $_POST['company'];

		echo "<option value=''> --Select-- </option>";
		$sql = mysql_query("SELECT * FROM `locate_promo_company` WHERE status = '1' ORDER BY pc_name ASC") or die(mysql_error());
		while ($row = mysql_fetch_array($sql)) { ?>

			<option value='<?php echo $row['pc_code'] ?>' <?php if ($company == $row['pc_code']) : echo "selected=''";
															endif; ?>><?php echo $row['pc_name']; ?></option>
		<?php
		}
	} else if ($_GET['request'] == "loadDepartment") {

		$empId = $_POST['empId'];
		$promoType = mysql_real_escape_string($_POST['promoType']);
		$department = mysql_real_escape_string($_POST['department']);
		$condition = "";

		if ($promoType == "STATION") {

			$bunitQ = mysql_query("SELECT bunit_id, bunit_field FROM `locate_promo_business_unit`") or die(mysql_error());
			while ($bF = mysql_fetch_array($bunitQ)) {

				$bunitId = $bF['bunit_id'];
				$bunitField = $bF['bunit_field'];

				$appPD = mysql_query("SELECT promo_id FROM `promo_record` WHERE $bunitField = 'T'  AND emp_id = '$empId'") or die(mysql_error());
				$appPDNum = mysql_num_rows($appPD);

				if ($appPDNum > 0) {

					$condition = "AND bunit_id = '$bunitId'";
				}
			}
		} else {

			$ctr = 0;
			$bunitQ = mysql_query("SELECT bunit_id, bunit_field FROM `locate_promo_business_unit`") or die(mysql_error());
			while ($bF = mysql_fetch_array($bunitQ)) {

				$bunitId = $bF['bunit_id'];
				$bunitField = $bF['bunit_field'];

				$appPD = mysql_query("SELECT promo_id FROM `promo_record` WHERE $bunitField = 'T'  AND emp_id = '$empId'") or die(mysql_error());
				$appPDNum = mysql_num_rows($appPD);

				if ($appPDNum > 0) {

					$ctr++;
					if ($ctr == 1) {

						$condition .= "AND (bunit_id = '$bunitId' ";
					} else {

						$condition .= "OR bunit_id = '$bunitId' ";
					}
				}
			}

			if ($condition != "") {

				$condition .= ")";
			}
		}

		$locDept = mysql_query("SELECT dept_name FROM `locate_promo_department` WHERE status = 'active' $condition GROUP BY dept_name ORDER BY dept_name ASC") or die(mysql_error());
		while ($d = mysql_fetch_array($locDept)) { ?>

			<option value="<?php echo $d['dept_name']; ?>" <?php if ($department == $d['dept_name']) : echo "selected=''";
															endif; ?>><?php echo $d['dept_name']; ?></option>
		<?php
		}
	} else if ($_GET['request'] == 'loadVendor') {

		$department = htmlspecialchars($_POST['department']);
		$vendor_code = htmlspecialchars($_POST['vendor']);

		if ($department == "EASY FIX") {
			$department = 'FIXRITE';
		}

		echo "<option value=''> --Select-- </option>";
		$query = mysql_query("SELECT vendor_code, vendor_name FROM `promo_vendor_lists` WHERE department = '" . $department . "' AND vendor_name != '' ORDER BY vendor_name ASC") or die(mysql_error());
		while ($row = mysql_fetch_array($query)) { ?>

			<option value="<?= $row['vendor_code'] ?>" <?php if ($vendor_code == $row['vendor_code']) : echo "selected=''";
														endif; ?>><?= $row['vendor_name'] ?></option>"
		<?php
		}
	} else if ($_GET['request'] == "loadPosition") {

		$position = mysql_escape_string($_POST['position']);

		$sql = mysql_query("SELECT position_title AS position FROM position_leveling ORDER BY position ASC") or die(mysql_error());

		echo "<option value =''> --Select-- </option>";
		while ($row = mysql_fetch_array($sql)) { ?>

			<option value="<?php echo $row['position']; ?>" <?php if ($position == $row['position']) : echo "selected=''";
															endif; ?>><?php echo $row['position']; ?></option>
		<?php
		}
	} else if ($_GET['request'] == "loadPositionLevel") {

		$position = $_POST['position'];
		$query = mysql_query("SELECT level, lvlno FROM position_leveling WHERE position_title = '" . mysql_real_escape_string($position) . "'") or die(mysql_error());
		$pos = mysql_fetch_array($query);

		echo json_encode(array('level' => $pos['level'], 'levelno' => $pos['lvlno']));
	} else if ($_GET['request'] == "loadEmpType") {

		$empType = mysql_escape_string($_POST['empType']);

		$sql = mysql_query("SELECT emp_type FROM `employee_type` ORDER BY emp_type ASC") or die(mysql_error());
		while ($row = mysql_fetch_array($sql)) { ?>

			<option value="<?php echo $row['emp_type']; ?>" <?php if ($empType == $row['emp_type']) : echo "selected=''";
															endif; ?>><?php echo $row['emp_type']; ?></option>
		<?php
		}
	} else if ($_GET['request'] == "loadContractType") {

		$contractType = mysql_escape_string($_POST['contractType']);

		?>
		<option value="Contractual" <?php if ($contractType == "Contractual") : echo "selected=''";
									endif; ?>>Contractual</option>
		<option value="Seasonal" <?php if ($contractType == "Seasonal") : echo "selected=''";
									endif; ?>>Seasonal</option>
		<?php
	} else if ($_GET['request'] == "locateDeptS") {

		$id = explode("/", $_POST['id']);

		echo "<option value=''> Select Department </option>";
		$query = mysql_query("SELECT dept_name FROM `locate_promo_department` WHERE bunit_id  = '$id[0]' AND status = 'active'") or die(mysql_error());
		while ($row = mysql_fetch_array($query)) {

			echo "<option value='" . $row['dept_name'] . "'>" . $row['dept_name'] . "</option>";
		}
	} else if ($_GET['request'] == "showIntroS") {

		$id = explode("/", $_POST['id']);
		$bunitId = $id[0];
		$counter = 0;

		$query = mysql_query("SELECT bunit_id, bunit_name, bunit_intro FROM `locate_promo_business_unit` WHERE bunit_id = '$bunitId'") or die(mysql_error());
		while ($s = mysql_fetch_array($query)) {

			$counter++; ?>
			<tr>
				<td><?php echo $s['bunit_name']; ?></td>
				<td>
					<input type="file" name="<?php echo $s['bunit_intro']; ?>" class="form-control" id="intro_<?php echo $counter; ?>" onchange="validateForm(this.id)">
				</td>
				<td></td>
			</tr>
			<?php
		}
		echo "

			<tr style='display:none'>
				<td colspan='3'><input type='hidden' name='counter2' value='$counter'></td>
			</tr>";
	} else if ($_GET['request'] == "locateDeptR") {

		$storeId = explode("|", $_POST['storeId']);
		$count = sizeof($storeId);
		$condition = "";

		for ($i = 0; $i < $count - 1; $i++) {

			$store = explode("/", $storeId[$i]);
			if ($i == 0) {

				$condition .= "(bunit_id = '" . $store[0] . "'";
			} else {

				$condition .= " OR bunit_id = '" . $store[0] . "'";
			}
		}

		$condition .= ")";

		echo "<option value=''> Select Department</option>";
		$query = mysql_query("SELECT dept_name FROM `locate_promo_department` WHERE $condition AND status = 'active' GROUP BY dept_name") or die(mysql_error());
		while ($row = mysql_fetch_array($query)) {

			echo "<option value='$row[dept_name]'>$row[dept_name]</option>";
		}
	} else if ($_GET['request'] == "showIntroR") {

		$storeId = explode("|", $_POST['storeId']);
		$count = sizeof($storeId);
		$counter = 0;

		for ($i = 0; $i < $count; $i++) {

			$store = explode("/", $storeId[$i]);
			$bunitId = $store[0];

			$query = mysql_query("SELECT bunit_id, bunit_name, bunit_intro FROM `locate_promo_business_unit` WHERE bunit_id = '$bunitId'") or die(mysql_error());
			while ($r = mysql_fetch_array($query)) {

				$counter++;
			?>
				<tr>
					<td><?php echo $r['bunit_name']; ?></td>
					<td>
						<input type="file" name="<?php echo $r['bunit_intro']; ?>" class="form-control" id="intro_<?php echo $counter; ?>" onchange="validateForm(this.id)">
					</td>
					<td></td>
				</tr>
			<?php
			}
		}

		echo "
			<tr style='display:none'>
				<td colspan='3'><input type='hidden' name='counter2' value='$counter'></td>
			</tr>
		";
	} else if ($_GET['request'] == "locateBunit") {

		$promoType = $_POST['promoType'];

		if ($promoType == "ROVING") {
			?>
			<table class="table table-bordered">
				<tr>
					<th colspan="2"><i class="text-red">*</i> SELECT STORE</th>
				</tr>
				<?php

				$counter = 0;
				$store = mysql_query("SELECT * FROM `locate_promo_business_unit` WHERE status = 'active' AND hrd_location = '$hrCode'") or die(mysql_error());
				while ($r = mysql_fetch_array($store)) {

					$counter++;
				?>
					<tr>
						<td><input type="checkbox" id="check_<?php echo $counter; ?>" name="<?php echo $r['bunit_field']; ?>" value="<?php echo $r['bunit_id'] . '/' . $r['bunit_field']; ?>" onclick="locateDeptR()" /></td>
						<td><?php echo $r['bunit_name']; ?></td>
					</tr><?php
						}
							?>
				<input type="hidden" name="counter" value="<?php echo $counter; ?>">
			</table>
		<?php
		} else {

		?>
			<table class="table table-bordered">
				<tr>
					<th colspan="2"><i class="text-red">*</i> SELECT STORE</th>
				</tr>
				<?php

				$loop = 0;
				$store = mysql_query("SELECT * FROM `locate_promo_business_unit` WHERE status = 'active' AND hrd_location = '$hrCode'") or die(mysql_error());
				while ($s = mysql_fetch_array($store)) {

					$loop++;
				?>
					<tr>
						<td><input type="radio" name="station" id="radio_<?php echo $loop; ?>" value="<?php echo $s['bunit_id'] . '/' . $s['bunit_field']; ?>" onclick="locateDeptS(this.value)" /></td>
						<td><?php echo $s['bunit_name']; ?></td>
					</tr><?php
						}
							?>

				<input type="hidden" name="loop" value="<?php echo $loop; ?>">
			</table>
		<?php
		}
	} else if ($_GET['request'] == "loadPromoBusinessUnit") {

		$promoType = $_POST['promoType'];
		$empId = $_POST['empId'];
		$recordNo = $_POST['recordNo'];

		if ($promoType == "ROVING") { ?>
			<table class="table table-bordered">
				<tr>
					<th colspan="2"> Business Unit</th>
				</tr>
				<?php

				$counter = 0;
				$store = mysql_query("SELECT bunit_id, bunit_name, bunit_field FROM `locate_promo_business_unit` WHERE status = 'active' AND hrd_location = '$hrCode'") or die(mysql_error());
				while ($r = mysql_fetch_array($store)) {

					$counter++;
					$bunit = mysql_query("SELECT promo_id FROM `promo_record` WHERE $r[bunit_field] = 'T' AND emp_id = '$empId' AND record_no = '$recordNo'") or die(mysql_error());
					$bunitNum = mysql_num_rows($bunit);
				?>
					<tr>
						<td><input type="checkbox" class="checkedEnable" disabled="" id="check_<?php echo $counter; ?>" name="<?php echo $r['bunit_field']; ?>" value="<?php echo $r['bunit_id'] . '/' . $r['bunit_field']; ?>" <?php if ($bunitNum > 0) {
																																																									echo "checked=''";
																																																								} ?> onclick="locateDeptR()" /></td>
						<td><?php echo $r['bunit_name']; ?></td>
					</tr><?php
						}
							?>
				<input type="hidden" name="counter" value="<?php echo $counter; ?>">
			</table>
		<?php } else { ?>

			<table class="table table-bordered">
				<tr>
					<th colspan="2"> Business Unit</th>
				</tr>
				<?php

				$loop = 0;
				$store = mysql_query("SELECT * FROM `locate_promo_business_unit` WHERE status = 'active' AND hrd_location = '$hrCode'") or die(mysql_error());
				while ($s = mysql_fetch_array($store)) {

					$loop++;
					$bunit = mysql_query("SELECT promo_id FROM `promo_record` WHERE $s[bunit_field] = 'T' AND emp_id = '$empId' AND record_no = '$recordNo'") or die(mysql_error());
					$bunitNum = mysql_num_rows($bunit);

				?>
					<tr>
						<td><input type="radio" class="checkedEnable" disabled="" name="station" id="radio_<?php echo $loop; ?>" value="<?php echo $s['bunit_id'] . '/' . $s['bunit_field']; ?>" <?php if ($bunitNum > 0) {
																																																		echo "checked=''";
																																																	} ?> onclick="locateDeptS(this.value)" /></td>
						<td><?php echo $s['bunit_name']; ?></td>
					</tr><?php
						}
							?>
				<input type="hidden" name="loop" value="<?php echo $loop; ?>">
			</table>
			<?php
		}
	} else if ($_GET['request'] == "loadPromoIntro") {

		$promoType = $_POST['promoType'];
		$empId = $_POST['empId'];
		$recordNo = $_POST['recordNo'];

		if ($promoType == "STATION") {

			$counter = 0;
			$bunitIntro = mysql_query("SELECT bunit_id, bunit_field, bunit_name, bunit_intro FROM `locate_promo_business_unit`") or die(mysql_error());
			while ($bI = mysql_fetch_array($bunitIntro)) {

				$bunitId = $bI['bunit_id'];
				$bunitField = $bI['bunit_field'];

				$emp = mysql_query("SELECT promo_id FROM `promo_record` WHERE $bunitField = 'T'  AND emp_id = '$empId'") or die(mysql_error());
				$empNum = mysql_num_rows($emp);

				if ($empNum > 0) {

					$counter++;
			?>
					<tr>
						<td><i class="text-red">*</i> <?php echo $bI['bunit_name']; ?></td>
						<td>
							<input type="file" name="<?php echo $bI['bunit_intro']; ?>" class="form-control" id="intro_<?php echo $counter; ?>" onchange="validateForm(this.id)">
						</td>
						<td></td>
					</tr>
				<?php
				}
			}

			echo "
                    <tr style='display:none'>
                        <td colspan='3'><input type='hidden' name='counter2' value='$counter'></td>
                    </tr>
                ";
		} else {

			$counter = 0;
			$bunitIntro = mysql_query("SELECT bunit_id, bunit_field, bunit_name, bunit_intro FROM `locate_promo_business_unit`") or die(mysql_error());
			while ($bI = mysql_fetch_array($bunitIntro)) {

				$bunitId = $bI['bunit_id'];
				$bunitField = $bI['bunit_field'];

				$emp = mysql_query("SELECT promo_id FROM `promo_record` WHERE $bunitField = 'T'  AND emp_id = '$empId'") or die(mysql_error());
				$empNum = mysql_num_rows($emp);

				if ($empNum > 0) {

					$counter++;
				?>
					<tr>
						<td><i class="text-red">*</i> <?php echo $bI['bunit_name']; ?></td>
						<td>
							<input type="file" name="<?php echo $bI['bunit_intro']; ?>" class="form-control" id="intro_<?php echo $counter; ?>" onchange="validateForm(this.id)">
						</td>
						<td></td>
					</tr>
		<?php
				}
			}

			echo "
                    <tr style='display:none'>
                        <td colspan='3'><input type='hidden' name='counter2' value='$counter'></td>
                    </tr>
                ";
		}
	} else if ($_GET['request'] == "processRenewal") {

		$empId = $_POST['empId'];
		$recordNo = $_POST['recordNo'];
		$edited = $_POST['edited'];
		$name = $nq->getEmpName($empId);

		if ($_POST['companyDuration'] == "") :

			$comDuration = "0000-00-00";
		else :

			$comDuration = date("Y-m-d", strtotime($_POST['companyDuration']));
		endif;

		$startdate 	= date("Y-m-d", strtotime($_POST['startdate']));
		$eocdate 	= date("Y-m-d", strtotime($_POST['eocdate']));
		$duration 	= $_POST['duration'];
		$companyDuration = mysql_real_escape_string($_POST['companyDuration']);
		$introNames = explode("|", $_POST['introName']);
		$witness1 	= mysql_real_escape_string($_POST['witness1']);
		$witness2 	= mysql_real_escape_string($_POST['witness2']);
		$comments 	= mysql_real_escape_string($_POST['comments']);
		$remarks 	= mysql_real_escape_string($_POST['remarks']);

		$str = "";
		$addField = "";
		$addValue = "";

		$strNumArr = explode("|", $_POST['store']);
		for ($i = 0; $i < sizeof($strNumArr); $i++) {

			$str = explode("/", $strNumArr[$i]);

			if ($i == 0) {
				$addField .= "`$str[1]`";
				$addValue .= "'T'";
			} else {

				$addField .= ",`$str[1]`";
				$addValue .= ",'T'";
			}
		}

		if ($edited == "true") {

			$agency_code = mysql_real_escape_string($_POST['agency_select']);
			$companyCode = mysql_real_escape_string($_POST['company_select']);
			$promoType 	= mysql_real_escape_string($_POST['promoType_select']);
			$department = mysql_real_escape_string($_POST['department_select']);
			$vendor_code = mysql_real_escape_string($_POST['vendor_select']);
			$products = $_POST['product_select'];
			$position 	= mysql_real_escape_string($_POST['position_select']);
			$positionlevel = mysql_real_escape_string($_POST['positionlevel_select']);
			$empType 	= mysql_real_escape_string($_POST['empType_select']);
			$contractType = mysql_real_escape_string($_POST['contractType_select']);
			$statCut = mysql_real_escape_string($_POST['cutoff_select']);
		} else {

			$agency_code = mysql_real_escape_string($_POST['agency']);
			$companyCode = mysql_real_escape_string($_POST['company']);
			$promoType 	= mysql_real_escape_string($_POST['promoType']);
			$department = mysql_real_escape_string($_POST['department']);
			$vendor_code = mysql_real_escape_string($_POST['vendor']);
			$products = explode("|", mysql_real_escape_string($_POST['product']));
			$position 	= mysql_real_escape_string($_POST['position']);
			$positionlevel = mysql_real_escape_string($_POST['positionlevel']);
			$empType 	= mysql_real_escape_string($_POST['empType']);
			$contractType = mysql_real_escape_string($_POST['contractType']);
			$statCut = mysql_real_escape_string($_POST['cutoff']);
		}

		// print_r($products);

		$agency_code = ($agency_code == '') ? 0 : $agency_code;

		$comSql = mysql_query("SELECT pc_name FROM `locate_promo_company` WHERE pc_code = '$companyCode'") or die(mysql_error());
		$com = mysql_fetch_array($comSql);
		$company = mysql_real_escape_string($com['pc_name']);

		// upload intro(s)
		$introField = "";
		$introValue = "";

		for ($i = 0; $i < sizeof($introNames) - 1; $i++) {

			if (!empty($_FILES[$introNames[$i]]['name'])) {

				$image		= addslashes(file_get_contents($_FILES[$introNames[$i]]['tmp_name']));
				$image_name	= addslashes($_FILES[$introNames[$i]]['name']);
				$array 	= explode(".", $image_name);

				$filename 	= $empId . "=" . date('Y-m-d') . "=" . $introNames[$i] . "=" . date('H-i-s-A') . "." . end($array);
				$destination_path	= "../document/final_requirements/others/" . $filename;

				if (@move_uploaded_file($_FILES[$introNames[$i]]['tmp_name'], $destination_path)) {

					if ($i == 0) {
						$introField .= "`$introNames[$i]`";
						$introValue .= "'$destination_path'";
					} else {

						$introField .= " ,`$introNames[$i]`";
						$introValue	.= ",'$destination_path'";
					}

					$ins = mysql_query("INSERT INTO `application_otherreq`(`app_id`, `requirement_name`, `filename`, `date_time`, `requirement_status`, `receiving_staff`) 
											VALUES ('$empId','intro','$destination_path','$date','passed','$loginId')") or die(mysql_error());
				}
			}
		}

		$sql = mysql_query(
			"SELECT
					*
				 FROM
					employee3 
				 WHERE
					emp_id = '" . $empId . "' AND record_no = '" . $recordNo . "'"
		) or die(mysql_error());
		$old_data = mysql_fetch_array($sql);

		// insert the old contrct to the employment record table
		mysql_query(
			"INSERT
				INTO
			 employmentrecord_
				(
					emp_id,
					names,
					company_code,
					bunit_code,
					dept_code,
					section_code,
					sub_section_code,
					unit_code,
					barcodeId,
					bioMetricId,
					payroll_no,
					startdate,
					eocdate,
					emp_type,
					position,
					positionlevel,
					current_status,
					lodging,
					pos_desc,
					remarks,
					epas_code,
					contract,
					permit,
					clearance,
					comments,
					date_updated,
					updatedby,
					duration,
					emp_no,
					emp_pins
				) VALUES (
					'" . $empId . "',
					'" . $old_data['name'] . "',
					'" . $old_data['company_code'] . "',
					'" . $old_data['bunit_code'] . "',
					'" . $old_data['dept_code'] . "',
					'" . $old_data['section_code'] . "',
					'" . $old_data['sub_section_code'] . "',
					'" . $old_data['unit_code'] . "',
					'" . $old_data['barcodeId'] . "',
					'" . $old_data['bioMetricId'] . "',
					'" . $old_data['payroll_no'] . "',
					'" . $old_data['startdate'] . "',
					'" . $old_data['eocdate'] . "',
					'" . $old_data['emp_type'] . "',
					'" . $old_data['position'] . "',
					'" . $old_data['positionlevel'] . "',
					'End of Contract',
					'" . $old_data['lodging'] . "',
					'" . mysql_real_escape_string($old_data['position_desc']) . "',
					'" . mysql_real_escape_string($old_data['remarks']) . "',
					'" . $old_data['epas_code'] . "',
					'" . $old_data['contract'] . "',
					'" . $old_data['permit'] . "',
					'" . $old_data['clearance'] . "',
					'" . mysql_real_escape_string($old_data['comments']) . "',
					'" . $old_data['date_updated'] . "',
					'" . $old_data['updated_by'] . "',
					'" . $old_data['duration'] . "',
					'" . $old_data['emp_no'] . "',
					'" . $old_data['emp_pins'] . "'
				)"
		) or die(mysql_error());

		$barcodeId 	= $old_data['barcodeId'];
		$bioMetricId = $old_data['bioMetricId'];
		$payroll_no = $old_data['payroll_no'];

		$emp_no 	= $old_data['emp_no'];
		$emp_pins 	= $old_data['emp_pins'];

		$sql = mysql_query(
			"SELECT
					record_no
				  FROM
					employmentrecord_
				  WHERE
					emp_id = '" . $empId . "'
				  ORDER BY 
					record_no DESC"
		) or die(mysql_error());
		$new_rno = mysql_fetch_array($sql);

		// appraisal details
		$sql = mysql_query(
			"SELECT 
					record_no
				 FROM
					appraisal_details
				 WHERE
					record_no = '" . $old_data['record_no'] . "'
					and emp_id = '" . $empId . "'"
		) or die(mysql_error());
		$c_appdetails = mysql_num_rows($sql);

		if ($c_appdetails > 0) {
			mysql_query(
				"UPDATE
					appraisal_details
				 SET
					record_no = '" . $new_rno['record_no'] . "'
				 WHERE
					record_no = '" . $old_data['record_no'] . "'
					and emp_id = '" . $empId . "'"
			) or die(mysql_error());
		}

		// witness
		$sql = mysql_query(
			"SELECT
					rec_no
				 FROM
					employment_witness
				 WHERE
					rec_no = '" . $old_data['record_no'] . "'"
		) or die(mysql_error());

		$c_empwitness = mysql_num_rows($sql);
		if ($c_empwitness > 0) {
			mysql_query(
				"UPDATE
					employment_witness
				 SET
					rec_no = '" . $new_rno['record_no'] . "'
				 WHERE
					rec_no = '" . $old_data['record_no'] . "'"
			) or die(mysql_error());
		}

		$sql2 = mysql_query(
			"SELECT
					*
				 FROM
					promo_record 
				 WHERE
					emp_id = '" . $empId . "'"
		) or die(mysql_error());
		$old_promo_data = mysql_fetch_array($sql2);

		// insert the old contrct to the promo_history_record table
		mysql_query(
			"INSERT
				INTO
			 promo_history_record
				(
					emp_id,
					agency_code,
					promo_company,
					promo_department,
					vendor_code,
					company_duration,
					al_tag,
					al_tal,
					icm,
					pm,
					abenson_tag,
					abenson_icm,
					cdc,
					berama,
					al_tub,
					colc,
					colm,
					alta_citta,
					fr_panglao,
					fr_panglao_epascode,
					fr_panglao_contract,
					fr_panglao_permit,
					fr_panglao_clearance,
					fr_panglao_intro,
					fr_tubigon,
					fr_tubigon_epascode,
					fr_tubigon_contract,
					fr_tubigon_permit,
					fr_tubigon_clearance,
					fr_tubigon_intro,
					al_panglao,
					al_panglao_epascode,
					al_panglao_contract,
					al_panglao_permit,
					al_panglao_clearance,
					al_panglao_intro,
					promo_type,
					record_no,
					asc_epascode,
					tal_epascode,
					icm_epascode,
					pm_epascode,
					absna_epascode,
					absni_epascode,
					cdc_epascode,
					berama_epascode,
					tub_epascode,
					asc_contract,
					tal_contract,
					icm_contract,
					pm_contract,
					absna_contract,
					absni_contract,
					cdc_contract,
					berama_contract,
					tub_contract,
					asc_permit,
					tal_permit,
					icm_permit,
					pm_permit,
					absna_permit,
					absni_permit,
					cdc_permit,
					berama_permit,
					tub_permit,
					asc_clearance,
					tal_clearance,
					icm_clearance,
					pm_clearance,
					absna_clearance,
					absni_clearance,
					cdc_clearance,
					berama_clearance,
					tub_clearance,
					asc_intro,
					tal_intro,
					icm_intro,
					pm_intro,
					absna_intro,
					absni_intro,
					cdc_intro,
					berama_intro,
					tub_intro,
					colc_epascode,
					colc_contract,
					colc_permit,
					colc_clearance,
					colc_intro,
					colm_epascode,
					colm_contract,
					colm_permit,
					colm_clearance,
					colm_intro,
					alta_epascode,
					alta_contract,
					alta_permit,
					alta_clearance,
					alta_intro,
					type,
					epas,
					transferOn,
					addedoutlet,
					hr_location
				) VALUES (
					'" . $empId . "',
					'" . $old_promo_data['agency_code'] . "',
					'" . mysql_real_escape_string($old_promo_data['promo_company']) . "',
					'" . $old_promo_data['promo_department'] . "',
					'" . $old_promo_data['vendor_code'] . "',
					'" . $old_promo_data['company_duration'] . "',
					'" . $old_promo_data['al_tag'] . "',
					'" . $old_promo_data['al_tal'] . "',
					'" . $old_promo_data['icm'] . "',
					'" . $old_promo_data['pm'] . "',
					'" . $old_promo_data['abenson_tag'] . "',
					'" . $old_promo_data['abenson_icm'] . "',
					'" . $old_promo_data['cdc'] . "',
					'" . $old_promo_data['berama'] . "',
					'" . $old_promo_data['al_tub'] . "',
					'" . $old_promo_data['colc'] . "',
					'" . $old_promo_data['colm'] . "',
					'" . $old_promo_data['alta_citta'] . "',
					'" . $old_promo_data['fr_panglao'] . "',
					'" . $old_promo_data['fr_panglao_epascode'] . "',
					'" . $old_promo_data['fr_panglao_contract'] . "',
					'" . $old_promo_data['fr_panglao_permit'] . "',
					'" . $old_promo_data['fr_panglao_clearance'] . "',
					'" . $old_promo_data['fr_panglao_intro'] . "',
					'" . $old_promo_data['fr_tubigon'] . "',
					'" . $old_promo_data['fr_tubigon_epascode'] . "',
					'" . $old_promo_data['fr_tubigon_contract'] . "',
					'" . $old_promo_data['fr_tubigon_permit'] . "',
					'" . $old_promo_data['fr_tubigon_clearance'] . "',
					'" . $old_promo_data['fr_tubigon_intro'] . "',
					'" . $old_promo_data['al_panglao'] . "',
					'" . $old_promo_data['al_panglao_epascode'] . "',
					'" . $old_promo_data['al_panglao_contract'] . "',
					'" . $old_promo_data['al_panglao_permit'] . "',
					'" . $old_promo_data['al_panglao_clearance'] . "',
					'" . $old_promo_data['al_panglao_intro'] . "',
					'" . $old_promo_data['promo_type'] . "',
					'" . $new_rno['record_no'] . "',
					'" . $old_promo_data['asc_epascode'] . "',
					'" . $old_promo_data['tal_epascode'] . "',
					'" . $old_promo_data['icm_epascode'] . "',
					'" . $old_promo_data['pm_epascode'] . "',
					'" . $old_promo_data['absna_epascode'] . "',
					'" . $old_promo_data['absni_epascode'] . "',
					'" . $old_promo_data['cdc_epascode'] . "',
					'" . $old_promo_data['berama_epascode'] . "',
					'" . $old_promo_data['tub_epascode'] . "',
					'" . $old_promo_data['asc_contract'] . "',
					'" . $old_promo_data['tal_contract'] . "',
					'" . $old_promo_data['icm_contract'] . "',
					'" . $old_promo_data['pm_contract'] . "',
					'" . $old_promo_data['absna_contract'] . "',
					'" . $old_promo_data['absni_contract'] . "',
					'" . $old_promo_data['cdc_contract'] . "',
					'" . $old_promo_data['berama_contract'] . "',
					'" . $old_promo_data['tub_contract'] . "',
					'" . $old_promo_data['asc_permit'] . "',
					'" . $old_promo_data['tal_permit'] . "',
					'" . $old_promo_data['icm_permit'] . "',
					'" . $old_promo_data['pm_permit'] . "',
					'" . $old_promo_data['absna_permit'] . "',
					'" . $old_promo_data['absni_permit'] . "',
					'" . $old_promo_data['cdc_permit'] . "',
					'" . $old_promo_data['berama_permit'] . "',
					'" . $old_promo_data['tub_permit'] . "',
					'" . $old_promo_data['asc_clearance'] . "',
					'" . $old_promo_data['tal_clearance'] . "',
					'" . $old_promo_data['icm_clearance'] . "',
					'" . $old_promo_data['pm_clearance'] . "',
					'" . $old_promo_data['absna_clearance'] . "',
					'" . $old_promo_data['absni_clearance'] . "',
					'" . $old_promo_data['cdc_clearance'] . "',
					'" . $old_promo_data['berama_clearance'] . "',
					'" . $old_promo_data['tub_clearance'] . "',
					'" . $old_promo_data['asc_intro'] . "',
					'" . $old_promo_data['tal_intro'] . "',
					'" . $old_promo_data['icm_intro'] . "',
					'" . $old_promo_data['pm_intro'] . "',
					'" . $old_promo_data['absna_intro'] . "',
					'" . $old_promo_data['absni_intro'] . "',
					'" . $old_promo_data['cdc_intro'] . "',
					'" . $old_promo_data['berama_intro'] . "',
					'" . $old_promo_data['tub_intro'] . "',
					'" . $old_promo_data['colc_epascode'] . "',
					'" . $old_promo_data['colc_contract'] . "',
					'" . $old_promo_data['colc_permit'] . "',
					'" . $old_promo_data['colc_clearance'] . "',
					'" . $old_promo_data['colc_intro'] . "',
					'" . $old_promo_data['colm_epascode'] . "',
					'" . $old_promo_data['colm_contract'] . "',
					'" . $old_promo_data['colm_permit'] . "',
					'" . $old_promo_data['colm_clearance'] . "',
					'" . $old_promo_data['colm_intro'] . "',
					'" . $old_promo_data['alta_epascode'] . "',
					'" . $old_promo_data['alta_contract'] . "',
					'" . $old_promo_data['alta_permit'] . "',
					'" . $old_promo_data['alta_clearance'] . "',
					'" . $old_promo_data['alta_intro'] . "',
					'" . $old_promo_data['type'] . "',
					'" . $old_promo_data['epas'] . "',
					'" . $old_promo_data['transferOn'] . "',
					'" . $old_promo_data['addedoutlet'] . "',
					'" . $old_promo_data['hr_location'] . "'
				)"
		) or die(mysql_error());

		// delete the old record in employee3
		mysql_query(
			"DELETE
				FROM
			 employee3
				WHERE
			 emp_id = '" . $empId . "' AND record_no = '" . $recordNo . "'"
		) or die(mysql_error());

		// delete the old record in promo_record
		mysql_query(
			"DELETE
				FROM
			 promo_record
				WHERE
			 emp_id = '" . $empId . "' AND record_no = '" . $recordNo . "'"
		) or die(mysql_error());

		// insert new in employee 3
		$insertEmp = mysql_query(
			"INSERT 
				INTO
			 employee3
				(
					emp_id,
					name,
					startdate,
					eocdate,
					emp_type,
					current_status,
					barcodeId,
					bioMetricId,
					position,
					positionlevel,
					comments,
					remarks,
					date_added,
					added_by,
					duration,
					emp_no,
					emp_pins
				) VALUES (
					'" . $empId . "',
					'" . $name . "',
					'" . $startdate . "',
					'" . $eocdate . "',
					'" . $empType . "',
					'Active',
					'" . $barcodeId . "',
					'" . $bioMetricId . "',
					'" . $position . "',
					'" . intval($positionlevel) . "',
					'" . $comments . "',
					'" . $remarks . "',
					'" . $date . "',
					'" . $loginId . "',
					'" . $duration . "',
					'" . $emp_no . "',
					'" . $emp_pins . "'
				)"
		) or die(mysql_error());

		$new_recordNo = mysql_insert_id();

		mysql_query("INSERT INTO 
							`promo_record`
								(
									`record_no`, 
									`emp_id`, 
									`agency_code`, 
									`promo_company`, 
									`promo_department`, 
									`vendor_code`, 
									`company_duration`, 
									$addField, 
									`promo_type`, 
									$introField, 
									`type`, 
									`hr_location`
								) 
						VALUES 
								(
									'" . $new_recordNo . "',
									'" . $empId . "',
									'" . $agency_code . "',
									'" . $company . "',
									'" . $department . "',
									'" . $vendor_code . "',
									'" . $comDuration . "',
									$addValue,
									'" . $promoType . "',
									$introValue,
									'" . $contractType . "',
									'" . $hrCode . "'
								)
					") or die(mysql_error());

		mysql_query("INSERT INTO 
							`employment_witness`
								(
									`emp_id`, 
									`rec_no`, 
									`witness1`, 
									`witness2`
								) 
						VALUES 
								(
									'" . $empId . "',
									'" . $new_recordNo . "',
									'" . $witness1 . "',
									'" . $witness2 . "'
								)
					") or die(mysql_error());

		mysql_query("DELETE FROM promo_products WHERE record_no = '" . $new_recordNo . "' AND emp_id = '" . $empId . "'") or die(mysql_error());
		if (is_array($products)) {

			for ($i = 0; $i < count($products); $i++) {

				mysql_query("INSERT INTO promo_products
								(record_no, emp_id, product, created_at)
							VALUES 
								(
								'" . $new_recordNo . "',
								'" . $empId . "',
								'" . $products[$i] . "',
								'" . date('Y-m-d H:i:s') . "'
								)
							") or die(mysql_error());
			}
		}

		// secure clearance for promo
		$query = mysql_query("SELECT scpr_id FROM secure_clearance_promo WHERE emp_id = '" . $empId . "' AND status = 'Pending' ORDER BY scpr_id DESC LIMIT 1") or die(mysql_error());
		$secure = mysql_fetch_array($query);

		mysql_query("UPDATE secure_clearance_promo SET status = 'Completed' WHERE scpr_id = '" . $secure['scpr_id'] . "'") or die('Error in updated secure_clearance_promo ' . mysql_error());
		mysql_query("UPDATE secure_clearance_promo_details SET record_no = '" . $new_rno['record_no'] . "', clearance_status = 'Completed', date_cleared = '" . date("Y-m-d") . "' WHERE scpr_id = '" . $secure['scpr_id'] . "'") or die('Error in updating secure_clearance_promo_details' . mysql_error());

		$corporate = 'false';
		$talibon = 'false';
		$tubigon = 'false';
		$colon = 'false';
		$query = mysql_query("SELECT bunit_field FROM locate_promo_business_unit WHERE status = 'active'") or die(mysql_error());
		while ($bf = mysql_fetch_array($query)) {

			$chk_store = mysql_query("SELECT COUNT(promo_id) AS exist FROM promo_record WHERE record_no = '" . $new_recordNo . "' AND emp_id = '" . $empId . "' AND " . $bf['bunit_field'] . " = 'T'") or die(mysql_error());
			$store = mysql_fetch_array($chk_store);
			if ($store['exist'] > 0 && $bf['bunit_field'] == 'al_tal') {

				$talibon = 'true';
			} else if ($store['exist'] > 0 && $bf['bunit_field'] == 'al_tub') {

				$tubigon = 'true';
			} else if ($store['exist'] > 0 && ($bf['bunit_field'] == 'colm' || $bf['bunit_field'] == 'colc')) {

				$colon = 'true';
			} else {

				$corporate = 'true';
			}
		}

		// if ($corporate == 'true') {

		// $query = mysql_query("SELECT COUNT('peId') AS exist FROM timekeeping.promo_sched_emp WHERE recordNo = '" . $new_recordNo . "' AND empId = '" . $empId . "'") or die(mysql_error());
		// $e = mysql_fetch_array($query);
		// if ($e['exist'] == 0) {

		// 	$query = mysql_query("SELECT peId, recordNo FROM timekeeping.promo_sched_emp WHERE recordNo IN ('', '0') AND empId = '" . $empId . "'") or die(mysql_error());
		// 	$e = mysql_fetch_array($query);
		// 	if (mysql_num_rows($query) == 0) {

		// 		mysql_query("INSERT INTO timekeeping.promo_sched_emp (statCut, recordNo, empId, date_setup) VALUES ('" . $statCut . "', '" . $new_recordNo . "', '" . $empId . "', '" . date('Y-m-d') . "')") or die(mysql_error());
		// 	} else {

		// 		mysql_query("UPDATE timekeeping.promo_sched_emp SET statCut = '" . $statCut . "', recordNo = '" . $new_recordNo . "' WHERE recordNo = '" . $e['recordNo'] . "' AND empId='" . $empId . "'") or die(mysql_error());
		// 	}
		// } else {

		// 	mysql_query("UPDATE timekeeping.promo_sched_emp SET statCut = '" . $statCut . "' WHERE recordNo='" . $new_recordNo . "' AND empId='" . $empId . "'") or die(mysql_error());
		// }

		$get_oldCutoff = mysql_query("SELECT * FROM timekeeping.promo_sched_emp WHERE recordNo = '" . $old_data['record_no'] . "' AND empId = '" . $empId . "'");
		$cutoffNum =  mysql_num_rows($get_oldCutoff);

		if ($cutoffNum > 0) {

			mysql_query("UPDATE timekeeping.promo_sched_emp SET `recordNo`='" . $new_rno['record_no'] . "'
	   				WHERE `recordNo`='" . $old_data['record_no'] . "'");
		} else {

			mysql_query("INSERT INTO timekeeping.promo_sched_emp (statCut, recordNo, empId, date_setup)
					VALUES ('" . $statCut . "', '" . $new_rno['record_no'] . "', '" . $empId . "', '" . date('Y-m-d') . "')");
		}

		$insert_newSC = mysql_query("INSERT INTO timekeeping.promo_sched_emp (statCut, recordNo, empId, date_setup)
				VALUES ('" . $statCut . "', '" . $new_recordNo . "', '" . $empId . "', '" . date('Y-m-d') . "')");
		// }

		if ($talibon == 'true') {
			include("config_talibon_timekeeping.php");

			// $query = mysql_query("SELECT COUNT('peId') AS exist FROM promo_sched_emp WHERE recordNo = '" . $new_recordNo . "' AND empId = '" . $empId . "'") or die(mysql_error());
			// $e = mysql_fetch_array($query);
			// if ($e['exist'] == 0) {

			// 	$query = mysql_query("SELECT peId, recordNo FROM promo_sched_emp WHERE recordNo IN ('', '0') AND empId = '" . $empId . "'") or die(mysql_error());
			// 	$e = mysql_fetch_array($query);
			// 	if (mysql_num_rows($query) == 0) {

			// 		mysql_query("INSERT INTO promo_sched_emp (statCut, recordNo, empId, date_setup) VALUES ('" . $statCut . "', '" . $new_recordNo . "', '" . $empId . "', '" . date('Y-m-d') . "')") or die(mysql_error());
			// 	} else {

			// 		mysql_query("UPDATE promo_sched_emp SET statCut = '" . $statCut . "', recordNo = '" . $new_recordNo . "' WHERE recordNo = '" . $e['recordNo'] . "' AND empId='" . $empId . "'") or die(mysql_error());
			// 	}
			// } else {

			// 	mysql_query("UPDATE promo_sched_emp SET statCut = '" . $statCut . "' WHERE recordNo='" . $new_recordNo . "' AND empId='" . $empId . "'") or die(mysql_error());
			// }

			$get_oldCutoff = mysql_query("SELECT * FROM promo_sched_emp WHERE recordNo = '" . $old_data['record_no'] . "' AND empId = '" . $empId . "'");
			$cutoffNum =  mysql_num_rows($get_oldCutoff);

			if ($cutoffNum > 0) {

				mysql_query("UPDATE promo_sched_emp SET `recordNo`='" . $new_rno['record_no'] . "'
	   					WHERE `recordNo`='" . $old_data['record_no'] . "'");
			} else {

				mysql_query("INSERT INTO promo_sched_emp (statCut, recordNo, empId, date_setup)
						VALUES ('" . $statCut . "', '" . $new_rno['record_no'] . "', '" . $empId . "', '" . date('Y-m-d') . "')");
			}

			$insert_newSC = mysql_query("INSERT INTO promo_sched_emp (statCut, recordNo, empId, date_setup)
					VALUES ('" . $statCut . "', '" . $new_recordNo . "', '" . $empId . "', '" . date('Y-m-d') . "')");

			mysql_close($con);
		}

		if ($tubigon == 'true') {
			include("config_tubigon_timekeeping.php");

			// $query = mysql_query("SELECT COUNT('peId') AS exist FROM promo_sched_emp WHERE recordNo = '" . $new_recordNo . "' AND empId = '" . $empId . "'") or die(mysql_error());
			// $e = mysql_fetch_array($query);
			// if ($e['exist'] == 0) {

			// 	$query = mysql_query("SELECT peId, recordNo FROM promo_sched_emp WHERE recordNo IN ('', '0') AND empId = '" . $empId . "'") or die(mysql_error());
			// 	$e = mysql_fetch_array($query);
			// 	if (mysql_num_rows($query) == 0) {

			// 		mysql_query("INSERT INTO promo_sched_emp (statCut, recordNo, empId, date_setup) VALUES ('" . $statCut . "', '" . $new_recordNo . "', '" . $empId . "', '" . date('Y-m-d') . "')") or die(mysql_error());
			// 	} else {

			// 		mysql_query("UPDATE promo_sched_emp SET statCut = '" . $statCut . "', recordNo = '" . $new_recordNo . "' WHERE recordNo = '" . $e['recordNo'] . "' AND empId='" . $empId . "'") or die(mysql_error());
			// 	}
			// } else {

			// 	mysql_query("UPDATE promo_sched_emp SET statCut = '" . $statCut . "' WHERE recordNo='" . $new_recordNo . "' AND empId='" . $empId . "'") or die(mysql_error());
			// }

			$get_oldCutoff = mysql_query("SELECT * FROM promo_sched_emp WHERE recordNo = '" . $old_data['record_no'] . "' AND empId = '" . $empId . "'");
			$cutoffNum =  mysql_num_rows($get_oldCutoff);

			if ($cutoffNum > 0) {

				mysql_query("UPDATE promo_sched_emp SET `recordNo`='" . $new_rno['record_no'] . "'
	   					WHERE `recordNo`='" . $old_data['record_no'] . "'");
			} else {

				mysql_query("INSERT INTO promo_sched_emp (statCut, recordNo, empId, date_setup)
						VALUES ('" . $statCut . "', '" . $new_rno['record_no'] . "', '" . $empId . "', '" . date('Y-m-d') . "')");
			}

			$insert_newSC = mysql_query("INSERT INTO promo_sched_emp (statCut, recordNo, empId, date_setup)
					VALUES ('" . $statCut . "', '" . $new_recordNo . "', '" . $empId . "', '" . date('Y-m-d') . "')");

			mysql_close($con);
		}

		$activity = "Added a new Contract of Employment of " . $name . " Record No:" . $new_recordNo;
		$nq->savelogs($activity, $date, $time, $_SESSION['emp_id'], $_SESSION['username']);

		die("success||" . $name);
	} else if ($_GET['request'] == "printContractAndPermit") {

		$empId = $_POST['empId'];
		$name = $nq->getEmpName($empId);

		?>
		<p style="font-size: 15px;">
			Contract of Employment of <code><?php echo $name; ?></code> was successfully added.<br>
			Please Proceed on <code>Printing of Contract</code> and <code>Permit-to-Work</code>. &nbsp;Thank You!
		</p>
		<br><br>
		<div class="row">
			<div class="col-md-12">
				<div class="col-md-6">
					<button class="btn btn-primary btn-block" onclick="printPermit('<?php echo $empId; ?>')"> Permit-To-Work </button>
				</div>
				<div class="col-md-6">
					<button class="btn btn-primary btn-block" onclick="printContract('<?php echo $empId; ?>')"> Contract of Employment </button>
				</div>
			</div>
		</div>
	<?php
	} else if ($_GET['request'] == "printPermitRenewal") {

		$empId = $_POST['empId'];
		$recordNo = $nq->getRec($empId);
		$name = $nq->getEmpName($empId);

	?>
		<style type="text/css">
			.ui-autocomplete {
				padding: 0;
				list-style: none;
				background-color: #fff;
				width: 218px;
				border: 1px solid #B0BECA;
				max-height: 350px;
				overflow-x: hidden;
			}

			.ui-autocomplete .ui-menu-item {
				border-top: 1px solid #B0BECA;
				display: block;
				padding: 4px 6px;
				color: #353D44;
				cursor: pointer;
			}

			.ui-autocomplete .ui-menu-item:first-child {
				border-top: none;
			}

			.ui-autocomplete .ui-menu-item.ui-state-focus {
				background-color: #D5E5F4;
				color: #161A1C;
			}

			.ui-autocomplete {
				z-index: 9999;
			}
		</style>
		<input type="hidden" name="empId" value="<?php echo $empId; ?>">
		<input type="hidden" name="newest_recordNo" value="<?php echo $recordNo; ?>">
		<div class="form-group"> <i class="text-red">*</i>
			<label>Search Promo</label>
			<div class="input-group">
				<input class="form-control" name="employee" disabled="" value="<?php echo $empId . ' * ' . $name; ?>" onkeyup="nameSearch(this.value)" autocomplete="off" type="text">
				<span class="input-group-addon"><i class="fa fa-user"></i></span>
			</div>
			<div class="search-results" style="display: none;"></div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group"> <i class="text-red">*</i>
					<label>Business Unit</label>
					<select name="storeName" class="form-control" onchange="inputField(this.name)">
						<option value=""> --Select-- </option>
					</select>
				</div>
				<div class="form-group"> <i class="text-red">*</i>
					<label>Duty Schedule</label>
					<select name="dutySched" class="form-control selects2 dutySched" onchange="inputDutySched()">
						<option value=""> --Select-- </option>
						<?php

						$query = mysql_query("SELECT * FROM timekeeping.shiftcodes ORDER BY 1stIn, 1stOut, 2ndIn, 2ndOut ASC") or die(mysql_error());
						while ($row = mysql_fetch_array($query)) {
							$shiftCode 	= $row['shiftCode'];
							$In1 	   	= $row['1stIn'];
							$Out1 		= $row['1stOut'];
							$In2 		= $row['2ndIn'];
							$Out2 		= $row['2ndOut'];

							if ($In2 == "") {

								echo "<option value = '$shiftCode'>$shiftCode = $In1-$Out1 </option>";
							} else {

								echo "<option value = '$shiftCode'>$shiftCode = $In1-$Out1, $In2-$Out2</option>";
							}
						}
						?>
					</select>
				</div>
				<div class="form-group">
					<label>Special Schedule</label>
					<select name="specialSched" class="form-control selects2" onchange="inputSpecialDays(this.value)">
						<option value=""> --Select-- </option>
						<?php

						$query = mysql_query("SELECT * FROM timekeeping.shiftcodes ORDER BY 1stIn, 1stOut, 2ndIn, 2ndOut ASC") or die(mysql_error());
						while ($row = mysql_fetch_array($query)) {
							$shiftCode 	= $row['shiftCode'];
							$In1 	   	= $row['1stIn'];
							$Out1 		= $row['1stOut'];
							$In2 		= $row['2ndIn'];
							$Out2 		= $row['2ndOut'];

							if ($In2 == "") {

								echo "<option value = '$shiftCode'>$shiftCode = $In1-$Out1 </option>";
							} else {

								echo "<option value = '$shiftCode'>$shiftCode = $In1-$Out1, $In2-$Out2</option>";
							}
						}
						?>
					</select>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group"> <i class="text-red">*</i>
					<label>Day Off</label>
					<select name="dayOff" class="form-control" onchange="inputField(this.name)">
						<option value=""> --Select-- </option>
						<?php

						$days = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday', 'No Day Off');

						for ($x = 0; $x < sizeof($days); $x++) { ?>

							<option value="<?php echo $days[$x]; ?>"><?php echo $days[$x]; ?></option><?php
																									} ?>
					</select>
				</div>
				<div class="form-group"> <i class="text-red">*</i>
					<label>Duty Days</label>
					<input type="text" name="dutyDays" class="form-control" onkeyup="inputField(this.name)" style="text-transform: uppercase;">
				</div>
				<div class="form-group">
					<label>Special Days</label>
					<input type="text" name="specialDays" class="form-control" disabled="" onkeyup="inputField(this.name)" style="text-transform: uppercase;">
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<label>Cut-off</label>
				<select name="cutOff" class="form-control">
					<option value=""> --Select-- </option>
					<?php

					$cutOff = mysql_query("SELECT startFC, endFC, startSC, endSC, statCut FROM timekeeping.promo_schedule") or die(mysql_error());
					while ($c = mysql_fetch_array($cutOff)) {

						$statCut = $c['statCut'];

						if ($c['endFC'] == "") {

							$endFC = "last";
						} else {
							$endFC = $c['endFC'];
						}

						$cut_off = "$c[startFC]-$endFC / $c[startSC]-$c[endSC]";

						echo '<option value="' . $statCut . '|' . $cut_off . '">' . $cut_off . '</option>';
					}
					?>
				</select>
			</div>
		</div>
		<script src="plugins/autoSuggest/js/jquery.select-to-autocomplete.js"></script>
		<script type="text/javascript">
			$(function() {

				$('select.selects2').selectToAutocomplete();
			});
		</script>
	<?php
	} else if ($_GET['request'] == "printContractRenewal") {

		$empId = $_POST['empId'];
		$recordNo = $nq->getRec($empId);

		$query = mysql_query(" SELECT * from employment_witness where rec_no = '$recordNo' AND emp_id ='$empId'") or die(mysql_error());
		$row = mysql_fetch_array($query);

		$chNo 	= $row['contract_header_no'];
		$contractDate = $row['date_generated'];
		$w1 	= $row['witness1'];
		$w2 	= $row['witness2'];
		$issuedon = date("m/d/Y", strtotime($row['issuedon']));
		$issuedat = $row['issuedat'];

		$otherDetails = mysql_query("SELECT * FROM applicant_otherdetails WHERE app_id='$empId'") or die(mysql_error());
		$cs = mysql_fetch_array($otherDetails);

		$cedulaNo = $cs['cedula_no'];
		$sssNum = $cs['sss_no'];
		$cedula_date = date("m/d/Y", strtotime($cs['cedula_date']));
		$cedula_place = $cs['cedula_place'];

		if (@$contractDate == "" || @$contractDate == "0000-00-00") {
			$contractDate = '';
		} else {
			$contractDate = $nq->changeDateFormat('m/d/Y', $contractDate);
		}

	?>
		<style type="text/css">
			.issued {
				display: none;
			}
		</style>

		<input type="hidden" name="empId" value="<?php echo $empId; ?>">
		<input type="hidden" class="renewContract" value="renewal">
		<input type="hidden" name="contract_recordNo" value="<?php echo $recordNo; ?>">

		<div class="row">
			<div class="col-md-6">
				<div class="form-group"> <i class="text-red">*</i>
					<label>Witness(1)</label>
					<input type="text" name="witness1Renewal" class="form-control" placeholder="Firstname Lastname" style="text-transform: uppercase;" value="<?php if (!empty($w1)) : echo $w1;
																																								endif; ?>" onkeyup="searchWitness1(this.value)">
					<div class="witness1Renewal" style="display: none;"></div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group"> <i class="text-red">*</i>
					<label>Witness(2)</label>
					<input type="text" name="witness2Renewal" class="form-control" placeholder="Firstname Lastname" style="text-transform: uppercase;" value="<?php if (!empty($w2)) : echo $w2;
																																								endif; ?>" onkeyup="searchWitness2(this.value)">
					<div class="witness2Renewal" style="display: none;"></div>
				</div>
			</div>
		</div>
		<div class="form-group"> <i class="text-red">*</i>
			<label>Contract Header</label>
			<select name="contractHeader" class="form-control" onchange="inputField(this.name)">
				<option value=""> --Select-- </option>
				<?php

				$header = mysql_query("SELECT * FROM contract_header WHERE hr_location = '$hrCode' order by company") or die(mysql_error());
				while ($r = mysql_fetch_array($header)) { ?>

					<option value="<?php echo $r['ccode_no'] ?>" <?php if ($r['ccode_no'] == $chNo) : echo "selected=''";
																	endif; ?>><?php echo $r['company'] . " ----- " . $r['address']; ?></option><?php
																																			}
																																				?>
			</select>
		</div>
		<div class="form-group"> <i class="text-red">*</i>
			<label>Please choose either to use Cedula (CTC No.) or SSS No.</label>
			<div style="margin-left:40px;">
				<p>
				<div class="row">
					<div class="col-md-4">
						<input type='radio' name='clear' style="border-color: red;" id="clear1" value='Cedula' onclick="sssctc('ctc')"> Cedula (CTC No.)
					</div>
					<div class="col-md-8">
						<input type='text' name='cedula' value="<?php if (!empty($cedulaNo)) : echo $cedulaNo;
																endif; ?>" class="form-control issued" onkeyup="inputField(this.name)" data-inputmask='"mask": "CCI9999 99999999"' data-mask>
					</div>
				</div>
				</p>
				<p>
				<div class="row">
					<div class="col-md-4">
						<input type='radio' name='clear' id="clear2" value='SSS' onclick="sssctc('sss')"> SSS No.
					</div>
					<div class="col-md-8">
						<input type='text' name='sss' value="<?php if (!empty($sssNum)) : echo $sssNum;
																endif; ?>" class="form-control issued" onkeyup="inputField(this.name)" data-inputmask='"mask": "99-9999999-9"' data-mask>
					</div>
				</div>
				</p>
				<p>
				<div class="row">
					<div class="col-md-6 issuedOn issued">
						<div class="form-group"> <i class="text-red">*</i>
							<label>Issued On</label>
							<div class="input-group date">
								<div class="input-group-addon">
									<i class="fa fa-calendar"></i>
								</div>
								<input type="text" name="issuedOn" class="form-control pull-right datepicker" value="<?php if (!empty($issuedon)) {
																															echo $issuedon;
																														} else if (!empty($cedula_date)) {
																															echo $cedula_date;
																														} else {
																															echo date('m/d/Y');
																														} ?>" onchange="inputField(this.name)">
							</div>
						</div>
					</div>
					<div class="col-md-6 issuedAt issued">
						<div class="form-group"> <i class="text-red">*</i>
							<label>Issued At</label>
							<input type="text" name="issuedAt" class="form-control" value="<?php if (!empty($issuedat)) {
																								echo $issuedat;
																							} else if (!empty($cedula_place)) {
																								echo $cedula_place;
																							} else {
																								echo date('m/d/Y');
																							} ?>" onkeyup="inputField(this.name)">
						</div>
					</div>
				</div>
				</p>
			</div>
		</div>
		<div class="form-group"> <i class="text-red">*</i>
			<label>Date of Signing the Contract</label>
			<div class="input-group date">
				<div class="input-group-addon">
					<i class="fa fa-calendar"></i>
				</div>
				<input type="text" name="contractDate" class="form-control pull-right datepicker" value="<?php if (!empty($contractDate)) : echo $contractDate;
																											else : echo date('m/d/Y');
																											endif; ?>" onchange="inputField(this.name)">
			</div>
		</div>
		<script type="text/javascript">
			//Date picker
			$('.datepicker').datepicker({

				inline: true,
				changeYear: true,
				changeMonth: true
			});

			// Input mask
			$("[data-mask]").inputmask();
		</script>
	<?php

	} else if ($_GET['request'] == "uploadClearanceforRenewal") {

		$empId = $_POST['empId'];
		$recordNo = $_POST['recordNo'];
		$name = $nq->getEmpName($empId);

	?>
		<?php

		$counter = 0;
		$store = mysql_query("SELECT bunit_id, bunit_name, bunit_field, bunit_clearance FROM `locate_promo_business_unit` WHERE hrd_location='$hrCode'") or die(mysql_error());
		while ($str = mysql_fetch_array($store)) {

			$bunitName 	= ucwords(strtolower($str['bunit_name']));
			$bunitField = $str['bunit_field'];;
			$bunitClearance = $str['bunit_clearance'];

			$appPD = mysql_query("SELECT promo_id, $bunitClearance FROM `promo_record` WHERE $bunitField = 'T'  AND emp_id = '$empId' AND record_no = '$recordNo'") or die(mysql_error());
			$appPDNum = mysql_num_rows($appPD);
			$row = mysql_fetch_array($appPD);

			if ($appPDNum > 0) {
				$counter++;

		?>

				<input type="hidden" name="clearance[]" value="<?php echo $bunitClearance; ?>">
				<div class="row">
					<div class="col-md-12">
						<b><?php echo "Clearance ($bunitName)"; ?></b><br>
						<img id="photo<?php echo $bunitClearance; ?>" class='preview img-responsive' /><br>
						<input type='file' name='<?php echo $bunitClearance; ?>' id='<?php echo $bunitClearance; ?>' class='btn btn-default clearance_<?php echo $counter; ?>' onchange='readURL(this,"<?php echo $bunitClearance; ?>");'>
						<input type='button' name='clear<?php echo $bunitClearance; ?>' id='clear<?php echo $bunitClearance; ?>' style='display:none' class='btn btn-default' value='Clear' onclick="clears('<?php echo $bunitClearance; ?>','photo<?php echo $bunitClearance; ?>','clear<?php echo $bunitClearance; ?>')">
						<input type='button' id='<?php echo $bunitClearance; ?>_change' style='display:none;' class='btn btn-primary btn-sm' value='Change Clearance?' onclick='changePhoto("Clearance","<?php echo $bunitClearance; ?>","<?php echo $bunitClearance; ?>_change")'>
					</div>
				</div><br>
		<?php
			}
		}
		?>

		<input type="hidden" name="counter" value="<?php echo $counter; ?>">
		<input type="hidden" name="empId" id="empId" value="<?php echo $empId; ?>">
		<input type="hidden" name="recordNo" id="recordNo" value="<?php echo $recordNo; ?>">
		<script type="text/javascript">
			/*var counter = $("[name = 'counter']").val();
				var empId = $("[name = 'empId']").val();
				var recordNo = $("[name = 'recordNo']").val();

				var clearance = $("[name = 'clearance[]']");
				var contract = $("[name = 'contract[]']");

				var table = "promo_record";

				for (var i = 0; i < counter; i++) {

					$('#'+clearance[i].value).val('');
					$('#clear'+clearance[i].value).hide();
					
					(function (i) {

							$('#photo'+clearance[i].value).removeAttr('src');
					      	$.ajax({
								type : "POST",
								url : "employee_information_details.php?request=getScannedPromoFile",
								data : { table:table, recordNo:recordNo, empId:empId, field:clearance[i].value },
								success : function(data){

									if(data != ''){				
										document.getElementById("photo"+clearance[i].value).src = data;	
										$('#'+clearance[i].value).hide();		
										$('#'+clearance[i].value+"_change").show();								
									}else{
										$('#'+clearance[i].value+"_change").hide();	
										$('#'+clearance[i].value).show();
									}			
								}
							});
					      	
					})(i);
				}*/
		</script>
	<?php
	} else if ($_GET['request'] == "savingCleranceforRenewal") {

		$empId = $_POST['empId'];
		$recordNo = $_POST['recordNo'];
		$clearance = $_POST['clearance'];

		$clearanceFlag = "";

		// echo "Key=" . $key . ", Value=" . $value;
		foreach ($clearance as $key => $value) {

			$destination_path = "";
			if (!empty($_FILES[$value]['name'])) {

				$image		= addslashes(file_get_contents($_FILES[$value]['tmp_name']));
				$image_name	= addslashes($_FILES[$value]['name']);
				$array 	= explode(".", $image_name);

				$filename 	= $empId . "=" . date('Y-m-d') . "=" . $value . "=" . date('H-i-s-A') . "." . end($array);
				$destination_path	= "../document/clearance/" . $filename;

				if (move_uploaded_file($_FILES[$value]['tmp_name'], $destination_path)) {

					mysql_query("UPDATE promo_record SET $value = '" . mysql_real_escape_string($destination_path) . "' WHERE emp_id = '" . $empId . "' AND record_no = '" . $recordNo . "'") or die(mysql_error());
					$clearanceFlag = "true";
				}
			}
		}

		$name = mysql_real_escape_string($nq->getPromoName($_POST['empId']));

		if ($clearanceFlag == 'true') {

			$activity = "Uploaded the scanned Clearance for Renewal of " . $name . " Record No." . $recordNo;
			$nq->savelogs($activity, $date, $time, $_SESSION['emp_id'], $_SESSION['username']);

			die("success");
		}
	} else if ($_GET['request'] == "uploadClearanceAbenson") {

		$empId = $_POST['empId'];
		$recordNo = $nq->getRec($empId);
		$name = $nq->getEmpName($empId);

	?>
		<?php

		$counter = 0;
		$store = mysql_query("SELECT bunit_id, bunit_name, bunit_field, bunit_clearance FROM `locate_promo_business_unit` WHERE hrd_location='$hrCode'") or die(mysql_error());
		while ($str = mysql_fetch_array($store)) {

			$bunitName 	= ucwords(strtolower($str['bunit_name']));
			$bunitField = $str['bunit_field'];;
			$bunitClearance = $str['bunit_clearance'];

			$appPD = mysql_query("SELECT promo_id, $bunitClearance FROM `promo_record` WHERE $bunitField = 'T'  AND emp_id = '$empId' AND record_no = '$recordNo'") or die(mysql_error());
			$appPDNum = mysql_num_rows($appPD);
			$row = mysql_fetch_array($appPD);

			if ($appPDNum > 0) {
				$counter++;

		?>

				<input type="hidden" name="clearance[]" value="<?php echo $bunitClearance; ?>">
				<div class="row">
					<div class="col-md-12">
						<b><?php echo "Clearance ($bunitName)"; ?></b><br>
						<img id="photo<?php echo $bunitClearance; ?>" class='preview img-responsive' /><br>
						<input type='file' name='<?php echo $bunitClearance; ?>' id='<?php echo $bunitClearance; ?>' class='btn btn-default clearance_<?php echo $counter; ?>' onchange='readURL(this,"<?php echo $bunitClearance; ?>");'>
						<input type='button' name='clear<?php echo $bunitClearance; ?>' id='clear<?php echo $bunitClearance; ?>' style='display:none' class='btn btn-default' value='Clear' onclick="clears('<?php echo $bunitClearance; ?>','photo<?php echo $bunitClearance; ?>','clear<?php echo $bunitClearance; ?>')">
						<input type='button' id='<?php echo $bunitClearance; ?>_change' style='display:none;' class='btn btn-primary btn-sm' value='Change Clearance?' onclick='changePhoto("Clearance","<?php echo $bunitClearance; ?>","<?php echo $bunitClearance; ?>_change")'>
					</div>
				</div>
		<?php
			}
		}
		?>

		<input type="hidden" name="counter" value="<?php echo $counter; ?>">
		<input type="hidden" name="empId" id="empId" value="<?php echo $empId; ?>">
		<input type="hidden" name="recordNo" id="recordNo" value="<?php echo $recordNo; ?>">

		<?php
	} else if ($_GET['request'] == "uploadEpasAbenson") {

		$empId = $_POST['empId'];
		$recordNo = $nq->getRec($empId);
		$name = $nq->getEmpName($empId);

		$counter = 0;
		$epas = "";
		$storeName = "";
		$store = mysql_query("SELECT bunit_id, bunit_name, bunit_field, bunit_epascode FROM `locate_promo_business_unit` WHERE hrd_location='$hrCode'") or die(mysql_error());
		while ($str = mysql_fetch_array($store)) {

			$bunitName 	= ucwords(strtolower($str['bunit_name']));
			$bunitField = $str['bunit_field'];;
			$bunitEpascode = $str['bunit_epascode'];

			$appPD = mysql_query("SELECT promo_id FROM `promo_record` WHERE $bunitField = 'T'  AND emp_id = '$empId' AND record_no = '$recordNo'") or die(mysql_error());
			$appPDNum = mysql_num_rows($appPD);
			$row = mysql_fetch_array($appPD);

			if ($appPDNum > 0) {
				$counter++;

				$epas = $bunitEpascode;
				$storeName = $bunitName;

		?>
				<input type="hidden" name="empId" value="<?php echo $empId; ?>">
				<input type="hidden" name="recordNo" value="<?php echo $recordNo; ?>">
				<input type="hidden" name="epas" value="<?php echo $epas; ?>">
				<input type="hidden" name="store" value="<?php echo $storeName; ?>">

				<div class="form-group"> <i class="text-red">*</i>
					<label>Epas Grade</label>
					<select name="epasGrade" class="form-control select2">
						<option value=""></option>
						<?php

						for ($i = 100; $i > 0; $i -= 0.01) { ?>

							<option value="<?php echo sprintf("%.2f", $i); ?>"><?php echo sprintf("%.2f", $i); ?></option> <?php
																														}
																															?>
					</select>
				</div>
		<?php
			}
		}

		?>
		<script type="text/javascript">
			$(".select2").select2();
		</script>
	<?php
	} else if ($_GET['request'] == "submitEpasAbenson") {

		$empId = $_POST['empId'];
		$recordNo = $_POST['recordNo'];
		$epas = mysql_real_escape_string($_POST['epas']);
		$store = mysql_real_escape_string($_POST['store']);
		$epasGrade = $_POST['epasGrade'];

		$dateTimeAdded = date('Y-m-d H:i:s');

		if ($epasGrade == 100) {

			$desrate = "Excellent";
			$code = "E";
		} else if ($epasGrade >= 90 && $epasGrade <= 99.99) {

			$desrate = "Very Satisfactory";
			$code = "VS";
		} else if ($epasGrade >= 85 && $epasGrade <= 89.99) {

			$desrate = "Satisfactory";
			$code = "S";
		} else if ($epasGrade >= 70 && $epasGrade <= 84.99) {

			$desrate = "Unsatisfactory";
			+$code = "US";
		} else if ($epasGrade >= 0 && $epasGrade <= 69.99) {

			$desrate = "Very Unsatisfactory";
			$code = "VU";
		} else {

			$desrate = "Very Unsatisfactory";
			$code = "VU";
		}

		$updateRec = mysql_query("UPDATE promo_record SET $epas = '1' WHERE emp_id = '$empId' AND record_no = '$recordNo'") or die(mysql_error());
		$insertRec = mysql_query("INSERT INTO appraisal_details
											(emp_id, record_no, addedby, numrate, descrate, ratingdate, code, store) 
										VALUES 
											(
												'$empId',
												'$recordNo',
												'$loginId',
												'$epasGrade',
												'$code',
												'$dateTimeAdded',
												11,
												'$store'
											)
								") or die(mysql_error());

		$name = mysql_real_escape_string($nq->getEmpName($empId));
		$nq->savelogs("Added the EOC Appraisal for Abenson of " . $name . " record no." . $recordNo, $date, $time, $_SESSION['emp_id'], $_SESSION['username']);

		if ($updateRec && $insertRec) {

			die("success");
		}
	} else if ($_GET['request'] == "cancelRenewal") {

		$empId = $_POST['empId'];
		$recordNo = $nq->getRec($empId);

		$counter = 0;
		$epasField = "";
		$clearanceField = "";
		$storeName = "";

		$store = mysql_query("SELECT bunit_id, bunit_name, bunit_field, bunit_epascode, bunit_clearance FROM `locate_promo_business_unit` WHERE hrd_location='$hrCode'") or die(mysql_error());
		while ($str = mysql_fetch_array($store)) {

			$bunitName 	= ucwords(strtolower($str['bunit_name']));
			$bunitField = $str['bunit_field'];;
			$bunitEpascode = $str['bunit_epascode'];
			$bunitClearance = $str['bunit_clearance'];

			$appPD = mysql_query("SELECT promo_id, $bunitClearance FROM `promo_record` WHERE $bunitField = 'T'  AND emp_id = '$empId' AND record_no = '$recordNo'") or die(mysql_error());
			$appPDNum = mysql_num_rows($appPD);
			$row = mysql_fetch_array($appPD);

			if ($appPDNum > 0) {

				$counter++;
				unlink($row[$bunitClearance]);
				$storeName = $bunitName;

				if ($counter == 1) {

					$epasField = "$bunitEpascode = ''";
					$clearanceField = "$bunitClearance = ''";
				} else {

					$epasField .= ", $bunitEpascode = ''";
					$clearanceField .= ", $bunitClearance = ''";
				}
			}
		}

		$record = mysql_query("UPDATE `promo_record` SET $epasField, $clearanceField WHERE `emp_id` = '$empId' and `record_no` = '$recordNo'") or die(mysql_error());
		$appraisal = mysql_query("DELETE FROM appraisal_details WHERE `emp_id` = '$empId' AND `record_no` = '$recordNo' AND `store` = '$storeName'") or die(mysql_error());

		if ($record && $appraisal) {

			die("success");
		}
	} else if ($_GET['request'] == "removeOutlet") {

		$empId = $_POST['empId'];
		$recordNo = $nq->getRec($empId);

	?>
		<hr>
		<table class="table table-bordered table-hover">
			<thead>
				<?php

				$bunit = mysql_query("SELECT bunit_acronym FROM `locate_promo_business_unit` WHERE appraisal_status = 'active' AND hrd_location = '$hrCode'") or die(mysql_error());
				while ($str = mysql_fetch_array($bunit)) {

					echo "<th align='center'>" . $str['bunit_acronym'] . "</th>";
				}
				?>
				<th>ACTION</th>
			</thead>
			<tbody>
				<?php

				$counter = 0;
				$bunit = mysql_query("SELECT bunit_field, bunit_name FROM `locate_promo_business_unit` WHERE appraisal_status = 'active' AND hrd_location = '$hrCode'") or die(mysql_error());
				while ($str = mysql_fetch_array($bunit)) {

					echo "<td>";
					$promo = mysql_query("SELECT promo_id FROM `promo_record` WHERE `" . $str['bunit_field'] . "` = 'T' AND emp_id = '" . $empId . "'") or die(mysql_error());
					if (mysql_num_rows($promo) > 0) {

						$storeApp = mysql_query("SELECT details_id, numrate, descrate, raterSO, rateeSO FROM `appraisal_details` WHERE emp_id = '$empId' AND record_no = '$recordNo' AND store = '" . $str['bunit_name'] . "'") or die(mysql_error());
						$epas = mysql_fetch_array($storeApp);

						$raterSO = $epas['raterSO'];
						$rateeSO = $epas['rateeSO'];
						$numrate = $epas['numrate'];

						if ($numrate != "") {

							if ($raterSO == 1 && $rateeSO == 1) {

								$rate = "yes";
								$label = "btn btn-success btn-xs btn-flat";
							} else {

								$rate = "no";
								$label = "btn btn-warning btn-xs btn-flat";
							}

							if ($numrate == 100) {
								$grade = 'pass';
								$label2 = "btn btn-success btn-flat btn-xs";
							} else if ($numrate >= 90 && $numrate <= 99.99) {
								$grade = 'pass';
								$label2 = "btn btn-primary btn-flat btn-xs";
							} else if ($numrate >= 85 && $numrate <= 89.99) {
								$grade = 'pass';
								$label2 = "btn btn-info btn-flat btn-xs";
							} else if ($numrate >= 70 && $numrate <= 84.99) {
								$grade = 'failed';
								$label2 = "btn btn-danger btn-flat btn-xs";
							} else if ($numrate >= 0 && $numrate <= 69.99) {
								$grade = 'failed';
								$label2 = "btn btn-danger btn-flat btn-xs";
							} else {
								$label2 = "label label-danger";
							}

							echo "<a href='javascript:void(0)' onclick=viewdetails('$epas[details_id]') title='Click to View Appraisal Details'> <span class='$label2'>$numrate</span></a> <span class='$label'>$rate</span>";

							if ($grade == "pass" && $rate == "yes") {

								echo " | <input type='checkbox' name ='store[]' value='" . $str['bunit_field'] . "' id='chk_$counter' onclick=checkField('$counter')> <span class='chk_$counter'>Remove</span>";
							}

							$counter++;
						}
					}
					echo "</td>";
				}

				echo "
							<td>
								<select name='action' style='display:none;' onchange=removeOutlet(this.value,'$empId','$recordNo')>
									<option value=''>Proceed To</option>
									<option value='Remove'>Remove</option>
								</select>
							</td>
						";
				?>
			</tbody>
		</table>
		<?php
	} else if ($_GET['request'] == "uploadClearanceforRemoveOutlet") {

		$empId = $_POST['empId'];
		$recordNo = $_POST['recordNo'];
		$newCHK = explode("*", $_POST['newChk']);

		$ctr = 0;
		$previousStore = "";
		$sqlBU = mysql_query("SELECT bunit_name, bunit_field FROM `locate_promo_business_unit` WHERE status = 'active'") or die(mysql_error());
		while ($rowBU = mysql_fetch_array($sqlBU)) {

			$chk = mysql_query("SELECT promo_id FROM `promo_record` WHERE " . $rowBU['bunit_field'] . " = 'T' AND emp_id = '$empId' AND record_no = '$recordNo'") or die(mysql_error());
			if (mysql_num_rows($chk) > 0) {

				$ctr++;
				if ($ctr == 1) {

					$previousStore = $rowBU['bunit_name'];
				} else {

					$previousStore .= ", " . $rowBU['bunit_name'];
				}
			}
		}

		$counter = 0;
		$iremoveOutlet = "";
		for ($i = 0; $i < sizeof($newCHK) - 1; $i++) {

			$sql = mysql_query("SELECT bunit_name, bunit_clearance FROM `locate_promo_business_unit` WHERE bunit_field = '" . $newCHK[$i] . "'") or die(mysql_error());
			$row = mysql_fetch_array($sql);

			$bunitName = $row['bunit_name'];
			$bunitClearance = $row['bunit_clearance'];
			$counter++;

			if ($counter == 1) {

				$iremoveOutlet = $row['bunit_name'];
			} else {

				$iremoveOutlet .= ", " . $row['bunit_name'];
			}

		?>
			<input type="hidden" name="bunitField[]" value="<?php echo $newCHK[$i]; ?>">
			<input type="hidden" name="clearance[]" value="<?php echo $bunitClearance; ?>">
			<input type="hidden" name="chk_store[]" value="<?php echo $bunitName; ?>">
			<div class="row">
				<div class="col-md-12">
					<b><?php echo "Clearance ($bunitName)"; ?></b><br>
					<img id="photo<?php echo $bunitClearance; ?>" class='preview img-responsive' /><br>
					<input type='file' name='<?php echo $bunitClearance; ?>' id='<?php echo $bunitClearance; ?>' class='btn btn-default clearance_<?php echo $counter; ?>' onchange='readURL(this,"<?php echo $bunitClearance; ?>");'>
					<input type='button' name='clear<?php echo $bunitClearance; ?>' id='clear<?php echo $bunitClearance; ?>' style='display:none' class='btn btn-default' value='Clear' onclick="clears('<?php echo $bunitClearance; ?>','photo<?php echo $bunitClearance; ?>','clear<?php echo $bunitClearance; ?>')">
					<input type='button' id='<?php echo $bunitClearance; ?>_change' style='display:none;' class='btn btn-primary btn-sm' value='Change Clearance?' onclick='changePhoto("Clearance","<?php echo $bunitClearance; ?>","<?php echo $bunitClearance; ?>_change")'>
				</div>
			</div><br>
		<?php
		}

		?>
		<input type="hidden" name="counter" value="<?php echo $counter; ?>">
		<input type="hidden" name="empId" id="empId" value="<?php echo $empId; ?>">
		<input type="hidden" name="recordNo" id="recordNo" value="<?php echo $recordNo; ?>">
		<input type="hidden" name="iremoveOutlet" value="<?php echo $iremoveOutlet; ?>">
		<input type="hidden" name="previousStore" value="<?php echo $previousStore; ?>">
	<?php
	} else if ($_GET['request'] == "savingCleranceforRemoveOutlet") {

		$empId = $_POST['empId'];
		$recordNo = $_POST['recordNo'];
		$clearance = $_POST['clearance'];
		$bunitField = $_POST['bunitField'];
		$iremoveOutlet = $_POST['iremoveOutlet'];
		$previousStore = $_POST['previousStore'];
		$effectiveOn = date("Y-m-d");
		$chk_store_values = $_POST['chk_store'];

		$clearanceFlag = "";

		foreach ($clearance as $key => $value) {

			$destination_path = "";
			if (!empty($_FILES[$value]['name'])) {

				$image		= addslashes(file_get_contents($_FILES[$value]['tmp_name']));
				$image_name	= addslashes($_FILES[$value]['name']);
				$array 	= explode(".", $image_name);

				$filename 	= $empId . "=" . date('Y-m-d') . "=" . $value . "=" . date('H-i-s-A') . "." . end($array);
				$destination_path	= "../document/clearance/" . $filename;

				if (move_uploaded_file($_FILES[$value]['tmp_name'], $destination_path)) {

					mysql_query("UPDATE promo_record SET $value = '" . mysql_real_escape_string($destination_path) . "' WHERE emp_id = '" . $empId . "' AND record_no = '" . $recordNo . "'") or die(mysql_error());
					$clearanceFlag = "true";
				}
			}
		}

		$name = mysql_real_escape_string($nq->getPromoName($_POST['empId']));

		if ($clearanceFlag == 'true') {

			// update secure_clearance_promo_details and secure_clearance_promo
			$updateStatus = mysql_query("SELECT scpr_id FROM secure_clearance_promo WHERE emp_id = '" . $empId . "' AND status = 'Pending' ORDER BY scpr_id DESC LIMIT 1");
			$secure = mysql_fetch_array($updateStatus);
			foreach ($chk_store_values as $clr_store) {

				$updateClearance_promoDetails = mysql_query("UPDATE secure_clearance_promo_details 
						SET clearance_status = 'Completed', date_cleared = '" . date("Y-m-d") . "' 
						WHERE scpr_id = '" . $secure['scpr_id'] . "' AND store = '" . $clr_store . "' AND clearance_status='Pending'");
			}
			// check if all store clearance are completed
			$chk_clearanceStore = mysql_query("SELECT scpr_id FROM secure_clearance_promo_details 
					WHERE scpr_id = '" . $secure['scpr_id'] . "' AND emp_id = '" . $empId . "' AND clearance_status='Pending'");
			if (mysql_num_rows($chk_clearanceStore) == 0) {

				$updateClearance_promo = mysql_query("UPDATE secure_clearance_promo SET status = 'Completed' 
						WHERE scpr_id = '" . $secure['scpr_id'] . "'");
			}

			$activity = "Uploaded the scanned Clearance for Remove Outlet of " . $name . " Record No." . $recordNo;
			$nq->savelogs($activity, $date, $time, $_SESSION['emp_id'], $_SESSION['username']);

			$sql = mysql_query(
				"SELECT
						*
					 FROM
						employee3 
					 WHERE
						emp_id = '" . $empId . "'"
			) or die(mysql_error());
			$old_data = mysql_fetch_array($sql);
			// insert the old contrct to the employment record table
			mysql_query(
				"INSERT
					INTO
				 employmentrecord_
					(
						emp_id,
						names,
						company_code,
						bunit_code,
						dept_code,
						section_code,
						sub_section_code,
						unit_code,
						barcodeId,
						bioMetricId,
						payroll_no,
						startdate,
						eocdate,
						emp_type,
						position,
						positionlevel,
						current_status,
						lodging,
						pos_desc,
						remarks,
						epas_code,
						contract,
						permit,
						clearance,
						comments,
						date_updated,
						updatedby,
						duration,
						emp_no,
						emp_pins
					) VALUES (
						'" . $empId . "',
						'" . $old_data['name'] . "',
						'" . $old_data['company_code'] . "',
						'" . $old_data['bunit_code'] . "',
						'" . $old_data['dept_code'] . "',
						'" . $old_data['section_code'] . "',
						'" . $old_data['sub_section_code'] . "',
						'" . $old_data['unit_code'] . "',
						'" . $old_data['barcodeId'] . "',
						'" . $old_data['bioMetricId'] . "',
						'" . $old_data['payroll_no'] . "',
						'" . $old_data['startdate'] . "',
						'" . $old_data['eocdate'] . "',
						'" . $old_data['emp_type'] . "',
						'" . $old_data['position'] . "',
						'" . $old_data['positionlevel'] . "',
						'End of Contract',
						'" . $old_data['lodging'] . "',
						'" . $old_data['position_desc'] . "',
						'" . mysql_real_escape_string($old_data['remarks']) . "',
						'" . $old_data['epas_code'] . "',
						'" . $old_data['contract'] . "',
						'" . $old_data['permit'] . "',
						'" . $old_data['clearance'] . "',
						'" . $old_data['comments'] . "',
						'" . $old_data['date_updated'] . "',
						'" . $old_data['updated_by'] . "',
						'" . $old_data['duration'] . "',
						'" . $old_data['emp_no'] . "',
						'" . $old_data['emp_pins'] . "'
					)"
			) or die(mysql_error());
			$sql = mysql_query(
				"SELECT
						record_no
					  FROM
						employmentrecord_
					  WHERE
						emp_id = '" . $empId . "'
					  ORDER BY 
						record_no DESC"
			) or die(mysql_error());
			$new_rno = mysql_fetch_array($sql);
			// appraisal details
			$sql = mysql_query(
				"SELECT 
						record_no
					 FROM
						appraisal_details
					 WHERE
						record_no = '" . $old_data['record_no'] . "'
						and emp_id = '" . $empId . "'"
			) or die(mysql_error());

			$c_appdetails = mysql_num_rows($sql);
			if ($c_appdetails > 0) {
				foreach ($chk_store_values as $clr_store) {
					mysql_query(
						"UPDATE appraisal_details
					 	SET record_no = '" . $new_rno['record_no'] . "' 
						WHERE record_no = '" . $old_data['record_no'] . "'
					 	AND emp_id = '" . $empId . "'
					 	AND store = '" . $clr_store . "'"
					) or die(mysql_error());
				}
			}
			// witness
			$sql = mysql_query(
				"SELECT
						rec_no
					 FROM
						employment_witness
					 WHERE
						rec_no = '" . $old_data['record_no'] . "'"
			) or die(mysql_error());
			$c_empwitness = mysql_num_rows($sql);
			if ($c_empwitness > 0) {
				mysql_query(
					"UPDATE
						employment_witness
					 SET
						rec_no = '" . $new_rno['record_no'] . "'
					 WHERE
						rec_no = '" . $old_data['record_no'] . "'"
				) or die(mysql_error());
			}
			$sql2 = mysql_query(
				"SELECT
						*
					 FROM
						promo_record 
					 WHERE
						emp_id = '" . $empId . "'"
			) or die(mysql_error());
			$old_promo_data = mysql_fetch_array($sql2);

			// insert the old contrct to the promo_history_record table
			mysql_query(
				"INSERT
					INTO
				 promo_history_record
					(
						emp_id,
						promo_company,
						promo_department,
						company_duration,
						al_tag,
						al_tal,
						icm,
						pm,
						abenson_tag,
						abenson_icm,
						al_tub,
						alta_citta,
						fr_panglao,
						fr_panglao_epascode,
						fr_panglao_contract,
						fr_panglao_permit,
						fr_panglao_clearance,
						fr_panglao_intro,
						fr_tubigon,
						fr_tubigon_epascode,
						fr_tubigon_contract,
						fr_tubigon_permit,
						fr_tubigon_clearance,
						fr_tubigon_intro,
						al_panglao,
						al_panglao_epascode,
						al_panglao_contract,
						al_panglao_permit,
						al_panglao_clearance,
						al_panglao_intro,
						alta_epascode,
						alta_contract,
						alta_permit,
						alta_clearance,
						alta_intro,
						promo_type,
						record_no,
						asc_epascode,
						tal_epascode,
						icm_epascode,
						pm_epascode,
						absna_epascode,
						absni_epascode,
						cdc_epascode,
						berama_epascode,
						tub_epascode,
						asc_contract,
						tal_contract,
						icm_contract,
						pm_contract,
						absna_contract,
						absni_contract,
						cdc_contract,
						berama_contract,
						tub_contract,
						asc_permit,
						tal_permit,
						icm_permit,
						pm_permit,
						absna_permit,
						absni_permit,
						cdc_permit,
						berama_permit,
						tub_permit,
						asc_clearance,
						tal_clearance,
						icm_clearance,
						pm_clearance,
						absna_clearance,
						absni_clearance,
						cdc_clearance,
						berama_clearance,
						tub_clearance,
						asc_intro,
						tal_intro,
						icm_intro,
						pm_intro,
						absna_intro,
						absni_intro,
						cdc_intro,
						berama_intro,
						tub_intro,
						type,
						epas,
						transferOn,
						addedoutlet,
						hr_location
					) VALUES (
						'" . $empId . "',
						'" . mysql_real_escape_string($old_promo_data['promo_company']) . "',
						'" . $old_promo_data['promo_department'] . "',
						'" . $old_promo_data['company_duration'] . "',
						'" . $old_promo_data['al_tag'] . "',
						'" . $old_promo_data['al_tal'] . "',
						'" . $old_promo_data['icm'] . "',
						'" . $old_promo_data['pm'] . "',
						'" . $old_promo_data['abenson_tag'] . "',
						'" . $old_promo_data['abenson_icm'] . "',
						'" . $old_promo_data['al_tub'] . "',
						'" . $old_promo_data['alta_citta'] . "',
						'" . $old_promo_data['fr_panglao'] . "',
						'" . $old_promo_data['fr_panglao_epascode'] . "',
						'" . $old_promo_data['fr_panglao_contract'] . "',
						'" . $old_promo_data['fr_panglao_permit'] . "',
						'" . $old_promo_data['fr_panglao_clearance'] . "',
						'" . $old_promo_data['fr_panglao_intro'] . "',
						'" . $old_promo_data['fr_tubigon'] . "',
						'" . $old_promo_data['fr_tubigon_epascode'] . "',
						'" . $old_promo_data['fr_tubigon_contract'] . "',
						'" . $old_promo_data['fr_tubigon_permit'] . "',
						'" . $old_promo_data['fr_tubigon_clearance'] . "',
						'" . $old_promo_data['fr_tubigon_intro'] . "',
						'" . $old_promo_data['al_panglao'] . "',
						'" . $old_promo_data['al_panglao_epascode'] . "',
						'" . $old_promo_data['al_panglao_contract'] . "',
						'" . $old_promo_data['al_panglao_permit'] . "',
						'" . $old_promo_data['al_panglao_clearance'] . "',
						'" . $old_promo_data['al_panglao_intro'] . "',
						'" . $old_promo_data['alta_epascode'] . "',
						'" . $old_promo_data['alta_contract'] . "',
						'" . $old_promo_data['alta_permit'] . "',
						'" . $old_promo_data['alta_clearance'] . "',
						'" . $old_promo_data['alta_intro'] . "',
						'" . $old_promo_data['promo_type'] . "',
						'" . $new_rno['record_no'] . "',
						'" . $old_promo_data['asc_epascode'] . "',
						'" . $old_promo_data['tal_epascode'] . "',
						'" . $old_promo_data['icm_epascode'] . "',
						'" . $old_promo_data['pm_epascode'] . "',
						'" . $old_promo_data['absna_epascode'] . "',
						'" . $old_promo_data['absni_epascode'] . "',
						'" . $old_promo_data['cdc_epascode'] . "',
						'" . $old_promo_data['berama_epascode'] . "',
						'" . $old_promo_data['tub_epascode'] . "',
						'" . $old_promo_data['asc_contract'] . "',
						'" . $old_promo_data['tal_contract'] . "',
						'" . $old_promo_data['icm_contract'] . "',
						'" . $old_promo_data['pm_contract'] . "',
						'" . $old_promo_data['absna_contract'] . "',
						'" . $old_promo_data['absni_contract'] . "',
						'" . $old_promo_data['cdc_contract'] . "',
						'" . $old_promo_data['berama_contract'] . "',
						'" . $old_promo_data['tub_contract'] . "',
						'" . $old_promo_data['asc_permit'] . "',
						'" . $old_promo_data['tal_permit'] . "',
						'" . $old_promo_data['icm_permit'] . "',
						'" . $old_promo_data['pm_permit'] . "',
						'" . $old_promo_data['absna_permit'] . "',
						'" . $old_promo_data['absni_permit'] . "',
						'" . $old_promo_data['cdc_permit'] . "',
						'" . $old_promo_data['berama_permit'] . "',
						'" . $old_promo_data['tub_permit'] . "',
						'" . $old_promo_data['asc_clearance'] . "',
						'" . $old_promo_data['tal_clearance'] . "',
						'" . $old_promo_data['icm_clearance'] . "',
						'" . $old_promo_data['pm_clearance'] . "',
						'" . $old_promo_data['absna_clearance'] . "',
						'" . $old_promo_data['absni_clearance'] . "',
						'" . $old_promo_data['cdc_clearance'] . "',
						'" . $old_promo_data['berama_clearance'] . "',
						'" . $old_promo_data['tub_clearance'] . "',
						'" . $old_promo_data['asc_intro'] . "',
						'" . $old_promo_data['tal_intro'] . "',
						'" . $old_promo_data['icm_intro'] . "',
						'" . $old_promo_data['pm_intro'] . "',
						'" . $old_promo_data['absna_intro'] . "',
						'" . $old_promo_data['absni_intro'] . "',
						'" . $old_promo_data['cdc_intro'] . "',
						'" . $old_promo_data['berama_intro'] . "',
						'" . $old_promo_data['tub_intro'] . "',
						'" . $old_promo_data['type'] . "',
						'" . $old_promo_data['epas'] . "',
						'" . $old_promo_data['transferOn'] . "',
						'" . $old_promo_data['addedoutlet'] . "',
						'" . $old_promo_data['hr_location'] . "'
					)"
			) or die(mysql_error());

			if ($updateClearance_promo) {

				foreach ($chk_store_values as $clr_store) {

					mysql_query("UPDATE secure_clearance_promo_details SET record_no = '" . $new_rno['record_no'] . "' 
						WHERE scpr_id = '" . $secure['scpr_id'] . "' AND store = '" . $clr_store . "'");
				}
				$removedStore = 0;
				$current_stat = 'Active';
				$sub_stat = '';
			} else {

				foreach ($chk_store_values as $clr_store) {

					mysql_query("UPDATE secure_clearance_promo_details SET record_no = '" . $new_rno['record_no'] . "' 
					WHERE scpr_id = '" . $secure['scpr_id'] . "'");
				}
				$removedStore = 1;
				$current_stat = $old_data['current_status'];
				$sub_stat = $old_data['sub_status'];
			}

			// insert employee3

			mysql_query(
				"INSERT INTO 
				`employee3`
					(
						`emp_id`, 
						`emp_no`, 
						`emp_pins`, 
						`barcodeId`, 
						`bioMetricId`, 
						`payroll_no`, 
						`name`, 
						`startdate`, 
						`eocdate`, 
						`emp_type`, 
						`current_status`,
						`sub_status`, 
						`duration`, 
						`positionlevel`, 
						`position`,  
						`lodging`, 
						`remarks`, 
						`date_added`, 
						`added_by`
					) VALUES (
						'" . $empId . "',
						'" . $old_data['emp_no'] . "',
						'" . $old_data['emp_pins'] . "',
						'" . $old_data['barcodeId'] . "',
						'" . $old_data['bioMetricId'] . "',
						'" . $old_data['payroll_no'] . "',
						'" . $old_data['name'] . "',
						'" . $effectiveOn . "',
						'" . $old_data['eocdate'] . "',
						'" . $old_data['emp_type'] . "',
						'" . $current_stat . "',
						'" . $sub_stat . "',
						'" . $old_data['duration'] . "',
						'" . $old_data['positionlevel'] . "',
						'" . $old_data['position'] . "',
						'" . $old_data['lodging'] . "',
						'',
						'" . $date . "',
						'" . $loginId . "'
					)
				"
			) or die(mysql_error());

			$recordNo = mysql_insert_id();

			//move epas of unremoved store to current contract
			if ($removedStore == 1) {

				$pending_store = mysql_query("SELECT store FROM secure_clearance_promo_details 
				WHERE scpr_id = '" . $secure['scpr_id'] . "' AND emp_id = '" . $empId . "' AND clearance_status='Pending'");
				if (mysql_num_rows($pending_store) > 0) {
					while ($store_nr = mysql_fetch_array($pending_store)) {
						mysql_query(
							"UPDATE appraisal_details
				 		SET record_no = '" . $recordNo . "' 
						WHERE record_no = '" . $old_data['record_no'] . "'
				 		AND emp_id = '" . $empId . "'
				 		AND store = '" . $store_nr['store'] . "'
						AND record_no = '" . $old_data['record_no'] . "'"
						);

						mysql_query(
							"UPDATE secure_clearance_promo_details 
						SET record_no = '" . $recordNo . "' 
						WHERE scpr_id = '" . $secure['scpr_id'] . "'
						AND store = '" . $store_nr['store'] . "'
						AND record_no = '" . $old_data['record_no'] . "'"
						);
					}
				}
			}

			// delete the old record in employee3
			mysql_query(
				"DELETE
					FROM
				 employee3
					WHERE
				 emp_id = '" . $empId . "' AND
				 record_no = '" . $old_data['record_no'] . "'"
			) or die(mysql_error());

			$updateField = "";
			$updateEpas = "";
			$updateContract = "";
			$updatePermit = "";
			$updateClearance = "";
			$updateIntro = "";
			$counter = 0;
			foreach ($bunitField as $fields => $field) {

				$counter++;

				$query = mysql_query("SELECT bunit_name, bunit_epascode, bunit_contract, bunit_permit, bunit_clearance, bunit_intro FROM `locate_promo_business_unit` WHERE bunit_field = '" . $field . "'") or die(mysql_error());
				$fetch = mysql_fetch_array($query);

				if ($counter == 1) {

					$currentStore = $fetch['bunit_name'];
					$updateField = "$field = ''";
					$updateEpas = $fetch['bunit_epascode'] . "=''";
					$updateContract = $fetch['bunit_contract'] . "=''";
					$updatePermit = $fetch['bunit_permit'] . "=''";
					$updateClearance = $fetch['bunit_clearance'] . "=''";
					$updateIntro = $fetch['bunit_intro'] . "=''";
				} else {

					$currentStore .= ", " . $fetch['bunit_name'];
					$updateField .= ", $field = ''";
					$updateEpas .= ", " . $fetch['bunit_epascode'] . "=''";
					$updateContract .= ", " . $fetch['bunit_contract'] . "=''";
					$updatePermit .= ", " . $fetch['bunit_permit'] . "=''";
					$updateClearance .= ", " . $fetch['bunit_clearance'] . "=''";
					$updateIntro .= ", " . $fetch['bunit_intro'] . "=''";
				}
			}

			$update = mysql_query("UPDATE `promo_record` SET `record_no`='$recordNo', $updateField, $updateEpas, $updateContract, $updatePermit, $updateClearance, $updateIntro, `transferOn`='$effectiveOn' WHERE `emp_id` = '$empId'") or die(mysql_error());

			$ctr = 0;
			$currentStore = "";
			$promo_type = "";
			$sqlBU = mysql_query("SELECT bunit_name, bunit_field FROM `locate_promo_business_unit` WHERE status = 'active'") or die(mysql_error());
			while ($rowBU = mysql_fetch_array($sqlBU)) {

				$chk = mysql_query("SELECT promo_id FROM `promo_record` WHERE " . $rowBU['bunit_field'] . " = 'T' AND emp_id = '$empId' AND record_no = '$recordNo'") or die(mysql_error());
				if (mysql_num_rows($chk) > 0) {

					$ctr++;
					if ($ctr == 1) {

						$field_store = $rowBU['bunit_field'];
						$currentStore = $rowBU['bunit_name'];
						$promo_type = "STATION";
					} else {

						$field_store .= "|" . $rowBU['bunit_field'];
						$currentStore .= ", " . $rowBU['bunit_name'];
						$promo_type = "ROVING";
					}
				}
			}

			//update for timekeeping table
			$store_tk = explode('|', $field_store);

			foreach ($store_tk as $field) {

				if ($field == 'al_tal') {
					$talibon = 'true';
				} else if ($field == 'al_tub') {
					$tubigon = 'true';
				} else if ($field == 'colm' || $field == 'colc') {
					$colon = 'true';
				} else {
					$corporate = 'true';
				}
			}

			$get_oldCutoff = mysql_query("SELECT * FROM timekeeping.promo_sched_emp WHERE recordNo = '" . $old_data['record_no'] . "' AND empId = '" . $empId . "'");
			$cutoffNum =  mysql_num_rows($get_oldCutoff);

			if ($cutoffNum > 0) {

				mysql_query("UPDATE timekeeping.promo_sched_emp SET `recordNo`='" . $new_rno['record_no'] . "'
	   					WHERE `recordNo`='" . $old_data['record_no'] . "'");
			} else {

				mysql_query("INSERT INTO timekeeping.promo_sched_emp (statCut, recordNo, empId, date_setup)
						VALUES ('10', '" . $new_rno['record_no'] . "', '" . $empId . "', '" . $date . "')");
			}

			$get_statCut = mysql_query("SELECT * FROM timekeeping.promo_sched_emp WHERE `recordNo`='" . $new_rno['record_no'] . "'");
			$statCut = mysql_fetch_array($get_statCut);
			$insert_newSC = mysql_query("INSERT INTO timekeeping.promo_sched_emp (statCut, recordNo, empId, date_setup)
					VALUES ('" . $statCut['statCut'] . "', '" . $recordNo . "', '" . $empId . "', '" . $date . "')");

			if ($talibon == 'true') {

				include("config_talibon_timekeeping.php");
				$get_oldCutoff = mysql_query("SELECT * FROM promo_sched_emp WHERE recordNo = '" . $old_data['record_no'] . "' AND empId = '" . $empId . "'");
				$cutoffNum =  mysql_num_rows($get_oldCutoff);

				if ($cutoffNum > 0) {

					mysql_query("UPDATE promo_sched_emp SET `recordNo`='" . $new_rno['record_no'] . "'
								   WHERE `recordNo`='" . $old_data['record_no'] . "'");
				} else {

					mysql_query("INSERT INTO promo_sched_emp (statCut, recordNo, empId, date_setup)
								VALUES ('10', '" . $new_rno['record_no'] . "', '" . $empId . "', '" . date('Y-m-d') . "')");
				}

				$get_statCut = mysql_query("SELECT * FROM promo_sched_emp WHERE `recordNo`='" . $new_rno['record_no'] . "'");
				$statCut = mysql_fetch_array($get_statCut);
				$insert_newSC = mysql_query("INSERT INTO promo_sched_emp (statCut, recordNo, empId, date_setup)
						VALUES ('" . $statCut['statCut'] . "', '" . $recordNo . "', '" . $empId . "', '" . $date . "')");

				mysql_close($con);
			}

			if ($tubigon == 'true') {

				include("config_tubigon_timekeeping.php");
				$get_oldCutoff = mysql_query("SELECT * FROM promo_sched_emp WHERE recordNo = '" . $old_data['record_no'] . "' AND empId = '" . $empId . "'");
				$cutoffNum =  mysql_num_rows($get_oldCutoff);

				if ($cutoffNum > 0) {

					mysql_query("UPDATE promo_sched_emp SET `recordNo`='" . $new_rno['record_no'] . "'
							WHERE `recordNo`='" . $old_data['record_no'] . "'");
				} else {

					mysql_query("INSERT INTO promo_sched_emp (statCut, recordNo, empId, date_setup)
							VALUES ('10', '" . $new_rno['record_no'] . "', '" . $empId . "', '" . date('Y-m-d') . "')");
				}

				$get_statCut = mysql_query("SELECT * FROM promo_sched_emp WHERE `recordNo`='" . $new_rno['record_no'] . "'");
				$statCut = mysql_fetch_array($get_statCut);
				$insert_newSC = mysql_query("INSERT INTO promo_sched_emp (statCut, recordNo, empId, date_setup)
						VALUES ('" . $statCut['statCut'] . "', '" . $recordNo . "', '" . $empId . "', '" . $date . "')");
				mysql_close($con);
			}

			$remarks = "Removed Outlet - $iremoveOutlet";

			$insert = mysql_query("INSERT INTO `change_outlet_record`
											(`emp_id`, `changefrom`, `changeto`, `effectiveon`) 
										VALUES 
											('$empId','$previousStore','$currentStore','$effectiveOn')") or die(mysql_error());

			mysql_query("UPDATE promo_record SET promo_type = '$promo_type' WHERE emp_id = '$empId' AND record_no = '$recordNo'") or die(mysql_error());
			mysql_query("UPDATE `employee3` SET `remarks`='$remarks' WHERE emp_id ='$empId' AND record_no = '$recordNo'") or die(mysql_error());
			die("success");
		}
	} else if ($_GET['request'] == "changePassword") {

		$query = mysql_query("SELECT password FROM users WHERE username = '" . $_SESSION['username'] . "' AND emp_id = '" . $_SESSION['emp_id'] . "'") or die(mysql_error());
		$row = mysql_fetch_array($query);
		$old = $row['password'];

		if ($old == md5($_POST['currentPassword'])) {

			$newpass = md5($_POST['newPassword']);
			$savepass = mysql_query("UPDATE users SET password = '$newpass', c_pass = 1 WHERE username = '" . $_SESSION['username'] . "' AND emp_id = '" . $_SESSION['emp_id'] . "'") or die(mysql_error());
			if ($savepass) {

				die("success");
			}
		} else {

			die("failure");
		}
	} else if ($_GET['request'] == "changeUsername") {

		$newUsername = $_POST['newUsername'];

		$query = mysql_query("SELECT user_no FROM users WHERE username = '" . $_POST['newUsername'] . "' AND emp_id = '" . $_SESSION['emp_id'] . "'") or die(mysql_error());
		$userno = $nq->getOneField('user_no', 'users', "username = '" . $_SESSION['username'] . "' AND emp_id ='" . $_SESSION['emp_id'] . "'");

		if (mysql_num_rows($query) > 0) {

			die("Username is already taken. Please use another username.");
		} else {

			$saveuser = mysql_query("UPDATE users SET username = '$newUsername' WHERE user_no = '$userno'") or die(mysql_error());
			if ($saveuser) {

				die("success");
			}
		}
	} else if ($_GET['request'] == "newPromo") {

		$lastmonth = date("Y-m-d", strtotime("-1 month"));
		$date = date('Y-m-d');

		$sql = mysql_query("SELECT COUNT(employee3.emp_id) AS num FROM employee3, promo_record WHERE employee3.emp_id = promo_record.emp_id AND $promoEmpType AND tag_as = 'new' AND hr_location = '$hrCode' AND startdate BETWEEN '$lastmonth' AND '$date'") or die(mysql_error());
		$row = mysql_fetch_array($sql);

		if ($row['num'] > 0) {

			echo "<a href='?p=newPromo&&module=Promo' style='color:white;'>" . $row['num'] . "</a>";
		} else {

			echo "0";
		}
	} else if ($_GET['request'] == "birthdayToday") {

		$month_day = date('m-d');

		$sql = mysql_query("SELECT COUNT(app_id) AS num FROM employee3, applicant, promo_record WHERE employee3.emp_id = applicant.app_id AND employee3.emp_id = promo_record.emp_id AND $promoEmpType AND hr_location = '$hrCode' AND birthdate LIKE '%$month_day' AND current_status = 'active'") or die(mysql_error());
		$row = mysql_fetch_array($sql);

		if ($row['num'] > 0) {

			echo "<a href='?p=birthdayToday&&module=Promo' style='color:white;'>" . $row['num'] . "</a>";
		} else {

			echo "0";
		}
	} else if ($_GET['request'] == "activePromo") {

		$sql = mysql_query("SELECT COUNT(employee3.emp_id) AS num FROM employee3 INNER JOIN promo_record ON employee3.emp_id=promo_record.emp_id
				 WHERE current_status = 'Active' AND promo_record.hr_location = '$hrCode' AND $promoEmpType") or die(mysql_error());
		$row = mysql_fetch_array($sql);

		if ($row['num'] > 0) {

			echo "<a href='?p=masterfile&&module=Promo' style='color:white;'>" . $row['num'] . "</a>";
		} else {

			echo "0";
		}
	} else if ($_GET['request'] == "eocToday") {

		$today = date('Y-m-d');

		$sql = mysql_query("SELECT COUNT(employee3.emp_id) AS num FROM employee3, promo_record WHERE employee3.emp_id = promo_record.emp_id AND $promoEmpType AND hr_location = '$hrCode' AND (employee3.current_status = 'Active' OR employee3.current_status = 'End of Contract') AND eocdate LIKE '%$today'") or die(mysql_error());
		$row = mysql_fetch_array($sql);

		if ($row['num'] > 0) {

			echo "<a href='?p=eocList&&module=Contract&&filterBU=&&filterDate=today&&filterMonth=' style='color:white;'>" . $row['num'] . "</a>";
		} else {

			echo "0";
		}
	} else if ($_GET['request'] == "failedEpas") {

		$plus3 	= date('Y-m-d', strtotime('+5 month')); //date 5 month after the current date

		$sql = mysql_query("SELECT COUNT(employee3.emp_id) AS num FROM employee3, appraisal_details, promo_record WHERE employee3.emp_id = appraisal_details.emp_id AND employee3.record_no = appraisal_details.record_no AND employee3.emp_id = promo_record.emp_id 
								AND appraisal_details.numrate < 85 AND $promoEmpType AND hr_location = '$hrCode' AND (current_status = 'Active'or current_status = 'End of Contract') AND eocdate <= '$plus3'") or die(mysql_error());
		$row = mysql_fetch_array($sql);

		if ($row['num'] > 0) {

			echo "<a href='?p=failedEpas&&module=Contract' style='color:white;'>" . $row['num'] . "</a>";
		} else {

			echo "0";
		}
	} else if ($_GET['request'] == "transferOutlet") {

		$empId = $_POST['empId'];
		$recordNo = $nq->getRec($empId);

	?>
		<hr>
		<table class="table table-bordered table-hover">
			<thead>
				<?php

				$bunit = mysql_query("SELECT bunit_acronym FROM `locate_promo_business_unit` WHERE appraisal_status = 'active' AND hrd_location = '$hrCode'") or die(mysql_error());
				while ($str = mysql_fetch_array($bunit)) {

					echo "<th align='center'>" . $str['bunit_acronym'] . "</th>";
				}
				?>
				<th>ACTION</th>
			</thead>
			<tbody>
				<?php

				$counter = 0;
				$bunit = mysql_query("SELECT bunit_field, bunit_name FROM `locate_promo_business_unit` WHERE appraisal_status = 'active' AND hrd_location = '$hrCode'") or die(mysql_error());
				while ($str = mysql_fetch_array($bunit)) {

					echo "<td>";
					$promo = mysql_query("SELECT promo_id FROM `promo_record` WHERE `" . $str['bunit_field'] . "` = 'T' AND emp_id = '" . $empId . "'") or die(mysql_error());
					if (mysql_num_rows($promo) > 0) {

						$storeApp = mysql_query("SELECT details_id, numrate, descrate, raterSO, rateeSO FROM `appraisal_details` WHERE emp_id = '$empId' AND record_no = '$recordNo' AND store = '" . $str['bunit_name'] . "'") or die(mysql_error());
						$epas = mysql_fetch_array($storeApp);

						$raterSO = $epas['raterSO'];
						$rateeSO = $epas['rateeSO'];
						$numrate = $epas['numrate'];

						if ($numrate != "") {

							if ($raterSO == 1 && $rateeSO == 1) {

								$rate = "yes";
								$label = "btn btn-success btn-xs btn-flat";
							} else {

								$rate = "no";
								$label = "btn btn-warning btn-xs btn-flat";
							}

							if ($numrate == 100) {
								$grade = 'pass';
								$label2 = "btn btn-success btn-flat btn-xs";
							} else if ($numrate >= 90 && $numrate <= 99.99) {
								$grade = 'pass';
								$label2 = "btn btn-primary btn-flat btn-xs";
							} else if ($numrate >= 85 && $numrate <= 89.99) {
								$grade = 'pass';
								$label2 = "btn btn-info btn-flat btn-xs";
							} else if ($numrate >= 70 && $numrate <= 84.99) {
								$grade = 'failed';
								$label2 = "btn btn-danger btn-flat btn-xs";
							} else if ($numrate >= 0 && $numrate <= 69.99) {
								$grade = 'failed';
								$label2 = "btn btn-danger btn-flat btn-xs";
							} else {
								$label2 = "label label-danger";
							}

							echo "<a href='javascript:void(0)' onclick=viewdetails('$epas[details_id]') title='Click to View Appraisal Details'> <span class='$label2'>$numrate</span></a> <span class='$label'>$rate</span>";

							if ($grade == "pass" && $rate == "yes") {

								echo " | <input type='checkbox' name ='store[]' value='" . $str['bunit_field'] . "' id='chk_$counter' onclick=checkField('$counter')> <span class='chk_$counter'>Transfer</span>";
							}

							$counter++;
						}
					}
					echo "</td>";
				}

				echo "
							<td>
								<select name='action' style='display:none;' onchange=transferOutlet(this.value,'$empId','$recordNo')>
									<option value=''>Proceed To</option>
									<option value='Transfer'>Transfer</option>
								</select>
							</td>
						";
				?>
			</tbody>
		</table>
		<?php
	} else if ($_GET['request'] == "uploadClearanceforTransferOutlet") {

		$empId = $_POST['empId'];
		$recordNo = $_POST['recordNo'];
		$newCHK = explode("*", $_POST['newChk']);

		$ctr = 0;
		$previousStore = "";
		$sqlBU = mysql_query("SELECT bunit_name, bunit_field FROM `locate_promo_business_unit` WHERE status = 'active'") or die(mysql_error());
		while ($rowBU = mysql_fetch_array($sqlBU)) {

			$chk = mysql_query("SELECT promo_id FROM `promo_record` WHERE " . $rowBU['bunit_field'] . " = 'T' AND emp_id = '$empId' AND record_no = '$recordNo'") or die(mysql_error());
			if (mysql_num_rows($chk) > 0) {

				$ctr++;
				if ($ctr == 1) {

					$previousStore = $rowBU['bunit_name'];
				} else {

					$previousStore .= ", " . $rowBU['bunit_name'];
				}
			}
		}

		$counter = 0;
		for ($i = 0; $i < sizeof($newCHK) - 1; $i++) {

			$sql = mysql_query("SELECT bunit_name, bunit_clearance FROM `locate_promo_business_unit` WHERE bunit_field = '" . $newCHK[$i] . "'") or die(mysql_error());
			$row = mysql_fetch_array($sql);

			$bunitName = $row['bunit_name'];
			$bunitClearance = $row['bunit_clearance'];
			$counter++;


		?>
			<input type="hidden" name="bunitField[]" value="<?php echo $newCHK[$i]; ?>">
			<input type="hidden" name="clearance[]" value="<?php echo $bunitClearance; ?>">
			<div class="row">
				<div class="col-md-12">
					<b><?php echo "Clearance ($bunitName)"; ?></b><br>
					<img id="photo<?php echo $bunitClearance; ?>" class='preview img-responsive' /><br>
					<input type='file' name='<?php echo $bunitClearance; ?>' id='<?php echo $bunitClearance; ?>' class='btn btn-default clearance_<?php echo $counter; ?>' onchange='readURL(this,"<?php echo $bunitClearance; ?>");'>
					<input type='button' name='clear<?php echo $bunitClearance; ?>' id='clear<?php echo $bunitClearance; ?>' style='display:none' class='btn btn-default' value='Clear' onclick="clears('<?php echo $bunitClearance; ?>','photo<?php echo $bunitClearance; ?>','clear<?php echo $bunitClearance; ?>')">
					<input type='button' id='<?php echo $bunitClearance; ?>_change' style='display:none;' class='btn btn-primary btn-sm' value='Change Clearance?' onclick='changePhoto("Clearance","<?php echo $bunitClearance; ?>","<?php echo $bunitClearance; ?>_change")'>
				</div>
			</div><br>
		<?php
		} ?>

		<input type="hidden" name="counter" value="<?php echo $counter; ?>">
		<input type="hidden" name="empId" id="empId" value="<?php echo $empId; ?>">
		<input type="hidden" name="recordNo" id="recordNo" value="<?php echo $recordNo; ?>">
		<input type="hidden" name="previousStore" value="<?php echo $previousStore; ?>">
	<?php
	} else if ($_GET['request'] == "savingCleranceforTransferOutlet") {

		$empId = $_POST['empId'];
		$recordNo = $_POST['recordNo'];
		$clearance = $_POST['clearance'];

		$clearanceFlag = "";
		foreach ($clearance as $key => $value) {

			$destination_path = "";
			if (!empty($_FILES[$value]['name'])) {

				$image		= addslashes(file_get_contents($_FILES[$value]['tmp_name']));
				$image_name	= addslashes($_FILES[$value]['name']);
				$array 	= explode(".", $image_name);

				$filename 	= $empId . "=" . date('Y-m-d') . "=" . $value . "=" . date('H-i-s-A') . "." . end($array);
				$destination_path	= "../document/clearance/" . $filename;

				if (move_uploaded_file($_FILES[$value]['tmp_name'], $destination_path)) {

					mysql_query("UPDATE promo_record SET $value = '" . mysql_real_escape_string($destination_path) . "' WHERE emp_id = '" . $empId . "' AND record_no = '" . $recordNo . "'") or die(mysql_error());
					$clearanceFlag = "true";
				}
			}
		}

		$name = mysql_real_escape_string($nq->getPromoName($_POST['empId']));

		if ($clearanceFlag == 'true') {

			$activity = "Uploaded the scanned Clearance for Transfer Outlet of " . $name . " Record No." . $recordNo;
			$nq->savelogs($activity, $date, $time, $_SESSION['emp_id'], $_SESSION['username']);

			die("success");
		}
	} else if ($_GET['request'] == "submitTransferOutlet") {

		$empId = $_POST['empId'];
		$recordNo = $_POST['recordNo'];
		$bunit = explode("||", $_POST['store']);
		$previousStore = $_POST['previousStore'];
		$effectiveOn = date("Y-m-d", strtotime($_POST['effectiveOn']));
		$remarks = $_POST['remarks'];

		$currentStore = "";
		$updateField = "";
		$updateEpas = "";
		$updateContract = "";
		$updatePermit = "";
		$updateClearance = "";
		$updateIntro = "";
		$counter = 0;

		// get current store
		for ($i = 0; $i < sizeof($bunit) - 1; $i++) {

			$counter++;
			$store = explode("/", $bunit[$i]);
			$query = mysql_query("SELECT bunit_name, bunit_epascode, bunit_contract, bunit_permit, bunit_clearance, bunit_intro FROM `locate_promo_business_unit` WHERE bunit_id = '" . $store[0] . "'") or die(mysql_error());
			$fetch = mysql_fetch_array($query);

			if ($counter == 1) {

				$currentStore .= $fetch['bunit_name'];
				$updateField = "`$store[1]`='T'";
			} else {

				$currentStore .= ", " . $fetch['bunit_name'];
				$updateField .= ", `$store[1]`='T'";
			}
		}

		$ctr = 0;
		$query = mysql_query("SELECT bunit_name, bunit_epascode, bunit_contract, bunit_permit, bunit_clearance, bunit_intro FROM `locate_promo_business_unit`") or die(mysql_error());
		while ($fetch = mysql_fetch_array($query)) {

			$ctr++;
			if ($ctr == 1) {

				$updateEpas = $fetch['bunit_epascode'] . "=''";
				$updateContract = $fetch['bunit_contract'] . "=''";
				$updatePermit = $fetch['bunit_permit'] . "=''";
				$updateClearance = $fetch['bunit_clearance'] . "=''";
				$updateIntro = $fetch['bunit_intro'] . "=''";
			} else {

				$updateEpas .= ", " . $fetch['bunit_epascode'] . "=''";
				$updateContract .= ", " . $fetch['bunit_contract'] . "=''";
				$updatePermit .= ", " . $fetch['bunit_permit'] . "=''";
				$updateClearance .= ", " . $fetch['bunit_clearance'] . "=''";
				$updateIntro .= ", " . $fetch['bunit_intro'] . "=''";
			}
		}

		$sql = mysql_query(
			"SELECT
					*
				 FROM
					employee3 
				 WHERE
					emp_id = '" . $empId . "'"
		) or die(mysql_error());
		$old_data = mysql_fetch_array($sql);
		// insert the old contrct to the employment record table
		mysql_query(
			"INSERT
				INTO
			 employmentrecord_
				(
					emp_id,
					names,
					company_code,
					bunit_code,
					dept_code,
					section_code,
					sub_section_code,
					unit_code,
					barcodeId,
					bioMetricId,
					payroll_no,
					startdate,
					eocdate,
					emp_type,
					position,
					positionlevel,
					current_status,
					lodging,
					pos_desc,
					remarks,
					epas_code,
					contract,
					permit,
					clearance,
					comments,
					date_updated,
					updatedby,
					duration,
					emp_no,
					emp_pins
				) VALUES (
					'" . $empId . "',
					'" . $old_data['name'] . "',
					'" . $old_data['company_code'] . "',
					'" . $old_data['bunit_code'] . "',
					'" . $old_data['dept_code'] . "',
					'" . $old_data['section_code'] . "',
					'" . $old_data['sub_section_code'] . "',
					'" . $old_data['unit_code'] . "',
					'" . $old_data['barcodeId'] . "',
					'" . $old_data['bioMetricId'] . "',
					'" . $old_data['payroll_no'] . "',
					'" . $old_data['startdate'] . "',
					'" . $old_data['eocdate'] . "',
					'" . $old_data['emp_type'] . "',
					'" . $old_data['position'] . "',
					'" . $old_data['positionlevel'] . "',
					'End of Contract',
					'" . $old_data['lodging'] . "',
					'" . $old_data['position_desc'] . "',
					'" . mysql_real_escape_string($old_data['remarks']) . "',
					'" . $old_data['epas_code'] . "',
					'" . $old_data['contract'] . "',
					'" . $old_data['permit'] . "',
					'" . $old_data['clearance'] . "',
					'" . $old_data['comments'] . "',
					'" . $old_data['date_updated'] . "',
					'" . $old_data['updated_by'] . "',
					'" . $old_data['duration'] . "',
					'" . $old_data['emp_no'] . "',
					'" . $old_data['emp_pins'] . "'
				)"
		) or die(mysql_error());
		$sql = mysql_query(
			"SELECT
					record_no
				  FROM
					employmentrecord_
				  WHERE
					emp_id = '" . $empId . "'
				  ORDER BY 
					record_no DESC"
		) or die(mysql_error());
		$new_rno = mysql_fetch_array($sql);
		// appraisal details
		$sql = mysql_query(
			"SELECT 
					record_no
				 FROM
					appraisal_details
				 WHERE
					record_no = '" . $old_data['record_no'] . "'
					and emp_id = '" . $empId . "'"
		) or die(mysql_error());
		$c_appdetails = mysql_num_rows($sql);
		if ($c_appdetails > 0) {
			mysql_query(
				"UPDATE
					appraisal_details
				 SET
					record_no = '" . $new_rno['record_no'] . "'
				 WHERE
					record_no = '" . $old_data['record_no'] . "'
					and emp_id = '" . $empId . "'"
			) or die(mysql_error());
		}
		// witness
		$sql = mysql_query(
			"SELECT
					rec_no
				 FROM
					employment_witness
				 WHERE
					rec_no = '" . $old_data['record_no'] . "'"
		) or die(mysql_error());
		$c_empwitness = mysql_num_rows($sql);
		if ($c_empwitness > 0) {
			mysql_query(
				"UPDATE
					employment_witness
				 SET
					rec_no = '" . $new_rno['record_no'] . "'
				 WHERE
					rec_no = '" . $old_data['record_no'] . "'"
			) or die(mysql_error());
		}
		$sql2 = mysql_query(
			"SELECT
					*
				 FROM
					promo_record 
				 WHERE
					emp_id = '" . $empId . "'"
		) or die(mysql_error());
		$old_promo_data = mysql_fetch_array($sql2);
		// insert the old contrct to the promo_history_record table
		mysql_query(
			"INSERT
				INTO
			 promo_history_record
				(
					emp_id,
					promo_company,
					promo_department,
					company_duration,
					al_tag,
					al_tal,
					icm,
					pm,
					abenson_tag,
					abenson_icm,
					al_tub,
					promo_type,
					record_no,
					asc_epascode,
					tal_epascode,
					icm_epascode,
					pm_epascode,
					absna_epascode,
					absni_epascode,
					cdc_epascode,
					berama_epascode,
					tub_epascode,
					asc_contract,
					tal_contract,
					icm_contract,
					pm_contract,
					absna_contract,
					absni_contract,
					cdc_contract,
					berama_contract,
					tub_contract,
					asc_permit,
					tal_permit,
					icm_permit,
					pm_permit,
					absna_permit,
					absni_permit,
					cdc_permit,
					berama_permit,
					tub_permit,
					asc_clearance,
					tal_clearance,
					icm_clearance,
					pm_clearance,
					absna_clearance,
					absni_clearance,
					cdc_clearance,
					berama_clearance,
					tub_clearance,
					asc_intro,
					tal_intro,
					icm_intro,
					pm_intro,
					absna_intro,
					absni_intro,
					cdc_intro,
					berama_intro,
					tub_intro,
					fr_panglao,
					fr_panglao_epascode,
					fr_panglao_contract,
					fr_panglao_permit,
					fr_panglao_clearance,
					fr_panglao_intro,
					fr_tubigon,
					fr_tubigon_epascode,
					fr_tubigon_contract,
					fr_tubigon_permit,
					fr_tubigon_clearance,
					fr_tubigon_intro,
					al_panglao,
					al_panglao_epascode,
					al_panglao_contract,
					al_panglao_permit,
					al_panglao_clearance,
					al_panglao_intro,
					type,
					epas,
					transferOn,
					addedoutlet
				) VALUES (
					'" . $empId . "',
					'" . mysql_real_escape_string($old_promo_data['promo_company']) . "',
					'" . $old_promo_data['promo_department'] . "',
					'" . $old_promo_data['company_duration'] . "',
					'" . $old_promo_data['al_tag'] . "',
					'" . $old_promo_data['al_tal'] . "',
					'" . $old_promo_data['icm'] . "',
					'" . $old_promo_data['pm'] . "',
					'" . $old_promo_data['abenson_tag'] . "',
					'" . $old_promo_data['abenson_icm'] . "',
					'" . $old_promo_data['al_tub'] . "',
					'" . $old_promo_data['promo_type'] . "',
					'" . $new_rno['record_no'] . "',
					'" . $old_promo_data['asc_epascode'] . "',
					'" . $old_promo_data['tal_epascode'] . "',
					'" . $old_promo_data['icm_epascode'] . "',
					'" . $old_promo_data['pm_epascode'] . "',
					'" . $old_promo_data['absna_epascode'] . "',
					'" . $old_promo_data['absni_epascode'] . "',
					'" . $old_promo_data['cdc_epascode'] . "',
					'" . $old_promo_data['berama_epascode'] . "',
					'" . $old_promo_data['tub_epascode'] . "',
					'" . $old_promo_data['asc_contract'] . "',
					'" . $old_promo_data['tal_contract'] . "',
					'" . $old_promo_data['icm_contract'] . "',
					'" . $old_promo_data['pm_contract'] . "',
					'" . $old_promo_data['absna_contract'] . "',
					'" . $old_promo_data['absni_contract'] . "',
					'" . $old_promo_data['cdc_contract'] . "',
					'" . $old_promo_data['berama_contract'] . "',
					'" . $old_promo_data['tub_contract'] . "',
					'" . $old_promo_data['asc_permit'] . "',
					'" . $old_promo_data['tal_permit'] . "',
					'" . $old_promo_data['icm_permit'] . "',
					'" . $old_promo_data['pm_permit'] . "',
					'" . $old_promo_data['absna_permit'] . "',
					'" . $old_promo_data['absni_permit'] . "',
					'" . $old_promo_data['cdc_permit'] . "',
					'" . $old_promo_data['berama_permit'] . "',
					'" . $old_promo_data['tub_permit'] . "',
					'" . $old_promo_data['asc_clearance'] . "',
					'" . $old_promo_data['tal_clearance'] . "',
					'" . $old_promo_data['icm_clearance'] . "',
					'" . $old_promo_data['pm_clearance'] . "',
					'" . $old_promo_data['absna_clearance'] . "',
					'" . $old_promo_data['absni_clearance'] . "',
					'" . $old_promo_data['cdc_clearance'] . "',
					'" . $old_promo_data['berama_clearance'] . "',
					'" . $old_promo_data['tub_clearance'] . "',
					'" . $old_promo_data['asc_intro'] . "',
					'" . $old_promo_data['tal_intro'] . "',
					'" . $old_promo_data['icm_intro'] . "',
					'" . $old_promo_data['pm_intro'] . "',
					'" . $old_promo_data['absna_intro'] . "',
					'" . $old_promo_data['absni_intro'] . "',
					'" . $old_promo_data['cdc_intro'] . "',
					'" . $old_promo_data['berama_intro'] . "',
					'" . $old_promo_data['tub_intro'] . "',
					'" . $old_promo_data['fr_panglao'] . "',
					'" . $old_promo_data['fr_panglao_epascode'] . "',
					'" . $old_promo_data['fr_panglao_contract'] . "',
					'" . $old_promo_data['fr_panglao_permit'] . "',
					'" . $old_promo_data['fr_panglao_clearance'] . "',
					'" . $old_promo_data['fr_panglao_intro'] . "',
					'" . $old_promo_data['fr_tubigon'] . "',
					'" . $old_promo_data['fr_tubigon_epascode'] . "',
					'" . $old_promo_data['fr_tubigon_contract'] . "',
					'" . $old_promo_data['fr_tubigon_permit'] . "',
					'" . $old_promo_data['fr_tubigon_clearance'] . "',
					'" . $old_promo_data['fr_tubigon_intro'] . "',
					'" . $old_promo_data['al_panglao'] . "',
					'" . $old_promo_data['al_panglao_epascode'] . "',
					'" . $old_promo_data['al_panglao_contract'] . "',
					'" . $old_promo_data['al_panglao_permit'] . "',
					'" . $old_promo_data['al_panglao_clearance'] . "',
					'" . $old_promo_data['al_panglao_intro'] . "',
					'" . $old_promo_data['type'] . "',
					'" . $old_promo_data['epas'] . "',
					'" . $old_promo_data['transferOn'] . "',
					'" . $old_promo_data['addedoutlet'] . "'
				)"
		) or die(mysql_error());

		// insert employee3
		mysql_query(
			"INSERT INTO 
			`employee3`
				(
					`emp_id`, 
					`emp_no`, 
					`emp_pins`, 
					`barcodeId`, 
					`bioMetricId`, 
					`payroll_no`, 
					`name`, 
					`startdate`, 
					`eocdate`, 
					`emp_type`, 
					`current_status`, 
					`duration`, 
					`positionlevel`, 
					`position`,  
					`lodging`, 
					`remarks`, 
					`date_added`, 
					`added_by`
				) VALUES (
					'" . $empId . "',
					'" . $old_data['emp_no'] . "',
					'" . $old_data['emp_pins'] . "',
					'" . $old_data['barcodeId'] . "',
					'" . $old_data['bioMetricId'] . "',
					'" . $old_data['payroll_no'] . "',
					'" . $old_data['name'] . "',
					'" . $effectiveOn . "',
					'" . $old_data['eocdate'] . "',
					'" . $old_data['emp_type'] . "',
					'" . $old_data['current_status'] . "',
					'" . $old_data['duration'] . "',
					'" . $old_data['positionlevel'] . "',
					'" . $old_data['position'] . "',
					'" . $old_data['lodging'] . "',
					'',
					'" . $date . "',
					'" . $loginId . "'
				)
			"
		) or die(mysql_error());

		$recordNo = mysql_insert_id();

		// delete the old record in employee3
		mysql_query(
			"DELETE
				FROM
			 employee3
				WHERE
			 emp_id = '" . $empId . "' AND
			 record_no = '" . $old_data['record_no'] . "'"
		) or die(mysql_error());

		$upd = mysql_query("UPDATE `promo_record` SET `al_tag`='',`al_tal`='',`icm`='',`pm`='',`abenson_tag`='',`abenson_icm`='',`cdc`='',`berama`='',`al_tub`='',`colc`='',`colm`='' WHERE `emp_id` = '$empId'") or die(mysql_error());
		$update = mysql_query("UPDATE `promo_record` SET `record_no`='$recordNo', $updateField, $updateEpas, $updateContract, $updatePermit, $updateClearance, $updateIntro, `transferOn`='$effectiveOn' WHERE `emp_id` = '$empId'") or die(mysql_error());

		$loop = 0;
		$promo_type = "";
		$sqlBU = mysql_query("SELECT bunit_name, bunit_field FROM `locate_promo_business_unit` WHERE status = 'active'") or die(mysql_error());
		while ($rowBU = mysql_fetch_array($sqlBU)) {

			$chk = mysql_query("SELECT promo_id FROM `promo_record` WHERE " . $rowBU['bunit_field'] . " = 'T' AND emp_id = '$empId' AND record_no = '$recordNo'") or die(mysql_error());
			if (mysql_num_rows($chk) > 0) {

				$loop++;
				if ($loop == 1) {

					$promo_type = "STATION";
				} else {

					$promo_type = "ROVING";
				}
			}
		}

		if (empty($remarks)) {

			$remarks = "Transfer Outlet from $previousStore to $currentStore";
		}

		$insert = mysql_query("INSERT INTO `change_outlet_record`
										(`emp_id`, `changefrom`, `changeto`, `effectiveon`) 
									VALUES 
										('$empId','$previousStore','$currentStore','$effectiveOn')") or die(mysql_error());

		mysql_query("UPDATE promo_record SET promo_type = '$promo_type' WHERE emp_id = '$empId' AND record_no = '$recordNo'") or die(mysql_error());
		mysql_query("UPDATE `employee3` SET `remarks`='$remarks' WHERE emp_id ='$empId' AND record_no = '$recordNo'") or die(mysql_error());
		die("success");
	} else if ($_GET['request'] == "loadNewPromo") {

		$lastmonth = date("Y-m-d", strtotime("-1 month"));
		$date = date('Y-m-d');

		// storing  request (ie, get/post) global array to a variable  
		$requestData = $_REQUEST;
		$columns = array(
			// datatable column index  => database column lastname
			0 => 'employee3.name',
			1 => 'employee3.emp_type',
			2 => 'employee3.position',
			3 => 'employee3.startdate',
			4 => 'employee3.eocdate',
			5 => 'promo_record.promo_department',
		);

		// getting total number records without any search
		$sql = "SELECT employee3.emp_id, employee3.name, employee3.emp_type, employee3.startdate, employee3.eocdate, employee3.position, promo_record.promo_department FROM `employee3` INNER JOIN `promo_record` ON employee3.emp_id = promo_record.emp_id WHERE $promoEmpType AND tag_as = 'new' AND hr_location = '$hrCode' AND startdate BETWEEN '$lastmonth' AND '$date'";
		$query = mysql_query($sql) or die(mysql_error());
		$totalData = mysql_num_rows($query);
		$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

		$sql = "SELECT employee3.emp_id, employee3.name, employee3.emp_type, employee3.startdate, employee3.eocdate, employee3.position, promo_record.promo_department FROM `employee3` INNER JOIN `promo_record` ON employee3.emp_id = promo_record.emp_id WHERE 1=1 AND $promoEmpType AND tag_as = 'new' AND hr_location = '$hrCode' AND startdate BETWEEN '$lastmonth' AND '$date'";
		if (!empty($requestData['search']['value'])) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
			$sql .= " AND (employee3.name LIKE '%" . $requestData['search']['value'] . "%' ";
			$sql .= " OR DATE_FORMAT(employee3.startdate, '%m/%d/%Y') LIKE '%" . $requestData['search']['value'] . "%' ";
			$sql .= " OR DATE_FORMAT(employee3.eocdate, '%m/%d/%Y') LIKE '%" . $requestData['search']['value'] . "%' ";
			$sql .= " OR employee3.emp_type LIKE '%" . $requestData['search']['value'] . "%' ";
			$sql .= " OR employee3.position LIKE '%" . $requestData['search']['value'] . "%' ";
			$sql .= " OR promo_record.promo_department LIKE '%" . $requestData['search']['value'] . "%' )";
		}

		$query = mysql_query($sql) or die(mysql_error());
		$totalFiltered = mysql_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
		$sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "  LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
		/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */
		$query = mysql_query($sql) or die(mysql_error());

		$data = array();
		while ($row = mysql_fetch_array($query)) {  // preparing an array

			$empId = $row['emp_id'];

			$storeName = "";
			$ctr = 0;
			$bunit = mysql_query("SELECT bunit_field, bunit_acronym FROM `locate_promo_business_unit`") or die(mysql_error());
			while ($str = mysql_fetch_array($bunit)) {

				$promo = mysql_query("SELECT promo_id FROM `promo_record` WHERE `" . $str['bunit_field'] . "` = 'T' AND emp_id = '$empId'") or die(mysql_error());
				if (mysql_num_rows($promo) > 0) {
					$ctr++;

					if ($ctr == 1) {

						$storeName = $str['bunit_acronym'];
					} else {

						$storeName .= ", " . $str['bunit_acronym'];
					}
				}
			}

			$nestedData = array();
			$nestedData[] = "<a href='?p=profile&&module=Promo&&com=$empId' target='_blank'>" . ucwords(strtolower($row['name'])) . "</a>";
			$nestedData[] = ucwords(strtolower($row['position']));
			$nestedData[] = $row['emp_type'];
			$nestedData[] = $storeName;
			$nestedData[] = $row['promo_department'];
			$nestedData[] = date("m/d/Y", strtotime($row['startdate']));
			$nestedData[] = date("m/d/Y", strtotime($row['eocdate']));
			$data[] = $nestedData;
		}

		$json_data = array(
			"draw"            => intval($requestData['draw']),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
			"recordsTotal"    => intval($totalData),  // total number of records
			"recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
			"data"            => $data   // total data array
		);
		echo json_encode($json_data);  // send data as json format
	} else if ($_GET['request'] == "loadBirthdayToday") {

		$month_day = date('m-d');

		// storing  request (ie, get/post) global array to a variable  
		$requestData = $_REQUEST;
		$columns = array(
			// datatable column index  => database column lastname
			0 => 'employee3.name',
			1 => 'applicant.gender',
			2 => 'applicant.birthdate',
			3 => 'employee3.position',
			4 => 'promo_record.promo_department',
		);

		// getting total number records without any search
		$sql = "SELECT employee3.emp_id, employee3.name, employee3.position, employee3.startdate, applicant.birthdate, applicant.gender, promo_record.promo_department, promo_record.promo_type FROM `employee3` INNER JOIN `applicant` ON employee3.emp_id = applicant.app_id INNER JOIN `promo_record` ON employee3.emp_id = promo_record.emp_id WHERE $promoEmpType AND hr_location = '$hrCode' AND birthdate LIKE '%$month_day' AND current_status = 'active'";
		$query = mysql_query($sql) or die(mysql_error());
		$totalData = mysql_num_rows($query);
		$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

		$sql = "SELECT employee3.emp_id, employee3.name, employee3.position, employee3.startdate, applicant.birthdate, applicant.gender, promo_record.promo_department, promo_record.promo_type FROM `employee3` INNER JOIN `applicant` ON employee3.emp_id = applicant.app_id INNER JOIN `promo_record` ON employee3.emp_id = promo_record.emp_id WHERE 1=1 AND $promoEmpType AND hr_location = '$hrCode' AND birthdate LIKE '%$month_day' AND current_status = 'active'";
		if (!empty($requestData['search']['value'])) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
			$sql .= " AND (employee3.name LIKE '%" . $requestData['search']['value'] . "%' ";
			$sql .= " OR DATE_FORMAT(applicant.birthdate, '%m/%d/%Y') LIKE '%" . $requestData['search']['value'] . "%' ";
			$sql .= " OR applicant.gender LIKE '%" . $requestData['search']['value'] . "%' ";
			$sql .= " OR employee3.position LIKE '%" . $requestData['search']['value'] . "%' ";
			$sql .= " OR promo_record.promo_department LIKE '%" . $requestData['search']['value'] . "%' ";
			$sql .= " OR promo_record.promo_type LIKE '%" . $requestData['search']['value'] . "%' )";
		}

		$query = mysql_query($sql) or die(mysql_error());
		$totalFiltered = mysql_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
		$sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "  LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
		/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */
		$query = mysql_query($sql) or die(mysql_error());

		$data = array();
		while ($row = mysql_fetch_array($query)) {  // preparing an array

			$empId = $row['emp_id'];

			$storeName = "";
			$ctr = 0;
			$bunit = mysql_query("SELECT bunit_field, bunit_acronym FROM `locate_promo_business_unit`") or die(mysql_error());
			while ($str = mysql_fetch_array($bunit)) {

				$promo = mysql_query("SELECT promo_id FROM `promo_record` WHERE `" . $str['bunit_field'] . "` = 'T' AND emp_id = '$empId'") or die(mysql_error());
				if (mysql_num_rows($promo) > 0) {
					$ctr++;

					if ($ctr == 1) {

						$storeName = $str['bunit_acronym'];
					} else {

						$storeName .= ", " . $str['bunit_acronym'];
					}
				}
			}

			$nestedData = array();
			$nestedData[] = "<a href='?p=profile&&module=Promo&&com=$empId' target='_blank'>" . ucwords(strtolower($row['name'])) . "</a>";
			$nestedData[] = $row['gender'];
			$nestedData[] = date("m/d/Y", strtotime($row['birthdate']));
			$nestedData[] = $storeName;
			$nestedData[] = $row['promo_department'];
			$nestedData[] = $row['promo_type'];
			$nestedData[] = ucwords(strtolower($row['position']));
			$data[] = $nestedData;
		}

		$json_data = array(
			"draw"            => intval($requestData['draw']),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
			"recordsTotal"    => intval($totalData),  // total number of records
			"recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
			"data"            => $data   // total data array
		);
		echo json_encode($json_data);  // send data as json format
	} else if ($_GET['request'] == "loadFailedEpas") {

		$plus3 		= date('Y-m-d', strtotime('+5 month')); //date 3 month from the current date

		// storing  request (ie, get/post) global array to a variable  
		$requestData = $_REQUEST;
		$columns = array(
			// datatable column index  => database column lastname
			0 => 'employee3.name',
			1 => 'employee3.startdate',
			2 => 'employee3.eocdate'
		);

		// getting total number records without any search
		$sql = " SELECT employee3.record_no, employee3.emp_id, employee3.name, employee3.startdate, employee3.eocdate FROM `employee3` INNER JOIN `promo_record` ON employee3.emp_id = promo_record.emp_id INNER JOIN `appraisal_details` ON employee3.record_no = appraisal_details.record_no AND employee3.emp_id = appraisal_details.emp_id WHERE appraisal_details.numrate < 85 AND $promoEmpType AND hr_location = '$hrCode' AND (current_status = 'Active'or current_status = 'End of Contract') AND eocdate <= '$plus3'";
		$query = mysql_query($sql) or die(mysql_error());
		$totalData = mysql_num_rows($query);
		$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

		$sql = "SELECT employee3.record_no, employee3.emp_id, employee3.name, employee3.startdate, employee3.eocdate FROM `employee3` INNER JOIN `promo_record` ON employee3.emp_id = promo_record.emp_id INNER JOIN `appraisal_details` ON employee3.record_no = appraisal_details.record_no AND employee3.emp_id = appraisal_details.emp_id WHERE 1=1 AND appraisal_details.numrate < 85 AND $promoEmpType AND hr_location = '$hrCode' AND (current_status = 'Active'or current_status = 'End of Contract') AND eocdate <= '$plus3'";
		if (!empty($requestData['search']['value'])) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
			$sql .= " AND ( employee3.emp_id LIKE '%" . $requestData['search']['value'] . "%' ";
			$sql .= " OR employee3.name LIKE '%" . $requestData['search']['value'] . "%' ";
			$sql .= " OR DATE_FORMAT(employee3.startdate, '%m/%d/%Y') LIKE '%" . $requestData['search']['value'] . "%' ";
			$sql .= " OR DATE_FORMAT(employee3.eocdate, '%m/%d/%Y') LIKE '%" . $requestData['search']['value'] . "%' )";
		}

		$query = mysql_query($sql) or die(mysql_error());
		$totalFiltered = mysql_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
		$sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "  LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
		/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */
		$query = mysql_query($sql) or die(mysql_error());

		$data = array();
		while ($row = mysql_fetch_array($query)) {  // preparing an array

			$name = ucwords(strtolower($row['name']));
			$empId = $row['emp_id'];
			$recordNo = $row['record_no'];

			$nestedData = array();
			$nestedData[] = "<a href='?p=profile&&module=Promo&&com=$empId' target='_blank'>$name</a>";
			$nestedData[] = date("m/d/Y", strtotime($row['startdate']));
			$nestedData[] = date("m/d/Y", strtotime($row['eocdate']));

			$rate = $label = $label2 = "";
			$al_tagEpas = $al_talEpas = $icmEpas = $pmEpas = $al_tubEpas = $colcEpas = $colmEpas = 0;
			$al_tagComment = $al_talComment = $icmComment = $pmComment = $al_tubComment = $colcComment = $colmComment = "yes";
			$al_tagStore = $al_talStore = $icmStore = $pmStore = $al_tubStore = $colcStore = $colmStore = "";

			if ($hrCode == "asc") {

				$stores = mysql_query("SELECT bunit_name, bunit_field, bunit_epascode FROM `locate_promo_business_unit` WHERE appraisal_status = 'active' AND hrd_location = '$hrCode'") or die(mysql_error());
				while ($bU = mysql_fetch_array($stores)) {

					$promo = mysql_query("SELECT promo_id, " . $bU['bunit_epascode'] . " FROM promo_record WHERE " . $bU['bunit_field'] . " = 'T' AND emp_id = '$empId' AND record_no = '$recordNo'") or die(mysql_error());
					if (mysql_num_rows($promo) > 0) {

						$storeApp = mysql_query("SELECT details_id, numrate, descrate, raterSO, rateeSO FROM `appraisal_details` WHERE emp_id = '$empId' AND record_no = '$recordNo' AND store = '" . $bU['bunit_name'] . "'") or die(mysql_error());
						$epas = mysql_fetch_array($storeApp);

						$raterSO = $epas['raterSO'];
						$rateeSO = $epas['rateeSO'];
						$numrate = $epas['numrate'];

						if ($numrate != "") {

							if ($raterSO == 1 && $rateeSO == 1) {

								$rate = "yes";
								$label = "label label-success";
							} else {

								$rate = "no";
								$label = "label label-warning";
							}

							if ($numrate == 100) {
								$label2 = "label label-success";
							} else if ($numrate >= 90 && $numrate <= 99.99) {
								$label2 = "label label-primary";
							} else if ($numrate >= 85 && $numrate <= 89.99) {
								$label2 = "label label-info";
							} else if ($numrate >= 70 && $numrate <= 84.99) {
								$label2 = "label label-danger";
							} else if ($numrate >= 0 && $numrate <= 69.99) {
								$label2 = "label label-danger";
							} else {
								$label2 = "label label-danger";
							}

							if ($bU['bunit_name'] == "ASC: MAIN") {
								$al_tagEpas = $numrate;
								$al_tagComment = $rate;
							} else if ($bU['bunit_name'] == "ALTURAS TALIBON") {
								$al_talEpas = $numrate;
								$al_talComment = $rate;
							} else if ($bU['bunit_name'] == "ISLAND CITY MALL") {
								$icmEpas = $numrate;
								$icmComment = $rate;
							} else if ($bU['bunit_name'] == "PLAZA MARCELA") {
								$pmEpas = $numrate;
								$pmComment = $rate;
							} else if ($bU['bunit_name'] == "ALTURAS TUBIGON") {
								$al_tubEpas = $numrate;
								$al_tubComment = $rate;
							}

							$nestedData[] = "<a href='javascript:void(0)' onclick=viewdetails('$epas[details_id]') title='Click to View Appraisal Details'> <span class='$label2'>$numrate</span></a> <span class='$label'>$rate</span>";
						} else {

							if ($bU['bunit_name'] == "ASC: MAIN") {
								$al_tagEpas = 0;
								$al_tagComment = "no";
							} else if ($bU['bunit_name'] == "ALTURAS TALIBON") {
								$al_talEpas = 0;
								$al_talComment = "no";
							} else if ($bU['bunit_name'] == "ISLAND CITY MALL") {
								$icmEpas = 0;
								$icmComment = "no";
							} else if ($bU['bunit_name'] == "PLAZA MARCELA") {
								$pmEpas = 0;
								$pmComment = "no";
							} else if ($bU['bunit_name'] == "ALTURAS TUBIGON") {
								$al_tubEpas = 0;
								$al_tubComment = "no";
							}

							$nestedData[] = "<span class='label label-default'>none</span>";
						}
					} else {

						if ($bU['bunit_name'] == "ASC: MAIN") {
							$al_tagStore = "none";
						} else if ($bU['bunit_name'] == "ALTURAS TALIBON") {
							$al_talStore = "none";
						} else if ($bU['bunit_name'] == "ISLAND CITY MALL") {
							$icmStore = "none";
						} else if ($bU['bunit_name'] == "PLAZA MARCELA") {
							$pmStore = "none";
						} else if ($bU['bunit_name'] == "ALTURAS TUBIGON") {
							$al_tubStore = "none";
						}

						$nestedData[] = "";
					}
				}

				$option = "";

				if (($al_tagEpas >= 85 || $al_tagStore == "none") && ($al_talEpas >= 85 || $al_talStore == "none") && ($icmEpas >= 85 || $icmStore == "none") && ($pmEpas >= 85 || $pmStore == "none") && ($al_tubEpas >= 85 || $al_tubStore == "none")) {

					if ($al_tagComment == "yes" && $al_talComment == "yes" && $icmComment == "yes" && $pmComment == "yes" && $al_tubComment == "yes") {

						$option = "
								<select onchange=proceedTo(this.value,'$empId','$recordNo')>
									<option value=''>Proceed To</option>
									<option value='Renewal'>Renewal</option>
									<option value='Resigned'>Resigned</option>
								</select>
							";
					} else if (($al_tagEpas >= 85 && $al_tagComment == "no") || ($al_talEpas >= 85 && $al_talComment == "no") || ($icmEpas >= 85 && $icmComment == "no") || ($pmEpas >= 85 && $pmComment == "no") || ($al_tubEpas >= 85 && $al_tubComment == "no")) {

						$option = "
								<select onchange=proceedTo(this.value,'$empId','$recordNo')>
									<option value=''>Proceed To</option>
									<option value='Resigned'>Resigned</option>
									<option value='Blacklist'>Blacklist</option>
								</select>
							";
					} else {

						$option = "
								<select onchange=proceedTo(this.value,'$empId','$recordNo')>
									<option value=''>Proceed To</option>
									<option value='Resigned'>Resigned</option>
									<option value='Blacklist'>Blacklist</option>
								</select>
						";
					}
				} else {

					if ($al_tagEpas == 0 && $al_talEpas == 0 && $icmEpas == 0 && $pmEpas == 0 && $al_tubEpas == 0) {

						$option = "";
					} else {

						$option = "
									<select onchange=proceedTo(this.value,'$empId','$recordNo')>
										<option value=''>Proceed To</option>
										<option value='Blacklist'>Blacklist</option>
									</select>
								";
					}
				}
			} else {

				$stores = mysql_query("SELECT bunit_name, bunit_field, bunit_epascode FROM `locate_promo_business_unit` WHERE appraisal_status = 'active' AND hrd_location = '$hrCode'") or die(mysql_error());
				while ($bU = mysql_fetch_array($stores)) {

					$promo = mysql_query("SELECT promo_id, " . $bU['bunit_epascode'] . " FROM promo_record WHERE " . $bU['bunit_field'] . " = 'T' AND emp_id = '$empId' AND record_no = '$recordNo'") or die(mysql_error());
					if (mysql_num_rows($promo) > 0) {

						$storeApp = mysql_query("SELECT details_id, numrate, descrate, raterSO, rateeSO FROM `appraisal_details` WHERE emp_id = '$empId' AND record_no = '$recordNo' AND store = '" . $bU['bunit_name'] . "'") or die(mysql_error());
						$epas = mysql_fetch_array($storeApp);

						$raterSO = $epas['raterSO'];
						$rateeSO = $epas['rateeSO'];
						$numrate = $epas['numrate'];

						if ($numrate != "") {

							if ($raterSO == 1 && $rateeSO == 1) {

								$rate = "yes";
								$label = "label label-success";
							} else {

								$rate = "no";
								$label = "label label-warning";
							}

							if ($numrate == 100) {
								$label2 = "label label-success";
							} else if ($numrate >= 90 && $numrate <= 99.99) {
								$label2 = "label label-primary";
							} else if ($numrate >= 85 && $numrate <= 89.99) {
								$label2 = "label label-info";
							} else if ($numrate >= 70 && $numrate <= 84.99) {
								$label2 = "label label-danger";
							} else if ($numrate >= 0 && $numrate <= 69.99) {
								$label2 = "label label-danger";
							} else {
								$label2 = "label label-danger";
							}

							if ($bU['bunit_name'] == "COLONNADE- COLON") {
								$colcEpas = $numrate;
								$colcComment = $rate;
							} else if ($bU['bunit_name'] == "COLONNADE- MANDAUE") {
								$colmEpas = $numrate;
								$colmComment = $rate;
							}

							$nestedData[] = "<a href='javascript:void(0)' onclick=viewdetails('$epas[details_id]') title='Click to View Appraisal Details'> <span class='$label2'>$numrate</span></a> <span class='$label'>$rate</span>";
						} else {

							if ($bU['bunit_name'] == "COLONNADE- COLON") {
								$colcEpas = 0;
								$colcComment = "no";
							} else if ($bU['bunit_name'] == "COLONNADE- MANDAUE") {
								$colmEpas = 0;
								$colmComment = "no";
							}

							$nestedData[] = "<span class='label label-default'>none</span>";
						}
					} else {

						if ($bU['bunit_name'] == "COLONNADE- COLON") {
							$colcStore = "none";
						} else if ($bU['bunit_name'] == "COLONNADE- MANDAUE") {
							$colmStore = "none";
						}

						$nestedData[] = "";
					}
				}

				$option = "";

				if (($colcEpas >= 85 || $colcStore == "none") && ($colmEpas >= 85 || $colmStore == "none")) {

					if ($colcComment == "yes" && $colmComment == "yes") {

						$option = "
								<select onchange=proceedTo(this.value,'$empId','$recordNo')>
									<option value=''>Proceed To</option>
									<option value='Renewal'>Renewal</option>
									<option value='Resigned'>Resigned</option>
								</select>
							";
					} else if (($colcEpas >= 85 && $colcComment == "no") || ($colmEpas >= 85 && $colmComment == "no")) {

						$option = "
								<select onchange=proceedTo(this.value,'$empId','$recordNo')>
									<option value=''>Proceed To</option>
									<option value='Resigned'>Resigned</option>
									<option value='Blacklist'>Blacklist</option>
								</select>
							";
					} else {

						$option = "
								<select onchange=proceedTo(this.value,'$empId','$recordNo')>
									<option value=''>Proceed To</option>
									<option value='Resigned'>Resigned</option>
									<option value='Blacklist'>Blacklist</option>
								</select>
						";
					}
				} else {

					if ($colcEpas == 0 && $colmEpas == 0) {

						$option = "";
					} else {

						$option = "
									<select onchange=proceedTo(this.value,'$empId','$recordNo')>
										<option value=''>Proceed To</option>
										<option value='Blacklist'>Blacklist</option>
									</select>
								";
					}
				}
			}

			$nestedData[] = $option;
			$data[] = $nestedData;
		}

		$json_data = array(
			"draw"            => intval($requestData['draw']),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
			"recordsTotal"    => intval($totalData),  // total number of records
			"recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
			"data"            => $data   // total data array
		);
		echo json_encode($json_data);  // send data as json format
	} else if ($_GET['request'] == "viewStatDept") {

		$bunit = $_POST['bunit'];
		$dept = $_POST['dept'];
		$emp_type = $_POST['emp_type'];

		$department = "";
		if ($dept != "") {
			$department = " / " . $dept;
		}

		$query = mysql_query("SELECT bunit_name FROM `locate_promo_business_unit` WHERE bunit_field = '$bunit'") or die(mysql_error());
		$bu = mysql_fetch_array($query);

	?>
		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="btn-group pull-right">
					<a href="#" onclick="generate('<?php echo $bunit; ?>','<?php echo $dept; ?>','<?php echo $emp_type; ?>')" class="btn btn-success btn-md">Generate Report</a>
				</div>
				<h4><?php echo $bu['bunit_name'] . "" . $department . " / " . $emp_type; ?></h4>
			</div>
			<div class="panel-body">

				<table id="statistics" class="table table-bordered table-hover table-condensed" width="100%">
					<thead>
						<tr>
							<th>No.</th>
							<th>Emp.Id</th>
							<th>Employee Name</th>
							<th>Promo Type</th>
							<th>Contract Type</th>
						</tr>
					</thead>
				</table>
			</div>
		</div>

		<script type="text/javascript">
			var dataTable = $('#statistics').DataTable({

				"destroy": true,
				"order": [
					[0, 'asc'],
					[2, 'asc']
				],
				"ajax": {
					url: "functionquery.php?request=loadStatistics",
					type: "post",
					data: {
						bunit: '<?php echo $bunit; ?>',
						dept: '<?php echo $dept; ?>',
						emp_type: '<?php echo $emp_type; ?>'
					},
				},
				"columns": [{
						"width": "5%"
					},
					{
						"width": "15%"
					},
					{
						"width": "40%"
					},
					{
						"width": "20%"
					},
					{
						"width": "20%"
					}
				]
			});
		</script>
	<?php
	} else if ($_GET['request'] == "loadStatistics") {

		$bunit = $_POST['bunit'];
		$dept = $_POST['dept'];
		$emp_type = $_POST['emp_type'];

		$department = "";
		if ($dept != "") {
			$department = "AND promo_department = '$dept'";
		}

		$data = array();
		$num = 1;
		$query = mysql_query("SELECT employee3.emp_id, name, promo_department, promo_type, type FROM employee3, promo_record WHERE employee3.emp_id = promo_record.emp_id AND emp_type = '$emp_type' AND $bunit = 'T' $department AND current_status = 'Active'") or die(mysql_error());
		while ($row = mysql_fetch_array($query)) {
			$sub_array = array();
			$sub_array[] = $num++;
			$sub_array[] = $row['emp_id'];
			$sub_array[] = '<a href="?p=profile&&module=Promo&&com=' . $row['emp_id'] . '" target="_blank">' . ucwords(strtolower($row['name'])) . '</a>';
			$sub_array[] = $row['promo_type'];
			$sub_array[] = $row['type'];
			$data[] = $sub_array;
		}

		echo json_encode(array("data" => $data));
	} else if ($_GET['request'] == "viewStatAll") {

		$bunit = $_POST['bunit'];
		$dept = $_POST['dept'];

		$department = "";
		if ($dept != "") {
			$department = " / " . $dept;
		}

		$query = mysql_query("SELECT bunit_name FROM `locate_promo_business_unit` WHERE bunit_field = '$bunit'") or die(mysql_error());
		$bu = mysql_fetch_array($query);

	?>

		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="btn-group pull-right">
					<a href="#" onclick="generate('<?php echo $bunit; ?>','<?php echo $dept; ?>','')" class="btn btn-success btn-md">Generate Report</a>
				</div>
				<h4><?php echo $bu['bunit_name'] . "" . $department; ?></h4>
			</div>
			<div class="panel-body">

				<table id="statistics" class="table table-bordered table-hover table-condensed" width="100%">
					<thead>
						<tr>
							<th>No.</th>
							<th>Employee Name</th>
							<th>Emp. Type</th>
							<th>Promo Type</th>
							<th>Contract Type</th>
						</tr>
					</thead>
				</table>
			</div>
		</div>

		<script type="text/javascript">
			var dataTable = $('#statistics').DataTable({

				"destroy": true,
				"order": [
					[0, 'asc'],
					[2, 'asc']
				],
				"ajax": {
					url: "functionquery.php?request=loadStatisticsAllPromo",
					type: "post",
					data: {
						bunit: '<?php echo $bunit; ?>',
						dept: '<?php echo $dept; ?>'
					},
				},
				"columns": [{
						"width": "5%"
					},
					{
						"width": "40%"
					},
					{
						"width": "15%"
					},
					{
						"width": "20%"
					},
					{
						"width": "20%"
					}
				]
			});
		</script>
	<?php
	} else if ($_GET['request'] == "loadStatisticsAllPromo") {

		$bunit = $_POST['bunit'];
		$dept = $_POST['dept'];

		$department = "";
		if ($dept != "") {
			$department = "AND promo_department = '$dept'";
		}

		$data = array();
		$num = 1;
		$query = mysql_query("SELECT employee3.emp_id, name, emp_type, promo_department, promo_type, type FROM employee3, promo_record WHERE employee3.emp_id = promo_record.emp_id AND (emp_type = 'Promo' OR emp_type = 'Promo-NESCO') AND $bunit = 'T' $department AND current_status = 'Active'") or die(mysql_error());
		while ($row = mysql_fetch_array($query)) {
			$sub_array = array();
			$sub_array[] = $num++;
			$sub_array[] = '<a href="?p=profile&&module=Promo&&com=' . $row['emp_id'] . '" target="_blank">' . ucwords(strtolower($row['name'])) . '</a>';
			$sub_array[] = $row['emp_type'];
			$sub_array[] = $row['promo_type'];
			$sub_array[] = $row['type'];
			$data[] = $sub_array;
		}

		echo json_encode(array("data" => $data));
	} else if ($_GET['request'] == "saveDutyDetails") {

		$empId 		= $_POST['empId'];
		$recordNo 	= $_POST['recordNo'];
		$store 		= explode("|", $_POST['store']);
		$cutOff 	= explode("|", $_POST['cutOff']);

		$sched = mysql_query("SELECT * FROM timekeeping.shiftcodes WHERE shiftcode = '" . $_POST['dutySched'] . "'") or die(mysql_error());
		$s1 = mysql_fetch_array($sched);

		$In1 	= $s1['1stIn'];
		$Out1 	= $s1['1stOut'];
		$In2 	= $s1['2ndIn'];
		$Out2 	= $s1['2ndOut'];

		if ($In2 == "") {

			$promoSched = "$In1-$Out1";
		} else {

			$promoSched = "$In1-$Out1, $In2-$Out2";
		}

		if ($_POST['specialSched'] != "") {

			$specialSched = mysql_query("SELECT * FROM timekeeping.shiftcodes WHERE shiftcode = '" . $_POST['specialSched'] . "'") or die(mysql_error());
			$s2 = mysql_fetch_array($specialSched);

			$In1 	= $s2['1stIn'];
			$Out1 	= $s2['1stOut'];
			$In2 	= $s2['2ndIn'];
			$Out2 	= $s2['2ndOut'];

			if ($In2 == "") {

				$promoSpecialSched = "$In1-$Out1";
			} else {

				$promoSpecialSched = "$In1-$Out1, $In2-$Out2";
			}
		} else {

			$promoSpecialSched = "";
		}

		$saveDuty = mysql_query("UPDATE 
									`promo_record` 
										SET 
											`$store[2]` = '" . $promoSched . "', 
											`$store[3]`= '" . ucwords(strtolower(mysql_real_escape_string($_POST['dutyDays']))) . "', 
											`$store[4]` = '" . $promoSpecialSched . "', 
											`$store[5]` = '" . ucwords(strtolower(mysql_real_escape_string($_POST['specialDays']))) . "',
											`dayoff` = '" . mysql_real_escape_string($_POST['dayOff']) . "',
											`cutoff` = '" . mysql_real_escape_string($cutOff[1]) . "'
										WHERE `record_no` = '" . $recordNo . "' AND `emp_id` = '" . $empId . "'") or die(mysql_error());

		if ($cutOff[0] != "") {

			$chkNum = mysql_query("SELECT peId FROM timekeeping.promo_sched_emp WHERE statCut = '" . trim($cutOff[0]) . "' AND empId = '" . $empId . "'") or die(mysql_error());
			if (mysql_num_rows($chkNum) == 0) {

				$chkNum2 = mysql_query("SELECT peId FROM timekeeping.promo_sched_emp WHERE empId = '" . $empId . "'") or die(mysql_error());

				if (mysql_num_rows($chkNum2) > 0) {

					$cutoffHist = mysql_query("INSERT INTO 
													timekeeping.promo_sched_emp_hist
														(`statCut`, `empId`, `dateUpdated`) 
													VALUES 
														('" . $cutOff[0] . "','" . $empId . "','" . $date . "')
											") or die(mysql_error());

					$updCutoff = mysql_query("UPDATE 
													timekeeping.promo_sched_emp 
													SET 
														`statCut`='" . $cutOff[0] . "',
														`date_setup`='" . $date . "' 
													WHERE `empId`='" . $empId . "'
											") or die(mysql_error());
				} else {

					$insCutoff = mysql_query("INSERT INTO 
													timekeeping.promo_sched_emp
														(`statCut`, `empId`, `date_setup`) 
													VALUES 
														('" . $cutOff[0] . "','" . $empId . "','" . $date . "')
											") or die(mysql_error());
				}
			}
		}

		die("success");
	} else if ($_GET['request'] == "dutySched") {

		$store = mysql_real_escape_string($_POST['store']);
		$department = $_POST['department'];
		$company = $_POST['company'];
		$status = mysql_real_escape_string($_POST['status']);

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

		$data = array();
		$query = mysql_query("SELECT employee3.emp_id, name, current_status, startdate, eocdate, position, promo_company, promo_department, promo_type, dayoff, cutoff, $fields
									FROM employee3, promo_record 
										WHERE employee3.emp_id = promo_record.emp_id AND (emp_type = 'Promo' OR emp_type = 'Promo-NESCO' OR emp_type = 'Promo-EasyL') $where ORDER BY name ASC") or die(mysql_error());

		while ($row = mysql_fetch_array($query)) {

			if ($row[$sched['bunit_specialDays']] != "") {

				$dutySched = $row[$sched['bunit_dutySched']] . " & " . $row[$sched['bunit_specialSched']];
				$dutyDays = $row[$sched['bunit_dutyDays']] . " & " . $row[$sched['bunit_specialDays']];
			} else {

				$dutySched = $row[$sched['bunit_dutySched']];
				$dutyDays = $row[$sched['bunit_dutyDays']];
			}

			$ctr = 0;
			$bunit = mysql_query("SELECT bunit_field, bunit_name FROM `locate_promo_business_unit` WHERE hrd_location = '" . $hrCode . "'") or die(mysql_error());
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

			$name = utf8_encode($row['name']);

			$sub_array = array();
			$sub_array[] = '<a href="?p=profile&&module=Promo&&com=' . $row['emp_id'] . '" target="_blank">' . ucwords(strtolower(utf8_decode($name))) . '</a>';
			$sub_array[] = strtoupper($dutyDays);
			$sub_array[] = $dutySched;
			$sub_array[] = $row['dayoff'];
			$sub_array[] = $row['cutoff'];
			$sub_array[] = $storeName;
			$sub_array[] = $row['promo_department'];
			$sub_array[] = $row['promo_company'];
			$sub_array[] = $row['position'];
			$sub_array[] = $row['promo_type'];
			$sub_array[] = date('m/d/Y', strtotime($row['startdate'])) . " - " . date('m/d/Y', strtotime($row['eocdate']));
			$data[] = $sub_array;
		}

		echo json_encode(array("data" => $data));
	} else if ($_GET['request'] == "addAgencyForm") {

	?>
		<div class="form-group">
			<label>Agency Name :</label>
			<input type="text" class="form-control" name="agency_name" onclick="inputField(this.name)">
		</div>
	<?php
	} else if ($_GET['request'] == "addAgency") {

		$agency = mysql_real_escape_string(utf8_decode($_POST['agency']));

		$sql = mysql_query("INSERT INTO $datab2.promo_locate_agency
								(agency_name, created_at)
							VALUES
								('" . $agency . "', '" . date('Y-m-d H:i:s') . "')
							") or die(mysql_error());
		die("success");
	} else if ($_GET['request'] == "selectAgency") {
	?>
		<div class="form-group">
			<label>Agency Name:</label>
			<select name="setupan_agency" class="form-control" onchange="company_list(this.value)">
				<option value="">Select Agency</option>
				<?php

				$agencies = mysql_query("SELECT * FROM $datab2.promo_locate_agency ORDER BY agency_name ASC") or die(mysql_error());
				while ($agency = mysql_fetch_array($agencies)) {

					echo '<option value="' . $agency['agency_code'] . '">' . $agency['agency_name'] . '</option>';
				}
				?>
			</select>
		</div>
	<?php
	} else if ($_GET['request'] == "setupan_agency") {

	?>
		<div class="table-responsive">
			<table id="dt_companies" class="table table-bordered table-hover" width="100%">
				<thead>
					<tr>
						<th>Company Name</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php

					$agency_code = $_POST['agency_code'];
					$fetch_data = mysql_query("SELECT pc_name FROM locate_promo_company ORDER BY pc_name ASC") or die(mysql_error());

					$data = array();
					$x = 1;
					while ($row = mysql_fetch_array($fetch_data)) {

						$sql = mysql_query("SELECT COUNT(company_code) AS numExist FROM $datab2.promo_locate_company WHERE agency_code = '$agency_code' AND company_name = '" . mysql_real_escape_string(trim($row['pc_name'])) . "'") or die(mysql_error());
						$com = mysql_fetch_array($sql);
						if ($com['numExist'] > 0) {

							$action =  '<input type="checkbox" name="" class="chk_' . $x . '" value="' . $row['pc_name'] . '" checked="" onclick="chk(' . $x . ')">';
							echo '<input type="checkbox" name="chkCom[]" class="chkA_' . $x . '" value="' . $row['pc_name'] . '" checked="" style="display:none;">';
						} else {

							$action =  '<input type="checkbox" name="" class="chk_' . $x . '" value="' . $row['pc_name'] . '" onclick="chk(' . $x . ')">';
							echo '<input type="checkbox" name="chkCom[]" class="chkA_' . $x . '" value="' . $row['pc_name'] . '" style="display:none;">';
						}

						echo '
								<tr>
									<td>' . $row['pc_name'] . '</td>
									<td>' . $action . '</td>
								</tr>
							';

						$x++;
					}
					?>
				</tbody>
			</table>
		</div>
		<script type="text/javascript">
			$("table#dt_companies").DataTable({

				"destroy": true,
				"paging": false
			});
		</script>
	<?php
	} else if ($_GET['request'] == "insert_update_locate_company") {

		$agency_code = $_POST['agency_code'];
		$newCHK 	 = $_POST['newCHK'];

		$delete = mysql_query("DELETE FROM $datab2.promo_locate_company WHERE agency_code = '$agency_code'") or die(mysql_error());
		if ($delete) {

			$chk = explode("|_", $newCHK);

			for ($i = 0; $i < count($chk) - 1; $i++) {

				$sql = mysql_query("INSERT INTO $datab2.promo_locate_company
								(agency_code, company_name, created_at)
							VALUES
								('$agency_code', '" . mysql_real_escape_string($chk[$i]) . "', '" . date('Y-m-d H:i:s') . "')
							") or die(mysql_error());
			}

			die("success");
		} else {

			die("failure");
		}
	} else if ($_GET['request'] == "com_for_agency") {

		$agencies = mysql_query("SELECT * FROM $datab2.promo_locate_agency") or die(mysql_error());
		$data = array();
		while ($agency = mysql_fetch_array($agencies)) {

			$agency_code = $agency['agency_code'];
			$agency_name = $agency['agency_name'];

			$fetch_data = mysql_query("SELECT company_code, agency_code, company_name FROM $datab2.promo_locate_company WHERE agency_code = '$agency_code'") or die(mysql_error());
			while ($row = mysql_fetch_array($fetch_data)) {

				$action =  '<i id="delete_' . $row['company_code'] . '" class="fa fa-lg fa-trash text-danger delete_company"></i>';

				$sub_array = array();
				$sub_array[] = $agency_name;
				$sub_array[] = $row['company_name'];
				$sub_array[] = $action;
				$data[] = $sub_array;
			}
		}

		echo json_encode(array("data" => $data));
	} else if ($_GET['request'] == "delete_company") {

		$company_code = $_POST['company_code'];
		$delete = mysql_query("DELETE FROM $datab2.promo_locate_company WHERE company_code = '$company_code'") or die(mysql_error());
		if ($delete) {

			die("success");
		} else {

			die("failure");
		}
	} else if ($_GET['request'] == "agency_list") {

		$agencies = mysql_query("SELECT * FROM $datab2.promo_locate_agency") or die(mysql_error());
		$data = array();
		$no = 1;
		while ($agency = mysql_fetch_array($agencies)) {

			$agency_code = $agency['agency_code'];
			$agency_name = $agency['agency_name'];

			$action =  '<a href="javascript:void(0);" id="update_' . $agency_code . '" class="update_agency"><i class="glyphicon glyphicon-pencil"></i> &nbsp;</a>';
			if ($agency['status'] == '1') {

				$action .= '<a href="javascript:void(0)" id="deactivate_' . $agency_code . '" title="click to deactivate agency" class="action"><img src="../images/icons/icon-close-circled-20.png" height="17" width="17"></a>';
			} else {

				$action .= '<a href="javascript:void(0)" id="activate_' . $agency_code . '" title="click to activate agency" class="action"><img src="../images/icons/icn_active.gif" height="17" width="17"></a>';
			}

			if ($_SESSION['emp_id'] == "06359-2013") {
				$action .= ' <a href="javascript:void(0);" id="delete_' . $agency_code . '" class="action"><i class="glyphicon glyphicon-trash text-red"></i></a>';
			}

			$sub_array = array();
			$sub_array[] = $agency_name;
			$sub_array[] = $action;
			$data[] = $sub_array;

			$no++;
		}

		echo json_encode(array("data" => $data));
	} else if ($_GET['request'] == "department_list") {
		$query = mysql_query("SELECT * FROM locate_promo_business_unit") or die(mysql_error());
		$data = [];
		while ($bu = mysql_fetch_array($query)) {

			$dept = mysql_query("SELECT * FROM locate_promo_department WHERE bunit_id='" . $bu['bunit_id'] . "'") or die(mysql_error());

			while ($row = mysql_fetch_array($dept)) {
				$action =  '<a href="javascript:void(0);" id="remove-' . $row['dept_id'] . '" onclick="dept_action(this.id)" class="edit" title="edit">
							<i class="glyphicon glyphicon-trash text-danger"></i>
						</a>';
				$options = ['active', 'inactive'];

				$status = '<select id="status-' . $row['dept_id'] . '" class="status">';
				foreach ($options as $option) {
					if ($option == $row['status']) {
						$status .= '<option value="' . $option . '" selected ">' . $option . '</option>';
					} else {
						if ($row['status'] == '') {
							$status .= '<option value="inactive">inactive</option>';
						} else {
							$status .= '<option value="' . $option . '">' . $option . '</option>';
						}
					}
				}
				$status .= '</select>';
				$sub_array = array();
				$sub_array[] = $bu['bunit_name'];
				$sub_array[] = $row['dept_name'];
				$sub_array[] = $status;
				$sub_array[] = $action;
				$data[] = $sub_array;
			}
		}

		echo json_encode(array("data" => $data));
	} else if ($_GET['request'] == "updateDeptStat") {
		$column 	= $_POST['column'];
		$dept_id	= $_POST['dept_id'];
		$status 	= $_POST['status'];
		$update 	= mysql_query("UPDATE locate_promo_department SET $column = '" . $status . "' WHERE dept_id = '" . $dept_id . "'");
		if ($update) {
			echo json_encode(array('status' => 'success'));
		} else {
			echo json_encode(array('status' => 'failure'));
		}
	} else if ($_GET['request'] == "deptAction") {
		$process 	= $_POST['process'];
		$dept_id	= $_POST['dept_id'];
		if ($process === 'remove') {
			$query = mysql_query("DELETE FROM locate_promo_department  WHERE dept_id = '" . $dept_id . "'");
		}
		if ($query) {
			echo json_encode(array('status' => 'success'));
		} else {
			echo json_encode(array('status' => 'failure'));
		}
	} else if ($_GET['request'] == "addDeptForm") {

		echo 	'<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<label for="">Business Unit</label> <span class="text-red">*</span>
							<select name="bunit_id" id="" class="form-control">
								<option value="">Select Business Unit</option>';
		$query = mysql_query("SELECT * FROM locate_promo_business_unit WHERE hrd_location='asc'") or die(mysql_error());
		while ($bu = mysql_fetch_array($query)) {

			echo 				'<option value="' . $bu['bunit_id'] . '">' . $bu['bunit_name'] . '</option>';
		}
		echo 				'</select>
						</div>
					</div>
					<div class=" col-md-12">
						<div class="form-group">
							<label for="">Deparment</label> <span class="text-red">*</span>
							<select name="dept_name" id="" class="form-control">
								<option value="">Select Department</option>';
		$query = mysql_query("SELECT distinct dept_name FROM locate_promo_department WHERE dept_name!=''") or die(mysql_error());
		while ($dept = mysql_fetch_array($query)) {

			echo 				'<option value="' . $dept['dept_name'] . '">' . $dept['dept_name'] . '</option>';
		}
		echo 				'</select>
						</div>
						
					</div>
					
				</div>';
	} else if ($_GET['request'] == "saveAddDept") {
		$bunit_id  	= $_POST['bunit_id'];
		$dept_name	= $_POST['dept_name'];

		$check = mysql_query("SELECT * FROM locate_promo_department WHERE bunit_id = '" . $bunit_id . "' AND dept_name = '" . $dept_name . "'");
		if (mysql_num_rows($check) == 0) {
			$insert = mysql_query("INSERT INTO locate_promo_department (
				bunit_id, 
				dept_name, 
				status) 
				VALUES (
				'" . $bunit_id  . "',
				'" . $dept_name . "',
				'active'
				)");
			if ($insert) {
				echo json_encode(array('status' => 'success'));
			} else {
				echo json_encode(array('status' => 'failure'));
			}
		} else {
			echo json_encode(array('status' => 'exist'));
		}
	} else if ($_GET['request'] == "bu_list") {

		$query = mysql_query("SELECT * FROM locate_promo_business_unit") or die(mysql_error());
		$data = [];
		while ($bu = mysql_fetch_array($query)) {

			$action =  '<a href="javascript:void(0);" id="edit-' . $bu['bunit_id'] . '" onclick="update_bu(this.id)" class="edit" title="edit">
							<i class="glyphicon glyphicon-pencil"></i>
						</a>';
			$options = ['active', 'inactive'];

			$status = '<select id="status-' . $bu['bunit_id'] . '" class="status">';
			foreach ($options as $option) {
				if ($option == $bu['status']) {
					$status .= '<option value="' . $option . '" selected ">' . $option . '</option>';
				} else {
					if ($bu['status'] == '') {
						$status .= '<option value="inactive">inactive</option>';
					} else {
						$status .= '<option value="' . $option . '">' . $option . '</option>';
					}
				}
			}
			$status .= '</select>';

			$tk_status = '<select id="tk_status-' . $bu['bunit_id'] . '" class="status">';
			foreach ($options as $option) {
				if ($option == $bu['tk_status']) {
					$tk_status .= '<option value="' . $option . '" selected ">' . $option . '</option>';
				} else {
					if ($bu['tk_status'] == '') {
						$tk_status .= '<option value="inactive">inactive</option>';
					} else {
						$tk_status .= '<option value="' . $option . '">' . $option . '</option>';
					}
				}
			}
			$tk_status .= '</select>';

			$appraisal_status = '<select id="appraisal_status-' . $bu['bunit_id'] . '" class="status">';
			foreach ($options as $option) {
				if ($option == $bu['appraisal_status']) {
					$appraisal_status .= '<option value="' . $option . '" selected ">' . $option . '</option>';
				} else {
					if ($bu['appraisal_status'] == '') {
						$appraisal_status .= '<option value="inactive">inactive</option>';
					} else {
						$appraisal_status .= '<option value="' . $option . '">' . $option . '</option>';
					}
				}
			}
			$appraisal_status .= '</select>';

			$sub_array = array();
			$sub_array[] = $bu['bunit_name'];
			$sub_array[] = $status;
			$sub_array[] = $tk_status;
			$sub_array[] = $appraisal_status;
			$sub_array[] = $action;
			$data[] = $sub_array;
		}

		echo json_encode(array("data" => $data));
	} else if ($_GET['request'] == "addBuForm") {

		echo 	'<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<label for="">Business Unit</label> <span class="text-red">*</span>
							<input type="text" name="business_unit" class="form-control" style="text-transform: uppercase;">
						</div>
					</div>
					<div class=" col-md-6">
						<div class="form-group">
							<label for="">Business Unit Name</label> <span class="text-red">*</span>
							<input type="text" name="bunit_name" class="form-control" style="text-transform: uppercase;">
						</div>
						<div class="form-group">
							<label for="">Acronym</label> <span class="text-red">*</span>
							<input type="text" name="bunit_acronym" class="form-control" style="text-transform: uppercase;">
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="">Column Field</label> <span class="text-red">*</span>
							<input type="text" name="bunit_field" class="form-control" style="text-transform: lowercase;">
						</div>
						<div class="form-group">
							<label for="">Location</label> <span class="text-red">*</span>
							<select name="hrd_location" id="" class="form-control">
								<option value="">Select HRD Location</option>';
		$locations = array('asc', 'cebo', 'nesco');
		foreach ($locations as $location) {
			echo 				'<option value="' . $location . '">' . $location . '</option>';
		}
		echo 				'</select>
						</div>
					</div>
				</div>';
	} else if ($_GET['request'] == "updateBUform") {

		$bunit_id	= $_POST['bunit_id'];
		$query = mysql_query("SELECT * FROM locate_promo_business_unit WHERE bunit_id = '" . $bunit_id . "'");
		$bu = mysql_fetch_array($query);

		echo 	'<div class="row">
					<input type="hidden" name="bunit_id" value="' . $bu['bunit_id'] . '">
					<div class="col-md-12">
						<div class="form-group">
							<label for="">Business Unit</label> <span class="text-red">*</span>
							<input type="text" name="business_unit" value="' . $bu['business_unit'] . '" class="form-control" style="text-transform: uppercase;">
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="">Business Unit Name</label> <span class="text-red">*</span>
							<input type="text" name="bunit_name" value="' . $bu['bunit_name'] . '" class="form-control" style="text-transform: uppercase;">
						</div>
						<div class="form-group">
							<label for="">Acronym</label> <span class="text-red">*</span>
							<input type="text" name="bunit_acronym" value="' . $bu['bunit_acronym'] . '" class="form-control" style="text-transform: uppercase;">
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="">Location</label> <span class="text-red">*</span>
							<select name="hrd_location" id="" class="form-control">
								<option value="">-Select HRD Location-</option>';

		$locations = array('asc', 'cebo', 'nesco');
		foreach ($locations as $location) {
			if ($location == $bu['hrd_location']) {
				echo 			'<option value="' . $location . '" selected>' . $location . '</option>';
			} else {
				echo 			'<option value="' . $location . '">' . $location . '</option>';
			}
		}
		echo 				'</select>
						</div>
						<div class="form-group">
							<label for="">Column Field</label> <span class="text-red">*</span>
							<input type="text" name="bunit_field" value="' . $bu['bunit_field'] . '"  class="form-control" style="text-transform: lowercase;" disabled>
						</div>
					</div>
				</div>';
	} else if ($_GET['request'] == "updateBUdetails") {
		$bunit_id       = $_POST['bunit_id'];
		$business_unit  = $_POST['business_unit'];
		$bunit_name     = $_POST['bunit_name'];
		$bunit_acronym  = $_POST['bunit_acronym'];
		$hrd_location   = $_POST['hrd_location'];

		$chk_field = array(
			'business_unit' => $business_unit,
			'bunit_name' 	=> $bunit_name,
			'bunit_acronym' => $bunit_acronym,
		);
		$check = false;
		foreach ($chk_field as $key => $value) {
			$query = mysql_query("SELECT * FROM locate_promo_business_unit WHERE $key = '" . $value . "' AND bunit_id != '" . $bunit_id . "'");
			if (mysql_num_rows($query) > 0) {
				$check = true;
				$exist = $key;
			}
		}
		if (!$check) {
			$update = mysql_query("UPDATE locate_promo_business_unit 
                      SET business_unit = '" . $business_unit . "', 
                          bunit_name = '" . $bunit_name . "', 
                          bunit_acronym = '" . $bunit_acronym . "', 
                          hrd_location = '" . $hrd_location . "' 
                    	WHERE bunit_id = '" . $bunit_id . "'");
			if ($update) {
				echo json_encode(array('status' => 'success'));
			} else {
				echo json_encode(array('status' => 'failure'));
			}
		} else {
			echo json_encode(array('status' => 'exist', 'field' => $exist));
		}
	} else if ($_GET['request'] == "updateBUstat") {
		$column 	= $_POST['column'];
		$bunit_id	= $_POST['bunit_id'];
		$status 	= $_POST['status'];
		$update 	= mysql_query("UPDATE locate_promo_business_unit SET $column = '" . $status . "' WHERE bunit_id = '" . $bunit_id . "'");
		if ($update) {
			echo json_encode(array('status' => 'success'));
		} else {
			echo json_encode(array('status' => 'failure'));
		}
	} else if ($_GET['request'] == "saveAddBu") {
		$business_unit  = $_POST['business_unit'];
		$bunit_name     = $_POST['bunit_name'];
		$bunit_acronym  = $_POST['bunit_acronym'];
		$bunit_field  	= $_POST['bunit_field'];
		$hrd_location   = $_POST['hrd_location'];

		$chk_field = array(
			'business_unit' => $business_unit,
			'bunit_name' 	=> $bunit_name,
			'bunit_acronym' => $bunit_acronym,
			'bunit_field' 	=> $bunit_field
		);
		$check = false;
		foreach ($chk_field as $key => $value) {
			$query = mysql_query("SELECT * FROM locate_promo_business_unit WHERE $key = '" . $value . "'");
			if (mysql_num_rows($query) > 0) {
				$check = true;
				$exist = $key;
			}
		}

		if (!$check) {
			$save = mysql_query("INSERT INTO locate_promo_business_unit (
			bunit_epascode,        
			bunit_contract,       
			bunit_permit,         
			bunit_clearance,      
			bunit_intro,          
			bunit_dutySched,      
			bunit_dutyDays,       
			bunit_specialSched,   
			bunit_specialDays,     
			bunit_name, 
			business_unit, 
			bunit_field, 
			bunit_acronym, 
			hrd_location, 
			status, 
			tk_status, 
			appraisal_status) 
			VALUES (
			'" . strtolower($bunit_field) . "_epascode',
			'" . strtolower($bunit_field) . "_contract',
			'" . strtolower($bunit_field) . "_permit',
			'" . strtolower($bunit_field) . "_clearance',
			'" . strtolower($bunit_field) . "_intro',
			'" . strtolower($bunit_field) . "_sched',
			'" . strtolower($bunit_field) . "_days',
			'" . strtolower($bunit_field) . "_special_sched',
			'" . strtolower($bunit_field) . "_special_days',
			'" . strtoupper($bunit_name) . "', 
			'" . strtoupper($business_unit) . "', 
			'" . strtolower($bunit_field) . "', 
			'" . strtoupper($bunit_acronym) . "', 
			'" . $hrd_location . "', 
			'active', 
			'active', 
			'active')");

			if ($save) {
				$pr = mysql_query("ALTER TABLE `promo_record`
 				ADD COLUMN `" . strtolower($bunit_field) . "` VARCHAR(1) NOT NULL AFTER `alta_citta`,
 				ADD COLUMN `" . strtolower($bunit_field) . "_epascode` VARCHAR(255) NOT NULL AFTER `alta_special_days`,
 				ADD COLUMN `" . strtolower($bunit_field) . "_contract` TEXT NOT NULL AFTER `" . strtolower($bunit_field) . "_epascode`,
 				ADD COLUMN `" . strtolower($bunit_field) . "_permit` VARCHAR(255) NOT NULL AFTER `" . strtolower($bunit_field) . "_contract`,
 				ADD COLUMN `" . strtolower($bunit_field) . "_clearance` TEXT NOT NULL AFTER `" . strtolower($bunit_field) . "_permit`,
 				ADD COLUMN `" . strtolower($bunit_field) . "_intro` VARCHAR(255) NOT NULL AFTER `" . strtolower($bunit_field) . "_clearance`,
 				ADD COLUMN `" . strtolower($bunit_field) . "_sched` VARCHAR(50) NOT NULL AFTER `" . strtolower($bunit_field) . "_intro`,
 				ADD COLUMN `" . strtolower($bunit_field) . "_days` VARCHAR(50) NOT NULL AFTER `" . strtolower($bunit_field) . "_sched`,
 				ADD COLUMN `" . strtolower($bunit_field) . "_special_sched` VARCHAR(50) NOT NULL AFTER `" . strtolower($bunit_field) . "_days`,
 				ADD COLUMN `" . strtolower($bunit_field) . "_special_days` VARCHAR(50) NOT NULL AFTER `" . strtolower($bunit_field) . "_special_sched`") or die(mysql_error());;

				$phr = mysql_query("ALTER TABLE `promo_history_record`
				ADD COLUMN `" . strtolower($bunit_field) . "` VARCHAR(1) NOT NULL AFTER `alta_citta`,
				ADD COLUMN `" . strtolower($bunit_field) . "_epascode` VARCHAR(255) NOT NULL AFTER `alta_special_days`,
				ADD COLUMN `" . strtolower($bunit_field) . "_contract` TEXT NOT NULL AFTER `" . strtolower($bunit_field) . "_epascode`,
				ADD COLUMN `" . strtolower($bunit_field) . "_permit` VARCHAR(255) NOT NULL AFTER `" . strtolower($bunit_field) . "_contract`,
				ADD COLUMN `" . strtolower($bunit_field) . "_clearance` TEXT NOT NULL AFTER `" . strtolower($bunit_field) . "_permit`,
				ADD COLUMN `" . strtolower($bunit_field) . "_intro` VARCHAR(255) NOT NULL AFTER `" . strtolower($bunit_field) . "_clearance`,
				ADD COLUMN `" . strtolower($bunit_field) . "_sched` VARCHAR(50) NOT NULL AFTER `" . strtolower($bunit_field) . "_intro`,
				ADD COLUMN `" . strtolower($bunit_field) . "_days` VARCHAR(50) NOT NULL AFTER `" . strtolower($bunit_field) . "_sched`,
				ADD COLUMN `" . strtolower($bunit_field) . "_special_sched` VARCHAR(50) NOT NULL AFTER `" . strtolower($bunit_field) . "_days`,
				ADD COLUMN `" . strtolower($bunit_field) . "_special_days` VARCHAR(50) NOT NULL AFTER `" . strtolower($bunit_field) . "_special_sched`") or die(mysql_error());;
			}
			if ($pr && $phr) {
				echo json_encode(array('status' => 'success'));
			} else {
				echo json_encode(array('status' => 'failure'));
			}
		} else {

			echo json_encode(array('status' => 'exist', 'field' => $exist));
		}
	} else if ($_GET['request'] == "delete_agency") {

		$agency_code = $_POST['agency_code'];
		$company = mysql_query("DELETE FROM $datab2.promo_locate_company WHERE agency_code = '$agency_code'") or die(mysql_error());
		$agency = mysql_query("DELETE FROM $datab2.promo_locate_agency WHERE agency_code = '$agency_code'") or die(mysql_error());
		if ($agency) {

			die("success");
		} else {

			die("failure");
		}
	} else if ($_GET['request'] == "update_agency_form") {

		$agency_code = $_POST['agency_code'];

		$sql = mysql_query("SELECT * FROM $datab2.promo_locate_agency WHERE agency_code = '" . $agency_code . "'") or die(mysql_error());
		$row = mysql_fetch_array($sql);
	?>
		<input type="hidden" name="agency_code" value="<?php echo $agency_code; ?>">
		<div class="form-group">
			<label>Agency Name :</label>
			<input type="text" class="form-control" name="agency_name" value="<?php echo $row['agency_name']; ?>" onkeyup="inputField(this.name)">
		</div>
	<?php
	} else if ($_GET['request'] == "updateAgency") {

		$agency_code = $_POST['agency_code'];
		$agency = mysql_real_escape_string($_POST['agency']);

		$sql = mysql_query("UPDATE $datab2.promo_locate_agency SET agency_name = '" . $agency . "', updated_at = '" . date('Y-m-d H:i:s') . "' WHERE agency_code = '" . $agency_code . "'") or die();
		if ($sql) {

			die("success");
		} else {

			die("failure");
		}
	} else if ($_GET['request'] == "loadPromoIupdate") {

		$server = $_POST['server'];
		// if ($server === 'talibon_server') {

		// 	$where = "AND al_tal = 'T'";
		// } else {

		// 	$where = "AND al_tub = 'T'";
		// }

		$where = '';

		$query = mysql_query("
					SELECT  employee3.emp_id, employee3.name, employee3.position, employee3.current_status, employee3.startdate, employee3.eocdate,  promo_record.promo_company, promo_record.promo_department, promo_record.promo_type
					FROM employee3
					INNER JOIN promo_record ON employee3.record_no = promo_record.record_no
					WHERE employee3.emp_type LIKE 'Promo%' AND name != '' $where
				") or die(mysql_error());

		$data = array();
		while ($row = mysql_fetch_array($query)) {
			$empId = $row['emp_id'];
			$name = utf8_decode(ucwords(strtolower($row['name'])));

			$store = "";
			$ctr = 0;
			$bunit = mysql_query("SELECT bunit_field, bunit_acronym FROM `locate_promo_business_unit`") or die(mysql_error());
			while ($str = mysql_fetch_array($bunit)) {

				$promo = mysql_query("SELECT promo_id FROM `promo_record` WHERE `" . $str['bunit_field'] . "` = 'T' AND emp_id = '$empId'") or die(mysql_error());
				if (mysql_num_rows($promo) > 0) {
					$ctr++;

					if ($ctr == 1) {

						$store = $str['bunit_acronym'];
					} else {

						$store .= ", " . $str['bunit_acronym'];
					}
				}
			}

			if (strtolower($row['current_status']) == "active") {

				$labelC = "btn btn-success btn-xs btn-flat btn-block";
			} else if (strtolower($row['current_status']) == "end of contract" || strtolower($row['current_status']) == "resigned") {

				$labelC = "btn btn-warning btn-xs btn-flat btn-block";
			} else {

				$labelC = "btn btn-danger btn-xs btn-flat btn-block";
			}

			$sub_array = array();
			$sub_array[] = "";
			$sub_array[] = $row['emp_id'];
			$sub_array[] = '<a href="?p=profile&&module=Promo&&com=' . $row['emp_id'] . '" target="_blank">' . ucwords(strtolower($row['name'])) . '</a>';
			$sub_array[] = $store;
			$sub_array[] = $row['promo_department'];
			$sub_array[] = $row['startdate'];
			$sub_array[] = $row['eocdate'];
			$sub_array[] = "<label class='$labelC'>" . $row['current_status'] . "</label>";
			$data[] = $sub_array;
		}

		echo json_encode(array("data" => $data));
	} else if ($_GET['request'] == "update_server") {

		$server = $_POST['server'];
		$newCHK = $_POST['newCHK'];

		for ($i = 0; $i < count($newCHK); $i++) {

			if ($i == 0) {
				$whereIn = "'$newCHK[$i]'";
			} else if ($i < count($newCHK)) {
				$whereIn .= ", '$newCHK[$i]'";
			} else {
				$whereIn = "";
			}
		}

		$sql = mysql_query("SELECT
									employee3.emp_id, emp_no, emp_pins,
									payroll_no, name, startdate, eocdate, emp_type, current_status, 
									position, promo_company, promo_department, al_tag, al_tal, 
									icm, pm, abenson_tag, abenson_icm, cdc, berama, al_tub,
									colc, colm, alta_citta, promo_type   
								FROM employee3, promo_record WHERE employee3.record_no = promo_record.record_no AND employee3.emp_id IN ($whereIn)") or die(mysql_error());
		while ($res = mysql_fetch_array($sql)) {
			$dataE[] = $res;
		}

		if ($server == "talibon_server") {
			include("config_talibon.php");
		} else {
			include("config_tubigon.php");
		}

		for ($x = 0; $x < count($dataE); $x++) {

			$del = mysql_query("DELETE FROM employee3 WHERE emp_id = '" . $dataE[$x]['emp_id'] . "'") or die(mysql_error());
			$ins3 = mysql_query(
				"INSERT INTO `employee3`
					 (
						`emp_id`,`emp_no`,`emp_pins`,`payroll_no`,`name`,`startdate`,`eocdate`,
						`emp_type`,`current_status`,`position`
					 ) VALUES 
					 (
						'" . mysql_real_escape_string($dataE[$x]['emp_id']) . "',
						'" . mysql_real_escape_string($dataE[$x]['emp_no']) . "',
						'" . mysql_real_escape_string($dataE[$x]['emp_pins']) . "',
						'" . mysql_real_escape_string($dataE[$x]['payroll_no']) . "',
						'" . mysql_real_escape_string($dataE[$x]['name']) . "',
						'" . mysql_real_escape_string($dataE[$x]['startdate']) . "',
						'" . mysql_real_escape_string($dataE[$x]['eocdate']) . "',
						'" . mysql_real_escape_string($dataE[$x]['emp_type']) . "',
						'" . mysql_real_escape_string($dataE[$x]['current_status']) . "',
						'" . mysql_real_escape_string($dataE[$x]['position']) . "'
					 )"
			) or die(mysql_error());
			$record_no = mysql_insert_id();

			$del = mysql_query("DELETE FROM promo_record WHERE emp_id = '" . $dataE[$x]['emp_id'] . "'") or die(mysql_error());
			$ins = mysql_query(
				"INSERT INTO `promo_record`
					 (
						`record_no`,`emp_id`,`promo_company`,`promo_department`,`al_tag`,
						`al_tal`,`icm`,`pm`,`abenson_tag`,`abenson_icm`,`cdc`, `berama`,
						`al_tub`,`colc`,`colm`,`alta_citta`,`promo_type`
					 ) VALUES 
					 (
						'" . mysql_real_escape_string($record_no) . "',
						'" . mysql_real_escape_string($dataE[$x]['emp_id']) . "',
						'" . mysql_real_escape_string($dataE[$x]['promo_company']) . "',
						'" . mysql_real_escape_string($dataE[$x]['promo_department']) . "',
						'" . mysql_real_escape_string($dataE[$x]['al_tag']) . "',
						'" . mysql_real_escape_string($dataE[$x]['al_tal']) . "',
						'" . mysql_real_escape_string($dataE[$x]['icm']) . "',
						'" . mysql_real_escape_string($dataE[$x]['pm']) . "',
						'" . mysql_real_escape_string($dataE[$x]['abenson_tag']) . "',
						'" . mysql_real_escape_string($dataE[$x]['abenson_icm']) . "',
						'" . mysql_real_escape_string($dataE[$x]['cdc']) . "',
						'" . mysql_real_escape_string($dataE[$x]['berama']) . "',
						'" . mysql_real_escape_string($dataE[$x]['al_tub']) . "',
						'" . mysql_real_escape_string($dataE[$x]['colc']) . "',
						'" . mysql_real_escape_string($dataE[$x]['colm']) . "',
						'" . mysql_real_escape_string($dataE[$x]['alta_citta']) . "',
						'" . mysql_real_escape_string($dataE[$x]['promo_type']) . "'
					 )"
			) or die(mysql_error());
		}

		mysql_close($con);
		die("success");
	} else if ($_GET['request'] == 'import_file') {

		$datetime = date("Y-m-d H:i:s");
		$department = $_POST['department'];
		if (isset($_FILES['textfile'])) {

			$errors = array();
			$file_name 	= $_FILES['textfile']['name'];
			$file_size 	= $_FILES['textfile']['size'];
			$file_tmp 	= $_FILES['textfile']['tmp_name'];
			$file_ext 	= strtolower(end(explode('.', $_FILES['textfile']['name'])));

			$extensions = array("text", "txt", "csv");

			if (in_array($file_ext, $extensions) === false) {
				die("extension not allowed, please choose a text file.");
			}

			// Check if file already exists
			$target_file = 'vendor/' . basename($file_name);
			if (file_exists($target_file)) {
				die("Sorry, file already exists.");
			}


			if (move_uploaded_file($file_tmp, "vendor/" . $file_name)) {

				$lines = file($target_file); //file in to an array

				$i = 0;
				foreach ($lines as $line) {
					if (in_array($file_ext, array('text', 'txt'))) {

						$data = explode('|', $line);

						if (count($data) != 8) {
							die("Column count doestn't match to the number of fields.");
						}

						$vendor_code = str_replace('"', '', $data[0]);
						$company = str_replace('"', '', $data[1]);
						$address = str_replace('"', '', $data[2]);
						$address2 = str_replace('"', '', $data[3]);
						$city = str_replace('"', '', $data[4]);
						$contact = str_replace('"', '', $data[5]);
						$v_posting_group = str_replace('"', '', $data[6]);
						$vat_reg_no = str_replace('"', '', $data[7]);

						$sql = mysql_query("SELECT COUNT(id) AS exist FROM promo_vendor_lists WHERE vendor_code = '" . $vendor_code . "'") or die(mysql_error());
						$row = mysql_fetch_array($sql);

						if ($row['exist'] > 0) {

							mysql_query("UPDATE promo_vendor_lists
		    		    			SET 
		    		    				`department` = '" . htmlspecialchars($department) . "',
		    		    				`vendor_name` = '" . trim(addslashes($company)) . "',
		    		    				`address` = '" . trim(addslashes($address)) . "',
		    		    				`address2` = '" . trim(addslashes($address2)) . "',
		    		    				`city` = '" . trim(addslashes($city)) . "',
		    		    				`contact` = '" . trim(addslashes($contact)) . "',
		    		    				`v_posting_group` = '" . trim($v_posting_group) . "',
		    		    				`vat_reg_no` = '" . trim($vat_reg_no) . "',
		    		    				`updated_at` = '" . $datetime . "'
		    		    			WHERE
		    		    				`vendor_code` = '" . trim($vendor_code) . "'
		    		    		") or die(mysql_error());
						} else {

							mysql_query("INSERT INTO `promo_vendor_lists`
		    						(
		    							`department`, 
		    							`vendor_code`, 
		    							`vendor_name`, 
		    							`address`, 
		    							`address2`, 
		    							`city`, 
		    							`contact`, 
		    							`v_posting_group`, 
		    							`vat_reg_no`, 
		    							`created_at`
		    						) 
		    					VALUES 
		    						(
		    							'" . htmlspecialchars($department) . "',
		    							'" . trim($vendor_code) . "',
		    							'" . trim(addslashes($company)) . "',
		    							'" . trim(addslashes($address)) . "',
		    							'" . trim(addslashes($address2)) . "',
		    							'" . trim(addslashes($city)) . "',
		    							'" . trim(addslashes($contact)) . "',
		    							'" . trim($v_posting_group) . "',
		    							'" . trim($vat_reg_no) . "',
		    							'" . $datetime . "'
		    					)") or die(mysql_error() . '' . $data[0]);
						}
					} else {

						if ($i > 0) {

							$data = str_getcsv($line);

							if (count($data) != 8) {
								die("Column count doestn't match to the number of fields.");
							}

							$vendor_code = str_replace('"', '', $data[0]);
							$company = str_replace('"', '', $data[1]);
							$address = str_replace('"', '', $data[2]);
							$address2 = str_replace('"', '', $data[3]);
							$city = str_replace('"', '', $data[4]);
							$contact = str_replace('"', '', $data[5]);
							$v_posting_group = str_replace('"', '', $data[6]);
							$vat_reg_no = str_replace('"', '', $data[7]);

							$sql = mysql_query("SELECT COUNT(id) AS exist FROM promo_vendor_lists WHERE vendor_code = '" . $vendor_code . "'") or die(mysql_error());
							$row = mysql_fetch_array($sql);

							if ($row['exist'] > 0) {

								mysql_query("UPDATE promo_vendor_lists
						    			SET 
						    				`department` = '" . htmlspecialchars($department) . "',
						    				`vendor_name` = '" . trim(addslashes($company)) . "',
						    				`address` = '" . trim(addslashes($address)) . "',
						    				`address2` = '" . trim(addslashes($address2)) . "',
						    				`city` = '" . trim(addslashes($city)) . "',
						    				`contact` = '" . trim(addslashes($contact)) . "',
						    				`v_posting_group` = '" . trim($v_posting_group) . "',
						    				`vat_reg_no` = '" . trim($vat_reg_no) . "',
						    				`updated_at` = '" . $datetime . "'
						    			WHERE
						    				`vendor_code` = '" . trim($vendor_code) . "'
						    		") or die(mysql_error());
							} else {

								mysql_query("INSERT INTO `promo_vendor_lists`
										(
											`department`, 
											`vendor_code`, 
											`vendor_name`, 
											`address`, 
											`address2`, 
											`city`, 
											`contact`, 
											`v_posting_group`, 
											`vat_reg_no`, 
											`created_at`
										) 
									VALUES 
										(
											'" . htmlspecialchars($department) . "',
											'" . trim($vendor_code) . "',
											'" . trim(addslashes($company)) . "',
											'" . trim(addslashes($address)) . "',
											'" . trim(addslashes($address2)) . "',
											'" . trim(addslashes($city)) . "',
											'" . trim(addslashes($contact)) . "',
											'" . trim($v_posting_group) . "',
											'" . trim($vat_reg_no) . "',
											'" . $datetime . "'
									)") or die(mysql_error() . '' . $data[0]);
							}
						}
						$i++;
					}
				}

				die("Data has been imported.");
			} else {

				die("Sorry, your file was not uploaded.");
			}
		}
	} else if ($_GET['request'] == "company_list") {

		$companies = mysql_query("SELECT * FROM locate_promo_company ORDER BY pc_name ASC") or die(mysql_error());
		$data = array();
		$no = 1;
		while ($comp = mysql_fetch_array($companies)) {

			$pc_code = $comp['pc_code'];
			$pc_name = $comp['pc_name'];

			$action =  '<a href="javascript:void(0);" id="update_' . $pc_code . '" title="click to update company" class="update_company"><i class="glyphicon glyphicon-pencil"></i> &nbsp;</a>';
			if ($comp['status'] == '1') {

				$action .= '<a href="javascript:void(0)" id="deactivate_' . $pc_code . '" title="click to deactivate company" class="action"><img src="../images/icons/icon-close-circled-20.png" height="17" width="17"></a>';
			} else {

				$action .= '<a href="javascript:void(0)" id="activate_' . $pc_code . '" title="click to activate company" class="action"><img src="../images/icons/icn_active.gif" height="17" width="17"></a>';
			}

			if ($_SESSION['emp_id'] == "06359-2013") {
				$action .= ' <a href="javascript:void(0);" id="delete_' . $pc_code . '" title="click to delete company" class="action"><i class="glyphicon glyphicon-trash text-red"></i></a>';
			}

			$sub_array = array();
			$sub_array[] = $pc_name;
			$sub_array[] = $action;
			$data[] = $sub_array;

			$no++;
		}

		echo json_encode(array("data" => $data));
	} else if ($_GET['request'] == "delete_pc_name") {

		$company_code = $_POST['company_code'];
		$delete = mysql_query("DELETE FROM locate_promo_company WHERE pc_code = '$company_code'") or die(mysql_error());
		if ($delete) {

			die("success");
		} else {

			die("failure");
		}
	} else if ($_GET['request'] == 'update_company_form') {

		$company_code = $_GET['company_code'];
		$company = mysql_query("SELECT pc_name FROM locate_promo_company WHERE pc_code = '$company_code'") or die(mysql_error());
		$comp = mysql_fetch_array($company);
	?>
		<div class="form-group">
			<label>Company</label>
			<div class="input-group">
				<input type="hidden" name="company_code" value="<?= $company_code ?>">
				<input class="form-control" type="text" name="company" value="<?= $comp['pc_name'] ?>" style="text-transform: uppercase; border-color: rgb(204, 204, 204);" autocomplete="off" onkeyup="inputField(this.name)">
				<span class="input-group-addon"><i class="fa fa-bank"></i></span>
			</div>
		</div>
	<?php
	} else if ($_GET['request'] == 'update_company') {

		$fetch = $_POST;

		$check = mysql_query("SELECT COUNT(pc_code) AS exist FROM locate_promo_company WHERE pc_name = '" . strtoupper(mysql_real_escape_string($fetch['company'])) . "'") or die(mysql_error());
		$exist = mysql_fetch_array($check);
		if ($exist['exist'] > 0) {

			mysql_query("UPDATE locate_promo_company SET updated_at = '" . date('Y-m-d') . "' WHERE pc_code = '" . $fetch['company_code'] . "'") or die(mysql_error());
			die('exist');
		} else {

			mysql_query("UPDATE locate_promo_company SET pc_name = '" . strtoupper(mysql_real_escape_string($fetch['company'])) . "', updated_at = '" . date('Y-m-d') . "' WHERE pc_code = '" . $fetch['company_code'] . "'") or die(mysql_error());
			die('success');
		}
	} else if ($_GET['request'] == 'add_company') {

		$fetch = $_POST;

		$check = mysql_query("SELECT COUNT(pc_code) AS exist FROM locate_promo_company WHERE pc_name = '" . strtoupper(mysql_real_escape_string($fetch['company'])) . "'") or die(mysql_error());
		$exist = mysql_fetch_array($check);
		if ($exist['exist'] > 0) {
			die('exist');
		} else {

			mysql_query("INSERT INTO locate_promo_company (pc_name, created_at) VALUES ('" . strtoupper(mysql_real_escape_string($fetch['company'])) . "', '" . date('Y-m-d') . "')") or die(mysql_error());
			die('success');
		}
	} else if ($_GET['request'] == 'company_status') {

		$fetch = $_POST;

		if ($fetch['action'] == "activate") {

			mysql_query("UPDATE locate_promo_company SET status = '1', updated_at = '" . date('Y-m-d') . "' WHERE pc_code = '" . $fetch['company_code'] . "'") or die(mysql_errno());
		} else {

			mysql_query("UPDATE locate_promo_company SET status = '0', updated_at = '" . date('Y-m-d') . "' WHERE pc_code = '" . $fetch['company_code'] . "'") or die(mysql_errno());
		}
		die('success');
	} else if ($_GET['request'] == 'agency_status') {

		$fetch = $_POST;

		if ($fetch['action'] == "activate") {

			mysql_query("UPDATE $datab2.promo_locate_agency SET status = '1' WHERE agency_code = '" . $fetch['agency_code'] . "'") or die(mysql_errno());
		} else {

			mysql_query("UPDATE $datab2.promo_locate_agency SET status = '0' WHERE agency_code = '" . $fetch['agency_code'] . "'") or die(mysql_errno());
		}
		die('success');
	} else if ($_GET['request'] == 'add_product') {

		$fetch = $_POST;

		$check = mysql_query("SELECT COUNT(id) AS exist FROM locate_promo_product WHERE product = '" . strtoupper(mysql_real_escape_string($fetch['product'])) . "'") or die(mysql_error());
		$exist = mysql_fetch_array($check);
		if ($exist['exist'] > 0) {
			die('exist');
		} else {

			mysql_query("INSERT INTO locate_promo_product (product, status, created_at) VALUES ('" . strtoupper(mysql_real_escape_string($fetch['product'])) . "','1','" . date('Y-m-d H:i:s') . "')") or die(mysql_error());
			die('success');
		}
	} else if ($_GET['request'] == 'product_lists') {

		$products = mysql_query("SELECT * FROM locate_promo_product ORDER BY product ASC") or die(mysql_error());
		$data = array();
		$no = 1;
		while ($prod = mysql_fetch_array($products)) {

			$id = $prod['id'];
			$product = $prod['product'];

			$action =  '<a href="javascript:void(0);" id="update_' . $id . '" title="click to update product" class="update_product"><i class="glyphicon glyphicon-pencil"></i> &nbsp;</a>';
			if ($prod['status'] == '1') {

				$action .= '<a href="javascript:void(0)" id="deactivate_' . $id . '" title="click to deactivate product" class="action"><img src="../images/icons/icon-close-circled-20.png" height="17" width="17"></a>';
			} else {

				$action .= '<a href="javascript:void(0)" id="activate_' . $id . '" title="click to activate product" class="action"><img src="../images/icons/icn_active.gif" height="17" width="17"></a>';
			}

			if ($_SESSION['emp_id'] == "06359-2013") {
				$action .= ' <a href="javascript:void(0);" id="delete_' . $id . '" title="click to delete product" class="action"><i class="glyphicon glyphicon-trash text-red"></i></a>';
			}

			$sub_array = array();
			$sub_array[] = $product;
			$sub_array[] = $action;
			$data[] = $sub_array;

			$no++;
		}

		echo json_encode(array("data" => $data));
	} else if ($_GET['request'] == 'update_product_form') {

		$product = mysql_query("SELECT id, product FROM locate_promo_product WHERE id = '" . $_GET['product_id'] . "'") or die(mysql_error());
		$prod = mysql_fetch_array($product);

	?>
		<input type="hidden" name="product_id" value="<?= $prod['id'] ?>">
		<input type="text" name="product_name" class="form-control" value="<?= $prod['product'] ?>">
	<?php
	} else if ($_GET['request'] == 'update_product') {

		$fetch = $_POST;

		$check = mysql_query("SELECT COUNT(id) AS exist FROM locate_promo_product WHERE product = '" . strtoupper(mysql_real_escape_string($fetch['product'])) . "'") or die(mysql_error());
		$exist = mysql_fetch_array($check);
		if ($exist['exist'] > 0) {
			die('exist');
		} else {

			mysql_query("UPDATE locate_promo_product SET product = '" . strtoupper(mysql_real_escape_string($fetch['product'])) . "', updated_at = '" . date('Y-m-d H:i:s') . "' WHERE id = '" . $fetch['product_id'] . "'") or die(mysql_error());
			die('success');
		}
	} else if ($_GET['request'] == 'product_status') {

		$fetch = $_POST;

		if ($fetch['action'] == "activate") {

			mysql_query("UPDATE locate_promo_product SET status = '1' WHERE id = '" . $fetch['product_id'] . "'") or die(mysql_errno());
		} else {

			mysql_query("UPDATE locate_promo_product SET status = '0' WHERE id = '" . $fetch['product_id'] . "'") or die(mysql_errno());
		}
		die('success');
	} else if ($_GET['request'] == 'delete_product') {

		$product_id = $_POST['product_id'];
		$delete = mysql_query("DELETE FROM locate_promo_product WHERE id = '$product_id'") or die(mysql_error());
		if ($delete) {

			die("success");
		} else {

			die("failure");
		}
	} else if ($_GET['request'] == 'company_product') {

		$companies = mysql_query("SELECT * FROM promo_company_products") or die(mysql_error());
		$data = array();
		while ($comp = mysql_fetch_array($companies)) {

			$company = $comp['company'];
			$product = $comp['product'];

			$action =  '<i id="delete_' . $comp['id'] . '" class="fa fa-lg fa-trash text-danger delete_product"></i>';

			$sub_array = array();
			$sub_array[] = $company;
			$sub_array[] = $product;
			$sub_array[] = $action;
			$data[] = $sub_array;
		}

		echo json_encode(array("data" => $data));
	} else if ($_GET['request'] == 'selectCompany') {
	?>
		<div class="form-group">
			<label>Company Name:</label>
			<select name="setupan_product" class="form-control" onchange="select_product_list(this.value)">
				<option value="">Select Company</option>
				<?php

				$companies = mysql_query("SELECT * FROM locate_promo_company ORDER BY pc_name ASC") or die(mysql_error());
				while ($comp = mysql_fetch_array($companies)) {

					echo '<option value="' . $comp['pc_name'] . '">' . $comp['pc_name'] . '</option>';
				}
				?>
			</select>
		</div>
	<?php
	} else if ($_GET['request'] == 'setupan_company_product') {

	?>
		<div class="table-responsive">
			<table id="dt_products" class="table table-bordered table-hover" width="100%">
				<thead>
					<tr>
						<th>Product Name</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php

					$company = $_POST['company'];
					$fetch_data = mysql_query("SELECT product FROM locate_promo_product ORDER BY product ASC") or die(mysql_error());

					$data = array();
					$x = 1;
					while ($row = mysql_fetch_array($fetch_data)) {

						$sql = mysql_query("SELECT COUNT(id) AS numExist FROM promo_company_products WHERE company = '" . mysql_real_escape_string($company) . "' AND product = '" . mysql_real_escape_string(trim($row['product'])) . "'") or die(mysql_error());
						$com = mysql_fetch_array($sql);
						if ($com['numExist'] > 0) {

							$action =  '<input type="checkbox" name="" class="chkP_' . $x . '" value="' . $row['product'] . '" checked="" onclick="chkProduct(' . $x . ')">';
							echo '<input type="checkbox" name="chkProd[]" class="chkAP_' . $x . '" value="' . $row['product'] . '" checked="" style="display:none;">';
						} else {

							$action =  '<input type="checkbox" name="" class="chkP_' . $x . '" value="' . $row['product'] . '" onclick="chkProduct(' . $x . ')">';
							echo '<input type="checkbox" name="chkProd[]" class="chkAP_' . $x . '" value="' . $row['product'] . '" style="display:none;">';
						}

						echo '
								<tr>
									<td>' . $row['product'] . '</td>
									<td>' . $action . '</td>
								</tr>
							';

						$x++;
					}
					?>
				</tbody>
			</table>
		</div>
		<script type="text/javascript">
			$("table#dt_products").DataTable({

				"destroy": true,
				"paging": false
			});
		</script>
	<?php
	} else if ($_GET['request'] == 'insert_update_company_product') {

		$company = $_POST['company'];
		$newCHK  = $_POST['newCHK'];

		$delete = mysql_query("DELETE FROM promo_company_products WHERE company = '" . mysql_real_escape_string($company) . "'") or die(mysql_error());
		if ($delete) {

			$chk = explode("|_", $newCHK);

			for ($i = 0; $i < count($chk) - 1; $i++) {

				$sql = mysql_query("INSERT INTO promo_company_products
								(company, product, created_at)
							VALUES
								('" . mysql_real_escape_string($company) . "', '" . mysql_real_escape_string($chk[$i]) . "', '" . date('Y-m-d H:i:s') . "')
							") or die(mysql_error());
			}

			die("success");
		} else {

			die("failure");
		}
	} else if ($_GET['request'] == 'delete_company_product') {

		$id = $_POST['product_id'];
		$delete = mysql_query("DELETE FROM promo_company_products WHERE id = '$id'") or die(mysql_error());
		if ($delete) {

			die("success");
		} else {

			die("failure");
		}
	} else if ($_GET['request'] == 'loadProducts') {

		$data = $_POST;
		$company = mysql_query("SELECT pc_name FROM locate_promo_company WHERE pc_code = '" . $data['company'] . "'") or die(mysql_error());
		$comp = mysql_fetch_array($company);

		$emp_products = explode("|", $data['product']);
	?>
		<select name="product_select[]" class="form-control select2" multiple="multiple">
			<?php
			$products = mysql_query("SELECT product FROM promo_company_products WHERE company = '" . mysql_real_escape_string($comp['pc_name']) . "'") or die(mysql_error());
			while ($prod = mysql_fetch_array($products)) {
			?>
				<option value="<?= $prod['product'] ?>" <?php if (in_array($prod['product'], $emp_products)) {
															echo "selected=''";
														} ?>><?= $prod['product'] ?></option><?php
																							} ?>
		</select>
		<script type="text/javascript">
			$('.select2').select2();
			$("span.select2").css("width", "100%");
		</script>
	<?php
	} else if ($_GET['request'] == 'load_products') {

		$company_code = $_GET['company_code'];
		$company = mysql_query("SELECT pc_name FROM locate_promo_company WHERE pc_code = '" . $company_code . "'") or die(mysql_error());
		$comp = mysql_fetch_array($company);
	?>
		<select name="product_select[]" class="form-control select2" multiple="multiple">
			<?php
			$products = mysql_query("SELECT product FROM promo_company_products WHERE company = '" . mysql_real_escape_string($comp['pc_name']) . "'") or die(mysql_error());
			while ($prod = mysql_fetch_array($products)) {
			?>
				<option value="<?= $prod['product'] ?>"><?= $prod['product'] ?></option><?php
																					}
																						?>
		</select>
		<script type="text/javascript">
			$('.select2').select2();
			$("span.select2").css("width", "100%");
		</script>
	<?php
	} else if ($_GET['request'] == 'loadCutoffs') {

		$statCut = $_POST['statCut'];

		$cutoffs = mysql_query("SELECT startFC, endFC, startSC, endSC, statCut FROM timekeeping.promo_schedule WHERE remark = 'active'") or die(mysql_error());
		while ($co = mysql_fetch_array($cutoffs)) {

			$endFC = ($co['endFC'] != '') ? $co['endFC'] : 'last';
			if ($statCut == $co['statCut']) {

				echo '<option value="' . $co['statCut'] . '" selected>' . $co['startFC'] . '-' . $endFC . ' / ' . $co['startSC'] . '-' . $co['endSC'] . '</option>';
			} else {

				echo '<option value="' . $co['statCut'] . '">' . $co['startFC'] . '-' . $endFC . ' / ' . $co['startSC'] . '-' . $co['endSC'] . '</option>';
			}
		}
	}

	?>