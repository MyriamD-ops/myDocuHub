<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $query = Document::with(['user', 'categorie'])->latest();

        if ($request->filled('categorie')) {
            $query->where('categorie_id', $request->categorie);
        }

        $documents  = $query->paginate(12)->withQueryString();
        $categories = Categorie::orderBy('nom')->get();

        return view('documents.index', compact('documents', 'categories'));
    }

    public function create()
    {
        $categories = Categorie::orderBy('nom')->get();
        return view('documents.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre'        => ['required', 'string', 'max:255'],
            'description'  => ['nullable', 'string'],
            'categorie_id' => ['required', 'exists:categories,id'],
            'fichier'      => ['required', 'file', 'max:10240',
                               'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx'],
        ], [
            'titre.required'        => 'Le titre est obligatoire.',
            'categorie_id.required' => 'Veuillez choisir une catégorie.',
            'categorie_id.exists'   => 'La catégorie sélectionnée est invalide.',
            'fichier.required'      => 'Veuillez sélectionner un fichier.',
            'fichier.max'           => 'Le fichier ne doit pas dépasser 10 Mo.',
            'fichier.mimes'         => 'Format accepté : PDF, Word, Excel, PowerPoint.',
        ]);

        $file         = $request->file('fichier');
        $nomOriginal  = $file->getClientOriginalName();
        $extension    = $file->getClientOriginalExtension();
        $nomStockage  = Str::uuid() . '.' . $extension;

        $file->storeAs('documents', $nomStockage, 'private');

        Document::create([
            'titre'        => $validated['titre'],
            'description'  => $validated['description'],
            'categorie_id' => $validated['categorie_id'],
            'user_id'      => auth()->id(),
            'fichier'      => 'documents/' . $nomStockage,
            'nom_original' => $nomOriginal,
        ]);

        return redirect()->route('documents.index')
            ->with('success', 'Document publié avec succès.');
    }

    public function show(Document $document)
    {
        $document->load(['user', 'categorie']);
        return view('documents.show', compact('document'));
    }

    public function download(Document $document)
    {
        abort_unless(Storage::disk('private')->exists($document->fichier), 404);

        return Storage::disk('private')->download(
            $document->fichier,
            $document->nom_original
        );
    }

    public function destroy(Document $document)
    {
        Gate::authorize('delete', $document);

        // Suppression du fichier physique
        if (Storage::disk('private')->exists($document->fichier)) {
            Storage::disk('private')->delete($document->fichier);
        }

        $document->delete();

        return redirect()->route('documents.index')
            ->with('success', 'Document supprimé.');
    }
}
