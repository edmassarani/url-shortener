<?php

namespace App\Http\Controllers;

use App\Models\Url;
use Illuminate\Http\Request;
use Tuupola\Base62;

class UrlController extends Controller
{
    public function index()
    {
        return Url::all();
    }

    public function shorten(Request $request)
    {
        $request->validate(['long_url' => 'required|url', 'expiration' => 'integer|min:1']);

        $shortCode = substr((new Base62())->encode($request->long_url), 0, 10);

        $url = Url::create([
            'short_code' => $shortCode,
            'long_url' => $request->long_url,
            'expires_at' => now()->addHours($request->expiration ?? 24),
        ]);

        return response()->json(['status' => 'success', 'data' => $url, 'message' => 'Url shortened successfully']);
    }

    public function redirect($code)
    {
        $url = Url::where('short_code', $code)->first();

        if (!$url || ($url->expires_at && now()->greaterThan($url->expires_at))) {
            return response()->json(['status' => 'error', 'message' => 'This link has expired'], 404);
        }

        $url->increment('visit_count');

        return redirect($url->long_url);
    }
}
