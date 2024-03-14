<?php

use App\Auth\Autenticacion;
use App\Modelos\Carrito;
use App\Modelos\DetalleItemCarrito;
use App\Modelos\Productos;

require_once __DIR__ . '/../bootstrap/init.php';

$autenticacion = new Autenticacion();
if(!$autenticacion->estaAutenticado()){
    $_SESSION['mensaje_error'] = '<i class="bi bi-exclamation-square"></i> Para modificar la cantidad del producto tenés que iniciar sesión.';
    header("Location: ../index.php?s=inicio-sesion");
    exit;
}
if($autenticacion->esAdmin()){
    $_SESSION['mensaje_error'] = '<i class="bi bi-exclamation-square"></i> Para modificar la cantidad del producto tenés que iniciar sesión como usuario común.';
    header("Location: ../index.php?s=inicio-sesion");
    exit;
}

$producto_fk = $_POST['id'];
$producto = (new Productos())->traerPorId($producto_fk);
if(!$producto){
    $_SESSION['mensaje_error'] = '<i class="bi bi-exclamation-square"></i> No existe el producto que se está tratando de quitar.';
    header("Location: ../index.php?s=productos&c=1");
    exit;
}

$carrito = (new Carrito())->obtenerCarrito($autenticacion->getId());
if($carrito){
    $itemsCarrito = (new Carrito())->cargarItems($carrito->getCarritoId()); 
    $productoItem = (new DetalleItemCarrito())->obtenerItem($producto_fk, $carrito->getCarritoId());
}

try {
    $cantidad = $productoItem->decrementarCantidad();
    $cantidadNva = $productoItem->getCantidad();
    $subtotal = $productoItem->actualizarSubtotalResta($productoItem->getSubtotal(), $producto->getPrecio());
    $subtotalNvo = $productoItem->getSubtotal();
    if($productoItem->getCantidad() >= 1){
        $productoItem->actualizarCantidadySubtotal([
            'cantidad' => $cantidadNva,
            'subtotal' => $subtotalNvo,
        ]);
    }
    header("Location: ../index.php?s=carrito");
    exit;
} catch (\Throwable $th) {
    $_SESSION['mensaje_error'] = '<i class="bi bi-exclamation-square"></i> Ocurrió un error al intentar decrementar la cantidad. Por favor, intenta nuevamente en unos minutos.';
    header("Location: ../index.php?s=carrito");
    exit;
}