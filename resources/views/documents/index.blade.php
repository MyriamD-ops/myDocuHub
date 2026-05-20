@extends('layouts.app')
@section('title', 'Documents')

@section('content')
<div class="flex-1 flex flex-col overflow-hidden">

    {{-- Header --}}
    <header class="premium-navbar-gradient text-white py-4 px-6 flex flex-wrap justify-between items-center gap-4 shadow-md">
        <div class="flex items-center gap-3">
            <span class="bg-white/10 border border-white/10 px-2.5 py-1 rounded-lg text-xs font-bold uppercase tracking-wide">
                <i class="fa-solid fa-folder-open mr-1 text-blue-200"></i> Documents partagés
            </span>
            <span class="text-white/20">|</span>
            <span class="text-sm font-extrabold tracking-tight text-white/80">
                {{ $documents->total() }} ressource{{ $documents->total() > 1 ? 's' : '' }} disponible{{ $documents->total() > 1 ? 's' : '' }}
            </span>
        </div>
        <a href="{{ route('documents.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-4 py-2.5 rounded-lg text-xs transition flex items-center gap-1.5 shadow-sm border border-blue-500/20">
            <i class="fa-solid fa-circle-plus"></i> Déposer un document
        </a>
    </header>

    <div class="flex-1 overflow-y-auto p-6 custom-scrollbar bg-slate-50">

        {{-- Filtres par catégorie --}}
        <div class="flex flex-wrap gap-2 mb-6">
            <a href="{{ route('documents.index') }}"
               class="px-3 py-1.5 rounded-full text-xs font-bold border transition-all
                      {{ !request('categorie') ? 'bg-slate-900 text-white border-slate-900' : 'bg-white text-slate-500 border-slate-200 hover:border-slate-400' }}">
                Tous
            </a>
            @foreach($categories as $cat)
            <a href="{{ route('documents.index', ['categorie' => $cat->id]) }}"
               class="px-3 py-1.5 rounded-full text-xs font-bold border transition-all
                      {{ request('categorie') == $cat->id ? 'bg-slate-900 text-white border-slate-900' : 'bg-white text-slate-500 border-slate-200 hover:border-slate-400' }}">
                {{ $cat->nom }}
            </a>
            @endforeach
        </div>

        {{-- Grille des documents --}}
        @if($documents->isEmpty())
        <div class="text-center py-16 bg-white rounded-xl border border-dashed border-slate-300">
            <i class="fa-solid fa-folder-open text-4xl text-slate-300 mb-3 block"></i>
            <p class="text-slate-400 text-sm font-semibold">Aucun document disponible pour l'instant.</p>
            <a href="{{ route('documents.create') }}" class="mt-3 inline-block text-xs text-blue-600 font-bold hover:underline">
                Déposez le premier document →
            </a>
        </div>
        @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
            @foreach($documents as $doc)
            <div class="bg-white rounded-xl border border-slate-200 p-5 hover:shadow-md hover:border-blue-300/50 transition duration-300 flex flex-col justify-between">
                <div>
                    {{-- Icône + badges --}}
                    <div class="flex justify-between items-start mb-3">
                        <span class="w-10 h-10 rounded-lg bg-blue-50 text-blue-700 flex items-center justify-center">
                            @php
                                $ext = pathinfo($doc->nom_original, PATHINFO_EXTENSION);
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
                        <span class="text-[10px] font-extrabold uppercase tracking-wide px-2 py-0.5 rounded border bg-slate-50 text-slate-500 border-slate-200">
                            {{ $doc->categorie->nom }}
                        </span>
                    </div>

                    <h4 class="font-extrabold text-slate-800 text-sm mb-1 leading-tight">{{ $doc->titre }}</h4>
                    @if($doc->description)
                    <p class="text-xs text-slate-500 line-clamp-2 mb-3">{{ $doc->description }}</p>
                    @endif
                </div>

                <div class="pt-3 border-t border-slate-100">
                    <div class="flex items-center justify-between text-[10px] text-slate-400 mb-3">
                        <span><i class="fa-solid fa-user mr-1"></i>{{ $doc->user->prenom }} {{ $doc->user->nom }}</span>
                        <span>{{ $doc->created_at->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('documents.download', $doc) }}"
                           class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 rounded-lg text-[10px] transition text-center flex items-center justify-center gap-1">
                            <i class="fa-solid fa-download"></i> Télécharger
                        </a>
                        <a href="{{ route('documents.show', $doc) }}"
                           class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-lg text-[10px] font-bold transition">
                            <i class="fa-solid fa-eye"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($documents->hasPages())
        <div class="mt-8 flex justify-center">
            {{ $documents->links() }}
        </div>
        @endif
        @endif
    </div>
</div>
@endsection
