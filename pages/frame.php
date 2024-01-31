<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="shortcut icon" href="../images/system/hrlogo.png" />
  <title>HRMS</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="bootstrap/css/font-awesome.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="bootstrap/css/ionicons.css">
  <!-- Jquery UI -->
  <link rel="stylesheet" href="js/jquery-ui.min.css">
  <!-- Select2 -->
  <link rel="stylesheet" href="plugins/select2/select2.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="plugins/DataTables/datatables.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">
  <!-- Alert Messages -->
  <link href="plugins/alert/css/alert.css" rel="stylesheet" />
  <link href="plugins/alert/themes/default/theme.css" rel="stylesheet" />

</head>

<style type="text/css">
  .fieldReq {
    color: #f56954;
  }

  td.details-control {
    background: url('images/datatables/details_open.png') no-repeat center center;
    cursor: pointer;
  }

  tr.shown td.details-control {
    background: url('images/datatables/details_close.png') no-repeat center center;
  }
</style>
<?php
$m = date('m');
if ($m == 12) { ?>
  <style type="text/css">
    /* customizable snowflake styling */
    .snowflake {
      /* color: #2bb1db; */
      font-size: 2em;
      font-family: Arial, sans-serif;
      text-shadow: 0 0 0px;
    }

    @-webkit-keyframes snowflakes-fall {
      0% {
        top: -10%
      }

      100% {
        top: 100%
      }
    }

    @-webkit-keyframes snowflakes-shake {

      0%,
      100% {
        -webkit-transform: translateX(0);
        transform: translateX(0)
      }

      50% {
        -webkit-transform: translateX(80px);
        transform: translateX(80px)
      }
    }

    @keyframes snowflakes-fall {
      0% {
        top: -10%
      }

      100% {
        top: 100%
      }
    }

    @keyframes snowflakes-shake {

      0%,
      100% {
        transform: translateX(0)
      }

      50% {
        transform: translateX(80px)
      }
    }

    .snowflake {
      position: fixed;
      top: -10%;
      z-index: 9999;
      -webkit-user-select: none;
      -moz-user-select: none;
      -ms-user-select: none;
      user-select: none;
      cursor: default;
      -webkit-animation-name: snowflakes-fall, snowflakes-shake;
      -webkit-animation-duration: 10s, 3s;
      -webkit-animation-timing-function: linear, ease-in-out;
      -webkit-animation-iteration-count: infinite, infinite;
      -webkit-animation-play-state: running, running;
      animation-name: snowflakes-fall, snowflakes-shake;
      animation-duration: 10s, 3s;
      animation-timing-function: linear, ease-in-out;
      animation-iteration-count: infinite, infinite;
      animation-play-state: running, running
    }

    .snowflake:nth-of-type(0) {
      left: 1%;
      -webkit-animation-delay: 0s, 0s;
      animation-delay: 0s, 0s
    }

    .snowflake:nth-of-type(1) {
      left: 10%;
      -webkit-animation-delay: 1s, 1s;
      animation-delay: 1s, 1s
    }

    .snowflake:nth-of-type(2) {
      left: 20%;
      -webkit-animation-delay: 6s, .5s;
      animation-delay: 6s, .5s
    }

    .snowflake:nth-of-type(3) {
      left: 30%;
      -webkit-animation-delay: 4s, 2s;
      animation-delay: 4s, 2s
    }

    .snowflake:nth-of-type(4) {
      left: 40%;
      -webkit-animation-delay: 2s, 2s;
      animation-delay: 2s, 2s
    }

    .snowflake:nth-of-type(5) {
      left: 50%;
      -webkit-animation-delay: 8s, 3s;
      animation-delay: 8s, 3s
    }

    .snowflake:nth-of-type(6) {
      left: 60%;
      -webkit-animation-delay: 6s, 2s;
      animation-delay: 6s, 2s
    }

    .snowflake:nth-of-type(7) {
      left: 70%;
      -webkit-animation-delay: 2.5s, 1s;
      animation-delay: 2.5s, 1s
    }

    .snowflake:nth-of-type(8) {
      left: 80%;
      -webkit-animation-delay: 1s, 0s;
      animation-delay: 1s, 0s
    }

    .snowflake:nth-of-type(9) {
      left: 90%;
      -webkit-animation-delay: 3s, 1.5s;
      animation-delay: 3s, 1.5s
    }

    .snowflake:nth-of-type(10) {
      left: 25%;
      -webkit-animation-delay: 2s, 0s;
      animation-delay: 2s, 0s
    }

    .snowflake:nth-of-type(11) {
      left: 65%;
      -webkit-animation-delay: 4s, 2.5s;
      animation-delay: 4s, 2.5s
    }

    .snowflake:nth-of-type(12) {
      left: 25%;
      -webkit-animation-delay: 2s, 0s;
      animation-delay: 2s, 0s
    }

    /* Array of 12 random colors */
    .snowflake:nth-of-type(1) {
      color: #cce5ff;

    }

    .snowflake:nth-of-type(2) {
      color: #99ccff;

    }

    .snowflake:nth-of-type(3) {
      color: #66b3ff;

    }

    .snowflake:nth-of-type(4) {
      color: #cce5ff;

    }

    .snowflake:nth-of-type(5) {
      color: #99ccff;

    }

    .snowflake:nth-of-type(6) {
      color: #66b3ff;

    }

    .snowflake:nth-of-type(7) {
      color: #66b3ff;

    }

    .snowflake:nth-of-type(8) {
      color: #99ccff;

    }

    .snowflake:nth-of-type(9) {
      color: #cce5ff;

    }

    .snowflake:nth-of-type(10) {
      color: #66b3ff;

    }

    .snowflake:nth-of-type(11) {
      color: #99ccff;

    }

    .snowflake:nth-of-type(12) {
      color: #cce5ff;

    }
  </style>
  <div class="snowflakes" aria-hidden="true">
    <div class="snowflake">❅</div>
    <div class="snowflake">❅</div>
    <div class="snowflake">❅</div>
    <div class="snowflake">❅</div>
    <div class="snowflake">❅</div>
    <div class="snowflake">❅</div>
    <div class="snowflake">❅</div>
    <div class="snowflake">❅</div>
    <div class="snowflake">❅</div>
    <div class="snowflake">❅</div>
    <div class="snowflake">❅</div>
    <div class="snowflake">❅</div>
  </div>
<?php } ?>

<body class="hold-transition <?php echo $skinClass; ?> sidebar-mini">
  <div class="row" style="background-color:#333; color:#FFF; font-size:25px;height:37px; width:101.1%"> &nbsp; &nbsp; Human Resource Management System [ Placement ]</div>
  <div class="row" style="background-color:#090; height:5px;width:101.1%">&nbsp;</div>
  <div class="wrapper">

    <header class="main-header">
      <!-- Logo -->
      <a href="?p=dashboard" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>P</b></span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b>Promo</b> Module</span>
      </a>
      <!-- Header Navbar: style can be found in header.less -->
      <nav class="navbar navbar-static-top">
        <div class="navbar-custom-menu">
          <ul class="nav navbar-nav">
            <!-- User Account: style can be found in dropdown.less -->
            <?php

            // $allow = mysql_query("SELECT * FROM franchise_access where emp_id = '".$_SESSION['emp_id']."'")or die(mysql_error());
            // if (mysql_num_rows($allow) > 0) {

            //     echo '<li class="user-menu"> <a href="../franchise/placement/">Franchise</a></li>';
            // }

            $hrAccount = mysql_query("SELECT * FROM timekeeping.promo_hr_start WHERE empId = '" . $_SESSION['emp_id'] . "'") or die(mysql_error());
            if (mysql_num_rows($hrAccount)) {

              echo '<li><a href="http://172.16.161.34/timekeeping/others/declare/index.php?id=' . $_SESSION['emp_id'] . '&&status=in&&type=hrPromo&&type2=' . $_SESSION['type'] . '">H.R. Account</a></li>';
            }
            ?>
            <li class="user-menu"> <a href='../placement/'>Placement </a></li>

            <li class="dropdown user user-menu">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <img src="<?php echo $_SESSION['photo']; ?>" class="user-image" alt="User Image">
                <span class="hidden-xs"><?php echo $_SESSION['username']; ?></span>
              </a>
              <ul class="dropdown-menu">
                <!-- User image -->
                <li class="user-header">
                  <img src="<?php echo $_SESSION['photo']; ?>" class="img-circle" alt="User Image">
                  <p> <?php echo $_SESSION['name'] . " <br> " . $_SESSION['position']; ?>

                  </p>
                </li>
                <!-- Menu Footer-->
                <li class="user-footer">
                  <div class="pull-left">
                    <a href="?p=changeAccount&&module=Promo" class="btn btn-default btn-flat">Change Account Details</a>
                  </div>
                  <div class="pull-right">
                    <a href="../logout.php?com=logout" class="btn btn-default btn-flat">Log out</a>
                  </div>
                </li>
              </ul>
            </li>
          </ul>
        </div>
      </nav>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
      <!-- sidebar: style can be found in sidebar.less -->
      <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="sidebar-form">
          <div class="input-group">
            <input name="searchPromo" class="form-control" id="searchPromo" placeholder="Search..." type="text">
            <span class="input-group-btn">
              <button type="submit" name="search" id="search-btn" class="btn btn-flat" onclick="searchPromo()"><i class="fa fa-search"></i>
              </button>
            </span>
          </div>
        </div>
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">

          <?php

          include("menu.php");

          ?>
        </ul>
      </section>
      <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->

      <?php

      include("pages/$template");
      ?>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <?php

    include("footer.php");
    include("control-sidebar.php");
    include("js.php");
    include("general_function.php");
    ?>

  </div>
  <!-- ./wrapper -->

</body>

</html>