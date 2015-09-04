<?php
$template['page'] = 'serie';

include_once('../app/include.php');

$data = dibi::query('SELECT [KA_Series].[Id], [KA_Series].[Name], [KA_Series].[Description], [KA_Series].[ClosureDateTime], count([KA_StoredResults].[Id]) AS [Results] FROM [KA_Series] LEFT JOIN [KA_StoredResults] ON [KA_Series].[Id] = [KA_StoredResults].[SerieId] WHERE [KA_Series].[Year] = (SELECT [Year] FROM [KA_Years] ORDER BY [Year] DESC LIMIT 1) GROUP BY [KA_Series].[Id] ORDER BY [KA_Series].[CreatedDateTime] ASC')->fetchAll();

$template['data'] = $data;

$latte->render('../templates/serie.latte', $template);
?>
