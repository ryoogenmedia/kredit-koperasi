<?php

namespace App\Livewire\Akad;

use App\Livewire\Traits\DataTable\WithBulkActions;
use App\Livewire\Traits\DataTable\WithCachedRows;
use App\Livewire\Traits\DataTable\WithPerPagePagination;
use App\Livewire\Traits\DataTable\WithSorting;
use App\Models\DetailPinjaman;
use App\Models\Pinjaman;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithFileUploads;

class Funds extends Component
{
    use WithBulkActions;
    use WithPerPagePagination;
    use WithCachedRows;
    use WithSorting;
    use WithFileUploads;

    public $filters = [
        'search' => '',
        'ktp' => '',
        'status' => '',
        'bunga_pinjaman' => '',
        'angsuran' => '',
        'total_pinjaman' => '',
    ];

    public $show = false;
    public $showTwo = false;
    public $buktiTransfer;
    public $detailPinjamanId;
    public $pinjamanId;
    public $imageBuktiTransfer;

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

    public function openModal($id){
        $pinjaman = Pinjaman::findOrFail($id);

        if($pinjaman){
            $this->pinjamanId = $pinjaman->id;
            $this->detailPinjamanId = $pinjaman->detail->id;
        }

        $this->show = true;
    }

    public function closeModal(){
        $this->show = false;
    }

    public function openModalTwo($id){
        $pinjaman = Pinjaman::findOrFail($id);
        $this->imageBuktiTransfer = $pinjaman->detail->proof_funds;
        $this->showTwo = true;
    }

    public function closeModalTwo(){
        $this->showTwo = false;
    }

    public function save(){
        $this->validate([
            'buktiTransfer' => ['required','image','mimes:png,jpg','max:2048'],
        ]);

        $detailPinjaman = DetailPinjaman::findOrFail($this->detailPinjamanId);

        try{
            DB::beginTransaction();

            $detailPinjaman->update([
                'proof_funds' => $this->buktiTransfer->store('nasabah/proof-funds','public'),
            ]);

            DB::commit();
        }catch(Exception $e){
            session()->flash('alert', [
                'type' => 'danger',
                'message' => 'Gagal.',
                'detail' => "Pencairan dana gagal di lakukan!.",
            ]);
        }

        session()->flash('alert', [
            'type' => 'success',
            'message' => 'Berhasil.',
            'detail' => "Pencairan dana berhasil di lakukan.",
        ]);

        $this->show = false;
        return redirect()->route('akad.funds');
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
