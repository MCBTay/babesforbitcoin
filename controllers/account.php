<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Account extends CI_Controller
{

	// Object containing currently logged in user
	public $_user;

	// Allowed pages without login
	private $_allowed = array(
		'login',
		'forgot',
		'reset',
		'register',
		'lockout',
		'faq',
		'preview',
		'legal',
		'tos',
		'privacy',
		'add_funds_card_callback',
		'add_funds_bank_callback',
		'add_funds_btc_callback',
	);

	// Banned email domains
	private $_banned = array(
		'mailinator',
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

		// If user isn't logged in, redirect to login (unless already there)
		if (!$this->_user && !in_array($this->uri->segment(2), $this->_allowed))
		{
			// Redirect to login
			redirect('account/login');
		}
	}

	/**
	 * Account - Index
	 *
	 * The index page for the account controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function index()
	{
		// Data array to be used in views
		$data = array(
			'class' => 'account',
			'title' => 'Account',
		);

		// Load views
		$this->load->view('templates/header',     $data);
		$this->load->view('templates/navigation', $data);
		$this->load->view('pages/account/index',  $data);
		$this->load->view('templates/footer-nav', $data);
		$this->load->view('templates/footer',     $data);
	}

	/**
	 * Account - Register
	 *
	 * The register page for the account controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function register()
	{
		// Data array to be used in views
		$data = array(
			'class' => 'register',
			'title' => 'Register',
		);

		// Set validation error delimiters
		$this->form_validation->set_error_delimiters('', '');

		// Set validation rules
		$this->form_validation->set_rules('user_type',        'Account Type',     'trim|required|greater_than[0]|less_than[3]|xss_clean');
		$this->form_validation->set_rules('display_name',     'Display Name',     'trim|required|min_length[2]|max_length[15]|xss_clean');
		$this->form_validation->set_rules('email',            'Email',            'trim|required|valid_email|callback_banned_domain|xss_clean');
		$this->form_validation->set_rules('password',         'Password',         'required|min_length[8]|callback_complex_password');
		$this->form_validation->set_rules('confirm-password', 'Confirm Password', 'required|matches[password]');
		$this->form_validation->set_rules('date_of_birth',    'Date of Birth',    'required|callback_age_18');
		$this->form_validation->set_rules('agree_terms',      'Agree to Terms',   'required');

		// Run validation
		if ($this->form_validation->run() == TRUE)
		{
			// See if user exists already
			$exists = $this->user_model->get_user_by_email($this->input->post('email'));

			if ($exists)
			{
				$data['exists'] = TRUE;
			}
			else
			{
				// Register user
				$this->user_model->register_user();

				// Redirect home
				redirect();
			}
		}

		$this->load->view('templates/header',           $data);
		$this->load->view('pages/account/register',     $data);
		$this->load->view('templates/footer-nav-login', $data);
		$this->load->view('templates/footer',           $data);
	}

	/**
	 * Account - Login
	 *
	 * The login page for the account controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function login()
	{
		// Load the stats model
		$this->load->model('stats_model');

		// Data array to be used in views
		$data = array(
			'class'  => 'login',
			'title'  => 'Login',
			'models' => $this->stats_model->count_models(),
			'photos' => $this->stats_model->count_photos(),
			'videos' => $this->stats_model->count_videos(),
		);

		// Set validation error delimiters
		$this->form_validation->set_error_delimiters('', '');

		// Set validation rules
		$this->form_validation->set_rules('login-email',    'Email',    'trim|required|valid_email|xss_clean');
		$this->form_validation->set_rules('login-password', 'Password', 'trim|required|xss_clean');

		// Run validation
		if ($this->form_validation->run() == TRUE)
		{
			// Validation passed, attempt login
			if ($this->user_model->login_user())
			{
				// Successful login, redirect to account
				redirect();
			}
			else
			{
				// Invalid login
				$data['error'] = 'Please enter a valid email and password.';
			}
		}

		// Load views
		$this->load->view('templates/header',           $data);
		$this->load->view('pages/account/login',        $data);
		$this->load->view('templates/footer-nav-login', $data);
		$this->load->view('templates/footer',           $data);
	}

	/**
	 * Account - Forgot
	 *
	 * The forgot page for the account controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function forgot()
	{
		// Load the stats model
		$this->load->model('stats_model');

		// Data array to be used in views
		$data = array(
			'class'  => 'login',
			'title'  => 'Login',
			'models' => $this->stats_model->count_models(),
			'photos' => $this->stats_model->count_photos(),
			'videos' => $this->stats_model->count_videos(),
		);

		// Set validation error delimiters
		$this->form_validation->set_error_delimiters('', '');

		// Set validation rules
		$this->form_validation->set_rules('login-email',    'Email',    'trim|required|valid_email|xss_clean');

		// Run validation
		if ($this->form_validation->run() == TRUE)
		{
			// Validation passed, attempt password reset
			$data['success'] = TRUE;

			$user = $this->user_model->get_user_by_email($this->input->post('login-email'));

			if ($user)
			{
				// Add reset_hash and reset_expiration to database
				$reset_hash = $this->user_model->password_reset($user->user_id);

				// Create link for email with reset_hash
				$data['reset_url'] = base_url() . 'account/reset/' . $reset_hash;

				// Email template
				$message = $this->load->view('emails/password_reset', $data, true);

				// Send email
				$this->emailer_model->send(
					$mail_to         = $user->email,
					$mail_subject    = SITE_TITLE . ' Password Reset',
					$mail_message    = $message,
					$mail_from_email = 'info@babesforbitcoin.com',
					$mail_from_name  = SITE_TITLE
				);
			}
		}

		// Load views
		$this->load->view('templates/header',           $data);
		$this->load->view('pages/account/forgot',       $data);
		$this->load->view('templates/footer-nav-login', $data);
		$this->load->view('templates/footer',           $data);
	}

	/**
	 * Account - Reset
	 *
	 * The reset page for the account controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function reset($reset_hash = '1')
	{
		if (!$this->_user)
		{
			// This will return user if valid, and log them in if valid
			$this->_user = $this->user_model->verify_reset_hash($reset_hash);

			if ($this->_user)
			{
				redirect('account/preferences/password');
			}
			else
			{
				// Invalid reset link
				redirect();
			}
		}
		else
		{
			// User already logged in
			redirect();
		}
	}

	/**
	 * Account - Logout
	 *
	 * The logout page for the account controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function logout()
	{
		if ($this->_user)
		{
			// User is logged in, so we can logout
			$this->user_model->logout_user();
		}

		// Redirect to login page
		redirect('account/login');
	}

	/**
	 * Account - Lockout
	 *
	 * The lockout page for the account controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function lockout()
	{
		// Load the stats model
		$this->load->model('stats_model');

		// Data array to be used in views
		$data = array(
			'class' => 'login',
			'title' => 'Login',
			'models' => $this->stats_model->count_models(),
			'photos' => $this->stats_model->count_photos(),
			'videos' => $this->stats_model->count_videos(),
			'error' => 'Your account has been locked.',
		);

		// Load views
		$this->load->view('templates/header',           $data);
		$this->load->view('pages/account/login',        $data);
		$this->load->view('templates/footer-nav-login', $data);
		$this->load->view('templates/footer',           $data);
	}

	/**
	 * Account - FAQ
	 *
	 * The FAQ page for the account controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function faq()
	{
		// Data array to be used in views
		$data = array(
			'class' => 'login',
			'title' => 'Frequently Asked Questions',
		);

		// Load views
		$this->load->view('templates/header',           $data);
		$this->load->view('pages/account/faq',          $data);
		$this->load->view('templates/footer-nav-login', $data);
		$this->load->view('templates/footer',           $data);
	}

	/**
	 * Account - Preview
	 *
	 * The preview page for the account controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function preview()
	{
		// Data array to be used in views
		$data = array(
			'class' => 'login',
			'title' => 'Preview',
		);

		// Load views
		$this->load->view('templates/header',           $data);
		$this->load->view('pages/account/preview',      $data);
		$this->load->view('templates/footer-nav-login', $data);
		$this->load->view('templates/footer',           $data);
	}

	/**
	 * Account - Legal
	 *
	 * The legal page for the account controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function legal()
	{
		// Data array to be used in views
		$data = array(
			'class' => 'login',
			'title' => '&sect;2257 Exemption Statement',
		);

		// Load views
		$this->load->view('templates/header',           $data);
		$this->load->view('pages/account/legal',          $data);
		$this->load->view('templates/footer-nav-login', $data);
		$this->load->view('templates/footer',           $data);
	}

	/**
	 * Account - TOS
	 *
	 * The TOS page for the account controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function tos()
	{
		// Data array to be used in views
		$data = array(
			'class' => 'login',
			'title' => 'Terms of Service',
		);

		// Load views
		$this->load->view('templates/header',           $data);
		$this->load->view('pages/account/tos',          $data);
		$this->load->view('templates/footer-nav-login', $data);
		$this->load->view('templates/footer',           $data);
	}

	/**
	 * Account - Privacy
	 *
	 * The privacy page for the account controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function privacy()
	{
		// Data array to be used in views
		$data = array(
			'class' => 'login',
			'title' => 'Privacy Policy',
		);

		// Load views
		$this->load->view('templates/header',           $data);
		$this->load->view('pages/account/privacy',      $data);
		$this->load->view('templates/footer-nav-login', $data);
		$this->load->view('templates/footer',           $data);
	}

	/**
	 * Account - Preferences
	 *
	 * The preferences page for the account controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function preferences($pw_note = FALSE)
	{
		// Data array to be used in views
		$data = array(
			'class'    => 'preferences',
			'title'    => 'Account Preferences',
			'user'     => $this->_user,
			'carriers' => $this->notifications_model->get_carriers(),
			'pw_note'  => $pw_note,
		);

		// Create a couple variables to help with validation
		$text1 = (int) $this->input->post('notify_text_messages');
		$text2 = (int) $this->input->post('notify_text_photos');
		$text3 = (int) $this->input->post('notify_text_videos');
		$pass  = $this->input->post('change');

		// Set validation error delimiters
		$this->form_validation->set_error_delimiters('', '');

		if (!empty($pass))
		{
			// Set validation rules for change password
			if ($pw_note != 'password')
			{
				$this->form_validation->set_rules('password',         'Current Password', 'required|callback_correct_password');
			}
			$this->form_validation->set_rules('drowssap',         'New Password',     'required');
			$this->form_validation->set_rules('confirm-password', 'Confirm Password', 'required|matches[drowssap]');
		}
		else
		{
			// Set validation rules
			$this->form_validation->set_rules('display_name',       'Display Name',     'trim|required|min_length[2]|max_length[15]|xss_clean');
			$this->form_validation->set_rules('email',              'Email',            'trim|required|valid_email|xss_clean');

			if ($text1 || $text2 || $text3)
			{
				$this->form_validation->set_rules('text_number',      'Cell Number',      'trim|required|min_length[10]|max_length[10]|xss_clean');
				$this->form_validation->set_rules('text_carrier',     'Cell Carrier',     'trim|required|greater_than[0]|xss_clean');
			}
		}

		// Run validation
		if ($this->form_validation->run() == TRUE)
		{
			if (!empty($pass))
			{
				// Save user password
				$this->user_model->password_change($this->input->post('drowssap'));
			}
			else
			{
				// Save user preferences
				$user = $this->user_model->save_preferences();

				// Update the user data with updated values
				$data['user'] = $user;

				// See if we need to send our confirmation text
				if (
					$this->_user->notify_text_messages == 0 &&
					$this->_user->notify_text_photos == 0 &&
					$this->_user->notify_text_videos == 0 &&
					(
						$user->notify_text_messages == 1 ||
						$user->notify_text_photos   == 1 ||
						$user->notify_text_videos   == 1
					)
				)
				{
					// email template
					$message = $this->load->view('emails/new_subscribe_text', array(), true);

					$this->emailer_model->send(
						$mail_to         = $user->text_number . '@' . $this->notifications_model->get_carrier_domain($user->text_carrier),
						$mail_subject    = '',
						$mail_message    = $message,
						$mail_from_email = 'info@babesforbitcoin.com',
						$mail_from_name  = SITE_TITLE,
						$tag             = 'user-notifications'
					);
				}
			}

			$data['success'] = TRUE;
		}
		elseif (count($_POST))
		{
			// Failed validation, update the user data to POSTed values
			$data['user']->display_name          = $this->input->post('display_name');
			$data['user']->email                 = $this->input->post('email');
			$data['user']->notify_email_messages = $this->input->post('notify_email_messages');
			$data['user']->notify_email_photos   = $this->input->post('notify_email_photos');
			$data['user']->notify_email_videos   = $this->input->post('notify_email_videos');
			$data['user']->text_number           = $this->input->post('text_number');
			$data['user']->text_carrier          = $this->input->post('text_carrier');
			$data['user']->notify_text_messages  = $this->input->post('notify_text_messages');
			$data['user']->notify_text_photos    = $this->input->post('notify_text_photos');
			$data['user']->notify_text_videos    = $this->input->post('notify_text_videos');
		}

		// Load views
		$this->load->view('templates/header',          $data);
		$this->load->view('templates/navigation',      $data);
		$this->load->view('pages/account/preferences', $data);
		$this->load->view('templates/footer-nav',      $data);
		$this->load->view('templates/footer',          $data);
	}

	/**
	 * Account - Profile
	 *
	 * The profile page for the account controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function profile()
	{
		// Data array to be used in views
		$data = array(
			'class'    => 'profile',
			'title'    => 'Edit Profile',
			'user'     => $this->_user,
		);

		// Set validation error delimiters
		$this->form_validation->set_error_delimiters('', '');

		// Set validation rules
		$this->form_validation->set_rules('profile', 'Profile', 'trim|required|max_length[5000]|xss_clean');

		// Run validation
		if ($this->form_validation->run() == TRUE)
		{
			// Save user preferences
			$user = $this->user_model->save_profile();

			// Update the user data with updated values
			$data['user'] = $user;

			$data['success'] = TRUE;
		}
		elseif (count($_POST))
		{
			// Failed validation, update the user data to POSTed values
			$data['user']->profile = $this->input->post('profile');
		}

		// Load views
		$this->load->view('templates/header',      $data);
		$this->load->view('templates/navigation',  $data);
		$this->load->view('pages/account/profile', $data);
		$this->load->view('templates/footer-nav',  $data);
		$this->load->view('templates/footer',      $data);
	}

	/**
	 * Account - Add Funds
	 *
	 * The add funds page for the account controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function add_funds()
	{
		// Only contributors should be able to see this page
		if ($this->_user->user_type != 1)
		{
			redirect();
		}

		// Data array to be used in views
		$data = array(
			'class'    => 'add-funds',
			'title'    => 'Add Funds',
			'user'     => $this->_user,
			'rate'     => $this->cart_model->btc_to_usd(1),
		);

		// Set validation error delimiters
		$this->form_validation->set_error_delimiters('', '');

		$funding_type = $this->input->post('funding_type');
		$action       = $this->input->post('action');

		if ($funding_type == 'card')
		{
			// Set validation rules for credit card
			$this->form_validation->set_rules('funding_type', 'Funding Type',     'required');
			$this->form_validation->set_rules('amount_card',  'Amount (USD)',     'required|greater_than[0.99]');
		}
		elseif ($funding_type == 'bank')
		{
			// Set validation rules for bank account
			$this->form_validation->set_rules('funding_type', 'Funding Type', 'required');
			$this->form_validation->set_rules('amount_bank',  'Amount (USD)', 'required|greater_than[0.99]');
		}
		elseif ($funding_type == 'btc')
		{
			// Set validation rules for btc
			$this->form_validation->set_rules('funding_type',   'Funding Type',          'required');
			$this->form_validation->set_rules('amount_btc_usd', 'Amount (USD)',          'required|greater_than[0.99]');
			$this->form_validation->set_rules('convert_to_usd', 'Conversion Preference', 'required');
		}
		elseif ($action == 'convert_btc')
		{
			// Set validation rules
			$this->form_validation->set_rules('convert', 'Amount (&#579;TC)', 'trim|required|greater_than[0.0000009]|less_than[' . $this->_user->funds_btc + 0.0000001 . ']|xss_clean');
		}

		// Run validation
		if ($this->form_validation->run() == TRUE)
		{
			if ($funding_type == 'card')
			{
				$this->cart_model->add_funds_card();
			}
			elseif ($funding_type == 'bank')
			{
				$this->cart_model->add_funds_bank();
			}
			elseif ($funding_type == 'btc')
			{
				// This will redirect the user to coinbase
				$this->cart_model->add_funds_btc();
			}
			elseif ($action == 'convert_btc')
			{
				$convert = $this->input->post('convert');

				if ($convert >= 0.000001 && $convert <= $this->_user->funds_btc)
				{
					// Sell bitcoins
					$response = $this->coinbase_model->coinbase->sell($convert);

					// Calculate site fee
					$site_fee    = round($response->transfer->total->amount * FEE_CONVERT, 2);
					$contrib_usd = $response->transfer->total->amount - $site_fee;

					// Data for conversions table
					$data = array(
						'user_id'     => $this->_user->user_id,
						'cb_code'     => $response->transfer->code,
						'btc_out'     => $response->transfer->btc->amount,
						'usd_in'      => $response->transfer->total->amount,
						'site_fee'    => $site_fee,
						'payout_date' => strtotime($response->transfer->payout_date),
						'created'     => time(),
					);

					// Insert into conversions table
					$this->db->insert('conversions', $data);

					// Update user's funds
					$this->_user->funds_btc = $this->_user->funds_btc - $response->transfer->btc->amount;
					$this->_user->funds_usd = $this->_user->funds_usd + $contrib_usd;
					$this->db->set('funds_btc', $this->_user->funds_btc);
					$this->db->set('funds_usd', $this->_user->funds_usd);
					$this->db->where('user_id', $this->_user->user_id);
					$this->db->update('users');

					redirect('account/add_funds_convert');
				}
			}
		}

		// Load views
		$this->load->view('templates/header',        $data);
		$this->load->view('templates/navigation',    $data);
		$this->load->view('pages/account/add-funds', $data);
		$this->load->view('templates/footer-nav',    $data);
		$this->load->view('templates/footer',        $data);
	}

	/**
	 * Account - Add Funds Convert
	 *
	 * The add funds convert page for the account controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function add_funds_convert()
	{
		// Only contributors should be able to see this page
		if ($this->_user->user_type != 1)
		{
			redirect();
		}

		// Data array to be used in views
		$data = array(
			'class'    => 'add-funds',
			'title'    => 'Add Funds',
			'user'     => $this->_user,
			'rate'     => $this->cart_model->btc_to_usd(1),
			'convert'  => TRUE,
		);

		// Load views
		$this->load->view('templates/header',        $data);
		$this->load->view('templates/navigation',    $data);
		$this->load->view('pages/account/add-funds', $data);
		$this->load->view('templates/footer-nav',    $data);
		$this->load->view('templates/footer',        $data);
	}

	/**
	 * Account - Add Funds Card
	 *
	 * The add funds card page for the account controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function add_funds_card()
	{
		// Only contributors should be able to see this page
		if ($this->_user->user_type != 1)
		{
			redirect();
		}

		// Data array to be used in views
		$data = array(
			'class'    => 'add-funds',
			'title'    => 'Add Funds',
			'user'     => $this->_user,
			'rate'     => $this->cart_model->btc_to_usd(1),
			'status'   => 'Completed',
		);

		// Load views
		$this->load->view('templates/header',        $data);
		$this->load->view('templates/navigation',    $data);
		$this->load->view('pages/account/add-funds', $data);
		$this->load->view('templates/footer-nav',    $data);
		$this->load->view('templates/footer',        $data);
	}

	/**
	 * Account - Add Funds Bank
	 *
	 * The add funds bank page for the account controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function add_funds_bank()
	{
		// Only contributors should be able to see this page
		if ($this->_user->user_type != 1)
		{
			redirect();
		}

		// Data array to be used in views
		$data = array(
			'class'    => 'add-funds',
			'title'    => 'Add Funds',
			'user'     => $this->_user,
			'rate'     => $this->cart_model->btc_to_usd(1),
			'status'   => $this->input->get('status'),
		);

		// Load views
		$this->load->view('templates/header',        $data);
		$this->load->view('templates/navigation',    $data);
		$this->load->view('pages/account/add-funds', $data);
		$this->load->view('templates/footer-nav',    $data);
		$this->load->view('templates/footer',        $data);
	}

	/**
	 * Account - Add Funds Card Callback
	 *
	 * The add funds card callback page for the account controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function add_funds_card_callback()
	{
		$this->cart_model->add_funds_card_process();
	}

	/**
	 * Account - Add Funds Bank Callback
	 *
	 * The add funds bank callback page for the account controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function add_funds_bank_callback()
	{
		$this->cart_model->add_funds_bank_process();
	}

	/**
	 * Account - Add Funds BTC
	 *
	 * The add funds btc page for the account controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function add_funds_btc()
	{
		// Only contributors should be able to see this page
		if ($this->_user->user_type != 1)
		{
			redirect();
		}

		$order = (array) $this->input->get('order');

		// Data array to be used in views
		$data = array(
			'class'    => 'add-funds',
			'title'    => 'Add Funds',
			'user'     => $this->_user,
			'rate'     => $this->cart_model->btc_to_usd(1),
			'status'   => $order['status'],
		);

		// Load views
		$this->load->view('templates/header',        $data);
		$this->load->view('templates/navigation',    $data);
		$this->load->view('pages/account/add-funds', $data);
		$this->load->view('templates/footer-nav',    $data);
		$this->load->view('templates/footer',        $data);
	}

	/**
	 * Account - Add Funds BTC Callback
	 *
	 * The add funds btc callback page for the account controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function add_funds_btc_callback()
	{
		$this->cart_model->add_funds_btc_process();
	}

	/**
	 * Account - Upgrade
	 *
	 * The upgrade page for the account controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function upgrade()
	{
		// Data array to be used in views
		$data = array(
			'class'    => 'upgrade',
			'title'    => 'Upgrade',
			'user'     => $this->_user,
		);

		// Load views
		$this->load->view('templates/header',      $data);
		$this->load->view('templates/navigation',  $data);
		$this->load->view('pages/account/upgrade', $data);
		$this->load->view('templates/footer-nav',  $data);
		$this->load->view('templates/footer',      $data);
	}

	/**
	 * Account - Visitors
	 *
	 * The visitors page for the account controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function visitors()
	{
		// Data array to be used in views
		$data = array(
			'class'    => 'profile-visitors',
			'title'    => 'Profile Visitors',
			'user'     => $this->_user,
		);

		// Load views
		$this->load->view('templates/header',       $data);
		$this->load->view('templates/navigation',   $data);
		$this->load->view('pages/account/visitors', $data);
		$this->load->view('templates/footer-nav',   $data);
		$this->load->view('templates/footer',       $data);
	}

	/**
	 * Account - Earnings
	 *
	 * The earnings page for the account controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function earnings()
	{
		// Only models should be able to see this page
		if ($this->_user->user_type != 2)
		{
			redirect();
		}

		// Data array to be used in views
		$data = array(
			'class'    => 'earnings',
			'title'    => 'My Earnings',
			'user'     => $this->_user,
			'rate'     => $this->cart_model->btc_to_usd(1),
		);

		// Set validation error delimiters
		$this->form_validation->set_error_delimiters('', '');

		$action = $this->input->post('action');

		if ($action == 'withdrawal_usd')
		{
			// Set validation rules
			$this->form_validation->set_rules('amount', 'Amount (USD)', 'trim|required|greater_than[0.99]|less_than[' . $this->_user->funds_usd + 0.01 . ']|xss_clean');
		}
		elseif ($action == 'withdrawal_btc')
		{
			// Set validation rules
			$this->form_validation->set_rules('amount_btc', 'Amount (&#579;TC)', 'trim|required|greater_than[0.0000009]|less_than[' . $this->_user->funds_btc + 0.0000001 . ']|xss_clean');
		}
		elseif ($action == 'convert_btc')
		{
			// Set validation rules
			$this->form_validation->set_rules('convert', 'Amount (&#579;TC)', 'trim|required|greater_than[0.0000009]|less_than[' . $this->_user->funds_btc + 0.0000001 . ']|xss_clean');
		}

		// Run validation
		if ($this->form_validation->run() == TRUE)
		{
			if ($action == 'withdrawal_usd')
			{
				// Temporarily disable this section - no USD withdrawals
				redirect();
				$amount = $this->input->post('amount');

				if ($amount >= 1 && $amount <= $this->_user->funds_usd)
				{
					$site_fee = $amount > 10 ? 1 : 0;

					$withdrawal_id = (int) $this->models_model->make_withdrawal($amount, $site_fee);

					if ($withdrawal_id)
					{
						$this->dwolla_model->initialize($redirectUri = FALSE, $permissions = FALSE, $mode = FALSE, $debugMode = FALSE, $sandboxMode = FALSE);
						$transaction_id = $this->dwolla_model->send(decrypt(DWOLLA_PIN), $this->_user->email, $amount - $site_fee, 'Email', 'Withdrawal of funds from Babes for Bitcoin account.');

						if (!$transaction_id)
						{
							$data['error'] = $this->dwolla_model->getError();

							// Log withdrawal error, refund account
							$this->models_model->update_withdrawal($withdrawal_id, '', $data['error']);
						}
						else
						{
							$data['transaction_id'] = $transaction_id;

							// Log successful withdrawal
							$this->models_model->update_withdrawal($withdrawal_id, $transaction_id);

							// Update _user object so we have newest information
							$this->_user  = $this->user_model->get_user();
							$data['user'] = $this->_user;
						}
					}
				}
			}
			elseif ($action == 'withdrawal_btc')
			{
				$amount_btc = $this->input->post('amount_btc');

				if ($amount_btc >= 0.000001 && $amount_btc <= $this->_user->funds_btc)
				{
					$withdrawal_id = (int) $this->models_model->make_withdrawal_btc($amount_btc);

					if ($withdrawal_id)
					{
						$response = $this->coinbase_model->coinbase->sendMoney($this->_user->email, $amount_btc, 'Withdrawal of funds from Babes for Bitcoin account.');

						$response->transaction->id;

						if ($response->success)
						{
							// Log successful withdrawal
							$this->models_model->update_withdrawal_btc($withdrawal_id, $response->transaction->id);

							// Update _user object so we have newest information
							$this->_user  = $this->user_model->get_user();
							$data['user'] = $this->_user;
						}
						else
						{
							// Log withdrawal error, refund account
							$this->models_model->update_withdrawal_btc($withdrawal_id, $response->transaction->id, $response->errors[0]);
						}
					}
				}
			}
			elseif ($action == 'convert_btc')
			{
				$convert = $this->input->post('convert');

				if ($convert >= 0.000001 && $convert <= $this->_user->funds_btc)
				{
					// Sell bitcoins
					$response = $this->coinbase_model->coinbase->sell($convert);

					// Calculate site fee
					$site_fee  = round($response->transfer->total->amount * FEE_CONVERT, 2);
					$model_usd = $response->transfer->total->amount - $site_fee;

					// Data for conversions table
					$data = array(
						'user_id'     => $this->_user->user_id,
						'cb_code'     => $response->transfer->code,
						'btc_out'     => $response->transfer->btc->amount,
						'usd_in'      => $response->transfer->total->amount,
						'site_fee'    => $site_fee,
						'payout_date' => strtotime($response->transfer->payout_date),
						'created'     => time(),
					);

					// Insert into conversions table
					$this->db->insert('conversions', $data);

					// Update user's funds
					$this->_user->funds_btc = $this->_user->funds_btc - $response->transfer->btc->amount;
					$this->_user->funds_usd = $this->_user->funds_usd + $model_usd;
					$this->db->set('funds_btc', $this->_user->funds_btc);
					$this->db->set('funds_usd', $this->_user->funds_usd);
					$this->db->where('user_id', $this->_user->user_id);
					$this->db->update('users');
				}
			}
		}

		// Load views
		$this->load->view('templates/header',       $data);
		$this->load->view('templates/navigation',   $data);
		$this->load->view('pages/account/earnings', $data);
		$this->load->view('templates/footer-nav',   $data);
		$this->load->view('templates/footer',       $data);
	}

	/**
	 * Account - Referrals
	 *
	 * The referrals page for the account controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function referrals()
	{
		// Only models should be able to see this page
		if ($this->_user->user_type != 2)
		{
			redirect();
		}

		// Data array to be used in views
		$data = array(
			'class'    => 'referrals',
			'title'    => 'Retouch and Referrals',
			'user'     => $this->_user,
		);

		// Load views
		$this->load->view('templates/header',        $data);
		$this->load->view('templates/navigation',    $data);
		$this->load->view('pages/account/referrals', $data);
		$this->load->view('templates/footer-nav',    $data);
		$this->load->view('templates/footer',        $data);
	}

	/**
	 * Account - Correct Password
	 *
	 * The correct password page for the account controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function correct_password($password)
	{
		// Set variables for the hash and salt from the database
		list($db_hash, $db_salt) = explode(':', $this->_user->password, 2);

		// Create hash of user supplied password for comparison
		$hash = $this->user_model->_password_hash($password, $db_salt);

		// See if user supplied hash matches database hash
		if ($hash == $db_hash)
		{
			return TRUE;
		}
		else
		{
			$this->form_validation->set_message('correct_password', 'Your current password was incorrect, please try again.');

			return FALSE;
		}
	}

	/**
	 * Account - Complex Password
	 *
	 * Given password, make sure it is complex enough
	 *
	 * @access public
	 * @return n/a
	 */
	public function complex_password($password)
	{
		if (!preg_match('/^.*[0-9].*$/', $password))
		{
			$this->form_validation->set_message('complex_password', 'Password must contain at least 1 number.');

			return FALSE;
		}
		elseif (!preg_match('/^.*[a-zA-Z].*$/', $password))
		{
			$this->form_validation->set_message('complex_password', 'Password must contain at least 1 letter.');

			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}

	/**
	 * Account - Banned Domain
	 *
	 * Given email, make sure it isn't from a banned domain
	 *
	 * @access public
	 * @return n/a
	 */
	public function banned_domain($email)
	{
		$return = TRUE;

		foreach ($this->_banned as $banned)
		{
			if (strstr($email, $banned))
			{
				$this->form_validation->set_message('banned_domain', 'Emails are not allowed from that domain.');

				$return = FALSE;

				break;
			}
		}

		return $return;
	}

	/**
	 * Account - Age 18
	 *
	 * Given date of birth, make sure user is at least 18 years of age
	 *
	 * @access public
	 * @return n/a
	 */
	public function age_18($date_of_birth)
	{
		$dob     = strtotime($date_of_birth);
		$user_18 = strtotime('+18 years', $dob);
		$time    = time();

		if ($time > $user_18)
		{
			return TRUE;
		}
		else
		{
			$this->form_validation->set_message('age_18', 'You must be at least 18 years of age to use this site.');
			return FALSE;
		}
	}

}

/* End of file account.php */
/* Location: ./application/controllers/account.php */