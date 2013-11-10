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
        $this->load->model(array('clients/clients_m', 'clients/clients_region_m', 'clients/clients_province_m', 'clients/clients_city_m', 'clients/clients_barangay_m','consultations/consultations_m','settings/settings_m','bhs_a/bhs_a_m'));
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
    
    public function show()
    {
        $this->bhs_a();
    }
  
    public function bhs_a()
    {           
        $action = $this->input->post('btnAction');
        
        $year = $this->input->post('a_year'); 
        
        if($year=='') 
            {$year = date('Y');} 
        
        $default_province = $this->settings_m->get_default_province();
        $default_city = $this->settings_m->get_default_city();
        $station_name = $this->settings_m->get_station_name();
        
        if(($station_name=="Main Health Center")&&($default_city!=null))
            {$bhs_bhc = $default_city.' - '.$station_name;}
        else
            {$bhs_bhc = $station_name;}
         
        // Using this data, get the relevant results 
        $maternal = $this->bhs_a_m->get_medicare_montlyreport($year);
        $family_planning = $this->bhs_a_m->get_familyplanning_montlyreport($year);
        //$childcare = $this->bhs_a_m->get_childcare_montlyreport($year);
        //$sick_children = $this->bhs_a_m->get_sc_vita_m1($year);
        //$sc_anemic = $this->bhs_a_m->get_sc_anemic_m1($year);
        //$inf_lbw = $this->bhs_a_m->get_sc_lbw_m1($year);
        //$sc_diarrhea = $this->bhs_a_m->get_sc_diarrhea_m1($year);
        //$sc_pneumonia = $this->bhs_a_m->get_sc_pneumonia_m1($year);
        
        $this->fpdf->Open();
        $this->fpdf->AliasNbPages();
        $this->fpdf->AddPage('P','A4');
        $this->fpdf->SetAutoPageBreak(true, 10);
                                                                              
        $this->fpdf->SetDrawColor(150,150,150);   // Set Line and Border color
        
        //Draw rectangle
        $this->fpdf->Cell(15);
        $this->fpdf->SetFillColor(247,247,247);       //Set Fill color
        //$this->fpdf->Rect(10,10,197,251);
        $this->fpdf->Rect(10,10,45,45,'D',true);      //Right box
        $this->fpdf->Rect(55,10,100,45,'D',true);              //Mid box
        $this->fpdf->Rect(155,10,45,45,'D',true);     //Left box
        
        //Header
        $this->fpdf->Image(base_url().'system/cms/themes/pyrocms/img/logo_small.png',14,25,40);
        $this->fpdf->SetY(15);
        $this->fpdf->SetX(58);
        $this->fpdf->SetFont('Helvetica','',10);
        $this->fpdf->SetTextColor(0,0,0);
        $this->fpdf->Cell(63,7,'FHSIS BHS Annual Report for the year',0,0,'L');
        //$this->fpdf->Cell(20,7,$monthname,'ltrB',0,'L');
        //$this->fpdf->Cell(10,7,'Year:',0,0,'L');
        $this->fpdf->Cell(10,7,$year,'ltrB',1,'L');
        $this->fpdf->SetX(58);
        $this->fpdf->Cell(23,7,'Name of BHS:',0,0,'L');
        $this->fpdf->Cell(70,7,$bhs_bhc,'ltrB',1,'L');
        $this->fpdf->SetX(58);
        $this->fpdf->Cell(31,7,'Municipality/City of:',0,0,'L');
        $this->fpdf->Cell(62,7,$default_city,'ltrB',1,'L');
        $this->fpdf->SetX(58);
        $this->fpdf->Cell(16,7,'Province:',0,0,'L');
        $this->fpdf->Cell(77,7,$default_province,'ltrB',1,'L');
        $this->fpdf->SetFont('Helvetica','B',10);
        $this->fpdf->SetX(58);
        $this->fpdf->Cell(95,7,'VITAL STATISTICS REPORT',0,0,'C');
        
        $this->a_header_icon();
        
        $this->fpdf->SetFillColor(247,247,247);
        
        $this->fpdf->SetY(55);
        $this->fpdf->SetFont('Helvetica','B',11);   
        $this->fpdf->Cell(190,7,'DEMOGRAPHIC',1,1,'C',true);
        
        $this->fpdf->SetFont('Helvetica','',9);
        $this->fpdf->Cell(50,7,'Population',1,0,'L');
        $this->fpdf->Cell(45,7,'','1',0,'C');
        $this->fpdf->Cell(50,7,'Households',1,0,'L');
        $this->fpdf->Cell(45,7,'','1',1,'C');
        $this->fpdf->Cell(50,7,'Barangays',1,0,'L');
        $this->fpdf->Cell(45,7,'','1',0,'C');
        $this->fpdf->Cell(50,7,'',1,0,'L');
        $this->fpdf->Cell(45,7,'','1',1,'C');
        
        $this->fpdf->SetFont('Helvetica','B',11);   
        $this->fpdf->Cell(190,7,'ENVIRONMNENTAL',1,1,'C',true);
        
        $this->fpdf->SetFont('Helvetica','',9);
        $this->fpdf->Cell(145,7,'Households with access to improved or safe water supply',1,0,'L');
        $this->fpdf->Cell(45,7,'','1',1,'C');
        $this->fpdf->Cell(145,7,'       - Level 1',1,0,'L');
        $this->fpdf->Cell(45,7,'','1',1,'C');
        $this->fpdf->Cell(145,7,'       - Level 2',1,0,'L');
        $this->fpdf->Cell(45,7,'','1',1,'C');
        $this->fpdf->Cell(145,7,'       - Level 3',1,0,'L');
        $this->fpdf->Cell(45,7,'','1',1,'C');
        $this->fpdf->Cell(145,7,'Households with sanitary toilet facilities',1,0,'L');
        $this->fpdf->Cell(45,7,'','1',1,'C');
        $this->fpdf->Cell(145,7,'Households with satisfactory disposal of solid waste',1,0,'L');
        $this->fpdf->Cell(45,7,'','1',1,'C');
        $this->fpdf->Cell(145,7,'Households with complete basic sanitation facilities',1,0,'L');
        $this->fpdf->Cell(45,7,'','1',1,'C');
        $this->fpdf->Cell(145,7,'Food Establishments',1,0,'L');
        $this->fpdf->Cell(45,7,'','1',1,'C');
        $this->fpdf->Cell(145,7,'Food Establishments with sanitary permit',1,0,'L');
        $this->fpdf->Cell(45,7,'','1',1,'C');
        $this->fpdf->Cell(145,7,'Food Handlers',1,0,'L');
        $this->fpdf->Cell(45,7,'','1',1,'C');
        $this->fpdf->Cell(145,7,'Food Handlers with health certificate',1,0,'L');
        $this->fpdf->Cell(45,7,'','1',1,'C');
        $this->fpdf->Cell(145,7,'Salt Samples Tested',1,0,'L');
        $this->fpdf->Cell(45,7,'','1',1,'C');
        $this->fpdf->Cell(145,7,'Salt Samples Tested found (+) for iodine',1,0,'L');
        $this->fpdf->Cell(45,7,'','1',1,'C');
        
        $this->fpdf->SetFont('Helvetica','B',11);   
        $this->fpdf->Cell(190,7,'NATALITY',1,1,'C',true);
        
        $this->fpdf->SetFont('Helvetica','',9);
        $this->fpdf->Cell(40,7,'No. of Livebirths',1,0,'L');
        $this->fpdf->Cell(55,7,'','1',0,'C');
        $this->fpdf->SetFont('Helvetica','BI',10);
        $this->fpdf->Cell(95,7,'Birthweight',1,1,'C',true);
        
        $this->fpdf->SetFont('Helvetica','',9);
        $this->fpdf->Cell(40,7,'    - Male',1,0,'L');
        $this->fpdf->Cell(55,7,'','1',0,'C');
        $this->fpdf->Cell(40,7,'2500 grams & greater',1,0,'L');
        $this->fpdf->Cell(55,7,'','1',1,'C');
        $this->fpdf->Cell(40,7,'    - Female',1,0,'L');
        $this->fpdf->Cell(55,7,'','1',0,'C');
        $this->fpdf->Cell(40,7,'Less than 2500 grams',1,0,'L');
        $this->fpdf->Cell(55,7,'','1',1,'C');
        
        $this->fpdf->SetFont('Helvetica','BI',10);
        $this->fpdf->Cell(95,7,'Deliveries Attended by',1,0,'C',true);
        $this->fpdf->SetFont('Helvetica','',9);
        $this->fpdf->Cell(40,7,'Not Known','1',0,'L');
        $this->fpdf->Cell(55,7,'',1,1,'C');
        
        $this->fpdf->Cell(40,7,'Doctors',1,0,'L');
        $this->fpdf->Cell(55,7,'','1',0,'C');
        $this->fpdf->Cell(40,7,'',1,0,'L');
        $this->fpdf->Cell(55,7,'','1',1,'C');
        $this->fpdf->Cell(40,7,'Nurses',1,0,'L');
        $this->fpdf->Cell(55,7,'','1',0,'C');
        $this->fpdf->Cell(40,7,'',1,0,'L');
        $this->fpdf->Cell(55,7,'','1',1,'C');
        $this->fpdf->Cell(40,7,'Midwives',1,0,'L');
        $this->fpdf->Cell(55,7,'','1',0,'C');
        $this->fpdf->SetFont('Helvetica','BI',10);
        $this->fpdf->Cell(95,7,'Types of Pregnancy','1',1,'C',true);
        $this->fpdf->SetFont('Helvetica','',9);
        $this->fpdf->Cell(40,7,'Hilot/TBA',1,0,'L');
        $this->fpdf->Cell(55,7,'','1',0,'C');
        $this->fpdf->Cell(40,7,'Normal',1,0,'L');
        $this->fpdf->Cell(55,7,'','1',1,'C');
        $this->fpdf->Cell(40,7,'Others',1,0,'L');
        $this->fpdf->Cell(55,7,'','1',0,'C');
        $this->fpdf->Cell(40,7,'Risk',1,0,'L');
        $this->fpdf->Cell(55,7,'','1',1,'C');
        $this->fpdf->Cell(40,7,'',1,0,'L');
        $this->fpdf->Cell(55,7,'','1',0,'C');
        $this->fpdf->Cell(40,7,'Unknown',1,0,'L');
        $this->fpdf->Cell(55,7,'','1',1,'C');
        
        $this->fpdf->SetTextColor(0,0,0);
        
        $this->fpdf->SetFont('Helvetica','BI',10);
        $this->fpdf->Cell(190,7,'Deliveries by Type and Place',1,1,'C',true);
        
        $this->fpdf->SetFont('Helvetica','',8);           //set new font size
        
        $this->fpdf->Cell(38,7,'Type',1,0,'C'); 
        $this->fpdf->Cell(38,7,'Home',1,0,'C');
        $this->fpdf->Cell(38,7,'Hospital',1,0,'C');
        $this->fpdf->Cell(38,7,'Others',1,0,'C');
        $this->fpdf->Cell(38,7,'Total',1,1,'C');  
        
        $this->fpdf->Cell(38,7,'Normal',1,0,'L');
        $this->fpdf->Cell(38,7,'',1,0,'C');
        $this->fpdf->Cell(38,7,'',1,0,'C');
        $this->fpdf->Cell(38,7,'',1,0,'C');
        $this->fpdf->Cell(38,7,'',1,1,'C');
        
        $this->fpdf->Cell(38,7,'Others',1,0,'L');
        $this->fpdf->Cell(38,7,'',1,0,'C');
        $this->fpdf->Cell(38,7,'',1,0,'C');
        $this->fpdf->Cell(38,7,'',1,0,'C');
        $this->fpdf->Cell(38,7,'',1,1,'C');

        $this->footer();
        
        if($action=="generate_bhs_a_dl")  
            {$this->fpdf->Output('BHS_A_'.url_title($bhs_bhc).'_'.$year.'.pdf','D');} 
        else
            {$this->fpdf->Output('BHS_A_'.url_title($bhs_bhc).'_'.$year.'.pdf','I');}            

    }
      
    function footer()
    {
        $year = date('Y');
        
        //Draw a line
        $this->fpdf->SetY(-15);
        
        //Page footer
        $this->fpdf->SetTextColor(128);
        $this->fpdf->SetFont('Helvetica','I',6.5);
        $this->fpdf->Cell(10,4,'Page '.$this->fpdf->PageNo().'/{nb}',0,0,'C');
        $this->fpdf->SetFont('Helvetica','',7);
        $this->fpdf->Cell(0,4,'Copyright (C) '.$year.' IMPROVHEALTH | Provided by: Touch Foundation Inc.',0,0,'C');
    } 
    
    function a_header_icon()
    {
        $this->fpdf->SetTextColor(0,0,0);
        $this->fpdf->SetY(10);
        $this->fpdf->SetX(155);
        $this->fpdf->SetFont('Helvetica','',7);
        $this->fpdf->Cell(45,7,'FHSIS Version 2013',0,1,'C');
        $this->fpdf->SetX(155);
        $this->fpdf->SetFont('Helvetica','B',95);
        $this->fpdf->Cell(45,25,'A',0,1,'C');
        $this->fpdf->SetX(155);
        $this->fpdf->SetFont('Helvetica','B',40);
        $this->fpdf->Cell(45,10,'BHS',0,1,'C');
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
        echo print_r($model->get_sc_pneumonia_m1('3','2012')); 
    }

} /* end admin class */
?>