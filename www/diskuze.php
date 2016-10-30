<?php

  $template['page'] = 'diskuze';

  include_once('../app/include.php');

  $template['javascript'][] = array(
    'source' => '/js/netteForms.js',
  );
  $template['javascript'][] = array(
    'source' => '/js/diskuze.js',
  );
  $template['javascript'][] = array(
    'source' => 'https://www.google.com/recaptcha/api.js?onload=loadPostCaptcha&render=explicit',
    'async' => True,
    'defer' => True,
  );

// TEXY
$texy = new Texy();
TexyConfigurator::safeMode($texy);


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

$rules = array(
  'title' => 'Pravidla diskuze',
  'data-content' => '<p>Chceš-li přispět do diskuze, musíš se řídit následujícími pravidly:</p>
                     <ul>
                       <li>Piš věcně a k tématu (matematika, seminář, soustředění, soutěže, apod.)</li>
                       <li>Chovej se v souladu s dobrými mravy. Chceš-li nám něco vytknout, směle do toho, ale slušnou formou.</li>
                       <li>Na fóru si pokud možno tykáme. Je hezké pozdravit (stačí ahoj).</li>
                       <li>Zkus si přečíst i pár starších příspěvků, aby ses neptal na něco, co se již řešilo.</li>
                       <li>Je-li to jen trochu možné, piš česky a ne cesky.</li>
                       <li>Chceš-li reagovat na nějaký příspěvek, nepiš nové téma, ale klikni na tlačítko v pravém dolním rohu příspěvku.</li>
                     </ul>
                     <p>Příspěvky, které budou porušovat tato pravidla nebo budou odporovat slušným mravům, bez milosti mažeme.</p>',
);

$showForm = array();

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
  ->setRequired('Zadej prosím svůj e-mail, ať tě kdyžtak můžeme kontaktovat přímo.')
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

$agreeLabel = Nette\Utils\Html::el();
$agreeLabel
  ->add(' Přečetl jsem si ')
  ->add(Nette\Utils\Html::el('a')->setText('pravidla diskuze')->addAttributes(array('data-toggle' => 'popover', 'data-trigger' => 'focus', 'data-html' => 'true', 'data-trigger' => 'click', 'data-placement' => 'bottom'))->addAttributes($rules))
  ->add(' a respektuji je.');
$form->addCheckbox('agree', $agreeLabel)
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

$defaultValues = array(
  'parent' => 'zero',
  'name' => '',
  'email' => '',
  'headline' => '',
  'text' => '',
  'agree' => False,
  'captcha' => False,
);

function formPreview($form, $gotValues) {
  $viewData = array();
  $viewData['name'] = $gotValues['name'] != '' ? $gotValues['name'] : '???';
  $viewData['email'] = $gotValues['email'] != '' ? $gotValues['email'] : '???';
  $viewData['headline'] = $gotValues['headline'] != '' ? $gotValues['headline'] : '???';
  $viewData['text'] = $gotValues['text'];
  $form->getElementPrototype()->id = 'form-' . $gotValues['parent'];
  return $viewData;

}

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

// posts
//get total number
$nPerPage = 10;
$count = dibi::query('SELECT count([Id]) FROM [KFE_Board] WHERE [ParentId] = 0')->fetchSingle();
$nPages = ceil($count/$nPerPage);

if(isset($_GET['page'])) {
  if(!(($_GET['page'] > 0) && ($_GET['page'] <= $nPages))) {
    header('Location: /diskuze');
  }
  else {
    $page = $_GET['page'];
  }
}
else {
  $page = 1;
}
$ofset = ($page - 1)*$nPerPage;

// get post
$data[0] = dibi::query('SELECT [Id], [Date], [Title], [Author], [Email], [Message] FROM [KFE_Board] WHERE [ParentId] = 0 ORDER BY [Date] DESC LIMIT %i, %i', $ofset, $nPerPage)->fetchAll();

printPosts($data, 0);


function printPosts(&$data, $id) {
  foreach ($data[$id] as $post) {
    $innerData = dibi::query('SELECT [Id], [Date], [Title], [Author], [Email], [Message] FROM [KFE_Board] WHERE [ParentId] = %i ORDER BY [Date] ASC', $post['Id']);
    if (count($innerData) != 0) {
      $data[$post['Id']] = $innerData->fetchAll();
      printPosts($data, $post['Id']);
    }
  }
}

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
$defaultViewData['html'] = $texy->process($defaultViewData['text']);
$template['defaultViewData'] = $defaultViewData;

$viewData['html'] = $texy->process($viewData['text']);
$template['viewData'] = $viewData;

$template['defaultValues'] = $defaultValues;

$template['form'] = $form;
$template['showForm'] = $showForm;
$template['data'] = $data;

$template['curPage'] = $page;
$template['maxPage'] = $nPages;

$latte->render('../templates/diskuze.latte', $template);

?>
