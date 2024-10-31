<?php
/**
 * MySchedulr by Newfold
 *
 * @package MySchedulr
*/
/**
 * Plugin Name: MySchedulr
 * Plugin URI: https://wordpress.org/plugins/myschedulr/
 * Description: Free Booking service designed specifically for WordPress.
 * Author: Newfold
 * Version: 1.0.0
 * Author URI: https://www.newfold.com
 * Requires at least: 4.7
 * Requires PHP: 7.1
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/
use MySchedulr\MySchedulr;
use MySchedulr\Blocks\LoadBlock;

function ms4wp_myschdulr_load_plugin() {
    global $myschedulr;

    if($myschedulr!= null) {
        return true;
    }

    define('MS4WP_PLUGIN_DIR', __DIR__ . '/');
    define('MS4WP_PLUGIN_URL', plugin_dir_url(__FILE__) . '/');
    define('MS4WP_PLUGIN_FILE', __FILE__);
    define('MS4WP_PLUGIN_VERSION', '1.0.0');
    define('MS4WP_INSTANCE_UUID_KEY', 'ms4wp_instance_uuid');
    define('MS4WP_INSTANCE_HANDSHAKE_TOKEN', 'ms4wp_handshake_token');
    define('MS4WP_INSTANCE_HANDSHAKE_EXPIRATION', 'ms4wp_handshake_expiration');
    define('MS4WP_INSTANCE_ID_KEY', 'ms4wp_instance_id');
    define('MS4WP_INSTANCE_API_KEY_KEY', 'ms4wp_instance_api_key');
    define('MS4WP_ENCRYPTION_KEY_KEY', 'ms4wp_encryption_key');
    define('MS4WP_CONNECTED_ACCOUNT_ID', 'ms4wp_connected_account_id');
    define('MS4WP_ACTIVATED_PLUGINS', 'ms4wp_activated_plugins');
    define('MS4WP_MANAGED_EMAIL_NOTIFICATIONS', 'ms4wp_managed_email_notifications');
    define('MS4WP_ACCEPTED_CONSENT', 'ms4wp_accepted_consent');
    define('MS4WP_SYNCHRONIZE_ACTION', 'ms4wp_synchronize_contacts');
    define('MS4WP_CHECKOUT_CHECKBOX_TEXT', 'ms4wp_checkout_checkbox_text');
    define('MS4WP_CHECKOUT_CHECKBOX_ENABLED', 'ms4wp_checkout_checkbox_enabled');
    define('MS4WP_APP_GATEWAY_URL', 'https://app-gateway.myschedulr.com/');
    define('MS4WP_APP_URL', 'https://app.myschedulr.com/');
    define('MS4WP_ENVIRONMENT', 'PRODUCTION');
    define('MS4WP_BUILD_NUMBER', '366');
    define('MS4WP_RAYGUN_PHP_KEY', '');
    define('MS4WP_BATCH_SIZE', 500);
    define('MS4WP_WC_API_KEY_ID', 'ms4wp_woocommerce_api_key_id');
    define('MS4WP_WC_API_CONSUMER_KEY', 'ms4wp_woocommerce_consumer_key');
    define('MS4WP_REFERRED_BY', 'ms4wp_referred_by');
    define('MS4WP_HIDE_BANNER', 'ms4wp_hide_banner');
    define('MS4WP_PUBLIC_API_URL', MS4WP_APP_GATEWAY_URL . 'booking/public/v1.0');

    // Load all the required files
    if (file_exists(__DIR__ . '/vendor/autoload.php')) {
        include_once __DIR__ . '/vendor/autoload.php';
    }

    $myschedulr = MySchedulr::get_instance();
    $myschedulr->add_hooks();

    if (version_compare($GLOBALS['wp_version'], '5.5', '>=')) {
        $loadBlock = LoadBlock::getInstance();
        $loadBlock->addHooks();
    }

    return true;
}

function ms4wp_activate_action() {
	add_option('ms4wp_activated', true);
	add_option('ms4wp_install_date', date('Y-m-d G:i:s'), '', 'yes');
	if (( isset($_REQUEST['action']) && 'activate-selected' === $_REQUEST['action'] )
	    && ( isset($_POST['checked']) && count($_POST['checked']) > 1 )
	) {
		return;
	}
	add_option('ms4wp_activation_redirect', wp_get_current_user()->ID);
}

function ms4wp_deactivate_action() {
    delete_option('ms4wp_activated');
    delete_option('ms4wp_install_date');
}

add_action('plugins_loaded', 'ms4wp_myschdulr_load_plugin', 10);
register_activation_hook(__FILE__, 'ms4wp_activate_action' );
register_deactivation_hook(__FILE__, 'ms4wp_deactivate_action' );

// Add on submit to subscribe buttons
add_action('init', 'ms4wp_add_frontend_on_submit' );
function ms4wp_add_frontend_on_submit() {
    wp_localize_script(
        'ms4wp_form_submit',
        'ms4wp_form_submit_data',
        [
            'siteUrl' => get_site_url(),
            'url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce( 'ms4wp_form_submission'),
            'listNonce' => wp_create_nonce( 'ms4wp_get_lists' ),
            'activatedNonce' => wp_create_nonce(  'ms4wp_get_myschedulr_activated' )
        ]
    );
}
