<?php

namespace MySchedulr\Managers;

use MySchedulr\MySchedulr;
use MySchedulr\Helpers\OptionsHelper;
use WP_Error;

final class ApiManager
{
    private const API_NAMESPACE = "myschedulr/v1";
    private const ROUTE_METHODS = 'methods';
    private const ROUTE_PATH = 'path';
    private const ROUTE_CALLBACK = 'callback';
    private const ROUTE_PERMISSION_CALLBACK = 'permission_callback';
    private const ROUTE_REQUIRES_WP_ADMIN = [
        '/sso'
    ];
    private const HTTP_STATUS = 'status';

    public function addHooks()
    {
        add_action('rest_api_init', [$this, 'ms4wpAddRestEndpoints' ]);
    }

    private function validateWPAdmin()
    {
        if(!current_user_can('administrator')) {
            return new WP_Error(
                'rest_forbidden',
                __('Sorry, you are not allowed to do that.', 'myschedulr'),
                [self::HTTP_STATUS => 401]
            );
        }

        return true;
    }

    public function validateApiKey()
    {
        nocache_headers();

        if (!array_key_exists('HTTP_X_API_KEY', $_SERVER)) {
            return new WP_Error(
                'rest_forbidden',
                __('Sorry, you are not allowed to do that.','myschedulr'),
                [self::HTTP_STATUS => 401]
            );
        }

        $instanceApiKey = OptionsHelper::getInstanceApiKey();
        $apiKey = $_SERVER['HTTP_X_API_KEY'];

        if ($apiKey === $instanceApiKey ) {
            return true;
        }

        return new WP_Error(
            'rest_forbidden',
            __('Sorry, you are not allowed to do that.','myschedulr'),
            [self::HTTP_STATUS => 401]
        );
    }

    public function validateCallback()
    {
        nocache_headers();

        if (!array_key_exists('HTTP_X_API_KEY', $_SERVER)) {
            return new WP_Error(
                'rest_forbidden',
                __('Sorry, you are not allowed to do that.','myschedulr'),
                [self::HTTP_STATUS => 401]
            );
        }

        $apiKey = $_SERVER['HTTP_X_API_KEY'];
        $expiration = OptionsHelper::getHandshakeExpiration();

        if ($expiration === null || $expiration < time()) {
            return new WP_Error('rest_unauthorized', 'Unauthorized', [self::HTTP_STATUS => 401]);
        }

        if ($apiKey === OptionsHelper::getHandshakeToken() ) {
            return true;
        }

        return new WP_Error('rest_unauthorized', 'Unauthorized', [self::HTTP_STATUS => 401]);
    }

    public function ms4wpAddRestEndpoints()
    {
        $routes = [
            [
                self::ROUTE_PATH                => '/callback',
                self::ROUTE_METHODS             => 'POST',
                self::ROUTE_CALLBACK            => [MySchedulr::get_instance()->get_instance_manager(), 'handle_callback'],
                self::ROUTE_PERMISSION_CALLBACK => function () {
                    return $this->validateCallback();
                }
            ]
        ];

        foreach ($routes as $route) {
            $this->registerRoute($route);
        }
    }

    private function registerRoute(array $route)
    {
        $ms4wp_route_path = $route[self::ROUTE_PATH];
        $methods = $route[self::ROUTE_METHODS];
        $callback = $route[self::ROUTE_CALLBACK];

        if (array_key_exists(self::ROUTE_PERMISSION_CALLBACK, $route)) {
            $permission_callback = $route[self::ROUTE_PERMISSION_CALLBACK];
        } else if(in_array($ms4wp_route_path, self::ROUTE_REQUIRES_WP_ADMIN)) {
            $permission_callback = [$this, 'validateWPAdmin'];
        } else {
            $permission_callback = [$this, 'validateApiKey'];
        }

        if (empty($ms4wp_route_path)) {
            return;
        }

        if(empty($methods)) {
            $methods = 'GET';
        }

        $arguments = [
            self::ROUTE_METHODS             => $methods,
            self::ROUTE_CALLBACK            => $callback,
            self::ROUTE_PERMISSION_CALLBACK => $permission_callback
        ];

        register_rest_route(self::API_NAMESPACE, $ms4wp_route_path, $arguments);
    }
}
