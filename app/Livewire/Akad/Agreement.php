<?php

namespace App\Livewire\Akad;

use App\Models\DetailPinjaman;
use App\Models\Nasabah;
use App\Models\Pinjaman;
use Illuminate\Support\Facades\Date;
use Livewire\Component;
use Livewire\WithFileUploads;

class Agreement extends Component
{
    use WithFileUploads;

    public $pinjamanId;
    public Nasabah $nasabah;
    public Pinjaman $pinjaman;
    public $tglPinjaman;
    public $fileAkad;

    public function rules(){
        return [
            'fileAkad' => ['required','image','max:2048'],
        ];
    }

    public function changeInterest($id, $value){
        $pinjaman = Pinjaman::findOrFail($id);

        $pinjaman->update([
            'interest' => $value,
        ]);
    }

    public function changeInstallmentsType($id, $value){
        $pinjaman = Pinjaman::findOrFail($id);

        $pinjaman->update([
            'installments_type' => $value,
        ]);
    }

    public function sendToNasabah($id){
        $pinjaman = Pinjaman::findOrFail($id);
        $detailPinjaman = DetailPinjaman::findOrFail($pinjaman->detail->id);

        if(!$this->fileAkad){
            session()->flash('alert', [
                'type' => 'warning',
                'message' => 'Bahaya.',
                'detail' => "Tambahkan gambar surat perjanjian terlebih dahulu untuk nasabah!.",
            ]);

            return redirect()->route('akad.pinjaman.agreement', $this->pinjamanId);
        }

        $pinjaman->update([
            'status_akad' => 'di berikan',
            'confirmation_nasabah' => false,
        ]);

        $detailPinjaman->update([
            'file_akad' => $this->fileAkad->store('akad','public'),
            'note' => 'Pinjaman dana sebesar ' . money_format_idr($this->pinjaman->amount) . ' dengan bunga sebesar % ' . $this->pinjaman->interest . ' dan angsuran ' . $this->pinjaman->installments . 'x Pembayaran per ' . $this->pinjaman->installments_type,
        ]);

        $this->reset('fileAkad');

        session()->flash('alert', [
            'type' => 'success',
            'message' => 'Berhasil.',
            'detail' => "Berhasi di kirim ke nasabah, silahkan tunggu konfirmasi dari nasabah.",
        ]);

        return redirect()->route('akad.pinjaman.agreement', $this->pinjamanId);
    }

    public function mount($id){
        $pinjaman = Pinjaman::findOrFail($id);

        if($pinjaman){
            $this->pinjaman = $pinjaman;
            $this->pinjamanId = $pinjaman->id;
            $this->nasabah = $pinjaman->nasabah;
            $this->tglPinjaman = Date::now();
        }
    }

    public function render()
    {
        return view('livewire.akad.agreement');
    }
}
