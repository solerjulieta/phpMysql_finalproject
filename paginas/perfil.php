<?php
$usuario = (new \App\Auth\Autenticacion())->getUsuario();
$ordenes = (new \App\Modelos\Orden())->obtenerOrdenes($usuario->getUsuarioId());
?>
<section id="miPerfil" class="container space">
    <h2>Mi <span>PERFIL</span></h2>
    <?php
    if($usuario->getRolId() === 1):
    ?>
    <a class="btn btn-panel" href="panel/index.php?s=panel">Panel de administración</a>
    <?php
    endif;
    ?>
    <div id="info-perfil" class="row">
        <div class="col-lg-4">
            <h3>Información de la cuenta</h3>
            <dl class="usuario-datos">
                <dt>Email</dt>
                <dd><?= $usuario->getEmail();?></dd>
                <dt>Nombre</dt>
                <dd><?= $usuario->getNombre(); ?></dd>
                <dt>Apellido</dt>
                <dd><?= $usuario->getApellido(); ?></dd>
            </dl>
        </div>
        <?php
        if($usuario->getRolId() !== 1):
        ?>
        <div id="pedidos" class="col-lg-8 col-xl-6">
            <h3>Historial de pedidos</h3>
            <?php
            if(!$ordenes):
            ?>
            <div>
            <p>No ha realizado ningún pedido.</p>
            <a href="index.php?s=productos&c=1" class="btn">Ir a la tienda</a>
            </div>
            <?php
            else:
            ?>
            <table>
                <thead>
                    <tr>
                        <th>Nro Orden</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach($ordenes as $orden):
                    ?>
                    <tr>
                        <td><span>Nro Orden: </span><?= $orden->getOrdenId(); ?></td>
                        <td><span>Fecha: </span><?= $orden->getFechaPedido(); ?></td>
                        <td><span>Estado: </span><?= $orden->getEstado()->getNombreEstado(); ?></td>
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
        </div>
        <?php
        endif;
        ?>
    </div>
</section>