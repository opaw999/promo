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

    .witness1{

        box-shadow: 5px 5px 5px #ccc; 
        margin-top: 1px; 
        margin-left : 0px; 
        background-color: #F1F1F1;
        width : 90%;
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

    .witness2{

        box-shadow: 5px 5px 5px #ccc; 
        margin-top: 1px; 
        margin-left : 0px; 
        background-color: #F1F1F1;
        width : 90%;
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
                            <div class="editContractForm">
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

        editContractForm(empId[0].trim());
    }

    function searchWitness1(key){
        
        var str = key.trim();
        $(".witness1").hide();
        if(str == '') {
            $(".witness1-loading").slideUp(100);
        }
        else {
            $.ajax({
                type : "POST",
                url  : "functionquery.php?request=findWitness1",
                data : { str : str },
                success : function(data){

                    data = data.trim();
                    if(data != ""){
                        $(".witness1").show().html(data);
                    } else {

                        $(".witness1").hide();
                    }
                } 
            });
        }
    }

    function getWitness1(empId){

        $("[name='witness1']").val(empId);
        $(".witness1").hide();
        $("[name = 'witness1']").css("border-color","#ccc");

    }

    function searchWitness2(key){
        
        var str = key.trim();
        $(".witness2").hide();
        if(str == '') {
            $(".witness2-loading").slideUp(100);
        }
        else {
            $.ajax({
                type : "POST",
                url  : "functionquery.php?request=findWitness2",
                data : { str : str },
                success : function(data){

                    data = data.trim();
                    if(data != ""){
                        $(".witness2").show().html(data);
                    } else {

                        $(".witness2").hide();
                    }
                } 
            });
        }
    }

    function getWitness2(empId){

        $("[name='witness2']").val(empId);
        $(".witness2").hide();
        $("[name = 'witness2']").css("border-color","#ccc");

    }

    function editContractForm(empId){

        $.ajax({
            type : "POST",
            url  : "functionquery.php?request=editContractForm",
            data : { empId:empId },
            success : function(data){

                $(".editContractForm").html(data);
            } 
        });
    }

    function sssctc(type){

        if (type == "ctc") {

            $("[name = 'cedula']").show();
            $(".issuedOn").show();
            $(".issuedAt").show();

            $("[name = 'sss']").hide();
        } else {

            $("[name = 'cedula']").hide();
            $(".issuedOn").hide();

            $(".issuedAt").show();
            $("[name = 'sss']").show();
        }
    }

    function genContract(){

        var empId = $("[name = 'empId']").val();
        var recordNo = $("[name = 'recordNo']").val();
        var contract = $("[name = 'contract']").val();
        var witness1 = $("[name = 'witness1']").val();
        var witness2 = $("[name = 'witness2']").val();
        var contractHeader = $("[name = 'contractHeader']").val();
        var contractDate = $("[name = 'contractDate']").val();
        var clear = $("[name = 'clear']").val();

        var clear1 = "";
        var clear2 = "";

        if (contract == "current") {
            
            var table1 = "employee3";
            var table2 = "promo_record";
        } else {

            var table1 = "employmentrecord_";
            var table2 = "promo_history_record";
        }

        if ($("#clear1").is(':checked')) {

            clear1 = "true"; 
        } 

        if ($("#clear2").is(':checked')) {

            clear2 = "true"; 
        } 

        var issuedOn = "";
        var sss = "";
        var cedula = "";
        if(clear1 == "true"){

            cedula = $("[name = 'cedula']").val();
            issuedOn = $("[name = 'issuedOn']").val();
            var issuedAt = $("[name = 'issuedAt']").val();
        } else {

            sss = $("[name = 'sss']").val();
            var issuedAt = $("[name = 'issuedAt']").val();
        }

        if(witness1 == "" || witness2 == "" || (clear1 == "" && clear2 == "") || contractHeader == "" || contractDate == ""){

            $.alert.open({
                type: 'warning',
                cancel: false,
                content: "Please Fill-up Required Fields!",
                buttons:{
                    OK: 'Ok'
                },

                callback: function(button) {
                    if (button == 'OK'){

                        if (witness1 == "") {

                            $("[name = 'witness1']").css("border-color","#dd4b39");
                        }

                        if (witness2 == "") {

                            $("[name = 'witness2']").css("border-color","#dd4b39");
                        }

                        if (contractHeader == "") {

                            $("[name = 'contractHeader']").css("border-color","#dd4b39");
                        }

                        if (contractDate == "") {

                            $("[name = 'contractDate']").css("border-color","#dd4b39");
                        }
                    }           

                }
            });
        } else {

            var chk = "false";
            if (clear1 == "true") {


                if (cedula == "" || issuedOn == "" || issuedAt == "") {

                    chk = "true";
                    $.alert.open({
                        type: 'warning',
                        cancel: false,
                        content: "Please Fill-up Required Fields!",
                        buttons:{
                            OK: 'Ok'
                        },

                        callback: function(button) {
                            if (button == 'OK'){

                                if (cedula == "") {

                                    $("[name = 'cedula']").css("border-color","#dd4b39");
                                }

                                if (issuedOn == "") {

                                    $("[name = 'issuedOn']").css("border-color","#dd4b39");
                                }

                                if (issuedAt == "") {

                                    $("[name = 'issuedAt']").css("border-color","#dd4b39");
                                }
                            }           

                        }
                    });
                }
            } 

            if (clear2 == "true") {

                if (sss == "" || issuedAt == "") {

                    chk = "true";
                    $.alert.open({
                        type: 'warning',
                        cancel: false,
                        content: "Please Fill-up Required Fields!",
                        buttons:{
                            OK: 'Ok'
                        },

                        callback: function(button) {
                            if (button == 'OK'){

                                if (sss == "") {

                                    $("[name = 'sss']").css("border-color","#dd4b39");
                                }

                                if (issuedAt == "") {

                                    $("[name = 'issuedAt']").css("border-color","#dd4b39");
                                }
                            }           

                        }
                    });
                }
            }

            if (chk == "false") {

                $.ajax({
                    type : "POST",
                    url  : "functionquery.php?request=submitContract",
                    data : { empId:empId, recordNo:recordNo, witness1:witness1, witness2:witness2, contractHeader:contractHeader, contractDate:contractDate, cedula:cedula, sss:sss, issuedOn:issuedOn, issuedAt:issuedAt, clear1:clear1, clear2:clear2 },
                    success : function(data){

                        $.alert.open({
                            type: 'warning',
                            title: 'Info',
                            icon: 'confirm',            
                            cancel: false,
                            content: "Contract Successfully Saved",
                            buttons:{
                                OK: 'Yes'
                            },
                            
                            callback: function(button) {
                                if (button == 'OK'){
                                    
                                    editContractForm(empId);  
                                    window.open("../report/promo_contract.php?empId="+empId+"&&recordNo="+recordNo+"&&table1="+table1+"&&table2="+table2);
                                }           

                            }
                        });
                    } 
                });
            }
        }
    }

</script>