<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ThemeController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'theme' => 'required|in:light,dark'
        ]);
        
        auth()->user()->update(['theme' => $request->theme]);
        
        return response()->json(['success' => true]);
    }
}