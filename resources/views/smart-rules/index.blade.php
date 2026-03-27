@extends('layouts.app')

@section('title', (app()->getLocale() == 'ar' ? 'القواعد الذكية' : 'Smart Rules') . ' – ZainDoc')

@section('header_title')
    {{ app()->getLocale() == 'ar' ? 'إدارة القواعد الذكية' : 'Manage Smart Rules' }}
@endsection

@section('content')
    <div x-data="{ showModal: false, step: 1, ruleName: '' }">
        <div class="rounded-md border border-gray-200 bg-white p-5 shadow-sm space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-gray-100">
            <h3 class="text-lg font-bold text-gray-900 font-cairo">
                {{ app()->getLocale() == 'ar' ? 'قواعد التصنيف التلقائي' : 'Auto-Classification Rules' }}
            </h3>
            <div class="flex gap-4">
                <button @click="showModal = true; step = 1; ruleName = ''" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-sm font-semibold shadow-sm flex items-center gap-2 transition-colors">
                    <i class="fa-solid fa-plus"></i> {{ app()->getLocale() == 'ar' ? 'قاعدة جديدة' : 'New Rule' }}
                </button>
            </div>
        </div>
        
            @if($rules->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-left rtl:text-right">
                <thead>
                    <tr class="text-gray-500 text-xs uppercase tracking-wider font-semibold border-b border-gray-200">
                        <th class="pb-3 ps-2">{{ app()->getLocale() == 'ar' ? 'اسم القاعدة' : 'Rule Name' }}</th>
                        <th class="pb-3">{{ app()->getLocale() == 'ar' ? 'المجلد الوجهة' : 'Target Folder' }}</th>
                        <th class="pb-3">{{ app()->getLocale() == 'ar' ? 'الكلمات المفتاحية' : 'Keywords' }}</th>
                        <th class="pb-3 text-center w-24">{{ app()->getLocale() == 'ar' ? 'إجراء' : 'Action' }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($rules as $rule)
                    <tr class="hover:bg-gray-50 transition-colors duration-200 text-sm">
                        <td class="py-3 ps-2">
                            <p class="font-semibold text-gray-900">{{ $rule->name }}</p>
                        </td>
                        <td class="py-3">
                            <span class="px-2 py-0.5 rounded bg-blue-50 text-blue-600 text-xs font-bold border border-blue-100">{{ $rule->folder->name ?? (app()->getLocale() == 'ar' ? 'غير محدد' : 'Root') }}</span>
                        </td>
                        <td class="py-3 font-medium text-gray-600">
                            @if($rule->keywords && count($rule->keywords) > 0)
                                <div class="flex flex-wrap gap-1">
                                    @foreach(array_slice($rule->keywords, 0, 4) as $kw)
                                        <span class="px-1.5 py-0.5 bg-gray-100 text-gray-600 text-xs rounded border border-gray-200">{{ $kw }}</span>
                                    @endforeach
                                    @if(count($rule->keywords) > 4)
                                        <span class="text-xs text-gray-400">+{{ count($rule->keywords) - 4 }}</span>
                                    @endif
                                </div>
                            @else
                                <span class="text-gray-400 text-xs">—</span>
                            @endif
                        </td>
                        <td class="py-3 text-center">
                            <div class="flex items-center justify-center gap-1">
                                <form action="{{ route('smart-rules.destroy', $rule->id) }}" method="POST" onsubmit="return confirm('{{ app()->getLocale() == 'ar' ? 'هل أنت متأكد؟' : 'Are you sure?' }}')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 rounded text-gray-400 hover:text-red-600 hover:bg-red-50 transition-colors">
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
        <div class="mt-6">
            {{ $rules->links() }}
        </div>
        @else
        <div class="flex flex-col items-center justify-center py-12 text-center space-y-3">
            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center text-gray-400 border border-gray-100">
                 <i class="fa-solid fa-gears text-2xl"></i>
            </div>
            <h4 class="text-sm font-bold text-gray-900 font-cairo">
                {{ app()->getLocale() == 'ar' ? 'لا توجد قواعد حالياً' : 'No rules defined yet' }}
            </h4>
            <p class="text-gray-500 text-xs max-w-xs mx-auto">
                {{ app()->getLocale() == 'ar' ? 'قم بإنشاء قواعد ذكية لأتمتة عملية الأرشفة.' : 'Create smart rules to automate your archiving process.' }}
            </p>
        </div>
        @endif
        </div>

        <!-- Create Rule Modal (Two-Step) -->
        <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/50 backdrop-blur-sm" x-cloak
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0">
            <div class="bg-white w-full max-w-md rounded-md shadow-xl border border-gray-200 overflow-hidden" @click.away="showModal = false; step = 1">
                
                <!-- Modal Header -->
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 leading-tight">
                            {{ app()->getLocale() == 'ar' ? 'إنشاء قاعدة ذكية' : 'Create Smart Rule' }}
                        </h3>
                        <p class="text-gray-500 text-xs mt-1">
                            <span x-show="step === 1">
                                {{ app()->getLocale() == 'ar' ? 'الخطوة 1 من 2: اسم القاعدة' : 'Step 1 of 2: Rule Name' }}
                            </span>
                            <span x-show="step === 2">
                                {{ app()->getLocale() == 'ar' ? 'الخطوة 2 من 2: الكلمات المفتاحية والتصنيف' : 'Step 2 of 2: Keywords & Classification' }}
                            </span>
                        </p>
                    </div>
                    <button @click="showModal = false; step = 1" class="text-gray-400 hover:text-gray-600">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <!-- Step Indicators -->
                <div class="px-6 pt-4 flex items-center gap-3">
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold border-2 transition-colors"
                             :class="step >= 1 ? 'bg-blue-600 border-blue-600 text-white' : 'border-gray-300 text-gray-400'">1</div>
                        <span class="text-xs font-semibold" :class="step >= 1 ? 'text-blue-600' : 'text-gray-400'">
                            {{ app()->getLocale() == 'ar' ? 'اسم القاعدة' : 'Rule Name' }}
                        </span>
                    </div>
                    <div class="flex-1 h-px" :class="step >= 2 ? 'bg-blue-400' : 'bg-gray-200'"></div>
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold border-2 transition-colors"
                             :class="step >= 2 ? 'bg-blue-600 border-blue-600 text-white' : 'border-gray-300 text-gray-400'">2</div>
                        <span class="text-xs font-semibold" :class="step >= 2 ? 'text-blue-600' : 'text-gray-400'">
                            {{ app()->getLocale() == 'ar' ? 'الكلمات والتصنيف' : 'Keywords & Folder' }}
                        </span>
                    </div>
                </div>

                <!-- Step 1: Rule Name -->
                <div x-show="step === 1" class="p-6 space-y-4 text-sm">
                    <div class="space-y-1.5">
                        <label class="block font-semibold text-gray-700">{{ app()->getLocale() == 'ar' ? 'اسم القاعدة' : 'Rule Name' }}</label>
                        <input type="text" x-model="ruleName" required
                               class="w-full px-3 py-2 rounded-md bg-white border border-gray-300 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-colors"
                               placeholder="{{ app()->getLocale() == 'ar' ? 'مثال: فاتورة المشتريات' : 'e.g. Purchase Invoices' }}"
                               @keydown.enter.prevent="if(ruleName.trim()) step = 2">
                    </div>
                    <div class="flex gap-3 pt-3 border-t border-gray-100 mt-4">
                        <button type="button" @click="showModal = false; step = 1" class="flex-1 py-2 rounded-md bg-white border border-gray-300 text-gray-700 font-semibold text-sm hover:bg-gray-50 transition-colors font-cairo">
                            {{ app()->getLocale() == 'ar' ? 'إلغاء' : 'Cancel' }}
                        </button>
                        <button type="button" @click="if(ruleName.trim()) step = 2" class="flex-1 py-2 rounded-md bg-blue-600 border border-transparent text-white font-semibold text-sm hover:bg-blue-700 transition-colors shadow-sm font-cairo"
                                :disabled="!ruleName.trim()" :class="!ruleName.trim() ? 'opacity-50 cursor-not-allowed' : ''">
                            {{ app()->getLocale() == 'ar' ? 'التالي' : 'Next' }} <i class="fa-solid fa-arrow-right ms-1 rtl:hidden"></i><i class="fa-solid fa-arrow-left me-1 hidden rtl:inline"></i>
                        </button>
                    </div>
                </div>

                <!-- Step 2: Keywords + Folder -->
                <div x-show="step === 2" class="p-6 text-sm">
                    <form action="{{ route('smart-rules.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="name" :value="ruleName">

                        <div class="space-y-1.5">
                            <label class="block font-semibold text-gray-700">{{ app()->getLocale() == 'ar' ? 'الكلمات المفتاحية للتصنيف' : 'Classification Keywords' }}</label>
                            <textarea name="keywords" rows="3"
                                      class="w-full px-3 py-2 rounded-md bg-white border border-gray-300 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-colors resize-none"
                                      placeholder="{{ app()->getLocale() == 'ar' ? 'أدخل الكلمات مفصولة بفواصل، مثال: فاتورة، شراء، موردون' : 'Enter keywords separated by commas, e.g. invoice, purchase, vendor' }}"></textarea>
                            <p class="text-gray-400 text-xs">{{ app()->getLocale() == 'ar' ? 'سيتم تصنيف المستندات التي تحتوي على هذه الكلمات تلقائياً.' : 'Documents containing these keywords will be auto-classified.' }}</p>
                        </div>

                        <div class="space-y-1.5">
                            <label class="block font-semibold text-gray-700">{{ app()->getLocale() == 'ar' ? 'المجلد المستهدف (التصنيف)' : 'Target Folder (Classification)' }}</label>
                            <select name="folder_id" class="w-full px-3 py-2 rounded-md bg-white border border-gray-300 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-colors">
                                <option value="">{{ app()->getLocale() == 'ar' ? 'بدون تصنيف محدد' : 'No specific folder' }}</option>
                                @foreach($folders as $folder)
                                    <option value="{{ $folder->id }}">{{ $folder->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex gap-3 pt-3 border-t border-gray-100 mt-4">
                            <button type="button" @click="step = 1" class="flex-1 py-2 rounded-md bg-white border border-gray-300 text-gray-700 font-semibold text-sm hover:bg-gray-50 transition-colors font-cairo">
                                <i class="fa-solid fa-arrow-left me-1 rtl:hidden"></i><i class="fa-solid fa-arrow-right ms-1 hidden rtl:inline"></i>
                                {{ app()->getLocale() == 'ar' ? 'السابق' : 'Back' }}
                            </button>
                            <button type="submit" class="flex-1 py-2 rounded-md bg-blue-600 border border-transparent text-white font-semibold text-sm hover:bg-blue-700 transition-colors shadow-sm font-cairo">
                                {{ app()->getLocale() == 'ar' ? 'حفظ القاعدة' : 'Save Rule' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
