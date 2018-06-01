<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MainModel extends CI_Model {
	protected $table;
	protected $old_table='';
	//protected $domain_id=1;
	function __construct()
	{
		parent::__construct();
	}
	
	public function insert($data_array =array(),$return_id = true)
	{
		if(is_array($data_array) && sizeof($data_array)>0)
		{
			/** For multi domain**/
			/* $data_array['domain_id'] = $this->domain_id; */
			
			$this->db->insert($this->table,$data_array);
			if($return_id == true)
			{
				return $this->db->insert_id();
			}
		}
	}

	/**
	 * 
	 * @author Tuhin | <tuhin@technoexponent.com>
	 */
	public function insertByFillable($insert_data = [], $fillable = [], $return_id = true)
	{
		if (empty($fillable)) return false;

		if(is_array($insert_data) && sizeof($insert_data) > 0) {

			foreach($insert_data as $key => $param) {
				if (!in_array($key, $fillable)) {
					unset($insert_data[$key]);
				}
			}
			
			$this->db->insert($this->table, $insert_data);

			if($return_id == true) return $this->db->insert_id();
		}
		return true;
	}

	public Function fetchOne($condition = array())
	{
		$data = array();
		if(count($condition) > 0)
		{
			/** For multi domain**/
			/* if($this->domain_id != 1)
			{
				$condition['domain_id'] = $this->domain_id;
			} */
			$query = $this->db->get_where($this->table,$condition);
			$data = $query->row();
		}
		return $data;
	}
	public function fetchAll($condition = array(), $order_by = array(), $limit = array())
	{
		$this->db->select('*');
		$this->db->from($this->table);
		/** For multi domain**/
		/* if($this->domain_id != 1)
		{
			$this->db->where('domain_id', $this->domain_id);
		} */
		if(count($condition) > 0)
		{
			$this->db->where($condition);
		}
	
		if(count($order_by) > 0)
		{
			foreach($order_by as $key => $val)
			{
				$this->db->order_by($key,$val);
			}
		}
		if(count($limit) > 0)
		{
			$this->db->limit($limit['count'],$limit['start']);
		}
		$query = $this->db->get();
		return $query->result();
	}
	
	public function countRows($condition = array())
	{
		$this->db->select('*');
		$this->db->from($this->table);
		/** For multi domain**/
		/* if($this->domain_id != 1)
		{
			$this->db->where('domain_id', $this->domain_id);
		} */
		if(count($condition) > 0)
		{
			$this->db->where($condition);
		}
		$query = $this->db->get();
		return $query->num_rows();
	}
	
	public function update($data = array(),$condition = array())
	{
		if(count($data) > 0 && count($condition) > 0)
		{
			$this->db->update($this->table, $data, $condition);
		}
	}
	
	public function delete($condition = array())
	{
		if(count($condition) > 0)
		{
			$this->db->delete($this->table, $condition);
		}
	}
	
	public function setTable($table)
	{
		$this->old_table=$this->table;
		$this->table=$table;
		return $this;
	}
	public function unsetTable()
	{
		$this->table=$this->old_table;
		$this->old_table='';
		return $this;
	}
	
	public function sendMail($from=array(),$to=array(), $subject="", $message="", $cc=array(),$bcc=array())
	{
		$this->load->library('email');
		$config['mailtype'] = SITE_MAIL_TYPE;
		if (SITE_MAIL_CARRIER=="SMTP")
		{
			
			$config['smtp_host'] = SITE_SMTP_HOST;
			$config['smtp_user'] = SITE_SMTP_USER;
			$config['smtp_pass'] = SITE_SMTP_PASS;
			$config['smtp_port'] = SITE_SMTP_PORT;
	        
		}
		$this->email->initialize($config);
		$this->email->from($from['fromEmail'],$from['fromName']);
        $this->email->to($to);
        if(count($cc)>0)   
        {
        	$this->email->cc($cc);
        }
        if(count($bcc)>0)
        {
        	$this->email->bcc($bcc);
        }
        $this->email->subject($subject);
        $this->email->message($message);
        
        if($this->email->send())
        {
        	return TRUE;
        }
        else
        {
        	echo $this->email->print_debugger();
        	die();
        }  

	}
}