<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require('html2pdf.class.php');    
class Htmltopdf extends HTML2PDF
{
	// Extend FPDF using this class
	// More at fpdf.org -> Tutorials

	function __construct($orientation = 'P', $format = 'A4', $langue='fr', $unicode=true, $encoding='UTF-8', $marges = array(5, 5, 5, 8))
	{
		// Call parent constructor
		parent::__construct($orientation, $format, $langue, $unicode, $encoding, $marges);
	}
    
}
?>