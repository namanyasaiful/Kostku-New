@extends('layouts.pengelola')
@section('title', 'Penghuni')

@section('content')

<div
    x-data="{
        activeTab: 'daftar',

        modalOpen: false,
        modalType: null,

        openModal(type) {
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

            setTimeout(() => {
                this.closeModal();
            }, 2500);
        }
    }">

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
    <div class="bg-white rounded-lg p-4 lg:p-6 mt-4 mb-6">

        {{-- ================= TAB ================= --}}
        <div class="flex lg:gap-6 gap-3 mb-6 min-w-max border-b">

            {{-- DAFTAR PENGHUNI --}}
            <button
                @click="activeTab = 'daftar'"
                :class="
                    activeTab === 'daftar'
                    ? 'border-primary text-primary font-bold'
                    : 'border-transparent text-black font-medium'
                "
                class="
                    pb-3 border-b-2
                    text-xs lg:text-sm
                    transition
                ">

                Daftar Penghuni

            </button>

            {{-- PERMINTAAN MASUK --}}
            <button
                @click="activeTab = 'masuk'"
                :class="
                    activeTab === 'masuk'
                    ? 'border-primary text-primary font-bold'
                    : 'border-transparent text-black font-medium'
                "
                class="
                    pb-3 border-b-2
                    text-xs lg:text-sm
                    transition
                ">

                Permintaan Masuk

            </button>

            {{-- PERMINTAAN KELUAR --}}
            <button
                @click="activeTab = 'keluar'"
                :class="
                    activeTab === 'keluar'
                    ? 'border-primary text-primary font-bold'
                    : 'border-transparent text-black font-medium'
                "
                class="
                    pb-3 border-b-2
                    text-xs lg:text-sm
                    transition
                ">

                Permintaan Keluar

            </button>

        </div>

        {{-- ================= DAFTAR PENGHUNI ================= --}}
        <div
            x-show="activeTab === 'daftar'"
            x-transition>

            <div class="overflow-x-auto overflow-y-auto max-h-[360px]">

                <table class="w-full min-w-[700px] table-fixed">

                    <thead>

                        <tr class="border-b">

                            <th class="w-[28%] text-left py-4 px-2 text-xs lg:text-sm font-medium">
                                Nama Lengkap
                            </th>

                            <th class="w-[22%] text-left py-4 px-2 text-xs lg:text-sm font-medium">
                                No HP
                            </th>

                            <th class="w-[15%] text-left py-4 px-2 text-xs lg:text-sm font-medium">
                                Kamar
                            </th>

                            <th class="w-[20%] text-left py-4 px-2 text-xs lg:text-sm font-medium">
                                Tanggal Masuk
                            </th>

                            <th class="w-[15%] text-center py-4 px-2 text-xs lg:text-sm font-medium">
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
                                        @click.prevent="openModal('detail-penghuni')"
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

        {{-- ================= PERMINTAAN MASUK ================= --}}
        <div
            x-show="activeTab === 'masuk'"
            x-transition>

            <div class="overflow-x-auto overflow-y-auto max-h-[360px]">

                <table class="w-full min-w-[600px] table-fixed">

                    <thead>

                        <tr class="border-b">

                            <th class="w-[40%] text-left py-4 px-2 text-xs lg:text-sm font-medium">
                                Nama Lengkap
                            </th>

                            <th class="w-[35%] text-left py-4 px-2 text-xs lg:text-sm font-medium">
                                No HP
                            </th>

                            <th class="w-[25%] text-center py-4 px-2 text-xs lg:text-sm font-medium">
                                Aksi
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        <tr class="border-b">

                            <td class="py-4 px-2 text-xs lg:text-sm">
                                Budi Santoso
                            </td>

                            <td class="py-4 px-2 text-xs lg:text-sm">
                                081234567890
                            </td>

                            <td class="py-4 px-2">

                                <div class="flex justify-center gap-2">
                                    <a
                                        href="#"
                                        @click.prevent="openModal('permintaan-masuk')"
                                        class="p-2 rounded-md hover:bg-blue-50 transition">
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

        {{-- ================= PERMINTAAN KELUAR ================= --}}
        <div
            x-show="activeTab === 'keluar'"
            x-transition>

            <div class="overflow-x-auto overflow-y-auto max-h-[360px]">

                <table class="w-full min-w-[600px] table-fixed">

                    <thead>

                        <tr class="border-b">

                            <th class="w-[40%] text-left py-4 px-2 text-xs lg:text-sm font-medium">
                                Nama Lengkap
                            </th>

                            <th class="w-[35%] text-left py-4 px-2 text-xs lg:text-sm font-medium">
                                No HP
                            </th>

                            <th class="w-[25%] text-center py-4 px-2 text-xs lg:text-sm font-medium">
                                Aksi
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        <tr class="border-b">

                            <td class="py-4 px-2 text-xs lg:text-sm">
                                Siti Rahma
                            </td>

                            <td class="py-4 px-2 text-xs lg:text-sm">
                                089876543210
                            </td>

                            <td class="py-4 px-2">

                                <div class="flex justify-center">

                                    <a
                                        href="#"
                                        @click.prevent="openModal('permintaan-keluar')"
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

    </div>

    {{-- ================= PAGINATION ================= --}}
    <x-pagination />

    {{-- ================= MODAL ================= --}}
    <x-modal show="modalOpen" maxWidth="lg:max-w-[500px] max-w-[350px]">

        {{-- ================= DETAIL PENGHUNI ================= --}}
        <template x-if="modalType === 'detail-penghuni'">

            <div class="relative">

                <button
                    type="button"
                    class="absolute top-0 right-0 text-xl"
                    @click="closeModal()">

                    ✕

                </button>

                <h2 class="text-xl font-bold mb-6">
                    Detail Penghuni
                </h2>

                <div class="space-y-4">

                    <div class="flex lg:gap-28 gap-20">
                        <div>
                            <p class="text-xs text-neutral mb-1">Nama Lengkap</p>
                            <p class="text-xs font-medium">Anto Subagja</p>
                        </div>

                        <div>
                            <p class="text-xs text-neutral mb-1">No HP</p>
                            <p class="text-xs font-medium">081234567892</p>
                        </div>
                    </div>
                    <hr>

                    <div class="my-2">
                        <p class="text-xs text-neutral mb-1">Alamat</p>
                        <p class="text-xs font-medium">Jl. Sudirman No.123</p>
                    </div>

                    <div class="flex gap-28">
                        <div>
                            <p class="text-xs text-neutral mb-1">Kamar</p>
                            <p class="text-xs font-medium">KM002</p>
                        </div>

                        <div>
                            <p class="text-xs text-neutral mb-1">Tanggal Masuk</p>
                            <p class="text-xs font-medium">08/05/2026</p>
                        </div>
                    </div>
                    <hr>

                    <div class="flex flex-col gap-4">
                        <div class="w-full flex justify-between">
                            <p class="text-sm font-medium text-primary">Track Record</p>
                            <a href="#" class="text-xs no-underline text-neutral">Lihat selengkapnya</a>
                        </div>
                        <div class="w-full flex justify-between">
                            <p class="text-xs text-neutral">Pembayaran</p>
                            <x-badge type="success">Baik</x-badge>
                        </div>
                        <div class="w-full flex justify-between">
                            <p class="text-xs text-neutral">Sikap</p>
                            <x-badge type="warning">Perlu perhatian</x-badge>
                        </div>
                        <div class="w-full flex justify-between">
                            <p class="text-xs text-neutral">Perawatan Fasilitas</p>
                            <x-badge type="success">Baik</x-badge>
                        </div>
                    </div>

                </div>

            </div>

        </template>


        {{-- ================= PERMINTAAN MASUK ================= --}}
        <template x-if="modalType === 'permintaan-masuk'">

            <div class="relative">

                <button
                    type="button"
                    class="absolute top-0 right-0 text-xl"
                    @click="closeModal()">

                    ✕

                </button>

                <h2 class="text-xl font-bold mb-4">
                    Permintaan Masuk
                </h2>

                <div class="space-y-4">

                    <div class="flex lg:gap-28 gap-20">
                        <div>
                            <p class="text-xs text-neutral mb-1">Nama Lengkap</p>
                            <p class="text-xs font-medium">Anto Subagja</p>
                        </div>

                        <div>
                            <p class="text-xs text-neutral mb-1">No HP</p>
                            <p class="text-xs font-medium">081234567892</p>
                        </div>
                    </div>
                    <hr>

                    <div class="my-2">
                        <p class="text-xs text-neutral mb-1">Alamat</p>
                        <p class="text-xs font-medium">Jl. Sudirman No.123</p>
                    </div>
                    <hr>
                    <div class="flex flex-col gap-4">
                        <div class="w-full flex justify-between">
                            <p class="text-sm font-medium text-primary">Track Record</p>
                            <a href="#" class="text-xs no-underline text-neutral">Lihat selengkapnya</a>
                        </div>
                        <div class="w-full flex justify-between">
                            <p class="text-xs text-neutral">Pembayaran</p>
                            <x-badge type="success">Baik</x-badge>
                        </div>
                        <div class="w-full flex justify-between">
                            <p class="text-xs text-neutral">Sikap</p>
                            <x-badge type="warning">Perlu perhatian</x-badge>
                        </div>
                        <div class="w-full flex justify-between">
                            <p class="text-xs text-neutral">Perawatan Fasilitas</p>
                            <x-badge type="success">Baik</x-badge>
                        </div>
                    </div>
                </div>

                <div class="flex gap-3 mt-8">
                    <x-form.button
                        type="button"
                        class="w-full text-white bg-red-600 hover:bg-red-100 hover:text-red-600"
                        @click="modalType = 'confirm-tolak'">
                        Tolak
                    </x-form.button>
                    <x-form.button
                        type="button"
                        class="w-full text-white bg-green-600 hover:bg-green-100 hover:text-[#5BBA43]"
                        @click="modalType = 'setujui-penghuni'">
                        Setuju
                    </x-form.button>

                </div>

            </div>

        </template>


        {{-- ================= CONFIRM TOLAK ================= --}}
        <template x-if="modalType === 'confirm-tolak'">

            <div class="relative">

                <button
                    type="button"
                    class="absolute top-0 right-0 text-xl"
                    @click="closeModal()">

                    ✕

                </button>

                <h2 class="text-xl font-bold mb-4">
                    Konfirmasi Tolak
                </h2>

                <p class="text-xs text-neutral">Apakah Anda yakin ingin menolak permintaan penghuni? Tindakan ini tidak dapat dibatalkan.</p>

                <div class="flex gap-3 mt-8">
                    <x-form.button
                        type="button"
                        class="w-full !text-neutral bg-transparent border-2 border-neutral hover:bg-neutral hover:!text-white"
                        @click="modalType = 'permintaan-masuk'">
                        Batal
                    </x-form.button>
                    <x-form.button
                        type="button"
                        class="w-full text-white bg-red-600 hover:bg-red-100 hover:text-red-600"
                        @click="showSuccess('Permintaan berhasil ditolak')">
                        Tolak
                    </x-form.button>

                </div>

            </div>

        </template>


        {{-- ================= SETUJUI PENGHUNI ================= --}}
        <template x-if="modalType === 'setujui-penghuni'">

            <div class="relative">

                <button
                    type="button"
                    class="absolute top-0 right-0 text-xl"
                    @click="closeModal()">

                    ✕

                </button>

                <h2 class="text-xl font-bold mb-6">
                    Detail Penghuni
                </h2>

                <div class="space-y-4">

                    <div class="flex lg:gap-28 gap-20">
                        <div>
                            <p class="text-xs text-neutral mb-1">Nama Lengkap</p>
                            <p class="text-xs font-medium">Anto Subagja</p>
                        </div>

                        <div>
                            <p class="text-xs text-neutral mb-1">No HP</p>
                            <p class="text-xs font-medium">081234567892</p>
                        </div>
                    </div>
                    <hr>

                    <div class="my-2">
                        <p class="text-xs text-neutral mb-1">Alamat</p>
                        <p class="text-xs font-medium">Jl. Sudirman No.123</p>
                    </div>
                    <hr>

                    <x-form.select
                        label="Pilih Kamar"
                        name="kamar">

                        <option value="">
                            Pilih kamar
                        </option>

                        <option value="KM001">
                            KM001
                        </option>

                        <option value="KM002">
                            KM002
                        </option>

                    </x-form.select>

                    <div class="flex gap-3 pt-2">
                        <x-form.button
                            type="button"
                            class="w-full bg-transparent border-2 border-primary !text-primary hover:bg-secondary hover:border-secondary hover:!text-primary"
                            @click="modalType = 'permintaan-masuk'">
                            Kembali
                        </x-form.button>
                        <x-form.button
                            type="button"
                            class="w-full"
                            @click="showSuccess('Penghuni berhasil ditambahkan')">
                            Simpan
                        </x-form.button>
                    </div>

                </div>

            </div>
        </template>


        {{-- ================= PERMINTAAN KELUAR ================= --}}
        <template x-if="modalType === 'permintaan-keluar'">

            <div class="relative">

                <button
                    type="button"
                    class="absolute top-0 right-0 text-xl"
                    @click="closeModal()">

                    ✕

                </button>

                <h2 class="text-xl font-bold mb-4">
                    Konfirmasi keluar kost
                </h2>

                <div class="space-y-4">
                    <div class="flex lg:gap-28 gap-20">

                        <div>
                            <p class="text-xs text-neutral mb-1">Nama Lengkap</p>
                            <p class="text-xs font-medium">Siti Rahma</p>
                        </div>

                        <div>
                            <p class="text-xs text-neutral mb-1">No HP</p>
                            <p class="text-xs font-medium">089876543210</p>
                        </div>

                    </div>
                    <hr>
                    <div>
                        <p class="text-xs text-neutral mb-1">Alamat</p>
                        <p class="text-xs font-medium">
                            Jl. Sudirman No.123
                        </p>
                    </div>
                    <hr>
                    <x-form.textarea
                        label="Alasan Keluar"
                        name="alasan-keluar-kost"
                        rows="3"
                        class="text-xs"
                        readonly>
                        Sudah selesai masa kuliah
                    </x-form.textarea>

                    <div class="flex gap-3 mt-8">
                        <x-form.button
                            type="button"
                            class="w-full text-white bg-red-600 hover:bg-red-100 hover:text-red-600"
                            @click="modalType = 'confirm-tolak-keluar'">
                            Tolak
                        </x-form.button>
                        <x-form.button
                            type="button"
                            class="w-full text-white bg-green-600 hover:bg-green-100 hover:text-[#5BBA43]"
                            @click="modalType = 'setujui-keluar'">
                            Setuju
                        </x-form.button>
                    </div>

                </div>

            </div>

        </template>

        {{-- ================= CONFIRM TOLAK KELUAR ================= --}}
        <template x-if="modalType === 'confirm-tolak-keluar'">

            <div class="relative">

                <button
                    type="button"
                    class="absolute top-0 right-0 text-xl"
                    @click="closeModal()">

                    ✕

                </button>

                <h2 class="text-xl font-bold mb-4">
                    Konfirmasi Hapus
                </h2>

                <p class="text-xs text-neutral">
                    Apakah Anda yakin ingin menolak permintaan penghuni? Tindakan ini tidak dapat dibatalkan.
                </p>

                <div class="flex gap-3 mt-8">

                    <x-form.button
                        type="button"
                        class="w-full bg-transparent border-2 border-neutral !text-neutral hover:bg-neutral hover:!text-white"
                        @click="modalType = 'permintaan-keluar'">
                        Batal
                    </x-form.button>

                    <x-form.button
                        type="button"
                        class="w-full text-white bg-red-600 hover:bg-red-100 hover:text-red-600"
                        @click="showSuccess('Permintaan keluar berhasil ditolak')">
                        Ya
                    </x-form.button>

                </div>

            </div>

        </template>

        {{-- ================= SETUJUI PENGHUNI KELUAR ================= --}}
        <template x-if="modalType === 'setujui-keluar'">

            <div class="relative">

                <button
                    type="button"
                    class="absolute top-0 right-0 text-xl"
                    @click="closeModal()">

                    ✕

                </button>

                <h2 class="text-xl font-bold mb-6">
                    Penilaian Penghuni
                </h2>

                <div class="space-y-4 lg:max-h-[450px] max-h-[250px] overflow-auto">

                    <div class="flex lg:gap-28 gap-20">
                        <div>
                            <p class="text-xs text-neutral mb-1">Nama Lengkap</p>
                            <p class="text-xs font-medium">Anto Subagja</p>
                        </div>

                        <div>
                            <p class="text-xs text-neutral mb-1">No HP</p>
                            <p class="text-xs font-medium">081234567892</p>
                        </div>
                    </div>
                    <hr>
                    <div class="my-2">
                        <p class="text-xs text-neutral mb-1">Alamat</p>
                        <p class="text-xs font-medium">Jl. Sudirman No.123</p>
                    </div>
                    <hr>

                    <x-form.select
                        label="Ketertiban pembayaran"
                        name="ketertiban-bayar" placeholder="Status">
                        <option value="baik">
                            Baik
                        </option>
                        <option value="perlu-perhatian">
                            Perlu perhatian
                        </option>
                        <option value="Buruk">
                            Buruk
                        </option>
                    </x-form.select>
                    <x-form.select
                        label="Sikap"
                        name="sikap" placeholder="Status">
                        <option value="baik">
                            Baik
                        </option>
                        <option value="perlu-perhatian">
                            Perlu perhatian
                        </option>
                        <option value="Buruk">
                            Buruk
                        </option>
                    </x-form.select>
                    <x-form.select
                        label="Perawatan fasilitas"
                        name="perawatan-fasilitas" placeholder="Status">
                        <option value="baik">
                            Baik
                        </option>
                        <option value="perlu-perhatian">
                            Perlu perhatian
                        </option>
                        <option value="Buruk">
                            Buruk
                        </option>
                    </x-form.select>

                    {{-- FILE UPLOAD --}}
                    <div x-data="{
                                file: null,
                                fileSize: '',
                                handleFile(event) {
                                    this.file = event.target.files[0];
                                    this.fileSize = (this.file.size / 1024 / 1024).toFixed(2) + ' MB';
                                },
                                removeFile() {
                                    this.file = null;
                                    this.fileSize = '';
                                }
                            }" class="w-full mb-1" name="sertifikat">

                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <span class="text-black font-medium">Upload bukti (Opsional),</span> *wajib jika penilaian <span class="font-semibold text-black">buruk</span>
                        </label>

                        {{-- ================= BEFORE UPLOAD ================= --}}
                        <div x-show="!file">

                            <div
                                class="border-2 border-dashed border-gray-300 rounded-xl h-32 cursor-pointer hover:border-primary transition flex items-center justify-center"
                                @click="$refs.file.click()">

                                <div class="flex flex-col items-center justify-center text-body lg:p-2 p-6">

                                    <img src="{{ asset('assets/icons/cloud-add.png') }}" class="w-8 h-8 lg:mb-4 mb-2">

                                    <p class="lg:text-sm text-xs text-center mb-1">Drag & drop file atau klik untuk upload</p>

                                    <p class="lg:text-xs text-[10px] text-[#B0B0B0]">Format: PDF (Max 10MB)</p>

                                </div>

                            </div>

                        </div>

                        {{-- ================= AFTER UPLOAD ================= --}}
                        <div x-show="file" x-transition class="w-full">

                            <x-card class="relative flex items-center gap-3 w-full h-14 overflow-hidden bg-[#F8F8F8]">

                                {{-- DELETE --}}
                                <button
                                    type="button"
                                    class="absolute top-2 right-2"
                                    @click="removeFile(); $refs.file.value = null">
                                    <img src="{{ asset('assets/icons/delete-icon.png') }}" class="w-4">
                                </button>

                                {{-- ICON --}}
                                <img src="{{ asset('assets/icons/pdf-icon.png') }}" class="w-10 h-10 shrink-0">

                                {{-- INFO --}}
                                <div class="flex-1 min-w-0 pr-6">

                                    {{-- FILE NAME --}}
                                    <p class="text-sm font-medium truncate w-full">
                                        <span x-text="file.name"></span>
                                    </p>

                                    {{-- META --}}
                                    <div class="flex items-center gap-2 mt-1 text-xs flex-wrap">

                                        <span class="text-gray-500 whitespace-nowrap"
                                            x-text="fileSize + ' of ' + fileSize + ' •'">
                                        </span>

                                        <span class="text-black flex items-center gap-1 whitespace-nowrap">
                                            <img src="{{ asset('assets/icons/success-icon.png') }}" class="w-3 h-3">
                                            Selesai
                                        </span>

                                    </div>

                                </div>

                            </x-card>

                        </div>

                        {{-- INPUT (DI LUAR BOX) --}}
                        <input
                            type="file"
                            name="sertifikat"
                            accept=".pdf"
                            class="hidden"
                            x-ref="file"
                            @change="handleFile($event)">
                    </div>
                </div>
                <div class="flex gap-3 lg:mt-6 mt-10">
                    <x-form.button
                        type="button"
                        class="w-full bg-transparent border-2 border-primary !text-primary hover:bg-secondary hover:border-secondary hover:!text-primary"
                        @click="modalType = 'permintaan-keluar'">
                        Kembali
                    </x-form.button>
                    <x-form.button
                        type="button"
                        class="w-full"
                        @click="showSuccess('Permintaan berhasil dikonfirmasi')">
                        Simpan
                    </x-form.button>
                </div>

            </div>
        </template>

        {{-- ================= SUCCESS ================= --}}
        <template x-if="modalType === 'success'">

            <div class="text-center">

                <div class="flex justify-center mb-4">

                    <img
                        src="{{ asset('assets/icons/success-modal-icon.png') }}"
                        class="w-12">

                </div>

                <h2 class="text-lg font-bold">
                    <span x-text="successMessage"></span>
                </h2>

            </div>

        </template>

    </x-modal>
</div>

@endsection