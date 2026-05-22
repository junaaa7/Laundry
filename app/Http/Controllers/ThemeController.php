<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ThemeController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'theme' => 'required|in:light,dark'
        ]);
        
        if (auth()->check()) {
            auth()->user()->update(['theme' => $request->theme]);
        }
        
        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }
        
        return back()->with('success', 'Theme berhasil diubah');
    }
}