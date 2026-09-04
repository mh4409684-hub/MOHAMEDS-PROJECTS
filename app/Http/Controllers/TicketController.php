<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTicketRequest;
use App\Models\Category;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $statusFilter = request('status');

        $query = Ticket::with(['category', 'requester'])
            ->when($user && ! $user->hasRole('Admin') && ! $user->hasRole('Staff Member'), function ($query) use ($user) {
                $query->where('requester_id', $user->id);
            })
            ->when($statusFilter === 'pending', function ($query) {
                $query->whereIn('status', ['new', 'seen', 'in_progress', 'pending_requester']);
            })
            ->when(in_array($statusFilter, ['resolved', 'solved', 'done', 'closed'], true), function ($query) {
                $query->whereIn('status', ['resolved', 'done', 'closed']);
            })
            ->when($statusFilter && ! in_array($statusFilter, ['pending', 'resolved', 'solved', 'done', 'closed'], true), fn ($query) => $query->where('status', $statusFilter))
            ->when(request('priority'), fn ($query) => $query->where('priority', request('priority')))
            ->when(request('category_id'), fn ($query) => $query->where('category_id', request('category_id')))
            ->latest();

        $tickets = $query->get();

        return view('tickets.index', compact('tickets'));
    }

    public function create()
    {
        $user = Auth::user();

        if ($user && $user->hasRole('Admin')) {
            return redirect()->route('tickets.index')->with('info', 'Admin operators manage tickets from the queue and solve them directly.');
        }

        $categories = Category::orderBy('name')->get();

        return view('tickets.create', compact('categories'));
    }

    public function store(StoreTicketRequest $request)
    {
        $user = Auth::user();

        if (! $user) {
            abort(403, 'You must be logged in to create a ticket.');
        }

        if ($user->hasRole('Admin')) {
            abort(403, 'Admin operators do not create tickets.');
        }

        $data = $request->validated();
        $category = Category::findOrFail($data['category_id']);

        $lastTicket = Ticket::latest('id')->first();
        $nextId = $lastTicket ? $lastTicket->id + 1 : 1;
        $ticketNumber = 'GST-' . str_pad($nextId, 6, '0', STR_PAD_LEFT);

        $ticket = Ticket::create([
            'ticket_number' => $ticketNumber,
            'subject' => $data['subject'],
            'description' => $data['description'],
            'category_id' => $data['category_id'],
            'department_id' => $category->department_id ?? 1,
            'sla_policy_id' => $category->sla_policy_id ?? null,
            'priority' => $data['priority'],
            'requester_id' => $user->id,
            'status' => 'new',
            'source' => 'portal',
        ]);

        return redirect()
            ->route('tickets.index')
            ->with('success', 'Ticket created successfully. Reference: ' . $ticket->ticket_number);
    }

    public function show(Ticket $ticket)
    {
        $user = Auth::user();

        if (! $user) {
            abort(403, 'You must be logged in to view this ticket.');
        }

        if (! $user->hasRole('Admin') && ! $user->hasRole('Staff Member') && $ticket->requester_id !== $user->id) {
            abort(403, 'You are not allowed to view this ticket.');
        }

        $ticket->load(['category', 'requester']);

        return view('tickets.show', compact('ticket'));
    }

    public function updateStatus(Request $request, Ticket $ticket)
    {
        $user = Auth::user();

        if (! $user || (! $user->hasRole('Admin') && ! $user->hasRole('Staff Member'))) {
            abort(403, 'Only operators can update ticket status.');
        }

        $status = $request->validate([
            'status' => ['required', 'string', 'in:new,seen,in_progress,pending_requester,resolved,done,closed'],
        ])['status'];

        $ticket->update(['status' => $status]);

        $label = match ($status) {
            'seen' => 'seen by the operator',
            'in_progress' => 'marked as in progress',
            'pending_requester' => 'marked as pending',
            'done', 'resolved' => 'marked as done',
            'closed' => 'closed',
            default => 'updated',
        };

        return back()->with('success', 'Ticket has been ' . $label . '.');
    }
}
