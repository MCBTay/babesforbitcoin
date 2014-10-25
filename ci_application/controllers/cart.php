<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cart extends CI_Controller
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

		// Models can't use carts
		if ($this->_user->user_type == 2)
		{
			redirect();
		}
	}

	/**
	 * Cart - Index
	 *
	 * The index page for the cart controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function index()
	{
		// Data array to be used in views
		$data = array(
			'class'  => 'cart',
			'title'  => 'Cart',
			'assets' => $this->cart_model->get_items(),
			'total'  => $this->cart_model->get_total(),
		);

		// See if the user has submitted their purchase
		$purchase = $this->input->post('purchase');

		if ($purchase)
		{
			// Process purchase
			$this->cart_model->process_purchase();

			// Redirect to my files
			//redirect('my-files/success');
		}

		// Load views
		$this->load->view('templates/header',      $data);
		$this->load->view('templates/navigation',  $data);
		$this->load->view('pages/cart/index',      $data);
		$this->load->view('templates/footer-nav',  $data);
		$this->load->view('templates/footer',      $data);
	}

	/**
	 * Cart - Exists
	 *
	 * The exists page for the cart controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function exists()
	{
		// Data array to be used in views
		$data = array(
			'class'  => 'cart',
			'title'  => 'Cart',
			'exists' => TRUE,
			'assets' => $this->cart_model->get_items(),
			'total'  => $this->cart_model->get_total(),
		);

		// Load views
		$this->load->view('templates/header',      $data);
		$this->load->view('templates/navigation',  $data);
		$this->load->view('pages/cart/index',      $data);
		$this->load->view('templates/footer-nav',  $data);
		$this->load->view('templates/footer',      $data);
	}

	/**
	 * Cart - Add
	 *
	 * The add page for the cart controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function add($asset_id)
	{
		// Get current items in cart

		$cart_assets = $this->session->userdata('cart_assets');
		// If empty, create array
		if (empty($cart_assets))
		{
			$cart_assets = array();
		}

		// Make sure this item isn't already in cart
		if (!in_array($asset_id, $cart_assets))
		{
			// Make sure they don't already own this asset
            $is_photoset = $this->uri->segment(3) == photoset ? true : false;

            if ($is_photoset)
            {
                $asset_id = $this->uri->segment(4);
            }

            if (!$this->cart_model->already_purchased($asset_id, $is_photoset))
            {
                $cart_assets[] = $asset_id;

                $this->session->set_userdata('cart_assets', $cart_assets);
            }
            else
            {
                redirect('cart/exists');
            }
		}

		redirect('cart');
	}

	/**
	 * Cart - Remove
	 *
	 * The remove page for the cart controller
	 *
	 * @access public
	 * @return n/a
	 */
	public function remove($asset_id)
	{
		// Get current items in cart
		$cart_assets = $this->session->userdata('cart_assets');

		if (!empty($cart_assets) && is_array($cart_assets))
		{
			foreach ($cart_assets as $key => $cart_asset)
			{
				if ($cart_asset == $asset_id)
				{
					unset($cart_assets[$key]);

					$this->session->set_userdata('cart_assets', $cart_assets);

					break;
				}
			}
		}

		redirect('cart');
	}

}

/* End of file cart.php */
/* Location: ./application/controllers/cart.php */