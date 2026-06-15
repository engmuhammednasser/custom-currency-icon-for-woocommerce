# Deployment Guide - رفع البلاجن على GitHub 🚀

هذا الدليل يشرح كيفية رفع مشروع البلاجن على GitHub بشكل احترافي.

## المتطلبات الأساسية 📋

1. حساب GitHub: https://github.com/engmuhammednasser
2. Git مثبّت على جهازك: https://git-scm.com/
3. Visual Studio Code أو أي محرر نصوص

## الخطوة 1: إنشاء مستودع جديد على GitHub

### أ) عبر الويب

1. اذهب إلى https://github.com/new
2. اسم المستودع: `custom-currency-icon-for-woocommerce`
3. الوصف: "عرض أيقونة مخصصة للعملة في WooCommerce"
4. اختر **Public** (عام)
5. ضع علامة على `.gitignore` → اختر **PHP**
6. الترخيص: **GPL-2.0**
7. اضغط **Create repository**

## الخطوة 2: تحضير الملفات المحلية

```bash
# انتقل إلى مجلد البلاجن
cd c:/xampp/htdocs/ashhalanksa/wp-content/plugins/custom-currency-icon-for-woocommerce

# تهيئة Git
git init

# إضافة ملف .gitignore
echo "# Node modules" > .gitignore
echo "node_modules/" >> .gitignore
echo "# IDE" >> .gitignore
echo ".vscode/" >> .gitignore
echo ".idea/" >> .gitignore
echo "*.code-workspace" >> .gitignore
echo "# OS" >> .gitignore
echo ".DS_Store" >> .gitignore
echo "Thumbs.db" >> .gitignore
echo "# Build" >> .gitignore
echo "build/" >> .gitignore
echo "dist/" >> .gitignore
echo "# Vendor (optional - remove if including)" >> .gitignore
# echo "vendor/" >> .gitignore
echo "# Composer" >> .gitignore
echo "composer.lock" >> .gitignore
echo "# WordPress" >> .gitignore
echo "wp-content/" >> .gitignore
echo "wp-admin/" >> .gitignore
echo "wp-includes/" >> .gitignore
echo "# Temp" >> .gitignore
echo "*.tmp" >> .gitignore
echo ".DS_Store" >> .gitignore
```

## الخطوة 3: إضافة الملفات والـ Commit الأول

```bash
# إضافة جميع الملفات
git add .

# عرض الملفات المراد إضافتها
git status

# Commit الأول
git commit -m "Initial commit: Add Custom Currency Icon for WooCommerce plugin MVP"

# عرض السجل
git log
```

## الخطوة 4: ربط المستودع المحلي بـ GitHub

```bash
# إضافة remote
git remote add origin https://github.com/engmuhammednasser/custom-currency-icon-for-woocommerce.git

# التحقق من الـ remote
git remote -v

# رفع الملفات (Push)
git branch -M main
git push -u origin main
```

## الخطوة 5: إنشاء Release للتحديثات التلقائية

### الطريقة الأولى: عبر الويب

1. اذهب إلى المستودع على GitHub
2. انقر على **Releases** أو **Tags**
3. انقر **Create a new release**
4. في **Tag version**: أدخل `v1.0.0`
5. في **Release title**: أدخل `Version 1.0.0 - Initial Release`
6. في **Describe this release**:

```
## ✨ Features
- Custom currency icon upload
- Live preview
- Multiple display methods
- Full WooCommerce support
- Automatic GitHub updates
- High security standards

## 🔧 Settings
- Image size control
- Display locations selection
- Custom CSS class support
- Vertical alignment options

## 📋 Requirements
- WordPress 5.0+
- PHP 7.4+
- WooCommerce 3.0+

## 🔒 Security
- CSRF protection
- Nonce verification
- Input sanitization
- Output escaping
- Safe file upload handling
- No hardcoded secrets

## 📝 Installation
Upload the plugin folder to `/wp-content/plugins/` and activate from WordPress admin.
```

7. اضغط **Publish release**

### الطريقة الثانية: من Terminal

```bash
# إنشاء tag
git tag -a v1.0.0 -m "Version 1.0.0 - Initial Release"

# رفع الـ tag
git push origin v1.0.0

# أو رفع جميع الـ tags
git push origin --tags
```

## الخطوة 6: التحقق من أن التحديثات تعمل

بعد إنشاء Release:

1. في WordPress Dashboard
2. اذهب إلى **Plugins > Updates**
3. يجب أن تري رسالة تحديث جديد متاح
4. اختبر التحديث

## الخطوات التالية - إنشاء Release جديد

### عند كل تحديث جديد:

```bash
# 1. عدّل الملفات
# 2. Test locally
# 3. Update version in main plugin file:
#    custom-currency-icon-for-woocommerce.php
#    Version: 1.1.0

# 4. Commit
git add .
git commit -m "Release version 1.1.0 - Add feature X, fix bug Y"

# 5. Create tag
git tag -a v1.1.0 -m "Version 1.1.0 - Add feature X, fix bug Y"

# 6. Push
git push origin main
git push origin v1.1.0
```

## ملفات مهمة للإضافة

تأكد من وجود هذه الملفات قبل الرفع:

```
✓ README.md - توثيق شامل
✓ readme.txt - متطلبات WordPress
✓ LICENSE - GPL-2.0 license
✓ SECURITY-AUDIT.md - معايير الأمان
✓ CHANGELOG.md (اختياري) - قائمة التغييرات
✓ CODE_OF_CONDUCT.md (اختياري)
✓ CONTRIBUTING.md (اختياري)
```

## إضافة Badge إلى README

```markdown
# Custom Currency Icon for WooCommerce

[![License: GPL-2.0+](https://img.shields.io/badge/License-GPL%202.0%2B-blue.svg)](https://www.gnu.org/licenses/gpl-2.0.html)
[![GitHub Release](https://img.shields.io/github/release/engmuhammednasser/custom-currency-icon-for-woocommerce.svg)](https://github.com/engmuhammednasser/custom-currency-icon-for-woocommerce/releases)
[![WordPress Tested](https://img.shields.io/badge/WordPress-5.0%2B-brightgreen.svg)](https://wordpress.org/)
[![PHP](https://img.shields.io/badge/PHP-7.4%2B-blue.svg)](https://php.net)
```

## حلول المشاكل الشائعة

### المشكلة: "fatal: remote origin already exists"

```bash
# الحل:
git remote remove origin
git remote add origin https://github.com/engmuhammednasser/custom-currency-icon-for-woocommerce.git
```

### المشكلة: "Permission denied (publickey)"

```bash
# الحل: إعداد SSH keys
ssh-keygen -t ed25519 -C "your_email@example.com"
# ثم أضف المفتاح العام إلى GitHub Settings
```

### المشكلة: "error: src refspec main does not match any"

```bash
# الحل:
git branch -M main
git push -u origin main
```

## Configuration GitHub للتحديثات التلقائية

لا يلزم أي إعدادات خاصة! البلاجن يستخدم GitHub API بشكل آمن:

1. ✅ قراءة البيانات من API بدون authentication
2. ✅ استخدام GitHub Releases كمصدر للتحديثات
3. ✅ تخزين مؤقت لمدة 12 ساعة لتقليل الطلبات
4. ✅ لا توجد API tokens محفوظة

## Project Structure النهائي

```
custom-currency-icon-for-woocommerce/
├── README.md
├── readme.txt
├── LICENSE
├── SECURITY-AUDIT.md
├── CHANGELOG.md (اختياري)
├── composer.json
├── .gitignore
├── .github/
│   └── workflows/
│       └── security.yml
├── custom-currency-icon-for-woocommerce.php
├── uninstall.php
├── includes/
│   ├── class-plugin.php
│   ├── class-admin.php
│   ├── class-frontend.php
│   ├── class-settings.php
│   ├── class-updater.php
│   ├── helpers.php
│   └── templates/
│       └── settings-page.php
├── assets/
│   ├── css/
│   │   ├── admin.css
│   │   └── frontend.css
│   └── js/
│       └── admin.js
└── languages/
```

## Branching Strategy (اختياري)

```bash
# للمشاريع الكبيرة
git checkout -b develop
git checkout -b feature/new-feature
git checkout -b bugfix/issue-123

# بعد الانتهاء
git checkout develop
git merge feature/new-feature
git push origin develop

# عند الإطلاق
git checkout main
git merge develop
git tag -a v1.1.0
git push origin main --tags
```

## النصائح النهائية

1. **اختبر محلياً أولاً** قبل الرفع
2. **اكتب commit messages واضحة**: "Fix XSS vulnerability" بدلاً من "fix"
3. **حدّث الإصدار** في plugin header قبل كل release
4. **كتابة release notes** تفصيلية
5. **استخدم semantic versioning**: v1.0.0, v1.1.0, v1.1.1
6. **المراجعة الأمنية** قبل كل release
7. **اختبر التحديث** في WordPress test site

---

✅ **أنت الآن جاهز لرفع البلاجن على GitHub والاستفادة من التحديثات التلقائية!**

لأسئلة أخرى، راجع:
- GitHub Docs: https://docs.github.com/
- Git Documentation: https://git-scm.com/doc
