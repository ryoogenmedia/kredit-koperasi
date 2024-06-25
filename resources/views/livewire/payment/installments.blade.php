<div>
    <x-slot name="title">Daftar Angsuran</x-slot>

    <x-slot name="pagePretitle">Daftar Angsuran Pembayaran Nasabah</x-slot>

    <x-slot name="pageTitle">Daftar Angsuran</x-slot>

    <x-alert />

    <x-modal size="md" :show="$show">
        <div class="modal-header">
            <h5 class="modal-title">Konfirmasi Pembayaran</h5>
            <button wire:click='closeModal' type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body overflow-hidden">
            <img width="500px" height="300px" src="{{ asset('storage/' . $this->imageProof) }}" alt="bukti-bayar-nasabah">
        </div>
        <div class="modal-footer">
            <div class="btn-list justify-content-end">
                <button wire:click='closeModal' type="reset" class="btn">Tutup</button>
                <button wire:click='confirmationInstallments' class="btn btn-success">Konfirmasi Pembayaran</button>
            </div>
        </div>
    </x-modal>

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
            <div class="col-lg-4 col-12">
                <x-form.input
                    wire:model.live="filters.ktp"
                    name="filters.ktp"
                    label="NO KTP"
                    placeholder="masukkan no indentitias"
                    type="text"
                />
            </div>

            <div class="col-lg-4 col-12">
                <x-form.select
                    wire:model.lazy="filters.status_installments"
                    name="filters.status_installments"
                    label="Status Angsuran"
                >

                    <option selected value=""> - Pilih Status Angsuran - </option>
                    @foreach (config('const.status_installments') as $status)
                        <option wire:key="row-{{ $status }}" value="{{ $status }}">{{ ucwords($status) }}
                        </option>
                    @endforeach

                </x-form.select>
            </div>

            <div class="col-lg-4 col-12">
                <x-form.select
                    wire:model.lazy="filters.status_confirm"
                    name="filters.status_confirm"
                    label="Status Konfirmasi"
                >

                    <option selected value=""> - Pilih Status Konfirmasi - </option>
                    @foreach (config('const.status_confirm') as $status)
                        <option wire:key="row-{{ $status }}" value="{{ $status }}">{{ ucwords($status) }}
                        </option>
                    @endforeach

                </x-form.select>
            </div>
        </div>
    </x-datatable.filter.card>

    <div class="card" wire:loading.class.delay="card-loading" wire:offline.class="card-loading">
        <div class="table-responsive mb-0">
            <table class="table card-table table-bordered datatable">
                <thead>
                    <tr>
                        <th>Nasabah</th>

                        <th>Tanggal Jatuh Tempo</th>

                        <th>Status</th>

                        <th>Pokok Awal Nasabah</th>

                        <th>Bunga Bulanan</th>

                        <th>Angsuran Bulanan</th>

                        <th>Pembayaran Pokok</th>

                        <th>Sisa Pokok Nasabah</th>

                        <th></th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($this->rows as $index => $row)
                        @php
                            $amortisasi = hitungAmortisasi($row->pinjaman->id);
                        @endphp

                        @foreach ($amortisasi['tabel_amortisasi'] as  $data)
                            <tr wire:key="row-{{ $row->id }}-{{ $loop->iteration }}">
                                @if ($loop->iteration == 1)
                                    <td class="p-0" rowspan="{{ $row->pinjaman->installments }}">
                                        <div class="p-3 d-flex flex-column">
                                            <div class="ms-2"><strong>{{ $row->nasabah->name }}</strong></div>
                                            <div class="ms-2">{{ $row->nasabah->email }}</div>
                                            <div class="ms-2">{{ $row->nasabah->number_identity }}</div>
                                        </div>

                                        <hr class="p-0 m-0"/>

                                        <div class="p-3 d-flex flex-column">
                                            <div class="ms-2">Pinjaman : <strong>{{ money_format_idr($row->pinjaman->amount) }}</strong></div>
                                            <div class="ms-2">Bunga    : <strong>% {{ $row->pinjaman->interest }}</strong></div>
                                            <div class="ms-2">Angsuran : <strong>{{ $row->pinjaman->installments }}x Per {{ ucwords($row->pinjaman->installments_type) }}</strong></div>
                                            <div class="ms-2">Sisa Angsuran : <strong>{{ $row->pinjaman->latestAngsuran->remaining_installments }}</strong></div>
                                        </div>

                                        <hr class="p-0 m-0"/>
                                    </td>
                                @endif

                                <td style="padding: 15px;">
                                    @if ($row->pinjaman->installments_type == 'bulan')
                                        {{ $row->pinjaman->date->addDay(30*$loop->iteration)->format('d/m/Y') }}
                                    @endif

                                    @if ($row->pinjaman->installments_type == 'hari')
                                        {{ $row->pinjaman->date->addDay(1*$loop->iteration)->format('d/m/Y') }}
                                    @endif

                                    @if ($row->pinjaman->installments_type == 'tahun')
                                        {{ $row->pinjaman->date->addYear(1*$loop->iteration)->format('d/m/Y') }}
                                    @endif
                                </td>

                                <td style="padding: 15px;">
                                    @if ($row->installments_to == $loop->iteration)
                                        <p class="mb-0 pb-0">
                                            <span class="badge bg-{{ $row->proof ? 'success' : 'danger' }}-lt">{{ $row->proof ? 'sudah bayar' : 'belum bayar' }}</span>
                                        </p>

                                        <p class="mt-2 mb-0 pb-0">
                                            <span class="badge bg-{{ $row->confirmation_repayment ? 'primary' : 'danger' }}-lt">{{ $row->confirmation_repayment ? 'terkonfirmasi' : 'butuh konfirmasi' }}</span>
                                        </p>
                                    @else
                                        <p class="mt-2 mb-0 pb-0">
                                            <span class="badge bg-orange-lt">menunggu pembayaran</span>
                                        </p>
                                    @endif
                                </td>

                                <td style="padding: 15px;">
                                    {{ $data['pokok_awal'] }}
                                </td>

                                <td style="padding: 15px;">
                                    {{ $data['bunga_bulanan'] }}
                                </td>

                                <td style="padding: 15px;">
                                    {{ $data['angsuran_bulanan'] }}
                                </td>

                                <td style="padding: 15px;">
                                    {{ $data['pembayaran_pokok'] }}
                                </td>

                                <td style="padding: 15px;">
                                    {{ $data['sisa_pokok'] }}
                                </td>

                                <td>
                                    <div class="d-flex">
                                        <div style="width: 150px"class="ms-auto">
                                            <button
                                                {{ $row->installments_to == $loop->iteration && $row->proof ? '' : 'disabled' }}
                                                class="btn btn-success"
                                                wire:click='openModal({{ $row->id }})'
                                            >
                                                Konfirmasi
                                            </button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                        <tr>
                            <td class="text-start" colspan="4"><strong>TOTAL</strong></td>
                            <td class="text-start"><strong>{{ $amortisasi['total_bunga_bulanan'] }}</strong></td>
                            <td class="text-start"><strong>{{ $amortisasi['total_angsuran_bulanan'] }}</strong></td>
                            <td class="text-start" colspan="3"><strong>{{ $amortisasi['total_pembayaran_pokok'] }}</strong></td>
                        </tr>
                    @empty
                        <x-datatable.empty colspan="9"/>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $this->rows->links() }}
    </div>
</div>
