<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushMessage;
use NotificationChannels\WebPush\WebPushChannel;

class PembayaranBaruNotification extends Notification
{
    public function __construct(public $pembayaran) {}

    public function via($notifiable): array
    {
        return ['database', WebPushChannel::class];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'judul' => 'Pembayaran Baru',
            'pesan' => $this->pembayaran->user->nama . ' telah melakukan pembayaran sebesar Rp ' . number_format($this->pembayaran->nominal, 0, ',', '.'),
            'url'   => route('pembayaran.pengelola'),
            'icon'  => 'pembayaran',
        ];
    }

    public function toWebPush($notifiable, $notification): WebPushMessage
    {
        return (new WebPushMessage)
            ->title('Pembayaran Baru')
            ->body($this->pembayaran->user->nama . ' telah melakukan pembayaran sebesar Rp ' . number_format($this->pembayaran->nominal, 0, ',', '.'))
            ->action('Lihat', route('pembayaran.pengelola'));
    }
}