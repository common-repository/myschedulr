<div class="ms4wp-admin-wrapper">
    <div class="ms4wp-dots-decor ms4wp-mt-8 ms4wp-ml-lg-5">
        <?php include 'elements/dots-decor.php'; ?>
    </div>
    <header class="ms4wp-onboard-header1"></header>
    <header class="ms4wp-onboard-header2"></header>

    <div class="ms4wp-swoosh-container">
        <div class="ms4wp-no-margin-top">
            <div class="ms4wp-backdrop">
                <div class="ms4wp-backdrop-container ms4wp-mt-10">
                    <div class="ms4wp-card">
                        <div class="ms4wp-px-4 ms4wp-pt-4">
                            <h1 class="ms4wp-typography-root ms4wp-typography-h1">
                                <?php echo __( 'WordPress + MySchedulr,', 'myschedulr' ) ?>
                                <br />
                                <?php echo  __( 'An Amazing Combination!', 'myschedulr' ) ?>
                            </h1>
                            <?php
                                if (in_array('password-protected/password-protected.php', apply_filters('active_plugins', get_option('active_plugins'))) && (bool) get_option( 'password_protected_status' ) ) {
                                    include 'password-protected-notice.php';
                                }
                                else {
                                    include 'onboarding-content.php';
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="application/javascript">
    let blurred = false;
    window.onblur = function() {
        blurred = true;
        document.getElementById('ms4wp-go-button').style.display = "none";
    };
    window.onfocus = function() { blurred && (location.reload()); };
</script>
