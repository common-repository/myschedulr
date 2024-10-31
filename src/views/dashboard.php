<div class="ms4wp-admin-wrapper ms4wp-mb-4 ms4wp-pb-4">
    <div class="ms4wp-dots-decor ms4wp-mt-lg-7 ms4wp-ml-lg-5">
        <?php include 'elements/dots-decor.php'; ?>
    </div>
    <header class="ms4wp-onboard-header3"></header>
    <header class="ms4wp-onboard-header2"></header>

    <div class="ms4wp-swoosh-container">
      <div class="ms4wp-no-margin-top">
          <div class="ms4wp-backdrop">
                <div class="ms4wp-backdrop-container ms4wp-mt-8">
                    <div class="ms4wp-logo-poppins ms4wp-mb-2"></div>
                    <div class="ms4wp-card">
                        <div class="ms4wp-px-4 ms4wp-pt-4">
                            <h1 class="ms4wp-typography-root ms4wp-typography-h1">
                                <?php echo __( 'Intelligent Online Scheduling for WordPress', 'myschedulr' ); ?>
                            </h1>
                            <?php
                                if (in_array('password-protected/password-protected.php', apply_filters('active_plugins', get_option('active_plugins'))) && (bool) get_option( 'password_protected_status' ) ) {
                                    include 'password-protected-notice.php';
                                }
                                else {
                                    include 'dashboard-open.php';
                                }
                            ?>
                            <div id="ms4wpskeleton" class="ms4wp-show-on-mobile">
                                <div class="ms4wp-button-base-root ms4wp-button-root ms4wp-button-contained ms4wp-mt-2 skeleton-pulse skeleton-container">
                                  <span class="ms4wp-button-label ms4wp-responsive-width"><?php echo __( 'Loading your account...', 'myschedulr' ); ?></span>
                                </div>
                                <div class="ms4wp-typography-root ms4wp-typography-h6 ms4wp-mt-4 ms4wp-mb-3 skeleton-pulse ms4wp-subapps-skeleton"></div>
                                <div class="ms4wp-grid ms4wp-mt-3">
                                  <div class="ms4wp-grid-item">
                                    <div class="ms4wp-grid-item-card ms4wp-mb-4">
                                      <div class="ms4wp-grid-item-card-media skeleton-pulse ms4wp-grid-item-card-media-skeleton"></div>
                                      <div class="ms4wp-grid-item-card-content-root skeleton-pulse">
                                        <div class="ms4wp-grid-item-card-content-skeleton-title"></div>
                                        <div class="ms4wp-grid-item-card-content-skeleton-description"></div>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="ms4wp-grid-item">
                                    <div class="ms4wp-grid-item-card ms4wp-mb-4">
                                      <div class="ms4wp-grid-item-card-media skeleton-pulse ms4wp-grid-item-card-media-skeleton"></div>
                                      <div class="ms4wp-grid-item-card-content-root skeleton-pulse">
                                        <div class="ms4wp-grid-item-card-content-skeleton-title"></div>
                                        <div class="ms4wp-grid-item-card-content-skeleton-description"></div>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="ms4wp-grid-item">
                                    <div class="ms4wp-grid-item-card ms4wp-mb-4">
                                      <div class="ms4wp-grid-item-card-media skeleton-pulse ms4wp-grid-item-card-media-skeleton"></div>
                                      <div class="ms4wp-grid-item-card-content-root skeleton-pulse">
                                        <div class="ms4wp-grid-item-card-content-skeleton-title"></div>
                                        <div class="ms4wp-grid-item-card-content-skeleton-description"></div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
