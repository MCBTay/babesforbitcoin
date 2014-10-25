<?php if (! defined('BASEPATH')) exit('No direct script access');

class Assets_model extends CI_Model
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
	 * Insert Public
	 *
	 * Insert a public photo into the database
	 *
	 * @access public
	 * @return n/a
	 */
	public function insert_public()
	{
		// Uploaded photo
		$uploaded_photo = $this->input->post('uploaded_photo');

		// Get image width, height
		list($width, $height) = @getimagesize('./assets/uploads/' . $uploaded_photo);

		// See if asset is HD
		if (isset($height) && $height >= 720)
		{
			$asset_hd = 1;
		}
		else
		{
			$asset_hd = 0;
		}

		// See if this will be the default
		$default = (int) $this->input->post('default');

		if ($default)
		{
			// Erase current default
			$this->db->set('default', 0);
			$this->db->where('user_id', $this->_user->user_id);
			$this->db->update('assets');
		}

		// Add asset to the database
		$data = array(
			'user_id'       => $this->_user->user_id,
			'asset_type'    => 1,
			'asset_title'   => $this->input->post('asset_title'),
			'filename'      => $uploaded_photo,
			'asset_hd'      => $asset_hd,
			'default'       => $default,
			'approved'      => 1,
			'approved_by'   => $this->_user->user_id,
			'approved_on'   => time(),
			'asset_created' => time(),
		);
		$this->db->insert('assets', $data);
	}

	/**
	 * Insert Private
	 *
	 * Insert a private photo into the database
	 *
	 * @access public
	 * @return n/a
	 */
	public function insert_private()
	{
		// Uploaded photo
		$uploaded_photos = (array) $this->input->post('uploaded_photo');
		$asset_titles    = (array) $this->input->post('asset_title');

		foreach ($uploaded_photos as $key => $uploaded_photo)
		{
			if (!empty($uploaded_photo))
			{
				// Get image width, height
				list($width, $height) = @getimagesize('./assets/uploads/' . $uploaded_photo);

				// See if asset is HD
				if (isset($height) && $height >= 720)
				{
					$asset_hd = 1;
				}
				else
				{
					$asset_hd = 0;
				}

				// Add asset to the database
				$data = array(
					'user_id'       => $this->_user->user_id,
					'asset_type'    => 2,
					'asset_title'   => $asset_titles[$key],
					'filename'      => $uploaded_photo,
					'asset_hd'      => $asset_hd,
					//'approved'      => 1,
					//'approved_by'   => $this->_user->user_id,
					//'approved_on'   => time(),
					'asset_created' => time(),
				);
				$this->db->insert('assets', $data);

				// Move to AWS CDN
				$this->aws_model->move_file('./assets/uploads/', $uploaded_photo);
				$this->aws_model->move_file('./assets/uploads/', 'lrg-'  . strtolower($uploaded_photo));
				$this->aws_model->move_file('./assets/uploads/', 'med-'  . strtolower($uploaded_photo));
				$this->aws_model->move_file('./assets/uploads/', 'sml-'  . strtolower($uploaded_photo));
				$this->aws_model->move_file('./assets/uploads/', 'tall-' . strtolower($uploaded_photo));
			}
		}
	}

	/**
	 * Insert Photoset
	 *
	 * Insert a photoset into the database
	 *
	 * @access public
	 * @return n/a
	 */
	public function insert_photoset($photoset_id)
	{
		// Photoset title
		$photoset_title = $this->input->post('photoset_title');
		$asset_title    = $this->input->post('asset_title');

		// Child photos
		$child_asset_title    = (array) $this->input->post('child_asset_title');
		$child_uploaded_photo = (array) $this->input->post('child_uploaded_photo');

		if (empty($photoset_title)) {
            $photoset_title = 'Set of ' . count($child_uploaded_photo) . ' photos';
        }

        if ($photoset_id)
        {
            // Add asset to the database
            $data = array(
                'asset_cost'    => $this->input->post('asset_cost'),
                'asset_title'   => $photoset_title,
            );

            $this->db->where('photoset_id', $photoset_id);
            $this->db->update('photosets', $data);
        }

        $counter = 1;
		foreach ($child_uploaded_photo as $key => $child_photo)
		{
			if (!empty($child_photo))
			{
				// Get asset title
				$child_title = $child_asset_title[$key];

				// Get image width, height
				list($width, $height) = @getimagesize('./assets/uploads/' . $child_photo);

				// See if asset is HD
				if (isset($height) && $height >= 720)
				{
					$asset_hd = 1;
				}
				else
				{
					$asset_hd = 0;
				}

                if ($counter == 1)
                {
                    // Add asset to the database
                    $data = array(
                        'user_id'       => $this->_user->user_id,
                        'asset_cost'    => $this->input->post('asset_cost'),
                        'asset_title'   => $photoset_title,
                        'asset_created' => time(),
                    );
                    $this->db->insert('photosets', $data);

                    $photoset_id = $this->db->insert_id();
                }

				// Add asset to the database
				$data = array(
					'user_id'       => $this->_user->user_id,
					'asset_type'    => 4,
					'photoset_id'   => $photoset_id,
					'asset_title'   => $child_title,
					'filename'      => $child_photo,
					'asset_hd'      => $asset_hd,
					'asset_created' => time(),
				);
				$this->db->insert('assets', $data);

                //default cover photo
                if ($counter == 1)
                {
                    $data = array(
                        'cover_photo_id' => $this->db->insert_id(),
                    );
                    $this->db->where('photoset_id', $photoset_id);
                    $this->db->update('photosets', $data);
                }



				// Move to AWS CDN
				$this->aws_model->move_file('./assets/uploads/', $child_photo);
				$this->aws_model->move_file('./assets/uploads/', 'lrg-'  . strtolower($child_photo));
				$this->aws_model->move_file('./assets/uploads/', 'med-'  . strtolower($child_photo));
				$this->aws_model->move_file('./assets/uploads/', 'sml-'  . strtolower($child_photo));
				$this->aws_model->move_file('./assets/uploads/', 'tall-' . strtolower($child_photo));
			}
            $counter++;
		}
	}

	/**
	 * Insert Video
	 *
	 * Insert a video into the database
	 *
	 * @access public
	 * @return n/a
	 */
	public function insert_video($video_id)
	{
		// Uploaded assets
		$uploaded_photo = $this->input->post('uploaded_photo');
		$uploaded_video = $this->input->post('uploaded_video');
		$uploaded_thumb = $this->input->post('uploaded_thumb');
		$asset_title    = $this->input->post('asset_title');
		$video_title    = $this->input->post('video_title');
		$chosen_title   = $this->input->post('chosen_title');

		if ($video_id)
		{
			// Update asset to the database
			$data = array(
				'asset_cost'    => $this->input->post('asset_cost'),
				'asset_title'   => $chosen_title,
			);
			$this->db->where('asset_id', $video_id);
			$this->db->update('assets', $data);
		}
		else
		{
			// Set asset title
			if (!empty($chosen_title))
			{
				$asset_title = $chosen_title;
			}
			elseif (!empty($video_title))
			{
				$asset_title = $video_title;
			}

			// Set uploaded photo
			if (empty($uploaded_photo))
			{
				$uploaded_photo = $uploaded_thumb;
			}

            // Move to AWS CDN
            $this->aws_model->move_file('./assets/uploads/', $uploaded_photo);
            $this->aws_model->move_file('./assets/uploads/', 'lrg-'  . strtolower($uploaded_photo));
            $this->aws_model->move_file('./assets/uploads/', 'med-'  . strtolower($uploaded_photo));
            $this->aws_model->move_file('./assets/uploads/', 'sml-'  . strtolower($uploaded_photo));
            $this->aws_model->move_file('./assets/uploads/', 'tall-' . strtolower($uploaded_photo));

			// Get thumb width, height
			list($width, $height) = @getimagesize('./assets/uploads/' . $uploaded_thumb);

			// See if asset is HD
			if (isset($height) && $height >= 720)
			{
				$asset_hd = 1;
			}
			else
			{
				$asset_hd = 0;
			}

			// Add asset to the database
			$data = array(
				'user_id'       => $this->_user->user_id,
				'asset_type'    => 5,
				'asset_cost'    => $this->input->post('asset_cost'),
				'asset_title'   => $asset_title,
				'filename'      => $uploaded_photo,
				'video'         => $uploaded_video,
				'asset_hd'      => $asset_hd,
				'asset_created' => time(),
			);
			$this->db->insert('assets', $data);

			// Move to AWS CDN
			$this->aws_model->move_file('./assets/uploads/', $uploaded_thumb);
			$this->aws_model->move_file('./assets/uploads/', 'lrg-'  . strtolower($uploaded_thumb));
			$this->aws_model->move_file('./assets/uploads/', 'med-'  . strtolower($uploaded_thumb));
			$this->aws_model->move_file('./assets/uploads/', 'sml-'  . strtolower($uploaded_thumb));
			$this->aws_model->move_file('./assets/uploads/', 'tall-' . strtolower($uploaded_thumb));
		}
	}

	/**
	 * Get Mimetype
	 *
	 * Get video mimetype from file extention
	 *
	 * @access public
	 * @return n/a
	 */
	public function get_mimetype($filename)
	{
		// Set mimetype value to ensure it exists
		$mimetype = '';

		// Separate the filename into an array
		$filename = explode('.', $filename);

		// Pop the last item out of array (file extension)
		$extension = array_pop($filename);

		// Determine mimetype from extension
		switch ($extension)
		{
			case 'mpeg':
			case 'mpg':
				$mimetype = 'video/mpeg';
			break;

			case 'webm':
				$mimetype = 'video/webm';
			break;

			case 'flv':
				$mimetype = 'video/x-flv';
			break;

			case 'ogv':
				$mimetype = 'video/ogg';
			break;

			case 'wmv':
				$mimetype = 'video/x-ms-wmv';
			break;

			default:
				$mimetype = 'video/mp4';
			break;
		}

		return $mimetype;
	}

	/**
	 * Remove
	 *
	 * Remove an asset from the database
	 *
	 * @access public
	 * @return n/a
	 */
	public function remove($asset_id)
	{
		$this->db->from('assets');
		$this->db->where('asset_id', $asset_id);
		$this->db->where('user_id', $this->_user->user_id);
		$query = $this->db->get();
		$row   = $query->row();

		if ($row)
		{
			if ($row->asset_type == 1 || $row->asset_type == 2)
			{
				$this->db->set('deleted', 1);
				$this->db->where('asset_id', $asset_id);
				$this->db->or_where('photoset_id', $asset_id);
				$this->db->update('assets');
			}
		}
	}

	/**
	 * Get Asset
	 *
	 * Get Asset, regardless of approval/deleted/etc
	 *
	 * @access public
	 * @return n/a
	 */
	public function get_asset($asset_id)
	{
		$this->db->from('assets');
		$this->db->where('asset_id', $asset_id);
		$this->db->where('user_id', $this->_user->user_id);
		$query = $this->db->get();
		$row   = $query->row();

		if ($row)
		{
			if ($row->asset_type == 4)
			{
				// Get sub photos
				$this->db->from('assets');
				$this->db->where('photoset_id', $asset_id);
				$this->db->where('user_id', $this->_user->user_id);
				$this->db->order_by('asset_id', 'asc');
				$query = $this->db->get();
				$row->photos = $query->result();
			}
			elseif ($row->asset_type == 5)
			{
				$row->mimetype = $this->get_mimetype($row->video);
			}
		}
        else
        {
            $this->db->from('photosets');
            $this->db->where('photoset_id', $asset_id);
            $this->db->where('user_id', $this->_user->user_id);
            $query = $this->db->get();
            $row = $query->row();

            if ($row)
            {
                // Get sub photos
                $this->db->from('assets');
                $this->db->where('photoset_id', $row->photoset_id);
                $this->db->where('user_id', $this->_user->user_id);
                $this->db->order_by('asset_id', 'asc');
                $query = $this->db->get();
                $row->photos = $query->result();
            }



        }

		return $row;
	}

    /**
     * Get Asset Type
     *
     * Get Asset Type
     *
     * @access public
     * @return n/a
     */
    public function get_asset_simple($asset_id)
    {
        $this->db->from('assets');
        $this->db->where('asset_id', $asset_id);
        $query = $this->db->get();
        $row   = $query->row();
        return $row;
    }

    /**
     * Get Photoset Cover
     *
     * Get Photoset Cover photo
     *
     * @access public
     * @return n/a
     */
    public function get_photoset_cover($photoset_id)
    {
        $this->db->from('photosets');
        $this->db->join('assets', 'assets.asset_id = photosets.cover_photo_id');
        $this->db->where('photosets.photoset_id', $photoset_id);
        $query = $this->db->get();
        $row   = $query->row();

        return $row;
    }

    /**
     * Get Photoset Photos
     *
     * Get Photoset Photos,
     *
     * @access public
     * @return n/a
     */
    public function get_photoset($photoset_id)
    {
        $this->db->from('photosets');
        $this->db->where('photosets.photoset_id', $photoset_id);
        $query = $this->db->get();
        $row   = $query->row();

        if ($row)
        {
            $this->db->from('assets');
            $this->db->where('photoset_id', $photoset_id);
            $this->db->order_by('asset_id', 'asc');
            $query = $this->db->get();

            $row->photos = $query->result();

            $row->cover_photo = $this->get_photoset_cover($photoset_id);

            $this->db->from('users');
            $this->db->where('user_id', $row->user_id);
            $query = $this->db->get();
            $user = $query->row();

            $row->display_name = $user->display_name;
        }

        return $row;
    }

    /**
     * Get User Photosets
     *
     * Get user's photosets
     *
     * @access public
     * @return n/a
     */
    public function get_user_photosets($user_id)
    {
        $this->db->from('photosets');
        $this->db->where('user_id', $user_id);
        $query = $this->db->get();
        $photosets   = $query->result();

        foreach($photosets as $photoset)
        {
            $this->db->from('assets');
            $this->db->where('photoset_id', $photoset->photoset_id);
            $this->db->order_by('asset_id', 'desc');
            $query = $this->db->get();

            $photoset->photos = $query->result();

            $photoset->cover_photo = $this->get_photoset_cover($photoset->photoset_id);
        }

        return $photosets;
    }
}

/* End of file assets_model.php */
/* Location: ./application/models/assets_model.php */