<?php if (! defined('BASEPATH')) exit('No direct script access');

class Ajax_model extends CI_Model
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
	}

	/**
	 * Get Tags
	 *
	 * Get tags that match specified term
	 *
	 * @access public
	 * @return n/a
	 */
	public function get_tags($term)
	{
		$this->db->from('tags');
		$this->db->like('tag', $term, 'after');
		$this->db->order_by('tag', 'asc');
		$this->db->limit(20);
		$query  = $this->db->get();
		$result = $query->result();

		$tags = array();

		foreach ($result as $row)
		{
			$tags[] = array(
				'id'       => $row->tag_id,
				'label'    => $row->tag,
				'value'    => $row->tag,
				'category' => 'Tags',
			);
		}

		return $tags;
	}

	/**
	 * Get Users
	 *
	 * Get users that match specified term
	 *
	 * @access public
	 * @return n/a
	 */
	public function get_users($term)
	{
		$this->db->from('users');
		$this->db->like('display_name', $term);
		$this->db->order_by('display_name', 'asc');
		$this->db->limit(20);
		$query  = $this->db->get();
		$result = $query->result();

		$users = array();

		foreach ($result as $row)
		{
			$users[] = array(
				'id'       => $row->user_id,
				'label'    => $row->display_name,
				'value'    => $row->display_name,
				'category' => 'Models',
			);
		}

		return $users;
	}

	/**
	 * Get All
	 *
	 * Get all that match specified term
	 *
	 * @access public
	 * @return n/a
	 */
	public function get_all($term)
	{
		$all = array();

		// Models can't search for other models
		if ($this->_user->user_type != 2)
		{
			$this->db->from('users');
			$this->db->like('display_name', $term);
			$this->db->where('user_type', 2);
			$this->db->where('disabled', 0);
			$this->db->where('user_approved', 1);
			$this->db->order_by('display_name', 'asc');
			$this->db->limit(10);
			$query  = $this->db->get();
			$result = $query->result();

			foreach ($result as $row)
			{
				$all[] = array(
					'id'       => $row->user_id,
					'label'    => $row->display_name,
					'value'    => $row->display_name,
					'category' => 'Models',
				);
			}
		}

		// Contributors can't search for other contributors
		if ($this->_user->user_type != 1)
		{
			$this->db->from('users');
			$this->db->like('display_name', $term);
			$this->db->where('user_type', 1);
			$this->db->where('disabled', 0);
			$this->db->order_by('display_name', 'asc');
			$this->db->limit(10);
			$query  = $this->db->get();
			$result = $query->result();

			foreach ($result as $row)
			{
				$all[] = array(
					'id'       => $row->user_id,
					'label'    => $row->display_name,
					'value'    => $row->display_name,
					'category' => 'Contributors',
				);
			}
		}

		// Everyone can search tags
		$this->db->from('tags');
		$this->db->like('tag', $term);
		$this->db->order_by('tag', 'asc');
		$this->db->limit(10);
		$query  = $this->db->get();
		$result = $query->result();

		foreach ($result as $row)
		{
			$all[] = array(
				'id'       => $row->tag_id,
				'label'    => $row->tag,
				'value'    => $row->tag,
				'category' => 'Tags',
			);
		}

		return $all;
	}

}

/* End of file ajax_model.php */
/* Location: ./application/models/ajax_model.php */