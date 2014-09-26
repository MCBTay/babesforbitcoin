<?php if (! defined('BASEPATH')) exit('No direct script access');

class Upload_model extends CI_Model
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
	 * User Crop
	 *
	 * Create profile photo from user chosen crop
	 *
	 * @access public
	 * @return n/a
	 */
	public function user_crop()
	{
		// Get uploaded photo
		$uploaded_photo = $this->input->post('uploaded_photo');

		// Get image width, height
		list($width, $height) = @getimagesize('./assets/uploads/' . $uploaded_photo);

		// Calculate ratio
		$ratio = $width / 536;

		// Get chosen crop coordinates
		$coords_x1      = round($ratio * $this->input->post('coords-x1'));
		$coords_y1      = round($ratio * $this->input->post('coords-y1'));
		$coords_x2      = round($ratio * $this->input->post('coords-x2'));
		$coords_y2      = round($ratio * $this->input->post('coords-y2'));
		$coords_w       = round($ratio * $this->input->post('coords-w'));
		$coords_h       = round($ratio * $this->input->post('coords-h'));

		// Profile thumbnail crop config
		$config['source_image']   = './assets/uploads/' . $uploaded_photo;
		$config['new_image']      = './assets/uploads/tall-' . strtolower($uploaded_photo);
		$config['width']          = $coords_w;
		$config['height']         = $coords_h;
		$config['maintain_ratio'] = FALSE;
		$config['x_axis']         = $coords_x1;
		$config['y_axis']         = $coords_y1;

		// Initialize the image library with config
		$this->image_lib->initialize($config);

		// Crop the image
		if (!$this->image_lib->crop())
		{
			$errors[] = $this->image_lib->display_errors('', '');
		}

		// Clear the config to start next thumbnail
		$config = array();
		$this->image_lib->clear();

		// Profile thumbnail resize config
		$config['source_image']   = './assets/uploads/tall-' . strtolower($uploaded_photo);
		$config['width']          = 385;
		$config['height']         = 465;
		$config['maintain_ratio'] = FALSE;

		// Initialize the image library with config
		$this->image_lib->initialize($config);

		// Resize the image
		if (!$this->image_lib->resize())
		{
			$errors[] = $this->image_lib->display_errors('', '');
		}
	}

	/**
	 * Create Thumbnails
	 *
	 * Create Thumbnails for an uploaded image
	 *
	 * @access public
	 * @return n/a
	 */
	public function create_thumbnails($data)
	{
		// Create an errors array to return
		$errors = array();

		// Create a profile thumbnail 385x465

		// Set the target values
		$target_width  = 385;
		$target_height = 465;
		$target_ratio  = $target_width / $target_height;

		// Get original values
		$width          = $data['image_width'];
		$height         = $data['image_height'];
		$original_ratio = $width / $height;

		// Set the new height and width such that width is at least target width AND height is at least target height
		if ($original_ratio > $target_ratio)
		{
			$new_height = $target_height;
			$new_width  = round(($target_height / $height) * $width);
		}
		else
		{
			$new_width  = $target_width;
			$new_height = round(($target_width / $width) * $height);
		}

		// Determine new x/y axis based on new_width and new_height (center crop)
		$new_x_axis = 0;
		$new_y_axis = 0;

		if ($new_width > $target_width)
		{
			$new_x_axis = round(($new_width - $target_width) / 2);
		}

		if ($new_height > $target_height)
		{
			$new_y_axis = round(($new_height - $target_height) / 2);
		}

		// Profile thumbnail config
		$config['source_image']   = $data['full_path'];
		$config['new_image']      = $data['file_path'] . 'tall-' . strtolower($data['file_name']);
		$config['width']          = $new_width;
		$config['height']         = $new_height;
		$config['maintain_ratio'] = TRUE;

		// Initialize the image library with config
		$this->image_lib->initialize($config);

		// Resize the image
		if (!$this->image_lib->resize())
		{
			$errors[] = $this->image_lib->display_errors('', '');
		}

		// Clear the config to start next thumbnail
		$config = array();
		$this->image_lib->clear();

		// Profile thumbnail config v2
		$config['source_image']   = $data['file_path'] . 'tall-' . strtolower($data['file_name']);
		$config['width']          = $target_width;
		$config['height']         = $target_height;
		$config['maintain_ratio'] = FALSE;
		$config['x_axis']         = $new_x_axis;
		$config['y_axis']         = $new_y_axis;

		// Initialize the image library with config
		$this->image_lib->initialize($config);

		// Crop the image
		if (!$this->image_lib->crop())
		{
			$errors[] = $this->image_lib->display_errors('', '');
		}

		// Large thumbnail config
		$config['source_image']   = $data['full_path'];
		$config['new_image']      = $data['file_path'] . 'lrg-' . strtolower($data['file_name']);
		$config['width']          = 1200;
		$config['height']         = 1200;
		$config['maintain_ratio'] = TRUE;
		$config['master_dim']     = 'width';

		// Initialize the image library with config
		$this->image_lib->initialize($config);

		// Resize the image
		if (!$this->image_lib->resize())
		{
			$errors[] = $this->image_lib->display_errors('', '');
		}

		// Clear the config to start next thumbnail
		$config = array();
		$this->image_lib->clear();

		// Medium thumbnail config
		$config['source_image']   = $data['full_path'];
		$config['new_image']      = $data['file_path'] . 'med-' . strtolower($data['file_name']);
		$config['width']          = 600;
		$config['height']         = 600;
		$config['maintain_ratio'] = TRUE;
		$config['master_dim']     = 'width';

		// Initialize the image library with config
		$this->image_lib->initialize($config);

		// Resize the image
		if (!$this->image_lib->resize())
		{
			$errors[] = $this->image_lib->display_errors('', '');
		}

		// Clear the config to start next thumbnail
		$config = array();
		$this->image_lib->clear();

		// Create a small square thumbnail

		// Set the target values
		$target_width  = 300;
		$target_height = 300;
		$target_ratio  = $target_width / $target_height;

		// Get original values
		$width          = $data['image_width'];
		$height         = $data['image_height'];
		$original_ratio = $width / $height;

		// Set the new height and width such that width is at least target width AND height is at least target height
		if ($original_ratio > $target_ratio)
		{
			$new_height = $target_height;
			$new_width  = round(($target_height / $height) * $width);
		}
		else
		{
			$new_width  = $target_width;
			$new_height = round(($target_width / $width) * $height);
		}

		// Determine new x/y axis based on new_width and new_height (center crop)
		$new_x_axis = 0;
		$new_y_axis = 0;

		if ($new_width > $target_width)
		{
			$new_x_axis = round(($new_width - $target_width) / 2);
		}

		if ($new_height > $target_height)
		{
			$new_y_axis = round(($new_height - $target_height) / 2);
		}

		// Small thumbnail config
		$config['source_image']   = $data['full_path'];
		$config['new_image']      = $data['file_path'] . 'sml-' . strtolower($data['file_name']);
		$config['width']          = $new_width;
		$config['height']         = $new_height;
		$config['maintain_ratio'] = TRUE;

		// Initialize the image library with config
		$this->image_lib->initialize($config);

		// Resize the image
		if (!$this->image_lib->resize())
		{
			$errors[] = $this->image_lib->display_errors('', '');
		}

		// Clear the config to start next thumbnail
		$config = array();
		$this->image_lib->clear();

		// Small thumbnail config
		$config['source_image']   = $data['file_path'] . 'sml-' . strtolower($data['file_name']);
		$config['width']          = $target_width;
		$config['height']         = $target_height;
		$config['maintain_ratio'] = FALSE;
		$config['x_axis']         = $new_x_axis;
		$config['y_axis']         = $new_y_axis;

		// Initialize the image library with config
		$this->image_lib->initialize($config);

		// Crop the image
		if (!$this->image_lib->crop())
		{
			$errors[] = $this->image_lib->display_errors('', '');
		}
	}

	/**
	 * Create Admin Thumb
	 *
	 * Create Admin Thumb for an uploaded image
	 *
	 * @access public
	 * @return n/a
	 */
	public function create_admin_thumb($data)
	{
		// Create an errors array to return
		$errors = array();

		// Set the target values
		$target_width  = 72;
		$target_height = 72;
		$target_ratio  = $target_width / $target_height;

		// Get original values
		$width          = $data['image_width'];
		$height         = $data['image_height'];
		$original_ratio = $width / $height;

		// Set the new height and width such that width is at least target width AND height is at least target height
		if ($original_ratio > $target_ratio)
		{
			$new_height = $target_height;
			$new_width  = round(($target_height / $height) * $width);
		}
		else
		{
			$new_width  = $target_width;
			$new_height = round(($target_width / $width) * $height);
		}

		// Determine new x/y axis based on new_width and new_height (center crop)
		$new_x_axis = 0;
		$new_y_axis = 0;

		if ($new_width > $target_width)
		{
			$new_x_axis = round(($new_width - $target_width) / 2);
		}

		if ($new_height > $target_height)
		{
			$new_y_axis = round(($new_height - $target_height) / 2);
		}

		// Admin thumb config
		$config['source_image']   = $data['full_path'];
		$config['width']          = $new_width;
		$config['height']         = $new_height;
		$config['maintain_ratio'] = TRUE;

		// Initialize the image library with config
		$this->image_lib->initialize($config);

		// Resize the image
		if (!$this->image_lib->resize())
		{
			$errors[] = $this->image_lib->display_errors('', '');
		}

		// Clear the config to start next thumbnail
		$config = array();
		$this->image_lib->clear();

		// Admin thumb config
		$config['source_image']   = $data['full_path'];
		$config['width']          = $target_width;
		$config['height']         = $target_height;
		$config['maintain_ratio'] = FALSE;
		$config['x_axis']         = $new_x_axis;
		$config['y_axis']         = $new_y_axis;

		// Initialize the image library with config
		$this->image_lib->initialize($config);

		// Crop the image
		if (!$this->image_lib->crop())
		{
			$errors[] = $this->image_lib->display_errors('', '');
		}
	}

}

/* End of file upload_model.php */
/* Location: ./application/models/upload_model.php */