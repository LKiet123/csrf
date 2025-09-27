# Redis Session Installation Guide

## 1. Install Redis Extension for PHP

### For Windows XAMPP:

1. **Download Redis Extension:**
   - Go to: https://pecl.php.net/package/redis
   - Download the appropriate version for your PHP version
   - Or download from: https://github.com/phpredis/phpredis/releases

2. **Install Extension:**
   ```bash
   # Copy redis.dll to XAMPP ext directory
   copy redis.dll C:\xampp\php\ext\
   ```

3. **Update php.ini:**
   ```ini
   ; Add this line to your php.ini
   extension=redis
   ```

4. **Restart XAMPP:**
   - Stop and restart Apache and MySQL services

## 2. Start Redis Server

### Option A: Using Docker (Recommended)
```bash
# Start Redis container
docker run -d --name redis-server \
  -p 6379:6379 \
  redis:7.2 \
  redis-server --requirepass mypassword
```

### Option B: Using Docker Compose
```bash
# Use existing docker-compose.yml
docker-compose up -d redis
```

### Option C: Native Installation
1. Download Redis from: https://redis.io/download
2. Install and start Redis server
3. Configure password: requirepass mypassword

## 3. Configure PHP Session to use Redis

### Option A: php.ini Configuration
```ini
[session]
session.save_handler = redis
session.save_path = "tcp://127.0.0.1:6379?auth=mypassword"
session.gc_maxlifetime = 1440
session.cookie_lifetime = 0
session.cookie_secure = 0
session.cookie_httponly = 1
```

### Option B: Programmatic Configuration
```php
// Configure session to use Redis
ini_set('session.save_handler', 'redis');
ini_set('session.save_path', 'tcp://127.0.0.1:6379?auth=mypassword');

// Start session
session_start();
```

## 4. Test Configuration

1. **Test Redis Connection:**
   ```php
   $redis = new Redis();
   $redis->connect('127.0.0.1', 6379);
   $redis->auth('mypassword');
   ```

2. **Test Session Storage:**
   ```php
   session_start();
   $_SESSION['test'] = 'Hello Redis!';
   ```

## 5. Verify Installation

- **Check Redis Extension:** `php -m | grep redis`
- **Check Redis Server:** `redis-cli -h 127.0.0.1 -p 6379 -a mypassword ping`
- **Test Session:** Create a PHP file with session_start() and check if data persists

## 6. Troubleshooting

### Common Issues:

1. **Extension not loading:**
   - Check php.ini syntax
   - Verify redis.dll is in correct directory
   - Restart web server

2. **Cannot connect to Redis:**
   - Check if Redis server is running
   - Verify password and port
   - Check firewall settings

3. **Session not persisting:**
   - Verify session.save_handler = redis
   - Check session.save_path format
   - Ensure Redis has proper permissions

### Debug Commands:
```bash
# Check PHP modules
php -m | grep redis

# Test Redis connection
redis-cli -h 127.0.0.1 -p 6379 -a mypassword ping

# Check session configuration
php -i | grep session

# Check Redis keys
redis-cli -h 127.0.0.1 -p 6379 -a mypassword keys "PHPREDIS_SESSION:*"
```
