<?php if (! defined('BASEPATH')) exit('No direct script access');

class Contributors_model extends CI_Model
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
	 * Get contributors
	 *
	 * Get contributors from database
	 *
	 * @access public
	 * @return object
	 */
	public function get_contributors()
	{
		// Array of contributors to return
		$contributors = array();

		$filter_tags = $this->input->post('tags');
		$sort        = $this->input->post('sort');

		$this->db->select('users.user_id');
		$this->db->from('users');
		if (!empty($filter_tags) && is_array($filter_tags))
		{
			$this->db->join('assets', 'assets.user_id = users.user_id');
			$this->db->join('assets_tags', 'assets_tags.asset_id = assets.asset_id');
			$this->db->where_in('assets_tags.tag_id', $filter_tags);
		}
		$this->db->where('users.user_type', 1);
		if ($sort == 'asset_created')
		{
			if (empty($filter_tags) || !is_array($filter_tags))
			{
				$this->db->join('assets', 'assets.user_id = users.user_id');
			}
			$this->db->order_by('assets.asset_id', 'desc');
		}
		elseif ($sort == 'name_asc')
		{
			$this->db->order_by('users.display_name', 'asc');
		}
		elseif ($sort == 'name_desc')
		{
			$this->db->order_by('users.display_name', 'desc');
		}
		else
		{
			$this->db->order_by('users.last_login', 'desc');
		}
		$query  = $this->db->get();
		$result = $query->result();
		$done   = array();

		foreach ($result as $row)
		{
			if (!in_array($row->user_id, $done))
			{
				// Add to done array to remove duplicates
				$done[] = $row->user_id;

				// Get contributor information
				$contributor = $this->get_contributor($row->user_id);

				if ($contributor)
				{
					$contributors[] = $contributor;
				}
			}
		}

		return $contributors;
	}

	/**
	 * Get contributor
	 *
	 * Get contributor from database
	 *
	 * @access public
	 * @return object
	 */
	public function get_contributor($contributor_id = 0)
	{
		// Get contributor from database
		$this->db->from('users');
		$this->db->where('user_id', $contributor_id);
		$this->db->where('user_type', 1);
		$this->db->where('disabled', 0);
		$query = $this->db->get();
		$row   = $query->row();

		if ($row)
		{
			// Set initial count of assets to 0
			$row->assets = array(
				1 => 0,
				2 => 0,
				3 => 0,
				4 => 0,
				5 => 0,
			);

			// Get assets to calculate total values
			$this->db->from('assets');
			$this->db->where('user_id', $contributor_id);
			$this->db->where('deleted', 0);
			$query  = $this->db->get();
			$assets = $query->result();

			// Assign total number of each asset_type
			foreach ($assets as $asset)
			{
				$row->assets[$asset->asset_type]++;

				// If this asset is the default public photo
				if ($asset->asset_type == 1 && $asset->default == 1)
				{
					// Set it for use in the view
					$row->default = $asset;
				}
			}

			// Set number of photos and videos based on above
			$row->num_photos = $row->assets[1] + $row->assets[2];

			// If no default, try first public photo instead
			if (!isset($row->default))
			{
				$this->db->from('assets');
				$this->db->where('user_id', $contributor_id);
				$this->db->where('asset_type', 1);
				$this->db->where('deleted', 0);
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
		}

		return $row;
	}

	/**
	 * Get purchased models
	 *
	 * Get purchased models from database
	 *
	 * @access public
	 * @return object
	 */
	public function get_purchased_models()
	{
		// Array of models to return
		$models = array();

		$filter_type = $this->input->post('type');
		$filter_tags = $this->input->post('tags');

		// If moderator or administrator, show all models
		if ($this->_user->user_type >= 3)
		{
			$this->db->select('assets.user_id');
			$this->db->from('assets');
			$this->db->join('users', 'users.user_id = assets.user_id');
			$this->db->where_in('asset_type', array(2, 3, 4, 5));
			if (!empty($filter_type) && is_array($filter_type))
			{
				$this->db->where_in('assets.asset_type', $filter_type);
			}
			if (!empty($filter_tags) && is_array($filter_tags))
			{
				$this->db->join('assets_tags', 'assets_tags.asset_id = assets.asset_id');
				$this->db->where_in('assets_tags.tag_id', $filter_tags);
			}
			$this->db->where('(`assets`.`approved` = 1 OR `users`.`trusted` = 1)', NULL, FALSE);
			$this->db->order_by('assets.asset_id', 'desc');
			$query  = $this->db->get();
			$result = $query->result();
		}
		else
		{
			$this->db->select('assets.user_id');
			$this->db->from('users_purchases');
			$this->db->join('assets', 'assets.asset_id = users_purchases.asset_id');
			$this->db->join('users', 'users.user_id = assets.user_id');
			$this->db->where('users_purchases.user_id', $this->_user->user_id);
			if (!empty($filter_type) && is_array($filter_type))
			{
				$this->db->where_in('assets.asset_type', $filter_type);
			}
			if (!empty($filter_tags) && is_array($filter_tags))
			{
				$this->db->join('assets_tags', 'assets_tags.asset_id = assets.asset_id');
				$this->db->where_in('assets_tags.tag_id', $filter_tags);
			}
			$this->db->where('(`assets`.`approved` = 1 OR `users`.`trusted` = 1)', NULL, FALSE);
			$this->db->order_by('users_purchases.purchase_id', 'desc');
			$query  = $this->db->get();
			$result = $query->result();
		}

		$done = array();

		foreach ($result as $row)
		{
			if (!in_array($row->user_id, $done))
			{
				// Add to done array to remove duplicates
				$done[] = $row->user_id;

				// Get model information
				$model = $this->models_model->get_model($row->user_id);

				if ($model)
				{
					if ($this->_user->user_type >= 3)
					{
						$model->owned_photos = count($this->contributors_model->get_purchased_photos($row->user_id));
						$model->owned_videos = count($this->contributors_model->get_purchased_videos($row->user_id));
					}
					else
					{
						$model->owned_photos = $this->get_owned_photos($row->user_id);
						$model->owned_videos = $this->get_owned_videos($row->user_id);
					}

					$models[] = $model;
				}
			}
		}

		return $models;
	}

	/**
	 * Get purchased photos
	 *
	 * Get purchased photos from database
	 *
	 * @access public
	 * @return object
	 */
	public function get_purchased_photos($model_id = 0)
	{
		// If moderator or administrator, show all private photos
		if ($this->_user->user_type >= 3)
		{
			// Get photos
			$this->db->from('assets');
			$this->db->where_in('asset_type', array(2, 3, 4));
			if ($model_id)
			{
				$this->db->where('assets.user_id', $model_id);
			}
			$this->db->order_by('asset_id', 'desc');
			$query  = $this->db->get();
			$assets = $query->result();

			foreach ($assets as $asset)
			{
				$asset->user = $this->user_model->get_user($asset->user_id);
			}
		}
		else
		{
			$this->db->from('users_purchases');
			$this->db->join('assets', 'assets.asset_id = users_purchases.asset_id');
			$this->db->where('assets.asset_type', 2);
			$this->db->where('users_purchases.user_id', $this->_user->user_id);
			if ($model_id)
			{
				$this->db->where('assets.user_id', $model_id);
			}
			$this->db->order_by('users_purchases.purchase_created', 'desc');
			$query  = $this->db->get();
			$assets = $query->result();

			foreach ($assets as $asset)
			{
				$asset->user = $this->user_model->get_user($asset->user_id);
			}
		}

		return $assets;
	}

	/**
	 * Get purchased photosets
	 *
	 * Get purchased photosets from database
	 *
	 * @access public
	 * @return object
	 */
	public function get_purchased_photosets($model_id = 0)
	{
		// If moderator or administrator, show all
		if ($this->_user->user_type >= 3)
		{
			// Get photosets
			$this->db->from('photosets');
			$this->db->order_by('photoset_id', 'desc');
			$query  = $this->db->get();
			$assets = $query->result();

			foreach ($assets as $asset)
			{
				// Exchange USD to BTC
				$asset->asset_cost_btc = $this->cart_model->usd_to_btc($asset->asset_cost);

				// Get subphotos
				$asset->photos = $this->get_purchased_photosets_photos($asset->asset_id);

				// Add user information to photoset
				$asset->user = $this->user_model->get_user($asset->user_id);

				// Add in the amount of sub photos if photoset
				$photos = count($asset->photos) + 1;
				$asset->asset_extra = 'Number of photos: ' . $photos;
			}
		}
		else
		{
			// Get photosets
			$this->db->from('users_purchases');
			$this->db->join('photosets', 'photosets.photoset_id = users_purchases.photoset_id');
			$this->db->where('users_purchases.user_id', $this->_user->user_id);
			if ($model_id)
			{
				$this->db->where('photosets.user_id', $model_id);
			}
			$this->db->order_by('users_purchases.purchase_created', 'desc');
			$query  = $this->db->get();
			$assets = $query->result();

			foreach ($assets as $asset)
			{
				// Set asset cost to actual purchase price
				$asset->asset_cost = $asset->purchase_price;

				// Don't show BTC value on purchases since it probably changed
				$asset->asset_cost_btc = FALSE;

				// Add user information to photoset
				$asset->user = $this->user_model->get_user($asset->user_id);

				// Get subphotos
				$asset->photos = $this->get_purchased_photosets_photos($asset->photoset_id);

				// Add in the amount of sub photos if photoset
				$photos = count($asset->photos) + 1;
				$asset->asset_extra = 'Number of photos: ' . $photos;
			}
		}

		return $assets;
	}

	/**
	 * Get purchased photosets photos
	 *
	 * Get purchased photosets photos from database
	 *
	 * @access public
	 * @return object
	 */
	public function get_purchased_photosets_photos($photoset_id)
	{
		// Get photosets photos
		$this->db->from('assets');
		$this->db->where('photoset_id', $photoset_id);
		$this->db->order_by('asset_id', 'desc');
		$query  = $this->db->get();
		$assets = $query->result();

		return $assets;
	}

	/**
	 * Get purchased videos
	 *
	 * Get purchased videos photos from database
	 *
	 * @access public
	 * @return object
	 */
	public function get_purchased_videos($model_id = 0)
	{
		// If moderator or administrator, show all
		if ($this->_user->user_type >= 3)
		{
			// Get videos
			$this->db->from('assets');
			$this->db->where('asset_type', 5);
			if ($model_id)
			{
				$this->db->where('assets.user_id', $model_id);
			}
			$this->db->order_by('asset_id', 'desc');
			$query  = $this->db->get();
			$assets = $query->result();

			foreach ($assets as $asset)
			{
				// Exchange USD to BTC
				$asset->asset_cost_btc = $this->cart_model->usd_to_btc($asset->asset_cost);

				// Get video mimetype
				$asset->mimetype = $this->assets_model->get_mimetype($asset->video);

				// Add user information to photoset
				$asset->user = $this->user_model->get_user($asset->user_id);

				$duration = shell_exec(BIN_PATH . 'ffmpeg -i "' . CDN_URL . $asset->video . '" 2>&1 | grep Duration | cut -d " " -f 4 | rev | cut -c 2- | rev');

				if (strpos($duration, '.') !== FALSE)
				{
					$duration = substr($duration, 0, strpos($duration, '.'));
				}

				if (!empty($duration))
				{
					$asset->asset_extra = 'Duration: ' . $duration;
				}
			}
		}
		else
		{
			// Get videos
			$this->db->from('users_purchases');
			$this->db->join('assets', 'assets.asset_id = users_purchases.asset_id');
			$this->db->where('assets.asset_type', 5);
			$this->db->where('users_purchases.user_id', $this->_user->user_id);
			if ($model_id)
			{
				$this->db->where('assets.user_id', $model_id);
			}
			$this->db->order_by('users_purchases.purchase_created', 'desc');
			$query  = $this->db->get();
			$assets = $query->result();

			foreach ($assets as $asset)
			{
				// Set asset cost to actual purchase price
				$asset->asset_cost = $asset->purchase_price;

				// Don't show BTC value on purchases since it probably changed
				$asset->asset_cost_btc = FALSE;

				// Get video mimetype
				$asset->mimetype = $this->assets_model->get_mimetype($asset->video);

				// Add user information to photoset
				$asset->user = $this->user_model->get_user($asset->user_id);

				$duration = shell_exec(BIN_PATH . 'ffmpeg -i "' . CDN_URL . $asset->video . '" 2>&1 | grep Duration | cut -d " " -f 4 | rev | cut -c 2- | rev');

				if (strpos($duration, '.') !== FALSE)
				{
					$duration = substr($duration, 0, strpos($duration, '.'));
				}

				if (!empty($duration))
				{
					$asset->asset_extra = 'Duration: ' . $duration;
				}
			}
		}

		return $assets;
	}

	/**
	 * Get assets
	 *
	 * Get assets from database
	 *
	 * @access public
	 * @return object
	 */
	public function get_assets($contributor_id = 0, $asset_type = 0)
	{
		// Get contributor first, to make sure it's valid
		$contributor = $this->get_contributor($contributor_id);

		if ($contributor)
		{
			// Get assets
			$this->db->from('assets');
			$this->db->where('user_id', $contributor_id);
			$this->db->where('asset_type', $asset_type);
			$this->db->where('deleted', 0);
			$query  = $this->db->get();
			$assets = $query->result();

			return $assets;
		}
		else
		{
			return FALSE;
		}
	}

	/**
	 * Get owned
	 *
	 * Get owned from database
	 *
	 * @access public
	 * @return object
	 */
	public function get_owned($model_id)
	{
		$total = 0;

		$this->db->from('users_purchases');
		$this->db->join('assets', 'assets.asset_id = users_purchases.asset_id');
		$this->db->where_in('assets.asset_type', array(2, 3, 4, 5));
		$this->db->where('users_purchases.user_id', $this->_user->user_id);
		$this->db->where('assets.user_id', $model_id);
		$this->db->order_by('users_purchases.purchase_created', 'desc');
		$query  = $this->db->get();
		$assets = $query->result();

		foreach ($assets as $asset)
		{
			$total++;

			// If photoset
			if ($asset->asset_type == 3)
			{
				// Count sub photos as well
				$photos = $this->get_purchased_photosets_photos($asset->asset_id);

				$total += count($photos);
			}
		}

        $this->db->from('users_purchases');
        $this->db->join('photosets', 'photosets.photoset_id = users_purchases.photoset_id');
        $this->db->where('users_purchases.user_id', $this->_user->user_id);
        $this->db->where('photosets.user_id', $model_id);
        $this->db->order_by('users_purchases.purchase_created', 'desc');
        $query  = $this->db->get();
        $photosets = $query->result();

        foreach ($photosets as $photoset)
        {
            $total++;

            // Count sub photos as well
            $photos = $this->get_purchased_photosets_photos($photoset->photoset_id);

            $total += count($photos);
        }

		return $total;
	}

	/**
	 * Get owned photos
	 *
	 * Get owned photos from database
	 *
	 * @access public
	 * @return object
	 */
	public function get_owned_photos($model_id)
	{
		$total = 0;

		$this->db->from('users_purchases');
		$this->db->join('assets', 'assets.asset_id = users_purchases.asset_id');
		$this->db->where('assets.asset_type', 2);
		$this->db->where('users_purchases.user_id', $this->_user->user_id);
		$this->db->where('assets.user_id', $model_id);
		$this->db->order_by('users_purchases.purchase_created', 'desc');
		$query  = $this->db->get();
		$assets = $query->result();

        $total = count($assets);

        $this->db->from('users_purchases');
        $this->db->join('photosets', 'users_purchases.asset_id = photosets.photoset_id');
        $this->db->where('users_purchases.user_id', $this->_user->user_id);
        $this->db->where('photosets.user_id', $model_id);
        $this->db->order_by('users_purchases.purchase_created', 'desc');
        $query  = $this->db->get();
        $photosets = $query->result();

        foreach ($photosets as $photoset)
        {
            $total++;

            $photos = $this->get_purchased_photosets_photos($photoset->asset_id);
            $total += count($photos);
        };

		return $total;
	}

	/**
	 * Get owned videos
	 *
	 * Get owned videos from database
	 *
	 * @access public
	 * @return object
	 */
	public function get_owned_videos($model_id)
	{
		$total = 0;

		$this->db->from('users_purchases');
		$this->db->join('assets', 'assets.asset_id = users_purchases.asset_id');
		$this->db->where('assets.asset_type', 5);
		$this->db->where('users_purchases.user_id', $this->_user->user_id);
		$this->db->where('assets.user_id', $model_id);
		$this->db->order_by('users_purchases.purchase_created', 'desc');
		$query  = $this->db->get();
		$assets = $query->result();

		foreach ($assets as $asset)
		{
			$total++;
		}

		return $total;
	}

}

/* End of file contributors_model.php */
/* Location: ./application/contributors/contributors_model.php */