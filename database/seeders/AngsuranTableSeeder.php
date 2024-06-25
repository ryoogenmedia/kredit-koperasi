<?php

namespace Database\Seeders;

use App\Models\Angsuran;
use App\Models\DetailAngsuran;
use Illuminate\Database\Seeder;

class AngsuranTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $angsuran = [
            [
                'pinjaman_id' => 1,
                'nasabah_id' => 1,
                'date_installments' => now(),
                'installments_to' => 1,
                'remaining_installments' => 11,
                'remaining_loan' => 1833314,
                'proof' => 'proof/proof-01.jpeg',
            ]
        ];

        foreach($angsuran as $data){
            $angsuran = Angsuran::create($data);
            $angsuran->pinjaman->update([
                'amount_installments' => 1,
            ]);

            DetailAngsuran::updateOrCreate(
                [
                    'angsuran_id' => $angsuran->id,
                    'detail_pinjaman_id' => 1,
                ],
                [
                    'amount_installments' => 1,
                    'note' => 'Jumlah angsuran adalah 12x pembayaran dan telah di bayar 1x angsurannya',
                ]
            );
        }
    }
}
