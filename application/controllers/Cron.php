<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cron extends CI_Controller {
	public $data=array();

	function __construct()
	{
		parent::__construct();
		$this->load->model('webservicedata');
		$this->load->model('userdata');
		$this->data=$this->defaultdata->getFrontendDefaultData();
	}

	public function sendJobNotificationToStuff()
	{
		$cond = array('status'=>'P');
		$get_pending_jobs = $this->userdata->getDetails(TABLE_BOOKING_DETAILS, $cond);

		foreach ($get_pending_jobs as $get_pending_jobs_value) 
		{
			$get_staff = $this->webservicedata->getHighestRatingUser($get_pending_jobs_value->latitude, $get_pending_jobs_value->longitude);
			$staff_id = array();
			foreach ($get_staff as $get_staff_value) 
			{
				$staff_id[] = ($get_staff_value->id);
			}

			$get_booked_staff = $this->webservicedata->getBookedUser($get_pending_jobs_value->end_time, $get_pending_jobs_value->start_time);
			$booked_staff_id = array();
			foreach ($get_booked_staff as $get_booked_staff_value) 
			{
				$booked_staff_id[] = ($get_booked_staff_value->approved_by_userId);
			}

			$free_staff_id = array_diff($staff_id, $booked_staff_id);

			 print_r($free_staff_id);
		}
	}

	public function sendFinishJobNotification()
	{
		/*$txt = "Hey man!";
		$myfile = file_put_contents(APPPATH . 'logs.txt', $txt.PHP_EOL , FILE_APPEND | LOCK_EX);
		echo $txt.PHP_EOL;*/

		// Get jobs which are about to finish(in current minute)
		$now = date('Y-m-d H:i');
		// $now = '2017-06-23 03:00';
		$jobs_finishing_now = $this->webservicedata->getJobsFinishingNow($now);
		// dd($jobs_finishing_now);

		if (empty($jobs_finishing_now)) {
			exit;
		}

		$this->load->helper('text');

		foreach ($jobs_finishing_now as $job) {
			// Send notification to customer.
			$push_notification_user_id = $job->userId;

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

			$this->webservicedata->increaseNotificationBadgeCount($push_notification_user_id);
			$badge_count = $this->webservicedata->getNotificationBadgeCount($push_notification_user_id);

			$job_details_lmtd = character_limiter($job->job_details, 150, '...');

			$noti = [
				"body" => 'Times up for job!',
				"content_available" => 1,
				"sound" => "default",
				"badge" => $badge_count,
				"click_action" => "ACTIONABLE",
				"mutable-content" => 1
			];

			$message['job_id'] = $job->id;
			$message['job_details'] = $job_details_lmtd;
			$message['type'] = 'times_up';

			$message['notification'] = [
				"body" => 'Times up for job!',
				"badge" => $badge_count
			];

			$json_message = json_encode($message);
			$message = array("message" => $json_message, "contents" => "contents");

			if (!empty($ios_tokens)) {
				$message_status_ios = sendNotificationIOS($ios_tokens, $message, $noti);
				$message_status_ios = json_decode($message_status_ios);
				// dump($message_status_ios);
			}
			
			if (!empty($android_tokens)) {
				$message_status_andriod = sendNotificationAndroid($android_tokens, $message);
				$message_status_andriod = json_decode($message_status_andriod);
				// dump($message_status_andriod);
			}
		}
		// End foreach.
	}

	public function changeJobToExpired()
	{
		$expiry_day = 7;
		$this->webservicedata->changeJobToExpired($expiry_day);
	}
	public function getChatListRemove()
	{
		$expiry_day = 1;
         $this->webservicedata->getChatListRemove($expiry_day);
	}
	
}