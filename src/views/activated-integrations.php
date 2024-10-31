<?php

use MySchedulr\MySchedulr;
use MySchedulr\Helpers\EnvironmentHelper;

$available_integrations = MySchedulr::get_instance()->get_integration_manager()->get_active_plugins();
$activated_integrations = MySchedulr::get_instance()->get_integration_manager()->get_activated_integrations();

?>

<script type="application/javascript">
  function showConsentModal () {
    var form = document.getElementById("activated_plugins_form");
    var checkboxes = form.querySelectorAll("input[type='checkbox']:checked");
    if (checkboxes.length > 0) {
        document.getElementById('consent-modal').style.display = "block";
    } else {
        submitForm();
    }
  }

  function closeConsentModal () {
    document.getElementById('consent-modal').style.display = "none";
  }

  function submitForm() {
    document.getElementById('consent-modal-activated-loader').classList.remove("ms4wp-hidden");
    document.getElementById('consent-modal-activated-content').style.display = "none";
    document.getElementById('activated_plugins_form').submit()
  }

  function onChecked(slug){
      var card = document.getElementById('activated-plugins-' + slug);
      if(card !== undefined && card !== null) {
          card.classList.toggle("ms4wp-selected")
      }
  }
</script>

<p class="ms4wp-typography-root ms4wp-typography-color ms4wp-body2">
  <?php echo __( 'Select one or more plugins to enable the synchronization of its contacts with MySchedulr.', 'myschedulr' ) ?>
</p>
<br />
<form id="activated_plugins_form" name="plugins" action="" method="post">
  <input type="hidden" name="hidden_action" value="change_activated_plugins" />
  <div class="ms4wp-grid ms4wp-typography-color">
        <?php
        foreach ($available_integrations as $available_integration) {
            if ($available_integration->is_hidden_from_active_list()) {
                continue;
            }
            $active = in_array($available_integration, $activated_integrations);
            $checked_integrations = $active === true ? 'checked' : '';
            $image_path = '/assets/p/universal/wordpress-plugin/'.$available_integration->get_slug() .'.png';
            $plugin_image = EnvironmentHelper::getAppUrl().$image_path;

            echo '<div class="ms4wp-grid-item">
        <div id="activated-plugins-' . esc_attr($available_integration->get_slug()) .'" class="ms4wp-settings-card" >
            <label for="activated-plugins-check-' . esc_attr($available_integration->get_slug()) .'">
                <div class="ms4wp-grid">
                    <div class="ms4wp-grid-item ms4wp-grid-xs-2">
                        <div class="ms4wp-settings-card-image" style="background-image: url(' . esc_attr($plugin_image) . ')" title="' . esc_attr($available_integration->get_slug()) . '"></div>
                    </div>
                    <div class="ms4wp-grid-item ms4wp-grid-xs-8">
                            <span class="ms4wp-settings-card-title">' . esc_html($available_integration->get_name()) . '</span>
                    </div>
                    <div class="ms4wp-grid-item ms4wp-grid-xs-2 ms4wp-grid-line-height">
                    <label class="ms4wp-checkbox">
                        <input onclick="onChecked(&quot;' . esc_attr($available_integration->get_slug()) .'&quot;)" type="checkbox" name="activated_plugins[]" id="activated-plugins-check-' . esc_attr($available_integration->get_slug()) .'" value="' . esc_attr($available_integration->get_slug()) . '" ' . esc_attr($checked_integrations) . ' />
                        <span></span>
                    </label>
                    </div>
                </div>
            </label>
        </div>
    </div>';
        }
        ?>
    </div>
    <div class="ce-kvp">
        <br />
    <input name="save_button" type="submit" class="ms4wp-button-base-root ms4wp-button-root ms4wp-button-contained ms4wp-button-contained-primary ms4wp-mt-2" id="save-activated-plugins" value="Save" onclick="showConsentModal(); return false;" />
    <!--  -->
  </div>

  <!-- Consent modal -->
  <div id="consent-modal" role="presentation" class="ms4wp-dialog-root ms4wp-show-on-mobile">
    <div class="ms4wp-backdrop-root ms4wp-opacity-one" aria-hidden="true"></div>

    <div class="ms4wp-dialog-container ms4wp-opacity-one" role="none presentation" tabindex="-1">
      <div class="ms4wp-dialog-wrapper" role="dialog">
        <div width="100%" class="ms4wp-dialog-header">
          <div class="ms4wp-dialog-header-title">
            <div class="ms4wp-dialog-header-title-wrapper">
              <div class="ms4wp-dialog-header-title-wrapper-content">
                <h3 class="ms4wp-typography-root ms4wp-typography-h3">
                    <?php echo __( 'Yes, these contacts expect to hear from me', 'myschedulr' ) ?>
                </h3>
              </div>
            </div>
          </div>
          <div class="ms4wp-dialog-header-close">
            <div class="ms4wp-dialog-header-close-wrapper" onclick="closeConsentModal()">
              <div class="ms4wp-dialog-header-close-wrapper-button">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="black" xmlns="http://www.w3.org/2000/svg">
                  <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z" />
                </svg>
              </div>
            </div>
          </div>
        </div>
        <div  id='consent-modal-activated-loader' class="ms4wp-dialog-content  ms4wp-hidden">
          <div class="ms4wp-loader ms4wp-loader-container" role="progressbar">
              <svg class="core-test-MuiCircularProgress-svg" viewBox="22 22 44 44">
                  <circle class="core-test-MuiCircularProgress-circle core-test-MuiCircularProgress-circleIndeterminate" cx="44" cy="44" r="20.2" fill="none" stroke-width="3.6">
                  </circle>
              </svg>
          </div>
        </div>
        <div id='consent-modal-activated-content'>
          <div class="ms4wp-dialog-content">
              <div>
                  <div class="ms4wp-pb-3">
                      <span>
                          <?php echo __( 'Each time you add contacts, they must meet the following conditions.', 'myschedulr' ) ?>
                      </span>
                  </div>
                  <div class="ms4wp-consent">
                      <div class="ms4wp-pb-3">
                          <h4 class="ms4wp-typography-root ms4wp-typography-h4"><?php echo __('I have the consent of each contact on my list', 'myschedulr' ) ?></h4>
                          <span><?php echo __( 'You must have the prior consent of each contact added to your Newfold account. Your account cannot contain purchased, rented, third party or appended lists. In addition, you may not add auto-response addresses, transactional addresses, or user group addresses.', 'myschedulr' ) ?></span>
                      </div>
                      <h4 class="ms4wp-typography-root ms4wp-typography-h4"><?php echo __('I am not adding role addresses or distribution lists', 'myschedulr' ) ?></h4>
                      <span><?php echo __( 'Role addresses, such as sales@ or marketing@, and distribution lists often mail to more than one person and result in higher than normal spam complaints. You must remove these from your list prior to upload.', 'myschedulr' ) ?></span>
                  </div>
                  <div class="ms4wp-pb-3">
                      <span><?php echo __('Getting your email delivered is important to us. We may contact you to review your list before we send your email, if you add contacts that are likely to cause higher than normal bounces or for other reasons that we know may cause spam complaints. Thanks for helping to eliminate spam.', 'myschedulr' ) ?></span>
                  </div>
              </div>
          </div>
          <div class="ms4wp-dialog-footer">
            <div class="ms4wp-dialog-footer-close">
              <div class="ms4wp-dialog-footer-close-wrapper">
                <button class="ms4wp-button-base-root ms4wp-button-root ms4wp-button-contained ms4wp-button-contained-primary" type="button" onclick="submitForm()" >
                  <span class="MuiButton-label"><?php echo __( 'Got it!', 'myschedulr' ) ?></span>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</form>
