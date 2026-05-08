# 🌮 TaquerosWeb — Sistema SaaS de Clientes y Órdenes

Backend completo en PHP 8 + MySQL que extiende `index.html` con gestión de
registros, órdenes, verificación de correo, pagos con MercadoPago y dashboard
de usuario.

---

## 📦 Stack

| Capa        | Tecnología |
|-------------|-----------|
| Backend     | PHP 8.1+ |
| Base de datos | MySQL 8.0+ |
| Correos     | PHPMailer 6 (SMTP) |
| Pagos       | MercadoPago SDK v2 |
| Env         | vlucas/phpdotenv |
| Arquitectura | MVC — Controllers / Services / Repositories / DTOs |

---

## 🗂️ Estructura del Proyecto

```
/
├── index.php / index.html    ← Landing (NO modificar directamente)
├── patch-index.php           ← Script para aplicar cambios al index
├── login.php                 ← Página de login
├── dashboard.php             ← Panel del cliente (requiere auth)
├── logout.php                ← Cierra sesión
├── verify-email.php          ← Confirma el token de correo
│
├── api/
│   ├── register.php          ← POST /api/register
│   ├── login.php             ← POST /api/login
│   ├── orders.php            ← GET|POST /api/orders
│   ├── packages.php          ← GET /api/packages
│   └── webhook-mp.php        ← POST /api/webhook-mp
│
├── app/
│   ├── Controllers/          ← HTTP handlers
│   ├── Services/             ← Business logic
│   ├── Repositories/         ← DB access layer
│   ├── DTOs/                 ← Input validation objects
│   └── Core/                 ← Database, Auth, Response
│
├── config/
│   └── app.php               ← Bootstrap (autoload, dotenv, session)
│
├── database/
│   ├── schema.sql            ← DDL completo
│   └── seeds.sql             ← Paquetes iniciales
│
├── composer.json
├── .env.example
└── .htaccess
```

---

## 🚀 Instalación

### 1. Clonar / subir archivos al servidor

```bash
# En local:
git clone ... && cd taquerosweb
# o simplemente sube los archivos vía FTP/SFTP al public_html
```

### 2. Instalar dependencias con Composer

```bash
composer install --no-dev --optimize-autoloader
```

### 3. Configurar el entorno

```bash
cp .env.example .env
nano .env   # editar con tus credenciales reales
```

Variables clave:
```
APP_URL=https://taquerosweb.com
APP_SECRET=genera_con: php -r "echo bin2hex(random_bytes(32));"

DB_HOST=localhost
DB_NAME=taquerosweb
DB_USER=tu_usuario
DB_PASS=tu_password

MAIL_HOST=mail.taquerosweb.com
MAIL_PORT=465
MAIL_ENCRYPTION=ssl
MAIL_USER=hola@taquerosweb.com
MAIL_PASS=tu_password_smtp
MAIL_FROM=hola@taquerosweb.com
MAIL_ADMIN=admin@taquerosweb.com

MP_ACCESS_TOKEN=APP_USR-xxxx       # de mercadopago.com/developers
MP_WEBHOOK_SECRET=tu_secreto_mp
```

### 4. Crear la base de datos

```bash
mysql -u root -p
CREATE DATABASE taquerosweb CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE taquerosweb;
SOURCE database/schema.sql;
SOURCE database/seeds.sql;
```

O desde phpMyAdmin: importar `schema.sql` y luego `seeds.sql`.

### 5. Aplicar parche al index

El script modifica el `index.html`/`index.php` existente sin reescribirlo:

```bash
# Si tu landing es index.html:
cp index.html index.php    # renombrar para que PHP lo procese
php patch-index.php index.php
```

> ⚠️ El patch busca cadenas exactas del HTML original. Si ya modificaste el
> index, aplica los cambios manualmente según indica el script.

### 6. Configurar el webhook en MercadoPago

En el panel de MercadoPago → Configuración → Notificaciones IPN:

```
URL: https://taquerosweb.com/api/webhook-mp
Eventos: payments
```

Copia el "Secreto de firma" generado y ponlo en `.env` como `MP_WEBHOOK_SECRET`.

---

## 🔄 Flujo Completo

```
1. Usuario entra a taquerosweb.com
2. Selecciona paquete en el formulario
3. POST /api/register
   ├── Crea cliente (email sin verificar)
   ├── Crea orden en estado "borrador"
   ├── Envía correo de verificación
   └── Notifica al admin
4. Cliente da clic en enlace del correo → /verificar-email?token=xxx
5. Cliente inicia sesión → POST /api/login
6. Dashboard: ve su orden en estado "borrador"
7. Click "Confirmar orden" → POST /api/orders/{id}/confirm
   └── Orden pasa a "pendiente_pago" + correo de confirmación
8. Click "Pagar ahora" → POST /api/orders/{id}/pay
   └── Redirige a MercadoPago
9. MercadoPago paga → webhook POST /api/webhook-mp
   ├── Orden pasa a "pagado"
   └── Correo de pago aprobado al cliente
```

---

## 🔌 API Reference

### POST /api/register
```json
{
  "nombre": "Juan",
  "apellidos": "Pérez",
  "telefono": "+52 55 1234 5678",
  "email": "juan@correo.com",
  "password": "mipassword",
  "password_confirmation": "mipassword",
  "paquete_id": 3,
  "descripcion": "Necesito una tienda para vender artesanías..."
}
```
**Response 200:**
```json
{ "success": true, "message": "¡Registro exitoso! Revisa tu correo...", "data": { "orden_id": 7 } }
```

---

### POST /api/login
```json
{ "email": "juan@correo.com", "password": "mipassword" }
```
**Response 200:**
```json
{ "success": true, "message": "Bienvenido, Juan!", "data": { "redirect": "https://taquerosweb.com/dashboard" } }
```

---

### GET /api/packages
```json
{
  "success": true,
  "data": [
    { "id": 1, "emoji": "🌮", "nombre": "Starter – Landing Express", "precio": "2199.00", "entrega": "3 días hábiles" },
    ...
  ]
}
```

---

### POST /api/orders
*(requiere sesión)*
```json
{ "paquete_id": 2, "descripcion": "Sitio para mi estética..." }
```

---

### POST /api/orders/{id}/confirm
*(requiere sesión)*
```json
{ "success": true, "message": "Orden confirmada. Procede al pago.", "data": { "id": 7, "estado": "pendiente_pago", ... } }
```

---

### POST /api/orders/{id}/pay
*(requiere sesión)*
```json
{ "success": true, "data": { "init_point": "https://www.mercadopago.com.mx/checkout/v1/redirect?...", "preference_id": "xxx" } }
```

---

## 🔐 Seguridad

- Contraseñas hasheadas con **bcrypt cost=12**
- Tokens de verificación generados con **random_bytes(32)**
- Sessions PHP con `cookie_httponly`, `cookie_samesite=Strict`, regeneración de ID en login
- Todas las queries via **PDO con parámetros preparados**
- Honeypot antispam en el formulario
- Validación en DTOs antes de llegar a los servicios
- Firma HMAC del webhook de MercadoPago verificada
- Acceso denegado a `app/`, `config/`, `database/`, `vendor/` via `.htaccess`
- Headers de seguridad: `X-Content-Type-Options`, `X-Frame-Options`, `X-XSS-Protection`

---

## 📧 Correos Implementados

| Trigger | Destinatario | Asunto |
|---------|-------------|--------|
| Registro | Cliente | Confirma tu correo |
| Registro | Admin | Nueva orden recibida |
| Orden confirmada | Cliente | Orden #X confirmada |
| Pago aprobado | Cliente | ¡Pago recibido! |

---

## 🛠️ Requisitos del Servidor

- PHP 8.1+
- Extensiones: `pdo_mysql`, `openssl`, `json`, `mbstring`, `curl`
- MySQL 8.0+
- mod_rewrite habilitado (Apache) o equivalente (Nginx)
- Composer

---

## 📈 Extensiones Futuras Recomendadas

- Panel de administración para gestionar órdenes (`/admin`)
- Notificaciones push / Slack al admin por nueva orden
- Subida de archivos del cliente (logo, brief)
- Reseteo de contraseña por correo
- Integración con CRM (HubSpot, Notion)
