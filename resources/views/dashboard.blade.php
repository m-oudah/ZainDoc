@extends('layouts.app')

@section('title', 'Dashboard – ZainDoc')

@section('header_title')
    {{ app()->getLocale() == 'ar' ? 'نظرة عامة على لوحة التحكم' : 'Admin Dashboard Overview' }}
@endsection

@section('content')
    <!-- Welcome Bar -->
    <div class="flex flex-col md:flex-row items-center justify-between gap-6 py-5 px-8 rounded-[2rem] bg-gradient-to-r from-zain-600 to-zain-800 text-white shadow-2xl relative overflow-hidden">
        <div class="absolute inset-0 opacity-20 bg-[radial-gradient(#fff_1px,transparent_0)] bg-[size:20px_20px]"></div>
        <div class="relative z-10">
            <h2 class="text-3xl font-black mb-2 font-cairo">
                {{ app()->getLocale() == 'ar' ? 'مرحباً، ' . Auth::user()->name . ' 👋' : 'Welcome, ' . Auth::user()->name . ' 👋' }}
            </h2>
            <p class="text-zain-100 font-medium text-sm">
                {{ app()->getLocale() == 'ar' ? 'لديك ' . $stats['docs'] . ' مستندات في الأرشيف اليوم.' : 'You have ' . $stats['docs'] . ' documents in archive today.' }}
            </p>
        </div>
        <div class="relative z-10 flex gap-4">
            <button @click="showUploadModal = true; setTimeout(() => { document.querySelectorAll('input[type=file]').forEach(el => el.multiple = true) }, 100)" class="px-6 py-3 rounded-xl bg-white text-zain-600 font-black shadow-lg hover:-translate-y-1 transition-transform">
                {{ app()->getLocale() == 'ar' ? 'رفع ملفات بالجملة' : 'Bulk Upload' }}
            </button>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Docs -->
        <div class="p-6 rounded-3xl border border-zain-100 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-sm hover:shadow-xl hover:border-zain-200 transition-all duration-300 transform hover:-translate-y-1 group">
            <div class="w-12 h-12 rounded-xl bg-zain-50 text-zain-500 flex items-center justify-center mb-4 group-hover:bg-zain-500 group-hover:text-white transition-colors duration-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2z"></path></svg>
            </div>
            <p class="text-zain-500/60 font-bold uppercase tracking-widest text-[0.65rem] mb-1">
                {{ app()->getLocale() == 'ar' ? 'المستندات' : 'Documents' }}
            </p>
            <h4 class="text-2xl font-black text-zain-950 dark:text-white">{{ $stats['docs'] }}</h4>
        </div>
        <!-- Folders -->
        <div class="p-6 rounded-3xl border border-zain-100 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-sm hover:shadow-xl hover:border-zain-200 transition-all duration-300 transform hover:-translate-y-1 group">
            <div class="w-12 h-12 rounded-xl bg-orange-50 text-orange-500 flex items-center justify-center mb-4 group-hover:bg-orange-500 group-hover:text-white transition-colors duration-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2z"></path></svg>
            </div>
            <p class="text-zain-500/60 font-bold uppercase tracking-widest text-[0.65rem] mb-1">
                {{ app()->getLocale() == 'ar' ? 'المجلدات' : 'Folders' }}
            </p>
            <h4 class="text-2xl font-black text-zain-950 dark:text-white">{{ $stats['folders'] }}</h4>
        </div>
        <!-- Shares -->
        <div class="p-6 rounded-3xl border border-zain-100 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-sm hover:shadow-xl hover:border-zain-200 transition-all duration-300 transform hover:-translate-y-1 group">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-500 flex items-center justify-center mb-4 group-hover:bg-emerald-500 group-hover:text-white transition-colors duration-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4a2 2 0 012 2v2m-6 4h4"></path></svg>
            </div>
            <p class="text-zain-500/60 font-bold uppercase tracking-widest text-[0.65rem] mb-1">
                {{ app()->getLocale() == 'ar' ? 'الملفات المشتركة' : 'Shared Files' }}
            </p>
            <h4 class="text-2xl font-black text-zain-950 dark:text-white">{{ $stats['shares'] }}</h4>
        </div>
        <!-- Storage -->
        <div class="p-6 rounded-3xl border border-zain-100 dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-sm hover:shadow-xl hover:border-zain-200 transition-all duration-300 transform hover:-translate-y-1 group">
            <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-500 flex items-center justify-center mb-4 group-hover:bg-purple-500 group-hover:text-white transition-colors duration-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            </div>
            <p class="text-zain-500/60 font-bold uppercase tracking-widest text-[0.65rem] mb-1">
                {{ app()->getLocale() == 'ar' ? 'المساحة المستخدمة' : 'Storage Used' }}
            </p>
            <h4 class="text-2xl font-black text-zain-950 dark:text-white">{{ $stats['storage'] }}</h4>
        </div>
    </div>

    <!-- Recent Documents Table -->
    <div class="rounded-3xl border border-zain-100 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-8 space-y-6">
        <div class="flex items-center justify-between">
            <h3 class="text-xl font-black text-zain-950 dark:text-white font-cairo">
                {{ app()->getLocale() == 'ar' ? 'أحدث الأرشيفات' : 'Recent Artifacts' }}
            </h3>
            <a href="{{ route('my-archive') }}" class="text-zain-500 font-bold hover:underline">
                {{ app()->getLocale() == 'ar' ? 'عرض كافة الأرشيف' : 'View All Archive' }}
            </a>
        </div>
        
        @if($recentDocuments->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-left rtl:text-right">
                <thead>
                    <tr class="text-zain-500/40 text-[0.65rem] uppercase tracking-[0.2em] font-black border-b border-zain-100 dark:border-zinc-800">
                        <th class="pb-4 ps-4">{{ app()->getLocale() == 'ar' ? 'اسم الملف' : 'Artifact Name' }}</th>
                        <th class="pb-4">{{ app()->getLocale() == 'ar' ? 'الامتداد' : 'Extension' }}</th>
                        <th class="pb-4">{{ app()->getLocale() == 'ar' ? 'الحجم' : 'Size' }}</th>
                        <th class="pb-4">{{ app()->getLocale() == 'ar' ? 'بواسطة' : 'Archived By' }}</th>
                        <th class="pb-4">{{ app()->getLocale() == 'ar' ? 'التاريخ' : 'Date' }}</th>
                        <th class="pb-4 text-center">{{ app()->getLocale() == 'ar' ? 'إجراء' : 'Action' }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zain-50 dark:divide-zinc-800/50">
                    @foreach($recentDocuments as $doc)
                    <tr class="group hover:bg-zain-50/50 dark:hover:bg-white/5 transition-colors duration-200">
                        <td class="py-5 ps-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-zain-50 text-zain-500 flex items-center justify-center group-hover:bg-zain-500 group-hover:text-white transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                </div>
                                <div>
                                    <p class="font-bold text-zain-950 dark:text-white">{{ $doc->title }}</p>
                                    <p class="text-xs text-zain-500/60 font-medium">v{{ $doc->version }} · Latest</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-5">
                            <span class="px-3 py-1 rounded-lg bg-zain-100 text-zain-600 text-[0.65rem] font-black uppercase">{{ $doc->extension }}</span>
                        </td>
                        <td class="py-5 text-sm font-medium text-zain-600">
                            {{ round($doc->file_size / 1024, 1) }} KB
                        </td>
                        <td class="py-5">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-lg bg-zain-950 text-white text-[10px] flex items-center justify-center font-bold">{{ substr($doc->creator->name, 0, 1) }}</div>
                                <span class="text-sm font-bold text-zain-950 dark:text-white">{{ $doc->creator->name }}</span>
                            </div>
                        </td>
                        <td class="py-5 text-sm text-zain-500 font-medium">
                            {{ $doc->created_at->diffForHumans() }}
                        </td>
                        <td class="py-5 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('documents.show', $doc->id) }}" target="_blank" class="p-2 rounded-xl border border-zain-100 text-zain-400 hover:bg-zain-500 hover:text-white hover:border-zain-500 transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </a>
                                <form action="{{ route('documents.destroy', $doc->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 rounded-xl border border-red-100 text-red-400 hover:bg-red-500 hover:text-white hover:border-red-500 transition-all">
                                        <i class="fa-solid fa-trash-can"></i>
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
        <div class="flex flex-col items-center justify-center py-20 text-center space-y-4">
            <div class="w-20 h-20 bg-zain-50 rounded-full flex items-center justify-center text-zain-200">
                 <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 13h6-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path></svg>
            </div>
            <h4 class="text-lg font-bold text-zain-950 dark:text-white font-cairo">
                {{ app()->getLocale() == 'ar' ? 'أرشيفك فارغ' : 'Your archive is empty' }}
            </h4>
            <p class="text-zain-500/60 max-w-xs mx-auto">
                {{ app()->getLocale() == 'ar' ? 'قم برفع أول مستند للبدء في إدارة بياناتك بذكاء.' : 'Upload your first document to start intelligently managing your data.' }}
            </p>
        </div>
        @endif
    </div>
@endsection
