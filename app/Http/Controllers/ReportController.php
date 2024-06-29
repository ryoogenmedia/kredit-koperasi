<?php

namespace App\Http\Controllers;

use App\Models\Nasabah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ReportController extends Controller
{
    public function nasabah(){
        $session = Session::get('cetak-nasabah');

        $data = $session['data']->items();
        $tahun = $session['tahun'];
        $ktp = $session['ktp'];

        $pdf = \PDF::loadView('report.nasabah', [
            'data' => $data,
            'tahun' => $tahun,
            'ktp' => $ktp,
        ]);

        $pdf->setPaper('a4', 'potrait');
        return $pdf->stream('Laporan'  . "_" . "nasabah" . "_" . $tahun ?? '' . ".pdf");
    }

    public function angsuran(){
        $session = Session::get('cetak-angsuran');

        $data = $session['data']->items();
        $tahun = $session['tahun'];
        $ktp = $session['ktp'];

        if($ktp){
            $nasabah = Nasabah::where('number_identity', $ktp)->where('status_verification', true)->first();
        }

        $pdf = \PDF::loadView('report.installments', [
            'data' => $data,
            'tahun' => $tahun,
            'ktp' => $ktp,
            'nasabah' => $nasabah ?? null,
        ]);

        $pdf->setPaper('a4', 'potrait');
        return $pdf->stream('Laporan'  . "_" . "angsuran" . "_" . $tahun ?? '' . ".pdf");
    }

    public function pinjaman(){
        $session = Session::get('cetak-pinjaman');

        $data = $session['data']->items();
        $tahun = $session['tahun'];
        $ktp = $session['ktp'];
        $status = $session['status'];

        $pdf = \PDF::loadView('report.loan', [
            'data' => $data,
            'tahun' => $tahun,
            'ktp' => $ktp,
            'status' => $status,
        ]);

        $pdf->setPaper('a4', 'potrait');
        return $pdf->stream('Laporan'  . "_" . "pinjaman" . "_" . $tahun ?? '' . ".pdf");
    }
}
