<?php
    use MySchedulr\Helpers\EnvironmentHelper;
?>

<div class="ms4wp-card">
    <div class="ms4wp-px-4 ms4wp-py-4">
        <h2 class="ms4wp-typography-root ms4wp-typography-h2 ms4wp-mb-2"><?php echo __( 'Technical details', 'myschedulr' ); ?></h2>

        <div class="ms4wp-kvp">
            <h4 class="ms4wp-typography-root ms4wp-typography-h4 ms4wp-mb-2"><?php echo __( 'Instance UUID', 'myschedulr' ); ?></h4>
            <p class="ms4wp-typography-root ms4wp-body2 ms4wp-typography-color"><?php echo esc_html($this->instance_uuid) ?></p>
        </div>

        <div class="ms4wp-kvp">
            <h4 class="ms4wp-typography-root ms4wp-typography-h4 ms4wp-mb-2"><?php echo __( 'Instance Id', 'myschedulr' ); ?></h4>
            <p class="ms4wp-typography-root ms4wp-body2 ms4wp-typography-color"><?php echo esc_html($this->instance_id) ?></p>
        </div>

        <div class="ms4wp-kvp">
            <h4 class="ms4wp-typography-root ms4wp-typography-h4 ms4wp-mb-2"><?php echo __( 'Environment', 'myschedulr' ); ?></h4>
            <p class="ms4wp-typography-root ms4wp-body2 ms4wp-typography-color"><?php echo esc_html(EnvironmentHelper::get_environment()) ?></p>
        </div>

        <div class="ms4wp-kvp">
            <h4 class="ms4wp-typography-root ms4wp-typography-h4 ms4wp-mb-2"><?php echo __( 'Plugin version', 'myschedulr' ); ?></h4>
            <p class="ms4wp-typography-root ms4wp-body2 ms4wp-typography-color"><?php echo esc_html(MS4WP_PLUGIN_VERSION) . '.' . esc_html(MS4WP_BUILD_NUMBER) ?></p>
        </div>

        <div class="ms4wp-kvp">
            <h4 class="ms4wp-typography-root ms4wp-typography-h4 ms4wp-mb-2"><?php echo __( 'App', 'myschedulr' ); ?></h4>
            <p class="ms4wp-typography-root ms4wp-body2 ms4wp-typography-color"><?php echo esc_js(EnvironmentHelper::getAppUrl()) ?></p>
        </div>

        <div class="ms4wp-kvp">
            <h4 class="ms4wp-typography-root ms4wp-typography-h4 ms4wp-mb-2"><?php echo __( 'App Gateway', 'myschedulr' ); ?></h4>
            <p class="ms4wp-typography-root ms4wp-body2 ms4wp-typography-color"><?php echo esc_js(EnvironmentHelper::get_app_gateway_url()) ?></p>
        </div>
    </div>
</div>
