@extends('layouts.superadmin')
@section('title', 'Manajemen Penghuni')

@section('content')

{{-- DATA DUMMY --}}
@php
$dummyData = [
    [
        'id' => 1,
        'nama' => 'Budi Santoso',
        'email' => 'budi@gmail.com',
        'telpon' => '081234567890',
        'alamat' => 'Jl. Merdeka No. 10, Bandung',
        'tanggal_daftar' => '01/01/2025',
        'status' => 'Aktif',
        'status_kost' => 'Sudah Gabung',
        'nama_kost' => 'Kost Melati',
        'nomor_kamar' => 'A01',
        'tanggal_masuk' => '05/01/2025',
        'skor_pembayaran' => 'Baik',
        'skor_sikap' => 'Baik',
        'skor_perawatan_fasilitas' => 'Perlu Perhatian',
    ],
    [
        'id' => 2,
        'nama' => 'Siti Rahayu',
        'email' => 'siti@gmail.com',
        'telpon' => '082345678901',
        'alamat' => 'Jl. Sudirman No. 5, Bandung',
        'tanggal_daftar' => '15/02/2025',
        'status' => 'Aktif',
        'status_kost' => 'Belum Gabung',
        'nama_kost' => '-',
        'nomor_kamar' => '-',
        'tanggal_masuk' => '-',
        'skor_pembayaran' => 'Baik',
        'skor_sikap' => 'Buruk',
        'skor_perawatan_fasilitas' => 'Perlu Perhatian',
    ],
    [
        'id' => 3,
        'nama' => 'Ahmad Fauzi',
        'email' => 'ahmad@gmail.com',
        'telpon' => '083456789012',
        'alamat' => 'Jl. Diponegoro No. 22, Bandung',
        'tanggal_daftar' => '10/03/2025',
        'status' => 'Dibatasi',
        'status_kost' => 'Sudah Gabung',
        'nama_kost' => 'Kost Anggrek',
        'nomor_kamar' => 'B03',
        'tanggal_masuk' => '15/03/2025',
        'skor_pembayaran' => 'Buruk',
        'skor_sikap' => 'Perlu Perhatian',
        'skor_perawatan_fasilitas' => 'Buruk',
    ],
    [
        'id' => 4,
        'nama' => 'Dewi Lestari',
        'email' => 'dewi@gmail.com',
        'telpon' => '084567890123',
        'alamat' => 'Jl. Gatot Subroto No. 8, Bandung',
        'tanggal_daftar' => '20/03/2025',
        'status' => 'Aktif',
        'status_kost' => 'Sudah Gabung',
        'nama_kost' => 'Kost Kenanga',
        'nomor_kamar' => 'C02',
        'tanggal_masuk' => '25/03/2025',
        'skor_pembayaran' => 'Baik',
        'skor_sikap' => 'Baik',
        'skor_perawatan_fasilitas' => 'Baik',
    ],
    [
        'id' => 5,
        'nama' => 'Reza Firmansyah',
        'email' => 'reza@gmail.com',
        'telpon' => '085678901234',
        'alamat' => 'Jl. Asia Afrika No. 3, Bandung',
        'tanggal_daftar' => '05/04/2025',
        'status' => 'Dibatasi',
        'status_kost' => 'Belum Gabung',
        'nama_kost' => '-',
        'nomor_kamar' => '-',
        'tanggal_masuk' => '-',
        'skor_pembayaran' => '-',
        'skor_sikap' => '-',
        'skor_perawatan_fasilitas' => '-',
    ],
];

$semuaPenghuni    = $dummyData;
$aktifPenghuni    = array_filter($dummyData, fn($p) => $p['status'] === 'Aktif');
$dibatasiPenghuni = array_filter($dummyData, fn($p) => $p['status'] === 'Dibatasi');
@endphp

<div
    x-data="{
        activeTab: 'semua',

        init() {
            @if(session('success'))
                this.successMessage = '{{ session('success') }}';
                this.modalType = 'success';
                this.modalOpen = true;
                setTimeout(() => { this.closeModal(); }, 2500);
            @endif
        },

        modalOpen: false,
        modalType: null,
        selectedPenghuni: {},

        openModal(type, data = {}) {
            this.selectedPenghuni = data;
            this.modalOpen = true;
            this.modalType = type;
        },

        closeModal() {
            this.modalOpen = false;
            this.modalType = null;
        },

        successMessage: '',

        showSuccess(message) {
            this.successMessage = message;
            this.modalType = 'success';
            this.modalOpen = true;
            setTimeout(() => { this.closeModal(); }, 2500);
        }
    }">

    {{-- ================= PAGE HEADER ================= --}}
    <x-page-header
        title="Manajemen Penghuni"
        description="Kelola semua penghuni kost yang terdaftar">
    </x-page-header>

    {{-- ================= SEARCH ================= --}}
    <x-search-input
        name="search_penghuni"
        placeholder="Cari" />

    {{-- ================= TABLE ================= --}}
    <div class="bg-white rounded-lg p-4 lg:p-6 mt-4 mb-6">

        {{-- ================= TAB ================= --}}
        <div class="flex lg:gap-6 gap-3 mb-6 min-w-max border-b">

            <button
                @click="activeTab = 'semua'"
                :class="activeTab === 'semua' ? 'border-primary text-primary font-bold' : 'border-transparent text-black font-medium'"
                class="pb-3 border-b-2 text-xs lg:text-sm transition">
                Semua
            </button>

            <button
                @click="activeTab = 'aktif'"
                :class="activeTab === 'aktif' ? 'border-primary text-primary font-bold' : 'border-transparent text-black font-medium'"
                class="pb-3 border-b-2 text-xs lg:text-sm transition">
                Aktif
            </button>

            <button
                @click="activeTab = 'dibatasi'"
                :class="activeTab === 'dibatasi' ? 'border-primary text-primary font-bold' : 'border-transparent text-black font-medium'"
                class="pb-3 border-b-2 text-xs lg:text-sm transition">
                Dibatasi
            </button>

        </div>

        {{-- ================= SEMUA ================= --}}
        <div x-show="activeTab === 'semua'" x-transition>
            <div class="overflow-x-auto">
                <x-table.index>
                    <thead class="sticky top-0 bg-white z-10 border-b border-default">
                        <x-table.tr>
                            <x-table.th>Nama Lengkap</x-table.th>
                            <x-table.th>Email</x-table.th>
                            <x-table.th>Status Kost</x-table.th>
                            <x-table.th>Status Akun</x-table.th>
                            <x-table.th class="text-center">Aksi</x-table.th>
                        </x-table.tr>
                    </thead>
                    <tbody>
                        @foreach($semuaPenghuni as $penghuni)
                        <x-table.tr>
                            <x-table.td class="font-medium">{{ $penghuni['nama'] }}</x-table.td>
                            <x-table.td>{{ $penghuni['email'] }}</x-table.td>
                            <x-table.td>
                                @if($penghuni['status_kost'] === 'Sudah Gabung')
                                    <x-badge type="success">Sudah Gabung</x-badge>
                                @else
                                    <x-badge type="neutral">Belum Gabung</x-badge>
                                @endif
                            </x-table.td>
                            <x-table.td>
                                @if($penghuni['status'] === 'Aktif')
                                    <x-badge type="success">Aktif</x-badge>
                                @else
                                    <x-badge type="danger">Dibatasi</x-badge>
                                @endif
                            </x-table.td>
                            <x-table.td class="text-center">
                                <x-form.button
                                    @click.prevent="openModal('detail-{{ $penghuni['status'] === 'Aktif' ? 'aktif' : 'dibatasi' }}', {
                                        id: {{ $penghuni['id'] }},
                                        name: '{{ $penghuni['nama'] }}',
                                        no_hp: '{{ $penghuni['telpon'] }}',
                                        email: '{{ $penghuni['email'] }}',
                                        alamat: '{{ $penghuni['alamat'] }}',
                                        tanggal_daftar: '{{ $penghuni['tanggal_daftar'] }}',
                                        status_kost: '{{ $penghuni['status_kost'] }}',
                                        nama_kost: '{{ $penghuni['nama_kost'] }}',
                                        tanggal_masuk: '{{ $penghuni['tanggal_masuk'] }}',
                                        skor_pembayaran: '{{ $penghuni['skor_pembayaran'] }}',
                                        skor_sikap: '{{ $penghuni['skor_sikap'] }}',
                                        skor_perawatan_fasilitas: '{{ $penghuni['skor_perawatan_fasilitas'] }}'
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
            <p class="text-xs text-neutral mt-3">Menampilkan {{ count($semuaPenghuni) }} data</p>
        </div>

        {{-- ================= AKTIF ================= --}}
        <div x-show="activeTab === 'aktif'" x-transition>
            <div class="overflow-x-auto">
                <x-table.index>
                    <thead class="sticky top-0 bg-white z-10 border-b border-default">
                        <x-table.tr>
                            <x-table.th>Nama Lengkap</x-table.th>
                            <x-table.th>Email</x-table.th>
                            <x-table.th>Status Kost</x-table.th>
                            <x-table.th>Status Akun</x-table.th>
                            <x-table.th class="text-center">Aksi</x-table.th>
                        </x-table.tr>
                    </thead>
                    <tbody>
                        @foreach($aktifPenghuni as $penghuni)
                        <x-table.tr>
                            <x-table.td class="font-medium">{{ $penghuni['nama'] }}</x-table.td>
                            <x-table.td>{{ $penghuni['email'] }}</x-table.td>
                            <x-table.td>
                                @if($penghuni['status_kost'] === 'Sudah Gabung')
                                    <x-badge type="success">Sudah Gabung</x-badge>
                                @else
                                    <x-badge>Belum Gabung</x-badge>
                                @endif
                            </x-table.td>
                            <x-table.td>
                                <x-badge type="success">Aktif</x-badge>
                            </x-table.td>
                            <x-table.td class="text-center">
                                <x-form.button
                                    @click.prevent="openModal('detail-aktif', {
                                        id: {{ $penghuni['id'] }},
                                        name: '{{ $penghuni['nama'] }}',
                                        no_hp: '{{ $penghuni['telpon'] }}',
                                        email: '{{ $penghuni['email'] }}',
                                        alamat: '{{ $penghuni['alamat'] }}',
                                        tanggal_daftar: '{{ $penghuni['tanggal_daftar'] }}',
                                        status_kost: '{{ $penghuni['status_kost'] }}',
                                        nama_kost: '{{ $penghuni['nama_kost'] }}',
                                        tanggal_masuk: '{{ $penghuni['tanggal_masuk'] }}',
                                        skor_pembayaran: '{{ $penghuni['skor_pembayaran'] }}',
                                        skor_sikap: '{{ $penghuni['skor_sikap'] }}',
                                        skor_perawatan_fasilitas: '{{ $penghuni['skor_perawatan_fasilitas'] }}'
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
            <p class="text-xs text-neutral mt-3">Menampilkan {{ count($aktifPenghuni) }} data</p>
        </div>

        {{-- ================= DIBATASI ================= --}}
        <div x-show="activeTab === 'dibatasi'" x-transition>
            <div class="overflow-x-auto">
                <x-table.index>
                    <thead class="sticky top-0 bg-white z-10 border-b border-default">
                        <x-table.tr>
                            <x-table.th>Nama Lengkap</x-table.th>
                            <x-table.th>Email</x-table.th>
                            <x-table.th>Status Kost</x-table.th>
                            <x-table.th>Status Akun</x-table.th>
                            <x-table.th class="text-center">Aksi</x-table.th>
                        </x-table.tr>
                    </thead>
                    <tbody>
                        @foreach($dibatasiPenghuni as $penghuni)
                        <x-table.tr>
                            <x-table.td class="font-medium">{{ $penghuni['nama'] }}</x-table.td>
                            <x-table.td>{{ $penghuni['email'] }}</x-table.td>
                            <x-table.td>
                                @if($penghuni['status_kost'] === 'Sudah Gabung')
                                    <x-badge type="success">Sudah Gabung</x-badge>
                                @else
                                    <x-badge>Belum Gabung</x-badge>
                                @endif
                            </x-table.td>
                            <x-table.td>
                                <x-badge type="danger">Dibatasi</x-badge>
                            </x-table.td>
                            <x-table.td class="text-center">
                                <x-form.button
                                    @click.prevent="openModal('detail-dibatasi', {
                                        id: {{ $penghuni['id'] }},
                                        name: '{{ $penghuni['nama'] }}',
                                        no_hp: '{{ $penghuni['telpon'] }}',
                                        email: '{{ $penghuni['email'] }}',
                                        alamat: '{{ $penghuni['alamat'] }}',
                                        tanggal_daftar: '{{ $penghuni['tanggal_daftar'] }}',
                                        status_kost: '{{ $penghuni['status_kost'] }}',
                                        nama_kost: '{{ $penghuni['nama_kost'] }}',
                                        tanggal_masuk: '{{ $penghuni['tanggal_masuk'] }}',
                                        skor_pembayaran: '{{ $penghuni['skor_pembayaran'] }}',
                                        skor_sikap: '{{ $penghuni['skor_sikap'] }}',
                                        skor_perawatan_fasilitas: '{{ $penghuni['skor_perawatan_fasilitas'] }}'
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
            <p class="text-xs text-neutral mt-3">Menampilkan {{ count($dibatasiPenghuni) }} data</p>
        </div>

    </div>

    {{-- ================= PAGINATION ================= --}}
    <x-pagination />

    {{-- ================= MODAL ================= --}}
    <x-modal show="modalOpen" maxWidth="lg:max-w-[500px] max-w-[350px]">

        {{-- ================= DETAIL AKTIF ================= --}}
        <template x-if="modalType === 'detail-aktif'">
            <div class="relative">

                <button type="button" class="absolute top-0 right-0 text-xl" @click="closeModal()">✕</button>

                {{-- HEADER --}}
                <div class="flex items-center justify-between mb-6 pr-6">
                    <h2 class="text-xl font-bold">Detail Penghuni</h2>
                    <x-badge type="success" class="!px-3 !py-1 !text-xs">Aktif</x-badge>
                </div>

                <div class="space-y-4 lg:max-h-[450px] max-h-[300px] overflow-y-auto pr-1">

                    {{-- SECTION 1 --}}
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <p class="text-xs text-neutral mb-1">Nama Lengkap</p>
                            <p class="text-xs font-medium" x-text="selectedPenghuni.name"></p>
                        </div>
                        <div>
                            <p class="text-xs text-neutral mb-1">Nomor Telepon</p>
                            <p class="text-xs font-medium" x-text="selectedPenghuni.no_hp"></p>
                        </div>
                        <div>
                            <p class="text-xs text-neutral mb-1">Email</p>
                            <p class="text-xs font-medium" x-text="selectedPenghuni.email"></p>
                        </div>
                    </div>
                    <hr>

                    {{-- SECTION 2 --}}
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <p class="text-xs text-neutral mb-1">Status Kost</p>
                            <template x-if="selectedPenghuni.status_kost === 'Sudah Gabung'">
                                <x-badge type="success" class="!px-3 !py-1 !text-xs">Sudah Gabung</x-badge>
                            </template>

                            <template x-if="selectedPenghuni.status_kost !== 'Sudah Gabung'">
                                <x-badge type="neutral">Belum Gabung</x-badge>
                            </template>
                        </div>

                        <div>
                            <p class="text-xs text-neutral mb-1">Nama Kost</p>
                            <p class="text-xs font-medium" x-text="selectedPenghuni.nama_kost"></p>
                        </div>

                        <div>
                            <p class="text-xs text-neutral mb-1">Tanggal Masuk</p>
                            <p class="text-xs font-medium" x-text="selectedPenghuni.tanggal_masuk"></p>
                        </div>
                    </div>                        
                    <hr>

                    {{-- SECTION 3 --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-neutral mb-1">Alamat</p>
                            <p class="text-xs font-medium" x-text="selectedPenghuni.alamat"></p>
                        </div>
                        <div>
                            <p class="text-xs text-neutral mb-1">Tanggal Daftar</p>
                            <p class="text-xs font-medium" x-text="selectedPenghuni.tanggal_daftar"></p>
                        </div>
                    </div>
                    <hr>

                    {{-- SECTION 4: Penilaian Penghuni --}}
                    <div class="flex items-center justify-between">
                        <p class="text-xm font-semibold text-primary">Penilaian Penghuni</p>
                        <a href="#" class="text-xs text-neutral no-underline hover:underline">Lihat selengkapnya</a>
                    </div>
                    <div class="grid grid-cols- gap-3">
                        <div class="flex items-center justify-between">
                            <p class="text-xs text-neutral">Pembayaran</p>
                            <template x-if="selectedPenghuni.skor_pembayaran === 'Baik'">
                                <x-badge type="success" class="!px-3 !py-1 !text-xs">Baik</x-badge>
                            </template>

                            <template x-if="selectedPenghuni.skor_pembayaran === 'Perlu Perhatian'">
                                <x-badge type="warning" class="!px-3 !py-1 !text-xs">Perlu Perhatian</x-badge>
                            </template>

                            <template x-if="selectedPenghuni.skor_pembayaran === 'Buruk'">
                                <x-badge type="danger" class="!px-3 !py-1 !text-xs">Buruk</x-badge>
                            </template>
                        </div>

                        <div class="flex items-center justify-between">
                            <p class="text-xs text-neutral">Sikap</p>
                            <template x-if="selectedPenghuni.skor_sikap === 'Baik'">
                                <x-badge type="success" class="!px-3 !py-1 !text-xs">Baik</x-badge>
                            </template>

                            <template x-if="selectedPenghuni.skor_sikap === 'Perlu Perhatian'">
                                <x-badge type="warning" class="!px-3 !py-1 !text-xs">Perlu Perhatian</x-badge>
                            </template>

                            <template x-if="selectedPenghuni.skor_sikap === 'Buruk'">
                                <x-badge type="danger" class="!px-3 !py-1 !text-xs">Buruk</x-badge>
                            </template>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <p class="text-xs text-neutral">Perawatan Fasilitas</p>
                            <template x-if="selectedPenghuni.skor_perawatan_fasilitas === 'Baik'">
                                <x-badge type="success" class="!px-3 !py-1 !text-xs">Baik</x-badge>
                            </template>

                            <template x-if="selectedPenghuni.skor_perawatan_fasilitas === 'Perlu Perhatian'">
                                <x-badge type="warning" class="!px-3 !py-1 !text-xs">Perlu Perhatian</x-badge>
                            </template>

                            <template x-if="selectedPenghuni.skor_perawatan_fasilitas === 'Buruk'">
                                <x-badge type="danger" class="!px-3 !py-1 !text-xs">Buruk</x-badge>
                            </template>
                        </div>
                    </div>

                </div>

                <div class="mt-6">
                    <x-form.button
                        type="button"
                        class="w-full text-white !bg-red-600 hover:!bg-red-100 hover:!text-red-600"
                        @click="modalType = 'confirm-batasi'">
                        Batasi Akun
                    </x-form.button>
                </div>

            </div>
        </template>


        {{-- ================= DETAIL DIBATASI ================= --}}
        <template x-if="modalType === 'detail-dibatasi'">
            <div class="relative">

                <button type="button" class="absolute top-0 right-0 text-xl" @click="closeModal()">✕</button>

                {{-- HEADER --}}
                <div class="flex items-center justify-between mb-6 pr-6">
                    <h2 class="text-xl font-bold">Detail Penghuni</h2>
                    <x-badge type="danger" class="!px-3 !py-1 !text-xs">Dibatasi</x-badge>
                </div>

                <div class="space-y-4 lg:max-h-[450px] max-h-[300px] overflow-y-auto pr-1">

                    {{-- SECTION 1 --}}
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <p class="text-xs text-neutral mb-1">Nama Lengkap</p>
                            <p class="text-xs font-medium" x-text="selectedPenghuni.name"></p>
                        </div>
                        <div>
                            <p class="text-xs text-neutral mb-1">Nomor Telepon</p>
                            <p class="text-xs font-medium" x-text="selectedPenghuni.no_hp"></p>
                        </div>
                        <div>
                            <p class="text-xs text-neutral mb-1">Email</p>
                            <p class="text-xs font-medium" x-text="selectedPenghuni.email"></p>
                        </div>
                    </div>                    
                    <hr>

                    {{-- SECTION 2 --}}
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <p class="text-xs text-neutral mb-1">Status Kost</p>
                            <template x-if="selectedPenghuni.status_kost === 'Sudah Gabung'">
                                <x-badge type="success" class="!px-3 !py-1 !text-xs">Sudah Gabung</x-badge>
                            </template>

                            <template x-if="selectedPenghuni.status_kost !== 'Sudah Gabung'">
                                <x-badge type="neutral">Belum Gabung</x-badge>
                            </template>
                        </div>
                        <div>
                            <p class="text-xs text-neutral mb-1">Nama Kost</p>
                            <p class="text-xs font-medium" x-text="selectedPenghuni.nama_kost"></p>
                        </div>
                        <div>
                            <p class="text-xs text-neutral mb-1">Tanggal Masuk</p>
                            <p class="text-xs font-medium" x-text="selectedPenghuni.tanggal_masuk"></p>
                        </div>
                    </div>
                    <hr>

                    {{-- SECTION 3 --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-neutral mb-1">Alamat</p>
                            <p class="text-xs font-medium" x-text="selectedPenghuni.alamat"></p>
                        </div>
                        <div>
                            <p class="text-xs text-neutral mb-1">Tanggal Daftar</p>
                            <p class="text-xs font-medium" x-text="selectedPenghuni.tanggal_daftar"></p>
                        </div>
                    </div>
                    <hr>

                    {{-- SECTION 4: Penilaian Penghuni --}}
                    <div class="flex items-center justify-between">
                        <p class="text-xm font-semibold text-primary">Penilaian Penghuni</p>
                        <a href="#" class="text-xs text-neutral no-underline hover:underline">Lihat selengkapnya</a>
                    </div>
                    <div class="grid grid-cols- gap-3">
                        <div class="flex items-center justify-between">
                            <p class="text-xs text-neutral">Pembayaran</p>
                            <template x-if="selectedPenghuni.skor_pembayaran === 'Baik'">
                                <x-badge type="success" class="!px-3 !py-1 !text-xs">Baik</x-badge>
                            </template>

                            <template x-if="selectedPenghuni.skor_pembayaran === 'Perlu Perhatian'">
                                <x-badge type="warning" class="!px-3 !py-1 !text-xs">Perlu Perhatian</x-badge>
                            </template>

                            <template x-if="selectedPenghuni.skor_pembayaran === 'Buruk'">
                                <x-badge type="danger" class="!px-3 !py-1 !text-xs">Buruk</x-badge>
                            </template>
                        </div>

                        <div class="flex items-center justify-between">
                            <p class="text-xs text-neutral">Sikap</p>
                            <template x-if="selectedPenghuni.skor_sikap === 'Baik'">
                                <x-badge type="success" class="!px-3 !py-1 !text-xs">Baik</x-badge>
                            </template>

                            <template x-if="selectedPenghuni.skor_sikap === 'Perlu Perhatian'">
                                <x-badge type="warning" class="!px-3 !py-1 !text-xs">Perlu Perhatian</x-badge>
                            </template>

                            <template x-if="selectedPenghuni.skor_sikap === 'Buruk'">
                                <x-badge type="danger" class="!px-3 !py-1 !text-xs">Buruk</x-badge>
                            </template>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <p class="text-xs text-neutral">Perawatan Fasilitas</p>
                            <template x-if="selectedPenghuni.skor_perawatan_fasilitas === 'Baik'">
                                <x-badge type="success" class="!px-3 !py-1 !text-xs">Baik</x-badge>
                            </template>

                            <template x-if="selectedPenghuni.skor_perawatan_fasilitas === 'Perlu Perhatian'">
                                <x-badge type="warning" class="!px-3 !py-1 !text-xs">Perlu Perhatian</x-badge>
                            </template>

                            <template x-if="selectedPenghuni.skor_perawatan_fasilitas === 'Buruk'">
                                <x-badge type="danger" class="!px-3 !py-1 !text-xs">Buruk</x-badge>
                            </template>
                        </div>
                    </div>

                </div>

                <div class="mt-6">
                    <x-form.button
                        type="button"
                        class="w-full text-white !bg-green-600 hover:!bg-green-100 hover:!text-green-600"
                        @click="modalType = 'confirm-aktifkan'">
                        Aktifkan Akun
                    </x-form.button>
                </div>

            </div>
        </template>


        {{-- ================= CONFIRM BATASI ================= --}}
        <template x-if="modalType === 'confirm-batasi'">
            <div class="relative">

                <button type="button" class="absolute top-0 right-0 text-xl" @click="closeModal()">✕</button>

                <h2 class="text-xl font-bold mb-4">Konfirmasi Batasi Akun</h2>

                <p class="text-xs text-neutral">Apakah Anda yakin ingin membatasi akun penghuni ini? Penghuni tidak akan bisa mengakses aplikasi.</p>

                <div class="flex gap-3 mt-8">
                    <x-form.button
                        type="button"
                        class="w-full !text-neutral !bg-transparent border-2 !border-neutral hover:!bg-neutral hover:!text-white"
                        @click="modalType = 'detail-aktif'">
                        Batal
                    </x-form.button>
                    <x-form.button
                        type="button"
                        class="w-full !text-white !bg-red-600 hover:!bg-red-100 hover:!text-red-600"
                        @click="$refs.formBatasi.submit()">
                        Batasi
                    </x-form.button>
                </div>

                <form x-ref="formBatasi" :action="'/superadmin/manajemen-penghuni/batasi/' + selectedPenghuni.id" method="POST" class="hidden">
                    @csrf
                </form>

            </div>
        </template>


        {{-- ================= CONFIRM AKTIFKAN ================= --}}
        <template x-if="modalType === 'confirm-aktifkan'">
            <div class="relative">

                <button type="button" class="absolute top-0 right-0 text-xl" @click="closeModal()">✕</button>

                <h2 class="text-xl font-bold mb-4">Konfirmasi Aktifkan Akun</h2>

                <p class="text-xs text-neutral">Apakah Anda yakin ingin mengaktifkan kembali akun penghuni ini?</p>

                <div class="flex gap-3 mt-8">
                    <x-form.button
                        type="button"
                        class="w-full !text-neutral !bg-transparent border-2 !border-neutral hover:!bg-neutral hover:!text-white"
                        @click="modalType = 'detail-dibatasi'">
                        Batal
                    </x-form.button>
                    <x-form.button
                        type="button"
                        class="w-full !text-white !bg-green-600 hover:!bg-green-100 hover:!text-green-600"
                        @click="$refs.formAktifkan.submit()">
                        Aktifkan
                    </x-form.button>
                </div>

                <form x-ref="formAktifkan" :action="'/superadmin/manajemen-penghuni/aktifkan/' + selectedPenghuni.id" method="POST" class="hidden">
                    @csrf
                </form>

            </div>
        </template>


        {{-- ================= SUCCESS ================= --}}
        <template x-if="modalType === 'success'">
            <div class="text-center">
                <div class="flex justify-center mb-4">
                    <img src="{{ asset('assets/icons/success-modal-icon.png') }}" class="w-12">
                </div>
                <h2 class="text-lg font-bold">
                    <span x-text="successMessage"></span>
                </h2>
            </div>
        </template>

    </x-modal>

</div>

@endsection