/**
 * Footer rating javascript.
 *
 * @package ScheduleScout
 */
jQuery( 'a.ms4wp-rating-link' ).click( function() {
    jQuery.post( 'admin-ajax.php', { action: 'woocommerce_ms4wp_rated' } );
    jQuery( this ).parent().text( jQuery( this ).data( 'rated' ) );
});