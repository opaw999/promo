<style type="text/css">
    
    .search-results{

        box-shadow: 5px 5px 5px #ccc; 
        margin-top: 1px; 
        margin-left : 0px; 
        background-color: #F1F1F1;
        width : 85%;
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

<?php 

    if (!empty($_GET['empId'])) {
        
        $empId = $_GET['empId'];
    } else {

        $empId = "";
    }
?>

<section class="content">

    <!-- Default box -->
    <div class="row">
        <div class="col-md-8">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Add New Blacklist Entry</h3>
                </div>

                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">

                            <input type="hidden" name="appId" value="<?php if($empId != ""): echo $empId; endif; ?>">
                            <input type="hidden" name="appName" value="<?php if($empId != ""): echo $nq->getEmpName($empId); endif; ?>">
                            <div class="form-group">
                                <label>Employee</label> <i class="text-red">*</i>
                                <div class="input-group">
                                    <input class="form-control" type="text" name="employee" disabled="" value="<?php if($empId != ""): echo $empId.' * '.$nq->getEmpName($empId); endif; ?>">
                                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary" onclick="browse()">Browse</button>
                                <i>Click Browse</i>
                            </div>
                            <div class="form-group">
                                <label>Reason</label> <i class="text-red">*</i>
                                <textarea name="reason" class="form-control inputForm" rows="4" onkeyup="inputField(this.name)" <?php if($empId == ""): echo "disabled=''"; endif; ?>></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Date Blacklisted</label> <i class="text-red">*</i>
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" name="dateBlacklisted" class="form-control inputForm pull-right datepicker" onchange="inputField(this.name)" value="<?php echo date('m/d/Y'); ?>" <?php if($empId == ""): echo "disabled=''"; endif; ?>>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Birthday</label>
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" name="birthday" class="form-control inputForm pull-right datepicker" <?php if($empId == ""): echo "disabled=''"; endif; ?>>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Reported By</label> <i class="text-red">*</i>
                                <div class="input-group">
                                    <input class="form-control inputForm" type="text" name="reportedBy" onkeyup="nameSearch(this.value)" <?php if($empId == ""): echo "disabled=''"; endif; ?> autocomplete="off">
                                    <span class="input-group-addon"><i class="fa fa-child"></i></span>
                                </div>
                                <div class="search-results" style="display: none;"></div>
                            </div>
                            <div class="form-group">
                                <label>Address</label>
                                <input type="text" class="form-control inputForm" name="address" <?php if($empId == ""): echo "disabled=''"; endif; ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <div class="pull-right">
                        <button type="button" class="btn btn-primary btnOption" <?php if($empId == ""): echo "disabled=''"; endif; ?> onclick="submitFields()"><i class="fa fa-send"></i> Submit</button>
                        <button type="button" class="btn btn-default btnOption" disabled="" onclick="resetFields()""><i class="fa fa-refresh"></i> Reset</button>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>
    

</section>

<div id="addBlacklistForm" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light-blue color-palette">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Browse</h4>
            </div>
        <div class="modal-body">
            <div class="addBlacklistForm"></div>
        </div>
        <div class="modal-footer">
            <span class="loadingSave"></span>
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<script type="text/javascript">
    
    function browse(){

        $("#addBlacklistForm").modal({
            backdrop: 'static',
            keyboard: false
          });

        $("#addBlacklistForm").modal("show");

        $.ajax({
            type : "POST",
            url  : "functionquery.php?request=addBlacklistForm",
            success : function(data){
            
                $(".addBlacklistForm").html(data);  
            }
        });
    }

    function browseNames(){

        var lastname = $("[name = 'lastname'").val().trim();
        var firstname = $("[name = 'firstname'").val().trim();

        if (lastname == "" && firstname == "") {

            errDup("Please indicate either the employee lastname or firstname to be searched.");
        } else {

            $("#loading-gif").show();
            $('#resultBrowse').html("");

            $.ajax({
                type : "POST",
                url  : "functionquery.php?request=browseNames",
                data : { lastname:lastname, firstname:firstname },
                success : function(data){
                    
                    data = data.trim();
                    if (data == "No Result Found") {

                        $("#nonApp").show();
                        $("#loading-gif").hide();
                    } else {
                        
                        $("#nonApp").hide();
                        $("#loading-gif").hide();
                        $("#resultBrowse").html(data);  
                    }
                }
            });
        }
    }

    function choose(id){

        var fullname = $("#appName_"+id).val();
        $("[name = 'employee']").val(id+" * "+fullname);
        $("[name = 'appName']").val(fullname);
        $("[name = 'appId']").val(id);

        $("#addBlacklistForm").modal("hide");
        enabledFields();
    }

    function enabledFields(){

        $("[name = 'reason']").prop("disabled",false);
        $("[name = 'dateBlacklisted']").prop("disabled",false);
        $("[name = 'birthday']").prop("disabled",false);
        $("[name = 'reportedBy']").prop("disabled",false);
        $("[name = 'address']").prop("disabled",false);
        $(".btnOption").prop("disabled",false);
    }

    function resetFields(){

        // disabled
        $("[name = 'reason']").prop("disabled",true);
        $("[name = 'dateBlacklisted']").prop("disabled",true);
        $("[name = 'birthday']").prop("disabled",true);
        $("[name = 'reportedBy']").prop("disabled",true);
        $("[name = 'address']").prop("disabled",true);
        $(".btnOption").prop("disabled",true);

        // empty fields
        $("[name = 'reason']").val("");
        $("[name = 'dateBlacklisted']").val("");
        $("[name = 'birthday']").val("");
        $("[name = 'reportedBy']").val("");
        $("[name = 'address']").val("");
        $("[name = 'employee']").val("");
        $("[name = 'appId']").val("");
        $("[name = 'appName']").val("");
    }

    function chooseToBlacklist(){
       
        var fname = $("[name = 'fname']").val();
        var lname = $("[name = 'lname']").val();
        var mname = $("[name = 'mname']").val();

        var fullname = lname+", "+fname+" "+mname;
        $("[name = 'employee']").val(fullname);
        $("[name = 'appName']").val(fullname);

        $("#addBlacklistForm").modal("hide");
        enabledFields();
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
                url  : "functionquery.php?request=findHrStaff",
                data : { str : str },
                success : function(data){

                    if(data){
                        $(".search-results").show().html(data);
                    }
                } 
            });
        }
    }

    function getEmpId(supId){

        $("[name='reportedBy']").val(supId);
        $(".search-results").hide();
        $("[name = 'reportedBy']").css("border-color","#ccc");
    }

    function submitFields(){

        var appId = $("[name = 'appId']").val();
        var appName = $("[name = 'appName']").val().trim();
        var reason = $("[name = 'reason']").val().trim();
        var dateBlacklisted = $("[name = 'dateBlacklisted']").val();
        var birthday = $("[name = 'birthday']").val();
        var reportedBy = $("[name = 'reportedBy']").val().split("*");
        var address = $("[name = 'address']").val();
        
        if (reason == "" || dateBlacklisted == "" || reportedBy[1].trim() == "") {
            
            $.alert.open({
                type: 'warning',
                cancel: false,
                content: "Please Fill-up Required Fields!",
                buttons:{
                    OK: 'Ok'
                },
                    callback: function(button) {
                        if (button == 'OK'){

                            if (reason == "") {

                                $("[name = 'reason']").css("border-color","#dd4b39");
                            }

                            if (dateBlacklisted == "") {

                                $("[name = 'dateBlacklisted']").css("border-color","#dd4b39");
                            }

                            if (reportedBy[1].trim() == "") {

                                $("[name = 'reportedBy']").css("border-color","#dd4b39");
                            }

                        }           

                    }
            });
        } else {

            $.ajax({
                type : "POST",
                url  : "functionquery.php?request=submitBlacklist",
                data : { appId:appId, appName:appName, reason:reason, dateBlacklisted:dateBlacklisted, birthday:birthday, reportedBy:reportedBy[1].trim(), address:address },
                success : function(data){

                    data = data.trim();
                    if(data == "Ok"){
                        
                        $.alert.open({
                            type: 'warning',
                            title: 'Info',
                            icon: 'confirm',            
                            cancel: false,
                            content: "Blacklist Successfully Saved!",
                            buttons:{
                                OK: 'Yes'
                            },
                            
                            callback: function(button) {
                                if (button == 'OK'){
                                        
                                    window.location = "?p=addBlacklist&&module=Blacklisted";
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