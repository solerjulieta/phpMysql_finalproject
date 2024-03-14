<?php

use App\Auth\Autenticacion;

require_once __DIR__ . '/../../bootstrap/init.php';

$autenticacion = new Autenticacion();

$autenticacion->cerrarSesion();

$_SESSION['mensaje_exito'] = '<i class="bi bi-check-square"></i> La sesión se cerró correctamente.';
header("Location: ../index.php?s=inicio-sesion");

