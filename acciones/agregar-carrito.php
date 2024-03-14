<?php

use App\Auth\Autenticacion;
use App\Modelos\Carrito;
use App\Modelos\DetalleItemCarrito;
use App\Modelos\Productos;

require_once __DIR__ . '/../bootstrap/init.php';

$autenticacion = new Autenticacion();
if(!$autenticacion->estaAutenticado()){
    $_SESSION['mensaje_error'] = '<i class="bi bi-exclamation-square"></i> Para añadir un producto al carrito tenés que iniciar sesión.';
    header("Location: ../index.php?s=inicio-sesion");
    exit;
}
if($autenticacion->esAdmin()){
    $_SESSION['mensaje_error'] = '<i class="bi bi-exclamation-square"></i> Para añadir un producto al carrito tenés que iniciar sesión como usuario común.';
    header("Location: ../index.php?s=inicio-sesion");
    exit;
}

$producto_fk = $_POST['id'];
$usuario_fk = $_POST['usuario_fk'];
$producto = (new Productos())->traerPorId($producto_fk);
if(!$producto){
    $_SESSION['mensaje_error'] = '<i class="bi bi-exclamation-square"></i> No existe el producto que se está tratando de añadir al carrito.';
    header("Location: ../index.php?s=productos&c=1");
    exit;
}
$carrito = (new Carrito())->obtenerCarrito($usuario_fk);
if($carrito){
    $productoItem = (new DetalleItemCarrito())->obtenerItem($producto_fk, $carrito->getCarritoId());
}

try {
    if(!$carrito && !$productoItem){
        (new Carrito)->crear([
            'usuario_fk' => $usuario_fk,
            'producto_fk' => $producto_fk,
            'cantidad'    => 1,
            'subtotal'    => $producto->getPrecio(),
        ]);
    }
    if($productoItem && $carrito){
        $cantidad = $productoItem->incrementarCantidad();
        $cantidadNva = $productoItem->getCantidad();
        $subtotal = $productoItem->actualizarSubtotal($producto->getPrecio(), $cantidadNva);
        $subtotalNvo = $productoItem->getSubtotal();
        $productoItem->actualizarCantidadySubtotal([
            'cantidad' => $cantidadNva,
            'subtotal' => $subtotalNvo,
        ]);
    }
    if(!$productoItem && $carrito){
        $carritoId = $carrito->getCarritoId();
        (new Carrito)->crearItems($carritoId, [
            'producto_fk' => $producto_fk,
            'cantidad'    => 1,
            'subtotal'    => $producto->getPrecio(),
        ]);
    }
    $_SESSION['mensaje_exito'] = '<i class="bi bi-check-square"></i> ¡Se añadió <b>' . $producto->getNombre() . '</b> al carrito! <a href="index.php?s=carrito">Ver carrito</a>';
    header("Location: ../index.php?s=detalle-productos&id=" . $producto_fk);
    exit;
} catch (\Throwable $th) {
    $_SESSION['mensaje_error'] = '<i class="bi bi-exclamation-square"></i> Ocurrió un error al intentar añadir el producto. Por favor, intenta nuevamente en unos minutos.';
    header("Location: ../index.php?s=detalle-productos&id=" . $producto_fk);
    exit;
}