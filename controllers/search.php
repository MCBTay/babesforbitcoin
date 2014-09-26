<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Search extends CI_Controller
{

	// Object containing currently logged in user
	public $_user;

	// Array containing valid categories
	private $_valid = array(
		'models',
		'contributors',
		'assets',
	);

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
	 * Search - Index
	 *
	 * The index page for the search controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function index()
	{
		// Data array to be used in views
		$data = array(
			'class' => 'search-results',
			'title' => 'Search Results',
		);

		// Load views
		$this->load->view('templates/header',     $data);
		$this->load->view('templates/navigation', $data);
		$this->load->view('pages/search/index',   $data);
		$this->load->view('templates/footer-nav', $data);
		$this->load->view('templates/footer',     $data);
	}

	/**
	 * Search - Tag
	 *
	 * The tag page for the search controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function tag($tag_id, $category = 'models', $type = 3)
	{
		if (!in_array($category, $this->_valid))
		{
			// Not a valid category
			redirect();
		}

		if ($this->_user->user_type == 1 && $category == 'contributors')
		{
			// Contributors can't view other contributors
			redirect();
		}
		elseif ($this->_user->user_type == 2 && $category == 'models')
		{
			// Models can't view other models
			$category = 'contributors';
		}
		elseif ($this->_user->user_type == 2 && $category == 'assets')
		{
			// Models can't view other models
			redirect();
		}

		// Data array to be used in views
		$data = array(
			'class'     => 'search-results',
			'title'     => 'Search Results',
			'tag_id'    => $tag_id,
			'category'  => $category,
			'type'      => $type,
			'user_type' => $this->_user->user_type,
		);

		// Get models by tag
		$models = $this->search_model->find_models($tag_id);

		// Add to our data array to be used in views
		$data['models'] = $models;

		// Get contributors by tag
		$contributors = $this->search_model->find_contributors($tag_id);

		// Add to our data array to be used in views
		$data['contributors'] = $contributors;

		// Get assets by tag
		$assets = $this->search_model->find_assets($tag_id);

		// Add to our data array to be used in views
		$data['assets'] = $assets;

		// Load views
		$this->load->view('templates/header',     $data);
		$this->load->view('templates/navigation', $data);
		$this->load->view('pages/search/tag',     $data);
		$this->load->view('templates/footer-nav', $data);
		$this->load->view('templates/footer',     $data);
	}

	/**
	 * Search - Results
	 *
	 * The results page for the search controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function results($keyword, $category = 'models', $type = 3)
	{
		if (!in_array($category, $this->_valid))
		{
			// Not a valid category
			redirect();
		}

		if ($this->_user->user_type == 1 && $category == 'contributors')
		{
			// Contributors can't view other contributors
			redirect();
		}
		elseif ($this->_user->user_type == 2 && $category == 'models')
		{
			// Models can't view other models
			$category = 'contributors';
		}
		elseif ($this->_user->user_type == 2 && $category == 'assets')
		{
			// Models can't view other models
			redirect();
		}

		// Find models/assets by keyword (name search)
		$data = $this->search_model->find_keyword($keyword);

		// Data array to be used in views
		$data['class']     = 'search-results';
		$data['title']     = 'Search Results';
		$data['keyword']   = $keyword;
		$data['category']  = $category;
		$data['type']      = $type;
		$data['user_type'] = $this->_user->user_type;

		// Load views
		$this->load->view('templates/header',     $data);
		$this->load->view('templates/navigation', $data);
		$this->load->view('pages/search/results', $data);
		$this->load->view('templates/footer-nav', $data);
		$this->load->view('templates/footer',     $data);
	}

	/**
	 * Search - Advanced
	 *
	 * The advanced page for the search controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function advanced()
	{
		// Data array to be used in views
		$data = array(
			'class'    => 'advanced',
			'title'    => 'Advanced Search',
		);

		// Load views
		$this->load->view('templates/header',      $data);
		$this->load->view('templates/navigation',  $data);
		$this->load->view('pages/search/advanced', $data);
		$this->load->view('templates/footer-nav',  $data);
		$this->load->view('templates/footer',      $data);
	}

}

/* End of file search.php */
/* Location: ./application/controllers/search.php */