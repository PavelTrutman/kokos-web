<?php
  // load all from composer
  include_once('../vendor/autoload.php');

  require_once('../app/db.php');

  if(isset($_GET['galleryId']) && isset($_GET['photoId']) && isset($_GET['thumb'])) {
    $galleryId = (int) $_GET['galleryId'];
    $photoId = (int) $_GET['photoId'];

    if($_GET['thumb'] == 0) {
      $type = '[Photo]';
    }
    elseif($_GET['thumb'] == 1) {
      $type = '[PhotoThumbnail]';
    }
    else {
     header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
     die;
    }

    //$result = dibi::query('SELECT [GalleryId], [PhotoId] FROM [KFE_Photos] WHERE [GalleryId] = %i AND [PhotoId] = %i LIMIT 1', $galleryId, $photoId);
    $result = dibi::query('SELECT', $type, 'FROM [KFE_Photos] WHERE [GalleryId] = %i AND [PhotoId] = %i LIMIT 1', $galleryId, $photoId);

    if(count($result) > 0) {
      $file = $result->fetchSingle();
      header('Content-type: image/jpeg');
      echo $file;
    }
    else {
     header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
     die;
    }

  }
  else {
   header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
   die;
  }
 
?>
