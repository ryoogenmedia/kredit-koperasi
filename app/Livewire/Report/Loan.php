<?php

namespace App\Livewire\Report;

use App\Livewire\Traits\DataTable\WithBulkActions;
use App\Livewire\Traits\DataTable\WithCachedRows;
use App\Livewire\Traits\DataTable\WithPerPagePagination;
use App\Livewire\Traits\DataTable\WithSorting;
use App\Models\Pinjaman;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Loan extends Component
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

    public function cetakPinjaman(){
        if(Session::get('cetak-pinjaman')){
            Session::remove('cetak-pinjaman');
        }

        Session::put('cetak-pinjaman', [
            'data' => $this->rows(),
            'tahun' => $this->year,
            'ktp' => $this->filters['ktp'],
            'status' => $this->filters['status'],
        ]);

        return redirect()->route('cetak.pinjaman');
    }

    #[Computed()]
    public function getYears(){
        return Pinjaman::select(DB::raw('YEAR(date) as year'))
            ->groupBy('year')
            ->distinct()
            ->get();
    }

    #[Computed()]
    public function rows()
    {
        $query = Pinjaman::query()
            ->when(!$this->sorts, fn ($query) => $query->first())
            ->when($this->filters['ktp'], function($query, $ktp){
                $query->whereHas('nasabah', function($query) use ($ktp){
                    $query->where('number_identity', $ktp);
                });
            })->when($this->year, function($query, $year){
                $query->whereYear('created_at', $year);
            })->when($this->filters['status'], function($query, $status){
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
        return view('livewire.report.loan');
    }
}
