<?php

use App\Auth\Autenticacion;
use App\Modelos\Productos;
use App\Uploaders\ProductoImgUploader;
use App\Validacion\ValidarProducto;

require_once __DIR__ . '/../../bootstrap/init.php';

$autenticacion = new Autenticacion();
if(!$autenticacion->estaAutenticado() || !$autenticacion->esAdmin()){
    $_SESSION['mensaje_error'] = 'Para añadir un producto tenés que iniciar sesión como administrador/a.';
    header("Location: ../index.php?s=inicio-sesion");
    exit;
}

$nombre              = $_POST['nombre'];
$descripcion         = $_POST['descripcion'];
$categoria           = $_POST['categoria'];
$precio              = $_POST['precio'];
$imagen              = $_FILES['imagen'];
$imagen_descripcion  = $_POST['imagen_descripcion'];
$recomendado         = $_POST['recomendado'];
$caracteristicas     = $_POST['caracteristicas_id'] ?? [];

$validador = new \App\Validacion\Validador($_POST, [
    'categoria'    => ['required'],
    'nombre'       => ['required', 'min:6'],
    'descripcion'  => ['required'],
    'precio'       => ['required', 'numeric'],
]);

if($validador->hayErrores()) {
    $_SESSION['errores'] = $validador->getErrores();
    $_SESSION['data_form'] = $_POST;
    header("Location: ../index.php?s=aniadir-producto");
    exit;
}

if(!empty($imagen['tmp_name'])){
    $nombreImagen = date('YmdHis_') . $imagen['name'];
    $nombreImagen = ProductoImgUploader::upload($imagen, $nombreImagen);
}

try {
    (new Productos)->crear([
        'categoria_fk'        => $categoria,
        'usuario_id'          => 1,
        'nombre'              => $nombre,
        'descripcion'         => $descripcion,
        'precio'              => $precio,
        'imagen'              => $nombreImagen ?? '',
        'imagen_descripcion'  => $imagen_descripcion,
        'recomendado'         => $recomendado ?? '',
        'caracteristicas'     => $caracteristicas,
        'mostrar'             => 1,
    ]);
    $_SESSION['mensaje_exito'] = '<i class="bi bi-check-square"></i> El producto <b>' . $nombre . '</b> fue publicado con éxito.';
    header("Location: ../index.php?s=productos");
    exit;
} catch (\Throwable $th) {
    $_SESSION['mensaje_error'] = '<i class="bi bi-exclamation-square"></i> Ocurrió un error inesperado al intentar publicar el producto. Por favor, intentá nuevamente más tarde.';
    $_SESSION['data_form'] = $_POST;
    header("Location: ../index.php?s=aniadir-producto");
    exit;
}