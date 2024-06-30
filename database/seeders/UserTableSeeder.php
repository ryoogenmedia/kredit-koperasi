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
                'username' => 'Hasrianti',
                'email' => 'hasrianti252@gmail.com',
                'email_verified_at' => now(),
                'password' => bcrypt('hasrianti123'),
                'roles' => 'admin',
            ],
            [
                'username' => 'Hasrianti Operator',
                'email' => 'anti252@gmail.com',
                'email_verified_at' => now(),
                'password' => bcrypt('hasrianti123'),
                'roles' => 'operator',
            ],
        ];

        foreach($users as $user){
            User::create($user);
        }
    }
}

