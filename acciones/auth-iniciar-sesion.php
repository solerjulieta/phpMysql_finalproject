<?php

use App\Auth\Autenticacion;

require_once __DIR__ . '/../bootstrap/init.php';

$email      = $_POST['email'];
$contrasena = $_POST['contrasena'];

$validador = new \App\Validacion\Validador($_POST, [
    'email' => ['required', 'email'],
    'contrasena' => ['required']
]);

if($validador->hayErrores()) {
    $_SESSION['errores'] = $validador->getErrores();
    $_SESSION['data_form'] = $_POST;
    header("Location: ../index.php?s=iniciar-sesion");
    exit;
}

$autenticacion = new Autenticacion();

if(!$autenticacion->iniciarSesion($email, $contrasena)){
    $_SESSION['mensaje_error'] = '<i class="bi bi-exclamation-square"></i> El email o clave ingresada no son correctos.';
    $_SESSION['data_form'] = $_POST;
    header('Location: ../index.php?s=iniciar-sesion');
    exit;
}

$_SESSION['mensaje_exito'] = 'Bienvenido/a <b>' . $email . '</b>';
header('Location: ../index.php?s=perfil');
exit;