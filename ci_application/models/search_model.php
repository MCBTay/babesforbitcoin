<?php if (! defined('BASEPATH')) exit('No direct script access');

class Search_model extends CI_Model
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
	 * Find Keyword
	 *
	 * Find models by searching name for keyword
	 *
	 * @access public
	 * @return object
	 */
	public function find_keyword($keyword = 0)
	{
		// Array to contain user_id's that match this keyword
		$users = array();

		// Array of models to return
		$models = array();

		// Array of contributors to return
		$contributors = array();

		// Array of found user_id's
		$found = array();

		// Array of assets to return
		$assets = array();

		// Get the user_id's from the database
		$this->db->select('user_id');
		$this->db->from('users');
		$this->db->like('display_name', $keyword);
		$query  = $this->db->get();
		$result = $query->result();

		foreach ($result as $row)
		{
			$users[] = $row->user_id;
		}

		if ($users)
		{
			$this->db->distinct();
			$this->db->select('assets.user_id');
			$this->db->from('assets');
			$this->db->join('users', 'users.user_id = assets.user_id');
			$this->db->where_in('assets.user_id', $users);
			$this->db->where('deleted', 0);
			$this->db->where('(`assets`.`approved` = 1 OR `users`.`trusted` = 1)', NULL, FALSE);
			$this->db->order_by('asset_id', 'desc');
			$query  = $this->db->get();
			$result = $query->result();

			foreach ($result as $row)
			{
				// Add to found
				$found[] = $row->user_id;

				// Get model information
				$model = $this->models_model->get_model($row->user_id);

				if ($model)
				{
					$models[] = $model;
				}

				// Get contributor information
				$contributor = $this->contributors_model->get_contributor($row->user_id);

				if ($contributor)
				{
					$contributors[] = $contributor;
				}
			}

			// If anyone didn't have an asset, add them at the end
			foreach ($users as $user)
			{
				if (!in_array($user, $found))
				{
					// Get model information
					$model = $this->models_model->get_model($user);

					if ($model)
					{
						$models[] = $model;
					}

					// Get contributor information
					$contributor = $this->contributors_model->get_contributor($user);

					if ($contributor)
					{
						$contributors[] = $contributor;
					}
				}
			}
		}

		// Get the assets from the database
		$this->db->from('assets');
		$this->db->join('users', 'users.user_id = assets.user_id');
		$this->db->where('user_type', 2);
		$this->db->like('asset_title', $keyword);
		$this->db->where_in('asset_type', array(3, 5));
		$this->db->where('deleted', 0);
		$this->db->where('(`assets`.`approved` = 1 OR `users`.`trusted` = 1)', NULL, FALSE);
		$this->db->order_by('asset_id', 'desc');
		$query  = $this->db->get();
		$result = $query->result();

		foreach ($result as $key => $row)
		{
			$assets[$key] = $row;

			// Get model information
			$model = $this->models_model->get_model($row->user_id);

			if ($model)
			{
				$assets[$key]->model = $model;
			}

			// Exchange USD to BTC
			$assets[$key]->asset_cost_btc = $this->cart_model->usd_to_btc($row->asset_cost);
		}

		$return = array(
			'models'       => $models,
			'contributors' => $contributors,
			'assets'       => $assets,
		);

		return $return;
	}

	/**
	 * Find Models
	 *
	 * Find models by a tag from database
	 *
	 * @access public
	 * @return object
	 */
	public function find_models($tag_id = 0)
	{
		// Array to contain user_id's that match this tag
		$users = array();

		// Array of models to return
		$models = array();

		// Get the user_id's from the database
		$this->db->select('user_id');
		$this->db->from('users_tags');
		$this->db->where('tag_id', $tag_id);
		$query  = $this->db->get();
		$result = $query->result();

		foreach ($result as $row)
		{
			$users[] = $row->user_id;
		}

		if ($users)
		{
			$this->db->distinct();
			$this->db->select('assets.user_id');
			$this->db->from('assets');
			$this->db->join('users', 'users.user_id = assets.user_id');
			$this->db->where_in('assets.user_id', $users);
			$this->db->where('deleted', 0);
			$this->db->where('(`assets`.`approved` = 1 OR `users`.`trusted` = 1)', NULL, FALSE);
			$this->db->order_by('asset_id', 'desc');
			$query  = $this->db->get();
			$result = $query->result();

			foreach ($result as $row)
			{
				// Get model information
				$model = $this->models_model->get_model($row->user_id);

				if ($model)
				{
					$models[] = $model;
				}
			}
		}

		return $models;
	}

	/**
	 * Find Contributors
	 *
	 * Find contributors by a tag from database
	 *
	 * @access public
	 * @return object
	 */
	public function find_contributors($tag_id = 0)
	{
		// Array to contain user_id's that match this tag
		$users = array();

		// Array of contributors to return
		$contributors = array();

		// Get the user_id's from the database
		$this->db->select('user_id');
		$this->db->from('users_tags');
		$this->db->where('tag_id', $tag_id);
		$query  = $this->db->get();
		$result = $query->result();

		foreach ($result as $row)
		{
			$users[] = $row->user_id;
		}

		if ($users)
		{
			$this->db->distinct();
			$this->db->select('assets.user_id');
			$this->db->from('assets');
			$this->db->join('users', 'users.user_id = assets.user_id');
			$this->db->where_in('assets.user_id', $users);
			$this->db->where('deleted', 0);
			$this->db->where('(`assets`.`approved` = 1 OR `users`.`trusted` = 1)', NULL, FALSE);
			$this->db->order_by('asset_id', 'desc');
			$query  = $this->db->get();
			$result = $query->result();

			foreach ($result as $row)
			{
				// Get contributor information
				$contributor = $this->contributors_model->get_contributor($row->user_id);

				if ($contributor)
				{
					$contributors[] = $contributor;
				}
			}
		}

		return $contributors;
	}

	/**
	 * Find Assets
	 *
	 * Find assets by a tag from database
	 *
	 * @access public
	 * @return object
	 */
	public function find_assets($tag_id = 0)
	{
		// Array to contain asset_id's that match this tag
		$assets = array();

		// Array of items to return
		$items = array();

		// Get the user_id's from the database
		$this->db->select('asset_id');
		$this->db->from('assets_tags');
		$this->db->where('tag_id', $tag_id);
		$query  = $this->db->get();
		$result = $query->result();

		foreach ($result as $row)
		{
			$assets[] = $row->asset_id;
		}

		if ($assets)
		{
			$this->db->from('assets');
			$this->db->join('users', 'users.user_id = assets.user_id');
			$this->db->where('user_type', 2);
			$this->db->where_in('asset_type', array(3, 5));
			$this->db->where_in('asset_id', $assets);
			$this->db->where('deleted', 0);
			$this->db->where('(`assets`.`approved` = 1 OR `users`.`trusted` = 1)', NULL, FALSE);
			$this->db->order_by('asset_id', 'desc');
			$query = $this->db->get();
			$result = $query->result();

			foreach ($result as $key => $row)
			{
				$items[$key] = $row;

				// Get model information
				$model = $this->models_model->get_model($row->user_id);

				if ($model)
				{
					$items[$key]->model = $model;
				}
			}
		}

		return $items;
	}

}

/* End of file search_model.php */
/* Location: ./application/models/search_model.php */