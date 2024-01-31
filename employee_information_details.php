<?php

session_start();
include("connection.php");
mysql_query("set character_set_results='utf8'");
date_default_timezone_set("Asia/Manila");

$date 	= date('Y-m-d');
$time 	= date("H:i:s");
$datab2 = "timekeeping";

// location
$loginId = $_SESSION['emp_id'];
$hrLocation = mysql_query("SELECT company_code, bunit_code, dept_code FROM employee3 WHERE emp_id = '$loginId' AND current_status = 'Active'") or die(mysql_error());
$hr = mysql_fetch_array($hrLocation);

$ccHr = $hr['company_code'];
$bcHr = $hr['bunit_code'];
$dcHr = $hr['dept_code'];

if ($ccHr != "07") {
	$hrCode = 'asc';
} else {

	$hrCode = 'cebo';
}

$accessPromo = mysql_query("SELECT usertype FROM promo_user WHERE emp_id = '$loginId' AND user_status = 'active'") or die(mysql_error());
$accP = mysql_fetch_array($accessPromo);

$promoUserType = $accP['usertype'];

function logs($user, $username, $date, $time, $activity)
{

	mysql_query("INSERT INTO
						logs 
							VALUES(
								'',
								'" . mysql_real_escape_string($activity) . "',
								'" . mysql_real_escape_string($date) . "',
								'" . mysql_real_escape_string($time) . "',
								'" . mysql_real_escape_string($user) . "',
								'" . mysql_real_escape_string($username) . "'
					)") or die(mysql_error());
}

function getEmpName($empid)
{

	$query = mysql_query("SELECT name from employee3 where emp_id = '$empid'") or die();
	$fetch = mysql_fetch_array($query);
	return $fetch['name'];
}

$Sweight = $nq->selectTable('weight');
$Sheight = $nq->selectTable('height');
$religion_query = $nq->selectTable('religion');
$bloodtype_array = array('A', 'A+', 'A-', 'B', 'B+', 'B-', 'O', 'O+', 'O-', 'AB', 'AB+', 'AB-');
$cv_array		 = array('Single', 'Married', 'Widowed', 'Separated', 'Annulled', 'Divorced');

if ($_GET['request'] == "basicinfo") {

	$empId = $_POST['empId'];
	$basicinfo = mysql_query("SELECT firstname, lastname, middlename, suffix, citizenship, gender, civilstatus, religion, weight, height, bloodtype, birthdate 
									FROM applicant WHERE app_id = '$empId'") or die(mysql_error());
	$row = mysql_fetch_array($basicinfo);

	$lastname	= htmlspecialchars($row['lastname'], ENT_QUOTES);
	$firstname	= htmlspecialchars($row['firstname'], ENT_QUOTES);
	$middlename	= htmlspecialchars($row['middlename'], ENT_QUOTES);
	$suffix		= htmlspecialchars($row['suffix'], ENT_QUOTES);
	$citizenship = htmlspecialchars($row['citizenship'], ENT_QUOTES);
	$gender 	= htmlspecialchars($row['gender'], ENT_QUOTES);
	$cv 		= htmlspecialchars($row['civilstatus'], ENT_QUOTES);
	$religion 	= htmlspecialchars($row['religion'], ENT_QUOTES);
	$weight 	= htmlspecialchars($row['weight'], ENT_QUOTES);
	$height 	= htmlspecialchars($row['height'], ENT_QUOTES);
	$bloodtype 	= htmlspecialchars($row['bloodtype'], ENT_QUOTES);

	$datebirth = date("m/d/Y");
	if ($row['birthdate'] != "0000-00-00" && $row['birthdate'] != "NULL" && $row['birthdate'] != "") {

		$datebirth	= date("m/d/Y", strtotime($row['birthdate']));
	}

?>
	<div class="modf">Basic Information
		<input name="edit" id="edit-basicinfo" value="edit" class="btn btn-primary btn-sm" onclick="edit_basicinfo()" type="button">
		<input class="btn btn-primary btn-sm" id="update-basicinfo" value="update" onclick="update(this.id)" style="display:none" type="button">
	</div>
	<table class="table table-bordered">
		<tbody>
			<tr>
				<td width="20%" align="right">Employee No</td>
				<td colspan="4">
					<input name="" value="<?php echo $empId; ?>" readonly="" class="form-control" disabled="" type="text">
				</td>
			</tr>
			<tr>
				<td align="right">Firstname</td>
				<td><input name="fname" value="<?php echo $firstname; ?>" class="form-control inputForm" type="text"></td>
				<td align="right">Middlename</td>
				<td><input name="mname" value="<?php echo $middlename; ?>" class="form-control inputForm" type="text"></td>
			</tr>
			<tr>
				<td align="right">Lastname</td>
				<td><input name="lname" value="<?php echo $lastname; ?>" class="form-control inputForm" type="text"></td>
				<td align="right">Suffix</td>
				<td><input name="suffix" value="<?php echo $suffix; ?>" class="form-control inputForm" type="text"></td>
			</tr>
			<tr>
				<td align="right">Date of Birth</td>
				<td><input name="datebirth" value="<?php echo $datebirth; ?>" class="form-control inputForm datepicker" placeholder="mm/dd/yyyy" type="text"></td>
				<td align="right">Citizenship</td>
				<td><input name="citizenship" value="<?php echo $citizenship ?>" class="form-control inputForm" type="text"></td>
			</tr>
			<tr>
				<td align="right">Gender</td>
				<td>
					<select name="gender" class="form-control inputForm">
						<option value=""> --Select-- </option>
						<option value="Male" <?php if ($gender == "Male") : echo "selected=''";
												endif; ?>>Male</option>
						<option value="Female" <?php if ($gender == "Female") : echo "selected=''";
												endif; ?>>Female</option>
					</select>
				</td>
				<td align="right">Civil Status</td>
				<td>
					<select name="civilstatus" class="form-control inputForm">
						<option value=""></option>
						<?php

						for ($i = 0; $i < count($cv_array); $i++) {

							if ($cv == $cv_array[$i]) {
								echo "<option value='" . $cv_array[$i] . "' selected='selected' >" . $cv_array[$i] . "</option>";
							} else {
								echo "<option value='" . $cv_array[$i] . "'>" . $cv_array[$i] . "</option>";
							}
						}
						?>
					</select>
				</td>
			</tr>
			<tr>
				<td align="right">Religion</td>
				<td>
					<input list="religions" name="religion" class="form-control inputForm" autocomplete="off" value="<?php echo $religion; ?>">
					<datalist id="religions">
						<?php
						while ($rrow = mysql_fetch_array($religion_query)) {
							if ($rrw['religion'] == $religion) {
								echo "<option value='" . $rrow['religion'] . "''>" . $rrow['religion'] . "</option>";
							} else {
								echo "<option value='" . $rrow['religion'] . "''>" . $rrow['religion'] . "</option>";
							}
						} ?>
					</datalist>
				</td>
				<td align="right">Bloodtype</td>
				<td>
					<select class="form-control inputForm" name="bloodtype">
						<option value=""></option>
						<?php

						for ($i = 0; $i < count($bloodtype_array); $i++) {

							if ($bloodtype == $bloodtype_array[$i]) {
								echo "<option value='" . $bloodtype_array[$i] . "' selected='selected' >" . $bloodtype_array[$i] . "</option>";
							} else {
								echo "<option value='" . $bloodtype_array[$i] . "'>" . $bloodtype_array[$i] . "</option>";
							}
						}
						?>
					</select>
				</td>
			</tr>
			<tr>
				<td align="right">Weight <i>in kilogram</i></td>
				<td>
					<input list="weights" name="weight" class="form-control inputForm" autocomplete="off" value="<?php echo $weight; ?>">
					<datalist id="weights">
						<?php
						while ($wrow = mysql_fetch_array($Sweight)) {
							$we = $wrow['kilogram'] . " / " . $wrow['pounds'];
							echo "<option value=\"$we\">" . $we . "</option>";
						} ?>
					</datalist>
				</td>
				<td align="right">Height <i>in centimeter</i></td>
				<td>
					<input list="heights" name="height" class="form-control inputForm" autocomplete="off" value="<?php echo $height; ?>">
					<datalist id="heights">
						<?php
						while ($hrow = mysql_fetch_array($Sheight)) {
							$he = $hrow['cm'] . " / " . $hrow['feet'];
							echo "<option value=\"$he\">" . $he . "</option>";
						} ?>
					</datalist>
				</td>
			</tr>
		</tbody>
	</table>
	<script type="text/javascript">
		$('.datepicker').datepicker({
			inline: true,
			changeYear: true,
			changeMonth: true
		});

		$(".inputForm").prop("disabled", true);
	</script>
<?php
} else if ($_GET['request'] == "family") {

	$empId = $_POST['empId'];
	$family = mysql_query("SELECT spouse, noofSiblings, siblingOrder, mother, father, guardian
									FROM applicant WHERE app_id = '$empId'") or die(mysql_error());
	$row = mysql_fetch_array($family);

	$mother 	= htmlspecialchars($row['mother'], ENT_QUOTES);
	$father 	= htmlspecialchars($row['father'], ENT_QUOTES);
	$guardian 	= htmlspecialchars($row['guardian'], ENT_QUOTES);
	$noofsibling = htmlspecialchars($row['noofSiblings'], ENT_QUOTES);
	$siblingordr = htmlspecialchars($row['siblingOrder'], ENT_QUOTES);
	$spouse 	= htmlspecialchars($row['spouse'], ENT_QUOTES);

?>
	<div class="modf">Family Background
		<input name="edit" id="edit-family" value="edit" class="btn btn-primary btn-sm" onclick="edit_family()" type="button">
		<input class="btn btn-primary btn-sm" id="update-family" value="update" onclick="update(this.id)" style="display:none" type="button">
	</div>
	<table class="table table-bordered" width="600">
		<tbody>
			<tr>
				<td width="20%" align="right">Mother</td>
				<td colspan="2"><input name="mother" value="<?php echo $mother; ?>" class="form-control inputForm" type="text"></td>
			</tr>
			<tr>
				<td align="right">Father</td>
				<td colspan="2"><input name="father" value="<?php echo $father; ?>" class="form-control inputForm" type="text"></td>
			</tr>
			<tr>
				<td align="right">Guardian</td>
				<td colspan="2"><input name="guardian" value="<?php echo $guardian; ?>" class="form-control inputForm" type="text"></td>
			</tr>
			<tr>
				<td align="right">Spouse</td>
				<td colspan="2"><input name="spouse" value="<?php echo $spouse; ?>" class="form-control inputForm" type="text"></td>
			</tr>
		</tbody>
	</table>
	<script type="text/javascript">
		$(".inputForm").prop("disabled", true);
	</script>
<?php
} else if ($_GET['request'] == "contact") {

	$empId = $_POST['empId'];

	$contactinfo = mysql_query("SELECT home_address, city_address, contact_person, contact_person_address, contact_person_number, contactno, telno, email, facebookAcct, twitterAcct
										FROM applicant WHERE app_id = '$empId'") or die(mysql_error());
	$row = mysql_fetch_array($contactinfo);

	$homeaddress 		= htmlspecialchars($row['home_address'], ENT_QUOTES);
	$cityaddress 		= htmlspecialchars($row['city_address'], ENT_QUOTES);
	$contactperson 		= htmlspecialchars($row['contact_person'], ENT_QUOTES);
	$contactpersonadd 	= htmlspecialchars($row['contact_person_address'], ENT_QUOTES);
	$contactpersonno 	= htmlspecialchars($row['contact_person_number'], ENT_QUOTES);
	$cellphone 	= htmlspecialchars($row['contactno'], ENT_QUOTES);
	$telno 		= htmlspecialchars($row['telno'], ENT_QUOTES);
	$email 		= htmlspecialchars($row['email'], ENT_QUOTES);
	$fb 		= htmlspecialchars($row['facebookAcct'], ENT_QUOTES);
	$twitter 	= htmlspecialchars($row['twitterAcct'], ENT_QUOTES);

?>
	<div class="modf">Contact &amp; Address Information
		<input name="edit" id="edit-contact" value="edit" class="btn btn-primary btn-sm" onclick="edit_contact()" type="button">
		<input class="btn btn-primary btn-sm" id="update-contact" value="update" onclick="update(this.id)" style="display:none" type="button">
	</div>
	<table class="table table-bordered">
		<tbody>
			<tr>
				<td width="20%" align="right">Home Address</td>
				<td colspan="4">
					<input list="homeadd" name="homeaddress" autocomplete="off" value="<?php echo $homeaddress; ?>" class="form-control inputForm">
					<datalist id="homeadd">
						<?php

						$result = $q->innerJOINbrgytownprov();
						while ($rs = $q->fetchArray($result)) {

							echo "<option value='" . $rs['brgy_name'] . ", " . $rs['town_name'] . ", " . $rs['prov_name'] . "'>" . $rs['brgy_name'] . ", " . $rs['town_name'] . ", " . $rs['prov_name'] . "</option>";
						}
						?>
					</datalist>
				</td>
			</tr>
			<tr>
				<td align="right">City Address</td>
				<td colspan="4">
					<input list="cityadd" name="cityaddress" autocomplete="off" value="<?php echo $cityaddress; ?>" class="form-control inputForm">
					<datalist id="cityadd">
						<?php

						$result = $q->innerJOINbrgytownprov();
						while ($rs = $q->fetchArray($result)) {

							echo "<option value='" . $rs['brgy_name'] . ", " . $rs['town_name'] . ", " . $rs['prov_name'] . "'>" . $rs['brgy_name'] . ", " . $rs['town_name'] . ", " . $rs['prov_name'] . "</option>";
						}
						?>
					</datalist>
				</td>
			</tr>
			<tr>
				<td align="right">Contact Person</td>
				<td colspan="4"><input name="contactperson" id="contactperson" value="<?php echo $contactperson; ?>" class="form-control inputForm" type="text"></td>
			</tr>
			<tr>
				<td align="right">Contact Person Address</td>
				<td colspan="4">
					<input list="contactpersonadd" name="contactpersonadd" autocomplete="off" value="<?php echo $contactpersonadd; ?>" class="form-control inputForm">
					<datalist id="contactpersonadd">
						<?php

						$result = $q->innerJOINbrgytownprov();
						while ($rs = $q->fetchArray($result)) {

							echo "<option value='" . $rs['brgy_name'] . ", " . $rs['town_name'] . ", " . $rs['prov_name'] . "'>" . $rs['brgy_name'] . ", " . $rs['town_name'] . ", " . $rs['prov_name'] . "</option>";
						}
						?>
					</datalist>
				</td>
			</tr>
			<tr>
				<td align="right">Contact Person No.</td>
				<td><input name="contactpersonno" value="<?php echo $contactpersonno; ?>" data-inputmask='"mask": "+639999999999"' data-mask class="form-control inputForm" type="text"></td>
				<td align="right">Cellphone No</td>
				<td><input name="cellphone" value="<?php echo $cellphone; ?>" data-inputmask='"mask": "+639999999999"' data-mask class="form-control inputForm" type="text"></td>
			</tr>
			<tr>
				<td align="right">Telephone No.</td>
				<td><input name="telno" value="<?php echo $telno; ?>" class="form-control inputForm" type="text"></td>
				<td align="right">Email address</td>
				<td><input name="email" value="<?php echo $email; ?>" class="form-control inputForm" type="text"></td>
			</tr>
			<tr>
				<td align="right">Facebook</td>
				<td><input name="fb" value="<?php echo $fb; ?>" class="form-control inputForm" type="text"></td>
				<td align="right">Twitter</td>
				<td><input name="twitter" value="<?php echo $twitter; ?>" class="form-control inputForm" type="text"></td>
			</tr>
		</tbody>
	</table>
	<script type="text/javascript">
		$(".inputForm").prop("disabled", true);
		$("[data-mask]").inputmask();
	</script>
<?php
} else if ($_GET['request'] == "educ") {

	$empId = $_POST['empId'];

	$educinfo = mysql_query("SELECT attainment, school, course FROM applicant WHERE app_id = '$empId'") or die(mysql_error());
	$row = mysql_fetch_array($educinfo);

	$attainment = htmlspecialchars($row['attainment'], ENT_QUOTES);
	$school 	= htmlspecialchars($row['school'], ENT_QUOTES);
	$course 	= htmlspecialchars($row['course'], ENT_QUOTES);

?>
	<div class="modf">Educational Background
		<input name="edit" id="edit-educ" value="edit" class="btn btn-primary btn-sm" onclick="edit_educ()" type="button">
		<input class="btn btn-primary btn-sm" id="update-educ" value="update" onclick="update(this.id)" style="display:none" type="button">
	</div>
	<table class="table table-bordered">
		<tbody>
			<tr>
				<td width="20%" align="right">Educational Attainment</td>
				<td>
					<select name="attainment" class="form-control inputForm" id="attainment">
						<option></option>
						<?php

						$result1 = $q->selectALLfromATTAINMENT();
						while ($rw = $q->fetchArray($result1)) {

							if ($attainment == $rw['attainment']) {
								echo "<option value='" . $rw['attainment'] . "' selected='selected' >" . $rw['attainment'] . "</option>";
							} else {
								echo "<option value='" . $rw['attainment'] . "' >" . $rw['attainment'] . "</option>";
							}
						}
						?>
					</select>
				</td>
			</tr>
			<tr>
				<td align="right">School</td>
				<td>
					<input list="schools" name="school" autocomplete="off" value="<?php echo $school; ?>" class="form-control inputForm">
					<datalist id="schools">
						<?php

						$result = $q->selectDISTINCTschoolnameFROMSCHOOL();
						while ($rows = $q->fetchArray($result)) {

							if ($school == $rows['school_name']) {
								echo "<option value='" . $rows['school_name'] . "'>" . $rows['school_name'] . "</option>";
							} else {
								echo "<option value='" . $rows['school_name'] . "'>" . $rows['school_name'] . "</option>";
							}
						}
						?>
					</datalist>
				</td>
			</tr>
			<tr>
				<td align="right">Details / Course</td>
				<td>
					<input list="courses" name="course" autocomplete="off" value="<?php echo $course; ?>" class="form-control inputForm">
					<datalist id="courses">
						<?php

						$result = $nq->selectTable('course');
						while ($rs = mysql_fetch_array($result)) {

							if ($course == $rs['course_name']) {
								echo "<option value='" . $rs['course_name'] . "'>" . $rs['course_name'] . "</option>";
							} else {
								echo "<option value='" . $rs['course_name'] . "'>" . $rs['course_name'] . "</option>";
							}
						}
						?>
					</datalist>
				</td>
			</tr>
		</tbody>
	</table>
	<script type="text/javascript">
		$(".inputForm").prop("disabled", true);
	</script>
<?php
} else if ($_GET['request'] == "seminar") {

	$empId = $_POST['empId'];
?>
	<div class="modf">Eligibility / Seminar / Training Information
		<button class="btn btn-primary btn-sm" id="add-seminar" onclick="add_seminar('')">Add</button>
	</div>
	<table class="table table-striped" width="100%">
		<thead>
			<tr>
				<th>Name</th>
				<th>Date</th>
				<th>Location</th>
				<th>Certificate</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody>
			<?php

			$ss = mysql_query("SELECT * FROM `application_seminarsandeligibility` WHERE app_id  = '$empId' ORDER BY no DESC") or die(mysql_error());
			while ($rwss = mysql_fetch_array($ss)) {

				echo "  
							<tr>		           
								<td>" . htmlspecialchars($rwss['name'], ENT_QUOTES) . "</td>
								<td>" . htmlspecialchars($rwss['dates'], ENT_QUOTES) . "</td>
								<td>" . htmlspecialchars($rwss['location'], ENT_QUOTES) . "</td>
								<td>";
				if (!empty($rwss['sem_certificate'])) {
					echo "<button class='btn btn-primary btn-sm' onclick=viewSeminarCert('$rwss[no]')>view</button>";
				}
				echo "
								</td>
								<td><input type='button' class='btn btn-primary btn-sm' value='edit' id='edit-seminar' onclick=add_seminar('$rwss[no]')></td>
							</tr>";
			}
			?>
		</tbody>
	</table>
<?php
} else if ($_GET['request'] == "addSeminar") {

	$no = $_POST['no'];
	$empId = $_POST['empId'];

	$sql = mysql_query("SELECT * FROM `application_seminarsandeligibility` WHERE no ='$no'") or die(mysql_error());
	$row = mysql_fetch_array($sql);

	$name = $row['name'];
	$dates = $row['dates'];
	// if($row['dates'] == "0000-00-00" || $row['dates'] == "1970-01-01" || $row['dates'] == "") : $dates = ""; else : $dates = date("m/d/Y", strtotime($row['dates'])); endif;
	$location = $row['location'];
	$sem_certificate = $row['sem_certificate'];

?>
	<input type="hidden" name="no" value="<?php echo $no; ?>">
	<input type="hidden" name="appId" value="<?php echo $empId; ?>">
	<div class="form-group"> <i class="text-red">*</i>
		<label>Name</label>
		<input type="text" name="semName" value="<?php echo $name; ?>" class="form-control" onkeyup="inputField(this.name)" autocomplete="off">
	</div>
	<div class="form-group"> <i class="text-red">*</i>
		<label>Location</label>
		<input type="text" name="semLocation" value="<?php echo $location; ?>" class="form-control" onkeyup="inputField(this.name)" autocomplete="off">
	</div>
	<div class="row">
		<div class="col-md-6">
			<div class="form-group">
				<label>Date</label>
				<input type="text" name="semDate" value="<?php echo $dates; ?>" class="form-control" onchange="inputField(this.name)">
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
				<label>Certificate</label> <?php if (!empty($sem_certificate)) : echo "<i class='text-red'> - Already Uploaded</i>";
											endif; ?>
				<input type="file" name="semCertificate" class="form-control" onchange="inputField(this.name)">
			</div>
		</div>
	</div>

	<script type="text/javascript">
		$('.datepicker').datepicker({
			inline: true,
			changeYear: true,
			changeMonth: true
		});
	</script>
<?php
} else if ($_GET['request'] == "charref") {

	$empId = $_POST['empId'];
?>
	<div class="modf">Character References
		<button class="btn btn-primary btn-sm" id="add-charref" onclick="add_charref('')">add</button>
	</div>
	<table class="table table-striped" width="100%">
		<thead>
			<tr>
				<th>Name</th>
				<th>Position</th>
				<th>Contact Number</th>
				<th>Company / Location</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody>
			<?php

			$s = mysql_query("SELECT * FROM application_character_ref WHERE app_id  = '$empId'") or die(mysql_error());
			while ($rws = mysql_fetch_array($s)) {

				echo " 
								<tr>            
									<td>" . htmlspecialchars($rws['name'], ENT_QUOTES) . "</td>
									<td>" . htmlspecialchars($rws['position'], ENT_QUOTES) . "</td>
									<td>" . htmlspecialchars($rws['contactno'], ENT_QUOTES) . "</td>
									<td>" . htmlspecialchars($rws['company'], ENT_QUOTES) . "</td>
									<td><button class='btn btn-primary btn-sm' id='edit-charref' onclick=add_charref('$rws[no]')>edit</button></td>
								</tr>";
			}
			?>
		</tbody>
	</table>
<?php
} else if ($_GET['request'] == "addCharRef") {

	$empId = $_POST['empId'];
	$no = $_POST['no'];

	$charRef = mysql_query("SELECT * FROM `application_character_ref` WHERE `no` = '$no' AND `app_id` = '$empId'") or die(mysql_error());
	$row = mysql_fetch_array($charRef);

	$name = $row['name'];
	$position = $row['position'];
	$contactno = $row['contactno'];
	$company = $row['company'];
?>
	<input type="hidden" name="no" value="<?php echo $no; ?>">
	<input type="hidden" name="appId" value="<?php echo $empId; ?>">
	<div class="form-group"> <i class="text-red">*</i>
		<label>Name</label>
		<input type="text" name="charName" value="<?php echo $name; ?>" class="form-control" onkeyup="inputField(this.name)" autocomplete="off">
	</div>
	<div class="form-group"> <i class="text-red">*</i>
		<label>Company / Location</label>
		<input type="text" name="charCompanyLocation" value="<?php echo $company; ?>" class="form-control" onkeyup="inputField(this.name)" autocomplete="off">
	</div>
	<div class="row">
		<div class="col-md-6">
			<div class="form-group"> <i class="text-red">*</i>
				<label>Position</label>
				<input type="text" name="charPosition" value="<?php echo $position; ?>" class="form-control" onkeyup="inputField(this.name)">
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
				<label>Contact Number</label>
				<input type="text" name="charContact" class="form-control" value="<?php echo $contactno; ?>" onkeyup="inputField(this.name)">
			</div>
		</div>
	</div>
<?php
} else if ($_GET['request'] == "skills") {

	$empId = $_POST['empId'];

	$skillsInfo = mysql_query("SELECT hobbies, specialSkills from applicant where app_id = '$empId'") or die(mysql_error());
	$row = mysql_fetch_array($skillsInfo);

	$hobbies 	= htmlspecialchars($row['hobbies'], ENT_QUOTES);
	$skills  	= htmlspecialchars($row['specialSkills'], ENT_QUOTES);

?>
	<div class="modf">Skills &amp; Competencies
		<input name="edit" id="edit-skills" value="edit" class="btn btn-primary btn-sm" onclick="edit_skills()" type="button">
		<input class="btn btn-primary btn-sm" id="update-skills" value="update" onclick="update(this.id)" style="display:none" type="button">
	</div>
	<table class="table table-bordered" width="100%">
		<tbody>
			<tr>
				<td width="20%" align="right">Hobbies</td>
				<td><textarea name="hobbies" class="form-control inputForm" onkeyup="inputField(this.name)"><?php echo $hobbies; ?></textarea></td>
			</tr>
			<tr>
				<td align="right">Special skills / Talents</td>
				<td><textarea name="skills" class="form-control inputForm" onkeyup="inputField(this.name)"><?php echo $skills; ?></textarea></td>
			</tr>
		</tbody>
	</table>
	<script type="text/javascript">
		$(".inputForm").prop("disabled", true);
	</script>
<?php
} else if ($_GET['request'] == "eocapp") {

	$empId = $_POST['empId'];
?>
	<div class="modf">EOC Appraisal History</div>
	<div class="table-height">
		<table class="table table-bordered" width="100%">
			<thead>
				<tr>
					<th>Startdate</th>
					<th>EOCdate</th>
					<th>Rater's Name</th>
					<th>NumRate</th>
					<th>DescRate</th>
					<th>Store</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				<?php

				$storeEpas = "";
				$ctr = 0;
				$bunit = mysql_query("SELECT bunit_field, bunit_epascode FROM `locate_promo_business_unit`") or die(mysql_error());
				while ($str = mysql_fetch_array($bunit)) {

					$promo = mysql_query("SELECT promo_id FROM `promo_record` WHERE `" . $str['bunit_field'] . "` = 'T' AND emp_id = '$empId'") or die(mysql_error());
					if (mysql_num_rows($promo) > 0) {
						$ctr++;

						if ($ctr == 1) {

							$storeEpas = "AND (" . $str['bunit_epascode'] . " = '1'";
						} else {

							$storeEpas .= "OR " . $str['bunit_epascode'] . " = '1'";
						}
					}
				}

				$storeEpas .= ")";

				// employee3
				$sql = mysql_query("SELECT employee3.record_no, startdate, eocdate FROM `employee3`, `promo_record` 
													WHERE employee3.emp_id = promo_record.emp_id AND employee3.record_no = promo_record.record_no AND employee3.emp_id = '$empId' ORDER BY startdate DESC") or die(mysql_error());
				while ($row = mysql_fetch_array($sql)) {

					$sqlPromo = mysql_query("SELECT promo_id FROM promo_record WHERE emp_id='$empId' AND record_no = '" . $row['record_no'] . "' $storeEpas") or die(mysql_error());
					if (mysql_num_rows($sqlPromo) > 0) {

						$appraisal = mysql_query("SELECT details_id, rater, numrate, descrate, ratingdate, store FROM appraisal_details WHERE record_no = '" . $row['record_no'] . "' and emp_id = '" . $empId . "'") or die(mysql_error());
						while ($r1 = mysql_fetch_array($appraisal)) {

							$detailsId = $r1['details_id'];
							echo "
											<tr>
												<td>" . date('m/d/Y', strtotime($row['startdate'])) . "</td>
												<td>" . date('m/d/Y', strtotime($row['eocdate'])) . "</td>
												<td><a href='#'>" . $nq->getApplicantName2($r1['rater']) . "</a></td>
												<td>" . $r1['numrate'] . "</td>
												<td align='center'>" . $r1['descrate'] . "</td>
												<td>" . ucwords(strtolower($r1['store'])) . "</td>
												<td><button class='btn btn-primary btn-sm' onclick='viewAppraisalDetails(\"$detailsId\")'>view</button></td>
											</tr>
										";
						}
					}
				}

				// employmentrecord_
				$sql2 = mysql_query("SELECT employmentrecord_.record_no, startdate, eocdate FROM `employmentrecord_`, `promo_history_record` 
													WHERE employmentrecord_.emp_id = promo_history_record.emp_id AND employmentrecord_.record_no = promo_history_record.record_no AND employmentrecord_.emp_id = '$empId' ORDER BY startdate DESC") or die(mysql_error());
				while ($row2 = mysql_fetch_array($sql2)) {

					$storeEpas = "";
					$ctr = 0;
					$bunit = mysql_query("SELECT bunit_field, bunit_epascode FROM `locate_promo_business_unit`") or die(mysql_error());
					while ($str = mysql_fetch_array($bunit)) {

						$promo = mysql_query("SELECT promo_id FROM `promo_history_record` WHERE `" . $str['bunit_field'] . "` = 'T' AND emp_id = '$empId' AND record_no = '" . $row2['record_no'] . "'") or die(mysql_error());
						if (mysql_num_rows($promo) > 0) {
							$ctr++;

							if ($ctr == 1) {

								$storeEpas = "AND (" . $str['bunit_epascode'] . " = '1'";
							} else {

								$storeEpas .= "OR " . $str['bunit_epascode'] . " = '1'";
							}
						}
					}

					$storeEpas .= ")";
					if ($storeEpas == ")") {
						$storeEpas = "";
					}

					$sqlPromo = mysql_query("SELECT promo_id FROM promo_history_record WHERE emp_id='$empId' AND record_no = '" . $row2['record_no'] . "' $storeEpas") or die(mysql_error());
					if (mysql_num_rows($sqlPromo) > 0) {

						$appraisal = mysql_query("SELECT details_id, rater, numrate, descrate, ratingdate, record_no, store FROM appraisal_details WHERE record_no = '" . $row2['record_no'] . "' and emp_id = '" . $empId . "'") or die(mysql_error());
						while ($r2 = mysql_fetch_array($appraisal)) {

							$detailsId = $r2['details_id'];
							echo "
											<tr>
												<td>" . date('m/d/Y', strtotime($row2['startdate'])) . "</td>
												<td>" . date('m/d/Y', strtotime($row2['eocdate'])) . "</td>
												<td><a href='#'>" . ucwords(strtolower($nq->getApplicantName2($r2['rater']))) . "</a></td>
												<td>" . $r2['numrate'] . "</td>
												<td align='center'>" . $r2['descrate'] . "</td>
												<td>" . ucwords(strtolower($r2['store'])) . "</td>
												<td><button class='btn btn-primary btn-sm' onclick='viewAppraisalDetails(\"$detailsId\")'>view</button></td>
											</tr>
										";
						}
					}
				}
				?>
			</tbody>
		</table>
	</div>
<?php
} else if ($_GET['request'] == "appraisalDetails") {

	$detailsId = $_POST['detailsId'];

	$query = mysql_query("SELECT numrate, descrate, ratercomment, rateecomment, ratingdate, code FROM `appraisal_details` WHERE details_id = '$detailsId'") or die(mysql_error());
	$r = mysql_fetch_array($query);

	$numrate = $r['numrate'];
	$ratercomment = $r['ratercomment'];
	$rateecomment = $r['rateecomment'];

	switch ($r['descrate']) {
		case "E":
			$descrate = "Excellent";
			break;
		case "VS":
			$descrate = "Very Satisfactory";
			break;
		case "S":
			$descrate = "Satisfactory";
			break;
		case "US":
			$descrate = "Unsatisfactory";
			break;
		case "VU":
			$descrate = "Very Unsatisfactory";
			break;
	}

	$sql = mysql_query("SELECT q_no, title, description, rate FROM `appraisal`, `appraisal_answer` WHERE appraisal.appraisal_id = appraisal_answer.appraisal_id AND details_id = '$detailsId'") or die(mysql_error());


?>
	<div class="table-height">
		<table class="table table-bordered" width="100%">
			<thead>
				<tr>
					<th colspan="3">GUIDE QUESTIONS</th>
					<th>RATE</th>
				</tr>
			</thead>
			<tbody>
				<?php

				while ($row = mysql_fetch_array($sql)) {

					echo "
									<tr>
										<td colspan='3'>" . $row['q_no'] . ". " . $row['title'] . "<br><small>" . $row['description'] . "</small></td>
										<th>" . $row['rate'] . "</th>
									</tr>
								";
				}

				echo "
								<tr>
									<th width='20%'>Descriptive Rating</th>
									<td widtd='45%'>" . $descrate . "</th>
									<th width=''>Numerical Rating</th>
									<th width=''>" . $numrate . "</th>
								</tr>
								<tr>
									<th>Rater's Comment</th>
									<td colspan='3'>
										<textarea class='form-control' readonly=''>" . $ratercomment . "</textarea>
									</td>
								</tr>
								<tr>
									<th>Ratee's Comment</th>
									<td colspan='3'>
										<textarea class='form-control' readonly=''>" . $rateecomment . "</textarea>
									</td>
								</tr>
							";
				?>
			</tbody>
		</table>
	</div><?php
		} else if ($_GET['request'] == "application") {

			$empId = $_POST['empId'];

			$appDetails = mysql_query("SELECT date_brief, date_hired, date_applied, aeregular, exam_results, position_applied, date_examined FROM application_details WHERE app_id = '$empId'") or die(mysql_error());
			while ($row = mysql_fetch_array($appDetails)) {

				if ($row['date_applied'] == '' || $row['date_applied'] == '0000-00-00') {
					$dateApplied = '';
				} else {
					$dateApplied = date('m/d/Y', strtotime($row['date_applied']));
				}
				if ($row['date_hired'] == '' || $row['date_hired'] == '0000-00-00') {
					$dateHired = '';
				} else {
					$dateHired 	 = date('m/d/Y', strtotime($row['date_hired']));
				}
				if ($row['date_brief'] == '' || $row['date_brief'] == '0000-00-00') {
					$dateBrief = '';
				} else {
					$dateBrief 	 = date('m/d/Y', strtotime($row['date_brief']));
				}
				if ($row['date_examined'] == '' || $row['date_examined'] == '0000-00-00') {
					$dateExamined = '';
				} else {
					$dateExamined 	 = date('m/d/Y', strtotime($row['date_examined']));
				}
				$examResult	 = $row['exam_results'];
				$posApplied  = $row['position_applied'];
				$aeRegular   = $row['aeregular'];
			}

			?>
	<div class="modf">Application History
		<button name="edit" id="edit-apphis" class="btn btn-primary btn-sm" onclick="edit_apphis()">edit</button>
		<button class="btn btn-primary btn-sm" id="update-apphis" onclick="update(this.id)" style="display:none">update</button>
	</div>
	<table class="table table-bordered">
		<tbody>
			<tr>
				<td width="20%" align="right">Position Applied</td>
				<td width="30%">
					<!-- <input name="posApplied" value="<?php echo $posApplied; ?>" class="form-control inputForm" type="text"> -->
					<select name="posApplied" class="form-control select2 inputForm">
						<option value=""></option>
						<option value=""> --Select-- </option>
						<?php

						$query = mysql_query("SELECT position FROM `positions`") or die(mysql_error());
						while ($p = mysql_fetch_array($query)) { ?>

							<option value="<?php echo $p['position']; ?>" <?php if ($posApplied == $p['position']) : echo "selected=''";
																			endif; ?>><?php echo $p['position']; ?></option><?php
																														}
																															?>
					</select>
				</td>
				<td width="20%" align="right">Date Applied</td>
				<td><input name="dateApplied" value="<?php echo $dateApplied; ?>" placeholder="mm/dd/yyyy" class="form-control datepicker inputForm" type="text"></td>
			</tr>
			<tr>
				<td align="right">Date of Exam</td>
				<td><input name="dateExamined" value="<?php echo $dateExamined; ?>" placeholder="mm/dd/yyyy" class="form-control datepicker inputForm" type="text"></td>
				<td align="right">Exam Result</td>
				<td><input name="examResult" value="<?php echo $examResult; ?>" class="form-control inputForm" type="text"></td>
			</tr>
			<tr>
				<td align="right">Date Briefed</td>
				<td><input name="dateBrief" value="<?php echo $dateBrief; ?>" placeholder="mm/dd/yyyy" class="form-control datepicker inputForm" type="text"></td>
				<td align="right">Date Hired</td>
				<td><input name="dateHired" value="<?php echo $dateHired; ?>" placeholder="mm/dd/yyyy" class="form-control datepicker inputForm" type="text"></td>
			</tr>
			<tr>
				<td align="right">Recommended by (Alturas Employee)</td>
				<td colspan="3"><input name="aeRegular" value="<?php echo $aeRegular; ?>" class="form-control inputForm" type="text"></td>
			</tr>
		</tbody>
	</table>
	<table class="table table-striped" width="100%">
		<thead bgcolor="#f9f9f9">
			<tr>
				<th colspan="11" height="39">Examination History</th>
			</tr>
			<tr bgcolor="#ccc">
				<th width="174">No.</th>
				<th width="130">Examination&nbsp;Date</th>
				<th width="500">Applying&nbsp;For</th>
				<th width="345">Exam&nbsp;Code</th>
				<th width="345">Exam&nbsp;Details</th>
			</tr>
		</thead>
		<tbody>
			<?php

			$x = 0;
			$query = mysql_query("SELECT date_time, description, position FROM application_history WHERE app_id='$empId' AND phase='Examination' AND status='completed'") or die(mysql_error());
			if (mysql_num_rows($query) > 0) {

				while ($result = mysql_fetch_array($query)) {
					$x++;
					$exstr = explode(",", $result['description']);
					$excode = explode(" ", $exstr[1]);
					$exam_val = $empId . "|" . $excode[0];
					echo
					"<tr>
										<td width='174'>" . $x . ".</td>
										<td width='419'>" . $nq->changeDateFormat("M. d, Y", $result['date_time']) . "</td>
										<td width='307'>" . $result['position'] . "</td>
										<td width='419'>" . $excode[0] . "</td>
										<td width='345' align='center'><a href='javascript:void()' onclick='viewExam(\"$exam_val\")'>view</a></td>                
									</tr>";
				}
			}
			?>
		</tbody>
	</table>
	<input class="btn btn-primary btn-sm" onclick="viewAppDetails('<?php echo $empId; ?>')" value="View Application Details" type="button">
	<input class="btn btn-primary btn-sm" onclick="viewInterview('<?php echo $empId; ?>')" value="View Interview Details" type="button">
	<script type="text/javascript">
		$('.datepicker').datepicker({
			inline: true,
			changeYear: true,
			changeMonth: true
		});

		$(".inputForm").prop("disabled", true);
		$('.select2').select2();
	</script>
<?php
		} else if ($_GET['request'] == "examDetails") {

			$examVal = explode("|", $_POST['examVal']);
			$empId 		= $examVal[0];
			$examcode 	= $examVal[1];
			$q1 		= mysql_query("SELECT exam_codename FROM application_examtypes WHERE exam_code='$examcode'") or die(mysql_error());
			$rw1 		= mysql_fetch_array($q1);
			$codename 	= $rw1['exam_codename'];
			$q3 		= mysql_query("SELECT * FROM application_examtypes WHERE exam_code='$examcode'") or die(mysql_error());

			echo "
		<h4>$codename</h4>
		<table class='table'>
			<thead>
		        <tr>
					<th>Exam Type</th>
					<th>Score</th>
					<th>Norm</th>
				</tr>
			</thead>
			<tbody>
			";

			$overall = 0;
			while ($r3 = mysql_fetch_array($q3)) {
				$extype = $r3['exam_type'];
				if ($extype == "EXB") {
					$overall = 28;
				} elseif ($extype == "ACCP-A" || $extype == "ACCP-B") {
					$overall = 10;
				} elseif ($extype == "AIT-A") {
					$overall = 60;
				} elseif ($extype == "AIT-B") {
					$overall = 50;
				} elseif ($extype == "FIT") {
					$overall = 12;
				} elseif ($extype == "NTA" || $extype == "VAT") {
					$overall = 25;
				} elseif ($extype == "STAR" || $extype == "SACHS") {
					$overall = 0;
				} else {
					$overall = 0;
				}

				$q2 = mysql_query("SELECT * FROM application_examdetails WHERE exam_ref LIKE '%$empId' AND exam_type='$extype'") or die(mysql_error());
				$retctr = 0;
				while ($r2 = mysql_fetch_array($q2)) {

					$retctr++;
					$exscore = $r2['exam_score'] . " / " . $overall;
					echo "              
						<tr>
						  	<td>" . $extype;
					if ($retctr > 1) {
						echo " - $retctr(retake)";
					}
					echo "</td>
						  	<td>$exscore</td>
						  	<td>" . $nq->getNorm($extype, $exscore) . "</td>
						</tr>
						";
				}
			}
			$q6 = mysql_query("SELECT result FROM application_exams2take WHERE app_id='$empId' AND exam_cat='$examcode'") or die(mysql_error());
			$r6 = mysql_fetch_array($q6);

			echo "		
				<tr>
			        <td>
						<label for='gender'>Exam result: &ensp;&ensp;</label>";
			$result = $r6['result'];
			if ($result == "passed") {
				echo "<label class='label label-success'>Passed</label>";
			} else if ($result == "assessment") {
				echo "<label class='label label-information'>For Assessment</label>";
			} else if ($result == "failed") {
				echo "<label class='label label-danger'>Failed</label>";
			}
			echo "  
			        </td>
		        	<td></td>
		        	<td align='center'></td>
		      	</tr>
		    </tbody>
	    </table>";
		} else if ($_GET['request'] == "appHistDetails") {

			$empId = $_POST['empId'];
			$sql = mysql_query("SELECT * from application_history where app_id='$empId' ORDER BY no DESC") or die(mysql_error());

?>
	<div class="table-height">
		<table class="table table-striped" width="96%">
			<thead>
				<tr>
					<th>No</th>
					<th>Date Accomplished</th>
					<th>Description</th>
					<th>Applying For</th>
					<th>Status</th>
					<th>Phases / Process</th>
				</tr>
			</thead>
			<tbody>
				<?php

				$sqlNum = mysql_num_rows($sql) + 1;
				while ($row = mysql_fetch_array($sql)) {

					$sqlNum--;
					echo "
									<tr>
										<td>" . $sqlNum . "</td>
										<td>" . date("M. d, Y", strtotime($row['date_time'])) . "</td>
										<td>" . $row['description'] . "</td>
										<td>" . $row['position'] . "</td>
										<td>" . $row['status'] . "</td>
										<td>" . $row['phase'] . "</td>
									</tr>
									";
				}
				?>
			</tbody>
		</table>
	</div>
<?php
		} else if ($_GET['request'] == "interviewDetails") {

			$empId = $_POST['empId'];

			echo "
				<div class='table-height'>
				<table class='table table-bordered' width='100%'>";
			$sqly = mysql_query("SELECT distinct(`group`) FROM `application_interview_details` WHERE `interviewee_id`= '$empId' ORDER BY `group` DESC") or die(mysql_error());
			if (mysql_num_rows($sqly) > 0) {
				//if kung naay interview history
				$sql = mysql_query("SELECT distinct(`group`) FROM `application_interview_details` WHERE `interviewee_id`= '$empId' ORDER BY `group` DESC") or die(mysql_error());
			} else {
				//else kung walay interview history
				$sql = mysql_query("SELECT distinct(`group`) FROM `application_interview_details_history` WHERE `interviewee_id`='$empId' ORDER BY `group` DESC") or die(mysql_error());
			}
			if (mysql_num_rows($sql) > 0) {
				while ($row = mysql_fetch_array($sql)) {
					echo "
			    		  	<tr bgcolor='#CCCCCC'>
								<th colspan='4'>Date Interviewed - ";
					$sqly = mysql_query("SELECT distinct(`date_interviewed`) FROM `application_interview_details_history` WHERE `group` = '" . $row['group'] . "'") or die(mysql_error());
					$rowy = mysql_fetch_array($sqly);
					echo $nq->changeDateFormat('F d, Y', $rowy['date_interviewed']);
					echo "</th>
							</tr>
							<tr>    			
								<th>Interview Code</th>
								<th>Interviewer</th>
								<th>Status</th>
								<th>Remarks</th>
							</tr>";

					$sqls = mysql_query("SELECT * FROM `application_interview_details_history` WHERE `interviewee_id` = '$empId' and `group`= '" . $row['group'] . "' ORDER BY interviewee_level ASC") or die(mysql_error());
					while ($rows = mysql_fetch_array($sqls)) {
						$go = $rows['interviewee_id'] . "/" . $rows['interviewee_level'] . "/" . $rows['interview_code'];
						echo
						"<tr>      			
			      				<td>" . $rows['interview_code'] . "</td>
			      				<td>&nbsp;&nbsp;";
						$emp = mysql_query("SELECT distinct(`name`) FROM employee3 WHERE `emp_id`= '" . $rows['interviewer_id'] . "'") or die(mysql_error());
						if (mysql_num_rows($emp)) {
							$em = mysql_fetch_array($emp);
							echo $em['name'];
						} else {
							$sql = mysql_query("SELECT name,position FROM users4owner WHERE user_id = '" . $rows['interviewer_id'] . "'") or die(mysql_error());
							$tab = mysql_fetch_array($sql);
							echo $tab['name'];
						}
						echo
						"</td>
			      				<td>&nbsp;&nbsp;" . $rows['interview_status'] . "</td>
			      				<td><p align='justify' style='padding:10px'>" . nl2br(trim($rows['interviewer_remarks'])) . "</p></td>";
						echo "
			            	</tr>";
					}
				}
			} else {
				echo "
			      		<tr bgcolor='#CCCCCC'>
							<th colspan='4'>Date Interviewed</th>
					  	</tr>
						<tr>    			
							<th>Interview Code</th>
							<th>Interviewer</th>
							<th>Status</th>
							<th>Remarks</th>
						</tr>";
			}
			echo "</table></div>";
		} else if ($_GET['request'] == "employment") {

			$empId = $_POST['empId'];

?>
	<div class="modf">Contract History
		<button id="add-contract" class="btn btn-primary btn-sm" onclick="add_contract()">add</button>
	</div>
	<p>
		<i class="text-red">Note:</i> There should <code>ONLY BE ONE CURRENT CONTRACT</code> and that should be the latest contract of the employee.<br>
		<span style="display:inline-block; width: 35px;"></span>When adding <code>PREVIOUS CONTRACT</code>, status should not be active.
	</p>

	<!-- ./current Contract -->
	<h4><span class="btn btn-success btn-xs">CURRENT CONTRACT</span></h4>
	<table class="table table-hover" width="100%">
		<thead>
			<tr>
				<th width="1%">No</th>
				<th>Position</th>
				<th>Company</th>
				<th>BusinessUnit</th>
				<th>Department</th>
				<th>Status</th>
				<th>Startdate</th>
				<th>EOCdate</th>
				<th width="9%">Action</th>
			</tr>
		</thead>
		<tbody>
			<?php

			$no = 0;
			$sql = mysql_query("SELECT record_no, emp_id, startdate, eocdate, emp_type, current_status, company_code, bunit_code, dept_code, section_code, position FROM `employee3` WHERE emp_id = '$empId'") or die(mysql_error());
			while ($row = mysql_fetch_array($sql)) {

				if ($row['startdate'] == "0000-00-00") : $startdate = '';
				else : $startdate = date("m/d/Y", strtotime($row['startdate']));
				endif;
				if ($row['eocdate'] == "0000-00-00") : $eocdate = '';
				else : $eocdate = date("m/d/Y", strtotime($row['eocdate']));
				endif;
				$recordNo = $row['record_no'];

				$no++;
				if ($row['emp_type'] == "Promo" || $row['emp_type'] == "Promo-NESCO" || $row['emp_type'] == "Promo-EasyL") {

					$promoSql = mysql_query("SELECT promo_company, promo_department FROM `promo_record` WHERE emp_id= '$empId' AND record_no ='" . $row['record_no'] . "'") or die(mysql_error());
					$rec = mysql_fetch_array($promoSql);

					$storeName = "";
					$ctr = 0;
					$bunit = mysql_query("SELECT bunit_field, bunit_acronym FROM `locate_promo_business_unit`") or die(mysql_error());
					while ($str = mysql_fetch_array($bunit)) {

						$promo = mysql_query("SELECT promo_id FROM `promo_record` WHERE `" . $str['bunit_field'] . "` = 'T' AND emp_id = '$empId' AND record_no='" . $row['record_no'] . "'") or die(mysql_error());
						if (mysql_num_rows($promo) > 0) {

							$ctr++;
							if ($ctr == 1) {

								$storeName = $str['bunit_acronym'];
							} else {

								$storeName .= ", " . $str['bunit_acronym'];
							}
						}
					}

					echo "
									<tr>
										<td>" . $no . ".</td>
										<td>" . $row['position'] . "</td>
										<td>" . $rec['promo_company'] . "</td>
										<td>" . $storeName . "</td>
										<td>" . $rec['promo_department'] . "</td>
										<td>" . $row['current_status'] . "</td>
										<td>" . $startdate . "</td>
										<td>" . $eocdate . "</td>
										<td>
											<a href='javascript:void(0)' onclick='viewPromoDetails(\"current\",\"$recordNo\",\"$empId\")' title='View Promo Information' class='text-success'><i class='glyphicon glyphicon-info-sign'></i></a>
											<a href='javascript:void(0)' onclick='updatePromoDetails(\"current\",\"$recordNo\",\"$empId\")' title='Edit Employment History' class='text-red'><i class='glyphicon glyphicon-pencil'></i></a>
											<a href='javascript:void(0)' onclick='uploadPromoScannedFile(\"current\",\"$recordNo\",\"$empId\")' title='Upload Scanned Contract'><i class='glyphicon glyphicon-upload'></i></a>
										</td>
									</tr>
								";
				} else {

					if ($nq->getBUAcroname($row['bunit_code'], $row['company_code']) != "") : $bunitName = $nq->getBUAcroname($row['bunit_code'], $row['company_code']);
					else : $bunitName = $nq->getBusinessUnitName($row['bunit_code'], $row['company_code']);
					endif;
					if ($nq->getDeptAcroname($row['dept_code'], $row['bunit_code'], $row['company_code']) != "") : $deptName = $nq->getDeptAcroname($row['dept_code'], $row['bunit_code'], $row['company_code']);
					else : $deptName = $nq->getDepartmentName($row['dept_code'], $row['bunit_code'], $row['company_code']);
					endif;

					echo "
									<tr>
										<td>" . $no . ".</td>
										<td>" . $row['position'] . "</td>
										<td>" . $nq->getCompanyAcroname($row['company_code']) . "</td>
										<td>" . $bunitName . "</td>
										<td>" . $deptName . "</td>
										<td>" . $row['current_status'] . "</td>
										<td>" . $startdate . "</td>
										<td>" . $eocdate . "</td>
										<td>
											<a href='javascript:void(0)' onclick='viewDetails(\"current\",\"$recordNo\",\"$empId\")' title='View Promo Information' class='text-success'><i class='glyphicon glyphicon-info-sign'></i></a>
											<a href='javascript:void(0)' onclick='updateDetails(\"current\",\"$recordNo\",\"$empId\")' title='Edit Employment History' class='text-red'><i class='glyphicon glyphicon-pencil'></i></a>
											<a href='javascript:void(0)' onclick='uploadScannedFile(\"current\",\"$recordNo\",\"$empId\")' title='Upload Scanned Contract'><i class='glyphicon glyphicon-upload'></i></a>
										</td>
									</tr>
								";
				}
			}
			?>
		</tbody>
	</table>

	<!-- ./previous Contract -->
	<h4><span class="btn btn-danger btn-xs">PREVIOUS CONTRACT</span></h4>
	<table class="table table-hover" width="100%">
		<thead>
			<tr>
				<th width="1%">No</th>
				<th>Position</th>
				<th>Company</th>
				<th>BusinessUnit</th>
				<th>Department</th>
				<th>Status</th>
				<th>Startdate</th>
				<th>EOCdate</th>
				<th width="9%">Action</th>
			</tr>
		</thead>
		<tbody>
			<?php

			$no = 0;
			$sql2 = mysql_query("SELECT record_no, emp_id, startdate, eocdate, emp_type, current_status, company_code, bunit_code, dept_code, section_code, position FROM `employmentrecord_` WHERE emp_id = '$empId' ORDER BY startdate DESC") or die(mysql_error());
			while ($row2 = mysql_fetch_array($sql2)) {

				$recordNo = $row2['record_no'];
				if ($row2['startdate'] == "0000-00-00") : $startdate = '';
				else : $startdate = date("m/d/Y", strtotime($row2['startdate']));
				endif;
				if ($row2['eocdate'] == "0000-00-00") : $eocdate = '';
				else : $eocdate = date("m/d/Y", strtotime($row2['eocdate']));
				endif;

				$no++;

				if ($row2['emp_type'] == "Promo" || $row2['emp_type'] == "Promo-NESCO" || $row2['emp_type'] == "Promo-EasyL") {

					$promoSql2 = mysql_query("SELECT promo_company, promo_department FROM `promo_history_record` WHERE emp_id= '$empId' AND record_no ='" . $row2['record_no'] . "'") or die(mysql_error());
					$rec2 = mysql_fetch_array($promoSql2);

					$storeName = "";
					$ctr = 0;
					$bunit2 = mysql_query("SELECT bunit_field, bunit_acronym FROM `locate_promo_business_unit`") or die(mysql_error());
					while ($str2 = mysql_fetch_array($bunit2)) {

						$promo2 = mysql_query("SELECT promo_id FROM `promo_history_record` WHERE `" . $str2['bunit_field'] . "` = 'T' AND emp_id = '$empId' AND record_no='" . $row2['record_no'] . "'") or die(mysql_error());
						if (mysql_num_rows($promo2) > 0) {

							$ctr++;
							if ($ctr == 1) {

								$storeName = $str2['bunit_acronym'];
							} else {

								$storeName .= ", " . $str2['bunit_acronym'];
							}
						}
					}

					echo "
									<tr>
										<td>" . $no . ".</td>
										<td>" . $row2['position'] . "</td>
										<td>" . $rec2['promo_company'] . "</td>
										<td>" . $storeName . "</td>
										<td>" . $rec2['promo_department'] . "</td>
										<td>" . $row2['current_status'] . "</td>
										<td>" . $startdate . "</td>
										<td>" . $eocdate . "</td>
										<td>
											<a href='javascript:void(0)' onclick='viewPromoDetails(\"previous\",\"$recordNo\",\"$empId\")' title='View Promo Information' class='text-success'><i class='glyphicon glyphicon-info-sign'></i></a>
											<a href='javascript:void(0)' onclick='updatePromoDetails(\"previous\",\"$recordNo\",\"$empId\")' title='Edit Employment History' class='text-red'><i class='glyphicon glyphicon-pencil'></i></a>
											<a href='javascript:void(0)' onclick='uploadPromoScannedFile(\"previous\",\"$recordNo\",\"$empId\")' title='Upload Scanned Contract'><i class='glyphicon glyphicon-upload'></i></a>
										</td>
									</tr>
								";
				} else {

					if ($nq->getBUAcroname($row2['bunit_code'], $row2['company_code']) != "") : $bunitName = $nq->getBUAcroname($row2['bunit_code'], $row2['company_code']);
					else : $bunitName = $nq->getBusinessUnitName($row2['bunit_code'], $row2['company_code']);
					endif;
					if ($nq->getDeptAcroname($row2['dept_code'], $row2['bunit_code'], $row2['company_code']) != "") : $deptName = $nq->getDeptAcroname($row2['dept_code'], $row2['bunit_code'], $row2['company_code']);
					else : $deptName = $nq->getDepartmentName($row2['dept_code'], $row2['bunit_code'], $row2['company_code']);
					endif;

					echo "
									<tr>
										<td>" . $no . ".</td>
										<td>" . $row2['position'] . "</td>
										<td>" . $nq->getCompanyAcroname($row2['company_code']) . "</td>
										<td>" . $bunitName . "</td>
										<td>" . $deptName . "</td>
										<td>" . $row2['current_status'] . "</td>
										<td>" . $startdate . "</td>
										<td>" . $eocdate . "</td>
										<td>
											<a href='javascript:void(0)' onclick='viewDetails(\"previous\",\"$recordNo\",\"$empId\")' title='View Promo Information' class='text-success'><i class='glyphicon glyphicon-info-sign'></i></a>
											<a href='javascript:void(0)' onclick='updateDetails(\"previous\",\"$recordNo\",\"$empId\")' title='Edit Employment History' class='text-red'><i class='glyphicon glyphicon-pencil'></i></a>
											<a href='javascript:void(0)' onclick='uploadScannedFile(\"previous\",\"$recordNo\",\"$empId\")' title='Upload Scanned Contract'><i class='glyphicon glyphicon-upload'></i></a>
										</td>
									</tr>
								";
				}
			}

			?>
		</tbody>
	</table>
<?php
		} else if ($_GET['request'] == "addContract") {

			$empId = $_POST['empId'];

?>
	<style type="text/css">
		.datepicker {
			z-index: 9999 !important
		}
	</style>
	<input type="hidden" name="empId" value="<?php echo $empId; ?>">
	<div class="row">
		<div class="col-md-6">
			<div class="form-group">
				<label>Agency</label>
				<select class="form-control" name="agency" style="width: 100%;" onchange="select_agency(this.value)">
					<option value=""> --Select-- </option>
					<?php

					$comp = mysql_query("SELECT * FROM $datab2.`promo_locate_agency` ORDER BY agency_name ASC") or die(mysql_error());
					while ($com = mysql_fetch_array($comp)) { ?>

						<option value="<?php echo $com['agency_code']; ?>"><?php echo $com['agency_name']; ?></option><?php
																													}
																														?>
				</select>
			</div>
			<div class="form-group"> <i class="text-red">*</i>
				<label>Company</label>
				<select class="form-control" name="company" style="width: 100%;" onchange="inputField(this.name)">
					<option value=""> --Select-- </option>
					<?php

					$comp = mysql_query("SELECT pc_code, pc_name FROM `locate_promo_company` ORDER BY pc_name ASC") or die(mysql_error());
					while ($com = mysql_fetch_array($comp)) { ?>

						<option value="<?php echo $com['pc_code']; ?>"><?php echo $com['pc_name']; ?></option><?php
																											}
																												?>
				</select>
			</div>
			<div class="form-group"> <i class="text-red">*</i>
				<label>Promo Type</label>
				<select name="promoType" class="form-control" onchange="locateBunit(this.value)">
					<option value="STATION">STATION</option>
					<option value="ROVING">ROVING</option>
				</select>
			</div>
			<div class="form-group">
				<div class="store">
					<table class="table table-bordered">
						<tr>
							<th colspan="2"><i class="text-red">*</i> Business Unit</th>
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
				</div>
			</div>
			<div class="form-group"> <i class="text-red">*</i>
				<label>Department</label>
				<select name="department" class="form-control" onchange="inputField(this.name)">
					<option value=""> --Select-- </option>
				</select>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group"><i class="text-red">*</i>
				<label>Startdate</label>
				<div class="input-group">
					<div class="input-group-addon">
						<i class="fa fa-calendar"></i>
					</div>
					<?php
					if ($promoUserType == "promo2") :

						echo "<input type='text' name='startdate' class='form-control datepicker' placeholder='mm/dd/yyyy'>";
					else : ?>

						<input type="text" name="startdate" class="form-control datepicker" placeholder="mm/dd/yyyy" data-inputmask="'alias': 'mm/dd/yyyy'" data-mask><?php
																																									endif;
																																										?>
				</div>
			</div>
			<div class="form-group"> <i class="text-red">*</i>
				<label>EOCdate</label>
				<div class="input-group">
					<div class="input-group-addon">
						<i class="fa fa-calendar"></i>
					</div>
					<input type="text" name="eocdate" class="form-control datepicker" placeholder="mm/dd/yyyy" data-inputmask="'alias': 'mm/dd/yyyy'" data-mask onchange="durationContract(this.value)">
				</div>
			</div>
			<input type="hidden" name="duration">
			<div class="form-group"> <i class="text-red">*</i>
				<label>Position</label>
				<select class="form-control" name="position" style="width: 100%;" onchange="inputField(this.name)">
					<option value=""> --Select-- </option>
					<?php

					$pos = mysql_query("SELECT position FROM `positions` ORDER BY position ASC") or die(mysql_error());
					while ($p = mysql_fetch_array($pos)) { ?>

						<option value="<?php echo $p['position']; ?>"><?php echo $p['position']; ?></option><?php
																										}
																											?>
				</select>
			</div>
			<div class="form-group"> <i class="text-red">*</i>
				<label>Employee Type</label>
				<select name="empType" class="form-control" onchange="inputField(this.name)">
					<option value=""> --Select-- </option>
					<?php

					$empTyp = mysql_query("SELECT emp_type FROM `employee_type` WHERE emp_type = 'Promo' OR emp_type = 'Promo-NESCO' ORDER BY emp_type ASC") or die(mysql_error());
					while ($empT = mysql_fetch_array($empTyp)) { ?>

						<option value="<?php echo $empT['emp_type']; ?>"><?php echo $empT['emp_type']; ?></option><?php
																												}
																													?>
				</select>
			</div>
			<div class="form-group">
				<label>Contract Type</label>
				<select name="contractType" class="form-control">
					<option value="Contractual">Contractual</option>
					<option value="Seasonal">Seasonal</option>
				</select>
			</div>
			<div class="form-group"> <i class="text-red">*</i>
				<label>Current Status</label>
				<select name="current_status" class="form-control" onchange="inputField(this.name)">
					<option value=""> --Select-- </option>
					<option value="Active">Active</option>
					<option value="End of Contract">End of Contract</option>
					<option value="Resigned">Resigned</option>
					<?php if ($loginId == "06359-2013") : ?>
						<option value="blacklisted">blacklisted</option>
					<?php endif; ?>
				</select>
			</div>
			<div class="form-group">
				<label>Remarks</label>
				<textarea name="remarks" class="form-control" rows="6"></textarea>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		$('.datepicker').datepicker({
			inline: true,
			changeYear: true,
			changeMonth: true
		});

		$("[data-mask]").inputmask();

		$('.select2').select2();
	</script>
<?php
		} else if ($_GET['request'] == "contractPromoDetails") {

			$contract = $_POST['contract'];
			$recordNo = $_POST['recordNo'];
			$empId 	= $_POST['empId'];

			if ($contract == "current") :

				$table1 = "employee3";
				$table2 = "promo_record";
			else :

				$table1 = "employmentrecord_";
				$table2 = "promo_history_record";
			endif;

			$sql = mysql_query("SELECT startdate, eocdate, current_status, position, remarks, promo_company, promo_department, promo_type, type 
								FROM `$table1`, `$table2` 
									WHERE $table1.record_no = $table2.record_no AND $table1.emp_id = '$empId' AND $table1.record_no = '$recordNo'") or die(mysql_error());
			$row = mysql_fetch_array($sql);

			if ($row['startdate'] == "0000-00-00") : $startdate = '';
			else : $startdate = date("m/d/Y", strtotime($row['startdate']));
			endif;
			if ($row['eocdate'] == "0000-00-00") : $eocdate = '';
			else : $eocdate = date("m/d/Y", strtotime($row['eocdate']));
			endif;

?>
	<div class="table-height">
		<table class="table table-bordered">
			<tbody>
				<tr>
					<td width="28%">Employee ID</td>
					<th width="22%"><?php echo $empId; ?></th>
					<td width="28%">Record No.</td>
					<th width="22%"><?php echo $recordNo; ?></th>
				</tr>
				<tr>
					<td>Position</td>
					<th><?php echo $row['position']; ?></th>
					<td>Current Status</td>
					<th><?php echo $row['current_status']; ?></th>
				</tr>
				<tr>
					<td>Promo Type</td>
					<th><?php echo $row['promo_type']; ?></th>
					<td>Contract Type</td>
					<th><?php echo $row['type']; ?></th>
				</tr>
				<tr>
					<td>Startdate</td>
					<th><?php echo $startdate; ?></td>
					<td>EOCdate</td>
					<th><?php echo $eocdate; ?></th>
				</tr>
				<tr>
					<td>Company</td>
					<th><?php echo $row['promo_company']; ?></th>
					<td>Department</td>
					<th><?php echo $row['promo_department']; ?></th>
				</tr>
				<?php

				$storeName = "";
				$bunit = mysql_query("SELECT bunit_field, bunit_name, bunit_epascode, bunit_intro FROM `locate_promo_business_unit`") or die(mysql_error());
				while ($str = mysql_fetch_array($bunit)) {

					$promo = mysql_query("SELECT promo_id, " . $str['bunit_epascode'] . ", " . $str['bunit_intro'] . " FROM `$table2` WHERE `" . $str['bunit_field'] . "` = 'T' AND emp_id = '$empId' AND record_no='$recordNo'") or die(mysql_error());
					$p = mysql_fetch_array($promo);
					if (mysql_num_rows($promo) > 0) {

						// query epascode
						$epas = mysql_query("SELECT details_id, numrate, descrate FROM `appraisal_details` WHERE emp_id ='$empId' AND record_no = '$recordNo' AND store = '" . $str['bunit_name'] . "'") or die(mysql_error());
						$epasNum = mysql_num_rows($epas);
						$e = mysql_fetch_array($epas);

						if ($epasNum == 1) : $displayEpas = "<button class='btn btn-primary btn-sm btn-flat btn-block' onclick='viewAppraisalDetails(\"$e[details_id]\")'>" . $e['numrate'] . " - " . $e['descrate'] . " &nbsp;[ View Epas ]</button>";
						else : $displayEpas = "";
						endif;
						if (!empty($p[$str['bunit_intro']])) : $displayIntro = "<button class='btn btn-primary btn-sm btn-flat btn-block' onclick='viewFile(\"promoFile\",\"$table2\",\"$str[bunit_intro]\",\"$empId\",\"$recordNo\")'>View Intro</button>";
						else : $displayIntro = "";
						endif;

						echo "
										<tr>
											<td> EPAS - " . ucwords(strtolower($str['bunit_name'])) . "</td>
											<td>" . $displayEpas . "</td>
											<td> Intro - " . ucwords(strtolower($str['bunit_name'])) . "</td>
											<td>" . $displayIntro . "</td>
										</tr>
									";
					}
				}
				?>
				<?php

				$storeName = "";
				$bunit = mysql_query("SELECT bunit_field, bunit_name, bunit_clearance, bunit_contract FROM `locate_promo_business_unit`") or die(mysql_error());
				while ($str = mysql_fetch_array($bunit)) {

					$promo = mysql_query("SELECT promo_id, " . $str['bunit_clearance'] . ", " . $str['bunit_contract'] . " FROM `$table2` WHERE `" . $str['bunit_field'] . "` = 'T' AND emp_id = '$empId' AND record_no='$recordNo'") or die(mysql_error());
					$p = mysql_fetch_array($promo);
					if (mysql_num_rows($promo) > 0) {

						if (!empty($p[$str['bunit_clearance']])) : $dispalyClearance = "<button class='btn btn-primary btn-sm btn-flat btn-block' onclick='viewFile(\"promoFile\",\"$table2\",\"$str[bunit_clearance]\",\"$empId\",\"$recordNo\")'>View Clearance</button>";
						else : $dispalyClearance = "";
						endif;
						if (!empty($p[$str['bunit_contract']])) : $displayContract = "<button class='btn btn-primary btn-sm btn-flat btn-block' onclick='viewFile(\"promoFile\",\"$table2\",\"$str[bunit_contract]\",\"$empId\",\"$recordNo\")'>View Contract</button>";
						else : $displayContract = "";
						endif;

						echo "
										<tr>
											<td> Clearance - " . ucwords(strtolower($str['bunit_name'])) . "</td>
											<td>" . $dispalyClearance . "</td>
											<td> Contract - " . ucwords(strtolower($str['bunit_name'])) . "</td>
											<td>" . $displayContract . "</td>
										</tr>
									";
					}
				}
				?>
				<tr>
					<td>Remarks</td>
					<td colspan="3">
						<textarea class="form-control" readonly=""><?php echo $row['remarks']; ?></textarea>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
<?php
		} else if ($_GET['request'] == "contractDetails") {

			$contract = $_POST['contract'];
			$recordNo = $_POST['recordNo'];
			$empId 	= $_POST['empId'];

			if ($contract == "current") :

				$table = "employee3";
				$posDesc = "position_desc";
			else :

				$table = "employmentrecord_";
				$posDesc = "pos_desc";
			endif;

			$sql = mysql_query("SELECT record_no, startdate, eocdate, emp_type, current_status, company_code, bunit_code, dept_code, section_code, sub_section_code, unit_code, positionlevel, position, $posDesc, lodging, comments, contract, permit, epas_code, clearance, remarks 
								FROM `$table` 
									WHERE record_no = '$recordNo' AND emp_id = '$empId'") or die(mysql_error());
			$row = mysql_fetch_array($sql);

			if ($row['startdate'] == "0000-00-00") : $startdate = '';
			else : $startdate = date("m/d/Y", strtotime($row['startdate']));
			endif;
			if ($row['eocdate'] == "0000-00-00") : $eocdate = '';
			else : $eocdate = date("m/d/Y", strtotime($row['eocdate']));
			endif;

			$dateHired = $nq->getOneField('date_hired', 'application_details', "app_id='$empId'");

?>
	<div class="table-height">
		<table class="table table-bordered">
			<tbody>
				<tr>
					<td width="20%">Position</td>
					<th width="30%"><?php echo $row['position']; ?></th>
					<td width="20%">Current Status</td>
					<th width="30%"><?php echo $row['current_status']; ?></th>
				</tr>
				<tr>
					<td>Position Level</td>
					<th><?php echo $row['positionlevel']; ?></th>
					<td>Startdate</td>
					<th><?php echo $startdate; ?></th>
				</tr>
				<tr>
					<td>Position Description</td>
					<th><?php echo $row[$posDesc]; ?></th>
					<td>EOCdate</td>
					<th><?php echo $eocdate; ?></th>
				</tr>
				<tr>
					<td>Company</td>
					<th><?php echo $nq->getCompanyName($row['company_code']); ?></th>
					<td>Lodging</td>
					<th><?php echo $row['lodging']; ?></th>
				</tr>
				<tr>
					<td>Business Unit</td>
					<th><?php echo $nq->getBusinessUnitName($row['bunit_code'], $row['company_code']); ?></th>
					<td>Remarks</td>
					<th><?php echo $row['remarks']; ?></th>
				</tr>
				<tr>
					<td>Department</td>
					<th><?php echo $nq->getDepartmentName($row['dept_code'], $row['bunit_code'], $row['company_code']); ?></th>
					<td>Comments</td>
					<th><?php echo $row['comments']; ?></th>
				</tr>
				<tr>
					<td>Section</td>
					<th><?php echo $nq->getSectionName($row['section_code'], $row['dept_code'], $row['bunit_code'], $row['company_code']); ?></th>
					<td>Clearance</td>
					<th>
						<?php

						if ($row['clearance'] != "") {
							echo "<button class='btn btn-primary btn-sm btn-flat btn-block' onclick='viewFile(\"promoFile\",\"$table\",\"clearance\",\"$empId\",\"$recordNo\")'>View Clearance</button>";
						}
						?>
					</th>
				</tr>
				<tr>
					<td>Sub Section</td>
					<th><?php echo $nq->getSubSectionName($row['sub_section_code'], $row['section_code'], $row['dept_code'], $row['bunit_code'], $row['company_code']); ?></th>
					<td>Epas</td>
					<th>
						<?php

						$epas = mysql_query("SELECT details_id, numrate, descrate FROM `appraisal_details` WHERE emp_id ='$empId' AND record_no = '$recordNo' AND store = ''") or die(mysql_error());
						$epasNum = mysql_num_rows($epas);
						$e = mysql_fetch_array($epas);

						if ($epasNum == 1) : echo "<button class='btn btn-primary btn-sm btn-flat btn-block' onclick='previewAppraisalDetails(\"$e[details_id]\")'>" . $e['numrate'] . " - " . $e['descrate'] . " &nbsp;[ View Epas ]</button>";
						endif;
						?>
					</th>
				</tr>
				<tr>
					<td>Unit</td>
					<th><?php echo $nq->getUnitName($row['unit_code'], $row['sub_section_code'], $row['section_code'], $row['dept_code'], $row['bunit_code'], $row['company_code']); ?></th>
					<td>Contract</td>
					<th>
						<?php

						if ($row['contract'] != "") {
							echo "<button class='btn btn-primary btn-sm btn-flat btn-block' onclick='viewFile(\"promoFile\",\"$table\",\"contract\",\"$empId\",\"$recordNo\")'>View Contract</button>";
						}
						?>
					</th>
				</tr>
				<tr>
					<td>Employee Type</td>
					<th><?php echo $row['emp_type']; ?></th>
					<td>Date Regular</td>
					<th>
						<?php

						if ($row['emp_type'] == 'Regular') {
							echo $nq->changeDateFormat('m/d/Y', $startdate);
						}
						?>
					</th>
				</tr>
				<tr>
					<td>Record No</td>
					<th><?php echo $row['record_no']; ?></th>
					<td>Date Hired</td>
					<th>
						<?php

						echo $nq->changeDateFormat('m/d/Y', $dateHired);
						?>
					</th>
				</tr>
			</tbody>
		</table>
	</div>
<?php
		} else if ($_GET['request'] == "editContract") {

			$contract = $_POST['contract'];
			$recordNo = $_POST['recordNo'];
			$empId = $_POST['empId'];

			if ($contract == "current") :

				$table1 = "employee3";
				$table2 = "promo_record";
			else :

				$table1 = "employmentrecord_";
				$table2 = "promo_history_record";
			endif;

			$sql = mysql_query("SELECT startdate, eocdate, current_status, position, emp_type, remarks, agency_code, promo_company, promo_department, vendor_code, promo_type, type 
								FROM `$table1` LEFT JOIN `$table2` ON 
									$table1.record_no = $table2.record_no AND $table1.emp_id = $table2.emp_id WHERE $table1.emp_id = '$empId' AND $table1.record_no = '$recordNo'") or die(mysql_error());
			$row = mysql_fetch_array($sql);


			if ($row['startdate'] == "0000-00-00") : $startdate = '';
			else : $startdate = date("m/d/Y", strtotime($row['startdate']));
			endif;
			if ($row['eocdate'] == "0000-00-00") : $eocdate = '';
			else : $eocdate = date("m/d/Y", strtotime($row['eocdate']));
			endif;

			$promoType 		= $row['promo_type'];
			$agency_code 	= $row['agency_code'];
			$department 	= $row['promo_department'];
			$vendor_code 	= $row['vendor_code'];


			$condition = "";

			if ($promoType == "STATION") {

				$bunitQ = mysql_query("SELECT bunit_id, bunit_field FROM `locate_promo_business_unit`") or die(mysql_error());
				while ($bF = mysql_fetch_array($bunitQ)) {

					$bunitId = $bF['bunit_id'];
					$bunitField = $bF['bunit_field'];

					$appPD = mysql_query("SELECT promo_id FROM `$table2` WHERE $bunitField = 'T'  AND emp_id = '$empId'") or die(mysql_error());
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

					$appPD = mysql_query("SELECT promo_id FROM `$table2` WHERE $bunitField = 'T'  AND emp_id = '$empId'") or die(mysql_error());
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

			$emp_products = array();
			$promo_products = mysql_query("SELECT product FROM promo_products WHERE record_no = '" . $_POST['recordNo'] . "' AND emp_id = '" . $_POST['empId'] . "'") or die(mysql_error());
			while ($rowP = mysql_fetch_array($promo_products)) {
				array_push($emp_products, $rowP['product']);
			}

			$statCut = mysql_query("SELECT statCut FROM timekeeping.promo_sched_emp WHERE recordNo = '" . $_POST['recordNo'] . "' AND empId = '" . $_POST['empId'] . "'") or die(mysql_error());
			$sc = mysql_fetch_array($statCut);

?>
	<style type="text/css">
		.datepicker {
			z-index: 9999 !important
		}
	</style>
	<input type="hidden" name="empId" value="<?php echo $empId; ?>">
	<input type="hidden" name="recordNo" value="<?php echo $recordNo; ?>">
	<input type="hidden" name="contract" value="<?php echo $contract; ?>">
	<input type="hidden" name="company_name" value="<?php echo $row['promo_company']; ?>">
	<div class="row">
		<div class="col-md-6">
			<div class="form-group">
				<label>Agency</label>
				<select class="form-control" name="agency" style="width: 100%;" onchange="select_agency(this.value)">
					<option value=""> --Select-- </option>
					<?php

					$comp = mysql_query("SELECT * FROM $datab2.`promo_locate_agency` WHERE status = '1' ORDER BY agency_name ASC") or die(mysql_error());
					while ($com = mysql_fetch_array($comp)) { ?>

						<option value="<?php echo $com['agency_code']; ?>" <?php if ($agency_code == $com['agency_code']) : echo "selected=''";
																			endif; ?>><?php echo $com['agency_name']; ?></option><?php
																																}
																																	?>
				</select>
			</div>
			<div class="form-group"> <i class="text-red">*</i>
				<label>Company</label>
				<select class="form-control" name="company" style="width: 100%;" onchange="selectProduct(this.value)">
					<option value=""> --Select-- </option>
					<?php

					if ($agency_code != 0) {

						$comp = mysql_query("SELECT company_name FROM $datab2.promo_locate_company 
															INNER JOIN  $datab2.promo_locate_agency ON promo_locate_company.agency_code = promo_locate_agency.agency_code 
														WHERE promo_locate_agency.agency_code = '$agency_code'") or die(mysql_error());
						while ($com = mysql_fetch_array($comp)) {

							$com_list = mysql_query("SELECT pc_code FROM locate_promo_company WHERE pc_name = '" . mysql_real_escape_string($com['company_name']) . "'") or die(mysql_error());
							$c = mysql_fetch_array($com_list); ?>

							<option value="<?php echo $c['pc_code']; ?>" <?php if ($row['promo_company'] == $com['company_name']) : echo "selected=''";
																			endif; ?>><?php echo $com['company_name']; ?></option><?php
																																}
																															} else {
																																$comp = mysql_query("SELECT pc_code, pc_name FROM `locate_promo_company` WHERE status = '1' ORDER BY pc_name ASC") or die(mysql_error());
																																while ($com = mysql_fetch_array($comp)) { ?>

							<option value="<?php echo $com['pc_code']; ?>" <?php if ($row['promo_company'] == $com['pc_name']) : echo "selected=''";
																																	endif; ?>><?php echo $com['pc_name']; ?></option><?php
																																													}
																																												}
																																														?>
				</select>
			</div>
			<div class="form-group"> <i class="text-red">*</i>
				<label>Promo Type</label>
				<select name="promoType" class="form-control" onchange="locateBunit(this.value)">
					<option value=""> --Select-- </option>
					<option value="STATION" <?php if ($promoType == "STATION") : echo "selected=''";
											endif; ?>>STATION</option>
					<option value="ROVING" <?php if ($promoType == "ROVING") : echo "selected=''";
											endif; ?>>ROVING</option>
				</select>
			</div>
			<div class="form-group">
				<div class="store">
					<?php if ($row['promo_type'] == "ROVING") { ?>
						<table class="table table-bordered">
							<tr>
								<th colspan="2"><i class="text-red">*</i> Business Unit</th>
							</tr>
							<?php

							$counter = 0;
							$store = mysql_query("SELECT bunit_id, bunit_name, bunit_field FROM `locate_promo_business_unit` WHERE status = 'active' AND hrd_location = '$hrCode'") or die(mysql_error());
							while ($r = mysql_fetch_array($store)) {

								$counter++;
								$bunit = mysql_query("SELECT promo_id FROM `$table2` WHERE $r[bunit_field] = 'T' AND emp_id = '$empId' AND record_no = '$recordNo'") or die(mysql_error());
								$bunitNum = mysql_num_rows($bunit);
							?>
								<tr>
									<td><input type="checkbox" id="check_<?php echo $counter; ?>" name="<?php echo $r['bunit_field']; ?>" value="<?php echo $r['bunit_id'] . '/' . $r['bunit_field']; ?>" <?php if ($bunitNum > 0) {
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
								<th colspan="2"><i class="text-red">*</i> Business Unit</th>
							</tr>
							<?php

							$loop = 0;
							$store = mysql_query("SELECT * FROM `locate_promo_business_unit` WHERE status = 'active' AND hrd_location = '$hrCode'") or die(mysql_error());
							while ($s = mysql_fetch_array($store)) {

								$loop++;
								$bunit = mysql_query("SELECT promo_id FROM `$table2` WHERE $s[bunit_field] = 'T' AND emp_id = '$empId' AND record_no = '$recordNo'") or die(mysql_error());
								$bunitNum = mysql_num_rows($bunit);

							?>
								<tr>
									<td><input type="radio" name="station" id="radio_<?php echo $loop; ?>" value="<?php echo $s['bunit_id'] . '/' . $s['bunit_field']; ?>" <?php if ($bunitNum > 0) {
																																												echo "checked=''";
																																											} ?> onclick="locateDeptS(this.value)" /></td>
									<td><?php echo $s['bunit_name']; ?></td>
								</tr><?php
									}
										?>

							<input type="hidden" name="loop" value="<?php echo $loop; ?>">
						</table>
					<?php } ?>
				</div>
			</div>
			<div class="form-group"> <i class="text-red">*</i>
				<label>Department</label>
				<select name="department" class="form-control" onchange="locate_vendor(this.value)">
					<option value=""> --Select-- </option>
					<?php

					$locDept = mysql_query("SELECT dept_name FROM `locate_promo_department` WHERE status = 'active' $condition GROUP BY dept_name ORDER BY dept_name ASC") or die(mysql_error());
					while ($d = mysql_fetch_array($locDept)) { ?>

						<option value="<?php echo $d['dept_name']; ?>" <?php if ($department == $d['dept_name']) : echo "selected=''";
																		endif; ?>><?php echo $d['dept_name']; ?></option> <?php
																														}
																															?>
				</select>
			</div>
			<div class="form-group">
				<label>Vendor Name</label>
				<select name="vendor" class="form-control select2" required="">
					<option value=""> --Select-- </option>
					<?php

					if ($department == "EASY FIX") {
						$department = 'FIXRITE';
					}

					$vendor_list = mysql_query("SELECT vendor_code, vendor_name FROM `promo_vendor_lists` WHERE department = '" . $department . "' AND vendor_name != '' ORDER BY vendor_name ASC") or die(mysql_error());
					while ($vl = mysql_fetch_array($vendor_list)) { ?>

						<option value="<?php echo $vl['vendor_code']; ?>" <?php if ($vendor_code == $vl['vendor_code']) : echo "selected=''";
																			endif; ?>><?php echo $vl['vendor_name']; ?></option> <?php
																																}
																																	?>
				</select>
			</div>
			<div class="form-group">
				<label>Product</label>
				<select name="product" class="form-control select2" multiple="multiple">
					<option value=""> --Select-- </option>
					<?php
					$products = mysql_query("SELECT product FROM promo_company_products WHERE company = '" . mysql_real_escape_string($row['promo_company']) . "'") or die(mysql_error());
					while ($prod = mysql_fetch_array($products)) {
					?>
						<option value="<?= $prod['product'] ?>" <?php if (in_array($prod['product'], $emp_products)) {
																	echo "selected=''";
																} ?>><?= $prod['product'] ?></option><?php
																									}
																										?>
				</select>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group"><i class="text-red">*</i>
				<label>Startdate</label>
				<div class="input-group">
					<div class="input-group-addon">
						<i class="fa fa-calendar"></i>
					</div>
					<input type="text" value="<?php echo $startdate; ?>" name="startdate" class="form-control datepicker" onchange="startdate()">
				</div>
			</div>
			<div class="form-group"> <i class="text-red">*</i>
				<label>EOCdate</label>
				<div class="input-group">
					<div class="input-group-addon">
						<i class="fa fa-calendar"></i>
					</div>
					<input type="text" value="<?php echo $eocdate; ?>" name="eocdate" class="form-control datepicker" onchange="durationContract(this.value)">
				</div>
			</div>
			<input type="hidden" name="duration">
			<div class="form-group"> <i class="text-red">*</i>
				<label>Position</label>
				<select class="form-control" name="position" style="width: 100%;" onchange="inputField(this.name)">
					<option value=""> --Select-- </option>
					<?php

					$pos = mysql_query("SELECT position FROM `positions` ORDER BY position ASC") or die(mysql_error());
					while ($p = mysql_fetch_array($pos)) { ?>

						<option value="<?php echo $p['position']; ?>" <?php if (strtolower($row['position']) == strtolower($p['position'])) : echo "selected=''";
																		endif; ?>><?php echo $p['position']; ?></option><?php
																													}
																														?>
				</select>
			</div>
			<div class="form-group"> <i class="text-red">*</i>
				<label>Employee Type</label>
				<select name="empType" class="form-control" onchange="inputField(this.name)">
					<option value=""> --Select-- </option>
					<?php

					$empTyp = mysql_query("SELECT emp_type FROM `employee_type` ORDER BY emp_type ASC") or die(mysql_error());
					while ($empT = mysql_fetch_array($empTyp)) { ?>

						<option value="<?php echo $empT['emp_type']; ?>" <?php if ($row['emp_type'] == $empT['emp_type']) : echo "selected=''";
																			endif; ?>><?php echo $empT['emp_type']; ?></option><?php
																															}
																																?>
				</select>
			</div>
			<div class="form-group">
				<label>Contract Type</label>
				<select name="contractType" class="form-control">
					<option value="Contractual" <?php if ($row['type'] == "Contractual") : echo "selected=''";
												endif; ?>>Contractual</option>
					<option value="Seasonal" <?php if ($row['type'] == "Seasonal") : echo "selected=''";
												endif; ?>>Seasonal</option>
				</select>
			</div>
			<div class="form-group"> <i class="text-red">*</i>
				<label>Current Status</label>
				<select name="current_status" class="form-control" onchange="inputField(this.name)">
					<option value="Active" <?php if ($row['current_status'] == "Active") : echo "selected=''";
											endif; ?>>Active</option>
					<option value="End of Contract" <?php if ($row['current_status'] == "End of Contract") : echo "selected=''";
													endif; ?>>End of Contract</option>
					<option value="Resigned" <?php if ($row['current_status'] == "Resigned") : echo "selected=''";
												endif; ?>>Resigned</option>
					<option value="Ad-Resigned" <?php if ($row['current_status'] == "Ad-Resigned") : echo "selected=''";
												endif; ?>>Ad-Resigned</option>
					<option value="V-Resigned" <?php if ($row['current_status'] == "V-Resigned") : echo "selected=''";
												endif; ?>>V-Resigned</option>
					<?php if ($loginId == "06359-2013" || $loginId == "01476-2015") : ?>
						<option value="blacklisted" <?php if ($row['current_status'] == "blacklisted") : echo "selected=''";
													endif; ?>>blacklisted</option>
					<?php endif; ?>
				</select>
			</div>
			<div class="form-group"> <i class="text-red">*</i>
				<label for="cutoff">Cut-off</label>
				<select name="cutoff" id="cutoff" class="form-control">
					<option value=""> --Select-- </option>
					<?php
					echo $sc['statCut'];
					$cutoffs = mysql_query("SELECT startFC, endFC, startSC, endSC, statCut FROM timekeeping.promo_schedule WHERE remark = 'active'") or die(mysql_error());
					while ($co = mysql_fetch_array($cutoffs)) {

						$endFC = ($co['endFC'] != '') ? $co['endFC'] : 'last';
						if ($sc['statCut'] == $co['statCut']) {

							echo '<option value="' . $co['statCut'] . '" selected>' . $co['startFC'] . '-' . $endFC . ' / ' . $co['startSC'] . '-' . $co['endSC'] . '</option>';
						} else {

							echo '<option value="' . $co['statCut'] . '">' . $co['startFC'] . '-' . $endFC . ' / ' . $co['startSC'] . '-' . $co['endSC'] . '</option>';
						}
					}
					?>
				</select>
			</div>
			<div class="form-group">
				<label>Remarks</label>
				<textarea name="remarks" class="form-control" rows="6"><?php echo $row['remarks']; ?></textarea>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		$('.datepicker').datepicker({
			inline: true,
			changeYear: true,
			changeMonth: true
		});

		$('.select2').select2();
		$("span.select2").css("width", "100%");
	</script>
<?php
		} else if ($_GET['request'] == "editContractDetails") {

			$contract = $_POST['contract'];
			$recordNo = $_POST['recordNo'];
			$empId = $_POST['empId'];

			if ($contract == "current") :

				$table = "employee3";
				$posDesc = "position_desc";
			else :

				$table = "employmentrecord_";
				$posDesc = "pos_desc";
			endif;

			$sql = mysql_query("SELECT record_no, startdate, eocdate, emp_type, current_status, company_code, bunit_code, dept_code, section_code, sub_section_code, unit_code, positionlevel, position, $posDesc, lodging, comments, contract, permit, epas_code, clearance, remarks 
								FROM `$table` 
									WHERE record_no = '$recordNo' AND emp_id = '$empId'") or die(mysql_error());
			$row = mysql_fetch_array($sql);

			if ($row['startdate'] == "0000-00-00") : $startdate = '';
			else : $startdate = date("m/d/Y", strtotime($row['startdate']));
			endif;
			if ($row['eocdate'] == "0000-00-00" || $row['eocdate'] == "1970-01-01") : $eocdate = '';
			else : $eocdate = date("m/d/Y", strtotime($row['eocdate']));
			endif;

?>
	<input type="hidden" name="contract" value="<?php echo $contract; ?>">
	<input type="hidden" name="empId" value="<?php echo $empId; ?>">
	<input type="hidden" name="recordNo" value="<?php echo $recordNo; ?>">
	<div class="row">
		<div class="col-md-4">
			<div class="form-group"> <i class="text-red">*</i>
				<label>Company</label>
				<select name="company" class="form-control" onchange="getBusinessUnit(this.value)">
					<option value=""> --Select-- </option>
					<?php

					$sql = mysql_query("SELECT * FROM locate_company ORDER BY company ASC") or die(mysql_error());
					while ($res = mysql_fetch_array($sql)) { ?>

						<option value="<?php echo $res['company_code']; ?>" <?php if ($row['company_code'] == $res['company_code']) : echo "selected=''";
																			endif; ?>><?php echo $res['company']; ?></option> <?php
																															}
																																?>
				</select>
			</div>
			<div class="form-group"> <i class="text-red">*</i>
				<label>Business Unit</label>
				<select name="businessUnit" class="form-control" onchange="getDepartment(this.value)">
					<option value=""> --Select-- </option>
					<?php

					$sql = mysql_query("SELECT * FROM locate_business_unit WHERE company_code = '" . $row['company_code'] . "' ORDER BY business_unit ASC") or die(mysql_error());
					while ($res = mysql_fetch_array($sql)) { ?>

						<option value="<?php echo $res['company_code'] . '/' . $res['bunit_code']; ?>" <?php if ($row['bunit_code'] == $res['bunit_code']) : echo "selected=''";
																										endif; ?>><?php echo $res['business_unit']; ?></option> <?php
																																							}
																																								?>
				</select>
			</div>
			<div class="form-group"> <i class="text-red">*</i>
				<label>Department</label>
				<select name="department" class="form-control" onchange="getSection(this.value)">
					<option value=""> --Select-- </option>
					<?php

					$sql = mysql_query("SELECT * FROM locate_department WHERE company_code = '" . $row['company_code'] . "' AND bunit_code = '" . $row['bunit_code'] . "' ORDER BY dept_name ASC") or die(mysql_error());
					while ($res = mysql_fetch_array($sql)) { ?>

						<option value="<?php echo $res['company_code'] . '/' . $res['bunit_code'] . '/' . $res['dept_code']; ?>" <?php if ($row['dept_code'] == $res['dept_code']) : echo "selected=''";
																																	endif; ?>><?php echo $res['dept_name']; ?></option> <?php
																																													}
																																														?>
				</select>
			</div>
			<div class="form-group">
				<label>Section</label>
				<select name="section" class="form-control" onchange="getSubSection(this.value)">
					<option value=""> --Select-- </option>
					<?php

					$sql = mysql_query("SELECT * FROM locate_section WHERE company_code = '" . $row['company_code'] . "' AND bunit_code = '" . $row['bunit_code'] . "' AND dept_code = '" . $row['dept_code'] . "' ORDER BY section_name ASC") or die(mysql_error());
					while ($res = mysql_fetch_array($sql)) { ?>

						<option value="<?php echo $res['company_code'] . '/' . $res['bunit_code'] . '/' . $res['dept_code'] . '/' . $res['section_code']; ?>" <?php if ($row['section_code'] == $res['section_code']) : echo "selected=''";
																																								endif; ?>><?php echo $res['section_name']; ?></option> <?php
																																																					}
																																																						?>
				</select>
			</div>
			<div class="form-group">
				<label>Sub-section</label>
				<select name="subSection" class="form-control" onchange="getUnit(this.value)">
					<option value=""> --Select-- </option>
					<?php

					$sql = mysql_query("SELECT * FROM locate_sub_section WHERE company_code = '" . $row['company_code'] . "' AND bunit_code = '" . $row['bunit_code'] . "' AND dept_code = '" . $row['dept_code'] . "' AND section_code = '" . $row['section_code'] . "' ORDER BY sub_section_name ASC") or die(mysql_error());
					while ($res = mysql_fetch_array($sql)) { ?>

						<option value="<?php echo $res['company_code'] . '/' . $res['bunit_code'] . '/' . $res['dept_code'] . '/' . $res['section_code'] . '/' . $res['sub_section_code']; ?>" <?php if ($row['sub_section_code'] == $res['sub_section_code']) : echo "selected=''";
																																																endif; ?>><?php echo $res['sub_section_name']; ?></option> <?php
																																																														}
																																																															?>
				</select>
			</div>
			<div class="form-group">
				<label>Unit</label>
				<select name="unit" class="form-control">
					<option value=""> --Select-- </option>
					<?php

					$sql = mysql_query("SELECT * FROM locate_unit WHERE company_code = '" . $row['company_code'] . "' AND bunit_code = '" . $row['bunit_code'] . "' AND dept_code = '" . $row['dept_code'] . "' AND section_code = '" . $row['section_code'] . "' AND sub_section_code = '" . $row['sub_section_code'] . "' ORDER BY unit_name ASC") or die(mysql_error());
					while ($res = mysql_fetch_array($sql)) { ?>

						<option value="<?php echo $res['company_code'] . '/' . $res['bunit_code'] . '/' . $res['dept_code'] . '/' . $res['section_code'] . '/' . $res['sub_section_code'] . '/' . $res['unit_code']; ?>" <?php if ($row['unit_code'] == $res['unit_code']) : echo "selected=''";
																																																							endif; ?>><?php echo $res['unit_name']; ?></option> <?php
																																																																			}
																																																																				?>
				</select>
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group"> <i class="text-red">*</i>
				<label>Startdate</label>
				<div class="input-group">
					<div class="input-group-addon">
						<i class="fa fa-calendar"></i>
					</div>
					<input type="text" name="startdate" value="<?php echo $startdate; ?>" onkeyup="inputField(this.name)" class="form-control" placeholder="mm/dd/yyyy" data-inputmask='"mask": "99/99/9999"' data-mask>
				</div>
			</div>
			<div class="form-group"> <?php if ($contract == "previous") : echo "<i class='text-red'>*</i>";
										endif; ?>
				<label>EOCdate</label>
				<div class="input-group">
					<div class="input-group-addon">
						<i class="fa fa-calendar"></i>
					</div>
					<input type="text" name="eocdate" value="<?php echo $eocdate; ?>" onkeyup="inputField(this.name)" class="form-control" placeholder="mm/dd/yyyy" data-inputmask='"mask": "99/99/9999"' data-mask>
				</div>
			</div>
			<div class="form-group"> <i class="text-red">*</i>
				<label>Position</label>
				<select name="position" class="form-control" onchange="inputField(this.name)">
					<option value=""> --Select-- </option>
					<?php

					$sql = mysql_query("SELECT position FROM positions ORDER BY position ASC") or die(mysql_error());
					while ($pos = mysql_fetch_array($sql)) { ?>

						<option value="<?php echo $pos['position']; ?>" <?php if ($row['position'] == $pos['position']) : echo "selected=''";
																		endif; ?>><?php echo $pos['position']; ?></option> <?php
																														}
																															?>
				</select>
			</div>
			<div class="form-group"> <i class="text-red">*</i>
				<label>Employee Type</label>
				<select name="empType" class="form-control" onchange="inputField(this.name)">
					<option value=""> --Select-- </option>
					<?php

					$sql = mysql_query("SELECT * FROM employee_type ORDER BY emp_type ASC") or die(mysql_error());
					while ($r = mysql_fetch_array($sql)) { ?>

						<option value="<?php echo $r['emp_type']; ?>" <?php if ($row['emp_type'] == $r['emp_type']) : echo "selected=''";
																		endif; ?>><?php echo $r['emp_type']; ?></option> <?php
																														}
																															?>
				</select>
			</div>
			<div class="form-group"> <i class="text-red">*</i>
				<label>Current Status</label>
				<select name="current_status" class="form-control" onchange="inputField(this.name)">
					<option value=""> --Select-- </option>
					<option <?php if ($row['current_status'] == 'Active') : ?>selected<?php endif; ?>>Active</option>
					<option <?php if ($row['current_status'] == 'End of Contract') : ?>selected<?php endif; ?>>End of Contract</option>
					<option <?php if ($row['current_status'] == 'Resigned') : ?>selected<?php endif; ?>>Resigned</option>
					<option <?php if ($row['current_status'] == 'For Promotion') : ?>selected<?php endif; ?>>For Promotion</option>
					<?php if ($loginId == "06359-2013") { ?>
						<option <?php if ($row['current_status'] == 'blacklisted' || $row['current_status'] == "Blacklisted") : ?>selected<?php endif; ?>>blacklisted</option>
					<?php } ?>
				</select>
			</div>
			<div class="form-group">
				<label>Position Level</label>
				<select name="posLevel" class="form-control">
					<option value=""> --Select-- </option>
					<?php

					for ($i = 1; $i <= 16; $i++) { ?>

						<option value="<?php echo $i; ?>" <?php if ($row['positionlevel'] == $i) : echo "selected=''";
															endif; ?>><?php echo $i; ?></option><?php
																							}
																								?>
				</select>
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group">
				<label>Lodging</label>
				<select name="lodging" class="form-control">
					<option value=""> --Select-- </option>
					<option value="Stay-in" <?php if ($row['lodging'] == "Stay-in") : echo "selected=''";
											endif; ?>>Stay-in</option>
					<option value="Stay-out" <?php if ($row['lodging'] == "Stay-out") : echo "selected=''";
												endif; ?>>Stay-out</option>
				</select>
			</div>
			<div class="form-group">
				<label>Position Description</label>
				<textarea name="posDesc" class="form-control" rows="5"><?php echo $row[$posDesc]; ?></textarea>
			</div>
			<div class="form-group">
				<label>Remarks</label>
				<textarea name="remarks" class="form-control" rows="5"><?php echo $row['remarks']; ?></textarea>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		$('.datepicker').datepicker({
			inline: true,
			changeYear: true,
			changeMonth: true
		});
		$("[data-mask]").inputmask();
	</script>
	<?php
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
		} else if ($_GET['request'] == "locate_vendor") {

			$department = htmlspecialchars($_POST['department']);
			if ($department == "EASY FIX") {
				$department = 'FIXRITE';
			}

			echo "<option value=''> --Select-- </option>";
			$query = mysql_query("SELECT vendor_code, vendor_name FROM `promo_vendor_lists` WHERE department = '" . $department . "' AND vendor_name != '' ORDER BY vendor_name ASC") or die(mysql_error());
			while ($row = mysql_fetch_array($query)) {

				echo "<option value='$row[vendor_code]'>$row[vendor_name]</option>";
			}
		} else if ($_GET['request'] == "locateDeptS") {

			$id = explode("/", $_POST['id']);

			echo "<option value=''> --Select-- </option>";
			$query = mysql_query("SELECT dept_name FROM `locate_promo_department` WHERE bunit_id  = '$id[0]' AND status = 'active'") or die(mysql_error());
			while ($row = mysql_fetch_array($query)) {

				echo "<option value='$row[dept_name]'>$row[dept_name]</option>";
			}
		} else if ($_GET['request'] == "locateDeptR") {

			$storeId = explode("||", $_POST['storeId']);
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

			echo "<option value=''> --Select-- </option>";
			$query = mysql_query("SELECT dept_name FROM `locate_promo_department` WHERE $condition AND status = 'active' GROUP BY dept_name") or die(mysql_error());
			while ($row = mysql_fetch_array($query)) {

				echo "<option value='$row[dept_name]'>$row[dept_name]</option>";
			}
		} else if ($_GET['request'] == "uploadPromoScannedFileForm") {

			$contract = $_POST['contract'];
			$recordNo = $_POST['recordNo'];
			$empId = $_POST['empId'];

			if ($contract == "current") :

				$table1 = "employee3";
				$table2 = "promo_record";
			else :

				$table1 = "employmentrecord_";
				$table2 = "promo_history_record";
			endif;

			$counter = 0;
			$store = mysql_query("SELECT bunit_id, bunit_name, bunit_field, bunit_epascode, bunit_contract, bunit_clearance FROM `locate_promo_business_unit` WHERE hrd_location='$hrCode'") or die(mysql_error());
			while ($str = mysql_fetch_array($store)) {

				$bunitName 	= ucwords(strtolower($str['bunit_name']));
				$bunitField = $str['bunit_field'];
				$bunitEpas 	= $str['bunit_epascode'];
				$bunitContract = $str['bunit_contract'];
				$bunitClearance = $str['bunit_clearance'];

				$appPD = mysql_query("SELECT promo_id FROM `$table2` WHERE $bunitField = 'T'  AND emp_id = '$empId' AND record_no = '$recordNo'") or die(mysql_error());
				$appPDNum = mysql_num_rows($appPD);

				if ($appPDNum > 0) {
					$counter++;
		?>

			<input type="hidden" name="epas[]" value="<?php echo $bunitEpas; ?>">
			<input type="hidden" name="clearance[]" value="<?php echo $bunitClearance; ?>">
			<input type="hidden" name="contract[]" value="<?php echo $bunitContract; ?>">

			<div class="row">
				<div class="col-md-4">
					<b><?php echo "Clearance ($bunitName)"; ?></b><br>
					<img id="photo<?php echo $bunitClearance; ?>" class='preview img-responsive' /><br>
					<input type='file' name='<?php echo $bunitClearance; ?>' id='<?php echo $bunitClearance; ?>' class='btn btn-default' onchange='readURL(this,"<?php echo $bunitClearance; ?>");'>
					<input type='button' name='clear<?php echo $bunitClearance; ?>' id='clear<?php echo $bunitClearance; ?>' style='display:none' class='btn btn-default' value='Clear' onclick="clears('<?php echo $bunitClearance; ?>','photo<?php echo $bunitClearance; ?>','clear<?php echo $bunitClearance; ?>')">
					<input type='button' id='<?php echo $bunitClearance; ?>_change' style='display:none;' class='btn btn-primary btn-sm' value='Change Clearance?' onclick='changePhoto("Clearance","<?php echo $bunitClearance; ?>","<?php echo $bunitClearance; ?>_change")'>
				</div>
				<div class="col-md-4">
					<b><?php echo "Contract ($bunitName)"; ?></b><br>
					<img id="photo<?php echo $bunitContract; ?>" class='preview img-responsive' /><br>
					<input type='file' name='<?php echo $bunitContract; ?>' id='<?php echo $bunitContract; ?>' class='btn btn-default' onchange='readURL(this,"<?php echo $bunitContract; ?>");'>
					<input type='button' name='clear<?php echo $bunitContract; ?>' id='clear<?php echo $bunitContract; ?>' style='display:none' class='btn btn-default' value='Clear' onclick="clears('<?php echo $bunitContract; ?>','photo<?php echo $bunitContract; ?>','clear<?php echo $bunitContract; ?>')">
					<input type='button' id='<?php echo $bunitContract; ?>_change' style='display:none;' class='btn btn-primary btn-sm' value='Change Contract?' onclick='changePhoto("Contract","<?php echo $bunitContract; ?>","<?php echo $bunitContract; ?>_change")'>
				</div>
				<div class="col-md-4">
					<b><?php echo "EOC Appraisal ($bunitName)"; ?></b><br>
					<img id="photo<?php echo $bunitEpas; ?>" class='preview img-responsive' /><br>
					<input type='file' name='<?php echo $bunitEpas; ?>' id='<?php echo $bunitEpas; ?>' class='btn btn-default' onchange='readURL(this,"<?php echo $bunitEpas; ?>");'>
					<input type='button' name='clear<?php echo $bunitEpas; ?>' id='clear<?php echo $bunitEpas; ?>' class='btn btn-default' value='Clear' style='display:none' onclick="clears('<?php echo $bunitEpas; ?>','photo<?php echo $bunitEpas; ?>','clear<?php echo $bunitEpas; ?>')">
					<input type='button' id='<?php echo $bunitEpas; ?>_change' style='display:none;' class='btn btn-primary btn-sm' value='Change Epas?' onclick='changePhoto("Epas","<?php echo $bunitEpas; ?>","<?php echo $bunitEpas; ?>_change")'>
				</div>
			</div><br>
	<?php
				}
			} ?>
	<input type="hidden" name="counter" value="<?php echo $counter; ?>">
	<input type="hidden" name="contrata" id="contrata" value="<?php echo $contract; ?>">
	<input type="hidden" name="empId" id="empId" value="<?php echo $empId; ?>">
	<input type="hidden" name="recordNo" id="recordNo" value="<?php echo $recordNo; ?>">
	<script type="text/javascript">
		var counter = $("[name = 'counter']").val();
		var contrata = $("[name = 'contrata']").val();
		var empId = $("[name = 'empId']").val();
		var recordNo = $("[name = 'recordNo']").val();

		var epas = $("[name = 'epas[]']");
		var clearance = $("[name = 'clearance[]']");
		var contract = $("[name = 'contract[]']");

		if (contrata == "current") {

			var table = "promo_record";
		} else {

			var table = "promo_history_record";
		}

		for (var i = 0; i < counter; i++) {

			$('#' + epas[i].value).val('');
			$('#' + clearance[i].value).val('');
			$('#' + contract[i].value).val('');
			$('#clear' + epas[i].value).hide();
			$('#clear' + clearance[i].value).hide();
			$('#clear' + contract[i].value).hide();

			(function(i) {

				$('#photo' + clearance[i].value).removeAttr('src');
				$.ajax({
					type: "POST",
					url: "employee_information_details.php?request=getScannedPromoFile",
					data: {
						table: table,
						recordNo: recordNo,
						empId: empId,
						field: clearance[i].value
					},
					success: function(data) {

						if (data != '') {
							document.getElementById("photo" + clearance[i].value).src = data;
							$('#' + clearance[i].value).hide();
							$('#' + clearance[i].value + "_change").show();
						} else {
							$('#' + clearance[i].value + "_change").hide();
							$('#' + clearance[i].value).show();
						}
					}
				});

				$('#photo' + contract[i].value).removeAttr('src');
				$.ajax({
					type: "POST",
					url: "employee_information_details.php?request=getScannedPromoFile",
					data: {
						table: table,
						recordNo: recordNo,
						empId: empId,
						field: contract[i].value
					},
					success: function(data) {

						if (data != '') {
							document.getElementById("photo" + contract[i].value).src = data;
							$('#' + contract[i].value).hide();
							$('#' + contract[i].value + "_change").show();
						} else {
							$('#' + contract[i].value + "_change").hide();
							$('#' + contract[i].value).show();
						}
					}
				});

				$('#photo' + epas[i].value).removeAttr('src');
				$.ajax({
					type: "POST",
					url: "employee_information_details.php?request=getScannedPromoFile",
					data: {
						table: table,
						recordNo: recordNo,
						empId: empId,
						field: epas[i].value
					},
					success: function(data) {

						if (data != 0) {

							if (data > 0) {

								var alternative = 'images/epas_msg.jpg'
								document.getElementById("photo" + epas[i].value).src = alternative;
								$('#' + epas[i].value).hide();

							} else {

								document.getElementById("photo" + epas[i].value).src = data;
								$('#' + epas[i].value).hide();
								$('#' + epas[i].value + "_change").show();
							}
						} else {
							$('#' + epas[i].value).show();
						}
					}
				});
			})(i);
		}
	</script>

<?php
		} else if ($_GET['request'] == "uploadScannedFileForm") {

			$contract = $_POST['contract'];
			$recordNo = $_POST['recordNo'];
			$empId = $_POST['empId'];

			if ($contract == "current") :

				$table = "employee3";
			else :

				$table = "employmentrecord_";
			endif;

?>
	<input type="hidden" name="contract" value="<?php echo $contract; ?>">
	<input type="hidden" name="empId" value="<?php echo $empId; ?>">
	<input type="hidden" name="recordNo" value="<?php echo $recordNo; ?>">

	<div class="row">
		<div class="col-md-4">
			<b>Clearance</b><br>
			<img id="photoclearance" class='preview img-responsive' /><br>
			<input type='file' name='clearance' id='clearance' class='btn btn-default' onchange='readURL(this,"clearance");'>
			<input type='button' name='clearclearance' id='clearclearance' style='display:none' class='btn btn-default' value='Clear' onclick="clears('clearance','photoclearance','clearclearance')">
			<input type='button' id='clearance_change' style='display:none;' class='btn btn-primary btn-sm' value='Change Clearance?' onclick='changePhoto("Clearance","clearance","clearance_change")'>
		</div>
		<div class="col-md-4">
			<b>Contract</b><br>
			<img id="photocontract" class='preview img-responsive' /><br>
			<input type='file' name='contract' id='contract' class='btn btn-default' onchange='readURL(this,"contract");'>
			<input type='button' name='clearcontract' id='clearcontract' style='display:none' class='btn btn-default' value='Clear' onclick="clears('contract','photocontract','clearcontract')">
			<input type='button' id='contract_change' style='display:none;' class='btn btn-primary btn-sm' value='Change Contract?' onclick='changePhoto("Contract","contract","contract_change")'>
		</div>
		<div class="col-md-4">
			<b>EOC Appraisal</b><br>
			<img id="photoepas" class='preview img-responsive' /><br>
			<input type='file' name='epas' id='epas' class='btn btn-default' onchange='readURL(this,"epas");'>
			<input type='button' name='clearepas' id='clearepas' style='display:none' class='btn btn-default' value='Clear' onclick="clears('epas','photoepas','clearepas')">
			<input type='button' id='epas_change' style='display:none;' class='btn btn-primary btn-sm' value='Change EOC Appraisal?' onclick='changePhoto("EOC Appraisal","epas","epas_change")'>
		</div>
	</div>
	<script type="text/javascript">
		var contract = $("[name = 'contract']").val();
		var empId = $("[name = 'empId']").val();
		var recordNo = $("[name = 'recordNo']").val();

		if (contract == "current") {

			var table = "employee3";
		} else {

			var table = "employmentrecord_";
		}

		$('#epas').val('');
		$('#clerance').val('');
		$('#contract').val('');
		$('#clearepas').hide();
		$('#clearclerance').hide();
		$('#clearcontract').hide();

		$('#photoclearance').removeAttr('src');
		$.ajax({
			type: "POST",
			url: "employee_information_details.php?request=getScannedPromoFile",
			data: {
				table: table,
				recordNo: recordNo,
				empId: empId,
				field: "clearance"
			},
			success: function(data) {

				if (data != '') {
					document.getElementById("photoclearance").src = data;
					$('#clearance').hide();
					$('#clearance_change').show();
				} else {
					$('#clearance_change').hide();
					$('#clearance').show();
				}
			}
		});

		$('#photoepas').removeAttr('src');
		$.ajax({
			type: "POST",
			url: "employee_information_details.php?request=getScannedPromoFile",
			data: {
				table: table,
				recordNo: recordNo,
				empId: empId,
				field: "epas_code"
			},
			success: function(data) {

				if (data != 0) {

					if (data > 0) {

						var alternative = 'images/epas_msg.jpg'
						document.getElementById("photoepas").src = alternative;
						$('#epas').hide();

					} else {

						document.getElementById("photoepas").src = data;
						$('#epas').hide();
						$('#epas_change').show();
					}
				} else {
					$('#epas').show();
				}
			}
		});

		$('#photocontract').removeAttr('src');
		$.ajax({
			type: "POST",
			url: "employee_information_details.php?request=getScannedPromoFile",
			data: {
				table: table,
				recordNo: recordNo,
				empId: empId,
				field: "contract"
			},
			success: function(data) {

				if (data != '') {
					document.getElementById("photocontract").src = data;
					$('#contract').hide();
					$('#contract_change').show();
				} else {
					$('#contract_change').hide();
					$('#contract').show();
				}
			}
		});
	</script>
<?php
		} else if ($_GET['request'] == "getScannedPromoFile") {

			$field = $_POST['field'];
			$table = $_POST['table'];

			$sql 	= mysql_query("SELECT $field FROM $table WHERE emp_id = '" . $_POST['empId'] . "' AND record_no = '" . $_POST['recordNo'] . "'") or die(mysql_error());
			$row 	= mysql_fetch_array($sql);

			die($row[$_POST['field']]);
		} else if ($_GET['request'] == "history") {

			$empId = $_POST['empId'];

			$sql = mysql_query("SELECT * FROM application_employment_history WHERE app_id = '$empId'") or die(mysql_error());

?>
	<div class="modf">Employment History
		<input class="btn btn-primary btn-sm" id="add-emphis" value="add" onclick="add_emphis('')" type="button">
	</div>
	<table class="table table-striped" width="100%">
		<thead>
			<tr>
				<th>No</th>
				<th>Company</th>
				<th>Position</th>
				<th>DateStart</th>
				<th>DateEnd</th>
				<th>Address/Location</th>
				<th>Certificate</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody>
			<?php

			$i = 0;
			while ($row = mysql_fetch_array($sql)) {

				$i++;
				echo "
								<tr>
									<td>" . $i . ".</td>
									<td>" . $row['company'] . "</td>
									<td>" . $row['position'] . "</td>
									<td>" . $row['yr_start'] . "</td>
									<td>" . $row['yr_ends'] . "</td>
									<td>" . $row['address'] . "</td>
									<td>";

				if (!empty($row['emp_certificate'])) {

					echo "<button class='btn btn-primary btn-sm' onclick=viewEmpCert('$row[no]')>view</button>";
				} else {

					echo "none";
				}
				echo "
									</td>
									<td>
										<td><button class='btn btn-primary btn-sm' onclick=add_emphis('$row[no]')>edit</button></td>
									</td>
								</tr>
							";
			}
			?>
		</tbody>
	</table>
<?php
		} else if ($_GET['request'] == "addEmploymentHist") {

			$no 	= $_POST['no'];
			$empId 	= $_POST['empId'];

			$sql = mysql_query("SELECT * FROM application_employment_history WHERE no = '$no'") or die(mysql_error());
			$row = mysql_fetch_array($sql);

?>

	<input type="hidden" name="empId" value="<?php echo $empId; ?>">
	<input type="hidden" name="no" value="<?php echo $no; ?>">
	<div class="form-group"> <i class="text-red">*</i>
		<label>Company</label>
		<input type="text" value="<?php echo $row['company']; ?>" name="company" class="form-control" onkeyup="inputField(this.name)">
	</div>
	<div class="form-group">
		<label>Address</label>
		<input type="text" value="<?php echo $row['address']; ?>" name="address" class="form-control" onkeyup="inputField(this.name)">
	</div>
	<div class="form-group"> <i class="text-red">*</i>
		<label>Position</label>
		<input type="text" value="<?php echo $row['position']; ?>" name="position" class="form-control" onkeyup="inputField(this.name)">
	</div>
	<div class="form-group">
		<label>Employment Certificate</label> <?php if (!empty($row['emp_certificate'])) : echo "<i class='text-red'> - Already Uploaded</i>";
												endif; ?>
		<input type="file" name="certificate" class="form-control">
	</div>
	<div class="row">
		<div class="col-md-6">
			<div class="form-group">
				<label>Date Start</label>
				<input type="text" value="<?php echo $row['yr_start']; ?>" name="startdate" class="form-control" onkeyup="inputField(this.name)">
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
				<label>Date End</label>
				<input type="text" value="<?php echo $row['yr_ends']; ?>" name="eocdate" class="form-control" onkeyup="inputField(this.name)">
			</div>
		</div>
	</div>
<?php
		}

		// insert, update, delete here!
		else if ($_GET['request'] == "update-basicinfo") {

			if ($_POST['suffix'] != "") {
				$suffix = " " . $_POST['suffix'] . ",";
			} else {
				$suffix = "";
			}
			if ($_POST['mname'] != "") {
				$mname = " " . $_POST['mname'];
			} else {
				$mname = "";
			}

			$properName = ucwords(strtolower($_POST['lname'] . ", " . $_POST['fname'] . "" . $suffix . "" . $mname));

			$updatebasicinfo = mysql_query("UPDATE 
											applicant 
										SET 
											lastname 	= '" . utf8_decode(mysql_real_escape_string($_POST['lname'])) . "',
											firstname 	= '" . utf8_decode(mysql_real_escape_string($_POST['fname'])) . "',
											middlename 	= '" . utf8_decode(mysql_real_escape_string($_POST['mname'])) . "',
											birthdate 	= '" . date("Y-m-d", strtotime($_POST['datebirth'])) . "',
											religion 	= '" . mysql_real_escape_string($_POST['religion']) . "',
											civilstatus = '" . mysql_real_escape_string($_POST['civilstatus']) . "',
											gender 		= '" . mysql_real_escape_string($_POST['gender']) . "',
											citizenship = '" . mysql_real_escape_string($_POST['citizenship']) . "',
											bloodtype 	= '" . mysql_real_escape_string($_POST['bloodtype']) . "',
											weight 		= '" . mysql_real_escape_string($_POST['weight']) . "',
											height 		= '" . mysql_real_escape_string($_POST['height']) . "',
											suffix 		= '" . mysql_real_escape_string($_POST['suffix']) . "'
										WHERE 
											app_id = '" . $_POST['empId'] . "'") or die(mysql_error());

			mysql_query("UPDATE
							employee3
						SET
							name = '" . mysql_real_escape_string($properName) . "'
						WHERE
							emp_id = '" . $_POST['empId'] . "'") or die(mysql_error());

			if ($updatebasicinfo) {

				logs($_SESSION['emp_id'], $_SESSION['username'], date("Y-m-d"), date("H:i:s"), 'Updating the Basic Information of ' . $nq->getAppName($_POST['empId']));
				die("success");
			}
		} else if ($_GET['request'] == "update-family") {

			$updatefam = mysql_query(
				"UPDATE 
						applicant 
					 SET 
						mother = '" . mysql_real_escape_string($_POST['mother']) . "', 
						father = '" . mysql_real_escape_string($_POST['father']) . "', 
						guardian = '" . mysql_real_escape_string($_POST['guardian']) . "', 
						spouse = '" . mysql_real_escape_string($_POST['spouse']) . "' 
					 where 
						app_id = '" . $_POST['empId'] . "'
				"
			) or die(mysql_error());

			if ($updatefam) {
				logs($_SESSION['emp_id'], $_SESSION['username'], date("Y-m-d"), date("H:i:s"), 'Updating the Family Information of ' . $nq->getAppName($_POST['empId']));
				die("success");
			}
		} else if ($_GET['request'] == "update-contact") {

			$updatecontact = mysql_query("UPDATE 
										applicant 
									SET 
										home_address ='" . mysql_real_escape_string($_POST['homeaddress']) . "', 
										city_address ='" . mysql_real_escape_string($_POST['cityaddress']) . "', 
										contact_person ='" . mysql_real_escape_string($_POST['contactperson']) . "', 
										contact_person_address ='" . mysql_real_escape_string($_POST['contactpersonadd']) . "',
										contact_person_number ='" . mysql_real_escape_string($_POST['contactpersonno']) . "', 
										contactno ='" . mysql_real_escape_string($_POST['cellphone']) . "', 
										telno='" . mysql_real_escape_string($_POST['telno']) . "', 
										email='" . mysql_real_escape_string($_POST['email']) . "', 
										facebookAcct ='" . mysql_real_escape_string($_POST['fb']) . "', 
										twitterAcct ='" . mysql_real_escape_string($_POST['twitter']) . "'
									WHERE 
										app_id = '" . $_POST['empId'] . "' 
								") or die(mysql_error());
			if ($updatecontact) {
				logs($_SESSION['emp_id'], $_SESSION['username'], date("Y-m-d"), date("H:i:s"), 'Updating the Contact Information of ' . $nq->getAppName($_POST['empId']));
				die("success");
			}
		} else if ($_GET['request'] == "update-educ") {

			$updateeduc = mysql_query(
				"UPDATE 
						applicant 
					 SET 
						attainment='" . mysql_real_escape_string($_POST['attainment']) . "', 
						school='" . mysql_real_escape_string($_POST['school']) . "', 
						course='" . mysql_real_escape_string($_POST['course']) . "' 
					 WHERE 
						app_id = '" . $_POST['empId'] . "' 
				 "
			) or die(mysql_error());

			if ($updateeduc) {
				logs($_SESSION['emp_id'], $_SESSION['username'], date("Y-m-d"), date("H:i:s"), 'Updating the Educational Information of ' . $nq->getAppName($_POST['empId']));
				die("success");
			}
		} else if ($_GET['request'] == "submitSeminar") {

			$no = $_POST['no'];
			$empId = $_POST['appId'];
			$semName = mysql_real_escape_string($_POST['semName']);
			$semDate = $_POST['semDate'];
			$semLocation = mysql_real_escape_string($_POST['semLocation']);

			$destination_path = "";
			if (!empty($_FILES['semCertificate']['name'])) {

				$image		= addslashes(file_get_contents($_FILES['semCertificate']['tmp_name']));
				$image_name	= addslashes($_FILES['semCertificate']['name']);
				$array 	= explode(".", $image_name);

				$filename 	= $empId . "=" . date('Y-m-d') . "=" . 'Seminar-Certificate' . "=" . date('H-i-s-A') . "." . end($array);
				$destination_path	= "../document/seminar_certificate/" . $filename;

				move_uploaded_file($_FILES['semCertificate']['tmp_name'], $destination_path);
			}

			if ($no == "") {

				$seminar = mysql_query("INSERT INTO 
										`application_seminarsandeligibility`
											(`app_id`, `name`, `dates`, `location`, `sem_certificate`) 
										VALUES 
											('$empId','$semName','$semDate','$semLocation','$destination_path')") or die(mysql_error());
				if ($seminar) {
					logs($_SESSION['emp_id'], $_SESSION['username'], date("Y-m-d"), date("H:i:s"), "Adding New Seminar/Eligibility/Training Information of " . $nq->getAppName($empId));
					die("success||Added");
				}
			} else {

				$updateSem = "";
				if (!empty($destination_path)) {

					$updateSem = ", `sem_certificate`='" . $destination_path . "'";
				}

				$seminar = mysql_query("UPDATE `application_seminarsandeligibility` 
										SET 
											`name`='$semName',
											`dates`='$semDate',
											`location`='$semLocation'
											$updateSem
										WHERE 
											`no`='$no' AND `app_id`='$empId'
								") or die(mysql_error());

				if ($seminar) {
					logs($_SESSION['emp_id'], $_SESSION['username'], date("Y-m-d"), date("H:i:s"), "Updated Seminar/Eligibility/Training Information of " . $nq->getAppName($empId));
					die("success||Updated");
				}
			}
		} else if ($_GET['request'] == "seminarCertificate") {

			$no = $_POST['no'];

			$query = mysql_query("SELECT sem_certificate FROM `application_seminarsandeligibility` WHERE no = '$no'") or die(mysql_error());
			$fetch = mysql_fetch_array($query);

			die($fetch['sem_certificate']);
		} else if ($_GET['request'] == "submitCharRef") {

			$no = $_POST['no'];
			$empId = $_POST['empId'];

			if ($no == "") {

				$charRef = mysql_query("INSERT INTO 
										`application_character_ref`
											(`app_id`, `name`, `position`, `contactno`, `company`) 
									VALUES 
											(
												'" . $empId . "',
												'" . mysql_real_escape_string($_POST['charName']) . "',
												'" . mysql_real_escape_string($_POST['charPosition']) . "',
												'" . mysql_real_escape_string($_POST['charContact']) . "',
												'" . mysql_real_escape_string($_POST['charCompanyLocation']) . "'
											)
								") or die(mysql_error());

				if ($charRef) {

					logs($_SESSION['emp_id'], $_SESSION['username'], date("Y-m-d"), date("H:i:s"), "Adding new Character References Details of " . $nq->getAppName($empId));
					die("success||Added");
				}
			} else {

				$charRef = mysql_query(
					"UPDATE 
						application_character_ref 
					 SET 
						name = '" . mysql_real_escape_string($_POST['charName']) . "', 
						position= '" . mysql_real_escape_string($_POST['charPosition']) . "', 
						contactno = '" . mysql_real_escape_string($_POST['charContact']) . "', 
						company = '" . mysql_real_escape_string($_POST['charCompanyLocation']) . "' 
					 WHERE 
						no = '" . $no . "' 
					 AND 
						app_id = '" . $empId . "' 
				 "
				) or die(mysql_error());

				if ($charRef) {
					logs($_SESSION['emp_id'], $_SESSION['username'], date("Y-m-d"), date("H:i:s"), "Updating the Character References Details of " . $nq->getAppName($empId));
					die("success||Updated");
				}
			}
		} else if ($_GET['request'] == "update-skills") {

			$updateskills = mysql_query(
				"UPDATE 
							applicant 
						 set 
							hobbies='" . mysql_real_escape_string(strip_tags(($_POST['hobbies']))) . "', 
							specialSkills='" . mysql_real_escape_string(strip_tags(($_POST['skills']))) . "' 
						 where 
							app_id = '" . $_POST['empId'] . "' 
					"
			) or die(mysql_error());

			if ($updateskills) {

				logs($_SESSION['emp_id'], $_SESSION['username'], date("Y-m-d"), date("H:i:s"), 'Updating the Skills Information of ' . $nq->getAppName($_POST['empId']));
				die("success");
			}
		} else if ($_GET['request'] == "update-apphis") {

			$checkApp = mysql_query("SELECT no FROM application_details where app_id = '" . $_POST['empId'] . "'") or die(mysql_error());

			if ($_POST['dateApplied'] == '') {
				$dateApplied = '';
			} else {
				$dateApplied = date('Y-m-d', strtotime($_POST['dateApplied']));
			}
			if ($_POST['dateHired'] == '') {
				$dateHired = '';
			} else {
				$dateHired 	 = date('Y-m-d', strtotime($_POST['dateHired']));
			}
			if ($_POST['dateBrief'] == '') {
				$dateBrief = '';
			} else {
				$dateBrief 	 = date('Y-m-d', strtotime($_POST['dateBrief']));
			}
			if ($_POST['dateExamined'] == '') {
				$dateExamined = '';
			} else {
				$dateExamined 	 = date('Y-m-d', strtotime($_POST['dateExamined']));
			}

			if (mysql_num_rows($checkApp) > 0) {

				$act = "Updating the Application History Information of";
				$appHis = mysql_query(
					"UPDATE 
									application_details 
							 	SET 
									date_applied = '" . $dateApplied . "',
									date_brief = '" . $dateBrief . "',
									date_hired = '" . $dateHired . "',
									aeregular  = '" . mysql_real_escape_string($_POST['aeRegular']) . "',
									date_examined = '" . $dateExamined . "',
									position_applied= '" . mysql_real_escape_string($_POST['posApplied']) . "',
									exam_results = '" . mysql_real_escape_string($_POST['examResult']) . "'
								WHERE 
									app_id = '" . $_POST['empId'] . "'"
				) or die(mysql_error());
			} else {

				$act = "Adding the Application History of";
				$appHis = mysql_query("INSERT INTO `application_details`
											(`app_id`, `position_applied`, `date_applied`, `exam_results`, `date_examined`, `date_brief`, `date_hired`, `aeregular`) 
										VALUES 
											(
												'" . $_POST['empId'] . "',
												'" . mysql_real_escape_string($_POST['posApplied']) . "',
												'" . $dateApplied . "',
												'" . mysql_real_escape_string($_POST['examResult']) . "',
												'" . $dateExamined . "',
												'" . $dateBrief . "',
												'" . $dateHired . "',
												'" . mysql_real_escape_string($_POST['aeRegular']) . "'
											)
								") or die(mysql_error());
			}

			if ($appHis) {

				logs($_SESSION['emp_id'], $_SESSION['username'], date("Y-m-d"), date("H:i:s"), $act . " " . $nq->getAppName($_POST['empId']));
				die("success");
			}
		} else if ($_GET['request'] == "promoFile") {

			$sql = mysql_query("SELECT " . $_POST['field'] . " FROM " . $_POST['table'] . " WHERE emp_id = '" . $_POST['empId'] . "' AND record_no = '" . $_POST['recordNo'] . "'") or die(mysql_error());
			$fetch  = mysql_fetch_array($sql);

			die($fetch[$_POST['field']]);
		} else if ($_GET['request'] == "updateContract") {

			$company = $nq->getPromoCompanyName($_POST['company']);
			$name = mysql_real_escape_string($nq->getPromoName($_POST['empId']));

			if ($_POST['contract'] == "current") :

				$table1 = "employee3";
				$table2 = "promo_record";
				$updatedBy = "`updated_by`";
			else :

				$table1 = "employmentrecord_";
				$table2 = "promo_history_record";
				$updatedBy = "`updatedby`";
			endif;

			if ($_POST['startdate'] == "0000-00-00") : $startdate = '0000-00-00';
			else : $startdate = date("Y-m-d", strtotime($_POST['startdate']));
			endif;
			if ($_POST['eocdate'] == "0000-00-00") : $eocdate = '0000-00-00';
			else : $eocdate = date("Y-m-d", strtotime($_POST['eocdate']));
			endif;

			$updEmp = mysql_query("UPDATE `$table1` 
									SET 
										`startdate`='" . $startdate . "',
										`eocdate`='" . $eocdate . "',
										`emp_type`='" . $_POST['empType'] . "',
										`duration`='" . mysql_real_escape_string($_POST['duration']) . "',
										`current_status`='" . $_POST['current_status'] . "',
										`position`='" . mysql_real_escape_string($_POST['position']) . "',
										`date_updated`='" . $date . "',
										$updatedBy='" . $loginId . "',
										`remarks`='" . mysql_real_escape_string($_POST['remarks']) . "'
									WHERE 
										record_no = '" . $_POST['recordNo'] . "' AND emp_id = '" . $_POST['empId'] . "'
							") or die(mysql_error());

			$field = "";
			$str = "";
			$addField = "";
			$addValue = "";

			if ($_POST['promoType'] == "STATION") {

				$str = explode("/", $_POST['store']);
				$field = "`$str[1]`='T'";
				$addField = "`$str[1]`";
				$addValue = "'T'";
			} else {

				$counter = 0;

				$strNumArr = explode("||", $_POST['store']);
				for ($i = 0; $i < sizeof($strNumArr) - 1; $i++) {

					$counter++;
					$str = explode("/", $strNumArr[$i]);

					if ($counter == 1) {
						$field = "`$str[1]`='T'";
						$addField .= "`$str[1]`";
						$addValue .= "'T'";
					} else {

						$field .= ",`$str[1]`='T'";
						$addField .= ",`$str[1]`";
						$addValue .= ",'T'";
					}
				}
			}

			// empty stores
			$ctr = 0;
			$setFields = "";
			$displayFields = mysql_query("SELECT bunit_id, bunit_field FROM `locate_promo_business_unit` WHERE hrd_location = '$hrCode'") or die(mysql_error());
			while ($dF = mysql_fetch_array($displayFields)) {

				$ctr++;
				$bunitField = $dF['bunit_field'];
				if ($ctr == 1) {

					$setFields = "`$bunitField` = ''";
				} else {

					$setFields .= ",`$bunitField` = ''";
				}
			}

			mysql_query("UPDATE `$table2` SET $setFields WHERE emp_id= '" . $_POST['empId'] . "' AND record_no= '" . $_POST['recordNo'] . "'") or die(mysql_error());

			$chkPromo = mysql_query("SELECT promo_id FROM $table2 WHERE emp_id= '" . $_POST['empId'] . "' AND record_no= '" . $_POST['recordNo'] . "'") or die(mysql_error());

			$agency_code = 0;
			if ($_POST['agency']) {
				$agency_code = $_POST['agency'];
			}

			if (mysql_num_rows($chkPromo) > 0) {

				$updPromo = mysql_query("UPDATE `$table2` 
										SET 
											`agency_code`='" . $agency_code . "',
											`promo_company`='" . mysql_real_escape_string($company) . "',
											`promo_department`='" . $_POST['department'] . "',
											`vendor_code`='" . $_POST['vendor'] . "',
											`promo_type`='" . $_POST['promoType'] . "',
											$field,
											`type`='" . $_POST['contractType'] . "' 
										WHERE emp_id= '" . $_POST['empId'] . "' AND record_no= '" . $_POST['recordNo'] . "'
									") or die(mysql_error());
			} else {

				$updPromo = mysql_query("INSERT INTO 
												`$table2`
											(`emp_id`, `agency_code`, `promo_company`, `promo_department`, `vendor_code`, $addField, `promo_type`, `record_no`, `type`, `hr_location`) 
										VALUES 
											(
												'" . $_POST['empId'] . "',
												'" . $agency_code . "',
												'" . mysql_real_escape_string($company) . "',
												'" . $_POST['department'] . "',
												'" . $_POST['vendor'] . "',
												$addValue,
												'" . $_POST['promoType'] . "',
												'" . $_POST['recordNo'] . "',
												'" . $_POST['contractType'] . "',
												'" . $hrCode . "'
											)
									") or die(mysql_error());
			}

			mysql_query("DELETE FROM promo_products WHERE record_no = '" . $_POST['recordNo'] . "' AND emp_id = '" . $_POST['empId'] . "'") or die(mysql_error());
			if (count($_POST['product']) > 0 && !empty($_POST['product'])) {

				for ($i = 0; $i < count($_POST['product']); $i++) {

					mysql_query("INSERT INTO promo_products
								(record_no, emp_id, product, created_at)
							VALUES 
								(
								'" . $_POST['recordNo'] . "',
								'" . $_POST['empId'] . "',
								'" . $_POST['product'][$i] . "',
								'" . date('Y-m-d H:i:s') . "'
								)
							") or die(mysql_error());
				}
			}

			$corporate = 'false';
			$talibon = 'false';
			$tubigon = 'false';
			$colon = 'false';
			$query = mysql_query("SELECT bunit_field FROM locate_promo_business_unit WHERE status = 'active'") or die(mysql_error());
			while ($bf = mysql_fetch_array($query)) {

				$chk_store = mysql_query("SELECT COUNT(promo_id) AS exist FROM promo_record WHERE record_no = '" . $_POST['recordNo'] . "' AND emp_id = '" . $_POST['empId'] . "' AND " . $bf['bunit_field'] . " = 'T'") or die(mysql_error());
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

			$query = mysql_query("SELECT COUNT('peId') AS exist FROM timekeeping.promo_sched_emp WHERE recordNo = '" . $_POST['recordNo'] . "' AND empId = '" . $_POST['empId'] . "'") or die(mysql_error());
			$e = mysql_fetch_array($query);
			if ($e['exist'] == 0) {

				$query = mysql_query("SELECT peId, recordNo FROM timekeeping.promo_sched_emp WHERE recordNo IN ('', '0') AND empId = '" . $_POST['empId'] . "'") or die(mysql_error());
				$e = mysql_fetch_array($query);
				if (mysql_num_rows($query) == 0) {

					mysql_query("INSERT INTO timekeeping.promo_sched_emp (statCut, recordNo, empId, date_setup) VALUES ('" . $_POST['cutoff'] . "', '" . $_POST['recordNo'] . "', '" . $_POST['empId'] . "', '" . date('Y-m-d') . "')") or die(mysql_error());
				} else {

					mysql_query("UPDATE timekeeping.promo_sched_emp SET statCut = '" . $_POST['cutoff'] . "', recordNo = '" . $_POST['recordNo'] . "' WHERE recordNo = '" . $e['recordNo'] . "' AND empId='" . $_POST['empId'] . "'") or die(mysql_error());
				}
			} else {

				mysql_query("UPDATE timekeeping.promo_sched_emp SET statCut = '" . $_POST['cutoff'] . "' WHERE recordNo='" . $_POST['recordNo'] . "' AND empId='" . $_POST['empId'] . "'") or die(mysql_error());
			}
			// }

			// mysql_close($conns);
			if ($talibon == 'true') {
				include("config_talibon_timekeeping.php");

				$query = mysql_query("SELECT COUNT('peId') AS exist FROM promo_sched_emp WHERE recordNo = '" . $_POST['recordNo'] . "' AND empId = '" . $_POST['empId'] . "'") or die(mysql_error());
				$e = mysql_fetch_array($query);
				if ($e['exist'] == 0) {

					$query = mysql_query("SELECT peId, recordNo FROM promo_sched_emp WHERE recordNo IN ('', '0') AND empId = '" . $_POST['empId'] . "'") or die(mysql_error());
					$e = mysql_fetch_array($query);
					if (mysql_num_rows($query) == 0) {

						mysql_query("INSERT INTO promo_sched_emp (statCut, recordNo, empId, date_setup) VALUES ('" . $_POST['cutoff'] . "', '" . $_POST['recordNo'] . "', '" . $_POST['empId'] . "', '" . date('Y-m-d') . "')") or die(mysql_error());
					} else {

						mysql_query("UPDATE promo_sched_emp SET statCut = '" . $_POST['cutoff'] . "', recordNo = '" . $_POST['recordNo'] . "' WHERE recordNo = '" . $e['recordNo'] . "' AND empId='" . $_POST['empId'] . "'") or die(mysql_error());
					}
				} else {

					mysql_query("UPDATE promo_sched_emp SET statCut = '" . $_POST['cutoff'] . "' WHERE recordNo='" . $_POST['recordNo'] . "' AND empId='" . $_POST['empId'] . "'") or die(mysql_error());
				}

				mysql_close($con);
			}

			if ($tubigon == 'true') {

				include("config_tubigon_timekeeping.php");

				$query = mysql_query("SELECT COUNT('peId') AS exist FROM promo_sched_emp WHERE recordNo = '" . $_POST['recordNo'] . "' AND empId = '" . $_POST['empId'] . "'") or die(mysql_error());
				$e = mysql_fetch_array($query);
				if ($e['exist'] == 0) {

					$query = mysql_query("SELECT peId, recordNo FROM promo_sched_emp WHERE recordNo IN ('', '0') AND empId = '" . $_POST['empId'] . "'") or die(mysql_error());
					$e = mysql_fetch_array($query);
					if (mysql_num_rows($query) == 0) {

						mysql_query("INSERT INTO promo_sched_emp (statCut, recordNo, empId, date_setup) VALUES ('" . $_POST['cutoff'] . "', '" . $_POST['recordNo'] . "', '" . $_POST['empId'] . "', '" . date('Y-m-d') . "')") or die(mysql_error());
					} else {

						mysql_query("UPDATE promo_sched_emp SET statCut = '" . $_POST['cutoff'] . "', recordNo = '" . $_POST['recordNo'] . "' WHERE recordNo = '" . $e['recordNo'] . "' AND empId='" . $_POST['empId'] . "'") or die(mysql_error());
					}
				} else {

					mysql_query("UPDATE promo_sched_emp SET statCut = '" . $_POST['cutoff'] . "' WHERE recordNo='" . $_POST['recordNo'] . "' AND empId='" . $_POST['empId'] . "'") or die(mysql_error());
				}

				mysql_close($con);
			}

			if ($updEmp && $updPromo) {

				$nq->savelogs("Updated the current contract history of " . $name, date('Y-m-d'), date('H:i:s'), $_SESSION['emp_id'], $_SESSION['username']);
				die("success");
			}
		} else if ($_GET['request'] == "uploadPromoScannedFile") {

			$contrata = $_POST['contrata'];
			$empId = $_POST['empId'];
			$recordNo = $_POST['recordNo'];
			$table = "";

			if ($contrata == "current") :

				$table = "promo_record";
			else :

				$table = "promo_history_record";
			endif;

			$epas = $_POST['epas'];
			$clearance = $_POST['clearance'];
			$contract = $_POST['contract'];

			$epasFlag = "";
			$contractFlag = "";
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

						mysql_query("UPDATE $table SET $value = '" . mysql_real_escape_string($destination_path) . "' WHERE emp_id = '" . $empId . "' AND record_no = '" . $recordNo . "'") or die(mysql_error());
						$clearanceFlag = "true";
					}
				}
			}

			foreach ($contract as $key => $value) {

				$destination_path = "";
				if (!empty($_FILES[$value]['name'])) {

					$image		= addslashes(file_get_contents($_FILES[$value]['tmp_name']));
					$image_name	= addslashes($_FILES[$value]['name']);
					$array 	= explode(".", $image_name);

					$filename 	= $empId . "=" . date('Y-m-d') . "=" . $value . "=" . date('H-i-s-A') . "." . end($array);
					$destination_path	= "../document/contract/" . $filename;

					if (move_uploaded_file($_FILES[$value]['tmp_name'], $destination_path)) {

						mysql_query("UPDATE $table SET $value = '" . mysql_real_escape_string($destination_path) . "' WHERE emp_id = '" . $empId . "' AND record_no = '" . $recordNo . "'") or die(mysql_error());
						$contractFlag = "true";
					}
				}
			}

			foreach ($epas as $key => $value) {

				$destination_path = "";
				if (!empty($_FILES[$value]['name'])) {

					$image		= addslashes(file_get_contents($_FILES[$value]['tmp_name']));
					$image_name	= addslashes($_FILES[$value]['name']);
					$array 	= explode(".", $image_name);

					$filename 	= $empId . "=" . date('Y-m-d') . "=" . $value . "=" . date('H-i-s-A') . "." . end($array);
					$destination_path	= "../document/epas/" . $filename;

					if (move_uploaded_file($_FILES[$value]['tmp_name'], $destination_path)) {

						$updateEpas = mysql_query("UPDATE $table SET $value = '" . mysql_real_escape_string($destination_path) . "' WHERE emp_id = '" . $empId . "' AND record_no = '" . $recordNo . "'") or die(mysql_error());
						$epasFlag = "true";
					}
				}
			}

			$message = "";
			$name = mysql_real_escape_string($nq->getPromoName($_POST['empId']));

			if ($contractFlag == 'true' && $clearanceFlag == 'true' && $epasFlag == 'true') {

				$activity = "Uploaded the scanned Contract, Clearance and EPAS of " . $name . " Record No." . $recordNo;
				$nq->savelogs($activity, $date, $time, $_SESSION['emp_id'], $_SESSION['username']);
				$message = "Contract, Clearance and Scanned EPAS are Successfully Uploaded!";
			} else if ($contractFlag == 'true' && $clearanceFlag == 'true') {
				$activity = "Uploaded the scanned Contract and Clearance of " . $name . " Record No." . $recordNo;
				$nq->savelogs($activity, $date, $time, $_SESSION['emp_id'], $_SESSION['username']);
				$message = "Contract and Clearance are Successfully Uploaded!";
			} else if ($contractFlag == 'true' && $epasFlag == 'true') {
				$activity = "Uploaded the scanned Contract and EPAS of " . $name . " Record No." . $recordNo;
				$nq->savelogs($activity, $date, $time, $_SESSION['emp_id'], $_SESSION['username']);
				$message = "Contract and Scanned EPAS are Successfully Uploaded!";
			} else if ($epasFlag == 'true' && $clearanceFlag == 'true') {
				$activity = "Uploaded the scanned Clearance and EPAS of " . $name . " Record No." . $recordNo;
				$nq->savelogs($activity, $date, $time, $_SESSION['emp_id'], $_SESSION['username']);
				$message = "Clearance and Scanned EPAS are Successfully Uploaded!";
			} else if ($contractFlag == 'true') {
				$activity = "Uploaded the scanned Contract of " . $name . " Record No." . $recordNo;
				$nq->savelogs($activity, $date, $time, $_SESSION['emp_id'], $_SESSION['username']);
				$message = "Contract Successfully Uploaded!";
			} else if ($clearanceFlag == 'true') {
				$activity = "Uploaded the scanned Clearance (override) " . $name . " Record No." . $recordNo;
				$nq->savelogs($activity, $date, $time, $_SESSION['emp_id'], $_SESSION['username']);
				$message = "Clearance Successfully Uploaded!";
			} else if ($epasFlag == 'true') {
				$activity = "Uploaded the scanned EPAS of " . $name . " Record No." . $recordNo;
				$nq->savelogs($activity, $date, $time, $_SESSION['emp_id'], $_SESSION['username']);
				$message = "Scanned EPAS Successfully Uploaded!";
			}

			die("success||" . $message);
		} else if ($_GET['request'] == "uploadScannedFile") {

			$contract = $_POST['contract'];
			$empId = $_POST['empId'];
			$recordNo = $_POST['recordNo'];
			$table = "";

			if ($contract == "current") :

				$table = "employee3";
			else :

				$table = "employmentrecord_";
			endif;

			$epasFlag = "";
			$contractFlag = "";
			$clearanceFlag = "";

			$destination_path = "";
			if (!empty($_FILES['clearance']['name'])) {

				$image		= addslashes(file_get_contents($_FILES['clearance']['tmp_name']));
				$image_name	= addslashes($_FILES['clearance']['name']);
				$array 	= explode(".", $image_name);

				$filename 	= $empId . "=" . date('Y-m-d') . "=" . 'clearance' . "=" . date('H-i-s-A') . "." . end($array);
				$destination_path	= "../document/clearance/" . $filename;

				if (move_uploaded_file($_FILES['clearance']['tmp_name'], $destination_path)) {

					mysql_query("UPDATE $table SET clearance = '" . mysql_real_escape_string($destination_path) . "' WHERE emp_id = '" . $empId . "' AND record_no = '" . $recordNo . "'") or die(mysql_error());
					$clearanceFlag = "true";
				}
			}

			$destination_path = "";
			if (!empty($_FILES['contract']['name'])) {

				$image		= addslashes(file_get_contents($_FILES['contract']['tmp_name']));
				$image_name	= addslashes($_FILES['contract']['name']);
				$array 	= explode(".", $image_name);

				$filename 	= $empId . "=" . date('Y-m-d') . "=" . 'contract' . "=" . date('H-i-s-A') . "." . end($array);
				$destination_path	= "../document/contract/" . $filename;

				if (move_uploaded_file($_FILES['contract']['tmp_name'], $destination_path)) {

					mysql_query("UPDATE $table SET contract = '" . mysql_real_escape_string($destination_path) . "' WHERE emp_id = '" . $empId . "' AND record_no = '" . $recordNo . "'") or die(mysql_error());
					$contractFlag = "true";
				}
			}

			$destination_path = "";
			if (!empty($_FILES['epas']['name'])) {

				$image		= addslashes(file_get_contents($_FILES['epas']['tmp_name']));
				$image_name	= addslashes($_FILES['epas']['name']);
				$array 	= explode(".", $image_name);

				$filename 	= $empId . "=" . date('Y-m-d') . "=" . 'epas' . "=" . date('H-i-s-A') . "." . end($array);
				$destination_path	= "../document/epas/" . $filename;

				if (move_uploaded_file($_FILES['epas']['tmp_name'], $destination_path)) {

					mysql_query("UPDATE $table SET epas_code = '" . mysql_real_escape_string($destination_path) . "' WHERE emp_id = '" . $empId . "' AND record_no = '" . $recordNo . "'") or die(mysql_error());
					$epasFlag = "true";
				}
			}

			$message = "";
			$name = mysql_real_escape_string($nq->getPromoName($_POST['empId']));

			if ($contractFlag == 'true' && $clearanceFlag == 'true' && $epasFlag == 'true') {

				$activity = "Uploaded the scanned Contract, Clearance and EPAS of " . $name . " Record No." . $recordNo;
				$nq->savelogs($activity, $date, $time, $_SESSION['emp_id'], $_SESSION['username']);
				$message = "Contract, Clearance and Scanned EPAS are Successfully Uploaded!";
			} else if ($contractFlag == 'true' && $clearanceFlag == 'true') {

				$activity = "Uploaded the scanned Contract and Clearance of " . $name . " Record No." . $recordNo;
				$nq->savelogs($activity, $date, $time, $_SESSION['emp_id'], $_SESSION['username']);
				$message = "Contract and Clearance are Successfully Uploaded!";
			} else if ($contractFlag == 'true' && $epasFlag == 'true') {

				$activity = "Uploaded the scanned Contract and EPAS of " . $name . " Record No." . $recordNo;
				$nq->savelogs($activity, $date, $time, $_SESSION['emp_id'], $_SESSION['username']);
				$message = "Contract and Scanned EPAS are Successfully Uploaded!";
			} else if ($epasFlag == 'true' && $clearanceFlag == 'true') {

				$activity = "Uploaded the scanned Clearance and EPAS of " . $name . " Record No." . $recordNo;
				$nq->savelogs($activity, $date, $time, $_SESSION['emp_id'], $_SESSION['username']);
				$message = "Clearance and Scanned EPAS are Successfully Uploaded!";
			} else if ($contractFlag == 'true') {

				$activity = "Uploaded the scanned Contract of " . $name . " Record No." . $recordNo;
				$nq->savelogs($activity, $date, $time, $_SESSION['emp_id'], $_SESSION['username']);
				$message = "Contract Successfully Uploaded!";
			} else if ($clearanceFlag == 'true') {

				$activity = "Uploaded the scanned Clearance (override) " . $name . " Record No." . $recordNo;
				$nq->savelogs($activity, $date, $time, $_SESSION['emp_id'], $_SESSION['username']);
				$message = "Clearance Successfully Uploaded!";
			} else if ($epasFlag == 'true') {

				$activity = "Uploaded the scanned EPAS of " . $name . " Record No." . $recordNo;
				$nq->savelogs($activity, $date, $time, $_SESSION['emp_id'], $_SESSION['username']);
				$message = "Scanned EPAS Successfully Uploaded!";
			}

			die("success||" . $message);
		} else if ($_GET['request'] == "businessUnit") {
			echo "
				SELECT
					*
				FROM
					locate_business_unit
				WHERE
					company_code = '" . $_POST['id'] . "' and status = 'active'
				ORDER BY
					business_unit ASC
			";
			$sql = mysql_query("
				SELECT
					*
				FROM
					locate_business_unit
				WHERE
					company_code = '" . $_POST['id'] . "' and status = 'active'
				ORDER BY
					business_unit ASC
			") or die(mysql_error());
?>
	<option value="">Select</option>
	<?php
			while ($res = mysql_fetch_array($sql)) { ?>
		<option value="<?php echo $res['company_code'] . "/" . $res['bunit_code']; ?>">
			<?php echo $res['business_unit']; ?>
		</option>
	<?php }
		} else if ($_GET['request'] == "department") {

			$id = explode("/", $_POST['id']);
			$sql = mysql_query("
					SELECT
						*
					FROM
						locate_department
					WHERE
						company_code = '" . $id[0] . "'
					AND
						bunit_code = '" . $id[1] . "' and status = 'active'
					ORDER BY
						dept_name ASC
				") or die(mysql_error());
	?>
	<option value="">Select</option>
	<?php
			while ($res = mysql_fetch_array($sql)) { ?>
		<option value="<?php echo $res['company_code'] . "/" . $res['bunit_code'] . "/" . $res['dept_code']; ?>">
			<?php echo $res['dept_name']; ?>
		</option>
	<?php }
		} else if ($_GET['request'] == "section") {

			$id = explode("/", $_POST['id']);
			$sql = mysql_query("
					SELECT
						*
					FROM
						locate_section
					WHERE
						company_code = '" . $id[0] . "'
					AND
						bunit_code = '" . $id[1] . "'
					AND
						dept_code = '" . $id[2] . "'
					ORDER BY
						section_name ASC
				") or die(mysql_error());
	?>
	<option value="">Select</option>
	<?php
			while ($res = mysql_fetch_array($sql)) { ?>
		<option value="<?php echo $res['company_code'] . "/" . $res['bunit_code'] . "/" . $res['dept_code'] . "/" . $res['section_code']; ?>">
			<?php echo $res['section_name']; ?>
		</option>
	<?php }
		} else if ($_GET['request'] == "subSection") {

			$id = explode("/", $_POST['id']);
			$sql = mysql_query("
					SELECT
						*
					FROM
						locate_sub_section
					WHERE
						company_code = '" . $id[0] . "'
					AND
						bunit_code = '" . $id[1] . "'
					AND
						dept_code = '" . $id[2] . "'
					AND
						section_code = '" . $id[3] . "'
					ORDER BY
						sub_section_name ASC
				") or die(mysql_error());
	?>
	<option value="">Select</option>
	<?php
			while ($res = mysql_fetch_array($sql)) { ?>
		<option value="<?php echo $res['company_code'] . "/" . $res['bunit_code'] . "/" . $res['dept_code'] . "/" . $res['section_code'] . "/" . $res['sub_section_code']; ?>">
			<?php echo $res['sub_section_name']; ?>
		</option>
	<?php }
		} else if ($_GET['request'] == "unit") {

			$id = explode("/", $_POST['id']);
			$sql = mysql_query("
					SELECT
						*
					FROM
						locate_unit
					WHERE
						company_code = '" . $id[0] . "'
					AND
						bunit_code = '" . $id[1] . "'
					AND
						dept_code = '" . $id[2] . "'
					AND
						section_code = '" . $id[3] . "'
					AND
						sub_section_code = '" . $id[4] . "'
					ORDER BY
						unit_name ASC
				") or die(mysql_error());
	?>
	<option value="">Select</option>
	<?php
			while ($res = mysql_fetch_array($sql)) { ?>
		<option value="<?php echo $res['company_code'] . "/" . $res['bunit_code'] . "/" . $res['dept_code'] . "/" . $res['section_code'] . "/" . $res['sub_section_code'] . "/" . $res['unit_code']; ?>">
			<?php echo $res['unit_name']; ?>
		</option>
	<?php }
		} else if ($_GET['request'] == "updateContractDetails") {

			$name = mysql_real_escape_string($nq->getPromoName($_POST['empId']));
			if ($_POST['contract'] == "current") :

				$table = "employee3";
				$updatedBy = "updated_by";
				$postDesc  = "position_desc";
			else :

				$table = "employmentrecord_";
				$updatedBy = "updatedby";
				$postDesc  = "pos_desc";
			endif;

			if ($_POST['startdate'] == "0000-00-00") : $startdate = '0000-00-00';
			else : $startdate = date("Y-m-d", strtotime($_POST['startdate']));
			endif;
			if ($_POST['eocdate'] == "0000-00-00") : $eocdate = '0000-00-00';
			else : $eocdate = date("Y-m-d", strtotime($_POST['eocdate']));
			endif;

			$businessUnit 	= explode("/", $_POST['businessUnit']);
			$department 	= explode("/", $_POST['department']);
			@$section 		= explode("/", $_POST['section']);
			@$subSection 	= explode("/", $_POST['subSection']);
			@$unit 			= explode("/", $_POST['unit']);

			$updEmp = mysql_query("UPDATE `$table` 
									SET 
										`startdate`='" . $startdate . "',
										`eocdate`='" . $eocdate . "',
										`emp_type`='" . $_POST['empType'] . "',
										`current_status`='" . $_POST['current_status'] . "',
										`company_code`='" . $_POST['company'] . "',
										`bunit_code`='" . end($businessUnit) . "',
										`dept_code`='" . end($department) . "',
										`section_code`='" . end($section) . "',
										`sub_section_code`='" . end($subSection) . "',
										`unit_code`='" . end($unit) . "',
										`positionlevel`='" . $_POST['posLevel'] . "',
										`position`='" . $_POST['position'] . "',
										`$postDesc`='" . mysql_real_escape_string($_POST['posDesc']) . "',
										`lodging`='" . $_POST['lodging'] . "',
										`date_updated`='" . $date . "',
										`$updatedBy`='" . $loginId . "',
										`remarks`='" . mysql_real_escape_string($_POST['remarks']) . "'
									WHERE 
										record_no = '" . $_POST['recordNo'] . "' AND emp_id = '" . $_POST['empId'] . "'
							") or die(mysql_error());
			if ($updEmp) {

				$nq->savelogs("Updated the current contract history of " . $name, date('Y-m-d'), date('H:i:s'), $_SESSION['emp_id'], $_SESSION['username']);
				die("success");
			}
		} else if ($_GET['request'] == "addPromoContract") {

			$company = $nq->getPromoCompanyName($_POST['company']);
			$name = mysql_real_escape_string($nq->getPromoName($_POST['empId']));

			$exist = mysql_query("SELECT record_no FROM employee3 WHERE emp_id = '" . $_POST['empId'] . "'") or die(mysql_error());
			if (mysql_num_rows($exist) > 0) {

				$emp_exist = mysql_query("SELECT record_no FROM employmentrecord_ WHERE startdate = '" . date("Y-m-d", strtotime($_POST['startdate'])) . "' AND eocdate = '" . date("Y-m-d", strtotime($_POST['eocdate'])) . "' AND emp_id = '" . $_POST['empId'] . "'") or die(mysql_error());
				if ($_POST['current_status'] != "Active" && mysql_num_rows($emp_exist) == 0) {

					$addEmp = mysql_query("INSERT INTO 
										`employmentrecord_`
											(
												`emp_id`,
												`startdate`,
												`eocdate`,
												`emp_type`,
												`current_status`, 
												`duration`, 
												`position`, 
												`remarks`, 
												`date_updated`, 
												`updatedby`
											) VALUES (
												'" . $_POST['empId'] . "',
												'" . date("Y-m-d", strtotime($_POST['startdate'])) . "',
												'" . date("Y-m-d", strtotime($_POST['eocdate'])) . "',
												'" . $_POST['empType'] . "',
												'" . $_POST['current_status'] . "',
												'" . mysql_real_escape_string($_POST['duration']) . "',
												'" . mysql_real_escape_string($_POST['position']) . "',
												'" . mysql_real_escape_string($_POST['remarks']) . "',
												'" . $date . "',
												'" . $loginId . "'
											)
								") or die(mysql_error());
					$recordNo = mysql_insert_id();

					$field = "";
					$value = "";
					$str = "";

					if ($_POST['promoType'] == "STATION") {

						$str = explode("/", $_POST['store']);
						$field = "`$str[1]`";
						$value = "'T'";
					} else {

						$counter = 0;

						$strNumArr = explode("||", $_POST['store']);
						for ($i = 0; $i < sizeof($strNumArr) - 1; $i++) {

							$counter++;
							$str = explode("/", $strNumArr[$i]);

							if ($counter == 1) {
								$field = "`$str[1]`";
								$value = "'T'";
							} else {

								$field .= ",`$str[1]`";
								$value .= ",'T'";
							}
						}
					}

					$addPromo = mysql_query("INSERT INTO 
											`promo_history_record`
												(
													`emp_id`, 
													`agency_code`, 
													`promo_company`, 
													`promo_department`, 
													$field, 
													`promo_type`, 
													`record_no`, 
													`type`
												) VALUES (
													'" . $_POST['empId'] . "',
													'" . $_POST['agency'] . "',
													'" . mysql_real_escape_string($company) . "',
													'" . $_POST['department'] . "',
													$value,
													'" . $_POST['promoType'] . "',
													'" . $recordNo . "',
													'" . $_POST['contractType'] . "'
												)
										") or die(mysql_error());

					if ($addEmp && $addPromo) {

						$nq->savelogs("Added the previous contract history of " . $name, date('Y-m-d'), date('H:i:s'), $_SESSION['emp_id'], $_SESSION['username']);
						die("success");
					}
				} else {

					if ($_POST['current_status'] == "Active") {
						die("active");
					} else {

						die("duplicate");
					}
				}
			} else {

				$fullname = mysql_query("SELECT firstname, middlename, lastname, suffix FROM applicant WHERE app_id = '" . $_POST['empId'] . "'") or die(mysql_error());
				$row = mysql_fetch_array($fullname);

				if ($row['suffix'] != "") {
					$suffix = " " . $row['suffix'] . ",";
				} else {
					$suffix = "";
				}
				if ($row['middlename'] != "") {
					$mname = " " . $row['middlename'];
				} else {
					$mname = "";
				}

				$properName = ucwords(strtolower($row['lastname'] . ", " . $row['firstname'] . "" . $suffix . "" . $mname));

				$addEmp = mysql_query("INSERT INTO 
									`employee3`
										(
											`emp_id`,
											`name`,
											`startdate`,
											`eocdate`,
											`emp_type`,
											`current_status`, 
											`duration`, 
											`position`, 
											`remarks`, 
											`date_added`, 
											`added_by`
										) VALUES (
											'" . $_POST['empId'] . "',
											'" . $properName . "',
											'" . date("Y-m-d", strtotime($_POST['startdate'])) . "',
											'" . date("Y-m-d", strtotime($_POST['eocdate'])) . "',
											'" . $_POST['empType'] . "',
											'" . $_POST['current_status'] . "',
											'" . mysql_real_escape_string($_POST['duration']) . "',
											'" . mysql_real_escape_string($_POST['position']) . "',
											'" . mysql_real_escape_string($_POST['remarks']) . "',
											'" . $date . "',
											'" . $loginId . "'
										)
							") or die(mysql_error());
				$recordNo = mysql_insert_id();

				$field = "";
				$value = "";
				$str = "";

				if ($_POST['promoType'] == "STATION") {

					$str = explode("/", $_POST['store']);
					$field = "`$str[1]`";
					$value = "'T'";
				} else {

					$counter = 0;

					$strNumArr = explode("||", $_POST['store']);
					for ($i = 0; $i < sizeof($strNumArr) - 1; $i++) {

						$counter++;
						$str = explode("/", $strNumArr[$i]);

						if ($counter == 1) {
							$field = "`$str[1]`";
							$value = "'T'";
						} else {

							$field .= ",`$str[1]`";
							$value .= ",'T'";
						}
					}
				}

				$addPromo = mysql_query("INSERT INTO 
										`promo_record`
											(
												`emp_id`, 
												`promo_company`, 
												`promo_department`, 
												$field, 
												`promo_type`, 
												`record_no`, 
												`type`,
												`hr_location`
											) VALUES (
												'" . $_POST['empId'] . "',
												'" . $company . "',
												'" . $_POST['department'] . "',
												$value,
												'" . $_POST['promoType'] . "',
												'" . $recordNo . "',
												'" . $_POST['contractType'] . "',
												'" . $hrCode . "'
											)
									") or die(mysql_error());

				if ($addEmp && $addPromo) {

					$nq->savelogs("Added the current contract history of " . $name, date('Y-m-d'), date('H:i:s'), $_SESSION['emp_id'], $_SESSION['username']);
					die("success");
				}
			}
		} else if ($_GET['request'] == "durationContract") {

			$dF =  new DateTime($_POST['dF']);
			$dT =  new DateTime($_POST['dT']);

			$newDF = strtotime($_POST['dF']);
			$newDT = strtotime($_POST['dT']);

			if ($newDF > $newDT) {

				die("EOCdate must be greater than or equal to startdate!");
			} else {

				$interval = $dT->diff($dF);
				$duration = $interval->format('%a') + 1;

				if ($duration >= 32) {
					$duration = $interval->format('%m');
				} else {
					$duration = "$duration day(s)";
				}

				die("Ok||" . $duration);
			}
		} else if ($_GET['request'] == "submitEmploymentHist") {

			$no = $_POST['no'];
			$empId = $_POST['empId'];
			$company = mysql_real_escape_string($_POST['company']);
			$address = mysql_real_escape_string($_POST['address']);
			$position = mysql_real_escape_string($_POST['position']);
			$startdate = mysql_real_escape_string($_POST['startdate']);
			$eocdate = mysql_real_escape_string($_POST['eocdate']);

			$destination_path = "";
			if (!empty($_FILES['certificate']['name'])) {

				$image		= addslashes(file_get_contents($_FILES['certificate']['tmp_name']));
				$image_name	= addslashes($_FILES['certificate']['name']);
				$array 	= explode(".", $image_name);

				$filename 	= $empId . "=" . date('Y-m-d') . "=" . 'Employment-Certificate' . "=" . date('H-i-s-A') . "." . end($array);
				$destination_path	= "../document/employment_certificate/" . $filename;

				move_uploaded_file($_FILES['certificate']['tmp_name'], $destination_path);
			}

			if ($no == "") {

				$employment = mysql_query("INSERT INTO 
											`application_employment_history`
												(`app_id`, `company`, `position`, `yr_start`, `yr_ends`, `address`, `emp_certificate`) 
										VALUES 
												('$empId','$company','$position','$startdate','$eocdate','$address','" . mysql_real_escape_string($destination_path) . "')") or die(mysql_error());
				if ($employment) {
					logs($_SESSION['emp_id'], $_SESSION['username'], date("Y-m-d"), date("H:i:s"), "Adding new Employment History of " . $nq->getAppName($empId));
					die("success||Added");
				}
			} else {

				$updateCer = "";
				if (!empty($destination_path)) {

					$updateCer = ", `emp_certificate`='" . $destination_path . "'";
				}

				$seminar = mysql_query("UPDATE 
										`application_employment_history` 
											SET 
												`company`='$company',
												`position`='$position',
												`yr_start`='$startdate',
												`yr_ends`='$eocdate',
												`address`='$address'
												$updateCer 
										WHERE
												`no`='$no' AND `app_id`='$empId'
								") or die(mysql_error());

				if ($seminar) {
					logs($_SESSION['emp_id'], $_SESSION['username'], date("Y-m-d"), date("H:i:s"), "Updated Employment History of of " . $nq->getAppName($empId));
					die("success||Updated");
				}
			}
		} else if ($_GET['request'] == "employmentCertificate") {

			$no = $_POST['no'];

			$query = mysql_query("SELECT emp_certificate FROM `application_employment_history` WHERE no = '$no'") or die(mysql_error());
			$fetch = mysql_fetch_array($query);

			die($fetch['emp_certificate']);
		} else if ($_GET['request'] == "transfer") {

			$empId = $_POST['empId'];

			$sql = mysql_query("SELECT * FROM employee_transfer_details WHERE emp_id = '$empId' ORDER BY transfer_no DESC") or die(mysql_error());
	?>
	<div class="modf">Job Transfer History</div>
	<table class="table table-striped" id="data">
		<thead>
			<tr>
				<th>No</th>
				<th>Effectivity</th>
				<th>TransferFrom</th>
				<th>TransferTo</th>
				<th>OldPosition</th>
				<th>NewPosition</th>
				<th>DirectSupervisor</th>
				<th>JobTrans</th>
			</tr>
		</thead>
		<tbody>
			<?php

			while ($row = mysql_fetch_array($sql)) {

				$oldLoc = explode('-', $row['old_location']);
				$newLoc = explode('-', $row['new_location']);
				$olddept = $nq->getDeptAcroname(@$oldLoc[2], @$oldLoc[1], @$oldLoc[0]);
				$dept = $nq->getDeptAcroname(@$newLoc[2], @$newLoc[1], @$newLoc[0]);

				if (empty($olddept)) {

					$olddept = $nq->getDepartmentName(@$oldLoc[2], @$oldLoc[1], @$oldLoc[0]);
				}

				if (empty($dept)) {

					$dept = $nq->getDepartmentName(@$newLoc[2], @$newLoc[1], @$newLoc[0]);
				}

				$businessUnit = $nq->getBUAcroname($oldLoc[1], $oldLoc[0]);
				if (empty($businessUnit)) {

					$businessUnit = $nq->getBusinessUnitName($oldLoc[1], $oldLoc[0]);
				} ?>
				<tr>
					<td><?php echo $row['transfer_no']; ?></td>
					<td><?php
						if (strlen($row['effectiveon']) > 10 || strlen($row['effectiveon']) < 10) {
							echo $row['effectiveon'];
						} else {
							echo $nq->changeDateFormat('m/d/Y', @$row['effectiveon']);
						} ?></td>
					<td><?php echo $nq->getCompanyAcroName($oldLoc[0]) . "-" . $businessUnit . "-" . $olddept; ?></td>
					<td><?php echo $nq->getCompanyAcroName($newLoc[0]) . "-" . $businessUnit . "-" . $dept; ?></td>
					<td><?php echo @$row['old_position']; ?></td>
					<td><?php echo @$row['position']; ?></td>
					<td><?php echo @$row['supervision']; ?></td>
					<td><button class="btn btn-primary btn-sm" onclick="viewJobTrans(<?php echo $row['transfer_no']; ?>)">view</button></td>
				</tr> <?php
					}
						?>
		</tbody>
	</table>
<?php
		} else if ($_GET['request'] == "viewJobTrans") {

			$jobTransfer = $_POST['jobTransfer'];

			$filename 	= mysql_query("SELECT * FROM employee_transfer_details WHERE transfer_no = '$jobTransfer'") or die(mysql_error());
			$r 			= mysql_fetch_array($filename);
			$file 		= $r['file'];

?>

	<body>
		<center>
			<?php
			if (@$_GET['header'] == "yes") {
				echo "<h3>JOB TRANSFER REPORT</h3>";
				echo "<embed src='$file' width='80%' height='500'></embed>";
			} else {
				echo "<embed src='$file' width='85%' height='500'></embed>";
			}
			?>
		</center>
	</body> <?php
		} else if ($_GET['request'] == "blacklist") {

			$empId = $_POST['empId'];

			$sql = mysql_query("SELECT blacklist_no, date_blacklisted, date_added, reportedby, reason, status FROM `blacklist` WHERE app_id = '$empId'") or die(mysql_error());
			$blNum = mysql_num_rows($sql);

			?>
	<div class="modf">Blacklist History
		<button class="btn btn-primary btn-sm" id="add-blacklist" <?php if ($blNum > 0) : echo "disabled=''";
																	endif; ?> onclick="add_blacklist('')">add</button>
	</div>
	<table class="table table-bordered">
		<thead>
			<tr>
				<th>DateBlacklisted</th>
				<th>ReportedBy</th>
				<th>Reason</th>
				<th>DateAdded</th>
				<th>Status</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody>
			<?php

			while ($row = mysql_fetch_array($sql)) {

				if ($row['date_blacklisted'] == '0000-00-00' || $row['date_blacklisted'] == '') {

					$datebl = '';
				} else {

					$datebl = date("m/d/Y", strtotime($row['date_blacklisted']));
				}

				if ($row['date_added'] == '0000-00-00' || $row['date_added'] == '') {

					$dateadded = '';
				} else {

					$dateadded = date("m/d/Y", strtotime($row['date_added']));
				}

				echo "
								<tr>
									<td>" . $datebl . "</td>
									<td>" . $row['reportedby'] . "</td>
									<td>" . htmlspecialchars($row['reason']) . "</td>
									<td>" . $dateadded . "</td>
									<td><label class='btn btn-xs btn-danger btn-block'>" . $row['status'] . "</label></td>
									<td><button class='btn btn-sm btn-primary' onclick=add_blacklist('$row[blacklist_no]')>edit</button></td>
								</tr>
							";
			}
			?>
		</tbody>
	</table>
<?php
		} else if ($_GET['request'] == "addBlacklist") {

			$no = $_POST['no'];
			$empId = $_POST['empId'];
			$name = $nq->getEmpName($empId);

			$sql = mysql_query("SELECT * FROM `blacklist` WHERE blacklist_no = '$no'") or die(mysql_error());
			$row = mysql_fetch_array($sql);

			if ($row['date_blacklisted'] == '0000-00-00' || $row['date_blacklisted'] == '') {

				$datebl = '';
			} else {

				$datebl = date("m/d/Y", strtotime($row['date_blacklisted']));
			}

			if ($row['date_added'] == '0000-00-00' || $row['date_added'] == '') {

				$dateadded = '';
			} else {

				$dateadded = date("m/d/Y", strtotime($row['date_added']));
			}

			if ($row['bday'] == "0000-00-00" || $row['bday'] == "" || $row['bday'] == "1970-01-01") {

				$bday = "";
			} else {

				$bday = date("m/d/Y", strtotime($row['bday']));
			}

?>
	<style type="text/css">
		.search-results {

			box-shadow: 5px 5px 5px #ccc;
			margin-top: 1px;
			margin-left: 0px;
			background-color: #F1F1F1;
			width: 85%;
			border-radius: 3px 3px 3px 3px;
			font-size: 18x;
			padding: 8px 10px;
			display: block;
			position: absolute;
			z-index: 9999;
			max-height: 300px;
			overflow-y: scroll;
			overflow: auto;
		}

		.datepicker {
			z-index: 9999 !important
		}
	</style>
	<input type="hidden" name="empId" value="<?php echo $empId; ?>">
	<input type="hidden" name="no" value="<?php echo $no; ?>">
	<div class="form-group">
		<label>Employee</label>
		<div class="input-group">
			<input class="form-control" value="<?php echo $empId . ' * ' . $name; ?>" name="employee" disabled="" type="text">
			<span class="input-group-addon"><i class="fa fa-user"></i></span>
		</div>
	</div>
	<div class="form-group"> <i class="text-red">*</i>
		<label>Reason</label>
		<textarea name="reason" class="form-control" rows="4" onkeyup="inputField(this.name)"><?php echo $row['reason']; ?></textarea>
	</div>
	<div class="row">
		<div class="col-md-6">
			<div class="form-group"> <i class="text-red">*</i>
				<label>Date Blacklisted</label>
				<div class="input-group date">
					<div class="input-group-addon">
						<i class="fa fa-calendar"></i>
					</div>
					<input type="text" name="dateBlacklisted" class="form-control pull-right datepicker" onchange="inputField(this.name)" value="<?php echo $datebl; ?>" placeholder="mm/dd/yyy">
				</div>
			</div>
			<div class="form-group">
				<label>Birthday</label>
				<div class="input-group date">
					<div class="input-group-addon">
						<i class="fa fa-calendar"></i>
					</div>
					<input type="text" name="birthday" value="<?php echo $bday; ?>" class="form-control pull-right datepicker" placeholder="mm/dd/yyy">
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group"> <i class="text-red">*</i>
				<label>Reported By</label>
				<div class="input-group">
					<input class="form-control" type="text" name="reportedBy" value="<?php echo $row['reportedby']; ?>" onkeyup="nameSearch(this.value)" autocomplete="off">
					<span class="input-group-addon"><i class="fa fa-child"></i></span>
				</div>
				<div class="search-results" style="display: none;"></div>
			</div>
			<div class="form-group">
				<label>Address</label>
				<input type="text" class="form-control" name="address" value="<?php echo $row['address']; ?>">
			</div>
		</div>
	</div>
	<script type="text/javascript">
		$('.datepicker').datepicker({
			inline: true,
			changeYear: true,
			changeMonth: true
		});
	</script>
<?php
		} else if ($_GET['request'] == "submitBlacklist") {

			if ($_POST['birthday'] == "") {

				$birthday = "";
			} else {

				$birthday = date("Y-m-d", strtotime($_POST['birthday']));
			}

			if ($_POST['no'] == "") {
				$name = mysql_real_escape_string($nq->getEmpName($_POST['empId']));

				$query = mysql_query("INSERT INTO `blacklist`
											(`app_id`, `name`, `date_blacklisted`, `date_added`, `reportedby`, `reason`, `status`, `staff`, `bday`, `address`) 
										VALUES 
											(
												'" . $_POST['empId'] . "',
												'" . $name . "',
												'" . date("Y-m-d", strtotime($_POST['dateBlacklisted'])) . "',
												'" . $date . "',
												'" . $_POST['reportedBy'] . "',
												'" . mysql_real_escape_string($_POST['reason']) . "',
												'blacklisted',
												'" . $_SESSION['emp_id'] . "',
												'" . $birthday . "',
												'" . mysql_real_escape_string($_POST['address']) . "'
											)
								") or die(mysql_error());

				$sql = mysql_query("UPDATE `employee3` SET current_status = 'blacklisted' WHERE emp_id = '" . $_POST['empId'] . "'") or die(mysql_error());
				$user = mysql_query("UPDATE users SET user_status = 'inactive' WHERE emp_id = '" . $_POST['empId'] . "'") or die(mysql_error());

				die("success||Added!");
			} else {

				$query = mysql_query("UPDATE 
										`blacklist` 
									SET 
										`date_blacklisted`='" . date("Y-m-d", strtotime($_POST['dateBlacklisted'])) . "',
										`reportedby`='" . $_POST['reportedBy'] . "',
										`reason`='" . mysql_real_escape_string($_POST['reason']) . "',
										`staff`='" . $_SESSION['emp_id'] . "',
										`bday`='" . $birthday . "',
										`address`='" . mysql_real_escape_string($_POST['address']) . "'
									WHERE 
										`blacklist_no`='" . $_POST['no'] . "' AND `app_id` = '" . $_POST['empId'] . "'
								") or die(mysql_error());

				$sql = mysql_query("UPDATE `employee3` SET current_status = 'blacklisted' WHERE emp_id = '" . $_POST['empId'] . "'") or die(mysql_error());

				die("success||Updated!");
			}
		} else if ($_GET['request'] == "benefits") {

			$empId = $_POST['empId'];

			$sql = mysql_query("SELECT sss_no, pagibig_tracking, pagibig, philhealth, tin_no FROM `applicant_otherdetails` WHERE app_id = '$empId'") or die(mysql_error());
			$row = mysql_fetch_array($sql);

?>
	<div class="modf">BENEFITS
		<input name="edit" id="edit-benefits" value="edit" class="btn btn-primary btn-sm" onclick="edit_benefits()" type="button">
		<input class="btn btn-primary btn-sm" id="update-benefits" value="update" style="display:none" onclick="update(this.id)" type="button">
	</div>
	<table class="table table-bordered" width="100%">
		<tbody>
			<tr>
				<td align="right" width="20%">Philhealth No.</td>
				<td><input name="ph" value="<?php echo $row['philhealth']; ?>" class="form-control inputForm" placeholder="00-000000000-0" type="text" data-inputmask='"mask": "99-999999999-9"' data-mask></td>
			</tr>
			<tr>
				<td align="right">SSS No.</td>
				<td><input name="sss" value="<?php echo $row['sss_no']; ?>" class="form-control inputForm" placeholder="00-0000000-0" type="text" data-inputmask='"mask": "99-9999999-9"' data-mask></td>
			</tr>
			<tr>
				<td align="right">Pag-ibig No.</td>
				<td><input name="pagibig" value="<?php echo $row['pagibig']; ?>" class="form-control inputForm" placeholder="0000-0000-0000" type="text" data-inputmask='"mask": "9999-9999-9999"' data-mask></td>
			</tr>
			<tr>
				<td align="right">Pag-ibig RTN</td>
				<td><input name="pagibigrtn" value="<?php echo $row['pagibig_tracking']; ?>" class="form-control inputForm" placeholder="0000-0000-0000" type="text" data-inputmask='"mask": "9999-9999-9999"' data-mask></td>
			</tr>
			<tr>
				<td align="right">TIN no.</td>
				<td><input name="tinno" value="<?php echo $row['tin_no']; ?>" class="form-control inputForm" placeholder="000-000-000-000" type="text" data-inputmask='"mask": "999-999-999-999"' data-mask></td>
			</tr>
		</tbody>
	</table>
	<script type="text/javascript">
		$(".inputForm").prop("disabled", true);
		$("[data-mask]").inputmask();
	</script>
<?php
		} else if ($_GET['request'] == "update-benefits") {

			$pagibigrtn = $_POST['pagibigrtn'];
			$recordedby = $_SESSION['username'] . "/" . $_SESSION['emp_id'];
			$check = mysql_query("SELECT * FROM applicant_otherdetails where app_id = '" . $_POST['empId'] . "'") or die(mysql_error());
			if (mysql_num_rows($check) > 0) {
				$act = "Updating the Benefits Info of";
				$updatebenefits = mysql_query(
					"UPDATE 
									applicant_otherdetails 
								SET 
									philhealth='" . mysql_real_escape_string($_POST['ph']) . "', 
									sss_no='" . mysql_real_escape_string($_POST['sss']) . "', 
									pagibig='" . mysql_real_escape_string($_POST['pagibig']) . "',
									pagibig_tracking = '$pagibigrtn',
									tin_no = '" . $_POST['tinno'] . "',
									recordedby = '$recordedby'								
								WHERE 
									app_id = '" . mysql_real_escape_string($_POST['empId']) . "'
							"
				) or die(mysql_error());
			} else {
				$act = "Adding the Benefits Info of";
				$updatebenefits = mysql_query(
					"INSERT INTO
									applicant_otherdetails
										(no,app_id,sss_no,recordedby,pagibig_tracking,pagibig,philhealth,tin_no)
								VALUES	
										(no,'" . $_POST['empId'] . "','" . mysql_real_escape_string($_POST['sss']) . "','$recordedby','$pagibigrtn','" . mysql_real_escape_string($_POST['pagibig']) . "','" . mysql_real_escape_string($_POST['ph']) . "','" . mysql_real_escape_string($_POST['tinno']) . "')
							"
				) or die(mysql_error());
			}

			if ($updatebenefits) {
				logs($_SESSION['emp_id'], $_SESSION['username'], date("Y-m-d"), date("H:i:s"), $act . " " . $nq->getAppName($_POST['empId']));
				die("success");
			}
		} else if ($_GET['request'] == "pss") {

			$empId = $_POST['empId'];

			$sql = mysql_query("SELECT leveling_subordinates.record_no, ratee, name, current_status, emp_type, position FROM `leveling_subordinates`, `employee3` WHERE leveling_subordinates.ratee = employee3.emp_id AND subordinates_rater = '$empId' GROUP BY ratee") or die(mysql_error());

?>
	<div class="modf">Peer-Subordinate-Supervisor
		<button class="btn btn-primary btn-sm" id="add-supervisor" onclick="add_supervisor()">add</button>
	</div>
	<h4>Supervisor</h4>
	<table class="table table-striped">
		<thead>
			<tr>
				<th>Name</th>
				<th>Position</th>
				<th>Status</th>
				<th>EmployeeType</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody>
			<?php

			while ($row = mysql_fetch_array($sql)) {

				if ($row['current_status'] == "Active") {

					$class = "btn btn-success btn-xs btn-block";
				} else if ($row['current_status'] == "End of Contract" || $row['current_status'] == "Resigned") {

					$class = "btn btn-warning btn-xs btn-block";
				} else {

					$class = "btn btn-danger btn-xs btn-block";
				}

				echo "
								<tr id='remove_" . $row['record_no'] . "'>
									<td><a href='?p=profile&&module=Employee&&com=" . $row['ratee'] . "'>" . ucwords(strtolower($row['name'])) . "</a></td>
									<td>" . $row['position'] . "</td>
									<td><label class='$class'>" . $row['current_status'] . "</label></td>
									<td>" . $row['emp_type'] . "</td>
									<td><button class='btn btn-warning btn-sm' onclick=removeSubordinates('$row[record_no]')>remove</button></td>
								</tr>
							";
			}
			?>
		</tbody>
	</table>
<?php
		} else if ($_GET['request'] == "removeSubordinates") {

			$recordNo = $_POST['recordNo'];

			$remove = mysql_query("DELETE FROM `leveling_subordinates` WHERE `record_no` = '$recordNo'") or die(mysql_error());

			die("success");
		} else if ($_GET['request'] == "addSupervisor") {

			$empId = $_POST['empId'];

?>
	<input type="hidden" name="empId" value="<?php echo $empId; ?>">
	<div class="row">
		<div class="col-md-4">
			<div class="form-group">
				<label>Company</label>
				<select class="form-control" name="company" onchange="loadBusinessUnit(this.value)">
					<option value="">Select</option>
					<?php

					$sql = mysql_query("SELECT * FROM locate_company ORDER BY company ASC") or die(mysql_error());
					while ($res = mysql_fetch_array($sql)) { ?>

						<option value="<?php echo $res['company_code']; ?>"><?php echo $res['company']; ?></option> <?php
																												}
																													?>
				</select>
			</div>
			<div class="form-group">
				<label>Business Unit</label>
				<select class="form-control" name="businessUnit" onchange="loadDepartment(this.value)">
					<option value="">Select</option>
				</select>
			</div>
			<div class="form-group">
				<label>Department</label>
				<select class="form-control" name="department" onchange="loadSection(this.value)">
					<option value="">Select</option>
				</select>
			</div>
			<div class="form-group">
				<label>Section</label>
				<select class="form-control" name="section" onchange="loadSubSection(this.value)">
					<option value="">Select</option>
				</select>
			</div>
			<div class="form-group">
				<label>Sub-section</label>
				<select class="form-control" name="subSection" onchange="loadUnit(this.value)">
					<option value="">Select</option>
				</select>
			</div>
			<div class="form-group">
				<label>Unit</label>
				<select class="form-control" name="unit">
					<option value="">Select</option>
				</select>
			</div>
		</div>
		<div class="col-md-8">
			<div class="supervisor table-height">
				<div class="loading-gif"></div>
			</div>
		</div>
	</div>
<?php
		} else if ($_GET['request'] == "selectSupervisor") {

			$id = explode("/", $_POST['id']);
			$loc = $_POST['loc'];

			$where = "";
			if ($loc == "cc") {
				$where = "AND company_code = '" . $_POST['id'] . "'";
			} else if ($loc == "bc") {
				$where = "AND company_code = '$id[0]' and bunit_code = '$id[1]'";
			} else if ($loc == "dc") {
				$where = "AND company_code = '$id[0]' and bunit_code = '$id[1]' and dept_code = '$id[2]'";
			} else if ($loc == "sc") {
				$where = "AND company_code = '$id[0]' and bunit_code = '$id[1]' and dept_code = '$id[2]' and section_code = '$id[3]'";
			} else if ($loc == "ssc") {
				$where = "AND company_code = '$id[0]' and bunit_code = '$id[1]' and dept_code = '$id[2]' and section_code = '$id[3]' and sub_section_code = '$id[4]'";
			} else if ($loc == "uc") {
				$where = "AND company_code = '$id[0]' and bunit_code = '$id[1]' and dept_code = '$id[2]' and section_code = '$id[3]' and sub_section_code = '$id[4]' and unit_code = '$id[5]'";
			}

			$sql = mysql_query("SELECT emp_id, name, position, current_status FROM `employee3`, `leveling_subordinates` WHERE employee3.emp_id = leveling_subordinates.ratee AND current_status = 'Active' $where GROUP BY ratee ORDER BY name ASC") or die(mysql_error());

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
		<tbody>
			<?php

			while ($row = mysql_fetch_array($sql)) {

				$supId = $row['emp_id'];
				if ($row['current_status'] == "Active") {

					$class = "btn btn-success btn-xs";
				} else {

					$class = "btn btn-warning btn-xs";
				}

				echo "
								<tr>
									<span style='display:none;'><input type='checkbox' name='chkempId[]' class='chkId_$supId' value='$supId'></span>
									<td><input type='checkbox' class='chkIdC_$supId' onclick='chkIdC(\"$supId\")'></td>
									<td><a href='?p=profile&&module=Promo&&com='$supId'>$supId</td>
									<td>" . ucwords(strtolower($row['name'])) . "</td>
									<td>" . ucfirst(strtolower($row['position'])) . "</td>
									<td><span class='$class btn-block'>" . $row['current_status'] . "</span></td>
								</tr>
							";
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
		} else if ($_GET['request'] == "saveSupervisor") {

			$empId = $_POST['empId'];
			$supIds = explode("*", $_POST['newCHK']);

			for ($i = 0; $i < sizeof($supIds) - 1; $i++) {

				mysql_query("INSERT INTO `leveling_subordinates`(`ratee`, `subordinates_rater`) VALUES ('" . $supIds[$i] . "','" . $empId . "')") or die();
			}

			die("success");
		} else if ($_GET['request'] == "remarks") {

			$empId = $_POST['empId'];

			$sql = mysql_query("SELECT remarks FROM remarks where emp_id = '$empId'") or die(mysql_error());
			$row = mysql_fetch_array($sql);

?>
	<div class="modf">Remarks
		<input name="edit" id="edit-remarks" value="edit" class="btn btn-primary btn-sm" onclick="edit_remarks()" type="button">
		<input class="btn btn-primary btn-sm" id="update-remarks" value="update" style="display:none" onclick="update(this.id)" type="button">
	</div>
	<?php

			$checkifres = mysql_query("SELECT * FROM `termination` WHERE emp_id = '$empId' order by date desc");
			if (mysql_num_rows($checkifres) > 0) {

				echo "<div class='alert alert-info' role='alert'>";
				while ($rch = mysql_fetch_array($checkifres)) {
					echo "<i>" . $rch['remarks'] . " last " . $nq->changeDateFormat('M d, Y', $rch['date']) . " added by " . $nq->getApplicantName2($rch['added_by']) . " updated last " . $nq->changeDateFormat('M d, Y', $rch['date_updated']) . ".</i><br>";
				}
				echo "</div>";
			}
	?>
	<textarea name="remarks" rows="15" cols="150" class="form-control inputForm" id="remarks"><?php echo $row['remarks']; ?></textarea>
	<br>
	<b>Clearance Processing History</b>
	<?php
			$empClearance = mysql_query("SELECT scp.reason, scpd.store, scpd.date_secure, scpd.date_effectivity, scpd.resignation_letter, scpd.clearance_status, scpd.added_by
			FROM secure_clearance_promo_details AS scpd
			INNER JOIN secure_clearance_promo AS scp
			ON scp.scpr_id = scpd.scpr_id
			WHERE scpd.emp_id = '$empId'
			GROUP BY scpd.scdetails_id
			ORDER BY scpd.date_secure DESC");
	?>
	<table class="table table-striped table-bordered" style='font-size:12px'>
		<thead>
			<tr>
				<th> REASON </th>
				<th> STORE </th>
				<th> DATE SECURE </th>
				<th> DATE EFFECTIVE </th>
				<th> RESIGNATION LETTER </th>
				<th> STATUS </th>
				<th> ADDED BY </th>
			</tr>
		</thead>
		<tbody>
			<?php
			while ($getClearance = mysql_fetch_array($empClearance)) {

				echo "<tr>
				<td>$getClearance[reason]</td>
				<td>$getClearance[store]</td>					
				<td>" . $nq->changeDateFormat('m/d/y', $getClearance['date_secure']) . "</td>
				<td>" . $nq->changeDateFormat('m/d/y', $getClearance['date_effectivity']) . "</td>															
				<td><a href='#' data-toggle='modal' data-target='#viewcertificate' onclick=showRL('" . $getClearance['resignation_letter'] . "')> <img src='../images/icons/Search-icon.png'>
				</a></td>				

				<td>$getClearance[clearance_status]</td>
				<td>" . $nq->getEmpName($getClearance['added_by']) . "</td>

			</tr>";
			}

			?>
		</tbody>
	</table>
	<script type="text/javascript">
		$(".inputForm").prop("disabled", true);
	</script>
<?php
		} else if ($_GET['request'] == "update-remarks") {

			$chk = mysql_query("SELECT * FROM remarks where emp_id = '" . $_POST['empId'] . "'") or die(mysql_error());
			if (mysql_num_rows($chk) > 0) {

				$remarks = mysql_query("UPDATE `remarks` SET remarks = '" . mysql_real_escape_string($_POST['remarks']) . "' WHERE emp_id= '" . $_POST['empId'] . "'") or die(mysql_error);
			} else {

				$remarks = mysql_query("INSERT INTO `remarks`(`emp_id`, `remarks`) VALUES ('" . $_POST['empId'] . "','" . mysql_real_escape_string($_POST['remarks']) . "')") or die(mysql_error);
			}
			if ($remarks) {

				logs($_SESSION['emp_id'], $_SESSION['username'], date("Y-m-d"), date("H:i:s"), "Saving remarks of " . $nq->getAppName($_POST['empId']));
				die("success");
			}
		} else if ($_GET['request'] == "useraccount") {

			$empId = $_POST['empId'];

			$sql = mysql_query("SELECT user_no, username, usertype, user_status, login, date_created FROM users WHERE emp_id = '$empId'") or die(mysql_error());

?>
	<div class="modf">User Account
		<input type="button" class="btn btn-sm btn-primary" <?php if (mysql_num_rows($sql) > 0) : echo "disabled=''";
															endif; ?> value="add" onclick="addUserAccount()">
	</div>
	<table class="table table-striped table-hover">
		<thead>
			<tr>
				<th>UserNo.</th>
				<th>Username</th>
				<th>Usertype</th>
				<th>Status</th>
				<th>LogIn</th>
				<th>Date Created</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody>
			<?php

			while ($row = mysql_fetch_array($sql)) {

				if ($row['user_status'] == "active") {

					$userClass = "btn btn-success btn-xs btn-block btn-flat";
				} else {

					$userClass = "btn btn-danger btn-xs btn-block btn-flat";
				}

				if ($row['user_status'] == "active") {

					$iconImage = "<a href='javascript:void(0)' title='click to deactivate account' onclick=userAction('$row[user_no]','deactivateAccount')><img src='../images/icons/icon-close-circled-20.png' height='17' width='17'></a>";
				} else {

					$iconImage = "<a href='javascript:void(0)' title='click to activate account' onclick=userAction('$row[user_no]','activateAccount')><img src='../images/icons/icn_active.gif' height='17' width='17'></a>";
				}

				if ($loginId == "06359-2013") {
					$trashImage = "<a href='javascript:void(0)' title='click to delete account' onclick=userAction('$row[user_no]','deleteAccount')><img src='../images/icons/delete-icon.png' height='17' width='17'></a>";
				}

				echo "
								<tr>
									<td>" . $row['user_no'] . "</td>
									<td>" . $row['username'] . "</td>
									<td>" . $row['usertype'] . "</td>
									<td><label class='$userClass'>" . $row['user_status'] . "</label></td>
									<td>" . $row['login'] . "</td>
									<td>" . date("M. d, Y", strtotime($row['date_created'])) . "</td>
									<td>
										<a href='javascript:void(0)' title='click to reset password' onclick=userAction('$row[user_no]','resetPass')><img src='../images/icons/refresh.png' height='17' width='17'></a>&nbsp;$iconImage&nbsp;$trashImage
									</td>
								</tr>
							";
			}
			?>
		</tbody>
	</table>
<?php
		} else if ($_GET['request'] == "addUserAccount") {

			$empId = $_POST['empId'];
			$name = $nq->getEmpName($empId);

?>
	<div class="form-group">
		<label>Employee</label>
		<div class="input-group">
			<input class="form-control" type="text" name="employee" onkeyup="nameSearch(this.value)" autocomplete="off" style="text-transform: uppercase;" disabled="" value="<?php echo $empId . ' * ' . $name; ?>">
			<span class="input-group-addon"><i class="fa fa-user"></i></span>
		</div>
		<div class="search-results" style="display: none;"></div>
	</div>
	<div class="form-group">
		<label>User Type</label>
		<input type="hidden" name="usertype" class="form-control" value="employee">
		<input type="text" name="" class="form-control" value="Employee" disabled="">
	</div>
	<div class="form-group">
		<label>Username</label>
		<input type="text" name="username" class="form-control" value="<?php echo $empId; ?>" disabled="" onkeyup="inputField(this.name)">
	</div>
	<hr>
	<button class="btn btn-primary btn-sm" onclick="defaultPassword()">set default password</button> <i>Default password: Hrms2014</i>
	<div class="form-group">
		<label>Password</label> <i class="text-red">*</i>
		<input type="password" name="password" class="form-control" onkeyup="inputField(this.name)">
	</div>
<?php
		} else if ($_GET['request'] == "201doc") {

			$empId = $_POST['empId'];
			$doc = mysql_query("SELECT no, 201_name, tableName, empField, table_condition FROM `201document` WHERE promo = 'yes' ORDER BY 201_name ASC") or die(mysql_error());

?>
	<div class="modf">201 Documents
		<input type="button" class="btn btn-sm btn-primary" value="upload" onclick="upload201Files()">
	</div>
	<div class="row">
		<?php

			$total = 0;
			while ($row = mysql_fetch_array($doc)) {

				$no 		= $row['no'];
				$title 		= mysql_real_escape_string($row['201_name']);
				$tableName 	= mysql_real_escape_string($row['tableName']);
				$empField 	= mysql_real_escape_string($row['empField']);
				$table_condition = $row['table_condition'];

				$sql = mysql_query("SELECT $empField FROM `$tableName` WHERE $empField = '$empId' $table_condition") or die();
				// update viewing of Resignation Letter on 201files if ONLY termination table returs 0
				if ($tableName == 'termination' && mysql_num_rows($sql) == 0) {


					$sql = mysql_query("SELECT $empField FROM `secure_clearance_promo_details` WHERE $empField = '$empId' $table_condition AND resignation_letter like '%M.___'") or die();
					$total = mysql_num_rows($sql);
				}
				// end of update
				$total = mysql_num_rows($sql);

		?>
			<div class="col-md-3">
				<div class="panel panel-default">
					<div class="panel-heading">
						<center><span class="sm"><?php echo $title; ?></span></center>
					</div>
					<div class="panel-body">
						<span class="label label-danger pull-right"><?php echo $total; ?></span>
						<center>
							<a href="javascript:void(0)" onclick="view201Files('<?php echo $no; ?>','<?php echo $title; ?>')" title="click to view"><img src="images/docs.png" class="img"></a>
						</center>
					</div>
				</div>
			</div> <?php
				}
					?>
	</div>
<?php
		} else if ($_GET['request'] == "view201File") {

			$empId  = $_POST['empId'];
			$no 	= $_POST['no'];

			$start 	= 0;
			$limit 	= 1;
			if (!empty($_POST['page'])) {

				$id = $_POST['page'];
				$start = ($id - 1) * $limit;
			} else {

				$id = 1;
			}

			$doc = mysql_query("SELECT tableName, empField, table_condition FROM `201document` WHERE no = '$no'") or die(mysql_error());
			$d 	 = mysql_fetch_array($doc);

			$tableName 	= mysql_real_escape_string($d['tableName']);
			$empField 	= mysql_real_escape_string($d['empField']);
			$table_condition = $d['table_condition'];

			$sql = mysql_query("SELECT * FROM `$tableName` 
								WHERE 
									$empField = '" . $empId . "' $table_condition
				 				LIMIT 
				 					$start, $limit
				 			") or die(mysql_error());

			$rows = mysql_num_rows(mysql_query(
				"SELECT * FROM `$tableName`
								WHERE
									$empField = '" . $empId . "' $table_condition"
			));
			// update 201file viewing of Resignation Letter
			if ($no == 27 && $rows == 0) {
				//echo 'test';

				$sql = mysql_query("SELECT * FROM `secure_clearance_promo_details` 
					WHERE $empField = '" . $empId . "' $table_condition AND resignation_letter like '%M.___'
				 	LIMIT $start, $limit") or die(mysql_error());

				$rows = mysql_num_rows(mysql_query("SELECT * FROM `secure_clearance_promo_details` 
					WHERE $empField = '" . $empId . "' $table_condition AND resignation_letter like '%M.___'"));
				$total = ceil($rows / $limit);
				$flag = 1;
			} else {
				$total = ceil($rows / $limit);
				$flag = 0;
			} //end of update
			// $total = ceil($rows / $limit);
?>

	<div class="col-md-2" style="position:absolute;top:2px; right:1px;">
		<div class="form-horizontal">
			<div class="form-group">
				<label class="col-md-3">page</label>
				<div class="col-md-9">
					<select name="page" class="form-control" onchange="pagi('<?php echo $no; ?>',this.value)">
						<?php

						for ($i = 1; $i <= $total; $i++) { ?>

							<option value="<?php echo $i; ?>" <?php if ($id == $i) : echo "selected=''";
																endif; ?>><?php echo $i; ?></option>
						<?php
						}
						?>
					</select>
				</div>
			</div>
		</div>
	</div><?php

			while ($row = mysql_fetch_array($sql)) {

				if ($no == 27) {
					// update date if flag=1
					if ($flag == 1) {
						$date_time = $row['date_secure'];
					} else {
						$date_time = $row['date_updated'];
					} // end of update

					//$date_time = $row['date_updated'];
					$receiving_staff = $row['added_by'];
					$filename = $row['resignation_letter'];
				} else {

					$date_time = $row['date_time'];
					$receiving_staff = $row['receiving_staff'];
					$filename = $row['filename'];
				} ?>

		<div class="row">
			<div class="col-md-10">
				<span><i>Date Uploaded :</i><strong><?php echo date('F d, Y', strtotime($date_time)); ?></strong></span>
				<span><i>Uploaded By : </i><strong><?php echo getEmpName($receiving_staff); ?></strong></span>
			</div>
		</div><br>
		<div class="row">
			<div class="col-md-12">
				<center><img src="<?php echo $filename; ?>" width="100%"></center>
			</div>
		</div> <?php
			}
		} else if ($_GET['request'] == "upload201Files") {

			$empId = $_POST['empId'];

				?>
	<i class="text-red">Allowed File : jpg, jpeg, png only</i><br>
	<input type="hidden" name="empId" value="<?php echo $empId; ?>">
	<div class="form-group">
		<label>201 File</label>
		<select name="sel201File" class="form-control" onchange="inputField(this.name)">
			<option value=""> --Select-- </option>
			<?php

			$qu = mysql_query("SELECT no, 201_name FROM `201document` WHERE promo = 'yes' ORDER BY 201_name ASC") or die(mysql_error());
			while ($rq = mysql_fetch_array($qu)) {

				if ($rq['no'] != 27) {

					echo "<option value='" . $rq['no'] . "'>" . $rq['201_name'] . "</option>";
				}
			}
			?>
		</select>
	</div>
	<div class="form-group">
		<label>Browse File</label>
		<input type="file" name="file_upload[]" multiple class="form-control" onchange="validateFile()">
	</div>
<?php
		} else if ($_GET['request'] == "upload201File") {

			$empId 	= $_POST['empId'];
			$no 	= $_POST['sel201File'];

			$sql = mysql_query("SELECT 201_name, tableName, requirementName, path, empField FROM `201document` WHERE no = '$no'") or die(mysql_error());
			$row = mysql_fetch_array($sql);

			$reqName 	= $row['requirementName'];
			$tableName 	= $row['tableName'];
			$empField 	= $row['empField'];
			$path 		= $row['path'];
			$req 		= explode("/", $row['path']);
			$filename 	= end($req);

			$destination_path = "";
			if (!empty($_FILES['file_upload']['name'])) {

				$file = $_FILES['file_upload']['name'];
				for ($i = 0; $i < count($file); $i++) {

					$query = mysql_query("SELECT 
											$empField 
										FROM 
											`$tableName`
										WHERE 
											$empField = '" . $empId . "' AND requirement_name = '" . $reqName . "'
								   	") or die(mysql_error());
					$num = mysql_num_rows($query) + 1;

					$image = mysql_real_escape_string($file[$i]);
					$array = explode(".", $image);

					$destination_path 	= $path . "/" . $empId . "=" . $num . "=" . date('Y-m-d') . "=" . $reqName . "=" . date('H-i-s-A') . "." . end($array);

					if (move_uploaded_file($_FILES['file_upload']['tmp_name'][$i], $destination_path)) {

						mysql_query("INSERT INTO $tableName
										($empField, requirement_name, filename, date_time, requirement_status, receiving_staff) 
									VALUES
										(
											'" . $empId . "',
											'" . $reqName . "',
											'" . $destination_path . "',
											'" . $date . "',
											'passed',
											'" . $loginId . "'
										)
								") or die(mysql_error());

						//inserting to logs			
						$nq->savelogs("Uploaded the 201 file [ " . $reqName . " ] of " . $nq->getAppName($empId), $date, $time, $loginId, $_SESSION['username']);
					}
				}
			}

			die("success");
		} else if ($_GET['request'] == "changeProfilePic") {

			$empId = $_POST['empId'];

?>
	<small><i><span class="text-red">Note:</span> Acceptable file format are [ jpg, png, gif ] and file size should not be greater than 1MB.</i></small><br><br>
	<input type="hidden" name="empId" value="<?php echo $empId; ?>">

	<img id="photoprofile" class="img-circle center profilePhoto"><br>
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<center><input type='button' id='profile_change' style='display:none;' class='btn btn-primary btn-md btn-block' value='Change Photo?' onclick='changePhoto("Photo","profile","profile_change")'>
				<input type='file' name='profile' id='profile' class='btn btn-default btn-block' onchange='readURL(this,"profile");'>
				<input type='button' name='clearprofile' id='clearprofile' style='display:none' class='btn btn-warning btn-block' value='Clear' onclick="clears('profile','photoprofile','clearprofile')">
			</center>
		</div>
	</div>

	<script type="text/javascript">
		var empId = $("[name = 'empId']").val();

		$('#photoprofile').removeAttr('src');
		$.ajax({
			type: "POST",
			url: "employee_information_details.php?request=getProfilePic",
			data: {
				empId: empId
			},
			success: function(data) {

				if (data != '') {
					document.getElementById("photoprofile").src = data;
					$('#profile').hide();
					$("#profile_change").show();
				} else {
					$("#profile_change").hide();
					$('#profile').show();
				}
			}
		});
	</script>
	<?php
		} else if ($_GET['request'] == "getProfilePic") {

			$empId = $_POST['empId'];
			$photo = $nq->getPhoto($empId);

			die($photo);
		} else if ($_GET['request'] == "uploadProfilePic") {

			$empId = $_POST['empId'];

			if (!empty($_FILES['profile']['name'])) {

				$photo = $nq->getPhoto($empId);
				unlink($photo);

				$image		= addslashes(file_get_contents($_FILES['profile']['tmp_name']));
				$image_name	= addslashes($_FILES['profile']['name']);
				$array 	= explode(".", $image_name);

				$filename 	= $empId . "=" . date('Y-m-d') . "=" . 'Profile' . "=" . date('H-i-s-A') . "." . end($array);
				$destination_path	= "../images/users/" . $filename;

				if (move_uploaded_file($_FILES['profile']['tmp_name'], $destination_path)) {

					mysql_query("UPDATE applicant SET photo = '" . mysql_real_escape_string($destination_path) . "' WHERE app_id = '" . $empId . "'") or die(mysql_error());
				}
			}

			die("success");
		}

		// newly added module
		else if ($_GET['request'] == "company_list") {

			$agency_code  = $_POST['agency_code'];
			$promo_company = $_POST['promo_company'];

			if ($agency_code == 0) {

	?>
		<option value=""> --Select-- </option>
		<?php

				$comp = mysql_query("SELECT pc_code, pc_name FROM `locate_promo_company` ORDER BY pc_name ASC") or die(mysql_error());
				while ($com = mysql_fetch_array($comp)) { ?>

			<option value="<?php echo $com['pc_code']; ?>" <?php if ($promo_company == $com['pc_name']) : echo "selected=''";
															endif; ?>><?php echo $com['pc_name']; ?></option><?php
																											}
																										} else {

																												?>
		<option value=""> --Select-- </option>
		<?php

																											$comp = mysql_query("SELECT company_name FROM $datab2.promo_locate_company 
															INNER JOIN  $datab2.promo_locate_agency ON promo_locate_company.agency_code = promo_locate_agency.agency_code 
														WHERE promo_locate_agency.agency_code = '$agency_code'") or die(mysql_error());
																											while ($com = mysql_fetch_array($comp)) {

																												$com_list = mysql_query("SELECT pc_code FROM locate_promo_company WHERE pc_name = '" . mysql_real_escape_string($com['company_name']) . "'") or die(mysql_error());
																												$row = mysql_fetch_array($com_list);
		?>

			<option value="<?php echo $row['pc_code']; ?>" <?php if ($promo_company == $com['company_name']) : echo "selected=''";
																												endif; ?>><?php echo $com['company_name']; ?></option><?php
																																									}
																																								}
																																							} else if ($_GET['request'] == 'product_list') {

																																								$pc_code = $_GET['company_code'];
																																								$company = mysql_query("SELECT pc_name FROM locate_promo_company WHERE pc_code = '$pc_code'") or die(mysql_error());
																																								$comp = mysql_fetch_array($company);

																																								$products = mysql_query("SELECT product FROM promo_company_products WHERE company = '" . mysql_real_escape_string($comp['pc_name']) . "'") or die(mysql_error());
																																										?>
	<option value=""> --Select-- </option><?php
																																								while ($prod = mysql_fetch_array($products)) {

											?>
		<option value="<?= $prod['product'] ?>"><?= $prod['product'] ?></option>
<?php
																																								}
																																							}

?>