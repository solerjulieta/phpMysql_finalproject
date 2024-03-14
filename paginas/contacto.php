<section id="contacto" class="container space">
    <div class="row align-items-lg-center justify-content-lg-evenly">
        <div id="txtContacto" class="col-lg-4">
            <h2>Envianos tu <span>CONSULTA</span></h2>
            <p><i class="bi bi-geo-alt"></i> San Lorenzo 155, Nueva Córdoba, Córdoba.</p>
            <p><i class="bi bi-clock"></i> Lunes a Viernes de 10am a 21pm.</p>
            <p><i class="bi bi-envelope"></i> tumate@gmail.com</p>
            <p><i class="bi bi-whatsapp"></i> 3515555555</p>
        </div>
        <div class="col-lg-5">
            <form class="row justify-content-center" action="index.php?s=gracias" method="post" enctype="application/x-www-form-urlencoded">
                <div class="col-lg-10 col-md-8 mb-3">
                    <label for="nombre">Nombre*</label>
                    <input type="text" class="form-control" id="nombre" required>
                </div>
                <div class="col-lg-10 col-md-8 mb-3">
                    <label for="mail">Mail*</label>
                    <input type="text" class="form-control" id="mail" required>
                </div>
                <div class="col-lg-10 col-md-8 mb-3">
                    <label for="mensaje">Mensaje</label>
                    <textarea class="form-control" id="mensaje"></textarea>
                </div>
                <div class="col-lg-10 col-md-8">
                    <input type="submit" value="enviar" class="btn float-end marronOsc">
                </div>
            </form>
        </div> 
    </div> 
  </section> 