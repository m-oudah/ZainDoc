<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ in_array(app()->getLocale(), ['ar', 'fa', 'he']) ? 'rtl' : 'ltr' }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&family=Inter:wght@100..900&display=swap" rel="stylesheet">

    <!-- Tailwind & Logic Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- SEO Meta Tags -->
    <meta name="description" content="Secure, intelligent, and bilingual document management for modern enterprises. ZainDoc offers version control, smart collections, and audit trails.">
    
    <style>
        /* Extra custom animations */
        .fade-in-up {
            animation: fadeInUp 0.8s ease-out forwards;
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .font-cairo {
            font-family: 'Cairo', sans-serif !important;
        }
    </style>
</head>
<body class="antialiased font-cairo bg-zain-50/30 dark:bg-zinc-950 selection:bg-zain-500 selection:text-white" x-data="{ scrolled: false }" @scroll.window="scrolled = (window.pageYOffset > 20)">

    <!-- Premium Navigation -->
    <nav :class="scrolled ? 'glassmorphism py-3 shadow-lg' : 'bg-transparent py-5'" class="fixed top-0 left-0 w-full z-50 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-6 flex items-center justify-between">
            <!-- Brand Logo -->
            <a href="#" class="flex items-center space-x-3 rtl:space-x-reverse group">
                <div class="w-10 h-10 bg-zain-600 rounded-xl flex items-center justify-center text-white shadow-lg transition-transform group-hover:rotate-12">
                    <i class="fa-solid fa-folder-tree text-lg"></i>
                </div>
                <span class="text-2xl font-black text-zain-950 dark:text-white tracking-tight">ZainDoc</span>
            </a>

            <!-- Navigation Links (Desktop) -->
            <div class="hidden md:flex items-center space-x-8 rtl:space-x-reverse font-semibold">
                <a href="#features" class="text-zinc-600 dark:text-zinc-400 hover:text-zain-600 transition">
                    {{ app()->getLocale() == 'ar' ? 'المميزات' : 'Features' }}
                </a>
                <a href="#about" class="text-zinc-600 dark:text-zinc-400 hover:text-zain-600 transition">
                    {{ app()->getLocale() == 'ar' ? 'عن المنظومة' : 'About' }}
                </a>
                <a href="#security" class="text-zinc-600 dark:text-zinc-400 hover:text-zain-600 transition">
                    {{ app()->getLocale() == 'ar' ? 'الأمان' : 'Security' }}
                </a>
            </div>

            <!-- Action Buttons & Locale Toggle -->
            <div class="flex items-center space-x-4 rtl:space-x-reverse">
                <!-- Locale Toggle -->
                <a href="{{ route('language.switch', app()->getLocale() == 'ar' ? 'en' : 'ar') }}" 
                   class="flex items-center gap-2 bg-white/50 dark:bg-zinc-800/50 px-3 py-2 rounded-lg text-zinc-950 dark:text-white hover:bg-white dark:hover:bg-zinc-800 border border-white/20 transition shadow-sm font-bold">
                    <i class="fa-solid fa-globe text-zain-600"></i>
                    <span class="text-xs uppercase tracking-widest">{{ app()->getLocale() == 'ar' ? 'English' : 'العربية' }}</span>
                </a>
                
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn-premium btn-primary py-2 px-5 text-sm">
                            {{ app()->getLocale() == 'ar' ? 'لوحة التحكم' : 'Dashboard' }}
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-zinc-600 dark:text-zinc-400 font-semibold hover:text-zain-600 transition px-2">
                            {{ app()->getLocale() == 'ar' ? 'دخول' : 'Log In' }}
                        </a>
                        <a href="#" class="btn-premium btn-primary py-2 px-5 text-sm">
                            {{ app()->getLocale() == 'ar' ? 'ابدأ الآن' : 'Get Started' }}
                        </a>
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="relative pt-32 pb-20 overflow-hidden">
        <!-- Background Gradients -->
        <div class="absolute top-0 right-0 -translate-y-1/2 translate-x-1/2 w-[800px] h-[800px] bg-zain-100/50 dark:bg-zain-900/10 rounded-full blur-3xl -z-10"></div>
        <div class="absolute bottom-0 left-0 translate-y-1/2 -translate-x-1/2 w-[600px] h-[600px] bg-zain-200/50 dark:bg-zain-950/20 rounded-full blur-3xl -z-10"></div>

        <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-2 gap-12 items-center">
            <!-- Hero Content -->
            <div class="fade-in-up">
                <div class="inline-flex items-center space-x-2 rtl:space-x-reverse bg-zain-100 dark:bg-zain-900/40 text-zain-700 dark:text-zain-300 px-4 py-1.5 rounded-full text-xs font-bold mb-6 border border-zain-200 dark:border-zain-800">
                    <span class="flex h-2 w-2 rounded-full bg-zain-600 animate-pulse"></span>
                    <span>Intelligent Document Archiving v1.0</span>
                </div>
                <h1 class="text-5xl lg:text-7xl font-black text-zinc-950 dark:text-white leading-[1.1] mb-6">
                    @if(app()->getLocale() == 'ar')
                        أرشفة ذكية. <br>
                        <span class="text-zain-600">وصول أسرع.</span>
                    @else
                        Archive Smarter. <br>
                        <span class="text-zain-600">Access Faster.</span>
                    @endif
                </h1>
                <p class="text-lg lg:text-xl text-zinc-600 dark:text-zinc-400 mb-10 leading-relaxed max-w-lg">
                    @if(app()->getLocale() == 'ar')
                        استمتع بتجربة أرشفة إبداعية بتوطين عربي عميق، وأمان بمستوى المؤسسات الكبرى، وتصنيف ذكي مدعوم بالذكاء الاصطناعي في منصة واحدة سلسة.
                    @else
                        Experience deep Arabic localization, enterprise-grade security, and AI-powered categorization in one seamless platform designed for the modern workplace.
                    @endif
                </p>
                <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4 rtl:sm:space-x-reverse">
                    <a href="#" class="btn-premium btn-primary text-center justify-center">
                        @if(app()->getLocale() == 'ar')
                            استكشف العرض التجريبي <i class="fa-solid fa-arrow-left me-2"></i>
                        @else
                            Explore Demo <i class="fa-solid fa-arrow-right ms-2 rtl:rotate-180"></i>
                        @endif
                    </a>
                    <a href="#" class="btn-premium btn-secondary text-center justify-center group">
                        Watch Video <i class="fa-solid fa-play ms-2 text-zain-600 group-hover:scale-110 transition-transform"></i>
                    </a>
                </div>
                
                <div class="mt-8 flex items-center space-x-6 rtl:space-x-reverse text-sm font-medium text-zinc-500">
                    <span class="flex items-center"><i class="fa-solid fa-check text-zain-600 me-2"></i> No credit card</span>
                    <span class="flex items-center"><i class="fa-solid fa-check text-zain-600 me-2"></i> Free setup</span>
                </div>
            </div>

            <!-- Hero Illustration -->
            <div class="relative group fade-in-up" style="animation-delay: 0.2s">
                <div class="absolute -inset-4 bg-zain-500/10 rounded-3xl blur-2xl group-hover:bg-zain-500/20 transition duration-500"></div>
                <div class="relative glassmorphism rounded-3xl p-4 shadow-2xl border-white/50">
                    <img src="{{ asset('images/hero.png') }}" alt="ZainDoc Workspace" class="rounded-2xl shadow-lg">
                </div>
            </div>
        </div>
    </header>

    <!-- Partners / Trust Bar -->
    <section class="max-w-7xl mx-auto px-6 py-10 opacity-60">
        <div class="border-y border-zinc-200 dark:border-zinc-800 py-8 text-center flex flex-wrap justify-center items-center gap-12 text-zinc-400">
            <span class="text-xl font-bold font-sans">CORP<span class="text-zain-600">X</span></span>
            <span class="text-xl font-bold font-sans">VALLEY</span>
            <span class="text-xl font-bold font-sans italic underline underline-offset-4">G-GOV</span>
            <span class="text-xl font-bold font-sans tracking-widest">ARCHIV</span>
        </div>
    </section>

    <!-- Key Features -->
    <section id="features" class="max-w-7xl mx-auto px-6 py-24">
        <div class="text-center max-w-2xl mx-auto mb-16">
            <h2 class="text-4xl font-black text-zinc-950 dark:text-white mb-4">Mastering Modern Archiving</h2>
            <p class="text-zinc-600 dark:text-zinc-400 font-medium">Built with the features government institutions and enterprises need most.</p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            <!-- Feature 1 -->
            <div class="p-8 rounded-3xl bg-white dark:bg-zinc-900 shadow-xl shadow-zinc-200/50 dark:shadow-none border border-zinc-100 dark:border-zinc-800 hover:-translate-y-2 transition-all duration-300 cursor-default group">
                <div class="w-14 h-14 bg-zain-100 dark:bg-zain-900/50 text-zain-600 rounded-2xl flex items-center justify-center mb-6 text-2xl group-hover:bg-zain-600 group-hover:text-white transition duration-300">
                    <i class="fa-solid fa-code-fork"></i>
                </div>
                <h3 class="text-xl font-bold text-zinc-950 dark:text-white mb-3">Version Control</h3>
                <p class="text-zinc-600 dark:text-zinc-400 leading-relaxed text-sm">
                    Track every change effortlessly. Restore historical versions and manage document "groups" with absolute precision.
                </p>
            </div>

            <!-- Feature 2 -->
            <div class="p-8 rounded-3xl bg-white dark:bg-zinc-900 shadow-xl shadow-zinc-200/50 dark:shadow-none border border-zinc-100 dark:border-zinc-800 hover:-translate-y-2 transition-all duration-300 cursor-default group">
                <div class="w-14 h-14 bg-amber-100 dark:bg-amber-900/50 text-amber-600 rounded-2xl flex items-center justify-center mb-6 text-2xl group-hover:bg-amber-600 group-hover:text-white transition duration-300">
                    <i class="fa-solid fa-magnifying-glass-chart"></i>
                </div>
                <h3 class="text-xl font-bold text-zinc-950 dark:text-white mb-3">Smart Collections</h3>
                <p class="text-zinc-600 dark:text-zinc-400 leading-relaxed text-sm">
                    Define rules and watch your folders organize themselves. Dynamic metadata filtering keeps everything in its place.
                </p>
            </div>

            <!-- Feature 3 -->
            <div class="p-8 rounded-3xl bg-white dark:bg-zinc-900 shadow-xl shadow-zinc-200/50 dark:shadow-none border border-zinc-100 dark:border-zinc-800 hover:-translate-y-2 transition-all duration-300 cursor-default group">
                <div class="w-14 h-14 bg-emerald-100 dark:bg-emerald-900/50 text-emerald-600 rounded-2xl flex items-center justify-center mb-6 text-2xl group-hover:bg-emerald-600 group-hover:text-white transition duration-300">
                    <i class="fa-solid fa-language"></i>
                </div>
                <h3 class="text-xl font-bold text-zinc-950 dark:text-white mb-3">RTL-First Interface</h3>
                <p class="text-zinc-600 dark:text-zinc-400 leading-relaxed text-sm">
                    No separate stylesheets. A single, high-performance bidirectional codebase using modern CSS logical properties.
                </p>
            </div>
        </div>
    </section>

    <!-- Arabic Excellence (Typography Showcase) -->
    <section class="max-w-7xl mx-auto px-6 py-24 bg-zain-950 rounded-[3rem] text-center overflow-hidden relative font-cairo" lang="ar" dir="rtl">
        <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#fff_1px,transparent_0)] bg-[size:40px_40px] pointer-events-none"></div>
        <div class="relative z-10">
            <span class="text-zain-400 font-bold tracking-widest text-sm uppercase mb-6 block font-sans">Designed for Zain</span>
            <h2 class="text-5xl lg:text-7xl font-bold text-white leading-tight mb-8 font-cairo">
                أرشفة ذكية بلمسة <br> <span class="text-zain-500">عربية أصيلة</span>
            </h2>
            <p class="text-zain-200/70 text-lg max-w-2xl mx-auto mb-10 font-cairo">
                نحن نؤمن بأن الواجهات العربية يجب أن تكون مبهرة بقدر ما هي وظيفية. مع خط Cairo والخصائص المنطقية الحديثة، ZainDoc هو الحل المتكامل.
            </p>
            <a href="#" class="btn-premium bg-white text-zain-950 hover:bg-zain-50 px-10 font-cairo">
                ابدأ رحلتك الآن
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="max-w-7xl mx-auto px-6 py-12 border-t border-zinc-200 dark:border-zinc-800 flex flex-col md:flex-row justify-between items-center text-sm text-zinc-500 gap-6">
        <div class="flex items-center space-x-2 rtl:space-x-reverse opacity-75">
            <div class="w-6 h-6 bg-zain-600 rounded flex items-center justify-center text-white text-[10px]">
                <i class="fa-solid fa-folder-tree"></i>
            </div>
            <span class="font-bold text-zinc-900 dark:text-zinc-300 tracking-tight">ZainDoc</span>
        </div>
        
        <div class="flex items-center space-x-6 rtl:space-x-reverse">
            <a href="#" class="hover:text-zain-600">Privacy Policy</a>
            <a href="#" class="hover:text-zain-600">Terms of Service</a>
            <a href="#" class="hover:text-zain-600">Documentation</a>
        </div>

        <div class="rtl:text-left text-right">
            <span>&copy; 2026 ZainDoc. All Rights Reserved.</span>
        </div>
    </footer>

</body>
</html>
