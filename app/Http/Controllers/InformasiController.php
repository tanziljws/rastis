<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Informasi;

class InformasiController extends Controller
{
    public function index()
    {
        $informasis = Informasi::where('status', 'published')
            ->orderBy('tanggal_publish', 'desc')
            ->paginate(10);
        
        return view('informasi.index', compact('informasis'));
    }

    public function show(Informasi $informasi)
    {
        return view('informasi.show', compact('informasi'));
    }
}
