<?php

namespace App\Helpers;

use App\Models\Nasabah;
use App\Models\Pinjaman;

class HomeCart{
    public static function NASABAH(){
        $data = [
            'date' => [],
            'data' => [],
        ];

        for ($i = 9; $i >= 0; $i--) {
            $dates = date('Y-m-d', strtotime("-" . $i . " day"));
            $datas = Nasabah::where('status_verification', true)
                ->whereDate('created_at', $dates)
                ->count();

            $data['date'][] = date('d M Y', strtotime($dates));
            $data['data'][] = $datas ? $datas : 0;
        }

        return $data;
    }

    public static function LOAN(){
        $data = [
            'date' => [],
            'data' => [],
        ];

        for ($i = 9; $i >= 0; $i--) {
            $dates = date('Y-m-d', strtotime("-" . $i . " day"));
            $datas = Pinjaman::whereDate('created_at', $dates)
                ->count();

            $data['date'][] = date('d M Y', strtotime($dates));
            $data['data'][] = $datas ? $datas : 0;
        }

        return $data;
    }

    public static function PENCAIRAN(){
        $data = [
            'date' => [],
            'data' => [],
        ];

        for ($i = 9; $i >= 0; $i--) {
            $dates = date('Y-m-d', strtotime("-" . $i . " day"));
            $datas = Pinjaman::where('confirmation_nasabah', true)
                ->whereHas('detail', function($query){
                    $query->whereNotNull('proof_funds');
                })
                ->whereDate('created_at', $dates)
                ->count();

            $data['date'][] = date('d M Y', strtotime($dates));
            $data['data'][] = $datas ? $datas : 0;
        }

        return $data;
    }

    public static function AKAD(){
        $data = [
            'date' => [],
            'data' => [],
        ];

        for ($i = 9; $i >= 0; $i--) {
            $dates = date('Y-m-d', strtotime("-" . $i . " day"));
            $datas = Pinjaman::where('status_akad', 'di berikan')
                ->whereDate('created_at', $dates)
                ->count();

            $data['date'][] = date('d M Y', strtotime($dates));
            $data['data'][] = $datas ? $datas : 0;
        }

        return $data;
    }


}
