<style type="text/css">
    .search-results {

        box-shadow: 5px 5px 5px #ccc;
        margin-top: 1px;
        margin-left: 0px;
        background-color: #F1F1F1;
        width: 89%;
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
                    <div class="form-group"> <i class="text-red">*</i>
                        <label>Search Applicant</label>
                        <div class="input-group">
                            <input class="form-control" type="text" name="employee" onkeyup="nameSearch(this.value)" autocomplete="off" placeholder="Lastname or Firstname or Emp.ID">
                            <span class="input-group-addon"><i class="fa fa-user"></i></span>
                        </div>
                        <div class="search-results" style="display: none;"></div>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <input type="text" name="status" class="form-control" disabled="">
                    </div>
                    <div class="form-group"> <i class="text-red">*</i>
                        <label>Recruitment Process</label>
                        <select name="recProcess" class="form-control" disabled="" onchange="inputField(this.name)">
                            <option value=""> -- Select -- </option>
                            <option value="initial_completion"> Initial Completion </option>
                            <option value="exam"> For Examination </option>
                            <option value="interview"> For Interview </option>
                            <option value="training"> For Training </option>
                            <option value="final_completion"> For Final Completion </option>
                            <option value="orientation"> For Orientation </option>
                            <option value="hiring"> For Hiring </option>
                            <option value="deployment"> For Deployment </option>
                        </select>
                    </div>
                    <div class="form-group"> <i class="text-red">*</i>
                        <label>Position</label>
                        <select name="position" class="form-control" style="width: 100%;" tabindex="-1" aria-hidden="true" disabled="" onchange="inputField(this.name)">
                            <option value=""> --Select-- </option>
                            <?php

                            $posSelect = mysql_query("SELECT position FROM `positions` WHERE (tag = 'active' OR tag = '') ORDER BY position ASC") or die(mysql_error());
                            while ($row = mysql_fetch_array($posSelect)) { ?>

                                <option value="<?php echo $row['position']; ?>"><?php echo $row['position']; ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="box-footer">
                    <div class="pull-right">
                        <button type="button" class="btn btn-primary" onclick="submitFields()"><i class="fa fa-tag"></i> Tag Now!</button>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>

</section>
<script type="text/javascript">
    function nameSearch(key) {

        var str = key.trim();
        $(".search-results").hide();
        if (str == '') {
            $(".search-results-loading").slideUp(100);
        } else {
            $.ajax({
                type: "POST",
                url: "functionquery.php?request=findApplicant",
                data: {
                    str: str
                },
                success: function(data) {

                    if (data) {
                        $(".search-results").show().html(data);
                    }
                }
            });
        }
    }

    function getEmpId(empId) {

        $("[name='employee']").val(empId);
        $(".search-results").hide();
        $("[name = 'employee']").css("border-color", "#ccc");

        empId = empId.split("*");

        $.ajax({
            type: "POST",
            url: "functionquery.php?request=getApplicantStat",
            data: {
                empId: empId[0].trim()
            },
            success: function(data) {

                data = data.split("||");
                if (data[0].trim() == "Ok") {

                    $("[name = 'status']").val(data[1].trim());
                } else {

                    alert(data);
                }
            }
        });

        enabledFields();
    }

    function enabledFields() {

        $("[name = 'recProcess']").prop("disabled", false);
        $("[name = 'position']").prop("disabled", false);
    }

    function submitFields() {

        var empId = $("[name='employee']").val().split("*");
        var recProcess = $("[name='recProcess']").val();
        var position = $("[name='position']").val();

        if (empId[0].trim() == "" || recProcess == "" || position == "") {

            $.alert.open({
                type: 'warning',
                cancel: false,
                content: "Please Fill-up Required Fields!",
                buttons: {
                    OK: 'Ok'
                },

                callback: function(button) {
                    if (button == 'OK') {

                        if (empId[0].trim() == "") {

                            $("[name = 'employee']").css("border-color", "#dd4b39");
                        }

                        if (recProcess == "") {

                            $("[name = 'recProcess']").css("border-color", "#dd4b39");
                        }

                        if (position == "") {

                            $("[name = 'position']").css("border-color", "#dd4b39");
                        }
                    }

                }
            });
        } else {

            $.ajax({
                type: "POST",
                url: "functionquery.php?request=getCurrentStat",
                data: {
                    empId: empId[0].trim()
                },
                success: function(data) {

                    data = data.split("||");
                    if (data[0].trim() == "Ok") {

                        if ((data[1].trim() == "blacklisted" && recProcess != "deployment") || (data[1].trim() == "Active" && recProcess != "deployment")) {

                            $.alert.open({
                                type: 'warning',
                                cancel: false,
                                content: "Cannot be process! Applicant/Employee is " + data[1].trim() + ".",
                                buttons: {
                                    OK: 'Ok'
                                },

                                callback: function(button) {
                                    if (button == 'OK') {

                                        var loc = document.location;
                                        window.location = loc;
                                    }

                                }
                            });
                        } else {

                            $.ajax({
                                type: "POST",
                                url: "functionquery.php?request=tagToRecruitment",
                                data: {
                                    empId: empId[0].trim(),
                                    recProcess: recProcess,
                                    position: position
                                },
                                success: function(data) {

                                    data = data.trim();
                                    if (data == "Ok") {

                                        var msg = "";

                                        if (recProcess == "initial_completion") {

                                            msg = "Applicant may process his/her initial requirements.";
                                        } else if (recProcess == "exam") {

                                            msg = "Applicant is now in examination process.";
                                        } else if (recProcess == "interview") {

                                            msg = "Applicant is now in interview process.";
                                        } else if (recProcess == "training") {

                                            msg = "Applicant is now in training process.";
                                        } else if (recProcess == "final_completion") {

                                            msg = "Applicant is now in final completion process.";
                                        } else if (recProcess == "orientation") {

                                            msg = "Applicant is now in orientation process.";
                                        } else if (recProcess == "hiring") {

                                            msg = "Applicant is now in hiring process.";
                                        }

                                        var loc = document.location;
                                        $.alert.open({
                                            type: 'warning',
                                            title: 'Info',
                                            icon: 'confirm',
                                            cancel: false,
                                            content: msg,
                                            buttons: {
                                                OK: 'Yes'
                                            },

                                            callback: function(button) {
                                                if (button == 'OK') {

                                                    window.location = loc;
                                                }

                                            }
                                        });

                                    } else {

                                        alert(data);
                                    }
                                }
                            });
                        }
                    } else {

                        alert(data);
                    }
                }
            });
        }
    }
</script>