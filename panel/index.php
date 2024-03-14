<?php

use App\Modelos\Productos;

require_once __DIR__ . '/../bootstrap/init.php';

$productoObj = new Productos();
$productos = $productoObj->todoContenido();

$rutas = [
    'inicio-sesion' => [
      'title' => 'Iniciar Sesión',
    ],
    'recuperar-contrasena' => [
      'title' => 'Recuperar Contraseña',
    ],
    'cambiar-contrasena' => [
      'title' => 'Cambiar Contraseña',
    ],
    'panel' => [
      'title' => 'Panel de Administración',
      'requiereAutenticacion' => true,
    ],
    'productos' => [
      'title' => 'Administrar productos',
      'requiereAutenticacion' => true,
    ],
    'aniadir-producto' => [
     'title' => 'Agregar nuevo producto',
     'requiereAutenticacion' => true,
    ],
    'producto-editar' => [
     'title' => 'Editar producto',
     'requiereAutenticacion' => true,
    ],
    'usuarios' => [
      'title' => 'Administrar usuarios',
      'requiereAutenticacion' => true,
    ],
    'compras-usuario' => [
      'title' => 'Compras de usuario',
      'requiereAutenticacion' => true,
    ],
    '404' => [
     'title' => 'Página no encontrada',
    ],
];

$pagina = $_GET['s'] ?? 'panel';

if(!isset($rutas[$pagina])){
   $pagina = '404';
}

$rutaFiltrada = $rutas[$pagina];

$autenticacion = new \App\Auth\Autenticacion();

$requiereAuth = $rutaFiltrada['requiereAutenticacion'] ?? false;
if($requiereAuth && (!$autenticacion->estaAutenticado() || !$autenticacion->esAdmin())){
  $_SESSION['mensaje_error'] = 'Para acceder a esta pantalla tenés que iniciar sesión como administrador/a.';
  header("Location: index.php?s=inicio-sesion");
  exit;
}

$mensajeExito = $_SESSION['mensaje_exito'] ?? null;
$mensajeError = $_SESSION['mensaje_error'] ?? null;
unset($_SESSION['mensaje_error'], $_SESSION['mensaje_exito']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $rutaFiltrada['title'];?></title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Raleway&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/estilo.css?=<?=time()?>">
</head>
<body>
  <header class="marronOsc">
      <a href="index.php?s=panel"><h1 id="logo">Tú mate</h1></a>
      <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#barra" aria-controls="barra" aria-expanded="false" aria-label="Botón hamburguesa">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="barra">
            <?php 
            if($autenticacion->estaAutenticado() && $autenticacion->esAdmin()):
            ?>
              <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="index.php?s=panel">Panel</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php?s=productos">Productos</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php?s=usuarios">Usuarios</a></li>
                <li class="nav-item cerrarSesion">
                  <form action="acciones/auth-cerrar-sesion.php">
                    <i class="bi bi-person-square"></i> <?= $autenticacion->getUsuario()->getEmail();?>
                    <button type="submit" class="btn">Cerrar Sesión</button>
                  </form>
                </li>
              </ul>
            <?php
            endif;
            ?>
          </div>
        </div>
      </nav>
  </header>
  <main>
      <?php
      if($mensajeExito !== null): 
      ?>
      <div id="alertaExito" class="alert alert-success container" role="alert"><?= $mensajeExito; ?></div>
      <?php
      endif;
      ?>
      <?php
      if($mensajeError !== null): 
      ?>
      <div id="alertaError" class="alert alert-danger container" role="alert"><?= $mensajeError; ?></div>
      <?php
      endif;
      ?>        

      <?php
        if(file_exists('./paginas/' . $pagina . '.php')){
        require __DIR__ . './paginas/' . $pagina . '.php';
        } else {
          require __DIR__ . './paginas/404.php'; 
        }      
      ?>
  </main>
  <footer class="marronOsc">
      <div>
        <div>
        <img src="../imagenes/logomobile.png" alt="Logotipo">
        </div> 
      </div>
  </footer>
  <script src="../js/bootstrap.bundle.min.js"></script>
  <script src="../js/main.js"></script>
  <script src="../js/btn.password.js"></script>
  <script src="../js/alerta.js"></script>
</body>
</html>