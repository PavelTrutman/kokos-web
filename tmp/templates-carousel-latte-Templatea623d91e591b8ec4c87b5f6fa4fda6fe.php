<?php
// source: ../templates/carousel.latte

class Templatea623d91e591b8ec4c87b5f6fa4fda6fe extends Latte\Template {
function render() {
foreach ($this->params as $__k => $__v) $$__k = $__v; unset($__k, $__v);
// prolog Latte\Macros\CoreMacros
list($_b, $_g, $_l) = $template->initialize('33aab5af9e', 'html')
;
// prolog Latte\Macros\BlockMacros
//
// block carousel
//
if (!function_exists($_b->blocks['carousel'][] = '_lb8a903487b2_carousel')) { function _lb8a903487b2_carousel($_b, $_args) { foreach ($_args as $__k => $__v) $$__k = $__v
?>    <div id="myCarousel" class="carousel slide" data-ride="carousel">
      <!-- Indicators -->
      <ol class="carousel-indicators">

<?php $iterations = 0; foreach ($iterator = $_l->its[] = new Latte\Runtime\CachingIterator($carousel) as $item) { ?>
          <li data-target="#myCarousel" data-slide-to="<?php echo Latte\Runtime\Filters::escapeHtml($iterator->counter - 1, ENT_COMPAT) ?>
"<?php if ($_l->tmp = array_filter(array($item === $carouselActive ? 'active' : NULL))) echo ' class="', Latte\Runtime\Filters::escapeHtml(implode(" ", array_unique($_l->tmp)), ENT_COMPAT), '"' ?>></li>
<?php $iterations++; } array_pop($_l->its); $iterator = end($_l->its) ?>

      </ol>
      <div class="carousel-inner" role="listbox">
        <div<?php if ($_l->tmp = array_filter(array($carouselActive === 'uvod' ? 'active' : NULL, 'item', 'item0'))) echo ' class="', Latte\Runtime\Filters::escapeHtml(implode(" ", array_unique($_l->tmp)), ENT_COMPAT), '"' ?>>
          <div class="container">
            <div class="carousel-caption">
              <h1>Koperníkův Korespondenční Seminář</h1>
              <p>Matematický seminář pro žáky 6. &ndash; 9. tříd základních škol nebo odpovídajících ročníků gymnázií.</p><p>Baví tě matematika nebo logické hádanky?</p>
              <p><a class="btn btn-lg btn-primary" href="#" role="button"><span class="fa fa-lg fa-user-plus"></span>&nbsp;Zaregistruj se!</a></p>
            </div>
          </div>
        </div>

        <div<?php if ($_l->tmp = array_filter(array($carouselActive === 'serie' ? 'active' : NULL, 'item', 'item1'))) echo ' class="', Latte\Runtime\Filters::escapeHtml(implode(" ", array_unique($_l->tmp)), ENT_COMPAT), '"' ?>>
          <div class="container">
            <div class="carousel-caption">
              <h1>Série</h1>
              <p>Pravidelně během školního roku výchází série příkladů, které vyřešíš a pošleš nám je. My je opravíme, ohodnotíme a pošleme ti zpět správné řešení.</p><p>Stáhni si aktuální sérii a pusť se do řešení!</p>
              <p><a class="btn btn-lg btn-primary" href="/serie/<?php echo Latte\Runtime\Filters::escapeHtml(Latte\Runtime\Filters::safeUrl($lastSerie), ENT_COMPAT) ?>" role="button"><span class="fa fa-lg fa-download"></span>&nbsp;Aktuální série</a></p>
            </div>
          </div>
        </div>

        <div<?php if ($_l->tmp = array_filter(array($carouselActive === 'vysledky' ? 'active' : NULL, 'item', 'item2'))) echo ' class="', Latte\Runtime\Filters::escapeHtml(implode(" ", array_unique($_l->tmp)), ENT_COMPAT), '"' ?>>
          <div class="container">
            <div class="carousel-caption">
              <h1>Výsledky</h1>
              <p>Porovnej si svoje znalosti se znalostmi tvých vrstevníků a podívej se, které příklady ti jdou a kde naopak máš více zabrat.</p><p>Zjisti, jak jsi na tom se svými znalostmi!</p>
              <p><a class="btn btn-lg btn-primary" href="/vysledky" role="button"><span class="fa fa-lg fa-trophy"></span>&nbsp;Výsledky</a></p>
            </div>
          </div>
        </div>
      </div>


      <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
      </a>
      <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
      </a>
    </div><!-- /.carousel -->
<?php
}}

//
// end of blocks
//

// template extending

$_l->extends = "layout.latte"; $_g->extended = TRUE;

if ($_l->extends) { ob_start();}

//
// main template
// ?>


<?php if ($_l->extends) { ob_end_clean(); return $template->renderChildTemplate($_l->extends, get_defined_vars()); }
call_user_func(reset($_b->blocks['carousel']), $_b, get_defined_vars()) ; 
}}