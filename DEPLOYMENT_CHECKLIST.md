# Pre-Deployment Checklist

## 📋 Database Configuration
- [ ] MySQL/MariaDB tersedia di hosting
- [ ] Database credentials siap (host, username, password)
- [ ] Database charset utf8mb4
- [ ] User MySQL memiliki ALL PRIVILEGES

## 📝 Environment Configuration
- [ ] `.env` file sudah dikonfigurasi untuk MySQL
  - [ ] `DB_CONNECTION=mysql`
  - [ ] `DB_HOST` sesuai dengan hosting
  - [ ] `DB_PORT=3306` (atau port custom jika ada)
  - [ ] `DB_DATABASE` sesuai nama database
  - [ ] `DB_USERNAME` dan `DB_PASSWORD` benar
- [ ] `APP_KEY` sudah diisi di .env
- [ ] `APP_ENV=production`
- [ ] `APP_DEBUG=false`
- [ ] `APP_URL` menggunakan domain production

## 🔐 Security
- [ ] APP_KEY di-generate dengan `php artisan key:generate`
- [ ] .env tidak ter-commit ke repository
- [ ] Database password di-set strong dan unik
- [ ] FILE_UPLOAD_MAX_SIZE sudah diatur sesuai kebutuhan

## 📦 Dependencies
- [ ] PHP 8.2+ tersedia di hosting
- [ ] Composer terinstall
- [ ] Node.js/npm tersedia (untuk Vite compilation)
- [ ] Semua Composer dependencies terinstall: `composer install --no-dev`
- [ ] Semua NPM dependencies terinstall: `npm install --production`
- [ ] Vite assets sudah di-build: `npm run build`

## 📁 File Permissions
- [ ] `storage/` folder readable dan writable (775)
- [ ] `bootstrap/cache/` folder readable dan writable (775)
- [ ] `public/` folder accessible dari web server
- [ ] `.env` file tidak accessible dari web (644)

## 🗄️ Database
- [ ] Database sudah dibuat
- [ ] User MySQL sudah dibuat dengan privileges
- [ ] Migrations sudah di-run: `php artisan migrate --force`
- [ ] Database connection dapat ditest

## 🌐 Web Server
- [ ] Web server (Apache/Nginx) sudah configured
- [ ] DocumentRoot pointing ke `/public` folder
- [ ] URL rewrite module enabled (mod_rewrite untuk Apache)
- [ ] `.htaccess` in `/public` folder correctly configured

## 🔒 SSL/TLS
- [ ] SSL certificate sudah installed
- [ ] HTTP traffic di-redirect ke HTTPS
- [ ] HSTS header configured (optional tapi recommended)

## ✉️ Email Configuration
- [ ] Mail driver configured (jangan gunakan 'log' di production!)
- [ ] SMTP credentials valid
- [ ] Test email sending

## 📊 Logging & Monitoring
- [ ] Log channel configured (`single` atau `stack`)
- [ ] Log rotation setup
- [ ] Error tracking configured (optional: Sentry, Bugsnag)

## 🔄 Cron Jobs (jika diperlukan)
- [ ] Schedule:run cron job dikonfigurasi
- [ ] Queue worker cron job dikonfigurasi (jika using database queue)

## 🧪 Testing Before Going Live
- [ ] Application starts without errors
- [ ] Database connection works
- [ ] User can login successfully
- [ ] Forms dapat disubmit
- [ ] File upload works
- [ ] Reports/PDF generation works
- [ ] Email sending works (jika ada)

## 📈 Post-Deployment
- [ ] Monitor error logs
- [ ] Verify all features working
- [ ] Performance monitoring setup
- [ ] Backup strategy configured
- [ ] SSL certificate auto-renewal setup

---

## Quick Commands untuk Verification

```bash
# SSH ke hosting
ssh user@yourhost.com
cd /path/to/sttp

# Test database connection
php artisan tinker
>>> DB::connection()->getPdo()
>>> exit

# Run migrations
php artisan migrate --force

# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Generate APP_KEY jika diperlukan
php artisan key:generate

# Check Laravel installation
php artisan about
```

---

Last Updated: 2026-05-27
