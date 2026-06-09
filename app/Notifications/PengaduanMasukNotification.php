<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushMessage;
use NotificationChannels\WebPush\WebPushChannel;

class PengaduanMasukNotification extends Notification
{
    public function __construct(public $pengaduan) {}

    public function via($notifiable): array
    {
        return ['database', WebPushChannel::class];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'judul' => 'Pengaduan Baru',
            'pesan' => 'Pengaduan "' . $this->pengaduan->judul . '" masuk dari ' . $this->pengaduan->user->nama,
            'url'   => route('pengaduan.pengelola'),
            'icon'  => 'pengaduan',
        ];
    }

    public function toWebPush($notifiable, $notification): WebPushMessage
    {
        return (new WebPushMessage)
            ->title('Pengaduan Baru')
            ->body('Pengaduan "' . $this->pengaduan->judul . '" masuk dari ' . $this->pengaduan->user->nama)
            ->action('Lihat', route('pengaduan.pengelola'));
    }
}