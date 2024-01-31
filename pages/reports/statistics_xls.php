<?php
session_start();
include("../../../connection.php");

/****************************************************************************************************/
$filename = "Statistic Report";
header("Content-Type: application/vnd.ms-excel");
header("Content-disposition: attachment; filename=" . $filename . ".xls");

/********************************** values being passed from report_details **************************/
$date = date("F d, Y"); //date of today
$statistics = $_GET['statistics'];
$preparedBy = mysql_escape_string(strtoupper($_GET['preparedBy']));
$submittedTo = mysql_escape_string(strtoupper($_GET['submittedTo']));

?>

<!DOCTYPE html>
<html>

<head>
	<title>Status Report</title>
</head>

<body>

	<div style="width:90%;margin-left:auto;margin-right:auto">

		<br>
		<center>
			<h4>STATISTICS REPORT by <?php echo "$statistics"; ?> as of <?php echo $date; ?></h4>
		</center>
		<?php
		$total = 0;
		if ($statistics == "Business Unit") { ?>
			<table class='table table-bordered' border='1'>
				<tr>
					<th>BUSINESS UNIT</th>
					<th>GROCERY</th>
					<th>SUPERMARKET</th>
					<th>SOD</th>
					<th>HOME & FASHION</th>
					<th>FRESH MARKET</th>
					<th>FIXRITE</th>
					<th>MEDICINE PLUS</th>
					<th>ABENSON ALTURAS</th>
					<th>ABENSON ICM</th>
					<th>TOTAL</th>
				</tr>
				<tr>
					<?php
					$condition = "al_tag = 'T' and al_tal = '' and icm = '' and pm = '' and abenson_tag='' and abenson_icm='' and al_tub = '' and alta_citta=''";
					$condition2 = "(emp_type = 'Promo' OR emp_type = 'Promo-NESCO' OR emp_type = 'Promo-EasyL')";
					?>
					<td>ASCM</td>
					<td><?php echo $nq->promoCount_($condition, 'GROCERY', $condition2); ?></td>
					<td><?php echo $nq->promoCount_($condition, 'SUPERMARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_($condition, 'SOD', $condition2); ?></td>
					<td><?php echo $nq->promoCount_($condition, 'HOME AND FASHION', $condition2); ?></td>
					<td><?php echo $nq->promoCount_($condition, 'FRESH MARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_($condition, 'FIXRITE', $condition2) + $nq->promoCount_($condition, 'EASY FIX', $condition2); ?></td>
					<td><?php echo $nq->promoCount_($condition, 'MEDICINE PLUS', $condition2); ?></td>
					<td></td>
					<td></td>
					<td><?php echo $subTotal = $nq->promoCountAll_($condition, $condition2); ?></td>
				</tr>
				<tr>
					<?php
					$total += $subTotal;
					$condition = "al_tag = '' and al_tal = 'T' and icm = '' and pm = '' and abenson_tag='' and abenson_icm='' and al_tub = '' and alta_citta=''";
					$condition2 = "(emp_type = 'Promo' OR emp_type = 'Promo-NESCO' OR emp_type = 'Promo-EasyL')";
					?>
					<td>ALTAL</td>
					<td><?php echo $nq->promoCount_($condition, 'GROCERY', $condition2); ?></td>
					<td><?php echo $nq->promoCount_($condition, 'SUPERMARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_($condition, 'SOD', $condition2); ?></td>
					<td><?php echo $nq->promoCount_($condition, 'HOME AND FASHION', $condition2); ?></td>
					<td><?php echo $nq->promoCount_($condition, 'FRESH MARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_($condition, 'FIXRITE', $condition2) + $nq->promoCount_($condition, 'EASY FIX', $condition2); ?></td>
					<td><?php echo $nq->promoCount_($condition, 'MEDICINE PLUS', $condition2); ?></td>
					<td></td>
					<td></td>
					<td><?php echo $subTotal = $nq->promoCountAll_($condition, $condition2); ?></td>
				</tr>
				<tr>
					<?php
					$total += $subTotal;
					$condition = "al_tag = '' and al_tal = '' and icm = 'T' and pm = '' and abenson_tag='' and abenson_icm='' and al_tub = '' and alta_citta=''";
					$condition2 = "(emp_type = 'Promo' OR emp_type = 'Promo-NESCO' OR emp_type = 'Promo-EasyL')";
					?>
					<td>ICM</td>
					<td><?php echo $nq->promoCount_($condition, 'GROCERY', $condition2); ?></td>
					<td><?php echo $nq->promoCount_($condition, 'SUPERMARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_($condition, 'SOD', $condition2); ?></td>
					<td><?php echo $nq->promoCount_($condition, 'HOME AND FASHION', $condition2); ?></td>
					<td><?php echo $nq->promoCount_($condition, 'FRESH MARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_($condition, 'FIXRITE', $condition2) + $nq->promoCount_($condition, 'EASY FIX', $condition2); ?></td>
					<td><?php echo $nq->promoCount_($condition, 'MEDICINE PLUS', $condition2); ?></td>
					<td></td>
					<td></td>
					<td><?php echo $subTotal = $nq->promoCountAll_($condition, $condition2); ?></td>
				</tr>
				<tr>
					<?php
					$total += $subTotal;
					$condition = "al_tag = '' and al_tal = '' and icm = '' and pm = 'T' and abenson_tag='' and abenson_icm='' and al_tub = '' and alta_citta=''";
					$condition2 = "(emp_type = 'Promo' OR emp_type = 'Promo-NESCO' OR emp_type = 'Promo-EasyL')";
					?>
					<td>PM</td>
					<td><?php echo $nq->promoCount_($condition, 'GROCERY', $condition2); ?></td>
					<td><?php echo $nq->promoCount_($condition, 'SUPERMARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_($condition, 'SOD', $condition2); ?></td>
					<td><?php echo $nq->promoCount_($condition, 'HOME AND FASHION', $condition2); ?></td>
					<td><?php echo $nq->promoCount_($condition, 'FRESH MARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_($condition, 'FIXRITE', $condition2) + $nq->promoCount_($condition, 'EASY FIX', $condition2); ?></td>
					<td><?php echo $nq->promoCount_($condition, 'MEDICINE PLUS', $condition2); ?></td>
					<td></td>
					<td></td>
					<td><?php echo $subTotal = $nq->promoCountAll_($condition, $condition2); ?></td>
				</tr>
				<tr>
					<?php
					$total += $subTotal;
					$condition = "al_tag = '' and al_tal = '' and icm = '' and pm = '' and abenson_tag='' and abenson_icm='' and al_tub = 'T' and alta_citta=''";
					$condition2 = "(emp_type = 'Promo' OR emp_type = 'Promo-NESCO' OR emp_type = 'Promo-EasyL')";
					?>
					<td>ALTUB</td>
					<td><?php echo $nq->promoCount_($condition, 'GROCERY', $condition2); ?></td>
					<td><?php echo $nq->promoCount_($condition, 'SUPERMARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_($condition, 'SOD', $condition2); ?></td>
					<td><?php echo $nq->promoCount_($condition, 'HOME AND FASHION', $condition2); ?></td>
					<td><?php echo $nq->promoCount_($condition, 'FRESH MARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_($condition, 'FIXRITE', $condition2) + $nq->promoCount_($condition, 'EASY FIX', $condition2); ?></td>
					<td><?php echo $nq->promoCount_($condition, 'MEDICINE PLUS', $condition2); ?></td>
					<td></td>
					<td></td>
					<td><?php echo $subTotal = $nq->promoCountAll_($condition, $condition2); ?></td>
				</tr>
				<tr>
					<?php
					$total += $subTotal;
					$condition = "alta_citta='T'";
					$condition2 = "(emp_type = 'Promo' OR emp_type = 'Promo-NESCO' OR emp_type = 'Promo-EasyL')";
					?>
					<td>ALTA</td>
					<td><?php echo $nq->promoCount_($condition, 'GROCERY', $condition2); ?></td>
					<td><?php echo $nq->promoCount_($condition, 'SUPERMARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_($condition, 'SOD', $condition2); ?></td>
					<td><?php echo $nq->promoCount_($condition, 'HOME AND FASHION', $condition2); ?></td>
					<td><?php echo $nq->promoCount_($condition, 'FRESH MARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_($condition, 'FIXRITE', $condition2) + $nq->promoCount_($condition, 'EASY FIX', $condition2); ?></td>
					<td><?php echo $nq->promoCount_($condition, 'MEDICINE PLUS', $condition2); ?></td>
					<td></td>
					<td></td>
					<td><?php echo $subTotal = $nq->promoCountAll_($condition, $condition2); ?></td>
				</tr>
				<tr>
					<?php
					$total += $subTotal;
					$condition = "al_tag = '' and al_tal = '' and icm = '' and pm = '' and abenson_tag='T' and abenson_icm='' and al_tub = '' and alta_citta=''";
					$condition2 = "(emp_type = 'Promo' OR emp_type = 'Promo-NESCO' OR emp_type = 'Promo-EasyL')";
					?>
					<td>ABSN-ASC</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><?php echo $nq->promoCount_($condition, 'ABENSON', $condition2); ?></td>
					<td></td>
					<td><?php echo $subTotal = $nq->promoCount_($condition, 'ABENSON', $condition2); ?></td>
				</tr>
				<tr>
					<?php
					$total += $subTotal;
					$condition = "al_tag = '' and al_tal = '' and icm = '' and pm = '' and abenson_tag='' and abenson_icm='T' and al_tub = '' and alta_citta=''";
					$condition2 = "(emp_type = 'Promo' OR emp_type = 'Promo-NESCO' OR emp_type = 'Promo-EasyL')";
					?>
					<td>ABSN-ICM</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><?php echo $nq->promoCount_($condition, 'ABENSON', $condition2); ?></td>
					<td><?php echo $subTotal = $nq->promoCount_($condition, 'ABENSON', $condition2); ?></td>
				</tr>
				<tr>
					<?php
					$total += $subTotal;
					$condition = "al_tag = '' and al_tal = '' and icm = '' and pm = '' and abenson_tag='' and abenson_icm='' and al_tub = '' and fr_panglao='T' and alta_citta=''";
					$condition2 = "(emp_type = 'Promo' OR emp_type = 'Promo-NESCO' OR emp_type = 'Promo-EasyL')";
					?>
					<td>FRP</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><?php echo $nq->promoCount_($condition, 'FIXRITE', $condition2) + $nq->promoCount_($condition, 'EASY FIX', $condition2); ?></td>
					<td></td>
					<td></td>
					<td></td>
					<td><?php echo $subTotal = $nq->promoCountAll_($condition, $condition2); ?></td>
				</tr>
				<tr>
					<?php
					$total += $subTotal;
					$condition = "al_tag = 'T' and al_tal = 'T' and icm = '' and pm = '' and al_tub = '' and alta_citta=''";
					$condition2 = "(emp_type = 'Promo' OR emp_type = 'Promo-NESCO' OR emp_type = 'Promo-EasyL')";
					?>
					<td>ASCM, ALTAL</td>
					<td><?php echo $nq->promoCount_1($condition, 'GROCERY', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SUPERMARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SOD', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'HOME AND FASHION', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FRESH MARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FIXRITE', $condition2) + $nq->promoCount_1($condition, 'EASY FIX', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'MEDICINE PLUS', $condition2); ?></td>
					<td></td>
					<td></td>
					<td><?php echo $subTotal = $nq->promoCountAll_1($condition, $condition2); ?></td>
				</tr>
				<tr>
					<?php
					$total += $subTotal;
					$condition = "al_tag = 'T' and al_tal = '' and icm = 'T' and pm = '' and al_tub = '' and alta_citta=''";
					$condition2 = "(emp_type = 'Promo' OR emp_type = 'Promo-NESCO' OR emp_type = 'Promo-EasyL')";
					?>
					<td>ASCM, ICM</td>
					<td><?php echo $nq->promoCount_1($condition, 'GROCERY', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SUPERMARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SOD', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'HOME AND FASHION', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FRESH MARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FIXRITE', $condition2) + $nq->promoCount_1($condition, 'EASY FIX', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'MEDICINE PLUS', $condition2); ?></td>
					<td></td>
					<td></td>
					<td><?php echo $subTotal = $nq->promoCountAll_1($condition, $condition2); ?></td>
				</tr>
				<tr>
					<?php
					$total += $subTotal;
					$condition = "al_tag = 'T' and al_tal = '' and icm = '' and pm = 'T' and al_tub = '' and alta_citta=''";
					$condition2 = "(emp_type = 'Promo' OR emp_type = 'Promo-NESCO' OR emp_type = 'Promo-EasyL')";
					?>
					<td>ASCM, PM</td>
					<td><?php echo $nq->promoCount_1($condition, 'GROCERY', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SUPERMARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SOD', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'HOME AND FASHION', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FRESH MARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FIXRITE', $condition2) + $nq->promoCount_1($condition, 'EASY FIX', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'MEDICINE PLUS', $condition2); ?></td>
					<td></td>
					<td></td>
					<td><?php echo $subTotal = $nq->promoCountAll_1($condition, $condition2); ?></td>
				</tr>
				<tr>
					<?php
					$total += $subTotal;
					$condition = "al_tag = 'T' and al_tal = '' and icm = '' and pm = '' and al_tub = 'T' and alta_citta=''";
					$condition2 = "(emp_type = 'Promo' OR emp_type = 'Promo-NESCO' OR emp_type = 'Promo-EasyL')";
					?>
					<td>ASCM, ALTUB</td>
					<td><?php echo $nq->promoCount_1($condition, 'GROCERY', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SUPERMARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SOD', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'HOME AND FASHION', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FRESH MARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FIXRITE', $condition2) + $nq->promoCount_1($condition, 'EASY FIX', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'MEDICINE PLUS', $condition2); ?></td>
					<td></td>
					<td></td>
					<td><?php echo $subTotal = $nq->promoCountAll_1($condition, $condition2); ?></td>
				</tr>
				<tr>
					<?php
					$total += $subTotal;
					$condition = "al_tag = 'T' and al_tal = '' and icm = '' and pm = '' and al_tub = '' and alta_citta='T'";
					$condition2 = "(emp_type = 'Promo' OR emp_type = 'Promo-NESCO' OR emp_type = 'Promo-EasyL')";
					?>
					<td>ASCM, ALTA</td>
					<td><?php echo $nq->promoCount_1($condition, 'GROCERY', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SUPERMARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SOD', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'HOME AND FASHION', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FRESH MARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FIXRITE', $condition2) + $nq->promoCount_1($condition, 'EASY FIX', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'MEDICINE PLUS', $condition2); ?></td>
					<td></td>
					<td></td>
					<td><?php echo $subTotal = $nq->promoCountAll_1($condition, $condition2); ?></td>
				</tr>
				<tr>
					<?php
					$total += $subTotal;
					$condition = "al_tag = '' and al_tal = 'T' and icm = 'T' and pm = '' and al_tub = '' and alta_citta=''";
					$condition2 = "(emp_type = 'Promo' OR emp_type = 'Promo-NESCO' OR emp_type = 'Promo-EasyL')";
					?>
					<td>ALTAL, ICM</td>
					<td><?php echo $nq->promoCount_1($condition, 'GROCERY', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SUPERMARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SOD', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'HOME AND FASHION', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FRESH MARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FIXRITE', $condition2) + $nq->promoCount_1($condition, 'EASY FIX', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'MEDICINE PLUS', $condition2); ?></td>
					<td></td>
					<td></td>
					<td><?php echo $subTotal = $nq->promoCountAll_1($condition, $condition2); ?></td>
				</tr>
				<tr>
					<?php
					$total += $subTotal;
					$condition = "al_tag = '' and al_tal = 'T' and icm = '' and pm = 'T' and al_tub = '' and alta_citta=''";
					$condition2 = "(emp_type = 'Promo' OR emp_type = 'Promo-NESCO' OR emp_type = 'Promo-EasyL')";
					?>
					<td>ALTAL, PM</td>
					<td><?php echo $nq->promoCount_1($condition, 'GROCERY', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SUPERMARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SOD', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'HOME AND FASHION', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FRESH MARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FIXRITE', $condition2) + $nq->promoCount_1($condition, 'EASY FIX', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'MEDICINE PLUS', $condition2); ?></td>
					<td></td>
					<td></td>
					<td><?php echo $subTotal = $nq->promoCountAll_1($condition, $condition2); ?></td>
				</tr>
				<tr>
					<?php
					$total += $subTotal;
					$condition = "al_tag = '' and al_tal = 'T' and icm = '' and pm = '' and al_tub = 'T' and alta_citta=''";
					$condition2 = "(emp_type = 'Promo' OR emp_type = 'Promo-NESCO' OR emp_type = 'Promo-EasyL')";
					?>
					<td>ALTAL, ALTUB</td>
					<td><?php echo $nq->promoCount_1($condition, 'GROCERY', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SUPERMARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SOD', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'HOME AND FASHION', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FRESH MARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FIXRITE', $condition2) + $nq->promoCount_1($condition, 'EASY FIX', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'MEDICINE PLUS', $condition2); ?></td>
					<td></td>
					<td></td>
					<td><?php echo $subTotal = $nq->promoCountAll_1($condition, $condition2); ?></td>
				</tr>
				<tr>
					<?php
					$total += $subTotal;
					$condition = "al_tag = '' and al_tal = 'T' and icm = '' and pm = '' and al_tub = '' and alta_citta='T'";
					$condition2 = "(emp_type = 'Promo' OR emp_type = 'Promo-NESCO' OR emp_type = 'Promo-EasyL')";
					?>
					<td>ALTAL, ALTA</td>
					<td><?php echo $nq->promoCount_1($condition, 'GROCERY', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SUPERMARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SOD', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'HOME AND FASHION', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FRESH MARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FIXRITE', $condition2) + $nq->promoCount_1($condition, 'EASY FIX', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'MEDICINE PLUS', $condition2); ?></td>
					<td></td>
					<td></td>
					<td><?php echo $subTotal = $nq->promoCountAll_1($condition, $condition2); ?></td>
				</tr>
				<tr>
					<?php
					$total += $subTotal;
					$condition = "al_tag = '' and al_tal = '' and icm = 'T' and pm = 'T' and al_tub = '' and alta_citta=''";
					$condition2 = "(emp_type = 'Promo' OR emp_type = 'Promo-NESCO' OR emp_type = 'Promo-EasyL')";
					?>
					<td>ICM, PM</td>
					<td><?php echo $nq->promoCount_1($condition, 'GROCERY', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SUPERMARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SOD', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'HOME AND FASHION', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FRESH MARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FIXRITE', $condition2) + $nq->promoCount_1($condition, 'EASY FIX', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'MEDICINE PLUS', $condition2); ?></td>
					<td></td>
					<td></td>
					<td><?php echo $subTotal = $nq->promoCountAll_1($condition, $condition2); ?></td>
				</tr>
				<tr>
					<?php
					$total += $subTotal;
					$condition = "al_tag = '' and al_tal = '' and icm = 'T' and pm = '' and al_tub = 'T' and alta_citta=''";
					$condition2 = "(emp_type = 'Promo' OR emp_type = 'Promo-NESCO' OR emp_type = 'Promo-EasyL')";
					?>
					<td>ICM, ALTUB</td>
					<td><?php echo $nq->promoCount_1($condition, 'GROCERY', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SUPERMARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SOD', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'HOME AND FASHION', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FRESH MARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FIXRITE', $condition2) + $nq->promoCount_1($condition, 'EASY FIX', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'MEDICINE PLUS', $condition2); ?></td>
					<td></td>
					<td></td>
					<td><?php echo $subTotal = $nq->promoCountAll_1($condition, $condition2); ?></td>
				</tr>
				<tr>
					<?php
					$total += $subTotal;
					$condition = "al_tag = '' and al_tal = '' and icm = 'T' and pm = '' and al_tub = '' and alta_citta='T'";
					$condition2 = "(emp_type = 'Promo' OR emp_type = 'Promo-NESCO' OR emp_type = 'Promo-EasyL')";
					?>
					<td>ICM, ALTA</td>
					<td><?php echo $nq->promoCount_1($condition, 'GROCERY', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SUPERMARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SOD', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'HOME AND FASHION', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FRESH MARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FIXRITE', $condition2) + $nq->promoCount_1($condition, 'EASY FIX', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'MEDICINE PLUS', $condition2); ?></td>
					<td></td>
					<td></td>
					<td><?php echo $subTotal = $nq->promoCountAll_1($condition, $condition2); ?></td>
				</tr>
				<tr>
					<?php
					$total += $subTotal;
					$condition = "al_tag = '' and al_tal = '' and icm = '' and pm = 'T' and al_tub = 'T' and alta_citta=''";
					$condition2 = "(emp_type = 'Promo' OR emp_type = 'Promo-NESCO' OR emp_type = 'Promo-EasyL')";
					?>
					<td>PM, ALTUB</td>
					<td><?php echo $nq->promoCount_1($condition, 'GROCERY', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SUPERMARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SOD', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'HOME AND FASHION', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FRESH MARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FIXRITE', $condition2) + $nq->promoCount_1($condition, 'EASY FIX', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'MEDICINE PLUS', $condition2); ?></td>
					<td></td>
					<td></td>
					<td><?php echo $subTotal = $nq->promoCountAll_1($condition, $condition2); ?></td>
				</tr>
				<tr>
					<?php
					$total += $subTotal;
					$condition = "al_tag = '' and al_tal = '' and icm = '' and pm = 'T' and al_tub = '' and alta_citta='T'";
					$condition2 = "(emp_type = 'Promo' OR emp_type = 'Promo-NESCO' OR emp_type = 'Promo-EasyL')";
					?>
					<td>PM, ALTA</td>
					<td><?php echo $nq->promoCount_1($condition, 'GROCERY', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SUPERMARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SOD', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'HOME AND FASHION', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FRESH MARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FIXRITE', $condition2) + $nq->promoCount_1($condition, 'EASY FIX', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'MEDICINE PLUS', $condition2); ?></td>
					<td></td>
					<td></td>
					<td><?php echo $subTotal = $nq->promoCountAll_1($condition, $condition2); ?></td>
				</tr>
				<tr>
					<?php
					$total += $subTotal;
					$condition = "al_tag = '' and al_tal = '' and icm = '' and pm = '' and al_tub = 'T' and alta_citta='T'";
					$condition2 = "(emp_type = 'Promo' OR emp_type = 'Promo-NESCO' OR emp_type = 'Promo-EasyL')";
					?>
					<td>ALTUB, ALTA</td>
					<td><?php echo $nq->promoCount_1($condition, 'GROCERY', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SUPERMARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SOD', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'HOME AND FASHION', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FRESH MARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FIXRITE', $condition2) + $nq->promoCount_1($condition, 'EASY FIX', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'MEDICINE PLUS', $condition2); ?></td>
					<td></td>
					<td></td>
					<td><?php echo $subTotal = $nq->promoCountAll_1($condition, $condition2); ?></td>
				</tr>
				<tr>
					<?php
					$total += $subTotal;
					$condition = "al_tag = 'T' and al_tal = 'T' and icm = 'T' and pm = '' and al_tub = '' and alta_citta=''";
					$condition2 = "(emp_type = 'Promo' OR emp_type = 'Promo-NESCO' OR emp_type = 'Promo-EasyL')";
					?>
					<td>ASCM, ALTAL, ICM</td>
					<td><?php echo $nq->promoCount_1($condition, 'GROCERY', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SUPERMARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SOD', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'HOME AND FASHION', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FRESH MARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FIXRITE', $condition2) + $nq->promoCount_1($condition, 'EASY FIX', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'MEDICINE PLUS', $condition2); ?></td>
					<td></td>
					<td></td>
					<td><?php echo $subTotal = $nq->promoCountAll_1($condition, $condition2); ?></td>
				</tr>
				<tr>
					<?php
					$total += $subTotal;
					$condition = "al_tag = 'T' and al_tal = 'T' and icm = '' and pm = 'T' and al_tub = '' and alta_citta=''";
					$condition2 = "(emp_type = 'Promo' OR emp_type = 'Promo-NESCO' OR emp_type = 'Promo-EasyL')";
					?>
					<td>ASCM, ALTAL, PM</td>
					<td><?php echo $nq->promoCount_1($condition, 'GROCERY', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SUPERMARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SOD', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'HOME AND FASHION', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FRESH MARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FIXRITE', $condition2) + $nq->promoCount_1($condition, 'EASY FIX', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'MEDICINE PLUS', $condition2); ?></td>
					<td></td>
					<td></td>
					<td><?php echo $subTotal = $nq->promoCountAll_1($condition, $condition2); ?></td>
				</tr>
				<tr>
					<?php
					$total += $subTotal;
					$condition = "al_tag = 'T' and al_tal = 'T' and icm = '' and pm = '' and al_tub = 'T' and alta_citta=''";
					$condition2 = "(emp_type = 'Promo' OR emp_type = 'Promo-NESCO' OR emp_type = 'Promo-EasyL')";
					?>
					<td>ASCM, ALTAL, ALTUB</td>
					<td><?php echo $nq->promoCount_1($condition, 'GROCERY', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SUPERMARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SOD', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'HOME AND FASHION', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FRESH MARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FIXRITE', $condition2) + $nq->promoCount_1($condition, 'EASY FIX', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'MEDICINE PLUS', $condition2); ?></td>
					<td></td>
					<td></td>
					<td><?php echo $subTotal = $nq->promoCountAll_1($condition, $condition2); ?></td>
				</tr>
				<tr>
					<?php
					$total += $subTotal;
					$condition = "al_tag = 'T' and al_tal = 'T' and icm = '' and pm = '' and al_tub = '' and alta_citta='T'";
					$condition2 = "(emp_type = 'Promo' OR emp_type = 'Promo-NESCO' OR emp_type = 'Promo-EasyL')";
					?>
					<td>ASCM, ALTAL, ALTA</td>
					<td><?php echo $nq->promoCount_1($condition, 'GROCERY', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SUPERMARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SOD', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'HOME AND FASHION', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FRESH MARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FIXRITE', $condition2) + $nq->promoCount_1($condition, 'EASY FIX', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'MEDICINE PLUS', $condition2); ?></td>
					<td></td>
					<td></td>
					<td><?php echo $subTotal = $nq->promoCountAll_1($condition, $condition2); ?></td>
				</tr>
				<tr>
					<?php
					$total += $subTotal;
					$condition = "al_tag = 'T' and al_tal = '' and icm = 'T' and pm = 'T' and al_tub = '' and alta_citta=''";
					$condition2 = "(emp_type = 'Promo' OR emp_type = 'Promo-NESCO' OR emp_type = 'Promo-EasyL')";
					?>
					<td>ASCM, ICM, PM</td>
					<td><?php echo $nq->promoCount_1($condition, 'GROCERY', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SUPERMARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SOD', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'HOME AND FASHION', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FRESH MARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FIXRITE', $condition2) + $nq->promoCount_1($condition, 'EASY FIX', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'MEDICINE PLUS', $condition2); ?></td>
					<td></td>
					<td></td>
					<td><?php echo $subTotal = $nq->promoCountAll_1($condition, $condition2); ?></td>
				</tr>
				<tr>
					<?php
					$total += $subTotal;
					$condition = "al_tag = 'T' and al_tal = '' and icm = 'T' and pm = '' and al_tub = 'T' and alta_citta=''";
					$condition2 = "(emp_type = 'Promo' OR emp_type = 'Promo-NESCO' OR emp_type = 'Promo-EasyL')";
					?>
					<td>ASCM, ICM, ALTUB</td>
					<td><?php echo $nq->promoCount_1($condition, 'GROCERY', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SUPERMARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SOD', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'HOME AND FASHION', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FRESH MARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FIXRITE', $condition2) + $nq->promoCount_1($condition, 'EASY FIX', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'MEDICINE PLUS', $condition2); ?></td>
					<td></td>
					<td></td>
					<td><?php echo $subTotal = $nq->promoCountAll_1($condition, $condition2); ?></td>
				</tr>
				<tr>
					<?php
					$total += $subTotal;
					$condition = "al_tag = 'T' and al_tal = '' and icm = 'T' and pm = '' and al_tub = '' and alta_citta='T'";
					$condition2 = "(emp_type = 'Promo' OR emp_type = 'Promo-NESCO' OR emp_type = 'Promo-EasyL')";
					?>
					<td>ASCM, ICM, ALTA</td>
					<td><?php echo $nq->promoCount_1($condition, 'GROCERY', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SUPERMARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SOD', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'HOME AND FASHION', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FRESH MARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FIXRITE', $condition2) + $nq->promoCount_1($condition, 'EASY FIX', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'MEDICINE PLUS', $condition2); ?></td>
					<td></td>
					<td></td>
					<td><?php echo $subTotal = $nq->promoCountAll_1($condition, $condition2); ?></td>
				</tr>
				<tr>
					<?php
					$total += $subTotal;
					$condition = "al_tag = 'T' and al_tal = '' and icm = '' and pm = 'T' and al_tub = 'T' and alta_citta=''";
					$condition2 = "(emp_type = 'Promo' OR emp_type = 'Promo-NESCO' OR emp_type = 'Promo-EasyL')";
					?>
					<td>ASCM, PM, ALTUB</td>
					<td><?php echo $nq->promoCount_1($condition, 'GROCERY', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SUPERMARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SOD', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'HOME AND FASHION', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FRESH MARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FIXRITE', $condition2) + $nq->promoCount_1($condition, 'EASY FIX', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'MEDICINE PLUS', $condition2); ?></td>
					<td></td>
					<td></td>
					<td><?php echo $subTotal = $nq->promoCountAll_1($condition, $condition2); ?></td>
				</tr>
				<tr>
					<?php
					$total += $subTotal;
					$condition = "al_tag = 'T' and al_tal = '' and icm = '' and pm = 'T' and al_tub = '' and alta_citta='T'";
					$condition2 = "(emp_type = 'Promo' OR emp_type = 'Promo-NESCO' OR emp_type = 'Promo-EasyL')";
					?>
					<td>ASCM, PM, ALTA</td>
					<td><?php echo $nq->promoCount_1($condition, 'GROCERY', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SUPERMARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SOD', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'HOME AND FASHION', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FRESH MARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FIXRITE', $condition2) + $nq->promoCount_1($condition, 'EASY FIX', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'MEDICINE PLUS', $condition2); ?></td>
					<td></td>
					<td></td>
					<td><?php echo $subTotal = $nq->promoCountAll_1($condition, $condition2); ?></td>
				</tr>
				<tr>
					<?php
					$total += $subTotal;
					$condition = "al_tag = 'T' and al_tal = '' and icm = '' and pm = '' and al_tub = 'T' and alta_citta='T'";
					$condition2 = "(emp_type = 'Promo' OR emp_type = 'Promo-NESCO' OR emp_type = 'Promo-EasyL')";
					?>
					<td>ASCM, ALTUB, ALTA</td>
					<td><?php echo $nq->promoCount_1($condition, 'GROCERY', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SUPERMARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SOD', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'HOME AND FASHION', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FRESH MARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FIXRITE', $condition2) + $nq->promoCount_1($condition, 'EASY FIX', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'MEDICINE PLUS', $condition2); ?></td>
					<td></td>
					<td></td>
					<td><?php echo $subTotal = $nq->promoCountAll_1($condition, $condition2); ?></td>
				</tr>
				<tr>
					<?php
					$total += $subTotal;
					$condition = "al_tag = '' and al_tal = 'T' and icm = 'T' and pm = 'T' and al_tub = '' and alta_citta=''";
					$condition2 = "(emp_type = 'Promo' OR emp_type = 'Promo-NESCO' OR emp_type = 'Promo-EasyL')";
					?>
					<td>ALTAL, ICM, PM</td>
					<td><?php echo $nq->promoCount_1($condition, 'GROCERY', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SUPERMARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SOD', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'HOME AND FASHION', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FRESH MARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FIXRITE', $condition2) + $nq->promoCount_1($condition, 'EASY FIX', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'MEDICINE PLUS', $condition2); ?></td>
					<td></td>
					<td></td>
					<td><?php echo $subTotal = $nq->promoCountAll_1($condition, $condition2); ?></td>
				</tr>
				<tr>
					<?php
					$total += $subTotal;
					$condition = "al_tag = '' and al_tal = 'T' and icm = 'T' and pm = '' and al_tub = 'T' and alta_citta=''";
					$condition2 = "(emp_type = 'Promo' OR emp_type = 'Promo-NESCO' OR emp_type = 'Promo-EasyL')";
					?>
					<td>ALTAL, ICM, ALTUB</td>
					<td><?php echo $nq->promoCount_1($condition, 'GROCERY', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SUPERMARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SOD', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'HOME AND FASHION', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FRESH MARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FIXRITE', $condition2) + $nq->promoCount_1($condition, 'EASY FIX', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'MEDICINE PLUS', $condition2); ?></td>
					<td></td>
					<td></td>
					<td><?php echo $subTotal = $nq->promoCountAll_1($condition, $condition2); ?></td>
				</tr>
				<tr>
					<?php
					$total += $subTotal;
					$condition = "al_tag = '' and al_tal = 'T' and icm = 'T' and pm = '' and al_tub = '' and alta_citta='T'";
					$condition2 = "(emp_type = 'Promo' OR emp_type = 'Promo-NESCO' OR emp_type = 'Promo-EasyL')";
					?>
					<td> ALTAL, ICM, ALTA</td>
					<td><?php echo $nq->promoCount_1($condition, 'GROCERY', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SUPERMARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SOD', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'HOME AND FASHION', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FRESH MARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FIXRITE', $condition2) + $nq->promoCount_1($condition, 'EASY FIX', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'MEDICINE PLUS', $condition2); ?></td>
					<td></td>
					<td></td>
					<td><?php echo $subTotal = $nq->promoCountAll_1($condition, $condition2); ?></td>
				</tr>
				<tr>
					<?php
					$total += $subTotal;
					$condition = "al_tag = '' and al_tal = 'T' and icm = '' and pm = 'T' and al_tub = 'T' and alta_citta=''";
					$condition2 = "(emp_type = 'Promo' OR emp_type = 'Promo-NESCO' OR emp_type = 'Promo-EasyL')";
					?>
					<td>ALTAL, PM, ALTUB</td>
					<td><?php echo $nq->promoCount_1($condition, 'GROCERY', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SUPERMARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SOD', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'HOME AND FASHION', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FRESH MARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FIXRITE', $condition2) + $nq->promoCount_1($condition, 'EASY FIX', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'MEDICINE PLUS', $condition2); ?></td>
					<td></td>
					<td></td>
					<td><?php echo $subTotal = $nq->promoCountAll_1($condition, $condition2); ?></td>
				</tr>
				<tr>
					<?php
					$total += $subTotal;
					$condition = "al_tag = '' and al_tal = 'T' and icm = '' and pm = 'T' and al_tub = '' and alta_citta='T'";
					$condition2 = "(emp_type = 'Promo' OR emp_type = 'Promo-NESCO' OR emp_type = 'Promo-EasyL')";
					?>
					<td>ALTAL, PM, ALTA</td>
					<td><?php echo $nq->promoCount_1($condition, 'GROCERY', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SUPERMARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SOD', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'HOME AND FASHION', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FRESH MARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FIXRITE', $condition2) + $nq->promoCount_1($condition, 'EASY FIX', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'MEDICINE PLUS', $condition2); ?></td>
					<td></td>
					<td></td>
					<td><?php echo $subTotal = $nq->promoCountAll_1($condition, $condition2); ?></td>
				</tr>
				<tr>
					<?php
					$total += $subTotal;
					$condition = "al_tag = '' and al_tal = 'T' and icm = '' and pm = '' and al_tub = 'T' and alta_citta='T'";
					$condition2 = "(emp_type = 'Promo' OR emp_type = 'Promo-NESCO' OR emp_type = 'Promo-EasyL')";
					?>
					<td>ALTAL, ALTUB, ALTA</td>
					<td><?php echo $nq->promoCount_1($condition, 'GROCERY', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SUPERMARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SOD', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'HOME AND FASHION', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FRESH MARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FIXRITE', $condition2) + $nq->promoCount_1($condition, 'EASY FIX', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'MEDICINE PLUS', $condition2); ?></td>
					<td></td>
					<td></td>
					<td><?php echo $subTotal = $nq->promoCountAll_1($condition, $condition2); ?></td>
				</tr>
				<tr>
					<?php
					$total += $subTotal;
					$condition = "al_tag = '' and al_tal = '' and icm = 'T' and pm = 'T' and al_tub = 'T' and alta_citta=''";
					$condition2 = "(emp_type = 'Promo' OR emp_type = 'Promo-NESCO' OR emp_type = 'Promo-EasyL')";
					?>
					<td>ICM, PM, ALTUB</td>
					<td><?php echo $nq->promoCount_1($condition, 'GROCERY', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SUPERMARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SOD', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'HOME AND FASHION', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FRESH MARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FIXRITE', $condition2) + $nq->promoCount_1($condition, 'EASY FIX', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'MEDICINE PLUS', $condition2); ?></td>
					<td></td>
					<td></td>
					<td><?php echo $subTotal = $nq->promoCountAll_1($condition, $condition2); ?></td>
				</tr>
				<tr>
					<?php
					$total += $subTotal;
					$condition = "al_tag = '' and al_tal = '' and icm = 'T' and pm = 'T' and al_tub = '' and alta_citta='T'";
					$condition2 = "(emp_type = 'Promo' OR emp_type = 'Promo-NESCO' OR emp_type = 'Promo-EasyL')";
					?>
					<td>ICM, PM, ALTA</td>
					<td><?php echo $nq->promoCount_1($condition, 'GROCERY', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SUPERMARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SOD', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'HOME AND FASHION', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FRESH MARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FIXRITE', $condition2) + $nq->promoCount_1($condition, 'EASY FIX', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'MEDICINE PLUS', $condition2); ?></td>
					<td></td>
					<td></td>
					<td><?php echo $subTotal = $nq->promoCountAll_1($condition, $condition2); ?></td>
				</tr>
				<tr>
					<?php
					$total += $subTotal;
					$condition = "al_tag = '' and al_tal = '' and icm = 'T' and pm = '' and al_tub = 'T' and alta_citta='T'";
					$condition2 = "(emp_type = 'Promo' OR emp_type = 'Promo-NESCO' OR emp_type = 'Promo-EasyL')";
					?>
					<td>ICM, ALTUB, ALTA</td>
					<td><?php echo $nq->promoCount_1($condition, 'GROCERY', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SUPERMARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SOD', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'HOME AND FASHION', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FRESH MARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FIXRITE', $condition2) + $nq->promoCount_1($condition, 'EASY FIX', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'MEDICINE PLUS', $condition2); ?></td>
					<td></td>
					<td></td>
					<td><?php echo $subTotal = $nq->promoCountAll_1($condition, $condition2); ?></td>
				</tr>
				<tr>
					<?php
					$total += $subTotal;
					$condition = "al_tag = '' and al_tal = '' and icm = '' and pm = 'T' and al_tub = 'T' and alta_citta='T'";
					$condition2 = "(emp_type = 'Promo' OR emp_type = 'Promo-NESCO' OR emp_type = 'Promo-EasyL')";
					?>
					<td>PM, ALTUB, ALTA</td>
					<td><?php echo $nq->promoCount_1($condition, 'GROCERY', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SUPERMARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SOD', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'HOME AND FASHION', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FRESH MARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FIXRITE', $condition2) + $nq->promoCount_1($condition, 'EASY FIX', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'MEDICINE PLUS', $condition2); ?></td>
					<td></td>
					<td></td>
					<td><?php echo $subTotal = $nq->promoCountAll_1($condition, $condition2); ?></td>
				</tr>
				<tr>
					<?php
					$total += $subTotal;
					$condition = "al_tag = 'T' and al_tal = 'T' and icm = 'T' and pm = 'T' and al_tub = '' and alta_citta=''";
					$condition2 = "(emp_type = 'Promo' OR emp_type = 'Promo-NESCO' OR emp_type = 'Promo-EasyL')";
					?>
					<td>ASCM, ALTAL, ICM, PM</td>
					<td><?php echo $nq->promoCount_1($condition, 'GROCERY', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SUPERMARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SOD', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'HOME AND FASHION', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FRESH MARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FIXRITE', $condition2) + $nq->promoCount_1($condition, 'EASY FIX', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'MEDICINE PLUS', $condition2); ?></td>
					<td></td>
					<td></td>
					<td><?php echo $subTotal = $nq->promoCountAll_1($condition, $condition2); ?></td>
				</tr>
				<tr>
					<?php
					$total += $subTotal;
					$condition = "al_tag = 'T' and al_tal = 'T' and icm = 'T' and pm = '' and al_tub = 'T' and alta_citta=''";
					$condition2 = "(emp_type = 'Promo' OR emp_type = 'Promo-NESCO' OR emp_type = 'Promo-EasyL')";
					?>
					<td>ASCM, ALTAL, ICM, ALTUB</td>
					<td><?php echo $nq->promoCount_1($condition, 'GROCERY', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SUPERMARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SOD', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'HOME AND FASHION', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FRESH MARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FIXRITE', $condition2) + $nq->promoCount_1($condition, 'EASY FIX', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'MEDICINE PLUS', $condition2); ?></td>
					<td></td>
					<td></td>
					<td><?php echo $subTotal = $nq->promoCountAll_1($condition, $condition2); ?></td>
				</tr>
				<tr>
					<?php
					$total += $subTotal;
					$condition = "al_tag = 'T' and al_tal = 'T' and icm = 'T' and pm = '' and al_tub = '' and alta_citta='T'";
					$condition2 = "(emp_type = 'Promo' OR emp_type = 'Promo-NESCO' OR emp_type = 'Promo-EasyL')";
					?>
					<td>ASCM, ALTAL, ICM, ALTA</td>
					<td><?php echo $nq->promoCount_1($condition, 'GROCERY', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SUPERMARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SOD', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'HOME AND FASHION', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FRESH MARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FIXRITE', $condition2) + $nq->promoCount_1($condition, 'EASY FIX', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'MEDICINE PLUS', $condition2); ?></td>
					<td></td>
					<td></td>
					<td><?php echo $subTotal = $nq->promoCountAll_1($condition, $condition2); ?></td>
				</tr>
				<tr>
					<?php
					$total += $subTotal;
					$condition = "al_tag = 'T' and al_tal = 'T' and icm = '' and pm = 'T' and al_tub = 'T' and alta_citta=''";
					$condition2 = "(emp_type = 'Promo' OR emp_type = 'Promo-NESCO' OR emp_type = 'Promo-EasyL')";
					?>
					<td>ASCM, ALTAL, PM, ALTUB</td>
					<td><?php echo $nq->promoCount_1($condition, 'GROCERY', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SUPERMARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SOD', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'HOME AND FASHION', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FRESH MARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FIXRITE', $condition2) + $nq->promoCount_1($condition, 'EASY FIX', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'MEDICINE PLUS', $condition2); ?></td>
					<td></td>
					<td></td>
					<td><?php echo $subTotal = $nq->promoCountAll_1($condition, $condition2); ?></td>
				</tr>
				<tr>
					<?php
					$total += $subTotal;
					$condition = "al_tag = 'T' and al_tal = 'T' and icm = '' and pm = 'T' and al_tub = '' and alta_citta='T'";
					$condition2 = "(emp_type = 'Promo' OR emp_type = 'Promo-NESCO' OR emp_type = 'Promo-EasyL')";
					?>
					<td>ASCM, ALTAL, PM, ALTA</td>
					<td><?php echo $nq->promoCount_1($condition, 'GROCERY', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SUPERMARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SOD', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'HOME AND FASHION', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FRESH MARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FIXRITE', $condition2) + $nq->promoCount_1($condition, 'EASY FIX', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'MEDICINE PLUS', $condition2); ?></td>
					<td></td>
					<td></td>
					<td><?php echo $subTotal = $nq->promoCountAll_1($condition, $condition2); ?></td>
				</tr>
				<tr>
					<?php
					$total += $subTotal;
					$condition = "al_tag = 'T' and al_tal = 'T' and icm = '' and pm = '' and al_tub = 'T' and alta_citta='T'";
					$condition2 = "(emp_type = 'Promo' OR emp_type = 'Promo-NESCO' OR emp_type = 'Promo-EasyL')";
					?>
					<td>ASCM, ALTAL, ALTUB, ALTA</td>
					<td><?php echo $nq->promoCount_1($condition, 'GROCERY', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SUPERMARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SOD', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'HOME AND FASHION', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FRESH MARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FIXRITE', $condition2) + $nq->promoCount_1($condition, 'EASY FIX', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'MEDICINE PLUS', $condition2); ?></td>
					<td></td>
					<td></td>
					<td><?php echo $subTotal = $nq->promoCountAll_1($condition, $condition2); ?></td>
				</tr>
				<tr>
					<?php
					$total += $subTotal;
					$condition = "al_tag = 'T' and al_tal = '' and icm = 'T' and pm = 'T' and al_tub = 'T' and alta_citta=''";
					$condition2 = "(emp_type = 'Promo' OR emp_type = 'Promo-NESCO' OR emp_type = 'Promo-EasyL')";
					?>
					<td>ASCM, ICM, PM, ALTUB</td>
					<td><?php echo $nq->promoCount_1($condition, 'GROCERY', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SUPERMARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SOD', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'HOME AND FASHION', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FRESH MARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FIXRITE', $condition2) + $nq->promoCount_1($condition, 'EASY FIX', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'MEDICINE PLUS', $condition2); ?></td>
					<td></td>
					<td></td>
					<td><?php echo $subTotal = $nq->promoCountAll_1($condition, $condition2); ?></td>
				</tr>
				<tr>
					<?php
					$total += $subTotal;
					$condition = "al_tag = 'T' and al_tal = '' and icm = 'T' and pm = 'T' and al_tub = '' and alta_citta='T'";
					$condition2 = "(emp_type = 'Promo' OR emp_type = 'Promo-NESCO' OR emp_type = 'Promo-EasyL')";
					?>
					<td>ASCM, ICM, PM, ALTA</td>
					<td><?php echo $nq->promoCount_1($condition, 'GROCERY', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SUPERMARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SOD', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'HOME AND FASHION', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FRESH MARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FIXRITE', $condition2) + $nq->promoCount_1($condition, 'EASY FIX', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'MEDICINE PLUS', $condition2); ?></td>
					<td></td>
					<td></td>
					<td><?php echo $subTotal = $nq->promoCountAll_1($condition, $condition2); ?></td>
				</tr>
				<tr>
					<?php
					$total += $subTotal;
					$condition = "al_tag = 'T' and al_tal = '' and icm = 'T' and pm = '' and al_tub = 'T' and alta_citta='T'";
					$condition2 = "(emp_type = 'Promo' OR emp_type = 'Promo-NESCO' OR emp_type = 'Promo-EasyL')";
					?>
					<td>ASCM, ICM, ALTUB, ALTA</td>
					<td><?php echo $nq->promoCount_1($condition, 'GROCERY', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SUPERMARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SOD', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'HOME AND FASHION', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FRESH MARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FIXRITE', $condition2) + $nq->promoCount_1($condition, 'EASY FIX', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'MEDICINE PLUS', $condition2); ?></td>
					<td></td>
					<td></td>
					<td><?php echo $subTotal = $nq->promoCountAll_1($condition, $condition2); ?></td>
				</tr>
				<tr>
					<?php
					$total += $subTotal;
					$condition = "al_tag = 'T' and al_tal = '' and icm = '' and pm = 'T' and al_tub = 'T' and alta_citta='T'";
					$condition2 = "(emp_type = 'Promo' OR emp_type = 'Promo-NESCO' OR emp_type = 'Promo-EasyL')";
					?>
					<td>ASCM, PM, ALTUB, ALTA</td>
					<td><?php echo $nq->promoCount_1($condition, 'GROCERY', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SUPERMARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SOD', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'HOME AND FASHION', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FRESH MARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FIXRITE', $condition2) + $nq->promoCount_1($condition, 'EASY FIX', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'MEDICINE PLUS', $condition2); ?></td>
					<td></td>
					<td></td>
					<td><?php echo $subTotal = $nq->promoCountAll_1($condition, $condition2); ?></td>
				</tr>
				<tr>
					<?php
					$total += $subTotal;
					$condition = "al_tag = '' and al_tal = 'T' and icm = 'T' and pm = 'T' and al_tub = 'T' and alta_citta=''";
					$condition2 = "(emp_type = 'Promo' OR emp_type = 'Promo-NESCO' OR emp_type = 'Promo-EasyL')";
					?>
					<td>ALTAL, ICM, PM, ALTUB</td>
					<td><?php echo $nq->promoCount_1($condition, 'GROCERY', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SUPERMARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SOD', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'HOME AND FASHION', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FRESH MARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FIXRITE', $condition2) + $nq->promoCount_1($condition, 'EASY FIX', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'MEDICINE PLUS', $condition2); ?></td>
					<td></td>
					<td></td>
					<td><?php echo $subTotal = $nq->promoCountAll_1($condition, $condition2); ?></td>
				</tr>
				<tr>
					<?php
					$total += $subTotal;
					$condition = "al_tag = '' and al_tal = 'T' and icm = 'T' and pm = 'T' and al_tub = '' and alta_citta='T'";
					$condition2 = "(emp_type = 'Promo' OR emp_type = 'Promo-NESCO' OR emp_type = 'Promo-EasyL')";
					?>
					<td>ALTAL, ICM, PM, ALTA</td>
					<td><?php echo $nq->promoCount_1($condition, 'GROCERY', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SUPERMARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SOD', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'HOME AND FASHION', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FRESH MARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FIXRITE', $condition2) + $nq->promoCount_1($condition, 'EASY FIX', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'MEDICINE PLUS', $condition2); ?></td>
					<td></td>
					<td></td>
					<td><?php echo $subTotal = $nq->promoCountAll_1($condition, $condition2); ?></td>
				</tr>
				<tr>
					<?php
					$total += $subTotal;
					$condition = "al_tag = '' and al_tal = 'T' and icm = 'T' and pm = '' and al_tub = 'T' and alta_citta='T'";
					$condition2 = "(emp_type = 'Promo' OR emp_type = 'Promo-NESCO' OR emp_type = 'Promo-EasyL')";
					?>
					<td>ALTAL, ICM, ALTUB, ALTA</td>
					<td><?php echo $nq->promoCount_1($condition, 'GROCERY', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SUPERMARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SOD', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'HOME AND FASHION', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FRESH MARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FIXRITE', $condition2) + $nq->promoCount_1($condition, 'EASY FIX', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'MEDICINE PLUS', $condition2); ?></td>
					<td></td>
					<td></td>
					<td><?php echo $subTotal = $nq->promoCountAll_1($condition, $condition2); ?></td>
				</tr>
				<tr>
					<?php
					$total += $subTotal;
					$condition = "al_tag = '' and al_tal = 'T' and icm = '' and pm = 'T' and al_tub = 'T' and alta_citta='T'";
					$condition2 = "(emp_type = 'Promo' OR emp_type = 'Promo-NESCO' OR emp_type = 'Promo-EasyL')";
					?>
					<td>ALTAL, PM, ALTUB, ALTA</td>
					<td><?php echo $nq->promoCount_1($condition, 'GROCERY', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SUPERMARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SOD', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'HOME AND FASHION', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FRESH MARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FIXRITE', $condition2) + $nq->promoCount_1($condition, 'EASY FIX', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'MEDICINE PLUS', $condition2); ?></td>
					<td></td>
					<td></td>
					<td><?php echo $subTotal = $nq->promoCountAll_1($condition, $condition2); ?></td>
				</tr>
				<tr>
					<?php
					$total += $subTotal;
					$condition = "al_tag = '' and al_tal = '' and icm = 'T' and pm = 'T' and al_tub = 'T' and alta_citta='T'";
					$condition2 = "(emp_type = 'Promo' OR emp_type = 'Promo-NESCO' OR emp_type = 'Promo-EasyL')";
					?>
					<td>ICM, PM, ALTUB, ALTA</td>
					<td><?php echo $nq->promoCount_1($condition, 'GROCERY', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SUPERMARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SOD', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'HOME AND FASHION', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FRESH MARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FIXRITE', $condition2) + $nq->promoCount_1($condition, 'EASY FIX', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'MEDICINE PLUS', $condition2); ?></td>
					<td></td>
					<td></td>
					<td><?php echo $subTotal = $nq->promoCountAll_1($condition, $condition2); ?></td>
				</tr>
				<tr>
					<?php
					$total += $subTotal;
					$condition = "al_tag = 'T' and al_tal = 'T' and icm = 'T' and pm = 'T' and al_tub = 'T' and alta_citta=''";
					$condition2 = "(emp_type = 'Promo' OR emp_type = 'Promo-NESCO' OR emp_type = 'Promo-EasyL')";
					?>
					<td>ASCM, ALTAL, ICM, PM, ALTUB</td>
					<td><?php echo $nq->promoCount_1($condition, 'GROCERY', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SUPERMARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SOD', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'HOME AND FASHION', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FRESH MARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FIXRITE', $condition2) + $nq->promoCount_1($condition, 'EASY FIX', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'MEDICINE PLUS', $condition2); ?></td>
					<td></td>
					<td></td>
					<td><?php echo $subTotal = $nq->promoCountAll_1($condition, $condition2); ?></td>
				</tr>
				<tr>
					<?php
					$total += $subTotal;
					$condition = "al_tag = 'T' and al_tal = 'T' and icm = 'T' and pm = 'T' and al_tub = '' and alta_citta='T'";
					$condition2 = "(emp_type = 'Promo' OR emp_type = 'Promo-NESCO' OR emp_type = 'Promo-EasyL')";
					?>
					<td>ASCM, ALTAL, ICM, PM, ALTA</td>
					<td><?php echo $nq->promoCount_1($condition, 'GROCERY', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SUPERMARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SOD', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'HOME AND FASHION', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FRESH MARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FIXRITE', $condition2) + $nq->promoCount_1($condition, 'EASY FIX', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'MEDICINE PLUS', $condition2); ?></td>
					<td></td>
					<td></td>
					<td><?php echo $subTotal = $nq->promoCountAll_1($condition, $condition2); ?></td>
				</tr>
				<tr>
					<?php
					$total += $subTotal;
					$condition = "al_tag = 'T' and al_tal = 'T' and icm = 'T' and pm = '' and al_tub = 'T' and alta_citta='T'";
					$condition2 = "(emp_type = 'Promo' OR emp_type = 'Promo-NESCO' OR emp_type = 'Promo-EasyL')";
					?>
					<td>ASCM, ALTAL, ICM, ALTUB, ALTA</td>
					<td><?php echo $nq->promoCount_1($condition, 'GROCERY', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SUPERMARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SOD', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'HOME AND FASHION', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FRESH MARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FIXRITE', $condition2) + $nq->promoCount_1($condition, 'EASY FIX', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'MEDICINE PLUS', $condition2); ?></td>
					<td></td>
					<td></td>
					<td><?php echo $subTotal = $nq->promoCountAll_1($condition, $condition2); ?></td>
				</tr>
				<tr>
					<?php
					$total += $subTotal;
					$condition = "al_tag = 'T' and al_tal = 'T' and icm = '' and pm = 'T' and al_tub = 'T' and alta_citta='T'";
					$condition2 = "(emp_type = 'Promo' OR emp_type = 'Promo-NESCO' OR emp_type = 'Promo-EasyL')";
					?>
					<td>ASCM, ALTAL, PM, ALTUB, ALTA</td>
					<td><?php echo $nq->promoCount_1($condition, 'GROCERY', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SUPERMARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SOD', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'HOME AND FASHION', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FRESH MARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FIXRITE', $condition2) + $nq->promoCount_1($condition, 'EASY FIX', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'MEDICINE PLUS', $condition2); ?></td>
					<td></td>
					<td></td>
					<td><?php echo $subTotal = $nq->promoCountAll_1($condition, $condition2); ?></td>
				</tr>
				<tr>
					<?php
					$total += $subTotal;
					$condition = "al_tag = 'T' and al_tal = '' and icm = 'T' and pm = 'T' and al_tub = 'T' and alta_citta='T'";
					$condition2 = "(emp_type = 'Promo' OR emp_type = 'Promo-NESCO' OR emp_type = 'Promo-EasyL')";
					?>
					<td>ASCM, ICM, PM, ALTUB, ALTA</td>
					<td><?php echo $nq->promoCount_1($condition, 'GROCERY', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SUPERMARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SOD', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'HOME AND FASHION', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FRESH MARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FIXRITE', $condition2) + $nq->promoCount_1($condition, 'EASY FIX', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'MEDICINE PLUS', $condition2); ?></td>
					<td></td>
					<td></td>
					<td><?php echo $subTotal = $nq->promoCountAll_1($condition, $condition2); ?></td>
				</tr>
				<tr>
					<?php
					$total += $subTotal;
					$condition = "al_tag = '' and al_tal = 'T' and icm = 'T' and pm = 'T' and al_tub = 'T' and alta_citta='T'";
					$condition2 = "(emp_type = 'Promo' OR emp_type = 'Promo-NESCO' OR emp_type = 'Promo-EasyL')";
					?>
					<td>ALTAL, ICM, PM, ALTUB, ALTA</td>
					<td><?php echo $nq->promoCount_1($condition, 'GROCERY', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SUPERMARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SOD', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'HOME AND FASHION', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FRESH MARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FIXRITE', $condition2) + $nq->promoCount_1($condition, 'EASY FIX', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'MEDICINE PLUS', $condition2); ?></td>
					<td></td>
					<td></td>
					<td><?php echo $subTotal = $nq->promoCountAll_1($condition, $condition2); ?></td>
				</tr>
				<tr>
					<?php
					$total += $subTotal;
					$condition = "al_tag = 'T' and al_tal = 'T' and icm = 'T' and pm = 'T' and al_tub = 'T' and alta_citta='T'";
					$condition2 = "(emp_type = 'Promo' OR emp_type = 'Promo-NESCO' OR emp_type = 'Promo-EasyL')";
					?>
					<td>ASCM, ALTAL, ICM, PM, ALTUB, ALTA</td>
					<td><?php echo $nq->promoCount_1($condition, 'GROCERY', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SUPERMARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'SOD', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'HOME AND FASHION', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FRESH MARKET', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'FIXRITE', $condition2) + $nq->promoCount_1($condition, 'EASY FIX', $condition2); ?></td>
					<td><?php echo $nq->promoCount_1($condition, 'MEDICINE PLUS', $condition2); ?></td>
					<td></td>
					<td></td>
					<td><?php echo $subTotal = $nq->promoCountAll_1($condition, $condition2); ?></td>
				</tr>
				<?php
				$total += $subTotal;
				?>
				<tr>
					<td colspan="10" align="right">Total</td>
					<td><?php echo $total; ?></td>
				</tr>
				<table>
					<tr></tr>
					<tr></tr>
					<tr>
						<td align="right">Prepared By:</td>
						<td><u><b>
									<center><?php echo $preparedBy; ?></center>
								</b></u></td>
					</tr>
					<tr>
						<td></td>
						<td>
							<center>Promo/Merchandiser Coordinator</center>
						</td>
					</tr>
					<tr></tr>
					<tr></tr>
					<tr>
						<td align="right">Submitted To:</td>
						<td><u><b>
									<center><?php echo $submittedTo; ?><center>
								</b></u></td>
					</tr>
					<tr>
						<td></td>
						<td>
							<center>HRD Manager</center>
						</td>
					</tr>
				</table>
			</table>
		<?php	} ?>
	</div>

</body>

</html>

<?php
//********************************* for report logs	*********************************************//

$activity 		= "Generate Promo Statistic Report for " . date("F d, Y");
$date 			= date("Y-m-d");
$time 			= date("H:i:s");
$nq->savelogs($activity, $date, $time, $_SESSION['emp_id'], $_SESSION['username']);

/************************************************************************************************/
?>