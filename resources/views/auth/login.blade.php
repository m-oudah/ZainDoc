<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&family=Inter:wght@100..900&display=swap" rel="stylesheet">

    <title>Login – ZainDoc</title>

    <!-- Tailwind & Logic Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        .font-cairo {
            font-family: 'Cairo', sans-serif !important;
        }
    </style>
</head>
<body class="h-full bg-gray-50 font-cairo selection:bg-blue-500 selection:text-white overflow-hidden flex items-center justify-center p-6 antialiased">
    <!-- Abstract Background -->
    <div class="absolute inset-0 opacity-40 pointer-events-none overflow-hidden">
        <div class="absolute -top-[10%] -left-[10%] w-[50%] h-[50%] bg-blue-100 rounded-full blur-[100px] animate-pulse"></div>
        <div class="absolute -bottom-[10%] -right-[10%] w-[40%] h-[40%] bg-gray-200 rounded-full blur-[100px]"></div>
    </div>

    <!-- Login Card -->
    <div class="relative w-full max-w-md z-10">
        <div class="bg-white p-8 rounded-md shadow-xl border border-gray-100 space-y-8">
            <div class="text-center">
                <a href="/" class="inline-flex items-center gap-2 mb-4">
                    <div class="w-10 h-10 bg-blue-600 rounded flex items-center justify-center shadow-md transform hover:rotate-12 transition-transform duration-300">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <span class="text-2xl font-black text-gray-900 tracking-tight">ZainDoc</span>
                </a>
                <p class="text-gray-500 text-sm font-medium">
                    {{ app()->getLocale() == 'ar' ? 'يرجى إدخال بيانات الاعتماد للوصول إلى أرشيفك.' : 'Please enter your credentials to access your archive.' }}
                </p>
            </div>

            <form method="POST" action="/login" class="space-y-6 text-sm">
                @csrf
                
                @if($errors->any())
                    <div class="p-3 rounded-md bg-red-50 border border-red-200 text-red-600 font-semibold mb-4 text-xs">
                        {{ $errors->first() }}
                    </div>
                @endif

                <div class="flex items-center gap-4 justify-between">
                    <label for="email" class="font-semibold text-gray-700 whitespace-nowrap">{{ app()->getLocale() == 'ar' ? 'البريد الإلكتروني' : 'Email Address' }}</label>
                    <input type="email" id="email" name="email" required autofocus tabindex="1"
                        class="w-64 px-3 py-2 rounded-md bg-white border border-gray-300 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-colors placeholder:text-gray-400"
                        placeholder="admin@zaindoc.com">
                </div>

                <div class="space-y-1">
                    <div class="flex items-center gap-4 justify-between">
                        <label for="password" class="font-semibold text-gray-700 whitespace-nowrap">{{ app()->getLocale() == 'ar' ? 'كلمة المرور' : 'Password' }}</label>
                        <input type="password" id="password" name="password" required tabindex="2"
                            class="w-64 px-3 py-2 rounded-md bg-white border border-gray-300 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-colors placeholder:text-gray-400"
                            placeholder="••••••••">
                    </div>
                    <div class="flex justify-end pt-1">
                        <a href="#" tabindex="5" class="text-xs font-bold text-blue-600 hover:text-blue-700 transition-colors">{{ app()->getLocale() == 'ar' ? 'نسيت كلمة المرور؟' : 'Forgot Password?' }}</a>
                    </div>
                </div>

                <div class="flex items-center gap-2 px-1">
                    <input type="checkbox" id="remember" name="remember" tabindex="4" class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <label for="remember" class="font-medium text-gray-600">{{ app()->getLocale() == 'ar' ? 'تذكرني لمدة 30 يوماً' : 'Remember me for 30 days' }}</label>
                </div>

                <button type="submit" tabindex="3" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 rounded-md shadow-sm transition-colors flex justify-center items-center">
                    {{ app()->getLocale() == 'ar' ? 'تسجيل الدخول' : 'Sign In' }}
                </button>
            </form>


        </div>
    </div>
</body>
</html>
