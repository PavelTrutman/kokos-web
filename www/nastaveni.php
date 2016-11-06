<?php

  $template['page'] = 'nastaveni';

  include_once('../app/include.php');

  if(!isset($_SESSION['id'])) {
    header("Location: /prihlasit-se");
  }

  $template['javascript'][] = array(
    'source' => '/js/netteForms.js',
  );

use Nette\Forms\Form;

// form for adding posts

$form = new Form;

$form->addPassword('passOld', 'Staré heslo:')
  ->addRule(Form::MAX_LENGTH, 'S tou délkou hesla to zas tak nepřeháněj. %d znaků ti nestačí?', 40)
  ->setRequired('Zapomněl jsi vyplnit staré heslo.');

$form->addPassword('passNew', 'Nové heslo:')
  ->addRule(Form::MAX_LENGTH, 'S tou délkou hesla to zas tak nepřeháněj. %d znaků ti nestačí?', 40)
  ->setRequired('Zapomněl jsi vyplnit nové heslo.');

$form->addPassword('passNewRep', 'Nové heslo znovu:')
  ->addRule(Form::MAX_LENGTH, 'S tou délkou hesla to zas tak nepřeháněj. %d znaků ti nestačí?', 40)
  ->setRequired('Zapomněl jsi znovu vyplnit nové heslo.')
  ->addRule(Form::EQUAL, "Hesla se musí shodovat!", $form["passNew"]);

$form->addSubmit('send', 'send')
  ->getControlPrototype()
    ->setName('button')
    ->setHtml('<span class="fa fa-refresh"></span>&nbsp;Změnit heslo')
    ->addClass('btn-primary');

$form->getElementPrototype()->class('form-horizontal');
$form->getElementPrototype()->role('form');

foreach ($form->getControls() as $control) {
  if (!$control instanceof Nette\Forms\Controls\Checkbox) {
    $control->getLabelPrototype()->class("col-xs-3 control-label", TRUE);
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
  if($form['send']) {
    $query = dibi::query('SELECT [Id] FROM [KA_Competitors] WHERE ([Id] = %s AND [Pass] = %s)', $_SESSION['id'], sha1($gotValues['passOld']));
    if(count($query) === 1) {
      $query = dibi::query('UPDATE [KA_Competitors] SET [pass] = %s WHERE [Id] = %s LIMIT 1', sha1($gotValues['passNew']), $_SESSION['id']);
      if(count($query) === 1) {
        $template['successes'][] = 'Heslo bylo úspěšně změněno.';
      }
      else {
        $form->addError('Heslo se nepodařilo změnit. Zkus to prosím znovu.');
      }
    }
    else {
      $form->addError('Přihlašovací heslo nesouhlasí.');
    }

  }
}

$data = dibi::fetch('SELECT [KA_Competitors].[Id], [KA_Competitors].[Name], [KA_Competitors].[Surname], [KA_Competitors].[Street], [KA_Competitors].[NumberO], [KA_Competitors].[NumberD], [KA_Competitors].[City], [KA_Competitors].[Zipcode], [KA_Competitors].[Grade], [KA_Schools].[Name] AS [School], [KA_Schools].[City] AS [SchoolCity], [KA_Competitors].[Shipping], [KA_Competitors].[Phone], [KA_Competitors].[Email] FROM [KA_Competitors], [KA_Schools] WHERE [KA_Competitors].[Id] = %i AND [KA_Competitors].[SchoolId] = [KA_Schools].[Id]', $_SESSION['id']);

if ($data['Shipping'] == 1) {
  $data['Shipping'] = 'Domů';
}
else if ($data['Shipping'] == 2) {
  $data['Shipping'] = 'Do školy';
}
else {
  $data['Shipping'] = '-';
}
if($data['NumberO'] == 0 || $data['NumberO'] == null || !$data['NumberO']) {
  $data['Number'] = $data['NumberD'];
}
else if($data['NumberD'] == 0 || $data['NumberD'] == null || !$data['NumberD']) {
  $data['Number'] = $data['NumberO'];
}
else if($data['NumberD'] == $data['NumberO']) {
  $data['Number'] = $data['NumberD'];
}
else {
  $data['Number'] = $data['NumberD'].'/'.$data['NumberO'];
}

$template['errors'] = array_merge($template['errors'], $form->getErrors());

$template['form'] = $form;
$template['data'] = $data;

$latte->render('../templates/nastaveni.latte', $template);

?>
