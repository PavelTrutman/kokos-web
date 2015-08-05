<?php

  // load all from composer
  include_once('../vendor/autoload.php');

  // load DB
  include_once('db.php');

  // load Latte
  $latte = new Latte\Engine;
  $latte->setTempDirectory('../tmp');

  // menu
  $template['menu'] = array(

    'uvod' => array(
      'name' => 'Úvod',
      'link' => 'uvod',
      'glyphicon' => 'home',
    ),

    'serie' => array(
      'name' => 'Série',
      'link' => 'serie',
      'glyphicon' => 'puzzle-piece',
    ),

    'diskuze' => array(
      'name' => 'Diskuze',
      'link' => 'diskuze',
      'glyphicon' => 'comments',
    ),

    'facebook' => array(
      'name' => 'Facebook',
      'link' => 'https://www.facebook.com/gmkkokos',
      'glyphicon' => 'facebook',
    ),

  );

?>
