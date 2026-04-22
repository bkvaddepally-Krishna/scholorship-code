<?php
require('fpdf.php');

function hallTicket($student){
 $pdf=new FPDF();
 $pdf->AddPage();
 $pdf->SetFont('Arial','B',16);

 $pdf->Cell(40,10,'HALL TICKET');
 $pdf->Ln();

 $pdf->SetFont('Arial','',12);
 $pdf->Cell(40,10,$student['name']);

 $pdf->Output();
}
?>