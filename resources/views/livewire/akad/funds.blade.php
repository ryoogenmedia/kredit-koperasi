<div>
    <x-slot name="title">Data Pencairan Dana</x-slot>

    <x-slot name="pagePretitle">Daftar Data Pencairan Dana</x-slot>

    <x-slot name="pageTitle">Data Pencairan Dana</x-slot>

    <x-alert />

    <x-modal.delete-confirmation />

    <x-modal size="md" :show="$show">
        <form wire:submit='save'>
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Pencairan Dana</h5>
                <button wire:click='closeModal' type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <x-form.input
                    wire:model='buktiTransfer'
                    type='file'
                    name="buktiTransfer"
                    label="Bukti Transfer"
                />
            </div>
            <div class="modal-footer">
                <div class="btn-list justify-content-end">
                    <button type="reset" class="btn">Reset</button>

                    <x-datatable.button.save
                        name="Cairkan Dana"
                        target="save"
                    />
                </div>
            </div>
        </form>
    </x-modal>

    <x-modal size="md" :show="$showTwo">
        <div class="modal-header">
            <h5 class="modal-title">Bukti Transfer Pencairan Dana</h5>
            <button wire:click='closeModalTwo' type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" style="overflow: hidden">
            <img style="width: 500px; height: 300px; object-fit:cover" src="{{ asset('storage/' . $this->imageBuktiTransfer) }}" alt="bukti-transfer-pencairan-dana">
        </div>
        <div class="modal-footer">
            <div class="btn-list justify-content-end">
                <button wire:click='closeModalTwo' type="reset" class="btn">Tutup</button>
            </div>
        </div>
    </x-modal>

    <div class="row mb-3 align-items-center justify-content-between">
        <div class="col-12 col-lg-5 d-flex">
            <div>
                <x-datatable.search placeholder="Cari nama nasabah..." />
            </div>

            <div class="ms-2">
                <x-datatable.filter.button target="akad-pinjaman" />
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

    <x-datatable.filter.card id="akad-pinjaman">
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
                    label="Status Pencairan"
                >

                    <option selected value=""> - Pilih Status Pencairan - </option>
                    @foreach (config('const.status_pencairan') as $status)
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
                        <th class="w-1">
                            <x-datatable.bulk.check wire:model.lazy="selectPage" />
                        </th>

                        <th>
                            <x-datatable.column-sort name="Nasabah" wire:click="sortBy('name')" :direction="$sorts['name'] ?? null" />
                        </th>

                        <th>
                            <x-datatable.column-sort name="Status Pencairan" wire:click="sortBy('name')" :direction="$sorts['name'] ?? null" />
                        </th>

                        <th>
                            <x-datatable.column-sort name="Total Pinjaman" wire:click="sortBy('number_identity')" :direction="$sorts['number_identity'] ?? null" />
                        </th>

                        <th>
                            <x-datatable.column-sort name="Bunga Pinjaman" wire:click="sortBy('address')" :direction="$sorts['address'] ?? null" />
                        </th>

                        <th>
                            <x-datatable.column-sort name="Angsuran" wire:click="sortBy('job')" :direction="$sorts['job'] ?? null" />
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
                                        <span>Anda telah memilih <strong>{{ $this->rows->total() }}</strong> pinjaman,
                                            apakah
                                            Anda mau memilih semua <strong>{{ $this->rows->total() }}</strong>
                                            pinjaman?</span>

                                        <button wire:click="selectedAll" class="btn ms-2">Pilih Semua</button>
                                    </div>
                                @else
                                    <span class="text-pink">Anda sekarang memilih semua
                                        <strong>{{ count($this->selected) }}</strong> pinjaman.
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endif

                    @forelse ($this->rows as $row)
                        <tr wire:key="row-{{ $row->id }}">
                            <td>
                                <x-datatable.bulk.check wire:model.lazy="selected" value="{{ $row->id }}" />
                            </td>

                            <td>
                                <div class="d-flex flex-column">
                                    <div class="ms-2">{{ $row->nasabah->name }}</div>
                                    <div class="ms-2">{{ $row->nasabah->email }}</div>
                                    <div class="ms-2">{{ $row->nasabah->number_identity }}</div>
                                </div>
                            </td>

                            <td>
                                <p><span class="badge bg-{{ $row->detail->proof_funds ? 'success' : 'danger' }}-lt">{{ $row->detail->proof_funds ? 'sudah di berikan' : 'belum di berikan' }}</span></p>
                            </td>

                            <td>{{ money_format_idr($row->amount) }}</td>

                            <td>% {{ $row->interest ?? '-' }}</td>

                            <td>{{ $row->installments }}x Pembayaran Per {{ ucwords($row->installments_type) }}</td>

                            <td>
                                <div class="d-flex">
                                    <div class="ms-auto">
                                        @if ($row->detail->proof_funds)
                                            <button wire:click='openModalTwo({{ $row->id }})' class="btn">Lihat Bukti Transfer</button>
                                        @else
                                            <button wire:click='openModal({{ $row->id }})' class="btn btn-success">Carikan Dana</button>
                                        @endif
                                    </div>
                                </div>
                            </td>
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
