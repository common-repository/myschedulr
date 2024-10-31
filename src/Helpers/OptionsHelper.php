<?php

namespace MySchedulr\Helpers;

use stdClass;

final class OptionsHelper
{
    public static function getInstanceUUID(): string
    {
        $instanceUuid = get_option(MS4WP_INSTANCE_UUID_KEY, null);

        if ($instanceUuid === null) {
            $instanceUuid = uniqid();

            add_option(MS4WP_INSTANCE_UUID_KEY, $instanceUuid);
        }

        return $instanceUuid;
    }

    public static function getHandshakeToken(): string
    {
        $token = get_option(MS4WP_INSTANCE_HANDSHAKE_TOKEN, null);
        $expiration = self::getHandshakeExpiration();

        if ($token === null || $expiration === null || $expiration < time()) {
            $token = GuidHelper::generateGUID();

            update_option(MS4WP_INSTANCE_HANDSHAKE_TOKEN, $token);
            update_option(MS4WP_INSTANCE_HANDSHAKE_EXPIRATION, time() + 3600);
        }

        return $token;
    }

    public static function getHandshakeExpiration()
    {
        return get_option(MS4WP_INSTANCE_HANDSHAKE_EXPIRATION, null);
    }

    public static function getInstanceId()
    {
        return get_option(MS4WP_INSTANCE_ID_KEY, null);
    }

    public static function setInstanceId(int $value): void
    {
        add_option(MS4WP_INSTANCE_ID_KEY, $value);
    }

    public static function setCheckoutCheckboxText(string $value): void
    {
        update_option(MS4WP_CHECKOUT_CHECKBOX_TEXT, $value);
    }

    public static function setCheckoutCheckboxEnabled(bool $value): void
    {
        update_option(MS4WP_CHECKOUT_CHECKBOX_ENABLED, $value);
    }

    public static function getConnectedAccountId()
    {
        return get_option(MS4WP_CONNECTED_ACCOUNT_ID, null);
    }

    public static function setConnectedAccountId(int $value): void
    {
        add_option(MS4WP_CONNECTED_ACCOUNT_ID, $value);
    }

    public static function getInstanceApiKey()
    {
        return EncryptionHelper::getOption(MS4WP_INSTANCE_API_KEY_KEY, null);
    }

    /**
     * Adds an instance api key entry the wp_options table
     *
     * @throws \Defuse\Crypto\Exception\BadFormatException
     * @throws \Defuse\Crypto\Exception\EnvironmentIsBrokenException
     */
    public static function setInstanceApiKey(string $value): void
    {
        EncryptionHelper::addOption(MS4WP_INSTANCE_API_KEY_KEY, $value);
    }

    public static function getManagedEmailNotifications(): array
    {
        global $wpdb;

        $rows = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT option_name, option_value FROM $wpdb->options WHERE option_name like %s",
                MS4WP_MANAGED_EMAIL_NOTIFICATIONS . '%'
            )
        );
        $result = [];

        foreach ($rows as $row) {
            $option_name = $row->option_name;

            if ($option_name === MS4WP_MANAGED_EMAIL_NOTIFICATIONS ) {
                return self::convertManagedEmailNotifications($row->option_value);
            }

            $item = new stdClass();
            $item->name = str_replace(MS4WP_MANAGED_EMAIL_NOTIFICATIONS . '_', '', $option_name);
            $item->active = $row->option_value === 'true';
            $result[] = $item;
        }

        return $result;
    }

    private static function convertManagedEmailNotifications($items): array
    {
        $items = maybe_unserialize($items);

        if (empty($items)) {
            return [];
        }

        $result = [];

        foreach ($items as $item) {
            if (property_exists($item, 'name') ) {
                OptionsHelper::set_managed_email_notification(
                    $item->name,
                    $item->active === true ? 'true' : 'false'
                );

                $result[] = $item;
            }
        }

        delete_option(MS4WP_MANAGED_EMAIL_NOTIFICATIONS);

        return $result;
    }

    private static function delete_managed_email_notifications(): void
    {
        $managed_notifications = self::getManagedEmailNotifications();

        foreach ($managed_notifications as $item) {
            if (property_exists($item, 'name')) {
                delete_option(MS4WP_MANAGED_EMAIL_NOTIFICATIONS . '_' . $item->name);
            }
        }
    }

    public static function set_managed_email_notification($option_name, $active)
    {
        update_option(MS4WP_MANAGED_EMAIL_NOTIFICATIONS . '_' . $option_name, $active);
    }

    public static function set_did_accept_consent(): void
    {
        update_option(MS4WP_ACCEPTED_CONSENT, time());
    }

    public static function get_referred_by()
    {
        return get_option(MS4WP_REFERRED_BY, null);
    }

    public static function clear_options($clear_all): void
    {
        delete_option(MS4WP_INSTANCE_ID_KEY);
        delete_option(MS4WP_INSTANCE_API_KEY_KEY);
        delete_option(MS4WP_CONNECTED_ACCOUNT_ID);
        delete_option(MS4WP_ACTIVATED_PLUGINS);
        delete_option(MS4WP_ACCEPTED_CONSENT);
        delete_option(MS4WP_WC_API_KEY_ID);
        delete_option(MS4WP_WC_API_CONSUMER_KEY);
        delete_option(MS4WP_INSTANCE_HANDSHAKE_TOKEN);
        delete_option(MS4WP_INSTANCE_HANDSHAKE_EXPIRATION);
        delete_option(MS4WP_MANAGED_EMAIL_NOTIFICATIONS);
        delete_option(MS4WP_CHECKOUT_CHECKBOX_TEXT);
        self::delete_managed_email_notifications();

        if ($clear_all === true) {
            delete_option(MS4WP_INSTANCE_UUID_KEY);
            delete_option(MS4WP_ENCRYPTION_KEY_KEY);
        }
    }
}
