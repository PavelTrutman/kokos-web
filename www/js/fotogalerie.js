$('.gallery img').on('click',function(){
  id = $(this).attr('id').replace('photo-', '');
  renderModal(id);
});

$('.previous').on('click', function(){
  if(id > 0) {
    id = id - 1;
    renderModal(id);
  }
});

$('.next').on('click', function(){
  if(id < maxId) {
    id = parseInt(id) + 1;
    renderModal(id);
  }
});

$(document).keydown(function(e) {
  if(($("#PhotoModal").data('bs.modal') || {isShown: false}).isShown) {
    if(e.keyCode == 37) {
      if(id > 0) {
        id = id - 1;
        renderModal(id);
      }
    }
    else if(e.keyCode == 39) {
      if(id < maxId) {
        id = parseInt(id) + 1;
        renderModal(id);
      }
    }
  }
});

function renderModal(id) {
  $('#PhotoModal').modal();
  $('#PhotoModal .modal-body img').attr('src', '/photo.php?galleryId=' + GalleryId + '&photoId=' + photos[id].PhotoId + '&thumb=0');
  $('#PhotoModal .modal-body img').attr('alt', photos[id].Description);
  $('#PhotoModal .modal-body img').attr('title', photos[id].Description);
  $('#PhotoModal .modal-title').html(photos[id].Description);
  $('#PhotoModal').attr('aria-labelledby', photos[id].Description);
  if(id <= 0) {
    $('.previous').addClass('disabled');
  }
  else {
    $('.previous').removeClass('disabled');
  }
  if(id >= maxId) {
    $('.next').addClass('disabled');
  }
  else {
    $('.next').removeClass('disabled');
  }
}
