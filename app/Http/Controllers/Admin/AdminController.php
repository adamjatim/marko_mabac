<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mobil;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function dashboard(): View
    {
        $mobils = Mobil::all();
        return view('admin.dashboard', ['mobils' => $mobils]);
    }
}

