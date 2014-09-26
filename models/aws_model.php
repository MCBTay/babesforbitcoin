<?php if (! defined('BASEPATH')) exit('No direct script access');

// AWS has to be in global scope
require BIN_PATH . 'aws.phar';
use Aws\S3\S3Client;

class Aws_model extends CI_Model
{

	public $client;

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

		// Initialize S3 Client
		$this->client = S3Client::factory();
	}

	/**
	 * Move File
	 *
	 * Move file to AWS and delete local copy
	 *
	 * @access public
	 * @return n/a
	 */
	public function move_file($file_path, $file_name)
	{
		// Send source image to CDN
		$result = $this->client->putObject(array(
			'Bucket'     => S3_BUCKET,
			'Key'        => $file_name,
			'SourceFile' => $file_path . $file_name,
		));

		// Poll the source image until it is accessible
		$this->client->waitUntil('ObjectExists', array(
			'Bucket'     => S3_BUCKET,
			'Key'        => $file_name,
		));

		// Source image is on CDN, delete local file
		unlink($file_path . $file_name);
	}

	/**
	 * Delete Asset
	 *
	 * Delete all files associated with a specific asset on AWS
	 *
	 * @access public
	 * @return n/a
	 */
	public function delete_asset($asset_id)
	{
		$this->db->from('assets');
		$this->db->where('asset_id', $asset_id);
		$query = $this->db->get();
		$row   = $query->row();

		if (!empty($row->video))
		{
			// Delete video from CDN
			$result = $this->client->deleteObject(array(
				'Bucket'     => S3_BUCKET,
				'Key'        => $row->video,
			));
		}

		if (!empty($row->filename))
		{
			// Delete main image from CDN
			$result = $this->client->deleteObject(array(
				'Bucket'     => S3_BUCKET,
				'Key'        => $row->filename,
			));

			// Delete large image from CDN
			$result = $this->client->deleteObject(array(
				'Bucket'     => S3_BUCKET,
				'Key'        => 'lrg-' . $row->filename,
			));

			// Delete medium image from CDN
			$result = $this->client->deleteObject(array(
				'Bucket'     => S3_BUCKET,
				'Key'        => 'med-' . $row->filename,
			));

			// Delete small image from CDN
			$result = $this->client->deleteObject(array(
				'Bucket'     => S3_BUCKET,
				'Key'        => 'sml-' . $row->filename,
			));

			// Delete tall image from CDN
			$result = $this->client->deleteObject(array(
				'Bucket'     => S3_BUCKET,
				'Key'        => 'tall-' . $row->filename,
			));
		}
	}

}

/* End of file aws_model.php */
/* Location: ./application/models/aws_model.php */