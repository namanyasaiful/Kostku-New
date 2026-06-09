@extends('layouts.pengelola')
@section('title', 'Penghuni')

@section('content')

<div
    x-data="{
    activeTab: 'daftar',

    modalOpen: false,
    modalType: null,
    selectedPenghuni: {},

    successMessage: '',

    openModal(type, data = {}) {
        this.selectedPenghuni = data;
        this.modalOpen = true;
        this.modalType = type;
    },

    closeModal() {
        this.modalOpen = false;
        this.modalType = null;
    },

    showSuccess(message) {
        this.successMessage = message;
        this.modalOpen = true;
        this.modalType = 'success';

        setTimeout(() => {
            this.closeModal();
        }, 2500);
    }
}"
    x-init="
@if(session('success'))
    showSuccess('{{ session('success') }}');
@endif
">

    {{-- ================= PAGE HEADER ================= --}}
    <x-page-header
        title="Data Penghuni"
        description="Daftar penghuni dan kelola permintaan">
    </x-page-header>

    {{-- ================= SEARCH ================= --}}
    <form
        method="GET"
        x-data>
        <x-search-input
            name="search"
            placeholder="Cari nama atau nomor HP"
            :value="request('search')"
            x-on:input.debounce.500ms="$el.form.submit()" />
    </form>

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
                        @if($daftarPenghuni->count())
                        @foreach($daftarPenghuni as $penghuni)
                        <tr class="border-b">

                            <td class="py-4 px-2 text-xs lg:text-sm">
                                {{ $penghuni->user->nama }}
                            </td>

                            <td class="py-4 px-2 text-xs lg:text-sm">
                                {{ $penghuni->user->telpon }}
                            </td>

                            <td class="py-4 px-2 text-xs lg:text-sm">
                                {{ $penghuni->kamar->nomor_kamar }}
                            </td>

                            <td class="py-4 px-2 text-xs lg:text-sm">
                                {{ \Carbon\Carbon::parse($penghuni->tanggal_masuk)->format('d/m/Y') }}
                            </td>

                            <td class="py-4 px-2">
                                <div class="flex justify-center">
                                    <x-form.button @click.prevent="openModal('detail-penghuni', {
                                            name: '{{ $penghuni->user->nama }}',
                                            no_hp: '{{ $penghuni->user->telpon }}',
                                            alamat: '{{ $penghuni->user->alamat ?? '-' }}',
                                            nomor_kamar: '{{ $penghuni->kamar->nomor_kamar }}',
                                            tanggal_masuk: '{{ \Carbon\Carbon::parse($penghuni->tanggal_masuk)->format('d/m/Y') }}',
                                            skor_pembayaran: '{{ optional($penghuni->user->records->last())->skor_pembayaran ?? 'Belum Ada Data' }}',
                                            skor_sikap: '{{ optional($penghuni->user->records->last())->skor_sikap ?? 'Belum Ada Data' }}',
                                            skor_perawatan_fasilitas: '{{ optional($penghuni->user->records->last())->skor_perawatan_fasilitas ?? 'Belum Ada Data' }}',
                                        })" class="!w-24 !p-2 border border-primary bg-transparent !text-primary hover:bg-secondary hover:border-secondary">Detail</x-form.button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        @else

                        <tr>
                            <td colspan="5" class="text-center py-8">
                                Belum ada daftar penghuni
                            </td>
                        </tr>

                        @endif

                    </tbody>

                </table>
            </div>
            <p class="text-xs text-neutral mt-3">Menampilkan {{ $daftarPenghuni->count() }} data</p>

        </div>

        {{-- ================= PERMINTAAN MASUK ================= --}}
        <div
            x-show="activeTab === 'masuk'"
            x-transition>

            <div class="overflow-x-auto overflow-y-auto max-h-[360px]">

                <table class="w-full min-w-[600px] table-fixed">

                    <thead>

                        <tr class="border-b">

                            <th class="w-[40%] text-left py-4 px-2 text-xs lg:text-sm font-semibold">
                                Nama Lengkap
                            </th>

                            <th class="w-[35%] text-left py-4 px-2 text-xs lg:text-sm font-semibold">
                                No HP
                            </th>

                            <th class="w-[25%] text-center py-4 px-2 text-xs lg:text-sm font-semibold">
                                Aksi
                            </th>

                        </tr>

                    </thead>

                    <tbody>
                        @if($permintaanMasuk->count())
                        @foreach($permintaanMasuk as $penghuni)
                        <tr class="border-b">

                            <td class="py-4 px-2 text-xs lg:text-sm">
                                {{ $penghuni->user->nama }}
                            </td>

                            <td class="py-4 px-2 text-xs lg:text-sm">
                                {{ $penghuni->user->telpon }}
                            </td>

                            <td class="py-4 px-2">
                                <div class="flex justify-center gap-2">
                                    <x-form.button @click.prevent="openModal('permintaan-masuk', {
                                    id: {{ $penghuni->id }},
                                    user_id: {{ $penghuni->user_id }},
                                    name: '{{ $penghuni->user->nama }}',
                                    no_hp: '{{ $penghuni->user->telpon }}',
                                    alamat: '{{ $penghuni->user->alamat ?? '-' }}',
                                    skor_pembayaran: '{{ optional($penghuni->user->records->last())->skor_pembayaran ?? 'Belum Ada Data' }}',
                                    skor_sikap: '{{ optional($penghuni->user->records->last())->skor_sikap ?? 'Belum Ada Data' }}',
                                    skor_perawatan_fasilitas: '{{ optional($penghuni->user->records->last())->skor_perawatan_fasilitas ?? 'Belum Ada Data' }}',
                                    })" class="!w-24 !p-2 border border-primary bg-transparent !text-primary hover:bg-secondary hover:border-secondary">Detail</x-form.button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        @else

                        <tr>
                            <td colspan="5" class="text-center py-8">
                                Belum ada permintaan masuk
                            </td>
                        </tr>

                        @endif
                    </tbody>

                </table>

            </div>
            <p class="text-xs text-neutral mt-3">Menampilkan {{ $permintaanMasuk->count() }} data</p>

        </div>

        {{-- ================= PERMINTAAN KELUAR ================= --}}
        <div
            x-show="activeTab === 'keluar'"
            x-transition>

            <div class="overflow-x-auto overflow-y-auto max-h-[360px]">

                <table class="w-full min-w-[600px] table-fixed">

                    <thead>

                        <tr class="border-b">

                            <th class="w-[40%] text-left py-4 px-2 text-xs lg:text-sm font-semibold">
                                Nama Lengkap
                            </th>

                            <th class="w-[35%] text-left py-4 px-2 text-xs lg:text-sm font-semibold">
                                No HP
                            </th>

                            <th class="w-[25%] text-center py-4 px-2 text-xs lg:text-sm font-semibold">
                                Aksi
                            </th>

                        </tr>

                    </thead>

                    <tbody>
                        @if($permintaanKeluar->count())
                        @foreach($permintaanKeluar as $penghuni)
                        <tr class="border-b">

                            <td class="py-4 px-2 text-xs lg:text-sm">
                                {{ $penghuni->user->nama }}
                            </td>

                            <td class="py-4 px-2 text-xs lg:text-sm">
                                {{ $penghuni->user->telpon}}
                            </td>

                            <td class="py-4 px-2">
                                <div class="flex justify-center">
                                    <x-form.button @click.prevent="openModal('permintaan-keluar', {
                                            id: {{ $penghuni->id }},
                                            name: '{{ $penghuni->user->nama }}',
                                            no_hp: '{{ $penghuni->user->telpon }}',
                                            alamat: '{{ $penghuni->user->alamat }}',
                                            notes: '{{ $penghuni->notes_penghuni }}',
                                        })" class="!w-24 !p-2 border border-primary bg-transparent !text-primary hover:bg-secondary hover:border-secondary">Detail</x-form.button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        @else

                        <tr>
                            <td colspan="5" class="text-center py-8">
                                Belum ada permintaan keluar
                            </td>
                        </tr>

                        @endif
                    </tbody>

                </table>
            </div>
            <p class="text-xs text-neutral mt-3">Menampilkan {{ $permintaanKeluar->count() }} data</p>

        </div>

    </div>

    {{-- ================= PAGINATION ================= --}}
    <div x-show="activeTab === 'daftar'">
        <x-pagination :paginator="$daftarPenghuni" />
    </div>
    <div x-show="activeTab === 'masuk'">
        <x-pagination :paginator="$permintaanMasuk" />
    </div>
    <div x-show="activeTab === 'keluar'">
        <x-pagination :paginator="$permintaanKeluar" />
    </div>

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
                            <p class="text-xs font-medium" x-text="selectedPenghuni.name"></p>
                        </div>

                        <div>
                            <p class="text-xs text-neutral mb-1">No HP</p>
                            <p class="text-xs font-medium" x-text="selectedPenghuni.no_hp"></p>
                        </div>
                    </div>
                    <hr>

                    <div class="my-2">
                        <p class="text-xs text-neutral mb-1">Alamat</p>
                        <p class="text-xs font-medium" x-text="selectedPenghuni.alamat"></p>
                    </div>

                    <div class="flex gap-28">
                        <div>
                            <p class="text-xs text-neutral mb-1">Kamar</p>
                            <p class="text-xs font-medium" x-text="selectedPenghuni.nomor_kamar"></p>
                        </div>

                        <div>
                            <p class="text-xs text-neutral mb-1">Tanggal Masuk</p>
                            <p class="text-xs font-medium" x-text="selectedPenghuni.tanggal_masuk"></p>
                        </div>
                    </div>
                    <hr>

                    <div class="flex flex-col gap-4">
                        <div class="w-full flex justify-between">
                            <p class="text-sm font-medium text-primary">Penilaian Penghuni</p>
                        </div>
                        <div class="w-full flex justify-between">
                            <p class="text-xs text-neutral">Pembayaran</p>

                            <template x-if="selectedPenghuni.skor_pembayaran == 'Baik'">
                                <x-badge type="success">Baik</x-badge>
                            </template>

                            <template x-if="selectedPenghuni.skor_pembayaran == 'Perlu Perhatian'">
                                <x-badge type="warning">Perlu Perhatian</x-badge>
                            </template>

                            <template x-if="selectedPenghuni.skor_pembayaran == 'Buruk'">
                                <x-badge type="danger">Buruk</x-badge>
                            </template>
                            <template x-if="selectedPenghuni.skor_sikap == 'Belum Ada Data'">
                                <span class="text-xs text-gray-500 italic">
                                    Belum Ada Penilaian
                                </span>
                            </template>
                        </div>
                        <div class="w-full flex justify-between">
                            <p class="text-xs text-neutral">Sikap</p>

                            <template x-if="selectedPenghuni.skor_sikap == 'Baik'">
                                <x-badge type="success">Baik</x-badge>
                            </template>

                            <template x-if="selectedPenghuni.skor_sikap == 'Perlu Perhatian'">
                                <x-badge type="warning">Perlu Perhatian</x-badge>
                            </template>

                            <template x-if="selectedPenghuni.skor_sikap == 'Buruk'">
                                <x-badge type="danger">Buruk</x-badge>
                            </template>
                            <template x-if="selectedPenghuni.skor_sikap == 'Belum Ada Data'">
                                <span class="text-xs text-gray-500 italic">
                                    Belum Ada Penilaian
                                </span>
                            </template>
                        </div>
                        <div class="w-full flex justify-between">
                            <p class="text-xs text-neutral">Perawatan Fasilitas</p>

                            <template x-if="selectedPenghuni.skor_perawatan_fasilitas == 'Baik'">
                                <x-badge type="success">Baik</x-badge>
                            </template>

                            <template x-if="selectedPenghuni.skor_perawatan_fasilitas == 'Perlu Perhatian'">
                                <x-badge type="warning">Perlu Perhatian</x-badge>
                            </template>

                            <template x-if="selectedPenghuni.skor_perawatan_fasilitas == 'Buruk'">
                                <x-badge type="danger">Buruk</x-badge>
                            </template>
                            <template x-if="selectedPenghuni.skor_sikap == 'Belum Ada Data'">
                                <span class="text-xs text-gray-500 italic">
                                    Belum Ada Penilaian
                                </span>
                            </template>
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
                            <p class="text-xs font-medium" x-text="selectedPenghuni.name"></p>
                        </div>

                        <div>
                            <p class="text-xs text-neutral mb-1">No HP</p>
                            <p class="text-xs font-medium" x-text="selectedPenghuni.no_hp"></p>
                        </div>
                    </div>
                    <hr>

                    <div class="my-2">
                        <p class="text-xs text-neutral mb-1">Alamat</p>
                        <p class="text-xs font-medium" x-text="selectedPenghuni.alamat"></p>
                    </div>
                    <hr>
                    <div class="flex flex-col gap-4">
                        <div class="w-full flex justify-between">
                            <p class="text-sm font-medium text-primary">Penilaian Penghuni</p>
                            <a :href="'/pengelola/riwayat-penilaian-penghuni/' + selectedPenghuni.user_id" class="text-xs no-underline text-neutral hover:underline">Lihat selengkapnya</a>
                        </div>
                        <div class="w-full flex justify-between">
                            <p class="text-xs text-neutral">Pembayaran</p>

                            <template x-if="selectedPenghuni.skor_pembayaran == 'Baik'">
                                <x-badge type="success">Baik</x-badge>
                            </template>

                            <template x-if="selectedPenghuni.skor_pembayaran == 'Perlu Perhatian'">
                                <x-badge type="warning">Perlu Perhatian</x-badge>
                            </template>

                            <template x-if="selectedPenghuni.skor_pembayaran == 'Buruk'">
                                <x-badge type="danger">Buruk</x-badge>
                            </template>
                            <template x-if="selectedPenghuni.skor_sikap == 'Belum Ada Data'">
                                <span class="text-xs text-gray-500 italic">
                                    Belum Ada Penilaian
                                </span>
                            </template>
                        </div>
                        <div class="w-full flex justify-between">
                            <p class="text-xs text-neutral">Sikap</p>

                            <template x-if="selectedPenghuni.skor_sikap == 'Baik'">
                                <x-badge type="success">Baik</x-badge>
                            </template>

                            <template x-if="selectedPenghuni.skor_sikap == 'Perlu Perhatian'">
                                <x-badge type="warning">Perlu Perhatian</x-badge>
                            </template>

                            <template x-if="selectedPenghuni.skor_sikap == 'Buruk'">
                                <x-badge type="danger">Buruk</x-badge>
                            </template>
                            <template x-if="selectedPenghuni.skor_sikap == 'Belum Ada Data'">
                                <span class="text-xs text-gray-500 italic">
                                    Belum Ada Penilaian
                                </span>
                            </template>
                        </div>
                        <div class="w-full flex justify-between">
                            <p class="text-xs text-neutral">Perawatan Fasilitas</p>

                            <template x-if="selectedPenghuni.skor_perawatan_fasilitas == 'Baik'">
                                <x-badge type="success">Baik</x-badge>
                            </template>

                            <template x-if="selectedPenghuni.skor_perawatan_fasilitas == 'Perlu Perhatian'">
                                <x-badge type="warning">Perlu Perhatian</x-badge>
                            </template>

                            <template x-if="selectedPenghuni.skor_perawatan_fasilitas == 'Buruk'">
                                <x-badge type="danger">Buruk</x-badge>
                            </template>
                            <template x-if="selectedPenghuni.skor_sikap == 'Belum Ada Data'">
                                <span class="text-xs text-gray-500 italic">
                                    Belum Ada Penilaian
                                </span>
                            </template>
                        </div>
                    </div>
                </div>

                <div class="flex gap-3 mt-8">
                    <x-form.button
                        type="button"
                        class="w-full text-white !bg-red-600 hover:!bg-red-100 hover:!text-red-600"
                        @click="modalType = 'confirm-tolak'">

                        Tolak
                    </x-form.button>
                    <x-form.button
                        type="submit"
                        class="w-full text-white !bg-green-600 hover:!bg-green-100 hover:!text-[#5BBA43]"
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
                        class="w-full !text-neutral !bg-transparent border-2 !border-neutral hover:!bg-neutral hover:!text-white"
                        @click="modalType = 'permintaan-masuk'">
                        Batal
                    </x-form.button>
                    <x-form.button
                        type="submit"
                        class="w-full !text-white !bg-red-600 hover:!bg-red-100 hover:!text-red-600"
                        @click="$refs.formRejectMasuk.submit()">
                        Tolak
                    </x-form.button>
                    <form
                        x-ref="formRejectMasuk"
                        :action="'/pengelola/penghuni-pengelola/reject-masuk/' + selectedPenghuni.id"
                        method="POST"
                        class="hidden">
                        @csrf
                    </form>

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
                    Pilih Kamar Untuk Penghuni
                </h2>

                <div class="space-y-4">

                    <div class="flex lg:gap-28 gap-20">
                        <div>
                            <p class="text-xs text-neutral mb-1">Nama Lengkap</p>
                            <p class="text-xs font-medium" x-text="selectedPenghuni.name"></p>
                        </div>

                        <div>
                            <p class="text-xs text-neutral mb-1">No HP</p>
                            <p class="text-xs font-medium" x-text="selectedPenghuni.no_hp"></p>
                        </div>
                    </div>
                    <hr>

                    <div class="my-2">
                        <p class="text-xs text-neutral mb-1">Alamat</p>
                        <p class="text-xs font-medium" x-text="selectedPenghuni.alamat"></p>
                    </div>
                    <hr>

                    <form :action="'/pengelola/penghuni-pengelola/approve/' + selectedPenghuni.id" method="POST">
                        @csrf
                        @if($kamarKosong->count() > 0)

                        <x-form.select
                            label="Pilih Kamar"
                            name="nomor_kamar"
                            x-model="selectedPenghuni.requested_kamar_id"
                            class="!bg-[#F8F8F8]">

                            @foreach($kamarKosong as $kamar)
                            <option value="{{ $kamar->id }}">
                                {{ $kamar->nomor_kamar }}
                            </option>
                            @endforeach

                        </x-form.select>

                        @else

                        <div class="p-3 bg-red-50 border border-red-200 rounded-md">
                            <p class="text-sm text-red-600">
                                Tidak ada kamar kosong yang tersedia.
                            </p>
                        </div>

                        @endif

                        <div class="flex gap-3 pt-2 mt-4">
                            <x-form.button
                                type="button"
                                class="w-full bg-transparent border-2 border-primary !text-primary hover:bg-secondary hover:border-secondary hover:!text-primary"
                                @click="modalType = 'permintaan-masuk'">
                                Kembali
                            </x-form.button>
                            @if($kamarKosong->count() > 0)

                            <x-form.button
                                type="submit"
                                class="w-full">
                                Simpan
                            </x-form.button>

                            @else

                            <x-form.button
                                type="button"
                                class="w-full"
                                @click="modalType = 'cannot-approve'">
                                Simpan
                            </x-form.button>

                            @endif
                        </div>
                    </form>
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
                            <p class="text-xs font-medium" x-text="selectedPenghuni.name"></p>
                        </div>

                        <div>
                            <p class="text-xs text-neutral mb-1">No HP</p>
                            <p class="text-xs font-medium" x-text="selectedPenghuni.no_hp"></p>
                        </div>

                    </div>
                    <hr>
                    <div>
                        <p class="text-xs text-neutral mb-1">Alamat</p>
                        <p class="text-xs font-medium" x-text="selectedPenghuni.alamat"></p>
                    </div>
                    <hr>
                    <x-form.textarea
                        label="Alasan Keluar"
                        x-model="selectedPenghuni.notes"
                        rows="3"
                        class="text-xs"
                        readonly>
                        Sudah selesai masa kuliah
                    </x-form.textarea>

                    <div class="flex gap-3 mt-8">
                        <x-form.button
                            type="button"
                            class="w-full !text-white !bg-red-600 hover:!bg-red-100 hover:!text-red-600"
                            @click="modalType = 'confirm-tolak-keluar'">
                            Tolak
                        </x-form.button>
                        <x-form.button
                            type="button"
                            class="w-full !text-white !bg-green-600 hover:!bg-green-100 hover:!text-[#5BBA43]"
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
                        class="w-full bg-transparent border-2 !border-neutral !text-neutral hover:!bg-neutral hover:!text-white"
                        @click="modalType = 'permintaan-keluar'">
                        Batal
                    </x-form.button>

                    <x-form.button
                        type="button"
                        class="w-full text-white !bg-red-600 hover:!bg-red-100 hover:!text-red-600"
                        @click="$refs.formRejectKeluar.submit()">
                        Ya
                    </x-form.button>
                    <form
                        x-ref="formRejectKeluar"
                        :action="'/pengelola/penghuni-pengelola/reject-keluar/' + selectedPenghuni.id"
                        method="POST"
                        class="hidden">
                        @csrf
                    </form>

                </div>

            </div>

        </template>

        {{-- ================= SETUJUI PENGHUNI KELUAR ================= --}}
        <template x-if="modalType === 'setujui-keluar'">
            <form
                x-data="{
        skor_pembayaran:'',
        skor_sikap:'',
        skor_perawatan_fasilitas:'',
        catatan:'',

        errors:{},

        validate(){
            this.errors = {};

            if(!this.skor_pembayaran){
                this.errors.skor_pembayaran = 'Ketertiban pembayaran wajib dipilih';
            }

            if(!this.skor_sikap){
                this.errors.skor_sikap = 'Sikap wajib dipilih';
            }

            if(!this.skor_perawatan_fasilitas){
                this.errors.skor_perawatan_fasilitas = 'Perawatan fasilitas wajib dipilih';
            }

            if(!this.catatan.trim()){
                this.errors.catatan = 'Catatan wajib diisi';
            }

            return Object.keys(this.errors).length === 0;
        }
    }"
                :action="'/pengelola/penghuni-pengelola/keluar/' + selectedPenghuni.id"
                method="POST"
                enctype="multipart/form-data">

                @csrf

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
                                <p class="text-xs font-medium" x-text="selectedPenghuni.name"></p>
                            </div>

                            <div>
                                <p class="text-xs text-neutral mb-1">No HP</p>
                                <p class="text-xs font-medium" x-text="selectedPenghuni.no_hp"></p>
                            </div>
                        </div>

                        <hr>

                        <div class="my-2">
                            <p class="text-xs text-neutral mb-1">Alamat</p>
                            <p class="text-xs font-medium" x-text="selectedPenghuni.alamat"></p>
                        </div>

                        <hr>

                        {{-- Ketertiban Pembayaran --}}
                        <x-form.select
                            label="Ketertiban Pembayaran"
                            name="skor_pembayaran"
                            x-model="skor_pembayaran"
                            class="!bg-[#F8F8F8] text-xs">
                            <option value="">Pilih Penilaian</option>
                            <option value="Baik">Baik</option>
                            <option value="Perlu Perhatian">Perlu Perhatian</option>
                            <option value="Buruk">Buruk</option>
                        </x-form.select>
                        <p
                            x-show="errors.skor_pembayaran"
                            x-text="errors.skor_pembayaran"
                            class="text-red-500 text-xs mt-1">
                        </p>

                        {{-- Sikap --}}
                        <x-form.select
                            label="Sikap"
                            name="skor_sikap"
                            x-model="skor_sikap"
                            class="!bg-[#F8F8F8] text-xs">
                            <option value="">Pilih Penilaian</option>
                            <option value="Baik">Baik</option>
                            <option value="Perlu Perhatian">Perlu Perhatian</option>
                            <option value="Buruk">Buruk</option>
                        </x-form.select>
                        <p
                            x-show="errors.skor_sikap"
                            x-text="errors.skor_sikap"
                            class="text-red-500 text-xs mt-1">
                        </p>

                        {{-- Perawatan Fasilitas --}}
                        <x-form.select
                            label="Perawatan Fasilitas"
                            name="skor_perawatan_fasilitas"
                            x-model="skor_perawatan_fasilitas"
                            class="!bg-[#F8F8F8] text-xs">
                            <option value="">Pilih Penilaian</option>
                            <option value="Baik">Baik</option>
                            <option value="Perlu Perhatian">Perlu Perhatian</option>
                            <option value="Buruk">Buruk</option>
                        </x-form.select>
                        <p
                            x-show="errors.skor_perawatan_fasilitas"
                            x-text="errors.skor_perawatan_fasilitas"
                            class="text-red-500 text-xs mt-1">
                        </p>

                        {{-- Catatan --}}
                        <x-form.input
                            label="Catatan Tambahan"
                            name="catatan"
                            type="text"
                            x-model="catatan"
                            class="!p-4 bg-[#F8F8F8] text-xs"
                            placeholder="Tuliskan catatan tambahan jika ada" />
                        <p
                            x-show="errors.catatan"
                            x-text="errors.catatan"
                            class="text-red-500 text-xs mt-1">
                        </p>

                        {{-- Upload Bukti --}}
                        <div
                            x-data="{
                        file: null,
                        fileSize: '',

                        handleFile(event) {
                            this.file = event.target.files[0];

                            if(this.file){
                                this.fileSize = (this.file.size / 1024 / 1024).toFixed(2) + ' MB';
                            }
                        },

                        removeFile() {
                            this.file = null;
                            this.fileSize = '';
                        }
                    }"
                            class="w-full mb-1">

                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <span class="text-black font-medium">
                                    Upload bukti (Opsional)
                                </span>
                            </label>

                            {{-- BEFORE UPLOAD --}}
                            <div x-show="!file">

                                <div
                                    class="border-2 border-dashed border-gray-300 rounded-xl h-32 cursor-pointer hover:border-primary transition flex items-center justify-center"
                                    @click="$refs.file.click()">

                                    <div class="flex flex-col items-center justify-center text-body lg:p-2 p-6">

                                        <img
                                            src="{{ asset('assets/icons/cloud-add.png') }}"
                                            class="w-8 h-8 lg:mb-4 mb-2">

                                        <p class="lg:text-sm text-xs text-center mb-1">
                                            Drag & drop file atau klik untuk upload
                                        </p>

                                        <p class="lg:text-xs text-[10px] text-[#B0B0B0]">
                                            Format: PDF (Max 10MB)
                                        </p>

                                    </div>

                                </div>

                            </div>

                            {{-- AFTER UPLOAD --}}
                            <div
                                x-show="file"
                                x-transition
                                class="w-full">

                                <x-card class="relative flex items-center gap-3 w-full h-14 overflow-hidden bg-[#F8F8F8]">

                                    <button
                                        type="button"
                                        class="absolute top-2 right-2"
                                        @click="removeFile(); $refs.file.value = null">

                                        <img
                                            src="{{ asset('assets/icons/delete-icon.png') }}"
                                            class="w-4">

                                    </button>

                                    <img
                                        src="{{ asset('assets/icons/pdf-icon.png') }}"
                                        class="w-10 h-10 shrink-0">

                                    <div class="flex-1 min-w-0 pr-6">

                                        <p class="text-sm font-medium truncate w-full">
                                            <span x-text="file?.name"></span>
                                        </p>

                                        <div class="flex items-center gap-2 mt-1 text-xs flex-wrap">

                                            <span
                                                class="text-gray-500 whitespace-nowrap"
                                                x-text="fileSize">
                                            </span>

                                            <span class="text-black flex items-center gap-1 whitespace-nowrap">

                                                <img
                                                    src="{{ asset('assets/icons/success-icon.png') }}"
                                                    class="w-3 h-3">

                                                Selesai

                                            </span>

                                        </div>

                                    </div>

                                </x-card>

                            </div>

                            {{-- INPUT FILE --}}
                            <input
                                type="file"
                                name="bukti"
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
                            @click="
        if(validate()){
            $el.closest('form').submit();
        }
    ">
                            Simpan
                        </x-form.button>

                    </div>

                </div>

            </form>
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

        {{-- ================= CANNOT APPROVE KAMAR KOSONG ================= --}}
        <template x-if="modalType === 'cannot-approve'">

            <div class="relative lg:px-2 px-1 pt-2 pb-1">

                <button
                    type="button"
                    @click="closeModal()"
                    class="absolute top-0 right-0 w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100">

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
                            Tidak dapat menambahkan penghuni
                        </h2>

                        <p class="lg:text-sm text-xs text-neutral leading-relaxed">
                            Tidak ada kamar kosong yang tersedia untuk penghuni ini.
                        </p>

                    </div>

                </div>

            </div>

        </template>

    </x-modal>
</div>

@endsection