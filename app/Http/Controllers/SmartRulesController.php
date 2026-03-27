<?php

namespace App\Http\Controllers;
use App\Models\Folder;
use App\Models\SmartCollection;
use Illuminate\Http\Request;

class SmartRulesController extends Controller
{
    public function index()
    {
        $rules = SmartCollection::with('folder')->latest()->paginate(20);
        $folders = Folder::orderBy('name')->get();
        return view('smart-rules.index', compact('rules', 'folders'));
    }

    public function store(Request $request) 
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'folder_id'  => 'nullable|exists:folders,id',
            'keywords'   => 'nullable|string',
        ]);

        // Convert comma-separated keywords string to array
        $keywordsArray = null;
        if ($request->filled('keywords')) {
            $keywordsArray = array_filter(array_map('trim', explode(',', $request->keywords)));
            $keywordsArray = array_values($keywordsArray);
        }

        SmartCollection::create([
            'name'       => $request->name,
            'folder_id'  => $request->folder_id ?: null,
            'keywords'   => $keywordsArray,
            'rules'      => json_encode(['conditions' => []]),
            'created_by' => auth()->id()
        ]);
        
        return back()->with('success', app()->getLocale() === 'ar' ? 'تم إنشاء القاعدة بنجاح.' : 'Smart rule created successfully.');
    }

    public function destroy(SmartCollection $smartRule)
    {
        $smartRule->delete();
        return back()->with('success', app()->getLocale() === 'ar' ? 'تم حذف القاعدة بنجاح.' : 'Smart rule deleted successfully.');
    }
}
