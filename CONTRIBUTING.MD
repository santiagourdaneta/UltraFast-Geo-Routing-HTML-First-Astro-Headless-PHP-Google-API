# 🤝 Guía de Contribución para Cohete Rutas

¡Gracias por tu interés en contribuir a Cohete Rutas! Agradecemos cualquier reporte de errores, sugerencia de características o código nuevo que mejore la velocidad y robustez de esta arquitectura.

---

## 🐛 Reporte de Errores (Bugs)

Si encuentras un error (bug), sigue estos pasos:

1.  **Verifica la existencia:** Asegúrate de que el error no haya sido reportado ya en la sección de [Issues](https://github.com/santiagourdaneta/UltraFast-Geo-Routing-HTML-First-Astro-Headless-PHP-Google-API/issues).
2.  **Crea un Nuevo Issue:** Usa la plantilla de "Bug Report".
3.  **Proporciona Contexto:**
    * Pasos exactos para reproducir el error.
    * Versión de PHP, Node.js y sistema operativo que estás utilizando.
    * Mensaje de error completo del log (terminal).

---

## ✨ Sugerencia de Nuevas Características

Si tienes ideas para mejorar la aplicación (ej. una nueva métrica de ruta, un *refactoring* del Rate Limiter, etc.):

1.  **Crea un Nuevo Issue:** Usa la plantilla de "Feature Request".
2.  **Describe la necesidad:** Explica por qué la característica es necesaria y cómo mejoraría la experiencia del usuario o el rendimiento.

---

## 💻 Proceso de Contribución de Código (Pull Requests)

Para enviar código nuevo o arreglos, sigue el flujo estándar de GitHub:

### 1. Preparación

1.  **Haz un Fork** del repositorio a tu propia cuenta de GitHub.
2.  **Clona tu Fork** a tu máquina local.
    ```bash
    git clone [https://github.com/santiagourdaneta/UltraFast-Geo-Routing-HTML-First-Astro-Headless-PHP-Google-API.git](https://github.com/santiagourdaneta/UltraFast-Geo-Routing-HTML-First-Astro-Headless-PHP-Google-API.git)
    ```
3.  **Crea una nueva rama** para tu trabajo (ej. `fix/timeout-guzzle` o `feat/nuevo-middleware`).
    ```bash
    git checkout -b nombre-de-tu-rama
    ```

### 2. Estándares de Código

Antes de enviar un Pull Request (PR), asegúrate de que tu código cumpla con los estándares.

* **PHP:** Ejecuta el linter y el *fixer* para asegurar el cumplimiento de PSR-12:
    ```bash
    cd back
    ./vendor/bin/phpcbf src/
    ./vendor/bin/phpcs src/
    ```
* **Astro/JavaScript:** Asegúrate de que tu código JavaScript esté libre de errores.

### 3. Envío del Pull Request

1.  **Haz Commit** de tus cambios con un mensaje claro y descriptivo.
2.  **Haz Push** a tu rama en tu fork.
3.  **Crea un Pull Request** dirigido a la rama `main` del repositorio original.
4.  **Describe el PR:** Explica brevemente qué resuelve o añade tu contribución.

Agradecemos tu esfuerzo y te responderemos lo antes posible. ¡Feliz codificación!