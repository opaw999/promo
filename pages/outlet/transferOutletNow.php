<?php 

    $empId = $_GET['empId'];
    $recordNo = $_GET['recordNo'];
    $field = explode("*", $_GET['field']);
    $previousStore = $_GET['previousStore'];

    $sql = mysql_query("SELECT name, startdate, eocdate, position, promo_company, promo_department, promo_type, type FROM employee3, promo_record WHERE employee3.emp_id = promo_record.emp_id AND employee3.emp_id = '$empId' AND employee3.record_no = '$recordNo'")or die(mysql_error());
    $row = mysql_fetch_array($sql);

    $name = $row['name'];


?>
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
                    <h3 class="box-title"><?php echo "$subMenu of <code>$name</code>"; ?></h3>
                </div>

                <div class="box-body">
                    
                    <input type="hidden" name="empId" value="<?php echo $empId; ?>">
                    <input type="hidden" name="recordNo" value="<?php echo $recordNo; ?>">
                    <input type="hidden" name="previousStore" value="<?php echo $previousStore; ?>">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="panel panel-default">
                                <div class="panel-heading"> 
                                    <strong>PREVIOUS OUTLET</strong>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <?php if ($row['promo_type'] == "ROVING") { ?>

                                                <table class="table table-bordered">
                                                    <tr>
                                                        <td colspan="2"><b>FROM :</b></td>
                                                    </tr>
                                                    <?php 

                                                        $store = mysql_query("SELECT * FROM `locate_promo_business_unit` WHERE status = 'active' AND hrd_location = '$hrCode'")or die(mysql_error());
                                                        while ($r = mysql_fetch_array($store)) { 

                                                            $bunit = mysql_query("SELECT promo_id FROM `promo_record` WHERE $r[bunit_field] = 'T' AND emp_id = '$empId'")or die(mysql_error());
                                                            $bunitNum = mysql_num_rows($bunit);
                                                            ?>
                                                            <tr>
                                                                <td><input type="checkbox" disabled="" <?php if($bunitNum > 0) { echo "checked=''"; } ?>/></td>
                                                                <td><?php echo $r['bunit_name'];?></td>
                                                            </tr><?php 
                                                        }
                                                    ?>
                                                </table>
                                            <?php } else { ?>

                                                <table class="table table-bordered">
                                                    <tr>
                                                        <td colspan="2"><b>FROM :</b></td>
                                                    </tr>
                                                    <?php 

                                                        $store = mysql_query("SELECT * FROM `locate_promo_business_unit` WHERE status = 'active' AND hrd_location = '$hrCode'")or die(mysql_error());
                                                        while ($r = mysql_fetch_array($store)) { 

                                                            $bunit = mysql_query("SELECT promo_id FROM `promo_record` WHERE $r[bunit_field] = 'T' AND emp_id = '$empId'")or die(mysql_error());
                                                            $bunitNum = mysql_num_rows($bunit);
                                                            ?>
                                                            <tr>
                                                                <td><input type="radio" <?php if($bunitNum > 0) { echo "checked=''"; } else { echo "disabled=''"; } ?>/></td>
                                                                <td><?php echo $r['bunit_name'];?></td>
                                                            </tr><?php 
                                                        }
                                                    ?>
                                                </table>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <table class="table table-bordered">
                                        <tr style="background-color: #f3f3f3;">
                                            <th colspan="3">More Details...</th>
                                        </tr>
                                        <tr>
                                            <td>Department</td>
                                            <td><?php echo $row['promo_department']; ?></td>
                                        </tr>
                                        <tr>
                                            <td>Company</td>
                                            <td><?php echo $row['promo_company']; ?></td>
                                        </tr>
                                        <tr>
                                            <td>Contract Type</td>
                                            <td><?php echo $row['type']; ?></td>
                                        </tr>
                                        <tr>
                                            <td>Position</td>
                                            <td><?php echo $row['position']; ?></td>
                                        </tr>
                                        <tr>
                                            <td>Startdate</td>
                                            <td><?php echo date("m/d/Y", strtotime($row['startdate'])); ?></td>
                                        </tr>
                                        <tr>
                                            <td>EOCdate</td>
                                            <td><?php echo date("m/d/Y", strtotime($row['eocdate'])); ?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="panel panel-default">
                                <div class="panel-heading"> 
                                    <strong>CURRENT OUTLET</strong>
                                </div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <div class="store">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th colspan="2"><i class="text-red">*</i> TO :</th>
                                                </tr>
                                                <?php 

                                                    $counter = 0;
                                                    $noStore = 0;

                                                    $store = mysql_query("SELECT bunit_id, bunit_name, bunit_field FROM `locate_promo_business_unit` WHERE status = 'active' AND hrd_location = '$hrCode'")or die(mysql_error());
                                                    while ($r = mysql_fetch_array($store)) { 

                                                        $chk = "false";
                                                        for ($i=0; $i < sizeof($field) -1; $i++) { 
                                                            
                                                            if ($r['bunit_field'] == $field[$i]) {
                                                                $chk = "true";
                                                            }
                                                        }

                                                        $counter++;
                                                        $bunit = mysql_query("SELECT promo_id FROM `promo_record` WHERE $r[bunit_field] = 'T' AND emp_id = '$empId' AND record_no = '$recordNo'")or die(mysql_error());
                                                        $bunitNum = mysql_num_rows($bunit);

                                                        ?>
                                                        <tr>
                                                            <td><input type="checkbox" id="check_<?php echo $counter; ?>" name="<?php echo $r['bunit_field']; ?>" value="<?php echo $r['bunit_id'].'/'.$r['bunit_field']; ?>" <?php if($bunitNum > 0 && $chk == "false"): echo "checked=''"; $noStore++; endif; ?> <?php if($bunitNum > 0): echo "disabled=''"; endif; ?> onclick="checkField(<?php echo $counter; ?>)"/></td>
                                                            <td><span class="chk_<?php echo $counter; ?>"><?php echo $r['bunit_name']; ?></span></td>
                                                        </tr><?php 
                                                    }
                                                ?>
                                                <input type="hidden" name="counter" value="<?php echo $counter; ?>">
                                                <input type="hidden" name="noStore" value="<?php echo $noStore; ?>">
                                            </table>
                                            
                                        </div>
                                    </div>
                                    <div class="form-group"> <span class="text-red">*</span>
                                        <label>Effective On</label> 
                                        <div class="input-group date">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <input type="text" name="effectiveOn" class="form-control pull-right datepicker" value="<?php echo date('m/d/Y'); ?>" onchange="inputField(this.name)">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Remarks</label> <i class="">(optional)</i>
                                        <textarea name="remarks" class="form-control" rows="4"></textarea>
                                    </div>
                                    
                                </div>
                                <div class="panel-footer">
                                    <button class="btn btn-primary" onclick="transferOutlet()"><i class="fa fa-bank"></i>&nbsp; Transfer Outlet</button>
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
    
    function transferOutlet(){

        var empId = $("[name = 'empId']").val();
        var recordNo = $("[name = 'recordNo']").val();
        var previousStore = $("[name = 'previousStore']").val();
        var noStore = $("[name = 'noStore']").val();
        var effectiveOn = $("[name = 'effectiveOn']").val();
        var remarks = $("[name = 'remarks']").val().trim();
       
        // current store
        var counter = $("[name = 'counter']").val();
        var chkNum = 0;
        var store = "";

        for (var i = 1;  i <= counter; i++) {
            
            if ($("#check_"+i).is(':checked')) {

                chkNum++;
                var bunit_id = $("#check_"+i).val();
                store += bunit_id+"||";
            }
        }

        if (chkNum <= noStore || effectiveOn == "") {

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

                errDup("Please Add Store for Setup!");
            }


        } else {
           
            $.ajax({
                type : "POST",
                url  : "functionquery.php?request=submitTransferOutlet",
                data : { empId:empId, recordNo:recordNo, store:store, previousStore:previousStore, effectiveOn:effectiveOn, remarks:remarks },
                success : function(data){

                    data = data.trim();
                    if (data == "success") {

                        $.alert.open({
                            type: 'warning',
                            title: 'Info',
                            icon: 'confirm',            
                            cancel: false,
                            content: "<b>Successfully Transfer Outlet!</b> <br><br> Please inform the promo to proceed to the newly assigned store for futher instructions.",
                            buttons:{
                                OK: 'Yes'
                            },
                            
                            callback: function(button) {
                                if (button == 'OK'){
                                        
                                    window.location = "?p=transferOutlet&&module=Outlet";
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

    function checkField(id){

        if ($("#check_"+id).is(':checked')) {
          
            $(".chk_"+id).css({
                "color": "red", 
                "font-style": "italic"
            });
        } else {
          
            $(".chk_"+id).css({
                "color": "black", 
                "font-style": "normal"
            });
        }
    }

</script>