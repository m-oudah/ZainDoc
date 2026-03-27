<?php

namespace App\Http\Controllers;
use App\Models\Document;
use App\Models\Folder;
use Illuminate\Http\Request;

class MyArchiveController extends Controller
{
    public function index(Request $request)
    {
        $query = Document::with(['creator', 'folder'])
            ->where('is_latest', true);
            
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('file_name', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('folder_id')) {
            if ($request->folder_id === 'uncategorized') {
                $query->whereNull('folder_id');
            } else {
                $query->where('folder_id', $request->folder_id);
            }
        }
            
        $documents = $query->latest()->paginate(15)->withQueryString();
            
        $folders = Folder::all();
            
        return view('my-archive.index', compact('documents', 'folders'));
    }
}
