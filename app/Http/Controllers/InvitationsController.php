<?php

namespace App\Http\Controllers;

use App\Invitation;
use Illuminate\Http\Request;

class InvitationsController extends Controller
{
    public function show($code)
    {
        $invitation = Invitation::findByCode($code);

        return view('invitations.show', [
            'invitation' => $invitation,
        ]);
    }
}
