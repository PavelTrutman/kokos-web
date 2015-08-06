<?php
  $template['page'] = 'diskuze';

  include_once('../app/include.php');
  include_once('../app/carousel.php');

use Nette\Forms\Form;

// form for adding posts
$form = new Form;
$form->addSubmit('send', 'Odelat')
  ->getControlPrototype()
    ->setName('button')
    ->setHtml('<span class="fa fa-paper-plane"></span>&nbsp;Odeslat')
    ->addClass('btn-primary');

$form->addText('name', 'Jméno:')
  ->addRule(Form::MAX_LENGTH, 'S tou délkou jména to zas tak nepřeháněj. %d znaků ti nestačí?', 30);
$form['name']
  ->addConditionOn($form['send'], Form::SUBMITTED)
  ->setRequired('Zadej prosím svoje jméno, ať víme, kdo jsi.');

$form->addText('email', 'E-mail:')
  ->addRule(Form::MAX_LENGTH, 'S tou délkou e-mailu to zas tak nepřeháněj. %d znaků ti nestačí?', 30);
$form['email']
  ->addConditionOn($form['send'], Form::SUBMITTED)
  ->setRequired('Zadej prosím svoje jméno, ať víme, kdo jsi.')
  ->addRule(Form::EMAIL, 'Asi máš chybu v e-mailu, takhle by vypadat neměl.');

$form->addText('headline', 'Nadpis:')
       ->addRule(Form::MAX_LENGTH, 'S tou délkou naspisu to zas tak nepřeháněj. %d znaků ti nestačí?', 30);
$form['headline']
  ->addConditionOn($form['send'], Form::SUBMITTED)
  ->setRequired('Zadej svému příspěvku nějaký nadpis.');

$form->addTextArea('text', 'Tvoje zpráva:')
       ->addRule(Form::MAX_LENGTH, 'Tvůj příspěvěk je příliš dlouhý.', 10000);
$form['text']
  ->addConditionOn($form['send'], Form::SUBMITTED)
  ->setRequired('Zapomněl jsi na text.');

$form->addCheckbox('agree', ' Přečetl jsem si pravidla diskuze a respektuji je.')
  ->addConditionOn($form['send'], Form::SUBMITTED)
  ->addRule(Form::EQUAL, 'Je potřeba souhlasit s pravidly diskuze.', TRUE);

$form->addCheckbox('captcha', 'Captcha.')
  ->addConditionOn($form['send'], Form::SUBMITTED)
  ->addRule(Form::EQUAL, 'Vyplň prosím captchu.', TRUE);

$form->addSubmit('view', 'Náhled')
  ->getControlPrototype()
    ->setName('button')
    ->setHtml('<span class="fa fa-eye"></span>&nbsp;Náhled');

$form->getElementPrototype()->class('form-horizontal');
$form->getElementPrototype()->role('form');

foreach ($form->getControls() as $control) {
  if (!$control instanceof Nette\Forms\Controls\Checkbox) {
    $control->getLabelPrototype()->class("col-xs-2 control-label", TRUE);
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

$template['form'] = $form;
$template['data'] = $data;

$latte->render('../templates/diskuze.latte', $template);

?>
