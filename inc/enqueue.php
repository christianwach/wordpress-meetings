<?php
/**
 * ANP Meetings Enqueue
 *
 * @author    Pea, Glocal
 * @license   GPL-2.0+
 * @link      http://glocal.coop
 * @since     1.1.0
 * @package   ANP_Meetings
 */

function anp_meetings_enqueue_scripts() {
  $hide_css = anp_meetings_get_option( 'anp_meetings_css', false );
  if( ! $hide_css ) {
    wp_enqueue_style( 'anp-meetings', ANP_MEETINGS_PLUGIN_URL . 'assets/css/style.min.css', array( 'dashicons' ) );
  }
}
add_action( 'wp_enqueue_scripts', 'anp_meetings_enqueue_scripts' );