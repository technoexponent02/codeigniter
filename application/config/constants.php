<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// date_default_timezone_set('Europe/London');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESCTRUCTIVE') OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

define('ROOT_URL', 'PROJECT_URL');

define('UPLOAD_PATH_URL', 'assets/upload/');
define('DEFAULT_ASSETS_URL', ROOT_URL.'assets/');
define('FRONTEND_ASSETS_URL', ROOT_URL.'assets/frontend/');

define('API_PER_PAGE', 10);

define('GMAPS_API_KEY', 'AIzaSyDCllpWul1TotXhkOs_xdf9Ql_waikWEf4');


define('IOS_FCM_SERVER_KEY', 'KEY');

define('ANDROID_FCM_SERVER_KEY', 'key');

//SMTP Constants
define('SITE_MAIL_CARRIER', 'MAIL');// MAIL,SMTP
define('SITE_MAIL_TYPE', 'html');
define('SITE_SMTP_HOST', '');
define('SITE_SMTP_USER', '');
define('SITE_SMTP_PASS', '');
define('SITE_SMTP_PORT', '25');

define('TABLE_ADMINUSER','com_adminuser');
define('TABLE_ALLLANGUAGE','com_alllanguage');
define('TABLE_COUNTRIES','com_countries');
define('TABLE_GENERAL_SETTINGS','com_general_settings');
define('TABLE_USER','com_user');
define('TABLE_STAFF_DETAILS','com_staff_details');
define('TABLE_USERLOGIN','com_userlogin');
define('TABLE_EMAIL_ACTIVATION','com_email_activation');
define('TABLE_EMAIL_FORGET_PASSWORD','com_email_forget_password');
define('TABLE_EMAIL_FORGET_USERNAME','com_email_forget_username');
define('TABLE_USERS_CHILD','com_users_child');
define('TABLE_BOOKING_DETAILS','com_booking_details');
define('TABLE_BOOKING_CHILD_DETAILS','com_booking_child_details');
define('TABLE_USER_TOKENS','com_user_tokens');
define('TABLE_USER_RATING','com_user_rating');
define('TABLE_BOOKING_CHAT_LIST','com_booking_chat_list');
define('TABLE_USER_NOTIFICATION', 'com_user_notification');
define('TABLE_HOURLY_RATE', 'com_hourly_rate');
define('TABLE_TRANSACTION', 'com_transactions');
define('TABLE_EXTEND_BOOKING', 'com_extend_booking');

define('TABLE_WITHDRAWl', 'com_withdrawl');
define('TABLE_EMAIL_TEMPLATE','com_email_template');

