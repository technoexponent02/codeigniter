<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ajax extends CI_Controller {
	public $data=array();
	public $controller_arr = array('user','frontend','fbcontroller','gpluscontroller','routemanager','ajax','admin');
	function __construct()
	{
		parent::__construct();
		$this->load->model('userdata');
	}
	

	// public function validateBusinessRegister()
	// {
	// 	$input_data = $this->input->post();
	// 	$this->load->library('form_validation');
	// 	$this->form_validation->set_rules('name', 'Name', 'trim|required');
	// 	$this->form_validation->set_rules('emailAddress', 'Email', 'trim|required|valid_email|is_unique['.TABLE_USER.'.emailAddress]');
	// 	$this->form_validation->set_message('valid_email', 'Please enter valid email.');
	// 	$this->form_validation->set_message('is_unique', 'This email is already registered.');

			
	// 	$this->form_validation->set_rules('userPassword', 'Password', 'trim|required');
	// 	$this->form_validation->set_rules('re_password', 'Repeat Password', 'trim|required|matches[userPassword]');
	// 	$this->form_validation->set_rules('business_name', 'Business name', 'trim|required');
	// 	$this->form_validation->set_rules('business_website', 'Business website', 'trim|required');
	// 	$this->form_validation->set_rules('business_ph', 'Business phone', 'trim|required');
		
		
		
	// 	$return_data = array();
	// 	$return_data['has_error'] = 0;
	// 	if($this->form_validation->run() == FALSE)
	// 	{
	// 		$return_data['has_error'] = 1;
	// 		$return_data['business_register_error'] = validation_errors();
	// 	}
	// 	echo json_encode($return_data);
	// }
	// public function validateReviewerRegister()
	// {
	// 	$input_data = $this->input->post();
	// 	$this->load->library('form_validation');
	// 	$this->form_validation->set_rules('name', 'Name', 'trim|required');
	// 	$this->form_validation->set_rules('emailAddress', 'Email', 'trim|required|valid_email|is_unique['.TABLE_USER.'.emailAddress]');
	// 	$this->form_validation->set_message('valid_email', 'Please enter valid email.');
	// 	$this->form_validation->set_message('is_unique', 'This email is already registered.');
	// 	$this->form_validation->set_rules('phone', 'Phone number', 'trim|required');

			
	// 	$this->form_validation->set_rules('userPassword', 'Password', 'trim|required');
	// 	$this->form_validation->set_rules('re_password', 'Repeat Password', 'trim|required|matches[userPassword]');
	// 	$this->form_validation->set_rules('reviewer_bio', 'Reviewer Bio', 'trim|required');
	// 	$this->form_validation->set_rules('city', 'City', 'trim|required');
	// 	$this->form_validation->set_rules('state', 'State', 'trim|required');
	// 	$this->form_validation->set_rules('country', 'Country', 'trim|required');
	// 	$this->form_validation->set_rules('zipcode', 'Zip code', 'trim|required');
		
	// 	$return_data = array();
	// 	$return_data['has_error'] = 0;
	// 	if($this->form_validation->run() == FALSE)
	// 	{
	// 		$return_data['has_error'] = 1;
	// 		$return_data['reviewer_register_error'] = validation_errors();
	// 	}
	// 	echo json_encode($return_data);
	// }
	// public function applyCampaignProcess()
	// {
	// 	$this->load->model('campaingmodel');
	// 	$input_data = $this->input->post();
	// 	$this->load->library('form_validation');
	// 	$this->form_validation->set_rules('application_messages', 'Message', 'trim|required');
		
	// 	$return_data = array();
	// 	$return_data['has_error'] = 0;
	// 	if($this->form_validation->run() == FALSE)
	// 	{
	// 		$return_data['has_error'] = 1;
	// 		$return_data['apply_campaign_error'] = validation_errors();
	// 	}
	// 	else
	// 	{
	// 		$input_data['reviewer_id'] = $this->session->userdata('usrid');
	// 		$input_data['aplication_postedtime'] = time();
	// 		$input_data = $this->defaultdata->secureInput($input_data);
	// 		$application_id = $this->campaingmodel->setTable(TABLE_CAMPAIGN_APPLICATION)->insert($input_data);
	// 		if($application_id > 0)
	// 		{
	// 			$return_data['has_error'] = 0;
	// 		}
	// 		else
	// 		{
	// 			$return_data['has_error'] = 1;
	// 			$return_data['apply_campaign_error'] = '<p>Internal server error.</p>';
	// 		}
	// 	}
	// 	echo json_encode($return_data);
	// }
	// public function awardCampaign()
	// {
	// 	$appl_id = $this->input->post('appl_id');
	// 	if($appl_id != 0 || $appl_id != '')
	// 	{
	// 		$this->load->model('campaingmodel');
	// 		$appl_data = array('aplication_status' => 'A');
	// 		$this->campaingmodel->setTable(TABLE_CAMPAIGN_APPLICATION)->update($appl_data, array('application_id' => $appl_id));
	// 		echo 1;
	// 	}
	// 	else
	// 	{
	// 		echo 0;
	// 	}
	// }
	// public function rejectCampaign()
	// {
	// 	$appl_id = $this->input->post('appl_id');
	// 	if($appl_id != 0 || $appl_id != '')
	// 	{
	// 		$this->load->model('campaingmodel');
	// 		$appl_data = array('aplication_status' => 'N');
	// 		$this->campaingmodel->setTable(TABLE_CAMPAIGN_APPLICATION)->update($appl_data, array('application_id' => $appl_id));
	// 		echo 1;
	// 	}
	// 	else
	// 	{
	// 		echo 0;
	// 	}
	// }
	// public function getCampRoprt()
	// {
	// 	$appl_id = $this->input->post('appl_id');
	// 	$return_data = array();
	// 	$return_data['has_error'] = 0;
	// 	if($appl_id != 0 || $appl_id != '')
	// 	{
	// 		$this->load->model('campaingmodel');
	// 		$report_arr = $this->campaingmodel->setTable(TABLE_CAMPAIGN_REPORT)->fetchAll(array('application_id' => $appl_id), array('report_postedtime' => 'DESC'));
	// 		$return_data['previous_report'] = "";
	// 		if(count($report_arr) > 0)
	// 		{
	// 			foreach($report_arr as $rp)
	// 			{
	// 				$return_data['previous_report'] .= '<p>Date : '.date('d-m-Y',$rp->report_postedtime).'<br/>Message : '.$rp->report_message;
	// 				if($rp->report_image != '')
	// 				{
	// 					$return_data['previous_report'] .= '<br/>Report picture : <img src="'.DEFAULT_ASSETS_URL.'/upload/report_images/'.$rp->report_image.'" style="width:100px;height:60px;">';
	// 				}
	// 				$return_data['previous_report'] .= '</p>';
	// 			} 
	// 		}
			
	// 	}
	// 	else
	// 	{
	// 		$return_data['has_error'] = 1;
	// 	}
	// 	echo json_encode($return_data);
	// }
	// public function reportCampaignProcess()
	// {
	// 	$this->load->model('campaingmodel');
	// 	$input_data = $this->input->post();
	// 	$this->load->library('form_validation');
	// 	$this->form_validation->set_rules('report_message', 'Report', 'trim|required');
		
	// 	$return_data = array();
	// 	$return_data['has_error'] = 0;
	// 	if($this->form_validation->run() == FALSE)
	// 	{
	// 		$return_data['has_error'] = 1;
	// 		$return_data['report_campaign_error'] = validation_errors();
	// 	}
	// 	else
	// 	{
	// 		$input_data['report_image'] = "";
	// 		if (!empty($_FILES['report_image_file']['name']))
	// 		{
	// 			$config['upload_path'] = UPLOAD_PATH_URL.'report_images/';
	// 			$config['allowed_types'] = 'gif|jpg|png|bmp';
	// 			$config['file_name'] = time().strtolower(str_replace(' ','-',$_FILES['report_image_file']['name']));
				
	// 			$this->load->library('upload');
	// 			$this->upload->initialize($config);
				
	// 			if ($this->upload->do_upload('report_image_file'))
	// 			{
	// 				$report_image_arr = $this->upload->data();
	// 				$input_data['report_image'] = $report_image_arr['file_name'];
	// 			}
	// 		}
	// 		if(isset($input_data['report_image_file']))
	// 		{
	// 			unset($input_data['report_image_file']);
	// 		}
	// 		$application_data = $this->campaingmodel->setTable(TABLE_CAMPAIGN_APPLICATION)->fetchOne(array('application_id' => $input_data['application_id']));
	// 		$input_data['campaign_id'] = $application_data->campaign_id;
	// 		$input_data['reviewer_id'] = $application_data->reviewer_id;
	// 		$input_data['report_postedtime'] = time();
	// 		$input_data = $this->defaultdata->secureInput($input_data);
	// 		$report_id = $this->campaingmodel->setTable(TABLE_CAMPAIGN_REPORT)->insert($input_data);
	// 		if($report_id > 0)
	// 		{
	// 			$return_data['has_error'] = 0;
	// 			$appl_data = array('aplication_status' => 'R');
	// 			$this->campaingmodel->setTable(TABLE_CAMPAIGN_APPLICATION)->update($appl_data, array('application_id' => $input_data['application_id']));
	// 		}
	// 		else
	// 		{
	// 			$return_data['has_error'] = 1;
	// 			$return_data['report_campaign_error'] = '<p>Internal server error.</p>';
	// 		}
			
	// 	}
	// 	echo json_encode($return_data);
	// }
	// public function completeCampaingProcess()
	// {
	// 	$campaing_id = $this->input->post('camp_id');
	// 	$this->load->model('campaingmodel');
	// 	$camp_data = array('cam_status' => 'C');
	// 	$this->campaingmodel->update($camp_data, array('campaing_id' => $campaing_id));
	// 	$this->campaingmodel->setTable(TABLE_CAMPAIGN_APPLICATION)->update(array('aplication_status' =>'C'), array('campaign_id' => $campaing_id, 'aplication_status' => 'R'));
	// 	echo 1;
	// }
	// public function getAplication()
	// {
	// 	$appl_id = $this->input->post('appl_id');
	// 	$appl_msg = '';
	// 	if($appl_id != 0 || $appl_id != '')
	// 	{
	// 		$this->load->model('campaingmodel');
	// 		$appl_det = $this->campaingmodel->setTable(TABLE_CAMPAIGN_APPLICATION)->fetchOne(array('application_id' => $appl_id));
	// 		$appl_msg = $appl_det->application_messages;
	// 	}
	// 	echo $appl_msg;
	// }
}
/* End of file ajax.php */
/* Location: ./application/controllers/ajax.php */