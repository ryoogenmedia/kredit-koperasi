<?php

namespace Database\Seeders;

use App\Models\Nasabah;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NasabaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $akunNasabah = User::create([
            'username' => 'Abdul Wahid',
            'roles' => 'user',
            'email' => 'abdulwahid44@gmail.com',
            'password' => bcrypt('abdulwahid123'),
        ]);

        $nasabah = [
            [
                'user_id' => $akunNasabah->id,
                'number_identity' => '990393029292',
                'name' => 'Abdul Wahid',
                'address' => 'Jl Pettarani Kota Makassar No 18',
                'job' => 'PNS',
                'age' => '18',
                'email' => 'abdulwahid44@gmail.com',
                'phone' => '08938383728291'
            ]
        ];

        foreach($nasabah as $data){
            Nasabah::create($data);
        }
    }
}
