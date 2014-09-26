<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users extends CI_Controller
{

	// Object containing currently logged in user
	public $_user;

	// Array of user fields allowed to be sorted by
	private $_allowed_sort = array(
		'user_id',
		'display_name',
		'user_type_title',
		'email',
		'last_login',
	);

	// Array of sorting directions allowed
	private $_allowed_dir = array(
		'asc',
		'desc',
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

		// Load management model
		$this->load->model('management_model');

		// Load aws model
		$this->load->model('aws_model');
	}

	/**
	 * Management/Users - Index
	 *
	 * The index page for the management/users controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function index($filter = '0.all.all.all', $sort = 'user_id', $dir = 'desc')
	{
		// Require valid user
		$this->management_model->require_valid_user();

		// Make sure $filter, $sort, and $dir are set
		if ($this->uri->segment(3) == '' || $this->uri->segment(4) == '' || $this->uri->segment(5) == '')
		{
			redirect('management/users/0.all.all.all/user_id/desc');
		}

		// Make sure $filter has all available options
		if (substr_count($filter, '.') != 3)
		{
			redirect('management/users/0.all.all.all/user_id/desc');
		}

		// Make sure $sort is allowed value
		if (!in_array($sort, $this->_allowed_sort))
		{
			redirect('management/users/0.all.all.all/user_id/desc');
		}

		// Make sure $dir is allowed value
		if (!in_array($dir, $this->_allowed_dir))
		{
			redirect('management/users/0.all.all.all/user_id/desc');
		}

		// Setup filter options into proper variables
		list($type, $disabled, $lockout, $approved) = explode('.', $filter);

		// Get total users
		$total = (int) $this->management_model->count_users($filter);

		// Setup pagination config
		$config['base_url']         = base_url() . 'management/users/' . $filter . '/' . $sort . '/' . $dir . '/';
		$config['uri_segment']      = 6;
		$config['num_links']        = 2;
		$config['use_page_numbers'] = TRUE;
		$config['total_rows']       = $total;
		$config['per_page']         = 12;
		$config['first_link']       = '&laquo;';
		$config['first_tag_open']   = '<li>';
		$config['first_tag_close']  = '</li>';
		$config['last_link']        = '&raquo;';
		$config['last_tag_open']    = '<li>';
		$config['last_tag_close']   = '</li>';
		$config['full_tag_open']    = '<ul class="pagination">';
		$config['full_tag_close']   = '</ul>';
		$config['prev_link']        = FALSE;
		$config['next_link']        = FALSE;
		$config['cur_tag_open']     = '<li class="active"><a href="#">';
		$config['cur_tag_close']    = ' <span class="sr-only">(current)</span></a></li>';
		$config['num_tag_open']     = '<li>';
		$config['num_tag_close']    = '</li>';

		// Initialize pagination
		$this->pagination->initialize($config);

		// Data array to be used in views
		$data = array(
			'users'           => $this->management_model->get_users($config['per_page'], $this->uri->segment($config['uri_segment']), $filter, $sort, $dir),
			'types'           => $this->management_model->get_users_types(),
			'pagination'      => $this->pagination->create_links(),
			'total'           => $total,
			'filter'          => $filter,
			'filter_type'     => $type,
			'filter_disabled' => $disabled,
			'filter_lockout'  => $lockout,
			'filter_approved' => $approved,
			'sort'            => $sort,
			'dir'             => $dir,
		);

		// Load views
		$this->load->view('templates/management/header',  $data);
		$this->load->view('pages/management/users/index', $data);
		$this->load->view('templates/management/footer',  $data);
	}

	/**
	 * Management/Users - View
	 *
	 * The view page for the management/users controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function view($user_id)
	{
		// Require valid user
		$this->management_model->require_valid_user();

		// Get the user object from the database
		$user = $this->management_model->get_user($user_id);

		if (!$user)
		{
			// Invalid user
			redirect('management/users');
		}

		// Data array to be used in views
		$data = array(
			'user' => $user,
		);

		// Load views
		$this->load->view('templates/management/header', $data);
		$this->load->view('pages/management/users/view', $data);
		$this->load->view('templates/management/footer', $data);
	}

	/**
	 * Management/Users - Edit
	 *
	 * The edit page for the management/users controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function edit($user_id)
	{
		// Require valid user
		$this->management_model->require_valid_user();

		// Get the user object from the database
		$user = $this->management_model->get_user($user_id);

		if (!$user)
		{
			// Invalid user
			redirect('management/users');
		}

		// Data array to be used in views
		$data = array();

		// Set validation error delimiters
		$this->form_validation->set_error_delimiters('', '');

		// Set validation rules
		$this->form_validation->set_rules('display_name', 'Display Name', 'trim|required|xss_clean');
		$this->form_validation->set_rules('email',        'Email',        'trim|required|valid_email|xss_clean');

		// Run validation
		if ($this->form_validation->run() == FALSE)
		{
			// Validation failed
			$data['success'] = FALSE;

			// See if the user has actually POSTed values
			if (count($_POST))
			{
				// Override database values with POSTed values
				$user->user_type        = (int) $this->input->post('user_type');
				$user->display_name     = $this->input->post('display_name');
				$user->email            = $this->input->post('email');
				$user->reset_hash       = $this->input->post('reset_hash');
				$user->reset_expiration = strtotime($this->input->post('reset_expiration'));
				$user->disabled         = (int) $this->input->post('disabled');
				$user->lockout          = (int) $this->input->post('lockout');
				$user->user_approved    = (int) $this->input->post('user_approved');
				$user->tags             = $this->input->post('tags');
				$user->profile          = $this->input->post('profile');
			}
		}
		else
		{
			// Set the upload config
			$config['upload_path']   = './assets/uploads/';
			$config['allowed_types'] = 'gif|jpg|png';
			$config['max_size']      = '10240'; // 10MB (in kilobytes)
			$config['encrypt_name']  = TRUE;

			// Initialize the upload with config
			$this->upload->initialize($config);

			if (!$this->upload->do_upload('admin_thumb'))
			{
				// Upload error
				$data['error'] = $this->upload->display_errors('', '');

				// Make sure admin_thumb isn't required
				if ($data['error'] == 'You did not select a file to upload.')
				{
					$data['success'] = TRUE;

					// Remove error since it isn't required
					unset($data['error']);

					// Update values in database
					$user = $this->management_model->edit_user($user_id);
				}
				else
				{
					$data['success'] = FALSE;
				}
			}
			else
			{
				// Validation passed, upload good
				$data['success'] = TRUE;

				// Upload success
				$thumb = $this->upload->data();

				// Create thumbnails
				$this->upload_model->create_admin_thumb($thumb);

				// Move to AWS CDN
				$this->aws_model->move_file($thumb['file_path'], $thumb['file_name']);

				// Update values in database
				$user = $this->management_model->edit_user($user_id, $thumb['file_name']);
			}
		}

		// More values for data array to be used in views
		$data['user']     = $user;
		$data['types']    = $this->management_model->get_users_types();
		$data['carriers'] = $this->notifications_model->get_carriers();

		// Load views
		$this->load->view('templates/management/header', $data);
		$this->load->view('pages/management/users/edit', $data);
		$this->load->view('templates/management/footer', $data);
	}

	/**
	 * Management/Users - Approve
	 *
	 * The approve page for the management/users controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function approve($user_id)
	{
		// Require valid user
		$this->management_model->require_valid_user();

		$this->management_model->approve_user($user_id);

		redirect('management/users/view/' . $user_id);
	}

	/**
	 * Management/Users - Add
	 *
	 * The add page for the management/users controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function add()
	{
		// Require valid user
		$this->management_model->require_valid_user();

		if ($this->_user->user_type != 4)
		{
			// Only Administrators should be able to add users
			redirect('management/users');
		}

		// Data array to be used in views
		$data = array();

		// Set validation error delimiters
		$this->form_validation->set_error_delimiters('', '');

		// Set validation rules
		$this->form_validation->set_rules('user_type',     'Type',         'trim|required|xss_clean');
		$this->form_validation->set_rules('display_name',  'Display Name', 'trim|required|xss_clean');
		$this->form_validation->set_rules('email',         'Email',        'trim|required|valid_email|xss_clean');
		$this->form_validation->set_rules('drowssap',      'Password',     'required');
		$this->form_validation->set_rules('user_hd',       'Set as HD',    'trim|required|xss_clean');
		$this->form_validation->set_rules('accept_btc',    'Accept BTC',   'trim|required|xss_clean');
		$this->form_validation->set_rules('prefer_btc',    'Prefer BTC',   'trim|required|xss_clean');
		$this->form_validation->set_rules('trusted',       'Trusted',      'trim|required|xss_clean');
		$this->form_validation->set_rules('featured',      'Front Page',   'trim|required|xss_clean');
		$this->form_validation->set_rules('disabled',      'Hidden',       'trim|required|xss_clean');
		$this->form_validation->set_rules('lockout',       'Locked Out',   'trim|required|xss_clean');
		$this->form_validation->set_rules('user_approved', 'Approved',     'trim|required|xss_clean');

		// Run validation
		if ($this->form_validation->run() == TRUE)
		{
			// Set the upload config
			$config['upload_path']   = './assets/uploads/';
			$config['allowed_types'] = 'gif|jpg|png';
			$config['max_size']      = '10240'; // 10MB (in kilobytes)
			$config['encrypt_name']  = TRUE;

			// Initialize the upload with config
			$this->upload->initialize($config);

			if (!$this->upload->do_upload('admin_thumb'))
			{
				// Upload error
				$data['error'] = $this->upload->display_errors('', '');

				// Make sure admin_thumb isn't required
				if ($data['error'] == 'You did not select a file to upload.')
				{
					// Remove error since it isn't required
					unset($data['error']);
				}
			}
			else
			{
				// Upload success
				$thumb = $this->upload->data();

				// Create thumbnails
				$this->upload_model->create_admin_thumb($thumb);

				// Move to AWS CDN
				$this->aws_model->move_file($thumb['file_path'], $thumb['file_name']);
			}

			if (!isset($data['error']))
			{
				// Update values in database
				if (isset($thumb))
				{
					$user_id = $this->management_model->add_user($thumb['file_name']);
				}
				else
				{
					$user_id = $this->management_model->add_user();
				}

				redirect('management/users/view/' . $user_id);
			}
		}

		// More values for data array to be used in views
		$data['types'] = $this->management_model->get_users_types();

		// Load views
		$this->load->view('templates/management/header', $data);
		$this->load->view('pages/management/users/add',  $data);
		$this->load->view('templates/management/footer', $data);
	}

	/**
	 * Management/Users - Transactions
	 *
	 * The transactions page for the management/users controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function transactions($user_id)
	{
		// Load the stats model
		$this->load->model('stats_model');

		// Require valid user
		$this->management_model->require_valid_user();

		// Get the user object from the database
		$user = $this->management_model->get_user($user_id);

		if (!$user)
		{
			// Invalid user
			redirect('management/users');
		}

		// Data array to be used in views
		$data = array(
			'user'        => $user,
			'orders'      => $this->stats_model->get_orders($user_id),
			'purchases'   => $this->stats_model->get_purchases($user_id),
			'withdrawals' => $this->stats_model->get_withdrawals($user_id),
			'conversions' => $this->stats_model->get_conversions($user_id),
		);

		// Load views
		$this->load->view('templates/management/header',         $data);
		$this->load->view('pages/management/users/transactions', $data);
		$this->load->view('templates/management/footer',         $data);
	}

	/**
	 * Management/Users - Gallery
	 *
	 * The gallery page for the management/users controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function gallery($user_id)
	{
		// Require valid user
		$this->management_model->require_valid_user();

		// Get the user object from the database
		$user = $this->management_model->get_user($user_id);

		if (!$user)
		{
			// Invalid user
			redirect('management/users');
		}

		// Data array to be used in views
		$data = array(
			'user'  => $user,
			'stats' => $this->management_model->user_asset_stats($user_id),
		);

		// Load views
		$this->load->view('templates/management/header',          $data);
		$this->load->view('pages/management/users/gallery/index', $data);
		$this->load->view('templates/management/footer',          $data);
	}

	/**
	 * Management/Users - Gallery/View
	 *
	 * The gallery/view page for the management/users controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function gallery_view($user_id, $asset_type)
	{
		// Require valid user
		$this->management_model->require_valid_user();

		// Get the user object from the database
		$user = $this->management_model->get_user($user_id);

		if (!$user)
		{
			// Invalid user
			redirect('management/users');
		}

		if ($asset_type > 2 && $user->user_type != 2)
		{
			// Only models have photosets/videos
			redirect('management/users');
		}

		if (!in_array($asset_type, array(1, 2, 3, 5)))
		{
			// Not a valid asset type
			redirect('management/users');
		}

		// Data array to be used in views
		$data = array(
			'user'     => $user,
			'type'     => $asset_type,
			'assets'   => $this->management_model->user_assets($user_id, $asset_type),
			'category' => $this->management_model->get_assets_types_title($asset_type),
		);

		// Load views
		$this->load->view('templates/management/header',         $data);
		$this->load->view('pages/management/users/gallery/view', $data);
		$this->load->view('templates/management/footer',         $data);
	}

	/**
	 * Management/Users - Gallery/Approve
	 *
	 * The gallery/approve page for the management/users controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function gallery_approve($user_id, $asset_id)
	{
		// Require valid user
		$this->management_model->require_valid_user();

		$asset = $this->management_model->approve_asset($asset_id);

		redirect('management/users/gallery/' . $user_id . '/view/' . $asset->asset_type);
	}

	/**
	 * Management/Users - Gallery/Add
	 *
	 * The gallery/add page for the management/users controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function gallery_add($user_id, $asset_type, $photoset_id = 0)
	{
		// Require valid user
		$this->management_model->require_valid_user();

		// Get the user object from the database
		$user = $this->management_model->get_user($user_id);

		if (!$user)
		{
			// Invalid user
			redirect('management/users');
		}

		if ($asset_type > 2 && $user->user_type != 2)
		{
			// Only models have photosets/videos
			redirect('management/users');
		}

		if (!preg_match('/^[1-5]$/', $asset_type))
		{
			// Not a valid asset type
			redirect('management/users');
		}

		// Data array to be used in views
		$data = array();

		// Set validation error delimiters
		$this->form_validation->set_error_delimiters('', '');

		// Set validation rules
		if ($asset_type == 3 || $asset_type == 5)
		{
			// Only required for photosets and videos
			$this->form_validation->set_rules('asset_title', 'Title', 'trim|required|xss_clean');
			$this->form_validation->set_rules('asset_cost',  'Cost',  'trim|required|callback_is_monetary|xss_clean');
		}
		else
		{
			$this->form_validation->set_rules('asset_title', 'Title', 'trim|xss_clean');
		}

		// Run validation
		if ($this->form_validation->run() == TRUE)
		{
			// Set the upload config
			$config['upload_path']   = './assets/uploads/';
			$config['allowed_types'] = 'gif|jpg|png';
			$config['max_size']      = '10240'; // 10MB (in kilobytes)
			$config['encrypt_name']  = TRUE;

			// Initialize the upload with config
			$this->upload->initialize($config);

			if (!$this->upload->do_upload('filename'))
			{
				// Upload error
				$data['error'] = $this->upload->display_errors('', '');

				// Make sure this isn't required if asset_type is video
				if ($data['error'] == 'You did not select a file to upload.' && $asset_type == 5)
				{
					// Remove error since it isn't required
					unset($data['error']);
				}
			}
			else
			{
				// Upload success
				$file = $this->upload->data();

				if (empty($_POST['asset_title']))
				{
					$_POST['asset_title'] = $file['orig_name'];
				}
			}

			if (!isset($data['error']))
			{
				// Do we need to upload video too?
				if ($asset_type == 5)
				{
					// Set the upload config
					$config['upload_path']   = './assets/uploads/';
					$config['allowed_types'] = 'mpeg|mpg|mp4|m4v|f4v|webm|flv|ogv|wmv';
					$config['max_size']      = '2097152'; // 2GB (in kilobytes)
					$config['encrypt_name']  = TRUE;

					// Initialize the upload with config
					$this->upload->initialize($config);

					if (!$this->upload->do_upload('video'))
					{
						// Upload error
						$data['verror'] = $this->upload->display_errors('', '');
					}
					else
					{
						// Upload success
						$video = $this->upload->data();

						if (!isset($file))
						{
							// Capture image from video for cover photo
							shell_exec(BIN_PATH . 'ffmpeg -i "' . $video['full_path'] . '" -an -ss 1.001 -y -f mjpeg "' . $video['file_path'] . $video['raw_name'] . '-thumb.jpg' . '" 2>&1');

							// Get the new image width and height
							list($width, $height) = @getimagesize($video['file_path'] . $video['raw_name'] . '-thumb.jpg');

							// Save file information
							$file = array(
								'full_path'    => $video['file_path'] . $video['raw_name'] . '-thumb.jpg',
								'file_path'    => $video['file_path'],
								'file_name'    => $video['raw_name'] . '-thumb.jpg',
								'image_width'  => $width,
								'image_height' => $height,
							);
						}

						// Move to AWS CDN
						$this->aws_model->move_file($video['file_path'], $video['file_name']);
					}
				}

				if ($asset_type == 5 && !isset($video))
				{
					if (isset($file))
					{
						// Error uploading video, delete cover photo
						unlink($file['file_path'] . $file['file_name']);
					}
				}
				else
				{
					// Create thumbnails
					$this->upload_model->create_thumbnails($file);

					// Move to AWS CDN
					$this->aws_model->move_file($file['file_path'], $file['file_name']);
					$this->aws_model->move_file($file['file_path'], 'lrg-'  . strtolower($file['file_name']));
					$this->aws_model->move_file($file['file_path'], 'med-'  . strtolower($file['file_name']));
					$this->aws_model->move_file($file['file_path'], 'sml-'  . strtolower($file['file_name']));
					$this->aws_model->move_file($file['file_path'], 'tall-' . strtolower($file['file_name']));

					// Add asset to the database
					if (isset($video))
					{
						$asset = $this->management_model->add_asset($user_id, $asset_type, $photoset_id, $file['file_name'], $video['file_name']);
					}
					else
					{
						$asset = $this->management_model->add_asset($user_id, $asset_type, $photoset_id, $file['file_name']);
					}

					// Determine best place to redirect the user
					if ($asset_type == 4)
					{
						$redirect = 'management/users/gallery/' . $user_id . '/view/3';
					}
					else
					{
						$redirect = 'management/users/gallery/' . $user_id . '/view/' . $asset_type;
					}

					// Redirect
					redirect($redirect);
				}
			}
		}

		// More values for data array to be used in views
		$data['user']        = $user;
		$data['type']        = $asset_type;
		$data['photoset_id'] = $photoset_id;
		$data['category']    = $this->management_model->get_assets_types_title($asset_type);

		// Load views
		$this->load->view('templates/management/header',        $data);
		$this->load->view('pages/management/users/gallery/add', $data);
		$this->load->view('templates/management/footer',        $data);
	}

	/**
	 * Management/Users - Gallery/Delete
	 *
	 * The gallery/delete page for the management/users controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function gallery_delete($user_id)
	{
		// Require valid user
		$this->management_model->require_valid_user();

		// Delete the asset from the database
		$this->management_model->delete_gallery($user_id);

		// Gallery deleted, send back to gallery page
		redirect('management/users/gallery/' . $user_id);
	}

	/**
	 * Management/Users - Delete
	 *
	 * The delete page for the management/users controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function delete($user_id)
	{
		// Require valid user
		$this->management_model->require_valid_user();

		if ($this->_user->user_type != 4)
		{
			// Only Administrators should be able to delete users
			redirect('management/users');
		}

		// Delete the user from the database
		$this->management_model->delete_user($user_id);

		// User deleted, send back to users page
		redirect('management/users');
	}

	/**
	 * Users - Is Monetary
	 *
	 * The is monetary page for the users controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function is_monetary($amount)
	{
		// Can be #+.##, #+, or .##
		if (preg_match('/^([0-9]+\.[0-9]{2}|[0-9]+|\.[0-9]{2})$/', $amount))
		{
			return TRUE;
		}
		else
		{
			$this->form_validation->set_message('is_monetary', 'Please enter a valid cost.');

			return FALSE;
		}
	}

}

/* End of file users.php */
/* Location: ./application/controllers/management/users.php */