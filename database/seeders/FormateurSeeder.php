<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Crée le compte formateur initial (bootstrapping).
 *
 * Ce seeder ne tourne QU'UNE FOIS en prod pour créer le premier compte formateur,
 * qui pourra ensuite générer les codes d'invitation pour les autres.
 *
 * php artisan db:seed --class=FormateurSeeder
 */
class FormateurSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'formateur@mydocs.fr'],
            [
                'nom'       => 'Admin',
                'prenom'    => 'Formateur',
                'password'  => Hash::make('MyDocs2026!'),
                'promotion' => null,
                'role'      => 'formateur',
            ]
        );

        $this->command->info('Formateur créé : formateur@mydocs.fr / MyDocs2026!');
        $this->command->warn('⚠  Changez ce mot de passe immédiatement après connexion.');
    }
}
