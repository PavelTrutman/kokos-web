<?php

  $template['page'] = 'ucet';

  include_once('../app/include.php');

  if(!isset($_SESSION['id'])) {
    header("Location: /prihlasit-se");
  }

  dibi::query('CREATE TEMPORARY TABLE [Body] SELECT [KA_Competitors].[Id], [KA_Competitors].[Grade], SUM([KA_Scores.Score]) AS [Total] FROM [KA_Competitors], [KA_Scores] WHERE [KA_Competitors].[Id] = [KA_Scores].[CompetitorId] AND [KA_Scores].[SerieId] <= (SELECT [Id] FROM [KA_Series] ORDER BY [Id] DESC LIMIT 1) GROUP BY [KA_Competitors].[Id] ORDER BY [Total] DESC');

  $pointsTotal = dibi::fetchSingle('SELECT [Total] FROM [Body] WHERE [Id] =  %i', $_SESSION['id']);
  if ($pointsTotal == NULL) {
    $pointsTotal = 0;
  }

  $pointsSerie = dibi::fetchSingle('SELECT SUM([KA_Scores.Score]) FROM [KA_Competitors], [KA_Scores] WHERE [KA_Competitors].[Id] = [KA_Scores].[CompetitorId] AND [KA_Scores].[SerieId] = (SELECT [Id] FROM [KA_Series] ORDER BY [Id] DESC LIMIT 1) AND [KA_Competitors].[Id] = %i GROUP BY [KA_Competitors].[Id]', $_SESSION['id']);
  if ($pointsSerie == NULL) {
    $pointsSerie = 0;
  }

  $rankTotal = dibi::fetchSingle('SELECT count(*) + 1 FROM [Body] WHERE [Total] > %i', $pointsTotal);
  $rankTotalTies = dibi::fetchSingle('SELECT count(*) - 1 FROM [Body] WHERE [Total] = %i', $pointsTotal);

  $rankGrade = dibi::fetchSingle('SELECT count(*) + 1 FROM [Body] WHERE [Total] > %i AND [Grade] = (SELECT [Grade] FROM [KA_Competitors] WHERE [Id] = %i)', $pointsTotal, $_SESSION['id']);
  $rankGradeTies = dibi::fetchSingle('SELECT count(*) - 1 FROM [Body] WHERE [Total] = %i AND [Grade] = (SELECT [Grade] FROM [KA_Competitors] WHERE [Id] = %i)', $pointsTotal, $_SESSION['id']);

  $hasSolved = dibi::fetchSingle('SELECT count(*) FROM [Body] WHERE [Id] = %i', $_SESSION['id']);
  if (!$hasSolved) {
    $rankTotalTies++;
    $rankGradeTies++;
  }

  $serie = dibi::query('SELECT [Id], [Name], [MaxScore1], [MaxScore2], [MaxScore3], [MaxScore4], [MaxScore5], [MaxScore6] FROM [KA_Series] WHERE [Year] = (SELECT [Year] FROM [KA_Years] ORDER BY [Year] DESC LIMIT 1) ORDER BY [CreatedDateTime] DESC')->fetchAll();
  
  foreach($serie as $n => $row) {
    $row['score'] = 0;
    $row['maxScore'] = 0;
    for ($i = 1; $i <= 6; $i++) { 
      $row['score' . $i] = dibi::fetchSingle('SELECT [Score] FROM [KA_Scores] WHERE [CompetitorId] = %i AND [SerieId] = %i AND [Problem] = %i', $_SESSION['id'], $row['Id'], $i);
      $row['solution'] = dibi::fetchSingle('SELECT [Id] FROM [KA_Solutions] WHERE [CompetitorId] = %i AND [SerieId] = %i AND [ProblemId] = %i ORDER BY [Created] DESC LIMIT 1', $_SESSION['id'], $row['Id'], $i);
      $row['score'] += $row['score' . $i];
      $row['maxScore'] += $row['MaxScore' . $i];
    }
  }

  $template['pointsTotal'] = $pointsTotal;
  $template['pointsSerie'] = $pointsSerie;
  $template['rankTotal'] = $rankTotal;
  $template['rankTotalTies'] = $rankTotalTies;
  $template['rankGrade'] = $rankGrade;
  $template['rankGradeTies'] = $rankGradeTies;

  $template['serie'] = $serie;

$latte->render('../templates/ucet.latte', $template);

?>
