<footer class="app-footer">
    <div class="container">

        <div class="row g-4">

            <div class="col-lg-5">
                <a href="{{ route('home') }}" class="footer-brand">
                    <i class="bi bi-heart-fill" aria-hidden="true"></i>
                    منصة التطوع
                </a>

                <p class="footer-description">
                    منصة تربط المتطوعين بالفرص المناسبة، وتساعد المنظمات
                    على الوصول إلى متطوعين للمساهمة في خدمة المجتمع.
                </p>
            </div>

            <div class="col-6 col-lg-2">
                <h5>روابط سريعة</h5>

                <ul class="footer-links">
                    <li>
                        <a href="{{ route('home') }}">الرئيسية</a>
                    </li>
                    <li>
                        <a href="{{ route('jobs.index') }}">فرص التطوع</a>
                    </li>
                    <li>
                        <a href="{{ route('organizations.index') }}">المنظمات</a>
                    </li>
                </ul>
            </div>

            <div class="col-6 col-lg-2">
                <h5>الحساب</h5>

                <ul class="footer-links">
                    <li>
                        <a href="{{ route('login') }}">تسجيل الدخول</a>
                    </li>
                    <li>
                        <a href="{{ route('register') }}">إنشاء حساب</a>
                    </li>
                    <li>
                        <a href="{{ route('organization.register') }}">تسجيل منظمة</a>
                    </li>
                </ul>
            </div>

            <div class="col-lg-3">
                <h5>تواصل معنا</h5>

                <ul class="footer-links">
                    <li>
                        <a href="mailto:{{ config('mail.from.address', 'hello@example.com') }}">
                            <i class="bi bi-envelope me-2" aria-hidden="true"></i>
                            راسلنا عبر البريد الإلكتروني
                        </a>
                    </li>
                </ul>
            </div>

        </div>

        <hr class="footer-divider">

        <div class="footer-bottom">
            <p>© {{ date('Y') }} منصة التطوع. جميع الحقوق محفوظة.</p>
        </div>

    </div>
</footer>