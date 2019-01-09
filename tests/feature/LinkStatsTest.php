<?php

use App\Link;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class LinkStatsTest extends TestCase
{
    public function test_link_stats_can_be_shown_by_shortened_code()
    {
        $link = factory(Link::class)->create([
            'requested_count' => 5,
            'used_count' => 200
        ]);

        $this->json('GET', '/stats' ,[
            'code' => $link->code
        ])->seeJson($this->expectedJson($link));
    }

    public function test_link_stats_fails_if_not_found_code()
    {
        $this->json('GET', '/stats' ,[
            'code' => 'abc'
        ])->assertResponseStatus(404);
    }

    protected function expectedJson(Link $link)
    {
        return [
            'original_url' => $link->original_url,
            'shortened_url' => $link->shortenedUrl(),
            'code' => $link->code,
            'requested_count' => $link->requested_count,
            'used_count' => $link->used_count,
            'last_requested' => $link->last_requested->toDateTimeString(),
            'last_used' => $link->last_used ? $link->last_used->toDateTimeString() : null
        ];
    }
}
