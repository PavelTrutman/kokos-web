<?php

  $template['page'] = 'nahrat-reseni';

  include_once('../app/include.php');

  if(!isset($_SESSION['id'])) {
    header("Location: /prihlasit-se");
  }

  $template['javascript'][] = array(
    'source' => '/js/netteForms.js',
  );

use Nette\Forms\Form;

$serie = dibi::fetch('SELECT [Id], [Name], [ClosureDateTime], [MaxScore1], [MaxScore2], [MaxScore3], [MaxScore4], [MaxScore5], [MaxScore6] FROM [KA_Series] WHERE [Locked] = 0 AND [ClosureDateTime] >= DATE_FORMAT(CURDATE(), "%Y-%m-%d") ORDER BY [ClosureDateTime] DESC LIMIT 1');

if($serie !== FALSE) {

  $form = new Form;
  for($i = 1; $i <= 6; $i++) {
    $form->addUpload('file' . $i, 'Příklad ' . $i)
      ->addRule(Form::MAX_FILE_SIZE, 'Maximální velikost souboru je 3 MB.', 3*1024*1024)
      ->addCondition(Form::FILLED)
        ->addRule(Form::MIME_TYPE, 'Nesprávný typ souboru. Povoleny jsou jen obrázky (JPG, BMP, PNG) a dokumenty v PDF.', 'application/pdf,image/jpeg,image/bmp,image/png');
  }
  $form->addSubmit('send', 'send')
    ->getControlPrototype()
      ->setName('button')
      ->setHtml('<span class="fa fa-cloud-upload"></span>&nbsp;Nahrát řešení')
      ->addClass('btn-primary');

  $form->getElementPrototype()->class('form-horizontal upload');
  $form->getElementPrototype()->role('form');

  foreach ($form->getControls() as $control) {
    if (!$control instanceof Nette\Forms\Controls\Checkbox) {
      //$control->getLabelPrototype()->class("col-xs-3 control-label", TRUE);
    }
    if ($control instanceof Nette\Forms\Controls\TextInput || $control instanceof Nette\Forms\Controls\TextArea) {
      $control->getControlPrototype()->addClass('form-control');
    }
    elseif ($control instanceof Nette\Forms\Controls\Checkbox) {

    }
    elseif ($control instanceof Nette\Forms\Controls\SubmitButton) {
      $control->getControlPrototype()->addClass('btn btn-default');
    }
    else {
    }
  }
  
  if ($form->isSuccess()) {
    $gotValues = $form->getValues(True);
    if($form['send']) {
      for($i = 1; $i <= 6; $i++) {
        if($gotValues['file'.$i]->isOK()) {
          $array['CompetitorId'] = $_SESSION['id'];
          $array['SerieId'] = $serie['Id'];
          $array['ProblemId'] = $i;
          $parts = $gotValues['file'.$i]->getSize() / 524288 + 1;
          settype($parts, "integer");
          $array['Parts'] = $parts;
          $array['Type'] = $gotValues['file'.$i]->getContentType();
          $array['Size'] = $gotValues['file'.$i]->getSize();
          $array['Created'] = date('Y-m-d H:i:s');
          if(dibi::query('INSERT INTO [KA_Solutions] ', $array)) {
            $solutionId = dibi::insertId();
            $content = $gotValues['file'.$i]->getContents();
            unset($resultFiles);
            for($k = 0; $k < $parts; $k++) {
              $data = mb_strcut($content, $k*524288, 524288);
              $arrayFiles['SolutionId'] = $solutionId;
              $arrayFiles['Part'] = $k + 1;
              $arrayFiles['Data'] = $data;
              $resultFiles[$k] = dibi::query('INSERT INTO [KA_SolutionsFiles] ', $arrayFiles);
            }
            if(array_sum($resultFiles) === $parts) {
              $template['successes'][] = 'Řešení k příkladu '.$i.' bylo uloženo.';
            }
            else {
              $form->addError('Řešení k příkladu '.$i.' se nepodařilo nahrát.');
            }
          }
          else {
            $form->addError('Řešení k příkladu '.$i.' se nepodařilo nahrát.');
          }
        }
        else if($gotValues['file'.$i]->getError() != 4){
          $form->addError('Řešení k příkladu '.$i.' se nepodařilo nahrát.');
        }
      }
    }
  }

  $files = dibi::query('SELECT [Id], [ProblemId], [Type], [Size], [Created] FROM [KA_Solutions] WHERE [CompetitorId] = %i AND [SerieId] = %i ORDER BY [Created] ASC', $_SESSION['id'], $serie['Id'])->fetchAssoc('ProblemId,#');
  foreach($files as $problem) {
    foreach($problem as $f) {
      if($f['Type'] === 'application/pdf') {
        $f['Icon'] = 'fa-file-pdf-o';
      }
      else if($f['Type'] === 'image/jpeg' || $f['Type'] === 'image/png' || $f['Type'] === 'image/bmp') {
        $f['Icon'] = 'fa-file-image-o';
      }
      else {
        $f['Icon'] = 'fa-file-o';
      }
    }
  }

  $template['files'] = $files;
  $template['errors'] = array_merge($template['errors'], $form->getErrors());
  $template['form'] = $form;
}

$template['serie'] = $serie;

$latte->render('../templates/upload.latte', $template);

?>
