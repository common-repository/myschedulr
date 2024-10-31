<?php

printf('<div class="ms4wp-deactivate-survey-modal" id="ms4wp-deactivate-survey">
          <div class="ms4wp-deactivate-survey-wrap">
            <div class="ms4wp-deactivate-survey">
                <h2>%s</h2>
                <form method="post" id="ms4wp-deactivate-survey-form">
                    <fieldset>
                    <span><input type="radio" name="ms4wp_deactivation_option" value="0"> %s</span>
                    <span><input type="radio" name="ms4wp_deactivation_option" value="1"> %s</span>
                    <span><input type="radio" name="ms4wp_deactivation_option" value="2"> %s</span>
                    <span><input type="radio" name="ms4wp_deactivation_option" value="3"> %s</span>
                    <span><input type="radio" name="ms4wp_deactivation_option" value="4"> %s</span>
                    <span><input type="radio" name="ms4wp_deactivation_option" value="5"> %s</span>
                    <span><input type="radio" name="ms4wp_deactivation_option" value="6"> %s</span>
                    <span><input type="radio" name="ms4wp_deactivation_option" value="7"> %s: <input type="text" name="other" /></span>
                    <br>
                    <span><input type="submit" class="button button-primary" value="Submit"></span>
                    </fieldset>
                </form>
                <p id="ms4wp-deactivate-survey-form-success">%s</p>
                <a class="button" id="ms4wp-deactivate-survey-close">%s</a>
            </div>
          </div>
        </div>',
    __('Sadness... why leave so soon?', 'myschedulr'),
    __('I’m not booking right now', 'myschedulr'),
    __('It didn’t have the features I want', 'myschedulr'),
    __('I didn’t like the guttenberg block', 'myschedulr'),
    __('It was too confusing', 'myschedulr'),
    __('There were technical issues', 'myschedulr'),
    __('I don’t have enough contacts reservations', 'myschedulr'),
    __('It’s a temporary deactivation', 'myschedulr'),
    __('Other', 'myschedulr'),
    __('Thank you', 'myschedulr'),
    __('Close this window and deactivate ' . self::ADMIN_APP_NAME, 'myschedulr')
);
