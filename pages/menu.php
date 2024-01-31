<li class="treeview <?php if ($process == 'dashboard') {
                      echo 'active';
                    } ?>">
  <a href="?p=dashboard">
    <i class="fa fa-dashboard"></i> <span>Dashboard</span>
  </a>
</li>

<?php

$accessPromo = mysql_query("SELECT usertype FROM promo_user WHERE emp_id = '$loginId' AND user_status = 'active'") or die(mysql_error());
$accP = mysql_fetch_array($accessPromo);

$promoUserType = $accP['usertype'];

$accessMenu = mysql_query("SELECT DISTINCT(menu_name), icon FROM `promo_menu` WHERE $promoUserType = 'yes' ORDER BY menu_name;") or die(mysql_error());
while ($accM = mysql_fetch_array($accessMenu)) { ?>

  <li class="treeview <?php if ($module == $accM['menu_name']) {
                        echo 'active';
                      } ?>">
    <a href="#">
      <i class="<?php echo $accM['icon']; ?>"></i>
      <span><?php echo $accM['menu_name']; ?></span>
      <i class="fa fa-angle-left pull-right"></i>
    </a>
    <ul class="treeview-menu">
      <?php

      $accessSubMenu = mysql_query("SELECT submenu_name, process FROM `promo_menu` WHERE $promoUserType = 'yes' AND menu_name = '" . $accM['menu_name'] . "'") or die(mysql_error());
      while ($accSM = mysql_fetch_array($accessSubMenu)) {

        $classM = "";
        $classC = "";
        if ($process == $accSM['process']) {

          $classM = "active";
          $classC = "text-aqua";
        }
      ?>

        <li class="<?php echo $classM; ?>"><a href="?p=<?php echo $accSM['process']; ?>&&module=<?php echo $accM['menu_name']; ?>"><i class="fa fa-circle-o <?php echo $classC; ?>"></i><?php echo $accSM['submenu_name']; ?></a></li> <?php
                                                                                                                                                                                                                                      }
                                                                                                                                                                                                                                        ?>
    </ul>
  </li> <?php
      }
        ?>

<li class="treeview <?php if ($process == 'import') {
                      echo 'active';
                    }; ?>">
  <a href="?p=import">
    <i class="fa fa-file"></i> <span>Import</span>
  </a>
</li>

<li class="treeview <?php ($process == 'aboutUs' ? 'active' : '') ?>">
  <a href="?p=aboutUs&&module=Promo">
    <i class="fa fa-fw fa-info-circle"></i> <span>About Us</span>
  </a>
</li>

<li class="treeview <?php ($process == 'userGuide' ? 'active' : '') ?>">
  <a href="images/userguide.pdf" target="_blank">
    <i class="fa fa-fw fa-map-o"></i> <span>User Guide</span>
  </a>
</li>