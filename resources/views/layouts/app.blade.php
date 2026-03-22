<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&family=Inter:wght@100..900&display=swap" rel="stylesheet">

    <title>@yield('title', 'ZainDoc')</title>

    <!-- Tailwind & Logic Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine.js (Lightweight interactivity) -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        .font-cairo {
            font-family: 'Cairo', sans-serif !important;
        }
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>
<body class="h-full bg-zain-50/20 dark:bg-zinc-950 font-cairo selection:bg-zain-500 selection:text-white flex overflow-hidden antialiased" 
    x-init="window.axios.defaults.headers.common['X-CSRF-TOKEN'] = '{{ csrf_token() }}';"
    x-data="{ 
        scrolled: false, 
        showUploadModal: false,
        dragging: false,
        uploadProgress: 0,
        isUploading: false,
        handleFile(files) {
            if (files.length > 0) {
                this.isUploading = true;
                this.uploadProgress = 0;
                
                let formData = new FormData();
                if (files.length === 1) {
                    formData.append('document', files[0]);
                    axios.post('{{ route("documents.store") }}', formData, {
                        onUploadProgress: (progressEvent) => {
                            this.uploadProgress = Math.round((progressEvent.loaded * 100) / progressEvent.total);
                        }
                    }).then(response => {
                        this.showUploadModal = false;
                        this.isUploading = false;
                        window.location.reload();
                    }).catch(error => {
                        alert('Upload failed: ' + (error.response?.data?.message || 'Unknown error'));
                        this.isUploading = false;
                    });
                } else {
                    for (let i = 0; i < files.length; i++) {
                        formData.append('documents[]', files[i]);
                    }
                    axios.post('{{ route("documents.bulk") }}', formData, {
                        onUploadProgress: (progressEvent) => {
                            this.uploadProgress = Math.round((progressEvent.loaded * 100) / progressEvent.total);
                        }
                    }).then(response => {
                        this.showUploadModal = false;
                        this.isUploading = false;
                        window.location.reload();
                    }).catch(error => {
                        alert('Bulk upload failed: ' + (error.response?.data?.message || 'Unknown error'));
                        this.isUploading = false;
                    });
                }
            }
        }
    }">
    
    <!-- Sidebar -->
    <aside class="w-72 bg-zain-950 text-white flex flex-col items-center py-10 px-6 shrink-0 relative z-20 overflow-hidden">
        <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#fff_1px,transparent_0)] bg-[size:40px_40px] pointer-events-none"></div>
        <div class="relative z-10 w-full">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 mb-12 px-2">
                <div class="w-10 h-10 bg-zain-500 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <span class="text-2xl font-black font-sans tracking-tight">ZainDoc</span>
            </a>

            <nav class="space-y-4">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-4 px-4 py-3 rounded-2xl {{ request()->routeIs('dashboard') ? 'bg-zain-500 text-white font-bold shadow-xl shadow-zain-500/20 transform scale-105' : 'text-zain-200 hover:bg-white/5 hover:text-white transition-all duration-300' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    {{ app()->getLocale() == 'ar' ? 'لوحة التحكم' : 'Dashboard' }}
                </a>
                <div x-data="{ archiveOpen: {{ request()->routeIs('my-archive*') ? 'true' : 'false' }} }">
                    <div class="flex items-center w-full">
                        <a href="{{ route('my-archive') }}" class="flex-1 flex items-center gap-4 px-4 py-3 rounded-tr-2xl rounded-br-2xl rtl:rounded-tr-none rtl:rounded-br-none rtl:rounded-tl-2xl rtl:rounded-bl-2xl {{ request()->routeIs('my-archive*') && !request()->filled('folder_id') ? 'bg-zain-500 text-white font-bold shadow-xl shadow-zain-500/20 transform scale-105' : 'text-zain-200 hover:bg-white/5 hover:text-white transition-all duration-300' }}">
                            <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2z"></path></svg>
                            <span class="truncate">{{ app()->getLocale() == 'ar' ? 'أرشيفي' : 'My Archive' }}</span>
                        </a>
                        <button @click="archiveOpen = !archiveOpen" class="px-2 py-3 text-zain-400 hover:text-white hover:bg-white/5 rounded-tl-2xl rounded-bl-2xl rtl:rounded-tl-none rtl:rounded-bl-none rtl:rounded-tr-2xl rtl:rounded-br-2xl transition-colors">
                            <i class="fa-solid fa-chevron-down text-sm transition-transform duration-300" :class="archiveOpen ? 'rotate-180' : ''"></i>
                        </button>
                    </div>
                    
                    <div x-show="archiveOpen" x-transition class="mt-2 ml-8 rtl:ml-0 rtl:mr-8 space-y-1 border-l-2 border-white/5 rtl:border-l-0 rtl:border-r-2 pl-3 rtl:pl-0 rtl:pr-3" x-cloak>
                        <a href="{{ route('my-archive', ['folder_id' => 'uncategorized']) }}" 
                           class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm transition-all duration-200 {{ request('folder_id') == 'uncategorized' ? 'bg-zain-500/20 text-zain-300 font-bold translate-x-1 rtl:-translate-x-1' : 'text-zain-400 hover:bg-white/5 hover:text-white hover:translate-x-1 rtl:hover:-translate-x-1' }}">
                            <i class="fa-regular fa-folder{{ request('folder_id') == 'uncategorized' ? '-open opacity-100' : ' opacity-70' }}"></i>
                            <span class="truncate">{{ app()->getLocale() == 'ar' ? 'غير مصنف' : 'Uncategorized' }}</span>
                        </a>
                        
                        @foreach(\App\Models\Folder::all() as $folder)
                            <a href="{{ route('my-archive', ['folder_id' => $folder->id]) }}" 
                               class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm transition-all duration-200 {{ request('folder_id') == $folder->id ? 'bg-zain-500/20 text-zain-300 font-bold translate-x-1 rtl:-translate-x-1' : 'text-zain-400 hover:bg-white/5 hover:text-white hover:translate-x-1 rtl:hover:-translate-x-1' }}">
                                <i class="fa-regular fa-folder{{ request('folder_id') == $folder->id ? '-open opacity-100' : ' opacity-70' }}"></i>
                                <span class="truncate">{{ $folder->name }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
                <a href="{{ route('folders.index') }}" class="flex items-center gap-4 px-4 py-3 rounded-2xl {{ request()->routeIs('folders*') ? 'bg-zain-500 text-white font-bold shadow-xl shadow-zain-500/20 transform scale-105' : 'text-zain-200 hover:bg-white/5 hover:text-white transition-all duration-300' }}">
                    <i class="fa-solid fa-folder-tree text-lg w-6 text-center"></i>
                    {{ app()->getLocale() == 'ar' ? 'التصنيفات' : 'Categories' }}
                </a>
                <a href="{{ route('smart-rules') }}" class="flex items-center gap-4 px-4 py-3 rounded-2xl {{ request()->routeIs('smart-rules*') ? 'bg-zain-500 text-white font-bold shadow-xl shadow-zain-500/20 transform scale-105' : 'text-zain-200 hover:bg-white/5 hover:text-white transition-all duration-300' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4a2 2 0 012 2v2m-6 4h4"></path></svg>
                    {{ app()->getLocale() == 'ar' ? 'القواعد الذكية' : 'Smart Rules' }}
                </a>
                <a href="{{ route('users') }}" class="flex items-center gap-4 px-4 py-3 rounded-2xl {{ request()->routeIs('users*') ? 'bg-zain-500 text-white font-bold shadow-xl shadow-zain-500/20 transform scale-105' : 'text-zain-200 hover:bg-white/5 hover:text-white transition-all duration-300' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    {{ app()->getLocale() == 'ar' ? 'المستخدمين والصلاحيات' : 'Users & RBAC' }}
                </a>
            </nav>

            <div class="mt-40 pt-10 border-t border-white/10 space-y-4">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-4 px-4 py-3 rounded-2xl text-red-300 hover:bg-red-500/10 transition-all duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        {{ app()->getLocale() == 'ar' ? 'تسجيل الخروج' : 'Sign Out' }}
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 bg-white dark:bg-zinc-950 flex flex-col relative overflow-y-auto">
        <!-- Top Nav -->
        <header class="h-20 border-b border-zain-100 dark:border-zinc-800 flex items-center justify-between px-10 shrink-0 bg-white/50 backdrop-blur-lg sticky top-0 z-30">
            <h2 class="text-xl font-black text-zain-950 dark:text-white">
                @yield('header_title')
            </h2>
            <div class="flex items-center gap-6">
                <!-- Locale Switcher -->
                <a href="{{ route('language.switch', app()->getLocale() == 'ar' ? 'en' : 'ar') }}" 
                   class="flex items-center gap-2 bg-zain-50 dark:bg-zinc-800 px-3 py-2 rounded-xl text-zain-600 dark:text-zain-400 hover:bg-zain-100 transition-all font-bold group">
                    <i class="fa-solid fa-globe group-hover:rotate-12 transition-transform"></i>
                    <span class="text-xs uppercase tracking-widest">{{ app()->getLocale() == 'ar' ? 'English' : 'العربية' }}</span>
                </a>

                <!-- Profile Info (No Logout here) -->
                <div class="flex items-center gap-3 p-1 rounded-2xl">
                    <div class="hidden text-right lg:block rtl:text-left">
                        <p class="text-sm font-bold text-zain-950 dark:text-white leading-none mb-1">{{ Auth::user()->name }}</p>
                        <p class="text-xs font-semibold text-zain-500 uppercase tracking-widest text-[0.6rem]">
                            {{ app()->getLocale() == 'ar' ? 'مسؤول النظام' : 'Administrator' }}
                        </p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-zain-600 text-white flex items-center justify-center font-bold shadow-lg border-2 border-zain-200/20">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                </div>
            </div>
        </header>

        <!-- Dynamic Content -->
        <div class="p-10 space-y-10">
            @if(session('success'))
                <div class="p-4 rounded-2xl bg-emerald-50 shadow-sm border border-emerald-200 text-emerald-600 font-bold flex items-center justify-between" x-data="{show: true}" x-show="show">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-check-circle"></i> <span>{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="text-emerald-400 hover:text-emerald-700">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            @endif
            
            @if(session('error') || $errors->any())
                <div class="p-4 rounded-2xl bg-red-50 shadow-sm border border-red-200 text-red-600 font-bold flex items-center justify-between" x-data="{show: true}" x-show="show">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-triangle-exclamation"></i> <span>{{ session('error') ?? $errors->first() }}</span>
                    </div>
                    <button @click="show = false" class="text-red-400 hover:text-red-700">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- Upload Modal -->
    <div x-show="showUploadModal" 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 flex items-center justify-center p-6 bg-zain-950/60 backdrop-blur-sm"
        x-cloak>
        
        <div class="glassmorphism w-full max-w-xl rounded-[2.5rem] p-8 space-y-8 relative overflow-hidden" 
            @click.away="!isUploading && (showUploadModal = false)">
            
            <div class="text-center">
                <h3 class="text-3xl font-black text-white mb-2">{{ app()->getLocale() == 'ar' ? 'رفع أرشفة جديدة' : 'Ingest New Archive' }}</h3>
                <p class="text-zain-100/60 font-medium">{{ app()->getLocale() == 'ar' ? 'اسحب الملفات هنا أو تصفح جهازك' : 'Drag and drop or use your device camera.' }}</p>
            </div>

            <!-- Dropzone -->
            <div 
                @dragover.prevent="dragging = true"
                @dragleave.prevent="dragging = false"
                @drop.prevent="dragging = false; handleFile($event.dataTransfer.files)"
                :class="{ 'border-zain-500 bg-zain-500/10 scale-102': dragging, 'border-white/10 bg-white/5': !dragging }"
                class="border-2 border-dashed rounded-[2rem] p-12 text-center transition-all duration-300 cursor-pointer relative group"
                @click="$refs.fileInput.click()">
                
                <input type="file" x-ref="fileInput" class="hidden" @change="handleFile($event.target.files)">
                
                <div class="space-y-4">
                    <div class="w-16 h-16 bg-zain-500 rounded-2xl flex items-center justify-center mx-auto shadow-lg group-hover:rotate-12 transition-transform">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xl font-bold text-white">{{ app()->getLocale() == 'ar' ? 'أسقط الملفات هنا' : 'Drop files here' }}</p>
                        <p class="text-zain-300 font-medium">{{ app()->getLocale() == 'ar' ? 'أو اضغط للتصفح' : 'or click to browse local storage' }}</p>
                    </div>
                </div>
            </div>

            <!-- Mobile Camera Section -->
            <div class="grid grid-cols-1 gap-4">
                <button @click="$refs.cameraInput.click()" 
                    class="flex items-center justify-center gap-4 px-6 py-5 rounded-2xl bg-white/5 border border-white/10 text-white hover:bg-white/10 transition-all font-bold">
                    <svg class="w-6 h-6 text-zain-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    {{ app()->getLocale() == 'ar' ? 'المسح باستخدام كاميرا الجهاز' : 'Scan using Device Camera' }}
                </button>
                <input type="file" x-ref="cameraInput" class="hidden" accept="image/*" capture="environment" @change="handleFile($event.target.files)">
            </div>

            <!-- Progress Bar -->
            <div x-show="isUploading" class="space-y-2">
                <div class="flex justify-between text-sm font-bold text-white uppercase tracking-wider">
                    <span>{{ app()->getLocale() == 'ar' ? 'جاري التحميل...' : 'Inbound Transmission...' }}</span>
                    <span x-text="uploadProgress + '%'"></span>
                </div>
                <div class="h-3 w-full bg-white/5 rounded-full overflow-hidden border border-white/10">
                    <div class="h-full bg-gradient-to-r from-zain-400 to-zain-600 transition-all duration-300 shadow-[0_0_15px_rgba(14,165,233,0.5)]" 
                        :style="{ width: uploadProgress + '%' }"></div>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4">
                <button @click="showUploadModal = false" :disabled="isUploading"
                    class="px-6 py-3 rounded-xl text-zain-200 font-bold hover:text-white disabled:opacity-50">
                    {{ app()->getLocale() == 'ar' ? 'إلغاء' : 'Cancel' }}
                </button>
            </div>
        </div>
    </div>
</body>
</html>
