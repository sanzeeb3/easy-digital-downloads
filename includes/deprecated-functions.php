<?php
/**
 * Deprecated Functions
 *
 * All functions that have been deprecated.
 *
 * @package     EDD
 * @subpackage  Deprecated
 * @copyright   Copyright (c) 2018, Easy Digital Downloads, LLC
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

use EDD\Reports;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Get Download Sales Log
 *
 * Returns an array of sales and sale info for a download.
 *
 * @since       1.0
 * @deprecated  1.3.4
 *
 * @param int $download_id ID number of the download to retrieve a log for
 * @param bool $paginate Whether to paginate the results or not
 * @param int $number Number of results to return
 * @param int $offset Number of items to skip
 *
 * @return mixed array|bool
*/
function edd_get_download_sales_log( $download_id, $paginate = false, $number = 10, $offset = 0 ) {
	$backtrace = debug_backtrace();

	_edd_deprecated_function( __FUNCTION__, '1.3.4', null, $backtrace );

	$sales_log = get_post_meta( $download_id, '_edd_sales_log', true );

	if ( $sales_log ) {
		$sales_log = array_reverse( $sales_log );
		$log = array();
		$log['number'] = count( $sales_log );
		$log['sales'] = $sales_log;

		if ( $paginate ) {
			$log['sales'] = array_slice( $sales_log, $offset, $number );
		}

		return $log;
	}

	return false;
}

/**
 * Get Downloads Of Purchase
 *
 * Retrieves an array of all files purchased.
 *
 * @since 1.0
 * @deprecated 1.4
 *
 * @param int  $payment_id ID number of the purchase
 * @param null $payment_meta
 * @return bool|mixed
 */
function edd_get_downloads_of_purchase( $payment_id, $payment_meta = null ) {
	$backtrace = debug_backtrace();

	_edd_deprecated_function( __FUNCTION__, '1.4', 'edd_get_payment_meta_downloads', $backtrace );

	if ( is_null( $payment_meta ) ) {
		$payment_meta = edd_get_payment_meta( $payment_id );
	}

	$downloads = maybe_unserialize( $payment_meta['downloads'] );

	if ( $downloads ) {
		return $downloads;
	}

	return false;
}

/**
 * Get Menu Access Level
 *
 * Returns the access level required to access the downloads menu. Currently not
 * changeable, but here for a future update.
 *
 * @since 1.0
 * @deprecated 1.4.4
 * @return string
*/
function edd_get_menu_access_level() {
	$backtrace = debug_backtrace();

	_edd_deprecated_function( __FUNCTION__, '1.4.4', 'current_user_can(\'manage_shop_settings\')', $backtrace );

	return apply_filters( 'edd_menu_access_level', 'manage_options' );
}



/**
 * Check if only local taxes are enabled meaning users must opt in by using the
 * option set from the EDD Settings.
 *
 * @since 1.3.3
 * @deprecated 1.6
 * @global $edd_options
 * @return bool $local_only
 */
function edd_local_taxes_only() {

	$backtrace = debug_backtrace();

	_edd_deprecated_function( __FUNCTION__, '1.6', 'no alternatives', $backtrace );

	global $edd_options;

	$local_only = isset( $edd_options['tax_condition'] ) && $edd_options['tax_condition'] == 'local';

	return apply_filters( 'edd_local_taxes_only', $local_only );
}

/**
 * Checks if a customer has opted into local taxes
 *
 * @since 1.4.1
 * @deprecated 1.6
 * @uses EDD_Session::get()
 * @return bool
 */
function edd_local_tax_opted_in() {

	$backtrace = debug_backtrace();

	_edd_deprecated_function( __FUNCTION__, '1.6', 'no alternatives', $backtrace );

	$opted_in = EDD()->session->get( 'edd_local_tax_opt_in' );
	return ! empty( $opted_in );
}

/**
 * Show taxes on individual prices?
 *
 * @since 1.4
 * @deprecated 1.9
 * @global $edd_options
 * @return bool Whether or not to show taxes on prices
 */
function edd_taxes_on_prices() {
	global $edd_options;

	$backtrace = debug_backtrace();

	_edd_deprecated_function( __FUNCTION__, '1.9', 'no alternatives', $backtrace );

	return apply_filters( 'edd_taxes_on_prices', isset( $edd_options['taxes_on_prices'] ) );
}

/**
 * Show Has Purchased Item Message
 *
 * Prints a notice when user has already purchased the item.
 *
 * @since 1.0
 * @deprecated 1.8
 * @global $user_ID
 */
function edd_show_has_purchased_item_message() {

	$backtrace = debug_backtrace();

	_edd_deprecated_function( __FUNCTION__, '1.8', 'no alternatives', $backtrace );

	global $user_ID, $post;

	if ( !isset( $post->ID ) ) {
		return;
	}

	if ( edd_has_user_purchased( $user_ID, $post->ID ) ) {
		$alert = '<p class="edd_has_purchased">' . __( 'You have already purchased this item, but you may purchase it again.', 'easy-digital-downloads' ) . '</p>';
		echo apply_filters( 'edd_show_has_purchased_item_message', $alert );
	}
}

/**
 * Flushes the total earning cache when a new payment is created
 *
 * @since 1.2
 * @deprecated 1.8.4
 * @param int $payment Payment ID
 * @param array $payment_data Payment Data
 * @return void
 */
function edd_clear_earnings_cache( $payment, $payment_data ) {

	$backtrace = debug_backtrace();

	_edd_deprecated_function( __FUNCTION__, '1.8.4', 'no alternatives', $backtrace );

	delete_transient( 'edd_total_earnings' );
}
//add_action( 'edd_insert_payment', 'edd_clear_earnings_cache', 10, 2 );

/**
 * Get Cart Amount
 *
 * @since 1.0
 * @deprecated 1.9
 * @param bool $add_taxes Whether to apply taxes (if enabled) (default: true)
 * @param bool $local_override Force the local opt-in param - used for when not reading $_POST (default: false)
 * @return float Total amount
*/
function edd_get_cart_amount( $add_taxes = true, $local_override = false ) {

	$backtrace = debug_backtrace();

	_edd_deprecated_function( __FUNCTION__, '1.9', 'edd_get_cart_subtotal() or edd_get_cart_total()', $backtrace );

	$amount = edd_get_cart_subtotal( );
	if ( ! empty( $_POST['edd-discount'] ) || edd_get_cart_discounts() !== false ) {
		// Retrieve the discount stored in cookies
		$discounts = edd_get_cart_discounts();

		// Check for a posted discount
		$posted_discount = isset( $_POST['edd-discount'] ) ? trim( $_POST['edd-discount'] ) : '';

		if ( $posted_discount && ! in_array( $posted_discount, $discounts ) ) {
			// This discount hasn't been applied, so apply it
			$amount = edd_get_discounted_amount( $posted_discount, $amount );
		}

		if ( ! empty( $discounts ) ) {
			// Apply the discounted amount from discounts already applied
			$amount -= edd_get_cart_discounted_amount();
		}
	}

	if ( edd_use_taxes() && edd_is_cart_taxed() && $add_taxes ) {
		$tax = edd_get_cart_tax();
		$amount += $tax;
	}

	if ( $amount < 0 ) {
		$amount = 0.00;
	}

	return apply_filters( 'edd_get_cart_amount', $amount, $add_taxes, $local_override );
}

/**
 * Get Purchase Receipt Template Tags
 *
 * Displays all available template tags for the purchase receipt.
 *
 * @since 1.6
 * @deprecated 1.9
 * @author Daniel J Griffiths
 * @return string $tags
 */
function edd_get_purchase_receipt_template_tags() {
	$tags = __('Enter the email that is sent to users after completing a successful purchase. HTML is accepted. Available template tags:','easy-digital-downloads' ) . '<br/>' .
			'{download_list} - ' . __('A list of download links for each download purchased','easy-digital-downloads' ) . '<br/>' .
			'{file_urls} - ' . __('A plain-text list of download URLs for each download purchased','easy-digital-downloads' ) . '<br/>' .
			'{name} - ' . __('The buyer\'s first name','easy-digital-downloads' ) . '<br/>' .
			'{fullname} - ' . __('The buyer\'s full name, first and last','easy-digital-downloads' ) . '<br/>' .
			'{username} - ' . __('The buyer\'s user name on the site, if they registered an account','easy-digital-downloads' ) . '<br/>' .
			'{user_email} - ' . __('The buyer\'s email address','easy-digital-downloads' ) . '<br/>' .
			'{billing_address} - ' . __('The buyer\'s billing address','easy-digital-downloads' ) . '<br/>' .
			'{date} - ' . __('The date of the purchase','easy-digital-downloads' ) . '<br/>' .
			'{subtotal} - ' . __('The price of the purchase before taxes','easy-digital-downloads' ) . '<br/>' .
			'{tax} - ' . __('The taxed amount of the purchase','easy-digital-downloads' ) . '<br/>' .
			'{price} - ' . __('The total price of the purchase','easy-digital-downloads' ) . '<br/>' .
			'{payment_id} - ' . __('The unique ID number for this purchase','easy-digital-downloads' ) . '<br/>' .
			'{receipt_id} - ' . __('The unique ID number for this purchase receipt','easy-digital-downloads' ) . '<br/>' .
			'{payment_method} - ' . __('The method of payment used for this purchase','easy-digital-downloads' ) . '<br/>' .
			'{sitename} - ' . __('Your site name','easy-digital-downloads' ) . '<br/>' .
			'{receipt_link} - ' . __( 'Adds a link so users can view their receipt directly on your website if they are unable to view it in the browser correctly.', 'easy-digital-downloads' );

	return apply_filters( 'edd_purchase_receipt_template_tags_description', $tags );
}


/**
 * Get Sale Notification Template Tags
 *
 * Displays all available template tags for the sale notification email
 *
 * @since 1.7
 * @deprecated 1.9
 * @author Daniel J Griffiths
 * @return string $tags
 */
function edd_get_sale_notification_template_tags() {
	$tags = __( 'Enter the email that is sent to sale notification emails after completion of a purchase. HTML is accepted. Available template tags:', 'easy-digital-downloads' ) . '<br/>' .
			'{download_list} - ' . __('A list of download links for each download purchased','easy-digital-downloads' ) . '<br/>' .
			'{file_urls} - ' . __('A plain-text list of download URLs for each download purchased','easy-digital-downloads' ) . '<br/>' .
			'{name} - ' . __('The buyer\'s first name','easy-digital-downloads' ) . '<br/>' .
			'{fullname} - ' . __('The buyer\'s full name, first and last','easy-digital-downloads' ) . '<br/>' .
			'{username} - ' . __('The buyer\'s user name on the site, if they registered an account','easy-digital-downloads' ) . '<br/>' .
			'{user_email} - ' . __('The buyer\'s email address','easy-digital-downloads' ) . '<br/>' .
			'{billing_address} - ' . __('The buyer\'s billing address','easy-digital-downloads' ) . '<br/>' .
			'{date} - ' . __('The date of the purchase','easy-digital-downloads' ) . '<br/>' .
			'{subtotal} - ' . __('The price of the purchase before taxes','easy-digital-downloads' ) . '<br/>' .
			'{tax} - ' . __('The taxed amount of the purchase','easy-digital-downloads' ) . '<br/>' .
			'{price} - ' . __('The total price of the purchase','easy-digital-downloads' ) . '<br/>' .
			'{payment_id} - ' . __('The unique ID number for this purchase','easy-digital-downloads' ) . '<br/>' .
			'{receipt_id} - ' . __('The unique ID number for this purchase receipt','easy-digital-downloads' ) . '<br/>' .
			'{payment_method} - ' . __('The method of payment used for this purchase','easy-digital-downloads' ) . '<br/>' .
			'{sitename} - ' . __('Your site name','easy-digital-downloads' );

	return apply_filters( 'edd_sale_notification_template_tags_description', $tags );
}

/**
 * Email Template Header
 *
 * @access private
 * @since 1.0.8.2
 * @deprecated 2.0
 * @return string Email template header
 */
function edd_get_email_body_header() {
	$backtrace = debug_backtrace();

	_edd_deprecated_function( __FUNCTION__, '2.0', '', $backtrace );

	ob_start();
	?>
	<html>
	<head>
		<style type="text/css">#outlook a { padding: 0; }</style>
	</head>
	<body dir="<?php echo is_rtl() ? 'rtl' : 'ltr'; ?>">
	<?php
	do_action( 'edd_email_body_header' );
	return ob_get_clean();
}

/**
 * Email Template Footer
 *
 * @since 1.0.8.2
 * @deprecated 2.0
 * @return string Email template footer
 */
function edd_get_email_body_footer() {

	$backtrace = debug_backtrace();

	_edd_deprecated_function( __FUNCTION__, '2.0', '', $backtrace );

	ob_start();
	do_action( 'edd_email_body_footer' );
	?>
	</body>
	</html>
	<?php
	return ob_get_clean();
}

/**
 * Applies the Chosen Email Template
 *
 * @since 1.0.8.2
 * @deprecated 2.0
 * @param string $body The contents of the receipt email
 * @param int $payment_id The ID of the payment we are sending a receipt for
 * @param array $payment_data An array of meta information for the payment
 * @return string $email Formatted email with the template applied
 */
function edd_apply_email_template( $body, $payment_id, $payment_data = array() ) {
	global $edd_options;

	$backtrace = debug_backtrace();

	_edd_deprecated_function( __FUNCTION__, '2.0', '', $backtrace );

	$template_name = isset( $edd_options['email_template'] ) ? $edd_options['email_template'] : 'default';
	$template_name = apply_filters( 'edd_email_template', $template_name, $payment_id );

	if ( $template_name == 'none' ) {
		if ( is_admin() ) {
			$body = edd_email_preview_template_tags( $body );
		}

		// Return the plain email with no template
		return $body;
	}

	ob_start();

	do_action( 'edd_email_template_' . $template_name );

	$template = ob_get_clean();

	if ( is_admin() ) {
		$body = edd_email_preview_template_tags( $body );
	}

	$body = apply_filters( 'edd_purchase_receipt_' . $template_name, $body );

	$email = str_replace( '{email}', $body, $template );

	return $email;

}

/**
 * Checks if the user has enabled the option to calculate taxes after discounts
 * have been entered
 *
 * @since 1.4.1
 * @deprecated 2.1
 * @global $edd_options
 * @return bool Whether or not taxes are calculated after discount
 */
function edd_taxes_after_discounts() {

	$backtrace = debug_backtrace();

	_edd_deprecated_function( __FUNCTION__, '2.1', 'none', $backtrace );

	global $edd_options;
	$ret = isset( $edd_options['taxes_after_discounts'] ) && edd_use_taxes();
	return apply_filters( 'edd_taxes_after_discounts', $ret );
}

/**
 * Verifies a download purchase using a purchase key and email.
 *
 * @deprecated Please avoid usage of this function in favor of the tokenized urls with edd_validate_url_token()
 * introduced in EDD 2.3
 *
 * @since 1.0
 *
 * @param int    $download_id
 * @param string $key
 * @param string $email
 * @param string $expire
 * @param int    $file_key
 *
 * @return bool True if payment and link was verified, false otherwise
 */
function edd_verify_download_link( $download_id = 0, $key = '', $email = '', $expire = '', $file_key = 0 ) {

	$meta_query = array(
		'relation'  => 'AND',
		array(
			'key'   => '_edd_payment_purchase_key',
			'value' => $key
		),
		array(
			'key'   => '_edd_payment_user_email',
			'value' => $email
		)
	);

	$accepted_stati = apply_filters( 'edd_allowed_download_stati', array( 'publish', 'complete' ) );

	$payments = get_posts( array( 'meta_query' => $meta_query, 'post_type' => 'edd_payment', 'post_status' => $accepted_stati ) );

	if ( $payments ) {
		foreach ( $payments as $payment ) {

			$cart_details = edd_get_payment_meta_cart_details( $payment->ID, true );

			if ( ! empty( $cart_details ) ) {
				foreach ( $cart_details as $cart_key => $cart_item ) {

					if ( $cart_item['id'] != $download_id ) {
						continue;
					}

					$price_options 	= isset( $cart_item['item_number']['options'] ) ? $cart_item['item_number']['options'] : false;
					$price_id 		= isset( $price_options['price_id'] ) ? $price_options['price_id'] : false;

					$file_condition = edd_get_file_price_condition( $cart_item['id'], $file_key );

					// Check to see if the file download limit has been reached
					if ( edd_is_file_at_download_limit( $cart_item['id'], $payment->ID, $file_key, $price_id ) ) {
						wp_die( apply_filters( 'edd_download_limit_reached_text', __( 'Sorry but you have hit your download limit for this file.', 'easy-digital-downloads' ) ), __( 'Error', 'easy-digital-downloads' ), array( 'response' => 403 ) );
					}

					// If this download has variable prices, we have to confirm that this file was included in their purchase
					if ( ! empty( $price_options ) && $file_condition != 'all' && edd_has_variable_prices( $cart_item['id'] ) ) {
						if ( $file_condition == $price_options['price_id'] ) {
							return $payment->ID;
						}
					}

					// Make sure the link hasn't expired

					if ( base64_encode( base64_decode( $expire, true ) ) === $expire ) {
						$expire = base64_decode( $expire ); // If it is a base64 string, decode it. Old expiration dates were in base64
					}

					if ( current_time( 'timestamp' ) > $expire ) {
						wp_die( apply_filters( 'edd_download_link_expired_text', __( 'Sorry but your download link has expired.', 'easy-digital-downloads' ) ), __( 'Error', 'easy-digital-downloads' ), array( 'response' => 403 ) );
					}
					return $payment->ID; // Payment has been verified and link is still valid
				}
			}
		}

	} else {
		wp_die( __( 'No payments matching your request were found.', 'easy-digital-downloads' ), __( 'Error', 'easy-digital-downloads' ), array( 'response' => 403 ) );
	}
	// Payment not verified
	return false;
}

/**
 * Get Success Page URL
 *
 * @param string $query_string
 * @since       1.0
 * @deprecated  2.6 Please avoid usage of this function in favor of edd_get_success_page_uri()
 * @return      string
*/
function edd_get_success_page_url( $query_string = null ) {

	$backtrace = debug_backtrace();

	_edd_deprecated_function( __FUNCTION__, '2.6', 'edd_get_success_page_uri()', $backtrace );

	return apply_filters( 'edd_success_page_url', edd_get_success_page_uri( $query_string ) );
}

/**
 * Reduces earnings and sales stats when a purchase is refunded
 *
 * @since 1.8.2
 * @param int $payment_id the ID number of the payment
 * @param string $new_status the status of the payment, probably "publish"
 * @param string $old_status the status of the payment prior to being marked as "complete", probably "pending"
 * @deprecated  2.5.7 Please avoid usage of this function in favor of refund() in EDD_Payment
 * @internal param Arguments $data passed
 */
function edd_undo_purchase_on_refund( $payment_id, $new_status, $old_status ) {

	$backtrace = debug_backtrace();
	_edd_deprecated_function( 'edd_undo_purchase_on_refund', '2.5.7', 'EDD_Payment->refund()', $backtrace );

	$payment = new EDD_Payment( $payment_id );
	$payment->refund();
}

/**
 * Get Earnings By Date
 *
 * @since 1.0
 * @deprecated 2.7
 * @param int $day Day number
 * @param int $month_num Month number
 * @param int $year Year
 * @param int $hour Hour
 * @return int $earnings Earnings
 */
function edd_get_earnings_by_date( $day, $month_num, $year = null, $hour = null, $include_taxes = true ) {
	$backtrace = debug_backtrace();

	_edd_deprecated_function( __FUNCTION__, '2.7', 'EDD_Payment_Stats()->get_earnings()', $backtrace );

	global $wpdb;

	$args = array(
		'post_type'      => 'edd_payment',
		'nopaging'       => true,
		'year'           => $year,
		'monthnum'       => $month_num,
		'post_status'    => array( 'publish', 'revoked' ),
		'fields'         => 'ids',
		'include_taxes'  => $include_taxes,
		'update_post_term_cache' => false,
	);

	if ( ! empty( $day ) ) {
		$args['day'] = $day;
	}

	if ( ! empty( $hour ) || $hour == 0 ) {
		$args['hour'] = $hour;
	}

	$args   = apply_filters( 'edd_get_earnings_by_date_args', $args );
	$cached = get_transient( 'edd_stats_earnings' );
	$key    = md5( json_encode( $args ) );

	if ( ! isset( $cached[ $key ] ) ) {
		$sales = get_posts( $args );
		$earnings = 0;
		if ( $sales ) {
			$sales = implode( ',', $sales );

			$total_earnings = $wpdb->get_var( "SELECT SUM(meta_value) FROM $wpdb->postmeta WHERE meta_key = '_edd_payment_total' AND post_id IN ({$sales})" );
			$total_tax      = 0;

			if ( ! $include_taxes ) {
				$total_tax = $wpdb->get_var( "SELECT SUM(meta_value) FROM $wpdb->postmeta WHERE meta_key = '_edd_payment_tax' AND post_id IN ({$sales})" );
			}

			$earnings += ( $total_earnings - $total_tax );
		}
		// Cache the results for one hour
		$cached[ $key ] = $earnings;
		set_transient( 'edd_stats_earnings', $cached, HOUR_IN_SECONDS );
	}

	$result = $cached[ $key ];

	return round( $result, 2 );
}

/**
 * Get Sales By Date
 *
 * @since 1.1.4.0
 * @deprecated 2.7
 * @author Sunny Ratilal
 * @param int $day Day number
 * @param int $month_num Month number
 * @param int $year Year
 * @param int $hour Hour
 * @return int $count Sales
 */
function edd_get_sales_by_date( $day = null, $month_num = null, $year = null, $hour = null ) {
	$backtrace = debug_backtrace();

	_edd_deprecated_function( __FUNCTION__, '2.7', 'EDD_Payment_Stats()->get_sales()', $backtrace );

	$args = array(
		'post_type'      => 'edd_payment',
		'nopaging'       => true,
		'year'           => $year,
		'fields'         => 'ids',
		'post_status'    => array( 'publish', 'revoked' ),
		'update_post_meta_cache' => false,
		'update_post_term_cache' => false
	);

	$show_free = apply_filters( 'edd_sales_by_date_show_free', true, $args );

	if ( false === $show_free ) {
		$args['meta_query'] = array(
			array(
				'key' => '_edd_payment_total',
				'value' => 0,
				'compare' => '>',
				'type' => 'NUMERIC',
			),
		);
	}

	if ( ! empty( $month_num ) ) {
		$args['monthnum'] = $month_num;
	}

	if ( ! empty( $day ) ) {
		$args['day'] = $day;
	}

	if ( ! empty( $hour ) ) {
		$args['hour'] = $hour;
	}

	$args = apply_filters( 'edd_get_sales_by_date_args', $args  );

	$cached = get_transient( 'edd_stats_sales' );
	$key    = md5( json_encode( $args ) );

	if ( ! isset( $cached[ $key ] ) ) {
		$sales = new WP_Query( $args );
		$count = (int) $sales->post_count;

		// Cache the results for one hour
		$cached[ $key ] = $count;
		set_transient( 'edd_stats_sales', $cached, HOUR_IN_SECONDS );
	}

	$result = $cached[ $key ];

	return $result;
}

/**
 * Set the Page Style for PayPal Purchase page
 *
 * @since 1.4.1
 * @deprecated 2.8
 * @return string
 */
function edd_get_paypal_page_style() {

	$backtrace = debug_backtrace();

	_edd_deprecated_function( __FUNCTION__, '2.8', 'edd_get_paypal_image_url', $backtrace );

	$page_style = trim( edd_get_option( 'paypal_page_style', 'PayPal' ) );
	return apply_filters( 'edd_paypal_page_style', $page_style );
}

/**
 * Should we add schema.org microdata?
 *
 * @since 1.7
 * @since 3.0 - Deprecated as the switch was made to JSON-LD.
 * @see https://github.com/easydigitaldownloads/easy-digital-downloads/issues/5240
 *
 * @return bool
 */
function edd_add_schema_microdata() {
	$backtrace = debug_backtrace();

	_edd_deprecated_function( __FUNCTION__, '3.0', 'EDD_Structured_Data', $backtrace );

	// Don't modify anything until after wp_head() is called
	$ret = (bool)did_action( 'wp_head' );
	return apply_filters( 'edd_add_schema_microdata', $ret );
}

/**
 * Add Microdata to download titles
 *
 * @since 1.5
 * @since 3.0 - Deprecated as the switch was made to JSON-LD.
 * @see https://github.com/easydigitaldownloads/easy-digital-downloads/issues/5240
 *
 * @param string $title Post Title
 * @param int $id Post ID
 * @return string $title New title
 */
function edd_microdata_title( $title, $id = 0 ) {
	$backtrace = debug_backtrace();

	_edd_deprecated_function( __FUNCTION__, '3.0', 'EDD_Structured_Data', $backtrace );

	global $post;

	if ( ! edd_add_schema_microdata() || ! is_object( $post ) ) {
		return $title;
	}

	if ( $post->ID == $id && is_singular( 'download' ) && 'download' == get_post_type( intval( $id ) ) ) {
		$title = '<span itemprop="name">' . $title . '</span>';
	}

	return $title;
}

/**
 * Start Microdata to wrapper download
 *
 * @since 2.3
 * @since 3.0 - Deprecated as the switch was made to JSON-LD.
 * @see https://github.com/easydigitaldownloads/easy-digital-downloads/issues/5240
 *
 * @return void
 */
function edd_microdata_wrapper_open( $query ) {
	$backtrace = debug_backtrace();

	_edd_deprecated_function( __FUNCTION__, '3.0', 'EDD_Structured_Data', $backtrace );

	static $microdata_open = NULL;

	if ( ! edd_add_schema_microdata() || true === $microdata_open || ! is_object( $query ) ) {
		return;
	}

	if ( $query && ! empty( $query->query['post_type'] ) && $query->query['post_type'] == 'download' && is_singular( 'download' ) && $query->is_main_query() ) {
		$microdata_open = true;
		echo '<div itemscope itemtype="http://schema.org/Product">';
	}
}

/**
 * End Microdata to wrapper download
 *
 * @since 2.3
 * @since 3.0 - Deprecated as the switch was made to JSON-LD.
 * @see https://github.com/easydigitaldownloads/easy-digital-downloads/issues/5240
 *
 * @return void
 */
function edd_microdata_wrapper_close() {
	$backtrace = debug_backtrace();

	_edd_deprecated_function( __FUNCTION__, '3.0', 'EDD_Structured_Data', $backtrace );

	global $post;

	static $microdata_close = NULL;

	if ( ! edd_add_schema_microdata() || true === $microdata_close || ! is_object( $post ) ) {
		return;
	}

	if ( $post && $post->post_type == 'download' && is_singular( 'download' ) && is_main_query() ) {
		$microdata_close = true;
		echo '</div>';
	}
}

/**
 * Add Microdata to download description
 *
 * @since 1.5
 * @since 3.0 - Deprecated as the switch was made to JSON-LD.
 * @see https://github.com/easydigitaldownloads/easy-digital-downloads/issues/5240
 *
 * @param $content
 * @return mixed|void New title
 */
function edd_microdata_description( $content ) {
	$backtrace = debug_backtrace();

	_edd_deprecated_function( __FUNCTION__, '3.0', 'EDD_Structured_Data', $backtrace );

	global $post;

	static $microdata_description = NULL;

	if ( ! edd_add_schema_microdata() || true === $microdata_description || ! is_object( $post ) ) {
		return $content;
	}

	if ( $post && $post->post_type == 'download' && is_singular( 'download' ) && is_main_query() ) {
		$microdata_description = true;
		$content = apply_filters( 'edd_microdata_wrapper', '<div itemprop="description">' . $content . '</div>' );
	}
	return $content;
}

/**
 * Output schema markup for single price products.
 *
 * @since  2.6.14
 * @since 3.0 - Deprecated as the switch was made to JSON-LD.
 * @see https://github.com/easydigitaldownloads/easy-digital-downloads/issues/5240
 *
 * @param  int $download_id The download being output.
 * @return void
 */
function edd_purchase_link_single_pricing_schema( $download_id = 0, $args = array() ) {
	$backtrace = debug_backtrace();

	_edd_deprecated_function( __FUNCTION__, '3.0', 'EDD_Structured_Data', $backtrace );

	// Bail if the product has variable pricing, or if we aren't showing schema data.
	if ( edd_has_variable_prices( $download_id ) || ! edd_add_schema_microdata() ) {
		return;
	}

	// Grab the information we need.
	$download = new EDD_Download( $download_id );
	?>
    <span itemprop="offers" itemscope itemtype="http://schema.org/Offer">
		<meta itemprop="price" content="<?php echo esc_attr( $download->price ); ?>" />
		<meta itemprop="priceCurrency" content="<?php echo esc_attr( edd_get_currency() ); ?>" />
	</span>
	<?php
}

/**
 * Renders the Logs tab in the Reports screen.
 *
 * @since 1.3
 * @deprecated 3.0 Use edd_tools_tab_logs() instead.
 * @see edd_tools_tab_logs()
 * @return void
 */
function edd_reports_tab_logs() {
	_edd_deprecated_function( __FUNCTION__, '3.0', 'edd_tools_tab_logs' );

	if ( ! function_exists( 'edd_tools_tab_logs' ) ) {
		require_once EDD_PLUGIN_DIR . 'includes/admin/tools/logs.php';
	}

	edd_tools_tab_logs();
}

/**
 * Defines views for the legacy 'Reports' tab.
 *
 * @since 1.4
 * @deprecated 3.0 Use \EDD\Reports\get_reports()
 * @see \EDD\Reports\get_reports()
 *
 * @return array $views Report Views
 */
function edd_reports_default_views() {
	_edd_deprecated_function( __FUNCTION__, '3.0', '\EDD\Reports\get_reports' );

	return Reports\get_reports();
}

/**
 * Renders the Reports page
 *
 * @since 1.3
 * @deprecated 3.0 Unused.
 */
function edd_reports_tab_reports() {

	_edd_deprecated_function( __FUNCTION__, '3.0' );

	if ( ! current_user_can( 'view_shop_reports' ) ) {
		wp_die( __( 'You do not have permission to access this report', 'easy-digital-downloads' ), __( 'Error', 'easy-digital-downloads' ), array( 'response' => 403 ) );
	}

	$current_view = 'earnings';
	$views        = edd_reports_default_views();

	if ( isset( $_GET['view'] ) && array_key_exists( $_GET['view'], $views ) ) {
		$current_view = $_GET['view'];
	}

	/**
	 * Legacy: fired inside the old global 'Reports' tab.
	 *
	 * The dynamic portion of the hook name, `$current_view`, represented the parsed value of
	 * the 'view' query variable.
	 *
	 * @since 1.3
	 * @deprecated 3.0 Unused.
	 */
	edd_do_action_deprecated( 'edd_reports_view_' . $current_view, array(), '3.0' );

}

/**
 * Default Report Views
 *
 * Checks the $_GET['view'] parameter to ensure it exists within the default allowed views.
 *
 * @param string $default Default view to use.
 *
 * @since 1.9.6
 * @deprecated 3.0 Unused.
 *
 * @return string $view Report View
 */
function edd_get_reporting_view( $default = 'earnings' ) {

	_edd_deprecated_function( __FUNCTION__, '3.0' );

	if ( ! isset( $_GET['view'] ) || ! in_array( $_GET['view'], array_keys( edd_reports_default_views() ) ) ) {
		$view = $default;
	} else {
		$view = $_GET['view'];
	}

	/**
	 * Legacy: filters the current reporting view (now implemented solely via the 'tab' var).
	 *
	 * @since 1.9.6
	 * @deprecated 3.0 Unused.
	 *
	 * @param string $view View slug.
	 */
	return edd_apply_filters_deprecated( 'edd_get_reporting_view', array( $view ), '3.0' );
}

/**
 * Renders the Reports Page Views Drop Downs
 *
 * @since 1.3
 * @deprecated 3.0 Unused.
 *
 * @return void
 */
function edd_report_views() {

	_edd_deprecated_function( __FUNCTION__, '3.0' );

	/**
	 * Legacy: fired before the view actions drop-down was output.
	 *
	 * @since 1.3
	 * @deprecated 3.0 Unused.
	 */
	edd_do_action_deprecated( 'edd_report_view_actions', array(), '3.0' );

	/**
	 * Legacy: fired after the view actions drop-down was output.
	 *
	 * @since 1.3
	 * @deprecated 3.0 Unused.
	 */
	edd_do_action_deprecated( 'edd_report_view_actions_after', array(), '3.0' );

	return;
}

/**
 * Show report graph date filters.
 *
 * @since 1.3
 * @deprecated 3.0 Unused.
 */
function edd_reports_graph_controls() {
	_edd_deprecated_function( __FUNCTION__, 'EDD 3.0' );
}

/**
 * Sets up the dates used to filter graph data
 *
 * Date sent via $_GET is read first and then modified (if needed) to match the
 * selected date-range (if any)
 *
 * @since 1.3
 * @deprecated 3.0 Use \EDD\Reports\get_dates_filter() instead
 * @see \EDD\Reports\get_dates_filter()
 *
 * @param string $timezone Optional. Timezone to force for report filter dates calculations.
 *                         Default is the WP timezone.
 * @return array Array of report filter dates.
 */
function edd_get_report_dates( $timezone = null ) {

	_edd_deprecated_function( __FUNCTION__, '3.0', '\EDD\Reports\get_dates_filter' );

	Reports\Init::bootstrap();

	add_filter( 'edd_get_dates_filter_range', '\EDD\Reports\compat_filter_date_range' );

	$filter_dates = Reports\get_dates_filter( 'objects', $timezone );
	$range        = Reports\get_dates_filter_range();

	remove_filter( 'edd_get_report_dates_default_range', '\EDD\Reports\compat_filter_date_range' );

	$dates = array(
		'range'    => $range,
		'day'      => $filter_dates['start']->format( 'd' ),
		'day_end'  => $filter_dates['end']->format( 'd' ),
		'm_start'  => $filter_dates['start']->month,
		'm_end'    => $filter_dates['end']->month,
		'year'     => $filter_dates['start']->year,
		'year_end' => $filter_dates['end']->year,
	);

	/**
	 * Filters the legacy list of parsed report dates for use in the Reports API.
	 *
	 * @since 1.3
	 * @deprecated 3.0
	 *
	 * @param array $dates Array of legacy date parts.
	 */
	return edd_apply_filters_deprecated( 'edd_report_dates', array( $dates ), '3.0' );
}

/**
 * Intercept default Edit post links for EDD orders and rewrite them to the View Order Details screen.
 *
 * @since 1.8.3
 * @deprecated 3.0 No alternative present as get_post() does not work with orders.
 *
 * @param $url
 * @param $post_id
 * @param $context
 *
 * @return string
 */
function edd_override_edit_post_for_payment_link( $url = '', $post_id = 0, $context = '') {
	_edd_deprecated_function( __FUNCTION__, '3.0', '' );

	$post = get_post( $post_id );

	if ( empty( $post ) ) {
		return $url;
	}

	if ( 'edd_payment' !== $post->post_type ) {
		return $url;
	}

	return edd_get_admin_url( array(
		'page' => 'edd-payment-history',
		'view' => 'view-order-details',
		'id'   => $post_id
	) );
}

/**
 * Record sale as a log.
 *
 * Stores log information for a download sale.
 *
 * @since 1.0
 * @deprecated 3.0 Sales logs are no longed stored.
 *
 * @param int    $download_id Download ID
 * @param int    $payment_id  Payment ID.
 * @param int    $price_id    Optional. Price ID.
 * @param string $sale_date   Optional. Date of the sale.
 */
function edd_record_sale_in_log( $download_id, $payment_id, $price_id = false, $sale_date = null ) {
	_edd_deprecated_function( __FUNCTION__, '3.0' );

	$edd_logs = EDD()->debug_log;

	$log_data = array(
		'post_parent'   => $download_id,
		'log_type'      => 'sale',
		'post_date'     => ! empty( $sale_date ) ? $sale_date : null,
		'post_date_gmt' => ! empty( $sale_date ) ? get_gmt_from_date( $sale_date ) : null,
	);

	$log_meta = array(
		'payment_id' => $payment_id,
		'price_id'   => (int) $price_id,
	);

	$edd_logs->insert_log( $log_data, $log_meta );
}

/**
 * Outputs the JavaScript code for the Agree to Terms section to toggle
 * the T&Cs text
 *
 * @since 1.0
 * @deprecated 3.0 Moved to external scripts in assets/js/frontend/checkout/components/agree-to-terms
 */
function edd_agree_to_terms_js() {
	_edd_deprecated_function( __FUNCTION__, '3.0' );
}

/**
 * Record payment status change
 *
 * @since 1.4.3
 * @deprecated since 3.0
 * @param int    $payment_id the ID number of the payment.
 * @param string $new_status the status of the payment, probably "publish".
 * @param string $old_status the status of the payment prior to being marked as "complete", probably "pending".
 * @return void
 */
function edd_record_status_change( $payment_id, $new_status, $old_status ) {
	$backtrace = debug_backtrace();

	_edd_deprecated_function( __FUNCTION__, '3.0', 'edd_record_order_status_change', $backtrace );

	// Get the list of statuses so that status in the payment note can be translated
	$stati      = edd_get_payment_statuses();
	$old_status = isset( $stati[ $old_status ] ) ? $stati[ $old_status ] : $old_status;
	$new_status = isset( $stati[ $new_status ] ) ? $stati[ $new_status ] : $new_status;

	$status_change = sprintf( __( 'Status changed from %s to %s', 'easy-digital-downloads' ), $old_status, $new_status );

	edd_insert_payment_note( $payment_id, $status_change );
}


/**
 * Shows checkbox to automatically refund payments made in PayPal.
 *
 * @deprecated 3.0 In favour of `edd_paypal_refund_checkbox()`
 * @see edd_paypal_refund_checkbox()
 *
 * @since  2.6.0
 *
 * @param int $payment_id The current payment ID.
 * @return void
 */
function edd_paypal_refund_admin_js( $payment_id = 0 ) {

	$backtrace = debug_backtrace();

	_edd_deprecated_function( __FUNCTION__, '3.0', 'edd_paypal_refund_checkbox', $backtrace );

	// If not the proper gateway, return early.
	if ( 'paypal' !== edd_get_payment_gateway( $payment_id ) ) {
		return;
	}

	// If our credentials are not set, return early.
	$key       = edd_get_payment_meta( $payment_id, '_edd_payment_mode', true );
	$username  = edd_get_option( 'paypal_' . $key . '_api_username' );
	$password  = edd_get_option( 'paypal_' . $key . '_api_password' );
	$signature = edd_get_option( 'paypal_' . $key . '_api_signature' );

	if ( empty( $username ) || empty( $password ) || empty( $signature ) ) {
		return;
	}

	// Localize the refund checkbox label.
	$label = __( 'Refund Payment in PayPal', 'easy-digital-downloads' );

	?>
	<script type="text/javascript">
		jQuery(document).ready(function($) {
			$('select[name=edd-payment-status]').change(function() {
				if ( 'refunded' === $(this).val() ) {
					$(this).parent().parent().append('<input type="checkbox" id="edd-paypal-refund" name="edd-paypal-refund" value="1" style="margin-top:0">');
					$(this).parent().parent().append('<label for="edd-paypal-refund"><?php echo $label; ?></label>');
				} else {
					$('#edd-paypal-refund').remove();
					$('label[for="edd-paypal-refund"]').remove();
				}
			});
		});
	</script>
	<?php
}

/**
 * Possibly refunds a payment made with PayPal Standard or PayPal Express.
 *
 * @deprecated 3.0 In favour of `edd_paypal_maybe_refund_transaction()`
 * @see edd_paypal_maybe_refund_transaction()
 *
 * @since  2.6.0
 *
 * @param object|EDD_Payment $payment The current payment ID.
 * @return void
 */
function edd_maybe_refund_paypal_purchase( EDD_Payment $payment ) {
	$backtrace = debug_backtrace();

	_edd_deprecated_function( __FUNCTION__, '3.0', 'edd_paypal_maybe_refund_transaction', $backtrace );

	if ( ! current_user_can( 'edit_shop_payments', $payment->ID ) ) {
		return;
	}

	if ( empty( $_POST['edd-paypal-refund'] ) ) {
		return;
	}

	$processed = $payment->get_meta( '_edd_paypal_refunded', true );

	// If the status is not set to "refunded", return early.
	if ( 'complete' !== $payment->old_status && 'revoked' !== $payment->old_status ) {
		return;
	}

	// If not PayPal/PayPal Express, return early.
	if ( 'paypal' !== $payment->gateway ) {
		return;
	}

	// If the payment has already been refunded in the past, return early.
	if ( $processed ) {
		return;
	}

	// Process the refund in PayPal.
	edd_refund_paypal_purchase( $payment );
}
