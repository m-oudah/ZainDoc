@extends('layouts.app')

@section('title', (app()->getLocale() == 'ar' ? 'القواعد الذكية' : 'Smart Rules') . ' – ZainDoc')

@section('header_title')
    {{ app()->getLocale() == 'ar' ? 'إدارة القواعد الذكية' : 'Manage Smart Rules' }}
@endsection

@section('content')
    <div x-data="{ showModal: false }">
        <div class="rounded-3xl border border-zain-100 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-8 space-y-6">
            <div class="flex items-center justify-between">
            <h3 class="text-xl font-black text-zain-950 dark:text-white font-cairo">
                {{ app()->getLocale() == 'ar' ? 'قواعد التصنيف التلقائي' : 'Auto-Classification Rules' }}
            </h3>
            <div class="flex gap-4">
                <button @click="showModal = true" class="btn-premium btn-primary py-2 px-6 text-sm">
                    <i class="fa-solid fa-plus me-2"></i> {{ app()->getLocale() == 'ar' ? 'قاعدة جديدة' : 'New Rule' }}
                </button>
            </div>
        </div>
        
        @if($rules->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-left rtl:text-right">
                <thead>
                    <tr class="text-zain-500/40 text-[0.65rem] uppercase tracking-[0.2em] font-black border-b border-zain-100 dark:border-zinc-800">
                        <th class="pb-4 ps-4">{{ app()->getLocale() == 'ar' ? 'اسم القاعدة' : 'Rule Name' }}</th>
                        <th class="pb-4">{{ app()->getLocale() == 'ar' ? 'المجلد الوجهة' : 'Target Folder' }}</th>
                        <th class="pb-4">{{ app()->getLocale() == 'ar' ? 'الشروط' : 'Conditions' }}</th>
                        <th class="pb-4 text-center">{{ app()->getLocale() == 'ar' ? 'إجراء' : 'Action' }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zain-50 dark:divide-zinc-800/50">
                    @foreach($rules as $rule)
                    <tr class="group hover:bg-zain-50/50 dark:hover:bg-white/5 transition-colors duration-200">
                        <td class="py-5 ps-4">
                            <p class="font-bold text-zain-950 dark:text-white">{{ $rule->name }}</p>
                        </td>
                        <td class="py-5">
                            <span class="px-3 py-1 rounded-lg bg-orange-100 text-orange-600 text-xs font-bold">{{ $rule->folder->name ?? 'Root' }}</span>
                        </td>
                        <td class="py-5 text-sm font-medium text-zain-500">
                            {{ count($rule->query ?? []) }} {{ app()->getLocale() == 'ar' ? 'شروط' : 'conditions' }}
                        </td>
                        <td class="py-5 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button class="p-2 rounded-xl border border-zain-100 text-zain-400 hover:bg-zain-500 hover:text-white transition-all">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                <form action="{{ route('smart-rules.destroy', $rule->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 rounded-xl border border-red-100 text-red-400 hover:bg-red-500 hover:text-white transition-all">
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
        <div class="mt-6">
            {{ $rules->links() }}
        </div>
        @else
        <div class="flex flex-col items-center justify-center py-20 text-center space-y-4">
            <div class="w-20 h-20 bg-zain-50 rounded-full flex items-center justify-center text-zain-200">
                 <i class="fa-solid fa-gears text-4xl"></i>
            </div>
            <h4 class="text-lg font-bold text-zain-950 dark:text-white font-cairo">
                {{ app()->getLocale() == 'ar' ? 'لا توجد قواعد حالياً' : 'No rules defined yet' }}
            </h4>
            <p class="text-zain-500/60 max-w-xs mx-auto">
                {{ app()->getLocale() == 'ar' ? 'قم بإنشاء قواعد ذكية لأتمتة عملية الأرشفة.' : 'Create smart rules to automate your archiving process.' }}
            </p>
        </div>
        @endif
        </div>

        <!-- Create Rule Modal -->
        <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-6 bg-zain-950/60 backdrop-blur-sm" x-cloak>
            <div class="glassmorphism w-full max-w-md rounded-[2.5rem] p-8 space-y-8" @click.away="showModal = false">
                <div class="text-center">
                    <h3 class="text-3xl font-black text-white mb-2">{{ app()->getLocale() == 'ar' ? 'إنشاء قاعدة ذكية' : 'Create Smart Rule' }}</h3>
                </div>

                <form action="{{ route('smart-rules.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-zain-100">{{ app()->getLocale() == 'ar' ? 'اسم القاعدة' : 'Rule Name' }}</label>
                        <input type="text" name="name" required class="w-full px-5 py-4 rounded-2xl bg-white/5 border border-white/10 text-white focus:ring-2 focus:ring-zain-500 outline-none">
                    </div>

                    <div class="flex gap-4 pt-4">
                        <button type="button" @click="showModal = false" class="flex-1 py-4 rounded-2xl bg-white/5 text-white font-bold hover:bg-white/10 border border-white/10 transition-all font-cairo">
                            {{ app()->getLocale() == 'ar' ? 'إلغاء' : 'Cancel' }}
                        </button>
                        <button type="submit" class="flex-1 py-4 rounded-2xl bg-emerald-600 text-white font-bold hover:bg-emerald-700 transition-all shadow-xl shadow-emerald-600/30 font-cairo">
                            {{ app()->getLocale() == 'ar' ? 'حفظ' : 'Save Rule' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
