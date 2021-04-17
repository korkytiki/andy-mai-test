<?php
class invoice_item extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('session', 'form_validation'));
		$this->load->model('invoice_item_model', 'invoice_item');
	}

	public function get($id)
	{
		$this->_check_login();

		$user = $this->invoice_item->get($id);

		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($user));
	}

	public function get_all()
	{
		$this->_check_login();

		$users = $this->invoice_item->get_all();
		
		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($users));
	}

	public function post()
	{
		$this->_check_login();
		
		$this->form_validation->set_rules('invoice_ID', 'Invoice ID', 'required|stripslashes|trim');
		$this->form_validation->set_rules('product_ID', 'Product ID', 'required|stripslashes|trim');
		$this->form_validation->set_rules('qty', 'QTY', 'required|stripslashes|trim');
		$this->form_validation->set_rules('invoice_price', 'Invoice Price', 'required|stripslashes|trim');

		if ($this->form_validation->run() == FALSE)
		{
			show_error(validation_errors());
			return FALSE;
		}
		else
		{
			$invoice_item = array(
				'invoice_ID' => $this->input->post('invoice_ID'),
				'product_ID' => $this->input->post('product_ID'),
				'qty' => $this->input->post('qty'),
				'invoice_price' => $this->input->post('invoice_price')
			);
			$this->invoice_item->create($invoice_item);
			$invoice_item['ID'] = $this->db->insert_id();

			$this->output
			->set_content_type('application/json')
			->set_output(json_encode($invoice_item));
		}
	}

	public function put($id)
	{
		$this->_check_login();

		// NOTE: validation for put requests
		$set_data = array(
			'invoice_ID' => $this->input->input_stream('invoice_ID'),
			'product_ID' => $this->input->input_stream('product_ID'),
			'qty' => $this->input->input_stream('qty'),
			'invoice_price' => $this->input->input_stream('invoice_price')
		);
		$this->form_validation->set_data($set_data);
		$this->form_validation->set_rules('invoice_ID', 'Invoice ID', 'required|stripslashes|trim');
		$this->form_validation->set_rules('product_ID', 'Product ID', 'required|stripslashes|trim');
		$this->form_validation->set_rules('qty', 'QTY', 'required|stripslashes|trim');
		$this->form_validation->set_rules('invoice_price', 'Invoice Price', 'required|stripslashes|trim');

		if ($this->form_validation->run() == FALSE)
		{
			show_error(validation_errors());
			return FALSE;
		}
		else
		{
			$invoice_item = array(
				'invoice_ID' => $this->input->post('invoice_ID'),
				'product_ID' => $this->input->post('product_ID'),
				'qty' => $this->input->post('qty'),
				'invoice_price' => $this->input->post('invoice_price')
			);
			$this->invoice_item->update($invoice_item, $id);
			$invoice_item['ID'] = $id;

			$this->output
			->set_content_type('application/json')
			->set_output(json_encode($invoice_item));
		}
	}

	public function remove($id)
	{
		$this->_check_login();

		$user = $this->invoice_item->get($id);
		$result = array( 'success' => TRUE );

		if (!$user)
		{
			$result['success'] = false;
			$result['message'] = 'invoice_item does not exist';
		}
		else
		{
			$this->invoice_item->delete($id);
		}

		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($result));
	}

	public function batch_save()
	{
		$this->load->model('product_model', 'product');

		$this->_check_login();
		
		$item_list = $this->input->post('item_list');
		$saved_item_list = [];
		if($item_list != false)
		{
			foreach($item_list as $item)
			{
				$invoice_item = array(
					'invoice_ID' => $item['invoice_ID'],
					'product_ID' => $item['product_ID'],
					'qty' => $item['qty'],
					'invoice_price' => $item['invoice_price']
				);
				$this->invoice_item->create($invoice_item);
				$invoice_item['ID'] = $this->db->insert_id();

				// $product = $this->product->get($item['product_ID']);
				// $product->qty = $product->qty - $item['qty'];
				// $this->product->update($product, $product->ID);

				$saved_item_list[] = $invoice_item;
			}
		}

		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($saved_item_list));
	}

	public function batch_update()
	{
		$this->_check_login();
		
		$item_list = $this->input->post('item_list');
		$saved_item_list = [];
		if($item_list != false)
		{
			foreach($item_list as $item)
			{
				$invoice_item = array(
					'invoice_ID' => $item['invoice_ID'],
					'product_ID' => $item['product_ID'],
					'qty' => isset($item['qty']) ? $item['qty'] : 0,
					'invoice_price' => isset($item['invoice_price']) ? $item['invoice_price'] : 0
				);
				if ($item['ID'] > 0) {
					if ($item['product_ID'] > 0) {
						$this->invoice_item->update($invoice_item, $item['ID']);
					} else {
						$this->invoice_item->delete($item['ID']);
						continue;
					}
				} else {
					$this->invoice_item->create($invoice_item);
					$invoice_item['ID'] = $this->db->insert_id();
				}
				$saved_item_list[] = $invoice_item;
			}
		}

		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($saved_item_list));
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
