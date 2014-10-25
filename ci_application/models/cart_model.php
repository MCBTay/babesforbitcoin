<?php if (! defined('BASEPATH')) exit('No direct script access');

class Cart_model extends CI_Model
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
	 * Get Items
	 *
	 * Get items that are currently in the cart
	 *
	 * @access public
	 * @return n/a
	 */
	public function get_items()
	{
		// Array to return to controller
		$return = array();

		// Assets that are in the cart
		$assets = $this->session->userdata('cart_assets');

		// If they have at least one item in their cart
		if (!empty($assets) && is_array($assets))
		{
			foreach ($assets as $asset)
			{
                // check if it's a photoset first
                $photoset = $this->models_model->get_photoset($asset);

                if ($photoset)
                {
                    $return[] = $photoset;
                }
                else
                {
                    $item = $this->models_model->get_asset($asset);

                    if ($item) {
                        $return[] = $this->models_model->get_asset($asset);
                    }
                }
			}
		}

		return $return;
	}

	/**
	 * Get Total
	 *
	 * Get total of items that are currently in the cart
	 *
	 * @access public
	 * @return n/a
	 */
	public function get_total()
	{
		// Set total to 0 for now
		$total = 0;

		// Get assets from cart
		$assets = $this->get_items();

		foreach ($assets as $asset)
		{
			$total += $asset->asset_cost;
		}

		return number_format($total, 2, '.', '');
	}

	/**
	 * Process Purchase
	 *
	 * Process a purchase of items that are currently in the cart
	 *
	 * @access public
	 * @return n/a
	 */
	public function process_purchase()
	{
		// Set redirect URL (which will end up being last purchased item)
		$redirect_url = 'my-files/success';

		// Set totals to 0 for now
		$total     = 0;
		$total_usd = 0;

		// Get assets from cart
		$assets = $this->get_items();

		foreach ($assets as $asset)
		{
			$total += $asset->asset_cost;

			if ($asset->model->accept_btc != 1)
			{
				// Model only accepts USD
				$total_usd += $asset->asset_cost;
			}
		}

		$total     = number_format($total, 2, '.', '');
		$total_usd = number_format($total_usd, 2, '.', '');

		// See if user needs to convert BTC to USD
		if ($total_usd > $this->_user->funds_usd)
		{
			// How much USD we're missing
			$missing = $total_usd - $this->_user->funds_usd;

			// Adjusted amount based on fees
			$adjusted = ($missing + 0.15) / 0.9865;

			// Exchange Rate
			$exchange_rate = $this->coinbase_model->coinbase->getExchangeRate('usd', 'btc');

			// Amount of BTC that needs to be converted to USD
			$btc = $adjusted * $exchange_rate;

			if ($this->_user->funds_btc >= $btc)
			{
				// Sell bitcoins
				$response = $this->coinbase_model->coinbase->sell($btc);

				// Data for conversions table
				$data = array(
					'user_id'     => $this->_user->user_id,
					'cb_code'     => $response->transfer->code,
					'btc_out'     => $response->transfer->btc->amount,
					'usd_in'      => $response->transfer->total->amount,
					'payout_date' => strtotime($response->transfer->payout_date),
					'created'     => time(),
				);

				// Insert into conversions table
				$this->db->insert('conversions', $data);

				// Update user's funds
				$this->_user->funds_btc = $this->_user->funds_btc - $response->transfer->btc->amount;
				$this->_user->funds_usd = $this->_user->funds_usd + $response->transfer->total->amount;
				$this->db->set('funds_btc', $this->_user->funds_btc);
				$this->db->set('funds_usd', $this->_user->funds_usd);
				$this->db->where('user_id', $this->_user->user_id);
				$this->db->update('users');
			}
		}

		// Use should have enough funds now, if not, redirect them back to cart page
		if ($total_usd > $this->_user->funds_usd)
		{
			redirect('cart');
		}

		// Get current items in cart
		$cart_assets = $this->session->userdata('cart_assets');

		if (!empty($cart_assets) && is_array($cart_assets))
		{
			// Loop through cart assets and make usd only purchases
			foreach ($assets as $asset)
			{
				if ($asset->model->accept_btc != 1)
				{
					if ($asset->asset_cost > $this->_user->funds_usd)
					{
						redirect('cart');
					}

					// Update user's funds
					$this->_user->funds_usd = $this->_user->funds_usd - $asset->asset_cost;
					$this->db->set('funds_usd', $this->_user->funds_usd);
					$this->db->where('user_id', $this->_user->user_id);
					$this->db->update('users');

					// Calculate site fee and model commission
					$site_usd  = round($asset->asset_cost * FEE_PURCHASE, 2);
					$model_usd = $asset->asset_cost - $site_usd;

					// Update model's funds
					$model = $this->user_model->get_user($asset->user_id);
					$this->db->set('funds_usd', $model->funds_usd + $model_usd);
					$this->db->where('user_id', $asset->user_id);
					$this->db->update('users');

					// Data for users purchases table
                    $asset_id = $asset->asset_id;

                    $data = array(
                        'user_id'            => $this->_user->user_id,
                        'purchase_price'     => $asset->asset_cost,
                        'purchase_price_usd' => $asset->asset_cost,
                        'model_usd'          => $model_usd,
                        'site_usd'           => $site_usd,
                        'purchase_created'   => time(),
                    );

                    if ($asset->photoset_id != 0)
                    {
                        $data['photoset_id'] = $asset->photoset_id;
                    }
                    else
                    {
                        $data['asset_id'] = $asset->asset_id;
                    }

					// Insert purchase into users purchases table
					$this->db->insert('users_purchases', $data);

					// Set redirect URL to this asset's page
					$redirect_url = 'my-files/model/' . $asset->user_id;

					// Remove the item from the user's cart
					foreach ($cart_assets as $key => $cart_asset)
					{
						if ($cart_asset == $asset->asset_id)
						{
							unset($cart_assets[$key]);

							$this->session->set_userdata('cart_assets', $cart_assets);

							break;
						}
					}
				}
			}

			// Loop through cart assets and make mixed usd/btc purchases
			foreach ($assets as $asset)
			{
				if ($asset->model->accept_btc == 1)
				{
					$asset_cost_btc = $this->usd_to_btc($asset->asset_cost);

					// See if transaction can be entirely BTC
					if ($asset_cost_btc <= $this->_user->funds_btc && ($this->_user->prefer_btc == 1 || $asset->asset_cost > $this->_user->funds_usd))
					{
						// Update user's funds
						$this->_user->funds_btc = $this->_user->funds_btc - $asset_cost_btc;
						$this->db->set('funds_btc', $this->_user->funds_btc);
						$this->db->where('user_id', $this->_user->user_id);
						$this->db->update('users');

						// Calculate site fee and model commission
						$site_btc  = round($asset_cost_btc * FEE_PURCHASE, 6);

						// Data for users purchases table
						$data = array(
							'asset_id'           => $asset->asset_id,
							'user_id'            => $this->_user->user_id,
							'purchase_price'     => $asset->asset_cost,
							'purchase_price_btc' => $asset_cost_btc,
							'purchase_created'   => time(),
						);

						// Sell bitcoins for site fee
						$response = $this->coinbase_model->coinbase->sell($site_btc);

						// Model's BTC with site fee subtracted
						$model_btc = round($asset_cost_btc - $response->transfer->btc->amount, 6);

						// Data for users purchases table
						$data['model_btc']          = $model_btc;
						$data['site_usd']           = $response->transfer->total->amount;
						$data['site_btc_converted'] = $response->transfer->btc->amount;
						$data['cb_code']            = $response->transfer->code;
						$data['payout_date']        = strtotime($response->transfer->payout_date);

						// Update model's funds
						$model = $this->user_model->get_user($asset->user_id);
						$this->db->set('funds_btc', $model->funds_btc + $model_btc);
						$this->db->where('user_id', $asset->user_id);
						$this->db->update('users');

						// Insert purchase into users purchases table
						$this->db->insert('users_purchases', $data);

						// Set redirect URL to this asset's page
						$redirect_url = 'my-files/model/' . $asset->user_id;

						// Remove the item from the user's cart
						foreach ($cart_assets as $key => $cart_asset)
						{
							if ($cart_asset == $asset->asset_id)
							{
								unset($cart_assets[$key]);

								$this->session->set_userdata('cart_assets', $cart_assets);

								break;
							}
						}
					}
					// Can't use BTC, see if user has enough USD to cover the transaction
					elseif ($asset->asset_cost <= $this->_user->funds_usd)
					{
						// Update user's funds
						$this->_user->funds_usd = $this->_user->funds_usd - $asset->asset_cost;
						$this->db->set('funds_usd', $this->_user->funds_usd);
						$this->db->where('user_id', $this->_user->user_id);
						$this->db->update('users');

						// Calculate site fee and model commission
						$site_usd  = round($asset->asset_cost * FEE_PURCHASE, 2);
						$model_usd = $asset->asset_cost - $site_usd;

						// Update model's funds
						$model = $this->user_model->get_user($asset->user_id);
						$this->db->set('funds_usd', $model->funds_usd + $model_usd);
						$this->db->where('user_id', $asset->user_id);
						$this->db->update('users');

						// Data for users purchases table
						$data = array(
							'asset_id'           => $asset->asset_id,
							'user_id'            => $this->_user->user_id,
							'purchase_price'     => $asset->asset_cost,
							'purchase_price_usd' => $asset->asset_cost,
							'model_usd'          => $model_usd,
							'site_usd'           => $site_usd,
							'purchase_created'   => time(),
						);

						// Insert purchase into users purchases table
						$this->db->insert('users_purchases', $data);

						// Set redirect URL to this asset's page
						$redirect_url = 'my-files/model/' . $asset->user_id;

						// Remove the item from the user's cart
						foreach ($cart_assets as $key => $cart_asset)
						{
							if ($cart_asset == $asset->asset_id)
							{
								unset($cart_assets[$key]);

								$this->session->set_userdata('cart_assets', $cart_assets);

								break;
							}
						}
					}
					else
					{
						// User doesn't have enough BTC or USD to cover entire transaction
						redirect('cart');
					}
				}
			}
		}

		// Redirect user appropriately
		redirect($redirect_url);
	}

	/**
	 * Get Order
	 *
	 * Gets an order from the database
	 *
	 * @access public
	 * @return n/a
	 */
	public function get_order($order_id)
	{
		$this->db->from('orders');
		$this->db->where('order_id', $order_id);
		$query = $this->db->get();
		$row   = $query->row();

		return $row;
	}

	/**
	 * Add Funds Card
	 *
	 * Add Funds via Credit Card using Epoch
	 *
	 * @access public
	 * @return n/a
	 */
	public function add_funds_card()
	{
		// Get POSTed values from form
		$amount_card = $this->input->post('amount_card');

		switch ($amount_card)
		{
			case 1:
				$amount  =  1.00;
				$fee     =  0.19;
				$total   =  1.19;
				$pi_code = 'ciwcwv8p637837';
			break;
			case 5:
				$amount  =  5.00;
				$fee     =  0.99;
				$total   =  5.99;
				$pi_code = 'ciwcwv2p632872';
			break;
			case 10:
				$amount  = 10.00;
				$fee     =  1.99;
				$total   = 11.99;
				$pi_code = 'ciwcwv3p632873';
			break;
			case 15:
				$amount  = 15.00;
				$fee     =  2.99;
				$total   = 17.99;
				$pi_code = 'ciwcwv4p632874';
			break;
			case 20:
				$amount  = 20.00;
				$fee     =  3.99;
				$total   = 23.99;
				$pi_code = 'ciwcwv5p632875';
			break;
			default:
				$amount  = 50.00;
				$fee     =  9.99;
				$total   = 59.99;
				$pi_code = 'ciwcwv6p632876';
			break;
		}

		// Create transaction locally with pending status
		$data = array(
			'user_id'  => $this->_user->user_id,
			'method'   => 'card',
			'amount'   => $amount,
			'fee'      => $fee,
			'total'    => $total,
			'currency' => 'usd',
			'created'  => time(),
		);
		$this->db->insert('orders', $data);
		$order_id = $this->db->insert_id();

		// Redirect the user to checkout with Epoch
		redirect('https://wnu.com/secure/services/?api=join&pi_code=' . $pi_code . '&reseller=a&pi_returnurl=' . urlencode(base_url() . 'account/add_funds_card') . '&no_userpass=1&x_oid=' . $order_id);
	}

	/**
	 * Add Funds Bank
	 *
	 * Add Funds via Bank Account using Dwolla
	 *
	 * @access public
	 * @return n/a
	 */
	public function add_funds_bank()
	{
		// Get POSTed values from form
		$amount_bank = round($this->input->post('amount_bank'), 2);

		// Calculate fee and total
		$amount_bank_fee   = round($amount_bank * FEE_BANK, 2);
		$amount_bank_total = round($amount_bank + $amount_bank_fee, 2);

		// Create transaction locally with pending status
		$data = array(
			'user_id'  => $this->_user->user_id,
			'method'   => 'bank',
			'amount'   => $amount_bank,
			'fee'      => $amount_bank_fee,
			'total'    => $amount_bank_total,
			'currency' => 'usd',
			'created'  => time(),
		);
		$this->db->insert('orders', $data);
		$order_id = $this->db->insert_id();

		// Initialize Dwolla API
		$this->dwolla_model->initialize($redirectUri = base_url() . 'account/add_funds_bank', $permissions = FALSE, $mode = FALSE, $debugMode = FALSE, $sandboxMode = FALSE);

		// Start Gateway Session for Dwolla
		$this->dwolla_model->startGatewaySession();

		// Add "products" to Gateway Session for Dwolla
		$this->dwolla_model->addGatewayProduct('Account Funding',     $amount_bank,     $quantity = 1, $description = '');
		$this->dwolla_model->addGatewayProduct('Account Funding Fee', $amount_bank_fee, $quantity = 1, $description = '');

		// Get Gateway URL for Dwolla
		$gateway_url = $this->dwolla_model->getGatewayURL(
			$destinationId            = '812-300-4923', // Site: 812-300-4923 Reflector: 812-713-9234
			$orderId                  = $order_id,
			$discount                 = 0,
			$shipping                 = 0,
			$tax                      = 0,
			$notes                    = '',
			$callback                 = base_url() . 'account/add_funds_bank_callback',
			$allowFundingSources      = TRUE,
			$allowGuestCheckout       = TRUE,
			$additionalFundingSources = 'true'
		);

		// Redirect the user to checkout with Dwolla
		redirect($gateway_url);
	}

	/**
	 * Add Funds Card Process
	 *
	 * Add Funds via Credit Card using Epoch (process callback)
	 *
	 * @access public
	 * @return n/a
	 */
	public function add_funds_card_process()
	{
		$epoch = array(
			// customer's username
			'username'        => $this->input->post('username'),
			// customer's password
			'password'        => $this->input->post('password'),
			// customer's email address
			'email'           => $this->input->post('email'),
			// customer's name
			'name'            => $this->input->post('name'),
			// customer's street address
			'address'         => $this->input->post('address'),
			// customer's city
			'city'            => $this->input->post('city'),
			// customer's state
			'state'           => $this->input->post('state'),
			// customer's zip
			'zip'             => $this->input->post('zip'),
			// customer's country
			'country'         => $this->input->post('country'),
			// customer's Epoch assigned order id (member id)
			'order_id'        => $this->input->post('order_id'),
			// id assigned per transaction
			'transaction_id'  => $this->input->post('transaction_id'),
			// default currency of the product
			'currency'        => $this->input->post('currency'),
			// customer's ip address
			'ipaddress'       => $this->input->post('ipaddress'),
			// amount of the sale by default currency
			'amount'          => $this->input->post('amount'),
			// amount of the sale by buyer's local currency
			'localamount'     => $this->input->post('localamount'),
			// reseller code of the affiliate
			'reseller'        => $this->input->post('reseller'),
			// optional reseller code that you pass to Epoch
			'site'            => $this->input->post('site'),
			// product code for the product purchased
			'pi_code'         => $this->input->post('pi_code'),
			// id of the session recorded at the point of sale
			'session_id'      => $this->input->post('session_id'),
			// two digit initials of the payment typs(Example: VS, MC, DS, etc…)
			'payment_subtype' => $this->input->post('payment_subtype'),
		);

		$epoch_json = json_encode($epoch);

		$order_id = $this->input->post('x_oid');

		// Get order details
		$order = $this->get_order($order_id);

		// If the order_id was a valid entry in the database
		if ($order)
		{
			// See if transaction amount matches expected amount
			if ($order->total == $epoch['amount'])
			{
				// Update database with completed status
				$data = array(
					'completed'      => 1,
					'transaction_id' => $epoch['transaction_id'],
					'epoch_json'     => $epoch_json,
				);
				$this->db->where('order_id', $order_id);
				$this->db->update('orders', $data);

				// Get the user so we can add the funds
				$user = $this->user_model->get_user($order->user_id);

				if ($user)
				{
					// Add the funds to the user's account
					$this->db->set('funds_usd', $user->funds_usd + $order->amount);
					$this->db->where('user_id', $user->user_id);
					$this->db->update('users');
				}
			}
			else
			{
				// Update database with failed status
				$data = array(
					'error'      => 'Amount does not match',
					'epoch_json' => $epoch_json,
				);
				$this->db->where('order_id', $order_id);
				$this->db->update('orders', $data);
			}
		}
	}

	/**
	 * Add Funds Bank Process
	 *
	 * Add Funds via Bank Account using Dwolla (process callback)
	 *
	 * @access public
	 * @return n/a
	 */
	public function add_funds_bank_process()
	{
		// Get data from input that Dwolla sends us
		$data = json_decode(file_get_contents('php://input'));

		// Set variables from the data for ease-of-use
		$amount         = $data->Amount;
		$checkout_id    = $data->CheckoutId;
		$clearing_date  = strtotime($data->ClearingDate);
		$error          = $data->Error;
		$order_id       = $data->OrderId;
		$signature      = $data->Signature;
		$status         = $data->Status;
		$test_mode      = $data->TestMode;
		$transaction_id = $data->TransactionId;

		// Initialize Dwolla API
		$this->dwolla_model->initialize($redirectUri = base_url() . 'account/add_funds_bank', $permissions = FALSE, $mode = FALSE, $debugMode = FALSE, $sandboxMode = FALSE);

		// Verify Gateway Signature - order is valid if this returns true
		if ($this->dwolla_model->verifyGatewaySignature($signature, $checkout_id, $amount))
		{
			// Get order details
			$order = $this->get_order($order_id);

			// If the order_id was a valid entry in the database
			if ($order)
			{
				// See if transaction completed and if amount matches expected amount
				if ($status == 'Completed' && $order->total == $amount)
				{
					// Update database with completed status
					$data = array(
						'completed'      => 1,
						'checkout_id'    => $checkout_id,
						'clearing_date'  => $clearing_date,
						'error'          => $error,
						'signature'      => $signature,
						'status'         => $status,
						'transaction_id' => $transaction_id,
					);
					$this->db->where('order_id', $order_id);
					$this->db->update('orders', $data);

					// Get the user so we can add the funds
					$user = $this->user_model->get_user($order->user_id);

					if ($user)
					{
						// Add the funds to the user's account
						$this->db->set('funds_usd', $user->funds_usd + $order->amount);
						$this->db->where('user_id', $user->user_id);
						$this->db->update('users');
					}
				}
				else
				{
					// Update database with failed status
					$data = array(
						'status' => $status,
						'error'  => $error,
					);
					$this->db->where('order_id', $order_id);
					$this->db->update('orders', $data);
				}
			}
		}
	}

	/**
	 * Add Funds BTC
	 *
	 * Add Funds via BTC using Coinbase
	 *
	 * @access public
	 * @return n/a
	 */
	public function add_funds_btc()
	{
		// Get POSTed values from form
		$amount_btc_usd = round($this->input->post('amount_btc_usd'), 2);
		$convert_to_usd = (int) $this->input->post('convert_to_usd');

		// Calculate some of the values
		$fee_usd    = round($amount_btc_usd * FEE_BTC, 2);
		$total_usd  = round($amount_btc_usd + $fee_usd, 2);
		$total_btc  = $this->usd_to_btc($total_usd);
		$amount_btc = round($total_btc / (1 + FEE_BTC), 6);
		$fee_btc    = round($total_btc - $amount_btc, 6);

		// Data array for inserting into database
		$data = array(
			'user_id' => $this->_user->user_id,
			'created' => time(),
		);

		if ($convert_to_usd == 1)
		{
			// Data array for inserting into database
			$data['method']     = 'btc_usd';
			$data['amount']     = $amount_btc_usd;
			$data['fee']        = $fee_usd;
			$data['total']      = $total_usd;
			$data['currency']   = 'usd';

			// Currency for Coinbase
			$currency = 'USD';
		}
		else
		{
			// Data array for inserting into database
			$data['method']     = 'btc';
			$data['amount']     = $amount_btc;
			$data['fee']        = $fee_btc;
			$data['total']      = $total_btc;
			$data['currency']   = 'btc';

			// Currency for Coinbase
			$currency = 'BTC';
		}

		$this->db->insert('orders', $data);
		$order_id = $this->db->insert_id();

		$options = array(
			'callback_url'  => base_url() . 'account/add_funds_btc_callback',
			'success_url'   => base_url() . 'account/add_funds_btc',
			'cancel_url'    => base_url() . 'account/add_funds_btc',
			'auto_redirect' => TRUE,
		);

		$response = $this->coinbase_model->coinbase->createButton('Account Funding', $data['total'], $currency, $order_id, $options);

		// Let's update the database with some details returned via Coinbase
		$udata = array(
			'cb_code' => $response->button->code,
		);

		// There's a chance the USD/BTC exchange rate changed before we POSTed, so let's check
		if ($convert_to_usd == 1)
		{
			$amount = $response->button->price->cents / 100;

			if ($amount != $data['total'])
			{
				// We need to update our values to correct values
				$udata['total']  = $amount;
				$udata['amount'] = round($amount / (1 + FEE_BTC), 2);
				$udata['fee']    = $amount - $udata['amount'];
			}
		}

		// Update this order in the database
		$this->db->where('order_id', $order_id);
		$this->db->update('orders', $udata);

		redirect('https://coinbase.com/checkouts/' . $response->button->code);

		//return $response;
	}

	/**
	 * Add Funds BTC Process
	 *
	 * Add Funds via BTC using Coinbase (process callback)
	 *
	 * @access public
	 * @return n/a
	 */
	public function add_funds_btc_process()
	{
		$response = $this->coinbase_model->decode_callback();

		if (!$response)
		{
			// Invalid Callback
			return FALSE;
		}

		$order_id = (int) $response->custom;
		$status   = $response->status;

		// Get order details
		$order = $this->get_order($order_id);

		if (!$order)
		{
			// Not a valid order
			return FALSE;
		}

		if ($order->completed)
		{
			// Order is already completed
			return FALSE;
		}

		if ($response->total_native->currency_iso != strtoupper($order->currency))
		{
			// Currency mismatch
			return FALSE;
		}

		if ($response->total_native->currency_iso == 'BTC')
		{
			$compare = number_format($response->total_native->cents / 100000000, 8, '.', '');
		}
		else
		{
			$compare = number_format($response->total_native->cents / 100, 8, '.', '');
		}

		$amount = number_format($order->total, 8, '.', '');

		if ($amount != $compare)
		{
			// Amounts don't match
			return FALSE;
		}

		if ($response->status == 'completed')
		{
			$completed = 1;
		}
		else
		{
			$completed = 0;
		}

		$data = array(
			'completed' => $completed,
			'status'    => $status,
		);
		$this->db->where('order_id', $order_id);
		$this->db->update('orders', $data);

		if ($completed)
		{
			// Get the user so we can add the funds
			$user = $this->user_model->get_user($order->user_id);

			if (!$user)
			{
				// User not found
				return FALSE;
			}

			// Add the funds to the user's account
			if ($order->method == 'btc')
			{
				$this->db->set('funds_btc', $user->funds_btc + $order->amount);
			}
			else
			{
				$this->db->set('funds_usd', $user->funds_usd + $order->amount);
			}

			// Update the database with new funds
			$this->db->where('user_id', $user->user_id);
			$this->db->update('users');
		}

		return TRUE;
	}

	/**
	 * USD to BTC
	 *
	 * Convert USD value to BTC based on Coinbase exchange rate
	 *
	 * @access public
	 * @return n/a
	 */
	public function usd_to_btc($usd)
	{
		// Get stored exchange rate from database
		$this->db->from('settings');
		$this->db->where('setting_name', 'exchange_rate');
		$this->db->limit(1);
		$query = $this->db->get();
		$row   = $query->row();

		// Get current time minus 15 minutes (60s * 15m = 900)
		$limit = time() - 900;

		// If more than the limit has passed since last update
		if ($limit > $row->setting_updated)
		{
			// Update exchange rate with new value from Coinbase
			$row->setting_value = $this->coinbase_model->coinbase->getExchangeRate('btc', 'usd');

			$data = array(
				'setting_value'   => $row->setting_value,
				'setting_updated' => time(),
			);

			$this->db->where('setting_name', 'exchange_rate');
			$this->db->update('settings', $data);
		}

		$conversion = round($usd / $row->setting_value, 6);

		return $conversion;
	}

	/**
	 * BTC to USD
	 *
	 * Convert BTC value to UTC based on Coinbase exchange rate
	 *
	 * @access public
	 * @return n/a
	 */
	public function btc_to_usd($btc)
	{
		// Get stored exchange rate from database
		$this->db->from('settings');
		$this->db->where('setting_name', 'exchange_rate');
		$this->db->limit(1);
		$query = $this->db->get();
		$row   = $query->row();

		// Get current time minus 15 minutes (60s * 15m = 900)
		$limit = time() - 900;

		// If more than the limit has passed since last update
		if ($limit > $row->setting_updated)
		{
			// Update exchange rate with new value from Coinbase
			$row->setting_value = $this->coinbase_model->coinbase->getExchangeRate('btc', 'usd');

			$data = array(
				'setting_value'   => $row->setting_value,
				'setting_updated' => time(),
			);

			$this->db->where('setting_name', 'exchange_rate');
			$this->db->update('settings', $data);
		}

		$conversion = round($btc * $row->setting_value, 2);

		return $conversion;
	}

	/**
	 * Already Purchased
	 *
	 * See if a user has already purchased, or been gifted, a particular asset
	 *
	 * @access public
	 * @return n/a
	 */
	public function already_purchased($asset_id, $is_photoset)
	{
		$this->db->from('users_purchases');
		$this->db->where('user_id', $this->_user->user_id);

        if ($is_photoset) {
            $this->db->where('photoset_id', $asset_id);
        } else {
            $this->db->where('asset_id', $asset_id);
        }
		$query = $this->db->get();
		$row   = $query->row();

		if ($row)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

}

/* End of file cart_model.php */
/* Location: ./application/models/cart_model.php */