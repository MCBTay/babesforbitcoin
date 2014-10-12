<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Assets extends CI_Controller
{

	// Object containing currently logged in user
	public $_user;

	// Array of asset fields allowed to be sorted by
	private $_allowed_sort = array(
		'asset_id',
		'asset_title',
		'asset_type_title',
		'display_name',
		'approved',
		'purchased',
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
	 * Management/Assets - Index
	 *
	 * The index page for the management/assets controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function index($filter = '0.all.all.all', $sort = 'approved', $dir = 'asc')
	{
		// Require valid user
		$this->management_model->require_valid_user();

		// Make sure $filter, $sort, and $dir are set
		if ($this->uri->segment(3) == '' || $this->uri->segment(4) == '' || $this->uri->segment(5) == '')
		{
			redirect('management/assets/0.all.all.all/approved/asc');
		}

		// Make sure $filter has all available options
		if (substr_count($filter, '.') != 3)
		{
			redirect('management/assets/0.all.all.all/approved/asc');
		}

		// Make sure $sort is allowed value
		if (!in_array($sort, $this->_allowed_sort))
		{
			redirect('management/assets/0.all.all.all/approved/asc');
		}

		// Make sure $dir is allowed value
		if (!in_array($dir, $this->_allowed_dir))
		{
			redirect('management/assets/0.all.all.all/approved/asc');
		}

		// Setup filter options into proper variables
		list($type, $default, $deleted, $approved) = explode('.', $filter);

		// Get total assets
		$total = (int) $this->management_model->count_assets($filter);

		// Setup pagination config
		$config['base_url']         = base_url() . 'management/assets/' . $filter . '/' . $sort . '/' . $dir . '/';
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
			'assets'          => $this->management_model->get_assets($config['per_page'], $this->uri->segment($config['uri_segment']), $filter, $sort, $dir),
			'types'           => $this->management_model->get_assets_types(),
			'pagination'      => $this->pagination->create_links(),
			'total'           => $total,
			'filter'          => $filter,
			'filter_type'     => $type,
			'filter_default'  => $default,
			'filter_deleted'  => $deleted,
			'filter_approved' => $approved,
			'sort'            => $sort,
			'dir'             => $dir,
		);

		// Load views
		$this->load->view('templates/management/header',   $data);
		$this->load->view('pages/management/assets/index', $data);
		$this->load->view('templates/management/footer',   $data);
	}

	/**
	 * Management/Assets - Edit
	 *
	 * The edit page for the management/assets controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function edit($asset_id)
    {
        // Require valid user
        $this->management_model->require_valid_user();

        $asset = $this->management_model->get_asset($asset_id);


		if (!$asset)
		{
			// Invalid asset
			redirect('management/assets');
		}

		// Data array to be used in views
		$data = array();

		// Set validation error delimiters
		$this->form_validation->set_error_delimiters('', '');

		// Set validation rules
		$this->form_validation->set_rules('asset_title', 'Title', 'trim|required|xss_clean');
		if ($asset->asset_type == 3 || $asset->asset_type == 5)
		{
			$this->form_validation->set_rules('asset_cost',  'Cost',  'trim|required|callback_is_monetary|xss_clean');
		}

		// Run validation
		if ($this->form_validation->run() == FALSE)
		{
			// Validation failed
			$data['success'] = FALSE;

			// See if the user has actually POSTed values
			if (count($_POST))
			{
				// Override database values with POSTed values
				$asset->asset_title = $this->input->post('asset_title');
				$asset->default     = (int) $this->input->post('default');
				$asset->deleted     = (int) $this->input->post('deleted');
				$asset->approved    = (int) $this->input->post('approved');
				$asset->asset_hd    = (int) $this->input->post('asset_hd');
			}
		}
		else
		{
			// Validation passed
			$data['success'] = TRUE;

			// Update values in database
			$asset = $this->management_model->edit_asset($asset_id);
		}

		// More values for data array to be used in views
		$data['asset'] = $asset;

		// Load views
		$this->load->view('templates/management/header',  $data);
		$this->load->view('pages/management/assets/edit', $data);
		$this->load->view('templates/management/footer',  $data);
	}

	/**
	 * Management/Assets - Delete
	 *
	 * The delete page for the management/assets controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function delete($asset_id)
	{
		// Require valid user
		$this->management_model->require_valid_user();

		// Remove asset from CDN
		$this->aws_model->delete_asset($asset_id);

		// Delete the asset from the database
		$this->management_model->delete_asset($asset_id);

		// Asset deleted, send back to list of assets
		redirect('management/assets');
	}

	/**
	 * Assets - Is Monetary
	 *
	 * The is monetary page for the assets controller
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

/* End of file assets.php */
/* Location: ./application/controllers/management/assets.php */