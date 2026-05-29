<x-card>
    <div>
        <h1 class="lg:text-xl text-md font-bold text-black mb-4">Informasi Kost</h1>
        <div class="flex gap-4 items-center">
            <img src="{{ asset('assets/icons/kost-icon.png') }}" alt="Kost" class="w-14 h-auto mb-4">
            <h1 class="lg:text-xl text-md font-bold text-primary mb-4">{{ $penghuni?->kamar?->kost?->nama_kost ?? $penghuni?->kamar?->kost?->kode_kost ?? 'Kost' }}</h1>
        </div>
        <div class="flex flex-row lg:gap-48 gap-14">
            <div>
                <p class="text-xs text-neutral mb-2">Nomor Kamar</p>
                <P class="text-black text-sm font-bold">{{ $penghuni?->kamar?->nomor_kamar ?? '-' }}</P>

            </div>
            <div>
                <p class="text-xs text-neutral mb-2">Tanggal Masuk</p>
                <P class="text-black text-sm font-bold">{{ $penghuni?->tanggal_masuk ? \Carbon\Carbon::parse($penghuni?->tanggal_masuk)->format('d F Y') : '-' }}</P>
            </div>
        </div>
    </div>
</x-card>
