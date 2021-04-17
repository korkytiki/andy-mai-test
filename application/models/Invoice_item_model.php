
<?php
class invoice_item_model extends CI_Model {

	public function __construct()
	{
		$this->load->database();
	}
	
	public function get($id)
	{		
		$this->db->where('ID', $id);
		$query = $this->db->get('invoice_item');
		return $query->row();
	}
	
	public function get_all()
	{		
		$query = $this->db->get('invoice_item');
		return $query->result();
	}
	
	public function get_by_invoice($invoice_id)
	{		
		$this->db->where('invoice_ID', $invoice_id);
		$query = $this->db->get('invoice_item');
		return $query->result();
	}
	
	public function create($record)
	{
		return $this->db->insert('invoice_item', $record);
	}
	
	public function update($record, $id)
	{
		$this->db->where('ID', $id);
		return $this->db->update('invoice_item', $record);
	}
	
	public function delete($id)
	{
		$this->db->where('ID', $id);
		$this->db->delete('invoice_item');
	}
	
	public function delete_by_invoice($invoice_id)
	{		
		$this->db->where('invoice_ID', $invoice_id);
		$this->db->delete('invoice_item');
	}

	public function get_remaining_product_qty($product_id) 
	{
		$sql = "
			SELECT p.qty - SUM(i.qty) AS product_remaining
			FROM invoice_item AS i
			JOIN product p
			ON i.product_ID = p.ID
			WHERE product_ID = {$product_id}
		";
	
		$query = $this->db->query($sql);
		
		if($query->num_rows() > 0)
		{
			$result = $query->row();
			
			return $result->product_remaining ? $result->product_remaining : 0;
		}
		
		return 0;
	}
}
