<?php

namespace App\Livewire\Report;

use App\Livewire\Traits\DataTable\WithBulkActions;
use App\Livewire\Traits\DataTable\WithCachedRows;
use App\Livewire\Traits\DataTable\WithPerPagePagination;
use App\Livewire\Traits\DataTable\WithSorting;
use App\Models\Angsuran;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Installments extends Component
{
    use WithBulkActions;
    use WithPerPagePagination;
    use WithCachedRows;
    use WithSorting;

    public $year = '';

    public $filters = [
        'search' => '',
        'ktp' => '',
        'status' => '',
    ];
    public function cetakAngsuran(){
        if(Session::get('cetak-angsuran')){
            Session::remove('cetak-angsuran');
        }

        Session::put('cetak-angsuran', [
            'data' => $this->rows(),
            'tahun' => $this->year,
            'ktp' => $this->filters['ktp']
        ]);

        return redirect()->route('cetak.angsuran');
    }

    #[Computed()]
    public function getYears(){
        return Angsuran::select(DB::raw('YEAR(date_installments) as year'))
            ->groupBy('year')
            ->distinct()
            ->get();
    }

    #[Computed()]
    public function rows()
    {
        $query = Angsuran::query()
            ->when(!$this->sorts, fn ($query) => $query->first())
            ->when($this->filters['ktp'], function($query, $ktp){
                $query->whereHas('nasabah', function($query) use ($ktp){
                    $query->where('number_identity', $ktp);
                });
            })
            ->when($this->year, function($query, $year){
                $query->whereYear('date_installments', $year);
            })
            ->when($this->filters['search'], function ($query, $search) {
                $query->whereAny(['date_installments','remaining_installments','remaining_loan'], 'LIKE', "%$search%");
            });

        return $this->applyPagination($query);
    }

    #[Computed()]
    public function allData()
    {
        return Angsuran::query()->get();
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
        return view('livewire.report.installments');
    }
}
