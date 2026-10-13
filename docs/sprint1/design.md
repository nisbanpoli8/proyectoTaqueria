# Diseño del Sprint 1: Login y Roles de Usuario

## Diseño visual
- Bootstrap 5 y CSS personalizado.
- Fuentes:
  - Títulos: "Lilita One" (decorativa).
  - Texto: "Poppins" (legible).
- Campos vacíos o no válidos se marcarán en rojo.

## Vistas

### Login

**Elementos visuales**
- Fondo con textura sutil en tono arena claro.
- Tarjeta central con bordes redondeados y sombra suave.
- Logo circular de la taquería en la parte superior.
- Título: "Taquería Tacos Roy" (fuente decorativa, color marrón oscuro).

**Formulario del Usuario**
- Formulario del login con los campos **Usuario** y **Contraseña** únicamente.
- Botón principal: "ENTRAR AL SISTEMA".

**Campo Adicional**
- En la parte inferior de la página aparecerá la leyenda: "¿Problemas para conectarse? Contáctese con el administrador".

---

### Mapa de Mesas

**Elementos visuales**
- Fondo general en tono marrón oscuro con textura de puntos.
- Contenedor principal en tono arena claro con borde grueso marrón, esquinas redondeadas y patrón de textura sutil.
- Título exterior: "Comanda de Mesas" con icono de utensilios, sobre fondo amarillo con borde marrón.

**Header de control**
- Nombre de la taquería: "Tacos Roy", sobre fondo amarillo.
- Datos del usuario ingresado: Nombre del usuario.
- Botón para cerrar sesión con fondo rojo.

**Barra de filtros**
- Botones para filtrar por estado las mesas: Todas, Libres, Ocupadas, Pendiente Pago.
- Cada botón muestra un contador dinámico (número de mesas).
- Al seleccionar un filtro, el botón cambia a fondo marrón con texto amarillo.

**Tarjetas de mesas**
- Cada tarjeta contiene:
  - Número de mesa (fuente decorativa).
  - Capacidad de la mesa (icono de usuarios).
  - Estado de la mesa: Libre (verde), Ocupada (rojo), Cuenta (amarillo).
  - Resumen del consumo (solo si la mesa no está libre).
  - Botón de acción según el estado:
    - "Ocupar Mesa" si está libre.
    - "Ver Comanda" si está ocupada.
    - "Cobrar Ticket" si tiene cuenta pendiente.

### Comanda de Pedidos

**Elementos visuales**
- Fondo general en tono marrón oscuro con textura de puntos.
- Contenedor principal en tono arena claro con borde grueso marrón y esquinas redondeadas.
- Título exterior: "Comanda de Pedidos" con icono de recibo, sobre fondo amarillo con borde marrón.

**Header de control**
- Datos de la mesa: Número de mesa (ej. "MESA 02"), sobre fondo amarillo con borde marrón.
- Ocupantes: Número de personas (ej. "3 personas"), con icono de usuarios.
- Mesero: Nombre del mesero asignado (dinámico).
- Botón "Volver a Mesas": fondo marrón con texto amarillo e icono de flecha.

**Columna Izquierda: Menú**
- Encabezado "MENÚ" con icono de utensilios, sobre fondo amarillo con borde marrón.
- Lista de platillos: cada uno muestra nombre, precio y botón "Seleccionar".
- Los productos están en tarjetas con fondo amarillo claro y borde marrón.
- Pie del menú: leyenda "Presiona 'Seleccionar' para agregar items a la comanda".

**Columna Derecha: Resumen de Comanda**
- Encabezado "Resumen de Comanda" con icono de lista, sobre fondo amarillo con borde marrón.
- Tabla con encabezados: CANTIDAD, PRODUCTO, PRECIO.
- Cada fila muestra: cantidad (con botones + / -), nombre del producto y precio total del ítem.
- Campo de Subtotal: fondo amarillo claro con borde marrón, mostrando el monto total.
- Campo de Modificaciones: área de texto para notas (ej. "sin cebolla, poca salsa").

**Botones de acción**
- Botón "Cancelar": fondo rojo con icono de X, texto blanco.
- Botón "Enviar a Cocina": fondo amarillo con icono de fuego, texto marrón oscuro.

**Modal de notificaciones**
- Modal centrado con icono dinámico, título, mensaje y botón "Aceptar".
- Se usa para confirmar: comanda enviada, comanda cancelada, comanda vacía.