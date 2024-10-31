<?php

namespace MySchedulr\Managers;

use MySchedulr\Helpers\OptionsHelper;
use WP_Error;

final class InstanceManager
{
    public function handle_callback($request)
    {
        $accountInformation = json_decode($request->get_body());

        if ($accountInformation === null) {
            return new WP_Error('rest_bad_request', 'Invalid account details', ['status' => 400]);
        }

        OptionsHelper::setInstanceId($accountInformation->site_id);
        OptionsHelper::setInstanceApiKey($accountInformation->api_key);
        OptionsHelper::setConnectedAccountId($accountInformation->account_id);

        return true;
    }
}
