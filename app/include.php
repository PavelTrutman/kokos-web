<?php

  // load all from composer
  include_once('../vendor/autoload.php');

  // load DB
  include_once('db.php');

  // load Latte
  $latte = new Latte\Engine;
  $latte->setTempDirectory('../tmp');

  // add latte filters
  addFilters($latte);

  //session
  session_start();
  if(isset($_SESSION['id']) && ($_SESSION['id'] !== NULL)) {
    if(!isset($_SESSION['ip']) || ($_SESSION['ip'] != $_SERVER['REMOTE_ADDR'])) {
      session_unset();
      session_destroy();
    }
  }
  $template['session'] = $_SESSION;

  // errors and successes to the template
  $template['errors'] = array();
  $template['successes'] = array();

  // javascripts
  $template['javascript'] = array();
  $template['javascript'][] = array(
    'source' => '/js/utils.js',
  );

  // menu
  $template['menu'] = array(

    'uvod' => array(
      'name' => 'Úvod',
      'link' => '/uvod',
      'glyphicon' => 'home',
    ),

    'serie' => array(
      'name' => 'Série',
      'link' => '/serie',
      'glyphicon' => 'puzzle-piece',
    ),

    'fotogalerie' => array(
      'name' => 'Fotogalerie',
      'link' => '/fotogalerie',
      'glyphicon' => 'photo',
    ),

    'diskuze' => array(
      'name' => 'Diskuze',
      'link' => '/diskuze',
      'glyphicon' => 'comments',
    ),

    'facebook' => array(
      'name' => 'Facebook',
      'link' => 'https://www.facebook.com/gmkkokos',
      'glyphicon' => 'facebook',
    ),

  );


  function addFilters(&$latte) {
    /*
     *  Loads latte filters.
     *
     *  Args:
     *    $latte: latte object
     *
     *  Returns:
     *
     */
    
    // load filters
    require_once('latteFilters.php');

    // register filters
    $latte->addFilter('hideMail', 'hideMail');
  }

?>
