{{-- OVERLAY --}}
<div
    x-show="notifOpen"
    x-transition.opacity
    class="fixed inset-0 bg-black/40 z-[998]"
    @click="notifOpen = false"
    style="display: none;">
</div>

{{-- PANEL NOTIFIKASI --}}
<div
    x-show="notifOpen"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0 translate-y-2"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 translate-y-2"
    class="fixed z-[999] bg-white rounded-xl shadow-xl border border-gray-100 top-14 left-2 right-2 lg:top-16 lg:left-auto lg:right-4 lg:max-w-xl"
    style="display: none;">

    {{-- HEADER --}}
    <div class="flex items-center justify-between px-4 py-3 border-b">
        <div class="flex items-center gap-2">
            <h2 class="text-sm lg:text-md font-bold">Notifikasi</h2>
            {{-- BADGE JUMLAH --}}
            <span class="bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">2</span>
        </div>
        <button
            type="button"
            class="text-lg text-neutral hover:text-black"
            @click="notifOpen = false">
            ✕
        </button>
    </div>

    {{-- BODY --}}
    <div class="space-y-2 max-h-[60vh] lg:max-h-[400px] overflow-y-auto p-3 lg:p-4">

        <div class="border rounded-md p-3 lg:p-4 hover:bg-gray-50 transition cursor-pointer">
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 lg:w-10 lg:h-10 rounded-lg bg-[#FEF5B2] flex items-center justify-center shrink-0">
                    <img src="{{ asset('assets/icons/Information-warning.png') }}" class="w-4 h-4 lg:w-5 lg:h-5">
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="text-xs lg:text-sm font-semibold leading-snug">
                        Pembayaran akan jatuh tempo 3 hari lagi
                    </h3>
                    <p class="text-[11px] lg:text-xs text-neutral my-1 leading-relaxed">
                        Pembayaran untuk periode April 2026 akan jatuh tempo 3 hari lagi (11 April 2026)
                    </p>
                    <p class="text-[10px] lg:text-[11px] text-neutral">2 jam lalu</p>
                </div>
            </div>
        </div>

        <div class="border rounded-md p-3 lg:p-4 hover:bg-gray-50 transition cursor-pointer">
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 lg:w-10 lg:h-10 rounded-lg bg-[#FFC5BF] flex items-center justify-center shrink-0">
                    <img src="{{ asset('assets/icons/Information-danger.png') }}" class="w-4 h-4 lg:w-5 lg:h-5">
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="text-xs lg:text-sm font-semibold leading-snug">
                        Pembayaran jatuh tempo hari ini
                    </h3>
                    <p class="text-[11px] lg:text-xs text-neutral my-1 leading-relaxed">
                        Jangan lupa untuk melakukan pembayaran periode Mei 2026
                    </p>
                    <p class="text-[10px] lg:text-[11px] text-neutral">1 hari yang lalu</p>
                </div>
            </div>
        </div>

    </div>

</div>