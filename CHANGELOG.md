# Changelog

جميع التغييرات البارزة في هذا المشروع موثقة في هذا الملف.

## [1.0.0] - 2024-01-15

### ✨ أضيف (Added)

- **إنشاء صفحة إعدادات قابلة للاستخدام** في WooCommerce Admin
  - تحميل صور عملات مخصصة
  - معاينة مباشرة للتغييرات
  - إعدادات متقدمة للحجم والمحاذاة

- **دعم تحميل الصور الآمن**
  - دعم PNG و JPG و WebP
  - التحقق من الـ MIME type
  - حد أقصى 2MB لكل ملف
  - استخدام WordPress Media Library

- **طرق عرض متعددة**
  - صورة قبل السعر
  - صورة بعد السعر
  - صورة فقط
  - رمز العملة فقط
  - صورة + رمز العملة
  - صورة + اسم العملة

- **تحكم شامل في مواقع العرض**
  - صفحة المنتج
  - صفحات المتجر والأرشيف
  - السلة
  - الدفع (Checkout)
  - تفاصيل الطلب
  - إيميلات الطلب

- **معاينة مباشرة (Live Preview)**
  - تحديث فوري عند تغيير الإعدادات
  - عرض للسعر مع الأيقونة

- **Shortcode للعرض المخصص**
  - `[currency_icon amount="100"]`
  - دعم معاملات مخصصة

- **دعم GitHub Updates**
  - التحديث التلقائي من GitHub Releases
  - عدم الحاجة إلى tokens أو API keys
  - تخزين مؤقت ذكي (12 ساعة)

- **معايير أمان عالية جداً**
  - CSRF Protection مع Nonces
  - Input Sanitization شامل
  - Output Escaping على جميع النصوص
  - Capability Checks (manage_options)
  - منع Direct Access
  - عدم وجود SQL Injection risks
  - عدم وجود hardcoded secrets

- **توثيق شامل**
  - README.md مفصّل
  - readme.txt متوافق مع WordPress
  - Security Audit شامل
  - Deployment Guide خطوة بخطوة
  - Inline code comments

- **دعم WooCommerce كامل**
  - Product prices
  - Sale prices
  - Variable products
  - Shop/Archive pages
  - Cart totals
  - Checkout totals
  - Order details
  - Order emails

### 🔧 تحسينات تقنية

- أنماط كود احترافية (PSR-4 Autoloading)
- استخدام WordPress Hooks و Filters بشكل نظيف
- عدم كسر تنسيق الأسعار الأصلي
- Fallback إلى الرمز الافتراضي عند عدم وجود صورة
- دعم Responsive Design
- دعم متصفحات قديمة نسبياً (jQuery compat)

### 📦 الملفات المضمنة

- `custom-currency-icon-for-woocommerce.php` - ملف البلاجن الرئيسي
- `includes/class-plugin.php` - الفئة الرئيسية
- `includes/class-admin.php` - إدارة لوحة التحكم
- `includes/class-frontend.php` - عرض الواجهة الأمامية
- `includes/class-settings.php` - إدارة الإعدادات
- `includes/class-updater.php` - معالج التحديثات من GitHub
- `includes/helpers.php` - دوال مساعدة شاملة
- `includes/templates/settings-page.php` - نموذج صفحة الإعدادات
- `assets/css/admin.css` - تنسيقات الإدارة
- `assets/css/frontend.css` - تنسيقات الواجهة الأمامية
- `assets/js/admin.js` - معالج JavaScript للإدارة
- `uninstall.php` - تنظيف عند حذف البلاجن
- `composer.json` - ملف Composer للمشروع
- `.github/workflows/security.yml` - فحص أمان GitHub Actions

### 🔐 المتطلبات

- WordPress 5.0+
- PHP 7.4+
- WooCommerce 3.0+
- jQuery (من WordPress)

### 📝 الملاحظات

- نسخة MVP جاهزة للإنتاج
- جميع الميزات الأساسية مطبقة
- معايير أمان عالية جداً
- توثيق شامل وسهل الفهم
- جاهزة للتطوير المستقبلي

---

## الإصدارات المستقبلية المخططة

### v1.1.0 - Multi-currency Support (مخطط)
- دعم إعدادات مخصصة لكل عملة
- presets لأشكال العملات الشهيرة

### v1.2.0 - Advanced Features (مخطط)
- Dark/Light mode icons
- Gutenberg block
- Rules حسب المنتج أو الدولة

### v2.0.0 - Pro Version (مخطط)
- Advanced analytics
- Custom branding
- Priority support

---

**آخر تحديث:** 2024-01-15
**الإصدار الحالي:** 1.0.0
**الحالة:** ✅ Stable - جاهز للاستخدام الإنتاجي
