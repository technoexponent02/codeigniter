<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {

	public $data=array();
	
	public $loggedin_method_arr = array('my-account', 'update-profile');

	public $controller_arr = array('user','frontend','fbcontroller','gpluscontroller','routemanager','ajax');

	function __construct()
	{
		parent::__construct();
		$this->load->model('userdata');
        $this->data=$this->defaultdata->getFrontendDefaultData();
        //print_r($this->data['tot_segments']);
        //die('err');

		 if(array_search($this->data['tot_segments'][1],$this->loggedin_method_arr) !== false || array_search($this->data['tot_segments'][2],$this->loggedin_method_arr) !== false)
		{
			if($this->defaultdata->is_session_active() == 0)
			{
				redirect(base_url('sign-in'));
			}else{
                //die('2');
                if($this->session->userdata('usrtype') =='A' ){
                    redirect(base_url('/dashboard'));
                }
            }
		}else{

            //die($this->session->userdata('usrtype'));
        }

        //echo ($this->defaultdata->is_session_active());exit;
		if($this->defaultdata->is_session_active() == 1)
		{

            if($this->session->userdata('usrtype')=='A')
            {
                redirect(base_url('dashboard'));
            }
			$user_cond = array();
			$user_cond['id'] = $this->session->userdata('usrid'); 
			//$this->data['user_details'] = $this->userdata->grabUserData($user_cond);

			//print_r($this->data['user_details']);exit;
            //die($this->session->userdata('usrtype'));

            //if($this->session->userdata('usrtype') != "A")    $this->data = $this->defaultdata->getFrontendDefaultData();
            //else    $this->data=$this->defaultdata->getAdminDefaultData();

            $this->data['user_details'] = $this->userdata->grabUserData($user_cond);

            //print_r($this->data);exit;
		}else{
		    /*echo ($this->defaultdata->is_session_active());
            die('err');*/
        }

	}

    public function signIn()
    {
        if($this->session->userdata('usrid') != '')
        {
            if($this->session->userdata('usrtype')!='A')
            {
                redirect(base_url('my-account'));
            }
        }
        else
        {
            $this->load->view('sign-in',$this->data);
        }
    }

	public function loginProcess()
	{
		$login_data=array();
		$input_data = $this->input->post();
		//print_r($input_data);die;
		$this->load->library('form_validation');
		$this->form_validation->set_rules('userName', 'Username', 'trim|required');
		$this->form_validation->set_rules('userPassword', 'Password', 'trim|required');
		
		if($this->form_validation->run() == FALSE)
		{
			$this->session->set_flashdata('login_error',validation_errors());
			$this->session->set_userdata($input_data);
			redirect(base_url('login'));
		}
		else
		{
			$where_str = "(userName='".$input_data['userName']."' OR emailAddress='".$input_data['userName']."') AND userPassword='".md5($input_data['userPassword'])."'";
			/*$where_str = "emailAddress='".$input_data['userName']."' AND userPassword='".md5($input_data['userPassword'])."'";*/
			$user_data = $this->userdata->grabLoginUserData($where_str);
			if(count($user_data) > 0)
			{
				if($user_data->status == 'Y')
				{
					
					if($user_data->userType=='A')
					{
						$this->userdata->saveLoginLog($user_data->id);
						$this->defaultdata->setLoginSession($user_data);
						redirect(base_url('dashboard'));
					}
					else if($user_data->userType=='C')
					{
					    //die('112');
						$this->userdata->saveLoginLog($user_data->id);
						$this->defaultdata->setLoginSession($user_data);
                        //echo ($this->defaultdata->is_session_active());exit;
						redirect(base_url('my-account'));
					}
					else
					{
						$this->session->set_flashdata('login_error','<p>Your account is not activated or blocked by admin.</p>');
						$this->session->set_userdata($input_data);
						//redirect(base_url('sign-in'));

                            $refer =  $this->agent->referrer();
                            redirect($refer);

					}
									
				}
				else
				{
					$this->session->set_flashdata('login_error','<p>Your account is not activated or blocked by admin.</p>');
					$this->session->set_userdata($input_data);
					//redirect(base_url('sign-in'));

                        $refer =  $this->agent->referrer();
                        redirect($refer);

				}
			}
			else
			{
				$this->session->set_flashdata('login_error','<p>Wrong username or password.</p>');
				$this->session->set_userdata($input_data);
				//redirect(base_url('sign-in'));

                    $refer =  $this->agent->referrer();
                    redirect($refer);

			}
		}
	}


	public function userList()
	{
		$this->data['user_list'] = $this->userdata->listOfUsers();

		$this->load->view('admin/list-user', $this->data);
	}

	public function passportVarificationStatus()
	{
		$input_data = $this->input->post();
		$u_id = $input_data['user_id'];
		$update_data['passport_varification_status'] = $input_data['status'];
		$user_cond = array('id' => $u_id);
		$this->userdata->update($update_data,$user_cond);
	}

	public function removeUser()
	{
		$input_data = $this->input->post();
		$cond = array('id' => $input_data['user_id']);
		$this->userdata->deleteFunction(TABLE_USER, $cond);
	}

	public function changeUserStatus()
	{
		$input_data = $this->input->post();
		$cond = array('id' => $input_data['user_id']);
		unset($input_data['user_id']);
		$this->userdata->updateFunction(TABLE_USER, $input_data, $cond);
	}


    public function myAccount()
    {
       // print_r($this->data['user_details']);exit;
        $this->load->view('user/my-account',$this->data);
    }





















	// public function registerBussiness()
	// {
	// 	if($this->session->userdata('usrid') != '')
	// 	{
	// 		redirect(base_url('user/my-account'));
	// 	}
	// 	else
	// 	{
	// 		$this->load->view('user/register_bussiness',$this->data);
	// 	}
	// }
	// public function businessRegisterProcess()
	// {
	// 	$input_data = $this->input->post();
	// 	$this->load->library('form_validation');
	// 	$this->form_validation->set_rules('name', 'Name', 'trim|required');
	// 	$this->form_validation->set_rules('emailAddress', 'Email', 'trim|required|valid_email|is_unique['.TABLE_USER.'.emailAddress]');
	// 	$this->form_validation->set_message('valid_email', 'Please enter valid email.');
	// 	$this->form_validation->set_message('is_unique', 'This email is already registered.');

	// 	if (empty($_FILES['profile_picture_image']['name']))
	// 	{
	// 		$this->form_validation->set_rules('profile_picture', 'Profile Image', 'required');
	// 	}

	// 	$this->form_validation->set_rules('userPassword', 'Password', 'trim|required');
	// 	$this->form_validation->set_rules('re_password', 'Repeat Password', 'trim|required|matches[userPassword]');
	// 	$this->form_validation->set_rules('business_name', 'Business name', 'trim|required');
	// 	$this->form_validation->set_rules('business_website', 'Business website', 'trim|required');
	// 	$this->form_validation->set_rules('business_ph', 'Business phone', 'trim|required');
		
		
	// 	$this->session->unset_userdata($input_data);
		
	// 	if($this->form_validation->run() == FALSE)
	// 	{
	// 		$this->session->set_userdata('business_register_error',validation_errors());
	// 		$this->session->set_userdata($input_data);
	// 	}
	// 	else
	// 	{
	// 		$config['upload_path'] = UPLOAD_PATH_URL.'profile_images/';
	// 		$config['allowed_types'] = 'gif|jpg|png|bmp';
	// 		$config['file_name'] = time().strtolower(str_replace(' ','-',$_FILES['profile_picture_image']['name']));
			
	// 		$this->load->library('upload');
	// 		$this->upload->initialize($config);
	// 		if (!$this->upload->do_upload('profile_picture_image'))
	// 		{
	// 			$this->session->set_userdata('business_register_error',$this->upload->display_errors());
	// 		}
	// 		else
	// 		{
	// 			unset($input_data['re_password']);
	// 			$input_data = $this->defaultdata->secureInput($input_data);

	// 			$profile_image_arr = $this->upload->data();
	// 			$input_data['profile_picture'] = $profile_image_arr['file_name'];

	// 			$input_data['userPassword'] = md5($input_data['userPassword']);
	// 			$input_data['userType'] = 1;
	// 			$input_data['status'] = 'E';
	// 			$input_data['postedtime'] = time();
	// 			$input_data['ipaddress'] = $_SERVER["REMOTE_ADDR"];
				
	// 			$user_id = $this->userdata->insert($input_data);

	// 			$this->session->set_userdata('business_register_success','Registration successfully complete, Please check your email to activate.');
				
	// 			$encydata=encrypt($user_id,'sdasdasd');
				
	// 			$mail_data = $this->userdata->getActivationEmailTemplate();//print_r($mail_data);exit;
	// 			$activation_link=base_url()."user/activation/b674b2f8e615753f1fd54406349d37".$encydata;

	// 			$mailcontent=htmlspecialchars_decode($mail_data->description);
	// 			$mailcontent=str_replace('{USER_NAME}',$input_data['name'],$mailcontent);
	// 			$mailcontent=str_replace('{SITE_URL}',base_url(),$mailcontent);
	// 			$mailcontent=str_replace('{REG_LINK}',$activation_link,$mailcontent);
	// 			$mailcontent=str_replace('{SITE_TITLE}',$this->data['general_settings']->SiteTitle,$mailcontent);
				
	// 			$to=$input_data['emailAddress'];
	// 			$headers ="From: ".$this->data['general_settings']->contactEmailName."<".$this->data['general_settings']->Contact_Email."> \r\n"; 
	// 			$headers .= "MIME-Version: 1.0\n"; 
	// 			$headers .= "Content-type: text/html; charset=UTF-8\n"; 
	// 			$subject = $mail_data->emailTitle;
	// 			$message ="<html><head></head><body>"."<style type=\"text/css\">
	// 			<!--
	// 			.style4 {font-size: x-small}
	// 			-->
	// 			</style>
	// 			".$mailcontent."
	// 			</body><html>"; 
	// 			@mail($to,$subject, $message,$headers);
	// 		}
			
	// 	}
	// 	redirect(base_url('register-bussiness'));
	// }
	// public function registerReviewer()
	// {
	// 	if($this->session->userdata('usrid') != '')
	// 	{
	// 		redirect(base_url('user/my-account'));
	// 	}
	// 	else
	// 	{
	// 		$this->data['all_countries'] = $this->defaultdata->getAllCountry();
	// 		$this->load->view('user/register_reviewer',$this->data);
	// 	}
	// }
	// public function reviewerRegisterProcess()
	// {
	// 	$input_data = $this->input->post();
	// 	$this->load->library('form_validation');
	// 	$this->form_validation->set_rules('name', 'Name', 'trim|required');
	// 	$this->form_validation->set_rules('emailAddress', 'Email', 'trim|required|valid_email|is_unique['.TABLE_USER.'.emailAddress]');
	// 	$this->form_validation->set_message('valid_email', 'Please enter valid email.');
	// 	$this->form_validation->set_message('is_unique', 'This email is already registered.');
	// 	$this->form_validation->set_rules('phone', 'Phone number', 'trim|required');

	// 	if (empty($_FILES['profile_picture_image']['name']))
	// 	{
	// 		$this->form_validation->set_rules('profile_picture', 'Profile Image', 'required');
	// 	}

	// 	$this->form_validation->set_rules('userPassword', 'Password', 'trim|required');
	// 	$this->form_validation->set_rules('re_password', 'Repeat Password', 'trim|required|matches[userPassword]');
	// 	$this->form_validation->set_rules('reviewer_bio', 'Reviewer Bio', 'trim|required');
	// 	$this->form_validation->set_rules('city', 'City', 'trim|required');
	// 	$this->form_validation->set_rules('state', 'State', 'trim|required');
	// 	$this->form_validation->set_rules('country', 'Country', 'trim|required');
	// 	$this->form_validation->set_rules('zipcode', 'Zip code', 'trim|required');
		
		
	// 	$this->session->unset_userdata($input_data);
		
	// 	if($this->form_validation->run() == FALSE)
	// 	{
	// 		$this->session->set_userdata('reviewer_register_error',validation_errors());
	// 		$this->session->set_userdata($input_data);
	// 	}
	// 	else
	// 	{
	// 		$config['upload_path'] = UPLOAD_PATH_URL.'profile_images/';
	// 		$config['allowed_types'] = 'gif|jpg|png|bmp';
	// 		$config['file_name'] = time().strtolower(str_replace(' ','-',$_FILES['profile_picture_image']['name']));
			
	// 		$this->load->library('upload');
	// 		$this->upload->initialize($config);
	// 		if (!$this->upload->do_upload('profile_picture_image'))
	// 		{
	// 			$this->session->set_userdata('reviewer_register_error',$this->upload->display_errors());
	// 		}
	// 		else
	// 		{
	// 			unset($input_data['re_password']);
	// 			$input_data = $this->defaultdata->secureInput($input_data);

	// 			$profile_image_arr = $this->upload->data();
	// 			$input_data['profile_picture'] = $profile_image_arr['file_name'];

	// 			$input_data['userPassword'] = md5($input_data['userPassword']);
	// 			$input_data['userType'] = 2;
	// 			$input_data['status'] = 'E';
	// 			$input_data['postedtime'] = time();
	// 			$input_data['ipaddress'] = $_SERVER["REMOTE_ADDR"];
				
	// 			$user_id = $this->userdata->insert($input_data);

	// 			$this->session->set_userdata('reviewer_register_success','Registration successfully complete, Please check your email to activate.');
				
	// 			$encydata=encrypt($user_id,'sdasdasd');
				
	// 			$mail_data = $this->userdata->getActivationEmailTemplate();//print_r($mail_data);exit;
	// 			$activation_link=base_url()."user/activation/b674b2f8e615753f1fd54406349d37".$encydata;

	// 			$mailcontent=htmlspecialchars_decode($mail_data->description);
	// 			$mailcontent=str_replace('{USER_NAME}',$input_data['name'],$mailcontent);
	// 			$mailcontent=str_replace('{SITE_URL}',base_url(),$mailcontent);
	// 			$mailcontent=str_replace('{REG_LINK}',$activation_link,$mailcontent);
	// 			$mailcontent=str_replace('{SITE_TITLE}',$this->data['general_settings']->SiteTitle,$mailcontent);
				
	// 			$to=$input_data['emailAddress'];
	// 			$headers ="From: ".$this->data['general_settings']->contactEmailName."<".$this->data['general_settings']->Contact_Email."> \r\n"; 
	// 			$headers .= "MIME-Version: 1.0\n"; 
	// 			$headers .= "Content-type: text/html; charset=UTF-8\n"; 
	// 			$subject = $mail_data->emailTitle;
	// 			$message ="<html><head></head><body>"."<style type=\"text/css\">
	// 			<!--
	// 			.style4 {font-size: x-small}
	// 			-->
	// 			</style>
	// 			".$mailcontent."
	// 			</body><html>"; 
	// 			@mail($to,$subject, $message,$headers);
	// 		}
			
	// 	}
	// 	redirect(base_url('register-reviewer'));
	// }
	// public function activation($str)
	// {
	// 	$data=$str;
	// 	$encydata=substr($data,30);
	// 	$uid=decrypt($encydata,'sdasdasd');
		
	// 	$cond = array('id' => $uid);
	// 	$user_data = $this->userdata->grabUserData($cond);

	// 	if($user_data->status=='E')
	// 	{
	// 		$this->userdata->saveLoginLog($user_data->id);
	// 		$this->defaultdata->setLoginSession($user_data);
			
	// 		$all_business_user = $this->userdata->getAllUsers(array('userType' => 2, 'status' => 'Y'));
	// 		$time = time();
	// 		if($user_data->userType == 1 && count($all_business_user) <= 50)
	// 		{
	// 			$membership_type = 'B';
	// 			$membership_starttime = $time;
	// 			$membership_endtime = $time + (365*24*3600);
	// 			$update_data = array('status' => 'Y', 'membership_type' => $membership_type, 'membership_starttime' => $membership_starttime, 'membership_endtime' => $membership_endtime);
	// 		}
	// 		else
	// 		{
	// 			$update_data = array('status' => 'Y');
	// 		}
			
	// 		$condition = array('id' => $uid);
	// 		$this->userdata->update($update_data,$condition);
	// 		redirect(base_url('user/my-account'));
	// 	}
	// 	else
	// 	{
	// 		redirect(base_url('login'));
	// 	}
	// }
	
	// public function myAccount()
	// {
	// 	$this->data['acc_leftpanel'] = $this->load->view('account_subviews/account_leftpanel',$this->data,true);
	// 	$this->load->view('user/my_account',$this->data);
	// }
	// public function updateProfile()
	// {
	// 	if($this->session->userdata('profile_update_error') == '')
	// 	{//print_r($this->data['user_details']);
	// 		$this->session->set_userdata((array) $this->data['user_details']);
	// 	}
	// 	$this->data['acc_leftpanel'] = $this->load->view('account_subviews/account_leftpanel',$this->data,true);
	// 	$this->load->view('user/update_profile',$this->data);
	// }
	// public function updateProfileProcess()
	// {
	// 	$input_data = $this->input->post();
	// 	$this->load->library('form_validation');
	// 	$this->form_validation->set_rules('name', 'Full Name', 'trim|required');
	// 	$this->form_validation->set_rules('emailAddress', 'Email', 'trim|required|valid_email|callback_checkEmail');
	// 	$this->form_validation->set_message('valid_email', 'Please enter valid Email address.');
	// 	//$this->form_validation->set_rules('phone', 'Phone number', 'trim|required');

				
	// 	$this->session->unset_userdata($input_data);
	// 	if($this->form_validation->run() == FALSE)
	// 	{
	// 		$this->session->set_userdata('profile_update_error',validation_errors());
	// 		$this->session->set_userdata($input_data);
	// 	}
	// 	else
	// 	{
	// 		$user_cond = array();
	// 		$user_cond['id'] = $this->session->userdata('usrid');
	// 		$user_det = $this->userdata->fetchOne($user_cond);
	// 		if (!empty($_FILES['profile_picture_image']['name']))
	// 		{
	// 			$config['upload_path'] = UPLOAD_PATH_URL.'profile_images/';
	// 			$config['allowed_types'] = 'gif|jpg|png|bmp';
	// 			$config['file_name'] = time().strtolower(str_replace(' ','-',$_FILES['profile_picture_image']['name']));
				
	// 			$this->load->library('upload');
	// 			$this->upload->initialize($config);
				
	// 			if ($this->upload->do_upload('profile_picture_image'))
	// 			{
	// 				if($user_det->profile_picture != "" && file_exists(UPLOAD_PATH_URL.'profile_images/'.$user_det->profile_picture))
	// 				{
	// 					unlink(UPLOAD_PATH_URL.'profile_images/'.$user_det->profile_picture);
	// 				}
	// 				$profile_image_arr = $this->upload->data();
	// 				$input_data['profile_picture'] = $profile_image_arr['file_name'];
	// 			}
	// 		}
	// 		$input_data = $this->defaultdata->secureInput($input_data);
			
	// 		$this->userdata->update($input_data,$user_cond);
	// 		$this->session->set_userdata('profile_update_success','Profile updated successfully.');
	// 	}
	// 	redirect(base_url('user/update-profile'));
	// }
	// /*public function changePassword()
	// {
	// 	$this->data['acc_leftpanel'] = $this->load->view('account_subviews/account_leftpanel',$this->data,true);
	// 	$this->load->view('user/change_password',$this->data);
	// }*/
	// public function updatePasswordProcess()
	// {
	// 	$input_data = $this->input->post();
	// 	$uid = $this->session->userdata('usrid');
	// 	$user_cond = array('id' => $uid);
	// 	$user_det = $this->userdata->grabUserData($user_cond);
	// 	$this->session->unset_userdata($input_data);
	// 	$this->load->library('form_validation');
	// 	$this->form_validation->set_rules('newpassword', 'New password', 'trim|required|matches[renewpassword]');
	// 	$this->form_validation->set_rules('renewpassword', 'Re-type new password', 'trim|required');
		
	// 	if($this->form_validation->run() == FALSE)
	// 	{
	// 		$this->session->set_userdata('password_update_error',validation_errors());
	// 		$this->session->set_userdata($input_data);
	// 	}
	// 	else
	// 	{
	// 		$input_data = $this->defaultdata->secureInput($input_data);
	// 		$update_data = array();
	// 		$update_data['userPassword'] = md5($input_data['newpassword']);
	// 		$this->userdata->update($update_data,$user_cond);
	// 		$this->session->set_userdata('password_update_success','Your password updated successfully.');
	// 	}
	// 	redirect(base_url('user/update-profile'));
	// }
	// public function updateShippingAddress()
	// {
	// 	if($this->session->userdata('usrtype') == 2)
	// 	{
	// 		if($this->session->userdata('shipping_address_update_error') == '')
	// 		{//print_r($this->data['user_details']);
	// 			$this->session->set_userdata((array) $this->data['user_details']);
	// 		}
	// 		$this->data['all_countries'] = $this->defaultdata->getAllCountry();
	// 		$this->data['acc_leftpanel'] = $this->load->view('account_subviews/account_leftpanel',$this->data,true);
	// 		$this->load->view('user/update_shipping_address',$this->data);
	// 	}
	// 	else
	// 	{
	// 		redirect(base_url('user/my-account'));
	// 	}
	// }
	// public function updateShippingAddressProcess()
	// {
	// 	$input_data = $this->input->post();
	// 	$this->load->library('form_validation');
	// 	$this->form_validation->set_rules('city', 'City', 'trim|required');
	// 	$this->form_validation->set_rules('state', 'State', 'trim|required');
	// 	$this->form_validation->set_rules('country', 'Country', 'trim|required');
	// 	$this->form_validation->set_rules('zipcode', 'Zip code', 'trim|required');

	// 	$this->session->unset_userdata($input_data);
	// 	if($this->form_validation->run() == FALSE)
	// 	{
	// 		$this->session->set_userdata('shipping_address_update_error',validation_errors());
	// 		$this->session->set_userdata($input_data);
	// 	}
	// 	else
	// 	{
	// 		$user_cond = array();
	// 		$user_cond['id'] = $this->session->userdata('usrid');

	// 		$input_data = $this->defaultdata->secureInput($input_data);
			
	// 		$this->userdata->update($input_data,$user_cond);
	// 		$this->session->set_userdata('shipping_address_update_success','Shipping address updated successfully.');
	// 	}
	// 	redirect(base_url('user/update-shipping-address'));
	// }
	// public function updateSocialChannel()
	// {
	// 	if($this->session->userdata('usrtype') == 2)
	// 	{
	// 		if($this->session->userdata('social_channel_update_error') == '')
	// 		{//print_r($this->data['user_details']);
	// 			$this->session->set_userdata((array) $this->data['user_details']);
	// 		}
	// 		$this->data['acc_leftpanel'] = $this->load->view('account_subviews/account_leftpanel',$this->data,true);
	// 		$this->load->view('user/update_social_channel',$this->data);
	// 	}
	// 	else
	// 	{
	// 		redirect(base_url('user/my-account'));
	// 	}
	// }
	// public function updateSocialChannelProcess()
	// {
	// 	$input_data = $this->input->post();
	// 	$this->load->library('form_validation');
	// 	//$this->form_validation->set_rules('facebook_profile_link', 'Facebook profile link', 'trim|required');

	// 	$this->session->unset_userdata($input_data);
	// 	if($this->form_validation->run() == FALSE)
	// 	{
	// 		$this->session->set_userdata('social_channel_update_error',validation_errors());
	// 		$this->session->set_userdata($input_data);
	// 	}
	// 	else
	// 	{
	// 		$user_cond = array();
	// 		$user_cond['id'] = $this->session->userdata('usrid');

	// 		$input_data = $this->defaultdata->secureInput($input_data);
			
	// 		$this->userdata->update($input_data,$user_cond);
	// 		$this->session->set_userdata('social_channel_update_success','Social media link updated successfully.');
	// 	}
	// 	redirect(base_url('user/update-social-channel'));
	// }
	// public function updateReviewerBio()
	// {
	// 	if($this->session->userdata('usrtype') == 2)
	// 	{
	// 		if($this->session->userdata('reviewer_bio_update_error') == '')
	// 		{//print_r($this->data['user_details']);
	// 			$this->session->set_userdata((array) $this->data['user_details']);
	// 		}
	// 		$this->data['acc_leftpanel'] = $this->load->view('account_subviews/account_leftpanel',$this->data,true);
	// 		$this->load->view('user/update_reviewer_bio',$this->data);
	// 	}
	// 	else
	// 	{
	// 		redirect(base_url('user/my-account'));
	// 	}
	// }
	// public function updateReviewerBioProcess()
	// {
	// 	$input_data = $this->input->post();
	// 	$this->load->library('form_validation');
	// 	$this->form_validation->set_rules('reviewer_bio', 'Reviewer Bio', 'trim|required');

	// 	$this->session->unset_userdata($input_data);
	// 	if($this->form_validation->run() == FALSE)
	// 	{
	// 		$this->session->set_userdata('reviewer_bio_update_error',validation_errors());
	// 		$this->session->set_userdata($input_data);
	// 	}
	// 	else
	// 	{
	// 		$user_cond = array();
	// 		$user_cond['id'] = $this->session->userdata('usrid');

	// 		$input_data = $this->defaultdata->secureInput($input_data);
			
	// 		$this->userdata->update($input_data,$user_cond);
	// 		$this->session->set_userdata('reviewer_bio_update_success','Reviewer bio updated successfully.');
	// 	}
	// 	redirect(base_url('user/update-reviewer-bio'));
	// }

	// public function forgotPassword()
	// {
	// 	if($this->session->userdata('usrid') != '')
	// 	{
	// 		redirect(base_url('user/my-account'));
	// 	}
	// 	else
	// 	{
	// 		$this->load->view('user/forgot_password',$this->data);
	// 	}
	// }
	
	// public function forgotPasswordProcess()
	// {    
	// 	$input_data = $this->input->post();
 //        $this->session->unset_userdata($input_data);        
 //        $this->load->library('form_validation');
 //        $this->form_validation->set_rules('emailAddress', 'Email', 'trim|required|valid_email');
 //        $this->form_validation->set_message('valid_email', 'Please enter valid Email address.');
			
 //        if($this->form_validation->run() == FALSE)
 //        {
	// 		$this->session->set_userdata('forgotpassword_error',validation_errors());
	// 		$this->session->set_userdata($input_data);
 //        }
	// 	else
	// 	{
 //            $input_data = $this->defaultdata->secureInput($input_data);
 //            $user_cond = array();
 //            $user_cond['emailAddress'] = $input_data['emailAddress'];
 //            $user_details = $this->userdata->grabUserData($user_cond);
 //            if(!empty($user_details))
	// 		{   //print_r($user_details); exit;      
 //                // send mail to user
 //                $query = $this->db->get(TABLE_EMAIL_FORGET_PASSWORD);
 //                $result = $query->row();
 //                $admin_settings = $this->defaultdata->grabSettingData();
	// 			$enc_user = base64_encode($user_details->id.'####'.$user_details->emailAddress); 
	// 			$reset_pass_link = base_url('reset-password/'.$enc_user);
 //                $mailcontent = htmlspecialchars_decode($result->description);
 //                $mailcontent = str_replace('{USER_NAME}',$user_details->name,$mailcontent);
	// 			$mailcontent = str_replace('{RESET_PASS_LINK}',$reset_pass_link,$mailcontent);
 //                $mailcontent = str_replace('{SITE_TITLE}',$admin_settings->SiteTitle,$mailcontent);
 //                $mailcontent = str_replace('{SITE_URL}',base_url(),$mailcontent);				
	// 			$to=$input_data['emailAddress'];
				
	// 			$headers ="From: ".$this->data['general_settings']->contactEmailName."<".$this->data['general_settings']->Contact_Email.">\n"; 
	// 			$headers .= "MIME-Version: 1.0\n"; 
	// 			$headers .= "Content-type: text/html; charset=UTF-8\n"; 
	// 			$subject = $result->emailTitle;
	// 			$message ="<html><head></head><body>"."<style type=\"text/css\">
	// 			<!--
	// 			.style4 {font-size: x-small}
	// 			-->
	// 			</style>
	// 			".$mailcontent."
	// 			</body></html>";
	// 			@mail($to,$subject, $message,$headers);
				
	// 			$this->session->set_userdata('fogetpass_success','Email has been sent to '.$user_details->emailAddress.' with a reset password link.');
				
 //            }
	// 		else
	// 		{
	// 			$this->session->set_userdata('forgotpassword_error','<p>Email Address does not exist.</p>');
	// 			$this->session->set_userdata($input_data);
				
 //            }
 //        }
	// 	redirect(base_url('forgot-password'));
	// }
	// public function resetPassword($user_info = '')
	// {
	// 	if($this->session->userdata('usrid') == '')
	// 	{
	// 		if($user_info == '')
	// 		{
	// 			redirect(base_url('login'));
	// 		}
	// 		else
	// 		{
	// 			$this->data['user_info'] = $user_info;
	// 			$this->load->view('user/reset_password',$this->data);
	// 		}
	// 	}
	// 	else
	// 	{
	// 		redirect(base_url('user/my-account'));
	// 	}
	// }
	// public function resetPassProcess()
	// {
	// 	$input_data = $this->input->post();
	// 	$user_info = $input_data['user_info'];
	// 	unset($input_data['user_info']);
	// 	$this->load->library('form_validation');
	// 	$this->form_validation->set_rules('userPassword', 'Password', 'trim|required');
	// 	$this->form_validation->set_rules('re_password', 'Repeat Password', 'trim|required|matches[userPassword]');
	// 	$this->form_validation->set_message('matches', 'Passwords do not match.');
	// 	if($this->form_validation->run() == FALSE)
	// 	{
	// 		$this->session->set_userdata('reset_pass_error',validation_errors());
	// 		$this->session->set_userdata($input_data);
	// 		redirect(base_url('reset-password/'.$user_info));
	// 	}
	// 	else
	// 	{
	// 		$info_user = base64_decode($user_info);
	// 		$info_user_arr = explode('####',$info_user);
	// 		$user_id = $info_user_arr[0];
	// 		unset($input_data['re_password']);
	// 		$input_data = $this->defaultdata->secureInput($input_data);
	// 		$input_data['userPassword'] = md5($input_data['userPassword']);
	// 		$user_cond = array('id' => $user_id);
	// 		$this->userdata->update($input_data,$user_cond);
	// 		$user_det = $this->userdata->grabUserData($user_cond);
			
	// 		$this->session->set_userdata('reset_pass_success','Your password has been reset.');
	// 		redirect(base_url('login'));
	// 	}
	// }
	// public function profile($user_id = 0)
	// {
	// 	if($user_id == 0)
	// 	{
	// 		redirect(base_url());
	// 	}
	// 	else
	// 	{
	// 		$user_det = $this->userdata->grabUserData(array('id' => $user_id));
	// 		if($user_det->userType == 1)
	// 		{
	// 			redirect(base_url());
	// 		}
	// 		else
	// 		{
	// 			$this->data['user_det'] = $user_det;
	// 			$this->load->view('user/user_profile',$this->data);
	// 		}
	// 	}
	// }

	// public function messages($uid = 0)
	// {
	// 	$key = $this->input->get('key');
	// 	$this->load->model('chatdata');
	// 	$this->data['show_chat_list']=$this->chatdata->showChatList($key);
	// 	$this->data['uid'] = $uid;
	// 	$this->load->view('chat/messages',$this->data);
	// }
	// public function pricing($msg = '')
	// {
	// 	$this->load->model('cmsdata');
	// 	$this->data['msg'] = $msg;
	// 	$this->data['pricing_content'] = $this->cmsdata->fetchOne(array('id' => 6));
	// 	$this->load->view('user/pricing',$this->data);
	// }
	// public function upgradeMembership()
	// {
	// 	$input_data = $this->input->post();
	// 	//print_r($input_data);
	// 	$membershipType = "";
	// 	if($input_data['membership_type'] == 'B')
	// 	{
	// 		$membershipType = '(Basic)';
	// 	}
	// 	elseif($input_data['membership_type'] == 'S')
	// 	{
	// 		$membershipType = '(Standard)';
	// 	}
	// 	elseif($input_data['membership_type'] == 'U')
	// 	{
	// 		$membershipType = '(Ultimate)';
	// 	}

	// 	$uid = $this->session->userdata('usrid');
	// 	$paypal_confiq = array();
	// 	$paypal_confiq['form_name'] = 'ireviewbrand_membership';
	// 	$paypal_confiq['business_email'] = $this->data['general_settings']->paypalemailaddress;
	// 	$paypal_confiq['return_url'] = base_url('user/pricing/success');
	// 	$paypal_confiq['notify_url'] = base_url('user/paypalMembershipNotify/'.$input_data['membership_type'].'/'.$uid);
	// 	$paypal_confiq['currency_code'] = 'USD';
	// 	$paypal_confiq['amount'] = $input_data['membership_price'];
	// 	$paypal_confiq['item_name'] = 'Ireviewbrands Membership'.$membershipType;
	// 	$paypal_confiq['cancel_url'] = base_url('user/pricing');
	// 	$paypal_data = array();
	// 	$paypal_data['paypal_confiq'] = $paypal_confiq;
	// 	$this->load->view('includes/paypal_form',$paypal_data);
	// }
	// public function paypalMembershipNotify($membership_type = "", $uid = 0)
	// {
	// 	if($membership_type != "" && $uid != 0)
	// 	{
	// 		$time = time();
	// 		$update_usr_data = array();
	// 		$update_usr_data['membership_type'] = $membership_type;
	// 		$update_usr_data['membership_starttime'] = $time;
	// 		$update_usr_data['membership_endtime'] = $time + (30*24*3600);
	// 		$this->userdata->update($update_usr_data, array('id' => $uid));
	// 	}
	// }
	// public function resources()
	// {
	// 	if($this->session->userdata('usrtype') == 1)
	// 	{
	// 		$this->load->model('resourcedata');
	// 		$all_resources = $this->resourcedata->fetchAll(array('resource_status' => 'Y'), array('resource_postedtime' => 'DESC'));
	// 		$this->data['all_resources'] = $all_resources;
	// 		$this->load->view('user/resource',$this->data);
	// 	}
	// 	else
	// 	{
	// 		redirect(base_url('user/my-account'));
	// 	}
	// }
	// public function consultingPrice()
	// {
	// 	$this->load->view('user/consultation_price',$this->data);
	// }
	// public function consultingPriceProcess()
	// {
	// 	$this->load->model('contactdata');
	// 	$input_data = $this->input->post();
	// 	$this->load->library('form_validation');
	// 	$this->form_validation->set_rules('contactName', 'Name', 'trim|required');
	// 	$this->form_validation->set_rules('contactEmail', 'Email', 'trim|required|valid_email');
	// 	$this->form_validation->set_message('valid_email', 'Please enter valid email.');
	// 	$this->form_validation->set_rules('subject', 'Subject', 'trim|required');
	// 	$this->form_validation->set_rules('message', 'Message', 'trim|required');
		
	// 	if($this->form_validation->run() == FALSE)
	// 	{
	// 		$this->session->set_userdata('consulting_error',validation_errors());
	// 		$this->session->set_userdata($input_data);
	// 	}
	// 	else
	// 	{
	// 		$send_copy = 0;
	// 		if(isset($input_data['send_copy']) && $input_data['send_copy'] == 1)
	// 		{
	// 			$send_copy = 1;
	// 			unset($input_data['send_copy']);
	// 		}
	// 		$input_data['contact_type'] = 2;
	// 		$input_data['postedtime'] = time();
	// 		$input_data = $this->defaultdata->secureInput($input_data);

	// 		$this->contactdata->insert($input_data);
			
	// 		$mailcontent = '<p>Name : '.$input_data['contactName'].'</p>';
	// 		$mailcontent .= '<p>Email : '.$input_data['contactEmail'].'</p>';
	// 		$mailcontent .= '<p>Subject : '.$input_data['subject'].'</p>';
	// 		$mailcontent .= '<p>Message : '.$input_data['message'].'</p>';
			
	// 		$to=$this->data['general_settings']->Contact_Email;
	// 		$headers ="From: ".$input_data['contactName']."<".$input_data['contactEmail'].">\n"; 
	// 		$headers .= "MIME-Version: 1.0\n"; 
	// 		$headers .= "Content-type: text/html; charset=UTF-8\n"; 
	// 		$subject = 'Business Consultation';
	// 		$message ="<html><head></head><body>"."<style type=\"text/css\">
	// 		<!--
	// 		.style4 {font-size: x-small}
	// 		-->
	// 		</style>
	// 		".$mailcontent."
	// 		</body><html>"; 
			
	// 		@mail($to,$subject, $message,$headers);

	// 		if($send_copy == 1)
	// 		{
	// 			$to1=$input_data['contactEmail'];
	// 			$headers1 ="From: ".$input_data['contactName']."<".$input_data['contactEmail'].">\n"; 
	// 			$headers1 .= "MIME-Version: 1.0\n"; 
	// 			$headers1 .= "Content-type: text/html; charset=UTF-8\n"; 
	// 			$subject1 = 'Business Consultation';
	// 			$message1 ="<html><head></head><body>"."<style type=\"text/css\">
	// 			<!--
	// 			.style4 {font-size: x-small}
	// 			-->
	// 			</style>
	// 			".$mailcontent."
	// 			</body><html>";
	// 			@mail($to1,$subject1, $message1,$headers1);
	// 		}
	// 		$this->session->set_userdata('consulting_success','Successfully submitted. Thank you!!');
			
	// 	}
	// 	redirect(base_url('user/consulting-price'));
	// }
	public function reports()
	{
		$this->load->model('campaingmodel');
		$camp_reports = $this->campaingmodel->getAllCampignReport();
		//print_r($camp_reports);
		$this->data['camp_reports'] = $camp_reports;
		$this->load->view('user/campaign_reports',$this->data);
	}

	
	/************Validation callback functions**************/
	public function checkEmail($email)
	{
		$user_cond = array();
		$user_cond['id !='] = $this->session->userdata('usrid');
		$user_cond['emailAddress'] = $email;
		$exit_user = $this->userdata->grabUserData($user_cond);
		if(count($exit_user) > 0)
		{
			$this->form_validation->set_message('checkEmail', 'This Email is already exist.');
			return false;
		}
		else
		{
			return true;
		}
	}
	public function currPassCheck($pass)
	{
		$cond = array();
		$this->load->model('userdata');
		$user_id = $this->session->userdata['usrid'];
		$cond['userPassword'] = md5($pass);
		$cond['id'] = $user_id;
		$user_det=$this->userdata->grabUserData($cond);
		if (count($user_det) > 0)
		{
			return true;
		}
		else
		{
			$this->form_validation->set_message('currPassCheck', 'Please enter correct password.');
			return false;
		}
	}
	public function checkUserName($str)
	{
		$usr_name = url_title($str,'-',true);
		if(in_array($usr_name, $this->controller_arr))
		{
			$this->form_validation->set_message('checkUserName', 'Please choose different username.');
			return false;
		}
		else
		{
			$post_cond = array('URL_SEOTOOL' => $usr_name);
			$post_data = $this->defaultdata->grabStaticPost($post_cond);
			$page_data = $this->defaultdata->grabStaticPage($post_cond);
			if(count($post_data) > 0 || count($page_data))
			{
				$this->form_validation->set_message('checkUserName', 'Please choose different username.');
				return false;
			}
			else
			{
				$user_cond = array();
				if($this->session->userdata('usrid'))
				{
					$user_cond['id !='] = $this->session->userdata('usrid');
				}
				$user_cond['userName'] = $usr_name;
				$exit_user = $this->userdata->grabUserData($user_cond);
				if(count($exit_user) > 0)
				{
					$this->form_validation->set_message('checkUserName', 'Please choose different username.');
					return false;
				}
				else
				{
					return true;
				}
			}
		}
	}

	
	public function accept_terms() 
	{
		if (isset($_POST['terms']))
		{
			return true;
		}
		else
		{
			 $this->form_validation->set_message('accept_terms', 'Please read and accept our terms of use.');
			return false;
		}
	}
	/************Validation callback functions end**************/



	public function logout()
	{
		$condarr['login_status']=0;
		$this->userdata->updateLoginUser($condarr);
		$this->defaultdata->unsetLoginSession();
		redirect(base_url('/'));
	}
}

/* End of file user.php */
/* Location: ./application/controllers/user.php */