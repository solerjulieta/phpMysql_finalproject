<?php

use App\Auth\Autenticacion;
use App\Modelos\Orden;

require_once __DIR__ . '/../../bootstrap/init.php';

$autenticacion = new Autenticacion();
if(!$autenticacion->estaAutenticado()){
    $_SESSION['mensaje_error'] = 'Para confirmar la compra tenés que iniciar sesión.';
    header("Location: ../index.php?s=inicio-sesion");
    exit;
}

$orden_id = $_POST['orden_id'];
$usuario_fk = $_POST['usuario_fk'];
$orden_estado_fk = $_POST['orden_estado_fk'];

$orden = (new Orden())->traerPorId($orden_id);

try {
    $orden->editar([
        'orden_estado_fk' => $orden_estado_fk,
    ]);
    $_SESSION['mensaje_exito'] = '<i class="bi bi-check-square"></i> El estado del pedido fue actualizado con éxito.'; 
    header("Location: ../index.php?s=compras-usuario&id=" . $usuario_fk);
    exit;
} catch (\Throwable $th) {
    $_SESSION['mensaje_error'] = '<i class="bi bi-exclamation-square"></i> Ocurrió un error inesperado al intentar actualizar el estado del pedido. Por favor, intentá nuevamente más tarde.';
    header("Location: ../index.php?s=compras-usuario&id=" . $usuario_fk);
    exit;
}