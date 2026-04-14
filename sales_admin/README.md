# 🌐 WebAdmin — Panel de Administración de Ventas

Sistema administrativo completo para agencias de desarrollo web. Gestiona clientes, ventas y el ciclo de vida de los proyectos desde un panel centralizado.

---

## ✨ Funcionalidades

| Módulo | Funciones |
|---|---|
| **Auth** | Login seguro, sesiones, cambio de contraseña, CSRF |
| **Clientes** | CRUD completo, búsqueda, historial de ventas por cliente |
| **Ventas** | CRUD, filtros por estado/servicio, múltiples métodos de pago |
| **Proyectos** | Estado del proyecto 1:1 con cada venta, URL, notas |
| **Dashboard** | Stats en tiempo real, gráficas con Chart.js, filtro por fecha |

---

## 🗂️ Estructura del proyecto

```
sales_admin/
├── run.py                    ← Punto de entrada
├── seed.py                   ← Inicialización + datos de demo
├── config.py                 ← Configuraciones (dev/prod)
├── requirements.txt
├── .env.example              ← Variables de entorno (copiar a .env)
│
├── database/
│   ├── schema.sql            ← DDL puro (MySQL)
│   └── seed.sql              ← Datos de prueba (SQL puro)
│
└── app/
    ├── __init__.py           ← Application Factory
    ├── extensions.py         ← db, login_manager, bcrypt, csrf
    │
    ├── models/               ← ORM SQLAlchemy
    │   ├── user.py
    │   ├── client.py
    │   ├── sale.py
    │   └── project.py
    │
    ├── schemas/              ← Validación Marshmallow (DTOs)
    │   └── __init__.py
    │
    ├── services/             ← Lógica de negocio
    │   ├── auth_service.py
    │   ├── client_service.py
    │   ├── sale_service.py
    │   └── dashboard_service.py
    │
    ├── routes/               ← Blueprints Flask (Controllers)
    │   ├── auth_routes.py
    │   ├── dashboard_routes.py
    │   ├── client_routes.py
    │   └── sale_routes.py
    │
    ├── templates/            ← Jinja2 + Bootstrap 5
    │   ├── base.html
    │   ├── login.html
    │   ├── dashboard.html
    │   ├── change_password.html
    │   ├── clients/
    │   │   ├── index.html
    │   │   ├── form.html
    │   │   └── detail.html
    │   └── sales/
    │       ├── index.html
    │       ├── form.html
    │       └── detail.html
    │
    └── static/
        ├── css/main.css
        └── js/main.js
```

---

## ⚙️ Por qué Flask y no FastAPI

| Criterio | Flask | FastAPI |
|---|---|---|
| Sistema administrativo (SSR) | ✅ Ideal | ⚠️ Requiere más setup |
| Jinja2 integrado | ✅ Nativo | ❌ No incluye |
| Flask-Login / sesiones web | ✅ Maduro | Requiere implementar |
| Validación de formularios | Flask-WTF + Marshmallow | Pydantic (para APIs) |

> **Conclusión:** Flask es la opción natural para un panel admin con server-side rendering, sesiones y plantillas HTML. FastAPI brillaría si el frontend fuera un SPA (React/Vue).

---

## 🚀 Instalación paso a paso

### 1. Requisitos previos

- Python 3.11 o superior
- MySQL 8.0 o superior
- pip

### 2. Clonar/copiar el proyecto

```bash
cd tu-directorio
# Asegúrate de estar dentro de sales_admin/
```

### 3. Crear entorno virtual

```bash
python -m venv venv

# Linux / macOS
source venv/bin/activate

# Windows
venv\Scripts\activate
```

### 4. Instalar dependencias

```bash
pip install -r requirements.txt
```

### 5. Configurar variables de entorno

```bash
cp .env.example .env
```

Edita `.env` con tus datos:

```env
SECRET_KEY=una-clave-muy-larga-y-aleatoria-aqui
DB_HOST=localhost
DB_PORT=3306
DB_NAME=sales_admin
DB_USER=root
DB_PASSWORD=tu_password_mysql

ADMIN_USERNAME=admin
ADMIN_EMAIL=admin@tuagencia.com
ADMIN_PASSWORD=Admin1234!
```

### 6. Crear la base de datos en MySQL

**Opción A — Con script SQL puro:**
```bash
mysql -u root -p < database/schema.sql
```

**Opción B — Con el seed de Python (recomendado):**
El script crea las tablas automáticamente vía SQLAlchemy.

### 7. Inicializar la base de datos y datos de demo

```bash
python seed.py
```

Salida esperada:
```
🚀 Inicializando base de datos...
✅ Tablas creadas/verificadas.
✅ Admin creado — usuario: admin | contraseña: Admin1234!
✅ Datos de demo insertados: 5 clientes, 12 ventas.

🎉 Listo. Ejecuta: flask run
```

### 8. Ejecutar el servidor

```bash
# Opción A
flask run

# Opción B
python run.py
```

### 9. Abrir en el navegador

```
http://localhost:5000
```

**Credenciales iniciales:**
- Usuario: `admin`
- Contraseña: `Admin1234!`

---

## 🔑 Seguridad implementada

| Medida | Detalle |
|---|---|
| Hash de contraseñas | bcrypt con 12 rondas |
| Protección CSRF | Flask-WTF en todos los formularios POST |
| Autenticación | Flask-Login con sesiones firmadas |
| Rutas protegidas | `@login_required` en todos los endpoints |
| Validación de inputs | Marshmallow schemas (server-side) |
| FK con RESTRICT | No se elimina un cliente con ventas |
| Sesión secreta | SECRET_KEY configurable por env var |

---

## 🗄️ Esquema de base de datos

```
users (1) ──────────────────────────── administrador
clients (1) ──── (N) sales (1) ──── (1) projects
```

### Tipos de servicio disponibles
`landing_page` | `catalog` | `store` | `corporate` | `portfolio` | `custom`

### Estados del proyecto
`not_started` → `in_development` → `in_review` → `finished`

### Métodos de pago
`mercado_pago` | `transfer` | `cash` | `card` | `other`

---

## 📦 Dependencias principales

```
Flask==3.0.3              # Framework web
Flask-SQLAlchemy==3.1.1   # ORM
Flask-Login==0.6.3        # Autenticación / sesiones
Flask-Bcrypt==1.0.1       # Hash de contraseñas
Flask-WTF==1.2.1          # CSRF protection
PyMySQL==1.1.1            # Driver MySQL
marshmallow==3.21.3       # Validación / serialización
python-dotenv==1.0.1      # Variables de entorno
```

---

## 🐛 Solución de problemas comunes

**Error de conexión MySQL:**
```
sqlalchemy.exc.OperationalError: Can't connect to MySQL server
```
→ Verifica que MySQL esté corriendo y los datos en `.env` sean correctos.

**Error de módulo no encontrado:**
```
ModuleNotFoundError: No module named 'flask'
```
→ Asegúrate de haber activado el entorno virtual: `source venv/bin/activate`

**Error CSRF token missing:**
→ Asegúrate de incluir `{{ csrf_token() }}` en todos los formularios (ya está incluido en las plantillas).

**Puerto 5000 ocupado:**
```bash
flask run --port 5001
```

---

## 🔧 Personalización rápida

**Agregar un nuevo tipo de servicio:**
1. `database/schema.sql` — modificar el ENUM en `sales`
2. `app/schemas/__init__.py` — agregar a `VALID_SERVICE_TYPES`
3. `app/__init__.py` — agregar etiqueta en `SERVICE_LABELS`

**Cambiar moneda:**
→ En `app/static/js/main.js` cambia `'es-MX'` / `'MXN'` según tu región.

---

## 🚀 Deploy en producción

1. Cambiar `FLASK_ENV=production` en `.env`
2. Usar Gunicorn:
   ```bash
   pip install gunicorn
   gunicorn -w 4 -b 0.0.0.0:8000 "run:app"
   ```
3. Configurar Nginx como reverse proxy
4. Usar `SECRET_KEY` generada con:
   ```python
   python -c "import secrets; print(secrets.token_hex(32))"
   ```
