<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class DashboardController extends Controller
{
    /**
     * Redirect users based on their role
     */
    public function redirect(): RedirectResponse
    {
        $user = Auth::user();

        return match ($user->role) {
            'guru' => redirect()->route('guru.dashboard'),
            'murid' => redirect()->route('murid.dashboard'),
            default => abort(403, 'Unauthorized role'),
        };
    }

    /**
     * Display guru dashboard
     */
    public function guru()
    {
        return view('guru.dashboard');
    }

    /**
     * Display murid dashboard
     */
    public function murid()
    {
        return view('murid.dashboard');
    }
}
