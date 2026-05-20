@extends('layouts.app')
@section('title', 'Déposer un document')

@section('content')
<div class="flex-1 overflow-y-auto p-6 custom-scrollbar bg-slate-50">
    <div class="max-w-2xl mx-auto">

        <a href="{{ route('documents.index') }}"
           class="inline-flex items-center gap-1.5 text-xs text-slate-500 hover:text-slate-800 font-bold mb-5 transition">
            <i class="fa-solid fa-arrow-left"></i> Retour aux documents
        </a>

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">

            {{-- Header --}}
            <div class="premium-navbar-gradient p-5 text-white flex items-center gap-3">
                <span class="w-9 h-9 bg-white/10 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-upload"></i>
                </span>
                <div>
                    <h2 class="font-extrabold text-sm">Déposer un document</h2>
                    <p class="text-[11px] text-blue-200">Le document sera accessible à tous les membres de la plateforme.</p>
                </div>
            </div>

            {{-- Erreurs --}}
            @if($errors->any())
            <div class="mx-6 mt-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-xs font-semibold">
                <ul class="space-y-1">
                    @foreach($errors->all() as $e)
                    <li class="flex items-center gap-1.5">
                        <i class="fa-solid fa-circle-exclamation"></i> {{ $e }}
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form method="POST" action="{{ route('documents.store') }}" enctype="multipart/form-data" class="p-6 space-y-5">
                @csrf

                {{-- Zone de dépôt --}}
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2">
                        Fichier <span class="text-red-500">*</span>
                    </label>
                    <label for="fichier"
                           class="flex flex-col items-center justify-center border-2 border-dashed border-slate-200 rounded-xl p-8 cursor-pointer
                                  hover:border-blue-400 hover:bg-blue-50/50 transition-all duration-200 group">
                        <i class="fa-solid fa-cloud-arrow-up text-3xl text-slate-300 group-hover:text-blue-400 mb-3 transition"></i>
                        <span class="text-sm font-bold text-slate-500 group-hover:text-blue-600 transition">
                            Glissez votre fichier ici
                        </span>
                        <span class="text-[11px] text-slate-400 mt-1">ou cliquez pour parcourir — PDF, Word, Excel, PowerPoint — 10 Mo max</span>
                        <span id="file-name" class="mt-3 text-xs text-blue-700 font-semibold hidden"></span>
                        <input type="file" name="fichier" id="fichier" required class="hidden"
                               accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx"
                               onchange="document.getElementById('file-name').textContent = this.files[0]?.name; document.getElementById('file-name').classList.remove('hidden')">
                    </label>
                </div>

                {{-- Titre --}}
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">
                        Titre <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="titre" value="{{ old('titre') }}" required
                           placeholder="Ex : Mémento SQL — Les jointures complexes"
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-sm focus:ring-1 focus:ring-blue-600 focus:border-blue-600 outline-none transition">
                </div>

                {{-- Catégorie --}}
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">
                        Catégorie <span class="text-red-500">*</span>
                    </label>
                    <select name="categorie_id" required
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-sm focus:ring-1 focus:ring-blue-600 focus:border-blue-600 outline-none transition">
                        <option value="">Choisir une catégorie…</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('categorie_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->nom }}
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- Description --}}
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">
                        Description (optionnel)
                    </label>
                    <textarea name="description" rows="3"
                              placeholder="Décrivez brièvement le contenu du document…"
                              class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-sm focus:ring-1 focus:ring-blue-600 focus:border-blue-600 outline-none transition resize-none">{{ old('description') }}</textarea>
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-3 justify-end pt-2 border-t border-slate-100">
                    <a href="{{ route('documents.index') }}"
                       class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold rounded-xl text-xs transition">
                        Annuler
                    </a>
                    <button type="submit"
                            class="px-6 py-2.5 premium-navbar-gradient hover:opacity-95 text-white font-bold rounded-xl text-xs transition shadow-md flex items-center gap-1.5">
                        <i class="fa-solid fa-circle-check"></i> Publier le document
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
