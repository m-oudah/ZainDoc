<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
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
<body class="h-full bg-zain-950 font-cairo selection:bg-zain-500 selection:text-white overflow-hidden flex items-center justify-center p-6 antialiased">
    <!-- Abstract Background -->
    <div class="absolute inset-0 opacity-20 pointer-events-none overflow-hidden">
        <div class="absolute -top-[10%] -left-[10%] w-[50%] h-[50%] bg-zain-500 rounded-full blur-[150px] animate-pulse"></div>
        <div class="absolute -bottom-[10%] -right-[10%] w-[40%] h-[40%] bg-zain-800 rounded-full blur-[120px]"></div>
    </div>

    <!-- Login Card -->
    <div class="relative w-full max-w-md z-10">
        <div class="glassmorphism p-8 rounded-[2.5rem] shadow-2xl space-y-8">
            <div class="text-center">
                <a href="/" class="inline-flex items-center gap-2 mb-4">
                    <div class="w-10 h-10 bg-zain-500 rounded-xl flex items-center justify-center shadow-lg transform hover:rotate-12 transition-transform duration-300">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <span class="text-2xl font-black text-white font-sans tracking-tight">ZainDoc</span>
                </a>
                <h1 class="text-3xl font-bold text-white mb-2">Welcome Back</h1>
                <p class="text-zain-200/60 font-medium">Please enter your credentials to access your archive.</p>
            </div>

            <form method="POST" action="/login" class="space-y-6">
                @csrf
                
                @if($errors->any())
                    <div class="p-4 rounded-xl bg-red-500/10 border border-red-500/20 text-red-100 text-sm">
                        {{ $errors->first() }}
                    </div>
                @endif

                <div class="space-y-2">
                    <label for="email" class="text-sm font-semibold text-zain-100 ms-1">Email Address</label>
                    <input type="email" id="email" name="email" required autofocus
                        class="w-full px-5 py-4 rounded-2xl bg-white/5 border border-white/10 text-white focus:outline-none focus:ring-2 focus:ring-zain-500/50 focus:border-zain-500 transition-all duration-300 placeholder:text-white/20"
                        placeholder="admin@zaindoc.com">
                </div>

                <div class="space-y-2">
                    <div class="flex items-center justify-between px-1">
                        <label for="password" class="text-sm font-semibold text-zain-100">Password</label>
                        <a href="#" class="text-xs font-bold text-zain-400 hover:text-zain-300 transition-colors">Forgot Password?</a>
                    </div>
                    <input type="password" id="password" name="password" required
                        class="w-full px-5 py-4 rounded-2xl bg-white/5 border border-white/10 text-white focus:outline-none focus:ring-2 focus:ring-zain-500/50 focus:border-zain-500 transition-all duration-300 placeholder:text-white/20"
                        placeholder="••••••••">
                </div>

                <div class="flex items-center gap-3 px-1">
                    <input type="checkbox" id="remember" name="remember" class="w-5 h-5 rounded border-white/10 bg-white/5 text-zain-500 focus:ring-zain-500/50">
                    <label for="remember" class="text-sm font-medium text-zain-200">Remember me for 30 days</label>
                </div>

                <button type="submit" class="w-full btn-premium btn-primary py-4 justify-center text-lg shadow-xl shadow-zain-600/20">
                    Sign In to Dashboard
                </button>
            </form>

            <div class="text-center pt-4">
                <p class="text-zain-200/40 text-sm">Designated secure access point v1.0</p>
            </div>
        </div>
    </div>
</body>
</html>
