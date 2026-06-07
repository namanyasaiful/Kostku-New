@extends('layouts.superadmin')
@section('title', 'Manajemen Pengelola')

@section('content')


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
        title="Manajemen Pengelola"
        description="Daftar pengelola dan kelola permintaan akun">
    </x-page-header>

    {{-- ================= SEARCH ================= --}}
    <form method="GET" action="{{ route('manajemen-pengelola.superadmin') }}">
        <x-search-input
            name="search"
            placeholder="Cari"
            value="{{ $search ?? '' }}" />
    </form>

    {{-- ================= TABLE ================= --}}
    <div class="bg-white rounded-lg p-4 lg:p-6 mt-4 mb-6">

        {{-- ================= TAB ================= --}}
        <div class="flex lg:gap-6 gap-3 mb-6 min-w-max border-b">

            <button
                @click="activeTab = 'semua'"
                :class="activeTab === 'semua' ? 'border-primary text-primary font-bold' : 'border-transparent text-black font-medium break-all'"
                class="pb-3 border-b-2 text-xs lg:text-sm transition">
                Semua
            </button>

            <button
                @click="activeTab = 'aktif'"
                :class="activeTab === 'aktif' ? 'border-primary text-primary font-bold' : 'border-transparent text-black font-medium break-all'"
                class="pb-3 border-b-2 text-xs lg:text-sm transition">
                Aktif
            </button>

            <button
                @click="activeTab = 'menunggu'"
                :class="activeTab === 'menunggu' ? 'border-primary text-primary font-bold' : 'border-transparent text-black font-medium break-all'"
                class="pb-3 border-b-2 text-xs lg:text-sm transition">
                Menunggu
            </button>

            <button
                @click="activeTab = 'dibatasi'"
                :class="activeTab === 'dibatasi' ? 'border-primary text-primary font-bold' : 'border-transparent text-black font-medium break-all'"
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
                            <x-table.th>Nama Kost</x-table.th>
                            <x-table.th>Kode Kost</x-table.th>
                            <x-table.th>Status Akun</x-table.th>
                            <x-table.th class="text-center">Aksi</x-table.th>
                        </x-table.tr>
                    </thead>
                    <tbody>
                        @foreach($semuaPengelola as $pengelola)
                        <x-table.tr>
                            <x-table.td class="font-medium break-all">{{ $pengelola->nama }}</x-table.td>
                            <x-table.td>{{ $pengelola->email }}</x-table.td>
                            <x-table.td>{{ $pengelola->kosts->nama_kost ?? '-' }}</x-table.td>
                            <x-table.td>{{ $pengelola->kosts->kode_kost ?? '-' }}</x-table.td>
                            <x-table.td>
                                @if($pengelola->status === 'Aktif')
                                    <x-badge type="success">Aktif</x-badge>
                                @elseif($pengelola->status === 'Menunggu')
                                    <x-badge type="warning">Menunggu</x-badge>
                                @elseif($pengelola->status === 'Dibatasi')
                                    <x-badge type="danger">Dibatasi</x-badge>
                                @endif
                            </x-table.td>
                            <x-table.td class="text-center">
                                <x-form.button
                                    @click.prevent="openModal('detail-{{ $pengelola->status === 'Aktif' ? 'aktif' : ($pengelola->status === 'Menunggu' ? 'menunggu' : 'dibatasi') }}', {
                                        id: {{ $pengelola['id'] }},
                                        nama: '{{ $pengelola->nama }}',
                                        no_hp: '{{ $pengelola->telpon }}',
                                        email: '{{ $pengelola->email }}',
                                        nama_kost: '{{ $pengelola->kosts->nama_kost ?? '-' }}',
                                        kode_kost: '{{ $pengelola->kode_kost ?? '-' }}',
                                        alamat: '{{ $pengelola->alamat }}',
                                        tanggal_daftar: '{{ $pengelola->created_at->format('d/m/Y') }}',
                                        sertifikat: '{{ $pengelola->kosts && $pengelola->kosts->sertifikat ? asset('storage/'.$pengelola->kosts->sertifikat) : '' }}'                                       })"
                                    class="border border-primary bg-transparent !text-primary hover:bg-secondary hover:border-secondary">
                                    Detail
                                </x-form.button>
                            </x-table.td>
                        </x-table.tr>
                        @endforeach
                    </tbody>
                </x-table.index>
            </div>
            <p class="text-xs text-neutral mt-3">Menampilkan {{ $semuaPengelola->count() }} data</p>
        </div>

        {{-- ================= AKTIF ================= --}}
        <div x-show="activeTab === 'aktif'" x-transition>
            <div class="overflow-x-auto">
                <x-table.index>
                    <thead class="sticky top-0 bg-white z-10 border-b border-default">
                        <x-table.tr>
                            <x-table.th>Nama Lengkap</x-table.th>
                            <x-table.th>Email</x-table.th>
                            <x-table.th>Nama Kost</x-table.th>
                            <x-table.th>Kode Kost</x-table.th>
                            <x-table.th>Status Akun</x-table.th>
                            <x-table.th class="text-center">Aksi</x-table.th>
                        </x-table.tr>
                    </thead>
                    <tbody>
                        @foreach($aktifPengelola as $pengelola)
                        <x-table.tr>
                            <x-table.td class="font-medium break-all">{{ $pengelola->nama }}</x-table.td>
                            <x-table.td>{{ $pengelola->email }}</x-table.td>
                            <x-table.td>{{ $pengelola->kosts->nama_kost ?? '-' }}</x-table.td>
                            <x-table.td>{{ $pengelola->kosts->kode_kost ?? '-' }}</x-table.td>
                            <x-table.td>
                                <x-badge type="success">Aktif</x-badge>
                            </x-table.td>
                            <x-table.td class="text-center">
                                <x-form.button
                                    @click.prevent="openModal('detail-aktif', {
                                        id: {{ $pengelola['id'] }},
                                        nama: '{{ $pengelola->nama }}',
                                        no_hp: '{{ $pengelola->telpon }}',
                                        email: '{{ $pengelola->email }}',
                                        nama_kost: '{{ $pengelola->kosts->nama_kost ?? '-' }}',
                                        kode_kost: '{{ $pengelola->kosts->kode_kost ?? '-' }}',
                                        alamat: '{{ $pengelola->alamat }}',
                                        tanggal_daftar: '{{ $pengelola->created_at->format('d/m/Y') }}',
                                        sertifikat: '{{ $pengelola->kosts && $pengelola->kosts->sertifikat ? asset('storage/'.$pengelola->kosts->sertifikat) : '' }}'                                       })"
                                    class="border border-primary bg-transparent !text-primary hover:bg-secondary hover:border-secondary">
                                    Detail
                                </x-form.button>
                            </x-table.td>
                        </x-table.tr>
                        @endforeach
                    </tbody>
                </x-table.index>
            </div>
            <p class="text-xs text-neutral mt-3">Menampilkan {{ $aktifPengelola->count() }} data</p>
        </div>

        {{-- ================= MENUNGGU ================= --}}
        <div x-show="activeTab === 'menunggu'" x-transition>
            <div class="overflow-x-auto">
                <x-table.index>
                    <thead class="sticky top-0 bg-white z-10 border-b border-default">
                        <x-table.tr>
                            <x-table.th>Nama Lengkap</x-table.th>
                            <x-table.th>Email</x-table.th>
                            <x-table.th>Nama Kost</x-table.th>
                            <x-table.th>Kode Kost</x-table.th>
                            <x-table.th>Status Akun</x-table.th>
                            <x-table.th class="text-center">Aksi</x-table.th>
                        </x-table.tr>
                    </thead>
                    <tbody>
                        @foreach($menungguPengelola as $pengelola)
                        <x-table.tr>
                            <x-table.td class="font-medium break-all">{{ $pengelola->nama }}</x-table.td>
                            <x-table.td>{{ $pengelola->email }}</x-table.td>
                            <x-table.td>{{ $pengelola->kosts->nama_kost ?? '-' }}</x-table.td>
                            <x-table.td>{{ $pengelola->kosts->kode_kost ?? '-' }}</x-table.td>
                            <x-table.td>
                                <x-badge type="warning">Menunggu</x-badge>
                            </x-table.td>
                            <x-table.td class="text-center">
                                <x-form.button
                                    @click.prevent="openModal('detail-menunggu', {
                                        id: {{ $pengelola['id'] }},
                                        nama: '{{ $pengelola->nama }}',
                                        no_hp: '{{ $pengelola->telpon }}',
                                        email: '{{ $pengelola->email }}',
                                        nama_kost: '{{ $pengelola->kosts->nama_kost ?? '-' }}',
                                        kode_kost: '{{ $pengelola->kosts->kode_kost ?? '-' }}',
                                        alamat: '{{ $pengelola->alamat }}',
                                        tanggal_daftar: '{{ $pengelola->created_at->format('d/m/Y') }}',
                                        sertifikat: '{{ $pengelola->kosts && $pengelola->kosts->sertifikat ? asset('storage/'.$pengelola->kosts->sertifikat) : '' }}'                                       })"
                                    class="border border-primary bg-transparent !text-primary hover:bg-secondary hover:border-secondary">
                                    Detail
                                </x-form.button>
                            </x-table.td>
                        </x-table.tr>
                        @endforeach
                    </tbody>
                </x-table.index>
            </div>
            <p class="text-xs text-neutral mt-3">Menampilkan {{ $menungguPengelola->count() }} data</p>
        </div>

        {{-- ================= DIBATASI ================= --}}
        <div x-show="activeTab === 'dibatasi'" x-transition>
            <div class="overflow-x-auto">
                <x-table.index>
                    <thead class="sticky top-0 bg-white z-10 border-b border-default">
                        <x-table.tr>
                            <x-table.th>Nama Lengkap</x-table.th>
                            <x-table.th>Email</x-table.th>
                            <x-table.th>Nama Kost</x-table.th>
                            <x-table.th>Kode Kost</x-table.th>
                            <x-table.th>Status Akun</x-table.th>
                            <x-table.th class="text-center">Aksi</x-table.th>
                        </x-table.tr>
                    </thead>
                    <tbody>
                        @foreach($dibatasiPengelola as $pengelola)
                        <x-table.tr>
                            <x-table.td class="font-medium break-all">{{ $pengelola->nama }}</x-table.td>
                            <x-table.td>{{ $pengelola->email }}</x-table.td>
                            <x-table.td>{{ $pengelola->kosts->nama_kost ?? '-' }}</x-table.td>
                            <x-table.td>{{ $pengelola->kosts->kode_kost ?? '-' }}</x-table.td>
                            <x-table.td>
                                <x-badge type="danger">Dibatasi</x-badge>
                            </x-table.td>
                            <x-table.td class="text-center">
                                <x-form.button
                                    @click.prevent="openModal('detail-dibatasi', {
                                        id: {{ $pengelola['id'] }},
                                        nama: '{{ $pengelola->nama }}',
                                        no_hp: '{{ $pengelola->telpon }}',
                                        email: '{{ $pengelola->email }}',
                                        nama_kost: '{{ $pengelola->kosts->nama_kost ?? '-' }}',
                                        kode_kost: '{{ $pengelola->kosts->kode_kost ?? '-' }}',
                                        alamat: '{{ $pengelola->alamat }}',
                                        tanggal_daftar: '{{ $pengelola->created_at->format('d/m/Y') }}',
                                        sertifikat: '{{ $pengelola->kosts && $pengelola->kosts->sertifikat ? asset('storage/'.$pengelola->kosts->sertifikat) : '' }}'                                       })"
                                    class="border border-primary bg-transparent !text-primary hover:bg-secondary hover:border-secondary">
                                    Detail
                                </x-form.button>
                            </x-table.td>
                        </x-table.tr>
                        @endforeach
                    </tbody>
                </x-table.index>
            </div>
            <p class="text-xs text-neutral mt-3">Menampilkan {{ $dibatasiPengelola->count() }} data</p>
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
                    <h2 class="text-xl font-bold">Detail Pengelola</h2>
                    <x-badge type="success" class="!px-3 !py-1 !text-xs">Aktif</x-badge>
                </div>

                <div class="space-y-4 lg:max-h-[450px] max-h-[300px] overflow-y-auto pr-1">

                    {{-- SECTION 1 --}}
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <p class="text-xs text-neutral mb-1">Nama Lengkap</p>
                            <p class="text-xs font-medium break-all" x-text="selectedPengelola.nama"></p>
                        </div>
                        <div>
                            <p class="text-xs text-neutral mb-1">Nomor Telepon</p>
                            <p class="text-xs font-medium break-all" x-text="selectedPengelola.no_hp"></p>
                        </div>
                        <div>
                            <p class="text-xs text-neutral mb-1">Email</p>
                            <p class="text-xs font-medium break-all" x-text="selectedPengelola.email"></p>
                        </div>
                    </div>
                    
                    <hr>

                    {{-- SECTION 2 --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-neutral mb-1">Nama Kost</p>
                            <p class="text-xs font-medium break-all" x-text="selectedPengelola.nama_kost"></p>
                        </div>
                        <div>
                            <p class="text-xs text-neutral mb-1">Kode Kost</p>
                            <p class="text-xs font-medium break-all" x-text="selectedPengelola.kode_kost"></p>
                        </div>
                    </div>
                    <hr>

                    {{-- SECTION 3 --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-neutral mb-1">Alamat</p>
                            <p class="text-xs font-medium break-all" x-text="selectedPengelola.alamat"></p>
                        </div>
                        <div>
                            <p class="text-xs text-neutral mb-1">Tanggal Daftar</p>
                            <p class="text-xs font-medium break-all" x-text="selectedPengelola.tanggal_daftar"></p>
                        </div>
                    </div>

                    {{-- SERTIFIKAT --}}
                    <div>
                        <p class="text-xs text-neutral mb-2">Sertifikat</p>
                        <template x-if="selectedPengelola.sertifikat">
                            <a :href="selectedPengelola.sertifikat" target="_blank"
                                class="flex items-center gap-3 bg-[#F8F8F8] rounded-xl px-4 py-3 w-full hover:bg-gray-100 transition no-underline">
                                <img src="{{ asset('assets/icons/pdf-icon.png') }}" class="w-8 h-8 shrink-0">
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-medium break-all text-black truncate">Sertifikat Kost</p>
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
                        class="w-full text-white !bg-red-600 hover:!bg-red-100 hover:!text-red-600"
                        @click="modalType = 'confirm-batasi'">
                        Batasi Akun
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
                    <h2 class="text-xl font-bold">Detail Pengelola</h2>
                    <x-badge type="warning" class="!px-3 !py-1 !text-xs">Menunggu</x-badge>
                </div>

                <div class="space-y-4 lg:max-h-[400px] max-h-[260px] overflow-y-auto pr-1">

                    {{-- SECTION 1 --}}
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <p class="text-xs text-neutral mb-1">Nama Lengkap</p>
                            <p class="text-xs font-medium break-all" x-text="selectedPengelola.nama"></p>
                        </div>
                        <div>
                            <p class="text-xs text-neutral mb-1">Nomor Telepon</p>
                            <p class="text-xs font-medium break-all" x-text="selectedPengelola.no_hp"></p>
                        </div>
                        <div>
                            <p class="text-xs text-neutral mb-1">Email</p>
                            <p class="text-xs font-medium break-all" x-text="selectedPengelola.email"></p>
                        </div>
                    </div>                  
                    <hr>

                    {{-- SECTION 2 --}}
                    <div>
                        <p class="text-xs text-neutral mb-1">Nama Kost</p>
                        <p class="text-xs font-medium break-all" x-text="selectedPengelola.nama_kost"></p>
                    </div>
                    <hr>

                    {{-- SECTION 3 --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-neutral mb-1">Alamat</p>
                            <p class="text-xs font-medium break-all" x-text="selectedPengelola.alamat"></p>
                        </div>
                        <div>
                            <p class="text-xs text-neutral mb-1">Tanggal Daftar</p>
                            <p class="text-xs font-medium break-all" x-text="selectedPengelola.tanggal_daftar"></p>
                        </div>
                    </div>

                    {{-- SERTIFIKAT --}}
                    <div>
                        <p class="text-xs text-neutral mb-2">Sertifikat</p>
                        <template x-if="selectedPengelola.sertifikat">
                            <a :href="selectedPengelola.sertifikat" target="_blank"
                                class="flex items-center gap-3 bg-[#F8F8F8] rounded-xl px-4 py-3 w-full hover:bg-gray-100 transition no-underline">
                                <img src="{{ asset('assets/icons/pdf-icon.png') }}" class="w-8 h-8 shrink-0">
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-medium break-all text-black truncate">Sertifikat Kost</p>
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


        {{-- ================= DETAIL DIBATASI ================= --}}
        <template x-if="modalType === 'detail-dibatasi'">
            <div class="relative">

                <button type="button" class="absolute top-0 right-0 text-xl" @click="closeModal()">✕</button>

                {{-- HEADER --}}
                <div class="flex items-center justify-between mb-6 pr-6">
                    <h2 class="text-xl font-bold">Detail Pengelola</h2>
                    <x-badge type="danger" class="!px-3 !py-1 !text-xs">Dibatasi</x-badge>
                </div>

                <div class="space-y-4 lg:max-h-[450px] max-h-[300px] overflow-y-auto pr-1">

                    {{-- SECTION 1 --}}
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <p class="text-xs text-neutral mb-1">Nama Lengkap</p>
                            <p class="text-xs font-medium break-all" x-text="selectedPengelola.nama"></p>
                        </div>
                        <div>
                            <p class="text-xs text-neutral mb-1">Nomor Telepon</p>
                            <p class="text-xs font-medium break-all" x-text="selectedPengelola.no_hp"></p>
                        </div>
                        <div>
                            <p class="text-xs text-neutral mb-1">Email</p>
                            <p class="text-xs font-medium break-all" x-text="selectedPengelola.email"></p>
                        </div>
                    </div>                    
                    <hr>

                    {{-- SECTION 2 --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-neutral mb-1">Nama Kost</p>
                            <p class="text-xs font-medium break-all" x-text="selectedPengelola.nama_kost"></p>
                        </div>
                        <div>
                            <p class="text-xs text-neutral mb-1">Kode Kost</p>
                            <p class="text-xs font-medium break-all" x-text="selectedPengelola.kode_kost"></p>
                        </div>
                    </div>
                    <hr>

                    {{-- SECTION 3 --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-neutral mb-1">Alamat</p>
                            <p class="text-xs font-medium break-all" x-text="selectedPengelola.alamat"></p>
                        </div>
                        <div>
                            <p class="text-xs text-neutral mb-1">Tanggal Daftar</p>
                            <p class="text-xs font-medium break-all" x-text="selectedPengelola.tanggal_daftar"></p>
                        </div>
                    </div>

                    {{-- SERTIFIKAT --}}
                    <div>
                        <p class="text-xs text-neutral mb-2">Sertifikat</p>
                        <template x-if="selectedPengelola.sertifikat">
                            <a :href="selectedPengelola.sertifikat" target="_blank"
                                class="flex items-center gap-3 bg-[#F8F8F8] rounded-xl px-4 py-3 w-full hover:bg-gray-100 transition no-underline">
                                <img src="{{ asset('assets/icons/pdf-icon.png') }}" class="w-8 h-8 shrink-0">
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-medium break-all text-black truncate">Sertifikat Kost</p>
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
                        class="w-full text-white !bg-green-600 hover:!bg-green-100 hover:!text-green-600"
                        @click="modalType = 'confirm-aktifkan'">
                        Aktifkan Akun
                    </x-form.button>
                </div>

            </div>
        </template>


        {{-- ================= CONFIRM TOLAK ================= --}}
        <template x-if="modalType === 'confirm-tolak'">
            <div class="relative">

                <button type="button" class="absolute top-0 right-0 text-xl" @click="closeModal()">✕</button>

                <h2 class="text-xl font-bold mb-4">Konfirmasi Tolak</h2>

                <p class="text-xs text-neutral">Apakah Anda yakin ingin menolak pendaftaran pengelola ini? Tindakan ini tidak dapat dibatalkan.</p>

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


        {{-- ================= CONFIRM BATASI ================= --}}
        <template x-if="modalType === 'confirm-batasi'">
            <div class="relative">

                <button type="button" class="absolute top-0 right-0 text-xl" @click="closeModal()">✕</button>

                <h2 class="text-xl font-bold mb-4">Konfirmasi Batasi Akun</h2>

                <p class="text-xs text-neutral">Apakah Anda yakin ingin membatasi akun pengelola ini? Pengelola tidak akan bisa mengakses Kostku.</p>

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

                <form x-ref="formBatasi" :action="'/superadmin/manajemen-pengelola/batasi/' + selectedPengelola.id" method="POST" class="hidden">
                    @csrf
                </form>

            </div>
        </template>


        {{-- ================= CONFIRM AKTIFKAN ================= --}}
        <template x-if="modalType === 'confirm-aktifkan'">
            <div class="relative">

                <button type="button" class="absolute top-0 right-0 text-xl" @click="closeModal()">✕</button>

                <h2 class="text-xl font-bold mb-4">Konfirmasi Aktifkan Akun</h2>

                <p class="text-xs text-neutral">Apakah Anda yakin ingin mengaktifkan kembali akun pengelola ini?</p>

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

                <form x-ref="formAktifkan" :action="'/superadmin/manajemen-pengelola/aktifkan/' + selectedPengelola.id" method="POST" class="hidden">
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