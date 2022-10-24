<h1 class="nombre-pagina">Confirmación de cuenta</h1>

<?php include_once __DIR__ . '/../templates/alertas.php';

if ($confirmado) { ?>
    <div class="acciones">
        <a href="/">Iniciar Sesión</a>
    </div>
<?php
} else { ?>
    <p class="descripcion-pagina">Lo sentimos, solicitud rechazada</p>
<?php
}
?>