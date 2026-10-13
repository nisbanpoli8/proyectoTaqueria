# Requisitos del Sprint 1

## HU-01: Login y Autenticación por Roles

### Requisitos funcionales
- El sistema debe permitir el inicio de sesión con **usuario y contraseña**.
- Las contraseñas deben estar encriptadas con `password_hash()`.
- La validación se hace con `password_verify()`.
- El sistema debe redirigir al usuario según su rol:
  - Administrador → Panel de administración.
  - Cajero → Panel de caja.
  - Mesero → Mapa de mesas.
- Si las credenciales son incorrectas, mostrar mensaje de error.
- La sesión debe cerrarse por inactividad (con aviso de 10 segundos).
- Al hacer clic en "Cerrar Sesión", se debe destruir la sesión y redirigir al Login.

---

## HU-02: Mapa de Mesas y Capacidad

### Requisitos funcionales
- El mesero debe ver un mapa visual con todas las mesas.
- Cada mesa debe mostrar su estado: Libre, Ocupada, Cuenta.
- El mesero puede ocupar una mesa (cambia a "Ocupada").
- El mesero puede capturar el número de comensales al abrir una mesa.
- El mesero puede ver la comanda actual de una mesa ocupada.
- El mesero puede solicitar la cuenta (cambia a "Cuenta").
- El cajero puede cobrar el ticket y liberar la mesa (cambia a "Libre").

---

## HU-03: Toma de Comandas y Catálogo del Menú

### Requisitos funcionales
- El mesero puede seleccionar productos del menú.
- El mesero puede indicar la cantidad de cada producto.
- El sistema debe permitir notas/observaciones (ej. "sin cebolla").
- El sistema debe calcular automáticamente los subtotales.
- El sistema debe enviar la comanda a la cocina.
- Cada comanda debe registrarse en la base de datos.

---

## HU-04: Mapa Interactivo con Codificación por Color

### Requisitos funcionales
- El mapa de mesas debe usar colores: Verde (Libre), Rojo (Ocupada), Amarillo (Cuenta).
- El estado de las mesas debe actualizarse en tiempo real.
- El mapa debe reflejar los cambios de estado inmediatamente.
- Los filtros deben actualizar los contadores dinámicamente.


## Requisitos no funcionales

### Arquitectura y código
- Arquitectura **Modelo-Vista-Controlador (MVC)**.
- Consultas SQL con **PDO** y sentencias preparadas.
- Contraseñas encriptadas con `password_hash()`, nunca en texto plano.
- Validación con `password_verify()`.
- Comentarios en español en el código.

### Frontend
- **Framework CSS:** Bootstrap 5 y CSS personalizado.
- **Iconos:** FontAwesome.
- **Tipografías:** 
  - Títulos: "Lilita One" (decorativa).
  - Texto: "Poppins" (legible).
- JavaScript vanilla (sin jQuery ni frameworks pesados).
- Archivos JS separados en `public/assets/js/`.

### Seguridad
- Nunca subir `config/database.php` ni `.env` al repositorio.
- Usar variables de sesión para autenticación.
- Cerrar sesión por inactividad (10 minutos + aviso de 10 segundos).

### Documentación
- Actualizar `README.md` al agregar cada módulo.
- Documentar cada HU en `docs/sprintN/`.