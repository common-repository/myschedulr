<?php

namespace MySchedulr\Helpers;

final class GuidHelper
{
    public static function generateGUID(): string
    {
        return sprintf(
            '%04X%04X-%04X-%04X-%04X-%04X%04X%04X',
            wp_rand(0, 65535),
            wp_rand(0, 65535),
            wp_rand(0, 65535),
            wp_rand(16384, 20479),
            wp_rand(32768, 49151),
            wp_rand(0, 65535),
            wp_rand(0, 65535),
            wp_rand(0, 65535)
        );
    }
}
