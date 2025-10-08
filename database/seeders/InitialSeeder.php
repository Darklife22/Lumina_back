<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Area;
use App\Models\Nivel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class InitialSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::firstOrCreate(
            ['email' => 'admin@ohsansi.local'],
            ['name' => 'Administrador', 'password' => Hash::make('admin123'), 'role' => 'admin']
        );

        // Áreas demo
        foreach (['Matemática','Robótica','Informática'] as $a) {
            Area::firstOrCreate(['nombre' => $a]);
        }

        // Niveles demo
        foreach (['Inicial','Intermedio','Avanzado'] as $n) {
            Nivel::firstOrCreate(['nombre' => $n]);
        }
    }
}
