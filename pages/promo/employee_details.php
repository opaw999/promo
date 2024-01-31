<?php 
  
  $empId = $_GET['com'];
  $dateToday = date("Y-m-d");

  $query = mysql_query("SELECT emp_no, startdate, eocdate, emp_type, current_status, position, agency_code, promo_company, promo_department, vendor_code, type, sub_status FROM `employee3` LEFT JOIN `promo_record` 
                          ON employee3.emp_id = promo_record.emp_id AND employee3.record_no = promo_record.record_no WHERE (emp_type = 'Promo' OR emp_type = 'Promo-NESCO' OR emp_type = 'Promo-EasyL') AND employee3.emp_id = '$empId'")or die(mysql_error());
  $row = mysql_fetch_array($query);

    $photo = $nq->getPhoto($empId);
    $name = ucwords(strtolower($nq->getApplicantName2($empId)));
    $startdate = date("m/d/Y", strtotime($row['startdate']));
    $eocdate = date("m/d/Y", strtotime($row['eocdate']));
    $idNum = $row['emp_no'];

    // if naay agency
    $agency_name = "";
    if ($row['agency_code'] != 0) {
        
        $agency = mysql_query("SELECT agency_name FROM timekeeping.promo_locate_agency WHERE agency_code = '".$row['agency_code']."'")or die(mysql_error());
        $a = mysql_fetch_array($agency);

        $agency_name = $a['agency_name'];
    }

    // if naay vendor
    $vendor_name = "";
    if ($row['vendor_code'] != '') {
        
        $vendor = mysql_query("SELECT vendor_name FROM promo_vendor_lists WHERE vendor_code = '".$row['vendor_code']."'")or die(mysql_error());
        $v = mysql_fetch_array($vendor);

        $vendor_name = $v['vendor_name'].'<br>';
    }

    // get store name
    $storeName = "";
    $ctr = 0;
    $bunit = mysql_query("SELECT bunit_field, bunit_name FROM `locate_promo_business_unit`")or die(mysql_error());
    while ($str = mysql_fetch_array($bunit)) {

      $promo = mysql_query("SELECT promo_id FROM `promo_record` WHERE `".$str['bunit_field']."` = 'T' AND emp_id = '$empId'")or die(mysql_error());
      if(mysql_num_rows($promo) > 0) {
        $ctr++;

        if($ctr == 1){

          $storeName = $str['bunit_name'];
        } else {

          $storeName .= "<br> ".$str['bunit_name'];
        }
      }
    }

    $currentStatus = $row['current_status'];
    if($row['sub_status'] == ""){ $subStatus = ""; } else { $subStatus = "(".$row['sub_status'].")"; }
    
    switch ($row['current_status']) {
      case 'Active':
        $statClass = "btn-success";
        break;
      case 'Resigned':
        $statClass = "btn-warning";
        break;
      case 'V-Resigned':
        $statClass = "btn-warning";
        break;
      case 'Ad-Resigned':
        $statClass = "btn-warning";
        break;    
      case 'End of Contract':
        $statClass = "btn-warning";
        break;
      case 'blacklisted':
        $statClass = "btn-danger";
        break;
      default:

        $checkifblacklisted = mysql_query("SELECT app_id FROM `blacklist` WHERE app_id = '$empId'")or die(mysql_error());
        
        if(mysql_num_rows($checkifblacklisted) > 0){
          
            $statClass = "btn-danger";
            $currentStatus = "blacklisted";
        }else{  
          
            $statClass = "btn-primary";

            if($nq->getApplicantStatus($empId) != ""){

                $currentStatus = $nq->getApplicantStatus($empId);
            } else {

                $currentStatus = "--------------------";
            }
        }

        break;
    }
   
    $birthdate = $nq->getOneField("birthdate","applicant","app_id='".$empId."'");
 
    $age = ""; $msgbd = "";
    if($birthdate != "0000-00-00" && $birthdate != "NULL" && $birthdate != "") {
      
      $birthDate = date("m/d/Y", strtotime($birthdate));
      $birthDate = explode("/", $birthDate);
      $age = (date("md", date("U", mktime(0, 0, 0, $birthDate[0], $birthDate[1], $birthDate[2]))) > date("md")
        ? ((date("Y") - $birthDate[2]) - 1)
        : (date("Y") - $birthDate[2]));
     
      $md = explode('-',$birthdate);
      if($md[1]."-".$md[2] == date('m-d')): $msgbd = "<i class='text-red'>...has a birthday today!!!</i><br>"; endif;
    }

    $userLogin = mysql_query("SELECT login from users where emp_id = '$empId' and (usertype='employee' or usertype='supervisor')")or die(mysql_error());
    $ru = mysql_fetch_array($userLogin);
    $userStat = $ru['login'];

    switch ($userStat) {
      case 'yes':
        $userStatClass = "text-success";
        break;
      default:
        $userStatClass = "text-default";
        break;
    }

?> 
    <style type="text/css">
      .center {
          display: block;
          margin-left: auto;
          margin-right: auto;
          width: 90%;
      }

      .img {
          width:70px;height:80px;
      }

      .modf { 
          float:right;
          margin-top:-55px;
          font-size:20px
      }

      .table-height {

          overflow: auto;
          max-height: 450px;
      }

      .preview {
        
          background-image: url("images/images.png");
          background-size:contain;
          width:300px;
          height:370px;
          border:2px solid #BBD9EE;
      }

      .profilePhoto {
        
          background-image: url("images/images.png");
          background-size:contain;
          width:270px;
          height:270px;
          border:2px solid #BBD9EE;
      }
      
    </style>
    <!-- Main content -->
    <section class="content">

      <div class="row">
        <div class="col-md-3">

          <input type="hidden" name="empId" value="<?php echo $empId; ?>">
          <div class="box box-primary">
            <div class="box-body box-profile">
              <a href="javascript:void(0)" onclick="changeProfilePic()"><img width="200" height="200" class="img-circle center" src="<?php echo $photo; ?>"></a>
              <h3 class="profile-username text-center"><small><i class="fa fa-circle <?php echo $userStatClass; ?>"></i></small> <?php echo utf8_encode($name); ?></h3>
              <p class="text-muted text-center"><?php echo @$row['position']; ?></p>
              <p class="text-center"><button class="btn btn-success btn-sm" onclick="changeProfilePic()">Change Photo</button> &nbsp; <button class="btn btn-success btn-sm" onclick="viewProfile()">View Full Profile</button></p>
              
              <div style="padding:10px; font-style:italic">
                <p><strong>
                  <?php 

                    echo  $msgbd."".
                          $empId."<br>".
                          $idNum."<br>".
                          $age." years old <br><br>".
                          $agency_name."<br>".
                          $row['promo_company']."<br>".
                          $vendor_name."<br>".
                          $storeName."<br><br>".
                          $row['promo_department']."<br>".
                          $row['emp_type']."(".$row['type'].")<br>".
                          $startdate." - ".$eocdate;
                  ?>
                </strong></p>
              </div>
              <a href="#" class="btn btn-sm btn-block <?php echo $statClass; ?>"><b><?php echo $currentStatus." ". $subStatus;  ?></b></a>
            </div>
            <!-- /.box-body -->
          </div>

        </div>
        <!-- /.col -->
        <div class="col-md-9">
          <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                  <div class="col-md-6">        
                    <select class="form-control" style="width:250px" id='classify' onchange="getdefault(this.value)">             
                        <option value="basicinfo">Basic Information</option>
                        <option value="family">Family Background</option>
                        <option value="contact">Contact & Address Information</option>
                        <option value="educ">Educational Background</option>
                        <option value="seminar">Eligibility/Seminars/Trainings</option>
                        <option value="charref">Character References</option>
                        <option value="skills">Skills and Competencies</option>
                        <option value="eocapp">EOC Appraisal</option>
                        <option value="application">Application History</option>
                        <option value="employment">Contract History</option>
                        <option value="history">Employment History</option>
                        <option value="transfer">Job Transfer History</option>
                        <option value="blacklist">Blacklist History</option>
                        <option value="benefits">Benefits</option>
                        <option value="201doc">201 Documents</option>
                        <option value="pss">Peer-Subordinate-Supervisor</option>
                        <option value="remarks">Remarks</option>
                        <option value="useraccount">User Account</option>
                      </select>
                  </div>
                  <div class="col-md-6 modf pull-right"></div>
                </div><br>
                <div class="row">
                  <div class="col-md-12" id="details"></div>
                </div>
            </div>
          </div>
        </div>
      </div>

    </section>

<?php 

    include("modal.php");
    include("function.php");
?>