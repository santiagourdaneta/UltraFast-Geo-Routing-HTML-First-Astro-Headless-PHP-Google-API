<?php
// router.php

// 1. Cargar el autoloader de Composer desde la raíz del proyecto
// Esto hace que todas las clases de Guzzle, Dotenv, y tu aplicación sean accesibles.
require __DIR__ . '/vendor/autoload.php'; 

// 2. Cargar variables de entorno (.env)
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad(); // safeLoad evita errores si el .env no existe

// ----------------------------------------------------
// RATE LIMITER AQUÍ
// ----------------------------------------------------

require __DIR__ . '/src/Middleware/RateLimiter.php'; 

use App\Middleware\RateLimiter; // Usar la clase con su namespace

// 3. Ejecutar el Rate Limiter ANTES de cualquier ruta.
$limiter = new RateLimiter();
$limiter->check(); // Si excede el límite, detiene la ejecución y devuelve un 429.

// Lógica de ruteo:
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = $requestUri;

// Limitar la aplicación a servir solo el endpoint de la API
if ($path === '/api/ruta.php') {
    require __DIR__ . '/public/api/ruta.php';
} else {
    // Si intentan acceder a otro archivo, damos un 404
    http_response_code(404);
    echo "Endpoint no encontrado.";
}

// --- SEGURIDAD Y CONFIGURACIÓN ---
// Permitir peticiones desde Astro (CORS) - Necesario para la comunicación entre localhost:3000 y localhost:8080
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Manejo de pre-vuelos OPTIONS de CORS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}
// --- FIN SEGURIDAD ---


// 4. Lógica de Enrutamiento del Servidor PHP Incorporado
// Si el servidor PHP es llamado para un archivo estático (CSS, imágenes) que existe, lo sirve.
// Esto es necesario para el router de 'php -S'.
if (php_sapi_name() === 'cli-server' && file_exists(__DIR__ . '/' . $_SERVER['REQUEST_URI'])) {
    return false;
}


// 5. Punto de entrada de la API
// Todas las demás peticiones que no sean archivos estáticos serán dirigidas aquí.
require 'public/api/ruta.php';