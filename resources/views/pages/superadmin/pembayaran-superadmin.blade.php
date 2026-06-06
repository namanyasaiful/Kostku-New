@extends('layouts.superadmin')
@section('title', 'Pembayaran')

@section('content')

{{-- DATA DUMMY --}}
@php
$dummyData = [
    [
        'id' => 1,
        'nama' => 'Budi Santoso',
        'nama_kost' => 'Kost Melati',
        'tanggal_pembayaran' => '04/05/2026',
        'nominal' => 'Rp500.000',
        'jenis' => 'Bayar Lunas',
        'status' => 'Lunas',
        'periode' => 'Mei 2026',
        'nomor_transaksi' => 'TRS001',
        'metode' => 'Transfer Bank',
        'waktu' => '04/05/2026 10.30',
    ],
    [
        'id' => 2,
        'nama' => 'Siti Rahayu',
        'nama_kost' => 'Kost Mawar',
        'tanggal_pembayaran' => '05/05/2026',
        'nominal' => 'Rp250.000',
        'jenis' => 'Cicilan 1',
        'status' => 'Belum Lunas',
        'periode' => 'Mei 2026',
        'nomor_transaksi' => 'TRS002',
        'metode' => 'Dana',
        'waktu' => '05/05/2026 09.15',
    ],
    [
        'id' => 3,
        'nama' => 'Ahmad Fauzi',
        'nama_kost' => 'Kost Anggrek',
        'tanggal_pembayaran' => '06/05/2026',
        'nominal' => 'Rp250.000',
        'jenis' => 'Cicilan 2',
        'status' => 'Lunas',
        'periode' => 'Mei 2026',
        'nomor_transaksi' => 'TRS003',
        'metode' => 'GoPay',
        'waktu' => '06/05/2026 14.00',
    ],
    [
        'id' => 4,
        'nama' => 'Dewi Lestari',
        'nama_kost' => 'Kost Kenanga',
        'tanggal_pembayaran' => '07/05/2026',
        'nominal' => 'Rp500.000',
        'jenis' => 'Bayar Lunas',
        'status' => 'Lunas',
        'periode' => 'Mei 2026',
        'nomor_transaksi' => 'TRS004',
        'metode' => 'OVO',
        'waktu' => '07/05/2026 11.45',
    ],
    [
        'id' => 5,
        'nama' => 'Reza Firmansyah',
        'nama_kost' => 'Kost Cempaka',
        'tanggal_pembayaran' => '08/05/2026',
        'nominal' => 'Rp250.000',
        'jenis' => 'Cicilan 1',
        'status' => 'Belum Lunas',
        'periode' => 'Mei 2026',
        'nomor_transaksi' => 'TRS005',
        'metode' => 'Dana',
        'waktu' => '08/05/2026 08.00',
    ],
];
@endphp

<div
    x-data="{
        modalOpen: false,
        modalType: null,
        selectedPembayaran: {},

        openModal(type, data = {}) {
            this.selectedPembayaran = data;
            this.modalOpen = true;
            this.modalType = type;
        },

        closeModal() {
            this.modalOpen = false;
            this.modalType = null;
        }
    }">

    {{-- ================= PAGE HEADER ================= --}}
    <x-page-header
        title="Pembayaran"
        description="Lihat semua riwayat pembayaran penghuni">
    </x-page-header>

    {{-- ================= SEARCH ================= --}}
    <x-search-input
        name="search_pembayaran"
        placeholder="Cari" />

    {{-- ================= TABLE ================= --}}
    <div class="bg-white rounded-lg p-4 lg:p-6 mt-4 mb-6">
        <div class="overflow-x-auto">
            <x-table.index>
                <thead class="sticky top-0 bg-white z-10 border-b border-default">
                    <x-table.tr>
                        <x-table.th>Nama Lengkap</x-table.th>
                        <x-table.th>Kost</x-table.th>
                        <x-table.th>Tanggal Pembayaran</x-table.th>
                        <x-table.th>Nominal</x-table.th>
                        <x-table.th>Jenis Pembayaran</x-table.th>
                        <x-table.th>Status</x-table.th>
                        <x-table.th class="text-center">Aksi</x-table.th>
                    </x-table.tr>
                </thead>
                <tbody>
                    @foreach($dummyData as $pembayaran)
                    <x-table.tr>
                        <x-table.td class="font-medium">{{ $pembayaran['nama'] }}</x-table.td>
                        <x-table.td>{{ $pembayaran['nama_kost'] }}</x-table.td>
                        <x-table.td>{{ $pembayaran['tanggal_pembayaran'] }}</x-table.td>
                        <x-table.td>{{ $pembayaran['nominal'] }}</x-table.td>
                        <x-table.td>{{ $pembayaran['jenis'] }}</x-table.td>
                        <x-table.td>
                            @if($pembayaran['status'] === 'Lunas')
                                <x-badge type="success">Lunas</x-badge>
                            @else
                                <x-badge type="danger">Belum Lunas</x-badge>
                            @endif
                        </x-table.td>
                        <x-table.td class="text-center">
                            <x-form.button
                                @click.prevent="openModal('detail-pembayaran', {
                                    nama: '{{ $pembayaran['nama'] }}',
                                    nama_kost: '{{ $pembayaran['nama_kost'] }}',
                                    periode: '{{ $pembayaran['periode'] }}',
                                    nomor_transaksi: '{{ $pembayaran['nomor_transaksi'] }}',
                                    jenis: '{{ $pembayaran['jenis'] }}',
                                    metode: '{{ $pembayaran['metode'] }}',
                                    waktu: '{{ $pembayaran['waktu'] }}',
                                    nominal: '{{ $pembayaran['nominal'] }}',
                                    status: '{{ $pembayaran['status'] }}'
                                })"
                                class="border border-primary bg-transparent !text-primary hover:bg-secondary hover:border-secondary">
                                Detail
                            </x-form.button>
                        </x-table.td>
                    </x-table.tr>
                    @endforeach
                </tbody>
            </x-table.index>
        </div>
        <p class="text-xs text-neutral mt-3">Menampilkan {{ count($dummyData) }} data</p>
    </div>

    {{-- ================= PAGINATION ================= --}}
    <x-pagination />

    {{-- ================= MODAL ================= --}}
    <x-modal show="modalOpen" maxWidth="lg:max-w-[500px] max-w-[350px]">
        <template x-if="modalType === 'detail-pembayaran'">
            <div class="relative">

                <button
                    type="button"
                    class="absolute top-0 right-0 text-neutral hover:text-black text-xl font-bold"
                    @click="closeModal()">
                    ✕
                </button>

                <h2 class="text-xl font-bold mb-8">Struk Pembayaran</h2>

                <div class="flex flex-col gap-4">

                    <div class="grid grid-cols-3 gap-4 items-start">
                        <div class="flex flex-col gap-1">
                            <p class="text-neutral text-xs">Nama Lengkap</p>
                            <p class="text-black text-xs font-semibold" x-text="selectedPembayaran.nama"></p>
                        </div>

                        <div class="flex flex-col gap-1">
                            <p class="text-neutral text-xs">Nama Kost</p>
                            <p class="text-black text-xs font-semibold" x-text="selectedPembayaran.nama_kost"></p>
                        </div>

                        <div class="flex justify-end">
                            <template x-if="selectedPembayaran.status === 'Lunas'">
                                <x-badge type="success">Lunas</x-badge>
                            </template>

                            <template x-if="selectedPembayaran.status === 'Belum Lunas'">
                                <x-badge type="danger">Belum Lunas</x-badge>
                            </template>
                        </div>
                    </div>
                    <hr>

                    <div class="flex flex-col gap-4">
                        <div class="w-full flex justify-between">
                            <p class="text-xs text-neutral">Periode</p>
                            <p class="text-xs text-black font-semibold" x-text="selectedPembayaran.periode"></p>
                        </div>
                        <div class="w-full flex justify-between">
                            <p class="text-xs text-neutral">Nomor Transaksi</p>
                            <p class="text-xs text-black font-semibold" x-text="selectedPembayaran.nomor_transaksi"></p>
                        </div>
                        <div class="w-full flex justify-between">
                            <p class="text-xs text-neutral">Jenis Pembayaran</p>
                            <p class="text-xs text-black font-semibold" x-text="selectedPembayaran.jenis"></p>
                        </div>
                        <div class="w-full flex justify-between">
                            <p class="text-xs text-neutral">Metode Pembayaran</p>
                            <p class="text-xs text-black font-semibold" x-text="selectedPembayaran.metode"></p>
                        </div>
                        <div class="w-full flex justify-between">
                            <p class="text-xs text-neutral">Tanggal Transaksi</p>
                            <p class="text-xs text-black font-semibold" x-text="selectedPembayaran.waktu"></p>
                        </div>
                    </div>

                    <div class="flex justify-between w-full rounded-md bg-[#F8F8F8] shadow-sm px-4 py-3">
                        <p class="text-xs text-black font-medium">Total Dibayar</p>
                        <p class="text-sm font-semibold text-primary" x-text="selectedPembayaran.nominal"></p>
                    </div>
                        <x-badge type="success" class="text-center">Pembayaran Berhasil</x-badge>

                </div>
            </div>
        </template>
    </x-modal>

</div>

@endsection