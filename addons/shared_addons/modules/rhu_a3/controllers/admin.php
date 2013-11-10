<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *
 * The Report Module - currently only remove/empty cache folder(s)
 *
 * @author		Donald Myers
 * @package		PyroCMS
 * @subpackage	Report Module
 * @category	Modules
 */

class Admin extends Admin_Controller 
{

	private $cache_path;

	public function __construct()
	{
		parent::__construct();
        $this->load->model(array('clients/clients_m', 'clients/clients_region_m', 'clients/clients_province_m', 'clients/clients_city_m', 'clients/clients_barangay_m','consultations/consultations_m','settings/settings_m','report/report_m2'));
		$this->cache_path = FCPATH.APPPATH.'cache/'.SITE_REF.'/';

		$this->config->load('report');
		$this->lang->load('report'); 
        
        $this->load->library('fpdf'); //Load FPDF library    
                                
	}


	/**
	 * List all folders
	 */
	public function index()
	{
		$folders = glob($this->cache_path.'*', GLOB_ONLYDIR);        

		/* get protected cache folders from module config file */
		$protected = (array)$this->config->item('report.cache_protected_folders');
		$cannot_remove = (array)$this->config->item('report.cannot_remove_folders');

		/* remove protected */
		foreach ($folders as $key => $folder)
		{
			$basename = basename($folder);
			
			if (in_array($basename, $protected))
			{
				unset($folders[$key]);
			}
			else
			{
				/* we just use the filename on the front end to not expose complete paths */
				$folder_ary[] = (object)array(
					'name'=>$basename,
					'count'=>count(glob($folder.'/*')),
					'cannot_remove'=>in_array($basename, $cannot_remove)
				);
			}
		}
		
		$i = 0;
		$table_list = config_item('report.export_tables');
		asort($table_list);

		foreach ($table_list AS $table)
		{
			$tables->{$i}->{'name'} = $table;
			$tables->{$i}->{'count'} = $this->db->count_all($table);
			$i++;
		}
    $year = date("Y");
    for($i=$year -10; $i < $year + 10; $i++)
    {
    $years[$i]  = $i;
    }
    $this->data->months = array ('1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5','6'=>'6','7'=>'7','8'=>'8','9'=>'9','10'=>'10','11'=>'11','12'=>'12');
    $this->data->quarters = array ('1'=>'1st Quarter','2'=>'2nd Quarter','3'=>'3rd Quarter','4'=>'4th Quarter');
    $this->data->years= $years;
		$this->data->tables = $tables;
		$this->data->folders = &$folder_ary;

		$this->template->title($this->module_details['name'])->build('admin/rpt_form', $this->data);   
	}
    
    public function rpt_bhs_m1()
	{
		
        $i = 0;
		$table_list = config_item('report.export_tables');
		asort($table_list);

		foreach ($table_list AS $table)
		{
			$tables->{$i}->{'name'} = $table;
			$tables->{$i}->{'count'} = $this->db->count_all($table);
			$i++;
		}
        
        $year = date("Y");
        for($i=$year -10; $i < $year + 10; $i++)
        {
            $years[$i]  = $i;
        }
        $this->data->months = array ('1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5','6'=>'6','7'=>'7','8'=>'8','9'=>'9','10'=>'10','11'=>'11','12'=>'12');
        $this->data->quarters = array ('1'=>'1st Quarter','2'=>'2nd Quarter','3'=>'3rd Quarter','4'=>'4th Quarter');
        $this->data->years= $years;
    	$this->data->tables = $tables;
    	$this->data->folders = &$folder_ary;
    
    	$this->template->title($this->module_details['name'])->build('admin/bhs_m1', $this->data);   
	}
    
    public function show()
    {
        $this->rhu_a3();
    }
    
    function rhu_a3()
    {
        $action = $this->input->post('btnAction');
        $year = $this->input->post('fhsi_year');
        
        $default_province = $this->settings_m->get_default_province();
        $default_city = $this->settings_m->get_default_city();
        
        if($year=='') 
            {$year = date('Y');} 
        
        $this->fpdf->AliasNbPages();
        $this->fpdf->AddPage('L','Letter');
        $this->fpdf->SetAutoPageBreak(true, 10);
        $this->fpdf->SetLeftMargin(5);
        
        $this->fpdf->SetDrawColor(150,150,150);   // Set Line and Border color
        
        //Draw rectangles
        $this->fpdf->Cell(10);
        $this->fpdf->SetFillColor(247,247,247);       //Set Fill color
        //$this->fpdf->Rect(5,10,260,178);             // Main box
        $this->fpdf->Rect(5,10,45,30,'D',true);      //Left box
        $this->fpdf->Rect(50,10,195,30,'D',true);     //Mid box
        $this->fpdf->Rect(245,10,30,30,'D',true);     //Right box
        
        //Header
        $this->fpdf->Image(base_url().'system/cms/themes/pyrocms/img/logo_small.png',7,20,40);
        $this->fpdf->SetY(15);
        $this->fpdf->SetX(110);
        $this->fpdf->SetFont('Helvetica','',9);
        $this->fpdf->SetTextColor(0,0,0);
        $this->fpdf->Cell(53,5,'FHSIS ANNUAL REPORT for YEAR',0,0,'L');
        $this->fpdf->Cell(12,5,$year,'ltrB',1,'C');
        $this->fpdf->SetX(100);
        $this->fpdf->Cell(22,5,'Municipality of:',0,0,'L');
        $this->fpdf->Cell(58,5,$default_city,'ltrB',1,'C');
        $this->fpdf->SetX(100);
        $this->fpdf->Cell(15,5,'Province:',0,0,'L');
        $this->fpdf->Cell(65,5,$default_province,'ltrB',1,'C');
        $this->fpdf->SetX(115);
        $this->fpdf->SetFont('Helvetica','B',9);
        $this->fpdf->Cell(50,5,'MORTALITY REPORT',0,1,'C');
        $this->fpdf->SetX(115);
        $this->fpdf->SetFont('Helvetica','',9);
        $this->fpdf->Cell(50,5,'for submission to PHO',0,1,'C');
        
        $this->fpdf->SetFillColor(247,247,247);
        
        $this->fpdf->SetY(40);
        $this->fpdf->SetFont('Helvetica','B',12);
        $this->fpdf->Cell(270,10,'AGE GROUP',1,1,'C',true);
        
        $this->fpdf->SetFont('Helvetica','B',7);
        $this->fpdf->Cell(37,7,'DISEASES','LTRb',0,'C');
        $this->fpdf->Cell(14,7,'Under 1',1,0,'C');
        $this->fpdf->Cell(14,7,'1-4',1,0,'C');
        $this->fpdf->Cell(14,7,'5-9',1,0,'C');
        $this->fpdf->Cell(14,7,'10-12',1,0,'C');
        $this->fpdf->Cell(14,7,'15-19',1,0,'C');
        $this->fpdf->Cell(14,7,'20-24',1,0,'C');
        $this->fpdf->Cell(14,7,'25-29',1,0,'C');
        $this->fpdf->Cell(14,7,'30-34',1,0,'C');
        $this->fpdf->Cell(14,7,'38-39',1,0,'C');
        $this->fpdf->Cell(14,7,'40-44',1,0,'C');
        $this->fpdf->Cell(14,7,'45-49',1,0,'C');
        $this->fpdf->Cell(14,7,'50-54',1,0,'C');
        $this->fpdf->Cell(14,7,'55-59',1,0,'C');
        $this->fpdf->Cell(14,7,'60-64',1,0,'C');
        $this->fpdf->Cell(14,7,'65 & Above',1,0,'C');
        $this->fpdf->Cell(14,7,'Total',1,0,'C');
        $this->fpdf->Cell(9,7,'Total',1,1,'C');
        
        $this->fpdf->Cell(37,7,'','LtRB',0,'C');
        $this->fpdf->Cell(7,7,'M',1,0,'C');
        $this->fpdf->Cell(7,7,'F',1,0,'C');
        $this->fpdf->Cell(7,7,'M',1,0,'C');
        $this->fpdf->Cell(7,7,'F',1,0,'C');
        $this->fpdf->Cell(7,7,'M',1,0,'C');
        $this->fpdf->Cell(7,7,'F',1,0,'C');
        $this->fpdf->Cell(7,7,'M',1,0,'C');
        $this->fpdf->Cell(7,7,'F',1,0,'C');
        $this->fpdf->Cell(7,7,'M',1,0,'C');
        $this->fpdf->Cell(7,7,'F',1,0,'C');
        $this->fpdf->Cell(7,7,'M',1,0,'C');
        $this->fpdf->Cell(7,7,'F',1,0,'C');
        $this->fpdf->Cell(7,7,'M',1,0,'C');
        $this->fpdf->Cell(7,7,'F',1,0,'C');
        $this->fpdf->Cell(7,7,'M',1,0,'C');
        $this->fpdf->Cell(7,7,'F',1,0,'C');
        $this->fpdf->Cell(7,7,'M',1,0,'C');
        $this->fpdf->Cell(7,7,'F',1,0,'C');
        $this->fpdf->Cell(7,7,'M',1,0,'C');
        $this->fpdf->Cell(7,7,'F',1,0,'C');
        $this->fpdf->Cell(7,7,'M',1,0,'C');
        $this->fpdf->Cell(7,7,'F',1,0,'C');
        $this->fpdf->Cell(7,7,'M',1,0,'C');
        $this->fpdf->Cell(7,7,'F',1,0,'C');
        $this->fpdf->Cell(7,7,'M',1,0,'C');
        $this->fpdf->Cell(7,7,'F',1,0,'C');
        $this->fpdf->Cell(7,7,'M',1,0,'C');
        $this->fpdf->Cell(7,7,'F',1,0,'C');
        $this->fpdf->Cell(7,7,'M',1,0,'C');
        $this->fpdf->Cell(7,7,'F',1,0,'C');
        $this->fpdf->Cell(7,7,'M',1,0,'C');
        $this->fpdf->Cell(7,7,'F',1,0,'C');
        $this->fpdf->Cell(9,7,'',1,1,'C');
        
        $this->fpdf->SetFont('Helvetica','',7);
        
        $this->fpdf->Cell(37,6,'Renal Failure',1,0,'L');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(9,6,'',1,1,'C');
        
        $this->fpdf->Cell(37,6,'Cardiac Arrest',1,0,'L');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(9,6,'',1,1,'C');
        
        $this->fpdf->Cell(37,6,'CHD',1,0,'L');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(9,6,'',1,1,'C');
        
        $this->fpdf->Cell(37,6,'Pneumonia',1,0,'L');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C'); 
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(9,6,'',1,1,'C');
        
        $this->fpdf->Cell(37,6,'Hypertension',1,0,'L');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');  
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(9,6,'',1,1,'C');
        
        $this->fpdf->Cell(37,6,'CVA',1,0,'L');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');   
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(9,6,'',1,1,'C');
        
        $this->fpdf->Cell(37,6,'G I Bleeding',1,0,'L');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(9,6,'',1,1,'C');
        
        $this->fpdf->Cell(37,6,'Anemia',1,0,'L');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(9,6,'',1,1,'C');
        
        $this->fpdf->Cell(37,6,'Food Poisoning',1,0,'L');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(9,6,'',1,1,'C');
        
        $this->fpdf->Cell(37,6,'Septicemia',1,0,'L');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(9,6,'',1,1,'C');
        
        $this->fpdf->Cell(37,6,'Accident',1,0,'L');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(9,6,'',1,1,'C');
        
        $this->fpdf->Cell(37,6,'Brocho',1,0,'L');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(9,6,'',1,1,'C');
        
        $this->fpdf->Cell(37,6,'B.P.U',1,0,'L');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(9,6,'',1,1,'C');
        
        $this->fpdf->Cell(37,6,'Injury',1,0,'L');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(9,6,'',1,1,'C');
        
        $this->fpdf->Cell(37,6,'Pulmonary Emphysema',1,0,'L');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(9,6,'',1,1,'C');
        
        $this->fpdf->Cell(37,6,'PTB',1,0,'L');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(9,6,'',1,1,'C');
        
        $this->fpdf->Cell(37,6,'Liver Cirrhosis',1,0,'L');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(9,6,'',1,1,'C');
        
        $this->fpdf->Cell(37,6,'D.O.A.',1,0,'L');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(9,6,'',1,1,'C');
        
        $this->fpdf->Cell(37,6,'Sepsis Hepatic encephalophaty',1,0,'L');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(9,6,'',1,1,'C');
        
        $this->fpdf->Cell(37,6,'Cancer',1,0,'L');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(7,6,'',1,0,'C');
        $this->fpdf->Cell(9,6,'',1,1,'C');
        
        $this->a3_header_icon();
        //$this->a2_pagenum();
        $this->footer();
            
        if($action=="generate_rhu_a3_dl")  
            {$this->fpdf->Output('RHU_Annual3_'.$year.'.pdf','D');}
        else
            {$this->fpdf->Output('RHU_Annual3_'.$year.'.pdf','I');}
    }
    
    function footer()
    {
        $year = date('Y');
        
        //Draw a line
        $this->fpdf->SetY(-15);
        
        //Page footer
        $this->fpdf->SetTextColor(128);
        $this->fpdf->SetFont('Helvetica','',8);
        $this->fpdf->Cell(0,4,'Copyright (C) '.$year.' IMPROVHEALTH | Provided by: Touch Foundation Inc.',0,0,'C');
    } 
    
    function a3_header_icon()
    {
        $this->fpdf->SetTextColor(0,0,0);
        $this->fpdf->SetY(10);
        $this->fpdf->SetX(245);
        $this->fpdf->SetFont('Helvetica','',6);
        $this->fpdf->Cell(30,5,'FHSIS Version 2013',0,1,'L');
        $this->fpdf->SetX(245);
        $this->fpdf->SetFont('Helvetica','B',60);
        $this->fpdf->Cell(30,15,'A3',0,1,'C');
        $this->fpdf->SetX(245);
        $this->fpdf->SetFont('Helvetica','B',30);
        $this->fpdf->Cell(30,10,'RHU',0,1,'C');
    }

	public function cleanup($name = '', $andfolder = 0)
	{

		if ( ! empty($name))
		{
			$apath = $this->_refind_apath($name);
			
			if ( ! empty($apath))
			{
				$item_count = count(glob($apath.'/*'));
				$which = ($andfolder) ? 'remove' : 'empty'; /* just empty or empty and remove? */
				
				if ($this->delete_files($apath, $andfolder))
				{
					$this->session->set_flashdata('success', sprintf(lang('report.'.$which.'_msg'), $item_count, $name));
				}
				else
				{
					$this->session->set_flashdata('error', sprintf(lang('report.'.$which.'_msg_err'), $name));
				}
			}
		}
		redirect('admin/report/');
	}


	private function delete_files($apath, $andfolder = 0)
	{
		$this->load->helper('file');

		if ( ! delete_files($apath, TRUE)) 
		{
			return FALSE;
		}
		
		if ($andfolder)
		{
			if ( ! rmdir($apath))
			{
				return FALSE;
			}
		}
		return TRUE;
	}
	
	public function export($table = '', $type = 'xml')
	{
		$this->load->model('report_m');
		$this->load->helper('download');
		$this->load->library('format');

		$table_list = config_item('report.export_tables');

		if (in_array($table, $table_list))
		{			
			$this->report_m->export($table, $type, $table_list);
		}
		else
		{
			redirect('admin/report');
		}
	}
  
    public function download()
	{
		$this->load->model('report_m');
		$this->load->helper('download');
		$this->load->library('format');
		
		$this->report_m->download();
	}
  
    public function upload()
	{
        $this->load->model('report_m');
		$this->load->helper('download');
		$this->load->library('format');
		
		$this->report_m->upload();
  
	}


	private function _refind_apath($name)
	{
		$folders = glob($this->cache_path.'*', GLOB_ONLYDIR);
		foreach ($folders as $folder)
		{
			if (basename($folder) === $name)
			{
				return $folder;
			}
		}
	}
        
    public function testreport()
    {
        $model = $this->load->model('report_m2');
        echo print_r($model->get_sc_diarrhea_m1('1','2013')); 
    }

} /* end admin class */
?>