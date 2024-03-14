<?php
$dataForm = $_SESSION['data_form'] ?? [];
unset($_SESSION['data_form']);
?>
<section class="container recuperarContrasena space">
    <div class="page-back">
        <a href="javascript:history.back()">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left-short" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M12 8a.5.5 0 0 1-.5.5H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5a.5.5 0 0 1 .5.5z"/>
        </svg> Atrás
        </a>
    </div>
    <h2>¿No recuerdas tu contraseña?</h2>
    <p>¡No te preocupes! Ingresá tu mail y te enviaremos un link para que puedas reestablecer una nueva.</p>
    <form action="acciones/auth-enviar-recuperacion.php" method="post" class="row">
        <div class="col-lg-7">
            <label for="email">Email</label>
            <input
                type="email"
                id="email"
                name="email"
                class="form-control"
                value="<?= $dataForm['email'] ?? null;?>"
            >
        </div>
        <div class="col-lg-7">
            <button class="btn float-end" type="submit">Recuperar contraseña</button>
        </div>
    </form>
</section>