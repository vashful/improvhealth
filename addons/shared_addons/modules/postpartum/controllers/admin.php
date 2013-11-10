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
	
  	protected $section = 'postpartum';
  	
    private $validation_rules = array(
    array(
			'field' => 'client_id',
			'label' => 'Client ID',
			'rules' => 'required'
		),
		array(
			'field' => 'delivery',
			'label' => 'Date of Delivery',
			'rules' => 'required'
		),
		array(
			'field' => 'delivery_on_hour',
			'label' => 'Hour(s) of Delivery',
			'rules' => 'trim|numeric|required'
		),
		array(
			'field' => 'delivery_on_minute',
			'label' => 'Minute(s) of Delivery',
			'rules' => 'trim|numeric|required'
		),
		array(
			'field' => 'visits_day',
			'label' => 'Date Post-Partum Visits w/in 24 hours after Delivery',
			'rules' => ''
		),
		array(
			'field' => 'visits_week',
			'label' => 'Date Post-Partum Visits w/in one week after Delivery',
			'rules' => ''
		),
		array(
			'field' => 'breastfeeding',
			'label' => 'Date and Time Initiated Breastfeeding',
			'rules' => 'callback_check_data[breastfeeding,breastfeeding_on_hour,breastfeeding_on_minute]'
		),
		array(
			'field' => 'breastfeeding_on_hour',
			'label' => 'Hour(s) Initiated Breastfeeding',
			'rules' => 'trim|numeric|callback_check_data[breastfeeding,breastfeeding_on_hour,breastfeeding_on_minute]'
		),
		array(
			'field' => 'breastfeeding_on_minute',
			'label' => 'Minute(s) Initiated Breastfeeding',
			'rules' => 'trim|numeric|callback_check_data[breastfeeding,breastfeeding_on_hour,breastfeeding_on_minute]'
		),
		array(
			'field' => 'iron1_date',
			'label' => '1st Micronutrient Supplementation Iron Date Given',
			'rules' => ''
		),
		array(
			'field' => 'iron1_tabs',
			'label' => '1st Micronutrient Supplementation Iron Number of Tablets Given',
			'rules' => 'integer'
		),
		array(
			'field' => 'iron2_date',
			'label' => '2nd Micronutrient Supplementation Iron Date Given',
			'rules' => ''
		),
		array(
			'field' => 'iron2_tabs',
			'label' => '2nd Micronutrient Supplementation Iron Number of Tablets Given',
			'rules' => 'integer'
		),
		array(
			'field' => 'iron3_date',
			'label' => '3rd Micronutrient Supplementation Iron Date Given',
			'rules' => ''
		),
		array(
			'field' => 'iron3_tabs',
			'label' => '3rd Micronutrient Supplementation Iron Number of Tablets Given',
			'rules' => 'integer'
		),
		array(
			'field' => 'vitamin_a',
			'label' => 'Micronutrient Supplementation Vitamin A Date Given',
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
		$this->load->model(array('postpartum_m','clients/clients_m', 'clients/clients_region_m', 'clients/clients_province_m', 'clients/clients_city_m', 'clients/clients_barangay_m'));
		$this->load->library('form_validation');
        $this->load->library('fpdf');
		$this->lang->load(array('postpartum','clients/clients'));     
		//$this->output->enable_profiler(TRUE);
		// Date ranges for select boxes
		$this->data->hours = array_combine($hours = range(0, 23), $hours);
		$this->data->minutes = array_combine($minutes = range(0, 59), $minutes);
        
        $this->data->postpartum_filters = array(
          'ppv2'  => 'Women with 2 Postpartum Visits',
          'vita'  => 'Women given Vitamin A',
          'iron'  => 'Women given complete Iron',
          'bf'  => 'Women initiated Breastfeeding');	
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
		redirect('admin/postpartum/my_list');
		
		//keyphrase param
		$base_where['name'] = $this->input->post('f_keywords') ? $this->input->post('f_keywords') : '';
        //barangay param   
    $base_where = $this->input->post('f_barangays') ? $base_where + array('barangay' => $this->input->post('f_barangays')) : $base_where;
     
        //year param    
        $base_where = $this->input->post('by_year') ? $base_where + array('by_year' => $this->input->post('by_year')) : $base_where;
        
        //prenatal visits param    
		$base_where = $this->input->post('postpartum_filters') ? $base_where + array('postpartum_filters' => $this->input->post('postpartum_filters')) : $base_where;

		//Set parameters session
        $this->session->set_userdata('base_where',$base_where);
        
        // Create pagination links
		$pagination = create_pagination('admin/postpartum/index', $this->postpartum_m->count_by($base_where));

		$year = date("Y");
        for($i=$year; $i >= $year - 10; $i--)
        {
            $years[$i]  = $i;
        }
        $this->data->year = $year;
		$this->data->years= $years;
        
        // Using this data, get the relevant results
		$clients = $this->postpartum_m->get_results($pagination['limit'],$base_where);
        
        $this->data->totalitems = $this->postpartum_m->count_by($base_where);
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
                ->set_partial('postpartum-list', 'admin/tables/clients');
       				
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
		$selected_client or redirect('admin/postpartum');
		
                //determine active param
		$base_where['client_id'] = $this->session->userdata('selected_client_id');
		
				// Create pagination links
		$pagination = create_pagination('admin/postpartum/my_list', $this->postpartum_m->count_by($base_where));

		// Using this data, get the relevant results
		$clients = $this->postpartum_m->get_results($pagination['limit'],$base_where);

		//unset the layout if we have an ajax request
		if ($this->input->is_ajax_request()) $this->template->set_layout(FALSE);
		
                $this->data->selected_client = & $selected_client;
		// Render the view
		$this->template
			->title($this->module_details['name'])
			->set('pagination', $pagination)
			->set('clients', $clients)      
                ->set_partial('postpartum-my_list', 'admin/tables/my_list');
       				
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
                $this->session->set_flashdata('warning', 'Search and Select a Client to be Added to "Postpartum Care" Or <a href="admin/clients/add" class="success-link">Register</a> a New Client.');
                $this->session->set_userdata('current_action', 'postpartum'); 
                redirect('admin/clients');
	}
	
	public function select_client($client_id)
	{		
               	  if($id = $this->postpartum_m->check_postpartum_exist($client_id))
               	  {	  
                   redirect('admin/postpartum/edit/'.$id);
                  }
                   redirect('admin/postpartum/add/'.$client_id);
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
     $this->session->set_flashdata('warning', 'Please Add New Consultation for this Client before Adding Postpartum Care');
     redirect('admin/consultations/add/'.$client_id);
    } 
      
    $client = $this->clients_m->get($client_id);
    $client or redirect('admin/postpartum');
    
    $this->form_validation->set_rules($this->validation_rules);
    
    if ($this->input->post('delivery'))
		{
			$delivery = strtotime(sprintf('%s %s:%s', $this->input->post('delivery'), $this->input->post('delivery_on_hour'), $this->input->post('delivery_on_minute')));
		}
		else
		{
			$delivery = '';
		}
		
		if ($this->input->post('breastfeeding'))
		{
			$breastfeeding = strtotime(sprintf('%s %s:%s', $this->input->post('breastfeeding'), $this->input->post('breastfeeding_on_hour'), $this->input->post('breastfeeding_on_minute')));
		}
		else
		{
			$breastfeeding = '';
		}
		
    if ($_POST)
		{
      // Loop through each POST item and add it to the secure_post array
      $secure_post = $this->input->post();
        
      $dates = array('visits_day','visits_week','iron1_date','iron2_date'
                      ,'iron3_date','vitamin_a');        
                              
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
  		if ($this->input->post('delivery'))
  		{
  			$secure_post['delivery'] = strtotime(sprintf('%s %s:%s', $this->input->post('delivery'), $this->input->post('delivery_on_hour'), $this->input->post('delivery_on_minute')));
  		}
  		if ($this->input->post('breastfeeding'))
  		{
  			$secure_post['breastfeeding'] = strtotime(sprintf('%s %s:%s', $this->input->post('breastfeeding'), $this->input->post('breastfeeding_on_hour'), $this->input->post('breastfeeding_on_minute')));
  		}
      

          		if ($this->form_validation->run() !== FALSE)
          		{ 
                                if($this->postpartum_m->insert($secure_post))
                                {
                                  $this->session->set_flashdata('success', 'New Client Successfully Added');
                                  $this->session->set_userdata('consultation_id', 0);
                                  if($this->session->userdata('selected_client_id'))
                                    redirect('admin/postpartum/my_list');
                                  else
                                    redirect('admin/postpartum');
                                }
                                else 
                                {
                                $this->session->set_flashdata('error', 'Error Adding Client');
                                redirect("admin/postpartum/add/$client_id");
                                }
                                
                                
          		}
          		else
          		{
          			// Dirty hack that fixes the issue of having to re-add all data upon an error
          			$postpartum = (object) $_POST;

          		}      
		}
                // Loop through each validation rule
		foreach ($this->validation_rules as $rule)
		{
			$postpartum->{$rule['field']} = set_value($rule['field']);
		}

		$postpartum->delivery = $delivery;
		$postpartum->breastfeeding = $breastfeeding;

		$postpartum->method_action = 'add';
		$postpartum->client_id = $client_id;

		// Render the view
		$this->data->postpartum = & $postpartum;
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
        redirect("admin/postpartum");
    }
    $count = $this->postpartum_m->check_record($client_id);
    if($count > 0)
    {
        $this->session->set_flashdata('warning', 'Please Select on the List or '."<a href='admin/postpartum/add/$client_id' class='add'>Add</a>".' New Postpartum Care for this Client');
        $this->session->set_userdata('selected_client_id',$client_id);     
        $this->session->set_userdata('set', $type);
        redirect("admin/postpartum/my_list");
    }
    else
    {
        $this->session->set_flashdata('error', 'No Postpartum Care Found for this Client. Click '."<a href='admin/postpartum/add/$client_id' class='add'>here</a>".' to Add New Postpartum Care for this Client');
        $this->session->set_userdata('selected_client_id',$client_id);     
        $this->session->set_userdata('set', $type);
        redirect("admin/postpartum/my_list");
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
	  $postpartum = $this->postpartum_m->get_postpartum_details($id);
	  
		$client = $this->clients_m->get($postpartum->client_id);
    $client or redirect('admin/postpartum');
    
    $this->form_validation->set_rules($this->validation_rules);
		
    if ($this->input->post('delivery'))
		{
			$delivery = strtotime(sprintf('%s %s:%s', $this->input->post('delivery'), $this->input->post('delivery_on_hour'), $this->input->post('delivery_on_minute')));
		}
		else
		{
			$delivery = '';
		}
		
		if ($this->input->post('breastfeeding'))
		{
			$breastfeeding = strtotime(sprintf('%s %s:%s', $this->input->post('breastfeeding'), $this->input->post('breastfeeding_on_hour'), $this->input->post('breastfeeding_on_minute')));
		}
		else
		{
			$breastfeeding = '';
		}
    
    if ($_POST)
		{
      // Loop through each POST item and add it to the secure_post array
      $secure_post = $this->input->post();
      
      $dates = array('visits_day','visits_week','iron1_date','iron2_date'
                      ,'iron3_date','vitamin_a');        
                              
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
  		if ($this->input->post('delivery'))
  		{
  			$secure_post['delivery'] = strtotime(sprintf('%s %s:%s', $this->input->post('delivery'), $this->input->post('delivery_on_hour'), $this->input->post('delivery_on_minute')));
  		}
  		if ($this->input->post('breastfeeding'))
  		{
  			$secure_post['breastfeeding'] = strtotime(sprintf('%s %s:%s', $this->input->post('breastfeeding'), $this->input->post('breastfeeding_on_hour'), $this->input->post('breastfeeding_on_minute')));
  		}
               
          		if ($this->form_validation->run() !== FALSE)
          		{ 
                                if($this->postpartum_m->update($id, $secure_post))
                                {
                                  $this->session->set_flashdata('success', 'Client Successfully Updated');
                                  if($this->session->userdata('selected_client_id'))
                                    redirect('admin/postpartum/my_list');
                                  else
                                    redirect('admin/postpartum');
                                }
                                else 
                                {
                                  $this->session->set_flashdata('error', 'Error updating Client/No Data has been entered.');
                                  redirect("admin/postpartum/edit/$id");
                                }
                                
                                
          		}
          		else
          		{
          			// Dirty hack that fixes the issue of having to re-add all data upon an error
          			$postpartum = (object) $_POST;
          		}
          		foreach ($this->validation_rules as $rule)
          		{
          			$postpartum->{$rule['field']} = set_value($rule['field']);
          		}
		}
		else
		{
            $dates = array('visits_day','visits_week','iron1_date','iron2_date'
                      ,'iron3_date','vitamin_a');                       
          		foreach ($dates as $date_field)
          		{
                    $postpartum->{$date_field}= $postpartum->{$date_field} ? date('Y-m-d', $postpartum->{$date_field}) : ''; 
          		}	
    }

    $postpartum->method_action = 'edit';
		// Render the view
		$this->data->postpartum = & $postpartum;
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
    				if ($this->postpartum_m->delete($id))
    				{
    					$deleted++;
    				}
    				$to_delete++;
    			}
    
    			if ($to_delete > 0)
    			{
    				$this->session->set_flashdata('success', sprintf($this->lang->line('postpartum_mass_delete_success'), $deleted, $to_delete));
    			}
    		}
    		// The array of id's to delete is empty
    		else
    			$this->session->set_flashdata('error', $this->lang->line('postpartum.delete_error'));
    }
    else
    {
        $this->postpartum_m->delete($id)
    			? $this->session->set_flashdata('success', lang('postpartum.delete_success'))
    			: $this->session->set_flashdata('error', lang('postpartum.delete_error'));
		}
    if($this->session->userdata('selected_client_id'))
      redirect('admin/postpartum/my_list');
    else
		redirect('admin/postpartum');
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
		$pagination = create_pagination('admin/postpartum/index', $this->postpartum_m->count_by($base_where));

		// Using this data, get the relevant results
		$clients = $this->postpartum_m->get_results($pagination['limit'],$base_where);
        //echo print_r($clients);
        $totalitems = count($clients);   
        
        $this->fpdf->Open();
        $this->fpdf->AliasNbPages();
        $this->fpdf->AddPage('L','A4');
        $this->fpdf->SetAutoPageBreak(true, 10);
                            
        $this->fpdf->Image(base_url().'system/cms/themes/pyrocms/img/logo_small.png',10,5,40);
        $this->fpdf->SetY(10);
        $this->fpdf->SetFont('Helvetica','B',16);
        $this->fpdf->SetTextColor(0,0,0);
        $this->fpdf->Cell(0,4,strtoupper('Client List for Postpartum Care'),0,0,'C');
        $this->fpdf->SetFont('Helvetica','',8);
        
        //Table Headers
        $this->header_page1();
        //$this->tcl_header();
        
        $i = 1;
        $x = 1;
        foreach ($clients as $client)
        {                             
            
                $age = floor((time() - $client->dob)/31556926); 
                if(!$age)
                    {$age = '-';} 
                    
                $brgy = $this->clients_barangay_m->get_name($client->barangay_id);   
                if(!$client->address)
                        {$address = $brgy;} 
                elseif(!$brgy)
                        {$address = $client->address;} 
                else 
                        {$address = $brgy.', '.$client->address;}
                
                $date_delivery = $client->delivery ? date('Y-m-d', $client->delivery): '';
                $time_delivery = $client->delivery ? date('g:i a', $client->delivery): '';
                $date_visits_day = $client->visits_day ? date('Y-m-d', $client->visits_day): '';
                $date_visits_week = $client->visits_week ? date('Y-m-d', $client->visits_week): '';
                $date_breastfeeding = $client->breastfeeding ? date('Y-m-d', $client->breastfeeding): '';
                	 
                $iron1_date = $client->iron1_date ? date('m-d-y', $client->iron1_date): '';
                $iron2_date = $client->iron2_date ? date('m-d-y', $client->iron2_date): '';
                $iron3_date = $client->iron2_date ? date('m-d-y', $client->iron3_date): '';
                
                $iron1_tabs = $client->iron1_tabs ? $client->iron1_tabs: '';
                $iron2_tabs = $client->iron2_tabs ? $client->iron2_tabs: ''; 
                $iron3_tabs = $client->iron3_tabs ? $client->iron3_tabs: '';
                
                $date_vitamin_a = $client->vitamin_a ? date('Y-m-d', $client->vitamin_a): '';       
                            
                $this->fpdf->SetFillColor(230,230,230);             
                $modx = $x%2;
                if($modx==0) 
                {$fill=false;}
                else
                {$fill=true;}
                $this->fpdf->SetFont('Helvetica','B',7);
                $this->fpdf->Cell(12,8,$x.'.',1,0,'L',$fill);
                $this->fpdf->SetFont('Helvetica','',7);
                $this->fpdf->Cell(15,4,$date_delivery,'LTRb',0,'C',$fill); 
                $posx = $this->fpdf->GetX();
                $posy = $this->fpdf->GetY();
                $posx = number_format($posx)-15;
                $posy = $posy+4;
                $posx2 = number_format($posx)+15;
                $posy2 = $posy-4;
                $this->fpdf->SetY($posy);
                $this->fpdf->SetX($posx);
                $this->fpdf->Cell(15,4,strtoupper($time_delivery),1,1,'C',$fill); 
                $this->fpdf->SetY($posy2);
                $this->fpdf->SetX($posx2);  
                $this->fpdf->SetFont('Helvetica','',7); 
                $this->fpdf->Cell(25,8,$client->serial_number,1,0,'L',$fill);
                $this->fpdf->Cell(55,8,trim($client->last_name).', '.trim($client->first_name).' '.trim($client->middle_name),1,0,'L',$fill);
                $this->fpdf->Cell(50,8,$address,1,0,'L',$fill);
                $this->fpdf->Cell(20,8,$date_visits_day,1,0,'C',$fill);
                $this->fpdf->Cell(20,8,$date_visits_week,1,0,'C',$fill);
                $this->fpdf->Cell(20,8,$date_breastfeeding,1,0,'C',$fill);
                $this->fpdf->Cell(15,4,$iron1_date,1,0,'C',$fill);
                $this->fpdf->Cell(15,4,$iron2_date,1,0,'C',$fill);
                $this->fpdf->Cell(15,4,$iron3_date,1,0,'C',$fill);
                $posx3 = $this->fpdf->GetX();
                $posy3 = $this->fpdf->GetY();
                $posx3 = number_format($posx3)-45;
                $posy3 = $posy3+4;
                $posx4 = number_format($posx3)+45;
                $posy4 = $posy3-4;
                $this->fpdf->SetY($posy3);
                $this->fpdf->SetX($posx3);
                $this->fpdf->Cell(15,4,$iron1_tabs,1,0,'C',$fill);
                $this->fpdf->Cell(15,4,$iron2_tabs,1,0,'C',$fill);
                $this->fpdf->Cell(15,4,$iron3_tabs,1,0,'C',$fill);
                $this->fpdf->SetY($posy4);
                $this->fpdf->SetX($posx4);
                $this->fpdf->Cell(15,8,$date_vitamin_a,1,1,'C',$fill);
                
                if($i==15)
                {
                    if($x<$totalitems)
                    {
                        $this->tcl_header();
                        $this->footer();
                        
                        $i = 1;
                        $this->fpdf->SetTextColor(0,0,0);
                        
                        $this->fpdf->AliasNbPages();
                        $this->fpdf->AddPage('L','A4');
                        $this->fpdf->SetAutoPageBreak(true, 10);
                                            
                        $this->fpdf->Image(base_url().'system/cms/themes/pyrocms/img/logo_small.png',10,5,40);
                        $this->fpdf->SetY(10);
                        $this->fpdf->SetFont('Helvetica','B',16);
                        $this->fpdf->SetTextColor(0,0,0);
                        $this->fpdf->Cell(0,4,strtoupper('Postpartum Care Client\'s List'),0,0,'C');
                        $this->fpdf->SetFont('Helvetica','',8);
                        
                        $this->header_page1();
                        //$this->tcl_header();
        
                    }
                }
                else
                {
                    $i=$i+1;
                }
                $x=$x+1;              
        }
        
        $this->tcl_header();
        $this->footer();
        
        $this->fpdf->Output('TCL_Postpartum.pdf','I');        
	} 
    
    function footer()
    {
        $year = date('Y');
        //Draw a line
        $this->fpdf->SetY(-15);
        
        //Page footer
        $this->fpdf->SetY(-15);
        $this->fpdf->SetTextColor(128);
        $this->fpdf->SetFont('Helvetica','I',6);
        $this->fpdf->Cell(10,4,'Page '.$this->fpdf->PageNo().'/{nb}',0,0,'C');
        $this->fpdf->SetFont('Helvetica','',6);
        $this->fpdf->Cell(0,4,'Copyright © '.$year.' IMPROVHEALTH | Provided by: Touch Foundation Inc.',0,0,'C');
    } 
    
    function tcl_header()
    {
        //Draw a line
        $this->fpdf->SetY(5);
        $this->fpdf->SetX(255);
        
        $this->fpdf->SetTextColor(0,0,0);
        $this->fpdf->SetFont('Helvetica','B',20);
        $this->fpdf->Cell(30,10,'TCL - PP',0,1,'C');
    } 
    
    function header_page1()
    {
        $this->fpdf->SetY(15);
        $this->fpdf->SetFillColor(208,208,255);
        $fill = true;
        $this->fpdf->SetFont('Helvetica','',8);
        $this->fpdf->Cell(12,4,'','LTRb',0,'C',$fill);
        $this->fpdf->Cell(15,4,'','LTRb',0,'C',$fill);  
        $this->fpdf->Cell(25,4,'','LTRb',0,'C',$fill); 
        $this->fpdf->Cell(55,4,'','LTRb',0,'C',$fill);
        $this->fpdf->Cell(50,4,'','LTRb',0,'C',$fill);
        $this->fpdf->Cell(40,4,'DATE POST-PARTUM VISITS','LTRb',0,'C',$fill);
        $this->fpdf->Cell(20,4,'DATE & TIME','LTRb',0,'C',$fill);
        $this->fpdf->Cell(60,4,'','LTRb',1,'C',$fill);  
        
        $this->fpdf->SetFont('Helvetica','',8);
        $this->fpdf->Cell(12,4,'','LtRb',0,'C',$fill);
        $this->fpdf->Cell(15,4,'DATE &','LtRb',0,'C',$fill); 
        $this->fpdf->Cell(25,4,'FAMILY','LtRb',0,'C',$fill);  
        $this->fpdf->Cell(55,4,'NAME','LtRb',0,'C',$fill);
        $this->fpdf->Cell(50,4,'ADDRESS','LtRb',0,'C',$fill);
        $this->fpdf->SetFont('Helvetica','',7);
        $this->fpdf->Cell(40,4,'(5)','LtRB',0,'C',$fill);
        $this->fpdf->SetFont('Helvetica','',8);
        $this->fpdf->Cell(20,4,'INITIATED','LtRb',0,'C',$fill);
        $this->fpdf->Cell(60,4,'MICRONUTRIENT SUPPLEMENTATION','LtRb',1,'C',$fill); 
        
        $this->fpdf->SetFont('Helvetica','B',8);
        $this->fpdf->Cell(12,4,'NO.','LtRb',0,'C',$fill);
        $this->fpdf->SetFont('Helvetica','',8);
        $this->fpdf->Cell(15,4,'TIME OF','LtRb',0,'C',$fill);  
        $this->fpdf->Cell(25,4,'SERIAL','LtRb',0,'C',$fill);  
        $this->fpdf->Cell(55,4,'','LtRb',0,'C',$fill);
        $this->fpdf->Cell(50,4,'','LtRb',0,'C',$fill);
        $this->fpdf->SetFont('Helvetica','',7);
        $this->fpdf->Cell(20,4,'W/IN 24 HOURS','LTRb',0,'C',$fill);
        $this->fpdf->Cell(20,4,'WITHIN ONE','LTRb',0,'C',$fill);
        $this->fpdf->SetFont('Helvetica','',8);
        $this->fpdf->Cell(20,4,'BREAST-','LtRb',0,'C',$fill);
        $this->fpdf->SetFont('Helvetica','',7);
        $this->fpdf->Cell(60,4,'(7)','LtRb',1,'C',$fill);
        $this->fpdf->SetFont('Helvetica','',8);
        
        $this->fpdf->SetFont('Helvetica','',8);
        $this->fpdf->Cell(12,4,'','LtRb',0,'C',$fill);
        $this->fpdf->Cell(15,4,'DELIVERY','LtRb',0,'C',$fill);  
        $this->fpdf->Cell(25,4,'NUMBER','LtRb',0,'C',$fill);  
        $this->fpdf->Cell(55,4,'','LtRb',0,'C',$fill);
        $this->fpdf->Cell(50,4,'','LtRb',0,'C',$fill);
        $this->fpdf->SetFont('Helvetica','',7);
        $this->fpdf->Cell(20,4,'AFTER','LtRb',0,'C',$fill);
        $this->fpdf->Cell(20,4,'WEEK AFTER','LtRb',0,'C',$fill);
        $this->fpdf->SetFont('Helvetica','',8);
        $this->fpdf->Cell(20,4,'FEEDING','LtRb',0,'C',$fill);
        $this->fpdf->SetFont('Helvetica','',7);
        $this->fpdf->Cell(45,4,'IRON',1,0,'C',$fill);
        $this->fpdf->Cell(15,4,'VIT. A',1,1,'C',$fill);  
        $this->fpdf->SetFont('Helvetica','',8);
        
        $this->fpdf->SetFont('Helvetica','',7);
        $this->fpdf->Cell(12,4,'','LtRB',0,'C',$fill); 
        $this->fpdf->Cell(15,4,'(1)','LtRB',0,'C',$fill);  
        $this->fpdf->Cell(25,4,'(2)','LtRB',0,'C',$fill);  
        $this->fpdf->Cell(55,4,'(3)','LtRB',0,'C',$fill);
        $this->fpdf->Cell(50,4,'(4)','LtRB',0,'C',$fill);
        $this->fpdf->SetFont('Helvetica','',8);
        $this->fpdf->Cell(20,4,'DELIVERY','LtRb',0,'C',$fill);
        $this->fpdf->Cell(20,4,'DELIVERY','LtRb',0,'C',$fill);
        $this->fpdf->SetFont('Helvetica','',7);
        $this->fpdf->Cell(20,4,'(6)','LtRB',0,'C',$fill);
        $this->fpdf->Cell(45,4,'DATE/ No. Tablets',1,0,'C',$fill);  
        $this->fpdf->Cell(15,4,'DATE',1,1,'C',$fill); 
        $this->fpdf->SetFont('Helvetica','',8);
        
    }         
	
}
