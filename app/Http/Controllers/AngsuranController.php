<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\JwtAuth;
use App\Models\Angsuran;
use App\Models\DetailAngsuran;
use App\Models\Pinjaman;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AngsuranController extends Controller
{
    use JwtAuth;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = $this->user();
        $pinjaman = Pinjaman::query()->with(['detail','angsuran'])
            ->where('nasabah_id', $user->nasabah->id)
            ->where('confirmation_nasabah', true)
            ->where('status_akad', 'di berikan')
            ->get();

        if(!$pinjaman){
            return response()->json([
                'status' => 'error',
                'message' => 'Data angsuran gagal ditampilkan',
            ]);
        }

        $keuntungan = hitungKeuntungan($user->nasabah->id);

        return response()->json([
            'status' => 'success',
            'message' => 'Data angsuran berhasil ditampilkan',
            'data' => [
                'loan' => $pinjaman,
                'amortisasi' => $keuntungan,
            ],
        ]);
    }

    public function payment(Request $request, string $id){
        $data = $request->all();
        $user = $this->user();

        $pinjaman = Pinjaman::query()
            ->where('confirmation_nasabah', true)
            ->where('status_akad', 'di berikan')
            ->where('id', $id)
            ->first();

        $rules = [
            'pinjaman_id' => ['required'],
            'proof' => ['required','image','min:2048'],
        ];

        $validator = Validator::make($data, $rules);

        if($validator->fails()){
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal melakukan pembayaran, cek kembali data yang anda kirim.',
                'data' => $validator->errors()
            ]);
        }

        if(!$pinjaman){
            return response()->json([
                'status' => 'error',
                'message' => 'Data pinjaman tidak ada.',
            ]);
        }

        try{
            DB::beginTransaction();

            $angsuran = Angsuran::create($data);

            $totalAngsuran = Angsuran::query()
                ->where('confirmation_repayment', true)
                ->where('pinjaman_id', $pinjaman->id)
                ->count();

            $amountAngsuran = Angsuran::query()
                ->where('confirmation_repayment', true)
                ->where('pinjaman_id', $pinjaman->id)
                ->sum('remaining_loan');

            $installmentsTo = $pinjaman->installments - $totalAngsuran;
            $remainingLoan = $pinjaman->amount -  $amountAngsuran;

            $angsuran->update([
                'nasabah_id' => $user->nasabah->id,
                'date_installments' => now(),
                'installments_to' => $installmentsTo,
                'remaining_installments' => $totalAngsuran,
                'remaining_loan' => $remainingLoan,
            ]);

            DetailAngsuran::create([
                'pinjaman_id' => $pinjaman->id,
                'detail_pinjaman_id' => $pinjaman->detail->id,
                'amount_installments' => $totalAngsuran,
                'note' => 'Pinjaman Bpk/Ibu : ' . $user->nasabah->name,
            ]);

            if($pinjaman->installments == $installmentsTo){
                $pinjaman->detail->update([
                    'date_repayment' => now(),
                ]);
            }

            DB::commit();
        }catch(Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal melakukan pembayaran.',
                'data' => $e->getMessage(),
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Gagal melakukan pembayaran.',
            'data' => $angsuran,
        ]);
    }
}
