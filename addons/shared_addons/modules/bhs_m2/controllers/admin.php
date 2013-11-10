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
        $this->load->model(array('clients/clients_m', 'clients/clients_region_m', 'clients/clients_province_m', 'clients/clients_city_m', 'clients/clients_barangay_m','consultations/consultations_m','settings/settings_m','bhs_m2/bhs_m2_m'));
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
    //$this->data->months = array ('1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5','6'=>'6','7'=>'7','8'=>'8','9'=>'9','10'=>'10','11'=>'11','12'=>'12');
    $this->data->months = array ('1'=>'January','2'=>'February','3'=>'March','4'=>'April','5'=>'May','6'=>'June','7'=>'July','8'=>'August','9'=>'September','10'=>'October','11'=>'November','12'=>'December');
    $this->data->quarters = array ('1'=>'1st Quarter','2'=>'2nd Quarter','3'=>'3rd Quarter','4'=>'4th Quarter');
    $this->data->years= $years;
		$this->data->tables = $tables;
		$this->data->folders = &$folder_ary;

		$this->template->title($this->module_details['name'])->build('admin/rpt_form', $this->data);   
	}
    
    public function show()
    {
        $this->bhs_m2();
    }
    
    public function bhs_m2()
    {
        $action = $this->input->post('btnAction');
        $month = $this->input->post('bhs_m2_month');
        $year = $this->input->post('bhs_m2_year');
        
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

        $default_city = $this->settings_m->get_default_city();
        $station_name = $this->settings_m->get_station_name();
        
        if(($station_name=="Main Health Center")&&($default_city!=null))
            {$bhs_bhc = $default_city.'-'.url_title($station_name);}
        else
            {$bhs_bhc = $station_name;}
        
        if($year=='') 
            {$year = date('Y');} 
        
        $this->bhs_m2_morbidity($month,$year);
        
        if($action=="generate_bhs_m2_dl")  
            {$this->fpdf->Output('BHS_M2_'.$bhs_bhc.'_'.$monthname.'_'.$year.'.pdf','D');}
        else
            {$this->fpdf->Output('BHS_M2_'.$bhs_bhc.'_'.$monthname.'_'.$year.'.pdf','I');}
    }
    
    function bhs_m2_morbidity($month,$year)
    {               
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
        $morbidity_cases_under1 = $this->bhs_m2_m->get_morbidity_cases_under1($month, $year);
        $morbidity_cases_1to4 = $this->bhs_m2_m->get_morbidity_cases_1to4($month, $year);
        $morbidity_cases_5to9 = $this->bhs_m2_m->get_morbidity_cases_5to9($month, $year);
        $morbidity_cases_10to14 = $this->bhs_m2_m->get_morbidity_cases_10to14($month, $year);
        $morbidity_cases_15to19 = $this->bhs_m2_m->get_morbidity_cases_15to19($month, $year);
        $morbidity_cases_20to24 = $this->bhs_m2_m->get_morbidity_cases_20to24($month, $year);
        $morbidity_cases_25to29 = $this->bhs_m2_m->get_morbidity_cases_25to29($month, $year);
        $morbidity_cases_30to34 = $this->bhs_m2_m->get_morbidity_cases_30to34($month, $year);
        $morbidity_cases_35to39 = $this->bhs_m2_m->get_morbidity_cases_35to39($month, $year);
        $morbidity_cases_40to44 = $this->bhs_m2_m->get_morbidity_cases_40to44($month, $year);
        $morbidity_cases_45to49 = $this->bhs_m2_m->get_morbidity_cases_45to49($month, $year);
        $morbidity_cases_50to54 = $this->bhs_m2_m->get_morbidity_cases_50to54($month, $year);
        $morbidity_cases_55to59 = $this->bhs_m2_m->get_morbidity_cases_55to59($month, $year);
        $morbidity_cases_60to64 = $this->bhs_m2_m->get_morbidity_cases_60to64($month, $year);
        $morbidity_cases_65up = $this->bhs_m2_m->get_morbidity_cases_65up($month, $year);
        $morbidity_cases_total = $this->bhs_m2_m->get_morbidity_cases_total($month, $year);
     
        $this->fpdf->AliasNbPages();
        $this->fpdf->AddPage('L','A4');
        $this->fpdf->SetAutoPageBreak(true, 10);
        $this->fpdf->SetLeftMargin(10);
        $this->m2_header($month,$year);
    
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
                  $this->m2_header_icon();
                  $this->footer();
                  
                  $i = 1;
                  $this->fpdf->AddPage('L','A4');
                  $this->m2_header($month,$year); 
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
        
        $this->m2_header_icon();
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
    
    function m2_header($month,$year)
    {
        $default_province = $this->settings_m->get_default_province();
        $default_city = $this->settings_m->get_default_city();
        $station_name = $this->settings_m->get_station_name();
        
        if(($station_name=="Main Health Center")&&($default_city!=null))
            {$bhs_bhc = $default_city.' - '.$station_name;}
        else
            {$bhs_bhc = $station_name;}
        
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
        $this->fpdf->SetX(110);
        $this->fpdf->SetFont('Helvetica','',9);
        $this->fpdf->SetTextColor(0,0,0);
        $this->fpdf->Cell(43,5,'FHSIS MONTHLY REPORT',0,0,'L');
        $this->fpdf->Cell(12,5,$monthname,'ltrB',0,'C');
        $this->fpdf->Cell(12,5,'Year',0,0,'C');
        $this->fpdf->Cell(12,5,$year,'ltrB',1,'C');
        $this->fpdf->SetX(110);
        $this->fpdf->Cell(29,5,'Name of BHS/BHC:',0,0,'L');
        $this->fpdf->Cell(50,5,$bhs_bhc,'ltrB',1,'C');
        $this->fpdf->SetX(110);
        $this->fpdf->Cell(25,5,'Catchment RHU:',0,0,'L');
        $this->fpdf->Cell(54,5,$default_city,'ltrB',1,'C');
        $this->fpdf->SetX(125);
        $this->fpdf->SetFont('Helvetica','B',9);
        $this->fpdf->Cell(50,5,'MORBIDITY DISEASES REPORT',0,1,'C');
        $this->fpdf->SetX(125);
        $this->fpdf->SetFont('Helvetica','',9);
        $this->fpdf->Cell(50,5,'for submission to RHU',0,1,'C');
        
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
        $this->fpdf->Cell(13,7,'65 & Above',0,0,'C');
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
    
    function m2_pagenum()
    {
        //Draw a line
        $this->fpdf->SetY(5);
        $this->fpdf->SetX(229);
        
        //Page footer
        $this->fpdf->SetTextColor(128);
        $this->fpdf->SetFont('Helvetica','',7);
        $this->fpdf->Cell(50,4,'FHSIS V 2013 - A Form (Page '.$this->fpdf->PageNo().' of {nb})',0,1,'R');
    } 
    
    function m2_header_icon()
    {
        $this->fpdf->SetTextColor(0,0,0);
        $this->fpdf->SetY(10);
        $this->fpdf->SetX(258);
        $this->fpdf->SetFont('Helvetica','',6);
        $this->fpdf->Cell(30,5,'FHSIS Version 2013',0,1,'L');
        $this->fpdf->SetX(258);
        $this->fpdf->SetFont('Helvetica','B',60);
        $this->fpdf->Cell(30,15,'M2',0,1,'C');
        $this->fpdf->SetX(258);
        $this->fpdf->SetFont('Helvetica','B',30);
        $this->fpdf->Cell(30,10,'BHS',0,1,'C');
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
        $model = $this->load->model('bhs_m2_m');
        echo print_r($this->bhs_m2_m->get_morbidity_cases_1to4(5,2012)); 
    }

} /* end admin class */
?>