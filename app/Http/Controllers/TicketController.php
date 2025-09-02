<?php

namespace App\Http\Controllers;

use App\Http\Requests\TicketStoreRequest;
use App\Http\Resources\TicketResource;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class TicketController extends Controller
{
    public function store(TicketStoreRequest $request)
    {
        $data       = $request->validated();
        DB::beginTransaction();

        try {
            $ticket                 = new Ticket;
            $ticket->user_id        = auth()->user()->id;
            $ticket->code           = 'TIC-' . rand(10000, 9999);
            $ticket->title          = $data['title'];
            $ticket->description    = $data['description'];
            $ticket->priority       = $data['priority'];
            $ticket->save();

            DB::commit();

            return response()->json([
                    'message' => 'Ticket success',
                    'data'    => new TicketResource($ticket)
                ], 201);
        } catch (Exception $e) {
                DB::rollBack();

                return response()->json([
                    'message' => 'Ticket Error',
                    'data'    => null
                ], 500);
        }
    }
}
