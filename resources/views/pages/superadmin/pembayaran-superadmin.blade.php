@extends('layouts.superadmin')
@section('title', 'Pembayaran - Super Admin')

@section('content')

<div x-data="{
    modalOpen: false,
    modalType: null,
    selectedPembayaran: {},

    openModal(type, data = {}) {
        this.selectedPembayaran = data;
        this.modalOpen = true;
        this.modalType = type;
    },

    closeModal() {
        this.modalOpen = false;
        this.modalType = null;
    }
}">

    {{-- ================= PAGE HEADER ================= --}}
    <x-page-header
        title="Pembayaran"
        description="Lihat semua riwayat pembayaran penghuni">
    </x-page-header>

    {{-- ================= SEARCH ================= --}}
    <form method="GET" action="{{ route('pembayaran-superadmin.superadmin') }}">
        <x-search-input
            name="search"
            placeholder="Cari"
            value="{{ $search ?? '' }}" />
    </form>

    {{-- ================= TABLE ================= --}}
    <div class="bg-white rounded-lg p-4 lg:p-6 mt-4 mb-6">
        <div class="overflow-x-auto">
            <x-table.index>
                <thead class="sticky top-0 bg-white z-10 border-b border-default">
                    <x-table.tr>
                        <x-table.th>Nama Lengkap</x-table.th>
                        <x-table.th>Kost</x-table.th>
                        <x-table.th>Tanggal Pembayaran</x-table.th>
                        <x-table.th>Nominal</x-table.th>
                        <x-table.th>Jenis Pembayaran</x-table.th>
                        <x-table.th>Status</x-table.th>
                        <x-table.th class="text-center">Aksi</x-table.th>
                    </x-table.tr>
                </thead>
                <tbody>
                    @forelse ($pembayarans as $pembayaran)
                    <x-table.tr>
                        <x-table.td class="font-medium">
                            {{ $pembayaran->user->nama ?? '-' }}
                        </x-table.td>
                        <x-table.td>
                            {{ $pembayaran->user->penghuni->kamar->kost->nama_kost ?? '-' }}
                        </x-table.td>
                        <x-table.td>
                            {{ \Carbon\Carbon::parse($pembayaran->tanggal_pembayaran)->format('d/m/Y') }}
                        </x-table.td>
                        <x-table.td>
                            Rp{{ number_format($pembayaran->nominal, 0, ',', '.') }}
                        </x-table.td>
                        <x-table.td>
                            @if ($pembayaran->tipe_pembayaran === 'lunas')
                                Bayar Lunas
                            @else
                                Cicilan {{ $pembayaran->jumlah_cicilan }}
                            @endif
                        </x-table.td>
                        <x-table.td>
                            @if ($pembayaran->status === 'lunas')
                                <x-badge type="success">Lunas</x-badge>
                            @else
                                <x-badge type="danger">Belum Lunas</x-badge>
                            @endif
                        </x-table.td>
                        <x-table.td class="text-center">
                            <x-form.button
                                @click.prevent="openModal('detail-pembayaran', {
                                    nama: '{{ addslashes($pembayaran->user->nama ?? '-') }}',
                                    nama_kost: '{{ addslashes($pembayaran->user->penghuni->kamar->kost->nama_kost ?? '-') }}',
                                    nomor_transaksi: '{{ $pembayaran->id_pembayaran }}',
                                    jenis: '{{ $pembayaran->tipe_pembayaran === 'lunas' ? 'Bayar Lunas' : 'Cicilan ' . $pembayaran->jumlah_cicilan }}',
                                    metode: '{{ addslashes($pembayaran->payment_type ?? '-') }}',
                                    waktu: '{{ $pembayaran->paid_at ? \Carbon\Carbon::parse($pembayaran->paid_at)->format('d/m/Y H.i') : '-' }}',
                                    nominal: 'Rp{{ number_format($pembayaran->nominal, 0, ',', '.') }}',
                                    status: '{{ $pembayaran->status === 'lunas' ? 'Lunas' : 'Belum Lunas' }}'
                                })"
                                class="border border-primary bg-transparent !text-primary hover:bg-secondary hover:border-secondary">
                                Detail
                            </x-form.button>
                        </x-table.td>
                    </x-table.tr>
                    @empty
                    <x-table.tr>
                        <x-table.td colspan="7" class="text-center text-neutral py-10">
                            {{ $search ? 'Tidak ada hasil untuk "' . $search . '".' : 'Belum ada pembayaran.' }}
                        </x-table.td>
                    </x-table.tr>
                    @endforelse
                </tbody>
            </x-table.index>
        </div>
        <div class="flex items-center justify-between mt-4">
            <p class="text-xs text-neutral mt-3">Menampilkan {{ $pembayarans->count() }} data</p>
        </div>
    </div>

    {{-- ================= MODAL ================= --}}
    <x-modal show="modalOpen" maxWidth="lg:max-w-[500px] max-w-[350px]">
        <template x-if="modalType === 'detail-pembayaran'">
            <div class="relative">

                <button
                    type="button"
                    class="absolute top-0 right-0 text-neutral hover:text-black text-xl font-bold"
                    @click="closeModal()">
                    ✕
                </button>

                <h2 class="text-xl font-bold mb-8">Struk Pembayaran</h2>

                <div class="flex flex-col gap-4">
                    <div class="grid grid-cols-3 gap-4 items-start">
                        <div class="flex flex-col gap-1">
                            <p class="text-neutral text-xs">Nama Lengkap</p>
                            <p class="text-black text-xs font-semibold" x-text="selectedPembayaran.nama"></p>
                        </div>
                        <div class="flex flex-col gap-1">
                            <p class="text-neutral text-xs">Nama Kost</p>
                            <p class="text-black text-xs font-semibold" x-text="selectedPembayaran.nama_kost"></p>
                        </div>
                        <div class="flex justify-end">
                            <template x-if="selectedPembayaran.status === 'Lunas'">
                                <x-badge type="success">Lunas</x-badge>
                            </template>
                            <template x-if="selectedPembayaran.status === 'Belum Lunas'">
                                <x-badge type="danger">Belum Lunas</x-badge>
                            </template>
                        </div>
                    </div>
                    <hr>

                    <div class="flex flex-col gap-4">
                        <div class="w-full flex justify-between">
                            <p class="text-xs text-neutral">Nomor Transaksi</p>
                            <p class="text-xs text-black font-semibold" x-text="selectedPembayaran.nomor_transaksi"></p>
                        </div>
                        <div class="w-full flex justify-between">
                            <p class="text-xs text-neutral">Jenis Pembayaran</p>
                            <p class="text-xs text-black font-semibold" x-text="selectedPembayaran.jenis"></p>
                        </div>
                        <div class="w-full flex justify-between">
                            <p class="text-xs text-neutral">Metode Pembayaran</p>
                            <p class="text-xs text-black font-semibold" x-text="selectedPembayaran.metode"></p>
                        </div>
                        <div class="w-full flex justify-between">
                            <p class="text-xs text-neutral">Tanggal Transaksi</p>
                            <p class="text-xs text-black font-semibold" x-text="selectedPembayaran.waktu"></p>
                        </div>
                    </div>

                    <div class="flex justify-between w-full rounded-md bg-[#F8F8F8] shadow-sm px-4 py-3">
                        <p class="text-xs text-black font-medium">Total Dibayar</p>
                        <p class="text-sm font-semibold text-primary" x-text="selectedPembayaran.nominal"></p>
                    </div>
                    <x-badge type="success" class="text-center">Pembayaran Berhasil</x-badge>

                </div>
            </div>
        </template>
    </x-modal>
    <x-pagination :paginator="$pembayarans" />

</div>

@endsection