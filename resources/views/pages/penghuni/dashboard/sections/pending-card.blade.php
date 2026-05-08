<x-card class="flex flex-col justify-between">

    <div class="flex flex-col justify-center items-center text-center">
        <img src="{{ asset('assets/icons/kost-icon.png') }}" alt="Kost" class="w-14 h-auto mb-4">
        <h2 class="lg:text-xl text-md text-primary font-semibold text-heading mb-2">
            Belum Bergabung Kost
        </h2>

        <p class="text-sm text-neutral mb-6">
            Masukkan kode kost untuk mulai menggunakan layanan kami </p>
        <x-badge type="warning" class="w-48 text-center py-3">Menunggu</x-badge>
    </div>

</x-card>