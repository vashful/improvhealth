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
        $this->load->model(array('clients/clients_m', 'clients/clients_region_m', 'clients/clients_province_m', 'clients/clients_city_m', 'clients/clients_barangay_m','consultations/consultations_m','settings/settings_m','bhs_m1/bhs_m1_m','brgy_m1/brgy_m1_m'));
		$this->cache_path = FCPATH.APPPATH.'cache/'.SITE_REF.'/';

		$this->config->load('report');
		$this->lang->load('report'); 
        
        $this->load->library('fpdf'); //Load FPDF library    
    
        $this->data->provinces = array();
		if ($provinces = $this->clients_province_m->order_by('id')->get_all())
		{
			foreach ($provinces as $province)
			{
				$this->data->provinces[$province->id] = $province->name;
			}
		}
		
		$this->data->cities = array();
		if ($cities = $this->clients_city_m->order_by('id')->get_all())
		{
			foreach ($cities as $city)
			{
				$this->data->cities[$city->id] = $city->name;
			}
		}
		
		$this->data->barangays = array();
		if ($barangays = $this->clients_barangay_m->order_by('name')->get_all())
		{
			foreach ($barangays as $barangay)
			{
				$this->data->barangays[$barangay->id] = $barangay->name;
			}
		}                             
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
    $this->data->months = array ('1'=>'January','2'=>'February','3'=>'March','4'=>'April','5'=>'May','6'=>'June','7'=>'July','8'=>'August','9'=>'September','10'=>'October','11'=>'November','12'=>'December');
    $this->data->quarters = array ('1'=>'1st Quarter','2'=>'2nd Quarter','3'=>'3rd Quarter','4'=>'4th Quarter');
    $this->data->years= $years;
		$this->data->tables = $tables;
		$this->data->folders = &$folder_ary;

		$this->template->title($this->module_details['name'])->build('admin/rpt_form', $this->data);   
	}
    
    public function show()
    {
        $this->brgy_m1();
    }
  
    public function brgy_m1()
    {           
        $action = $this->input->post('btnAction');
        
        $month = $this->input->post('m1_month');
        $year = $this->input->post('m1_year');
        $brgy = $this->input->post('m1_brgy'); 
        
        //echo $brgy; 
        
        if($year=='') 
            {$year = date('Y');} 
        if($month=='') 
            {$month = date('m');} 
        
        $default_province = $this->settings_m->get_default_province();
        $default_city = $this->settings_m->get_default_city();
        $station_name_default = $this->settings_m->get_station_name_default();
        $station_name = $this->settings_m->get_station_name(); 
        if($brgy==0)
            {
                $barangay = 'All - '.$station_name;
                $brgy_filename = url_title('All-'.$station_name);
            }
        else
            {
                $barangay = $this->brgy_m1_m->get_brgy_name($brgy);
                $brgy_filename = $barangay;
            }
               
        if($station_name_default==!null && $station_name==!null)
          {$bhs_bhc = $station_name_default.' - '.$station_name;}
        elseif($station_name_default==null)
          {$bhs_bhc = $station_name;}
        elseif($station_name==null)
          {$bhs_bhc = $station_name_default;}
        
        if($month==1)
            {$monthname="January";}
        elseif($month==2)
            {$monthname="February";}
        elseif($month==3)
            {$monthname="March";}
        elseif($month==4)
            {$monthname="April";}
        elseif($month==5)
            {$monthname="May";}
        elseif($month==6)
            {$monthname="June";}
        elseif($month==7)
            {$monthname="July";}
        elseif($month==8)
            {$monthname="August";}
        elseif($month==9)
            {$monthname="September";}
        elseif($month==10)
            {$monthname="October";}
        elseif($month==11)
            {$monthname="November";}
        elseif($month==12)
            {$monthname="December";}
         
        // Using this data, get the relevant results 
        if($brgy==0)
        {
            $maternal = $this->bhs_m1_m->get_medicare_montlyreport($month, $year);
            $family_planning = $this->bhs_m1_m->get_familyplanning_montlyreport($month, $year);
            $childcare = $this->bhs_m1_m->get_childcare_montlyreport($month, $year);
            $sick_children = $this->bhs_m1_m->get_sc_vita_m1($month, $year);
            $sc_anemic = $this->bhs_m1_m->get_sc_anemic_m1($month, $year);
            $inf_lbw = $this->bhs_m1_m->get_sc_lbw_m1($month, $year);
            $sc_diarrhea = $this->bhs_m1_m->get_sc_diarrhea_m1($month, $year);
            $sc_pneumonia = $this->bhs_m1_m->get_sc_pneumonia_m1($month, $year);
        }
        else
        {
            $maternal = $this->brgy_m1_m->get_medicare_montlyreport($month, $year, $brgy);
            $family_planning = $this->brgy_m1_m->get_familyplanning_montlyreport($month, $year, $brgy);
            $childcare = $this->brgy_m1_m->get_childcare_montlyreport($month, $year, $brgy);
            $sick_children = $this->brgy_m1_m->get_sc_vita_m1($month, $year, $brgy);
            $sc_anemic = $this->brgy_m1_m->get_sc_anemic_m1($month, $year, $brgy);
            $inf_lbw = $this->brgy_m1_m->get_sc_lbw_m1($month, $year, $brgy);
            $sc_diarrhea = $this->brgy_m1_m->get_sc_diarrhea_m1($month, $year, $brgy);
            $sc_pneumonia = $this->brgy_m1_m->get_sc_pneumonia_m1($month, $year, $brgy);
        }
        
        $this->fpdf->Open();
        $this->fpdf->AliasNbPages();
        $this->fpdf->AddPage('P','A4');
        $this->fpdf->SetAutoPageBreak(true, 10);
                                                                              
        $this->fpdf->SetDrawColor(150,150,150);   // Set Line and Border color
        
        //Draw rectangle
        $this->fpdf->Cell(15);
        $this->fpdf->SetFillColor(247,247,247);       //Set Fill color
        //$this->fpdf->Rect(10,10,197,251);
        $this->fpdf->Rect(10,10,45,50,'D',true);      //Right box
        $this->fpdf->Rect(55,10,100,50,'D',true);              //Mid box
        $this->fpdf->Rect(155,10,45,50,'D',true);     //Left box
        
        //Header
        $this->fpdf->Image(base_url().'system/cms/themes/pyrocms/img/logo_small.png',14,30,40);
        $this->fpdf->SetY(15);
        $this->fpdf->SetX(58);
        $this->fpdf->SetFont('Helvetica','',10);
        $this->fpdf->SetTextColor(0,0,0);
        $this->fpdf->Cell(53,7,'FHSIS REPORT for the MONTH:',0,0,'L');
        $this->fpdf->Cell(20,7,$monthname,'ltrB',0,'L');
        $this->fpdf->Cell(10,7,'Year:',0,0,'L');
        $this->fpdf->Cell(10,7,$year,'ltrB',1,'L');
        $this->fpdf->SetX(58);
        $this->fpdf->Cell(32,7,'Name of Barangay:',0,0,'L');
        $this->fpdf->Cell(61,7,$barangay,'ltrB',1,'L');
        $this->fpdf->SetX(58);
        $this->fpdf->Cell(31,7,'Municipality/City of:',0,0,'L');
        $this->fpdf->Cell(62,7,$default_city,'ltrB',1,'L');
        $this->fpdf->SetX(58);
        $this->fpdf->Cell(16,7,'Province:',0,0,'L');
        $this->fpdf->Cell(77,7,$default_province,'ltrB',1,'L');
        $this->fpdf->SetX(58);
        $this->fpdf->Cell(53,7,'Projected Population of the Year:',0,0,'L');
        $this->fpdf->Cell(40,7,'','ltrB',1,'L');
        $this->fpdf->SetFont('Helvetica','I',8);
        $this->fpdf->Cell(0,7,'For submission to RHU',0,1,'C');
        
        $this->fpdf->SetFillColor(247,247,247);
        
        $this->fpdf->SetY(60);
        $this->fpdf->SetFont('Helvetica','BI',12);
        $this->fpdf->Cell(115,10,'MATERNAL CARE',1,0,'C',true);
        $this->fpdf->Cell(75,10,'No.','1',1,'C',true);
        
        $this->fpdf->SetFont('Helvetica','',10);
        $this->fpdf->Cell(115,9,'Pregnant women with 4 or more Prenatal visits',1,0,'L');
        $this->fpdf->Cell(75,9,$maternal['total1'],'1',1,'C');
        $this->fpdf->Cell(115,9,'Pregnant women given 2 doses of Tetanus Toxoid',1,0,'L');
        $this->fpdf->Cell(75,9,$maternal['total2'],'1',1,'C');
        $this->fpdf->Cell(115,9,'Pregnant women given TT2 plus',1,0,'L');
        $this->fpdf->Cell(75,9,$maternal['total3'],'1',1,'C');
        $this->fpdf->Cell(115,9,'Preg. women complete iron w/folic acid supplementation',1,0,'L');
        $this->fpdf->Cell(75,9,$maternal['total4'],'1',1,'C');
        $this->fpdf->Cell(115,9,'Preg. women given Vitamin A supplementation',1,0,'L');
        $this->fpdf->Cell(75,9,$maternal['total5'],'1',1,'C');
        $this->fpdf->Cell(115,9,'Postpartum women with at least 2 Postpartum visits',1,0,'L');
        $this->fpdf->Cell(75,9,$maternal['total6'],'1',1,'C');
        $this->fpdf->Cell(115,9,'Postpartum women given Iron supplementation',1,0,'L');
        $this->fpdf->Cell(75,9,$maternal['total7'],'1',1,'C');
        $this->fpdf->Cell(115,9,'Postpartum women given Vitamin A supplementation',1,0,'L');
        $this->fpdf->Cell(75,9,$maternal['total8'],'1',1,'C');
        $this->fpdf->Cell(115,9,'PP women initiated breastfeeding w/in 1 hr. after delivery',1,0,'L');
        $this->fpdf->Cell(75,9,$maternal['total9'],'1',1,'C');
        
        $this->fpdf->SetFont('Helvetica','BI',10);
        $this->fpdf->Cell(65,20,'FAMILY PLANNING',1,0,'C',true);
        $this->fpdf->SetFont('Helvetica','B',9);
        $this->fpdf->Cell(25,10,'Current Users',1,0,'C',true);
        $this->fpdf->Cell(50,10,'Acceptors',1,0,'C',true);
        $this->fpdf->Cell(25,20,'Dropout',1,0,'C',true);
        $this->fpdf->Cell(25,10,'Current Users',1,1,'C',true);
        
        $this->fpdf->SetX(75);
        $this->fpdf->Cell(25,10,'(Begin Mo.).','1',0,'C',true);
        $this->fpdf->Cell(25,10,'New','1',0,'C',true);
        $this->fpdf->Cell(25,10,'Other','1',0,'C',true);
        
        $this->fpdf->SetX(175);
        $this->fpdf->Cell(25,10,'(End Mo.)','1',1,'C',true);
        
        $this->fpdf->SetFont('Helvetica','',9);
        $this->fpdf->Cell(65,8,'a. Female Sterilization/BTl',1,0,'L');
        $this->fpdf->Cell(25,8,$family_planning['fstr_cu'],1,0,'R');
        $this->fpdf->Cell(25,8,$family_planning['fstr_na'],1,0,'R');
        $this->fpdf->Cell(25,8,$family_planning['fstr_others'],1,0,'R');
        $this->fpdf->Cell(25,8,$family_planning['fstr_others'],1,0,'R');
        $this->fpdf->Cell(25,8,$family_planning['fstr_dropout'],1,1,'R');
        
        $this->fpdf->Cell(65,8,'b. Male Sterilization/Vasectomy',1,0,'L');
        $this->fpdf->Cell(25,8,$family_planning['mstr_cu'],1,0,'R');
        $this->fpdf->Cell(25,8,$family_planning['mstr_na'],1,0,'R');
        $this->fpdf->Cell(25,8,$family_planning['mstr_others'],1,0,'R');
        $this->fpdf->Cell(25,8,$family_planning['mstr_others'],1,0,'R');
        $this->fpdf->Cell(25,8,$family_planning['mstr_dropout'],1,1,'R');
        
        $this->fpdf->Cell(65,8,'c. Pills',1,0,'L');
        $this->fpdf->Cell(25,8,$family_planning['pills_cu'],1,0,'R');
        $this->fpdf->Cell(25,8,$family_planning['pills_na'],1,0,'R');
        $this->fpdf->Cell(25,8,$family_planning['pills_na'],1,0,'R');
        $this->fpdf->Cell(25,8,$family_planning['pills_others'],1,0,'R');
        $this->fpdf->Cell(25,8,$family_planning['pills_dropout'],1,1,'R');
        
        $this->fpdf->Cell(65,8,'d. IUD',1,0,'L');
        $this->fpdf->Cell(25,8,$family_planning['iud_cu'],1,0,'R');
        $this->fpdf->Cell(25,8,$family_planning['iud_na'],1,0,'R');
        $this->fpdf->Cell(25,8,$family_planning['iud_na'],1,0,'R');
        $this->fpdf->Cell(25,8,$family_planning['iud_others'],1,0,'R');
        $this->fpdf->Cell(25,8,$family_planning['iud_dropout'],1,1,'R');
        
        $this->fpdf->Cell(65,8,'e. Injectables(DMPA)',1,0,'L');
        $this->fpdf->Cell(25,8,$family_planning['dmpa_cu'],1,0,'R');
        $this->fpdf->Cell(25,8,$family_planning['dmpa_na'],1,0,'R');
        $this->fpdf->Cell(25,8,$family_planning['dmpa_na'],1,0,'R');
        $this->fpdf->Cell(25,8,$family_planning['dmpa_others'],1,0,'R');
        $this->fpdf->Cell(25,8,$family_planning['dmpa_dropout'],1,1,'R');
        
        $this->fpdf->Cell(65,8,'f. NFP-CM',1,0,'L');
        $this->fpdf->Cell(25,8,$family_planning['nfpcm_cu'],1,0,'R');
        $this->fpdf->Cell(25,8,$family_planning['nfpcm_na'],1,0,'R');
        $this->fpdf->Cell(25,8,$family_planning['nfpcm_na'],1,0,'R');
        $this->fpdf->Cell(25,8,$family_planning['nfpcm_others'],1,0,'R');
        $this->fpdf->Cell(25,8,$family_planning['nfpcm_dropout'],1,1,'R');
        
        $this->fpdf->Cell(65,8,'g. NFP-BBT',1,0,'L');
        $this->fpdf->Cell(25,8,$family_planning['nfpbbt_cu'],1,0,'R');
        $this->fpdf->Cell(25,8,$family_planning['nfpbbt_na'],1,0,'R');
        $this->fpdf->Cell(25,8,$family_planning['nfpbbt_na'],1,0,'R');
        $this->fpdf->Cell(25,8,$family_planning['nfpbbt_others'],1,0,'R');
        $this->fpdf->Cell(25,8,$family_planning['nfpbbt_dropout'],1,1,'R');
        
        $this->fpdf->Cell(65,8,'h. NFP-STM',1,0,'L');
        $this->fpdf->Cell(25,8,$family_planning['nfpstm_cu'],1,0,'R');
        $this->fpdf->Cell(25,8,$family_planning['nfpstm_na'],1,0,'R');
        $this->fpdf->Cell(25,8,$family_planning['nfpstm_na'],1,0,'R');
        $this->fpdf->Cell(25,8,$family_planning['nfpstm_others'],1,0,'R');
        $this->fpdf->Cell(25,8,$family_planning['nfpstm_dropout'],1,1,'R');
        
        $this->fpdf->Cell(65,8,'i. NFPD-Standard Days Method',1,0,'L');
        $this->fpdf->Cell(25,8,$family_planning['nfdsdm_cu'],1,0,'R');
        $this->fpdf->Cell(25,8,$family_planning['nfdsdm_na'],1,0,'R');
        $this->fpdf->Cell(25,8,$family_planning['nfdsdm_na'],1,0,'R');
        $this->fpdf->Cell(25,8,$family_planning['nfdsdm_others'],1,0,'R');
        $this->fpdf->Cell(25,8,$family_planning['nfdsdm_dropout'],1,1,'R');
        
        $this->fpdf->Cell(65,8,'j. NFP-LAM',1,0,'L');
        $this->fpdf->Cell(25,8,$family_planning['nfplam_cu'],1,0,'R');
        $this->fpdf->Cell(25,8,$family_planning['nfplam_na'],1,0,'R');
        $this->fpdf->Cell(25,8,$family_planning['nfplam_na'],1,0,'R');
        $this->fpdf->Cell(25,8,$family_planning['nfplam_others'],1,0,'R');
        $this->fpdf->Cell(25,8,$family_planning['nfplam_dropout'],1,1,'R');
        
        $this->fpdf->Cell(65,8,'k. Condom',1,0,'L');
        $this->fpdf->Cell(25,8,$family_planning['condom_cu'],1,0,'R');
        $this->fpdf->Cell(25,8,$family_planning['condom_na'],1,0,'R');
        $this->fpdf->Cell(25,8,$family_planning['condom_na'],1,0,'R');
        $this->fpdf->Cell(25,8,$family_planning['condom_others'],1,0,'R');
        $this->fpdf->Cell(25,8,$family_planning['condom_dropout'],1,1,'R');
            
        $this->m1_header_icon();
        $this->footer();
        
        $this->fpdf->AddPage('P','A4');
        $this->fpdf->SetAutoPageBreak(true, 10);
        
        $this->fpdf->SetDrawColor(150,150,150);   // Set Line and Border color
        
        //Draw rectangle
        $this->fpdf->Cell(15);
        $this->fpdf->SetFillColor(247,247,247);       //Set Fill color
        $this->fpdf->Rect(10,10,190,252);             //Main box
        
        $this->fpdf->SetFillColor(247,247,247);
        
        //============================= Top Left Child Care =================================//
        
        $this->fpdf->SetTextColor(0,0,0);
        
        $this->fpdf->SetY(10);
        $this->fpdf->SetFont('Helvetica','BI',10);
        $this->fpdf->Cell(59,6,'CHILD CARE',1,0,'C',true);
        $this->fpdf->Cell(18,6,'Male',1,0,'C',true);
        $this->fpdf->Cell(18,6,'Female',1,1,'C',true);
        
        $this->fpdf->SetFont('Helvetica','',7.5);           //set new font size
        
        $this->fpdf->Cell(59,3,'Infant Given','LTRb',0,'L');
        
        $this->fpdf->SetFont('Helvetica','',7.5); 
        $this->fpdf->Cell(18,6,$childcare['bcgM_total'],1,0,'R');
        $this->fpdf->Cell(18,6,$childcare['bcgF_total'],1,1,'R');  
        
        $this->fpdf->SetY(19);
        $this->fpdf->Cell(59,3,'- BCG','LtRB',1,'L');
        
        $this->fpdf->Cell(59,6,'- Hepa B at Birth',1,0,'L');
        $this->fpdf->Cell(18,6,$childcare['hepa_b_at_birthM_total'],1,0,'R');
        $this->fpdf->Cell(18,6,$childcare['hepa_b_at_birthF_total'],1,1,'R');
        
        $this->fpdf->Cell(59,6,'- Pentavalent 1',1,0,'L');
        $this->fpdf->Cell(18,6,$childcare['pentavalent_1M_total'],1,0,'R');
        $this->fpdf->Cell(18,6,$childcare['pentavalent_1F_total'],1,1,'R');
        
        $this->fpdf->Cell(59,6,'- Pentavalent 2',1,0,'L');
        $this->fpdf->Cell(18,6,$childcare['pentavalent_2M_total'],1,0,'R');
        $this->fpdf->Cell(18,6,$childcare['pentavalent_2F_total'],1,1,'R');
        
        $this->fpdf->Cell(59,6,'- Pentavalent 3',1,0,'L');
        $this->fpdf->Cell(18,6,$childcare['pentavalent_3M_total'],1,0,'R');
        $this->fpdf->Cell(18,6,$childcare['pentavalent_3F_total'],1,1,'R');
        
        $this->fpdf->Cell(59,6,'- DPT1',1,0,'L');
        $this->fpdf->Cell(18,6,$childcare['dpt1M_total'],1,0,'R');
        $this->fpdf->Cell(18,6,$childcare['dpt1F_total'],1,1,'R');
        
        $this->fpdf->Cell(59,6,'- DPT2',1,0,'L');
        $this->fpdf->Cell(18,6,$childcare['dpt2M_total'],1,0,'R');
        $this->fpdf->Cell(18,6,$childcare['dpt2F_total'],1,1,'R');
        
        $this->fpdf->Cell(59,6,'- DPT3',1,0,'L');
        $this->fpdf->Cell(18,6,$childcare['dpt3M_total'],1,0,'R');
        $this->fpdf->Cell(18,6,$childcare['dpt3F_total'],1,1,'R');
        
        $this->fpdf->Cell(59,6,'- OPV1',1,0,'L');
        $this->fpdf->Cell(18,6,$childcare['opv1M_total'],1,0,'R');
        $this->fpdf->Cell(18,6,$childcare['opv1F_total'],1,1,'R');
        
        $this->fpdf->Cell(59,6,'- OPV2',1,0,'L');
        $this->fpdf->Cell(18,6,$childcare['opv2M_total'],1,0,'R');
        $this->fpdf->Cell(18,6,$childcare['opv2F_total'],1,1,'R');
        
        $this->fpdf->Cell(59,6,'- OPV3',1,0,'L');
        $this->fpdf->Cell(18,6,$childcare['opv3M_total'],1,0,'R');
        $this->fpdf->Cell(18,6,$childcare['opv3F_total'],1,1,'R');
        
        $this->fpdf->Cell(59,6,'- Rotarix 1',1,0,'L');
        $this->fpdf->Cell(18,6,$childcare['rotarix1M_total'],1,0,'R');
        $this->fpdf->Cell(18,6,$childcare['rotarix1F_total'],1,1,'R');
        
        $this->fpdf->Cell(59,6,'- Rotarix 2',1,0,'L');
        $this->fpdf->Cell(18,6,$childcare['rotarix2M_total'],1,0,'R');
        $this->fpdf->Cell(18,6,$childcare['rotarix2F_total'],1,1,'R');
        
        $this->fpdf->Cell(59,6,'- Hepa B1 w/in 24 hrs. after birth',1,0,'L');
        $this->fpdf->Cell(18,6,$childcare['hepa_b1_with_inM_total'],1,0,'R');
        $this->fpdf->Cell(18,6,$childcare['hepa_b1_with_inF_total'],1,1,'R');
        
        $this->fpdf->Cell(59,6,'- Hepa B1 more than 24 hrs. after birth',1,0,'L');
        $this->fpdf->Cell(18,6,$childcare['hepa_b1_more_thanM_total'],1,0,'R');
        $this->fpdf->Cell(18,6,$childcare['hepa_b1_more_thanF_total'],1,1,'R');
        
        $this->fpdf->Cell(59,6,'- Hepatitis B2',1,0,'L');
        $this->fpdf->Cell(18,6,$childcare['hepa_b2M_total'],1,0,'R');
        $this->fpdf->Cell(18,6,$childcare['hepa_b2F_total'],1,1,'R');
        
        $this->fpdf->Cell(59,6,'- Hepatitis B3',1,0,'L');
        $this->fpdf->Cell(18,6,$childcare['hepa_b3M_total'],1,0,'R');
        $this->fpdf->Cell(18,6,$childcare['hepa_b3F_total'],1,1,'R');
        
        $this->fpdf->Cell(59,6,'- anti-Measles',1,0,'L');
        $this->fpdf->Cell(18,6,$childcare['im_anti_measlesM_total'],1,0,'R');
        $this->fpdf->Cell(18,6,$childcare['im_anti_measlesF_total'],1,1,'R');
        
        $this->fpdf->Cell(59,6,'- MMR Vaccine',1,0,'L');
        $this->fpdf->Cell(18,6,$childcare['mmrM_total'],1,0,'R');
        $this->fpdf->Cell(18,6,$childcare['mmrF_total'],1,1,'R');
        
        $this->fpdf->Cell(59,6,'Fully Immunized Child(0-11 mos)',1,0,'L');
        $this->fpdf->Cell(18,6,$childcare['im_fully_under1M_total'],1,0,'R');
        $this->fpdf->Cell(18,6,$childcare['im_fully_under1F_total'],1,1,'R');
        
        $this->fpdf->Cell(59,6,'Completely Immunized Child(12-23 mos)',1,0,'L');
        $this->fpdf->Cell(18,6,$childcare['im_fully_under1M_total'],1,0,'R');
        $this->fpdf->Cell(18,6,$childcare['im_fully_under1F_total'],1,1,'R');
        
        $this->fpdf->Cell(59,6,'Total Livebirths',1,0,'L');
        $this->fpdf->Cell(18,6,$childcare['livebirths_M_total'],1,0,'R');
        $this->fpdf->Cell(18,6,$childcare['livebirths_F_total'],1,1,'R');
        
        $this->fpdf->Cell(59,6,'Child Protected at Birth (CPAB0)',1,0,'L');
        $this->fpdf->Cell(18,6,$childcare['child_protected_M_total'],1,0,'R');
        $this->fpdf->Cell(18,6,$childcare['child_protected_F_total'],1,1,'R');
        
        $this->fpdf->Cell(59,6,'Infant age 6 mos. seen',1,0,'L');
        $this->fpdf->Cell(18,6,$childcare['infant_6mos_seen_M_total'],1,0,'R');
        $this->fpdf->Cell(18,6,$childcare['infant_6mos_seen_F_total'],1,1,'R');
        
        $this->fpdf->Cell(59,6,'Infant exclusively breastfeed until 6th mo.',1,0,'L');
        $this->fpdf->Cell(18,6,$childcare['breastfeed_6th_M_total'],1,0,'R');                      
        $this->fpdf->Cell(18,6,$childcare['breastfeed_6th_F_total'],1,1,'R');
        
        $this->fpdf->Cell(59,6,'Infant 0-11 mos. referred for newborn screening',1,0,'L');
        $this->fpdf->SetFont('Helvetica','',8);
        $this->fpdf->Cell(18,6,$childcare['referred_nb_screeningM_total'],1,0,'R');
        $this->fpdf->Cell(18,6,$childcare['referred_nb_screeningF_total'],1,1,'R');
        
        //============================= Top Right Child Care =================================//   
        
        $this->fpdf->SetTextColor(0,0,0);
        
        $this->fpdf->SetY(10);
        $this->fpdf->SetX(105);
        $this->fpdf->SetFont('Helvetica','BI',10);
        $this->fpdf->Cell(59,6,'CHILD CARE',1,0,'C',true);
        $this->fpdf->Cell(18,6,'Male',1,0,'C',true);
        $this->fpdf->Cell(18,6,'Female',1,1,'C',true);
        
        $this->fpdf->SetFont('Helvetica','',7.5);           //set new font size
        
        $this->fpdf->SetX(105);
        $this->fpdf->Cell(59,6,'Infant 6-11 months old given Vitamin A',1,0,'L');
        $this->fpdf->Cell(18,6,'-',1,0,'R');
        $this->fpdf->Cell(18,6,'-',1,1,'R');  
        
        $this->fpdf->SetX(105);
        $this->fpdf->Cell(59,6,'Children 12-59 months old given Vitamin A',1,0,'L');
        $this->fpdf->Cell(18,6,'-',1,0,'R');
        $this->fpdf->Cell(18,6,'-',1,1,'R');
        
        $this->fpdf->SetX(105);
        $this->fpdf->Cell(59,6,'Children 60-71 months old given Vitamin A',1,0,'L');
        $this->fpdf->Cell(18,6,'-',1,0,'R');
        $this->fpdf->Cell(18,6,'-',1,1,'R');
        
        $this->fpdf->SetX(105);
        $this->fpdf->Cell(59,6,'Sick Children 6-11 months seen',1,0,'L');
        $this->fpdf->Cell(18,6,$sick_children['child_sick_611_M_total'],1,0,'R');
        $this->fpdf->Cell(18,6,$sick_children['child_sick_611_F_total'],1,1,'R');
        
        $this->fpdf->SetX(105);
        $this->fpdf->Cell(59,6,'Sick Children 12-59 months seen',1,0,'L');
        $this->fpdf->Cell(18,6,$sick_children['child_sick_1259_M_total'],1,0,'R');
        $this->fpdf->Cell(18,6,$sick_children['child_sick_1259_F_total'],1,1,'R');
        
        $this->fpdf->SetX(105);
        $this->fpdf->Cell(59,6,'Sick Children 60-71 months seen',1,0,'L');
        $this->fpdf->Cell(18,6,$sick_children['child_sick_6079_M_total'],1,0,'R');
        $this->fpdf->Cell(18,6,$sick_children['child_sick_6079_F_total'],1,1,'R');
        
        $this->fpdf->SetX(105);
        $this->fpdf->Cell(59,6,'Sick Children 6-11 months given Vitamin A',1,0,'L');
        $this->fpdf->Cell(18,6,$sick_children['child_sick_611_vitA_M_total'],1,0,'R');
        $this->fpdf->Cell(18,6,$sick_children['child_sick_611_vitA_F_total'],1,1,'R');
        
        $this->fpdf->SetX(105);
        $this->fpdf->Cell(59,6,'Sick Children 12-59 months given Vitamin A',1,0,'L');
        $this->fpdf->Cell(18,6,$sick_children['child_sick_1259_vitA_M_total'],1,0,'R');
        $this->fpdf->Cell(18,6,$sick_children['child_sick_1259_vitA_F_total'],1,1,'R');
        
        $this->fpdf->SetX(105);
        $this->fpdf->Cell(59,6,'Sick Children 60-71 months given Vitamin A',1,0,'L');
        $this->fpdf->Cell(18,6,$sick_children['child_sick_6079_vitA_M_total'],1,0,'R');
        $this->fpdf->Cell(18,6,$sick_children['child_sick_6079_vitA_F_total'],1,1,'R');
        
        $this->fpdf->SetX(105);
        $this->fpdf->Cell(59,6,'Infant 2-6 mos. with Low Birth Weight seen',1,0,'L');
        $this->fpdf->Cell(18,6,$inf_lbw['infant_26mos_lbw_M_total'],1,0,'R');
        $this->fpdf->Cell(18,6,$inf_lbw['infant_26mos_lbw_F_total'],1,1,'R');
        
        $this->fpdf->SetX(105);
        $this->fpdf->Cell(59,6,'Infant 2-6 mos. with LBW given Iron',1,0,'L');
        $this->fpdf->Cell(18,6,$inf_lbw['infant_26mos_lbw_iron_M_total'],1,0,'R');
        $this->fpdf->Cell(18,6,$inf_lbw['infant_26mos_lbw_iron_F_total'],1,1,'R');
        
        $this->fpdf->SetX(105);
        $this->fpdf->Cell(59,6,'Anemic Children 2-59 months old seen',1,0,'L');
        $this->fpdf->Cell(18,6,$sc_anemic['anemic_259_M_total'],1,0,'R');
        $this->fpdf->Cell(18,6,$sc_anemic['anemic_259_F_total'],1,1,'R');
        
        $this->fpdf->SetX(105);
        $this->fpdf->Cell(59,6,'Anemic Children 2-59 mos old given Iron',1,0,'L');
        $this->fpdf->Cell(18,6,$sc_anemic['anemic_259_iron_M_total'],1,0,'R');
        $this->fpdf->Cell(18,6,$sc_anemic['anemic_259_iron_F_total'],1,1,'R');
        
        $this->fpdf->SetX(105);
        $this->fpdf->Cell(59,6,'Diarrhea case 0-59 months old seen',1,0,'L');
        $this->fpdf->Cell(18,6,$sc_diarrhea['diarrhea_059_M_total'],1,0,'R');
        $this->fpdf->Cell(18,6,$sc_diarrhea['diarrhea_059_F_total'],1,1,'R');
        
        $this->fpdf->SetX(105);
        $this->fpdf->Cell(59,6,'Diarrhea case 0-59 months old given ORT',1,0,'L');
        $this->fpdf->Cell(18,6,$sc_diarrhea['diarrhea_059_ort_M_total'],1,0,'R');
        $this->fpdf->Cell(18,6,$sc_diarrhea['diarrhea_059_ort_F_total'],1,1,'R');
        
        $this->fpdf->SetX(105);
        $this->fpdf->Cell(59,6,'Diarrhea case 0-59 months old given ORS',1,0,'L');
        $this->fpdf->Cell(18,6,$sc_diarrhea['diarrhea_059_ors_M_total'],1,0,'R');
        $this->fpdf->Cell(18,6,$sc_diarrhea['diarrhea_059_ors_F_total'],1,1,'R');
        
        $this->fpdf->SetX(105);
        $this->fpdf->Cell(59,6,'Diarrhea case 0-59 mos given ORS with Zinc',1,0,'L');
        $this->fpdf->Cell(18,6,$sc_diarrhea['diarrhea_059_ors_zinc_M_total'],1,0,'R');
        $this->fpdf->Cell(18,6,$sc_diarrhea['diarrhea_059_ors_zinc_F_total'],1,1,'R');
        
        $this->fpdf->SetX(105);
        $this->fpdf->Cell(59,6,'Pneumonia cases 0-59 months old',1,0,'L');
        $this->fpdf->Cell(18,6,$sc_pneumonia['pneumonia_059_M_total'],1,0,'R');
        $this->fpdf->Cell(18,6,$sc_pneumonia['pneumonia_059_F_total'],1,1,'R');
        
        $this->fpdf->SetX(105);
        $this->fpdf->Cell(59,6,'Pneumonia cases 0-59 months old given Tx',1,0,'L');
        $this->fpdf->Cell(18,6,$sc_pneumonia['pneumonia_059_tx_M_total'],1,0,'R');
        $this->fpdf->Cell(18,6,$sc_pneumonia['pneumonia_059_tx_F_total'],1,1,'R');
        
        $this->fpdf->SetX(105);
        $this->fpdf->Cell(59,6,'',1,0,'L');
        $this->fpdf->Cell(18,6,'',1,0,'R');
        $this->fpdf->Cell(18,6,'',1,1,'R');
        
        $this->fpdf->SetX(105);
        $this->fpdf->Cell(59,6,'',1,0,'L');
        $this->fpdf->Cell(18,6,'',1,0,'R');
        $this->fpdf->Cell(18,6,'',1,1,'R');
        
        $this->fpdf->SetX(105);
        $this->fpdf->Cell(59,6,'',1,0,'L');
        $this->fpdf->Cell(18,6,'',1,0,'R');
        $this->fpdf->Cell(18,6,'',1,1,'R');
        
        $this->fpdf->SetX(105);
        $this->fpdf->Cell(59,6,'',1,0,'L');
        $this->fpdf->Cell(18,6,'',1,0,'R');
        $this->fpdf->Cell(18,6,'',1,1,'R');
        
        $this->fpdf->SetX(105);
        $this->fpdf->Cell(59,6,'',1,0,'L');
        $this->fpdf->Cell(18,6,'',1,0,'R');
        $this->fpdf->Cell(18,6,'',1,1,'R');
        
        $this->fpdf->SetX(105);
        $this->fpdf->Cell(59,6,'',1,0,'L');
        $this->fpdf->Cell(18,6,'',1,0,'R');
        $this->fpdf->Cell(18,6,'',1,1,'R');
        
        $this->fpdf->SetX(105);
        $this->fpdf->Cell(59,6,'',1,0,'L');
        $this->fpdf->Cell(18,6,'',1,0,'R');
        $this->fpdf->Cell(18,6,'',1,1,'R');
        
        //============================= Mid Left Malaria =================================//
        
        $this->fpdf->SetTextColor(0,0,0);
        
        $this->fpdf->SetFont('Helvetica','BI',10);
        $this->fpdf->Cell(44,6,'MALARIA',1,0,'C',true);
        $this->fpdf->SetFont('Helvetica','',8);
        $this->fpdf->Cell(15,6,'Pregnant',1,0,'C',true);
        $this->fpdf->Cell(18,6,'Male',1,0,'C',true);
        $this->fpdf->Cell(18,6,'Female',1,1,'C',true);
        
        $this->fpdf->SetFont('Helvetica','',8);           //set new font size
        
        $this->fpdf->Cell(44,6,'Malaria Case',1,0,'L'); 
        $this->fpdf->Cell(15,6,'',1,0,'C');
        $this->fpdf->Cell(18,6,'',1,0,'R');
        $this->fpdf->Cell(18,6,'',1,1,'R');  
        
        $this->fpdf->Cell(44,6,'- < 5 yo',1,0,'L');
        $this->fpdf->Cell(15,6,'',1,0,'C');
        $this->fpdf->Cell(18,6,'',1,0,'R');
        $this->fpdf->Cell(18,6,'',1,1,'R');
        
        $this->fpdf->Cell(44,6,'- >=5 yo',1,0,'L');
        $this->fpdf->Cell(15,6,'',1,0,'C');
        $this->fpdf->Cell(18,6,'',1,0,'R');
        $this->fpdf->Cell(18,6,'',1,1,'R');
        
        $this->fpdf->Cell(44,6,'Confirmed malaria Case',1,0,'L');
        $this->fpdf->Cell(15,6,'',1,0,'C');
        $this->fpdf->Cell(18,6,'',1,0,'R');
        $this->fpdf->Cell(18,6,'',1,1,'R');
        
        $this->fpdf->Cell(44,6,'By Species','LTRb',0,'L');
        $this->fpdf->Cell(15,6,'','LTRb',0,'C');
        $this->fpdf->Cell(18,6,'','LTRb',0,'R');
        $this->fpdf->Cell(18,6,'','LTRb',1,'R');
        
        $this->fpdf->Cell(44,6,'- P.falciparum','LtRB',0,'L');
        $this->fpdf->Cell(15,6,'','LtRB',0,'C');
        $this->fpdf->Cell(18,6,'','LtRB',0,'R');
        $this->fpdf->Cell(18,6,'','LtRB',1,'R');
        
        $this->fpdf->Cell(44,6,'- P.vivax',1,0,'L');
        $this->fpdf->Cell(15,6,'',1,0,'C');
        $this->fpdf->Cell(18,6,'',1,0,'R');
        $this->fpdf->Cell(18,6,'',1,1,'R');
        
        $this->fpdf->Cell(44,6,'- P.malariae',1,0,'L');
        $this->fpdf->Cell(15,6,'',1,0,'C');
        $this->fpdf->Cell(18,6,'',1,0,'R');
        $this->fpdf->Cell(18,6,'',1,1,'R');
        
        $this->fpdf->Cell(44,6,'- P.ovale',1,0,'L');
        $this->fpdf->Cell(15,6,'',1,0,'C');
        $this->fpdf->Cell(18,6,'',1,0,'R');
        $this->fpdf->Cell(18,6,'',1,1,'R');
        
        $this->fpdf->Cell(44,6,'By Method','LTRb',0,'L');
        $this->fpdf->Cell(15,6,'','LTRb',0,'C');
        $this->fpdf->Cell(18,6,'','LTRb',0,'R');
        $this->fpdf->Cell(18,6,'','LTRb',1,'R');
        
        $this->fpdf->Cell(44,6,'- Slide','LtRB',0,'L');
        $this->fpdf->Cell(15,6,'','LtRB',0,'C');
        $this->fpdf->Cell(18,6,'','LtRB',0,'R');
        $this->fpdf->Cell(18,6,'','LtRB',1,'R');
        
        $this->fpdf->Cell(44,6,'- RDT',1,0,'L');
        $this->fpdf->Cell(15,6,'',1,0,'C');
        $this->fpdf->Cell(18,6,'',1,0,'R');
        $this->fpdf->Cell(18,6,'',1,1,'R');
        
        $this->fpdf->Cell(44,6,'Malaria deaths',1,0,'L');
        $this->fpdf->Cell(15,6,'',1,0,'C');
        $this->fpdf->Cell(18,6,'',1,0,'R');
        $this->fpdf->Cell(18,6,'',1,1,'R');
        
        $this->fpdf->Cell(44,6,'Households at risk',1,0,'L');
        $this->fpdf->Cell(15,6,'',1,0,'C');
        $this->fpdf->Cell(18,6,'',1,0,'R');
        $this->fpdf->Cell(18,6,'',1,1,'R');
        
        $this->fpdf->Cell(44,6,'Households given ITN',1,0,'L');
        $this->fpdf->Cell(15,6,'',1,0,'C');
        $this->fpdf->Cell(18,6,'',1,0,'R');
        $this->fpdf->Cell(18,6,'',1,1,'R');
        
        //============================= Mid Right Schistomiasis =================================//
        
        $this->fpdf->SetTextColor(0,0,0);
        
        $this->fpdf->SetY(172);
        $this->fpdf->SetX(105);
        $this->fpdf->SetFont('Helvetica','BI',10);
        $this->fpdf->Cell(59,6,'SCHISTOMIASIS',1,0,'C',true); 
        $this->fpdf->Cell(18,6,'Male',1,0,'C',true);
        $this->fpdf->Cell(18,6,'Female',1,1,'C',true);
        
        $this->fpdf->SetFont('Helvetica','',8);           //set new font size
        
        $this->fpdf->SetX(105);
        $this->fpdf->Cell(59,6,'Symptomatic case',1,0,'L');
        $this->fpdf->Cell(18,6,'',1,0,'R');
        $this->fpdf->Cell(18,6,'',1,1,'R');  
        
        $this->fpdf->SetX(105);
        $this->fpdf->Cell(59,6,'Positive case',1,0,'L');
        $this->fpdf->Cell(18,6,'',1,0,'R');
        $this->fpdf->Cell(18,6,'',1,1,'R');
        
        $this->fpdf->SetX(105);
        $this->fpdf->Cell(59,6,'Case examined',1,0,'L');
        $this->fpdf->Cell(18,6,'',1,0,'R');
        $this->fpdf->Cell(18,6,'',1,1,'R');
        
        $this->fpdf->SetX(105);
        $this->fpdf->Cell(59,6,' - Low intensity',1,0,'L');
        $this->fpdf->Cell(18,6,'',1,0,'R');
        $this->fpdf->Cell(18,6,'',1,1,'R');
        
        $this->fpdf->SetX(105);
        $this->fpdf->Cell(59,6,' - Medium intensity',1,0,'L');
        $this->fpdf->Cell(18,6,'',1,0,'R');
        $this->fpdf->Cell(18,6,'',1,1,'R');
        
        $this->fpdf->SetX(105);
        $this->fpdf->Cell(59,6,' - High intensity',1,0,'L');
        $this->fpdf->Cell(18,6,'',1,0,'R');
        $this->fpdf->Cell(18,6,'',1,1,'R');
        
        $this->fpdf->SetX(105);
        $this->fpdf->Cell(59,6,'Case treated',1,0,'L');
        $this->fpdf->Cell(18,6,'',1,0,'R');
        $this->fpdf->Cell(18,6,'',1,1,'R');
        
        $this->fpdf->SetX(105);
        $this->fpdf->Cell(59,6,'Cases referred to hsp. facilities',1,0,'L');
        $this->fpdf->Cell(18,6,'',1,0,'R');
        $this->fpdf->Cell(18,6,'',1,1,'R');
        
        $this->fpdf->SetX(105);
        $this->fpdf->SetFont('Helvetica','BI',10);
        $this->fpdf->Cell(59,6,'FILARIASIS',1,0,'C',true);
        $this->fpdf->Cell(18,6,'Male',1,0,'C',true);
        $this->fpdf->Cell(18,6,'Female',1,1,'C',true);
        
        $this->fpdf->SetFont('Helvetica','',8); 
        
        $this->fpdf->SetX(105);
        $this->fpdf->Cell(59,6,'Cases examined',1,0,'L');
        $this->fpdf->Cell(18,6,'',1,0,'R');
        $this->fpdf->Cell(18,6,'',1,1,'R');
        
        $this->fpdf->SetX(105);
        $this->fpdf->Cell(59,6,'Cases Positive',1,0,'L');
        $this->fpdf->Cell(18,6,'',1,0,'R');
        $this->fpdf->Cell(18,6,'',1,1,'R');
        
        $this->fpdf->SetX(105);
        $this->fpdf->Cell(59,6,'MF in the siles found Positive',1,0,'L');
        $this->fpdf->Cell(18,6,'',1,0,'R');
        $this->fpdf->Cell(18,6,'',1,1,'R');
        
        $this->fpdf->SetX(105);
        $this->fpdf->Cell(59,6,'Persons givn MDA',1,0,'L');
        $this->fpdf->Cell(18,6,'',1,0,'R');
        $this->fpdf->Cell(18,6,'',1,1,'R');
        
        $this->fpdf->SetX(105);
        $this->fpdf->Cell(59,6,'Adenolympahangitis Cases',1,0,'L');
        $this->fpdf->Cell(18,6,'',1,0,'R');
        $this->fpdf->Cell(18,6,'',1,1,'R');
        
        $this->fpdf->SetX(105);
        $this->fpdf->Cell(59,6,'',1,0,'L');
        $this->fpdf->Cell(18,6,'',1,0,'R');
        $this->fpdf->Cell(18,6,'',1,1,'R');
        
        //============================= Bot Left Malaria =================================//
        
        $this->footer();
        
        $this->fpdf->AddPage('P','A4');
        $this->fpdf->SetAutoPageBreak(true, 10);
        
        $this->fpdf->SetDrawColor(150,150,150);   // Set Line and Border color
        
        //Draw rectangle
        $this->fpdf->SetFillColor(247,247,247);       //Set Fill color
        $this->fpdf->Rect(10,10,190,35);             //Main box
        
        $this->fpdf->SetFillColor(247,247,247);
        
        $this->fpdf->SetTextColor(0,0,0);
        
        $this->fpdf->SetFont('Helvetica','BI',10);
        $this->fpdf->Cell(59,5,'TUBERCULOSIS',1,0,'C',true);
        $this->fpdf->Cell(18,5,'Male',1,0,'C',true);
        $this->fpdf->Cell(18,5,'Female',1,1,'C',true);
        
        $this->fpdf->SetFont('Helvetica','',8);           //set new font size
        
        $this->fpdf->Cell(59,5,'TB symptomanitics who underwent DSSM',1,0,'L'); 
        $this->fpdf->Cell(18,5,'',1,0,'R');
        $this->fpdf->Cell(18,5,'',1,1,'R');  
        
        $this->fpdf->Cell(59,5,'Smear positive discovered',1,0,'L');
        $this->fpdf->Cell(18,5,'',1,0,'R');
        $this->fpdf->Cell(18,5,'',1,1,'R');
        
        $this->fpdf->Cell(59,5,'New Smear(+)cases initiated treatment',1,0,'L');
        $this->fpdf->Cell(18,5,'',1,0,'R');
        $this->fpdf->Cell(18,5,'',1,1,'R');
        
        $this->fpdf->Cell(59,5,'New Smear(+)case cured',1,0,'L');
        $this->fpdf->Cell(18,5,'',1,0,'R');
        $this->fpdf->Cell(18,5,'',1,1,'R');
        
        $this->fpdf->Cell(59,5,'Smear(+)retreatment case initiated Tx',1,0,'L');
        $this->fpdf->Cell(18,5,'',1,0,'R');
        $this->fpdf->Cell(18,5,'',1,1,'R');
        
        $this->fpdf->Cell(59,5,'Smear(+)retreatment case got cured',1,0,'L');
        $this->fpdf->Cell(18,5,'',1,0,'R');
        $this->fpdf->Cell(18,5,'',1,1,'R');
        
        //============================= Bot Right Schistomiasis =================================//
        
        $this->fpdf->SetTextColor(0,0,0);
        
        $this->fpdf->SetY(10);
        $this->fpdf->SetX(105);
        $this->fpdf->SetFont('Helvetica','BI',10);
        $this->fpdf->Cell(59,5,'LEPROSY',1,0,'C',true); 
        $this->fpdf->Cell(18,5,'Male',1,0,'C',true);
        $this->fpdf->Cell(18,5,'Female',1,1,'C',true);
        
        $this->fpdf->SetFont('Helvetica','',8);           //set new font size
        
        $this->fpdf->SetX(105);
        $this->fpdf->Cell(59,5,'Leprosy cases',1,0,'L');
        $this->fpdf->Cell(18,5,'',1,0,'R');
        $this->fpdf->Cell(18,5,'',1,1,'R');  
        
        $this->fpdf->SetX(105);
        $this->fpdf->Cell(59,5,'Leprosy cases below 15 yo',1,0,'L');
        $this->fpdf->Cell(18,5,'',1,0,'R');
        $this->fpdf->Cell(18,5,'',1,1,'R');
        
        $this->fpdf->SetX(105);
        $this->fpdf->Cell(59,5,'Newly detected Leprosy cases',1,0,'L');
        $this->fpdf->Cell(18,5,'',1,0,'R');
        $this->fpdf->Cell(18,5,'',1,1,'R');
        
        $this->fpdf->SetX(105);
        $this->fpdf->Cell(59,5,'Newly detected cases w/ grade 2 disability',1,0,'L');
        $this->fpdf->Cell(18,5,'',1,0,'R');
        $this->fpdf->Cell(18,5,'',1,1,'R');
        
        $this->fpdf->SetX(105);
        $this->fpdf->Cell(59,5,'Case cured',1,0,'L');
        $this->fpdf->Cell(18,5,'',1,0,'R');
        $this->fpdf->Cell(18,5,'',1,1,'R');
        
        $this->fpdf->SetX(105);
        $this->fpdf->Cell(59,5,'',1,0,'L');
        $this->fpdf->Cell(18,5,'',1,0,'R');
        $this->fpdf->Cell(18,5,'',1,1,'R');

        $this->footer();
        
        if($action=="generate_bhs_m1_dl")  
            {$this->fpdf->Output('Brgy_M1_'.$brgy_filename.'_'.$monthname.'_'.$year.'.pdf','D');} 
        else
            {$this->fpdf->Output('Brgy_M1_'.$brgy_filename.'_'.$monthname.'_'.$year.'.pdf','I');}            

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
    
    function m1_header_icon()
    {
        $this->fpdf->SetTextColor(0,0,0);
        $this->fpdf->SetY(10);
        $this->fpdf->SetX(155);
        $this->fpdf->SetFont('Helvetica','',7);
        $this->fpdf->Cell(45,5,'FHSIS Version 2013',0,1,'C');
        $this->fpdf->SetX(157);
        $this->fpdf->SetFont('Helvetica','B',100);
        $this->fpdf->Cell(45,30,'M1',0,1,'C');
        $this->fpdf->SetX(155);
        $this->fpdf->SetFont('Helvetica','B',42);
        $this->fpdf->Cell(45,15,'BRGY',0,1,'C');
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
        $model = $this->load->model('bhs_m1_m');
        echo print_r($model->get_sc_pneumonia_m1('3','2012')); 
    }

} /* end admin class */
?>