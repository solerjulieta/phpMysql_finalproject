<?php
use App\Modelos\Caracteristica;
$caracteristicas = (new Caracteristica())->todoContenido();
$errores  = $_SESSION['errores'] ?? [];
$dataForm = $_SESSION['data_form'] ?? []; 
unset($_SESSION['errores'], $_SESSION['data_form']);
?>
<section class="container cargar-prod">
  <div class="page-back">
        <a href="index.php?s=productos">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left-short" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M12 8a.5.5 0 0 1-.5.5H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5a.5.5 0 0 1 .5.5z"/>
        </svg> Atrás
        </a>
  </div>
    <h2>Cargar datos del <span>producto</span></h2>
    <p>Completá el siguiente formulario con los datos solicitados para agregar el producto.</p>
    <form class="row" action="acciones/crear-producto.php" method="post" enctype="multipart/form-data">
        <div class="col-lg-7">
            <label for="nombre">Nombre</label>
            <input 
              type="text" 
              class="form-control" 
              id="nombre" 
              name="nombre"  
              aria-describedby="ayuda-nombre <?= isset($errores['nombre']) ? 'error-nombre' : ''; ?>"
              value="<?= $dataForm['nombre'] ?? null; ?>"
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
            <textarea name="descripcion" id="descripcion" class="form-control" <?php if(isset($errores['descripcion'])): ?> aria-describedby="error-descripcion" <?php endif; ?>><?= $dataForm['descripcion'] ?? null; ?></textarea>
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
              <input class="form-check-input" value="2" type="radio" name="categoria" id="bombillas" <?= isset($dataForm['categoria']) && $dataForm['categoria']==2 ? 'checked' : '' ?>>
              <label class="form-check-label" for="bombillas">Bombillas</label>
          </div>
          <div class="form-check">
              <input class="form-check-input" value="3" type="radio" name="categoria" id="mates" <?= isset($dataForm['categoria']) && $dataForm['categoria']==3 ? 'checked' : '' ?>>
              <label class="form-check-label" for="mates">Mates</label>
          </div>
          <div class="form-check">
              <input class="form-check-input" value="4" type="radio" name="categoria" id="termos" <?= isset($dataForm['categoria']) && $dataForm['categoria']==4 ? 'checked' : '' ?>>
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
              value="<?= $dataForm['precio'] ?? null; ?>"  
              <?php if(isset($errores['precio'])): ?>aria-describedby="error-precio" <?php endif; ?>>
            <?php
              if(isset($errores['precio'][0])):
            ?>
            <div class="msj-error col-lg-7" id="error-precio"><i class="bi bi-exclamation-square"></i><span class="no-visible">Error:</span><?= $errores['precio'][0];?></div>
            <?php
              endif;
            ?>                  
        </div>
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
              value="<?= $dataForm['imagen_descripcion'] ?? null; ?>"
            >
        </div>
        <div class="col-lg-7">
          <span>Características</span>
          <fieldset class="mt-3">
            <?php
            foreach($caracteristicas as $caracteristica):
            ?>
              <label class="col-md-6">
                <input 
                    type="checkbox" 
                    name="caracteristicas_id[]" 
                    value="<?= $caracteristica->getCaracteristicaId();?>"
                    <?= in_array($caracteristica->getCaracteristicaId(), $dataForm['caracteristicas_id'] ?? [])
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
              <input class="form-check-input" value="1" type="radio" name="recomendado" id="recomendar" <?= isset($dataForm['recomendado']) && $dataForm['recomendado']==1 ? 'checked' : ''; ?>>
              <label class="form-check-label" for="recomendar">Recomendado</label>
          </div>
          <div class="form-check">
              <input class="form-check-input" value="" type="radio" name="recomendado" id="norecomendado" <?= isset($dataForm['recomendado']) && $dataForm['recomendado']== '' ? 'checked' : ''; ?>>
              <label class="form-check-label" for="norecomendado">No recomendado</label>
          </div>
        </div>
        <div id="div-btn" class="col-lg-4 col-md-8">
              <input type="submit" value="añadir" class="btn marronOsc float-end">
        </div> 
    </form>
</section>