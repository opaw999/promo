<section class="content-header">
  	<h1>
    	<?php echo $module ?>
  	</h1>
  	<ol class="breadcrumb">
    	<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    	<li><a href="#"><?php echo $module; ?></a></li>
    	<li class="active"><?php echo $subMenu; ?></li>
  	</ol>
</section>

<?php       
    
    $searchThis = "";

    if (!empty($_GET['search'])) { ?>

        <script type="text/javascript">
            
            $(".searchTable").show();
        </script>
        <?php

        $searchThis = mysql_real_escape_string($_GET['search']);

        $query = mysql_query("SELECT employee3.emp_id, employee3.name, employee3.position, employee3.current_status, promo_record.promo_company, promo_record.promo_department, promo_record.promo_type, employee3.remarks  FROM `employee3` LEFT JOIN `promo_record` 
                        ON employee3.emp_id = promo_record.emp_id AND employee3.record_no = promo_record.record_no
                        WHERE (employee3.emp_type = 'Promo' OR employee3.emp_type = 'Promo-NESCO' OR employee3.emp_type = 'Promo-EasyL') AND (employee3.name LIKE '%$searchThis%' OR employee3.emp_id LIKE '%$searchThis%' OR promo_record.promo_company LIKE '%$searchThis%') ORDER BY name ASC")or die(mysql_error());
      
    } else { ?>

        <style type="text/css">
            .searchTable {
                display: none;
            }
        </style><?php
    }
?>
<section class="content">

    <!-- Default box -->
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><?php echo $subMenu; ?></label>
                                <div class="input-group">
                                    <input class="form-control searchPromo" type="text" name="searchPromo" style="text-transform:uppercase" autocomplete="off" value="<?php echo $searchThis; ?>">
                                    <span class="input-group-addon" onclick="searchEmployee()"><i class="fa fa-male"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="searchTable">
                        <table class="table table-hover table2">
                            <thead style="display: none;">
                                <tr>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 

                                    $counter = 0;
                                    while ($row = mysql_fetch_array($query)) { 

                                        $counter++;

                                        $queryApp = mysql_query("SELECT birthdate, home_address, civilstatus, photo FROM applicant where app_id = '".$row['emp_id']."'")or die(mysql_error());                    
                                        $fetch = mysql_fetch_array($queryApp);

                                        $photo = $fetch['photo'];
                                        if(empty($photo)){
                                            $photo = '../images/users/icon-user-default.png';
                                        }

                                        if (strtolower($row['current_status']) == "active") {
                                            
                                            $classSpan = "btn btn-xs btn-success";
                                        } else if (strtolower($row['current_status']) == "blacklisted") {
                            
                                            $classSpan = "btn btn-xs btn-danger";
                                        } else {
                                            
                                            $classSpan = "btn btn-xs btn-warning";
                                        }

                                        $store = "";
                                        $ctr = 0;
                                        $bunit = mysql_query("SELECT bunit_field, bunit_acronym FROM `locate_promo_business_unit`")or die(mysql_error());
                                        while ($str = mysql_fetch_array($bunit)) {
                                            
                                            $promo = mysql_query("SELECT promo_id FROM `promo_record` WHERE `".$str['bunit_field']."` = 'T' AND emp_id = '".$row['emp_id']."'")or die(mysql_error());
                                            if(mysql_num_rows($promo) > 0) {
                                                $ctr++;

                                                if($ctr == 1){

                                                    $store = $str['bunit_acronym'];
                                                } else {

                                                    $store .= ", ".$str['bunit_acronym'];
                                                }
                                            }
                                        }

                                        ?>
                                        
                                                <tr>
                                                    <td>
                                                        <div class="row">
                                                            <div class="col-md-1">
                                                                <img class="" src="<?php echo $photo; ?>" alt="" style="width: 100%; height: 100%;">
                                                            </div>
                                                            <div class="col-md-11">
                                                                <?php 

                                                                    echo "<p><span class='text-red'>[".$counter."]</span> <strong>".$row['emp_id']." <a href='?p=profile&&module=Promo&&com=".$row['emp_id']."'>".utf8_encode(ucwords(strtolower($row['name'])))."</a></strong> &nbsp;<span class='$classSpan'>".$row['current_status']."</span>";
                                                                    echo "<span class='text-success'><br><strong>Company: </strong><i>".$row['promo_company']."</i> <strong>Business Unit: </strong><i>$store</i> <strong>Department: </strong><i>".$row['promo_department']."</i> <strong>Promo Type: </strong><i>".$row['promo_type']."</i></span>";
                                                                    echo "<br><strong>Position: </strong><i>".$row['position']."</i> <strong>Civil Status: </strong><i>".$fetch['civilstatus']."</i> <strong>Birthdate: </strong><i>".date('F d, Y', strtotime($fetch['birthdate']))."</i> <strong>Home Address: </strong><i>".$fetch['home_address']."</i>";
                                                                    echo "<br><strong>Remarks: </strong><i>".$row['remarks']."</i></p>";
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            
                                        <?php
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>

</section>
<script type="text/javascript">
    
    function searchEmployee(){
     
        var search = $(".searchPromo").val();
        var loc = document.location;

        window.location = loc+"&&search="+search;
    }
</script>