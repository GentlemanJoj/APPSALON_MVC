<h1 class="nombre-pagina">Restablecer contraseña</h1>
<p class="descripcion-pagina">Escribe tu nueva contraseña</p>

<?php include_once __DIR__ . '/../templates/alertas.php'; ?>

<?php if ($error) return ?>
<form class="formulario" method="POST">
    <div class="campo">
        <label for="password"> Contraseña </label>
        <input type="password" id="password" placeholder="Tu Contraseña" name="password">
    </div>

    <input type="submit" class="boton" value="Cambiar Contraseña">
</form>

<div class="acciones">
    <a href="/">Iniciar Sesión</a>
    <a href="/crear-cuenta">Registrarse</a>
</div>