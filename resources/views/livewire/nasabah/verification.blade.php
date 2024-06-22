<div>
    <x-slot name="title">Data Verifikasi Nasabah</x-slot>

    <x-slot name="pagePretitle">Daftar Data Verifikasi Nasabah</x-slot>

    <x-slot name="pageTitle">Data Verifikasi Nasabah</x-slot>

    <x-alert />

    <x-modal.delete-confirmation />

    <div class="row mb-3 align-items-center justify-content-between">
        <div class="col-12 col-lg-5 d-flex">
            <div>
                <x-datatable.search placeholder="Cari nama nasabah..." />
            </div>
        </div>

        <div class="col-auto ms-auto d-flex">
            <x-datatable.items-per-page />

            <x-datatable.bulk.dropdown>
                <div class="dropdown-menu dropdown-menu-end">
                    <button data-bs-toggle="modal" data-bs-target="#delete-confirmation" class="dropdown-item"
                        type="button">
                        <i class="las la-trash me-3"></i>

                        <span>Hapus</span>
                    </button>
                </div>
            </x-datatable.bulk.dropdown>
        </div>
    </div>

    <div class="card" wire:loading.class.delay="card-loading" wire:offline.class="card-loading">
        <div class="table-responsive mb-0">
            <table class="table card-table table-bordered datatable">
                <thead>
                    <tr>
                        <th class="w-1">
                            <x-datatable.bulk.check wire:model.lazy="selectPage" />
                        </th>
                        <th>
                            <x-datatable.column-sort name="Nasabah" wire:click="sortBy('name')" :direction="$sorts['name'] ?? null" />
                        </th>
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

                        <th></th>
                    </tr>
                </thead>

                <tbody>
                    @if ($selectPage)
                        <tr>
                            <td colspan="10" class="bg-red-lt">
                                @if (!$selectAll)
                                    <div class="text-red">
                                        <span>Anda telah memilih <strong>{{ $this->rows->total() }}</strong> nasabah,
                                            apakah
                                            Anda mau memilih semua <strong>{{ $this->rows->total() }}</strong>
                                            nasabah?</span>

                                        <button wire:click="selectedAll" class="btn ms-2">Pilih Semua</button>
                                    </div>
                                @else
                                    <span class="text-pink">Anda sekarang memilih semua
                                        <strong>{{ count($this->selected) }}</strong> nasabah.
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endif

                    @forelse ($this->rows as $row)
                        @php
                            $rowspan = $row->pinjaman->count() > 0 ? $row->pinjaman->count() : 1;
                        @endphp

                        @foreach ($row->pinjaman as $index => $pinjaman)
                            <tr wire:key="row-{{ $row->id }}-{{ $index }}">
                                @if ($index === 0)
                                    <td rowspan="{{ $rowspan }}">
                                        <x-datatable.bulk.check wire:model.lazy="selected" value="{{ $row->id }}" />
                                    </td>

                                    <td rowspan="{{ $rowspan }}">
                                        <div class="d-flex flex-column">
                                            <div class="ms-2">{{ $row->name }}</div>
                                            <div class="ms-2">{{ $row->email }}</div>
                                            <div class="ms-2">{{ $row->phone }}</div>
                                        </div>
                                    </td>
                                @endif

                                <td style="padding: 15px;">
                                    {{ money_format_idr($pinjaman->amount) }}
                                    <p><span class="badge bg-{{ $pinjaman->detail->date_acc_loan ? 'success' : 'orange' }}-lt mt-2">{{ $pinjaman->detail->date_acc_loan ? 'di setujui' : 'belum di setujui' }}</span></p>
                                </td>

                                <td class="w-50" style="padding: 15px;">
                                    <span>%</span>
                                    <input
                                        value="{{ $pinjaman->interest }}"
                                        wire:change='changeInterest({{ $pinjaman->id }}, $event.target.value)'
                                        type="number"
                                        class="form-control w-66 d-inline ms-2"
                                    >
                                </td>

                                <td style="padding: 15px;">{{ $pinjaman->installments }}x pembayaran</td>

                                <td style="padding: 15px;">{{ $pinjaman->date->diffForHumans() }}</td>

                                <td style="padding: 5px 10px; padding-right: 20px;">
                                    @if (!$pinjaman->detail->date_acc_loan)
                                        <div class="d-flex mt-2">
                                            <div class="ms-auto">
                                                <button wire:confirm='Apakah anda yakin ingin menyetujui pinjaman nasabah?' wire:click='changeDetailPinjaman({{ $pinjaman->detail->id }})' class="btn btn-sm btn bg-success-lt w-100">
                                                    Setujui Pinjaman
                                                </button>
                                            </div>
                                        </div>
                                    @else
                                        <div class="d-flex mt-2">
                                            <div class="ms-auto">
                                                <button wire:confirm='Apakah anda yakin ingin membatalkan pinjaman nasabah? apa yang anda lakukan akan menghapus seluruh history pinjaman!' wire:click='changeDetailPinjaman({{ $pinjaman->detail->id }})' class="btn btn-sm btn bg-orange-lt w-100">
                                                    Batalkan Pinjaman
                                                </button>
                                            </div>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach

                        @if ($row->pinjaman->isEmpty())
                            <tr>
                                <td rowspan="1">
                                    <x-datatable.bulk.check wire:model.lazy="selected" value="{{ $row->id }}" />
                                </td>

                                <td rowspan="1">
                                    <div class="d-flex flex-column">
                                        <div class="ms-2">{{ $row->name }}</div>
                                        <div class="ms-2">{{ $row->email }}</div>
                                        <div class="ms-2">{{ $row->phone }}</div>
                                    </div>
                                </td>

                                <td colspan="4" style="text-align: center;">
                                    <em>Tidak ada data pinjaman</em>
                                </td>
                            </tr>
                        @endif
                    @empty
                        <x-datatable.empty colspan="7"/>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $this->rows->links() }}
    </div>
</div>
