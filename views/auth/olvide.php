<h1 class="nombre-pagina">¿Olvidaste tu contraseña?</h1>
<p class="descripcion-pagina">Escribe tu correo para realizar el proceso de cambio</p>

<?php include_once __DIR__ . '/../templates/alertas.php'; ?>

<form class="formulario" method="POST" action="/olvide">
    <div class="campo">
        <label for="email">Email</label>
        <input type="email" placeholder="Tu Email" id="email" name="email">
    </div>

    <input type="submit" class="boton" value="Enviar instrucciones">
</form>

<div class="acciones">
    <a href="/">Iniciar Sesión</a>
    <a href="/crear-cuenta">Registrarse</a>
</div>