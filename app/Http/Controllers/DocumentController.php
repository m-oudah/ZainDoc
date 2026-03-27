<?php

namespace App\Http\Controllers;



use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentController extends Controller
{
    public function index()
    {
        $documents = Document::with(['creator', 'folder'])
            ->where('is_latest', true)
            ->latest()
            ->paginate(20);
            
        return view('my-archive.index', compact('documents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'document' => ['required', 'file', 'max:51200'], // 50MB limit
            'folder_id' => ['nullable', 'exists:folders,id'],
            'title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $file = $request->file('document');
        $fileName = $file->getClientOriginalName();
        $title = $request->title ?: pathinfo($fileName, PATHINFO_FILENAME);
        
        $path = $file->store('documents', 'local');

        return DB::transaction(function () use ($file, $path, $fileName, $title, $request) {
            $document = Document::create([
                'folder_id' => $request->folder_id,
                'version_group_id' => (string) Str::uuid(),
                'version' => 1,
                'is_latest' => true,
                'title' => $title,
                'description' => $request->description,
                'file_path' => $path,
                'file_name' => $fileName,
                'extension' => $file->getClientOriginalExtension(),
                'file_size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'created_by' => Auth::id(),
            ]);

            return response()->json([
                'success' => true,
                'document' => $document,
                'message' => 'Document uploaded successfully.'
            ]);
        });
    }

    public function bulkStore(Request $request)
    {
        $request->validate([
            'documents' => ['required', 'array'],
            'documents.*' => ['file', 'max:51200'],
            'folder_id' => ['nullable', 'exists:folders,id'],
            'description' => ['nullable', 'string'],
        ]);

        $uploaded = [];
        foreach ($request->file('documents') as $file) {
            $fileName = $file->getClientOriginalName();
            $path = $file->store('documents', 'local');
            
            $doc = DB::transaction(function () use ($file, $path, $fileName, $request) {
                $document = Document::create([
                    'folder_id' => $request->folder_id,
                    'version_group_id' => (string) Str::uuid(),
                    'version' => 1,
                    'is_latest' => true,
                    'title' => pathinfo($fileName, PATHINFO_FILENAME),
                    'description' => $request->description,
                    'file_path' => $path,
                    'file_name' => $fileName,
                    'extension' => $file->getClientOriginalExtension(),
                    'file_size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                    'created_by' => Auth::id(),
                ]);
                return $document;
            });
            $uploaded[] = $doc;
        }

        return response()->json([
            'success' => true,
            'count' => count($uploaded),
            'message' => 'Documents uploaded successfully.'
        ]);
    }

    public function show(Document $document)
    {
        return response()->file(storage_path('app/' . $document->file_path));
    }

    public function destroy(Document $document)
    {
        $message = app()->getLocale() === 'ar' ? 'تم حذف المستند بنجاح.' : 'Document deleted successfully.';
        return back()->with('success', $message);
    }
}
