<?php

use App\Auth\Autenticacion;
use App\Modelos\Carrito;
use App\Modelos\DetalleItemCarrito;
use App\Modelos\OrdenTieneProductos;
use App\Modelos\Productos;

require_once __DIR__ . '/../../bootstrap/init.php';

$autenticacion = new Autenticacion();
if(!$autenticacion->estaAutenticado() || !$autenticacion->esAdmin()){
    $_SESSION['mensaje_error'] = '<i class="bi bi-exclamation-square"></i> Para añadir un producto tenés que iniciar sesión como administrador/a.';
    header("Location: ../index.php?s=inicio-sesion");
    exit;
}

$id = $_POST['id'];
$producto = (new Productos())->traerPorId($id);
if(!$producto){
    $_SESSION['mensaje_error'] = '<i class="bi bi-exclamation-square"></i> No existe el producto que se está tratando de eliminar.';
    header("Location: ../index.php?s=productos");
    exit;
}

$productoEnOrden = (new OrdenTieneProductos())->obtenerProductoOrdenes($id);
$carritos = (new Carrito())->todoContenido();

try {
    if(!empty($productoEnOrden)){
        $producto->ocultar([
            'mostrar' => '',
        ]);
    } else {
        $producto->eliminar();
        if(!empty($producto->getImagen())){
            chmod(PATH_IMG . '/' . $producto->getImagen(), 0755);
            if(file_exists(PATH_IMG . '/' . $producto->getImagen())){
                unlink(PATH_IMG . '/' . $producto->getImagen());
            }
            if(file_exists(PATH_IMG . '/mobile-' . $producto->getImagen())){
                unlink(PATH_IMG . '/mobile-' . $producto->getImagen());
            }
        }
        foreach ($carritos as $carrito) {
            $carritoItems = (new DetalleItemCarrito())->obtenerCarritoItems($carrito->getCarritoId());
            if(empty($carritoItems)){
                $carrito->eliminar();
            }
        }
    }
    $_SESSION['mensaje_exito'] = '<i class="bi bi-check-square"></i> El producto <b>' . $producto->getNombre() . '</b> fue eliminado con éxito.';
    header("Location: ../index.php?s=productos");
    exit;
} catch (\Throwable $th) {
    $_SESSION['mensaje_error'] = '<i class="bi bi-exclamation-square"></i> Ocurrió un error inesperado al tratar de eliminar el producto. Por favor, intentá nuevamente más tarde.';
    header("Location: ../index.php?s=productos");
    exit;
}