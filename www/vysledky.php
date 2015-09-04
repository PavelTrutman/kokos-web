<?php
$template['page'] = 'vysledky';

include_once('../app/include.php');

if (isset($_GET['id'])) {
  $serie = $_GET['id'];
}
else {
 header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
 die;
}

if (isset($_GET['grade']) && $_GET['grade'] >= 6 && $_GET['grade'] <= 9) {
 $grade = $_GET['grade'];
}
else {
  $grade = 0;
}

$name = dibi::query('SELECT [Name] FROM [KA_Series] WHERE [Id] = %i', $serie)->fetchSingle();
if ($name === False) {
   header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
   die;
}

$data = dibi::fetch('SELECT [File] FROM [KA_StoredResults] WHERE [SerieId] = %i AND [Grade] = %i ORDER BY [CreatedDateTime] Desc', $serie, $grade);
$table = str_replace('result-table', 'result-table table table-striped table-hover', $data['File']);

$year = dibi::query('SELECT [StartYear], [FinalYear] FROM [KA_Years], [KA_Series] WHERE [KA_Years].[Year] = [KA_Series].[Year] AND [KA_Series].[Id] = %i LIMIT 1', $serie)->fetch();


$template['name'] = $name;
$template['year'] = $year;
$template['grade'] = $grade;
$template['serie'] = $serie;
$template['table'] = $table;

$latte->render('../templates/vysledky.latte', $template);

?>
