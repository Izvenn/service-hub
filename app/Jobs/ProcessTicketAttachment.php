<?php

namespace App\Jobs;

use App\Models\Ticket;
use App\Models\User;
use App\Notifications\TicketProcessed;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

class ProcessTicketAttachment implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Ticket $ticket) {}

    public function handle(): void
    {
        // Carrega o detalhe para podermos manipular o texto
        $this->ticket->loadMissing(['detail']);

        if (! $this->ticket->attachment_path || ! Storage::disk('public')->exists($this->ticket->attachment_path)) {
            return;
        }

        $content = Storage::disk('public')->get($this->ticket->attachment_path);
        $data = json_decode($content, true);

        // --- FORMATAÇÃO BONITA ---
        $report = "\n\n".str_repeat('=', 40)."\n";
        $report .= str_repeat('-', 40)."\n";

        if ($data && is_array($data)) {
            foreach ($data as $key => $value) {
                $label = ucfirst(str_replace(['_', '-'], ' ', $key));
                $val = is_array($value) ? json_encode($value, JSON_UNESCAPED_UNICODE) : $value;
                $report .= " {$label}: {$val}\n";
            }
        } else {
            $report .= "Conteúdo em Anexo: \n{$content}\n";
        }
        $report .= str_repeat('=', 40);

        // --- LÓGICA DE SUBSTITUIÇÃO ---
        // Removemos a mensagem de "Aguardando..." e colocamos o relatório
        $currentInfo = $this->ticket->detail->technical_info;
        $cleanInfo = str_replace('[Status: Aguardando processamento do anexo...]', $report, $currentInfo);

        $this->ticket->detail()->update([
            'technical_info' => $cleanInfo,
            'payload' => $data ?? ['raw' => $content],
        ]);

        $usersToNotify = User::whereHas('profile', function ($query) {
            $query->where('project_id', $this->ticket->project_id);
        })->get();

        Notification::send($usersToNotify, new TicketProcessed($this->ticket));
    }
}
