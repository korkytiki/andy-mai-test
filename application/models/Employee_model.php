<?php
class Employee_model extends CI_Model {

	public function __construct()
	{
		$this->load->database();
	}

	public function try_login($username, $password)
	{
		$this->db->where('username', $username);
		$this->db->where('password', md5($password));
		$query = $this->db->get('employee');
		$result = $query->row();
		if(count($result) == 1)
		{
			return $result;
		}
		else
		{
			return FALSE;
		}
	}
}
