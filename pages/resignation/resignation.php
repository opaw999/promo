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
        <div class="col-md-7">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Add New Resignation/Termination Entry</h3>
                </div>
                <form action="" id="dataRT" method="post" enctype="multipart/form-data">

                    <input type="hidden" name="clearanceName">
                    <div class="box-body">
                        <div class="form-group">
                            <label>Search Promo</label> <i class="text-red">*</i>
                            <div class="input-group">
                                <input class="form-control" type="text" name="employee" autocomplete="off" onkeyup="nameSearch(this.value)" value="<?php if($empId != ""): echo $empId.' * '.$nq->getEmpName($empId); endif; ?>">
                                <span class="input-group-addon"><i class="fa fa-male"></i></span>
                            </div>
                            <div class="search-results" style="display: none;"></div>
                        </div>
                        <div class="form-group">
                            <label>Date</label> <i class="text-red">*</i>
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" name="dateEffective" class="form-control pull-right datepicker" onchange="inputField(this.name)" value="<?php echo date('m/d/Y'); ?>" <?php if($empId == ""): echo "disabled=''"; endif; ?>>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Remarks</label> <i class="text-red">*</i>
                            <textarea name="remarks" class="form-control" rows="3" <?php if($empId == ""): echo "disabled=''"; endif; ?> onkeyup="inputField(this.name)"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Status</label> <i class="text-red">*</i>
                            <select name="status" class="form-control" <?php if($empId == ""): echo "disabled=''"; endif; ?> onchange="chkReqs(this.value)">
                                <option value=""> --Select-- </option>
                                <option value="End of Contract">End of Contract</option>
                                <option value="Resigned">Resigned</option>
                            </select>
                        </div>
                        <div class="uploadResignation"></div>
                    </div>
                    <div class="box-footer">
                        <div class="pull-right">
                            <input type="submit" class="btn btn-primary" value="Submit">
                        </div>
                    </div>
                </form>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>

</section>
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
                url  : "functionquery.php?request=findAllPromo",
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

        disabledButtons();

        var empId = empId.trim().split("*");
        $.ajax({
            type : "POST",
            url  : "functionquery.php?request=checkPromoStatus",
            data : { empId:empId[0] },
            success : function(data){

                data = data.trim();

                if (data == "Active" || data == "End of Contract") {

                    $.alert.open({
                        type: 'warning',
                        title: 'Info',
                        icon: 'confirm',            
                        cancel: false,
                        content: "Employee status is "+data,
                        buttons:{
                            OK: 'Yes'
                        },
                        
                        callback: function(button) {
                            if (button == 'OK'){
                                    
                                enabledButtons();
                            }           

                        }
                    });
                } else {

                    errDup("Employee status is "+data);
                }
            } 
        });
    }

    function enabledButtons(){

        $("[name = 'dateEffective']").prop("disabled",false);
        $("[name = 'remarks']").prop("disabled",false);
        $("[name = 'status']").prop("disabled",false);
    }


    function disabledButtons(){

        $("[name = 'dateEffective']").prop("disabled",true);
        $("[name = 'remarks']").prop("disabled",true);
        $("[name = 'status']").prop("disabled",true);

        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth()+1; //January is 0!

        var yyyy = today.getFullYear();
        if(dd<10){dd='0'+dd} if(mm<10){mm='0'+mm} today = mm+'/'+dd+'/'+yyyy;
        // $("[name = 'dateEffective']").attr('value', today);
        $("[name = 'dateEffective']").val(today);
        $("[name = 'remarks']").val("");
        $("[name = 'status']").val("");

        $(".uploadResignation").html("");
    }

    function chkReqs(status){

        if(status == ""){
            
            $(".uploadResignation").html("");
        } else {
            $("[name = 'status']").css("border-color","#ccc");
            var empId = $("[name='employee']").val().split("*");
           
            $.ajax({
                type : "POST",
                url  : "functionquery.php?request=chkReqsRT",
                data : { empId:empId[0].trim(), status:status },
                success : function(data){

                    if(data){
                        $(".uploadResignation").html(data);
                    }
                } 
            });
        }
    }

    function inputClearance(name){

        $("[name = '"+name+"']").css("border-color","#ccc");

        var clearanceName = "";
        var clearanceValue = "";
        var clearanceMsg = "";
        var loop = $("[name = 'loop']").val();
    
        for (var x = 1; x <= loop; x++) {
         
            var clearance = $("#clearance_"+x).attr('name');
            var clearanceVal = $("[name = '"+clearance+"']").val();
    
            clearanceName += clearance+"||";
            clearanceValue  += clearanceVal+"||";
    
            if (clearanceVal == "") {
    
                clearanceMsg = "true";
            }
        }

        $("[name = 'clearanceName']").val(clearanceName);
    }
</script>