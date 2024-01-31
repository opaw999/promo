<style type="text/css">
    
    .search-results{

        box-shadow: 5px 5px 5px #ccc; 
        margin-top: 1px; 
        margin-left : 0px; 
        background-color: #F1F1F1;
        width : 38%;
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
                <div class="box-header with-border">
                    <h3 class="box-title">Remove Outlet Details</h3>
                </div>

                <div class="box-body">
                    <div class="form-group">
                        <label>Search Employee</label>
                        <div class="input-group col-md-5">
                            <input class="form-control" type="text" name="employee" onkeyup="nameSearch(this.value)" autocomplete="off">
                            <span class="input-group-addon"><i class="fa fa-user"></i></span>
                        </div>
                        <div class="search-results" style="display: none;"></div>
                    </div>
                    <div class="displayStores"></div>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>
    
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
        <form action="" id="dataUploadClearanceforRemoveOutlet" method="post" enctype="multipart/form-data">
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
    
    function nameSearch(key){
        
        var str = key.trim();
        $(".search-results").hide();
        if(str == '') {
            $(".search-results-loading").slideUp(100);
        }
        else {
            $.ajax({
                type : "POST",
                url  : "functionquery.php?request=findActivePromoStation",
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

        var empId = empId.trim().split("*");

        $.ajax({
            type : "POST",
            url  : "functionquery.php?request=removeOutlet",
            data : { empId:empId[0].trim() },
            success : function(data){

                $(".displayStores").html(data);
            } 
        });
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

    function removeOutlet(code,empId,recordNo){
      
        if (code == "Remove") {

            $("#uploadClearance").modal({
                backdrop: 'static',
                keyboard: false
            });

            $("#uploadClearance").modal("show");

            var chk = $("[name = 'store[]']"); // name sa checkbox 
            var newChk = "";
            for (var i=0;i<chk.length;i++) {

                if (chk[i].checked == true) {
                    
                    newChk += chk[i].value+"*";
                }
            }

            $.ajax({
                type : "POST",
                url  : "functionquery.php?request=uploadClearanceforRemoveOutlet",
                data : { empId:empId, recordNo:recordNo, newChk:newChk },
                success : function(data){
                
                    $(".uploadClearance").html(data);  
                }
            });
        }
    }

    function checkField(id){
  
        var count = 0;

        $("[name = 'action']").val("");
        var chk = $("[name = 'store[]']"); // name sa checkbox 
        for (var i=0;i<chk.length;i++) {

            if (chk[i].checked == true) {
                count++;
            }
        }
        
        if (count > chk.length) {
            
            $("#chk_"+id).prop("checked",false);

        } else {
          
            if(count == 0){

                $("[name = 'action']").hide();
            } else {
                
                $("[name = 'action']").show();
            }

            if ($("#chk_"+id).is(':checked')) {
              
                $(".chk_"+id).css({
                    "color": "red", 
                    "font-style": "italic"
                });
            } else {
              
                $(".chk_"+id).css({
                    "color": "black", 
                    "font-style": "normal"
                });
            }
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