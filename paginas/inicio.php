<?php
use App\Modelos\Productos;
$productos = (new Productos())->mostrarYrecomendados(null);
require_once __DIR__ . '/../layout/portada.php';
?>
<section class="container space">
    <div class="row justify-content-lg-center">
        <div class="col-md-11 col-xl-10">
            <h2>Acerca de nosotros,</h2>
            <span>NUESTRA HISTORIA.</span>
            <p><span class="negrita">Tú mate</span> nace de las ganas de querer acercarle a todos los amantes del mate insumos característicos de la cultura Argentina, priorizando la <span class="negrita">calidad</span> y el <span class="negrita">precio</span>.</p> 
            <p>Nos enfocamos en los detalles y en la atención personalizada para poder brindar un servicio cálido y eficiente. <br> En Tú mate ofrecemos productos que conllevan historias, leyendas, buenos momentos y costumbres de nuestros pasado, presente y futuro.</p>
         </div>
    </div>
</section>
<section id="recomendado" class="container">
    <div class="row">
        <div class="col-lg-12">
            <h2>Nuestros <span>RECOMENDADOS</span></h2>
        </div>
    </div>
    <ul class="row p-0 justify-content-center justify-content-md-start">
        <?php 
         foreach($productos as $prodRecomendado):
        ?>
        <li class="col-lg-3 col-md-5 card mb-4">
            <a href="index.php?s=detalle-productos&id=<?= $prodRecomendado->getProductoId();  ?>">
            <picture class="card-img-top">
                <source media="(max-width:767px)" srcset="<?= 'imagenes/mobile-' . $prodRecomendado->getImagen(); ?>">
                <img src="<?= 'imagenes/' . $prodRecomendado->getImagen(); ?>" alt="<?= $prodRecomendado->getImagenDescripcion(); ?>" class="img-fluid">
            </picture>
            <div class="card-body">
            <h3 class="card-title"><?= $prodRecomendado->getNombre(); ?></h3>
                <p class="card-text">$<?= $prodRecomendado->getPrecio(); ?></p>
            </div>
            </a>
        </li>
        <?php 
          endforeach; 
        ?>
    </ul>
    <a href="index.php?s=productos&c=1" class="btn marronOsc">Ir a la tienda<i class="bi bi-arrow-right-short"></i></a>
</section>
<aside id="img-mate">
    <div>
        <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" fill="currentColor" class="bi bi-quote" viewBox="0 0 16 16">
        <path d="M12 12a1 1 0 0 0 1-1V8.558a1 1 0 0 0-1-1h-1.388c0-.351.021-.703.062-1.054.062-.372.166-.703.31-.992.145-.29.331-.517.559-.683.227-.186.516-.279.868-.279V3c-.579 0-1.085.124-1.52.372a3.322 3.322 0 0 0-1.085.992 4.92 4.92 0 0 0-.62 1.458A7.712 7.712 0 0 0 9 7.558V11a1 1 0 0 0 1 1h2Zm-6 0a1 1 0 0 0 1-1V8.558a1 1 0 0 0-1-1H4.612c0-.351.021-.703.062-1.054.062-.372.166-.703.31-.992.145-.29.331-.517.559-.683.227-.186.516-.279.868-.279V3c-.579 0-1.085.124-1.52.372a3.322 3.322 0 0 0-1.085.992 4.92 4.92 0 0 0-.62 1.458A7.712 7.712 0 0 0 3 7.558V11a1 1 0 0 0 1 1h2Z"/>
        </svg>
        <p>Lo que no decimos con palabras, <span>LO DECIMOS CON MATES.</span></p>
    </div>
</aside>
<?php
    require_once __DIR__ . '/../layout/info.php';
?>