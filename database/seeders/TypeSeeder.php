<?php

namespace Database\Seeders;

use App\Models\Type;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Type::factory()->createMany([
            ['name' => 'Music', 'slug' => 'music'],
            ['name' => 'Place', 'slug' => 'place'],
            ['name' => 'Food', 'slug' => 'food']
        ]);
    }
}
