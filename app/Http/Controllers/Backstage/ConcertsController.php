<?php

namespace App\Http\Controllers\Backstage;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ConcertsController extends Controller
{
    public function create()
    {
        return view('backstage.concerts.create');
    }
}
