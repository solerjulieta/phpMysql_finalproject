<?php
use App\Modelos\Carrito;
$autenticacion = new \App\Auth\Autenticacion();
$carrito = (new Carrito())->obtenerCarrito($autenticacion->getId());
if($carrito){
    $carrito_fk = $carrito->getCarritoId();
    $items = (new Carrito())->cargarItems($carrito->getCarritoId());
    $subtotal = (new \App\Modelos\DetalleItemCarrito())->obtenerTotal($carrito->getCarritoId());
}
$dataForm = $_SESSION['data_form'] ?? []; 
?>
<section id="carrito" class="container space">
    <h2>Mi <span>CARRITO</span></h2>
    <?php
    if($carrito):
    ?>
    <div class="row contenedor">
        <div class="carrito-prods col-lg-6">
        <?php
        foreach($items as $item):
        ?>
            <div class="row info-carrito">
                <div class="col-lg-2 img-carrito">
                    <picture>
                        <source media="(max-width:767px)" srcset="<?= 'imagenes/mobile-' . $item->getImagen(); ?>">
                        <img src="<?= './imagenes/' . $item->getImagen(); ?>" alt="<?= $item->getImagenDescripcion(); ?>" class="img-fluid">
                    </picture>
                </div>
                <ul>
                    <li><h3><?= $item->getNombre(); ?></h3></li>
                    <li class="negrita">$<?= $item->getSubtotal(); ?></li>
                    <li>
                        <span>Cantidad:</span>
                        <div class="campo-cantidad">
                            <form action="acciones/decrementar-cantidad.php" method="post" class="decrementar">
                                <input type="hidden" name="id" value="<?= $item->getProductoFk(); ?>">
                                <button class="btn" <?php if($item->getCantidad()==1): ?>disabled<?php endif; ?>>-</button>
                            </form> 
                            <input name="cantidad" class="form-control cantidad" value="<?= $dataForm['cantidad'] ?? $item->getCantidad(); ?>" disabled>
                            <form action="acciones/incrementar-cantidad.php" method="post" class="incrementar">
                                <input type="hidden" name="id" value="<?= $item->getProductoFk(); ?>">
                                <button class="btn" <?php if($item->getCantidad()==3): ?>disabled<?php endif; ?>>+</button>
                            </form>
                        </div>
                    </li>
                    <li>
                        <form action="acciones/eliminar-producto.php" method="post">
                            <input type="hidden" name="carrito_fk" value="<?= $item->getCarritoFk(); ?>">
                            <input type="hidden" name="producto_fk" value="<?= $item->getProductoFk(); ?>">
                            <button class="btn delete" type="submit">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5Zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5Zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6Z"/>
                                <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1ZM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118ZM2.5 3h11V2h-11v1Z"/>
                                </svg>
                                Eliminar
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        <?php
        endforeach;
        ?>
        </div>
        <div class="pago col-lg-4">
            <?php
            foreach($subtotal as $total):
            ?>
            <div class="precio">
                <p>Subtotal: <span> $<?= $total ?> </span></p>
                <p>Total: <span>$<?= $total ?></span></p>
            </div>
            <?php
            endforeach;
            ?>
            <form action="acciones/crear-orden.php" method="post">
                <?php 
                foreach($items as $item):
                ?>
                <input type="hidden" name="producto_fk[]" value="<?= $item->getProductoFk(); ?>">
                <input type="hidden" name="cantidad[]" value="<?= $item->getCantidad(); ?>">
                <input type="hidden" name="subtotal[]" value="<?= $item->getSubtotal(); ?>">
                <?php
                endforeach;
                ?>
                <input type="hidden" name="total" value="<?= $total ?>">
                <button class="btn comprar" type="submit">Finalizar Compra</button>
            </form>
            <a href="index.php?s=productos&c=1" class="btn vermas">Elegir más productos</a>
            <form action="acciones/vaciar-carrito.php" method="post">
                <input type="hidden" name="id" value="<?= $carrito->getUsuarioFk(); ?>">
                <button class="btn vaciar" type="submit">Vaciar carrito</button>
            </form>
        </div>
    </div>
    <?php
    else:
    ?>
    <div class="contenedor">
        <p class="negrita">Parece que no hay productos en el carrito.</p>
        <p class="italica">*Ruído de mate*</p>
        <a href="index.php?s=productos&c=1" id="tienda" class="btn marronOsc">Ir a la tienda<i class="bi bi-arrow-right-short"></i></a>
    </div>
    <?php
    endif;
    ?>
</section>