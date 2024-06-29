<?php

use App\Models\Pinjaman;

/**
 * Mengubah format menjadi mata uang rupiah (IDR)
 *
 * @param  int  $uang  angka yang ingin diubah
 * @return string Rp. $uang ,-
 */
if (!function_exists('money_format_idr')) {
    function money_format_idr($money, $withRp = true, $desimal = false)
    {
        $money = (float) $money;

        return $withRp
            ? 'Rp. ' . number_format($money, $desimal ? 2 : 0, ',', '.') . ''
            : number_format($money, $desimal ? 2 : 0, ',', '.');
    }
}

/**
 * Bunga Rata Rata Pinjaman
 *
 * @return boolean rata rata angsuran
 */
if(!function_exists('average_interest')){
    function average_interest(){
        $pinjaman = Pinjaman::all();
        $totalInterest = $pinjaman->sum('interest');
        $jumlahPinjaman = $pinjaman->count();

        if ($jumlahPinjaman == 0) {
            return 0;
        }

        $rataRataBunga = $totalInterest / $jumlahPinjaman;

        return $rataRataBunga;
    }
}

/**
 * Bunga Rata Rata Pinjaman
 *
 * @return boolean rata rata angsuran
 */
if(!function_exists('average_installments')){
    function average_installments(){
        $pinjaman = Pinjaman::all();
        $totalInstallments = $pinjaman->sum('installments');
        $jumlahPinjaman = $pinjaman->count();

        if ($jumlahPinjaman == 0) {
            return 0;
        }

        $rataRataBunga = $totalInstallments / $jumlahPinjaman;

        return $rataRataBunga;
    }
}

/**
 * Hitung keuntungan Amrotisasi Semua Pinjaman
 *
 * @return array amortisasi keuntungan
 */
if(!function_exists('hitungKeuntungan')){
    function hitungKeuntungan($nasabahId = null) {
        if(!$nasabahId){
            $pinjamanSemua = Pinjaman::where('confirmation_nasabah', true)->get();
        }

        if($nasabahId){
            $pinjamanSemua = Pinjaman::where('confirmation_nasabah', true)
                ->where('id', $nasabahId)->get();
        }

        $hasilAmortisasi = [];

        // Variabel untuk total keseluruhan
        $totalKeseluruhanAngsuranBulanan = 0;
        $totalKeseluruhanPembayaranPokok = 0;
        $totalKeseluruhanBungaBulanan = 0;
        $totalKeseluruhanPokokAwal = 0;

        // Iterasi melalui setiap pinjaman
        foreach ($pinjamanSemua as $pinjaman) {
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
                $sisaPokok = $pokokAwal - $pembayaranPokok;

                $tabelAmortisasi[] = [
                    'bulan' => $bulan,
                    'pokok_awal' => money_format_idr($pokokAwal),
                    'bunga_bulanan' => money_format_idr($bungaBulanan),
                    'angsuran_bulanan' => money_format_idr($angsuranBulanan),
                    'pembayaran_pokok' => money_format_idr($pembayaranPokok),
                    'sisa_pokok' => money_format_idr($sisaPokok),
                ];

                $totalAngsuranBulanan += $angsuranBulanan;
                $totalPembayaranPokok += $pembayaranPokok;
                $totalBungaBulanan += $bungaBulanan;
                $totalPokokAwal += $pokokAwal;

                // Update nilai pokok awal untuk bulan berikutnya
                $pokokAwal = $sisaPokok;
            }

            $hasilAmortisasi[] = [
                'pinjaman_id' => $pinjaman->id,
                'tabel_amortisasi' => $tabelAmortisasi,
                'total_angsuran_bulanan' => money_format_idr($totalAngsuranBulanan),
                'total_pembayaran_pokok' => money_format_idr($totalPembayaranPokok),
                'total_bunga_bulanan' => money_format_idr($totalBungaBulanan),
                'total_pokok_awal' => money_format_idr($totalPokokAwal),
            ];

            // Tambahkan ke total keseluruhan
            $totalKeseluruhanAngsuranBulanan += $totalAngsuranBulanan;
            $totalKeseluruhanPembayaranPokok += $totalPembayaranPokok;
            $totalKeseluruhanBungaBulanan += $totalBungaBulanan;
            $totalKeseluruhanPokokAwal += $jumlahPinjaman; // Sesuai dengan logika, jumlah pinjaman awal harus digunakan di sini
        }

        return [
            'hasil_amortisasi' => $hasilAmortisasi,
            'total_keseluruhan_angsuran_bulanan' => money_format_idr($totalKeseluruhanAngsuranBulanan),
            'total_keseluruhan_pembayaran_pokok' => money_format_idr($totalKeseluruhanPembayaranPokok),
            'total_keseluruhan_bunga_bulanan' => money_format_idr($totalKeseluruhanBungaBulanan),
            'total_keseluruhan_pokok_awal' => money_format_idr($totalKeseluruhanPokokAwal),
        ];
    }
}

/**
 * Hitung Amortisasi Pinjaman
 *
 * @param  int pinjamanId
 * @return array amortisasi pinjaman
 */
if(!function_exists('hitungAmortisasi')){
    function hitungAmortisasi($pinjamanId) {
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
