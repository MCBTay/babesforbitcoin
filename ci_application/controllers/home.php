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

		// If user isn't logged in, make sure we're on a public page
		if (!$this->_user)
		{
			// Redirect to login
			redirect('account/login');
		}
	}

	/**
	 * Home - Index
	 *
	 * The index page for the home controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function index()
	{
		// See if they are searching
		$search = $this->input->post('search-all');

		if (!empty($search))
		{
			// Redirect to search results page
			redirect('search/results/' . $search);
		}

		// Data array to be used in views
		$data = array(
			'class'    => 'home',
			'title'    => 'Home',
			'messages' => $this->messages_model->get_new(),
		);

		// Load views
		$this->load->view('templates/header',     $data);
		$this->load->view('templates/navigation', $data);
		$this->load->view('pages/home/index',     $data);
		$this->load->view('templates/footer-nav', $data);
		$this->load->view('templates/footer',     $data);
	}

}

/* End of file home.php */
/* Location: ./application/controllers/home.php */