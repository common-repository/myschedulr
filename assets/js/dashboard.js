// phpcs:disable

var ms4wpHtml = `
<!DOCTYPE html>
<html>
  <head>
    <title>Loading...</title>
    <style>
        body { background-color: #F6F6F6; }
        .ms4wp-wrapper { position: absolute; top: 0; bottom: 0; left: 0; right: 0; padding: 5%; }
        .ms4wp-loader { position: relative; margin: 0 auto; width: 100px; }
        .ms4wp-loader:before{content: ''; display: block; padding-top: 100%;}
        .ms4wp-circular { 
            animation: rotate 2s linear infinite;
            height: 100%;
            transform-origin: center center;
            width: 100%;
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            margin: auto;
            }
        .ms4wp-path { 
            stroke-dasharray: 1, 200;
            stroke-dashoffset: 0;
            stroke: #0076DF;
            animation: dash 1.5s ease-in-out infinite; stroke-linecap: round;
        }
        @keyframes rotate{100%{transform: rotate(360deg);}}
        @keyframes dash{
            0%{ stroke-dasharray: 1, 200; stroke-dashoffset: 0; }
            50% { stroke-dasharray: 89, 200; stroke-dashoffset: -35px; }
            100% { stroke-dasharray: 89, 200; stroke-dashoffset: -124px; }
          }
    </style>
  </head>
  <body>
    <div class="ms4wp-wrapper">
    <div class="ms4wp-loader"> 
      <svg class="ms4wp-circular" viewBox="25 25 50 50">
      <circle class="ms4wp-path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/></svg>
    </div>
    </div>
  </body>
</html>`

function ms4wpNavigateToDashboard(element, linkReference, linkParameters, startCallback, finishCallback) {
  if (typeof startCallback === 'function') {
    startCallback(element)
  }

  var ms4wpWindow = window.open("about:blank", '_blank');
  ms4wpWindow.document.body.innerHTML = ms4wpHtml;

  jQuery.ajax({
    type   : "POST",
    url    : ms4wp_data.url,
    data   : {
      nonce: ms4wp_data.nonce,
      link_reference: linkReference || undefined,
      link_parameters: linkParameters || undefined,
      action: 'ms4wp_request_sso'
    },
    success: function(response) {
      if (response.success) {
        ms4wpWindow.location = response.data.url;
        if (typeof finishCallback === 'function') {
          finishCallback(element)
        }
      }
    },
    error: function(){
      ms4wpWindow.close();
    }
  });
}

function ms4wpDashboardStartCallback (element) {
  var skeleton = document.getElementById('ms4wpskeleton')
  var loaded = document.getElementById('ms4wploaded')
  if (skeleton && loaded) {
    skeleton.style.display = "block";
    loaded.style.display = "none";
  }
}
function ms4wpDashboardFinishCallback (element) {
  var skeleton = document.getElementById('ms4wpskeleton')
  var loaded = document.getElementById('ms4wploaded')
  if (skeleton && loaded) {
    skeleton.style.display = "none";
    loaded.style.display = "block";
  }
}

function ms4wpWidgetStartCallback (element) {
  if (element) {
    element.setAttribute('disabled', true)
  }
}
function ms4wpWidgetFinishCallback (element) {
  if (element) {
    element.removeAttribute('disabled')
  }
}

function ms4wpOnMenuClick(event) {
  event.stopImmediatePropagation();
  event.preventDefault();
  var element = this;
  jQuery(function($){
    var link_reference = $(element).find("span").data("ms4wp_link_reference");
    ms4wpNavigateToDashboard(element, link_reference, { source: 'ms4wp_admin_menu' }, ms4wpDashboardStartCallback, ms4wpDashboardFinishCallback);
  });
}

jQuery(function($){
  $('#ms4wp-menu-contacts').parent().on('click', ms4wpOnMenuClick);
  $('#ms4wp-menu-campaigns').parent().on('click', ms4wpOnMenuClick);
  $('#ms4wp-menu-woocommerce').parent().on('click', ms4wpOnMenuClick);
  $('#ms4wp-menu-automation').parent().on('click', ms4wpOnMenuClick);

  if (window.location.href.indexOf("myschedulr") > -1 || window.location.href.indexOf("ms4wp") > -1) {
    $("body").addClass("ms4wp-loaded");
    $("#wpcontent, #wpwrap, body").css("background-color", "#EDFDF7");
  } else {
    $("body").removeClass("ms4wp-loaded");
  }
});
