<?php

/**
 * Mengubah format menjadi mata uang rupiah (IDR)
 *
 * @param  int  $uang  angka yang ingin diubah
 * @return string Rp. $uang ,-
 */

use App\Models\Pinjaman;

if (!function_exists('money_format_idr')) {
    function money_format_idr($money, $withRp = true, $desimal = false)
    {
        $money = (float) $money;

        return $withRp
            ? 'Rp. ' . number_format($money, $desimal ? 2 : 0, ',', '.') . ''
            : number_format($money, $desimal ? 2 : 0, ',', '.');
    }
}

if(!function_exists('hitungAmortisasi')){
    function hitungAmortisasi($pinjamanId) {
        // Anda perlu mengganti ini sesuai dengan model dan logika aplikasi Anda
        $pinjaman = Pinjaman::findOrFail($pinjamanId);

        $jumlahPinjaman = $pinjaman->amount;
        $sukuBungaTahunan = $pinjaman->interest;
        $jangkaWaktuBulan = $pinjaman->installments;

        $sukuBungaBulanan = ($sukuBungaTahunan / 100) / 12;

        $angsuranBulanan = $jumlahPinjaman * $sukuBungaBulanan * pow(1 + $sukuBungaBulanan, $jangkaWaktuBulan) / (pow(1 + $sukuBungaBulanan, $jangkaWaktuBulan) - 1);

        $tabelAmortisasi = [];
        $totalAngsuranBulanan = 0;
        $totalPembayaranPokok = 0;
        $totalBungaBulanan = 0;
        $totalPokokAwal = 0;
        $pokokAwal = $jumlahPinjaman;

        for ($bulan = 1; $bulan <= $jangkaWaktuBulan; $bulan++) {
            $bungaBulanan = $pokokAwal * $sukuBungaBulanan;
            $pembayaranPokok = $angsuranBulanan - $bungaBulanan;
            $pembayaranPokok = $pembayaranPokok;
            $sisaPokok = $pokokAwal - $pembayaranPokok;

            $tabelAmortisasi[] = [
                'bulan' =>  $bulan,
                'pokok_awal' =>  money_format_idr($pokokAwal),
                'bunga_bulanan' =>  money_format_idr($bungaBulanan),
                'angsuran_bulanan' =>  money_format_idr($angsuranBulanan),
                'pembayaran_pokok' =>  money_format_idr($pembayaranPokok),
                'sisa_pokok' =>  money_format_idr($sisaPokok),
            ];

            $totalAngsuranBulanan += $angsuranBulanan;
            $totalPembayaranPokok += $pembayaranPokok;
            $totalBungaBulanan += $bungaBulanan;
            $totalPokokAwal += $pokokAwal;


            // Update nilai pokok awal untuk bulan berikutnya
            $pokokAwal = $sisaPokok;
        }

        return [
            'tabel_amortisasi' => $tabelAmortisasi,
            'total_angsuran_bulanan' => money_format_idr($totalAngsuranBulanan),
            'total_pembayaran_pokok' => money_format_idr($totalPembayaranPokok),
            'total_bunga_bulanan' => money_format_idr($totalBungaBulanan),
            'total_pokok_awal' => money_format_idr($totalPokokAwal),
        ];
    }

}
