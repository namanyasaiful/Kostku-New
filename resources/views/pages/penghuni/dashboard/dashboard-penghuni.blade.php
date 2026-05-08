@extends('layouts.penghuni')
@section('title', 'Dashboard Penghuni')

@section('content')

<!-- cara cek section, ubah statusnya dengan berikut: -->
<!-- not_joined, pending, joined -->

<div
    x-data="{
        status: 'not_joined',
        modalOpen: false,
        modalType: null,

        openModal(type, duration = 2500) {
            this.modalOpen = true;
            this.modalType = type;

            setTimeout(() => {
                this.modalOpen = false;
                this.modalType = null;
            }, duration)
        }
    }">

    {{-- ================= PAGE HEADER ================= --}}
    <x-page-header
        title="Dashboard"
        description="Pantau informasi kost dan aktivitas Anda">

        <x-form.button
            type="button"
            x-show="status === 'not_joined'"
            @click="
                modalOpen = true;
                modalType = 'join-kost';
            ">

            Gabung Kost

        </x-form.button>

    </x-page-header>


    {{-- ====================================================== --}}
    {{-- ================= BELUM JOIN KOST ==================== --}}
    {{-- ====================================================== --}}
    <template x-if="status === 'not_joined'">

        <div class="flex flex-col gap-6">

            {{-- PROFILE CARD --}}
            @include('pages.penghuni.dashboard.sections.profile-card')

            {{-- JOIN KOST CARD --}}
            @include('pages.penghuni.dashboard.sections.join-kost-card')

        </div>

    </template>


    {{-- ====================================================== --}}
    {{-- ================= MENUNGGU VERIFIKASI ================ --}}
    {{-- ====================================================== --}}
    <template x-if="status === 'pending'">

        <div class="flex flex-col gap-6">

            {{-- PROFILE CARD --}}
            @include('pages.penghuni.dashboard.sections.profile-card')

            {{-- PENDING CARD --}}
            @include('pages.penghuni.dashboard.sections.pending-card')

        </div>

    </template>


    {{-- ====================================================== --}}
    {{-- ================= SUDAH JOIN ========================= --}}
    {{-- ====================================================== --}}
    <template x-if="status === 'joined'">

        <div class="space-y-6">

            {{-- TOP CARD --}}
            <div class="grid lg:grid-cols-2 gap-6">

                {{-- PROFILE CARD --}}
                @include('pages.penghuni.dashboard.sections.profile-card')

                {{-- KOST INFO CARD --}}
                @include('pages.penghuni.dashboard.sections.kost-info-card')

            </div>

            {{-- LEAVE KOST CARD --}}
            @include('pages.penghuni.dashboard.sections.leave-kost-card')

        </div>

    </template>


    {{-- ====================================================== --}}
    {{-- ================= MODAL ============================== --}}
    {{-- ====================================================== --}}
    <x-modal show="modalOpen" maxWidth="lg:max-w-[450px] max-w-[350px]">

        {{-- ====================================================== --}}
        {{-- ================= JOIN KOST MODAL ==================== --}}
        {{-- ====================================================== --}}
        <template x-if="modalType === 'join-kost'">

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

                <h2 class="text-xl font-bold mb-4">
                    Gabung Kost
                </h2>

                <div class="mb-1">

                    <x-form.input
                        label="Kode Kost"
                        name="kode_kost"
                        placeholder="Masukkan kode kost"
                        class="text-black" />

                </div>

                <p class="text-xs text-neutral mb-6">
                    Hubungi pengelola kost untuk mendapatkan kode
                </p>

                <x-form.button
                    type="button"
                    class="w-full"
                    @click="
                        status = 'pending';
                        openModal('pending-success');
                    ">

                    Gabung Sekarang

                </x-form.button>

            </div>

        </template>


        {{-- ====================================================== --}}
        {{-- ================= SUCCESS PENDING ==================== --}}
        {{-- ====================================================== --}}
        <template x-if="modalType === 'pending-success'">

            <div class="text-center">

                <div class="flex justify-center mb-4">

                    <div class="w-20 h-20 flex items-center justify-center">

                        <img
                            src="{{ asset('assets/icons/success-modal-icon.png') }}"
                            class="w-12">

                    </div>

                </div>

                <h2 class="lg:text-xl text-md font-bold mb-2">
                    Pengajuan berhasil dikirim!
                </h2>

                <p class="lg:text-sm text-xs text-neutral">
                    Menunggu persetujuan dari pemilik kost.
                </p>

            </div>

        </template>


        {{-- ====================================================== --}}
        {{-- ================= LOADING MODAL ====================== --}}
        {{-- ====================================================== --}}
        <template x-if="modalType === 'loading-join'">

            <div class="text-center">

                <div class="flex justify-center mb-4">

                    <div class="w-14 h-14 border-4 border-gray-200 border-t-primary rounded-full animate-spin"></div>

                </div>

                <h2 class="lg:text-xl text-md font-bold mb-2">
                    Memproses Persetujuan
                </h2>

                <p class="lg:text-sm text-xs text-neutral">
                    Sedang menghubungkan penghuni ke kost
                </p>

            </div>

        </template>


        {{-- ====================================================== --}}
        {{-- ================= SUCCESS JOIN ======================= --}}
        {{-- ====================================================== --}}
        <template x-if="modalType === 'joined-success'">

            <div class="text-center">

                <div class="flex justify-center mb-4">

                    <div class="w-20 h-20 flex items-center justify-center">

                        <img
                            src="{{ asset('assets/icons/success-modal-icon.png') }}"
                            class="w-12">

                    </div>

                </div>

                <h2 class="lg:text-xl text-md font-bold mb-2">
                    Selamat bergabung di Kost!
                </h2>

            </div>

        </template>

    </x-modal>

</div>

@endsection