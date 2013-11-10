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
  	protected $section = 'dental_health';
  	
        private $validation_rules = array(
                array(
			'field' => 'client_id',
			'label' => 'Client ID',
			'rules' => 'required'
		),
                array(
			'field' => 'orally_fit',
			'label' => 'Orally Fit',
			'rules' => ''
		),
		array(
			'field' => 'date_given_bohc',
			'label' => 'Date Given BOHC',
			'rules' => ''
		),
		array(
			'field' => 'bohc_services',
			'label' => 'BOHC(Basic Oral Health Care) Services',
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
		$this->load->model(array('dental_health_m','clients/clients_m', 'clients/clients_region_m', 'clients/clients_province_m', 'clients/clients_city_m', 'clients/clients_barangay_m'));
		$this->load->library('form_validation');
		$this->lang->load(array('dental_health','clients/clients'));     
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
		redirect('admin/dental_health/my_list');
		
         //determine active param
		$base_where['gender'] = $this->input->post('f_module') ? $this->input->post('f_gender') : '';
        
        //keyphrase param
		$base_where = $this->input->post('f_keywords') ? $base_where + array('name' => $this->input->post('f_keywords')) : $base_where;

		//year param    
		$base_where = $this->input->post('by_year') ? $base_where + array('by_year' => $this->input->post('by_year')) : $base_where;
        
        //Create pagination links
		$pagination = create_pagination('admin/dental_health/index', $this->dental_health_m->count_by($base_where));
        
        $year = date("Y");
        for($i=$year -10; $i < $year + 10; $i++)
        {
            $years[$i]  = $i;
        }
        $this->data->year = $year;
		$this->data->years= $years;
        
        //Set parameters session
        $this->session->set_userdata('base_where',$base_where);
        
        // Using this data, get the relevant results
		$clients = $this->dental_health_m->get_results($pagination['limit'],$base_where);

		//unset the layout if we have an ajax request
		if ($this->input->is_ajax_request()) $this->template->set_layout(FALSE);

		// Render the view
		$this->template
			->title($this->module_details['name'])
			->set('pagination', $pagination)
			->set('clients', $clients)      
			->set_partial('filters', 'admin/partials/filters')   
			->append_metadata(js('admin/filter.js'))
                        ->set_partial('postpartum-list', 'admin/tables/dental_healths');
       				
		$this->input->is_ajax_request() ? $this->template->build('admin/tables/dental_healths', $this->data) : $this->template->build('admin/index', $this->data);	 
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
		$selected_client or redirect('admin/dental_health');
		
                //determine active param
		$base_where['client_id'] = $this->session->userdata('selected_client_id');
		
				// Create pagination links
		$pagination = create_pagination('admin/dental_health/my_list', $this->dental_health_m->count_by($base_where));

		// Using this data, get the relevant results
		$clients = $this->dental_health_m->get_results($pagination['limit'],$base_where);

		//unset the layout if we have an ajax request
		if ($this->input->is_ajax_request()) $this->template->set_layout(FALSE);
		
                $this->data->selected_client = & $selected_client;
		// Render the view
		$this->template
			->title($this->module_details['name'])
			->set('pagination', $pagination)
			->set('clients', $clients)      
                ->set_partial('postpartum-my_list', 'admin/tables/my_list');
       				
		$this->input->is_ajax_request() ? $this->template->build('admin/tables/dental_healths', $this->data) : $this->template->build('admin/list', $this->data);	 
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
				redirect('admin/dental_health');
				break;
		}
	}

        public function add_client()
	{		
                $this->session->set_flashdata('warning', 'Search and Select a Client to be Added to "Dental Health Care" Or <a href="admin/clients/add" class="success-link">Register</a> a New Client.');
                $this->session->set_userdata('current_action', 'dental_health'); 
                redirect('admin/clients');
	}
	
	public function select_client($client_id)
	{		
        if($client_id = $this->dental_health_m->check_dental_health_exist($client_id))
        {	  
            redirect('admin/dental_health/edit/'.$client_id);    
        }
            redirect('admin/dental_health/add/'.$client_id);    
                                       
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
                $client or redirect('admin/dental_health');
                
                $this->form_validation->set_rules($this->validation_rules);
            
                // Loop through each POST item and add it to the secure_post array
                $secure_post = $this->input->post();
                
                $dates = array('date_given_bohc');        
                              
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
                        if($this->dental_health_m->insert($secure_post))
                        {
                                $this->session->set_flashdata('success', 'New Client Successfully Added');
                                if($this->session->userdata('selected_client_id'))
                                        {redirect('admin/dental_health/my_list');}
                                else
                                        {redirect('admin/dental_health');}
                        }
                        else 
                        {
                                $this->session->set_flashdata('error', 'Error Adding Client');
                                redirect("admin/dental_health/add/$client_id");
                        }                                                                
  		}
  		else
  		{
  			// Dirty hack that fixes the issue of having to re-add all data upon an error
  			$dental_health = (object) $_POST;
  		}      
	
                // Loop through each validation rule
		foreach ($this->validation_rules as $rule)
		{
			$dental_health->{$rule['field']} = set_value($rule['field']);
		}
		
		$dental_health->method_action = 'add';
		$dental_health->client_id = $client_id;

		// Render the view
		$this->data->dental_health = & $dental_health;
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
                        redirect("admin/dental_health");
                }
                $count = $this->dental_health_m->check_record($client_id);
                if($count > 0)
                {
                        $this->session->set_flashdata('warning', 'Please Select on the List or '."<a href='admin/dental_health/add/$client_id' class='add'>Add</a>".' New Dental Health Care for this Client');
                        $this->session->set_userdata('selected_client_id',$client_id);
                        $this->session->set_userdata('set', $type);
                        redirect("admin/dental_health/my_list");
                }
                else
                {
                        $this->session->set_flashdata('error', 'No Dental Health Care Found for this Client. Click '."<a href='admin/dental_health/add/$client_id' class='add'>here</a>".' to Add New Dental Health Care for this Client');
                        $this->session->set_userdata('selected_client_id',$client_id);
                        $this->session->set_userdata('set', $type);
                        redirect("admin/dental_health/my_list");
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
                $dental_health = $this->dental_health_m->get_dental_health_details($id);
                
                $client = $this->clients_m->get($dental_health->client_id);
                $client or redirect('admin/dental_health');
                $this->form_validation->set_rules($this->validation_rules);
    
                if ($_POST)
		{
                        // Loop through each POST item and add it to the secure_post array
                        $secure_post = $this->input->post();
                      
                        $dates = array('date_given_bohc');        
                                      
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
                                if($this->dental_health_m->update($id, $secure_post))
                                {
                                        $this->session->set_flashdata('success', 'Client Successfully Updated');
                                        if($this->session->userdata('selected_client_id'))
                                                {redirect('admin/dental_health/my_list');}
                                        else
                                                {redirect('admin/dental_health');}
                                }
                                else 
                                {
                                        $this->session->set_flashdata('error', 'Error updating Client/No Data has been entered.');
                                        redirect("admin/dental_health/edit/$id");
                                }
                                
                                
          		}
          		else
          		{
          			// Dirty hack that fixes the issue of having to re-add all data upon an error
          			$dental_health = (object) $_POST;
          		}
          		foreach ($this->validation_rules as $rule)
          		{
          			$dental_health->{$rule['field']} = set_value($rule['field']);
          		}
		}
		else
		{
                        $dates = array('date_given_bohc');                       
          		foreach ($dates as $date_field)
          		{
                                $dental_health->{$date_field}= $dental_health->{$date_field} ? date('Y-m-d', $dental_health->{$date_field}) : ''; 
          		}	
                }

                $dental_health->method_action = 'edit';
		// Render the view
		$this->data->dental_health = & $dental_health;
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
            				if ($this->dental_health_m->delete($id))
            				{
                                                $deleted++;
            				} 
            				$to_delete++;
            			}
            
            			if ($to_delete > 0)
            			{
            				$this->session->set_flashdata('success', sprintf($this->lang->line('dental_health_mass_delete_success'), $deleted, $to_delete));
            			}
            		}
                        // The array of id's to delete is empty
                        else
    			{
                                $this->session->set_flashdata('error', $this->lang->line('dental_health.delete_error'));    
                        }                        
                }
                else
                {
                        $this->dental_health_m->delete($id)
    			? $this->session->set_flashdata('success', lang('dental_health.delete_success'))
    			: $this->session->set_flashdata('error', lang('dental_health.delete_error'));
		}
                if($this->session->userdata('selected_client_id'))
                        {redirect('admin/dental_health/my_list');}
                else
                        {redirect('admin/dental_health');}
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
    		$pagination = create_pagination('admin/dental_health/index', $this->dental_health_m->count_by($base_where));
    
    		// Using this data, get the relevant results
    		$clients = $this->dental_health_m->get_results($pagination['limit'],$base_where);
        $totalitems = count($clients);
        
        $this->load->library('html2pdf'); // Load library
        $this->html2pdf->fontpath = 'font/'; // Specify font folder     
        
        $pdf=new Html2pdf();
        
        $pdf->Open();
        $pdf->AliasNbPages();
        $pdf->AddPage(' L','Letter');
        $pdf->SetAutoPageBreak(true, 10);
                            
        $pdf->Image(base_url().'system/cms/themes/pyrocms/img/logo_small.png',10,5,40);
        $pdf->SetY(10);
        $pdf->SetFont('Times','B',12);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(0,4,strtoupper('Dental Health Client\'s List'),0,0,'C');
        $pdf->SetFont('Helvetica','',8);
        //$pdf->WriteHTML($html);
        
        $pdf->SetY(15);
        $pdf->SetFillColor(208,208,255);
        $fill = true;
        $pdf->SetFont('Times','B',9);
        $pdf->Cell(10,5,'No.',1,0,'L',$fill);
        $pdf->Cell(50,5,'Full Name',1,0,'L',$fill);
        $pdf->Cell(40,5,'Address',1,0,'L',$fill);
        $pdf->Cell(10,5,'Age',1,0,'L',$fill);
        $pdf->Cell(15,5,'Gender',1,0,'L',$fill);
        $pdf->Cell(20,5,'Given BOHC',1,0,'L',$fill);
        $pdf->Cell(80,5,'Services',1,0,'L',$fill);
        $pdf->Cell(35,5,'Remarks',1,1,'L',$fill);
        
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
                $pdf->Cell(10,4,$x,1,0,'L');
                $pdf->Cell(50,4,trim($client->last_name).', '.trim($client->first_name).' '.trim($client->middle_name),1,0,'L');
                $pdf->Cell(40,4,$address,1,0,'L');
                $pdf->Cell(10,4,$age,1,0,'R');
                $pdf->Cell(15,4,ucfirst($client->gender),1,0,'L');
                $pdf->Cell(20,4,date('M j, Y', $client->date_given_bohc),1,0,'R');
                $pdf->Cell(80,4,$client->bohc_services,1,0,'L');
                $pdf->Cell(35,4,substr($client->remarks,0,25),1,1,'L');     

                if($i==45)
                {
                    if($x < $totalitems)
                    {
                      $pdf->SetY(-15);
                      $pdf->SetTextColor(128);
                      $pdf->SetFont('Times','I',7);
                      $pdf->Cell(10,4,'Page '.$pdf->PageNo().'/{nb}',0,0,'C');
                      $pdf->SetFont('Times','',7);
                      $pdf->Cell(0,4,'Copyright © 2013 IMPROVHEALTH | Provided by: Touch Foundation Inc.',0,0,'C');
                      
                      $pdf->AliasNbPages();
                      $pdf->AddPage(' L','Letter');
                      $pdf->SetTitle('Improvhealth - Dental Health Client List');
                      $pdf->SetAutoPageBreak(true, 10);
                                          
                      $pdf->Image(base_url().'system/cms/themes/pyrocms/img/logo_small.png',10,5,40);
                      $pdf->SetY(10);
                      $pdf->SetFont('Times','B',10);
                      $pdf->SetTextColor(0,0,0);
                      $pdf->Cell(0,4,strtoupper('Dental Health Client List'),0,0,'C');
                      $pdf->SetFont('Times','',8);
                      //$pdf->WriteHTML($html);
                      
                      $pdf->SetY(15);
                      $pdf->SetFillColor(208,208,255);
                      $fill = true;
                      $pdf->SetFont('Times','B',9);
                      $pdf->Cell(10,5,'No.',1,0,'L',$fill);
                      $pdf->Cell(50,5,'Full Name',1,0,'L',$fill);
                      $pdf->Cell(40,5,'Address',1,0,'L',$fill);
                      $pdf->Cell(10,5,'Age',1,0,'L',$fill);
                      $pdf->Cell(15,5,'Gender',1,0,'L',$fill);
                      $pdf->Cell(20,5,'Given BOHC',1,0,'L',$fill);
                      $pdf->Cell(80,5,'Services',1,0,'L',$fill);
                      $pdf->Cell(35,5,'Remarks',1,1,'L',$fill);
                      
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
        
        $pdf->Output('Dental_Clients_List.pdf','I');  
	} 
	
}
