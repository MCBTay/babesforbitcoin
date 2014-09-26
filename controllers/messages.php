<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Messages extends CI_Controller
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
	 * Messages - Index
	 *
	 * The index page for the messages controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function index()
	{
		// Data array to be used in views
		$data = array(
			'class'    => 'messages',
			'title'    => 'Messages',
			'messages' => $this->messages_model->get_messages(),
		);

		// Load views
		$this->load->view('templates/header',     $data);
		$this->load->view('templates/navigation', $data);
		$this->load->view('pages/messages/index', $data);
		$this->load->view('templates/footer-nav', $data);
		$this->load->view('templates/footer',     $data);
	}

	/**
	 * Messages - Delete
	 *
	 * The delete page for the messages controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function delete($message_id)
	{
		$this->db->set('message_deleted', 1);
		$this->db->where('message_id', $message_id);
		$this->db->where('user_id_to', $this->_user->user_id);
		$this->db->update('messages');

		redirect('messages');
	}

}

/* End of file messages.php */
/* Location: ./application/controllers/messages.php */