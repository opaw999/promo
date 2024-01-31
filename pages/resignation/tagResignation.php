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
                        <div class="col-md-4">
                            <!-- <h3>Supervisor Details</h3> -->
                            <div class="form-group">
                                <label>Supervisor</label>
                                <div class="input-group">
                                    <input class="form-control" type="text" name="supervisor" onkeyup="nameSearch(this.value)" autocomplete="off">
                                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                </div>
                                <div class="search-results" style="display: none;"></div>
                            </div>
                            <div class="legend">
                                <div class="panel panel-default">
                                    <div class="panel-body">
                                    <label>Legend</label><hr>
                                        <table>
                                            <tr>
                                                <td><a class="btn btn-primary btn-xs disabled">&nbsp;</a></td>
                                                <td> - </td>
                                                <td>Pending</td>
                                                <td width="10%"></td>
                                                <td><a class="btn btn-success btn-xs disabled">&nbsp;</a></td>
                                                <td> - </td>
                                                <td>Done</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="subordinateList">
                                
                                <div style="width: 5%; margin:0 auto;">
                                    <br><br><br><br><br>
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
                url  : "functionquery.php?request=findSup",
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

        $("[name='supervisor']").val(empId);
        $(".search-results").hide();
        $("[name = 'supervisor']").css("border-color","#ccc");

        var empId = empId.trim().split("*");

        moresignedList(empId[0].trim());

    }

    function moresignedList(empId){

        $("#loading-gif").show();

        $.ajax({
            type : "POST",
            url  : "functionquery.php?request=moresignedList",
            data : { empId:empId },
            success : function(data){

                $("#loading-gif").hide();
                $(".subordinateList").html(data);
            } 
        });
    }

    function statForResign(empId,stat,supId){

        $.alert.open({
            type: 'warning',
            cancel: false,
            content: "Are you sure you want to "+stat+" for resignation?",
            buttons:{
                OK: 'Ok'
            },

            callback: function(button) {
                if (button == 'OK'){

                    $.ajax({
                        type : "POST",
                        url  : "functionquery.php?request=statForResign",
                        data : { empId:empId, stat:stat, supId:supId },
                        success : function(data){

                            data = data.trim();
                            if(data == "Ok"){

                                $.alert.open({
                                    type: 'warning',
                                    title: 'Info',
                                    icon: 'confirm',            
                                    cancel: false,
                                    content: "Successfully "+stat+" for resignation.",
                                    buttons:{
                                        OK: 'Yes'
                                    },
                                    
                                    callback: function(button) {
                                        if (button == 'OK'){
                                                
                                            moresignedList(supId);
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
        });
        
    }
</script>