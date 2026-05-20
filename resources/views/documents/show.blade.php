@extends('layouts.app')
@section('title', $document->titre)

@section('content')
<div class="flex-1 overflow-y-auto p-6 custom-scrollbar bg-slate-50">
    <div class="max-w-3xl mx-auto">

        {{-- Retour --}}
        <a href="{{ route('documents.index') }}"
           class="inline-flex items-center gap-1.5 text-xs text-slate-500 hover:text-slate-800 font-bold mb-5 transition">
            <i class="fa-solid fa-arrow-left"></i> Retour aux documents
        </a>

        {{-- Card principale --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">

            {{-- Header de la card --}}
            <div class="premium-navbar-gradient p-6 text-white">
                <div class="flex items-center gap-4">
                    <span class="w-14 h-14 bg-white/10 rounded-xl flex items-center justify-center text-2xl flex-shrink-0">
                        @php
                            $ext = pathinfo($document->nom_original, PATHINFO_EXTENSION);
                            $icon = match(strtolower($ext)) {
                                'pdf' => 'fa-file-pdf',
                                'doc', 'docx' => 'fa-file-word',
                                'xls', 'xlsx' => 'fa-file-excel',
                                'ppt', 'pptx' => 'fa-file-powerpoint',
                                default => 'fa-file'
                            };
                        @endphp
                        <i class="fa-solid {{ $icon }}"></i>
                    </span>
                    <div>
                        <span class="text-[10px] text-blue-200 font-bold uppercase tracking-wider">
                            {{ $document->categorie->nom }}
                        </span>
                        <h1 class="text-lg font-extrabold leading-tight">{{ $document->titre }}</h1>
                        <p class="text-xs text-blue-200 mt-1">
                            Déposé par {{ $document->user->prenom }} {{ $document->user->nom }}
                            le {{ $document->created_at->format('d/m/Y') }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Détails --}}
            <div class="p-6 space-y-4">
                @if($document->description)
                <div class="bg-slate-50 p-4 rounded-xl border border-slate-200">
                    <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Description</h3>
                    <p class="text-sm text-slate-700">{{ $document->description }}</p>
                </div>
                @endif

                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-200">
                        <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Fichier</h3>
                        <p class="text-sm text-blue-700 font-semibold">{{ $document->nom_original }}</p>
                    </div>
                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-200">
                        <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Catégorie</h3>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-blue-50 text-blue-700 border border-blue-100">
                            {{ $document->categorie->nom }}
                        </span>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-3 pt-2">
                    <a href="{{ route('documents.download', $document) }}"
                       class="flex-1 premium-navbar-gradient hover:opacity-95 text-white font-bold py-3 rounded-xl text-xs transition text-center flex items-center justify-center gap-2 shadow-md">
                        <i class="fa-solid fa-download"></i> Télécharger le document
                    </a>

                    @can('delete', $document)
                    <form method="POST" action="{{ route('documents.destroy', $document) }}"
                          onsubmit="return confirm('Supprimer ce document définitivement ?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="px-4 py-3 bg-white hover:bg-red-50 border border-slate-200 hover:border-red-300 text-slate-500 hover:text-red-600 rounded-xl text-xs font-bold transition flex items-center gap-1.5">
                            <i class="fa-solid fa-trash-can"></i> Supprimer
                        </button>
                    </form>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
