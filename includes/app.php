<?php

require __DIR__ . '/../vendor/autoload.php';
//Antes de conectar a la base de datos
//Crear una instancia de dotenv con el archivo .env en la dirección
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
//Si el archivo no existe, no marca error, para continuar con la ejecución del código
$dotenv->safeLoad();

require 'funciones.php';
require 'database.php';

// Conectarnos a la base de datos
use Model\ActiveRecord;

ActiveRecord::setDB($db);
