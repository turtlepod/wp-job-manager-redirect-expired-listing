<?php
/**
 * Plugin Name: WP Job Manager - Redirect Expired Listing
 * Plugin URI: http://astoundify.com/plugins/{SLUG}/
 * Description: Ability to select a page as redirect target for expired listing. This plugin requires PHP 5.3+
 * Version: 1.0.0
 * Author: David Chandra Purnama
 * Author URI: http://shellcreeper.com/
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: wp-job-manager-redirect-expired-listing
 * Domain Path: /languages/
**/
if ( ! defined( 'WPINC' ) ) { die; }

/* Load plugin in "plugins_loaded" hook */
add_action( 'plugins_loaded', 'wpjm_rxl_init' );

/**
 * Plugin Init
 * @since 0.1.0
 */
function wpjm_rxl_init(){

	/* Check if WPJM Active */
	if( class_exists( 'WP_Job_Manager' ) ){

		/* Add Settings
		------------------------------------------ */
		add_filter( 'job_manager_settings', function( $settings ){
			$settings['job_pages'][1][] = array(
				'name'      => 'wpjm_rxl_page_id',
				'std'       => '',
				'label'     => __( 'Expired Listing Redirect', 'wp-job-manager-redirect-expired-listing' ),
				'desc'      => __( 'If selected, all expired listing will be redirected to this page.', 'wp-job-manager-redirect-expired-listing' ),
				'type'      => 'page'
			);
			return $settings;
		} );

		/* Redirect It
		------------------------------------------ */
		add_action( 'template_redirect', function(){

			/* Only in singular job listing */
			if( is_singular( 'job_listing' ) ){

				/* Get target redirect page ID */
				$redirect_page_id = get_option( 'wpjm_rxl_page_id' );

				/* Only if redirect page is selected in admin */
				if( $redirect_page_id ){

					/* Get job status */
					$job_status = get_post_status( get_queried_object_id() );

					/* If it's expired, redirect it */
					if( 'expired' == $job_status ){
						wp_redirect( esc_url_raw( get_permalink( $redirect_page_id ) ) );
						exit;
					}
				}
			}
		} );
	}
}

