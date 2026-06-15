# Custom Currency Icon for WooCommerce

عرض أيقونة مخصصة للعملة بدلاً من الرمز النصي العادي في متجر WooCommerce.

## المميزات الرئيسية ✨

- 🎨 **صور مخصصة للعملات**: رفع صور PNG, JPG, أو WebP لكل عملة
- 👁️ **معاينة مباشرة**: رؤية التغييرات فوراً دون حفظ
- 📍 **تحكم شامل في الموقع**: اختر أماكن ظهور الأيقونة (منتج، سلة، دفع، إلخ)
- 🎯 **طرق عرض متعددة**: صورة قبل/بعد السعر، صورة فقط، مع الرمز، مع الاسم
- 🎛️ **إعدادات مرنة**: تحكم في الحجم، المسافة، المحاذاة، و CSS مخصص
- 🔒 **آمان عالي**: حماية CSRF، Nonce verification، Sanitization
- 🔄 **تحديثات من GitHub**: دعم التحديث المباشر من المستودع
- 📱 **responsive Design**: يعمل بشكل مثالي على جميع الأجهزة
- 🌐 **دعم WooCommerce كامل**: product prices, cart, checkout, emails
- 🏷️ **Shortcode**: استخدم `[currency_icon amount="100"]`

## المتطلبات 📋

- WordPress 5.0+
- PHP 7.4+
- WooCommerce 3.0+

## التثبيت 📥

### من صفحة WooCommerce

1. اذهب إلى **WooCommerce > Currency Icon Settings**
2. اختر العملة من القائمة المنسدلة
3. انقر **Upload Image** واختر صورة من مكتبة WordPress
4. اختر طريقة العرض والحجم والمكان
5. اضغط **Save Settings**

### Upload يدوي

1. قم بتحميل مجلد البلاجن إلى `/wp-content/plugins/`
2. فعّل البلاجن من قسم Plugins في WordPress
3. اذهب إلى WooCommerce > Currency Icon Settings

## الاستخدام 🚀

### الصفحة الرئيسية

1. **Enable/Disable**: فعّل أو عطّل البلاجن
2. **Upload Image**: اختر صورة لعملتك
3. **Display Method**: اختر كيف تريد عرض الأيقونة
4. **Image Size**: اضبط حجم الأيقونة (العرض والارتفاع)
5. **Margin**: اضبط المسافة حول الأيقونة
6. **Alignment**: اختر محاذاة الأيقونة مع النص
7. **Display Locations**: اختر الصفحات التي تظهر فيها الأيقونة

### طرق العرض المتاحة

| الطريقة | الوصف | مثال |
|--------|-------|-------|
| **Image Before Price** | الصورة قبل السعر | 🇸🇦 100 ر.س |
| **Image After Price** | الصورة بعد السعر | 100 ر.س 🇸🇦 |
| **Image Only** | الصورة فقط | 🇸🇦 |
| **Symbol Only** | الرمز فقط | 100 ر.س |
| **Image + Symbol** | الصورة والرمز | 🇸🇦 100 ر.س |
| **Image + Name** | الصورة مع اسم العملة | 🇸🇦 100 ر.س (الريال السعودي) |

### Shortcode

استخدم shortcode لعرض مبلغ معين مع الأيقونة:

```
[currency_icon amount="100"]
[currency_icon amount="50" currency="USD"]
```

**المعاملات:**
- `amount` (مطلوب): المبلغ المراد عرضه
- `currency` (اختياري): رمز العملة (مثل USD, EUR, SAR)

## معالجة التحديثات من GitHub 🔄

### إعداد التحديثات التلقائية

البلاجن يدعم التحديث المباشر من GitHub! عند إنشاء release جديد، سيظهر التحديث في لوحة تحكم WordPress.

### طريقة عمل الـ Release

1. اذهب إلى مستودع GitHub: https://github.com/engmuhammednasser/custom-currency-icon-for-woocommerce
2. انقر على **Releases**
3. انقر **Create a new release**
4. أدخل رقم الإصدار في **Tag version** (مثل: v1.1.0)
5. أضف عنوان وتفاصيل التحديث في **Release title** و **Description**
6. انقر **Publish release**

### التحقق من التحديثات

التحديثات تُفحص تلقائياً كل 12 ساعة. يمكنك فحصها يدوياً من:
- WordPress Dashboard > Updates

**ملاحظة:** البلاجن يستخدم GitHub API بدون Token، لذا لا حاجة لأي تكوين إضافي!

## الأمان 🔒

### معايير الأمان المتبعة

- ✅ منع Direct Access (ABSPATH check)
- ✅ Nonce Verification على كل الـ AJAX requests
- ✅ Capability Checks (manage_options)
- ✅ Input Sanitization
- ✅ Output Escaping
- ✅ Validation على الملفات المرفوعة
- ✅ دعم أنواع ملفات آمنة فقط (PNG, JPG, WebP)
- ✅ حد أقصى 2MB لحجم الملف
- ✅ No SQL injection risks (استخدام wp_options)
- ✅ No Eval/Exec/Shell commands
- ✅ No external file loading without verification

### معالجة الملفات المرفوعة

- الملفات تُرفع عبر WordPress Media Library
- فحص MIME type التلقائي
- دعم PNG, JPG, WebP فقط
- حد أقصى 2MB لكل ملف
- جميع المعالجات عبر WordPress functions الآمنة

## الأخطاء الشائعة والحل 🐛

### الصور لا تظهر
- تأكد من تفعيل البلاجن
- تأكد من اختيار صورة للعملة المستخدمة
- افحص إعدادات "Display Locations"

### الأيقونة تكسر التخطيط
- اضبط حجم الأيقونة من الإعدادات
- جرّب محاذاة مختلفة (alignment)
- استخدم Custom CSS class لمزيد من التحكم

### التحديثات لا تظهر
- انتظر حتى 12 ساعة
- فعّل تحديث يدوي من: WordPress Dashboard > Updates
- تأكد من اتصالك بالإنترنت

## الملفات المضمنة 📁

```
custom-currency-icon-for-woocommerce/
├── custom-currency-icon-for-woocommerce.php  # الملف الرئيسي
├── uninstall.php                            # تنظيف عند الحذف
├── README.md                                # هذا الملف
├── readme.txt                               # ملف WordPress
├── LICENSE                                  # رخصة GPL
├── includes/
│   ├── class-plugin.php                    # الفئة الرئيسية
│   ├── class-admin.php                     # إدارة لوحة التحكم
│   ├── class-frontend.php                  # عرض الواجهة الأمامية
│   ├── class-settings.php                  # إدارة الإعدادات
│   ├── class-updater.php                   # معالج التحديثات
│   ├── helpers.php                         # دوال مساعدة
│   └── templates/
│       └── settings-page.php               # نموذج صفحة الإعدادات
├── assets/
│   ├── css/
│   │   ├── admin.css                       # CSS الإدارة
│   │   └── frontend.css                    # CSS الواجهة الأمامية
│   └── js/
│       └── admin.js                        # JavaScript الإدارة
└── languages/                              # ملفات الترجمة
```

## دعم المتغيرات والـ Hooks 🎣

### Filters المتاحة

```php
// تعديل HTML أيقونة العملة
apply_filters( 'ccfw_currency_icon_html', $html, $currency, $image, $settings );

// تعديل سعر مع الأيقونة
apply_filters( 'ccfw_formatted_price', $formatted_price, $price, $currency );
```

### Actions المتاحة

```php
// عند تحميل البلاجن بنجاح
do_action( 'ccfw_loaded' );
```

### Helper Functions

```php
// الحصول على إعدادات
ccfw_get_option( 'display_method', 'image_before_price' );

// تحديث الإعدادات
ccfw_update_option( 'display_method', 'image_after_price' );

// الحصول على صورة العملة
ccfw_get_currency_image( 'SAR' );

// فحص إذا كان البلاجن مفعّل
ccfw_is_enabled();

// تنسيق المبلغ مع الأيقونة
ccfw_format_amount( 100, 'SAR' );
```

## Changelog 📝

### 1.0.0 (2024-01-15)
- 🎉 الإطلاق الأول
- ✨ دعم تحميل الصور
- 🎨 معاينة مباشرة
- 🔄 دعم GitHub Updates
- 🔒 أمان عالي
- 📝 توثيق كامل

## الترخيص 📄

هذا البلاجن مرخص تحت GPL-2.0+. انظر ملف LICENSE للتفاصيل.

## المساهمة 🤝

لديك ملاحظات أو تحسينات؟ تفضل بالمساهمة عبر:

1. Fork المستودع
2. أنشئ فرع للميزة الجديدة (`git checkout -b feature/AmazingFeature`)
3. Commit التغييرات (`git commit -m 'Add some AmazingFeature'`)
4. Push إلى الفرع (`git push origin feature/AmazingFeature`)
5. افتح Pull Request

## الدعم 💬

للتقارير عن الأخطاء والطلبات الجديدة: https://github.com/engmuhammednasser/custom-currency-icon-for-woocommerce/issues

## المؤلف ✍️

**Muhammed Nasser**
- GitHub: [@engmuhammednasser](https://github.com/engmuhammednasser)
- Email: eng.mohamed_nasser@hotmail.com

---

**استمتع باستخدام البلاجن! 🚀**
