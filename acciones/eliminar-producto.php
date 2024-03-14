<?php

use App\Auth\Autenticacion;
use App\Modelos\Carrito;
use App\Modelos\DetalleItemCarrito;

require_once __DIR__ . '/../bootstrap/init.php';

$autenticacion = new Autenticacion();
if(!$autenticacion->estaAutenticado()){
    $_SESSION['mensaje_error'] = '<i class="bi bi-exclamation-square"></i> Para eliminar un producto del carrito tenés que iniciar sesión.';
    header("Location: ../index.php?s=inicio-sesion");
    exit;
}
if($autenticacion->esAdmin()){
    $_SESSION['mensaje_error'] = '<i class="bi bi-exclamation-square"></i> Para eliminar un producto del carrito tenés que iniciar sesión como usuario común.';
    header("Location: ../index.php?s=inicio-sesion");
    exit;
}

$producto_fk = $_POST['producto_fk'];
$carrito_fk = $_POST['carrito_fk'];

$itemCarrito = (new DetalleItemCarrito())->obtenerItem($producto_fk, $carrito_fk); 
if(!$itemCarrito){
    $_SESSION['mensaje_error'] = '<i class="bi bi-exclamation-square"></i> No existe el producto que estás tratando de eliminar.';
    header("Location: ../index.php?s=carrito");
    exit;
}
$itemsCarrito = (new Carrito())->cargarItems($carrito_fk); 

try {
    $itemCarrito->eliminarItem();
    if(count($itemsCarrito) <= 1){
        $carrito = (new Carrito())->obtenerCarrito($autenticacion->getId());
        $carrito->eliminar();
    }
    $_SESSION['mensaje_exito'] = '<i class="bi bi-check-square"></i> Se ha quitado el producto del carrito.';
    header("Location: ../index.php?s=carrito");
    exit;
} catch (\Throwable $th) {
    $_SESSION['mensaje_error'] = '<i class="bi bi-exclamation-square"></i> Ocurrió un error inesperado al tratar de eliminar el producto. Por favor, probá de nuevo más tarde.';
    header("Location: ../index.php?s=carrito");
    exit;
}