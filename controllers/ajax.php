<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ajax extends CI_Controller
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

		if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']))
		{
			// Should never come here without HTTP_X_REQUESTED_WITH set
			redirect();
		}

		// Get currently logged in user
		$this->_user = $this->user_model->get_user();

		// Load ajax model
		$this->load->model('ajax_model');

		// Turn off caching and set JSON content-type header
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
		$this->output->set_header('Cache-Control: post-check=0, pre-check=0');
		$this->output->set_header('Pragma: no-cache');
		$this->output->set_header('Content-Type: application/json');
	}

	/**
	 * Ajax - Index
	 *
	 * The index page for the ajax controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function index()
	{
		// Should never come here without anything set
		redirect();
	}

	/**
	 * Ajax - Tags
	 *
	 * The tags page for the ajax controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function tags()
	{
		$term = $this->input->get('term');
		$tags = $this->ajax_model->get_tags($term);

		echo json_encode($tags);
	}

	/**
	 * Ajax - Users
	 *
	 * The users page for the ajax controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function users()
	{
		$term  = $this->input->get('term');
		$users = $this->ajax_model->get_users($term);

		echo json_encode($users);
	}

	/**
	 * Ajax - All
	 *
	 * The all page for the ajax controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function all()
	{
		$term = $this->input->get('term');
		$all  = $this->ajax_model->get_all($term);

		echo json_encode($all);
	}

	/**
	 * Ajax - Send Message
	 *
	 * The send message page for the ajax controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function send_message()
	{
		$return = $this->messages_model->send_message();

		echo json_encode($return);
	}

	/**
	 * Ajax - Get Messages
	 *
	 * Get child messages for the given parent
	 *
	 * @access public
	 * @return n/a
	 */

	public function get_messages()
	{
		$message_id = (int) $this->input->post('message_id');

		$data = array(
			'messages'  => $this->messages_model->get_message_thread($message_id),
			'parent_id' => $message_id,
		);

		$return = array(
			'html' => $this->load->view('pages/messages/index_messages', $data, TRUE),
		);

		echo json_encode($return);
	}

}

/* End of file ajax.php */
/* Location: ./application/controllers/ajax.php */