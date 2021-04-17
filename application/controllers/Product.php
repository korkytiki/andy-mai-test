<?php
class Product extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('session', 'form_validation'));
		$this->load->model('product_model', 'product');
	}

	public function get($id)
	{
		$this->_check_login();

		$product = $this->product->get($id);

		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($product));
	}

	public function get_all()
	{
		$this->_check_login();

		$products = $this->product->get_all();
		
		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($products));
	}

	public function post()
	{
		$this->_check_login();
		
		$this->form_validation->set_rules('name', 'Name', 'required|stripslashes|trim');
		$this->form_validation->set_rules('price', 'Price', 'required|stripslashes|trim');
		$this->form_validation->set_rules('qty', 'Qty', 'required|stripslashes|trim');

		if ($this->form_validation->run() == FALSE)
		{
			show_error(validation_errors());
			return FALSE;
		}
		else
		{
			$product = array(
				'name' => $this->input->post('name'),
				'price' => $this->input->post('price'),
				'qty' => $this->input->post('qty')
			);
			$this->product->create($product);
			$product['ID'] = $this->db->insert_id();

			$this->output
			->set_content_type('application/json')
			->set_output(json_encode($product));
		}
	}

	public function put($id)
	{
		$this->_check_login();

		// NOTE: validation for put requests
		$set_data = array(
			'name' => $this->input->input_stream('name'),
			'price' => $this->input->input_stream('price'),
			'qty' => $this->input->input_stream('qty')
		);
		$this->form_validation->set_data($set_data);
		$this->form_validation->set_rules('name', 'Name', 'required|stripslashes|trim');
		$this->form_validation->set_rules('price', 'Price', 'required|stripslashes|trim');
		$this->form_validation->set_rules('qty', 'Qty', 'required|stripslashes|trim');

		if ($this->form_validation->run() == FALSE)
		{
			show_error(validation_errors());
			return FALSE;
		}
		else
		{
			$product = array(
				'name' => $this->input->input_stream('name'),
				'price' => $this->input->input_stream('price'),
				'qty' => $this->input->input_stream('qty')
			);
			$this->product->update($product, $id);
			$product['ID'] = $id;

			$this->output
			->set_content_type('application/json')
			->set_output(json_encode($product));
		}
	}

	public function remove($id)
	{
		$this->_check_login();

		$product = $this->product->get($id);
		$result = array( 'success' => TRUE );

		if (!$product)
		{
			$result['success'] = false;
			$result['message'] = 'Product does not exist';
		}
		else
		{
			$this->product->delete($id);
		}

		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($result));
	}

	public function compute_latest_qty() 
	{
		$this->load->model('invoice_item_model', 'invoice_item');

		$this->_check_login();

		$products = $this->product->get_all();
		foreach($products as $product)
		{
			$new_product_qty = $this->invoice_item->get_remaining_product_qty($product->ID);
			if ($product->qty != $new_product_qty) 
			{
				$product->qty = $new_product_qty;
				$this->product->update($product, $product->ID);
			}
		}

		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($products));
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
