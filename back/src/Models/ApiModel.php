<?php
// src/Models/ApiModel.php

namespace App\Models;

use GuzzleHttp\Client;
use GuzzleHttp\Promise\Utils;
use Exception;

class ApiModel {
    
    private $client;
    private $googleApiKey;

    public function __construct() {
        $this->client = new Client(['timeout' => 10.0]);
        $this->googleApiKey = $_ENV['GOOGLE_API_KEY'] ?? '';

        if (empty($this->googleApiKey)) {
            error_log("ADVERTENCIA: La clave de Google API no está configurada en .env.");
        }
    }

    /**
     * Obtiene datos de ruta, coordenadas y URLs de imágenes de mapa en paralelo.
     */
    public function getFullRouteDetails(string $puntoA, string $puntoB): array {
        
        // 1. Crear las promesas (Geocoding de A, Geocoding de B, y la Ruta)
        $promises = [
            'route' => $this->getRoutePromise($puntoA, $puntoB),
            'geoA' => $this->getGeocodingPromise($puntoA),
            'geoB' => $this->getGeocodingPromise($puntoB),
        ];

        try {
            $results = Utils::settle($promises)->wait();
            
            // 2. Procesar resultados y verificar errores
            $geoA = $this->handlePromiseResult($results['geoA']);
            $geoB = $this->handlePromiseResult($results['geoB']);
            $routeResult = $this->handlePromiseResult($results['route']);

            if (isset($routeResult['error']) || isset($geoA['error']) || isset($geoB['error'])) {
                $errorMsg = $routeResult['error'] ?? $geoA['error'] ?? $geoB['error'] ?? 'Error desconocido en la API.';
                return ['error' => $errorMsg];
            }
            
            // 3. Generar URLs de imágenes de mapa estático
            $mapUrlA = $this->getStaticMapUrl($geoA['lat'], $geoA['lng']);
            $mapUrlB = $this->getStaticMapUrl($geoB['lat'], $geoB['lng']);
            
            // 4. Consolidar todos los resultados
            return array_merge($routeResult, [
                'fotoA' => $mapUrlA,
                'fotoB' => $mapUrlB,
            ]);

        } catch (Exception $e) {
            error_log("ERROR DE RED (Guzzle): " . $e->getMessage());
            return ['error' => 'Error de red grave o timeout de la API (Ver log).'];
        }
    }

    /**
     * Helper para manejar el resultado de una promesa de Utils::settle
     */
    private function handlePromiseResult(array $result): array {
        if ($result['state'] === 'fulfilled' && !isset($result['value']['error'])) {
            return $result['value'];
        }
        
        $errorMessage = $result['value']['error'] ?? 'Petición rechazada o fallida (Ver log del servidor PHP para más detalles).';
        
        if ($result['state'] === 'rejected') {
            error_log("API RECHAZADA: " . $result['reason']->getMessage());
        }
        
        return ['error' => $errorMessage];
    }

    /**
     * Crea una promesa para obtener datos de Geocoding (Lat/Lng).
     */
    private function getGeocodingPromise(string $address) {
        $url = 'https://maps.googleapis.com/maps/api/geocode/json';
        return $this->client->getAsync($url, [
            'query' => [
                'address' => $address,
                'key' => $this->googleApiKey
            ]
        ])->then(function ($response) {
            $data = json_decode($response->getBody(), true);
            
            if ($data['status'] !== 'OK' || empty($data['results'])) {
                return ['error' => "Ubicación '$address' no encontrada."];
            }
            
            $location = $data['results'][0]['geometry']['location'];
            return [
                'lat' => $location['lat'],
                'lng' => $location['lng'],
            ];
        });
    }

    /**
     * Crea una promesa para obtener datos de ruta de Google Directions (con unidades métricas).
     */
    private function getRoutePromise(string $origin, string $destination) {
        $url = 'https://maps.googleapis.com/maps/api/directions/json';
        return $this->client->getAsync($url, [
            'query' => [
                'origin' => $origin,
                'destination' => $destination,
                'key' => $this->googleApiKey,
                'units' => 'metric', // Forzar kilómetros
                'language' => 'es' // Forzar español
            ]
        ])->then(function ($response) {
            $data = json_decode($response->getBody(), true);
            
            if ($data['status'] !== 'OK' || empty($data['routes'])) {
                $statusMsg = $data['status'] ?? 'ERROR_DESCONOCIDO';
                return ['error' => 'Ruta no encontrada (Estado: ' . $statusMsg . ').'];
            }
            
            $route = $data['routes'][0]['legs'][0];
            $distanceMeters = $route['distance']['value'];
            
            return [
                'distancia' => $route['distance']['text'],
                'duracion' => $route['duration']['text'],
                'transporte' => $this->suggestTransport($distanceMeters),
            ];
        });
    }
    
    /**
     * Genera la URL para una imagen estática del mapa.
     */
    private function getStaticMapUrl(float $lat, float $lng): string {
        $center = "$lat,$lng";
        $marker = "color:red%7Clabel:P%7C$center";

        $url = 'https://maps.googleapis.com/maps/api/staticmap?';
        $params = [
            'center' => $center,
            'zoom' => 14,
            'size' => '600x400',
            'markers' => $marker,
            'key' => $this->googleApiKey,
        ];

        return $url . http_build_query($params);
    }
    
    private function suggestTransport(int $distanceMeters): string {
        if ($distanceMeters < 1000) {
            return "Caminar 🚶";
        } elseif ($distanceMeters < 10000) {
            return "Bicicleta 🚲 o Taxi 🚕";
        } elseif ($distanceMeters < 300000) {
            return "Coche 🚗 o Tren 🚄";
        } else {
            return "Avión ✈️ o Tren de Alta Velocidad 🚄";
        }
    }
}