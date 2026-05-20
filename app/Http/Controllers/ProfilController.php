<?php

namespace App\Http\Controllers;

class ProfilController extends Controller
{
    public function index()
    {
        $documents = auth()->user()
            ->documents()
            ->with('categorie')
            ->latest()
            ->paginate(12);

        return view('profil.index', compact('documents'));
    }
}
