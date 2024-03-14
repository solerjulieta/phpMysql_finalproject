<?php
use App\Modelos\Usuario;
use App\Modelos\Orden;
$usuario = (new Usuario())->traerPorId($_GET['id']);
$ordenes = (new Orden())->obtenerOrdenes($_GET['id']);
$estados = (new \App\Modelos\OrdenEstado())->todoContenido();
$dataForm = $_SESSION['data_form'] ?? [];
if(!$usuario){
    require_once __DIR__ . '/404.php';
} else {
?>
<section id="compras-usuario" class="container space">
    <div class="page-back">
        <a href="javascript:history.back()">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left-short" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M12 8a.5.5 0 0 1-.5.5H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5a.5.5 0 0 1 .5.5z"/>
        </svg> Atrás
        </a>
    </div>
    <h2>Compras de <span><?= $usuario->getNombreCompleto(); ?></span></h2>
    <?php
    if(!$ordenes):
    ?>
    <p>El usuario no ha realizado ninguna compra.</p>
    <?php
    else:
    ?>
    <table>
        <thead>
            <tr>
                <th>Pedido</th>
                <th>Fecha</th>
                <th>Estado</th>
                <th>Detalle Pedido</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach($ordenes as $orden):
            ?>
            <tr>
                <td><span>Pedido: </span><?= $orden->getOrdenId(); ?></td>
                <td><span>Fecha: </span><?= $orden->getFechaPedido(); ?></td>
                <td>
                    <span>Estado: </span>
                    <form action="acciones/editar-orden.php" method="post">
                        <input type="hidden" name="orden_id" value="<?= $orden->getOrdenId(); ?>">
                        <input type="hidden" name="usuario_fk" value="<?= $_GET['id']; ?>">
                        <select name="orden_estado_fk" id="orden_estado_fk" class="form-select">
                            <?php
                            foreach($estados as $estado):
                            ?>
                            <option value="<?= $estado->getOrdenEstadoId(); ?>"
                            <?= $estado->getOrdenEstadoId() == ($dataForm['orden_estado_fk'] ?? $orden->getOrdenEstadoFk()) ? 'selected' : ''; ?>
                            >
                            <?= $estado->getNombreEstado(); ?>
                            </option>
                            <?php
                            endforeach;
                            ?>
                        </select>
                        <button class="btn actualizar">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-clockwise" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2v1z"/>
                            <path d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466z"/>
                            </svg>
                        </button>
                    </form>
                </td>
                <td>
                    <span>Detalle pedido: </span>
                    <?php
                    $itemsOrden = (new \App\Modelos\OrdenTieneProductos())->obtenerItemsOrden($orden->getOrdenId());
                    foreach($itemsOrden as $item):
                    ?>
                    <ul>
                        <li><span class="grisaceo">Producto: </span> <?= $item->getNombre(); ?></li>
                        <li><span class="grisaceo">Cantidad: </span> <?= $item->getCantidad(); ?></li>
                    </ul>
                    <?php
                    endforeach;
                    ?>
                </td>
                <td><span>Total: </span>$<?= $orden->getTotal(); ?></td>
            </tr>
            <?php
            endforeach;
            ?>
        </tbody>
    </table>
    <?php
    endif;
    ?>
</section>
<?php
}
?>