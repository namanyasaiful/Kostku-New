@extends('layouts.penghuni')
@section('title', 'Pengaduan Penghuni')

@section('content')

<div x-data="{
    modalOpen: false,
    modalType: null,

    id: null,
    judul: '',
    isi: '',
    balasan: '',
    status: '',
    tanggalPengaduan: '',

    openModal(type, data = {}) {
        if (data) {
            this.id = data.id;
            this.judul = data.judul || '';
            this.isi = data.isi || '';
            this.balasan = data.balasan || '';
            this.status = data.status || '';
            this.tanggalPengaduan = data.tanggal_pengaduan || '';
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

    <form action="{{ route('penghuni.pengaduan.store') }}" method="POST">
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

            <div class="mb-4">
                <h3 class="lg:text-md text-sm text-black font-semibold mb-2">
                    Deskripsi Pengaduan
                </h3>

                <x-form.textarea
                    name="isi"
                    rows="6"
                    placeholder="Jelaskan masalah Anda secara detail..."
                    class="mb-4 bg-[#F8F8F8] text-sm"></x-form.textarea>
            </div>

            <x-form.button
                type="submit"
                @click="openModal('kirim-success')">

                Kirim Pengaduan

            </x-form.button>

        </x-card>
    </form>

    <h1 class="lg:text-xl text-lg text-black font-semibold mb-4">
        Riwayat Pengaduan
    </h1>

    <x-card>

        @if ($pengaduans->count() > 0)

        <x-table.index class="min-w-[700px]">


            <thead class="sticky top-0 bg-white z-10 border-default">

                <x-table.tr>

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

                @forelse ($pengaduans as $pengaduan)
                    <x-table.tr>

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
                            @else
                                <x-badge type="secondary">{{ $pengaduan->status }}</x-badge>
                            @endif
                        </x-table.td>

                        <x-table.td>

                            <a
                                href="#"
                                @click.prevent="openModal('detail-pengaduan', {
                                    id: {{ $pengaduan->id }},
                                    judul: '{{ addslashes($pengaduan->judul) }}',
                                    isi: '{{ addslashes($pengaduan->isi) }}',
                                    balasan: '{{ addslashes($pengaduan->balasan) }}',
                                    status: '{{ $pengaduan->status }}',
                                    tanggal_pengaduan: '{{ $pengaduan->created_at->format('d/m/Y') }}'
                                })"
                                class="w-28 flex justify-center cursor-pointer">

                                <img
                                    src="{{ asset('assets/icons/lihat-detail-icon.png') }}"
                                    alt="Lihat Detail"
                                    class="w-4 h-4">

                            </a>

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

    {{-- ================= MODAL ================= --}}
    <x-modal show="modalOpen" maxWidth="lg:max-w-[450px] max-w-[350px]">

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

                    <div>
                        <div class="bg-[#EDF2FF] rounded-md shadow-md px-4 py-5">
                            <h3 class="lg:text-md text-sm text-black font-semibold mb-2" x-text="judul"></h3>
                            <p class="text-xs text-neutral mb-3" x-text="isi"></p>
                            <div class="flex justify-around">
                                <p class="text-xs text-neutral" x-text="tanggalPengaduan"></p>
                                <p class="text-xs text-neutral" x-text="status"></p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Balasan Pengelola</label>
                        <div class="w-full
                    rounded-xl bg-[#F8F8F8] border-2 border-[#E2E2E2]
                    px-4
                    py-3">
                            <p class="text-xs text-black mb-1" x-text="balasan"></p>

                            <p class="text-[10px] text-neutral text-right" x-text="status"></p>
                        </div>
                    </div>

                    <div>
                        <x-form.input
                            label="Status"
                            name="status-pengaduan"
                            class="bg-[#F8F8F8] border-[#E2E2E2]"
                            x-bind:value="formatStatus(status)"
                            placeholder="Status" disabled />
                    </div>
                </div>
            </div>

        </template>

        {{-- ================= SUCCESS KIRIM ==================== --}}
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
                    Pengaduan berhasil dikirim
                </h2>

            </div>

        </template>
    </x-modal>

</div>

@endsection
