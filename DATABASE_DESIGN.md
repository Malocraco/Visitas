# Diseño de Base de Datos - Sistema de Agendamiento de Visitas

## Descripción General

Este documento describe la estructura de la base de datos para el sistema de agendamiento de visitas de la institución educativa. El sistema permite a visitantes solicitar tours/visitas, administradores gestionar las solicitudes, y super administradores tener control total del sistema.

## Estructura de Tablas

### 1. Tablas de Usuarios y Roles

#### `users`
Tabla principal de usuarios del sistema.
- **Campos principales:**
  - `id` - Identificador único
  - `name` - Nombre completo del usuario
  - `email` - Correo electrónico (único)
  - `password` - Contraseña hasheada
  - `email_verified_at` - Fecha de verificación de email
  - `remember_token` - Token para "recordar sesión"

#### `roles`
Define los roles disponibles en el sistema.
- **Campos:**
  - `id` - Identificador único
  - `name` - Nombre del rol (visitante, administrador, superadmin)
  - `display_name` - Nombre para mostrar
  - `description` - Descripción del rol

#### `user_roles`
Tabla pivot para la relación muchos a muchos entre usuarios y roles.
- **Campos:**
  - `user_id` - ID del usuario
  - `role_id` - ID del rol
  - `created_at`, `updated_at` - Timestamps

#### `permissions`
Define los permisos disponibles en el sistema.
- **Campos:**
  - `id` - Identificador único
  - `name` - Nombre del permiso
  - `display_name` - Nombre para mostrar
  - `description` - Descripción del permiso

#### `user_permissions`
Tabla pivot para permisos especiales otorgados a usuarios específicos.
- **Campos:**
  - `user_id` - ID del usuario
  - `permission_id` - ID del permiso
  - `granted_by` - ID del usuario que otorgó el permiso
  - `granted_at` - Fecha de otorgamiento
  - `expires_at` - Fecha de expiración (opcional)

### 2. Tablas de Actividades y Horarios

#### `visit_activities`
Define las actividades disponibles para las visitas.
- **Campos:**
  - `id` - Identificador único
  - `name` - Nombre de la actividad
  - `description` - Descripción detallada
  - `instructor` - Nombre del instructor
  - `duration_minutes` - Duración en minutos
  - `max_participants` - Máximo número de participantes
  - `is_active` - Si la actividad está disponible
  - `requirements` - Requisitos especiales (ropa, equipamiento)
  - `location` - Ubicación de la actividad

#### `visit_schedules`
Define los horarios disponibles para las visitas.
- **Campos:**
  - `id` - Identificador único
  - `day_of_week` - Día de la semana
  - `start_time` - Hora de inicio
  - `end_time` - Hora de fin
  - `max_visits_per_slot` - Máximo de visitas por horario
  - `is_active` - Si el horario está disponible
  - `notes` - Notas adicionales

### 3. Tablas de Visitas

#### `visits`
Tabla principal de solicitudes de visita.
- **Campos principales:**
  - `id` - Identificador único
  - `user_id` - ID del visitante que solicita
  - `institution_name` - Nombre de la institución/empresa
  - `contact_person` - Persona de contacto
  - `contact_email` - Email de contacto
  - `contact_phone` - Teléfono de contacto
  - `contact_position` - Cargo de la persona de contacto
  - `institution_description` - Descripción de la institución
  - `institution_type` - Tipo de institución (Universidad, Empresa, etc.)
  - `expected_participants` - Número de participantes esperados
  - `preferred_date` - Fecha preferida
  - `preferred_start_time` - Hora de inicio preferida
  - `preferred_end_time` - Hora de fin preferida
  - `visit_purpose` - Propósito de la visita
  - `special_requirements` - Requisitos especiales
  - `status` - Estado de la solicitud (pending, under_review, approved, rejected, completed, cancelled)
  - `assigned_admin_id` - ID del administrador asignado
  - `approved_by` - ID del administrador que aprobó
  - `approved_at` - Fecha de aprobación
  - `confirmed_date` - Fecha confirmada después de negociación
  - `confirmed_start_time` - Hora de inicio confirmada
  - `confirmed_end_time` - Hora de fin confirmada
  - `admin_notes` - Notas del administrador
  - `rejection_reason` - Razón del rechazo
  - `restaurant_service` - Si requiere servicio de restaurante
  - `restaurant_participants` - Número de personas para restaurante
  - `restaurant_notes` - Notas para el restaurante

#### `visit_activities_visits`
Tabla pivot para la relación entre visitas y actividades.
- **Campos:**
  - `visit_id` - ID de la visita
  - `visit_activity_id` - ID de la actividad
  - `participants` - Número de participantes para esta actividad
  - `notes` - Notas específicas para esta actividad

### 4. Tablas de Comunicación y Seguimiento

#### `visit_messages`
Mensajes del chat privado entre visitante y administrador.
- **Campos:**
  - `id` - Identificador único
  - `visit_id` - ID de la visita
  - `user_id` - ID del usuario que envía el mensaje
  - `message` - Contenido del mensaje
  - `is_read` - Si el mensaje ha sido leído
  - `read_at` - Fecha de lectura

#### `visit_attendees`
Lista de asistentes a la visita.
- **Campos:**
  - `id` - Identificador único
  - `visit_id` - ID de la visita
  - `name` - Nombre del asistente
  - `email` - Email del asistente
  - `phone` - Teléfono del asistente
  - `position` - Cargo o posición
  - `identification_number` - Número de identificación
  - `special_requirements` - Requisitos especiales
  - `is_contact_person` - Si es la persona de contacto principal

#### `visit_logs`
Registro de todas las acciones realizadas en el sistema.
- **Campos:**
  - `id` - Identificador único
  - `visit_id` - ID de la visita
  - `user_id` - ID del usuario que realizó la acción
  - `action` - Tipo de acción (create, update, approve, reject, etc.)
  - `description` - Descripción detallada de la acción
  - `old_values` - Valores anteriores (JSON)
  - `new_values` - Valores nuevos (JSON)
  - `ip_address` - Dirección IP
  - `user_agent` - User agent del navegador

## Roles y Permisos

### Roles Disponibles

1. **visitante** - Usuarios que pueden solicitar visitas
2. **administrador** - Administradores que gestionan visitas aprobadas
3. **superadmin** - Super administrador con todos los permisos

### Permisos Principales

- `create_visits` - Crear solicitudes de visita
- `view_own_visits` - Ver propias visitas
- `view_all_visits` - Ver todas las visitas
- `approve_visits` - Aprobar visitas
- `reject_visits` - Rechazar visitas
- `modify_visits` - Modificar visitas
- `assign_visits` - Asignar visitas a otros administradores
- `manage_activities` - Gestionar actividades
- `manage_schedules` - Gestionar horarios
- `manage_users` - Gestionar usuarios
- `grant_permissions` - Otorgar permisos especiales
- `view_logs` - Ver logs del sistema

## Flujo de Trabajo

### 1. Solicitud de Visita
1. El visitante se registra/inicia sesión
2. Completa el formulario de solicitud de visita
3. Selecciona actividades de interés
4. Elige fecha y horario preferido
5. Envía la solicitud (status: pending)

### 2. Revisión y Negociación
1. El SuperAdmin recibe la solicitud
2. Puede asignar la solicitud a un Administrador específico
3. Se inicia el chat privado para negociar detalles
4. Se pueden modificar fechas, horarios, actividades
5. Status cambia a "under_review"

### 3. Aprobación/Rechazo
1. El administrador asignado aprueba o rechaza la solicitud
2. Si se aprueba, se confirma fecha y horario final
3. Status cambia a "approved" o "rejected"
4. Se registra en logs

### 4. Ejecución
1. El administrador puede ver las visitas aprobadas
2. Se registra cuando la visita se completa
3. Status cambia a "completed"

## Consideraciones de Seguridad

1. **Auditoría completa** - Todas las acciones se registran en logs
2. **Permisos granulares** - Sistema de permisos flexible
3. **Validación de datos** - Validación en frontend y backend
4. **Protección CSRF** - Tokens CSRF en formularios
5. **Sanitización** - Limpieza de datos de entrada

## Datos de Prueba

El sistema incluye seeders con:
- Roles y permisos predefinidos
- Actividades basadas en el cronograma real
- Horarios disponibles
- Usuario SuperAdmin inicial (superadmin@institucion.com / password123)

## Comandos de Instalación

```bash
# Ejecutar migraciones
php artisan migrate

# Ejecutar seeders
php artisan db:seed

# Ejecutar seeders específicos
php artisan db:seed --class=SuperAdminSeeder
```

## Notas de Implementación

1. **Formulario de Visitante**: Incluye todos los campos necesarios basados en las capturas de pantalla proporcionadas
2. **Sistema de Chat**: Permite comunicación privada entre visitante y administrador
3. **Gestión de Permisos**: Sistema flexible para otorgar permisos temporales
4. **Logs de Auditoría**: Registro completo de todas las acciones
5. **Restaurante**: Integración con servicio de restaurante ($12,000 por persona)
6. **Requisitos Especiales**: Manejo de requisitos de vestimenta y equipamiento
