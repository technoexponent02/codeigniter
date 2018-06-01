<?php
class Userdata extends MainModel {

	private $data=array();
	function __construct()
	{
		parent::__construct();
		
		$this->table = TABLE_USER;
	}
	
	public function grabUserData($user_cond = array())
	{
		$return_data = array();
		if(count($user_cond) > 0)
		{
			$query = $this->db->get_where($this->table,$user_cond);
			$return_data = $query->row();
			// if(count($return_data) > 0)
			// {
			// 	if($return_data->userType == 2 && $return_data->country != 0)
			// 	{
			// 		$return_data->country_det = $this->defaultdata->grabCountry(array('idCountry' => $return_data->country));
			// 	}
			// }
		}
		return $return_data;
	}
	public function grabLoginUserData($where_str = '')
	{
		$return_data = array();
		if($where_str != '')
		{
			$this->db->where($where_str);
			$query = $this->db->get($this->table);
			$return_data = $query->row();
		}
		return $return_data;
	}
	public function getActivationEmailTemplate()
	{
		$query = $this->db->get(TABLE_EMAIL_ACTIVATION);
		$mail_data = $query->row();
		return $mail_data;
	}
	
	public function saveLoginLog($id)
	{
		$cond = array('uid' => $id);
		$usr_data = $this->db->get_where(TABLE_USERLOGIN,$cond)->row();
		if(count($usr_data) > 0)
		{
			$up_data = array('lastlogintime' => time(),'ipaddress' => $_SERVER["REMOTE_ADDR"],'login_status'=>1);
			$cond = array('uid' => $id);
			$this->db->where($cond);
			$this->db->update(TABLE_USERLOGIN, $up_data);
		}
		else
		{
			$in_data = array('uid' => $id,'lastlogintime' => time(),'ipaddress' => $_SERVER["REMOTE_ADDR"],'login_status'=>1);
			$this->db->insert(TABLE_USERLOGIN,$in_data);
		}
	}
	public function updateLoginUser($up_data=array())
	{
		$this->db->where('uid', $this->session->userdata('usrid'));
		$this->db->update(TABLE_USERLOGIN, $up_data);
	}
	public function getAllUsers($cond = array())
	{
		$return_data = array();
		if(count($cond) > 0)
		{
			$query = $this->db->get_where($this->table,$cond);
			$return_data = $query->result();
		}
		return $return_data;
	}
	public function updateUser($data,$condition)
	{
		$this->db->update($this->table, $data, $condition);
	}

	public function saveStaffDetail($data, $condition)
	{
		$this->db->update(TABLE_STAFF_DETAILS, $data, $condition);
		if ($this->db->affected_rows() < 1) {
			$data['user_id'] = $condition['user_id'];
			$this->db->insert(TABLE_STAFF_DETAILS, $data);
		}
	}

	public function getStaffDetail($user_id)
	{
		$this->db->where(['user_id' => $user_id]);
		$staff_detail = $this->db->get(TABLE_STAFF_DETAILS)->row();
		return (!empty($staff_detail) ? $staff_detail : []);
	}

	public function listOfUsers()
	{
		$query = $this->db->get(TABLE_USER);
		$return_data = $query->result();
		return $return_data;
	}

	public function getDetails($tableName,$cond = array())
	{
		return $this->db->select('*')->from($tableName)->where($cond)->get()->result();
	}

	public function getDetailsOrderby($tableName,$cond = array())
	{
		return $this->db->select('*')->from($tableName)->where($cond)->order_by('token_id','desc')->get()->result();
	}
	
	public function getLikeDetails($tableName,$cond = array())
	{
		return $this->db->select('*')->from($tableName)->like($cond)->get()->result();
	}
	
	public function grabDetails($tableName,$cond = array())
	{
		return $this->db->select('*')->from($tableName)->where($cond)->get()->row();
	}
	
	public function grabLikeDetails($tableName,$cond = array())
	{
		$sql= $this->db->select('*')->from($tableName)->like($cond)->get()->row();  
		return $sql;
	}
	
	public function insertFunction($tableName,$data = array())
	{
		$this->db->insert($tableName, $data);
		return $this->db->insert_id();
	}
	
	public function updateFunction($tableName,$data = array(),$cond = array())
	{
		$this->db->update($tableName,$data,$cond);
	}
	
	public function deleteFunction($tableName,$cond = array())
	{
		$this->db->delete($tableName,$cond);
	}


	public function fetchCityData($stateCode)
	{
		$this->db->where('state', $stateCode);
		$this->db->order_by('primary_city','ASC');
		$this->db->group_by('primary_city');
		return $this->db->get(TABLE_COMMERCIAL_ZIP)->result();
	}

	public function getStateName()
	{
		$this->db->order_by('title','ASC');
		return $this->db->get(TABLE_STATES_AMERICA)->result();
	}

	public function updateUserRating($user_rating, $user_id)
	{
		$user_id = (int) $user_id;

		$query = $this->db->select('id, user_rating')->get_where($this->table, ['id' => $user_id]);
		$user_data = $query->row();

		$this->db->where('id', $user_id);
		if ($user_data->user_rating > 0) {
			$this->db->set('user_rating', '(user_rating + ' . $user_rating . ')/2', FALSE);
		}
		else {
			$this->db->set('user_rating', $user_rating, FALSE);
		}

		$this->db->update(TABLE_USER);
	}
	public function listFunction($tablename)
    {
        $query = $this->db->get($tablename);
        $return_data = $query->result();
        return $return_data;
    }
	 public function sendUserEmail($user_data,$to,$type,$u_id)
	{
		//echo "ok"; //die();
		//$encydata = encrypt($user_data->id, SITE_ENCRYPTION_KEY);
	    $settings = $this->grabDetails(TABLE_GENERAL_SETTINGS,array('id' => 1));

        $mail_data = $this->setTable(TABLE_EMAIL_TEMPLATE)->fetchOne(array('type'=>$type)); 
        $this->unsetTable();
		
       
        $settings = $this->grabDetails(TABLE_GENERAL_SETTINGS,array('id' => 1));
        $mailcontent = htmlspecialchars_decode($mail_data->description);
        $subject = $mail_data->emailTitle;
          
        if ($type == 'withdrawal_admin_mail') {
			
			   
        	$old = array('{USER_NAME}');
        	$new =array($user_data->firstName);	
			
			$from_details = $this->userdata->grabDetails(TABLE_USER, array('id' => $u_id));
			//dd($from_details->emailAddress);die();
			$from=array('fromEmail'=>'admin@twebexponent.in','fromName'=>$from_details->firstName);
			/*foreach($from_details as $val){ 
			   
				$from=array('fromEmail'=>$val->emailAddress,'fromName'=>$val->firstName);
			}*/
			
        }
		
        		
        else{
        	echo "Error";
			die();        
        }
        $mailcontent = str_replace($old, $new, $mailcontent);       
        $message=commonMailContent($mailcontent);
        

        $mail =$this->mainmodel->sendMail($from,$to,$subject,$message);
        return $mail;
	}

	public function getDetailsWithdraw($cond = array())
	{
		//return $this->db->select('*')->from($tableName)->where($cond)->get()->result();
		
		
		$select = 't.*,u.id as uid ,u.firstName,u.lastName';
		$this->db->select($select);
		$this->db->from(TABLE_TRANSACTION.' t');
		$this->db->join(TABLE_USER.' u', 't.user_id = u.id', 'LEFT');
		$this->db->where($cond);
		$query = $this->db->get();
		
		//echo $this->db->last_query(); exit;
		$result = $query->result();
		return $result;
	}
	
}
?>