<?php
// public/index.php

// 1. Cargar el autoloader de Composer (para usar Guzzle)
require __DIR__ . '/../vendor/autoload.php';

// 2. Cargar variables de entorno (usaremos una función simple para esto)
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// 3. Incluir el Controlador
require __DIR__ . '/../src/Controllers/MapController.php';
require __DIR__ . '/../src/Models/ApiModel.php';

use App\Controllers\MapController;

// 4. Enrutamiento simple: Si hay datos POST, calcula la ruta; si no, muestra el formulario.
$controller = new MapController();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['puntoA']) && isset($_POST['puntoB'])) {
    $controller->calculateRoute($_POST['puntoA'], $_POST['puntoB']);
} else {
    $controller->showForm();
}