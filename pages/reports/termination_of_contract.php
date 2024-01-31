<?php
	
	session_start();
	include('fpdf/fpdf.php');
	include("../../../connection.php");
	date_default_timezone_set("Asia/Manila");
	
	class PDF extends FPDF
	{
		function Header(){}		
		function Footer(){}
	}	

	// Instanciation of inherited class
	$pdf = new PDF();
	$pdf->AliasNbPages();
	$pdf->AddPage("P","Letter");	
	$pdf->SetFont('Times','',12);
	$pdf->SetAutoPageBreak('on',5);	
	$pdf->SetTitle('Termination of Contract');

	$store = $_GET['store'];
	$ids = explode("*", $_GET['empIds']);
	$where = "";
	$condition = "";
	$dateToday = date("F d, Y");

	if (!empty($store)) {
		
		$where .= " AND $store= 'T'";
		$condition = "WHERE bunit_field = '$store'";
	}

	for ($i=0; $i < sizeof($ids) -1; $i++) { 
		
		$query = mysql_query("SELECT employee3.emp_id, position, promo_company, promo_department, eocdate
								FROM employee3 INNER JOIN promo_record ON employee3.emp_id=promo_record.emp_id WHERE (emp_type = 'Promo' OR emp_type = 'Promo-NESCO' OR emp_type = 'Promo-EasyL') AND current_status = 'Active' AND employee3.emp_id = '$ids[$i]' $where ORDER BY eocdate, name ASC")or die(mysql_error());
		while ($row = mysql_fetch_array($query))
		{ 	

			$bunit = mysql_query("SELECT bunit_field, business_unit FROM `locate_promo_business_unit` $condition")or die(mysql_error());
			while ($str = mysql_fetch_array($bunit)) {

				$promo = mysql_query("SELECT promo_id FROM `promo_record` WHERE `".$str['bunit_field']."` = 'T' AND emp_id = '".$row['emp_id']."'")or die(mysql_error());
				if(mysql_num_rows($promo) > 0) {
						
					$name = $nq->getApplicantName2($row['emp_id']);
					$company = $row['promo_company'];
					$department = $row['promo_department'];
					$eocdate = $row['eocdate'];
					$eocdate = new DateTime(@$row['eocdate']); 
					$day = date('l',strtotime($row['eocdate'])); 

					$pdf->SetFont('Arial','B',12);		
					$pdf->Cell(85);	

					$pdf->Ln(5);			
					$pdf->SetFont('Times','B',12);
					$pdf->Cell(0,5,'NOTICE OF TERMINATION',0,0,'C');				
					$pdf->Ln(5);
					$pdf->SetFont('Times','',12);
					$pdf->Cell(0,5,'For Promodiser - Merchandiser',0,0,'C');
					$pdf->Ln(5);
					$pdf->Cell(0,5,'Assigned at '.ucwords(strtolower($str['business_unit'])).'',0,0,'C');	
						

					$pdf->Ln(10);
					$pdf->SetX(150);
					$pdf->SetFont('Arial','B',11);	
					$pdf->Cell(1,7, 'Date:');
					$pdf->SetX(160);
					$pdf->SetFont('Arial','U',11);		
					$pdf->Cell(30 ,7,$dateToday);	
					$pdf->Ln(10);
					$pdf->Cell(5);
					$pdf->SetFont('Arial','B',11);
					$pdf->Cell(30 ,7, 'TO');
					$pdf->Cell(5);	
					$pdf->SetX(53);
					$pdf->Cell(5,7, ':');	
					$pdf->SetFont('Arial','BU',11);
					$pdf->Cell(35,7, mb_convert_encoding(ucwords(strtolower($name)), '', 'UTF-8'));
					$pdf->Ln();	
					$pdf->Cell(5);	
					$pdf->SetFont('Arial','B',11);
					$pdf->Cell(30 ,7, 'COMPANY/AGENCY:');
					$pdf->Cell(5);	
					$pdf->SetX(55);	
					$pdf->SetFont('Arial','B',11);
					$pdf->Cell(80 ,7,$department.'-'.$company);	
					$pdf->Ln(10);
					$pdf->SetFont('Arial','',11);		
					$pdf->Cell(35);	

					$pdf->Cell(15 ,7, 'Please be reminded that according to the Introductory Letter we received from your');	
					$pdf->Ln(5);
					$pdf->Cell(5);
					$pdf->Cell(15 ,7,'company/agency, your assignment on this establishment will expire on');
					$pdf->Ln(5);
					$pdf->Cell(5);		
					$pdf->SetFont('Arial','BU',12);
					$pdf->Cell(15 ,7,$day.' '.$eocdate->format('F d, Y').'.','U');
					$pdf->Ln();
					$pdf->SetFont('Arial','',11);	
					$pdf->Cell(35);
					$pdf->Cell(15 ,7, "In connection with this you are advised to yield all company properties under your care");	
					$pdf->Ln();
					$pdf->Cell(5);	
					$pdf->MultiCell(175, 5, 'and seek clearance before you leave the company premises of '.ucwords(strtolower($str['business_unit'])).' at the close of business hours on such day');	
					$pdf->Ln();	
					$pdf->Cell(35);			
					$pdf->Cell(15 ,7, 'Thank you and good luck!');	
					$pdf->Ln(15);	
					$pdf->SetFont('Arial','B',11);	
					$pdf->Cell(5);	
					$pdf->Cell(15 ,7, 'MS. MARIA NORA A. PAHANG');	
					$pdf->Ln(5);	
					$pdf->Cell(5);	
					$pdf->Cell(15 ,7, 'HRD MANAGER');	
					$pdf->Ln(30);

					//********************************* for report logs	*********************************************//

						$activity 		= "Generate Termination of Contract Report of ".$name;
						$date 			= date("Y-m-d");
						
						$time 			= date("H:i:s");
						$nq->savelogs($activity,$date,$time,$_SESSION['emp_id'],$_SESSION['username']);		

					/************************************************************************************************/
				}
			}
		}
	}

	$pdf->Output();
?>