# ⚡️ Cohete Rutas: Calculadora Instantánea (Astro + PHP Headless)

![GitHub Stars](https://img.shields.io/github/stars/[@santiagourdaneta]/[https://github.com/santiagourdaneta/UltraFast-Geo-Routing-HTML-First-Astro-Headless-PHP-Google-API]?style=flat-square&color=FFC300)
![GitHub Last Commit](https://img.shields.io/github/last-commit/[@santiagourdaneta]/[https://github.com/santiagourdaneta/UltraFast-Geo-Routing-HTML-First-Astro-Headless-PHP-Google-API]?style=flat-square&color=900C3F)
![Tech Stack](https://img.shields.io/badge/Stack-Astro%20%7C%20PHP%20%7C%20Guzzle-4CAF50?style=flat-square)

Este proyecto es un caso de estudio de **alto rendimiento web** que demuestra cómo una arquitectura **HTML-First** (Astro Frontend) alimentada por una **API Headless PHP** puede superar a las soluciones puramente *JavaScript-centric* en velocidad de carga y experiencia de usuario (UX).

La aplicación calcula rutas entre dos puntos geográficos usando API's de Google, priorizando el **rendimiento crudo** y la **eficiencia de recursos**.

---

## 🛠️ Arquitectura y Flujo de Datos

| Componente | Tecnología | Rol Principal |
| :--- | :--- | :--- |
| **Frontend** (`/front`) | **Astro (SSR/HTML-First)** | Carga inicial ultrarrápida, **View Transitions** para navegación SPA instantánea. |
| **Backend** (`/back`) | **PHP (8.2+) + Guzzle** | **API Headless** (solo JSON), Concurrencia Asíncrona para consultas API, Rate Limiting (Seguridad). |
| **Concurrencia** | **Guzzle Promises** | Lanza peticiones a Google Maps (Directions y Geocoding) en paralelo para reducir la latencia de respuesta. |
| **Datos** | **Google Maps API** | Dirección, Distancia y Duración. |

---

## 🚀 Características Clave y Optimización

### ⚡️ Perceived Performance (Instantaneidad)

* **HTML-First:** Astro genera todo el HTML en el servidor, eliminando el "costo de hidratación" y logrando que la página sea interactiva al instante.
* **View Transitions:** La navegación entre el formulario (`/`) y la página de resultados (`/ruta/...`) es fluida, simulando una SPA sin recarga.

### 🛡️ Seguridad y Calidad del Código

* **Rate Limiting:** Implementación de *middleware* en PHP (`router.php`) para limitar las peticiones por dirección IP, previniendo abusos y DDoS.
* **Validaciones HTML5:** Uso de `minlength` y `maxlength` en el formulario para detener peticiones malformadas antes de que lleguen al servidor PHP.
* **Código Limpio:** Uso de **PHPCBF** y **PHPCS** para asegurar que todo el código del backend cumpla con los estándares de estilo (PSR-12).

### 🖼️ SEO y UX

* **Meta Tags Completas:** Implementación de `og:`, `twitter:` y otras meta etiquetas para asegurar que los enlaces compartidos muestren vistas previas ricas y atractivas.
* **Unidades Métricas:** La API de PHP fuerza las respuestas de distancia en Kilómetros y texto en español.

---

## ⚙️ Instalación y Ejecución Local

Para ejecutar el proyecto, necesitarás **PHP 8.2+**, **Composer** y **Node.js/npm**.

### Paso 1: Configurar el Backend (`/back`)

1.  **Instalar dependencias de PHP:**
    ```bash
    cd back
    composer install
    ```
2.  **Configurar Clave API:** Crea el archivo **`.env`** en `/back/` con tu clave de Google Cloud. Asegúrate de tener habilitadas las APIs de **Geocoding y Directions**.

    ```dotenv
    # back/.env
    GOOGLE_API_KEY=[tu_clave_de_google_aqui]
    ```
3.  **Iniciar el Servidor PHP:** (Debe estar activo en este puerto para Astro)
    ```bash
    php -S localhost:8080 router.php
    ```

### Paso 2: Configurar el Frontend (`/front`)

1.  **Instalar dependencias de Node.js:**
    ```bash
    cd ../front
    npm install
    ```
2.  **Configurar URL de la API:** Crea el archivo **`.env`** en `/front/` para apuntar al servidor PHP:

    ```dotenv
    # front/.env
    PUBLIC_PHP_API_URL="http://localhost:8080/api/ruta.php"
    ```
3.  **Iniciar el Servidor Astro:**
    ```bash
    npm run dev
    ```

Accede a la aplicación en el navegador.

---

## 🛑 Rate Limiting (Importante)

El backend de PHP tiene un límite de **5 peticiones por minuto por IP** implementado en `router.php`. Si ves el error `Rate limit exceeded` o el timeout, espera 60 segundos antes de volver a intentarlo.