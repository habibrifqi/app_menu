<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CanOrder
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
     public function handle(Request $request, Closure $next)
    {
        // hanya kasir dan manager yang boleh order
        $user = $request->user();

        if ($user->role_id !== 2 && $user->role_id !== 4) {
            // dd($user->role_id);
            return response()->json([
                'message' => 'Waiters tidak boleh cek data'
            ], 403);
        }


        return $next($request);
    }
}
