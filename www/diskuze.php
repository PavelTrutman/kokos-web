<?php

  $template['page'] = 'diskuze';

  include_once('../app/include.php');
  include_once('../app/carousel.php');

use Nette\Forms\Form;

// form for adding posts

$formVals = array(
  'name' => 'Zatvrzelý KoKoSák',
  'email' => 'zatvrdl@do.kokosu.cz',
  'headline' => 'Víc příkladů, víc sérií!',
  'text' => 
'//Milí KoKoSáci//,

řešení **KoKoSu** mě strašně baví a nemohli by jste posílat série častěji s více příklady uvnitř? Já je totiž vyřeším všechny strašně rychle a pak nemám co dělat.

Díky moc

P.S. A dělejte taky častěji soustředění, jsou totiž super!',
);
$viewData = $formVals;

$form = new Form;
$form->addSubmit('send', 'send')
  ->getControlPrototype()
    ->setName('button')
    ->setHtml('<span class="fa fa-paper-plane"></span>&nbsp;Odeslat')
    ->addClass('btn-primary');

$form->addText('parent', '')
  ->setRequired('Formulář nebyl korektně vyplněn. Zkus stránku znovu načíst a formulář odeslat znovu.');

$form->addText('name', 'Jméno:')
  ->setAttribute('placeholder', $formVals['name'])
  ->addRule(Form::MAX_LENGTH, 'S tou délkou jména to zas tak nepřeháněj. %d znaků ti nestačí?', 40);
$form['name']
  ->addConditionOn($form['send'], Form::SUBMITTED)
  ->setRequired('Zadej prosím svoje jméno, ať víme, kdo jsi.');

$form->addText('email', 'E-mail:')
  ->setAttribute('placeholder', $formVals['email'])
  ->addRule(Form::MAX_LENGTH, 'S tou délkou e-mailu to zas tak nepřeháněj. %d znaků ti nestačí?', 40);
$form['email']
  ->setType('email')
  ->addConditionOn($form['send'], Form::SUBMITTED)
  ->setRequired('Zadej prosím svoje jméno, ať víme, kdo jsi.')
  ->addRule(Form::EMAIL, 'Asi máš chybu v e-mailu, takhle by vypadat neměl.');

$form->addText('headline', 'Nadpis:')
  ->setAttribute('placeholder', $formVals['headline'])
  ->addRule(Form::MAX_LENGTH, 'S tou délkou naspisu to zas tak nepřeháněj. %d znaků ti nestačí?', 40);
$form['headline']
  ->addConditionOn($form['send'], Form::SUBMITTED)
  ->setRequired('Zadej svému příspěvku nějaký nadpis.');

$form->addTextArea('text', 'Tvoje zpráva:')
  ->setAttribute('placeholder', $formVals['text'])
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

$form->addSubmit('view', 'view')
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

// send form parsing
if ($form->isSuccess()) {
  $gotValues = $form->getValues(True);
  if($form['view']->submittedBy) {
    $viewData = array();
    $viewData['name'] = $gotValues['name'] != '' ? $gotValues['name'] : '???';
    $viewData['email'] = $gotValues['email'] != '' ? $gotValues['email'] : '???';
    $viewData['headline'] = $gotValues['headline'] != '' ? $gotValues['headline'] : '???';
    $viewData['text'] = $gotValues['text'];
  }
  elseif($form['send']) {

  }
}


// set defaul values 
$form['captcha']->setValue(FALSE);

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

$texy = new Texy();
TexyConfigurator::safeMode($texy);

$viewData['html'] = $texy->process($viewData['text']);
$template['form'] = $form;
$template['viewData'] = $viewData;
$template['data'] = $data;

$latte->render('../templates/diskuze.latte', $template);

?>
