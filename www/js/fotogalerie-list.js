function loadImage(galleryId) {
  //pick random new image
  var photoId = Math.floor(Math.random() * photos[galleryId].length);
  $('#'+ galleryId + ' img.bottom').attr('src', '/photo.php?galleryId=' + galleryId + '&photoId=' + photoId + '&thumb=2');
  setTimeout(function() {crossfade(galleryId)}, 2000);
}

function crossfade(galleryId) {
  botEl = $('#'+ galleryId + ' img.bottom');
  topEl = $('#'+ galleryId + ' img.top');

  botEl.removeClass('hidden');
  topEl.fadeOut(3000, function() {switchClasses(galleryId)});
}

function switchClasses(galleryId) {
  botEl = $('#'+ galleryId + ' img.bottom');
  topEl = $('#'+ galleryId + ' img.top');

  botEl.addClass('top').removeClass('bottom');
  topEl.addClass('bottom').removeClass('top').show();
  wait(galleryId);
}

function wait(galleryId) {
  var timeout = Math.random()*12 + 1;
  setTimeout(function() {loadImage(galleryId)}, timeout*1000);
}

$( document ).ready(function() {
  for(gallery in photos) {
    wait(gallery);
  };
});
