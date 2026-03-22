<?php

namespace App\Http\Controllers;
use App\Models\SmartCollection;
use Illuminate\Http\Request;

class SmartRulesController extends Controller
{
    public function index()
    {
        $rules = SmartCollection::latest()->paginate(20);
        return view('smart-rules.index', compact('rules'));
    }

    public function store(Request $request) 
    {
        $request->validate(['name' => 'required|string|max:255']);
        
        SmartCollection::create([
            'name' => $request->name,
            'rules' => json_encode(['conditions' => []]),
            'created_by' => auth()->id()
        ]);
        
        return back()->with('success', 'Smart rule created successfully.');
    }

    public function destroy(SmartCollection $smartRule)
    {
        $smartRule->delete();
        return back()->with('success', 'Smart rule deleted successfully.');
    }
}
