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
        <div class="rounded-md border border-gray-200 bg-white p-5 shadow-sm space-y-4">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-3 border-b border-gray-100">
                <h3 class="text-lg font-bold text-gray-900 font-cairo">
                    {{ app()->getLocale() == 'ar' ? 'كافة المستندات' : 'All Documents' }}
                </h3>
                <div class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto">
                    <form method="GET" action="{{ route('my-archive') }}" class="relative w-full sm:w-64">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ app()->getLocale() == 'ar' ? 'بحث عن مستند...' : 'Search artifacts...' }}" 
                               class="w-full pl-4 pr-10 py-2 rounded-md bg-gray-50 border border-gray-200 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-colors text-sm">
                        <button type="submit" class="absolute right-3 rtl:left-3 rtl:right-auto top-1/2 -translate-y-1/2 text-gray-400 hover:text-blue-600 transition-colors">
                            <i class="fa-solid fa-search"></i>
                        </button>
                    </form>
                    
                    <button @click="showArchiveModal = true; step = 'choice'" class="w-full sm:w-auto px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-sm font-semibold shadow-sm flex items-center justify-center gap-2 transition-colors shrink-0">
                        <i class="fa-solid fa-plus"></i> {{ app()->getLocale() == 'ar' ? 'إضافة مستند' : 'Add Document' }}
                    </button>
                </div>
            </div>
            
            @if($documents->count() > 0)
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
                        @foreach($documents as $doc)
                        <tr class="hover:bg-gray-50 transition-colors duration-200 text-sm">
                            <td class="py-3 ps-2">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded shrink-0 bg-gray-100 text-gray-500 flex items-center justify-center border border-gray-200">
                                        <i class="fa-solid fa-file-invoice"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-semibold text-gray-900 truncate" title="{{ $doc->title }}">{{ Str::limit($doc->title, 40) }}</p>
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
                                    <span class="font-medium text-gray-900 truncate max-w-[100px]">{{ $doc->creator->name }}</span>
                                </div>
                            </td>
                            <td class="py-3 text-gray-500">
                                {{ $doc->created_at->format('Y-m-d H:i') }}
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
            <div class="mt-6">
                {{ $documents->links() }}
            </div>
            @else
            <div class="flex flex-col items-center justify-center py-12 text-center space-y-3">
                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center text-gray-400 border border-gray-100">
                     <i class="fa-solid fa-folder-open text-2xl"></i>
                </div>
                <h4 class="text-sm font-bold text-gray-900 font-cairo">
                    {{ app()->getLocale() == 'ar' ? 'أرشيفك فارغ' : 'Your archive is empty' }}
                </h4>
            </div>
            @endif
        </div>

        <!-- Custom Upload Modal for MyArchive -->
        <div x-show="showArchiveModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/50 backdrop-blur-sm" x-cloak
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0">
            <div class="bg-white w-full max-w-lg rounded-md shadow-xl border border-gray-200 overflow-hidden" @click.away="!isUploading && resetUpload(); showArchiveModal = false">
                
                <!-- Modal Header (Global) -->
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 leading-tight">
                            <span x-show="step === 'choice'">{{ app()->getLocale() == 'ar' ? 'إضافة مستند جديد' : 'Add New Document' }}</span>
                            <span x-show="step === 'cameraView'">{{ app()->getLocale() == 'ar' ? 'التقاط صورة للمستند' : 'Take a Picture' }}</span>
                            <span x-show="step === 'metadata'">{{ app()->getLocale() == 'ar' ? 'معلومات المستند' : 'Document Information' }}</span>
                        </h3>
                        <p class="text-gray-500 text-xs mt-1" x-show="step === 'choice'">{{ app()->getLocale() == 'ar' ? 'اختر طريقة الرفع المفضلة' : 'Choose your preferred upload method' }}</p>
                        <p class="text-gray-500 text-xs mt-1" x-show="step === 'metadata'" x-text="uploadType === 'bulk' ? selectedFiles.length + ' documents selected' : selectedFiles[0]?.name"></p>
                    </div>
                    <button @click="!isUploading && (resetUpload(), showArchiveModal = false)" class="text-gray-400 hover:text-gray-600">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <div class="p-6">
                    <!-- Step 1: Choice -->
                    <div x-show="step === 'choice'" class="space-y-3">
                        <button @click="$refs.singleInput.click()" class="w-full flex items-center gap-4 p-4 rounded-md bg-white border border-gray-200 hover:border-blue-300 hover:bg-blue-50 transition-colors text-left rtl:text-right group">
                            <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded flex items-center justify-center">
                                <i class="fa-solid fa-file-arrow-up text-lg"></i>
                            </div>
                            <span class="font-semibold text-gray-700 group-hover:text-blue-700 text-sm">{{ app()->getLocale() == 'ar' ? 'رفع ملف واحد' : 'Upload Single File' }}</span>
                        </button>
                        <button @click="$refs.bulkInput.click()" class="w-full flex items-center gap-4 p-4 rounded-md bg-white border border-gray-200 hover:border-blue-300 hover:bg-blue-50 transition-colors text-left rtl:text-right group">
                            <div class="w-10 h-10 bg-indigo-100 text-indigo-600 rounded flex items-center justify-center">
                                <i class="fa-solid fa-layer-group text-lg"></i>
                            </div>
                            <span class="font-semibold text-gray-700 group-hover:text-indigo-700 text-sm">{{ app()->getLocale() == 'ar' ? 'رفع ملفات بالجملة' : 'Upload Bulk Files' }}</span>
                        </button>
                        <button @click="startCamera()" class="w-full flex items-center gap-4 p-4 rounded-md bg-white border border-gray-200 hover:border-blue-300 hover:bg-blue-50 transition-colors text-left rtl:text-right group">
                            <div class="w-10 h-10 bg-emerald-100 text-emerald-600 rounded flex items-center justify-center">
                                <i class="fa-solid fa-camera text-lg"></i>
                            </div>
                            <span class="font-semibold text-gray-700 group-hover:text-emerald-700 text-sm">{{ app()->getLocale() == 'ar' ? 'استخدام كاميرا الجهاز' : 'Use Device Camera' }}</span>
                        </button>

                        <input type="file" x-ref="bulkInput" class="hidden" multiple @change="prepareUpload($event.target.files, 'bulk')">
                        <input type="file" x-ref="singleInput" class="hidden" @change="prepareUpload($event.target.files, 'single')">
                    </div>

                    <!-- Step 1.5: Camera View -->
                    <div x-show="step === 'cameraView'" class="space-y-5">
                        <div class="rounded-md overflow-hidden bg-black aspect-video relative flex items-center justify-center shadow-inner">
                            <video x-ref="videoEl" autoplay playsinline class="w-full h-full object-cover"></video>
                        </div>
                        <div class="flex gap-3">
                            <button @click="stopCamera(); step = 'choice'" class="flex-1 py-2 rounded-md bg-white border border-gray-300 text-gray-700 font-semibold text-sm hover:bg-gray-50 transition-colors">
                                {{ app()->getLocale() == 'ar' ? 'إلغاء' : 'Cancel' }}
                            </button>
                            <button @click="takeSnapshot()" class="flex-1 py-2 rounded-md bg-blue-600 border border-transparent text-white font-semibold text-sm hover:bg-blue-700 transition-colors flex justify-center items-center gap-2">
                                <i class="fa-solid fa-camera"></i> {{ app()->getLocale() == 'ar' ? 'التقاط الآن' : 'Capture Now' }}
                            </button>
                        </div>
                    </div>

                    <!-- Step 2: Metadata Form -->
                    <div x-show="step === 'metadata'" class="space-y-4 text-sm">
                        <div x-show="uploadType !== 'bulk'" class="space-y-1.5">
                            <label class="block font-semibold text-gray-700">{{ app()->getLocale() == 'ar' ? 'عنوان المستند' : 'Document Title' }}</label>
                            <input type="text" x-model="metadata.title" class="w-full px-3 py-2 rounded-md bg-white border border-gray-300 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-colors">
                        </div>

                        <div class="space-y-1.5">
                            <label class="block font-semibold text-gray-700">{{ app()->getLocale() == 'ar' ? 'التصنيف (المجلد)' : 'Category (Folder)' }}</label>
                            <select x-model="metadata.folder_id" class="w-full px-3 py-2 rounded-md bg-white border border-gray-300 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-colors">
                                <option value="">No Category</option>
                                @foreach($folders as $f)
                                    <option value="{{ $f->id }}">{{ $f->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="space-y-1.5">
                            <label class="block font-semibold text-gray-700">{{ app()->getLocale() == 'ar' ? 'الكلمات الدالة أو الوصف' : 'Description or Keywords' }}</label>
                            <textarea x-model="metadata.description" rows="3" class="w-full px-3 py-2 rounded-md bg-white border border-gray-300 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-colors resize-none" placeholder="e.g. invoice, important, 2026..."></textarea>
                        </div>

                        <div class="flex gap-3 pt-3 border-t border-gray-100 mt-4">
                            <button @click="step = 'choice'" class="flex-1 py-4 md:py-2.5 rounded-md bg-white border border-gray-300 text-gray-700 font-semibold text-sm hover:bg-gray-50 transition-colors" :disabled="isUploading">
                                {{ app()->getLocale() == 'ar' ? 'رجوع' : 'Back' }}
                            </button>
                            <button @click="submitFinal()" :disabled="isUploading" class="flex-1 py-4 md:py-2.5 rounded-md bg-blue-600 border border-transparent text-white font-semibold text-sm hover:bg-blue-700 transition-colors flex justify-center items-center gap-2">
                                <template x-if="!isUploading">
                                    <span>{{ app()->getLocale() == 'ar' ? 'إتمام الأرشفة' : 'Finalize Archive' }}</span>
                                </template>
                                <template x-if="isUploading">
                                    <div class="flex items-center gap-2">
                                        <i class="fa-solid fa-spinner animate-spin"></i>
                                        <span>{{ app()->getLocale() == 'ar' ? 'الرجاء الانتظار...' : 'Please Wait...' }}</span>
                                    </div>
                                </template>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
