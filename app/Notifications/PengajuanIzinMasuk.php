<?php

namespace App\Notifications;

use App\Models\Perizinan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PengajuanIzinMasuk extends Notification
{
    use Queueable;

    protected $perizinan;

    /**
     * Create a new notification instance.
     */
    public function __construct(Perizinan $perizinan)
    {
        $this->perizinan = $perizinan;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database']; // Kita hanya akan simpan ke database
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'perizinan_id' => $this->perizinan->id,
            'title' => 'Pengajuan Izin Baru', // <-- Ganti 'nama_siswa' menjadi 'title'
            'message' => 'Siswa a/n ' . $this->perizinan->user->name . ' mengajukan izin tidak masuk.', // <-- Ganti 'pesan' menjadi 'message'
        ];
    }
}
