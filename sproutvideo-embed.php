<?php
/**
 * Plugin Name:     SproutVideo Embed
 * Plugin URI:      PLUGIN SITE HERE
 * Description:     Provides a shortcode for embedding videos hosted on SproutVideo.
 * Author:          TheWebist
 * Author URI:      https://mwender.com
 * Text Domain:     sproutvideo-embed
 * Domain Path:     /languages
 * Version:         1.0.1
 *
 * @package         Sproutvideo_Embed
 */

/**
 * Embeds a SproutVideo
 *
 * @param      array   $atts {
 *   @type  string  $url  The URL for the SproutVideo.
 * }
 *
 * @return     string  HTML embed code for the SproutVideo.
 */
function embed_sprout_video( $atts = [] ){
  $args = shortcode_atts([
    'url' => null,
  ], $atts, $shortcode = '');

  $keys = [ 'vemail', 'vfname', 'vlname' ];
  $query_vars = [];
  foreach( $keys as $key ){
    if( isset( $_GET[ $key ] ) && ! empty( $_GET[ $key ] ) ){
      $value = $_GET[ $key ];
      switch ( $key ) {
        case 'vemail':
          if( ! is_email( $value ) )
            continue 2;
          break;

        default:
          $value = sanitize_text_field( $value );
          break;
      }
      $query_vars[ $key ] = $value;
    }
  }
  $query_vars = urlencode_deep( $query_vars );

  // Add any Sprout query variables
  $url = $args['url'];
  if( 0 < count( $query_vars ) )
    $url = add_query_arg( $query_vars, $url );

  $embed = "<div style=\"position:relative;height:0;padding-bottom:56.25%\"><iframe class='sproutvideo-player' src='$url' style='position:absolute;width:100%;height:100%;left:0;top:0' frameborder='0' allowfullscreen referrerpolicy=\'no-referrer-when-downgrade\'></iframe></div>";

  return $embed;
}
add_shortcode( 'sproutvideo', 'embed_sprout_video' );

/**
 * Enhanced logging.
 *
 * @param      string  $message  The log message
 */
if( ! function_exists( 'uber_log' ) ){
  function uber_log( $message = null ){
    static $counter = 1;

    $bt = debug_backtrace();
    $caller = array_shift( $bt );

    if( 1 == $counter )
      error_log( "\n\n" . str_repeat('-', 25 ) . ' STARTING DEBUG [' . date('h:i:sa', current_time('timestamp') ) . '] ' . str_repeat('-', 25 ) . "\n\n" );
    error_log( "\n" . $counter . '. ' . basename( $caller['file'] ) . '::' . $caller['line'] . "\n" . $message . "\n---\n" );
    $counter++;
  }
}