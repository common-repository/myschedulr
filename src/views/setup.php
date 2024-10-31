<?php ?>
<div class="ms4wp-admin-wrapper">
    <header class="ms4wp-swoosh-header"></header>
    <div class="ms4wp-dots-decor ms4wp-mt-lg-5 ms4wp-ml-lg-1">
        <?php include 'elements/dots-decor.php'; ?>
    </div>

    <div class="ms4wp-swoosh-container">
        <div class="s4wp-no-margin-top">
            <div class="ms4wp-backdrop">
                <div class="ms4wp-backdrop-container">
                    <div class="ms4wp-backdrop-header ms4wp-pb-3">
                        <h1 class="ms4wp-typography-root ms4wp-typography-h1 ms4wp-mb-2 ms4wp-mt-4">
                            <?php echo __( 'MySchedulr Embed Instructions', 'myschedulr' ); ?>
                        </h1>
                    </div>

                    <div class="ms4wp-card">
                        <div class="ms4wp-px-4 ms4wp-py-4">
                            <h2 class="ms4wp-typography-root ms4wp-typography-h2 ms4wp-mb-4 ms4wp-pb-2">
                                <?php echo __( 'How to Add a Standalone Booking Page to Your Website Menu', 'myschedulr' ); ?>
                            </h2>
                            <p class="ms4wp-typography-root ms4wp-body2">
                                <ol class="ms4wp-m-1 ms4wp-ordered-list">
                                    <li class="ms4wp-mt-2">
                                        <div>
                                            <?php echo __( 'Go to the WordPress Dashboard', 'myschedulr' ); ?>
                                        </div>
                                    </li>
                                    <li class="ms4wp-mt-4">
                                        <div>
                                            <?php echo __( 'From the <b>‘Appearance’</b> tab on the left-hand side, select <b>‘Menus’</b>', 'myschedulr' ); ?>
                                        </div>
                                        <div class="ms4wp-mt-4">
                                            <img
                                                class="ms4wp-ml-2"
                                                src="<?php echo esc_url( MS4WP_PLUGIN_URL . 'assets/images/setup/setup1.png' ); ?>"
                                                alt="<?php echo __( 'Click the \'Menus\' Tab inside \'Appearence\' on the Dashboard', 'myschedulr' ); ?>"
                                            >
                                        </div>
                                    </li>
                                    <li class="ms4wp-mt-2">
                                        <div><?php echo __( 'Go to <b>‘Add menu items’</b> and click <b>‘Custom Links’</b>', 'myschedulr' ); ?></div>
                                        <div class="ms4wp-mt-4">
                                            <img
                                                class="ms4wp-responsive-img ms4wp-ml-2"
                                                src="<?php echo esc_url( MS4WP_PLUGIN_URL . 'assets/images/setup/setup2.png' ); ?>"
                                                alt="<?php echo __('Go to \'Add menu items\' and click \'Custom Links\'', 'myschedulr' ); ?>"
                                            >
                                        </div>
                                    </li>
                                    <li class="ms4wp-mt-4">
                                        <div>
                                            <?php echo __( 'Enter the <b>URL</b> of your standalone booking page and name the <b>Link Text</b> that will appear on your website menu ', 'myschedulr' ); ?>
                                        </div>
                                    </li>
                                    <li class="ms4wp-mt-4">
                                        <div>
                                            <?php echo __( 'Click <b>‘Add to Menu’</b> and save your changes', 'myschedulr' ); ?>
                                        </div>
                                    </li>
                                </ol>
                            </p>
                        </div>
                    </div>

                    <div class="ms4wp-card">
                        <div class="ms4wp-px-4 ms4wp-py-4">
                            <h2 class="ms4wp-typography-root ms4wp-typography-h2 ms4wp-mb-4 ms4wp-pb-2">
                                <?php echo __( 'How to Add a Booking Widget to Your Website With the Block Editor', 'myschedulr' ); ?>
                            </h2>
                            <p class="ms4wp-typography-root ms4wp-body2">
                                <ol class="ms4wp-m-1 ms4wp-ordered-list">
                                    <li class="ms4wp-mt-2">
                                        <div>
                                            <?php echo __( 'Go to the WordPress Dashboard', 'myschedulr' ); ?>
                                        </div>
                                    </li>
                                    <li class="ms4wp-mt-4">
                                        <div>
                                            <?php echo __( 'From the <b>‘Pages’</b> tab on the left-hand side, choose to edit an existing page or add new one.', 'myschedulr' ); ?>
                                        </div>
                                        <div class="ms4wp-mt-4">
                                            <img
                                                class="ms4wp-ml-2"
                                                src="<?php echo esc_url( MS4WP_PLUGIN_URL . 'assets/images/setup/setup3.png' ); ?>"
                                                alt="<?php echo __( 'From the \'Pages\' tab on the left-hand side, choose to edit an existing page or add new one.', 'myschedulr' ); ?>"
                                            >
                                        </div>
                                    </li>
                                    <li class="ms4wp-mt-2">
                                        <div><?php echo __( 'Once the block editor is launched, click the <b>‘block inserter’</b> button located on the top left corner or below an existing block.', 'myschedulr' ); ?></div>
                                        <div class="ms4wp-mt-4">
                                            <img
                                                class="ms4wp-responsive-img ms4wp-ml-2"
                                                src="<?php echo esc_url( MS4WP_PLUGIN_URL . 'assets/images/setup/setup4.png' ); ?>"
                                                alt="<?php echo __('Once the block editor is launched, click the \'block inserter\' button located on the top left corner or below an existing block.', 'myschedulr' ); ?>"
                                            >
                                        </div>
                                    </li>
                                    <li class="ms4wp-mt-4">
                                        <div>
                                            <?php echo __( 'When the block menu appears, search <b>‘MySchedulr’</b> and select it.', 'myschedulr' ); ?>
                                        </div>
                                        <div class="ms4wp-mt-4">
                                            <img
                                                class="ms4wp-responsive-img ms4wp-ml-2"
                                                src="<?php echo esc_url( MS4WP_PLUGIN_URL . 'assets/images/setup/setup5.png' ); ?>"
                                                alt="<?php echo __('Go to \'Add menu items\' and click \'Custom Links\'', 'myschedulr' ); ?>"
                                            >
                                        </div>
                                    </li>
                                    <li class="ms4wp-mt-4">
                                        <div>
                                            <?php echo __( 'Customize the layout of your services using the edit menus located on the right-hand side and above the block.', 'myschedulr' ); ?>
                                        </div>
                                        <div class="ms4wp-mt-4">
                                            <img
                                                class="ms4wp-responsive-img ms4wp-ml-2"
                                                src="<?php echo esc_url( MS4WP_PLUGIN_URL . 'assets/images/setup/setup6.png' ); ?>"
                                                alt="<?php echo __('Customize the layout of your services using the edit menus located on the right-hand side and above the block.', 'myschedulr' ); ?>"
                                            >
                                        </div>
                                    </li>
                                    <li class="ms4wp-mt-4">
                                        <div>
                                            <?php echo __( 'Preview the widget on your website and save your changes.', 'myschedulr' ); ?>
                                        </div>
                                    </li>
                                </ol>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

