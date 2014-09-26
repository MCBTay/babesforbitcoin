<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Manage_my_files extends CI_Controller
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

		// If user isn't a model
		if ($this->_user->user_type != 2)
		{
			redirect();
		}
	}

	/**
	 * My Files - Index
	 *
	 * The index page for the my files controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function index()
	{
		// Data array to be used in views
		$data = array(
			'class'     => 'my-files',
			'title'     => 'My Files',
			'public'    => $this->models_model->get_mine_public(),
			'private'   => $this->models_model->get_mine_private(),
			'photosets' => $this->models_model->get_mine_photosets(),
			'videos'    => $this->models_model->get_mine_videos(),
		);

		// Load views
		$this->load->view('templates/header',            $data);
		$this->load->view('templates/navigation',        $data);
		$this->load->view('pages/manage-my-files/index', $data);
		$this->load->view('templates/footer-nav',        $data);
		$this->load->view('templates/footer',            $data);
	}

	/**
	 * My Files - Sent
	 *
	 * The sent page for the my files controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function sent()
	{
		// Data array to be used in views
		$data = array(
			'class'     => 'my-files',
			'title'     => 'My Files',
			'public'    => $this->models_model->get_mine_public(),
			'private'   => $this->models_model->get_mine_private(),
			'photosets' => $this->models_model->get_mine_photosets(),
			'videos'    => $this->models_model->get_mine_videos(),
			'sent'      => TRUE,
		);

		// Load views
		$this->load->view('templates/header',            $data);
		$this->load->view('templates/navigation',        $data);
		$this->load->view('pages/manage-my-files/index', $data);
		$this->load->view('templates/footer-nav',        $data);
		$this->load->view('templates/footer',            $data);
	}

	/**
	 * My Files - Send
	 *
	 * The send page for the my files controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function send($contributor_id)
	{
		// Data array to be used in views
		$data = array(
			'class'          => 'my-files',
			'title'          => 'My Files',
			'public'         => $this->models_model->get_mine_public(),
			'private'        => $this->models_model->get_mine_private(),
			'photosets'      => $this->models_model->get_mine_photosets(),
			'videos'         => $this->models_model->get_mine_videos(),
			'send'           => TRUE,
			'contrib'        => $this->user_model->get_user($contributor_id),
			'contributor_id' => $contributor_id,
		);

		// Set validation error delimiters
		$this->form_validation->set_error_delimiters('', '');

		// Set validation rules
		$this->form_validation->set_rules('send', 'Send', 'required');

		// Run validation
		if ($this->form_validation->run() == TRUE)
		{
			// Send to contributor
			$this->models_model->send_assets($contributor_id);

			redirect('manage-my-files/sent');
		}

		// Load views
		$this->load->view('templates/header',            $data);
		$this->load->view('templates/navigation',        $data);
		$this->load->view('pages/manage-my-files/index', $data);
		$this->load->view('templates/footer-nav',        $data);
		$this->load->view('templates/footer',            $data);
	}

	/**
	 * My Files - Remove
	 *
	 * The remove page for the my files controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function remove($asset_id)
	{
		$this->assets_model->remove($asset_id);

		redirect('manage-my-files');
	}

	/**
	 * My Files - Set Default
	 *
	 * The set default page for the my files controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function set_default($asset_id)
	{
		$this->models_model->set_default($asset_id);

		redirect();
	}

}

/* End of file manage_my_files.php */
/* Location: ./application/controllers/manage_my_files.php */