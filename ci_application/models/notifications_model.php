<?php if (! defined('BASEPATH')) exit('No direct script access');

class Notifications_model extends CI_Model
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
	 * Get Carriers
	 *
	 * Get list of carriers from the database
	 *
	 * @access public
	 * @return object
	 */
	public function get_carriers()
	{
		$this->db->from('carriers');
		$this->db->order_by('carrier_name');
		$query  = $this->db->get();
		$result = $query->result();

		return $result;
	}

	/**
	 * Get Carrier Domain
	 *
	 * Get carrier domain from the database
	 *
	 * @access public
	 * @return string
	 */
	public function get_carrier_domain($carrier_id)
	{
		$this->db->from('carriers');
		$this->db->where('carrier_id', $carrier_id);
		$query = $this->db->get();
		$row   = $query->row();

		return $row->carrier_domain;
	}

}

/* End of file notifications_model.php */
/* Location: ./application/models/notifications_model.php */