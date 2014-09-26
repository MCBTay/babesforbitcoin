<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contact extends CI_Controller
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
	 * Contact - Index
	 *
	 * The index page for the contact controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function index()
	{
		// Data array to be used in views
		$data = array(
			'class' => 'contact',
			'title' => 'Contact Us',
			'user'  => $this->_user,
			'mods'  => $this->user_model->get_moderators(),
		);

		// Set validation error delimiters
		$this->form_validation->set_error_delimiters('', '');

		// Set validation rules
		$this->form_validation->set_rules('subject', 'Subject', 'trim|required|xss_clean');
		$this->form_validation->set_rules('message', 'Message', 'trim|required|xss_clean');

		if ($this->form_validation->run() == TRUE)
		{
			$subject = $this->input->post('subject');
			$reason  = $this->input->post('reason');
			$message = $this->input->post('message');

			if (!empty($reason))
			{
				$message = '<p>Please state your reason for leaving:<br>' . $reason . '</p>' . $message;
			}

			$this->emailer_model->send(
				$mail_to         = 'info@babesforbitcoin.com',
				$mail_subject    = $subject,
				$mail_message    = nl2br($message),
				$mail_from_email = $this->_user->email,
				$mail_from_name  = $this->_user->display_name,
				$tag             = 'contact'
			);

			redirect('contact/thanks');
		}

		// Load views
		$this->load->view('templates/header',     $data);
		$this->load->view('templates/navigation', $data);
		$this->load->view('pages/contact/index', $data);
		$this->load->view('templates/footer-nav', $data);
		$this->load->view('templates/footer',     $data);
	}

	/**
	 * Contact - Delete Photoset
	 *
	 * The delete photoset page for the contact controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function delete_photoset($photoset_id)
	{
		$asset = $this->assets_model->get_asset($photoset_id);

		if (!$asset)
		{
			redirect('contact');
		}

		// Data array to be used in views
		$data = array(
			'class' => 'contact',
			'title' => 'Contact Us',
			'user'  => $this->_user,
			'asset' => $asset,
		);

		// Load views
		$this->load->view('templates/header',              $data);
		$this->load->view('templates/navigation',          $data);
		$this->load->view('pages/contact/delete_photoset', $data);
		$this->load->view('templates/footer-nav',          $data);
		$this->load->view('templates/footer',              $data);
	}

	/**
	 * Contact - Cancel
	 *
	 * The cancel page for the contact controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function cancel()
	{
		// Data array to be used in views
		$data = array(
			'class' => 'contact',
			'title' => 'Contact Us',
			'user'  => $this->_user,
		);

		// Load views
		$this->load->view('templates/header',     $data);
		$this->load->view('templates/navigation', $data);
		$this->load->view('pages/contact/cancel', $data);
		$this->load->view('templates/footer-nav', $data);
		$this->load->view('templates/footer',     $data);
	}

	/**
	 * Contact - Thanks
	 *
	 * The thanks page for the contact controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function thanks()
	{
		// Data array to be used in views
		$data = array(
			'class' => 'contact',
			'title' => 'Contact Us',
		);

		// Load views
		$this->load->view('templates/header',     $data);
		$this->load->view('templates/navigation', $data);
		$this->load->view('pages/contact/thanks', $data);
		$this->load->view('templates/footer-nav', $data);
		$this->load->view('templates/footer',     $data);
	}

}

/* End of file contact.php */
/* Location: ./application/controllers/contact.php */