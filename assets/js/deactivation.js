/**
 * Deactivation survey javascript.
 *
 * @package MySchedulr
 */
jQuery(function($){
    let ms4wpDeactivateLink = $('#the-list').find('[data-slug="myschedulr"] span.deactivate a');
    let ms4wpForm = $('#ms4wp-deactivate-survey-form');
    let ms4wpThankYou = $('#ms4wp-deactivate-survey-form-success');
    let ms4wpOverlay = $('#ms4wp-deactivate-survey');
    let ms4wpCloseButton = $('#ms4wp-deactivate-survey-close');
    let ms4wpFormOpen = false;

    ms4wpDeactivateLink.on('click', function(event) {
      event.preventDefault();
      ms4wpOverlay.css('display', 'table');
      ms4wpFormOpen = true;
    });

    ms4wpForm.on('submit', function (event) {
        event.preventDefault();

        let ms4wpFormData = jQuery(this).serialize();
        jQuery.ajax({
            type   : "POST",
            url    : ms4wp_data.url,
            data   : {
                nonce: ms4wp_data.nonce,
                data: ms4wpFormData,
                action: 'ms4wp_deactivate_survey'
            },
            success: function(data){
                ms4wpForm.hide();
                ms4wpThankYou.show();
            }
        });
    });

    ms4wpCloseButton.on('click', function(event) {
      event.preventDefault();
      ms4wpOverlay.css('display', 'none');
      ms4wpFormOpen = false;
      location.href = ms4wpDeactivateLink.attr('href');
    });

    $(document).keyup(function(event) {
      if ((event.keyCode === 27) && ms4wpFormOpen) {
        location.href = ms4wpDeactivateLink.attr('href');
      }
    });
});
