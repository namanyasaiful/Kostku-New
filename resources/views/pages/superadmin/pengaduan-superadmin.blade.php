@extends('layouts.superadmin')
@section('title', 'Pengaduan - Super Admin')

@section('content')

{{-- DATA DUMMY --}}
@php
$dummyData = [
    [
        'id' => 1,
        'nama_lengkap' => 'Budi Santoso',
        'email' => 'budi@gmail.com',
        'nama_kost' => 'Kost Melati',
        'nomor_kamar' => 'KM001',
        'judul' => 'WiFi ngelag terus',
        'isi' => 'Sudah 3 hari ini wifi di lantai 2 sangat lambat, tidak bisa digunakan untuk kerja.',
        'balasan' => 'Sudah kami teruskan ke teknisi, akan diperbaiki besok.',
        'status' => 'proses',
        'tanggal_pengaduan' => '01/06/2026',
        'bukti' => 'https://placehold.co/400x300.jpg',
    ],
    [
        'id' => 2,
        'nama_lengkap' => 'Siti Rahayu',
        'email' => 'siti@gmail.com',
        'nama_kost' => 'Kost Mawar',
        'nomor_kamar' => 'KM005',
        'judul' => 'Kamar mandi bocor',
        'isi' => 'Ada kebocoran di langit-langit kamar mandi, air menetes saat hujan deras.',
        'balasan' => '',
        'status' => 'baru',
        'tanggal_pengaduan' => '02/06/2026',
        'bukti' => 'https://placehold.co/400x300.jpg',
    ],
    [
        'id' => 3,
        'nama_lengkap' => 'Ahmad Fauzi',
        'email' => 'ahmad@gmail.com',
        'nama_kost' => 'Kost Anggrek',
        'nomor_kamar' => 'KM003',
        'judul' => 'AC tidak dingin',
        'isi' => 'AC di kamar sudah seminggu tidak dingin, sudah dicoba restart tapi tetap sama.',
        'balasan' => 'Sudah selesai diperbaiki oleh teknisi.',
        'status' => 'selesai',
        'tanggal_pengaduan' => '03/06/2026',
        'bukti' => '',
    ],
    [
        'id' => 4,
        'nama_lengkap' => 'Dewi Lestari',
        'email' => 'dewi@gmail.com',
        'nama_kost' => 'Kost Kenanga',
        'nomor_kamar' => 'KM002',
        'judul' => 'Kunci kamar rusak',
        'isi' => 'Kunci kamar saya susah dibuka, harus diputar berkali-kali baru bisa.',
        'balasan' => '',
        'status' => 'baru',
        'tanggal_pengaduan' => '04/06/2026',
        'bukti' => 'https://placehold.co/400x300.jpg',
    ],
    [
        'id' => 5,
        'nama_lengkap' => 'Reza Firmansyah',
        'email' => 'reza@gmail.com',
        'nama_kost' => 'Kost Cempaka',
        'nomor_kamar' => 'KM007',
        'judul' => 'Lampu teras mati',
        'isi' => 'Lampu teras depan kamar saya sudah mati sejak 4 hari lalu, jadi gelap saat malam.',
        'balasan' => 'Sedang menunggu pengiriman bohlam baru.',
        'status' => 'proses',
        'tanggal_pengaduan' => '05/06/2026',
        'bukti' => '',
    ],
];
@endphp

<div x-data="{
    modalOpen: false,
    modalType: null,

    id: null,
    namaLengkap: '',
    email: '',
    namaKost: '',
    nomorKamar: '',
    judul: '',
    isi: '',
    balasan: '',
    status: '',
    tanggalPengaduan: '',
    bukti: '',

    openModal(type, data = {}) {
        if (data) {
            this.id = data.id || null;
            this.namaLengkap = data.nama_lengkap || '';
            this.email = data.email || '';
            this.namaKost = data.nama_kost || '';
            this.nomorKamar = data.nomor_kamar || '';
            this.judul = data.judul || '';
            this.isi = data.isi || '';
            this.balasan = data.balasan || '';
            this.status = data.status || '';
            this.tanggalPengaduan = data.tanggal_pengaduan || '';
            this.bukti = data.bukti || '';
        }
        this.modalOpen = true;
        this.modalType = type;
    },

    formatStatus(status) {
        if (status === 'baru') return 'Baru';
        if (status === 'proses') return 'Diproses';
        if (status === 'selesai') return 'Selesai';
        return status;
    }
}">

    {{-- ================= PAGE HEADER ================= --}}
    <x-page-header
        title="Pengaduan"
        description="Lihat semua riwayat pengaduan penghuni">
    </x-page-header>

    {{-- ================= TABLE ================= --}}
    <div class="bg-white rounded-lg p-4 lg:p-6 mt-4 mb-6">
        <div class="overflow-x-auto">
            <x-table.index>
                <thead class="sticky top-0 bg-white z-10 border-b border-default">
                    <x-table.tr>
                        <x-table.th>Nama Lengkap</x-table.th>
                        <x-table.th>Nama Kost</x-table.th>
                        <x-table.th>Judul Pengaduan</x-table.th>
                        <x-table.th>Tanggal Pengaduan</x-table.th>
                        <x-table.th>Status</x-table.th>
                        <x-table.th class="text-center">Aksi</x-table.th>
                    </x-table.tr>
                </thead>
                <tbody>
                    @foreach ($dummyData as $pengaduan)
                    <x-table.tr>
                        <x-table.td class="font-medium text-heading">{{ $pengaduan['nama_lengkap'] }}</x-table.td>
                        <x-table.td>{{ $pengaduan['nama_kost'] }}</x-table.td>
                        <x-table.td>{{ Str::limit($pengaduan['judul'], 40) }}</x-table.td>
                        <x-table.td>{{ $pengaduan['tanggal_pengaduan'] }}</x-table.td>
                        <x-table.td>
                            @if ($pengaduan['status'] === 'baru')
                                <x-badge type="info">Baru</x-badge>
                            @elseif ($pengaduan['status'] === 'proses')
                                <x-badge type="warning">Diproses</x-badge>
                            @elseif ($pengaduan['status'] === 'selesai')
                                <x-badge type="success">Selesai</x-badge>
                            @else
                                <x-badge type="secondary">{{ $pengaduan['status'] }}</x-badge>
                            @endif
                        </x-table.td>
                        <x-table.td class="text-center">
                            <x-form.button
                                type="button"
                                @click="openModal('detail-pengaduan', {
                                    id: '{{ $pengaduan['id'] }}',
                                    nama_lengkap: '{{ $pengaduan['nama_lengkap'] }}',
                                    email: '{{ $pengaduan['email'] }}',
                                    nama_kost: '{{ $pengaduan['nama_kost'] }}',
                                    nomor_kamar: '{{ $pengaduan['nomor_kamar'] }}',
                                    judul: '{{ $pengaduan['judul'] }}',
                                    isi: '{{ $pengaduan['isi'] }}',
                                    balasan: '{{ $pengaduan['balasan'] }}',
                                    status: '{{ $pengaduan['status'] }}',
                                    tanggal_pengaduan: '{{ $pengaduan['tanggal_pengaduan'] }}',
                                    bukti: '{{ $pengaduan['bukti'] }}'
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
    <x-modal show="modalOpen" maxWidth="lg:max-w-[450px] max-w-[350px]">

        {{-- DETAIL PENGADUAN --}}
        <template x-if="modalType === 'detail-pengaduan'">
            <div class="lg:!max-w-[450px] !max-w-[350px] -mx-8 -my-16 p-8 bg-[#f5f6fa] rounded-md">
                <div class="relative">
                    <button
                        type="button"
                        class="absolute top-0 right-0 text-neutral hover:text-black text-xl font-bold"
                        @click="modalOpen = false; modalType = null;">
                        ✕
                    </button>
                    <h2 class="text-xl font-bold mb-8">Detail Pengaduan</h2>
                    <div class="flex flex-col gap-4">

                        {{-- Info Penghuni & Pengaduan --}}
                        <div class="bg-white rounded-md shadow-md lg:px-4 px-2 lg:py-5 py-3">
                            <div class="grid grid-cols-2 gap-4 items-start">
                                <div>
                                    <label class="lg:text-xs text-[10px] text-neutral">Nama</label>
                                    <p class="lg:text-md text-[12px] font-medium" x-text="namaLengkap"></p>
                                </div>
                                <div>
                                    <label class="lg:text-xs text-[10px] text-neutral">Email</label>
                                    <p class="lg:text-md text-[12px] font-medium" x-text="email"></p>
                                </div>
                            </div>
                            <hr class="my-4">
                            <div class="grid grid-cols-2 gap-4 items-start">
                                <div>
                                    <label class="lg:text-xs text-[10px] text-neutral">Nama Kost</label>
                                    <p class="lg:text-md text-[12px] font-medium" x-text="namaKost"></p>
                                </div>
                                <div>
                                    <label class="lg:text-xs text-[10px] text-neutral">Tanggal Pengaduan</label>
                                    <p class="lg:text-md text-[12px] font-medium" x-text="tanggalPengaduan"></p>
                                </div>
                            </div>
                            <hr class="my-4">
                            <div class="flex justify-between h-32">
                                <div class="w-60 h-full overflow-auto">
                                    <p class="lg:text-md text-xs text-black font-medium mb-1" x-text="judul"></p>
                                    <p class="text-xs text-neutral mb-3" x-text="isi"></p>
                                </div>
                                <div x-show="bukti" class="lg:w-28 w-32 lg:pl-3 pl-2">
                                    <label class="lg:text-xs text-[10px] text-black font-medium mb-1 block">Bukti</label>
                                    <template x-if="bukti && (bukti.endsWith('.jpg') || bukti.endsWith('.jpeg') || bukti.endsWith('.png'))">
                                        <a :href="bukti" target="_blank">
                                            <img
                                                :src="bukti"
                                                class="lg:w-24 w-20 lg:h-24 h-20 object-cover rounded-md border border-gray-200 hover:opacity-80 transition">
                                        </a>
                                    </template>
                                    <template x-if="bukti && bukti.endsWith('.pdf')">
                                        <a
                                            :href="bukti"
                                            target="_blank"
                                            class="flex items-center gap-2 text-xs text-primary underline hover:opacity-80 transition">
                                            <img src="{{ asset('assets/icons/pdf-icon.png') }}" class="lg:w-10 w-8 lg:h-10 h-8">
                                            Lihat PDF
                                        </a>
                                    </template>
                                </div>
                            </div>
                        </div>

                        {{-- Respon Pengelola --}}
                        <div class="bg-white rounded-md shadow-md lg:px-4 px-2 lg:py-5 py-3">
                            <label class="block text-sm font-medium text-black mb-2">Respon Pengelola</label>
                            <div class="w-full rounded-xl bg-[#F8F8F8] border-2 border-[#E2E2E2] px-4 py-3">
                                <p class="text-xs text-black mb-1" x-text="balasan || 'Belum ada balasan'"></p>
                            </div>
                        </div>

                        {{-- Status --}}
                        <div class="bg-white rounded-md shadow-md lg:px-4 px-2 lg:py-5 py-3">
                            <x-form.input
                                label="Status"
                                name="status-pengaduan"
                                class="bg-[#F8F8F8] border-[#E2E2E2] text-xs"
                                x-bind:value="formatStatus(status)"
                                placeholder="Status"
                                disabled />
                        </div>

                    </div>
                </div>
            </div>
        </template>

    </x-modal>

</div>

@endsection