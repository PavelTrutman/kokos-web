<?php

  $template['page'] = '';

  include_once('../app/include.php');

  $template['javascript'][] = array(
    'source' => './js/netteForms.js',
  );
  $template['javascript'][] = array(
    'source' => 'https://www.google.com/recaptcha/api.js?onload=loadPostCaptcha&render=explicit',
    'async' => True,
    'defer' => True,
  );

use Nette\Forms\Form;

$years = array(
  5 => '5.',
  6 => '6.',
  7 => '7.',
  8 => '8.',
  9 => '9.',
);

$shippings = array(
  1 => 'Domů',
  2 => 'Do školy',
);


$result = dibi::query('SELECT [Id], [Name], [City] FROM [KA_Schools] ORDER BY [Name] ASC');
$schools = $result->fetchAssoc('Id');

// form

$form = new Form;

$form->addText('name', 'Jméno:')
  ->setAttribute('placeholder', '')
  ->addRule(Form::MAX_LENGTH, 'S tou délkou jména to zas tak nepřeháněj. %d znaků ti nestačí?', 40)
  ->setRequired('Zadej prosím svoje jméno, ať víme, kdo jsi.');

$form->addText('surname', 'Přijmení:')
  ->setAttribute('placeholder', '')
  ->addRule(Form::MAX_LENGTH, 'S tou délkou příjmení to zas tak nepřeháněj. %d znaků ti nestačí?', 40)
  ->setRequired('Zadej prosím svoje příjmení, ať víme, kdo jsi.');

$form->addText('street', 'Ulice:')
  ->setAttribute('placeholder', '')
  ->addRule(Form::MAX_LENGTH, 'S tou délkou názvu ulice to zas tak nepřeháněj. %d znaků ti nestačí?', 40)
  ->setRequired('Vyplň prosím jméno ulice, na které bydlíš, ať víme, kam ti máme posílat sérii.');

$form->addText('numberO', 'Číslo orientační:')
  ->setAttribute('placeholder', '')
  ->addCondition(Form::FILLED)
    ->addRule(Form::INTEGER, 'Číslo orientační musí být opravdu číslo!')
    ->addRule(Form::MAX_LENGTH, 'Číslo orientační asi není tak dlouhé. Musí ti stačit %d znaků.', 10);

$form->addText('numberD', 'Číslo popisné:')
  ->setAttribute('placeholder', '')
  ->addCondition(Form::FILLED)
    ->addRule(Form::INTEGER, 'Číslo popisné musí být opravdu číslo!')
    ->addRule(Form::MAX_LENGTH, 'Číslo popisné asi není tak dlouhé. Musí ti stačit %d znaků.', 10);

$form['numberO']
  ->addConditionOn($form['numberD'], ~Form::FILLED)
    ->setRequired('Vyplň prosím orientační číslo, ať víme, kam ti máme posílat sérii.');

$form['numberD']
  ->addConditionOn($form['numberO'], ~Form::FILLED)
    ->setRequired('Vyplň prosím číslo popisné, ať víme, kam ti máme posílat sérii.');

$form->addText('city', 'Město:')
  ->setAttribute('placeholder', '')
  ->addRule(Form::MAX_LENGTH, 'S tou délkou názvu města to zas tak nepřeháněj. %d znaků ti nestačí?', 40)
  ->setRequired('Vyplň prosím město, ve kterém bydlíš, ať víme, kam ti máme posílat sérii.');

$form->addText('zip', 'PSČ:')
  ->setAttribute('placeholder', '')
  ->addRule(Form::INTEGER, 'PSČ musí být číslo!')
  ->addRule(Form::LENGTH, 'PSČ musí mít právě %d znaků.', 5)
  ->setRequired('Vyplň prosím svoje PSČ, ať víme, kam ti máme posílat sérii.');

$form->addText('phone', 'Telefon:')
  ->setAttribute('placeholder', '')
  ->addRule(Form::LENGTH, 'Telefonní číslo musí mít právě %d znaků.', 9)
  ->addRule(Form::INTEGER, 'Telefonní číslo musí být opravdu číslo.')
  ->setRequired('Zadej prosím svůje telefonní číslo, ať tě kdyžtak můžeme kontaktovat přímo.');

$form->addText('email', 'E-mail:')
  ->setAttribute('placeholder', '')
  ->addRule(Form::MAX_LENGTH, 'S tou délkou e-mailu to zas tak nepřeháněj. %d znaků ti nestačí?', 40)
  ->setType('email')
  ->setRequired('Zadej prosím svůj e-mail, ať tě kdyžtak můžeme kontaktovat přímo.')
  ->addRule(Form::EMAIL, 'Asi máš chybu v e-mailu, takhle by vypadat neměl.');

$form->addRadioList('year', 'Ročník ZŠ:', $years)
  ->setRequired('Vyber svůj ročník na základní škole.')
  ->getSeparatorPrototype()->setName(NULL);
$form['year']->getItemLabelPrototype()->addClass('radio-inline');

$form->addRadioList('shipping', 'Zasílat:', $shippings)
  ->setRequired('Vyber si prosím, kam chceš zasílat sérii.')
  ->getSeparatorPrototype()->setName(NULL);
$form['shipping']->getItemLabelPrototype()->addClass('radio-inline');

$form->addSelect('school', 'Škola:', array_map(function($val) {return $val['Name'] . ', ' . $val['City'];}, $schools))
  ->setRequired('Vyber prosím svoji školu ze seznamu. Pokud jsi tam svoji školu nenašel, napiš nám a my ji do seznamu přidáme.');

$form->addCheckbox('captcha', 'Captcha.')
  ->addRule(Form::EQUAL, 'Vyplň prosím captchu.', TRUE);

$form->addSubmit('send', 'send')
  ->getControlPrototype()
    ->setName('button')
    ->setHtml('<span class="fa fa-paper-plane"></span>&nbsp;Odeslat')
    ->addClass('btn-primary');

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
  elseif ($control instanceof Nette\Forms\Controls\RadioList) {
  }
  elseif ($control instanceof Nette\Forms\Controls\SelectBox) {
    $control->getControlPrototype()->addClass('form-control');
    $control->getControlPrototype()->addAttributes(array('size' => '10'));
  }
  else {
    var_dump($control);
  }
}

$defaultValues = array(
  'name' => '',
  'email' => '',
  'headline' => '',
  'text' => '',
  'agree' => False,
  'captcha' => False,
);

// send form parsing
if ($form->isSuccess()) {
  $gotValues = $form->getValues(True);
  if($form['view']->submittedBy) {
    if(($gotValues['parent'] === '0') || count(dibi::query('SELECT [Id] FROM [KFE_Board] WHERE [Id] = %i', $gotValues['parent'])) > 0) {
      $showForm[$gotValues['parent']] = True;
      $viewData = formPreview($form, $gotValues);
    }
    else {
      $form->addError('Formulář nebyl korektně vyplněn. Zkus stránku znovu načíst a formulář odeslat znovu.');
    }
  }
  elseif($form['send']) {
    // check google captcha
    $gotValues = $form->getValues(True);
    $recaptcha = new \ReCaptcha\ReCaptcha("6LeGrgoTAAAAAAkVNJNQSyCEySeO2Hs7bAu4z7bw");
    $httpData = $form->getHttpData();
    if(isset($httpData['g-recaptcha-response']) && $httpData['g-recaptcha-response'] != '') {
      $recaptchaResponse = $httpData['g-recaptcha-response'];
      $recaptcha = $recaptcha->verify($httpData['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
      if($recaptcha->isSuccess()) {
        // check parentId, must exists
        if(($gotValues['parent'] === '0') || count(dibi::query('SELECT [Id] FROM [KFE_Board] WHERE [Id] = %i', $gotValues['parent'])) > 0) {
          if(dibi::query('INSERT INTO [KFE_Board] ([ParentId], [Date], [Title], [Author], [Email], [ShowEmail], [Message], [Agent]) VALUES (%i, %t, %s, %s, %s, %b, %s, %s)', $gotValues['parent'], time(), $gotValues['headline'], $gotValues['name'], $gotValues['email'], 1, $texy->process($gotValues['text']), $_SERVER['HTTP_USER_AGENT'])) {
            $template['successes'][] = 'Tak a je to. Tvůj příspěvěk byl zveřejněn.';
          }
          else {
            $form->addError('Odeslání formuláře se nezdařilo. Zkus to prosím za chvíli znovu.');
            $showForm[$gotValues['parent']] = True;
            $viewData = formPreview($form, $gotValues);
          }
        }
        else {
          $form->addError('Formulář nebyl korektně vyplněn. Zkus stránku znovu načíst a formulář odeslat znovu.');
        }
      }
      else {
        $form->addError('Špatně jsi vyplnil captchu. Zkus to znovu.');
        $showForm[$gotValues['parent']] = True;
        $viewData = formPreview($form, $gotValues);
      }
    }
    else {
      $form->addError('Nevyplnil jsi captchu. Příště to prosím nezapomeň udělat.');
      $showForm[$gotValues['parent']] = True;
      $viewData = formPreview($form, $gotValues);
    }
  }
}
else {
  $gotValues = $form->getValues(True);
  if(($gotValues['parent'] === '0') || count(dibi::query('SELECT [Id] FROM [KFE_Board] WHERE [Id] = %i', $gotValues['parent'])) > 0) {
    $showForm[$gotValues['parent']] = True;
    $viewData = formPreview($form, $gotValues);
  }
}

$template['errors'] = array_merge($template['errors'], $form->getErrors());

$form['captcha']->setValue(False);

function resetForm($form, $defaultValues) {
  $form['parent']->setValue($defaultValues['parent']);
  $form['name']->setValue($defaultValues['name']);
  $form['email']->setValue($defaultValues['email']);
  $form['headline']->setValue($defaultValues['headline']);
  $form['text']->setValue($defaultValues['text']);
  $form['agree']->setValue($defaultValues['agree']);
  $form['captcha']->setValue($defaultValues['captcha']);
  $form->getElementPrototype()->id = 'form-zero';
}

$defaultViewData = $formVals;
$template['defaultViewData'] = $defaultViewData;

$template['viewData'] = $viewData;

$template['defaultValues'] = $defaultValues;

$template['form'] = $form;
$template['showForm'] = $showForm;
$template['data'] = $data;

$template['curPage'] = $page;
$template['maxPage'] = $nPages;

$latte->render('../templates/prihlaska.latte', $template);

?>
