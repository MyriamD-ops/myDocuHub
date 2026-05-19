<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invitations', function (Blueprint $table) {
            $table->id();
            $table->string('code', 64)->unique();
            $table->enum('role', ['formateur', 'stagiaire'])->default('stagiaire');
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            // used_by : l'utilisateur qui s'est inscrit via ce code
            $table->foreignId('used_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('used_at')->nullable();
            // Expiration par défaut : 31 décembre 2026
            $table->timestamp('expires_at')->default('2026-12-31 23:59:59');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invitations');
    }
};
