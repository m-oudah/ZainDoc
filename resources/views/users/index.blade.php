@extends('layouts.app')

@section('title', (app()->getLocale() == 'ar' ? 'المستخدمين' : 'Users') . ' – ZainDoc')

@section('header_title')
    {{ app()->getLocale() == 'ar' ? 'إدارة المستخدمين والصلاحيات' : 'User & RBAC Management' }}
@endsection

@section('content')
    <div x-data="{ 
        showModal: false, 
        mode: 'create',
        currentUser: { id: '', name: '', email: '', password: '' },
        errors: [],
        isSaving: false,
        openCreate() {
            this.mode = 'create';
            this.currentUser = { id: '', name: '', email: '', password: '' };
            this.errors = [];
            this.showModal = true;
        },
        openEdit(user) {
            this.mode = 'edit';
            this.currentUser = { ...user, password: '' };
            this.errors = [];
            this.showModal = true;
        },
        saveUser() {
            this.isSaving = true;
            this.errors = [];
            let url = this.mode === 'create' ? '{{ route('users.store') }}' : '/users/' + this.currentUser.id;
            let method = this.mode === 'create' ? 'post' : 'put';
            
            axios[method](url, this.currentUser)
                .then(res => {
                    window.location.reload();
                })
                .catch(err => {
                    this.isSaving = false;
                    if (err.response && err.response.data.errors) {
                        this.errors = Object.values(err.response.data.errors).flat();
                    } else {
                        this.errors = [err.response?.data?.message || 'Unknown error occurred'];
                    }
                });
        }
    }">
        <div class="rounded-md border border-gray-200 bg-white p-5 shadow-sm space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                <h3 class="text-lg font-bold text-gray-900 font-cairo">
                    {{ app()->getLocale() == 'ar' ? 'المستخدمون المسجلون' : 'Registered Users' }}
                </h3>
                <div class="flex gap-4">
                    <button @click="openCreate()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-sm font-semibold shadow-sm flex items-center gap-2 transition-colors">
                        <i class="fa-solid fa-user-plus"></i> {{ app()->getLocale() == 'ar' ? 'إضافة مستخدم' : 'Add User' }}
                    </button>
                </div>
            </div>
            
            @if($users->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left rtl:text-right">
                    <thead>
                        <tr class="text-gray-500 text-xs uppercase tracking-wider font-semibold border-b border-gray-200">
                            <th class="pb-3 ps-2">{{ app()->getLocale() == 'ar' ? 'الاسم' : 'Name' }}</th>
                            <th class="pb-3">{{ app()->getLocale() == 'ar' ? 'البريد الإلكتروني' : 'Email' }}</th>
                            <th class="pb-3">{{ app()->getLocale() == 'ar' ? 'تاريخ التسجيل' : 'Registered At' }}</th>
                            <th class="pb-3 text-center w-24">{{ app()->getLocale() == 'ar' ? 'إجراء' : 'Action' }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($users as $user)
                        <tr class="hover:bg-gray-50 transition-colors duration-200 text-sm">
                            <td class="py-3 ps-2">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded shrink-0 bg-gray-100 text-gray-700 flex items-center justify-center font-bold border border-gray-200 text-xs">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <p class="font-semibold text-gray-900">{{ $user->name }}</p>
                                </div>
                            </td>
                            <td class="py-3">
                                <span class="font-medium text-gray-600">{{ $user->email }}</span>
                            </td>
                            <td class="py-3 text-gray-500">
                                {{ $user->created_at->format('Y-m-d') }}
                            </td>
                            <td class="py-3 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    <button @click="openEdit({{ $user->toJson() }})" class="p-1.5 rounded text-gray-400 hover:text-blue-600 hover:bg-blue-50 transition-colors" title="Edit">
                                        <i class="fa-solid fa-user-gear text-sm"></i>
                                    </button>
                                    @if($user->id !== auth()->id())
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded text-gray-400 hover:text-red-600 hover:bg-red-50 transition-colors" title="Delete">
                                            <i class="fa-solid fa-user-minus text-sm"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-6">
                {{ $users->links() }}
            </div>
            @endif
        </div>

        <!-- User Modal -->
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
                        <h3 class="text-lg font-bold text-gray-900 leading-tight" x-text="mode === 'create' ? 'Add User' : 'Edit User'"></h3>
                        <p class="text-gray-500 text-xs mt-1">{{ app()->getLocale() == 'ar' ? 'يرجى إدخال بيانات المستخدم' : 'Please enter user credentials' }}</p>
                    </div>
                    <button @click="showModal = false" class="text-gray-400 hover:text-gray-600">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <form @submit.prevent="saveUser()" class="p-6 space-y-4 text-sm">
                    <div x-show="errors.length > 0" class="p-3 bg-red-50 border border-red-200 rounded-md mb-4" x-cloak>
                        <ul class="list-disc list-inside text-xs font-semibold text-red-600">
                            <template x-for="error in errors" :key="error">
                                <li x-text="error"></li>
                            </template>
                        </ul>
                    </div>

                    <div class="space-y-1.5">
                        <label class="block font-semibold text-gray-700">{{ app()->getLocale() == 'ar' ? 'الاسم الكامل' : 'Full Name' }}</label>
                        <input type="text" name="name" x-model="currentUser.name" required class="w-full px-3 py-2 rounded-md bg-white border border-gray-300 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-colors">
                    </div>

                    <div class="space-y-1.5">
                        <label class="block font-semibold text-gray-700">{{ app()->getLocale() == 'ar' ? 'البريد الإلكتروني' : 'Email Address' }}</label>
                        <input type="email" name="email" x-model="currentUser.email" required class="w-full px-3 py-2 rounded-md bg-white border border-gray-300 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-colors">
                    </div>

                    <div class="space-y-1.5">
                        <label class="block font-semibold text-gray-700">{{ app()->getLocale() == 'ar' ? 'كلمة المرور' : 'Password' }}</label>
                        <input type="password" name="password" x-model="currentUser.password" :required="mode === 'create'" class="w-full px-3 py-2 rounded-md bg-white border border-gray-300 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-colors" :placeholder="mode === 'edit' ? 'Leave blank to keep current' : ''">
                    </div>

                    <div class="flex gap-3 pt-3 border-t border-gray-100 mt-4">
                        <button type="button" @click="showModal = false" class="flex-1 py-2 rounded-md bg-white border border-gray-300 text-gray-700 font-semibold text-sm hover:bg-gray-50 transition-colors font-cairo">
                            {{ app()->getLocale() == 'ar' ? 'إلغاء' : 'Cancel' }}
                        </button>
                        <button type="submit" :disabled="isSaving" class="flex-1 py-2 rounded-md bg-blue-600 border border-transparent text-white font-semibold text-sm hover:bg-blue-700 transition-colors flex justify-center items-center gap-2 shadow-sm font-cairo">
                            <span x-show="!isSaving">{{ app()->getLocale() == 'ar' ? 'حفظ' : 'Save User' }}</span>
                            <i x-show="isSaving" class="fa-solid fa-spinner animate-spin" x-cloak></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
