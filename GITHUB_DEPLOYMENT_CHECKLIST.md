# Pre-GitHub Deployment Checklist

Complete this checklist before pushing to GitHub to ensure security and quality.

---

## 🔒 Security Verification (CRITICAL)

- [ ] Run security check script:
  ```bash
  python check_security.py
  # Result should be: ✅ SECURITY CHECK PASSED
  ```

- [ ] Verify no `.env` files exist:
  ```bash
  ls -la backend/.env          # ❌ Should NOT exist
  ls -la frontend/.env         # ❌ Should NOT exist
  ```

- [ ] Verify debug files deleted:
  ```bash
  ls -la backend/public/test-db.php  # ❌ Should NOT exist
  ls -la backend/public/debug.php    # ❌ Should NOT exist
  ```

- [ ] Check git status shows no sensitive files:
  ```bash
  git status
  # Should NOT include: .env, test-db.php, debug.php
  ```

- [ ] Verify `.env.example` files exist:
  ```bash
  ls backend/.env.example   # ✅ SHOULD exist
  ls frontend/.env.example  # ✅ SHOULD exist
  ```

---

## 🔐 Production Server Changes (IMPORTANT)

**MUST do on your hosting provider (Hostinger) BEFORE pushing:**

- [ ] SSH into server and edit `.env`:
  ```bash
  # Change APP_DEBUG to false
  APP_DEBUG=false
  
  # Change APP_ENV to production
  APP_ENV=production
  
  # Generate new APP_KEY
  php artisan key:generate
  
  # Change database password to NEW value
  DB_PASSWORD=NewComplexPassword@123!
  ```

- [ ] Verify changes took effect:
  ```bash
  php artisan config:show
  # APP_DEBUG should be: false
  # APP_ENV should be: production
  ```

---

## 📝 Documentation Check

- [ ] [SETUP_GUIDE.md](./SETUP_GUIDE.md) is complete
- [ ] [CONTRIBUTING.md](./CONTRIBUTING.md) is complete
- [ ] [DEPLOYMENT.md](./DEPLOYMENT.md) is complete
- [ ] [README.md](./README.md) is up to date
- [ ] [SECURITY_SUMMARY.md](./SECURITY_SUMMARY.md) is reviewed

---

## 🧪 Local Testing

- [ ] Backend tests pass:
  ```bash
  cd backend && php artisan test
  ```

- [ ] Frontend tests pass:
  ```bash
  cd frontend && npm test
  ```

- [ ] Backend runs without errors:
  ```bash
  php artisan serve
  # Should start without errors
  ```

- [ ] Frontend builds without errors:
  ```bash
  npm run build
  # Should complete successfully
  ```

- [ ] No console errors in browser (F12)
- [ ] Can submit a test survey end-to-end

---

## 🗑️ Delete Sensitive Files

**Run these commands to clean up:**

```bash
cd e:\rouh\rouh-soon

# Delete backend .env (will be in .gitignore anyway)
Remove-Item -Path backend\.env -ErrorAction SilentlyContinue

# Delete frontend .env
Remove-Item -Path frontend\.env -ErrorAction SilentlyContinue

# Delete debug files
Remove-Item -Path backend\public\test-db.php -ErrorAction SilentlyContinue
Remove-Item -Path backend\public\debug.php -ErrorAction SilentlyContinue
Remove-Item -Path backend\public\clear.php -ErrorAction SilentlyContinue

# Verify they're gone
Get-ChildItem -Path backend -Name ".env", "test-db.php", "debug.php" -ErrorAction SilentlyContinue
# Should return: (nothing)
```

- [ ] Confirmed: No `.env` files exist locally
- [ ] Confirmed: No debug files exist

---

## 📊 Code Quality

- [ ] Backend code follows PSR-12:
  ```bash
  composer lint
  ```

- [ ] Frontend code follows Airbnb style:
  ```bash
  npm run lint
  ```

- [ ] No `TODO`/`FIXME` comments left unaddressed
- [ ] All functions have proper documentation
- [ ] No console.log() debugging statements left in frontend

---

## 🔍 Final Git Verification

- [ ] Check what will be committed:
  ```bash
  git status
  # Review each file - nothing sensitive should appear
  ```

- [ ] View the actual diff:
  ```bash
  git diff --cached
  # Scan for: password, secret, api_key, credentials
  ```

- [ ] Verify no .env in history:
  ```bash
  git log --all --full-history -- "*.env"
  # Should return: (nothing)
  ```

- [ ] Verify test-db.php not committed:
  ```bash
  git log --all --full-history -- "test-db.php"
  # Should return: (nothing)
  ```

---

## 🚀 GitHub Repository Setup

- [ ] Created new repository on GitHub
  - Name: `rouh-soon`
  - Visibility: Public
  - No initialization (we have existing code)

- [ ] Configured local git remote:
  ```bash
  git remote add origin https://github.com/YOUR_USERNAME/rouh-soon.git
  git branch -M main
  ```

---

## ✅ Final Commit & Push

```bash
# Stage all changes
git add -A

# Create commit with descriptive message
git commit -m "feat: add comprehensive documentation and security configurations

- Add SETUP_GUIDE.md with complete setup instructions
- Add CONTRIBUTING.md with development guidelines
- Add DEPLOYMENT.md with production deployment guide
- Add SECURITY_SUMMARY.md documenting all security fixes
- Update .gitignore to protect all sensitive files
- Create .env.example templates for developers
- Add security verification script"

# Push to GitHub
git push -u origin main
```

- [ ] Commit created successfully
- [ ] Push completed without errors
- [ ] Repository visible on GitHub

---

## 🔐 GitHub Configuration

After pushing, configure repository security:

### Branch Protection
1. Go to **Settings → Branches**
2. Click **Add rule**
3. **Branch name pattern:** `main`
4. Enable:
   - [ ] Require pull request reviews before merging
   - [ ] Require branches to be up to date before merging
   - [ ] Require status checks to pass before merging
   - [ ] Include administrators

### Repository Secrets
1. Go to **Settings → Secrets and variables → Actions**
2. Add secrets for CI/CD (if using GitHub Actions):
   - [ ] `DB_PASSWORD` - Production database password
   - [ ] `APP_KEY` - Laravel application key
   - [ ] `HOSTING_USERNAME` - SSH username (if auto-deploying)
   - [ ] `HOSTING_SSH_KEY` - SSH private key (if auto-deploying)

### Repository Settings
1. Go to **Settings → General**
2. Configure:
   - [ ] Description: "Survey management system with Laravel & React"
   - [ ] Website: `https://rouh.shop` (if live)
   - [ ] Topics: `survey`, `laravel`, `react`, `php`

---

## 📢 Team Communication

After pushing to GitHub:

- [ ] Notify team members of the public repository
- [ ] Send them the link to README.md
- [ ] Remind them to:
  - Copy `.env.example` files locally
  - Ask for real credentials from tech lead
  - **NEVER** commit `.env` files
  - Read CONTRIBUTING.md before developing

---

## 🎯 Post-Deployment Verification

**After code is on GitHub:**

- [ ] Repository is accessible: `https://github.com/YOUR_USERNAME/rouh-soon`
- [ ] Files visible correctly (README shows, .env hidden)
- [ ] Clone test works:
  ```bash
  git clone https://github.com/YOUR_USERNAME/rouh-soon.git test-clone
  cd test-clone
  
  # Verify necessary files exist
  ls backend/.env.example        # ✅ exists
  ls backend/.env                # ❌ doesn't exist
  ls SETUP_GUIDE.md             # ✅ exists
  ```

- [ ] Project is marked as public (visible to everyone)
- [ ] Branch protection rules are active
- [ ] GitHub Actions enabled (if using)

---

## 🔔 Monitoring & Maintenance

After going live on GitHub:

- [ ] Monitor repository for issues/PRs daily
- [ ] Review any security alerts from GitHub
- [ ] Keep dependencies updated
- [ ] Review access logs for suspicious activity
- [ ] Keep production backups current

---

## 📋 Troubleshooting

### If security check fails:
1. Review the errors listed
2. Fix each issue
3. Re-run `python check_security.py`
4. Don't push until it passes ✅

### If files were accidentally committed:
```bash
# BEFORE pushing to GitHub:
git reset HEAD <file>           # Unstage
git checkout -- <file>         # Discard
git filter-branch --tree-filter 'rm -f <file>' -f HEAD  # Remove from history
```

### If already pushed to GitHub:
1. **Change ALL credentials immediately**
2. Keep repository but mark as archived
3. Create new repository with fixed code
4. Report to team members about incident

---

## ✨ Success Criteria

Project is ready for GitHub when:

- ✅ `python check_security.py` returns: **SECURITY CHECK PASSED**
- ✅ All tests pass (backend & frontend)
- ✅ No `.env` files exist locally
- ✅ Production server credentials changed
- ✅ Documentation is complete
- ✅ `git status` shows no sensitive files
- ✅ GitHub repository created
- ✅ Branch protection enabled
- ✅ Code successfully pushed

---

## 🎉 You're Ready!

Once all items are checked, your project is secure and ready for public GitHub sharing.

**Remember:** The public code is safe because:
- All credentials are in `.env` (protected by `.gitignore`)
- Documentation guides developers to create local `.env` files
- Sensitive files are never committed
- Production servers have different credentials

---

**Checklist Version:** 1.0  
**Last Updated:** April 21, 2026  
**Status:** Ready for deployment
