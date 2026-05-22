@extends('layouts.pengelola')
@section('title', 'Pembayaran Pengelola')

@section('content')
<div x-data="{ modalOpen: false, modalType: null,
                openModal(type, duration = 2500) {
            this.modalOpen = true;
            this.modalType = type;}}">
    {{-- ================= PAGE HEADER ================= --}}
    <x-page-header
        title="Riwayat Pembayaran"
        description="Lihat semua riwayat pembayaran">
    </x-page-header>

    {{-- ================= INFORMATION CARD ================= --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-2 gap-4 mb-4">
        <div class="bg-white border border-green-400 rounded-md p-4 lg:p-5">

            <img
                src="{{ asset('assets/icons/pengelola-pembayaran-pendapatan-icon.png') }}"
                alt="Kamar Terisi"
                class="w-9 h-9 lg:w-10 lg:h-10 mb-4">

            <p class="text-xs lg:text-sm text-black mb-1">
                Pendapatan Bulan ini
            </p>

            <h2 class="text-2xl lg:text-3xl font-bold text-green-500">
                Rp. 1000.000
            </h2>

        </div>
        <div class="bg-white border border-primary rounded-md p-4 lg:p-5">

            <img
                src="{{ asset('assets/icons/pengelola-dashboard-pendapatan-icon.png') }}"
                alt="Pendapatan"
                class="w-9 h-9 lg:w-10 lg:h-10 mb-4">

            <p class="text-xs lg:text-sm text-black mb-1">
                Total Transaksi
            </p>

            <h2 class="text-xl lg:text-2xl font-bold text-primary break-words">
                3
            </h2>

        </div>
    </div>

    {{-- ================= SEARCH ================= --}}
    <x-search-input
        name="search_pembayaran"
        placeholder="Cari" />

    {{-- ================= TABLE ================= --}}
    <x-card class="mt-4 mb-6">
        <x-table.index class="min-w-[700px]">
            <thead class="sticky top-0 bg-white z-10 border-b border-default">
                <x-table.tr>
                    <x-table.th>
                        Nama Lengkap
                    </x-table.th>
                    <x-table.th>
                        Kamar
                    </x-table.th>
                    <x-table.th>
                        Tanggal Pembayaran
                    </x-table.th>
                    <x-table.th>
                        Nominal
                    </x-table.th>
                    <x-table.th>
                        Jenis
                    </x-table.th>
                    <x-table.th>
                        Aksi
                    </x-table.th>
                </x-table.tr>
            </thead>

            <tbody>
                <x-table.tr>
                    <x-table.td class="font-medium text-heading">
                        Anto Subagja
                    </x-table.td>
                    <x-table.td>
                        KM001
                    </x-table.td>
                    <x-table.td>
                        08/04/2026
                    </x-table.td>
                    <x-table.td>
                        Rp500.000
                    </x-table.td>
                    <x-table.td>
                        Lunas
                    </x-table.td>
                    <x-table.td>

                        <a
                            href="#"
                            @click.prevent="openModal('detail-pembayaran')"
                            class="w-8 flex justify-center cursor-pointer">

                            <img
                                src="{{ asset('assets/icons/lihat-detail-icon.png') }}"
                                alt="Lihat Detail"
                                class="w-4 h-4">

                        </a>

                    </x-table.td>
                </x-table.tr>
            </tbody>
        </x-table.index>
    </x-card>

    {{-- ================= PAGINATION ================= --}}
    <x-pagination />

    {{-- ================= MODAL ================= --}}
    <x-modal show="modalOpen" maxWidth="lg:max-w-[500px] max-w-[350px]">
        <template x-if="modalType === 'detail-pembayaran'">

            <div class="relative">

                {{-- CLOSE BUTTON --}}
                <button
                    type="button"
                    class="
                        absolute top-0 right-0
                        text-neutral hover:text-black
                        text-xl font-bold
                    "
                    @click="
                        modalOpen = false;
                        modalType = null;
                    ">

                    ✕

                </button>

                <h2 class="text-xl font-bold mb-8">
                    Struk Pembayaran
                </h2>

                <div class="flex flex-col gap-4">

                    <div class="flex gap-28">
                        <div class="flex flex-col gap-1">
                            <p class="text-neutral text-xs">Nama Lengkap</p>
                            <p class="text-black text-xs font-semibold">Anton Subagja</p>
                        </div>
                        <div class="flex flex-col gap-1">
                            <p class="text-neutral text-xs">Nama Kost</p>
                            <p class="text-black text-xs font-semibold">Kost Jaya Abadi</p>
                        </div>
                    </div>
                    <hr>

                    <div class="flex flex-col gap-4">
                        <div class="w-full flex justify-between">
                            <p class="text-xs text-neutral">Nomor Transaksi</p>
                            <p class="text-xs text-black font-semibold">TRS01</p>
                        </div>
                        <div class="w-full flex justify-between">
                            <p class="text-xs text-neutral">Jenis Pembayaran</p>
                            <p class="text-xs text-black font-semibold">Lunas</p>
                        </div>
                        <div class="w-full flex justify-between">
                            <p class="text-xs text-neutral">Metode Pembayaran</p>
                            <p class="text-xs text-black font-semibold">Dana</p>
                        </div>
                        <div class="w-full flex justify-between">
                            <p class="text-xs text-neutral">Tanggal Transaksi</p>
                            <p class="text-xs text-black font-semibold">08/04/2026 15.00</p>
                        </div>
                    </div>
                    <div class="flex justify-between w-full rounded-md bg-[#F8F8F8] shadow-sm px-4 py-3">
                        <p class="text-xs text-black font-medium mb-1">
                            Total Dibayar
                        </p>

                        <p class="text-sm font-semibold text-primary">
                            Rp500.000
                        </p>
                    </div>

                    <x-badge type="success" class="text-center">
                        Pembayaran Berhasil
                    </x-badge>
                </div>
            </div>

        </template>
    </x-modal>
</div>
@endsection