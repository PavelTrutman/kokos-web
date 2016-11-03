var captchaId = [];

function loadCaptcha(element) {
  cId = grecaptcha.render(element, {
    'sitekey' : '6LeGrgoTAAAAADcm2eibbollw7PzMHt14nPu6Npi',
    'callback' : captchaCallback,
    'expired-callback' : captchaCallback,
  });
  return cId;
};

function loadPostCaptcha() {
  formId = $("#captcha-post").parent().parent().parent().attr('id');
  if(typeof formId !== "undefined") {
    cId = loadCaptcha('captcha-post');
    captchaId[cId] = 'post';
  }
};

function captchaCallback() {
  for(var cId in captchaId) {
    if(captchaId[cId] == 'post') {
      id = '#frm-captcha';
    }
    else {
      id = '#frm-captcha-' + captchaId[cId];
    }
    if(grecaptcha.getResponse(cId) != '') {
      $(id).prop("checked", true);
    }
    else {
      $(id).prop("checked", false);
    }
  }
}

function showForm(id) {
  if($('#well-' + id).length == 0) {
    newForm = formHtml.replace(/zero/g, id);
    newForm = newForm.replace(/(="frm-[a-z]+)(")/g, '$1-' + id + '$2');
    if(id == 0) {
      $('#placeholder-form-0').after(newForm);
      $('#placeholder-form-0').remove();
    }
    else {
      $('#post-' + id).after(newForm);
    }
    cId = loadCaptcha('captcha-' + id);
    captchaId[cId] = id;

    // decode mail
    decodeMailElement($('#well-' + id).find('.hiddenMail'));

  }
  else {
    $('#well-' + id).removeClass('hidden');
  }
  $('#button-' + id).addClass('hidden');
  $('[data-toggle="popover"]').popover();
  Nette.initForm(document.getElementById('form-' + id));
  
}

function hideForm(id) {
  $('#well-' + id).addClass('hidden');
  $('#button-' + id).removeClass('hidden');
}
