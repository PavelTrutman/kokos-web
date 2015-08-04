<?php

  // get last serie number
  $template['lastSerie'] = dibi::query('SELECT [Id] FROM [KA_Series] ORDER BY [ClosureDateTime] DESC LIMIT 1')->fetchSingle();

  // carousel data
  $template['carousel'] = array('uvod', 'serie', 'vysledky');
  if (isset($template['page']) && in_array($template['page'], $template['carousel'])) {
    $template['carouselActive'] = $template['page'];
  } 
  else {
    $template['carouselActive'] = 'uvod';
  }

?>
