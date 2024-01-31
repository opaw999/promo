<section class="content-header">
    <h1>
        Query By Example
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#"><?php echo $module; ?></a></li>
        <li class="active"><?php echo $subMenu; ?></li>
    </ol>
</section>

<section class="content">

    <!-- Default box -->
<form action="pages/reports/qbe_xls.php" method="post" target="_blank">
    <div class="row">
        <div class="col-md-4">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Field Names</h3>
                </div>

                <div class="box-body">
                    <p><i><span class="text-red">Note:</span> Please click on the <code>checkbox beside the fieldnames</code> atleast one or as many as you can. (These fieldnames will be displayed in the report.)</i></p><hr>   
                    <?php

                        $values = array('home_address','gender','birthdate','religion','civilstatus','school','attainment','course','contactno','mother','father','height','weight','bloodtype','startdate','eocdate','current_status');
                        $fields = array('Home Address','Gender','Birthday','Religion','Civil Status','School','Attainment','Course','Contact Number','Mother','Father','Height','Weight','Bloodtype','Startdate','Eocdate','Current Status');
                        for ($i=0;$i<count($fields);$i++) {

                            $field = str_replace(" ","_",$fields[$i]);
                            $v = $values[$i]."%".$field;
                        
                            echo "<input type='checkbox' name='check[]' value=".$values[$i]." onclick=addField('".$field."') id='".$field."' class='chk'>&nbsp; 
                                <span class='".$field."f'>".$fields[$i]."</span><br>";     
                        }
                    ?>
                    <input type='checkbox' name='agecb' value="age" onclick="addField('Age')" id='Age' class='chk'>&nbsp; <span class='Agef'>Age</span><br>
                    <input type='checkbox' name='gradecb' value="grade" onclick="addField('Grade')" id='Grade' class='chk'>&nbsp; <span class='Gradef'>Appraisal Grade</span><br>
                    <input type='checkbox' name='yearsinservicecb' value="yearsinservice" onclick="addField('YearsInService')" id='YearsInService' class='chk'>&nbsp; <span class='YearsInServicef'>Date Hired & Years in Service</span><br>

                    <hr><p><i><span class="text-red">Take Note: </span>Emp. ID, Firstname, Middlename, Lastname, Company, Business Unit, Department, Promo Type, Contract Type and Duration from Company are default column names.</i></p>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <div class="col-md-8">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Query</h3>
                </div>

                <div class="box-body">
                    <p><i><span class="text-red">Note:</span> You are only allowed to select <code>five (5) conditions.</code>Once reached to 5 selection, unchecked the checkbox and select another one.</i></p><hr>

                    <div class="row">
                        <div class="col-md-6">
                            <?php

                                $civilstatus    = array('','Single','Married','Separated','Divorced','Widowed','Annulled');
                                $bloodtype      = array('','A','B','O','A+','A-','B+','B-','O+','O-','AB','AB+','AB-');
                                $currentstatus  = array('','Active','End of Contract','Resigned','Blacklisted');
                                $weight         = $nq->selectTable('weight');
                                $height         = $nq->selectTable('height');
                                $religion       = $nq->selectTable('religion');
                                $course         = $nq->selectTable('course');
                                $attainment     = $nq->selectTable('attainment');
                                $school         = $nq->selectTable('school');
                                $position       =array('','Promodiser','Merchandiser','Promoter','DEVELOPMENTAL RETAIL SALES COORDINATOR');
                            ?>  
                                <h4>Conditions</h4>
                                <div class="form-group">
                                    <input type='checkbox' name='check1[]' value="name" onclick="checkField('name')" id='namei'> &nbsp <span id='nameff'>Name</span>
                                    <input type='text' class='form-control' id='nametf' size='55' name='nname' disabled="" required="">
                                </div>
                                <div class="form-group">
                                    <input type='checkbox' name='check1[]' value="home_address" onclick="checkField('home_address')" id='home_addressi'> &nbsp <span id='home_addressff'>Home Address</span>
                                    <input type='text' class='form-control' id='home_addresstf' size='55' name='nhome_address' disabled="" required="">
                                </div>
                                <div class="form-group">
                                    <input type='checkbox' name='check1[]' value="gender" onclick="checkField('gender')" id='genderi'> &nbsp <span id='genderff'>Gender</span>
                                    <select class="form-control" id='gendertf' name="ngender" disabled="" required="">
                                        <option></option>
                                        <option>Male</option>
                                        <option>Female</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <input type='checkbox' name='check1[]' value="religion" onclick="checkField('religion')" id='religioni' > &nbsp <span id='religionff'>Religion</span>
                                    <input list="religions" class="form-control" size="50" name="nreligion" id='religiontf' autocomplete="off" disabled="" required="">
                                        <datalist id="religions">
                                            <?php 
                                                
                                                while ($rrel = mysql_fetch_array($religion)) {

                                                    echo "<option value='".$rrel['religion']."'>".$rrel['religion']."</option>";  
                                                }
                                            ?>                            
                                        </datalist>
                                </div>
                                <div class="form-group">
                                    <input type='checkbox' name='check1[]' value="civilstatus" onclick="checkField('civilstatus')" id='civilstatusi'> &nbsp <span id='civilstatusff'>Civil Status</span>   
                                    <select class="form-control" id='civilstatustf' name="ncivilstatus" disabled="" required="">
                                        <?php 

                                            for ($i=0;$i<count($civilstatus);$i++) {
                                                
                                                echo "<option value='$civilstatus[$i]'>".$civilstatus[$i]."</option>";  
                                            }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <input type='checkbox' name='check1[]' value="school" onclick="checkField('school')" id='schooli'> &nbsp <span id='schoolff'>School</span>
                                    <input list="schools" class="form-control" size="50" name="nschool" id="schooltf" autocomplete="off" disabled="" required="">
                                    <datalist id="schools">
                                        <?php 

                                            while ($rsch = mysql_fetch_array($school)) {
                                                
                                                echo "<option value='".$rsch['school_name']."'>".$rsch['school_name']."</option>";  
                                            }
                                        ?>                              
                                    </datalist>
                                </div>
                                <div class="form-group">
                                    <input type='checkbox' name='check1[]' value="attainment" onclick="checkField('attainment')" id='attainmenti'> &nbsp <span id='attainmentff'>Attainment</span>
                                    <input list="attainments" class="form-control" size="50" name="nattainment" id="attainmenttf" autocomplete="off" disabled="" required="">
                                    <datalist id="attainments">
                                        <?php 

                                            while ($ratt = mysql_fetch_array($attainment)) {
                                                
                                                echo "<option value='".$ratt['attainment']."'>".$ratt['attainment']."</option>";  
                                            }
                                        ?>                             
                                    </datalist>
                                </div>
                                <div class="form-group">
                                    <input type='checkbox' name='check1[]' value="course" onclick="checkField('course')" id='coursei'> &nbsp <span id='courseff'>Course</span>
                                    <input list="courses" class="form-control" size="50" name="ncourse" id="coursetf" autocomplete="off" disabled="" required="">
                                    <datalist id="courses">                         
                                        <?php 

                                            while ($rco = mysql_fetch_array($course)) {
                                                echo "<option value='".$rco['course_name']."'>".$rco['course_name']."</option>";  
                                            }
                                        ?>                            
                                    </datalist>
                                </div>
                                <div class="form-group">
                                    <input type='checkbox' name='check1[]' value="height" onclick="checkField('height')" id='heighti'> &nbsp <span id='heightff'>Height</span>
                                    <input list="heights" class="form-control" size="50" name="nheight" id="heighttf" autocomplete="off" disabled="" required="">
                                    <datalist id="heights">
                                        <?php 

                                            while ($rhe = mysql_fetch_array($height)) {
                                                
                                                echo "<option value='".$rhe['feet']."'>".$rhe['feet']."/".$rhe['cm']."</option>";  
                                            }
                                        ?>                         
                                    </datalist>
                                </div>
                                <div class="form-group">
                                    <input type='checkbox' name='check1[]' value="weight" onclick="checkField('weight')" id='weighti'> &nbsp <span id='weightff'>Weight</span>
                                    <input list="weights" class="form-control" size="50" name="nweight" id="weighttf" autocomplete="off" disabled="" required="">
                                    <datalist id="weights">
                                        <?php 

                                            while ($rwe = mysql_fetch_array($weight)) {
                                                
                                                echo "<option value='".$rwe['kilogram']."'>".$rwe['kilogram']."/".$rwe['pounds']."</option>";  
                                            }
                                        ?>                            
                                    </datalist>
                                </div>
                                <div class="form-group">
                                    <input type='checkbox' name='check1[]' value="bloodtype" onclick="checkField('bloodtype')" id='bloodtypei'> &nbsp <span id='bloodtypeff'>Bloodtype</span>
                                    <select class="form-control" id='bloodtypetf' name="nbloodtype" disabled="" required="">
                                        <?php 

                                            for ($i=0;$i<count($bloodtype);$i++) {

                                                echo "<option value='$bloodtype[$i]'>".$bloodtype[$i]."</option>";  
                                            }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <input type='checkbox' name='check1[]' value="position" onclick="checkField('position')" id='positioni'> &nbsp <span id='positionff'>Position</span>
                                    <select class="form-control" id='positiontf' name="nposition" disabled="" required="">
                                        <?php 

                                            for ($i=0;$i<count($position);$i++) {

                                                echo "<option value='$position[$i]'>".$position[$i]."</option>";  
                                            }
                                        ?>
                                    </select>               
                                </div>
                        </div>
                        <div class="col-md-6">
                            <h4>Filter</h4>
                            <div class="form-group">
                                Agency
                                <select class="form-control" name="agency" style="width: 100%;" onchange="select_agency(this.value)">
                                    <option value=""> --Select-- </option>
                                    <?php 

                                        $comp = mysql_query("SELECT * FROM timekeeping.`promo_locate_agency` ORDER BY agency_name ASC")or die(mysql_error());
                                        while ($com = mysql_fetch_array($comp)) { ?>
                                            
                                            <option value="<?php echo $com['agency_code']; ?>"><?php echo $com['agency_name']; ?></option><?php
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                Company
                                <select class="form-control" name="company" style="width: 100%;" onchange="selectProduct(this.value)">
                                    <option value=""> --Select-- </option>
                                    <?php 

                                        $query = mysql_query("SELECT pc_code, pc_name FROM `locate_promo_company` ORDER BY pc_name ASC")or die(mysql_error());
                                        while ($row = mysql_fetch_array($query)) {
                                            
                                            echo "<option value='".$row['pc_code']."'>".$row['pc_name']."</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                Business Unit
                                <select name="store" class="form-control" onchange="locateDept(this.value)">
                                    <option value=""> All Business Unit </option>
                                    <?php 

                                        $query = mysql_query("SELECT bunit_id, bunit_name, bunit_field FROM `locate_promo_business_unit` WHERE hrd_location = '$hrCode' AND status = 'active'")or die(mysql_error());
                                        while ($row = mysql_fetch_array($query)) {
                                            
                                            echo "<option value='".$row['bunit_id']."/".$row['bunit_field']."'>".$row['bunit_name']."</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                Department
                                <select name="department" class="form-control">
                                    <option value=""> --Select-- </option>
                                </select>
                            </div>
                            <div class="form-group">
                                Product
                                <select name="product" class="form-control">
                                    <option value=""> --Select-- </option>
                                    <?php 

                                        $products = mysql_query("SELECT product FROM locate_promo_product WHERE status = '1'")or die(mysql_error());
                                        while ($prod = mysql_fetch_array($products)) {
                                            
                                            echo '<option value="'.$prod['product'].'">'.$prod['product'].'</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                Promo Type
                                <select name="promo_type" class="form-control">
                                    <option value=""> -- Select -- </option>
                                    <option value="STATION">Station</option>
                                    <option value="ROVING">Roving</option>
                                </select>
                            </div>
                            <div class="form-group"> <i class="text-red">*</i>
                                Current Status
                                <select class="form-control" name="currentStatus" style="width: 100%;" required="">
                                    <option value=""> --Select-- </option>
                                    <option value="Active">Active</option>
                                    <option value="End of Contract">End of Contract</option>
                                    <option value="Resigned">Resigned</option>
                                    <option value="blacklisted">Blacklisted</option>
                                </select>
                            </div>
                            <div class="form-group"> <i class="text-red">*</i>
                                Date as of
                                <input type="text" name="dateAsOf" class="form-control datepicker" placeholder="mm/dd/yyyy" required="">
                            </div>
                            <div class="form-group"> <i class="text-red">*</i>
                                Report Title
                                <input type="text" name="report_title" class="form-control" autocomplete="off" required="">
                            </div>
                            <div class="form-group"> <i class="text-red">*</i>
                                Filename
                                <input type="text" name="filename" class="form-control" autocomplete="off" placeholder="ex. All_single_employees" required="">
                            </div>
                            <input type="submit" name="submit" class="btn btn-primary" value="Submit"/>          
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>
</form>

</section>
<script type="text/javascript">
    
    function locateDept(id){

        $.ajax({
            type : "POST",
            url  : "functionquery.php?request=locateDepartment",
            data : { id:id },
            success : function(data){
            
                $("[name = 'department']").html(data);  
            }
        }); 
    }

    function addField(fieldId){   

        var ctr = 0;
        var field = fieldId.replace(" ","_");
        var f = field+"f";

        if ($("#"+fieldId).is(':checked')) {
            $("."+f).css({
                "color": "red", 
                "font-style": "italic"
            });
        } else {   
   
            $("."+f).css({
                "color": "black", 
                "font-style": "normal"
            });
        }
    }

    function checkField(id){

        var count   = 0;        // counter
        var tf_id   = id+"tf";  // id of the textfield
        var newid   = id+"i";   // id of the checkbox
        var ii = $("#"+newid);            
        var newif = id+"ff";
        var chk = $("[name = 'check1[]']"); // name sa checkbox 
        for (var i=0;i<chk.length;i++) {

            if (chk[i].checked == true) {
                count++;
            }
        }

        if (count > 5) {
            
            $("#"+newid).prop("checked",false);

        } else {

            if ($("#"+newid).is(':checked')) {
                 
                $("#"+tf_id).prop("disabled",false);
                $("#"+newif).css({
                    "color": "red", 
                    "font-style": "italic"
                });
            } else {

                $("#"+tf_id).prop("disabled",true);
                $("#"+tf_id).val("");
                $("#"+newif).css({
                    "color": "black", 
                    "font-style": "normal"
                });
            }
        }
    }

    // newly added module add agency
    function select_agency(agency_code) {

        var promo_company = $("select[name = 'company']").val();

        $.ajax({
            type : "POST",
            url  : "employee_information_details.php?request=company_list",
            data : { agency_code:agency_code, promo_company:promo_company },
            success : function(data){
                
                $("select[name = 'company']").html(data);
            }
        });
    }
</script>