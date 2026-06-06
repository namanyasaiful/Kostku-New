<div
    x-data="{ 
    sidebarOpen: false,
        notifOpen: false,
        modalOpen: false,
        modalType: null,
        openModal(type) {
            this.modalOpen = true;
            this.modalType = type;
        },
        closeModal() {
            this.modalOpen = false;
            this.modalType = null;
        }
    }"
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
                    title="Dashboard"
                    icon="dashboard-icon"
                    :route="route('dashboard.superadmin')"
                    :active="request()->routeIs('dashboard.superadmin')" />

                <x-sidebar.item
                    title="Manajemen Pengelola"
                    icon="admin-pengelola-icon"
                    :route="route('manajemen-pengelola.superadmin')"
                    :active="request()->routeIs('manajemen-pengelola.superadmin')" />

                <x-sidebar.item
                    title="Manajemen Penghuni"
                    icon="penghuni-icon"
                    :route="route('manajemen-penghuni.superadmin')"
                    :active="request()->routeIs('manajemen-penghuni.superadmin')" />

                <x-sidebar.item
                    title="Penilaian Penghuni"
                    icon="admin-penilaian-penghuni-icon"
                    :route="route('penilaian-penghuni.superadmin')"
                    :active="request()->routeIs('penilaian-penghuni.superadmin')" />

                <x-sidebar.item
                    title="Pengaduan"
                    icon="pengaduan-icon"
                    :route="route('pengaduan-superadmin.superadmin')"
                    :active="request()->routeIs('pengaduan-superadmin.superadmin')" />

                <x-sidebar.item
                    title="Pembayaran"
                    icon="pembayaran-icon"
                    :route="route('pembayaran-superadmin.superadmin')"
                    :active="request()->routeIs('pembayaran-superadmin.superadmin')" />

                <x-sidebar.item
                    title="Log Audit"
                    icon="admin-log-icon"
                    :route="route('log-audit.superadmin')"
                    :active="request()->routeIs('log-audit.superadmin')" />

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

            <form id="logout-form" action="{{ route('superadmin.logout') }}" method="POST" class="hidden">
                @csrf
            </form>
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
                    py-5
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

                    </button>

                    {{-- PROFILE --}}
                    <div class="flex items-center gap-3">
                        <div class="block">

                            <p class="lg:text-sm text-xs font-semibold">
                                Admin
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