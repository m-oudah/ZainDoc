<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Document;
use App\Models\Folder;
use App\Models\DocumentShare;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'docs' => Document::where('is_latest', true)->count(),
            'folders' => Folder::count(),
            'shares' => DocumentShare::count(),
            'storage' => round(Document::sum('file_size') / 1024 / 1024, 2) . ' MB',
        ];

        $recentDocuments = Document::with('creator')
            ->where('is_latest', true)
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact('stats', 'recentDocuments'));
    }
}
