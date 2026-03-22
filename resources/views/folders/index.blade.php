@extends('layouts.app')

@section('title', (app()->getLocale() == 'ar' ? 'التصنيفات' : 'Categories') . ' – ZainDoc')

@section('header_title')
    {{ app()->getLocale() == 'ar' ? 'إدارة تصنيفات الملفات' : 'Manage File Categories' }}
@endsection

@section('content')
    <div x-data="{ 
        showModal: false, 
        mode: 'create',
        currentFolder: { id: '', name: '', description: '' },
        openCreate() {
            this.mode = 'create';
            this.currentFolder = { id: '', name: '', description: '' };
            this.showModal = true;
        },
        openEdit(folder) {
            this.mode = 'edit';
            this.currentFolder = { ...folder };
            this.showModal = true;
        }
    }">
        <div class="rounded-3xl border border-zain-100 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-8 space-y-6">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-black text-zain-950 dark:text-white font-cairo">
                    {{ app()->getLocale() == 'ar' ? 'كافة المجلدات والتصنيفات' : 'All Folders & Categories' }}
                </h3>
                <div class="flex gap-4">
                    <button @click="openCreate()" class="btn-premium btn-primary py-2 px-6 text-sm">
                        <i class="fa-solid fa-folder-plus me-2"></i> {{ app()->getLocale() == 'ar' ? 'إضافة تصنيف' : 'Add Category' }}
                    </button>
                </div>
            </div>
            
            @if($folders->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left rtl:text-right">
                    <thead>
                        <tr class="text-zain-500/40 text-[0.65rem] uppercase tracking-[0.2em] font-black border-b border-zain-100 dark:border-zinc-800">
                            <th class="pb-4 ps-4">{{ app()->getLocale() == 'ar' ? 'اسم التصنيف' : 'Category Name' }}</th>
                            <th class="pb-4">{{ app()->getLocale() == 'ar' ? 'الوصف' : 'Description' }}</th>
                            <th class="pb-4">{{ app()->getLocale() == 'ar' ? 'تحتوي على' : 'Contains' }}</th>
                            <th class="pb-4 text-center">{{ app()->getLocale() == 'ar' ? 'إجراء' : 'Action' }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zain-50 dark:divide-zinc-800/50">
                        @foreach($folders as $folder)
                        <tr class="group hover:bg-zain-50/50 dark:hover:bg-white/5 transition-colors duration-200">
                            <td class="py-5 ps-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-orange-100 text-orange-500 flex items-center justify-center group-hover:bg-orange-500 group-hover:text-white transition-all">
                                        <i class="fa-solid fa-folder text-lg"></i>
                                    </div>
                                    <a href="{{ route('my-archive', ['folder_id' => $folder->id]) }}" class="font-bold text-zain-950 dark:text-white hover:text-zain-600 transition-colors">{{ $folder->name }}</a>
                                </div>
                            </td>
                            <td class="py-5 max-w-xs">
                                <p class="text-sm text-zain-500 font-medium truncate" title="{{ $folder->description }}">
                                    {{ $folder->description ?? '--' }}
                                </p>
                            </td>
                            <td class="py-5">
                                <span class="px-3 py-1 rounded-lg bg-zain-100 text-zain-600 text-xs font-black uppercase">
                                    {{ $folder->documents_count }} {{ app()->getLocale() == 'ar' ? 'ملفات' : 'Files' }}
                                </span>
                            </td>
                            <td class="py-5 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button @click="openEdit({{ $folder->toJson() }})" class="p-2 rounded-xl border border-zain-100 text-zain-400 hover:bg-zain-500 hover:text-white transition-all">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                    <form action="{{ route('folders.destroy', $folder->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
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
                {{ $folders->links() }}
            </div>
            @else
            <div class="flex flex-col items-center justify-center py-20 text-center space-y-4">
                <div class="w-20 h-20 bg-orange-50 rounded-full flex items-center justify-center text-orange-300">
                     <i class="fa-regular fa-folder-open text-3xl"></i>
                </div>
                <h4 class="text-lg font-bold text-zain-950 dark:text-white font-cairo">
                    {{ app()->getLocale() == 'ar' ? 'لا توجد تصنيفات حالياً' : 'No categories yet' }}
                </h4>
            </div>
            @endif
        </div>

        <!-- Category Modal -->
        <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-6 bg-zain-950/60 backdrop-blur-sm" x-cloak>
            <div class="glassmorphism w-full max-w-md rounded-[2.5rem] p-8 space-y-8" @click.away="showModal = false">
                <div class="text-center">
                    <h3 class="text-3xl font-black text-white mb-2" x-text="mode === 'create' ? '{{ app()->getLocale() == 'ar' ? 'إضافة تصنيف' : 'Add Category' }}' : '{{ app()->getLocale() == 'ar' ? 'تعديل التصنيف' : 'Edit Category' }}'"></h3>
                </div>

                <form :action="mode === 'create' ? '{{ route('folders.store') }}' : '/folders/' + currentFolder.id" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="_method" value="PUT" x-bind:disabled="mode === 'create'">

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-zain-100">{{ app()->getLocale() == 'ar' ? 'اسم التصنيف' : 'Category Name' }}</label>
                        <input type="text" name="name" x-model="currentFolder.name" required class="w-full px-5 py-4 rounded-2xl bg-white/5 border border-white/10 text-white focus:ring-2 focus:ring-zain-500 outline-none">
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-zain-100">{{ app()->getLocale() == 'ar' ? 'وصف التصنيف' : 'Description' }}</label>
                        <textarea name="description" rows="3" x-model="currentFolder.description" class="w-full px-5 py-4 rounded-2xl bg-white/5 border border-white/10 text-white focus:ring-2 focus:ring-zain-500 outline-none"></textarea>
                    </div>

                    <div class="flex gap-4 pt-4">
                        <button type="button" @click="showModal = false" class="flex-1 py-4 rounded-2xl bg-white/5 text-white font-bold hover:bg-white/10 border border-white/10 transition-all font-cairo">
                            {{ app()->getLocale() == 'ar' ? 'إلغاء' : 'Cancel' }}
                        </button>
                        <button type="submit" class="flex-1 py-4 rounded-2xl bg-zain-600 text-white font-bold hover:bg-zain-700 transition-all shadow-xl shadow-zain-600/30 font-cairo">
                            {{ app()->getLocale() == 'ar' ? 'حفظ' : 'Save Category' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
