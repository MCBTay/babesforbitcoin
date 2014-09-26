<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Maintenance extends CI_Controller
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
	}

	/**
	 * Maintenance - Index
	 *
	 * The index page for the maintenance controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function index()
	{
		// Data array to be used in views
		$data = array(
			'class' => 'login',
			'title' => 'Down for Maintenance',
		);

		// Load views
		$this->load->view('templates/header',           $data);
		$this->load->view('pages/maintenance/index',    $data);
		$this->load->view('templates/footer',           $data);
	}

}

/* End of file maintenance.php */
/* Location: ./application/controllers/maintenance.php */