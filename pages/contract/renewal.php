<style type="text/css">
    
    .search-results{

        box-shadow: 5px 5px 5px #ccc; 
        margin-top: 1px; 
        margin-left : 0px; 
        background-color: #F1F1F1;
        width : 89%;
        border-radius: 3px 3px 3px 3px;
        font-size: 18x;
        padding: 8px 10px;
        display: block;
        position:absolute;
        z-index:9999;
        max-height:300px;
        overflow-y:scroll;
        overflow:auto; 
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
        <?php echo $module ?>
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
                <div class="box-body">

                    <p style='font-size:20px'>Manual</p>
                    <p><a href="javascript:void(0)" class='btn btn-primary' onclick="manual()">Renewal of Contract</a></p>
                    You are advised to use the <code>Online Renewal of Contract</code>. This is for <code>ABENSON OUTLET</code> only. Thank You!
                    <hr>

                    <p style='font-size:20px'>Online</p>
                    <p><a href="javascript:void(0)" class='btn btn-primary' onclick="extend()">Extend of Contract</a></p>
                    <code>Extending of Contract</code> for all employee. 
                    <hr>

                    <p><a href="javascript:void(0)" class='btn btn-primary' onclick="processEOC()">Online Process of EOC</a></p>
                    Listing the <code>End of Contract Employees</code> one month from today and one month after today. Proceed with the process of End of Contract.  

                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>   

</section>

<!-- ./Modal -->
<div id="extend" class="modal fade">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header bg-light-blue color-palette">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Renew Employee</h4>
            </div>
            <div class="modal-body">
                <div class="extend"></div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary submit" onclick="extendContract()">Proceed</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<!-- ./Modal -->
<div id="manualRenewal" class="modal fade">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header bg-light-blue color-palette">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="cancelRenewal()">
                <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Renew Employee</h4>
            </div>
            <div class="modal-body">
                <div class="manualRenewal"></div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary submit" onclick="submit()">Proceed</button>
                <button type="button" class="btn btn-default" onclick="cancelRenewal()">Cancel</button>
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
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeModal('clearance')"onclick="cancelRenewal()"onclick="closeModal('clearance')">
                <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Upload Clearance</h4>
            </div>
        <form action="" id="dataUploadClearanceAbenson" method="post" enctype="multipart/form-data">
            <div class="modal-body">
                <div class="uploadClearance"></div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary">Upload</button>
                <button type="button" class="btn btn-default" data-dismiss="modal" onclick="closeModal('clearance')">Close</button>
            </div>
        </form>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<!-- ./Modal -->
<div id="uploadEpas" class="modal fade">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header bg-light-blue color-palette">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeModal('epas')">
                <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Input Epas Grade</h4>
            </div>
            <div class="modal-body">
                <div class="uploadEpas"></div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" onclick="submitEpasAbenson()">Submit</button>
                <button type="button" class="btn btn-default" data-dismiss="modal" onclick="closeModal('epas')">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script type="text/javascript">

    function extend(){

        $("#extend").modal({
            backdrop: 'static',
            keyboard: false
        });

        $("#extend").modal("show");
        $(".submit").prop("disabled",true);

        $.ajax({
            type : "POST",
            url  : "functionquery.php?request=extend",
            success : function(data){
              
                $(".extend").html(data);
            }
        });
    }

    function processEOC(){

        window.location = "?p=eocList&&module=Contract&&filterBU=&&filterDate=&&filterMonth=&&filterYear=";
    }

    function nameSearch(key){

        var str = key.trim();
        $(".search-results").hide();
        if(str == '') {
            $(".search-results-loading").slideUp(100);
        }
        else {
            $.ajax({
                type : "POST",
                url  : "functionquery.php?request=findAllForExtendPromo",
                data : { str : str },
                success : function(data){

                    if(data){
                        $(".search-results").show().html(data);
                    }
                } 
            });
        }
    }

    function extendContract(){

        var empId = $("[name = 'employee']").val().split("*");

        if (empId[1].trim() == "") {

            $.alert.open({
                type: 'warning',
                cancel: false,
                content: "Please Fill-up Required Fields!",
                buttons:{
                    OK: 'Ok'
                },

                callback: function(button) {
                    if (button == 'OK'){

                        if (empId[1].trim() == "") {

                            $("[name = 'employee']").css("border-color","#dd4b39");
                        }
                    }           

                }
            });
        } else {

             $.alert.open({
                type: 'warning',
                cancel: false,
                content: "Process to renewal process now?",
                buttons:{
                    OK: 'Yes',
                    NO: 'Not now'
                },

                callback: function(button) {
                    if (button == 'OK'){
                            
                        window.location = "?p=processRenewal&&module=Contract&&empId="+empId[0].trim();
                    }           

                }
            });
        }
    }
    
    function manual(){

        $("#manualRenewal").modal({
            backdrop: 'static',
            keyboard: false
        });

        $("#manualRenewal").modal("show");
        $(".submit").prop("disabled",true);

        $.ajax({
            type : "POST",
            url  : "functionquery.php?request=manualRenewal",
            success : function(data){
              
                $(".manualRenewal").html(data);
            }
        });
    }

    function abensonNamesearch(key){
        
        var str = key.trim();
        $(".search-results").hide();
        if(str == '') {
            $(".search-results-loading").slideUp(100);
        }
        else {
            $.ajax({
                type : "POST",
                url  : "functionquery.php?request=findAbensonEmp",
                data : { str : str },
                success : function(data){

                    if(data){
                        $(".search-results").show().html(data);
                    }
                } 
            });
        }
    }

    function getEmpId(empId){

        $("[name='employee']").val(empId);
        $(".search-results").hide();
        $("[name = 'employee']").css("border-color","#ccc");

        $(".clearance").prop("disabled",false);
        $(".epas").prop("disabled",false);
        $(".submit").prop("disabled",false);
    }

    function withClearance(){

        $("#uploadClearance").modal({
            backdrop: 'static',
            keyboard: false
        });

        $("#uploadClearance").modal("show");
        var empId = $("[name='employee']").val().split("*");

        $.ajax({
            type : "POST",
            url  : "functionquery.php?request=uploadClearanceAbenson",
            data : { empId:empId[0].trim() },
            success : function(data){

                $(".uploadClearance").html(data);
            } 
        });

    }

    function withEpas(){

        $("#uploadEpas").modal({
            backdrop: 'static',
            keyboard: false
        });

        $("#uploadEpas").modal("show");
        var empId = $("[name='employee']").val().split("*");

        $.ajax({
            type : "POST",
            url  : "functionquery.php?request=uploadEpasAbenson",
            data : { empId:empId[0].trim() },
            success : function(data){

                $(".uploadEpas").html(data);
            } 
        });
    }

    function submitEpasAbenson(){

        var empId = $("[name = 'empId']").val();
        var recordNo = $("[name = 'recordNo']").val();
        var epas = $("[name = 'epas']").val();
        var store = $("[name = 'store']").val();
        var epasGrade = $("[name = 'epasGrade']").val();

        if (epasGrade == "") {

            $.alert.open({
                type: 'warning',
                cancel: false,
                content: "Please Fill-up Required Fields!",
                buttons:{
                    OK: 'Ok'
                },

                callback: function(button) {
                    if (button == 'OK'){

                        if (epasGrade == "") {

                            $("[name = 'epasGrade']").css("border-color","#dd4b39");
                        }
                    }           

                }
            });

        } else {

             $.ajax({
                type : "POST",
                url  : "functionquery.php?request=submitEpasAbenson",
                data : { empId:empId, recordNo:recordNo, epas:epas, store:store, epasGrade:epasGrade },
                success : function(data){

                    if (data == "success") {

                        $.alert.open({
                            type: 'warning',
                            title: 'Info',
                            icon: 'confirm',            
                            cancel: false,
                            content: "Epas Successfully Saved",
                            buttons:{
                                OK: 'Yes'
                            },
                            
                            callback: function(button) {
                                if (button == 'OK'){
                                        
                                    $("#uploadEpas").modal("hide");
                                    $(".epas").prop("disabled",true);
                                }           

                            }
                        });
                    } else  {

                        alert(data);
                    }
                } 
            });
        }
    }

    function noClearance(){

        errDup("You choose WITHOUT CLEARANCE. Please be sure to ask ASAP.");

        $(".clearance").prop("disabled",true);
    }

    function noEpas(){

        errDup("You choose NO EPAS. Please be sure to ask for epas grade ASAP.");
        $(".epas").prop("disabled",true);
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

    function closeModal(code){

        if (code == "clearance") {

            $("[name = 'clearance']").attr("checked",false);
        } else {

            $(".epas").attr("checked",false);
        }
    }

    function submit(){

        var empId = $("[name = 'employee']").val().split("*");

        if($(".clearance").is(':checked') && $(".epas").is(':checked')){

            $.alert.open({
                type: 'warning',
                cancel: false,
                content: "Process to renewal process now?",
                buttons:{
                    OK: 'Yes',
                    NO: 'Not now'
                },
                    callback: function(button) {
                        if (button == 'OK'){
                                
                            window.location = "?p=processRenewal&&module=Contract&&empId="+empId[0].trim();
                        }           

                    }
                });

        } else {

            errDup("Please check the required fields");
        }
    }

    function cancelRenewal(){

        if ($(".clearance").is(':checked') || $(".epas").is(':checked')) {
        
            var empId = $("[name='employee']").val().split("*");

            $.alert.open({
                type: 'warning',
                cancel: false,
                content: "Are you sure you want to cancel renewal process?",
                buttons:{
                    OK: 'Yes',
                    NO: 'Not now'
                },

                callback: function(button) {
                    if (button == 'OK'){
                        
                        $.ajax({
                            type : "POST",
                            url  : "functionquery.php?request=cancelRenewal",
                            data : { empId:empId[0].trim() },
                            success : function(data){
                          
                                if (data == "success") {

                                    window.location = "?p=renewal&&module=Contract";
                                } else {
                                    alert(data);
                                }
                            } 
                        });
                    }           

                }
            });

        } else {

            window.location = "?p=renewal&&module=Contract";
        }
    }
    
</script>