<?php

namespace App\Livewire\Nasabah;

use App\Livewire\Traits\DataTable\WithBulkActions;
use App\Livewire\Traits\DataTable\WithCachedRows;
use App\Livewire\Traits\DataTable\WithPerPagePagination;
use App\Livewire\Traits\DataTable\WithSorting;
use App\Models\Nasabah;
use Livewire\Attributes\Computed;

use Livewire\Component;

class Index extends Component
{
    use WithBulkActions;
    use WithPerPagePagination;
    use WithCachedRows;
    use WithSorting;

    public $filters = [
        'search' => '',
        'ktp' => '',
        'nomor_ponsel' => '',
        'umur' => '',
        'pekerjaan' => '',
    ];

    #[Computed()]
    public function jobs(){
        return Nasabah::query()
            ->select('job')
            ->groupBy('job')
            ->distinct()
            ->get();
    }

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

        return redirect()->route('nasabah.index');
    }

    public function changeVerification($id){
        $nasabah = Nasabah::findOrFail($id);
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
        $query = Nasabah::query()
            ->when(!$this->sorts, fn ($query) => $query->first())
            ->when($this->filters['ktp'], function($query, $ktp){
                $query->where('number_identity', $ktp);
            })
            ->when($this->filters['nomor_ponsel'], function($query, $nomorPonsel){
                $query->where('phone', $nomorPonsel);
            })
            ->when($this->filters['umur'], function($query, $umur){
                $query->where('age', $umur);
            })
            ->when($this->filters['pekerjaan'], function($query, $pekerjaan){
                $query->where('job', $pekerjaan);
            })
            ->when($this->filters['search'], function ($query, $search) {
                $query->where('status_verification',false)->whereAny(['name','number_identity','address','job','age','email'], 'LIKE', "%$search%");
            })->where('status_verification',false);

        return $this->applyPagination($query);
    }

    #[Computed()]
    public function allData()
    {
        return Nasabah::all();
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
        return view('livewire.nasabah.index');
    }
}
