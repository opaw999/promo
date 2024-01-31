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
                    <h3 class="box-title">Add New User Account</h3>
                </div>

                <div class="box-body">
                    <div class="form-group">
                        <label>Employee</label> <i class="text-red">*</i>
                        <div class="input-group">
                            <input class="form-control" type="text" name="employee" onkeyup="nameSearch(this.value)" autocomplete="off" style="text-transform: uppercase;">
                            <span class="input-group-addon"><i class="fa fa-user"></i></span>
                        </div>
                        <div class="search-results" style="display: none;"></div>
                    </div>
                    <div class="form-group">
                        <label>User Type</label>
                        <input type="hidden" name="usertype" class="form-control" value="employee">
                        <input type="text" name="" class="form-control" value="Employee" disabled="">
                    </div>
                    <div class="form-group">
                        <label>Username</label> <i class="text-red">*</i>
                        <input type="text" name="username" class="form-control" onkeyup="inputField(this.name)">
                    </div>
                    <hr>
                    <button class="btn btn-primary btn-sm" onclick="defaultPassword()">set default password</button> <i>Default password: Hrms2014</i>
                    <div class="form-group">
                        <label>Password</label> <i class="text-red">*</i>
                        <input type="password" name="password" class="form-control" onkeyup="inputField(this.name)">
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
        $("[name = 'username']").css("border-color","#ccc");

        var empId = $("[name='employee']").val().trim().split("*");

        $("[name = 'username']").val(empId[0]);
        // $("[name = 'username']").prop("disabled",true);
    }

    function defaultPassword(){

        var password = "Hrms2014";
        $("[name= 'password']").val(password);
        $("[name= 'password']").prop("disabled",true);
        $("[name= 'password']").css("border-color","#ccc");
    }

    function submitFields(){

        var empId = $("[name='employee']").val().trim().split("*");
        var usertype = $("[name = 'usertype']").val();
        var username = $("[name = 'username']").val();
        var password = $("[name = 'password']").val();

        if (empId[0] == "" || username == "" || password == "") {

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

                        if (username == "") {

                            $("[name = 'username']").css("border-color","#dd4b39");
                        }

                        if (password == "") {

                            $("[name = 'password']").css("border-color","#dd4b39");
                        }
                    }           

                }
            });
        } else {

            $.ajax({
                type : "POST",
                url  : "functionquery.php?request=submitPromoAccount",
                data : { empId:empId[0], usertype:usertype, username:username, password:password },
                success : function(data){

                    data =  data.trim();
                    if(data == "Ok"){
                        
                        var loc = document.location;
                        $.alert.open({
                            type: 'warning',
                            title: 'Info',
                            icon: 'confirm',            
                            cancel: false,
                            content: "Promo Account Successfully Saved",
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

                        errDup("Username Already Exist");
                    } else {

                        alert(data);
                    }
                } 
            });
        }
    }
</script>