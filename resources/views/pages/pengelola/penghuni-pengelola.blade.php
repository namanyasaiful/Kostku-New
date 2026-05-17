@extends('layouts.pengelola')
@section('title', 'Penghuni')

@section('content')

<div class="space-y-6">

    {{-- ================= PAGE HEADER ================= --}}
    <x-page-header
        title="Data Penghuni"
        description="Daftar penghuni dan kelola permintaan">

    </x-page-header>


    {{-- ================= SEARCH ================= --}}
    <x-search-input
        name="search_penghuni"
        placeholder="Cari" />


    {{-- ================= TABLE ================= --}}
    <div class="bg-white rounded-lg p-4 lg:p-6">

        {{-- ================= TAB ================= --}}
        <div class="flex gap-6 mb-6 min-w-max border-b">

            <button
                class="
                    pb-3 border-b-2 border-primary
                    text-primary font-medium
                    text-xs lg:text-sm
                ">

                Daftar Penghuni

            </button>

            <button
                class="
                    pb-3 border-b-2 border-transparent
                    text-neutral
                    text-xs lg:text-sm
                ">

                Permintaan Masuk

            </button>

            <button
                class="
                    pb-3 border-b-2 border-transparent
                    text-neutral
                    text-xs lg:text-sm
                ">

                Permintaan Keluar

            </button>

        </div>


        {{-- ================= TABLE CONTENT ================= --}}
        <div class="overflow-x-auto">

            <table class="w-full min-w-[700px] table-fixed">

                <thead>

                    <tr class="border-b">

                        <th class="w-[28%] text-left py-4 px-2 text-xs lg:text-sm font-semibold">
                            Nama Lengkap
                        </th>

                        <th class="w-[22%] text-left py-4 px-2 text-xs lg:text-sm font-semibold">
                            No HP
                        </th>

                        <th class="w-[15%] text-left py-4 px-2 text-xs lg:text-sm font-semibold">
                            Kamar
                        </th>

                        <th class="w-[20%] text-left py-4 px-2 text-xs lg:text-sm font-semibold">
                            Tanggal Masuk
                        </th>

                        <th class="w-[15%] text-center py-4 px-2 text-xs lg:text-sm font-semibold">
                            Aksi
                        </th>

                    </tr>

                </thead>


                <tbody>

                    <tr class="border-b">

                        <td class="py-4 px-2 text-xs lg:text-sm">
                            Anto Subagja
                        </td>

                        <td class="py-4 px-2 text-xs lg:text-sm">
                            081234567892
                        </td>

                        <td class="py-4 px-2 text-xs lg:text-sm">
                            KM001
                        </td>

                        <td class="py-4 px-2 text-xs lg:text-sm">
                            08/04/2026
                        </td>

                        <td class="py-4 px-2">

                            <div class="flex justify-center">

                                <a
                                    href="#"
                                    class="
                                        p-2 rounded-md
                                        hover:bg-blue-50
                                        transition
                                    ">

                                    <img
                                        src="{{ asset('assets/icons/lihat-detail-icon.png') }}"
                                        alt="Lihat Detail"
                                        class="w-4 h-4">

                                </a>

                            </div>

                        </td>

                    </tr>

                </tbody>

            </table>

        </div>

    </div>


    {{-- ================= PAGINATION ================= --}}
    <x-pagination />

</div>

@endsection