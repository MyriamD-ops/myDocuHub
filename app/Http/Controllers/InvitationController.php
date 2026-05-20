<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use Illuminate\Http\Request;

class InvitationController extends Controller
{
    public function index()
    {
        $invitations = Invitation::with(['createur', 'utilisateur'])
            ->latest()
            ->get();

        $stats = [
            'total'    => $invitations->count(),
            'actifs'   => $invitations->filter(fn($i) => $i->getStatus() === 'actif')->count(),
            'utilises' => $invitations->filter(fn($i) => $i->getStatus() === 'utilisé')->count(),
            'expires'  => $invitations->filter(fn($i) => $i->getStatus() === 'expiré')->count(),
        ];

        return view('invitations.index', compact('invitations', 'stats'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'role'       => ['required', 'in:formateur,stagiaire'],
            'expires_at' => ['required', 'date', 'after:today'],
        ], [
            'role.required'       => 'Le rôle est obligatoire.',
            'role.in'             => 'Rôle invalide.',
            'expires_at.required' => 'La date d\'expiration est obligatoire.',
            'expires_at.after'    => 'La date d\'expiration doit être dans le futur.',
        ]);

        $invitation = Invitation::create([
            'code'       => \Illuminate\Support\Str::random(32),
            'role'       => $validated['role'],
            'created_by' => auth()->id(),
            'expires_at' => $validated['expires_at'],
        ]);

        return redirect()->route('admin.invitations.index')
            ->with('success', 'Code généré avec succès.')
            ->with('nouveau_code', $invitation->code);
    }

    public function destroy(Invitation $invitation)
    {
        // Ne pas supprimer un code déjà utilisé
        abort_if($invitation->isUsed(), 403, 'Impossible de révoquer un code déjà utilisé.');

        $invitation->delete();

        return redirect()->route('admin.invitations.index')
            ->with('success', 'Code révoqué.');
    }
}
