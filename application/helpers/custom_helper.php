<?php
if(!function_exists("encrypt"))
{
	function encrypt($string, $key) {
		$result = '';
		for($i=0; $i<strlen($string); $i++) {
			$char = substr($string, $i, 1);
			$keychar = substr($key, ($i % strlen($key))-1, 1);
			$char = chr(ord($char)+ord($keychar));
			$result.=$char;
		}
		return base64_encode($result);
	}
}
if(!function_exists("decrypt"))
{
	function decrypt($string, $key) {
		$result = '';
		$string = base64_decode($string);
		for($i=0; $i<strlen($string); $i++) {
			$char = substr($string, $i, 1);
			$keychar = substr($key, ($i % strlen($key))-1, 1);
			$char = chr(ord($char)-ord($keychar));
			$result.=$char;
		}
		return $result;
	}
}
if(!function_exists("getCurlData"))
{
	function getCurlData($url,$poststr)
	{
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_VERBOSE, 1);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl,CURLOPT_POSTFIELDS,$poststr);
		//curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16");
		$curlData = curl_exec($curl);
		if(curl_errno($curl))
		{
			echo curl_error($curl);
		}
		else
		{
			curl_close($curl);
			return $curlData;
		}
	}
}
if(!function_exists("deformatUrlStr"))
{
	function deformatUrlStr($nvpstr)
	{
		$intial=0;
		$nvpArray = array();

		while(strlen($nvpstr))
		{
			//postion of Key
			$keypos= strpos($nvpstr,'=');
			//position of value
			$valuepos = strpos($nvpstr,'&') ? strpos($nvpstr,'&'): strlen($nvpstr);

			/*getting the Key and Value values and storing in a Associative Array*/
			$keyval=substr($nvpstr,$intial,$keypos);
			$valval=substr($nvpstr,$keypos+1,$valuepos-$keypos-1);
			//decoding the respose
			$nvpArray[urldecode($keyval)] =urldecode( $valval);
			$nvpstr=substr($nvpstr,$valuepos+1,strlen($nvpstr));
		 }
		return $nvpArray;
	}
}
if(!function_exists("grabStaticText"))
{
	function grabStaticText($table = TABLE_STATIC_TEXT1,$typeID)
	{
		$ci = & get_instance();
		$lang_id = $ci->session->userdata('languageID');
		$cond = array('languageID' => $lang_id,'typeID' => $typeID);
		$query = $ci->db->get_where($table,$cond);
		return $query->row()->title;
	}
}
if(!function_exists("getRandomUserName"))
{
	function getRandomUserName($name)
	{
		$rondom_no = mt_rand(0, 10000000);
		$name = $name.$rondom_no;
		return $name;
	}
}
if(!function_exists("fix_url"))
{
	function fix_url($url) 
	{
			if (substr($url, 0, 7) == 'http://') { return $url; }
			if (substr($url, 0, 8) == 'https://') { return $url; }
			return 'http://'. $url;
	}
}
if ( ! function_exists('profile_picture'))
{
	function profile_picture($picture)
	{
		if($picture != ''){
			$profile_pic = DEFAULT_ASSETS_URL.'upload/profile_pictures/'.$picture;
		}else{
			$profile_pic = DEFAULT_ASSETS_URL.'images/user-default-image.jpg';
		}
		return $profile_pic;
	}
}
if ( ! function_exists('word_wrap'))
{
	function word_wrap($str,$length = 90)
	{
		$string = strip_tags($str);
		$s_length=strlen($string);
		if($s_length > $length)
		{
			if(strpos($string," ",$length) !== false)
			{
				$string=substr($string,0,strpos($string," ",$length));
			}
			else
			{
				$string=substr($string,0,$length);
			}
			$string.=' ...';
		} 
		else
		{
			$string=$string;
		}
		return stripslashes($string);
	}
}
if ( ! function_exists('checkTaskerStep3Complete'))
{
	function checkTaskerStep3Complete($user_details)
	{
		$flag = 1;
		if($user_details->address == '' || $user_details->addr_lat == '' || $user_details->addr_long == '')
		{
			$flag = 0;
		}
		return $flag;
	}
}
if ( ! function_exists('checkTaskerStep4Complete'))
{
	function checkTaskerStep4Complete($user_details)
	{
		$flag = 1;
		if($user_details->profileType == 1 && ($user_details->comapany_name == '' || $user_details->comapany_no == '' || $user_details->company_ph == ''))
		{
			$flag = 0;
		}
		elseif($user_details->profileType == 2 && ($user_details->firstName == '' || $user_details->lastName == '' || $user_details->phone == ''))
		{
			$flag = 0;
		}
		elseif($user_details->profileType == 3 && ($user_details->firstName == '' || $user_details->lastName == '' || $user_details->phone == ''))
		{
			$flag = 0;
		}
		else
		{
			$flag = 1;
		}
		return $flag;
	}
}
if ( ! function_exists('calculateDistance'))
{
	function calculateDistance($lat1, $lon1, $lat2, $lon2, $unit = 'M') 
	{
		$lat1 = (double)$lat1;
		$lon1 = (double)$lon1;
		$lat2 = (double)$lat2;
		$lon2 = (double)$lon2;
		$theta = $lon1 - $lon2;
		$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
		$dist = acos($dist);
		$dist = rad2deg($dist);
		$miles = $dist * 60 * 1.1515;
		$unit = strtoupper($unit);
		if ($unit == "K")
		{
			return ($miles * 1.609344);
		}
		else if ($unit == "N")
		{
	      return ($miles * 0.8684);
	    }
		else
		{
	        return $miles;
	    }
	}
}

function pre_r($data)
{
echo "<pre>";
print_r($data);
echo "</pre>";
die;
}

function empty_check_api_keys($post = NULL,$param_list = NULL)//Checking post data is empty or not
{
	if($post == NULL || $param_list == NULL || ! is_array($post)|| ! is_array($param_list)){
	return TRUE;
	}
	else
	{
		$result = array();
		foreach($param_list as $oneparam ){  
		if(empty($post[$oneparam])){
		$result[$oneparam] =  "'".$oneparam."' is missing.";
	}
	}
		return (count($result > 0)) ? $result : FALSE;
	}
}


if ( ! function_exists('sendNotificationIOS'))
{
    function sendNotificationIOS($tokens, $message, $noti) {
		
		$url = 'https://fcm.googleapis.com/fcm/send';

		$tokens = array_values(array_unique($tokens));
		
		$fields = array(
			 'notification' => $noti,
			 'registration_ids' => $tokens,
			 'priority' => 'high',
			 'data' => $message,
			 'sound' => 'default'
			);
		
		$headers = array(
			'Authorization:key = ' . IOS_FCM_SERVER_KEY,
			'Content-Type: application/json'
			);

	   $ch = curl_init();
       curl_setopt($ch, CURLOPT_URL, $url);
       curl_setopt($ch, CURLOPT_POST, true);
       curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
       curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);  
       curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
       curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
       $result = curl_exec($ch);           
       if ($result === FALSE) {
           die('Curl failed: ' . curl_error($ch));
       }
       curl_close($ch);
       return $result;
	}
}

if ( ! function_exists('sendNotificationAndroid'))
{
    function sendNotificationAndroid($tokens, $message) {
		$url = 'https://fcm.googleapis.com/fcm/send';

		$tokens = array_values(array_unique($tokens));
		
		//For android
		$fields = array( 
			 'registration_ids' => $tokens,
			 'priority' => 'high',
			 'data' => $message
			);

		$headers = array(
			'Authorization:key = ' . ANDROID_FCM_SERVER_KEY,
			'Content-Type: application/json'
			);

	   $ch = curl_init();
       curl_setopt($ch, CURLOPT_URL, $url);
       curl_setopt($ch, CURLOPT_POST, true);
       curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
       curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);  
       curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
       curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
       $result = curl_exec($ch);           
       if ($result === FALSE) {
           die('Curl failed: ' . curl_error($ch));
       }
       curl_close($ch);
       return $result;
	}
}

if ( ! function_exists('time_elapsed_string'))
{
	function time_elapsed_string($ptime = 0)
	{
		$etime = time() - $ptime;
		if ($etime < 1)
		{
			return '0 seconds';
		}
		$a = array( 12 * 30 * 24 * 60 * 60  =>  'year',
					30 * 24 * 60 * 60       =>  'month',
					24 * 60 * 60            =>  'day',
					60 * 60                 =>  'hour',
					60                      =>  'minute',
					1                       =>  'second'
					);
		foreach ($a as $secs => $str)
		{
			$d = $etime / $secs;
			if ($d >= 1)
			{
				$r = round($d);
				return $r . ' ' . $str . ($r > 1 ? 's' : '') . ' ago';
			}
		}
	}
}

if(!function_exists("getAd"))
{
	function getAd()
	{
		$ci = & get_instance();
		//$lang_id = $ci->session->userdata('languageID');
		
		$query = $ci->db->query("SELECT ad_link, ad_image FROM com_ads where status='Y' ORDER BY rand() LIMIT 0,1");
		return $query->row();
	}
}


/**
 * Send json response to client.

 * @param $data string
 * @author Tuhin | <tuhin@technoexponent.com>
 */
if (! function_exists('jsonResponse')) {
	function jsonResponse($data, $status_code = 200) {
		http_response_code($status_code);
		header('Content-Type: application/json');
		echo json_encode($data);
	}
}

/**
 * Validate a api request from client. Checks for any missing param.

 * @param array $input
 * @param array $required_params
 * @author Tuhin | <tuhin@technoexponent.com>
 */
if (! function_exists('validateRequest')) {
	function validateRequest($input, $required_params) {
	    $missing_list = [];
	    $isValid = true;
	    
	    foreach($required_params as $param) {
	        if (!array_key_exists($param, $input)) {
	            array_push($missing_list, "'$param'");
	            $isValid = false;
	        }
	    }
	    $missing_text = '';
	    if (!$isValid) {
	    	$missing_text = 'Invalid request. Missing ' . implode(', ', $missing_list) . ' parameter.';
	    }
	    
	    return [
	        'isValid' => $isValid,
	        'missing_text' => $missing_text
	    ];   
	}
}

if (!function_exists("dd")) {
	/**
	 * Function to dump inside <pre> tag and die..
	 * @author Tuhin Subhra Mandal | <tuhin@technoexponent.com>
	 * @param mixed $expression
	 *
	 * @return string
	 */
	function dd($expression)
	{
		echo '<pre>';
		echo gettype($expression) . '<br />';
		echo '<div style="padding-left: 5em">';
		if (is_scalar($expression)) {
			$span = '<span style="color: #ff751a;">"</span>';
			echo $span . $expression . $span;
		}
		else {
			print_r($expression);
		}

		echo '</div>';
		echo '</pre>';
		exit(1);
	}
}


if (!function_exists("dump")) {
	/**
	 * Function to dump inside <pre> tag and die..
	 * @author Tuhin Subhra Mandal | <tuhin@technoexponent.com>
	 * @param mixed $expression
	 *
	 * @return string
	 */
	function dump($expression)
	{
		echo '<pre>';
		echo gettype($expression) . '<br />';
		echo '<div style="padding-left: 5em">';
		if (is_scalar($expression)) {
			$span = '<span style="color: #ff751a;">"</span>';
			echo $span . $expression . $span;
		}
		else {
			print_r($expression);
		}

		echo '</div>';
		echo '</pre>';
	}
}

if(!function_exists("commonMailContent"))
{
	function commonMailContent($mailcontent)
	{
		$ci = & get_instance();
		$site_details = $ci->defaultdata->grabSettingData();
		//$mailcontent = str_replace('{SITE_URL}', $site_details->SiteTitle, $mailcontent);
		//$mailcontent = str_replace('{SITE_TITLE}',
		//	$site_details->siteurl,$mailcontent);
		//$footer = htmlspecialchars_decode($site_details->email_footer);
		//$footer = htmlspecialchars_decode($site_details->email_footer);
        /*$message = "<html><head></head><body>".$mailcontent."
        </body><footer>".$footer."</footer></html>";*/
		$message = "<html><head></head><body>".$mailcontent."
        </body></html>";
		return $message;
	}
}

?>