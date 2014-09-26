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

		// If user isn't logged in, make sure we're on a public page
		if (!$this->_user)
		{
			// Redirect to login
			redirect('account/login');
		}
	}

	/**
	 * Content - Index
	 *
	 * The index page for the content controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function index()
	{
		// There's no index here
		redirect();
	}

	/**
	 * Content - Legal
	 *
	 * The legal page for the content controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function legal()
	{
		// Data array to be used in views
		$data = array(
			'class' => 'legal',
			'title' => '&sect;2257 Exemption Statement',
		);

		// Load views
		$this->load->view('templates/header',     $data);
		$this->load->view('templates/navigation', $data);
		$this->load->view('pages/content/legal',  $data);
		$this->load->view('templates/footer-nav', $data);
		$this->load->view('templates/footer',     $data);
	}

	/**
	 * Content - FAQ
	 *
	 * The FAQ page for the content controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function faq()
	{
		// Data array to be used in views
		$data = array(
			'class' => 'faq',
			'title' => 'Frequently Asked Questions',
		);

		// Load views
		$this->load->view('templates/header',     $data);
		$this->load->view('templates/navigation', $data);
		$this->load->view('pages/content/faq',    $data);
		$this->load->view('templates/footer-nav', $data);
		$this->load->view('templates/footer',     $data);
	}

	/**
	 * Content - TOS
	 *
	 * The TOS page for the content controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function tos()
	{
		// Data array to be used in views
		$data = array(
			'class' => 'tos',
			'title' => 'Terms of Service',
		);

		// Load views
		$this->load->view('templates/header',     $data);
		$this->load->view('templates/navigation', $data);
		$this->load->view('pages/content/tos',    $data);
		$this->load->view('templates/footer-nav', $data);
		$this->load->view('templates/footer',     $data);
	}

	/**
	 * Content - Privacy
	 *
	 * The privacy page for the content controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function privacy()
	{
		// Data array to be used in views
		$data = array(
			'class' => 'privacy',
			'title' => 'Privacy Policy',
		);

		// Load views
		$this->load->view('templates/header',      $data);
		$this->load->view('templates/navigation',  $data);
		$this->load->view('pages/content/privacy', $data);
		$this->load->view('templates/footer-nav',  $data);
		$this->load->view('templates/footer',      $data);
	}

	/**
	 * Content - Upcoming Features
	 *
	 * The upcoming features page for the content controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function upcoming_features()
	{
		// Data array to be used in views
		$data = array(
			'class' => 'upcoming_features',
			'title' => 'Upcoming Features',
		);

		// Load views
		$this->load->view('templates/header',                $data);
		$this->load->view('templates/navigation',            $data);
		$this->load->view('pages/content/upcoming-features', $data);
		$this->load->view('templates/footer-nav',            $data);
		$this->load->view('templates/footer',                $data);
	}

}

/* End of file content.php */
/* Location: ./application/controllers/content.php */