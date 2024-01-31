<style type="text/css">
    
    .search-results{

        box-shadow: 5px 5px 5px #ccc; 
        margin-top: 1px; 
        margin-left : 0px; 
        background-color: #F1F1F1;
        width : 92%;
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

    .size-emp {

        max-height: 450px;
        overflow: auto;
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
                    <h3 class="box-title">Subordinates Setup</h3>
                </div>

                <div class="box-body">
                    
                    <div class="row">
                        <div class="col-md-4">
                            <!-- <h3>Supervisor Details</h3> -->
                            <div class="form-group">
                                <label>Supervisor</label>
                                <div class="input-group">
                                    <input class="form-control" type="text" name="supervisor" onkeyup="nameSearch(this.value)" autocomplete="off">
                                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                </div>
                                <div class="search-results" style="display: none;"></div>
                            </div>
                            <div class="supervisorDetails"></div>
                        </div>
                        <div class="col-md-8">
                            <div class="subordinates">
                                <div style="width: 5%; margin:0 auto;">
                                    <br><br><br><br><br><br>
                                    <img src="../images/system/10.gif" id='loading-gif' style="display: none;"> 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>
    
</section>
<div id="addSubordinates" class="modal fade">
    <div class="modal-dialog" style="width: 80%;">
        <div class="modal-content">
            <div class="modal-header bg-light-blue color-palette">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Select Subordinate(s)</h4>
            </div>
        <div class="modal-body">
            <div class="addSubordinates"></div>
        </div>
        <div class="modal-footer">
            <span class="loadingSave"></span>
            <button type="button" class="btn btn-primary" onclick="saveSubordinates()"><i class="fa fa-send"></i> Submit</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
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
                url  : "functionquery.php?request=findSup",
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

        $("[name='supervisor']").val(empId);
        $(".search-results").hide();
        $("[name = 'supervisor']").css("border-color","#ccc");

        var empId = empId.trim().split("*");

        $.ajax({
            type : "POST",
            url  : "functionquery.php?request=supervisorDetails",
            data : { empId:empId[0].trim() },
            success : function(data){

                $(".supervisorDetails").html(data);
            } 
        });

         subordinates(empId[0]);
    }

    function chkSub(empId){

        if ($(".chkC_"+empId).is(':checked')) {

            $(".chk_"+empId).prop("checked",true);
        } else {

            $(".chk_"+empId).prop("checked",false);
        }
    }

    function chkIdC(empId){

        if ($(".chkIdC_"+empId).is(':checked')) {

            $(".chkId_"+empId).prop("checked",true);
        } else {

            $(".chkId_"+empId).prop("checked",false);
        }
    }

    function addSubordinates(){

        $("#addSubordinates").modal({
            backdrop: 'static',
            keyboard: false
          });

        $("#addSubordinates").modal("show");

        $.ajax({
            type : "POST",
            url  : "functionquery.php?request=addSubordinates",
            success : function(data){
            
                $(".addSubordinates").html(data);  
            }
        });
    }

    function bunit(id){
        
        $.ajax({
            type : "POST",
            url  : "functionquery.php?request=locateDepartment",
            data : { id:id },
            success : function(data){
                
                $("[name = 'department']").html(data);
            }
        });
    }

    function dept(){

        var store = $("[name = 'store']").val().trim().split("/");
        var department = $("[name = 'department']").val();
        var emp_type = $("[name = 'contract_type']").val();
        var supId = $("[name = 'supervisor']").val().trim().split("*");
        $("#loading-gif").show();

        $.ajax({
            type : "POST",
            url  : "functionquery.php?request=subordinatesList",
            data : { emp_type:emp_type, store:store[1], department:department, supId:supId[0] },
            success : function(data){
            
                $("#loading-gif").hide();
                $(".subordinates-list").html(data);
            }
        });
    }

    function saveSubordinates(){

        var supId = $("[name = 'supervisor']").val().trim().split("*");
        var chk = document.getElementsByName('chkEmpId[]');
           
        var newCHK = "";
        var chkNum = 0;
        for(var i = 0;i<chk.length;i++) {

          if(chk[i].checked == true) {

            chkNum++;
            newCHK += chk[i].value+"*";
          } 
        }

        if(chkNum == 0) {

            errDup("You need to check a subordinate to be added!");

        } else {

            $.ajax({
                type : "POST",
                url  : "functionquery.php?request=saveSubordinates",
                data : { supId:supId[0], newCHK:newCHK },
                success : function(data){

                    data = data.trim();
                    if (data == "Ok") {

                        $.alert.open({
                            type: 'warning',
                            title: 'Info',
                            icon: 'confirm',            
                            cancel: false,
                            content: "Subordinate(s) Successfully Added",
                            buttons:{
                                OK: 'Yes'
                            },
                            
                            callback: function(button) {
                                if (button == 'OK'){
                                        
                                    $("#addSubordinates").modal("hide");
                                    subordinates(supId[0]);
                                }           

                            }
                        });
                    } else {

                        alert(data);
                    }
                }
            });
        }
    }

    function subordinates(supId){

        $("#loading-gif").show();

        $.ajax({
            type : "POST",
            url  : "functionquery.php?request=subordinates",
            data : { empId:supId },
            success : function(data){

                $("#loading-gif").hide();
                $(".subordinates").html(data);
            } 
        });
    }

    function remove_sub(){

        var supId = $("[name = 'supervisor']").val().trim().split("*");
        var chk = document.getElementsByName('empId[]');
           
        var newCHK = "";
        var chkNum = 0;
        for(var i = 0;i<chk.length;i++) {

          if(chk[i].checked == true) {

            chkNum++;
            newCHK += chk[i].value+"*";
          } 
        }

        if (chkNum == 0) {

            errDup("You need to check a subordinate to remove!");
        } else  {
            $.ajax({
                type : "POST",
                url  : "functionquery.php?request=remove_sub",
                data : { newCHK:newCHK },
                success : function(data){

                    data = data.trim();
                    if (data == "Ok") {

                        $.alert.open({
                            type: 'warning',
                            title: 'Info',
                            icon: 'confirm',            
                            cancel: false,
                            content: "Subordinate(s) Successfully Removed",
                            buttons:{
                                OK: 'Yes'
                            },
                            
                            callback: function(button) {
                                if (button == 'OK'){

                                    subordinates(supId[0]);
                                }           

                            }
                        });
                    } else {

                        alert(data);
                    }
                } 
            });
        }

    }
</script>