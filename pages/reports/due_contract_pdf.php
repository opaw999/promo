<?php
	session_start();
	include("../../../connection.php");
	include('fpdf/fpdf.php');

	mysql_query ("set character_set_results='utf8'");

	class PDF extends FPDF
	{
		function Header()
		{								
			$this->SetFont('Arial','B',12);
			$this->Ln();
			$this->Cell(145);
			$this->Cell(30 ,7, "DUE CONTRACTS REPORT",0,0,'C');
			$this->Ln();
			$this->Cell(145);
			$this->Cell(30 ,7, "as of today ".date('F d, Y'),0,0,'C');
			$this->SetFont('Arial','B',10);	
			//$this->Cell(101);
			//$this->Cell(40 ,6,date_format(@$sd, 'F d, Y')." to ".date_format(@$ed, 'F d, Y'));
			$this->Ln(10);	
			$this->SetFont('Arial','B',10);
			$this->Cell(12 ,7, 'NO',1);
			$this->Cell(55 ,7, "NAME",1);
			$this->Cell(27 ,7, "EMPTYPE",1);		
			$this->Cell(25 ,7, "STARTDATE ",1);
			$this->Cell(22 ,7, 'EOCDATE',1);
			$this->Cell(45 ,7, "POSITION",1);
			$this->Cell(110 ,7, "BUSINESS UNIT",1);
			$this->Cell(40 ,7, "DEPARTMENT",1);
			$this->Ln();
		}
		
		function Footer()
		{
			// Position at 1.8 cm from bottom		
			$this->SetY(-18); //-20	
			// Arial italic 8
			$this->SetFont('Arial','I',8);
			// Page number
			$this->Cell(4);	
			$this->Cell(0,10,'Date printed: '.date('M d, Y',strtotime(date("Y-m-d"))),0,0,'L');		
			// Position at 1.8 cm from bottom		
			$this->SetY(-18); //-20	
			// Arial italic 8
			$this->SetFont('Arial','I',8);
			// Page number
			$this->Cell(130);	
			$this->Cell(0,10,'Prepared by: '.$_SESSION['name'],0,0,'L');		
			// Position at 1.8 cm from bottom
			$this->SetY(-18);
			// Arial italic 8
			$this->SetFont('Arial','I',8);
			// Page number
			$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'R');
		}

		function changeDateFormat($changeformatto,$date){
			$datearr = explode("-", $date);
			$month = $datearr[0];
			$day = $datearr[1];
			$yr = $datearr[2];
			$converted = "";
			switch($changeformatto){
				case 'Y-m-d': 
					$converted = $yr."-".$month."-".$day;
				break;
			}
			return $converted;
		}	
	}	

	// Instanciation of inherited class
	$pdf = new PDF();
	$pdf->AliasNbPages();
	$pdf->AddPage("L","Legal");	
	$pdf->SetTitle("DUE CONTRACTS REPORT");	
	$pdf->SetFont('Times','',12);	
	
	$today = date("Y-m-d");
	
	$dept 	= $_GET['department']; 
	$store	= $_GET['store'];
	$bunit 	= '';
	if ($store != "") {
		
		$s	 	= explode("/",$store);
		$bunit	= $s[1];
	}

	$where = '';
	if ($bunit != "") {
		
		$where .= " AND $bunit = 'T'";
	} 

	if ($dept != "") {
		
		$where .= " AND promo_department = '$dept'";
	}

	$query = mysql_query("SELECT employee3.emp_id, name, position, startdate, eocdate, emp_type, promo_company, promo_department, al_tag, al_tal, icm, pm, abenson_tag, abenson_icm, cdc, berama, al_tub, colc, colm
							FROM employee3 INNER JOIN promo_record ON employee3.emp_id = promo_record.emp_id
								AND current_status = 'Active'
								AND emp_type LIKE 'Promo%'
								AND eocdate < '$today'
								AND hr_location = 'asc'
								$where
								GROUP BY promo_record.emp_id
								ORDER BY name ASC
	")or die(mysql_error());

	$ctr = 0;			
	while($row = mysql_fetch_array($query))
	{	
		$pdf->SetFont('Arial','',9);
		$ctr++;
		
		// get store name
		$storeName = "";
		$loop = 0;
		$store_name = mysql_query("SELECT bunit_field, bunit_name FROM `locate_promo_business_unit`")or die(mysql_error());
		while ($str = mysql_fetch_array($store_name)) {

		  	$promo = mysql_query("SELECT promo_id FROM `promo_record` WHERE `".$str['bunit_field']."` = 'T' AND emp_id = '".$row['emp_id']."'")or die(mysql_error());
		  	if(mysql_num_rows($promo) > 0) {
		    	$loop++;

		    	if($loop == 1){

		      		$storeName = $str['bunit_name'];
		    	} else {

		      		$storeName .= ", ".$str['bunit_name'];
		    	}
		  	}
		}

		$pdf->SetFillColor(200,220,255);
		$pdf->Cell(12, 7, $ctr,1);
		$pdf->Cell(55, 7, utf8_decode(ucwords(strtolower($row['name']))),1);
		$pdf->Cell(27, 7, $row['emp_type'],1);		
		$pdf->Cell(25, 7, $nq->ChangeDateFormat("m/d/Y",$row['startdate']),1);
		$pdf->Cell(22, 7, $nq->ChangeDateFormat("m/d/Y",$row['eocdate']),1);
		$pdf->Cell(45, 7, ucwords(strtolower($row['position'])),1);
		$pdf->Cell(110, 7, $storeName,1);
		$pdf->Cell(40, 7, $row['promo_department'],1);
		$pdf->Ln();
	}

	$pdf->Output();
?> 