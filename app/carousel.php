<?php

  // get last serie number
  $template['lastSerie'] = dibi::query('SELECT [Id] FROM [KA_Series] ORDER BY [ClosureDateTime] DESC LIMIT 1')->fetchSingle();

  // carousel data
  $template['carousel'] = array(
    'uvod' => ' <h1>Koperníkův Korespondenční Seminář</h1>
                <p>Matematický seminář pro žáky 6. &ndash; 9. tříd základních škol nebo odpovídajících ročníků gymnázií.</p><p>Baví tě matematika nebo logické hádanky?</p>
                <p><a class="btn btn-lg btn-primary" href="#" role="button"><span class="fa fa-lg fa-user-plus"></span>&nbsp;Zaregistruj se!</a></p>',
    'serie' => '<h1>Série</h1>
                <p>Pravidelně během školního roku výchází série příkladů, které vyřešíš a pošleš nám je. My je opravíme, ohodnotíme a pošleme ti zpět správné řešení.</p><p>Stáhni si aktuální sérii a pusť se do řešení!</p>
                <p><a class="btn btn-lg btn-primary" href="/serie/' . $template['lastSerie'] . '" role="button"><span class="fa fa-lg fa-download"></span>&nbsp;Aktuální série</a></p>',
    'vysledky' => ' <h1>Výsledky</h1>
                    <p>Porovnej si svoje znalosti se znalostmi tvých vrstevníků a podívej se, které příklady ti jdou a kde naopak máš více zabrat.</p><p>Zjisti, jak jsi na tom se svými znalostmi!</p>
                    <p><a class="btn btn-lg btn-primary" href="/vysledky" role="button"><span class="fa fa-lg fa-trophy"></span>&nbsp;Výsledky</a></p>',
  );
  if (isset($template['page']) && array_key_exists($template['page'], $template['carousel'])) {
    $template['carouselActive'] = $template['page'];
  } 
  else {
    $template['carouselActive'] = 'uvod';
  }

?>
