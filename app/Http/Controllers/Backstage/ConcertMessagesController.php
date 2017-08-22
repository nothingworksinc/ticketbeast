<?php

namespace App\Http\Controllers\Backstage;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ConcertMessagesController extends Controller
{
    public function create($id)
    {
        $concert = Auth::user()->concerts()->findOrFail($id);

        return view('backstage.concert-messages.new', ['concert' => $concert]);
    }

    public function store($id)
    {
        $concert = Auth::user()->concerts()->findOrFail($id);

        $message = $concert->attendeeMessages()->create(request(['subject', 'message']));

        return redirect()->route('backstage.concert-messages.new', $concert)
            ->with('flash', "Your message has been sent.");
    }
}
