@extends('layouts.penghuni')
@section('title', 'Pembayaran Penghuni')

@section('content')
<div x-data="{
        modalOpen: false,
        modalType: null,
        cicilanPertamaBayar: false,
        cicilanKeduaBayar: false,
        fullBayar: false,
    }">
    <x-page-header
        title="Pembayaran"
        description="Tagihan dan riwayat pembayaran Anda">
    </x-page-header>

    <x-card class="mb-4">
        <div class="flex flex-col lg:gap-8 gap-4">
            <div>
                <p class="text-neutral text-xs font-medium mb-2">Total Tagihan</p>
                <h1 class="text-primary lg:text-3xl text-2xl font-bold">RP500.000</h1>
            </div>
            <div class="flex lg:flex-row lg:gap-96 gap-20">
                <div>
                    <p class="text-xs text-neutral mb-2">Periode Tagihan</p>
                    <p class="text-black text-sm font-bold">Mei 2026</p>
                </div>
                <div>
                    <p class="text-xs text-neutral mb-2">Jatuh Tempo</p>
                    <p class="text-black text-sm font-bold">4 Mei 2026</p>
                </div>
            </div>

            {{-- DEFAULT: belum bayar apapun --}}
            <template x-if="!cicilanPertamaBayar && !cicilanKeduaBayar && !fullBayar">
                <div class="flex gap-8">
                    <button
                        @click="modalOpen = true; modalType = 'bayar-lunas';"
                        class="w-full bg-primary hover:bg-secondary text-white hover:text-primary lg:text-md text-sm font-bold lg:p-3 p-2 rounded-md">
                        Bayar Lunas
                    </button>
                    <button
                        @click="modalOpen = true; modalType = 'bayar-cicilan';"
                        class="w-full border-2 border-primary hover:bg-secondary hover:border-secondary text-primary lg:text-md text-sm font-bold lg:p-3 p-2 rounded-md">
                        Bayar Cicilan
                    </button>
                </div>
            </template>

            {{-- Cicilan 1 sudah bayar, cicilan 2 belum --}}
            <template x-if="cicilanPertamaBayar && !cicilanKeduaBayar && !fullBayar">
                <div class="flex gap-8">
                    <button
                        @click="modalOpen = true; modalType = 'lanjut-cicilan';"
                        class="w-full bg-primary hover:bg-secondary text-white hover:text-primary lg:text-md text-sm font-bold lg:p-3 p-2 rounded-md">
                        Lanjutkan Cicilan
                    </button>
                </div>
            </template>

            {{-- Semua sudah lunas --}}
            <template x-if="cicilanKeduaBayar || fullBayar">
                <div class="flex gap-8">
                    <button
                        disabled
                        class="w-full bg-gray-200 text-gray-400 cursor-not-allowed lg:text-md text-sm font-bold lg:p-3 p-2 rounded-md">
                        Sudah Dibayar
                    </button>
                </div>
            </template>

        </div>
    </x-card>

    <x-card>
        <h1 class="text-black text-xl font-bold mb-4">Riwayat Pembayaran</h1>
        <x-table.index class="min-w-[700px]">
            <thead class="sticky top-0 bg-white z-10 border-b border-default">
                <x-table.tr>
                    <x-table.th>Tanggal Pembayaran</x-table.th>
                    <x-table.th>Jenis</x-table.th>
                    <x-table.th>Nominal</x-table.th>
                    <x-table.th>Status</x-table.th>
                    <x-table.th>Aksi</x-table.th>
                </x-table.tr>
            </thead>
            <tbody>
                <x-table.tr>
                    <x-table.td class="font-medium text-heading">04/05/2026</x-table.td>
                    <x-table.td>Cicilan 2</x-table.td>
                    <x-table.td>Rp250.000</x-table.td>
                    <x-table.td>
                        <x-badge type="success">Berhasil</x-badge>
                    </x-table.td>
                    <x-table.td>
                        <a href="#" class="font-medium text-primary hover:underline">Lihat Struk</a>
                    </x-table.td>
                </x-table.tr>
            </tbody>
        </x-table.index>
    </x-card>

    <x-modal show="modalOpen" maxWidth="lg:max-w-[450px] max-w-[350px]">

        {{-- ================= BAYAR LUNAS ================= --}}
        <template x-if="modalType === 'bayar-lunas'">
            <div class="relative">

                <button
                    type="button"
                    class="absolute top-0 right-0 text-neutral hover:text-black text-xl font-bold"
                    @click="modalOpen = false; modalType = null;">
                    ✕
                </button>

                <h2 class="text-xl font-bold mb-8">
                    Konfirmasi Pembayaran Lunas
                </h2>

                <div class="flex flex-col gap-4">
                    <div class="flex justify-between border-b-2">
                        <p class="text-xs text-neutral">Tagihan Mei 2026</p>
                        <p class="text-sm text-black font-semibold">Rp500.000</p>
                    </div>
                    <div class="flex justify-between border-b-2">
                        <p class="text-md text-black font-medium">Total Pembayaran</p>
                        <p class="text-md text-primary font-semibold">Rp500.000</p>
                    </div>
                </div>

                <x-form.button
                    type="button"
                    class="w-full mt-8"
                    @click="fullBayar = true; modalOpen = false; modalType = null;">
                    Bayar Sekarang
                </x-form.button>

            </div>
        </template>

        {{-- ================= BAYAR CICILAN (modal awal, cicilan 1 aktif) ================= --}}
        <template x-if="modalType === 'bayar-cicilan'">
            <div class="relative">

                <button
                    type="button"
                    class="absolute top-0 right-0 text-neutral hover:text-black text-xl font-bold"
                    @click="modalOpen = false; modalType = null;">
                    ✕
                </button>

                <h2 class="text-xl font-bold">
                    Konfirmasi Pembayaran Cicilan
                </h2>
                <p class="text-xs text-neutral mb-8">Total tagihan dibagi menjadi 2 pembayaran</p>

                <div class="flex flex-col gap-4">

                    {{-- Cicilan Pertama: aktif --}}
                    <div class="flex flex-col justify-between border-2 border-primary p-4 rounded-lg">
                        <p class="text-lg text-black font-medium">Cicilan Pertama</p>
                        <p class="text-sm text-neutral my-1">Jatuh Tempo minggu pertama</p>
                        <p class="text-xl text-primary font-semibold">Rp250.000</p>
                        <x-form.button
                            type="button"
                            class="w-full mt-4"
                            @click="cicilanPertamaBayar = true; modalOpen = false; modalType = null;">
                            Bayar Cicilan Pertama
                        </x-form.button>
                    </div>

                    {{-- Cicilan Kedua: disabled --}}
                    <div class="flex flex-col justify-between border-2 border-primary/30 p-4 rounded-lg opacity-60">
                        <p class="text-lg text-black font-medium">Cicilan Kedua</p>
                        <p class="text-sm text-neutral my-1">Jatuh Tempo minggu kedua</p>
                        <p class="text-xl text-primary font-semibold">Rp250.000</p>
                        <x-form.button
                            type="button"
                            class="w-full mt-4 cursor-not-allowed"
                            disabled>
                            Bayar Cicilan Kedua
                        </x-form.button>
                    </div>

                </div>
            </div>
        </template>

        {{-- ================= LANJUT CICILAN (hanya cicilan 2) ================= --}}
        <template x-if="modalType === 'lanjut-cicilan'">
            <div class="relative">

                <button
                    type="button"
                    class="absolute top-0 right-0 text-neutral hover:text-black text-xl font-bold"
                    @click="modalOpen = false; modalType = null;">
                    ✕
                </button>

                <h2 class="text-xl font-bold">
                    Konfirmasi Pembayaran Cicilan
                </h2>
                <p class="text-xs text-neutral mb-8">Melanjutkan cicilan kedua</p>

                {{-- Cicilan Kedua: aktif --}}
                <div class="flex flex-col justify-between border-2 border-primary p-4 rounded-lg">
                    <p class="text-lg text-black font-medium">Cicilan Kedua</p>
                    <p class="text-sm text-neutral my-1">Jatuh Tempo minggu kedua</p>
                    <p class="text-xl text-primary font-semibold">Rp250.000</p>
                    <x-form.button
                        type="button"
                        class="w-full mt-4"
                        @click="cicilanKeduaBayar = true; modalOpen = false; modalType = null;">
                        Bayar Cicilan Kedua
                    </x-form.button>
                </div>

            </div>
        </template>

    </x-modal>
</div>
@endsection