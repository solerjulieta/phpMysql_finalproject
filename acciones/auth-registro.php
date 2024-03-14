<?php

use App\Modelos\Usuario;
use App\Validacion\ValidarUsuario;

require_once __DIR__ . '/../bootstrap/init.php';

$nombre       = $_POST['nombre'];
$apellido     = $_POST['apellido'];
$email        = $_POST['email'];
$contrasena   = $_POST['contrasena'];

$validador = new \App\Validacion\Validador($_POST, [
    'nombre'     => ['required'],
    'apellido'   => ['required'],
    'email'      => ['required', 'email'],
    'contrasena' => ['required', 'min:6']
]);

if($validador->hayErrores()) {
    $_SESSION['errores'] = $validador->getErrores();
    $_SESSION['data_form'] = $_POST;
    header("Location: ../index.php?s=registro");
    exit;
}

$usuario = (new Usuario())->traerPorEmail($email);

if($usuario){
    $_SESSION['data_form'] = $_POST;
    $_SESSION['mensaje_error'] = '<i class="bi bi-exclamation-square"></i> Ya existe un usuario registrado con ese email.';
    header('Location: ../index.php?s=registro');
    exit;
}

try {
    (new Usuario)->crear([
        'email'      => $email,
        'contrasena' => password_hash($contrasena, PASSWORD_DEFAULT), 
        'rol_id'     => 2,
        'nombre'     => $nombre,
        'apellido'   => $apellido,
    ]);
    $_SESSION['mensaje_exito'] = '<i class="bi bi-check-square"></i> Tu cuenta fue creada correctamente. Ya podés iniciar sesión.';
    header('Location: ../index.php?s=iniciar-sesion');
    exit;
} catch (\Throwable $th) {
    $_SESSION['data_form'] = $_POST;
    $_SESSION['mensaje_error'] = '<i class="bi bi-exclamation-square"></i> Ocurrió un error al tratar de crear la cuenta. Por favor, intenta nuevamente en unos minutos.';
    header('Location: ../index.php?s=registro');
    exit;
}
