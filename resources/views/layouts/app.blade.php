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

    <style>
        .font-cairo {
            font-family: 'Cairo', sans-serif !important;
        }
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>
<body class="h-full bg-gray-50 font-cairo selection:bg-blue-500 selection:text-white flex overflow-hidden antialiased text-gray-800 text-sm" 
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
    <aside class="w-64 bg-[#1B2A4A] border-l border-r border-[#1B2A4A]/80 flex flex-col items-stretch py-8 px-4 shrink-0 relative z-20 overflow-y-auto">
        <div class="relative z-10 w-full">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 mb-10 px-2 group">
                <div class="w-8 h-8 bg-blue-600 rounded flex items-center justify-center shadow-sm group-hover:bg-blue-700 transition-colors">
                    <i class="fa-solid fa-folder-tree text-white text-sm"></i>
                </div>
                <span class="text-xl font-bold font-sans tracking-tight text-white">ZainDoc</span>
            </a>

            <nav class="space-y-1">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-md {{ request()->routeIs('dashboard') ? 'bg-blue-500 text-white font-semibold shadow-sm' : 'text-blue-100 hover:bg-white/10 hover:text-white transition-colors' }}">
                    <i class="fa-solid fa-chart-pie w-5 text-center"></i>
                    <span class="text-sm">{{ app()->getLocale() == 'ar' ? 'لوحة التحكم' : 'Dashboard' }}</span>
                </a>
                <div x-data="{ archiveOpen: {{ request()->routeIs('my-archive*') ? 'true' : 'false' }} }">
                    <div class="flex items-center w-full">
                        <a href="{{ route('my-archive') }}" class="flex-1 flex items-center gap-3 px-3 py-2.5 rounded-md {{ request()->routeIs('my-archive*') && !request()->filled('folder_id') ? 'bg-blue-500 text-white font-semibold shadow-sm' : 'text-blue-100 hover:bg-white/10 hover:text-white transition-colors' }}">
                            <i class="fa-solid fa-box-archive w-5 text-center"></i>
                            <span class="truncate text-sm">{{ app()->getLocale() == 'ar' ? 'أرشيفي' : 'My Archive' }}</span>
                        </a>
                    </div>
                    
                    <div x-show="archiveOpen" x-transition class="mt-1 ml-6 rtl:ml-0 rtl:mr-6 space-y-1 border-l border-white/20 rtl:border-l-0 rtl:border-r pl-2 rtl:pl-0 rtl:pr-2" x-cloak>
                        <a href="{{ route('my-archive', ['folder_id' => 'uncategorized']) }}" 
                           class="flex items-center gap-2 px-3 py-2 rounded-md transition-colors {{ request('folder_id') == 'uncategorized' ? 'bg-blue-500 text-white font-semibold' : 'text-blue-200 hover:bg-white/10 hover:text-white' }}">
                            <i class="fa-regular fa-folder text-xs"></i>
                            <span class="truncate text-xs">{{ app()->getLocale() == 'ar' ? 'غير مصنف' : 'Uncategorized' }}</span>
                        </a>
                        
                        @foreach(\App\Models\Folder::all() as $folder)
                            <a href="{{ route('my-archive', ['folder_id' => $folder->id]) }}" 
                               class="flex items-center gap-2 px-3 py-2 rounded-md transition-colors {{ request('folder_id') == $folder->id ? 'bg-blue-500 text-white font-semibold' : 'text-blue-200 hover:bg-white/10 hover:text-white' }}">
                                <i class="fa-regular fa-folder text-xs"></i>
                                <span class="truncate text-xs">{{ $folder->name }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
                <a href="{{ route('folders.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-md {{ request()->routeIs('folders*') ? 'bg-blue-500 text-white font-semibold shadow-sm' : 'text-blue-100 hover:bg-white/10 hover:text-white transition-colors' }}">
                    <i class="fa-solid fa-folder-tree w-5 text-center"></i>
                    <span class="text-sm">{{ app()->getLocale() == 'ar' ? 'التصنيفات' : 'Categories' }}</span>
                </a>
                <a href="{{ route('smart-rules') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-md {{ request()->routeIs('smart-rules*') ? 'bg-blue-500 text-white font-semibold shadow-sm' : 'text-blue-100 hover:bg-white/10 hover:text-white transition-colors' }}">
                    <i class="fa-solid fa-robot w-5 text-center"></i>
                    <span class="text-sm">{{ app()->getLocale() == 'ar' ? 'القواعد الذكية' : 'Smart Rules' }}</span>
                </a>
                <a href="{{ route('users') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-md {{ request()->routeIs('users*') ? 'bg-blue-500 text-white font-semibold shadow-sm' : 'text-blue-100 hover:bg-white/10 hover:text-white transition-colors' }}">
                    <i class="fa-solid fa-users-gear w-5 text-center"></i>
                    <span class="text-sm">{{ app()->getLocale() == 'ar' ? 'المستخدمين والصلاحيات' : 'Users & RBAC' }}</span>
                </a>
            </nav>

            <div class="mt-auto pt-8 space-y-2">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-md text-red-300 hover:bg-red-500/20 hover:text-red-200 transition-colors text-sm font-bold">
                        <i class="fa-solid fa-right-from-bracket w-5 text-center"></i>
                        {{ app()->getLocale() == 'ar' ? 'تسجيل الخروج' : 'Sign Out' }}
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col relative overflow-y-auto">
        <!-- Top Nav -->
        <header class="h-16 border-b border-gray-200 flex items-center justify-between px-8 shrink-0 bg-white sticky top-0 z-30 shadow-sm">
            <h2 class="text-lg font-bold text-gray-900">
                @yield('header_title')
            </h2>
            <div class="flex items-center gap-4">
                <!-- Locale Switcher -->
                <a href="{{ route('language.switch', app()->getLocale() == 'ar' ? 'en' : 'ar') }}" 
                   class="flex items-center gap-2 bg-gray-50 px-3 py-1.5 rounded text-gray-600 hover:text-gray-900 hover:bg-gray-100 transition-colors border border-gray-200 text-xs font-semibold">
                    <i class="fa-solid fa-globe"></i>
                    <span class="uppercase tracking-wider">{{ app()->getLocale() == 'ar' ? 'English' : 'العربية' }}</span>
                </a>

                <!-- Profile Info -->
                <div class="flex items-center gap-3">
                    <div class="hidden text-right lg:block rtl:text-left">
                        <p class="text-sm font-bold text-gray-900 leading-tight">{{ Auth::user()->name }}</p>
                        <p class="text-[0.65rem] font-semibold text-gray-500 uppercase tracking-wider">
                            {{ app()->getLocale() == 'ar' ? 'مسؤول النظام' : 'Administrator' }}
                        </p>
                    </div>
                    <div class="w-8 h-8 rounded bg-blue-100 text-blue-700 flex items-center justify-center text-sm font-bold border border-blue-200">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                </div>
            </div>
        </header>

        <!-- Dynamic Content -->
        <div class="p-6 md:p-8 space-y-6">
            @if(session('success'))
                <div class="p-4 rounded-md bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-bold flex items-center justify-between" x-data="{show: true}" x-show="show">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-circle-check"></i> <span>{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="text-emerald-500 hover:text-emerald-800">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            @endif
            
            @if(session('error') || $errors->any())
                <div class="p-4 rounded-md bg-red-50 border border-red-200 text-red-700 text-sm font-bold flex items-center justify-between" x-data="{show: true}" x-show="show">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-triangle-exclamation"></i> <span>{{ session('error') ?? $errors->first() }}</span>
                    </div>
                    <button @click="show = false" class="text-red-500 hover:text-red-800">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- Upload Modal -->
    <div x-show="showUploadModal" 
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/50 backdrop-blur-sm"
        x-cloak>
        
        <div class="bg-white w-full max-w-lg rounded-md shadow-xl border border-gray-200 overflow-hidden" 
            @click.away="!isUploading && (showUploadModal = false)">
            
            <div class="px-6 py-5 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-gray-900 leading-tight">{{ app()->getLocale() == 'ar' ? 'رفع أرشفة جديدة' : 'Ingest New Archive' }}</h3>
                    <p class="text-gray-500 text-xs mt-1">{{ app()->getLocale() == 'ar' ? 'اسحب الملفات أو تصفح جهازك.' : 'Drag and drop or use your device.' }}</p>
                </div>
                <button @click="!isUploading && (showUploadModal = false)" class="text-gray-400 hover:text-gray-600">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <div class="p-6 space-y-5">
                <!-- Dropzone -->
                <div 
                    @dragover.prevent="dragging = true"
                    @dragleave.prevent="dragging = false"
                    @drop.prevent="dragging = false; handleFile($event.dataTransfer.files)"
                    :class="{ 'border-blue-500 bg-blue-50': dragging, 'border-gray-300 bg-gray-50 hover:bg-gray-100': !dragging }"
                    class="border-2 border-dashed rounded-md p-8 text-center transition-colors cursor-pointer group"
                    @click="$refs.fileInput.click()">
                    
                    <input type="file" x-ref="fileInput" class="hidden" @change="handleFile($event.target.files)">
                    
                    <div class="space-y-3">
                        <div class="w-12 h-12 bg-white rounded shadow-sm border border-gray-200 flex items-center justify-center mx-auto group-hover:border-blue-300 group-hover:text-blue-600 transition-colors text-gray-400">
                            <i class="fa-solid fa-cloud-arrow-up text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-700">{{ app()->getLocale() == 'ar' ? 'أسقط الملفات هنا' : 'Drop files here' }}</p>
                            <p class="text-gray-500 text-xs mt-1">{{ app()->getLocale() == 'ar' ? 'أو اضغط للتصفح من جهازك' : 'or click to browse local storage' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Mobile Camera Section -->
                <button @click="$refs.cameraInput.click()" 
                    class="w-full flex items-center justify-center gap-3 px-4 py-3 rounded-md bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors text-sm font-semibold shadow-sm">
                    <i class="fa-solid fa-camera text-gray-400"></i>
                    {{ app()->getLocale() == 'ar' ? 'المسح باستخدام الكاميرا' : 'Scan using Camera' }}
                </button>
                <input type="file" x-ref="cameraInput" class="hidden" accept="image/*" capture="environment" @change="handleFile($event.target.files)">

                <!-- Progress Bar -->
                <div x-show="isUploading" class="space-y-2 pt-2">
                    <div class="flex justify-between text-xs font-bold text-gray-700 tracking-wider">
                        <span>{{ app()->getLocale() == 'ar' ? 'جاري التحميل...' : 'Uploading...' }}</span>
                        <span x-text="uploadProgress + '%'"></span>
                    </div>
                    <div class="h-2 w-full bg-gray-100 rounded-full overflow-hidden border border-gray-200">
                        <div class="h-full bg-blue-600 transition-all duration-300" 
                            :style="{ width: uploadProgress + '%' }"></div>
                    </div>
                </div>

                <div class="flex justify-end pt-4 border-t border-gray-100">
                    <button @click="showUploadModal = false" :disabled="isUploading"
                        class="px-5 py-2 rounded-md text-gray-600 font-semibold hover:bg-gray-100 transition-colors text-sm disabled:opacity-50 border border-transparent">
                        {{ app()->getLocale() == 'ar' ? 'إلغاء' : 'Cancel' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
