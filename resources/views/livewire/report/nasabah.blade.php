<div>
    <x-slot name="title">Laporan Nasabah</x-slot>

    <x-slot name="pagePretitle">Daftar Laporan Nasabah</x-slot>

    <x-slot name="pageTitle">Laporan Nasabah</x-slot>

    <x-alert />

    <x-modal.delete-confirmation />

    <div class="row mb-3 align-items-center justify-content-between">
        <div class="col-12 col-lg-5 d-flex">
            <div>
                <x-datatable.search placeholder="Cari nama nasabah..." />
            </div>
            <div class="ms-2">
                <input
                    wire:model='filters.ktp'
                    name="ktp"
                    class="form-control"
                    placeholder=""
                />
            </div>
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

                    </tr>
                </thead>

                <tbody>
                    @forelse ($this->rows as $row)
                        <tr wire:key="row-{{ $row->id }}">
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
