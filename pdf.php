<?php
require_once('fpdf/fpdf.php');
require_once('fpdf/WriteTag.php');

function pdfGenerate($content, $file, $date) {
    $pdfDir = 'output-pdf';

    // Instanciation of inherited class
    $pdf = new PDF_WriteTag();
    $pdf->AliasNbPages();
    $pdf->AddPage();

    // Header start
    // Arial bold 15
    $pdf->SetFont('times','BU',20);
    // Title
    $pdf->Cell(30,10,'MID AMERICA RADON TESTING INC.',0,0);
    // Move to the right
    $pdf->Cell(150);
    // Logo
    $pdf->Image('logo.jpeg',150,6,50);

    // Line break
    $pdf->Ln(7);

    $pdf->SetFont('times','',15);
    $pdf->Cell(30,10,'9404 W. 83rd St. Overland Park, KS 66204',0,0);
    $pdf->Ln(7);

    $pdf->SetFont('times','B',15);
    $pdf->Cell(30,10,'913-469-1997               ID# 101148 ALI',0,0);
    $pdf->Ln(7);

    $pdf->SetFont('times','BU',15);
    $pdf->SetTextColor(99, 0, 255);
    $pdf->Cell(30,10,'www.midamericaradon.com',0,0, '', false, 'https://midamericaradon.com/');
    $pdf->Ln(10);

    $pdf->SetTextColor(0, 0, 0);
    $pdf->Line(0,40,250,40);
    // Header end

    $pdf->SetFont('courier','',12);

    $pdf->Cell(30,10,($content['name'] ?? ''),0,0);
    $pdf->Cell(110);
    $pdf->Cell(30,10,($content['date'] ?? ''),0,0);
    $pdf->Ln(7);

    //$pdf->Cell(30,10,($content['name'] ?? ''),0,0);
    //$pdf->Ln(7);

    $pdf->Cell(30,10,($content['address1'] ?? ''),0,0);
    $pdf->Ln(7);

    $pdf->Cell(30,10,($content['address2'] ?? ''),0,0);
    $pdf->Ln(10);

    $pdf->Cell(30);
    $pdf->SetFont('times','B',12);
    $pdf->Cell(30,10,'Client: ',0,0);
    $pdf->SetFont('courier','',12);
    $pdf->Cell(10,10,($content['client'] ?? ''),0,0);
    $pdf->Ln(7);

    $pdf->Cell(30);
    $pdf->SetFont('times','B',12);
    $pdf->Cell(30,10,'Test Address: ',0,0);
    $pdf->SetFont('courier','',12);
    $pdf->Cell(10,10,($content['address'] ?? ''),0,0);
    $pdf->Ln(15);

    $pdf->SetFont('times','BU',15);
    $pdf->Cell(30,10,'RADON TEST RESULTS:',0,0);
    $pdf->Ln(7);

    $pdf->SetFont('times','B',12);
    $pdf->Cell(30);
    $pdf->Cell(30,10,'ID# ',0,0);
    $pdf->Cell(30,10,'RADON',0,0);
    $pdf->Cell(30,10,'TEST',0,0);
    $pdf->Cell(30,10,'TEST',0,0);
    $pdf->Cell(30,10,'DEVICE',0,0);
    $pdf->Ln(5);
    $pdf->Cell(30);
    $pdf->Cell(30);
    $pdf->Cell(30,10,'LEVEL',0,0);
    $pdf->Cell(30,10,'LOCATION',0,0);
    $pdf->Cell(30,10,'LENGTH',0,0);
    $pdf->Cell(30,10,'USED',0,0);
    $pdf->Ln(7);

    foreach($content['result'] as $radonResult) {
        //print_r(count($radonResult);
        $pdf->SetFont('courier','',12);
        $pdf->Cell(30);
        $pdf->Cell(30,10,($radonResult['id'] ?? ''),0,0);
        $pdf->Cell(30,10,($radonResult['level'] ?? ''),0,0);
        $pdf->Cell(30,10,($radonResult['location'] ?? ''),0,0);
        $pdf->Cell(30,10,($radonResult['duration'] ?? ''),0,0);
        $pdf->Cell(30,10,(!empty($radonResult['id']) ? 'AC': ''),0,0);
        $pdf->Ln(7);
    }

    $pdf->Ln(5);
    $pdf->SetFont('Times','B',12);
    $pdf->MultiCell(0,5,'Use the chart below to compare your radon test results with the EPA guideline. The higher a home\'s radon level, the greater the risk to you and your family.');
    $pdf->Ln(0);

    $pdf->Cell(0,0, $pdf->Image('picture.jpg', null, null, 150),0,0);
    $pdf->Ln(10);

    $pdf->SetStyle("p","courier","N",12,"10,100,250",15);
    //$pdf->SetStyle("h1","times","N",18,"102,0,102",0);
    $pdf->SetStyle("a","times","BU",9,"0,0,255");
    $pdf->SetStyle("pers","times","I",0,"255,0,0");
    $pdf->SetStyle("place","arial","U",0,"153,0,0");

    $pdf->SetStyle("p","Times","N",12,"0,0,0",15);
    $pdf->SetStyle("h1","Times","B",18,"0,0,0",0);
    $pdf->SetStyle("vb","Times","B",0,"0,0,0");

    // Text
    $txt=" 
    <h1>RADON HEALTH RISK INFORMATION</h1><p>Radon is the second leading cause of lung cancer, after smoking. <vb>The US Environmental Protection Agency (EPA) and the Surgeon General strongly recommend taking further action when the home's test results are 4.0 pCi/l or greater. </vb>The concentration of radon in the home is measured in picocuries per liter of air (pCi/l). The National average indoor radon level is about 1.3 pCi/l. The higher a home's radon level, the greater the risk to you and your family. Smokers and former smokers are especially high risk. There are straightforward ways to fix a home's radon problems that are not too costly. Even home with very high levels can be reduced to below 4.0 pCi/l. EPA recommends that you use EPA or State-approved contractors trained to fix radon problems.</p>
    ";
    $pdf->WriteTag(0,7,$txt,0,"J",0,0);
    $pdf->Ln(5);

    if (!file_exists($pdfDir.'/'.$date)) {
        mkdir($pdfDir.'/'.$date);
    }
    $outputPath = $pdfDir.'/'.$date.'/'.pathinfo($file, PATHINFO_FILENAME).'.pdf';
    $pdf->Output($outputPath, 'F');
    //$pdf->Output();
}
?>