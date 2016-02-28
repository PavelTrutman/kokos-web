<?php

  $template['page'] = 'odhlasit-se';

  include_once('../app/include.php');

  if(!isset($_SESSION['id'])) {
    header("Location: /prihlasit-se");
  }

  session_unset();
  session_destroy();
  header("Location: /prihlasit-se");

?>
