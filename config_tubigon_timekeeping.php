<?php
$tub_host = "172.16.96.1";
$tub_user = "hrms";
$tub_pass = "itprog2013";
$tub_db  = "timekeeping";

$con = mysql_connect(
				$tub_host,
				$tub_user,
				$tub_pass
			) or die("Unable to connect to the server!");
mysql_select_db($tub_db,$con);
