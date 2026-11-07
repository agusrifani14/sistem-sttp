# Panduan Migration SQLite ke MySQL & Deployment

## 📋 Langkah-Langkah Deployment

### 1. **Setup Database di Hosting**

**SQL Script untuk membuat database dan user:**

```sql
-- Buat database
CREATE DATABASE IF NOT EXISTS sttp_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Buat user MySQL
CREATE USER 'sttp_user'@'localhost' IDENTIFIED BY 'your_secure_password_here';

-- Berikan privilege lengkap
GRANT ALL PRIVILEGES ON sttp_db.* TO 'sttp_user'@'localhost';

-- Apply changes
FLUSH PRIVILEGES;
```

### 2. **Konfigurasi .env**

File `.env` sudah dikonfigurasi untuk MySQL. Sesuaikan nilai berikut sesuai hosting Anda:

```env
# Ubah ini dengan domain Anda
APP_URL=https://yourdomain.com

# Database credentials (sesuaikan dengan hosting)
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=sttp_db
DB_USERNAME=sttp_user
DB_PASSWORD=your_secure_password_here

# Ubah APP_ENV menjadi 'production'
APP_ENV=production
APP_DEBUG=false
```

### 3. **Upload ke Hosting**

1. **Via FTP/SFTP:**
   - Upload semua file kecuali `node_modules/` dan `vendor/`
   - Buat folder `storage` dan `bootstrap/cache` dengan write permission (755 atau 775)

2. **Via Git:**
   ```bash
   git clone <your-repo-url>
   ```

### 4. **Install Dependencies di Hosting**

```bash
# SSH ke hosting
ssh user@yourhost.com

# Navigate ke project
cd /path/to/sttp

# Install Composer dependencies
composer install --optimize-autoloader --no-dev

# Install NPM dependencies (jika diperlukan)
npm install --production

# Generate APP_KEY jika belum
php artisan key:generate

# Build Vite assets (jika ada)
npm run build
```

### 5. **Jalankan Migrations**

```bash
# Pastikan .env sudah dikonfigurasi dengan benar
php artisan migrate --force
```

### 6. **Set Permissions (Linux/Unix)**

```bash
# Folder storage dan bootstrap/cache harus writable
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Jika menggunakan web server sebagai user (seperti www-data):
chown -R www-data:www-data storage
chown -R www-data:www-data bootstrap/cache
```

### 7. **Konfigurasi Web Server**

**Untuk Apache:**
```apache
<VirtualHost *:443>
    ServerName yourdomain.com
    DocumentRoot /path/to/sttp/public
    
    <Directory /path/to/sttp/public>
        AllowOverride All
        Require all granted
    </Directory>
    
    # Enable SSL
    SSLEngine on
    SSLCertificateFile /path/to/certificate.crt
    SSLCertificateKeyFile /path/to/private.key
</VirtualHost>

# Redirect HTTP ke HTTPS
<VirtualHost *:80>
    ServerName yourdomain.com
    Redirect permanent / https://yourdomain.com/
</VirtualHost>
```

**Untuk Nginx:**
```nginx
server {
    listen 443 ssl http2;
    server_name yourdomain.com;
    root /path/to/sttp/public;
    
    ssl_certificate /path/to/certificate.crt;
    ssl_certificate_key /path/to/private.key;
    
    index index.php;
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}

# Redirect HTTP ke HTTPS
server {
    listen 80;
    server_name yourdomain.com;
    return 301 https://$server_name$request_uri;
}
```

### 8. **Setup Cron Job (untuk Queue & Scheduler)**

Tambahkan ke crontab (crontab -e):

```bash
* * * * * cd /path/to/sttp && php artisan schedule:run >> /dev/null 2>&1
* * * * * cd /path/to/sttp && php artisan queue:work --stop-when-empty >> /dev/null 2>&1
```

### 9. **Backup Database**

```bash
# Backup sebelum production
mysqldump -u sttp_user -p sttp_db > backup_sttp_$(date +%Y%m%d).sql
```

---

## ✅ Perubahan Konfigurasi yang Sudah Dilakukan

### Database Configuration:
- ✅ Ubah `DB_CONNECTION` dari `sqlite` menjadi `mysql`
- ✅ Konfigurasi MySQL ready: host, port, database, username, password
- ✅ Set charset: `utf8mb4` untuk support emoji dan karakter khusus

### Environment:
- ✅ APP_ENV set ke `production`
- ✅ APP_DEBUG set ke `false`
- ✅ Session driver: `database` (lebih reliable)
- ✅ Cache driver: `database` (persistent)
- ✅ Queue connection: `database`

### Migration Compatibility:
- ✅ Semua migrations sudah compatible dengan MySQL
- ✅ Tidak ada SQLite-specific syntax yang mengganggu

---

## 🐛 Troubleshooting

### Error: "Connection refused"
```bash
# Pastikan MySQL running di hosting
# Hubungi hosting support untuk verifikasi
```

### Error: "SQLSTATE[HY000]: General error: 1030 Got error from storage engine"
```bash
# Check storage permissions:
chmod -R 775 storage bootstrap/cache
```

### Error: "No application encryption key has been specified"
```bash
php artisan key:generate
```

### Error: "Column not found in where clause"
```bash
# Run migrations:
php artisan migrate --force
```

---

## 📝 Database Struktur

Migrations yang akan dijalankan:
- ✅ `0001_01_01_000000_create_users_table.php`
- ✅ `0001_01_01_000001_create_cache_table.php`
- ✅ `0001_01_01_000002_create_jobs_table.php`
- ✅ `2024_02_14_create_password_reset_tokens_table.php`
- ✅ `2025_10_04_140304_create_sttps_table.php`
- ✅ `2025_10_21_204451_create_reports_table.php`
- ✅ `2025_11_05_add_reset_password_columns.php`
- ✅ `2026_02_09_add_kategori_kejahatan_to_reports_table.php`
- ✅ `2026_02_20_make_ttl_columns_nullable.php`
- ✅ `2026_02_20_split_personal_fields.php`
- ✅ `2026_03_18_000001_add_whatsapp_otp_to_users_table.php`

---

## 🔐 Security Checklist

- [ ] Change `APP_KEY` di .env
- [ ] Set `APP_DEBUG=false`
- [ ] Set `APP_ENV=production`
- [ ] Change default passwords di database
- [ ] Enable HTTPS (SSL/TLS)
- [ ] Setup firewall rules
- [ ] Regular backups configured
- [ ] Update APP_URL ke domain Anda
- [ ] Change MAIL credentials ke SMTP real
- [ ] Setup strong DB_PASSWORD

---

## 📞 Support

Untuk pertanyaan hosting-specific, hubungi technical support hosting Anda.
