@extends('layouts.penghuni')
@section('title', 'Dashboard Penghuni')

@section('content')
@php
$penghuni = \App\Models\Penghuni::where('user_id', Auth::id())
->latest()
->first();

$status = 'not_joined';
$leaveStatus = 'none';

if ($penghuni) {

if ($penghuni->status_request === 'menunggu') {

$status = 'pending';

} elseif (
$penghuni->status_request === 'disetujui'
&& !is_null($penghuni->tanggal_keluar)
) {

$status = 'leave_pending';

} elseif ($penghuni->status_request === 'disetujui') {

$status = 'joined';
}
}
@endphp

<div x-data="{
    status: '{{ $status }}',
    leaveStatus: '{{ $leaveStatus }}',

    kodeKost: '',
    kodeKostError: '',

    kostInfo: {
    id: '',
    nama_kost: '',
    alamat: '',
    pemilik: ''
    },

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

    @if(session('join_success'))
        this.modalOpen = true;
        this.modalType = 'pending-success';

        setTimeout(() => {
            this.closeModal();
        }, 2500);
    @endif

    @if(session('leave_success'))
        this.modalOpen = true;
        this.modalType = 'leave-kost-success';

        setTimeout(() => {
            this.closeModal();
        }, 2500);
    @endif

}
}">

    {{-- ================= PAGE HEADER ================= --}}
    <x-page-header
        title="Selamat Datang, Saifulloh Fattah">
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
            {{-- JOIN KOST CARD --}}
            @include('pages.penghuni.dashboard.sections.join-kost-card')

            {{-- INFORMATION CARD --}}
            @include('pages.penghuni.dashboard.sections.information-card')
        </div>

    </template>


    {{-- ====================================================== --}}
    {{-- ================= MENUNGGU VERIFIKASI ================ --}}
    {{-- ====================================================== --}}
    <template x-if="status === 'pending'">

        <div class="flex flex-col gap-6">
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
                @include('pages.penghuni.dashboard.sections.kost-info-card')

                <template x-if="leaveStatus === 'none'">
                    @include('pages.penghuni.dashboard.sections.leave-kost-card')
                </template>

                <template x-if="leaveStatus === 'pending'">
                    @include('pages.penghuni.dashboard.sections.leave-pending-card')
                </template>
            </div>
        </div>

    </template>

    {{-- ====================================================== --}}
    {{-- ================= LEAVE PENDING ====================== --}}
    {{-- ====================================================== --}}
    <template x-if="status === 'leave_pending'">

        <div class="space-y-6">

            <div class="grid lg:grid-cols-2 gap-6">

                @include('pages.penghuni.dashboard.sections.kost-info-card')

                @include('pages.penghuni.dashboard.sections.leave-pending-card')

            </div>

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

                <form>
                    @csrf
                    <div class="mb-1">
                        <x-form.input
                            x-model="kodeKost"
                            label="Kode Kost"
                            name="kode_kost"
                            placeholder="Masukkan kode kost"
                            class="text-black"
                            required />
                        <p
                            x-show="kodeKostError"
                            x-text="kodeKostError"
                            class="text-red-500 text-xs mt-1">
                        </p>
                    </div>

                    <p class="text-xs text-neutral mb-6">
                        Hubungi pengelola kost untuk mendapatkan kode
                    </p>

                    <x-form.button
                        type="button"
                        class="w-full"
                        @click="
                            if(!kodeKost.trim()){
                                kodeKostError = 'Kode kost wajib diisi.';
                                return;
                            }

                            kodeKostError = '';

                            fetch('{{ route('penghuni.validasi-kost') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    kode_kost: kodeKost
                                })
                            })
                            .then(res => res.json())
                            .then(data => {

                                if(data.success){

                                    kostInfo = data.data;
                                    modalType = 'informasi-kost';

                                } else {

                                    kodeKostError = data.message;

                                }

                            });
                        ">
                        Validasi Kode
                    </x-form.button>
                </form>

            </div>

        </template>

        {{-- ====================================================== --}}
        {{-- ================= INFORMATION KOST MODAL ==================== --}}
        {{-- ====================================================== --}}
        <template x-if="modalType === 'informasi-kost'">

            <div class="relative">

                <button
                    type="button"
                    class="absolute top-0 right-0 text-xl"
                    @click="closeModal()">

                    ✕

                </button>

                <h2 class="text-xl font-bold mb-6">
                    Konfirmasi informasi Kost
                </h2>

                <p class="text-xs text-neutral mb-4">Pastikan informasi kost di bawah ini sudah benar sebelum bergabung</p>

                <div class="space-y-4">

                    <div class="flex lg:gap-28 gap-20">
                        <div>
                            <p class="text-xs text-neutral mb-1">Nama Kost</p>
                            <p class="text-xs font-medium" x-text="kostInfo.nama_kost"></p>
                        </div>

                        <div>
                            <p class="text-xs text-neutral mb-1">Nama Pemilik</p>
                            <p class="text-xs font-medium" x-text="kostInfo.pemilik"></p>
                        </div>
                    </div>
                    <hr>

                    <div class="my-2">
                        <p class="text-xs text-neutral mb-1">Alamat</p>
                        <p class="text-xs font-medium" x-text="kostInfo.alamat"></p>
                    </div>
                    <hr>
                    <div class="flex gap-3 pt-2 mt-4">
                        <x-form.button
                            type="button"
                            class="w-full bg-transparent border-2 !border-neutral !text-neutral hover:!bg-[#E2E2E2] hover:!border-[#E2E2E2] hover:!text-neutral"
                            @click="modalType = 'join-kost'">
                            Kembali
                        </x-form.button>
                        <form
                            action="{{ route('penghuni.join') }}"
                            method="POST" class="w-full">

                            @csrf

                            <input
                                type="hidden"
                                name="kode_kost"
                                :value="kodeKost">

                            <x-form.button
                                type="submit"
                                class="w-full">
                                Gabung Sekarang
                            </x-form.button>

                        </form>
                    </div>
                </div>

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
        {{-- ================= FAILED JOIN ======================= --}}
        {{-- ====================================================== --}}
        <template x-if="modalType === 'pending-failed'">

            <div class="text-center">

                <div class="flex justify-center mb-4">

                    <div class="w-20 h-20 flex items-center justify-center">

                        <img
                            src="{{ asset('assets/icons/failed-modal-icon.png') }}"
                            class="w-12">

                    </div>

                </div>

                <h2 class="lg:text-xl text-md font-bold mb-2">
                    Gagal Bergabung Kost!
                </h2>

                <p class="lg:text-sm text-xs text-neutral">
                    Silakan coba lagi
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

                <form
                    action="{{ route('penghuni.leave') }}"
                    method="POST"
                    x-data="{
                    alasan: '',
                    error: ''}">

                    @csrf

                    <x-form.textarea
                        label="Alasan Keluar"
                        name="alasan_keluar"
                        rows="4"
                        placeholder="Masukkan alasan keluar kost..."
                        class="text-black"
                        x-model="alasan" />

                    <p
                        x-show="error"
                        x-text="error"
                        class="text-red-500 text-xs mt-1">
                    </p>
                    <div class="mt-6">

                        <x-form.button
                            type="button"
                            class="w-full rounded-md !bg-[#E73D2E] !text-white"
                            @click="if(!alasan.trim()){error = 'Alasan keluar wajib diisi.'; return;} error = ''; $el.closest('form').submit();">

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
        {{-- ================= LEAVE APPROVED ===================== --}}
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