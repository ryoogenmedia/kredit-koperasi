<div>
    <x-slot name="title">Beranda</x-slot>

    <x-slot name="pagePretitle">Ringkasan aplikasi anda berada disini.</x-slot>

    <x-slot name="pageTitle">Beranda</x-slot>

    @if (auth()->user()->roles == 'admin')
        <div class="row">
            <div class="col-md-3 col-12">
                <div class="card mt-2">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>Nasabah / Pengajuan Pinjaman</div>

                            <div class="ms-auto lh-1">
                                <span class="badge bg-blue-lt">Total</span>
                            </div>
                        </div>

                        <div class="d-flex align-items-baseline mt-3">
                            <div class="h1 mb-0 me-2" style="font-size: 30px;">{{ $this->jmlNasabah ?? 0 }} / {{ $this->pengajuanPinjaman ?? 0 }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-12">
                <div class="card mt-2">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>Nasabah Belum Di Verifikasi</div>

                            <div class="ms-auto lh-1">
                                <span class="badge bg-blue-lt">Total</span>
                            </div>
                        </div>

                        <div class="d-flex align-items-baseline mt-3">
                            <div class="h1 mb-0 me-2" style="font-size: 30px;">{{ $this->jmlNasabahBelumVerifikasi ?? 0 }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-12">
                <div class="card mt-2">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>Akad Belum Di Konfirmasi</div>

                            <div class="ms-auto lh-1">
                                <span class="badge bg-blue-lt">Total</span>
                            </div>
                        </div>

                        <div class="d-flex align-items-baseline mt-3">
                            <div class="h1 mb-0 me-2" style="font-size: 30px;">{{ $this->jmlAkadBelumKonfirmasi ?? 0 }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-12">
                <div class="card mt-2">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>Pencairan Dana Nasabah</div>

                            <div class="ms-auto lh-1">
                                <span class="badge bg-blue-lt">Total</span>
                            </div>
                        </div>

                        <div class="d-flex align-items-baseline mt-3">
                            <div class="h1 mb-0 me-2" style="font-size: 30px;">{{ $this->jmlPencairan ?? 0 }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
