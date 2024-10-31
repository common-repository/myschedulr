/**
 * Feedback notice javascript.
 *
 * @package MySchedulr
 */
jQuery(function($){
    window.addEventListener('load', () => {
        const ms4wpParent = document.getElementById('wpbody-content')
        const ms4wpScreenMetaLinks = document.getElementById('screen-meta-links')
        const ms4wpNotice = document.getElementById('ms4wp-admin-feedback-notice')

        if ([ms4wpParent, ms4wpScreenMetaLinks, ms4wpNotice].some(element => element == null)) {
            return
        }

        ms4wpParent.insertBefore(ms4wpNotice, ms4wpScreenMetaLinks.nextSibling)
        ms4wpNotice.hidden = false
    });

});

function hideAdminFeedbackNotice (banner) {
    document.querySelector('#ms4wp-admin-feedback-notice').hidden = true

    const { ms4wp_hide_banner_url } = ms4wp_data
    fetch(`${ms4wp_hide_banner_url}${banner}`, { method: 'POST' })
}
