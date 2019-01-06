<?php

namespace App\Http\Controllers;

use App\Link;
use Illuminate\Http\Request;

class LinkController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request, [
            'url' => 'required|url',
        ], [
            'url.required' => 'Please enter a URL to shorten.',
            'url.url' => 'Hmm, that doesn\'t look like a valid URL.'
        ]);
    }
}
