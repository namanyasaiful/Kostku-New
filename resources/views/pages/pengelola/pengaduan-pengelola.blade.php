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

        openModal(type, data = {}) {
            if(data) {
                this.id = data.id;
                this.judul = data.judul;
                this.isi = data.isi;
                this.nama = data.nama;
                this.kamar = data.kamar;
                this.status = data.status;
                // pastikan value status selalu sesuai enum database: baru|proses|selesai
                this.selectedStatus = ['baru','proses','selesai'].includes(data.status)
                    ? data.status
                    : 'proses';
                this.balasan = data.balasan || '';
            }
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

                @foreach ($pengaduans as $pengaduan)
                    <x-table.tr>
                        <x-table.td class="font-medium text-heading">
                            {{ $pengaduan->user->nama }}
                        </x-table.td>

                        <x-table.td class="font-medium text-heading">
                            {{ $pengaduan->user->penghuni->first()->kamar->nomor_kamar ?? '-' }}
                        </x-table.td>

                        <x-table.td class="font-medium text-heading">
                            {{ $pengaduan->created_at->format('d/m/Y') }}
                        </x-table.td>

                        <x-table.td>
                            @if ($pengaduan->status === 'baru')
                                <x-badge type="danger">Baru</x-badge>
                            @elseif($pengaduan->status === 'proses')
                                <x-badge type="warning">Diproses</x-badge>
                            @elseif($pengaduan->status === 'selesai')
                                <x-badge type="success">Selesai</x-badge>
                            @endif
                        </x-table.td>

                        {{-- ================= DETAIL BUTTON ================= --}}
                        <x-table.td>
                            <a
                                href="#"
                                @click.prevent="openModal('detail-pengaduan', {
                                    id: {{ $pengaduan->id }},
                                    judul: '{{ addslashes($pengaduan->judul) }}',
                                    isi: '{{ addslashes($pengaduan->isi) }}',
                                    nama: '{{ addslashes($pengaduan->user->nama) }}',
                                    kamar: '{{ $pengaduan->user->penghuni->first()->kamar->nomor_kamar ?? '-' }}',
                                    status: '{{ $pengaduan->status }}',
                                    balasan: '{{ addslashes($pengaduan->balasan) }}'
                                })"
                                class="w-28 flex justify-center cursor-pointer">

                                <img
                                    src="{{ asset('assets/icons/lihat-detail-icon.png') }}"
                                    alt="Lihat Detail"
                                    class="w-4 h-4">

                            </a>
                        </x-table.td>
                    </x-table.tr>
                @endforeach

            </tbody>

        </x-table.index>

    </x-card>

    {{-- ================= PAGINATION ================= --}}
    <div class="mt-4">
        {{ $pengaduans->links() }}
    </div>

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

                <form action="{{ route('pengelola.pengaduan.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="pengaduan_id" :value="id">

                    <div class="flex flex-col gap-4">

                        {{-- CARD BALASAN PENGADUAN --}}
                        <div class="bg-[#EDF2FF] rounded-md shadow-md px-4 py-5">

                            <h3 class="lg:text-md text-sm text-black font-semibold mb-2" x-text="judul"></h3>

                            <p class="text-xs text-neutral mb-3" x-text="isi"></p>

                            <div class="flex justify-around">
                                <p class="text-xs text-neutral" x-text="nama"></p>
                                <p class="text-xs text-neutral" x-text="kamar"></p>
                            </div>

                        </div>

                        {{-- TEXTAREA --}}
                        <x-form.textarea
                            label="Respon Anda"
                            name="balasan"
                            x-model="balasan"
                            rows="3"
                            placeholder="Tulis respon untuk pengaduan ini" />

                        {{-- SELECT STATUS --}}
                        <x-form.select
                            label="Status"
                            name="status"
                            x-model="selectedStatus"
                            class="bg-[#F8F8F8] border-[#E2E2E2]">

                            <option value="proses">
                                Diproses
                            </option>

                            <option value="selesai">
                                Selesai
                            </option>

                        </x-form.select>

                        {{-- BUTTON --}}
                        <x-form.button
                            type="submit"
                            class="w-full mt-4">

                            Simpan dan balas pengaduan

                        </x-form.button>

                    </div>
                </form>

            </div>

        </template>

        {{-- ================= SUCCESS MODAL ================= --}}
        <template x-if="modalOpen && modalType === 'kirim-success'">

            <div class="text-center">

                <div class="flex justify-center mb-4">

                    <div class="w-20 h-20 flex items-center justify-center">

                        <img
                            src="{{ asset('assets/icons/success-modal-icon.png') }}"
                            class="w-12">

                    </div>

                </div>

                <h2 class="lg:text-xl text-md font-bold mb-2">

                    <template x-if="status === 'proses'">
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
