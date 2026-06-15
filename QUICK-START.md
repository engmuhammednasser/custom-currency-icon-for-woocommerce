<!-- GitHub Profile README -->

# Custom Currency Icon for WooCommerce - دليل الاستخدام السريع

## 🚀 البدء السريع (Quick Start)

### التثبيت

1. **تحميل البلاجن**
   ```bash
   # Clone المستودع أو Download الملفات
   git clone https://github.com/engmuhammednasser/custom-currency-icon-for-woocommerce.git
   ```

2. **رفع للخادم**
   ```
   /wp-content/plugins/custom-currency-icon-for-woocommerce
   ```

3. **تفعيل البلاجن**
   - اذهب إلى WordPress Admin
   - Plugins > Custom Currency Icon for WooCommerce
   - انقر Activate

4. **الإعدادات**
   - WooCommerce > Currency Icon
   - اختر عملة ورفع صورة
   - اضبط الإعدادات
   - Save Settings

### الأول مرة استخدام

```
1. رفع صورة للعملة (PNG, JPG, WebP)
   ↓
2. اختر طريقة العرض
   ↓
3. اضبط الحجم والمحاذاة
   ↓
4. اختر أماكن الظهور
   ↓
5. احفظ الإعدادات
```

## 📸 لقطات الشاشة

### صفحة الإعدادات
```
┌─────────────────────────────────┐
│ Custom Currency Icon Settings   │
├─────────────────────────────────┤
│ ✓ Enable Currency Icon          │
│                                 │
│ Select Currency:  [SAR ▼]       │
│ Upload Image      [Browse...]   │
│                                 │
│ Display Method:   [Image+Price▼]│
│ Width (px):       [24    ]      │
│ Height (px):      [24    ]      │
│ Margin (px):      [4     ]      │
│ Alignment:        [Middle▼]     │
│                                 │
│ ☑ Show in Product              │
│ ☑ Show in Shop                 │
│ ☑ Show in Cart                 │
│ ☑ Show in Checkout             │
│ ☑ Show in Order                │
│ ☑ Show in Emails               │
│                                 │
│              [Save Settings]    │
└─────────────────────────────────┘
```

## 🎯 استخدام Shortcode

```html
<!-- عرض مبلغ 100 بـ SAR -->
[currency_icon amount="100"]

<!-- عرض مبلغ 50 بـ USD -->
[currency_icon amount="50" currency="USD"]

<!-- في HTML Pages -->
<p>السعر: [currency_icon amount="99.99"]</p>
```

## 🔧 معالجة المشاكل الشائعة

### ❌ الصور لا تظهر

```php
✓ تأكد من تفعيل البلاجن
✓ تأكد من رفع صورة للعملة المستخدمة
✓ افحص إعدادات Display Locations
✓ فرّغ cache إذا كان موجوداً
```

### ❌ الأيقونة تكسر التخطيط

```php
✓ جرّب حجم أصغر (Width/Height)
✓ غيّر المحاذاة (Alignment)
✓ أضف Custom CSS class
```

### ❌ التحديثات لا تظهر

```php
✓ انتظر 12 ساعة
✓ جرّب فحص يدوي: Plugins > Updates
✓ تأكد من اتصال الإنترنت
✓ تحقق من إصدار الـ GitHub Release
```

## 📝 الملفات الرئيسية

| الملف | الوصف |
|------|--------|
| `custom-currency-icon-for-woocommerce.php` | ملف البلاجن الرئيسي |
| `includes/class-admin.php` | إدارة Dashboard |
| `includes/class-frontend.php` | عرض الواجهة |
| `includes/class-updater.php` | التحديثات من GitHub |
| `includes/helpers.php` | دوال مساعدة |
| `assets/css/admin.css` | تنسيقات الإدارة |
| `assets/js/admin.js` | معالج الإدارة |

## 🔐 الأمان

البلاجن يتبع أعلى معايير الأمان:

✅ CSRF Protection  
✅ Nonce Verification  
✅ Input Sanitization  
✅ Output Escaping  
✅ Capability Checks  
✅ Safe File Upload  

انظر [SECURITY-AUDIT.md](SECURITY-AUDIT.md) للتفاصيل.

## 📚 التوثيق الكاملة

- [README.md](README.md) - دليل شامل
- [SECURITY-AUDIT.md](SECURITY-AUDIT.md) - معايير الأمان
- [DEPLOYMENT-GUIDE.md](DEPLOYMENT-GUIDE.md) - رفع على GitHub
- [CONTRIBUTING.md](CONTRIBUTING.md) - المساهمة
- [CHANGELOG.md](CHANGELOG.md) - قائمة التغييرات

## 🛠️ الدعم الفني

### مشاكل شائعة

**S1: لم يتم حفظ الإعدادات**
- تأكد من الصلاحيات (Admin access)
- افحص console للأخطاء
- تحقق من PHP error logs

**S2: الصور لا تُحفظ**
- تأكد من صلاحيات الـ uploads
- تحقق من حجم الملف (max 2MB)
- جرّب صيغة مختلفة (PNG instead of JPG)

**S3: الشورتكود لا يعمل**
- تأكد من الصيغة الصحيحة
- استخدم `amount` و `currency`
- تحقق من تفعيل البلاجن

### طلب الدعم

1. [GitHub Issues](https://github.com/engmuhammednasser/custom-currency-icon-for-woocommerce/issues)
2. اختر Bug Report أو Feature Request
3. املأ النموذج بالتفاصيل
4. انتظر الرد

## 🚀 الإصدارات القادمة

- **v1.1.0**: Multi-currency support
- **v1.2.0**: Dark/Light mode icons
- **v2.0.0**: Advanced analytics

## 📄 الترخيص

GPL-2.0+ - انظر [LICENSE](LICENSE)

## ✍️ المؤلف

**Muhammed Nasser**
- GitHub: [@engmuhammednasser](https://github.com/engmuhammednasser)
- Portfolio: https://github.com/engmuhammednasser

---

**استمتع باستخدام البلاجن! 🎉**
