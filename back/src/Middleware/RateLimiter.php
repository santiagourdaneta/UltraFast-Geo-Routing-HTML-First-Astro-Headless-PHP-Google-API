<?php
// src/Middleware/RateLimiter.php

namespace App\Middleware;

class RateLimiter {
    // Almacenamos el conteo en memoria 
    private $requests = []; 
    private const MAX_REQUESTS = 5; // Límite: 5 peticiones por minuto
    private const TIME_WINDOW = 60; // 60 segundos (1 minuto)

    public function check() {
        $ip = $_SERVER['REMOTE_ADDR'];
        $currentTime = time();

        // 1. Limpiar registros viejos (más de 60 segundos)
        if (isset($this->requests[$ip])) {
            $this->requests[$ip] = array_filter($this->requests[$ip], function($timestamp) use ($currentTime) {
                return $timestamp > ($currentTime - self::TIME_WINDOW);
            });
        } else {
            $this->requests[$ip] = [];
        }

        // 2. Verificar el límite
        if (count($this->requests[$ip]) >= self::MAX_REQUESTS) {
            // Si el límite se excede, terminamos la ejecución
            header("Content-Type: application/json");
            http_response_code(429); // 429 Too Many Requests
            echo json_encode(['error' => 'Rate limit exceeded. Try again in one minute.']);
            exit();
        }

        // 3. Registrar la petición actual
        $this->requests[$ip][] = $currentTime;
    }
}

