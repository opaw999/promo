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

<section class="content">

    <!-- Default box -->
    <div class="box box-primary">
        <div class="box-header with-border">
          	<h3 class="box-title">Blacklisted Employees</h3>
        </div>

        <div class="box-body">
          	<table id="blacklists" class="table table-striped table-hover table1">
                <thead>
                	<tr>
                		<th>Emp.ID</th>
                		<th>Name</th>
                		<th>ReportedBy</th>
                		<th>BlacklistDate</th>
                		<th>Reason</th>
                		<th>Action</th>
                	</tr>
                </thead>
                <tfoot>
                	<tr>
                		<th>Emp.ID</th>
                		<th>Name</th>
                		<th>ReportedBy</th>
                		<th>BlacklistDate</th>
                		<th>Reason</th>
                		<th>Action</th>
                	</tr>
                </tfoot>
            </table>
        </div>

        <!-- /.box-body -->
    </div>
   	<!-- /.box -->

</section>

<div id="editBlacklist" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light-blue color-palette">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Edit Blacklisted Employee</h4>
            </div>
        <div class="modal-body">
            <div class="editBlacklist"></div>
        </div>
        <div class="modal-footer">
            <span class="loadingSave"></span>
            <button type="button" class="btn btn-primary" onclick="updateBlacklist()">Update</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<script type="text/javascript">
    
    function editBlacklist(blacklistNo){

        $("#editBlacklist").modal({
            backdrop: 'static',
            keyboard: false
          });

        $("#editBlacklist").modal("show");

        $.ajax({
            type : "POST",
            url  : "functionquery.php?request=editBlacklist",
            data : { blacklistNo:blacklistNo },
            success : function(data){
            
                $(".editBlacklist").html(data);  
            }
        });
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

    function updateBlacklist(){

        var blacklistNo = $("[name = 'blacklistNo']").val();
        var reason      = $("[name = 'reason']").val().trim();
        var dateBlacklisted = $("[name = 'dateBlacklisted']").val();
        var birthday = $("[name = 'birthday']").val();
        var reportedBy = $("[name = 'reportedBy']").val().trim().split("*");
        var address = $("[name = 'address']").val();
        
        if (reason == "" || dateBlacklisted == "" || reportedBy[0] == "") {
            
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

                            if (reportedBy[0] == "") {

                                $("[name = 'reportedBy']").css("border-color","#dd4b39");
                            }

                        }           

                    }
            });
        } else {

            $.ajax({
                type : "POST",
                url  : "functionquery.php?request=updateBlacklist",
                data : { blacklistNo:blacklistNo, reason:reason, dateBlacklisted:dateBlacklisted, birthday:birthday, reportedBy:reportedBy[0], address:address },
                success : function(data){

                    data = data.trim();
                    if(data == "Ok"){
                        
                        var loc = document.location;
                        $.alert.open({
                            type: 'warning',
                            title: 'Info',
                            icon: 'confirm',            
                            cancel: false,
                            content: "Successfully Updated",
                            buttons:{
                                OK: 'Yes'
                            },
                            
                            callback: function(button) {
                                if (button == 'OK'){
                                        
                                    window.location = loc;
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