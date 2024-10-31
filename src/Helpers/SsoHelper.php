<?php


namespace MySchedulr\Helpers;

use Exception;

final class SsoHelper
{
    /**
     * Generates and return a single sign-on link
     *
     * @throws Exception
     */
    public static function generateSsoLink($instanceId, $apiKey, $connectedAccountId, $linkReference = null, $linkParameters = null)
    {

        if (!isset($instanceId)) {
            throw new Exception('Please provide a valid siteId');
        }

        if (!isset($apiKey)) {
            throw new Exception('Please provide a valid apiKey');
        }
        if (!isset($connectedAccountId)) {
            throw new Exception('Please provide a valid connectedAccountId');
        }

        $arguments = [
            'method'  => 'POST',
            'headers' => [
                'x-api-key'    => $apiKey,
                'x-account-id' => $connectedAccountId,
                'content-type' => 'application/json'
            ],
            'body' => wp_json_encode(
                [
                    'instance_url'       => get_bloginfo('wpurl'),
                    'plugin_version'     => MS4WP_PLUGIN_VERSION,
                    'word_press_version' => get_bloginfo('version'),
                    'link_reference'     => $linkReference,
                    'link_parameters'    => $linkParameters
                ]
            )
        ];

        $response = wp_remote_post(
            EnvironmentHelper::get_app_gateway_url() . 'wordpress/v1.0/account/sso',
            $arguments
        );

        if (is_wp_error($response)) {
            return null;
        }

        $properties = json_decode($response['body'], true);

        if ($properties === null) {
            return null;
        }

        if(array_key_exists('login_url', $properties)) {
            return $properties['login_url'];
        }

        return null;
    }
}
