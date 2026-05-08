@extends('layouts.app')

@section('title', 'Riwayat Pembayaran')

@section('content')

<div class="p-6">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-primary">
            Riwayat Pembayaran
        </h1>

        <p class="text-gray-500 text-sm">
            Lihat semua riwayat pembayaran
        </p>
    </div>

    {{-- CARD SUMMARY --}}
    <div class="grid grid-cols-2 gap-4 mb-6">

        <div class="border border-green-400 rounded-lg p-4 bg-white">
            <p class="text-sm text-gray-500">
                Pendapatan Bulan Ini
            </p>

            <h2 class="text-2xl font-bold text-green-500">
                Rp1.000.000
            </h2>
        </div>

        <div class="border border-primary rounded-lg p-4 bg-white">
            <p class="text-sm text-gray-500">
                Total Transaksi
            </p>

            <h2 class="text-2xl font-bold text-blue-500">
                2
            </h2>
        </div>

    </div>

    {{-- SEARCH --}}
    <div class="mb-4">
        <input 
            type="text"
            placeholder="Cari"
            class="w-full border rounded-lg px-4 py-2"
        >
    </div>

    {{-- TABLE --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">

        <table class="w-full text-sm">

            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3">Nama Lengkap</th>
                    <th class="p-3">Kamar</th>
                    <th class="p-3">Tanggal</th>
                    <th class="p-3">Nominal</th>
                    <th class="p-3">Jenis</th>
                    <th class="p-3">Aksi</th>
                </tr>
            </thead>

            <tbody>

                <tr class="border-t">
                    <td class="p-3">Anto Subagja</td>
                    <td class="p-3">KM001</td>
                    <td class="p-3">08/04/2026</td>
                    <td class="p-3">Rp500.000</td>
                    <td class="p-3">Lunas</td>
                    <td class="p-3">
                        <img src="" alt="">
                    </td>
                </tr>

            </tbody>

        </table>

    </div>

</div>

@endsection