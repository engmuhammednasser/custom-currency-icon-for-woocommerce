# CONTRIBUTING - المساهمة في البلاجن

شكراً لك على اهتمامك بالمساهمة في هذا المشروع! 🎉

## آداب التعاون 📋

- كن محترماً وبناءً في التعليقات والنقاش
- لا تقبل السلوك المسيء أو التمييزي
- ركز على الكود والأفكار، لا على الأشخاص

## كيفية المساهمة 🚀

### 1. الإبلاغ عن الأخطاء 🐛

إذا وجدت خطأ:
1. تحقق من أن الخطأ لم يتم الإبلاغ عنه بالفعل
2. اذهب إلى [Issues](https://github.com/engmuhammednasser/custom-currency-icon-for-woocommerce/issues)
3. انقر **New Issue**
4. اختر **Bug Report** واملأ النموذج

### 2. طلب ميزات جديدة ✨

هل لديك فكرة للتحسين؟
1. اذهب إلى [Issues](https://github.com/engmuhammednasser/custom-currency-icon-for-woocommerce/issues)
2. انقر **New Issue**
3. اختر **Feature Request** واملأ التفاصيل

### 3. المساهمة بالكود 💻

#### الخطوة 1: Fork المستودع

```bash
# اذهب إلى الصفحة الرئيسية
https://github.com/engmuhammednasser/custom-currency-icon-for-woocommerce

# انقر Fork
```

#### الخطوة 2: Clone الـ Fork

```bash
git clone https://github.com/YOUR-USERNAME/custom-currency-icon-for-woocommerce.git
cd custom-currency-icon-for-woocommerce
```

#### الخطوة 3: أنشئ فرع جديد

```bash
git checkout -b feature/your-feature-name
# أو للإصلاحات:
git checkout -b bugfix/your-bug-name
```

#### الخطوة 4: اعمل على التحسينات

```bash
# عدّل الملفات المطلوبة
# اختبر محلياً بجدية
# اتبع معايير الكود (انظر أسفل)
```

#### الخطوة 5: Commit التغييرات

```bash
git add .
git commit -m "Add description of changes"

# رسائل جيدة:
# "Add dark mode icon support"
# "Fix XSS vulnerability in settings"
# "Improve performance of icon loading"
```

#### الخطوة 6: Push التغييرات

```bash
git push origin feature/your-feature-name
```

#### الخطوة 7: فتح Pull Request

1. اذهب إلى المستودع الأصلي
2. انقر **Pull Requests**
3. انقر **New Pull Request**
4. اختر الفرع الجديد
5. أكمل النموذج واشرح التغييرات
6. انقر **Create Pull Request**

## معايير الكود 📐

### PHP

- اتبع [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/php/)
- استخدم tabs بدلاً من spaces (4 spaces = 1 tab)
- أضف comments على الأماكن المعقدة
- استخدم type hints عند الإمكان (PHP 7.4+)

```php
// ✅ جيد
public function save_settings( $settings ) {
	if ( ! current_user_can( 'manage_options' ) ) {
		return false;
	}

	return update_option( 'ccfw_settings', $settings );
}

// ❌ سيء
function save_settings($s) {
	update_option('ccfw_settings', $s);
}
```

### JavaScript

- استخدم jQuery إذا لزم الأمر (دعم النسخ القديمة)
- تجنب Arrow Functions (PHP < 7.4 compatibility)
- أضف comments واضحة

```javascript
// ✅ جيد
$(document).on('click', '#button', function() {
	// do something
});

// ❌ سيء
$('#button').click(() => {
	// arrow functions not compatible
});
```

### CSS

- استخدم BEM naming convention
- أضف معلقات للأجزاء المعقدة
- استخدم CSS variables عند الإمكان

```css
/* ✅ جيد */
.ccfw-settings__form {
	display: flex;
}

.ccfw-settings__button {
	background: #0073aa;
}

/* ❌ سيء */
.form {
	display: flex;
}

button {
	background: #0073aa;
}
```

## الاختبار 🧪

قبل فتح PR، تأكد من:

1. **اختبار locally**
   ```bash
   # قم بتثبيت البلاجن محلياً
   # اختبر الميزة الجديدة
   # اختبر الميزات الأخرى لا تزال تعمل
   ```

2. **فحص الأمان**
   ```bash
   # ابحث عن:
   # - eval(), exec(), shell_exec()
   # - Hardcoded secrets
   # - SQL injection risks
   # - XSS vulnerabilities
   ```

3. **اختبر على متصفحات مختلفة**
   - Chrome/Edge
   - Firefox
   - Safari

4. **اختبر على أجهزة مختلفة**
   - Desktop
   - Tablet
   - Mobile

## أثناء مراجعة PR 🔍

قد تطلب تحسينات إضافية. يرجى:
- الاستماع للملاحظات بانفتاح
- طلب توضيحات إذا لم تكن واضحة
- التقيد بمعايير المشروع

## ترخيص 📄

بالمساهمة، توافق على أن تكون مساهمتك تحت ترخيص GPL-2.0+.

## أسئلة؟ ❓

- اقرأ [README.md](README.md)
- اقرأ [SECURITY-AUDIT.md](SECURITY-AUDIT.md)
- افتح issue للسؤال
- تواصل عبر GitHub Discussions

---

**شكراً لمساهمتك! 🙏**
