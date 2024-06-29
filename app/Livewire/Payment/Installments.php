<?php

namespace App\Livewire\Payment;

use App\Livewire\Traits\DataTable\WithBulkActions;
use App\Livewire\Traits\DataTable\WithCachedRows;
use App\Livewire\Traits\DataTable\WithPerPagePagination;
use App\Livewire\Traits\DataTable\WithSorting;
use App\Models\Angsuran;
use App\Models\Nasabah;
use App\Models\Pinjaman;
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
        'ktp' => '',
        'status_confirm' => '',
        'status_installments' => '',
    ];

    public $show = false;
    public $angsuranId;
    public $imageProof;

    public function openModal($id){
        $angsuran = Angsuran::findOrFail($id);
        $this->show = true;

        if($angsuran){
            $this->imageProof = $angsuran->proof;
            $this->angsuranId = $angsuran->id;
        }
    }

    public function confirmationInstallments(){
        $angsuran = Angsuran::findOrFail($this->angsuranId);

        $angsuran->update([
            'confirmation_repayment' => true,
        ]);

        $pinjaman = Pinjaman::findOrFail($angsuran->pinjaman_id);

        $jmlAngsuran = $pinjaman->angsuran->count();

        if($jmlAngsuran == $pinjaman->interest){
            $pinjaman->detail->update([
                'date_repayment' => now(),
            ]);
        }

        session()->flash('alert', [
            'type' => 'success',
            'message' => 'Berhasil.',
            'detail' => "Angsuran berhasil di konfirmasi.",
        ]);

        $this->show = false;
        return redirect()->route('payment.installments');
    }

    public function closeModal(){
        $this->show = false;
    }

    #[Computed()]
    public function rows()
    {
        $query = Angsuran::query()
            ->when(!$this->sorts, fn ($query) => $query->first())
            ->when($this->filters['status_confirm'], function($query, $statusConfirm){
                if($statusConfirm == 'butuh konfirmasi'){
                    $query->where('confirmation_repayment', false);
                }

                if($statusConfirm == 'terkonfirmasi'){
                    $query->where('confirmation_repayment', true);
                }
            })
            ->when($this->filters['status_installments'], function($query, $statusInstallments){
                if($statusInstallments == 'sudah bayar'){
                    $query->whereHas('detail', function($query){
                        $query->whereNotNull('proof');
                    });
                }

                if($statusInstallments == 'belum bayar' || $statusInstallments ==  'menunggu pembayaran'){
                    $query->whereHas('detail', function($query){
                        $query->whereNull('proof');
                    });
                }
            })->when($this->filters['ktp'], function($query, $ktp){
                $query->whereHas('nasabah', function($query) use ($ktp){
                    $query->where('number_identity', $ktp);
                });
            })->with('pinjaman', function($q){
                $q->with('nasabah')->select('*')->groupBy('nasabah_id');
            })->whereHas('pinjaman', function($query){
                $query->where('confirmation_nasabah', true)
                    ->where('status_akad', 'di berikan')
                    ->whereNotNull('date');
            });

        return $this->applyPagination($query);
    }

    #[Computed()]
    public function allData()
    {
        return Angsuran::whereHas('pinjaman', function($query){
            $query->where('confirmation_nasabah', true)
                ->where('status_akad', 'di berikan')
                ->whereNotNull('date')
                ->whereHas('detail', function($query){
                    $query->whereNotNull('date_acc_loan')
                        ->whereNotNull('proof_funds');
                });
        });
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
