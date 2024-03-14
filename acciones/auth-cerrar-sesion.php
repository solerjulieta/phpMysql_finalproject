<?php

require_once __DIR__ . '/../bootstrap/init.php';

(new \App\Auth\Autenticacion)->cerrarSesion();

$_SESSION['mensaje_exito'] = '<i class="bi bi-check-square"></i> La sesión se cerró correctamente.';
header('Location: ../index.php?s=iniciar-sesion');
exit;