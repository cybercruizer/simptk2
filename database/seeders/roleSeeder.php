<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class roleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \Spatie\Permission\Models\Role::create(['name' => 'super_admin']);
        \Spatie\Permission\Models\Role::create(['name' => 'cabdin']);
        \Spatie\Permission\Models\Role::create(['name' => 'induk']);
        \Spatie\Permission\Models\Role::create(['name' => 'operator']);
    }
}
