@extends('layouts.penghuni')
@section('title', 'Pembayaran Penghuni')

@section('content')
<div x-data="pembayaranPage({{ json_encode($pending ? ['id' => $pending->id, 'id_pembayaran' => $pending->id_pembayaran, 'nominal' => $pending->nominal, 'tipe_pembayaran' => $pending->tipe_pembayaran, 'tanggal_pembayaran' => $pending->tanggal_pembayaran] : null) }})" @if(session('payment_status')==='finish' ) @init="pollHistoryAfterPayment()" data-payment-finish @endif>
    <x-page-header
        title="Pembayaran"
        description="Tagihan dan riwayat pembayaran Anda">
    </x-page-header>

    @if(session('payment_status'))
    <div class="mb-4 p-4 rounded-lg bg-green-50 border border-green-200 text-green-800" x-data @if(session('payment_status')==='finish' ) x-init="setTimeout(() => window.location.reload(), 2000)" @endif>
        @if(session('payment_status') === 'finish')
        ✅ Pembayaran berhasil. Terima kasih. Halaman akan dimuat ulang...
        @elseif(session('payment_status') === 'unfinish')
        Pembayaran belum selesai. Silakan lanjutkan kembali.
        @elseif(session('payment_status') === 'error')
        Terjadi kesalahan pada proses pembayaran.
        @endif
    </div>
    @endif

    <x-card class="mb-4">
        <div class="flex flex-col lg:gap-8 gap-4">
            <div>
                <p class="text-neutral text-xs font-medium mb-2">Total Tagihan</p>
                <h1 class="text-primary lg:text-3xl text-2xl font-bold">
                    @if($pending)
                    Rp{{ number_format($pending->nominal, 0, ',', '.') }}
                    @else
                    Rp0
                    @endif
                </h1>
            </div>
            <div class="flex lg:flex-row lg:gap-96 gap-20">
                <div>
                    <p class="text-xs text-neutral mb-2">Periode Tagihan</p>
                    <p class="text-black text-sm font-bold">
                        @if($pending)
                        {{ \Carbon\Carbon::parse($pending->tanggal_pembayaran)->format('F Y') }}
                        @else
                        -
                        @endif
                    </p>
                </div>
                <div>
                    <p class="text-xs text-neutral mb-2">Jatuh Tempo</p>
                    <p class="text-black text-sm font-bold">
                        @if($pending)
                        {{ \Carbon\Carbon::parse($pending->tanggal_pembayaran)->format('d F Y') }}
                        @else
                        -
                        @endif
                    </p>
                </div>
            </div>

            @if($pending)
            <div class="flex gap-8">
                <button
                    @click="modalOpen = true"
                    class="w-full bg-primary hover:bg-secondary text-white hover:text-primary lg:text-md text-sm font-bold lg:p-3 p-2 rounded-md">
                    @if($pending->tipe_pembayaran === 'cicilan') Bayar Cicilan @else Bayar Sekarang @endif
                </button>
            </div>
            @else
            <div class="flex gap-8">
                <button
                    disabled
                    class="w-full bg-gray-200 text-gray-400 cursor-not-allowed lg:text-md text-sm font-bold lg:p-3 p-2 rounded-md">
                    Tidak ada tagihan aktif
                </button>
            </div>
            @endif

        </div>
    </x-card>

    <x-card>
        <h1 class="text-black text-xl font-bold mb-4">Riwayat Pembayaran</h1>
        <div id="historyContainer">
            <x-table.index class="min-w-[700px]">
                <thead class="sticky top-0 bg-white z-10 border-b border-default">
                    <x-table.tr>
                        <x-table.th>Tanggal Pembayaran</x-table.th>
                        <x-table.th>Jenis</x-table.th>
                        <x-table.th>Nominal</x-table.th>
                        <x-table.th>Status</x-table.th>
                        <x-table.th>Aksi</x-table.th>
                    </x-table.tr>
                </thead>
                <tbody>
                    @forelse($history as $item)
                    <x-table.tr>
                        <x-table.td class="font-medium text-heading">{{ \Carbon\Carbon::parse($item->tanggal_pembayaran)->format('d/m/Y') }}</x-table.td>
                        <x-table.td>{{ $item->tipe_pembayaran === 'cicilan' ? 'Cicilan' : 'Lunas' }}</x-table.td>
                        <x-table.td>Rp{{ number_format($item->nominal, 0, ',', '.') }}</x-table.td>
                        <x-table.td>
                            <x-badge type="success">Berhasil</x-badge>
                        </x-table.td>
                        <x-table.td>
                            <a href="#" class="font-medium text-primary hover:underline">Lihat Struk</a>
                        </x-table.td>
                    </x-table.tr>
                    @empty
                    <x-table.tr>
                        <x-table.td colspan="5" class="text-center text-neutral">Belum ada riwayat pembayaran.</x-table.td>
                    </x-table.tr>
                    @endforelse
                </tbody>
            </x-table.index>
        </div>
    </x-card>

    <x-modal show="modalOpen" maxWidth="lg:max-w-[450px] max-w-[350px]">
        <div class="relative">
            <button
                type="button"
                class="absolute top-0 right-0 text-neutral hover:text-black text-xl font-bold"
                @click="modalOpen = false; alertMessage = null;">
                ✕
            </button>

            <h2 class="text-xl font-bold mb-8">Konfirmasi Pembayaran</h2>

            <div class="flex flex-col gap-4">
                <div class="flex justify-between border-b-2">
                    <p class="text-xs text-neutral">Tagihan</p>
                    <p class="text-sm text-black font-semibold">
                        <span x-text="payment ? 'Rp' + payment.nominal.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') : 'Rp0'"></span>
                    </p>
                </div>
                <div class="flex justify-between border-b-2">
                    <p class="text-md text-black font-medium">Jenis Pembayaran</p>
                    <p class="text-md text-primary font-semibold" x-text="payment ? (payment.tipe_pembayaran === 'cicilan' ? 'Cicilan' : 'Lunas') : '-' "></p>
                </div>
            </div>

            <template x-if="alertMessage">
                <div class="mt-4 p-3 rounded-md bg-red-50 text-red-700">
                    <p x-text="alertMessage"></p>
                </div>
            </template>

            <button
                type="button"
                class="w-full bg-primary hover:bg-secondary text-white hover:text-primary lg:text-md text-sm font-bold lg:p-3 p-2 rounded-md mt-8"
                :disabled="isLoading || !payment"
                @click="payNow()">
                <span x-text="isLoading ? 'Memproses...' : 'Bayar Sekarang'"></span>
            </button>
        </div>
    </x-modal>

    <script src="{{ config('midtrans.isProduction') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ config('midtrans.clientKey') }}"></script>
    <script>
        function pembayaranPage(payment) {
            return {
                modalOpen: false,
                isLoading: false,
                alertMessage: null,
                statusPolling: false,
                payment: payment,

                payNow() {
                    if (!this.payment) {
                        this.alertMessage = 'Tidak ada tagihan aktif untuk dibayar.';
                        return;
                    }

                    this.isLoading = true;
                    this.alertMessage = null;

                    axios.post('/payment/create', {
                            pembayaran_id: this.payment.id,
                        })
                        .then(response => {
                            const snapToken = response.data.snap_token;
                            const paymentId = response.data.payment_id;

                            // Midtrans proses VA & konfirmasi final dilakukan oleh callback/webhook,
                            // sehingga di sini cukup lakukan polling status sampai menjadi 'lunas'.
                            this.pollPaymentStatus();

                            if (!snapToken) {
                                throw new Error('Token pembayaran tidak ditemukan.');
                            }

                            window.snap.pay(snapToken, {
                                onSuccess: (result) => {
                                    window.location.href = '/payment/finish';
                                },
                                onPending: (result) => {
                                    window.location.href = `/payment/pending/${paymentId}`;
                                },
                                onError: (result) => {
                                    this.alertMessage = 'Terjadi kesalahan pada proses pembayaran. Silakan coba lagi.';
                                    console.error(result);
                                },
                                onClose: () => {
                                    this.alertMessage = 'Pembayaran dibatalkan. Silakan coba lagi.';
                                }
                            });
                        })
                        .catch(error => {
                            this.alertMessage = error.response?.data?.message || error.message;
                        })
                        .finally(() => {
                            this.isLoading = false;
                        });
                },

                pollHistoryAfterPayment() {
                    if (!document.querySelector('[data-payment-finish]')) {
                        return;
                    }

                    let pollCount = 0;
                    const maxPolls = 10; // Max 30 detik polling

                    const check = () => {
                        axios.get('/payment/history')
                            .then(response => {
                                if (response.data.history && response.data.history.length > 0) {
                                    // History berhasil ter-update, refresh halaman
                                    window.location.reload();
                                } else {
                                    pollCount++;
                                    if (pollCount < maxPolls) {
                                        setTimeout(check, 3000);
                                    }
                                }
                            })
                            .catch(error => {
                                console.error('History poll error:', error);
                                pollCount++;
                                if (pollCount < maxPolls) {
                                    setTimeout(check, 3000);
                                }
                            });
                    };

                    check();
                },

                extractVaNumber(result) {
                    if (!result) {
                        return '';
                    }

                    if (result.va_numbers && result.va_numbers.length > 0) {
                        return result.va_numbers.map(item => item.va_number).join(', ');
                    }

                    if (result.permata_va_number) {
                        return result.permata_va_number;
                    }

                    if (result.bill_key && result.biller_code) {
                        return `${result.biller_code} / ${result.bill_key}`;
                    }

                    return '';
                },

                pollPaymentStatus() {
                    if (this.statusPolling || !this.payment) {
                        return;
                    }

                    this.statusPolling = true;
                    const check = () => {
                        axios.get(`/payment/status/${this.payment.id}`)
                            .then(response => {
                                if (response.data.status === 'lunas') {
                                    window.location.href = '/payment/finish';
                                    return;
                                }

                                setTimeout(check, 5000);
                            })
                            .catch(error => {
                                console.error('Status polling error:', error);
                                this.statusPolling = false;
                            });
                    };

                    check();
                },

                simulateSettlement() {
                    if (!this.payment) {
                        this.alertMessage = 'Tidak ada pembayaran untuk disimulasikan.';
                        return;
                    }

                    this.isLoading = true;
                    axios.post(`/payment/simulate/${this.payment.id}/settlement`)
                        .then(() => {
                            window.location.href = '/payment/finish';
                        })
                        .catch(error => {
                            this.alertMessage = error.response?.data?.message || 'Gagal mensimulasikan pembayaran.';
                        })
                        .finally(() => {
                            this.isLoading = false;
                        });
                }
            }
        }
    </script>
</div>
@endsection