<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Invitation extends Model
{
    protected $fillable = [
        'code',
        'role',
        'created_by',
        'used_by',
        'used_at',
        'expires_at',
    ];

    protected $casts = [
        'used_at'    => 'datetime',
        'expires_at' => 'datetime',
    ];

    // ── Factory ───────────────────────────────────────────────────────────────

    /**
     * Crée et persiste un nouveau code pour un formateur.
     * Expiration par défaut : 31 décembre 2026.
     */
    public static function generer(User $formateur, string $role = 'stagiaire'): self
    {
        return self::create([
            'code'       => Str::random(32),
            'role'       => $role,
            'created_by' => $formateur->id,
            'expires_at' => now()->parse('2026-12-31 23:59:59'),
        ]);
    }

    // ── Helpers d'état ────────────────────────────────────────────────────────

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public function isUsed(): bool
    {
        return $this->used_at !== null;
    }

    /**
     * Retourne le statut lisible : 'actif', 'utilisé', 'expiré'
     */
    public function getStatus(): string
    {
        if ($this->isUsed())    return 'utilisé';
        if ($this->isExpired()) return 'expiré';
        return 'actif';
    }

    /**
     * Un code est utilisable s'il n'est ni utilisé ni expiré.
     */
    public function isValide(): bool
    {
        return ! $this->isUsed() && ! $this->isExpired();
    }

    /**
     * Marque le code comme utilisé par l'utilisateur donné.
     */
    public function consommer(User $user): void
    {
        $this->update([
            'used_by' => $user->id,
            'used_at' => now(),
        ]);
    }

    // ── Relations ─────────────────────────────────────────────────────────────

    public function createur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function utilisateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'used_by');
    }
}
