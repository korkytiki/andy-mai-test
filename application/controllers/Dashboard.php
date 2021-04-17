<?php
class Dashboard extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->library('session');
	}

	public function index() {

		$this->_check_login();

		$data['menu'] = 'index';

        $this->load->view('templates/header', $data);
        $this->load->view('dashboard/index', $data);
        $this->load->view('templates/footer');
	}

	public function invoicing() {

		$this->_check_login();

		$this->load->model('customer_model', 'customer');
		$this->load->model('product_model', 'product');
		$this->load->model('invoice_model', 'invoice');
		
		$user = $this->session->userdata('user');

		$customers = $this->customer->get_all();
		$invoices = $this->invoice->get_all();
		foreach($invoices as $invoice)
		{
			$customer_idx = array_search($invoice->customer_ID, array_column($customers, 'ID'));
			$invoice->customer_name = $customers[$customer_idx]->name;
		}

		$data['menu'] = 'invoicing';
		$data['cuser'] = $user;
		$data['customers'] = $customers;
		$data['products'] = $this->product->get_all();
		$data['invoices'] = $invoices;

        $this->load->view('templates/header', $data);
        $this->load->view('dashboard/invoicing', $data);
        $this->load->view('templates/footer');
	}

	public function products() {
		
		$this->_check_login();

		$data['menu'] = 'products';

        $this->load->view('templates/header', $data);
        $this->load->view('dashboard/products', $data);
        $this->load->view('templates/footer');
	}

	public function customers() {
		
		$this->_check_login();

		$data['menu'] = 'customers';

        $this->load->view('templates/header', $data);
        $this->load->view('dashboard/customers', $data);
        $this->load->view('templates/footer');
	}

	public function login()
	{		
		$this->load->model('employee_model', 'employee');
		$this->load->helper('form');
		$this->load->library('form_validation');
		
		$user = $this->session->userdata('user');
		if($user == TRUE) 
			redirect('dashboard');

		$this->form_validation->set_rules('username', 'Username', 'required|stripslashes|trim');
		$this->form_validation->set_rules('password', 'Password', 'required|stripslashes|trim');

		if($this->form_validation->run() === FALSE)
		{
			$this->load->view('dashboard/login.php');
		}
		else
		{
			$user = $this->employee->try_login($this->input->post('username'), $this->input->post('password'));
			if($user === FALSE)
			{
				$data['error'] = 'Wrong username or password';
				$this->load->view('dashboard/login.php', $data);
			}
			else 
			{
				$this->session->set_userdata('user', $user);
				redirect('dashboard');
			}
		}
	}

	public function logout()
	{
		$this->session->sess_destroy();
		
		redirect(base_url() . 'dashboard/login');
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
