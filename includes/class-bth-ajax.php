<?php
/**
 * AJAX Handlers
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class BTH_Ajax {

    /**
     * Currency conversion via AJAX.
     */
    public static function currency_convert() {
        check_ajax_referer( 'bth_nonce', 'nonce' );

        $from   = sanitize_text_field( $_POST['from'] ?? 'USD' );
        $to     = sanitize_text_field( $_POST['to'] ?? 'BDT' );
        $amount = floatval( $_POST['amount'] ?? 1 );

        $cache_key = 'bth_currency_' . strtolower( $from . '_' . $to );
        $cached    = get_transient( $cache_key );

        if ( $cached !== false ) {
            $rate = $cached;
        } else {
            $api_key = get_option( 'bth_currency_api_key', '' );

            if ( $api_key ) {
                $url = "https://v6.exchangerate-api.com/v6/{$api_key}/pair/{$from}/{$to}";
            } else {
                // Free tier — no API key needed
                $url = "https://open.er-api.com/v6/latest/{$from}";
            }

            $response = wp_remote_get( $url, array( 'timeout' => 10 ) );

            if ( is_wp_error( $response ) ) {
                wp_send_json_error( array( 'message' => __( 'Unable to fetch exchange rates. Please try again.', 'ald-business-tools' ) ) );
            }

            $body = json_decode( wp_remote_retrieve_body( $response ), true );

            if ( ! $body || ( isset( $body['result'] ) && $body['result'] !== 'success' ) ) {
                wp_send_json_error( array( 'message' => __( 'Invalid response from exchange rate service.', 'ald-business-tools' ) ) );
            }

            if ( $api_key ) {
                $rate = floatval( $body['conversion_rate'] ?? 0 );
            } else {
                $rate = floatval( $body['rates'][ $to ] ?? 0 );
            }

            if ( $rate <= 0 ) {
                wp_send_json_error( array( 'message' => __( 'Exchange rate not available for selected currencies.', 'ald-business-tools' ) ) );
            }

            // Cache for 1 hour
            set_transient( $cache_key, $rate, HOUR_IN_SECONDS );
        }

        $converted = round( $amount * $rate, 2 );

        wp_send_json_success( array(
            'from'      => $from,
            'to'        => $to,
            'amount'    => $amount,
            'rate'      => $rate,
            'converted' => $converted,
        ) );
    }
}
