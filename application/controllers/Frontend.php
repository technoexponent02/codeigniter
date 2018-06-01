<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Frontend extends CI_Controller {

	public $data=array();
	public $loggedout_method_arr = array();

	function __construct()
	{
		parent::__construct();

		$this->data=$this->defaultdata->getFrontendDefaultData();
		
		$this->load->model('userdata');
		if($this->defaultdata->is_session_active() == 1)
		{
			$user_cond = array();
			$user_cond['id'] = $this->session->userdata('usrid'); 
			$this->data['user_details'] = $this->userdata->grabUserData($user_cond);
		}
	}

	public function index()
	{
		$user_id = $this->session->userdata('usrid');
		/*if($user_id)
		{
			redirect(base_url('dashboard'));
		}
		else
		{
			redirect(base_url('login'));
		}*/
		//$this->load->view('user/login',$this->data);
        $this->load->view('home',$this->data);
	}



}

/* End of file frontend.php */
/* Location: ./application/controllers/frontend.php */