<h1 class="nombre-pagina">Login</h1>

<p class="descripcion-pagina">Inicia sesión con tus datos</p>

<?php include_once __DIR__ . '/../templates/alertas.php' ?>

<form class="formulario" action="/" method="POST">
    <div class="campo">
        <label for="email"> Email </label>
        <input type="email" id="email" placeholder="Tu Email" name="email" value="<?php echo s($usuario->email); ?>">
    </div>

    <div class="campo">
        <label for="password"> Contraseña </label>
        <input type="password" id="password" placeholder="Tu Contraseña" name="password">
    </div>

    <input type="submit" class="boton" value="Iniciar Sesión">
</form>

<div class="acciones">
    <a href="/crear-cuenta">Registrarse</a>
    <a href="/olvide">¿Olvidó su contraseña?</a>
</div>