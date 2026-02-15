<?php

namespace App\Notifications;

use App\Models\TravelOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class TravelOrderStatusChanged extends Notification
{
    use Queueable;

    public $travelOrder;

    public function __construct(TravelOrder $travelOrder)
    {
        $this->travelOrder = $travelOrder;
    }

    public function via($notifiable)
    {
        return ['mail', 'database', 'log'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                ->line("O status do seu pedido de viagem para {$this->order->destination} mudou para: {$this->order->status}.")
                ->action('Ver Pedido', url("/api/travel-orders/{$this->order->id}"));
    }

    public function toArray($notifiable)
    {
        return [
            'order_id' => $this->travelOrder->id,
            'status' => $this->travelOrder->status,
        ];
    }
}
