# Tareas del Sprint 1: Sistema de Comandas - Taquería Tacos Roy

**Reglas:** hacer una tarea a la vez, probarla en el navegador y hacer commit antes de pasar a la siguiente.

## 1. Preparación
- [X] 1.1 Crear la estructura de carpetas MVC (models, views, controllers, public, config, database, docs).
- [X] 1.2 Crear el archivo de configuración `.env` en la raíz del proyecto con las credenciales de la base de datos.
- [X] 1.3 Crear la conexión con PDO (`config/database.php`) leyendo las variables del `.env`.
- [X] 1.4 Crear el archivo `config/init_admin.php` para insertar el administrador inicial (solo si no existe), con contraseña encriptada con `password_hash()`.
- [X] 1.5 Verificar que `.gitignore` excluya `config/database.php` y `.env`.

## 2. Base de datos
- [X] 2.1 Conectar la aplicación mediante PDO a la base de datos `taqueriadb` existente. **No crear, modificar ni eliminar la base de datos ni sus tablas.**
- [X] 2.2 Utilizar las tablas existentes: `usuarios`, `roles`, `mesas`, `categorias`, `productos`, `insumos`, `metodos_pago`, `ordenes`, `detalle_ordenes`, `pagos`, `movimientos`.

## 3. Modelos
- [X] 3.1 Crear `models/Usuario.php` con los métodos: `buscarPorUsuario`, `crear`, `editar`, `desactivar`, `listar`.
- [X] 3.2 Crear `models/Mesa.php` con los métodos: `obtenerTodas`, `obtenerPorId`, `actualizarEstado`, `actualizarCapacidad`.
- [X] 3.3 Crear `models/Producto.php` con los métodos: `listar`, `obtenerPorCategoria`.
- [X] 3.4 Crear `models/Orden.php` con los métodos: `crear`, `agregarDetalle`, `calcularTotal`, `obtenerPorMesa`.

## 4. Inicio y cierre de sesión (HU-01)
- [X] 4.0 Crear `public/index.php` como punto de entrada y enrutador principal.
- [X] 4.1 Crear la vista `views/login.php` con formulario, Bootstrap 5 y campos en rojo cuando falten.
- [X] 4.2 Crear `controllers/AuthController.php` con el método `login()` usando `password_verify()`.
- [X] 4.3 Mostrar el mensaje "Usuario o contraseña incorrectos" y bloquear cuentas desactivadas.
- [X] 4.4 Crear el cierre de sesión que destruye la sesión y regresa al login.
- [X] 4.5 Implementar el cierre de sesión por inactividad (10 minutos + aviso de 10 segundos).

## 5. Control de acceso (HU-01)
- [X] 5.1 Redirigir al login a quien no tenga sesión iniciada.
- [X] 5.2 Redirigir según rol después del login:
  - Administrador → Panel de administración.
  - Cajero → Panel de caja.
  - Mesero → Mapa de mesas.
- [X] 5.3 Negar el acceso a la gestión de usuarios si el rol no es Administrador.

## 6. Gestión de cuentas (HU-01)
- [X] 6.1 Crear la vista `views/usuarios/lista.php` con la tabla de usuarios.
- [X] 6.2 Crear la vista `views/usuarios/formulario.php` para crear y editar usuarios.
- [X] 6.3 Crear `controllers/UsuarioController.php` con crear, editar y desactivar, guardando la contraseña con `password_hash()`.
- [X] 6.4 Validar contraseñaS.

## 7. Mapa de Mesas (HU-02 y HU-04)
- [X] 7.1 Crear la vista `views/mesas.php` con grid responsive de tarjetas.
- [X] 7.2 Crear `controllers/MesasController.php` con los métodos: `index`, `ocupar`, `liberar`, `solicitarCuenta`.
- [X] 7.3 Implementar el cambio de estado de mesa: Libre → Ocupada → Cuenta → Libre.
- [X] 7.4 Implementar el registro del número de comensales al ocupar una mesa.
- [X] 7.5 Implementar la barra de filtros (Todas, Libres, Ocupadas, Pendiente Pago) con contadores dinámicos.
- [X] 7.6 Implementar codificación por color: Verde (Libre), Rojo (Ocupada), Amarillo (Cuenta).
- [X] 7.7 Crear los modales: Abrir Mesa, Ver Comanda, Cobrar Ticket.

## 8. Toma de Comandas (HU-03)
- [ ] 8.1 Crear la vista `views/comanda.php` con menú a la izquierda y resumen a la derecha.
- [ ] 8.2 Crear `controllers/ComandaController.php` con los métodos: `index`, `agregarProducto`, `eliminarProducto`, `enviarACocina`.
- [ ] 8.3 Implementar la selección de productos y cantidad.
- [ ] 8.4 Implementar el campo de notas/observaciones.
- [ ] 8.5 Implementar el cálculo automático de subtotales.
- [ ] 8.6 Implementar el envío de comanda a la cocina.
- [ ] 8.7 Registrar la comanda en la base de datos (`ordenes` y `detalle_ordenes`).

## 9. Cierre del Sprint
- [ ] 9.1 Probar manualmente todos los criterios de aceptación de `requirements.md`.
- [ ] 9.2 Verificar que el login funcione con los 3 roles (Administrador, Cajero, Mesero).
- [ ] 9.3 Verificar que el mapa de mesas muestre los colores correctos.
- [ ] 9.4 Verificar que la comanda se envíe correctamente.
- [ ] 9.5 Actualizar el `README.md` con las instrucciones para ejecutar el proyecto.