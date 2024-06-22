<div>
    <x-slot name="title">Pemberian Akad</x-slot>

    <x-slot name="pagePretitle">Pemberian Akad Pinjaman</x-slot>

    <x-slot name="pageTitle">Pemberian Akad</x-slot>

    <x-slot name="button">
        <x-datatable.button.back name="Kembali" :route="route('akad.pinjaman.index')" />
    </x-slot>

    <x-alert />

        <div class="card card-lg">
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <p class="h3">Koperasi Setia Karya</p>
                        <address>
                            Jl Perintis<br>
                            Indonesia, Makassar<br>
                            Sudiang, 9940<br>
                            kopsetuakarya@gmail.com
                        </address>
                    </div>
                    <div class="col-6 text-end">
                        <p class="h3">{{ $this->nasabah->name }}</p>
                        <address>
                            {{ $this->nasabah->address }}<br>
                            Indonesia, Makassar<br>
                            {{ $this->nasabah->email }}
                        </address>
                    </div>
                <div class="col-12 my-5">
                    <h1>Akad Pinjaman Dana</h1>
                </div>
            </div>

            <table class="table table-transparent table-responsive">
                <thead>
                    <tr>
                        <th class="text-center"></th>
                        <th>Nama Nasabah</th>
                        <th class="text-center">Alamat Nasabah</th>
                        <th class="text-end">NO KTP</th>
                        <th class="text-end">Nomor Ponsel</th>
                        <th class="text-end">Email</th>
                    </tr>
                </thead>
                <tr>
                    <td class="text-center">1</td>
                    <td>
                        <p class="strong mb-1">{{ $this->nasabah->name }}</p>
                    </td>
                    <td class="text-center">{{ $this->nasabah->address }}</td>
                    <td class="text-end">{{ $this->nasabah->number_identity }}</td>
                    <td class="text-end">{{ $this->nasabah->phone }}</td>
                    <td class="text-end">{{ $this->nasabah->email }}</td>
                </tr>
                <tr>
                    <td colspan="5" class="strong text-end">Bunga Pinjaman</td>
                    <td class="text-end d-flex flex-wrap">
                        <span class="me-2">%</span>
                        @if ($this->pinjaman->status_akad == 'belum di berikan')
                            <input
                                wire:change='changeInterest({{ $this->pinjaman->id }}, $event.target.value)' class="form-control"
                                style="width: 250px"
                                type="number"
                                value="{{ $this->pinjaman->interest }}"
                            >
                        @else
                            {{ $this->pinjaman->interest }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td colspan="5" class="strong text-end">Angsuran Pembayaran</td>
                    <td class="text-end d-flex flex-wrap">{{ $this->pinjaman->installments }}x Pembayaran Per
                        @if ($this->pinjaman->status_akad == 'belum di berikan')
                            <select wire:change='changeInstallmentsType({{ $this->pinjaman->id }}, $event.target.value)' style="width: 115px" class="form-control ms-3">
                                <option selected disabled value="{{ $this->pinjaman->installments_type }}">{{ ucwords($this->pinjaman->installments_type) }}</option>
                                <option value="tahun">Tahun</option>
                                <option value="bulan">Bulan</option>
                                <option value="hari">Hari</option>
                            </select>
                        @else
                            {{ ucwords($this->pinjaman->installments_type) }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td colspan="5" class="strong text-end">Total Pinjaman</td>
                    <td class="text-end"><strong>{{ money_format_idr($this->pinjaman->amount) }}</strong></td>
                </tr>
            </table>

            @if ($this->pinjaman->status_akad == 'belum di berikan')
                <label class="mb-2" for="file-upload">Tambahkan File Gambar Surat Perjanjian</label>
                <input wire:model='fileAkad' name="fileAkad" class="form-control" type="file">

                <div class="d-flex mt-4 justify-content-end">
                    <button wire:click='sendToNasabah({{ $this->pinjaman->id }})' class="btn btn-success">Kirim Akad Ke Nasabah</button>
                </div>
            @endif

            @if ($this->pinjaman->status_akad == 'di berikan')
                <div class="border border-1 border-success text-center p-5 mt-5 d-flex justify-content-center flex-column">
                    <h1 class="text-success text-center">Akad Selesai Terkirim</h1>
                    <a class="btn btn-success text-center" href="{{ route('akad.pinjaman.index') }}">Kembali Halaman Utama</a>
                </div>
            @endif
        </div>
    </div>
</div>
