<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contributors extends CI_Controller
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
	}

	/**
	 * Contributors - Index
	 *
	 * The index page for the contributors controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function index()
	{
		// Data array to be used in views
		$data = array(
			'class'        => 'contributors',
			'title'        => 'Contributors',
			'contributors' => $this->contributors_model->get_contributors(),
			'tags'         => (array) $this->input->post('tags'),
			'sort'         => $this->input->post('sort'),
		);

		// Load views
		$this->load->view('templates/header',         $data);
		$this->load->view('templates/navigation',     $data);
		$this->load->view('pages/contributors/index', $data);
		$this->load->view('templates/footer-nav',     $data);
		$this->load->view('templates/footer',         $data);
	}

	/**
	 * Contributors - Profile
	 *
	 * The profile page for the contributors controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function profile($contributor_id)
	{
		// Contributors can't view other contributors
		if ($this->_user->user_type == 1 && $this->_user->user_id != $contributor_id)
		{
			redirect('contributors/profile/' . $this->_user->user_id);
		}

		// Get contributor profile information
		$contributor = $this->contributors_model->get_contributor($contributor_id);

		if (!$contributor)
		{
			// No contributor found :(
			redirect();
		}

		// Data array to be used in views
		$data = array(
			'class'       => 'contributors',
			'title'       => ($contributor->display_name ? $contributor->display_name : 'User # ' . $contributor->user_id) . "'s Profile",
			'contributor' => $contributor,
		);

		// Load views
		$this->load->view('templates/header',           $data);
		$this->load->view('templates/navigation',       $data);
		$this->load->view('pages/contributors/profile', $data);
		$this->load->view('templates/footer-nav',       $data);
		$this->load->view('templates/footer',           $data);
	}

	/**
	 * Contributors - Assets
	 *
	 * The assets page for the contributors controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function assets($contributor_id, $asset_type)
	{
		// Contributors can't view other contributors
		if ($this->_user->user_type == 1 && $this->_user->user_id != $contributor_id)
		{
			redirect('contributors/profile/' . $this->_user->user_id);
		}

		// Get contributor profile information
		$contributor = $this->contributors_model->get_contributor($contributor_id);

		if (!$contributor)
		{
			// No contributor found :(
			redirect();
		}

		switch ($asset_type)
		{
			case 1:  $title = 'Public Photos'; break;
			default: redirect();               break;
		}

		$assets = $this->contributors_model->get_assets($contributor_id, $asset_type);

		// Data array to be used in views
		$data = array(
			'class'       => 'contributors',
			'title'       => ($contributor->display_name ? $contributor->display_name : 'User # ' . $contributor->user_id) . "'s " . $title,
			'contributor' => $contributor,
			'assets'      => $assets,
		);

		// Load views
		$this->load->view('templates/header',           $data);
		$this->load->view('templates/navigation',       $data);
		$this->load->view('pages/contributors/assets',  $data);
		$this->load->view('templates/footer-nav',       $data);
		$this->load->view('templates/footer',           $data);
	}

}

/* End of file contributors.php */
/* Location: ./application/controllers/contributors.php */