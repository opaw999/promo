
<style type="text/css">
    
    .table-height {

        overflow: auto;
        max-height: 450px;
    }

    .preview {
        
        background-image: url("images/images.png");
        background-size:contain;
        width:700px;
        height:500px;
        border:2px solid #BBD9EE;
    }

</style>
<section class="content-header">
    <h1>
        <?php echo $subMenu ?>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#"><?php echo $module; ?></a></li>
        <li class="active"><?php echo $subMenu; ?></li>
    </ol>
</section>
<section class="content">

    <?php 

        $bunit = $_GET['filterBU'];
        $filterDate = $_GET['filterDate'];
        $filterMonth = $_GET['filterMonth'];
        $filterYear = $_GET['filterYear'];

    ?>

    <!-- Default box -->
    <div class="box box-primary">
        <div class="box-header with-border">
            <table align="center" width="98%">
                <tbody>
                    <tr>
                        <td>
                            <label><i class="glyphicon glyphicon-filter"></i> Filter</label>&nbsp;

                            <select name="filterBU" id="filterBU" onchange="filter('')">
                                <option value="">All Business Unit</option>
                                <?php 

                                    $store = mysql_query("SELECT bunit_id, bunit_name, bunit_field FROM `locate_promo_business_unit` WHERE status ='active' AND appraisal_status = 'active' AND hrd_location = '$hrCode'")or die(mysql_error());
                                    while ($str = mysql_fetch_array($store)) { ?>
                                        
                                        <option value="<?php echo $str['bunit_field']; ?>" <?php if($str['bunit_field'] == $bunit): echo "selected=''"; endif; ?>><?php echo $str['bunit_name']; ?></option> <?php
                                    }
                                ?>
                            </select>

                            <select name="filterDate" id="filterDate" onchange="filter('filterDate')">
                                <option value="">All</option>
                                <option value="today" <?php if($filterDate == "today"): echo "selected=''"; endif; ?>>Today</option>
                                <option value="yesterday" <?php if($filterDate == "yesterday"): echo "selected=''"; endif; ?>>Yesterday</option>
                                <option value="last7days" <?php if($filterDate == "last7days"): echo "selected=''"; endif; ?>>Last 7days</option>
                                <option value="last1month" <?php if($filterDate == "last1month"): echo "selected=''"; endif; ?>>Last 1 month</option>                    
                            </select>

                            <select name="filterMonth" id="filterMonth" onchange="filter('filterMonth')">
                                <option value="">EOC Per Month</option>    
                                <?php

                                    for($i=0; $i < count($nq->monthname()); $i++) {
                                        
                                        if($filterMonth == $nq->monthno()[$i]){
                                            echo "<option value='".$nq->monthno()[$i]."' selected>".$nq->monthname()[$i]."</option>";
                                        }else{
                                            echo "<option value='".$nq->monthno()[$i]."'>".$nq->monthname()[$i]."</option>";
                                        }
                                    }

                                ?>
                            </select>

                            <select name="filterYear" onchange="filter('filterYear')" style="display:none">
                                <option value="<?= date('Y', strtotime('-1 year')) ?>" <?php if($filterYear == date('Y', strtotime('-1 year'))) { echo 'selected'; } ?>><?= date('Y', strtotime('-1 year')) ?></option>
                                <option value="<?= date('Y') ?>" <?php if($filterYear == date('Y') || $filterYear == '') { echo 'selected'; } ?>><?= date('Y') ?></option>
                                <option value="<?= date('Y', strtotime('+1 year')) ?>" <?php if($filterYear == date('Y', strtotime('+1 year'))) { echo 'selected'; } ?>><?= date('Y', strtotime('+1 year')) ?></option>
                            </select> &nbsp;

                            <button class="btn btn-primary btn-sm" onclick="genEOCList()">Filter</button> &nbsp;
                            <a href="javascript:void(0)" class="btn btn-primary btn-sm" onclick="genReport()"> Generate in Excel</a>              
                        </td> 
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="box-body">
            

            <table id="eocList" class="table table-striped table-hover table1">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Startdate</th>
                        <th>EOCdate</th>
                        <?php if($hrCode == "asc") : 

                            $stores = mysql_query("SELECT bunit_acronym FROM `locate_promo_business_unit` WHERE appraisal_status = 'active' AND status = 'active' AND hrd_location = '$hrCode'")or die(mysql_error());
                            while ($bU = mysql_fetch_array($stores)) {

                                echo "<th width='8%'>".$bU['bunit_acronym']."</th>";
                            }

                        else : 

                            $stores = mysql_query("SELECT bunit_acronym FROM `locate_promo_business_unit` WHERE appraisal_status = 'active' AND status = 'active' AND hrd_location = '$hrCode'")or die(mysql_error());
                            while ($bU = mysql_fetch_array($stores)) {

                                echo "<th width='8%'>".$bU['bunit_acronym']."</th>";
                            }
                        endif; ?>
                        
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->

</section>

<!-- ./Modal -->
<div id="promoAppraisalDetails" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light-blue color-palette">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Performance Appraisal For Promodiser/Merchandiser</h4>
            </div>
        <div class="modal-body">
            <div class="promoAppraisalDetails"></div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<!-- ./Modal -->
<div id="uploadClearance" class="modal fade">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header bg-light-blue color-palette">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Upload Clearance</h4>
            </div>
        <form action="" id="dataUploadClearance" method="post" enctype="multipart/form-data">
            <div class="modal-body">
                <div class="uploadClearance"></div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary">Upload</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </form>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<script type="text/javascript">
    
    function filter(code){

        var filterBU = $("[name = 'filterBU']").val();
        var filterDate = $("[name = 'filterDate']").val();
        var filterMonth = $("[name = 'filterMonth']").val();
        var filterYear = $("[name = 'filterYear']").val();
        
        if(code == "filterDate"){

            filterYear = "<?= date('Y') ?>";
            $("[name = 'filterYear']").hide();
            if (filterDate != "") {

                $("[name = 'filterMonth']").val('');
                filterMonth = "";
            }
        } else if(code == "filterMonth"){

            $("[name = 'filterYear']").show();
            if (filterMonth != "") {

                $("[name = 'filterDate']").val('');
                filterDate = "";
            } else {

                $("[name = 'filterYear']").hide();
            }
        }

        // window.location = "?p=eocList&&module=Contract&&filterBU="+filterBU+"&&filterDate="+filterDate+"&&filterMonth="+filterMonth+"&&filterYear="+filterYear;
    }

    function genEOCList() {
        
        var filterBU = $("[name = 'filterBU']").val();
        var filterDate = $("[name = 'filterDate']").val();
        var filterMonth = $("[name = 'filterMonth']").val();
        var filterYear = $("[name = 'filterYear']").val();

        console.log(filterYear)

        window.location = "?p=eocList&&module=Contract&&filterBU="+filterBU+"&&filterDate="+filterDate+"&&filterMonth="+filterMonth+"&&filterYear="+filterYear;
    }

    function genReport(){

        var filterBU = $("[name = 'filterBU']").val();
        var filterDate = $("[name = 'filterDate']").val();
        var filterMonth = $("[name = 'filterMonth']").val();

        window.open("pages/contract/eocList_xls.php?filterBU="+filterBU+"&&filterDate="+filterDate+"&&filterMonth="+filterMonth);
    }

    function viewdetails(detailsId){

        $("#promoAppraisalDetails").modal({
            backdrop: 'static',
            keyboard: false
        });

        $("#promoAppraisalDetails").modal("show");

        $.ajax({
            type : "POST",
            url  : "employee_information_details.php?request=appraisalDetails",
            data : { detailsId:detailsId },
            success : function(data){
            
                $(".promoAppraisalDetails").html(data);  
            }
        });
    }

    function proceedTo(process,empId,recordNo){

        if (process != "") {

            $.alert.open({
                type: 'warning',
                cancel: false,
                content: "Are you sure you want to Proceed to "+process+"?",
                buttons:{
                    OK: 'Ok',
                    NO : 'Not Now'
                },

                callback: function(button) {
                    if (button == 'OK'){

                        if (process == "Blacklist") {

                            window.location = "?p=addBlacklist&&module=Blacklisted&&empId="+empId
                        } else if (process == "Resigned") {

                            window.location = "?p=resignation&&module=Resignation/Termination&&empId="+empId
                        } else {

                            $("#uploadClearance").modal({
                                backdrop: 'static',
                                keyboard: false
                            });

                            $("#uploadClearance").modal("show");

                            $.ajax({
                                type : "POST",
                                url  : "functionquery.php?request=uploadClearanceforRenewal",
                                data : { empId:empId, recordNo:recordNo },
                                success : function(data){
                                
                                    $(".uploadClearance").html(data);  
                                }
                            });
                        }
                    }           

                }
            });
        }
    }

    function changePhoto(file, photoid, change) {

        $.alert.open({
            type: 'warning',
            cancel: false,
            content: "You are attempting to change the uploaded "+file+", <br> Click OK to proceed.",
            buttons:{
                OK: 'Ok',
                NO: 'Not now'
            },

            callback: function(button) {
                if (button == 'OK'){

                    $('#'+change).hide();
                    $('#'+photoid).show();
                }           

            }
        });
    }

    function readURL(input, upload) {

        $('#clear'+upload).show();
        var res = validateForm(upload);
     
        if(res !=1){
            
            $("[name = '"+upload+"']").css("border-color","#ccc");
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#photo'+upload).attr('src', e.target.result);
                }
                    reader.readAsDataURL(input.files[0]);
               }
            }
        else
        {
            $('#clear'+upload).hide();
            $('#photo'+upload).removeAttr('src');
        } 
    }

    function validateForm(imgid)
    {   
        var img =  $("#"+imgid).val();
        var res = '';
        var i = img.length-1;   
        while(img[i] != "."){
            res = img[i] + res;     
            i--;
        }   
        
        //checks the file format
        if(res != "PNG" && res != "jpg" && res !="JPG" && res != "png"){
            $("#"+imgid).val("");
            errDup('Invalid File Format. Take note on the allowed file!');
            return 1;
        }   

        //checks the filesize- should not be greater than 2MB
        var uploadedFile = document.getElementById(imgid);
        var fileSize = uploadedFile.files[0].size < 1024 * 1024 * 2;
        if(fileSize == false){
            $("#"+imgid).val("");
            errDup('The size of the file exceeds 2MB!')     
            return 1;
        }
    }

    function clears(file,preview,clrbtn){

        $("#"+file).val("");
        $('#'+preview).removeAttr('src');
        $('#'+clrbtn).hide();
    }

</script>