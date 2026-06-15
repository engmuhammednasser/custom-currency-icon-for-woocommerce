name: Bug Report
description: أبلغ عن خطأ أو مشكلة في البلاجن
title: "[BUG] "
labels: ["bug", "triage"]

body:
  - type: markdown
    attributes:
      value: |
        شكراً لإبلاغك عن مشكلة! يرجى ملء هذا النموذج لمساعدتنا في فهم المشكلة.

  - type: input
    id: title
    attributes:
      label: وصف المشكلة (Summary)
      description: "وصف قصير للمشكلة"
      placeholder: "الصور لا تظهر في السلة"
    validations:
      required: true

  - type: textarea
    id: description
    attributes:
      label: التفاصيل الكاملة (Detailed Description)
      description: |
        اشرح المشكلة بالتفصيل:
        - متى تحدث؟
        - ما الذي تتوقعه؟
        - ما الذي يحدث بدلاً من ذلك؟
      placeholder: |
        عند الذهاب إلى صفحة السلة، الأيقونات لا تظهر بجانب الأسعار...
    validations:
      required: true

  - type: textarea
    id: steps
    attributes:
      label: خطوات إعادة إنتاج المشكلة (Steps to Reproduce)
      description: "اكتب الخطوات بالضبط للحصول على نفس المشكلة"
      placeholder: |
        1. فعّل البلاجن
        2. ارفع صورة للعملة
        3. اذهب إلى السلة
        4. لاحظ أن الأيقونة لا تظهر
    validations:
      required: true

  - type: input
    id: expected
    attributes:
      label: السلوك المتوقع (Expected Behavior)
      placeholder: "الأيقونة يجب أن تظهر بجانب كل سعر"
    validations:
      required: true

  - type: textarea
    id: environment
    attributes:
      label: معلومات النظام (System Information)
      description: |
        يرجى تقديم المعلومات التالية:
      placeholder: |
        - إصدار WordPress: 6.0
        - إصدار PHP: 7.4
        - إصدار WooCommerce: 7.0
        - المتصفح: Chrome 120
        - النظام: Windows 10
    validations:
      required: true

  - type: textarea
    id: screenshots
    attributes:
      label: لقطات الشاشة (Screenshots)
      description: "إذا كان ممكناً، أضف لقطات شاشة توضح المشكلة"

  - type: textarea
    id: logs
    attributes:
      label: سجلات الأخطاء (Error Logs)
      description: "هل توجد رسائل خطأ في console أو debug.log؟"
      render: php

  - type: textarea
    id: context
    attributes:
      label: معلومات إضافية (Additional Context)
      description: "أي معلومات أخرى قد تساعد في حل المشكلة"

  - type: checkboxes
    id: checklist
    attributes:
      label: تأكيد (Checklist)
      options:
        - label: "جربت تعطيل البلاجنات الأخرى لاستبعاد التضارب"
          required: false
        - label: "تحقق من السجل للبحث عن رسائل خطأ"
          required: false
        - label: "تأكد من تفعيل WooCommerce"
          required: false
        - label: "جربت في متصفح مختلف"
          required: false
