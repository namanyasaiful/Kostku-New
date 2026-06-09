<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushMessage;
use NotificationChannels\WebPush\WebPushChannel;

class RequestMasukNotification extends Notification
{
    public function __construct(public $penghuni) {}

    public function via($notifiable): array
    {
        return ['database', WebPushChannel::class];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'judul' => 'Request Masuk Kost',
            'pesan' => $this->penghuni->user->nama . ' mengajukan permintaan untuk bergabung ke kost.',
            'url'   => route('penghuni.pengelola'),
            'icon'  => 'penghuni',
        ];
    }

    public function toWebPush($notifiable, $notification): WebPushMessage
    {
        return (new WebPushMessage)
            ->title('Request Masuk Kost')
            ->body($this->penghuni->user->nama . ' mengajukan permintaan untuk bergabung ke kost.')
            ->action('Lihat', route('penghuni.pengelola'));
    }
}