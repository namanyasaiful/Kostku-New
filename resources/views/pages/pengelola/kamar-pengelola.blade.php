@extends('layouts.pengelola')
@section('title', 'Kamar')

@section('content')

<div
    x-data="{
        activeTab: 'semua',

        modalOpen: false,
        modalType: null,

        successMessage: '',

        kamarStatus: 'terisi',

        // EDIT KAMAR
        editKamarId: null,
        editKamarNomor: '',
        editKamarPenghuni: '',
        editKamarTipe: '',
        editKamarStatus: 'kosong',
        editKamarHarga: '',
        editKamarFasilitas: '',

        deleteKamarId: null,

        openModal(type) {
            this.modalOpen = true;
            this.modalType = type;
        },

        closeModal() {
            this.modalOpen = false;
            this.modalType = null;
        },

        showSuccess(message) {
            this.successMessage = message;
            this.modalType = 'success';

            setTimeout(() => {
                this.closeModal();
            }, 2200);
        }
    }"
    x-init="@if(session('success')) modalOpen = true; modalType = 'success'; successMessage = '{{ session('success') }}'; @elseif(session('error')) modalOpen = true; modalType = 'success'; successMessage = '{{ session('error') }}'; @endif">

    {{-- ================= PAGE HEADER ================= --}}
    <x-page-header
        title="Data Kamar"
        description="Kelola kamar kost dan informasi penghuni">

        <x-slot name="action">

            <x-form.button
                type="button"
                class="lg:w-auto !w-fit"
                @click="openModal('tambah-kamar')">
                Tambah Kamar
            </x-form.button>

        </x-slot>

    </x-page-header>


    {{-- ================= TABLE ================= --}}
    <div class="bg-white rounded-xl p-4 lg:p-6 mb-6 shadow-sm">

        {{-- ================= TAB ================= --}}
        <div class="flex gap-6 border-b mb-6 overflow-x-auto">

            {{-- SEMUA --}}
            <button
                @click="activeTab = 'semua'"
                :class="
                    activeTab === 'semua'
                    ? 'border-primary text-primary font-semibold'
                    : 'border-transparent text-black font-medium'
                "
                class="pb-3 border-b-2 text-sm whitespace-nowrap transition">

                Semua

            </button>

            {{-- KOSONG --}}
            <button
                @click="activeTab = 'kosong'"
                :class="
                    activeTab === 'kosong'
                    ? 'border-primary text-primary font-semibold'
                    : 'border-transparent text-black font-medium'
                "
                class="pb-3 border-b-2 text-sm whitespace-nowrap transition">

                Kosong

            </button>

            {{-- TERISI --}}
            <button
                @click="activeTab = 'terisi'"
                :class="
                    activeTab === 'terisi'
                    ? 'border-primary text-primary font-semibold'
                    : 'border-transparent text-black font-medium'
                "
                class="pb-3 border-b-2 text-sm whitespace-nowrap transition">

                Terisi

            </button>

        </div>


        {{-- ================= TABLE WRAPPER ================= --}}
        <div class="overflow-x-auto overflow-y-auto max-h-[420px]">

            <table class="w-full min-w-[900px]">

                <thead>

                    <tr class="border-b">

                        <th class="py-4 px-3 text-left text-sm font-semibold">
                            Nomor Kamar
                        </th>

                        <th class="py-4 px-3 text-left text-sm font-semibold">
                            Nama Penghuni
                        </th>

                        <th class="py-4 px-3 text-left text-sm font-semibold">
                            Tipe Kamar
                        </th>

                        <th class="py-4 px-3 text-left text-sm font-semibold">
                            Harga
                        </th>

                        <th class="py-4 px-3 text-center text-sm font-semibold">
                            Status
                        </th>

                        <th class="py-4 px-3 text-center text-sm font-semibold">
                            Aksi
                        </th>

                    </tr>

                </thead>

                {{-- ================= KAMAR TERISI ================= --}}
                <template x-if="activeTab === 'semua' || activeTab === 'terisi'">
                    <tbody class="divide-y divide-gray-200">
                        @forelse($terisiKamars as $kamar)
                        <tr class="border-b hover:bg-gray-50 transition">
                            <td class="py-4 px-3 text-sm">
                                {{ $kamar->nomor_kamar }}
                            </td>

                            <td class="py-4 px-3 text-sm">
                                {{ $kamar->penghuni?->nama ?? '-' }}
                            </td>

                            <td class="py-4 px-3 text-sm">
                                {{ $kamar->tipe_kamar }}
                            </td>

                            <td class="py-4 px-3 text-sm">
                                Rp{{ number_format($kamar->harga, 0, ',', '.') }}
                            </td>

                            <td class="py-4 px-3 text-center">
                                <x-badge type="danger">Terisi</x-badge>
                            </td>

                            <td class="py-4 px-3">
                                <div class="flex items-center justify-center gap-2">
                                    {{-- DETAIL --}}
                                    <x-form.button
                                        @click="kamarStatus = 'terisi'; detailKamarNomor = '{{ $kamar->nomor_kamar }}'; detailKamarPenghuni = '{{ addslashes($kamar->penghuni?->nama ?? '-') }}'; detailKamarTipe = '{{ addslashes($kamar->tipe_kamar) }}'; detailKamarStatus = '{{ $kamar->status }}'; detailKamarHarga = '{{ $kamar->harga }}'; detailKamarFasilitas = '{{ addslashes($kamar->fasilitas ?? '-') }}'; openModal('detail-kamar')"
                                        class="w-24 !p-2 border border-primary bg-transparent !text-primary hover:bg-secondary hover:border-secondary">Detail</x-form.button>

                                    {{-- EDIT --}}
                                    <x-form.button
                                        @click="editKamarId = '{{ $kamar->id }}'; editKamarNomor = '{{ addslashes($kamar->nomor_kamar) }}'; editKamarPenghuni = '{{ addslashes($kamar->penghuni?->nama ?? '-') }}'; editKamarTipe = '{{ addslashes($kamar->tipe_kamar) }}'; editKamarStatus = '{{ $kamar->status }}'; editKamarHarga = '{{ $kamar->harga }}'; editKamarFasilitas = '{{ addslashes($kamar->fasilitas ?? '-') }}'; kamarStatus = 'terisi'; openModal('edit-kamar')"
                                        class="w-24 !p-2 border border-neutral bg-transparent !text-neutral hover:!bg-[#E2E2E2] hover:!border-[#E2E2E2]">Edit</x-form.button>

                                    {{-- DELETE --}}
                                    <x-form.button
                                        @click="kamarStatus = 'terisi'; openModal('cannot-delete')"
                                        class="w-24 !p-2 border border-[#E73D2E] bg-transparent !text-[#E73D2E] hover:!bg-[#FFC5BF] hover:!border-[#FFC5BF]">Hapus</x-form.button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr x-show="activeTab === 'terisi'">
                            <td colspan="6" class="py-8 text-center text-gray-500 text-sm">
                                Tidak ada data kamar terisi.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </template>

                {{-- ================= KAMAR KOSONG ================= --}}
                <template x-if="activeTab === 'semua' || activeTab === 'kosong'">
                    <tbody class="divide-y divide-gray-200">
                        @forelse($kosongKamars as $kamar)
                        <tr class="border-b hover:bg-gray-50 transition">
                            <td class="py-4 px-3 text-sm">
                                {{ $kamar->nomor_kamar }}
                            </td>

                            <td class="py-4 px-3 text-sm">
                                {{ $kamar->penghuni?->nama ?? '-' }}
                            </td>

                            <td class="py-4 px-3 text-sm">
                                {{ $kamar->tipe_kamar }}
                            </td>

                            <td class="py-4 px-3 text-sm">
                                Rp{{ number_format($kamar->harga, 0, ',', '.') }}
                            </td>

                            <td class="py-4 px-3 text-center">
                                <x-badge type="success">Kosong</x-badge>
                            </td>

                            <td class="py-4 px-3">
                                <div class="flex items-center justify-center gap-2">
                                    {{-- DETAIL --}}
                                    <x-form.button
                                        @click="kamarStatus = 'kosong'; detailKamarNomor = '{{ $kamar->nomor_kamar }}'; detailKamarPenghuni = '{{ addslashes($kamar->penghuni?->nama ?? '-') }}'; detailKamarTipe = '{{ addslashes($kamar->tipe_kamar) }}'; detailKamarStatus = '{{ $kamar->status }}'; detailKamarHarga = '{{ $kamar->harga }}'; detailKamarFasilitas = '{{ addslashes($kamar->fasilitas ?? '-') }}'; openModal('detail-kamar')"
                                        class="w-24 !p-2 border border-primary bg-transparent !text-primary hover:bg-secondary hover:border-secondary">Detail</x-form.button>

                                    {{-- EDIT --}}
                                    <x-form.button
                                        @click="editKamarId = '{{ $kamar->id }}'; editKamarNomor = '{{ addslashes($kamar->nomor_kamar) }}'; editKamarPenghuni = '{{ addslashes($kamar->penghuni?->nama ?? '-') }}'; editKamarTipe = '{{ addslashes($kamar->tipe_kamar) }}'; editKamarStatus = '{{ $kamar->status }}'; editKamarHarga = '{{ $kamar->harga }}'; editKamarFasilitas = '{{ addslashes($kamar->fasilitas ?? '-') }}'; kamarStatus = 'kosong'; openModal('edit-kamar')"
                                        class="w-24 !p-2 border border-neutral bg-transparent !text-neutral hover:!bg-[#E2E2E2] hover:!border-[#E2E2E2]">Edit</x-form.button>

                                    {{-- DELETE --}}
                                    <x-form.button
                                        @click="kamarStatus = 'kosong'; deleteKamarId = '{{ $kamar->id }}'; openModal('confirm-delete')"
                                        class="w-24 !p-2 border border-[#E73D2E] bg-transparent !text-[#E73D2E] hover:!bg-[#FFC5BF] hover:!border-[#FFC5BF]">Hapus</x-form.button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr x-show="activeTab === 'kosong'">
                            <td colspan="6" class="py-8 text-center text-gray-500 text-sm">
                                Tidak ada data kamar kosong.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </template>

                @if($allKamars->isEmpty())
                <tbody>
                    <tr>
                        <td colspan="6" class="py-8 text-center text-gray-500 text-sm">
                            Belum ada data kamar terdaftar.
                        </td>
                    </tr>
                </tbody>
                @endif

            </table>

        </div>

    </div>


    {{-- ================= PAGINATION ================= --}}
    <div x-show="activeTab === 'semua'">
    <x-pagination :paginator="$allKamars" />
    </div>
    <div x-show="activeTab === 'terisi'">
        <x-pagination :paginator="$terisiKamars" />
    </div>
    <div x-show="activeTab === 'kosong'">
        <x-pagination :paginator="$kosongKamars" />
    </div>


    {{-- ================= MODAL ================= --}}
    <x-modal show="modalOpen" maxWidth="lg:max-w-[550px] max-w-[360px]">

        {{-- ================= TAMBAH KAMAR ================= --}}
        <template x-if="modalType === 'tambah-kamar'">
            <div class="relative">

                <button
                    type="button"
                    @click="closeModal()"
                    class="absolute top-0 right-0 text-xl">

                    ✕

                </button>

                <h2 class="text-xl font-bold mb-6">
                    Tambah Kamar
                </h2>

                <div class="space-y-4">
                    <form action="{{ route('kamar.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <x-form.input
                            label="Nomor Kamar"
                            name="nomor_kamar"
                            placeholder="Contoh: KM001" class="text-sm" />

                        <x-form.select
                            label="Tipe Kamar"
                            name="tipe_kamar"
                            placeholder="Pilih tipe kamar" class="text-sm">
                            <option value="standard" class="text-xs">Standard</option>
                            <option value="premium" class="text-xs">Premium</option>

                        </x-form.select>

                        <x-form.input
                            label="Harga per Bulan"
                            name="harga"
                            placeholder="Contoh: Rp500.000" class="text-sm" />

                        <x-form.input
                            label="Fasilitas Kamar"
                            name="fasilitas"
                            placeholder="Contoh: Lemari, WiFi, dll" class="text-sm" />

                        <div class="flex gap-3 pt-4">

                            <x-form.button
                                type="button"
                                class="w-full bg-transparent border-2 border-primary !text-primary hover:bg-secondary hover:border-secondary hover:!text-primary"
                                @click="closeModal()">
                                Batal
                            </x-form.button>

                            <x-form.button
                                type="submit"
                                class="w-full">
                                Simpan
                            </x-form.button>

                    </form>
                </div>
            </div>
</div>
</template>


{{-- ================= DETAIL KAMAR ================= --}}
<template x-if="modalType === 'detail-kamar'">

    <div class="relative">

        {{-- CLOSE --}}
        <button
            type="button"
            @click="closeModal()"
            class="absolute top-0 right-0 text-xl">

            ✕

        </button>

        {{-- TITLE --}}
        <h2 class="text-xl font-bold mb-6">
            Detail Kamar
        </h2>

        {{-- CONTENT --}}
        <div class="space-y-4 lg:max-h-[450px] max-h-[250px] overflow-auto">

            <x-form.input
                label="Nomor Kamar"
                name="detail-nomor-kamar"
                x-model="detailKamarNomor"
                class="lg:text-sm text-xs"
                readonly />

            <x-form.input
                label="Nama Penghuni"
                name="detail-penghuni"
                x-model="detailKamarPenghuni"
                class="lg:text-sm text-xs"
                readonly />

            <x-form.select
                label="Tipe Kamar"
                name="detail-tipe"
                class="lg:text-sm text-xs"
                disabled>

                <option x-text="detailKamarTipe"></option>

            </x-form.select>

            <x-form.select
                label="Status Kamar"
                name="detail-status"
                x-model="detailKamarStatus"
                class="lg:text-sm text-xs"
                disabled>

                <option value="kosong" x-text="detailKamarStatus === 'kosong' ? 'Kosong' : ''"></option>
                <option value="terisi" x-text="detailKamarStatus === 'terisi' ? 'Terisi' : ''"></option>

            </x-form.select>

            <x-form.input
                label="Harga per Bulan"
                name="detail-harga"
                x-model="detailKamarHarga"
                class="lg:text-sm text-xs"
                readonly />

            <x-form.input
                label="Fasilitas Kamar"
                name="detail-fasilitas"
                x-model="detailKamarFasilitas"
                class="lg:text-sm text-xs"
                readonly />
        </div>
    </div>

</template>


{{-- ================= EDIT KAMAR ================= --}}
<template x-if="modalType === 'edit-kamar'">

    <div class="relative">

        {{-- CLOSE --}}
        <button
            type="button"
            @click="closeModal()"
            class="absolute top-0 right-0 text-xl">

            ✕

        </button>

        {{-- TITLE --}}
        <h2 class="text-xl font-bold mb-6">
            Edit Kamar
        </h2>

        {{-- CONTENT --}}
        <div class="space-y-4 lg:max-h-[450px] max-h-[250px] overflow-auto">

            <form :action="'{{ url('/pengelola/kamar-pengelola/update') }}/' + editKamarId" method="POST">
                @csrf

                {{-- Nomor Kamar --}}
                <x-form.input
                    label="Nomor Kamar"
                    name="nomor_kamar"
                    x-model="editKamarNomor"
                    class="lg:text-sm text-xs !mb-2" />

                {{-- Nama Penghuni (display saja) --}}
                <x-form.input
                    label="Nama Penghuni"
                    name="penghuni"
                    x-model="editKamarPenghuni"
                    class="lg:text-sm text-xs !mb-2"
                    readonly />

                {{-- Tipe Kamar --}}
                <x-form.select
                    label="Tipe Kamar"
                    name="tipe_kamar"
                    x-model="editKamarTipe"
                    class="lg:text-sm text-xs !mb-2">

                    <option value="standard">Standard</option>
                    <option value="premium">Premium</option>

                </x-form.select>

                {{-- Status Kamar (display saja) --}}
                <x-form.select
                    label="Status Kamar"
                    name="status"
                    x-model="editKamarStatus"
                    class="lg:text-sm text-xs !mb-2"
                    disabled>

                    <option value="kosong">Kosong</option>
                    <option value="terisi">Terisi</option>

                </x-form.select>

                {{-- Harga --}}
                <x-form.input
                    label="Harga per Bulan"
                    name="harga"
                    x-model="editKamarHarga"
                    class="lg:text-sm text-xs !mb-2" />

                {{-- Fasilitas --}}
                <x-form.input
                    label="Fasilitas Kamar"
                    name="fasilitas"
                    x-model="editKamarFasilitas"
                    class="lg:text-sm text-xs mb-2" />

                {{-- ACTION --}}
                <div class="flex gap-3 pt-4">

                    <x-form.button
                        type="button"
                        class="w-full bg-transparent border-2 border-primary !text-primary hover:bg-secondary hover:border-secondary hover:!text-primary"
                        @click="closeModal()">
                        Batal
                    </x-form.button>

                    <x-form.button
                        type="submit"
                        class="w-full">
                        Simpan
                    </x-form.button>

                </div>
            </form>

        </div>
    </div>
</template>


{{-- ================= CONFIRM DELETE ================= --}}
<template x-if="modalType === 'confirm-delete'">

    <div class="relative">

        <button
            type="button"
            @click="closeModal()"
            class="absolute top-0 right-0 text-xl">

            ✕

        </button>

        <h2 class="text-xl font-bold mb-4">
            Konfirmasi Hapus
        </h2>

        <p class="text-sm text-neutral">
            Apakah Anda yakin ingin menghapus kamar? Tindakan ini tidak dapat dibatalkan.
        </p>

        <div class="flex gap-3 mt-8">

            <x-form.button
                type="button"
                class="w-full bg-transparent border-2 border-neutral !text-neutral hover:bg-neutral hover:!text-white"
                @click="closeModal()">
                Batal
            </x-form.button>

            <x-form.button
                type="button"
                class="w-full bg-red-600 hover:bg-red-100 hover:text-red-600"
                @click="$refs.deleteForm.submit()">
                Hapus
            </x-form.button>

            <form x-ref="deleteForm" :action="'{{ url('/pengelola/kamar-pengelola/delete') }}/' + deleteKamarId" method="POST" class="hidden">
                @csrf
            </form>

        </div>

    </div>

</template>


{{-- ================= CANNOT DELETE ================= --}}
<template x-if="modalType === 'cannot-delete'">

    <div class="relative lg:px-2 px-1 pt-2 pb-1">

        {{-- CLOSE BUTTON --}}
        <button
            type="button"
            @click="closeModal()"
            class="
                absolute
                top-0
                right-0
                w-8 h-8
                flex items-center justify-center
                rounded-full
                hover:bg-gray-100
                transition
            ">
            ✕
        </button>

        <div class="text-center">
            <div class="flex justify-center mb-4">
                <img
                    src="{{ asset('assets/icons/failed-modal-icon.png') }}"
                    class="w-14">
            </div>
            <div class="text-center pt-6">
                <h2 class="lg:text-lg text-sm font-bold mb-2 text-black">
                    Tidak dapat menghapus kamar
                </h2>
                <p class="lg:text-sm text-xs text-neutral leading-relaxed">
                    Anda tidak dapat menghapus kamar yang masih ada penghuninya.
                </p>
            </div>
        </div>

</template>


{{-- ================= SUCCESS ================= --}}
<template x-if="modalType === 'success'">

    <div class="text-center">

        <div class="flex justify-center mb-4">

            <img
                src="{{ asset('assets/icons/success-modal-icon.png') }}"
                class="w-14">

        </div>

        <h2 class="text-lg font-bold">
            <span x-text="successMessage"></span>
        </h2>

    </div>

</template>

</x-modal>

</div>
@endsection