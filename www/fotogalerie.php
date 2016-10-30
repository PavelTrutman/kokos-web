<?php

  $template['page'] = '';

  include_once('../app/include.php');

if(isset($_GET['name'])) {
  $gallery = $_GET['name'];

  $result = dibi::query('SELECT [Id], [Name], [Description] FROM [KFE_Galleries] WHERE [NameWebalized] = %s LIMIT 1', $gallery);
  if(count($result) > 0) {
    $data = $result->fetch();

    $photos = dibi::query('SELECT [PhotoId], [Description] FROM [KFE_Photos] WHERE [GalleryId] = %i ORDER BY [Created]', $data['Id'])->fetchAll();

    $template['data'] = $data;
    $template['photos'] = $photos;
    $template['javascript'][] = array(
      'source' => '/js/fotogalerie.js',
    );
    $latte->render('../templates/fotogalerie.latte', $template);

  }
  else {
   header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
   die;
  }
}
else {
  $template['page'] = 'fotogalerie';

  $template['javascript'][] = array(
    'source' => './js/fotogalerie-list.js',
  );

  // gallery list
  $galleries = dibi::query('SELECT [Id], [Name], [Description], [NameWebalized] FROM [KFE_Galleries] ORDER BY [Id] DESC')->fetchAll();

  $photos = dibi::query('SELECT [GalleryId], [PhotoId] FROM [KFE_Photos]')->fetchAssoc('GalleryId,PhotoId');

  $template['galleries'] = $galleries;
  $template['photos'] = $photos;
  $latte->render('../templates/fotogalerie-list.latte', $template);
}

?>
