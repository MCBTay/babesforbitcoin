<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller
{

	// Object containing currently logged in user
	private $_user;

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
	}

	/**
	 * User - Index
	 *
	 * The index page for the user controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function index()
	{
	}

	/**
	 * User - Login
	 *
	 * The login page for the user controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function login()
	{
	}

	/**
	 * User - Logout
	 *
	 * The logout page for the user controller
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
		redirect('user/login');
	}

	/**
	 * User - Lockout
	 *
	 * The lockout page for the user controller
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

		// Data array to be used in views
		$data = array();

		// Load views
		$this->load->view('templates/header',   $data);
		$this->load->view('pages/user/lockout', $data);
		$this->load->view('templates/footer',   $data);
	}

}

/* End of file user.php */
/* Location: ./application/controllers/user.php */