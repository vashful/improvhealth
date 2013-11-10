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
	
  	protected $section = 'environmental_health';
  	
    private $validation_rules = array(
                array(
			'field' => 'client_id',
			'label' => 'Client ID',
			'rules' => 'required'
		),
		array(
			'field' => 'date_conducted',
			'label' => 'Date Conducted',
			'rules' => 'required'
		), 
                array(
			'field' => 'hh_safe_water',
			'label' => 'Access to Safe Improved or Safe Water',
			'rules' => ''
		),                      
		array(
			'field' => 'hh_safe_water_level',
			'label' => 'Safe Water - Access Level',
			'rules' => ''
		),
		array(
			'field' => 'hh_sanitary_toilet',
			'label' => 'Sanitary Toilet',
			'rules' => ''
		),
		array(
			'field' => 'hh_satisfactory_waste_disposal',
			'label' => 'Satisfactory Disposal of Solid Waste',
			'rules' => ''
		),
		array(
			'field' => 'hh_complete_sanitation_facility',
			'label' => 'Complete Basic Sanitation Facility',
			'rules' => ''
		),
		array(
			'field' => 'food_establishment',
			'label' => 'Food Establishment',
			'rules' => ''
		),
		array(
			'field' => 'food_establishment_sanitary_permit',
			'label' => 'Food Establishment - Sanitary Permit ',
			'rules' => ''
		),
		array(
			'field' => 'food_handler',
			'label' => 'Food Handler',
			'rules' => ''
		),
		array(
			'field' => 'food_handler_health_certificate',
			'label' => 'Food Handler - Health Certificate',
			'rules' => ''
		),
		array(
			'field' => 'salt_sample_tested',
			'label' => 'Salt Samples Tested',
			'rules' => ''
		),
		array(
			'field' => 'salt_sample_iodine',
			'label' => 'Salt Samples Tested - Positive for Iodine',
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
		$this->load->model(array('environmental_health_m','clients/clients_m', 'clients/clients_region_m', 'clients/clients_province_m', 'clients/clients_city_m', 'clients/clients_barangay_m'));
		$this->load->library('form_validation');
		$this->lang->load(array('environmental_health','clients/clients'));     
		//$this->output->enable_profiler(TRUE);
		// Date ranges for select boxes
		$this->data->hours = array_combine($hours = range(0, 23), $hours);
		$this->data->minutes = array_combine($minutes = range(0, 59), $minutes);
		                
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
		redirect('admin/environmental_health/my_list');
		
        //determine active param
		$base_where['gender'] = $this->input->post('f_module') ? $this->input->post('f_gender') : '';
        
        //keyphrase param
		$base_where = $this->input->post('f_keywords') ? $base_where + array('name' => $this->input->post('f_keywords')) : $base_where;

		//year param    
		$base_where = $this->input->post('by_year') ? $base_where + array('by_year' => $this->input->post('by_year')) : $base_where;

		//Set parameters session
        $this->session->set_userdata('base_where',$base_where);
        
        // Create pagination links
		$pagination = create_pagination('admin/environmental_health/index', $this->environmental_health_m->count_by($base_where));

		$year = date("Y");
        for($i=$year -10; $i < $year + 10; $i++)
        {
            $years[$i]  = $i;
        }
        $this->data->year = $year;
		$this->data->years= $years;
        
        // Using this data, get the relevant results
		$clients = $this->environmental_health_m->get_results($pagination['limit'],$base_where);

		//unset the layout if we have an ajax request
		if ($this->input->is_ajax_request()) $this->template->set_layout(FALSE);

		// Render the view
		$this->template
			->title($this->module_details['name'])
			->set('pagination', $pagination)
			->set('clients', $clients)      
			->set_partial('filters', 'admin/partials/filters')   
			->append_metadata(js('admin/filter.js'))
                ->set_partial('environmental_health-list', 'admin/tables/clients');
       				
		$this->input->is_ajax_request() ? $this->template->build('admin/tables/clients', $this->data) : $this->template->build('admin/index', $this->data);	 
	}
	
			/**
	 * Create a new client role
	 *
	 * @access public
	 * @return void
	 */
	public function my_list()
	{
		$selected_client = $this->clients_m->get($this->session->userdata('selected_client_id'));
		$selected_client or redirect('admin/environmental_health');
		
                //determine active param
		$base_where['client_id'] = $this->session->userdata('selected_client_id');
		
				// Create pagination links
		$pagination = create_pagination('admin/environmental_health/my_list', $this->environmental_health_m->count_by($base_where));

		// Using this data, get the relevant results
		$clients = $this->environmental_health_m->get_results($pagination['limit'],$base_where);

		//unset the layout if we have an ajax request
		if ($this->input->is_ajax_request()) $this->template->set_layout(FALSE);
		
                $this->data->selected_client = & $selected_client;
		// Render the view
		$this->template
			->title($this->module_details['name'])
			->set('pagination', $pagination)
			->set('clients', $clients)      
                ->set_partial('environmental_health-my_list', 'admin/tables/my_list');
       				
		$this->input->is_ajax_request() ? $this->template->build('admin/tables/clients', $this->data) : $this->template->build('admin/list', $this->data);	 
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
                $this->session->set_flashdata('warning', 'Search and Select a Client to be Added to "Environmental Health Care" Or <a href="admin/clients/add" class="success-link">Register</a> a New Client.');
                $this->session->set_userdata('current_action', 'environmental_health'); 
                redirect('admin/clients');
	}
	
	public function select_client($client_id)
	{		
                redirect('admin/environmental_health/add/'.$client_id);
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
                $client = $this->clients_m->get($client_id);
                $client or redirect('admin/environmental_health');
                
                $this->form_validation->set_rules($this->validation_rules);
		
                if ($_POST)
                {
                        // Loop through each POST item and add it to the secure_post array
                        $secure_post = $this->input->post();
                        
                        $dates = array('date_conducted');        
                                      
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
                                if($this->environmental_health_m->insert($secure_post))
                                {
                                        $this->session->set_flashdata('success', 'New Environmental Health Transaction Successfully Added');
                                        if($this->session->userdata('selected_client_id'))
                                                {redirect('admin/environmental_health/my_list');}
                                        else
                                                {redirect('admin/environmental_health');}
                                }
                                else 
                                {
                                        $this->session->set_flashdata('error', 'Error Adding Environmental Health Transaction');
                                        redirect("admin/environmental_health/add/$client_id");
                                }                                                              
          		}
          		else
          		{
          			// Dirty hack that fixes the issue of having to re-add all data upon an error
          			$environmental_health = (object) $_POST;
          		}      
		}
                // Loop through each validation rule
		foreach ($this->validation_rules as $rule)
		{
			$environmental_health->{$rule['field']} = set_value($rule['field']);
		}

		$environmental_health->method_action = 'add';
		$environmental_health->client_id = $client_id;
		
		$this->data->access_level = array('Level 1','Level 2','Level 3');

		// Render the view
		$this->data->environmental_health = & $environmental_health;
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
                        redirect("admin/environmental_health");
                }
                $count = $this->environmental_health_m->check_record($client_id);
                if($count > 0)
                {
                        $this->session->set_flashdata('warning', 'Please Select on the List or '."<a href='admin/environmental_health/add/$client_id' class='add'>Add</a>".' New Environmental Health Care for this Client');
                        $this->session->set_userdata('selected_client_id',$client_id);
                        $this->session->set_userdata('set', $type);
                        redirect("admin/environmental_health/my_list");
                }
                else
                {
                        $this->session->set_flashdata('error', 'No Environmental Health Care Found for this Client. Click '."<a href='admin/environmental_health/add/$client_id' class='add'>here</a>".' to Add New Environmental Health Care for this Client');
                        $this->session->set_userdata('selected_client_id',$client_id);
                        $this->session->set_userdata('set', $type);
                        redirect("admin/environmental_health/my_list");
                }        
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
                $environmental_health = $this->environmental_health_m->get_environmental_health_details($id);
                
                $client = $this->clients_m->get($environmental_health->client_id);
                $client or redirect('admin/environmental_health');
                
                $this->form_validation->set_rules($this->validation_rules);
		       
                if ($_POST)
		{
                // Loop through each POST item and add it to the secure_post array
                $secure_post = $this->input->post();
                
                $dates = array('date_conducted');        
                              
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
                                if($this->environmental_health_m->update($id, $secure_post))
                                {
                                  $this->session->set_flashdata('success', 'Client Successfully Updated');
                                  if($this->session->userdata('selected_client_id'))
                                    redirect('admin/environmental_health/my_list');
                                  else
                                    redirect('admin/environmental_health');
                                }
                                else 
                                {
                                  $this->session->set_flashdata('error', 'Error updating Client/No Data has been entered.');
                                  redirect("admin/environmental_health/edit/$id");
                                }
                                
                                
          		}
          		else
          		{
          			// Dirty hack that fixes the issue of having to re-add all data upon an error
          			$environmental_health = (object) $_POST;
          		}
          		foreach ($this->validation_rules as $rule)
          		{
          			$environmental_health->{$rule['field']} = set_value($rule['field']);
          		}
		}
		else
		{
                        $dates = array('date_conducted');                       
          		foreach ($dates as $date_field)
          		{
                                $environmental_health->{$date_field}= $environmental_health->{$date_field} ? date('Y-m-d', $environmental_health->{$date_field}) : ''; 
          		}	
                }

                $environmental_health->method_action = 'edit';
    
                $this->data->access_level = array('Level 1','Level 2','Level 3');
    
		// Render the view
		$this->data->environmental_health = & $environmental_health;
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
    				if ($this->environmental_health_m->delete($id))
    				{
    					$deleted++;
    				}
    				$to_delete++;
    			}
    
    			if ($to_delete > 0)
    			{
    				$this->session->set_flashdata('success', sprintf($this->lang->line('environmental_health_mass_delete_success'), $deleted, $to_delete));
    			}
    		}
    		// The array of id's to delete is empty
    		else
    			$this->session->set_flashdata('error', $this->lang->line('environmental_health.delete_error'));
    }
    else
    {
        $this->environmental_health_m->delete($id)
    			? $this->session->set_flashdata('success', lang('environmental_health.delete_success'))
    			: $this->session->set_flashdata('error', lang('environmental_health.delete_error'));
		}
    if($this->session->userdata('selected_client_id'))
      redirect('admin/environmental_health/my_list');
    else
		redirect('admin/environmental_health');
	}
	
	public function check_data($str, $fields)
	{
    foreach (explode(',', $fields) as $field)
  	{
  		if (!$str && !empty($_POST[$field]))
  		{
  			$this->form_validation->set_message('check_data', 'The %s field is required.');
  			return FALSE;
  		}
  	}
		return TRUE;
	}

  public function print_page()
	{
    		$base_where = $this->session->userdata('base_where');
      
    		// Create pagination links
    		$pagination = create_pagination('admin/environmental_health/index', $this->environmental_health_m->count_by($base_where));
    
    		// Using this data, get the relevant results
    		$clients = $this->environmental_health_m->get_results($pagination['limit'],$base_where);
        $totalitems = count($clients);
    
        $this->load->library('html2pdf'); // Load library
        $this->html2pdf->fontpath = 'font/'; // Specify font folder     
        
        $pdf=new Html2pdf();
        
        $pdf->Open();
        $pdf->AliasNbPages();
        $pdf->AddPage('L','Letter');
        $pdf->SetAutoPageBreak(true, 10);
                            
        $pdf->Image(base_url().'system/cms/themes/pyrocms/img/logo_small.png',10,5,40);
        $pdf->SetY(10);
        $pdf->SetFont('Times','B',12);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(0,4,strtoupper('Environmental Health Client List'),0,0,'C');
        $pdf->SetFont('Times','',8);
        //$pdf->WriteHTML($html);
        
        $pdf->SetY(15);
        $pdf->SetFillColor(208,208,255);
        $fill = true;
        $pdf->SetFont('Times','B',9);
        $pdf->Cell(8,4,'No.','LTRb',0,'L',$fill);
        $pdf->Cell(42,4,'Full Name','LTRb',0,'L',$fill);
        $pdf->Cell(10,4,'Age','LTRb',0,'L',$fill);
        $pdf->Cell(13,4,'Gender','LTRb',0,'L',$fill);
        $pdf->Cell(20,4,'Conducted','LTRb',0,'L',$fill);
        $pdf->Cell(15,4,'Access to','LTRb',0,'C',$fill);
        $pdf->Cell(10,4,'Level','LTRb',0,'L',$fill);
        $pdf->Cell(15,4,'Sanitary','LTRb',0,'C',$fill);
        $pdf->Cell(20,4,'Solid Waste','LTRb',0,'L',$fill); 
        $pdf->Cell(25,4,'Complete Basic','LTRb',0,'C',$fill);
        $pdf->Cell(23,4,'Food','LTRb',0,'C',$fill);
        $pdf->Cell(20,4,'w/ Health','LTRb',0,'C',$fill);
        $pdf->Cell(20,4,'Food','LTRb',0,'C',$fill);
        $pdf->Cell(20,4,'w/ Health','LTRb',1,'C',$fill);
        
        $pdf->SetFont('Times','B',9);
        $pdf->Cell(8,4,'','LtRB',0,'L',$fill);
        $pdf->Cell(42,4,'','LtRB',0,'L',$fill);
        $pdf->Cell(10,4,'','LtRB',0,'L',$fill);
        $pdf->Cell(13,4,'','LtRB',0,'L',$fill);
        $pdf->Cell(20,4,'','LtRB',0,'L',$fill);
        $pdf->Cell(15,4,'safe water','LtRB',0,'C',$fill);
        $pdf->Cell(10,4,'','LtRB',0,'L',$fill);
        $pdf->Cell(15,4,'Toilet','LtRB',0,'C',$fill);
         $pdf->Cell(20,4,'Disposal','LtRB',0,'L',$fill); 
        $pdf->Cell(25,4,'Sani. Facilities','LtRB',0,'C',$fill);
        $pdf->Cell(23,4,'Establishment','LtRB',0,'C',$fill);
        $pdf->Cell(20,4,'Certificate','LtRB',0,'C',$fill);
        $pdf->Cell(20,4,'Handler','LtRB',0,'C',$fill);
        $pdf->Cell(20,4,'Certificate','LtRB',1,'C',$fill);  
        
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
                        {$address = $client->residence.','.$client->address;} 
                $pdf->SetFont('Times','',8);
                $pdf->Cell(8,4,$i,1,0,'L');
                $pdf->Cell(42,4,trim($client->last_name).', '.trim($client->first_name).' '.trim($client->middle_name),1,0,'L');
                $pdf->Cell(10,4,$age,1,0,'R');
                $pdf->Cell(13,4,ucfirst($client->gender),1,0,'L');
                $pdf->Cell(20,4,date('M j, Y', $client->date_conducted),1,0,'R');
                $pdf->Cell(15,4,$client->hh_safe_water,1,0,'C');
                $pdf->Cell(10,4,$client->hh_safe_water_level,1,0,'C');
                $pdf->Cell(15,4,$client->hh_sanitary_toilet,1,0,'C');
                $pdf->Cell(20,4,$client->hh_satisfactory_waste_disposal,1,0,'C');
                $pdf->Cell(25,4,$client->hh_complete_sanitation_facility,1,0,'C');
                $pdf->Cell(23,4,$client->food_establishment,1,0,'C');
                $pdf->Cell(20,4,$client->food_establishment_sanitary_permit,1,0,'C');
                $pdf->Cell(20,4,$client->food_handler,1,0,'C');
                $pdf->Cell(20,4,$client->food_handler_health_certificate,1,1,'C');
                if($i==44)
                {
                    if($x < $totalitems)
                    {
                        $pdf->SetY(-15);
                        $pdf->SetTextColor(128);
                        $pdf->SetFont('Times','I',7);
                        $pdf->Cell(10,4,'Page '.$pdf->PageNo().'/{nb}',0,0,'C');
                        $pdf->SetFont('Times','',7);
                        $pdf->Cell(0,4,'Copyright © 2013 IMPROVHEALTH | Provided by: Touch Foundation Inc.',0,0,'C');
                        
                        $pdf->AddPage('L','Letter');
                        $pdf->SetAutoPageBreak(true, 10);
                                          
                        $pdf->Image(base_url().'system/cms/themes/pyrocms/img/logo_small.png',10,5,40);
                        $pdf->SetY(10);
                        $pdf->SetFont('Times','B',12);
                        $pdf->SetTextColor(0,0,0);
                        $pdf->Cell(0,4,strtoupper('Environmental Health Client List'),0,0,'C');
                        $pdf->SetFont('Times','',8);
                        //$pdf->WriteHTML($html);
                        
                        $pdf->SetY(15);
                        $pdf->SetFillColor(208,208,255);
                        $fill = true;
                        $pdf->SetFont('Times','B',9);
                        $pdf->Cell(8,4,'No.','LTRb',0,'L',$fill);
                        $pdf->Cell(42,4,'Full Name','LTRb',0,'L',$fill);
                        $pdf->Cell(10,4,'Age','LTRb',0,'L',$fill);
                        $pdf->Cell(13,4,'Gender','LTRb',0,'L',$fill);
                        $pdf->Cell(20,4,'Conducted','LTRb',0,'L',$fill);
                        $pdf->Cell(15,4,'Access to','LTRb',0,'C',$fill);
                        $pdf->Cell(10,4,'Level','LTRb',0,'L',$fill);
                        $pdf->Cell(15,4,'Sanitary','LTRb',0,'C',$fill);
                        $pdf->Cell(20,4,'Solid Waste','LTRb',0,'L',$fill); 
                        $pdf->Cell(25,4,'Complete Basic','LTRb',0,'C',$fill);
                        $pdf->Cell(23,4,'Food','LTRb',0,'C',$fill);
                        $pdf->Cell(20,4,'w/ Health','LTRb',0,'C',$fill);
                        $pdf->Cell(20,4,'Food','LTRb',0,'C',$fill);
                        $pdf->Cell(20,4,'w/ Health','LTRb',1,'C',$fill);
                        
                        $pdf->SetFont('Times','B',9);
                        $pdf->Cell(8,4,'','LtRB',0,'L',$fill);
                        $pdf->Cell(42,4,'','LtRB',0,'L',$fill);
                        $pdf->Cell(10,4,'','LtRB',0,'L',$fill);
                        $pdf->Cell(13,4,'','LtRB',0,'L',$fill);
                        $pdf->Cell(20,4,'','LtRB',0,'L',$fill);
                        $pdf->Cell(15,4,'safe water','LtRB',0,'C',$fill);
                        $pdf->Cell(10,4,'','LtRB',0,'L',$fill);
                        $pdf->Cell(15,4,'Toilet','LtRB',0,'C',$fill);
                        $pdf->Cell(20,4,'Disposal','LtRB',0,'L',$fill); 
                        $pdf->Cell(25,4,'Sani. Facilities','LtRB',0,'C',$fill);
                        $pdf->Cell(23,4,'Establishment','LtRB',0,'C',$fill);
                        $pdf->Cell(20,4,'Certificate','LtRB',0,'C',$fill);
                        $pdf->Cell(20,4,'Handler','LtRB',0,'C',$fill);
                        $pdf->Cell(20,4,'Certificate','LtRB',1,'C',$fill);
                    }
                }
                else
                {
                    $i=$i+1;
                }   
                $x=$x+1;           
        }
        
        $pdf->SetY(-15);
        $pdf->SetTextColor(128);
        $pdf->SetFont('Times','I',7);
        $pdf->Cell(10,4,'Page '.$pdf->PageNo().'/{nb}',0,0,'C');
        $pdf->SetFont('Times','',7);
        $pdf->Cell(0,4,'Copyright © 2013 IMPROVHEALTH | Provided by: Touch Foundation Inc.',0,0,'C');
        $pdf->SetTextColor(0,0,0);
        
        $pdf->Output('Environmental_Health_Clients_List.pdf','I');        
	} 
	
}
