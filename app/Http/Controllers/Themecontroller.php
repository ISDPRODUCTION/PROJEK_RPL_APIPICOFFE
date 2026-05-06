<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ThemeController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'theme_color' => 'required|in:#F97316,#3B82F6,#10B981,#8B5CF6,#EF4444',
            'dark_mode'   => 'required|boolean',
        ]);

        auth()->user()->update([
            'theme_color' => $request->theme_color,
            'dark_mode'   => $request->dark_mode,
        ]);

        return response()->json(['success' => true]);
    }
}