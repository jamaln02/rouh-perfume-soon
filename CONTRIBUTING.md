# Contributing to Rouh Project

Thank you for your interest in contributing to the Rouh survey system! This document provides guidelines and best practices for developers working on this project.

---

## Table of Contents

1. [Getting Started](#getting-started)
2. [Development Workflow](#development-workflow)
3. [Code Standards](#code-standards)
4. [Security Best Practices](#security-best-practices)
5. [Commit Message Guidelines](#commit-message-guidelines)
6. [Testing](#testing)
7. [Reporting Issues](#reporting-issues)
8. [Pull Request Process](#pull-request-process)

---

## Getting Started

### 1. Fork & Clone (if you're external contributor)

```bash
# Fork the repo on GitHub, then:
git clone https://github.com/YOUR_USERNAME/rouh-soon.git
cd rouh-soon
```

### 2. Set Up Your Development Environment

Follow the instructions in [SETUP_GUIDE.md](./SETUP_GUIDE.md):

```bash
# Backend Setup
cd backend
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate

# Frontend Setup (in another terminal)
cd frontend
npm install
cp .env.example .env
npm start
```

### 3. Create a Feature Branch

```bash
git checkout -b feature/descriptive-feature-name
```

Branch naming conventions:
- `feature/` - New features
- `fix/` - Bug fixes
- `refactor/` - Code refactoring
- `docs/` - Documentation updates
- `test/` - Adding tests
- `chore/` - Maintenance tasks

---

## Development Workflow

### 1. Before Making Changes

```bash
# Make sure you're on latest main
git checkout main
git pull origin main

# Create your feature branch
git checkout -b feature/your-feature-name
```

### 2. Making Changes

**For Backend (Laravel):**
- Write code following PSR-12 standards
- Update tests in `tests/` folder
- Keep database migrations clean and reversible
- Add comments for complex logic

**For Frontend (React):**
- Use functional components with hooks
- Follow ESLint rules
- Keep components small and reusable
- Add PropTypes or TypeScript types

### 3. Testing Locally

```bash
# Backend tests
cd backend
php artisan test

# Frontend tests
cd frontend
npm test
```

### 4. Commit Your Changes

```bash
git add .
git commit -m "feat: add new survey question type"
```

See [Commit Message Guidelines](#commit-message-guidelines) below.

### 5. Push to Your Branch

```bash
git push origin feature/your-feature-name
```

### 6. Create a Pull Request

Go to GitHub and create a Pull Request with:
- Clear title describing what you did
- Description explaining why these changes were needed
- Link any related issues
- Verify CI/CD checks pass

---

## Code Standards

### Backend (PHP/Laravel)

**Style Guide:** PSR-12 (PHP Standard Recommendation)

```php
// ✅ Correct
class SurveyController extends Controller
{
    public function store(StoreRequest $request)
    {
        return Survey::create($request->validated());
    }
}

// ❌ Incorrect
class SurveyController extends Controller {
    public function store($request) {
        return Survey::create($request->all());
    }
}
```

**Best Practices:**
- Use form requests for validation
- Use models instead of raw queries
- Add type hints to methods
- Keep controllers slim (use services if needed)
- Add docblocks to complex methods
- Use meaningful variable names

**Check Code Style:**
```bash
composer lint
```

### Frontend (JavaScript/React)

**Style Guide:** Airbnb JavaScript Style Guide

```jsx
// ✅ Correct
import React, { useState } from 'react';

const SurveyForm = ({ onSubmit }) => {
  const [loading, setLoading] = useState(false);

  const handleSubmit = async (data) => {
    setLoading(true);
    try {
      await onSubmit(data);
    } finally {
      setLoading(false);
    }
  };

  return <form onSubmit={handleSubmit}>...</form>;
};

export default SurveyForm;

// ❌ Incorrect
const SurveyForm = (props) => {
  let loading = false;
  
  function handleSubmit(data) {
    loading = true;
    props.onSubmit(data);
  }

  return <form onSubmit={handleSubmit}>...</form>;
};
```

**Best Practices:**
- Use hooks instead of class components
- Destructure props
- Use descriptive function names
- Extract complex JSX into components
- Avoid inline styles (use CSS modules or Tailwind)
- Add comments for business logic

**Check Code Style:**
```bash
npm run lint
```

---

## Security Best Practices

### ⚠️ CRITICAL: Never Commit Credentials

**DO NOT:**
```javascript
// ❌ WRONG
const API_KEY = 'sk-1234567890abcdef';
const DB_PASSWORD = 'Jamal@26@Nabaa';
const SECRET = 'some-hardcoded-secret';
```

**DO:**
```javascript
// ✅ CORRECT
const apiKey = process.env.REACT_APP_API_KEY;
const backendUrl = process.env.REACT_APP_BACKEND_URL;
```

### Environment Variables

**For Development:**
```bash
# .env (NEVER commit)
DB_PASSWORD=strong_password_123
APP_KEY=base64:generated_key
REACT_APP_API_KEY=dev_key_only

# .env.example (OK to commit)
DB_PASSWORD=CHANGE_ME
APP_KEY=GENERATE_WITH_php_artisan_key_generate
REACT_APP_API_KEY=
```

### Pre-Commit Verification

Before pushing, verify:
```bash
# Check for .env files
git diff --cached --name-only | grep -E "\.env|credentials|secret|password|api_key"

# Should return nothing - if it does, DON'T PUSH!
git reset HEAD <file>
```

### Common Security Mistakes to Avoid

- ❌ Hardcoding API keys or passwords
- ❌ Logging sensitive information
- ❌ Committing `.env` files
- ❌ Using weak passwords (< 12 characters)
- ❌ Storing user passwords in plain text
- ❌ Missing CSRF protection
- ❌ SQL injection vulnerabilities
- ❌ XSS vulnerabilities in forms

---

## Commit Message Guidelines

### Format

```
<type>(<scope>): <subject>

<body>

<footer>
```

### Type

- `feat` - New feature
- `fix` - Bug fix
- `refactor` - Code refactoring
- `style` - Formatting, missing semicolons, etc.
- `test` - Adding tests
- `chore` - Maintenance, dependencies
- `docs` - Documentation updates
- `perf` - Performance improvements

### Scope (optional)

Component or area affected:
- `survey` - Survey functionality
- `auth` - Authentication
- `admin` - Admin panel
- `api` - API endpoints
- `frontend` - React components
- `db` - Database

### Subject

- Use imperative mood ("add feature" not "added feature")
- No capital letter at start
- No period at end
- Maximum 50 characters

### Body (optional)

- Explain WHAT changed and WHY
- Reference issue number: `Closes #123`
- Keep lines under 72 characters
- Use bullet points for multiple changes

### Examples

```bash
git commit -m "feat(survey): add conditional question logic"

git commit -m "fix(auth): resolve JWT token expiration issue

Fixes #456

- Updated token refresh endpoint
- Added retry logic for expired tokens
- Updated tests for new behavior"

git commit -m "refactor(api): simplify survey submission handler"

git commit -m "docs: update setup instructions for Windows"
```

---

## Testing

### Backend Testing

```bash
cd backend

# Run all tests
php artisan test

# Run specific test file
php artisan test tests/Feature/SurveyTest.php

# Run with coverage
php artisan test --coverage

# Run unit tests only
php artisan test tests/Unit
```

### Frontend Testing

```bash
cd frontend

# Run all tests
npm test

# Run with coverage
npm test -- --coverage

# Run specific test file
npm test SurveyForm.test.js
```

### Test Coverage Requirements

- Aim for > 80% code coverage
- Test happy path and error cases
- Test edge cases and boundary conditions
- Include integration tests

### Writing Good Tests

```php
// Backend Example
public function test_user_can_submit_survey()
{
    $survey = Survey::factory()->create();
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/api/surveys/' . $survey->id . '/submit', [
        'answers' => ['question_1' => 'answer']
    ]);

    $response->assertStatus(201);
    $this->assertDatabaseHas('survey_submissions', [
        'user_id' => $user->id,
        'survey_id' => $survey->id
    ]);
}
```

```javascript
// Frontend Example
test('renders survey form with questions', () => {
  const mockSurvey = { id: 1, questions: [] };
  render(<SurveyForm survey={mockSurvey} />);
  
  expect(screen.getByRole('form')).toBeInTheDocument();
});
```

---

## Reporting Issues

### How to Report a Bug

1. Check if the issue already exists
2. Create a new issue with:
   - **Clear title** describing the problem
   - **Steps to reproduce**
   - **Expected behavior**
   - **Actual behavior**
   - **Screenshots** (if applicable)
   - **Environment** (OS, browser, PHP version, etc.)

### Example Issue

```markdown
## Bug: Survey submission fails with validation error

### Steps to Reproduce
1. Navigate to survey with conditional questions
2. Fill out all required fields
3. Click submit button
4. Error appears

### Expected Behavior
Survey should submit successfully and show confirmation

### Actual Behavior
Error: "SQLSTATE[42S22]: Column not found"

### Environment
- PHP: 8.2
- Laravel: 10.x
- Browser: Chrome 124
```

---

## Pull Request Process

### Before Creating PR

- [ ] Code follows project standards
- [ ] Tests pass locally (`php artisan test`, `npm test`)
- [ ] No console errors or warnings
- [ ] `.env` files are NOT committed
- [ ] No sensitive data exposed
- [ ] Related issues are referenced
- [ ] Documentation updated if needed

### PR Template

```markdown
## Description
Brief description of changes

## Type of Change
- [ ] New feature
- [ ] Bug fix
- [ ] Breaking change
- [ ] Documentation update

## Testing
How was this tested?

## Screenshots (if applicable)
Before/after screenshots

## Related Issues
Closes #123

## Checklist
- [ ] Tests pass
- [ ] No breaking changes
- [ ] Documented changes
- [ ] No sensitive data committed
```

### Review Process

1. **Author** opens PR with description
2. **Reviewers** request changes (if needed)
3. **Author** makes updates
4. **Reviewers** approve when satisfied
5. **Maintainer** merges to main

### Merging

```bash
# Only maintainers can merge to main
git merge --squash feature/your-feature-name
git push origin main
```

---

## Development Tips

### Useful Commands

```bash
# View recent commits
git log --oneline -10

# See what files changed
git status

# Compare branches
git diff main feature/your-branch

# Clear PHP/Node cache
php artisan optimize:clear
npm cache clean --force

# Format code (Laravel)
composer run-script format
```

### Debugging

**Laravel Debugging:**
```bash
# Enable debug mode locally only
APP_DEBUG=true

# Check logs
tail -f storage/logs/laravel.log

# Database inspection
php artisan tinker
```

**React Debugging:**
- Use React DevTools browser extension
- Use Redux DevTools if using Redux
- Check browser console (F12)
- Use `console.log()` judiciously

### Performance

- Use lazy loading for components
- Implement pagination for large datasets
- Cache frequently accessed data
- Use database indexes for large tables
- Minimize bundle size

---

## Questions?

- Check existing issues and discussions
- Review SETUP_GUIDE.md for setup help
- Ask project maintainers for guidance
- Check project documentation

---

## Code of Conduct

Be respectful and constructive in all interactions. This is a collaborative project and we value all contributions.

---

**Thank you for contributing to Rouh!** 🎉
