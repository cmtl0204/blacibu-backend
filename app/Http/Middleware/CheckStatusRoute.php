<?php

namespace App\Http\Middleware;

use App\Models\Authentication\Route;
use Closure;
use Illuminate\Http\Request;

class CheckStatusRoute
{
    public function handle(Request $request, Closure $next)
    {
        $request->validate([
            'uri' => [
                'required',
            ]
        ]);

        $route = Route::where('uri', $request->uri)->whereHas('status', function ($status) {
            $status->where('code', '503');
        })->first();

        if (!$route) {
            return response()->json([
                'data' => null,
                'msg' => [
                    'summary' => "La página se encuentra en mantimiento",
                    'detail' => "",
                    'code' => '503'
                ]
            ], 503);
        }
        return $next($request);
    }
}
