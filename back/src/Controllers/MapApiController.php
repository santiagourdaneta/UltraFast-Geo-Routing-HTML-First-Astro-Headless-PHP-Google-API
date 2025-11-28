<?php
// src/Controllers/MapApiController.php

namespace App\Controllers;

use App\Models\ApiModel;

class MapApiController {
    
    private $apiModel;

    public function __construct() {
        $this->apiModel = new ApiModel();
    }

    /**
     * Calcula la ruta y obtiene detalles de fotos/mapas.
     * @param string $puntoA Origen
     * @param string $puntoB Destino
     * @return array
     */
    public function calculateRoute(string $puntoA, string $puntoB): array {
        // Llama al modelo para obtener todos los datos en paralelo (concurrencia)
        $fullData = $this->apiModel->getFullRouteDetails($puntoA, $puntoB);
        
        // Devolver el resultado (ya incluye 'error' si algo falló)
        return $fullData;
    }
}