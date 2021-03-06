<?php

use App\Link;
use Carbon\Carbon;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class LinkShowTest extends TestCase
{
    public function test_requested_link_details_are_returned()
    {
        $link = factory(Link::class)->create();

        $this->json('GET', '/', [
            'code' => $link->code
        ])
        ->seeJson([
            'original_url' => $link->original_url,
            'shortened_url' => $link->shortenedUrl(),
            'code' => $link->code
        ])
        ->assertResponseStatus(200);
    }

    public function test_throws_404_if_no_link_found()
    {
        $this->json('GET', '/', ['code' => 'abc'])
             ->assertResponseStatus(404);
    }

    public function test_used_count_is_incremented()
    {
        $link = factory(Link::class)->create();

        $this->json('GET', '/', ['code' => $link->code]);
        $this->json('GET', '/', ['code' => $link->code]);
        $this->json('GET', '/', ['code' => $link->code]);

        $this->seeInDatabase('links', [
            'original_url' => $link->original_url,
            'used_count' => 3
        ]);
    }

    public function test_last_used_date_is_updated()
    {
        Link::flushEventListeners();

        $link = factory(Link::class)->create([
            'last_used' => Carbon::now()->subDays(2)
        ]);

        $this->json('GET', '/', ['code' => $link->code])
             ->seeInDatabase('links', [
                 'original_url' => $link->original_url,
                 'last_used' => Carbon::now()->toDateTimeString()
             ]);
    }
}
