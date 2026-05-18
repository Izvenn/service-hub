<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessTicketAttachment;
use App\Models\Project;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TicketController extends Controller
{
    public function create()
    {
        $projects = Project::all(['id', 'name']);

        return inertia('Tickets/Create', [
            'projects' => $projects,
        ]);
    }

    public function index()
    {
        $user = auth()->user();

        $profile = $user->profile;

        $tickets = Ticket::query()
            ->with(['project', 'user']) 
            ->where(function ($query) use ($user, $profile) {
                // Regra 1: Tickets que eu criei
                $query->where('user_id', $user->id);

                
                if ($profile && $profile->project_id) {
                    $query->orWhere('project_id', $profile->project_id);
                }
            })
            ->latest()
            ->paginate(10); 

        return Inertia::render('Tickets/Index', [
            'tickets' => $tickets,
        ]);
    }

public function store(Request $request)
{
    $validated = $request->validate([
        'project_id' => 'required|exists:projects,id',
        'title'      => 'required|string|max:255',
        'technical_info' => 'nullable|string|max:5000', 
        'attachment' => 'nullable|file|mimes:json,txt|max:2048',
    ]);

    // Upload do anexo
    $path = $request->file('attachment')
        ? $request->file('attachment')->store('attachments', 'public')
        : null;

    $ticket = Ticket::create([
        'project_id'      => $validated['project_id'],
        'company_id'      => auth()->user()->profile->company_id,
        'user_id'         => auth()->id(),
        'title'           => $validated['title'],
        'status'          => 'open',
        'attachment_path' => $path,
    ]);

    $userDescription = $validated['technical_info'] ?? 'Sem descrição técnica fornecida.';
    $attachmentStatus = $path ? "\n\n[Status: Aguardando processamento do anexo...]" : "";

    $ticket->detail()->create([
        'technical_info' => $userDescription . $attachmentStatus,
    ]);

    if ($path) {
        ProcessTicketAttachment::dispatch($ticket);
    }

    return redirect()->route('tickets.index')->with('success', 'Ticket criado com sucesso!');
}

    public function show(Ticket $ticket)
    {
        $ticket->load(['detail', 'project']);

        return inertia('Tickets/Show', [
            'ticket' => $ticket,
        ]);
    }
}
