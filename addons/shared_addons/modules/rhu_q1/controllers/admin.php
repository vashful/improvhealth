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
        $this->load->model(array('clients/clients_m', 'clients/clients_region_m', 'clients/clients_province_m', 'clients/clients_city_m', 'clients/clients_barangay_m','consultations/consultations_m','settings/settings_m','rhu_q1/rhu_q1_m'));
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
        $quarter = $this->input->post('q1_quarter');
        $year = $this->input->post('q1_year');
        $action = "generate_rhu_q1";
        $this->rhu_q1($action);        
    }
    
    public function show_pdf()
    {
        $quarter = $this->input->post('q1_quarter');
        $year = $this->input->post('q1_year');
        $action = "generate_rhu_q1_dl";
        $this->rhu_q1($action);        
    }
    
    public function preview()
    {
        $folders = glob($this->cache_path.'*', GLOB_ONLYDIR);        
        
        $default_province = $this->settings_m->get_default_province();
        $default_city = $this->settings_m->get_default_city();
        $quarter = $this->input->post('q1_quarter');
        $year = $this->input->post('q1_year');
        $ppoty = $this->input->post('q1_ppoty');
        
        $maternal = $this->rhu_q1_m->get_maternal_quarterreport($quarter, $year);
        $family_planning = $this->rhu_q1_m->get_familyplanning_quarterreport($quarter, $year);
        $dental = $this->rhu_q1_m->get_dental_quarterreport($quarter, $year);
        $childcare = $this->rhu_q1_m->get_childcare_quarterreport($quarter, $year);
        
        if($year=='') 
            {$year = date('Y');} 
        if($quarter=='') 
            {$quarter = 1;}
        
        if($quarter==1)
            {$this->data->quartername="1st Quarter";}
        elseif($quarter==2)
            {$this->data->quartername="2nd Quarter";}
        elseif($quarter==3)
            {$this->data->quartername="3rd Quarter";}
        elseif($quarter==4)
            {$this->data->quartername="4th Quarter";}
        
        //=========================== Maternal Care ==========================//
        $eligible_pop_maternal1 = number_format($ppoty * 0.035);
        $eligible_pop_maternal2 = number_format($ppoty * 0.030);
        
        if($maternal['total2']>0)
            {$this->data->maternal_per1 = number_format(($maternal['total2']/$eligible_pop_maternal1)*100);}
        else
            {$this->data->maternal_per1 = "0";}
        if($maternal['total2']>0)
            {$this->data->maternal_per2 = number_format(($maternal['total2']/$eligible_pop_maternal1)*100);}
        else
            {$this->data->maternal_per2 = "0";}
        if($maternal['total3']>0)
            {$this->data->maternal_per3 = number_format(($maternal['total3']/$eligible_pop_maternal1)*100);}
        else
            {$this->data->maternal_per3 = "0";}
        if($maternal['total4']>0)
            {$this->data->maternal_per4 = number_format(($maternal['total4']/$eligible_pop_maternal1)*100);}
        else
            {$this->data->maternal_per4 = "0";}
        if($maternal['total5']>0)
            {$this->data->maternal_per5 = number_format(($maternal['total5']/$eligible_pop_maternal1)*100);}
        else
            {$this->data->maternal_per5 = "0";}
        if($maternal['total6']>0)
            {$this->data->maternal_per6 = number_format(($maternal['total6']/$eligible_pop_maternal2)*100);}
        else
            {$this->data->maternal_per6 = "0";}
        if($maternal['total7']>0)
            {$this->data->maternal_per7 = number_format(($maternal['total7']/$eligible_pop_maternal2)*100);}
        else
            {$this->data->maternal_per7 = "0";}
        if($maternal['total8']>0)
            {$this->data->maternal_per8 = number_format(($maternal['total8']/$eligible_pop_maternal2)*100);}
        else
            {$this->data->maternal_per8 = "0";}
        if($maternal['total9']>0)
            {$this->data->maternal_per9 = number_format(($maternal['total9']/$eligible_pop_maternal2)*100);}
        else
            {$this->data->maternal_per9 = "0";}
            
        $this->data->eligible_pop_maternal1 = $eligible_pop_maternal1;
        $this->data->eligible_pop_maternal2 = $eligible_pop_maternal2;
            
        //========================== Family Planning =========================//
        
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
            {$this->data->fstr_cpr = number_format($fstr_cu_end/$ppoty*0.145*0.85,2);}
        else
            {$this->data->fstr_cpr = "0";}
        if($mstr_cu_end>0)
            {$this->data->mstr_cpr = number_format($mstr_cu_end/$ppoty*0.145*0.85,2);}
        else
            {$this->data->mstr_cpr = "0";}
        if($pills_cu_end>0)
            {$this->data->pills_cpr  = number_format($pills_cu_end/$ppoty*0.145*0.85,2);}
        else
            {$this->data->pills_cpr = "0";}
        if($iud_cu_end>0)
            {$this->data->iud_cpr  = number_format($iud_cu_end/$ppoty*0.145*0.85,2);}
        else
            {$this->data->iud_cpr = "0";}
        if($dmpa_cu_end>0)
            {$this->data->dmpa_cpr   = number_format($dmpa_cu_end/$ppoty*0.145*0.85,2);}
        else
            {$this->data->dmpa_cpr = "0";}
        if($nfpcm_cu_end>0)
            {$this->data->nfpcm_cpr   = number_format($nfpcm_cu_end/$ppoty*0.145*0.85,2);}
        else
            {$this->data->nfpcm_cpr = "0";}
        if($nfpbbt_cu_end>0)
            {$this->data->nfpbbt_cpr   = number_format($nfpbbt_cu_end/$ppoty*0.145*0.85,2);}
        else
            {$this->data->nfpbbt_cpr = "0";}
        if($nfpstm_cu_end>0)
            {$this->data->nfpstm_cpr   = number_format($nfpstm_cu_end/$ppoty*0.145*0.85,2);}
        else
            {$this->data->nfpstm_cpr = "0";}
        if($nfdsdm_cu_end>0)
            {$this->data->nfdsdm_cpr   = number_format($nfdsdm_cu_end/$ppoty*0.145*0.85,2);}
        else
            {$this->data->nfdsdm_cpr = "0";}
        if($nfplam_cu_end>0)
            {$this->data->nfplam_cpr   = number_format($nfplam_cu_end/$ppoty*0.145*0.85,2);}
        else
            {$this->data->nfplam_cpr = "0";}
        if($condom_cu_end>0)
            {$condom_cpr   =  number_format($condom_cu_end/$ppoty*0.145*0.85,2);}
        else
            {$this->data->condom_cpr = "0";}
        
        $this->data->fstr_cu_end = $fstr_cu_end;
        $this->data->mstr_cu_end = $mstr_cu_end;
        $this->data->pills_cu_end = $pills_cu_end;
        $this->data->iud_cu_end = $iud_cu_end;
        $this->data->dmpa_cu_end = $dmpa_cu_end;
        $this->data->nfpcm_cu_end = $nfpcm_cu_end;
        $this->data->nfpbbt_cu_end = $nfpbbt_cu_end;
        $this->data->nfpstm_cu_end = $nfpstm_cu_end;
        $this->data->nfdsdm_cu_end = $nfdsdm_cu_end;
        $this->data->nfplam_cu_end = $nfplam_cu_end;
        $this->data->condom_cu_end = $condom_cu_end;
            
        //============================== Dental ===============================//
        
        $eligible_pop_dental1 = number_format($ppoty * 0.135);
        $eligible_pop_dental2 = number_format($ppoty * 0.035);
        $eligible_pop_dental3 = number_format($ppoty * 0.135);
        $eligible_pop_dental4 = number_format($ppoty * 0.061);
       
        if($dental['orally_fit_1271_mo']>0)
            {$this->data->orally_fit_1271_per = number_format($dental['orally_fit_1271_mo']/$eligible_pop_dental1,2);}
        else
            {$this->data->orally_fit_1271_per = "0";}
        if($dental['bohc_1271_mo']>0)
            {$this->data->bohc_1271_mo_per = number_format($dental['bohc_1271_mo']/$eligible_pop_dental1,2);}
        else
            {$this->data->bohc_1271_mo_per = "0";} 
        if($dental['bohc_1024_yo']>0)
            {$this->data->bohc_1024_yo_per = number_format($dental['bohc_1024_yo']/$eligible_pop_dental2,2);}
        else
            {$this->data->bohc_1024_yo_per = "0";}  
        if($dental['bohc_pregnant']>0)
            {$this->data->bohc_pregnant_per = number_format($dental['bohc_pregnant']/$eligible_pop_dental3,2);}
        else
            {$this->data->bohc_pregnant_per = "0";}   
        if($dental['bohc_60plus_yo']>0)
            {$this->data->bohc_60plus_yo_per = number_format($dental['bohc_60plus_yo']/$eligible_pop_dental4,2);}
        else
            {$this->data->bohc_60plus_yo_per = "0";}
            
        $this->data->eligible_pop_dental1 = $eligible_pop_dental1;
        $this->data->eligible_pop_dental2 = $eligible_pop_dental2;
        $this->data->eligible_pop_dental3 = $eligible_pop_dental3;
        $this->data->eligible_pop_dental4 = $eligible_pop_dental4;
        
        //============================= Child Care ===========================//
        
        $eligible_pop_childcare1 = number_format($ppoty * 0.027);
        
        $bcg_Total = $childcare['bcgF_total']+$childcare['bcgM_total'];
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
        $livebirths_Total = $childcare['livebirths_M_total']+$childcare['livebirths_F_total'];
        $child_protected_Total = $childcare['child_protected_F_total']+$childcare['child_protected_M_total'];
        $infant_6mos_seen_Total = $childcare['infant_6mos_seen_F_total']+$childcare['infant_6mos_seen_M_total'];
        $breastfeed_6th_Total = $childcare['breastfeed_6th_F_total']+$childcare['breastfeed_6th_M_total'];
        $referred_nb_screening_Total = $childcare['referred_nb_screeningF_total']+$childcare['referred_nb_screeningM_total'];
        
        if($bcg_Total>0)
            {$this->data->bcg_per = number_format($bcg_Total/$eligible_pop_childcare1);}
        else
            {$this->data->bcg_per = "0";}
        if($dpt1_Total>0)
            {$this->data->dpt1_per = number_format($dpt1_Total/$eligible_pop_childcare1);}
        else
            {$this->data->dpt1_per = "0";}
        if($dpt2_Total>0)
            {$this->data->dpt2_per = number_format($dpt2_Total/$eligible_pop_childcare1);}
        else
            {$this->data->dpt2_per = "0";}
        if($dpt3_Total>0)
            {$this->data->dpt3_per = number_format($dpt3_Total/$eligible_pop_childcare1);}
        else
            {$this->data->dpt3_per = "0";}
        if($opv1_Total>0)
            {$this->data->opv1_per = number_format($opv1_Total/$eligible_pop_childcare1);}
        else
            {$this->data->opv1_per = "0";}
        if($opv2_Total>0)
            {$this->data->opv2_per = number_format($opv2_Total/$eligible_pop_childcare1);}
        else
            {$this->data->opv2_per = "0";}
        if($opv3_Total>0)
            {$this->data->opv3_per = number_format($opv3_Total/$eligible_pop_childcare1);}
        else
            {$this->data->opv3_per = "0";}
        if($hepa_b1_with_in_Total>0)
            {$this->data->hepa_b1_with_in_per = number_format($hepa_b1_with_in_Total/$eligible_pop_childcare1);}
        else
            {$this->data->hepa_b1_with_in_per = "0";}
        if($hepa_b1_more_than_Total>0)
            {$this->data->hepa_b1_more_than_per = number_format($hepa_b1_more_than_Total/$eligible_pop_childcare1);}
        else
            {$this->data->hepa_b1_more_than_per = "0";}
        if($hepa_b2_Total>0)
            {$this->data->hepa_b2_per = number_format($hepa_b2_Total/$eligible_pop_childcare1);}
        else
            {$this->data->hepa_b2_per = "0";}
        if($hepa_b3_Total>0)
            {$this->data->hepa_b3_per = number_format($hepa_b3_Total/$eligible_pop_childcare1);}
        else
            {$this->data->hepa_b3_per = "0";}
        if($im_anti_measles_Total>0)
            {$this->data->im_anti_measles_per = number_format($im_anti_measles_Total/$eligible_pop_childcare1);}
        else
            {$this->data->im_anti_measles_per = "0";}
        if($im_fully_under_Total>0)
            {$this->data->im_fully_under_per = number_format($im_fully_under_Total/$eligible_pop_childcare1);}
        else
            {$this->data->im_fully_under_per = "0";}
        if($livebirths_Total>0)
            {$this->data->livebirths_per = number_format($livebirths_Total/$eligible_pop_childcare1);}
        else
            {$this->data->livebirths_per = "0";}
        if($child_protected_Total>0)
            {$this->data->child_protected_per = number_format($child_protected_Total/$eligible_pop_childcare1);}
        else
            {$this->data->child_protected_per = "0";}
        if($infant_6mos_seen_Total>0)
            {$this->data->infant_6mos_seen_per = number_format($infant_6mos_seen_Total/$eligible_pop_childcare1);}
        else
            {$this->data->infant_6mos_seen_per = "0";}
        if($breastfeed_6th_Total>0)
            {$this->data->breastfeed_6th_per = number_format($breastfeed_6th_Total/$eligible_pop_childcare1);}
        else
            {$this->data->breastfeed_6th_per = "0";}
        if($referred_nb_screening_Total>0)
            {$this->data->referred_nb_screening_per =  number_format($referred_nb_screening_Total/$eligible_pop_childcare1);}
        else
            {$this->data->referred_nb_screening_per = "0";}
            
        $this->data->bcg_Total = $bcg_Total;
        $this->data->dpt1_Total = $dpt1_Total;
        $this->data->dpt2_Total = $dpt2_Total;
        $this->data->dpt3_Total = $dpt3_Total;
        $this->data->opv1_Total = $opv1_Total;
        $this->data->opv2_Total = $opv2_Total;
        $this->data->opv3_Total = $opv3_Total;
        $this->data->hepa_b1_with_in_Total = $hepa_b1_with_in_Total;
        $this->data->hepa_b1_more_than_Total = $hepa_b1_more_than_Total;
        $this->data->hepa_b2_Total = $hepa_b2_Total;
        $this->data->hepa_b3_Total = $hepa_b3_Total;
        $this->data->im_anti_measles_Total = $im_anti_measles_Total;
        $this->data->im_fully_under_Total = $im_fully_under_Total;
        $this->data->livebirths_Total = $livebirths_Total;
        $this->data->child_protected_Total = $child_protected_Total;
        $this->data->infant_6mos_seen_Total = $infant_6mos_seen_Total;
        $this->data->breastfeed_6th_Total = $breastfeed_6th_Total;
        $this->data->referred_nb_screening_Total = $referred_nb_screening_Total;
        
        $this->data->eligible_pop_childcare1 = $eligible_pop_childcare1;
            
        //============================= More Data ============================//
        
        $this->data->default_province = $default_province;
        $this->data->default_city = $default_city;
        $this->data->quarter = $quarter;
        $this->data->year = $year;
        $this->data->ppoty = $ppoty;
        
        $this->data->maternal = $maternal;
        $this->data->family_planning = $family_planning;
        $this->data->dental = $dental;
        $this->data->childcare = $childcare;

		$this->template->title($this->module_details['name'])->build('admin/rpt_view.php', $this->data);   
    }
  
    public function rhu_q1($action)
    {
        $quarter = $this->input->post('q1_quarter');
        $year = $this->input->post('q1_year');
        $ppoty = $this->input->post('q1_ppoty');
        
        $this->session->set_userdata('ppoty',$ppoty);
        
        $maternal_int = $this->input->post('maternal_int');
        $maternal_rec = $this->input->post('maternal_rec');
        $family_int = $this->input->post('family_int');
        $family_rec = $this->input->post('family_rec');
        $dental_int = $this->input->post('dental_int');
        $dental_rec = $this->input->post('dental_rec');
        $childcare_int = $this->input->post('childcare_int');
        $childcare_rec = $this->input->post('childcare_rec');
        $diseases_int = $this->input->post('diseases_int');
        $diseases_rec = $this->input->post('diseases_rec');
        
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
        
        $this->rhu_q1_maternal($quarter, $year,  $maternal_int, $maternal_rec);
        $this->rhu_q1_family_planning($quarter, $year, $family_int, $family_rec);
        $this->rhu_q1_dental($quarter, $year, $dental_int, $dental_rec);
        $this->rhu_q1_childcare($quarter, $year, $childcare_int, $childcare_rec);
        $this->rhu_q1_diseases($quarter, $year, $diseases_int, $diseases_rec);
        
        if($action=="generate_rhu_q1_dl")  
            {$this->fpdf->Output('BHS_Quarterly1_'.$quartername.'_'.$year.'.pdf','D');}
        else
            {$this->fpdf->Output('BHS_Quarterly1_'.$quartername.'_'.$year.'.pdf','I');}
    }
    
    function rhu_q1_maternal($quarter=null, $year=null, $maternal_int=null, $maternal_rec=null)
    {
        $maternal_int = str_replace(array("\r","\n"),"",$maternal_int);
        $maternal_rec = str_replace(array("\r","\n"),"",$maternal_rec);
        
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
        $ppoty = doubleval($this->session->userdata('ppoty'));
        //$ppoty = 23994;
        
        $eligible_pop_maternal1 = number_format($ppoty * 0.035);
        $eligible_pop_maternal2 = number_format($ppoty * 0.03);
        
        // Using this data, get the relevant results 
        $maternal = $this->rhu_q1_m->get_maternal_quarterreport($quarter, $year);
        if($maternal['total2']>0)
            {$maternal_per1 = number_format(($maternal['total2']/$eligible_pop_maternal1)*100,2);}
        else
            {$maternal_per1 = "0";}
        if($maternal['total2']>0)
            {$maternal_per2 = number_format(($maternal['total2']/$eligible_pop_maternal1)*100,2);}
        else
            {$maternal_per2 = "0";}
        if($maternal['total3']>0)
            {$maternal_per3 = number_format(($maternal['total3']/$eligible_pop_maternal1)*100,2);}
        else
            {$maternal_per3 = "0";}
        if($maternal['total4']>0)
            {$maternal_per4 = number_format(($maternal['total4']/$eligible_pop_maternal1)*100,2);}
        else
            {$maternal_per4 = "0";}
        if($maternal['total5']>0)
            {$maternal_per5 = number_format(($maternal['total5']/$eligible_pop_maternal1)*100,2);}
        else
            {$maternal_per5 = "0";}
        if($maternal['total6']>0)
            {$maternal_per6 = number_format(($maternal['total6']/$eligible_pop_maternal2)*100,2);}
        else
            {$maternal_per6 = "0";}
        if($maternal['total7']>0)
            {$maternal_per7 = number_format(($maternal['total7']/$eligible_pop_maternal2)*100,2);}
        else
            {$maternal_per7 = "0";}
        if($maternal['total8']>0)
            {$maternal_per8 = number_format(($maternal['total8']/$eligible_pop_maternal2)*100,2);}
        else
            {$maternal_per8 = "0";}
        if($maternal['total9']>0)
            {$maternal_per9 = number_format(($maternal['total9']/$eligible_pop_maternal2)*100,2);}
        else
            {$maternal_per9 = "0";}
        
        $this->fpdf->AliasNbPages();
        $this->fpdf->AddPage('L','A4');
        $this->fpdf->SetAutoPageBreak(true, 10);
        
        $this->fpdf->SetDrawColor(150,150,150);   // Set Line and Border color
        
        //Draw rectangles
        $this->fpdf->Cell(15);
        $this->fpdf->SetFillColor(247,247,247);       //Set Fill color
        $this->fpdf->Rect(10,10,277,178);             // Main box
        $this->fpdf->Rect(10,10,45,30,'D',true);      //Left box
        $this->fpdf->Rect(55,10,202,30,'D',true);     //Mid box
        $this->fpdf->Rect(257,10,30,30,'D',true);     //Right box
        
        //Header
        $this->fpdf->Image(base_url().'system/cms/themes/pyrocms/img/logo_small.png',12,20,40);
        $this->fpdf->SetY(15);
        $this->fpdf->SetX(57);
        $this->fpdf->SetFont('Helvetica','',10);
        $this->fpdf->SetTextColor(0,0,0);
        $this->fpdf->Cell(39,7,'FHSIS REPORT for the ',0,0,'L');
        $this->fpdf->Cell(35,7,$quartername,'ltrB',0,'L');
        $this->fpdf->SetX(200);
        $this->fpdf->Cell(10,7,'Year:',0,0,'L');
        $this->fpdf->Cell(12,7,$year,'ltrB',1,'L');
        $this->fpdf->SetX(57);
        $this->fpdf->Cell(32,7,'Municipality/City of:',0,0,'L');
        $this->fpdf->Cell(30,7,$default_city,'ltrB',1,'L');
        $this->fpdf->SetX(57);
        $this->fpdf->Cell(16,7,'Province:',0,0,'L');
        $this->fpdf->Cell(40,7,$default_province,'ltrB',0,'L');
        $this->fpdf->SetX(146);
        $this->fpdf->Cell(65,7,'Projected Population of the Year:',0,0,'R');
        $this->fpdf->SetX(210);
        $this->fpdf->Cell(20,7,number_format($ppoty),'ltrB',1,'L');
        
        $this->fpdf->SetFillColor(247,247,247);
        
        $this->fpdf->SetY(40);
        $this->fpdf->SetFont('Helvetica','B',12);
        $this->fpdf->Cell(277,15,'MATERNAL CARE',1,1,'C',true);
        
        $this->fpdf->SetFont('Helvetica','B',9);
        $this->fpdf->Cell(97,7,'','LTRb',0,'C');
        $this->fpdf->Cell(20,7,'Elig','LTRb',0,'C');
        $this->fpdf->Cell(20,7,'','LTRb',0,'C');
        $this->fpdf->Cell(20,7,'','LTRb',0,'C');
        $this->fpdf->Cell(60,7,'','LTRb',0,'C');
        $this->fpdf->Cell(60,7,'Recommendations/','LTRb',1,'C');
        
        $this->fpdf->Cell(97,7,'Indicators','LtRB',0,'C');
        $this->fpdf->Cell(20,7,'Pop.','LtRB',0,'C');
        $this->fpdf->Cell(20,7,'No.','LtRB',0,'C');
        $this->fpdf->Cell(20,7,'% Rate','LtRB',0,'C');
        $this->fpdf->Cell(60,7,'Interpretation','LtRB',0,'C');
        $this->fpdf->Cell(60,7,'Action Taken','LtRB',1,'C');
        
        $this->fpdf->SetFont('Helvetica','I',9);
        
        $this->fpdf->Cell(97,8,'Col. 1',1,0,'C');
        $this->fpdf->Cell(20,8,'Col. 2',1,0,'C');
        $this->fpdf->Cell(20,8,'Col. 3',1,0,'C');
        $this->fpdf->Cell(20,8,'Col. 4',1,0,'C');
        $this->fpdf->Cell(60,8,'Col. 5',1,0,'C');
        $this->fpdf->Cell(60,8,'Col. 6',1,1,'C');
        
        $this->fpdf->SetFont('Helvetica','',8.5);
        
        $this->fpdf->Cell(97,8,'Pregnant women with 4 or more Prenatal visits?',1,0,'L');
        $this->fpdf->Cell(20,8,$eligible_pop_maternal1,1,0,'C');
        $this->fpdf->Cell(20,8,$maternal['total2'],1,0,'C');
        $this->fpdf->Cell(20,8,$maternal_per1,1,0,'C');
        
        $this->fpdf->SetY(79);
        $this->fpdf->SetX(169);
        $this->fpdf->MultiCell(56,5,$maternal_int);
        $this->fpdf->SetY(79);
        $this->fpdf->SetX(229);
        $this->fpdf->MultiCell(56,5,$maternal_rec);
        
        $this->fpdf->Rect(167,77,60,104,'D',true);     //Interpretation box
        $this->fpdf->Rect(227,77,60,104,'D',true);     //Recommendations box
        
        $this->fpdf->SetY(85);
        $this->fpdf->Cell(97,8,'Pregnant women given 2 doses of Tetanus Toxoid?',1,0,'L');
        $this->fpdf->Cell(20,8,$eligible_pop_maternal1,1,0,'C');
        $this->fpdf->Cell(20,8,$maternal['total2'],1,0,'C');
        $this->fpdf->Cell(20,8,$maternal_per2,1,1,'C');
        
        $this->fpdf->Cell(97,8,'Pregnant women given TT2 plus?',1,0,'L');
        $this->fpdf->Cell(20,8,$eligible_pop_maternal1,1,0,'C');
        $this->fpdf->Cell(20,8,$maternal['total3'],1,0,'C');
        $this->fpdf->Cell(20,8,$maternal_per3,1,1,'C');
        
        $this->fpdf->Cell(97,8,'Pregnant women given complete Iron w/ Folic Acid?',1,0,'L');
        $this->fpdf->Cell(20,8,$eligible_pop_maternal1,1,0,'C');
        $this->fpdf->Cell(20,8,$maternal['total4'],1,0,'C');
        $this->fpdf->Cell(20,8,$maternal_per4,1,1,'C');
        
        $this->fpdf->Cell(97,8,'Pregnant women given Vitamin A',1,0,'L');
        $this->fpdf->Cell(20,8,$eligible_pop_maternal1,1,0,'C');
        $this->fpdf->Cell(20,8,$maternal['total5'],1,0,'C');
        $this->fpdf->Cell(20,8,$maternal_per5,1,1,'C');
        
        $this->fpdf->Cell(97,8,'Postpartum women with at least 2 Postpartum visits',1,0,'L');
        $this->fpdf->Cell(20,8,$eligible_pop_maternal2,1,0,'C');
        $this->fpdf->Cell(20,8,$maternal['total6'],1,0,'C');
        $this->fpdf->Cell(20,8,$maternal_per6,1,1,'C');
        
        $this->fpdf->Cell(97,8,'Postpartum women given complete Iron',1,0,'L');
        $this->fpdf->Cell(20,8,$eligible_pop_maternal2,1,0,'C');
        $this->fpdf->Cell(20,8,$maternal['total7'],1,0,'C');
        $this->fpdf->Cell(20,8,$maternal_per7,1,1,'C');
        
        $this->fpdf->Cell(97,8,'Postpartum women given Vitamin A',1,0,'L');
        $this->fpdf->Cell(20,8,$eligible_pop_maternal2,1,0,'C');
        $this->fpdf->Cell(20,8,$maternal['total8'],1,0,'C');
        $this->fpdf->Cell(20,8,$maternal_per8,1,1,'C');
        
        $this->fpdf->Cell(97,8,'Postpartum women initiated breastfeeding',1,0,'L');
        $this->fpdf->Cell(20,8,$eligible_pop_maternal2,1,0,'C');
        $this->fpdf->Cell(20,8,$maternal['total9'],1,0,'C');
        $this->fpdf->Cell(20,8,$maternal_per9,1,1,'C');
        
        $this->fpdf->Cell(97,8,'',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,1,'L');
        
        $this->fpdf->Cell(97,8,'',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,1,'L');
        
        $this->fpdf->Cell(97,8,'',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,1,'L');
        
        $this->fpdf->Cell(97,8,'',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,1,'L');
        
        $this->fpdf->Cell(80,7,'Eligible Population: ? TP x 3.5%','LTrB',0,'L');
        $this->fpdf->Cell(197,7,'? TP x 3%','lTRB',1,'L');
        
        $this->footer();
        $this->q1_header_icon();
        //$this->fpdf->Output();
    }
    
    function rhu_q1_family_planning($quarter, $year, $family_int, $family_rec)
    {           
        // Using this data, get the relevant results
        $family_planning = $this->rhu_q1_m->get_familyplanning_quarterreport($quarter, $year);      
        
        $family_int = str_replace(array("\r","\n"),"",$family_int);
        $family_rec = str_replace(array("\r","\n"),"",$family_rec);
        
        $ppoty = doubleval($this->session->userdata('ppoty'));
        //$ppoty = 2394;
        
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
            {$fstr_cpr = "0";}
        if($mstr_cu_end>0)
            {$mstr_cpr = number_format($mstr_cu_end/$ppoty*0.145*0.85,2);}
        else
            {$mstr_cpr = "0";}
        if($pills_cu_end>0)
            {$pills_cpr  = number_format($pills_cu_end/$ppoty*0.145*0.85,2);}
        else
            {$pills_cpr = "0";}
        if($iud_cu_end>0)
            {$iud_cpr  = number_format($iud_cu_end/$ppoty*0.145*0.85,2);}
        else
            {$iud_cpr = "0";}
        if($dmpa_cu_end>0)
            {$dmpa_cpr   = number_format($dmpa_cu_end/$ppoty*0.145*0.85,2);}
        else
            {$dmpa_cpr = "0";}
        if($nfpcm_cu_end>0)
            {$nfpcm_cpr   = number_format($nfpcm_cu_end/$ppoty*0.145*0.85,2);}
        else
            {$nfpcm_cpr = "0";}
        if($nfpbbt_cu_end>0)
            {$nfpbbt_cpr   = number_format($nfpbbt_cu_end/$ppoty*0.145*0.85,2);}
        else
            {$nfpbbt_cpr = "0";}
        if($nfpstm_cu_end>0)
            {$nfpstm_cpr   = number_format($nfpstm_cu_end/$ppoty*0.145*0.85,2);}
        else
            {$nfpstm_cpr = "0";}
        if($nfdsdm_cu_end>0)
            {$nfdsdm_cpr   = number_format($nfdsdm_cu_end/$ppoty*0.145*0.85,2);}
        else
            {$nfdsdm_cpr = "0";}
        if($nfplam_cu_end>0)
            {$nfplam_cpr   = number_format($nfplam_cu_end/$ppoty*0.145*0.85,2);}
        else
            {$nfplam_cpr = "0";}
        if($condom_cu_end>0)
            {$condom_cpr   =  number_format($condom_cu_end/$ppoty*0.145*0.85,2);}
        else
            {$condom_cpr = "0";}
        
        $this->fpdf->AliasNbPages();
        $this->fpdf->AddPage('L','A4');
        $this->fpdf->SetAutoPageBreak(true, 10);
        
        $this->fpdf->SetDrawColor(150,150,150);   // Set Line and Border color
        
        //Draw rectangles
        $this->fpdf->Cell(15);
        $this->fpdf->SetFillColor(247,247,247);       //Set Fill color
        $this->fpdf->Rect(10,15,277,155);             // Main box
        
        $this->fpdf->SetFillColor(247,247,247);
        
        $this->fpdf->SetY(15);
        $this->fpdf->SetFont('Helvetica','B',12);
        $this->fpdf->SetTextColor(0,0,0);
        $this->fpdf->Cell(277,15,'FAMILY PLANNING',1,1,'C',true);
        
        $this->fpdf->SetFont('Helvetica','B',9);
        $this->fpdf->Cell(52,5,'','LTRb',0,'C');
        $this->fpdf->Cell(25,5,'Current User','LTRb',0,'C');
        $this->fpdf->Cell(30,5,'Acceptors','LTRB',0,'C');
        $this->fpdf->Cell(15,5,'','LTRb',0,'C');
        $this->fpdf->Cell(30,5,'Current Users','LTRb',0,'C');
        $this->fpdf->Cell(25,5,'CPR','LTRb',0,'C');
        $this->fpdf->Cell(50,5,'','LTRb',0,'C');
        $this->fpdf->Cell(50,5,'Recommendations/','LTRb',1,'C');
        
        $this->fpdf->Cell(52,5,'Indicators','LtRb',0,'C');
        $this->fpdf->Cell(25,5,'(Begin Qtr.)','LtRb',0,'C');
        $this->fpdf->Cell(15,5,'New','LTRb',0,'C');
        $this->fpdf->Cell(15,5,'Others','LTRb',0,'C');
        $this->fpdf->Cell(15,5,'Drop-out','LtRb',0,'C');
        $this->fpdf->Cell(30,5,'(End Qtr.)','LtRb',0,'C');
        $this->fpdf->Cell(25,5,'Col. 6/TP x','LtRb',0,'C');
        $this->fpdf->Cell(50,5,'Interpretation','LtRb',0,'C');
        $this->fpdf->Cell(50,5,'Actions Taken','LtRb',1,'C');
        
        $this->fpdf->Cell(52,5,'','LtRB',0,'C');
        $this->fpdf->Cell(25,5,'','LtRB',0,'C');
        $this->fpdf->Cell(15,5,'','LtRB',0,'C');
        $this->fpdf->Cell(15,5,'','LtRB',0,'C');
        $this->fpdf->Cell(15,5,'','LtRB',0,'C');
        $this->fpdf->Cell(30,5,'','LtRB',0,'C');
        $this->fpdf->Cell(25,5,'14.5% x 85%','LtRB',0,'C');
        $this->fpdf->Cell(50,5,'','LtRB',0,'C');
        $this->fpdf->Cell(50,5,'','LtRB',1,'C');
        
        $this->fpdf->SetFont('Helvetica','I',9);
        
        $this->fpdf->Cell(52,8,'Col. 1',1,0,'C');
        $this->fpdf->Cell(25,8,'Col. 2',1,0,'C');
        $this->fpdf->Cell(15,8,'Col. 3',1,0,'C');
        $this->fpdf->Cell(15,8,'Col. 4',1,0,'C');
        $this->fpdf->Cell(15,8,'Col. 5',1,0,'C');
        $this->fpdf->Cell(30,8,'Col. 6',1,0,'C');
        $this->fpdf->Cell(25,8,'Col. 7',1,0,'C');
        $this->fpdf->Cell(50,8,'Col. 8',1,0,'C');
        $this->fpdf->Cell(50,8,'Col. 9',1,1,'C');
        
        $this->fpdf->SetFont('Helvetica','',8.5);
        
        $this->fpdf->Cell(52,8,'a. Female Ster/BTL',1,0,'L');
        $this->fpdf->Cell(25,8,$family_planning['fstr_cu_begin'],1,0,'C');
        $this->fpdf->Cell(15,8,$family_planning['fstr_na'],1,0,'C');
        $this->fpdf->Cell(15,8,$family_planning['fstr_others'],1,0,'C');
        $this->fpdf->Cell(15,8,$family_planning['fstr_dropout'],1,0,'C');
        $this->fpdf->Cell(30,8,$fstr_cu_end,1,0,'C');
        $this->fpdf->Cell(25,8,$fstr_cpr,1,1,'C');
        
        $this->fpdf->SetY(55);
        $this->fpdf->SetX(189);
        $this->fpdf->MultiCell(46,5,$family_int);
        $this->fpdf->SetY(55);
        $this->fpdf->SetX(239);
        $this->fpdf->MultiCell(46,5,$family_rec);
        
        $this->fpdf->Rect(187,53,50,112,'D',true);     //Interpretation box
        $this->fpdf->Rect(237,53,50,112,'D',true); 
        
        $this->fpdf->SetY(61);
        
        $this->fpdf->Cell(52,8,'b. Male Ster/Vasectomy',1,0,'L');
        $this->fpdf->Cell(25,8,$family_planning['mstr_cu_begin'],1,0,'C');
        $this->fpdf->Cell(15,8,$family_planning['mstr_na'],1,0,'C');
        $this->fpdf->Cell(15,8,$family_planning['mstr_others'],1,0,'C');
        $this->fpdf->Cell(15,8,$family_planning['mstr_dropout'],1,0,'C');
        $this->fpdf->Cell(30,8,$mstr_cu_end ,1,0,'C');
        $this->fpdf->Cell(25,8,$mstr_cpr,1,1,'C');
        
        $this->fpdf->Cell(52,8,'c. Pills',1,0,'L');
        $this->fpdf->Cell(25,8,$family_planning['pills_cu_begin'],1,0,'C');
        $this->fpdf->Cell(15,8,$family_planning['pills_na'],1,0,'C');
        $this->fpdf->Cell(15,8,$family_planning['pills_others'],1,0,'C');
        $this->fpdf->Cell(15,8,$family_planning['pills_dropout'],1,0,'C');
        $this->fpdf->Cell(30,8,$pills_cu_end,1,0,'C');
        $this->fpdf->Cell(25,8,$pills_cpr,1,1,'C');      
        
        $this->fpdf->Cell(52,8,'d. IUD',1,0,'L');
        $this->fpdf->Cell(25,8,$family_planning['iud_cu_begin'],1,0,'C');
        $this->fpdf->Cell(15,8,$family_planning['iud_na'],1,0,'C');
        $this->fpdf->Cell(15,8,$family_planning['iud_others'],1,0,'C');
        $this->fpdf->Cell(15,8,$family_planning['iud_dropout'],1,0,'C');
        $this->fpdf->Cell(30,8,$iud_cu_end,1,0,'C');
        $this->fpdf->Cell(25,8,$iud_cpr,1,1,'C');
        
        $this->fpdf->Cell(52,8,'e. Injectables(DMPA)',1,0,'L');
        $this->fpdf->Cell(25,8,$family_planning['dmpa_cu_begin'],1,0,'C');
        $this->fpdf->Cell(15,8,$family_planning['dmpa_na'],1,0,'C');
        $this->fpdf->Cell(15,8,$family_planning['dmpa_others'],1,0,'C');
        $this->fpdf->Cell(15,8,$family_planning['dmpa_dropout'],1,0,'C');
        $this->fpdf->Cell(30,8,$dmpa_cu_end,1,0,'C');
        $this->fpdf->Cell(25,8,$dmpa_cpr,1,1,'C');
        
        $this->fpdf->Cell(52,8,'f. NFP-CM',1,0,'L');
        $this->fpdf->Cell(25,8,$family_planning['nfpcm_cu_begin'],1,0,'C');
        $this->fpdf->Cell(15,8,$family_planning['nfpcm_na'],1,0,'C');
        $this->fpdf->Cell(15,8,$family_planning['nfpcm_others'],1,0,'C');
        $this->fpdf->Cell(15,8,$family_planning['nfpcm_dropout'],1,0,'C');
        $this->fpdf->Cell(30,8,$nfpcm_cu_end,1,0,'C');
        $this->fpdf->Cell(25,8,$nfpcm_cpr,1,1,'C');
        
        $this->fpdf->Cell(52,8,'g. NFP-BBT',1,0,'L');
        $this->fpdf->Cell(25,8,$family_planning['nfpbbt_cu_begin'],1,0,'C');
        $this->fpdf->Cell(15,8,$family_planning['nfpbbt_na'],1,0,'C');
        $this->fpdf->Cell(15,8,$family_planning['nfpbbt_others'],1,0,'C');
        $this->fpdf->Cell(15,8,$family_planning['nfpbbt_dropout'],1,0,'C');
        $this->fpdf->Cell(30,8,$nfpbbt_cu_end,1,0,'C');
        $this->fpdf->Cell(25,8,$nfpbbt_cpr,1,1,'C');
        
        $this->fpdf->Cell(52,8,'h. NFP-STM',1,0,'L');
        $this->fpdf->Cell(25,8,$family_planning['nfpstm_cu_begin'],1,0,'C');
        $this->fpdf->Cell(15,8,$family_planning['nfpstm_na'],1,0,'C');
        $this->fpdf->Cell(15,8,$family_planning['nfpstm_others'],1,0,'C');
        $this->fpdf->Cell(15,8,$family_planning['nfpstm_dropout'],1,0,'C');
        $this->fpdf->Cell(30,8,$nfpstm_cu_end,1,0,'C');
        $this->fpdf->Cell(25,8,$nfpstm_cpr,1,1,'C');
        
        $this->fpdf->Cell(52,8,'i. NFPD-Standard Days Method',1,0,'L');
        $this->fpdf->Cell(25,8,$family_planning['nfdsdm_cu_begin'],1,0,'C');
        $this->fpdf->Cell(15,8,$family_planning['nfdsdm_na'],1,0,'C');
        $this->fpdf->Cell(15,8,$family_planning['nfdsdm_others'],1,0,'C');
        $this->fpdf->Cell(15,8,$family_planning['nfdsdm_dropout'],1,0,'C');
        $this->fpdf->Cell(30,8,$nfdsdm_cu_end,1,0,'C');
        $this->fpdf->Cell(25,8,$nfdsdm_cpr,1,1,'C');
        
        $this->fpdf->Cell(52,8,'j. NFP-LAM',1,0,'L');
        $this->fpdf->Cell(25,8,$family_planning['nfplam_cu_begin'],1,0,'C');
        $this->fpdf->Cell(15,8,$family_planning['nfplam_na'],1,0,'C');
        $this->fpdf->Cell(15,8,$family_planning['nfplam_others'],1,0,'C');
        $this->fpdf->Cell(15,8,$family_planning['nfplam_dropout'],1,0,'C');
        $this->fpdf->Cell(30,8,$nfplam_cu_end,1,0,'C');
        $this->fpdf->Cell(25,8,$nfplam_cpr,1,1,'C');
        
        $this->fpdf->Cell(52,8,'k. Condom',1,0,'L');
        $this->fpdf->Cell(25,8,$family_planning['condom_cu_begin'],1,0,'C');
        $this->fpdf->Cell(15,8,$family_planning['condom_na'],1,0,'C');
        $this->fpdf->Cell(15,8,$family_planning['condom_others'],1,0,'C');
        $this->fpdf->Cell(15,8,$family_planning['condom_dropout'],1,0,'C');
        $this->fpdf->Cell(30,8,$condom_cu_end,1,0,'C');
        $this->fpdf->Cell(25,8,$condom_cpr,1,1,'C');
        
        $this->fpdf->Cell(52,8,'',1,0,'L');
        $this->fpdf->Cell(25,8,'',1,0,'C');
        $this->fpdf->Cell(15,8,'',1,0,'C');
        $this->fpdf->Cell(15,8,'',1,0,'C');
        $this->fpdf->Cell(15,8,'',1,0,'C');
        $this->fpdf->Cell(30,8,'',1,0,'C');
        $this->fpdf->Cell(25,8,'',1,1,'C');
        
        $this->fpdf->Cell(52,8,'',1,0,'L');
        $this->fpdf->Cell(25,8,'',1,0,'C');
        $this->fpdf->Cell(15,8,'',1,0,'C');
        $this->fpdf->Cell(15,8,'',1,0,'C');
        $this->fpdf->Cell(15,8,'',1,0,'C');
        $this->fpdf->Cell(30,8,'',1,0,'C');
        $this->fpdf->Cell(25,8,'',1,1,'C');
        
        $this->fpdf->Cell(52,8,'',1,0,'L');
        $this->fpdf->Cell(25,8,'',1,0,'C');
        $this->fpdf->Cell(15,8,'',1,0,'C');
        $this->fpdf->Cell(15,8,'',1,0,'C');
        $this->fpdf->Cell(15,8,'',1,0,'C');
        $this->fpdf->Cell(30,8,'',1,0,'C');
        $this->fpdf->Cell(25,8,'',1,1,'C');
        
        $this->fpdf->Cell(80,5,'','LTrB',0,'L');
        $this->fpdf->Cell(197,5,'','lTRB',1,'L');
        
        $this->fpdf->Cell(50,8,'* Others include CM, CC and RS',0,0,'L');
        
        $this->footer();
        $this->q1_pagenum();
        
        //$this->fpdf->Output();
    }
    
    function rhu_q1_dental($quarter, $year, $dental_int, $dental_rec)
    {
        $base_where = $this->session->userdata('base_where');
            
        // Using this data, get the relevant results
        $dental = $this->rhu_q1_m->get_dental_quarterreport($quarter, $year);
        
        $dental_int = str_replace(array("\r","\n"),"",$dental_int);
        $dental_rec = str_replace(array("\r","\n"),"",$dental_rec);
        
        $ppoty = doubleval($this->session->userdata('ppoty'));
        //$ppoty = 23994;
        
        $eligible_pop_dental1 = number_format($ppoty * 0.135);
        $eligible_pop_dental2 = number_format($ppoty * 0.035);
        $eligible_pop_dental3 = number_format($ppoty * 0.135);
        $eligible_pop_dental4 = number_format($ppoty * 0.061);
       
        if($dental['orally_fit_1271_mo']>0)
            {$orally_fit_1271_per = number_format($dental['orally_fit_1271_mo']/$eligible_pop_dental1,2);}
        else
            {$orally_fit_1271_per = "0";}
        if($dental['orally_fit_1271_mo']>0)
            {$bohc_1271_mo_per = number_format($dental['bohc_1271_mo']/$eligible_pop_dental1,2);}
        else
            {$bohc_1271_mo_per = "0";} 
        if($dental['bohc_1024_yo']>0)
            {$bohc_1024_yo_per = number_format($dental['bohc_1024_yo']/$eligible_pop_dental2,2);}
        else
            {$bohc_1024_yo_per = "0";}  
        if($dental['bohc_pregnant']>0)
            {$bohc_pregnant_per = number_format($dental['bohc_pregnant']/$eligible_pop_dental3,2);}
        else
            {$bohc_pregnant_per = "0";}   
        if($dental['bohc_60plus_yo']>0)
            {$bohc_60plus_yo_per = number_format($dental['bohc_60plus_yo']/$eligible_pop_dental4,2);}
        else
            {$bohc_60plus_yo_per = "0";}
        
        $this->fpdf->AliasNbPages();
        $this->fpdf->AddPage('L','A4');
        $this->fpdf->SetAutoPageBreak(true, 10);
        
        $this->fpdf->SetDrawColor(150,150,150);   // Set Line and Border color
        
        //Draw rectangles
        $this->fpdf->Cell(15);
        $this->fpdf->SetFillColor(247,247,247);       //Set Fill color
        $this->fpdf->Rect(10,15,277,150);             // Main box
        
        $this->fpdf->SetFillColor(247,247,247);
        
        $this->fpdf->SetY(15);
        $this->fpdf->SetFont('Helvetica','B',12);
        $this->fpdf->SetTextColor(0,0,0);
        $this->fpdf->Cell(277,15,'DENTAL CARE',1,1,'C',true);
        
        $this->fpdf->SetFont('Helvetica','B',9);
        $this->fpdf->Cell(77,5,'','LTRb',0,'C');
        $this->fpdf->Cell(20,5,'Elig.','LTRb',0,'C');
        $this->fpdf->Cell(80,5,'Number','1',0,'C');
        $this->fpdf->Cell(50,5,'','LTRb',0,'C');
        $this->fpdf->Cell(50,5,'Recommendations/','LTRb',1,'C');
        
        $this->fpdf->Cell(77,5,'Indicators','LtRb',0,'C');
        $this->fpdf->Cell(20,5,'Pop.','LtRb',0,'C');
        $this->fpdf->Cell(20,5,'Male','LtRb',0,'C');
        $this->fpdf->Cell(20,5,'Female','LtRb',0,'C');
        $this->fpdf->Cell(20,5,'Total','LtRb',0,'C');
        $this->fpdf->Cell(20,5,'%','LtRb',0,'C');
        $this->fpdf->Cell(50,5,'Interpretation','LtRb',0,'C');
        $this->fpdf->Cell(50,5,'Actions Taken','LtRb',1,'C');
        
        $this->fpdf->SetFont('Helvetica','I',9);
        
        $this->fpdf->Cell(77,8,'Col. 1',1,0,'C');
        $this->fpdf->Cell(20,8,'Col. 2',1,0,'C');
        $this->fpdf->Cell(20,8,'Col. 3',1,0,'C');
        $this->fpdf->Cell(20,8,'Col. 4',1,0,'C');
        $this->fpdf->Cell(20,8,'Col. 5',1,0,'C');
        $this->fpdf->Cell(20,8,'Col. 6',1,0,'C');
        $this->fpdf->Cell(50,8,'Col. 7',1,0,'C');
        $this->fpdf->Cell(50,8,'Col. 8',1,1,'C');
        
        $this->fpdf->SetFont('Helvetica','',8.5);
        
        $this->fpdf->Cell(77,8,'Orally Fit Childen 12-71 mos old?',1,0,'L');
        $this->fpdf->Cell(20,8,$eligible_pop_dental1,1,0,'C');
        $this->fpdf->Cell(20,8,$dental['orally_fit_1271_mo_m'],1,0,'C');
        $this->fpdf->Cell(20,8,$dental['orally_fit_1271_mo_f'],1,0,'C');
        $this->fpdf->Cell(20,8,$dental['orally_fit_1271_mo'],1,0,'C');
        $this->fpdf->Cell(20,8,$orally_fit_1271_per,1,1,'C');
        
        $this->fpdf->SetY(50);
        $this->fpdf->SetX(189);
        $this->fpdf->MultiCell(46,5,$dental_int);
        $this->fpdf->SetY(50);
        $this->fpdf->SetX(239);
        $this->fpdf->MultiCell(46,5,$dental_rec);
        
        $this->fpdf->Rect(187,48,50,112,'D',true);     //Interpretation box
        $this->fpdf->Rect(237,48,50,112,'D',true); 
        
        $this->fpdf->SetY(56);
        
        $this->fpdf->Cell(77,8,'Childen 12-71 mos old provided w/ BOHC?',1,0,'L');
        $this->fpdf->Cell(20,8,$eligible_pop_dental1,1,0,'C');
        $this->fpdf->Cell(20,8,$dental['bohc_1271_mo_m'],1,0,'C');
        $this->fpdf->Cell(20,8,$dental['bohc_1271_mo_f'],1,0,'C');
        $this->fpdf->Cell(20,8,$dental['bohc_1271_mo'],1,0,'C');
        $this->fpdf->Cell(20,8,$bohc_1271_mo_per,1,1,'C');
        
        $this->fpdf->Cell(77,8,'Adolescent & Youth (10-24 yrs) given BOHC?',1,0,'L');
        $this->fpdf->Cell(20,8,$eligible_pop_dental2,1,0,'C');
        $this->fpdf->Cell(20,8,$dental['bohc_1024_yo_m'],1,0,'C');
        $this->fpdf->Cell(20,8,$dental['bohc_1024_yo_f'],1,0,'C');
        $this->fpdf->Cell(20,8,$dental['bohc_1024_yo'],1,0,'C');
        $this->fpdf->Cell(20,8,$bohc_1024_yo_per,1,1,'C');
        
        $this->fpdf->Cell(77,8,'Pregnant women provided with BOHC',1,0,'L');
        $this->fpdf->Cell(20,8,$eligible_pop_dental3,1,0,'C');
        $this->fpdf->Cell(20,8,'-',1,0,'C');
        $this->fpdf->Cell(20,8,$dental['bohc_pregnant'],1,0,'C');
        $this->fpdf->Cell(20,8,$dental['bohc_pregnant'],1,0,'C');
        $this->fpdf->Cell(20,8,$bohc_pregnant_per,1,1,'C');
        
        $this->fpdf->Cell(77,8,'Older Person 60 yrs old & above provided with BOHC?',1,0,'L');
        $this->fpdf->Cell(20,8,$eligible_pop_dental4,1,0,'C');
        $this->fpdf->Cell(20,8,$dental['bohc_60plus_yo_m'],1,0,'C');
        $this->fpdf->Cell(20,8,$dental['bohc_60plus_yo_f'],1,0,'C');
        $this->fpdf->Cell(20,8,$dental['bohc_60plus_yo'],1,0,'C');
        $this->fpdf->Cell(20,8,$bohc_60plus_yo_per,1,1,'C');
        
        $this->fpdf->Cell(77,8,'',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,'',1,1,'C');
        
        $this->fpdf->Cell(77,8,'',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,'',1,1,'C');
        
        $this->fpdf->Cell(77,8,'',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,'',1,1,'C');
        
        $this->fpdf->Cell(77,8,'',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,'',1,1,'C');
        
        $this->fpdf->Cell(77,8,'',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,'',1,1,'C');
        
        $this->fpdf->Cell(77,8,'',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,'',1,1,'C');
        
        $this->fpdf->Cell(77,8,'',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,'',1,1,'C');
        
        $this->fpdf->Cell(77,8,'',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,'',1,1,'C');
        
        $this->fpdf->Cell(77,8,'',1,0,'L');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,'',1,0,'C');
        $this->fpdf->Cell(20,8,'',1,1,'C');
        
        $this->fpdf->Cell(77,5,'','LTrB',0,'L');
        $this->fpdf->Cell(200,5,'','lTRB',1,'L');
        
        $this->fpdf->SetFont('Helvetica','I',9);
        $this->fpdf->Cell(50,8,'Eligible Population ?TPX13.5%X20%(ST)  ?TPX3.5%X25%(ST)  ?TPX13.5%X20%(ST) ?TPX6.1%X30%(ST)',0,0,'L');
        
        $this->footer();
        $this->q1_pagenum();
        
        //$this->fpdf->Output();
    }
    
    function rhu_q1_childcare($quarter, $year, $childcare_int, $childcare_rec)                                                  
    {            
      // Using this data, get the relevant results
        $childcare = $this->rhu_q1_m->get_childcare_quarterreport($quarter, $year);
        
        $childcare_int = str_replace(array("\r","\n"),"",$childcare_int);
        $childcare_rec = str_replace(array("\r","\n"),"",$childcare_rec);
        
        $ppoty = doubleval($this->session->userdata('ppoty'));
        //$ppoty = 23994;
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
        $livebirths_Total = $childcare['livebirths_F_total']+$childcare['livebirths_M_total'];
        $child_protected_Total = $childcare['child_protected_F_total']+$childcare['child_protected_M_total'];
        $infant_6mos_seen_Total = $childcare['infant_6mos_seen_F_total']+$childcare['infant_6mos_seen_M_total'];
        $breastfeed_6th_Total = $childcare['breastfeed_6th_F_total']+$childcare['breastfeed_6th_M_total'];
        $referred_nb_screening_Total = $childcare['referred_nb_screeningF_total']+$childcare['referred_nb_screeningM_total'];
        
        if($bcg_Total>0)
            {$bcg_per = number_format($bcg_Total/$eligible_pop,2);}
        else
            {$bcg_per = "0";}
        if($dpt1_Total>0)
            {$dpt1_per = number_format($dpt1_Total/$eligible_pop,2);}
        else
            {$dpt1_per = "0";}
        if($dpt2_Total>0)
            {$dpt2_per = number_format($dpt2_Total/$eligible_pop,2);}
        else
            {$dpt2_per = "0";}
        if($dpt3_Total>0)
            {$dpt3_per = number_format($dpt3_Total/$eligible_pop,2);}
        else
            {$dpt3_per = "0";}
        if($opv1_Total>0)
            {$opv1_per = number_format($opv1_Total/$eligible_pop,2);}
        else
            {$opv1_per = "0";}
        if($opv2_Total>0)
            {$opv2_per = number_format($opv2_Total/$eligible_pop,2);}
        else
            {$opv2_per = "0";}
        if($opv3_Total>0)
            {$opv3_per = number_format($opv3_Total/$eligible_pop,2);}
        else
            {$opv3_per = "0";}
        if($hepa_b1_with_in_Total>0)
            {$hepa_b1_with_in_per = number_format($hepa_b1_with_in_Total/$eligible_pop,2);}
        else
            {$hepa_b1_with_in_per = "0";}
        if($hepa_b1_more_than_Total>0)
            {$hepa_b1_more_than_per = number_format($hepa_b1_more_than_Total/$eligible_pop,2);}
        else
            {$hepa_b1_more_than_per = "0";}
        if($hepa_b2_Total>0)
            {$hepa_b2_per = number_format($hepa_b2_Total/$eligible_pop,2);}
        else
            {$hepa_b2_per = "0";}
        if($hepa_b3_Total>0)
            {$hepa_b3_per = number_format($hepa_b3_Total/$eligible_pop,2);}
        else
            {$hepa_b3_per = "0";}
        if($im_anti_measles_Total>0)
            {$im_anti_measles_per = number_format($im_anti_measles_Total/$eligible_pop,2);}
        else
            {$im_anti_measles_per = "0";}
        if($im_fully_under_Total>0)
            {$im_fully_under_per = number_format($im_fully_under_Total/$eligible_pop,2);}
        else
            {$im_fully_under_per = "0";}
        if($livebirths_Total>0)
            {$livebirths_per = number_format($livebirths_Total/$eligible_pop,2);}
        else
            {$livebirths_per = "0";}    
        if($child_protected_Total>0)
            {$child_protected_per = number_format($child_protected_Total/$eligible_pop,2);}
        else
            {$child_protected_per = "0";}
        if($infant_6mos_seen_Total>0)
            {$infant_6mos_seen_per = number_format($infant_6mos_seen_Total/$eligible_pop_childcare1);}
        else
            {$infant_6mos_seen_per = "0";}
        if($breastfeed_6th_Total>0)
            {$breastfeed_6th_per = number_format($breastfeed_6th_Total/$eligible_pop,2);}
        else
            {$breastfeed_6th_per = "0";}
        if($referred_nb_screening_Total>0)
            {$referred_nb_screening_per =  number_format($referred_nb_screening_Total/$eligible_pop,2);}
        else
            {$referred_nb_screening_per = "0";}
        
        $this->fpdf->AliasNbPages();
        $this->fpdf->AddPage('L','A4');
        $this->fpdf->SetAutoPageBreak(true, 10);
        
        $this->fpdf->SetDrawColor(150,150,150);   // Set Line and Border color
        
        //Draw rectangles
        $this->fpdf->Cell(15);
        $this->fpdf->SetFillColor(247,247,247);       //Set Fill color
        $this->fpdf->Rect(10,15,277,178);             // Main box
        
        $this->fpdf->SetFillColor(247,247,247);
        
        $this->fpdf->SetY(15);
        $this->fpdf->SetFont('Helvetica','B',12);
        $this->fpdf->SetTextColor(0,0,0);
        $this->fpdf->Cell(277,15,'CHILD CARE',1,1,'C',true);
        
        $this->fpdf->SetFont('Helvetica','B',9);
        $this->fpdf->Cell(77,5,'','LTRb',0,'C');
        $this->fpdf->Cell(20,5,'Elig.','LTRb',0,'C');
        $this->fpdf->Cell(80,5,'Number','1',0,'C');
        $this->fpdf->Cell(50,5,'','LTRb',0,'C');
        $this->fpdf->Cell(50,5,'Recommendations/','LTRb',1,'C');
        
        $this->fpdf->Cell(77,5,'Indicators','LtRb',0,'C');
        $this->fpdf->Cell(20,5,'Pop.','LtRb',0,'C');
        $this->fpdf->Cell(20,5,'Male','LtRb',0,'C');
        $this->fpdf->Cell(20,5,'Female','LtRb',0,'C');
        $this->fpdf->Cell(20,5,'Total','LtRb',0,'C');
        $this->fpdf->Cell(20,5,'%','LtRb',0,'C');
        $this->fpdf->Cell(50,5,'Interpretation','LtRb',0,'C');
        $this->fpdf->Cell(50,5,'Actions Taken','LtRb',1,'C');
        
        $this->fpdf->SetFont('Helvetica','I',9);
        
        $this->fpdf->Cell(77,5,'Col. 1',1,0,'C');
        $this->fpdf->Cell(20,5,'Col. 2',1,0,'C');
        $this->fpdf->Cell(20,5,'Col. 3',1,0,'C');
        $this->fpdf->Cell(20,5,'Col. 4',1,0,'C');
        $this->fpdf->Cell(20,5,'Col. 5',1,0,'C');
        $this->fpdf->Cell(20,5,'Col. 6',1,0,'C');
        $this->fpdf->Cell(50,5,'Col. 7',1,0,'C');
        $this->fpdf->Cell(50,5,'Col. 8',1,1,'C');
        
        $this->fpdf->SetFont('Helvetica','',8.5);
        
        $this->fpdf->Cell(77,7,'Infant given BCG?',1,0,'L');
        $this->fpdf->Cell(20,7,number_format($eligible_pop),1,0,'C');
        $this->fpdf->Cell(20,7,$childcare['bcgM_total'],1,0,'C');
        $this->fpdf->Cell(20,7,$childcare['bcgF_total'],1,0,'C');
        $this->fpdf->Cell(20,7,$bcg_Total,1,0,'C');
        $this->fpdf->Cell(20,7,$bcg_per,1,0,'C');
        
        $this->fpdf->SetY(47);
        $this->fpdf->SetX(189);
        $this->fpdf->MultiCell(46,5,$childcare_int);
        $this->fpdf->SetY(47);
        $this->fpdf->SetX(239);
        $this->fpdf->MultiCell(46,5,$childcare_rec);
        
        $this->fpdf->Rect(187,45,50,133,'D',true);     //Interpretation box
        $this->fpdf->Rect(237,45,50,133,'D',true); 
        
        $this->fpdf->SetY(52);
        
        $this->fpdf->Cell(77,7,'Infant given DPT1?',1,0,'L');
        $this->fpdf->Cell(20,7,number_format($eligible_pop),1,0,'C');
        $this->fpdf->Cell(20,7,$childcare['dpt1M_total'],1,0,'C');
        $this->fpdf->Cell(20,7,$childcare['dpt1F_total'],1,0,'C');
        $this->fpdf->Cell(20,7,$dpt1_Total,1,0,'C');
        $this->fpdf->Cell(20,7,$dpt1_per,1,1,'C');
        
        $this->fpdf->Cell(77,7,'Infant given DPT2?',1,0,'L');
        $this->fpdf->Cell(20,7,number_format($eligible_pop),1,0,'C');
        $this->fpdf->Cell(20,7,$childcare['dpt2M_total'],1,0,'C');
        $this->fpdf->Cell(20,7,$childcare['dpt2F_total'],1,0,'C');
        $this->fpdf->Cell(20,7,$dpt2_Total,1,0,'C');
        $this->fpdf->Cell(20,7,$dpt2_per,1,1,'C');
        
        $this->fpdf->Cell(77,7,'Infant given DPT3?',1,0,'L');
        $this->fpdf->Cell(20,7,number_format($eligible_pop),1,0,'C');
        $this->fpdf->Cell(20,7,$childcare['dpt3M_total'],1,0,'C');
        $this->fpdf->Cell(20,7,$childcare['dpt3F_total'],1,0,'C');
        $this->fpdf->Cell(20,7,$dpt3_Total,1,0,'C');
        $this->fpdf->Cell(20,7,$dpt3_per,1,1,'C');
        
        $this->fpdf->Cell(77,7,'Infant given OPV1?',1,0,'L');
        $this->fpdf->Cell(20,7,number_format($eligible_pop),1,0,'C');
        $this->fpdf->Cell(20,7,$childcare['opv1M_total'],1,0,'C');
        $this->fpdf->Cell(20,7,$childcare['opv1F_total'],1,0,'C');
        $this->fpdf->Cell(20,7,$opv1_Total,1,0,'C');
        $this->fpdf->Cell(20,7,$opv1_per,1,1,'C');
        
        $this->fpdf->Cell(77,7,'Infant given OPV2?',1,0,'L');
        $this->fpdf->Cell(20,7,number_format($eligible_pop),1,0,'C');
        $this->fpdf->Cell(20,7,$childcare['opv2M_total'],1,0,'C');
        $this->fpdf->Cell(20,7,$childcare['opv2F_total'],1,0,'C');
        $this->fpdf->Cell(20,7,$opv2_Total,1,0,'C');
        $this->fpdf->Cell(20,7,$opv2_per,1,1,'C');
        
        $this->fpdf->Cell(77,7,'Infant given OPV3?',1,0,'L');
        $this->fpdf->Cell(20,7,number_format($eligible_pop),1,0,'C');
        $this->fpdf->Cell(20,7,$childcare['opv3M_total'],1,0,'C');
        $this->fpdf->Cell(20,7,$childcare['opv3F_total'],1,0,'C');
        $this->fpdf->Cell(20,7,$opv3_Total,1,0,'C');
        $this->fpdf->Cell(20,7,$opv3_per,1,1,'C');
        
        $this->fpdf->Cell(77,7,'Infant given Hepa B1 w/in 24 hrs. after birth2',1,0,'L');
        $this->fpdf->Cell(20,7,number_format($eligible_pop),1,0,'C');
        $this->fpdf->Cell(20,7,$childcare['hepa_b1_with_inM_total'],1,0,'C');
        $this->fpdf->Cell(20,7,$childcare['hepa_b1_with_inF_total'],1,0,'C');
        $this->fpdf->Cell(20,7,$hepa_b1_with_in_Total,1,0,'C');
        $this->fpdf->Cell(20,7,$hepa_b1_with_in_per,1,1,'C');
        
         $this->fpdf->Cell(77,7,'Infant given Hepa B1 more than 24 hrs. after birth2',1,0,'L');
        $this->fpdf->Cell(20,7,number_format($eligible_pop),1,0,'C');
        $this->fpdf->Cell(20,7,$childcare['hepa_b1_more_thanM_total'],1,0,'C');
        $this->fpdf->Cell(20,7,$childcare['hepa_b1_more_thanF_total'],1,0,'C');
        $this->fpdf->Cell(20,7,$hepa_b1_more_than_Total,1,0,'C');
        $this->fpdf->Cell(20,7,$hepa_b1_more_than_per,1,1,'C');
        
        $this->fpdf->Cell(77,7,'Infant given Hepatitis B2?',1,0,'L');
        $this->fpdf->Cell(20,7,number_format($eligible_pop),1,0,'C');
        $this->fpdf->Cell(20,7,$childcare['hepa_b2M_total'],1,0,'C');
        $this->fpdf->Cell(20,7,$childcare['hepa_b2F_total'],1,0,'C');
        $this->fpdf->Cell(20,7,$hepa_b2_Total,1,0,'C');
        $this->fpdf->Cell(20,7,$hepa_b2_per,1,1,'C');
        
        $this->fpdf->Cell(77,7,'Infant given Hepatitis B3?',1,0,'L');
        $this->fpdf->Cell(20,7,number_format($eligible_pop),1,0,'C');
        $this->fpdf->Cell(20,7,$childcare['hepa_b3M_total'],1,0,'C');
        $this->fpdf->Cell(20,7,$childcare['hepa_b3F_total'],1,0,'C');
        $this->fpdf->Cell(20,7,$hepa_b3_Total,1,0,'C');
        $this->fpdf->Cell(20,7,$hepa_b3_per,1,1,'C');
        
        $this->fpdf->Cell(77,7,'Infant given anti-Measles?',1,0,'L');
        $this->fpdf->Cell(20,7,number_format($eligible_pop),1,0,'C');
        $this->fpdf->Cell(20,7,$childcare['im_anti_measlesM_total'],1,0,'C');
        $this->fpdf->Cell(20,7,$childcare['im_anti_measlesF_total'],1,0,'C');
        $this->fpdf->Cell(20,7,$im_anti_measles_Total,1,0,'C');
        $this->fpdf->Cell(20,7,$im_anti_measles_per,1,1,'C');
        
        $this->fpdf->Cell(77,7,'Fully Immunized Child(0-11 mos)',1,0,'L');
        $this->fpdf->Cell(20,7,number_format($eligible_pop),1,0,'C');
        $this->fpdf->Cell(20,7,$childcare['im_fully_under1M_total'],1,0,'C');
        $this->fpdf->Cell(20,7,$childcare['im_fully_under1F_total'],1,0,'C');
        $this->fpdf->Cell(20,7,$im_fully_under_Total,1,0,'C');
        $this->fpdf->Cell(20,7,$im_fully_under_per,1,1,'C');
        
        $this->fpdf->Cell(77,7,'Completely Immunized Child(12-23 mos)',1,0,'L');
        $this->fpdf->Cell(20,7,'-',1,0,'C');
        $this->fpdf->Cell(20,7,'-',1,0,'C');
        $this->fpdf->Cell(20,7,'-',1,0,'C');
        $this->fpdf->Cell(20,7,'-',1,0,'C');
        $this->fpdf->Cell(20,7,'-',1,1,'C');
        
        $this->fpdf->Cell(77,7,'Total Livebirths',1,0,'L');
        $this->fpdf->Cell(20,7,'-',1,0,'C');
        $this->fpdf->Cell(20,7,$childcare['livebirths_M_total'],1,0,'C');
        $this->fpdf->Cell(20,7,$childcare['livebirths_F_total'],1,0,'C');
        $this->fpdf->Cell(20,7,$livebirths_Total,1,0,'C');
        $this->fpdf->Cell(20,7,'-',1,1,'C');
        
        $this->fpdf->Cell(77,7,'Child Protected at Birth (CPAB)?',1,0,'L');
        $this->fpdf->Cell(20,7,'-',1,0,'C');
        $this->fpdf->Cell(20,7,$childcare['child_protected_M_total'],1,0,'C');
        $this->fpdf->Cell(20,7,$childcare['child_protected_F_total'],1,0,'C');
        $this->fpdf->Cell(20,7,$child_protected_Total,1,0,'C');
        $this->fpdf->Cell(20,7,'-',1,1,'C');
        
        $this->fpdf->Cell(77,7,'Infant age 6 mos. Seen',1,0,'L');
        $this->fpdf->Cell(20,7,'-',1,0,'C');
        $this->fpdf->Cell(20,7,$childcare['child_protected_M_total'],1,0,'C');
        $this->fpdf->Cell(20,7,$childcare['child_protected_F_total'],1,0,'C');
        $this->fpdf->Cell(20,7,$infant_6mos_seen_Total,1,0,'C');
        $this->fpdf->Cell(20,7,'-',1,1,'C');
        
        $this->fpdf->Cell(77,7,'Infant exclusively breastfeed until 6th mos?',1,0,'L');
        $this->fpdf->Cell(20,7,'-',1,0,'C');
        $this->fpdf->Cell(20,7,$childcare['breastfeed_6th_M_total'],1,0,'C');
        $this->fpdf->Cell(20,7,$childcare['breastfeed_6th_F_total'],1,0,'C');
        $this->fpdf->Cell(20,7,$breastfeed_6th_Total,1,0,'C');
        $this->fpdf->Cell(20,7,'-',1,1,'C');
        
        $this->fpdf->Cell(77,7,'Infant 0-11 mos. referred for newborn screening',1,0,'L');
        $this->fpdf->Cell(20,7,'-',1,0,'C');
        $this->fpdf->Cell(20,7,$childcare['referred_nb_screeningM_total'],1,0,'C');
        $this->fpdf->Cell(20,7,$childcare['referred_nb_screeningF_total'],1,0,'C');
        $this->fpdf->Cell(20,7,$referred_nb_screening_Total,1,0,'C');
        $this->fpdf->Cell(20,7,'-',1,1,'C');
        
        $this->fpdf->SetFont('Helvetica','I',9);
        $this->fpdf->Cell(77,7,'Eligible Population ? TP x 2.7%','LTrB',0,'L');
        $this->fpdf->Cell(40,7,'? Total Livebirths','lTrB',0,'L');
        $this->fpdf->Cell(60,7,'? No. Infant seen at 6th month','lTrB',0,'L');
        $this->fpdf->Cell(100,7,'','lTRB',1,'C');
                                                                               
        $this->footer();
        $this->q1_pagenum();
        
        //$this->fpdf->Output();
    }
    
    function rhu_q1_diseases($quarter, $year, $diseases_int, $diseases_rec)                                                  
    {
       $base_where = $this->session->userdata('base_where');
            
       // Using this data, get the relevant results
       $clients = $this->clients_m
       ->order_by('last_name', 'ASC')
       ->get_many_by($base_where);
        
        $this->fpdf->AliasNbPages();
        $this->fpdf->AddPage('L','A4');
        $this->fpdf->SetAutoPageBreak(true, 10);
        
        $this->fpdf->SetDrawColor(150,150,150);   // Set Line and Border color
        
        //Draw rectangles
        $this->fpdf->Cell(15);
        $this->fpdf->SetFillColor(247,247,247);       //Set Fill color
        $this->fpdf->Rect(10,15,277,178);             // Main box
        
        $this->fpdf->SetFillColor(247,247,247);
        
        $this->fpdf->SetY(15);
        $this->fpdf->SetFont('Helvetica','B',12);
        $this->fpdf->SetTextColor(0,0,0);
        $this->fpdf->Cell(277,15,'DISEASES CONTROL',1,1,'C',true);
        
        $this->fpdf->SetFont('Helvetica','B',9);
        $this->fpdf->Cell(47,5,'Malaria','LTRb',0,'C');
        $this->fpdf->Cell(80,5,'Number','LTRb',0,'C');
        $this->fpdf->Cell(50,5,'','1',0,'C');
        $this->fpdf->Cell(50,5,'','LTRb',0,'C');
        $this->fpdf->Cell(50,5,'Recommendations/','LTRb',1,'C');
        
        $this->fpdf->Cell(47,5,'(endemic areas)','LtRb',0,'C');
        $this->fpdf->Cell(20,5,'Male.',1,0,'C');
        $this->fpdf->Cell(20,5,'Female',1,0,'C');
        $this->fpdf->Cell(20,5,'Pregnant',1,0,'C');
        $this->fpdf->Cell(20,5,'Total',1,0,'C');
        $this->fpdf->Cell(50,5,'Rate',1,0,'C');
        $this->fpdf->Cell(50,5,'Interpretation',1,0,'C');
        $this->fpdf->Cell(50,5,'Actions Taken',1,1,'C');
        
        $this->fpdf->SetFont('Helvetica','I',9);
        
        $this->fpdf->Cell(47,5,'Col. 1',1,0,'C');
        $this->fpdf->Cell(20,5,'Col. 2',1,0,'C');
        $this->fpdf->Cell(20,5,'Col. 3',1,0,'C');
        $this->fpdf->Cell(20,5,'Col. 4',1,0,'C');
        $this->fpdf->Cell(20,5,'Col. 5',1,0,'C');
        $this->fpdf->Cell(50,5,'Col. 6',1,0,'C');
        $this->fpdf->Cell(50,5,'Col. 7',1,0,'C');
        $this->fpdf->Cell(50,5,'Col. 8',1,1,'C');
        
        $this->fpdf->SetFont('Helvetica','',7);
        
        $this->fpdf->Cell(47,4,'','LtRb',0,'L');
        $this->fpdf->Cell(20,4,'','LtRb',0,'C');
        $this->fpdf->Cell(20,4,'','LtRb',0,'C');
        $this->fpdf->Cell(20,4,'','LtRb',0,'C');
        $this->fpdf->Cell(20,4,'','LtRb',0,'C');
        $this->fpdf->Cell(25,4,'Morbidity','LtRb',0,'C');
        $this->fpdf->Cell(25,4,'Annual Parasite','LtRb',0,'C');
        
        $this->fpdf->SetFont('Helvetica','',8.5);
        
        $this->fpdf->SetY(47);
        $this->fpdf->SetX(189);
        $this->fpdf->MultiCell(46,5,$diseases_int);
        $this->fpdf->SetY(47);
        $this->fpdf->SetX(239);
        $this->fpdf->MultiCell(46,5,$diseases_rec);
        
        $this->fpdf->Rect(187,45,50,123,'D',true);     //Interpretation box
        $this->fpdf->Rect(237,45,50,123,'D',true); 
        
        $this->fpdf->SetFont('Helvetica','',7);
        
        $this->fpdf->SetY(49);
        
        $this->fpdf->Cell(47,4,'','LtRb',0,'L');
        $this->fpdf->Cell(20,4,'','LtRb',0,'C');
        $this->fpdf->Cell(20,4,'','LtRb',0,'C');
        $this->fpdf->Cell(20,4,'','LtRb',0,'C');
        $this->fpdf->Cell(20,4,'','LtRb',0,'C');
        $this->fpdf->Cell(25,4,'Rate','LtRb',0,'C');
        $this->fpdf->Cell(25,4,'Incidents','LtRb',1,'C');
        
         $this->fpdf->SetFont('Helvetica','',8.5);
        
        $this->fpdf->Cell(47,8,'Malaria Case?','LtRb',0,'L');
        $this->fpdf->Cell(20,8,'','LtRb',0,'C');
        $this->fpdf->Cell(20,8,'','LtRb',0,'C');
        $this->fpdf->Cell(20,8,'','LtRb',0,'C');
        $this->fpdf->Cell(20,8,'','LtRb',0,'C');
        $this->fpdf->Cell(25,8,'','LTRb',0,'C');
        $this->fpdf->Cell(25,8,'','LTRb',1,'C');
        
        $this->fpdf->Cell(47,7,'   ? 5 yo ?',1,0,'L');
        $this->fpdf->Cell(20,7,'',1,0,'C');
        $this->fpdf->Cell(20,7,'',1,0,'C');
        $this->fpdf->Cell(20,7,'',1,0,'C');
        $this->fpdf->Cell(20,7,'',1,0,'C');
        $this->fpdf->Cell(25,7,'',1,0,'C');
        $this->fpdf->Cell(25,7,'',1,1,'C');
        
        $this->fpdf->Cell(47,7,'   ? >=5 yo ?',1,0,'L');
        $this->fpdf->Cell(20,7,'',1,0,'C');
        $this->fpdf->Cell(20,7,'',1,0,'C');
        $this->fpdf->Cell(20,7,'',1,0,'C');
        $this->fpdf->Cell(20,7,'',1,0,'C');
        $this->fpdf->Cell(25,7,'',1,0,'C');
        $this->fpdf->Cell(25,7,'',1,1,'C');
        
        $this->fpdf->Cell(47,7,'Confirmed malaria Case?',1,0,'L');
        $this->fpdf->Cell(20,7,'',1,0,'C');
        $this->fpdf->Cell(20,7,'',1,0,'C');
        $this->fpdf->Cell(20,7,'',1,0,'C');
        $this->fpdf->Cell(20,7,'',1,0,'C');
        $this->fpdf->Cell(25,7,'',1,0,'C');
        $this->fpdf->Cell(25,7,'',1,1,'C');
        
        $this->fpdf->Cell(47,7,'  By Species','LTRb',0,'L');
        $this->fpdf->Cell(20,7,'','LTRb',0,'C');
        $this->fpdf->Cell(20,7,'','LTRb',0,'C');
        $this->fpdf->Cell(20,7,'','LTRb',0,'C');
        $this->fpdf->Cell(20,7,'','LTRb',0,'C');
        $this->fpdf->Cell(25,7,'','LTRb',0,'C');
        $this->fpdf->Cell(25,7,'','LTRb',1,'C');
        
        $this->fpdf->Cell(47,7,'    ? P.falciparum ?','LtRB',0,'L');
        $this->fpdf->Cell(20,7,'','LtRB',0,'C');
        $this->fpdf->Cell(20,7,'','LtRB',0,'C');
        $this->fpdf->Cell(20,7,'','LtRB',0,'C');
        $this->fpdf->Cell(20,7,'','LtRB',0,'C');
        $this->fpdf->Cell(25,7,'','LtRB',0,'C');
        $this->fpdf->Cell(25,7,'','LtRB',1,'C');
        
        $this->fpdf->Cell(47,7,'    ? P.vivax ?',1,0,'L');
        $this->fpdf->Cell(20,7,'',1,0,'C');
        $this->fpdf->Cell(20,7,'',1,0,'C');
        $this->fpdf->Cell(20,7,'',1,0,'C');
        $this->fpdf->Cell(20,7,'',1,0,'C');
        $this->fpdf->Cell(25,7,'',1,0,'C');
        $this->fpdf->Cell(25,7,'',1,1,'C');
        
        $this->fpdf->Cell(47,7,'    ? P.ovale ?',1,0,'L');
        $this->fpdf->Cell(20,7,'',1,0,'C');
        $this->fpdf->Cell(20,7,'',1,0,'C');
        $this->fpdf->Cell(20,7,'',1,0,'C');
        $this->fpdf->Cell(20,7,'',1,0,'C');
        $this->fpdf->Cell(25,7,'',1,0,'C');
        $this->fpdf->Cell(25,7,'',1,1,'C');
        
        $this->fpdf->Cell(47,7,'  By Method',1,0,'L');
        $this->fpdf->Cell(20,7,'',1,0,'C');
        $this->fpdf->Cell(20,7,'',1,0,'C');
        $this->fpdf->Cell(20,7,'',1,0,'C');
        $this->fpdf->Cell(20,7,'',1,0,'C');
        $this->fpdf->Cell(25,7,'',1,0,'C');
        $this->fpdf->Cell(25,7,'',1,1,'C');
        
        $this->fpdf->Cell(47,7,'    ? Slide ?',1,0,'L');
        $this->fpdf->Cell(20,7,'',1,0,'C');
        $this->fpdf->Cell(20,7,'',1,0,'C');
        $this->fpdf->Cell(20,7,'',1,0,'C');
        $this->fpdf->Cell(20,7,'',1,0,'C');
        $this->fpdf->Cell(25,7,'',1,0,'C');
        $this->fpdf->Cell(25,7,'',1,1,'C');
        
        $this->fpdf->Cell(47,7,'    ? RDT ?',1,0,'L');
        $this->fpdf->Cell(20,7,'',1,0,'C');
        $this->fpdf->Cell(20,7,'',1,0,'C');
        $this->fpdf->Cell(20,7,'',1,0,'C');
        $this->fpdf->Cell(20,7,'',1,0,'C');
        $this->fpdf->Cell(25,7,'',1,0,'C');
        $this->fpdf->Cell(25,7,'',1,1,'C');
        
        $this->fpdf->Cell(47,7,'Infant given anti-Measles?',1,0,'L');
        $this->fpdf->Cell(20,7,'',1,0,'C');
        $this->fpdf->Cell(20,7,'',1,0,'C');
        $this->fpdf->Cell(20,7,'',1,0,'C');
        $this->fpdf->Cell(20,7,'',1,0,'C');
        $this->fpdf->Cell(25,7,'',1,0,'C');
        $this->fpdf->Cell(25,7,'',1,1,'C');
        
        $this->fpdf->Cell(47,7,'Households at risk?',1,0,'L');
        $this->fpdf->Cell(20,7,'',1,0,'C');
        $this->fpdf->Cell(20,7,'',1,0,'C');
        $this->fpdf->Cell(20,7,'',1,0,'C');
        $this->fpdf->Cell(20,7,'',1,0,'C');
        $this->fpdf->Cell(25,7,'',1,0,'C');
        $this->fpdf->Cell(25,7,'',1,1,'C');
        
        $this->fpdf->Cell(47,7,'Households given ITN?',1,0,'L');
        $this->fpdf->Cell(20,7,'',1,0,'C');
        $this->fpdf->Cell(20,7,'',1,0,'C');
        $this->fpdf->Cell(20,7,'',1,0,'C');
        $this->fpdf->Cell(20,7,'',1,0,'C');
        $this->fpdf->Cell(25,7,'',1,0,'C');
        $this->fpdf->Cell(25,7,'',1,1,'C');
        
        $this->fpdf->SetFont('Helvetica','',7);
        
        $this->fpdf->Cell(47,4,'','LtRb',0,'L');
        $this->fpdf->Cell(20,4,'','LtRb',0,'C');
        $this->fpdf->Cell(20,4,'','LtRb',0,'C');
        $this->fpdf->Cell(20,4,'','LtRb',0,'C');
        $this->fpdf->Cell(20,4,'','LtRb',0,'C');
        $this->fpdf->Cell(25,4,'Mortality','LtRb',0,'C');
        $this->fpdf->Cell(25,4,'Case Fatality','LtRb',1,'C');
        
        $this->fpdf->Cell(47,4,'','LtRb',0,'L');
        $this->fpdf->Cell(20,4,'','LtRb',0,'C');
        $this->fpdf->Cell(20,4,'','LtRb',0,'C');
        $this->fpdf->Cell(20,4,'','LtRb',0,'C');
        $this->fpdf->Cell(20,4,'','LtRb',0,'C');
        $this->fpdf->Cell(25,4,'Rate','LtRb',0,'C');
        $this->fpdf->Cell(25,4,'Ratio','LtRb',1,'C');
        
         $this->fpdf->SetFont('Helvetica','',9);
        
        $this->fpdf->Cell(47,8,'Malaria Death','LtRb',0,'L');
        $this->fpdf->Cell(20,8,'','LtRb',0,'C');
        $this->fpdf->Cell(20,8,'','LtRb',0,'C');
        $this->fpdf->Cell(20,8,'','LtRb',0,'C');
        $this->fpdf->Cell(20,8,'','LtRb',0,'C');
        $this->fpdf->Cell(25,8,'','LTRb',0,'C');
        $this->fpdf->Cell(25,8,'','LTRb',1,'C');
        
        $this->fpdf->SetFont('Helvetica','I',7);
        $this->fpdf->Cell(37,4,'Denominator', 'LTrb',0,'L');
        $this->fpdf->Cell(110,4,'? Morbidity Rate = TP/Annual Parasite Incidence = Endemic Pop    ? >5 & < 5 yo Population    ?Malaria cases seen,','lTrb',0,'L');
        $this->fpdf->Cell(130,4,'','lTRb',1,'C');
        
        $this->fpdf->SetFont('Helvetica','I',7);
        $this->fpdf->Cell(37,4,'', 'LtrB',0,'L');
        $this->fpdf->Cell(110,4,'? Total Confirmed Malaria Case    ?Household at risk   ?Mortality Rate = TP/Case Fatality Ratio = Total Malaria Cases','ltrB',0,'L');
        $this->fpdf->Cell(130,4,'','ltRB',1,'C');
                                                                               
        $this->footer();
        $this->q1_pagenum();
        
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
        $this->fpdf->SetX(237);
        
        //Page footer
        $this->fpdf->SetTextColor(128);
        $this->fpdf->SetFont('Helvetica','',7);
        $this->fpdf->Cell(50,4,'FHSIS V 2013 - Q Form (Page '.$this->fpdf->PageNo().' of {nb})',0,1,'R');
    } 
    
    function q1_header_icon()
    {
        $this->fpdf->SetTextColor(0,0,0);
        $this->fpdf->SetY(10);
        $this->fpdf->SetX(257);
        $this->fpdf->SetFont('Helvetica','',6);
        $this->fpdf->Cell(30,5,'FHSIS Version 2013',0,1,'L');
        $this->fpdf->SetX(257);
        $this->fpdf->SetFont('Helvetica','B',60);
        $this->fpdf->Cell(30,15,'Q1',0,1,'C');
        $this->fpdf->SetX(257);
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
        echo print_r($model->get_childcare_quarterreport('1','2013')); 
    }

} /* end admin class */
?>