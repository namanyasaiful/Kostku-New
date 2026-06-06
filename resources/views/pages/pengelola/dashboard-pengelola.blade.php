@extends('layouts.pengelola')
@section('title', 'Dashboard Pengelola')

@section('content')

<div class="space-y-6">

    {{-- ================= PAGE HEADER ================= --}}
    <x-page-header
        title="Dashboard"
        description="Pantau data kost dan aktivitas penghuni">

    </x-page-header>

    {{-- ================= KOST INFORMATION ================= --}}
    <x-card>
        <div>
            <h1 class="lg:text-xl text-md font-bold text-primary mb-4">Informasi Kost Anda</h1>
            <div class="flex flex-row lg:gap-96 gap-10">
                <div class="flex lg:gap-4 gap-3 items-center">
                    <img src="{{ asset('assets/icons/kamar-icon-active.png') }}" alt="Kost" class="w-4 h-auto mb-4">
                    <div class="flex flex-col">
                        <p class="text-xs text-neutral mb-1">Nama Kost</p>
                        <p class="text-black text-sm font-bold">Kost Jaya Abadi</p>
                    </div>
                </div>
                <div class="flex lg:gap-4 gap-2 items-center">
                    <img src="{{ asset('assets/icons/key-icon.png') }}" alt="Key" class="w-5 h-auto mb-4">
                    <div class="flex flex-col">
                        <p class="text-xs text-neutral mb-1">Kode Kost</p>
                        <p class="text-black text-sm font-bold">JYABD1</p>
                    </div>
                </div>
            </div>
        </div>
    </x-card>


    {{-- ================= CARD STATISTIK ================= --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">

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
                    src="{{ asset('assets/icons/total-penghuni-icon.png') }}"
                    alt="Total Penghuni"
                    class="w-9 h-9 lg:w-14 lg:h-14 mb-4">
            </div>
            <div class="flex gap-2 items-center">
                <img src="{{ asset('assets/icons/up-icon.png') }}" alt="Naik" class="lg:w-6 w-4">
                <p class="lg:text-sm text-xs text-neutral">Naik dari hari kemarin</p>
            </div>
        </div>


        {{-- Kamar Terisi --}}
        <div class="flex flex-col justify-between bg-white border border-none rounded-xl p-4 lg:p-5">
            <div class="flex justify-between">
                <div class="flex flex-col gap-1">
                    <p class="text-xs lg:text-sm text-black">
                        Kamar Terisi
                    </p>
                    <h2 class="text-xl lg:text-2xl font-bold text-black">
                        3
                    </h2>
                </div>
                <img
                    src="{{ asset('assets/icons/kamar-terisi-icon.png') }}"
                    alt="Total Penghuni"
                    class="w-9 h-9 lg:w-14 lg:h-14 mb-4">
            </div>
            <div class="flex gap-2 items-center">
                <img src="{{ asset('assets/icons/up-icon.png') }}" alt="Naik" class="lg:w-6 w-4">
                <p class="lg:text-sm text-xs text-neutral">Naik dari hari kemarin</p>
            </div>
        </div>



        {{-- Kamar Kosong --}}
        <div class="flex flex-col justify-between bg-white border border-none rounded-xl p-4 lg:p-5">
            <div class="flex justify-between">
                <div class="flex flex-col gap-1">
                    <p class="text-xs lg:text-sm text-black">
                        Kamar Kosong
                    </p>
                    <h2 class="text-xl lg:text-2xl font-bold text-black">
                        3
                    </h2>
                </div>
                <img
                    src="{{ asset('assets/icons/kamar-kosong-icon.png') }}"
                    alt="Total Penghuni"
                    class="w-9 h-9 lg:w-14 lg:h-14 mb-4">
            </div>
            <div class="flex gap-2 items-center">
                <img src="{{ asset('assets/icons/down-icon.png') }}" alt="Turun" class="lg:w-6 w-4">
                <p class="lg:text-sm text-xs text-neutral">Turung dari hari kemarin</p>
            </div>
        </div>


        {{-- Pendapatan --}}
        <div class="flex flex-col justify-between bg-white border border-none rounded-xl p-4 lg:p-5">
            <div class="flex justify-between">
                <div class="flex flex-col gap-1">
                    <p class="text-xs lg:text-sm text-black">
                        Pendapatan Bulan Ini
                    </p>
                    <h2 class="text-xl lg:text-2xl font-bold text-black">
                        Rp.1.000.000
                    </h2>
                </div>
                <img
                    src="{{ asset('assets/icons/pendapatan-icon.png') }}"
                    alt="Total Penghuni"
                    class="w-9 h-9 lg:w-14 lg:h-14 mb-4">
            </div>
            <div class="flex gap-2 items-center">
                <img src="{{ asset('assets/icons/up-icon.png') }}" alt="Naik" class="lg:w-6 w-4">
                <p class="lg:text-sm text-xs text-neutral">Naik dari hari kemarin</p>
            </div>
        </div>

    </div>


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

</div>

@endsection