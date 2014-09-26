<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller
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
	 * Management/Home - Index
	 *
	 * The index page for the management/home controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function index()
	{
		// Require valid user
		$this->management_model->require_valid_user();

		// Data array to be used in views
		$data = array(
			'users'        => $this->management_model->get_users(0, 1, '2.all.all.0', $sort = 'user_id', $dir = 'asc'),
			'assets_users' => $this->management_model->get_assets_users(),
			'recent'       => $this->management_model->recently_approved(),
		);

		// Load views
		$this->load->view('templates/management/header', $data);
		$this->load->view('pages/management/index',      $data);
		$this->load->view('templates/management/footer', $data);
	}

}

/* End of file home.php */
/* Location: ./application/controllers/management/home.php */