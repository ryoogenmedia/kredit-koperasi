<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\JwtAuth;
use App\Models\Nasabah;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class NasabahController extends Controller
{
    use JwtAuth;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = $this->user();

        $nasabah = $user->nasabah;

        if(!$nasabah){
            return response()->json([
                'status' => 'error',
                'message' => 'Data nasabah gagal ditampilkan.',
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data nasabah berhasil ditampilkan',
            'data' => $nasabah,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $rules = [
            'number_identity' => ['required','string','min:2','max:255'],
            'name' => ['required','string','min:2','max:255'],
            'address' => ['required','string'],
            'job' => ['required','string','min:2','max:255'],
            'phone' => ['required','string','min:2','max:255'],
            'age' => ['required','string','min:2','max:255'],
            'email' => ['required','string','min:2','max:255','email','unique:nasabah,email'],
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data nasabah gagal ditambah.',
                'data' => $validator->errors()
            ]);
        }

        try{
            DB::beginTransaction();

            $nasabah = Nasabah::create($data);

            DB::commit();
        }catch(Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => 'Data nasabah gagal disunting.',
                'data' => $e->getMessage(),
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data nasabah berhasi disunting.',
            'data' => $nasabah,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $nasbah = Nasabah::query()->with(['angsuran','pinjaman'])->where('id', $id)->first();

        if(!$nasbah){
            return response()->json([
                'status' => 'error',
                'message' => 'Data nasabah tidak ditemukan'
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data nasabah berhasil ditampilkan',
            'data' => $nasbah,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = $this->user();
        $data = $request->all();

        $rules = [
            'number_identity' => ['required','string','min:2','max:255'],
            'name' => ['required','string','min:2','max:255'],
            'address' => ['required','string'],
            'job' => ['required','string','min:2','max:255'],
            'phone' => ['required','string','min:2','max:255'],
            'age' => ['required','string','min:2','max:255'],
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data nasabah gagal disunting.',
                'data' => $validator->errors()
            ]);
        }

        $nasabah = Nasabah::query()->where('id', $id)->first();

        if(!$nasabah){
            return response()->json([
                'status' => 'error',
                'message' => 'Data nasabah gagal disunting',
            ]);
        }

        try{
            DB::beginTransaction();

            $nasabah->update($data);

            $nasabah->update([
                'email' => $user->email,
            ]);

            DB::commit();
        }catch(Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => 'Data nasabah gagal disunting.',
                'data' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data nasabah berhasil disunting.',
            'data' => $nasabah,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $nasabah = Nasabah::query()->where('id', $id)->first();

        if(!$nasabah){
            return response()->json([
                'status' => 'error',
                'message' => 'Data nasabah tidak ditemukan.',
            ]);
        }

        try{
            DB::beginTransaction();

            $nasabah->delete();

            DB::commit();
        }catch(Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => 'Data nasabah gagal dihapus.',
                'data' => $e->getMessage(),
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data nasabah berhasil dihapus.',
            'data' => $nasabah,
        ]);
    }
}
