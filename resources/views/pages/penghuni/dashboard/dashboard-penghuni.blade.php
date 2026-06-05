@extends('layouts.penghuni')
@section('title', 'Dashboard Penghuni')

@section('content')
@php
// Mengambil data penghuni aktif milik user
$penghuni = \App\Models\Penghuni::where('user_id', Auth::id())
->latest()
->first();

$status = 'not_joined';
$leaveStatus = 'none';

if ($penghuni) {
    if ($penghuni->status_request === 'menunggu') {
        $status = 'pending';
    } elseif ($penghuni->status_request === 'disetujui') {
        $status = 'joined';

        // Jika penghuni sudah minta keluar tapi belum disetujui admin
        if ($penghuni->tanggal_keluar !== null) {
            $leaveStatus = 'pending';
        }

        // Cek apakah kamar sudah dilepaskan oleh admin
        if ($penghuni->kamar && $penghuni->kamar->user_id != Auth::id()) {
            $status = 'not_joined';
        }
    }
}
@endphp

<div x-data="{
    status: '{{ $status }}',
    leaveStatus: '{{ $leaveStatus }}',

    modalOpen: false,
    modalType: null,

    openModal(type){
        this.modalOpen = true;
        this.modalType = type;
    },

    closeModal(){
        this.modalOpen = false;
        this.modalType = null;
    },

    init() {
        @if(session('success'))
            this.modalOpen = true;
            @if(session('success') == 'Permintaan keluar kost telah dikirim ke pengelola.')
                this.modalType = 'leave-kost-success';
            @else
                this.modalType = 'joined-success';
            @endif
            setTimeout(() => {
                this.closeModal();
            }, 2500);
        @endif
    },
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

            <div class="grid lg:grid-cols-2 gap-6">

                @include('pages.penghuni.dashboard.sections.profile-card')

                @include('pages.penghuni.dashboard.sections.kost-info-card')

            </div>

            <template x-if="leaveStatus === 'none'">

                @include('pages.penghuni.dashboard.sections.leave-kost-card')

            </template>

            <template x-if="leaveStatus === 'pending'">

                @include('pages.penghuni.dashboard.sections.leave-pending-card')

            </template>

        </div>

    </template>

    {{-- ====================================================== --}}
    {{-- ================= LEAVE PENDING ====================== --}}
    {{-- ====================================================== --}}
    <!-- <template x-if="status === 'leave_pending'">

        <div class="space-y-6">

            <div class="grid lg:grid-cols-2 gap-6">

                @include('pages.penghuni.dashboard.sections.profile-card')

                @include('pages.penghuni.dashboard.sections.kost-info-card')

            </div>

            @include('pages.penghuni.dashboard.sections.leave-pending-card')

        </div>

    </template> -->


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

                <form action="{{ route('penghuni.join') }}" method="POST">
                    @csrf
                    <div class="mb-1">
                        <x-form.input
                            label="Kode Kost"
                            name="kode_kost"
                            placeholder="Masukkan kode kost"
                            class="text-black"
                            required />
                    </div>

                    <p class="text-xs text-neutral mb-6">
                        Hubungi pengelola kost untuk mendapatkan kode
                    </p>

                    <x-form.button
                        type="submit"
                        class="w-full">
                        Gabung Sekarang
                    </x-form.button>
                </form>

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

        {{-- ====================================================== --}}
        {{-- ================= LEAVE KOST ========================= --}}
        {{-- ====================================================== --}}
        <template x-if="modalType === 'leave-kost'">

            <div class="relative">

                {{-- CLOSE --}}
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
                    Selesai Kost
                </h2>

                <form action="{{ route('penghuni.out') }}" method="POST">
                    @csrf

                    <x-form.textarea
                        label="Alasan Keluar"
                        name="alasan_keluar"
                        rows="4"
                        placeholder="Masukkan alasan keluar kost..."
                        class="text-black"
                        required />

                    <div class="mt-6">

                        <x-form.button
                            type="submit"
                            class="w-full rounded-md !bg-[#E73D2E] !text-white lg:!text-md !text-sm !font-medium hover:!bg-[#FFC5BF] hover:!text-[#E73D2E] !transition"
                            >
                            Ajukan Permintaan
                        </x-form.button>

                    </div>

                </form>

            </div>

        </template>

        {{-- ====================================================== --}}
        {{-- ================= SUCCESS LEAVE ====================== --}}
        {{-- ====================================================== --}}
        <template x-if="modalType === 'leave-kost-success'">

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
        {{-- ================= LEAVE APPROVED ====================== --}}
        {{-- ====================================================== --}}
        <template x-if="modalType === 'leave-approved'">

            <div class="text-center">

                <div class="flex justify-center mb-4">

                    <div class="w-20 h-20 flex items-center justify-center">

                        <img
                            src="{{ asset('assets/icons/success-modal-icon.png') }}"
                            class="w-12">

                    </div>

                </div>

                <h2 class="lg:text-xl text-md font-bold mb-2">
                    Pengajuan sudah disetujui pengelola
                </h2>

                <p class="lg:text-sm text-xs text-neutral">
                    Sampai jumpa kembali di lain kost!
                </p>

            </div>

        </template>
    </x-modal>

</div>

@endsection
