<?php

use App\Modelos\Productos;

require_once __DIR__ . '/bootstrap/init.php';

$productoObj = new Productos();
$productos = $productoObj->todoContenido();

$rutas = [
   'inicio' => [
     'title' => 'Página de inicio',
    ],
   'productos' => [
     'title' => 'Productos',
    ],
   'detalle-productos' => [
     'title' => 'Detalle producto',
    ],
   'contacto' => [
     'title' => 'Contacto',
    ],
    'registro' => [
      'title' => 'Registrar una cuenta',
    ],
    'iniciar-sesion' => [
      'title' => 'Iniciar Sesión',
    ],
    'recuperar-contrasena' => [
      'title' => 'Recuperar Contraseña',
    ],
    'cambiar-contrasena' => [
      'title' => 'Cambiar Contraseña',
    ],
    'carrito' => [
      'title' => 'Mi Carrito',
      'requiereAutenticacion' => true,
    ],
    'perfil' => [
      'title' => 'Mi Perfil',
      'requiereAutenticacion' => true,
    ],
   'gracias' => [
     'title' => 'Gracias',
    ],
   '404' => [
     'title' => 'Página no encontrada',
    ],
];

$pagina = $_GET['s'] ?? 'inicio';

if(!isset($rutas[$pagina])){
   $pagina = '404';
}

$rutaFiltrada = $rutas[$pagina];

//Autenticación
$autenticacion = new \App\Auth\Autenticacion();
$requiereAuth = $rutaFiltrada['requiereAutenticacion'] ?? false;
if($requiereAuth && !$autenticacion->estaAutenticado()){
  $_SESSION['mensaje_error'] = 'Para acceder a esta pantalla tenés que iniciar sesión.';
  header("Location: index.php?s=inicio-sesion");
  exit;
}

//Mensajes de feedback para el usuario.
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

    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/estilo.css?=<?=time()?>">
</head>
<body>
  <header class="marronOsc">
    <a href="index.php?s=inicio"><h1 id="logo">Tú mate</h1></a>
    <nav class="navbar navbar-expand-lg">
      <div class="container-fluid">
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#barra" aria-controls="barra" aria-expanded="false" aria-label="Botón hamburguesa">
            <span class="navbar-toggler-icon"></span>
          </button>
        <div class="collapse navbar-collapse" id="barra">
          <ul class="navbar-nav ms-auto">
            <li class="nav-item">
              <a class="nav-link" href="index.php?s=inicio">Inicio</a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">Categorías</a>
              <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                <li><a class="dropdown-item" href="index.php?s=productos&c=1">Ver todo</a></li>
                <li><a class="dropdown-item" href="index.php?s=productos&c=2">Bombillas</a></li>
                <li><a class="dropdown-item" href="index.php?s=productos&c=3">Mates</a></li>
                <li><a class="dropdown-item" href="index.php?s=productos&c=4">Termos</a></li>
              </ul>
            </li>
            <li class="nav-item"><a class="nav-link" href="index.php?s=contacto">Contacto</a></li>
            <?php
            if($autenticacion->estaAutenticado()):
            ?>
              <?php
              if(!$autenticacion->esAdmin()):
              ?>
              <li class="nav-item"><a class="nav-link" href="index.php?s=carrito">Carrito</a></li>
              <?php
              endif;
              ?>
              <li class="nav-item"><a class="nav-link" href="index.php?s=perfil">Mi Perfil</a></li>
              <li class="nav-item">
                <form action="acciones/auth-cerrar-sesion.php" method="post">
                  <button id="btn-link" class="btn" type="submit">Cerrar Sesión</button>
                </form>
              </li>
            <?php
            else:
            ?>
              <li class="nav-item"><a class="nav-link" href="index.php?s=iniciar-sesion">Iniciar Sesión</a></li>
              <li class="nav-item"><a class="nav-link" href="index.php?s=registro">Registrarse</a></li>
            <?php
            endif;
            ?>
          </ul>
        </div>
      </div>
    </nav>
  </header>
  <main>
      <?php
      if($mensajeExito !== null): 
      ?>
      <div id="alertaExito" class="alert show alert-success container" role="alert"><?= $mensajeExito; ?></div>
      <?php
      endif;
      ?>
      <?php
      if($mensajeError !== null): 
      ?>
      <div id="alertaError" class="alert show alert-danger container" role="alert"><?= $mensajeError; ?></div>
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
          <img src="imagenes/logomobile.png" alt="Logotipo">
        </div>
        <div>
        <p>¡Seguinos en nuestras redes!</p>
        <ul id="redes">
            <li id="fb"><a href="https://www.facebook.com/" target="_blank">Facebook</a></li>
            <li id="tw"><a href="https://twitter.com/" target="_blank">Twitter</a></li>
            <li id="in"><a href="https://www.instagram.com/" target="_blank">Instagram</a></li>
        </ul>  
        </div>  
      </div>
  </footer>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/btn.password.js"></script>
    <script src="js/alerta.js"></script>
</body>
</html>