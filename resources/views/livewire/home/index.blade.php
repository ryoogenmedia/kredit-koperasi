<div>
    <x-slot name="title">Beranda</x-slot>

    <x-slot name="pagePretitle">Ringkasan aplikasi anda berada disini.</x-slot>

    <x-slot name="pageTitle">Beranda</x-slot>

    @if (auth()->user()->roles == 'admin')
        <div class="row">
            <div class="col-md-6 col-12">
                <div class="card my-2 py-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>Jumlah Pinjaman Diberikan</div>

                            <div class="ms-auto lh-1">
                                <span class="badge bg-blue-lt">Total</span>
                            </div>
                        </div>

                        <div class="d-flex align-items-baseline mt-3">
                            <div class="h1 mb-0 me-2" style="font-size: 30px;">{{ money_format_idr($this->totalUangPencairan ?? 0) }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-12">
                <div class="card my-2 py-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>Jumlah Pinjaman Belum Diberikan</div>

                            <div class="ms-auto lh-1">
                                <span class="badge bg-blue-lt">Total</span>
                            </div>
                        </div>

                        <div class="d-flex align-items-baseline mt-3">
                            <div class="h1 mb-0 me-2" style="font-size: 30px;">{{ money_format_idr($this->totalPinjamanYangBelumCair) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-4 col-lg-3">
                <div class="card mt-3">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>Nasabah / Pinjaman</div>

                            <div class="ms-auto lh-1">
                                <span class="badge bg-blue-lt">Total</span>
                            </div>
                        </div>

                        <div class="d-flex align-items-baseline mt-3">
                            <div class="h1 mb-0 me-2" style="font-size: 30px;">{{ $this->jmlNasabah ?? 0 }} / {{ $this->pengajuanPinjaman ?? 0 }}</div>
                        </div>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>Menunggu Konfirmasi Nasabah</div>

                            <div class="ms-auto lh-1">
                                <span class="badge bg-blue-lt">Total</span>
                            </div>
                        </div>

                        <div class="d-flex align-items-baseline mt-3">
                            <div class="h1 mb-0 me-2" style="font-size: 30px;">{{ $this->jmlNasabahBelumVerifikasi ?? 0 }}</div>
                        </div>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>Menunggu Konfirmasi Akad</div>

                            <div class="ms-auto lh-1">
                                <span class="badge bg-blue-lt">Total</span>
                            </div>
                        </div>

                        <div class="d-flex align-items-baseline mt-3">
                            <div class="h1 mb-0 me-2" style="font-size: 30px;">{{ $this->jmlAkadBelumKonfirmasi ?? 0 }}</div>
                        </div>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>Pencairan Dana</div>

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

            <div class="col-12 col-md-8 col-lg-9 d-flex">
                <div class="card h-100 mt-3 w-100" wire:ignore>
                    <div class="card-body">
                        <h3 class="card-title">Data Koperasi Setia Karya 10 Hari Terakhir</h3>

                        <div data-nasabah="{{ json_encode($this->countNasabahVerification['data']) }}"
                            data-akad="{{ json_encode($this->countAkadConfirm['data']) }}"
                            data-pencairan="{{ json_encode($this->countFunds['data']) }}"
                            data-pinjaman="{{ json_encode($this->countLoan['data']) }}"
                            date="{{ json_encode($this->countLoan['date']) }}"
                            id="chart-mentions"
                            class="chart-lg">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if (auth()->user()->roles == 'operator')

    @endif
</div>

@push('scripts')
    <script>
        const item = document.getElementById('chart-mentions');
        console.log(item.getAttribute('data-menunggu'));
        window.ApexCharts && (new ApexCharts(item, {
            chart: {
                type: "bar",
                fontFamily: 'inherit',
                height: 430,
                parentHeightOffset: 0,
                toolbar: {
                    show: false,
                },
                animations: {
                    enabled: true
                },
                stacked: true,
            },
            plotOptions: {
                bar: {
                    columnWidth: '50%',
                }
            },
            dataLabels: {
                enabled: false,
            },
            fill: {
                opacity: 1,
            },
            series: [
                {
                    name: "Verfikasi Nasabah",
                    data: JSON.parse(item.getAttribute('data-nasabah'))
                },
                {
                    name: "Akad di Terima",
                    data: JSON.parse(item.getAttribute('data-akad'))
                },
                {
                    name: "Pencairan Dana",
                    data: JSON.parse(item.getAttribute('data-pencairan'))
                },
                {
                    name: "Total Pinjaman",
                    data: JSON.parse(item.getAttribute('data-pinjaman'))
                }
            ],
            grid: {
                padding: {
                    top: -19,
                    right: 0,
                    left: -4,
                    bottom: -4
                },
                strokeDashArray: 4,
                xaxis: {
                    lines: {
                        show: true
                    }
                },
            },
            xaxis: {
                labels: {
                    padding: 0,
                },
                tooltip: {
                    enabled: false
                },
                axisBorder: {
                    show: false,
                },
                type: 'datetime',
            },
            yaxis: {
                labels: {
                    padding: 4
                },
            },
            labels: JSON.parse(item.getAttribute('date')),
            colors: ["#39FF74","#2FD15F","#1A79C7","#219BFF"],
            legend: {
                show: false,
            },
        })).render();
    </script>
@endpush
