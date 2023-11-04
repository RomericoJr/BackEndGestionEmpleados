<?php

namespace Database\Seeders;

use App\Models\rol;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class role extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['tipo' => 'admin'],
            ['tipo' => 'agregmiado'],
        ];
        rol::insert($roles);
    }
}
