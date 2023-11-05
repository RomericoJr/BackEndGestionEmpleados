<?php

namespace Database\Seeders;

use App\Models\sex;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class sexSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sexes = [
            ['genere' => 'Masculino'],
            ['genere' => 'Femenino'],
            ['genere' => 'Otro'],
        ];
        sex::insert($sexes);
    }
}
