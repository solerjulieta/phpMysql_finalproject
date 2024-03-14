<?php

use App\Auth\Autenticacion;
use App\Modelos\Carrito;
use App\Modelos\Orden;

require_once __DIR__ . '/../bootstrap/init.php';

$autenticacion = new Autenticacion();
if(!$autenticacion->estaAutenticado()){
    $_SESSION['mensaje_error'] = '<i class="bi bi-exclamation-square"></i> Para confirmar la compra tenés que iniciar sesión.';
    header("Location: ../index.php?s=inicio-sesion");
    exit;
}
if($autenticacion->esAdmin()){
    $_SESSION['mensaje_error'] = '<i class="bi bi-exclamation-square"></i> Para realizar una compra tenés que iniciar sesión como usuario común.';
    header("Location: ../index.php?s=inicio-sesion");
    exit;
}

$carrito = (new Carrito())->obtenerCarrito($autenticacion->getId());

$total       = $_POST['total']; 
$cantidad    = $_POST['cantidad'];
$producto_fk = $_POST['producto_fk'];
$subtotal    = $_POST['subtotal'];

try {
    (new Orden)->crear([
        'usuario_fk'      => $autenticacion->getId(),
        'fecha_pedido'    => date('Y-m-d H:i:s'),
        'orden_estado_fk' => 1,
        'total'           => $total,
        'cantidad'        => $cantidad,
        'producto_fk'     => $producto_fk,
        'subtotal'        => $subtotal,
    ]);
    $carrito->eliminar();
    $_SESSION['mensaje_exito'] = '<i class="bi bi-check-square"></i> ¡Muchas gracias por tu compra!';
    header("Location: ../index.php?s=perfil");
    exit;
} catch (\Throwable $th) {
    $_SESSION['mensaje_error'] = '<i class="bi bi-exclamation-square"></i> Ocurrió un error al intentar hacer la compra. Por favor, intenta nuevamente en unos minutos.';
    header("Location: ../index.php?s=carrito");
    exit;
}