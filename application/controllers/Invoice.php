<?php
class Invoice extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('session', 'form_validation'));
		$this->load->model('invoice_model', 'invoice');
	}

	public function get($id)
	{
		$this->load->model('invoice_item_model', 'invoice_item');

		$this->_check_login();

		$invoice = $this->invoice->get($id);

		$invoice_items = $this->invoice_item->get_by_invoice($invoice->ID);
		$invoice->items = $invoice_items;

		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($invoice));
	}

	public function get_all()
	{
		$this->_check_login();

		$invoices = $this->invoice->get_all();
		
		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($invoices));
	}

	public function post()
	{
		$this->_check_login();
		
		$this->form_validation->set_rules('number', 'Number', 'required|stripslashes|trim');
		$this->form_validation->set_rules('total', 'Total', 'required|stripslashes|trim');
		$this->form_validation->set_rules('customer_ID', 'Customer ID', 'required|stripslashes|trim');
		$this->form_validation->set_rules('employee_ID', 'Employee ID', 'required|stripslashes|trim');

		if ($this->form_validation->run() == FALSE)
		{
			show_error(validation_errors());
			return FALSE;
		}
		else
		{
			$invoice = array(
				'number' => $this->input->post('number'),
				'total' => $this->input->post('total'),
				'customer_ID' => $this->input->post('customer_ID'),
				'employee_ID' => $this->input->post('employee_ID')
			);
			$this->invoice->create($invoice);
			$invoice['ID'] = $this->db->insert_id();

			$this->output
			->set_content_type('application/json')
			->set_output(json_encode($invoice));
		}
	}

	public function put($id)
	{
		$this->_check_login();

		// NOTE: validation for put requests
		$set_data = array(
			'number' => $this->input->input_stream('number'),
			'total' => $this->input->input_stream('total'),
			'customer_ID' => $this->input->input_stream('customer_ID'),
			'employee_ID' => $this->input->input_stream('employee_ID')
		);
		$this->form_validation->set_data($set_data);
		$this->form_validation->set_rules('number', 'Number', 'required|stripslashes|trim');
		$this->form_validation->set_rules('total', 'Total', 'required|stripslashes|trim');
		$this->form_validation->set_rules('customer_ID', 'Customer ID', 'required|stripslashes|trim');
		$this->form_validation->set_rules('employee_ID', 'Employee ID', 'required|stripslashes|trim');

		if ($this->form_validation->run() == FALSE)
		{
			show_error(validation_errors());
			return FALSE;
		}
		else
		{
			$invoice = array(
				'number' => $this->input->input_stream('number'),
				'total' => $this->input->input_stream('total'),
				'customer_ID' => $this->input->input_stream('customer_ID'),
				'employee_ID' => $this->input->input_stream('employee_ID')
			);
			$this->invoice->update($invoice, $id);
			$invoice['ID'] = $id;

			$this->output
			->set_content_type('application/json')
			->set_output(json_encode($invoice));
		}
	}

	public function remove($id)
	{
		$this->load->model('invoice_item_model', 'invoice_item');

		$this->_check_login();

		$user = $this->invoice->get($id);
		$result = array( 'success' => TRUE );

		if (!$user)
		{
			$result['success'] = false;
			$result['message'] = 'invoice does not exist';
		}
		else
		{
			$this->invoice_item->delete_by_invoice($id);
			$this->invoice->delete($id);
		}

		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($result));
	}

	public function get_daily_sales() {
		
		$this->_check_login();
		
		$date_list = $this->input->post('date_list');
		$sales_list = [];
		if($date_list != false)
		{
			foreach($date_list as $date)
			{
				$sales_list[] = $this->invoice->get_sales_by_date($date);
			}
		}

		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($sales_list));
	}

	public function get_monthly_sales() {
		
		$this->_check_login();
		
		$date_list = $this->input->post('date_list');
		$sales_list = [];
		if($date_list != false)
		{
			foreach($date_list as $date)
			{
				$sales_list[] = $this->invoice->get_sales_by_month($date['start'], $date['end']);
			}
		}

		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($sales_list));
	}

	public function get_yearly_sales() {
		
		$this->_check_login();
		
		$date_list = $this->input->post('date_list');
		$sales_list = [];
		if($date_list != false)
		{
			foreach($date_list as $year)
			{
				$sales_list[] = $this->invoice->get_sales_by_year($year);
			}
		}

		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($sales_list));
	}
	
	private function _check_login()
	{
		$user = $this->session->userdata('user');
		if($user == FALSE)
		{
			show_error('No user');
		}
	}
}
