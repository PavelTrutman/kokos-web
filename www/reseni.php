<?php
  // load all from composer
  include_once('../vendor/autoload.php');

  require_once('../app/db.php');

  //session
  session_start();
  if(isset($_SESSION['id']) && ($_SESSION['id'] !== NULL)) {
    if(!isset($_SESSION['ip']) || ($_SESSION['ip'] != $_SERVER['REMOTE_ADDR'])) {
      session_unset();
      session_destroy();
    }
  }
  if(!isset($_SESSION['id'])) {
    header("Location: /prihlasit-se");
  }

  if(isset($_GET['id'])) {
    $solutionId = (int) $_GET['id'];

    $solution = dibi::fetch('SELECT [CompetitorId], [Parts], [Type] FROM [KA_Solutions] WHERE [Id] = %i', $solutionId);
    if($solution !== FALSE) {
      if($solution['CompetitorId'] == $_SESSION['id']) {
        //var_dump($solution['Parts']);
        $file = '';
        for($i = 1; $i <= $solution['Parts']; $i++) {
          $data = dibi::fetchSingle('SELECT [Data] FROM [KA_SolutionsFiles] WHERE [SolutionId] = %i AND [Part] = %i', $solutionId, $i);
          //var_dump(strlen($data));
          $file .= $data; 
        }
        header('Content-type: ' . $solution['Type']);
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
  }
  else {
   header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
   die;
  }

?>
