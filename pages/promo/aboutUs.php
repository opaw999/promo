<section class="content-header">
    <h1 class="info-box-number" style="text-align: center;">
        <?php echo $subMenu; ?>
    </h1>
    <!-- <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#"><?php echo $module; ?></a></li>
        <li class="active"><?php echo $subMenu; ?></li>
    </ol> -->
</section>
<section class="content">
    <!-- Default box -->
    <div class="row">
        <div class="col-md-12">
            <h4 class="text-light-blue" style="text-align: justify;padding:20px;padding-top:0px;">
                <i>
                    HRMS is an online system used by the HRD staff in managing the records of all employees.
                    One significant module presented is the Promo Module which comprises namely:
                    Blacklisted Employee's, Contract, Outlet, Promo Employee's Masterfile, Reports,
                    Resignation/Termination, Setups, User Accounts, and Utilities.
                </i>
            </h4>
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Contact Us </h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">

                            <p class="text-light-blue text-center">
                                <i>For inquiries, please contact the ff:</i>
                            </p>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box" style="box-shadow: none;">
                                <span class="info-box-icon bg-orange" style="height: 150px;">
                                    <i class="fa fa-fw fa-map-marker" style="margin-top: 50%;"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-number">HEAD OFFICE</span>
                                    <p>
                                        AGC Corporate Center, North Wing ICM Bldg.,
                                        Dampas District, Tagbilaran City, Bohol
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box" style="box-shadow: none;">
                                <span class="info-box-icon bg-green" style="height: 150px;">
                                    <i class="fa fa-fw fa-phone" style="margin-top: 50%;"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-number">IP PHONES</span>
                                    <p>
                                        1819 - GC <br>
                                        1844 - HRMS, Timekeeping, CMS<br>
                                        1821 - EBM, GO<br>
                                        1847 - FARMS, ATP<br>
                                        1822 - TMS, BR, Institutional, CWO, Leasing/PMS<br>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box" style="box-shadow: none;">
                                <span class="info-box-icon bg-aqua" style="height: 150px;">
                                    <i class="fa fa-fw fa-envelope" style="margin-top: 50%;"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-number">EMAIL</span>
                                    <a href="mailto:itsysdev@alturasbohol.com">itsysdev@alturasbohol.com</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Our Team</h3>
                </div>
                <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-4">
                    <?php
                    $team   = array('43864-2013', '21114-2013', '01476-2015', '02897-2023', '06359-2013', '01186-2023');
                    $i      = 0;
                    foreach ($team as $value) {
                        $i++;
                        $app    = mysql_query("SELECT * FROM `applicant` WHERE app_id = '$value'") or die(mysql_error());
                        $row    = mysql_fetch_array($app);
                        $emp3   = mysql_query("SELECT * FROM `employee3` WHERE emp_id = '$value'") or die(mysql_error());
                        $row1   = mysql_fetch_array($emp3);
                        $name   = ucwords(strtolower($row['lastname'] . ', ' . $row['firstname'] . ' ' . substr($row['middlename'], 0, 1) . '. ' . $row['suffix']));
                        $col    = ($i == 1 || $i == 2) ? '12' : '6';

                        echo    '<div class="col-md-' . $col . '">
                                    <div class="box-body box-profile">
                                        <img class="profile-user-img img-responsive img-circle" src="' . $row['photo'] . '" alt="User profile picture" style="width: 100px;height: 100px;">
                                        <h3 class="profile-username text-center">' . $name . '</h3>';
                        if ($value == '43864-2013') {
                            echo        '<p class="text-muted text-center">CPA, CIA, CSCU, CISA, REB, REA, CICA, CrFA</p>';
                        }
                        echo            '<p class="text-muted text-center">' . $row1['position'] . '</p>
                                    </div> 
                                </div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</section>