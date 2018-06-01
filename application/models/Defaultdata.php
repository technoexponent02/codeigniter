<?php
class Defaultdata extends CI_Model {

	private $data=array();
	private $mydata=array();
	private $footerdata=array();
	private $headerdata=array();
	public $chat_data = array();
	function __construct()
	{
		parent::__construct();
		if(!$this->session->userdata('languageID'))
		{
			$this->session->set_userdata('languageID',1);
		}
	}

	public function getFrontendDefaultData()
	{
		$all_segment= $this->getUrlSegments();
		$this->mydata["tot_segments"]=$all_segment;
		$this->mydata['general_settings'] = $this->grabSettingData();
		$this->data=$this->mydata;
		$this->headerdata=$this->mydata;
		$this->footerdata=$this->mydata;
		$this->data["header_scripts"]=$this->load->view('includes/header_scripts',$this->mydata,true);
		$this->headerdata['all_langueges'] = $this->geAllLanguages();


		$this->data["header"]=$this->load->view('includes/header',$this->headerdata,true);
		$this->data["left_sidebar"]=$this->load->view('includes/account_left_panel',$this->mydata,true);
		$this->data["right_sidebar"]=$this->load->view('includes/account_right_panel',$this->mydata,true);
		$this->data["footer"]=$this->load->view('includes/footer',$this->footerdata,true);
		$this->data["footer_scripts"]=$this->load->view('includes/footer_scripts',$this->mydata,true);

		return $this->data;
	}

    public function getAdminDefaultData()
    {
        $all_segment= $this->getUrlSegments();
        $this->mydata["tot_segments"]=$all_segment;
        $this->mydata['general_settings'] = $this->grabSettingData();
        $this->data=$this->mydata;
        $this->headerdata=$this->mydata;
        $this->footerdata=$this->mydata;
        $this->data["header_scripts"]=$this->load->view('admin/includes/header_scripts',$this->mydata,true);
        $this->headerdata['all_langueges'] = $this->geAllLanguages();


        $this->data["header"]=$this->load->view('admin/includes/header',$this->headerdata,true);
        $this->data["left_sidebar"]=$this->load->view('admin/includes/left_sidebar',$this->mydata,true);
        $this->data["right_sidebar"]=$this->load->view('admin/includes/right_sidebar',$this->mydata,true);
        $this->data["footer"]=$this->load->view('admin/includes/footer',$this->footerdata,true);
        $this->data["footer_scripts"]=$this->load->view('admin/includes/footer_scripts',$this->mydata,true);

        return $this->data;
    }

	public function geAllLanguages()
	{
		$this->db->where('status','Y');
		$this->db->order_by('weight','ASC');
		return $this->db->get(TABLE_ALLLANGUAGE)->result();
	}
	public function grabLanguage($languageID)
	{
		$this->db->where('id',$languageID);
		return $this->db->get(TABLE_ALLLANGUAGE)->row();
	}
	public function getMaxTypeId($table)
	{
		$this->db->select_max('typeID');
		$arr = $this->db->get($table)->row();
		$new_typeID = 100;
		if($arr->typeID != '')
		{
			$new_typeID = $arr->typeID + 1;
		}
		return $new_typeID;
	}
	public function is_session_active()
	{
		//session_start();
		$sess_id = $this->session->userdata('usrid');
		//$sess_usr_type=$this->session->userdata('usrtype');
		if (isset($sess_id)==true && $sess_id!="")
			return 1;
		else
			return 0;
	}
	public function CheckFilename($page_filename)
	{
		$page_filename=str_replace(" ","-",$page_filename); //blank space is converted into blank
		$special_char=array("/",".htm",".","!","@","#","$","^","&","*","(",")","=","+","|","\\","{","}",":",";","'","<",">",",",".","?","\"","%");
		$page_filename=str_replace($special_char,"",$page_filename); // dot is converted into blank
		return strtolower($page_filename);      
	}
	public function getUrlSegments()
	{
		$all_segment=$this->uri->segment_array();
		if(sizeof($all_segment)==0)
		{
			$all_segment[1]=$this->router->class;
		}
		if(sizeof($all_segment)==1)
		{
			$all_segment[2]=$this->router->method;
		}
		return $all_segment;
	}
	
	// public function returnPartString($string,$length)
	// {
	// 	$string = strip_tags($string);
	// 	$s_length=strlen($string);
	// 	if($s_length > $length)
	// 	{
	// 		if(strpos($string," ",$length) !== false)
	// 		{
	// 			$string=substr($string,0,strpos($string," ",$length));
	// 		}
	// 		else
	// 		{
	// 			$string=substr($string,0,$length);
	// 		}
	// 	} 
	// 	else
	// 	{
	// 		$string=$string;
	// 	}
	// 	return stripslashes($string);
	// }
	public function grabSettingData(){
		$query = $this->db->get(TABLE_GENERAL_SETTINGS);
		return $query->row();
	}
	// public function getAllCountry()
	// {
	// 	$this->db->order_by('countryName','asc');
	// 	$query = $this->db->get(TABLE_COUNTRIES);
	// 	return $query->result();
	// }
	// public function grabCountry($c_cond = array())
	// {
	// 	if(count($c_cond) > 0)
	// 	{
	// 		$this->db->where($c_cond);
	// 		$query = $this->db->get(TABLE_COUNTRIES);
	// 		return $query->row();
	// 	}
	// 	else
	// 	{
	// 		return array();
	// 	}
	// }
	public function secureInput($data)
	{
		$return_data = array();
		foreach($data as $field => $inp_data)
		{
			//$return_data[$field]=$this->db->escape_str($inp_data);
			$return_data[$field] = $this->security->xss_clean(trim($inp_data));
		}
		return $return_data;
	}
	public function setLoginSession($user_data = array())
	{
		if(count($user_data) > 0)
		{
			$this->session->set_userdata('usrid',$user_data->id);
			$this->session->set_userdata('usremail',$user_data->emailAddress);
			$this->session->set_userdata('usrtype',$user_data->userType);
			// $this->session->set_userdata('nickname',$user_data->nickName);
			// $this->session->set_userdata('registertype',$user_data->registerType);
		}
	}
	public function unsetLoginSession()
	{
		$this->session->unset_userdata('usrid');
		$this->session->unset_userdata('usremail');
		$this->session->unset_userdata('usrtype');
		// $this->session->unset_userdata('usrname');
		// $this->session->unset_userdata('usr_name');
	}
	// public function getGplusLoginUrl()
	// {
	// 	require_once APPPATH .'libraries/google-api-php-client-master/src/Google/autoload.php';
	// 	$client_id = $this->config->item('client_id','googleplus');
	// 	$client_secret = $this->config->item('client_secret','googleplus');
	// 	$redirect_uri = $this->config->item('redirect_uri','googleplus');
	// 	$simple_api_key = $this->config->item('api_key','googleplus');
		
	// 	// Create Client Request to access Google API
	// 	$client = new Google_Client();
	// 	$client->setApplicationName("PHP Google OAuth Login Example");
	// 	$client->setClientId($client_id);
	// 	$client->setClientSecret($client_secret);
	// 	$client->setRedirectUri($redirect_uri);
	// 	$client->setDeveloperKey($simple_api_key);
	// 	$client->addScope("https://www.googleapis.com/auth/userinfo.email");
	// 	$authUrl = $client->createAuthUrl();
	// 	return $authUrl;
	// }
	
	// public function getGeneratedPassword( $length = 6 ) {
	// 	$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";
	// 	$password = substr( str_shuffle( $chars ), 0, $length );	
	// 	return $password;
	// }

	

	// public function get_results($table,$where="",$order="",$limit="",$start="")
	// {
	// 	if($where)
	// 	{
	// 		$this->db->where($where);
	// 	}
	// 	if($order)
	// 	{
	// 		foreach($order as $key=>$val){
	// 			$this->db->order_by($key,$val);
	// 		}
			
	// 	}
	// 	if($limit)
	// 	{
	// 		$this->db->limit($limit,$start);
	// 	}
	// 	return $this->db->get($table)->result();
		
	// }

	// public function get_single_row($table,$where="",$select="")
	// {
	// 	if($select!="")
	// 	{
	// 		$this->db->select($select);
	// 	}
	// 	if($where)
	// 	{
	// 		$this->db->where($where);
	// 	}				
	// 	return $this->db->get($table)->row();
		
	// }
	// public function getField($table,$where,$select)
	// {
	// 	$result = $this->db->select($select)->where($where)->get($table)->row();
	// 	if(count($result) > 0) {
	// 		return $result->$select;
	// 	} else {
	// 		return '';
	// 	}
				
	// }

	// public function count_record($table,$where="")
	// {
	// 	if($where){
	// 		$record = $this->db->where($where)->count_all_results($table);			
	// 	}else{
	// 		$record = $this->db->count_all($table);
	// 	}
	// 	return $record;
	// }

	// public function insert($tbl,$data = array())
	// {
	// 	if(count($user_data) > 0){
	// 		$this->db->insert($tbl, $data);
	// 		 return $this->db->insert_id();
	// 	}else{
	// 		return 0;
	// 	}
	// }
	// public function delete($tbl, $cond=array())
	// {
	// 	$this->db->delete($tbl, $cond); 
	// 	if ($this->db->count_all($tbl)==0)
	// 	{
	// 		$this->db->truncate($tbl); 	
	// 	}
	// }
	// public function update($tbl,$data,$condition)
	// {
	// 	return $this->db->update($tbl, $data, $condition);
	// }

	// function sendMail($to, $subject, $message, $from, $from_name, $cc=array(), $attach=array())
	// {
	// 	$this->load->library('email');
	// 	$config['mailtype'] = 'html';
	// 	/*$config['smtp_host'] = 'backpagepal.com';
	// 	$config['smtp_user'] = 'no-reply@backpagepal.com';
	// 	$config['smtp_pass'] = 'n9t?3B#UR2j';
	// 	$config['smtp_port'] = '25';*/
	// 	//$this->email->clear(TRUE);
 //        $this->email->initialize($config);
 //        $this->email->from($from, $from_name);
 //        $this->email->to($to);
 //        if(count($cc)>0)
 //        {
 //        	$this->email->cc($cc);
 //        }
 //        $this->email->subject($subject);
 //        $this->email->message($message);
 //        if(count($attach)>0)
 //        {
 //        	foreach($attach as $file)
 //        	{
 //        		$this->email->attach($file);
 //        	}
 //        }
 //        return $this->email->send();
	// }
	
	
}
?>