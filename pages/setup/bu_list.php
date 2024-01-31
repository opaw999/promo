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
                <button type="button" class="btn btn-primary btn-sm" onclick="add_bu()">
                    Add Business Unit
                </button>
            </div>
        </div>

        <div class="box-body">
            <table id="dt_bu" class="table table-striped table-hover table1">
                <thead>
                    <tr>
                        <th>Business Unit Name</th>
                        <th>Status</th>
                        <th>TK Status</th>
                        <th>Appraisal Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->

</section>

<div id="addBu" class="modal fade">
    <div class="modal-dialog" style="width: 37%;">
        <div class="modal-content">
            <div class="modal-header bg-light-blue color-palette">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Add Business Unit</h4>
            </div>
            <form id="saveBU" autocomplete="off">
                <div class="modal-body">
                    <div class="addBu"></div>
                </div>
                <div class="modal-footer">
                    <span class="loadingSave"></span>
                    <button type="submit" class="btn btn-primary saveBU">Submit</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="updateBu" class="modal fade">
    <div class="modal-dialog" style="width: 40%;">
        <div class="modal-content">
            <div class="modal-header bg-light-blue color-palette">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Update Business Unit</h4>
            </div>
            <form id="saveUpdateBu" autocomplete="off">
                <div class="modal-body">
                    <div class="updateBu"></div>
                </div>
                <div class="modal-footer">
                    <span class="loadingSave"></span>
                    <button type="submit" class="btn btn-primary">Update</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function add_bu() {
        $("#addBu").modal({
            backdrop: 'static',
            keyboard: false
        });

        $("#addBu").modal("show");
        $.ajax({
            type: "POST",
            url: "functionquery.php?request=addBuForm",
            success: function(data) {

                $(".addBu").html(data);
            }
        });
    }

    function update_bu(id) {
        $("#updateBu").modal({
            backdrop: 'static',
            keyboard: false
        });

        $("#updateBu").modal("show");

        let [process, bunit_id] = id.split('-');
        console.log(bunit_id)
        $.ajax({
            type: "POST",
            url: "functionquery.php?request=updateBUform",
            data: {
                process,
                bunit_id,
            },
            success: function(data) {
                $(".updateBu").html(data);
            }
        });
    }
</script>