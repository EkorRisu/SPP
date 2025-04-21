<?php
namespace App\Http\Controllers;

class DashboardController extends Controller
{
    public function guru()
    {
        return view('dashboard.guru');
    }

    public function murid()
    {
        return view('dashboard.murid');
    }
}
