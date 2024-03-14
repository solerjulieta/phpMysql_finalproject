<?php
use App\Modelos\Usuario;
use App\Modelos\Orden;
$usuarios = (new Usuario())->todoContenido();
?>
<section id="tabla-usuarios" class="container space">
    <h2>Administrar <span>Usuarios</span></h2>
    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Compras</th>
            </tr>
        </thead>
        <tbody>
        <?php
        foreach($usuarios as $usuario):
        ?>
            <tr>
                <td><span>Nombre:</span><?= $usuario->getNombre(); ?></td>
                <td><span>Apellido:</span><?= $usuario->getApellido(); ?></td>
                <td><span>Email:</span><?= $usuario->getEmail(); ?></td>
                <td><span>Rol:</span><?= $usuario->getRolUsuario()->getNombre(); ?></td>
                <td>
                    <span>Compras:</span>
                    <?php
                    $ordenes = (new Orden())->obtenerOrdenes($usuario->getUsuarioId());
                    if($ordenes):
                    ?>
                    <a href="index.php?s=compras-usuario&id=<?= $usuario->getUsuarioId(); ?>" class="btn compras">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bag" viewBox="0 0 16 16">
                        <path d="M8 1a2.5 2.5 0 0 1 2.5 2.5V4h-5v-.5A2.5 2.5 0 0 1 8 1zm3.5 3v-.5a3.5 3.5 0 1 0-7 0V4H1v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V4h-3.5zM2 5h12v9a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V5z"/>
                        </svg>
                    </a>
                    <?php
                    else:
                    ?>
                    <p>Sin pedido</p>
                    <?php
                    endif;
                    ?>
                </td>
            </tr>
        <?php
        endforeach;
        ?>
        </tbody>
    </table>
</section>