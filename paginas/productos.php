<?php
use App\Modelos\Productos;
$c = $_GET['c'];
$productoFactory = new Productos();

$where = [
    ['mostrar', '=', 1],
];

if(!empty($_GET['n'])){
    $where[] = ['nombre', 'LIKE', '%' . $_GET['n'] . '%'];
}
if($c == 1){
    $productos = $productoFactory->todoContenido($where, 3);
} else {
    $where[] = ['categoria_fk', '=', $c];
    $productos = $productoFactory->todoContenido($where, 3);
}
$paginador = $productoFactory->getPaginador();
if($c >= 5){
    require_once __DIR__ . '/404.php';
} else {
?>
<section id="sec-prod" class="container space">
    <h2>Nuestros <span>PRODUCTOS</span></h2>
    <div class="row">
        <div id="info-prod" class="col-lg-3">
            <section class="mb-3">
                <h3 class="negrita">Buscar</h3>
                <form class="buscar" action="index.php" method="get">
                    <input type="hidden" name="s" value="productos">
                    <input type="hidden" name="c" value="<?= $c ?>">
                    <div>
                        <input 
                            type="search" 
                            id="buscar-nombre" 
                            name="n"
                            class="form-control"
                            value="<?= $_GET['n'] ?? null; ?>"
                        >
                    </div>
                    <button id="btnBuscar" type="submit" class="btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                        </svg>
                    </button>
                </form>
            </section>
            <section class="order-lg-1">
                <h3 class="negrita">Categorías</h3>
                <ul>
                    <li><a href="index.php?s=productos&c=1">Ver todo</a></li>
                    <li><a href="index.php?s=productos&c=2">Bombillas</a></li>
                    <li><a href="index.php?s=productos&c=3">Mates</a></li>
                    <li><a href="index.php?s=productos&c=4">Termos</a></li>
                </ul>
            </section>
        </div>
        <div>
            <?php
            if(!$productos):
            ?>
                <p>UPS! No encotramos resultados para tu búsqueda.</p>
            <?php
            endif;
            ?>
            <ul class="row order-lg-1 listado-productos">
            <?php
            foreach($productos as $producto):
            ?>
                <li class="col-md-4 col-lg-3 mb-4 card">
                    <a href="index.php?s=detalle-productos&id=<?= $producto->getProductoId();  ?>">
                    <picture class="card-img-top">
                        <source media="(max-width:767px)" srcset="<?= 'imagenes/mobile-' . $producto->getImagen(); ?>">
                        <img src="<?= 'imagenes/' . $producto->getImagen(); ?>" alt="<?= $producto->getImagenDescripcion(); ?>" class="img-fluid">
                    </picture>
                    <div class="card-body">
                        <h3 class="card-title"><?= $producto->getNombre(); ?></h3>
                        <p class="card-text">$<?= $producto->getPrecio(); ?></p>
                    </div>
                    </a>
                </li>
            <?php
            endforeach;
            ?>  
            </ul>
            <?php
            if(!empty($_GET['n'])){
                $paginador->setUrl('?s=productos&c=' . $c . '&n=' . $_GET['n']);
            } else {
                $paginador->setUrl('?s=productos&c=' . $c);
            }
            $paginador->links();
            ?>
        </div>
    </div>
</section> 
<?php
    require_once __DIR__ . '/../layout/info.php';
?>
<?php
}
?>


 