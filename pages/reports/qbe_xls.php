<?php

	session_start();
	include("../../../connection.php");

	// location
	$hrLocation = mysql_query("SELECT company_code, bunit_code, dept_code FROM `employee3` WHERE emp_id = '".$_SESSION['emp_id']."' AND current_status = 'Active'")or die(mysql_error());
	$hr = mysql_fetch_array($hrLocation);

	$ccHr = $hr['company_code'];
	$bcHr = $hr['bunit_code'];
	$dcHr = $hr['dept_code'];

	if ($ccHr != "07") {
		$hrCode = 'asc';
	} else {

		$hrCode = 'cebo';
	}


	if(isset($_POST['submit']))
	{	
		$filename = str_replace(" ","_", @$_POST['filename']);	
		header("Cache-Control: public"); 
		header("Content-Type: application/octet-stream");
		header( "Content-Type: application/vnd.ms-excel; charset=utf-8" );
		header( "Content-disposition: attachment; filename=".$filename.".xls");
	
		$ff = "";	
		$f = @$_POST['check'];	
		
		for($i=0;$i<count($f);$i++)
		{					
			if($i<count($f)-1)
			{						
				$ff = $ff.$f[$i].",";								
			}else{
				$ff = $ff.$f[$i];						
			}		
		}	
		//check if age is checked in the fieldnames
		$empage 		= @$_POST['agecb'];
		$grade 			= @$_POST['gradecb'];
		$yearsinservice = @$_POST['yearsinservicecb'];
		//get age function
		date_default_timezone_set('Asia/Manila');
		function getAge( $dob, $tdate )
		{
			$age = 0;
			while( $tdate >= $dob = strtotime('+1 year', $dob)){
					++$age;
			}return $age;
		}
		
		$ch 		= @$_POST['check1'];
		$ch_array 	= array();	
			
		for($i=0;$i<count($ch);$i++)
		{		
			$ch_array[$i] = $ch[$i];	
		}	
		
		//main queries
		$name 			= @$_POST['nname'];
		$homeaddress 	= @$_POST['nhome_address'];
		$religion		= @$_POST['nreligion'];
		$gender			= @$_POST['ngender'];
		$civilstatus 	= @$_POST['ncivilstatus'];
		$attainment 	= @$_POST['nattainment'];
		$school			= @$_POST['nschool'];
		$course			= @$_POST['ncourse'];
		$height 		= @$_POST['nheight'];
		$weight			= @$_POST['nweight'];
		$bloodtype 		= @$_POST['nbloodtype'];
		$position 		= @$_POST['nposition'];
		//$emptype 		= @$_POST['emp_type'];
		$contactno		= @$_POST['ncontactno'];
		

		//other details
		$agency 		= @$_POST['agency'];
		$cc 			= @$_POST['company'];
		$bc 			= @$_POST['store'];
		$dc 			= @$_POST['department'];
		$title 			= @$_POST['report_title'];
		$currentstatus 	= @$_POST['currentStatus'];
		$promo_type 	= @$_POST['promo_type'];
		$product 		= @$_POST['product'];

		$company = mysql_real_escape_string($nq->getPromoCompanyName($cc));
		$where = "";

		if ($bc != "") {

			$store = explode("/", $bc);
			$where .= "AND $store[1] = 'T' ";
		}

		if ($dc != "") { 
				
			$where .= "AND promo_department = '$dc' ";
		}

		if($cc !="")
		{
			$where .= "AND promo_company = '$company'";
		} 

		if ($agency != "") {
			
			$where .= "AND agency_code = '$agency'";
		}

		if ($promo_type != "") {
			
			$where .= "AND promo_type = '$promo_type'";
		}

		//names
		//remove the special characters
		$str 	= preg_replace('/[^A-Za-z0-9\. -]/', '', mysql_real_escape_string(trim($name))); 
		//put spaces in the first and last part of the string
		$str 	= " ".$str." ";	
		$str 	= preg_replace('/  */', '%', $str);

		if($name)		{	$nm 	= "and name like '%$str%' "; 					$nmquery	= "name like '$str'; "; }
		if($homeaddress){ 	$hm 	= "and home_address like '%$homeaddress%'"; 	$hmquery 	= "home address like '$homeaddress'; "; }
		if($religion)	{ 	$rel 	= "and religion like '%$religion%' "; 			$relquery 	= "religion like '$religion'; "; }
		if($gender)		{ 	$gen 	= "and gender = '$gender' "; 					$genquery 	= "gender = '$gender'; ";}
		if($civilstatus){ 	$cv 	= "and civilstatus = '$civilstatus' ";  		$cvquery	= "civilstatus = '$civilstatus'; "; }
		if($attainment)	{ 	$attain = "and attainment like '%$attainment%' ";  		$attainquery= "attainment like '$attainment';  "; }
		if($school) { 		$sch 	= "and school like '%$school%' "; 				$schquery 	= "school like '$school';  ";}
		if($course) { 		$cours 	= "and course like '%$course%' "; 				$coursquery	= "course like '$course'; ";}
		if($height) { 		$hei 	= "and height like '%$height%' "; 				$heiquery 	= "height like '$height'; ";}
		if($weight) { 		$wei 	= "and weight like '%$weight%' "; 				$weiquery 	= "weight like '$weight'; "; }
		if($bloodtype) { 	$bt 	= "and bloodtype = '$bloodtype' ";				$btquery 	= "bloodtype = '$bloodtype'; "; }
		if($position)  { 	$pos 	= "and position like '%$position%' "; 			$posquery 	= "position like '$position'; "; }	
		if($contactno){		$cno	= "and contactno like '%$contactno%'";			$contactnoquery	= "contactno like '%$contactno%'"; }
	
		if($currentstatus){ $csquery 	= "current_status = '$currentstatus' "; }

		$details	= @$nm." ".@$hm." ".@$rel." ".@$gen." ".@$cv." ".@$attain." ".@$sch." ".@$cours." ".@$hei." ".@$wei." ".@$bt." ".@$pos." ".@$type." ".@$lod." ".@$cno;
		$condition 	= @$nmquery." ".@$hmquery.@$relquery.@$genquery.@$cvquery.@$attainquery.@$schquery.@$coursquery.@$heiquery.@$weiquery.@$btquery.@$posquery.@$csquery.@$typequery.@$lodquery.@$contactnoquery;
			
		if($ff == ""){
			$select = "employee3.emp_id, employee3.emp_no, employee3.emp_pins, employee3.record_no, employee3.startdate, employee3.eocdate, firstname,middlename,lastname,birthdate,position, agency_code, promo_company, promo_department, vendor_code, company_duration, promo_type, type";
		}else{  
			$select = "employee3.emp_id, employee3.emp_no, employee3.emp_pins, employee3.record_no, employee3.startdate, employee3.eocdate, firstname,middlename,lastname,birthdate,position, agency_code, promo_company, promo_department, vendor_code, company_duration, promo_type, type,".$ff;
		}		
						
		//required
		if($currentstatus){ $cs = "current_status = '$currentstatus' "; $cs = "current_status = '$currentstatus' "; }

		//date details from and to
		if($_POST['dateAsOf'] != ""){ $where .= "AND startdate <= '".date('Y-m-d', strtotime($_POST['dateAsOf']))."'"; } else { $where .= ""; }			

		
		/************************************************************************/
			//other details	
			echo "<br>";			
			echo "<i>Date Generated : ".date('F d, Y')."</i><br>";
			echo "<i>Generated Thru : HRMS - Promo</i><br>";
			echo "<i>Generated by : ".$_SESSION['name']."</i><br>";	
			echo "<i>Report Title : ".$_POST['report_title']."</i><br><br>";	
			echo "<br>";
		/************************************************************************/	
	
		//query
		mysql_query("SET NAMES utf8"); 

		$query = mysql_query("SELECT $select from employee3 inner join applicant on applicant.app_id = employee3.emp_id inner join promo_record on employee3.emp_id=promo_record.emp_id where colc != 'T' AND colm != 'T' AND ".$cs." ".@$details."  ".@$where_date." AND (emp_type = 'Promo' OR emp_type = 'Promo-NESCO' OR emp_type = 'Promo-EasyL') $where order by firstname, promo_company, promo_department")or die(mysql_error());	
			
		echo "<table border='1'";
			//table header
			echo "<tr>";
				echo "<th align='center'>HRMS ID.</th>";
				echo "<th align='center'>Emp. No.</th>";
				echo "<th align='center'>Emp. Pins.</th>";
				echo "<th align='center'>FirstName</th>";
				echo "<th align='center'>MiddleName</th>";
				echo "<th align='center'>LastName</th>";
				echo "<th align='center'>Agency</th>";	
				echo "<th align='center'>Company</th>";	
				echo "<th align='center'>Business Unit</th>";	
				echo "<th align='center'>Department</th>";
				echo "<th align='center'>Product</th>";
				echo "<th align='center'>Vendor</th>";
				echo "<th align='center'>Position</th>";
				echo "<th align='center'>Promo Type</th>";
				echo "<th align='center'>Contract Type</th>";
				if($empage !=""){					
					echo "<th align='center'><b>Age</b></th>";	
				}
				echo "<th align='center'>Duration from Company</th>";
				
				$fn = explode(",",$ff);	
				
				for($i=0;$i<count($fn);$i++)
				{
					if($fn[$i] == "civilstatus"){
						echo "<th align='center'>Civil Status</th>";	
					}else if($fn[$i] == "home_address"){
						echo "<th align='center'>Home Address</th>";											
					}else if($fn[$i] == ""){

					}else{
						echo "<th align='center'>".ucwords(strtolower($fn[$i]))."</th>";					
					}
				}

				if ($grade == "grade") {

					$bunit = mysql_query("SELECT bunit_acronym FROM `locate_promo_business_unit` WHERE appraisal_status = 'active' AND hrd_location = '$hrCode'")or die(mysql_error());
					while ($str = mysql_fetch_array($bunit)) {

						echo "<th align='center'>".$str['bunit_acronym']."</th>";
					}
				}	

				if ($yearsinservice == "yearsinservice") {
					
					echo "<th align='center'>Date Hired</th>";
					echo "<th align='center'>Years in Service</th>";
				}			
			echo "</tr>";
		
			$countDisplay = array();	
						
			while($row = mysql_fetch_array($query))
			{		
				if($empage != "")
				{					
					$datebirth = $row['birthdate'];/*** a date before 1970 ***/		
					$dob = strtotime($datebirth);		
					$now = date('Y-m-d');/*** another date ***/		
					$tdate = strtotime($now);/*** show the date ***/		
					$age= getAge( $dob, $tdate );
					//if($row['birthdate']!=""){ $age = $age; }   	 
					if($datebirth !=""){ $age = $age; }    
				}
				
				if(!in_array($row['emp_id'], $countDisplay)) // checks if the emp_id is already in the array $countDisplay
				{
					array_push($countDisplay,$row['emp_id']); //save and pushes the emp_id to the array $countDisplay							

					//checks if the current_status id eoc but still having an active status
					if($currentstatus == 'End of Contract'){
						$query_check = mysql_query("SELECT emp_id, name FROM employee3 WHERE emp_id = '$row[emp_id]' and current_status = 'active' ")or die(mysql_error());
						$cqc = mysql_num_rows($query_check);				
					}else{
						$cqc=0;
					}

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

					if(!$cqc)
					{
						$products = "";
						$ctrProd = 0;

						if ($product != "") {

							$product_sql = mysql_query("SELECT product FROM promo_products WHERE record_no = '".$row['record_no']."' AND emp_id = '".$row['emp_id']."' AND product = '".$product."'")or die(mysql_error());
							
						} else {

							$product_sql = mysql_query("SELECT product FROM promo_products WHERE record_no = '".$row['record_no']."' AND emp_id = '".$row['emp_id']."'")or die(mysql_error());
						}

						while ($p = mysql_fetch_array($product_sql)) {
							
							if ($ctrProd == 0) {
								
								$products = $p['product'];
							} else {

								$products .= ", ".$p['product'];
							}
							$ctrProd++;
						}

						$sql = mysql_query("SELECT agency_name FROM timekeeping.promo_locate_agency WHERE agency_code = '".$row['agency_code']."'")or die(mysql_error());
						$agency = mysql_fetch_array($sql);
						$agency_name = $agency['agency_name'];

						$sql = mysql_query("SELECT vendor_name FROM promo_vendor_lists WHERE vendor_code = '".$row['vendor_code']."'")or die(mysql_error());
						$v = mysql_fetch_array($sql);
						$vendor_name = $v['vendor_name'];

						if ($product != "" && $products != "") {
							
							$fn = explode(",",$ff);
							echo "<tr>";	
							echo "<td>".$row['emp_id']."</td>";			
							echo "<td>".$row['emp_no']."</td>";			
							echo "<td>".$row['emp_pins']."</td>";			
							echo "<td>".mb_convert_encoding($row['firstname'], 'UCS-2LE', 'UTF-8')."</td>";	
							echo "<td>".mb_convert_encoding($row['middlename'], 'UCS-2LE', 'UTF-8')."</td>";	
							echo "<td>".mb_convert_encoding($row['lastname'], 'UCS-2LE', 'UTF-8')."</td>";							
							echo "<td>".$agency_name."</td>";
							echo "<td>".$row['promo_company']."</td>";
							echo "<td>".ucwords(strtolower($storeName))."</td>";
							echo "<td>".$row['promo_department']."</td>";	
							echo "<td>".$products."</td>";	
							echo "<td>".$vendor_name."</td>";	
							echo "<td>".ucwords(strtolower($row['position']))."</td>";	
							echo "<td>".$row['promo_type']."</td>";
							echo "<td>";
							if(strtolower($row['type']) == "contractual"){
								echo "Contractual";
							} else {
								echo "Seasonal";
							}
							echo "</td>";
							if($empage != ""){
								echo "<td>".$age."</td>";	
							}
							echo "<td>";
								if($row['company_duration'] == '0000-00-00') { echo ""; }	
								else {
									echo $nq->changeDateFormat('m/d/Y',$row['company_duration']);
								}
							echo "</td>";
							
							for($i=0;$i<count($fn);$i++)
							{		
								if($fn[$i] == ""){						
									echo "";
								}else{
									echo "<td align='left'>".mb_convert_encoding($row[$fn[$i]], 'UCS-2LE', 'UTF-8')."</td>";		
								}			
							}
							
							if ($grade == "grade") {

								$bunit = mysql_query("SELECT bunit_field, bunit_name FROM `locate_promo_business_unit` WHERE appraisal_status = 'active' AND hrd_location = '$hrCode'")or die(mysql_error());
								while ($str = mysql_fetch_array($bunit)) {

									echo "<td>";
									$promo = mysql_query("SELECT promo_id FROM `promo_record` WHERE `".$str['bunit_field']."` = 'T' AND emp_id = '".$row['emp_id']."'")or die(mysql_error());
									if(mysql_num_rows($promo) > 0) {
										
										$epas = mysql_query("SELECT `numrate` FROM `appraisal_details` WHERE `emp_id` = '".$row['emp_id']."' and `record_no` = '".$row['record_no']."' and `store` = '".$str['bunit_name']."' LIMIT 1")or die(mysql_error());
										$fetch = mysql_fetch_array($epas);

										echo $fetch['numrate'];
									}
									echo "</td>";
								}		
							}

							if ($yearsinservice == "yearsinservice") {
								
								$yrsInService = mysql_query("SELECT date_hired FROM application_details WHERE app_id = '".$row['emp_id']."'")or die(mysql_error());
								$yrs = mysql_fetch_array($yrsInService);

								$date_hired = $yrs['date_hired'];
								if ($date_hired == "0000-00-00" || $date_hired == "" || $date_hired == NULL) {
									
									$date_hired = $row['startdate'];
								} else {

									$date_hired = date("m/d/Y", strtotime($date_hired));
								}

								$date2 = date('Y-m-d');
								$dif = abs(strtotime($date2) - strtotime($date_hired));
											
								$year = floor($dif / (365*60*60*24));
								$months = floor(($dif - $year * 365*60*60*24) / (30*60*60*24));
								$day = floor(($dif - $year * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
								if($year == 0){
									$no_of_yrs = $months." month(s) & ".$day." day(s)";
								}else{
									$no_of_yrs = $year."yrs & ".$months."mos & ".$day."days ";
								}

								echo "<td>$date_hired</td>";
								echo "<td>$no_of_yrs</td>";
							}
							echo "</tr>";
							
						} else if ($product == "") {

							$fn = explode(",",$ff);
							echo "<tr>";	
							echo "<td>".$row['emp_id']."</td>";			
							echo "<td>".$row['emp_no']."</td>";			
							echo "<td>".$row['emp_pins']."</td>";			
							echo "<td>".mb_convert_encoding($row['firstname'], 'UCS-2LE', 'UTF-8')."</td>";	
							echo "<td>".mb_convert_encoding($row['middlename'], 'UCS-2LE', 'UTF-8')."</td>";	
							echo "<td>".mb_convert_encoding($row['lastname'], 'UCS-2LE', 'UTF-8')."</td>";							
							echo "<td>".$agency_name."</td>";
							echo "<td>".$row['promo_company']."</td>";
							echo "<td>".ucwords(strtolower($storeName))."</td>";
							echo "<td>".$row['promo_department']."</td>";	
							echo "<td>".$products."</td>";	
							echo "<td>".$vendor_name."</td>";
							echo "<td>".ucwords(strtolower($row['position']))."</td>";	
							echo "<td>".$row['promo_type']."</td>";
							echo "<td>";
							if(strtolower($row['type']) == "contractual"){
								echo "Contractual";
							} else {
								echo "Seasonal";
							}
							echo "</td>";
							if($empage != ""){
								echo "<td>".$age."</td>";	
							}
							echo "<td>";
								if($row['company_duration'] == '0000-00-00') { echo ""; }	
								else {
									echo $nq->changeDateFormat('m/d/Y',$row['company_duration']);
								}
							echo "</td>";
							
							for($i=0;$i<count($fn);$i++)
							{		
								if($fn[$i] == ""){						
									echo "";
								}else{
									echo "<td align='left'>".mb_convert_encoding($row[$fn[$i]], 'UCS-2LE', 'UTF-8')."</td>";		
								}			
							}
							
							if ($grade == "grade") {

								$bunit = mysql_query("SELECT bunit_field, bunit_name FROM `locate_promo_business_unit` WHERE appraisal_status = 'active' AND hrd_location = '$hrCode'")or die(mysql_error());
								while ($str = mysql_fetch_array($bunit)) {

									echo "<td>";
									$promo = mysql_query("SELECT promo_id FROM `promo_record` WHERE `".$str['bunit_field']."` = 'T' AND emp_id = '".$row['emp_id']."'")or die(mysql_error());
									if(mysql_num_rows($promo) > 0) {
										
										$epas = mysql_query("SELECT `numrate` FROM `appraisal_details` WHERE `emp_id` = '".$row['emp_id']."' and `record_no` = '".$row['record_no']."' and `store` = '".$str['bunit_name']."' LIMIT 1")or die(mysql_error());
										$fetch = mysql_fetch_array($epas);

										echo $fetch['numrate'];
									}
									echo "</td>";
								}		
							}

							if ($yearsinservice == "yearsinservice") {
								
								$yrsInService = mysql_query("SELECT date_hired FROM application_details WHERE app_id = '".$row['emp_id']."'")or die(mysql_error());
								$yrs = mysql_fetch_array($yrsInService);

								$date_hired = $yrs['date_hired'];
								if ($date_hired == "0000-00-00" || $date_hired == "" || $date_hired == NULL) {
									
									$date_hired = $row['startdate'];
								} else {

									$date_hired = date("m/d/Y", strtotime($date_hired));
								}

								$date2 = date('Y-m-d');
								$dif = abs(strtotime($date2) - strtotime($date_hired));
											
								$year = floor($dif / (365*60*60*24));
								$months = floor(($dif - $year * 365*60*60*24) / (30*60*60*24));
								$day = floor(($dif - $year * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
								if($year == 0){
									$no_of_yrs = $months." month(s) & ".$day." day(s)";
								}else{
									$no_of_yrs = $year."yrs & ".$months."mos & ".$day."days ";
								}

								echo "<td>$date_hired</td>";
								echo "<td>$no_of_yrs</td>";
							}
							echo "</tr>";
						}
						
					}	

			 	}
			}
			echo "</table>";


		if(mysql_num_rows($query) < 1 ){
			echo "<br><h3>No Result Found!</h3>";
		}
	}

	//logs
	$date = date("Y-m-d");
  	$time = date("H:i:s");     
  	$qw   = $nq->savelogs("Generated QBE - Report title: ".$_POST['report_title'],$date,$time,$_SESSION['emp_id'],$_SESSION['username']);

?>