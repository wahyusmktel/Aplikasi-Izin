<?php

namespace App\Notifications;

use App\Models\Perizinan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class IzinDisetujuiNotification extends Notification
{
    use Queueable;

    protected $perizinan;

    public function __construct(Perizinan $perizinan)
    {
        $this->perizinan = $perizinan;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'perizinan_id' => $this->perizinan->id,
            'title' => 'Izin Disetujui',
            'message' => 'Pengajuan izin Anda pada tanggal ' . \Carbon\Carbon::parse($this->perizinan->tanggal_izin)->isoFormat('D MMM YYYY') . ' telah disetujui.',
        ];
    }
}
