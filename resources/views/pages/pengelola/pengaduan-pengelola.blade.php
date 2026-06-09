@extends('layouts.pengelola')
@section('title', 'Pengaduan Pengelola')

@section('content')

<div
    x-data="{
        modalOpen: false,
        modalType: null,

        id: null,
        judul: '',
        isi: '',
        nama: '',
        kamar: '',
        status: '',
        selectedStatus: 'proses',
        balasan: '',
        tanggalPengaduan: '',
        bukti: '',
        sudahDibalas: false,

        openModal(type, data = {}) {
            if (data) {
                this.id             = data.id;
                this.judul          = data.judul;
                this.isi            = data.isi;
                this.nama           = data.nama;
                this.kamar          = data.kamar;
                this.status         = data.status;
                this.selectedStatus = ['baru','proses','selesai'].includes(data.status)
                    ? data.status : 'proses';
                this.balasan        = data.balasan || '';
                this.tanggalPengaduan = data.tanggal_pengaduan || '';
                this.bukti          = data.bukti || '';
                this.sudahDibalas   = data.sudah_dibalas || false;
            }
            this.modalOpen = true;
            this.modalType = type;
        }
    }"

    x-init="
        @if(session('success_balasan'))
            openModal('success-balasan')
            setTimeout(() => { modalOpen = false; modalType = null; }, 2500)
        @endif
        @if(session('success_status'))
            openModal('success-status')
            setTimeout(() => { modalOpen = false; modalType = null; }, 2500)
        @endif
        @if(session('failed_balasan'))
            openModal('failed-balasan')
            setTimeout(() => { modalOpen = false; modalType = null; }, 2500)
        @endif
    ">

    {{-- ================= PAGE HEADER ================= --}}
    <x-page-header
        title="Pengaduan Penghuni"
        description="Kelola dan respon pengaduan">
    </x-page-header>

    {{-- ================= SEARCH ================= --}}
    <form method="GET" action="{{ route('pengaduan.pengelola') }}" class="mb-4">
        <x-search-input
            name="search_pengaduan"
            placeholder="Cari"
            value="{{ request('search_pengaduan') }}" />
    </form>

    {{-- ================= TABLE ================= --}}
    <x-card class="mb-6">
        <x-table.index class="min-w-[700px]">

            <thead class="sticky top-0 bg-white z-10 border-default">
                <x-table.tr>
                    <x-table.th>Nama Lengkap</x-table.th>
                    <x-table.th>Kamar</x-table.th>
                    <x-table.th>Judul Pengaduan</x-table.th>
                    <x-table.th>Tanggal Pengaduan</x-table.th>
                    <x-table.th>Status</x-table.th>
                    <x-table.th>Detail Pengaduan</x-table.th>
                </x-table.tr>
            </thead>

            <tbody>
                @forelse ($pengaduans as $pengaduan)
                <x-table.tr>
                    <x-table.td class="font-medium text-heading">
                        {{ $pengaduan->user->nama }}
                    </x-table.td>

                    <x-table.td class="font-medium text-heading">
                        {{ $pengaduan->user->penghuni->first()->kamar->nomor_kamar ?? '-' }}
                    </x-table.td>

                    <x-table.td class="font-medium text-heading">
                        {{ Str::limit($pengaduan->judul, 30) }}
                    </x-table.td>

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
                        @endif
                    </x-table.td>

                    <x-table.td>
                        <x-form.button
                            type="button"
                            @click="openModal('detail-pengaduan', {
                                id: {{ $pengaduan->id }},
                                judul: '{{ addslashes($pengaduan->judul) }}',
                                isi: '{{ addslashes($pengaduan->isi) }}',
                                nama: '{{ addslashes($pengaduan->user->nama) }}',
                                kamar: '{{ $pengaduan->user->penghuni->first()->kamar->nomor_kamar ?? '-' }}',
                                status: '{{ $pengaduan->status }}',
                                balasan: '{{ addslashes($pengaduan->balasan ?? '') }}',
                                tanggal_pengaduan: '{{ $pengaduan->created_at->format('d/m/Y') }}',
                                bukti: '{{ $pengaduan->bukti_pengaduan ? asset('storage/' . $pengaduan->bukti_pengaduan) : '' }}',
                                sudah_dibalas: {{ $pengaduan->balasan ? 'true' : 'false' }}
                            })"
                            class="w-24 !p-2 border border-primary bg-transparent !text-primary hover:bg-secondary hover:border-secondary">
                            Detail
                        </x-form.button>
                    </x-table.td>
                </x-table.tr>
                @empty
                <x-table.tr>
                    <x-table.td colspan="5" class="text-center text-neutral py-10">
                        {{ request('search_pengaduan') ? 'Tidak ada hasil untuk "' . request('search_pengaduan') . '".' : 'Belum ada pengaduan.' }}
                    </x-table.td>
                </x-table.tr>
                @endforelse
            </tbody>

        </x-table.index>

    {{-- MENAMPILKAN DATA --}}
    <div class="flex items-center justify-between mt-4">
        <p class="text-xs text-neutral mt-3">Menampilkan {{ $pengaduans->count() }} data</p>
    </div>
    </x-card>

    {{-- ================= PAGINATION ================= --}}
    <x-pagination :paginator="$pengaduans" />

    {{-- ================= MODAL ================= --}}
    <x-modal show="modalOpen" maxWidth="lg:max-w-[450px] max-w-[350px]">

        {{-- DETAIL PENGADUAN --}}
        <template x-if="modalType === 'detail-pengaduan'">
            <div class="lg:!max-w-[450px] !max-w-[350px] -mx-8 -my-16 p-8 bg-[#f5f6fa] rounded-md">
                <div class="relative">

                    {{-- CLOSE --}}
                    <button
                        type="button"
                        class="absolute top-0 right-0 text-neutral hover:text-black text-xl font-bold"
                        @click="modalOpen = false; modalType = null;">
                        ✕
                    </button>

                    <h2 class="text-xl font-bold mb-8">Detail Pengaduan</h2>

                    <div class="flex flex-col gap-4 lg:h-[500px] h-[380px] overflow-auto pr-1">

                        {{-- INFO PENGADUAN --}}
                        <div class="bg-white rounded-md shadow-md lg:px-4 px-2 lg:py-5 py-3">
                            <div class="flex lg:gap-8 gap-3 mb-2 flex-wrap">
                                <div>
                                    <label class="lg:text-xs text-[10px] text-neutral">Nama</label>
                                    <p class="lg:text-sm text-[12px] font-medium" x-text="nama"></p>
                                </div>
                                <div>
                                    <label class="lg:text-xs text-[10px] text-neutral">Kamar</label>
                                    <p class="lg:text-sm text-[12px] font-medium" x-text="kamar"></p>
                                </div>
                                <div>
                                    <label class="lg:text-xs text-[10px] text-neutral">Tanggal</label>
                                    <p class="lg:text-sm text-[12px] font-medium" x-text="tanggalPengaduan"></p>
                                </div>
                            </div>
                            <hr class="mb-2">
                            <div class="flex justify-between h-32">
                                <div class="w-60 h-full overflow-auto">
                                    <p class="lg:text-sm text-xs text-black font-medium mb-1" x-text="judul"></p>
                                    <p class="text-xs text-neutral" x-text="isi"></p>
                                </div>
                                {{-- BUKTI --}}
                                <div x-show="bukti" class="lg:w-28 w-24 lg:pl-3 pl-2">
                                    <label class="lg:text-xs text-[10px] text-black font-medium mb-1 block">Bukti</label>
                                    <template x-if="bukti && (bukti.endsWith('.jpg') || bukti.endsWith('.jpeg') || bukti.endsWith('.png'))">
                                        <a :href="bukti" target="_blank">
                                            <img :src="bukti" class="lg:w-24 w-20 lg:h-24 h-20 object-cover rounded-md border border-gray-200 hover:opacity-80 transition">
                                        </a>
                                    </template>
                                    <template x-if="bukti && bukti.endsWith('.pdf')">
                                        <a :href="bukti" target="_blank" class="flex items-center gap-2 text-xs text-primary underline hover:opacity-80 transition">
                                            <img src="{{ asset('assets/icons/pdf-icon.png') }}" class="lg:w-10 w-8 lg:h-10 h-8">
                                            Lihat PDF
                                        </a>
                                    </template>
                                </div>
                            </div>
                        </div>

                        {{-- FORM BALASAN --}}
                        <div class="bg-white rounded-md shadow-md lg:px-4 px-2 lg:py-5 py-3">
                            <label class="block text-sm font-medium text-black mb-1">Respon Anda</label>

                            {{-- Sudah dibalas: tampilkan teks balasan saja --}}
                            <template x-if="sudahDibalas">
                                <div>
                                    <p class="text-xs text-neutral mb-2">Balasan sudah dikirim dan tidak dapat diubah.</p>
                                    <div class="w-full rounded-xl bg-[#F8F8F8] border-2 border-[#E2E2E2] px-4 py-3">
                                        <p class="text-xs text-black" x-text="balasan"></p>
                                    </div>
                                </div>
                            </template>

                            {{-- Belum dibalas: form kirim --}}
                            <template x-if="!sudahDibalas">
                                <form action="{{ route('pengelola.pengaduan.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="pengaduan_id" :value="id">
                                    <p class="text-xs text-neutral mb-2">Anda hanya dapat membalas sekali dan tidak dapat dibatalkan.</p>
                                    <x-form.textarea
                                        name="balasan"
                                        x-model="balasan"
                                        rows="3"
                                        placeholder="Tulis respon untuk pengaduan ini"
                                        class="bg-[#F8F8F8] text-xs mb-2" />
                                    <x-form.button type="submit" class="w-full">
                                        Kirim Balasan
                                    </x-form.button>
                                </form>
                            </template>
                        </div>

                        {{-- FORM UPDATE STATUS --}}
                        <div class="bg-white rounded-md shadow-md lg:px-4 px-2 lg:py-5 py-3">
                            <form :action="`/pengelola/pengaduan-pengelola/${id}/status`" method="POST">
                                @csrf
                                @method('PATCH')
                                <label class="block text-sm font-medium text-black mb-1">Update Status</label>
                                <x-form.select
                                    name="status"
                                    x-model="selectedStatus"
                                    class="!bg-[#F8F8F8] border-[#E2E2E2] text-xs mb-4">
                                    <option value="proses">Diproses</option>
                                    <option value="selesai">Selesai</option>
                                </x-form.select>
                                <x-form.button type="submit" class="w-full">
                                    Simpan Status
                                </x-form.button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </template>

        {{-- SUCCESS BALASAN --}}
        <template x-if="modalType === 'success-balasan'">
            <div class="text-center">
                <div class="flex justify-center mb-4">
                    <img src="{{ asset('assets/icons/success-modal-icon.png') }}" class="w-12">
                </div>
                <h2 class="lg:text-xl text-md font-bold mb-2">Balasan berhasil dikirim</h2>
            </div>
        </template>

        {{-- SUCCESS STATUS --}}
        <template x-if="modalType === 'success-status'">
            <div class="text-center">
                <div class="flex justify-center mb-4">
                    <img src="{{ asset('assets/icons/success-modal-icon.png') }}" class="w-12">
                </div>
                <h2 class="lg:text-xl text-md font-bold mb-2">Status berhasil diperbarui</h2>
            </div>
        </template>

        {{-- FAILED BALASAN --}}
        <template x-if="modalType === 'failed-balasan'">
            <div class="text-center">
                <div class="flex justify-center mb-4">
                    <img src="{{ asset('assets/icons/failed-modal-icon.png') }}" class="w-12">
                </div>
                <h2 class="lg:text-xl text-md font-bold mb-2">Pengaduan sudah pernah dibalas</h2>
                <p class="text-xs text-neutral">{{ session('failed_balasan') }}</p>
            </div>
        </template>

    </x-modal>

</div>

@endsection