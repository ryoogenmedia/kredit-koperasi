<?php

namespace Database\Seeders;

use App\Models\Angsuran;
use App\Models\DetailPinjaman;
use App\Models\Pinjaman;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PinjamanTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pinjaman = [
            [
                'nasabah_id' => 1,
                'amount' => 5000000,
                'interest' => 2,
                'date' => now(),
                'installments' => 12,
                'amount_installments' => 0,
            ]
        ];

        foreach($pinjaman as $data){
            $pinjaman = Pinjaman::create($data);

            $angsuranLatest = $pinjaman->latestAngsuran;

            DetailPinjaman::updateOrCreate(
                [
                    'pinjaman_id' => $pinjaman->id,
                ],
                [
                    'date_submission_loan' => now(),
                    'date_acc_loan' => now()->addDay(5),
                    'remaining_loan' => $angsuranLatest->remaining_loan,
                    'note' => "Pinjaman dari bpk/ibu " . $pinjaman->nasabah->name,
                ]
            );
        }
    }
}
