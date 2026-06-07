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
        <div class="flex flex-col justify-between bg-white border border-none rounded-xl p-4 lg:p-5">
            <div class="flex justify-between">
                <div class="flex flex-col gap-1">
                    <p class="text-xs lg:text-sm text-black">
                        Pendapatan Bulan Ini
                    </p>
                    <h2 class="text-xl lg:text-2xl font-bold text-black">
                        Rp1.000.000
                    </h2>
                </div>
                <img
                    src="{{ asset('assets/icons/pendapatan-vers2-icon.png') }}"
                    alt="Total Penghuni"
                    class="w-9 h-9 lg:w-14 lg:h-14 mb-4">
            </div>
            <div class="flex gap-2 items-center">
                <img src="{{ asset('assets/icons/down-icon.png') }}" alt="Turun" class="lg:w-6 w-4">
                <p class="lg:text-sm text-xs text-neutral">Turun dari bulan kemarin</p>
            </div>
        </div>
        <div class="flex flex-col justify-between bg-white border border-none rounded-xl p-4 lg:p-5">
            <div class="flex justify-between">
                <div class="flex flex-col gap-1">
                    <p class="text-xs lg:text-sm text-black">
                        Total Transaksi
                    </p>
                    <h2 class="text-xl lg:text-2xl font-bold text-black">
                        3
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
            @forelse($pembayarans as $bayar)
            <x-table.tr>
                <x-table.td class="font-medium text-heading">
                    {{ $bayar->user->nama ?? '-' }}
                </x-table.td>
                <x-table.td>
                    {{ $bayar->user->penghuni->kamar->nomor_kamar ?? '-' }}
                </x-table.td>
                <x-table.td>
                    {{ \Carbon\Carbon::parse($bayar->tanggal_pembayaran)->format('d/m/Y') }}
                </x-table.td>
                <x-table.td>
                    Rp{{ number_format($bayar->nominal, 0, ',', '.') }}
                </x-table.td>
                <x-table.td>
                    {{ ucfirst($bayar->tipe_pembayaran ?? '-') }}
                </x-table.td>
                <x-table.td>
                    <x-form.button
                        @click.prevent="openModal('detail-pembayaran')"
                        class="w-24 !p-2 border border-primary bg-transparent !text-primary hover:bg-secondary hover:border-secondary">
                        Detail
                    </x-form.button>
                </x-table.td>
            </x-table.tr>
            @empty
            <x-table.tr>
                <x-table.td colspan="6" class="text-center text-neutral">
                    Belum ada data pembayaran.
                </x-table.td>
            </x-table.tr>
            @endforelse
        </tbody>
        </x-table.index>
    </x-card>

    {{-- ================= PAGINATION ================= --}}
    <x-pagination :paginator="$pembayarans" />

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

                    <div class="flex justify-between">
                        <div class="flex flex-col gap-1">
                            <p class="text-neutral text-xs">Nama Lengkap</p>
                            <p class="text-black text-xs font-semibold">Anton Subagja</p>
                        </div>
                        <div class="flex flex-col gap-1">
                            <p class="text-neutral text-xs">Nama Kost</p>
                            <p class="text-black text-xs font-semibold">Kost Jaya Abadi</p>
                        </div>
                        <x-badge type="success" class="!text-[10px] !p-1  !h-fit">Lunas</x-badge>
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