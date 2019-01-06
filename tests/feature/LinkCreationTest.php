<?php

use App\Link;
use Carbon\Carbon;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class LinkCreationTest extends TestCase
{
    public function test_fails_if_no_url_given()
    {
        $this->json('POST', '/')
             ->notSeeInDatabase('links', [
                'code' => '1'
            ])
            ->seeJson(['url' => ['Please enter a URL to shorten.']])
            ->assertResponseStatus(422);
    }

    public function test_fails_if_url_is_invalid()
    {
            $this->json('POST', '/', [
                'url' => 'http://google^&$$^&*^',
            ])
            ->notSeeInDatabase('links', [
                'code' => '1'
            ])
            ->seeJson(['url' => ['Hmm, that doesn\'t look like a valid URL.']])
            ->assertResponseStatus(422);
    }

    public function test_link_can_be_shortened()
    {
        $this->json('POST', '/', [
            'url' => 'http://www.google.com'
        ])
        ->seeInDatabase('links', [
            'original_url' => 'http://www.google.com',
            'code' => '1'
        ])
        ->seeJson([
            'data' => [
                'original_url' => 'http://www.google.com',
                'shortened_url' => env('CLIENT_URL') . '1',
                'code' => '1'
            ]
        ])
        ->assertResponseStatus(200);
    }

    public function test_link_is_only_shortened_once()
    {
        $link = 'http://www.google.com';

        $this->json('POST', '/', ['url' => $link]);
        $this->json('POST', '/', ['url' => $link]);

        $link = Link::where('original_url', $link)->get();

        $this->assertEquals(1, $link->count());
    }

    public function test_requested_count_is_incremented()
    {
        $link = 'http://www.google.com';

        $this->json('POST', '/', ['url' => $link]);
        $this->json('POST', '/', ['url' => $link]);

        $this->seeInDatabase('links', [
            'original_url' => $link,
            'requested_count' => 2
        ]);
    }
}
