<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Project;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $companies = Company::factory(3)
            ->has(Project::factory()->count(2))
            ->create();

        $firstCompany = $companies->first();
        $firstProject = $firstCompany->projects->first();

        $admin = User::factory()->create([
            'name' => 'Admin ServiceHub',
            'email' => 'admin@servicehub.com',
            'password' => Hash::make('admin123'),
            'admin' => true,
        ]);

        $admin->profile()->create([
            'company_id' => $firstCompany->id,
            'project_id' => $firstProject->id,
            'phone' => '11999999999',
            'position' => 'Gerente de TI',
            'is_responsible' => true,
        ]);

        User::factory(5)->create()->each(function ($user) use ($companies) {
            $randomCompany = $companies->random();
            $user->profile()->create([
                'company_id' => $randomCompany->id,
                'project_id' => $randomCompany->projects->random()->id,
                'phone' => '11' . rand(900000000, 999999999),
                'position' => 'Técnico de Suporte',
                'is_responsible' => false,
            ]);
        });
    }
}