<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Pastikan Auth di-import
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$roles  // Kita akan menerima satu atau lebih peran sebagai argumen
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Pertama, pastikan pengguna sudah login.
        if (!Auth::check()) {
            return redirect('login');
        }

        $user = Auth::user();

        
        foreach ($roles as $role) {
            if ($user->role == $role) {
                return $next($request); 
            }
        }


        return redirect('/')->with('error', 'Anda tidak diizinkan mengakses halaman tersebut.');
    }
}