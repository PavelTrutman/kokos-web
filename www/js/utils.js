function decodeMail(s) {
  return s.replace(/[a-zA-Z]/g, function(c) {
      return String.fromCharCode((c <= "Z" ? 90 : 122) >= (c = c.charCodeAt(0) + 13) ? c : c - 26);
  });
}

function decodeMailElement(element) {
  element.text(decodeMail(element.text()));

  if(element.prop('tagName') == 'A') {
    var href = element.attr('href');
    if(href != undefined) {
      if(href.indexOf('mailto:') == 0) {
        var newHref = href.replace('mailto:', '');
        newHref = decodeMail(newHref);
        newHref = 'mailto:' + newHref;
        element.attr('href', newHref);
      }
    }
  }
}

$(document).ready(function() {
  var mails = $('.hiddenMail');
  mails.each(function() {decodeMailElement($(this))});
});
