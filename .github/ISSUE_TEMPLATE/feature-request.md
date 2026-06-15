name: Feature Request
description: طلب ميزة جديدة أو تحسين
title: "[FEATURE] "
labels: ["enhancement", "feature-request"]

body:
  - type: markdown
    attributes:
      value: |
        لديك فكرة ميزة؟ نحن نحب الاقتراحات! يرجى ملء هذا النموذج.

  - type: input
    id: title
    attributes:
      label: عنوان الميزة (Feature Title)
      placeholder: "دعم النمط الليلي للأيقونات"
    validations:
      required: true

  - type: textarea
    id: description
    attributes:
      label: الوصف التفصيلي (Description)
      description: "اشرح الميزة المطلوبة بالتفصيل"
      placeholder: "أود رؤية دعم dark mode icons بحيث تتغير الأيقونات تلقائياً وفقاً لنمط الموقع..."
    validations:
      required: true

  - type: textarea
    id: use-case
    attributes:
      label: حالة الاستخدام (Use Case)
      description: "ما المشكلة التي ستحل هذه الميزة؟"
      placeholder: "المتاجر ذات النمط الليلي تحتاج أيقونات مختلفة للوضوح..."
    validations:
      required: true

  - type: textarea
    id: solution
    attributes:
      label: الحل المقترح (Proposed Solution)
      description: "كيف تعتقد أن يتم تطبيق هذه الميزة؟"

  - type: textarea
    id: alternatives
    attributes:
      label: بدائل (Alternatives Considered)
      description: "هل فكرت في حلول بديلة؟"

  - type: checkboxes
    id: checklist
    attributes:
      label: تأكيد (Checklist)
      options:
        - label: "تحقق من الميزات الموجودة بالفعل"
          required: false
        - label: "هذه الميزة لا تتضارب مع الميزات الحالية"
          required: false
