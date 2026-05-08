<x-card>
    <h1 class="lg:text-xl text-md font-bold text-primary mb-4">Profil Kamu</h1>
    <div class="grid lg:grid-cols-2 grid-cols-1 justify-around lg:gap-8 gap-4">
        <div class="flex gap-4 items-start">
            <img src="{{ asset('assets/icons/profil-icon.png') }}" alt="Profile" class="w-4 h-auto">
            <div class="flex flex-col gap-1">
                <p class="text-xs text-neutral">Nama Lengkap</p>
                <p class="text-sm text-black font-bold">Saifulloh Fattah</p>
            </div>
        </div>
        <div class="flex gap-4 items-start">
            <img src="{{ asset('assets/icons/email-icon.png') }}" alt="Profile" class="w-4 h-auto">
            <div class="flex flex-col gap-1">
                <p class="text-xs text-neutral">Email</p>
                <p class="text-sm text-black font-bold">saifulloh1@gmail.com</p>
            </div>
        </div>
        <div class="flex gap-4 items-start">
            <img src="{{ asset('assets/icons/telepon-icon.png') }}" alt="Profile" class="w-5 h-auto">
            <div class="flex flex-col gap-1">
                <p class="text-xs text-neutral">Nomor Telepon</p>
                <p class="text-sm text-black font-bold">081234567890</p>
            </div>
        </div>
        <div class="flex gap-4 items-start">
            <img src="{{ asset('assets/icons/location-icon.png') }}" alt="Profile" class="w-5 h-auto">
            <div class="flex flex-col gap-1">
                <p class="text-xs text-neutral">Alamat</p>
                <p class="text-sm text-black font-bold">Jl. Sudirman No. 123</p>
            </div>
        </div>
    </div>
</x-card>