<?php

use App\Auth\Autenticacion;
use App\Modelos\Carrito;

require_once __DIR__ . '/../bootstrap/init.php';

$autenticacion = new Autenticacion();
if(!$autenticacion->estaAutenticado()){
    $_SESSION['mensaje_error'] = '<i class="bi bi-exclamation-square"></i> Para vaciar el carrito tenés que iniciar sesión.';
    header("Location: ../index.php?s=iniciar-sesion");
    exit;
}
if($autenticacion->esAdmin()){
    $_SESSION['mensaje_error'] = '<i class="bi bi-exclamation-square"></i> Para vaciar el carrito tenés que iniciar sesión como usuario común.';
    header("Location: ../index.php?s=inicio-sesion");
    exit;
}

$id = $_POST['id'];

$carrito = (new Carrito())->obtenerCarrito($id);

if(!$carrito) {
    $_SESSION['mensaje_error'] = '<i class="bi bi-exclamation-square"></i> No existe el carrito que se está tratando de vaciar.';
    header("Location: ../index.php?s=carrito");
    exit;
}

try {
    $carrito->eliminar();
    $_SESSION['mensaje_exito'] = '<i class="bi bi-check-square"></i> Se ha vaciado el carrito.';
    header("Location: ../index.php?s=carrito");
    exit;
} catch (\Throwable $th) {
    $_SESSION['mensaje_error'] = '<i class="bi bi-exclamation-square"></i> Ocurrió un error al tratar de vaciar el carrito. Por favor, probá de nuevo más tarde.';
    header("Location: ../index.php?s=carrito");
    exit;
}