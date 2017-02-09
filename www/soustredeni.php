<?php

  $template['page'] = 'soustredeni';

  include_once('../app/include.php');

  $template['javascript'][] = array(
    'source' => '/js/netteForms.js',
  );
  $template['javascript'][] = array(
    'source' => 'https://www.google.com/recaptcha/api.js?onload=loadCaptcha&render=explicit',
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

// data to show
$camp = dibi::query('SELECT [Id], [Name], [Description], [OpenDate], [ClosureDate] FROM [KA_Camps] ORDER BY [ClosureDate] DESC LIMIT 1')->fetch();
$template['camp'] = $camp;
if($camp) {
  $campId = $camp['Id'];
  $openDate = new DateTime($camp['OpenDate']);
  $closureDate = new DateTime($camp['ClosureDate']);
  $closureDate = $closureDate->modify('tomorrow');
  $nowDate = new DateTime();
  $template['openDate'] = $openDate;
  $template['closureDate'] = $closureDate;
  $template['nowDate'] = $nowDate;
}


// form for signing up for the camp
$form = new Form;

$form->addText('name', 'Jméno:')
  ->setAttribute('placeholder', '')
  ->addRule(Form::MAX_LENGTH, 'S tou délkou jména to zas tak nepřeháněj. %d znaků ti nestačí?', 40)
  ->setRequired('Zadej prosím svoje jméno, ať víme, kdo jsi.');

$form->addText('surname', 'Přijmení:')
  ->setAttribute('placeholder', '')
  ->addRule(Form::MAX_LENGTH, 'S tou délkou příjmení to zas tak nepřeháněj. %d znaků ti nestačí?', 40)
  ->setRequired('Zadej prosím svoje příjmení, ať víme, kdo jsi.');

$form->addTextArea('address', 'Adresa domů:')
  ->setAttribute('placeholder', '')
  ->setAttribute('rows', 3)
  ->addRule(Form::MAX_LENGTH, 'S tou délkou adresy to zas tak nepřeháněj. %d znaků ti nestačí?', 100)
  ->setRequired('Zadej prosím svoji adresu domů, ať víme, odkud jsi.');

$form->addRadioList('year', 'Ročník ZŠ:', $years)
  ->setRequired('Vyber svůj ročník na základní škole.')
  ->getSeparatorPrototype()->setName(NULL);
$form['year']->getItemLabelPrototype()->addClass('radio-inline');

$form->addText('identification', 'Rodné číslo:')
  ->setAttribute('placeholder', '')
  ->addRule(Form::PATTERN, 'Rodné číslo musí být ve tvaru 123456/7890.', '[0-9]{6}/[0-9]{4}')
  ->setRequired('Vyplň prosím svoje rodné číslo.');

$form->addText('email', 'E-mail:')
  ->addRule(Form::MAX_LENGTH, 'S tou délkou e-mailu to zas tak nepřeháněj. %d znaků ti nestačí?', 40);
$form['email']
  ->setType('email')
  ->setRequired('Zadej prosím svůj e-mail.')
  ->addRule(Form::EMAIL, 'Asi máš chybu v e-mailu, takhle by vypadat neměl.');

$form->addText('phone', 'Telefon:')
  ->setAttribute('placeholder', '')
  ->addRule(Form::LENGTH, 'Telefonní číslo musí mít právě %d znaků.', 9)
  ->addRule(Form::INTEGER, 'Telefonní číslo musí být opravdu číslo.')
  ->setRequired('Zadej prosím svůje telefonní číslo, ať tě kdyžtak můžeme kontaktovat přímo.');

$form->addText('phoneParrents', 'Telefon na rodiče:')
  ->setAttribute('placeholder', '')
  ->addRule(Form::LENGTH, 'Telefonní číslo na rodiče musí mít právě %d znaků.', 9)
  ->addRule(Form::INTEGER, 'Telefonní číslo na rodiče musí být opravdu číslo.')
  ->setRequired('Zadej prosím telefonní číslo na svoje rodiče, ať tě můžeme kontaktovat, kdyby se něco přihodilo.');

$form->addTextArea('healthConstraints', 'Zdravotní omezení:')
  ->setAttribute('placeholder', '')
  ->setAttribute('rows', 3)
  ->addRule(Form::MAX_LENGTH, 'S tou délkou popisu zdravotních omezení to zas tak nepřeháněj. %d znaků by ti mohlo stačit?', 255);

$form->addTextArea('hobby', 'Zájmy a koníčky:')
  ->setAttribute('placeholder', '')
  ->setAttribute('rows', 3)
  ->addRule(Form::MAX_LENGTH, 'S tou délkou popisu svých koníčků to zas tak nepřeháněj. %d znaků by ti mohlo stačit?', 255);

$form->addCheckbox('captcha', 'Captcha.')
  ->addRule(Form::EQUAL, 'Vyplň prosím captchu.', TRUE);

$form->addSubmit('send', 'send')
  ->getControlPrototype()
    ->setName('button')
    ->setHtml('<span class="fa fa-child"></span>&nbsp;Přihlásit se na soustředění')
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

  // check google captcha
  $recaptcha = new \ReCaptcha\ReCaptcha("6LeGrgoTAAAAAAkVNJNQSyCEySeO2Hs7bAu4z7bw");
  $httpData = $form->getHttpData();
  if(isset($httpData['g-recaptcha-response']) && $httpData['g-recaptcha-response'] != '') {
    $recaptchaResponse = $httpData['g-recaptcha-response'];
    $recaptcha = $recaptcha->verify($httpData['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
    if($recaptcha->isSuccess()) {

      // check dates
      if($camp && ($openDate < $nowDate) && ($closureDate > $nowDate)) {
        $toDB['CampId'] = $campId;
        $toDB['Name'] = $gotValues['name'];
        $toDB['Surname'] = $gotValues['surname'];
        $toDB['Address'] = $gotValues['address'];
        $toDB['Year'] = $gotValues['year'];
        $toDB['Identification'] = $gotValues['identification'];
        $toDB['Email'] = $gotValues['email'];
        $toDB['Phone'] = $gotValues['phone'];
        $toDB['PhoneParrents'] = $gotValues['phoneParrents'];
        $toDB['HealthConstraints'] = $gotValues['healthConstraints'];
        $toDB['Hobby'] = $gotValues['hobby'];
        $toDB['SignedUp'] = date("Y-m-d H:i:s");

        $result = dibi::query('INSERT INTO [KA_CampsPeople]', $toDB);
        if($result) {
          $template['successes'][] = 'Byl jsi úspěšně přihlášen na soustředění.';
        }
        else {
          $form->addError('Odeslání formuláře se nezdařilo. Zkus to prosím za chvíli znovu.');
        }
      }
      else {
        $form->addError('Žádné soustředění není otevřeno pro přihlašování.');
      }
    }
    else {
      $form->addError('Špatně jsi vyplnil captchu. Zkus to znovu.');
    }
  }
  else {
    $form->addError('Nevyplnil jsi captchu. Příště to prosím nezapomeň udělat.');
  }

}

if($camp) {
  $people = dibi::query('SELECT [Id], [Name], [Surname], [Year] FROM [KA_CampsPeople] WHERE [CampId] = %i ORDER BY [Surname] ASC', $campId)->fetchAll();
  $template['people'] = $people;
}

$template['errors'] = array_merge($template['errors'], $form->getErrors());

$template['form'] = $form;

$latte->render('../templates/soustredeni.latte', $template);

?>
