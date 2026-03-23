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
        <div class="rounded-md border border-gray-200 bg-white p-5 shadow-sm space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                <h3 class="text-lg font-bold text-gray-900 font-cairo">
                    {{ app()->getLocale() == 'ar' ? 'كافة التصنيفات والمجلدات' : 'All Folders & Categories' }}
                </h3>
                <div class="flex gap-4">
                    <button @click="openCreate()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-sm font-semibold shadow-sm flex items-center gap-2 transition-colors">
                        <i class="fa-solid fa-folder-plus"></i> {{ app()->getLocale() == 'ar' ? 'إضافة تصنيف' : 'Add Category' }}
                    </button>
                </div>
            </div>
            
            @if($folders->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left rtl:text-right">
                    <thead>
                        <tr class="text-gray-500 text-xs uppercase tracking-wider font-semibold border-b border-gray-200">
                            <th class="pb-3 ps-2">{{ app()->getLocale() == 'ar' ? 'اسم التصنيف' : 'Category Name' }}</th>
                            <th class="pb-3">{{ app()->getLocale() == 'ar' ? 'الوصف' : 'Description' }}</th>
                            <th class="pb-3">{{ app()->getLocale() == 'ar' ? 'تحتوي على' : 'Contains' }}</th>
                            <th class="pb-3 text-center w-24">{{ app()->getLocale() == 'ar' ? 'إجراء' : 'Action' }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($folders as $folder)
                        <tr class="hover:bg-gray-50 transition-colors duration-200 text-sm">
                            <td class="py-3 ps-2">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded shrink-0 bg-blue-50 text-blue-600 flex items-center justify-center border border-blue-100">
                                        <i class="fa-regular fa-folder text-sm"></i>
                                    </div>
                                    <a href="{{ route('my-archive', ['folder_id' => $folder->id]) }}" class="font-semibold text-gray-900 hover:text-blue-600 transition-colors truncate">{{ $folder->name }}</a>
                                </div>
                            </td>
                            <td class="py-3 max-w-xs">
                                <p class="text-xs text-gray-500 font-medium truncate" title="{{ $folder->description }}">
                                    {{ $folder->description ?? '--' }}
                                </p>
                            </td>
                            <td class="py-3">
                                <span class="px-2 py-0.5 rounded bg-gray-100 text-gray-600 text-xs font-bold uppercase border border-gray-200">
                                    {{ $folder->documents_count }} {{ app()->getLocale() == 'ar' ? 'ملفات' : 'Files' }}
                                </span>
                            </td>
                            <td class="py-3 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    <button @click="openEdit({{ $folder->toJson() }})" class="p-1.5 rounded text-gray-400 hover:text-blue-600 hover:bg-blue-50 transition-colors" title="Edit">
                                        <i class="fa-solid fa-pen-to-square text-sm"></i>
                                    </button>
                                    <form action="{{ route('folders.destroy', $folder->id) }}" method="POST" onsubmit="return confirm('Are you sure?')" class="inline">
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
            <div class="mt-6">
                {{ $folders->links() }}
            </div>
            @else
            <div class="flex flex-col items-center justify-center py-12 text-center space-y-3">
                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center text-gray-400 border border-gray-100">
                     <i class="fa-regular fa-folder-open text-2xl"></i>
                </div>
                <h4 class="text-sm font-bold text-gray-900 font-cairo">
                    {{ app()->getLocale() == 'ar' ? 'لا توجد تصنيفات حالياً' : 'No categories yet' }}
                </h4>
            </div>
            @endif
        </div>

        <!-- Category Modal -->
        <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/50 backdrop-blur-sm" x-cloak
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0">
            <div class="bg-white w-full max-w-md rounded-md shadow-xl border border-gray-200 overflow-hidden" @click.away="showModal = false">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 leading-tight" x-text="mode === 'create' ? '{{ app()->getLocale() == 'ar' ? 'إضافة تصنيف' : 'Add Category' }}' : '{{ app()->getLocale() == 'ar' ? 'تعديل التصنيف' : 'Edit Category' }}'"></h3>
                        <p class="text-gray-500 text-xs mt-1">{{ app()->getLocale() == 'ar' ? 'أدخل تفاصيل التصنيف' : 'Provide category details' }}</p>
                    </div>
                    <button @click="showModal = false" class="text-gray-400 hover:text-gray-600">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <form :action="mode === 'create' ? '{{ route('folders.store') }}' : '/folders/' + currentFolder.id" method="POST" class="p-6 space-y-4 text-sm">
                    @csrf
                    <input type="hidden" name="_method" value="PUT" x-bind:disabled="mode === 'create'">

                    <div class="space-y-1.5">
                        <label class="block font-semibold text-gray-700">{{ app()->getLocale() == 'ar' ? 'اسم التصنيف' : 'Category Name' }}</label>
                        <input type="text" name="name" x-model="currentFolder.name" required class="w-full px-3 py-2 rounded-md bg-white border border-gray-300 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-colors">
                    </div>

                    <div class="space-y-1.5">
                        <label class="block font-semibold text-gray-700">{{ app()->getLocale() == 'ar' ? 'وصف التصنيف' : 'Description' }}</label>
                        <textarea name="description" rows="3" x-model="currentFolder.description" class="w-full px-3 py-2 rounded-md bg-white border border-gray-300 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-colors resize-none"></textarea>
                    </div>

                    <div class="flex gap-3 pt-3 border-t border-gray-100 mt-4">
                        <button type="button" @click="showModal = false" class="flex-1 py-2 rounded-md bg-white border border-gray-300 text-gray-700 font-semibold text-sm hover:bg-gray-50 transition-colors font-cairo">
                            {{ app()->getLocale() == 'ar' ? 'إلغاء' : 'Cancel' }}
                        </button>
                        <button type="submit" class="flex-1 py-2 rounded-md bg-blue-600 border border-transparent text-white font-semibold text-sm hover:bg-blue-700 transition-colors shadow-sm font-cairo">
                            {{ app()->getLocale() == 'ar' ? 'حفظ' : 'Save Category' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
