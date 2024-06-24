<?php

namespace App\Livewire\Home;

use App\Models\DetailPinjaman;
use App\Models\Nasabah;
use App\Models\Pinjaman;
use Faker\Guesser\Name;
use Livewire\Component;

class Index extends Component
{
    public $jmlNasabahVerifikasi;
    public $jmlNasabahBelumVerifikasi;
    public $jmlAkadBelumKonfirmasi;
    public $jmlAkadTelahKonfirmasi;
    public $jmlPencairan;
    public $jmlBelumCari;
    public $pengajuanPinjaman;
    public $jmlNasabah;

    public function mount(){
        $this->jmlNasabahBelumVerifikasi = $this->nasabah('unverification');
        $this->jmlNasabahVerifikasi = $this->nasabah('verification');

        $this->jmlAkadBelumKonfirmasi = $this->akad('unconfirm');
        $this->jmlAkadTelahKonfirmasi = $this->akad('confirm');

        $this->jmlPencairan = $this->funds('success');
        $this->jmlBelumCari = $this->funds('not-success');

        $this->pengajuanPinjaman = Pinjaman::count();
        $this->jmlNasabah = Nasabah::count();
    }

    public function nasabah($status){
        if($status == 'verification'){
            return Nasabah::where('status_verification', true)->count();
        }

        if($status == 'unverfication'){
            return Nasabah::where('status_verification', false)->count();
        }
    }

    public function akad($status){
        if($status == 'confirm'){
            return Pinjaman::where('status_akad', 'di berikan')->count();
        }

        if($status == 'unconfirm'){
            return Pinjaman::where('status_akad', 'belum di berikan')->count();
        }
    }

    public function funds($status){
        if($status == 'success'){
            return DetailPinjaman::whereNotNull('proof_funds')->count();
        }

        if($status == 'not-success'){
            return DetailPinjaman::whereNull('proof_funds')->count();
        }
    }

    public function render()
    {
        return view('livewire.home.index');
    }
}
