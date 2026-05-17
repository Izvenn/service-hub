<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
   

public function store(Request $request)
{

    $validated = $request->validate([
        'project_id' => 'required|exists:projects,id',
        'title' => 'required|string|max:255',
        'attachment' => 'nullable|file|mimes:json,txt|max:2048',
    ]);

   
    $path = $request->file('attachment') 
        ? $request->file('attachment')->store('attachments', 'public') 
        : null;

    $ticket = Ticket::create([
        'project_id' => $validated['project_id'],
        'user_id' => auth()->id(),
        'title' => $validated['title'],
        'status' => 'open',
        'attachment_path' => $path,
    ]);

    if ($path) {
        \App\Jobs\ProcessTicketAttachment::dispatch($ticket);
    }

    return redirect()->back()->with('success', 'Ticket criado e em processamento!');
}
}
