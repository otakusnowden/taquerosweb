# 🚀 Despliegue de TaquerosWeb via Git

Este proyecto está listo para desplegar en **HostGator** u otros hosting compartidos usando Git.

## 📋 Requisitos previos

- Hosting con soporte para Git (HostGator, SiteGround, Bluehost, etc.)
- PHP 8.2+
- Composer instalado en el servidor
- Node.js y npm instalados en el servidor (para assets)

## 🔧 Pasos de despliegue

### 1. Clonar el repositorio en el servidor

```bash
cd /home/usuario/public_html
git clone https://github.com/tuusuario/taquerosweb.git .
```

O si ya existe:
```bash
cd /home/usuario/public_html
git pull origin master
```

### 2. Crear archivo `.env` en el servidor

```bash
cp .env.example .env
```

Luego edita `.env` con los datos reales (cPanel File Manager o SSH):
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://taquerosweb.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_DATABASE=tu_base_de_datos
DB_USERNAME=tu_usuario_bd
DB_PASSWORD=tu_contraseña_bd

MAIL_MAILER=smtp
MAIL_HOST=smtp.titan.email
MAIL_PORT=465
MAIL_USERNAME=tu_email@domain.com
MAIL_PASSWORD=tu_contraseña_smtp

TW_WHATSAPP=5215662866353
TW_EMAIL=contact@taquerosweb.com
```

### 3. Generar clave de encriptación

```bash
php artisan key:generate
```

### 4. Instalar dependencias de Composer

```bash
composer install --no-dev --optimize-autoloader
```

### 5. Compilar assets (CSS/JS)

```bash
npm install
npm run build
```

### 6. Ejecutar migraciones

```bash
php artisan migrate --force
php artisan db:seed --force
```

### 7. Optimizar para producción

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan storage:link
```

### 8. Configurar permisos

```bash
chmod -R 755 .
chmod -R 775 storage bootstrap/cache
```

### 9. Configurar DocumentRoot en Apache

En **cPanel → Addon Domains** o **Manage Domains**, apunta el `Document Root` a:
```
/home/usuario/public_html/public
```

### 10. Instalar SSL (Let's Encrypt)

En **cPanel → AutoSSL** o **Manage SSL/TLS**, instala certificado gratuito.

### 11. Configurar queue worker (para correos asíncronos)

En **cPanel → Cron Jobs**, agrega un job que corra cada minuto:
```bash
cd /home/usuario/public_html && php artisan queue:work --stop-when-empty --tries=3 >> /dev/null 2>&1
```

## 📦 Estructura post-despliegue

El servidor solo necesita estos directorios:
```
/home/usuario/public_html/
├── public/               ← DocumentRoot apunta aquí
├── app/
├── config/
├── database/
├── resources/
├── routes/
├── storage/
├── bootstrap/
├── artisan
├── .env                  ← Generado en el servidor
├── composer.json
└── package.json
```

**No están en Git (se generan en producción):**
- `/vendor/` → `composer install`
- `/node_modules/` → `npm install`
- `/public/build/` → `npm run build`

## 🔄 Actualizaciones futuras

Para actualizar el sitio después de cambios en Git:

```bash
cd /home/usuario/public_html

# Traer cambios
git pull origin master

# Instalar nuevas dependencias (si las hay)
composer install --no-dev --optimize-autoloader
npm install
npm run build

# Migraciones si hay cambios en BD
php artisan migrate --force

# Limpiar caches
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 🐛 Troubleshooting

**Error 500 "No application encryption key"**
```bash
php artisan key:generate
```

**Assets no se ven**
```bash
npm run build
php artisan view:cache
```

**Correos no se envían**
- Verifica que el **queue worker** esté corriendo (cron)
- Revisa el log: `cat storage/logs/laravel.log`

**Base de datos no se migra**
```bash
php artisan migrate --force --seed
```

## 📧 Soporte

Para más info sobre configuración de hosting, ver:
- [Laravel Deployment](https://laravel.com/docs/12/deployment)
- [HostGator Laravel Guide](https://support.hostgator.com/articles/cpanel/installing-laravel-framework)
