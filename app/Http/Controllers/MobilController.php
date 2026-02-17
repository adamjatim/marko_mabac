<?php

namespace App\Http\Controllers;

use App\Models\Mobil;
use Illuminate\View\View;

class MobilController extends Controller
{
    public function index(): View
    {
        $mobils = Mobil::all();
        // dd($mobils);
        return view('mobil.index', ['mobils' => $mobils]);
    }

    public function show(Mobil $mobil): View
    {
        return view('mobil.show', ['mobil' => $mobil]);
    }
}
