<?php

namespace MySchedulr\Managers;

use MySchedulr\Helpers\EnvironmentHelper;
use MySchedulr\Helpers\OptionsHelper;
use MySchedulr\Helpers\SsoHelper;
use Exception;
use stdClass;

final class AdminManager
{
    private $instance_name;
    private $instance_uuid;
    private $instance_handshake_token;
    public $instance_id;
    private $instance_url;
    private $instance_callback_url;
    public $dashboard_url;
    private const ADMIN_INIT_HOOK = 'admin_init';
    private const ADMIN_MENU_HOOK = 'admin_menu';
    private const ADMIN_ENQUEUE_SCRIPTS_HOOK = 'admin_enqueue_scripts';
    private const ADMIN_AJAX_NONCE = 'ajax-nonce';
    private const ADMIN_NONCE = 'nonce';
    private const ADMIN_MS4WP_DATA_VAR = 'ms4wp_data';
    private const ADMIN_APP_NAME = 'MySchedulr';

    public function __construct()
    {
        $this->instance_name = rawurlencode(get_bloginfo('name'));
        $this->instance_handshake_token = OptionsHelper::getHandshakeToken();
        $this->instance_uuid = OptionsHelper::getInstanceUUID();
        $this->instance_id = OptionsHelper::getInstanceId();
        $this->instance_url = rawurlencode(get_bloginfo('wpurl'));
        $this->instance_callback_url = rawurlencode(get_bloginfo('wpurl') . '?rest_route=/myschedulr/v1/callback');
        $this->dashboard_url = EnvironmentHelper::getAppUrl() . 'bookings/dashboard?wp_site_name=' . $this->instance_name
                               . '&wp_site_uuid=' . $this->instance_uuid
                               . '&wp_callback_url=' . $this->instance_callback_url
                               . '&wp_instance_url=' . $this->instance_url
                               . '&wp_version=' . get_bloginfo('version')
                               . '&plugin_version=' . MS4WP_PLUGIN_VERSION;
    }

    public function addHooks()
    {
        add_action(self::ADMIN_MENU_HOOK, [$this, 'ms4wpBuildMenu' ]);
        add_action(self::ADMIN_ENQUEUE_SCRIPTS_HOOK, [$this, 'ms4wpAddAssets' ]);
        add_action(self::ADMIN_INIT_HOOK, [$this, 'ms4wpActivationRedirect' ]);
        add_action(self::ADMIN_INIT_HOOK, [$this, 'ms4wpIgnoreReviewNotice' ]);
        add_filter('admin_footer_text', [$this, 'ms4wpAdminFooterText' ], 1);
        add_action('wp_ajax_woocommerce_ms4wp_rated', [$this, 'ms4wpMarkAsRated' ]);
        add_action('wp_ajax_ms4wp_request_sso', [$this, 'ms4wpRequestSingleSignOnUrl' ]);
        add_action(self::ADMIN_ENQUEUE_SCRIPTS_HOOK, [$this, 'ms4wpDeactivationModalJS' ], 20);
        add_action(self::ADMIN_ENQUEUE_SCRIPTS_HOOK, [$this, 'ms4wpDeactivationModalCSS' ]);
        add_action('admin_footer', [$this, 'ms4wpShowDeactivationModal' ]);
        add_action('wp_ajax_ms4wp_deactivate_survey', [$this, 'ms4wpDeactivateSurveyPost' ]);
    }

    private function checkNonce()
    {
        $nonce = sanitize_text_field($_POST[self::ADMIN_NONCE]);

        if (!wp_verify_nonce($nonce,self::ADMIN_AJAX_NONCE))
        {
            $response = new stdClass();
            $response->url = admin_url('admin.php?page=myschedulr');

            wp_send_json_success($response);
        }
    }

    private function createNonce()
    {
        return wp_create_nonce(self::ADMIN_AJAX_NONCE);
    }

    private function requestSingleSignOnUrlInternal($linkReference = null, $linkParameters = null)
    {
        $sso = $this->getSsoLink($linkReference, $linkParameters);

        if (is_null($sso)) {
            $current_user = wp_get_current_user();
            $redirectUrl = EnvironmentHelper::get_app_gateway_url('wordpress/v1.0/instances/open?clearSession=true&redirectUrl=');
            $onboardingUrl = EnvironmentHelper::getAppUrl() . 'bookings-scheduler/wp-signup?wp_site_name=' . $this->instance_name
                . '&wp_site_uuid=' . $this->instance_uuid
                . '&wp_handshake=' . $this->instance_handshake_token
                . '&wp_callback_url=' . $this->instance_callback_url
                . '&wp_instance_url=' . $this->instance_url
                . '&wp_version=' . get_bloginfo('version')
                . '&plugin_version=' . MS4WP_PLUGIN_VERSION
                . '&first_name=' . urlencode($current_user->user_firstname)
                . '&last_name=' . urlencode($current_user->user_lastname)
                . '&email=' . urlencode($current_user->user_email);
            $referred_by = OptionsHelper::get_referred_by();

            if (isset($referred_by)) {
                $utm_campaign = '';

                if (is_array($referred_by)
                    && array_key_exists('plugin', $referred_by)
                    && array_key_exists('source', $referred_by)) {
                    $utm_campaign = $referred_by['plugin'] . $referred_by['source'];
                } else if (is_string($referred_by)) {
                    $utm_campaign = str_replace(';', '|', $referred_by);
                }

                $onboardingUrl .= '&utm_source=wordpress&utm_medium=plugin&utm_campaign=' . $utm_campaign;
            }

            return $redirectUrl . rawurlencode($onboardingUrl);
        }

        return $sso;
    }

    public function ms4wpRequestSingleSignOnUrl()
    {
        $this->checkNonce();

        $linkReference = array_key_exists('link_reference', $_POST) ? sanitize_key($_POST['link_reference']) : null;
        $linkParameters = null;

        if (array_key_exists('link_parameters', $_POST)) {
            foreach ($_POST['link_parameters'] as $key => $value) {
                $linkParameters[$key] = sanitize_text_field($value);
            }
        }

        $response = new stdClass();
        $response->url = $this->requestSingleSignOnUrlInternal($linkReference, $linkParameters);

        wp_send_json_success($response);
    }

    public function ms4wpDeactivateSurveyPost()
    {
        $this->checkNonce();

        $instance_id = OptionsHelper::getInstanceId();
        $instance_api_key = OptionsHelper::getInstanceApiKey();
        $connected_account_id = OptionsHelper::getConnectedAccountId();

        parse_str($_POST['data'], $post_data);

        $survey_value = $post_data['ms4wp_deactivation_option'];

        if (is_null($survey_value)) {
            wp_send_json_success();
        }

        $arguments = [
            'method'  => 'POST',
            'headers' => [
                'x-api-key'    => $instance_api_key,
                'x-account-id' => $connected_account_id,
                'content-type' => 'application/json'
            ],
            'body' => wp_json_encode(
                [
                    'instance_id' => $instance_id,
                    'survey_id'   => 1,
                    'value'       => $survey_value,
                    'message'     => $post_data['other']
                ]
            )
        ];

        wp_remote_post(EnvironmentHelper::get_app_gateway_url() . 'wordpress/v1.0/survey', $arguments);
        wp_send_json_success();
    }

    private function shouldShowDeactivationModal()
    {
        if (!function_exists('get_current_screen')) {
            return false;
        }

        $screen = get_current_screen();

        if (is_null($screen)) {
            return false;
        }

        return (in_array($screen->id, ['plugins', 'plugins-network'], true));
    }

    public function ms4wpDeactivationModalJS()
    {
        if (!$this->shouldShowDeactivationModal()) {
            return;
        }

        wp_enqueue_script(
            'ms4wp_deactivate_survey',
            MS4WP_PLUGIN_URL . 'assets/js/deactivation.js',
            null,
            null,
            true
        );
        wp_localize_script('ms4wp_deactivate_survey', self::ADMIN_MS4WP_DATA_VAR, [
            'url'   => admin_url('admin-ajax.php'),
            'nonce' => $this->createNonce()
        ]);
    }

    public function ms4wpDeactivationModalCSS()
    {
        if (!$this->shouldShowDeactivationModal()) {
            return;
        }

        wp_enqueue_style(
            'ms4wp_deactivate_survey',
            MS4WP_PLUGIN_URL . 'assets/css/deactivation.css',
            null,
            null,
            null
        );
    }

    public function ms4wpShowDeactivationModal()
    {
        if (!$this->shouldShowDeactivationModal()) {
            return;
        }
        include MS4WP_PLUGIN_DIR . 'src/views/partials/deactivation-form.php';
    }

    public function ms4wpIgnoreReviewNotice()
    {
        if (isset($_GET['ms4wp-ignore-notice']) && '0' === $_GET['ms4wp-ignore-notice'] ) {
            update_option('ms4wp_ignore_review_notice', 'true');
        }
    }

    public function ms4wpMarkAsRated()
    {
        update_option('ms4wp_admin_footer_text_rated', 1);
        wp_send_json_success();
    }

    public function ms4wpAdminFooterText($footer_text)
    {
        if ($this->isCmScreenAndShowFooter()) {
            $footer_text = sprintf(
                esc_html__('If you like %1$s please leave us a %2$s rating. A huge thanks in advance!', 'myschedulr' ),
                sprintf('<strong>%s</strong>', esc_html__(self::ADMIN_APP_NAME, 'myschedulr' )),
                '<a href="https://wordpress.org/plugins/myschedulr/#reviews?rate=5#new-post" target="_blank" class="ms4wp-rating-link" data-rated="' . esc_attr__('Thank You', 'myschedulr' ) . '">&#9733;&#9733;&#9733;&#9733;&#9733;</a>'
            );
        }

        return $footer_text;
    }

    private function isCmScreenAndShowFooter()
    {
        $screen = get_current_screen();

        if (! empty($screen)
            && ('toplevel_page_myschedulr' === $screen->id ||
                'myschedulr_page_myschedulr_settings' === $screen->id ||
                'myschedulr_page_myschedulr_setup' === $screen->id)
            && ! get_option('ms4wp_admin_footer_text_rated')
        ) {
            return true;
        }

        return false;
    }

    public function ms4wpActivationRedirect()
    {
        if (intval(get_option('ms4wp_activation_redirect', false)) === wp_get_current_user()->ID) {
            delete_option('ms4wp_activation_redirect');

            if ((defined( 'REST_REQUEST' ) && REST_REQUEST)) {
                return;
            }

            $onboarding_profile = get_option('woocommerce_onboarding_profile');

            if (is_array($onboarding_profile) && array_key_exists('business_extensions', $onboarding_profile)) {
                if (is_array($onboarding_profile['business_extensions'])
                    && in_array('myschedulr', $onboarding_profile['business_extensions'])) {
                    return;
                }
            }

            if (isset($_GET['activate-multi']) || is_network_admin()) {
                return;
            }

            wp_safe_redirect(admin_url('admin.php?page=myschedulr'));
            exit;
        }
    }

    public function ms4wpAddAssets()
    {
        wp_register_style(
            'ms4wp_admin',
            MS4WP_PLUGIN_URL . 'assets/css/admin.css',
            null,
            MS4WP_PLUGIN_VERSION
        );
        wp_enqueue_style('ms4wp_admin');
        wp_enqueue_style(
            'ms4wp-font-poppins',
            'https://fonts.googleapis.com/css?family=Poppins:400,500'
        );
        wp_enqueue_script('wp-api');

        $this->enqueueDashboardJS();

        if ($this->isCmScreenAndShowFooter()) {
            wp_enqueue_script(
                'ms4wp_admin_footer_rating',
                MS4WP_PLUGIN_URL . 'assets/js/footer_rating.js',
                'wp-api',
                MS4WP_PLUGIN_VERSION,
                true
            );
        }
    }

    public function ms4wpBuildMenu()
    {
        $hasConnectedAccount = OptionsHelper::getInstanceId() !== null;
        $main_action = $hasConnectedAccount ? [$this, 'showDashboard'] : [$this, 'showSetup'];
        $icon = file_get_contents(MS4WP_PLUGIN_DIR . 'assets/images/icon.svg');
        $position = apply_filters( 'ms4wp_menu_position', '35.5' );

        add_menu_page(
            self::ADMIN_APP_NAME,
            esc_html__(self::ADMIN_APP_NAME, 'myschedulr' ),
            'manage_options',
            'myschedulr',
            $main_action,
            'data:image/svg+xml;base64,' . base64_encode($icon),
            $position
        );

        $sub_actions = [];

        if ($hasConnectedAccount) {
            $sub_actions[] = [
                'title' => esc_html__('Set-Up', 'myschedulr'),
                'text' => __('Set-Up', 'myschedulr'),
                'slug' => 'myschedulr_setup',
                'callback' => [$this, 'showSetupPage']
            ];
        }

        $sub_actions[] = [
            'title' => esc_html__('Settings', 'myschedulr'),
            'text' => __('Settings', 'myschedulr'),
            'slug' => 'myschedulr_settings',
            'callback' => [$this, 'showSettingsPage']
        ];

        foreach ($sub_actions as $sub_action) {
            add_submenu_page(
                'myschedulr',
                'MySchedulr - ' . $sub_action['title'],
                $sub_action['text'],
                'manage_options',
                $sub_action['slug'],
                $sub_action['callback']
            );
        }
    }

    public function showSetup()
    {
        include MS4WP_PLUGIN_DIR . 'src/views/onboarding.php';
    }

    public function showDashboard()
    {
        include MS4WP_PLUGIN_DIR . 'src/views/dashboard.php';
    }

    private function enqueueDashboardJS() {
        wp_enqueue_script(
            'ms4wp_dashboard',
            MS4WP_PLUGIN_URL . 'assets/js/dashboard.js',
            'jquery',
            MS4WP_PLUGIN_VERSION
        );
        wp_localize_script('ms4wp_dashboard', self::ADMIN_MS4WP_DATA_VAR, [
            'url'   => admin_url('admin-ajax.php'),
            'nonce' => $this->createNonce()
        ]);
    }

    private function getSsoLink(string $linkReference = null, array $linkParameters = null)
    {
        if(!current_user_can('administrator')) {
            return null;
        }

        $instance_id = OptionsHelper::getInstanceId();
        $instance_api_key = OptionsHelper::getInstanceApiKey();
        $connected_account_id = OptionsHelper::getConnectedAccountId();

        if (isset($instance_id) && isset($instance_api_key) && isset($connected_account_id)) {
            try {
                return SsoHelper::generateSsoLink(
                    $instance_id,
                    $instance_api_key,
                    $connected_account_id,
                    $linkReference,
                    $linkParameters
                );
            } catch(Exception $ex) {
                RaygunManager::getInstance()->exceptionHandler($ex);
            }
        }

        return null;
    }

    public function showSettingsPage()
    {
        include MS4WP_PLUGIN_DIR . 'src/views/settings.php';
    }
    public function showSetupPage()
    {
        include MS4WP_PLUGIN_DIR . 'src/views/setup.php';
    }
}
