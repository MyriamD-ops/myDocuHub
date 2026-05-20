@extends('layouts.app')
@section('title', 'Gestion des invitations')

@section('content')
<div class="flex-1 flex overflow-hidden">

    {{-- Sidebar Admin --}}
    <aside class="w-64 sidebar-blue text-white flex-col hidden md:flex border-r border-blue-900/20 shadow-xl flex-shrink-0">
        <div class="p-5 border-b border-white/10">
            <span class="text-lg font-extrabold tracking-tight flex items-center gap-2">
                <span class="w-2.5 h-2.5 bg-emerald-400 rounded-full animate-pulse shadow-[0_0_10px_rgba(52,211,153,0.8)]"></span>
                MyDocuHub Admin
            </span>
            <p class="text-[10px] text-blue-200 font-mono mt-1">v1.0.0 — Espace formateur</p>
        </div>
        <nav class="flex-grow p-4 space-y-1.5">
            <a href="{{ route('admin.invitations.index') }}"
               class="flex items-center gap-3 p-2.5 rounded-lg bg-white/10 text-white font-semibold text-sm border border-white/5">
                <i class="fa-solid fa-key text-blue-200 w-5"></i> Codes d'invitation
            </a>
            <a href="{{ route('documents.index') }}"
               class="flex items-center gap-3 p-2.5 rounded-lg hover:bg-white/5 text-blue-100 hover:text-white transition text-sm">
                <i class="fa-solid fa-folder-open text-blue-300 w-5"></i> Documents
            </a>
            <a href="{{ route('profil') }}"
               class="flex items-center gap-3 p-2.5 rounded-lg hover:bg-white/5 text-blue-100 hover:text-white transition text-sm">
                <i class="fa-solid fa-user text-blue-300 w-5"></i> Mon profil
            </a>
        </nav>
        <div class="p-4 border-t border-white/10">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="w-full flex items-center justify-center gap-2 py-2.5 bg-black/20 hover:bg-black/40 text-blue-200 hover:text-white rounded-lg text-xs font-bold transition border border-white/10">
                    <i class="fa-solid fa-power-off"></i> Déconnexion
                </button>
            </form>
        </div>
    </aside>

    {{-- Contenu principal --}}
    <main class="flex-grow overflow-y-auto p-6 lg:p-8 custom-scrollbar bg-slate-50">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-200 mb-6">
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">Console Administrateur</h1>
                <p class="text-xs text-slate-500 mt-1">Génération et gestion des codes d'accès pour les formateurs et stagiaires.</p>
            </div>
            <span class="bg-emerald-50 text-emerald-700 text-xs font-bold px-3 py-1.5 rounded-lg border border-emerald-100 flex items-center gap-1.5 w-fit">
                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span> Base de données connectée
            </span>
        </div>

        {{-- Stats --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm hover:border-blue-300 transition-all duration-300">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Codes actifs</span>
                <h3 class="text-2xl font-black text-slate-800 mt-1">{{ $stats['actifs'] }}</h3>
            </div>
            <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm hover:border-blue-300 transition-all duration-300">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Codes utilisés</span>
                <h3 class="text-2xl font-black text-slate-800 mt-1">{{ $stats['utilises'] }}</h3>
            </div>
            <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm hover:border-blue-300 transition-all duration-300">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Codes expirés</span>
                <h3 class="text-2xl font-black text-slate-800 mt-1">{{ $stats['expires'] }}</h3>
            </div>
            <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm hover:border-blue-300 transition-all duration-300">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total générés</span>
                <h3 class="text-2xl font-black text-slate-800 mt-1">{{ $stats['total'] }}</h3>
            </div>
        </div>

        {{-- Générateurs de codes --}}
        <div class="mb-4">
            <h2 class="text-xs font-black text-slate-800 uppercase tracking-wider flex items-center gap-2">
                <i class="fa-solid fa-key text-blue-600"></i> Générateur de codes d'accès
            </h2>
            <p class="text-xs text-slate-500">Créez des codes d'invitation pour chaque rôle.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">

            {{-- Formulaire Stagiaire --}}
            <div class="bg-white rounded-xl p-6 border border-slate-200 shadow-sm hover:shadow-md transition">
                <div class="flex items-center gap-3 pb-3 mb-4 border-b border-slate-100">
                    <span class="w-9 h-9 rounded-lg bg-blue-50 text-blue-700 flex items-center justify-center text-sm font-bold border border-blue-100">
                        <i class="fa-solid fa-user-graduate"></i>
                    </span>
                    <div>
                        <h3 class="font-extrabold text-slate-800 text-sm">Code d'accès STAGIAIRE</h3>
                        <p class="text-[11px] text-slate-400">Pour intégrer la plateforme en tant que stagiaire</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('admin.invitations.store') }}" class="space-y-4">
                    @csrf
                    <input type="hidden" name="role" value="stagiaire">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Date d'expiration</label>
                        <input type="date" name="expires_at" value="{{ now()->endOfYear()->format('Y-m-d') }}" required
                               class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-xs focus:ring-1 focus:ring-blue-500 focus:border-blue-500 outline-none">
                    </div>
                    <button type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 rounded-lg text-xs transition flex items-center justify-center gap-1.5 shadow-sm">
                        <i class="fa-solid fa-plus-circle"></i> Créer code stagiaire
                    </button>
                </form>
            </div>

            {{-- Formulaire Formateur --}}
            <div class="bg-white rounded-xl p-6 border border-slate-200 shadow-sm hover:shadow-md transition">
                <div class="flex items-center gap-3 pb-3 mb-4 border-b border-slate-100">
                    <span class="w-9 h-9 rounded-lg bg-purple-50 text-purple-700 flex items-center justify-center text-sm font-bold border border-purple-100">
                        <i class="fa-solid fa-chalkboard-user"></i>
                    </span>
                    <div>
                        <h3 class="font-extrabold text-slate-800 text-sm">Code d'accès FORMATEUR</h3>
                        <p class="text-[11px] text-slate-400">Autorise l'accès à l'espace administrateur</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('admin.invitations.store') }}" class="space-y-4">
                    @csrf
                    <input type="hidden" name="role" value="formateur">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Date d'expiration</label>
                        <input type="date" name="expires_at" value="{{ now()->endOfYear()->format('Y-m-d') }}" required
                               class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-xs focus:ring-1 focus:ring-purple-500 focus:border-purple-500 outline-none">
                    </div>
                    <button type="submit"
                            class="w-full bg-slate-900 hover:bg-slate-800 text-white font-bold py-2.5 rounded-lg text-xs transition flex items-center justify-center gap-1.5 shadow-sm">
                        <i class="fa-solid fa-shield-halved text-purple-400"></i> Créer code formateur
                    </button>
                </form>
            </div>
        </div>

        {{-- Code généré flash --}}
        @if(session('nouveau_code'))
        <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 mb-6 flex items-center justify-between gap-4">
            <div>
                <p class="text-xs font-bold text-emerald-800 mb-1">
                    <i class="fa-solid fa-circle-check mr-1"></i> Nouveau code généré !
                </p>
                <p class="text-xs text-emerald-700">Partagez ce lien d'inscription :</p>
                <code class="font-mono text-sm text-emerald-900 font-bold">
                    {{ url('/register/' . session('nouveau_code')) }}
                </code>
            </div>
            <button onclick="copyToClipboard('{{ url('/register/' . session('nouveau_code')) }}')"
                    class="px-3 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold transition flex items-center gap-1.5 flex-shrink-0">
                <i class="fa-regular fa-copy"></i> Copier
            </button>
        </div>
        @endif

        {{-- Tableau des codes --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100">
                <h3 class="font-extrabold text-slate-800 text-sm">Historique des codes</h3>
                <p class="text-[11px] text-slate-400 mt-0.5">Tous les codes générés par les formateurs de la plateforme.</p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-slate-400 text-[10px] tracking-wider font-bold uppercase border-b border-slate-200">
                            <th class="py-3 px-5">Rôle</th>
                            <th class="py-3 px-5">Lien d'inscription</th>
                            <th class="py-3 px-5">Expiration</th>
                            <th class="py-3 px-5">Statut</th>
                            <th class="py-3 px-5">Généré par</th>
                            <th class="py-3 px-5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs divide-y divide-slate-100 font-medium">
                        @forelse($invitations as $inv)
                        <tr class="hover:bg-slate-50/50">
                            <td class="py-3 px-5">
                                @if($inv->role === 'formateur')
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold bg-purple-50 text-purple-700 border border-purple-100">
                                    <i class="fa-solid fa-chalkboard-user"></i> Formateur
                                </span>
                                @else
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-100">
                                    <i class="fa-solid fa-graduation-cap"></i> Stagiaire
                                </span>
                                @endif
                            </td>
                            <td class="py-3 px-5">
                                <div class="flex items-center gap-2">
                                    <code class="font-mono bg-slate-100 px-1.5 py-0.5 rounded text-slate-600 text-[10px]">
                                        /register/{{ Str::limit($inv->code, 12) }}…
                                    </code>
                                    <button onclick="copyToClipboard('{{ url('/register/' . $inv->code) }}')"
                                            class="text-slate-400 hover:text-slate-600 transition" title="Copier le lien">
                                        <i class="fa-regular fa-copy"></i>
                                    </button>
                                </div>
                            </td>
                            <td class="py-3 px-5 text-slate-500">
                                {{ \Carbon\Carbon::parse($inv->expires_at)->format('d/m/Y') }}
                            </td>
                            <td class="py-3 px-5">
                                @php $status = $inv->getStatus(); @endphp
                                @if($status === 'actif')
                                <span class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-100">
                                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span> Actif
                                </span>
                                @elseif($status === 'utilisé')
                                <span class="inline-flex items-center gap-1 text-[10px] font-bold text-slate-500 bg-slate-100 px-2 py-0.5 rounded border border-slate-200">
                                    <i class="fa-solid fa-check"></i> Utilisé
                                </span>
                                @else
                                <span class="inline-flex items-center gap-1 text-[10px] font-bold text-red-600 bg-red-50 px-2 py-0.5 rounded border border-red-100">
                                    <i class="fa-solid fa-clock"></i> Expiré
                                </span>
                                @endif
                            </td>
                            <td class="py-3 px-5 text-slate-500">
                                {{ $inv->createur->prenom }} {{ $inv->createur->nom }}
                            </td>
                            <td class="py-3 px-5 text-right">
                                @if($inv->getStatus() !== 'utilisé')
                                <form method="POST" action="{{ route('admin.invitations.destroy', $inv) }}"
                                      onsubmit="return confirm('Révoquer ce code ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-slate-400 hover:text-red-600 font-bold text-xs transition">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                                @else
                                <span class="text-slate-200"><i class="fa-solid fa-trash-can"></i></span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400 text-xs">
                                Aucun code généré pour l'instant.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

@push('scripts')
<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        const toast = document.createElement('div');
        toast.className = 'fixed bottom-6 right-6 bg-slate-900 text-white px-4 py-3 rounded-xl shadow-lg flex items-center gap-2.5 z-50 text-xs font-semibold';
        toast.innerHTML = '<span class="text-emerald-400"><i class="fa-solid fa-circle-check"></i></span><span>Lien copié !</span>';
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 2500);
    });
}
</script>
@endpush
@endsection
