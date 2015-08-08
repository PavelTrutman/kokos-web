var captchaId = [];

function loadCaptcha(element) {
  cId = grecaptcha.render(element, {
    'sitekey' : '6LeGrgoTAAAAADcm2eibbollw7PzMHt14nPu6Npi',
    'callback' : function(response) {
      $("#frm-captcha").prop("checked", true);
    },
    'expired-callback' : function() {
      $("#frm-captcha").prop("checked", false);
    }
  });
  return cId;
};

function loadPostCaptcha() {
  formId = $("#captcha-post").parent().parent().parent().attr('id');
  if(typeof formId !== "undefined") {
    id = parseInt(formId.substring(5));
    cId = loadCaptcha('captcha-post');
    captchaId[cId] = id;
    console.log(captchaId);
  }
};
