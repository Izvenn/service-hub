<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketProcessed extends Notification
{
    use Queueable;

    // Recebe o ticket no construtor
    public function __construct(public Ticket $ticket) {}

    public function via(object $notifiable): array
    {
        // 'mail' envia o e-mail, 'database' salva no banco para mostrar um sininho na UI
        return ['mail', 'database']; 
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Ticket Processado: ' . $this->ticket->title)
            ->greeting('Olá, ' . $notifiable->name)
            ->line('O anexo técnica do seu ticket foi processado com sucesso.')
            ->line('Título: ' . $this->ticket->title)
            ->action('Visualizar Detalhes', url('/tickets/' . $this->ticket->id))
            ->line('Obrigado por usar o ServiceHub!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'ticket_id' => $this->ticket->id,
            'title' => $this->ticket->title,
            'message' => 'Seu ticket foi processado assincronamente.'
        ];
    }
}
