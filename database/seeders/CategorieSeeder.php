<?php

namespace Database\Seeders;

use App\Models\Categorie;
use Illuminate\Database\Seeder;

class CategorieSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Rapport',
            'Cours',
            'Template',
            'Exercice',
            'Correction',
            'Autre',
        ];

        foreach ($categories as $nom) {
            Categorie::firstOrCreate(['nom' => $nom]);
        }
    }
}
