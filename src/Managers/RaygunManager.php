<?php


namespace MySchedulr\Managers;

use Exception;
use Raygun4php\RaygunClient;

final class RaygunManager
{
    private static $instance;
    private $raygunClient;

    public function __construct()
    {
        $this->raygunClient = new RaygunClient(MS4WP_RAYGUN_PHP_KEY);
    }

    public function exceptionHandler(Exception $exception): void
    {
        $this->raygunClient->SendException($exception, self::buildTags(), self::buildCustomUserData());
    }

    private function buildTags(): array
    {
        $tags = [];

        try {
            $tags['MS4WP_PLUGIN_VERSION'] = MS4WP_PLUGIN_VERSION;
            $tags['MS4WP_ENVIRONMENT'] = MS4WP_ENVIRONMENT;
            $tags['MS4WP_BUILD'] = MS4WP_BUILD_NUMBER;
        } catch (Exception $e) {
            // do nothing, otherwise we might have an endless loop
        }

        return $tags;
    }

    private function buildCustomUserData(): array
    {
        $userData = [];

        try {
            $userData['MS4WP_APP_URL'] = MS4WP_APP_URL;
            $userData['MS4WP_APP_GATEWAY_URL'] = MS4WP_APP_GATEWAY_URL;
            $userData['MS4WP_CONNECTED_ACCOUNT_ID'] = get_option(MS4WP_CONNECTED_ACCOUNT_ID);
            $userData['MS4WP_INSTANCE_UUID_KEY'] = get_option(MS4WP_INSTANCE_UUID_KEY);
            $userData['MS4WP_MANAGED_EMAIL_NOTIFICATIONS'] = get_option(MS4WP_MANAGED_EMAIL_NOTIFICATIONS);
            $userData['MS4WP_ACTIVATED_PLUGINS'] = get_option(MS4WP_ACTIVATED_PLUGINS);

        } catch (Exception $e) {
            // do nothing, otherwise we might have an endless loop
        }

        return $userData;
    }

    public static function getInstance(): RaygunManager
    {
        if (self::$instance === null) {
            self::$instance = new RaygunManager();
        }

        return self::$instance;
    }
}
