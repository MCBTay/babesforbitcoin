<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Models extends CI_Controller
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

        if ($this->uri->segment(2) != 'preview')
        {
            // If user isn't logged in
            if (!$this->_user)
            {
                // Redirect to login
                redirect('account/login');
            }
        }
	}

	/**
	 * Models - Index
	 *
	 * The index page for the models controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function index()
	{
		// Data array to be used in views
		$data = array(
			'class'    => 'models',
			'title'    => 'Models',
			'featured' => $this->models_model->get_featured(),
			'models'   => $this->models_model->get_models(),
			'fetishes' => $this->models_model->get_fetishes(),
			'type'     => (array) $this->input->post('type'),
			'tags'     => (array) $this->input->post('tags'),
			'sort'     => $this->input->post('sort'),
		);

		// Load views
		$this->load->view('templates/header',     $data);
		$this->load->view('templates/navigation', $data);
		$this->load->view('pages/models/index',   $data);
		$this->load->view('templates/footer-nav', $data);
		$this->load->view('templates/footer',     $data);
	}

	/**
	 * Models - Profile
	 *
	 * The profile page for the models controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function profile($model_id)
	{
		// Get model profile information
		$model = $this->models_model->get_model($model_id);

		if (!$model)
		{
			// No model found :(
			redirect();
		}

		// Data array to be used in views
		$data = array(
			'class' => 'models',
			'title' => ($model->display_name ? $model->display_name : 'User # ' . $model->user_id) . "'s Profile",
			'model' => $model,
			'owned' => $this->contributors_model->get_owned($model_id),
		);

		// Load views
		$this->load->view('templates/header',     $data);
		$this->load->view('templates/navigation', $data);
		$this->load->view('pages/models/profile', $data);
		$this->load->view('templates/footer-nav', $data);
		$this->load->view('templates/footer',     $data);
	}

    /**
     * Models - Preview Profile
     *
     * The preview profile page for the models controller
     *
     * @access public
     * @return n/a
     */
    public function preview($model_id)
    {
        // Get model profile information
        $model = $this->models_model->get_model($model_id);

        if (!$model)
        {
            // No model found :(
            redirect();
        }

        // Data array to be used in views
        $data = array(
            'class' => 'models',
            'title' => ($model->display_name ? $model->display_name : 'User # ' . $model->user_id) . "'s Profile",
            'model' => $model,
            'public' => $this->models_model->get_public_number($model->user_id, 6)
        );

        // Load views
        $this->load->view('templates/header',           $data);
        $this->load->view('pages/models/preview',      $data);
        $this->load->view('templates/footer-nav-login', $data);
        $this->load->view('templates/footer',           $data);
    }

	/**
	 * Models - Assets
	 *
	 * The assets page for the models controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function assets($model_id, $asset_type)
	{
		// Get model profile information
		$model = $this->models_model->get_model($model_id);

		if (!$model)
		{
			// No model found :(
			redirect();
		}

		switch ($asset_type)
		{
			case 1:  $title = 'Public Photos'; break;
			case 3:  $title = 'Photosets';     break;
			case 5:  $title = 'Videos';        break;
			default: redirect();               break;
		}

		$assets = $this->models_model->get_assets($model_id, $asset_type);

		// Data array to be used in views
		$data = array(
			'class'  => 'models',
			'title'  => ($model->display_name ? $model->display_name : 'User # ' . $model->user_id) . "'s " . $title,
			'model'  => $model,
			'assets' => $assets,
			'owned'  => $this->contributors_model->get_owned($model_id),
		);

		// Load views
		$this->load->view('templates/header',     $data);
		$this->load->view('templates/navigation', $data);
		$this->load->view('pages/models/assets',  $data);
		$this->load->view('templates/footer-nav', $data);
		$this->load->view('templates/footer',     $data);
	}

	/**
	 * Models - Asset
	 *
	 * The asset page for the models controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function asset($asset_id)
	{
		if (!$this->cart_model->already_purchased($asset_id) && $this->_user->user_type < 3)
		{
			// Asset not owned
			redirect();
		}

		$asset = $this->models_model->get_asset($asset_id);

		if (!$asset)
		{
			// Asset not found :(
			redirect();
		}

		// If photoset
		if ($asset->asset_type == 3)
		{
			// Add photoset photos
			$asset->photos = $this->contributors_model->get_purchased_photosets_photos($asset->asset_id);
		}
		elseif ($asset->asset_type == 5)
		{
			// Add mimetype information
			$asset->mimetype = $this->assets_model->get_mimetype($asset->video);
		}

		// Data array to be used in views
		$data = array(
			'class' => 'models',
			'title' => ($asset->model->display_name ? $asset->model->display_name : 'User # ' . $asset->model->user_id) . ' - ' . $asset->asset_title,
			'model' => $asset->model,
			'asset' => $asset,
			'owned' => $this->contributors_model->get_owned($asset->model->user_id),
		);

		// Load views
		$this->load->view('templates/header',     $data);
		$this->load->view('templates/navigation', $data);
		$this->load->view('pages/models/asset',   $data);
		$this->load->view('templates/footer-nav', $data);
		$this->load->view('templates/footer',     $data);
	}

}

/* End of file models.php */
/* Location: ./application/controllers/models.php */
