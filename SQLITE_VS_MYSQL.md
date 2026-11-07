# SQLite vs MySQL: Penjelasan Migrasi

## 📊 Perbandingan

| Aspek | SQLite | MySQL |
|-------|--------|-------|
| **Type** | File-based database | Client-server database |
| **Scalability** | Limited untuk concurrent users | Excellent untuk multi-user |
| **Performance** | Baik untuk development | Optimized untuk production |
| **Concurrency** | Poor (file-level locking) | Excellent (row-level locking) |
| **Hosting Support** | Terbatas | Tersedia di semua hosting |
| **Data Integrity** | Dasar | Advanced (transactions, constraints) |
| **Backup** | Simple (copy file) | Robust (mysqldump, replication) |
| **Security** | File permissions only | User authentication & privileges |
| **Suitable For** | Development, small projects | Production, enterprise |

---

## ✅ Keuntungan Migrasi ke MySQL

### 1. **Production Ready**
- MySQL adalah standard di industry untuk web applications
- Semua professional hosting support MySQL
- Battle-tested untuk millions of transactions

### 2. **Better Concurrency**
```
SQLite:   [User 1 Writing] - [User 2 Blocked] - [User 3 Blocked]
MySQL:    [User 1] [User 2] [User 3] - All concurrent!
```

### 3. **Scalability**
- SQLite: Single file, terbatas size
- MySQL: Dapat handle terabytes data
- Replication untuk high availability

### 4. **Security**
- User authentication dengan password
- Privilege management per user
- Audit logging capabilities

### 5. **Reliability**
- ACID compliance
- Transaction support
- Crash recovery built-in
- Backup/restore tools

### 6. **Performance**
- Query optimization
- Index management
- Connection pooling
- Caching mechanisms

---

## 🔧 Perbedaan Technical

### Data Types
SQLite dan MySQL memiliki data types yang kompatibel:

| SQLite | MySQL | Laravel Migration |
|--------|-------|-------------------|
| TEXT | VARCHAR(255) | string() |
| INTEGER | INT | integer() |
| REAL | DECIMAL(8,2) | decimal() |
| BLOB | LONGBLOB | binary() |
| DATETIME | TIMESTAMP | timestamp() |

### Key Differences:
- **Case Sensitivity**: MySQL lebih strict dengan case
- **Foreign Keys**: MySQL bisa disable (perlu ENGINE=InnoDB)
- **Transactions**: MySQL lebih robust
- **Character Set**: MySQL perlu explicit charset (utf8mb4)

---

## 📝 Migrationnya Sudah Compatible!

Semua migrations di project STTP sudah compatible dengan MySQL:

✅ Menggunakan Laravel migration syntax
✅ Tidak ada SQLite-specific code
✅ Foreign key constraints supported
✅ Indexes properly defined
✅ Character encoding compatible

---

## 🚀 Setup di Production

### Step-by-Step

1. **Create Database & User**
   ```sql
   CREATE DATABASE sttp_db CHARACTER SET utf8mb4;
   CREATE USER 'sttp_user'@'localhost' IDENTIFIED BY 'password';
   GRANT ALL PRIVILEGES ON sttp_db.* TO 'sttp_user'@'localhost';
   FLUSH PRIVILEGES;
   ```

2. **Update .env**
   ```env
   DB_CONNECTION=mysql
   DB_HOST=localhost
   DB_PORT=3306
   DB_DATABASE=sttp_db
   DB_USERNAME=sttp_user
   DB_PASSWORD=password
   ```

3. **Run Migrations**
   ```bash
   php artisan migrate --force
   ```

4. **Verify Connection**
   ```bash
   php artisan tinker
   DB::table('users')->count()
   ```

---

## 📊 MySQL Features Used di STTP

### Dari analysis, database menggunakan:
- ✅ Foreign Keys (cascading deletes)
- ✅ Timestamps (created_at, updated_at)
- ✅ Indexes untuk performance
- ✅ String columns dengan correct length
- ✅ Nullable columns
- ✅ JSON data types (jika ada)

Semua ini **fully supported** di MySQL dengan even better performance!

---

## 🔒 MySQL Security Tips

1. **Strong Password**
   ```
   ✓ sttp_user@P@ssw0rd!2024
   ✗ password
   ✗ 12345
   ```

2. **Limited Privileges**
   ```sql
   -- ✓ Good: hanya yang diperlukan
   GRANT SELECT, INSERT, UPDATE, DELETE ON sttp_db.* TO 'sttp_user'@'localhost';
   
   -- ✗ Bad: terlalu banyak privileges
   GRANT ALL ON *.* TO 'sttp_user'@'localhost';
   ```

3. **Host Restriction**
   ```sql
   -- ✓ Good: limited to application server
   CREATE USER 'sttp_user'@'192.168.1.10' IDENTIFIED BY '...';
   
   -- ✗ Bad: accessible dari mana saja
   CREATE USER 'sttp_user'@'%' IDENTIFIED BY '...';
   ```

---

## 📈 Performance Considerations

### Connection Pooling
```php
// Laravel automatically manages connections
// Dapat dioptimalkan di config/database.php
'connections' => [
    'mysql' => [
        'host' => env('DB_HOST'),
        'port' => env('DB_PORT'),
        'database' => env('DB_DATABASE'),
        'pool' => [
            'min' => 2,
            'max' => 10,
        ],
    ],
],
```

### Query Optimization
- Use proper indexes
- Eager load relationships (with())
- Use select() untuk specific columns
- Pagination untuk large datasets

---

## 🆘 Common Issues & Solutions

### Issue 1: "Connection refused"
```bash
# Check MySQL is running
sudo systemctl status mysql

# Verify credentials
mysql -h localhost -u sttp_user -p
```

### Issue 2: "Unknown character set 'utf8mb4'"
```sql
-- Update database charset
ALTER DATABASE sttp_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### Issue 3: "Lost connection to MySQL"
```bash
# Increase max_connections di MySQL config
# /etc/mysql/mysql.conf.d/mysqld.cnf
max_connections = 1000
```

---

## 📚 Resources

- [Laravel Database Documentation](https://laravel.com/docs/database)
- [MySQL Official Documentation](https://dev.mysql.com/doc/)
- [Best Practices for MySQL](https://dev.mysql.com/doc/refman/8.0/en/optimization.html)

---

**Summary**: Perubahan dari SQLite ke MySQL adalah **necessary untuk production** dan project STTP sudah siap untuk migrasi ini!
