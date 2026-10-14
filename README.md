# proyectoTaqueriia - Sistema de Comandas - Taquería Tacos Roy

Sistema web para la gestión de comandas y órdenes de la taquería Tacos Roy.

## 👥 Integrantes
- Nisban Alexis Diego Policarpo
- Ximena Itzel Hernandez García
- Ana Gabriela López González

**Semestre:** 5° Grupo: A  
**Asignatura:** Desarrollo e Implementación de Sistemas de Información  
**Docente:** Erahin Pacheco Luna

## 🛠️ Tecnologías
- **Backend:** PHP 8+
- **Base de Datos:** MySQL
- **Frontend:** HTML5, CSS3, JavaScript
- **Conexión a BD:** PDO con consultas preparadas
- **Control de Versiones:** Git y GitHub

## 🚀 Instalación

1. Clonar el repositorio:
   ```bash
   git clone https://github.com/nisbanpoli8/proyectoTaqueria.git
   ```

## Módulo de cocina

Inicia sesión con el rol **Encargado de Cocina** para consultar el estado del salón y las comandas pendientes en `index.php?action=cocina`. El tablero se refresca automáticamente cada 15 segundos. Desde cada comanda se puede iniciar la preparación o marcarla como lista; las órdenes listas se consultan en `index.php?action=cocina_listos` y pueden marcarse como servidas.