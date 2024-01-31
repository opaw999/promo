<?php
session_start();
include("../../../connection.php");

/****************************************************************************************************/		
	$filename = "Statistic Report";	
	header( "Content-Type: application/vnd.ms-excel" );
	header( "Content-disposition: attachment; filename=".$filename.".xls");

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

		<div style="width:90%;margin-left;auto;margin-right:auto">	
			
			<br>
			<center><h4>STATISTICS REPORT by <?php echo "$statistics";?> as of <?php echo $date;?></h4></center>
			<?php
				$total = 0;
			if($statistics == "Business Unit"){ ?>
				<table class='table table-bordered' border='1'>
					<tr>
						<th>BUSINESS UNIT</th>
						<th>GROCERY</th>
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
							$condition = "al_tag = 'T' and al_tal = '' and icm = '' and pm = '' and abenson_tag='' and abenson_icm='' and al_tub = ''";
						?>
						<td>COL-COL</td>
						<td><?php echo $nq->promoCount($condition,'GROCERY'); ?></td>						
						<td><?php echo $nq->promoCount($condition,'SOD'); ?></td>
						<td><?php echo $nq->promoCount($condition,'HOME AND FASHION'); ?></td>
						<td><?php echo $nq->promoCount($condition,'FRESH MARKET'); ?></td>
						<td><?php echo $nq->promoCount($condition,'FIXRITE') + $nq->promoCount($condition,'EASY FIX'); ?></td>
						<td><?php echo $nq->promoCount($condition,'MEDICINE PLUS'); ?></td>
						<td></td>
						<td></td>
						<td><?php echo $subTotal = $nq->promoCountAll($condition); ?></td>
					</tr>
					<tr>
						<?php 
							$total += $subTotal;
							$condition = "al_tag = '' and al_tal = 'T' and icm = '' and pm = '' and abenson_tag='' and abenson_icm='' and al_tub = ''";
						?>
						<td>COL-MAN</td>
						<td><?php echo $nq->promoCount($condition,'GROCERY'); ?></td>						
						<td><?php echo $nq->promoCount($condition,'SOD'); ?></td>
						<td><?php echo $nq->promoCount($condition,'HOME AND FASHION'); ?></td>
						<td><?php echo $nq->promoCount($condition,'FRESH MARKET'); ?></td>
						<td><?php echo $nq->promoCount($condition,'FIXRITE') + $nq->promoCount($condition,'EASY FIX'); ?></td>
						<td><?php echo $nq->promoCount($condition,'MEDICINE PLUS'); ?></td>
						<td></td>
						<td></td>
						<td><?php echo $subTotal = $nq->promoCountAll($condition); ?></td>
					</tr>
					<tr>
						<?php 
							$total += $subTotal;
							$condition = "al_tag = 'T' and al_tal = '' and icm = 'T' and pm = '' and abenson_tag='' and abenson_icm='' and al_tub = ''";
						?>
						<td>COL-COL, COL-MAN</td>
						<td><?php echo $nq->promoCount($condition,'GROCERY'); ?></td>						
						<td><?php echo $nq->promoCount($condition,'SOD'); ?></td>
						<td><?php echo $nq->promoCount($condition,'HOME AND FASHION'); ?></td>
						<td><?php echo $nq->promoCount($condition,'FRESH MARKET'); ?></td>
						<td><?php echo $nq->promoCount($condition,'FIXRITE') + $nq->promoCount($condition,'EASY FIX'); ?></td>
						<td><?php echo $nq->promoCount($condition,'MEDICINE PLUS'); ?></td>
						<td></td>
						<td></td>
						<td><?php echo $subTotal = $nq->promoCountAll($condition); ?></td>
					</tr>
						<?php 
							$total += $subTotal;
						?>
						
					<tr>
						<td colspan="9" align="right">Total</td>
						<td><?php echo $total; ?></td>
					</tr>
					<table>
						<tr></tr>
						<tr></tr>
						<tr>
							<td align="right">Prepared By:</td>
							<td><u><b><center><?php echo $preparedBy; ?></center></b></u></td>
						</tr>
						<tr>
							<td></td>
							<td><center>Promo/Merchandiser Coordinator</center></td>
						</tr>
						<tr></tr>
						<tr></tr>
						<tr>
							<td align="right">Submitted To:</td>
							<td><u><b><center><?php echo $submittedTo; ?><center></b></u></td>
						</tr>
						<tr>
							<td></td>
							<td><center>HRD Manager</center></td>
						</tr>
					</table>
				</table>
			<?php	} ?>
		</div>	

	</body>

</html>

<?php
//********************************* for report logs	*********************************************//

	$activity 		= "Generate Promo Statistic Report for ".date("F d, Y");
	$date 			= date("Y-m-d");
	$time 			= date("H:i:s");
	$nq->savelogs($activity,$date,$time,$_SESSION['emp_id'],$_SESSION['username']);	

/************************************************************************************************/
?>