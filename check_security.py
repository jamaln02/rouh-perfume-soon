#!/usr/bin/env python3
"""
Security Verification Script for Rouh Project
Checks for sensitive data before pushing to GitHub
"""

import os
import re
import subprocess
import sys
from pathlib import Path

class SecurityChecker:
    def __init__(self):
        self.errors = []
        self.warnings = []
        self.success = []
        
    def check_gitignore_patterns(self):
        """Verify .gitignore contains critical patterns"""
        gitignore_path = Path('.gitignore')
        
        if not gitignore_path.exists():
            self.errors.append("❌ .gitignore not found")
            return
            
        with open(gitignore_path, 'r') as f:
            content = f.read()
        
        critical_patterns = [
            r'\.env',
            r'backend/\.env',
            r'frontend/\.env',
            r'test-db\.php',
            r'debug\.php',
        ]
        
        for pattern in critical_patterns:
            if re.search(pattern, content):
                self.success.append(f"✅ Pattern '{pattern}' found in .gitignore")
            else:
                self.errors.append(f"❌ Pattern '{pattern}' NOT in .gitignore")
    
    def check_env_files_not_committed(self):
        """Check if .env files are tracked in git"""
        try:
            result = subprocess.run(
                ['git', 'ls-files', '--error-unmatch', 'backend/.env', 'frontend/.env'],
                capture_output=True,
                text=True
            )
            
            if result.returncode == 0:
                self.errors.append("❌ .env files are tracked in git!")
            else:
                self.success.append("✅ .env files are NOT tracked")
        except Exception as e:
            self.warnings.append(f"⚠️ Could not check git tracking: {e}")
    
    def check_debug_files(self):
        """Check for debug files that should not be committed"""
        debug_files = [
            'backend/public/test-db.php',
            'backend/public/debug.php',
            'backend/public/clear.php',
        ]
        
        for file_path in debug_files:
            if os.path.exists(file_path):
                self.warnings.append(f"⚠️ Debug file exists: {file_path} (should be deleted)")
            else:
                self.success.append(f"✅ Debug file not found: {file_path}")
    
    def check_env_examples(self):
        """Check if .env.example files exist"""
        examples = [
            'backend/.env.example',
            'frontend/.env.example',
        ]
        
        for example in examples:
            if os.path.exists(example):
                self.success.append(f"✅ Found: {example}")
            else:
                self.errors.append(f"❌ Missing: {example}")
    
    def check_sensitive_strings_in_code(self):
        """Search for common sensitive patterns in code"""
        sensitive_patterns = {
            r'password\s*=\s*["\'](?!CHANGE_ME|GENERATE|null|empty)[^"\']+["\']': 'Hardcoded password',
            r'api_key\s*=\s*["\'][^"\']+["\']': 'Hardcoded API key',
            r'secret\s*=\s*["\'][^"\']+["\']': 'Hardcoded secret',
            r'Jamal@26@Nabaa': 'Production password exposed',
        }
        
        search_paths = ['backend', 'frontend']
        
        for path in search_paths:
            if not os.path.exists(path):
                continue
                
            for root, dirs, files in os.walk(path):
                # Skip node_modules and vendor
                dirs[:] = [d for d in dirs if d not in ['node_modules', 'vendor', '.git']]
                
                for file in files:
                    if file.endswith(('.php', '.js', '.jsx', '.ts', '.tsx')):
                        file_path = os.path.join(root, file)
                        try:
                            with open(file_path, 'r', encoding='utf-8', errors='ignore') as f:
                                content = f.read()
                                
                            for pattern, description in sensitive_patterns.items():
                                if re.search(pattern, content, re.IGNORECASE):
                                    self.errors.append(
                                        f"❌ {description} found in {file_path}"
                                    )
                        except Exception as e:
                            pass
    
    def check_git_history(self):
        """Check git history for .env files"""
        try:
            result = subprocess.run(
                ['git', 'log', '--all', '--full-history', '--', '*.env'],
                capture_output=True,
                text=True
            )
            
            if result.stdout.strip():
                self.errors.append(
                    "❌ Found .env files in git history!\n"
                    "    Run: git filter-branch --tree-filter 'rm -f *.env' -f HEAD"
                )
            else:
                self.success.append("✅ No .env files in git history")
        except Exception as e:
            self.warnings.append(f"⚠️ Could not check git history: {e}")
    
    def run_all_checks(self):
        """Run all security checks"""
        print("\n" + "="*60)
        print("🔐 ROUH PROJECT SECURITY CHECKER")
        print("="*60 + "\n")
        
        self.check_gitignore_patterns()
        self.check_env_files_not_committed()
        self.check_debug_files()
        self.check_env_examples()
        self.check_sensitive_strings_in_code()
        self.check_git_history()
        
        self.print_results()
    
    def print_results(self):
        """Print check results"""
        if self.success:
            print("\n✅ PASSED CHECKS:")
            for msg in self.success:
                print(f"   {msg}")
        
        if self.warnings:
            print("\n⚠️  WARNINGS:")
            for msg in self.warnings:
                print(f"   {msg}")
        
        if self.errors:
            print("\n❌ FAILED CHECKS (MUST FIX BEFORE PUSHING):")
            for msg in self.errors:
                print(f"   {msg}")
        
        print("\n" + "="*60)
        
        if self.errors:
            print("❌ SECURITY CHECK FAILED - DO NOT PUSH TO GITHUB")
            print("="*60 + "\n")
            return False
        else:
            print("✅ SECURITY CHECK PASSED - SAFE TO PUSH")
            print("="*60 + "\n")
            return True

def main():
    checker = SecurityChecker()
    success = checker.run_all_checks()
    
    sys.exit(0 if success else 1)

if __name__ == '__main__':
    main()
