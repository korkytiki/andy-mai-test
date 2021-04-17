
<?php
class Invoice_model extends CI_Model {

	public function __construct()
	{
		$this->load->database();
	}
	
	public function get($id)
	{		
		$this->db->where('ID', $id);
		$query = $this->db->get('invoice');
		return $query->row();
	}
	
	public function get_all()
	{		
		$query = $this->db->get('invoice');
		return $query->result();
	}
	
	public function create($record)
	{
		return $this->db->insert('invoice', $record);
	}
	
	public function update($record, $id)
	{
		$this->db->where('ID', $id);
		return $this->db->update('invoice', $record);
	}
	
	public function get_sales_by_date($date)
	{		
		$sql = "
			SELECT SUM(total) as total_sales FROM invoice WHERE date_created BETWEEN '{$date} 00:00:00' AND '{$date} 23:59:59'
		";
	
		$query = $this->db->query($sql);
		
		if($query->num_rows() > 0)
		{
			$result = $query->row();
			
			return $result->total_sales ? $result->total_sales : 0;
		}
		
		return 0;
	}
	
	public function get_sales_by_month($start, $end)
	{		
		$sql = "
			SELECT SUM(total) as total_sales FROM invoice WHERE date_created BETWEEN '{$start} 00:00:00' AND '{$end} 23:59:59'
		";
	
		$query = $this->db->query($sql);
		
		if($query->num_rows() > 0)
		{
			$result = $query->row();
			
			return $result->total_sales ? $result->total_sales : 0;
		}
		
		return 0;
	}
	
	public function get_sales_by_year($year)
	{		
		$sql = "
			SELECT SUM(total) as total_sales FROM invoice WHERE date_created BETWEEN '{$year}-01-01 00:00:00' AND '{$year}-12-31 23:59:59'
		";
	
		$query = $this->db->query($sql);
		
		if($query->num_rows() > 0)
		{
			$result = $query->row();
			
			return $result->total_sales ? $result->total_sales : 0;
		}
		
		return 0;
	}
	
	public function delete($id)
	{
		$this->db->where('ID', $id);
		$this->db->delete('invoice');
	}
}
