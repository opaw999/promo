<?php

$empId = $_GET['empId'];
$recordNo = $nq->getRec($empId);
$name = $nq->getEmpName($empId);

$sql = mysql_query("SELECT name, startdate, eocdate, duration, emp_type, current_status, positionlevel, position, comments, remarks, agency_code, promo_company, promo_department, vendor_code, company_duration, promo_type, type
                                FROM 
                                    `employee3` LEFT JOIN `promo_record` ON employee3.emp_id = promo_record.emp_id 
                                WHERE employee3.emp_id = '$empId' AND employee3.record_no = '$recordNo'") or die(mysql_error());
$row = mysql_fetch_array($sql);

// agency name
$supplier = mysql_query("SELECT agency_name FROM timekeeping.promo_locate_agency WHERE agency_code = '" . $row['agency_code'] . "'") or die(mysql_error());
$ag = mysql_fetch_array($supplier);

$agency_name = $ag['agency_name'];

// vendor name
$vendor_list = mysql_query("SELECT vendor_name FROM promo_vendor_lists WHERE vendor_code = '" . $row['vendor_code'] . "'") or die(mysql_error());
$vl = mysql_fetch_array($vendor_list);
$vendor_name = $vl['vendor_name'];

// promo products
$emp_products = "";
$products = "";
$x = 0;
$promo_products = mysql_query("SELECT product FROM promo_products WHERE record_no = '$recordNo' AND emp_id = '$empId'") or die(mysql_error());
while ($prod = mysql_fetch_array($promo_products)) {

    // array_push($emp_products, $prod['product']);
    if ($x == 0) {
        $emp_products = $prod['product'];
        $products = $prod['product'];
    } else {

        $emp_products .= "|" . $prod['product'];
        $products .= ", " . $prod['product'];
    }

    $x++;
}

// promo cut-off
$statCut = mysql_query("SELECT statCut FROM timekeeping.promo_sched_emp WHERE recordNo = '$recordNo' AND empId = '$empId'") or die(mysql_error());
$sc = mysql_fetch_array($statCut);

$cutoff = mysql_query("SELECT startFC, endFC, startSC, endSC FROM timekeeping.promo_schedule WHERE statCut = '" . $sc['statCut'] . "'") or die(mysql_error());
$co = mysql_fetch_array($cutoff);

$endFC = ($co['endFC'] != '') ? $co['endFC'] : 'last';
if ($co['startFC'] != '') {

    $cut_off = $co['startFC'] . '-' . $endFC . ' / ' . $co['startSC'] . '-' . $co['endSC'];
} else {

    $cut_off = '';
}

$company = mysql_query("SELECT pc_code FROM `locate_promo_company` WHERE pc_name = '" . mysql_real_escape_string($row['promo_company']) . "'") or die(mysql_error());
$com = mysql_fetch_array($company);

$ctr = 0;
$storeList = "";
$getStore = mysql_query("SELECT bunit_id, bunit_field FROM `locate_promo_business_unit` WHERE status = 'active' AND hrd_location = '$hrCode'") or die(mysql_error());
while ($str = mysql_fetch_array($getStore)) {

    $bunit = mysql_query("SELECT promo_id FROM `promo_record` WHERE $str[bunit_field] = 'T' AND emp_id = '$empId' AND record_no = '$recordNo'") or die(mysql_error());
    $bunitNum = mysql_num_rows($bunit);

    if ($bunitNum > 0) {

        $ctr++;
        if ($ctr == 1) {

            $storeList = "$str[bunit_id]/$str[bunit_field]";
        } else {

            $storeList .= "|" . $str['bunit_id'] . "/" . $str['bunit_field'];
        }
    }
}

?>
<style type="text/css">
    .witness1,
    .witness1Renewal {

        box-shadow: 5px 5px 5px #ccc;
        margin-top: 1px;
        margin-left: 0px;
        background-color: #F1F1F1;
        width: 94%;
        border-radius: 3px 3px 3px 3px;
        font-size: 18x;
        padding: 8px 10px;
        display: block;
        position: absolute;
        z-index: 9999;
        max-height: 300px;
        overflow-y: scroll;
        overflow: auto;
    }

    .witness2,
    .witness2Renewal {

        box-shadow: 5px 5px 5px #ccc;
        margin-top: 1px;
        margin-left: 0px;
        background-color: #F1F1F1;
        width: 94%;
        border-radius: 3px 3px 3px 3px;
        font-size: 18x;
        padding: 8px 10px;
        display: block;
        position: absolute;
        z-index: 9999;
        max-height: 300px;
        overflow-y: scroll;
        overflow: auto;
    }
</style>
<section class="content">

    <!-- Default box -->
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Renewal of Contract</h3>
                </div>
                <form action="" id="dataProcessRenewal" method="post" enctype="multipart/form-data" autocomplete="off">
                    <div class="box-body">

                        <input type="hidden" name="empId" value="<?php echo $empId; ?>">
                        <input type="hidden" name="recordNo" value="<?php echo $recordNo; ?>">
                        <input type="hidden" name="edited" value="false">
                        <input type="hidden" name="introName">
                        <input type="hidden" name="store" value="<?php echo $storeList; ?>">
                        <input type="hidden" name="renewContract" value="">

                        <h4><strong><?php echo "[$empId] " . ucwords(strtolower($name)); ?></strong></h4>
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td width="26%"></td>
                                    <th width="37%">Previous Contract Details</th>
                                    <th width="37%">New Contract Details
                                        <a href="javascript:void(0)" onclick="edit_renew_control();" id="edit_new" class="btn btn-primary btn-sm pull-right">Edit</a>
                                        <a href="javascript:void(0)" style="display:none;" id="cancel_new" onclick="cancel_renew_control();" class="btn btn-danger btn-sm pull-right">Cancel</a>
                                    </th>
                                </tr>
                                <tr>
                                    <td>Agency</td>
                                    <td><?php echo $agency_name; ?></td>
                                    <td>
                                        <input name="agency" value="<?php echo $row['agency_code']; ?>" type="hidden">
                                        <span class="inputLabel"><?php echo $agency_name; ?></span>
                                        <select name="agency_select" class="form-control inputSelect" style="display:none;" onchange="select_agency(this.value)"></select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Company</td>
                                    <td><?php echo $row['promo_company']; ?></td>
                                    <td>
                                        <input name="company" value="<?php echo $com['pc_code']; ?>" type="hidden">
                                        <span class="inputLabel"><?php echo $row['promo_company']; ?></span>
                                        <select name="company_select" class="form-control inputSelect" style="display:none;" onchange="select_product(this.value)"></select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Promo Type</td>
                                    <td><?php echo $row['promo_type'] ?></td>
                                    <td>
                                        <input name="promoType" value="<?php echo $row['promo_type'] ?>" type="hidden">
                                        <span class="inputLabel"><?php echo $row['promo_type'] ?></span>
                                        <select name="promoType_select" class="form-control inputSelect" style="display:none;" onchange="locateBunit(this.value)">
                                            <option value="STATION" <?php if ($row['promo_type'] == "STATION") : echo "selected=''";
                                                                    endif; ?>>STATION</option>
                                            <option value="ROVING" <?php if ($row['promo_type'] == "ROVING") : echo "selected=''";
                                                                    endif; ?>>ROVING</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Business Unit</td>
                                    <td>
                                        <?php if ($row['promo_type'] == "ROVING") { ?>
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th colspan="2"> Business Unit</th>
                                                </tr>
                                                <?php

                                                $store = mysql_query("SELECT bunit_id, bunit_name, bunit_field FROM `locate_promo_business_unit` WHERE status = 'active' AND hrd_location = '$hrCode'") or die(mysql_error());
                                                while ($r = mysql_fetch_array($store)) {

                                                    $bunit = mysql_query("SELECT promo_id FROM `promo_record` WHERE $r[bunit_field] = 'T' AND emp_id = '$empId' AND record_no = '$recordNo'") or die(mysql_error());
                                                    $bunitNum = mysql_num_rows($bunit);
                                                ?>
                                                    <tr>
                                                        <td><input type="checkbox" name="<?php echo $r['bunit_field']; ?>" disabled="" <?php if ($bunitNum > 0) {
                                                                                                                                            echo "checked=''";
                                                                                                                                        } ?>></td>
                                                        <td><?php echo $r['bunit_name']; ?></td>
                                                    </tr><?php
                                                        }
                                                            ?>
                                            </table>
                                        <?php } else { ?>

                                            <table class="table table-bordered">
                                                <tr>
                                                    <th colspan="2"> Business Unit</th>
                                                </tr>
                                                <?php

                                                $store = mysql_query("SELECT * FROM `locate_promo_business_unit` WHERE status = 'active' AND hrd_location = '$hrCode'") or die(mysql_error());
                                                while ($s = mysql_fetch_array($store)) {

                                                    $bunit = mysql_query("SELECT promo_id FROM `promo_record` WHERE $s[bunit_field] = 'T' AND emp_id = '$empId' AND record_no = '$recordNo'") or die(mysql_error());
                                                    $bunitNum = mysql_num_rows($bunit);

                                                ?>
                                                    <tr>
                                                        <td><input type="radio" disabled="" name="stations" <?php if ($bunitNum > 0) {
                                                                                                                echo "checked=''";
                                                                                                            } ?>></td>
                                                        <td><?php echo $s['bunit_name']; ?></td>
                                                    </tr><?php
                                                        }
                                                            ?>
                                            </table>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <div class="store">
                                            <?php if ($row['promo_type'] == "ROVING") { ?>
                                                <table class="table table-bordered">
                                                    <tr>
                                                        <th colspan="2"> Business Unit</th>
                                                    </tr>
                                                    <?php

                                                    $counter = 0;
                                                    $store = mysql_query("SELECT bunit_id, bunit_name, bunit_field FROM `locate_promo_business_unit` WHERE status = 'active' AND hrd_location = '$hrCode'") or die(mysql_error());
                                                    while ($r = mysql_fetch_array($store)) {

                                                        $counter++;
                                                        $bunit = mysql_query("SELECT promo_id FROM `promo_record` WHERE $r[bunit_field] = 'T' AND emp_id = '$empId' AND record_no = '$recordNo'") or die(mysql_error());
                                                        $bunitNum = mysql_num_rows($bunit);
                                                    ?>
                                                        <tr>
                                                            <td><input type="checkbox" class="checkedEnable" disabled="" id="check_<?php echo $counter; ?>" name="<?php echo $r['bunit_field']; ?>" value="<?php echo $r['bunit_id'] . '/' . $r['bunit_field']; ?>" <?php if ($bunitNum > 0) {
                                                                                                                                                                                                                                                                        echo "checked=''";
                                                                                                                                                                                                                                                                    } ?> onclick="locateDeptR()" /></td>
                                                            <td><?php echo $r['bunit_name']; ?></td>
                                                        </tr><?php
                                                            }
                                                                ?>
                                                    <input type="hidden" name="counter" value="<?php echo $counter; ?>">
                                                </table>
                                            <?php } else { ?>

                                                <table class="table table-bordered">
                                                    <tr>
                                                        <th colspan="2"> Business Unit</th>
                                                    </tr>
                                                    <?php

                                                    $loop = 0;
                                                    $store = mysql_query("SELECT * FROM `locate_promo_business_unit` WHERE status = 'active' AND hrd_location = '$hrCode'") or die(mysql_error());
                                                    while ($s = mysql_fetch_array($store)) {

                                                        $loop++;
                                                        $bunit = mysql_query("SELECT promo_id FROM `promo_record` WHERE $s[bunit_field] = 'T' AND emp_id = '$empId' AND record_no = '$recordNo'") or die(mysql_error());
                                                        $bunitNum = mysql_num_rows($bunit);

                                                    ?>
                                                        <tr>
                                                            <td><input type="radio" class="checkedEnable" disabled="" name="station" id="radio_<?php echo $loop; ?>" value="<?php echo $s['bunit_id'] . '/' . $s['bunit_field']; ?>" <?php if ($bunitNum > 0) {
                                                                                                                                                                                                                                            echo "checked=''";
                                                                                                                                                                                                                                        } ?> onclick="locateDeptS(this.value)" /></td>
                                                            <td><?php echo $s['bunit_name']; ?></td>
                                                        </tr><?php
                                                            }
                                                                ?>

                                                    <input type="hidden" name="loop" value="<?php echo $loop; ?>">
                                                </table>
                                            <?php } ?>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Department</td>
                                    <td><?php echo $row['promo_department']; ?></td>
                                    <td>
                                        <input name="department" value="<?php echo $row['promo_department']; ?>" type="hidden">
                                        <span class="inputLabel"><?php echo $row['promo_department']; ?></span>
                                        <select name="department_select" class="form-control inputSelect" style="display:none;" onclick="chkDepartment(this.value)"></select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Vendor Name</td>
                                    <td><?php echo $vendor_name; ?></td>
                                    <td>
                                        <input name="vendor" value="<?php echo $row['vendor_code']; ?>" type="hidden">
                                        <span class="inputLabel"><?php echo $vendor_name; ?></span>
                                        <select name="vendor_select" class="form-control inputSelect" style="display:none;"></select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Product</td>
                                    <td><?php echo $products; ?></td>
                                    <td>
                                        <input name="product" value="<?php echo $emp_products; ?>" type="hidden">
                                        <span class="inputLabel"><?php echo $products; ?></span>
                                        <div class="inputSelect product_select" style="display:none;"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Position</td>
                                    <td><?php echo $row['position']; ?></td>
                                    <td>
                                        <input name="position" value="<?php echo $row['position']; ?>" type="hidden">
                                        <span class="inputLabel"><?php echo $row['position']; ?></span>
                                        <select name="position_select" class="form-control inputSelect" style="display:none;" onchange="positionLevel(this.value)"></select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Position Level</td>
                                    <td><?php echo $row['positionlevel']; ?></td>
                                    <td>
                                        <input name="positionlevel" value="<?php echo $row['positionlevel']; ?>" type="hidden">
                                        <span class="inputLabel"><?php echo $row['positionlevel']; ?></span>
                                        <input type="hidden" name="positionlevel_select">
                                        <input type="text" name="level" class="form-control inputSelect" readonly="" style="display:none;">
                                    </td>
                                </tr>
                                <tr>
                                    <td>Employee Type</td>
                                    <td><?php echo $row['emp_type']; ?></td>
                                    <td>
                                        <input name="empType" value="<?php echo $row['emp_type']; ?>" type="hidden">
                                        <span class="inputLabel"><?php echo $row['emp_type']; ?></span>
                                        <select name="empType_select" class="form-control inputSelect" style="display:none"></select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Contract Type</td>
                                    <td><?php echo $row['type']; ?></td>
                                    <td>
                                        <input name="contractType" value="<?php echo $row['type']; ?>" type="hidden">
                                        <span class="inputLabel"><?php echo $row['type']; ?></span>
                                        <select name="contractType_select" class="form-control inputSelect" style="display:none" onchange="chkContractType(this.value)"></select>
                                    </td>
                                </tr>
                                <tr class="companyDuration" <?php if ($row['type'] == "Seasonal" || $row['promo_department'] == "HOME AND FASHION" || $row['promo_department'] == "FIXRITE" || $row['promo_department'] == "EASY FIX") : echo "";
                                                            else : echo "style = 'display:none;'";
                                                            endif; ?>>
                                    <td><i class="text-red">*</i> Duration from Company</td>
                                    <td><?php if ($row['company_duration'] == '0000-00-00' || $row['company_duration'] == '') : echo "";
                                        else : echo date("M. d, Y", strtotime($row['company_duration']));
                                        endif; ?></td>
                                    <td>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <input type="text" name="companyDuration" class="form-control datepicker" placeholder="mm/dd/yyyy" onchange="inputField(this.name)">
                                    </td>
                    </div>
                    </tr>
                    <tr>
                        <th colspan="3">INCLUSIVE DATES OF CONTRACT</th>
                    </tr>
                    <tr>
                        <td><i class="text-red">*</i> Startdate</td>
                        <td><?php echo date("M. d, Y", strtotime($row['startdate'])); ?></td>
                        <td>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" name="startdate" class="form-control datepicker" placeholder="mm/dd/yyyy" required="" onchange="inputStartdate()">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><i class="text-red">*</i> EOCdate</td>
                        <td><?php echo date("M. d, Y", strtotime($row['eocdate'])); ?></td>
                        <td>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" name="eocdate" class="form-control datepicker" placeholder="mm/dd/yyyy" required="" onchange="durationContract(this.value)">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>No. of Month(s) to Work</td>
                        <td><?php echo $row['duration']; ?></td>
                        <td>
                            <input type="hidden" name="duration" class="duration">
                            <input type="text" class="form-control duration" disabled="">
                        </td>
                    </tr>
                    <tr>
                        <th colspan="3">CUT-OFF DETAILS</th>
                    </tr>
                    <tr>
                        <td><span class="text-red">*</span> </span>Cut-off</td>
                        <td><?php echo $cut_off; ?></td>
                        <td>
                            <input name="cutoff" value="<?php echo $sc['statCut']; ?>" type="hidden">
                            <span class="inputLabel"><?php echo $cut_off; ?></span>
                            <select name="cutoff_select" class="form-control inputSelect" style="display:none;"></select>
                        </td>
                    </tr>
                    <tr>
                        <th colspan="3">INTRO &nbsp;<small class="text-red">Allowed File : jpg, jpeg, png only</small></th>
                    </tr>
                    <tr>
                        <tbody id="promoIntro">
                            <?php

                            if ($row['promo_type'] == "STATION") {

                                $counter = 0;
                                $bunitIntro = mysql_query("SELECT bunit_id, bunit_field, bunit_name, bunit_intro FROM `locate_promo_business_unit`") or die(mysql_error());
                                while ($bI = mysql_fetch_array($bunitIntro)) {

                                    $bunitId = $bI['bunit_id'];
                                    $bunitField = $bI['bunit_field'];

                                    $emp = mysql_query("SELECT promo_id FROM `promo_record` WHERE $bunitField = 'T'  AND emp_id = '$empId'") or die(mysql_error());
                                    $empNum = mysql_num_rows($emp);

                                    if ($empNum > 0) {

                                        $counter++;
                            ?>
                                        <tr>
                                            <td><i class="text-red">*</i> <?php echo $bI['bunit_name']; ?></td>
                                            <td>
                                                <input type="file" name="<?php echo $bI['bunit_intro']; ?>" class="form-control" id="intro_<?php echo $counter; ?>" required onchange="validateForm(this.id)">
                                            </td>
                                            <td></td>
                                        </tr> <?php
                                            }
                                        }

                                        echo "
                                                    <tr style='display:none'>
                                                        <td colspan='3'><input type='hidden' name='counter2' value='$counter'></td>
                                                    </tr>
                                                ";
                                    } else {

                                        $counter = 0;
                                        $bunitIntro = mysql_query("SELECT bunit_id, bunit_field, bunit_name, bunit_intro FROM `locate_promo_business_unit`") or die(mysql_error());
                                        while ($bI = mysql_fetch_array($bunitIntro)) {

                                            $bunitId = $bI['bunit_id'];
                                            $bunitField = $bI['bunit_field'];

                                            $emp = mysql_query("SELECT promo_id FROM `promo_record` WHERE $bunitField = 'T'  AND emp_id = '$empId'") or die(mysql_error());
                                            $empNum = mysql_num_rows($emp);

                                            if ($empNum > 0) {

                                                $counter++;
                                                ?>
                                        <tr>
                                            <td><i class="text-red">*</i> <?php echo $bI['bunit_name']; ?></td>
                                            <td>
                                                <input type="file" name="<?php echo $bI['bunit_intro']; ?>" class="form-control" id="intro_<?php echo $counter; ?>" required onchange="validateForm(this.id)">
                                            </td>
                                            <td></td>
                                        </tr> <?php
                                            }
                                        }

                                        echo "
                                                    <tr style='display:none'>
                                                        <td colspan='3'><input type='hidden' name='counter2' value='$counter'></td>
                                                    </tr>
                                                ";
                                    }
                                                ?>
                        </tbody>
                    </tr>
                    <tr>
                        <th colspan="3">SIGNED IN THE PRESENCE OF</th>
                    </tr>
                    <tr>
                        <td colspan="3">

                            <div class="row">
                                <div class="col-md-6">
                                    <label>Witness 1</label>
                                    <input type="text" name="witness1" class="form-control" placeholder="Firstname Lastname" onkeyup="searchWitness1(this.value)" style="text-transform: uppercase;" autocomplete="off">
                                    <div class="witness1" style="display: none;"></div>
                                </div>
                                <div class="col-md-6">
                                    <label>Witness 2</label>
                                    <input type="text" name="witness2" class="form-control" placeholder="Firstname Lastname" onkeyup="searchWitness2(this.value)" style="text-transform: uppercase;" autocomplete="off">
                                    <div class="witness2" style="display: none;"></div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th colspan="3">COMMENTS/REMARKS</th>
                    </tr>
                    <tr>
                        <td colspan="3">

                            <div class="row">
                                <div class="col-md-6">
                                    <label>Comments</label>
                                    <textarea name="comments" class="form-control"></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label>Remarks</label>
                                    <textarea name="remarks" class="form-control"></textarea>
                                </div>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                    </table>
            </div>
            <div class="box-footer">
                <div class="pull-right">
                    <button class="submit-contract btn btn-primary">Submit</button>
                </div>
            </div>
            </form>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
    </div>

</section>

<!-- ./Modal -->
<div id="printContractAndPermit" class="modal fade">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header bg-light-blue color-palette">
                <button type="button" class="close" aria-label="Close" onclick="back()">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Employment</h4>
            </div>
            <div class="modal-body">
                <div class="printContractAndPermit"></div>
            </div>
            <div class="modal-footer">
                <span class="loadingSave"></span>
                <button type="button" class="btn btn-default" onclick="back()">Close</button>
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

<div id="printContract" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light-blue color-palette">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Edit & Generate Contract</h4>
            </div>
            <div class="modal-body">
                <div class="printContract"></div>
            </div>
            <div class="modal-footer">
                <span class="loadingSave"></span>
                <button class="btn btn-primary" onclick="genContract()"><i class="fa fa-file-pdf-o"></i> &nbsp;Generate Contract</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script type="text/javascript">
    function positionLevel(position) {

        // for business unit
        $.ajax({
            type: "POST",
            url: "functionquery.php?request=loadPositionLevel",
            data: {
                position
            },
            success: function(data) {

                data = JSON.parse(data);
                $("input[name='positionlevel_select']").val(data.levelno);
                $("input[name='level']").val(data.levelno);
            }
        });
    }

    function edit_renew_control() {

        $.alert.open({
            type: 'warning',
            cancel: false,
            content: "Are you sure do you want to edit this records?",
            buttons: {
                OK: 'Ok',
                NO: 'Not now'
            },

            callback: function(button) {
                if (button == 'OK') {

                    $("#cancel_new").show();
                    $("#edit_new").hide();
                    $(".inputLabel").hide();
                    $(".inputSelect").show();
                    $(".checkedEnable").prop("disabled", false);
                    $("[name = 'edited']").val("true");

                    var empId = $("[name = 'empId']").val();
                    var recordNo = $("[name = 'recordNo']").val();
                    var agency = $("input[name = 'agency']").val();
                    var company = $("[name = 'company']").val();
                    var promoType = $("[name = 'promoType']").val();
                    var department = $("[name = 'department']").val();
                    var vendor = $("[name = 'vendor']").val();
                    var product = $("[name = 'product']").val();
                    var position = $("[name = 'position']").val();
                    var empType = $("[name = 'empType']").val();
                    var contractType = $("[name = 'contractType']").val();
                    var statCut = $("input[name = 'cutoff']").val();

                    // for agency 
                    $.ajax({
                        type: "POST",
                        url: "functionquery.php?request=loadAgency",
                        data: {
                            agency: agency
                        },
                        success: function(data) {

                            $("[name='agency_select']").html(data);
                        }
                    });

                    // for company 
                    $.ajax({
                        type: "POST",
                        url: "functionquery.php?request=loadCompany",
                        data: {
                            company: company
                        },
                        success: function(data) {

                            $("[name='company_select']").html(data);
                        }
                    });

                    // for department
                    $.ajax({
                        type: "POST",
                        url: "functionquery.php?request=loadDepartment",
                        data: {
                            empId: empId,
                            promoType: promoType,
                            department: department
                        },
                        success: function(data) {

                            $("[name='department_select']").html(data);
                        }
                    });

                    // for vendor
                    $.ajax({
                        type: "POST",
                        url: "functionquery.php?request=loadVendor",
                        data: {
                            department: department,
                            vendor: vendor
                        },
                        success: function(data) {

                            $("[name='vendor_select']").html(data);
                            $("select[name = 'vendor_select']").addClass('select2');
                        }
                    });

                    // for product
                    $.ajax({
                        type: "POST",
                        url: "functionquery.php?request=loadProducts",
                        data: {
                            company,
                            product
                        },
                        success: function(data) {

                            $("div.product_select").html(data);
                        }
                    });

                    // for cutoff
                    $.ajax({
                        type: "POST",
                        url: "functionquery.php?request=loadCutoffs",
                        data: {
                            statCut
                        },
                        success: function(data) {

                            $("select[name = 'cutoff_select']").html(data);
                        }
                    });

                    // for position 
                    $.ajax({
                        type: "POST",
                        url: "functionquery.php?request=loadPosition",
                        data: {
                            position: position
                        },
                        success: function(data) {

                            $("[name='position_select']").html(data);
                        }
                    });

                    // for position level
                    $.ajax({
                        type: "POST",
                        url: "functionquery.php?request=loadPositionLevel",
                        data: {
                            position
                        },
                        success: function(data) {

                            data = JSON.parse(data);
                            $("input[name='positionlevel_select']").val(data.levelno);
                            $("input[name='level']").val(data.levelno);
                        }
                    });

                    // for employee type
                    $.ajax({
                        type: "POST",
                        url: "functionquery.php?request=loadEmpType",
                        data: {
                            empType: empType
                        },
                        success: function(data) {

                            $("[name='empType_select']").html(data);
                        }
                    });

                    // for contract type
                    $.ajax({
                        type: "POST",
                        url: "functionquery.php?request=loadContractType",
                        data: {
                            contractType: contractType
                        },
                        success: function(data) {

                            $("[name='contractType_select']").html(data);
                        }
                    });

                }

            }
        });
    }

    function cancel_renew_control() {

        $.alert.open({
            type: 'warning',
            cancel: false,
            content: "Are you sure do you want to cancel the edit transaction?",
            buttons: {
                OK: 'Ok',
                NO: 'Not now'
            },

            callback: function(button) {
                if (button == 'OK') {

                    $("#cancel_new").hide();
                    $("#edit_new").show();
                    $(".inputLabel").show();
                    $(".inputSelect").hide();
                    $(".checkedEnable").prop("disabled", true);
                    $("[name = 'edited']").val("false");

                    var promoType = $("[name = 'promoType']").val();
                    var empId = $("[name = 'empId']").val();
                    var recordNo = $("[name = 'recordNo']").val();

                    // for business unit
                    $.ajax({
                        type: "POST",
                        url: "functionquery.php?request=loadPromoBusinessUnit",
                        data: {
                            empId: empId,
                            promoType: promoType,
                            recordNo: recordNo
                        },
                        success: function(data) {

                            $(".store").html(data);
                        }
                    });

                    // for intro
                    $.ajax({
                        type: "POST",
                        url: "functionquery.php?request=loadPromoIntro",
                        data: {
                            empId: empId,
                            promoType: promoType,
                            recordNo: recordNo
                        },
                        success: function(data) {

                            $("#promoIntro").html(data);
                        }
                    });
                }

            }
        });
    }

    // newly added module add agency
    function select_agency(agency_code) {

        $("[name = 'agency_select']").css("border-color", "#ccc");
        var promo_company = $("input[name = 'company']").val();

        $.ajax({
            type: "POST",
            url: "employee_information_details.php?request=company_list",
            data: {
                agency_code: agency_code,
                promo_company: promo_company
            },
            success: function(data) {

                $("select[name = 'company_select']").html(data);
            }
        });
    }

    function locateDeptS(id) {

        $("[name = 'store']").val(id);

        $.ajax({
            type: "POST",
            url: "functionquery.php?request=locateDeptS",
            data: {
                id: id
            },
            success: function(data) {

                $("[name = 'department_select']").html(data);
            }
        });

        $.ajax({
            type: "POST",
            url: "functionquery.php?request=showIntroS",
            data: {
                id: id
            },
            success: function(data) {

                $("#promoIntro").html("").append(data);
            }
        });
    }

    function locateDeptR() {

        var storeId = "";
        var counter = $("[name = 'counter']").val();
        var loop = 0;

        for (var i = 1; i <= counter; i++) {

            if ($("#check_" + i).is(':checked')) {

                var bunit_id = $("#check_" + i).val();
                if (loop == 0) {

                    storeId += bunit_id;
                } else {

                    storeId += "|" + bunit_id;
                }
                loop++;
            }
        }

        $("[name = 'store']").val(storeId);

        $.ajax({
            type: "POST",
            url: "functionquery.php?request=locateDeptR",
            data: {
                storeId: storeId
            },
            success: function(data) {

                $("[name = 'department_select']").html(data);
            }
        });

        $.ajax({
            type: "POST",
            url: "functionquery.php?request=showIntroR",
            data: {
                storeId: storeId
            },
            success: function(data) {
                ;
                $("#promoIntro").html("").append(data);
            }
        });
    }

    function validateForm(imgid) {

        $("#" + imgid).css("border-color", "#ccc");

        var introName = "";
        var introValue = "";
        var introMsg = "";
        var counter2 = $("[name = 'counter2']").val();

        for (var x = 1; x <= counter2; x++) {

            var intro = $("#intro_" + x).attr('name');
            introName += intro + "|";
        }

        $("[name = 'introName']").val(introName);

        var img = $("#" + imgid).val();
        var res = '';
        var i = img.length - 1;
        while (img[i] != ".") {
            res = img[i] + res;
            i--;
        }

        //checks the file format
        if (res != "PNG" && res != "jpg" && res != "JPG" && res != "png") {
            $("#" + imgid).val("");
            errDup('Invalid File Format. Take note on the allowed file!');
            return;
        }

        //checks the filesize- should not be greater than 2MB
        var uploadedFile = document.getElementById(imgid);
        var fileSize = uploadedFile.files[0].size < 1024 * 1024 * 2;
        if (fileSize == false) {
            $("#" + imgid).val("");
            errDup('The size of the file exceeds 2MB!')
            return;
        }
    }

    function locateBunit(promoType) {

        $("[name = 'promoType']").css("border-color", "#ccc");
        $.ajax({
            type: "POST",
            url: "functionquery.php?request=locateBunit",
            data: {
                promoType: promoType
            },
            success: function(data) {

                $(".store").html(data);
            }
        });

    }

    function searchWitness1(key) {

        var str = key.trim();
        var contract = $(".renewContract").val();

        if (contract == "renewal") {

            $(".witness1Renewal").hide();
            if (str == '') {
                $(".witness1Renewal-loading").slideUp(100);
            } else {
                $.ajax({
                    type: "POST",
                    url: "functionquery.php?request=findWitness1",
                    data: {
                        str: str
                    },
                    success: function(data) {

                        data = data.trim();
                        if (data != "") {
                            $(".witness1Renewal").show().html(data);
                        } else {

                            $(".witness1Renewal").hide();
                        }
                    }
                });
            }
        } else {

            $(".witness1").hide();
            if (str == '') {
                $(".witness1-loading").slideUp(100);
            } else {
                $.ajax({
                    type: "POST",
                    url: "functionquery.php?request=findWitness1",
                    data: {
                        str: str
                    },
                    success: function(data) {

                        data = data.trim();
                        if (data != "") {
                            $(".witness1").show().html(data);
                        } else {

                            $(".witness1").hide();
                        }
                    }
                });
            }
        }
    }

    function getWitness1(empId) {

        var contract = $(".renewContract").val();
        if (contract == "renewal") {

            $("[name='witness1Renewal']").val(empId);
            $(".witness1Renewal").hide();
            $("[name = 'witness1Renewal']").css("border-color", "#ccc");

        } else {

            $("[name='witness1']").val(empId);
            $(".witness1").hide();
            $("[name = 'witness1']").css("border-color", "#ccc");
        }

    }

    function searchWitness2(key) {

        var str = key.trim();

        var contract = $(".renewContract").val();
        if (contract == "renewal") {

            $(".witness2Renewal").hide();
            if (str == '') {
                $(".witness2Renewal-loading").slideUp(100);
            } else {
                $.ajax({
                    type: "POST",
                    url: "functionquery.php?request=findWitness2",
                    data: {
                        str: str
                    },
                    success: function(data) {

                        data = data.trim();
                        if (data != "") {
                            $(".witness2Renewal").show().html(data);
                        } else {

                            $(".witness2Renewal").hide();
                        }
                    }
                });
            }
        } else {

            $(".witness2").hide();
            if (str == '') {
                $(".witness2-loading").slideUp(100);
            } else {
                $.ajax({
                    type: "POST",
                    url: "functionquery.php?request=findWitness2",
                    data: {
                        str: str
                    },
                    success: function(data) {

                        data = data.trim();
                        if (data != "") {
                            $(".witness2").show().html(data);
                        } else {

                            $(".witness2").hide();
                        }
                    }
                });
            }
        }
    }

    function getWitness2(empId) {

        var contract = $(".renewContract").val();
        if (contract == "renewal") {

            $("[name='witness2Renewal']").val(empId);
            $(".witness2Renewal").hide();
            $("[name = 'witness2Renewal']").css("border-color", "#ccc");

        } else {

            $("[name='witness2']").val(empId);
            $(".witness2").hide();
            $("[name = 'witness2']").css("border-color", "#ccc");
        }
    }

    function chkDepartment(dept) {

        if (dept == "HOME AND FASHION" || dept == "FIXRITE" || dept == "EASY FIX") {

            $(".companyDuration").show();
        } else {

            var type = $("select[name = 'contractType_select']").val();
            if (type == "Seasonal") {

                $(".companyDuration").show();
            } else {

                $(".companyDuration").hide();
            }
        }

        $.ajax({
            type: "POST",
            url: "employee_information_details.php?request=locate_vendor",
            data: {
                department: dept
            },
            success: function(data) {

                $("[name = 'vendor']").html(data);
            }
        });
    }

    function chkContractType(type) {

        if (type == "Seasonal") {

            $(".companyDuration").show();
        } else {

            $(".companyDuration").hide();
        }
    }

    function inputStartdate() {

        $("[name = 'startdate']").css("border-color", "#ccc");
        $("[name = 'eocdate']").val("");
    }

    function durationContract(eocdate) {

        var dF = $("[name = 'startdate']").val();
        var dT = eocdate;
        $("[name = 'eocdate']").css("border-color", "#ccc");

        if (dF == "") {

            $.alert.open({
                type: 'warning',
                cancel: false,
                content: "Please Fill-up Startdate First!",
                buttons: {
                    OK: 'Ok'
                },

                callback: function(button) {
                    if (button == 'OK') {

                        $("[name = 'eocdate']").val("");
                    }
                }
            });
        } else {

            $.ajax({
                type: "POST",
                url: "employee_information_details.php?request=durationContract",
                data: {
                    dF: dF,
                    dT: dT
                },
                success: function(data) {

                    data = data.trim();
                    data = data.split("||");

                    if (data[0] == "Ok") {

                        $(".duration").val(data[1]);
                    } else {

                        $.alert.open({
                            type: 'warning',
                            cancel: false,
                            content: data,
                            buttons: {
                                OK: 'Ok'
                            },

                            callback: function(button) {
                                if (button == 'OK') {

                                    $("[name = 'eocdate']").val("");
                                }
                            }
                        });
                    }
                }
            });
        }
    }

    function printPermit(empId) {

        $("#printPermit").modal({
            backdrop: 'static',
            keyboard: false
        });

        $("#printPermit").modal("show");

        $.ajax({
            type: "POST",
            url: "functionquery.php?request=printPermitRenewal",
            data: {
                empId: empId
            },
            success: function(data) {

                $(".printPermit").html(data);

                $.ajax({
                    type: "POST",
                    url: "functionquery.php?request=locateStore",
                    data: {
                        empId: empId
                    },
                    success: function(data) {

                        $("select[name = 'storeName']").html(data).trigger('change');

                        $.ajax({
                            type: "POST",
                            url: "functionquery.php?request=getEmpRecordNo",
                            data: {
                                empId: empId
                            },
                            success: function(data) {

                                $("[name = 'newest_recordNo']").val(data);

                                $.ajax({
                                    type: "POST",
                                    url: "functionquery.php?request=getPromoType",
                                    data: {
                                        empId: empId
                                    },
                                    success: function(data) {

                                        data = data.split("||");
                                        if (data[0].trim() == "Ok") {

                                            if (data[1].trim() == "STATION") {

                                                $("[name = 'dutyDays']").val("Daily");
                                            } else {

                                                $("[name = 'dutyDays']").val("");
                                            }
                                        }
                                    }
                                });
                            }
                        });
                    }
                });
            }
        });
    }

    function inputSpecialDays(specialSched) {

        if (specialSched == "") {

            $("[name = 'specialDays']").val("");
        } else {

            $("[name = 'specialDays']").prop("disabled", false);
        }
    }

    function inputDutySched() {

        $("select.dutySched").css("border-color", "#ccc");
    }

    function genPermit() {

        var empId = $("[name = 'empId']").val();
        var recordNo = $("[name = 'newest_recordNo']").val();
        var store = $("[name = 'storeName']").val();
        var dutyDays = $("[name = 'dutyDays']").val();
        var dutySched = $("[name = 'dutySched']").val();
        var specialSched = $("[name = 'specialSched']").val();
        var specialDays = $("[name = 'specialDays']").val();
        var dayOff = $("[name = 'dayOff']").val();
        var cutOff = $("[name = 'cutOff']").val();

        var table1 = "employee3";
        var table2 = "promo_record";

        if (store == "" || dutyDays == "" || dutySched == "" || dayOff == "" || (specialSched != "" && specialDays == "")) {

            $.alert.open({
                type: 'warning',
                cancel: false,
                content: "Please Fill-up Required Fields!",
                buttons: {
                    OK: 'Ok'
                },

                callback: function(button) {
                    if (button == 'OK') {

                        if (store == "") {

                            $("[name = 'store']").css("border-color", "#dd4b39");
                        }

                        if (dutyDays == "") {

                            $("[name = 'dutyDays']").css("border-color", "#dd4b39");
                        }

                        if (dutySched == "") {

                            $("[name = 'dutySched']").css("border-color", "#dd4b39");
                        }

                        if (specialSched != "" && specialDays == "") {

                            $("[name = 'specialDays']").css("border-color", "#dd4b39");
                        }

                        if (dayOff == "") {

                            $("[name = 'dayOff']").css("border-color", "#dd4b39");
                        }
                    }

                }
            });
        } else {

            dutyDays = dutyDays.replace(/ &/g, ",");
            specialDays = specialDays.replace(/ &/g, ",");

            $.ajax({
                type: "POST",
                url: "functionquery.php?request=saveDutyDetails",
                data: {
                    empId: empId,
                    recordNo: recordNo,
                    store: store,
                    dutyDays: dutyDays,
                    dutySched: dutySched,
                    specialSched: specialSched,
                    specialDays: specialDays,
                    dayOff: dayOff,
                    cutOff: cutOff
                },
                success: function(data) {

                    if (data == "success") {

                        window.open("../report/promo_permit_towork.php?recordNo=" + recordNo + "&empId=" + empId + "&store=" + store + "&dutySched=" + dutySched + "&specialSched=" + specialSched + "&dutyDays=" + dutyDays + "&specialDays=" + specialDays + "&dayoff=" + dayOff + "&table1=" + table1 + "&table2=" + table2);
                    } else {
                        alert(data);
                    }
                }
            });

        }
    }

    function printContract(empId) {

        $("#printContract").modal({
            backdrop: 'static',
            keyboard: false
        });

        $("#printContract").modal("show");

        $.ajax({
            type: "POST",
            url: "functionquery.php?request=printContractRenewal",
            data: {
                empId: empId
            },
            success: function(data) {

                $(".printContract").html(data);
            }
        });
    }

    function sssctc(type) {

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

    function genContract() {

        var empId = $("[name = 'empId']").val();
        var recordNo = $("[name = 'contract_recordNo']").val();
        var witness1 = $("[name = 'witness1Renewal']").val();
        var witness2 = $("[name = 'witness2Renewal']").val();
        var contractHeader = $("[name = 'contractHeader']").val();
        var contractDate = $("[name = 'contractDate']").val();
        var clear = $("[name = 'clear']").val();

        var clear1 = "";
        var clear2 = "";
        var table1 = "employee3";
        var table2 = "promo_record";

        if ($("#clear1").is(':checked')) {

            clear1 = "true";
        }

        if ($("#clear2").is(':checked')) {

            clear2 = "true";
        }

        var issuedOn = "";
        var sss = "";
        var cedula = "";
        if (clear1 == "true") {

            cedula = $("[name = 'cedula']").val();
            issuedOn = $("[name = 'issuedOn']").val();
            var issuedAt = $("[name = 'issuedAt']").val();
        } else {

            sss = $("[name = 'sss']").val();
            var issuedAt = $("[name = 'issuedAt']").val();
        }

        if (witness1 == "" || witness2 == "" || (clear1 == "" && clear2 == "") || contractHeader == "" || contractDate == "") {

            $.alert.open({
                type: 'warning',
                cancel: false,
                content: "Please Fill-up Required Fields!",
                buttons: {
                    OK: 'Ok'
                },

                callback: function(button) {
                    if (button == 'OK') {

                        if (witness1 == "") {

                            $("[name = 'witness1']").css("border-color", "#dd4b39");
                        }

                        if (witness2 == "") {

                            $("[name = 'witness2']").css("border-color", "#dd4b39");
                        }

                        if (contractHeader == "") {

                            $("[name = 'contractHeader']").css("border-color", "#dd4b39");
                        }

                        if (contractDate == "") {

                            $("[name = 'contractDate']").css("border-color", "#dd4b39");
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
                        buttons: {
                            OK: 'Ok'
                        },

                        callback: function(button) {
                            if (button == 'OK') {

                                if (cedula == "") {

                                    $("[name = 'cedula']").css("border-color", "#dd4b39");
                                }

                                if (issuedOn == "") {

                                    $("[name = 'issuedOn']").css("border-color", "#dd4b39");
                                }

                                if (issuedAt == "") {

                                    $("[name = 'issuedAt']").css("border-color", "#dd4b39");
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
                        buttons: {
                            OK: 'Ok'
                        },

                        callback: function(button) {
                            if (button == 'OK') {

                                if (sss == "") {

                                    $("[name = 'sss']").css("border-color", "#dd4b39");
                                }

                                if (issuedAt == "") {

                                    $("[name = 'issuedAt']").css("border-color", "#dd4b39");
                                }
                            }

                        }
                    });
                }
            }

            if (chk == "false") {

                $.ajax({
                    type: "POST",
                    url: "functionquery.php?request=submitContract",
                    data: {
                        empId: empId,
                        recordNo: recordNo,
                        witness1: witness1,
                        witness2: witness2,
                        contractHeader: contractHeader,
                        contractDate: contractDate,
                        cedula: cedula,
                        sss: sss,
                        issuedOn: issuedOn,
                        issuedAt: issuedAt,
                        clear1: clear1,
                        clear2: clear2
                    },
                    success: function(data) {

                        $.alert.open({
                            type: 'warning',
                            title: 'Info',
                            icon: 'confirm',
                            cancel: false,
                            content: "Contract Successfully Saved",
                            buttons: {
                                OK: 'Yes'
                            },

                            callback: function(button) {
                                if (button == 'OK') {

                                    window.open("../report/promo_contract.php?empId=" + empId + "&&recordNo=" + recordNo + "&&table1=" + table1 + "&&table2=" + table2);
                                }

                            }
                        });
                    }
                });
            }
        }
    }

    function back() {

        $.alert.open({
            type: 'warning',
            cancel: false,
            content: "Are you sure you want to exit?",
            buttons: {
                OK: 'Ok',
                NO: 'Not now'
            },

            callback: function(button) {
                if (button == 'OK') {

                    window.location = "?p=renewal&&module=Contract";
                }

            }
        });
    }
</script>