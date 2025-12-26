# Sistema Plaza - Implementación Mock (Sin Base de Datos)

## 📋 Descripción

Este es el backend completo del sistema Plaza implementado con datos mock en memoria. **No requiere base de datos** para funcionar. Todos los datos se almacenan en arrays PHP durante la ejecución.

## 🚀 Inicio Rápido

### Usuarios de Prueba Predefinidos

El sistema viene con 5 usuarios de prueba ya configurados:

| Email | Contraseña | Rol | Estado |
|-------|------------|-----|--------|
| owner@example.com | password123 | Owner | Activo |
| manager@example.com | password123 | Shop Manager | Activo |
| logistics@example.com | password123 | Logistics | Activo |
| editor@example.com | password123 | Editor (Custom) | Activo |
| pending@example.com | password123 | - | Pendiente |

### Tiendas de Prueba

- **Tienda 1**: Zapatillas Chile (owner: owner@example.com)
- **Tienda 2**: Ropa Deportiva MX (owner: owner@example.com)
- **Tienda 3**: Accesorios AR (owner: manager@example.com)

### Membresías de Prueba

- **owner@example.com**: Owner en Tienda 1 y Tienda 2
- **manager@example.com**: Shop Manager en Tienda 1, Owner en Tienda 3
- **logistics@example.com**: Logistics en Tienda 1 (modo normal)
- **editor@example.com**: Editor en Tienda 1 (modo CUSTOM con overrides)

## 🔐 Rutas Disponibles

### URLs de Producción

Base URL: `https://laravel.narkan.cl/public/`

### Autenticación

- `GET /plaza/login` - Formulario de login
  - URL completa: `https://laravel.narkan.cl/public/plaza/login`
- `POST /plaza/login` - Procesar login
- `POST /plaza/logout` - Cerrar sesión
- `GET /plaza/forgot-password` - Solicitar reset de contraseña
- `POST /plaza/forgot-password` - Enviar token de reset
- `GET /plaza/reset-password/{token}` - Formulario de reset
- `POST /plaza/reset-password` - Procesar reset

### Dashboard y Tiendas

- `GET /plaza/stores/select` - Seleccionar tienda (después de login)
  - URL completa: `https://laravel.narkan.cl/public/plaza/stores/select`
- `POST /plaza/stores/{storeId}/set-active` - Establecer tienda activa
- `GET /plaza/dashboard` - Dashboard principal (requiere tienda seleccionada)
  - URL completa: `https://laravel.narkan.cl/public/plaza/dashboard`

### Pruebas de Permisos

- `GET /plaza/test-permission/{capability}` - Probar si tienes un permiso específico
- `GET /plaza/orders` - Ver pedidos (requiere `orders.view`)
- `POST /plaza/orders/{id}/update` - Actualizar pedido (requiere `orders.manage`)
- `GET /plaza/products` - Ver productos (requiere `products.manage`)

## 🧪 Ejemplos de Prueba

### 1. Login y Selección de Tienda

**Paso 1:** Accede al login
```
https://laravel.narkan.cl/public/plaza/login
```

**Paso 2:** Ingresa credenciales
- Email: `editor@example.com`
- Contraseña: `password123`

**Paso 3:** Selecciona una tienda de la lista

**Paso 4:** Accede al dashboard
```
https://laravel.narkan.cl/public/plaza/dashboard
```

### 2. Prueba Rápida (Navegador)

1. Ve a: `https://laravel.narkan.cl/public/plaza/login`
2. Usa: `owner@example.com` / `password123`
3. Selecciona una tienda
4. Verás el dashboard con todos tus permisos

### 2. Probar Permisos

```bash
# Verificar si puedes ver pedidos
GET /plaza/test-permission/orders.view

# Verificar si puedes gestionar productos
GET /plaza/test-permission/products.manage

# Verificar si puedes gestionar reembolsos (editor NO debería poder)
GET /plaza/test-permission/orders.refund
```

### 3. Acceder a Rutas Protegidas

```bash
# Intentar ver pedidos (editor tiene este permiso por override)
GET /plaza/orders

# Intentar gestionar productos (editor tiene este permiso por rol)
GET /plaza/products
```

## 📊 Permisos por Rol

### Owner
- ✅ **TODOS** los permisos

### Shop Manager
- ✅ Casi todo excepto `settings.manage` y `users.manage`

### Logistics
- ✅ `orders.view`, `orders.manage`, `orders.tracking`, `orders.refund`
- ✅ `customers.view`

### Editor (Base)
- ✅ `products.view`, `products.manage`, `stock.manage`

### Editor (Custom Mode - Usuario 4 en Tienda 1)
- ✅ Permisos base de Editor
- ✅ `orders.view` (override: granted)
- ✅ `coupons.manage` (override: granted)
- ❌ `orders.refund` (override: denied)

## 🔧 Estructura del Sistema

```
app/
├── Data/                    # DTOs/Value Objects
├── Repositories/
│   ├── Contracts/          # Interfaces
│   └── Mock/               # Implementaciones mock
├── Services/               # Lógica de negocio
├── Http/
│   ├── Controllers/        # Controladores
│   └── Middleware/        # Middleware de autenticación y permisos
└── Helpers/                # Funciones helper globales
```

## 🎯 Funcionalidades Implementadas

✅ Autenticación completa con protección contra fuerza bruta
✅ Sistema de permisos híbrido (roles + custom overrides)
✅ Multi-tenant (múltiples tiendas por usuario)
✅ Auditoría de eventos
✅ Tokens de verificación y reset de contraseña
✅ Middleware de protección de rutas
✅ Helpers globales para verificación de permisos

## 🔄 Migración a Base de Datos Real

Cuando tengas la base de datos lista:

1. Crear migraciones para las tablas `plaza_*`
2. Crear modelos Eloquent
3. Implementar repositorios reales que usen Eloquent
4. Cambiar los bindings en `PlazaRepositoryServiceProvider`
5. **Sin cambios necesarios en servicios/controladores** - funcionan igual

## 📝 Notas

- Los datos se resetean en cada reinicio del servidor (son en memoria)
- Para desarrollo, el token de reset se muestra en la respuesta (solo en desarrollo)
- Las sesiones se almacenan normalmente en Laravel (cache/session)
- El sistema está listo para probar toda la lógica sin necesidad de BD

