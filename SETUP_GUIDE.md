# Project Setup Guide & Security Checklist

Complete guide for setting up the Rouh project locally, deploying to GitHub, and implementing security best practices.

---

## Table of Contents

1. [System Requirements](#system-requirements)
2. [Quick Start](#quick-start)
3. [Detailed Backend Setup](#detailed-backend-setup)
4. [Detailed Frontend Setup](#detailed-frontend-setup)
5. [Common Issues & Troubleshooting](#common-issues--troubleshooting)
6. [Security & Environment Variables](#security--environment-variables)
7. [GitHub Deployment Steps](#github-deployment-steps)
8. [Important Security Reminders](#important-security-reminders)

---

## System Requirements

### Backend (Laravel + PHP)
- **PHP**: >= 8.1
- **Composer**: Latest version
- **MySQL**: >= 5.7 or MariaDB >= 10.2
- **Node.js**: >= 16 (for build tools)
- **npm**: >= 8

### Frontend (React)
- **Node.js**: >= 16
- **npm**: >= 8

### Development Tools
- **Git**: Latest version
- **VS Code**: Recommended for development
- **Postman**: For API testing (optional)

### Check Your Versions
```bash
php --version
composer --version
node --version
npm --version
mysql --version
```

---

## Quick Start

### For Experienced Developers (5 minutes)

```bash
# Clone & Setup Backend
git clone https://github.com/YOUR_USERNAME/rouh-soon.git
cd rouh-soon/backend

cp .env.example .env
php artisan key:generate
composer install

# Update database credentials in .env
# DB_DATABASE=rouh_db_local
# DB_USERNAME=root
# DB_PASSWORD=YOUR_PASSWORD

php artisan migrate
php artisan serve

# In another terminal, Setup Frontend
cd ../frontend
npm install
cp .env.example .env
npm start
```

---

## Detailed Backend Setup

### Step 1: Clone the Repository

```bash
git clone https://github.com/YOUR_USERNAME/rouh-soon.git
cd rouh-soon
```

### Step 2: Prepare Environment File

```bash
cd backend
cp .env.example .env
```

Edit `.env` and update:

```env
APP_KEY=base64:GENERATE_NEW_KEY_WITH_php_artisan_key_generate
APP_DEBUG=false
APP_ENV=local
APP_URL=http://localhost:8000

# Database Configuration - CRITICAL
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=rouh_db_local
DB_USERNAME=root
DB_PASSWORD=YOUR_PASSWORD
```

### Step 3: Generate Application Key

**This creates a unique encryption key for your application:**

```bash
php artisan key:generate
```

Output should show: ✓ Application key set successfully

### Step 4: Install PHP Dependencies

```bash
composer install
```

This installs all Laravel packages listed in `composer.json`.

If you get permission errors, try:
```bash
composer install --no-interaction
```

### Step 5: Create Local Database

**Using MySQL CLI:**
```bash
mysql -u root -p
CREATE DATABASE rouh_db_local CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

**Or use phpMyAdmin:**
1. Open `http://localhost/phpmyadmin`
2. Create new database: `rouh_db_local`
3. Set collation to: `utf8mb4_unicode_ci`

### Step 6: Run Database Migrations

```bash
php artisan migrate
```

This creates all necessary tables.

**To seed test data (optional):**
```bash
php artisan db:seed
```

### Step 7: Start the Development Server

```bash
php artisan serve
```

The backend will be available at:
```
http://localhost:8000
```

Test it by visiting: `http://localhost:8000/api`

---

## Detailed Frontend Setup

### Step 1: Navigate to Frontend Directory

```bash
cd frontend
```

### Step 2: Prepare Environment File

```bash
cp .env.example .env
```

Edit `.env` to match your backend URL:

```env
REACT_APP_BACKEND_URL=http://localhost:8000/api
REACT_APP_DEBUG=false
```

### Step 3: Install Node Dependencies

```bash
npm install
```

This installs all packages from `package.json`.

**Troubleshooting:**
- If it fails, clear npm cache: `npm cache clean --force`
- Delete `node_modules/` folder and `package-lock.json`, then run `npm install` again

### Step 4: Start the Development Server

```bash
npm start
```

The frontend will automatically open at:
```
http://localhost:3000
```

---

## Available Scripts

### Backend (Laravel)

```bash
# Start development server
php artisan serve

# Run migrations
php artisan migrate

# Seed database with test data
php artisan db:seed

# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# Run tests
php artisan test

# Generate API documentation (if configured)
php artisan scribe:generate
```

### Frontend (React)

```bash
# Start development server
npm start

# Build for production
npm run build

# Run linter
npm run lint

# Run tests
npm test
```

---

## Common Issues & Troubleshooting

### Backend Issues

#### 1. "Port 8000 is already in use"
```bash
# Use a different port
php artisan serve --port=8001

# Or kill the process using port 8000
# Windows PowerShell:
Get-Process | Where-Object { $_.Handles -eq 8000 } | Stop-Process

# Linux/Mac:
lsof -ti:8000 | xargs kill -9
```

#### 2. "SQLSTATE[HY000]: General error"
**Problem:** Database connection failed
**Solution:**
```bash
# 1. Check MySQL is running
# 2. Verify .env credentials
# 3. Make sure database exists
mysql -u root -p -e "SHOW DATABASES;"
# 4. Rebuild connection
php artisan cache:clear
php artisan config:clear
```

#### 3. "No application encryption key has been specified"
```bash
# Run this
php artisan key:generate
```

#### 4. Composer "Out of memory"
```bash
# Increase PHP memory limit
php -d memory_limit=-1 composer install
```

#### 5. Permission denied errors (Linux/Mac)
```bash
chmod -R 755 storage bootstrap/cache
```

#### 6. CORS errors when calling frontend to backend
**Problem:** Frontend can't access backend API
**Solution:** Check `config/cors.php` in Laravel
```php
'allowed_origins' => ['http://localhost:3000'],
'allowed_origins_patterns' => ['localhost:*'],
```

### Frontend Issues

#### 1. "Cannot find module" errors
```bash
# Clear node_modules and reinstall
rm -r node_modules package-lock.json
npm install

# Or on Windows:
rmdir /s /q node_modules
del package-lock.json
npm install
```

#### 2. Port 3000 already in use
```bash
# Use a different port
PORT=3001 npm start

# Or on Windows PowerShell:
$env:PORT=3001; npm start
```

#### 3. API calls return 404 or "backend not responding"
**Check:**
- Backend is running: `php artisan serve`
- `.env` has correct `REACT_APP_BACKEND_URL`
- Backend URL is accessible: `curl http://localhost:8000/api`

#### 4. Hot reload not working
```bash
# Restart the development server
npm start
```

---

## Security & Environment Variables

### ⚠️ Critical Security Issues Fixed

**Before:** Database credentials were exposed in `.env`
**Now:** `.env` is in `.gitignore` and never committed to GitHub

### Environment Variables Explained

| Variable | Purpose | Development Value |
|----------|---------|------------------|
| `APP_DEBUG` | Show detailed errors | `false` (never `true` in production) |
| `APP_ENV` | Application environment | `local` (development) |
| `DB_PASSWORD` | Database password | Use strong password |
| `APP_KEY` | Encryption key | Auto-generated by `php artisan key:generate` |
| `REACT_APP_BACKEND_URL` | Backend API URL | `http://localhost:8000/api` |

### How to Handle Sensitive Data

✅ **CORRECT:**
```env
# .env (LOCAL ONLY - never commit)
DB_PASSWORD=strong_password_123
APP_KEY=base64:generated_key_here
```

❌ **WRONG:**
```php
// .js or .php files
const API_URL = 'https://api.rouh.shop/api';
const DB_PASSWORD = 'Jamal@26@Nabaa';
```

### Protected Files (Will NOT be committed)

```
backend/.env              ← Database credentials
frontend/.env             ← API URLs
backend/storage/logs/     ← Application logs
backend/public/test-db.php ← Debug files
```

### Accessing Environment Variables

**In Laravel (PHP):**
```php
$debugMode = env('APP_DEBUG', false);
$dbPassword = env('DB_PASSWORD');
```

**In React (JavaScript):**
```javascript
const backendUrl = process.env.REACT_APP_BACKEND_URL;
const debug = process.env.REACT_APP_DEBUG === 'true';
```

---

## GitHub Deployment Steps

### Step 1: Create GitHub Repository

1. Go to [github.com/new](https://github.com/new)
2. Repository name: `rouh-soon`
3. Choose **Public** (source code visible, but secrets are protected)
4. Click "Create repository"

### Step 2: Remove Sensitive Files Locally

```bash
# Make sure you're in the project root
cd e:\rouh\rouh-soon

# Delete .env files (already in .gitignore, but delete for safety)
Remove-Item -Path backend\.env -ErrorAction SilentlyContinue
Remove-Item -Path frontend\.env -ErrorAction SilentlyContinue
Remove-Item -Path backend\public\test-db.php -ErrorAction SilentlyContinue
Remove-Item -Path backend\public\debug.php -ErrorAction SilentlyContinue
Remove-Item -Path backend\public\clear.php -ErrorAction SilentlyContinue
```

### Step 3: Verify Git Configuration

```bash
git config --global user.name "Your Name"
git config --global user.email "your.email@example.com"
```

### Step 4: Initialize Git & Push to GitHub

```bash
# If this is a new repo locally
git init
git add .

# Create initial commit
git commit -m "Initial commit: Add project files with security configurations"

# Add remote
git remote add origin https://github.com/YOUR_USERNAME/rouh-soon.git

# Rename branch to main (GitHub standard)
git branch -M main

# Push to GitHub
git push -u origin main
```

### Step 5: Enable GitHub Security Features

After pushing, go to your repository settings:

1. **Settings → Branches → Add rule**
   - Branch name pattern: `main`
   - ✅ Require pull request reviews
   - ✅ Require branches to be up to date
   - ✅ Require status checks to pass

2. **Settings → Secrets and variables → Actions**
   - Add secrets that CI/CD pipelines need:
     - `DB_PASSWORD`
     - `APP_KEY`
     - Any API keys

### Step 6: Verify Nothing Sensitive Was Pushed

```bash
# Check if any .env files exist in history
git log --all --full-history -- "*.env"
git log --all --full-history -- "test-db.php"

# Should return: (nothing) - This means it's safe!
```

---

## Important Security Reminders

### Before Deploying to Production

**On Your Server (Hosting Provider):**

1. **Change Database Credentials**
   ```bash
   # SSH into server
   # Edit .env file with new credentials
   vi .env
   
   APP_DEBUG=false          # NEVER true in production
   APP_ENV=production
   DB_PASSWORD=NewStrong@Password123!
   ```

2. **Regenerate Application Key**
   ```bash
   php artisan key:generate
   ```

3. **Verify .env is not served publicly**
   - Test: Visit `https://yourdomain.com/.env` → Should be 404

4. **Set Proper File Permissions**
   ```bash
   chmod -R 755 storage bootstrap/cache
   chmod 644 .env
   ```

### What NOT to Do

- ❌ Never hardcode API keys or passwords in code
- ❌ Never commit `.env` files to Git
- ❌ Never set `APP_DEBUG=true` in production
- ❌ Never push files with credentials to GitHub
- ❌ Never share `.env` files via email or chat

### What to Do Instead

- ✅ Use `.env.example` as template
- ✅ Store secrets in environment variables
- ✅ Use GitHub Secrets for CI/CD
- ✅ Use hosting provider's secure environment variable system
- ✅ Rotate credentials regularly
- ✅ Use strong passwords (min 16 characters)

---

## Team Collaboration Guidelines

### When Joining the Project

1. Clone the repository
2. Copy `.env.example` files:
   ```bash
   cp backend/.env.example backend/.env
   cp frontend/.env.example frontend/.env
   ```
3. Ask the team lead for actual credentials
4. **NEVER commit `.env` files**

### Making Changes

1. Create a new branch:
   ```bash
   git checkout -b feature/your-feature-name
   ```

2. Make your changes and test locally

3. Commit with descriptive messages:
   ```bash
   git add .
   git commit -m "feat: add new survey question type"
   ```

4. Push and create a Pull Request:
   ```bash
   git push origin feature/your-feature-name
   ```

### Code Review Checklist

Before committing, verify:
- [ ] No `.env` files in staged changes
- [ ] No API keys or passwords in code
- [ ] No debug files (`test-db.php`, `debug.php`)
- [ ] No large unnecessary files
- [ ] Tests pass locally
- [ ] No console errors or warnings

---

## Useful Commands Reference

### Database Management

```bash
# Backup database
mysqldump -u root -p rouh_db_local > backup.sql

# Restore database
mysql -u root -p rouh_db_local < backup.sql

# Reset database (WARNING: deletes all data!)
php artisan migrate:refresh --seed
```

### Git Commands

```bash
# See changes before committing
git status
git diff

# View commit history
git log --oneline -10

# Undo recent commit (haven't pushed yet)
git reset --soft HEAD~1

# View specific file changes
git log -p -- filename.php
```

### Cache Clearing (when making changes)

```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Or all at once
php artisan optimize:clear
```

---

## Getting Help

### Common Resources

- **Laravel Documentation**: [laravel.com/docs](https://laravel.com/docs)
- **React Documentation**: [react.dev](https://react.dev)
- **MySQL Documentation**: [dev.mysql.com](https://dev.mysql.com)
- **Git Documentation**: [git-scm.com/docs](https://git-scm.com/docs)

### Debugging Tips

1. **Check Laravel logs**:
   ```bash
   tail -f storage/logs/laravel.log  # Linux/Mac
   Get-Content -Tail 20 storage/logs/laravel.log  # Windows PowerShell
   ```

2. **Enable debug mode locally** (ONLY):
   ```env
   APP_DEBUG=true
   ```

3. **Test API endpoints**:
   ```bash
   curl http://localhost:8000/api/surveys
   ```

4. **Check browser console** for frontend errors (F12 → Console tab)

---

## Version History

- **v1.0** (2026-04-21) - Initial setup guide with security best practices
  - Added comprehensive setup instructions
  - Security configuration and guidelines
  - GitHub deployment steps
  - Troubleshooting guide

---

**Last Updated:** April 21, 2026

**Questions?** Contact the project team or refer to the issue tracker on GitHub.
