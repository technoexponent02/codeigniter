<?php
class Webservicedata extends MainModel {

	private $data=array();
	function __construct()
	{
		parent::__construct();
		$this->table = TABLE_USER;

	}
    /*public function getListedPendingJobs($u_id, $status, $pagination)
	{
		$now = date('Y-m-d H:i');
		$this->db->select('bd.*, IF("' . $now . '" > DATE_FORMAT(`end_time` , "%Y-%m-%d %H:%i"), "Y", "N") as is_expired, u.id as userid,firstName,lastName,emailAddress,userName,userType,profile_picture,profile_picture_org,phone,description,u.status, r.rating as user_rating');
      	$this->db->from(TABLE_BOOKING_DETAILS.' as bd');
		$this->db->join(TABLE_USER.' as u' , 'bd.approved_by_userId = u.id','left');
		$this->db->join(TABLE_USER_RATING.' as r' , 'bd.id = r.job_id','left');
		$this->db->where('bd.userId', $u_id);
		if ($status === 'P') {
			$this->db->where("(bd.status = 'P' OR bd.status = 'AS')");
		}
		else {
			$this->db->where('bd.status', $status);
		}
		
		$this->db->order_by('bd.start_time_timestamp','DESC');
		$this->db->limit($pagination['per_page'], $pagination['offset']);

		$query = $this->db->get();
		$result = $query->result();

		// dd($result);

		 echo $this->db->last_query();exit();


	

		foreach ($result as $key => $value) {
			$value->child_details = $this->getChildData($value->id);
		}

		return $result;
	}*/
	public function getListedPendingJobs($u_id, $status, $pagination)
	{
		$current_date = date('Y-m-d',time());
		//echo $current_date;
		//die();
		$result=array();
		
		$now = date('Y-m-d H:i');
		$this->db->select('bd.*, IF("' . $now . '" > DATE_FORMAT(`end_time` , "%Y-%m-%d %H:%i"), "Y", "N") as is_expired, u.id as userid,firstName,lastName,emailAddress,userName,userType,profile_picture,profile_picture_org,phone,description,u.status, r.rating as user_rating');
      	$this->db->from(TABLE_BOOKING_DETAILS.' as bd');
		$this->db->join(TABLE_USER.' as u' , 'bd.approved_by_userId = u.id','left');
		$this->db->join(TABLE_USER_RATING.' as r' , 'bd.id = r.job_id','left');
		$this->db->where('bd.userId', $u_id);
		if ($status === 'P') {
			$this->db->where("(bd.status = 'P' OR bd.status = 'AS')");
		}
		else{
			$this->db->where('DATE(`start_time`) >= ', $current_date);
			$this->db->where('bd.status', $status);
		}
		
		$this->db->order_by('bd.start_time_timestamp','ASC');
		$this->db->limit($pagination['per_page'], $pagination['offset']);

		$query = $this->db->get();
		$result1 = $query->result();
		//------------------------------------------------------------------------//
		$now = date('Y-m-d H:i');
		$this->db->select('bd.*, IF("' . $now . '" > DATE_FORMAT(`end_time` , "%Y-%m-%d %H:%i"), "Y", "N") as is_expired, u.id as userid,firstName,lastName,emailAddress,userName,userType,profile_picture,profile_picture_org,phone,description,u.status, r.rating as user_rating');
      	$this->db->from(TABLE_BOOKING_DETAILS.' as bd');
		$this->db->join(TABLE_USER.' as u' , 'bd.approved_by_userId = u.id','left');
		$this->db->join(TABLE_USER_RATING.' as r' , 'bd.id = r.job_id','left');
		$this->db->where('bd.userId', $u_id);
	
			$this->db->where('DATE(`start_time`) <= ', $current_date);
			$this->db->where('bd.status', 'C');
		
		
		$this->db->order_by('bd.start_time_timestamp','ASC');
		$this->db->limit($pagination['per_page'], $pagination['offset']);

		$query = $this->db->get();
		$result2 = $query->result();

		if ($status === 'P') {
		$result=array($result1);
		}
		elseif($status === 'AP') {
		$result=array($result1);
		}
		elseif ($status === 'C') {
			$result=array($result1,$result2);
			//$result=array($result2);
		}
		
		// dd($result);

		// echo $this->db->last_query();exit();


		

		foreach ($result as $key => $value) {
			foreach($value as $key1=>$value1){
				
			$value1->child_details = $this->getChildData($value1->id);
		  }
		}

		return $result;
	}
	public function getListedPendingJobsCount($u_id, $status)
	{
		$now = date('Y-m-d H:i');
		$this->db->select('bd.*, IF("' . $now . '" > DATE_FORMAT(`end_time` , "%Y-%m-%d %H:%i"), "Y", "N") as is_expired, u.id as userid,firstName,lastName,emailAddress,userName,userType,profile_picture,profile_picture_org,phone,description,u.status, r.rating as user_rating');
      	$this->db->from(TABLE_BOOKING_DETAILS.' as bd');
		$this->db->join(TABLE_USER.' as u' , 'bd.approved_by_userId = u.id','left');
		$this->db->join(TABLE_USER_RATING.' as r' , 'bd.id = r.job_id','left');
		$this->db->where('bd.userId', $u_id);
		if ($status === 'P') {
			$this->db->where("(bd.status = 'P' OR bd.status = 'AS')");
		}
		else {
			$this->db->where('bd.status', $status);
		}
		
		$this->db->order_by('bd.start_time_timestamp','DESC');
		//$this->db->limit($pagination['per_page'], $pagination['offset']);

		$query = $this->db->get();
		$result = $query->result();

		// dd($result);

		 //echo $this->db->last_query();exit();


		/*$this->db->where('userId', $u_id);
		$this->db->where('status', $status);
		$this->db->order_by('posted_time','DESC');
		$sql = $this->db->get(TABLE_BOOKING_DETAILS)->result();
*/
		// foreach ($result as $key => $value) {
		// 	$value->child_details = $this->getChildData($value->id);
		// }

		return count($result);
	}
	public function getUserFeedbackCount($u_id)
	{
		$this->db->where('u_id', $u_id);
		$count = $this->db->get(TABLE_USER_RATING)->result();
		return count($count);
	}

	public function getDetailsOfaJob($job_id, $u_id=NULL)
	{
		$this->db->where('id', $job_id);
		// $this->db->where('status', 'P');
		$this->db->order_by('posted_time','DESC');
		$booking_detail = $this->db->get(TABLE_BOOKING_DETAILS)->row();

		if($booking_detail) {
			$booking_detail->child_details = $this->getChildData($booking_detail->id);
			$booking_detail->userdetails = $this->userdetailshere($booking_detail->approved_by_userId);

			if($u_id)
			{
				$u_id = $u_id;
			}
			else
			{
				$u_id = $booking_detail->userId;
			}


			$booking_detail->rating_details = $this->getUserRatingRespectiveBooking($booking_detail->id,$booking_detail->approved_by_userId,$booking_detail->userId,$u_id);
		}
		else {
			$booking_detail = array();
		}
		
		return $booking_detail;

	}

	public function userdetailshere($u_id)
	{
		$this->db->where('id', $u_id);
		return $this->db->get(TABLE_USER)->row();
	}

	public function getChildData($booking_id)
	{
		$this->db->where('booking_id', $booking_id);
		return $this->db->get(TABLE_BOOKING_CHILD_DETAILS)->result();
	}

	public function getUserRating($job_id = null)
	{
		if ($job_id === null) {
			return array();
		}

		$this->db->where('job_id', $job_id);
		return $this->db->get(TABLE_USER_RATING)->result();
	}

	public function getUserRatingRespectiveBooking($job_id = null,$approved_by_userId,$bookinguser_id,$u_id)
	{
		$rating_array = array();

		if ($job_id === null) {
			return array();
		}

		if($approved_by_userId!=$u_id)
		{
			$staff_id = $approved_by_userId;
		}
		else{
			$staff_id = $bookinguser_id;
		}	

		$this->db->where('job_id', $job_id);
		$this->db->where('staff_id', $staff_id);
		$this->db->where('u_id', $u_id);
		$rating_array= $this->db->get(TABLE_USER_RATING)->result();

		return $rating_array;
	}

	public function getHighestRatingUser($lat, $lng)
	{
		$radius = 30;	
		$sql = $this->db->query("SELECT `id`, `user_rating` , ( 6371 * acos( cos( radians({$lat}) ) * cos( radians( `latitude` ) ) * cos( radians( `longitude` ) - radians({$lng}) ) + sin( radians({$lat}) ) * sin( radians( `latitude` ) ) ) ) AS distance FROM ".TABLE_USER." WHERE `status`='Y' AND `userType`='S' HAVING distance <= {$radius}  ORDER BY `user_rating` DESC ");
		return $sql->result();

	}

	public function getBookedUser($start_time, $end_time)
	{
		$sql = $this->db->query("SELECT `approved_by_userId` FROM ".TABLE_BOOKING_DETAILS." WHERE `actual_start_time` < '".$end_time."' AND `actual_end_time` > '".$start_time."' AND `status`='A'");
		return $sql->result();
	}

	public function countBookingDetailsByCondition($condition, $field = 'id')
	{
		$this->db->select("COUNT($field) as total");
		$result = $this->db->where($condition)->get(TABLE_BOOKING_DETAILS)->row();
		//dd($this->db->last_query());die;
		//dd((int)$result->total);die;
		return (int) $result->total;
		
	}

	public function sumBookingDetailsByCondition($condition, $field)
	{
		$this->db->select("SUM($field) as hours_needed");
		$result = $this->db->where($condition)->get(TABLE_BOOKING_DETAILS)->row();
		return (int) $result->hours_needed;
	}

	public function getCurrentlyPendingJob($pagination)
	{
		$this->db->select('BD.*, U.id As com_user_id, U.firstName, U.lastName');
		$this->db->from(TABLE_BOOKING_DETAILS . ' AS BD');
		$this->db->join(TABLE_USER.' as U' , 'BD.userId = U.id', 'left');
		$this->db->where("`actual_start_time` > DATE_SUB(NOW(), INTERVAL -60 MINUTE) AND (BD.`status`='P' OR BD.`status`='AS')");
		
		$this->db->order_by('BD.id','DESC');
		$this->db->limit($pagination['per_page'], $pagination['offset']);

		$query = $this->db->get();
		$booking_details = $query->result();
//dd($this->db->last_query());die(); 


		foreach ($booking_details as $key => $detail) {
			$detail->user_details = [
				'id' => $detail->com_user_id,
				'firstName' => $detail->firstName,
				'lastName' => $detail->lastName,
				'user_rating' => $this->getRatingOfUser($detail->com_user_id)
			];
			unset($detail->com_user_id);
			unset($detail->firstName);
			unset($detail->lastName);
			$detail->child_details = $this->getChildData($detail->id);
		}

		return $booking_details;
	}

	/*public function getListOfAllJobs($u_id, $pagination = null)
	{
		$this->db->select('bd.*,u.id as userid,firstName,lastName,emailAddress,userName,userType,profile_picture,profile_picture_org,phone,description,u.status');
      	$this->db->from(TABLE_BOOKING_DETAILS.' as bd');
		$this->db->join(TABLE_USER.' as u' , 'bd.userId = u.id','left');
		$this->db->where('bd.approved_by_userId', $u_id);
		$this->db->where('bd.status', 'AP');
		$this->db->order_by('bd.start_time_timestamp','DESC');
		//$this->db->order_by('bd.posted_time','DESC');
		if ($pagination) {
			$this->db->limit($pagination['per_page'], $pagination['offset']);
		}		

		$query = $this->db->get();
		$sql = $query->result();

		// dd($this->db->last_query());

		foreach ($sql as $key => $value) 
		{
			$rowcount = 0;
			$re_rating = array();
			
			$value->child_details = $this->getChildData($value->id);
			$value->user_name = $this->getUserName($value->userId);
			if (time() >= $value->end_time_timestamp) 
			{
				$value->is_expired = 1;
			}
			else
			{
				$value->is_expired = 0;
			}

			$jobowner_userid = $value->userId;
			$job_id = $value->id;

			$this->db->select('rating');
			$this->db->from("com_user_rating"); 
			$this->db->where('staff_id', $jobowner_userid);
			$this->db->where('u_id', $u_id);
			$this->db->where('job_id', $job_id);
			$query = $this->db->get();
			$rowcount = $query->num_rows();

			$re_rating=$query->result();
			
			//echo "<pre>";
			//print_r($re_rating);

			//echo $this->db->last_query();
			//die;
			//echo "hi  ...".count($re_rating)."<br>";
			//$value->user_rating = 0;

			if(count($re_rating)>0) {
				//die;
				$value->user_rating = $re_rating[0]->rating;
			}
			else
			{
				$value->user_rating = 0;
			}

			


		}
		return $sql;
	}*/
	
	public function getListOfAllJobs($u_id,$month,$year,$pagination = null)
	{

		$current_month = date('m',time());
        $nmonth = date("m", strtotime($month));
        //echo date("Y-m-d", strtotime($month)); 
        //die();
		$sql=array();
		
       if($current_month == $nmonth)
       {

       	$this->db->select('bd.*,u.id as userid,firstName,lastName,emailAddress,userName,userType,profile_picture,profile_picture_org,phone,description,u.status');
      	$this->db->from(TABLE_BOOKING_DETAILS.' as bd');
		$this->db->join(TABLE_USER.' as u' , 'bd.userId = u.id','left');
		$this->db->where('bd.approved_by_userId', $u_id);
		$this->db->where('bd.status', 'AP');
		$this->db->where('MONTHNAME(`start_time`)', $month);
		$this->db->where('DATE(`start_time`) >= ', date("Y-m-d", strtotime($month)));//DATE("2017-06-15 09:34:21")
		$this->db->where('YEAR(`start_time`)', $year);
		$this->db->order_by('bd.start_time_timestamp','ASC');
		//$this->db->order_by('bd.posted_time','DESC');
		if ($pagination) {
			$this->db->limit($pagination['per_page'], $pagination['offset']);
		}		

		$query = $this->db->get();
		$sql1 = $query->result();
		
		$this->db->select('bd.*,u.id as userid,firstName,lastName,emailAddress,userName,userType,profile_picture,profile_picture_org,phone,description,u.status');
      	$this->db->from(TABLE_BOOKING_DETAILS.' as bd');
		$this->db->join(TABLE_USER.' as u' , 'bd.userId = u.id','left');
		$this->db->where('bd.approved_by_userId', $u_id);
		$this->db->where('bd.status', 'AP');
		$this->db->where('MONTHNAME(`start_time`)', $month);
		$this->db->where('DATE(`start_time`) < ', date("Y-m-d", strtotime($month)));
		$this->db->where('YEAR(`start_time`)', $year);
		$this->db->order_by('bd.start_time_timestamp','ASC');
		//$this->db->order_by('bd.posted_time','DESC');
		if ($pagination) {
			$this->db->limit($pagination['per_page'], $pagination['offset']);
		}		

		$query = $this->db->get();
		$sql2 = $query->result();
        
		$sql=array($sql1,$sql2);
       }
       else
       {
       	$this->db->select('bd.*,u.id as userid,firstName,lastName,emailAddress,userName,userType,profile_picture,profile_picture_org,phone,description,u.status');
      	$this->db->from(TABLE_BOOKING_DETAILS.' as bd');
		$this->db->join(TABLE_USER.' as u' , 'bd.userId = u.id','left');
		$this->db->where('bd.approved_by_userId', $u_id);
		$this->db->where('bd.status', 'AP');
		$this->db->where('MONTHNAME(`start_time`)', $month);
		$this->db->where('YEAR(`start_time`)', $year);
		//$this->db->order_by('bd.start_time_timestamp','DESC');
		$this->db->order_by('bd.posted_time','DESC');
		if ($pagination) {
			$this->db->limit($pagination['per_page'], $pagination['offset']);
		}		

		$query = $this->db->get();
		$sql = $query->result();
		$sql=array($sql);
       }

		// dd($this->db->last_query());die();
        foreach ($sql as $key => $value) 
		{
			
			foreach($value as $key1=>$value1){
				$value1->child_details = $this->getChildData($value1->id);
				$value1->user_details = $this->getUserDetails($value1->userId);
				//debug($value1); die();
				if (time() >= $value1->end_time_timestamp) 
			    {
				     $value1->is_expired = 1;
			    }
			    else
			    {
				     $value1->is_expired = 0;
			    }

			   $jobowner_userid = $value1->userId;
			   $job_id = $value1->id;

			   $this->db->select('rating');
			   $this->db->from("com_user_rating"); 
			   $this->db->where('staff_id', $jobowner_userid);
			   $this->db->where('u_id', $u_id);
			   $this->db->where('job_id', $job_id);
			   $query = $this->db->get();
			   $rowcount = $query->num_rows();

			   $re_rating=$query->result();
			
			   if(count($re_rating)>0) {
				//die;
				  $value1->user_rating = $re_rating[0]->rating;
			    }
			   else
			    {
				  $value1->user_rating = 0;
			    }
			
			}
			//die();
			/*$rowcount = 0;
			$re_rating = array();
			
			$indexPos=count($value);
			$value->$indexPos['child_details'] = $this->getChildData($value->id);
			$indexPos=count($value);
			$value->$indexPos['user_details'] = $this->getUserDetails($value->userId);
			//$value->user_details = $this->userdata->getDetails(TABLE_USER,$value->userId);
			debug($value);
			if (time() >= $value->end_time_timestamp) 
			{
				$value->is_expired = 1;
			}
			else
			{
				$value->is_expired = 0;
			}

			$jobowner_userid = $value->userId;
			$job_id = $value->id;

			$this->db->select('rating');
			$this->db->from("com_user_rating"); 
			$this->db->where('staff_id', $jobowner_userid);
			$this->db->where('u_id', $u_id);
			$this->db->where('job_id', $job_id);
			$query = $this->db->get();
			$rowcount = $query->num_rows();

			$re_rating=$query->result();
			
			//echo "<pre>";
			//print_r($re_rating);

			//echo $this->db->last_query();
			//die;
			//echo "hi  ...".count($re_rating)."<br>";
			//$value->user_rating = 0;

			if(count($re_rating)>0) {
				//die;
				$value->user_rating = $re_rating[0]->rating;
			}
			else
			{
				$value->user_rating = 0;
			}*/

			


		}
		
		//exit;
		return $sql;
	}


	public function getChatList($u_id)
	{
		$this->db->select('bc.*, bd.id as job_id, bd.*, u.id as user_id,firstName,lastName,emailAddress,userName,userType,profile_picture,profile_picture_org,phone,description,u.status,user_rating');
      	$this->db->from(TABLE_BOOKING_CHAT_LIST.' as bc');
		$this->db->join(TABLE_USER.' as u' , 'bc.receiver_id = u.id','left');
		$this->db->join(TABLE_BOOKING_DETAILS.' as bd' , 'bd.id = bc.job_id','left');
		$this->db->where('bc.sender_id', $u_id);
		//$this->db->where('bd.status !=', 'CC');
		$this->db->where('bd.status =', 'AP');
		$this->db->order_by('bc.id','DESC');
		$query = $this->db->get();
		$sql = $query->result();

		foreach ($sql as $key => $value) 
		{
			$value->child_details = $this->getChildData($value->job_id);
			// $value->user_name = $this->getUserName($value->userId);
		}
		//dd($this->db->last_query());
		return $sql;
	}
	/*public function getChatListRemove($expiry_day)
	{
	
		$now = date('Y-m-d H:i');
	    $this->db->where("'$now' > DATE_FORMAT( DATE_ADD( end_time, INTERVAL " . $expiry_day . " DAY ),  '%Y-%m-%d %H:%i' ) AND (`status`='AS')");
	    $this->db->update(TABLE_BOOKING_DETAILS, ['status' => 'C']);
        //dd($this->db->last_query());
	}*/
	
	public function getChatListRemove($expiry_day)
	{
	
		$now = date('Y-m-d H:i');
	    $this->db->where("'$now' > DATE_FORMAT( DATE_ADD( end_time, INTERVAL " . $expiry_day . " DAY ),  '%Y-%m-%d %H:%i' ) AND (`status`='AS')");
	    $this->db->update(TABLE_BOOKING_DETAILS, ['status' => 'C']);
        //dd($this->db->last_query());

        $this->db->select('*');
		$this->db->from(TABLE_BOOKING_DETAILS);
        $this->db->where("'$now' > DATE_FORMAT( DATE_ADD( end_time, INTERVAL " . $expiry_day . " DAY ),  '%Y-%m-%d %H:%i' ) AND (`status`='C') AND (`assigned_to` IS NOT NULL ) ");
		$query = $this->db->get();
		$updateddata = $query->result();
		//dd($this->db->last_query());
		//dd($updateddata);die();
		
        foreach ($updateddata as $value) {
        	$admin_amt = $this->webservicedata->adminAmount($value->userId);
            //dd($admin_amt->amount);
        	//$transaction_data = $this->userdata->grabDetails(TABLE_TRANSACTION, array('user_id' => $value->assigned_to,'transaction_type' => 2,'type' => 'D'));
			//if(count($transaction_data) <= 0){

			$insert_data['user_id']=$value->assigned_to; 
			//$insert_data['booking_detail_id']=$value->id;
			$insert_data['transaction_type']='2';
			$insert_data['type']='C';
			$insert_data['amount']=$value->booking_total_price;
			$insert_data['payment_status']='Y';
			//dd($insert_data);
			$this->userdata->insertFunction(TABLE_TRANSACTION, $insert_data); 
			
           //}  
       }
	}

	public function getUserName($id)
	{
		$this->db->select('firstName');
		$this->db->select('lastName');
		$this->db->where('id', $id);

		return $this->db->get(TABLE_USER)->row();
	}
	
	public function getUserDetails($id)
	{
		$this->db->select('*');
		$this->db->where('id', $id);

		return $this->db->get(TABLE_USER)->row();
	}

   public function getSpecificUserRating($id)
	{
		$this->db->select('user_rating');
		$this->db->where('id', $id);

		return $this->db->get(TABLE_USER)->row();
	}

	public function fetchJobByDate($date, $u_id)
	{
		/*$date = strtotime($date);
		$endtime = $date+24*3600;

		$this->db->where('start_time_timestamp >=', $date);
		$this->db->where('start_time_timestamp <', $endtime);*/

		$date = date("Y-m-d", strtotime($date));
		$this->db->where("start_time BETWEEN '$date 00:00:00' AND '$date 23:59:59'");

		$this->db->where('approved_by_userId', $u_id);
		$this->db->where('status', 'AP');

		$query = $this->db->get(TABLE_BOOKING_DETAILS);

		$sql = $query->result();

		foreach ($sql as $key => $value) {
			$value->child_details = $this->getChildData($value->id);
			$value->user_name = $this->getUserName($value->userId);


			
			if (time() >= $value->end_time_timestamp) 
			{
				$value->is_expired = 1;
			}
			else
			{
				$value->is_expired = 0;
			}

			$jobowner_userid = $value->userId;
			$job_id = $value->id;

			$this->db->select('rating');
			$this->db->from("com_user_rating"); 
			$this->db->where('staff_id', $jobowner_userid);
			$this->db->where('u_id', $u_id);
			$this->db->where('job_id', $job_id);
			$query = $this->db->get();
			
			$re_rating=$query->result();
			
		
			if(count($re_rating)>0) {
				//die;
				$value->user_rating = $re_rating[0]->rating;
			}
			else
			{
				$value->user_rating = 0;
			}




		}
		return $sql;
	}


	public function filterJobList($input_data)
	{
		// $date = !empty($input_data['date']) ? strtotime($input_data['date']) : null;
		$date = !empty($input_data['date']) ? date("Y-m-d", strtotime($input_data['date'])) : null;
		//echo $date; die();
		//$date = !empty($input_data['date']) ? date("Y-m-d", strtotime($input_data['date'])) : date("Y-m-d", time());
		$start_time = !empty($input_data['startTime']) ? $input_data['startTime']: null;
		$end_time = !empty($input_data['endTime']) ? $input_data['endTime'] : null;

		$distance = !empty($input_data['distance']) ? $input_data['distance'] : null;
		$lat = !empty($input_data['lat']) ? $input_data['lat'] : null;
		$lon = !empty($input_data['lon']) ? $input_data['lon'] : null;

		$no_of_child = !empty($input_data['noOfChild']) ? $input_data['noOfChild'] : null;
		$job_length_min = !empty($input_data['jobLengthMin']) ? $input_data['jobLengthMin'] : null;
		$job_length_max = !empty($input_data['jobLengthMax']) ? $input_data['jobLengthMax'] : null;

		$minPrice = !empty($input_data['minPrice']) ? $input_data['minPrice'] : null;
		$maxPrice = !empty($input_data['maxPrice']) ? $input_data['maxPrice'] : null;
		
		$sortBy = 'DESC';
		if (empty($input_data['sortBy']) || $input_data['sortBy'] == 'Newest') {
			$sortBy = 'DESC';
		}
		if ($input_data['sortBy'] == 'Oldest') {
			$sortBy = 'ASC';
		}

		$select_sql = '*';
		if (!empty($lat) && !empty($lon) && !empty($distance)) {
			$select_sql .= ", ROUND( ( 6371 * acos( cos( radians({$lat}) ) * cos( radians( `latitude` ) ) * cos( radians( `longitude` ) - radians({$lon}) ) + sin( radians({$lat}) ) * sin( radians( `latitude` ) ) ) ), 2) AS distance";
		}

		$this->db->select($select_sql);
		//echo $start_time
		/*if ($date != NULL || $date != 0) {
			//$this->db->where('date >=', $date);
			$this->db->where('DATE(`start_time`) >=', $date);
		}*/
		if ($start_time != null)
		{
			$start_dt= date("Y-m-d", time())." $start_time";
		//echo $start_dt; die();
			$this->db->where("`actual_start_time` > DATE_SUB('$start_dt', INTERVAL -60 MINUTE) AND TIME(`start_time`) >= '$start_time' AND  (`status`='P' OR `status`='AS')");
		}
		else
		{
			$this->db->where("`actual_start_time` > DATE_SUB(NOW(), INTERVAL -60 MINUTE) AND (`status`='P' OR `status`='AS')");
		}
		/*if ($start_time != null)
		{
		     $this->db->where(" TIME(start_time) >=",$start_time);
		}*/
		// If date is present in params.
		//if ($start_time != null) {
			/*$query_start_date = $date;
			if ($start_time !== null) {
				$query_start_date = $date . ' ' . $start_time;
			}*/

			// Initialize Default End time
			/*$default_end_time = '23:59:59';
			if ($end_time !== null) {
				$query_end_date = $date . ' ' . $end_time;
			}
			else {
				$query_end_date = $date . ' ' . $default_end_time;
			}*/
            // echo $start_time;die();
			//$this->db->where("start_time BETWEEN '$query_start_date' AND '$query_end_date'");
            // $this->db->where(" TIME(start_time) >=",$start_time);
				
			/*$this->db->where('start_time_timestamp >=', $date);
			$this->db->where('start_time_timestamp <', ($date + 24 * 3600));*/
		//}
		//else 
			if ($end_time != null) {
			/*$query_start_date = $date;
			if ($start_time !== null) {
				$query_start_date = $date . ' ' . $start_time;
			}

			// Initialize Default End time
			$default_end_time = '23:59:59';
			if ($end_time !== null) {
				$query_end_date = $date . ' ' . $end_time;
			}
			else {
				$query_end_date = $date . ' ' . $default_end_time;
			}*/
            // echo $start_time;die();
			//$this->db->where("start_time BETWEEN '$query_start_date' AND '$query_end_date'");
                $this->db->where(" TIME(end_time) <=",$end_time);
				
			/*$this->db->where('start_time_timestamp >=', $date);
			$this->db->where('start_time_timestamp <', ($date + 24 * 3600));*/
		}
		if ($date != NULL || $date != 0) {
			//$this->db->where('no_of_child', $no_of_child);
			 $this->db->where(" DATE(start_time) ", $date);
		}
		if ($no_of_child != NULL || $no_of_child != 0) {
			
			$start_dt= date("Y-m-d", time())." $start_time";
			$this->db->where("`actual_start_time` > DATE_SUB('$start_dt', INTERVAL -60 MINUTE) AND TIME(`start_time`) >= '$start_time' AND  (`status`='P' OR `status`='AS')");
			$this->db->where('no_of_child', $no_of_child);
		}
		if ($job_length_min != NULL || $job_length_min != 0) {
			$this->db->where('no_of_hours_needed >=', $job_length_min);
		}
		if ($job_length_max != NULL || $job_length_max != 0) {
			$this->db->where('no_of_hours_needed <=', $job_length_max);
		}
		// Filter by total price.
		if ($minPrice > 0) {
			$this->db->where('total_price >=', $minPrice);
		}
		if ($maxPrice > 0) {
			$this->db->where('total_price <=', $maxPrice);
		}

		if ($sortBy != NULL || $sortBy != 0) {
			$this->db->order_by('posted_time', $sortBy);
		}

		if (!empty($lat) && !empty($lon) && !empty($distance)) {
			$this->db->having('distance <=' . $distance, NULL, FALSE);
		}

		$sql = $this->db->get(TABLE_BOOKING_DETAILS)->result();

		 //dd($this->db->last_query());

		foreach ($sql as $key => $value) {
			$value->child_details = $this->getChildData($value->id);
			$value->user_name = $this->getUserName($value->userId);
			$value->user_rating = $this->getSpecificUserRating($value->userId);
			
		}
		return $sql;
	}


	public function allrating($u_id)
	{
		$return_data = array();
		
		$this->db->where('staff_id', $u_id);
		$query = $this->db->get('com_user_rating');
		$result = $query->result();
		$return_data['allcomment'] = $result;

		$this->db->where('staff_id', $u_id);
		$this->db->select_avg('rating');
		$query = $this->db->get('com_user_rating');
		$result = $query->row();

		$return_data['avg_rating'] = $result->rating;

		return $return_data;
	}


	public function getRatingOfUser($u_id)
	{
		$return_data = array();
		
		// $this->db->where('u_id', $u_id);
		// $query = $this->db->get('com_user_rating');
		// $result = $query->result();
		// $return_data['allcomment'] = $result;

		$this->db->where('u_id', $u_id);
		$this->db->select_avg('rating');
		$query = $this->db->get('com_user_rating');
		$result = $query->row();

		// $return_data['avg_rating'] = $result->rating;

		return $result->rating;
	}
	

	public function finishJob($job_id)
	{
		// $time = time();
		// Update booking details.
		$this->db->where('id', (int) $job_id)
			->update(TABLE_BOOKING_DETAILS, [
				// 'end_time_timestamp' => $time,
				'status' => 'C'
			]);
	}
	
	public function adminAmount($u_id)
	{
         $this->db->select('SUM(`amount`) AS amount');
         $this->db->where('type', 'D');
         $this->db->where('user_id', $u_id);
         $total_amt = $this->db->get(TABLE_TRANSACTION);
         $result = $total_amt->row();
         // dd($this->db->last_query()); die();
         return $result;
 	}

	/**
	 * Get jobs which are about to finish(in current minute)
	 */
	public function getJobsFinishingNow($now)
	{
		$this->db->select('id, userId, job_details');
		$this->db->where("DATE_FORMAT(`end_time` , '%Y-%m-%d %H:%i') = '$now' AND `finish_notification_sent` = 'N'");
		$jobs_finishing_now = $this->db->get(TABLE_BOOKING_DETAILS)->result();
		
		// Mark these jobs as notification sent.
		$this->db->where("DATE_FORMAT(`end_time` , '%Y-%m-%d %H:%i') = '$now'")
			->update(TABLE_BOOKING_DETAILS, [
				'finish_notification_sent' => 'Y',
				'status' => 'C'
			]);

		return $jobs_finishing_now;
	}

	public function changeJobToExpired($expiry_day)
	{
		$now = date('Y-m-d H:i');
		$this->db->where("'$now' > DATE_FORMAT( DATE_ADD( start_time, INTERVAL " . $expiry_day . " DAY ) ,  '%Y-%m-%d %H:%i' )")
			->where("(status = 'P' OR status = 'AS')")
			->update(TABLE_BOOKING_DETAILS, [
				'status' => 'EX'
			]);
	}

	public function extendJob($job_id, $extend_hour)
	{
		$extend_hour_timestamp = $extend_hour * 3600;

		// Fetch job details.
		// $booking_details = $this->webservicedata->getDetailsOfaJob($job_id);
		$rate_per_hour = $this->findHourlyRate($extend_hour);
		$amount = number_format($rate_per_hour * $extend_hour, 2);

		// Update booking details.
		$this->db->where('id', (int) $job_id)
			->set('end_time', 'DATE_ADD( end_time, INTERVAL ' . $extend_hour . ' HOUR )', FALSE)
			->set('end_time_timestamp', 'end_time_timestamp + ' . $extend_hour_timestamp, FALSE)
			->set('no_of_hours_needed', 'no_of_hours_needed + ' . $extend_hour, FALSE)
			->set('booking_total_price', 'booking_total_price + ' . $amount, FALSE)
			->update(TABLE_BOOKING_DETAILS);

		// Create new extend booking entry.
		$extend_booking_data = [
			'booking_detail_id' => $job_id,
			'hour' => $extend_hour,
			'amount' => $amount
		];
		$this->db->insert(TABLE_EXTEND_BOOKING, $extend_booking_data);
	}

	public function changeJobStatus($job_id, $status)
	{
		$this->db->where('id', (int) $job_id)
			->update(TABLE_BOOKING_DETAILS, ['status' => $status]);
	}

	/*public function saveUserToken($token_data)
	{
		$update_data = $token_data;
		unset($update_data['user_id']);
		unset($update_data['uuid']);

		$user_tokens = $this->db->select('token_id')->from(TABLE_USER_TOKENS)
								->where([
									'user_id' => $token_data['user_id'],
									'uuid' => $token_data['uuid'],
									'flag' => $token_data['flag']
								])->get()->result();
		if (!empty($user_tokens)) {
			$this->db->where([
				'user_id' => $token_data['user_id'],
				'flag' => $token_data['flag']
			])
			->update(TABLE_USER_TOKENS, $update_data);
		}
		else {
			// Create new entry if not found.
			if ($this->db->affected_rows() < 1) {
				$this->db->insert(TABLE_USER_TOKENS, $token_data);
			}
		}
	}*/
	public function saveUserToken($token_data)
    {
        $update_data = $token_data;
        unset($update_data['user_id']);
        unset($update_data['uuid']);

        $user_tokens = $this->db->select('token_id')->from(TABLE_USER_TOKENS)
                                ->where([
                                    //'user_id' => $token_data['user_id'],
                                    //'uuid' => $token_data['uuid'],
                                    //'flag' => $token_data['flag']
                                    'token' => $token_data['token']
                                ])->get()->result();
        if (!empty($user_tokens)) {
            $this->db->where([
                'uuid' => $token_data['uuid'],
                'user_id' => $token_data['user_id'],
                'flag' => $token_data['flag']
            ])
            ->update(TABLE_USER_TOKENS, $update_data);
        }
        else {
            // Create new entry if not found.
            if ($this->db->affected_rows() < 1) {
                $this->db->insert(TABLE_USER_TOKENS, $token_data);
            }
        }
    }

	public function updateUserToken($token_data)
	{
		$this->db->where([
			'user_id' => $token_data['user_id'],
			'uuid' => $token_data['uuid']
		])
		->update(TABLE_USER_TOKENS, $token_data);
		return $this->db->affected_rows();
	}

	public function increaseNotificationBadgeCount($user_id)
	{
		$this->db->set('badge_count', 'badge_count+1', FALSE);
		$this->db->where('user_id', $user_id);
		$this->db->update(TABLE_USER_NOTIFICATION);
		if ($this->db->affected_rows() < 1) {
			$data = [
				'user_id' => $user_id,
				'badge_count' => 1
			];
			$this->db->insert(TABLE_USER_NOTIFICATION, $data);
		}
	}

	public function getNotificationBadgeCount($user_id)
	{
		$user_notification = $this->db->where('user_id', $user_id)->get(TABLE_USER_NOTIFICATION)->row();
		$badge_count = !empty($user_notification->badge_count) ? $user_notification->badge_count : 0;
		return $badge_count;
	}

	public function resetNotificationBadgeCount($user_id)
	{
		$this->db->where('user_id', $user_id);
		$this->db->update(TABLE_USER_NOTIFICATION, ['badge_count' => 0]);
		return $this->db->affected_rows() >= 1;
	}

	public function findHourlyRate($no_of_hour)
	{
		$hourly_rate = $this->db->where('no_of_hr', $no_of_hour)->get(TABLE_HOURLY_RATE)->row();
		if(count($hourly_rate)>0)
		{
			return $hourly_rate->rate_per_hour;
		}
	}
	/*public function employeeEarnings($u_id,$month)
	{
 
		$this->db->select('id,TIMEDIFF(end_time, start_time) AS Hours ,DATE_FORMAT(`start_time`, " %M %d ") as start_date,total_price');
		$where = "assigned_to=$u_id AND MONTHNAME(`start_time`)='$month' ";
        $this->db->where($where);
        //$booking = $this->db->where()
        $this->db->order_by('start_time_timestamp','DESC');
		$booking_list = $this->db->get(TABLE_BOOKING_DETAILS)->result();
		//$booking_reviews = 

		 //dd($this->db->last_query()); die();
		return $booking_list;
	}*/
	public function employeeEarnings($u_id,$month,$year)
	{
		$this->db->select('id,TIME_FORMAT(SEC_TO_TIME(sum(TIME_TO_SEC(TIMEDIFF( end_time, start_time)))), "%h:%i") AS diff ,DATE_FORMAT(`start_time`, " %b %d ") as start_date,COUNT( DATE_FORMAT(`start_time`, " %M %d ")) as Booking,SUM(`total_price`) AS total_price');
		$where = "approved_by_userId=$u_id AND MONTHNAME(`start_time`)='$month'";
        $this->db->where($where);
        $this->db->where('YEAR(`start_time`)', $year);
		$this->db->where('status', 'C');
        //$booking = $this->db->where()
        $this->db->group_by("DATE_FORMAT(start_time,  '%M %d' )");
        $this->db->order_by('start_time_timestamp','DESC');
		$booking_list = $this->db->get(TABLE_BOOKING_DETAILS);
		//dd($this->db->last_query()); die();
		$result = $booking_list->result();

		return $result;
	}
	public function getListOfAllJobs1($u_id,$pagination = null)
	{

		$this->db->select('bd.*,u.id as userid,firstName,lastName,emailAddress,userName,userType,profile_picture,profile_picture_org,phone,description,u.status');
      	$this->db->from(TABLE_BOOKING_DETAILS.' as bd');
		$this->db->join(TABLE_USER.' as u' , 'bd.userId = u.id','left');
		$this->db->where('bd.approved_by_userId', $u_id);
		$this->db->where('bd.status', 'AP');
		//$this->db->where('MONTHNAME(`start_time`)', $month);
		//$this->db->order_by('bd.start_time_timestamp','DESC');
		$this->db->order_by('bd.posted_time','DESC');
		if ($pagination) {
			$this->db->limit($pagination['per_page'], $pagination['offset']);
		}		

		$query = $this->db->get();
		$sql = $query->result();

		// dd($this->db->last_query());

		foreach ($sql as $key => $value) 
		{
			$rowcount = 0;
			$re_rating = array();
			
			$value->child_details = $this->getChildData($value->id);
			$value->user_details = $this->getUserDetails($value->userId);
			//$value->user_details = $this->userdata->getDetails(TABLE_USER,$value->userId);
			if (time() >= $value->end_time_timestamp) 
			{
				$value->is_expired = 1;
			}
			else
			{
				$value->is_expired = 0;
			}

			$jobowner_userid = $value->userId;
			$job_id = $value->id;

			$this->db->select('rating');
			$this->db->from("com_user_rating"); 
			$this->db->where('staff_id', $jobowner_userid);
			$this->db->where('u_id', $u_id);
			$this->db->where('job_id', $job_id);
			$query = $this->db->get();
			$rowcount = $query->num_rows();

			$re_rating=$query->result();
			
			//echo "<pre>";
			//print_r($re_rating);

			//echo $this->db->last_query();
			//die;
			//echo "hi  ...".count($re_rating)."<br>";
			//$value->user_rating = 0;

			if(count($re_rating)>0) {
				//die;
				$value->user_rating = $re_rating[0]->rating;
			}
			else
			{
				$value->user_rating = 0;
			}

			


		}
		return $sql;
	}
	public function withdrawl_section($u_id)
	{
		
         $this->db->select('SUM(`amount`) AS creditamount');
         $this->db->where('type', 'C');
         $this->db->where('user_id', $u_id);
		 $total_amt1 = $this->db->get(TABLE_TRANSACTION);
         $result1 = $total_amt1->row();
         
		 
		 $this->db->select('SUM(`amount`) AS debitamount');
         $this->db->where('type', 'D');
         $this->db->where('user_id', $u_id);
         $total_amt2 = $this->db->get(TABLE_TRANSACTION);
		 $result2 = $total_amt2->row();
		 
		 $result=array($result1,$result2);
		 
          //dd($this->db->last_query()); die();
         return $result;
 	}

}
?>