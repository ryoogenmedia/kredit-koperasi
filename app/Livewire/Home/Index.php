<?php

namespace App\Livewire\Home;

use App\Helpers\HomeCart;
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

    public $countNasabahVerification;
    public $countAkadConfirm;
    public $countFunds;
    public $countLoan;

    public $totalUangPencairan = 0;
    public $totalPinjamanYangBelumCair;

    public function mount(){
        $this->jmlNasabahBelumVerifikasi = $this->nasabah('unverification');
        $this->jmlNasabahVerifikasi = $this->nasabah('verification');

        $this->jmlAkadBelumKonfirmasi = $this->akad('unconfirm');
        $this->jmlAkadTelahKonfirmasi = $this->akad('confirm');

        $this->jmlPencairan = $this->funds('success');
        $this->jmlBelumCari = $this->funds('not-success');

        $this->pengajuanPinjaman = Pinjaman::count();
        $this->jmlNasabah = Nasabah::count();

        $this->countNasabahVerification = HomeCart::NASABAH();
        $this->countAkadConfirm = HomeCart::AKAD();
        $this->countFunds = HomeCart::PENCAIRAN();
        $this->countLoan = HomeCart::LOAN();

        $pinjamanCair = Pinjaman::where("confirmation_nasabah", true)
            ->whereHas('detail', function($query){
                $query->whereNotNull('proof_funds');
            })->get();

        if($pinjamanCair){
            foreach($pinjamanCair as $pinjaman){
                $this->totalUangPencairan += $pinjaman->amount;
            }
        }

        $pinjamanBelumCair = Pinjaman::where("confirmation_nasabah", false)
            ->whereHas('detail', function($query){
                $query->whereNull('proof_funds');
            })->get();

        if($pinjamanBelumCair){
            foreach($pinjamanBelumCair as $pinjaman){
                $this->totalPinjamanYangBelumCair += $pinjaman->amount;
            }
        }
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
