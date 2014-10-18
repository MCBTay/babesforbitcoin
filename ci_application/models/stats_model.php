<?php if (! defined('BASEPATH')) exit('No direct script access');

class Stats_model extends CI_Model
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
	 * Count Contributors
	 *
	 * Count the total number of contributors in the database
	 *
	 * @access public
	 * @return n/a
	 */
	public function count_contributors()
	{
		$this->db->from('users');
		$this->db->where('user_type', 1);
		$count = $this->db->count_all_results();

		return number_format($count);
	}

	/**
	 * Count Contributors Online
	 *
	 * Count the total number of online contributors in the database
	 *
	 * @access public
	 * @return n/a
	 */
	public function count_contributors_online()
	{
		$time = time() - 1800;

		$this->db->from('users');
		$this->db->where('user_type', 1);
		$this->db->where('last_login >', $time);
		$count = $this->db->count_all_results();

		return number_format($count);
	}

	/**
	 * Count Models
	 *
	 * Count the total number of models in the database
	 *
	 * @access public
	 * @return n/a
	 */
	public function count_models()
	{
		$this->db->from('users');
		$this->db->where('user_type', 2);
        $this->db->where('user_approved', 1);
		$count = $this->db->count_all_results();

		return number_format($count);
	}

	/**
	 * Count Models Online
	 *
	 * Count the total number of online models in the database
	 *
	 * @access public
	 * @return n/a
	 */
	public function count_models_online()
	{
		$time = time() - 1800;

		$this->db->from('users');
		$this->db->where('user_type', 2);
		$this->db->where('last_login >', $time);
		$count = $this->db->count_all_results();

		return number_format($count);
	}

	/**
	 * Count Photos
	 *
	 * Count the total number of photos in the database
	 *
	 * @access public
	 * @return n/a
	 */
	public function count_photos()
	{
		$this->db->from('assets');
		$this->db->where_in('asset_type', array(1, 2, 3, 4));
		$count = $this->db->count_all_results();

		return number_format($count);
	}

	/**
	 * Count Photos Contributors
	 *
	 * Count the total number of contributors photos in the database
	 *
	 * @access public
	 * @return n/a
	 */
	public function count_photos_contributors()
	{
		$this->db->from('assets');
		$this->db->join('users', 'users.user_id = assets.user_id');
		$this->db->where_in('assets.asset_type', array(1, 2));
		$this->db->where('users.user_type', 1);
		$count = $this->db->count_all_results();

		return number_format($count);
	}

	/**
	 * Count Photos Models
	 *
	 * Count the total number of models photos in the database
	 *
	 * @access public
	 * @return n/a
	 */
	public function count_photos_models()
	{
		$this->db->from('assets');
		$this->db->join('users', 'users.user_id = assets.user_id');
		$this->db->where_in('assets.asset_type', array(1, 2, 3, 4));
		$this->db->where('users.user_type', 2);
		$count = $this->db->count_all_results();

		return number_format($count);
	}

	/**
	 * Count Videos
	 *
	 * Count the total number of videos in the database
	 *
	 * @access public
	 * @return n/a
	 */
	public function count_videos()
	{
		$this->db->from('assets');
		$this->db->where('asset_type', 5);
		$count = $this->db->count_all_results();

		return number_format($count);
	}

	/**
	 * Count Videos Models
	 *
	 * Count the total number of models videos in the database
	 *
	 * @access public
	 * @return n/a
	 */
	public function count_videos_models()
	{
		$this->db->from('assets');
		$this->db->join('users', 'users.user_id = assets.user_id');
		$this->db->where('assets.asset_type', 5);
		$this->db->where('users.user_type', 2);
		$count = $this->db->count_all_results();

		return number_format($count);
	}

	/**
	 * Count Messages
	 *
	 * Count the total number of messages in the database
	 *
	 * @access public
	 * @return n/a
	 */
	public function count_messages()
	{
		$this->db->from('messages');
		$count = $this->db->count_all_results();

		return number_format($count);
	}

	/**
	 * Count Messages Unread
	 *
	 * Count the total number of unread messages in the database
	 *
	 * @access public
	 * @return n/a
	 */
	public function count_messages_unread()
	{
		$this->db->from('messages');
		$this->db->where('read', 0);
		$count = $this->db->count_all_results();

		return number_format($count);
	}

	/**
	 * Get Conversions
	 *
	 * Get conversions from the database ordered newest first
	 *
	 * @access public
	 * @return n/a
	 */
	public function get_conversions($user_id = 0)
	{
		$this->db->from('conversions');
		$this->db->join('users', 'users.user_id = conversions.user_id');
		if ($user_id)
		{
			$this->db->where('users.user_id', $user_id);
		}
		$this->db->order_by('conversions.created', 'desc');
		$query  = $this->db->get();
		$result = $query->result();

		return $result;
	}

	/**
	 * Get Orders
	 *
	 * Get orders from the database ordered newest first
	 *
	 * @access public
	 * @return n/a
	 */
	public function get_orders($user_id = 0)
	{
		$this->db->from('orders');
		$this->db->join('users', 'users.user_id = orders.user_id');
		$this->db->where('orders.completed', 1);
		if ($user_id)
		{
			$this->db->where('users.user_id', $user_id);
		}
		$this->db->order_by('orders.created', 'desc');
		$query  = $this->db->get();
		$result = $query->result();

		return $result;
	}

	/**
	 * Get Orders Incomplete
	 *
	 * Get incomplete orders from the database ordered newest first
	 *
	 * @access public
	 * @return n/a
	 */
	public function get_orders_incomplete()
	{
		$this->db->from('orders');
		$this->db->join('users', 'users.user_id = orders.user_id');
		$this->db->where('orders.completed', 0);
		$this->db->order_by('orders.created', 'desc');
		$query  = $this->db->get();
		$result = $query->result();

		return $result;
	}

	/**
	 * Get Purchases
	 *
	 * Get purchases from the database ordered newest first
	 *
	 * @access public
	 * @return n/a
	 */
	public function get_purchases($user_id = 0)
	{
		$this->db->from('users_purchases');
		$this->db->join('users', 'users.user_id = users_purchases.user_id');
		if ($user_id)
		{
            $this->db->where('users.user_id', $user_id);
		}
		$this->db->order_by('users_purchases.purchase_created', 'desc');
		$query  = $this->db->get();
		$result = $query->result();

		return $result;
	}

    /**
     * Get Sales
     *
     * Get sales of a particular user
     *
     * @access public
     * @return n/a
     */
    public function get_sales($user_id, $limit)
    {
        $this->db->from('users_purchases');
        $this->db->join('users', 'users.user_id = users_purchases.user_id');

        if ($user_id)
        {
            $this->db->join('assets', 'assets.asset_id = users_purchases.asset_id');
            $this->db->join('photosets', 'photosets.photoset_id = users_purchases.asset_id');
            $this->db->where('assets.user_id', $user_id);
        }

        if ($limit)
        {
            $this->db->limit($limit);
        }

        $this->db->order_by('users_purchases.purchase_created', 'desc');
        $query  = $this->db->get();
        $result = $query->result();

        return $result;
    }

    /**
     * Get Purchases count
     *
     * Get number of purchases from the database
     *
     * @access public
     * @return n/a
     */
    public function get_purchases_count($user_id = 0)
    {
        $this->db->from('users_purchases');
        $this->db->join('users', 'users.user_id = users_purchases.user_id');
        if ($user_id)
        {
            $this->db->where('users.user_id', $user_id);
        }
        $this->db->order_by('users_purchases.purchase_created', 'desc');
        $query  = $this->db->get();
        $result = $query->result();

        return sizeof($result);
    }


    /**
	 * Get Withdrawals
	 *
	 * Get withdrawals from the database ordered newest first
	 *
	 * @access public
	 * @return n/a
	 */
	public function get_withdrawals($user_id = 0)
	{
		$this->db->from('users_withdrawals');
		$this->db->join('users', 'users.user_id = users_withdrawals.user_id');
		if ($user_id)
		{
			$this->db->where('users.user_id', $user_id);
		}
		$this->db->order_by('users_withdrawals.withdrawal_created', 'desc');
		$query  = $this->db->get();
		$result = $query->result();

		return $result;
	}

	/**
	 * Get Lifetime Spending
	 *
	 * Get lifetime spending (add funds) by contributors ordered highest to lowest
	 *
	 * @access public
	 * @return n/a
	 */
	public function get_lifetime_spending()
	{
		$this->db->select('users.user_id');
		$this->db->select('users.display_name');
		$this->db->select('users.last_login');
		$this->db->select_sum('orders.amount');
		$this->db->from('orders');
		$this->db->join('users', 'users.user_id = orders.user_id');
		$this->db->where('orders.currency', 'usd');
		$this->db->where('orders.completed', 1);
		$this->db->group_by('orders.user_id');
		$this->db->order_by('amount', 'desc');
		$query  = $this->db->get();
		$result = $query->result();

		return $result;
	}

	/**
	 * Get Lifetime Earnings
	 *
	 * Get lifetime earnings (purchases) by models ordered highest to lowest
	 *
	 * @access public
	 * @return n/a
	 */
	public function get_lifetime_earnings()
	{
		$this->db->select('users.user_id');
		$this->db->select('users.display_name');
		$this->db->select('users.last_login');
		$this->db->select_sum('users_purchases.model_usd');
		$this->db->from('users_purchases');
		$this->db->join('assets', 'assets.asset_id = users_purchases.asset_id');
		$this->db->join('users', 'users.user_id = assets.user_id');
		$this->db->where('users_purchases.model_usd >', 0);
		$this->db->group_by('assets.user_id');
		$this->db->order_by('model_usd', 'desc');
		$query  = $this->db->get();
		$result = $query->result();

		return $result;
	}

	/**
	 * Get Income
	 *
	 * Get Income from Epoch, Dwolla, and Coinbase
	 *
	 * @access public
	 * @return n/a
	 */
	public function get_income()
	{
		$income = array(
			'epoch'    => array(
				'day'      => 0,
				'week'     => 0,
				'month'    => 0,
				'lifetime' => 0,
			),
			'dwolla'   => array(
				'day'      => 0,
				'week'     => 0,
				'month'    => 0,
				'lifetime' => 0,
			),
			'coinbase' => array(
				'day'          => 0,
				'week'         => 0,
				'month'        => 0,
				'lifetime'     => 0,
				'day_btc'      => 0,
				'week_btc'     => 0,
				'month_btc'    => 0,
				'lifetime_btc' => 0,
			),
			'total'    => array(
				'day'          => 0,
				'week'         => 0,
				'month'        => 0,
				'lifetime'     => 0,
				'day_btc'      => 0,
				'week_btc'     => 0,
				'month_btc'    => 0,
				'lifetime_btc' => 0,
			),
		);

		// Get Conversions Day (Coinbase)
		$this->db->select_sum('site_fee');
		$this->db->from('conversions');
		$this->db->where('created >', time() - 86400);
		$query = $this->db->get();
		$row   = $query->row();
		$income['coinbase']['day'] += $row->site_fee;
		$income['total']['day']    += $row->site_fee;

		// Get Conversions Week (Coinbase)
		$this->db->select_sum('site_fee');
		$this->db->from('conversions');
		$this->db->where('created >', time() - 604800);
		$query = $this->db->get();
		$row   = $query->row();
		$income['coinbase']['week'] += $row->site_fee;
		$income['total']['week']    += $row->site_fee;

		// Get Conversions Month (Coinbase)
		$this->db->select_sum('site_fee');
		$this->db->from('conversions');
		$this->db->where('created >', time() - 2592000);
		$query = $this->db->get();
		$row   = $query->row();
		$income['coinbase']['month'] += $row->site_fee;
		$income['total']['month']    += $row->site_fee;

		// Get Conversions Lifetime (Coinbase)
		$this->db->select_sum('site_fee');
		$this->db->from('conversions');
		$query = $this->db->get();
		$row   = $query->row();
		$income['coinbase']['lifetime'] += $row->site_fee;
		$income['total']['lifetime']    += $row->site_fee;

		// Get Orders Day (Epoch)
		$this->db->select_sum('fee');
		$this->db->from('orders');
		$this->db->where('method', 'card');
		$this->db->where('completed', 1);
		$this->db->where('created >', time() - 86400);
		$query = $this->db->get();
		$row   = $query->row();
		$income['epoch']['day'] += $row->fee;
		$income['total']['day'] += $row->fee;

		// Get Orders Week (Epoch)
		$this->db->select_sum('fee');
		$this->db->from('orders');
		$this->db->where('method', 'card');
		$this->db->where('completed', 1);
		$this->db->where('created >', time() - 604800);
		$query = $this->db->get();
		$row   = $query->row();
		$income['epoch']['week'] += $row->fee;
		$income['total']['week'] += $row->fee;

		// Get Orders Month (Epoch)
		$this->db->select_sum('fee');
		$this->db->from('orders');
		$this->db->where('method', 'card');
		$this->db->where('completed', 1);
		$this->db->where('created >', time() - 2592000);
		$query = $this->db->get();
		$row   = $query->row();
		$income['epoch']['month'] += $row->fee;
		$income['total']['month'] += $row->fee;

		// Get Orders Lifetime (Epoch)
		$this->db->select_sum('fee');
		$this->db->from('orders');
		$this->db->where('method', 'card');
		$this->db->where('completed', 1);
		$query = $this->db->get();
		$row   = $query->row();
		$income['epoch']['lifetime'] += $row->fee;
		$income['total']['lifetime'] += $row->fee;

		// Get Orders Day (Dwolla)
		$this->db->select_sum('fee');
		$this->db->from('orders');
		$this->db->where('method', 'bank');
		$this->db->where('completed', 1);
		$this->db->where('created >', time() - 86400);
		$query = $this->db->get();
		$row   = $query->row();
		$income['dwolla']['day'] += $row->fee;
		$income['total']['day']  += $row->fee;

		// Get Orders Week (Dwolla)
		$this->db->select_sum('fee');
		$this->db->from('orders');
		$this->db->where('method', 'bank');
		$this->db->where('completed', 1);
		$this->db->where('created >', time() - 604800);
		$query = $this->db->get();
		$row   = $query->row();
		$income['dwolla']['week'] += $row->fee;
		$income['total']['week']  += $row->fee;

		// Get Orders Month (Dwolla)
		$this->db->select_sum('fee');
		$this->db->from('orders');
		$this->db->where('method', 'bank');
		$this->db->where('completed', 1);
		$this->db->where('created >', time() - 2592000);
		$query = $this->db->get();
		$row   = $query->row();
		$income['dwolla']['month'] += $row->fee;
		$income['total']['month']  += $row->fee;

		// Get Orders Lifetime (Dwolla)
		$this->db->select_sum('fee');
		$this->db->from('orders');
		$this->db->where('method', 'bank');
		$this->db->where('completed', 1);
		$query = $this->db->get();
		$row   = $query->row();
		$income['dwolla']['lifetime'] += $row->fee;
		$income['total']['lifetime']  += $row->fee;

		// Get Orders Day (Coinbase USD)
		$this->db->select_sum('fee');
		$this->db->from('orders');
		$this->db->where('method', 'btc_usd');
		$this->db->where('completed', 1);
		$this->db->where('created >', time() - 86400);
		$query = $this->db->get();
		$row   = $query->row();
		$income['coinbase']['day'] += $row->fee;
		$income['total']['day']    += $row->fee;

		// Get Orders Week (Coinbase USD)
		$this->db->select_sum('fee');
		$this->db->from('orders');
		$this->db->where('method', 'btc_usd');
		$this->db->where('completed', 1);
		$this->db->where('created >', time() - 604800);
		$query = $this->db->get();
		$row   = $query->row();
		$income['coinbase']['week'] += $row->fee;
		$income['total']['week']    += $row->fee;

		// Get Orders Month (Coinbase USD)
		$this->db->select_sum('fee');
		$this->db->from('orders');
		$this->db->where('method', 'btc_usd');
		$this->db->where('completed', 1);
		$this->db->where('created >', time() - 2592000);
		$query = $this->db->get();
		$row   = $query->row();
		$income['coinbase']['month'] += $row->fee;
		$income['total']['month']    += $row->fee;

		// Get Orders Lifetime (Coinbase USD)
		$this->db->select_sum('fee');
		$this->db->from('orders');
		$this->db->where('method', 'btc_usd');
		$this->db->where('completed', 1);
		$query = $this->db->get();
		$row   = $query->row();
		$income['coinbase']['lifetime'] += $row->fee;
		$income['total']['lifetime']    += $row->fee;

		// Get Orders Day (Coinbase BTC)
		$this->db->select_sum('fee');
		$this->db->from('orders');
		$this->db->where('method', 'btc');
		$this->db->where('completed', 1);
		$this->db->where('created >', time() - 86400);
		$query = $this->db->get();
		$row   = $query->row();
		$income['coinbase']['day_btc'] += $row->fee;
		$income['total']['day_btc']    += $row->fee;

		// Get Orders Week (Coinbase BTC)
		$this->db->select_sum('fee');
		$this->db->from('orders');
		$this->db->where('method', 'btc');
		$this->db->where('completed', 1);
		$this->db->where('created >', time() - 604800);
		$query = $this->db->get();
		$row   = $query->row();
		$income['coinbase']['week_btc'] += $row->fee;
		$income['total']['week_btc']    += $row->fee;

		// Get Orders Month (Coinbase BTC)
		$this->db->select_sum('fee');
		$this->db->from('orders');
		$this->db->where('method', 'btc');
		$this->db->where('completed', 1);
		$this->db->where('created >', time() - 2592000);
		$query = $this->db->get();
		$row   = $query->row();
		$income['coinbase']['month_btc'] += $row->fee;
		$income['total']['month_btc']    += $row->fee;

		// Get Orders Lifetime (Coinbase BTC)
		$this->db->select_sum('fee');
		$this->db->from('orders');
		$this->db->where('method', 'btc');
		$this->db->where('completed', 1);
		$query = $this->db->get();
		$row   = $query->row();
		$income['coinbase']['lifetime_btc'] += $row->fee;
		$income['total']['lifetime_btc']    += $row->fee;

		// Get Purchases Day (Dwolla)
		$this->db->select_sum('site_usd');
		$this->db->from('users_purchases');
		$this->db->where('purchase_created >', time() - 86400);
		$query = $this->db->get();
		$row   = $query->row();
		$income['dwolla']['day'] += $row->site_usd;
		$income['total']['day']  += $row->site_usd;

		// Get Purchases Week (Dwolla)
		$this->db->select_sum('site_usd');
		$this->db->from('users_purchases');
		$this->db->where('purchase_created >', time() - 604800);
		$query = $this->db->get();
		$row   = $query->row();
		$income['dwolla']['week'] += $row->site_usd;
		$income['total']['week']  += $row->site_usd;

		// Get Purchases Month (Dwolla)
		$this->db->select_sum('site_usd');
		$this->db->from('users_purchases');
		$this->db->where('purchase_created >', time() - 2592000);
		$query = $this->db->get();
		$row   = $query->row();
		$income['dwolla']['month'] += $row->site_usd;
		$income['total']['month']  += $row->site_usd;

		// Get Purchases Lifetime (Dwolla)
		$this->db->select_sum('site_usd');
		$this->db->from('users_purchases');
		$query = $this->db->get();
		$row   = $query->row();
		$income['dwolla']['lifetime'] += $row->site_usd;
		$income['total']['lifetime']  += $row->site_usd;

		// Get Withdrawals Day (Dwolla)
		$this->db->select_sum('site_fee');
		$this->db->from('users_withdrawals');
		$this->db->where('currency', 'usd');
		$this->db->where('refunded', 0);
		$this->db->where('withdrawal_created >', time() - 86400);
		$query = $this->db->get();
		$row   = $query->row();
		$income['dwolla']['day'] += $row->site_fee;
		$income['total']['day']  += $row->site_fee;

		// Get Withdrawals Week (Dwolla)
		$this->db->select_sum('site_fee');
		$this->db->from('users_withdrawals');
		$this->db->where('currency', 'usd');
		$this->db->where('refunded', 0);
		$this->db->where('withdrawal_created >', time() - 604800);
		$query = $this->db->get();
		$row   = $query->row();
		$income['dwolla']['week'] += $row->site_fee;
		$income['total']['week']  += $row->site_fee;

		// Get Withdrawals Month (Dwolla)
		$this->db->select_sum('site_fee');
		$this->db->from('users_withdrawals');
		$this->db->where('currency', 'usd');
		$this->db->where('refunded', 0);
		$this->db->where('withdrawal_created >', time() - 2592000);
		$query = $this->db->get();
		$row   = $query->row();
		$income['dwolla']['month'] += $row->site_fee;
		$income['total']['month']  += $row->site_fee;

		// Get Withdrawals Lifetime (Dwolla)
		$this->db->select_sum('site_fee');
		$this->db->from('users_withdrawals');
		$this->db->where('currency', 'usd');
		$this->db->where('refunded', 0);
		$query = $this->db->get();
		$row   = $query->row();
		$income['dwolla']['lifetime'] += $row->site_fee;
		$income['total']['lifetime']  += $row->site_fee;

		// Get Withdrawals Day (Coinbase)
		$this->db->select_sum('site_fee');
		$this->db->from('users_withdrawals');
		$this->db->where('currency', 'btc');
		$this->db->where('refunded', 0);
		$this->db->where('withdrawal_created >', time() - 86400);
		$query = $this->db->get();
		$row   = $query->row();
		$income['coinbase']['day'] += $row->site_fee;
		$income['total']['day']  += $row->site_fee;

		// Get Withdrawals Week (Coinbase)
		$this->db->select_sum('site_fee');
		$this->db->from('users_withdrawals');
		$this->db->where('currency', 'btc');
		$this->db->where('refunded', 0);
		$this->db->where('withdrawal_created >', time() - 604800);
		$query = $this->db->get();
		$row   = $query->row();
		$income['coinbase']['week'] += $row->site_fee;
		$income['total']['week']  += $row->site_fee;

		// Get Withdrawals Month (Coinbase)
		$this->db->select_sum('site_fee');
		$this->db->from('users_withdrawals');
		$this->db->where('currency', 'btc');
		$this->db->where('refunded', 0);
		$this->db->where('withdrawal_created >', time() - 2592000);
		$query = $this->db->get();
		$row   = $query->row();
		$income['coinbase']['month'] += $row->site_fee;
		$income['total']['month']  += $row->site_fee;

		// Get Withdrawals Lifetime (Coinbase)
		$this->db->select_sum('site_fee');
		$this->db->from('users_withdrawals');
		$this->db->where('currency', 'btc');
		$this->db->where('refunded', 0);
		$query = $this->db->get();
		$row   = $query->row();
		$income['coinbase']['lifetime'] += $row->site_fee;
		$income['total']['lifetime']  += $row->site_fee;

		return $income;
	}

}

/* End of file stats_model.php */
/* Location: ./application/models/stats_model.php */