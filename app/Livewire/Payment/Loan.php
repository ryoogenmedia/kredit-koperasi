<?php

namespace App\Livewire\Payment;

use App\Livewire\Traits\DataTable\WithBulkActions;
use App\Livewire\Traits\DataTable\WithCachedRows;
use App\Livewire\Traits\DataTable\WithPerPagePagination;
use App\Livewire\Traits\DataTable\WithSorting;
use App\Models\DetailPinjaman;
use App\Models\Nasabah;
use App\Models\Pinjaman;
use Livewire\Attributes\Computed;

use Livewire\Component;

class Loan extends Component
{
    use WithBulkActions;
    use WithPerPagePagination;
    use WithCachedRows;
    use WithSorting;

    public $filters = [
        'search' => '',
        'total_pinjaman' => '',
        'bunga_pinjaman' => '',
        'angsuran' => '',
        'ktp' => '',
        'status' => '',
    ];

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
        $query = Pinjaman::query()
            ->when(!$this->sorts, fn ($query) => $query->first())
            ->when($this->filters['angsuran'], function($query, $angsuran){
                $query->where('installments', $angsuran);
            })
            ->when($this->filters['bunga_pinjaman'], function($query, $bunga){
                $query->where('interest', $bunga);
            })
            ->when($this->filters['total_pinjaman'], function($query, $pinjaman){
                $query->where('amount', $pinjaman);
            })
            ->when($this->filters['ktp'], function($query, $ktp){
                $query->whereHas('nasabah', function($query) use ($ktp){
                    $query->where('number_identity', $ktp);
                });
            })
            ->when($this->filters['status'], function($query, $status){
                $query->whereHas('detail', function($query) use ($status){
                    if($status == 'lunas'){
                        $query->whereNotNull('date_repayment');
                    }

                    if($status == 'belum lunas'){
                        $query->whereNull('date_repayment');
                    }
                });
            })
            ->where('status_akad', 'di berikan')
            ->where('confirmation_nasabah', true);

        return $this->applyPagination($query);
    }

    #[Computed()]
    public function allData()
    {
        return Pinjaman::where('status_akad', 'di berikan')
        ->whereHas('detail', function($query){
            $query->whereNotNull('date_acc_loan')
                ->whereNotNull('proof_funds');
        })
        ->where('confirmation_nasabah', true);
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
        return view('livewire.payment.loan');
    }
}
