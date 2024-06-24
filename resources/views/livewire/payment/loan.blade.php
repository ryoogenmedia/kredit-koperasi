<div>
    <x-slot name="title">Daftar Pinjaman</x-slot>

    <x-slot name="pagePretitle">Daftar Data Pinjaman Nasabah</x-slot>

    <x-slot name="pageTitle">Daftar Pinjaman</x-slot>

    <x-alert />

    <div class="row mb-3 align-items-center justify-content-between">
        <div class="col-12 col-lg-5 d-flex">
            <div>
                <x-datatable.search placeholder="Cari nama nasabah..." />
            </div>

            <div class="ms-2">
                <x-datatable.filter.button target="verification-nasabah" />
            </div>
        </div>
    </div>

    <x-datatable.filter.card id="verification-nasabah">
        <div class="row">
            <div class="col-lg-3 col-12">
                <x-form.input
                    wire:model.live="filters.ktp"
                    name="filters.ktp"
                    label="NO KTP"
                    placeholder="masukkan no indentitias"
                    type="text"
                />
            </div>

            <div class="col-lg-3 col-12">
                <x-form.select
                    wire:model.lazy="filters.status"
                    name="filters.status"
                    label="Status Pembayaran"
                >

                    <option selected value=""> - Pilih Status Pembayaran - </option>
                    @foreach (config('const.status_payment') as $status)
                        <option wire:key="row-{{ $status }}" value="{{ $status }}">{{ ucwords($status) }}
                        </option>
                    @endforeach

                </x-form.select>
            </div>

            <div class="col-lg-2 col-12">
                <x-form.input
                    wire:model.live="filters.bunga_pinjaman"
                    name="filters.bunga_pinjaman"
                    label="Bunga Pinjaman"
                    type="number"
                />
            </div>

            <div class="col-lg-2 col-12">
                <x-form.input
                    wire:model.live="filters.angsuran"
                    name="filters.angsuran"
                    label="Angsuran Pembayaran"
                    type="number"
                />
            </div>

            <div class="col-lg-2 col-12">
                <x-form.input
                    wire:model.live="filters.total_pinjaman"
                    name="filters.total_pinjaman"
                    label="Total Pinjaman"
                    type="number"
                />
            </div>
        </div>
    </x-datatable.filter.card>

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
