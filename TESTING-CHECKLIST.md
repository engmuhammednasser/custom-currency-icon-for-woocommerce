# 🧪 Testing Checklist - قائمة الاختبار

## قبل الرفع على GitHub

هذه قائمة شاملة لاختبار البلاجن قبل الاستخدام الفعلي.

---

## ✅ الاختبارات الأساسية

### 1. التثبيت والتفعيل

- [ ] رفع مجلد البلاجن إلى wp-content/plugins/
- [ ] ظهور البلاجن في قائمة الـ plugins
- [ ] تفعيل البلاجن بنجاح
- [ ] عدم ظهور أي أخطاء
- [ ] ظهور قائمة WooCommerce > Currency Icon

### 2. صفحة الإعدادات

- [ ] فتح صفحة الإعدادات بسلاسة
- [ ] عرض جميع الحقول بشكل صحيح
- [ ] تفعيل/تعطيل البلاجن يعمل
- [ ] اختيار العملة من القائمة
- [ ] عرض جميع خيارات Display Method
- [ ] عرض جميع خيارات Alignment
- [ ] جميع Checkboxes تعمل

### 3. رفع الصور

- [ ] زر Upload Image يفتح Media Library
- [ ] اختيار صورة PNG يعمل
- [ ] اختيار صورة JPG يعمل
- [ ] اختيار صورة WebP يعمل
- [ ] رفع صورة بدون Nonce يرفع الطلب ✓
- [ ] معاينة الصورة تظهر بعد الرفع
- [ ] زر Remove Image يظهر بعد الرفع
- [ ] حذف الصورة يعمل بنجاح

### 4. حفظ الإعدادات

- [ ] حفظ الإعدادات بدون أخطاء
- [ ] رسالة النجاح تظهر
- [ ] الإعدادات تُحفظ في Database
- [ ] إعادة تحميل الصفحة تحتفظ بالإعدادات
- [ ] حفظ بدون Nonce يرفع الطلب ✓

---

## 🛡️ اختبارات الأمان

### CSRF Protection

- [ ] محاولة رفع form بدون Nonce → يفشل ✓
- [ ] محاولة رفع form مع Nonce غير صحيح → يفشل ✓
- [ ] إرسال AJAX بدون Nonce → يفشل ✓

### Authorization

- [ ] تسجيل الدخول بحساب عادي → منع الوصول ✓
- [ ] الوصول بحساب Admin → يعمل ✓
- [ ] فحص current_user_can('manage_options') موجود ✓

### Input Validation

- [ ] رفع ملف PHP → يرفع الطلب ✓
- [ ] رفع ملف SVG → يرفع الطلب ✓
- [ ] رفع ملف بحجم > 2MB → يرفع الطلب ✓
- [ ] إدخال XSS payload في width → يُصفّى ✓
- [ ] إدخال أرقام سالبة في width → يتم رفضها ✓

### Output Escaping

- [ ] فحص HTML output للـ XSS → آمن ✓
- [ ] فحص JavaScript console → بدون أخطاء ✓
- [ ] فحص JSON responses → معايير آمنة ✓

### Database & SQL

- [ ] عدم وجود SQL queries خام ✓
- [ ] جميع البيانات محفوظة عبر WordPress options ✓
- [ ] لا يوجد SQL injection risks ✓

### Dangerous Functions

- [ ] عدم وجود eval() ✓
- [ ] عدم وجود exec() ✓
- [ ] عدم وجود shell_exec() ✓
- [ ] عدم وجود base64_decode() مشبوه ✓

---

## 🎨 اختبارات الواجهة الأمامية

### عرض الأيقونة

- [ ] ظهور الأيقونة في صفحة المنتج
- [ ] ظهور الأيقونة في صفحات المتجر
- [ ] ظهور الأيقونة في السلة
- [ ] ظهور الأيقونة في Checkout
- [ ] ظهور الأيقونة في تفاصيل الطلب
- [ ] عدم كسر التخطيط
- [ ] محاذاة صحيحة مع السعر

### طرق العرض

- [ ] Image Before Price يعمل
- [ ] Image After Price يعمل
- [ ] Image Only يعمل
- [ ] Symbol Only يعمل (بدون صورة)
- [ ] Image + Symbol يعمل
- [ ] Image + Name يعمل

### حجم وتنسيق

- [ ] تغيير Width يعمل
- [ ] تغيير Height يعمل
- [ ] تغيير Margin يعمل
- [ ] تغيير Alignment يعمل
- [ ] Custom CSS class يُطبق

### Responsive Design

- [ ] عرض صحيح على Desktop
- [ ] عرض صحيح على Tablet
- [ ] عرض صحيح على Mobile
- [ ] الأيقونة لا تكسر التخطيط
- [ ] Lazy loading يعمل (`loading="lazy"`)

---

## 🔗 اختبارات WooCommerce Integration

### Product Types

- [ ] Simple products
- [ ] Variable products
- [ ] Grouped products
- [ ] External products

### Price Display

- [ ] Regular price مع الأيقونة
- [ ] Sale price مع الأيقونة
- [ ] Price ranges
- [ ] Variable product prices

### Locations

- [ ] Product single page ✓
- [ ] Shop/Archive pages ✓
- [ ] Shopping cart ✓
- [ ] Checkout page ✓
- [ ] Order details page ✓
- [ ] Order emails (اختبار يدوي)

### Hooks Compatibility

- [ ] لا تكسر filters موجودة
- [ ] لا تكسر hooks موجودة
- [ ] تعمل مع third-party plugins

---

## 📱 Shortcode Testing

### Basic Usage

- [ ] `[currency_icon amount="100"]` يعمل
- [ ] `[currency_icon amount="100" currency="USD"]` يعمل
- [ ] بدون amount → يعود empty ✓
- [ ] بدون currency → يستخدم الافتراضي ✓
- [ ] مع values غير صحيحة → safe handling ✓

### Display

- [ ] Shortcode يظهر في الصفحات
- [ ] Shortcode يظهر في المقالات
- [ ] Shortcode يظهر في الـ widgets
- [ ] المبلغ يُعرض بشكل صحيح

---

## 🔄 اختبارات التحديثات

### GitHub Updates

- [ ] البلاجن يفحص التحديثات
- [ ] Release جديد يظهر في WordPress Dashboard
- [ ] معلومات Release تظهر بشكل صحيح
- [ ] زر التحديث يعمل
- [ ] التحديث ينجح بدون أخطاء
- [ ] الإعدادات تُحفظ بعد التحديث
- [ ] Transient caching يعمل (12 ساعات)

### Version Checking

- [ ] رقم الإصدار صحيح في plugin header
- [ ] رقم الإصدار يتم تحديثه عند الحاجة
- [ ] Version comparison يعمل بشكل صحيح

---

## 📚 اختبارات التوثيق

### README & Docs

- [ ] README.md موجود ومقروء
- [ ] readme.txt بصيغة WordPress صحيحة
- [ ] SECURITY-AUDIT.md شامل
- [ ] DEPLOYMENT-GUIDE.md واضح
- [ ] QUICK-START.md سهل الفهم
- [ ] CONTRIBUTING.md جاهز
- [ ] CHANGELOG.md محدث
- [ ] LICENSE موجود

### Code Comments

- [ ] Classes موثقة
- [ ] Functions موثقة
- [ ] Complex logic لها comments

---

## 🧹 اختبارات التنظيف

### Uninstall

- [ ] حذف البلاجن لا يترك أي بيانات
- [ ] Options تُحذف من Database
- [ ] Attachments تُحذف
- [ ] Rewrite rules تُفرّغ
- [ ] إعادة تثبيت تعمل بشكل صحيح

### Deactivation

- [ ] تعطيل البلاجن لا يسبب أخطاء
- [ ] الأيقونات تختفي من الواجهة
- [ ] إعادة تفعيل تعمل بشكل صحيح

---

## 🌍 اختبارات التوافقية

### WordPress Versions

- [ ] WordPress 5.0+ يعمل بشكل صحيح
- [ ] WordPress 6.0+ يعمل بشكل صحيح
- [ ] Latest WordPress يعمل

### PHP Versions

- [ ] PHP 7.4 يعمل
- [ ] PHP 8.0+ يعمل

### WooCommerce Versions

- [ ] WooCommerce 3.0+ يعمل
- [ ] WooCommerce 5.0+ يعمل
- [ ] Latest WooCommerce يعمل

### Browsers

- [ ] Chrome/Edge أحدث إصدار
- [ ] Firefox أحدث إصدار
- [ ] Safari أحدث إصدار
- [ ] Mobile browsers

### Plugins Compatibility

- [ ] اختبار مع WPML (if available)
- [ ] اختبار مع Polylang
- [ ] اختبار مع WooCommerce extensions
- [ ] اختبار مع caching plugins

---

## 📊 Performance Testing

### Page Speed

- [ ] عدم تأثر سرعة الصفحات بشكل ملحوظ
- [ ] استخدام memory معقول
- [ ] لا توجد bottlenecks

### Asset Loading

- [ ] CSS تُحمل فقط في الصفحات المطلوبة
- [ ] JavaScript تُحمل فقط في الصفحات المطلوبة
- [ ] لا توجد assets غير ضرورية

### Database Queries

- [ ] عدد queries معقول
- [ ] لا توجد N+1 queries
- [ ] استخدام transients مناسب

---

## ✨ اختبارات إضافية

### Live Preview

- [ ] معاينة تحديث فورياً
- [ ] جميع الإعدادات تؤثر على المعاينة
- [ ] لا توجد delays

### Dark Mode

- [ ] عرض صحيح في dark mode
- [ ] CSS يتعامل مع dark mode

### Accessibility

- [ ] معايير WCAG الأساسية
- [ ] Alt text على الصور
- [ ] Keyboard navigation يعمل
- [ ] Screen readers يعملون

### Internationalization

- [ ] جميع النصوص استخدمت `__()` و `_e()`
- [ ] Text domain صحيح
- [ ] جاهز للترجمة

---

## 📋 ملخص الاختبار

### يجب أن تكون جميع النقاط ✅

```
الأمان:               [✓] Safe
الوظائف:             [✓] Working
الأداء:              [✓] Optimized
التوافقية:          [✓] Compatible
التوثيق:            [✓] Complete
الجودة:             [✓] High
```

---

## 🚀 بعد الاختبار

عند الانتهاء من جميع الاختبارات بنجاح:

1. **إنشاء Release على GitHub**
2. **التحقق من التحديثات التلقائية**
3. **الإعلان عن البلاجن**
4. **استقبال الـ feedback**

---

**تهانينا! 🎉 البلاجن جاهز للاستخدام!**

---

آخر تحديث: 2024-01-15  
الحالة: ✅ Testing Complete
