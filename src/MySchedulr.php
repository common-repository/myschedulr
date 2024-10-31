<?php

namespace MySchedulr;

use MySchedulr\Managers\AdminManager;
use MySchedulr\Managers\ApiManager;
use MySchedulr\Managers\InstanceManager;

class MySchedulr
{
    private static $instance;

    private $admin_manager;
    private $api_manager;
    private $instance_manager;

    public function __construct()
    {
        if (current_user_can('administrator')) {
            $this->admin_manager = new AdminManager();
        }
        $this->api_manager = new ApiManager();
        $this->instance_manager = new InstanceManager();
    }

    public function add_hooks()
    {
        if (!$this->is_active()) {
            return;
        }

        if ($this->admin_manager !== null) {
            $this->admin_manager->addHooks();
        }

        $this->api_manager->addHooks();
    }

    public function get_api_manager()
    {
        return $this->api_manager;
    }

    public function get_admin_manager()
    {
        return $this->admin_manager;
    }

    public function get_instance_manager()
    {
        return $this->instance_manager;
    }

    public function is_active()
    {
        return in_array(plugin_basename(MS4WP_PLUGIN_FILE), apply_filters('active_plugins', get_option('active_plugins')));
    }

    public static function get_instance()
    {
        if (self::$instance === null) {
            self::$instance = new MySchedulr();
        }

        return self::$instance;
    }
}
