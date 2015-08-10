<?php
  $template['page'] = 'uvod';

  include_once('../app/include.php');
  include_once('../app/carousel.php');

  // serie information
  $lastSerieDate = dibi::query('SELECT [ClosureDateTime] FROM [KA_Series] WHERE [Id] = %i', $template['lastSerie'])->fetchSingle();
  $lastSerieDate = date('j. n. Y', strtotime($lastSerieDate));


  $template['lastSerieDate'] = $lastSerieDate;

  $latte->render('../templates/index.latte', $template);
?>
