<?php
$cebu_host = "172.16.90.220";
$cebu_user = "root";
$cebu_pass = "itprog2013";
$cebu_db  = "pis";

$con = mysql_connect
			(
				$cebu_host,
				$cebu_user,
				$cebu_pass
			) or die("Unable to connect to the server!");
mysql_select_db($cebu_db,$con);
?>