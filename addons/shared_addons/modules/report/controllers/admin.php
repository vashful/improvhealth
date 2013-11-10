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

		$this->template->title($this->module_details['name'])->build('admin/items', $this->data);   
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


    public function action()
    {
		// Determine the type of action
		switch ($this->input->post('btnAction'))
		{
            case 'generate_fhis':
            	$this->fhis();
            	break;
            case 'generate_fhis_dl':
            	$this->fhis();
            	break;
            case 'generate_bhs_m1':
            	$this->bhs_m1();
            	break; 
            case 'generate_bhs_m1_dl':
            	$this->bhs_m1();
            	break;  
            case 'generate_rhu_q1':
            	$this->rhu_q1();
            	break; 
            case 'generate_rhu_q1_dl':
            	$this->rhu_q1();
            	break; 
            case 'generate_rhu_a1':
            	$this->rhu_a1();
            	break; 
            case 'generate_rhu_a1_dl':
            	$this->rhu_a1();
            	break; 
            case 'generate_rhu_a2':
            	$this->rhu_a2();
            	break; 
            case 'generate_rhu_a2_dl':
            	$this->rhu_a2();
            	break; 
            case 'generate_rhu_a3':
            	$this->rhu_a3();
            	break; 
            case 'generate_rhu_a3_dl':
            	$this->rhu_a3();
            	break;     
            default:
            	redirect('admin/report');
            	break;
		}    

	}
  
    public function fhis()
    {
    $base_where = $this->session->userdata('base_where');
        
    // Using this data, get the relevant results
    $clients = $this->clients_m
   ->order_by('last_name', 'ASC')
   ->get_many_by($base_where);
    
    $this->fpdf->Open();
    $this->fpdf->AliasNbPages();
    $this->fpdf->AddPage('L','Letter');
    $this->fpdf->SetAutoPageBreak(true, 10);
                        
    $this->fpdf->SetFont('Helvetica','',8);
    
    $this->fpdf->Image(base_url().'system/cms/themes/pyrocms/img/logo_small.png',10,5,40);
    $this->fpdf->SetY(10);
    $this->fpdf->SetFont('Times','B',12);
    $this->fpdf->SetTextColor(0,0,0);
    $this->fpdf->Cell(0,4,strtoupper('Target Client List'),0,0,'C');
    $this->fpdf->SetFont('Times','',8);
    //$this->fpdf->WriteHTML($html);
    
    $this->fpdf->SetY(15);
    $this->fpdf->SetFillColor(208,208,255);
    $fill = true;
    $this->fpdf->SetFont('Times','B',9);
    $this->fpdf->Cell(10,5,'#',1,0,'L',$fill);
    $this->fpdf->Cell(25,5,'Serial No.',1,0,'L',$fill);
    $this->fpdf->Cell(45,5,'Name',1,0,'L',$fill);
    $this->fpdf->Cell(50,5,'Address',1,0,'L',$fill);
    $this->fpdf->Cell(7,5,'Age',1,0,'L',$fill);
    $this->fpdf->Cell(13,5,'Gender',1,0,'L',$fill);
    $this->fpdf->Cell(25,5,'Date of Birth',1,0,'L',$fill);
    $this->fpdf->Cell(25,5,'Date Registered',1,0,'L',$fill);
    $this->fpdf->Cell(25,5,'Relation',1,0,'L',$fill);
    $this->fpdf->Cell(18,5,'Philhealth',1,0,'L',$fill);
    $this->fpdf->Cell(18,5,'PH-Type',1,1,'L',$fill);
    
    $i = 1;
    $x = 1;
    foreach ($clients as $client)
    {                             
            $age = floor((time() - $client->dob)/31556926); 
            if(!$age)
                {$age = '-';}
            if(!$client->address)
                    {$address = $client->residence;} 
            elseif(!$client->residence)
                    {$address = $client->address;} 
            else 
                    {$address = $client->residence.', '.$client->address;}
            $this->fpdf->SetFont('Times','',9);
            $this->fpdf->Cell(10,4,$x,1,0,'L');
            $this->fpdf->Cell(25,4,$client->serial_number,1,0,'L');
            $this->fpdf->Cell(45,4,trim($client->last_name).', '.trim($client->first_name).' '.trim($client->middle_name),1,0,'L');
            $this->fpdf->Cell(50,4,$address,1,0,'L');
            $this->fpdf->Cell(7,4,$age,1,0,'R');
            $this->fpdf->Cell(13,4,ucfirst($client->gender),1,0,'L');
            $this->fpdf->Cell(25,4,date('M j, Y', $client->dob),1,0,'R');
            $this->fpdf->Cell(25,4,date('M j, Y', $client->registration_date),1,0,'R');         
            $this->fpdf->Cell(25,4,ucwords($client->relation),1,0,'L');
            $this->fpdf->Cell(18,4,$client->philhealth,1,0,'L');
            $this->fpdf->Cell(18,4,$client->philhealth_type,1,1,'L');
            
            if($i==45)
            {
                $this->fpdf->SetY(-15);
                $this->fpdf->SetTextColor(128);
                $this->fpdf->SetFont('Times','I',8);
                $this->fpdf->Cell(10,4,'Page '.$this->fpdf->PageNo().'/{nb}',0,0,'C');
                $this->fpdf->SetFont('Times','',8);
                $this->fpdf->Cell(0,4,'Copyright © 2013 IMPROVHEALTH | Provided by: Touch Foundation Inc.',0,0,'C');
                $this->fpdf->AddPage('L','Letter');
                $i = 1;
                $this->fpdf->SetTextColor(0,0,0);
                
                $this->fpdf->SetFont('Times','',8);
    
                //$this->footer();
                
                $this->fpdf->SetY(15);
                $this->fpdf->SetFillColor(208,208,255);
                $fill = true;
                $this->fpdf->SetFont('Times','B',9);
                $this->fpdf->Cell(10,5,'#',1,0,'L',$fill);
                $this->fpdf->Cell(25,5,'Serial No.',1,0,'L',$fill);
                $this->fpdf->Cell(45,5,'Name',1,0,'L',$fill);
                $this->fpdf->Cell(50,5,'Address',1,0,'L',$fill);
                $this->fpdf->Cell(7,5,'Age',1,0,'L',$fill);
                $this->fpdf->Cell(13,5,'Gender',1,0,'L',$fill);
                $this->fpdf->Cell(25,5,'Date of Birth',1,0,'L',$fill);
                $this->fpdf->Cell(25,5,'Date Registered',1,0,'L',$fill);               
                $this->fpdf->Cell(25,5,'Relation',1,0,'L',$fill);
                $this->fpdf->Cell(18,5,'Philhealth',1,0,'L',$fill);
                $this->fpdf->Cell(18,5,'PH-Type',1,1,'L',$fill);
    
            }
            else
            {
                $i=$i+1;
            }       
            $x=$x+1;   
        }
        
        $this->footer();
    
        $this->fpdf->Output();                  

    }
  
    public function bhs_m1()
    {           
        $action = $this->input->post('btnAction');
        
        $month = $this->input->post('m1_month');
        $year = $this->input->post('m1_year'); 
        
        if($year=='') 
            {$year = date('Y');} 
        if($month=='') 
            {$month = date('m');} 
        
        $default_province = $this->settings_m->get_default_province();
        $default_city = $this->settings_m->get_default_city();
        $station_name = $this->settings_m->get_station_name();
        
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
        $maternal = $this->report_m2->get_medicare_montlyreport($month, $year);
        $family_planning = $this->report_m2->get_familyplanning_montlyreport($month, $year);
        $childcare = $this->report_m2->get_childcare_montlyreport($month, $year);
        
        $this->fpdf->Open();
        $this->fpdf->AliasNbPages();
        $this->fpdf->AddPage('P','Letter');
        $this->fpdf->SetAutoPageBreak(true, 10);
        
        $this->fpdf->SetDrawColor(150,150,150);   // Set Line and Border color
        
        //Draw rectangle
        $this->fpdf->Cell(15);
        $this->fpdf->SetFillColor(247,247,247);       //Set Fill color
        //$this->fpdf->Rect(10,10,197,251);
        $this->fpdf->Rect(10,10,50,50,'D',true);      //Right box
        $this->fpdf->Rect(60,10,97,50,'D',true);              //Mid box
        $this->fpdf->Rect(157,10,50,50,'D',true);     //Left box
        
        //Header
        $this->fpdf->Image(base_url().'system/cms/themes/pyrocms/img/logo_small.png',14,30,40);
        $this->fpdf->SetY(15);
        $this->fpdf->SetX(62);
        $this->fpdf->SetFont('Helvetica','',10);
        $this->fpdf->SetTextColor(0,0,0);
        $this->fpdf->Cell(53,7,'FHSIS REPORT for the MONTH:',0,0,'L');
        $this->fpdf->Cell(20,7,$monthname,'ltrB',0,'L');
        $this->fpdf->Cell(10,7,'Year:',0,0,'L');
        $this->fpdf->Cell(10,7,$year,'ltrB',1,'L');
        $this->fpdf->SetX(62);
        $this->fpdf->Cell(23,7,'Name of BHS:',0,0,'L');
        $this->fpdf->Cell(70,7,$station_name,'ltrB',1,'L');
        $this->fpdf->SetX(62);
        $this->fpdf->Cell(32,7,'Municipality/City of:',0,0,'L');
        $this->fpdf->Cell(61,7,$default_city,'ltrB',1,'L');
        $this->fpdf->SetX(62);
        $this->fpdf->Cell(15,7,'Province:',0,0,'L');
        $this->fpdf->Cell(78,7,$default_province,'ltrB',1,'L');
        $this->fpdf->SetX(62);
        $this->fpdf->Cell(49,7,'Projected Population of the Year:',0,0,'L');
        $this->fpdf->Cell(44,7,'','ltrB',1,'L');
        $this->fpdf->SetFont('Helvetica','I',8);
        $this->fpdf->Cell(0,7,'For submission to RHU',0,1,'C');
        
        $this->fpdf->SetFillColor(247,247,247);
        
        $this->fpdf->SetY(60);
        $this->fpdf->SetFont('Helvetica','BI',12);
        $this->fpdf->Cell(120,10,'MATERNAL CARE',1,0,'C',true);
        $this->fpdf->Cell(77,10,'No.','1',1,'C',true);
        
        $this->fpdf->SetFont('Helvetica','',10);
        $this->fpdf->Cell(120,9,'Pregnant women with 4 or more Prenatal visits',1,0,'L');
        $this->fpdf->Cell(77,9,$maternal['total2'],'1',1,'C');
        $this->fpdf->Cell(120,9,'Pregnant women given 2 doses of Tetanus Toxoid',1,0,'L');
        $this->fpdf->Cell(77,9,$maternal['total2'],'1',1,'C');
        $this->fpdf->Cell(120,9,'Pregnant women given TT2 plus',1,0,'L');
        $this->fpdf->Cell(77,9,$maternal['total3'],'1',1,'C');
        $this->fpdf->Cell(120,9,'Preg. women complete iron w/folic acid supplementation',1,0,'L');
        $this->fpdf->Cell(77,9,$maternal['total4'],'1',1,'C');
        $this->fpdf->Cell(120,9,'Preg. women given Vitamin A supplementation',1,0,'L');
        $this->fpdf->Cell(77,9,$maternal['total5'],'1',1,'C');
        $this->fpdf->Cell(120,9,'Postpartum women with at least 2 Postpartum visits',1,0,'L');
        $this->fpdf->Cell(77,9,$maternal['total6'],'1',1,'C');
        $this->fpdf->Cell(120,9,'Postpartum women given Iron supplementation',1,0,'L');
        $this->fpdf->Cell(77,9,$maternal['total7'],'1',1,'C');
        $this->fpdf->Cell(120,9,'Postpartum women given Vitamin A supplementation',1,0,'L');
        $this->fpdf->Cell(77,9,$maternal['total8'],'1',1,'C');
        $this->fpdf->Cell(120,9,'PP women initiated breastfeeding w/in 1 hr. after delivery',1,0,'L');
        $this->fpdf->Cell(77,9,$maternal['total9'],'1',1,'C');
        
        $this->fpdf->SetFont('Helvetica','BI',10);
        $this->fpdf->Cell(72,20,'FAMILY PLANNING',1,0,'C',true);
        $this->fpdf->SetFont('Helvetica','B',9);
        $this->fpdf->Cell(25,10,'Current Users',1,0,'C',true);
        $this->fpdf->Cell(50,10,'Acceptors',1,0,'C',true);
        $this->fpdf->Cell(25,20,'Dropout',1,0,'C',true);
        $this->fpdf->Cell(25,10,'Current Users',1,1,'C',true);
        
        $this->fpdf->SetX(82);
        $this->fpdf->Cell(25,10,'(Begin Mo.).','1',0,'C',true);
        $this->fpdf->Cell(25,10,'New','1',0,'C',true);
        $this->fpdf->Cell(25,10,'Other','1',0,'C',true);
        
        $this->fpdf->SetX(182);
        $this->fpdf->Cell(25,10,'(End Mo.)','1',1,'C',true);
        
        $this->fpdf->SetFont('Helvetica','',9);
        $this->fpdf->Cell(72,8,'a. Female Sterilization/BTl',1,0,'L');
        $this->fpdf->Cell(25,8,$family_planning['fstr_cu'],1,0,'R');
        $this->fpdf->Cell(25,8,$family_planning['fstr_na'],1,0,'R');
        $this->fpdf->Cell(25,8,$family_planning['fstr_others'],1,0,'R');
        $this->fpdf->Cell(25,8,$family_planning['fstr_others'],1,0,'R');
        $this->fpdf->Cell(25,8,$family_planning['fstr_dropout'],1,1,'R');
        
        $this->fpdf->Cell(72,8,'b. Male Sterilization/Vasectomy',1,0,'L');
        $this->fpdf->Cell(25,8,$family_planning['mstr_cu'],1,0,'R');
        $this->fpdf->Cell(25,8,$family_planning['mstr_na'],1,0,'R');
        $this->fpdf->Cell(25,8,$family_planning['mstr_others'],1,0,'R');
        $this->fpdf->Cell(25,8,$family_planning['mstr_others'],1,0,'R');
        $this->fpdf->Cell(25,8,$family_planning['mstr_dropout'],1,1,'R');
        
        $this->fpdf->Cell(72,8,'c. Pills',1,0,'L');
        $this->fpdf->Cell(25,8,$family_planning['pills_cu'],1,0,'R');
        $this->fpdf->Cell(25,8,$family_planning['pills_na'],1,0,'R');
        $this->fpdf->Cell(25,8,$family_planning['pills_na'],1,0,'R');
        $this->fpdf->Cell(25,8,$family_planning['pills_others'],1,0,'R');
        $this->fpdf->Cell(25,8,$family_planning['pills_dropout'],1,1,'R');
        
        $this->fpdf->Cell(72,8,'d. IUD',1,0,'L');
        $this->fpdf->Cell(25,8,$family_planning['iud_cu'],1,0,'R');
        $this->fpdf->Cell(25,8,$family_planning['iud_na'],1,0,'R');
        $this->fpdf->Cell(25,8,$family_planning['iud_na'],1,0,'R');
        $this->fpdf->Cell(25,8,$family_planning['iud_others'],1,0,'R');
        $this->fpdf->Cell(25,8,$family_planning['iud_dropout'],1,1,'R');
        
        $this->fpdf->Cell(72,8,'e. Injectables(DMPA)',1,0,'L');
        $this->fpdf->Cell(25,8,$family_planning['dmpa_cu'],1,0,'R');
        $this->fpdf->Cell(25,8,$family_planning['dmpa_na'],1,0,'R');
        $this->fpdf->Cell(25,8,$family_planning['dmpa_na'],1,0,'R');
        $this->fpdf->Cell(25,8,$family_planning['dmpa_others'],1,0,'R');
        $this->fpdf->Cell(25,8,$family_planning['dmpa_dropout'],1,1,'R');
        
        $this->fpdf->Cell(72,8,'f. NFP-CM',1,0,'L');
        $this->fpdf->Cell(25,8,$family_planning['nfpcm_cu'],1,0,'R');
        $this->fpdf->Cell(25,8,$family_planning['nfpcm_na'],1,0,'R');
        $this->fpdf->Cell(25,8,$family_planning['nfpcm_na'],1,0,'R');
        $this->fpdf->Cell(25,8,$family_planning['nfpcm_others'],1,0,'R');
        $this->fpdf->Cell(25,8,$family_planning['nfpcm_dropout'],1,1,'R');
        
        $this->fpdf->Cell(72,8,'g. NFP-BBT',1,0,'L');
        $this->fpdf->Cell(25,8,$family_planning['nfpbbt_cu'],1,0,'R');
        $this->fpdf->Cell(25,8,$family_planning['nfpbbt_na'],1,0,'R');
        $this->fpdf->Cell(25,8,$family_planning['nfpbbt_na'],1,0,'R');
        $this->fpdf->Cell(25,8,$family_planning['nfpbbt_others'],1,0,'R');
        $this->fpdf->Cell(25,8,$family_planning['nfpbbt_dropout'],1,1,'R');
        
        $this->fpdf->Cell(72,8,'h. NFP-STM',1,0,'L');
        $this->fpdf->Cell(25,8,$family_planning['nfpstm_cu'],1,0,'R');
        $this->fpdf->Cell(25,8,$family_planning['nfpstm_na'],1,0,'R');
        $this->fpdf->Cell(25,8,$family_planning['nfpstm_na'],1,0,'R');
        $this->fpdf->Cell(25,8,$family_planning['nfpstm_others'],1,0,'R');
        $this->fpdf->Cell(25,8,$family_planning['nfpstm_dropout'],1,1,'R');
        
        $this->fpdf->Cell(72,8,'i. NFPD-Standard Days Method',1,0,'L');
        $this->fpdf->Cell(25,8,$family_planning['nfdsdm_cu'],1,0,'R');
        $this->fpdf->Cell(25,8,$family_planning['nfdsdm_na'],1,0,'R');
        $this->fpdf->Cell(25,8,$family_planning['nfdsdm_na'],1,0,'R');
        $this->fpdf->Cell(25,8,$family_planning['nfdsdm_others'],1,0,'R');
        $this->fpdf->Cell(25,8,$family_planning['nfdsdm_dropout'],1,1,'R');
        
        $this->fpdf->Cell(72,8,'j. NFP-LAM',1,0,'L');
        $this->fpdf->Cell(25,8,$family_planning['nfplam_cu'],1,0,'R');
        $this->fpdf->Cell(25,8,$family_planning['nfplam_na'],1,0,'R');
        $this->fpdf->Cell(25,8,$family_planning['nfplam_na'],1,0,'R');
        $this->fpdf->Cell(25,8,$family_planning['nfplam_others'],1,0,'R');
        $this->fpdf->Cell(25,8,$family_planning['nfplam_dropout'],1,1,'R');
        
        $this->fpdf->Cell(72,8,'k. Condom',1,0,'L');
        $this->fpdf->Cell(25,8,$family_planning['condom_cu'],1,0,'R');
        $this->fpdf->Cell(25,8,$family_planning['condom_na'],1,0,'R');
        $this->fpdf->Cell(25,8,$family_planning['condom_na'],1,0,'R');
        $this->fpdf->Cell(25,8,$family_planning['condom_others'],1,0,'R');
        $this->fpdf->Cell(25,8,$family_planning['condom_dropout'],1,1,'R');
            
        $this->m1_header_icon();
        $this->footer();
        
        $this->fpdf->AddPage('P','Letter');
        $this->fpdf->SetAutoPageBreak(true, 10);
        
        $this->fpdf->SetDrawColor(150,150,150);   // Set Line and Border color
        
        //Draw rectangle
        $this->fpdf->Cell(15);
        $this->fpdf->SetFillColor(247,247,247);       //Set Fill color
        $this->fpdf->Rect(10,10,197,251);             //Main box
        
        $this->fpdf->SetFillColor(247,247,247);
        
        //============================= Top Left Child Care =================================//
        
        $this->fpdf->SetTextColor(0,0,0);
        
        $this->fpdf->SetY(10);
        $this->fpdf->SetFont('Helvetica','BI',10);
        $this->fpdf->Cell(61,6,'CHILD CARE',1,0,'C',true);
        $this->fpdf->Cell(19,6,'Male',1,0,'C',true);
        $this->fpdf->Cell(19,6,'Female',1,1,'C',true);
        
        $this->fpdf->SetFont('Helvetica','',8);           //set new font size
        
        $this->fpdf->Cell(61,3,'Infant Given','LTRb',0,'L');
        
        $this->fpdf->SetFont('Helvetica','',8); 
        $this->fpdf->Cell(19,6,$childcare['bcgM_total'],1,0,'R');
        $this->fpdf->Cell(19,6,$childcare['bcgF_total'],1,1,'R');  
        
        $this->fpdf->SetY(19);
        $this->fpdf->Cell(61,3,'- BCG','LtRB',1,'L');
        
        $this->fpdf->Cell(61,6,'- DPT1',1,0,'L');
        $this->fpdf->Cell(19,6,$childcare['dpt1M_total'],1,0,'R');
        $this->fpdf->Cell(19,6,$childcare['dpt1F_total'],1,1,'R');
        
        $this->fpdf->Cell(61,6,'- DPT2',1,0,'L');
        $this->fpdf->Cell(19,6,$childcare['dpt2M_total'],1,0,'R');
        $this->fpdf->Cell(19,6,$childcare['dpt2F_total'],1,1,'R');
        
        $this->fpdf->Cell(61,6,'- DPT3',1,0,'L');
        $this->fpdf->Cell(19,6,$childcare['dpt3M_total'],1,0,'R');
        $this->fpdf->Cell(19,6,$childcare['dpt3F_total'],1,1,'R');
        
        $this->fpdf->Cell(61,6,'- OPV1',1,0,'L');
        $this->fpdf->Cell(19,6,$childcare['opv1M_total'],1,0,'R');
        $this->fpdf->Cell(19,6,$childcare['opv1F_total'],1,1,'R');
        
        $this->fpdf->Cell(61,6,'- OPV2',1,0,'L');
        $this->fpdf->Cell(19,6,$childcare['opv2M_total'],1,0,'R');
        $this->fpdf->Cell(19,6,$childcare['opv2F_total'],1,1,'R');
        
        $this->fpdf->Cell(61,6,'- OPV3',1,0,'L');
        $this->fpdf->Cell(19,6,$childcare['opv3M_total'],1,0,'R');
        $this->fpdf->Cell(19,6,$childcare['opv3F_total'],1,1,'R');
        
        $this->fpdf->Cell(61,6,'- Hepa B1 w/in 24 hrs. after birth',1,0,'L');
        $this->fpdf->Cell(19,6,$childcare['hepa_b1_with_inM_total'],1,0,'R');
        $this->fpdf->Cell(19,6,$childcare['hepa_b1_with_inF_total'],1,1,'R');
        
        $this->fpdf->Cell(61,6,'- Hepa B1 more than 24 hrs. after birth',1,0,'L');
        $this->fpdf->Cell(19,6,$childcare['hepa_b1_more_thanM_total'],1,0,'R');
        $this->fpdf->Cell(19,6,$childcare['hepa_b1_more_thanF_total'],1,1,'R');
        
        $this->fpdf->Cell(61,6,'- Hepatitis B2',1,0,'L');
        $this->fpdf->Cell(19,6,$childcare['hepa_b2M_total'],1,0,'R');
        $this->fpdf->Cell(19,6,$childcare['hepa_b2F_total'],1,1,'R');
        
        $this->fpdf->Cell(61,6,'- Hepatitis B3',1,0,'L');
        $this->fpdf->Cell(19,6,$childcare['hepa_b3M_total'],1,0,'R');
        $this->fpdf->Cell(19,6,$childcare['hepa_b3F_total'],1,1,'R');
        
        $this->fpdf->Cell(61,6,'- anti-Measles',1,0,'L');
        $this->fpdf->Cell(19,6,$childcare['im_anti_measlesM_total'],1,0,'R');
        $this->fpdf->Cell(19,6,$childcare['im_anti_measlesF_total'],1,1,'R');
        
        $this->fpdf->Cell(61,6,'Fully Immunized Child(0-11 mos)',1,0,'L');
        $this->fpdf->Cell(19,6,$childcare['im_fully_under1M_total'],1,0,'R');
        $this->fpdf->Cell(19,6,$childcare['im_fully_under1F_total'],1,1,'R');
        
        $this->fpdf->Cell(61,6,'Completely Immunized Child(12-23 mos)',1,0,'L');
        $this->fpdf->Cell(19,6,$childcare['im_fully_under1M_total'],1,0,'R');
        $this->fpdf->Cell(19,6,$childcare['im_fully_under1F_total'],1,1,'R');
        
        $this->fpdf->Cell(61,6,'Total Livebirths',1,0,'L');
        $this->fpdf->Cell(19,6,$childcare['livebirths_M_total'],1,0,'R');
        $this->fpdf->Cell(19,6,$childcare['livebirths_F_total'],1,1,'R');
        
        $this->fpdf->Cell(61,6,'Child Protected at Birth (CPAB0)',1,0,'L');
        $this->fpdf->Cell(19,6,$childcare['child_protected_M_total'],1,0,'R');
        $this->fpdf->Cell(19,6,$childcare['child_protected_F_total'],1,1,'R');
        
        $this->fpdf->Cell(61,6,'Infant age 6 mos. seen',1,0,'L');
        $this->fpdf->Cell(19,6,$childcare['infant_6mos_seen_M_total'],1,0,'R');
        $this->fpdf->Cell(19,6,$childcare['infant_6mos_seen_F_total'],1,1,'R');
        
        $this->fpdf->Cell(61,6,'Infant exclusively breastfeed until 6th mo.',1,0,'L');
        $this->fpdf->Cell(19,6,$childcare['breastfeed_6th_M_total'],1,0,'R');                      
        $this->fpdf->Cell(19,6,$childcare['breastfeed_6th_F_total'],1,1,'R');
        
        $this->fpdf->Cell(61,6,'Infant referred for newborn screening',1,0,'L');
        $this->fpdf->Cell(19,6,$childcare['referred_nb_screeningM_total'],1,0,'R');
        $this->fpdf->Cell(19,6,$childcare['referred_nb_screeningF_total'],1,1,'R');
        
        //============================= Top Right Child Care =================================//
        
        $this->fpdf->SetTextColor(0,0,0);
        
        $this->fpdf->SetY(10);
        $this->fpdf->SetX(109);
        $this->fpdf->SetFont('Helvetica','BI',10);
        $this->fpdf->Cell(60,6,'CHILD CARE',1,0,'C',true);
        $this->fpdf->Cell(19,6,'Male',1,0,'C',true);
        $this->fpdf->Cell(19,6,'Female',1,1,'C',true);
        
        $this->fpdf->SetFont('Helvetica','',8);           //set new font size
        
        $this->fpdf->SetX(109);
        $this->fpdf->Cell(60,6,'Infant 6-11 months old given Vitamin A',1,0,'L');
        $this->fpdf->Cell(19,6,'-',1,0,'R');
        $this->fpdf->Cell(19,6,'-',1,1,'R');  
        
        $this->fpdf->SetX(109);
        $this->fpdf->Cell(60,6,'Children 12-59 months old given Vitamin A',1,0,'L');
        $this->fpdf->Cell(19,6,'-',1,0,'R');
        $this->fpdf->Cell(19,6,'-',1,1,'R');
        
        $this->fpdf->SetX(109);
        $this->fpdf->Cell(60,6,'Children 60-71 months old given Vitamin A',1,0,'L');
        $this->fpdf->Cell(19,6,'-',1,0,'R');
        $this->fpdf->Cell(19,6,'-',1,1,'R');
        
        $this->fpdf->SetX(109);
        $this->fpdf->Cell(60,6,'Sick Children 6-11 months seen',1,0,'L');
        $this->fpdf->Cell(19,6,'-',1,0,'R');
        $this->fpdf->Cell(19,6,'-',1,1,'R');
        
        $this->fpdf->SetX(109);
        $this->fpdf->Cell(60,6,'Sick Children 12-59 months seen',1,0,'L');
        $this->fpdf->Cell(19,6,'-',1,0,'R');
        $this->fpdf->Cell(19,6,'-',1,1,'R');
        
        $this->fpdf->SetX(109);
        $this->fpdf->Cell(60,6,'Sick Children 60-71 months seen',1,0,'L');
        $this->fpdf->Cell(19,6,'-',1,0,'R');
        $this->fpdf->Cell(19,6,'-',1,1,'R');
        
        $this->fpdf->SetX(109);
        $this->fpdf->Cell(60,6,'Sick Children 6-11 months given Vitamin A',1,0,'L');
        $this->fpdf->Cell(19,6,'-',1,0,'R');
        $this->fpdf->Cell(19,6,'-',1,1,'R');
        
        $this->fpdf->SetX(109);
        $this->fpdf->Cell(60,6,'Sick Children 12-59 months given Vitamin A',1,0,'L');
        $this->fpdf->Cell(19,6,'-',1,0,'R');
        $this->fpdf->Cell(19,6,'-',1,1,'R');
        
        $this->fpdf->SetX(109);
        $this->fpdf->Cell(60,6,'Sick Children 60-71 months given Vitamin A',1,0,'L');
        $this->fpdf->Cell(19,6,'-',1,0,'R');
        $this->fpdf->Cell(19,6,'-',1,1,'R');
        
        $this->fpdf->SetX(109);
        $this->fpdf->Cell(60,6,'Infant 2-6 mos. with Low Birth Weight seen',1,0,'L');
        $this->fpdf->Cell(19,6,'-',1,0,'R');
        $this->fpdf->Cell(19,6,'-',1,1,'R');
        
        $this->fpdf->SetX(109);
        $this->fpdf->Cell(60,6,'Infant 2-6 mos. with LBW given Iron',1,0,'L');
        $this->fpdf->Cell(19,6,'-',1,0,'R');
        $this->fpdf->Cell(19,6,'-',1,1,'R');
        
        $this->fpdf->SetX(109);
        $this->fpdf->Cell(60,6,'Anemic Children 2-59 months old seen',1,0,'L');
        $this->fpdf->Cell(19,6,'-',1,0,'R');
        $this->fpdf->Cell(19,6,'-',1,1,'R');
        
        $this->fpdf->SetX(109);
        $this->fpdf->Cell(60,6,'Anemic Children 2-59 mos old given Iron',1,0,'L');
        $this->fpdf->Cell(19,6,'-',1,0,'R');
        $this->fpdf->Cell(19,6,'-',1,1,'R');
        
        $this->fpdf->SetX(109);
        $this->fpdf->Cell(60,6,'Dairrhea case 0-59 months old seen',1,0,'L');
        $this->fpdf->Cell(19,6,'-',1,0,'R');
        $this->fpdf->Cell(19,6,'-',1,1,'R');
        
        $this->fpdf->SetX(109);
        $this->fpdf->Cell(60,6,'Dairrhea case 0-59 months old given ORT',1,0,'L');
        $this->fpdf->Cell(19,6,'-',1,0,'R');
        $this->fpdf->Cell(19,6,'-',1,1,'R');
        
        $this->fpdf->SetX(109);
        $this->fpdf->Cell(60,6,'Dairrhea case 0-59 months old given ORS',1,0,'L');
        $this->fpdf->Cell(19,6,'-',1,0,'R');
        $this->fpdf->Cell(19,6,'-',1,1,'R');
        
        $this->fpdf->SetX(109);
        $this->fpdf->Cell(60,6,'Dairrhea case 0-59 mos given ORS with Zinc',1,0,'L');
        $this->fpdf->Cell(19,6,'-',1,0,'R');
        $this->fpdf->Cell(19,6,'-',1,1,'R');
        
        $this->fpdf->SetX(109);
        $this->fpdf->Cell(60,6,'Pneumonia cases 0-59 months old',1,0,'L');
        $this->fpdf->Cell(19,6,'-',1,0,'R');
        $this->fpdf->Cell(19,6,'-',1,1,'R');
        
        $this->fpdf->SetX(109);
        $this->fpdf->Cell(60,6,'Pneumonia cases 0-59 months old given Tx',1,0,'L');
        $this->fpdf->Cell(19,6,'-',1,0,'R');
        $this->fpdf->Cell(19,6,'-',1,1,'R');
        
        //============================= Mid Left Malaria =================================//
        
        $this->fpdf->SetTextColor(0,0,0);
        
        $this->fpdf->SetFont('Helvetica','BI',10);
        $this->fpdf->Cell(46,6,'MALARIA',1,0,'C',true);
        $this->fpdf->SetFont('Helvetica','',8);
        $this->fpdf->Cell(15,6,'Pregnant',1,0,'C',true);
        $this->fpdf->Cell(19,6,'Male',1,0,'C',true);
        $this->fpdf->Cell(19,6,'Female',1,1,'C',true);
        
        $this->fpdf->SetFont('Helvetica','',8);           //set new font size
        
        $this->fpdf->Cell(46,6,'Malaria Case',1,0,'L'); 
        $this->fpdf->Cell(15,6,'',1,0,'C');
        $this->fpdf->Cell(19,6,'',1,0,'R');
        $this->fpdf->Cell(19,6,'',1,1,'R');  
        
        $this->fpdf->Cell(46,6,'- < 5 yo',1,0,'L');
        $this->fpdf->Cell(15,6,'',1,0,'C');
        $this->fpdf->Cell(19,6,'',1,0,'R');
        $this->fpdf->Cell(19,6,'',1,1,'R');
        
        $this->fpdf->Cell(46,6,'- >=5 yo',1,0,'L');
        $this->fpdf->Cell(15,6,'',1,0,'C');
        $this->fpdf->Cell(19,6,'',1,0,'R');
        $this->fpdf->Cell(19,6,'',1,1,'R');
        
        $this->fpdf->Cell(46,6,'Confirmed malaria Case',1,0,'L');
        $this->fpdf->Cell(15,6,'',1,0,'C');
        $this->fpdf->Cell(19,6,'',1,0,'R');
        $this->fpdf->Cell(19,6,'',1,1,'R');
        
        $this->fpdf->Cell(46,6,'By Species','LTRb',0,'L');
        $this->fpdf->Cell(15,6,'','LTRb',0,'C');
        $this->fpdf->Cell(19,6,'','LTRb',0,'R');
        $this->fpdf->Cell(19,6,'','LTRb',1,'R');
        
        $this->fpdf->Cell(46,6,'- P.falciparum','LtRB',0,'L');
        $this->fpdf->Cell(15,6,'','LtRB',0,'C');
        $this->fpdf->Cell(19,6,'','LtRB',0,'R');
        $this->fpdf->Cell(19,6,'','LtRB',1,'R');
        
        $this->fpdf->Cell(46,6,'- P.vivax',1,0,'L');
        $this->fpdf->Cell(15,6,'',1,0,'C');
        $this->fpdf->Cell(19,6,'',1,0,'R');
        $this->fpdf->Cell(19,6,'',1,1,'R');
        
        $this->fpdf->Cell(46,6,'- P.malariae',1,0,'L');
        $this->fpdf->Cell(15,6,'',1,0,'C');
        $this->fpdf->Cell(19,6,'',1,0,'R');
        $this->fpdf->Cell(19,6,'',1,1,'R');
        
        $this->fpdf->Cell(46,6,'- P.ovale',1,0,'L');
        $this->fpdf->Cell(15,6,'',1,0,'C');
        $this->fpdf->Cell(19,6,'',1,0,'R');
        $this->fpdf->Cell(19,6,'',1,1,'R');
        
        $this->fpdf->Cell(46,6,'By Method','LTRb',0,'L');
        $this->fpdf->Cell(15,6,'','LTRb',0,'C');
        $this->fpdf->Cell(19,6,'','LTRb',0,'R');
        $this->fpdf->Cell(19,6,'','LTRb',1,'R');
        
        $this->fpdf->Cell(46,6,'- Slide','LtRB',0,'L');
        $this->fpdf->Cell(15,6,'','LtRB',0,'C');
        $this->fpdf->Cell(19,6,'','LtRB',0,'R');
        $this->fpdf->Cell(19,6,'','LtRB',1,'R');
        
        $this->fpdf->Cell(46,6,'- RDT',1,0,'L');
        $this->fpdf->Cell(15,6,'',1,0,'C');
        $this->fpdf->Cell(19,6,'',1,0,'R');
        $this->fpdf->Cell(19,6,'',1,1,'R');
        
        $this->fpdf->Cell(46,6,'Malaria deaths',1,0,'L');
        $this->fpdf->Cell(15,6,'',1,0,'C');
        $this->fpdf->Cell(19,6,'',1,0,'R');
        $this->fpdf->Cell(19,6,'',1,1,'R');
        
        $this->fpdf->Cell(46,6,'Households at risk',1,0,'L');
        $this->fpdf->Cell(15,6,'',1,0,'C');
        $this->fpdf->Cell(19,6,'',1,0,'R');
        $this->fpdf->Cell(19,6,'',1,1,'R');
        
        $this->fpdf->Cell(46,6,'Households given ITN',1,0,'L');
        $this->fpdf->Cell(15,6,'',1,0,'C');
        $this->fpdf->Cell(19,6,'',1,0,'R');
        $this->fpdf->Cell(19,6,'',1,1,'R');
        
        //============================= Mid Right Schistomiasis =================================//
        
        $this->fpdf->SetTextColor(0,0,0);
        
        $this->fpdf->SetY(130);
        $this->fpdf->SetX(109);
        $this->fpdf->SetFont('Helvetica','BI',10);
        $this->fpdf->Cell(60,6,'SCHISTOMIASIS',1,0,'C',true); 
        $this->fpdf->Cell(19,6,'Male',1,0,'C',true);
        $this->fpdf->Cell(19,6,'Female',1,1,'C',true);
        
        $this->fpdf->SetFont('Helvetica','',8);           //set new font size
        
        $this->fpdf->SetX(109);
        $this->fpdf->Cell(60,6,'Symptomatic case',1,0,'L');
        $this->fpdf->Cell(19,6,'',1,0,'R');
        $this->fpdf->Cell(19,6,'',1,1,'R');  
        
        $this->fpdf->SetX(109);
        $this->fpdf->Cell(60,6,'Positive case',1,0,'L');
        $this->fpdf->Cell(19,6,'',1,0,'R');
        $this->fpdf->Cell(19,6,'',1,1,'R');
        
        $this->fpdf->SetX(109);
        $this->fpdf->Cell(60,6,'Case examined',1,0,'L');
        $this->fpdf->Cell(19,6,'',1,0,'R');
        $this->fpdf->Cell(19,6,'',1,1,'R');
        
        $this->fpdf->SetX(109);
        $this->fpdf->Cell(60,6,' - Low intensity',1,0,'L');
        $this->fpdf->Cell(19,6,'',1,0,'R');
        $this->fpdf->Cell(19,6,'',1,1,'R');
        
        $this->fpdf->SetX(109);
        $this->fpdf->Cell(60,6,' - Medium intensity',1,0,'L');
        $this->fpdf->Cell(19,6,'',1,0,'R');
        $this->fpdf->Cell(19,6,'',1,1,'R');
        
        $this->fpdf->SetX(109);
        $this->fpdf->Cell(60,6,' - High intensity',1,0,'L');
        $this->fpdf->Cell(19,6,'',1,0,'R');
        $this->fpdf->Cell(19,6,'',1,1,'R');
        
        $this->fpdf->SetX(109);
        $this->fpdf->Cell(60,6,'Case treated',1,0,'L');
        $this->fpdf->Cell(19,6,'',1,0,'R');
        $this->fpdf->Cell(19,6,'',1,1,'R');
        
        $this->fpdf->SetX(109);
        $this->fpdf->Cell(60,6,'Cases referred to hsp. facilities',1,0,'L');
        $this->fpdf->Cell(19,6,'',1,0,'R');
        $this->fpdf->Cell(19,6,'',1,1,'R');
        
        $this->fpdf->SetX(109);
        $this->fpdf->SetFont('Helvetica','BI',10);
        $this->fpdf->Cell(60,6,'FILARIASIS',1,0,'C',true);
        $this->fpdf->Cell(19,6,'Male',1,0,'C',true);
        $this->fpdf->Cell(19,6,'Female',1,1,'C',true);
        
        $this->fpdf->SetFont('Helvetica','',8); 
        
        $this->fpdf->SetX(109);
        $this->fpdf->Cell(60,6,'Cases examined',1,0,'L');
        $this->fpdf->Cell(19,6,'',1,0,'R');
        $this->fpdf->Cell(19,6,'',1,1,'R');
        
        $this->fpdf->SetX(109);
        $this->fpdf->Cell(60,6,'Cases Positive',1,0,'L');
        $this->fpdf->Cell(19,6,'',1,0,'R');
        $this->fpdf->Cell(19,6,'',1,1,'R');
        
        $this->fpdf->SetX(109);
        $this->fpdf->Cell(60,6,'MF in the siles found Positive',1,0,'L');
        $this->fpdf->Cell(19,6,'',1,0,'R');
        $this->fpdf->Cell(19,6,'',1,1,'R');
        
        $this->fpdf->SetX(109);
        $this->fpdf->Cell(60,6,'Persons givn MDA',1,0,'L');
        $this->fpdf->Cell(19,6,'',1,0,'R');
        $this->fpdf->Cell(19,6,'',1,1,'R');
        
        $this->fpdf->SetX(109);
        $this->fpdf->Cell(60,6,'Adenolympahangitis Cases',1,0,'L');
        $this->fpdf->Cell(19,6,'',1,0,'R');
        $this->fpdf->Cell(19,6,'',1,1,'R');
        
        $this->fpdf->SetX(109);
        $this->fpdf->Cell(60,6,'',1,0,'L');
        $this->fpdf->Cell(19,6,'',1,0,'R');
        $this->fpdf->Cell(19,6,'',1,1,'R');
        
        //============================= Bot Left Malaria =================================//
        
        $this->fpdf->SetTextColor(0,0,0);
        
        $this->fpdf->SetFont('Helvetica','BI',10);
        $this->fpdf->Cell(61,5,'TUBERCULOSIS',1,0,'C',true);
        $this->fpdf->Cell(19,5,'Male',1,0,'C',true);
        $this->fpdf->Cell(19,5,'Female',1,1,'C',true);
        
        $this->fpdf->SetFont('Helvetica','',8);           //set new font size
        
        $this->fpdf->Cell(61,5,'TB symptomanitics who underwent DSSM',1,0,'L'); 
        $this->fpdf->Cell(19,5,'',1,0,'R');
        $this->fpdf->Cell(19,5,'',1,1,'R');  
        
        $this->fpdf->Cell(61,5,'Smear positive discovered',1,0,'L');
        $this->fpdf->Cell(19,5,'',1,0,'R');
        $this->fpdf->Cell(19,5,'',1,1,'R');
        
        $this->fpdf->Cell(61,5,'New Smear(+)cases initiated treatment',1,0,'L');
        $this->fpdf->Cell(19,5,'',1,0,'R');
        $this->fpdf->Cell(19,5,'',1,1,'R');
        
        $this->fpdf->Cell(61,5,'New Smear(+)case cured',1,0,'L');
        $this->fpdf->Cell(19,5,'',1,0,'R');
        $this->fpdf->Cell(19,5,'',1,1,'R');
        
        $this->fpdf->Cell(61,5,'Smear(+)retreatment case initiated Tx',1,0,'L');
        $this->fpdf->Cell(19,5,'',1,0,'R');
        $this->fpdf->Cell(19,5,'',1,1,'R');
        
        $this->fpdf->Cell(61,5,'Smear(+)retreatment case got cured',1,0,'L');
        $this->fpdf->Cell(19,5,'',1,0,'R');
        $this->fpdf->Cell(19,5,'',1,1,'R');
        
        //============================= Bot Right Schistomiasis =================================//
        
        $this->fpdf->SetTextColor(0,0,0);
        
        $this->fpdf->SetY(226);
        $this->fpdf->SetX(109);
        $this->fpdf->SetFont('Helvetica','BI',10);
        $this->fpdf->Cell(60,5,'LEPROSY',1,0,'C',true); 
        $this->fpdf->Cell(19,5,'Male',1,0,'C',true);
        $this->fpdf->Cell(19,5,'Female',1,1,'C',true);
        
        $this->fpdf->SetFont('Helvetica','',8);           //set new font size
        
        $this->fpdf->SetX(109);
        $this->fpdf->Cell(60,5,'Leprosy cases',1,0,'L');
        $this->fpdf->Cell(19,5,'',1,0,'R');
        $this->fpdf->Cell(19,5,'',1,1,'R');  
        
        $this->fpdf->SetX(109);
        $this->fpdf->Cell(60,5,'Leprosy cases below 15 yo',1,0,'L');
        $this->fpdf->Cell(19,5,'',1,0,'R');
        $this->fpdf->Cell(19,5,'',1,1,'R');
        
        $this->fpdf->SetX(109);
        $this->fpdf->Cell(60,5,'Newly detected Leprosy cases',1,0,'L');
        $this->fpdf->Cell(19,5,'',1,0,'R');
        $this->fpdf->Cell(19,5,'',1,1,'R');
        
        $this->fpdf->SetX(109);
        $this->fpdf->Cell(60,5,'Newly detected cases w/ grade 2 disability',1,0,'L');
        $this->fpdf->Cell(19,5,'',1,0,'R');
        $this->fpdf->Cell(19,5,'',1,1,'R');
        
        $this->fpdf->SetX(109);
        $this->fpdf->Cell(60,5,'Case cured',1,0,'L');
        $this->fpdf->Cell(19,5,'',1,0,'R');
        $this->fpdf->Cell(19,5,'',1,1,'R');
        
        $this->fpdf->SetX(109);
        $this->fpdf->Cell(60,5,'',1,0,'L');
        $this->fpdf->Cell(19,5,'',1,0,'R');
        $this->fpdf->Cell(19,5,'',1,1,'R');

        $this->footer();
        
        if($action=="generate_bhs_m1_dl")  
            {$this->fpdf->Output('BHS_Monthly1_'.$monthname.'_'.$year.'.pdf','D');} 
        else
            {$this->fpdf->Output('BHS_Monthly1_'.$monthname.'_'.$year.'.pdf','I');}            

  }
  
  public function rhu_q1()
  {
        $action = $this->input->post('btnAction');
        $quarter = $this->input->post('q1_quarter');
        $year = $this->input->post('q1_year');
        
        if($year=='') 
            {$year = date('Y');} 
        if($quarter=='') 
            {$quarter = 1;}
        
        if($quarter==1)
            {$quartername="1st Quarter";}
        elseif($quarter==2)
            {$quartername="2nd Quarter";}
        elseif($quarter==3)
            {$quartername="3rd Quarter";}
        elseif($quarter==4)
            {$quartername="4th Quarter";}
        
        $this->rhu_q1_maternal($quarter,$year);
        $this->rhu_q1_family_planning($quarter,$year);
        $this->rhu_q1_dental($quarter,$year);
        $this->rhu_q1_childcare($quarter,$year);
        $this->rhu_q1_diseases();
        
        if($action=="generate_rhu_q1_dl")  
            {$this->fpdf->Output('BHS_Quarterly1_'.$quartername.'_'.$year.'.pdf','D');}
        else
            {$this->fpdf->Output('BHS_Quarterly1_'.$quartername.'_'.$year.'.pdf','I');}
  }
  
  function rhu_q1_maternal($quarter,$year)
  {
        if($quarter==1)
            {$quartername="1st Quarter";}
        elseif($quarter==2)
            {$quartername="2nd Quarter";}
        elseif($quarter==3)
            {$quartername="3rd Quarter";}
        elseif($quarter==4)
            {$quartername="4th Quarter";}
        
        $default_province = $this->settings_m->get_default_province();
        $default_city = $this->settings_m->get_default_city();
        $station_name = $this->settings_m->get_station_name();
        //$ppoty = $this->settings_m->get_station_name();
        $ppoty = 23994;
        
        $eligible_pop1 = $ppoty * 0.035;
        $eligible_pop2 = $ppoty * 0.03;
        
        // Using this data, get the relevant results 
        $maternal = $this->report_m2->get_maternal_quaterreport($quarter, $year);
        if($maternal['total2']>0)
            {$maternal_per1 = number_format(($maternal['total2']/$eligible_pop1)*100,2);}
        else
            {$maternal_per1 = "-";}
        if($maternal['total2']>0)
            {$maternal_per2 = number_format(($maternal['total2']/$eligible_pop1)*100,2);}
        else
            {$maternal_per2 = "-";}
        if($maternal['total3']>0)
            {$maternal_per3 = number_format(($maternal['total3']/$eligible_pop1)*100,2);}
        else
            {$maternal_per3 = "-";}
        if($maternal['total4']>0)
            {$maternal_per4 = number_format(($maternal['total4']/$eligible_pop1)*100,2);}
        else
            {$maternal_per4 = "-";}
        if($maternal['total5']>0)
            {$maternal_per5 = number_format(($maternal['total5']/$eligible_pop1)*100,2);}
        else
            {$maternal_per5 = "-";}
        if($maternal['total6']>0)
            {$maternal_per6 = number_format(($maternal['total6']/$eligible_pop2)*100,2);}
        else
            {$maternal_per6 = "-";}
        if($maternal['total7']>0)
            {$maternal_per7 = number_format(($maternal['total7']/$eligible_pop2)*100,2);}
        else
            {$maternal_per7 = "-";}
        if($maternal['total8']>0)
            {$maternal_per8 = number_format(($maternal['total8']/$eligible_pop2)*100,2);}
        else
            {$maternal_per8 = "-";}
        if($maternal['total9']>0)
            {$maternal_per9 = number_format(($maternal['total9']/$eligible_pop2)*100,2);}
        else
            {$maternal_per9 = "-";}
        
        $this->fpdf->AliasNbPages();
        $this->fpdf->AddPage('L','Letter');
        $this->fpdf->SetAutoPageBreak(true, 10);
        
        $this->fpdf->SetDrawColor(150,150,150);   // Set Line and Border color
        
        //Draw rectangles
        $this->fpdf->Cell(15);
        $this->fpdf->SetFillColor(247,247,247);       //Set Fill color
        $this->fpdf->Rect(10,10,260,178);             // Main box
        $this->fpdf->Rect(10,10,45,30,'D',true);      //Left box
        $this->fpdf->Rect(55,10,185,30,'D',true);     //Mid box
        $this->fpdf->Rect(240,10,30,30,'D',true);     //Right box
        
        //Header
        $this->fpdf->Image(base_url().'system/cms/themes/pyrocms/img/logo_small.png',12,20,40);
        $this->fpdf->SetY(15);
        $this->fpdf->SetX(57);
        $this->fpdf->SetFont('Helvetica','',10);
        $this->fpdf->SetTextColor(0,0,0);
        $this->fpdf->Cell(39,7,'FHSIS REPORT for the ',0,0,'L');
        $this->fpdf->Cell(35,7,$quartername,'ltrB',0,'L');
        $this->fpdf->SetX(188);
        $this->fpdf->Cell(10,7,'Year:',0,0,'L');
        $this->fpdf->Cell(12,7,$year,'ltrB',1,'L');
        $this->fpdf->SetX(57);
        $this->fpdf->Cell(32,7,'Municipality/City of:',0,0,'L');
        $this->fpdf->Cell(30,7,$default_city,'ltrB',1,'L');
        $this->fpdf->SetX(57);
        $this->fpdf->Cell(16,7,'Province:',0,0,'L');
        $this->fpdf->Cell(40,7,$default_province,'ltrB',0,'L');
        $this->fpdf->Cell(65,7,'Projected Population of the Year:',0,0,'R');
        $this->fpdf->SetX(178);
        $this->fpdf->Cell(20,7,number_format($ppoty),'ltrB',1,'L');
        
        $this->fpdf->SetFillColor(247,247,247);
        
        $this->fpdf->SetY(40);
        $this->fpdf->SetFont('Helvetica','B',12);
        $this->fpdf->Cell(260,15,'MATERNAL CARE',1,1,'C',true);
        
        $this->fpdf->SetFont('Helvetica','B',9);
        $this->fpdf->Cell(100,7,'','LTRb',0,'C');
        $this->fpdf->Cell(20,7,'Elig','LTRb',0,'L');
        $this->fpdf->Cell(20,7,'','LTRb',0,'L');
        $this->fpdf->Cell(20,7,'','LTRb',0,'L');
        $this->fpdf->Cell(50,7,'','LTRb',0,'C');
        $this->fpdf->Cell(50,7,'Recommendations/','LTRb',1,'C');
        
        $this->fpdf->Cell(100,7,'Indicators','LtRB',0,'C');
        $this->fpdf->Cell(20,7,'Pop.','LtRB',0,'L');
        $this->fpdf->Cell(20,7,'No.','LtRB',0,'L');
        $this->fpdf->Cell(20,7,'% Rate','LtRB',0,'L');
        $this->fpdf->Cell(50,7,'Interpretation','LtRB',0,'C');
        $this->fpdf->Cell(50,7,'Action Taken','LtRB',1,'C');
        
        $this->fpdf->Cell(100,8,'Col. 1',1,0,'C');
        $this->fpdf->Cell(20,8,'Col. 2',1,0,'L');
        $this->fpdf->Cell(20,8,'Col. 3',1,0,'L');
        $this->fpdf->Cell(20,8,'Col. 4',1,0,'L');
        $this->fpdf->Cell(50,8,'Col. 5',1,0,'C');
        $this->fpdf->Cell(50,8,'Col. 6',1,1,'C');
        
        $this->fpdf->SetFont('Helvetica','',8);
        
        $this->fpdf->Cell(100,8,'Pregnant women with 4 or more Prenatal visits?',1,0,'L');
        $this->fpdf->Cell(20,8,number_format($eligible_pop1),1,0,'C');
        $this->fpdf->Cell(20,8,$maternal['total2'],1,0,'C');
        $this->fpdf->Cell(20,8,$maternal_per1,1,0,'C');
        $this->fpdf->Cell(50,8,'','LtRb',0,'C');
        $this->fpdf->Cell(50,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(100,8,'Pregnant women given 2 doses of Tetanus Toxoid?',1,0,'L');
        $this->fpdf->Cell(20,8,number_format($eligible_pop1),1,0,'C');
        $this->fpdf->Cell(20,8,$maternal['total2'],1,0,'C');
        $this->fpdf->Cell(20,8,$maternal_per2,1,0,'C');
        $this->fpdf->Cell(50,8,'','LtRb',0,'C');
        $this->fpdf->Cell(50,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(100,8,'Pregnant women given TT2 plus?',1,0,'L');
        $this->fpdf->Cell(20,8,number_format($eligible_pop1),1,0,'C');
        $this->fpdf->Cell(20,8,$maternal['total3'],1,0,'C');
        $this->fpdf->Cell(20,8,$maternal_per3,1,0,'C');
        $this->fpdf->Cell(50,8,'','LtRb',0,'C');
        $this->fpdf->Cell(50,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(100,8,'Pregnant women given complete Iron w/ Folic Acid?',1,0,'L');
        $this->fpdf->Cell(20,8,number_format($eligible_pop1),1,0,'C');
        $this->fpdf->Cell(20,8,$maternal['total4'],1,0,'C');
        $this->fpdf->Cell(20,8,$maternal_per4,1,0,'C');
        $this->fpdf->Cell(50,8,'','LtRb',0,'C');
        $this->fpdf->Cell(50,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(100,8,'Pregnant women given Vitamin A',1,0,'L');
        $this->fpdf->Cell(20,8,number_format($eligible_pop1),1,0,'C');
        $this->fpdf->Cell(20,8,$maternal['total5'],1,0,'C');
        $this->fpdf->Cell(20,8,$maternal_per5,1,0,'C');
        $this->fpdf->Cell(50,8,'','LtRb',0,'C');
        $this->fpdf->Cell(50,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(100,8,'Postpartum women with at least 2 Postpartum visits',1,0,'L');
        $this->fpdf->Cell(20,8,number_format($eligible_pop2),1,0,'C');
        $this->fpdf->Cell(20,8,$maternal['total6'],1,0,'C');
        $this->fpdf->Cell(20,8,$maternal_per6,1,0,'C');
        $this->fpdf->Cell(50,8,'','LtRb',0,'C');
        $this->fpdf->Cell(50,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(100,8,'Postpartum women given complete Iron',1,0,'L');
        $this->fpdf->Cell(20,8,number_format($eligible_pop2),1,0,'C');
        $this->fpdf->Cell(20,8,$maternal['total7'],1,0,'C');
        $this->fpdf->Cell(20,8,$maternal_per7,1,0,'C');
        $this->fpdf->Cell(50,8,'','LtRb',0,'C');
        $this->fpdf->Cell(50,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(100,8,'Postpartum women given Vitamin A',1,0,'L');
        $this->fpdf->Cell(20,8,number_format($eligible_pop2),1,0,'C');
        $this->fpdf->Cell(20,8,$maternal['total8'],1,0,'C');
        $this->fpdf->Cell(20,8,$maternal_per8,1,0,'C');
        $this->fpdf->Cell(50,8,'','LtRb',0,'C');
        $this->fpdf->Cell(50,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(100,8,'Postpartum women initiated breastfeeding',1,0,'L');
        $this->fpdf->Cell(20,8,number_format($eligible_pop2),1,0,'C');
        $this->fpdf->Cell(20,8,$maternal['total9'],1,0,'C');
        $this->fpdf->Cell(20,8,$maternal_per9,1,0,'C');
        $this->fpdf->Cell(50,8,'','LtRb',0,'C');
        $this->fpdf->Cell(50,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(100,8,'',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'L');
        $this->fpdf->Cell(50,8,'','LtRb',0,'C');
        $this->fpdf->Cell(50,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(100,8,'',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'L');
        $this->fpdf->Cell(50,8,'','LtRb',0,'C');
        $this->fpdf->Cell(50,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(100,8,'',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'L');
        $this->fpdf->Cell(50,8,'','LtRb',0,'C');
        $this->fpdf->Cell(50,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(100,8,'',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'L');
        $this->fpdf->Cell(50,8,'','LtRb',0,'C');
        $this->fpdf->Cell(50,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(80,7,'Eligible Population: ? TP x 3.5%','LTrB',0,'L');
        $this->fpdf->Cell(180,7,'? TP x 3%','lTRB',1,'L');
        
        $this->footer();
        $this->q1_header_icon();
        //$this->fpdf->Output();
    }
    
    function rhu_q1_family_planning($quarter,$year)
    {           
        // Using this data, get the relevant results
        $family_planning = $this->report_m2->get_familyplanning_quarterreport($quarter, $year);
        
        $ppoty = 2394;
        
        $fstr_cu_end = ($family_planning['fstr_cu_begin']+$family_planning['fstr_na']+$family_planning['fstr_others'])-$family_planning['fstr_dropout'];
        $mstr_cu_end = ($family_planning['mstr_cu_begin']+$family_planning['mstr_na']+$family_planning['mstr_others'])-$family_planning['mstr_dropout'];
        $pills_cu_end  = ($family_planning['pills_cu_begin']+$family_planning['pills_na']+$family_planning['pills_others'])-$family_planning['pills_dropout'];
        $iud_cu_end  = ($family_planning['iud_cu_begin']+$family_planning['iud_na']+$family_planning['iud_others'])-$family_planning['iud_dropout'];
        $dmpa_cu_end   = ($family_planning['dmpa_cu_begin']+$family_planning['dmpa_na']+$family_planning['dmpa_others'])-$family_planning['dmpa_dropout'];
        $nfpcm_cu_end   = ($family_planning['nfpcm_cu_begin']+$family_planning['nfpcm_na']+$family_planning['nfpcm_others'])-$family_planning['nfpcm_dropout'];
        $nfpbbt_cu_end   = ($family_planning['nfpbbt_cu_begin']+$family_planning['nfpbbt_na']+$family_planning['nfpbbt_others'])-$family_planning['nfpbbt_dropout'];
        $nfpstm_cu_end   = ($family_planning['nfpstm_cu_begin']+$family_planning['nfpstm_na']+$family_planning['nfpstm_others'])-$family_planning['nfpstm_dropout'];
        $nfdsdm_cu_end   = ($family_planning['nfdsdm_cu_begin']+$family_planning['nfdsdm_na']+$family_planning['nfdsdm_others'])-$family_planning['nfdsdm_dropout'];
        $nfplam_cu_end   = ($family_planning['nfplam_cu_begin']+$family_planning['nfplam_na']+$family_planning['nfplam_others'])-$family_planning['nfplam_dropout'];
        $condom_cu_end   = ($family_planning['condom_cu_begin']+$family_planning['condom_na']+$family_planning['condom_others'])-$family_planning['condom_dropout'];
                
        if($fstr_cu_end>0)
            {$fstr_cpr = number_format($fstr_cu_end/$ppoty*0.145*0.85,2);}
        else
            {$fstr_cpr = "-";}
        if($mstr_cu_end>0)
            {$mstr_cpr = number_format($mstr_cu_end/$ppoty*0.145*0.85,2);}
        else
            {$mstr_cpr = "-";}
        if($pills_cu_end>0)
            {$pills_cpr  = number_format($pills_cu_end/$ppoty*0.145*0.85,2);}
        else
            {$pills_cpr = "-";}
        if($iud_cu_end>0)
            {$iud_cpr  = number_format($iud_cu_end/$ppoty*0.145*0.85,2);}
        else
            {$iud_cpr = "-";}
        if($dmpa_cu_end>0)
            {$dmpa_cpr   = number_format($dmpa_cu_end/$ppoty*0.145*0.85,2);}
        else
            {$dmpa_cpr = "-";}
        if($nfpcm_cu_end>0)
            {$nfpcm_cpr   = number_format($nfpcm_cu_end/$ppoty*0.145*0.85,2);}
        else
            {$nfpcm_cpr = "-";}
        if($nfpbbt_cu_end>0)
            {$nfpbbt_cpr   = number_format($nfpbbt_cu_end/$ppoty*0.145*0.85,2);}
        else
            {$nfpbbt_cpr = "-";}
        if($nfpstm_cu_end>0)
            {$nfpstm_cpr   = number_format($nfpstm_cu_end/$ppoty*0.145*0.85,2);}
        else
            {$nfpstm_cpr = "-";}
        if($nfdsdm_cu_end>0)
            {$nfdsdm_cpr   = number_format($nfdsdm_cu_end/$ppoty*0.145*0.85,2);}
        else
            {$nfdsdm_cpr = "-";}
        if($nfplam_cu_end>0)
            {$nfplam_cpr   = number_format($nfplam_cu_end/$ppoty*0.145*0.85,2);}
        else
            {$nfplam_cpr = "-";}
        if($condom_cu_end>0)
            {$condom_cpr   =  number_format($condom_cu_end/$ppoty*0.145*0.85,2);}
        else
            {$condom_cpr = "-";}
        
        $this->fpdf->AliasNbPages();
        $this->fpdf->AddPage('L','Letter');
        $this->fpdf->SetAutoPageBreak(true, 10);
        
        $this->fpdf->SetDrawColor(150,150,150);   // Set Line and Border color
        
        //Draw rectangles
        $this->fpdf->Cell(15);
        $this->fpdf->SetFillColor(247,247,247);       //Set Fill color
        $this->fpdf->Rect(10,15,260,155);             // Main box
        
        $this->fpdf->SetFillColor(247,247,247);
        
        $this->fpdf->SetY(15);
        $this->fpdf->SetFont('Helvetica','B',12);
        $this->fpdf->SetTextColor(0,0,0);
        $this->fpdf->Cell(260,15,'FAMILY PLANNING',1,1,'C',true);
        
        $this->fpdf->SetFont('Helvetica','B',9);
        $this->fpdf->Cell(55,5,'','LTRb',0,'C');
        $this->fpdf->Cell(25,5,'Current User','LTRb',0,'C');
        $this->fpdf->Cell(30,5,'Acceptors','LTRb',0,'C');
        $this->fpdf->Cell(15,5,'','LTRb',0,'C');
        $this->fpdf->Cell(30,5,'Current Users','LTRb',0,'C');
        $this->fpdf->Cell(25,5,'CPR','LTRb',0,'C');
        $this->fpdf->Cell(40,5,'','LTRb',0,'C');
        $this->fpdf->Cell(40,5,'Recommendations/','LTRb',1,'C');
        
        $this->fpdf->Cell(55,5,'Indicators','LtRb',0,'C');
        $this->fpdf->Cell(25,5,'(Begin Qtr.)','LtRb',0,'C');
        $this->fpdf->Cell(15,5,'New','LtRb',0,'C');
        $this->fpdf->Cell(15,5,'Others','LtRb',0,'C');
        $this->fpdf->Cell(15,5,'Drop-out','LtRb',0,'C');
        $this->fpdf->Cell(30,5,'(End Qtr.)','LtRb',0,'C');
        $this->fpdf->Cell(25,5,'Col. 6/TP x','LtRb',0,'C');
        $this->fpdf->Cell(40,5,'Interpretation','LtRb',0,'C');
        $this->fpdf->Cell(40,5,'Actions Taken','LtRb',1,'C');
        
        $this->fpdf->Cell(55,5,'','LtRB',0,'C');
        $this->fpdf->Cell(25,5,'','LtRB',0,'C');
        $this->fpdf->Cell(15,5,'','LtRB',0,'C');
        $this->fpdf->Cell(15,5,'','LtRB',0,'C');
        $this->fpdf->Cell(15,5,'','LtRB',0,'C');
        $this->fpdf->Cell(30,5,'','LtRB',0,'C');
        $this->fpdf->Cell(25,5,'14.5% x 85%','LtRB',0,'C');
        $this->fpdf->Cell(40,5,'','LtRB',0,'C');
        $this->fpdf->Cell(40,5,'','LtRB',1,'C');
        
        $this->fpdf->Cell(55,8,'Col. 1',1,0,'C');
        $this->fpdf->Cell(25,8,'Col. 2',1,0,'C');
        $this->fpdf->Cell(15,8,'Col. 3',1,0,'C');
        $this->fpdf->Cell(15,8,'Col. 4',1,0,'C');
        $this->fpdf->Cell(15,8,'Col. 5',1,0,'C');
        $this->fpdf->Cell(30,8,'Col. 6',1,0,'C');
        $this->fpdf->Cell(25,8,'Col. 7',1,0,'C');
        $this->fpdf->Cell(40,8,'Col. 8',1,0,'C');
        $this->fpdf->Cell(40,8,'Col. 9',1,1,'C');
        
        $this->fpdf->SetFont('Helvetica','',9);
        
        $this->fpdf->Cell(55,8,'a. Female Ster/BTL',1,0,'L');
        $this->fpdf->Cell(25,8,$family_planning['fstr_cu_begin'],1,0,'C');
        $this->fpdf->Cell(15,8,$family_planning['fstr_na'],1,0,'C');
        $this->fpdf->Cell(15,8,$family_planning['fstr_others'],1,0,'C');
        $this->fpdf->Cell(15,8,$family_planning['fstr_dropout'],1,0,'C');
        $this->fpdf->Cell(30,8,$fstr_cu_end,1,0,'C');
        $this->fpdf->Cell(25,8,$fstr_cpr,1,0,'C');
        $this->fpdf->Cell(40,8,'','LtRb',0,'C');
        $this->fpdf->Cell(40,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(55,8,'b. Male Ster/Vasectomy',1,0,'L');
        $this->fpdf->Cell(25,8,$family_planning['mstr_cu_begin'],1,0,'C');
        $this->fpdf->Cell(15,8,$family_planning['mstr_na'],1,0,'C');
        $this->fpdf->Cell(15,8,$family_planning['mstr_others'],1,0,'C');
        $this->fpdf->Cell(15,8,$family_planning['mstr_dropout'],1,0,'C');
        $this->fpdf->Cell(30,8,$mstr_cu_end ,1,0,'C');
        $this->fpdf->Cell(25,8,$mstr_cpr,1,0,'C');
        $this->fpdf->Cell(40,8,'','LtRb',0,'C');
        $this->fpdf->Cell(40,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(55,8,'c. Pills',1,0,'L');
        $this->fpdf->Cell(25,8,$family_planning['pills_cu_begin'],1,0,'C');
        $this->fpdf->Cell(15,8,$family_planning['pills_na'],1,0,'C');
        $this->fpdf->Cell(15,8,$family_planning['pills_others'],1,0,'C');
        $this->fpdf->Cell(15,8,$family_planning['pills_dropout'],1,0,'C');
        $this->fpdf->Cell(30,8,$pills_cu_end,1,0,'C');
        $this->fpdf->Cell(25,8,$pills_cpr,1,0,'C');      
        $this->fpdf->Cell(40,8,'','LtRb',0,'C');
        $this->fpdf->Cell(40,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(55,8,'d. IUD',1,0,'L');
        $this->fpdf->Cell(25,8,$family_planning['iud_cu_begin'],1,0,'C');
        $this->fpdf->Cell(15,8,$family_planning['iud_na'],1,0,'C');
        $this->fpdf->Cell(15,8,$family_planning['iud_others'],1,0,'C');
        $this->fpdf->Cell(15,8,$family_planning['iud_dropout'],1,0,'C');
        $this->fpdf->Cell(30,8,$iud_cu_end,1,0,'C');
        $this->fpdf->Cell(25,8,$iud_cpr,1,0,'C');
        $this->fpdf->Cell(40,8,'','LtRb',0,'C');
        $this->fpdf->Cell(40,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(55,8,'e. Injectables(DMPA)',1,0,'L');
        $this->fpdf->Cell(25,8,$family_planning['dmpa_cu_begin'],1,0,'C');
        $this->fpdf->Cell(15,8,$family_planning['dmpa_na'],1,0,'C');
        $this->fpdf->Cell(15,8,$family_planning['dmpa_others'],1,0,'C');
        $this->fpdf->Cell(15,8,$family_planning['dmpa_dropout'],1,0,'C');
        $this->fpdf->Cell(30,8,$dmpa_cu_end,1,0,'C');
        $this->fpdf->Cell(25,8,$dmpa_cpr,1,0,'C');
        $this->fpdf->Cell(40,8,'','LtRb',0,'C');
        $this->fpdf->Cell(40,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(55,8,'f. NFP-CM',1,0,'L');
        $this->fpdf->Cell(25,8,$family_planning['nfpcm_cu_begin'],1,0,'C');
        $this->fpdf->Cell(15,8,$family_planning['nfpcm_na'],1,0,'C');
        $this->fpdf->Cell(15,8,$family_planning['nfpcm_others'],1,0,'C');
        $this->fpdf->Cell(15,8,$family_planning['nfpcm_dropout'],1,0,'C');
        $this->fpdf->Cell(30,8,$nfpcm_cu_end,1,0,'C');
        $this->fpdf->Cell(25,8,$nfpcm_cpr,1,0,'C');
        $this->fpdf->Cell(40,8,'','LtRb',0,'C');
        $this->fpdf->Cell(40,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(55,8,'g. NFP-BBT',1,0,'L');
        $this->fpdf->Cell(25,8,$family_planning['nfpbbt_cu_begin'],1,0,'C');
        $this->fpdf->Cell(15,8,$family_planning['nfpbbt_na'],1,0,'C');
        $this->fpdf->Cell(15,8,$family_planning['nfpbbt_others'],1,0,'C');
        $this->fpdf->Cell(15,8,$family_planning['nfpbbt_dropout'],1,0,'C');
        $this->fpdf->Cell(30,8,$nfpbbt_cu_end,1,0,'C');
        $this->fpdf->Cell(25,8,$nfpbbt_cpr,1,0,'C');
        $this->fpdf->Cell(40,8,'','LtRb',0,'C');
        $this->fpdf->Cell(40,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(55,8,'h. NFP-STM',1,0,'L');
        $this->fpdf->Cell(25,8,$family_planning['nfpstm_cu_begin'],1,0,'C');
        $this->fpdf->Cell(15,8,$family_planning['nfpstm_na'],1,0,'C');
        $this->fpdf->Cell(15,8,$family_planning['nfpstm_others'],1,0,'C');
        $this->fpdf->Cell(15,8,$family_planning['nfpstm_dropout'],1,0,'C');
        $this->fpdf->Cell(30,8,$nfpstm_cu_end,1,0,'C');
        $this->fpdf->Cell(25,8,$nfpstm_cpr,1,0,'C');
        $this->fpdf->Cell(40,8,'','LtRb',0,'C');
        $this->fpdf->Cell(40,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(55,8,'i. NFPD-Standard Days Method',1,0,'L');
        $this->fpdf->Cell(25,8,$family_planning['nfdsdm_cu_begin'],1,0,'C');
        $this->fpdf->Cell(15,8,$family_planning['nfdsdm_na'],1,0,'C');
        $this->fpdf->Cell(15,8,$family_planning['nfdsdm_others'],1,0,'C');
        $this->fpdf->Cell(15,8,$family_planning['nfdsdm_dropout'],1,0,'C');
        $this->fpdf->Cell(30,8,$nfdsdm_cu_end,1,0,'C');
        $this->fpdf->Cell(25,8,$nfdsdm_cpr,1,0,'C');
        $this->fpdf->Cell(40,8,'','LtRb',0,'C');
        $this->fpdf->Cell(40,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(55,8,'j. NFP-LAM',1,0,'L');
        $this->fpdf->Cell(25,8,$family_planning['nfplam_cu_begin'],1,0,'C');
        $this->fpdf->Cell(15,8,$family_planning['nfplam_na'],1,0,'C');
        $this->fpdf->Cell(15,8,$family_planning['nfplam_others'],1,0,'C');
        $this->fpdf->Cell(15,8,$family_planning['nfplam_dropout'],1,0,'C');
        $this->fpdf->Cell(30,8,$nfplam_cu_end,1,0,'C');
        $this->fpdf->Cell(25,8,$nfplam_cpr,1,0,'C');
        $this->fpdf->Cell(40,8,'','LtRb',0,'C');
        $this->fpdf->Cell(40,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(55,8,'k. Condom',1,0,'L');
        $this->fpdf->Cell(25,8,$family_planning['condom_cu_begin'],1,0,'C');
        $this->fpdf->Cell(15,8,$family_planning['condom_na'],1,0,'C');
        $this->fpdf->Cell(15,8,$family_planning['condom_others'],1,0,'C');
        $this->fpdf->Cell(15,8,$family_planning['condom_dropout'],1,0,'C');
        $this->fpdf->Cell(30,8,$condom_cu_end,1,0,'C');
        $this->fpdf->Cell(25,8,$condom_cpr,1,0,'C');
        $this->fpdf->Cell(40,8,'','LtRb',0,'C');
        $this->fpdf->Cell(40,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(55,8,'',1,0,'L');
        $this->fpdf->Cell(25,8,'',1,0,'C');
        $this->fpdf->Cell(15,8,'',1,0,'C');
        $this->fpdf->Cell(15,8,'',1,0,'C');
        $this->fpdf->Cell(15,8,'',1,0,'C');
        $this->fpdf->Cell(30,8,'',1,0,'C');
        $this->fpdf->Cell(25,8,'',1,0,'C');
        $this->fpdf->Cell(40,8,'','LtRb',0,'C');
        $this->fpdf->Cell(40,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(55,8,'',1,0,'L');
        $this->fpdf->Cell(25,8,'',1,0,'C');
        $this->fpdf->Cell(15,8,'',1,0,'C');
        $this->fpdf->Cell(15,8,'',1,0,'C');
        $this->fpdf->Cell(15,8,'',1,0,'C');
        $this->fpdf->Cell(30,8,'',1,0,'C');
        $this->fpdf->Cell(25,8,'',1,0,'C');
        $this->fpdf->Cell(40,8,'','LtRb',0,'C');
        $this->fpdf->Cell(40,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(55,8,'',1,0,'L');
        $this->fpdf->Cell(25,8,'',1,0,'C');
        $this->fpdf->Cell(15,8,'',1,0,'C');
        $this->fpdf->Cell(15,8,'',1,0,'C');
        $this->fpdf->Cell(15,8,'',1,0,'C');
        $this->fpdf->Cell(30,8,'',1,0,'C');
        $this->fpdf->Cell(25,8,'',1,0,'C');
        $this->fpdf->Cell(40,8,'','LtRb',0,'C');
        $this->fpdf->Cell(40,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(80,5,'','LTrB',0,'L');
        $this->fpdf->Cell(180,5,'','lTRB',1,'L');
        
        $this->fpdf->Cell(50,8,'* Others include CM, CC and RS',0,0,'L');
        
        $this->footer();
        $this->q1_pagenum();
        
        //$this->fpdf->Output();
    }
    
    function rhu_q1_dental($quarter,$year)
    {
        $base_where = $this->session->userdata('base_where');
            
        // Using this data, get the relevant results
        $dental = $this->report_m2->get_dental_quaterreport($quarter, $year);
       
        if($dental['orally_fit_1271_mo']>0)
            {$orally_fit_1271_per = number_format($dental['orally_fit_1271_mo']/171,2);}
        else
            {$orally_fit_1271_per = "-";}
        if($dental['orally_fit_1271_mo']>0)
            {$bohc_1271_mo_per = number_format($dental['bohc_1271_mo']/171,2);}
        else
            {$bohc_1271_mo_per = "-";} 
        if($dental['bohc_1024_yo']>0)
            {$bohc_1024_yo_per = number_format($dental['bohc_1024_yo']/190,2);}
        else
            {$bohc_1024_yo_per = "-";}  
        if($dental['bohc_pregnant']>0)
            {$bohc_pregnant_per = number_format($dental['bohc_pregnant']/55,2);}
        else
            {$bohc_pregnant_per = "-";}   
        if($dental['bohc_60plus_yo']>0)
            {$bohc_60plus_yo_per = number_format($dental['bohc_60plus_yo']/116,2);}
        else
            {$bohc_60plus_yo_per = "-";}
        
        $this->fpdf->AliasNbPages();
        $this->fpdf->AddPage('L','Letter');
        $this->fpdf->SetAutoPageBreak(true, 10);
        
        $this->fpdf->SetDrawColor(150,150,150);   // Set Line and Border color
        
        //Draw rectangles
        $this->fpdf->Cell(15);
        $this->fpdf->SetFillColor(247,247,247);       //Set Fill color
        $this->fpdf->Rect(10,15,260,150);             // Main box
        
        $this->fpdf->SetFillColor(247,247,247);
        
        $this->fpdf->SetY(15);
        $this->fpdf->SetFont('Helvetica','B',12);
        $this->fpdf->SetTextColor(0,0,0);
        $this->fpdf->Cell(260,15,'DENTAL CARE',1,1,'C',true);
        
        $this->fpdf->SetFont('Helvetica','B',9);
        $this->fpdf->Cell(80,5,'','LTRb',0,'C');
        $this->fpdf->Cell(20,5,'Elig.','LTRb',0,'C');
        $this->fpdf->Cell(80,5,'Number','1',0,'C');
        $this->fpdf->Cell(30,5,'','LTRb',0,'C');
        $this->fpdf->Cell(50,5,'Recommendations/','LTRb',1,'C');
        
        $this->fpdf->Cell(80,5,'Indicators','LtRb',0,'C');
        $this->fpdf->Cell(20,5,'Pop.','LtRb',0,'C');
        $this->fpdf->Cell(20,5,'Male','LtRb',0,'C');
        $this->fpdf->Cell(20,5,'Female','LtRb',0,'C');
        $this->fpdf->Cell(20,5,'Total','LtRb',0,'C');
        $this->fpdf->Cell(20,5,'%','LtRb',0,'C');
        $this->fpdf->Cell(30,5,'Interpretation','LtRb',0,'C');
        $this->fpdf->Cell(50,5,'Actions Taken','LtRb',1,'C');
        
        $this->fpdf->Cell(80,8,'Col. 1',1,0,'C');
        $this->fpdf->Cell(20,8,'Col. 2',1,0,'C');
        $this->fpdf->Cell(20,8,'Col. 3',1,0,'C');
        $this->fpdf->Cell(20,8,'Col. 4',1,0,'C');
        $this->fpdf->Cell(20,8,'Col. 5',1,0,'C');
        $this->fpdf->Cell(20,8,'Col. 6',1,0,'C');
        $this->fpdf->Cell(30,8,'Col. 7',1,0,'C');
        $this->fpdf->Cell(50,8,'Col. 8',1,1,'C');
        
        $this->fpdf->SetFont('Helvetica','',9);
        
        $this->fpdf->Cell(80,8,'Orally Fit Childen 12-71 mos old?',1,0,'L');
        $this->fpdf->Cell(20,8,'171',1,0,'C');
        $this->fpdf->Cell(20,8,$dental['orally_fit_1271_mo_m'],1,0,'C');
        $this->fpdf->Cell(20,8,$dental['orally_fit_1271_mo_f'],1,0,'C');
        $this->fpdf->Cell(20,8,$dental['orally_fit_1271_mo'],1,0,'C');
        $this->fpdf->Cell(20,8,$orally_fit_1271_per,1,0,'C');
        $this->fpdf->Cell(30,8,'','LtRb',0,'C');
        $this->fpdf->Cell(50,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(80,8,'Childen 12-71 mos old provided w/ BOHC?',1,0,'L');
        $this->fpdf->Cell(20,8,'171',1,0,'C');
        $this->fpdf->Cell(20,8,$dental['bohc_1271_mo_m'],1,0,'C');
        $this->fpdf->Cell(20,8,$dental['bohc_1271_mo_f'],1,0,'C');
        $this->fpdf->Cell(20,8,$dental['bohc_1271_mo'],1,0,'C');
        $this->fpdf->Cell(20,8,$bohc_1271_mo_per,1,0,'C');
        $this->fpdf->Cell(30,8,'','LtRb',0,'C');
        $this->fpdf->Cell(50,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(80,8,'Adolescent & Youth (10-24 yrs) given BOHC?',1,0,'L');
        $this->fpdf->Cell(20,8,'190',1,0,'C');
        $this->fpdf->Cell(20,8,$dental['bohc_1024_yo_m'],1,0,'C');
        $this->fpdf->Cell(20,8,$dental['bohc_1024_yo_f'],1,0,'C');
        $this->fpdf->Cell(20,8,$dental['bohc_1024_yo'],1,0,'C');
        $this->fpdf->Cell(20,8,$bohc_1024_yo_per,1,0,'C');
        $this->fpdf->Cell(30,8,'','LtRb',0,'C');
        $this->fpdf->Cell(50,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(80,8,'Pregnant women provided with BOHC',1,0,'L');
        $this->fpdf->Cell(20,8,'55',1,0,'C');
        $this->fpdf->Cell(20,8,'-',1,0,'C');
        $this->fpdf->Cell(20,8,$dental['bohc_pregnant'],1,0,'C');
        $this->fpdf->Cell(20,8,$dental['bohc_pregnant'],1,0,'C');
        $this->fpdf->Cell(20,8,$bohc_pregnant_per,1,0,'C');
        $this->fpdf->Cell(30,8,'','LtRb',0,'C');
        $this->fpdf->Cell(50,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(80,8,'Older Person 60 yrs old & above provided with BOHC?',1,0,'L');
        $this->fpdf->Cell(20,8,'116',1,0,'C');
        $this->fpdf->Cell(20,8,$dental['bohc_60plus_yo_m'],1,0,'C');
        $this->fpdf->Cell(20,8,$dental['bohc_60plus_yo_f'],1,0,'C');
        $this->fpdf->Cell(20,8,$dental['bohc_60plus_yo'],1,0,'C');
        $this->fpdf->Cell(20,8,$bohc_60plus_yo_per,1,0,'C');
        $this->fpdf->Cell(30,8,'','LtRb',0,'C');
        $this->fpdf->Cell(50,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(80,8,'',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(30,8,'','LtRb',0,'C');
        $this->fpdf->Cell(50,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(80,8,'',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(30,8,'','LtRb',0,'C');
        $this->fpdf->Cell(50,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(80,8,'',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(30,8,'','LtRb',0,'C');
        $this->fpdf->Cell(50,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(80,8,'',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(30,8,'','LtRb',0,'C');
        $this->fpdf->Cell(50,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(80,8,'',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(30,8,'','LtRb',0,'C');
        $this->fpdf->Cell(50,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(80,8,'',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(30,8,'','LtRb',0,'C');
        $this->fpdf->Cell(50,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(80,8,'',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(30,8,'','LtRb',0,'C');
        $this->fpdf->Cell(50,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(80,8,'',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(30,8,'','LtRb',0,'C');
        $this->fpdf->Cell(50,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(80,8,'',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(30,8,'','LtRb',0,'C');
        $this->fpdf->Cell(50,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(80,5,'','LTrB',0,'L');
        $this->fpdf->Cell(180,5,'','lTRB',1,'L');
        
        $this->fpdf->SetFont('Helvetica','I',9);
        $this->fpdf->Cell(50,8,'Eligible Population ?TPX13.5%X20%(ST)  ?TPX3.5%X25%(ST)  ?TPX13.5%X20%(ST) ?TPX6.1%X30%(ST)',0,0,'L');
        
        $this->footer();
        $this->q1_pagenum();
        
        //$this->fpdf->Output();
    }
    
    function rhu_q1_childcare($quarter, $year)                                                  
    {            
      // Using this data, get the relevant results
        $childcare = $this->report_m2->get_childcare_quarterreport($quarter, $year);
        
        $ppoty = 23994;
        $eligible_pop = $ppoty * 0.027;
        
        $bcg_Total = $childcare['bcgF_total']+$childcare['bcgF_total'];
        $dpt1_Total = $childcare['dpt1F_total']+$childcare['dpt1M_total'];
        $dpt2_Total = $childcare['dpt2F_total']+$childcare['dpt2M_total'];
        $dpt3_Total = $childcare['dpt3F_total']+$childcare['dpt3M_total'];
        $opv1_Total = $childcare['opv1F_total']+$childcare['opv1M_total'];
        $opv2_Total = $childcare['opv2F_total']+$childcare['opv2M_total'];
        $opv3_Total = $childcare['opv3F_total']+$childcare['opv3M_total'];
        $hepa_b1_with_in_Total = $childcare['hepa_b1_with_inF_total']+$childcare['hepa_b1_with_inM_total'];
        $hepa_b1_more_than_Total = $childcare['hepa_b1_more_thanF_total']+$childcare['hepa_b1_more_thanM_total'];
        $hepa_b2_Total = $childcare['hepa_b2F_total']+$childcare['hepa_b2M_total'];
        $hepa_b3_Total = $childcare['hepa_b3F_total']+$childcare['hepa_b3M_total'];
        $im_anti_measles_Total = $childcare['im_anti_measlesF_total']+$childcare['im_anti_measlesM_total'];
        $im_fully_under_Total = $childcare['im_fully_under1F_total']+$childcare['im_fully_under1M_total'];
        $child_protected_Total = $childcare['child_protected_F_total']+$childcare['child_protected_M_total'];
        $breastfeed_6th_Total = $childcare['breastfeed_6th_F_total']+$childcare['breastfeed_6th_M_total'];
        $referred_nb_screening_Total = $childcare['referred_nb_screeningF_total']+$childcare['referred_nb_screeningM_total'];
        
        if($bcg_Total>0)
            {$bcg_per = number_format($bcg_Total/$eligible_pop,2);}
        else
            {$bcg_per = "-";}
        if($dpt1_Total>0)
            {$dpt1_per = number_format($dpt1_Total/$eligible_pop,2);}
        else
            {$dpt1_per = "-";}
        if($dpt2_Total>0)
            {$dpt2_per = number_format($dpt2_Total/$eligible_pop,2);}
        else
            {$dpt2_per = "-";}
        if($dpt3_Total>0)
            {$dpt3_per = number_format($dpt3_Total/$eligible_pop,2);}
        else
            {$dpt3_per = "-";}
        if($opv1_Total>0)
            {$opv1_per = number_format($opv1_Total/$eligible_pop,2);}
        else
            {$opv1_per = "-";}
        if($opv2_Total>0)
            {$opv2_per = number_format($opv2_Total/$eligible_pop,2);}
        else
            {$opv2_per = "-";}
        if($opv3_Total>0)
            {$opv3_per = number_format($opv3_Total/$eligible_pop,2);}
        else
            {$opv3_per = "-";}
        if($hepa_b1_with_in_Total>0)
            {$hepa_b1_with_in_per = number_format($hepa_b1_with_in_Total/$eligible_pop,2);}
        else
            {$hepa_b1_with_in_per = "-";}
        if($hepa_b1_more_than_Total>0)
            {$hepa_b1_more_than_per = number_format($hepa_b1_more_than_Total/$eligible_pop,2);}
        else
            {$hepa_b1_more_than_per = "-";}
        if($hepa_b2_Total>0)
            {$hepa_b2_per = number_format($hepa_b2_Total/$eligible_pop,2);}
        else
            {$hepa_b2_per = "-";}
        if($hepa_b3_Total>0)
            {$hepa_b3_per = number_format($hepa_b3_Total/$eligible_pop,2);}
        else
            {$hepa_b3_per = "-";}
        if($im_anti_measles_Total>0)
            {$im_anti_measles_per = number_format($im_anti_measles_Total/$eligible_pop,2);}
        else
            {$im_anti_measles_per = "-";}
        if($im_fully_under_Total>0)
            {$im_fully_under_per = number_format($im_fully_under_Total/$eligible_pop,2);}
        else
            {$im_fully_under_per = "-";}
        if($child_protected_Total>0)
            {$child_protected_per = number_format($child_protected_Total/$eligible_pop,2);}
        else
            {$child_protected_per = "-";}
        if($breastfeed_6th_Total>0)
            {$breastfeed_6th_per = number_format($breastfeed_6th_Total/$eligible_pop,2);}
        else
            {$breastfeed_6th_per = "-";}
        if($referred_nb_screening_Total>0)
            {$referred_nb_screening_per =  number_format($referred_nb_screening_Total/$eligible_pop,2);}
        else
            {$referred_nb_screening_per = "-";}
        
        $this->fpdf->AliasNbPages();
        $this->fpdf->AddPage('L','Letter');
        $this->fpdf->SetAutoPageBreak(true, 10);
        
        $this->fpdf->SetDrawColor(150,150,150);   // Set Line and Border color
        
        //Draw rectangles
        $this->fpdf->Cell(15);
        $this->fpdf->SetFillColor(247,247,247);       //Set Fill color
        //$this->fpdf->Rect(10,15,260,178);             // Main box
        
        $this->fpdf->SetFillColor(247,247,247);
        
        $this->fpdf->SetY(15);
        $this->fpdf->SetFont('Helvetica','B',12);
        $this->fpdf->SetTextColor(0,0,0);
        $this->fpdf->Cell(260,15,'CHILD CARE',1,1,'C',true);
        
        $this->fpdf->SetFont('Helvetica','B',9);
        $this->fpdf->Cell(80,5,'','LTRb',0,'C');
        $this->fpdf->Cell(20,5,'Elig.','LTRb',0,'C');
        $this->fpdf->Cell(80,5,'Number','1',0,'C');
        $this->fpdf->Cell(30,5,'','LTRb',0,'C');
        $this->fpdf->Cell(50,5,'Recommendations/','LTRb',1,'C');
        
        $this->fpdf->Cell(80,5,'Indicators','LtRb',0,'C');
        $this->fpdf->Cell(20,5,'Pop.','LtRb',0,'C');
        $this->fpdf->Cell(20,5,'Male','LtRb',0,'C');
        $this->fpdf->Cell(20,5,'Female','LtRb',0,'C');
        $this->fpdf->Cell(20,5,'Total','LtRb',0,'C');
        $this->fpdf->Cell(20,5,'%','LtRb',0,'C');
        $this->fpdf->Cell(30,5,'Interpretation','LtRb',0,'C');
        $this->fpdf->Cell(50,5,'Actions Taken','LtRb',1,'C');
        
        $this->fpdf->Cell(80,5,'Col. 1',1,0,'C');
        $this->fpdf->Cell(20,5,'Col. 2',1,0,'C');
        $this->fpdf->Cell(20,5,'Col. 3',1,0,'C');
        $this->fpdf->Cell(20,5,'Col. 4',1,0,'C');
        $this->fpdf->Cell(20,5,'Col. 5',1,0,'C');
        $this->fpdf->Cell(20,5,'Col. 6',1,0,'C');
        $this->fpdf->Cell(30,5,'Col. 7',1,0,'C');
        $this->fpdf->Cell(50,5,'Col. 8',1,1,'C');
        
        $this->fpdf->SetFont('Helvetica','',9);
        
        $this->fpdf->Cell(80,7,'Infant given BCG?',1,0,'L');
        $this->fpdf->Cell(20,7,number_format($eligible_pop),1,0,'C');
        $this->fpdf->Cell(20,7,$childcare['bcgM_total'],1,0,'C');
        $this->fpdf->Cell(20,7,$childcare['bcgF_total'],1,0,'C');
        $this->fpdf->Cell(20,7,$bcg_Total,1,0,'C');
        $this->fpdf->Cell(20,7,$bcg_per,1,0,'C');
        $this->fpdf->Cell(30,7,'','LtRb',0,'C');
        $this->fpdf->Cell(50,7,'','LtRb',1,'C');
        
        $this->fpdf->Cell(80,7,'Infant given DPT1?',1,0,'L');
        $this->fpdf->Cell(20,7,number_format($eligible_pop),1,0,'C');
        $this->fpdf->Cell(20,7,$childcare['dpt1M_total'],1,0,'C');
        $this->fpdf->Cell(20,7,$childcare['dpt1F_total'],1,0,'C');
        $this->fpdf->Cell(20,7,$dpt1_Total,1,0,'C');
        $this->fpdf->Cell(20,7,$dpt1_per,1,0,'C');
        $this->fpdf->Cell(30,7,'','LtRb',0,'C');
        $this->fpdf->Cell(50,7,'','LtRb',1,'C');
        
        $this->fpdf->Cell(80,7,'Infant given DPT2?',1,0,'L');
        $this->fpdf->Cell(20,7,number_format($eligible_pop),1,0,'C');
        $this->fpdf->Cell(20,7,$childcare['dpt2M_total'],1,0,'C');
        $this->fpdf->Cell(20,7,$childcare['dpt2F_total'],1,0,'C');
        $this->fpdf->Cell(20,7,$dpt2_Total,1,0,'C');
        $this->fpdf->Cell(20,7,$dpt2_per,1,0,'C');
        $this->fpdf->Cell(30,7,'','LtRb',0,'C');
        $this->fpdf->Cell(50,7,'','LtRb',1,'C');
        
        $this->fpdf->Cell(80,7,'Infant given DPT3?',1,0,'L');
        $this->fpdf->Cell(20,7,number_format($eligible_pop),1,0,'C');
        $this->fpdf->Cell(20,7,$childcare['dpt3M_total'],1,0,'C');
        $this->fpdf->Cell(20,7,$childcare['dpt3F_total'],1,0,'C');
        $this->fpdf->Cell(20,7,$dpt3_Total,1,0,'C');
        $this->fpdf->Cell(20,7,$dpt3_per,1,0,'C');
        $this->fpdf->Cell(30,7,'','LtRb',0,'C');
        $this->fpdf->Cell(50,7,'','LtRb',1,'C');
        
        $this->fpdf->Cell(80,7,'Infant given OPV1?',1,0,'L');
        $this->fpdf->Cell(20,7,number_format($eligible_pop),1,0,'C');
        $this->fpdf->Cell(20,7,$childcare['opv1M_total'],1,0,'C');
        $this->fpdf->Cell(20,7,$childcare['opv1F_total'],1,0,'C');
        $this->fpdf->Cell(20,7,$opv1_Total,1,0,'C');
        $this->fpdf->Cell(20,7,$opv1_per,1,0,'C');
        $this->fpdf->Cell(30,7,'','LtRb',0,'C');
        $this->fpdf->Cell(50,7,'','LtRb',1,'C');
        
        $this->fpdf->Cell(80,7,'Infant given OPV2?',1,0,'L');
        $this->fpdf->Cell(20,7,number_format($eligible_pop),1,0,'C');
        $this->fpdf->Cell(20,7,$childcare['opv2M_total'],1,0,'C');
        $this->fpdf->Cell(20,7,$childcare['opv2F_total'],1,0,'C');
        $this->fpdf->Cell(20,7,$opv2_Total,1,0,'C');
        $this->fpdf->Cell(20,7,$opv2_per,1,0,'C');
        $this->fpdf->Cell(30,7,'','LtRb',0,'C');
        $this->fpdf->Cell(50,7,'','LtRb',1,'C');
        
        $this->fpdf->Cell(80,7,'Infant given OPV3?',1,0,'L');
        $this->fpdf->Cell(20,7,number_format($eligible_pop),1,0,'C');
        $this->fpdf->Cell(20,7,$childcare['opv3M_total'],1,0,'C');
        $this->fpdf->Cell(20,7,$childcare['opv3F_total'],1,0,'C');
        $this->fpdf->Cell(20,7,$opv3_Total,1,0,'C');
        $this->fpdf->Cell(20,7,$opv3_per,1,0,'C');
        $this->fpdf->Cell(30,7,'','LtRb',0,'C');
        $this->fpdf->Cell(50,7,'','LtRb',1,'C');
        
        $this->fpdf->Cell(80,7,'Infant given Hepa B1 w/in 24 hrs. after birth2',1,0,'L');
        $this->fpdf->Cell(20,7,number_format($eligible_pop),1,0,'C');
        $this->fpdf->Cell(20,7,$childcare['hepa_b1_with_inM_total'],1,0,'C');
        $this->fpdf->Cell(20,7,$childcare['hepa_b1_with_inF_total'],1,0,'C');
        $this->fpdf->Cell(20,7,$hepa_b1_with_in_Total,1,0,'C');
        $this->fpdf->Cell(20,7,$hepa_b1_with_in_per,1,0,'C');
        $this->fpdf->Cell(30,7,'','LtRb',0,'C');
        $this->fpdf->Cell(50,7,'','LtRb',1,'C');
        
         $this->fpdf->Cell(80,7,'Infant given Hepa B1 more than 24 hrs. after birth2',1,0,'L');
        $this->fpdf->Cell(20,7,number_format($eligible_pop),1,0,'C');
        $this->fpdf->Cell(20,7,$childcare['hepa_b1_more_thanM_total'],1,0,'C');
        $this->fpdf->Cell(20,7,$childcare['hepa_b1_more_thanF_total'],1,0,'C');
        $this->fpdf->Cell(20,7,$hepa_b1_more_than_Total,1,0,'C');
        $this->fpdf->Cell(20,7,$hepa_b1_more_than_per,1,0,'C');
        $this->fpdf->Cell(30,7,'','LtRb',0,'C');
        $this->fpdf->Cell(50,7,'','LtRb',1,'C');
        
        $this->fpdf->Cell(80,7,'Infant given Hepatitis B2?',1,0,'L');
        $this->fpdf->Cell(20,7,number_format($eligible_pop),1,0,'C');
        $this->fpdf->Cell(20,7,$childcare['hepa_b2M_total'],1,0,'C');
        $this->fpdf->Cell(20,7,$childcare['hepa_b2F_total'],1,0,'C');
        $this->fpdf->Cell(20,7,$hepa_b2_Total,1,0,'C');
        $this->fpdf->Cell(20,7,$hepa_b2_per,1,0,'C');
        $this->fpdf->Cell(30,7,'','LtRb',0,'C');
        $this->fpdf->Cell(50,7,'','LtRb',1,'C');
        
        $this->fpdf->Cell(80,7,'Infant given Hepatitis B3?',1,0,'L');
        $this->fpdf->Cell(20,7,number_format($eligible_pop),1,0,'C');
        $this->fpdf->Cell(20,7,$childcare['hepa_b3M_total'],1,0,'C');
        $this->fpdf->Cell(20,7,$childcare['hepa_b3F_total'],1,0,'C');
        $this->fpdf->Cell(20,7,$hepa_b3_Total,1,0,'C');
        $this->fpdf->Cell(20,7,$hepa_b3_per,1,0,'C');
        $this->fpdf->Cell(30,7,'','LtRb',0,'C');
        $this->fpdf->Cell(50,7,'','LtRb',1,'C');
        
        $this->fpdf->Cell(80,7,'Infant given anti-Measles?',1,0,'L');
        $this->fpdf->Cell(20,7,number_format($eligible_pop),1,0,'C');
        $this->fpdf->Cell(20,7,$childcare['im_anti_measlesM_total'],1,0,'C');
        $this->fpdf->Cell(20,7,$childcare['im_anti_measlesF_total'],1,0,'C');
        $this->fpdf->Cell(20,7,$im_anti_measles_Total,1,0,'C');
        $this->fpdf->Cell(20,7,$im_anti_measles_per,1,0,'C');
        $this->fpdf->Cell(30,7,'','LtRb',0,'C');
        $this->fpdf->Cell(50,7,'','LtRb',1,'C');
        
        $this->fpdf->Cell(80,7,'Fully Immunized Child(0-11 mos)',1,0,'L');
        $this->fpdf->Cell(20,7,number_format($eligible_pop),1,0,'C');
        $this->fpdf->Cell(20,7,$childcare['im_fully_under1M_total'],1,0,'C');
        $this->fpdf->Cell(20,7,$childcare['im_fully_under1F_total'],1,0,'C');
        $this->fpdf->Cell(20,7,$im_fully_under_Total,1,0,'C');
        $this->fpdf->Cell(20,7,$im_fully_under_per,1,0,'C');
        $this->fpdf->Cell(30,7,'','LtRb',0,'C');
        $this->fpdf->Cell(50,7,'','LtRb',1,'C');
        
        $this->fpdf->Cell(80,7,'Completely Immunized Child(12-23 mos)',1,0,'L');
        $this->fpdf->Cell(20,7,number_format($eligible_pop),1,0,'C');
        $this->fpdf->Cell(20,7,$childcare['bcgF_total'],1,0,'C');
        $this->fpdf->Cell(20,7,$childcare['bcgF_total'],1,0,'C');
        $this->fpdf->Cell(20,7,$im_fully_under_Total,1,0,'C');
        $this->fpdf->Cell(20,7,$im_fully_under_per,1,0,'C');
        $this->fpdf->Cell(30,7,'','LtRb',0,'C');
        $this->fpdf->Cell(50,7,'','LtRb',1,'C');
        
        $this->fpdf->Cell(80,7,'Total Livebirths',1,0,'L');
        $this->fpdf->Cell(20,7,number_format($eligible_pop),1,0,'C');
        $this->fpdf->Cell(20,7,$childcare['bcgF_total'],1,0,'C');
        $this->fpdf->Cell(20,7,$childcare['bcgF_total'],1,0,'C');
        $this->fpdf->Cell(20,7,$im_fully_under_Total,1,0,'C');
        $this->fpdf->Cell(20,7,$im_fully_under_per,1,0,'C');
        $this->fpdf->Cell(30,7,'','LtRb',0,'C');
        $this->fpdf->Cell(50,7,'','LtRb',1,'C');
        
        $this->fpdf->Cell(80,7,'Child Protected at Birth (CPAB)?',1,0,'L');
        $this->fpdf->Cell(20,7,number_format($eligible_pop),1,0,'C');
        $this->fpdf->Cell(20,7,$childcare['child_protected_M_total'],1,0,'C');
        $this->fpdf->Cell(20,7,$childcare['child_protected_F_total'],1,0,'C');
        $this->fpdf->Cell(20,7,$child_protected_Total,1,0,'C');
        $this->fpdf->Cell(20,7,$child_protected_per,1,0,'C');
        $this->fpdf->Cell(30,7,'','LtRb',0,'C');
        $this->fpdf->Cell(50,7,'','LtRb',1,'C');
        
        $this->fpdf->Cell(80,7,'Infant age 6 mos. Seen',1,0,'L');
        $this->fpdf->Cell(20,7,number_format($eligible_pop),1,0,'C');
        $this->fpdf->Cell(20,7,$childcare['bcgF_total'],1,0,'C');
        $this->fpdf->Cell(20,7,$childcare['bcgF_total'],1,0,'C');
        $this->fpdf->Cell(20,7,$child_protected_Total,1,0,'C');
        $this->fpdf->Cell(20,7,$child_protected_per,1,0,'C');
        $this->fpdf->Cell(30,7,'','LtRb',0,'C');
        $this->fpdf->Cell(50,7,'','LtRb',1,'C');
        
        $this->fpdf->Cell(80,7,'Infant exclusively breastfeed until 6th mos?',1,0,'L');
        $this->fpdf->Cell(20,7,number_format($eligible_pop),1,0,'C');
        $this->fpdf->Cell(20,7,$childcare['breastfeed_6th_M_total'],1,0,'C');
        $this->fpdf->Cell(20,7,$childcare['breastfeed_6th_F_total'],1,0,'C');
        $this->fpdf->Cell(20,7,$breastfeed_6th_Total,1,0,'C');
        $this->fpdf->Cell(20,7,$breastfeed_6th_per,1,0,'C');
        $this->fpdf->Cell(30,7,'','LtRb',0,'C');
        $this->fpdf->Cell(50,7,'','LtRb',1,'C');
        
        $this->fpdf->Cell(80,7,'Infant 0-11 mos. referred for newborn screening',1,0,'L');
        $this->fpdf->Cell(20,7,number_format($eligible_pop),1,0,'C');
        $this->fpdf->Cell(20,7,$childcare['referred_nb_screeningM_total'],1,0,'C');
        $this->fpdf->Cell(20,7,$childcare['referred_nb_screeningF_total'],1,0,'C');
        $this->fpdf->Cell(20,7,$referred_nb_screening_Total,1,0,'C');
        $this->fpdf->Cell(20,7,$referred_nb_screening_per,1,0,'C');
        $this->fpdf->Cell(30,7,'','LtRb',0,'C');
        $this->fpdf->Cell(50,7,'','LtRb',1,'C');
        
        $this->fpdf->SetFont('Helvetica','I',9);
        $this->fpdf->Cell(80,7,'Eligible Population ? TP x 2.7%','LTrB',0,'L');
        $this->fpdf->Cell(40,7,'? Total Livebirths','lTrB',0,'L');
        $this->fpdf->Cell(60,7,'? No. Infant seen at 6th month','lTrB',0,'L');
        $this->fpdf->Cell(80,7,'','lTRB',1,'C');
                                                                               
        $this->footer();
        $this->q1_pagenum();
        
        //$this->fpdf->Output();
    }
    
    function rhu_q1_diseases()                                                  
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
        //$this->fpdf->Rect(10,15,260,178);             // Main box
        
        $this->fpdf->SetFillColor(247,247,247);
        
        $this->fpdf->SetY(15);
        $this->fpdf->SetFont('Helvetica','B',12);
        $this->fpdf->SetTextColor(0,0,0);
        $this->fpdf->Cell(260,15,'DISEASES CONTROL',1,1,'C',true);
        
        $this->fpdf->SetFont('Helvetica','B',9);
        $this->fpdf->Cell(60,5,'Malaria','LTRb',0,'C');
        $this->fpdf->Cell(80,5,'Number','LTRb',0,'C');
        $this->fpdf->Cell(50,5,'','1',0,'C');
        $this->fpdf->Cell(30,5,'','LTRb',0,'C');
        $this->fpdf->Cell(40,5,'Recommendations/','LTRb',1,'C');
        
        $this->fpdf->Cell(60,5,'(endemic areas)','LtRb',0,'C');
        $this->fpdf->Cell(20,5,'Male.',1,0,'C');
        $this->fpdf->Cell(20,5,'Female',1,0,'C');
        $this->fpdf->Cell(20,5,'Pregnant',1,0,'C');
        $this->fpdf->Cell(20,5,'Total',1,0,'C');
        $this->fpdf->Cell(50,5,'Rate',1,0,'C');
        $this->fpdf->Cell(30,5,'Interpretation',1,0,'C');
        $this->fpdf->Cell(40,5,'Actions Taken',1,1,'C');
        
        $this->fpdf->SetFont('Helvetica','I',9);
        
        $this->fpdf->Cell(60,5,'Col. 1',1,0,'C');
        $this->fpdf->Cell(20,5,'Col. 2',1,0,'C');
        $this->fpdf->Cell(20,5,'Col. 3',1,0,'C');
        $this->fpdf->Cell(20,5,'Col. 4',1,0,'C');
        $this->fpdf->Cell(20,5,'Col. 5',1,0,'C');
        $this->fpdf->Cell(50,5,'Col. 6',1,0,'C');
        $this->fpdf->Cell(30,5,'Col. 7',1,0,'C');
        $this->fpdf->Cell(40,5,'Col. 8',1,1,'C');
        
        $this->fpdf->SetFont('Helvetica','',7);
        
        $this->fpdf->Cell(60,4,'','LtRb',0,'L');
        $this->fpdf->Cell(20,4,'','LtRb',0,'C');
        $this->fpdf->Cell(20,4,'','LtRb',0,'C');
        $this->fpdf->Cell(20,4,'','LtRb',0,'C');
        $this->fpdf->Cell(20,4,'','LtRb',0,'C');
        $this->fpdf->Cell(25,4,'Morbidity','LtRb',0,'C');
        $this->fpdf->Cell(25,4,'Annual Parasite','LtRb',0,'C');
        $this->fpdf->Cell(30,4,'','LtRb',0,'C');
        $this->fpdf->Cell(40,4,'','LtRb',1,'C');
        
        $this->fpdf->Cell(60,4,'','LtRb',0,'L');
        $this->fpdf->Cell(20,4,'','LtRb',0,'C');
        $this->fpdf->Cell(20,4,'','LtRb',0,'C');
        $this->fpdf->Cell(20,4,'','LtRb',0,'C');
        $this->fpdf->Cell(20,4,'','LtRb',0,'C');
        $this->fpdf->Cell(25,4,'Rate','LtRb',0,'C');
        $this->fpdf->Cell(25,4,'Incidents','LtRb',0,'C');
        $this->fpdf->Cell(30,4,'','LtRb',0,'C');
        $this->fpdf->Cell(40,4,'','LtRb',1,'C');
        
         $this->fpdf->SetFont('Helvetica','',9);
        
        $this->fpdf->Cell(60,8,'Malaria Case?','LtRb',0,'L');
        $this->fpdf->Cell(20,8,'','LtRb',0,'C');
        $this->fpdf->Cell(20,8,'','LtRb',0,'C');
        $this->fpdf->Cell(20,8,'','LtRb',0,'C');
        $this->fpdf->Cell(20,8,'','LtRb',0,'C');
        $this->fpdf->Cell(25,8,'','LTRb',0,'C');
        $this->fpdf->Cell(25,8,'','LTRb',0,'C');
        $this->fpdf->Cell(30,8,'','LtRb',0,'C');
        $this->fpdf->Cell(40,8,'','LtRb',1,'C');
        
        $this->fpdf->Cell(60,7,'   ? 5 yo ?',1,0,'L');
        $this->fpdf->Cell(20,7,'',1,0,'C');
        $this->fpdf->Cell(20,7,'',1,0,'C');
        $this->fpdf->Cell(20,7,'',1,0,'C');
        $this->fpdf->Cell(20,7,'',1,0,'C');
        $this->fpdf->Cell(25,7,'',1,0,'C');
        $this->fpdf->Cell(25,7,'',1,0,'C');
        $this->fpdf->Cell(30,7,'','LtRb',0,'C');
        $this->fpdf->Cell(40,7,'','LtRb',1,'C');
        
        $this->fpdf->Cell(60,7,'   ? >=5 yo ?',1,0,'L');
        $this->fpdf->Cell(20,7,'',1,0,'C');
        $this->fpdf->Cell(20,7,'',1,0,'C');
        $this->fpdf->Cell(20,7,'',1,0,'C');
        $this->fpdf->Cell(20,7,'',1,0,'C');
        $this->fpdf->Cell(25,7,'',1,0,'C');
        $this->fpdf->Cell(25,7,'',1,0,'C');
        $this->fpdf->Cell(30,7,'','LtRb',0,'C');
        $this->fpdf->Cell(40,7,'','LtRb',1,'C');
        
        $this->fpdf->Cell(60,7,'Confirmed malaria Case?',1,0,'L');
        $this->fpdf->Cell(20,7,'',1,0,'C');
        $this->fpdf->Cell(20,7,'',1,0,'C');
        $this->fpdf->Cell(20,7,'',1,0,'C');
        $this->fpdf->Cell(20,7,'',1,0,'C');
        $this->fpdf->Cell(25,7,'',1,0,'C');
        $this->fpdf->Cell(25,7,'',1,0,'C');
        $this->fpdf->Cell(30,7,'','LtRb',0,'C');
        $this->fpdf->Cell(40,7,'','LtRb',1,'C');
        
        $this->fpdf->Cell(60,7,'  By Species','LTRb',0,'L');
        $this->fpdf->Cell(20,7,'','LTRb',0,'C');
        $this->fpdf->Cell(20,7,'','LTRb',0,'C');
        $this->fpdf->Cell(20,7,'','LTRb',0,'C');
        $this->fpdf->Cell(20,7,'','LTRb',0,'C');
        $this->fpdf->Cell(25,7,'','LTRb',0,'C');
        $this->fpdf->Cell(25,7,'','LTRb',0,'C');
        $this->fpdf->Cell(30,7,'','LtRb',0,'C');
        $this->fpdf->Cell(40,7,'','LtRb',1,'C');
        
        $this->fpdf->Cell(60,7,'    ? P.falciparum ?','LtRB',0,'L');
        $this->fpdf->Cell(20,7,'','LtRB',0,'C');
        $this->fpdf->Cell(20,7,'','LtRB',0,'C');
        $this->fpdf->Cell(20,7,'','LtRB',0,'C');
        $this->fpdf->Cell(20,7,'','LtRB',0,'C');
        $this->fpdf->Cell(25,7,'','LtRB',0,'C');
        $this->fpdf->Cell(25,7,'','LtRB',0,'C');
        $this->fpdf->Cell(30,7,'','LtRb',0,'C');
        $this->fpdf->Cell(40,7,'','LtRb',1,'C');
        
        $this->fpdf->Cell(60,7,'    ? P.vivax ?',1,0,'L');
        $this->fpdf->Cell(20,7,'',1,0,'C');
        $this->fpdf->Cell(20,7,'',1,0,'C');
        $this->fpdf->Cell(20,7,'',1,0,'C');
        $this->fpdf->Cell(20,7,'',1,0,'C');
        $this->fpdf->Cell(25,7,'',1,0,'C');
        $this->fpdf->Cell(25,7,'',1,0,'C');
        $this->fpdf->Cell(30,7,'','LtRb',0,'C');
        $this->fpdf->Cell(40,7,'','LtRb',1,'C');
        
        $this->fpdf->Cell(60,7,'    ? P.ovale ?',1,0,'L');
        $this->fpdf->Cell(20,7,'',1,0,'C');
        $this->fpdf->Cell(20,7,'',1,0,'C');
        $this->fpdf->Cell(20,7,'',1,0,'C');
        $this->fpdf->Cell(20,7,'',1,0,'C');
        $this->fpdf->Cell(25,7,'',1,0,'C');
        $this->fpdf->Cell(25,7,'',1,0,'C');
        $this->fpdf->Cell(30,7,'','LtRb',0,'C');
        $this->fpdf->Cell(40,7,'','LtRb',1,'C');
        
        $this->fpdf->Cell(60,7,'  By Method',1,0,'L');
        $this->fpdf->Cell(20,7,'',1,0,'C');
        $this->fpdf->Cell(20,7,'',1,0,'C');
        $this->fpdf->Cell(20,7,'',1,0,'C');
        $this->fpdf->Cell(20,7,'',1,0,'C');
        $this->fpdf->Cell(25,7,'',1,0,'C');
        $this->fpdf->Cell(25,7,'',1,0,'C');
        $this->fpdf->Cell(30,7,'','LtRb',0,'C');
        $this->fpdf->Cell(40,7,'','LtRb',1,'C');
        
        $this->fpdf->Cell(60,7,'    ? Slide ?',1,0,'L');
        $this->fpdf->Cell(20,7,'',1,0,'C');
        $this->fpdf->Cell(20,7,'',1,0,'C');
        $this->fpdf->Cell(20,7,'',1,0,'C');
        $this->fpdf->Cell(20,7,'',1,0,'C');
        $this->fpdf->Cell(25,7,'',1,0,'C');
        $this->fpdf->Cell(25,7,'',1,0,'C');
        $this->fpdf->Cell(30,7,'','LtRb',0,'C');
        $this->fpdf->Cell(40,7,'','LtRb',1,'C');
        
        $this->fpdf->Cell(60,7,'    ? RDT ?',1,0,'L');
        $this->fpdf->Cell(20,7,'',1,0,'C');
        $this->fpdf->Cell(20,7,'',1,0,'C');
        $this->fpdf->Cell(20,7,'',1,0,'C');
        $this->fpdf->Cell(20,7,'',1,0,'C');
        $this->fpdf->Cell(25,7,'',1,0,'C');
        $this->fpdf->Cell(25,7,'',1,0,'C');
        $this->fpdf->Cell(30,7,'','LtRb',0,'C');
        $this->fpdf->Cell(40,7,'','LtRb',1,'C');
        
        $this->fpdf->Cell(60,7,'Infant given anti-Measles?',1,0,'L');
        $this->fpdf->Cell(20,7,'',1,0,'C');
        $this->fpdf->Cell(20,7,'',1,0,'C');
        $this->fpdf->Cell(20,7,'',1,0,'C');
        $this->fpdf->Cell(20,7,'',1,0,'C');
        $this->fpdf->Cell(25,7,'',1,0,'C');
        $this->fpdf->Cell(25,7,'',1,0,'C');
        $this->fpdf->Cell(30,7,'','LtRb',0,'C');
        $this->fpdf->Cell(40,7,'','LtRb',1,'C');
        
        $this->fpdf->Cell(60,7,'Households at risk?',1,0,'L');
        $this->fpdf->Cell(20,7,'',1,0,'C');
        $this->fpdf->Cell(20,7,'',1,0,'C');
        $this->fpdf->Cell(20,7,'',1,0,'C');
        $this->fpdf->Cell(20,7,'',1,0,'C');
        $this->fpdf->Cell(25,7,'',1,0,'C');
        $this->fpdf->Cell(25,7,'',1,0,'C');
        $this->fpdf->Cell(30,7,'','LtRb',0,'C');
        $this->fpdf->Cell(40,7,'','LtRb',1,'C');
        
        $this->fpdf->Cell(60,7,'Households given ITN?',1,0,'L');
        $this->fpdf->Cell(20,7,'',1,0,'C');
        $this->fpdf->Cell(20,7,'',1,0,'C');
        $this->fpdf->Cell(20,7,'',1,0,'C');
        $this->fpdf->Cell(20,7,'',1,0,'C');
        $this->fpdf->Cell(25,7,'',1,0,'C');
        $this->fpdf->Cell(25,7,'',1,0,'C');
        $this->fpdf->Cell(30,7,'','LtRb',0,'C');
        $this->fpdf->Cell(40,7,'','LtRb',1,'C');
        
        $this->fpdf->SetFont('Helvetica','',7);
        
        $this->fpdf->Cell(60,4,'','LtRb',0,'L');
        $this->fpdf->Cell(20,4,'','LtRb',0,'C');
        $this->fpdf->Cell(20,4,'','LtRb',0,'C');
        $this->fpdf->Cell(20,4,'','LtRb',0,'C');
        $this->fpdf->Cell(20,4,'','LtRb',0,'C');
        $this->fpdf->Cell(25,4,'Mortality','LtRb',0,'C');
        $this->fpdf->Cell(25,4,'Case Fatality','LtRb',0,'C');
        $this->fpdf->Cell(30,4,'','LtRb',0,'C');
        $this->fpdf->Cell(40,4,'','LtRb',1,'C');
        
        $this->fpdf->Cell(60,4,'','LtRb',0,'L');
        $this->fpdf->Cell(20,4,'','LtRb',0,'C');
        $this->fpdf->Cell(20,4,'','LtRb',0,'C');
        $this->fpdf->Cell(20,4,'','LtRb',0,'C');
        $this->fpdf->Cell(20,4,'','LtRb',0,'C');
        $this->fpdf->Cell(25,4,'Rate','LtRb',0,'C');
        $this->fpdf->Cell(25,4,'Ratio','LtRb',0,'C');
        $this->fpdf->Cell(30,4,'','LtRb',0,'C');
        $this->fpdf->Cell(40,4,'','LtRb',1,'C');
        
         $this->fpdf->SetFont('Helvetica','',9);
        
        $this->fpdf->Cell(60,8,'Malaria Death','LtRb',0,'L');
        $this->fpdf->Cell(20,8,'','LtRb',0,'C');
        $this->fpdf->Cell(20,8,'','LtRb',0,'C');
        $this->fpdf->Cell(20,8,'','LtRb',0,'C');
        $this->fpdf->Cell(20,8,'','LtRb',0,'C');
        $this->fpdf->Cell(25,8,'','LTRb',0,'C');
        $this->fpdf->Cell(25,8,'','LTRb',0,'C');
        $this->fpdf->Cell(30,8,'','LtRb',0,'C');
        $this->fpdf->Cell(40,8,'','LtRb',1,'C');
        
        $this->fpdf->SetFont('Helvetica','I',7);
        $this->fpdf->Cell(30,4,'Denominator', 'LTrb',0,'L');
        $this->fpdf->Cell(100,4,'? Morbidity Rate = TP/Annual Parasite Incidence = Endemic Pop    ? >5 & < 5 yo Population    ?Malaria cases seen,','lTrb',0,'L');
        $this->fpdf->Cell(130,4,'','lTRb',1,'C');
        
        $this->fpdf->SetFont('Helvetica','I',7);
        $this->fpdf->Cell(30,4,'', 'LtrB',0,'L');
        $this->fpdf->Cell(100,4,'? Total Confirmed Malaria Case    ?Household at risk   ?Mortality Rate = TP/Case Fatality Ratio = Total Malaria Cases','ltrB',0,'L');
        $this->fpdf->Cell(130,4,'','ltRB',1,'C');
                                                                               
        $this->footer();
        $this->q1_pagenum();
        
        //$this->fpdf->Output();
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
    
    public function rhu_a2()
    {
        $action = $this->input->post('btnAction');
        $year = $this->input->post('fhsi_year');
        
        if($year=='') 
            {$year = date('Y');} 
        
        $this->rhu_a2_morbidity_1($year);
        $this->rhu_a2_morbidity_2($year);
        $this->rhu_a2_morbidity_3($year);
        $this->rhu_a2_morbidity_4($year);
        $this->rhu_a2_morbidity_5($year);  
        
        if($action=="generate_rhu_a2_dl")  
            {$this->fpdf->Output('RHU_Annual2_'.$year.'.pdf','D');}
        else
            {$this->fpdf->Output('RHU_Annual2_'.$year.'.pdf','I');}
    }
    
    function rhu_a2_morbidity_1($year)
    {       
        $default_province = $this->settings_m->get_default_province();
        $default_city = $this->settings_m->get_default_city();
        
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
        $this->fpdf->Cell(50,5,'MORBIDITY DISEASES REPORT',0,1,'C');
        $this->fpdf->SetX(115);
        $this->fpdf->SetFont('Helvetica','',9);
        $this->fpdf->Cell(50,5,'for submission to PHO',0,1,'C');
        
        $this->fpdf->SetFillColor(247,247,247);
        
        $this->fpdf->SetY(40);
        $this->fpdf->SetFont('Helvetica','B',12);
        $this->fpdf->Cell(270,10,'AGE GROUP',1,1,'C',true);
        
        $this->fpdf->SetFont('Helvetica','B',7);
        $this->fpdf->Cell(38,7,'DISEASES','LTRb',0,'C');
        $this->fpdf->Cell(8,7,'ICD 10',1,0,'C'); 
        $this->fpdf->Cell(14,7,'Under 1',1,0,'C');
        $this->fpdf->Cell(14,7,'1-4',1,0,'C');
        $this->fpdf->Cell(14,7,'5-9',1,0,'C');
        $this->fpdf->Cell(14,7,'10-12',1,0,'C');
        $this->fpdf->Cell(14,7,'15-19',1,0,'C');
        $this->fpdf->Cell(14,7,'20-24',1,0,'C');
        $this->fpdf->Cell(14,7,'25-29',1,0,'C');
        $this->fpdf->Cell(14,7,'30-34',1,0,'C');
        $this->fpdf->Cell(14,7,'35-39',1,0,'C');
        $this->fpdf->Cell(14,7,'40-44',1,0,'C');
        $this->fpdf->Cell(14,7,'45-49',1,0,'C');
        $this->fpdf->Cell(14,7,'50-54',1,0,'C');
        $this->fpdf->Cell(14,7,'55-59',1,0,'C');
        $this->fpdf->Cell(14,7,'60-64',1,0,'C');
        $this->fpdf->Cell(14,7,'65 & Above',1,0,'C');
        $this->fpdf->Cell(14,7,'Total',1,1,'C');
        
        $this->fpdf->Cell(38,7,'','LtRB',0,'C');
        $this->fpdf->Cell(8,7,'Code',1,0,'C');
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
        $this->fpdf->Cell(7,7,'F',1,1,'C');
        
        $this->fpdf->SetFont('Helvetica','',7);
        
        $this->fpdf->Cell(38,7,'URTI',1,0,'L');
        $this->fpdf->Cell(8,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,1,'C');
        
        $this->fpdf->Cell(38,7,'ARI',1,0,'L');
        $this->fpdf->Cell(8,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,1,'C');
        
        $this->fpdf->Cell(38,7,'URI',1,0,'L');
        $this->fpdf->Cell(8,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,1,'C');
        
        $this->fpdf->Cell(38,7,'Asth. Bronchitis',1,0,'L');
        $this->fpdf->Cell(8,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,1,'C');
        
        $this->fpdf->Cell(38,7,'Abraision',1,0,'L');
        $this->fpdf->Cell(8,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,1,'C');
        
        $this->fpdf->Cell(38,7,'Allergic Dermatitis',1,0,'L');
        $this->fpdf->Cell(8,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,1,'C');
        
        $this->fpdf->Cell(38,7,'Cellulitis',1,0,'L');
        $this->fpdf->Cell(8,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,1,'C');
        
        $this->fpdf->Cell(38,7,'Arthritis',1,0,'L');
        $this->fpdf->Cell(8,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,1,'C');
        
        $this->fpdf->Cell(38,7,'Abcess',1,0,'L');
        $this->fpdf->Cell(8,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,1,'C');
        
        $this->fpdf->Cell(38,7,'Gastritis',1,0,'L');
        $this->fpdf->Cell(8,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,1,'C');
        
        $this->fpdf->Cell(38,7,'Kocks',1,0,'L');
        $this->fpdf->Cell(8,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,1,'C');
        
        $this->fpdf->Cell(38,7,'Burn',1,0,'L');
        $this->fpdf->Cell(8,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,1,'C');
        
        $this->fpdf->Cell(38,7,'Dental Carries',1,0,'L');
        $this->fpdf->Cell(8,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,1,'C');
        
        $this->fpdf->Cell(38,7,'Infected Wound',1,0,'L');
        $this->fpdf->Cell(8,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,1,'C');
        
        $this->fpdf->Cell(38,7,'Polynueritis',1,0,'L');
        $this->fpdf->Cell(8,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,1,'C');
        
        $this->fpdf->Cell(38,7,'Nueritis',1,0,'L');
        $this->fpdf->Cell(8,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'1000',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,1,'C');
        
        $this->a2_header_icon();
        //$this->a2_pagenum();
        $this->footer();
            
        //$this->fpdf->Output();
    }
    
    function rhu_a2_morbidity_2($year)
    {
        $default_province = $this->settings_m->get_default_province();
        $default_city = $this->settings_m->get_default_city();
        
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
        $this->fpdf->Cell(50,5,'MORBIDITY DISEASES REPORT',0,1,'C');
        $this->fpdf->SetX(115);
        $this->fpdf->SetFont('Helvetica','',9);
        $this->fpdf->Cell(50,5,'for submission to PHO',0,1,'C');
        
        $this->fpdf->SetFillColor(247,247,247);
        
        $this->fpdf->SetY(40);
        $this->fpdf->SetFont('Helvetica','B',12);
        $this->fpdf->Cell(270,10,'AGE GROUP',1,1,'C',true);
        
        $this->fpdf->SetFont('Helvetica','B',7);
        $this->fpdf->Cell(38,7,'DISEASES','LTRb',0,'C');
        $this->fpdf->Cell(8,7,'ICD 10',1,0,'C');
        $this->fpdf->Cell(14,7,'Under 1',1,0,'C');
        $this->fpdf->Cell(14,7,'1-4',1,0,'C');
        $this->fpdf->Cell(14,7,'5-9',1,0,'C');
        $this->fpdf->Cell(14,7,'10-12',1,0,'C');
        $this->fpdf->Cell(14,7,'15-19',1,0,'C');
        $this->fpdf->Cell(14,7,'20-24',1,0,'C');
        $this->fpdf->Cell(14,7,'25-29',1,0,'C');
        $this->fpdf->Cell(14,7,'30-34',1,0,'C');
        $this->fpdf->Cell(14,7,'35-39',1,0,'C');
        $this->fpdf->Cell(14,7,'40-44',1,0,'C');
        $this->fpdf->Cell(14,7,'45-49',1,0,'C');
        $this->fpdf->Cell(14,7,'50-54',1,0,'C');
        $this->fpdf->Cell(14,7,'55-59',1,0,'C');
        $this->fpdf->Cell(14,7,'60-64',1,0,'C');
        $this->fpdf->Cell(14,7,'65 & Above',1,0,'C');
        $this->fpdf->Cell(14,7,'Total',1,1,'C');
        
        $this->fpdf->Cell(38,7,'','LtRB',0,'C');
        $this->fpdf->Cell(8,7,'Code',1,0,'C');
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
        $this->fpdf->Cell(7,7,'F',1,1,'C');
        
        $this->fpdf->SetFont('Helvetica','',8);
        
        $this->fpdf->Cell(38,7,'Muscle Pain',1,0,'L');
        $this->fpdf->Cell(8,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,1,'C');
        
        $this->fpdf->Cell(38,7,'Otitis Media',1,0,'L');
        $this->fpdf->Cell(8,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,1,'C');
        
        $this->fpdf->Cell(38,7,'Intestinal Pneumonia',1,0,'L');
        $this->fpdf->Cell(8,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,1,'C');
        
        $this->fpdf->Cell(38,7,'Contussion',1,0,'L');
        $this->fpdf->Cell(8,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,1,'C');
        
        $this->fpdf->Cell(38,7,'Parasitism',1,0,'L');
        $this->fpdf->Cell(8,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,1,'C');
        
        $this->fpdf->Cell(38,7,'Typhoid Fever',1,0,'L');
        $this->fpdf->Cell(8,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,1,'C');
        
        $this->fpdf->Cell(38,7,'Rhinitis',1,0,'L');
        $this->fpdf->Cell(8,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,1,'C');
        
        $this->fpdf->Cell(38,7,'Conjunctivitis',1,0,'L');
        $this->fpdf->Cell(8,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,1,'C');
        
        $this->fpdf->Cell(38,7,'Sinusitis',1,0,'L');
        $this->fpdf->Cell(8,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,1,'C');
        
        $this->fpdf->Cell(38,7,'Erysepelas',1,0,'L');
        $this->fpdf->Cell(8,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,1,'C');
        
        $this->fpdf->Cell(38,7,'Infected Dermatitis',1,0,'L');
        $this->fpdf->Cell(8,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,1,'C');
        
        $this->fpdf->Cell(38,7,'Constipation',1,0,'L');
        $this->fpdf->Cell(8,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,1,'C');
        
        $this->fpdf->Cell(38,7,'Cirvicitis',1,0,'L');
        $this->fpdf->Cell(8,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,1,'C');
        
        $this->fpdf->Cell(38,7,'Punctured Wound',1,0,'L');
        $this->fpdf->Cell(8,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,1,'C');
        
        $this->fpdf->Cell(38,7,'Insomia',1,0,'L');
        $this->fpdf->Cell(8,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,1,'C');
        
        $this->fpdf->Cell(38,7,'Bells Paisy',1,0,'L');
        $this->fpdf->Cell(8,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'600',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,1,'C');
        
        $this->a2_header_icon();
        //$this->a2_pagenum();
        $this->footer();
            
        //$this->fpdf->Output();
    }
    
    function rhu_a2_morbidity_3($year)
    {
        $default_province = $this->settings_m->get_default_province();
        $default_city = $this->settings_m->get_default_city();
        
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
        $this->fpdf->Cell(50,5,'MORBIDITY DISEASES REPORT',0,1,'C');
        $this->fpdf->SetX(115);
        $this->fpdf->SetFont('Helvetica','',9);
        $this->fpdf->Cell(50,5,'for submission to PHO',0,1,'C');
        
        $this->fpdf->SetFillColor(247,247,247);
        
        $this->fpdf->SetY(40);
        $this->fpdf->SetFont('Helvetica','B',12);
        $this->fpdf->Cell(270,10,'AGE GROUP',1,1,'C',true);
        
        $this->fpdf->SetFont('Helvetica','B',7);
        $this->fpdf->Cell(38,7,'DISEASES','LTRb',0,'C');
        $this->fpdf->Cell(8,7,'ICD 10',1,0,'C');
        $this->fpdf->Cell(14,7,'Under 1',1,0,'C');
        $this->fpdf->Cell(14,7,'1-4',1,0,'C');
        $this->fpdf->Cell(14,7,'5-9',1,0,'C');
        $this->fpdf->Cell(14,7,'10-12',1,0,'C');
        $this->fpdf->Cell(14,7,'15-19',1,0,'C');
        $this->fpdf->Cell(14,7,'20-24',1,0,'C');
        $this->fpdf->Cell(14,7,'25-29',1,0,'C');
        $this->fpdf->Cell(14,7,'30-34',1,0,'C');
        $this->fpdf->Cell(14,7,'35-39',1,0,'C');
        $this->fpdf->Cell(14,7,'40-44',1,0,'C');
        $this->fpdf->Cell(14,7,'45-49',1,0,'C');
        $this->fpdf->Cell(14,7,'50-54',1,0,'C');
        $this->fpdf->Cell(14,7,'55-59',1,0,'C');
        $this->fpdf->Cell(14,7,'60-64',1,0,'C');
        $this->fpdf->Cell(14,7,'65 & Above',1,0,'C');
        $this->fpdf->Cell(14,7,'Total',1,1,'C');
        
        $this->fpdf->Cell(38,7,'','LtRB',0,'C');
        $this->fpdf->Cell(8,7,'Code',1,0,'C');
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
        $this->fpdf->Cell(7,7,'F',1,1,'C');
        
        $this->fpdf->SetFont('Helvetica','',8);
        
        $this->fpdf->Cell(38,7,'Laryngitis',1,0,'L');
        $this->fpdf->Cell(8,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,1,'C');
        
        $this->fpdf->Cell(38,7,'Pneumonia',1,0,'L');
        $this->fpdf->Cell(8,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,1,'C');
        
        $this->fpdf->Cell(38,7,'Allergy',1,0,'L');
        $this->fpdf->Cell(8,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,1,'C');
        
        $this->fpdf->Cell(38,7,'Eye Defect',1,0,'L');
        $this->fpdf->Cell(8,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,1,'C');
        
        $this->fpdf->Cell(38,7,'Skin Disease',1,0,'L');
        $this->fpdf->Cell(8,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,1,'C');
        
        $this->fpdf->Cell(38,7,'Amoebiasis',1,0,'L');
        $this->fpdf->Cell(8,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,1,'C');
        
        $this->fpdf->Cell(38,7,'Pharyngitis',1,0,'L');
        $this->fpdf->Cell(8,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,1,'C');
        
        $this->fpdf->Cell(38,7,'Colds',1,0,'L');
        $this->fpdf->Cell(8,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,1,'C');
        
        $this->fpdf->Cell(38,7,'Myalgia',1,0,'L');
        $this->fpdf->Cell(8,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,1,'C');
        
        $this->fpdf->Cell(38,7,'Dengue',1,0,'L');
        $this->fpdf->Cell(8,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,1,'C');
        
        $this->fpdf->Cell(38,7,'Diabetes',1,0,'L');
        $this->fpdf->Cell(8,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,1,'C');
        
        $this->fpdf->Cell(38,7,'Fatigue',1,0,'L');
        $this->fpdf->Cell(8,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,1,'C');
        
        $this->fpdf->Cell(38,7,'Peptic Ulcer',1,0,'L');
        $this->fpdf->Cell(8,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,1,'C');
        
        $this->fpdf->Cell(38,7,'Bronchitis',1,0,'L');
        $this->fpdf->Cell(8,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,1,'C');
        
        $this->fpdf->Cell(38,7,'Lacerated Wound',1,0,'L');
        $this->fpdf->Cell(8,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,1,'C');
        
        $this->fpdf->Cell(38,7,'Hematoma',1,0,'L');
        $this->fpdf->Cell(8,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,1,'C');
        
        $this->a2_header_icon();
        //$this->a2_pagenum();
        $this->footer();
            
        //$this->fpdf->Output();
    }
    
    function rhu_a2_morbidity_4($year)
    {
        $default_province = $this->settings_m->get_default_province();
        $default_city = $this->settings_m->get_default_city();
        
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
        $this->fpdf->Cell(50,5,'MORBIDITY DISEASES REPORT',0,1,'C');
        $this->fpdf->SetX(115);
        $this->fpdf->SetFont('Helvetica','',9);
        $this->fpdf->Cell(50,5,'for submission to PHO',0,1,'C');
        
        $this->fpdf->SetFillColor(247,247,247);
        
        $this->fpdf->SetY(40);
        $this->fpdf->SetFont('Helvetica','B',12);
        $this->fpdf->Cell(270,10,'AGE GROUP',1,1,'C',true);
        
        $this->fpdf->SetFont('Helvetica','B',7);
        $this->fpdf->Cell(38,7,'DISEASES','LTRb',0,'C');
        $this->fpdf->Cell(8,7,'ICD 10',1,0,'C');
        $this->fpdf->Cell(14,7,'Under 1',1,0,'C');
        $this->fpdf->Cell(14,7,'1-4',1,0,'C');
        $this->fpdf->Cell(14,7,'5-9',1,0,'C');
        $this->fpdf->Cell(14,7,'10-12',1,0,'C');
        $this->fpdf->Cell(14,7,'15-19',1,0,'C');
        $this->fpdf->Cell(14,7,'20-24',1,0,'C');
        $this->fpdf->Cell(14,7,'25-29',1,0,'C');
        $this->fpdf->Cell(14,7,'30-34',1,0,'C');
        $this->fpdf->Cell(14,7,'35-39',1,0,'C');
        $this->fpdf->Cell(14,7,'40-44',1,0,'C');
        $this->fpdf->Cell(14,7,'45-49',1,0,'C');
        $this->fpdf->Cell(14,7,'50-54',1,0,'C');
        $this->fpdf->Cell(14,7,'55-59',1,0,'C');
        $this->fpdf->Cell(14,7,'60-64',1,0,'C');
        $this->fpdf->Cell(14,7,'65 & Above',1,0,'C');
        $this->fpdf->Cell(14,7,'Total',1,1,'C');
        
        $this->fpdf->Cell(38,7,'','LtRB',0,'C');
        $this->fpdf->Cell(8,7,'Code',1,0,'C');
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
        $this->fpdf->Cell(7,7,'F',1,1,'C');
        
        $this->fpdf->SetFont('Helvetica','',8);
        
        $this->fpdf->Cell(38,7,'Pyelonephritis',1,0,'L');
        $this->fpdf->Cell(8,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,1,'C');
        
        $this->fpdf->Cell(38,7,'Lymphadenopathy',1,0,'L');
        $this->fpdf->Cell(8,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,1,'C');
        
        $this->fpdf->Cell(38,7,'Tonsilitis',1,0,'L');
        $this->fpdf->Cell(8,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,1,'C');
        
        $this->fpdf->Cell(38,7,'Severe Anemia',1,0,'L');
        $this->fpdf->Cell(8,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,1,'C');
        
        $this->fpdf->Cell(38,7,'Mastitis',1,0,'L');
        $this->fpdf->Cell(8,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,1,'C');
        
        $this->fpdf->Cell(38,7,'Primary Complex',1,0,'L');
        $this->fpdf->Cell(8,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,1,'C');
        
        $this->fpdf->Cell(38,7,'Chicken Pox',1,0,'L');
        $this->fpdf->Cell(8,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,1,'C');
        
        $this->fpdf->Cell(38,7,'Mumps',1,0,'L');
        $this->fpdf->Cell(8,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,1,'C');
        
        $this->fpdf->Cell(38,7,'Trush',1,0,'L');
        $this->fpdf->Cell(8,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,1,'C');
        
        $this->fpdf->Cell(38,7,'Hypertension',1,0,'L');
        $this->fpdf->Cell(8,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,1,'C');
        
        $this->fpdf->Cell(38,7,'Herpes Zoster',1,0,'L');
        $this->fpdf->Cell(8,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,1,'C');
        
        $this->fpdf->Cell(38,7,'Giomerelonephritis',1,0,'L');
        $this->fpdf->Cell(8,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,1,'C');
        
        $this->fpdf->Cell(38,7,'Allergic Bronchitis',1,0,'L');
        $this->fpdf->Cell(8,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,1,'C');
        
        $this->fpdf->Cell(38,7,'Viral Rashes',1,0,'L');
        $this->fpdf->Cell(8,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,1,'C');
        
        $this->fpdf->Cell(38,7,'Indigestion',1,0,'L');
        $this->fpdf->Cell(8,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,1,'C');
        
        $this->fpdf->Cell(38,7,'Influenza',1,0,'L');
        $this->fpdf->Cell(8,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,1,'C');
        
        $this->a2_header_icon();
        //$this->a2_pagenum();
        $this->footer();
            
        //$this->fpdf->Output();
    }
    
    function rhu_a2_morbidity_5($year)
    {
        $default_province = $this->settings_m->get_default_province();
        $default_city = $this->settings_m->get_default_city();
        
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
        $this->fpdf->Cell(50,5,'MORBIDITY DISEASES REPORT',0,1,'C');
        $this->fpdf->SetX(115);
        $this->fpdf->SetFont('Helvetica','',9);
        $this->fpdf->Cell(50,5,'for submission to PHO',0,1,'C');
        
        $this->fpdf->SetFillColor(247,247,247);
        
        $this->fpdf->SetY(40);
        $this->fpdf->SetFont('Helvetica','B',12);
        $this->fpdf->Cell(270,10,'AGE GROUP',1,1,'C',true);
        
        $this->fpdf->SetFont('Helvetica','B',7);
        $this->fpdf->Cell(38,7,'DISEASES','LTRb',0,'C');
        $this->fpdf->Cell(8,7,'ICD 10',1,0,'C');
        $this->fpdf->Cell(14,7,'Under 1',1,0,'C');
        $this->fpdf->Cell(14,7,'1-4',1,0,'C');
        $this->fpdf->Cell(14,7,'5-9',1,0,'C');
        $this->fpdf->Cell(14,7,'10-12',1,0,'C');
        $this->fpdf->Cell(14,7,'15-19',1,0,'C');
        $this->fpdf->Cell(14,7,'20-24',1,0,'C');
        $this->fpdf->Cell(14,7,'25-29',1,0,'C');
        $this->fpdf->Cell(14,7,'30-34',1,0,'C');
        $this->fpdf->Cell(14,7,'35-39',1,0,'C');
        $this->fpdf->Cell(14,7,'40-44',1,0,'C');
        $this->fpdf->Cell(14,7,'45-49',1,0,'C');
        $this->fpdf->Cell(14,7,'50-54',1,0,'C');
        $this->fpdf->Cell(14,7,'55-59',1,0,'C');
        $this->fpdf->Cell(14,7,'60-64',1,0,'C');
        $this->fpdf->Cell(14,7,'65 & Above',1,0,'C');
        $this->fpdf->Cell(14,7,'Total',1,1,'C');   
        
        $this->fpdf->Cell(38,7,'','LtRB',0,'C');
        $this->fpdf->Cell(8,7,'CODE',1,0,'C');
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
        $this->fpdf->Cell(7,7,'F',1,1,'C');
        
        $this->fpdf->SetFont('Helvetica','',8);
        
        $this->fpdf->Cell(38,7,'Acute Gastro Enteritis',1,0,'L');
        $this->fpdf->Cell(8,7,'',1,0,'C');  
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,1,'C');
        
        $this->fpdf->Cell(38,7,'Gout',1,0,'L');
        $this->fpdf->Cell(8,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,1,'C');
        
        $this->fpdf->Cell(38,7,'Parental Diarrhea',1,0,'L');
        $this->fpdf->Cell(8,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,1,'C');
        
        $this->fpdf->Cell(38,7,'Broncho Pneumonia',1,0,'L');
        $this->fpdf->Cell(8,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,1,'C');
        
        $this->fpdf->Cell(38,7,'Sprain',1,0,'L');
        $this->fpdf->Cell(8,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,1,'C');
        
        $this->fpdf->Cell(38,7,'',1,0,'L');
        $this->fpdf->Cell(8,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,1,'C');
        
        $this->fpdf->Cell(38,7,'',1,0,'L');
        $this->fpdf->Cell(8,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,1,'C');
        
        $this->fpdf->Cell(38,7,'',1,0,'L');
        $this->fpdf->Cell(8,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,1,'C');
        
        $this->fpdf->Cell(38,7,'',1,0,'L');
        $this->fpdf->Cell(8,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,1,'C');
        
        $this->fpdf->Cell(38,7,'',1,0,'L');
        $this->fpdf->Cell(8,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,1,'C');
        
        $this->fpdf->Cell(38,7,'',1,0,'L');
        $this->fpdf->Cell(8,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,1,'C');
        
        $this->fpdf->Cell(38,7,'',1,0,'L');
        $this->fpdf->Cell(8,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,1,'C');
        
        $this->fpdf->Cell(38,7,'',1,0,'L');
        $this->fpdf->Cell(8,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,1,'C');
        
        $this->fpdf->Cell(38,7,'',1,0,'L');
        $this->fpdf->Cell(8,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,1,'C');
        
        $this->fpdf->Cell(38,7,'',1,0,'L');
        $this->fpdf->Cell(8,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,1,'C');
        
        $this->fpdf->Cell(38,7,'',1,0,'L');
        $this->fpdf->Cell(8,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,0,'C');
        $this->fpdf->Cell(7,7,'',1,1,'C');
        
        $this->a2_header_icon();
        //$this->a2_pagenum();
        $this->footer();
            
        //$this->fpdf->Output();
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