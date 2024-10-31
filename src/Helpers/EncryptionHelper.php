<?php


namespace MySchedulr\Helpers;

use Exception;
use MySchedulr\Managers\RaygunManager;
use Defuse\Crypto\Crypto;
use Defuse\Crypto\Key;

final class EncryptionHelper
{
    /**
     * Returns the encryption key for the plugin
     *
     * @throws \Defuse\Crypto\Exception\BadFormatException
     * @throws \Defuse\Crypto\Exception\EnvironmentIsBrokenException
     */
    private static function getEncryptionKey()
    {
        $ms4wpKey = get_option(MS4WP_ENCRYPTION_KEY_KEY, null);

        if ($ms4wpKey === null) {
            $ms4wpKey = Key::createNewRandomKey();

            update_option(MS4WP_ENCRYPTION_KEY_KEY, $ms4wpKey->saveToAsciiSafeString());
        } else {
            $ms4wpKey = Key::loadFromAsciiSafeString($ms4wpKey);
        }

        return $ms4wpKey;
    }

    /**
     * Add an encrypted entry to wp_options table
     *
     * @throws \Defuse\Crypto\Exception\BadFormatException
     * @throws \Defuse\Crypto\Exception\EnvironmentIsBrokenException
     */
    public static function addOption($optionName, $value, $autoload = true)
    {
        add_option($optionName, Crypto::encrypt($value, self::getEncryptionKey()), '', $autoload);
    }

    public static function getOption($option_name, $default = false)
    {
        $encrypted = get_option($option_name, $default);

        if ($encrypted === $default ) {
            return $default;
        } else {
            try {
                return Crypto::decrypt($encrypted, self::getEncryptionKey());
            } catch (Exception $e) {
                RaygunManager::getInstance()->exceptionHandler($e);

                return $encrypted;
            }
        }
    }
}
