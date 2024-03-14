<?php
use App\Modelos\Productos;
use App\Modelos\Carrito;
use App\Modelos\DetalleItemCarrito;
$producto = (new Productos())->traerPorId($_GET['id']);
$carrito = (new Carrito())->obtenerCarrito($autenticacion->getId());
if($carrito){
    $item = (new DetalleItemCarrito())->obtenerItem($producto->getProductoId() ,$carrito->getCarritoId());
}
if(!$producto){
    require_once __DIR__ . '/404.php';
} else {
    $producto->cargarCaracteristicas();
?>
<section id="detalleProd" class="container space">
    <div class="page-back">
        <a href="javascript:history.back()">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left-short" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M12 8a.5.5 0 0 1-.5.5H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5a.5.5 0 0 1 .5.5z"/>
        </svg> Atrás
        </a>
    </div>
    <div class="row justify-content-xl-evenly justify-content-center align-items-md-center">
        <div class="col-lg-6 col-md-5 col-xl-4 order-1">
            <h2><?= $producto->getNombre(); ?></h2>
            <p class="negrita">$<?= $producto->getPrecio(); ?></p>
            <p><?= $producto->getDescripcion() ?></p>
            <?php
            if($producto->getCaracteristicas()):
            ?>
            <p class="negrita">Características:</p>
            <ul>
                <?php 
                foreach($producto->getCaracteristicas() as $caracteristica):
                ?>
                <li><?= $caracteristica->getCualidad(); ?></li>
                <?php
                endforeach;
                ?>
            </ul>
            <?php
            endif;
            ?>
            <span class="txt-aclarativo">(3 unidades disponibles)</span>
            <?php
            if($autenticacion->estaAutenticado() && !$autenticacion->esAdmin()):
            ?>
            <form action="acciones/agregar-carrito.php" method="post">
                <input type="hidden" name="id" value="<?= $producto->getProductoId(); ?>">
                <input type="hidden" name="usuario_fk" value="<?= $autenticacion->getUsuario()->getUsuarioId(); ?>">
                <button class="btn" <?php if(isset($item) && $item->getCantidad() >= 3): ?>disabled<?php endif; ?>>
                    Añadir al carrito
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cart3" viewBox="0 0 16 16">
                    <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .49.598l-1 5a.5.5 0 0 1-.465.401l-9.397.472L4.415 11H13a.5.5 0 0 1 0 1H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l.84 4.479 9.144-.459L13.89 4H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                    </svg>
                </button>
            </form>
            <?php
            else:
            ?>
            <span id="txt" class="txt-aclarativo italica">*Para poder comprar el producto, debes registrarte y/o iniciar sesión.</span>
            <?php
            endif;
            ?>
        </div>
        <picture class="col-lg-4 col-md-6">
            <source media="(max-width:767px)" srcset="<?= 'imagenes/mobile-' . $producto->getImagen(); ?>">
            <img src="<?= 'imagenes/' . $producto->getImagen(); ?>" alt="<?= $producto->getImagenDescripcion(); ?>">
        </picture>
    </div>
</section>
<?php
    require_once __DIR__ . '/../layout/info.php';
?>
<?php
}
?>

