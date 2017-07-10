<?php

namespace App\Http\Controllers\Backstage;

use App\Concert;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PublishedConcertsController extends Controller
{
    public function store()
    {
        $concert = Concert::find(request('concert_id'));

        if ($concert->isPublished()) {
            abort(422);
        }

        $concert->publish();
        return redirect()->route('backstage.concerts.index');
    }
}
