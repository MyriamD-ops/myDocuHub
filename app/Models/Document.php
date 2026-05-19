<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Document extends Model
{
    protected $fillable = [
        'titre',
        'description',
        'fichier',
        'nom_original',
        'categorie_id',
        'user_id',
    ];

    // ── Relations ─────────────────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function categorie(): BelongsTo
    {
        return $this->belongsTo(Categorie::class);
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    /**
     * Chemin complet dans le disque privé : "documents/{uuid}.pdf"
     */
    public function cheminStockage(): string
    {
        return $this->fichier;
    }

    /**
     * Extension du fichier original : "pdf", "docx"…
     */
    public function extension(): string
    {
        return pathinfo($this->nom_original, PATHINFO_EXTENSION);
    }
}
