<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class FolderController extends Controller
{
    public function index()
    {
        $folders = Folder::withCount('documents')->latest()->paginate(20);
        return view('folders.index', compact('folders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        Folder::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . uniqid(),
            'description' => $request->description,
            'created_by' => Auth::id()
        ]);

        return back()->with('success', 'Category created successfully.');
    }

    public function update(Request $request, Folder $folder)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $folder->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . uniqid(),
            'description' => $request->description
        ]);

        return back()->with('success', 'Category updated successfully.');
    }

    public function destroy(Folder $folder)
    {
        if ($folder->documents()->count() > 0) {
            return back()->with('error', app()->getLocale() == 'ar' ? 'لا يمكن حذف التصنيف، يجب نقل الملفات إلى تصنيف آخر أولا.' : 'Cannot delete category, please move files first.');
        }

        $folder->delete();
        return back()->with('success', 'Category deleted successfully.');
    }
}
