<?php
use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'email' => 'admin@admin.com',
            'name' => 'Jane Doe',
            'role' => 'admin',
            'password' => bcrypt('123456')
        ]);

        User::create([
            'email' => 'shane@laraspace.in',
            'name' => 'Shane White',
            'role' => 'user',
            'password' => bcrypt('123456')
        ]);

        User::create([
            'email' => 'adam@laraspace.in',
            'name' => 'Adam David',
            'role' => 'user',
            'password' => bcrypt('123456')
        ]);
    }
}
