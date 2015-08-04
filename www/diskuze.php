<?php
  $template['page'] = 'diskuze';

  include_once('../app/include.php');
  include_once('../app/carousel.php');

  $data[0] = dibi::query('SELECT [Id], [Date], [Title], [Author], [Email], [Message] FROM [KFE_Board] WHERE [ParentId] = 0 ORDER BY [Date] DESC')->fetchAll();


printPosts($data, 0);


function printPosts(&$data, $id) {
  foreach ($data[$id] as $post) {
    $innerData = dibi::query('SELECT [Id], [Date], [Title], [Author], [Email], [Message] FROM [KFE_Board] WHERE [ParentId] = %i ORDER BY [Date] DESC', $post['Id']);
    if (count($innerData) != 0) {
      $data[$post['Id']] = $innerData->fetchAll();
      printPosts($data, $post['Id']);
    }
  }
}

$template['data'] = $data;

$latte->render('../templates/diskuze.latte', $template);

?>
