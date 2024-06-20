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

        return redirect()->route('nasabah.index');
    }

    #[Computed()]
    public function rows()
    {
        $query = Nasabah::query()
            ->when(!$this->sorts, fn ($query) => $query->first())
            ->when($this->filters['search'], function ($query, $search) {
                $query->whereAny(['username','roles','email'], 'LIKE', "%$search%");
            });

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
