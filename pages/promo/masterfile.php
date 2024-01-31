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

    <?php

    $store = $department = $promoType = $company = $status = "";

    if (!empty($_GET['store'])) {
        $store = $_GET['store'];
    }

    if (!empty($_GET['department'])) {
        $department = $_GET['department'];
    }

    if (!empty($_GET['promoType'])) {
        $promoType = $_GET['promoType'];
    }

    if (!empty($_GET['company'])) {
        $company = $_GET['company'];
    }

    if (!empty($_GET['status'])) {
        $status = $_GET['status'];
    }
    ?>

    <!-- Default box -->
    <div class="box box-primary">
        <div class="box-header with-border">
            <button type="button" class="btn btn-primary btn-sm pull-left" onclick="filterPromo()" style="margin-right: 10px;">
                <span class="glyphicon glyphicon-filter"></span> Show Filter
            </button>
            <?php if ($hrCode == "asc") : ?>
                <span class="pull-left">
                    <span style="margin-right:30px;"><label readonly=""><span>LEGEND :</span></label></span>
                    <?php
                    $stores = mysql_query("SELECT * FROM  `locate_promo_business_unit` WHERE status = 'active' AND appraisal_status = 'active' AND hrd_location = '$hrCode' ORDER BY bunit_acronym");

                    while ($bU = mysql_fetch_array($stores)) {

                        if ($bU['bunit_acronym'] == 'ALP') {
                            echo ' <span style="margin-right:30px;"><label readonly=""><span class="text-red">' . $bU['bunit_acronym'] . '</span><span> - Panglao</span></label></span>';
                        } else if ($bU['bunit_acronym'] == 'ALTAL') {
                            echo ' <span style="margin-right:30px;"><label readonly=""><span class="text-red">' . $bU['bunit_acronym'] . '</span><span> - Talibon</span></label></span>';
                        } else if ($bU['bunit_acronym'] == 'ALTUB') {
                            echo ' <span style="margin-right:30px;"><label readonly=""><span class="text-red">' . $bU['bunit_acronym'] . '</span><span> - Tubigon</span></label></span>';
                        } else if ($bU['bunit_acronym'] == 'ASCM') {
                            echo ' <span style="margin-right:30px;"><label readonly=""><span class="text-red">' . $bU['bunit_acronym'] . '</span><span> - A-Mall</span></label></span>';
                        } else {
                            echo ' <span style="margin-right:30px;"><label readonly=""><span class="text-red">' . $bU['bunit_acronym'] . '</span><span> - ' . $bU['bunit_name'] . '</span></label></span>';
                        }
                    }
                    ?>
                </span>
            <?php else : ?>
                <span class="pull-left">
                    <span style="margin-right:30px;"><label readonly=""><span>LEGEND :</span></label></span>
                    <span style="margin-right:30px;"><label readonly=""><span class="text-red">MAN</span><span> - Collonade Mandaue</span></label></span>
                    <span style="margin-right:30px;"><label readonly=""><span class="text-red">COL</span><span> - Collonade Colon</span></label></span>
                </span>
            <?php endif; ?>
        </div>

        <div class="box-body">
            <input type="hidden" name="tempStore" value="<?php echo $store; ?>">
            <input type="hidden" name="tempDepartment" value="<?php echo $department; ?>">
            <input type="hidden" name="tempPromoType" value="<?php echo $promoType; ?>">
            <input type="hidden" name="tempCompany" value="<?php echo $company; ?>">
            <input type="hidden" name="tempStatus" value="<?php echo $status; ?>">

            <table id="masterfile" class="table table-striped table-hover table1">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Company</th>
                        <th>Store</th>
                        <th>Department</th>
                        <th>Position</th>
                        <th>PromoType</th>
                        <th>Status</th>
                    </tr>
                </thead>
            </table>
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->

</section>

<div id="filterPromo" class="modal fade">
    <div class="modal-dialog" style="width: 37%;">
        <div class="modal-content">
            <div class="modal-header bg-light-blue color-palette">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Filter Promo</h4>
            </div>
            <div class="modal-body">
                <div class="filterPromo"></div>
            </div>
            <div class="modal-footer">
                <span class="loadingSave"></span>
                <button type="button" class="btn btn-primary" onclick="promoFilter()"><i class="glyphicon glyphicon-filter"></i> Filter</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script type="text/javascript">
    function filterPromo() {

        $("#filterPromo").modal({
            backdrop: 'static',
            keyboard: false
        });

        $("#filterPromo").modal("show");

        $.ajax({
            type: "POST",
            url: "functionquery.php?request=filterPromo",
            success: function(data) {

                $(".filterPromo").html(data);
            }
        });
    }

    function locateDept(id) {

        $("[name = 'department']").css("border-color", "#ccc");
        $.ajax({
            type: "POST",
            url: "functionquery.php?request=locateDepartment",
            data: {
                id: id
            },
            success: function(data) {

                $("[name = 'department']").html(data);
            }
        });
    }

    function promoFilter() {

        var store = $("[name = 'store']").val().split("/");
        var department = $("[name = 'department']").val();
        var promoType = $("[name = 'promoType']").val();
        var company = $("[name = 'company']").val();

        if (store == "") {

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

                    }

                }
            });
        } else {

            window.location = "?p=masterfile&&module=Promo&&store=" + store[1].trim() + "&&department=" + department + "&&promoType=" + promoType + "&&company=" + company;
        }
    }
</script>