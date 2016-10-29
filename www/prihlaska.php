<?php

  $template['page'] = '';

  include_once('../app/include.php');

  $template['javascript'][] = array(
    'source' => './js/netteForms.js',
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
    ->setHtml('<span class="fa fa-user-plus"></span>&nbsp;Zaregistrovat se')
    ->addClass('btn-primary');

Nette\Forms\Rules::$defaultMessages[':selectBoxValid'] = 'Vybral jsi školu, která neexistuje. Tak si vyber tu správnou.';

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
      // check e-mail, must not exists
      if(count(dibi::query('SELECT [Id] FROM [KA_Competitors] WHERE [Email] = %s', $gotValues['email'])) == 0) {

        // prepare and insert new competitor
        $toDB['Name'] = $gotValues['name'];
        $toDB['Surname'] = $gotValues['surname'];
        $toDB['Street'] = $gotValues['street'];
        $toDB['NumberO'] = $gotValues['numberO'];
        $toDB['NumberD'] = $gotValues['numberD'];
        $toDB['City'] = $gotValues['city'];
        $toDB['ZipCode'] = $gotValues['zip'];
        $toDB['ZipCode'] = $gotValues['zip'];
        $toDB['Grade'] = $gotValues['year'];
        $toDB['SchoolId'] = $gotValues['school'];
        $toDB['Shipping'] = $gotValues['shipping'];
        $toDB['Phone'] = $gotValues['phone'];
        $toDB['Email'] = $gotValues['email'];
        $pass = substr(md5(rand().rand()), 0, 10);
        $toDB['Pass'] = sha1($pass);
        $toDB['Approved'] = 0;
        $toDB['CreatedBy'] = 'self';
        $toDB['CreatedDateTime'] = date('Y-m-d H:i:s');
        $toDB['LastUpdatedBy'] = 'self';
        $toDB['LastUpdatedDateTime'] = $toDB['CreatedDateTime'];

        if(dibi::query('INSERT INTO [KA_Competitors]', $toDB)) {
          // send mail
          $to = $toDB['Email'];
          $subject = "KoKoS: Registrace do semináře";
                     
          $message = '<img style="float: right; margin-left: 20px" src="http://kokos.gmk.cz/images/logo.png">
                      <p>Milý řešiteli, </p>
                      <p style="text-align: justify;">vítej v KoKoSu! Právě jsi se přihlásil do <b>Koperníkova Korespondenčního Semináře</b>. Po přihlášení ke svému účtu na adrese <a href="http://kokos.gmk.cz/login">kokos.gmk.cz/login</a> můžeš jednoduše sledovat, jak si v semináři vedeš, kolik bodů jsi dostal a novinkou tohoto roku je: <b>odesílání řešení elektronicky</b>, tedy již žádné cesty na poštu!</p>
                      <p>Tvé přihlašovací údaje jsou:</p>
                      <table style="margin-left: 20px;">
                        <tr>
                          <th style="text-align: right">Jméno:</th><td>'  . $toDB['Name'] .  '</td>
                        </tr>
                        <tr>
                          <th style="text-align: right">Příjmení:</th><td>'  . $toDB['Surname'] .  '</td>
                        </tr>
                        <tr>
                          <th style="text-align: right">E-mail:</th><td><a href="mailto:' . $toDB['Email'] . '">' . $toDB['Email'] . '</td>
                        </tr>
                        <tr>
                          <th style="text-align: right">Heslo:</th><td>'  . $pass .  '</td>
                        </tr>
                      </table>
                      <p style="text-align: justify;">Všechny své údaje si můžeš po přihlášení změnit. Kdyby jsi si nevěděl s něčím rady, všechny potřebné informace najdeš na našem webu <a href="http://kokos.gmk.cz">kokos.gmk.cz</a> nebo nám zkrátka napiš e-mail na adresu <a href="mailto:gmkkokos@seznam.cz">gmkkokos@seznam.cz</a>.</p>
                      <p style="text-align: justify;">Ať se ti daří nejen v KoKoSe přejí<br><span style="float: right; font-style: oblique;">Organizátoři</span></p><br>
                      <hr>
                      <p style="color: grey">Na tento e-mail neodpovídej, jde pouze o automaticky vygenerovanou zprávu o novém účtu.</p>';

          $additional_headers = "MIME-Version: 1.0" . "\r\n";
          $additional_headers .= "Content-type: text/html; charset=UTF-8" . "\r\n";
          $additional_headers .= "From: KoKoS <no-reply@kokos.gmk.cz>" . "\r\n";
          $additional_headers .= "X-Sender: <no-reply@kokos.gmk.cz>" . "\r\n";
          $additional_headers .= "Reply-To: KoKoS <gmkkokos@seznam.cz>" . "\r\n";
          $additional_headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
          $additional_headers .= "X-Priority: 2 (Normal)" . "\r\n";
          $additional_headers .= "Return-Path: gmkkokos@seznam.cz" . "\r\n";

          mail($to, '=?UTF-8?B?'.base64_encode($subject).'?=', $message, $additional_headers);

          $template['data'] = $toDB;
          $template['successes'][] = 'Byl jsi úspěšně zaregistrován do semináře!';
          $latte->render('../templates/prihlaska-sent.latte', $template);
          exit(0);
        }
        else {
          $form->addError('Odeslání formuláře se nezdařilo. Zkus to prosím za chvíli znovu.');
        }
      }
      else {
        $form->addError('Účet se stejným e-mailem již existuje! Místo vytvoření nového účtu použij účet stávající.');
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

$template['errors'] = array_merge($template['errors'], $form->getErrors());

$form['captcha']->setValue(False);

$template['form'] = $form;

$latte->render('../templates/prihlaska.latte', $template);

?>
