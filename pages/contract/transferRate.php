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

        max-height: 320px;
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
                    <h3 class="box-title"><?php echo $subMenu; ?></h3>
                </div>

                <div class="box-body">
                    
                    <div class="row">
                        <div class="col-md-5">
                            <!-- <h3>Supervisor Details</h3> -->
                            <div class="form-group">
                                <label>Search Promo</label>
                                <div class="input-group">
                                    <input class="form-control" type="text" name="employee" onkeyup="nameSearch(this.value)" autocomplete="off">
                                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                </div>
                                <div class="search-results" style="display: none;"></div>
                            </div>
                            <div class="promoDetails"></div>
                        </div>
                        <div class="col-md-7">
                            <div class="transferForm">
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

        var empId = empId.split("*");

        $.ajax({
            type : "POST",
            url  : "functionquery.php?request=promoDetails",
            data : { empId:empId[0].trim() },
            success : function(data){

                $(".promoDetails").html(data);
            } 
        });

        transferForm(empId[0].trim());  
    }

    function transferForm(empId){

        $("#loading-gif").show();
        $.ajax({
            type : "POST",
            url  : "functionquery.php?request=transferForm",
            data : { empId:empId },
            success : function(data){

                $("#loading-gif").hide();
                $(".transferForm").html(data);
            } 
        }); 
    }

    function transfer(){

        var empId = $("[name = 'empId']").val();
        var recordNo = $("[name = 'recordNo']").val();
        var promoType = $("[name = 'promoType']").val();

        var store = "";
        var loop = $("[name = 'loop']").val();
        if (promoType == "STATION") {

            for (var i = 0; i <= loop; i++) {
                
                if ($("#chkS_"+i).is(':checked')) {

                    var store = $("#chkS_"+i).val();  
                } 
            }
        } else {

            for (var i = 0; i <= loop; i++) {
                
                if ($("#chk_"+i).is(':checked')) {

                    var tempStore = $("#chk_"+i).val(); 
                    store += tempStore+"||"; 
                } 
            }
        }

        if (store == "") {

            errDup("Choose one of the previous rate first!");
        } else {

            $(".loadingSave").html("Please Wait... <img src='../images/ajax.gif'>");
            $.ajax({
                type : "POST",
                url  : "functionquery.php?request=transferRate",
                data : { empId:empId, recordNo:recordNo, promoType:promoType, store:store },
                success : function(data){

                    $(".loadingSave").html("");

                    data = data.split("||");
                    if (data[0].trim() == "Ok") {

                        $.alert.open({
                            type: 'warning',
                            title: 'Info',
                            icon: 'confirm',            
                            cancel: false,
                            content: data[1].trim(),
                            buttons:{
                                OK: 'Yes'
                            },
                            
                            callback: function(button) {
                                if (button == 'OK'){
                                        
                                    transferForm(empId);  
                                }           

                            }
                        });
                    } else {

                        $.alert.open({
                            type: 'warning',
                            cancel: false,
                            content: data[1].trim(),
                            buttons:{
                                OK: 'Ok'
                            },

                            callback: function(button) {
                                if (button == 'OK'){

                                    if (company == "") {

                                        transferForm(empId);
                                    }
                                }           

                            }
                        });
                    }
                } 
            });
        }
    }

    function chk(loop,recordNo){
        
        if ($("#chk_"+loop).is(':checked')) {
         
            $(".chk_"+recordNo).prop("checked",true);
        } else {
           
            $(".chk_"+recordNo).prop("checked",false);
        }
    }

</script>