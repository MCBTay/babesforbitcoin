<?php if (! defined('BASEPATH')) exit('No direct script access');

class Management_model extends CI_Model
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
	 * Require Valid User
	 *
	 * Ensure that the currently logged in user has access to management
	 *
	 * @access public
	 * @return n/a
	 */
	public function require_valid_user()
	{
		if (!$this->_user)
		{
			// User needs to be logged in to view this page
			redirect('management/account');
		}

		if ($this->_user->user_type < 3)
		{
			// Not a moderator or administrator
			redirect();
		}

		if ($this->_user->lockout == 1)
		{
			// User has been locked out
			redirect('management/account/lockout');
		}
	}

	/**
	 * Get Users
	 *
	 * Get all users from database
	 *
	 * @access public
	 * @return array
	 */
	public function get_users($per_page = 1, $page_num = 1, $filter = '0.all.all.all', $sort = 'user_id', $dir = 'desc')
	{
		// Make sure $page_num is positive
		if ($page_num < 1)
		{
			$page_num = 1;
		}

		// Calculate offset
		$offset = $per_page * $page_num - $per_page;

		// Setup SQL Join to get user_type_title
		$this->db->from('users');
		$this->db->join('users_types', 'users_types.user_type_id = users.user_type');

		// Setup filter options into proper variables
		list($type, $disabled, $lockout, $approved) = explode('.', $filter);

		// If $type is set, limit to only those user_types
		if ($type > 0)
		{
			$this->db->where('user_type', $type);
		}

		// If $disabled is set, limit to only those users
		if ($disabled === '0' || $disabled === '1')
		{
			$this->db->where('disabled', $disabled);
		}

		// If $lockout is set, limit to only those users
		if ($lockout === '0' || $lockout === '1')
		{
			$this->db->where('lockout', $lockout);
		}

		// If $approved is set, limit to only those users
		if ($approved === '0' || $approved === '1')
		{
			$this->db->where('user_approved', $approved);
		}

		// If user is moderator, only show contributors and models
		if ($this->_user->user_type == 3)
		{
			$this->db->where('user_type <', 3);
		}

		// Finish up with sorting and limiting
		$this->db->order_by($sort, $dir);
		if ($per_page > 0)
		{
			$this->db->limit($per_page, $offset);
		}
		$query  = $this->db->get();
		$result = $query->result();

		// Let's add moderator names to approved users
		foreach ($result as $key => $row)
		{
			if ($row->user_approved_by > 0)
			{
				$this->db->select('display_name');
				$this->db->from('users');
				$this->db->where('user_id', $row->user_approved_by);
				$query = $this->db->get();
				$sub   = $query->row();
				$result[$key]->user_approved_by_name = $sub->display_name;
			}
			else
			{
				$result[$key]->user_approved_by_name = '';
			}
		}

		return $result;
	}

	/**
	 * Get User
	 *
	 * Get a specific user from database
	 *
	 * @access public
	 * @return object
	 */
	public function get_user($user_id = 0)
	{
		$this->db->from('users');
		$this->db->join('users_types', 'users_types.user_type_id = users.user_type');
		$this->db->join('carriers', 'users.text_carrier = carriers.carrier_id', 'left');
		$this->db->where('user_id', $user_id);
		if ($this->_user->user_type == 3)
		{
			// Moderators can only see contributors and models
			$this->db->where('user_type_id <', 3);
		}
		$query = $this->db->get();
		$row   = $query->row();

		// Set empty tags variable
		$row->tags = '';

		// Add in existing tags
		$this->db->from('users_tags');
		$this->db->join('tags', 'tags.tag_id = users_tags.tag_id');
		$this->db->where('user_id', $user_id);
		$this->db->order_by('user_tag_id', 'asc');
		$query  = $this->db->get();
		$result = $query->result();

		foreach ($result as $tag)
		{
			$row->tags .= $tag->tag . ', ';
		}

		// Let's add moderator name if approved user
		if ($row->user_approved_by > 0)
		{
			$this->db->select('display_name');
			$this->db->from('users');
			$this->db->where('user_id', $row->user_approved_by);
			$query = $this->db->get();
			$sub   = $query->row();
			$row->user_approved_by_name = $sub->display_name;
		}
		else
		{
			$row->user_approved_by_name = '';
		}

		return $row;
	}

	/**
	 * Edit User
	 *
	 * Edit a specific user in the database
	 *
	 * @access public
	 * @return object
	 */
	public function edit_user($user_id = 0, $admin_thumb = '')
	{
		// Get original user for reference
		$user = $this->get_user($user_id);

		// Get the selected user_type
		$user_type = (int) $this->input->post('user_type');

		if ($this->_user->user_type == 3 && $user_type >= 3)
		{
			// Only Administrators should be able to attempt this action
			redirect('management/users');
		}

		// Data array of items to update from POST in database
		$data = array(
			'user_type'             => (int) $this->input->post('user_type'),
			'display_name'          => $this->input->post('display_name'),
			'email'                 => $this->input->post('email'),
			'reset_hash'            => $this->input->post('reset_hash'),
			'reset_expiration'      => strtotime($this->input->post('reset_expiration')),
			'text_number'           => (int) $this->input->post('text_number'),
			'text_carrier'          => (int) $this->input->post('text_carrier'),
			'notify_email_messages' => (int) $this->input->post('notify_email_messages'),
			'notify_email_photos'   => (int) $this->input->post('notify_email_photos'),
			'notify_email_videos'   => (int) $this->input->post('notify_email_videos'),
			'notify_text_messages'  => (int) $this->input->post('notify_text_messages'),
			'notify_text_photos'    => (int) $this->input->post('notify_text_photos'),
			'notify_text_videos'    => (int) $this->input->post('notify_text_videos'),
			'user_hd'               => (int) $this->input->post('user_hd'),
			'accept_btc'            => (int) $this->input->post('accept_btc'),
			'prefer_btc'            => (int) $this->input->post('prefer_btc'),
			'trusted'               => (int) $this->input->post('trusted'),
			'featured'              => (int) $this->input->post('featured'),
			'disabled'              => (int) $this->input->post('disabled'),
			'lockout'               => (int) $this->input->post('lockout'),
			'user_approved'         => (int) $this->input->post('user_approved'),
			'profile'               => $this->input->post('profile'),
		);

		// Only administrators can adjust funds
		if ($this->_user->user_type == 4)
		{
			$data['funds_btc'] = (float) $this->input->post('funds_btc');
			$data['funds_usd'] = (float) $this->input->post('funds_usd');
		}

		// Only models need to be approved
		if ($data['user_type'] != 2)
		{
			$data['user_approved'] = 1;
		}

		// If approving this, we need to set approved fields
		if ($user->user_approved == 0 && $data['user_approved'] == 1)
		{
			$data['user_approved_by'] = $this->_user->user_id;
			$data['user_approved_on'] = time();
		}

		// If admin_thumb isn't empty, set it
		if (!empty($admin_thumb))
		{
			$data['admin_thumb'] = $admin_thumb;
		}

		// Get new password
		$password = $this->input->post('drowssap');

		// Update password if it's not empty
		if (!empty($password))
		{
			// Update the password in the database
			$data['password'] = $this->user_model->password_salt($password);
		}

		// Process database update
		$this->db->where('user_id', $user_id);
		$this->db->update('users', $data);

		// See if we need to initiate a password reset
		$password_reset = $this->input->post('password_reset');

		if ($password_reset)
		{
			// Add reset_hash and reset_expiration to database
			$reset_hash = $this->user_model->password_reset($user_id);

			// Create link for email with reset_hash
			$data['reset_url'] = base_url() . 'account/reset/' . $reset_hash;

			// Email template
			$message = $this->load->view('emails/password_reset', $data, true);

			// Send email
			$this->emailer_model->send(
				$mail_to         = $data['email'],
				$mail_subject    = SITE_TITLE . ' Password Reset',
				$mail_message    = $message,
				$mail_from_email = 'info@babesforbitcoin.com',
				$mail_from_name  = SITE_TITLE
			);
		}

		// Get tags
		$tags = $this->input->post('tags');

		// Process tags-related updates
		$this->process_tags('user', $user_id, $tags);

		// Get updated user to return to controller
		$user = $this->get_user($user_id);

		return $user;
	}

	/**
	 * Approve User
	 *
	 * Approve a user in the database
	 *
	 * @access public
	 * @return object
	 */
	public function approve_user($user_id)
	{
		$data = array(
			'user_approved'    => 1,
			'user_approved_by' => $this->_user->user_id,
			'user_approved_on' => time(),
		);
		$this->db->where('user_id', $user_id);
		$this->db->update('users', $data);
	}

	/**
	 * Add User
	 *
	 * Add a new user to the database
	 *
	 * @access public
	 * @return object
	 */
	public function add_user($admin_thumb = '')
	{
		// Get the selected user_type
		$user_type = (int) $this->input->post('user_type');

		if ($this->_user->user_type == 3 && $user_type >= 3)
		{
			// Only Administrators should be able to attempt this action
			redirect('management/users');
		}

		// Data array of items to update from POST in database
		$data = array(
			'user_type'     => $user_type,
			'display_name'  => $this->input->post('display_name'),
			'admin_thumb'   => $admin_thumb,
			'email'         => $this->input->post('email'),
			'password'      => $this->user_model->password_salt($this->input->post('drowssap')),
			'user_hd'       => (int) $this->input->post('user_hd'),
			'accept_btc'    => (int) $this->input->post('accept_btc'),
			'prefer_btc'    => (int) $this->input->post('prefer_btc'),
			'trusted'       => (int) $this->input->post('trusted'),
			'featured'      => (int) $this->input->post('featured'),
			'disabled'      => (int) $this->input->post('disabled'),
			'lockout'       => (int) $this->input->post('lockout'),
			'user_approved' => (int) $this->input->post('user_approved'),
			'created'       => time(),
		);

		// Only models need to be approved
		if ($user_type != 2)
		{
			$data['user_approved'] = 1;
		}

		// If approving this, we need to set approved fields
		if ($data['user_approved'] == 1)
		{
			$data['user_approved_by'] = $this->_user->user_id;
			$data['user_approved_on'] = time();
		}

		// Process database insert
		$this->db->insert('users', $data);

		// Get new user_id
		$user_id = $this->db->insert_id();

		// Get tags
		$tags = $this->input->post('tags');

		// Process tags-related updates
		$this->process_tags('user', $user_id, $tags);

		return $user_id;
	}

	/**
	 * Get Featured
	 *
	 * Get all featured models from database
	 *
	 * @access public
	 * @return array
	 */
	public function get_featured()
	{
		// Get data from users table
		$this->db->from('users');

		// Where user_type is Model
		$this->db->where('user_type', 2);

		// Where user is set to be featured
		$this->db->where('featured', 1);

		// Where user isn't disabled
		$this->db->where('disabled', 0);

		// Where user is approved
		$this->db->where('user_approved', 1);

		// Sort by featured_sort asc
		$this->db->order_by('featured_sort', 'asc');

		// Run the query
		$query = $this->db->get();

		// Get the results
		$result = $query->result();

		return $result;
	}

	/**
	 * Save Featured Sort
	 *
	 * Save the sort order of the featured models
	 *
	 * @access public
	 * @return array
	 */
	public function save_featured_sort($sort)
	{
		// Loop through array of sorted user_id's
		foreach ($sort as $key => $user_id)
		{
			// Set featured_sort value
			$this->db->set('featured_sort', $key);

			// Where user_id is given user_id
			$this->db->where('user_id', $user_id);

			// Where user_type is Model
			$this->db->where('user_type', 2);

			// Where user is set to be featured
			$this->db->where('featured', 1);

			// Where user isn't disabled
			$this->db->where('disabled', 0);

			// Where user is approved
			$this->db->where('user_approved', 1);

			// Perform the update
			$this->db->update('users');
		}
	}

	/**
	 * User Assets
	 *
	 * Get a specific user's assets
	 *
	 * @access public
	 * @return array
	 */
	public function user_assets($user_id, $asset_type)
	{
		// Get the values from the database
		$this->db->from('assets');
		$this->db->where('user_id', $user_id);

        //hacky workaround for no longer having asset types of 3 on new photosets
        if ($asset_type == 3)
        {
            $where_asset_type = "(asset_type = 3 OR asset_type = 4)";
            $this->db->where($where_asset_type);
            $this->db->where('is_cover_photo', 1);
        }
        else
        {
            $this->db->where('asset_type', $asset_type);
        }

		$this->db->order_by('asset_id', 'desc');
		$query  = $this->db->get();
		$result = $query->result();

		// Let's add moderator names to approved entries
		foreach ($result as $key => $row)
		{
			if ($row->approved_by > 0)
			{
				$this->db->select('display_name');
				$this->db->from('users');
				$this->db->where('user_id', $row->approved_by);
				$query = $this->db->get();
				$sub   = $query->row();
				$result[$key]->approved_by_name = $sub->display_name;
			}
			else
			{
				$result[$key]->approved_by_name = '';
			}
		}

		return $result;
	}

	/**
	 * User Asset Stats
	 *
	 * Get a specific user's asset stats
	 *
	 * @access public
	 * @return array
	 */
	public function user_asset_stats($user_id)
	{
		// Set default values to 0
		$default = array(
			'created'  => 0,
			'since'    => 0,
			'awaiting' => 0,
			'approved' => 0,
			'deleted'  => 0,
		);

		// Initialize stats variable with defaults
		$stats = array(
			1 => (object) $default,
			2 => (object) $default,
			3 => (object) $default,
			4 => (object) $default,
			5 => (object) $default,
		);

		// Get the values from the database
		$this->db->from('assets');
		$this->db->where('user_id', $user_id);
		$this->db->order_by('asset_id', 'asc');
		$query  = $this->db->get();
		$result = $query->result();

		// Loop through the assets
		foreach ($result as $row)
		{
			// Add one to created for this asset_type
			$stats[$row->asset_type]->created += 1;

			// If this is the first one of this asset type
			if ($stats[$row->asset_type]->created == 1)
			{
				// Add date to since
				$stats[$row->asset_type]->since = $row->asset_created;
			}

			// If awaiting approval
			if (!$row->approved)
			{
				// Add one to awaiting
				$stats[$row->asset_type]->awaiting += 1;
			}

			// If approved
			if ($row->approved)
			{
				// Add one to approved
				$stats[$row->asset_type]->approved += 1;
			}

			// If deleted
			if ($row->deleted)
			{
				// Add one to deleted
				$stats[$row->asset_type]->deleted += 1;
			}
		}

        //get photoset stats
        $this->db->from('photosets');
        $this->db->where('user_id', $user_id);
        $this->db->order_by('photoset_id', 'asc');
        $query  = $this->db->get();
        $result = $query->result();

        $stats[3] = (object) $default;
        // Loop through the assets
        foreach ($result as $row)
        {
            // Add one to created for this asset_type
            $stats[3]->created += 1;

            // If this is the first one of this asset type
            if ($stats[3]->created == 1)
            {
                // Add date to since
                $stats[3]->since = $row->asset_created;
            }

            // If awaiting approval
            if (!$row->approved)
            {
                // Add one to awaiting
                $stats[3]->awaiting += 1;
            }

            // If approved
            if ($row->approved)
            {
                // Add one to approved
                $stats[3]->approved += 1;
            }

            // If deleted
            if ($row->deleted)
            {
                // Add one to deleted
                $stats[3]->deleted += 1;
            }
        }

		return $stats;
	}

	/**
	 * Delete Gallery
	 *
	 * Delete an entire gallery from the database
	 *
	 * @access public
	 * @return object
	 */
	public function delete_gallery($user_id = 0)
	{
		// Get this user's assets
		$this->db->from('assets');
		$this->db->where('user_id', $user_id);
		$query  = $this->db->get();
		$result = $query->result();

		// Loop through their assets
		foreach ($result as $asset)
		{
			// Remove asset from CDN
			$this->aws_model->delete_asset($asset->asset_id);

			// Delete any tags assigned to this asset
			$this->db->where('asset_id', $asset->asset_id);
			$this->db->delete('assets_tags');

			// Delete this asset
			$this->db->where('asset_id', $asset->asset_id);
			$this->db->delete('assets');
		}

		return TRUE;
	}

	/**
	 * Delete User
	 *
	 * Delete a user and their entire gallery from the database
	 *
	 * @access public
	 * @return object
	 */
	public function delete_user($user_id = 0)
	{
		// Delete the user's gallery first
		$this->delete_gallery($user_id);

		// Delete messages to or from this user
		$this->db->where('user_id_from', $user_id);
		$this->db->or_where('user_id_to', $user_id);
		$this->db->delete('messages');

		// Delete any tags assigned to this user
		$this->db->where('user_id', $user_id);
		$this->db->delete('users_tags');

		// Delete this user
		$this->db->where('user_id', $user_id);
		$this->db->delete('users');

		return TRUE;
	}

	/**
	 * Count Users
	 *
	 * Get total user account
	 *
	 * @access public
	 * @return array
	 */
	public function count_users($filter)
	{
		// Setup filter options into proper variables
		list($type, $disabled, $lockout, $approved) = explode('.', $filter);

		// If $type is set, limit to only those user_types
		if ($type > 0)
		{
			$this->db->where('user_type', $type);
		}

		// If $disabled is set, limit to only those users
		if ($disabled === '0' || $disabled === '1')
		{
			$this->db->where('disabled', $disabled);
		}

		// If $lockout is set, limit to only those users
		if ($lockout === '0' || $lockout === '1')
		{
			$this->db->where('lockout', $lockout);
		}

		// If $approved is set, limit to only those users
		if ($approved === '0' || $approved === '1')
		{
			$this->db->where('user_approved', $approved);
		}

		// If user is moderator, only show contributors and models
		if ($this->_user->user_type == 3)
		{
			$this->db->where('user_type <', 3);
		}

		$total = $this->db->count_all_results('users');

		return $total;
	}

	/**
	 * Get Users_Types
	 *
	 * Get all users types from the database
	 *
	 * @access public
	 * @return array
	 */
	public function get_users_types()
	{
		$this->db->from('users_types');
		if ($this->_user->user_type == 3)
		{
			// Moderators can only see contributors and models
			$this->db->where('user_type_id <', 3);
		}
		$this->db->order_by('user_type_id', 'ASC');
		$query  = $this->db->get();
		$result = $query->result();

		return $result;
	}

	/**
	 * Get Assets Users
	 *
	 * Get users with latest assets updates
	 *
	 * @access public
	 * @return array
	 */
	public function get_assets_users()
	{
		// Setup array for users and stats
		$users = array();
		$stats = array();

		// Get users with unapproved assets
		$this->db->from('assets');
		$this->db->where('approved', 0);
		$this->db->order_by('asset_id', 'asc');
		$query  = $this->db->get();
		$result = $query->result();

		// Loop through results
		foreach ($result as $row)
		{
			// Get unique users only
			if (!in_array($row->user_id, $users))
			{
				$users[] = $row->user_id;
				$stats[$row->user_id] = array(
					'1' => 0,
					'2' => 0,
					'3' => 0,
					'4' => 0,
					'5' => 0,
				);
			}
		}

		// Loop through unique users
		foreach ($users as $user_id)
		{
			// Get asset stats (non photosets)
			$this->db->from('assets');
			$this->db->where('approved', 0);
			$this->db->where('user_id', $user_id);
            $this->db->where('asset_type !=', 3);
			$this->db->order_by('asset_id', 'asc');
			$query  = $this->db->get();
			$result = $query->result();

			foreach ($result as $row)
            {
                $stats[$user_id][$row->asset_type]++;

				if (!isset($stats[$user_id]['since']))
				{
					$stats[$user_id]['since'] = $row->asset_created;
				}
			}

            // Get photoset stats
            $this->db->from('photosets');
            $this->db->where('approved', 0);
            $this->db->where('user_id', $user_id);
            $this->db->order_by('photoset_id', 'asc');
            $query  = $this->db->get();
            $result = $query->result();

            foreach ($result as $row)
            {
                $stats[$user_id][3]++;

                if (!isset($stats[$user_id]['since']))
                {
                    $stats[$user_id]['since'] = $row->asset_created;
                }
            }

			// Assign user information
			$this->db->from('users');
			$this->db->where('user_id', $user_id);
			$query = $this->db->get();
			$row   = $query->row();

			$stats[$user_id]['user'] = $row;
		}

		return $stats;
	}

	/**
	 * Get Assets
	 *
	 * Get all assets from database
	 *
	 * @access public
	 * @return array
	 */
	public function get_assets($per_page = 1, $page_num = 1, $filter = '0.all.all.all', $sort = 'approved', $dir = 'asc')
	{
		// Make sure $page_num is positive
		if ($page_num < 1)
		{
			$page_num = 1;
		}

		// Calculate offset
		$offset = $per_page * $page_num - $per_page;

        // Setup filter options into proper variables
        list($type, $default, $deleted, $approved) = explode('.', $filter);

		// Setup SQL Join to get asset_type_title and display_name
		$this->db->select('COUNT(`users_purchases`.`purchase_id`) AS purchased', FALSE);
        $this->db->select('users.*');

        if ($type == 3) {
            $this->db->select('photosets.*');
            $this->db->select('assets.filename');
            $this->db->from('photosets');
            $this->db->join('users', 'users.user_id = photosets.user_id');
            $this->db->join('assets', 'assets.asset_id = photosets.cover_photo_id');
        } else {
            $this->db->select('assets.*');
            $this->db->select('assets_types.*');
            $this->db->from('assets');
            $this->db->join('assets_types', 'assets_types.asset_type_id = assets.asset_type');
            $this->db->join('users', 'users.user_id = assets.user_id');
        }



        if ($type == 3) {
            $this->db->join('users_purchases', 'users_purchases.photoset_id = photosets.photoset_id AND users_purchases.purchase_price > 0', 'left');
            $this->db->group_by('photosets.photoset_id');
        } else {
            $this->db->join('users_purchases', 'users_purchases.asset_id = assets.asset_id AND users_purchases.purchase_price > 0', 'left');
            $this->db->group_by('assets.asset_id');
        }

		// If $type is set, limit to only those asset_types
		if ($type > 0 && $type != 3)
		{
			$this->db->where('asset_type', $type);
		}

		// If $type isn't photoset_photo (4), hide them
		if ($type != 4  && $type != 3)
		{
			$this->db->where('asset_type !=', 4);
		}

        if ($type == 3)

		// If $default is set, limit to only those assets
		if ($default === '0' || $default === '1')
		{
			$this->db->where('default', $default);
		}

		// If $deleted is set, limit to only those assets
		if ($deleted === '0' || $deleted === '1')
		{
			$this->db->where('deleted', $deleted);
		}

		// If $approved is set, limit to only those assets
		if ($approved === '0' || $approved === '1')
		{
			$this->db->where('approved', $approved);
		}

		if ($sort == 'asset_id'  || $type != 3)
		{
			// Sort by chosen column and direction
			$this->db->order_by('assets.asset_id', $dir);
		}
		else
		{
			// Sort by chosen column and direction
			$this->db->order_by($sort, $dir);
		}

		// If not sorting by asset_id already, make it the secondary sort
		if ($sort != 'asset_id')
		{
			if ($sort == 'approved' && $dir == 'asc')
			{
                if ($type != 3) {
                    $this->db->order_by('assets.asset_id', 'asc');
                } else {
                    $this->db->order_by('photosets.photoset_id', 'asc');
                }
			}
			else
			{
                if ($type != 3) {
                    $this->db->order_by('assets.asset_id', 'desc');
                } else {
                    $this->db->order_by('photosets.photoset_id', 'desc');
                }
			}
		}

		// Finish up with limiting
		$this->db->limit($per_page, $offset);
		$query  = $this->db->get();
		$result = $query->result();

		// Let's add moderator names to approved entries
		foreach ($result as $key => $row)
		{
            if ($type == 3)
                $result[$key]->asset_type_title = 'Photoset';

			if ($row->approved_by > 0)
			{
				$this->db->select('display_name');
				$this->db->from('users');
				$this->db->where('user_id', $row->approved_by);
				$query = $this->db->get();
				$sub   = $query->row();
				$result[$key]->approved_by_name = $sub->display_name;
			}
			else
			{
				$result[$key]->approved_by_name = '';
			}
		}

		return $result;
	}

    /**
	/**
	 * Get Asset
	 *
	 * Get a specific asset from database
	 *
	 * @access public
	 * @return object
	 */
	public function get_asset($asset_id = 0)
	{
		$this->db->from('assets');
		$this->db->join('users', 'users.user_id = assets.user_id');
		$this->db->where('asset_id', $asset_id);
		$query = $this->db->get();
		$row   = $query->row();

		// Let's add moderator name to approved entries
		if ($row->approved_by > 0)
		{
			$this->db->select('display_name');
			$this->db->from('users');
			$this->db->where('user_id', $row->approved_by);
			$query = $this->db->get();
			$sub   = $query->row();
			$row->approved_by_name = $sub->display_name;
		}
		else
		{
			$row->approved_by_name = '';
		}

        $row->asset_title_type = $this->get_assets_types_title($row->asset_type);

		// Setup initial subphotos array
		$row->subphotos = array();

        if ($row->asset_type == 4)
        {
            $row->photoset = $this->assets_model->get_photoset($row->photoset_id);

            // Let's get all subphotos for photosets
            if ($row->asset_id == $row->photoset->cover_photo_id)
            {
                // Get asset_id's to re-use in this recursive function
                $this->db->select('asset_id');
                $this->db->from('assets');
                $this->db->where('photoset_id', $asset_id);
                $this->db->where('asset_id !=', $row->asset_id);
                $this->db->order_by('approved', 'asc');
                $this->db->order_by('asset_id', 'desc');
                $query  = $this->db->get();
                $result = $query->result();

                // Cycle through result getting each photo
                foreach ($result as $sub)
                {
                    $row->subphotos[] = $this->get_asset($sub->asset_id);
                }
            }
        }

		// Let's add mimetype for videos
		if ($row->asset_type == 5)
		{
			// Set mimetype value to ensure it exists
			$row->mimetype = '';

			// Separate the filename into an array
			$filename = explode('.', $row->filename);

			// Pop the last item out of array (file extension)
			$extension = array_pop($filename);

			// Determine mimetype from extension
			switch ($extension)
			{
				case 'mpeg':
				case 'mpg':
					$row->mimetype = 'video/mpeg';
				break;

				case 'webm':
					$row->mimetype = 'video/webm';
				break;

				case 'flv':
					$row->mimetype = 'video/x-flv';
				break;

				case 'ogv':
					$row->mimetype = 'video/ogg';
				break;

				case 'wmv':
					$row->mimetype = 'video/x-ms-wmv';
				break;

				default:
					$row->mimetype = 'video/mp4';
				break;
			}
		}

		// Set empty tags variable
		$row->tags = '';



		// Add in existing tags
		$this->db->from('assets_tags');
		$this->db->join('tags', 'tags.tag_id = assets_tags.tag_id');
		$this->db->where('asset_id', $asset_id);
		$this->db->order_by('asset_tag_id', 'asc');
		$query  = $this->db->get();
		$result = $query->result();

		foreach ($result as $tag)
		{
			$row->tags .= $tag->tag . ', ';
		}

		return $row;
	}

	/**
	 * Edit Asset
	 *
	 * Edit a specific asset in the database
	 *
	 * @access public
	 * @return object
	 */
	public function edit_asset($asset_id = 0)
	{
        if ($this->uri->segment(4) == 'photoset')
        {
            $asset = $this->assets_model->get_photoset($this->uri->segment(5));

            $data = array(
                'asset_title' => $this->input->post('asset_title'),
                'asset_cost'  => $this->input->post('asset_cost'),
                'deleted'     => (int) $this->input->post('deleted'),
                'approved'    => (int) $this->input->post('approved'),
            );
        }
        else
        {
            $asset = $this->get_asset($asset_id);

            $data = array(
                'asset_title' => $this->input->post('asset_title'),
                'asset_cost'  => $this->input->post('asset_cost'),
                'deleted'     => (int) $this->input->post('deleted'),
                'approved'    => (int) $this->input->post('approved'),
                'asset_hd'    => (int) $this->input->post('asset_hd'),
            );
        }

		// Ensure only a public photo can be set as a default
		if ($asset->asset_type == 1)
		{
			$data['default'] = (int) $this->input->post('default');

			if ($asset->default == 0 && $data['default'] == 1)
			{
				// We are setting a new default, so we need to remove existing
				$this->db->set('default', 0);
				$this->db->where('asset_type', 1);
				$this->db->where('user_id', $asset->user_id);
				$this->db->update('assets');
			}
		}

		// If approving this, we need to set approved fields
		if ($asset->approved == 0 && $data['approved'] == 1)
		{
			$data['approved_by'] = $this->_user->user_id;
			$data['approved_on'] = time();
		}

        if ($this->uri->segment(4) == 'photoset')
        {
            $this->db->where('photoset_id', $this->uri->segment(5));
            $this->db->update('photosets', $data);

            $subdata = array(
                'approved'    => (int) $this->input->post('approved'),
                'approved_by' => $data['approved_by'],
                'approved_on' => $data['approved_on'],
                'asset_title' => $this->input->post('asset_title'),
                'asset_cost'  => $this->input->post('asset_cost'),
                'deleted'     => (int) $this->input->post('deleted'),
                'approved'    => (int) $this->input->post('approved'),
                'asset_hd'    => (int) $this->input->post('asset_hd'),
            );

            $this->db->where('photoset_id', $this->uri->segment(5));
            $this->db->update('assets', $subdata);
        }
        else
        {
            $this->db->where('asset_id', $asset_id);
            $this->db->update('assets', $data);

            if ((int)$this->input->post('asset_cover_photo') == 1)
            {
                $this->db->set('cover_photo_id', $asset_id);
                $this->db->where('photoset_id', $asset->photoset_id);
                $this->db->update('photosets');

                redirect('/management/assets/edit/photoset/' . $asset->photoset_id);
            }
        }

		// Get updated asset to return to controller
        if ($this->uri->segment(4) == 'photoset')
        {
            $asset = $this->assets_model->get_photoset($this->uri->segment(5));
        }
        else
        {
            $asset = $this->get_asset($asset_id);
        }

		// Get tags
		$tags = $this->input->post('tags');

		// Process tags-related updates
		$this->process_tags('asset', $asset_id, $tags);

		return $asset;
	}

	/**
	 * Approve Asset
	 *
	 * Approve an asset in the database
	 *
	 * @access public
	 * @return object
	 */
	public function approve_asset($asset_id)
	{
		$data = array(
			'approved'    => 1,
			'approved_by' => $this->_user->user_id,
			'approved_on' => time(),
		);
		$this->db->where('asset_id', $asset_id);
		$this->db->update('assets', $data);

		// Get updated asset to return to controller
		$asset = $this->get_asset($asset_id);

		return $asset;
	}

	/**
	 * Add Asset
	 *
	 * Add a new asset to the database
	 *
	 * @access public
	 * @return object
	 */
	public function add_asset($user_id, $asset_type, $photoset_id, $filename, $video = '')
	{
		// Data array of items to update from POST in database
		$data = array(
			'user_id'       => (int) $user_id,
			'asset_type'    => (int) $asset_type,
			'asset_title'   => $this->input->post('asset_title'),
			'filename'      => $filename,
			'deleted'       => (int) $this->input->post('deleted'),
			'approved'      => (int) $this->input->post('approved'),
			'asset_hd'      => (int) $this->input->post('asset_hd'),
			'asset_created' => time(),
		);

		// Ensure only a public photo can be set as a default
		if ($asset_type == 1)
		{
			$data['default'] = (int) $this->input->post('default');

			if ($data['default'] == 1)
			{
				// We are setting a new default, so we need to remove existing
				$this->db->set('default', 0);
				$this->db->where('asset_type', 1);
				$this->db->where('user_id', $user_id);
				$this->db->update('assets');
			}
		}

		if ($asset_type == 3 || $asset_type == 5)
		{
			$data['asset_cost'] = $this->input->post('asset_cost');
		}

		// Ensure only asset_type 4 gets assigned to photoset_id
		if ($asset_type == 4)
		{
			$data['photoset_id'] = (int) $photoset_id;
		}

		// Ensure only asset_type 5 gets a video
		if ($asset_type == 5)
		{
			$data['video'] = $video;
		}

		// If approving this, we need to set approved fields
		if ($data['approved'] == 1)
		{
			$data['approved_by'] = $this->_user->user_id;
			$data['approved_on'] = time();
		}

		// Process database update
		$this->db->insert('assets', $data);

		$asset_id = $this->db->insert_id();

		// Get asset to return to controller
		$asset = $this->get_asset($asset_id);

		// Get tags
		$tags = $this->input->post('tags');

		// Process tags-related updates
		$this->process_tags('asset', $asset_id, $tags);

		return $asset;
	}

	/**
	 * Delete Asset
	 *
	 * Delete a specific asset from the database
	 *
	 * @access public
	 * @return object
	 */
	public function delete_asset($asset_id = 0)
	{
		// Delete this asset
		$this->db->where('asset_id', $asset_id);

		// Delete any subphotos
		$this->db->or_where('photoset_id', $asset_id);

		// Perform the delete
		$this->db->delete('assets');

		return TRUE;
	}

	/**
	 * Count Assets
	 *
	 * Get total asset count
	 *
	 * @access public
	 * @return array
	 */
	public function count_assets($filter)
	{
		// Setup filter options into proper variables
		list($type, $default, $deleted, $approved) = explode('.', $filter);

		// If $type is set, limit to only those asset_types
		if ($type > 0)
		{
            if ($type != 3) {
                $this->db->where('asset_type', $type);
            }
		}



		// If $type isn't photoset_photo (4), hide them
		if ($type != 4 && $type != 3)
		{
			$this->db->where('asset_type !=', 4);
		}

		// If $default is set, limit to only those assets
		if ($default === '0' || $default === '1')
		{
			$this->db->where('default', $default);
		}

		// If $deleted is set, limit to only those assets
		if ($deleted === '0' || $deleted === '1')
		{
			$this->db->where('deleted', $deleted);
		}

		// If $approved is set, limit to only those assets
		if ($approved === '0' || $approved === '1')
		{
			$this->db->where('approved', $approved);
		}

        if ($type != 3) {
            $total = $this->db->count_all_results('assets');
        } else {
            $total = $this->db->count_all_results('photosets');
        }

		return $total;
	}

	/**
	 * Get Assets_Types_Title
	 *
	 * Get a specific asset_type's title
	 *
	 * @access public
	 * @return array
	 */
	public function get_assets_types_title($asset_type)
	{
		$this->db->from('assets_types');
		$this->db->where('asset_type_id', $asset_type);
		$query = $this->db->get();
		$row   = $query->row();

		return $row->asset_type_title;
	}

	/**
	 * Get Assets_Types
	 *
	 * Get all assets types from the database
	 *
	 * @access public
	 * @return array
	 */
	public function get_assets_types()
	{
		$this->db->from('assets_types');
		$this->db->order_by('asset_type_id', 'ASC');
		$query  = $this->db->get();
		$result = $query->result();

		return $result;
	}

	/**
	 * Process Tags
	 *
	 * Process tags by removing old and adding new
	 *
	 * @access public
	 * @return n/a
	 */
	public function process_tags($type, $type_id, $tags)
	{
		// Delete existing tags
		$this->db->where($type . '_id', $type_id);
		$this->db->delete($type . 's_tags');

		// Convert tags to an array
		$tags = explode(',', $tags);

		// Array of existing tags
		$existing = array();

		foreach ($tags as $tag)
		{
			// Trim tags to remove any extra whitespace
			$tag = trim($tag);

			// Make sure tag isn't empty
			if (!empty($tag))
			{
				// See if tag_id exists
				$this->db->from('tags');
				$this->db->where('tag', $tag);
				$query = $this->db->get();
				$row   = $query->row();

				if ($row)
				{
					// Set tag_id
					$tag_id = $row->tag_id;
				}
				else
				{
					// Tag doesn't exist, let's create it
					$data = array(
						'tag' => $tag
					);
					$this->db->insert('tags', $data);

					// Get new tag_id
					$tag_id = $this->db->insert_id();
				}

				// Let's make sure this isn't a repeat of an earlier tag
				if (!in_array($tag, $existing))
				{
					// Assign this tag to this item
					$data = array(
						$type . '_id' => $type_id,
						'tag_id'      => $tag_id,
					);
					$this->db->insert($type . 's_tags', $data);

					$existing[] = $tag;
				}
			}
		}
	}

	/**
	 * Recently Approved Assets
	 *
	 * Get recently approved assets from the database
	 *
	 * @access public
	 * @return string
	 */
	public function recently_approved()
	{
		// Placeholder array for items and return
		$items  = array();
		$times  = array();
		$return = array();

		// Generate time for 1 day ago (60s * 60m * 24h = 86400)
		$since_new = time() - 86400;

		// Generate time for 1 week ago (60s * 60m * 24h * 7d = 604800)
		$since = time() - 604800;

		$this->db->from('assets');
		$this->db->where('asset_type !=', 4);
		$this->db->where('approved', 1);
		$this->db->where('approved_on >', $since);
		$this->db->order_by('approved_on', 'desc');
		$query  = $this->db->get();
		$result = $query->result();

		foreach ($result as $row)
		{
			// Create the array key to help show only 1 line per mod/user/asset_type
			$key = $row->approved_by . '|' . $row->user_id . '|' . $row->asset_type;

			if (isset($items[$key]))
			{
				$items[$key]++;
			}
			else
			{
				$items[$key] = 1;
				$times[$key] = $row->approved_on;
			}
		}

		// Loop through the items to actually create a usable object
		foreach ($items as $key => $item)
		{
			// Create new object to store values
			$object = new stdClass();

			// Convert the key to real values
			list($approved_by, $user_id, $asset_type) = explode('|', $key);

			// Get moderator object
			$moderator = $this->get_user($approved_by);

			// Get user object
			$user = $this->get_user($user_id);

			// Get asset type title
			$asset_type_title = $this->get_assets_types_title($asset_type);

			// Assign the values to the object
			$object->user_id          = $user_id;
			$object->asset_type       = $asset_type;
			$object->moderator        = $moderator->display_name ? $moderator->display_name : 'User # ' . $moderator->user_id;
			$object->user             = $user->display_name ? $user->display_name : 'User # ' . $user->user_id;
			$object->asset_type_title = $asset_type_title;
			$object->total            = $item;
			$object->age              = $times[$key] > $since_new ? 'new-approval' : 'old-approval';

			// Add the object to the return array
			$return[] = $object;
		}

		return $return;
	}

	/**
	 * Get Blocked IPs
	 *
	 * Get the blocked IP's from the database
	 *
	 * @access public
	 * @return string
	 */
	public function get_blocked_ips()
	{
		$blocked_ips = array();

		$this->db->from('blocked_ips');
		$this->db->order_by('blocked_ip_id', 'asc');
		$query  = $this->db->get();
		$result = $query->result();

		foreach ($result as $row)
		{
			$blocked_ips[] = $row->blocked_ip;
		}

		$blocked_ips = implode("\n", $blocked_ips);

		return $blocked_ips;
	}

	/**
	 * Save Blocked IPs
	 *
	 * Save the blocked IP's in the database
	 *
	 * @access public
	 * @return n/a
	 */
	public function save_blocked_ips()
	{
		// Get blocked ip's from POST
		$blocked_ips = $this->input->post('ip_addresses');

		// Convert to an array we can loop through
		$blocked_ips = explode("\n", $blocked_ips);

		// Delete existing blocked ip's
		$this->db->empty_table('blocked_ips');

		// Loop through IP's and add to database
		foreach ($blocked_ips as $blocked_ip)
		{
			$blocked_ip = trim($blocked_ip);

			if (!empty($blocked_ip))
			{
				$this->db->insert('blocked_ips', array('blocked_ip' => $blocked_ip));
			}
		}
	}

	/**
	 * Model Payout
	 *
	 * Get all models that are owned a payout
	 *
	 * @access public
	 * @return n/a
	 */
	public function model_payout()
	{
		$this->db->from('users');
		$this->db->where('user_type', 2);
		$this->db->where('funds_usd >=', 20);
		$this->db->order_by('funds_usd', 'desc');
		$this->db->order_by('last_login', 'desc');
		$query  = $this->db->get();
		$result = $query->result();

		foreach ($result as $key => $row)
		{
			// Go back 5 days ago, to midnight
			$time = mktime(0, 0, 0) - 432000;

			$this->db->select_sum('purchase_price_usd');
			$this->db->from('users_purchases');
			$this->db->where('user_id', $row->user_id);
			$this->db->where('purchase_created >', $time);
			$query    = $this->db->get();
			$purchase = $query->row();

			// Subtract purchases within the last ~5 days from funds_usd
			$result[$key]->funds_usd_payable = $row->funds_usd - $purchase->purchase_price_usd;
		}

		return $result;
	}

	/**
	 * Process Payout
	 *
	 * Process model payouts from checked values in form
	 *
	 * @access public
	 * @return n/a
	 */
	public function process_payout()
	{
		$payout_models = (array) $this->input->post('payout_models');
		$payout_funds  = (array) $this->input->post('payout_funds');

		foreach ($payout_models as $key => $model_id)
		{
			$funds = $payout_funds[$key];

			// If we are paying at least $20.00
			if ($funds >= 20)
			{
				$this->db->from('users');
				$this->db->where('user_id', $model_id);
				$this->db->where('user_type', 2);
				$this->db->where('funds_usd >=', 20);
				$query = $this->db->get();
				$row   = $query->row();

				// If model found
				if ($row)
				{
					// If model has enough funds
					if ($row->funds_usd >= $funds)
					{
						$site_fee = 1.00;

						$withdrawal_id = (int) $this->make_withdrawal($funds, $site_fee, $model_id);

						if ($withdrawal_id)
						{
							$this->dwolla_model->initialize();
							$transaction_id = $this->dwolla_model->send(decrypt(DWOLLA_PIN), $row->email, $funds - $site_fee, 'Email', 'Withdrawal of funds from Babes for Bitcoin account.');

							if (!$transaction_id)
							{
								$error = $this->dwolla_model->getError();
								// Log withdrawal error, refund account
								$this->update_withdrawal($model_id, $withdrawal_id, '', $error);
							}
							else
							{
								// Log successful withdrawal
								$this->update_withdrawal($model_id, $withdrawal_id, $transaction_id);
							}
						}
					}
				}
			}
		}
	}

	/**
	 * Make Withdrawal
	 *
	 * Make a withdrawal on a model account
	 *
	 * @access public
	 * @return object
	 */
	public function make_withdrawal($amount, $site_fee = 0, $model_id = 0)
	{
		$user = $this->user_model->get_user($model_id);

		$data = array(
			'user_id'             => $model_id,
			'currency'            => 'usd',
			'withdrawal_amount'   => $amount,
			'funds_usd_remaining' => $user->funds_usd - $amount,
			'site_fee'            => $site_fee,
			'withdrawal_created'  => time(),
		);

		$this->db->insert('users_withdrawals', $data);
		$withdrawal_id = $this->db->insert_id();

		$this->db->set('funds_usd', $data['funds_usd_remaining']);
		$this->db->where('user_id', $model_id);
		$this->db->update('users');

		return $withdrawal_id;
	}

	/**
	 * Update Withdrawal
	 *
	 * Update a withdrawal on a model account
	 *
	 * @access public
	 * @return object
	 */
	public function update_withdrawal($model_id, $withdrawal_id = 0, $transaction_id = '', $transaction_error = '')
	{
		$user = $this->user_model->get_user($model_id);

		$data = array(
			'transaction_id'    => $transaction_id,
			'transaction_error' => $transaction_error,
		);

		if (empty($transaction_id))
		{
			$data['refunded'] = 1;

			$this->db->from('users_withdrawals');
			$this->db->where('withdrawal_id', $withdrawal_id);
			$this->db->where('user_id', $model_id);
			$query = $this->db->get();
			$row   = $query->row();

			if ($row)
			{
				$this->db->set('funds_usd', $row->funds_usd_remaining + $row->withdrawal_amount);
				$this->db->where('user_id', $model_id);
				$this->db->update('users');
			}
		}

		$this->db->where('withdrawal_id', $withdrawal_id);
		$this->db->where('user_id', $model_id);
		$this->db->update('users_withdrawals', $data);
	}

}

/* End of file management_model.php */
/* Location: ./application/models/management_model.php */