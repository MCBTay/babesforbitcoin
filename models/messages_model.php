<?php if (! defined('BASEPATH')) exit('No direct script access');

class Messages_model extends CI_Model
{

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
	}

	/**
	 * Get New
	 *
	 * Get newest messages for logged in user from database
	 * This function can show the same thread twice
	 * if multiple new messages exist in the thread
	 *
	 * @access public
	 * @return n/a
	 */
	public function get_new()
	{
		$this->db->from('messages');
		$this->db->where('user_id_to', $this->_user->user_id);
		$this->db->where('message_deleted', 0);
		$this->db->order_by('read', 'asc');
		$this->db->order_by('message_id', 'desc');
		$this->db->limit(6);
		$query  = $this->db->get();
		$result = $query->result();

		foreach ($result as $key => $row)
		{
			// show user_id_from details
			$result[$key]->user = $this->user_model->get_user($row->user_id_from);

			// If message hasn't been read before
			if (!$row->read)
			{
				// Mark messages to this user as read
				$this->db->set('read', 1);
				$this->db->where('message_id', $row->message_id);
				$this->db->update('messages');
			}
		}

		return $result;
	}

	/**
	 * Get Messages
	 *
	 * Get messages for logged in user from database
	 *
	 * @access public
	 * @return n/a
	 */
	public function get_messages()
	{
		// Array to hold messages we will return
		$messages = array();

		// Get the messages from the database both sent and received
		$this->db->from('messages');
		$this->db->where('user_id_from', $this->_user->user_id);
		$this->db->or_where('user_id_to', $this->_user->user_id);
		$this->db->order_by('message_id', 'desc');
		$query  = $this->db->get();
		$result = $query->result();

		foreach ($result as $key => $row)
		{
			if ($row->parent_id > 0)
			{
				$index = $row->parent_id;
			}
			else
			{
				$index = $row->message_id;
			}

			// See if we already have this parent
			if (!array_key_exists($index, $messages))
			{
				// Set the message to our messages array
				$messages[$index] = $row;

				// We want the pic/name that shows up to not be the logged in user
				if ($this->_user->user_id == $row->user_id_to)
				{
					// user_id_to is logged in user so show user_id_from
					$messages[$index]->user = $this->user_model->get_user($row->user_id_from);
				}
				else
				{
					// user_id_from is logged in user so show user_id_to
					$messages[$index]->user = $this->user_model->get_user($row->user_id_to);
				}

				// Determine whether message is read
				$this->db->from('messages');
				$this->db->where("( `parent_id` = '$index' OR `message_id` = '$index' )");
				$this->db->where('user_id_to', $this->_user->user_id);
				$this->db->where('read', 0);
				$this->db->order_by('message_id', 'desc');
				$this->db->limit(1);
				$query = $this->db->get();
				$msg   = $query->row();

				if ($msg)
				{
					$messages[$index]->unread = TRUE;
				}
				else
				{
					$messages[$index]->unread = FALSE;
				}
			}
		}

		return $messages;
	}

	/**
	 * Get Message Thread
	 *
	 * Get message thread for given message_id from database
	 *
	 * @access public
	 * @return n/a
	 */
	public function get_message_thread($message_id)
	{
		// Make sure message id is an integar
		$message_id = (int) $message_id;

		$this->db->from('messages');
		$this->db->where("( `parent_id` = '$message_id' OR `message_id` = '$message_id' )");
		$this->db->where("( `user_id_to` = '{$this->_user->user_id}' OR `user_id_from` = '{$this->_user->user_id}' )");
		$this->db->order_by('parent_id', 'desc');
		$this->db->order_by('message_id', 'desc');
		$query  = $this->db->get();
		$result = $query->result();

		foreach ($result as $key => $row)
		{
			$result[$key]->from = $this->user_model->get_user($row->user_id_from);

			if ($this->_user->user_id == $row->user_id_from)
			{
				$result[$key]->other_id = $row->user_id_to;
			}
			else
			{
				$result[$key]->other_id = $row->user_id_from;

				if (!$row->read)
				{
					// Mark messages to this user as read
					$this->db->set('read', 1);
					$this->db->where('message_id', $row->message_id);
					$this->db->update('messages');
				}
			}
		}

		return $result;
	}

	/**
	 * Get Message Thread
	 *
	 * Get message thread for given message_id from database
	 *
	 * @access public
	 * @return n/a
	 */
	public function send_message()
	{
		// Setup array that we'll return
		$return = array();

		// Get variables from form and session
		$parent_id    = (int) $this->input->post('parent_id');
		$user_id_from = (int) $this->_user->user_id;
		$user_id_to   = (int) $this->input->post('user_id_to');
		$message      = trim($this->input->post('message'));

		if (!$parent_id)
		{
			// See if there is a parent we can assign this to - only one conversation per user
			$this->db->from('messages');
			$this->db->where('user_id_from', $user_id_from);
			$this->db->where('user_id_to', $user_id_to);
			$this->db->where('parent_id', 0);
			$query = $this->db->get();
			$row   = $query->row();

			if ($row)
			{
				$parent_id = $row->message_id;
			}
			else
			{
				// Check for the opposite
				$this->db->from('messages');
				$this->db->where('user_id_to', $user_id_from);
				$this->db->where('user_id_from', $user_id_to);
				$this->db->where('parent_id', 0);
				$query = $this->db->get();
				$row   = $query->row();

				if ($row)
				{
					$parent_id = $row->message_id;
				}
			}
		}

		// Create data array to use in insert
		$data = array(
			'parent_id'       => $parent_id,
			'user_id_from'    => $user_id_from,
			'user_id_to'      => $user_id_to,
			'message'         => $message,
			'message_created' => time(),
		);

		if ($user_id_to && $user_id_from && !empty($message))
		{
			if ($this->_user->user_type != 2 || $this->_user->user_approved)
			{
				if ($parent_id)
				{
					// Let's make sure this parent id belongs to one of the users
					$this->db->from('messages');
					$this->db->where('message_id', $parent_id);
					$query = $this->db->get();
					$row   = $query->row();

					if ($row)
					{
						if ($row->user_id_from == $user_id_from || $row->user_id_to == $user_id_from)
						{
							// Insert into the database
							$this->db->insert('messages', $data);

							// Let the JS know we're valid
							$return['success'] = TRUE;
						}
						else
						{
							// Let the JS know we have a problem
							$return['success'] = FALSE;
						}
					}
					else
					{
						// Let the JS know we have a problem
						$return['success'] = FALSE;
					}
				}
				else
				{
					// Insert into the database
					$this->db->insert('messages', $data);

					// Let the JS know we're valid
					$return['success'] = TRUE;
				}
			}
			else
			{
				// User isn't approved, give them a special message
				$return['special'] = TRUE;

				// Let the JS know we have a problem
				$return['success'] = FALSE;
			}
		}
		else
		{
			// Let the JS know we have a problem
			$return['success'] = FALSE;
		}

		// Notifications
		if ($return['success'] == TRUE)
		{
			// Get user we're sending to
			$user_to = $this->user_model->get_user($user_id_to);

			// Get user we're sending from
			$user_from = $this->user_model->get_user($user_id_from);

			if ($user_to->notify_email_messages)
			{
				// Data array to be used in views
				$data = array(
					'display_name' => $user_from->display_name,
				);

				// email template
				$message = $this->load->view('emails/new_message', $data, true);

				$this->emailer_model->send(
					$mail_to         = $user_to->email,
					$mail_subject    = SITE_TITLE . ' New Message',
					$mail_message    = $message,
					$mail_from_email = 'info@babesforbitcoin.com',
					$mail_from_name  = SITE_TITLE,
					$tag             = 'user-notifications'
				);
			}

			if ($user_to->notify_text_messages)
			{
				// Data array to be used in views
				$data = array(
					'display_name' => $user_from->display_name,
				);

				// email template
				$message = $this->load->view('emails/new_message_text', $data, true);

				$this->emailer_model->send(
					$mail_to         = $user_to->text_number . '@' . $this->notifications_model->get_carrier_domain($user_to->text_carrier),
					$mail_subject    = '',
					$mail_message    = $message,
					$mail_from_email = 'info@babesforbitcoin.com',
					$mail_from_name  = SITE_TITLE,
					$tag             = 'user-notifications'
				);
			}
		}

		return $return;
	}

}

/* End of file messages_model.php */
/* Location: ./application/models/messages_model.php */