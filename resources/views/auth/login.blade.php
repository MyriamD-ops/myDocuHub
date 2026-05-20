@extends('layouts.guest')
@section('title', 'Connexion')

@section('content')
<div class="max-w-md w-full bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">

    {{-- Header dégradé --}}
    <div class="premium-navbar-gradient p-6 text-center text-white">
        <span class="w-12 h-12 bg-white/10 text-white rounded-xl inline-flex items-center justify-center text-lg mb-3 shadow-inner">
            <i class="fa-solid fa-unlock-keyhole"></i>
        </span>
        <h2 class="text-xl font-extrabold tracking-tight">Accéder à MyDocuHub</h2>
        <p class="text-xs text-blue-100 mt-1">Connectez-vous pour accéder aux ressources partagées.</p>
    </div>

    {{-- Erreurs --}}
    @if($errors->any())
    <div class="mx-6 mt-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-xs font-semibold flex items-center gap-2">
        <i class="fa-solid fa-circle-exclamation text-red-500"></i>
        {{ $errors->first() }}
    </div>
    @endif

    {{-- Formulaire --}}
    <form method="POST" action="{{ route('login') }}" class="p-6 space-y-4">
        @csrf

        {{-- Email --}}
        <div>
            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">
                Adresse email
            </label>
            <input type="email" name="email" value="{{ old('email') }}" required autofocus
                   placeholder="vous@exemple.fr"
                   class="w-full bg-slate-50 border border-slate-200 focus:border-blue-600 focus:ring-1 focus:ring-blue-600 rounded-xl p-3.5 text-sm transition outline-none">
        </div>

        {{-- Mot de passe --}}
        <div>
            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">
                Mot de passe
            </label>
            <input type="password" name="password" required
                   placeholder="••••••••"
                   class="w-full bg-slate-50 border border-slate-200 focus:border-blue-600 focus:ring-1 focus:ring-blue-600 rounded-xl p-3.5 text-sm transition outline-none">
        </div>

        {{-- Se souvenir de moi --}}
        <div class="flex items-center gap-2">
            <input type="checkbox" name="remember" id="remember"
                   class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
            <label for="remember" class="text-xs text-slate-500 cursor-pointer">Se souvenir de moi</label>
        </div>

        <button type="submit"
                class="w-full premium-navbar-gradient hover:opacity-95 text-white font-bold py-3 rounded-xl text-xs transition duration-200 shadow-md">
            Se connecter
        </button>

        {{-- Info inscription --}}
        <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 text-[11px] text-slate-600">
            <p class="font-extrabold text-slate-700 flex items-center gap-1 mb-1">
                <i class="fa-solid fa-circle-info text-blue-600"></i> Première connexion ?
            </p>
            <p>L'inscription se fait uniquement via un lien d'invitation envoyé par votre formateur.</p>
        </div>
    </form>
</div>
@endsection
