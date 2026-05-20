@extends('layouts.app')
@section('title', 'Mon profil')

@section('content')
<div class="flex-1 overflow-y-auto p-6 custom-scrollbar bg-slate-50">
    <div class="max-w-5xl mx-auto space-y-6">

        {{-- Carte profil --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="premium-navbar-gradient p-6 text-white flex items-center gap-5">
                <div class="w-16 h-16 bg-white/20 rounded-xl flex items-center justify-center text-2xl font-extrabold flex-shrink-0">
                    {{ strtoupper(substr(auth()->user()->prenom, 0, 1)) }}{{ strtoupper(substr(auth()->user()->nom, 0, 1)) }}
                </div>
                <div>
                    <h1 class="text-lg font-extrabold">{{ auth()->user()->prenom }} {{ auth()->user()->nom }}</h1>
                    <p class="text-xs text-blue-200 mt-0.5">{{ auth()->user()->email }}</p>
                    <div class="flex items-center gap-2 mt-2">
                        @if(auth()->user()->isFormateur())
                        <span class="bg-white/20 text-white text-[10px] font-bold px-2.5 py-1 rounded-lg border border-white/20">
                            <i class="fa-solid fa-chalkboard-user mr-1 text-purple-300"></i> Formateur
                        </span>
                        @else
                        <span class="bg-white/20 text-white text-[10px] font-bold px-2.5 py-1 rounded-lg border border-white/20">
                            <i class="fa-solid fa-graduation-cap mr-1 text-emerald-300"></i> Stagiaire
                        </span>
                        @endif
                        @if(auth()->user()->promotion)
                        <span class="text-[11px] text-blue-200">
                            <i class="fa-solid fa-users mr-1"></i>{{ auth()->user()->promotion }}
                        </span>
                        @endif
                    </div>
                </div>
                <div class="ml-auto text-right">
                    <div class="text-2xl font-extrabold">{{ $documents->total() }}</div>
                    <div class="text-[11px] text-blue-200">document{{ $documents->total() > 1 ? 's' : '' }} déposé{{ $documents->total() > 1 ? 's' : '' }}</div>
                </div>
            </div>
        </div>

        {{-- Mes documents --}}
        <div>
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-sm font-extrabold text-slate-800 flex items-center gap-2">
                    <i class="fa-solid fa-folder-open text-blue-600"></i>
                    Mes documents ({{ $documents->total() }})
                </h2>
                <a href="{{ route('documents.create') }}"
                   class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-4 py-2 rounded-lg text-xs transition flex items-center gap-1.5 shadow-sm">
                    <i class="fa-solid fa-plus"></i> Déposer
                </a>
            </div>

            @if($documents->isEmpty())
            <div class="text-center py-12 bg-white rounded-xl border border-dashed border-slate-300">
                <i class="fa-solid fa-folder-open text-4xl text-slate-300 mb-3 block"></i>
                <p class="text-slate-400 text-sm font-semibold">Vous n'avez pas encore déposé de document.</p>
                <a href="{{ route('documents.create') }}" class="mt-3 inline-block text-xs text-blue-600 font-bold hover:underline">
                    Déposez votre premier document →
                </a>
            </div>
            @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($documents as $doc)
                <div class="bg-white rounded-xl border border-slate-200 p-5 hover:shadow-md hover:border-blue-300/50 transition duration-300 flex flex-col justify-between">
                    <div>
                        <div class="flex justify-between items-start mb-3">
                            <span class="w-9 h-9 rounded-lg bg-blue-50 text-blue-700 flex items-center justify-center text-sm">
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
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded border bg-slate-50 text-slate-500 border-slate-200">
                                {{ $doc->categorie->nom }}
                            </span>
                        </div>
                        <h4 class="font-extrabold text-slate-800 text-sm mb-1 leading-tight">{{ $doc->titre }}</h4>
                        <p class="text-[11px] text-slate-400">Déposé le {{ $doc->created_at->format('d/m/Y') }}</p>
                    </div>
                    <div class="flex items-center gap-2 mt-4 pt-3 border-t border-slate-100">
                        <a href="{{ route('documents.download', $doc) }}"
                           class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 rounded-lg text-[10px] transition text-center flex items-center justify-center gap-1">
                            <i class="fa-solid fa-download"></i> Télécharger
                        </a>
                        <form method="POST" action="{{ route('documents.destroy', $doc) }}"
                              onsubmit="return confirm('Supprimer ce document définitivement ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="px-3 py-2 bg-white hover:bg-red-50 border border-slate-200 hover:border-red-300 text-slate-400 hover:text-red-600 rounded-lg text-[10px] font-bold transition">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($documents->hasPages())
            <div class="mt-6 flex justify-center">
                {{ $documents->links() }}
            </div>
            @endif
            @endif
        </div>
    </div>
</div>
@endsection
