<div>
    <x-slot name="title">Laporan Angsuran</x-slot>

    <x-slot name="pagePretitle">Daftar Laporan Angsuran</x-slot>

    <x-slot name="pageTitle">Laporan Angsuran</x-slot>

    <x-alert />

    <x-modal.delete-confirmation />

    <div class="row mb-3 align-items-center justify-content-between">
        <div class="col-12 col-lg-9 d-flex">
            <div>
                <x-datatable.search placeholder="Cari nama nasabah..." />
            </div>
            <div class="ms-2">
                <input
                    wire:model.lazy='filters.ktp'
                    name="ktp"
                    class="form-control"
                    placeholder="Nomor KTP"
                />
            </div>
            <div class="ms-2">
                <x-form.select
                    wire:model.lazy='year'
                    name='year'
                >
                    <option value="">- Pilih Tahun -</option>
                    @foreach ($this->getYears as $data)
                        <option value="{{ $data->year }}">{{ $data->year }}</option>
                    @endforeach
                </x-form.select>
            </div>
        </div>

        <div class="col-auto ms-auto d-flex mt-lg-0 mt-md-0 mt-3 align-self-center">
            <button wire:click='cetakAngsuran' class="btn btn-danger mb-3">Cetak <i class="las la-print ms-2" style="font-size: 20px"></i></button>
        </div>
    </div>

    <div class="card" wire:loading.class.delay="card-loading" wire:offline.class="card-loading">
        <div class="table-responsive mb-0">
            <table class="table card-table table-bordered datatable">
                <thead>
                    <tr>
                        <th>NASABAH</th>

                        <th>NO KTP</th>

                        <th>TANGGAL ANGSURAN</th>

                        <th>ANGSURAN KE</th>

                        <th>SISA ANGSURAN</th>

                        <th>SISA PINJAMAN</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($this->rows as $row)
                        <tr wire:key="row-{{ $row->id }}">
                            <td>
                                <div class="d-flex flex-column">
                                    <div class="ms-2">{{ $row->nasabah->name }}</div>
                                    <div class="ms-2">{{ $row->nasabah->email }}</div>
                                </div>
                            </td>

                            <td>{{ $row->nasabah->number_identity ?? '-' }}</td>

                            <td>{{ $row->date_installments ?? '-' }}</td>

                            <td>{{ $row->installments_to ?? '-' }}</td>

                            <td>{{ $row->remaining_installments ?? '-' }}</td>

                            <td>{{ money_format_idr($row->remaining_loan) }}</td>
                        </tr>
                    @empty
                        <x-datatable.empty colspan="10" />
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $this->rows->links() }}
    </div>
</div>
