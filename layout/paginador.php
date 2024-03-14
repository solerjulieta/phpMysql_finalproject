<?php
if(!empty($_GET['c'])){
    $c = $_GET['c'];
} else {
    $c = 1;
}
/** @var \App\Paginacion\Paginador $paginador */
if($paginador->getPaginas() > 1):
?>
    <nav id="paginador" aria-label="Page navigation example">
        <h2 class="visually-hidden">Navegación de páginas</h2>
        <ul class="pagination">
            <?php
            if($paginador->getPagina() > 1):
            ?>
            <li class="page-item shadow-none">
                <a class="page-link shadow-none" href="<?= $paginador->getUrl() . '&p=' . ($paginador->getPagina() - 1); ?>" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
            <?php
            else:
            ?>
            <li class="page-item disabled shadow-none">
                <a class="page-link shadow-none" href="<?= $paginador->getUrl() . '&p=' . ($paginador->getPagina() - 1); ?>" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
            <?php
            endif;
            ?>
        <?php
        for($i = 1; $i <= $paginador->getPaginas(); $i++):
        ?>
            <?php
            if($i === $paginador->getPagina()):
            ?>
            <li class="page-item active shadow-none" aria-current="page"><a class="page-link shadow-none" href="<?= '?s=productos&c=' . $c . '&p=' . $i ?>"><?= $i; ?></a></li>
            <?php
            else:
            ?>
            <li class="page-item shadow-none" aria-current="page"><a class="page-link shadow-none" href="<?= $paginador->getUrl() . '&p=' . $i; ?>"><?= $i; ?></a></li>
            <?php
            endif;
            ?>
        <?php
        endfor;
        ?>
            <?php
            if($paginador->getPagina() < $paginador->getPaginas()):
            ?>
            <li class="page-item shadow-none">
                <a class="page-link shadow-none" href="<?= $paginador->getUrl() . '&p=' . ($paginador->getPagina() + 1); ?>" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
            <?php
            else:
            ?>
            <li class="page-item shadow-none disabled">
                <a class="page-link shadow-none" href="<?= $paginador->getUrl() . '&p=' . ($paginador->getPagina() + 1); ?>" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
            <?php
            endif;
            ?>
        </ul>
    </nav>
<?php
endif;
?>