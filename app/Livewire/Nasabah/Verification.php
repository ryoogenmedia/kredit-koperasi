<?php

namespace App\Livewire\Nasabah;

use App\Livewire\Traits\DataTable\WithBulkActions;
use App\Livewire\Traits\DataTable\WithCachedRows;
use App\Livewire\Traits\DataTable\WithPerPagePagination;
use App\Livewire\Traits\DataTable\WithSorting;
use App\Models\DetailPinjaman;
use App\Models\Nasabah;
use App\Models\Pinjaman;
use Livewire\Attributes\Computed;

use Livewire\Component;

class Verification extends Component
{
    use WithBulkActions;
    use WithPerPagePagination;
    use WithCachedRows;
    use WithSorting;

    public $filters = [
        'search' => '',
    ];

    public function deleteSelected()
    {
        $nasabah = Nasabah::whereIn('id', $this->selected)->get();
        $deleteCount = $nasabah->count();

        foreach ($nasabah as $data) {
            $data->delete();
        }

        $this->reset();

        session()->flash('alert', [
            'type' => 'success',
            'message' => 'Berhasil.',
            'detail' => "$deleteCount data nasabah berhasil dihapus.",
        ]);

        return redirect()->route('nasabah.verification');
    }

    public function changeDetailPinjaman($id){
        $detailPinjaman = DetailPinjaman::findOrFail($id);

        if(!$detailPinjaman->date_acc_loan || $detailPinjaman->date_acc_loan == null){

            $interest = $detailPinjaman->pinjaman->interest;

            if(!$interest > 0){
                session()->flash('alert', [
                    'type' => 'warning',
                    'message' => 'Bahaya.',
                    'detail' => "Bunga pinjaman minimal 1%, atur bunga pinjaman terlebih dahulu.",
                ]);

                return back();
            }

            $detailPinjaman->update([
                'date_acc_loan' => now(),
            ]);

            session()->flash('alert', [
                'type' => 'success',
                'message' => 'Berhasil.',
                'detail' => "Pinjaman nasabah berhasil di setujui.",
            ]);

            return back();
        }

        $detailPinjaman->update([
            'date_acc_loan' => null
        ]);

        session()->flash('alert', [
            'type' => 'success',
            'message' => 'Berhasil.',
            'detail' => "Pinjaman nasabah berhasil di batalkan.",
        ]);

        return back();
    }

    public function changeInterest($id, $value){
        $pinjaman = Pinjaman::findOrFail($id);

        $pinjaman->update([
            'interest' => $value,
        ]);
    }

    public function changeVerification($id){
        $nasabah = Nasabah::findOrFail($id);
        $nasabah->status_verification = !$nasabah->status_verification;
        $nasabah->save();

        session()->flash('alert', [
            'type' => 'success',
            'message' => 'Berhasil.',
            'detail' => "verfikasi nasabah $nasabah->name berhasil di batalkan.",
        ]);
    }

    #[Computed()]
    public function rows()
    {
        $query = Nasabah::query()
            ->when(!$this->sorts, fn ($query) => $query->first())
            ->when($this->filters['search'], function ($query, $search) {
                $query->where('status_verification',true)->whereAny(['username','roles','email'], 'LIKE', "%$search%");
            })->where('status_verification', true);

        return $this->applyPagination($query);
    }

    #[Computed()]
    public function allData()
    {
        return Nasabah::where('status_verification', true)->get();
    }

    public function updatedFilters()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset('filters');
    }

    public function render()
    {
        return view('livewire.nasabah.verification');
    }
}
