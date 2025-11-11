<?php

namespace App\Http\Controllers;

use App\Models\Foto;
use Illuminate\Http\Request;

class GaleryController extends Controller
{
    public function index()
    {
        $fotos = Foto::all();
        return view('galeri.index', compact('fotos'));
    }
}

