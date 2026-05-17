<?php

namespace App\Jobs;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ProcessTicketAttachment implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Ticket $ticket) {}

    public function handle(): void
    {
        // 1. Verifica se o arquivo realmente existe
        if (! $this->ticket->attachment_path || ! Storage::disk('public')->exists($this->ticket->attachment_path)) {
            return;
        }

        // 2. Lê o conteúdo do arquivo
        $content = Storage::disk('public')->get($this->ticket->attachment_path);
        $data = json_decode($content, true);

        // 3. Cria o TicketDetail (Relacionamento 1:1)
        // Aqui simulamos o "enriquecimento" salvando metadados técnicos
        $this->ticket->detail()->create([
            'technical_info' => 'Processamento automatizado realizado em '.now()->format('d/m/Y H:i'),
            'payload' => $data ?? ['raw_text' => $content], // Se não for JSON, salva como texto puro
        ]);

        // 4. Atualiza o status do Ticket
        $this->ticket->update(['status' => 'completed']);

        // Dica: Aqui você poderia disparar uma Notificação (Mail ou Database)
    }
}
