
<?php
class Customer_model extends CI_Model {

	public function __construct()
	{
		$this->load->database();
	}
	
	public function get($id)
	{		
		$this->db->where('ID', $id);
		$query = $this->db->get('customer');
		return $query->row();
	}
	
	public function get_all()
	{		
		$query = $this->db->get('customer');
		return $query->result();
	}
	
	public function create($record)
	{
		return $this->db->insert('customer', $record);
	}
	
	public function update($record, $id)
	{
		$this->db->where('ID', $id);
		return $this->db->update('customer', $record);
	}
	
	public function delete($id)
	{
		$this->db->where('ID', $id);
		$this->db->delete('customer');
	}
}
