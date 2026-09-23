@extends('layouts.app')

@section('title', 'منصة التطوع')

@section('content')

{{-- =========================================================
     HERO
========================================================= --}}

<section class="hero-section">

    <div class="container">

        <div class="row align-items-center g-5">

            <div class="col-lg-6">

                <span class="hero-badge">
                    <i class="bi bi-stars me-1"></i>
                    اصنع أثرًا حقيقيًا
                </span>

                <h1 class="hero-title mt-3">
                    تطوعك اليوم
                    <span>يصنع فرقًا غدًا</span>
                </h1>

                <p class="hero-description">
                    منصة تجمع المتطوعين مع فرص تطوعية ذات أثر،
                    وتساعد المنظمات على الوصول إلى متطوعين
                    مناسبين للمساهمة في خدمة المجتمع.
                </p>

                <div class="d-flex flex-wrap gap-3 mt-4">

<a href="{{ route('jobs.index') }}"
                   class="btn btn-primary btn-lg px-4">
                        <i class="bi bi-search me-2"></i>
                        اكتشف فرص التطوع
                    </a>

                    <a href="{{ route('register') }}"
                       class="btn btn-outline-primary btn-lg px-4">
                        <i class="bi bi-person-plus me-2"></i>
                        انضم كمتطوع
                    </a>

                </div>

                <div class="hero-features mt-4">

                    <div>
                        <i class="bi bi-check-circle-fill"></i>
                        فرص تطوعية متنوعة
                    </div>

                    <div>
                        <i class="bi bi-check-circle-fill"></i>
                        منظمات موثوقة
                    </div>

                    <div>
                        <i class="bi bi-check-circle-fill"></i>
                        تجربة سهلة وبسيطة
                    </div>

                </div>

            </div>


            <div class="col-lg-6">

                <div class="hero-visual">

                    <div class="hero-main-card">

                        <div class="hero-icon">
                            <i class="bi bi-people-fill"></i>
                        </div>

                        <h3>
                            معًا نصنع الأثر
                        </h3>

                        <p>
                            كل ساعة تطوع يمكن أن تحدث فرقًا في حياة شخص آخر.
                        </p>

                        <div class="hero-mini-stats">

                            <div>
                                <strong>1,250+</strong>
                                <span>متطوع</span>
                            </div>

                            <div>
                                <strong>85+</strong>
                                <span>منظمة</span>
                            </div>

                            <div>
                                <strong>320+</strong>
                                <span>فرصة</span>
                            </div>

                        </div>

                    </div>

                    <div class="floating-card floating-card-one">
                        <i class="bi bi-heart-fill"></i>
                        <span>ساهم في مجتمعك</span>
                    </div>

                    <div class="floating-card floating-card-two">
                        <i class="bi bi-hand-thumbs-up-fill"></i>
                        <span>كن جزءًا من التغيير</span>
                    </div>

                </div>

            </div>

        </div>

    </div>

</section>

<section class="statistics-section">

    <div class="container">

        <div class="row g-3">

            <div class="col-6 col-lg-3">
                <div class="stat-card">

                    <div class="stat-icon">
                        <i class="bi bi-people-fill"></i>
                    </div>

                    <h3>1,250+</h3>
                    <p>متطوع</p>

                </div>
            </div>

            <div class="col-6 col-lg-3">
                <div class="stat-card">

                    <div class="stat-icon">
                        <i class="bi bi-building"></i>
                    </div>

                    <h3>85+</h3>
                    <p>منظمة</p>

                </div>
            </div>

            <div class="col-6 col-lg-3">
                <div class="stat-card">

                    <div class="stat-icon">
                        <i class="bi bi-briefcase-fill"></i>
                    </div>

                    <h3>320+</h3>
                    <p>فرصة تطوعية</p>

                </div>
            </div>

            <div class="col-6 col-lg-3">
                <div class="stat-card">

                    <div class="stat-icon">
                        <i class="bi bi-send-fill"></i>
                    </div>

                    <h3>2,400+</h3>
                    <p>طلب تطوع</p>

                </div>
            </div>

        </div>

</div>

</section>

<section class="section-padding" id="opportunities">

    <div class="container">

        <div class="section-heading">

            <span>اكتشف الفرص</span>

            <h2>
                أحدث فرص التطوع
            </h2>

            <p>
                ابحث عن فرصة تناسب مهاراتك واهتماماتك وساهم في خدمة مجتمعك.
            </p>

        </div>


        <div class="row g-4">

            {{-- Opportunity 1 --}}
            <div class="col-md-6 col-lg-4">

                <div class="opportunity-card">

                    <div class="opportunity-top">
                        <span class="badge-soft">
                            تعليم
                        </span>

                        <i class="bi bi-book"></i>
                    </div>

                    <h4>
                        دعم الطلاب في التعليم
                    </h4>

                    <p>
                        المساعدة في تقديم الدعم التعليمي للطلاب
                        وتنظيم الأنشطة التعليمية.
                    </p>

                    <div class="opportunity-info">
                        <span>
                            <i class="bi bi-building"></i>
                            منظمة الأمل
                        </span>

                        <span>
                            <i class="bi bi-geo-alt"></i>
                            الخرطوم
                        </span>
                    </div>

                    <div class="opportunity-info">
                        <span>
                            <i class="bi bi-calendar3"></i>
                            15 سبتمبر 2026
                        </span>

                        <span>
                            <i class="bi bi-clock"></i>
                            10 سبتمبر
                        </span>
                    </div>

<a href="{{ route('jobs.index') }}"
                   class="btn btn-outline-primary w-100 mt-3">
                        عرض التفاصيل
                    </a>

                </div>

            </div>


            {{-- Opportunity 2 --}}
            <div class="col-md-6 col-lg-4">

                <div class="opportunity-card">

                    <div class="opportunity-top">
                        <span class="badge-soft">
                            مجتمع
                        </span>

                        <i class="bi bi-people"></i>
                    </div>

                    <h4>
                        حملة خدمة المجتمع
                    </h4>

                    <p>
                        شارك في حملة مجتمعية تهدف إلى تحسين
                        البيئة والمرافق العامة.
                    </p>

                    <div class="opportunity-info">
                        <span>
                            <i class="bi bi-building"></i>
                            مبادرة مجتمعنا
                        </span>

                        <span>
                            <i class="bi bi-geo-alt"></i>
                            كسلا
                        </span>
                    </div>

                    <div class="opportunity-info">
                        <span>
                            <i class="bi bi-calendar3"></i>
                            20 سبتمبر 2026
                        </span>

                        <span>
                            <i class="bi bi-clock"></i>
                            17 سبتمبر
                        </span>
                    </div>

<a href="{{ route('jobs.index') }}"
                   class="btn btn-outline-primary w-100 mt-3">
                        عرض التفاصيل
                    </a>

                </div>

            </div>


            {{-- Opportunity 3 --}}
            <div class="col-md-6 col-lg-4">

                <div class="opportunity-card">

                    <div class="opportunity-top">
                        <span class="badge-soft">
                            صحة
                        </span>

                        <i class="bi bi-heart-pulse"></i>
                    </div>

                    <h4>
                        التوعية الصحية
                    </h4>

                    <p>
                        المساعدة في تنظيم أنشطة توعوية حول
                        الصحة والوقاية داخل المجتمع.
                    </p>

                    <div class="opportunity-info">
                        <span>
                            <i class="bi bi-building"></i>
                            جمعية الصحة
                        </span>

                        <span>
                            <i class="bi bi-geo-alt"></i>
                            أم درمان
                        </span>
                    </div>

                    <div class="opportunity-info">
                        <span>
                            <i class="bi bi-calendar3"></i>
                            25 سبتمبر 2026
                        </span>

                        <span>
                            <i class="bi bi-clock"></i>
                            22 سبتمبر
                        </span>
                    </div>

<a href="{{ route('jobs.index') }}"
                   class="btn btn-outline-primary w-100 mt-3">
                        عرض التفاصيل
                    </a>

                </div>

            </div>

        </div>


        <div class="text-center mt-5">

<a href="{{ route('jobs.index') }}"
                   class="btn btn-primary px-4">
                    عرض جميع الفرص
                    <i class="bi bi-arrow-left ms-2"></i>
                </a>

        </div>

    </div>

</section>

<section class="section-padding bg-light" id="organizations">

    <div class="container">

        <div class="section-heading">

            <span>شركاؤنا</span>

            <h2>
                منظمات تعمل من أجل المجتمع
            </h2>

            <p>
                تعرف على بعض المنظمات والمبادرات التي توفر فرصًا تطوعية.
            </p>

        </div>


        <div class="row g-4">

            {{-- Organization 1 --}}
            <div class="col-md-6 col-lg-4">

                <div class="organization-card">

                    <div class="organization-logo">
                        <i class="bi bi-building"></i>
                    </div>

                    <h4>
                        منظمة الأمل
                    </h4>

                    <p>
                        منظمة تعمل على دعم التعليم وتمكين الشباب
                        وخدمة المجتمع.
                    </p>

                    <div class="organization-location">
                        <i class="bi bi-geo-alt"></i>
                        الخرطوم، السودان
                    </div>

<a href="{{ route('organizations.index') }}"
                   class="btn btn-outline-primary w-100 mt-3">
                        عرض المنظمة
                    </a>

                </div>

            </div>


            {{-- Organization 2 --}}
            <div class="col-md-6 col-lg-4">

                <div class="organization-card">

                    <div class="organization-logo">
                        <i class="bi bi-globe2"></i>
                    </div>

                    <h4>
                        مبادرة مجتمعنا
                    </h4>

                    <p>
                        مبادرة مجتمعية تهدف إلى تشجيع المشاركة
                        والعمل التطوعي.
                    </p>

                    <div class="organization-location">
                        <i class="bi bi-geo-alt"></i>
                        كسلا، السودان
                    </div>

<a href="{{ route('organizations.index') }}"
                   class="btn btn-outline-primary w-100 mt-3">
                        عرض المنظمة
                    </a>

                </div>

            </div>


            {{-- Organization 3 --}}
            <div class="col-md-6 col-lg-4">

                <div class="organization-card">

                    <div class="organization-logo">
                        <i class="bi bi-heart-pulse"></i>
                    </div>

                    <h4>
                        جمعية الصحة المجتمعية
                    </h4>

                    <p>
                        جمعية تهتم بالتوعية الصحية والمبادرات
                        الصحية داخل المجتمع.
                    </p>

                    <div class="organization-location">
                        <i class="bi bi-geo-alt"></i>
                        أم درمان، السودان
                    </div>

<a href="{{ route('organizations.index') }}"
                   class="btn btn-outline-primary w-100 mt-3">
                        عرض المنظمة
                    </a>

                </div>

            </div>

        </div>

    </div>

</section>

<section class="section-padding" id="how-it-works">

    <div class="container">

        <div class="section-heading">

            <span>طريقة الاستخدام</span>

            <h2>
                كيف تعمل منصة التطوع؟
            </h2>

            <p>
                خطوات بسيطة للبدء في رحلة التطوع.
            </p>

        </div>


        <div class="row g-4">

            <div class="col-md-4">

                <div class="step-card">

                    <div class="step-number">
                        1
                    </div>

                    <div class="step-icon">
                        <i class="bi bi-person-plus"></i>
                    </div>

                    <h4>
                        أنشئ ملفك الشخصي
                    </h4>

                    <p>
                        سجل حسابك وأضف معلوماتك ومهاراتك
                        لتجد الفرص المناسبة لك.
                    </p>

                </div>

            </div>


            <div class="col-md-4">

                <div class="step-card">

                    <div class="step-number">
                        2
                    </div>

                    <div class="step-icon">
                        <i class="bi bi-search"></i>
                    </div>

                    <h4>
                        ابحث عن فرصة
                    </h4>

                    <p>
                        استعرض فرص التطوع وابحث عن الفرصة
                        التي تناسب مهاراتك واهتماماتك.
                    </p>

                </div>

            </div>


            <div class="col-md-4">

                <div class="step-card">

                    <div class="step-number">
                        3
                    </div>

                    <div class="step-icon">
                        <i class="bi bi-hand-thumbs-up"></i>
                    </div>

                    <h4>
                        قدم واصنع أثرًا
                    </h4>

                    <p>
                        قدم على الفرصة المناسبة وابدأ
                        بالمساهمة في خدمة المجتمع.
                    </p>

                </div>

            </div>

        </div>


        <div class="organization-process mt-5">

            <div>
                <i class="bi bi-building"></i>

                <strong>
                    للمنظمات
                </strong>
            </div>

            <span>
                سجل منظمتك
            </span>

            <i class="bi bi-arrow-left"></i>

            <span>
                انشر فرص التطوع
            </span>

            <i class="bi bi-arrow-left"></i>

            <span>
                استقبل المتطوعين
            </span>

        </div>

    </div>

</section>


{{-- =========================================================
     CTA
========================================================= --}}

<section class="cta-section">

    <div class="container">

        <div class="cta-content">

            <div class="cta-icon">
                <i class="bi bi-heart-fill"></i>
            </div>

            <h2>
                مستعد لصنع فرق؟
            </h2>

            <p>
                انضم إلى مجتمع المتطوعين وابدأ اليوم
                في تقديم مساهمتك للمجتمع.
            </p>

            <div class="d-flex justify-content-center flex-wrap gap-3">

                <a href="{{ route('register') }}"
                   class="btn btn-light btn-lg px-4">
                    <i class="bi bi-person-plus me-2"></i>
                    انضم كمتطوع
                </a>

                <a href="{{ route('organization.register') }}"
                   class="btn btn-outline-light btn-lg px-4">
                    <i class="bi bi-building me-2"></i>
                    تسجيل منظمة
                </a>

            </div>

        </div>

    </div>

</section>

@endsection
