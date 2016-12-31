<?php
/**
 * Earnings Report Export Class
 *
 * This class handles earnings report export.
 *
 * @package     EDD
 * @subpackage  Admin/Reports
 * @copyright   Copyright (c) 2017, Sunny Ratilal
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       2.7
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * EDD_Earnings_Report_Export Class
 *
 * @since 2.7
 */
class EDD_Earnings_Report_Export extends EDD_Export {
	/**
	 * Our export type. Used for export-type specific filters/actions.
	 *
	 * @since 2.7
	 * @access public
	 * @var string
	 */
	public $export_type = 'earnings_report';

	/**
	 * Set the export headers.
	 *
	 * @since 2.7
	 * @access public
	 *
	 * @return void
	 */
	public function headers() {
		ignore_user_abort( true );

		if ( ! edd_is_func_disabled( 'set_time_limit' ) && ! ini_get( 'safe_mode' ) ) {
			set_time_limit( 0 );
		}

		nocache_headers();
		header( 'Content-Type: text/csv; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename=' . apply_filters( 'edd_earnings_report_export_filename', 'edd-export-' . $this->export_type . '-' . date( 'm' ) . '-' . date( 'Y' ) ) . '.csv' );
		header( "Expires: 0" );
	}

	/**
	 * Output the CSV rows.
	 *
	 * @since 2.7
	 * @access public
	 *
	 * @return void
	 */
	public function csv_rows_out() {
		$start_year  = isset( $_POST['start_year'] )   ? absint( $_POST['start_year'] )   : date( 'Y' );
		$end_year    = isset( $_POST['end_year'] )     ? absint( $_POST['end_year'] )     : date( 'Y' );
		$start_month = isset( $_POST['start_month'] )  ? absint( $_POST['start_month'] )  : date( 'm' );
		$end_month   = isset( $_POST['end_month'] )    ? absint( $_POST['end_month'] )    : date( 'm' );

		$start_date = date( 'Y-m-d', strtotime( $start_year . '-' . $start_month . '-01' ) );
		$end_date = date( 'Y-m-d', strtotime( $end_year . '-' . $end_month . '-01' ) );

		$data = $this->get_data();

		/**
		 * Month Row.
		 */
		$this->col( 2 );
		echo __( 'Month', 'easy-digital-downloads' ) . ',';
		while ( strtotime( $start_date ) <= strtotime( $end_date ) ) {
			echo date( 'Y-m-d', strtotime( $start_date ) );

			if ( $start_date == $end_date ) {
				$this->row();
			} else {
				$this->col();
			}

			$start_date = date( 'Y-m-d', strtotime( '+1 month', strtotime( $start_date ) ) );
		}

		/**
		 * Currency Row.
		 */
		$start_date = date( 'Y-m-d', strtotime( $start_year . '-' . $start_month . '-01' ) );
		$end_date = date( 'Y-m-d', strtotime( $end_year . '-' . $end_month . '-01' ) );
		$this->col( 2 );
		echo __( 'Currency', 'easy-digital-downloads' ) . ',';
		while ( strtotime( $start_date ) <= strtotime( $end_date ) ) {
			echo edd_get_currency();

			if ( $start_date == $end_date ) {
				$this->row();
			} else {
				echo ',';
			}

			$start_date = date( 'Y-m-d', strtotime( '+1 month', strtotime( $start_date ) ) );
		}

		$this->row();
		echo __( 'Monthly Sales Activity', 'easy-digital-downloads' );
		$this->row();

		/**
		 * Sales.
		 *
		 * Displays Sales Count and Amount.
		 */
		$this->col();
		echo __( 'Sales' , 'easy-digital-downloads' );
		$this->col();
		echo __( 'Count', 'easy-digital-downloads' );
		$this->col();
		foreach ( $data as $item ) {
			echo isset( $item['publish']['count'] ) ? $item['publish']['count'] : 0;
			$this->col();
		}
		$this->row();
		$this->col( 2 );
		echo __( 'Amount', 'easy-digital-downloads' );
		$this->col();
		foreach ( $data as $item ) {
			echo isset( $item['publish']['amount'] ) ? $item['publish']['amount'] : 0;
			$this->col();
		}

		/**
		 * Refunds.
		 *
		 * Displays Refunds Count and Amount.
		 */
		$this->row();
		$this->col();
		echo __( 'Refunds' , 'easy-digital-downloads' );
		$this->col();
		echo __( 'Count', 'easy-digital-downloads' );
		$this->col();
		foreach ( $data as $item ) {
			echo isset( $item['refunded']['count'] ) ? $item['refunded']['count'] : 0;
			$this->col();
		}
		$this->row();
		$this->col( 2 );
		echo __( 'Amount', 'easy-digital-downloads' );
		$this->col();
		foreach ( $data as $item ) {
			echo isset( $item['refunded']['amount'] ) ? $item['refunded']['amount'] : 0;
			$this->col();
		}

		/**
		 * Revoked.
		 *
		 * Displays Revoked Count and Amount.
		 */
		$this->row();
		$this->col();
		echo __( 'Revoked' , 'easy-digital-downloads' );
		$this->col();
		echo __( 'Count', 'easy-digital-downloads' );
		$this->col();
		foreach ( $data as $item ) {
			echo isset( $item['revoked']['count'] ) ? $item['revoked']['count'] : 0;
			$this->col();
		}
		$this->row();
		$this->col( 2 );
		echo __( 'Amount', 'easy-digital-downloads' );
		$this->col();
		foreach ( $data as $item ) {
			echo isset( $item['revoked']['amount'] ) ? $item['revoked']['amount'] : 0;
			$this->col();
		}

		/**
		 * Abandoned.
		 *
		 * Displays Abandoned Count and Amount.
		 */
		$this->row();
		$this->col();
		echo __( 'Abandoned' , 'easy-digital-downloads' );
		$this->col();
		echo __( 'Count', 'easy-digital-downloads' );
		$this->col();
		foreach ( $data as $item ) {
			echo isset( $item['abandoned']['count'] ) ? $item['abandoned']['count'] : 0;
			$this->col();
		}
		$this->row();
		$this->col( 2 );
		echo __( 'Amount', 'easy-digital-downloads' );
		$this->col();
		foreach ( $data as $item ) {
			echo isset( $item['abandoned']['amount'] ) ? $item['abandoned']['amount'] : 0;
			$this->col();
		}

		/**
		 * Failed.
		 *
		 * Displays Failed Count and Amount.
		 */
		$this->row();
		$this->col();
		echo __( 'Failed' , 'easy-digital-downloads' );
		$this->col();
		echo __( 'Count', 'easy-digital-downloads' );
		$this->col();
		foreach ( $data as $item ) {
			echo isset( $item['failed']['count'] ) ? $item['failed']['count'] : 0;
			$this->col();
		}
		$this->row();
		$this->col( 2 );
		echo __( 'Amount', 'easy-digital-downloads' );
		$this->col();
		foreach ( $data as $item ) {
			echo isset( $item['failed']['amount'] ) ? $item['failed']['amount'] : 0;
			$this->col();
		}

		$this->row();

		/**
		 * Net Activity.
		 */
		$this->row();
		echo __( 'Net Activity', 'easy-digital-downloads' );
		$this->col( 3 );
		foreach ( $data as $item ) {
			echo isset( $item['publish']['amount'] ) ? $item['publish']['amount'] : 0;
			$this->col();
		}
	}

	/**
	 * Helper method to move to the next column.
	 *
	 * @since 2.7
	 * @access private
	 *
	 * @param int $number Number of columns to move by.
	 * @return void
	 */
	private function col( $number = 1 ) {
		echo str_repeat( ',', $number );
	}

	/**
	 * Helper method to move to the next row.
	 *
	 * @since 2.7
	 * @access private
	 *
	 * @param int $number Number of rows to move by.
	 * @return void
	 */
	private function row( $number = 1 ) {
		echo str_repeat( "\r\n", $number );
	}

	/**
	 * Perform the export.
	 *
	 * @since 2.7
	 * @access public
	 *
	 * @uses EDD_Export::can_export()
	 * @uses EDD_Export::headers()
	 * @uses EDD_Export::csv_cols_out()
	 * @uses EDD_Export::csv_rows_out()
	 *
	 * @return void
	 */
	public function export() {
		if ( ! $this->can_export() )
			wp_die( __( 'You do not have permission to export data.', 'easy-digital-downloads' ), __( 'Error', 'easy-digital-downloads' ), array( 'response' => 403 ) );

		// Set headers
		$this->headers();

		$this->csv_rows_out();

		edd_die();
	}

	/**
	 * Get the Export Data.
	 *
	 * @since 2.7
	 * @access public
	 *
	 * @return array $data The data for the CSV file
	 */
	public function get_data() {
		global $wpdb;

		$data = array();

		$start_year  = isset( $_POST['start_year'] )   ? absint( $_POST['start_year'] )   : date( 'Y' );
		$end_year    = isset( $_POST['end_year'] )     ? absint( $_POST['end_year'] )     : date( 'Y' );
		$start_month = isset( $_POST['start_month'] )  ? absint( $_POST['start_month'] )  : date( 'm' );
		$end_month   = isset( $_POST['end_month'] )    ? absint( $_POST['end_month'] )    : date( 'm' );

		$start_date = date( 'Y-m-d', strtotime( $start_year . '-' . $start_month . '-01' ) );
		$end_date = date( 'Y-m-d', strtotime( $end_year . '-' . $end_month . '-01' ) );

		$totals = $wpdb->get_results( $wpdb->prepare(
			"SELECT SUM(meta_value) AS total, DATE_FORMAT(posts.post_date, '%%m') AS m, YEAR(posts.post_date) AS y, COUNT(DISTINCT posts.ID) AS count, posts.post_status AS status
			 FROM {$wpdb->posts} AS posts
			 INNER JOIN {$wpdb->postmeta} ON posts.ID = {$wpdb->postmeta}.post_ID
			 WHERE posts.post_type IN ('edd_payment')
			 AND {$wpdb->postmeta}.meta_key = '_edd_payment_total'
			 AND posts.post_date >= %s
			 AND posts.post_date < %s
			 GROUP BY YEAR(posts.post_date), MONTH(posts.post_date), posts.post_status
			 ORDER by posts.post_date ASC", $start_date, date( 'Y-m-d', strtotime( '+1 month', strtotime( $end_date ) ) ) ), ARRAY_A );

		foreach ( $totals as $total ) {
			$key = (int) $total['y'] . $total['m'];

			$data[ $key ][ $total['status'] ] = array(
				'count' => $total['count'],
				'amount' => $total['total']
			);
		}

		while ( strtotime( $start_date ) <= strtotime( $end_date ) ) {
			$year = date( 'Y', strtotime( $start_date ) );
			$month = date( 'm', strtotime( $start_date ) );

			$key = $year . $month;

			if ( ! isset( $data[ $key ] ) ) {
				$data[ $key ] = array(
					'publish' => array(
						'count' => 0,
						'amount' => 0
					),
					'refunded' => array(
						'count' => 0,
						'amount' => 0
					),
					'cancelled' => array(
						'count' => 0,
						'amount' => 0
					),
					'revoked' => array(
						'count' => 0,
						'amount' => 0
					),
				);
			}

			$start_date = date( 'Y-m-d', strtotime( '+1 month', strtotime( $start_date ) ) );
		}

		ksort( $data );

		$data = apply_filters( 'edd_export_get_data', $data );
		$data = apply_filters( 'edd_export_get_data_' . $this->export_type, $data );

		return $data;
	}
}