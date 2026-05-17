@extends('layouts.pengelola')
@section('title', 'Pengaduan Pengelola')

@section('content')

<div
    x-data="{
        modalOpen: false,
        modalType: null,

        status: 'baru',

        selectedStatus: 'diproses',

        openModal(type) {
            this.modalOpen = true;
            this.modalType = type;
        },

        savePengaduan() {

            this.status = this.selectedStatus;

            this.modalType = 'kirim-success';

            setTimeout(() => {
                this.modalOpen = false;
                this.modalType = null;
            }, 2500);
        }
    }">

    {{-- ================= PAGE HEADER ================= --}}
    <x-page-header
        title="Pengaduan Penghuni"
        description="Kelola dan respon pengaduan">
    </x-page-header>

    {{-- ================= TABLE ================= --}}
    <x-card class="mb-6">

        <x-table.index class="min-w-[700px]">

            <thead class="sticky top-0 bg-white z-10 border-default">

                <x-table.tr>

                    <x-table.th>
                        Nama Lengkap
                    </x-table.th>

                    <x-table.th>
                        Kamar
                    </x-table.th>

                    <x-table.th>
                        Tanggal Pengaduan
                    </x-table.th>

                    <x-table.th>
                        Status
                    </x-table.th>

                    <x-table.th>
                        Detail Pengaduan
                    </x-table.th>

                </x-table.tr>

            </thead>

            <tbody>

                <x-table.tr>

                    <x-table.td class="font-medium text-heading">
                        Anto Subagja
                    </x-table.td>

                    <x-table.td class="font-medium text-heading">
                        KM001
                    </x-table.td>

                    <x-table.td class="font-medium text-heading">
                        08/04/2026
                    </x-table.td>

                    {{-- ================= STATUS BADGE ================= --}}
                    <x-table.td>

                        {{-- BARU --}}
                        <template x-if="status === 'baru'">

                            <x-badge type="danger">
                                Baru
                            </x-badge>

                        </template>

                        {{-- DIPROSES --}}
                        <template x-if="status === 'diproses'">

                            <x-badge type="warning">
                                Diproses
                            </x-badge>

                        </template>

                        {{-- SELESAI --}}
                        <template x-if="status === 'selesai'">

                            <x-badge type="success">
                                Selesai
                            </x-badge>

                        </template>

                    </x-table.td>

                    {{-- ================= DETAIL BUTTON ================= --}}
                    <x-table.td>

                        <a
                            href="#"
                            @click.prevent="openModal('detail-pengaduan')"
                            class="w-28 flex justify-center cursor-pointer">

                            <img
                                src="{{ asset('assets/icons/lihat-detail-icon.png') }}"
                                alt="Lihat Detail"
                                class="w-4 h-4">

                        </a>

                    </x-table.td>

                </x-table.tr>

            </tbody>

        </x-table.index>

    </x-card>

    {{-- ================= PAGINATION ================= --}}
    <x-pagination />

    {{-- ================= MODAL ================= --}}
    <x-modal show="modalOpen" maxWidth="lg:max-w-[500px] max-w-[350px]">

        {{-- ================= DETAIL PENGADUAN ================= --}}
        <template x-if="modalType === 'detail-pengaduan'">

            <div class="relative">

                {{-- CLOSE BUTTON --}}
                <button
                    type="button"
                    class="
                        absolute top-0 right-0
                        text-neutral hover:text-black
                        text-xl font-bold
                    "
                    @click="
                        modalOpen = false;
                        modalType = null;
                    ">

                    ✕

                </button>

                <h2 class="text-xl font-bold mb-8">
                    Detail Pengaduan
                </h2>

                <div class="flex flex-col gap-4">

                    {{-- CARD PENGADUAN --}}
                    <div class="bg-[#EDF2FF] rounded-md shadow-md px-4 py-5">

                        <h3 class="lg:text-md text-sm text-black font-semibold mb-2">
                            Wifi ngelag
                        </h3>

                        <p class="text-xs text-neutral mb-3">
                            WiFi lambat internetnya sudah dari kemarin
                        </p>

                        <div class="flex justify-around">

                            <p class="text-xs text-neutral">
                                P001 - Anto Subagja
                            </p>

                            <p class="text-xs text-neutral">
                                KM001
                            </p>

                        </div>

                    </div>

                    {{-- TEXTAREA --}}
                    <x-form.textarea
                        label="Respon Anda"
                        name="pengaduan-pengelola"
                        rows="3"
                        placeholder="Tulis respon untuk pengaduan ini" />

                    {{-- SELECT STATUS --}}
                    <x-form.select
                        label="Status"
                        name="status-pengaduan-pengelola"
                        x-model="selectedStatus"
                        class="bg-[#F8F8F8] border-[#E2E2E2]">

                        <option value="diproses">
                            Diproses
                        </option>

                        <option value="selesai">
                            Selesai
                        </option>

                    </x-form.select>

                    {{-- BUTTON --}}
                    <x-form.button
                        type="button"
                        class="w-full mt-4"
                        @click="savePengaduan()">

                        Simpan dan balas pengaduan

                    </x-form.button>

                </div>

            </div>

        </template>

        {{-- ================= SUCCESS MODAL ================= --}}
        <template x-if="modalType === 'kirim-success'">

            <div class="text-center">

                <div class="flex justify-center mb-4">

                    <div class="w-20 h-20 flex items-center justify-center">

                        <img
                            src="{{ asset('assets/icons/success-modal-icon.png') }}"
                            class="w-12">

                    </div>

                </div>

                <h2 class="lg:text-xl text-md font-bold mb-2">

                    <template x-if="status === 'diproses'">
                        <span>
                            Balasan berhasil dikirim
                        </span>
                    </template>

                    <template x-if="status === 'selesai'">
                        <span>
                            Pengaduan berhasil diselesaikan
                        </span>
                    </template>

                </h2>

            </div>

        </template>

    </x-modal>

</div>

@endsection