<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Statistics extends CI_Controller
{

	// Object containing currently logged in user
	public $_user;

	// Array of asset fields allowed to be sorted by
	private $_allowed_sort = array(
		'asset_id',
		'asset_title',
		'asset_type_title',
		'display_name',
		'approved',
	);

	// Array of sorting directions allowed
	private $_allowed_dir = array(
		'asc',
		'desc',
	);

	// Delimitter used for CSV
	private $_delim     = ",";

	// New line used for CSV
	private $_newline   = "\n";

	// Enclosure used for fields in CSV
	private $_enclosure = '"';

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

		// Load management model
		$this->load->model('management_model');
		$this->load->model('stats_model');

		// Require valid user
		$this->management_model->require_valid_user();

		if ($this->_user->user_type != 4)
		{
			// Only Administrators should be able to add users
			redirect('management');
		}

		// Load download helper
		$this->load->helper('download');
	}

	/**
	 * Management/Assets - Index
	 *
	 * The index page for the management/assets controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function index()
	{
		// Data array to be used in views
		$data = array(
			'contributors_online' => $this->stats_model->count_contributors_online(),
			'contributors_total'  => $this->stats_model->count_contributors(),
			'contributors_photos' => $this->stats_model->count_photos_contributors(),
			'models_online'       => $this->stats_model->count_models_online(),
			'models_total'        => $this->stats_model->count_models(),
			'models_photos'       => $this->stats_model->count_photos_models(),
			'models_videos'       => $this->stats_model->count_videos_models(),
			'messages_unread'     => $this->stats_model->count_messages_unread(),
			'messages'            => $this->stats_model->count_messages(),
			'conversions'         => $this->stats_model->get_conversions(),
			'orders'              => $this->stats_model->get_orders(),
			'orders_incomplete'   => $this->stats_model->get_orders_incomplete(),
			'purchases'           => $this->stats_model->get_purchases(),
			'withdrawals'         => $this->stats_model->get_withdrawals(),
			'lifetime_spending'   => $this->stats_model->get_lifetime_spending(),
			'lifetime_earnings'   => $this->stats_model->get_lifetime_earnings(),
		);

		// Add $data array to itself to pass on to sidebar within view
		$data['data'] = $data;

		// Load views
		$this->load->view('templates/management/header',       $data);
		$this->load->view('pages/management/statistics/index', $data);
		$this->load->view('templates/management/footer',       $data);
	}

	/**
	 * Management/Assets - Site
	 *
	 * The site page for the management/assets controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function site()
	{
		// Data array to be used in views
		$data = array(
			'contributors_online' => $this->stats_model->count_contributors_online(),
			'contributors_total'  => $this->stats_model->count_contributors(),
			'contributors_photos' => $this->stats_model->count_photos_contributors(),
			'models_online'       => $this->stats_model->count_models_online(),
			'models_total'        => $this->stats_model->count_models(),
			'models_photos'       => $this->stats_model->count_photos_models(),
			'models_videos'       => $this->stats_model->count_videos_models(),
			'messages_unread'     => $this->stats_model->count_messages_unread(),
			'messages'            => $this->stats_model->count_messages(),
			'income'              => $this->stats_model->get_income(),
		);

		// Add $data array to itself to pass on to sidebar within view
		$data['data'] = $data;

		// Load views
		$this->load->view('templates/management/header',      $data);
		$this->load->view('pages/management/statistics/site', $data);
		$this->load->view('templates/management/footer',      $data);
	}

	/**
	 * Management/Assets - CSV Orders
	 *
	 * The csv orders page for the management/assets controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function csv_orders()
	{
		// Create data for CSV with headers
		$data  = $this->csv_format('ID');
		$data .= $this->csv_format('User');
		$data .= $this->csv_format('Method');
		$data .= $this->csv_format('Amount');
		$data .= $this->csv_format('Site Fee');
		$data .= $this->csv_format('Total');
		$data .= $this->csv_format('Confirmation');
		$data .= $this->csv_format('Created');
		$data .= $this->_newline;

		// Get items
		$orders = $this->stats_model->get_orders();

		foreach ($orders as $order)
		{
			$data .= $this->csv_format($order->order_id);
			$data .= $this->csv_format($order->display_name ? $order->display_name : 'User # ' . $order->user_id);
			$data .= $this->csv_format($order->method);
			$data .= $this->csv_format($order->currency == 'usd' ? '$' . number_format($order->amount, 2) : 'B' . round($order->amount, 6));
			$data .= $this->csv_format($order->currency == 'usd' ? '$' . number_format($order->fee, 2) : 'B' . round($order->fee, 6));
			$data .= $this->csv_format($order->currency == 'usd' ? '$' . number_format($order->total, 2) : 'B' . round($order->total, 6));
			$data .= $this->csv_format($order->method == 'bank' ? $order->transaction_id : $order->cb_code);
			$data .= $this->csv_format(date('Y-m-d H:i:s', $order->created));
			$data .= $this->_newline;
		}

		force_download('funds_added-' . time() . '.csv', $data);
	}

	/**
	 * Management/Assets - CSV Orders Incomplete
	 *
	 * The csv orders incomplete page for the management/assets controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function csv_orders_incomplete()
	{
		// Create data for CSV with headers
		$data  = $this->csv_format('ID');
		$data .= $this->csv_format('User');
		$data .= $this->csv_format('Method');
		$data .= $this->csv_format('Amount');
		$data .= $this->csv_format('Site Fee');
		$data .= $this->csv_format('Total');
		$data .= $this->csv_format('Confirmation');
		$data .= $this->csv_format('Created');
		$data .= $this->_newline;

		// Get items
		$orders = $this->stats_model->get_orders_incomplete();

		foreach ($orders as $order)
		{
			$data .= $this->csv_format($order->order_id);
			$data .= $this->csv_format($order->display_name ? $order->display_name : 'User # ' . $order->user_id);
			$data .= $this->csv_format($order->method);
			$data .= $this->csv_format($order->currency == 'usd' ? '$' . number_format($order->amount, 2) : 'B' . round($order->amount, 6));
			$data .= $this->csv_format($order->currency == 'usd' ? '$' . number_format($order->fee, 2) : 'B' . round($order->fee, 6));
			$data .= $this->csv_format($order->currency == 'usd' ? '$' . number_format($order->total, 2) : 'B' . round($order->total, 6));
			$data .= $this->csv_format($order->method == 'bank' ? $order->transaction_id : $order->cb_code);
			$data .= $this->csv_format(date('Y-m-d H:i:s', $order->created));
			$data .= $this->_newline;
		}

		force_download('funds_fail-' . time() . '.csv', $data);
	}

	/**
	 * Management/Assets - CSV Purchases
	 *
	 * The csv purchases page for the management/assets controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function csv_purchases()
	{
		// Create data for CSV with headers
		$data  = $this->csv_format('ID');
		$data .= $this->csv_format('User');
		$data .= $this->csv_format('Asset');
		$data .= $this->csv_format('Price');
		$data .= $this->csv_format('Site Fee');
		$data .= $this->csv_format('Coinbase Code');
		$data .= $this->csv_format('Payout Date');
		$data .= $this->csv_format('Created');
		$data .= $this->_newline;

		// Get items
		$purchases = $this->stats_model->get_purchases();

		foreach ($purchases as $purchase)
		{
			$data .= $this->csv_format($purchase->purchase_id);
			$data .= $this->csv_format($purchase->display_name ? $purchase->display_name : 'User # ' . $purchase->user_id);
			$data .= $this->csv_format($purchase->asset_id);
			$data .= $this->csv_format($purchase->purchase_price_btc > 0 ? 'B' . round($purchase->purchase_price_btc, 6) : '$' . number_format($purchase->purchase_price_usd, 2));
			$data .= $this->csv_format('$' . number_format($purchase->site_usd, 2));
			$data .= $this->csv_format($purchase->cb_code);
			$data .= $this->csv_format($purchase->payout_date ? date('Y-m-d H:i:s', $purchase->payout_date) : '');
			$data .= $this->csv_format(date('Y-m-d H:i:s', $purchase->created));
			$data .= $this->_newline;
		}

		force_download('purchases-' . time() . '.csv', $data);
	}

	/**
	 * Management/Assets - CSV Withdrawals
	 *
	 * The csv withdrawals page for the management/assets controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function csv_withdrawals()
	{
		// Create data for CSV with headers
		$data  = $this->csv_format('ID');
		$data .= $this->csv_format('User');
		$data .= $this->csv_format('Amount');
		$data .= $this->csv_format('Funds Remaining');
		$data .= $this->csv_format('Site Fee');
		$data .= $this->csv_format('Transaction ID');
		$data .= $this->csv_format('Refunded');
		$data .= $this->csv_format('Created');
		$data .= $this->_newline;

		// Get items
		$withdrawals = $this->stats_model->get_withdrawals();

		foreach ($withdrawals as $withdrawal)
		{
			$data .= $this->csv_format($withdrawal->withdrawal_id);
			$data .= $this->csv_format($withdrawal->display_name ? $withdrawal->display_name : 'User # ' . $withdrawal->user_id);
			$data .= $this->csv_format($withdrawal->currency == 'btc' ? 'B' . round($withdrawal->withdrawal_amount, 6) : '$' . number_format($withdrawal->withdrawal_amount, 2));
			$data .= $this->csv_format($withdrawal->currency == 'btc' ? 'B' . round($withdrawal->funds_btc_remaining, 6) : '$' . number_format($withdrawal->funds_btc_remaining, 2));
			$data .= $this->csv_format('$' . number_format($withdrawal->site_fee, 2));
			$data .= $this->csv_format($withdrawal->transaction_id);
			$data .= $this->csv_format($withdrawal->refunded);
			$data .= $this->csv_format(date('Y-m-d H:i:s', $withdrawal->withdrawal_created));
			$data .= $this->_newline;
		}

		force_download('withdrawals-' . time() . '.csv', $data);
	}

	/**
	 * Management/Assets - CSV Conversions
	 *
	 * The csv conversions page for the management/assets controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function csv_conversions()
	{
		// Create data for CSV with headers
		$data  = $this->csv_format('ID');
		$data .= $this->csv_format('User');
		$data .= $this->csv_format('Coinbase Code');
		$data .= $this->csv_format('BTC Out');
		$data .= $this->csv_format('USD In');
		$data .= $this->csv_format('Site Fee');
		$data .= $this->csv_format('Payout Date');
		$data .= $this->csv_format('Created');
		$data .= $this->_newline;

		// Get items
		$conversions = $this->stats_model->get_conversions();

		foreach ($conversions as $conversion)
		{
			$data .= $this->csv_format($conversion->conversion_id);
			$data .= $this->csv_format($conversion->display_name ? $conversion->display_name : 'User # ' . $conversion->user_id);
			$data .= $this->csv_format($conversion->cb_code);
			$data .= $this->csv_format('B' . $conversion->btc_out);
			$data .= $this->csv_format('$' . $conversion->usd_in);
			$data .= $this->csv_format('$' . $conversion->site_fee);
			$data .= $this->csv_format($conversion->payout_date ? date('Y-m-d H:i:s', $conversion->payout_date) : '');
			$data .= $this->csv_format(date('Y-m-d H:i:s', $conversion->created));
			$data .= $this->_newline;
		}

		force_download('conversions-' . time() . '.csv', $data);
	}

	/**
	 * Management/Assets - CSV Lifetime Spending
	 *
	 * The csv lifetime spending page for the management/assets controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function csv_lifetime_spending()
	{
		// Create data for CSV with headers
		$data  = $this->csv_format('ID');
		$data .= $this->csv_format('User');
		$data .= $this->csv_format('Amount');
		$data .= $this->csv_format('Last Login');
		$data .= $this->_newline;

		// Get items
		$lifetime_spending = $this->stats_model->get_lifetime_spending();

		foreach ($lifetime_spending as $spending)
		{
			$data .= $this->csv_format($spending->user_id);
			$data .= $this->csv_format($spending->display_name ? $spending->display_name : 'User # ' . $spending->user_id);
			$data .= $this->csv_format('$' . number_format($spending->amount, 2));
			$data .= $this->csv_format(date('Y-m-d H:i:s', $spending->last_login));
			$data .= $this->_newline;
		}

		force_download('lifetime_spending-' . time() . '.csv', $data);
	}

	/**
	 * Management/Assets - CSV Lifetime Earnings
	 *
	 * The csv lifetime earnings page for the management/assets controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function csv_lifetime_earnings()
	{
		// Create data for CSV with headers
		$data  = $this->csv_format('ID');
		$data .= $this->csv_format('User');
		$data .= $this->csv_format('Amount');
		$data .= $this->csv_format('Last Login');
		$data .= $this->_newline;

		// Get items
		$lifetime_earnings = $this->stats_model->get_lifetime_earnings();

		foreach ($lifetime_earnings as $earnings)
		{
			$data .= $this->csv_format($earnings->user_id);
			$data .= $this->csv_format($earnings->display_name ? $earnings->display_name : 'User # ' . $earnings->user_id);
			$data .= $this->csv_format('$' . number_format($earnings->model_usd, 2));
			$data .= $this->csv_format(date('Y-m-d H:i:s', $earnings->last_login));
			$data .= $this->_newline;
		}

		force_download('lifetime_earnings-' . time() . '.csv', $data);
	}

	/**
	 * Management/Assets - CSV Format
	 *
	 * The csv format page for the management/assets controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function csv_format($item)
	{
		$out = $this->_enclosure.str_replace($this->_enclosure, $this->_enclosure.$this->_enclosure, $item).$this->_enclosure.$this->_delim;
		$out = rtrim($out);

		return $out;
	}

}

/* End of file statistics.php */
/* Location: ./application/controllers/management/statistics.php */