<?php 

    $company = mysql_real_escape_string($_GET['company']);
    $report = $_GET['report'];
    $store = $_GET['store'];
    $department = $_GET['department'];
    $month = explode('|',$_GET['month']);

    $where = "";
    if(!empty($month[1])){
        $year     = $month[1];
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
?>
<style type="text/css">
    
    .size-emp {

        overflow: auto;
        max-height: 400px;
    }
</style>
<section class="content-header">
  	<h1>
    	<?php echo $module; ?>
  	</h1>
  	<ol class="breadcrumb">
    	<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    	<li><a href="#"><?php echo $module; ?></a></li>
        <li class="active"><?php echo $subMenu; ?></li>
  	</ol>
</section>

<section class="content">

    <!-- Default box -->
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">List of End Contract for <?php echo $monthName." ".$year; ?></h3>
                </div>

                <div class="box-body">
                    <div class="size-emp">

                        <input type="hidden" name="store" value="<?php echo $store; ?>">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" class="chk_0" onclick="chk_termination(0)"></th>
                                    <th>Emp.ID</th>
                                    <th>Name</th>
                                    <th>Company</th>
                                    <th>BusinessUnit</th>
                                    <th>Department</th>
                                    <th>Position</th>
                                    <th>Startdate</th>
                                    <th>EOCdate</th>
                                    <th>PromoType</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 

                                    $counter = 0;
                                    $query = mysql_query("SELECT employee3.emp_id, name, startdate, eocdate, position, promo_company, promo_department, promo_type, company_duration
                                                                FROM employee3, promo_record 
                                                                    WHERE employee3.emp_id = promo_record.emp_id AND (emp_type = 'Promo' OR emp_type = 'Promo-NESCO' OR emp_type = 'Promo-EasyL') AND current_status = 'Active' $where ORDER BY eocdate, name ASC")or die(mysql_error());
                                    while ($row = mysql_fetch_array($query)) {
                                        
                                        $empId = $row['emp_id'];
                                        $storeName = "";
                                        $ctr = 0;
                                        $bunit = mysql_query("SELECT bunit_field, bunit_acronym FROM `locate_promo_business_unit`")or die(mysql_error());
                                        while ($str = mysql_fetch_array($bunit)) {

                                            $promo = mysql_query("SELECT promo_id FROM `promo_record` WHERE `".$str['bunit_field']."` = 'T' AND emp_id = '".$row['emp_id']."'")or die(mysql_error());
                                            if(mysql_num_rows($promo) > 0) {
                                                $ctr++;

                                                if($ctr == 1){

                                                    $storeName = $str['bunit_acronym'];
                                                } else {

                                                    $storeName .= ", ".$str['bunit_acronym'];
                                                }
                                            }
                                        }

                                        $counter++;
                                        echo "<tr>
                                                <td><input type='checkbox' name='chkEmpId[]' class='chk_$counter chkC' onclick='chk_termination($counter)' value='$empId'></td>
                                                <td>".$row['emp_id']."</td>
                                                <td>".ucwords(strtolower($row['name']))."</td>
                                                <td>".$row['promo_company']."</td>
                                                <td>$storeName</td>
                                                <td>".$row['promo_department']."</td>
                                                <td>".ucwords(strtolower($row['position']))."</td>
                                                <td>".date('m/d/Y', strtotime($row['startdate']))."</td>
                                                <td>".date('m/d/Y', strtotime($row['eocdate']))."</td>
                                                <td>".$row['promo_type']."</td>
                                            </tr>";
                                    }
                                ?>
                            </tbody>
                    </table>
                    </div>
                </div>
                <div class="box-footer">
                    <?php

                        if($report == "forStore"){ ?> 
                            
                            <button class="btn btn-primary" id="genReport" disabled="" onclick="genReport()">Generate Termination of Contract</button><?php
                        } else { ?>
                            
                            <button class="btn btn-primary" id="genReport" disabled="" onclick="genReportCompany()">Generate Termination of Contract for Company</button><?php
                        }
                    ?>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>

</section>
<script type="text/javascript">
    
    function chk_termination(no){
        
        if(no == 0){

            if ($(".chk_"+0).is(':checked')) {

                $(".chkC").prop("checked",true);
                 $("#genReport").prop("disabled",false);
            } else {

                $(".chkC").prop("checked",false);
                 $("#genReport").prop("disabled",true);
            }

        } else {
            var chkBtn = 0;
            var chk = document.getElementsByName('chkEmpId[]');
           
            for(var i = 0;i<chk.length;i++) {
                
                if(chk[i].checked == true) {
                    chkBtn = 1;
                } 
    
            } 
                if (chkBtn == 1) {
                  
                    $("#genReport").prop("disabled",false);
                } else {
                    $(".chkA").prop("checked",false);
                    $("#genReport").prop("disabled",true);
                }
        }
    }

    function genReport(){

        var store = $("[name = 'store']").val();
        var chk = document.getElementsByName('chkEmpId[]');
        var empIds = "";
           
        for(var i = 0; i<chk.length; i++) {
            
            if(chk[i].checked == true) {
                
                empIds += chk[i].value+"*";
            } 
        }

        var bunit = "";
        if (store != "") {

            var temp = store.split("/");
            bunit = temp[1].trim();
        }

        window.open("pages/reports/termination_of_contract.php?store="+bunit+"&&empIds="+empIds);
    }

    function genReportCompany(){

        var chk = document.getElementsByName('chkEmpId[]');
        var empIds = "";
           
        for(var i = 0; i<chk.length; i++) {
            
            if(chk[i].checked == true) {
                
                empIds += chk[i].value+"*";
            } 
        }

        window.open("pages/reports/termination_of_contract_company.php?empIds="+empIds);
    }
</script>