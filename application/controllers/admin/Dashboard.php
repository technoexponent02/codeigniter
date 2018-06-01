<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	public $data=array();

    public $loggedin_method_arr = array('dashboard', 'user-list', 'hourly-management', 'hourly-management/create', 'hourly-management/delete', 'addEmail','listEmail','listWithdraw');

	public $controller_arr = array('user','frontend','fbcontroller','gpluscontroller','routemanager','ajax');
	
	function __construct()
	{
		parent::__construct();

		$this->load->model('userdata');
		$this->data=$this->defaultdata->getAdminDefaultData();
		if(array_search($this->data['tot_segments'][2],$this->loggedin_method_arr) !== false)
		{
			if($this->defaultdata->is_session_active() == 0)
			{
				redirect(base_url('admin/login'));
			}
		}
		if($this->defaultdata->is_session_active() == 1)
		{
            if($this->session->userdata('usrtype')!='A')
            {
                redirect(base_url('my-account'));
            }
			$user_cond = array();
			$user_cond['id'] = $this->session->userdata('usrid'); 
			$this->data['user_details'] = $this->userdata->grabUserData($user_cond);
		}
	}

	public function index()
	{
		$this->load->view('admin/dashboard',$this->data);
	}

    public function login()
    {
        if($this->session->userdata('usrid') != '')
        {
            if($this->session->userdata('usrtype')=='A')
            {
                redirect(base_url('dashboard'));
            }
        }
        else
        {
            $this->load->view('admin/login',$this->data);
        }
    }

	public function createHourlyManagement()
	{
		if ($this->input->post()) {
			$input_data = $this->input->post();

			$this->load->library('form_validation');
			$this->form_validation->set_rules('no_of_hr', 'Number of Hours', 'trim|required');
			$this->form_validation->set_rules('rate_per_hour', 'Rate per Hour', 'trim|required');
			
			if($this->form_validation->run() == FALSE) {
				$this->session->set_flashdata('validation_error', validation_errors());
				$this->session->set_userdata($input_data);
				return redirect(base_url('hourly-management/create'));
			}
			
			$hourly_rate = $this->db->where([
				'no_of_hr' => $input_data['no_of_hr']
			])->get(TABLE_HOURLY_RATE)->row();

			if(!empty($hourly_rate)) {
				$this->session->set_flashdata('already_exist_error', 'Sorry! This data is already inserted into database.');
				$this->session->set_userdata($input_data);
				return redirect(base_url('hourly-management/create'));
			}

			// Save to database.
			$this->db->insert(TABLE_HOURLY_RATE, $input_data);
			return redirect(base_url('hourly-management'));
		}
		
		$this->load->view('admin/hourly_rate/create', $this->data);
	}

	public function listHourlyManagement()
	{
		$this->data['hourly_rates'] = $this->db->order_by('no_of_hr', 'asc')->get(TABLE_HOURLY_RATE)->result();
		$this->load->view('admin/hourly_rate/list', $this->data);
	}

	public function deleteHourlyManagement()
	{
		$return_data = [
			'status' => 'failed'
		];

		$input_data = $this->input->post();
		$cond = ['id' => $input_data['id']];
		$this->db->delete(TABLE_HOURLY_RATE, $cond);
		
		if ($this->db->affected_rows() > 0) {
			$return_data = [
				'status' => 'success'
			];
		}
		return jsonResponse($return_data);
	}
	 public function email(){
        $this->page_title = 'Add Email Template';
        $this->data['admin_user'] = $this->userdata->fetchOne(array('id' => $this->session->userdata('admin_usrid')));
		$this->load->view('admin/user/email/add', $this->data);
        //$this->admin_page = 'admin/user/email/add';
        //$this->layoutAdmin();
    }

    public function addEmail(){
        $input_data = $this->input->post();
		$this->load->library('form_validation');
        $this->form_validation->set_rules('emailTitle', 'Email Title', 'trim|required');
        $this->form_validation->set_rules('description', 'Description', 'trim|required');
        $this->form_validation->set_rules('type', 'Email Type', 'trim|required');
        
        if($this->form_validation->run() == FALSE) {
            $this->page_title = 'Add Email';
            //$this->admin_page = 'admin/user/email/add';
            //$this->layoutAdmin();
			$this->load->view('admin/user/email/add', $this->data);
        }
        else
        {
            $input_data['time'] = time();
            $this->userdata->insertFunction(TABLE_EMAIL_TEMPLATE,$input_data);
            $this->session->set_flashdata('cat_msg', 'Email added Successfully.');
            //redirect(base_url('admin/user/proficiency/add'));
            redirect(base_url('admin/dashboard/listEmail'));
        }  
    }

    public function listEmail() {
        $this->page_title = 'Email Management';
        $this->data['admin_user'] = $this->userdata->fetchOne(array('id' => $this->session->userdata('admin_usrid')));
        $this->data['categories'] = $this->userdata->listFunction(TABLE_EMAIL_TEMPLATE);
        //$this->admin_page = 'admin/user/email/list';
        //$this->layoutAdmin();
		$this->load->view('admin/user/email/list', $this->data);
    }
	
	 public function deleteEmail($cat_id) {
        $this->userdata->deleteFunction(TABLE_EMAIL_TEMPLATE, array('id' => $cat_id));
        $this->session->set_flashdata('cat_msg', 'Email deleted Successfully.');
        redirect(base_url('admin/dashboard/listEmail'));
    }

    public function editEmail($cat_id) {
        $this->page_title = 'Edit Email';
        $this->data['admin_user'] = $this->userdata->fetchOne(array('id' => $this->session->userdata('admin_usrid')));
        $this->data['categories'] = $this->userdata->grabDetails(TABLE_EMAIL_TEMPLATE, array('id' => $cat_id));
        //$this->admin_page = 'admin/user/email/edit';
        //$this->layoutAdmin();
		$this->load->view('admin/user/email/edit', $this->data);
    }


    public function updateEmail(){
        $input_data = $this->input->post();
        $id = $input_data['id'];
		$this->load->library('form_validation');
        $this->form_validation->set_rules('emailTitle', 'Email Title', 'trim|required');
        $this->form_validation->set_rules('description', 'Description', 'trim|required');
        $this->form_validation->set_rules('type', 'Email Type', 'trim|required');
        if($this->form_validation->run() == FALSE) {
            $this->session->set_userdata('err_msg' , validation_errors());
            redirect(base_url('admin/dashboard/editEmail/'.$id));
            $this->layoutAdmin();
        }
        else
        {
            unset($input_data['id']);
            $input_data['time'] = time();
            $this->userdata->updateFunction(TABLE_EMAIL_TEMPLATE,$input_data, array('id' =>$id));
            $this->session->set_flashdata('cat_msg', 'Email Successfully Updated.');
            redirect(base_url('admin/dashboard/listEmail'));
        }  
    }
	
	public function listWithdraw()
	{
		//echo"ok";
		$this->page_title = 'Withdraw Management';
        $this->data['admin_user'] = $this->userdata->fetchOne(array('id' => $this->session->userdata('admin_usrid')));
        $this->data['categories'] = $this->userdata->getDetailsWithdraw(array('transaction_type' => 2,'type' => 'D'));
		//dd($this->data['categories']);die();
		$this->load->view('admin/user/withdraw/list', $this->data);
	}
	public function editWithdraw($cat_id) {
		//echo $cat_id;
        $this->page_title = 'Edit Email';
        $this->data['admin_user'] = $this->userdata->fetchOne(array('id' => $this->session->userdata('admin_usrid')));
        $this->data['categories'] = $this->userdata->grabDetails(TABLE_TRANSACTION, array('id' => $cat_id));
		//dd($this->data['categories']);die();
        //$this->admin_page = 'admin/user/email/edit';
        //$this->layoutAdmin();
		$this->load->view('admin/user/withdraw/edit', $this->data);
    }
	public function updateWithdraw(){
        $input_data = $this->input->post();
		//dd($input_data);die();
        $id = $input_data['id'];
		$this->load->library('form_validation');
        $this->form_validation->set_rules('payment_status', 'payment_status', 'trim|required');
        
        if($this->form_validation->run() == FALSE) {
            $this->session->set_userdata('err_msg' , validation_errors());
            redirect(base_url('admin/dashboard/editWithdraw/'.$id));
            $this->layoutAdmin();
        }
        else
        {
            unset($input_data['id']);
         
            $this->userdata->updateFunction(TABLE_TRANSACTION,$input_data, array('id' =>$id));
            $this->session->set_flashdata('cat_msg', 'Payment Status Successfully Updated.');
            redirect(base_url('admin/dashboard/listWithdraw'));
        }  
    }
	
}

