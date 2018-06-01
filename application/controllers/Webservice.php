<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Webservice extends CI_Controller {

	public $data=array();
	public $loggedout_method_arr = array('index');

	private $page;
    private $per_page;
    private $offset;

	function __construct()
	{
		parent::__construct();
		$this->load->model('webservicedata');
		$this->load->model('userdata');
		$this->data=$this->defaultdata->getFrontendDefaultData();

		// Under maintenance.
		/*http_response_code(503);
		echo 'Be right back! we are upgrading our system.';
		die(1);*/

		// Calculation for pagination.
		$this->per_page = API_PER_PAGE;
        $page = $this->input->post('page') ? $this->input->post('page') : 1;

        $this->page = $page;
        $this->offset = ($page - 1) * $this->per_page;
	}

	public function registerProcess()
	{
		/*$msg = "Email test!";
        mail("debdyuti@technoexponent.com", "Test subject", $msg);
        echo "check"; exit;*/
		$input_data = $this->input->post();

		$this->load->library('form_validation');

		$this->form_validation->set_rules('firstName', 'First Name', 'trim|required');
		$this->form_validation->set_rules('emailAddress', 'Email', 'trim|required|valid_email');

		/*$this->form_validation->set_rules('userName', 'User Name', 'trim|required|is_unique_username['.TABLE_USER.'.userName]');
		$this->form_validation->set_message('is_unique_username', 'This Username is Already Used.');*/

		$this->form_validation->set_rules('phone', 'Phone Number', 'trim|required');

		$this->form_validation->set_rules('userPassword', 'User Password', 'trim|required');
		$this->form_validation->set_message('valid_email', 'Please Enter Valid Email Address.');
		$this->form_validation->set_rules('termsCondition', '...', 'callback_termsCondition');

		if($this->form_validation->run() == FALSE)
		{
			$data['status_code'] = 1;
			$data['message'] = validation_errors();
		}
		else
		{
			// $where_str = "(userName='".$input_data['emailAddress']."' OR emailAddress='".$input_data['emailAddress']."')";
			$where_str = " emailAddress='".$input_data['emailAddress']."'";
			$user_data = $this->userdata->grabLoginUserData($where_str);

			if(count($user_data) > 0)
			{
				if($user_data->userType == $input_data['userType'])
				{
					$data['status_code'] = 1;
					$data['message'] = "You Have Already Registered. Please Login to Continue";
				}
				else
				{
					if ($user_data->userType == 'C') 
					{
						$data['status_code'] = 1;
						$data['message'] = "You Already Register as Client, So Please use Separate Email to Register as Staff";
					}
					else
					{
						if ($user_data->userType == 'S') 
						{
							$data['status_code'] = 1;
							$data['message'] = "You Already Register as Staff, So Please use Separate Email to Register as Client";
						}
					}
				}

			}
			else
			{
				unset($input_data['rePassword']);
				unset($input_data['termsCondition']);
				$input_data = $this->defaultdata->secureInput($input_data);
				$input_data['userPassword'] = md5($input_data['userPassword']);
				$input_data['status'] = 'E';
				$input_data['posted_time'] = time();
				$input_data['ipaddress'] = $_SERVER["REMOTE_ADDR"];
				
				$user_id = $this->userdata->insert($input_data);

				$encydata=encrypt($user_id,'sdasdasd');
					
				$mail_data = $this->userdata->getActivationEmailTemplate();

				$activation_link=base_url()."webservice/activation/b674b2f8e615753f1fd54406349d37".$encydata;

				$mail_full_name = $input_data['firstName'] . '  ' .  $input_data['lastName'];

				$mailcontent=htmlspecialchars_decode($mail_data->description);
				$mailcontent=str_replace('{USER_NAME}', $mail_full_name, $mailcontent);
				$mailcontent=str_replace('{SITE_URL}',base_url(),$mailcontent);
				$mailcontent=str_replace('{REG_LINK}',$activation_link,$mailcontent);
				$mailcontent=str_replace('{SITE_TITLE}',$this->data['general_settings']->SiteTitle,$mailcontent);
				
				$to=$input_data['emailAddress'];
				$headers ="From: 247 babysits<" . $this->data['general_settings']->Contact_Email . "> \r\n";
				$headers .= "MIME-Version: 1.0\n"; 
				$headers .= "Content-type: text/html; charset=UTF-8\n"; 
				$subject = $mail_data->emailTitle;
				$message ="<html><head></head><body>"."<style type=\"text/css\">
				<!--
				.style4 {font-size: x-small}
				-->
				</style>
				".$mailcontent."
				</body><html>"; 
                 //  $data_mail=@mail($to,$subject,$message,$headers);
				@mail($to,$subject,$message,$headers);
                  //echo $data_mail; die;
				// print_r($to); print_r($subject); print_r($message); print_r($headers); die;


				$data['status_code'] = 0;
				$data['message'] = "You Have Successfully Registered. Please Check Your Email to Activate Your Account.";
			}
		}
		jsonResponse($data);
	}



	public function activation($str)
	{
		$data=$str;
		$encydata=substr($data,30);
		$uid=decrypt($encydata,'sdasdasd');
		
		$cond = array('id' => $uid);
		$user_data = $this->userdata->grabUserData($cond);

		if($user_data->status=='E')
		{
			$this->userdata->saveLoginLog($user_data->id);
			$this->defaultdata->setLoginSession($user_data);
			
			$time = time();
			$update_data = array('status' => 'Y');
			$condition = array('id' => $uid);

			$this->userdata->updateUser($update_data,$condition);

			$this->load->view('success-register', $this->data);
		}
	}


	public function loginProcess()
	{
		$login_data = array();

		$this->load->model('defaultdata');

		$general_settings_hour = $this->data['general_settings']->pricePerHour;

		$input_data = $this->input->post();

		$token_data['token'] = $input_data['token'];
		$token_data['flag'] = $input_data['flag'];

		unset($input_data['token']);
		unset($input_data['flag']);

		$this->load->library('form_validation');
		$this->form_validation->set_rules('emailAddress', 'Email', 'trim|required');
		$this->form_validation->set_rules('userPassword', 'Password', 'trim|required');
		$this->form_validation->set_rules('uuid', 'Device ID', 'required');
		
		if($this->form_validation->run() == FALSE)
		{
			$data['status_code'] = 1;
			$data['message'] = validation_errors();
		}
		else
		{
			/*$where_str = "(userName='".$input_data['emailAddress']."' OR emailAddress='".$input_data['emailAddress']."') AND userPassword='".md5($input_data['userPassword'])."' AND userType='".$input_data['userType']."'";*/
			$where_str = " emailAddress='".$input_data['emailAddress']."' AND userPassword='".md5($input_data['userPassword'])."' AND userType='".$input_data['userType']."'";
			$user_data = $this->userdata->grabLoginUserData($where_str);

			if(!empty($user_data)) {
				if($user_data->status == 'Y') {
					if($user_data->userType=='C' || $user_data->userType=='S') {
						
						$token_data['user_id'] = $user_data->id;
						$token_data['uuid'] = $input_data['uuid'];
						$token_data['notification'] = 'ON';

						$this->webservicedata->saveUserToken($token_data);

						$this->userdata->saveLoginLog($user_data->id);
						$this->defaultdata->setLoginSession($user_data);
						$data['status_code'] = 0;
						$data['general_settings_hour']=$general_settings_hour;
						$data['message'] = "Login Successful";
						unset($user_data->userPassword);
						unset($user_data->profile_picture_org);
						unset($user_data->posted_time);
						unset($user_data->ipaddress);
						unset($user_data->status);
						$data['user_data'] = $user_data;
					//echo $this->db->last_query();
					//die;
					}
					else {
						$data['status_code'] = 1;
						$data['message'] = "Your account is not activated or blocked by admin";
					}									
				}
				else {
					$data['status_code'] = 1;
					$data['message'] = "Your account is not activated or blocked by admin";					
				}
			}
			else {
				$data['status_code'] = 1;
				$data['message'] = "Wrong Email Address OR Password";				
			}
		}
		jsonResponse($data);
	}

	public function appLogout()
	{
		$input_data = $this->input->post();
		
		$u_id = $input_data['u_id'];
		$uuid = $input_data['uuid'];

		$token_data = [
			'user_id' => $u_id,
			'uuid' => $uuid,
			'notification' => 'OFF'
		];

		$affected_rows = $this->webservicedata->updateUserToken($token_data);
		
		$data['statusCode'] = $affected_rows >= 1 ? 0 : 1;
		jsonResponse($data);
	}

	public function uploadProfilePicture()
	{
		$input_data = $this->input->post();

		$u_id = $input_data['u_id'];

		$user_cond = array('id' => $u_id);
		$check_user_data = $this->userdata->grabUserData($user_cond);

		if (count($check_user_data)>0) 
		{
		if($_FILES['profile_picture']['name'])
		{
			$config['upload_path'] = UPLOAD_PATH_URL.'profile_picture/';
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			$config['file_name'] = time().strtolower(str_replace(' ','-',$_FILES['profile_picture']['name']));
			
			$this->load->library('upload');
			$this->upload->initialize($config);
			if (!$this->upload->do_upload('profile_picture'))
			{
				$data['status_code'] = 1;
				$data['message'] = "Make Sure The Image File is Valid";
			}
			else
			{
				$user_details = $this->userdata->grabUserData(array('id' => $u_id));

				if($user_details->profile_picture != '' && file_exists('./assets/upload/profile_picture/'.$user_details->profile_picture))
				{
					@unlink(UPLOAD_PATH_URL.'profile_picture/'.$user_details->profile_picture);
					@unlink(UPLOAD_PATH_URL.'profile_picture/thumb/'.$user_details->profile_picture);  
				}
				
				$image_max_width = 600;
				$source_filename = $config['upload_path'].$config['file_name'];
				list($width, $height) = getimagesize($source_filename);
				$new_width=(int)$image_max_width;
				$new_height=(int)(($height/$width)*$image_max_width);
				
				$this->img_resize_to_fixed_dimension($config['file_name'],$_FILES['profile_picture']['type'],$config['upload_path'], $config['upload_path'].'thumb/', $new_width, $new_height);

				$update_data['profile_picture'] = $config['file_name'];
				$user_data['profile_picture_org'] = $config['file_name'];
				$condition = array('id' => $u_id);
				$this->userdata->updateUser($update_data,$condition);

				$updated_user_data = $this->userdata->grabUserData($condition);
				unset($updated_user_data->userPassword);
				unset($updated_user_data->profile_picture_org);
				unset($updated_user_data->posted_time);
				unset($updated_user_data->ipaddress);
				unset($updated_user_data->status);

				$data['status_code'] = 0;
				$data['message'] = "Profile Picture Uploaded";
				$data['user_data'] = $updated_user_data;
			}
		}
		}
		else
		{
			$data['status_code'] = 1;
			$data['message'] = "Unable To Find The User";
		}
		jsonResponse($data);
	}

	public function img_resize_to_fixed_dimension($file_name,$extn,$source_folder, $dest_folder="", $new_width, $new_height)
	{
		$filename = $source_folder . $file_name;
		if($dest_folder<>"/" && $dest_folder<>"")
		{
			$filename1 = $dest_folder . $file_name;
		}
		else
		{
			$filename1 = $filename;
		}

		list($width, $height) = getimagesize($filename);
		

		$image_p = imagecreatetruecolor($new_width, $new_height);
		if (strtolower($extn)=='image/jpeg' || strtolower($extn)=='image/pjpeg')
		{
			$image = imagecreatefromjpeg($filename);
			imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
			// Output
			imagejpeg($image_p,$filename1, 100);
			imagedestroy($image_p);
			imagedestroy($image);
		}
		else if (strtolower($extn)=='image/gif')
		{
			$image = @imagecreatefromgif($filename);
			imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
			// Output
			imagegif($image_p, $filename1);
			//imagejpeg($image_p,"../images/products/thumb_".$filefname.".jpg", 100);
			imagedestroy($image_p);
			imagedestroy($image);
		}
		else if (strtolower($extn)=='image/png')
		{
			$image = imagecreatefrompng($filename);
			imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
			// Output
			imagepng($image_p,$filename1);
			imagedestroy($image_p);
			imagedestroy($image);
		}
		return $filename1;
	}



	public function accountUpdate()
	{
		$user_data = $staff_detail = [];
		$input_data = $this->input->post();
		$u_id = $input_data['u_id'];
		unset($input_data['u_id']);
		$user_cond = array('id' => $u_id);
		$user_data = $this->userdata->grabUserData($user_cond);
		
		if($user_data !== null) {
			// Extract staff details data.
			if (!empty($input_data['xp_month'])) {
				$staff_detail['xp_month'] = $input_data['xp_month'];
				unset($input_data['xp_month']);
			}
			if (!empty($input_data['xp_year'])) {
				$staff_detail['xp_year'] = $input_data['xp_year'];
				unset($input_data['xp_year']);
			}
			
			// Fetch address from google maps API.
			$address = $input_data['address'];
			$prepAddr = str_replace(' ','+',$address);
			
			$geocode=file_get_contents('https://maps.google.com/maps/api/geocode/json?key=AIzaSyDQA9VSWI1KuE_2D1HfDwHjwxdLr_uBmyY&address='.$prepAddr.'&sensor=false'); 
			//$geocode=file_get_contents('https://maps.google.com/maps/api/geocode/json?key=AIzaSyB_1I4UGZG74rFpKJupehUjTlPLYOie0C4&address='.$prepAddr.'&sensor=false');
			$output= json_decode($geocode);
			$latitude = $output->results[0]->geometry->location->lat;
			$longitude = $output->results[0]->geometry->location->lng;

			$input_data['latitude'] = $latitude;
			$input_data['longitude'] = $longitude;

			$this->userdata->updateUser($input_data, $user_cond);
			// Save staff detail.
			if (!empty($staff_detail)) {
				$condition = ['user_id' => $u_id];
				$this->userdata->saveStaffDetail($staff_detail, $condition);
			}

			$user_data = $this->userdata->grabUserData($user_cond);
			unset($user_data->userPassword);
			unset($user_data->profile_picture_org);
			unset($user_data->posted_time);
			unset($user_data->ipaddress);
			unset($user_data->status);
			unset($user_data->stripe_customer_id);
			$send_data['user_data'] = $user_data;

			$send_data['status_code'] = 0;
			$send_data['message'] = "Your Account is Updated";
		}
		else
		{
			$send_data['status_code'] = 1;
			$send_data['message'] = "Unable To Find The User";
		}
		jsonResponse($send_data);
	}

	public function changePasswordProcess()
	{
		$input_data = $this->input->post();
		$u_id = $input_data['u_id'];
		$user_cond = array('id' => $u_id);
		$user_det = $this->userdata->grabUserData($user_cond);
		if($user_det != NULL) 
		{
			if ($user_det->userPassword != md5($input_data['oldPassword'])) 
			{
				$send_data['status_code'] = 1;
				$send_data['message'] = "Your Old Password is Wrong";
			}
			else
			{
				$this->load->library('form_validation');
				$this->form_validation->set_rules('newPassword', 'New password', 'trim|required|matches[reNewPassword]');
				$this->form_validation->set_rules('reNewPassword', 'Re-type new password', 'trim|required');
				
				if($this->form_validation->run() == FALSE)
				{
					$send_data['status_code'] = 1;
					$send_data['message'] = validation_errors();
				}
				else
				{
					$update_data['userPassword'] = md5($input_data['newPassword']);
					$this->userdata->update($update_data,$user_cond);

					$send_data['status_code'] = 0;
					$send_data['message'] = "Password Successfully Updated";
				}
			}
		}
		else
		{
			$send_data['status_code'] = 1;
			$send_data['message'] = "Unable To Find The User";
		}
		jsonResponse($send_data);
	}

	public function forgotPasswordProcess()
	{    
		$input_data = $this->input->post();
        $this->session->unset_userdata($input_data);

        $this->load->library('form_validation');
        $this->form_validation->set_rules('emailAddress', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_message('valid_email', 'Please enter valid Email address.');
			
        if($this->form_validation->run() == FALSE)
        {
			$send_data['status_code'] = 1;
			$send_data['message'] = validation_errors();
        }
		else
		{
            $input_data = $this->defaultdata->secureInput($input_data);
            $user_cond = array();
            $user_cond['emailAddress'] = $input_data['emailAddress'];
            $user_details = $this->userdata->grabUserData($user_cond);
            if(!empty($user_details))
			{   //print_r($user_details); exit;      
                // send mail to user
                $query = $this->db->get(TABLE_EMAIL_FORGET_PASSWORD);
                $result = $query->row();
                $admin_settings = $this->defaultdata->grabSettingData();

				$enc_user = base64_encode($user_details->id.'####'.$user_details->emailAddress); 

				$reset_pass_link = base_url('reset-password/'.$enc_user);

                $mailcontent = htmlspecialchars_decode($result->description);
                $mailcontent = str_replace('{USER_NAME}',$user_details->firstName.' '.$user_details->lastName,$mailcontent);
				$mailcontent = str_replace('{RESET_PASS_LINK}',$reset_pass_link,$mailcontent);
                $mailcontent = str_replace('{SITE_TITLE}',$admin_settings->SiteTitle,$mailcontent);
                $mailcontent = str_replace('{SITE_URL}',base_url(),$mailcontent);				
				$to=$input_data['emailAddress'];
				
				$headers ="From: ".$this->data['general_settings']->contactEmailName."<".$this->data['general_settings']->Contact_Email."> \r\n"; 
				$headers .= "MIME-Version: 1.0\n"; 
				$headers .= "Content-type: text/html; charset=UTF-8\n"; 
				$subject = $result->emailTitle;
				$message ="<html><head></head><body>"."<style type=\"text/css\">
				<!--
				.style4 {font-size: x-small}
				-->
				</style>
				".$mailcontent."
				</body></html>";

				@mail($to, $subject, $message, $headers);

				// print_r($to); print_r($subject); print_r($message); print_r($headers); die;
				
				$send_data['status_code'] = 0;
				$send_data['message'] = "Reset Password Link Sent to Your Email.";
				
            }
			else
			{
				$send_data['status_code'] = 1;
				$send_data['message'] = "Sorry The Email Address Does Not Exist";
            }
        }
		jsonResponse($send_data);
	}

	public function forgotUsernameProcess()
	{    
		$input_data = $this->input->post();
        $this->session->unset_userdata($input_data);

        $this->load->library('form_validation');
        $this->form_validation->set_rules('emailAddress', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_message('valid_email', 'Please enter valid Email address.');
			
        if($this->form_validation->run() == FALSE)
        {
			$send_data['status_code'] = 1;
			$send_data['message'] = validation_errors();
        }
		else
		{
            $input_data = $this->defaultdata->secureInput($input_data);
            $user_cond = array();
            $user_cond['emailAddress'] = $input_data['emailAddress'];
            $user_details = $this->userdata->grabUserData($user_cond);
            if(!empty($user_details))
			{   //print_r($user_details); exit;      
                // send mail to user
                $query = $this->db->get(TABLE_EMAIL_FORGET_USERNAME);
                $result = $query->row();
                $admin_settings = $this->defaultdata->grabSettingData();

				// $enc_user = base64_encode($user_details->id.'####'.$user_details->emailAddress); 

				// $reset_pass_link = base_url('reset-password/'.$enc_user);

                $mailcontent = htmlspecialchars_decode($result->description);
                $mailcontent = str_replace('{NAME}',$user_details->firstName.' '.$user_details->lastName,$mailcontent);
				$mailcontent = str_replace('{USER_NAME}',$user_details->userName,$mailcontent);
                $mailcontent = str_replace('{SITE_TITLE}',$admin_settings->SiteTitle,$mailcontent);
                $mailcontent = str_replace('{SITE_URL}',base_url(),$mailcontent);				
				$to=$input_data['emailAddress'];
				
				$headers ="From: ".$this->data['general_settings']->contactEmailName."<".$this->data['general_settings']->Contact_Email."> \r\n"; 
				$headers .= "MIME-Version: 1.0\n"; 
				$headers .= "Content-type: text/html; charset=UTF-8\n"; 
				$subject = $result->emailTitle;
				$message ="<html><head></head><body>"."<style type=\"text/css\">
				<!--
				.style4 {font-size: x-small}
				-->
				</style>
				".$mailcontent."
				</body></html>";

				@mail($to, $subject, $message, $headers);

				// print_r($to); print_r($subject); print_r($message); print_r($headers); die;
				
				$send_data['status_code'] = 0;
				$send_data['message'] = "Your Username is Sent to Your Email.";
				
            }
			else
			{
				$send_data['status_code'] = 1;
				$send_data['message'] = "Sorry The Email Address Does Not Exist";
            }
        }
		jsonResponse($send_data);
	}

	public function resetPassword($user_info = '')
	{
		if($user_info != '')
		{
			$this->data['user_info'] = $user_info;
			$this->load->view('reset-password', $this->data);
		}
	}

	public function resetPassProcess()
	{
		$input_data = $this->input->post();
		$user_info = $input_data['user_info'];
		unset($input_data['user_info']);
		$this->load->library('form_validation');
		$this->form_validation->set_rules('userPassword', 'Password', 'trim|required');
		$this->form_validation->set_rules('rePassword', 'Repeat Password', 'trim|required|matches[userPassword]');
		$this->form_validation->set_message('matches', 'Passwords do not match.');
		if($this->form_validation->run() == FALSE)
		{
			$this->session->set_userdata('reset_pass_error',validation_errors());
			$this->session->set_userdata($input_data);
			redirect(base_url('reset-password/'.$user_info));
		}
		else
		{
			$info_user = base64_decode($user_info);
			$info_user_arr = explode('####',$info_user);
			$user_id = $info_user_arr[0];
			unset($input_data['rePassword']);
			$input_data = $this->defaultdata->secureInput($input_data);
			$input_data['userPassword'] = md5($input_data['userPassword']);
			$user_cond = array('id' => $user_id);
			$this->userdata->update($input_data,$user_cond);
			$user_det = $this->userdata->grabUserData($user_cond);

			$this->load->view('success', $this->data);
		}
	}

	public function termsCondition() 
	{
	    if (isset($_POST['termsCondition'])) return true;
	    $this->form_validation->set_message('termsCondition', 'Please Accept Our Terms & Condition.');
	    return false;
	}


	public function facebookVarification()
	{
		$user_data = array();
		$input_data = $this->input->post();
		$u_id = $input_data['u_id'];
		unset($input_data['u_id']);
		$user_cond = array('id' => $u_id);

		$user_data = $this->userdata->grabUserData($user_cond);
		
		// $nick_name = $input_data['nickName'];

		if(count($user_data)>0)
		{
			// $update_data = array('nickName' => $nick_name);
			// $condition = array('id' => $u_id);
			$this->userdata->updateUser($input_data,$user_cond);

			$user_data = $this->userdata->grabUserData($user_cond);
			unset($user_data->userPassword);
			unset($user_data->profile_picture_org);
			unset($user_data->posted_time);
			unset($user_data->ipaddress);
			unset($user_data->status);
			$send_data['user_data'] = $user_data;

			if ($user_data->facebook_varification_status == 'Y') 
			{
				$send_data['status_code'] = 0;
				$send_data['message'] = "Facebook Varification Complete";
			}
			else
			{
				$send_data['status_code'] = 1;
				$send_data['message'] = "Unable To Verify Your Facebook Account";
			}
		}
		else
		{
			$send_data['status_code'] = 1;
			$send_data['message'] = "Unable To Find The User";
		}
		jsonResponse($send_data);
	}

	public function uploadPassport()
	{
		$input_data = $this->input->post();

		$u_id = $input_data['u_id'];

		$user_cond = array('id' => $u_id);
		$check_user_data = $this->userdata->grabUserData($user_cond);

		if (count($check_user_data)>0) 
		{

			if($_FILES['passport']['name'])
			{
				$path = UPLOAD_PATH_URL."passport/";
				$allowed =  array('gif','png','jpg','jpeg','pdf');
				$name = $_FILES['passport']['name'];
				list($txt, $ext) = explode(".", $name);
				$file= time().substr(str_replace(" ", "_", $txt), 0);
				$info = pathinfo($file);
				$filename = $file.".".$ext;

				$ext = pathinfo($name, PATHINFO_EXTENSION);
				if(in_array($ext,$allowed) ) 
				{
					if(move_uploaded_file($_FILES['passport']['tmp_name'], $path.$filename))
					{
						$update_data['passport'] = $filename;
						$this->userdata->updateUser($update_data,$user_cond);
						$data['status_code'] = 0; 
						$data['message'] = "Passport Successfully Uploaded";
					}
				}
				else
				{
					$data['status_code'] = 1;
					$data['message'] = "Invalid File Format";
				}
			}
			else
			{
				$data['status_code'] = 1;
				$data['message'] = "Unable To Upload Your Passport";
			}
		}
		else
		{
			$data['status_code'] = 1;
			$data['message'] = "Unable To Find The User";
		}
		jsonResponse($data);
	}


	public function addChildrenDetails()
	{
		$input_data = $this->input->post();

		// echo "<pre>"; print_r($input_data['child_gender'][1]);

		$u_id = $input_data['u_id'];

		$user_cond = array('id' => $u_id);
		$check_user_data = $this->userdata->grabUserData($user_cond);

		if (count($check_user_data)>0) 
		{
			$cond = array('user_id' => $u_id);
			$this->userdata->deleteFunction(TABLE_USERS_CHILD, $cond);

			$length = count($input_data['child_name']);
			for ($i=0; $i < $length ; $i++) 
			{ 
				$insert_data['user_id'] = $input_data['u_id'];
				$insert_data['child_name'] = $input_data['child_name'][$i];
				$insert_data['child_age'] = $input_data['child_age'][$i];
				$insert_data['child_gender'] = $input_data['child_gender'][$i];
				$insert_id = $this->userdata->insertFunction(TABLE_USERS_CHILD, $insert_data);
			}
			if ($insert_data != NULL) 
			{
				$data['status_code'] = 0; 
				$data['message'] = "Children Details Updated";
			}
			else
			{
				$data['status_code'] = 1;
				$data['message'] = "Unable To Update Details";
			}
		}
		else
		{
			$data['status_code'] = 1;
			$data['message'] = "Unable To Find The User";
		}
		jsonResponse($data);
	}


	public function fetchChildrenDetails()
	{
		$input_data = $this->input->post();
		$u_id = $input_data['u_id'];
		$user_cond = array('id' => $u_id);
		$check_user_data = $this->userdata->grabUserData($user_cond);

		if ($check_user_data !== null) {
			$cond = array('user_id' => $u_id);
			$user_cond = array('id' => $u_id);
			$user_data = $this->userdata->grabUserData($user_cond);
			
			unset($user_data->userPassword);
			unset($user_data->profile_picture_org);
			unset($user_data->posted_time);
			unset($user_data->ipaddress);
			unset($user_data->status);
			$data['user_data'] = $user_data;
			$data['children_data'] = $this->userdata->getDetails(TABLE_USERS_CHILD, $cond);

			if ($user_data->userType == 'S') {
				$data['staff_detail'] = $this->userdata->getStaffDetail($u_id);

				if (empty($data['staff_detail'])) {
					$data['staff_detail'] = new StdClass;
				}

				// Calculate total job worked.
				$condition = [
					'approved_by_userId' => $u_id,
					'status' => 'C'
				];
				$job_worked_count = $this->webservicedata->countBookingDetailsByCondition($condition);
				
				// $data['q'] = $this->db->last_query();
				$data['staff_detail']->total_job_worked = $job_worked_count;
				
				// Calculate total job assigned.
				$condition = [
					//'assigned_to' => $u_id
					'approved_by_userId' => $u_id
				];
				$job_assigned_count = $this->webservicedata->countBookingDetailsByCondition($condition);   
				$data['staff_detail']->total_job_assigned = $job_assigned_count;

				// Calculate total hours worked.
				$condition = [
					'approved_by_userId' => $u_id,
					'status' => 'C'
				];
				$field = 'no_of_hours_needed';
				$hours_worked = $this->webservicedata->sumBookingDetailsByCondition($condition, $field);
				$data['staff_detail']->total_hours_worked = $hours_worked;

				// Calculate total earned.
				$condition = [
					'approved_by_userId' => $u_id,
					'status' => 'C'
				];
				$field = 'booking_total_price';
				$total_earned = $this->webservicedata->sumBookingDetailsByCondition($condition, $field);
				$data['staff_detail']->total_total_earned = number_format($total_earned, 2);

				// Calculate completion rate.
				$completion_rate = ($job_worked_count / $job_assigned_count) * 100;
				$data['staff_detail']->completion_rate = number_format($completion_rate, 2);
			}
			
			$data['pending'] = $this->webservicedata->getListedPendingJobsCount($u_id, $status="P");
			$data['completed'] = $this->webservicedata->getListedPendingJobsCount($u_id, $status="C");
			$data['approved'] = $this->webservicedata->getListedPendingJobsCount($u_id, $status="AP");
			$data['feedback'] = $this->webservicedata->getUserFeedbackCount($u_id);
			$data['status_code'] = 0;
			$data['message'] = "Successful";
		}
		else {
			$data['status_code'] = 1;
			$data['message'] = "Unable To Find The User";
		}
		jsonResponse($data);
	}


	public function bookForStuff()
	{
		$input_data = $this->input->post();

		$u_id = $input_data['u_id'];
		$input_data['userId'] = $input_data['u_id'];
		unset($input_data['u_id']);
		$user_cond = array('id' => $u_id);
		$check_user_data = $this->userdata->grabUserData($user_cond);

		if ($check_user_data != NULL) {

			$date_time = $input_data['date'].' '.$input_data['time'];
			$time_needed = 3600 * $input_data['no_of_hours_needed'];

			$input_data['total_price'] = $input_data['no_of_hours_needed']*$this->data['general_settings']->pricePerHour;

			unset($input_data['date']);
			unset($input_data['time']);

			$child_name = $input_data['child_name'];
			$child_age = $input_data['child_age'];
			$child_gender = $input_data['child_gender'];

			unset($input_data['child_name']);
			unset($input_data['child_age']);
			unset($input_data['child_gender']);

			$address = $input_data['address'];

			if (empty($input_data['latitude']) || empty($input_data['longitude'])) {
				$prepAddr = str_replace(' ', '+' ,$address);
				
				/*$geocode = file_get_contents('https://maps.google.com/maps/api/geocode/json?key=' . GMAPS_API_KEY . '' . $prepAddr);
				$output = json_decode($geocode);*/

				 $details_url = "http://maps.googleapis.com/maps/api/geocode/json?key=" . GMAPS_API_KEY . "address=" . $prepAddr;

				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $details_url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				$output = json_decode(curl_exec($ch), true);

				if (empty($output) || $output->status == 'ZERO_RESULTS') {
					$response = [
						'status_code' => 1,
						'message' => 'Sorry! We are unable to locate your address. Please enter a valid address.'
					];
					return jsonResponse($response);
				}

				$latitude = $output->results[0]->geometry->location->lat;
				$longitude = $output->results[0]->geometry->location->lng;

				$input_data['latitude'] = $latitude;
				$input_data['longitude'] = $longitude;
			}
			else {
				$latitude = $input_data['latitude'];
				$longitude = $input_data['longitude'];
			}

			$input_data['start_time'] = date("Y-m-d H:i:s", strtotime($date_time));
			$input_data['end_time'] = date("Y-m-d H:i:s", strtotime($date_time)+$time_needed);

			$input_data['start_time_timestamp'] = strtotime($input_data['start_time']);
			$input_data['end_time_timestamp'] = strtotime($date_time)+$time_needed;

			$input_data['actual_start_time'] = date("Y-m-d H:i:s", strtotime($input_data['start_time'])-3600);
			$input_data['actual_end_time'] = date("Y-m-d H:i:s", strtotime($input_data['end_time'])+3600);
			$input_data['status'] = 'P';
			$input_data['posted_time'] = time();

			if ($input_data['no_of_hours_needed'] <= 10) {
				$rate_per_hour = $this->webservicedata->findHourlyRate($input_data['no_of_hours_needed']);
			}
			else {
				$rate_per_hour = 10;
			}
			
			$input_data['total_price'] = $input_data['booking_total_price'] 
										= $rate_per_hour * $input_data['no_of_hours_needed'];

			$this->userdata->setTable(TABLE_BOOKING_DETAILS)->insert($input_data);
			$booking_id = $this->db->insert_id();

			for ($i=0; $i < $input_data['no_of_child'] ; $i++) { 
				$insert_data['booking_id'] = $booking_id;
				$insert_data['child_name'] = $child_name[$i];
				$insert_data['child_age'] = $child_age[$i];
				$insert_data['child_gender'] = $child_gender[$i];
				$this->userdata->insertFunction(TABLE_BOOKING_CHILD_DETAILS, $insert_data);
			}

			$get_staff = $this->webservicedata->getHighestRatingUser($latitude, $longitude);

			$staff_id = array();
			foreach ($get_staff as $get_staff_value) {
				$staff_id[] = ($get_staff_value->id);
			}

			$get_booked_staff = $this->webservicedata->getBookedUser($input_data['end_time'], $input_data['start_time']);
			$booked_staff_id = array();
			foreach ($get_booked_staff as $get_booked_staff_value) {
				$booked_staff_id[] = ($get_booked_staff_value->approved_by_userId);
			}

			$free_staff_ids = array_diff($staff_id, $booked_staff_id);

			/*dump($staff_id);
			dump($booked_staff_id);
			dd($free_staff_ids);*/

			// Intialize isAssigned
			$isAssigned = false;
			foreach ($free_staff_ids as $key => $staff_id) {
				// Try for maximum 10 times.
				if ($key >= 10) {
					break;
				}
				$isSendSuccess = $this->sendBookingNotification($staff_id, $check_user_data, $booking_id);
				// If sending success then halt and mark notification as sent.
				if ($isSendSuccess) {
					$isAssigned = true;
					$update_data['status'] = 'AS';
					$update_data['assigned_to'] = $staff_id;

					$cond['id'] = $booking_id;

					$this->userdata->updateFunction(TABLE_BOOKING_DETAILS, $update_data, $cond);
					break;
				}
			}

			$data['isAssigned'] = $isAssigned;
			$data['status_code'] = 0;
			$data['message'] = "Your Booking is Done. Please Wait for Confirmation.";
		}
		else {
			$data['status_code'] = 1;
			$data['message'] = "Unable To Find The User.";
		}
		jsonResponse($data);
	}


	public function editJobs()
	{
		$input_data = $this->input->post();

		$id = $input_data['job_id'];
		unset($input_data['job_id']);
		$u_id = $input_data['u_id'];
		$input_data['userId'] = $input_data['u_id'];
		unset($input_data['u_id']);
		$user_cond = array('id' => $u_id);
		$check_user_data = $this->userdata->grabUserData($user_cond);

		if (count($check_user_data)>0) 
		{
			$date_time = $input_data['date'].' '.$input_data['time'];
			$time_needed = 3600*$input_data['no_of_hours_needed'];

			$input_data['total_price'] = $input_data['no_of_hours_needed']*$this->data['general_settings']->pricePerHour;

			unset($input_data['date']);
			unset($input_data['time']);

			$child_name = $input_data['child_name'];
			$child_age = $input_data['child_age'];
			$child_gender = $input_data['child_gender'];

			unset($input_data['child_name']);
			unset($input_data['child_age']);
			unset($input_data['child_gender']);

			$address =$input_data['address']; // Google HQ
			$prepAddr = str_replace(' ','+',$address);
			
			
			/*$geocode=file_get_contents('https://maps.google.com/maps/api/geocode/json?key=AIzaSyDWohSoky-GvX3DNRyMO4AUJSceNFxcEnU&address='.$prepAddr.'&sensor=false');*/
			//$geocode=file_get_contents('https://maps.google.com/maps/api/geocode/json?key=AIzaSyB7OLYpEdAhozIpP7jyLwlVXnyGPXdy5Ns&address='.$prepAddr.'&sensor=false');
			
			$geocode=file_get_contents('https://maps.google.com/maps/api/geocode/json?key=AIzaSyDQA9VSWI1KuE_2D1HfDwHjwxdLr_uBmyY&address='.$prepAddr.'&sensor=false');
			  
			
			$output= json_decode($geocode);
			//dd($output);die;
			$latitude = $output->results[0]->geometry->location->lat;
			$longitude = $output->results[0]->geometry->location->lng;
			//dd($latitude);die;
			
			/*$latitude = '22.569046'; 
			$longitude = '88.435784';*/
			
             
			$input_data['latitude'] = $latitude;
			$input_data['longitude'] = $longitude;

			$input_data['start_time'] = date("Y-m-d H:i:s", strtotime($date_time));
			$input_data['end_time'] = date("Y-m-d H:i:s", strtotime($date_time)+$time_needed);

			$input_data['start_time_timestamp'] = strtotime($input_data['start_time']);
			$input_data['end_time_timestamp'] = strtotime($date_time)+$time_needed;

			$input_data['actual_start_time'] = date("Y-m-d H:i:s", strtotime($input_data['start_time'])-3600);
			$input_data['actual_end_time'] = date("Y-m-d H:i:s", strtotime($input_data['end_time'])+3600);
			$input_data['status'] = 'P';
			$input_data['posted_time'] = time();

			$cond = array('id'=>$id);
			$this->userdata->updateFunction(TABLE_BOOKING_DETAILS, $input_data, $cond);

			$cond1 = array('booking_id'=>$id);

			$this->userdata->deleteFunction(TABLE_BOOKING_CHILD_DETAILS, $cond1);

			for ($i=0; $i < $input_data['no_of_child'] ; $i++) 
			{ 
				$insert_data['booking_id'] = $id;
				$insert_data['child_name'] = $child_name[$i];
				$insert_data['child_age'] = $child_age[$i];
				$insert_data['child_gender'] = $child_gender[$i];
				$this->userdata->insertFunction(TABLE_BOOKING_CHILD_DETAILS, $insert_data);
			}

			$get_staff = $this->webservicedata->getHighestRatingUser($latitude, $longitude);
			$staff_id = array();
			foreach ($get_staff as $get_staff_value) 
			{
				$staff_id[] = ($get_staff_value->id);
			}

			$get_booked_staff = $this->webservicedata->getBookedUser($input_data['end_time'], $input_data['start_time']);
			$booked_staff_id = array();
			foreach ($get_booked_staff as $get_booked_staff_value) 
			{
				$booked_staff_id[] = ($get_booked_staff_value->approved_by_userId);
			}

			$free_staff_id = array_diff($staff_id, $booked_staff_id);
			
			if ($free_staff_id != NULL) {
				$push_notification_user_id = $free_staff_id[0];
				$cond = [
					'user_id'=> $push_notification_user_id,
					'notification' => 'ON'
				];
				$get_tokens = $this->userdata->getDetails(TABLE_USER_TOKENS, $cond);

				foreach ($get_tokens as $tokens) {
					if($tokens->flag == 'I')
					{
						$ios_tokens[] = $tokens->token;				
					}
					if($tokens->flag == 'A')
					{
						$android_tokens[] = $tokens->token;
					}
				}

				$this->userNotificationSent($push_notification_user_id);
				$badge_count = $this->webservicedata->getNotificationBadgeCount($push_notification_user_id);

				$noti = [
					"body" => 'Job edited',
					"content_available" => 1,
					"sound" => "default",
					"badge" => $badge_count,
					"click_action" => "ACTIONABLE",
					"mutable-content" => 1
				];

				$job_details = $this->webservicedata->getDetailsOfaJob($id);

				$message['job_id'] = $job_details->id;
				$message['job_details'] = $job_details->job_details;
				$message['user_id'] = $check_user_data->id;
				$message['user_name'] = $check_user_data->userName;
				$message['profile_picture'] = $check_user_data->profile_picture;

				$message['notification'] = [
					"body" => 'Job edited',
					"badge" => $badge_count
				];

				$encode_message = json_encode($message);

				$message = array("message" => $encode_message, "contents" => "contents");
				
				if (!empty($ios_tokens)) {
					$message_status_ios = sendNotificationIOS($ios_tokens, $message, $noti);
					$message_status_ios = json_decode($message_status_ios);
					if ($message_status_ios->success == 0) {
						$response = [
							'status_code' => 1,
							'fcm_response' => $message_status_ios
						];
						return jsonResponse($response);
					}
				}
				
				if (!empty($android_tokens)) {
					$message_status_andriod = sendNotificationAndroid($android_tokens, $message);
					$message_status_andriod = json_decode($message_status_andriod);
					if ($message_status_andriod->success == 0) {
						$response = [
							'status_code' => 1,
							'fcm_response' => $message_status_andriod
						];
						return jsonResponse($response);
					}
				}

				$update_data['status'] = 'AS';
				$update_cond['id'] = $id;

				$this->userdata->updateFunction(TABLE_BOOKING_DETAILS, $update_data, $update_cond);
			}

			$data['status_code'] = 0;
			$data['message'] = "Your Booking is Done. Please Wait for Confirmation";
		}
		else
		{
			$data['status_code'] = 1;
			$data['message'] = "Unable To Find The User";
		}
		jsonResponse($data);
	}


	public function getListOfPendingJobs()
	{
		$input_data = $this->input->post();
		$u_id = $input_data['u_id'];
		$status = $input_data['status'];
		$user_cond = array('id' => $u_id);
		$check_user_data = $this->userdata->grabUserData($user_cond);

		if (count($check_user_data)>0) {
			// Pagination data.
			$pagination = [
				'offset' => $this->offset,
				'per_page' => $this->per_page
			];
			$data['job_list'] = $this->webservicedata->getListedPendingJobs($u_id, $status, $pagination);
			$data['status_code'] = 0;
			$data['message'] = "Successful";
		}
		else
		{
			$data['status_code'] = 1;
			$data['message'] = "Unable To Find The User";
		}
		jsonResponse($data);
	}

	public function getDetailsOfaJob()
	{
		$input_data = $this->input->post();
		$job_id = $input_data['job_id'];
		$u_id = $input_data['u_id'];

		$data['job_details'] = $this->webservicedata->getDetailsOfaJob($job_id,$u_id);
		$data['status_code'] = 0;
		$data['message'] = "Successful";

		jsonResponse($data);
	}


	public function getListofAllPendingJobs()
	{
		// Pagination data.
		$pagination = [
			'offset' => $this->offset,
			'per_page' => $this->per_page
		];
		$data['job_details'] = $this->webservicedata->getCurrentlyPendingJob($pagination);
		$data['status_code'] = 0;
		$data['message'] = "Successful";

		jsonResponse($data);
	}


	public function acceptJobRequest()
	{
		$input_data = $this->input->post();
		$cond = array('id' => $input_data['job_id']);
		$ios_tokens =array();
		$job_details = $this->userdata->grabDetails(TABLE_BOOKING_DETAILS, $cond);

		if ($job_details->status == 'AP' && $job_details->approved_by_userId != $input_data['u_id'])  {
			$data['status_code'] = 1;
			if ($input_data['status'] == 'AP') {
				$data['message'] = "This Job is Already Accepted by Another Staff";
			}
			else {
				$data['message'] = "Job is Rejected";
			}
		}
		else {
			if ($input_data['status'] == $job_details->status && $job_details->approved_by_userId == $input_data['u_id']) {
				$data['status_code'] = 1;
				if($job_details->status == 'AP') {
					$data['message'] = "You Have Already Accepted This Job.";
				}
				if($job_details->status == 'P') {
					$data['message'] = "You Have Already Rejected This Job.";
				}
			}
			else {
				$update_data['approved_by_userId'] = $input_data['u_id'];
				$update_data['status'] = $input_data['status'];
					$this->userdata->updateFunction(TABLE_BOOKING_DETAILS, $update_data, $cond);
				if ($input_data['status'] == 'AP') {

					$data['status_code'] = 0;
					$data['message'] = "Job is Accepted";

					/*-------- Send push notifications to customer ---------*/
					$push_notification_user_id = $job_details->userId;
					$token_cond = [
						'user_id'=> $push_notification_user_id,
						'notification' => 'ON'
					];

					//echo "<pre>";
					//print_r($token_cond);
					//die;
					//$token_cond['user_id'] = 23;
					$user_tokens = $this->userdata->getDetailsOrderby(TABLE_USER_TOKENS, $token_cond);
					//echo $this->db->last_query();
					//die;
					/*dump($this->db->last_query());
					dd($user_tokens);*/

					foreach ($user_tokens as $tokens) {
						if($tokens->flag == 'I') {
							$ios_tokens[] = $tokens->token;
						}
						if($tokens->flag == 'A') {
							$android_tokens[] = $tokens->token;
						}
					}

					

					$this->load->helper('text');

					// Grab the approved by user.
					$user_cond = ['id' => $input_data['u_id']];

					// dd($job_details->approved_by_userId);


					$approved_by_user_data = $this->userdata->grabUserData($user_cond);

					// dd($approved_by_user_data);

					$fullName = $approved_by_user_data->firstName . ' ' . $approved_by_user_data->lastName;
					$fullName = character_limiter($fullName, 100, '...');

					$job_desc = 'accepted your  booking' . character_limiter($job_details->job_details, 150, '...');

					$this->userNotificationSent($push_notification_user_id);
					$badge_count = $this->webservicedata->getNotificationBadgeCount($push_notification_user_id);

					$noti = [
						"body" => $job_desc,
						"title" => $fullName,
						"content_available" => 1, 
						"sound" => "default", 
						"badge" => $badge_count,
						"click_action" => "ACTIONABLE",
						"mutable-content" => 1
					];

					// dd($noti);

					$push_job_details = [
						'id' => $job_details->id,
						'booking_total_price' => $job_details->booking_total_price
					];

					$message['type'] = 'A';
					$message['job_details'] = $push_job_details;
					$message['notification'] = [
						"body" => $job_desc,
						"title" => $fullName,
						"badge" => $badge_count
					];

					// dd($message);

					$json_message = json_encode($message);
					$message = array("message" => $json_message, "contents" => "contents");
					
					if (count($ios_tokens)>0) {
						$message_status_ios = sendNotificationIOS($ios_tokens, $message, $noti);
						$message_status_ios = json_decode($message_status_ios);
						if ($message_status_ios->success == 0) {
							$response = [
								'status_code' => 1,
								'device' => 'ios',
								'fcm_response' => $message_status_ios
							];
							//return jsonResponse($response);
						}
					}
					
					if (count($android_tokens)>0) {
						//die('1');
						$message_status_andriod = sendNotificationAndroid($android_tokens, $message);
						$message_status_andriod = json_decode($message_status_andriod);
						
						//echo "<br>";
						if ($message_status_andriod->success == 0) {
							$response = [
								'status_code' => 1,
								'device' => 'android',
								'fcm_response' => $message_status_andriod
							];
							return jsonResponse($response);
						}
					}

					//die;
				}
				if ($input_data['status'] == 'P') {
					// $update_data['approved_by_userId'] = 0;
					// $update_data['status'] = $input_data['status'];
					// $this->userdata->updateFunction(TABLE_BOOKING_DETAILS, $update_data, $cond);
					$data['status_code'] = 0;
					$data['message'] = "Job is Rejected";
				}

				$updated_job_details = $this->userdata->grabDetails(TABLE_BOOKING_DETAILS, $cond);
				$data['job_details'] = $updated_job_details;
			}
		}
		jsonResponse($data);
	}

	public function addUserRating()
	{
		$input_data = $this->input->post();
		$u_id = $input_data['u_id'];
		$staff_id = $input_data['staff_id'];
		
		$required_params = ['job_id', 'staff_id', 'u_id'];
		$validate_request = validateRequest($input_data, $required_params);
		if (!$validate_request['isValid']) {
			return jsonResponse([
				'error_message' => $validate_request['missing_text'],
				'status' => 1
			], 400);
		}

		$input_data['posted_time'] = time();

		$cond = [
			'job_id' => $input_data['job_id'],
			'staff_id' => $input_data['staff_id'],
			'u_id' => $input_data['u_id'],
		];

		$user_rating = $this->db->where($cond)->get(TABLE_USER_RATING)->row();
        //dd($user_rating); die();
		if (! empty($user_rating)) {
			return jsonResponse([
				'message' => 'Sorry! You have already posted rating.',
				'user_rating' => $user_rating,
				'status' => 1
			], 200);
		}
		

		$fillable = [
			'job_id',
			'staff_id',
			'u_id',
			'rating',
			'comment',
			'posted_time'
		];

		if (!empty($input_data['rating'])) {
			$input_data['rating'] = 
				$input_data['rating'] > 5 ? 5 : $input_data['rating'];
		}
		else {
			$input_data['rating'] = 0;
		}

		$this->mainmodel->setTable(TABLE_USER_RATING);
		$last_id = $this->mainmodel->insertByFillable($input_data, $fillable);

		// Calculate user rating.
		$avg_user_rating = number_format($input_data['rating'], 1);

		$this->userdata->updateUserRating($avg_user_rating, $input_data['staff_id']);
		
		// Rating has been given by both sides or not
		$user_feedback = $this->mainmodel->setTable(TABLE_USER_RATING)->fetchAll($cond=array('job_id'=>$input_data['job_id']));
	    $this->mainmodel->unsetTable();
        // dd($user_feedback);die();
	    if (count($user_feedback)==2)
	    {
	    	// update job status to C
	    	$updated_job_id = $input_data['job_id'];
	    	//$this->db->where("job_id = $updated_job_id");
			$this->db->where("id = $updated_job_id");
	    	$this->db->update(TABLE_BOOKING_DETAILS, ['status' => 'C']);
			
			$admin_amt = $this->webservicedata->adminAmount($u_id);
		    //dd($admin_amt->amount); die();
			//$transaction_data = $this->userdata->grabDetails(TABLE_TRANSACTION, array('user_id' => $staff_id,'type' => 'D'));
			//if(count($transaction_data) <= 0){
			$insert_data['user_id']=$staff_id; 
			$insert_data['transaction_type']='2';
			$insert_data['type']='C';
			$insert_data['amount']=$admin_amt->amount;
			$insert_data['payment_status']='Y';
			$this->userdata->insertFunction(TABLE_TRANSACTION, $insert_data);
			
		//}

	    }

		$data['status_code'] = 0;
		$data['message'] = "Successful";
		jsonResponse($data);
	}


	public function getListOfAllJobs()
	{
		$input_data = $this->input->post();
		$u_id = $input_data['u_id'];
        $month = $input_data['month'];
		$year = $input_data['year'];
		// Pagination data.
		$pagination = [
			'offset' => $this->offset,
			'per_page' => $this->per_page
		];

		//$job_details = $this->webservicedata->getListOfAllJobs($u_id, $pagination);
        $job_details = $this->webservicedata->getListOfAllJobs($u_id,$month,$year,$pagination);
		if (count($job_details)>0) 
		{
			$data['job_details'] = $job_details;
			$data['status_code'] = 0;
			$data['message'] = "Successful";
		}
		else
		{
			$data['status_code'] = 1;
			$data['message'] = "No Data Available";
		}
		jsonResponse($data);
	}


	public function fetchJobByDate()
	{
		$input_data = $this->input->post();

		$job_details = $this->webservicedata->fetchJobByDate($input_data['date'], $input_data['u_id']);

		if (count($job_details)>0) 
		{
			$data['job_details'] = $job_details;
			$data['status_code'] = 0;
			$data['message'] = "Successful";
		}
		else
		{
			$data['status_code'] = 1;
			$data['message'] = "No Data Available";
		}
		jsonResponse($data);
	}


	public function getListOfDate()
	{
		$input_data = $this->input->post();
		$u_id = $input_data['u_id'];

		$job_details = $this->webservicedata->getListOfAllJobs1($u_id);

		if (count($job_details)>0) 
		{
			foreach ($job_details as $job_details_value) 
			{
				$date[] = array("time"=>$job_details_value->start_time_timestamp, "is_expired"=>$job_details_value->is_expired);
			}
			$data['date'] = $date;
			$data['status_code'] = 0;
			$data['message'] = "Successful";
		}
		else
		{
			$data['status_code'] = 1;
			$data['message'] = "No Data Available";
		}
		jsonResponse($data);
	}

	public function filterJobList()
	{
		$input_data = $this->input->post();

		$filteredData = $this->webservicedata->filterJobList($input_data);

		if (!empty($filteredData)) {
			$data['job_details'] = $filteredData;
			$data['status_code'] = 0;
			$data['message'] = "Successful";
		}
		else {
			$data['status_code'] = 1;
			$data['message'] = "No Data Available";
		}
		jsonResponse($data);
	}

	public function getallrating()
	{
		$input_data = $this->input->post();
		$u_id = $input_data['u_id'];

		$allratingdata = $this->webservicedata->allrating($u_id);

		jsonResponse($allratingdata);
	}

	public function finishJob()
	{
		$input_data = $this->input->post();
		$job_id = $input_data['job_id'];
		
		if (empty($job_id) || $job_id < 1) {
			return jsonResponse([
				'error_message' => "Invalid request. Missing the 'job_id' parameter",
				'status' => 1
			], 400);
		}

		$updateData = $this->webservicedata->finishJob($job_id);
		// Fetch job details.
		$booking_details = $this->webservicedata->getDetailsOfaJob($job_id);
		//dd($booking_details);die();
        //$admin_amt = $this->webservicedata->adminAmount($booking_details->userId);
		//dd($admin_amt->amount); die();
		//$transaction_data = $this->userdata->grabDetails(TABLE_TRANSACTION, array('user_id' => $booking_details->approved_by_userId,'type' => 'D'));
		//dd($transaction_data); die();
		//if(count($transaction_data) <= 0){
		$insert_data['user_id']=$booking_details->approved_by_userId; 
		//$insert_data['booking_detail_id']=$job_id;
		$insert_data['transaction_type']='2';
		$insert_data['type']='C';
		$insert_data['amount']=$booking_details->booking_total_price;
		$insert_data['payment_status']='Y';
		$this->userdata->insertFunction(TABLE_TRANSACTION, $insert_data);
		//dd($booking_details->userId);die();
		//}
		jsonResponse($booking_details);
	}

	public function extendJob()
	{
		$input_data = $this->input->post();
		$job_id = $input_data['job_id'];
		$extend_hour = $input_data['extend_hour'];

		if (empty($job_id) || $job_id < 1) {
			return jsonResponse([
				'error_message' => "Invalid request. Missing the 'job_id' parameter",
				'status' => 1
			], 400);
		}
		else if (empty($extend_hour) || $extend_hour < 1) {
			return jsonResponse([
				'error_message' => "Invalid request. Missing the 'job_id' parameter",
				'status' => 1
			], 400);
		}

		$updateData = $this->webservicedata->extendJob($job_id, $extend_hour);
		// Fetch job details.
		$booking_details = $this->webservicedata->getDetailsOfaJob($job_id);

		jsonResponse($booking_details);
	}

	public function customerCancelJob()
	{
		$input_data = $this->input->post();
		
		$required_params = ['job_id'];
		$validate_request = validateRequest($input_data, $required_params);
		if (!$validate_request['isValid']) {
			return jsonResponse([
				'error_message' => $validate_request['missing_text'],
				'status' => 1
			], 400);
		}

		// Allowed only for unpaid booking.
		// Fetch job details.
		$booking_details = $this->webservicedata->getDetailsOfaJob($input_data['job_id']);
		/*if ($booking_details->payment_status != 1) {
			return jsonResponse([
				'error_message' => 'Sorry!Cannot cancel booking for paid job.',
				'status' => 1
			], 400);
		}
		else*/if ($booking_details->status == 'CC') {
			return jsonResponse([
				'error_message' => 'Sorry!You have already cancelled the job.',
				'status' => 1
			], 400);
		}

		$status = 'CC';
		$updateData = $this->webservicedata->changeJobStatus($input_data['job_id'], $status);
		// Fetch job details.
		$booking_details = $this->webservicedata->getDetailsOfaJob($input_data['job_id']);

		$response = [
			'status' => 0,
			'message' => 'Successfully cancelled the job!',
			'booking_details' => $booking_details
		];

		jsonResponse($response);
	}

	public function registerChat()
	{
		$input_data = $this->input->post();

		if (empty($input_data['job_id']) || $input_data['job_id'] < 1) {
			return jsonResponse([
				'error_message' => "Invalid request. Missing the 'job_id' parameter",
				'status' => 1
			], 400);
		}
		else if (empty($input_data['sender_id']) || $input_data['sender_id'] < 1) {
			return jsonResponse([
				'error_message' => "Invalid request. Missing the 'sender_id' parameter",
				'status' => 1
			], 400);
		}
		else if (empty($input_data['receiver_id']) || $input_data['receiver_id'] < 1) {
			return jsonResponse([
				'error_message' => "Invalid request. Missing the 'receiver_id' parameter",
				'status' => 1
			], 400);
		}

		$booking_chat_list = $this->db->where($input_data)->get(TABLE_BOOKING_CHAT_LIST)->row();

		if (empty($booking_chat_list)) {
			$this->userdata->insertFunction(TABLE_BOOKING_CHAT_LIST, $input_data);
		}

		// Register for other user.
		$other_user = $input_data;
		$sender_id = $other_user['sender_id'];
		$other_user['sender_id'] = $other_user['receiver_id'];
		$other_user['receiver_id'] = $sender_id;

		$booking_chat_list = $this->db->where($other_user)->get(TABLE_BOOKING_CHAT_LIST)->row();

		if (empty($booking_chat_list)) {
			$this->userdata->insertFunction(TABLE_BOOKING_CHAT_LIST, $other_user);
		}

		return jsonResponse(['status_code' => 0]);

		/*------------------ No need for push notification -----------------------*/

		// Grab the receiver.
		$user_cond = array('id' => $input_data['receiver_id']);
		$check_user_data = $this->userdata->grabUserData($user_cond);

		$push_notification_user_id = $input_data['receiver_id'];

		$token_cond = [
			'user_id' => $push_notification_user_id,
			'notification' => 'ON'
		];
		$get_tokens = $this->userdata->getDetails(TABLE_USER_TOKENS, $token_cond);

		foreach ($get_tokens as $tokens) {
			if($tokens->flag == 'I') {
				$ios_tokens[] = $tokens->token;				
			}
			if($tokens->flag == 'A') {
				$android_tokens[] = $tokens->token;
			}
		}

		$this->userNotificationSent($push_notification_user_id);
		$badge_count = $this->webservicedata->getNotificationBadgeCount($push_notification_user_id);

		$noti = [
			"body" => 'User started chat',
			"content_available" => 1,
			"sound" => "default",
			"badge" => $badge_count,
			"click_action" => "ACTIONABLE",
			"mutable-content" => 1
		];

		$job_details = $this->webservicedata->getDetailsOfaJob($input_data['job_id']);

		if (empty($job_details)) {
			return jsonResponse(['status_code' => 0]);
		}

		$message['job_id'] = $job_details->id;
		$message['job_details'] = $job_details->job_details;
		$message['user_id'] = $check_user_data->id;
		$message['user_name'] = $check_user_data->userName;
		$message['profile_picture'] = $check_user_data->profile_picture;

		$message['notification'] = [
			"body" => 'User started chat',
			"badge" => $badge_count
		];

		$json_message = json_encode($message);
		$message = array("message" => $json_message, "contents" => "contents");

		if (!empty($ios_tokens)) {
			$message_status_ios = sendNotificationIOS($ios_tokens, $message, $noti);
			$message_status_ios = json_decode($message_status_ios);
			if ($message_status_ios->success == 0) {
				$response = [
					'status_code' => 1,
					'fcm_response' => $message_status_ios
				];
				return jsonResponse($response);
			}
		}
		
		if (!empty($android_tokens)) {
			$message_status_andriod = sendNotificationAndroid($android_tokens, $message);
			$message_status_andriod = json_decode($message_status_andriod);
			if ($message_status_andriod->success == 0) {
				$response = [
					'status_code' => 1,
					'fcm_response' => $message_status_andriod
				];
				return jsonResponse($response);
			}
		}

		jsonResponse(['status_code' => 0]);
	}

	public function notifyUserAboutChat()
	{
		$input_data = $this->input->post();

		// return jsonResponse($input_data);

		if (empty($input_data['job_id']) || $input_data['job_id'] < 1) {
			return jsonResponse([
				'error_message' => "Invalid request. Missing the 'job_id' parameter",
				'status' => 1
			], 400);
		}
		else if (empty($input_data['sender_id']) || $input_data['sender_id'] < 1) {
			return jsonResponse([
				'error_message' => "Invalid request. Missing the 'sender_id' parameter",
				'status' => 1
			], 400);
		}
		else if (empty($input_data['receiver_id']) || $input_data['receiver_id'] < 1) {
			return jsonResponse([
				'error_message' => "Invalid request. Missing the 'receiver_id' parameter",
				'status' => 1
			], 400);
		}
		else if (empty($input_data['message'])) {
			return jsonResponse([
				'error_message' => "Invalid request. Missing the 'message' parameter",
				'status' => 1
			], 400);
		}

		// Grab the sender.
		$user_cond = array('id' => $input_data['sender_id']);
		$sender_data = $this->userdata->grabUserData($user_cond);
		
		// Grab the receiver.
		/*$user_cond = array('id' => $input_data['receiver_id']);
		$receiver_data = $this->userdata->grabUserData($user_cond);*/

		$push_notification_user_id = $input_data['receiver_id'];

		// Grab the receiver.
		$token_cond = [
			'user_id'=> $push_notification_user_id,
			'notification' => 'ON'
		];
		$get_tokens = $this->userdata->getDetails(TABLE_USER_TOKENS, $token_cond);

		foreach ($get_tokens as $tokens) {
			if($tokens->flag == 'I') {
				$ios_tokens[] = $tokens->token;				
			}
			if($tokens->flag == 'A') {
				$android_tokens[] = $tokens->token;
			}
		}

		$this->userNotificationSent($push_notification_user_id);
		$badge_count = $this->webservicedata->getNotificationBadgeCount($push_notification_user_id);

		$noti = [
			"body" => $input_data['message'],
			"title" => $sender_data->firstName .' ' . $sender_data->lastName,
			"content_available" => 1, 
			"sound" => "default", 
			"badge" => $badge_count,
			"click_action" => "ACTIONABLE",
			"mutable-content" => 1
		];

		$job_details = $this->webservicedata->getDetailsOfaJob($input_data['job_id']);
		if (empty($job_details)) {
			return jsonResponse(['status_code' => 1]);
		}

		// dd($job_details);
		$fullName = $job_details->userdetails->firstName . ' ' . $job_details->userdetails->lastName;

		$push_job_details = [
			'user_id' => $job_details->userId,
			'approved_by_userId' => $job_details->approved_by_userId,
			'booking_total_price' => $job_details->booking_total_price,
			'fullName' => $fullName,
			'job_id' => $job_details->id,
			'posted_time' => $job_details->posted_time,
			'start_time_timestamp' => $job_details->start_time_timestamp,
			'end_time_timestamp' => $job_details->end_time_timestamp,
			'total_price' => $job_details->total_price
		];

		$push_chat_details = [
			'receiver_id' => $input_data['receiver_id'],
			'sender' => [
				'id' => $input_data['sender_id'],
				'fullName' => $sender_data->firstName . ' ' . $sender_data->lastName
			]
		];

		$message['type'] = 'C';
		$message['chat_details'] = $push_chat_details;
		$message['job_details'] = $push_job_details;

		$message['notification'] = [
			"body" => $input_data['message'],
			"title" => $sender_data->firstName .' ' . $sender_data->lastName,
			"badge" => $badge_count
		];

		$json_message = json_encode($message);
		$message = array("message" => $json_message, "contents" => "contents");
		
		if (!empty($ios_tokens)) {
			$message_status_ios = sendNotificationIOS($ios_tokens, $message, $noti);
			$message_status_ios = json_decode($message_status_ios);
			if ($message_status_ios->success == 0) {
				$response = [
					'status_code' => 1,
					'fcm_response' => $message_status_ios
				];
				return jsonResponse($response);
			}
		}
		
		if (!empty($android_tokens)) {
			$message_status_andriod = sendNotificationAndroid($android_tokens, $message);
			$message_status_andriod = json_decode($message_status_andriod);
			if ($message_status_andriod->success == 0) {
				$response = [
					'status_code' => 1,
					'fcm_response' => $message_status_andriod
				];
				return jsonResponse($response);
			}
		}

		jsonResponse(['status_code' => 0]);
	}

	public function getChatList()
	{
		$input_data = $this->input->post();
		$u_id = $input_data['u_id'];

		$chat_list = $this->webservicedata->getChatList($u_id);

		return jsonResponse([
			'chat_list' => $chat_list
		]);
	}

	public function resetBadgeCount()
	{
		$input_data = $this->input->post();
		if (empty($input_data['u_id']) || $input_data['u_id'] < 1) {
			return jsonResponse([
				'error_message' => "Invalid request. Missing the 'u_id' parameter",
				'status' => 1
			], 400);
		}

		$user_id = $input_data['u_id'];
		$isReset = $this->webservicedata->resetNotificationBadgeCount($user_id);

		/*----------- Send push notification -----------*/
		$push_notification_user_id = $user_id;

		$cond = [
			'user_id'=> $push_notification_user_id,
			'notification' => 'ON'
		];
		$get_tokens = $this->userdata->getDetails(TABLE_USER_TOKENS, $cond);

		foreach ($get_tokens as $tokens) {
			if($tokens->flag == 'I') {
				$ios_tokens[] = $tokens->token;				
			}
			if($tokens->flag == 'A')
			{
				$android_tokens[] = $tokens->token;
			}
		}

		$noti = [];
		/*$noti = [
			"body" => 'New Booking',
			"content_available" => 1,
			"sound" => "default",
			"badge" => $badge_count,
			"click_action" => "ACTIONABLE",
			"mutable-content" => 1
		];*/
		
		$message['type'] = 'BDG';
		$message['badge'] = 0;


		$encode_message = json_encode($message);

		$message = array("message" => $encode_message, "contents" => "contents");

		if (!empty($ios_tokens)) {
			$message_status_ios = sendNotificationIOS($ios_tokens, $message, $noti);
			$message_status_ios = json_decode($message_status_ios);
			if ($message_status_ios->success == 0) {
				$response = [
					'status_code' => 1,
					'fcm_response' => $message_status_ios
				];
				return jsonResponse($response);
			}
		}
		
		if (!empty($android_tokens)) {
			$message_status_andriod = sendNotificationAndroid($android_tokens, $message);
			$message_status_andriod = json_decode($message_status_andriod);
			if ($message_status_andriod->success == 0) {
				$response = [
					'status_code' => 1,
					'fcm_response' => $message_status_andriod
				];
				return jsonResponse($response);
			}
		}

		return jsonResponse([
			'status_code' => 0
		]);
	}

	public function getHourlyRates()
	{
		$hourly_rates = $this->db->select('id, no_of_hr, rate_per_hour')
			->where('is_active', 1)
			->order_by('no_of_hr', 'asc')
			->get(TABLE_HOURLY_RATE )
			->result();

		// Add 10+ hours.
		/*$object = new StdClass;
		$object->no_of_hr = '10+';
		$object->rate_per_hour = '10';
		array_push($hourly_rates, $object);*/

		return jsonResponse([
			'hourly_rates' => $hourly_rates,
			'currency' => 'pound',
			'curency_symbol' => '£',
			'status' => 0
		]);
	}

	public function customerPayment()
	{
		$input_data = $this->input->post();

		if (empty($input_data['stripeToken'])) {
			return jsonResponse([
				'error_message' => "Invalid request. Missing the 'stripeToken' parameter",
				'status' => 1
			], 400);
		}
		 if (empty($input_data['job_id'])) {
			return jsonResponse([
				'error_message' => "Invalid request. Missing the 'job_id' parameter",
				'status' => 1
			], 400);
		}
		 if (empty($input_data['user_id'])) {
			return jsonResponse([
				'error_message' => "Invalid request. Missing the 'user_id' parameter",
				'status' => 1
			], 400);
		}
		 if (empty($input_data['price'])) {
			return jsonResponse([
				'error_message' => "Invalid request. Missing the 'price' parameter",
				'status' => 1
			], 400);
		}

		$job_details = $this->webservicedata->getDetailsOfaJob($input_data['job_id'],$input_data['user_id']);
		if (empty($job_details)) {
			return jsonResponse(['status_code' => 1]);
		}

		$metadata = [
			'job_id' => $job_details->id,
			'job_details' => $job_details->job_details
		];

		require APPPATH . 'third_party/stripe/init.php';
		$stripe_secret_key =$this->data['general_settings']-> stripe_secret_key;

		$user_id = $input_data['user_id'];

		$amount = $input_data['price'];
		$amount = number_format($amount,  2);
		$request_price = $amount  * 100;
		// Get the user data from database.
		$user = $this->userdata->grabUserData(array('id'=> $user_id));

		try {
			\Stripe\Stripe::setApiKey($stripe_secret_key);
			// Check if customer is already registered to stripe.
			if (!empty($user->stripe_customer_id)) {
				$stripe_customer_id = $user->stripe_customer_id;
			}
			else {
				$customer = \Stripe\Customer::create(array(
						"source" => $input_data['stripeToken'],
						"description" => "Booking baby sitter",
						"email" =>$user->emailAddress
					)
				);
				$stripe_customer_id = $customer->id;
				
				// Update customer's stripe_id.
				$user_data = [
					'stripe_customer_id' => $stripe_customer_id
				];
				$cond = [
					'id' => $user_id
				];
				$this->userdata->updateFunction(TABLE_USER, $user_data, $cond);
			}

			// Charge the Customer instead of the card
			try {
				$stripe_response = \Stripe\Charge::create(array(
						"amount" => $request_price, # amount in cents, again
						"currency" => "GBP",
						"customer" => $stripe_customer_id,
						"capture" => false,
						"metadata" => $metadata
					)
				);
			} catch(\Stripe\Error\InvalidRequest $e) {
				$customer = \Stripe\Customer::create(array(
						"source" => $input_data['stripeToken'],
						"description" => "Booking baby sitter",
						"email" =>$user->emailAddress
					)
				);
				$stripe_customer_id = $customer->id;
				
				// Update customer's stripe_id.
				$user_data = [
					'stripe_customer_id' => $stripe_customer_id
				];
				$cond = [
					'id' => $user_id
				];
				$this->userdata->updateFunction(TABLE_USER, $user_data, $cond);

				$stripe_response = \Stripe\Charge::create(array(
						"amount" => $request_price, # amount in cents, again
						"currency" => "GBP",
						"customer" => $stripe_customer_id,
						"capture" => false,
						"metadata" => $metadata
					)
				);
			}
				
			
			// $retrieve = \Stripe\Charge::retrieve($stripe_response->id);

			$transaction_data = [
					'user_id' => $user->id,
					'booking_detail_id' => $input_data['job_id'],
					'transaction_type' => 1,
					'amount' => $amount,
					'gateway' => 1,
					'payment_status' => 'Y'
			];
			// Credit to user.
			$transaction_data['type'] = 'C';
			$transaction_id = $this->userdata->insertFunction(TABLE_TRANSACTION, $transaction_data);
			// Debit from user.
			$transaction_data['type'] = 'D';
			$transaction_id = $this->userdata->insertFunction(TABLE_TRANSACTION, $transaction_data);
			
			// set the booking charge id.
			$booking_data = [
				'payment_status' => 2, // Paid
				'stripe_charge_id' => $stripe_response->id
			];
			$cond = ['id' => $input_data['job_id']];
			$this->userdata->updateFunction(TABLE_BOOKING_DETAILS, $booking_data, $cond);
			
		} catch(\Stripe\Error\Card $e) {

			$booking_data = [
				'payment_status' => 3, // Failed
			];
			$cond = ['id', $input_data['job_id']];
			$this->userdata->updateFunction(TABLE_BOOKING_DETAILS, $booking_data, $cond);

			return jsonResponse([
				'message' =>'card_error',
				'status_code' => 1
			]);
		} catch(Expection $e) {
			return jsonResponse([
				'message' => 'error',
				'status_code' => 1
			]);
		}

		
		return jsonResponse([
			'status_code' => 0
		]);
	}

	public function fetchXpData()
	{
		$months = [1,2,3,4,5,6,7,8,9,10,11,12];
		$years = [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15];

		return jsonResponse([
			'months' => $months,
			'years' => $years
		]);
	}

	private function userNotificationSent($user_id)
	{
		$this->webservicedata->increaseNotificationBadgeCount($user_id);
	}

	private function getUserTokens($user_id = null) {
		if ($user_id === null || $user_id < 1) 
			return [];

		$cond = [
			'user_id'=> $user_id,
			'notification' => 'ON'
		];
		$user_tokens = $this->userdata->getDetails(TABLE_USER_TOKENS, $cond);

		// Initialize.
		$ios_tokens = $android_tokens = [];
		foreach ($user_tokens as $tokens) {
			if ($tokens->flag == 'I') {
				$ios_tokens[] = $tokens->token;
			}
			if ($tokens->flag == 'A') {
				$android_tokens[] = $tokens->token;
			}
		}

		return [
			'ios_tokens' => $ios_tokens,
			'android_tokens' => $android_tokens,
		];
	}

	private function sendBookingNotification($push_notification_user_id, $check_user_data, $job_id) {

		$isSendSuccess = false;
		// Get fcm tokens.
		$user_tokens = $this->getUserTokens($push_notification_user_id);
		// Get notigication badge count.
		$badge_count = $this->webservicedata->getNotificationBadgeCount($push_notification_user_id);
		// Add 1 with current count for the ongoing notification.
		$badge_count += 1;
		// Prepare and send notification.
		$noti = [
			"body" => 'New Booking',
			"content_available" => 1,
			"sound" => "default",
			"badge" => $badge_count,
			"click_action" => "ACTIONABLE",
			"mutable-content" => 1
		];
		
		$job_details = $this->webservicedata->getDetailsOfaJob($job_id);

		// return jsonResponse($job_details);

		$push_job_details = [
			'user_id' => $job_details->userId,
			'address' => $job_details->address,
			'approved_by_userId' => $job_details->approved_by_userId,
			'booking_total_price' => $job_details->booking_total_price,
			'total_price' => $job_details->total_price,
			'job_id' => $job_details->id,
			'latitude' => $job_details->latitude,
			'longitude' => $job_details->longitude,
			'no_of_child' => $job_details->no_of_child,
			'no_of_hours_needed' => $job_details->no_of_hours_needed,
			'start_time_timestamp' => $job_details->start_time_timestamp,
			'end_time_timestamp' => $job_details->end_time_timestamp,
			'child_details' => $job_details->child_details
		];

		$push_user_details = [
			'id' => $check_user_data->id,
			'firstName' => $check_user_data->firstName,
			'lastName' => $check_user_data->lastName
		];

		$message['job_details'] = $push_job_details;
		$message['user_details'] = $push_user_details;
		$message['type'] = 'PB';

		$message['notification'] = [
			"body" => 'New Booking',
			"badge" => $badge_count
		];

		$encode_message = json_encode($message);

		$message = array("message" => $encode_message, "contents" => "contents");


		if (!empty($user_tokens['ios_tokens'])) {
			$message_status_ios = sendNotificationIOS($user_tokens['ios_tokens'], $message, $noti);

			$message_status_ios = json_decode($message_status_ios);
			if ($message_status_ios->success == 1) {
				$isSendSuccess = true;
			}
		}
		
		if (!empty($user_tokens['android_tokens'])) {
			$message_status_andriod = sendNotificationAndroid($user_tokens['android_tokens'], $message);
			$message_status_andriod = json_decode($message_status_andriod);
			if ($message_status_andriod->success == 1) {
				$isSendSuccess = true;
			}
		}
		if ($isSendSuccess) {
			$this->userNotificationSent($push_notification_user_id);
		}
		return $isSendSuccess;
	}
	
	public function employeeEarnings()
	{
		$input_data = $this->input->post();
		$u_id = $input_data['u_id'];
		$month = $input_data['month'];
		$year = $input_data['year'];
        $settings=$this->defaultdata->grabSettingData();
        $comision247=$settings->percentage_amount;
         
		$earning = $this->webservicedata->employeeEarnings($u_id,$month,$year);
     
        foreach ($earning as $key => $value) {
        	//dd($value->id);
        	$earning[$key]->comision247 = ($comision247/100)*$value->total_price;
        	$earning[$key]->withdrawl_amt = $value->total_price-$earning[$key]->comision247; 
        	//$rate = $this->webservicedata->getUserRating($value->id);
        }
        $array=array('month_earning' => 0, 'total_bookings' => 0, 'hours_worked' => 0.0);
        foreach ($earning as $value) {
        	
			$hours_worked = $value->diff;
			$hours_worked = str_replace(':','.',$hours_worked);
			
			
			$float_hours_worked = (float)$hours_worked;
			
        	$array['month_earning'] += $value->total_price;
        	$array['total_bookings'] += $value->Booking; 
            $array['hours_worked'] += $float_hours_worked;
			
        }

       
		if(!empty($earning)){
			$data['earning_details'] = $earning;
			
			$data['rate'] = $this->webservicedata->allrating($u_id);
			// Calculate total job worked.
				$condition = [
					'approved_by_userId' => $u_id,
					'status' => 'C'
				];
				$job_worked_count = $this->webservicedata->countBookingDetailsByCondition($condition);
				// $data['q'] = $this->db->last_query();
				//print_r($data['staff_detail']->total_job_worked);die();
				$array['total_job_worked '] = $job_worked_count;
				
				// Calculate total job assigned.
				$condition = [
					'assigned_to' => $u_id
				];
				$job_assigned_count = $this->webservicedata->countBookingDetailsByCondition($condition);
				$array['total_job_assigned '] = $job_assigned_count;

				// Calculate total hours worked.
				$condition = [
					'approved_by_userId' => $u_id,
					'status' => 'C'
				];
				$field = 'no_of_hours_needed';
				$hours_worked = $this->webservicedata->sumBookingDetailsByCondition($condition, $field);
				$array['total_hours_worked '] = $hours_worked;

				// Calculate total earned.
				$condition = [
					'approved_by_userId' => $u_id,
					'status' => 'C'
				];
				$field = 'booking_total_price';
				$total_earned = $this->webservicedata->sumBookingDetailsByCondition($condition, $field);
				$array['total_total_earned '] = number_format($total_earned, 2);
				$data['staff_detail']=(object)$array;
				
				//$withdrawl_data = $this->userdata->grabDetails(TABLE_WITHDRAWl, array('u_id' => $u_id));
				//$data['withdrawl_status'] = $withdrawl_data->status;
				
				$withdrawl_amt = $this->webservicedata->withdrawl_section($u_id);
       
		        $creditamt=$withdrawl_amt[0]->creditamount;
		        $debitamt=$withdrawl_amt[1]->debitamount;
	  
		         $total_amt=$creditamt-$debitamt;
				$data['amount'] = $total_amt; 
				
			$data['status_code'] = 0;
	       	$data['message'] = "Successful";
		}
		else {
	 		$data['status_code'] = 1;
	 		$data['message'] = "No Data Available";
	 	}
		jsonResponse($data);
		
	}
	public function withdrawl_section()
	{
		/*$msg = "Email test!";
        mail("debdyuti@technoexponent.com", "Test subject", $msg);
		die();*/
		$input_data = $this->input->post();
		$u_id = $input_data['u_id'];
		//$job_id = $input_data['job_id'];
        $settings=$this->defaultdata->grabSettingData();
        $comision247=$settings->percentage_amount;
		$withdrawl_amt = $this->webservicedata->withdrawl_section($u_id);
       
		$creditamt=$withdrawl_amt[0]->creditamount;
		$debitamt=$withdrawl_amt[1]->debitamount;
	  
		$total_amt=$creditamt-$debitamt;
		//dd($total_amt);die(); 
		if($total_amt == 0)
		{
			$transaction_data = $this->userdata->grabDetails(TABLE_TRANSACTION, array('user_id' => $u_id,'type' => 'D'));
			$data['payment_status'] = $transaction_data->payment_status; 
		    $data['amount'] = $total_amt; 
			$data['status_code'] = 0; 
			$data['message'] = " Cannot withdrawl ";
		}
		else
		{
		 $insert_data['user_id']=$u_id; 
		 //$insert_data['booking_detail_id']=$job_id;
		 $insert_data['transaction_type']='2';
		 $insert_data['type']='D';
		 $insert_data['amount']=$total_amt;
		 $insert_data['service_charges']=($comision247/100)*$total_amt;
		 $this->userdata->insertFunction(TABLE_TRANSACTION, $insert_data);
		 
		 $staff_details = $this->userdata->grabDetails(TABLE_USER, array('id' => $u_id));
		 
		 //$to = $parent_data->emailAddress;
			$to='admin@twebexponent.in';
            $type = 'withdrawal_admin_mail';
            $mail_data = (object)array('firstName' => $staff_details->firstName);
            $this->userdata->sendUserEmail($mail_data,$to,$type,$input_data['u_id']);
			
		 $transaction_data = $this->userdata->grabDetails(TABLE_TRANSACTION, array('user_id' => $u_id,'type' => 'D'));
		 $data['payment_status'] = $transaction_data->payment_status;
         $data['amount'] = $total_amt; 		 
		 $data['status_code'] = 1;   
	 	 $data['message'] = "Can withdrawl";
		}
		
		
			jsonResponse($data);
	}
	
	
	
	
}




/* End of file user.php */
/* Location: ./application/controllers/webservice.php */