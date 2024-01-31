<?php
	
	session_start();
	include('fpdf/fpdf.php');
	include("../../../connection.php");

	class PDF extends FPDF
	{
		function Header(){}		
		function Footer(){}
	}

	// Instanciation of inherited class
	$pdf = new PDF();
	$pdf->AliasNbPages();
	$pdf->SetFont('Times','',12);	
	$pdf->SetTitle('Termination of Contract');

	$ids = explode("*", $_GET['empIds']);
	for ($i=0; $i < sizeof($ids) -1; $i++) { 
		
		$query = mysql_query("SELECT employee3.emp_id, position, promo_company, promo_department, eocdate
								FROM employee3 INNER JOIN promo_record ON employee3.emp_id=promo_record.emp_id WHERE (emp_type = 'Promo' OR emp_type = 'Promo-NESCO' OR emp_type = 'Promo-EasyL') AND current_status = 'Active' AND employee3.emp_id = '$ids[$i]' ORDER BY eocdate, name ASC")or die(mysql_error());
		while ($row = mysql_fetch_array($query))
		{
			$storeName = "";
			$ctr = 0;
			$bunit = mysql_query("SELECT bunit_field, bunit_acronym FROM `locate_promo_business_unit`")or die(mysql_error());
			while ($str = mysql_fetch_array($bunit)) {

				$promo = mysql_query("SELECT promo_id FROM `promo_record` WHERE `".$str['bunit_field']."` = 'T' AND emp_id = '".$row['emp_id']."'")or die(mysql_error());
				if(mysql_num_rows($promo) > 0) {
					$ctr++;

					if($ctr == 1){

						$storeName = $str['bunit_acronym'];
					} else {

						$storeName .= ", ".$str['bunit_acronym'];
					}
				}
			}

			$name = utf8_decode(ucwords(strtolower($nq->getPromoName($row['emp_id']))));

			$pdf->AddPage("P","Letter");
			$pdf->Image('../../dist/img/promo/alturas.png',40,10,120);
			$pdf->Ln(19);
			$pdf->Cell(15);
			$pdf->Cell(0,7,date('F d, Y'));

			$pdf->Ln(11);
			$pdf->Cell(15);
			$pdf->SetFont('Times','B',12);
			$pdf->Cell(0,7,$row['promo_company']);
			$pdf->Ln(8);
			$pdf->Cell(15);
			$pdf->Cell(0,7,'ATTENTION:	    PERSONNEL DEPARTMENT');

			$pdf->Ln(11);
			$pdf->Cell(15);
			$pdf->SetFont('Times','',12);
			$pdf->Cell(0,7,"Dear Sir/Ma'am:");

			$pdf->Ln(14);
			$pdf->Cell(15);
			$pdf->MultiCell(160, 7,"Please be reminded that based on your Intro Letter, the termination of contract of the ".strtolower($row['position'])." handling your product, will take effect on the date stated below:");
			
			$pdf->Ln(9);
			$pdf->Cell(15);
			$pdf->Cell(45 ,7, 'NAME','1',0);
			$pdf->Cell(58 ,7, 'PRODUCT', '1',0);
			// $pdf->Cell(35 ,7, 'OUTLET', '1',0);
			$pdf->Cell(50 ,7, 'OUTLET', '1',0);
			$pdf->Cell(17 ,7, 'EOC', '1','1');

			$pdf->SetFont('Times','',10);
			$pdf->Cell(15);

			$pdf->Cell(45 ,7,$name,'1');
			$pdf->Cell(58 ,7, $row['promo_company'], '1');
			$pdf->Cell(50 ,7, ucwords(strtolower($storeName)), '1');
			$pdf->Cell(17 ,7, date('m/d/y',strtotime($row['eocdate'])), '1',1);	  		
	  		$pdf->Cell(15);

			$pdf->SetFont('Times','',12);
			$pdf->Ln(8);
			$pdf->Cell(15);
			$pdf->MultiCell(160, 7,"In view of the above, if you find his / her performance commendable for renewal, we would like to request your good office to send us an Introductory Letter. Otherwise, please endorse a possible applicant as replacement.");

			$pdf->Ln(5);
			$pdf->Cell(15);
			$pdf->MultiCell(160, 7,"Kindly fax your Introductory Letter in this number (038) 501-9245 or you may email at corporatehrd@alturasbohol.com.");

			$pdf->Ln(5);
			$pdf->Cell(15);
			$pdf->MultiCell(160, 7,"Thank you!");

			$pdf->Ln(5);
			$pdf->Cell(15);
			$pdf->MultiCell(160, 7,"Very respectfully yours,");

			$pdf->SetFont('Times','B',12);
			$pdf->Ln(5);
			$pdf->Cell(15);
			$pdf->MultiCell(160, 7,"ALTURAS SUPERMARKET CORPORATION");

			$pdf->Ln(7);
			$pdf->Cell(15);
			$pdf->MultiCell(160, 7,ucwords(strtolower($nq->getPromoInchargeName($_SESSION['emp_id']))));

			$pdf->SetFont('Times','',12);
			$pdf->Ln(0);
			$pdf->Cell(15);
			$pdf->MultiCell(160, 7,"HRD - Promo Transaction");

			$pdf->SetFont('Times','',12);
			$pdf->Ln(5);
			$pdf->Cell(15);
			$pdf->MultiCell(160, 7,"Noted By:");

			$pdf->SetFont('Times','B',12);
			$pdf->Ln(8);
			$pdf->Cell(15);
			$pdf->MultiCell(160, 7,"Maria Nora A. Pahang");

			$pdf->SetFont('Times','',12);
			$pdf->Ln(0);
			$pdf->Cell(15);
			$pdf->MultiCell(160, 7,"HRD Manager");

			//********************************* for report logs	*********************************************//

				$activity 		= "Generate Termination of Contract Report of ".$name;
				$date 			= date("Y-m-d");
				
				$time 			= date("H:i:s");
				$nq->savelogs($activity,$date,$time,$_SESSION['emp_id'],$_SESSION['username']);		

			/************************************************************************************************/
		
		}
	}

	$pdf->Output();
?>