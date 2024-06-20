<div>
    <x-slot name="title">Data Verfikasi Nasabah</x-slot>

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
                            <x-datatable.column-sort name="NO KTP" wire:click="sortBy('number_identity')" :direction="$sorts['number_identity'] ?? null" />
                        </th>

                        <th>
                            <x-datatable.column-sort name="Alamat" wire:click="sortBy('address')" :direction="$sorts['address'] ?? null" />
                        </th>

                        <th>
                            <x-datatable.column-sort name="Pekerjaan" wire:click="sortBy('job')" :direction="$sorts['job'] ?? null" />
                        </th>

                        <th>
                            <x-datatable.column-sort name="Umur" wire:click="sortBy('age')" :direction="$sorts['age'] ?? null" />
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
                        <tr wire:key="row-{{ $row->id }}">
                            <td>
                                <x-datatable.bulk.check wire:model.lazy="selected" value="{{ $row->id }}" />
                            </td>

                            <td>
                                <div class="d-flex flex-column">
                                    <div class="ms-2">{{ $row->name }}</div>
                                    <div class="ms-2">{{ $row->email }}</div>
                                    <div class="ms-2">{{ $row->phone }}</div>
                                </div>
                            </td>

                            <td>{{ $row->number_identity ?? '-' }}</td>

                            <td>{{ $row->address ?? '-' }}</td>

                            <td>{{ $row->job ?? '-' }}</td>

                            <td>{{ $row->age ?? '-' }}</td>

                            <td>
                                <div class="d-flex">
                                    <div style="width: 150px"class="ms-auto">
                                        <button
                                            wire:confirm='Apakah anda yakin ingin {{ !$row->status_verification ? 'men-konfirmasi nasabah ini?' : 'membatalkan verfikasi nasabah ini?' }}'
                                            wire:click='changeVerification({{ $row->id }})'
                                            class="btn btn bg-{{ !$row->status_verification ? 'success' : 'orange' }}-lt w-100"
                                        >
                                            {{ !$row->status_verification ? 'Verifikasi' : 'Batalkan Verifikasi' }}
                                        </button>
                                    </div>
                                </div>

                                <div class="d-flex mt-2">
                                    <div style="width: 150px"class="ms-auto">
                                        <button
                                            class="btn btn bg-success-lt w-100"
                                        >
                                            Setujui Pinjaman
                                        </button>
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
