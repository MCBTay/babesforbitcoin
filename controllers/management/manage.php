<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Manage extends CI_Controller
{

	// Object containing currently logged in user
	public $_user;

	/**
	 * Class Constructor
	 *
	 * Override the parent class constructor with our own
	 *
	 * @access public
	 * @return n/a
	 */
	public function __construct()
	{
		// Call the parent class constructor
		parent::__construct();

		// Get currently logged in user
		$this->_user = $this->user_model->get_user();

		// Load management model
		$this->load->model('management_model');
	}

	/**
	 * Management/Manage - Index
	 *
	 * The index page for the management/manage controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function index()
	{
		// There's nothing at index - send to frontpage management
		redirect('management/manage/frontpage');
	}

	/**
	 * Management/Manage - Front Page
	 *
	 * The frontpage page for the management/manage controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function frontpage()
	{
		// Require valid user
		$this->management_model->require_valid_user();

		// Data array to be used in views
		$data = array();

		// Get array of POSTed sortable models
		$sort = (array) $this->input->post('sort');

		// See if data is POSTed
		if (count($sort) > 1)
		{
			// Save featured sort
			$this->management_model->save_featured_sort($sort);

			// Set success
			$data['success'] = TRUE;
		}

		// Data array to be used in views
		$data['models'] = $this->management_model->get_featured();

		// Load views
		$this->load->view('templates/management/header',       $data);
		$this->load->view('pages/management/manage/frontpage', $data);
		$this->load->view('templates/management/footer',       $data);
	}

	/**
	 * Management/Manage - IP's
	 *
	 * The IP's page for the management/manage controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function ips()
	{
		// Require valid user
		$this->management_model->require_valid_user();

		// Data array to be used in views
		$data = array();

		// Set validation error delimiters
		$this->form_validation->set_error_delimiters('', '');

		// Set validation rules
		$this->form_validation->set_rules('ip_addresses', 'Blocked IP\'s', 'trim|required|xss_clean');

		// Run validation
		if ($this->form_validation->run() == TRUE)
		{
			$data['success'] = TRUE;

			$this->management_model->save_blocked_ips();
		}

		// Data array to be used in views
		$data['blocked_ips']  = $this->management_model->get_blocked_ips();

		// Load views
		$this->load->view('templates/management/header', $data);
		$this->load->view('pages/management/manage/ips', $data);
		$this->load->view('templates/management/footer', $data);
	}

	/**
	 * Management/Manage - Payout
	 *
	 * The payout page for the management/manage controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function payout()
	{
		// Require valid user
		$this->management_model->require_valid_user();

		if ($this->_user->user_type != 4)
		{
			// Only administrators can see this page
			redirect('management');
		}

		// Initialize Dwolla model
		$this->dwolla_model->initialize();

		// Set validation error delimiters
		$this->form_validation->set_error_delimiters('', '');

		// Set validation rules
		$this->form_validation->set_rules('payout_models[]', 'Model Checkbox', 'required');

		// Data array to be used in views
		$data = array();

		// Run validation
		if ($this->form_validation->run() == TRUE)
		{
			$data['success'] = TRUE;
			$this->management_model->process_payout();
		}

		// Data array to be used in views
		$data['model_payout']    = $this->management_model->model_payout();
		$data['funds_available'] = $this->dwolla_model->balance();

		// Load views
		$this->load->view('templates/management/header',    $data);
		$this->load->view('pages/management/manage/payout', $data);
		$this->load->view('templates/management/footer',    $data);
	}

}

/* End of file manage.php */
/* Location: ./application/controllers/management/manage.php */