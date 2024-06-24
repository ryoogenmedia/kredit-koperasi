<?php

namespace App\Livewire\Akad;

use App\Livewire\Traits\DataTable\WithBulkActions;
use App\Livewire\Traits\DataTable\WithCachedRows;
use App\Livewire\Traits\DataTable\WithPerPagePagination;
use App\Livewire\Traits\DataTable\WithSorting;
use App\Models\Pinjaman;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Funds extends Component
{
    use WithBulkActions;
    use WithPerPagePagination;
    use WithCachedRows;
    use WithSorting;

    public $filters = [
        'search' => '',
        'ktp' => '',
        'status' => '',
        'bunga_pinjaman' => '',
        'angsuran' => '',
        'total_pinjaman' => '',
    ];

    public function deleteSelected()
    {
        $pinjaman = Pinjaman::whereIn('id', $this->selected)->get();
        $deleteCount = $pinjaman->count();

        foreach ($pinjaman as $data) {
            $data->delete();
        }

        $this->reset();

        session()->flash('alert', [
            'type' => 'success',
            'message' => 'Berhasil.',
            'detail' => "$deleteCount data pinjaman berhasil dihapus.",
        ]);

        return redirect()->route('akad.index');
    }

    #[Computed()]
    public function rows()
    {
        $query = Pinjaman::query()
            ->when(!$this->sorts, fn ($query) => $query->first())
            ->when($this->filters['status'], function($query, $status){
                $query->where('status_akad', $status);
            })
            ->when($this->filters['ktp'], function($query, $ktp){
                $query->whereHas('nasabah', function($query) use ($ktp){
                    $query->where('number_identity', $ktp);
                });
            })
            ->when($this->filters['total_pinjaman'], function($query, $totalPinjaman){
                $query->where('confirmation_nasabah', true)->where('amount', $totalPinjaman);
            })
            ->when($this->filters['bunga_pinjaman'], function($query, $bunga){
                $query->where('confirmation_nasabah', true)->where('interest', $bunga);
            })
            ->when($this->filters['angsuran'], function($query, $angsuran){
                $query->where('confirmation_nasabah', true)->where('installments', $angsuran);
            })
            ->when($this->filters['search'], function ($query, $search) {
                $query->where('confirmation_nasabah', true)->whereAny(['amount','interest','date','installments','amount_installments'], 'LIKE', "%$search%");
            })->whereHas('detail', function($query){
                $query->whereNotNull('date_acc_loan');
            })->where('confirmation_nasabah', true);

        return $this->applyPagination($query);
    }

    #[Computed()]
    public function allData()
    {
        return Pinjaman::query()
            ->whereHas('detail', function($query){
                $query->whereNotNull('date_acc_loan');
            })->where('confirmation_nasabah', true)->get();
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
        return view('livewire.akad.funds');
    }
}
