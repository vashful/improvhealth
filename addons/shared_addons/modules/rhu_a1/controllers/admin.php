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
    
    public function show()
    {
        $this->rhu_a1();
    }          
    
    public function rhu_a1()
    {
        $action = $this->input->post('btnAction');
        $year = $this->input->post('rhu_a1_year');
        
        if($year=='') 
            {$year = date('Y');} 
        
        $this->rhu_a1_demographic($year);
        $this->rhu_a1_environmental($year);
        $this->rhu_a1_livebirths($year);
        $this->rhu_a1_natality($year);
        $this->rhu_a1_mortality($year);
        
        if($action=="generate_rhu_a1_dl")  
            {$this->fpdf->Output('RHU_Annual1_'.$year.'.pdf','D');} 
        else
            {$this->fpdf->Output('RHU_Annual1_'.$year.'.pdf','I');}    
    }        
    
    function rhu_a1_demographic($year)
    {
        $ppoty = 23994;
        
        $eligible_pop1 = $ppoty * 0.035;
        $eligible_pop2 = $ppoty * 0.03;
            
        $default_province = $this->settings_m->get_default_province();
        $default_city = $this->settings_m->get_default_city();
        
        $demographic_brgy = $this->report_m2->get_demographic_brgy_annual1();
        $demographic_referrer = $this->report_m2->get_demographic_referrer_annual1();
        $demographic_users = $this->report_m2->get_demographic_users_annual1();
        
        $this->fpdf->AliasNbPages();
        $this->fpdf->AddPage('L','Letter');
        $this->fpdf->SetAutoPageBreak(true, 10);
        
        $this->fpdf->SetDrawColor(150,150,150);   // Set Line and Border color
        
        //Draw rectangles
        $this->fpdf->Cell(10);
        $this->fpdf->SetFillColor(247,247,247);       //Set Fill color
        //$this->fpdf->Rect(10,10,260,178);             // Main box
        $this->fpdf->Rect(10,10,45,30,'D',true);      //Left box
        $this->fpdf->Rect(55,10,185,30,'D',true);     //Mid box
        $this->fpdf->Rect(240,10,30,30,'D',true);     //Right box
        
        //Header
        $this->fpdf->Image(base_url().'system/cms/themes/pyrocms/img/logo_small.png',12,20,40);
        $this->fpdf->SetY(15);
        $this->fpdf->SetX(57);
        $this->fpdf->SetFont('Helvetica','',10);
        $this->fpdf->SetTextColor(0,0,0);
        $this->fpdf->Cell(62,7,'FHSIS ANNUAL REPORT FOR YEAR',0,0,'L');
        $this->fpdf->Cell(12,7,$year,'ltrB',1,'L');
        $this->fpdf->SetX(57);
        $this->fpdf->Cell(32,7,'Municipality/City of:',0,0,'L');
        $this->fpdf->Cell(30,7,$default_city,'ltrB',1,'L');
        $this->fpdf->SetX(57);
        $this->fpdf->Cell(16,7,'Province:',0,0,'L');
        $this->fpdf->Cell(40,7,$default_province,'ltrB',0,'L');
        $this->fpdf->Cell(65,7,'Projected Population of the Year:',0,0,'R');
        $this->fpdf->SetX(178);
        $this->fpdf->Cell(15,7,number_format($ppoty),'ltrB',1,'C');
        
        $this->fpdf->SetFillColor(247,247,247);
        
        $this->fpdf->SetY(40);
        $this->fpdf->SetFont('Helvetica','B',12);
        $this->fpdf->Cell(260,15,'DEMOGRAPHIC PROFILE',1,1,'C',true);
        
        $this->fpdf->SetFont('Helvetica','B',9);
        $this->fpdf->Cell(50,7,'','LTRb',0,'C');
        $this->fpdf->Cell(60,7,'Number',1,0,'C');
        $this->fpdf->Cell(25,7,'Ratio to',0,0,'C');
        $this->fpdf->Cell(75,7,'','LTRb',0,'C');
        $this->fpdf->Cell(50,7,'Recommendations/','LTRb',1,'C');
        
        $this->fpdf->Cell(50,7,'Indicators','LtRB',0,'C');
        $this->fpdf->Cell(20,7,'Male','LtRB',0,'C');
        $this->fpdf->Cell(20,7,'Female','LtRB',0,'C');
        $this->fpdf->Cell(20,7,'Total','LtRB',0,'C');
        $this->fpdf->Cell(25,7,'Pop',0,0,'C');
        $this->fpdf->Cell(75,7,'Interpretation','LtRB',0,'C');
        $this->fpdf->Cell(50,7,'Action Taken','LtRB',1,'C');
        
        $this->fpdf->SetFont('Helvetica','I',9);
        
        $this->fpdf->Cell(50,8,'Col. 1',1,0,'C');
        $this->fpdf->Cell(20,8,'Col. 2',1,0,'L');
        $this->fpdf->Cell(20,8,'Col. 3',1,0,'L');
        $this->fpdf->Cell(20,8,'Col. 4',1,0,'L');
        $this->fpdf->Cell(25,8,'Col. 5',1,0,'C');
        $this->fpdf->Cell(75,8,'Col. 6',1,0,'C');
        $this->fpdf->Cell(50,8,'Col. 7',1,1,'C');
        
        $this->fpdf->SetFont('Helvetica','',8);
        
        $this->fpdf->Cell(50,8,'Barangays',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,$demographic_brgy['total_brgy'],1,0,'C');
        $this->fpdf->Cell(25,8,'',1,0,'C');
        $this->fpdf->Cell(75,8,'','LtRb',0,'C');
        $this->fpdf->Cell(50,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(50,8,'Barangay Health Stations',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,$demographic_brgy['total_bhs'],1,0,'C');
        $this->fpdf->Cell(25,8,'',1,0,'C');
        $this->fpdf->Cell(75,8,'','LtRb',0,'C');
        $this->fpdf->Cell(50,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(50,8,'Doctors',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,$demographic_referrer['total_doctors'],1,0,'C');
        $this->fpdf->Cell(25,8,'',1,0,'C');
        $this->fpdf->Cell(75,8,'','LtRb',0,'C');
        $this->fpdf->Cell(50,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(50,8,'Dentist',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,$demographic_referrer['total_dentists'],1,0,'C');
        $this->fpdf->Cell(25,8,'',1,0,'C');
        $this->fpdf->Cell(75,8,'','LtRb',0,'C');
        $this->fpdf->Cell(50,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(50,8,'Nurses',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,$demographic_referrer['total_nurses'],1,0,'C');
        $this->fpdf->Cell(25,8,'',1,0,'C');
        $this->fpdf->Cell(75,8,'','LtRb',0,'C');
        $this->fpdf->Cell(50,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(50,8,'Midwives',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,$demographic_referrer['total_midwives'],1,0,'C');
        $this->fpdf->Cell(25,8,'',1,0,'C');
        $this->fpdf->Cell(75,8,'','LtRb',0,'C');
        $this->fpdf->Cell(50,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(50,8,'Nutritionist',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,$demographic_users['total_nutritionist'],1,0,'C');
        $this->fpdf->Cell(25,8,'',1,0,'C');
        $this->fpdf->Cell(75,8,'','LtRb',0,'C');
        $this->fpdf->Cell(50,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(50,8,'Medical Technologists',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'L');
        $this->fpdf->Cell(20,8,$demographic_users['total_medtechs'],1,0,'C');
        $this->fpdf->Cell(25,8,'',1,0,'C');
        $this->fpdf->Cell(75,8,'','LtRb',0,'C');
        $this->fpdf->Cell(50,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(50,8,'Sanitary Engineers',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,$demographic_users['total_sanitary_eng'],1,0,'C');
        $this->fpdf->Cell(25,8,'',1,0,'C');
        $this->fpdf->Cell(75,8,'','LtRb',0,'C');
        $this->fpdf->Cell(50,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(50,8,'Sanitary Inspectors',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,$demographic_users['total_sanitary_ins'],1,0,'C');
        $this->fpdf->Cell(25,8,'',1,0,'C');
        $this->fpdf->Cell(75,8,'','LtRb',0,'C');
        $this->fpdf->Cell(50,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(50,8,'Active BHWs',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,$demographic_users['total_bhws'],1,0,'C');
        $this->fpdf->Cell(25,8,'',1,0,'C');
        $this->fpdf->Cell(75,8,'','LtRb',0,'C');
        $this->fpdf->Cell(50,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(50,8,'',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'L');
        $this->fpdf->Cell(25,8,'',1,0,'L');
        $this->fpdf->Cell(75,8,'','LtRb',0,'C');
        $this->fpdf->Cell(50,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(50,8,'',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'L');
        $this->fpdf->Cell(25,8,'',1,0,'L');
        $this->fpdf->Cell(75,8,'','LtRb',0,'C');
        $this->fpdf->Cell(50,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(260,7,'',1,0,'L');
        
        $this->a1_header_icon();
        $this->footer();
            
        //$this->fpdf->Output();
    }
    
    function rhu_a1_environmental($year)
    {
        $environmental = $this->report_m2->get_environment_annual1($year);
        
            $hh_total = $environmental['hh_total'] ;
            if($environmental['hh_safe_water']>0 && $environmental['hh_total']>0)
                {$hh_safe_water_per = number_format($environmental['hh_safe_water']/$hh_total*100,2);}
            else
                {$hh_safe_water_per = "-";}
                
            if($environmental['hh_safe_water_lvl1']>0 && $environmental['hh_total']>0) 
                {$hh_safe_water_lvl1_per = number_format($environmental['hh_safe_water_lvl1']/$hh_total*100,2);}
            else
                {$hh_safe_water_lvl1_per = "-";}
                
            if($environmental['hh_safe_water_lvl2']>0 && $environmental['hh_total']>0)
                {$hh_safe_water_lvl2_per = number_format($environmental['hh_safe_water_lvl2']/$hh_total*100,2);}
            else
                {$hh_safe_water_lvl2_per = "-";}
                
            if($environmental['hh_safe_water_lvl3']>0 && $environmental['hh_total']>0)
                {$hh_safe_water_lvl3_per = number_format($environmental['hh_safe_water_lvl3']/$hh_total*100,2);}
            else
                {$hh_safe_water_lvl3_per = "-";}
                
            if($environmental['hh_sanitary_toilet']>0 && $environmental['hh_total']>0)
                {$hh_sanitary_toilet_per = number_format($environmental['hh_sanitary_toilet']/$hh_total*100,2);}
            else
                {$hh_sanitary_toilet_per = "-";}
                
            if($environmental['hh_satisfactory_waste_disposal']>0 && $environmental['hh_total']>0)
                {$hh_satisfactory_waste_disposal_per = number_format($environmental['hh_satisfactory_waste_disposal']/$hh_total*100,2);}
            else
                {$hh_satisfactory_waste_disposal_per = "-";}
                
            if($environmental['hh_complete_sanitation_facility']>0 && $environmental['hh_total']>0)
                {$hh_complete_sanitation_facility_per = number_format($environmental['hh_complete_sanitation_facility']/$hh_total*100,2);}
            else
                {$hh_complete_sanitation_facility_per = "-";}
                
            if($environmental['food_establishment']>0 && $environmental['hh_total']>0)
                {$food_establishment_per = number_format($environmental['food_establishment']/$hh_total*100,2);}
            else
                {$food_establishment_per = "-";}
                
            if($environmental['food_establishment_sanitary_permit']>0 && $environmental['hh_total']>0)
                {$food_establishment_sanitary_permit_per = number_format($environmental['food_establishment_sanitary_permit']/$hh_total*100,2);}
            else
                {$food_establishment_sanitary_permit_per = "-";}
            
            if($environmental['food_handler']>0 && $environmental['hh_total']>0)
                {$food_handler_per = number_format($environmental['food_handler']/$hh_total*100,2);}
            else
                {$food_handler_per = "-";}
                
            if($environmental['food_handler_health_certificate']>0 && $environmental['hh_total']>0)
                {$food_handler_health_certificate_per = number_format($environmental['food_handler_health_certificate']/$hh_total*100,2);}
            else
                {$food_handler_health_certificate_per = "-";}
                
            if($environmental['salt_sample_tested']>0 && $environmental['hh_total']>0)
                {$salt_sample_tested_per = number_format($environmental['salt_sample_tested']/$hh_total*100,2);}
            else
                {$salt_sample_tested_per = "-";}
                
            if($environmental['salt_sample_iodine']>0 && $environmental['hh_total']>0)
                {$salt_sample_iodine_per = number_format($environmental['salt_sample_iodine']/$hh_total*100,2);}
            else
                {$salt_sample_iodine_per = "-";}
        
        $this->fpdf->AliasNbPages();
        $this->fpdf->AddPage('L','Letter');
        $this->fpdf->SetAutoPageBreak(true, 10);
        
        $this->fpdf->SetDrawColor(150,150,150);   // Set Line and Border color
        
        //Draw rectangles
        $this->fpdf->Cell(15);
        $this->fpdf->SetFillColor(247,247,247);       //Set Fill color
        //$this->fpdf->Rect(10,15,260,155);             // Main box  
        
        $this->fpdf->SetFillColor(247,247,247);
        
        $this->fpdf->SetY(15);
        $this->fpdf->SetFont('Helvetica','B',12);
        $this->fpdf->SetTextColor(0,0,0);
        $this->fpdf->Cell(260,15,'ENVIRONMENTAL',1,1,'C',true);
        
        $this->fpdf->SetFont('Helvetica','B',9);
        $this->fpdf->Cell(90,5,'','LTRb',0,'C');
        $this->fpdf->Cell(30,5,'','LTRb',0,'C');
        $this->fpdf->Cell(30,5,'','LTRb',0,'C');
        $this->fpdf->Cell(60,5,'','LTRb',0,'C');
        $this->fpdf->Cell(50,5,'Recommendations/','LTRb',1,'C');
        
        $this->fpdf->Cell(90,5,'Indicators','LtRB',0,'C');
        $this->fpdf->Cell(30,5,'No.','LtRB',0,'C');
        $this->fpdf->Cell(30,5,'%','LtRB',0,'C');
        $this->fpdf->Cell(60,5,'Interpretation','LtRB',0,'C');
        $this->fpdf->Cell(50,5,'Actions Taken','LtRB',1,'C');
        
        $this->fpdf->SetFont('Helvetica','I',9);
        
        $this->fpdf->Cell(90,8,'Col. 1',1,0,'C');
        $this->fpdf->Cell(30,8,'Col. 2',1,0,'C');
        $this->fpdf->Cell(30,8,'Col. 3',1,0,'C');
        $this->fpdf->Cell(60,8,'Col. 4',1,0,'C');
        $this->fpdf->Cell(50,8,'Col. 5',1,1,'C');
        
        $this->fpdf->SetFont('Helvetica','',9);
        
        $this->fpdf->Cell(90,8,'Households(HH)',1,0,'L');
        $this->fpdf->Cell(30,8,$environmental['hh_total'],1,0,'C');
        $this->fpdf->Cell(30,8,'',1,0,'C');
        $this->fpdf->Cell(60,8,'','LtRb',0,'C');
        $this->fpdf->Cell(50,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(90,8,'Households with access to improve or safe water supply?',1,0,'L');
        $this->fpdf->Cell(30,8,$environmental['hh_safe_water'],1,0,'C');
        $this->fpdf->Cell(30,8,$hh_safe_water_per,1,0,'C');
        $this->fpdf->Cell(60,8,'','LtRb',0,'C');
        $this->fpdf->Cell(50,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(90,8,'Level I?',1,0,'L');
        $this->fpdf->Cell(30,8,$environmental['hh_safe_water_lvl1'],1,0,'C');
        $this->fpdf->Cell(30,8,$hh_safe_water_lvl1_per,1,0,'C');
        $this->fpdf->Cell(60,8,'','LtRb',0,'C');
        $this->fpdf->Cell(50,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(90,8,'Level II?',1,0,'L');
        $this->fpdf->Cell(30,8,$environmental['hh_safe_water_lvl2'],1,0,'C');
        $this->fpdf->Cell(30,8,$hh_safe_water_lvl2_per,1,0,'C');
        $this->fpdf->Cell(60,8,'','LtRb',0,'C');
        $this->fpdf->Cell(50,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(90,8,'Level III?',1,0,'L');
        $this->fpdf->Cell(30,8,$environmental['hh_safe_water_lvl3'],1,0,'C');
        $this->fpdf->Cell(30,8,$hh_safe_water_lvl3_per,1,0,'C');
        $this->fpdf->Cell(60,8,'','LtRb',0,'C');
        $this->fpdf->Cell(50,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(90,8,'Households with sanitary toilet Facilities?',1,0,'L');
        $this->fpdf->Cell(30,8,$environmental['hh_sanitary_toilet'],1,0,'C');
        $this->fpdf->Cell(30,8,$hh_sanitary_toilet_per,1,0,'C');
        $this->fpdf->Cell(60,8,'','LtRb',0,'C');
        $this->fpdf->Cell(50,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(90,8,'Households with satisfatory disposal of solid waste?',1,0,'L');
        $this->fpdf->Cell(30,8,$environmental['hh_satisfactory_waste_disposal'],1,0,'C');
        $this->fpdf->Cell(30,8,$hh_satisfactory_waste_disposal_per,1,0,'C');
        $this->fpdf->Cell(60,8,'','LtRb',0,'C');
        $this->fpdf->Cell(50,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(90,8,'Households with complete basic facilities?',1,0,'L');
        $this->fpdf->Cell(30,8,$environmental['hh_complete_sanitation_facility'],1,0,'C');
        $this->fpdf->Cell(30,8,$hh_complete_sanitation_facility_per,1,0,'C');
        $this->fpdf->Cell(60,8,'','LtRb',0,'C');
        $this->fpdf->Cell(50,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(90,8,'Food Establishments',1,0,'L');
        $this->fpdf->Cell(30,8,$environmental['food_establishment'],1,0,'C');
        $this->fpdf->Cell(30,8,$food_establishment_per,1,0,'C');
        $this->fpdf->Cell(60,8,'','LtRb',0,'C');
        $this->fpdf->Cell(50,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(90,8,'Food Establishments with Sanitary Permit',1,0,'L');
        $this->fpdf->Cell(30,8,$environmental['food_establishment_sanitary_permit'],1,0,'C');
        $this->fpdf->Cell(30,8,$food_establishment_sanitary_permit_per,1,0,'C');
        $this->fpdf->Cell(60,8,'','LtRb',0,'C');
        $this->fpdf->Cell(50,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(90,8,'Food Handlers',1,0,'L');
        $this->fpdf->Cell(30,8,$environmental['food_handler'],1,0,'C');
        $this->fpdf->Cell(30,8,$food_handler_per,1,0,'C');
        $this->fpdf->Cell(60,8,'','LtRb',0,'C');
        $this->fpdf->Cell(50,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(90,8,'Food Handlers with Health Certificate',1,0,'L');
        $this->fpdf->Cell(30,8,$environmental['food_handler_health_certificate'],1,0,'C');
        $this->fpdf->Cell(30,8,$food_handler_health_certificate_per,1,0,'C');
        $this->fpdf->Cell(60,8,'','LtRb',0,'C');
        $this->fpdf->Cell(50,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(90,8,'Salt Sample Tested',1,0,'L');
        $this->fpdf->Cell(30,8,$environmental['salt_sample_tested'],1,0,'C');
        $this->fpdf->Cell(30,8,$salt_sample_tested_per,1,0,'C');
        $this->fpdf->Cell(60,8,'','LtRb',0,'C');
        $this->fpdf->Cell(50,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(90,8,'Salt Sample Tested found (+) for Iodine',1,0,'L');
        $this->fpdf->Cell(30,8,$environmental['salt_sample_iodine'],1,0,'C');
        $this->fpdf->Cell(30,8,$salt_sample_iodine_per,1,0,'C');
        $this->fpdf->Cell(60,8,'','LtRb',0,'C');
        $this->fpdf->Cell(50,8,'','LtRb',1,'C');
        
        $this->fpdf->SetFont('Helvetica','I',8);
        $this->fpdf->Cell(260,6,'Denominator   ?No. of Households   ?No. of Food Establishments   ?No. of Food Handlers   *Salt Sample Tested',1,0,'L');
        
        $this->footer();
        $this->a1_pagenum();
        
        //$this->fpdf->Output();
    }
    
    function rhu_a1_livebirths($year)
    {
        $livebirths = $this->report_m2->get_livebirths_annual1($year);
        
        $this->fpdf->AliasNbPages();
        $this->fpdf->AddPage('L','Letter');
        $this->fpdf->SetAutoPageBreak(true, 10);
        
        $this->fpdf->SetDrawColor(150,150,150);   // Set Line and Border color
        
        //Draw rectangles
        $this->fpdf->Cell(15);
        $this->fpdf->SetFillColor(247,247,247);       //Set Fill color
        //$this->fpdf->Rect(10,15,260,155);             // Main box  
        
        $this->fpdf->SetFillColor(247,247,247);
        
        $this->fpdf->SetY(15);
        $this->fpdf->SetFont('Helvetica','B',12);
        $this->fpdf->SetTextColor(0,0,0);
        $this->fpdf->Cell(260,15,'LIVEBIRTHS',1,1,'C',true);
        
        $this->fpdf->SetFont('Helvetica','B',9);
        $this->fpdf->Cell(75,7,'','LTRb',0,'C');
        $this->fpdf->Cell(60,7,'Number',1,0,'C');
        $this->fpdf->Cell(25,7,'',0,0,'C');
        $this->fpdf->Cell(60,7,'','LTRb',0,'C');
        $this->fpdf->Cell(40,7,'Recommendations/','LTRb',1,'C');
        
        $this->fpdf->Cell(75,7,'Indicators','LtRB',0,'C');
        $this->fpdf->Cell(20,7,'Male','LtRB',0,'C');
        $this->fpdf->Cell(20,7,'Female','LtRB',0,'C');
        $this->fpdf->Cell(20,7,'Total','LtRB',0,'C');
        $this->fpdf->Cell(25,7,'%',0,0,'C');
        $this->fpdf->Cell(60,7,'Interpretation','LtRB',0,'C');
        $this->fpdf->Cell(40,7,'Action Taken','LtRB',1,'C');
        
        $this->fpdf->SetFont('Helvetica','I',9);
        
        $this->fpdf->Cell(75,8,'Col. 1',1,0,'C');
        $this->fpdf->Cell(20,8,'Col. 2',1,0,'L');
        $this->fpdf->Cell(20,8,'Col. 3',1,0,'L');
        $this->fpdf->Cell(20,8,'Col. 4',1,0,'L');
        $this->fpdf->Cell(25,8,'Col. 5',1,0,'C');
        $this->fpdf->Cell(60,8,'Col. 6',1,0,'C');
        $this->fpdf->Cell(40,8,'Col. 7',1,1,'C');
        
        $this->fpdf->SetFont('Helvetica','',8);
        
        $this->fpdf->Cell(75,8,'Livebirths(LB)',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,$livebirths['total_lb'],1,0,'C');
        $this->fpdf->Cell(25,8,'',1,0,'C');
        $this->fpdf->Cell(60,8,'','LtRb',0,'C');
        $this->fpdf->Cell(40,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(75,8,'Livebirths with weights 2500 grams & greater?',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,$livebirths['total_lb_weight_2500up'],1,0,'C');
        $this->fpdf->Cell(25,8,'',1,0,'C');
        $this->fpdf->Cell(60,8,'','LtRb',0,'C');
        $this->fpdf->Cell(40,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(75,8,'Livebirths with weights less than 2500 grams?',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,$livebirths['total_lb_weight_2500down'],1,0,'C');
        $this->fpdf->Cell(25,8,'',1,0,'C');
        $this->fpdf->Cell(60,8,'','LtRb',0,'C');
        $this->fpdf->Cell(40,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(75,8,'Livebirths not known weight?',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,$livebirths['total_lb_weight_unknown'],1,0,'C');
        $this->fpdf->Cell(25,8,'',1,0,'C');
        $this->fpdf->Cell(60,8,'','LtRb',0,'C');
        $this->fpdf->Cell(40,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(75,8,'Livebirths delivered by Doctors?',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,$livebirths['total_lb_by_doctors'],1,0,'C');
        $this->fpdf->Cell(25,8,'',1,0,'C');
        $this->fpdf->Cell(60,8,'','LtRb',0,'C');
        $this->fpdf->Cell(40,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(75,8,'Livebirths delivered by Nurses?',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,$livebirths['total_lb_by_nurses'],1,0,'C');
        $this->fpdf->Cell(25,8,'',1,0,'C');
        $this->fpdf->Cell(60,8,'','LtRb',0,'C');
        $this->fpdf->Cell(40,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(75,8,'Livebirths delivered by Midwives?',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,$livebirths['total_lb_by_midwived'],1,0,'C');
        $this->fpdf->Cell(25,8,'',1,0,'C');
        $this->fpdf->Cell(60,8,'','LtRb',0,'C');
        $this->fpdf->Cell(40,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(75,8,'Livebirths delivered by hilot/TBA',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,$livebirths['total_lb_by_htba'],1,0,'C');
        $this->fpdf->Cell(25,8,'',1,0,'C');
        $this->fpdf->Cell(60,8,'','LtRb',0,'C');
        $this->fpdf->Cell(40,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(75,8,'Livebirths delivered by others?',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,$livebirths['total_lb_by_others'],1,0,'C');
        $this->fpdf->Cell(25,8,'',1,0,'C');
        $this->fpdf->Cell(60,8,'','LtRb',0,'C');
        $this->fpdf->Cell(40,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(75,8,'',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'L');
        $this->fpdf->Cell(25,8,'',1,0,'L');
        $this->fpdf->Cell(60,8,'','LtRb',0,'C');
        $this->fpdf->Cell(40,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(75,8,'',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'L');
        $this->fpdf->Cell(25,8,'',1,0,'L');
        $this->fpdf->Cell(60,8,'','LtRb',0,'C');
        $this->fpdf->Cell(40,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(75,8,'',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'L');
        $this->fpdf->Cell(25,8,'',1,0,'L');
        $this->fpdf->Cell(60,8,'','LtRb',0,'C');
        $this->fpdf->Cell(40,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(75,8,'',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'L');
        $this->fpdf->Cell(25,8,'',1,0,'L');
        $this->fpdf->Cell(60,8,'','LtRb',0,'C');
        $this->fpdf->Cell(40,8,'','LtRb',1,'C');
        
        $this->fpdf->SetFont('Helvetica','I',8);
        
        $this->fpdf->Cell(260,6,'Denominator  ? Livebirths',1,0,'L');
        
        $this->a1_pagenum();
        $this->footer();
        
        //$this->fpdf->Output();
    }
    
    function rhu_a1_natality($year)
    {
        $natality = $this->report_m2->get_delivery_annual1($year);
          
        $this->fpdf->AliasNbPages();
        $this->fpdf->AddPage('L','Letter');
        $this->fpdf->SetAutoPageBreak(true, 10);
        
        $this->fpdf->SetDrawColor(150,150,150);   // Set Line and Border color
        
        //Draw rectangles
        $this->fpdf->Cell(15);
        $this->fpdf->SetFillColor(247,247,247);       //Set Fill color
        //$this->fpdf->Rect(10,15,260,155);             // Main box  
        
        $this->fpdf->SetFillColor(247,247,247);
        
        $this->fpdf->SetY(15);
        $this->fpdf->SetFont('Helvetica','B',12);
        $this->fpdf->SetTextColor(0,0,0);
        $this->fpdf->Cell(260,15,'NATALITY - DELIVERIES',1,1,'C',true);
        
        $this->fpdf->SetFont('Helvetica','B',9);
        $this->fpdf->Cell(90,5,'','LTRb',0,'C');
        $this->fpdf->Cell(30,5,'','LTRb',0,'C');
        $this->fpdf->Cell(30,5,'','LTRb',0,'C');
        $this->fpdf->Cell(60,5,'','LTRb',0,'C');
        $this->fpdf->Cell(50,5,'Recommendations/','LTRb',1,'C');
        
        $this->fpdf->Cell(90,5,'Indicators','LtRB',0,'C');
        $this->fpdf->Cell(30,5,'No.','LtRB',0,'C');
        $this->fpdf->Cell(30,5,'%','LtRB',0,'C');
        $this->fpdf->Cell(60,5,'Interpretation','LtRB',0,'C');
        $this->fpdf->Cell(50,5,'Actions Taken','LtRB',1,'C');
        
        $this->fpdf->SetFont('Helvetica','I',9);
        
        $this->fpdf->Cell(90,8,'Col. 1',1,0,'C');
        $this->fpdf->Cell(30,8,'Col. 2',1,0,'C');
        $this->fpdf->Cell(30,8,'Col. 3',1,0,'C');
        $this->fpdf->Cell(60,8,'Col. 4',1,0,'C');
        $this->fpdf->Cell(50,8,'Col. 5',1,1,'C');
        
        $this->fpdf->SetFont('Helvetica','',9);
        
        $this->fpdf->Cell(90,8,'Deliveries',1,0,'L');
        $this->fpdf->Cell(30,8,$natality['total_deliveries'],1,0,'C');
        $this->fpdf->Cell(30,8,'',1,0,'C');
        $this->fpdf->Cell(60,8,'','LtRb',0,'C');
        $this->fpdf->Cell(50,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(90,8,'Normal Pregnancy?',1,0,'L');
        $this->fpdf->Cell(30,8,$natality['total_pregancy_normal'],1,0,'C');
        $this->fpdf->Cell(30,8,'',1,0,'C');
        $this->fpdf->Cell(60,8,'','LtRb',0,'C');
        $this->fpdf->Cell(50,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(90,8,'Unknown Pregnancy?',1,0,'L');
        $this->fpdf->Cell(30,8,$natality['total_pregancy_unknown'],1,0,'C');
        $this->fpdf->Cell(30,8,'',1,0,'C');
        $this->fpdf->Cell(60,8,'','LtRb',0,'C');
        $this->fpdf->Cell(50,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(90,8,'Risk Pregnancy?',1,0,'L');
        $this->fpdf->Cell(30,8,$natality['total_pregancy_risk'],1,0,'C');
        $this->fpdf->Cell(30,8,'',1,0,'C');
        $this->fpdf->Cell(60,8,'','LtRb',0,'C');
        $this->fpdf->Cell(50,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(90,8,'Normal Deliveries?',1,0,'L');
        $this->fpdf->Cell(30,8,$natality['total_delivery_normal'],1,0,'C');
        $this->fpdf->Cell(30,8,'',1,0,'C');
        $this->fpdf->Cell(60,8,'','LtRb',0,'C');
        $this->fpdf->Cell(50,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(90,8,'Normal Deliveries at Home?',1,0,'L');
        $this->fpdf->Cell(30,8,$natality['total_delivery_normal_home'],1,0,'C');
        $this->fpdf->Cell(30,8,'',1,0,'C');
        $this->fpdf->Cell(60,8,'','LtRb',0,'C');
        $this->fpdf->Cell(50,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(90,8,'Normal Deliveries at Hospital?',1,0,'L');
        $this->fpdf->Cell(30,8,$natality['total_delivery_normal_hospital'],1,0,'C');
        $this->fpdf->Cell(30,8,'',1,0,'C');
        $this->fpdf->Cell(60,8,'','LtRb',0,'C');
        $this->fpdf->Cell(50,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(90,8,'Normal Deliveries - Other Place?',1,0,'L');
        $this->fpdf->Cell(30,8,$natality['total_delivery_normal_others'],1,0,'C');
        $this->fpdf->Cell(30,8,'',1,0,'C');
        $this->fpdf->Cell(60,8,'','LtRb',0,'C');
        $this->fpdf->Cell(50,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(90,8,'Other Type of Deliveries?',1,0,'L');
        $this->fpdf->Cell(30,8,$natality['total_delivery_other'],1,0,'C');
        $this->fpdf->Cell(30,8,'',1,0,'C');
        $this->fpdf->Cell(60,8,'','LtRb',0,'C');
        $this->fpdf->Cell(50,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(90,8,'Other Type of Deliveries at Home?',1,0,'L');
        $this->fpdf->Cell(30,8,$natality['total_delivery_other_home'],1,0,'C');
        $this->fpdf->Cell(30,8,'',1,0,'C');
        $this->fpdf->Cell(60,8,'','LtRb',0,'C');
        $this->fpdf->Cell(50,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(90,8,'Other Type of Deliveries at Hospital?',1,0,'L');
        $this->fpdf->Cell(30,8,$natality['total_delivery_other_hospital'],1,0,'C');
        $this->fpdf->Cell(30,8,'',1,0,'C');
        $this->fpdf->Cell(60,8,'','LtRb',0,'C');
        $this->fpdf->Cell(50,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(90,8,'Other Type of Deliveries - Other Place?',1,0,'L');
        $this->fpdf->Cell(30,8,$natality['total_delivery_other_other'],1,0,'C');
        $this->fpdf->Cell(30,8,'',1,0,'C');
        $this->fpdf->Cell(60,8,'','LtRb',0,'C');
        $this->fpdf->Cell(50,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(90,8,'',1,0,'L');
        $this->fpdf->Cell(30,8,'',1,0,'C');
        $this->fpdf->Cell(30,8,'',1,0,'C');
        $this->fpdf->Cell(60,8,'','LtRb',0,'C');
        $this->fpdf->Cell(50,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(90,8,'',1,0,'L');
        $this->fpdf->Cell(30,8,'',1,0,'C');
        $this->fpdf->Cell(30,8,'',1,0,'C');
        $this->fpdf->Cell(60,8,'','LtRb',0,'C');
        $this->fpdf->Cell(50,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(90,8,'',1,0,'L');
        $this->fpdf->Cell(30,8,'',1,0,'C');
        $this->fpdf->Cell(30,8,'',1,0,'C');
        $this->fpdf->Cell(60,8,'','LtRb',0,'C');
        $this->fpdf->Cell(50,8,'','LtRb',1,'C');
        
        $this->fpdf->SetFont('Helvetica','I',8);
        $this->fpdf->Cell(260,6,'Denominators   ? Livebirths   ? Deliveries   ? Normal Deliveries   ? Other Type of Deliveries',1,0,'L');
        
        $this->footer();
        $this->a1_pagenum();
        
        //$this->fpdf->Output();
    }
    
    function rhu_a1_mortality($year)
    {
        $base_where = $this->session->userdata('base_where');
            
        // Using this data, get the relevant results
        $clients = $this->clients_m
        ->order_by('last_name', 'ASC')
        ->get_many_by($base_where);
        
        $this->fpdf->AliasNbPages();
        $this->fpdf->AddPage('L','Letter');
        $this->fpdf->SetAutoPageBreak(true, 10);
        
        $this->fpdf->SetDrawColor(150,150,150);   // Set Line and Border color
        
        //Draw rectangles
        $this->fpdf->Cell(15);
        $this->fpdf->SetFillColor(247,247,247);       //Set Fill color
        //$this->fpdf->Rect(10,15,260,155);             // Main box  
        
        $this->fpdf->SetFillColor(247,247,247);
        
        $this->fpdf->SetY(15);
        $this->fpdf->SetFont('Helvetica','B',12);
        $this->fpdf->SetTextColor(0,0,0);
        $this->fpdf->Cell(260,15,'MORTALITY',1,1,'C',true);
        
        $this->fpdf->SetFont('Helvetica','B',9);
        $this->fpdf->Cell(75,7,'','LTRb',0,'C');
        $this->fpdf->Cell(60,7,'Number',1,0,'C');
        $this->fpdf->Cell(25,7,'',0,0,'C');
        $this->fpdf->Cell(60,7,'','LTRb',0,'C');
        $this->fpdf->Cell(40,7,'Recommendations/','LTRb',1,'C');
        
        $this->fpdf->Cell(75,7,'Indicators','LtRB',0,'C');
        $this->fpdf->Cell(20,7,'Male','LtRB',0,'C');
        $this->fpdf->Cell(20,7,'Female','LtRB',0,'C');
        $this->fpdf->Cell(20,7,'Total','LtRB',0,'C');
        $this->fpdf->Cell(25,7,'Rate',0,0,'C');
        $this->fpdf->Cell(60,7,'Interpretation','LtRB',0,'C');
        $this->fpdf->Cell(40,7,'Action Taken','LtRB',1,'C');
        
        $this->fpdf->SetFont('Helvetica','I',9);
        
        $this->fpdf->Cell(75,8,'Col. 1',1,0,'C');
        $this->fpdf->Cell(20,8,'Col. 2',1,0,'L');
        $this->fpdf->Cell(20,8,'Col. 3',1,0,'L');
        $this->fpdf->Cell(20,8,'Col. 4',1,0,'L');
        $this->fpdf->Cell(25,8,'Col. 5',1,0,'C');
        $this->fpdf->Cell(60,8,'Col. 6',1,0,'C');
        $this->fpdf->Cell(40,8,'Col. 7',1,1,'C');
        
        $this->fpdf->SetFont('Helvetica','',8);
        
        $this->fpdf->Cell(75,8,'Deaths?',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'L');
        $this->fpdf->Cell(25,8,'',1,0,'L');
        $this->fpdf->Cell(60,8,'','LtRb',0,'C');
        $this->fpdf->Cell(40,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(75,8,'Infant Deaths?',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'L');
        $this->fpdf->Cell(25,8,'',1,0,'L');
        $this->fpdf->Cell(60,8,'','LtRb',0,'C');
        $this->fpdf->Cell(40,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(75,8,'Maternal Deaths?',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'L');
        $this->fpdf->Cell(25,8,'',1,0,'L');
        $this->fpdf->Cell(60,8,'','LtRb',0,'C');
        $this->fpdf->Cell(40,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(75,8,'Deaths due to Neonatal Tetanus?',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'L');
        $this->fpdf->Cell(25,8,'',1,0,'L');
        $this->fpdf->Cell(60,8,'','LtRb',0,'C');
        $this->fpdf->Cell(40,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(75,8,'Perinatal Deaths?',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'L');
        $this->fpdf->Cell(25,8,'',1,0,'L');
        $this->fpdf->Cell(60,8,'','LtRb',0,'C');
        $this->fpdf->Cell(40,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(75,8,'Deaths among child Under 5 yrs. old?',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'L');
        $this->fpdf->Cell(25,8,'',1,0,'L');
        $this->fpdf->Cell(60,8,'','LtRb',0,'C');
        $this->fpdf->Cell(40,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(75,8,'',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'L');
        $this->fpdf->Cell(25,8,'',1,0,'L');
        $this->fpdf->Cell(60,8,'','LtRb',0,'C');
        $this->fpdf->Cell(40,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(75,8,'',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'L');
        $this->fpdf->Cell(25,8,'',1,0,'L');
        $this->fpdf->Cell(60,8,'','LtRb',0,'C');
        $this->fpdf->Cell(40,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(75,8,'',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'L');
        $this->fpdf->Cell(25,8,'',1,0,'L');
        $this->fpdf->Cell(60,8,'','LtRb',0,'C');
        $this->fpdf->Cell(40,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(75,8,'',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'L');
        $this->fpdf->Cell(25,8,'',1,0,'L');
        $this->fpdf->Cell(60,8,'','LtRb',0,'C');
        $this->fpdf->Cell(40,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(75,8,'',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'L');
        $this->fpdf->Cell(25,8,'',1,0,'L');
        $this->fpdf->Cell(60,8,'','LtRb',0,'C');
        $this->fpdf->Cell(40,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(75,8,'',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'L');
        $this->fpdf->Cell(25,8,'',1,0,'L');
        $this->fpdf->Cell(60,8,'','LtRb',0,'C');
        $this->fpdf->Cell(40,8,'','LtRb',1,'C');
        
        $this->fpdf->SetFont('Helvetica','I',8);
        
        $this->fpdf->Cell(260,6,'Denominator  ? Population   ? Livebirths',1,0,'L');
        
        $this->a1_pagenum();
        $this->footer();
        
        //$this->fpdf->Output();
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
    
    function q1_pagenum()
    {
        //Draw a line
        $this->fpdf->SetY(10);
        $this->fpdf->SetX(220);
        
        //Page footer
        $this->fpdf->SetTextColor(128);
        $this->fpdf->SetFont('Helvetica','',7);
        $this->fpdf->Cell(50,4,'FHSIS V 2013 - Q Form (Page '.$this->fpdf->PageNo().' of {nb})',0,1,'R');
    } 
    
    function a1_pagenum()
    {
        //Draw a line
        $this->fpdf->SetY(10);
        $this->fpdf->SetX(220);
        
        //Page footer
        $this->fpdf->SetTextColor(128);
        $this->fpdf->SetFont('Helvetica','',7);
        $this->fpdf->Cell(50,4,'FHSIS V 2013 - A Form (Page '.$this->fpdf->PageNo().' of {nb})',0,1,'R');
    } 
    
    function a2_pagenum()
    {
        //Draw a line
        $this->fpdf->SetY(5);
        $this->fpdf->SetX(229);
        
        //Page footer
        $this->fpdf->SetTextColor(128);
        $this->fpdf->SetFont('Helvetica','',7);
        $this->fpdf->Cell(50,4,'FHSIS V 2013 - A Form (Page '.$this->fpdf->PageNo().' of {nb})',0,1,'R');
    } 
    
    function q1_header_icon()
    {
        $this->fpdf->SetTextColor(0,0,0);
        $this->fpdf->SetY(10);
        $this->fpdf->SetX(240);
        $this->fpdf->SetFont('Helvetica','',6);
        $this->fpdf->Cell(30,5,'FHSIS Version 2013',0,1,'L');
        $this->fpdf->SetX(240);
        $this->fpdf->SetFont('Helvetica','B',60);
        $this->fpdf->Cell(30,15,'Q1',0,1,'C');
        $this->fpdf->SetX(240);
        $this->fpdf->SetFont('Helvetica','B',30);
        $this->fpdf->Cell(30,10,'RHU',0,1,'C');
    }
    
    function m1_header_icon()
    {
        $this->fpdf->SetTextColor(0,0,0);
        $this->fpdf->SetY(10);
        $this->fpdf->SetX(157);
        $this->fpdf->SetFont('Helvetica','',7);
        $this->fpdf->Cell(50,5,'FHSIS Version 2013',0,1,'C');
        $this->fpdf->SetX(157);
        $this->fpdf->SetFont('Helvetica','B',100);
        $this->fpdf->Cell(50,30,'M1',0,1,'C');
        $this->fpdf->SetX(157);
        $this->fpdf->SetFont('Helvetica','B',50);
        $this->fpdf->Cell(50,15,'BHS',0,1,'C');
    }
    
    function a1_header_icon()
    {
        $this->fpdf->SetTextColor(0,0,0);
        $this->fpdf->SetY(10);
        $this->fpdf->SetX(240);
        $this->fpdf->SetFont('Helvetica','',6);
        $this->fpdf->Cell(30,5,'FHSIS Version 2013',0,1,'L');
        $this->fpdf->SetX(240);
        $this->fpdf->SetFont('Helvetica','B',60);
        $this->fpdf->Cell(30,15,'A1',0,1,'C');
        $this->fpdf->SetX(240);
        $this->fpdf->SetFont('Helvetica','B',30);
        $this->fpdf->Cell(30,10,'RHU',0,1,'C');
    }
    
    function a2_header_icon()
    {
        $this->fpdf->SetTextColor(0,0,0);
        $this->fpdf->SetY(10);
        $this->fpdf->SetX(245);
        $this->fpdf->SetFont('Helvetica','',6);
        $this->fpdf->Cell(30,5,'FHSIS Version 2013',0,1,'L');
        $this->fpdf->SetX(245);
        $this->fpdf->SetFont('Helvetica','B',60);
        $this->fpdf->Cell(30,15,'A2',0,1,'C');
        $this->fpdf->SetX(245);
        $this->fpdf->SetFont('Helvetica','B',30);
        $this->fpdf->Cell(30,10,'RHU',0,1,'C');
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