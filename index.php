<?php

session_start();
require_once("connection.php");
date_default_timezone_set("Asia/Manila");
mysql_set_charset("UTF-8");

if (!isset($_SESSION['username'])) {
	header("location: ../index.php");
}

if ($_SESSION['usertype'] == "nesco") {
} else {

	if ($_SESSION['type'] != 'placement' && $_SESSION['type'] != 'administrator') {
		header("location: ../index.php");
	}
}

$loginId = $_SESSION['emp_id'];
$process = "";
$module = "";
$dashboard = "active";

// location
$hrLocation = mysql_query("SELECT company_code, bunit_code, dept_code FROM `employee3` WHERE emp_id = '$loginId' AND current_status = 'Active'") or die(mysql_error());
$hr = mysql_fetch_array($hrLocation);

$ccHr = $hr['company_code'];
$bcHr = $hr['bunit_code'];
$dcHr = $hr['dept_code'];

if ($ccHr != "07") {
	$hrCode = 'asc';
} else {

	$hrCode = 'cebo';
}

// !-- PLEASE DONT DELETE --!
if ($loginId == "06359-2013") {

	$skinClass = "skin-blue-light";
} else {

	$skinClass = "skin-blue-light";
	// $skinClass = "skin-black-light";
}

$pieColor = array('#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de', '#6db00a', '#6d0015', '#6648ae', '#cc8754', '#7a0a01', '#d42cc2');

$process = @$_GET['p'];

if (!empty($_GET['p']) && !empty($_GET['module'])) {

	$module = $_GET['module'];
	$dashboard = "";

	$query = mysql_query("SELECT submenu_name, pages FROM `promo_menu` WHERE menu_name = '$module' AND process = '$process'") or die(mysql_error());
	$fetch = mysql_fetch_array($query);

	$template = $fetch['pages'];
	$subMenu  = $fetch['submenu_name'];

	if ($process == "terminationReportList") {

		$template = "reports/termination_of_contract_list.php";
		$subMenu  = "List of End of Contract";
	} else if ($process == "dutyScheduleReportList") {

		$template = "reports/dutyScheduleReport_list.php";
		$subMenu  = "List of Duty Schedule";
	} else if ($process == "profile") {

		$template = "promo/employee_details.php";
		$subMenu  = "";
	} else if ($process == "updateRecordNo") {

		$template = "promo/updatePromoRecordNo.php";
		$subMenu  = "";
	} else if ($process == "processRenewal") {

		$template = "contract/processRenewal.php";
		$subMenu  = "";
	} else if ($process == "eocList") {

		$template = "contract/eocList.php";
		$subMenu  = "End of Contract List";
	} else if ($process == "changeAccount") {

		$template = "promo/changeAccount.php";
		$subMenu = "Change Account Details";
	} else if ($process == "transferOutletNow") {

		$template = "outlet/transferOutletNow.php";
		$subMenu = "Transfer Details";
	} else if ($process == "newPromo") {

		$template = "promo/newPromo.php";
		$subMenu = "New Promo";
	} else if ($process == "birthdayToday") {

		$template = "promo/birthdayToday.php";
		$subMenu = "Birthday Today";
	} else if ($process == "failedEpas") {

		$template = "contract/failedEpas.php";
		$subMenu = "Failed Epas";
	} else if ($process == "statistics") {

		$template = "reports/statistics.php";
		$subMenu = "Statistics";
	} else if ($process == "dueContract") {

		$template = "reports/dueContract.php";
		$subMenu = "Due Contract";
	} else if ($process == "aboutUs") {

		$template = "promo/aboutUs.php";
		$subMenu = "About Us";
	}
} else {

	$template = 'home.php';
	$title = 'Promo | Dashboard';

	if ($process == "import") {

		$template = "import/vendor_masterfile.php";
		$subMenu  = "Import Vendor Masterfile";
	}
}
include("pages/frame.php");
