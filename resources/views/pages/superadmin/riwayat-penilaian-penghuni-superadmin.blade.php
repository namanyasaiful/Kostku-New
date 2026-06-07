@extends('layouts.superadmin')
@section('title', 'Riwayat Penilaian Penghuni')

@section('content')

<a href="{{ route('manajemen-penghuni.superadmin') }}" class="text-sm text-[#313131] flex items-center gap-2 mb-4">
    <span class="text-2xl pb-1 text-[#313131]"><</span>
    Kembali
</a>

<div>
    {{-- PAGE HEADER --}}
    <x-page-header
        title="Penilaian Penghuni {{ $penghuni->nama }}"
        description="Detail track record penghuni">
    </x-page-header>

    @if($records->isEmpty())
        <div class= "text-center text-neutral text-sm">
            Belum ada riwayat penilaian untuk penghuni ini.
        </div>
    @else
    <div class="grid lg:grid-cols-2 grid-cols-1 lg:gap-8 gap-4">
        @foreach($records as $record)
        @php
            $namaKost   = $record->kamar->kost->nama_kost ?? '-';
            $alamatKost = $record->kamar->kost->alamat_kost ?? '-';
            $masuk      = $record->tanggal_masuk  ? \Carbon\Carbon::parse($record->tanggal_masuk)  : null;
            $keluar     = $record->tanggal_keluar ? \Carbon\Carbon::parse($record->tanggal_keluar) : null;

            $periode = '-';
            if ($masuk && $keluar) {
                $bulan   = $masuk->diffInMonths($keluar);
                $periode = $masuk->translatedFormat('M Y') . ' - ' . $keluar->translatedFormat('M Y') . ' (' . $bulan . ' Bulan)';
            }

            $badgeType = fn($skor) => match($skor) {
                'Baik'            => 'success',
                'Perlu Perhatian' => 'warning',
                'Buruk'           => 'danger',
                default           => 'neutral',
            };
        @endphp
        <x-card>
            <div class="flex w-full mb-2">
                <div class="w-1/2">
                    <p class="text-xs text-neutral mb-1">Nama Kost</p>
                    <p class="text-xs font-medium">{{ $namaKost }}</p>
                </div>
                <div class="w-1/2">
                    <p class="text-xs text-neutral mb-1">Periode</p>
                    <p class="text-xs font-medium">{{ $periode }}</p>
                </div>
            </div>
            <hr>

            <div class="w-full flex my-4">
                <div class="w-1/2">
                    <p class="text-xs text-neutral mb-1">Alamat</p>
                    <p class="text-xs font-medium">{{ $alamatKost }}</p>
                </div>
                <div class="w-1/2">
                    <x-form.input
                        name="catatan-{{ $record->id }}"
                        type="text"
                        class="!p-4 bg-[#F8F8F8] text-xs"
                        value="{{ $record->catatan ?? '-' }}"
                        disabled />
                </div>
            </div>
            <hr>

            <div class="flex flex-col gap-4 mt-4">
                <div class="w-full flex justify-between">
                    <p class="text-sm font-medium text-primary">Penilaian Penghuni</p>
                </div>
                <div class="w-full flex justify-between">
                    <p class="lg:text-sm text-xs text-neutral">Pembayaran</p>
                    <x-badge :type="$badgeType($record->skor_pembayaran)">{{ $record->skor_pembayaran }}</x-badge>
                </div>
                <div class="w-full flex justify-between">
                    <p class="lg:text-sm text-xs text-neutral">Sikap</p>
                    <x-badge :type="$badgeType($record->skor_sikap)">{{ $record->skor_sikap }}</x-badge>
                </div>
                <div class="w-full flex justify-between">
                    <p class="lg:text-sm text-xs text-neutral">Perawatan Fasilitas</p>
                    <x-badge :type="$badgeType($record->skor_perawatan_fasilitas)">{{ $record->skor_perawatan_fasilitas }}</x-badge>
                </div>
            </div>
        </x-card>
        @endforeach
    </div>
    @endif

</div>

@endsection