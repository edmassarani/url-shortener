<?php

namespace App\Http\Controllers;

use App\Models\Url;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
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

        $shortCode = substr((new Base62())->encode(md5($request->long_url)), 0, 10);

        $url = Url::create([
            'short_code' => $shortCode,
            'long_url' => $request->long_url,
            'expires_at' => now()->addHours($request->expiration ?? 24),
        ]);

        $requestsRemaining = null;

        if (!$request->user()) {
            // Tried everything I could to get the correct count, but this doesn't seem to want to give me the number I'm looking for
            $requestsRemaining = RateLimiter::remaining($request->ip(), 5);
        }

        return response()->json(['status' => 'success', 'data' => [...$url->toArray(), 'requests_remaining' => $requestsRemaining], 'message' => 'Url shortened successfully']);
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
