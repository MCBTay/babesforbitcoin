<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class My_files extends CI_Controller
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
	 * My Files - Index
	 *
	 * The index page for the my files controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function index()
	{
		// If this user is a model, include the model page
		if ($this->_user->user_type == 2)
		{
			$this->mine();
		}
		else
		{
			$this->purchased();
		}
	}

	/**
	 * My Files - Success
	 *
	 * The success page for the my files controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function success()
	{
		if ($this->_user->user_type != 2)
		{
			// Data array to be used in views
			$data = array(
				'class'     => 'my-files',
				'title'     => 'My Files',
				'success'   => TRUE,
				'photos'    => $this->contributors_model->get_purchased_photos(),
				'photosets' => $this->contributors_model->get_purchased_photosets(),
				'videos'    => $this->contributors_model->get_purchased_videos(),
			);

			// Load views
			$this->load->view('templates/header',     $data);
			$this->load->view('templates/navigation', $data);
			$this->load->view('pages/my-files/index', $data);
			$this->load->view('templates/footer-nav', $data);
			$this->load->view('templates/footer',     $data);
		}
	}

	/**
	 * My Files - Mine
	 *
	 * The mine page for the my files controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function mine()
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
		$this->load->view('templates/header',     $data);
		$this->load->view('templates/navigation', $data);
		$this->load->view('pages/my-files/mine',  $data);
		$this->load->view('templates/footer-nav', $data);
		$this->load->view('templates/footer',     $data);
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
		$this->load->view('templates/header',     $data);
		$this->load->view('templates/navigation', $data);
		$this->load->view('pages/my-files/mine',  $data);
		$this->load->view('templates/footer-nav', $data);
		$this->load->view('templates/footer',     $data);
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

			redirect('my-files/sent');
		}

		// Load views
		$this->load->view('templates/header',     $data);
		$this->load->view('templates/navigation', $data);
		$this->load->view('pages/my-files/mine',  $data);
		$this->load->view('templates/footer-nav', $data);
		$this->load->view('templates/footer',     $data);
	}

	/**
	 * My Files - Purchased
	 *
	 * The purchased page for the my files controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function purchased()
	{
		// Data array to be used in views
		$data = array(
			'class'     => 'my-files',
			'title'     => 'My Files',
			'models'    => $this->contributors_model->get_purchased_models(),
			'fetishes'  => $this->models_model->get_fetishes(),
			'type'      => (array) $this->input->post('type'),
			'tags'      => (array) $this->input->post('tags'),
		);

		// Load views
		$this->load->view('templates/header',     $data);
		$this->load->view('templates/navigation', $data);
		$this->load->view('pages/my-files/index', $data);
		$this->load->view('templates/footer-nav', $data);
		$this->load->view('templates/footer',     $data);
	}

	/**
	 * My Files - Model
	 *
	 * The model page for the my files controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function model($model_id, $show = FALSE)
	{
		// Get model profile information
		$model = $this->models_model->get_model($model_id);

		if (!$model)
		{
			// No model found :(
			redirect('my-files');
		}

		// Data array to be used in views
		$data = array(
			'class'     => 'my-files',
			'title'     => 'My Files',
			'model'     => $model,
			'owned'     => $this->contributors_model->get_owned($model_id),
			'photos'    => $this->contributors_model->get_purchased_photos($model_id),
			'photosets' => $this->contributors_model->get_purchased_photosets($model_id),
			'videos'    => $this->contributors_model->get_purchased_videos($model_id),
			'fetishes'  => $this->models_model->get_fetishes(),
			'type'      => (array) $this->input->post('type'),
			'tags'      => (array) $this->input->post('tags'),
			'show'      => $show,
		);

		// Load views
		$this->load->view('templates/header',     $data);
		$this->load->view('templates/navigation', $data);
		$this->load->view('pages/my-files/model', $data);
		$this->load->view('templates/footer-nav', $data);
		$this->load->view('templates/footer',     $data);
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

		redirect('my-files');
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

/* End of file my_files.php */
/* Location: ./application/controllers/my_files.php */