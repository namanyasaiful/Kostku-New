@extends('layouts.pengelola')
@section('title', 'Riwayat Penilaian Penghuni')

@section('content')
<a
    href="{{ route('penghuni.pengelola') }}"
    class="text-sm text-[#313131] flex items-center gap-2 mb-4">
    <span class="text-2xl pb-1 text-[#313131]">
        < </span>
            Kembali
</a>

<div>
    {{-- ================= PAGE HEADER ================= --}}
    <x-page-header
        title="Penilaian Penghuni Saifulloh Fattah"
        description="Detail track record penghuni">
    </x-page-header>

    <div class="grid lg:grid-cols-2 grid-cols-1 lg:gap-8 gap-4">
        <x-card>
            <div class="flex w-full mb-2">
                <div class="w-1/2">
                    <p class="text-xs text-neutral mb-1">Nama Kost</p>
                    <p class="text-xs font-medium">Kost Makmur</p>
                </div>

                <div class="w-1/2">
                    <p class="text-xs text-neutral mb-1">Periode</p>
                    <p class="text-xs font-medium">Jan 2023 - Des 2023 (12 Bulan)</p>
                </div>
            </div>
            <hr>

            <div class=" w-full flex my-4">
                <div class="w-1/2">
                    <p class="text-xs text-neutral mb-1">Alamat</p>
                    <p class="text-xs font-medium">Jl. Pacet No. 123</p>
                </div>
                <div class="w-1/2">
                    <x-form.input name="penilaian-penghuni" type="text" class="!p-4 bg-[#F8F8F8] text-xs" value="Anaknya baik tidak pernah telat membayar." />
                </div>
            </div>
            <hr>

            <div class="flex flex-col gap-4 mt-4">
                <div class="w-full flex justify-between">
                    <p class="text-sm font-medium text-primary">Penilaian Penghuni</p>
                </div>
                <div class="w-full flex justify-between">
                    <p class="lg:text-sm text-xs text-neutral">Pembayaran</p>
                    <x-badge type="success">Baik</x-badge>
                </div>
                <div class="w-full flex justify-between">
                    <p class="lg:text-sm text-xs text-neutral">Sikap</p>
                    <x-badge type="warning">Perlu perhatian</x-badge>
                </div>
                <div class="w-full flex justify-between">
                    <p class="lg:text-sm text-xs text-neutral">Perawatan Fasilitas</p>
                    <x-badge type="success">Baik</x-badge>
                </div>
            </div>
        </x-card>
        <x-card>
            <div class="flex w-full mb-2">
                <div class="w-1/2">
                    <p class="text-xs text-neutral mb-1">Nama Kost</p>
                    <p class="text-xs font-medium">Kost Makmur</p>
                </div>

                <div class="w-1/2">
                    <p class="text-xs text-neutral mb-1">Periode</p>
                    <p class="text-xs font-medium">Jan 2023 - Des 2023 (12 Bulan)</p>
                </div>
            </div>
            <hr>

            <div class=" w-full flex my-4">
                <div class="w-1/2">
                    <p class="text-xs text-neutral mb-1">Alamat</p>
                    <p class="text-xs font-medium">Jl. Pacet No. 123</p>
                </div>
                <div class="w-1/2">
                    <x-form.input name="penilaian-penghuni" type="text" class="!p-4 bg-[#F8F8F8] text-xs" value="Anaknya baik tidak pernah telat membayar." />
                </div>
            </div>
            <hr>

            <div class="flex flex-col gap-4 mt-4">
                <div class="w-full flex justify-between">
                    <p class="text-sm font-medium text-primary">Penilaian Penghuni</p>
                </div>
                <div class="w-full flex justify-between">
                    <p class="lg:text-sm text-xs text-neutral">Pembayaran</p>
                    <x-badge type="success">Baik</x-badge>
                </div>
                <div class="w-full flex justify-between">
                    <p class="lg:text-sm text-xs text-neutral">Sikap</p>
                    <x-badge type="warning">Perlu perhatian</x-badge>
                </div>
                <div class="w-full flex justify-between">
                    <p class="lg:text-sm text-xs text-neutral">Perawatan Fasilitas</p>
                    <x-badge type="success">Baik</x-badge>
                </div>
            </div>
        </x-card>
        <x-card>
            <div class="flex w-full mb-2">
                <div class="w-1/2">
                    <p class="text-xs text-neutral mb-1">Nama Kost</p>
                    <p class="text-xs font-medium">Kost Makmur</p>
                </div>

                <div class="w-1/2">
                    <p class="text-xs text-neutral mb-1">Periode</p>
                    <p class="text-xs font-medium">Jan 2023 - Des 2023 (12 Bulan)</p>
                </div>
            </div>
            <hr>

            <div class=" w-full flex my-4">
                <div class="w-1/2">
                    <p class="text-xs text-neutral mb-1">Alamat</p>
                    <p class="text-xs font-medium">Jl. Pacet No. 123</p>
                </div>
                <div class="w-1/2">
                    <x-form.input name="penilaian-penghuni" type="text" class="!p-4 bg-[#F8F8F8] text-xs" value="Anaknya baik tidak pernah telat membayar." />
                </div>
            </div>
            <hr>

            <div class="flex flex-col gap-4 mt-4">
                <div class="w-full flex justify-between">
                    <p class="text-sm font-medium text-primary">Penilaian Penghuni</p>
                </div>
                <div class="w-full flex justify-between">
                    <p class="lg:text-sm text-xs text-neutral">Pembayaran</p>
                    <x-badge type="success">Baik</x-badge>
                </div>
                <div class="w-full flex justify-between">
                    <p class="lg:text-sm text-xs text-neutral">Sikap</p>
                    <x-badge type="warning">Perlu perhatian</x-badge>
                </div>
                <div class="w-full flex justify-between">
                    <p class="lg:text-sm text-xs text-neutral">Perawatan Fasilitas</p>
                    <x-badge type="success">Baik</x-badge>
                </div>
            </div>
        </x-card>
        <x-card>
            <div class="flex w-full mb-2">
                <div class="w-1/2">
                    <p class="text-xs text-neutral mb-1">Nama Kost</p>
                    <p class="text-xs font-medium">Kost Makmur</p>
                </div>

                <div class="w-1/2">
                    <p class="text-xs text-neutral mb-1">Periode</p>
                    <p class="text-xs font-medium">Jan 2023 - Des 2023 (12 Bulan)</p>
                </div>
            </div>
            <hr>

            <div class=" w-full flex my-4">
                <div class="w-1/2">
                    <p class="text-xs text-neutral mb-1">Alamat</p>
                    <p class="text-xs font-medium">Jl. Pacet No. 123</p>
                </div>
                <div class="w-1/2">
                    <x-form.input name="penilaian-penghuni" type="text" class="!p-4 bg-[#F8F8F8] text-xs" value="Anaknya baik tidak pernah telat membayar." />
                </div>
            </div>
            <hr>

            <div class="flex flex-col gap-4 mt-4">
                <div class="w-full flex justify-between">
                    <p class="text-sm font-medium text-primary">Penilaian Penghuni</p>
                </div>
                <div class="w-full flex justify-between">
                    <p class="lg:text-sm text-xs text-neutral">Pembayaran</p>
                    <x-badge type="success">Baik</x-badge>
                </div>
                <div class="w-full flex justify-between">
                    <p class="lg:text-sm text-xs text-neutral">Sikap</p>
                    <x-badge type="warning">Perlu perhatian</x-badge>
                </div>
                <div class="w-full flex justify-between">
                    <p class="lg:text-sm text-xs text-neutral">Perawatan Fasilitas</p>
                    <x-badge type="success">Baik</x-badge>
                </div>
            </div>
        </x-card>
    </div>
</div>
@endsection