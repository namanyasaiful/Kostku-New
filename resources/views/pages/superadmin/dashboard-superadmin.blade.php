@extends('layouts.superadmin')
@section('title', 'Dashboard Superadmin')

@section('content')
{{-- ================= PAGE HEADER ================= --}}
<x-page-header
    title="Dashboard"
    description="Selamat datang di Super Admin">

</x-page-header>

{{-- ================= CARD STATISTIK ================= --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-8">

    {{-- Total Pengelola --}}
    <div class="flex flex-col justify-between bg-white border border-none rounded-xl p-4 lg:p-5">
        <div class="flex justify-between">
            <div class="flex flex-col gap-1">
                <p class="text-xs lg:text-sm text-black">
                    Total Pengelola
                </p>
                <h2 class="text-xl lg:text-2xl font-bold text-black">
                    3
                </h2>
            </div>
            <img
                src="{{ asset('assets/icons/total-pengelola-icon.png') }}"
                alt="Total Pengelola"
                class="w-9 h-9 lg:w-14 lg:h-14 mb-4">
        </div>
        <div class="flex gap-2 items-center">
            <img src="{{ asset('assets/icons/up-icon.png') }}" alt="Naik" class="lg:w-6 w-4">
            <p class="lg:text-sm text-xs text-neutral">Naik dari hari kemarin</p>
        </div>
    </div>

    {{-- Total Penghuni --}}
    <div class="flex flex-col justify-between bg-white border border-none rounded-xl p-4 lg:p-5">
        <div class="flex justify-between">
            <div class="flex flex-col gap-1">
                <p class="text-xs lg:text-sm text-black">
                    Total Penghuni
                </p>
                <h2 class="text-xl lg:text-2xl font-bold text-black">
                    3
                </h2>
            </div>
            <img
                src="{{ asset('assets/icons/total-penghuni2-icon.png') }}"
                alt="Total Penghuni"
                class="w-9 h-9 lg:w-14 lg:h-14 mb-4">
        </div>
        <div class="flex gap-2 items-center">
            <img src="{{ asset('assets/icons/down-icon.png') }}" alt="Turun" class="lg:w-6 w-4">
            <p class="lg:text-sm text-xs text-neutral">Turun dari hari kemarin</p>
        </div>
    </div>

    {{-- Total Kost --}}
    <div class="flex flex-col justify-between bg-white border border-none rounded-xl p-4 lg:p-5">
        <div class="flex justify-between">
            <div class="flex flex-col gap-1">
                <p class="text-xs lg:text-sm text-black">
                    Total Kost
                </p>
                <h2 class="text-xl lg:text-2xl font-bold text-black">
                    3
                </h2>
            </div>
            <img
                src="{{ asset('assets/icons/total-kost-icon.png') }}"
                alt="Total Kost"
                class="w-9 h-9 lg:w-14 lg:h-14 mb-4">
        </div>
        <div class="flex gap-2 items-center">
            <img src="{{ asset('assets/icons/down-icon.png') }}" alt="Turun" class="lg:w-6 w-4">
            <p class="lg:text-sm text-xs text-neutral">Turun dari hari kemarin</p>
        </div>
    </div>

    {{-- Total Transaksi --}}
    <div class="flex flex-col justify-between bg-white border border-none rounded-xl p-4 lg:p-5">
        <div class="flex justify-between">
            <div class="flex flex-col gap-1">
                <p class="text-xs lg:text-sm text-black">
                    Total Transaksi
                </p>
                <h2 class="text-xl lg:text-2xl font-bold text-black">
                    4
                </h2>
            </div>
            <img
                src="{{ asset('assets/icons/total-transaksi-icon.png') }}"
                alt="Total Transaksi"
                class="w-9 h-9 lg:w-14 lg:h-14 mb-4">
        </div>
        <div class="flex gap-2 items-center">
            <img src="{{ asset('assets/icons/up-icon.png') }}" alt="Naik" class="lg:w-6 w-4">
            <p class="lg:text-sm text-xs text-neutral">Naik dari hari kemarin</p>
        </div>
    </div>

</div>

{{-- ================= CART PERTUMBUHAN USER ================= --}}
<x-card class="relative overflow-hidden mb-8 !rounded-3xl">
    {{-- BACKGROUND DECORATION --}}
    <div
        class="absolute inset-0 opacity-30 pointer-events-none ml-20 mr-12 my-20"
        style="
            background-image:
            linear-gradient(to right, #E5E7EB 1px, transparent 1px),
            linear-gradient(to bottom, #E5E7EB 1px, transparent 1px);
            background-size: 80px 40px;
        ">
    </div>
    <div class="relative z-10">
        <h2 class="text-2xl font-bold text-black mb-6">
            Pertumbuhan User
        </h2>
        <div id="userGrowthChart"></div>
    </div>
</x-card>

{{-- ================= TABLE SECTION ================= --}}
<div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

    {{-- Pembayaran --}}
    <div class="bg-white rounded-2xl p-4 lg:p-6">

        <div class="flex items-center justify-between mb-5">

            <h2 class="text-base lg:text-lg font-bold">
                Pembayaran Terbaru
            </h2>

            <a
                href="{{ route('pembayaran.pengelola') }}"
                class="text-primary text-xs lg:text-sm hover:underline">

                Lihat Semua

            </a>

        </div>

        <div class="space-y-4">

            <div class="flex items-center justify-between border-b pb-3 gap-3">

                <p class="text-xs lg:text-sm">
                    P001 - Anto Subagja
                </p>

                <p class="text-xs lg:text-sm font-medium">
                    Rp500.000
                </p>

            </div>

            <div class="flex items-center justify-between border-b pb-3 gap-3">

                <p class="text-xs lg:text-sm">
                    P002 - Tono Sukamto
                </p>

                <p class="text-xs lg:text-sm font-medium">
                    Rp500.000
                </p>

            </div>

            <div class="flex items-center justify-between gap-3">

                <p class="text-xs lg:text-sm">
                    P003 - Saifullah Fattah
                </p>

                <p class="text-xs lg:text-sm font-medium">
                    Rp500.000
                </p>

            </div>

        </div>

    </div>


    {{-- Pengaduan --}}
    <div class="bg-white rounded-2xl p-4 lg:p-6">

        <div class="flex items-center justify-between mb-5">

            <h2 class="text-base lg:text-lg font-bold">
                Pengaduan Terbaru
            </h2>

            <a
                href="{{ route('pengaduan.pengelola') }}"
                class="text-primary text-xs lg:text-sm hover:underline">

                Lihat Semua

            </a>

        </div>

        <div class="space-y-4">

            <div class="flex items-center justify-between border-b pb-3 gap-3">

                <p class="text-xs lg:text-sm">
                    P001 - Anto Subagja
                </p>

                <x-badge type="info">Baru</x-badge>

            </div>

            <div class="flex items-center justify-between border-b pb-3 gap-3">

                <p class="text-xs lg:text-sm">
                    P002 - Tono Sukamto
                </p>

                <x-badge type="warning">Proses</x-badge>

            </div>

            <div class="flex items-center justify-between gap-3">

                <p class="text-xs lg:text-sm">
                    P003 - Saifullah Fattah
                </p>

                <x-badge type="success">Selesai</x-badge>

            </div>

        </div>

    </div>

</div>

<!-- CHART -->
<script>
    document.addEventListener('DOMContentLoaded', function() {

        const options = {
            series: [{
                name: 'User',
                data: [120, 340, 250, 490, 430, 800, 200, 580, 240, 640, 600]
            }],

            chart: {
                type: 'area',
                height: 300,
                toolbar: {
                    show: false
                },
                zoom: {
                    enabled: false
                }
            },

            colors: ['#243BFF'],

            stroke: {
                curve: 'smooth',
                width: 4
            },

            dataLabels: {
                enabled: false
            },

            // markers: {
            //     size: 7,
            //     strokeWidth: 0,
            //     hover: {
            //         size: 9
            //     }
            // },

            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    inverseColors: false,
                    opacityFrom: 0.55,
                    opacityTo: 0.08,
                    stops: [0, 100]
                }
            },

            grid: {
                borderColor: '#DCE5F3',
                strokeDashArray: 5,

                xaxis: {
                    lines: {
                        show: true
                    }
                },

                yaxis: {
                    lines: {
                        show: true
                    }
                }
            },

            xaxis: {
                categories: [
                    'Jul',
                    'Aug',
                    'Sep',
                    'Oct',
                    'Nov',
                    'Dec',
                    'Jan',
                    'Feb',
                    'Mar',
                    'Apr',
                    'May'
                ],

                axisBorder: {
                    show: false
                },

                axisTicks: {
                    show: false
                },

                labels: {
                    style: {
                        colors: '#7B92C8',
                        fontSize: '14px',
                        fontWeight: 500
                    }
                }
            },

            yaxis: {
                min: 0,
                max: 800,
                tickAmount: 4,

                labels: {
                    style: {
                        colors: '#7B92C8',
                        fontSize: '14px'
                    }
                }
            },

            tooltip: {
                theme: 'light'
            },

            legend: {
                show: false
            }
        };

        const chart = new ApexCharts(
            document.querySelector("#userGrowthChart"),
            options
        );

        chart.render();
    });
</script>

@endsection