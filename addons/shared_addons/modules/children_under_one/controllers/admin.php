<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Roles controller for the family module
 *
 * @author Phil Sturgeon - PyroCMS Dev Team
 * @package PyroCMS
 * @subpackage Groups Module
 * @category Modules
 *
 */
class Admin extends Admin_Controller
{
	
  	protected $section = 'cuo';
  	
    private $validation_rules = array(
    array(
			'field' => 'client_id',
			'label' => 'Client ID',
			'rules' => 'required'
		),
		array(
			'field' => 'mother_name',
			'label' => 'Mother Name',
			'rules' => 'required'
		),
		array(
			'field' => 'nb_referral_date',
			'label' => 'Referral Date of Newborn Screening',
			'rules' => ''
		),
		array(
			'field' => 'nb_done_date',
			'label' => 'Date of Newborn Screening Done/Completed',
			'rules' => ''
		),
		array(
			'field' => 'cp_date_assess',
			'label' => 'Child Protected at Birth(CPAB) Date Assess',
			'rules' => ''
		),
		array(
			'field' => 'cp_tt_status',
			'label' => 'Child Protected at Birth(CPAB) TT Status',
			'rules' => ''
		),
		array(
			'field' => 'ms_a_age_months',
			'label' => 'Age in Months for Vitamin A Micronutrient Supplementation',
			'rules' => ''
		),
		array(
			'field' => 'ms_a_date_given',
			'label' => 'Date Given for Vitamin A Micronutrient Supplementation',
			'rules' => ''
		),
		array(
			'field' => 'ms_iron_birth_weight',
			'label' => 'Birth Weight for Iron Micronutrient Supplementation',
			'rules' => ''
		),
		array(
			'field' => 'ms_iron_date_started',
			'label' => 'Date Started for Iron Micronutrient Supplementation',
			'rules' => ''
		),
		array(
			'field' => 'ms_iron_date_completed',
			'label' => 'Date Completed for Iron Micronutrient Supplementation',
			'rules' => ''
		),
		array(
			'field' => 'im_bcg',
			'label' => 'Date Immunization Received for BCG',
			'rules' => ''
		),
		array(
			'field' => 'im_hepa_b_at_birth',
			'label' => 'Hepa B at Birth',
			'rules' => ''
		),
		array(
			'field' => 'im_pentavalent_1',
			'label' => 'Pentavalent 1',
			'rules' => ''
		),
		array(
			'field' => 'im_pentavalent_2',
			'label' => 'Pentavalent 2',
			'rules' => ''
		),
		array(
			'field' => 'im_pentavalent_3',
			'label' => 'Pentavalent 3',
			'rules' => ''
		),
		array(
			'field' => 'im_dpt1',
			'label' => 'Date Immunization Received for DPT1',
			'rules' => ''
		),
		array(
			'field' => 'im_dpt2',
			'label' => 'Date Immunization Received for DPT2',
			'rules' => ''
		),
		array(
			'field' => 'im_dpt3',
			'label' => 'Date Immunization Received for DPT3',
			'rules' => ''
		),
		array(
			'field' => 'im_polio1',
			'label' => 'Date Immunization Received for POLIO1',
			'rules' => ''
		),
		array(
			'field' => 'im_polio2',
			'label' => 'Date Immunization Received for POLIO2',
			'rules' => ''
		),
		array(
			'field' => 'im_polio3',
			'label' => 'Date Immunization Received for POLIO3',          
			'rules' => ''
		),
			array(
			'field' => 'im_rotarix1',
			'label' => 'Rotarix 1',
			'rules' => ''
		),
			array(
			'field' => 'im_rotarix2',
			'label' => 'Rotarix 2',
			'rules' => ''
		),
		array(
			'field' => 'im_hepa_b1_with_in',
			'label' => 'Date Immunization Received for HEBA B1 within 24 hours',
			'rules' => ''
		),
		array(
			'field' => 'im_hepa_b1_more_than',
			'label' => 'Date Immunization Received for HEBA B1 more than 24 hours',
			'rules' => ''
		),
		array(
			'field' => 'im_hepa_b2',
			'label' => 'Date Immunization Received for HEBA B2',    
			'rules' => ''
		),
		array(
			'field' => 'im_hepa_b3',
			'label' => 'Date Immunization Received for HEBA B3',
			'rules' => ''
		),
		array(
			'field' => 'im_anti_measles',
			'label' => 'Date Immunization Received for Anti-Measles Vaccine',
			'rules' => ''
		),
		array(
			'field' => 'im_mmr',
			'label' => 'MMR Vaccine',
			'rules' => ''
		),
		array(
			'field' => 'im_fully',
			'label' => 'Date Fully Immunized(FIC)',
			'rules' => ''
		),
		array(
			'field' => 'bf_1',
			'label' => '1st Month the Child was exclusively breastfed',
			'rules' => ''
		),
		array(
			'field' => 'bf_2',
			'label' => '2nd Month the Child was exclusively breastfed',
			'rules' => ''
		),
		array(
			'field' => 'bf_3',
			'label' => '3rd Month the Child was exclusively breastfed',
			'rules' => ''
		),
		array(
			'field' => 'bf_4',
			'label' => '4th Month the Child was exclusively breastfed',
			'rules' => ''
		),
		array(
			'field' => 'bf_5',
			'label' => '5th Month the Child was exclusively breastfed',
			'rules' => ''
		),
		array(
			'field' => 'bf_6',
			'label' => 'Date for 6th Month the Child was exclusively breastfed',
			'rules' => ''
		),
		array(
			'field' => 'remarks',
			'label' => 'Remarks',
			'rules' => 'xss_clean|trim'
		)
	
	);


  /**
	 * Constructor method
	 *
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();

		// Load the required classes
		$this->load->model(array('cuo_m','clients/clients_m', 'clients/clients_region_m', 'clients/clients_province_m', 'clients/clients_city_m', 'clients/clients_barangay_m'));
		$this->load->library('form_validation');
        $this->load->library('fpdf');
		$this->lang->load(array('cuo','clients/clients'));     
		//$this->output->enable_profiler(TRUE);
		
		$this->data->methods = array();
		if ($methods = $this->cuo_m->get_method())
		{
			foreach ($methods as $method)
			{
				$this->data->methods[$method->id] = $method->method;
			}
		}		
		$this->data->client_types = array(
                  'CU'  => 'Current Users',
                  'NA'  => 'New Acceptors',
                  'CM'  => 'Changing Method',
                  'CC'  => 'Changing Clinic',
                  'RS'  => 'Restart');
    
		$this->data->diagnosis_list = array(
						      ''  => '-- Please Select --',
                  'A'  => 'Measles',
                  'B'    => 'Severe Pneumonia',
                  'C'    => 'Persistent Diarrhea',
                  'D'    => 'Malnutrition',
                  'E'    => 'Xerophthalmia',
                  'F'    => 'Night Blindness',
                  'G'    => 'Bitot\'s spots',
                  'H'    => 'Corneal Xerosis',
                  'I'    => 'Corneal Ulcerations',
                  'J'    => 'Keratomalacia'
                );
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
	 * Create a new client role
	 *
	 * @access public
	 * @return void
	 */
	public function index()
	{

		if($this->session->userdata('selected_client_id'))
		redirect('admin/clients/view/'.$this->session->userdata('selected_client_id'));
		
        //determine active param
		$base_where['gender'] = $this->input->post('f_module') ? $this->input->post('f_gender') : '';
    
    //barangay param   
    $base_where = $this->input->post('f_barangays') ? $base_where + array('barangay' => $this->input->post('f_barangays')) : $base_where;
        
		//year param    
		$base_where = $this->input->post('by_year') ? $base_where + array('by_year' => $this->input->post('by_year')) : $base_where;
        
        //status param
		$base_where = $this->input->post('f_status') ? $base_where + array('status' => $this->input->post('f_status')) : $base_where;
    
        //age parameters
        $base_where = $this->input->post('f_age') ? $base_where + array('age' => $this->input->post('f_age')) : $base_where;
    
		//keyphrase param
		$base_where = $this->input->post('f_keywords') ? $base_where + array('name' => $this->input->post('f_keywords')) : $base_where;

		//Set parameters session
        $this->session->set_userdata('base_where',$base_where);
    
        // Create pagination links
		$pagination = create_pagination('admin/children_under_one/index', $this->cuo_m->count_by($base_where));
   
        $year = date("Y");
        for($i=$year; $i >= $year - 10; $i--)
        {
            $years[$i]  = $i;
        }
        $this->data->year = $year;
		$this->data->years= $years;

		// Using this data, get the relevant results
		$clients = $this->cuo_m->get_results($pagination['limit'],$base_where);
        
        $this->data->totalitems = $this->cuo_m->count_by($base_where);
        $lastpage = $pagination['current_page']+$pagination['per_page'];
        
        $this->data->item_start = $pagination['current_page']+1;
        
        if($lastpage>$this->data->totalitems)
        {
            $lastpagediff = $lastpage-$this->data->totalitems;
            $this->data->item_end = ($pagination['current_page']+$pagination['per_page'])-$lastpagediff;
        }
        else    
        {
            $this->data->item_end = $pagination['current_page']+$pagination['per_page'];
        }

		//unset the layout if we have an ajax request
		if ($this->input->is_ajax_request()) $this->template->set_layout(FALSE);

		// Render the view
		$this->template
			->title($this->module_details['name'])
			->set('pagination', $pagination)
			->set('clients', $clients)      
			->set_partial('filters', 'admin/partials/filters')   
			->append_metadata(js('admin/filter.js'))
      ->set_partial('cuo-list', 'admin/tables/clients');
       				
		$this->input->is_ajax_request() ? $this->template->build('admin/tables/clients', $this->data) : $this->template->build('admin/index', $this->data);	 
	}


		/**
	 * Method for handling different form actions
	 * @access public
	 * @return void
	 */
	public function action()
	{
		// Determine the type of action
		switch ($this->input->post('btnAction'))
		{
			case 'delete':
				$this->delete();
				break;
			default:
				redirect('admin/family');
				break;
		}
	}

  public function add_client()
	{		
                $this->session->set_flashdata('warning', 'Search and Select a Client to be Added to "Children Under 1 Year Old" Or <a href="admin/clients/add" class="success-link">Register</a> a New Client.'); 
                $this->session->set_userdata('current_action', 'cuo'); 
                redirect('admin/clients');
	}
	
	public function select_client($client_id)
	{		
               	  if($id = $this->cuo_m->check_cuo_exist($client_id))
               	  {	  
                   redirect('admin/children_under_one/edit/'.$id);
                  }
                  redirect('admin/children_under_one/add/'.$client_id);
	}
	
	/**
	 * Create a new client role
	 *
	 * @access public
	 * @return void
	 */
	public function add($client_id)
	{		
    $this->session->unset_userdata('current_action'); 
    if($this->session->userdata('consultation_id') != $client_id)
    {
     $this->session->set_flashdata('warning', 'Please Add New Consultation for this Client before Adding Record for Children Under 1');
     redirect('admin/consultations/add/'.$client_id);
    } 
    
    $client = $this->clients_m->get($client_id);
    $client or redirect('admin/children_under_one');
    
    $this->form_validation->set_rules($this->validation_rules);
		
    if ($_POST)
		{
      // Loop through each POST item and add it to the secure_post array
      $secure_post = $this->input->post();
        
      $dates = array('nb_referral_date','nb_done_date','cp_date_assess','ms_a_date_given'
                      ,'ms_iron_date_started','ms_iron_date_completed','im_bcg','im_hepa_b_at_birth','im_pentavalent_1','im_pentavalent_2','im_pentavalent_3','im_dpt1'
                      ,'im_dpt2','im_dpt3','im_polio1','im_polio2','im_polio3','im_rotarix1','im_rotarix2','im_hepa_b1_with_in','im_hepa_b1_more_than','im_hepa_b2','im_hepa_b3'
                      ,'im_anti_measles','im_mmr','im_fully','bf_6');                
  		foreach ($dates as $date_field)
  		{
  			if ($this->input->post($date_field))
    		{
    			$secure_post[$date_field] = strtotime($this->input->post($date_field));  
    		}
    		else
    		{
    			$secure_post[$date_field] = '';  
    		}
  		}	
      

          		if ($this->form_validation->run() !== FALSE)
          		{ 
                                if($this->cuo_m->insert($secure_post))
                                {
                                  $this->session->set_flashdata('success', 'New Record Added Under 1 Year Old');
                                  $this->session->set_userdata('consultation_id', 0);
                                  if($this->session->userdata('selected_client_id'))
                                    redirect('admin/clients/view/'.$this->session->userdata('selected_client_id'));
                                  else
                                  redirect('admin/children_under_one');
                                }
                                else 
                                {
                                $this->session->set_flashdata('error', 'Error Adding Child');
                                redirect("admin/children_under_one/add/$client_id");
                                }
                                
                                
          		}
          		else
          		{
          			// Dirty hack that fixes the issue of having to re-add all data upon an error
          			$cuo = (object) $_POST;
          		}      
		}
                // Loop through each validation rule
		foreach ($this->validation_rules as $rule)
		{
			$cuo->{$rule['field']} = set_value($rule['field']);
		}
		

		$cuo->method_action = 'add';
		$cuo->client_id = $client_id;

		// Render the view
		$this->data->cuo = & $cuo;
		$this->data->client = & $client;
		$this->data->client->barangay_name = & $this->clients_barangay_m->get_name($client->barangay_id);;
		$this->data->client->province_name = & $this->clients_province_m->get_name($client->province_id);;
		$this->data->client->region_name = & $this->clients_region_m->get_name($client->region_id);;
		$this->data->client->city_name = & $this->clients_city_m->get_name($client->city_id);;
		
		$this->template
				->title($this->module_details['name'], lang('user_add_title'))
				->build('admin/form',$this->data);
	}

  public function set($type = 'default', $client_id = 0)
  {  
    if($type == 'default')
    {
        $this->session->set_userdata('selected_client_id','');
        $this->session->set_userdata('set', $type);
        redirect("admin/children_under_one");
    }
    $this->session->set_userdata('selected_client_id',$client_id);
    $this->session->set_userdata('set', $type);  
   
    if($id = $this->cuo_m->check_cuo_exist($client_id))
      redirect('admin/children_under_one/edit/'.$id);
    else
      redirect('admin/children_under_one/add/'.$client_id);
  }

	/**
	 * Edit a client role
	 *
	 * @access public
	 * @param int $id The ID of the client to edit
	 * @return void
	 */
	public function edit($id = 0)
	{
	  $cuo = $this->cuo_m->get_cuo_details($id);
	  
		$client = $this->clients_m->get($cuo->client_id);
    $client or redirect('admin/children_under_one');
    
    $this->form_validation->set_rules($this->validation_rules);
		
    
    if ($_POST)
		{
        // Loop through each POST item and add it to the secure_post array
        $secure_post = $this->input->post();
      
          
        $dates = array('nb_referral_date','nb_done_date','cp_date_assess','ms_a_date_given'
                      ,'ms_iron_date_started','ms_iron_date_completed','im_bcg','im_hepa_b_at_birth','im_pentavalent_1','im_pentavalent_2','im_pentavalent_3','im_dpt1'
                      ,'im_dpt2','im_dpt3','im_polio1','im_polio2','im_polio3','im_rotarix1','im_rotarix2','im_hepa_b1_with_in','im_hepa_b1_more_than','im_hepa_b2','im_hepa_b3'
                      ,'im_anti_measles','im_mmr','im_fully','bf_6');              
  		foreach ($dates as $date_field)
  		{
  			if ($this->input->post($date_field))
    		{
    			$secure_post[$date_field] = strtotime($this->input->post($date_field));  
    		}
    		else
    		{
    			$secure_post[$date_field] = '';  
    		}
  		}	
                
          		if ($this->form_validation->run() !== FALSE)
          		{ 
                                if($this->cuo_m->update($id, $secure_post))
                                {
                                  $this->session->set_flashdata('success', 'Child successfully updated');
                                  if($this->session->userdata('selected_client_id'))
                                    redirect('admin/clients/view/'.$this->session->userdata('selected_client_id'));
                                  else
                                    redirect('admin/children_under_one');
                                }
                                else 
                                {
                                  $this->session->set_flashdata('error', 'Error updating Children/No Data has been entered.');
                                  redirect("admin/children_under_one/edit/$id");
                                }
                                
                                
          		}
          		else
          		{
          			// Dirty hack that fixes the issue of having to re-add all data upon an error
          			$cuo = (object) $_POST;
          		}
          		foreach ($this->validation_rules as $rule)
          		{
          			$cuo->{$rule['field']} = set_value($rule['field']);
          		}
		}
		else
		{
              $dates = array('nb_referral_date','nb_done_date','cp_date_assess','ms_a_date_given'
                      ,'ms_iron_date_started','ms_iron_date_completed','im_bcg','im_hepa_b_at_birth','im_pentavalent_1','im_pentavalent_2','im_pentavalent_3','im_dpt1'
                      ,'im_dpt2','im_dpt3','im_polio1','im_polio2','im_polio3','im_rotarix1','im_rotarix2','im_hepa_b1_with_in','im_hepa_b1_more_than','im_hepa_b2','im_hepa_b3'
                      ,'im_anti_measles','im_mmr','im_fully','bf_6');              
          		foreach ($dates as $date_field)
          		{
                    $cuo->{$date_field}= $cuo->{$date_field} ? date('Y-m-d', $cuo->{$date_field}) : ''; 
          		}	
    }

    $cuo->method_action = 'edit';
		// Render the view
		$this->data->cuo = & $cuo;
		$this->data->client = & $client;
		$this->data->client->barangay_name = & $this->clients_barangay_m->get_name($client->barangay_id);;
		$this->data->client->province_name = & $this->clients_province_m->get_name($client->province_id);;
		$this->data->client->region_name = & $this->clients_region_m->get_name($client->region_id);;
		$this->data->client->city_name = & $this->clients_city_m->get_name($client->city_id);;
		
		$this->template
				->title($this->module_details['name'], lang('user_add_title'))
				->build('admin/form',$this->data);
	}

	/**
	 * Delete client role(s)
	 *
	 * @access public
	 * @param int $id The ID of the client to delete
	 * @return void
	 */
	public function delete($id = 0)
	{
		if($id == 0)
		{
        $ids = $this->input->post('action_to');
    
    		if (!empty($ids))
    		{
    			$deleted = 0;
    			$to_delete = 0;
    			foreach ($ids as $id)
    			{
    				if ($this->cuo_m->delete($id))
    				{
    					$deleted++;
    				}
    				$to_delete++;
    			}
    
    			if ($to_delete > 0)
    			{
    				$this->session->set_flashdata('success', sprintf($this->lang->line('cuo_mass_delete_success'), $deleted, $to_delete));
    			}
    		}
    		// The array of id's to delete is empty
    		else
    			$this->session->set_flashdata('error', $this->lang->line('cuo.delete_error'));
    }
    else
    {
        $this->cuo_m->delete($id)
    			? $this->session->set_flashdata('success', lang('cuo.delete_success'))
    			: $this->session->set_flashdata('error', lang('cuo.delete_error'));
		}
    if($this->session->userdata('selected_client_id'))
      redirect('admin/clients/view/'.$this->session->userdata('selected_client_id'));
    else
		redirect('admin/children_under_one');
	}
    
    public function print_page()
	{
		$base_where = $this->session->userdata('base_where');                                                      
  
		// Create pagination links
		$pagination = create_pagination('admin/children_under_one/index', $this->cuo_m->count_by($base_where));

		// Using this data, get the relevant results
		$clients = $this->cuo_m->get_results($pagination['limit'],$base_where);
        $totalitems = count($clients);
        if($totalitems<1)
            {$totalitems = 1;}
        $totalpage = $totalitems/17;
        $remainder = $totalitems%17;
        if($remainder!=0)
        {
            if($remainder>=9)
            {$totalpages=number_format($totalpage);}
            else
            {$totalpages=number_format($totalpage)+1;}
        }   
        
        $this->fpdf->Open();
        $this->fpdf->AliasNbPages();
        $this->fpdf->AddPage('L','A4');
        $this->fpdf->SetAutoPageBreak(true, 10);
                            
        $this->fpdf->Image(base_url().'system/cms/themes/pyrocms/img/logo_small.png',10,5,40);
        $this->fpdf->SetY(10);
        $this->fpdf->SetFont('Helvetica','B',16);
        $this->fpdf->SetTextColor(0,0,0);
        $this->fpdf->Cell(0,4,strtoupper('Client List for Children Under 1 Year Old'),0,0,'C');
        
        $this->header_page1();
               
        $i = 1;
        $x = 1;
        $currentpage = 1;
        foreach ($clients as $client)
        {                             
                $age = floor((time() - $client->dob)/31556926); 
                if(!$age)
                    {$age = ' ';} 
                    
                $brgy = $this->clients_barangay_m->get_name($client->barangay_id);
                    
                if(!$client->address)
                        {$address = $brgy;} 
                elseif(!$client->residence)
                        {$address = $client->address;} 
                else 
                        {$address = $brgy.', '.$client->address;}
                
                $dob = $client->dob ? date('m-d-y', $client->dob): ' ';
                $registration_date = $client->registration_date ? date('m-d-y', $client->registration_date): ' ';
                
                $nb_referral_date = $client->nb_referral_date ? date('Y-m-d', $client->nb_referral_date): ' ';
                $nb_done_date = $client->nb_done_date ? date('Y-m-d', $client->nb_done_date): ' ';
                $cp_date_assess = $client->cp_date_assess ? date('Y-m-d', $client->cp_date_assess): ' ';                   
                $ms_a_date_given = $client->ms_a_date_given ? date('Y-m-d', $client->ms_a_date_given): ' ';
                $ms_iron_date_started = $client->ms_iron_date_started ? date('Y-m-d', $client->ms_iron_date_started): ' ';
                $ms_iron_date_completed = $client->ms_iron_date_completed ? date('Y-m-d', $client->ms_iron_date_completed): ' ';                 
                $im_bcg = $client->im_bcg ? date('Y-m-d', $client->im_bcg): ' ';
                $im_anti_measles = $client->im_anti_measles ? date('Y-m-d', $client->im_anti_measles): ' ';                 
                         
                $this->fpdf->SetFillColor(230,230,230);             
                $modx = $x%2;
                if($modx==0) 
                {$fill=false;}
                else
                {$fill=true;}
                
                $this->fpdf->SetFont('Helvetica','B',7);
                $this->fpdf->Cell(10,8,$x.'.',1,0,'L',$fill);
                $this->fpdf->SetFont('Helvetica','',7);
                $this->fpdf->Cell(15,8,$registration_date,1,0,'C',$fill);
                $this->fpdf->Cell(15,8,$dob,1,0,'C',$fill);
                $this->fpdf->SetFont('Helvetica','',6);
                $this->fpdf->Cell(17,8,$client->serial_number,1,0,'L',$fill); 
                $this->fpdf->SetFont('Helvetica','',7);  
                $this->fpdf->Cell(35,8,trim($client->last_name).', '.trim($client->first_name).' '.trim($client->middle_name),1,0,'L',$fill);
                $this->fpdf->Cell(10,8,substr(strtoupper($client->gender),0,1),1,0,'C',$fill); 
                $this->fpdf->Cell(35,8,ucwords($client->mother_name),1,0,'L',$fill);
                $this->fpdf->Cell(20,8,ucwords($brgy),1,0,'L',$fill);    
                $this->fpdf->Cell(15,8,$nb_referral_date,1,0,'C',$fill);
                $this->fpdf->Cell(15,8,$nb_done_date,1,0,'C',$fill);
                $this->fpdf->Cell(15,8,$cp_date_assess,1,0,'C',$fill);
                $this->fpdf->Cell(10,8,strtoupper($client->cp_tt_status),1,0,'C',$fill);
                $this->fpdf->Cell(10,8,$client->ms_a_age_months,1,0,'C',$fill);
                $this->fpdf->Cell(15,8,$ms_a_date_given,1,0,'C',$fill);
                $this->fpdf->Cell(10,8,$client->ms_iron_birth_weight,1,0,'C',$fill); 
                $this->fpdf->Cell(15,8,$ms_iron_date_started,1,0,'C',$fill);
                $this->fpdf->Cell(15,8,$ms_iron_date_completed,1,1,'C',$fill);
                
                if($i==17)
                {
                    if($x<$totalitems)
                    {                       
                        $this->tcl_header();
                        $this->legend_page1();
                        $this->footer();
                        $this->fpdf->SetY(-15);
                        $this->fpdf->SetTextColor(128);
                        $this->fpdf->SetFont('Helvetica','I',6.5);
                        $this->fpdf->Cell(10,4,'Page '. $currentpage.'/'.$totalpages,0,0,'C');
        
                        $i = 1;
                        $currentpage = $currentpage + 1;
                        $this->fpdf->SetTextColor(0,0,0);
                    
                        $this->fpdf->AliasNbPages();
                        $this->fpdf->AddPage('L','A4');
                        $this->fpdf->SetAutoPageBreak(true, 10);
                                            
                        $this->fpdf->Image(base_url().'system/cms/themes/pyrocms/img/logo_small.png',10,5,40);
                        $this->fpdf->SetY(10);
                        $this->fpdf->SetFont('Helvetica','B',16);
                        $this->fpdf->SetTextColor(0,0,0);
                        $this->fpdf->Cell(0,4,strtoupper('Children Under 1 Year Old Client\'s List'),0,0,'C');
                        
                        $this->header_page1();
                        
                    }
                }
                else
                {
                    $i=$i+1;
                } 
                $x=$x+1;          
        }
        
        $this->tcl_header();
        $this->legend_page1();
        $this->footer();
        $this->fpdf->SetY(-15);
        $this->fpdf->SetTextColor(128);
        $this->fpdf->SetFont('Helvetica','I',6.5);
        $this->fpdf->Cell(10,4,'Page '. $currentpage.'/'.$totalpages,0,0,'C');
        
    //============================ Second Page ================================//
    
        $this->fpdf->AliasNbPages();
        $this->fpdf->AddPage('L','A4');
        $this->fpdf->SetAutoPageBreak(true, 10);
                            
        $this->fpdf->Image(base_url().'system/cms/themes/pyrocms/img/logo_small.png',10,5,40);
        $this->fpdf->SetY(10);
        $this->fpdf->SetFont('Helvetica','B',16);
        $this->fpdf->SetTextColor(0,0,0);
        $this->fpdf->Cell(0,4,strtoupper('Client List for Children Under 1 Year Old'),0,0,'C');
        
        $this->header_page2();
               
        $i = 1;
        $x = 1;
        $currentpage = 1;
        foreach ($clients as $client)
        {                                                     
                $im_bcg = $client->im_bcg ? date('Y-m-d', $client->im_bcg): ' ';
                
                $im_dpt1 = $client->im_dpt1 ? date('Y-m-d', $client->im_dpt1): ' ';
                $im_dpt2 = $client->im_dpt2 ? date('Y-m-d', $client->im_dpt2): ' ';
                $im_dpt3 = $client->im_dpt3 ? date('Y-m-d', $client->im_dpt3): ' ';                   
                
                $im_polio1 = $client->im_polio1 ? date('Y-m-d', $client->im_polio1): ' ';
                $im_polio2 = $client->im_polio2 ? date('Y-m-d', $client->im_polio2): ' ';
                $im_polio3 = $client->im_polio3 ? date('Y-m-d', $client->im_polio3): ' ';
                
                $im_hepa_b1_with_in = $client->im_hepa_b1_with_in ? date('Y-m-d', $client->im_hepa_b1_with_in): ' ';
                $im_hepa_b1_more_than = $client->im_hepa_b1_more_than ? date('Y-m-d', $client->im_hepa_b1_more_than): ' ';
                $im_hepa_b2 = $client->im_hepa_b2 ? date('Y-m-d', $client->im_hepa_b2): ' '; 
                $im_hepa_b3 = $client->im_hepa_b3 ? date('Y-m-d', $client->im_hepa_b3): ' ';       
                
                $im_fully = $client->im_fully ? date('Y-m-d', $client->im_fully): ' ';        
                
                $bf_1 = $client->bf_1 ? 'Y' : ' '; 
                $bf_2 = $client->bf_2 ? 'Y' : ' '; 
                $bf_3 = $client->bf_3 ? 'Y' : ' '; 
                $bf_4 = $client->bf_4 ? 'Y' : ' '; 
                $bf_5 = $client->bf_5 ? 'Y' : ' '; 
                
                $bf_6 = $client->bf_6 ? date('Y-m-d', $client->bf_6): ' ';  
                
                $this->fpdf->SetFillColor(230,230,230);             
                $modx = $x%2;
                if($modx==0) 
                {$fill=false;}
                else
                {$fill=true;}               
                         
                $this->fpdf->SetFont('Helvetica','B',7);
                $this->fpdf->Cell(10,8,$x.'.',1,0,'L',$fill);
                $this->fpdf->SetFont('Helvetica','',7);
                $this->fpdf->Cell(15,8,$im_bcg,1,0,'C',$fill);
                $this->fpdf->Cell(15,8,$im_dpt1,1,0,'C',$fill);
                $this->fpdf->Cell(15,8,$im_dpt2,1,0,'C',$fill);   
                $this->fpdf->Cell(15,8,$im_dpt3,1,0,'C',$fill);
                $this->fpdf->Cell(15,8,$im_polio1,1,0,'C',$fill);
                $this->fpdf->Cell(15,8,$im_polio2,1,0,'C',$fill);
                $this->fpdf->Cell(15,8,$im_polio3,1,0,'C',$fill);    
                $this->fpdf->Cell(15,8,$im_hepa_b1_with_in,1,0,'C',$fill);
                $this->fpdf->Cell(15,8,$im_hepa_b1_more_than,1,0,'C',$fill);
                $this->fpdf->Cell(15,8,$im_hepa_b2,1,0,'C',$fill);
                $this->fpdf->Cell(15,8,$im_hepa_b3,1,0,'C',$fill);
                $this->fpdf->Cell(15,8,$im_anti_measles,1,0,'C',$fill);
                $this->fpdf->Cell(20,8,$im_fully,1,0,'C',$fill);    
                $this->fpdf->Cell(10,8,$bf_1,1,0,'C',$fill);
                $this->fpdf->Cell(10,8,$bf_2,1,0,'C',$fill);
                $this->fpdf->Cell(10,8,$bf_3,1,0,'C',$fill);
                $this->fpdf->Cell(10,8,$bf_4,1,0,'C',$fill);
                $this->fpdf->Cell(10,8,$bf_5,1,0,'C',$fill);
                $this->fpdf->Cell(17,8,$bf_6,1,1,'C',$fill);
                
                if($i==17)
                {
                    if($x<$totalitems)
                    {                       
                        $this->legend_page2();
                        $this->footer();
                        $this->fpdf->SetY(-15);
                        $this->fpdf->SetTextColor(128);
                        $this->fpdf->SetFont('Helvetica','I',6.5);
                        $this->fpdf->Cell(10,4,'Page '. $currentpage.'/'.$totalpages,0,0,'C');
        
                        $i = 1;
                        $currentpage = $currentpage + 1;
                        $this->fpdf->SetTextColor(0,0,0);
                    
                        $this->fpdf->AliasNbPages();
                        $this->fpdf->AddPage('L','A4');
                        $this->fpdf->SetAutoPageBreak(true, 10);
                                            
                        $this->fpdf->Image(base_url().'system/cms/themes/pyrocms/img/logo_small.png',10,5,40);
                        $this->fpdf->SetY(10);
                        $this->fpdf->SetFont('Helvetica','B',16);
                        $this->fpdf->SetTextColor(0,0,0);
                        $this->fpdf->Cell(0,4,strtoupper('Children Under 1 Year Old Client\'s List'),0,0,'C');
                        
                        $this->header_page2();
                        
                    }
                }
                else
                {
                    $i=$i+1;
                } 
                $x=$x+1;          
        }
        
        $this->legend_page2();
        $this->footer();
        $this->fpdf->SetY(-15);
        $this->fpdf->SetTextColor(128);
        $this->fpdf->SetFont('Helvetica','I',6.5);
        $this->fpdf->Cell(10,4,'Page '. $currentpage.'/'.$totalpages,0,0,'C');
        
        $this->fpdf->Output('TCL_Child_Under_1.pdf','I');        
	}
    
    function footer()
    {
        $year = date('Y');
        //Draw a line
        $this->fpdf->SetY(-15);
        
        //Page footer
        $this->fpdf->SetY(-15);
        $this->fpdf->SetTextColor(128);
        $this->fpdf->SetFont('Helvetica','I',6.5);
        //$this->fpdf->Cell(10,4,'Page '.$this->fpdf->PageNo().'/{nb}',0,0,'C');
        $this->fpdf->SetFont('Helvetica','',6.5);
        $this->fpdf->Cell(0,4,'Copyright © '.$year.' IMPROVHEALTH | Provided by: Touch Foundation Inc.',0,0,'C');
    } 
    
    function tcl_header()
    {
        //Draw a line
        $this->fpdf->SetY(5);
        $this->fpdf->SetX(255);
        
        $this->fpdf->SetTextColor(0,0,0);
        $this->fpdf->SetFont('Helvetica','B',20);
        $this->fpdf->Cell(30,10,'TCL - <1',0,1,'C');
    } 
    
    function header_page1()
    {
        $this->fpdf->SetY(15);
        $this->fpdf->SetFillColor(208,208,255);
        $fill = true;
        $this->fpdf->SetFont('Helvetica','',8);
        $this->fpdf->Cell(10,4,'','LTRb',0,'C',$fill);
        $this->fpdf->Cell(15,4,'DATE OF','LTRb',0,'C',$fill);
        $this->fpdf->Cell(15,4,'DATE OF','LTRb',0,'C',$fill);
        $this->fpdf->Cell(17,4,'FAMILY','LTRb',0,'C',$fill);
        $this->fpdf->Cell(35,4,'','LTRb',0,'C',$fill);
        $this->fpdf->Cell(10,4,'','LTRb',0,'C',$fill);
        $this->fpdf->Cell(35,4,'','LTRb',0,'C',$fill);
        $this->fpdf->Cell(20,4,'','LTRb',0,'C',$fill);
        $this->fpdf->Cell(30,4,'DATE','LTRb',0,'C',$fill);
        $this->fpdf->Cell(25,4,'CHILD PROTEC-','LTRb',0,'C',$fill);
        $this->fpdf->Cell(65,4,'MICRONUTRIENT SUPPLEMENTATION','LTRB',1,'C',$fill);
        
        $this->fpdf->SetFont('Helvetica','B',8);
        $this->fpdf->Cell(10,4,'NO.','LtRb',0,'C',$fill);
        $this->fpdf->SetFont('Helvetica','',8);
        $this->fpdf->Cell(15,4,'REGIS-','LtRb',0,'C',$fill);
        $this->fpdf->Cell(15,4,'BIRTH','LtRb',0,'C',$fill);
        $this->fpdf->Cell(17,4,'SERIAL','LtRb',0,'C',$fill);
        $this->fpdf->Cell(35,4,'NAME OF CHILD','LtRb',0,'C',$fill);
        $this->fpdf->Cell(10,4,'SEX','LtRb',0,'C',$fill);
        $this->fpdf->Cell(35,4,'COMPLETE NAME','LtRb',0,'C',$fill);
        $this->fpdf->Cell(20,4,'ADDRESS','LtRb',0,'C',$fill);
        $this->fpdf->Cell(30,4,'NEWBORN','LtRb',0,'C',$fill);
        $this->fpdf->Cell(25,4,'TED AT BIRTH','LtRb',0,'C',$fill);
        $this->fpdf->SetFont('Helvetica','',7);
        $this->fpdf->Cell(65,4,'(7)','LtRB',1,'C',$fill);
        
        $this->fpdf->SetFont('Helvetica','',8);
        $this->fpdf->Cell(10,4,'','LtRb',0,'C',$fill);
        $this->fpdf->Cell(15,4,'TRATION','LtRb',0,'C',$fill);
        $this->fpdf->Cell(15,4,'','LtRb',0,'C',$fill);
        $this->fpdf->Cell(17,4,'NUMBER','LtRb',0,'C',$fill);
        $this->fpdf->Cell(35,4,'','LtRb',0,'C',$fill);
        $this->fpdf->Cell(10,4,'','LtRb',0,'C',$fill);
        $this->fpdf->Cell(35,4,'OF MOTHER','LtRb',0,'C',$fill);
        $this->fpdf->Cell(20,4,'','LtRb',0,'C',$fill);
        $this->fpdf->Cell(30,4,'SCREENING','LtRb',0,'C',$fill);
        $this->fpdf->SetFont('Helvetica','',7);
        $this->fpdf->Cell(25,4,'(6)',1,0,'C',$fill);
        $this->fpdf->SetFont('Helvetica','',7);
        $this->fpdf->Cell(25,4,'VITAMIN A','LTRb',0,'C',$fill);
        $this->fpdf->Cell(40,4,'IRON','LTRb',1,'C',$fill);
        $this->fpdf->SetFont('Helvetica','',7);
        
        $this->fpdf->SetFont('Helvetica','',7);
        $this->fpdf->Cell(10,4,'','LtRb',0,'C',$fill);
        $this->fpdf->Cell(15,4,'','LtRb',0,'C',$fill);
        $this->fpdf->Cell(15,4,'','LtRb',0,'C',$fill);
        $this->fpdf->Cell(17,4,'','LtRb',0,'C',$fill);
        $this->fpdf->Cell(35,4,'','LtRb',0,'C',$fill);
        $this->fpdf->Cell(10,4,'','LtRb',0,'C',$fill);
        $this->fpdf->Cell(35,4,'','LtRb',0,'C',$fill);
        $this->fpdf->Cell(20,4,'','LtRb',0,'C',$fill);
        $this->fpdf->SetFont('Helvetica','',7);
        $this->fpdf->Cell(30,4,'(5)',1,0,'C',$fill);
        $this->fpdf->SetFont('Helvetica','',6);
        $this->fpdf->Cell(15,4,'DATE','LTRb',0,'C',$fill);
        $this->fpdf->Cell(10,4,'TT','LTRb',0,'C',$fill);
        $this->fpdf->Cell(10,4,'AGE IN','LTRb',0,'C',$fill);
        $this->fpdf->Cell(15,4,'DATE','LTRb',0,'C',$fill);
        $this->fpdf->Cell(10,4,'BIRTH','LTRb',0,'C',$fill);
        $this->fpdf->Cell(15,4,'DATE','LTRb',0,'C',$fill);
        $this->fpdf->Cell(15,4,'DATE','LTRb',1,'C',$fill);
        $this->fpdf->SetFont('Helvetica','',6);
        
        $this->fpdf->SetFont('Helvetica','',7);
        $this->fpdf->Cell(10,4,'(1)','LtRb',0,'C',$fill);
        $this->fpdf->Cell(15,4,'','LtRB',0,'C',$fill);
        $this->fpdf->Cell(15,4,'','LtRB',0,'C',$fill);
        $this->fpdf->Cell(17,4,'','LtRB',0,'C',$fill);
        $this->fpdf->Cell(35,4,'(2)','LtRb',0,'C',$fill);
        $this->fpdf->Cell(10,4,'','LtRB',0,'C',$fill);
        $this->fpdf->Cell(35,4,'(3)','LtRb',0,'C',$fill);
        $this->fpdf->Cell(20,4,'','LtRB',0,'C',$fill);
        $this->fpdf->Cell(15,4,'REFERRAL',1,0,'C',$fill);
        $this->fpdf->Cell(15,4,'DONE',1,0,'C',$fill);
        $this->fpdf->SetFont('Helvetica','',6);
        $this->fpdf->Cell(15,4,'ASSESS','LtRB',0,'C',$fill);
        $this->fpdf->Cell(10,4,'STATUS','LtRB',0,'C',$fill);
        $this->fpdf->Cell(10,4,'MONTHS','LtRB',0,'C',$fill);
        $this->fpdf->Cell(15,4,'GIVEN','LtRB',0,'C',$fill);
        $this->fpdf->Cell(10,4,'WEIGHT','LtRB',0,'C',$fill);
        $this->fpdf->Cell(15,4,'STARTED','LtRB',0,'C',$fill);
        $this->fpdf->Cell(15,4,'COMPLETED','LtRB',1,'C',$fill); 
        $this->fpdf->SetFont('Helvetica','',7);        
            
    }          
    
    function header_page2()
    {
        $this->fpdf->SetY(15);
        $this->fpdf->SetFillColor(208,208,255);
        $fill = true;
        $this->fpdf->SetFont('Helvetica','',8);
        $this->fpdf->Cell(10,4,'','LTRb',0,'C',$fill);
        $this->fpdf->Cell(180,4,'DATE IMMUNIZATION RECEIVED','LTRb',0,'C',$fill);
        $this->fpdf->Cell(20,4,'DATE','LTRb',0,'C',$fill);
        $this->fpdf->Cell(67,4,'CHILD WAS EXCLUSIVELY BREASTFED','LTRb',1,'C',$fill);
        
        $this->fpdf->Cell(10,4,'NO.','LtRb',0,'C',$fill);
        $this->fpdf->Cell(180,4,'(11)','LtRB',0,'C',$fill);
        $this->fpdf->Cell(20,4,'FULLY','LtRb',0,'C',$fill);
        $this->fpdf->Cell(67,4,'(13)','LtRB',1,'C',$fill);
        
        $this->fpdf->SetFont('Helvetica','',8);
        $this->fpdf->Cell(10,4,'','LtRb',0,'C',$fill);
        $this->fpdf->Cell(15,4,'','LTRb',0,'C',$fill);
        $this->fpdf->Cell(15,4,'','LTRb',0,'C',$fill);
        $this->fpdf->Cell(15,4,'','LTRb',0,'C',$fill);
        $this->fpdf->Cell(15,4,'','LTRb',0,'C',$fill);
        $this->fpdf->Cell(15,4,'','LTRb',0,'C',$fill);
        $this->fpdf->Cell(15,4,'','LTRb',0,'C',$fill);
        $this->fpdf->Cell(15,4,'','LTRb',0,'C',$fill);
        $this->fpdf->Cell(30,4,'HEPA B1','LTRB',0,'C',$fill);
        $this->fpdf->Cell(15,4,'HEPA','LTRb',0,'C',$fill);
        $this->fpdf->Cell(15,4,'HEPA','LTRb',0,'C',$fill);
        $this->fpdf->Cell(15,4,'ANTI-','LTRb',0,'C',$fill);
        $this->fpdf->Cell(20,4,'IMMUNIZED','LtRb',0,'C',$fill);
        $this->fpdf->Cell(50,4,'Put (Y) if Yes','LTRB',0,'C',$fill);
        $this->fpdf->Cell(17,4,'Put a','LTRb',1,'C',$fill);
        
        $this->fpdf->SetFont('Helvetica','',7);
        $this->fpdf->Cell(10,4,'','LtRb',0,'C',$fill);
        $this->fpdf->Cell(15,4,'BCG','LtRb',0,'C',$fill);
        $this->fpdf->Cell(15,4,'DPT1','LtRb',0,'C',$fill);
        $this->fpdf->Cell(15,4,'DPT2','LtRb',0,'C',$fill);
        $this->fpdf->Cell(15,4,'DPT3','LtRb',0,'C',$fill);
        $this->fpdf->Cell(15,4,'POLIO1','LtRb',0,'C',$fill);
        $this->fpdf->Cell(15,4,'POLIO2','LtRb',0,'C',$fill);
        $this->fpdf->Cell(15,4,'POLIO3','LtRb',0,'C',$fill);
        $this->fpdf->Cell(15,4,'W/IN','LTRb',0,'C',$fill);
        $this->fpdf->Cell(15,4,'More than','LTRb',0,'C',$fill);
        $this->fpdf->Cell(15,4,'B2','LtRb',0,'C',$fill);
        $this->fpdf->Cell(15,4,'B3','LtRb',0,'C',$fill);
        $this->fpdf->Cell(15,4,'MEASLES','LtRb',0,'C',$fill);
        $this->fpdf->Cell(20,4,'(FIC)***','LtRb',0,'C',$fill);
        $this->fpdf->Cell(10,4,'1ST','LTRb',0,'C',$fill);   
        $this->fpdf->Cell(10,4,'2ND','LTRb',0,'C',$fill);
        $this->fpdf->Cell(10,4,'3RD','LTRb',0,'C',$fill);
        $this->fpdf->Cell(10,4,'4TH','LTRb',0,'C',$fill);
        $this->fpdf->Cell(10,4,'5TH','LTRb',0,'C',$fill);
        $this->fpdf->Cell(17,4,'Date','LtRb',1,'C',$fill);
        
        $this->fpdf->SetFont('Helvetica','',7);
        $this->fpdf->Cell(10,4,'(1)','LtRb',0,'C',$fill);
        $this->fpdf->Cell(15,4,'','LtRBb',0,'C',$fill);
        $this->fpdf->Cell(15,4,'','LtRB',0,'C',$fill);
        $this->fpdf->Cell(15,4,'','LtRB',0,'C',$fill);
        $this->fpdf->Cell(15,4,'','LtRB',0,'C',$fill);
        $this->fpdf->Cell(15,4,'','LtRB',0,'C',$fill);
        $this->fpdf->Cell(15,4,'','LtRB',0,'C',$fill);
        $this->fpdf->Cell(15,4,'','LtRB',0,'C',$fill);
        $this->fpdf->Cell(15,4,'24 HOURS','LtRB',0,'C',$fill);
        $this->fpdf->Cell(15,4,'24 HOURS','LtRB',0,'C',$fill);
        $this->fpdf->Cell(15,4,'','LtRB',0,'C',$fill);
        $this->fpdf->Cell(15,4,'','LtRB',0,'C',$fill);
        $this->fpdf->Cell(15,4,'VACCINE','LtRB',0,'C',$fill);
        $this->fpdf->Cell(20,4,'','LtRB',0,'C',$fill);
        $this->fpdf->Cell(10,4,'MO','LtRB',0,'C',$fill);
        $this->fpdf->Cell(10,4,'MO','LtRB',0,'C',$fill);
        $this->fpdf->Cell(10,4,'MO','LtRB',0,'C',$fill);
        $this->fpdf->Cell(10,4,'MO','LtRB',0,'C',$fill);
        $this->fpdf->Cell(10,4,'MO','LtRB',0,'C',$fill);
        $this->fpdf->Cell(17,4,'6th months','LtRB',1,'C',$fill);      
    }  
    
    function legend_page1()
    {
        //Draw a line
        $this->fpdf->SetY(173);
        $this->fpdf->SetX(117);
        $this->fpdf->SetTextColor(0,0,0);
        $this->fpdf->SetFont('Helvetica','',7);
        $this->fpdf->Cell(100,4,'*Child Prot refers to a child whose (1) Mother has received 2 doses  of TT during this',0,'L');
        $this->fpdf->Cell(70,4,'**Dosage is 0.3ml once a day to start at 3 months',0,1,'L');
        $this->fpdf->SetX(127);
        $this->fpdf->Cell(90,4,'pregnancy, provided TT2 was given at least a month prior to delivery, or',0,0,'L');
        $this->fpdf->Cell(2,4,'',0,0,'L');
        $this->fpdf->Cell(60,4,'of age until 6 months when complementary',0,1,'L');
        $this->fpdf->SetX(127);
        $this->fpdf->Cell(90,4,'(2) Mother has received at least 3 doses  of TT anytime prior to pregnancy',0,0,'L');
        $this->fpdf->Cell(2,4,'',0,0,'L');
        $this->fpdf->Cell(60,4,'foods are given. (Preparation s 15 mg.',0,1,'L');
        $this->fpdf->SetX(127);
        $this->fpdf->Cell(90,4,'with this child',0,0,'L');
        $this->fpdf->Cell(2,4,'',0,0,'L');
        $this->fpdf->Cell(60,4,'elemental iron/0.6 ml)',0,1,'L');
        $this->fpdf->SetX(127);
            
    } 
    
    function legend_page2()
    {
        //Draw a line
        $this->fpdf->SetY(173);
        $this->fpdf->SetX(15);
        $this->fpdf->SetTextColor(0,0,0);
        $this->fpdf->SetFont('Helvetica','BI',7);
        $this->fpdf->Cell(45,4,'*** FULLY IMMUNIZED CHILD:',0,0,'L');
        $this->fpdf->SetFont('Helvetica','',7);
        $this->fpdf->Cell(130,4,'= is a child who has recieved the following:',0,0,'L');
        $this->fpdf->SetFont('Helvetica','BI',7);
        $this->fpdf->Cell(30,4,'***Exclusively Brestfed -',0,0,'L');
        $this->fpdf->SetFont('Helvetica','',7);
        $this->fpdf->Cell(45,4,'means no other food (including(',0,1,'L');          
        $this->fpdf->SetX(60);
        $this->fpdf->Cell(130,4,'a) = one dose of BCG at birth or anytime before reching 12 months',0,0,'L');
        $this->fpdf->Cell(15,4,'',0,0,'L');
        $this->fpdf->Cell(60,4,'water) other than breastmilk. Drops of vitamis and',0,1,'L');
        $this->fpdf->SetX(60);
        $this->fpdf->Cell(130,4,'b) = 3 doses each of DPT, OPV and Hepa B as the 3rd dose is given before the child reaches 12 months of age',0,0,'L');
        $this->fpdf->Cell(15,4,'',0,0,'L');
        $this->fpdf->Cell(60,4,'prescribed medication given while breastfeedinf is',0,1,'L');
        $this->fpdf->SetX(60);
        $this->fpdf->Cell(130,4,'c) = One does of anti-measles vaccine before reaching 12 months',0,0,'L');
        $this->fpdf->Cell(15,4,'',0,0,'L');
        $this->fpdf->Cell(60,4,'still "exclusively breastfed".',0,1,'L'); 
    }        
	
}
