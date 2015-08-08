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
  }
};

function showForm(id) {
  if($('#well-' + id).length == 0) {
    newForm = formHtml.replace(/zero/g, id);
    if(id == 0) {
      $('#placeholder-form-0').after(newForm);
      $('#placeholder-form-0').remove();
    }
    else {
      $('#post-' + id).after(newForm);
    }
    cId = loadCaptcha('captcha-' + id);
    captchaId[cId] = id;
  }
  else {
    $('#well-' + id).removeClass('hidden');
  }
  $('#button-' + id).addClass('hidden');
}

function hideForm(id) {
  $('#well-' + id).addClass('hidden');
  $('#button-' + id).removeClass('hidden');
}
