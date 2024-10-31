<?php

use MySchedulr\MySchedulr;
use MySchedulr\Helpers\EnvironmentHelper;
use MySchedulr\Helpers\OptionsHelper;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if($_POST['hidden_action'] === 'disconnect') {
        OptionsHelper::clear_options(true);
        $this->instance_id = null;
    }

    if($_POST['hidden_action'] === 'change_activated_plugins') {
        $activated_plugins = [];

        if (isset($_POST['activated_plugins'])) {
            foreach ($_POST['activated_plugins'] as $activated_key) {
                $activated_plugins[] = sanitize_key($activated_key);
            }
        }

        MySchedulr::get_instance()->get_integration_manager()->set_activated_plugins($activated_plugins);
    }

    if ($_POST['hidden_action'] === 'change_marketing_information') {
        if(array_key_exists('ms4wp_show_marketing_checkbox', $_POST) && sanitize_key($_POST['ms4wp_show_marketing_checkbox']) === 'on') {
            OptionsHelper::setCheckoutCheckboxEnabled('1');
        } else {
            OptionsHelper::setCheckoutCheckboxEnabled('0');
        }
        OptionsHelper::setCheckoutCheckboxText(sanitize_text_field($_POST['ms4wp_checkbox_text']));
    }
}
?>

<div class="ms4wp-admin-wrapper">
    <header class="ms4wp-swoosh-header"></header>
    <div class="ms4wp-dots-decor ms4wp-mt-lg-5 ms4wp-ml-lg-1">
        <?php include 'elements/dots-decor.php'; ?>
    </div>

    <div class="ms4wp-swoosh-container">
    <div class="ms4wp-no-margin-top">
      <div class="ms4wp-backdrop">
        <div class="ms4wp-backdrop-container">
          <div class="ms4wp-backdrop-header ms4wp-pb-3">
              <h1 class="ms4wp-typography-root ms4wp-typography-h1 ms4wp-mb-4 ms4wp-mt-4">
                  <?php echo __( 'MySchedulr Technical Settings', 'myschedulr' ); ?>
              </h1>
              <p class="ms4wp-typography-root ms4wp-body2">
                  <?php echo __( 'We can help you grow your business without the hassle!', 'myschedulr' ); ?>
              </p>
          </div>

          <div class="ms4wp-card">
            <div class="ms4wp-px-4 ms4wp-py-4">
              <h2 class="ms4wp-typography-root ms4wp-typography-h2 ms4wp-mb-4 ms4wp-pb-2">
                  <?php echo __( 'MySchedulr by Newfold', 'myschedulr' ); ?>
              </h2>

              <?php
                if (OptionsHelper::getInstanceId()) {
                    include 'unlink.php';
                }
                else {
                    include 'pending-setup.php';
                }
                ?>
            </div>
          </div>
            <?php
                if (EnvironmentHelper::isTestEnvironment()) {
                    include 'settings-internal.php';
                }
            ?>
        </div>
      </div>
    </div>
  </div>
</div>
