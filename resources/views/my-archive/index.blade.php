@extends('layouts.app')

@section('title', (app()->getLocale() == 'ar' ? 'أرشيفي' : 'My Archive') . ' – ZainDoc')

@section('header_title')
    {{ app()->getLocale() == 'ar' ? 'إدارة أرشيف المستندات' : 'Manage Document Archive' }}
@endsection

@section('content')
    <div x-data="{ 
        showArchiveModal: false,
        step: 'choice',
        uploadType: '',
        selectedFiles: [],
        uploadProgress: 0,
        isUploading: false,
        metadata: {
            title: '',
            folder_id: '',
            description: ''
        },
        resetUpload() {
            this.step = 'choice';
            this.selectedFiles = [];
            this.metadata = { title: '', folder_id: '', description: '' };
            this.uploadProgress = 0;
            this.isUploading = false;
            this.stopCamera();
        },
        streamData: null,
        startCamera() {
            this.step = 'cameraView';
            navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } })
            .then(stream => {
                this.streamData = stream;
                this.$refs.videoEl.srcObject = stream;
            })
            .catch(err => {
                alert('Camera error: ' + err.message);
                this.step = 'choice';
            });
        },
        stopCamera() {
            if (this.streamData) {
                this.streamData.getTracks().forEach(track => track.stop());
                this.streamData = null;
            }
        },
        takeSnapshot() {
            let canvas = document.createElement('canvas');
            canvas.width = this.$refs.videoEl.videoWidth;
            canvas.height = this.$refs.videoEl.videoHeight;
            canvas.getContext('2d').drawImage(this.$refs.videoEl, 0, 0);
            canvas.toBlob(blob => {
                let file = new File([blob], 'snapshot-' + Date.now() + '.jpg', { type: 'image/jpeg' });
                this.stopCamera();
                this.prepareUpload([file], 'camera');
            }, 'image/jpeg');
        },
        prepareUpload(files, type) {
            this.selectedFiles = Array.from(files);
            this.uploadType = type;
            if (type === 'single' || type === 'camera') {
                this.metadata.title = this.selectedFiles[0].name.split('.').slice(0, -1).join('.');
                this.step = 'metadata';
            } else {
                // Bulk directly uploads or shows a simplified list? 
                // User said 'after uploading file it should show a form to fill document information'
                // For bulk, maybe we just show a form for common metadata?
                this.step = 'metadata';
            }
        },
        submitFinal() {
            this.isUploading = true;
            let formData = new FormData();
            
            if (this.uploadType === 'bulk') {
                for (let i = 0; i < this.selectedFiles.length; i++) {
                    formData.append('documents[]', this.selectedFiles[i]);
                }
                formData.append('folder_id', this.metadata.folder_id);
                formData.append('description', this.metadata.description);
                
                axios.post('{{ route("documents.bulk") }}', formData).then(r => {
                    window.location.reload();
                }).catch(e => { alert('Error'); this.isUploading = false; });
            } else {
                formData.append('document', this.selectedFiles[0]);
                formData.append('title', this.metadata.title);
                formData.append('folder_id', this.metadata.folder_id);
                formData.append('description', this.metadata.description);
                
                axios.post('{{ route("documents.store") }}', formData).then(r => {
                    window.location.reload();
                }).catch(e => { alert('Error'); this.isUploading = false; });
            }
        }
    }">
        <div class="rounded-3xl border border-zain-100 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-8 space-y-6">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-black text-zain-950 dark:text-white font-cairo">
                    {{ app()->getLocale() == 'ar' ? 'كافة المستندات' : 'All Documents' }}
                </h3>
                <div class="flex items-center gap-4">
                    <form method="GET" action="{{ route('my-archive') }}" class="relative w-64">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ app()->getLocale() == 'ar' ? 'بحث عن مستند...' : 'Search artifacts...' }}" 
                               class="w-full pl-5 pr-10 py-2 rounded-xl bg-white/5 border border-zain-100 dark:border-zinc-800 text-zain-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-zain-500/50 text-sm">
                        <button type="submit" class="absolute right-3 rtl:left-3 rtl:right-auto top-1/2 -translate-y-1/2 text-zain-400 hover:text-zain-600 transition">
                            <i class="fa-solid fa-search"></i>
                        </button>
                    </form>
                    
                    <button @click="showArchiveModal = true; step = 'choice'" class="btn-premium btn-primary py-2 px-6 text-sm shrink-0">
                        <i class="fa-solid fa-plus me-2"></i> {{ app()->getLocale() == 'ar' ? 'إضافة مستند' : 'Add Document' }}
                    </button>
                </div>
            </div>
            
            @if($documents->count() > 0)
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
                        @foreach($documents as $doc)
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
                                {{ $doc->created_at->format('Y-m-d H:i') }}
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
            <div class="mt-6">
                {{ $documents->links() }}
            </div>
            @else
            <div class="flex flex-col items-center justify-center py-20 text-center space-y-4">
                <div class="w-20 h-20 bg-zain-50 rounded-full flex items-center justify-center text-zain-200">
                     <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path></svg>
                </div>
                <h4 class="text-lg font-bold text-zain-950 dark:text-white font-cairo">
                    {{ app()->getLocale() == 'ar' ? 'أرشيفك فارغ' : 'Your archive is empty' }}
                </h4>
            </div>
            @endif
        </div>

        <!-- Custom Upload Modal for MyArchive -->
        <div x-show="showArchiveModal" class="fixed inset-0 z-50 flex items-center justify-center p-6 bg-zain-950/60 backdrop-blur-sm" x-cloak>
            <div class="glassmorphism w-full max-w-xl rounded-[2.5rem] p-8 space-y-8 relative overflow-hidden" @click.away="!isUploading && resetUpload(); showArchiveModal = false">
                
                <!-- Step 1: Choice -->
                <div x-show="step === 'choice'" class="space-y-6">
                    <div class="text-center">
                        <h3 class="text-3xl font-black text-white mb-2">{{ app()->getLocale() == 'ar' ? 'إضافة مستند جديد' : 'Add New Document' }}</h3>
                        <p class="text-zain-100/60 font-medium">{{ app()->getLocale() == 'ar' ? 'اختر طريقة الرفع المفضلة' : 'Choose your preferred upload method' }}</p>
                    </div>

                    <div class="grid grid-cols-1 gap-4">
                        <button @click="$refs.singleInput.click()" class="flex items-center gap-4 p-6 rounded-2xl bg-white/5 border border-white/10 text-white hover:bg-white/10 transition-all font-bold text-lg group">
                            <div class="w-12 h-12 bg-orange-500 rounded-xl flex items-center justify-center group-hover:rotate-12 transition-transform">
                                <i class="fa-solid fa-file-arrow-up"></i>
                            </div>
                            <span>{{ app()->getLocale() == 'ar' ? 'رفع ملف واحد' : 'Upload Single File' }}</span>
                        </button>
                        <button @click="$refs.bulkInput.click()" class="flex items-center gap-4 p-6 rounded-2xl bg-white/5 border border-white/10 text-white hover:bg-white/10 transition-all font-bold text-lg group">
                            <div class="w-12 h-12 bg-zain-500 rounded-xl flex items-center justify-center group-hover:rotate-12 transition-transform">
                                <i class="fa-solid fa-layer-group"></i>
                            </div>
                            <span>{{ app()->getLocale() == 'ar' ? 'رفع ملفات بالجملة' : 'Upload Bulk Files' }}</span>
                        </button>
                        <button @click="startCamera()" class="flex items-center gap-4 p-6 rounded-2xl bg-white/5 border border-white/10 text-white hover:bg-white/10 transition-all font-bold text-lg group">
                            <div class="w-12 h-12 bg-emerald-500 rounded-xl flex items-center justify-center group-hover:rotate-12 transition-transform">
                                <i class="fa-solid fa-camera"></i>
                            </div>
                            <span>{{ app()->getLocale() == 'ar' ? 'استخدام كاميرا الجهاز' : 'Use Device Camera' }}</span>
                        </button>
                    </div>

                    <input type="file" x-ref="bulkInput" class="hidden" multiple @change="prepareUpload($event.target.files, 'bulk')">
                    <input type="file" x-ref="singleInput" class="hidden" @change="prepareUpload($event.target.files, 'single')">
                </div>

                <!-- Step 1.5: Camera View -->
                <div x-show="step === 'cameraView'" class="space-y-6">
                    <div class="text-center">
                        <h3 class="text-3xl font-black text-white mb-2">{{ app()->getLocale() == 'ar' ? 'التقاط صورة للمستند' : 'Take a Picture' }}</h3>
                    </div>
                    <div class="rounded-2xl overflow-hidden bg-black aspect-video relative flex items-center justify-center shadow-lg border border-white/10">
                        <video x-ref="videoEl" autoplay playsinline class="w-full h-full object-cover"></video>
                    </div>
                    <div class="flex gap-4">
                        <button @click="stopCamera(); step = 'choice'" class="flex-1 py-4 rounded-2xl bg-white/5 text-white font-bold hover:bg-white/10 transition-all border border-white/10">
                            {{ app()->getLocale() == 'ar' ? 'إلغاء' : 'Cancel' }}
                        </button>
                        <button @click="takeSnapshot()" class="flex-1 py-4 rounded-2xl bg-emerald-600 text-white font-bold hover:bg-emerald-700 transition-all shadow-xl shadow-emerald-600/30">
                            <i class="fa-solid fa-camera me-2"></i> {{ app()->getLocale() == 'ar' ? 'التقاط الآن' : 'Capture Now' }}
                        </button>
                    </div>
                </div>

                <!-- Step 2: Metadata Form -->
                <div x-show="step === 'metadata'" class="space-y-6">
                    <div class="text-center">
                        <h3 class="text-3xl font-black text-white mb-2">{{ app()->getLocale() == 'ar' ? 'معلومات المستند' : 'Document Information' }}</h3>
                        <p class="text-zain-100/60 font-medium" x-text="uploadType === 'bulk' ? selectedFiles.length + ' documents selected' : selectedFiles[0]?.name"></p>
                    </div>

                    <div class="space-y-4">
                        <div x-show="uploadType !== 'bulk'" class="space-y-2">
                            <label class="text-sm font-bold text-zain-100">{{ app()->getLocale() == 'ar' ? 'عنوان المستند' : 'Document Title' }}</label>
                            <input type="text" x-model="metadata.title" class="w-full px-5 py-4 rounded-2xl bg-white/5 border border-white/10 text-white focus:ring-2 focus:ring-zain-500 outline-none">
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-bold text-zain-100">{{ app()->getLocale() == 'ar' ? 'التصنيف (المجلد)' : 'Category (Folder)' }}</label>
                            <select x-model="metadata.folder_id" class="w-full px-5 py-4 rounded-2xl bg-white/5 border border-white/10 text-white focus:ring-2 focus:ring-zain-500 outline-none">
                                <option value="" class="text-black">No Category</option>
                                @foreach($folders as $f)
                                    <option value="{{ $f->id }}" class="text-black">{{ $f->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-bold text-zain-100">{{ app()->getLocale() == 'ar' ? 'الكلمات الدالة أو الوصف' : 'Description or Keywords' }}</label>
                            <textarea x-model="metadata.description" rows="3" class="w-full px-5 py-4 rounded-2xl bg-white/5 border border-white/10 text-white focus:ring-2 focus:ring-zain-500 outline-none" placeholder="e.g. invoice, important, 2026..."></textarea>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <button @click="step = 'choice'" class="flex-1 py-4 rounded-2xl bg-white/5 text-white font-bold hover:bg-white/10 transition-all border border-white/10">
                            {{ app()->getLocale() == 'ar' ? 'رجوع' : 'Back' }}
                        </button>
                        <button @click="submitFinal()" :disabled="isUploading" class="flex-1 py-4 rounded-2xl bg-zain-600 text-white font-bold hover:bg-zain-700 transition-all shadow-xl shadow-zain-600/30 flex items-center justify-center gap-2">
                            <template x-if="!isUploading">
                                <span>{{ app()->getLocale() == 'ar' ? 'إتمام الأرشفة' : 'Finalize Archive' }}</span>
                            </template>
                            <template x-if="isUploading">
                                <i class="fa-solid fa-spinner animate-spin"></i>
                            </template>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
