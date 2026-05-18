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
        $this->ticket->loadMissing('project');

        if (! $this->ticket->attachment_path || ! Storage::disk('public')->exists($this->ticket->attachment_path)) {
            return;
        }

        $content = Storage::disk('public')->get($this->ticket->attachment_path);
        $data = json_decode($content, true);

        $this->ticket->detail()->updateOrCreate(
            ['ticket_id' => $this->ticket->id],
            [

                'payload' => $data ?? ['raw_text' => $content],
            ]
        );
        $this->ticket->detail()->update([
            'payload' => $data ?? ['raw_text' => $content],
        ]);

        $usersToNotify = User::whereHas('profile', function ($query) {
            $query->where('project_id', $this->ticket->project_id);
        })->get();

        Notification::send($usersToNotify, new TicketProcessed($this->ticket));

    }
}
