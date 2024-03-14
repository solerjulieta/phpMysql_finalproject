<?php

require_once __DIR__ . '/../bootstrap/init.php';

$id                   = $_POST['id'];
$token                = $_POST['token'];
$contrasena           = $_POST['contrasena'];

$validador = new \App\Validacion\Validador($_POST, [
    'contrasena' => ['required', 'min:6'],
]);

if($validador->hayErrores()) {
    $_SESSION['errores'] = $validador->getErrores();
    $_SESSION['data_form'] = $_POST;
    header('Location: ../index.php?s=cambiar-contrasena&token=' . $token . '&usuario=' . $id);
    exit;
}

$recuperar = new \App\Auth\RecuperarContrasena();
$recuperar->setUsuarioPorId($id);
$recuperar->setToken($token);

if(!$recuperar->esValido()){
    $_SESSION['mensaje_error'] = '<i class="bi bi-exclamation-square"></i> No se puede actualizar la contraseña, el código no coincide con este usuario.';
    header('Location: ../index.php?s=cambiar-contrasena&token=' . $token . '&usuario=' . $id);
    exit;
}

if($recuperar->expirado()){
    $_SESSION['mensaje_error'] = '<i class="bi bi-exclamation-square"></i> El enlace seleccionado ya no es válido. Por favor, solicite el restablecimiento de la contraseña nuevamente.';
    header('Location: ../index.php?s=recuperar-contrasena');
    exit;
}

try {
    $recuperar->actualizarContrasena($contrasena);
    $_SESSION['mensaje_exito'] = '<i class="bi bi-check-square"></i> La contraseña se actualizó correctamente.';
    header('Location: ../index.php?s=iniciar-sesion');
    exit;
} catch (\Throwable $th) {
    $_SESSION['mensaje_error'] = '<i class="bi bi-exclamation-square"></i> Ocurrió un error inesperado al tratar de actualizar la clave.';
    header('Location: ../index.php?s=cambiar-contrasena&token=' . $token . '&usuario=' . $id);
    exit;
}