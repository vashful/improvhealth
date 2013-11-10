<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Roles controller for the clients module
 *
 * @author Phil Sturgeon - PyroCMS Dev Team
 * @package PyroCMS
 * @subpackage Groups Module
 * @category Modules
 *
 */
class Admin extends Admin_Controller
{
	
  	protected $section = 'prenatals';
  	
        private $validation_rules = array(
		array(
			'field' => 'client_id',
			'label' => 'Client ID',
			'rules' => 'numeric|trim|max_length[10]'
		),
                array(
			'field' => 'last_menstrual_period',
			'label' => 'LMP (Last Menstrual Period)',
			'rules' => 'required'
		),
		array(
			'field' => 'gravida',
			'label' => 'G (Para)',
			'rules' => 'numeric|required'
		),
		array(
			'field' => 'term',
			'label' => 'T (Term)',
			'rules' => 'numeric|required'
		),
		array(
			'field' => 'para',
			'label' => 'P (Para)',
			'rules' => 'numeric|required'
		),
        array(
			'field' => 'abortion',
			'label' => 'A (Abortion)',
			'rules' => 'numeric|required'
		),
        array(
			'field' => 'live',
			'label' => 'L (Live Birth)',
			'rules' => 'numeric|required'
		),
		array(
			'field' => 'estimated_date_confinement',
			'label' => 'EDP (Estimated Date Confinement)',
			'rules' => 'required'
		),
			array(
			'field' => 'prenatal_tri1_v1',
			'label' => 'Prenatal Visit 1 (1st Trimester)',
			'rules' => ''
		),
			array(
			'field' => 'prenatal_tri1_v2',
			'label' => 'Prenatal Visit 2 (1st Trimester)',
			'rules' => ''
		),
			array(
			'field' => 'prenatal_tri1_v3',
			'label' => 'Prenatal Visit 3 (1st Trimester)',
			'rules' => ''
		),
        array(
			'field' => 'prenatal_tri2_v1',
			'label' => 'Prenatal Visit 1 (2nd Trimester)',
			'rules' => ''
		),
			array(
			'field' => 'prenatal_tri2_v2',
			'label' => 'Prenatal Visit 2 (2nd Trimester)',
			'rules' => ''
		),
			array(
			'field' => 'prenatal_tri2_v3',
			'label' => 'Prenatal Visit 3 (2nd Trimester)',
			'rules' => ''
		),
        array(
			'field' => 'prenatal_tri3_v1',
			'label' => 'Prenatal Visit 2 (3rd Trimester)',
			'rules' => ''
		),
			array(
			'field' => 'prenatal_tri3_v2',
			'label' => 'Prenatal Visit 2 (3rd Trimester)',
			'rules' => ''
		),
			array(
			'field' => 'prenatal_tri3_v3',
			'label' => 'Prenatal Visit 3 (3rd Trimester)',
			'rules' => ''
		),
		array(
			'field' => 'tetanus_status',
			'label' => 'Tetanus Status',
			'rules' => ''
		),
		array(
			'field' => 'tt1',
			'label' => 'Date TT Vaccine Given',
			'rules' => ''
		),
		array(
			'field' => 'tt2',
			'label' => 'Date TT Vaccine Given',
			'rules' => ''
		),
		array(
			'field' => 'tt3',
			'label' => 'Date TT Vaccine Given',
			'rules' => ''
		),
		array(
			'field' => 'tt4',
			'label' => 'Date TT Vaccine Given',
			'rules' => ''
		),
		array(
			'field' => 'tt5',
			'label' => 'Date TT Vaccine Given',
			'rules' => ''
		),
		array(
			'field' => 'date_given_vit_a',
			'label' => 'Date Given Vit. A',
			'rules' => ''
		),
		array(
			'field' => 'iron1_date',
			'label' => 'Date Iron with Folic Acid Given',
			'rules' => ''
		),
		array(
			'field' => 'iron1_number',
			'label' => 'Number Iron with Folic Acid Given',
			'rules' => ''
		),
		array(
			'field' => 'iron2_date',
			'label' => 'Date Iron with Folic Acid Given',
			'rules' => ''
		),
		array(
			'field' => 'iron2_number',
			'label' => 'Number Iron with Folic Acid Given',
			'rules' => ''
		),
		array(
			'field' => 'iron3_date',
			'label' => 'Date Iron with Folic Acid Given',
			'rules' => ''
		),
		array(
			'field' => 'iron3_number',
			'label' => 'Number Iron with Folic Acid Given',
			'rules' => ''
		),
		array(
			'field' => 'iron4_date',
			'label' => 'Date Iron with Folic Acid Given',
			'rules' => ''
		),
		array(
			'field' => 'iron4_number',
			'label' => 'Number Iron with Folic Acid Given',
			'rules' => ''
		),
		array(
			'field' => 'iron5_date',
			'label' => 'Date Iron with Folic Acid Given',
			'rules' => ''
		),
		array(
			'field' => 'iron5_number',
			'label' => 'Number Iron with Folic Acid Given',
			'rules' => ''
		),
		array(
			'field' => 'iron6_date',
			'label' => 'Date Iron with Folic Acid Given',
			'rules' => ''
		),
		array(
			'field' => 'iron6_number',
			'label' => 'Number Iron with Folic Acid Given',
			'rules' => ''
		),
		array(
			'field' => 'risk_code',
			'label' => 'Risk Code',
			'rules' => ''
		),
		array(
			'field' => 'risk_date_detected',
			'label' => 'Risk Code Date Detected',
			'rules' => ''
		),
		array(
			'field' => 'pregnancy_date_terminated',
			'label' => 'Pregnancy Date Terminated',
			'rules' => ''
		),
		array(
			'field' => 'pregnancy_outcome',
			'label' => 'Pregnancy Outcome',
			'rules' => ''
		),
                array(
			'field' => 'livebirths_birth_weight',
			'label' => 'Livebirths Birth Weight in Grams',
			'rules' => 'numeric'
		),
		array(
			'field' => 'livebirths_place_delivery',
			'label' => 'Livebirths Place of Delivery',
			'rules' => ''
		),
		array(
			'field' => 'livebirths_type_delivery',
			'label' => 'Livebirths Type of Delivery',
			'rules' => ''
		),
		array(
			'field' => 'livebirths_attended_by',
			'label' => 'Livebirths Attended by',
			'rules' => ''
		),
                array(
			'field' => 'remarks',
			'label' => 'Remarks',
			'rules' => ''
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
		$this->load->model(array('prenatals_m', 'clients/clients_m', 'clients/clients_region_m', 'clients/clients_province_m', 'clients/clients_city_m', 'clients/clients_barangay_m'));
		$this->load->library('form_validation');
        $this->load->library('fpdf'); // Load library 
		$this->lang->load(array('prenatals', 'clients', 'region', 'province', 'city', 'barangay'));
		//$this->output->enable_profiler(TRUE);
		
		$this->data->regions = array();
		if ($regions = $this->clients_region_m->order_by('name')->get_all())
		{
			foreach ($regions as $region)
			{
				$this->data->regions[$region->id] = $region->name;
			}
		}
		
		$this->data->provinces = array();
		if ($provinces = $this->clients_province_m->order_by('name')->get_all())
		{
			foreach ($provinces as $province)
			{
				$this->data->provinces[$province->id] = $province->name;
			}
		}
		
		$this->data->cities = array();
		if ($cities = $this->clients_city_m->order_by('name')->get_all())
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
        
        $this->data->prenatals_filters = array(
                  'first'  => 'First Trimester',
                  'second'  => 'Second Trimester',
                  'third'  => 'Third Trimester',
                  'complete'  => 'Complete');	
        $this->data->risk_code = array('N','A','B','C','D','E','O');
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
		redirect('admin/prenatals/my_list');  
		
		//keyphrase param
		$base_where['name'] = $this->input->post('f_keywords') ? $this->input->post('f_keywords') : '';
        
        //barangay param   
        $base_where = $this->input->post('f_barangays') ? $base_where + array('barangay' => $this->input->post('f_barangays')) : $base_where;
       
        //year param    
        $base_where = $this->input->post('by_year') ? $base_where + array('by_year' => $this->input->post('by_year')) : $base_where;
        
        //prenatal visits param    
		$base_where = $this->input->post('prenatals_filters') ? $base_where + array('prenatals_filters' => $this->input->post('prenatals_filters')) : $base_where;
        
        //risk code param    
		$base_where = $this->input->post('risk_code') ? $base_where + array('risk_code' => $this->input->post('risk_code')) : $base_where;
        
        $this->load->library('pagination');
                
        //Set parameters session
        $this->session->set_userdata('base_where',$base_where);       

		//Create pagination links
		$pagination = create_pagination('admin/prenatals/index', $this->prenatals_m->count_by($base_where));
        
        $year = date("Y");
        for($i=$year; $i >= $year - 10; $i--)
        {
            $years[$i]  = $i;
        }
        $this->data->year = $year;
		$this->data->years= $years;
        
        // Using this data, get the relevant results
		$prenatals = $this->prenatals_m->get_results($pagination['limit'],$base_where);
        
        $this->data->totalitems = $this->prenatals_m->count_by($base_where);
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

                //$this->data->prenatal->client = & $this->clients_barangay_m->get_name($client->barangay_id);;
                
		// Render the view
		$this->template
			->title($this->module_details['name'])
			->set('pagination', $pagination)
			->set('prenatals', $prenatals)
			->set_partial('filters', 'admin/partials/filters')  
			->append_metadata(js('admin/filter.js'))
                        ->set_partial('prenatals-list', 'admin/tables/prenatals');                                   
				
		$this->input->is_ajax_request() ? $this->template->build('admin/tables/prenatals', $this->data) : $this->template->build('admin/index', $this->data);
	 
	}
	
	public function my_list()
	{
		$selected_client = $this->clients_m->get($this->session->userdata('selected_client_id'));
		$selected_client or redirect('admin/family');
		
    //determine active param
		$base_where['client_id'] = $this->session->userdata('selected_client_id');
		
			// Create pagination links
		$pagination = create_pagination('admin/prenatals/index', $this->prenatals_m->count_by($base_where));

		// Using this data, get the relevant results
			
		$prenatals = $this->prenatals_m->get_results($pagination['limit'],$base_where);

		//unset the layout if we have an ajax request
		if ($this->input->is_ajax_request()) $this->template->set_layout(FALSE);

                //$this->data->prenatal->client = & $this->clients_barangay_m->get_name($client->barangay_id);;
    $this->data->selected_client = & $selected_client;            
		// Render the view
		$this->template
			->title($this->module_details['name'])
			->set('pagination', $pagination)
			->set('prenatals', $prenatals)
      ->set_partial('prenatals-my_list', 'admin/tables/my_list');                                   
				
		$this->input->is_ajax_request() ? $this->template->build('admin/tables/my_list', $this->data) : $this->template->build('admin/list', $this->data);
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
				redirect('admin/prenatals');
				break;
		}
	}

	/**
	 * Create a new client role
	 *
	 * @access public
	 * @return void
	 */
	 
	public function add_client()
	{		
                $this->session->set_flashdata('warning', 'Search and Select a Client to be Added to "Prenatal Care" Or <a href="admin/clients/add" class="success-link">Register</a> a New Client.');
                $this->session->set_userdata('current_action', 'pn'); 
                redirect('admin/clients');
	}
	
	public function select_client($client_id)
	{		
               	  if($id = $this->prenatals_m->check_prenatal_exist($client_id))
               	  {	  
                   redirect('admin/prenatals/edit/'.$id);
                  }
                   redirect('admin/prenatals/add/'.$client_id);
	}
	
	public function add($client_id)
	{		
    $this->session->unset_userdata('current_action'); 
    if($this->session->userdata('consultation_id') != $client_id)
    {
     $this->session->set_flashdata('warning', 'Please Add New Consultation for this Client before Adding Prenatal Care');
     redirect('admin/consultations/add/'.$client_id);
    }            $prenatal_client = $this->prenatals_m->get_client($client_id);
                
                $this->form_validation->set_rules($this->validation_rules);
                
                if ($_POST)
		{
                        // Loop through each POST item and add it to the secure_post array
                        $secure_post = $this->input->post();
                        
                        $dates = array('last_menstrual_period','estimated_date_confinement', 'prenatal_tri1_v1', 'prenatal_tri1_v2', 'prenatal_tri1_v3', 'prenatal_tri2_v1', 'prenatal_tri2_v2', 'prenatal_tri2_v3', 'prenatal_tri3_v1', 'prenatal_tri3_v2', 'prenatal_tri3_v3', 'tt1', 'tt2', 'tt3', 'tt4', 'tt5', 'date_given_vit_a', 'iron1_date', 'iron2_date', 'iron3_date', 'iron4_date', 'iron5_date', 'iron6_date', 'risk_date_detected','pregnancy_date_terminated','date_visited','tt_date_given','iron_date_given');
                        
                        // Set the full date of birth
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
                                
                                if($this->prenatals_m->insert($secure_post))
                                {
                                        $this->session->set_flashdata('success', 'New Prenatal Visits Added');
                                        $this->session->set_userdata('consultation_id', 0);
                                        if($this->session->userdata('selected_client_id'))
                                          redirect('admin/prenatals/my_list');
                                        else
                                          redirect('admin/prenatals');
                                }
                                else 
                                {
                                        $this->session->set_flashdata('error', 'Error Adding Prenatal Visits');
                                        redirect("admin/prenatal/add/$client_id");
                                }
          		}
          		else
          		{
          			// Dirty hack that fixes the issue of having to re-add all data upon an error
          			$prenatal = (object) $_POST;
          		}
		} 
                // Loop through each validation rule
		foreach ($this->validation_rules as $rule)
		{
			$prenatal->{$rule['field']} = set_value($rule['field']);
		}	
		
		$prenatal_client->client_id = $client_id;
                
		// Render the view   
	       	$this->data->prenatal = & $prenatal;
        	$this->data->prenatal_client = & $prenatal_client;
		$this->data->days = & $days;
		$this->data->months = & $months;
		$this->data->years = & $years;
		$this->data->risk_code = array('N','A','B','C','D','E','O');
		$this->data->livebirths_type_delivery = array('Normal' => 'Normal', 'CS' => 'CS');
		$this->data->livebirths_outcome = array('LB' => 'Livebirth','SB' => 'Stillbirth','AB' => 'Abortion');
		$this->data->livebirths_attendant = array('A' => 'Doctor','B' => 'Nurse','C' => 'Midwife','D' => 'Hilot/TBA','E' => 'Others');
        	
		$this->data->prenatal_client->barangay_name = & $this->clients_barangay_m->get_name($prenatal_client->barangay_id);;
		$this->data->prenatal_client->province_name = & $this->clients_province_m->get_name($prenatal_client->province_id);;
		$this->data->prenatal_client->region_name = & $this->clients_region_m->get_name($prenatal_client->region_id);;
		$this->data->prenatal_client->city_name = & $this->clients_city_m->get_name($prenatal_client->city_id);;
		
                $this->template
				->title($this->module_details['name'], lang('user_add_title'))
				->build('admin/form',$this->data); 
        }

	/**
	 * Edit a client
	 *
	 * @access public
	 * @param int $id The ID of the client to edit
	 * @return void
	 */
	public function edit($pnid)
	{
		$prenatal_client = $this->prenatals_m->get_client_pn($pnid);
		$client = $this->clients_m->get($prenatal_client->client_id);
		
		// Make sure we found something
		$prenatal_client or redirect('admin/prenatals');

		if ($_POST)
		{
			
                // Loop through each POST item and add it to the secure_post array
			$secure_post = $this->input->post();

		$dates = array('last_menstrual_period','estimated_date_confinement', 'prenatal_tri1_v1', 'prenatal_tri1_v2', 'prenatal_tri1_v3', 'prenatal_tri2_v1', 'prenatal_tri2_v2', 'prenatal_tri2_v3', 'prenatal_tri3_v1', 'prenatal_tri3_v2', 'prenatal_tri3_v3', 'tt1', 'tt2', 'tt3', 'tt4', 'tt5', 'date_given_vit_a', 'iron1_date', 'iron2_date', 'iron3_date', 'iron4_date', 'iron5_date', 'iron6_date', 'risk_date_detected','pregnancy_date_terminated','date_visited','tt_date_given','iron_date_given');
                        
                        // Set the full date of birth
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
			

			
                // Set the validation rules
  		$this->form_validation->set_rules($this->validation_rules);
  
  		if ($this->form_validation->run() !== FALSE)
  		{   		            
                        if($this->prenatals_m->update($pnid, $secure_post))
                        {
                                $this->session->set_flashdata('success', 'Prenatal Visits successfully updated');
                                if($this->session->userdata('selected_client_id'))
                                   redirect('admin/prenatals/my_list');
                                else
                                  redirect('admin/prenatals');
                        }
                        else                
                        {
                                $this->session->set_flashdata('error', 'Error updating Prenatal Visits/No data has been received.');
                                redirect("admin/prenatal/add/$client_id");
                        }
  		}
  		else
  		{
  				$prenatal_client = (object) $_POST;
  		}
  		// Loop through each validation rule
  		foreach ($this->validation_rules as $rule)
  		{
  			$prenatal_client->{$rule['field']} = set_value($rule['field']);
  		}
		}
		
                
                $prenatal_client->pn_id = $pnid;
                
		// Render the view
        	$this->data->prenatal_client = & $prenatal_client;
        	$this->data->client = & $client;
        	
		$this->data->days = & $days;
		$this->data->months = & $months;
		$this->data->years = & $years;
		$this->data->risk_code = array('N','A','B','C','D','E','O');
        
		$this->data->livebirths_type_delivery = array('Normal' => 'Normal', 'CS' => 'CS');
		$this->data->livebirths_outcome = array('LB' => 'Livebirth','SB' => 'Stillbirth','AB' => 'Abortion');
		$this->data->livebirths_attendant = array('A' => 'Doctor','B' => 'Nurse','C' => 'Midwife','D' => 'Hilot/TBA','E' => 'Others');
		
		$this->data->client->barangay_name = & $this->clients_barangay_m->get_name($client->barangay_id);;
		$this->data->client->province_name = & $this->clients_province_m->get_name($client->province_id);;
		$this->data->client->region_name = & $this->clients_region_m->get_name($client->region_id);;
		$this->data->client->city_name = & $this->clients_city_m->get_name($client->city_id);;
		
                $this->template
				->title($this->module_details['name'], lang('user_edit_title'))
				->append_metadata(js('fp_form.js', 'family'))
				->build('admin/form_edit', $this->data);
		//echo print_r($prenatal_client);
	}




        /**
	 * View a client
	 *
	 * @access public
	 * @param int $id The ID of the client to edit
	 * @return void
	 */
	public function view($pnid = 0)
	{
		$prenatal = $this->prenatals_m->get_client_pn($pnid);
                
		//Make sure we found something
		$prenatal or redirect('admin/prenatals');
		
		//Render the view
		$this->data->prenatal = & $prenatal;  
		$this->data->prenatal_visits = & $prenatal_visits;
		$this->data->prenatal_tts = & $prenatal_tts;
		$this->data->prenatal_micronutrients = & $prenatal_micronutrients;
		
		$this->template
				->title($this->module_details['name'], lang('user_add_title'))
				->build('admin/view', $this->data);
	}
	  
  public function set($type = 'default', $client_id = 0)
  {
    if($type == 'default')
    {
        $this->session->set_userdata('selected_client_id','');
        $this->session->set_userdata('set', $type);
        redirect("admin/prenatals");
    }
    $count = $this->prenatals_m->check_record($client_id);
    if($count > 0)
    {
        $this->session->set_flashdata('warning', 'Please Select on the List or '."<a href='admin/prenatals/add/$client_id' class='add'>Add</a>".' New Prenatal Care for this Client');
        $this->session->set_userdata('selected_client_id',$client_id);
        $this->session->set_userdata('set', $type);
        redirect("admin/prenatals/my_list");
    }
    else
    {
        $this->session->set_flashdata('error', 'No Prenatal Care Found for this Client. Click '."<a href='admin/prenatals/add/$client_id' class='add'>here</a>".' to Add New Prenatal Care for this Client');
        $this->session->set_userdata('selected_client_id',$client_id);
        $this->session->set_userdata('set', $type);
        redirect("admin/prenatals/my_list");
    }
  }

	/**
	 * Delete client 
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
    				if ($this->prenatals_m->delete($id))
    				{
    					$deleted++;
    				}
    				$to_delete++;
    			}
    
    			if ($to_delete > 0)
    			{
    				$this->session->set_flashdata('success', sprintf($this->lang->line('prenatals_mass_delete_success'), $deleted, $to_delete));
    			}
    		}
    		// The array of id's to delete is empty
    		else
    			$this->session->set_flashdata('error', $this->lang->line('prenatals.delete_error'));
    }
    else
    {
        $this->prenatals_m->delete($id)
    			? $this->session->set_flashdata('success', lang('prenatals.delete_success'))
    			: $this->session->set_flashdata('error', lang('prenatals.delete_error'));
		}
    if($this->session->userdata('selected_client_id'))
       redirect('admin/prenatals/my_list');
    else
		redirect('admin/prenatals');
	}
    
    public function print_page()
	{       
        $base_where = $this->session->userdata('base_where');
        
        // Create pagination links
	    $pagination = create_pagination('admin/prenatals/index', $this->prenatals_m->count_by($base_where));

	    // Using this data, get the relevant results
        $prenatals = $this->prenatals_m->get_results($pagination['limit'],$base_where);
        
        $totalitems = count($prenatals);
        if($totalitems<1)
            {$totalitems = 1;}
        $totalpage = number_format($totalitems/17);
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
        $this->fpdf->Cell(0,4,strtoupper('Target Client List For Prenatal Care'),0,0,'C');
        $this->fpdf->SetFont('Helvetica','',8);
        
        $this->header_page1();
        
        $i = 1;
        $x = 1;
        $currentpage = 1;
        foreach ($prenatals as $prenatal)
        {                             
                $age = floor((time() - $prenatal->dob)/31556926); 
                if(!$age)
                    {$age = '-';}
                    
                $brgy = $this->clients_barangay_m->get_name($prenatal->barangay_id);   
                if(!$prenatal->address)
                        {$address = $brgy;} 
                elseif(!$brgy)
                        {$address = $prenatal->address;} 
                else 
                        {$address = $brgy.', '.$prenatal->address;}
                        
                $date_registered = $prenatal->registration_date ? date('m-d-Y', $prenatal->registration_date): '';
                $lmp = $prenatal->last_menstrual_period ? date('m-d-Y', $prenatal->last_menstrual_period): '';
                $edc = $prenatal->estimated_date_confinement ? date('m-d-Y', $prenatal->estimated_date_confinement): '';
                
                $prenatal_tri1_v1 = $prenatal->prenatal_tri1_v1 ? date('m-d-Y', $prenatal->prenatal_tri1_v1): '';
                $prenatal_tri1_v2 = $prenatal->prenatal_tri1_v2 ? date('m-d-Y', $prenatal->prenatal_tri1_v2): '';
                $prenatal_tri1_v3 = $prenatal->prenatal_tri1_v3 ? date('m-d-Y', $prenatal->prenatal_tri1_v3): '';
                
                $this->fpdf->SetFillColor(230,230,230);             
                $modx = $x%2;
                if($modx==0) 
                {$fill=false;}
                else
                {$fill=true;}
                  
                $this->fpdf->SetFont('Helvetica','B',7);
                $this->fpdf->Cell(12,8,$x.'.',1,0,'L',$fill);
                $this->fpdf->SetFont('Helvetica','',7);
                $this->fpdf->Cell(15,8,$date_registered,1,0,'C',$fill);
                $this->fpdf->Cell(20,8,$prenatal->serial_number,1,0,'L',$fill);
                $this->fpdf->Cell(55,8,trim($prenatal->last_name).', '.trim($prenatal->first_name).' '.trim($prenatal->middle_name),1,0,'L',$fill);
                $this->fpdf->Cell(55,8,$address,1,0,'L',$fill);
                $this->fpdf->Cell(15,8,$age,1,0,'C',$fill);
                $this->fpdf->Cell(15,8,$lmp,1,0,'C',$fill);
                $this->fpdf->Cell(15,8,$prenatal->gravida.'-'.$prenatal->para.'-'.$prenatal->term,1,0,'L',$fill);
                $this->fpdf->Cell(15,8,$edc,1,0,'C',$fill);
                $this->fpdf->Cell(20,8,$prenatal_tri1_v1,1,0,'C',$fill);
                $this->fpdf->Cell(20,8,$prenatal_tri1_v1,1,0,'C',$fill);
                $this->fpdf->Cell(20,8,$prenatal_tri1_v1,1,1,'C',$fill);
                

                if($i==17)
                {
                    if($x<$totalitems)
                    {                    
                      $this->legend_page1();
                      $this->tcl_header();
                      $this->footer();
                      $this->fpdf->SetY(-15);
                      $this->fpdf->SetTextColor(128);
                      $this->fpdf->SetFont('Helvetica','I',6);
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
                      $this->fpdf->Cell(0,4,strtoupper('Target Client List For Prenatal Care'),0,0,'C');
                      $this->fpdf->SetFont('Helvetica','',8);
                      //$this->fpdf->WriteHTML($html);
                      
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
        $this->fpdf->SetFont('Helvetica','I',6);
        $this->fpdf->Cell(10,4,'Page '. $currentpage.'/'.$totalpages,0,0,'C');
        
        //================================ Second Page =============================//
        
        $this->fpdf->AliasNbPages();
        $this->fpdf->AddPage('L','A4');
        $this->fpdf->SetAutoPageBreak(true, 10);
                            
        $this->fpdf->Image(base_url().'system/cms/themes/pyrocms/img/logo_small.png',10,5,40);
        $this->fpdf->SetY(10);
        $this->fpdf->SetFont('Helvetica','B',16);
        $this->fpdf->SetTextColor(0,0,0);
        $this->fpdf->Cell(0,4,strtoupper('Target Client List For Prenatal Care'),0,0,'C');
        $this->fpdf->SetFont('Helvetica','',8);
        //$this->fpdf->WriteHTML($html);
        
        //Table Header
        $this->header_page2();
        
        $i = 1;
        $x = 1;
        $currentpage = 1;
        foreach ($prenatals as $prenatal)
        {                             
                $age = floor((time() - $prenatal->dob)/31556926); 
                if(!$age)
                    {$age = '-';}
                    
                $brgy = $this->clients_barangay_m->get_name($prenatal->barangay_id);   
                if(!$prenatal->address)
                        {$address = $brgy;} 
                elseif(!$brgy)
                        {$address = $prenatal->address;} 
                else 
                        {$address = $brgy.', '.$prenatal->address;}
                                        
                $tt1 = $prenatal->tt1 ? date('m-d-Y', $prenatal->tt1): '';
                $tt2 = $prenatal->tt2 ? date('m-d-Y', $prenatal->tt2): '';
                $tt3 = $prenatal->tt3 ? date('m-d-Y', $prenatal->tt3): '';
                $tt4 = $prenatal->tt4 ? date('m-d-Y', $prenatal->tt4): '';
                $tt5 = $prenatal->tt5 ? date('m-d-Y', $prenatal->tt5): '';
                
                $date_given_vit_a = $prenatal->date_given_vit_a ? date('m-d-Y', $prenatal->date_given_vit_a): '';
                
                $iron1_date = $prenatal->iron1_date ? date('m-d-y', $prenatal->iron1_date): '';
                $iron2_date = $prenatal->iron2_date ? date('m-d-y', $prenatal->iron2_date): '';
                $iron3_date = $prenatal->iron3_date ? date('m-d-y', $prenatal->iron3_date): '';
                $iron4_date = $prenatal->iron4_date ? date('m-d-y', $prenatal->iron4_date): '';
                $iron5_date = $prenatal->iron5_date ? date('m-d-y', $prenatal->iron5_date): '';
                $iron6_date = $prenatal->iron6_date ? date('m-d-y', $prenatal->iron6_date): '';
                
                $risk_date_detected = $prenatal->risk_date_detected ? date('m-d-Y', $prenatal->risk_date_detected): '';
                $pregnancy_date_terminated = $prenatal->pregnancy_date_terminated ? date('m-d-Y', $prenatal->pregnancy_date_terminated): '';
                
                
                if($prenatal->risk_code==0)
                    {$risk_code='N';}
                elseif($prenatal->risk_code==1)
                    {$risk_code='A';}
                elseif($prenatal->risk_code==2)
                    {$risk_code='B';}
                elseif($prenatal->risk_code==3)
                    {$risk_code='C';}
                elseif($prenatal->risk_code==4)
                    {$risk='D';}
                elseif($prenatal->risk_code==5)
                    {$risk_code='E';}
                elseif($prenatal->risk_code==6)
                    {$risk_code='0';}
                else
                    {$risk_code='-';}
                    
                $this->fpdf->SetFillColor(230,230,230);             
                $modx = $x%2;
                if($modx==0) 
                {$fill=false;}
                else
                {$fill=true;}
                    
                $this->fpdf->SetFont('Helvetica','B',7);
                $this->fpdf->Cell(12,8,$x.'.',1,0,'L',$fill);
                $this->fpdf->SetFont('Helvetica','',7);
                $this->fpdf->Cell(15,8,$prenatal->tetanus_status,1,0,'C',$fill);
                $this->fpdf->Cell(15,8,$tt1,1,0,'C',$fill);
                $this->fpdf->Cell(15,8,$tt2,1,0,'C',$fill);
                $this->fpdf->Cell(15,8,$tt3,1,0,'C',$fill);
                $this->fpdf->Cell(15,8,$tt4,1,0,'C',$fill);
                $this->fpdf->Cell(15,8,$tt5,1,0,'C',$fill);
                $this->fpdf->Cell(15,8,$date_given_vit_a,1,0,'C',$fill);
                $this->fpdf->SetFont('Helvetica','',6); 
                             
                $this->fpdf->Cell(10,4,$iron1_date,1,0,'C',$fill);
                $posx = $this->fpdf->GetX();
                $posy = $this->fpdf->GetY();
                $posx = number_format($posx)-10;
                $posy = $posy+4;
                $posx2 = number_format($posx)+10;
                $posy2 = $posy-4;
                $this->fpdf->SetY($posy);
                $this->fpdf->SetX($posx); 
                $this->fpdf->Cell(10,4,$prenatal->iron1_number,1,0,'C',$fill);
                $this->fpdf->SetY($posy2);
                $this->fpdf->SetX($posx2);
                
                $this->fpdf->Cell(10,4,$iron2_date,1,0,'C',$fill);
                $posx = $this->fpdf->GetX();
                $posy = $this->fpdf->GetY();
                $posx = number_format($posx)-10;
                $posy = $posy+4;
                $posx2 = number_format($posx)+10;
                $posy2 = $posy-4;
                $this->fpdf->SetY($posy);
                $this->fpdf->SetX($posx); 
                $this->fpdf->Cell(10,4,$prenatal->iron2_number,1,0,'C',$fill);
                $this->fpdf->SetY($posy2);
                $this->fpdf->SetX($posx2);
                
                $this->fpdf->Cell(10,4,$iron3_date,1,0,'C',$fill);
                $posx = $this->fpdf->GetX();
                $posy = $this->fpdf->GetY();
                $posx = number_format($posx)-10;
                $posy = $posy+4;
                $posx2 = number_format($posx)+10;
                $posy2 = $posy-4;
                $this->fpdf->SetY($posy);
                $this->fpdf->SetX($posx); 
                $this->fpdf->Cell(10,4,$prenatal->iron3_number,1,0,'C',$fill);
                $this->fpdf->SetY($posy2);
                $this->fpdf->SetX($posx2);
                
                $this->fpdf->Cell(10,4,$iron4_date,1,0,'C',$fill);
                $posx = $this->fpdf->GetX();
                $posy = $this->fpdf->GetY();
                $posx = number_format($posx)-10;
                $posy = $posy+4;
                $posx2 = number_format($posx)+10;
                $posy2 = $posy-4;
                $this->fpdf->SetY($posy);
                $this->fpdf->SetX($posx); 
                $this->fpdf->Cell(10,4,$prenatal->iron4_number,1,0,'C',$fill);
                $this->fpdf->SetY($posy2);
                $this->fpdf->SetX($posx2);
                
                $this->fpdf->Cell(10,4,$iron5_date,1,0,'C',$fill);
                $posx = $this->fpdf->GetX();
                $posy = $this->fpdf->GetY();
                $posx = number_format($posx)-10;
                $posy = $posy+4;
                $posx2 = number_format($posx)+10;
                $posy2 = $posy-4;
                $this->fpdf->SetY($posy);
                $this->fpdf->SetX($posx); 
                $this->fpdf->Cell(10,4,$prenatal->iron5_number,1,0,'C',$fill);
                $this->fpdf->SetY($posy2);
                $this->fpdf->SetX($posx2);
                
                $this->fpdf->Cell(10,4,$iron6_date,1,0,'C',$fill);
                $posx = $this->fpdf->GetX();
                $posy = $this->fpdf->GetY();
                $posx = number_format($posx)-10;
                $posy = $posy+4;
                $posx2 = number_format($posx)+10;
                $posy2 = $posy-4;
                $this->fpdf->SetY($posy);
                $this->fpdf->SetX($posx); 
                $this->fpdf->Cell(10,4,$prenatal->iron6_number,1,0,'C',$fill);
                $this->fpdf->SetY($posy2);
                $this->fpdf->SetX($posx2);
                
                $this->fpdf->SetFont('Helvetica','',7);
                $this->fpdf->Cell(20,4,$risk_code,1,0,'C',$fill);
                $posx = $this->fpdf->GetX();
                $posy = $this->fpdf->GetY();
                $posx = number_format($posx)-20;
                $posy = $posy+4;
                $posx2 = number_format($posx)+20;
                $posy2 = $posy-4;
                $this->fpdf->SetY($posy);
                $this->fpdf->SetX($posx);
                $this->fpdf->Cell(20,4,$risk_date_detected,1,0,'C',$fill);
                $this->fpdf->SetY($posy2);
                $this->fpdf->SetX($posx2); 
                
                $this->fpdf->Cell(15,8,$pregnancy_date_terminated,1,0,'C',$fill);
                $this->fpdf->Cell(15,8,$prenatal->pregnancy_outcome,1,0,'C',$fill);
                $this->fpdf->Cell(15,8,$prenatal->livebirths_birth_weight,1,0,'C',$fill);
                $this->fpdf->Cell(20,8,$prenatal->livebirths_place_delivery,1,0,'C',$fill);
                $this->fpdf->Cell(15,8,$prenatal->livebirths_attended_by,1,1,'C',$fill);
                

                if($i==17)
                {
                    if($x<$totalitems)
                    {                    
                      //Page footers
                      $this->legend_page2();
                      $this->footer();
                      $this->fpdf->SetY(-15);
                      $this->fpdf->SetTextColor(128);
                      $this->fpdf->SetFont('Helvetica','I',7);
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
                      $this->fpdf->Cell(0,4,strtoupper('Target Client List For Prenatal Care'),0,0,'C');
                      $this->fpdf->SetFont('Helvetica','',8);
                      
                      //Table headers
                      $this->header_page2();
                      
                    }
                }
                else
                {
                    $i=$i+1;
                }     
                $x=$x+1;     
        }
        
        //Page footers
        $this->legend_page2();
        $this->footer();
        $this->fpdf->SetY(-15);
        $this->fpdf->SetTextColor(128);
        $this->fpdf->SetFont('Helvetica','I',7);
        $this->fpdf->Cell(10,4,'Page '. $currentpage.'/'.$totalpages,0,0,'C');
               
        $this->fpdf->Output('TCL_Prenatal.pdf','I');  
	} 
    
    function footer()
    {
        $year = date('Y');
        //Draw a line
        $this->fpdf->SetY(-15);
        
        //Page footer
        $this->fpdf->SetY(-15);
        $this->fpdf->SetTextColor(128);
        $this->fpdf->SetFont('Helvetica','I',7);
        //$this->fpdf->Cell(10,4,'Page '.$this->fpdf->PageNo().'/{nb}',0,0,'C');
        $this->fpdf->SetFont('Helvetica','',7);
        $this->fpdf->Cell(0,4,'Copyright © '.$year.' IMPROVHEALTH | Provided by: Touch Foundation Inc.',0,0,'C');
    } 
    
    function tcl_header()
    {
        //Draw a line
        $this->fpdf->SetY(5);
        $this->fpdf->SetX(255);
        
        $this->fpdf->SetTextColor(0,0,0);
        $this->fpdf->SetFont('Helvetica','B',20);
        $this->fpdf->Cell(30,10,'TCL - PN',0,1,'C');
    } 
    
    function legend_page1()
    {
        //Draw a line
        $this->fpdf->SetY(173);
        $this->fpdf->SetX(170);
        $this->fpdf->SetTextColor(0,0,0);
        $this->fpdf->SetFont('Helvetica','BI',8);
        $this->fpdf->Cell(15,4,'NOTE:',0,0,'L');
        $this->fpdf->SetFont('Helvetica','',8);
        $this->fpdf->Cell(40,4,'NA = New Acceptors',0,1,'L');
        $this->fpdf->SetX(185);
        $this->fpdf->Cell(70,4,'CON = Condom',0,1,'L');
        $this->fpdf->SetX(185);
        $this->fpdf->Cell(60,4,'NFP-BBT = Basal Body Temperature',0,1,'L');
        $this->fpdf->SetX(185);
        $this->fpdf->Cell(70,4,'NFP-SDM = Standard Days Method',0,1,'L');
         
    } 
    
    function legend_page2()
    {
        //Draw a line
        $this->fpdf->SetY(173);
        $this->fpdf->SetX(10);
        $this->fpdf->SetTextColor(0,0,0);
        $this->fpdf->SetFont('Helvetica','BI',8);
        $this->fpdf->Cell(20,4,'* Risk Code:',0,0,'L');
        $this->fpdf->SetFont('Helvetica','',8);
        $this->fpdf->Cell(60,4,'N - Normal (No risk found)',0,0,'L');
        $this->fpdf->Cell(60,4,'D - Having one or more of the ff:',0,0,'L');
        $this->fpdf->Cell(55,4,'E - Having one or more of the ff medical',0,0,'L');
        $this->fpdf->SetFont('Helvetica','BI',8);
        $this->fpdf->Cell(18,4,'**Outcome',0,0,'L');
        $this->fpdf->SetFont('Helvetica','',8);
        $this->fpdf->Cell(20,4,'LB = Livebirth',0,0,'L');
        $this->fpdf->SetFont('Helvetica','BI',8);
        $this->fpdf->Cell(20,4,'***Attendant',0,0,'L');
        $this->fpdf->SetFont('Helvetica','',8);
        $this->fpdf->Cell(15,4,'A = Doctor',0,1,'L');
        $this->fpdf->SetX(30);
        $this->fpdf->Cell(60,4,'A - An age less than 18 or greater than 35',0,0,'L');
        $this->fpdf->Cell(60,4,'    (a) a previous caesarian section',0,0,'L');
        $this->fpdf->Cell(55,4,'    conditions: (1) Tuberculosis',0,0,'L');
        $this->fpdf->SetX(223);
        $this->fpdf->Cell(20,4,'SB = Stillbirth',0,0,'L');
        $this->fpdf->SetX(263);
        $this->fpdf->Cell(25,4,'B = Nurse',0,1,'L');
        $this->fpdf->SetX(30);
        $this->fpdf->Cell(60,4,'B - Being less than 145 cm (4\'9") tall',0,0,'L');
        $this->fpdf->Cell(60,4,'    (b) 3 consecutive miscarriages or ',0,0,'L');
        $this->fpdf->Cell(55,4,'    (2) Heart Disease (3) Diabetes',0,0,'L');
        $this->fpdf->SetX(223);
        $this->fpdf->Cell(20,4,'AB = Abortion',0,0,'L');
        $this->fpdf->SetX(263);
        $this->fpdf->Cell(25,4,'C = Midwife',0,1,'L');
        $this->fpdf->SetX(30);
        $this->fpdf->Cell(60,4,'C - Having a fourth (or more) baby',0,0,'L');
        $this->fpdf->Cell(60,4,'    stillborn bay and',0,0,'L');
        $this->fpdf->Cell(55,4,'    (4) Bronchial Asthma (5) Goiter',0,0,'L');
        $this->fpdf->SetX(263);
        $this->fpdf->Cell(25,4,'D = Hilot/TBA',0,1,'L');
        $this->fpdf->SetX(30);
        $this->fpdf->Cell(60,4,'    (or so called grand multi)',0,0,'L');
        $this->fpdf->Cell(60,4,'    (c) postpartum hemorrhage',0,0,'L');  
        $this->fpdf->Cell(55,4,'O - Others',0,0,'L');
        $this->fpdf->SetX(263);
        $this->fpdf->Cell(25,4,'E = Others',0,1,'L');
    }
    
    function header_page1()
    {
        $this->fpdf->SetY(15);
        $this->fpdf->SetFillColor(208,208,255);
        $fill = true;
        
        $this->fpdf->SetFont('Helvetica','',8);
        $this->fpdf->Cell(12,4,'','LTRb',0,'C',$fill);
        $this->fpdf->Cell(15,4,'DATE OF','LTRb',0,'C',$fill);
        $this->fpdf->Cell(20,4,'FAMILY','LTRb',0,'C',$fill);
        $this->fpdf->Cell(55,4,'','LTRb',0,'C',$fill);
        $this->fpdf->Cell(55,4,'','LTRb',0,'C',$fill);
        $this->fpdf->Cell(15,4,'','LTRb',0,'C',$fill);
        $this->fpdf->Cell(15,4,'','LTRb',0,'C',$fill);
        $this->fpdf->Cell(15,4,'','LTRb',0,'C',$fill);
        $this->fpdf->Cell(15,4,'','LTRb',0,'C',$fill);
        $this->fpdf->Cell(60,4,'DATE','LTRb',1,'C',$fill);
        
        $this->fpdf->SetFont('Helvetica','B',8);
        $this->fpdf->Cell(12,4,'NO.','LtRb',0,'C',$fill);
        $this->fpdf->SetFont('Helvetica','',8);
        $this->fpdf->Cell(15,4,'REGIS-','LtRb',0,'C',$fill);
        $this->fpdf->Cell(20,4,'SERIAL','LtRb',0,'C',$fill);
        $this->fpdf->Cell(55,4,'FULL NAME','LtRb',0,'C',$fill);
        $this->fpdf->Cell(55,4,'ADDRESS','LtRb',0,'C',$fill);
        $this->fpdf->Cell(15,4,'AGE','LtRb',0,'C',$fill);
        $this->fpdf->Cell(15,4,'LMP','LtRb',0,'C',$fill);
        $this->fpdf->Cell(15,4,'G-P-T','LtRb',0,'C',$fill);
        $this->fpdf->Cell(15,4,'EDC','LtRb',0,'C',$fill);
        $this->fpdf->Cell(60,4,'PRENATAL VISITS','LtRB',1,'C',$fill);
        
        $this->fpdf->Cell(12,4,'','LtRb',0,'C',$fill);
        $this->fpdf->Cell(15,4,'TRATION','LtRb',0,'C',$fill);
        $this->fpdf->Cell(20,4,'NO.','LtRb',0,'C',$fill);
        $this->fpdf->Cell(55,4,'','LtRb',0,'C',$fill);
        $this->fpdf->Cell(55,4,'','LtRb',0,'C',$fill);
        $this->fpdf->Cell(15,4,'','LtRb',0,'C',$fill);
        $this->fpdf->Cell(15,4,'','LtRb',0,'C',$fill);
        $this->fpdf->Cell(15,4,'','LtRb',0,'C',$fill);
        $this->fpdf->Cell(15,4,'','LtRb',0,'C',$fill);
        $this->fpdf->SetFont('Helvetica','',7);
        $this->fpdf->Cell(60,4,'(11)','LtRB',1,'C',$fill);
        $this->fpdf->SetFont('Helvetica','',8);
        
        $this->fpdf->Cell(12,4,'','LtRb',0,'C',$fill);
        $this->fpdf->Cell(15,4,'','LtRb',0,'C',$fill);
        $this->fpdf->Cell(20,4,'NO.','LtRb',0,'C',$fill);
        $this->fpdf->Cell(55,4,'','LtRb',0,'C',$fill);
        $this->fpdf->Cell(55,4,'','LtRb',0,'C',$fill);
        $this->fpdf->Cell(15,4,'','LtRb',0,'C',$fill);
        $this->fpdf->Cell(15,4,'','LtRb',0,'C',$fill);
        $this->fpdf->Cell(15,4,'','LtRb',0,'C',$fill);
        $this->fpdf->Cell(15,4,'','LtRb',0,'C',$fill);
        $this->fpdf->Cell(20,4,'FIRST','LTRb',0,'C',$fill);
        $this->fpdf->Cell(20,4,'SECOND','LTRb',0,'C',$fill);
        $this->fpdf->Cell(20,4,'THIRD','LTRb',1,'C',$fill);
        
        $this->fpdf->SetFont('Helvetica','',7);
        $this->fpdf->Cell(12,4,'(1)','LtRB',0,'C',$fill);
        $this->fpdf->Cell(15,4,'(2)','LtRB',0,'C',$fill);
        $this->fpdf->Cell(20,4,'(3)','LtRB',0,'C',$fill);
        $this->fpdf->Cell(55,4,'(4)','LtRB',0,'C',$fill);
        $this->fpdf->Cell(55,4,'(5)','LtRB',0,'C',$fill);
        $this->fpdf->Cell(15,4,'(7)','LtRB',0,'C',$fill);
        $this->fpdf->Cell(15,4,'(8)','LtRB',0,'C',$fill);
        $this->fpdf->Cell(15,4,'(9)','LtRB',0,'C',$fill);
        $this->fpdf->Cell(15,4,'(10)','LtRB',0,'C',$fill);
        $this->fpdf->SetFont('Helvetica','',8);
        $this->fpdf->Cell(20,4,'TRIMESTER','LtRB',0,'C',$fill);
        $this->fpdf->Cell(20,4,'TRIMESTER','LtRB',0,'C',$fill);
        $this->fpdf->Cell(20,4,'TRIMESTER','LtRB',1,'C',$fill);
    }                
    
    function header_page2()
    {
        $this->fpdf->SetY(15);
        $this->fpdf->SetFillColor(208,208,255);
        $fill = true;
        
        $this->fpdf->SetFont('Helvetica','',8);
        $this->fpdf->Cell(12,4,'','LTRb',0,'C',$fill);
        $this->fpdf->Cell(15,4,'','LTRb',0,'C',$fill);
        $this->fpdf->Cell(75,4,'DATE TETANUS TOXOID VACCINE','LTRb',0,'C',$fill);
        $this->fpdf->Cell(75,4,'MICRONUTRIENT SUPPLEMENTATION','LTRb',0,'C',$fill);
        $this->fpdf->Cell(20,4,'RISK','LTRb',0,'C',$fill);
        $this->fpdf->Cell(45,4,'PREGNANCY','LTRb',0,'C',$fill);
        $this->fpdf->Cell(35,4,'LIVEBIRTHS','LTRb',1,'C',$fill);
        
        $this->fpdf->SetFont('Helvetica','B',8);
        $this->fpdf->Cell(12,4,'NO.','LtRb',0,'C',$fill);
        $this->fpdf->SetFont('Helvetica','',8);
        $this->fpdf->Cell(15,4,'TETANUS','LtRb',0,'C',$fill);
        $this->fpdf->Cell(75,4,'GIVEN','LtRb',0,'C',$fill);
        $this->fpdf->SetFont('Helvetica','',7);
        $this->fpdf->Cell(75,4,'(15)','LtRB',0,'C',$fill);
        $this->fpdf->SetFont('Helvetica','',8);
        $this->fpdf->Cell(20,4,'CODE*/','LtRB',0,'C',$fill);
        $this->fpdf->SetFont('Helvetica','',7);
        $this->fpdf->Cell(45,4,'(17)','LtRB',0,'C',$fill);
        $this->fpdf->Cell(35,4,'(18)','LtRB',1,'C',$fill);
        $this->fpdf->SetFont('Helvetica','',8);
        
        $this->fpdf->SetFont('Helvetica','',7);
        $this->fpdf->Cell(12,4,'(12)','LtRb',0,'C',$fill);
        $this->fpdf->SetFont('Helvetica','',8);
        $this->fpdf->Cell(15,4,'STATUS','LtRb',0,'C',$fill);
        $this->fpdf->SetFont('Helvetica','',7);
        $this->fpdf->Cell(75,4,'(14)','LtRb',0,'C',$fill);
        $this->fpdf->SetFont('Helvetica','',8);
        $this->fpdf->Cell(15,4,'DATE','LTRb',0,'C',$fill);
        $this->fpdf->Cell(60,4,'DATE & NO.','LTRb',0,'C',$fill);
        $this->fpdf->Cell(20,4,'DATE','LtRb',0,'C',$fill);
        $this->fpdf->SetFont('Helvetica','',7);
        $this->fpdf->Cell(15,4,'DATE','LTRb',0,'C',$fill);
        $this->fpdf->Cell(15,4,'','LTRb',0,'C',$fill);
        $this->fpdf->Cell(15,4,'BIRTH','LTRb',0,'C',$fill);
        $this->fpdf->Cell(20,4,'PLACE OF','LTRb',0,'C',$fill);
        $this->fpdf->Cell(15,4,'ATTENDED','LTRb',1,'C',$fill);
        $this->fpdf->SetFont('Helvetica','',8);
        
        $this->fpdf->Cell(12,4,'','LtRb',0,'C',$fill);
        $this->fpdf->SetFont('Helvetica','',7);
        $this->fpdf->Cell(15,4,'(13)','LtRb',0,'C',$fill);
        $this->fpdf->SetFont('Helvetica','',8);
        $this->fpdf->Cell(75,4,'','LtRb',0,'C',$fill);
        $this->fpdf->Cell(15,4,'GIVEN','LtRb',0,'C',$fill);
        $this->fpdf->Cell(60,4,'IRON WITH FOLIC ACID','LtRb',0,'C',$fill);
        $this->fpdf->Cell(20,4,'DETECTED','LtRb',0,'C',$fill);
        $this->fpdf->SetFont('Helvetica','',7);
        $this->fpdf->Cell(15,4,'TERMI','LtRb',0,'C',$fill);
        $this->fpdf->Cell(15,4,'OUTCOME**','LtRb',0,'C',$fill);
        $this->fpdf->Cell(15,4,'WEIGHT','LtRb',0,'C',$fill);
        $this->fpdf->Cell(20,4,'DELIVERY','LtRb',0,'C',$fill);
        $this->fpdf->Cell(15,4,'BY***','LtRb',1,'C',$fill);
        
        $this->fpdf->SetFont('Helvetica','',7);
        $this->fpdf->Cell(12,4,'','LtRB',0,'C',$fill);
        $this->fpdf->Cell(15,4,'','LtRB',0,'C',$fill);
        $this->fpdf->Cell(15,4,'TT1',1,0,'C',$fill);
        $this->fpdf->Cell(15,4,'TT2',1,0,'C',$fill);
        $this->fpdf->Cell(15,4,'TT3',1,0,'C',$fill);
        $this->fpdf->Cell(15,4,'TT4',1,0,'C',$fill);
        $this->fpdf->Cell(15,4,'TT5',1,0,'C',$fill);
        $this->fpdf->SetFont('Helvetica','',8);
        $this->fpdf->Cell(15,4,'VIT. A','LtRB',0,'C',$fill);
        $this->fpdf->Cell(60,4,'WAS GIVEN','LtRB',0,'C',$fill);
        $this->fpdf->SetFont('Helvetica','',7);
        $this->fpdf->Cell(20,4,'(16)','LtRB',0,'C',$fill);
        $this->fpdf->Cell(15,4,'NATED','LtRB',0,'C',$fill);
        $this->fpdf->Cell(15,4,'','LtRB',0,'C',$fill);
        $this->fpdf->Cell(15,4,'','LtRB',0,'C',$fill);
        $this->fpdf->Cell(20,4,'','LtRB',0,'C',$fill);
        $this->fpdf->Cell(15,4,'','LtRB',1,'C',$fill);
    }                
     
}
