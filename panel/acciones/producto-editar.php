<?php

use App\Auth\Autenticacion;
use App\Modelos\Productos;
use App\Uploaders\ProductoImgUploader;
use App\Validacion\ValidarProducto;

require_once __DIR__ . '/../../bootstrap/init.php';

$autenticacion = new Autenticacion();
if(!$autenticacion->estaAutenticado() || !$autenticacion->esAdmin()){
    $_SESSION['mensaje_error'] = '<i class="bi bi-exclamation-square"></i> Para añadir un producto tenés que iniciar sesión como administrador/a.';
    header("Location: ../index.php?s=inicio-sesion");
    exit;
}

$id                 = $_POST['producto_id'];
$nombre             = $_POST['nombre'];
$descripcion        = $_POST['descripcion'];
$categoria          = $_POST['categoria'];
$precio             = $_POST['precio'];
$imagen             = $_FILES['imagen'];
$imagen_descripcion = $_POST['imagen_descripcion'];
$recomendado        = $_POST['recomendado'];
$caracteristicas    = $_POST['caracteristicas_id'] ?? [];

$producto = (new Productos())->traerPorId($id);
if(!$producto){
    $_SESSION['mensaje_error'] = '<i class="bi bi-exclamation-square"></i> No existe el producto que se está tratando de editar';
    header("Location: ../index.php?s=productos");
    exit;
}

$validador = new \App\Validacion\Validador($_POST, [
    'categoria'   => ['required'],
    'nombre'      => ['required', 'min:6'],
    'descripcion' => ['required'],
    'precio'      => ['required', 'numeric'],
]);

if($validador->hayErrores()) {
    $_SESSION['errores'] = $validador->getErrores();
    $_SESSION['data_form'] = $_POST;
    header("Location: ../index.php?s=producto-editar&id=" . $id);
    exit;
}

if(!empty($imagen['tmp_name'])){
    $nombreImagen = date('YmdHis_') . $imagen['name'];
    $nombreImagen = ProductoImgUploader::upload($imagen, $nombreImagen);
}

try {
    $imgOriginal = $producto->getImagen();
    $producto->editar([
        'categoria_fk'          => $categoria,
        'usuario_id'            => 1,
        'nombre'                => $nombre,
        'descripcion'           => $descripcion,
        'precio'                => $precio,
        'imagen'                => $nombreImagen ?? $producto->getImagen(),
        'imagen_descripcion'    => $imagen_descripcion,
        'recomendado'           => $recomendado,
        'caracteristicas'       => $caracteristicas,
    ]);
    if(!empty($nombreImagen) && !empty($imgOriginal)){
        if(file_exists( PATH_IMG . '/' . $producto->getImagen())){
            unlink( PATH_IMG . '/' . $producto->getImagen());
        }
        if(file_exists(PATH_IMG . '/mobile-' . $producto->getImagen())){
            unlink(PATH_IMG . '/mobile-' . $producto->getImagen());
        }
    }
    $_SESSION['mensaje_exito'] = '<i class="bi bi-check-square"></i> El producto <b>' . $nombre . '</b> fue actualizado con éxito.'; 
    header("Location: ../index.php?s=productos");
    exit;
} catch (\Throwable $th) {
    $_SESSION['mensaje_error'] = '<i class="bi bi-exclamation-square"></i> Ocurrió un error inesperado al intentar actualizar los datos del producto. Por favor, intentá nuevamente más tarde.';
    $_SESSION['data_form'] = $_POST;
    header("Location: ../index.php?s=producto-editar&id=" . $id);
    exit;
}