<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushMessage;
use NotificationChannels\WebPush\WebPushChannel;

class PengaduanDibalasNotification extends Notification
{
    public function __construct(public $pengaduan) {}

    public function via($notifiable): array
    {
        return ['database', WebPushChannel::class];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'judul' => 'Pengaduan Dibalas',
            'pesan' => 'Pengaduan "' . $this->pengaduan->judul . '" telah mendapat balasan dari pengelola.',
            'url'   => route('pengaduan.penghuni'),
            'icon'  => 'pengaduan',
        ];
    }

    public function toWebPush($notifiable, $notification): WebPushMessage
    {
        return (new WebPushMessage)
            ->title('Pengaduan Dibalas')
            ->body('Pengaduan "' . $this->pengaduan->judul . '" telah mendapat balasan dari pengelola.')
            ->action('Lihat', route('pengaduan.penghuni'));
    }
}