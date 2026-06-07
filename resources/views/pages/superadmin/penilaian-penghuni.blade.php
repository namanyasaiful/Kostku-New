@extends('layouts.superadmin')
@section('title', 'Penilaian Penghuni')
@section('content')

<div
    x-data="{
        activeTab: 'semua',

        init() {
            @if(session('success'))
                this.successMessage = '{{ session('success') }}';
                this.modalType = 'success';
                this.modalOpen = true;
                setTimeout(() => { this.closeModal(); }, 2500);
            @endif
        },

        modalOpen: false,
        modalType: null,
        selectedPengelola: {},

        openModal(type, data = {}) {
            this.selectedPengelola = data;
            this.modalOpen = true;
            this.modalType = type;
        },

        closeModal() {
            this.modalOpen = false;
            this.modalType = null;
        },

        successMessage: '',

        showSuccess(message) {
            this.successMessage = message;
            this.modalType = 'success';
            this.modalOpen = true;
            setTimeout(() => { this.closeModal(); }, 2500);
        }
    }">

    <x-page-header
        title="Penilaian Penghuni"
        description="Validasi penilaian penghuni dari pengelola kost">
    </x-page-header>

    <form method="GET" action="{{ route('penilaian-penghuni.superadmin') }}">
        <x-search-input name="search" placeholder="Cari" value="{{ request('search') }}" />
    </form>

    <div class="bg-white rounded-lg p-4 lg:p-6 mt-4 mb-6">

        <div class="flex lg:gap-6 gap-3 mb-6 min-w-[900px] border-b">
            <button
                @click="activeTab = 'semua'"
                :class="activeTab === 'semua' ? 'border-primary text-primary font-bold' : 'border-transparent text-black font-medium'"
                class="pb-3 border-b-2 text-xs lg:text-sm transition">
                Semua
            </button>
            <button
                @click="activeTab = 'menunggu'"
                :class="activeTab === 'menunggu' ? 'border-primary text-primary font-bold' : 'border-transparent text-black font-medium'"
                class="pb-3 border-b-2 text-xs lg:text-sm transition">
                Menunggu
            </button>
            <button
                @click="activeTab = 'disetujui'"
                :class="activeTab === 'disetujui' ? 'border-primary text-primary font-bold' : 'border-transparent text-black font-medium'"
                class="pb-3 border-b-2 text-xs lg:text-sm transition">
                Disetujui
            </button>
        </div>

        {{-- ================= SEMUA ================= --}}
        <div x-show="activeTab === 'semua'" x-transition>
            <div class="overflow-x-auto">
                <x-table.index>
                    <thead class="sticky top-0 bg-white z-10 border-b border-default">
                        <x-table.tr>
                            <x-table.th>Nama Lengkap</x-table.th>
                            <x-table.th>Nama Kost</x-table.th>
                            <x-table.th>Tanggal Penilaian</x-table.th>
                            <x-table.th>Status</x-table.th>
                            <x-table.th class="text-center">Aksi</x-table.th>
                        </x-table.tr>
                    </thead>
                    <tbody>
                        @foreach($semuaRecord as $record)
                        <x-table.tr>
                            <x-table.td class="font-medium">{{ $record->user->nama ?? '-' }}</x-table.td>
                            <x-table.td>{{ $record->kamar->kost->nama_kost ?? '-' }}</x-table.td>
                            <x-table.td>{{ $record->created_at->format('d/m/Y') }}</x-table.td>
                            <x-table.td>
                                @if($record->status === 'Disetujui')
                                    <x-badge type="success">Disetujui</x-badge>
                                @elseif($record->status === 'Menunggu')
                                    <x-badge type="warning">Menunggu</x-badge>
                                @endif
                            </x-table.td>
                            <x-table.td class="text-center">
                                <x-form.button
                                    @click.prevent="openModal('detail-{{ $record->status === 'Disetujui' ? 'aktif' : 'menunggu' }}', {
                                        id: {{ $record->id }},
                                        name: '{{ addslashes($record->user->nama ?? '-') }}',
                                        pemilik_kost: '{{ addslashes($record->kamar->kost->user->nama ?? '-') }}',
                                        no_hp: '{{ $record->user->telpon ?? '-' }}',
                                        email: '{{ $record->user->email ?? '-' }}',
                                        nama_kost: '{{ addslashes($record->kamar->kost->nama_kost ?? '-') }}',
                                        alamat: '{{ addslashes($record->user->alamat ?? '-') }}',
                                        tanggal_daftar: '{{ $record->created_at->format('d/m/Y') }}',
                                        skor_pembayaran: '{{ $record->skor_pembayaran }}',
                                        skor_sikap: '{{ $record->skor_sikap }}',
                                        skor_perawatan: '{{ $record->skor_perawatan_fasilitas }}',
                                        catatan: '{{ addslashes($record->catatan ?? '-') }}',
                                        bukti: '{{ $record->bukti ? Storage::url($record->bukti) : '' }}'
                                    })"
                                    class="border border-primary bg-transparent !text-primary hover:bg-secondary hover:border-secondary">
                                    Detail
                                </x-form.button>
                            </x-table.td>
                        </x-table.tr>
                        @endforeach
                    </tbody>
                </x-table.index>
            </div>
            <p class="text-xs text-neutral mt-3">Menampilkan {{ $semuaRecord->count() }} data</p>
        </div>

        {{-- ================= MENUNGGU ================= --}}
        <div x-show="activeTab === 'menunggu'" x-transition>
            <div class="overflow-x-auto">
                <x-table.index>
                    <thead class="sticky top-0 bg-white z-10 border-b border-default">
                        <x-table.tr>
                            <x-table.th>Nama Lengkap</x-table.th>
                            <x-table.th>Nama Kost</x-table.th>
                            <x-table.th>Tanggal Penilaian</x-table.th>
                            <x-table.th>Status</x-table.th>
                            <x-table.th class="text-center">Aksi</x-table.th>
                        </x-table.tr>
                    </thead>
                    <tbody>
                        @foreach($menungguRecord as $record)
                        <x-table.tr>
                            <x-table.td class="font-medium">{{ $record->user->nama ?? '-' }}</x-table.td>
                            <x-table.td>{{ $record->kamar->kost->nama_kost ?? '-' }}</x-table.td>
                            <x-table.td>{{ $record->created_at->format('d/m/Y') }}</x-table.td>
                            <x-table.td>
                                <x-badge type="warning">Menunggu</x-badge>
                            </x-table.td>
                            <x-table.td class="text-center">
                                <x-form.button
                                    @click.prevent="openModal('detail-menunggu', {
                                        id: {{ $record->id }},
                                        name: '{{ addslashes($record->user->nama ?? '-') }}',
                                        pemilik_kost: '{{ addslashes($record->kamar->kost->user->nama ?? '-') }}',
                                        no_hp: '{{ $record->user->telpon ?? '-' }}',
                                        email: '{{ $record->user->email ?? '-' }}',
                                        nama_kost: '{{ addslashes($record->kamar->kost->nama_kost ?? '-') }}',
                                        alamat: '{{ addslashes($record->user->alamat ?? '-') }}',
                                        tanggal_daftar: '{{ $record->created_at->format('d/m/Y') }}',
                                        skor_pembayaran: '{{ $record->skor_pembayaran }}',
                                        skor_sikap: '{{ $record->skor_sikap }}',
                                        skor_perawatan: '{{ $record->skor_perawatan_fasilitas }}',
                                        catatan: '{{ addslashes($record->catatan ?? '-') }}',
                                        bukti: '{{ $record->bukti ? Storage::url($record->bukti) : '' }}'
                                    })"
                                    class="border border-primary bg-transparent !text-primary hover:bg-secondary hover:border-secondary">
                                    Detail
                                </x-form.button>
                            </x-table.td>
                        </x-table.tr>
                        @endforeach
                    </tbody>
                </x-table.index>
            </div>
            <p class="text-xs text-neutral mt-3">Menampilkan {{ $menungguRecord->count() }} data</p>
        </div>

        {{-- ================= DISETUJUI ================= --}}
        <div x-show="activeTab === 'disetujui'" x-transition>
            <div class="overflow-x-auto">
                <x-table.index>
                    <thead class="sticky top-0 bg-white z-10 border-b border-default">
                        <x-table.tr>
                            <x-table.th>Nama Lengkap</x-table.th>
                            <x-table.th>Nama Kost</x-table.th>
                            <x-table.th>Tanggal Penilaian</x-table.th>
                            <x-table.th>Status</x-table.th>
                            <x-table.th class="text-center">Aksi</x-table.th>
                        </x-table.tr>
                    </thead>
                    <tbody>
                        @foreach($disetujuiRecord as $record)
                        <x-table.tr>
                            <x-table.td class="font-medium">{{ $record->user->nama ?? '-' }}</x-table.td>
                            <x-table.td>{{ $record->kamar->kost->nama_kost ?? '-' }}</x-table.td>
                            <x-table.td>{{ $record->created_at->format('d/m/Y') }}</x-table.td>
                            <x-table.td>
                                <x-badge type="success">Disetujui</x-badge>
                            </x-table.td>
                            <x-table.td class="text-center">
                                <x-form.button
                                    @click.prevent="openModal('detail-aktif', {
                                        id: {{ $record->id }},
                                        name: '{{ addslashes($record->user->nama ?? '-') }}',
                                        pemilik_kost: '{{ addslashes($record->kamar->kost->user->nama ?? '-') }}',
                                        no_hp: '{{ $record->user->telpon ?? '-' }}',
                                        email: '{{ $record->user->email ?? '-' }}',
                                        nama_kost: '{{ addslashes($record->kamar->kost->nama_kost ?? '-') }}',
                                        alamat: '{{ addslashes($record->user->alamat ?? '-') }}',
                                        tanggal_daftar: '{{ $record->created_at->format('d/m/Y') }}',
                                        skor_pembayaran: '{{ $record->skor_pembayaran }}',
                                        skor_sikap: '{{ $record->skor_sikap }}',
                                        skor_perawatan: '{{ $record->skor_perawatan_fasilitas }}',
                                        catatan: '{{ addslashes($record->catatan ?? '-') }}',
                                        bukti: '{{ $record->bukti ? Storage::url($record->bukti) : '' }}'
                                    })"
                                    class="border border-primary bg-transparent !text-primary hover:bg-secondary hover:border-secondary">
                                    Detail
                                </x-form.button>
                            </x-table.td>
                        </x-table.tr>
                        @endforeach
                    </tbody>
                </x-table.index>
            </div>
            <p class="text-xs text-neutral mt-3">Menampilkan {{ $disetujuiRecord->count() }} data</p>
        </div>

    </div>

    {{-- ================= PAGINATION ================= --}}

    <div x-show="activeTab === 'semua'"><x-pagination :paginator="$semuaRecord" /></div>
    <div x-show="activeTab === 'menunggu'"><x-pagination :paginator="$menungguRecord" /></div>
    <div x-show="activeTab === 'disetujui'"><x-pagination :paginator="$disetujuiRecord" /></div>
    <div x-show="activeTab === 'ditolak'"><x-pagination :paginator="$ditolakRecord" /></div>

    {{-- ================= MODAL ================= --}}
    <x-modal show="modalOpen" maxWidth="lg:max-w-[500px] max-w-[350px]">

        {{-- ================= DETAIL DISETUJUI ================= --}}
        <template x-if="modalType === 'detail-aktif'">
            <div class="relative">

                <button type="button" class="absolute top-0 right-0 text-xl" @click="closeModal()">✕</button>

                <div class="flex items-center justify-between !mb-6">
                    <h2 class="text-xl font-bold">Detail Penilaian Penghuni</h2>
                </div>

                <div class="space-y-4 lg:max-h-[450px] max-h-[300px] overflow-y-auto pr-1">

                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <p class="text-xs text-neutral mb-1">Nama Lengkap</p>
                            <p class="text-xs font-medium" x-text="selectedPengelola.name"></p>
                        </div>
                        <div>
                            <p class="text-xs text-neutral mb-1">Nomor HP</p>
                            <p class="text-xs font-medium" x-text="selectedPengelola.no_hp"></p>
                        </div>
                        <div>
                            <p class="text-xs text-neutral mb-1">Email</p>
                            <p class="text-xs font-medium" x-text="selectedPengelola.email"></p>
                        </div>
                    </div>

                    <hr>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-neutral mb-1">Nama Kost</p>
                            <p class="text-xs font-medium" x-text="selectedPengelola.nama_kost"></p>
                        </div>
                        <div>
                            <p class="text-xs text-neutral mb-1">Pemilik Kost</p>
                            <p class="text-xs font-medium" x-text="selectedPengelola.pemilik_kost"></p>
                        </div>
                    </div>
                    <hr>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-neutral mb-1">Alamat</p>
                            <p class="text-xs font-medium" x-text="selectedPengelola.alamat"></p>
                        </div>
                        <div>
                            <p class="text-xs text-neutral mb-1">Tanggal Penilaian</p>
                            <p class="text-xs font-medium" x-text="selectedPengelola.tanggal_daftar"></p>
                        </div>
                    </div>
                    <hr>

                    <x-form.input label="Ketertiban Pembayaran" name="ketertiban-pembayaran" ::value="selectedPengelola.skor_pembayaran" class="!bg-[#F8F8F8] text-xs" disabled />
                    <x-form.input label="Sikap" name="sikap" ::value="selectedPengelola.skor_sikap" class="!bg-[#F8F8F8] text-xs" disabled />
                    <x-form.input label="Perawatan Fasilitas" name="perawatan-fasilitas" ::value="selectedPengelola.skor_perawatan" class="!bg-[#F8F8F8] text-xs" disabled />
                    <x-form.input label="Catatan Tambahan" name="catatan-tambahan" ::value="selectedPengelola.catatan" class="!bg-[#F8F8F8] text-xs" disabled />

                    <div>
                        <p class="text-xs text-neutral mb-2">Bukti</p>
                        <template x-if="selectedPengelola.bukti">
                            <a :href="selectedPengelola.bukti" target="_blank"
                                class="flex items-center gap-3 bg-[#F8F8F8] rounded-xl px-4 py-3 w-full hover:bg-gray-100 transition no-underline">
                                <img src="{{ asset('assets/icons/pdf-icon.png') }}" class="w-8 h-8 shrink-0">
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-medium text-black truncate">Bukti Penilaian</p>
                                    <p class="text-[10px] text-gray-500 mt-0.5">Klik untuk membuka</p>
                                </div>
                            </a>
                        </template>
                        <template x-if="!selectedPengelola.bukti">
                            <p class="text-xs text-gray-400 italic">Tidak ada bukti</p>
                        </template>
                    </div>

                </div>

                <div class="mt-6">
                    <x-form.button type="button" class="w-full !text-neutral !bg-[#E2E2E2]" disabled>
                        Disetujui
                    </x-form.button>
                </div>

            </div>
        </template>

        {{-- ================= DETAIL MENUNGGU ================= --}}
        <template x-if="modalType === 'detail-menunggu'">
            <div class="relative">

                <button type="button" class="absolute top-0 right-0 text-xl" @click="closeModal()">✕</button>

                <div class="flex items-center justify-between mb-6 pr-6">
                    <h2 class="text-xl font-bold">Detail Penilaian Penghuni</h2>
                </div>

                <div class="space-y-4 lg:max-h-[450px] max-h-[300px] overflow-y-auto pr-1">

                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <p class="text-xs text-neutral mb-1">Nama Lengkap</p>
                            <p class="text-xs font-medium" x-text="selectedPengelola.name"></p>
                        </div>
                        <div>
                            <p class="text-xs text-neutral mb-1">Nomor HP</p>
                            <p class="text-xs font-medium" x-text="selectedPengelola.no_hp"></p>
                        </div>
                        <div>
                            <p class="text-xs text-neutral mb-1">Email</p>
                            <p class="text-xs font-medium" x-text="selectedPengelola.email"></p>
                        </div>
                    </div>

                    <hr>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-neutral mb-1">Nama Kost</p>
                            <p class="text-xs font-medium" x-text="selectedPengelola.nama_kost"></p>
                        </div>
                        <div>
                            <p class="text-xs text-neutral mb-1">Pemilik Kost</p>
                            <p class="text-xs font-medium" x-text="selectedPengelola.pemilik_kost"></p>
                        </div>
                    </div>
                    <hr>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-neutral mb-1">Alamat</p>
                            <p class="text-xs font-medium" x-text="selectedPengelola.alamat"></p>
                        </div>
                        <div>
                            <p class="text-xs text-neutral mb-1">Tanggal Penilaian</p>
                            <p class="text-xs font-medium" x-text="selectedPengelola.tanggal_daftar"></p>
                        </div>
                    </div>
                    <hr>

                    <x-form.input label="Ketertiban Pembayaran" name="ketertiban-pembayaran" value="Baik" class="!bg-[#F8F8F8] text-xs" disabled />
                    <x-form.input label="Sikap" name="sikap" value="Baik" class="!bg-[#F8F8F8] text-xs" disabled />
                    <x-form.input label="Perawatan Fasilitas" name="perawatan-fasilitas" value="Baik" class="!bg-[#F8F8F8] text-xs" disabled />
                    <x-form.input label="Catatan Tambahan" name="catatan-tambahan" value="Anaknya baik tidak pernah telat membayar." class="!bg-[#F8F8F8] text-xs" disabled />

                    <div>
                        <p class="text-xs text-neutral mb-2">Bukti</p>
                        <template x-if="selectedPengelola.bukti">
                            <a :href="selectedPengelola.bukti" target="_blank"
                                class="flex items-center gap-3 bg-[#F8F8F8] rounded-xl px-4 py-3 w-full hover:bg-gray-100 transition no-underline">
                                <img src="{{ asset('assets/icons/pdf-icon.png') }}" class="w-8 h-8 shrink-0">
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-medium text-black truncate">Bukti Penilaian</p>
                                    <p class="text-[10px] text-gray-500 mt-0.5">Klik untuk membuka</p>
                                </div>
                            </a>
                        </template>
                        <template x-if="!selectedPengelola.bukti">
                            <p class="text-xs text-gray-400 italic">Tidak ada bukti</p>
                        </template>
                    </div>

                </div>

                <div class="flex gap-3 mt-6">
                    <x-form.button
                        type="button"
                        class="w-full text-white !bg-red-600 hover:!bg-red-100 hover:!text-red-600"
                        @click="modalType = 'confirm-tolak'">
                        Tolak
                    </x-form.button>
                    <x-form.button
                        type="button"
                        class="w-full text-white !bg-green-600 hover:!bg-green-100 hover:!text-green-600"
                        @click="modalType = 'confirm-setujui'">
                        Setujui
                    </x-form.button>
                </div>

            </div>
        </template>

        {{-- ================= CONFIRM TOLAK ================= --}}
        <template x-if="modalType === 'confirm-tolak'">
            <div class="relative">

                <button type="button" class="absolute top-0 right-0 text-xl" @click="closeModal()">✕</button>

                <h2 class="text-xl font-bold mb-4">Konfirmasi Tolak Penilaian Penghuni</h2>

                <p class="text-xs text-neutral">Apakah Anda yakin ingin menolak penilaian penghuni ini? Tindakan ini tidak dapat dibatalkan.</p>

                <div class="flex gap-3 mt-8">
                    <x-form.button
                        type="button"
                        class="w-full !text-neutral !bg-transparent border-2 !border-neutral hover:!bg-neutral hover:!text-white"
                        @click="modalType = 'detail-menunggu'">
                        Batal
                    </x-form.button>
                    <x-form.button
                        type="button"
                        class="w-full !text-white !bg-red-600 hover:!bg-red-100 hover:!text-red-600"
                        @click="$refs.formTolak.submit()">
                        Tolak
                    </x-form.button>
                </div>

                <form x-ref="formTolak" :action="'/superadmin/penilaian-penghuni/tolak/' + selectedPengelola.id" method="POST" class="hidden">
                    @csrf
                </form>

            </div>
        </template>

        {{-- ================= CONFIRM SETUJUI ================= --}}
        <template x-if="modalType === 'confirm-setujui'">
            <div class="relative">

                <button type="button" class="absolute top-0 right-0 text-xl" @click="closeModal()">✕</button>

                <h2 class="text-xl font-bold mb-4">Konfirmasi Setujui</h2>

                <p class="text-xs text-neutral">Apakah Anda yakin ingin menyetujui pendaftaran pengelola ini? Akun akan segera aktif.</p>

                <div class="flex gap-3 mt-8">
                    <x-form.button
                        type="button"
                        class="w-full !text-neutral !bg-transparent border-2 !border-neutral hover:!bg-neutral hover:!text-white"
                        @click="modalType = 'detail-menunggu'">
                        Batal
                    </x-form.button>
                    <x-form.button
                        type="button"
                        class="w-full !text-white !bg-green-600 hover:!bg-green-100 hover:!text-green-600"
                        @click="$refs.formSetujui.submit()">
                        Setujui
                    </x-form.button>
                </div>

                <form x-ref="formSetujui" :action="'/superadmin/penilaian-penghuni/setujui/' + selectedPengelola.id" method="POST" class="hidden">
                    @csrf
                </form>

            </div>
        </template>

        {{-- ================= SUCCESS ================= --}}
        <template x-if="modalType === 'success'">
            <div class="text-center">
                <div class="flex justify-center mb-4">
                    <img src="{{ asset('assets/icons/success-modal-icon.png') }}" class="w-12">
                </div>
                <h2 class="text-lg font-bold">
                    <span x-text="successMessage"></span>
                </h2>
            </div>
        </template>

    </x-modal>

</div>

@endsection