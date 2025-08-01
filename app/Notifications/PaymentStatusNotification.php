<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Pembayaran;

class PaymentStatusNotification extends Notification
{
    use Queueable;

    protected $pembayaran;
    protected $sisaPembayaran;
    protected $statusPembayaran;

    /**
     * Create a new notification instance.
     */
    public function __construct(Pembayaran $pembayaran, $sisaPembayaran, $statusPembayaran)
    {
        $this->pembayaran = $pembayaran;
        $this->sisaPembayaran = $sisaPembayaran;
        $this->statusPembayaran = $statusPembayaran;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        $message = (new MailMessage)
            ->subject('Konfirmasi Pembayaran - NEO Agency')
            ->greeting('Halo ' . ($notifiable->nama ?? $notifiable->name ?? 'User') . '!')
            ->line('Pembayaran Anda telah dikonfirmasi dengan detail sebagai berikut:')
            ->line('**No Pesanan:** ' . $this->pembayaran->order->nomor)
            ->line('**Tanggal Pembayaran:** ' . $this->pembayaran->tgl->format('d F Y'))
            ->line('**Jumlah Dibayar:** Rp ' . number_format($this->pembayaran->jumlah, 0, ',', '.'))
            ->line('**Total Pesanan:** Rp ' . number_format($this->pembayaran->order->total, 0, ',', '.'));

        if ($this->sisaPembayaran > 0) {
            $message->line('**Sisa Pembayaran:** Rp ' . number_format($this->sisaPembayaran, 0, ',', '.'))
                   ->line('Status pembayaran Anda saat ini: **' . $this->statusPembayaran . '**')
                   ->line('Silakan lakukan pembayaran untuk sisa tagihan agar pesanan dapat diproses lebih lanjut.')
                   ->action('Bayar Sisa Tagihan', url('/user/pesanan/' . $this->pembayaran->order->id));
        } else {
            $message->line('**Status Pembayaran:** **LUNAS**')
                   ->line('Selamat! Pembayaran Anda telah lunas. Tim kami akan segera memproses pesanan Anda.')
                   ->action('Lihat Detail Pesanan', url('/user/pesanan/' . $this->pembayaran->order->id));
        }

        return $message->line('Terima kasih telah mempercayai NEO Agency untuk kebutuhan digital marketing Anda.')
                       ->salutation('Salam hangat,<br>Tim NEO Agency');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable)
    {
        return [
            'pembayaran_id' => $this->pembayaran->id,
            'order_id' => $this->pembayaran->order_id,
            'jumlah' => $this->pembayaran->jumlah,
            'sisa_pembayaran' => $this->sisaPembayaran,
            'status_pembayaran' => $this->statusPembayaran
        ];
    }
}