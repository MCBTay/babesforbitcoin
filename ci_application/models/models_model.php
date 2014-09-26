<?php if (! defined('BASEPATH')) exit('No direct script access');

class Models_model extends CI_Model
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
	 * Get featured
	 *
	 * Get featured from database
	 *
	 * @access public
	 * @return object
	 */
	public function get_featured()
	{
		// Array of models to return
		$models = array();

		$filter_type = $this->input->post('type');
		$filter_tags = $this->input->post('tags');
		$sort = $this->input->post('sort');

		$this->db->select('assets.user_id');
		$this->db->from('assets');
		$this->db->join('users', 'users.user_id = assets.user_id');
		if (!empty($filter_type) && is_array($filter_type))
		{
			$this->db->where_in('assets.asset_type', $filter_type);
		}
		if (!empty($filter_tags) && is_array($filter_tags))
		{
			$this->db->join('assets_tags', 'assets_tags.asset_id = assets.asset_id');
			$this->db->where_in('assets_tags.tag_id', $filter_tags);
		}
		$this->db->where('users.featured', 1);
		$this->db->where('users.user_type', 2);
		$this->db->where('users.disabled', 0);
		$this->db->where('users.user_approved', 1);
		$this->db->where('assets.deleted', 0);
		$this->db->where('(`assets`.`approved` = 1 OR `users`.`trusted` = 1)', NULL, FALSE);
		$this->db->order_by('users.featured_sort', 'asc');
		$query  = $this->db->get();
		$result = $query->result();
		$done   = array();

		foreach ($result as $row)
		{
			if (!in_array($row->user_id, $done))
			{
				// Add to done array to remove duplicates
				$done[] = $row->user_id;

				// Get model information
				$model = $this->get_model($row->user_id);

				if ($model)
				{
					$models[] = $model;
				}
			}
		}

		return $models;
	}

	/**
	 * Get models
	 *
	 * Get models from database
	 *
	 * @access public
	 * @return object
	 */
	public function get_models()
	{
		// Array of models to return
		$models = array();

		$filter_type = $this->input->post('type');
		$filter_tags = $this->input->post('tags');
		$sort        = $this->input->post('sort');

		$this->db->select('assets.user_id');
		$this->db->from('assets');
		$this->db->join('users', 'users.user_id = assets.user_id');
		if (!empty($filter_type) && is_array($filter_type))
		{
			$this->db->where_in('assets.asset_type', $filter_type);
		}
		if (!empty($filter_tags) && is_array($filter_tags))
		{
			$this->db->join('assets_tags', 'assets_tags.asset_id = assets.asset_id');
			$this->db->where_in('assets_tags.tag_id', $filter_tags);
		}
		$this->db->where('users.featured', 0);
		$this->db->where('users.user_type', 2);
		$this->db->where('users.disabled', 0);
		$this->db->where('users.user_approved', 1);
		$this->db->where('assets.deleted', 0);
		$this->db->where('(`assets`.`approved` = 1 OR `users`.`trusted` = 1)', NULL, FALSE);
		if ($sort == 'last_login')
		{
			$this->db->order_by('users.last_login', 'desc');
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
			$this->db->order_by('assets.asset_id', 'desc');
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

				// Get model information
				$model = $this->get_model($row->user_id);

				if ($model)
				{
					$models[] = $model;
				}
			}
		}

		return $models;
	}

	/**
	 * Get model
	 *
	 * Get model from database
	 *
	 * @access public
	 * @return object
	 */
	public function get_model($model_id = 0)
	{
		// Get model from database
		$this->db->from('users');
		$this->db->where('user_id', $model_id);
		$this->db->where('user_type', 2);
		$this->db->where('disabled', 0);
		$this->db->where('user_approved', 1);
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
			$this->db->where('user_id', $model_id);
			$this->db->where('deleted', 0);
			if (!$row->trusted)
			{
				$this->db->where('approved', 1);
			}
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
			$row->num_photos = $row->assets[1] + $row->assets[2] + $row->assets[3] + $row->assets[4];
			$row->num_videos = $row->assets[5];

			// If no default, try first public photo instead
			if (!isset($row->default))
			{
				$this->db->from('assets');
				$this->db->where('user_id', $model_id);
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
		}

		return $row;
	}

	/**
	 * Get assets
	 *
	 * Get assets from database
	 *
	 * @access public
	 * @return object
	 */
	public function get_assets($model_id = 0, $asset_type = 0)
	{
		// Get model first, to make sure it's valid
		$model = $this->get_model($model_id);

		if ($model)
		{
			// Get assets
			$this->db->from('assets');
			$this->db->where('user_id', $model_id);
			$this->db->where('asset_type', $asset_type);
			$this->db->where('deleted', 0);
			if (!$model->trusted)
			{
				$this->db->where('approved', 1);
			}
			$query  = $this->db->get();
			$assets = $query->result();

			foreach ($assets as $asset)
			{
				// Exchange USD to BTC
				$asset->asset_cost_btc = $this->cart_model->usd_to_btc($asset->asset_cost);

				// See if asset owned by user
				$this->db->from('users_purchases');
				$this->db->where('user_id', $this->_user->user_id);
				$this->db->where('asset_id', $asset->asset_id);
				$query = $this->db->get();
				$row   = $query->row();
				$asset->owned = $row ? 1 : 0;

				// Add in the amount of sub photos if photoset
				if ($asset->asset_type == 3)
				{
					$this->db->from('assets');
					$this->db->where('photoset_id', $asset->asset_id);
					$query  = $this->db->get();
					$result = $query->result();
					$photos = count($result) + 1;

					$asset->asset_extra = 'Number of photos: ' . $photos;
				}
				elseif ($asset->asset_type == 5)
				{
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
		else
		{
			return FALSE;
		}
	}

	/**
	 * Get asset
	 *
	 * Get asset from database
	 *
	 * @access public
	 * @return object
	 */
	public function get_asset($asset_id = 0)
	{
		$this->db->from('assets');
		$this->db->where('asset_id', $asset_id);
		$this->db->where_in('asset_type', array(2, 3, 4, 5));
		$this->db->where('deleted', 0);
		//$this->db->where('approved', 1);
		$query = $this->db->get();
		$asset = $query->row();

		if ($asset)
		{
			// Exchange USD to BTC
			$asset->asset_cost_btc = $this->cart_model->usd_to_btc($asset->asset_cost);

			// Add model information
			$asset->model = $this->get_model($asset->user_id);

			// Add in the amount of sub photos if photoset
			if ($asset->asset_type == 3)
			{
				$this->db->from('assets');
				$this->db->where('photoset_id', $asset->asset_id);
				$query  = $this->db->get();
				$result = $query->result();
				$photos = count($result) + 1;

				$asset->asset_extra = 'Number of photos: ' . $photos;
			}
			elseif ($asset->asset_type == 5)
			{
				$duration = shell_exec(BIN_PATH . 'ffmpeg -i "' . CDN_URL . $asset->video . '" 2>&1 | grep Duration | cut -d " " " -f 4 | rev | cut -c 2- | rev');

				if (strpos($duration, '.') !== FALSE)
				{
					$duration = substr($duration, 0, strpos($duration, '.'));
				}

				if (!empty($duration))
				{
					$asset->asset_extra = 'Duration: ' . $duration;
				}
			}

			return $asset;
		}
		else
		{
			return FALSE;
		}
	}

	/**
	 * Get mine public
	 *
	 * Get mine public photos from database
	 *
	 * @access public
	 * @return object
	 */
	public function get_mine_public()
	{
		// Get public photos
		$this->db->from('assets');
		$this->db->where('user_id', $this->_user->user_id);
		$this->db->where('asset_type', 1);
		$this->db->where('deleted', 0);
		$this->db->order_by('asset_id', 'desc');
		$query  = $this->db->get();
		$assets = $query->result();

		return $assets;
	}

	/**
	 * Get mine private
	 *
	 * Get mine private photos from database
	 *
	 * @access public
	 * @return object
	 */
	public function get_mine_private()
	{
		// Get private photos
		$this->db->from('assets');
		$this->db->where('user_id', $this->_user->user_id);
		$this->db->where('asset_type', 2);
		$this->db->where('deleted', 0);
		$this->db->order_by('asset_id', 'desc');
		$query  = $this->db->get();
		$assets = $query->result();

		return $assets;
	}

	/**
	 * Get mine photosets
	 *
	 * Get mine photosets from database
	 *
	 * @access public
	 * @return object
	 */
	public function get_mine_photosets()
	{
		// Get photosets
		$this->db->from('assets');
		$this->db->where('user_id', $this->_user->user_id);
		$this->db->where('asset_type', 3);
		$this->db->where('deleted', 0);
		$this->db->order_by('asset_id', 'desc');
		$query  = $this->db->get();
		$assets = $query->result();

		foreach ($assets as $asset)
		{
			// Exchange USD to BTC
			$asset->asset_cost_btc = $this->cart_model->usd_to_btc($asset->asset_cost);

			// Get subphotos
			$asset->photos = $this->get_mine_photosets_photos($asset->asset_id);

			$photos = count($asset->photos) + 1;

			$asset->asset_extra = 'Number of photos: ' . $photos;
		}

		return $assets;
	}

	/**
	 * Get mine photosets photos
	 *
	 * Get mine photosets photos from database
	 *
	 * @access public
	 * @return object
	 */
	public function get_mine_photosets_photos($photoset_id)
	{
		// Get photosets photos
		$this->db->from('assets');
		$this->db->where('user_id', $this->_user->user_id);
		$this->db->where('asset_type', 4);
		$this->db->where('deleted', 0);
		$this->db->where('photoset_id', $photoset_id);
		$this->db->order_by('asset_id', 'desc');
		$query  = $this->db->get();
		$assets = $query->result();

		return $assets;
	}

	/**
	 * Get mine videos
	 *
	 * Get mine videos photos from database
	 *
	 * @access public
	 * @return object
	 */
	public function get_mine_videos()
	{
		// Get videos
		$this->db->from('assets');
		$this->db->where('user_id', $this->_user->user_id);
		$this->db->where('asset_type', 5);
		$this->db->where('deleted', 0);
		$this->db->order_by('asset_id', 'desc');
		$query  = $this->db->get();
		$assets = $query->result();

		foreach ($assets as $asset)
		{
			// Exchange USD to BTC
			$asset->asset_cost_btc = $this->cart_model->usd_to_btc($asset->asset_cost);

			$asset->mimetype = $this->assets_model->get_mimetype($asset->video);

			$duration = shell_exec(BIN_PATH . 'ffmpeg -i "' . CDN_URL . $asset->video . '" 2>&1 | grep Duration | cut -d " " " -f 4 | rev | cut -c 2- | rev');

			if (strpos($duration, '.') !== FALSE)
			{
				$duration = substr($duration, 0, strpos($duration, '.'));
			}

			if (!empty($duration))
			{
				$asset->asset_extra = 'Duration: ' . $duration;
			}
		}

		return $assets;
	}

	/**
	 * Get fetishes
	 *
	 * Get fetishes from database
	 *
	 * @access public
	 * @return object
	 */
	public function get_fetishes()
	{
		$this->db->from('tags');
		$this->db->like('tag', 'fetish-', 'after');
		$this->db->order_by('tag', 'asc');
		$query  = $this->db->get();
		$result = $query->result();

		foreach ($result as $row)
		{
			// Prettify fetish name by removing "fetish-"" then replacing hyphens with spaces and uppercasing words
			$row->fetish = ucwords(str_replace('-', ' ', substr($row->tag, 7)));
		}

		return $result;
	}


	/**
	 * Send Assets
	 *
	 * Send assets to contributor for free
	 *
	 * @access public
	 * @return object
	 */
	public function send_assets($contributor_id)
	{
		if (!$this->_user->user_approved)
		{
			redirect();
		}

		$assets = (array) $this->input->post('gifts');

		$photos    = 0;
		$photosets = array();
		$videos    = array();

		foreach ($assets as $asset)
		{
			$this->db->from('assets');
			$this->db->where('asset_id', $asset);
			$this->db->where_in('asset_type', array(2, 3, 4, 5));
			$this->db->where('user_id', $this->_user->user_id);
			$this->db->where('deleted', 0);
			if (!$this->_user->trusted)
			{
				$this->db->where('approved', 1);
			}
			$query = $this->db->get();
			$gift  = $query->row();

			if ($gift)
			{
				// Make sure this hasn't already been purchased or gifted
				$this->db->from('users_purchases');
				$this->db->where('asset_id', $gift->asset_id);
				$this->db->where('user_id', $contributor_id);
				$query = $this->db->get();
				$row   = $query->row();

				if (!$row)
				{
					$data = array(
						'asset_id'         => $gift->asset_id,
						'user_id'          => $contributor_id,
						'purchase_created' => time(),
					);
					$this->db->insert('users_purchases', $data);


					switch ($gift->asset_type)
					{
						case 2:
						case 4:
							$photos++;
						break;
						case 3:
							$photosets[] = $gift->asset_title;
						break;
						case 5:
							$videos[] = $gift->asset_title;
						break;
					}
				}
			}
		}

		$contributor = $this->user_model->get_user($contributor_id);
		$total_photo = $photos + count($photosets);
		$total_video = count($videos);

		if ($contributor->notify_email_photos && $total_photo > 0)
		{
			// Data array to be used in views
			$data = array(
				'display_name' => $this->_user->display_name,
				'photos'       => $total_photo,
			);

			// email template
			$message = $this->load->view('emails/new_photo', $data, true);

			$this->emailer_model->send(
				$mail_to         = $contributor->email,
				$mail_subject    = SITE_TITLE . ' New Photo' . ($total_photo == 1 ? '' : 's'),
				$mail_message    = $message,
				$mail_from_email = 'info@babesforbitcoin.com',
				$mail_from_name  = SITE_TITLE,
				$tag             = 'user-notifications'
			);
		}

		if ($contributor->notify_text_photos && $total_photo > 0)
		{
			// Data array to be used in views
			$data = array(
				'display_name' => $this->_user->display_name,
				'photos'       => $total_photo,
			);

			// email template
			$message = $this->load->view('emails/new_photo_text', $data, true);

			$this->emailer_model->send(
				$mail_to         = $contributor->text_number . '@' . $this->notifications_model->get_carrier_domain($contributor->text_carrier),
				$mail_subject    = '',
				$mail_message    = $message,
				$mail_from_email = 'info@babesforbitcoin.com',
				$mail_from_name  = SITE_TITLE,
				$tag             = 'user-notifications'
			);
		}

		if ($contributor->notify_email_videos && $total_video > 0)
		{
			// Data array to be used in views
			$data = array(
				'display_name' => $this->_user->display_name,
				'videos'       => $total_video,
			);

			// email template
			$message = $this->load->view('emails/new_video', $data, true);

			$this->emailer_model->send(
				$mail_to         = $contributor->email,
				$mail_subject    = SITE_TITLE . ' New Video' . ($total_video == 1 ? '' : 's'),
				$mail_message    = $message,
				$mail_from_email = 'info@babesforbitcoin.com',
				$mail_from_name  = SITE_TITLE,
				$tag             = 'user-notifications'
			);
		}

		if ($contributor->notify_text_videos && $total_video > 0)
		{
			// Data array to be used in views
			$data = array(
				'display_name' => $this->_user->display_name,
				'videos'       => $total_video,
			);

			// email template
			$message = $this->load->view('emails/new_video_text', $data, true);

			$this->emailer_model->send(
				$mail_to         = $contributor->text_number . '@' . $this->notifications_model->get_carrier_domain($contributor->text_carrier),
				$mail_subject    = '',
				$mail_message    = $message,
				$mail_from_email = 'info@babesforbitcoin.com',
				$mail_from_name  = SITE_TITLE,
				$tag             = 'user-notifications'
			);
		}

		if ($photos > 0 || count($photosets) > 0 || count($videos) > 0)
		{
			ob_start();
			?>
			<p><?php echo $this->_user->display_name; ?> has sent you the following:</p>
			<dl>
				<?php if ($photos > 0 || count($photosets) > 0): ?>
					<dt>Photos -</dt>
						<dd>
							<?php if ($photos > 0): ?>
								<a href="<?php echo base_url(); ?>my-files/model/<?php echo $this->_user->user_id; ?>"><?php echo $photos; ?> Individual Photos</a><br>
							<?php endif; ?>
							<?php foreach ($photosets as $photoset): ?>
								<a href="<?php echo base_url(); ?>my-files/model/<?php echo $this->_user->user_id; ?>"><?php echo $photoset; ?></a><br>
							<?php endforeach; ?>
						</dd>
				<?php endif; ?>
				<?php if (count($videos) > 0): ?>
					<dt>Videos -</dt>
						<dd>
							<?php foreach ($videos as $video): ?>
								<a href="<?php echo base_url(); ?>my-files/model/<?php echo $this->_user->user_id; ?>"><?php echo $video; ?></a><br>
							<?php endforeach; ?>
						</dd>
				<?php endif; ?>
			</dl>
			<?php
			$message = ob_get_clean();

			$data = array(
				'user_id_from'    => $this->_user->user_id,
				'user_id_to'      => $contributor_id,
				'html'            => 1,
				'message'         => $message,
				'message_created' => time(),
			);
			$this->db->insert('messages', $data);
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
	public function make_withdrawal($amount, $site_fee = 0)
	{
		// One final check to be certain
		if ($amount >= 1 && $amount <= $this->_user->funds_usd)
		{
			$data = array(
				'user_id'             => $this->_user->user_id,
				'currency'            => 'usd',
				'withdrawal_amount'   => $amount,
				'funds_usd_remaining' => $this->_user->funds_usd - $amount,
				'site_fee'            => $site_fee,
				'withdrawal_created'  => time(),
			);

			$this->db->insert('users_withdrawals', $data);
			$withdrawal_id = $this->db->insert_id();

			$this->db->set('funds_usd', $data['funds_usd_remaining']);
			$this->db->where('user_id', $this->_user->user_id);
			$this->db->update('users');

			return $withdrawal_id;
		}
	}

	/**
	 * Make Withdrawal BTC
	 *
	 * Make a BTC withdrawal on a model account
	 *
	 * @access public
	 * @return object
	 */
	public function make_withdrawal_btc($amount)
	{
		// One final check to be certain
		if ($amount >= 0.000001 && $amount <= $this->_user->funds_btc)
		{
			$data = array(
				'user_id'             => $this->_user->user_id,
				'currency'            => 'btc',
				'withdrawal_amount'   => $amount,
				'funds_btc_remaining' => $this->_user->funds_btc - $amount,
				'withdrawal_created'  => time(),
			);

			$this->db->insert('users_withdrawals', $data);
			$withdrawal_id = $this->db->insert_id();

			$this->db->set('funds_btc', $data['funds_btc_remaining']);
			$this->db->where('user_id', $this->_user->user_id);
			$this->db->update('users');

			return $withdrawal_id;
		}
	}

	/**
	 * Update Withdrawal
	 *
	 * Update a withdrawal on a model account
	 *
	 * @access public
	 * @return object
	 */
	public function update_withdrawal($withdrawal_id = 0, $transaction_id = '', $transaction_error = '')
	{
		$data = array(
			'transaction_id'    => $transaction_id,
			'transaction_error' => $transaction_error,
		);

		if (empty($transaction_id))
		{
			$data['refunded'] = 1;

			$this->db->from('users_withdrawals');
			$this->db->where('withdrawal_id', $withdrawal_id);
			$this->db->where('user_id', $this->_user->user_id);
			$query = $this->db->get();
			$row   = $query->row();

			if ($row)
			{
				$this->db->set('funds_usd', $row->funds_usd_remaining + $row->withdrawal_amount);
				$this->db->where('user_id', $this->_user->user_id);
				$this->db->update('users');
			}
		}

		$this->db->where('withdrawal_id', $withdrawal_id);
		$this->db->where('user_id', $this->_user->user_id);
		$this->db->update('users_withdrawals', $data);
	}

	/**
	 * Update Withdrawal BTC
	 *
	 * Update a BTC withdrawal on a model account
	 *
	 * @access public
	 * @return object
	 */
	public function update_withdrawal_btc($withdrawal_id = 0, $transaction_id = '', $transaction_error = '')
	{
		$data = array(
			'transaction_id'    => $transaction_id,
			'transaction_error' => $transaction_error,
		);

		if (!empty($transaction_error))
		{
			$data['refunded'] = 1;

			$this->db->from('users_withdrawals');
			$this->db->where('withdrawal_id', $withdrawal_id);
			$this->db->where('user_id', $this->_user->user_id);
			$query = $this->db->get();
			$row   = $query->row();

			if ($row)
			{
				$this->db->set('funds_btc', $row->funds_btc_remaining + $row->withdrawal_amount);
				$this->db->where('user_id', $this->_user->user_id);
				$this->db->update('users');
			}
		}

		$this->db->where('withdrawal_id', $withdrawal_id);
		$this->db->where('user_id', $this->_user->user_id);
		$this->db->update('users_withdrawals', $data);
	}

	/**
	 * Set Default
	 *
	 * Set default photo and remove existing
	 *
	 * @access public
	 * @return object
	 */
	public function set_default($asset_id)
	{
		// Remove existing default photo
		$this->db->set('default', 0);
		$this->db->where('user_id', $this->_user->user_id);
		$this->db->update('assets');

		// Add new default photo
		$this->db->set('default', 1);
		$this->db->where('user_id', $this->_user->user_id);
		$this->db->where('asset_id', $asset_id);
		$this->db->update('assets');

		return TRUE;
	}

}

/* End of file models_model.php */
/* Location: ./application/models/models_model.php */