<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\JwtAuth;
use App\Models\Pinjaman;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PinjamanController extends Controller
{
    use JwtAuth;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = $this->user();
        $nasabah = $user->nasabah;

        $pinjaman = Pinjaman::query()->where('nasabah_id', $nasabah->id)->get();

        if(!$pinjaman){
            return response()->json([
                'status' => 'error',
                'message' => 'Data pinjaman tidak ditemukan.',
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data pinjaman berhasil ditampilkan.',
            'data' => $pinjaman,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $nasabah = $this->user()->nasabah;

        $rules = [
            'amount' => ['required','string','min:2','max:255'],
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data pinjaman gagal ditambahkan.',
                'data' => $validator->errors()
            ]);
        }

        try{
            DB::beginTransaction();

            $pinjaman = Pinjaman::create($data);

            $pinjaman->udpate([
                'nasabah_id' => $nasabah->id,
                'date' => now(),
                'interest' => 2,
                'installments' => 12,
                'amount_installments' => 0,
                'confirmation_nasabah' => false,
            ]);

            DB::commit();
        }catch(Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => 'Data pinjaman gagal ditambahkan.',
                'data' => $e->getMessage(),
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data pinjaman berhasil ditambahkan.',
            'data' => $pinjaman,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pinjaman = Pinjaman::query()->where('id', $id)->first();

        if(!$pinjaman){
            return response()->json([
                'status' => 'error',
                'message' => 'Data pinjaman gagal ditampilkan.',
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data pinjaman berhasil ditampilkan.',
            'data' => $pinjaman,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->all();
        $nasabah = $this->user()->nasabah;

        $rules = [
            'amount' => ['required','string','min:2','max:255'],
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data pinjaman gagal disunting.',
                'data' => $validator->errors()
            ]);
        }

        $pinjaman = Pinjaman::query()->where('id', $id)->first();

        try{
            DB::beginTransaction();

            $pinjaman->update($data);

            $pinjaman->update([
                'date' => now(),
            ]);

            DB::commit();
        }catch(Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => 'Data pinjaman gagal disunting.',
                'data' => $e->getMessage(),
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data pinjaman berhasil disunting.',
            'data' => $pinjaman,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pinjaman = Pinjaman::query()->where('id', $id)->first();

        if(!$pinjaman){
            return response()->json([
                'status' => 'error',
                'message' => 'Data pinjaman tidak ditemukan.',
            ]);
        }

        try{
            DB::beginTransaction();

            $pinjaman->delete();

            DB::commit();
        }catch(Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => 'Data pinjaman gagal dihapus.',
                'data' => $e->getMessage(),
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data pinjaman berhasil dihapus.',
            'data' => $pinjaman,
        ]);
    }
}
