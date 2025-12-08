<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use Illuminate\View\View;

class KriteriaController extends Controller
{
    public function index(): View
    {
        $kriterias = Kriteria::all();
        return view('kriteria.index', ['kriterias' => $kriterias]);
    }
}
