<?php
class customer extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('session', 'form_validation'));
		$this->load->model('customer_model', 'customer');
	}

	public function get($id)
	{
		$this->_check_login();

		$user = $this->customer->get($id);

		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($user));
	}

	public function get_all()
	{
		$this->_check_login();

		$users = $this->customer->get_all();
		
		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($users));
	}

	public function post()
	{
		$this->_check_login();
		
		$this->form_validation->set_rules('name', 'Name', 'required|stripslashes|trim');

		if ($this->form_validation->run() == FALSE)
		{
			show_error(validation_errors());
			return FALSE;
		}
		else
		{
			$customer = array(
				'name' => $this->input->post('name'),
			);
			$this->customer->create($customer);
			$customer['ID'] = $this->db->insert_id();

			$this->output
			->set_content_type('application/json')
			->set_output(json_encode($customer));
		}
	}

	public function put($id)
	{
		$this->_check_login();

		// NOTE: validation for put requests
		$set_data = array(
			'name' => $this->input->input_stream('name'),
		);
		$this->form_validation->set_data($set_data);
		$this->form_validation->set_rules('name', 'Name', 'required|stripslashes|trim');

		if ($this->form_validation->run() == FALSE)
		{
			show_error(validation_errors());
			return FALSE;
		}
		else
		{
			$customer = array(
				'name' => $this->input->input_stream('name'),
			);
			$this->customer->update($customer, $id);
			$customer['ID'] = $id;

			$this->output
			->set_content_type('application/json')
			->set_output(json_encode($customer));
		}
	}

	public function remove($id)
	{
		$this->_check_login();

		$user = $this->customer->get($id);
		$result = array( 'success' => TRUE );

		if (!$user)
		{
			$result['success'] = false;
			$result['message'] = 'Customer does not exist';
		}
		else
		{
			$this->customer->delete($id);
		}

		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($result));
	}
	
	private function _check_login()
	{
		$user = $this->session->userdata('user');
		if($user == FALSE)
		{
			redirect(base_url() . 'dashboard/login');
		}
	}
}
