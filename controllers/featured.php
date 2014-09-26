<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Featured extends CI_Controller
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

		// If user isn't logged in
		if (!$this->_user)
		{
			// Redirect to login
			redirect('account/login');
		}

		// Models can't view other models
		if ($this->_user->user_type == 2)
		{
			redirect();
		}
	}

	/**
	 * Featured - Index
	 *
	 * The index page for the featured controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function index()
	{
		// Data array to be used in views
		$data = array(
			'class' => 'featured',
			'title' => 'Featured',
		);

		// Load views
		$this->load->view('templates/header',     $data);
		$this->load->view('templates/navigation', $data);
		$this->load->view('pages/featured/index', $data);
		$this->load->view('templates/footer-nav', $data);
		$this->load->view('templates/footer',     $data);
	}

}

/* End of file featured.php */
/* Location: ./application/controllers/featured.php */