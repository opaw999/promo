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
        <div class="col-md-8">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><?php echo $subMenu; ?></h3>
                </div>
                <form id="data_contract">
                    <div class="box-body">
                        <div class="form-group">
                            <label>Business Unit</label>
                            <select name="store" class="form-control" onchange="locateDept(this.value)">
                                <option value=""> All Business Unit </option>
                                <?php 

                                    $query = mysql_query("SELECT bunit_id, bunit_name, bunit_field FROM `locate_promo_business_unit` WHERE hrd_location = '$hrCode' AND status = 'active'")or die(mysql_error());
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
                    </div>
                </form>
                <div class="box-footer">
                    <button type="button" class="btn btn-primary" onclick="genReport()"> Generate in PDF <img src="../images/icons/pdf-icon.png"></button>
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

    function genReport(){

        var formData = $("form#data_contract").serialize();
 
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
                        
                    window.open("pages/reports/due_contract_pdf.php?"+formData);
                }  
            }
        });
    }
</script>