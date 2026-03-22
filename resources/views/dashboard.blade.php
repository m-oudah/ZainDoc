<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&family=Inter:wght@100..900&display=swap" rel="stylesheet">

    <title>Dashboard – ZainDoc</title>

    <!-- Tailwind & Logic Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        .font-cairo {
            font-family: 'Cairo', sans-serif !important;
        }
    </style>
</head>
<body class="h-full bg-zain-50/20 dark:bg-zinc-950 font-cairo selection:bg-zain-500 selection:text-white flex overflow-hidden antialiased">
    <!-- Sidebar -->
    <aside class="w-72 bg-zain-950 text-white flex flex-col items-center py-10 px-6 shrink-0 relative z-20 overflow-hidden">
        <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#fff_1px,transparent_0)] bg-[size:40px_40px] pointer-events-none"></div>
        <div class="relative z-10 w-full">
            <a href="/dashboard" class="flex items-center gap-3 mb-12 px-2">
                <div class="w-10 h-10 bg-zain-500 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <span class="text-2xl font-black font-sans tracking-tight">ZainDoc</span>
            </a>

            <nav class="space-y-4">
                <a href="#" class="flex items-center gap-4 px-4 py-3 rounded-2xl bg-zain-500 text-white font-bold shadow-xl shadow-zain-500/20 transition-all duration-300 transform scale-105">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    Dashboard
                </a>
                <a href="#" class="flex items-center gap-4 px-4 py-3 rounded-2xl text-zain-200 hover:bg-white/5 hover:text-white transition-all duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2z"></path></svg>
                    My Archive
                </a>
                <a href="#" class="flex items-center gap-4 px-4 py-3 rounded-2xl text-zain-200 hover:bg-white/5 hover:text-white transition-all duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4a2 2 0 012 2v2m-6 4h4"></path></svg>
                    Smart Rules
                </a>
                <a href="#" class="flex items-center gap-4 px-4 py-3 rounded-2xl text-zain-200 hover:bg-white/5 hover:text-white transition-all duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    Users & RBAC
                </a>
            </nav>

            <div class="mt-40 pt-10 border-t border-white/10 space-y-4">
                <form method="POST" action="/logout">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-4 px-4 py-3 rounded-2xl text-red-300 hover:bg-red-500/10 transition-all duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        Sign Out
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 bg-white dark:bg-zinc-950 flex flex-col relative overflow-y-auto">
        <!-- Top Nav -->
        <header class="h-20 border-b border-zain-100 dark:border-zinc-800 flex items-center justify-between px-10 shrink-0 bg-white/50 backdrop-blur-lg sticky top-0 z-30">
            <h2 class="text-xl font-black text-zain-950 dark:text-white">Admin Dashboard Overview</h2>
            <div class="flex items-center gap-6">
                <!-- Notifications, etc -->
                <div class="flex items-center gap-3">
                    <div class="hidden text-right lg:block">
                        <p class="text-sm font-bold text-zain-950 dark:text-white leading-none mb-1">{{ Auth::user()->name }}</p>
                        <p class="text-xs font-semibold text-zain-500 uppercase tracking-widest">Administrator</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-zain-100 flex items-center justify-center text-zain-600 font-bold overflow-hidden border-2 border-zain-200">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                </div>
            </div>
        </header>

        <!-- Dynamic Content -->
        <div class="p-10 space-y-10">
            <!-- Welcome Bar -->
            <div class="flex flex-col md:flex-row items-center justify-between gap-6 p-8 rounded-[2rem] bg-gradient-to-r from-zain-600 to-zain-800 text-white shadow-2xl relative overflow-hidden">
                <div class="absolute inset-0 opacity-20 bg-[radial-gradient(#fff_1px,transparent_0)] bg-[size:20px_20px]"></div>
                <div class="relative z-10">
                    <h2 class="text-3xl font-black mb-2 font-cairo">Welcome, {{ Auth::user()->name }} 👋</h2>
                    <p class="text-zain-100 font-medium">You have 0 pending archiving tasks today.</p>
                </div>
                <div class="relative z-10 flex gap-4">
                    <button class="px-6 py-3 rounded-xl bg-white text-zain-600 font-black shadow-lg hover:-translate-y-1 transition-transform">
                        Bulk Upload
                    </button>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach([['Docs', '0', 'folder'], ['Folders', '0', 'archive'], ['Shares', '0', 'share'], ['Storage', '0 MB', 'p-icon']] as $stat)
                <div class="p-6 rounded-3xl border border-zain-100 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-sm hover:shadow-xl hover:border-zain-200 transition-all duration-300 transform hover:-translate-y-1 group">
                    <div class="w-12 h-12 rounded-xl bg-zain-50 text-zain-500 flex items-center justify-center mb-4 group-hover:bg-zain-500 group-hover:text-white transition-colors duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2z"></path></svg>
                    </div>
                    <p class="text-zain-500/60 font-bold uppercase tracking-widest text-[0.65rem] mb-1">{{ $stat[0] }}</p>
                    <h4 class="text-2xl font-black text-zain-950 dark:text-white">{{ $stat[1] }}</h4>
                </div>
                @endforeach
            </div>

            <!-- Recent Documents Placeholder -->
            <div class="rounded-3xl border border-zain-100 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-8 space-y-6">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-black text-zain-950 dark:text-white">Recent Documents</h3>
                    <button class="text-zain-500 font-bold hover:underline">View All</button>
                </div>
                <div class="flex flex-col items-center justify-center py-20 text-center space-y-4">
                    <div class="w-20 h-20 bg-zain-50 rounded-full flex items-center justify-center text-zain-200">
                         <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path></svg>
                    </div>
                    <h4 class="text-lg font-bold text-zain-950 dark:text-white font-cairo">Your archive is empty</h4>
                    <p class="text-zain-500/60 max-w-xs mx-auto">Upload your first document to start intelligently managing your data.</p>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
