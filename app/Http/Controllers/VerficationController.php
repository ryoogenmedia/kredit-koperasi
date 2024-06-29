<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\JwtAuth;
use App\Models\Pinjaman;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VerficationController extends Controller
{
    use JwtAuth;

    public function akad(Request $request, string $id){
        $confirmation = $request->only('confirmation_nasabah');

        $user = $this->user();

        $pinjaman = Pinjaman::query('nasabah_id', $user->nasabah->id)->where('id', $id)->first();

        if(!$pinjaman){
            return response()->json([
                'status' => 'error',
                'message' => 'Data pinjaman gagal diverifikasi.',
            ]);
        }

        try{
            DB::beginTransaction();

            $pinjaman->update([
                'confirmation_nasabah' => $confirmation,
            ]);

            DB::commit();
        }catch(Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => 'Data verifikasi pinjaman gagal disunting.',
                'data' => $e->getMessage(),
            ]);
        }

        if($pinjaman->confirmation_nasabah){
            $message = 'Data verifikasi pinjaman berhasil diterima';
        }

        if(!$pinjaman->confirmation_nasabah){
            $message = 'Data verifikasi pinjaman berhasil ditolak';
        }

        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $pinjaman,
        ]);
    }

    public function nasabah(){
        $user = $this->user();

        $nasabah = $user->nasabah;

        if(!$nasabah){
            return response()->json([
                'status' => 'error',
                'message' => 'Data verfikasi nasabah gagal ditampilkan.',
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data verifikasi nasabah berhasil ditampilkan',
            'status_verification' => $nasabah->status_verification,
            'data' => $nasabah,
        ]);
    }
}
