<?php // phpcs:ignore WordPress.Files.FileName
/**
 * This class manage all plugin features.
 *
 * @package YITH WooCommerce Name your Price\Classes
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'YITH_WooCommerce_Name_Your_Price' ) ) {
	/**
	 * Class YITH_WooCommerce_Name_Your_Price
	 */
	class YITH_WooCommerce_Name_Your_Price {


		/**
		 * The unque instance of the class
		 *
		 * @var YITH_WooCommerce_Name_Your_Price
		 */
		protected static $instance;
		/**
		 * The instance of YIT Plugin panel
		 *
		 * @var YIT_Plugin_Panel_WooCommerce
		 */
		protected $_panel;

		/**
		 * The plugin panel name
		 *
		 * @var string
		 */
		protected $_panel_page = 'yith_wcnp_panel';


		/**
		 * The premium template name
		 *
		 * @var string
		 */
		protected $_premium = 'premium.php';


		/**
		 * __construct function
		 *
		 * @author YITH
		 * @since 1.0.0
		 */
		public function __construct() {

			/* Plugin Informations */
			add_action( 'plugins_loaded', array( $this, 'plugin_fw_loader' ), 15 );
			add_filter( 'plugin_action_links_' . plugin_basename( YWCNP_DIR . '/' . basename( YWCNP_FILE ) ), array( $this, 'action_links' ) );
			add_filter( 'yith_show_plugin_row_meta', array( $this, 'plugin_row_meta' ), 10, 5 );
			add_action( 'yith_wc_name_your_price_premium', array( $this, 'show_premium_tab' ) );

			// Replace default price with minimum name your price.
			add_filter( 'woocommerce_get_price_html', array( $this, 'get_nameyourprice_price_html' ), 20, 2 );

			/*Add Name Your Price in YITH PLUGIN*/
			add_action( 'admin_menu', array( $this, 'add_name_your_price_menu' ), 5 );

			// Load Admin Class.
			if ( is_admin() && ( ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) || ! isset( $_REQUEST['action'] ) || 'yith_wacp_add_item_cart' !== $_REQUEST['action'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

				YITH_Name_Your_Price_Admin();
			} else {

				YITH_Name_Your_Price_Frontend();
			}

			// Set product as purchasable.
			add_filter( 'woocommerce_is_purchasable', array( $this, 'ywcnp_is_purchasable' ), 20, 2 );
			add_filter( 'woocommerce_variation_is_purchasable', array( $this, 'ywcnp_is_purchasable' ), 20, 2 );
			add_filter( 'woocommerce_product_is_on_sale', array( $this, 'ywcnp_is_on_sale' ), 20, 2 );
		}

		/**
		 * Check if the product is purchasable with name your price
		 *
		 * @author YITH
		 * @since 1.0.0
		 * @param bool       $purchasable If purchasable.
		 * @param WC_Product $product The product.
		 * @return bool
		 */
		public function ywcnp_is_purchasable( $purchasable, $product ) {

			$product_type_supported = ywcnp_get_product_type_allowed();

			if ( $product->is_type( $product_type_supported ) && ywcnp_product_is_name_your_price( $product ) ) {

				return true;
			}

			return $purchasable;

		}

		/**
		 * Check if the product is on sale
		 *
		 * @author YITH
		 * @since 1.0.3
		 * @param bool       $on_sale Is on sale.
		 * @param WC_Product $product The product.
		 * @return bool
		 */
		public function ywcnp_is_on_sale( $on_sale, $product ) {

			$product_type_supported = ywcnp_get_product_type_allowed();

			if ( $product->is_type( $product_type_supported ) && ywcnp_product_is_name_your_price( $product ) ) {
				return false;
			}

			return $on_sale;
		}

		/**
		 * Return single instance
		 *
		 * @author YITH
		 * @since 1.0.0
		 * @return YITH_WooCommerce_Name_Your_Price
		 */
		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Load the plugin Framework
		 *
		 * @author YITH
		 * @since 1.0.0
		 */
		public function plugin_fw_loader() {
			if ( ! defined( 'YIT_CORE_PLUGIN' ) ) {
				global $plugin_fw_data;
				if ( ! empty( $plugin_fw_data ) ) {
					$plugin_fw_file = array_shift( $plugin_fw_data );
					require_once $plugin_fw_file;
				}
			}
		}

		/**
		 * Add the action links to plugin admin page
		 *
		 * @param array $links | links plugin array.
		 * @since    1.0
		 * @author  YITH
		 * @return array
		 * @use plugin_action_links_{$plugin_file_name}
		 */
		public function action_links( $links ) {
			$is_premium = defined( 'YWCNP_PREMIUM' );
			$links      = yith_add_action_links( $links, $this->_panel_page, $is_premium );

			return $links;
		}

		/**
		 * Plugin_row_meta.
		 *
		 * Add the action links to plugin admin page.
		 *
		 * @param array  $new_row_meta_args The new plugin meta.
		 * @param array  $plugin_meta The plugin meta.
		 * @param string $plugin_file The filename of plugin.
		 * @param array  $plugin_data The plugin data.
		 * @param string $status The plugin status.
		 * @param string $init_file The filename of plugin.
		 * @return   array
		 * @since    1.0
		 * @author  YITH
		 * @use plugin_row_meta
		 */
		public function plugin_row_meta( $new_row_meta_args, $plugin_meta, $plugin_file, $plugin_data, $status, $init_file = 'YWCNP_FREE_INIT' ) {

			if ( defined( $init_file ) && constant( $init_file ) === $plugin_file ) {
				$new_row_meta_args['slug'] = YWCNP_SLUG;

			}

			return $new_row_meta_args;
		}


		/**
		 * Premium Tab Template
		 *
		 * Load the premium tab template on admin page
		 *
		 * @since   1.0.0
		 * @author YITH
		 */
		public function show_premium_tab() {
			$premium_tab_template = YWCNP_TEMPLATE_PATH . '/admin/' . $this->_premium;
			if ( file_exists( $premium_tab_template ) ) {
				include_once $premium_tab_template;
			}
		}

		/**
		 * Add a panel under YITH Plugins tab
		 *
		 * @return   void
		 * @since    1.0
		 * @author   Andrea Grillo <andrea.grillo@yithemes.com>
		 * @use     /Yit_Plugin_Panel class
		 * @see      plugin-fw/lib/yit-plugin-panel.php
		 */
		public function add_name_your_price_menu() {
			if ( ! empty( $this->_panel ) ) {
				return;
			}

			$admin_tabs['general-settings'] = __( 'General Settings', 'yith-woocommerce-name-your-price' );

			if ( ! defined( 'YWCNP_PREMIUM' ) ) {
				$admin_tabs['premium'] = __( 'Premium Version', 'yith-woocommerce-name-your-price' );
			}

			$args = array(
				'create_menu_page' => true,
				'parent_slug'      => '',
				'page_title'       => __( 'Name Your Price', 'yith-woocommerce-name-your-price' ),
				'plugin_slug'      => YWCNP_SLUG,
				'menu_title'       => 'Name Your Price',
				'capability'       => 'manage_options',
				'parent'           => '',
				'class'            => yith_set_wrapper_class(),
				'parent_page'      => 'yith_plugin_panel',
				'page'             => $this->_panel_page,
				'admin-tabs'       => apply_filters( 'ywcnp_add_premium_tab', $admin_tabs ),
				'options-path'     => YWCNP_DIR . '/plugin-options',
			);

			$this->_panel = new YIT_Plugin_Panel_WooCommerce( $args );
		}

		/**
		 * Print the minimum price html
		 *
		 * @author YITH
		 * @since 1.0.0
		 * @param string     $price The product price html.
		 * @param WC_Product $product The product.
		 * @return string
		 */
		public function get_nameyourprice_price_html( $price, $product ) {

			$product_type_supported = ywcnp_get_product_type_allowed();
			if ( $product->is_type( $product_type_supported ) && ywcnp_product_is_name_your_price( $product ) ) {

				$price = '';

				return apply_filters( 'ywcnp_get_product_price_html', $price, $product );
			} else {
				return $price;
			}

		}


	}
}
