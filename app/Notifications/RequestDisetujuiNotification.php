<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushMessage;
use NotificationChannels\WebPush\WebPushChannel;

class RequestDisetujuiNotification extends Notification
{
    public function __construct(public $penghuni) {}

    public function via($notifiable): array
    {
        return ['database', WebPushChannel::class];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'judul' => 'Request Kost Disetujui',
            'pesan' => 'Selamat! Permintaan bergabung kost kamu telah disetujui. Kamu sudah terdaftar di kamar ' . optional($this->penghuni->kamar)->nomor_kamar . '.',
            'url'   => route('dashboard.penghuni'),
            'icon'  => 'penghuni',
        ];
    }

    public function toWebPush($notifiable, $notification): WebPushMessage
    {
        return (new WebPushMessage)
            ->title('Request Kost Disetujui')
            ->body('Selamat! Permintaan bergabung kost kamu telah disetujui.')
            ->action('Lihat', route('dashboard.penghuni'));
    }
}