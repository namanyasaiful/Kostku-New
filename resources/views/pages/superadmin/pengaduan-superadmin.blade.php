@extends('layouts.superadmin')
@section('title', 'Pengaduan - Super Admin')

@section('content')

<div x-data="{
    modalOpen: false,
    modalType: null,

    id: null,
    namaLengkap: '',
    email: '',
    namaKost: '',
    nomorKamar: '',
    judul: '',
    isi: '',
    balasan: '',
    status: '',
    tanggalPengaduan: '',
    bukti: '',

    openModal(type, data = {}) {
        if (data) {
            this.id = data.id || null;
            this.namaLengkap = data.nama_lengkap || '';
            this.email = data.email || '';
            this.namaKost = data.nama_kost || '';
            this.nomorKamar = data.nomor_kamar || '';
            this.judul = data.judul || '';
            this.isi = data.isi || '';
            this.balasan = data.balasan || '';
            this.status = data.status || '';
            this.tanggalPengaduan = data.tanggal_pengaduan || '';
            this.bukti = data.bukti || '';
        }
        this.modalOpen = true;
        this.modalType = type;
    },

    formatStatus(status) {
        if (status === 'baru') return 'Baru';
        if (status === 'proses') return 'Diproses';
        if (status === 'selesai') return 'Selesai';
        return status;
    }
}">

    {{-- ================= PAGE HEADER ================= --}}
    <x-page-header
        title="Pengaduan"
        description="Lihat semua riwayat pengaduan penghuni">
    </x-page-header>

    {{-- ================= SEARCH ================= --}}
    <form method="GET" action="{{ route('pengaduan-superadmin.superadmin') }}">
        <x-search-input
            name="search"
            placeholder="Cari nama, email, atau judul pengaduan..."
            value="{{ $search ?? '' }}" />
    </form>

    {{-- ================= TABLE ================= --}}
    <div class="bg-white rounded-lg p-4 lg:p-6 mt-4 mb-6">
        <div class="overflow-x-auto">
            <x-table.index>
                <thead class="sticky top-0 bg-white z-10 border-b border-default">
                    <x-table.tr>
                        <x-table.th>Nama Lengkap</x-table.th>
                        <x-table.th>Nama Kost</x-table.th>
                        <x-table.th>Judul Pengaduan</x-table.th>
                        <x-table.th>Tanggal Pengaduan</x-table.th>
                        <x-table.th>Status</x-table.th>
                        <x-table.th class="text-center">Aksi</x-table.th>
                    </x-table.tr>
                </thead>
                <tbody>
                    @forelse ($pengaduans as $pengaduan)
                    <x-table.tr>
                        <x-table.td class="font-medium text-heading">
                            {{ $pengaduan->user->nama ?? '-' }}
                        </x-table.td>
                        <x-table.td>
                            {{ $pengaduan->user->penghuni->kamar->kost->nama_kost ?? '-' }}
                        </x-table.td>
                        <x-table.td>
                            {{ Str::limit($pengaduan->judul, 40) }}
                        </x-table.td>
                        <x-table.td>
                            {{ $pengaduan->created_at->format('d/m/Y') }}
                        </x-table.td>
                        <x-table.td>
                            @if ($pengaduan->status === 'baru')
                                <x-badge type="info">Baru</x-badge>
                            @elseif ($pengaduan->status === 'proses')
                                <x-badge type="warning">Diproses</x-badge>
                            @elseif ($pengaduan->status === 'selesai')
                                <x-badge type="success">Selesai</x-badge>
                            @else
                                <x-badge type="secondary">{{ $pengaduan->status }}</x-badge>
                            @endif
                        </x-table.td>
                        <x-table.td class="text-center">
                            <x-form.button
                                type="button"
                                @click="openModal('detail-pengaduan', {
                                    id: '{{ $pengaduan->id }}',
                                    nama_lengkap: '{{ addslashes($pengaduan->user->nama ?? '-') }}',
                                    email: '{{ addslashes($pengaduan->user->email ?? '-') }}',
                                    nama_kost: '{{ addslashes($pengaduan->user->penghuni->kamar->kost->nama_kost ?? '-') }}',
                                    nomor_kamar: '{{ addslashes($pengaduan->user->penghuni->nomor_kamar ?? '-') }}',
                                    judul: '{{ addslashes($pengaduan->judul) }}',
                                    isi: '{{ addslashes($pengaduan->isi) }}',
                                    balasan: '{{ addslashes($pengaduan->balasan ?? '') }}',
                                    status: '{{ $pengaduan->status }}',
                                    tanggal_pengaduan: '{{ $pengaduan->created_at->format('d/m/Y') }}',
                                    bukti: '{{ $pengaduan->bukti_pengaduan ? asset('storage/' . $pengaduan->bukti_pengaduan) : '' }}'
                                })"
                                class="border border-primary bg-transparent !text-primary hover:bg-secondary hover:border-secondary">
                                Detail
                            </x-form.button>
                        </x-table.td>
                    </x-table.tr>
                    @empty
                    <x-table.tr>
                        <x-table.td colspan="6" class="text-center text-neutral py-10">
                            {{ $search ? 'Tidak ada hasil untuk "' . $search . '".' : 'Belum ada pengaduan.' }}
                        </x-table.td>
                    </x-table.tr>
                    @endforelse
                </tbody>
            </x-table.index>
        </div>
        <div class="flex items-center justify-between mt-4">
            <p class="text-xs text-neutral mt-3">Menampilkan {{ $pengaduans->count() }} data</p>
            {{ $pengaduans->links() }}
        </div>
    </div>

    {{-- ================= MODAL ================= --}}
    <x-modal show="modalOpen" maxWidth="lg:max-w-[450px] max-w-[350px]">

        {{-- DETAIL PENGADUAN --}}
        <template x-if="modalType === 'detail-pengaduan'">
            <div class="lg:!max-w-[450px] !max-w-[350px] -mx-8 -my-16 p-8 bg-[#f5f6fa] rounded-md">
                <div class="relative">
                    <button
                        type="button"
                        class="absolute top-0 right-0 text-neutral hover:text-black text-xl font-bold"
                        @click="modalOpen = false; modalType = null;">
                        ✕
                    </button>
                    <h2 class="text-xl font-bold mb-8">Detail Pengaduan</h2>
                    <div class="flex flex-col gap-4">

                        {{-- Info Penghuni & Pengaduan --}}
                        <div class="bg-white rounded-md shadow-md lg:px-4 px-2 lg:py-5 py-3">
                            <div class="grid grid-cols-2 gap-4 items-start">
                                <div>
                                    <label class="lg:text-xs text-[10px] text-neutral">Nama</label>
                                    <p class="lg:text-md text-[12px] font-medium" x-text="namaLengkap"></p>
                                </div>
                                <div>
                                    <label class="lg:text-xs text-[10px] text-neutral">Email</label>
                                    <p class="lg:text-md text-[12px] font-medium" x-text="email"></p>
                                </div>
                            </div>
                            <hr class="my-4">
                            <div class="grid grid-cols-2 gap-4 items-start">
                                <div>
                                    <label class="lg:text-xs text-[10px] text-neutral">Nama Kost</label>
                                    <p class="lg:text-md text-[12px] font-medium" x-text="namaKost"></p>
                                </div>
                                <div>
                                    <label class="lg:text-xs text-[10px] text-neutral">Tanggal Pengaduan</label>
                                    <p class="lg:text-md text-[12px] font-medium" x-text="tanggalPengaduan"></p>
                                </div>
                            </div>
                            <hr class="my-4">
                            <div class="flex justify-between h-32">
                                <div class="w-60 h-full overflow-auto">
                                    <p class="lg:text-md text-xs text-black font-medium mb-1" x-text="judul"></p>
                                    <p class="text-xs text-neutral mb-3" x-text="isi"></p>
                                </div>
                                <div x-show="bukti" class="lg:w-28 w-32 lg:pl-3 pl-2">
                                    <label class="lg:text-xs text-[10px] text-black font-medium mb-1 block">Bukti</label>
                                    <template x-if="bukti && (bukti.endsWith('.jpg') || bukti.endsWith('.jpeg') || bukti.endsWith('.png'))">
                                        <a :href="bukti" target="_blank">
                                            <img
                                                :src="bukti"
                                                class="lg:w-24 w-20 lg:h-24 h-20 object-cover rounded-md border border-gray-200 hover:opacity-80 transition">
                                        </a>
                                    </template>
                                    <template x-if="bukti && bukti.endsWith('.pdf')">
                                        <a
                                            :href="bukti"
                                            target="_blank"
                                            class="flex items-center gap-2 text-xs text-primary underline hover:opacity-80 transition">
                                            <img src="{{ asset('assets/icons/pdf-icon.png') }}" class="lg:w-10 w-8 lg:h-10 h-8">
                                            Lihat PDF
                                        </a>
                                    </template>
                                </div>
                            </div>
                        </div>

                        {{-- Respon Pengelola --}}
                        <div class="bg-white rounded-md shadow-md lg:px-4 px-2 lg:py-5 py-3">
                            <label class="block text-sm font-medium text-black mb-2">Respon Pengelola</label>
                            <div class="w-full rounded-xl bg-[#F8F8F8] border-2 border-[#E2E2E2] px-4 py-3">
                                <p class="text-xs text-black mb-1" x-text="balasan || 'Belum ada balasan'"></p>
                            </div>
                        </div>

                        {{-- Status --}}
                        <div class="bg-white rounded-md shadow-md lg:px-4 px-2 lg:py-5 py-3">
                            <x-form.input
                                label="Status"
                                name="status-pengaduan"
                                class="bg-[#F8F8F8] border-[#E2E2E2] text-xs"
                                x-bind:value="formatStatus(status)"
                                placeholder="Status"
                                disabled />
                        </div>

                    </div>
                </div>
            </div>
        </template>

    </x-modal>

</div>

@endsection