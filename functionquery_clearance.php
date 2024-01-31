<?php
session_start();
if (!isset($_SESSION['username'])) {
	header("Location: ../placement/");
}

include("connection.php");
date_default_timezone_set('Asia/Manila');
mysql_set_charset("UTF-8");

function getPromoStore($empid, $promotype)
{
	$store  	= array();
	$cc 		= array();
	$bc 		= array();

	$bunit  	= mysql_query("SELECT bunit_field,bunit_name, cc, bc FROM `locate_promo_business_unit`") or die(mysql_error());
	while ($str = mysql_fetch_array($bunit)) {
		$promo = mysql_query("SELECT promo_id FROM `promo_record` WHERE `" . $str['bunit_field'] . "` = 'T' AND emp_id = '$empid'") or die(mysql_error());
		if (mysql_num_rows($promo) > 0) {
			array_push($store, $str['bunit_name']);
			array_push($cc, $str['cc']);
			array_push($bc, $str['bc']);
		}
	}
	return $store;
}

function getStoreClearance($store)
{
	switch ($store) {
		case 'ASC: MAIN':
			$clearance_field = "asc_clearance";
			break;
		case 'ISLAND CITY MALL':
			$clearance_field = "icm_clearance";
			break;
		case 'PLAZA MARCELA':
			$clearance_field = "pm_clearance";
			break;
		case 'ALTA CITTA':
			$clearance_field = "alta_Clearance";
			break;
		case 'ALTURAS TALIBON':
			$clearance_field = "tal_clearance";
			break;
		case 'ALTURAS TUBIGON':
			$clearance_field = "tub_clearance";
			break;
		case 'CDC':
			$clearance_field = "cdc_clearance";
			break;
	}
	return $clearance_field;
}



if ($_GET['request'] == "div_secureclearance") {
	/** Note: Secure clearance and after that status will change to 
	RESIGNED (UNCLEARED) or END OF CONTRACT (UNCLEARED)
	1.) check emptype if NESCO OR AE
		IF NESCO print NESCO CLEARANCE
	 **/ ?>

	<div id='div_secureclearance'>
		<h4> SECURE CLEARANCE </h4>

		<form action='' name='printClearance' id='printClearance_form' method="POST" enctype="multipart/form-data">

			<div class="form-group">
				<label> <span class='rqd'> * </span> Employee Name </label>
				<div class="input-group">
					<input type="text" required name="empid" id='empidClearance' onkeyup="namesearch(this.value)" class="form-control" placeholder="Lastname, Firstname" value="" autocomplete="off" required="">
					<span class="input-group-btn">
						<button class="btn btn-info" name="search">Search &nbsp;<i class="glyphicon glyphicon-search"></i></button>
					</span>
				</div>
				<div class="search-results" style="display:none;"></div>
			</div>

			<div class="form-group">
				<label> <span class='rqd'> * </span> Promo Type </label>
				<input type='text' name="promotype" id="promotype" class="form-control" readonly required>
			</div>

			<div class="form-group" id='formstore'>
				<label> <span class='rqd'> * </span> Store </label>
				<select name="store" class="form-control" disabled>
					<option value="">Select Store</option>
				</select>
			</div>

			<div class="form-group">
				<label> <span class='rqd'> * </span> Reason for asking Clearance </label>
				<select required class="form-control" id='reason' name='reason' onchange='getRL(this.value)' disabled>
					<option value=''>Select Reason</option>
					<option value="V-Resigned"> VOLUNTARY RESIGNATION FROM EMPLOYMENT WITH THE COMPANY </option>
					<option value="Ad-Resigned"> ADVISED TO RESIGNED FROM EMPLOYMENT WITH THE COMPANY </option>
					<option value="Termination"> TERMINATION OF CONTRACT FROM THE COMPANY </option>
					<option value="Deceased"> DECEASED </option>
				</select>
			</div>

			<div class="non_deceased_form"> </div>

			<div class="deceased_form"> </div>

			<input type="submit" class="btn btn-primary" value='Submit'> <br><br>
			<i> Note: <span class='rqd'> * </span> Required fields. </i> <br>

		</form>
	</div>

	<link rel="stylesheet" type="text/css" media="all" href="../css/jquery-ui.css" />
	<script type="text/javascript" src="../jquery/jquery-latest.min.js"></script>
	<script type="text/javascript" src="../jquery/jquery-ui.js"></script>

	<script>
		$(function() { // minDate: new Date(), //"yy-mm-dd"					

		});
	</script>

<?php
} else if ($_GET['request'] == "div_uploadclearance") { ?>
	<div id='div_uploadclearance'>
		<h4> UPLOAD CLEARANCE & CHANGE STATUS </h4>

		<form action='' name='uploadSignedClearance' id='uploadSignedClearance' method="POST" enctype="multipart/form-data">

			<div class="form-group">
				<label> <span class='rqd'> * </span> Employee Name </label>
				<div class="input-group">
					<input type="text" name="empid" id='empid' onkeyup="namesearch_2(this.value)" class="form-control" placeholder="Search Employee (Emp. ID, Lastname or Firstname)" value="" autocomplete="off" required="">
					<span class="input-group-btn">
						<button class="btn btn-info" name="search">Search &nbsp;<i class="glyphicon glyphicon-search"></i></button>
					</span>
				</div>
				<div class="search-results" style="display:none;"></div>
			</div>

			<div class="form-group">
				<label> <span class='rqd'> * </span> Promo Type </label>
				<input type='text' name="promotype" id="promotype" class="form-control" readonly required>
			</div>

			<div class="form-group" id='formstore'>
				<label> <span class='rqd'> * </span> Store </label>
			</div>

			<button class="btn btn-primary btn-xs" onclick="get_epas()">Browse EPAS </button> <br><br>
			<div id='showEpas'></div>

			<div class="form-group">
				<label> <span class='rqd'> * </span> Remarks </label>
				<textarea required name="remarks" id="remarks" cols="47" class="form-control" rows="2"></textarea>
			</div>

			<div class="form-group">
				<label> <span class='rqd'> * </span> Signed Clearance (Scanned) </label>
				<input type="file" required accept="image/*" name="clearance" id="clearance" class="btn btn-default" onchange="check(this.id,'imgclearance')" size="50">
			</div>

			<input type="submit" class="btn btn-primary" id='submit_printclearance_btn' disabled value='Submit'>
		</form>

		<br><i> Note:
			<br><span class='rqd'> * </span> Required fields.
			<br>EPAS grade is a REQUIREMENT. </b> </i> <br><br>

	</div>
<?php
} else if ($_GET['request'] == "div_clearanceprocessflow") { ?>
	<style type="text/css">
		#csubmission {
			display: none;
		}
	</style>

	<h4> PROCESS FLOW </h4>
	<embed src='images/clearance_process.pdf' style="height:450px;width:100%;"></embed>
<?php
} else if ($_GET['request'] == "div_listofwhosecure") // clearance
{
	if (!empty($_POST['year'])) {
		$year = $_POST['year'];
	} else {
		$year = date('Y');
	}

	$query = mysql_query("SELECT * FROM `secure_clearance_promo` WHERE YEAR(date_added)='$year'");

	$yearOpt = array();
	$yearSel = mysql_query("SELECT DISTINCT YEAR(date_added) AS years FROM `secure_clearance_promo` ORDER BY years DESC");
	while ($yearRes = mysql_fetch_array($yearSel)) {
		if ($yearRes['years'] != 0) {
			$yearOpt[] = $yearRes['years'];
		}
	}

	echo '<div class="row">
				<div class="col-md-5"> <h4> EMPLOYEE LIST </h4>  </div>
				<div class="col-md-5"> </div>
				<div class="col-md-2" id="loader"><h5>
					Filter Year <select name="year" onchange="filter_year(this.value)">';
	foreach ($yearOpt as $key => $value) {
		$selected = ($year == $value) ? "selected" : "";
		echo "<option value='$value' $selected> $value </option>";
	}
	echo '
					</select>
				</h5></div>		
			</div>';

	echo
	"<table class='table table-bordered' id='secureclearance_table' style='font-size:13px'>
			<thead>
				<tr>
					<th> NAME </th>
					<th> SECURE </th>
					<th> EFFECTIVE </th>												
					<th> CUR_STATUS </th>
					<th> C_STAT </th>
					<th> TYPE </th>
					<th> REASON </th>
					<th> ACTION </th>
				</tr>
			</thead>
			<tbody>";

	while ($row = mysql_fetch_array($query)) {
		$status 	= $nq->getOneField("current_status", "employee3", " emp_id = '$row[emp_id]'");
		$substatus 	= "(" . $nq->getOneField("sub_status", "employee3", " emp_id = '$row[emp_id]'") . ")";

		$query_d 	= mysql_query("SELECT * FROM `secure_clearance_deceased`  WHERE emp_id = '$row[emp_id]' ");
		$row_d 		= mysql_fetch_array($query_d);

		$dateeffective = $nq->getOneField("date_effectivity", "secure_clearance_promo_details", " scpr_id = '$row[scpr_id]' ");
		$dateeffective = $nq->changeDateFormat('m/d/y', $dateeffective);

		if ($status == "Active") {
			$status = "<span class='label label-success'> Active $substatus </span>";
		} else {
			if ($substatus == "(Uncleared)") {
				$status = "<span class='label label-danger'> $status $substatus</span>";
			} else {
				$status = "<span class='label label-warning'> $status $substatus</span>";
			}
		}


		//TO SHORTEN STORE DISPLAY
		// switch ($row['store']) {
		// 	case 'ASC: MAIN':
		// 		$newstore = 'AMALL';
		// 		break;
		// 	case 'ISLAND CITY MALL':
		// 		$newstore = 'ICM';
		// 		break;
		// 	case 'PLAZA MARCELA':
		// 		$newstore = 'PM';
		// 		break;
		// 	default:
		// 		$newstore = $row['store'];
		// 		break;
		// }

		echo "<tr>
					<td> <a href='?p=profile&&module=Promo&&com=$row[emp_id]'> " . ucwords(strtolower($nq->getEmpName($row['emp_id']))) . " </a> </td>					
					<td> " . $nq->changeDateFormat('m/d/y', $row['date_added']) . " </td>
					<td> $dateeffective </td>															
					<td> $status </td>				
					<td> $row[status] </td>
					<td> $row[promo_type] </td>
					<td> $row[reason] </td>
					<td> <button class='btn btn-primary btn-xs' data-toggle='modal' data-target='#modalDetails' onclick=showDetails('$row[emp_id]','$row[scpr_id]')> View Details </button> </td>
			</tr>";
	}

	echo "
			</tbody>
		</table>"; ?>

	<link href='../datatables/jquery.dataTables.css' rel='stylesheet' />
	<script src="../datatables/jquery-1.11.1.min.js" type="text/javascript"></script>
	<script src="../datatables/jquery.dataTables.min.js" type="text/javascript"></script>

	<script>
		$(document).ready(function() {
			$('#secureclearance_table').DataTable();
		});
	</script>

<?php

}
//REPRINT GENERATED CLEARANCE
else if ($_GET['request'] == "div_reprintclearance") // clearance
{
	echo "<h4> REPRINT CLEARANCE </h4>";
	$query = mysql_query("SELECT * FROM `secure_clearance` ");

?>
	<div class="form-group">
		<label> <span class='rqd'> * </span> Employee Name </label>
		<div class="input-group">
			<input type="text" name="empid" id='empid' onkeyup="namesearch_reprint(this.value)" class="form-control" placeholder="Search Employee (Emp. ID, Lastname or Firstname)" value="" autocomplete="off" required="">
			<span class="input-group-btn">
				<button class="btn btn-info" name="search">Search &nbsp;<i class="glyphicon glyphicon-search"></i></button>
			</span>
		</div>
		<div class="search-results" style="display:none;"></div>
	</div>

	<input type="hidden" id="scid" name="scid">

	<div class="form-group">
		<label> <span class='rqd'> * </span> Reason for Clearance Reprint </label>
		<textarea required name="reasonreprint" id="reasonreprint" cols="47" class="form-control" rows="2"></textarea>
	</div>

	<div class="form-group">
		<label> <span class='rqd'> * </span> Promo Type </label>
		<input type='text' name="promotype" id="promotype" class="form-control" readonly required>
	</div>

	<div class="form-group" id='formstore'>
		<label> <span class='rqd'> * </span> Store </label>
	</div>

	<input type="submit" class="btn btn-primary" id='submit_reprintclearance_btn' onclick="reprint_clearance()" value='Submit'>
	<input type="submit" class="btn btn-primary" onclick="get_reason()" id='view_reprintclearance_btn' disabled value='View Clearance'>
	<br><br>
<?php
} else if ($_GET['request'] == "findEmployeeforClearanceReprint") {

	$key = mysql_real_escape_string($_POST['str']);
	$val = "";
	$empname = mysql_query("SELECT employee3.`emp_id`, `name`,`scpr_id`,`promo_type` FROM `employee3`
								INNER JOIN secure_clearance_promo
								ON employee3.emp_id = secure_clearance_promo.emp_id
								WHERE secure_clearance_promo.status = 'Pending'							
								AND (name like '%$key%' or employee3.emp_id = '$key')  order by name limit 10") or die(mysql_error());
	while ($n = mysql_fetch_array($empname)) {
		$empId = $n['emp_id'];
		$name  = $n['name'];
		$scid  = $n['scpr_id'];
		$type  = $n['promo_type'];
		//$generatedclearance = $n['generated_clearance']; ,`generated_clearance` 

		if ($val != $empId) {
			echo "<a class = \"nameFind\" href = \"javascript:void\" onclick='getEmpId_reprint(\"$empId*$name*$scid*$type\")'>[ " . $empId . " ] = " . $name . "</a></br>";
		} else {
			echo "<a class = \"afont\" href = \"javascript:void\">No Result Found</a></br>";
		}
	}
} else if ($_GET['request'] == "findEmployeeforUploadSignedClearance") {
	/*(current_status = 'Active' or current_status = 'End of Contract' or current_status = 'Resigned' or current_status = 'V-Resigned' or current_status = 'Ad-Resigned' or current_status = 'Retrenched' or current_status = 'Retired' or current_status = 'Deceased') */
	$key = mysql_real_escape_string($_POST['str']);
	$val = "";
	$empname = mysql_query("SELECT employee3.`emp_id`, `name`,`current_status`, `promo_type` FROM `employee3`
							INNER JOIN secure_clearance_promo
							ON employee3.emp_id = secure_clearance_promo.emp_id
							WHERE current_status 
							IN ('Active','End of Contract','Resigned','V-Resigned','Ad-Resigned','Retrenched','Retired','Deceased')	
							AND (sub_status != 'Cleared')
							AND (name like '%$key%' or employee3.emp_id = '$key') AND status='Pending' order by name limit 10") or die(mysql_error());


	while ($n = mysql_fetch_array($empname)) {
		$empId = $n['emp_id'];
		$name  = $n['name'];
		$status = $n['current_status'];
		$type  = $n['promo_type'];

		if ($val != $empId) {
			echo "<a class = \"nameFind\" href = \"javascript:void\" onclick='getEmpId_2(\"$empId*$name*$status*$type\")'>[ " . $empId . " ] = " . $name . "</a></br>";
		} else {
			echo "<a class = \"afont\" href = \"javascript:void\">No Result Found</a></br>";
		}
	}
} else if ($_GET['request'] == "findEmployeeforClearance") {

	$key = mysql_real_escape_string($_POST['str']);
	$val = "";
	$empname = mysql_query("SELECT employee3.`emp_id`, `name`, `promo_type` FROM `employee3`
								INNER JOIN promo_record
								ON employee3.emp_id = promo_record.emp_id
								WHERE (current_status = 'Active' || current_status ='End of Contract' || current_status='V-Resigned' || current_status='Ad-Resigned' )
								AND (emp_type = 'Promo' or emp_type = 'Promo-NESCO') 
								AND (name like '%$key%' or employee3.emp_id = '$key')  order by name limit 10") or die(mysql_error());
	//or current_status = 'End of Contract'
	while ($n = mysql_fetch_array($empname)) {
		$empId = $n['emp_id'];
		$name  = utf8_encode($n['name']);
		$type  = $n['promo_type'];

		if ($val != $empId) {
			echo "<a class = \"nameFind\" href = \"javascript:void\" onclick=\"getEmpId('$empId*$name*$type')\">[ " . $empId . " ] = " . $name . "</a></br>";
		} else {
			echo "<a class = \"afont\" href = \"javascript:void\">No Result Found</a></br>";
		}
	}
} else if ($_GET['request'] == "check_employee_secure_clearance") {
	//checking so that the employee cannot secure clearance after asking just recently
	$empid 				= trim($_POST['empId']);

	$query = mysql_query("SELECT emp_id from secure_clearance where emp_id = '$empid' and status = 'Pending' ");

	if (mysql_num_rows($query) == 0) {
		echo "success";
	} else {
		echo "error";
	}
} else if ($_GET['request'] == "get_promo_store") {
	$empid 		= $_POST['empId'];
	$promotype 	= $_POST['type'];
	//get the list of stores for promo
	$store 		= getPromoStore($empid, $promotype);

	$sessionuser = $_SESSION['emp_id'];
	$usercc   	= $nq->getOneField("company_code", "employee3", "emp_id = '$sessionuser' ");
	$userbc   	= $nq->getOneField("bunit_code", "employee3", "emp_id = '$sessionuser' ");

	$scprid 		= $nq->getOneField("scpr_id", "secure_clearance_promo", " emp_id = '$empid' and promo_type = '$promotype' and status = 'Pending'");
	if (isset($scprid)) {
		$onchange = "onchange='getotherdata()'";
	} else {
		$onchange = "";
	}

	echo "<label>  <span class='rqd'> * </span> Store </label>
	<select class='form-control' name='store' required $onchange >
		<option value=''>Select Store</option>";

	for ($i = 0; $i < count($store); $i++) {
		if ($promotype == "STATION") {
			echo "<option value='$store[$i]' selected> $store[$i] </option>";
		} else {

			if ($usercc == $cc[$i] && $userbc == $bc[$i]) {
				echo "<option value='$store[$i]' selected > $store[$i] $cc[$i] $bc[$i] </option>";
			} else {

				//check store if na secure na
				$stores = $nq->getOneField("store", "secure_clearance_promo_details", "emp_id= '$empid' and store='$store[$i]' and clearance_status = 'Pending'");
				// if($stores != $store[$i]){		
				// 	echo "<option value='$store[$i]' disabled> $store[$i] - DONE </option>";
				// }else{
				// 	echo "<option value='$store[$i]'> $store[$i] </option>";
				// }

				//updated 11102022 by miri
				if ($stores == "") {
					echo "<option value='$store[$i]'> $store[$i] </option>";
				} else if ($stores == $store[$i]) {
					echo "<option value='$store[$i]' disabled> $store[$i] - DONE </option>";
				} else {
					echo "<option value='$store[$i]'> $store[$i] </option>";
				}
			}
		}
	}
	echo "</select>";

?>
	<script type="text/javascript">
		var reason;
		var dateeffective;

		function get_date_effectivity() {
			var promotype = $("[name='promotype']").val();
			var empid = $("[name='empid']").val();
			empid = empid.split('*');
			empid = empid[0].trim();

			$.ajax({
				type: "POST",
				url: "functionquery_clearance.php?request=get_date_effectivity",
				data: {
					promotype: promotype,
					empid: empid
				},
				success: function(data) {
					data = data.split('*');
					reason = data[0];
					dateeffective = data[1];
				}
			});
		}

		function getotherdata() {
			get_date_effectivity();

			setTimeout(function() {
				$("[name='reason']").val(reason);
				if (reason == "Termination") {
					$("[name='date_resignation']").val(dateeffective);
				}
			}, 1000);

			$("#date_resignation").show();
			$("#reason").attr('disabled', 'disabled');

			setTimeout(function() {

				var lbl = '';
				var lbl2 = '';

				if (reason == "Deceased") {
					$(".deceased_form").html(
						'<div class="form-group">' +
						'<label> <span class="rqd"> * </span> Date of Death </label>' +
						'<input type="text" required class="form-control" name="dateofdeath" id="dateofdeath" placeholder="mm/dd/yyyy">' +
						'</div>' +
						'<div class="form-group">' +
						'<label> <span class="rqd"> * </span> Name of Claimant </label>' +
						'<input type="text" required class="form-control" name="claimant" id="claimant">' +
						'</div>' +
						'<div class="form-group">' +
						'<label> <span class="rqd"> * </span>  Relation to the deceased employee </label>' +
						'<select class="form-control" name="relation" id="relation">' +
						'<option> - Choose Relationship - </option>' +
						'<option value="Father"> Father </option>' +
						'<option value="Mother"> Mother </option>' +
						'<option value="Spouse"> Spouse </option>' +
						'<option value="Son"> Son </option>' +
						'<option value="Daughter"> Daughter</option>' +
						'<option value="Sister/Brother"> Sister/Brother</option>' +
						'</select>' +
						'</div>' +
						'<div class="form-group">' +
						'<label> <span class="rqd"> * </span> Cause of Death </label>' +
						'<input type="text" required class="form-control" name="causeofdeath" id="causeofdeath">' +
						'</div>' +
						'<div class="rl_form">' +
						'<div class="form-group">' +
						'<label> <span class="rqd"> * </span> Required Document (Scanned Death Certificate) </label>' +
						'<input type="file" required accept="image/*" name="resignationletter" id="resignationletter" class="btn btn-default"  size="50" >' +
						'</div>' +
						'</div>' +
						'<div class="rl_form">' +
						'<div class="form-group" id="autholetter">' +
						'<label> Required Document (Scanned Authorization Letter) </label>' +
						'<input type="file" accept="image/*" name="authorizationletter" id="authorizationletter" class="btn btn-default"  size="50" >' +
						'</div>' +
						'</div>'

					);
					$(".non_deceased_form").html('');
				} else {
					if (reason == "V-Resigned" || reason == "Ad-Resigned") {
						lbl = "<span class='rqd'> * </span>  Date of Resignation ";
						lbl2 = "<span class='rqd'> * </span>  Required Document (Scanned Resignation Letter) ";
					} else if (reason == "Termination") {

						lbl = "<span class='rqd'> * </span> EOC Date ";
						lbl2 = "<span class='rqd'> * </span>  Required Document (Scanned Notice of End of Contract) ";
						$("#resignationletter").removeAttr("required");
						$("#resignationletter").hide();
						$("#rl_form").hide();

					}


					$(".deceased_form").html('');
					$(".non_deceased_form").html(
						'<div class="form-group">' +
						'<label class="label_date">' + lbl + '</label>' +
						'<input type="text" required class="form-control"  name="date_resignation" id="date_resignation" autocomplete="off" placeholder="mm/dd/yyyy">' +
						'</div>' +

						'<div class="rl_form" id="rl_form">' +
						'<div class="form-group">' +
						'<label> ' + lbl2 + ' </label>' +
						'<input type="file" required accept="image/*"   name="resignationletter" id="resignationletter" class="btn btn-default"  size="50" >' +
						'</div>' +
						'</div>'
					);

					if (reason == "Termination") {

						$("#resignationletter").removeAttr("required");
						$("#resignationletter").hide();
						$("#rl_form").hide();
					}
					$("#date_resignation").datepicker({
						dateFormat: "mm/dd/yy",
						changeMonth: true,
						changeYear: true,
						showButtonPanel: true
					});

				}

			}, 500);
			$("#dateofdeath").datepicker({
				dateFormat: "mm/dd/yy",
				changeMonth: true,
				changeYear: true,
				showButtonPanel: true
			});
		}
	</script>
<?php
} else if ($_GET['request'] == "get_promo_store_reprint") {
	$empid 		= $_POST['empId'];
	$promotype 	= $_POST['type'];
	//get the list of stores for promo
	$store 		= getPromoStore($empid, $promotype);

	$sessionuser = $_SESSION['emp_id'];
	$usercc   	= $nq->getOneField("company_code", "employee3", "emp_id = '$sessionuser' ");
	$userbc   	= $nq->getOneField("bunit_code", "employee3", "emp_id = '$sessionuser' ");

	echo "<label>  <span class='rqd'> * </span> Stores </label>
	<select class='form-control' name='store' required>
		<option> </option>";

	for ($i = 0; $i < count($store); $i++) {
		if ($promotype == "STATION") {
			echo "<option value='$store[$i]' selected> $store[$i] </option>";
		} else {

			if ($usercc == $cc[$i] && $userbc == $bc[$i]) {
				echo "<option value='$store[$i]' selected > $store[$i] </option>";
			} else {

				//check store if na secure na
				$stores = $nq->getOneField("store", "secure_clearance_promo_details", "emp_id= '$empid' and store='$store[$i]' ");
				if ($stores == $store[$i]) {
					echo "<option value='$store[$i]'> $store[$i] </option>";
				} else {
					echo "<option value='$store[$i]' disabled> $store[$i] - NOT SECURED YET! </option>";
				}
			}
		}
	}
	echo "</select>";
} else if ($_GET['request'] == "get_promo_store_upload") {
	$empid 		= $_POST['empId'];
	$promotype 	= $_POST['type'];
	//get the list of stores for promo
	$store 		= getPromoStore($empid, $promotype);

	$sessionuser = $_SESSION['emp_id'];
	$usercc   	= $nq->getOneField("company_code", "employee3", "emp_id = '$sessionuser' ");
	$userbc   	= $nq->getOneField("bunit_code", "employee3", "emp_id = '$sessionuser' ");

	echo "<label>  <span class='rqd'> * </span> Stores </label>
						<select class='form-control' name='store' required>
						<option> </option>";

	for ($i = 0; $i < count($store); $i++) {
		if ($promotype == "STATION") {
			echo "<option value='$store[$i]' selected> $store[$i] </option>";
		} else {

			if ($usercc == $cc[$i] && $userbc == $bc[$i]) {
				echo "<option value='$store[$i]' selected > $store[$i] </option>";
			} else {

				//check store if na secure na
				$scp_details = mysql_query("SELECT scpr_id FROM secure_clearance_promo WHERE emp_id = '" . $empid . "' ORDER BY scpr_id DESC LIMIT 1") or die(mysql_error());
				$scp = mysql_fetch_array($scp_details);
				$scpr_id = $scp['scpr_id'];

				$stores = $nq->getOneField("store", "secure_clearance_promo_details", "emp_id= '$empid' and store='$store[$i]' and clearance_status='Completed' and scpr_id='$scpr_id'");
				if ($stores == $store[$i]) {
					echo "<option value='$store[$i]' disabled> $store[$i] - Completed </option>";
				} else {
					$storess = mysql_query("SELECT store FROM secure_clearance_promo_details WHERE emp_id= '$empid' and store='$store[$i]' and scpr_id = '" . $scpr_id . "'") or die(mysql_error());
					if (mysql_num_rows($storess) == 0) {
						echo "<option value='$store[$i]' disabled> $store[$i] - Not Secured Yet!  </option>";
					} else {
						echo "<option value='$store[$i]'> $store[$i] </option>";
					}
				}
			}
		}
	}
	echo "</select>";
} else if ($_GET['request'] == "getEOCdate") //insert to termination
{
	$empid = $_POST['empId'];
	$emptype = $nq->getOneField("emp_type", "employee3", " emp_id = '$empid' ");
	$eocdate = $nq->getOneField("eocdate", "employee3", " emp_id = '$empid' and (eocdate !='' or eocdate !='0000-00-00') ");
	$eocdate = $nq->changeDateFormat("m/d/Y", $eocdate);

	if ($emptype == "Contractual" or $emptype == "NESCO Contractual" or $emptype == "PTA" or $emptype == "PTP" or $emptype == "Probationary" or $emptype == "NESCO Probationary" or $emptype == "Seasonal" or $emptype == "NESCO-PTA" or $emptype == "NESCO-PTP" or $emptype  == "Back-Up" or $emptype == "Partimer") {
		echo "ok+" . $eocdate;
	} else {
		echo "error";
	}
}

//generating clearance
else if ($_GET['request'] == "insert_secure_clearance") {
	//check from secure_clearance
	//insert into secure_clearance
	//update status to employee3 ex RESIGNED (UNCLEARED)

	//NOTES
	// date_activefor_resign// date when the employee inform his resignation and sub_status will change to ACTIVE (For Resignation) 
	// date_secure 			// date when the employee go to the hr ask for clearance and then generate the clearance
	// date_resignation 	// date of resignation or effectivity date
	// date_uncleared		// date when the sub_status change to RESIGNED (UNCLEARED)
	// date_cleared 		// date when the employee submits the fully-signed clearance RESIGNED (CLEARED)

	$addedby 		= $_SESSION['emp_id'];
	$reason 		= $_POST['reason'];
	$empid 			= explode("*", $_POST['empid']);
	$empid 			= trim($empid[0]);
	$date_res 		= $nq->changeDateFormat("Y-m-d", $_POST['date_resignation']);
	$date_secure 	= date("Y-m-d");
	$promotype 		= $_POST['promotype'];
	$store 			= $_POST['store'];

	//if deceased
	$claimant 		= @$_POST['claimant'];
	$relation 		= @$_POST['relation'];
	$dateofdeath 	= $nq->changeDateFormat("Y-m-d", @$_POST['dateofdeath']);
	$causeofdeath 	= @$_POST['causeofdeath'];
	$status 		= $reason;

	//flag 
	$flag = 0;
	$count = 0;
	if ($promotype == "ROVING") {
		$store_arr	= getPromoStore($empid, $promotype);
		for ($i = 0; $i < count($store_arr); $i++) {
			//check if the inputted store already exist
			$resultid = $nq->getOneField("emp_id", "secure_clearance_promo_details", "emp_id= '$empid' and store='$store' and clearance_status = 'Pending'  ");
			if (!$resultid) {
				$flag = 1;
			}
		}
	} else { //if STATION

		//CHECKS IF EXISTING NAH
		$select_query 	= mysql_query("SELECT emp_id from secure_clearance_promo_details where emp_id = '$empid' and clearance_status = 'Pending' and store = '$store' ");
		if (mysql_num_rows($select_query) == 0) {
			$flag = 1;
		}
	}

	//check date secure vs date resignation
	if ($date_secure <= $date_res) {
		$status = "Active";
		switch ($reason) {
			case 'V-Resigned':
				$substatus = "For Resignation";
				break;
			case 'Ad-Resigned':
				$substatus = "For Resignation";
				break;
			case 'Termination':
				$substatus = "For End of Contract";
				break;
		}

		$dateforactiveresign 	= date("Y-m-d");
		$dateuncleared 			= "";
	} else if ($date_secure > $date_res) //($date_secure == $date_res) ||
	{
		switch ($reason) {
			case 'V-Resigned':
				$status = "V-Resigned";
				break;
			case 'Ad-Resigned':
				$status = "Ad-Resigned";
				break;
			case 'Termination':
				$status = "End of Contract";
				break;
		}

		$substatus 				= "Uncleared";
		$dateforactiveresign 	= "";
		$dateuncleared 			= date("Y-m-d");
	}

	$scprdetailsid = 0;

	//IF ALLOWED NA MO PROCESS SA CLEARANCE
	if ($flag == 1) {

		//save and move the required documents ***********************************************************************    
		if (isset($_FILES['resignationletter']['name'])) {
			$letter 	= "../document/resignation/" . $_FILES["resignationletter"]["name"];
			$array 		= explode(".", $_FILES["resignationletter"]["name"]);
			$fletter 	= "../document/resignation/" . trim($empid) . "=" . date('Y-m-d') . "=" . "Resignation-Letter" . "=" . date('H-i-s-A') . "." . $array[1];
			move_uploaded_file($_FILES["resignationletter"]["tmp_name"], @$fletter);
		}

		if (isset($_FILES['authorizationletter']['name'])) {
			$letter 	= "../document/authorizationletter/" . $_FILES["authorizationletter"]["name"];
			$array 		= explode(".", $_FILES["authorizationletter"]["name"]);
			$autholetter = "../document/authorizationletter/" . trim($empid) . "=" . date('Y-m-d') . "=" . "Authorization-Letter" . "=" . date('H-i-s-A') . "." . $array[1];
			move_uploaded_file($_FILES["authorizationletter"]["tmp_name"], @$autholetter);
		}
		// END SAVING DOCUMENTS ***************************************************************************************

		$sql = "INSERT INTO `secure_clearance_promo` (`emp_id`, `promo_type`, `reason`, `date_added`, `added_by`, `status`) 
					VALUES ('$empid','$promotype','$reason','" . date('Y-m-d H:i:s') . "','" . $_SESSION['emp_id'] . "','Pending') ";

		//update the current_status and sub_status in employee3 table
		$sql_emp3 = "UPDATE employee3 SET current_status = '$status', sub_status = '$substatus' WHERE emp_id = '$empid' ";


		//IF ROVING
		if ($promotype == "ROVING") {
			$scpr_id = $nq->getOneField("scpr_id", "secure_clearance_promo", " emp_id = '$empid' and status = 'Pending' ");
			if (!$scpr_id) {
				//insert into table secure clearance **********************************************************************
				$insert_query1 	= mysql_query($sql) or die(mysql_error());
				$scpr_id 		= mysql_insert_id();
				$update_emp3 	= mysql_query($sql_emp3) or die(mysql_error());
			}
		} else {
			//insert into table secure clearance **************************************************************************
			$insert_query1 	= mysql_query($sql) or die(mysql_error());
			$scpr_id 		= mysql_insert_id();
			$update_emp3 	= mysql_query($sql_emp3) or die(mysql_error());
		}


		//INSERT INTO DETAILS TABLE	
		$recordno  = $nq->getOneField("record_no", "employee3", "emp_id = '$empid' ");
		$insert_query_promo_details = mysql_query(" INSERT INTO `secure_clearance_promo_details` (`scpr_id`, `emp_id`, `record_no`, `store`, `date_activefor_resign`, `date_secure`, `date_effectivity`, `date_uncleared`, `date_cleared`, `resignation_letter`, `generated_clearance`, `added_by`, `clearance_status`) 
		VALUES ('$scpr_id','$empid','$recordno','$store','$dateforactiveresign','$date_secure','$date_res','$dateuncleared','','$fletter','','" . $_SESSION['emp_id'] . "','Pending') ") or die(mysql_error());
		$scprdetailsid = mysql_insert_id();
		//END ***********************************************************************************************

		//if reason is deceased insert into this table
		if ($reason == "Deceased") {
			$last_inserted_id = mysql_insert_id();
			$insert_query2 = mysql_query("
						INSERT INTO `secure_clearance_deceased` 
						(`scd_id`, `sc_id`, `emp_id`, `claimant`, `relation`, `dateofdeath`, `causeofdeath`,`authorization_letter`) 
						VALUES ('','$last_inserted_id','$empid','$claimant','$relation','$dateofdeath','$causeofdeath','$autholetter') ") or die(mysql_error());
			$status = "Deceased";
		}

		if ($reason == "V-Resigned" || $reason == "Ad-Resigned") {
			//get raters of employee
			$slevelingsubordinates = mysql_query("SELECT * FROM leveling_subordinates where subordinates_rater = '$empid' ") or die(mysql_error());
			while ($rowsl = mysql_fetch_array($slevelingsubordinates)) {
				//employee // subordinate_rater
				//supervisor  // ratee

				//insert into tag for resignation //automatic
				$insert_tag_resign = mysql_query("
					INSERT INTO `tag_for_resignation` (`tfreg_id`, `ratee_id`, `rater_id`, `added_by`, `date_added`, `tag_stat`) 
					VALUES ('','" . $rowsl['subordinates_rater'] . "','" . $rowsl['ratee'] . "','" . $_SESSION['emp_id'] . "','" . date('Y-m-d') . "','Pending')") or die(mysql_error());
			}
		}
	}

	if ($scpr_id && $insert_query_promo_details) {
		echo "success+" . $scprdetailsid;
	} else {
	}
} else if ($_GET['request'] == "record_clearance_reprint") //insert to termination
{
	$scid 				= $_POST["scid"];
	$reason 			= $_POST["reason"];

	$query = mysql_query("INSERT INTO secure_clearance_reprint
		(`scr_id`,`sc_id`,`reason`,`date`,`generatedby`) 
		VALUES
		('','$scid','$reason','" . date("Y-m-d H:i:s") . "','" . $_SESSION['emp_id'] . "') ");

	if ($query) {
		echo "ok";
	}
} else if ($_GET['request'] == "get_store_reprint") {
	$empid 		= $_POST['empid'];
	$store 		= $_POST['store'];

	$reason 	= $nq->getOneField("reason", "secure_clearance_promo", "emp_id = '$empid' and status = 'Pending' ");
	$scdet_id 	= $nq->getOneField("scdetails_id", "secure_clearance_promo_details", "emp_id='$empid' and store = '$store' and clearance_status='Pending' ");
	echo $reason . "*" . $scdet_id;
} else if ($_GET['request'] == "get_clearance_details") {
	$empid 		= $_POST['empid'];
	$scprid 	= $_POST['scprid'];
	$select 	= mysql_query("SELECT * FROM secure_clearance_promo_details WHERE emp_id = '$empid' and scpr_id = '$scprid' ");
	$name 		= $nq->getEmpName($empid);
	echo
	"<h4> Employee Name: $name </h4> <br>
	<table class='table table-bordered' id='secureclearance_table' style='font-size:13px'>
		<thead>
			<tr>
				<th> STORE </th>
				<th> EPAS </th>
				<th> DATE SECURE </th>
				<th> DATE EFFECTIVITY </th>												
				<th> DATE CLEARED </th>												
				<th> CLEARANCE STATUS </th>
				<th> DOC </th>
				<th> ADDED BY </th>
				<th> ACTION </th>
			</tr>
		</thead>
		<tbody>";
	while ($row = mysql_fetch_array($select)) {
		$addedby 		= $nq->formalname($row['added_by']);

		if ($substatus == "(Cleared)") {
			$view_print = "";
		} else {
			$view_print = "<a href='#' onclick=show_RL('$row[resignation_letter]') data-toggle='modal' data-target='#modal_rl'> view </a>";
		}

		if ($row['reason'] == "Deceased") {
			$view_autho = "<a href='#' onclick=show_RL('$row_d[authorization_letter]') data-toggle='modal' data-target='#modal_rl'> view </a>";
		} else {
			$view_autho = "";
		}

		//get record_no in employee3 to get the epas in appraisal_details
		if (!empty($row['record_no'])) {

			$recordno = $row['record_no'];
		} else {

			$recordno  = $nq->getOneField("record_no", "employee3", "emp_id = '$row[emp_id]' ");
		}
		$epas_grade = $nq->getOneField("numrate", "appraisal_details", "emp_id='$row[emp_id]' and record_no='$recordno' and store='$row[store]'");

		echo "
				<tr>
					<td> $row[store] </td>
					<td> $epas_grade </td>
					<td> " . $nq->changeDateFormat("m/d/Y", $row['date_secure']) . " </td>
					<td> " . $nq->changeDateFormat("m/d/Y", $row['date_effectivity']) . " </td>
					<td> " . $nq->changeDateFormat("m/d/Y", $row['date_cleared']) . " </td>
					<td> $row[clearance_status]  </td>
					<td> $view_print $view_autho </td>
					<td> $addedby </td>
					<td>";
		if ($row['generated_clearance'] == "") {
			echo "<a href='#' onclick=print_clearance('" . $row['reason'] . "','" . $row['emp_id'] . "','" . $row['scdetails_id'] . "') title='Print Clearance'> 
							<img src='images/printer.png' width='18' height='17'>
							</a> ";
		}
		echo "
					</td>
				</tr>";
	}
	echo "
		</tbody>
	</table>";
} else if ($_GET['request'] == "getEPAS") //insert to termination
{
	$empid = $_POST['empid'];
	$store = $_POST['store'];

	//check if promo is seasonal and has 15days below days
	$query_p = mysql_query("
		SELECT startdate, eocdate, type 
		FROM employee3 
		INNER JOIN promo_record 
		ON promo_record.emp_id = employee3.emp_id
		WHERE promo_record.emp_id = '$empid' and type='Seasonal' ");

	$rowp 	= mysql_fetch_array($query_p);
	$type 	= $rowp['type'];
	$sdate 	= date_create($rowp['startdate']);
	$edate 	= date_create($rowp['eocdate']);
	$diff 	= date_diff($sdate, $edate);
	$days 	= $diff->format("%a");


	$query 	= mysql_query("SELECT epas_code, numrate, descrate, emp_type, eocdate, store FROM employee3 
			INNER JOIN appraisal_details
			ON employee3.record_no = appraisal_details.record_no
			WHERE employee3.emp_id = '$empid' and appraisal_details.emp_id = '$empid' and store = '$store' ") or die(mysql_error());

	if (mysql_num_rows($query) != 0) {
		$row 	= mysql_fetch_array($query);
		$emptype = $row['emp_type'];
		$epas 	= $row['numrate'] . " [" . $row['descrate'] . "]";
	}

	$reason 	= $nq->getOneField("reason", "secure_clearance_promo", "emp_id = '$empid' and status = 'Pending'");
	$newreason 	= "";
	if ($reason == "V-Resigned") {
		$newreason = "V-Resigned";
	} else if ($reason == "Ad-Resigned") {
		$newreason = "Ad-Resigned";
	} else if ($reason == "Deceased") {
		$newreason = "Deceased";
	} else if ($reason == "Termination") {
		$newreason = "End of Contract";
	}


	if ($days >= 0 && $days < 15 && $type == "Seasonal") { //15 days up need ug grado
		echo "Seasonal";
	} else if (mysql_num_rows($query) == 0) {
		echo "0";
	} else {

		echo "
		<div class='form-group'>
			<label>  <span class='rqd'> * </span> EPAS </label>	
			<input type='text' required id='epas' class='form-control' disabled value='$epas'>		
	    </div>

	    <div class='form-group'>
			<label>  <span class='rqd'> * </span> Succeeding Status </label>	
			<input type='text' required id='status' id='status' class='form-control' disabled value='$newreason (Cleared)'>				 	
	    </div>";
	}
} else if ($_GET['request'] == "upload_signed_clearance") {
	//insert into termination
	//update date_cleared to secure_clearance
	//update status to employee3 ex RESIGNED (UNCLEARED)

	//NOTES
	// date_activefor_resign// date when the employee inform his resignation and sub_status will change to ACTIVE (For Resignation) 
	// date_secure 			// date when the employee go to the hr ask for clearance and then generate the clearance
	// date_resignation 	// date of resignation or effectivity date
	// date_uncleared		// date when the sub_status change to RESIGNED (UNCLEARED)
	// date_cleared 		// date when the employee submits the fully-signed clearance RESIGNED (CLEARED)

	$addedby 		= $_SESSION['emp_id'];
	$remarks 		= $_POST['remarks'];
	//$status 		= $_POST['status'];	
	$empid 			= explode("*", $_POST['empid']);
	$empid 			= trim($empid[0]);
	$dateupdate 	= date("Y-m-d");
	$datecleared 	= date("Y-m-d");
	$substatus 		= "Cleared";
	$store 			= $_POST['store'];

	$select 			= mysql_query("SELECT * FROM secure_clearance_promo where emp_id = '$empid' and status = 'Pending' ");
	$rows 				= mysql_fetch_array($select);
	$reason 			= $rows['reason'];
	$promotype 			= $rows['promo_type'];
	// update : resignation_letter is only on the secure_clearance_promo_details not on the secure_clearance_promo
	$select2 			= mysql_query("SELECT * FROM secure_clearance_promo_details where emp_id = '$empid' and clearance_status = 'Pending' ");
	$rows2 				= mysql_fetch_array($select2);
	$resignationletter2 	= $rows2['resignation_letter'];
	// end of update
	$resignationletter 	= $rows['resignation_letter'];

	switch ($reason) {
		case 'V-Resigned':
			$status = "V-Resigned";
			break;
		case 'Ad-Resigned':
			$status = "Ad-Resigned";
			break;
		case 'Termination':
			$status = "End of Contract";
			break;
		case 'Deceased':
			$status = "Deceased";
			break;
		default:
			$status = "V-Resigned";
			break;
	}

	//CHECK
	$date_res_qry = mysql_query("SELECT date_effectivity FROM secure_clearance_promo_details WHERE emp_id = '$empid' ORDER BY date_effectivity DESC LIMIT 1");
	$date_res_row = mysql_fetch_array($date_res_qry);
	$date_res 	  = $date_res_row['date_effectivity'];

	if (isset($_FILES['clearance']['name'])) {
		$clearance 	= "../document/clearance/" . $_FILES["clearance"]["name"];
		$array 		= explode(".", $_FILES["clearance"]["name"]);
		$fclearance = "../document/clearance/" . $empid . "=" . date('Y-m-d') . "=" . "Clearance" . "=" . date('H-i-s-A') . "." . $array[1];
		move_uploaded_file($_FILES["clearance"]["tmp_name"], @$fclearance);
	}

	// update $resignationletter to $resignationletter2; updated to blank 
	$insert = mysql_query("INSERT INTO termination
				(`termination_no`,`emp_id`,`date`,`remarks`,`resignation_letter`,`added_by`,`date_updated` )
				VALUES  ('','$empid','$date_res','$remarks','','$addedby','$dateupdate')");

	$update_secc = mysql_query("
				UPDATE secure_clearance_promo_details SET date_cleared = '$datecleared', clearance_status = 'Completed'
				WHERE emp_id = '$empid' and clearance_status = 'Pending' and store = '$store' ");

	$clearance_field = getStoreClearance($store);
	$update_prmo = mysql_query("UPDATE promo_record SET $clearance_field = '$fclearance' WHERE emp_id = '$empid' ");

	//check all stores if status is completed
	$stores = getPromoStore($empid, $promotype);
	$counts = 0;
	for ($i = 0; $i < count($stores); $i++) {
		$storen = $nq->getOneField("store", "secure_clearance_promo_details", "emp_id = '$empid' and clearance_status = 'Completed' and store = '$stores[$i]' AND scpr_id = '" . $rows['scpr_id'] . "' ");
		if ($storen == $stores[$i]) {
			$counts++;
		}
	}

	// if all stores are completed time to update the employee3 status
	if (count($stores) ==  $counts) {
		$update_emp3 = mysql_query("UPDATE employee3 SET current_status='$status', sub_status= '$substatus' WHERE emp_id='$empid' ");
		$update_secp = mysql_query("UPDATE secure_clearance_promo SET status='Completed' WHERE emp_id='$empid' and status='Pending' ");
	}


	if ($insert && $update_secc) { //&& $update_emp3 
		echo "Success+Employee Successfully Cleared!+success";
	} else {
		echo "Error+Error Saving!+error";
	}
} else if ($_GET['request'] == "get_date_effectivity") //get date effectivity
{
	$empid 		= $_POST["empid"];
	$promotype 	= $_POST["promotype"];

	$scprid 	= $nq->getOneField("scpr_id", "secure_clearance_promo", " emp_id = '$empid' and promo_type = '$promotype' and status = 'Pending'");
	$reason 	= $nq->getOneField("reason", "secure_clearance_promo", " scpr_id = '$scprid' ");

	if ($scprid) {
		$dateeffective 	= $nq->getOneField("date_effectivity", "secure_clearance_promo_details", " scpr_id = '$scprid' ");
		echo $reason . "*" . $nq->changeDateFormat("m/d/Y", $dateeffective);
	} else {
		$dateeffective 	= $nq->getOneField("eocdate", "employee3", "emp_id = '$empid'");
		echo "*" . $nq->changeDateFormat("m/d/Y", $dateeffective);
	}
} else if ($_GET['request'] == "getNames") {
	$empid = $_POST['empid'];
	$name  = $nq->getOneField("name", "employee3", " emp_id = '$empid' ");
	echo $name;
}
