@php
$penghuni = \App\Models\Penghuni::where('user_id', Auth::id())
->where('status_request', 'disetujui')
->whereNull('tanggal_keluar')
->first();

$canAccessFeature = $penghuni ? true : false;
@endphp
<div
    x-data="{ 
        sidebarOpen: false,
        notifOpen: false,
        modalOpen: false,
        modalType: null,
        notifs: [],
        unread: 0,
        async loadNotifs() {
            const res = await fetch('{{ route('notifikasi.index') }}');
            const data = await res.json();
            this.notifs = data.notifs;
            this.unread = data.unread;
        },
        openModal(type) {
            this.modalOpen = true;
            this.modalType = type;
        },
        closeModal() {
            this.modalOpen = false;
            this.modalType = null;
        }
    }"
    x-init="loadNotifs()"
    class="min-h-screen flex">

    {{-- ================= SIDEBAR OVERLAY MOBILE ================= --}}
    <div
        x-show="sidebarOpen"
        x-transition.opacity
        class="fixed inset-0 bg-black/40 z-40 lg:hidden"
        @click="sidebarOpen = false">
    </div>

    {{-- ================= SIDEBAR ================= --}}
    <aside
        class="
        fixed lg:sticky
        top-0 left-0
        inset-y-0
        z-50
        w-72
        h-screen
        bg-white
        border-r border-gray-200
        transform
        transition-transform
        duration-300
        overflow-y-auto
        lg:translate-x-0
    "
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">

        <div class="flex flex-col h-auto p-6">

            {{-- LOGO --}}
            <div class="mb-4 flex items-center justify-center">
                <img
                    src="{{ asset('assets/images/logo-auth.png') }}"
                    class="w-32">
            </div>

            <div class="border-secondary border border-1 rounded-sm my-4"></div>

            {{-- MENU --}}
            <nav class="space-y-2 flex-1">

                <x-sidebar.item
                    title="Beranda"
                    icon="dashboard-icon"
                    :route="route('dashboard.penghuni')"
                    :active="request()->routeIs('dashboard.penghuni')" />

                @if($canAccessFeature)
                <x-sidebar.item
                    title="Pembayaran"
                    icon="pembayaran-icon"
                    :route="route('pembayaran.penghuni')"
                    :active="request()->routeIs('pembayaran.penghuni')" />
                @else
                <div
                    class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-400 cursor-not-allowed opacity-60">
                    <img
                        src="{{ asset('assets/icons/pembayaran-icon.png') }}"
                        class="w-5 h-5 grayscale">
                    <span>Pembayaran</span>
                </div>
                @endif

                @if($canAccessFeature)
                <x-sidebar.item
                    title="Pengaduan"
                    icon="pengaduan-icon"
                    :route="route('pengaduan.penghuni')"
                    :active="request()->routeIs('pengaduan.penghuni')" />
                @else
                <div
                    class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-400 cursor-not-allowed opacity-60">
                    <img
                        src="{{ asset('assets/icons/pengaduan-icon.png') }}"
                        class="w-5 h-5 grayscale">
                    <span>Pengaduan</span>
                </div>
                @endif

            </nav>

            <div class="border-secondary border border-1 rounded-sm my-4"></div>

            {{-- LOGOUT --}}
            <a
                href="#"
                class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-[#F5F6FA] cursor-pointer"
                @click.prevent="openModal('confirm-logout')">

                <img src="{{ asset('assets/icons/logout-icon.png') }}" class="w-5 h-5">

                <span class="text-[#E73D2E]">
                    Logout
                </span>
            </a>
        </div>

    </aside>

    {{-- ================= CONTENT ================= --}}
    <div class="flex-1 flex flex-col min-w-0">

        {{-- ================= TOPBAR ================= --}}
        <header
            class="
                    sticky top-0 z-30
                    bg-white
                    border-b border-gray-200
                    px-4 lg:px-8
                    py-2
                ">
            <div class="flex items-center justify-between">
                {{-- LEFT --}}
                <div class="flex items-center gap-4">
                    {{-- HAMBURGER --}}
                    <button
                        class="lg:hidden text-primary text-xl"
                        @click="sidebarOpen = true">
                        ☰
                    </button>
                </div>
                {{-- RIGHT --}}
                <div class="flex items-center gap-2">
                    
                    {{-- NOTIFICATION BELL --}}
                    <div>
                        <button @click="notifOpen = !notifOpen; loadNotifs()" class="relative w-11 h-11 flex items-center justify-center">
                            <img src="{{ asset('assets/icons/notif-icon.png') }}" class="w-5 h-5">
                            <span x-show="unread > 0" class="absolute top-2 right-2 w-4 h-4 rounded-full bg-red-500 text-white text-[9px] flex items-center justify-center" x-text="unread"></span>
                        </button>

                        {{-- DROPDOWN --}}
                        <div x-show="notifOpen" @click.outside="notifOpen = false"
                            class="absolute right-4 top-16 w-96 bg-white rounded-xl shadow-lg border border-gray-100 z-50 overflow-hidden">
                            
                            <div class="flex items-center justify-between px-4 py-3 border-b">
                                <span class="font-semibold text-sm">Notifikasi</span>
                                <a href="{{ route('notifikasi.readAll') }}" class="text-xs text-primary hover:underline">Tandai semua dibaca</a>
                            </div>

                            <div class="max-h-72 overflow-y-auto divide-y">
                                <template x-for="notif in notifs" :key="notif.id">
                                    <a :href="notif.data.url" class="flex gap-3 px-4 py-3 hover:bg-gray-50 transition"
                                        :class="notif.read_at ? 'opacity-60' : 'bg-blue-50/40'">
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs font-semibold" x-text="notif.data.judul"></p>
                                            <p class="text-xs text-gray-500 mt-0.5 leading-relaxed whitespace-normal" x-text="notif.data.pesan"></p>
                                        </div>
                                    </a>
                                </template>
                                <div x-show="notifs.length === 0" class="px-4 py-6 text-center text-xs text-gray-400">
                                    Belum ada notifikasi
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- PROFILE --}}
                    <div class="flex items-center gap-3">
                        <div class="block">
                            <p class="lg:text-sm text-xs font-semibold">
                                {{ Auth::user()->nama }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        {{-- ================= PAGE CONTENT ================= --}}
        <main class="flex-1 p-4 lg:p-8 overflow-x-hidden bg-[#F5F6FA]">
            @yield('content')
        </main>
    </div>
    {{-- <x-notification-modal /> --}}

    <x-modal show="modalOpen" maxWidth="lg:max-w-[450px] max-w-[350px]">
        {{-- ====================================================== --}}
        {{-- ================= LOGOUT CONFIRMATION ================ --}}
        {{-- ====================================================== --}}
        <template x-if="modalType === 'confirm-logout'">
            <div class="relative">
                <button
                    type="button"
                    class="absolute top-0 right-0 text-xl"
                    @click="closeModal()">
                    ✕
                </button>
                <h2 class="text-xl font-bold mb-4">
                    Konfirmasi Keluar Akun
                </h2>
                <p class="text-xs text-neutral">Apakah Anda yakin ingin keluar dari akun?</p>
                <div class="flex gap-3 mt-8">
                    <x-form.button
                        type="button"
                        class="w-full !text-neutral !bg-transparent border-2 !border-neutral hover:!bg-neutral hover:!text-white"
                        @click="closeModal()">
                        Batal
                    </x-form.button>
                    <x-form.button
                        type="button"
                        class="w-full !text-white !bg-red-600 hover:!bg-red-100 hover:!text-red-600"
                        @click="$refs.logoutForm.submit()">
                        Logout
                    </x-form.button>
                </div>
                <form x-ref="logoutForm" action="{{ route('penghuni.logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
            </div>
        </template>
    </x-modal>
</div>