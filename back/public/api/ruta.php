<?php
// public/api/ruta.php

use App\Controllers\MapApiController; 

// Crear una instancia del controlador
$controller = new MapApiController();

// 1. Obtener inputs y validación de método
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    header('Content-Type: application/json');
    http_response_code(405); 
    echo json_encode(['error' => 'Método no permitido. Use GET.']);
    exit();
}

$puntoA = $_GET['a'] ?? '';
$puntoB = $_GET['b'] ?? '';

// 2. Ejecutar la lógica y CAPTURAR el resultado
$routeData = $controller->calculateRoute($puntoA, $puntoB);

// 3. ENVIAR el resultado al frontend (Astro) como JSON

// Establecer la cabecera correcta
header('Content-Type: application/json'); 

// Imprimir la respuesta JSON
echo json_encode($routeData);

// Terminar el script
exit();