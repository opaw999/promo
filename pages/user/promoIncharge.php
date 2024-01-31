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
                    <h3 class="box-title">Add Promo Incharge Account</h3>
                </div>

                <div class="box-body">
                    <div class="form-group">
                        <label>HR Staff</label> <i class="text-red">*</i>
                        <div class="input-group">
                            <input class="form-control" type="text" name="employee" onkeyup="nameSearch(this.value)" autocomplete="off">
                            <span class="input-group-addon"><i class="fa fa-user"></i></span>
                        </div>
                        <div class="search-results" style="display: none;"></div>
                    </div>
                    <div class="form-group">
                        <label>User Type</label> <i class="text-red">*</i>
                        <select name="usertype" class="form-control" onchange="inputField(this.name)">
                            <option value=""></option>
                            <option value="promo1">Promo Incharge</option>
                            <option value="promo2">Encoder</option>
                        </select>
                    </div>
                </div>
                <div class="box-footer">
                    <div class="pull-right">
                        <button type="button" class="btn btn-primary" onclick="submitFields()"><i class="fa fa-send"></i> Submit</button>
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

    function getEmpId(empId){

        $("[name='employee']").val(empId);
        $(".search-results").hide();
        $("[name = 'employee']").css("border-color","#ccc");
    }

    function submitFields(){

        var empId = $("[name='employee']").val().trim().split("*");
        var usertype = $("[name = 'usertype']").val();

        if (empId[0] == "" || usertype == "") {

            $.alert.open({
                type: 'warning',
                cancel: false,
                content: "Please Fill-up Required Fields!",
                buttons:{
                    OK: 'Ok'
                },

                callback: function(button) {
                    if (button == 'OK'){

                        if (empId[0] == "") {

                            $("[name = 'employee']").css("border-color","#dd4b39");
                        }

                        if (usertype == "") {

                            $("[name = 'usertype']").css("border-color","#dd4b39");
                        }
                    }           

                }
            });
        } else {

            $.ajax({
                type : "POST",
                url  : "functionquery.php?request=submitPromoIncharge",
                data : { empId:empId[0], usertype:usertype },
                success : function(data){

                    data =  data.trim();
                    if(data == "Ok"){
                        
                        var loc = document.location;
                        $.alert.open({
                            type: 'warning',
                            title: 'Info',
                            icon: 'confirm',            
                            cancel: false,
                            content: "Promo Incharge Successfully Saved",
                            buttons:{
                                OK: 'Yes'
                            },
                            
                            callback: function(button) {
                                if (button == 'OK'){
                                        
                                    window.location = loc;
                                }           

                            }
                        });

                    } else if(data == "Exist") {

                        errDup("Promo Incharge Already Exist");
                    } else {

                        alert(data);
                    }
                } 
            });
        }
    }
</script>