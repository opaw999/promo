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
    <div class="box box-primary">
        <div class="box-header with-border">
            <div class="pull-right">
                <button type="button" class="btn btn-primary btn-sm" onclick="add_dept()">
                    Add Department
                </button>
            </div>
        </div>

        <div class="box-body">
            <table id="dt_department" class="table table-hover">
                <thead>
                    <tr>
                        <th>Business Unit</th>
                        <th>Department</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</section>

<div id="addDept" class="modal fade">
    <div class="modal-dialog" style="width: 30%;">
        <div class="modal-content">
            <div class="modal-header bg-light-blue color-palette">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Add Department to Store</h4>
            </div>
            <form id="saveDept" autocomplete="off">
                <div class="modal-body">
                    <div class="addDept"></div>
                </div>
                <div class="modal-footer">
                    <span class="loadingSave"></span>
                    <button type="submit" class="btn btn-primary saveDept">Submit</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>



<script>
    function add_dept() {
        $("#addDept").modal({
            backdrop: 'static',
            keyboard: false
        });

        $("#addDept").modal("show");
        $.ajax({
            type: "POST",
            url: "functionquery.php?request=addDeptForm",
            success: function(data) {

                $(".addDept").html(data);
            }
        });
    }

    function dept_action(id) {

        let [process, dept_id] = id.split('-');
        $.ajax({
            type: "POST",
            url: "functionquery.php?request=deptAction",
            data: {
                process,
                dept_id,
            },
            success: function(data) {
                let response = JSON.parse(data);
                if (response.status == 'success') {
                    $.alert.open({
                        type: 'warning',
                        title: 'Info',
                        icon: 'confirm',
                        cancel: false,
                        content: `Department has been removed.`,
                        buttons: {
                            OK: 'Yes'
                        },
                        callback: function(button) {
                            if (button == 'OK') {
                                location.reload();
                            }
                        }
                    });
                } else {
                    alert(data);
                }
            }
        });
    }
</script>