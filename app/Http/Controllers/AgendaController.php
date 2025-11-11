<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agenda;

class AgendaController extends Controller
{
    public function index()
    {
        $agendas = Agenda::where('status', 'aktif')
            ->orderBy('tanggal_mulai', 'asc')
            ->paginate(10);
        
        return view('agenda.index', compact('agendas'));
    }

    public function show(Agenda $agenda)
    {
        return view('agenda.show', compact('agenda'));
    }
}
