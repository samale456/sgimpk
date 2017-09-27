<?php

require_once('../../src/modelo/ModCategoria.php'); 
require_once('../../tcpdf/tcpdf.php');
header('Content-Type: text/html; charset=UTF-8'); 



// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {
		//Page header
		public function Header() {
				// get the current page break margin
				$bMargin = $this->getBreakMargin();
				// get current auto-page-break mode
				$auto_page_break = $this->AutoPageBreak;
				// disable auto-page-break
				$this->SetAutoPageBreak(false, 0);
				// set bacground image
				$img_file = K_PATH_IMAGES.'reporte.png';
				$this->Image($img_file, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
				// restore auto-page-break status
				$this->SetAutoPageBreak($auto_page_break, $bMargin);
				// set the starting point for the page content
				$this->setPageMark();
		}
}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('TCPDF Example 051');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(0);
$pdf->SetFooterMargin(0);

// remove default footer
$pdf->setPrintFooter(false);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
		require_once(dirname(__FILE__).'/lang/eng.php');
		$pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set font
$pdf->SetFont('times', '', 10);

// add a page
$pdf->AddPage();

 $categoria = new ModCategoria();
	$content = '

	';
 
	$content .= '
	<br>
	<br>
	<br>  
	<br>
	<br>
		<Title> LISTADO DE CATEGORÍAS </Title>
	 <br>
	 <br>
			<table cellpadding="5">
				<thead>
					<tr>
						<th><b>NOMBRE</b></th>
						<th><b>DESCRIPCIÓN</b></th>
						<th><b>LINEA DE INVESTIGACIÓN</b></th>
						<th><b>ESTATUS</b></th>
					</tr>
				</thead>
	';
	//CONSULTA
		$i=1;
		$data = $categoria->consultAll();
		
		foreach ($data as &$item) {
			$item = get_object_vars($item);
			$r=$i%2;

			if($r==0)
				$bg="white";
			else
				$bg="#F2F2F2";


				if ($item['status_categoria']=='I')
				$status = 'INACTIVO';
			else
				$status = 'ACTIVO';


	$content .= '
		<tr bgcolor="'.$bg.'">
						<td>'.$item['nombre_categoria'].'</td>
						<td>'.$item['descripcion_categoria'].'</td>
						<td>'.$item['nombre_linea_investigacion'].'</td>
						<td>'.$status.'</td>
				</tr>
	';
	 $i++;
	}


	$content .= '</table>';

$pdf->writeHTML( $content, true, false, true, false, '');


// add a page


// Print a text
$html = '';
$pdf->writeHTML($html, true, false, true, false, '');

// --- example with background set on page ---

// remove default header
$pdf->setPrintHeader(false);

// add a page



// -- set new background ---

// get the current page break margin
$bMargin = $pdf->getBreakMargin();
// get current auto-page-break mode
$auto_page_break = $pdf->getAutoPageBreak();
// disable auto-page-break
$pdf->SetAutoPageBreak(false, 0);
// set bacground image
$img_file = K_PATH_IMAGES.'reporte.png';
$pdf->Image($img_file, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
// restore auto-page-break status
$pdf->SetAutoPageBreak($auto_page_break, $bMargin);
// set the starting point for the page content
$pdf->setPageMark();


// Print a text
$html = '<span style="color:white;text-align:center;font-weight:bold;font-size:80pt;">PAGE 3</span>';
$pdf->writeHTML($html, true, false, true, false, '');

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('example_051.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+