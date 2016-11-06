<?php

  $template['page'] = 'prihlasit-se';

  include_once('../app/include.php');

  if(isset($_SESSION['id'])) {
    header("Location: /ucet");
  }

  $template['javascript'][] = array(
    'source' => '/js/netteForms.js',
  );

use Nette\Forms\Form;

// form for adding posts

$form = new Form;

$form->addText('email', 'E-mail:')
  ->addRule(Form::MAX_LENGTH, 'S tou délkou e-mailu to zas tak nepřeháněj. %d znaků ti nestačí?', 40);
$form['email']
  ->setType('email')
  ->setRequired('Zadej prosím svůj e-mail.')
  ->addRule(Form::EMAIL, 'Asi máš chybu v e-mailu, takhle by vypadat neměl.');

$form->addPassword('pass', 'Heslo:')
  ->addRule(Form::MAX_LENGTH, 'S tou délkou hesla to zas tak nepřeháněj. %d znaků ti nestačí?', 40)
  ->setRequired('Zapomněl jsi vyplnit heslo.');

$form->addSubmit('send', 'send')
  ->getControlPrototype()
    ->setName('button')
    ->setHtml('<span class="fa fa-key"></span>&nbsp;Přihlásit se')
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
    $query = dibi::query('SELECT [Id], [Name], [Surname] FROM [KA_Competitors] WHERE ([Email] = %s AND [Pass] = %s)', $gotValues['email'], sha1($gotValues['pass']));
    if(count($query) === 1) {
      $template['successes'][] = 'Přihlášení se zdařilo.';
      $userData = $query->fetch();
      $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
      $_SESSION['id'] = $userData['Id'];
      $_SESSION['name'] = $userData['Name'];
      $_SESSION['surname'] = $userData['Surname'];
      header("Location: /ucet");
    }
    else {
      $form->addError('Přihlášení se nezdařilo. Zadal jsi správné heslo?');
    }

  }
}

$template['errors'] = array_merge($template['errors'], $form->getErrors());

$template['form'] = $form;

$latte->render('../templates/login.latte', $template);

?>
