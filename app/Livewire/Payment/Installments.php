<?php

namespace App\Livewire\Payment;

use App\Livewire\Traits\DataTable\WithBulkActions;
use App\Livewire\Traits\DataTable\WithCachedRows;
use App\Livewire\Traits\DataTable\WithPerPagePagination;
use App\Livewire\Traits\DataTable\WithSorting;
use App\Models\Angsuran;
use Livewire\Attributes\Computed;

use Livewire\Component;

class Installments extends Component
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

    #[Computed()]
    public function rows()
    {
        $query = Angsuran::query()
            ->when(!$this->sorts, fn ($query) => $query->first())
            ->when($this->filters['angsuran'], function($query, $angsuran){
                $query->whereHas('pinjaman', function($query) use ($angsuran){
                    $query->where('installments', $angsuran);
                });
            })
            ->when($this->filters['bunga_pinjaman'], function($query, $bunga){
                $query->whereHas('pinjaman', function($query) use ($bunga){
                    $query->where('interest', $bunga);
                });
            })
            ->when($this->filters['total_pinjaman'], function($query, $pinjaman){
                $query->whereHas('pinjaman', function($query) use ($pinjaman){
                    $query->where('amount', $pinjaman);
                });
            })
            ->when($this->filters['ktp'], function($query, $ktp){
                $query->whereHas('nasabah', function($query) use ($ktp){
                    $query->where('number_identity', $ktp);
                });
            })
            ->when($this->filters['status'], function($query, $status){
                if($status == 'lunas'){
                    $query->where('confirmation_repayment', true);
                }

                if($status == 'belum lunas'){
                    $query->whereNull('confirmation_repayment', false);
                }
            })->with('pinjaman', function($q){
                $q->with('nasabah')->select('*')->groupBy('nasabah_id');
            });

        return $this->applyPagination($query);
    }

    #[Computed()]
    public function allData()
    {
        return Angsuran::all();
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
        return view('livewire.payment.installments');
    }
}
