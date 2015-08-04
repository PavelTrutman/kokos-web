<?php

  // load all from composer
  include_once('../vendor/autoload.php');

  // load DB
  include_once('db.php');

  // load Latte
  //include_once('../libs/latte/latte.php');
  $latte = new Latte\Engine;
  $latte->setTempDirectory('../tmp');

  // load Forms
  //include_once('../libs/forms/Container.php');
  //include_once('../libs/forms/Form.php');
  use Nette\Forms\Form;

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
