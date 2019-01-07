<?php

namespace App\Http\Controllers;

use Cache;
use App\Link;
use Illuminate\Http\Request;

class LinkStatsController extends Controller
{
    public function show(Request $request)
    {
        $code = $request->get('code');

        $link = Cache::remember("stats.{$code}", 10, function () use ($code) {
            return Link::byCode($code)->firstOrFail();
        });

        return $this->linkResponse($link, [
            'requested_count' => (int) $link->requested_count,
            'used_count' => (int) $link->used_count,
            'created_at' => $link->created_at->toDateTimeString(),
            'updated_at' => $link->updated_at->toDateTimeString()
        ]);
    }
}
