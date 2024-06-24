<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'username' => 'Bintang Admin',
                'email' => 'muhbintang650@gmail.com',
                'email_verified_at' => now(),
                'password' => bcrypt('bintang123'),
                'roles' => 'admin',
            ],
            [
                'username' => 'Fery Admin',
                'email' => 'feryfadulrahman@gmail.com',
                'email_verified_at' => now(),
                'password' => bcrypt('fery123'),
                'roles' => 'admin',
            ],
            [
                'username' => 'Hasrianti Operator',
                'email' => 'hasrianti10@gmail.com',
                'email_verified_at' => now(),
                'password' => bcrypt('hasrianti123'),
                'roles' => 'operator',
            ],
            [
                'username' => 'Hasrianti',
                'email' => 'hasrianti250@gmail.com',
                'email_verified_at' => now(),
                'password' => bcrypt('hasrianti123321'),
                'roles' => 'user',
            ]
        ];

        foreach($users as $user){
            User::create($user);
        }
    }
}

