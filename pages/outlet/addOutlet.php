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
                            <div class="addOutletForm">
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
                url  : "functionquery.php?request=findActivePromo",
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

        $("#loading-gif").show();
        $.ajax({
            type : "POST",
            url  : "functionquery.php?request=addOutletForm",
            data : { empId:empId[0].trim() },
            success : function(data){

                $("#loading-gif").hide();
                $(".addOutletForm").html(data);
            } 
        });  
    }

    function submitFields(){

        var empId = $("[name = 'employee']").val().split("*");
        var prevStore = $("[name = 'prevStore']").val();
        var storeNum = $("[name = 'storeNum']").val();
        var effectiveOn = $("[name = 'effectiveOn']").val();
        var remarks = $("[name = 'remarks']").val().trim();
       
        // current store
        var counter = $("[name = 'counter']").val();
        var chkNum = 0;
        var store = "";

        for (var i = 1;  i <= counter; i++) {
            
            if ($("#check2_"+i).is(':checked')) {

                chkNum++;
                var bunit_id = $("#check2_"+i).val();
                store += bunit_id+"||";
            }
        }

        if (chkNum <= storeNum || effectiveOn == "") {

            if(effectiveOn == ""){
                
                $.alert.open({
                    type: 'warning',
                    cancel: false,
                    content: "Please Fill-up Required Fields!",
                    buttons:{
                        OK: 'Ok'
                    },

                    callback: function(button) {
                        if (button == 'OK'){

                            if (effectiveOn == "") {

                                $("[name = 'effectiveOn']").css("border-color","#dd4b39");
                            }
                        }           

                    }
                });
            } else {

                errDup("Please Add Another Store for Setup!");
            }


        } else {
           
            $.ajax({
                type : "POST",
                url  : "functionquery.php?request=submitAddOutlet",
                data : { empId:empId[0].trim(), store:store, prevStore:prevStore, effectiveOn:effectiveOn, remarks:remarks },
                success : function(data){

                    data = data.trim();
                    if (data == "Ok") {

                        var loc = document.location;
                        $.alert.open({
                            type: 'warning',
                            title: 'Info',
                            icon: 'confirm',            
                            cancel: false,
                            content: "<b>New Outlet Successfully Added!</b> <br><br> Please inform the promo to proceed to the newly assigned store for futher instructions.",
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