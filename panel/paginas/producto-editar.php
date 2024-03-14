<?php
use App\Auth\Autenticacion;
use App\Modelos\Productos;
use App\Validacion\ValidarProducto;
use App\Modelos\Caracteristica;
$errores  = $_SESSION['errores'] ?? [];
$dataForm = $_SESSION['data_form'] ?? []; 
unset($_SESSION['errores'], $_SESSION['data_form']);
$producto = (new Productos())->traerPorId($_GET['id']);
if(!$producto){
  require_once __DIR__ . '/404.php';
} else {
    $producto->cargarCaracteristicas();
    $caracteristicas = (new Caracteristica())->todoContenido();
?>
<section class="container editar-prod">
    <div class="page-back">
        <a href="javascript:history.back()" >
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left-short" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M12 8a.5.5 0 0 1-.5.5H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5a.5.5 0 0 1 .5.5z"/>
        </svg> Atrás
        </a>
    </div>
    <h2>Editar datos del <span>producto</span></h2>
    <p>Editá los datos del producto en el siguiente formulario. Para guardar los cambios hacé click en "Actualizar".</p>
    <form class="row" action="acciones/producto-editar.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name="producto_id" value="<?= $producto->getProductoId();?>">
        <div class="col-lg-7">
            <label for="nombre">Nombre</label>
            <input 
              type="text" 
              class="form-control" 
              id="nombre" 
              name="nombre"
              aria-describedby="ayuda-nombre <?= isset($errores['nombre']) ? 'error-nombre' : ''; ?>"
              value="<?= $dataForm['nombre'] ?? $producto->getNombre(); ?>"
            >
              <div class="form-text" id="ayuda-nombre">Debe contener al menos 6 caracteres.</div>
              <?php
              if(isset($errores['nombre'])):
              ?>
            <div class="msj-error col-lg-7" id="error-nombre"><i class="bi bi-exclamation-square"></i><span class="no-visible">Error:</span><?= $errores['nombre'][0];?></div>
            <?php
              endif;
            ?>
        </div>
        <div class="col-lg-7">
            <label for="descripcion">Descripción</label>
            <textarea name="descripcion" id="descripcion" class="form-control" <?php if(isset($errores['descripcion'])): ?>aria-describedby="error-descripcion" <?php endif; ?>><?= $dataForm['descripcion'] ?? $producto->getDescripcion(); ?></textarea>
            <?php
              if(isset($errores['descripcion'])):
            ?>
            <div class="msj-error col-lg-7" id="error-descripcion"><i class="bi bi-exclamation-square"></i><span class="no-visible">Error:</span><?= $errores['descripcion'][0];?></div>
            <?php
              endif;
            ?>
        </div>
        <div class="col-lg-7">
          <span>Seleccioná la categoría del producto:</span>
          <div class="form-check">
              <input class="form-check-input" value="2" type="radio" name="categoria" id="bombillas" <?php if($producto->getCategoriaId()==2): ?>checked<?php endif; ?>>
              <label class="form-check-label" for="bombillas">Bombillas</label>
          </div>
          <div class="form-check">
              <input class="form-check-input" value="3" type="radio" name="categoria" id="mates" <?php if($producto->getCategoriaId()==3): ?>checked<?php endif; ?>>
              <label class="form-check-label" for="mates">Mates</label>
          </div>
          <div class="form-check">
              <input class="form-check-input" value="4" type="radio" name="categoria" id="termos" <?php if($producto->getCategoriaId()==4): ?>checked<?php endif; ?>>
              <label class="form-check-label" for="termos">Termos</label>
          </div>
          <?php
          if(isset($errores['categoria'])):
          ?>
            <div class="msj-error col-lg-7" id="error-categoria"><i class="bi bi-exclamation-square"></i><span class="no-visible">Error:</span><?= $errores['categoria'][0];?></div>
          <?php
            endif;
          ?>
        </div>
        <div class="col-lg-7">
            <label for="precio">Precio</label>
            <input 
              type="number" 
              class="form-control" 
              id="precio" 
              name="precio" 
              min="0"
              value="<?= $dataForm['precio'] ?? $producto->getPrecio(); ?>"  
              <?php if(isset($errores['precio'])): ?>aria-describedby="error-precio" <?php endif; ?>>
            <?php
              if(isset($errores['precio'])):
            ?>
            <div class="msj-error col-lg-7" id="error-precio"><i class="bi bi-exclamation-square"></i><span class="no-visible">Error:</span><?= $errores['precio'][0];?></div>
            <?php
              endif;
            ?>                  
        </div>
        <?php
        if(!empty($producto->getImagen()) && file_exists(__DIR__ . '/../../imagenes/' . $producto->getImagen())):
        ?>
        <div class="col-lg-7">
            <p>Imagen de producto actual:</p>
            <picture>
              <source media="(max-width:767px)" srcset="<?= '../imagenes/mobile-' . $producto->getImagen(); ?>">
              <img src="<?= '../imagenes/' . $producto->getImagen(); ?>" alt="<?= $producto->getImagenDescripcion(); ?>">
            </picture>
        </div>
        <?php
        endif;
        ?>
        <div class="col-lg-7">
            <label for="imagen">Imagen <span class="txt-aclarativo">(opcional)</span></label>
            <input type="file" class="form-control" id="imagen" name="imagen">              
        </div>
        <div class="col-lg-7">
            <label for="imagen_descripcion">Descripción de la imagen <span class="txt-aclarativo">(opcional)</span></label>
            <input 
              type="text"
              class="form-control" 
              id="imagen_descripcion" 
              name="imagen_descripcion" 
              value="<?= $dataForm['imagen_descripcion'] ?? $producto->getImagenDescripcion();?>"
            >
        </div>
        <div class="col-lg-7">
          <span>Características: <span class="txt-aclarativo">(opcional)</span></span>
          <fieldset class="mt-3">
            <?php
            foreach($caracteristicas as $caracteristica):
            ?>
              <label class="col-md-6">
                <input 
                    type="checkbox" 
                    name="caracteristicas_id[]" 
                    value="<?= $caracteristica->getCaracteristicaId();?>"
                    <?= in_array($caracteristica->getCaracteristicaId(), $dataForm['caracteristicas_id'] ?? 
                        $producto->getCaractId())
                        ? 'checked'
                        : ''; ?>
                >
                <?= $caracteristica->getCualidad(); ?>
              </label>
            <?php
            endforeach;
            ?>
          </fieldset>
        </div>
        <div class="col-lg-7">
          <span>Seleccioná si el producto es recomendado:</span>
          <div class="form-check">
              <input class="form-check-input" value="1" type="radio" name="recomendado" id="recomendar" <?php if($producto->getRecomendado()==1): ?>checked<?php endif; ?>>
              <label class="form-check-label" for="recomendar">Recomendado</label>
          </div>
          <div class="form-check">
              <input class="form-check-input" value="" type="radio" name="recomendado" id="norecomendado" <?php if($producto->getRecomendado()==0): ?>checked<?php endif; ?>>
              <label class="form-check-label" for="norecomendado">No recomendado</label>
          </div>
        </div>
        <div class="col-lg-4 col-md-8 btnActualizar">
              <input type="submit" value="actualizar" class="btn marronOsc float-end">
        </div>
    </form>
</section>
<?php
}
?>