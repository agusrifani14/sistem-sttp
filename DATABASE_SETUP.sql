-- ============================================
-- STTP Database Setup Script for Production
-- Database: MySQL 5.7+ / MariaDB 10.3+
-- Character Set: utf8mb4 (supports emojis)
-- ============================================

-- Create Database
CREATE DATABASE IF NOT EXISTS sttp_db 
    CHARACTER SET utf8mb4 
    COLLATE utf8mb4_unicode_ci;

-- Create MySQL User
CREATE USER IF NOT EXISTS 'sttp_user'@'localhost' IDENTIFIED BY 'your_secure_password_here';

-- Grant Privileges
GRANT ALL PRIVILEGES ON sttp_db.* TO 'sttp_user'@'localhost';
FLUSH PRIVILEGES;

-- ============================================
-- Database Setup Complete
-- ============================================
-- 
-- Next Steps:
-- 1. Update .env with these credentials:
--    DB_HOST=localhost
--    DB_PORT=3306
--    DB_DATABASE=sttp_db
--    DB_USERNAME=sttp_user
--    DB_PASSWORD=your_secure_password_here
--
-- 2. Run migrations in your hosting:
--    php artisan migrate --force
--
-- 3. Test connection:
--    php artisan tinker
--    >>> DB::connection()->getPdo();
--
-- ============================================
