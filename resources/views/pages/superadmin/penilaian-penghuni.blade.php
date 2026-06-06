@extends('layouts.superadmin')
@section('title', 'Penilaian Penghuni')
@section('content')

{{-- DATA DUMMY --}}
@php
$dummyData = [
[
'id' => 1,
'nama' => 'Andi Marito',
'pemilik_kost' => 'Rozali Siregar',
'email' => 'andimarito@gmail.com',
'telpon' => '081234567892',
'tanggal_penilaian' => '08/04/2026',
'nama_kost' => 'Kost Jaya Abadi',
'kode_kost' => 'JYABD1',
'alamat' => 'Jl. Merdeka No. 123, Jakarta',
'tanggal_daftar' => '08/01/2026',
'status' => 'Aktif',
'sertifikat' => 'https://www.w3.org/WAI/WCAG21/Techniques/pdf/pdf-sample.pdf',
],
[
'id' => 2,
'nama' => 'Siti Rahayu',
'pemilik_kost' => 'Rozali Siregar',
'email' => 'siti@gmail.com',
'telpon' => '082345678901',
'tanggal_penilaian' => '08/04/2026',
'nama_kost' => 'Kost Mawar',
'kode_kost' => '-',
'alamat' => 'Jl. Sudirman No. 5, Bandung',
'tanggal_daftar' => '15/02/2025',
'status' => 'Menunggu',
'sertifikat' => 'https://www.w3.org/WAI/WCAG21/Techniques/pdf/pdf-sample.pdf',
],
[
'id' => 3,
'nama' => 'Ahmad Fauzi',
'pemilik_kost' => 'Rozali Siregar',
'email' => 'ahmad@gmail.com',
'telpon' => '083456789012',
'tanggal_penilaian' => '08/04/2026',
'nama_kost' => 'Kost Anggrek',
'kode_kost' => 'ANGRK3',
'alamat' => 'Jl. Diponegoro No. 22, Bandung',
'tanggal_daftar' => '10/03/2025',
'status' => 'Ditolak',
'sertifikat' => 'https://www.w3.org/WAI/WCAG21/Techniques/pdf/pdf-sample.pdf',
],
[
'id' => 4,
'nama' => 'Dewi Lestari',
'pemilik_kost' => 'Rozali Siregar',
'email' => 'dewi@gmail.com',
'telpon' => '084567890123',
'tanggal_penilaian' => '08/04/2026',
'nama_kost' => 'Kost Kenanga',
'kode_kost' => 'KNNGG4',
'alamat' => 'Jl. Gatot Subroto No. 8, Bandung',
'tanggal_daftar' => '20/03/2025',
'status' => 'Aktif',
'sertifikat' => 'https://www.w3.org/WAI/WCAG21/Techniques/pdf/pdf-sample.pdf',
],
[
'id' => 5,
'nama' => 'Reza Firmansyah',
'pemilik_kost' => 'Rozali Siregar',
'email' => 'reza@gmail.com',
'telpon' => '085678901234',
'tanggal_penilaian' => '08/04/2026',
'nama_kost' => 'Kost Cempaka',
'kode_kost' => '-',
'alamat' => 'Jl. Asia Afrika No. 3, Bandung',
'tanggal_daftar' => '05/04/2025',
'status' => 'Menunggu',
'sertifikat' => 'https://www.w3.org/WAI/WCAG21/Techniques/pdf/pdf-sample.pdf',
],
];

$semuaPengelola = $dummyData;
$aktifPengelola = array_filter($dummyData, fn($p) => $p['status'] === 'Aktif');
$menungguPengelola = array_filter($dummyData, fn($p) => $p['status'] === 'Menunggu');
$dibatasiPengelola = array_filter($dummyData, fn($p) => $p['status'] === 'Dibatasi');
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
        selectedPengelola: {},

        openModal(type, data = {}) {
            this.selectedPengelola = data;
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
        title="Penilaian Penghuni"
        description="Validasi penilaian penghuni dari pengelola kost">
    </x-page-header>

    {{-- ================= SEARCH ================= --}}
    <x-search-input
        name="search"
        placeholder="Cari" />

    {{-- ================= TABLE ================= --}}
    <div class="bg-white rounded-lg p-4 lg:p-6 mt-4 mb-6">

        {{-- ================= TAB ================= --}}
        <div class="flex lg:gap-6 gap-3 mb-6 min-w-[900px] border-b">

            <button
                @click="activeTab = 'semua'"
                :class="activeTab === 'semua' ? 'border-primary text-primary font-bold' : 'border-transparent text-black font-medium'"
                class="pb-3 border-b-2 text-xs lg:text-sm transition">
                Semua
            </button>

            <button
                @click="activeTab = 'menunggu'"
                :class="activeTab === 'menunggu' ? 'border-primary text-primary font-bold' : 'border-transparent text-black font-medium'"
                class="pb-3 border-b-2 text-xs lg:text-sm transition">
                Menunggu
            </button>

            <button
                @click="activeTab = 'disetujui'"
                :class="activeTab === 'disetujui' ? 'border-primary text-primary font-bold' : 'border-transparent text-black font-medium'"
                class="pb-3 border-b-2 text-xs lg:text-sm transition">
                Disetujui
            </button>

            <button
                @click="activeTab = 'ditolak'"
                :class="activeTab === 'ditolak' ? 'border-primary text-primary font-bold' : 'border-transparent text-black font-medium'"
                class="pb-3 border-b-2 text-xs lg:text-sm transition">
                Ditolak
            </button>

        </div>

        {{-- ================= SEMUA ================= --}}
        <div x-show="activeTab === 'semua'" x-transition>
            <div class="overflow-x-auto">
                <x-table.index>
                    <thead class="sticky top-0 bg-white z-10 border-b border-default">
                        <x-table.tr>
                            <x-table.th>Nama Lengkap</x-table.th>
                            <x-table.th>Nama Kost</x-table.th>
                            <x-table.th>Tanggal Penilaian</x-table.th>
                            <x-table.th>Status</x-table.th>
                            <x-table.th class="text-center">Aksi</x-table.th>
                        </x-table.tr>
                    </thead>
                    <tbody>
                        @foreach($semuaPengelola as $pengelola)
                        <x-table.tr>
                            <x-table.td class="font-medium">{{ $pengelola['nama'] }}</x-table.td>
                            <x-table.td>{{ $pengelola['nama_kost'] }}</x-table.td>
                            <x-table.td>{{ $pengelola['tanggal_penilaian'] }}</x-table.td>
                            <x-table.td>
                                @if($pengelola['status'] === 'Aktif')
                                <x-badge type="success">Disetujui</x-badge>
                                @elseif($pengelola['status'] === 'Menunggu')
                                <x-badge type="warning">Menunggu</x-badge>
                                @elseif($pengelola['status'] === 'Ditolak')
                                <x-badge type="danger">Ditolak</x-badge>
                                @endif
                            </x-table.td>
                            <x-table.td class="text-center">
                                <x-form.button
                                    @click.prevent="openModal('detail-{{ $pengelola['status'] === 'Aktif' ? 'aktif' : ($pengelola['status'] === 'Menunggu' ? 'menunggu' : 'dibatasi') }}', {
                                        id: {{ $pengelola['id'] }},
                                        name: '{{ $pengelola['nama'] }}',
                                        pemilik_kost: '{{ $pengelola['pemilik_kost'] }}',
                                        no_hp: '{{ $pengelola['telpon'] }}',
                                        email: '{{ $pengelola['email'] }}',
                                        nama_kost: '{{ $pengelola['nama_kost'] }}',
                                        kode_kost: '{{ $pengelola['kode_kost'] }}',
                                        alamat: '{{ $pengelola['alamat'] }}',
                                        tanggal_daftar: '{{ $pengelola['tanggal_daftar'] }}',
                                        sertifikat: '{{ $pengelola['sertifikat'] }}'
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
        </div>

        {{-- ================= Menunggu ================= --}}
        <div x-show="activeTab === 'menunggu'" x-transition>
            <div class="overflow-x-auto">
                <x-table.index>
                    <thead class="sticky top-0 bg-white z-10 border-b border-default">
                        <x-table.tr>
                            <x-table.th>Nama Lengkap</x-table.th>
                            <x-table.th>Nama Kost</x-table.th>
                            <x-table.th>Tanggal Penilaian</x-table.th>
                            <x-table.th>Status</x-table.th>
                            <x-table.th class="text-center">Aksi</x-table.th>
                        </x-table.tr>
                    </thead>
                    <tbody>
                        @foreach($aktifPengelola as $pengelola)
                        <x-table.tr>
                            <x-table.td class="font-medium">{{ $pengelola['nama'] }}</x-table.td>
                            <x-table.td>{{ $pengelola['nama_kost'] }}</x-table.td>
                            <x-table.td>{{ $pengelola['tanggal_penilaian'] }}</x-table.td>
                            <x-table.td>
                                <x-badge type="warning">Menunggu</x-badge>
                            </x-table.td>
                            <x-table.td class="text-center">
                                <x-form.button
                                    @click.prevent="openModal('detail-menunggu', {
                                        id: {{ $pengelola['id'] }},
                                        name: '{{ $pengelola['nama'] }}',
                                        pemilik_kost: '{{ $pengelola['pemilik_kost'] }}',
                                        no_hp: '{{ $pengelola['telpon'] }}',
                                        email: '{{ $pengelola['email'] }}',
                                        nama_kost: '{{ $pengelola['nama_kost'] }}',
                                        kode_kost: '{{ $pengelola['kode_kost'] }}',
                                        alamat: '{{ $pengelola['alamat'] }}',
                                        tanggal_daftar: '{{ $pengelola['tanggal_daftar'] }}',
                                        sertifikat: '{{ $pengelola['sertifikat'] }}'
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
            <p class="text-xs text-neutral mt-3">Menampilkan {{ count($aktifPengelola) }} data</p>
        </div>

        {{-- ================= Disetujui ================= --}}
        <div x-show="activeTab === 'disetujui'" x-transition>
            <div class="overflow-x-auto">
                <x-table.index>
                    <thead class="sticky top-0 bg-white z-10 border-b border-default">
                        <x-table.tr>
                            <x-table.th>Nama Lengkap</x-table.th>
                            <x-table.th>Nama Kost</x-table.th>
                            <x-table.th>Tanggal Penilaian</x-table.th>
                            <x-table.th>Status</x-table.th>
                            <x-table.th class="text-center">Aksi</x-table.th>
                        </x-table.tr>
                    </thead>
                    <tbody>
                        @foreach($aktifPengelola as $pengelola)
                        <x-table.tr>
                            <x-table.td class="font-medium">{{ $pengelola['nama'] }}</x-table.td>
                            <x-table.td>{{ $pengelola['nama_kost'] }}</x-table.td>
                            <x-table.td>{{ $pengelola['tanggal_penilaian'] }}</x-table.td>
                            <x-table.td>
                                <x-badge type="success">Disetujui</x-badge>
                            </x-table.td>
                            <x-table.td class="text-center">
                                <x-form.button
                                    @click.prevent="openModal('detail-aktif', {
                                        id: {{ $pengelola['id'] }},
                                        name: '{{ $pengelola['nama'] }}',
                                        pemilik_kost: '{{ $pengelola['pemilik_kost'] }}',
                                        no_hp: '{{ $pengelola['telpon'] }}',
                                        email: '{{ $pengelola['email'] }}',
                                        nama_kost: '{{ $pengelola['nama_kost'] }}',
                                        kode_kost: '{{ $pengelola['kode_kost'] }}',
                                        alamat: '{{ $pengelola['alamat'] }}',
                                        tanggal_daftar: '{{ $pengelola['tanggal_daftar'] }}',
                                        sertifikat: '{{ $pengelola['sertifikat'] }}'
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
            <p class="text-xs text-neutral mt-3">Menampilkan {{ count($menungguPengelola) }} data</p>
        </div>

        {{-- ================= Ditolak ================= --}}
        <div x-show="activeTab === 'ditolak'" x-transition>
            <div class="overflow-x-auto">
                <x-table.index>
                    <thead class="sticky top-0 bg-white z-10 border-b border-default">
                        <x-table.tr>
                            <x-table.th>Nama Lengkap</x-table.th>
                            <x-table.th>Nama Kost</x-table.th>
                            <x-table.th>Tanggal Penilaian</x-table.th>
                            <x-table.th>Status</x-table.th>
                            <x-table.th class="text-center">Aksi</x-table.th>
                        </x-table.tr>
                    </thead>
                    <tbody>
                        @foreach($aktifPengelola as $pengelola)
                        <x-table.tr>
                            <x-table.td class="font-medium">{{ $pengelola['nama'] }}</x-table.td>
                            <x-table.td>{{ $pengelola['nama_kost'] }}</x-table.td>
                            <x-table.td>{{ $pengelola['tanggal_penilaian'] }}</x-table.td>
                            <x-table.td>
                                <x-badge type="danger">Ditolak</x-badge>
                            </x-table.td>
                            <x-table.td class="text-center">
                                <x-form.button
                                    @click.prevent="openModal('detail-dibatasi', {
                                        id: {{ $pengelola['id'] }},
                                        name: '{{ $pengelola['nama'] }}',
                                        pemilik_kost: '{{ $pengelola['pemilik_kost'] }}',
                                        no_hp: '{{ $pengelola['telpon'] }}',
                                        email: '{{ $pengelola['email'] }}',
                                        nama_kost: '{{ $pengelola['nama_kost'] }}',
                                        kode_kost: '{{ $pengelola['kode_kost'] }}',
                                        alamat: '{{ $pengelola['alamat'] }}',
                                        tanggal_daftar: '{{ $pengelola['tanggal_daftar'] }}',
                                        sertifikat: '{{ $pengelola['sertifikat'] }}'
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
            <p class="text-xs text-neutral mt-3">Menampilkan {{ count($dibatasiPengelola) }} data</p>
        </div>

    </div>

    {{-- ================= PAGINATION ================= --}}
    <x-pagination />

    {{-- ================= MODAL ================= --}}
    <x-modal show="modalOpen" maxWidth="lg:max-w-[500px] max-w-[350px]">

        {{-- ================= DETAIL DISETUJUI ================= --}}
        <template x-if="modalType === 'detail-aktif'">
            <div class="relative">

                <button type="button" class="absolute top-0 right-0 text-xl" @click="closeModal()">✕</button>

                {{-- HEADER --}}
                <div class="flex items-center justify-between !mb-6">
                    <h2 class="text-xl font-bold">Detail Penilaian Penghuni</h2>
                </div>

                <div class="space-y-4 lg:max-h-[450px] max-h-[300px] overflow-y-auto pr-1">

                    {{-- SECTION 1 --}}
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <p class="text-xs text-neutral mb-1">Nama Lengkap</p>
                            <p class="text-xs font-medium" x-text="selectedPengelola.name"></p>
                        </div>
                        <div>
                            <p class="text-xs text-neutral mb-1">Nomor HP</p>
                            <p class="text-xs font-medium" x-text="selectedPengelola.no_hp"></p>
                        </div>
                        <div>
                            <p class="text-xs text-neutral mb-1">Email</p>
                            <p class="text-xs font-medium" x-text="selectedPengelola.email"></p>
                        </div>
                    </div>

                    <hr>

                    {{-- SECTION 2 --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-neutral mb-1">Nama Kost</p>
                            <p class="text-xs font-medium" x-text="selectedPengelola.nama_kost"></p>
                        </div>
                        <div>
                            <p class="text-xs text-neutral mb-1">Pemilik Kost</p>
                            <p class="text-xs font-medium" x-text="selectedPengelola.pemilik_kost"></p>
                        </div>
                    </div>
                    <hr>

                    {{-- SECTION 3 --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-neutral mb-1">Alamat</p>
                            <p class="text-xs font-medium" x-text="selectedPengelola.alamat"></p>
                        </div>
                        <div>
                            <p class="text-xs text-neutral mb-1">Tanggal Penilaian</p>
                            <p class="text-xs font-medium" x-text="selectedPengelola.tanggal_daftar"></p>
                        </div>
                    </div>
                    <hr>

                    {{-- SECTION 4 --}}
                    <x-form.input label="Ketertiban Pembayaran" name="ketertiban-pembayaran" value="Baik" class="!bg-[#F8F8F8] text-xs" disabled />
                    <x-form.input label="Sikap" name="sikap" value="Baik" class="!bg-[#F8F8F8] text-xs" disabled />
                    <x-form.input label="Perawatan Fasilitas" name="perawatan-fasilitas" value="Baik" class="!bg-[#F8F8F8] text-xs" disabled />
                    <x-form.input label="Catatan Tambahan" name="catatan-tambahan" value="Anaknya baik tidak pernah telat membayar." class="!bg-[#F8F8F8] text-xs" disabled />
                    {{-- SERTIFIKAT --}}
                    <div>
                        <p class="text-xs text-neutral mb-2">Sertifikat</p>
                        <template x-if="selectedPengelola.sertifikat">
                            <a :href="selectedPengelola.sertifikat" target="_blank"
                                class="flex items-center gap-3 bg-[#F8F8F8] rounded-xl px-4 py-3 w-full hover:bg-gray-100 transition no-underline">
                                <img src="{{ asset('assets/icons/pdf-icon.png') }}" class="w-8 h-8 shrink-0">
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-medium text-black truncate">Sertifikat Kost</p>
                                    <p class="text-[10px] text-gray-500 mt-0.5">Klik untuk membuka</p>
                                </div>
                            </a>
                        </template>
                        <template x-if="!selectedPengelola.sertifikat">
                            <p class="text-xs text-gray-400 italic">Tidak ada sertifikat</p>
                        </template>
                    </div>

                </div>

                <div class="mt-6">
                    <x-form.button
                        type="button"
                        class="w-full !text-neutral !bg-[#E2E2E2]"
                        disabled>
                        Disetujui
                    </x-form.button>
                </div>

            </div>
        </template>


        {{-- ================= DETAIL MENUNGGU ================= --}}
        <template x-if="modalType === 'detail-menunggu'">
            <div class="relative">

                <button type="button" class="absolute top-0 right-0 text-xl" @click="closeModal()">✕</button>

                {{-- HEADER --}}
                <div class="flex items-center justify-between mb-6 pr-6">
                    <h2 class="text-xl font-bold">Detail Penilaian Penghuni</h2>
                </div>

                <div class="space-y-4 lg:max-h-[450px] max-h-[300px] overflow-y-auto pr-1">

                    {{-- SECTION 1 --}}
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <p class="text-xs text-neutral mb-1">Nama Lengkap</p>
                            <p class="text-xs font-medium" x-text="selectedPengelola.name"></p>
                        </div>
                        <div>
                            <p class="text-xs text-neutral mb-1">Nomor HP</p>
                            <p class="text-xs font-medium" x-text="selectedPengelola.no_hp"></p>
                        </div>
                        <div>
                            <p class="text-xs text-neutral mb-1">Email</p>
                            <p class="text-xs font-medium" x-text="selectedPengelola.email"></p>
                        </div>
                    </div>

                    <hr>

                    {{-- SECTION 2 --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-neutral mb-1">Nama Kost</p>
                            <p class="text-xs font-medium" x-text="selectedPengelola.nama_kost"></p>
                        </div>
                        <div>
                            <p class="text-xs text-neutral mb-1">Pemilik Kost</p>
                            <p class="text-xs font-medium" x-text="selectedPengelola.pemilik_kost"></p>
                        </div>
                    </div>
                    <hr>

                    {{-- SECTION 3 --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-neutral mb-1">Alamat</p>
                            <p class="text-xs font-medium" x-text="selectedPengelola.alamat"></p>
                        </div>
                        <div>
                            <p class="text-xs text-neutral mb-1">Tanggal Penilaian</p>
                            <p class="text-xs font-medium" x-text="selectedPengelola.tanggal_daftar"></p>
                        </div>
                    </div>
                    <hr>

                    {{-- SECTION 4 --}}
                    <x-form.input label="Ketertiban Pembayaran" name="ketertiban-pembayaran" value="Baik" class="!bg-[#F8F8F8] text-xs" disabled />
                    <x-form.input label="Sikap" name="sikap" value="Baik" class="!bg-[#F8F8F8] text-xs" disabled />
                    <x-form.input label="Perawatan Fasilitas" name="perawatan-fasilitas" value="Baik" class="!bg-[#F8F8F8] text-xs" disabled />
                    <x-form.input label="Catatan Tambahan" name="catatan-tambahan" value="Anaknya baik tidak pernah telat membayar." class="!bg-[#F8F8F8] text-xs" disabled />
                    {{-- SERTIFIKAT --}}
                    <div>
                        <p class="text-xs text-neutral mb-2">Sertifikat</p>
                        <template x-if="selectedPengelola.sertifikat">
                            <a :href="selectedPengelola.sertifikat" target="_blank"
                                class="flex items-center gap-3 bg-[#F8F8F8] rounded-xl px-4 py-3 w-full hover:bg-gray-100 transition no-underline">
                                <img src="{{ asset('assets/icons/pdf-icon.png') }}" class="w-8 h-8 shrink-0">
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-medium text-black truncate">Sertifikat Kost</p>
                                    <p class="text-[10px] text-gray-500 mt-0.5">Klik untuk membuka</p>
                                </div>
                            </a>
                        </template>
                        <template x-if="!selectedPengelola.sertifikat">
                            <p class="text-xs text-gray-400 italic">Tidak ada sertifikat</p>
                        </template>
                    </div>

                </div>

                <div class="flex gap-3 mt-6">
                    <x-form.button
                        type="button"
                        class="w-full text-white !bg-red-600 hover:!bg-red-100 hover:!text-red-600"
                        @click="modalType = 'confirm-tolak'">
                        Tolak
                    </x-form.button>
                    <x-form.button
                        type="button"
                        class="w-full text-white !bg-green-600 hover:!bg-green-100 hover:!text-green-600"
                        @click="modalType = 'confirm-setujui'">
                        Setujui
                    </x-form.button>
                </div>

            </div>
        </template>


        {{-- ================= DETAIL DITOLAK ================= --}}
        <template x-if="modalType === 'detail-dibatasi'">
            <div class="relative">

                <button type="button" class="absolute top-0 right-0 text-xl" @click="closeModal()">✕</button>

                {{-- HEADER --}}
                <div class="flex items-center justify-between mb-6 pr-6">
                    <h2 class="text-xl font-bold">Detail Penilaian Penghuni</h2>
                </div>

                <div class="space-y-4 lg:max-h-[450px] max-h-[300px] overflow-y-auto pr-1">

                    {{-- SECTION 1 --}}
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <p class="text-xs text-neutral mb-1">Nama Lengkap</p>
                            <p class="text-xs font-medium" x-text="selectedPengelola.name"></p>
                        </div>
                        <div>
                            <p class="text-xs text-neutral mb-1">Nomor HP</p>
                            <p class="text-xs font-medium" x-text="selectedPengelola.no_hp"></p>
                        </div>
                        <div>
                            <p class="text-xs text-neutral mb-1">Email</p>
                            <p class="text-xs font-medium" x-text="selectedPengelola.email"></p>
                        </div>
                    </div>

                    <hr>

                    {{-- SECTION 2 --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-neutral mb-1">Nama Kost</p>
                            <p class="text-xs font-medium" x-text="selectedPengelola.nama_kost"></p>
                        </div>
                        <div>
                            <p class="text-xs text-neutral mb-1">Pemilik Kost</p>
                            <p class="text-xs font-medium" x-text="selectedPengelola.pemilik_kost"></p>
                        </div>
                    </div>
                    <hr>

                    {{-- SECTION 3 --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-neutral mb-1">Alamat</p>
                            <p class="text-xs font-medium" x-text="selectedPengelola.alamat"></p>
                        </div>
                        <div>
                            <p class="text-xs text-neutral mb-1">Tanggal Penilaian</p>
                            <p class="text-xs font-medium" x-text="selectedPengelola.tanggal_daftar"></p>
                        </div>
                    </div>
                    <hr>

                    {{-- SECTION 4 --}}
                    <x-form.input label="Ketertiban Pembayaran" name="ketertiban-pembayaran" value="Baik" class="!bg-[#F8F8F8] text-xs" disabled />
                    <x-form.input label="Sikap" name="sikap" value="Baik" class="!bg-[#F8F8F8] text-xs" disabled />
                    <x-form.input label="Perawatan Fasilitas" name="perawatan-fasilitas" value="Baik" class="!bg-[#F8F8F8] text-xs" disabled />
                    <x-form.input label="Catatan Tambahan" name="catatan-tambahan" value="Anaknya baik tidak pernah telat membayar." class="!bg-[#F8F8F8] text-xs" disabled />
                    {{-- SERTIFIKAT --}}
                    <div>
                        <p class="text-xs text-neutral mb-2">Sertifikat</p>
                        <template x-if="selectedPengelola.sertifikat">
                            <a :href="selectedPengelola.sertifikat" target="_blank"
                                class="flex items-center gap-3 bg-[#F8F8F8] rounded-xl px-4 py-3 w-full hover:bg-gray-100 transition no-underline">
                                <img src="{{ asset('assets/icons/pdf-icon.png') }}" class="w-8 h-8 shrink-0">
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-medium text-black truncate">Sertifikat Kost</p>
                                    <p class="text-[10px] text-gray-500 mt-0.5">Klik untuk membuka</p>
                                </div>
                            </a>
                        </template>
                        <template x-if="!selectedPengelola.sertifikat">
                            <p class="text-xs text-gray-400 italic">Tidak ada sertifikat</p>
                        </template>
                    </div>

                </div>

                <div class="mt-6">
                    <x-form.button
                        type="button"
                        class="w-full !text-neutral !bg-[#E2E2E2]"
                        disabled>
                        Ditolak
                    </x-form.button>
                </div>

            </div>
        </template>


        {{-- ================= CONFIRM TOLAK ================= --}}
        <template x-if="modalType === 'confirm-tolak'">
            <div class="relative">

                <button type="button" class="absolute top-0 right-0 text-xl" @click="closeModal()">✕</button>

                <h2 class="text-xl font-bold mb-4">Konfirmasi Tolak Penilaian Penghuni</h2>

                <p class="text-xs text-neutral">Apakah Anda yakin ingin menolak penilaian penghuni ini? Tindakan ini tidak dapat dibatalkan.</p>

                <div class="flex gap-3 mt-8">
                    <x-form.button
                        type="button"
                        class="w-full !text-neutral !bg-transparent border-2 !border-neutral hover:!bg-neutral hover:!text-white"
                        @click="modalType = 'detail-menunggu'">
                        Batal
                    </x-form.button>
                    <x-form.button
                        type="button"
                        class="w-full !text-white !bg-red-600 hover:!bg-red-100 hover:!text-red-600"
                        @click="$refs.formTolak.submit()">
                        Tolak
                    </x-form.button>
                </div>

                <form x-ref="formTolak" :action="'/superadmin/manajemen-pengelola/tolak/' + selectedPengelola.id" method="POST" class="hidden">
                    @csrf
                </form>

            </div>
        </template>


        {{-- ================= CONFIRM SETUJUI ================= --}}
        <template x-if="modalType === 'confirm-setujui'">
            <div class="relative">

                <button type="button" class="absolute top-0 right-0 text-xl" @click="closeModal()">✕</button>

                <h2 class="text-xl font-bold mb-4">Konfirmasi Setujui</h2>

                <p class="text-xs text-neutral">Apakah Anda yakin ingin menyetujui pendaftaran pengelola ini? Akun akan segera aktif.</p>

                <div class="flex gap-3 mt-8">
                    <x-form.button
                        type="button"
                        class="w-full !text-neutral !bg-transparent border-2 !border-neutral hover:!bg-neutral hover:!text-white"
                        @click="modalType = 'detail-menunggu'">
                        Batal
                    </x-form.button>
                    <x-form.button
                        type="button"
                        class="w-full !text-white !bg-green-600 hover:!bg-green-100 hover:!text-green-600"
                        @click="$refs.formSetujui.submit()">
                        Setujui
                    </x-form.button>
                </div>

                <form x-ref="formSetujui" :action="'/superadmin/manajemen-pengelola/setujui/' + selectedPengelola.id" method="POST" class="hidden">
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