<?php

use App\Modelos\Usuario;

require_once __DIR__ . '/../../bootstrap/init.php';

$email = $_POST['email'];

$usuario = (new Usuario())->traerPorEmail($email);
if(!$usuario){
    $_SESSION['data_form'] = $_POST;
    $_SESSION['mensaje_error'] = '<i class="bi bi-exclamation-square"></i> No existe un usuario con este mail.';
    header('Location: ../index.php?s=recuperar-contrasena');
    exit;
}

try {
    $recuperar = new App\Auth\RecuperarContrasena();
    $recuperar->enviarEmail($usuario);
    $_SESSION['mensaje_exito'] = '<i class="bi bi-check-square"></i> ¡Te enviamos un email! Ingresá a tu casilla y seguí las instrucciones para recuperar tu clave.';
    header('Location: ../index.php?s=inicio-sesion');
    exit;
} catch (\Throwable $th) {
    $_SESSION['data_form'] = $_POST;
    $_SESSION['mensaje_error'] = '<i class="bi bi-exclamation-square"></i> Ocurrió un error y el email no pudo ser enviado. Intentá nuevamente más tarde.';
    header('Location: ../index.php?s=recuperar-contrasena');
    exit;
}