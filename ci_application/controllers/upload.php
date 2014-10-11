<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Upload extends CI_Controller
{

	// Object containing currently logged in user
	public $_user;

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

		// If user isn't logged in
		if (!$this->_user)
		{
			// Redirect to login
			redirect('account/login');
		}

		// Load aws model
		$this->load->model('aws_model');
	}

	/**
	 * Upload - Index
	 *
	 * The index page for the upload controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function index()
	{
		// Data array to be used in views
		$data = array(
			'class' => 'upload',
			'title' => 'Upload Photos | Videos',
		);

		// Load views
		$this->load->view('templates/header',     $data);
		$this->load->view('templates/navigation', $data);
		$this->load->view('pages/upload/index',   $data);
		$this->load->view('templates/footer-nav', $data);
		$this->load->view('templates/footer',     $data);
	}

	/**
	 * Upload - Success
	 *
	 * The success page for the upload controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function success()
	{
		// Data array to be used in views
		$data = array(
			'class'   => 'upload',
			'title'   => 'Upload Photos | Videos',
			'success' => TRUE,
		);

		// Load views
		$this->load->view('templates/header',     $data);
		$this->load->view('templates/navigation', $data);
		$this->load->view('pages/upload/index',   $data);
		$this->load->view('templates/footer-nav', $data);
		$this->load->view('templates/footer',     $data);
	}

	/**
	 * Upload - Public Photo
	 *
	 * The public page for the upload controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function public_photo()
	{
		// Data array to be used in views
		$data = array(
			'class'  => 'upload',
			'title'  => 'Upload Public Photo',
			'public' => $this->models_model->get_mine_public(),
		);

		// Set validation error delimiters
		$this->form_validation->set_error_delimiters('', '');

		// Set validation rules
		$this->form_validation->set_rules('uploaded_photo', 'Photo', 'trim|required|xss_clean');

		if ($this->form_validation->run() == TRUE)
		{
			// Crop the image using user selected crop
			$this->upload_model->user_crop();

			// Uploaded photo
			$uploaded_photo = $this->input->post('uploaded_photo');

			// Add asset to the database
			$this->assets_model->insert_public();

			// Move to AWS CDN
			$this->aws_model->move_file('./assets/uploads/', $uploaded_photo);
			$this->aws_model->move_file('./assets/uploads/', 'lrg-'  . strtolower($uploaded_photo));
			$this->aws_model->move_file('./assets/uploads/', 'med-'  . strtolower($uploaded_photo));
			$this->aws_model->move_file('./assets/uploads/', 'sml-'  . strtolower($uploaded_photo));
			$this->aws_model->move_file('./assets/uploads/', 'tall-' . strtolower($uploaded_photo));

			// Redirect with success message
			redirect('upload/success');
		}

		// Load views
		$this->load->view('templates/header',     $data);
		$this->load->view('templates/navigation', $data);
		$this->load->view('pages/upload/public',  $data);
		$this->load->view('templates/footer-nav', $data);
		$this->load->view('templates/footer',     $data);
	}

	/**
	 * Upload - Private Photo
	 *
	 * The private page for the upload controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function private_photo()
	{
		// Data array to be used in views
		$data = array(
			'class' => 'upload',
			'title' => 'Upload Private Photo',
		);

		// Set validation error delimiters
		$this->form_validation->set_error_delimiters('', '');

		// Set validation rules
		$this->form_validation->set_rules('uploaded_photo[]', 'Photo', 'trim|required|xss_clean');

		if ($this->form_validation->run() == TRUE)
		{
			// Add asset to the database
			$this->assets_model->insert_private();

			// Redirect with success message
			redirect('upload/success');
		}

		// Load views
		$this->load->view('templates/header',     $data);
		$this->load->view('templates/navigation', $data);
		$this->load->view('pages/upload/private', $data);
		$this->load->view('templates/footer-nav', $data);
		$this->load->view('templates/footer',     $data);
	}

	/**
	 * Upload - Photoset
	 *
	 * The photoset page for the upload controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function photoset($asset_id = 0)
	{
		// Data array to be used in views
		$data = array(
			'class' => 'upload',
			'title' => ($asset_id ? 'Edit' : 'Upload') . ' Photoset'
		);

		if ($asset_id)
		{
			// Get asset
            $asset = $this->assets_model->get_photoset($asset_id);

			$data['asset'] = $asset;
		}

		// Set validation error delimiters
		$this->form_validation->set_error_delimiters('', '');

		// Set validation rules
		$this->form_validation->set_rules('photoset_title',         'Photoset Title',  'trim|xss_clean');
		$this->form_validation->set_rules('asset_cost',             'Photoset Cost',   'trim|required|callback_is_monetary|xss_clean');
		if (!$asset_id)
		{
			$this->form_validation->set_rules('child_uploaded_photo[]', 'Photoset Photos', 'trim|required|xss_clean');
		}

		if ($this->form_validation->run() == TRUE)
		{
            if ($this->input->post('change_cover_photo'))
            {
                $new_cover_id = $this->input->post('change_cover_photo');

                if ($new_cover_id)
                {
                    // Add asset to the database
                    $data = array(
                        'asset_cost'    => $this->input->post('asset_cost'),
                        'asset_title'   => $this->input->post('photoset_title'),
                    );

                    $this->db->where('photoset_id', $new_cover_id);
                    $this->db->update('assets', $data);
                }

                //only one button should be pressed at once, but just as a safety check confirm
                if (count($new_cover_id) == 1)
                {
                    $this->models_model->change_cover_photo($asset_id, $new_cover_id);
                    redirect('upload/photoset/'. $asset_id);
                }


            } else {
                // Add assets to the database
                $this->assets_model->insert_photoset($asset_id);
                // Redirect with success message
                redirect('upload/photoset/'. $asset_id);
            }

		}
		else
		{
			// See if we actually tried submitting
			if (count($_POST))
			{
                // Get POSTed values
                $asset_title    = $this->input->post('asset_title');
                //$uploaded_photo = $this->input->post('uploaded_photo');
                // Make sure they actually uploaded a cover photo
                if (!empty($uploaded_photo))
                {
                    $data['cover_photo'] = '<img src="' . base_url() . 'assets/uploads/' . $uploaded_photo . '" width="536"><input id="asset_title" name="asset_title" type="hidden" value="' . $asset_title . '"><input id="uploaded_photo" name="uploaded_photo" type="hidden" value="' . $uploaded_photo . '">';
                }

                // Get POSTed values
                $child_asset_title    = (array) $this->input->post('child_asset_title');
                $child_uploaded_photo = (array) $this->input->post('child_uploaded_photo');

                // Make sure they actually uploaded at least one photoset photo
                if (count($child_uploaded_photo) >= 1 && !empty($child_uploaded_photo[0]))
                {
                    $data['photoset_photos'] = '';

                    foreach ($child_uploaded_photo as $key => $child_photo)
                    {
                        if (isset($child_asset_title[$key]))
                        {
                            $child_title = $child_asset_title[$key];
                        }

                        $data['photoset_photos'] .= '<p style="margin-bottom: 15px; margin-top: 0;"><img src="' . base_url() . 'assets/uploads/' . $child_photo . '" width="536"><input name="child_asset_title[]" type="hidden" value="' . $child_title . '"><input name="child_uploaded_photo[]" type="hidden" value="' . $child_photo . '"></p>';
                    }
                }

			}
		}

		// Load views
		$this->load->view('templates/header',      $data);
		$this->load->view('templates/navigation',  $data);
		$this->load->view('pages/upload/photoset', $data);
		$this->load->view('templates/footer-nav',  $data);
		$this->load->view('templates/footer',      $data);
	}

	/**
	 * Upload - Video
	 *
	 * The video page for the upload controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function video($asset_id = 0)
	{
		// Data array to be used in views
		$data = array(
			'class' => 'upload',
			'title' => ($asset_id ? 'Edit' : 'Upload') . ' Video',
		);

		if ($asset_id)
		{
			// Get asset
			$asset = $this->assets_model->get_asset($asset_id);

			if (!$asset || $asset->asset_type != 5)
			{
				redirect('upload/video');
			}

			$data['asset'] = $asset;
		}

		// Set validation error delimiters
		$this->form_validation->set_error_delimiters('', '');

		// Set validation rules
		$this->form_validation->set_rules('chosen_title',   'Video Title', 'trim|xss_clean');
		$this->form_validation->set_rules('asset_cost',     'Video Cost',  'trim|required|callback_is_monetary|xss_clean');
		if (!$asset_id)
		{
			$this->form_validation->set_rules('uploaded_video', 'Video',       'trim|required|xss_clean');
		}

		if ($this->form_validation->run() == TRUE)
		{
			// Add asset to the database
			$this->assets_model->insert_video($asset_id);

			// Redirect with success message
			redirect('upload/success');
		}
		else
		{
			// See if we actually tried submitting
			if (count($_POST))
			{
				// Get POSTed values
				$asset_title    = $this->input->post('asset_title');
				$uploaded_photo = $this->input->post('uploaded_photo');

				// Make sure they actually uploaded a cover photo
				if (!empty($uploaded_photo))
				{
					$data['cover_photo'] = '<img src="' . base_url() . 'assets/uploads/' . $uploaded_photo . '" width="536"><input id="asset_title" name="asset_title" type="hidden" value="' . $asset_title . '"><input id="uploaded_photo" name="uploaded_photo" type="hidden" value="' . $uploaded_photo . '">';
				}

				// Get POSTed values
				$video_title    = $this->input->post('video_title');
				$uploaded_video = $this->input->post('uploaded_video');
				$uploaded_thumb = $this->input->post('uploaded_thumb');

				// Get file names
				$file_thumb = base_url() . 'assets/uploads/' . $uploaded_thumb;
				$file_video = CDN_URL . $uploaded_video;

				// Get video mimetype
				$mimetype = $this->assets_model->get_mimetype($uploaded_video);

				ob_start();
				?>
				<video controls="controls" style="display: block; width: 100%; max-width: 100%; height: auto;">
					<source src="<?php echo $file_video; ?>" type="<?php echo $mimetype; ?>">
					<!-- Flash fallback for non-HTML5 browsers without JavaScript -->
					<object data="<?php echo base_url(); ?>assets/js/mediaelement/flashmediaelement.swf" style="display: block; width: 100%; max-width: 100%; height: auto;" type="application/x-shockwave-flash">
						<param name="movie" value="<?php echo base_url(); ?>assets/js/mediaelement/flashmediaelement.swf">
						<param name="flashvars" value="controls=true&amp;file=<?php echo urlencode($file_video); ?>">
						<!-- Image as a last resort -->
						<img class="img-responsive" src="<?php echo $file_thumb; ?>" title="No video playback capabilities">
					</object>
				</video>
				<input id="video_title" name="video_title" type="hidden" value="<?php echo $video_title; ?>">
				<input id="uploaded_video" name="uploaded_video" type="hidden" value="<?php echo $uploaded_video; ?>">
				<input id="uploaded_thumb" name="uploaded_thumb" type="hidden" value="<?php echo $uploaded_thumb; ?>">
				<?php
				$data['video'] = ob_get_clean();
			}
		}

		// Load views
		$this->load->view('templates/header',     $data);
		$this->load->view('templates/navigation', $data);
		$this->load->view('pages/upload/video',   $data);
		$this->load->view('templates/footer-nav', $data);
		$this->load->view('templates/footer',     $data);
	}

	/**
	 * Upload - AJAX Upload Public
	 *
	 * The AJAX upload public page for the upload controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function ajax_upload_public()
	{
		// Set the upload config
		$config['upload_path']   = './assets/uploads/';
		$config['allowed_types'] = 'gif|jpg|png';
		$config['max_size']      = '10240'; // 10MB (in kilobytes)
		$config['encrypt_name']  = TRUE;

		// Initialize the upload with config
		$this->upload->initialize($config);

		// Display preview image or error on failure
		if (!$this->upload->do_upload('public_photo'))
		{
			echo $this->upload->display_errors('', '');
		}
		else
		{
			// Get image info
			$image = $this->upload->data();

			// Create thumbnails
			$this->upload_model->create_thumbnails($image);

			// Get file name
			$file = base_url() . 'assets/uploads/' . $image['file_name'];

			// Image HTML
			$html = '<img src="' . $file . '" width="536"><input id="asset_title" name="asset_title" type="hidden" value="' . $image['orig_name'] . '"><input id="uploaded_photo" name="uploaded_photo" type="hidden" value="' . $image['file_name'] . '">';

			echo $html;
		}
	}

	/**
	 * Upload - AJAX Upload Private
	 *
	 * The AJAX upload private page for the upload controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function ajax_upload_private()
	{
		// Set the upload config
		$config['upload_path']   = './assets/uploads/';
		$config['allowed_types'] = 'gif|jpg|png';
		$config['max_size']      = '10240'; // 10MB (in kilobytes)
		$config['encrypt_name']  = TRUE;

		// Initialize the upload with config
		$this->upload->initialize($config);

		// Display preview image or error on failure
		if (!$this->upload->do_multi_upload('private_photo'))
		{
			echo $this->upload->display_errors('', '');
		}
		else
		{
			// Get image info
			$images = $this->upload->get_multi_upload_data();

			// Set return $html
			$html = '';

			foreach ($images as $image)
			{
				// Create thumbnails
				$this->upload_model->create_thumbnails($image);

				// Get file name
				$file = base_url() . 'assets/uploads/' . $image['file_name'];

				// Image HTML
				$html .= '<p style="margin-bottom: 15px; margin-top: 0;"><img src="' . $file . '" width="536"><input name="asset_title[]" type="hidden" value="' . $image['orig_name'] . '"><input name="uploaded_photo[]" type="hidden" value="' . $image['file_name'] . '"></p>';
			}

			echo $html;
		}
	}

	/**
	 * Upload - AJAX Upload Photoset
	 *
	 * The AJAX upload photoset page for the upload controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function ajax_upload_photoset()
	{
		// Set the upload config
		$config['upload_path']   = './assets/uploads/';
		$config['allowed_types'] = 'gif|jpg|png';
		$config['max_size']      = '10240'; // 10MB (in kilobytes)
		$config['encrypt_name']  = TRUE;

		// Initialize the upload with config
		$this->upload->initialize($config);

		// Display preview image or error on failure
		if (!$this->upload->do_upload('cover_photo'))
		{
			echo $this->upload->display_errors('', '');
		}
		else
		{
			// Get image info
			$image = $this->upload->data();

			// Create thumbnails
			$this->upload_model->create_thumbnails($image);

			// Get file name
			$file = base_url() . 'assets/uploads/' . $image['file_name'];

			// Image HTML
			$html = '<img src="' . $file . '" width="536"><input id="asset_title" name="asset_title" type="hidden" value="' . $image['orig_name'] . '"><input id="uploaded_photo" name="uploaded_photo" type="hidden" value="' . $image['file_name'] . '">';

			echo $html;
		}
	}

	/**
	 * Upload - AJAX Upload Photoset Photo
	 *
	 * The AJAX upload photoset photo page for the upload controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function ajax_upload_photoset_photo()
	{
		// Set the upload config
		$config['upload_path']   = './assets/uploads/';
		$config['allowed_types'] = 'gif|jpg|png';
		$config['max_size']      = '10240'; // 10MB (in kilobytes)
		$config['encrypt_name']  = TRUE;

		// Initialize the upload with config
		$this->upload->initialize($config);

		// Display preview image or error on failure
		if (!$this->upload->do_multi_upload('photoset_photo'))
		{
			echo $this->upload->display_errors('', '');
		}
		else
		{
			// Get image info
			$images = $this->upload->get_multi_upload_data();

			// Set return $html
			$html = '';

			foreach ($images as $image)
			{
				// Create thumbnails
				$this->upload_model->create_thumbnails($image);

				// Get file name
				$file = base_url() . 'assets/uploads/' . $image['file_name'];

				// Image HTML
				$html .= '<p style="margin-bottom: 15px; margin-top: 0;"><img src="' . $file . '" width="536"><input name="child_asset_title[]" type="hidden" value="' . $image['orig_name'] . '"><input name="child_uploaded_photo[]" type="hidden" value="' . $image['file_name'] . '"></p>';
			}

			echo $html;
		}
	}

	/**
	 * Upload - AJAX Upload Video Photo
	 *
	 * The AJAX upload video photo page for the upload controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function ajax_upload_video_photo()
	{
		// Set the upload config
		$config['upload_path']   = './assets/uploads/';
		$config['allowed_types'] = 'gif|jpg|png';
		$config['max_size']      = '10240'; // 10MB (in kilobytes)
		$config['encrypt_name']  = TRUE;

		// Initialize the upload with config
		$this->upload->initialize($config);

		// Display preview image or error on failure
		if (!$this->upload->do_upload('cover_photo'))
		{
			echo $this->upload->display_errors('', '');
		}
		else
		{
			// Get image info
			$image = $this->upload->data();

			// Create thumbnails
			$this->upload_model->create_thumbnails($image);

			// Get file name
			$file = base_url() . 'assets/uploads/' . $image['file_name'];

			// Image HTML
			$html = '<img src="' . $file . '" width="536"><input id="asset_title" name="asset_title" type="hidden" value="' . $image['orig_name'] . '"><input id="uploaded_photo" name="uploaded_photo" type="hidden" value="' . $image['file_name'] . '">';

			echo $html;
		}
	}

	/**
	 * Upload - AJAX Upload Video
	 *
	 * The AJAX upload video page for the upload controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function ajax_upload_video()
	{
		// Set the upload config
		$config['upload_path']   = './assets/uploads/';
		$config['allowed_types'] = 'mpeg|mpg|mp4|m4v|f4v|webm|flv|ogv|wmv';
		$config['max_size']      = '2097152'; // 2GB (in kilobytes)
		$config['encrypt_name']  = TRUE;

		// Initialize the upload with config
		$this->upload->initialize($config);

		// Display preview image or error on failure
		if (!$this->upload->do_upload('video'))
		{
			echo $this->upload->display_errors('', '');
		}
		else
		{
			// Get video info
			$video = $this->upload->data();

			$thumb = $video['raw_name'] . '-thumb.jpg';

			// Capture image from video for cover photo
			shell_exec(BIN_PATH . 'ffmpeg -i "' . $video['full_path'] . '" -an -ss 1.001 -y -f mjpeg "' . $video['file_path'] . $thumb . '" 2>&1');

			// Get the new image width and height
			list($width, $height) = @getimagesize($video['file_path'] . $thumb);

			// Save file information
			$file = array(
				'full_path'    => $video['file_path'] . $thumb,
				'file_path'    => $video['file_path'],
				'file_name'    => $thumb,
				'image_width'  => $width,
				'image_height' => $height,
			);

			// Create thumbnails
			$this->upload_model->create_thumbnails($file);

			// Move to AWS CDN
			$this->aws_model->move_file($video['file_path'], $video['file_name']);

			// Get file names
			$file_thumb = base_url() . 'assets/uploads/' . $thumb;
			$file_video = CDN_URL . $video['file_name'];

			// Get video mimetype
			$mimetype = $this->assets_model->get_mimetype($video['file_name']);

			// Potentially show HTML5 video player?
			ob_start();
			?>
			<video controls="controls" style="display: block; width: 100%; max-width: 100%; height: auto;">
				<source src="<?php echo $file_video; ?>" type="<?php echo $mimetype; ?>">
				<!-- Flash fallback for non-HTML5 browsers without JavaScript -->
				<object data="<?php echo base_url(); ?>assets/js/mediaelement/flashmediaelement.swf" style="display: block; width: 100%; max-width: 100%; height: auto;" type="application/x-shockwave-flash">
					<param name="movie" value="<?php echo base_url(); ?>assets/js/mediaelement/flashmediaelement.swf">
					<param name="flashvars" value="controls=true&amp;file=<?php echo urlencode($file_video); ?>">
					<!-- Image as a last resort -->
					<img class="img-responsive" src="<?php echo $file_thumb; ?>" title="No video playback capabilities">
				</object>
			</video>
			<input id="video_title" name="video_title" type="hidden" value="<?php echo $video['orig_name']; ?>">
			<input id="uploaded_video" name="uploaded_video" type="hidden" value="<?php echo $video['file_name']; ?>">
			<input id="uploaded_thumb" name="uploaded_thumb" type="hidden" value="<?php echo $thumb; ?>">
			<?php
			$html = ob_get_clean();

			echo $html;
		}
	}

	/**
	 * Upload - Remove
	 *
	 * The remove page for the upload controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function remove($asset_id)
	{
		$this->assets_model->remove($asset_id);

		redirect('upload/public');
	}

	/**
	 * Upload - Set Default
	 *
	 * The set default page for the upload controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function set_default($asset_id)
	{
		$this->models_model->set_default($asset_id);

		redirect('upload/public');
	}

	/**
	 * Upload - Is Monetary
	 *
	 * The is monetary page for the upload controller
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

	/**
	 * Upload - Minimum Photos
	 *
	 * The minimum photos page for the upload controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function minimum_photos($value)
	{
		// Get an array of all photoset photos that were submitted
		$check = (array) $this->input->post('child_uploaded_photo');

		// Make sure at least 2 photoset photos were submitted
		if (count($check) > 1)
		{
			return TRUE;
		}
		else
		{
			$this->form_validation->set_message('minimum_photos', 'You must upload at least 2 photos to each photoset.');

			return FALSE;
		}
	}

}

/* End of file upload.php */
/* Location: ./application/controllers/upload.php */