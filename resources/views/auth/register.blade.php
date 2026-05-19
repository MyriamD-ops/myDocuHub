<x-guest-layout>
    {{-- En-tête --}}
    <div class="mb-6 text-center">
        <h2 class="text-xl font-semibold text-gray-800">Créer votre compte</h2>
        <p class="mt-1 text-sm text-gray-500">
            Inscription en tant que
            <span class="font-medium
                {{ $invitation->role === 'formateur' ? 'text-blue-700' : 'text-emerald-700' }}">
                {{ ucfirst($invitation->role) }}
            </span>
        </p>
    </div>

    <form method="POST" action="{{ route('register', $invitation->code) }}">
        @csrf

        {{-- Nom --}}
        <div>
            <x-input-label for="nom" value="Nom" />
            <x-text-input id="nom" class="block mt-1 w-full"
                type="text" name="nom" :value="old('nom')" required autofocus />
            <x-input-error :messages="$errors->get('nom')" class="mt-2" />
        </div>

        {{-- Prénom --}}
        <div class="mt-4">
            <x-input-label for="prenom" value="Prénom" />
            <x-text-input id="prenom" class="block mt-1 w-full"
                type="text" name="prenom" :value="old('prenom')" required />
            <x-input-error :messages="$errors->get('prenom')" class="mt-2" />
        </div>

        {{-- Email --}}
        <div class="mt-4">
            <x-input-label for="email" value="Adresse email" />
            <x-text-input id="email" class="block mt-1 w-full"
                type="email" name="email" :value="old('email')" required />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        {{-- Promotion (optionnel) --}}
        <div class="mt-4">
            <x-input-label for="promotion" value="Promotion (optionnel)" />
            <x-text-input id="promotion" class="block mt-1 w-full"
                type="text" name="promotion" :value="old('promotion')" />
            <x-input-error :messages="$errors->get('promotion')" class="mt-2" />
        </div>

        {{-- Mot de passe --}}
        <div class="mt-4">
            <x-input-label for="password" value="Mot de passe" />
            <x-text-input id="password" class="block mt-1 w-full"
                type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        {{-- Confirmation --}}
        <div class="mt-4">
            <x-input-label for="password_confirmation" value="Confirmer le mot de passe" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                type="password" name="password_confirmation" required />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        {{-- Rôle en lecture seule (info) --}}
        <input type="hidden" name="_role_info" value="{{ $invitation->role }}" />

        <div class="flex items-center justify-end mt-6">
            <a class="text-sm text-gray-600 hover:text-gray-900 mr-4"
               href="{{ route('login') }}">
                Déjà inscrit ?
            </a>

            <x-primary-button>
                Créer mon compte
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
