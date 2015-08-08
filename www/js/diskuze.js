var captchaId = [];
var loadCaptcha = function() {
  id = grecaptcha.render('captcha-post', {
    'sitekey' : '6LeGrgoTAAAAADcm2eibbollw7PzMHt14nPu6Npi',
    'callback' : function(response) {
      $("#frm-captcha").prop("checked", true);
    },
    'expired-callback' : function() {
      $("#frm-captcha").prop("checked", false);
    }
  });
};
