<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Content extends CI_Controller
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
	 * Management/Content - Index
	 *
	 * The index page for the management/content controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function index()
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
		$this->load->view('templates/management/header',    $data);
		$this->load->view('pages/management/content/index', $data);
		$this->load->view('templates/management/footer',    $data);
	}

}

/* End of file content.php */
/* Location: ./application/controllers/management/content.php */