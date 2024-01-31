<section class="content-header">
    <h1>
        Dashboard
        <small>Control panel</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
    </ol>
</section>

<!-- Main content -->
<input type="hidden" name="dashboard" value="comeOut">
<section class="content">
    <div class="row">
        <div class="col-md-8">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-bar-chart"></i> Number of Promo per Business Unit</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-7">

                            <canvas id="pieChart" style="height:250px;"></canvas>
                            <button class="btn btn-primary btn-sm pull-right" onclick="viewDetails()">View Details ...</button>
                        </div>
                        <div class="col-md-5 table-responsive">
                            <table class="table table-bordered" style="font-size: 11px;">
                                <tr>
                                    <th>Business Unit</th>
                                    <th>Count</th>
                                    <?php

                                    $c = 0;
                                    $sqlBU = mysql_query("SELECT bunit_name, bunit_field FROM `locate_promo_business_unit` WHERE status = 'active' AND hrd_location = '$hrCode'") or die(mysql_error());
                                    while ($rowBU = mysql_fetch_array($sqlBU)) {

                                        $sqlPromo = mysql_query("SELECT DISTINCT(employee3.emp_id) AS num FROM employee3, promo_record WHERE employee3.emp_id = promo_record.emp_id AND (emp_type = 'Promo' OR emp_type = 'Promo-NESCO') AND " . $rowBU['bunit_field'] . " = 'T' AND current_status = 'Active'") or die(mysql_error());
                                        $rowPromo = mysql_num_rows($sqlPromo);

                                        echo "
                                                <tr>
                                                    <td><span class='glyphicon glyphicon-stop' style='color:" . $pieColor[$c] . "'></span> &nbsp;" . $rowBU['bunit_name'] . "</td>
                                                    <td><a href='#'>" . $rowPromo . "</a></td>
                                                </tr>
                                            ";
                                        $c++;
                                    }
                                    ?>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.box -->
        </div>
        <div class="col-md-4">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-tasks"></i> Dashboard</h3>
                </div>
                <div class="box-body">
                    <ul class="list-group">
                        <li class="list-group-item">
                            <span class="badge bg-red" id="newPromo">0</span><i class="fa fa-user-plus" style="margin-right: 5px;"></i> New Promo
                        </li>
                        <li class="list-group-item">
                            <span class="badge bg-red" id="birthdayToday">0</span><i class="fa fa-birthday-cake" style="margin-right: 5px;"></i> Birthday Today
                        </li>
                        <li class="list-group-item">
                            <span class="badge bg-red" id="activePromo">0</span><i class="fa fa-male" style="margin-right: 5px;"></i>&nbsp;&nbsp; Active Promo
                        </li>
                        <li class="list-group-item">
                            <span class="badge bg-red" id="eocToday">0</span><i class="fa fa-thumb-tack" style="margin-right: 5px;"></i>&nbsp; End of Contract Today
                        </li>
                        <li class="list-group-item">
                            <span class="badge bg-red" id="failedEpas">0</span><i class="fa fa-user-times" style="margin-right: 4px;"></i> Failed EPAS
                        </li>
                        <li class="list-group-item">
                            <a href="?p=dueContract&&module=Reports">Due Contracts Report</a>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- /.box -->
        </div>
    </div>

</section>
<script type="text/javascript">
    function viewDetails() {

        window.location = "?p=statistics&&module=Reports";
    }
</script>