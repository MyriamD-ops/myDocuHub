<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\InvitationRegisterRequest;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * GET /register/{code}
     *
     * Affiche le formulaire si le code est valide.
     * Redirige avec une erreur si le code est invalide / expiré / déjà utilisé.
     */
    public function create(string $code): View|RedirectResponse
    {
        $invitation = Invitation::where('code', $code)->first();

        if (! $invitation || ! $invitation->isValide()) {
            return redirect()->route('login')
                ->withErrors(['code' => $this->messageErreurCode($invitation)]);
        }

        return view('auth.register', compact('invitation'));
    }

    /**
     * POST /register/{code}
     *
     * Valide, crée l'utilisateur, consomme le code, connecte.
     */
    public function store(InvitationRegisterRequest $request, string $code): RedirectResponse
    {
        // Double-vérification : le code peut avoir été utilisé entre la page et le submit
        $invitation = Invitation::where('code', $code)->first();

        if (! $invitation || ! $invitation->isValide()) {
            return redirect()->route('login')
                ->withErrors(['code' => $this->messageErreurCode($invitation)]);
        }

        $user = User::create([
            'nom'        => $request->nom,
            'prenom'     => $request->prenom,
            'email'      => $request->email,
            'password'   => $request->password, // hashé automatiquement via cast 'hashed'
            'promotion'  => $request->promotion,
            'role'       => $invitation->role,  // le rôle vient du code, pas du formulaire
        ]);

        $invitation->consommer($user);

        Auth::login($user);

        return redirect()->route('documents.index');
    }

    // ── Helpers privés ────────────────────────────────────────────────────────

    private function messageErreurCode(?Invitation $invitation): string
    {
        if (! $invitation)              return 'Ce code d\'invitation n\'existe pas.';
        if ($invitation->isUsed())      return 'Ce code d\'invitation a déjà été utilisé.';
        if ($invitation->isExpired())   return 'Ce code d\'invitation a expiré.';
        return 'Code d\'invitation invalide.';
    }
}
