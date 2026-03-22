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
        <div class="rounded-3xl border border-zain-100 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-8 space-y-6">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-black text-zain-950 dark:text-white font-cairo">
                    {{ app()->getLocale() == 'ar' ? 'المستخدمون المسجلون' : 'Registered Users' }}
                </h3>
                <div class="flex gap-4">
                    <button @click="openCreate()" class="btn-premium btn-primary py-2 px-6 text-sm">
                        <i class="fa-solid fa-user-plus me-2"></i> {{ app()->getLocale() == 'ar' ? 'إضافة مستخدم' : 'Add User' }}
                    </button>
                </div>
            </div>
            
            @if($users->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left rtl:text-right">
                    <thead>
                        <tr class="text-zain-500/40 text-[0.65rem] uppercase tracking-[0.2em] font-black border-b border-zain-100 dark:border-zinc-800">
                            <th class="pb-4 ps-4">{{ app()->getLocale() == 'ar' ? 'الاسم' : 'Name' }}</th>
                            <th class="pb-4">{{ app()->getLocale() == 'ar' ? 'البريد الإلكتروني' : 'Email' }}</th>
                            <th class="pb-4">{{ app()->getLocale() == 'ar' ? 'تاريخ التسجيل' : 'Registered At' }}</th>
                            <th class="pb-4 text-center">{{ app()->getLocale() == 'ar' ? 'إجراء' : 'Action' }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zain-50 dark:divide-zinc-800/50">
                        @foreach($users as $user)
                        <tr class="group hover:bg-zain-50/50 dark:hover:bg-white/5 transition-colors duration-200">
                            <td class="py-5 ps-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-zain-950 text-white flex items-center justify-center font-bold">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <p class="font-bold text-zain-950 dark:text-white">{{ $user->name }}</p>
                                </div>
                            </td>
                            <td class="py-5">
                                <span class="text-sm font-medium text-zain-600">{{ $user->email }}</span>
                            </td>
                            <td class="py-5 text-sm text-zain-500 font-medium">
                                {{ $user->created_at->format('Y-m-d') }}
                            </td>
                            <td class="py-5 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button @click="openEdit({{ $user->toJson() }})" class="p-2 rounded-xl border border-zain-100 text-zain-400 hover:bg-zain-500 hover:text-white transition-all">
                                        <i class="fa-solid fa-user-gear"></i>
                                    </button>
                                    @if($user->id !== auth()->id())
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 rounded-xl border border-red-100 text-red-400 hover:bg-red-500 hover:text-white transition-all tabular-nums">
                                            <i class="fa-solid fa-user-minus"></i>
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
        <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-6 bg-zain-950/60 backdrop-blur-sm" x-cloak>
            <div class="glassmorphism w-full max-w-md rounded-[2.5rem] p-8 space-y-8" @click.away="showModal = false">
                <div class="text-center">
                    <h3 class="text-3xl font-black text-white mb-2" x-text="mode === 'create' ? 'Add User' : 'Edit User'"></h3>
                    <p class="text-zain-100/60 font-medium">{{ app()->getLocale() == 'ar' ? 'يرجى إدخال بيانات المستخدم' : 'Please enter user credentials' }}</p>
                </div>

                <form @submit.prevent="saveUser()" class="space-y-4">
                    <div x-show="errors.length > 0" class="p-4 bg-red-500/10 border border-red-500/20 rounded-2xl mb-4" x-cloak>
                        <ul class="list-disc list-inside text-sm font-bold text-red-200">
                            <template x-for="error in errors" :key="error">
                                <li x-text="error"></li>
                            </template>
                        </ul>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-zain-100">{{ app()->getLocale() == 'ar' ? 'الاسم الكامل' : 'Full Name' }}</label>
                        <input type="text" name="name" x-model="currentUser.name" required class="w-full px-5 py-4 rounded-2xl bg-white/5 border border-white/10 text-white focus:ring-2 focus:ring-zain-500 outline-none">
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-zain-100">{{ app()->getLocale() == 'ar' ? 'البريد الإلكتروني' : 'Email Address' }}</label>
                        <input type="email" name="email" x-model="currentUser.email" required class="w-full px-5 py-4 rounded-2xl bg-white/5 border border-white/10 text-white focus:ring-2 focus:ring-zain-500 outline-none">
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-zain-100">{{ app()->getLocale() == 'ar' ? 'كلمة المرور' : 'Password' }}</label>
                        <input type="password" name="password" x-model="currentUser.password" :required="mode === 'create'" class="w-full px-5 py-4 rounded-2xl bg-white/5 border border-white/10 text-white focus:ring-2 focus:ring-zain-500 outline-none" :placeholder="mode === 'edit' ? 'Leave blank to keep current' : ''">
                    </div>

                    <div class="flex gap-4 pt-4">
                        <button type="button" @click="showModal = false" class="flex-1 py-4 rounded-2xl bg-white/5 text-white font-bold hover:bg-white/10 border border-white/10 transition-all font-cairo">
                            {{ app()->getLocale() == 'ar' ? 'إلغاء' : 'Cancel' }}
                        </button>
                        <button type="submit" :disabled="isSaving" class="flex-1 py-4 rounded-2xl bg-zain-600 text-white font-bold hover:bg-zain-700 transition-all shadow-xl shadow-zain-600/30 flex justify-center items-center gap-2 font-cairo">
                            <span x-show="!isSaving">{{ app()->getLocale() == 'ar' ? 'حفظ' : 'Save User' }}</span>
                            <i x-show="isSaving" class="fa-solid fa-spinner animate-spin" x-cloak></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
