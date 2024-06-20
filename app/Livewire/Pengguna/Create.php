<?php

namespace App\Livewire\Pengguna;

use App\Models\Nasabah;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Livewire\WithFileUploads;
use Livewire\Component;

class Create extends Component
{
     use WithFileUploads;

    public $username;
    public $email;
    public $password;
    public $avatar;
    public $roles;

    public $nomorKTP;
    public $namaNasabah;
    public $umurNasabah;
    public $nomorPonsel;
    public $alamat;

    public function validateData(){
        $this->validate([
            'username' => ['required', 'string', 'min:2', 'max:255'],
            'roles' => ['required', 'string', 'min:2', 'max:255', Rule::in(config('const.roles'))],
            'email' => ['required', 'string', 'min:2', 'unique:users,email'],
            'password' => ['required', 'string', Password::default()],
            'avatar' => ['nullable', 'image', 'max:2048'],
        ]);

        if($this->roles == 'user'){
            $this->validate([
            'nomorKTP' => ['required', 'min:2' , 'max:255',  'string'],
            'namaNasabah' => ['required', 'min:2', 'max:255', 'string'],
            'umurNasabah' => ['required', 'min:2', 'max:255','string'],
            'nomorPonsel' => ['required', 'min:2', 'max:255','string'],
            'alamat' => ['required', 'min:5', 'string'],
            ]);
        }
    }

    public function save()
    {
        $this->validateData();

        try {
            DB::beginTransaction();

            $user = User::create([
                'username' => $this->username,
                'email' => strtolower($this->email),
                'password' => bcrypt($this->password),
                'roles' => $this->roles,
                'email_verified_at' => now(),
            ]);

            if ($this->avatar) {
                $user->update([
                    'avatar' => $this->avatar->store('avatars', 'public'),
                ]);
            }

            if($this->roles == 'user'){
                Nasabah::create([
                'user_id' => $user->id,
                'no_ktp'  => $this->nomorPonsel,
                'nama_nasabah' => $this->namaNasabah,
                'alamat'  => $this->alamat,
                'umur'    => $this->umurNasabah,
                'no_telp' => $this->nomorPonsel,
                ]);
            }

            DB::commit();
        } catch (Exception $e) {
            session()->flash('alert', [
                'type' => 'danger',
                'message' => 'Gagal.',
                'detail' => "data user gagal ditambah.",
            ]);
        }

        session()->flash('alert', [
            'type' => 'success',
            'message' => 'Berhasil.',
            'detail' => "data user berhasil ditambah.",
        ]);

        return redirect()->route('pengguna.index');
    }

    public function render()
    {
        return view('livewire.pengguna.create');
    }
}
