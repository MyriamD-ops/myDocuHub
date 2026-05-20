<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>MyDocuHub — @yield('title', 'Plateforme documentaire')</title>

    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- FontAwesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    {{-- Google Fonts Inter --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; }
        .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        .premium-navbar-gradient {
            background: linear-gradient(to right, #0284c7 0%, #1e3a8a 35%, #581c3f 70%, #881337 100%);
        }
        .sidebar-blue { background-color: #0b4f9c; }
    </style>

    @stack('styles')
</head>
<body class="bg-slate-50 text-slate-900 min-h-screen flex flex-col antialiased">

    {{-- Topbar de navigation --}}
    <div class="premium-navbar-gradient text-slate-100 px-4 py-2.5 text-xs flex flex-wrap justify-between items-center gap-2 z-50 shadow-md">
        <div class="flex items-center gap-2">
            <a href="{{ route('documents.index') }}" class="font-extrabold tracking-tight text-white text-sm flex items-center gap-2">
                <i class="fa-solid fa-folder-open text-blue-200"></i> MyDocuHub
            </a>
            <span class="text-slate-400 hidden sm:inline">|</span>
            <span class="text-slate-300 text-[11px] hidden sm:inline">Plateforme documentaire sécurisée</span>
        </div>

        <div class="flex items-center gap-3">
            {{-- Navigation principale --}}
            <nav class="flex items-center gap-1 bg-black/30 p-1 rounded-lg border border-white/15">
                <a href="{{ route('documents.index') }}"
                   class="px-2.5 py-1 text-[11px] font-bold rounded transition-all
                          {{ request()->routeIs('documents.*') ? 'bg-white/20 text-white shadow-sm' : 'hover:bg-white/10 text-slate-300' }}">
                    <i class="fa-solid fa-folder-open mr-1"></i> Documents
                </a>
                <a href="{{ route('documents.create') }}"
                   class="px-2.5 py-1 text-[11px] font-bold rounded transition-all
                          {{ request()->routeIs('documents.create') ? 'bg-white/20 text-white shadow-sm' : 'hover:bg-white/10 text-slate-300' }}">
                    <i class="fa-solid fa-upload mr-1"></i> Déposer
                </a>
                <a href="{{ route('profil') }}"
                   class="px-2.5 py-1 text-[11px] font-bold rounded transition-all
                          {{ request()->routeIs('profil') ? 'bg-white/20 text-white shadow-sm' : 'hover:bg-white/10 text-slate-300' }}">
                    <i class="fa-solid fa-user mr-1"></i> Mon profil
                </a>
                @if(auth()->user()->isFormateur())
                <a href="{{ route('admin.invitations.index') }}"
                   class="px-2.5 py-1 text-[11px] font-bold rounded transition-all text-amber-300
                          {{ request()->routeIs('admin.*') ? 'bg-white/20 text-amber-200 shadow-sm' : 'hover:bg-white/10' }}">
                    <i class="fa-solid fa-user-shield mr-1"></i> Admin
                </a>
                @endif
            </nav>

            {{-- Utilisateur connecté + déconnexion --}}
            <div class="flex items-center gap-2 text-[11px]">
                <span class="text-slate-300 hidden sm:inline">
                    <i class="fa-solid fa-circle-user mr-1"></i>
                    {{ auth()->user()->prenom }} {{ auth()->user()->nom }}
                </span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="px-2.5 py-1 bg-black/30 hover:bg-black/50 text-slate-300 hover:text-white rounded-lg border border-white/15 font-bold transition-all">
                        <i class="fa-solid fa-power-off"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Contenu principal --}}
    <main class="flex-1 flex overflow-hidden">
        @yield('content')
    </main>

    {{-- Toast notifications --}}
    @if(session('success'))
    <div id="toast" class="fixed bottom-6 right-6 bg-slate-900 text-white px-4 py-3 rounded-xl shadow-lg flex items-center gap-2.5 z-50 text-xs font-semibold">
        <span class="text-emerald-400 text-sm"><i class="fa-solid fa-circle-check"></i></span>
        <span>{{ session('success') }}</span>
    </div>
    <script>setTimeout(() => { const t = document.getElementById('toast'); if(t) t.remove(); }, 3000);</script>
    @endif

    @if(session('error'))
    <div id="toast" class="fixed bottom-6 right-6 bg-slate-900 text-white px-4 py-3 rounded-xl shadow-lg flex items-center gap-2.5 z-50 text-xs font-semibold">
        <span class="text-amber-400 text-sm"><i class="fa-solid fa-triangle-exclamation"></i></span>
        <span>{{ session('error') }}</span>
    </div>
    <script>setTimeout(() => { const t = document.getElementById('toast'); if(t) t.remove(); }, 3000);</script>
    @endif

    @stack('scripts')
</body>
</html>
