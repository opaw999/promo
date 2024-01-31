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
        <div class="col-md-7">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><?php echo $subMenu; ?></h3>
                </div>

                <div class="box-body">
                    <div class="form-group">
                        <label>Business Unit</label>
                        <select name="store" class="form-control" onchange="locateDept(this.value)">
                            <option value=""> All Business Unit </option>
                            <?php 

                                $query = mysql_query("SELECT bunit_id, bunit_name, bunit_field FROM `locate_promo_business_unit`  WHERE hrd_location = '$hrCode' AND status = 'active'")or die(mysql_error());
                                while ($row = mysql_fetch_array($query)) {
                                    
                                    echo "<option value='".$row['bunit_id']."/".$row['bunit_field']."'>".$row['bunit_name']."</option>";
                                }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Department</label>
                        <select name="department" class="form-control">
                            <option value=""> --Select-- </option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Company</label>
                        <select class="form-control select2" name="company" style="width: 100%;">
                            <option value=""> --Select-- </option>
                            <?php 

                                $query = mysql_query("SELECT pc_code, pc_name FROM `locate_promo_company`")or die(mysql_error());
                                while ($row = mysql_fetch_array($query)) {
                                    
                                    echo "<option value='".$row['pc_code']."'>".$row['pc_name']."</option>";
                                }
                            ?>
                        </select>
                    </div>
                    <div class="form-group"> <i class="text-red">*</i>
                        <label>Current Status</label>
                        <select class="form-control" name="currentStatus" style="width: 100%;" onchange="inputField(this.name)">
                            <option value=""> --Select-- </option>
                            <option value="Active">Active</option>
                            <option value="End of Contract">End of Contract</option>
                            <option value="Resigned">Resigned</option>
                            <option value="blacklisted">Blacklisted</option>
                        </select>
                    </div>
                    <div class="form-group"> <i class="text-red">*</i>
                        <label>Date From</label>
                        <input type="text" name="dateFrom" class="form-control datepicker" placeholder="mm/dd/yyyy" onchange="dateFrom(this.name)">
                    </div>
                    <div class="form-group"> <i class="text-red">*</i>
                        <label>Date To</label>
                        <input type="text" name="dateTo" class="form-control datepicker" placeholder="mm/dd/yyyy" onchange="dateTo(this.name)">
                    </div>
                </div>
                <div class="box-footer">
                    <button type="button" class="btn btn-primary" onclick="genReport('excel')"> Generate in Excel <img src="../images/icons/excel-xls-icon.png"></button>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>

</section>
<script type="text/javascript">
    
    function locateDept(id){

        $("[name = 'department']").css("border-color","#ccc");
        $.ajax({
            type : "POST",
            url  : "functionquery.php?request=locateDepartment",
            data : { id:id },
            success : function(data){
            
                $("[name = 'department']").html(data);  
            }
        }); 
    }

    function dateFrom(name){

        $("[name = '"+name+"']").css("border-color","#ccc");
        $("[name = 'dateTo']").val("");
    }

    function dateTo(name){

        var dF = $("[name = 'dateFrom']").val();
        var dT = $("[name = '"+name+"']").val();
        $("[name = '"+name+"']").css("border-color","#ccc");

        if (dF == "") {

            $.alert.open({
                type: 'warning',
                cancel: false,
                content: "Please Fill-up Date From First!",
                buttons:{
                    OK: 'Ok'
                },

                callback: function(button) {
                    if (button == 'OK'){

                        $("[name = '"+name+"']").val("");
                    }
                }
            });
        } else {

            if (new Date(dF) >= new Date(dT)) { 
                
                errDup("Date To must be lesser than Date From!");
                $("[name = '"+name+"']").val("");
            }
        }
    }

    function genReport(code){

        var store = $("[name = 'store']").val();
        var department = $("[name = 'department']").val();
        var company = $("[name = 'company']").val();
        var currentStatus = $("[name = 'currentStatus']").val();
        var dateFrom = $("[name = 'dateFrom']").val();
        var dateTo = $("[name = 'dateTo']").val();

        if (currentStatus == "" || dateFrom == "" || dateTo == "") {

            $.alert.open({
                type: 'warning',
                cancel: false,
                content: "Please Fill-up Required Fields!",
                buttons:{
                    OK: 'Ok'
                },

                callback: function(button) {
                    if (button == 'OK'){

                        if (currentStatus == "") {

                            $("[name = 'currentStatus']").css("border-color","#dd4b39");
                        }

                        if (dateFrom == "") {

                            $("[name = 'dateFrom']").css("border-color","#dd4b39");
                        }

                        if (dateTo == "") {

                            $("[name = 'dateTo']").css("border-color","#dd4b39");
                        }
                    }           

                }
            });

        } else {

            $.alert.open({
                type: 'warning',
                cancel: false,
                content: "Generate report now?",
                buttons:{
                    OK: 'Yes',
                    NO: 'Not now'
                },

                callback: function(button) {
                    if (button == 'OK'){
                            
                        if (code == "excel") {

                            window.open("pages/reports/annualStatusReport_xls.php?store="+store+"&&department="+department+"&&company="+company+"&&status="+currentStatus+"&&dateFrom="+dateFrom+"&&dateTo="+dateTo);
                        } 
                    }           

                }
            });
        }
    }

</script>