<?php


namespace MySchedulr\Helpers;

use MySchedulr\Constants\EnvironmentNames;

final class EnvironmentHelper
{
    public static function isTestEnvironment(): bool
    {
        return self::get_environment() !== EnvironmentNames::PRODUCTION;
    }

    public static function get_environment(): string
    {
        $environment = MS4WP_ENVIRONMENT;

        if ($environment === '{ENV}') {
            return EnvironmentNames::DEVELOPMENT;
        }

        return $environment;
    }

    public static function get_app_gateway_url($app_gateway_path = null): string
    {
        $url = MS4WP_APP_GATEWAY_URL;

        if ($url === '{GATEWAY_URL}') {
            $url = 'https://app-gateway.latest.schedulescout.com/';
        }

        if (is_null($app_gateway_path)) {
            return $url;
        }

        if (!empty($app_gateway_path)) {
            return $url . $app_gateway_path;
        }

        return $url;
    }

    public static function getAppUrl(): string
    {
        $url = MS4WP_APP_URL;

        if ($url === '{APP_URL}') {
            return 'https://app-gateway.latest.schedulescout.com/';
        }

        return $url;
    }
}
