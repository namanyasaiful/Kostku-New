@extends('layouts.superadmin')
@section('title', 'Log Audit - Super Admin')

@section('content')

{{-- DATA DUMMY --}}
@php
$dummyData = [
    [
        'pengguna' => 'Admin Utama',
        'status_pengguna' => 'superadmin',
        'aktivitas' => 'Login ke sistem',
        'waktu' => '06/06/2026 08.00',
        'pihak_terkait' => '-',
    ],
    [
        'pengguna' => 'Budi Santoso',
        'status_pengguna' => 'pengelola',
        'aktivitas' => 'Menambahkan kamar baru KM010',
        'waktu' => '06/06/2026 08.15',
        'pihak_terkait' => 'Kost Melati',
    ],
    [
        'pengguna' => 'Siti Rahayu',
        'status_pengguna' => 'penghuni',
        'aktivitas' => 'Mengajukan pengaduan baru',
        'waktu' => '06/06/2026 09.00',
        'pihak_terkait' => 'Kost Mawar',
    ],
    [
        'pengguna' => 'Ahmad Fauzi',
        'status_pengguna' => 'pengelola',
        'aktivitas' => 'Memperbarui status pembayaran',
        'waktu' => '06/06/2026 09.30',
        'pihak_terkait' => 'Dewi Lestari',
    ],
    [
        'pengguna' => 'Admin Utama',
        'status_pengguna' => 'superadmin',
        'aktivitas' => 'Menambahkan pengelola baru',
        'waktu' => '06/06/2026 10.00',
        'pihak_terkait' => 'Ahmad Fauzi',
    ],
    [
        'pengguna' => 'Dewi Lestari',
        'status_pengguna' => 'penghuni',
        'aktivitas' => 'Melakukan pembayaran sewa',
        'waktu' => '06/06/2026 10.45',
        'pihak_terkait' => 'Kost Kenanga',
    ],
    [
        'pengguna' => 'Reza Firmansyah',
        'status_pengguna' => 'penghuni',
        'aktivitas' => 'Memperbarui profil akun',
        'waktu' => '06/06/2026 11.00',
        'pihak_terkait' => '-',
    ],
    [
        'pengguna' => 'Budi Santoso',
        'status_pengguna' => 'pengelola',
        'aktivitas' => 'Menonaktifkan penghuni KM003',
        'waktu' => '06/06/2026 11.30',
        'pihak_terkait' => 'Ahmad Fauzi',
    ],
    [
        'pengguna' => 'Admin Utama',
        'status_pengguna' => 'superadmin',
        'aktivitas' => 'Mengakses halaman log audit',
        'waktu' => '06/06/2026 12.00',
        'pihak_terkait' => '-',
    ],
    [
        'pengguna' => 'Siti Rahayu',
        'status_pengguna' => 'penghuni',
        'aktivitas' => 'Login ke sistem',
        'waktu' => '06/06/2026 13.00',
        'pihak_terkait' => '-',
    ],
];
@endphp

<div x-data="{
    search: '',
    filter: 'semua',
    setFilter(val) { this.filter = val; }
}">

    {{-- ================= PAGE HEADER ================= --}}
    <x-page-header
        title="Log Audit"
        description="Melihat seluruh aktivitas sistem">
    </x-page-header>

    {{-- ================= SEARCH ================= --}}
    <x-search-input
        name="search_log"
        placeholder="Cari" />

    {{-- ================= FILTER TAB ================= --}}
    <div class="flex gap-2 mt-4 flex-wrap">
        <button
            type="button"
            @click="setFilter('semua')"
            :class="filter === 'semua'
                ? 'bg-primary text-white border-primary'
                : 'bg-white text-neutral border-gray-300 hover:border-primary hover:text-primary'"
            class="text-xs font-medium px-4 py-2 rounded-md border transition">
            Semua
        </button>
        <button
            type="button"
            @click="setFilter('hari_ini')"
            :class="filter === 'hari_ini'
                ? 'bg-primary text-white border-primary'
                : 'bg-white text-neutral border-gray-300 hover:border-primary hover:text-primary'"
            class="text-xs font-medium px-4 py-2 rounded-md border transition">
            Hari Ini
        </button>
        <button
            type="button"
            @click="setFilter('7_hari')"
            :class="filter === '7_hari'
                ? 'bg-primary text-white border-primary'
                : 'bg-white text-neutral border-gray-300 hover:border-primary hover:text-primary'"
            class="text-xs font-medium px-4 py-2 rounded-md border transition">
            7 Hari Terakhir
        </button>
        <button
            type="button"
            @click="setFilter('30_hari')"
            :class="filter === '30_hari'
                ? 'bg-primary text-white border-primary'
                : 'bg-white text-neutral border-gray-300 hover:border-primary hover:text-primary'"
            class="text-xs font-medium px-4 py-2 rounded-md border transition">
            30 Hari Terakhir
        </button>
    </div>

    {{-- ================= TABLE ================= --}}
    <div class="bg-white rounded-lg p-4 lg:p-6 mt-4 mb-6">
        <div class="overflow-x-auto">
            <x-table.index>
                <thead class="sticky top-0 bg-white z-10 border-b border-default">
                    <x-table.tr>
                        <x-table.th>Pengguna</x-table.th>
                        <x-table.th>Status Pengguna</x-table.th>
                        <x-table.th>Aktivitas</x-table.th>
                        <x-table.th>Waktu</x-table.th>
                        <x-table.th>Pihak Terkait</x-table.th>
                    </x-table.tr>
                </thead>
                <tbody>
                    @foreach ($dummyData as $log)
                    <x-table.tr>
                        <x-table.td class="font-medium text-heading">
                            {{ $log['pengguna'] }}
                        </x-table.td>
                        <x-table.td>
                            @if ($log['status_pengguna'] === 'superadmin')
                                <x-badge type="purple">Super Admin</x-badge>
                            @elseif ($log['status_pengguna'] === 'pengelola')
                                <x-badge type="info">Pengelola</x-badge>
                            @elseif ($log['status_pengguna'] === 'penghuni')
                                <x-badge type="neutral">Penghuni</x-badge>
                            @endif
                        </x-table.td>
                        <x-table.td>
                            {{ $log['aktivitas'] }}
                        </x-table.td>
                        <x-table.td class="text-neutral">
                            {{ $log['waktu'] }}
                        </x-table.td>
                        <x-table.td class="text-neutral">
                            {{ $log['pihak_terkait'] }}
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

</div>

@endsection