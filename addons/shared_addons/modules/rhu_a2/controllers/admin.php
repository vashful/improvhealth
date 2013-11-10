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
        $this->load->model(array('clients/clients_m', 'clients/clients_region_m', 'clients/clients_province_m', 'clients/clients_city_m', 'clients/clients_barangay_m','consultations/consultations_m','settings/settings_m','rhu_a2/rhu_a2_m'));
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
        $this->rhu_a2();
    }
    
    public function rhu_a2()
    {
        $action = $this->input->post('btnAction');
        $year = $this->input->post('fhsi_year');
        
        $default_city = $this->settings_m->get_default_city();
        
        if($year=='') 
            {$year = date('Y');} 
        
        $this->rhu_a2_morbidity($year); 
        
        if($action=="generate_rhu_a2_dl")  
            {$this->fpdf->Output('RHU_A2_'.url_title($default_city).'_'.$year.'.pdf','D');}
        else
            {$this->fpdf->Output('RHU_A2_'.url_title($default_city).'_'.$year.'.pdf','I');}
    }
    
    function rhu_a2_morbidity($year)
    {       
        $default_province = $this->settings_m->get_default_province();
        $default_city = $this->settings_m->get_default_city();
        
        // Using this data, get the relevant results 
        $morbidity_cases_under1 = $this->rhu_a2_m->get_morbidity_cases_under1($year);
        $morbidity_cases_1to4 = $this->rhu_a2_m->get_morbidity_cases_1to4($year);
        $morbidity_cases_5to9 = $this->rhu_a2_m->get_morbidity_cases_5to9($year);
        $morbidity_cases_10to14 = $this->rhu_a2_m->get_morbidity_cases_10to14($year);
        $morbidity_cases_15to19 = $this->rhu_a2_m->get_morbidity_cases_15to19($year);
        $morbidity_cases_20to24 = $this->rhu_a2_m->get_morbidity_cases_20to24($year);
        $morbidity_cases_25to29 = $this->rhu_a2_m->get_morbidity_cases_25to29($year);
        $morbidity_cases_30to34 = $this->rhu_a2_m->get_morbidity_cases_30to34($year);
        $morbidity_cases_35to39 = $this->rhu_a2_m->get_morbidity_cases_35to39($year);
        $morbidity_cases_40to44 = $this->rhu_a2_m->get_morbidity_cases_40to44($year);
        $morbidity_cases_45to49 = $this->rhu_a2_m->get_morbidity_cases_45to49($year);
        $morbidity_cases_50to54 = $this->rhu_a2_m->get_morbidity_cases_50to54($year);
        $morbidity_cases_55to59 = $this->rhu_a2_m->get_morbidity_cases_55to59($year);
        $morbidity_cases_60to64 = $this->rhu_a2_m->get_morbidity_cases_60to64($year);
        $morbidity_cases_65up = $this->rhu_a2_m->get_morbidity_cases_65up($year);
        $morbidity_cases_total = $this->rhu_a2_m->get_morbidity_cases_total($year);
        
        $this->fpdf->AliasNbPages();
        $this->fpdf->AddPage('L','A4');
        $this->fpdf->SetAutoPageBreak(true, 10);
        $this->fpdf->SetLeftMargin(10);
        
        $this->fpdf->SetDrawColor(150,150,150);   // Set Line and Border color
        
        //Header
        $this->a2_header($year);       
        
        $this->fpdf->SetFont('Helvetica','',7);
        $i = 1;
        $totalitems = count($morbidity_cases_under1);
        $totalindex = $totalitems-1;
        for($x=0; $x<=$totalindex; $x++)
        {          
            $this->fpdf->SetFillColor(230,230,230);             
            $modx = $x%2;
            if($modx==0) 
                {$fill=true;}
            else
                {$fill=false;}
            
          $total_cases_total_mf = $morbidity_cases_total[$x]['total_cases_total_m']+$morbidity_cases_total[$x]['total_cases_total_f']; 
          
          $this->fpdf->SetFont('Helvetica','',6.5);
          $this->fpdf->Cell(63.5,7,number_format($x+1).'.'.ucwords($morbidity_cases_under1[$x]['name']),1,0,'L',$fill);
          $this->fpdf->SetFont('Helvetica','',6.5);
          $this->fpdf->Cell(6.5,7,$morbidity_cases_under1[$x]['total_cases_under1_m'],1,0,'C',$fill);
          $this->fpdf->Cell(6.5,7,$morbidity_cases_under1[$x]['total_cases_under1_f'],1,0,'C',$fill);  
          $this->fpdf->Cell(6.5,7,$morbidity_cases_1to4[$x]['total_cases_1to4_m'],1,0,'C',$fill);
          $this->fpdf->Cell(6.5,7,$morbidity_cases_1to4[$x]['total_cases_1to4_f'],1,0,'C',$fill);           
          $this->fpdf->Cell(6.5,7,$morbidity_cases_5to9[$x]['total_cases_5to9_m'],1,0,'C',$fill);
          $this->fpdf->Cell(6.5,7,$morbidity_cases_5to9[$x]['total_cases_5to9_f'],1,0,'C',$fill); 
          $this->fpdf->Cell(6.5,7,$morbidity_cases_10to14[$x]['total_cases_10to14_m'],1,0,'C',$fill);
          $this->fpdf->Cell(6.5,7,$morbidity_cases_10to14[$x]['total_cases_10to14_f'],1,0,'C',$fill); 
          $this->fpdf->Cell(6.5,7,$morbidity_cases_15to19[$x]['total_cases_15to19_m'],1,0,'C',$fill);
          $this->fpdf->Cell(6.5,7,$morbidity_cases_15to19[$x]['total_cases_15to19_f'],1,0,'C',$fill); 
          $this->fpdf->Cell(6.5,7,$morbidity_cases_20to24[$x]['total_cases_20to24_m'],1,0,'C',$fill);
          $this->fpdf->Cell(6.5,7,$morbidity_cases_20to24[$x]['total_cases_20to24_f'],1,0,'C',$fill); 
          $this->fpdf->Cell(6.5,7,$morbidity_cases_25to29[$x]['total_cases_25to29_m'],1,0,'C',$fill);
          $this->fpdf->Cell(6.5,7,$morbidity_cases_25to29[$x]['total_cases_25to29_f'],1,0,'C',$fill); 
          $this->fpdf->Cell(6.5,7,$morbidity_cases_30to34[$x]['total_cases_30to34_m'],1,0,'C',$fill);
          $this->fpdf->Cell(6.5,7,$morbidity_cases_30to34[$x]['total_cases_30to34_f'],1,0,'C',$fill); 
          $this->fpdf->Cell(6.5,7,$morbidity_cases_35to39[$x]['total_cases_35to39_m'],1,0,'C',$fill);
          $this->fpdf->Cell(6.5,7,$morbidity_cases_35to39[$x]['total_cases_35to39_f'],1,0,'C',$fill); 
          $this->fpdf->Cell(6.5,7,$morbidity_cases_40to44[$x]['total_cases_40to44_m'],1,0,'C',$fill);
          $this->fpdf->Cell(6.5,7,$morbidity_cases_40to44[$x]['total_cases_40to44_f'],1,0,'C',$fill); 
          $this->fpdf->Cell(6.5,7,$morbidity_cases_45to49[$x]['total_cases_45to49_m'],1,0,'C',$fill);
          $this->fpdf->Cell(6.5,7,$morbidity_cases_45to49[$x]['total_cases_45to49_f'],1,0,'C',$fill); 
          $this->fpdf->Cell(6.5,7,$morbidity_cases_50to54[$x]['total_cases_50to54_m'],1,0,'C',$fill);
          $this->fpdf->Cell(6.5,7,$morbidity_cases_50to54[$x]['total_cases_50to54_f'],1,0,'C',$fill); 
          $this->fpdf->Cell(6.5,7,$morbidity_cases_55to59[$x]['total_cases_55to59_m'],1,0,'C',$fill);
          $this->fpdf->Cell(6.5,7,$morbidity_cases_55to59[$x]['total_cases_55to59_f'],1,0,'C',$fill); 
          $this->fpdf->Cell(6.5,7,$morbidity_cases_60to64[$x]['total_cases_60to64_m'],1,0,'C',$fill);
          $this->fpdf->Cell(6.5,7,$morbidity_cases_60to64[$x]['total_cases_60to64_f'],1,0,'C',$fill); 
          $this->fpdf->Cell(6.5,7,$morbidity_cases_65up[$x]['total_cases_65up_m'],1,0,'C',$fill);
          $this->fpdf->Cell(6.5,7,$morbidity_cases_65up[$x]['total_cases_65up_f'],1,0,'C',$fill); 
          $this->fpdf->Cell(6.5,7,$morbidity_cases_total[$x]['total_cases_total_m'],1,0,'C',$fill);
          $this->fpdf->Cell(6.5,7,$morbidity_cases_total[$x]['total_cases_total_f'],1,0,'C',$fill); 
          $this->fpdf->Cell(6.5,7,$total_cases_total_mf,1,1,'C',$fill);
          if($i==18)
          {
              if($x<($totalitems-1))
              {
                  $this->a2_header_icon();
                  $this->footer();
                  
                  $i = 1;
                  $this->fpdf->AddPage('L','A4');
                  $this->a2_header($year); 
              }
          }
          else
          {
              $i=$i+1;
          }      
        }
        $extra=18-($i-1);
        for($x2=1; $x2<=$extra; $x2++)
        { 
            $this->fpdf->SetFont('Helvetica','',6.5);
            $this->fpdf->Cell(63.5,7,'',1,0,'L');
            $this->fpdf->Cell(6.5,7,'',1,0,'C');
            $this->fpdf->Cell(6.5,7,'',1,0,'C'); 
            $this->fpdf->Cell(6.5,7,'',1,0,'C');
            $this->fpdf->Cell(6.5,7,'',1,0,'C');
            $this->fpdf->Cell(6.5,7,'',1,0,'C'); 
            $this->fpdf->Cell(6.5,7,'',1,0,'C');
            $this->fpdf->Cell(6.5,7,'',1,0,'C');
            $this->fpdf->Cell(6.5,7,'',1,0,'C'); 
            $this->fpdf->Cell(6.5,7,'',1,0,'C');
            $this->fpdf->Cell(6.5,7,'',1,0,'C');
            $this->fpdf->Cell(6.5,7,'',1,0,'C'); 
            $this->fpdf->Cell(6.5,7,'',1,0,'C');
            $this->fpdf->Cell(6.5,7,'',1,0,'C');
            $this->fpdf->Cell(6.5,7,'',1,0,'C'); 
            $this->fpdf->Cell(6.5,7,'',1,0,'C');
            $this->fpdf->Cell(6.5,7,'',1,0,'C');
            $this->fpdf->Cell(6.5,7,'',1,0,'C'); 
            $this->fpdf->Cell(6.5,7,'',1,0,'C');
            $this->fpdf->Cell(6.5,7,'',1,0,'C');
            $this->fpdf->Cell(6.5,7,'',1,0,'C'); 
            $this->fpdf->Cell(6.5,7,'',1,0,'C');
            $this->fpdf->Cell(6.5,7,'',1,0,'C');
            $this->fpdf->Cell(6.5,7,'',1,0,'C'); 
            $this->fpdf->Cell(6.5,7,'',1,0,'C');
            $this->fpdf->Cell(6.5,7,'',1,0,'C');
            $this->fpdf->Cell(6.5,7,'',1,0,'C'); 
            $this->fpdf->Cell(6.5,7,'',1,0,'C');
            $this->fpdf->Cell(6.5,7,'',1,0,'C');
            $this->fpdf->Cell(6.5,7,'',1,0,'C'); 
            $this->fpdf->Cell(6.5,7,'',1,0,'C');
            $this->fpdf->Cell(6.5,7,'',1,0,'C');
            $this->fpdf->Cell(6.5,7,'',1,0,'C'); 
            $this->fpdf->Cell(6.5,7,'',1,1,'C');
        }
        $this->a2_header_icon();
        $this->footer();
    }
    
    function footer()
    {
        $year = date('Y');
        
        //Draw a line
        $this->fpdf->SetY(-15);
        
        //Page footer
        $this->fpdf->SetTextColor(128);
        $this->fpdf->SetFont('Helvetica','I',7.5);
        $this->fpdf->Cell(10,4,'Page '.$this->fpdf->PageNo().'/{nb}',0,0,'C');
        $this->fpdf->SetFont('Helvetica','',7.5);
        $this->fpdf->Cell(0,4,'Copyright (C) '.$year.' IMPROVHEALTH | Provided by: Touch Foundation Inc.',0,0,'C');
    } 
    
    function a2_header($year)
    {
        $default_province = $this->settings_m->get_default_province();
        $default_city = $this->settings_m->get_default_city();
        $station_name = $this->settings_m->get_station_name();
        
        if(($station_name=="Main Health Center")&&($default_city!=null))
            {$bhs_bhc = $default_city.' - '.$station_name;}
        else
            {$bhs_bhc = $station_name;}
            
        $this->fpdf->SetLeftMargin(10);
        
        $this->fpdf->SetDrawColor(150,150,150);   // Set Line and Border color
        
        //Draw rectangles
        $this->fpdf->Cell(10);
        $this->fpdf->SetFillColor(247,247,247);       //Set Fill color
        //$this->fpdf->Rect(5,10,260,178);             // Main box
        $this->fpdf->Rect(10,10,45,30,'D',true);      //Left box
        $this->fpdf->Rect(55,10,203,30,'D',true);     //Mid box
        $this->fpdf->Rect(258,10,30,30,'D',true);     //Right box
        
        //Header
        
        $this->fpdf->Image(base_url().'system/cms/themes/pyrocms/img/logo_small.png',12,20,40);
        $this->fpdf->SetY(15);
        $this->fpdf->SetX(114);
        $this->fpdf->SetFont('Helvetica','',9);
        $this->fpdf->SetTextColor(0,0,0);
        $this->fpdf->Cell(53,5,'FHSIS ANNUAL REPORT for YEAR',0,0,'L');
        $this->fpdf->Cell(12,5,$year,'ltrB',1,'C');
        $this->fpdf->SetX(107);
        $this->fpdf->Cell(27,5,'City / Municipality:',0,0,'L');
        $this->fpdf->Cell(53,5,$default_city,'ltrB',1,'C');
        $this->fpdf->SetX(107);
        $this->fpdf->Cell(15,5,'Province:',0,0,'L');
        $this->fpdf->Cell(65,5,$default_province,'ltrB',1,'C');
        $this->fpdf->SetX(123);
        $this->fpdf->SetFont('Helvetica','B',9);
        $this->fpdf->Cell(50,5,'MORBIDITY DISEASES REPORT',0,1,'C');
        $this->fpdf->SetX(123);
        $this->fpdf->SetFont('Helvetica','',9);
        $this->fpdf->Cell(50,5,'for submission to PHO',0,1,'C');
        
        $this->fpdf->SetFillColor(247,247,247);
        
        $this->fpdf->SetY(40);
        $this->fpdf->SetFont('Helvetica','B',12);
        $this->fpdf->Cell(278,10,'AGE GROUP',1,1,'C',true);
        
        $this->fpdf->SetFont('Helvetica','B',7);
        $this->fpdf->Cell(63.5,7,'DISEASES','LTRb',0,'C'); 
        $this->fpdf->Cell(13,7,'Under 1',1,0,'C');
        $this->fpdf->Cell(13,7,'1-4',1,0,'C');
        $this->fpdf->Cell(13,7,'5-9',1,0,'C');
        $this->fpdf->Cell(13,7,'10-14',1,0,'C');
        $this->fpdf->Cell(13,7,'15-19',1,0,'C');
        $this->fpdf->Cell(13,7,'20-24',1,0,'C');
        $this->fpdf->Cell(13,7,'25-29',1,0,'C');
        $this->fpdf->Cell(13,7,'30-34',1,0,'C');
        $this->fpdf->Cell(13,7,'35-39',1,0,'C');
        $this->fpdf->Cell(13,7,'40-44',1,0,'C');
        $this->fpdf->Cell(13,7,'45-49',1,0,'C');
        $this->fpdf->Cell(13,7,'50-54',1,0,'C');
        $this->fpdf->Cell(13,7,'55-59',1,0,'C');
        $this->fpdf->Cell(13,7,'60-64',1,0,'C');
        $this->fpdf->SetFont('Helvetica','B',6.5);
        $this->fpdf->Cell(13,7,'65 & Above',0,0,'C');
        $this->fpdf->SetFont('Helvetica','B',7);
        $this->fpdf->Cell(19.5,7,'Total',1,1,'C');
        
        $this->fpdf->Cell(63.5,7,'','LtRB',0,'C');
        $this->fpdf->Cell(6.5,7,'M',1,0,'C');
        $this->fpdf->Cell(6.5,7,'F',1,0,'C');
        $this->fpdf->Cell(6.5,7,'M',1,0,'C');
        $this->fpdf->Cell(6.5,7,'F',1,0,'C');
        $this->fpdf->Cell(6.5,7,'M',1,0,'C');
        $this->fpdf->Cell(6.5,7,'F',1,0,'C');
        $this->fpdf->Cell(6.5,7,'M',1,0,'C');
        $this->fpdf->Cell(6.5,7,'F',1,0,'C');
        $this->fpdf->Cell(6.5,7,'M',1,0,'C');
        $this->fpdf->Cell(6.5,7,'F',1,0,'C');
        $this->fpdf->Cell(6.5,7,'M',1,0,'C');
        $this->fpdf->Cell(6.5,7,'F',1,0,'C');
        $this->fpdf->Cell(6.5,7,'M',1,0,'C');
        $this->fpdf->Cell(6.5,7,'F',1,0,'C');
        $this->fpdf->Cell(6.5,7,'M',1,0,'C');
        $this->fpdf->Cell(6.5,7,'F',1,0,'C');
        $this->fpdf->Cell(6.5,7,'M',1,0,'C');
        $this->fpdf->Cell(6.5,7,'F',1,0,'C');
        $this->fpdf->Cell(6.5,7,'M',1,0,'C');
        $this->fpdf->Cell(6.5,7,'F',1,0,'C');
        $this->fpdf->Cell(6.5,7,'M',1,0,'C');
        $this->fpdf->Cell(6.5,7,'F',1,0,'C');
        $this->fpdf->Cell(6.5,7,'M',1,0,'C');
        $this->fpdf->Cell(6.5,7,'F',1,0,'C');
        $this->fpdf->Cell(6.5,7,'M',1,0,'C');
        $this->fpdf->Cell(6.5,7,'F',1,0,'C');
        $this->fpdf->Cell(6.5,7,'M',1,0,'C');
        $this->fpdf->Cell(6.5,7,'F',1,0,'C');
        $this->fpdf->Cell(6.5,7,'M',1,0,'C');
        $this->fpdf->Cell(6.5,7,'F',1,0,'C');
        $this->fpdf->Cell(6.5,7,'M',1,0,'C');
        $this->fpdf->Cell(6.5,7,'F',1,0,'C');
        $this->fpdf->Cell(6.5,7,'',1,1,'C');
    }
    
    function a2_header_icon()
    {
        $this->fpdf->SetTextColor(0,0,0);
        $this->fpdf->SetY(10);
        $this->fpdf->SetX(258);
        $this->fpdf->SetFont('Helvetica','',6);
        $this->fpdf->Cell(30,5,'FHSIS Version 2013',0,1,'L');
        $this->fpdf->SetX(258);
        $this->fpdf->SetFont('Helvetica','B',60);
        $this->fpdf->Cell(30,15,'A2',0,1,'C');
        $this->fpdf->SetX(258);
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