# Rouh - Survey Management System

> A modern, secure, and scalable survey management platform built with Laravel and React.

![Status](https://img.shields.io/badge/status-active-success.svg)
![PHP Version](https://img.shields.io/badge/PHP-%3E%3D8.1-blue.svg)
![Node Version](https://img.shields.io/badge/Node-%3E%3D16-green.svg)
![License](https://img.shields.io/badge/license-MIT-blue.svg)
 
---

## 📋 Table of Contents

- [Features](#features)
- [Quick Start](#quick-start)
- [Project Structure](#project-structure)
- [Documentation](#documentation)
- [Development](#development)
- [Deployment](#deployment)
- [Security](#security)
- [Support](#support)

---

## ✨ Features

### Survey Management
- **Create & Distribute Surveys** - Intuitive survey builder with drag-and-drop
- **Question Types** - Multiple choice, text, rating, conditional logic
- **Analytics** - Real-time survey responses and insights
- **Export** - Download results in Excel/CSV format

### Advanced Capabilities
- **Conditional Logic** - Show/hide questions based on user responses
- **Discount Codes** - Generate and manage promotional codes
- **User Management** - Role-based access control (Admin, Manager, User)
- **API** - RESTful API for integrations

### Technical Highlights
- **Built with Laravel** - Robust PHP framework for backend
- **React Frontend** - Modern, responsive user interface
- **MySQL Database** - Reliable data persistence
- **Docker Support** - Easy local development
- **Security First** - Environment variables, CSRF protection, input validation

---

## 🚀 Quick Start

### For Development (5 minutes)

```bash
# 1. Clone repository
git clone https://github.com/YOUR_USERNAME/rouh-soon.git
cd rouh-soon

# 2. Setup Backend
cd backend
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate

# 3. Setup Frontend (new terminal)
cd frontend
npm install
npm start

# Backend: http://localhost:8000
# Frontend: http://localhost:3000
```

**Need more detailed setup?** → See [SETUP_GUIDE.md](./SETUP_GUIDE.md)

---

## 📁 Project Structure

```
rouh-soon/
├── backend/                 # Laravel API
│   ├── app/                # Laravel app code
│   ├── config/             # Configuration files
│   ├── database/           # Migrations & seeds
│   ├── routes/             # API routes
│   ├── storage/            # Logs & uploads (gitignored)
│   ├── tests/              # Unit & feature tests
│   ├── .env.example        # Environment template
│   └── composer.json       # PHP dependencies
│
├── frontend/               # React Application
│   ├── public/             # Static assets
│   ├── src/
│   │   ├── components/     # Reusable components
│   │   ├── pages/          # Page components
│   │   ├── hooks/          # Custom React hooks
│   │   └── App.js          # Main app
│   ├── .env.example        # Environment template
│   └── package.json        # Node dependencies
│
├── docs/                   # Additional documentation
├── SETUP_GUIDE.md         # Complete setup guide ⭐
├── CONTRIBUTING.md        # Contribution guidelines
├── DEPLOYMENT.md          # Production deployment
└── README.md              # This file
```

---

## 📚 Documentation

| Document | Purpose |
|----------|---------|
| **[SETUP_GUIDE.md](./SETUP_GUIDE.md)** ⭐ | Complete setup, troubleshooting, security |
| **[CONTRIBUTING.md](./CONTRIBUTING.md)** | Code standards, workflow, best practices |
| **[DEPLOYMENT.md](./DEPLOYMENT.md)** | Production deployment, GitHub Actions, rollback |
| **[backend/README.md](./backend/README.md)** | Backend-specific documentation |
| **[frontend/README.md](./frontend/README.md)** | Frontend-specific documentation |

### Quick Links
- **[System Requirements](./SETUP_GUIDE.md#system-requirements)** - What you need installed
- **[Common Issues](./SETUP_GUIDE.md#common-issues--troubleshooting)** - Solutions to common problems
- **[Security Best Practices](./SETUP_GUIDE.md#security--environment-variables)** - How to handle credentials
- **[GitHub Deployment](./DEPLOYMENT.md#github-setup)** - Push to GitHub safely

---

## 💻 Development

### Running Locally

```bash
# Terminal 1: Start Backend
cd backend
php artisan serve

# Terminal 2: Start Frontend
cd frontend
npm start
```

### Available Commands

**Backend (Laravel)**
```bash
php artisan migrate          # Run database migrations
php artisan test            # Run tests
php artisan tinker          # Interactive shell
php artisan serve           # Start dev server
```

**Frontend (React)**
```bash
npm start                   # Start dev server
npm test                    # Run tests
npm run build              # Build for production
npm run lint               # Check code style
```

### Code Quality

We follow:
- **Backend:** PSR-12 (PHP standards)
- **Frontend:** Airbnb ESLint config
- **Database:** Migrations for all changes

Check code style:
```bash
# Backend
composer lint

# Frontend
npm run lint
```

---

## 🔐 Security

### Environment Variables

**IMPORTANT:** Never commit `.env` files!

```bash
cp backend/.env.example backend/.env
# Edit with your local credentials

cp frontend/.env.example frontend/.env
# Edit with your local API URL
```

### What's Protected

- ✅ Database credentials (in .gitignore)
- ✅ API keys (in .gitignore)
- ✅ Environment configuration
- ✅ Sensitive debug files

### Production Security Checklist

Before deploying to production:
- [ ] Change database password on hosting provider
- [ ] Generate new APP_KEY: `php artisan key:generate`
- [ ] Set `APP_DEBUG=false`
- [ ] Set `APP_ENV=production`
- [ ] Use HTTPS everywhere
- [ ] Review [DEPLOYMENT.md](./DEPLOYMENT.md)

---

## 🌐 Deployment

### GitHub

1. Create repository on GitHub
2. Push code:
   ```bash
   git remote add origin https://github.com/YOUR_USERNAME/rouh-soon.git
   git branch -M main
   git push -u origin main
   ```
3. Enable branch protection

### Production

Full deployment guide → [DEPLOYMENT.md](./DEPLOYMENT.md)

Options:
- Manual SSH deployment
- GitHub Actions auto-deployment
- FTP upload
- Docker containers

---

## 📊 System Architecture

```
┌─────────────────────────────────────────────┐
│          React Frontend (Port 3000)         │
│       (Components, State, Forms)            │
└────────────────┬────────────────────────────┘
                 │ HTTP/HTTPS
                 │ /api/...
┌────────────────▼────────────────────────────┐
│       Laravel Backend (Port 8000)           │
│   (Controllers, Models, Business Logic)     │
└────────────────┬────────────────────────────┘
                 │ SQL
┌────────────────▼────────────────────────────┐
│           MySQL Database                    │
│    (Users, Surveys, Responses)             │
└─────────────────────────────────────────────┘
```

---

## 📈 Contribution Workflow

1. **Fork** the repository (if external)
2. **Create** a feature branch: `git checkout -b feature/your-feature`
3. **Make** your changes and test locally
4. **Commit** with descriptive messages
5. **Push** to your branch
6. **Open** a Pull Request with description

See [CONTRIBUTING.md](./CONTRIBUTING.md) for detailed guidelines.

---

## 🧪 Testing

```bash
# Backend Tests
cd backend
php artisan test

# Frontend Tests
cd frontend
npm test

# With Coverage
php artisan test --coverage
npm test -- --coverage
```

---

## 📦 Dependencies

### Backend
- **Laravel 10.x** - Web framework
- **PHP 8.1+** - Programming language
- **MySQL 5.7+** - Database
- **Filament** - Admin panel

### Frontend
- **React 18.x** - UI library
- **Tailwind CSS** - Styling
- **Axios** - HTTP client
- **React Router** - Navigation

---

## 🆘 Troubleshooting

### Quick Help

**Can't connect to database?**
→ Check [Database Connection Issues](./SETUP_GUIDE.md#2-sqlstatehr000-general-error)

**API showing CORS errors?**
→ Check [CORS Configuration](./SETUP_GUIDE.md#6-cors-errors-when-calling-frontend-to-backend)

**Port already in use?**
→ See [Port Issues](./SETUP_GUIDE.md#1-port-8000-is-already-in-use)

**More issues?**
→ Full troubleshooting: [SETUP_GUIDE.md](./SETUP_GUIDE.md#common-issues--troubleshooting)

---

## 📞 Support

- **Documentation:** See [docs/](./docs/) folder
- **Issues:** [GitHub Issues](https://github.com/YOUR_USERNAME/rouh-soon/issues)
- **Discussions:** [GitHub Discussions](https://github.com/YOUR_USERNAME/rouh-soon/discussions)

---

## 📄 License

This project is licensed under the MIT License - see LICENSE file for details.

---

## 👥 Team

- **Project Lead:** [Your Name]
- **Backend:** Laravel/PHP developers
- **Frontend:** React developers
- **DevOps:** Deployment & infrastructure

---

## 🚀 Roadmap

### Upcoming Features
- [ ] Advanced analytics dashboard
- [ ] API rate limiting
- [ ] Email campaign integration
- [ ] Survey templates library
- [ ] AI-powered question suggestions

### Current Version
- **v1.0.0** - MVP with core survey functionality

---

## ✅ Checklist Before Pushing to GitHub

- [ ] Run `git status` - no `.env` files shown
- [ ] Run tests locally - all pass
- [ ] Check browser console - no errors
- [ ] Review [SETUP_GUIDE.md](./SETUP_GUIDE.md#security--environment-variables)
- [ ] Read [CONTRIBUTING.md](./CONTRIBUTING.md#security-best-practices)
- [ ] Push to GitHub

---

**Ready to start?** → [SETUP_GUIDE.md](./SETUP_GUIDE.md)

**Questions?** → Check the relevant documentation or open an issue.

---

**Last Updated:** April 21, 2026
#
#   r o u h - p e r f u m e - s o o n 
 
 
