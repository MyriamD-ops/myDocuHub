@extends('layouts.guest')
@section('title', 'Inscription')

@section('content')
<div class="max-w-md w-full bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">

    {{-- Header dégradé avec rôle --}}
    <div class="premium-navbar-gradient p-6 text-center text-white">
        <span class="w-12 h-12 bg-white/10 text-white rounded-xl inline-flex items-center justify-center text-lg mb-3 shadow-inner">
            @if($invitation->role === 'formateur')
                <i class="fa-solid fa-chalkboard-user"></i>
            @else
                <i class="fa-solid fa-graduation-cap"></i>
            @endif
        </span>
        <h2 class="text-xl font-extrabold tracking-tight">Créer votre compte</h2>
        <p class="text-xs text-blue-100 mt-1">
            Inscription en tant que
            <span class="font-extrabold text-white bg-white/20 px-2 py-0.5 rounded ml-1">
                {{ ucfirst($invitation->role) }}
            </span>
        </p>
    </div>

    {{-- Erreurs --}}
    @if($errors->any())
    <div class="mx-6 mt-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-xs font-semibold flex items-center gap-2">
        <i class="fa-solid fa-circle-exclamation text-red-500"></i>
        <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    <form method="POST" action="{{ route('register', $invitation->code) }}" class="p-6 space-y-4">
        @csrf

        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1.5">Prénom</label>
                <input type="text" name="prenom" value="{{ old('prenom') }}" required autofocus
                       placeholder="Marie"
                       class="w-full bg-slate-50 border border-slate-200 focus:border-blue-600 focus:ring-1 focus:ring-blue-600 rounded-xl p-3 text-sm transition outline-none">
            </div>
            <div>
                <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1.5">Nom</label>
                <input type="text" name="nom" value="{{ old('nom') }}" required
                       placeholder="Dupont"
                       class="w-full bg-slate-50 border border-slate-200 focus:border-blue-600 focus:ring-1 focus:ring-blue-600 rounded-xl p-3 text-sm transition outline-none">
            </div>
        </div>

        <div>
            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1.5">Adresse email</label>
            <input type="email" name="email" value="{{ old('email') }}" required
                   placeholder="vous@exemple.fr"
                   class="w-full bg-slate-50 border border-slate-200 focus:border-blue-600 focus:ring-1 focus:ring-blue-600 rounded-xl p-3 text-sm transition outline-none">
        </div>

        <div>
            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1.5">Promotion (optionnel)</label>
            <input type="text" name="promotion" value="{{ old('promotion') }}"
                   placeholder="Ex : CDA 2025-2026"
                   class="w-full bg-slate-50 border border-slate-200 focus:border-blue-600 focus:ring-1 focus:ring-blue-600 rounded-xl p-3 text-sm transition outline-none">
        </div>

        <div>
            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1.5">Mot de passe</label>
            <input type="password" name="password" required
                   placeholder="••••••••"
                   class="w-full bg-slate-50 border border-slate-200 focus:border-blue-600 focus:ring-1 focus:ring-blue-600 rounded-xl p-3 text-sm transition outline-none">
        </div>

        <div>
            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1.5">Confirmer le mot de passe</label>
            <input type="password" name="password_confirmation" required
                   placeholder="••••••••"
                   class="w-full bg-slate-50 border border-slate-200 focus:border-blue-600 focus:ring-1 focus:ring-blue-600 rounded-xl p-3 text-sm transition outline-none">
        </div>

        {{-- Info rôle --}}
        <div class="bg-slate-50 p-3 rounded-xl border border-slate-200 text-[11px] text-slate-600 flex items-center gap-2">
            <i class="fa-solid fa-circle-info text-blue-600"></i>
            Votre rôle <strong>{{ ucfirst($invitation->role) }}</strong> est défini par le code d'invitation.
            Code valide jusqu'au {{ \Carbon\Carbon::parse($invitation->expires_at)->format('d/m/Y') }}.
        </div>

        <button type="submit"
                class="w-full premium-navbar-gradient hover:opacity-95 text-white font-bold py-3 rounded-xl text-xs transition duration-200 shadow-md">
            <i class="fa-solid fa-user-plus mr-1"></i> Créer mon compte
        </button>

        <div class="text-center">
            <a href="{{ route('login') }}" class="text-xs text-slate-500 hover:text-slate-700">
                Déjà inscrit ? Se connecter
            </a>
        </div>
    </form>
</div>
@endsection
