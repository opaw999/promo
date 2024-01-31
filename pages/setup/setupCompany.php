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
                    <h3 class="box-title"><?php echo $subMenu; ?></h3>
                </div>

                <div class="box-body">
                    <div class="form-group">
                        <label>Company</label>
                        <div class="input-group">
                            <input class="form-control" type="text" name="company" style="text-transform:uppercase" autocomplete="off" onkeyup="inputField(this.name)">
                            <span class="input-group-addon"><i class="fa fa-bank"></i></span>
                        </div>
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
    
    function submitFields(){

        var company = $("[name = 'company']").val();

        if (company == "") {

            $.alert.open({
                type: 'warning',
                cancel: false,
                content: "Please Fill-up Required Fields!",
                buttons:{
                    OK: 'Ok'
                },

                callback: function(button) {
                    if (button == 'OK'){

                        if (company == "") {

                            $("[name = 'company']").css("border-color","#dd4b39");
                        }
                    }           

                }
            });
        } else {

            $.ajax({
                type : "POST",
                url  : "functionquery.php?request=setupCompany",
                data : { company:company },
                success : function(data){

                    data = data.trim();
                    if(data == "Ok"){
                        
                        var loc = document.location;
                        $.alert.open({
                            type: 'warning',
                            title: 'Info',
                            icon: 'confirm',            
                            cancel: false,
                            content: "Company Successfully Saved",
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

                        errDup("Company Already Exist");
                    } else {

                        alert(data);
                    }
                } 
            });
        }

    }
</script>