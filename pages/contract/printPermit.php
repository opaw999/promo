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

    .size-emp {

        max-height: 335px;
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
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Generate Permit</h3>
                </div>

                <div class="box-body">
                    <input type="hidden" name="permit">
                    <p><strong> Current Permit </strong></p>
                    <p><button class='btn btn-primary btn-sm' onclick="printPermit()"> Print Permit </button></p>
                    <i> Allows printing of permit of current contract. </i>
                    <hr>

                    <p><strong> Previous Permit </strong></p>
                    <p><button class='btn btn-primary btn-sm' onclick="previousPermit()"> Print Permit </button></td></p>
                    <i> Allows printing of permit from previous contract. </i>
                    <hr>
                </div>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>

</section>

<div id="previousPermit" class="modal fade">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header bg-light-blue color-palette">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Print Permit</h4>
            </div>
        <div class="modal-body">
            <div class="previousPermit"></div>
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

<div id="printPermit" class="modal fade">
    <div class="modal-dialog" style="width: 50%">
        <div class="modal-content">
            <div class="modal-header bg-light-blue color-palette">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Print Permit</h4>
            </div>
        <div class="modal-body">
            <div class="printPermit"></div>
        </div>
        <div class="modal-footer">
            <span class="loadingSave"></span>
            <button class="btn btn-primary" onclick="genPermit()"><i class="fa fa-file-pdf-o"></i> &nbsp;Generate Permit</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<script type="text/javascript">
    
    function printPermit(){

        $("#printPermit").modal({
            backdrop: 'static',
            keyboard: false
          });

        $("#printPermit").modal("show");
        $("input[name = 'permit']").val("current");

        $.ajax({
            type : "POST",
            url  : "functionquery.php?request=printPermit",
            success : function(data){
            
                $(".printPermit").html(data);  
            }
        });
    }

    function previousPermit(){

        $("#previousPermit").modal({
            backdrop: 'static',
            keyboard: false
          });

        $("#previousPermit").modal("show");
        $("[name = 'permit']").val("previous");
        $.ajax({
            type : "POST",
            url  : "functionquery.php?request=previousPermit",
            success : function(data){
            
                $(".previousPermit").html(data);  
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


    function getEmpId(empId) {

        $("[name='employee']").val(empId);
        $(".search-results").hide();
        $("[name = 'employee']").css("border-color","#ccc");

        empId = empId.split("*");

        var permit = $("[name = 'permit']").val();
        if(permit == "current") {

            $.ajax({
                type : "POST",
                url  : "functionquery.php?request=locateStore",
                data : { empId : empId[0].trim() },
                success : function(data){

                    $("[name = 'store']").html(data);
                } 
            });

            $.ajax({
                type : "POST",
                url  : "functionquery.php?request=getEmpRecordNo",
                data : { empId : empId[0].trim() },
                success : function(data){
                     
                    $("[name = 'recordNo']").val(data);
                } 
            });

            $.ajax({
                type : "POST",
                url  : "functionquery.php?request=getPromoType",
                data : { empId : empId[0].trim() },
                success : function(data){

                    data = data.split("||");
                    if (data[0].trim() == "Ok") {

                        if (data[1].trim() == "STATION") {

                            $("[name = 'dutyDays']").prop("disabled",false);
                            $("[name = 'dutyDays']").val("Daily");
                        } else {

                            $("[name = 'dutyDays']").prop("disabled",false);
                            $("[name = 'dutyDays']").val("");
                        }
                    }
                } 
            });

            enabledBtns();
        } else {

            viewPreviousContract(empId[0].trim());
        }
    }

    function printPreviousPermit(empId,recordNo){

        $("#printPermit").modal({
            backdrop: 'static',
            keyboard: false
          });

        $("#printPermit").modal("show");
        $("[name = 'permit']").val("previous");

        $.ajax({
            type : "POST",
            url  : "functionquery.php?request=printPreviousPermit",
            data : { empId:empId, recordNo:recordNo },
            success : function(data){
            
                $(".printPermit").html(data);  
            }
        });

        $.ajax({
            type : "POST",
            url  : "functionquery.php?request=locateStore",
            data : { empId : empId },
            success : function(data){

                $("[name = 'store']").html(data);
            } 
        });

        $.ajax({
            type : "POST",
            url  : "functionquery.php?request=getPromoType",
            data : { empId : empId },
            success : function(data){

                data = data.split("||");
                if (data[0].trim() == "Ok") {

                    if (data[1].trim() == "STATION") {

                        // $("[name = 'dutyDays']").prop("disabled",false);
                        $("[name = 'dutyDays']").val("Daily");
                    } else {

                        $("[name = 'dutyDays']").prop("disabled",false);
                        $("[name = 'dutyDays']").val("");
                    }
                }
            } 
        });
    }


    function enabledBtns(){

        $("[name = 'store']").prop("disabled",false);
        // $("[name = 'dutySched']").prop("disabled",false);
        // $("[name = 'specialSched']").prop("disabled",false);
        $(".selects2").prop("disabled",false);
        $("[name = 'dayOff']").prop("disabled",false);
        $("[name = 'cutOff']").prop("disabled",false);
    }

    function viewPreviousContract(empId){

        $.ajax({
            type : "POST",
            url  : "functionquery.php?request=viewPreviousContract",
            data : { empId:empId },
            success : function(data){

                $(".previousContract").html(data);
            } 
        });
    }

    function genPermit(){

        var empId = $("[name = 'employee']").val().split("*");
        var recordNo = $("[name = 'recordNo']").val();
        var store = $("[name = 'store']").val();
        var dutyDays = $("[name = 'dutyDays']").val();
        var dutySched = $("[name = 'dutySched']").val();
        var specialSched = $("[name = 'specialSched']").val();
        var specialDays = $("[name = 'specialDays']").val();
        var dayOff = $("[name = 'dayOff']").val();
        var cutOff = $("[name = 'cutOff']").val();

        var permit = $("[name = 'permit']").val();
        if(permit == "current"){
            
            var table1      = "employee3";
            var table2      = "promo_record";
        } else {
            
            var table1      = "employmentrecord_";
            var table2      = "promo_history_record";
        }

        if (empId[0].trim() == "" || store == "" || dutySched == "" || dutyDays == "" || dayOff == "" || (specialSched != "" && specialDays == "")) {

            $.alert.open({
                type: 'warning',
                cancel: false,
                content: "Please Fill-up Required Fields!",
                buttons:{
                    OK: 'Ok'
                },

                callback: function(button) {
                    if (button == 'OK'){

                        if (empId[0].trim() == "") {

                            $("[name = 'employee']").css("border-color","#dd4b39");
                        }

                        if (store == "") {

                            $("[name = 'store']").css("border-color","#dd4b39");
                        }

                        if (dayOff == "") {

                            $("[name = 'dayOff']").css("border-color","#dd4b39");
                        }

                        if (dutySched == "") {

                            $(".dutySched").css("border-color","#dd4b39");
                        }

                        if (specialSched != "" && specialDays == "") {

                            $("[name = 'specialDays']").css("border-color","#dd4b39");
                        }

                        if (dutyDays == "") {

                            $("[name = 'dutyDays']").css("border-color","#dd4b39");
                        }
                    }           

                }
            });
        } else {

            dutyDays = dutyDays.replace(/ &/g, ",");
            specialDays = specialDays.replace(/ &/g, ",");

            if(permit == "current"){
            
                $.ajax({
                    type : "POST",
                    url  : "functionquery.php?request=saveDutyDetails",
                    data : { empId:empId[0].trim(), recordNo:recordNo, store:store, dutyDays:dutyDays, dutySched:dutySched, specialSched:specialSched, specialDays:specialDays, dayOff:dayOff, cutOff:cutOff },
                    success : function(data){

                        if (data == "success") {

                            window.open("../report/promo_permit_towork.php?recordNo="+recordNo+"&empId="+empId[0].trim()+"&store="+store+"&dutySched="+dutySched+"&specialSched="+specialSched+"&dutyDays="+dutyDays+"&specialDays="+specialDays+"&dayoff="+dayOff+"&table1="+table1+"&table2="+table2);
                        } else {
                            alert(data);
                        }
                    }
                });
            } else {

                window.open("../report/promo_permit_towork.php?recordNo="+recordNo+"&empId="+empId[0].trim()+"&store="+store+"&dutySched="+dutySched+"&specialSched="+specialSched+"&dutyDays="+dutyDays+"&specialDays="+specialDays+"&dayoff="+dayOff+"&table1="+table1+"&table2="+table2);
            }

        }
    }

    function inputSpecialDays(specialSched){

        if (specialSched == "") {

            $("[name = 'specialDays']").val("");
        } else {
            
            $("[name = 'specialDays']").prop("disabled",false);
        }
    }

    function inputDutySched(){

        $("select.dutySched").css("border-color","#ccc");
    }
</script>