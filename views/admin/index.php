<h1 class="nombre-pagina">Buscar Citas</h1>

<?php include_once __DIR__ . '/../templates/barra.php' ?>

<h2>Buscar Citas</h2>
<div class="busqueda">
    <form class="formulario">
        <div class="campo">
            <label for="fecha">Fecha</label>
            <input type="date" id="fecha" name="fecha" value="<?php echo $fecha ?>">
        </div>
    </form>
</div>

<?php
if (count($citas) === 0) {
    echo '<h2>No hay citas programadas</h2>';
}
?>

<div id="citas-admin">
    <ul class="citas">
        <?php
        $idCita = 0;
        foreach ($citas as $key => $cita) {
            if ($idCita !== $cita->id) {
        ?>
                <li>
                    <p>ID: <span><?php echo $cita->id; ?></span></p>
                    <p>Hora: <span><?php echo $cita->hora; ?></span></p>
                    <p>Cliente: <span><?php echo $cita->cliente; ?></span></p>
                    <p>Télefono: <span><?php echo $cita->telefono; ?></span></p>

                    <h3>Servicios</h3>
                <?php
                $totalCita = 0;
                $idCita = $cita->id;
            }
                ?>
                <p>Servicio: <span><?php echo $cita->servicio . ' $ ' . $cita->precio ?></span></p>

                <?php
                $totalCita = $totalCita + $cita->precio;

                $actual = $cita->id;
                $proximo = $citas[$key + 1]->id ?? 0;

                if ($proximo !== $actual) {
                ?>
                    <p>Total: <span><?php echo $totalCita ?></span></p>

                    <form action="/api/eliminar" method="POST">
                        <input type="hidden" name="id" value="<?php echo $cita->id; ?>">
                        <input type="submit" class="boton-eliminar" value="Eliminar">
                    </form>
                <?php
                }
                ?>

            <?php
        }
            ?>

    </ul>
</div>

<?php

$script = "<script src='build/js/buscador.js'></script>";

?>