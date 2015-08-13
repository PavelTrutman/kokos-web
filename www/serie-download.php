<?php
  // load all from composer
  include_once('../vendor/autoload.php');

  require_once('../app/db.php');

  if(isset($_GET['id'])) {

    $filepdf = dibi::query('SELECT [Data] FROM [KA_SeriesFiles] WHERE SerieId = %s AND Type = %s ', $_GET['id'], 'pdf')->fetchSingle();

    if ($filepdf) {
     header('Content-type: application/pdf');
     echo $filepdf;
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
