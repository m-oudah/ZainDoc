@extends('layouts.app')

@section('title', 'Dashboard – ZainDoc')

@section('header_title')
    {{ app()->getLocale() == 'ar' ? 'نظرة عامة على لوحة التحكم' : 'Admin Dashboard Overview' }}
@endsection

@section('content')
    <!-- Welcome Bar -->
    <div class="flex flex-col md:flex-row items-center justify-between gap-4 py-4 px-6 rounded-md bg-white border border-gray-200 shadow-sm w-full">
        <div class="flex flex-col">
            <h2 class="text-xl font-bold text-gray-900 mb-1 font-cairo">
                {{ app()->getLocale() == 'ar' ? 'مرحباً، ' . Auth::user()->name . ' 👋' : 'Welcome, ' . Auth::user()->name . ' 👋' }}
            </h2>
            <p class="text-gray-500 font-medium text-sm">
                {{ app()->getLocale() == 'ar' ? 'لديك ' . $stats['docs'] . ' مستندات في الأرشيف اليوم.' : 'You have ' . $stats['docs'] . ' documents in archive today.' }}
            </p>
        </div>
        <div class="shrink-0 flex gap-3">
            <button @click="showUploadModal = true; setTimeout(() => { document.querySelectorAll('input[type=file]').forEach(el => el.multiple = true) }, 100)" class="px-5 py-2.5 rounded-md bg-blue-600 hover:bg-blue-700 text-white shadow-sm transition-colors text-sm font-semibold flex items-center gap-2">
                <i class="fa-solid fa-cloud-arrow-up"></i>
                {{ app()->getLocale() == 'ar' ? 'رفع ملفات بالجملة' : 'Bulk Upload' }}
            </button>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Docs -->
        <div class="p-4 rounded-md border border-gray-200 bg-white shadow-sm hover:border-blue-300 transition-colors duration-200 group">
            <div class="flex items-center justify-between mb-3">
                <p class="text-gray-500 font-semibold uppercase tracking-wider text-xs">
                    {{ app()->getLocale() == 'ar' ? 'المستندات' : 'Documents' }}
                </p>
                <div class="w-8 h-8 rounded shrink-0 bg-gray-50 text-blue-600 flex items-center justify-center group-hover:bg-blue-50 transition-colors">
                    <i class="fa-solid fa-file-lines text-sm"></i>
                </div>
            </div>
            <h4 class="text-2xl font-bold text-gray-900">{{ $stats['docs'] }}</h4>
        </div>
        <!-- Folders -->
        <div class="p-4 rounded-md border border-gray-200 bg-white shadow-sm hover:border-blue-300 transition-colors duration-200 group">
            <div class="flex items-center justify-between mb-3">
                <p class="text-gray-500 font-semibold uppercase tracking-wider text-xs">
                    {{ app()->getLocale() == 'ar' ? 'المجلدات' : 'Folders' }}
                </p>
                <div class="w-8 h-8 rounded shrink-0 bg-gray-50 text-blue-600 flex items-center justify-center group-hover:bg-blue-50 transition-colors">
                    <i class="fa-solid fa-folder text-sm"></i>
                </div>
            </div>
            <h4 class="text-2xl font-bold text-gray-900">{{ $stats['folders'] }}</h4>
        </div>
        <!-- Shares -->
        <div class="p-4 rounded-md border border-gray-200 bg-white shadow-sm hover:border-blue-300 transition-colors duration-200 group">
            <div class="flex items-center justify-between mb-3">
                <p class="text-gray-500 font-semibold uppercase tracking-wider text-xs">
                    {{ app()->getLocale() == 'ar' ? 'الملفات المشتركة' : 'Shared Files' }}
                </p>
                <div class="w-8 h-8 rounded shrink-0 bg-gray-50 text-blue-600 flex items-center justify-center group-hover:bg-blue-50 transition-colors">
                    <i class="fa-solid fa-share-nodes text-sm"></i>
                </div>
            </div>
            <h4 class="text-2xl font-bold text-gray-900">{{ $stats['shares'] }}</h4>
        </div>
        <!-- Storage -->
        <div class="p-4 rounded-md border border-gray-200 bg-white shadow-sm hover:border-blue-300 transition-colors duration-200 group">
            <div class="flex items-center justify-between mb-3">
                <p class="text-gray-500 font-semibold uppercase tracking-wider text-xs">
                    {{ app()->getLocale() == 'ar' ? 'المساحة المستخدمة' : 'Storage Used' }}
                </p>
                <div class="w-8 h-8 rounded shrink-0 bg-gray-50 text-blue-600 flex items-center justify-center group-hover:bg-blue-50 transition-colors">
                    <i class="fa-solid fa-hard-drive text-sm"></i>
                </div>
            </div>
            <h4 class="text-2xl font-bold text-gray-900">{{ $stats['storage'] }}</h4>
        </div>
    </div>

    <!-- Recent Documents Table -->
    <div class="rounded-md border border-gray-200 bg-white p-5 shadow-sm space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-gray-100">
            <h3 class="text-lg font-bold text-gray-900 font-cairo">
                {{ app()->getLocale() == 'ar' ? 'أحدث الأرشيفات' : 'Recent Artifacts' }}
            </h3>
            <a href="{{ route('my-archive') }}" class="text-blue-600 text-sm font-semibold hover:text-blue-700 transition-colors">
                {{ app()->getLocale() == 'ar' ? 'عرض كافة الأرشيف' : 'View All Archive' }}
            </a>
        </div>
        
        @if($recentDocuments->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-left rtl:text-right">
                <thead>
                    <tr class="text-gray-500 text-xs uppercase tracking-wider font-semibold border-b border-gray-200">
                        <th class="pb-3 ps-2">{{ app()->getLocale() == 'ar' ? 'اسم الملف' : 'Artifact Name' }}</th>
                        <th class="pb-3">{{ app()->getLocale() == 'ar' ? 'الامتداد' : 'Extension' }}</th>
                        <th class="pb-3">{{ app()->getLocale() == 'ar' ? 'الحجم' : 'Size' }}</th>
                        <th class="pb-3">{{ app()->getLocale() == 'ar' ? 'بواسطة' : 'Archived By' }}</th>
                        <th class="pb-3">{{ app()->getLocale() == 'ar' ? 'التاريخ' : 'Date' }}</th>
                        <th class="pb-3 text-center w-24">{{ app()->getLocale() == 'ar' ? 'إجراء' : 'Action' }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($recentDocuments as $doc)
                    <tr class="hover:bg-gray-50 transition-colors duration-200 text-sm">
                        <td class="py-3 ps-2">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded shrink-0 bg-gray-100 text-gray-500 flex items-center justify-center border border-gray-200">
                                    <i class="fa-solid fa-file-invoice"></i>
                                </div>
                                <div class="min-w-0">
                                    <p class="font-semibold text-gray-900 truncate">{{ $doc->title }}</p>
                                    <p class="text-xs text-gray-500">v{{ $doc->version }} · Latest</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-3">
                            <span class="px-2 py-0.5 rounded bg-gray-100 text-gray-600 text-xs font-bold uppercase border border-gray-200">{{ $doc->extension }}</span>
                        </td>
                        <td class="py-3 font-medium text-gray-600">
                            {{ round($doc->file_size / 1024, 1) }} KB
                        </td>
                        <td class="py-3">
                            <div class="flex items-center gap-2">
                                <div class="w-5 h-5 rounded bg-blue-100 text-blue-700 text-[10px] flex items-center justify-center font-bold shrink-0">{{ substr($doc->creator->name, 0, 1) }}</div>
                                <span class="font-medium text-gray-900 truncate">{{ $doc->creator->name }}</span>
                            </div>
                        </td>
                        <td class="py-3 text-gray-500">
                            {{ $doc->created_at->diffForHumans() }}
                        </td>
                        <td class="py-3 text-center">
                            <div class="flex items-center justify-center gap-1">
                                <a href="{{ route('documents.show', $doc->id) }}" target="_blank" class="p-1.5 rounded text-gray-400 hover:text-blue-600 hover:bg-blue-50 transition-colors" title="View">
                                    <i class="fa-solid fa-eye text-sm"></i>
                                </a>
                                <form action="{{ route('documents.destroy', $doc->id) }}" method="POST" onsubmit="return confirm('Are you sure?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 rounded text-gray-400 hover:text-red-600 hover:bg-red-50 transition-colors" title="Delete">
                                        <i class="fa-solid fa-trash-can text-sm"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="flex flex-col items-center justify-center py-12 text-center space-y-3">
            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center text-gray-400 border border-gray-100">
                 <i class="fa-solid fa-box-open text-2xl"></i>
            </div>
            <h4 class="text-sm font-bold text-gray-900 font-cairo">
                {{ app()->getLocale() == 'ar' ? 'أرشيفك فارغ' : 'Your archive is empty' }}
            </h4>
            <p class="text-gray-500 text-xs max-w-xs mx-auto">
                {{ app()->getLocale() == 'ar' ? 'قم برفع أول مستند للبدء في إدارة بياناتك بذكاء.' : 'Upload your first document to start intelligently managing your data.' }}
            </p>
        </div>
        @endif
    </div>
@endsection
