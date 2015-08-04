<?php
// source: ../templates/diskuze.latte

class Template0043e66e32589d30114c0b4375505d3e extends Latte\Template {
function render() {
foreach ($this->params as $__k => $__v) $$__k = $__v; unset($__k, $__v);
// prolog Latte\Macros\CoreMacros
list($_b, $_g, $_l) = $template->initialize('c91fbd26ec', 'html')
;
// prolog Latte\Macros\BlockMacros
//
// block content
//
if (!function_exists($_b->blocks['content'][] = '_lbd686215ae3_content')) { function _lbd686215ae3_content($_b, $_args) { foreach ($_args as $__k => $__v) $$__k = $__v
?><h1>Diskuze</h1>
  <div class="diskuze">

<?php $id = 0 ;$level = 0 ?>

<?php call_user_func(reset($_b->blocks['item']), $_b, get_defined_vars())  ?>

  </div>
<?php
}}

//
// block item
//
if (!function_exists($_b->blocks['item'][] = '_lbdb38147dad_item')) { function _lbdb38147dad_item($_b, $_args) { foreach ($_args as $__k => $__v) $$__k = $__v
;$innerLevel = $level ;$iterations = 0; foreach ($iterator = $_l->its[] = new Latte\Runtime\CachingIterator($data[$id]) as $post) { ?>
      <div class="panel panel-default" id="<?php echo Latte\Runtime\Filters::escapeHtml($post['Id'], ENT_COMPAT) ?>
" style="margin-left: <?php echo Latte\Runtime\Filters::escapeHtml(Latte\Runtime\Filters::escapeCss($innerLevel*50), ENT_COMPAT) ?>px">
        <div class="panel-heading">
          <h2 class="panel-title"><?php echo Latte\Runtime\Filters::escapeHtml($post['Title'], ENT_NOQUOTES) ?>
</h2><span class="date"><em>Odesláno dne <?php echo Latte\Runtime\Filters::escapeHtml(date('j. n. Y v G:i', strtotime($post['Date'])), ENT_NOQUOTES) ?></em></span>
        </div>
        <div class="panel-body">
          <?php echo $post['Message'] ?>

        </div>
        <div class="panel-footer">
          <strong><?php echo Latte\Runtime\Filters::escapeHtml($post['Author'], ENT_NOQUOTES) ?>
</strong><em class="email"><?php echo Latte\Runtime\Filters::escapeHtml($post['Email'], ENT_NOQUOTES) ?></em>
          </div>
      </div>
<?php if (isset($data[$post['Id']])) { call_user_func(reset($_b->blocks['item']), $_b, array($id = $post['Id'], $level = $innerLevel + 1) + get_defined_vars()) ;} $iterations++; } array_pop($_l->its); $iterator = end($_l->its) ;
}}

//
// end of blocks
//

// template extending

$_l->extends = "carousel.latte"; $_g->extended = TRUE;

if ($_l->extends) { ob_start();}

//
// main template
// ?>

<?php if ($_l->extends) { ob_end_clean(); return $template->renderChildTemplate($_l->extends, get_defined_vars()); }
call_user_func(reset($_b->blocks['content']), $_b, get_defined_vars()) ; 
}}