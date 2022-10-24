<h1 class="nombre-pagina">Crear Servicios</h1>
<p class="descripcion-pagina">Completa los campos para añadir un servicio</p>

<?php
include_once __DIR__ . '/../templates/barra.php';
include_once __DIR__ . '/../templates/alertas.php';
?>


<form class="formulario" action="/servicios/crear" method="POST">

    <?php
    include_once __DIR__ . '/formulario.php';
    ?>

    <input type="submit" class="boton" value="Crear Servicio">
</form>