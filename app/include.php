<?php

  // load all from composer
  include_once('../vendor/autoload.php');

  // load DB
  include_once('db.php');

  // load Latte
  $latte = new Latte\Engine;
  $latte->setTempDirectory('../tmp');

  // errors and successes to the template
  $template['errors'] = array();
  $template['successes'] = array();

  // javascripts
  $template['javascript'] = array();

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

    'fotogalerie' => array(
      'name' => 'Fotogalerie',
      'link' => 'fotogalerie',
      'glyphicon' => 'photo',
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
