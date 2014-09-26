<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Account extends CI_Controller
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
	 * Management/Account - Index
	 *
	 * The index page for the management/account controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function index()
	{
		if ($this->_user)
		{
			if ($this->_user->user_type < 3)
			{
				// Not a moderator or administrator
				redirect();
			}
			elseif ($this->_user->lockout == 1)
			{
				// User has been locked out
				redirect('management/account/lockout');
			}
			else
			{
				// User is already logged in
				redirect('management');
			}
		}

		// Data array to be used in views
		$data = array();

		// Set validation error delimiters
		$this->form_validation->set_error_delimiters('', '');

		// Set validation rules
		$this->form_validation->set_rules('login-email',    'Email',    'trim|required|valid_email|xss_clean');
		$this->form_validation->set_rules('login-password', 'Password', 'trim|required|xss_clean');

		// Run validation
		if ($this->form_validation->run() == TRUE)
		{
			// Validation passed, attempt login
			if ($this->user_model->login_user())
			{
				// Successful login
				redirect('management');
			}
			else
			{
				// Invalid login
				$data['error'] = 'No account matches those login credentials.';
			}
		}

		// Hide navigation since user isn't logged in
		$data['hide_nav'] = TRUE;

		// Load views
		$this->load->view('templates/management/header', $data);
		$this->load->view('pages/management/login',      $data);
		$this->load->view('templates/management/footer', $data);
	}

	/**
	 * Management/Account - Logout
	 *
	 * The logout page for the management/account controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function logout()
	{
		if ($this->_user)
		{
			// User is logged in, so we can logout
			$this->user_model->logout_user();
		}

		// Redirect to login page
		redirect('management/account');
	}

	/**
	 * Management/Account - Lockout
	 *
	 * The lockout page for the management/account controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function lockout()
	{
		if ($this->_user)
		{
			// User is logged in, so we should logout
			$this->user_model->logout_user();
		}

		// Hide navigation since user isn't logged in
		$data['hide_nav'] = TRUE;

		// Load views
		$this->load->view('templates/management/header', $data);
		$this->load->view('pages/management/lockout',    $data);
		$this->load->view('templates/management/footer', $data);
	}

}

/* End of file account.php */
/* Location: ./application/controllers/management/account.php */