<x-card class="flex flex-col justify-between">

    <div class="flex flex-col justify-center items-center">
        <img src="{{ asset('assets/icons/kost-icon.png') }}" alt="Kost" class="w-14 h-auto mb-4">
        <h2 class="lg:text-xl text-md text-black font-semibold text-heading mb-2">
            Belum Terdaftar di Kost
        </h2>

        <p class="text-sm text-neutral mb-6 text-center">
            Anda belum terdaftar di kost manapun. Gunakan kode kost yang diberikan oleh pengelola untuk bergabung. </p>
        <x-form.button
            type="button"
            class="lg:w-48 w-36"
            @click="modalOpen = true; modalType = 'join-kost'">
            Gabung Kost
        </x-form.button>
    </div>

</x-card>