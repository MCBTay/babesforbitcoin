<?php if (! defined('BASEPATH')) exit('No direct script access');

class User_model extends CI_Model
{
	private $_key        = '';
	private $_token      = '';
	private $_hmac       = '';
	private $_user_token = '';

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

		// Set encryption key to $this->_key for easy access
		$this->_key = $this->config->item('encryption_key');
	}

	/**
	 * Generate Token
	 *
	 * Generate a unique and secure token for a user's session
	 *
	 * @access private
	 * @return array
	 */
	private function _token_generate()
	{
		$token = hash('sha256', uniqid(mt_rand(), true) . uniqid(mt_rand(), true));

		return $token;
	}

	/**
	 * Password Hash
	 *
	 * Create a password hash given password and salt
	 *
	 * @access public
	 * @return public
	 */
	public function _password_hash($password, $salt)
	{
		// Create hash of password using salt
		$hash = hash('sha256', $password . $salt . $this->_key);

		return $hash;
	}

	/**
	 * Password Reset
	 *
	 * Generate password reset hash and expiration in database
	 *
	 * @access public
	 * @return array
	 */
	public function password_reset($user_id)
	{
		// Generate hash for password reset
		$reset_hash = $this->_token_generate();

		// Determine reset hash expiration as 24 hours from now
		$reset_expiration = time() + 86400;

		$data = array(
			'reset_hash'       => $reset_hash,
			'reset_expiration' => $reset_expiration,
		);

		$this->db->where('user_id', $user_id);
		$this->db->update('users', $data);

		return $reset_hash;
	}

	/**
	 * Get user
	 *
	 * Get user from session or provided user_id
	 *
	 * @access public
	 * @return array
	 */
	public function get_user($user_id = 0)
	{
		if ($user_id == 0)
		{
			$user_id = $this->session->userdata('user_id');

			// This is a natural/organic login, so let's update last_login field
			$this->db->set('last_login', time());
			$this->db->where('user_id', $user_id);
			$this->db->update('users');
		}

		if ($user_id)
		{
			$this->db->from('users');
			$this->db->where('user_id', $user_id);
			$query = $this->db->get();
			$row   = $query->row();

			// Get the default photo, if one exists
			$this->db->from('assets');
			$this->db->where('user_id', $user_id);
			$this->db->where('deleted', 0);
			if (!$row->trusted)
			{
				$this->db->where('approved', 1);
			}
			$this->db->where('default', 1);
			$query = $this->db->get();
			$asset = $query->row();

			if ($asset)
			{
				$row->default = $asset;
			}
			else
			{
				// If no default, try first public photo instead
				$this->db->from('assets');
				$this->db->where('user_id', $user_id);
				$this->db->where('asset_type', 1);
				$this->db->where('deleted', 0);
				if (!$row->trusted)
				{
					$this->db->where('approved', 1);
				}
				$this->db->order_by('asset_id', 'asc');
				$this->db->limit(1);
				$query = $this->db->get();
				$asset = $query->row();

				if ($asset)
				{
					$row->default = $asset;
				}
			}

			// Set online to true if last_login within 30 minutes
			if ($row->last_login > (time() - 1800))
			{
				$row->online = TRUE;
			}
			else
			{
				$row->online = FALSE;
			}

			// Set the currently available funds_total
			$row->funds_usd   = number_format($row->funds_usd, 2, '.', '');
			$row->funds_btc   = round($row->funds_btc, 6);
			$row->funds_total = number_format($row->funds_usd + $this->cart_model->btc_to_usd($row->funds_btc), 2, '.', '');

			// See how many unread messages this user has
			$this->db->from('messages');
			$this->db->where('user_id_to', $row->user_id);
			$this->db->where('read', 0);
			$row->unread = (int) $this->db->count_all_results();

			// If unread is over 99, set it to 99
			if ($row->unread > 99)
			{
				$row->unread = 99;
			}

			return $row;
		}
		else
		{
			return FALSE;
		}
	}

	/**
	 * Get user by email
	 *
	 * Get user by email
	 *
	 * @access public
	 * @return array
	 */
	public function get_user_by_email($email = '')
	{
		$this->db->from('users');
		$this->db->where('email', $email);
		$query = $this->db->get();
		$row   = $query->row();

		return $row;
	}

	/**
	 * Login User
	 *
	 * Attempt to login a user
	 *
	 * @access public
	 * @return array
	 */
	public function login_user()
	{
		// Get the email/password from login form
		$email    = strtolower($this->input->post('login-email'));
		$password = $this->input->post('login-password');

		// Look up the user by email in database
		$row = $this->get_user_by_email($email);

		if ($row)
		{
			// Set variables for the hash and salt from the database
			list($db_hash, $db_salt) = explode(':', $row->password, 2);

			// Create hash of user supplied password for comparison
			$hash = $this->_password_hash($password, $db_salt);

			// See if user supplied hash matches database hash
			if ($hash == $db_hash)
			{
				if ($row->lockout == 1)
				{
					// User has been locked out by a moderator or administrator
					if ($this->uri->segment(1) == 'management')
					{
						// Redirect to management lockout page
						redirect('management/account/lockout');
					}
					else
					{
						// Redirect to user lockout page
						redirect('account/lockout');
					}
				}
				else
				{
					// Valid email/password, create session
					$this->session->set_userdata('user_id', $row->user_id);

					// Update last_login
					$this->db->set('last_login', time());
					$this->db->where('user_id', $row->user_id);
					$this->db->update('users');

					// Log login_success for auditing purposes
					$data = array(
						'user_id'    => $row->user_id,
						'created'    => time(),
						'action'     => 'login_success',
						'ip_address' => $this->input->ip_address(),
					);
					$this->db->insert('users_log', $data);

					return TRUE;
				}
			}
			else
			{
				// Invalid password

				// Log login_fail for auditing purposes
				$data = array(
					'user_id'    => $row->user_id,
					'created'    => time(),
					'action'     => 'login_fail',
					'ip_address' => $this->input->ip_address(),
				);
				$this->db->insert('users_log', $data);

				return FALSE;
			}
		}
		else
		{
			// No user found with given email
			return FALSE;
		}
	}

	/**
	 * Logout User
	 *
	 * Attempt to logout a user
	 *
	 * @access public
	 * @return boolean
	 */
	public function logout_user()
	{
		// Remove session cookie from user browser
		$this->session->unset_userdata('user_id');

		return TRUE;
	}

	/**
	 * Create User
	 *
	 * Create a new user
	 *
	 * @access public
	 * @return boolean
	 */
	public function create_user($user_id)
	{
		// Get the values from the form
		$email    = strtolower($this->input->post('create-email'));
		$password = $this->input->post('create-password');

		// Generate salted password hash
		$hash = $this->password_salt($password);

		// See if it's okay to insert into the database
		$exists = $this->get_user_by_email($email);

		if (!$exists)
		{
			// Insert the user into the database
			$data = array(
				'user_id'    => $user_id,
				'created'    => time(),
				'last_login' => time(),
				'email'      => $email,
				'password'   => $hash,
			);
			$this->db->insert('users', $data);

			// Log account_created for auditing purposes
			$data = array(
				'user_id'    => $user_id,
				'created'    => time(),
				'action'     => 'account_created',
				'ip_address' => $this->input->ip_address(),
			);
			$this->db->insert('users_log', $data);
		}

		// Just created user and therefore they are authenticated so start session
		$this->session->set_userdata('user_id', $user_id);

		return TRUE;
	}

	/**
	 * Register User
	 *
	 * Register a new user
	 *
	 * @access public
	 * @return boolean
	 */
	public function register_user()
	{
		// Get the values from the form
		$user_type     = strtolower($this->input->post('user_type'));
		$display_name  = $this->input->post('display_name');
		$email         = $this->input->post('email');
		$password      = $this->input->post('password');
		$date_of_birth = $this->input->post('date_of_birth');
		$accept_btc    = (int) $this->input->post('accept_btc');
		$agree_terms   = (int) $this->input->post('agree_terms');

		// This should never be possible, but just in case
		if ($user_type < 1 || $user_type > 2 )
		{
			$user_type = 1;
		}

		// Generate salted password hash
		$hash = $this->password_salt($password);

		// Insert the user into the database
		$data = array(
			'user_type'     => $user_type,
			'display_name'  => $display_name,
			'email'         => $email,
			'password'      => $hash,
			'date_of_birth' => $date_of_birth,
			'accept_btc'    => $accept_btc,
			'agree_terms'   => $agree_terms,
			'created'       => time(),
			'last_login'    => time(),
		);
		$this->db->insert('users', $data);

		$user_id = $this->db->insert_id();

		// Log account_created for auditing purposes
		$data = array(
			'user_id'    => $user_id,
			'created'    => time(),
			'action'     => 'account_created',
			'ip_address' => $this->input->ip_address(),
		);
		$this->db->insert('users_log', $data);

		// Just created user and therefore they are authenticated so start session
		$this->session->set_userdata('user_id', $user_id);

		return TRUE;
	}

	/**
	 * Save Preferences
	 *
	 * Save the user's preferences in the database
	 *
	 * @access public
	 * @return object
	 */
	public function save_preferences()
	{
		$data = array(
			'display_name'          => $this->input->post('display_name'),
			'accept_btc'            => (int) $this->input->post('accept_btc'),
			'prefer_btc'            => (int) $this->input->post('prefer_btc'),
			'notify_email_messages' => (int) $this->input->post('notify_email_messages'),
			'notify_email_photos'   => (int) $this->input->post('notify_email_photos'),
			'notify_email_videos'   => (int) $this->input->post('notify_email_videos'),
			'text_number'           => (int) $this->input->post('text_number'),
			'text_carrier'          => (int) $this->input->post('text_carrier'),
			'notify_text_messages'  => (int) $this->input->post('notify_text_messages'),
			'notify_text_photos'    => (int) $this->input->post('notify_text_photos'),
			'notify_text_videos'    => (int) $this->input->post('notify_text_videos'),
		);

		// See if email exists
		$exists = $this->user_model->get_user_by_email($this->input->post('email'));

		if (!$exists)
		{
			// Save new email in the database
			$data['email'] = $this->input->post('email');
		}

		$this->db->where('user_id', $this->_user->user_id);
		$this->db->update('users', $data);

		// Get updated user details
		$user = $this->get_user();

		return $user;
	}

	/**
	 * Save Profile
	 *
	 * Save the user's profile in the database
	 *
	 * @access public
	 * @return object
	 */
	public function save_profile()
	{
		$data = array(
			'profile'          => $this->input->post('profile'),
		);

		$this->db->where('user_id', $this->_user->user_id);
		$this->db->update('users', $data);

		// Get updated user details
		$user = $this->get_user();

		return $user;
	}

	/**
	 * Forgot Password
	 *
	 * Process a forgot password request and email
	 *
	 * @access public
	 * @return boolean
	 */
	public function password_forgot($user_id)
	{
		// Get user details (for email)
		$user = $this->get_user($user_id);

		// Add reset_hash and reset_expiration to database
		$reset_hash = $this->password_reset($user_id);

		// Email link with reset_hash to user
		$data['reset_url'] = base_url() . 'account/reset/' . $reset_hash;

		// email template
		$message = $this->load->view('emails/password_reset', $data, true);

		$this->emailer_model->send(
			$mail_to         = $user->email,
			$mail_subject    = SITE_TITLE . ' Password Reset',
			$mail_message    = $message,
			$mail_from_email = 'info@babesforbitcoin.com',
			$mail_from_name  = SITE_TITLE,
			$tag             = 'password-resets'
		);

		// Log password_forgot for auditing purposes
		$data = array(
			'user_id'    => $user_id,
			'created'    => time(),
			'action'     => 'password_forgot',
			'ip_address' => $this->input->ip_address(),
		);
		$this->db->insert('users_log', $data);

		return TRUE;
	}

	/**
	 * Verify Reset Hash
	 *
	 * Verify if a reset_hash is valid and also not expired
	 *
	 * @access public
	 * @return array
	 */
	public function verify_reset_hash($reset_hash)
	{
		$this->db->from('users');
		$this->db->where('reset_hash', $reset_hash);
		$query = $this->db->get();
		$row   = $query->row();

		if ($row)
		{
			if ($row->reset_expiration > time())
			{
				// Valid reset_hash and reset_expiration, reset hash in database
				$data = array(
					'reset_hash'       => '',
					'reset_expiration' => '0',
				);
				$this->db->where('user_id', $row->user_id);
				$this->db->update('users', $data);

				// Log them in now that password reset hash not active
				$this->session->set_userdata('user_id', $row->user_id);

				// Update last_login
				$this->db->set('last_login', time());
				$this->db->where('user_id', $row->user_id);
				$this->db->update('users');

				// Log login_success for auditing purposes
				$data = array(
					'user_id'    => $row->user_id,
					'created'    => time(),
					'action'     => 'login_success_via_reset_hash',
					'ip_address' => $this->input->ip_address(),
				);
				$this->db->insert('users_log', $data);

				return $row;
			}
			else
			{
				// reset_expiration has passed (24 hours)
				return FALSE;
			}
		}
		else
		{
			// reset_hash not found (invalid?)
			return FALSE;
		}

	}

	/**
	 * Change Password
	 *
	 * Process a password change
	 *
	 * @access public
	 * @return boolean
	 */
	public function password_change($password)
	{
		// Get logged in user
		$user = $this->get_user();

		// Generate salted password hash
		$hash = $this->password_salt($password);

		// Update the password in the database
		$data = array(
			'password' => $hash,
		);
		$this->db->where('user_id', $user->user_id);
		$this->db->update('users', $data);

		// Log password_changed for auditing purposes
		$data = array(
			'user_id'    => $user->user_id,
			'created'    => time(),
			'action'     => 'password_changed',
			'ip_address' => $this->input->ip_address(),
		);
		$this->db->insert('users_log', $data);

		return TRUE;
	}

	/**
	 * Password Salt
	 *
	 * Return a properly salted password
	 *
	 * @access public
	 * @return string
	 */
	public function password_salt($password)
	{
		// Generate a random salt
		$salt = $this->_token_generate();

		// Generate a hash using password and random salt
		$hash = $this->_password_hash($password, $salt);

		// Append the salt to the end of the hash
		$hash .= ':' . $salt;

		return $hash;
	}

	/**
	 * Get Moderators
	 *
	 * Return a list of moderators
	 *
	 * @access public
	 * @return string
	 */
	public function get_moderators()
	{
		$this->db->from('users');
		$this->db->where('user_type >=', 3);
		$this->db->where('disabled', 0);
		$this->db->where('lockout', 0);
		$this->db->order_by('display_name', 'asc');
		$this->db->order_by('user_id', 'asc');
		$query  = $this->db->get();
		$result = $query->result();

		return $result;
	}

}

/* End of file user_model.php */
/* Location: ./application/models/user_model.php */