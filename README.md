# Guía de Configuración del Proyecto Laravel

## Pasos de Instalación

### Configuración Inicial

1. **Instalar Dependencias**
   ```bash
   composer install
   ```

2. **Ejecutar Migraciones Centrales**
   ```bash
   php artisan migrate --path=database/migrations/central --seed
   ```
   > **Nota**: Todas las nuevas migraciones para el paquete central deben ubicarse en el directorio `database/migrations/central` y ejecutarse con:
   > ```bash
   > php artisan migrate --path=database/migrations/central
   > ```

3. **Crear Permisos Iniciales**
   ```bash
   php artisan create-permissions
   ```

### Configuración de Inquilinos (Tenants)

4. **Migraciones de Inquilinos**
   - Ubicación: `database/migrations/tenant`
   - Todas las migraciones específicas de inquilinos deben almacenarse en este directorio
   - Para ejecutar las migraciones de inquilinos, utiliza cualquiera de estos comandos:
     ```bash
     php artisan tenants:migrate
     ```
     o
     ```bash
     php artisan migrate --path=database/migrations/tenant
     ```
   > **Nota**: Al crear un nuevo inquilino, el sistema automáticamente:
   > - Creará su base de datos
   > - Ejecutará todas las migraciones de inquilinos y los permisos correspondientes

5. **Permisos de Inquilinos**
   - Cuando se creen nuevos permisos para los inquilinos, ejecuta:
     ```bash
     php artisan create create-permissions-all-tenants
     ```