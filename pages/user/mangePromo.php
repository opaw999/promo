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
            <?php if ($hrCode == "asc") : ?>
                <span class="pull-left">
                    <span style="margin-right:30px;"><label readonly=""><span>LEGEND :</span></label></span>
                    <span style="margin-right:30px;"><label readonly=""><span class="text-red">ASCM</span><span> - A-Mall</span></label></span>
                    <span style="margin-right:30px;"><label readonly=""><span class="text-red">ALTAL</span><span> - Talibon</span></label></span>
                    <span style="margin-right:30px;"><label readonly=""><span class="text-red">ALTUB</span><span> - Tubigon</span></label></span>
                    <span style="margin-right:30px;"><label readonly=""><span class="text-red">ICM</span><span> - Island City Mall</span></label></span>
                    <span style="margin-right:30px;"><label readonly=""><span class="text-red">PMDS</span><span> - Marcela</span></label></span>
                    <span style="margin-right:30px;"><label readonly=""><span class="text-red">CDC</span><span> - CDC</span></label></span>
                    <span style="margin-right:30px;"><label readonly=""><span class="text-red">BER</span><span> - Berama</span></label></span>
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
            <table id="managePromo" class="table table-striped table-hover table1">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Username</th>
                        <th>Usertype</th>
                        <th>Status</th>
                        <th>Login</th>
                        <th>Store</th>
                        <th>Department</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>Name</th>
                        <th>Username</th>
                        <th>Usertype</th>
                        <th>Status</th>
                        <th>Login</th>
                        <th>Store</th>
                        <th>Department</th>
                        <th>Action</th>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- /.box-body -->
    </div>
    <!-- /.box -->

</section>
<script type="text/javascript">
    function userAction(userNo, request) {

        var messej = "";

        if (request == 'resetPass') {
            messej = 'Are you sure to reset password for this User Account?';
        } else if (request == 'activateAccount') {
            messej = 'Are you sure to activate this User Account?';
        } else if (request == 'deactivateAccount') {
            messej = 'Are you sure to deactivate this User Account?';
        } else if (request == 'deleteAccount') {
            messej = 'Are you really sure to delete this User Account?';
        } else {

            messej = "";
        }

        $.alert.open({
            type: 'warning',
            cancel: false,
            content: messej,
            buttons: {
                OK: 'Yes',
                NO: 'Not now'
            },

            callback: function(button) {
                if (button == 'OK') {

                    $.ajax({
                        type: "POST",
                        url: "functionquery.php?request=" + request,
                        data: {
                            userNo: userNo
                        },
                        success: function(data) {

                            var loc = document.location;
                            $.alert.open({
                                type: 'warning',
                                title: 'Info',
                                icon: 'confirm',
                                cancel: false,
                                content: data,
                                buttons: {
                                    OK: 'Yes'
                                },

                                callback: function(button) {
                                    if (button == 'OK') {

                                        window.location = loc;
                                    }

                                }
                            });
                        }
                    });
                }

            }
        });
    }
</script>