<?php

namespace App\Livewire\Report;

use App\Livewire\Traits\DataTable\WithBulkActions;
use App\Livewire\Traits\DataTable\WithCachedRows;
use App\Livewire\Traits\DataTable\WithPerPagePagination;
use App\Livewire\Traits\DataTable\WithSorting;
use Livewire\Attributes\Computed;
use App\Models\Nasabah as ModelNasabah;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class Nasabah extends Component
{
    use WithBulkActions;
    use WithPerPagePagination;
    use WithCachedRows;
    use WithSorting;

    public $year = '';
    public $filters = [
        'search' => '',
        'ktp' => '',
    ];

    public function cetakNasabah(){
        if(Session::get('cetak-nasabah')){
            Session::remove('cetak-nasabah');
        }

        Session::put('cetak-nasabah', [
            'data' => $this->rows(),
            'tahun' => $this->year,
            'ktp' => $this->filters['ktp']
        ]);

        return redirect()->route('cetak.nasabah');
    }

    #[Computed()]
    public function getYears(){
        return ModelNasabah::select(DB::raw('YEAR(created_at) as year'))
            ->groupBy('year')
            ->distinct()
            ->get();
    }

    public function changeVerification($id){
        $nasabah = ModelNasabah::findOrFail($id);
        $nasabah->status_verification = !$nasabah->status_verification;
        $nasabah->save();

        session()->flash('alert', [
            'type' => 'success',
            'message' => 'Berhasil.',
            'detail' => "nasabah $nasabah->name berhasil di verfikasi.",
        ]);
    }

    #[Computed()]
    public function rows()
    {
        $query = ModelNasabah::query()
            ->when(!$this->sorts, fn ($query) => $query->first())
            ->when($this->filters['ktp'], function($query, $ktp){
                $query->where('number_identity', $ktp);
            })
            ->when($this->year, function($query, $year){
                $query->whereYear('created_at', $year);
            })
            ->when($this->filters['search'], function ($query, $search) {
                $query->where('status_verification',true)->whereAny(['name','number_identity','address','job','age','email'], 'LIKE', "%$search%");
            })->where('status_verification',true);

        return $this->applyPagination($query);
    }

    #[Computed()]
    public function allData()
    {
        return ModelNasabah::all();
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
        return view('livewire.report.nasabah');
    }
}
