<?php
  // load all from composer
  include_once('../vendor/autoload.php');

  require_once('../app/db.php');

  if(isset($_GET['galleryId']) && isset($_GET['photoId']) && isset($_GET['thumb'])) {
    $galleryId = (int) $_GET['galleryId'];
    $photoId = (int) $_GET['photoId'];

    if($_GET['thumb'] == 0 || $_GET['thumb'] == 1 || $_GET['thumb'] == 2) {
      $thumb = $_GET['thumb'];
    }
    else {
     header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
     die;
    }

    //$result = dibi::query('SELECT [GalleryId], [PhotoId] FROM [KFE_Photos] WHERE [GalleryId] = %i AND [PhotoId] = %i LIMIT 1', $galleryId, $photoId);
    $result = dibi::query('SELECT [Photo] FROM [KFE_Photos] WHERE [GalleryId] = %i AND [PhotoId] = %i LIMIT 1', $galleryId, $photoId);

    if(count($result) > 0) {
      $file = $result->fetchSingle();
      header('Content-type: image/jpeg');

      if($thumb == 1) {
        $image = imagecreatefromstring($file);
        $width = imagesx($image);
        $height = imagesy($image);
        $ratio = $width/$height;
        $resampledWidth = 400;
        $resampledHeight = $resampledWidth/$ratio;
        $imgResampled = imagecreatetruecolor($resampledWidth, $resampledHeight);
        imagecopyresampled($imgResampled, $image, 0, 0, 0, 0, $resampledWidth, $resampledHeight, $width, $height);
        imagejpeg($imgResampled, null, 90);
      }
      elseif($thumb == 2) {
        $image = imagecreatefromstring($file);
        $width = imagesx($image);
        $height = imagesy($image);

        if ($width > $height) {
          $y = 0;
          $x = ($width - $height) / 2;
          $smallestSide = $height;
        } else {
          $x = 0;
          $y = ($height - $width) / 2;
          $smallestSide = $width;
        }

        $resampledSize = 300;
        $imgResampled = imagecreatetruecolor($resampledSize, $resampledSize);
        imagecopyresampled($imgResampled, $image, 0, 0, $x, $y, $resampledSize, $resampledSize, $smallestSide, $smallestSide);
        imagejpeg($imgResampled, null, 90);
      }
      else {
        echo $file;
      }
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
