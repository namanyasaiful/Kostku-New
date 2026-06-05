@extends('layouts.penghuni')
@section('title', 'Pengaduan Penghuni')

@section('content')

<div x-data="{
    modalOpen: false,
    modalType: null,

    id: null,
    batalId: null, 
    judul: '',
    isi: '',
    balasan: '',
    status: '',
    tanggalPengaduan: '',
    bukti: '',

    openModal(type, data = {}) {
        if (data) {
            this.id = data.id;
            this.batalId = data.id || null;
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

    {{-- ================= FORM BUAT PENGADUAN ================= --}}
    <form action="{{ route('penghuni.pengaduan.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <x-card class="mb-8">
            <h1 class="lg:text-2xl text-xl text-black font-semibold mb-4">
                Buat Pengaduan Baru
            </h1>

            <div class="mb-4">
                <h3 class="lg:text-md text-sm text-black font-semibold mb-2">
                    Judul Pengaduan
                </h3>
                <x-form.input
                    name="judul"
                    placeholder="Contoh: WiFi ngelag"
                    class="mb-4 bg-[#F8F8F8]" />
            </div>

            <div class="lg:flex justify-between lg:gap-8 gap-4 mb-4">
                <div class="w-full">
                    <h3 class="lg:text-md text-sm text-black font-semibold mb-2">
                        Deskripsi Pengaduan
                    </h3>
                    <x-form.textarea
                        name="isi"
                        rows="6"
                        placeholder="Jelaskan masalah Anda secara detail..."
                        class="mb-4 bg-[#F8F8F8] text-sm"></x-form.textarea>
                </div>
                <div class="w-full">
                    <h3 class="lg:text-md text-sm text-black font-semibold mb-2">
                        Upload Gambar (Opsional)
                    </h3>
                    <div
                        x-data="fileUploadMixed()"
                        class="w-full mb-1">
                        {{-- ================= BEFORE UPLOAD ================= --}}
                        <div x-show="!file">
                            <div
                                class="border-2 border-dashed border-gray-300 rounded-xl h-36 cursor-pointer hover:border-primary transition flex items-center justify-center"
                                @click="$refs.file.click()">
                                <div class="flex flex-col items-center justify-center text-body lg:p-2 p-6">
                                    <img
                                        src="{{ asset('assets/icons/cloud-add.png') }}"
                                        class="w-8 h-8 lg:mb-4 mb-2">
                                    <p class="lg:text-sm text-xs text-center mb-1">
                                        Drag & drop file atau klik untuk upload
                                    </p>
                                    <p class="lg:text-xs text-[10px] text-[#B0B0B0]">
                                        Format: JPG, PNG, PDF (Max 10MB)
                                    </p>
                                </div>
                            </div>
                        </div>
                        {{-- ================= AFTER UPLOAD ================= --}}
                        <div
                            x-show="file"
                            x-transition
                            class="w-full">

                            {{-- Preview jika gambar --}}
                            <template x-if="file && (file.type === 'image/jpeg' || file.type === 'image/png')">
                                <div class="relative">
                                    <button
                                        type="button"
                                        class="absolute top-2 lg:right-[360px] right-28 bg-white rounded-full p-1 shadow"
                                        @click="removeFile(); $refs.file.value = null">
                                        <img src="{{ asset('assets/icons/delete-icon.png') }}" class="w-4">
                                    </button>
                                    <img
                                        :src="previewUrl"
                                        class="w-36 h-36 object-cover rounded-xl border border-gray-200">
                                </div>
                            </template>

                            {{-- Preview jika PDF (tetap tampil card seperti semula) --}}
                            <template x-if="file && file.type === 'application/pdf'">
                                <x-card class="relative flex items-center gap-3 w-full h-14 overflow-hidden bg-[#F8F8F8]">
                                    <button
                                        type="button"
                                        class="absolute top-2 lg:right-2 right-3"
                                        @click="removeFile(); $refs.file.value = null">
                                        <img src="{{ asset('assets/icons/delete-icon.png') }}" class="lg:w-4 w-3">
                                    </button>
                                    <img :src="getFileIcon()" class="w-10 h-10 shrink-0">
                                    <div class="flex-1 min-w-0 pr-6">
                                        <p class="text-sm font-medium truncate w-full">
                                            <span x-text="file.name"></span>
                                        </p>
                                        <div class="flex items-center gap-2 mt-1 text-xs flex-wrap">
                                            <span class="text-gray-500 whitespace-nowrap" x-text="fileSize + ' of ' + fileSize + ' •'"></span>
                                            <span class="text-black flex items-center gap-1 whitespace-nowrap">
                                                <img src="{{ asset('assets/icons/success-icon.png') }}" class="w-3 h-3">
                                                Selesai
                                            </span>
                                        </div>
                                    </div>
                                </x-card>
                            </template>

                        </div>
                        {{-- INPUT FILE --}}
                        <input
                            type="file"
                            name="bukti_pengaduan"
                            accept=".jpg,.jpeg,.png,.pdf"
                            class="hidden"
                            x-ref="file"
                            @change="handleFile($event)">
                    </div>
                </div>
            </div>

            <x-form.button type="submit">
                Kirim Pengaduan
            </x-form.button>

        </x-card>
    </form>

    {{-- ================= RIWAYAT PENGADUAN ================= --}}
    <h1 class="lg:text-xl text-lg text-black font-semibold mb-4">
        Riwayat Pengaduan
    </h1>
    <x-card class="mb-6">
        @if ($pengaduans->count() > 0)
        <x-table.index class="min-w-[700px]">
            <thead class="sticky top-0 bg-white z-10 border-default">
                <x-table.tr>
                    <x-table.th>Tanggal Pengaduan</x-table.th>
                    <x-table.th>Status</x-table.th>
                    <x-table.th>Aksi</x-table.th>
                </x-table.tr>
            </thead>
            <tbody>
                @forelse ($pengaduans as $pengaduan)
                <x-table.tr>
                    <x-table.td class="font-medium text-heading">
                        {{ $pengaduan->created_at->format('d/m/Y') }}
                    </x-table.td>
                    <x-table.td>
                        @if ($pengaduan->status === 'baru')
                        <x-badge type="info">Baru</x-badge>
                        @elseif($pengaduan->status === 'proses')
                        <x-badge type="warning">Diproses</x-badge>
                        @elseif($pengaduan->status === 'selesai')
                        <x-badge type="success">Selesai</x-badge>
                        @else
                        <x-badge type="secondary">{{ $pengaduan->status }}</x-badge>
                        @endif
                    </x-table.td>
                    <x-table.td>
                        <x-form.button
                            type="button"
                            @click="openModal('detail-pengaduan', {
                                id: '{{ $pengaduan->id }}',
                                judul: '{{ addslashes($pengaduan->judul) }}',
                                isi: '{{ addslashes($pengaduan->isi) }}',
                                balasan: '{{ addslashes($pengaduan->balasan) }}',
                                status: '{{ $pengaduan->status }}',
                                tanggal_pengaduan: '{{ $pengaduan->created_at->format('d/m/Y') }}',
                                bukti: '{{ $pengaduan->bukti_pengaduan ? asset('storage/' . $pengaduan->bukti_pengaduan) : '' }}'
                            })"
                            class="lg:!w-[150px] !w-[100px] !py-2 !px-4 border border-primary bg-transparent !text-primary hover:bg-secondary hover:border-secondary">
                            Detail
                        </x-form.button>
                        @if ($pengaduan->status === 'baru')
                        <x-form.button
                            type="button"
                            @click="openModal('batal-pengaduan', { id: '{{ $pengaduan->id }}' })"
                            class="lg:!w-[150px] !w-[100px] !py-2 !px-4 border border-red-500 bg-transparent !text-red-500 hover:bg-red-50 hover:border-red-600">
                            Batalkan
                        </x-form.button>
                        @endif
                    </x-table.td>
                </x-table.tr>
                @empty
                <x-table.tr>
                    <x-table.td colspan="3" class="text-center text-neutral py-10">
                        Belum ada pengaduan.
                    </x-table.td>
                </x-table.tr>
                @endforelse
            </tbody>
        </x-table.index>
        <div class="mt-4">
            {{ $pengaduans->links() }}
        </div>
        @else
        <p class="text-center text-neutral py-10">Belum ada pengaduan.</p>
        @endif
    </x-card>

    {{-- ================= PAGINATION ================= --}}
    <x-pagination />

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
                        <div>
                            <div class="bg-white rounded-md shadow-md lg:px-4 px-2 lg:py-5 py-3">
                                <div class="flex lg:gap-12 gap-4 mb-2">
                                    <div>
                                        <label for="nama" class="lg:text-xs text-[10px] text-neutral">Nama</label>
                                        <p class="lg:text-md text-[12px] font-medium">Anton Subagja</p>
                                    </div>
                                    <div>
                                        <label for="nama" class="lg:text-xs text-[10px] text-neutral">Kamar</label>
                                        <p class="lg:text-md text-[12px] font-medium">KM001</p>
                                    </div>
                                    <div>
                                        <label for="nama" class="lg:text-xs text-[10px] text-neutral">Tanggal Pengaduan</label>
                                        <p class="lg:text-md text-[12px] font-medium" x-text="tanggalPengaduan"></p>
                                    </div>
                                </div>
                                <hr class="mb-2">
                                <div class="flex justify-between h-32">
                                    <div class="w-60 h-full overflow-auto">
                                        <p class="lg:text-md text-xs text-black font-medium mb-1" x-text="judul"></p>
                                        <p class="text-xs text-neutral mb-3" x-text="isi"></p>
                                    </div>
                                    <div x-show="bukti" class="lg:w-28 w-32 lg:pl-3 pl-2">
                                        <label class="lg:text-xs text-[10px] text-black font-medium mb-1 block">Bukti</label>
                                        {{-- Jika gambar (jpg/png) --}}
                                        <template x-if="bukti && (bukti.endsWith('.jpg') || bukti.endsWith('.jpeg') || bukti.endsWith('.png'))">
                                            <a :href="bukti" target="_blank">
                                                <img
                                                    :src="bukti"
                                                    class="lg:w-24 w-20 lg:h-24 h-20 object-cover rounded-md border border-gray-200 hover:opacity-80 transition">
                                            </a>
                                        </template>
                                        {{-- Jika PDF --}}
                                        <template x-if="bukti && bukti.endsWith('.pdf')">
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
                        </div>
                        <div class="bg-white rounded-md shadow-md lg:px-4 px-2 lg:py-5 py-3">
                            <label class="block text-sm font-medium text-black mb-2">Respon Pengelola</label>
                            <div class="w-full rounded-xl bg-[#F8F8F8] border-2 border-[#E2E2E2] px-4 py-3">
                                <p class="text-xs text-black mb-1" x-text="balasan || 'Belum ada balasan'"></p>
                            </div>
                        </div>
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

        {{-- SUCCESS KIRIM --}}
        <template x-if="modalType === 'kirim-success'">
            <div class="text-center">
                <div class="flex justify-center mb-4">
                    <div class="w-20 h-20 flex items-center justify-center">
                        <img src="{{ asset('assets/icons/success-modal-icon.png') }}" class="w-12">
                    </div>
                </div>
                <h2 class="lg:text-xl text-md font-bold mb-2">
                    Pengaduan berhasil dikirim
                </h2>
            </div>
        </template>

        {{-- FAILED KIRIM --}}
        <template x-if="modalType === 'kirim-failed'">
            <div class="text-center">
                <div class="flex justify-center mb-4">
                    <div class="w-20 h-20 flex items-center justify-center">
                        <img src="{{ asset('assets/icons/failed-modal-icon.png') }}" class="w-12">
                    </div>
                </div>
                <h2 class="lg:text-xl text-md font-bold mb-2">
                    Gagal Mengirim Pengaduan
                </h2>
                <p class="lg:text-md text-xs text-neutral">Silakan coba lagi.</p>
            </div>
        </template>

        {{-- BATALKAN PENGADUAN --}}
        <template x-if="modalType === 'batal-pengaduan'">
            <div class="relative">
                <h2 class="text-xl font-bold mb-4">
                    Konfirmasi Batal Pengaduan
                </h2>
                <p class="text-xs text-neutral">Apakah Anda yakin ingin membatalkan pengaduan? Tindakan ini tidak dapat dibatalkan.</p>
                <div class="flex gap-3 mt-4">
                    <x-form.button
                        type="button"
                        class="w-full !text-neutral !bg-transparent border-2 !border-neutral hover:!bg-neutral hover:!text-white"
                        @click="modalOpen = false; modalType = null;">
                        Kembali
                    </x-form.button>
                    <form :action="`/penghuni/pengaduan/${batalId}/batal`" method="POST" class="w-full">
                        @csrf
                        @method('PATCH')
                        <x-form.button
                            type="submit"
                            class="w-full !text-white !bg-red-600 hover:!bg-red-100 hover:!text-red-600">
                            Ya, Batalkan
                        </x-form.button>
                    </form>
                </div>
            </div>
        </template>

        {{-- SUCCESS DIBATALKAN --}}
        <template x-if="modalType === 'kirim-failed'">
            <div class="text-center">
                <div class="flex justify-center mb-4">
                    <div class="w-20 h-20 flex items-center justify-center">
                        <img src="{{ asset('assets/icons/failed-modal-icon.png') }}" class="w-12">
                    </div>
                </div>
                <h2 class="lg:text-xl text-md font-bold mb-2">
                    Pengaduan berhasil dibatalkan
                </h2>
            </div>
        </template>
    </x-modal>

</div>

@endsection