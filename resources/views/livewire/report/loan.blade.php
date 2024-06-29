<div>
    <x-slot name="title">Laporan Pinjaman</x-slot>

    <x-slot name="pagePretitle">Laporan Data Pinjaman Nasabah</x-slot>

    <x-slot name="pageTitle">Laporan Pinjaman</x-slot>

    <x-alert />

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
            <div class="col-lg-3 col-12 ms-2">
                <x-form.select
                    wire:model.lazy="filters.status"
                    name="filters.status"
                >

                    <option selected value=""> - Pilih Status Pembayaran - </option>
                    @foreach (config('const.status_payment') as $status)
                        <option wire:key="row-{{ $status }}" value="{{ $status }}">{{ ucwords($status) }}
                        </option>
                    @endforeach

                </x-form.select>
            </div>
        </div>

        <div class="col-auto ms-auto d-flex mt-lg-0 mt-md-0 mt-3 align-self-center">
            <button wire:click='cetakPinjaman' class="btn btn-danger mb-3">Cetak <i class="las la-print ms-2" style="font-size: 20px"></i></button>
        </div>
    </div>

    <div class="card" wire:loading.class.delay="card-loading" wire:offline.class="card-loading">
        <div class="table-responsive mb-0">
            <table class="table card-table table-bordered datatable">
                <thead>
                    <tr>
                        <th>
                            <x-datatable.column-sort name="Nasabah" wire:click="sortBy('name')" :direction="$sorts['name'] ?? null" />
                        </th>

                        <th>Status Pembayaran</th>

                        <th>
                            <x-datatable.column-sort name="Total Pinjaman" wire:click="sortBy('amount')" :direction="$sorts['amount'] ?? null" />
                        </th>

                        <th>
                            <x-datatable.column-sort name="Bunga Pinjaman" wire:click="sortBy('interest')" :direction="$sorts['interest'] ?? null" />
                        </th>

                        <th>
                            <x-datatable.column-sort name="Angsuran Pembayaran" wire:click="sortBy('installments')" :direction="$sorts['installments'] ?? null" />
                        </th>

                        <th>
                            <x-datatable.column-sort name="Tanggal Pengajuan" wire:click="sortBy('date')" :direction="$sorts['date'] ?? null" />
                        </th>

                    </tr>
                </thead>

                <tbody>
                    @forelse ($this->rows as $index => $row)

                    @php
                        $rowspan = $row->count() > 0 ? $row->count() : 1;
                    @endphp

                        <tr wire:key="row-{{ $row->id }}-{{ $index }}">
                            @if ($index === 0)
                                <td rowspan="{{ $index }}">
                                    <div class="d-flex flex-column">
                                        <div class="ms-2">{{ $row->nasabah->name }}</div>
                                        <div class="ms-2">{{ $row->nasabah->email }}</div>
                                        <div class="ms-2">{{ $row->nasabah->number_identity }}</div>
                                    </div>
                                </td>
                            @endif

                            <td style="padding: 15px;">
                                <span class="badge bg-{{ $row->detail->date_repayment ? 'success' : 'danger' }}-lt">
                                    {{ $row->detail->date_repayment ? 'lunas' : 'belum lunas' }}
                                </span>
                            </td>

                            <td style="padding: 15px;">
                                {{ money_format_idr($row->amount) }}
                            </td>

                            <td style="padding: 15px;">
                                <span>%</span>
                                {{ $row->interest }}
                            </td>

                            <td style="padding: 15px;">{{ $row->installments }}x pembayaran</td>

                            <td style="padding: 15px;">{{ $row->date }}</td>

                        </tr>
                    @empty
                        <x-datatable.empty colspan="7"/>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $this->rows->links() }}
    </div>
</div>
