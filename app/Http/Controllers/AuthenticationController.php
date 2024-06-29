<?php

namespace App\Http\Controllers;

use App\Models\Nasabah;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class AuthenticationController extends Controller
{
    public function register(Request $request){
        $data = $request->all();
        $roles = 'user';

        $rules = [
            'username' => ['required','string','min:2','max:255'],
            'email' => ['required','email','unique:users,email', 'max:255'],
            'password' => ['required','string', Password::default()],
            'avatar' => ['nullable','image','max:2048'],

            'number_identitiy' => ['required','string','min:2','max:255','unique:nasabah,number_identity'],
            'name' => ['required','string','min:2','max:255','max:255'],
            'address' => ['required','string','min:2'],
            'job' => ['required','string','min:2','max:255'],
            'phone' => ['required','string','min:2','max:255'],
            'age' => ['required','string','min:2','max:255'],
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data pengguna gagal ditambah.',
                'data' => $validator->errors()
            ]);
        }

        try{
            DB::beginTransaction();
                $user = User::create([
                    'username' => $request->username,
                    'email' => $request->email,
                    'password' => bcrypt($request->password),
                    'email_verified_at' => now(),
                    'roles' => $roles,
                ]);

                if($request->file('avatar')){
                    $validator = $request->file('avatar')->store('avatars');

                    $user->update([
                        'avatar' => $validator['avatar'],
                    ]);
                }

                Nasabah::create([
                    'number_identity' => $request->number_identitiy,
                    'name' => $request->name,
                    'address' => $request->address,
                    'job' => $request->job,
                    'phone' => $request->phone,
                    'age' => $request->age,
                ]);

            DB::commit();
        }catch(Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => 'Data pengguna gagal ditambah.',
                'data' => $e->getMessage(),
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data pengguna berhasil terdaftar.',
            'token' => $user->getJWTToken(),
            'data' => $user,
        ]);
    }

    public function login(Request $request){
        $data = $request->all();

        $rules = [
            'email' => ['required','string','email','max:255'],
            'password' => ['required','string',Password::default()],
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Login gagal, cek kembali data yang anda masukkan.',
                'data' => $validator->errors()
            ]);
        }

        $user = User::query()->with('nasabah')->where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Login gagal, anda gagal masuk ke dalam aplikasi.',
                'error' => 'Password atau email salah!',
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Login berhasil, anda bisa masuk ke aplikasi.',
            'token' => $user->getJWTToken(),
            'avatar' => $user->avatarUrl(),
            'data' => $user,
        ]);
    }
}
