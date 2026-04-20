# Deployment & Production Guide

Complete guide for deploying the Rouh project to production, managing environments, and securing your deployment.

---

## Table of Contents

1. [Pre-Deployment Checklist](#pre-deployment-checklist)
2. [GitHub Setup](#github-setup)
3. [Production Environment Configuration](#production-environment-configuration)
4. [Deployment Steps (First Time)](#deployment-steps-first-time)
5. [Continuous Deployment](#continuous-deployment)
6. [Post-Deployment Monitoring](#post-deployment-monitoring)
7. [Rollback Procedures](#rollback-procedures)
8. [Troubleshooting Deployment Issues](#troubleshooting-deployment-issues)

---

## Pre-Deployment Checklist

### ⚠️ Critical: Security Verification

Before deploying to GitHub or production:

```bash
# 1. Verify no .env files in git history
git log --all --full-history -- "*.env" | head -20

# Should show nothing - if it shows commits, see "Remove sensitive files from git history"

# 2. Check for exposed credentials
git log --all --source --all -S "password" -S "secret" -S "api_key" --oneline

# Should show nothing

# 3. Verify .gitignore covers all sensitive files
cat .gitignore | grep -E "env|credentials|secret"
```

### Security Credentials to Change

**Before Going Public:**

On your **production server** (Hostinger/hosting provider):

1. **Database Credentials**
   ```bash
   SSH into server
   nano .env
   
   # Change these values to NEW values NOT used elsewhere
   DB_USERNAME=new_secure_username
   DB_PASSWORD=NewSecurePass@2026!
   DB_DATABASE=production_db_name
   ```

2. **Application Key**
   ```bash
   php artisan key:generate
   # This will create a new encryption key
   ```

3. **Disable Debug Mode**
   ```env
   APP_ENV=production
   APP_DEBUG=false
   ```

4. **Update API URLs**
   ```env
   APP_URL=https://api.yourdomain.com
   REACT_APP_BACKEND_URL=https://api.yourdomain.com/api
   ```

### Code Review Checklist

- [ ] All tests pass: `php artisan test` and `npm test`
- [ ] No console errors in browser (F12)
- [ ] No hardcoded credentials in code
- [ ] Database migrations are reversible
- [ ] `.env` not in git history
- [ ] README is up to date
- [ ] Code follows project standards
- [ ] Security headers configured
- [ ] CORS properly configured
- [ ] Rate limiting enabled

---

## GitHub Setup

### Step 1: Create Repository

1. Go to [github.com/new](https://github.com/new)
2. **Repository name:** `rouh-soon`
3. **Visibility:** Public (code is open source)
4. **Skip** initializing with README (already exists)
5. Click "Create repository"

### Step 2: Connect Local Git to GitHub

```bash
cd /path/to/rouh-soon

# Add remote
git remote add origin https://github.com/YOUR_USERNAME/rouh-soon.git

# Rename branch to main (GitHub convention)
git branch -M main

# Push all commits
git push -u origin main
```

### Step 3: Verify Push Was Safe

```bash
# On GitHub, verify the repo doesn't contain .env files
# Visit: https://github.com/YOUR_USERNAME/rouh-soon/tree/main

# Files visible in repo should NOT include:
# ❌ .env
# ❌ test-db.php
# ❌ debug.php

# Files that SHOULD be visible:
# ✅ SETUP_GUIDE.md
# ✅ CONTRIBUTING.md
# ✅ backend/.env.example
# ✅ frontend/.env.example
```

### Step 4: Enable Branch Protection

On GitHub:
1. Go to **Settings → Branches**
2. Click **Add rule**
3. **Branch name pattern:** `main`
4. Enable:
   - ✅ Require pull request reviews before merging
   - ✅ Require branches to be up to date before merging
   - ✅ Require status checks to pass before merging
   - ✅ Require code reviews before merging

### Step 5: Add GitHub Secrets (for CI/CD)

1. Go to **Settings → Secrets and variables → Actions**
2. Create secrets:
   - `DB_PASSWORD` - Production database password
   - `APP_KEY` - Laravel application key
   - `HOSTING_USERNAME` - SSH username (if auto-deploying)
   - `HOSTING_PASSWORD` - SSH password or key

---

## Production Environment Configuration

### Production vs Development

| Setting | Development | Production |
|---------|-------------|-----------|
| `APP_DEBUG` | `true` | `false` |
| `APP_ENV` | `local` | `production` |
| `DB_HOST` | `localhost` | `remote server` |
| `LOG_LEVEL` | `debug` | `error` |
| `CACHE_STORE` | `file` | `redis` |
| `SESSION_DRIVER` | `cookie` | `cookie` or `redis` |

### Production .env Example

```env
# Application
BASE_URL=https://api.rouh.shop
APP_NAME=Rouh
APP_ENV=production
APP_KEY=base64:PRODUCTION_KEY_HERE
APP_DEBUG=false
APP_URL=https://api.rouh.shop
TRUSTED_PROXIES=*

# Database (Production)
DB_CONNECTION=mysql
DB_HOST=mysql.hostingprovider.com
DB_PORT=3306
DB_DATABASE=production_db_name
DB_USERNAME=prod_username
DB_PASSWORD=STRONG_PASSWORD_HERE

# Session & Cache (Use Redis if possible)
SESSION_DRIVER=cookie
CACHE_STORE=redis
QUEUE_CONNECTION=redis

# Redis (if available)
REDIS_HOST=redis.hostingprovider.com
REDIS_PASSWORD=redis_password
REDIS_PORT=6379

# Mail (use real email service)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=465
MAIL_USERNAME=noreply@rouh.shop
MAIL_PASSWORD=app_specific_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@rouh.shop
MAIL_FROM_NAME=Rouh

# AWS S3 (if using)
AWS_ACCESS_KEY_ID=production_key_id
AWS_SECRET_ACCESS_KEY=production_secret_key
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=rouh-production-uploads
AWS_URL=https://s3.amazonaws.com/rouh-production-uploads

# Logging
LOG_CHANNEL=stack
LOG_LEVEL=error
LOG_SLACK_WEBHOOK_URL=https://hooks.slack.com/services/YOUR/WEBHOOK/URL

# Security
CORS_ALLOWED_ORIGINS=https://rouh.shop

# Monitoring
SENTRY_LARAVEL_DSN=https://key@sentry.io/project_id
```

---

## Deployment Steps (First Time)

### Method 1: Manual Deployment (FTP/SSH)

```bash
# 1. SSH into your hosting server
ssh user@yourdomain.com

# 2. Clone the repository
git clone https://github.com/YOUR_USERNAME/rouh-soon.git
cd rouh-soon

# 3. Create production .env
nano backend/.env
# Paste production environment variables

# 4. Install dependencies
cd backend
composer install --no-dev --optimize-autoloader
cd ../frontend
npm install --omit=dev
npm run build

# 5. Set up database
cd ../backend
php artisan migrate --force
php artisan db:seed --force  # Only if you have seeders

# 6. Set permissions
chmod -R 755 storage bootstrap/cache
chmod 644 .env

# 7. Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

# 8. Restart services
sudo systemctl restart php-fpm
sudo systemctl restart nginx
```

### Method 2: Git Auto-Deployment (Recommended)

Many hosting providers support Git webhooks for auto-deployment:

1. **Configure Webhook on GitHub:**
   - Go to repository **Settings → Webhooks**
   - Click **Add webhook**
   - **Payload URL:** `https://yourdomain.com/deploy.php`
   - **Content type:** `application/json`
   - **Secret:** Use a strong random string
   - **Events:** Push events
   - Click **Add webhook**

2. **Create deploy.php on your server:**

```php
<?php
// public/deploy.php

$secret = getenv('GITHUB_WEBHOOK_SECRET');
$payload = file_get_contents('php://input');
$signature = $_SERVER['HTTP_X_HUB_SIGNATURE_256'] ?? '';

// Verify signature
$hash = 'sha256=' . hash_hmac('sha256', $payload, $secret);
if (!hash_equals($hash, $signature)) {
    http_response_code(401);
    die('Unauthorized');
}

// Execute deployment
exec('cd /path/to/rouh-soon && git pull origin main && composer install --no-dev && php artisan migrate --force && npm run build 2>&1', $output);

http_response_code(200);
echo json_encode(['status' => 'success', 'output' => $output]);
```

3. **Add webhook secret to production .env:**
```env
GITHUB_WEBHOOK_SECRET=your_webhook_secret_here
```

---

## Continuous Deployment

### GitHub Actions (Automated Deployment)

Create `.github/workflows/deploy.yml`:

```yaml
name: Deploy to Production

on:
  push:
    branches:
      - main

jobs:
  deploy:
    runs-on: ubuntu-latest
    
    steps:
      - uses: actions/checkout@v3
      
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.2'
      
      - name: Install Composer Dependencies
        run: cd backend && composer install --no-dev --optimize-autoloader
      
      - name: Setup Node
        uses: actions/setup-node@v3
        with:
          node-version: '18'
      
      - name: Build Frontend
        run: cd frontend && npm install && npm run build
      
      - name: Deploy to Server
        uses: appleboy/ssh-action@master
        with:
          host: ${{ secrets.HOST }}
          username: ${{ secrets.USERNAME }}
          key: ${{ secrets.SSH_KEY }}
          script: |
            cd /path/to/rouh-soon
            git pull origin main
            cd backend
            php artisan migrate --force
            php artisan cache:clear
            cd ..
```

### Required GitHub Secrets

1. Go to **Settings → Secrets and variables → Actions**
2. Add:
   - `HOST` - Your server IP or domain
   - `USERNAME` - SSH username
   - `SSH_KEY` - SSH private key

---

## Post-Deployment Monitoring

### Health Check Endpoints

```bash
# Test API is running
curl https://api.rouh.shop/api

# Test database connection
curl https://api.rouh.shop/api/health

# Check frontend
curl https://rouh.shop

# Monitor logs
ssh user@yourdomain.com
tail -f /path/to/rouh-soon/backend/storage/logs/laravel.log
```

### Set Up Monitoring

1. **Uptime Monitoring** - Use services like:
   - Pingdom
   - Better Uptime
   - Sentry (for errors)

2. **Log Aggregation:**
   - Set up CloudWatch (AWS)
   - Or use Sentry for Laravel logs

3. **Performance Monitoring:**
   - Set up New Relic
   - Or use DataDog

### Automated Alerts

```bash
# Email alerts for errors
tail -f storage/logs/laravel.log | grep -i error | while read line; do
    echo "$line" | mail -s "Rouh Error Alert" admin@rouh.shop
done
```

---

## Rollback Procedures

### If Deployment Goes Wrong

**Option 1: Rollback via Git**

```bash
# View recent commits
git log --oneline -5

# Revert to previous version
git revert HEAD
git push origin main

# Or reset to previous commit (destructive)
git reset --hard commit_hash
git push -f origin main  # Force push - use with caution!
```

**Option 2: Keep Previous Release**

```bash
# Before deploying, backup current version
cp -r /var/www/rouh-soon /var/www/rouh-soon.backup

# If issues occur, restore
rm -rf /var/www/rouh-soon
mv /var/www/rouh-soon.backup /var/www/rouh-soon

# Restart services
sudo systemctl restart nginx php-fpm
```

**Option 3: Database Rollback**

```bash
# View migrations
php artisan migrate:status

# Rollback last migration
php artisan migrate:rollback

# Rollback specific number of steps
php artisan migrate:rollback --steps=3
```

---

## Troubleshooting Deployment Issues

### Common Issues

#### 1. "Permission denied" on storage folder

```bash
ssh user@yourdomain.com
chmod -R 755 /path/to/rouh-soon/backend/storage
chmod -R 755 /path/to/rouh-soon/backend/bootstrap/cache
chown -R www-data:www-data /path/to/rouh-soon/backend
```

#### 2. "Database connection failed"

```bash
# SSH into server
# Test connection
mysql -h db.host -u username -p database_name

# Check .env on server
cat backend/.env | grep DB_

# Verify database exists
mysql -u root -p -e "SHOW DATABASES;"
```

#### 3. "Blank page" or 500 error

```bash
# Check error logs
tail -f backend/storage/logs/laravel.log

# Ensure all permissions are correct
chmod 644 backend/.env
chmod -R 755 backend/storage

# Run cache clear
php artisan config:cache
php artisan route:cache
```

#### 4. "Too many redirects" (CORS/SSL issue)

```env
# In production .env:
APP_URL=https://api.rouh.shop  # Must be HTTPS if site is HTTPS
TRUSTED_PROXIES=*
SESSION_SECURE_COOKIES=true
```

#### 5. "Frontend can't reach backend"

```bash
# Verify CORS is configured
# In Laravel config/cors.php:
'allowed_origins' => ['https://rouh.shop'],

# Or test API is accessible
curl https://api.rouh.shop/api
```

### Debug Mode (Emergency Only!)

**⚠️ Only enable temporarily for debugging**

```env
APP_DEBUG=true
LOG_LEVEL=debug
```

After debugging:
```env
APP_DEBUG=false
LOG_LEVEL=error
```

---

## Maintenance

### Regular Maintenance Tasks

```bash
# Daily
tail -f storage/logs/laravel.log  # Monitor errors

# Weekly
php artisan tinker  # Quick database checks
php artisan telescope:prune  # Clear logs

# Monthly
php artisan backup:run  # Backup database
composer update  # Check for updates

# Quarterly
Security audit
Update dependencies
Review error logs
```

### Updating Production

```bash
# Always test updates locally first!
cd backend
composer update
php artisan migrate

cd ../frontend
npm update
npm run build
```

---

## Security Reminders

- ✅ Keep `.env` secure on production server
- ✅ Use HTTPS everywhere
- ✅ Set `APP_DEBUG=false`
- ✅ Use strong database passwords
- ✅ Regularly backup database
- ✅ Keep dependencies updated
- ✅ Monitor error logs
- ✅ Rotate API keys periodically

---

**For Questions:** Refer to [SETUP_GUIDE.md](./SETUP_GUIDE.md) or [CONTRIBUTING.md](./CONTRIBUTING.md)
