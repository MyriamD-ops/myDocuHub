<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'password',
        'promotion',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // ── Accesseurs ────────────────────────────────────────────────────────────

    /**
     * Nom complet : "Marie Dupont"
     */
    public function getNomCompletAttribute(): string
    {
        return "{$this->prenom} {$this->nom}";
    }

    // ── Helpers de rôle ───────────────────────────────────────────────────────

    public function isFormateur(): bool
    {
        return $this->role === 'formateur';
    }

    public function isStagiaire(): bool
    {
        return $this->role === 'stagiaire';
    }

    // ── Relations ─────────────────────────────────────────────────────────────

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    /** Codes générés par ce formateur */
    public function invitationsGenerees(): HasMany
    {
        return $this->hasMany(Invitation::class, 'created_by');
    }
}
