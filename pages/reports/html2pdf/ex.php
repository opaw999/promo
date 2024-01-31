<?php
require('html_table.php');
include("../../config.php");
$q = mysql_query("select emp_id, name from employee3 limit 10");

$htmlTable=
"<TABLE>
<TR>
<TD width='41'>S. No.</TD>
<TD width='95'>Name</TD>
</TR>"; 
while($r = mysql_fetch_array($q)){ 
"<TR>
<TD>".$r['emp_id']."</TD>
<TD>".$r['name']."</TD>
</TR>";
}
"</TABLE>";

$pdf=new PDF_HTML_Table();
$pdf->AddPage();
$pdf->SetFont('Arial','',10);
$pdf->WriteHTML("Start of the HTML table.<BR>$htmlTable<BR>End of the table.");
$pdf->Output();
?>
