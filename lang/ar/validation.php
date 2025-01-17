<?php

declare(strict_types=1);

return [
    'accepted'             => 'يجب قبول هذا الحقل.',
    'accepted_if'          => 'يجب قبول هذا الحقل في حالة :other يساوي :value.',
    'active_url'           => 'لا يُمثّل رابطًا صحيحًا.',
    'after'                => 'يجب أن يكون تاريخًا لاحقًا للتاريخ :date.',
    'after_or_equal'       => 'يجب أن يكون تاريخاً لاحقاً أو مطابقاً للتاريخ :date.',
    'alpha'                => 'يجب أن لا يحتوي سوى على حروف.',
    'alpha_dash'           => 'يجب أن لا يحتوي سوى على حروف، أرقام ومطّات.',
    'alpha_num'            => 'يجب أن يحتوي على حروفٍ وأرقامٍ فقط.',
    'array'                => 'يجب أن يكون مصفوفة.',
    'ascii'                => 'يجب أن يحتوي هذا الحقل فقط على أحرف أبجدية رقمية أحادية البايت ورموز.',
    'before'               => 'يجب أن يكون تاريخًا سابقًا للتاريخ :date.',
    'before_or_equal'      => 'يجب أن يكون تاريخا سابقا أو مطابقا للتاريخ :date.',
    'between'              => [
        'array'   => 'يجب أن يحتوي على عدد من العناصر بين :min و :max.',
        'file'    => 'يجب أن يكون حجم الملف بين :min و :max كيلوبايت.',
        'numeric' => 'يجب أن تكون القيمة بين :min و :max.',
        'string'  => 'يجب أن يكون عدد حروف النّص بين :min و :max.',
    ],
    'boolean'              => 'يجب أن تكون قيمة هذا الحقل إما true أو false .',
    'can'                  => 'هذا الحقل يحتوي على قيمة غير مصرّح بها.',
    'confirmed'            => 'التأكيد غير متطابق.',
    'current_password'     => 'كلمة المرور غير صحيحة.',
    'date'                 => 'هذا ليس تاريخًا صحيحًا.',
    'date_equals'          => 'يجب أن يكون تاريخاً مطابقاً للتاريخ :date.',
    'date_format'          => 'لا يتوافق مع الشكل :format.',
    'decimal'              => 'يجب أن يحتوي هذا الحقل على :decimal منزلة/منازل عشرية.',
    'declined'             => 'يجب رفض هذه القيمة.',
    'declined_if'          => 'يجب رفض هذه القيمة في حالة :other هو :value.',
    'different'            => 'يجب أن تكون القيمة مختلفة عن :other.',
    'digits'               => 'يجب أن يحتوي على :digits رقمًا/أرقام.',
    'digits_between'       => 'يجب أن يكون بين :min و :max رقمًا/أرقام .',
    'dimensions'           => 'الصورة تحتوي على أبعاد غير صالحة.',
    'distinct'             => 'هذا الحقل يحمل قيمة مُكرّرة.',
    'doesnt_end_with'      => 'هذا الحقل يجب ألّا ينتهي بأحد القيم التالية: :values.',
    'doesnt_start_with'    => 'هذا الحقل يجب ألّا يبدأ بأحد القيم التالية: :values.',
    'email'                => 'يجب أن يكون عنوان بريد إلكتروني صحيح البُنية.',
    'ends_with'            => 'يجب أن ينتهي بأحد القيم التالية: :values',
    'enum'                 => 'القيمة المحددة غير صالحة.',
    'exists'               => 'القيمة المحددة غير صالحة.',
    'file'                 => 'المحتوى يجب أن يكون ملفا.',
    'filled'               => 'هذا الحقل إجباري.',
    'gt'                   => [
        'array'   => 'يجب أن يحتوي على أكثر من :value عناصر/عنصر.',
        'file'    => 'يجب أن يكون حجم الملف أكبر من :value كيلوبايت.',
        'numeric' => 'يجب أن تكون القيمة أكبر من :value.',
        'string'  => 'يجب أن يكون طول النّص أكثر من :value حروفٍ/حرفًا.',
    ],
    'gte'                  => [
        'array'   => 'يجب أن يحتوي على الأقل على :value عُنصرًا/عناصر.',
        'file'    => 'يجب أن يكون حجم الملف على الأقل :value كيلوبايت.',
        'numeric' => 'يجب أن تكون القيمة مساوية أو أكبر من :value.',
        'string'  => 'يجب أن يكون طول النص على الأقل :value حروفٍ/حرفًا.',
    ],
    'image'                => 'يجب أن تكون صورةً.',
    'in'                   => 'القيمة المختارة غير صالحة.',
    'in_array'             => 'هذه القيمة غير موجودة في :other.',
    'integer'              => 'يجب أن يكون عددًا صحيحًا.',
    'ip'                   => 'يجب أن يكون عنوان IP صحيحًا.',
    'ipv4'                 => 'يجب أن يكون عنوان IPv4 صحيحًا.',
    'ipv6'                 => 'يجب أن يكون عنوان IPv6 صحيحًا.',
    'json'                 => 'يجب أن يكون نصًا من نوع JSON.',
    'lowercase'            => 'يجب أن يحتوي الحقل على حروف صغيرة.',
    'lt'                   => [
        'array'   => 'يجب أن يحتوي على أقل من :value عناصر/عنصر.',
        'file'    => 'يجب أن يكون حجم الملف أصغر من :value كيلوبايت.',
        'numeric' => 'يجب أن تكون القيمة أصغر من :value.',
        'string'  => 'يجب أن يكون طول النّص أقل من :value حروفٍ/حرفًا.',
    ],
    'lte'                  => [
        'array'   => 'يجب أن لا يحتوي على أكثر من :value عناصر/عنصر.',
        'file'    => 'يجب أن لا يتجاوز حجم الملف :value كيلوبايت.',
        'numeric' => 'يجب أن تكون القيمة مساوية أو أصغر من :value.',
        'string'  => 'يجب أن لا يتجاوز طول النّص :value حروفٍ/حرفًا.',
    ],
    'mac_address'          => 'يجب أن تكون القيمة عنوان MAC صالحاً.',
    'max'                  => [
        'array'   => 'يجب أن لا يحتوي على أكثر من :max عناصر/عنصر.',
        'file'    => 'يجب أن لا يتجاوز حجم الملف :max كيلوبايت.',
        'numeric' => 'يجب أن تكون القيمة مساوية أو أصغر من :max.',
        'string'  => 'يجب أن لا يتجاوز طول النّص :max حروفٍ/حرفًا.',
    ],
    'max_digits'           => 'يجب ألا يحتوي هذا الحقل على أكثر من :max رقم/أرقام.',
    'mimes'                => 'يجب أن يكون ملفًا من نوع : :values.',
    'mimetypes'            => 'يجب أن يكون ملفًا من نوع : :values.',
    'min'                  => [
        'array'   => 'يجب أن يحتوي على الأقل على :min عُنصرًا/عناصر.',
        'file'    => 'يجب أن يكون حجم الملف على الأقل :min كيلوبايت.',
        'numeric' => 'يجب أن تكون القيمة مساوية أو أكبر من :min.',
        'string'  => 'يجب أن يكون طول النص على الأقل :min حروفٍ/حرفًا.',
    ],
    'min_digits'           => 'يجب أن يحتوي هذا الحقل على الأقل :min رقم/أرقام.',
    'missing'              => 'يجب أن يكون هذا الحقل مفقوداً.',
    'missing_if'           => 'يجب أن يكون هذا الحقل مفقوداً عندما :other يساوي :value.',
    'missing_unless'       => 'يجب أن يكون هذا الحقل مفقوداً ما لم يكن :other يساوي :value.',
    'missing_with'         => 'يجب أن يكون هذا الحقل مفقوداً عند توفر :values.',
    'missing_with_all'     => 'يجب أن يكون هذا الحقل مفقوداً عند توفر :values.',
    'multiple_of'          => 'يجب أن تكون القيمة من مضاعفات :value',
    'not_in'               => 'العنصر المحدد غير صالح.',
    'not_regex'            => 'صيغة غير صالحة.',
    'numeric'              => 'يجب أن يكون رقمًا.',
    'password'             => [
        'letters'       => 'يجب أن يحتوي هذا الحقل على حرف واحد على الأقل.',
        'mixed'         => 'يجب أن يحتوي هذا الحقل على حرف كبير وحرف صغير على الأقل.',
        'numbers'       => 'يجب أن يحتوي هذا الحقل على رقمٍ واحدٍ على الأقل.',
        'symbols'       => 'يجب أن يحتوي هذا الحقل على رمزٍ واحدٍ على الأقل.',
        'uncompromised' => 'قيمة هذا الحقل ظهرت في بيانات مُسربة. الرجاء اختيار قيمة مختلفة.',
    ],
    'present'              => 'يجب توفر هذا الحقل.',
    'prohibited'           => 'هذا الحقل محظور.',
    'prohibited_if'        => 'هذا الحقل محظور إذا كان :other هو :value.',
    'prohibited_unless'    => 'هذا الحقل محظور ما لم يكن :other ضمن :values.',
    'prohibits'            => 'هذا الحقل يحظر تواجد الحقل :other.',
    'regex'                => 'الصيغة غير صحيحة.',
    'required'             => 'هذا الحقل مطلوب.',
    'required_array_keys'  => 'يجب أن يحتوي هذا الحقل على مدخلات لـ: :values.',
    'required_if'          => 'هذا الحقل مطلوب في حال ما إذا كان :other يساوي :value.',
    'required_if_accepted' => 'هذا الحقل مطلوب عند قبول الحقل :other.',
    'required_unless'      => 'هذا الحقل مطلوب في حال ما لم يكن :other يساوي :values.',
    'required_with'        => 'هذا الحقل مطلوب إذا توفّر :values.',
    'required_with_all'    => 'هذا الحقل مطلوب إذا توفّر :values.',
    'required_without'     => 'هذا الحقل مطلوب إذا لم يتوفّر :values.',
    'required_without_all' => 'هذا الحقل مطلوب إذا لم يتوفّر :values.',
    'same'                 => 'يجب أن يتطابق هذا الحقل مع :other.',
    'size'                 => [
        'array'   => 'يجب أن يحتوي على :size عنصرٍ/عناصر بالضبط.',
        'file'    => 'يجب أن يكون حجم الملف :size كيلوبايت.',
        'numeric' => 'يجب أن تكون القيمة مساوية لـ :size.',
        'string'  => 'يجب أن يحتوي النص على :size حروفٍ/حرفًا بالضبط.',
    ],
    'starts_with'          => 'يجب أن يبدأ بأحد القيم التالية: :values',
    'string'               => 'يجب أن يكون نصًا.',
    'timezone'             => 'يجب أن يكون نطاقًا زمنيًا صحيحًا.',
    'ulid'                 => 'يجب أن يكون بصيغة ULID سليمة.',
    'unique'               => 'هذه القيمة مُستخدمة من قبل.',
    'uploaded'             => 'فشل في عملية التحميل.',
    'uppercase'            => 'يجب أن يحتوي الحقل على حروف كبيرة.',
    'url'                  => 'الصيغة غير صحيحة.',
    'uuid'                 => 'يجب أن يكون بصيغة UUID سليمة.',
    'attributes'           => [
        'address'                  => 'العنوان',
        'age'                      => 'العمر',
        'amount'                   => 'الكمية',
        'area'                     => 'المنطقة',
        'available'                => 'مُتاح',
        'birthday'                 => 'عيد الميلاد',
        'body'                     => 'المُحتوى',
        'city'                     => 'المدينة',
        'content'                  => 'المُحتوى',
        'country'                  => 'الدولة',
        'created_at'               => 'تاريخ الإنشاء',
        'creator'                  => 'المنشئ',
        'current_password'         => 'كلمة المرور الحالية',
        'date'                     => 'التاريخ',
        'date_of_birth'            => 'تاريخ الميلاد',
        'day'                      => 'اليوم',
        'deleted_at'               => 'تاريخ الحذف',
        'description'              => 'الوصف',
        'district'                 => 'الحي',
        'duration'                 => 'المدة',
        'email'                    => 'البريد الالكتروني',
        'excerpt'                  => 'المُلخص',
        'filter'                   => 'تصفية',
        'first_name'               => 'الاسم الأول',
        'gender'                   => 'النوع',
        'group'                    => 'مجموعة',
        'hour'                     => 'ساعة',
        'image'                    => 'صورة',
        'last_name'                => 'اسم العائلة',
        'lesson'                   => 'درس',
        'line_address_1'           => 'العنوان 1',
        'line_address_2'           => 'العنوان 2',
        'message'                  => 'الرسالة',
        'middle_name'              => 'الاسم الأوسط',
        'minute'                   => 'دقيقة',
        'mobile'                   => 'الجوال',
        'month'                    => 'الشهر',
        'name'                     => 'الاسم',
        'national_code'            => 'الرمز الدولي',
        'number'                   => 'الرقم',
        'password'                 => 'كلمة المرور',
        'password_confirmation'    => 'تأكيد كلمة المرور',
        'phone'                    => 'الهاتف',
        'photo'                    => 'الصورة',
        'postal_code'              => 'الرمز البريدي',
        'price'                    => 'السعر',
        'province'                 => 'المحافظة',
        'recaptcha_response_field' => 'حقل استجابة recaptcha',
        'remember'                 => 'تذكير',
        'restored_at'              => 'تاريخ الاستعادة',
        'result_text_under_image'  => 'نص النتيجة أسفل الصورة',
        'role'                     => 'الصلاحية',
        'second'                   => 'ثانية',
        'sex'                      => 'الجنس',
        'short_text'               => 'نص مختصر',
        'size'                     => 'الحجم',
        'state'                    => 'الولاية',
        'street'                   => 'الشارع',
        'student'                  => 'طالب',
        'subject'                  => 'الموضوع',
        'teacher'                  => 'معلّم',
        'terms'                    => 'الأحكام',
        'test_description'         => 'وصف الاختبار',
        'test_locale'              => 'لغة الاختبار',
        'test_name'                => 'اسم الاختبار',
        'text'                     => 'نص',
        'time'                     => 'الوقت',
        'title'                    => 'اللقب',
        'updated_at'               => 'تاريخ التحديث',
        'username'                 => 'اسم المُستخدم',
        'year'                     => 'السنة',
    ],
];
